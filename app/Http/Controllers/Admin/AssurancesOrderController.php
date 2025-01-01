<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assurance;
use App\Models\AssuranceOrder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AssurancesOrderController extends Controller
{


    public function index()

    {
        $assurance = Assurance::all();

        return view('dashboard.assurances.orders', ['assurance' => $assurance]);
    }


    public function list(Request $request)

    {
        $assuranceOrders = AssuranceOrder::with(['assurance', 'user']);

        if ($request->has('assurance') && $request->assurance !== null) {
            $assuranceOrders->where('assurance_id', $request->assurance);
        }

//        if ($request->has('status') && $request->status !== null) {
//            $assuranceOrders->whereIn('status', $request->status);
//        }


        if ($request->has('payment_status') && $request->payment_status !== null) {
            if ($request->payment_status == 1) {
                // Check for payments with status = 1 (paid)
                $assuranceOrders->whereHas('payment', function ($q) {
                    $q->where('status', 1);
                });
            } elseif ($request->payment_status == 2) {
                // Check for payments with status = 2
                $assuranceOrders->whereHas('payment', function ($q) {
                    $q->where('status', 2);
                });
            }
        }


        $assuranceOrders = $assuranceOrders->get();

        return DataTables::of($assuranceOrders)
            ->editColumn('assurance', function ($item) {
                return $item->assurance->title;
            })
            ->editColumn('user', function ($item) {
                return '<img src="' . $item->user->getAvatar() . '" alt="avatar" id="add-avatar-img" class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer m-2" height="50" width="50" />
                      ' . $item->user->name;
            })
            ->addColumn('status', function ($item) {
                // Get the status text (to be displayed in the table)
                $statusText = StatusesAssurance($item->status);

                // Get the badge class based on the status
                $badgeClass = OrdorClass($item->status);

                // Create the select dropdown for status change
                $statusSelect = '<select class="status-select select2 form-control d-inline-block" data-id="' . $item->id . '" style="width: auto;">';
                $statusSelect .= '<option selected disabled>' . trans('main.change') . '</option>';

                // Loop through all statuses and mark the current status as selected
                foreach (StatusesAssurance() as $key => $value) {
                    // Check if the key matches the current status
                    $selected = ($key == $item->status) ? 'selected' : '';

                    // Check if the key is less than the current status, and disable it
                    $disabled = ($key < $item->status) ? 'disabled' : '';

                    $statusSelect .= '<option value="' . $key . '" ' . $selected . ' ' . $disabled . '>' . $value . '</option>';
                }


                $statusSelect .= '</select>';

                // Return the status badge and the select dropdown for display
                return '<div class="d-inline-block m-1"><span class="badge badge-glow ' . $badgeClass . '">' . $statusText . '</span></div>' . $statusSelect;
            })
//            ->editColumn('payment', function ($item) {
//                if ($item->payment()->count() > 0) {
//                    $statusText = paymentStatus($item->payment->status);
//                    $badgeClass = OrdorClass($item->payment->status);
//                    return '<div class="d-inline-block m-1"><span class="badge badge-glow ' . $badgeClass . '">' . $statusText . '</span></div>';
//                } else {
//                    return '     <div class="d-inline-block m-1"><span class="badge badge-glow ' . OrdorClass('0') . '">' . paymentStatus(0) . ' </span></div>';
//                }
//
//            })
            ->editColumn('phone', function ($item) {
                return $item->user->phone;
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y M d');
            })
            ->editColumn('action', function ($item) {
                return '


            <button type="button" class="btn btn-icon rounded-circle btn-outline-secondary waves-effect waves-float waves-light"
                    onclick="window.location.href=\'' . route('assurances.orders.view', $item->id) . '\'" title="View">
                <i class="fa fa-eye text-body"></i>
            </button>

            <button type="button" class="btn btn-icon rounded-circle btn-outline-secondary waves-effect waves-float waves-light"
                    id="delete" route="' . route('assurances.orders.delete') . '" model_id="' . $item->id . '" data-toggle="modal" title="Delete">
                <i class="fa fa-trash text-body"></i>
            </button>
        ';
            })


            ->addIndexColumn()
            ->rawColumns(['action', 'assurance', 'user', 'status', 'phone', 'created_at'])
            ->make(true);
    }


    public function view($id)
    {
        $order = AssuranceOrder::with(['assurance', 'user', 'payment'])->find($id);
        return view('dashboard.assurances.order-view', ['order' => $order]);
    }


    public function print($id)
    {
        $order = AssuranceOrder::with(['assurance', 'user', 'payment'])->find($id);
        return view('dashboard.assurances.order-print', ['order' => $order]);
    }

    public function files($id)
    {
        $assuranceOrderFiles = AssuranceOrder::find($id);

        if (!$assuranceOrderFiles) {
            return response()->json(['message' => trans('messages.file-not')], 404);
        }

        $attachments = $assuranceOrderFiles->attachments;

        $files = $attachments->map(function ($attachment) {
            return [
                'title' => $attachment->title, // Adjust based on your attachment model's field
                'file' => $attachment->getFile() // Adjust based on where files are stored
            ];
        });

        // Return the response as JSON
        return response()->json(['files' => $files]);
    }


    public function destroy(Request $request)
    {
        AssuranceOrder::find($request->id)->delete();
        return response()->json(['message' => trans('messages.delete-success'), 'status' => true], 200);

    }


    public function updateStatus(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'order_id' => 'required|exists:assurance_orders,id',
                'status' => 'required|integer',
                'note' => 'nullable|string',
                'attachment' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            ]);

            $order = AssuranceOrder::with('assurance')->find($request->order_id);
            if (!$order) {
                return response()->json(['message' => 'Order not found', 'status' => false], 404);
            }

            DB::beginTransaction();

            $order->status = $request->status;
            $this->handleStatusUpdates($order, $request);

            // Handle file upload if available
            if ($request->hasFile('attachment')) {
                $this->handleFileUpload($request, $order);
            }

            $order->save();
            DB::commit();

            return response()->json(['message' => trans('messages.change-success'), 'status' => true], 200);
        } catch (\Exception $e) {
            \Log::error('Error in updateStatus: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            DB::rollBack();
            return response()->json(['message' => 'Something went wrong', 'status' => false], 500);
        }


    }


}

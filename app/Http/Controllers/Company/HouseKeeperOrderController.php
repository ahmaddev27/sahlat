<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\HouseKeeper;
use App\Models\HouseKeeperOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class HouseKeeperOrderController extends Controller
{





    public function index()
    {
        $housekeepers=HouseKeeper::where('company_id',auth('company')->id())->get();

        return view('company.housekeepers.orders',['housekeepers'=>$housekeepers]);
    }


    public function list(Request $request)
    {
        $houseKeeperOrders = HouseKeeperOrder::with(['housekeeper', 'user'])
            ->whereHas('housekeeper', function ($query) {
                $query->where('company_id', auth('company')->id());
            });

        if ($request->has('housekeeper_id') && $request->filled('housekeeper_id')) {
            $houseKeeperOrders->where('housekeeper_id', $request->housekeeper_id);
        }

        if ($request->has('payment_status') && $request->payment_status !== null) {
            if ($request->payment_status == 1) {
                // Check for payments with status = 1 (paid)
                $houseKeeperOrders->whereHas('payment', function ($q) {
                    $q->where('status', 1);
                });
            } elseif ($request->payment_status == 2) {
                // Check for payments with status = 2
                $houseKeeperOrders->whereHas('payment', function ($q) {
                    $q->where('status', 2);
                });
            }
        }

        if ($request->has('status') && $request->filled('status')) {
            $houseKeeperOrders->wherein('status', $request->status);
        }

        return DataTables::of($houseKeeperOrders)
            ->editColumn('housekeeper', function ($item) {
                return '<img src="' . $item->housekeeper->getAvatar() . '" alt="avatar" class="user-avatar icon users-avatar-shadow rounded cursor-pointer m-1" height="60" width="60" />
                ' . $item->housekeeper->name;
            })
            ->editColumn('user', function ($item) {
                return  $item->user->name;
            })
            ->addColumn('status', function ($item) {
                // Get the status text (to be displayed in the table)
                $statusText = HouseKeeperStatuses($item->status);

                // Get the badge class based on the status
                $badgeClass = OrdorClass($item->status);

                // Create the select dropdown for status change
                $statusSelect = '<select class="status-select select2 form-control d-inline-block" data-id="' . $item->id . '" style="width: auto;" data-order-value="' . $item->value . '">';
                $statusSelect .= '<option selected disabled>' . trans('main.change') . '</option>';

                $statuses = HouseKeeperStatuses();
                $keys = array_keys($statuses);
                $currentStatusIndex = array_search($item->status, $keys);

                // Check if current status is the last one
                $isLastStatus = ($currentStatusIndex === count($keys) - 1);

                // Populate dropdown options
                foreach ($statuses as $key => $value) {
                    $selected = ($key == $item->status) ? 'selected' : '';
                    $disabled = $isLastStatus ? 'disabled' : (($key != $keys[$currentStatusIndex + 1] ?? null) ? 'disabled' : '');

                    $statusSelect .= '<option value="' . $key . '" ' . $selected . ' ' . $disabled . '>' . $value . '</option>';
                }

                $statusSelect .= '</select>';

                // Return the status badge and the select dropdown for display
                return '<div class="d-inline-block m-1"><span class="badge badge-light-' . $badgeClass . '">' . $statusText . '</span></div>' . $statusSelect;
            })

            ->editColumn('details', function ($item) {
                return \Illuminate\Support\Str::limit($item->details, 100);
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y M d');
            })
            ->editColumn('action', function ($item) {
                return '
            <button type="button" class="btn btn-icon rounded-circle btn-outline-secondary waves-effect waves-float waves-light"
                onclick="window.location.href=\'' . route('company.housekeepers.orders.view', $item->id) . '\'" title="View">
                <i class="fa fa-eye text-body"></i>
            </button>





            <button type="button" class="btn btn-icon rounded-circle btn-outline-secondary waves-effect waves-float waves-light"
                id="delete" route="' . route('company.housekeepers.orders.delete') . '" model_id="' . $item->id . '" data-toggle="modal" title="Delete">
                <i class="fa fa-trash text-body"></i>
            </button>
        ';
            })
            ->editColumn('payment', function ($item) {
                if ($item->payment()->count() > 0) {
                    $statusText = paymentStatus($item->payment->status);
                    $badgeClass = OrdorClass($item->payment->status);
                    return '<div class="d-inline-block m-1"><span class="badge badge-glow ' . $badgeClass . '">' . $statusText . '</span></div>';
                } else {
                    return '<div class="d-inline-block m-1"><span class="badge badge-glow ' . OrdorClass(0) . '">' . paymentStatus(0) . '</span></div>';
                }
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'housekeeper', 'user', 'status', 'details', 'payment', 'created_at'])
            ->make(true);
    }




    public function view($id)
    {

        $order = HouseKeeperOrder::with(['housekeeper','user','payment'])->find($id);

        if ($order->housekeeper->company_id == auth('company')->id()){
            return view('company.housekeepers.order-view',['order'=>$order]);

        }

        return redirect()->back()->withErrors(['error' => trans('messages.401')]);

    }






    public function updateStatus(Request $request)
    {
        // Validate request
        $request->validate([
            'order_id' => 'required|exists:house_keeper_orders,id',
            'status' => 'required|integer',
            'note' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);

        // Find the order
        $order = HouseKeeperOrder::with('housekeeper')->find($request->order_id);
        if (!$order) {
            return response()->json(['message' => 'Order not found', 'status' => false], 404);
        }

        // Start a database transaction to ensure atomicity
        DB::beginTransaction();

        try {
            // Update the order's status
            $order->status = $request->status;

            // Handle status-specific logic
            $this->HouseKeeperhandleStatusUpdates($order, $request);

//            // Handle file upload if available
//            if ($request->hasFile('attachment')) {
//                $this->HouseKeeperhandleFileUpload($request, $order);
//            }

            // Save the order and commit the transaction
            $order->save();
            DB::commit();

            return response()->json(['message' => trans('messages.change-success'), 'status' => true], 200);
        } catch (\Exception $e) {
            // Rollback if anything goes wrong
            DB::rollBack();

            \Log::error('Error in updateStatus: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);

            return response()->json(['message' => 'Something went wrong', 'status' => false], 500);
        }
    }



    public function destroy(Request $request)
    {
         HouseKeeperOrder::find($request->id)->delete();
         return response()->json(['message' => trans('messages.delete-success'), 'status' => true], 200);

    }


    public function sendSms(Request $request)
    {
        $request->validate([
            'orderLink' => 'required|url',
        ]);

        // Use a SMS API like Twilio, Nexmo, etc. to send the SMS
        // Example: Twilio
        try {
            $message = "Your order link: " . $request->orderLink;
            // Twilio::message($request->phone, $message);
            return response()->json(['message' => trans('messages.sms-sent')]);
        } catch (\Exception $e) {
            return response()->json(['message' => trans('messages.fail-sms')], 500);
        }
    }


}

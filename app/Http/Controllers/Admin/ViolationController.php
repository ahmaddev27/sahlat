<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Violation;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;

use Yajra\DataTables\DataTables;

class ViolationController extends Controller
{

    public function index()
    {
        return view('dashboard.violations.orders');
    }

    public function list(Request $request)
    {
        $query = Violation::with(['user']);


//        if ($request->has('status') && $request->status !== null) {
//            $query->whereIn('status', $request->status);
//        }


        if ($request->has('payment_status') && $request->payment_status !== null) {
            if ($request->payment_status == 1) {
                // Check for payments with status = 1 (paid)
                $query->whereHas('payment', function ($q) {
                    $q->where('status', 1);
                });
            } elseif ($request->payment_status == 2) {
                // Check for payments with status = 2
                $query->whereHas('payment', function ($q) {
                    $q->where('status', 2);
                });
            }
        }


        $v = $query->get();

        return DataTables::of($v)
            ->editColumn('user', function ($item) {
                return '<img src="' . $item->user->getAvatar() . '" alt="avatar" id="add-avatar-img" class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer m-1" height="60" width="60" />
                      ' . $item->user->name;
            })
            ->addColumn('status', function ($item) {
                // Get the status text and badge class
                $statusText = StatusesViolations($item->status);
                $badgeClass = OrdorClass($item->status);

                // Build the select dropdown for status change
                $statusSelect = '<select class="status-select select2 form-control d-inline-block" data-id="' . $item->id . '" data-order-value="' . $item->value . '" style="width: auto;">';
                $statusSelect .= '<option selected disabled>' . trans('main.change') . '</option>';

                $statuses = StatusesViolations();
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

                // Return the badge and select dropdown for display
                return '<div class="d-inline-block m-1"><span class="badge badge-light-' . $badgeClass . '">' . $statusText . '</span></div>' . $statusSelect;
            })


            ->editColumn('phone', function ($item) {
                return $item->user->phone;
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
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y M d ');
            })
            ->editColumn('action', function ($item) {
                return '


            <button type="button" class="btn btn-icon rounded-circle btn-outline-secondary waves-effect waves-float waves-light"
                    onclick="window.location.href=\'' . route('violations.view', $item->id) . '\'" title="View">
                <i class="fa fa-eye text-body"></i>
            </button>



            <button type="button" class="btn btn-icon rounded-circle btn-outline-secondary waves-effect waves-float waves-light"
                    id="delete" route="' . route('violations.delete') . '" model_id="' . $item->id . '" data-toggle="modal" title="Delete">
                <i class="fa fa-trash text-body"></i>
            </button>
        ';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'user', 'status', 'phone', 'created_at'])
            ->make(true);
    }

    public function updateStatus(Request $request)
    {
        // Validate request
        $request->validate([
            'order_id' => 'required|exists:violations,id',
            'status' => 'required|integer',
//            'note' => 'nullable|string',
//            'value' => 'nullable|numeric', // For payment value
            'attachment' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);

        // Find the order
        $order = Violation::find($request->order_id);
        if (!$order) {
            return response()->json(['message' => 'Order not found', 'status' => false], 404);
        }

        // Start a database transaction to ensure atomicity
        DB::beginTransaction();

        try {
            // Update the order's status and handle related logic
            $order->status = $request->status;

            // If status is 4, add a note
            if ($request->status == 4) {
                $order->note = $request->note;
            }


            // Handle specific statuses
            $this->ViolationhandleStatusUpdates($order, $request);

            // Handle file upload if available
            if ($request->hasFile('attachment')) {
                $this->ViolationhandleFileUpload($request, $order);
            }

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

    public function view($id)
    {

        $order = Violation::with(['attachments', 'user', 'payment'])->find($id);
        return view('dashboard.violations.order-view', ['order' => $order]);

    }


   public function destroy(Request $request){
    try {
        $violation = Violation::findOrFail($request->id);
        $violation->delete();
        return response()->json(['message' => trans('messages.delete-success'), 'status' => true], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['message' => trans('messages.not-found'), 'status' => false], 404);
    } catch (\Exception $e) {
        \Log::error('Error in destroy method: ' . $e->getMessage(), [
            'exception' => $e,
            'request' => $request->all(),
        ]);
        return response()->json(['message' => 'Something went wrong', 'status' => false], 500);
    }
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

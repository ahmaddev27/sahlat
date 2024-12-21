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

    public function under_payed ()
    {
        return view('dashboard.violations.under_payed');
    }


    public function payed ()
    {
        return view('dashboard.violations.payed');
    }

    public function completed ()
    {
        return view('dashboard.violations.completed');
    }



    public function cancelled()
    {
        return view('dashboard.violations.cancelled');
    }


    public function list(Request $request)
    {
        $query = Violation::with(['user']);



        if ($request->has('status') && $request->status !== null) {
            $query->whereIn('status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status !== null) {
            if ($request->payment_status == 1) {
                $query->whereHas('payment');
            } elseif ($request->payment_status == 0) {
                $query->whereDoesntHave('payment');
            }
        }


        $v = $query->get();

        return DataTables::of($v)

            ->editColumn('user', function ($item) {
                return '<img src="' . $item->user->getAvatar() . '" alt="avatar" id="add-avatar-img" class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer m-1" height="60" width="60" />
                      ' . $item->user->name;
            })

            ->addColumn('status', function ($item) {
                // Get the status text (to be displayed in the table)
                $statusText = StatusesViolations($item->status);

                // Get the badge class based on the status
                $badgeClass = OrdorClass($item->status);

                // Create the select dropdown for status change
                $statusSelect = '<select class="status-select select2 form-control d-inline-block" data-id="' . $item->id . '" style="width: auto;">';
                $statusSelect .= '<option selected disabled>' . trans('main.change') . '</option>';

                // Loop through all statuses and mark the current status as selected
                foreach (StatusesViolations() as $key => $value) {
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



            ->editColumn('phone', function ($item) {
                return $item->user->phone;
            })

            ->editColumn('payment', function ($item) {
                if($item->payment()->count()>0){
                    $statusText = paymentStatus($item->payment->status);
                    $badgeClass = OrdorClass($item->payment->status);
                    return  '<div class="d-inline-block m-1"><span class="badge badge-glow ' . $badgeClass . '">' . $statusText . '</span></div>';
                }else{
                    return  '     <div class="d-inline-block m-1"><span class="badge badge-glow '.OrdorClass('0').'">'.paymentStatus(0).' </span></div>';
                }

            })

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
            ->rawColumns(['action', 'user', 'status', 'phone', 'created_at','payment'])
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

        $order = Violation::with(['attachments', 'user','payment'])->find($id);
        return view('dashboard.violations.order-view',['order'=>$order]);

    }


    public function print($id)
    {

        $order = Violation::with(['user', 'payment'])->findOrFail($id);

        return view ('dashboard.violations.print', ['order'=>$order]);
    }


    public function destroy(Request $request)
    {
        $violations = Violation::find($request->id);

            $violations->delete();
            return response()->json(['message' => trans('messages.delete-success'), 'status' => true], 200);

    }


}

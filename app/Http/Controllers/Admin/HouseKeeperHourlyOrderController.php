<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Models\AssuranceOrder;
use App\Models\Company;
use App\Models\HouseKeeper;
use App\Models\HouseKeeperHourlyOrder;
use App\Models\HouseKeeperOrder;
use App\Models\Notification;
use App\Models\OrderAttachment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class HouseKeeperHourlyOrderController extends Controller{
    public function index()
    {
        $companies = Company::all();
        return view('dashboard.housekeepers.hourly-orders', ['companies' => $companies]);
    }


    public function list(Request $request)
    {
        $companyId = $request->get('company_id');
        $housekeeperId = $request->get('housekeeper_id');

        $query = HouseKeeperHourlyOrder::with(['company', 'user']);

        if ($companyId) {
            $query->where('company_id', $companyId);

        }
        if ($housekeeperId) {
            $query->where('house_keeper_id', $housekeeperId);
        }


//        if ($request->has('status') && $request->status !== null) {
//            $query->where('status', $request->status);
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


        $houseKeeper = $query->get();


        return DataTables::of($houseKeeper)
            ->editColumn('company', function ($item) {
                return '<img src="' . $item->company->getAvatar() . '" alt="avatar" id="add-avatar-img" class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer m-1" height="60" width="60" />
                              ' . $item->company->name;
            })
            ->editColumn('user', function ($item) {
                return '<img src="' . $item->user->getAvatar() . '" alt="avatar" id="add-avatar-img" class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer m-1" height="60" width="60" />
                              ' . $item->user->name;
            })
            ->addColumn('status', function ($item) {
                // Get the status text and badge class
                $statusText = HouseKeeperHourlyStatuses($item->status);
                $badgeClass = OrdorClass($item->status);

                // Build the select element and add the order value as a data attribute
                $statusSelect = '<select class="status-select select2 form-control d-inline-block" data-company-id="' . $item->company_id . '" data-id="' . $item->id . '" data-order-value="' . $item->value . '" style="width: auto;">';
                $statusSelect .= '<option selected disabled>' . trans('main.change') . '</option>';

                // Loop through all statuses and mark the current status as selected or disabled if needed
                foreach (HouseKeeperHourlyStatuses() as $key => $value) {
                    $selected = ($key == $item->status) ? 'selected' : '';
                    $disabled = ($key < $item->status) ? 'disabled' : '';
                    $statusSelect .= '<option value="' . $key . '" ' . $selected . ' ' . $disabled . '>' . $value . '</option>';
                }

                $statusSelect .= '</select>';

                // Return the status badge along with the select dropdown
                return '<div class="d-inline-block m-1"><span class="badge badge-glow ' . $badgeClass . '">' . $statusText . '</span></div>' . $statusSelect;
            })

            ->editColumn('date', function ($item) {
                return $item->date->format('Y M d');
            })
            ->editColumn('houseKeeper', function ($item) {
                return $item->housekeeper?->name;
            })
            ->editColumn('action', function ($item) {
                return '
                  <a  href="' . route('housekeepers.HourlyOrders.view', $item->id) . '" class="btn btn-icon btn-outline-secondary rounded-circle waves-effect waves-float waves-light edit-housekeeper">
            <i class="fa fa-eye text-body"></i>
        </a>



        <button type="button" class="btn btn-icon btn-outline-secondary rounded-circle waves-effect waves-float waves-light"
                id="delete" route="' . route('housekeepers.HourlyOrders.delete') . '" model_id="' . $item->id . '" data-toggle="modal" title="delete">
            <i class="fa fa-trash text-body"></i>
        </button>
    ';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'company', 'payment', 'user', 'status', 'created_at'])
            ->make(true);

    }


    public function view($id)
    {

        $order = HouseKeeperHourlyOrder::with(['housekeeper', 'user', 'payment'])->find($id);
        return view('dashboard.housekeepers.hourly-order-view', ['order' => $order]);

    }



    public function getHousekeepers($companyId)
    {
        $housekeepers = Housekeeper::where('company_id', $companyId)->get();

        return response()->json([
            'housekeepers' => $housekeepers
        ]);
    }



    public function destroy(Request $request)
    {
        try {
            $order = HouseKeeperHourlyOrder::findOrFail($request->id);
            $order->delete();
            return response()->json(['message' => trans('messages.delete-success'), 'status' => true], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => trans('messages.not-found'), 'status' => false], 404);
        } catch (\Exception $e) {
            \Log::error('Error in destroy: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Something went wrong', 'status' => false], 500);
        }
    }


    public function updateStatus(Request $request)
    {
        // Validate the request
        $request->validate([
            'order_id' => 'required|exists:house_keeper_hourly_orders,id',
            'status' => 'required|integer',
            'housekeeper_id' => 'nullable|exists:housekeepers,id',
            'note' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);

        // Find the order
        $order = HouseKeeperHourlyOrder::with('housekeeper')->find($request->order_id);
        if (!$order) {
            return response()->json(['message' => 'Order not found', 'status' => false], 404);
        }

        // Begin transaction
        DB::beginTransaction();

        try {
            // Update order's status
            $order->status = $request->status;

            // Handle status-specific logic
            $this->handleHourlyOrderStatus($order, $request);

            // Handle file upload
            if ($request->hasFile('attachment')) {
                $this->handleHourlyOrderFileUpload($request, $order);
            }

            // Save the order and commit the transaction
            $order->save();
            DB::commit();

            return response()->json(['message' => trans('messages.change-success'), 'status' => true], 200);
        } catch (\Exception $e) {
            // Rollback if something goes wrong
            DB::rollBack();

            \Log::error('Error in updateStatus: ' . $e->getMessage(), [
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

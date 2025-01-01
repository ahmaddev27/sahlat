<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Models\AssuranceOrder;
use App\Models\Company;
use App\Models\HouseKeeper;
use App\Models\HouseKeeperOrder;
use App\Models\Notification;
use App\Models\OrderAttachment;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class HouseKeeperOrderController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return view('dashboard.housekeepers.orders', ['companies' => $companies]);
    }



    public function list(Request $request)
    {
        $companyId = $request->get('company_id');
        $housekeeperId = $request->get('housekeeper_id');
        $dateFilter = $request->get('date');

        $query = HouseKeeperOrder::with(['housekeeper', 'user']);

        if ($companyId) {
            $query->whereHas('housekeeper', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        }

        if ($housekeeperId) {
            $query->where('housekeeper_id', $housekeeperId);
        }


        if ($request->has('status') && $request->status !== null) {
            $query->whereIn('status', $request->status);
        }

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


        // Check if a date filter is present
        if ($dateFilter) {
            $oneMonthAgo = Carbon::now()->subMonth();

            $query->where('sing_date', '<', $oneMonthAgo);
        }

        $houseKeeper = $query->get();


        return DataTables::of($houseKeeper)
            ->editColumn('housekeeper', function ($item) {
                return '<img src="' . $item->housekeeper->getAvatar() . '" alt="avatar" id="add-avatar-img" class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer m-1" height="60" width="60" />
                              ' . $item->housekeeper->name;
            })
            ->editColumn('user', function ($item) {
                return '<img src="' . $item->user->getAvatar() . '" alt="avatar" id="add-avatar-img" class="user-avatar icon users-avatar-shadow rounded-circle cursor-pointer m-1" height="60" width="60" />
                              ' . $item->user->name;
            })
            ->addColumn('status', function ($item) {
                // Get the status text (to be displayed in the table)
                $statusText = HouseKeeperStatuses($item->status);

                // Get the badge class based on the status
                $badgeClass = OrdorClass($item->status);

                // Create the select dropdown for status change
                $statusSelect = '<select class="status-select select2 form-control d-inline-block" data-id="' . $item->id . '" style="width: auto;">';
                $statusSelect .= '<option selected disabled>' . trans('main.change') . '</option>';

                // Loop through all statuses and mark the current status as selected
                foreach (HouseKeeperStatuses() as $key => $value) {
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
            ->editColumn('details', function ($item) {
                return str_limit($item->details, 100);
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y M d ');
            })
            ->editColumn('action', function ($item) {
                return '
                  <a  href="' . route('housekeepers.orders.view', $item->id) . '" class="btn btn-icon btn-outline-secondary rounded-circle waves-effect waves-float waves-light edit-housekeeper"
        >
            <i class="fa fa-eye text-body"></i>
        </a>

        <button type="button" class="btn btn-icon btn-outline-secondary rounded-circle waves-effect waves-float waves-light"
                id="delete" route="' . route('housekeepers.orders.delete') . '" model_id="' . $item->id . '" data-toggle="modal" title="delete">
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
                    return '     <div class="d-inline-block m-1"><span class="badge badge-glow ' . OrdorClass('0') . '">' . paymentStatus(0) . ' </span></div>';
                }

            })
            ->addIndexColumn()
            ->rawColumns(['action', 'housekeeper', 'payment', 'user', 'status', 'details', 'created_at'])
            ->make(true);

    }


    public function view($id)
    {

        $order = HouseKeeperOrder::with(['housekeeper', 'user', 'payment'])->find($id);
        return view('dashboard.housekeepers.order-view', ['order' => $order]);

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
        HouseKeeperOrder::find($request->id)->delete();
        return response()->json(['message' => trans('messages.delete-success'), 'status' => true], 200);

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



            // Handle file upload if available
            if ($request->hasFile('payment_attachment')) {
                $this->HouseKeeperhandleFileUpload($request, $order, 'payment_attachment', 'Payment received');
            }

            if ($request->hasFile('contract_attachment')) {
                $this->HouseKeeperhandleFileUpload($request, $order, 'contract_attachment', 'Contract');
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

}

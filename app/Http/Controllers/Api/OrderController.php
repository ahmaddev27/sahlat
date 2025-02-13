<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssuranceOrderResources;
use App\Http\Resources\HouseKeeperHourlyOrderResources;
use App\Http\Resources\HouseKeeperOrderResources;
use App\Http\Resources\ViolationResources;
use App\Models\AssuranceOrder;
use App\Models\HouseKeeperHourlyOrder;
use App\Models\HouseKeeperOrder;
use App\Models\Payment;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;




class OrderController extends Controller
{

    use ApiResponseTrait;
    use ApiResponsePaginationTrait;


    public function balance()
    {
        $user = Auth::user();
        return $this->apiRespose(['balance' => $user->tabby_balance], trans('messages.success'), true, 200);
    }

    public function payTabby(Request $request)
    {


        // Validate the incoming request
        $rules = [
            'order_id' => 'required|numeric',
            'payment_value' => 'required|numeric|min:1',
            'type' => 'required|in:violation,assurance,housekeeper,housekeeper_hourly_order',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->apiRespose($validator->errors(), 'Validation failed', false, 400);
        }

        // Define the mapping of order types to their corresponding models
        $orderModels = [
            'violation' => Violation::class,
            'assurance' => AssuranceOrder::class,
            'housekeeper' => HouseKeeperOrder::class,
            'housekeeper_hourly_order' => HouseKeeperHourlyOrder::class,
        ];

        // Get the correct model based on the provided type
        $orderModel = $orderModels[$request->type] ?? null;

        if (!$orderModel) {
            return $this->apiRespose(['Invalid order type'], 'Invalid order type', false, 400);
        }

        // Retrieve the order by order_id
       $order = $orderModel::find($request->order_id);

        if (!$order) {
            return $this->apiRespose(['error'=>['Order not found']], 'Order not found', false, 404);
        }

        // Check if a payment already exists for this order
        $latestPayment = Payment::where('order_id', $order->id)->where('type', $request->type)->first();

        if ($latestPayment) {
            return $this->apiRespose(['error'=>['This order has already been paid and cannot be paid again']], 'This order has already been paid and cannot be paid again.', false, 400);
        }

        // Ensure the payment value does not exceed the order value
        if ($request->payment_value > $order->value) {
            return $this->apiRespose(['error'=>['Payment value cannot exceed the order value']], 'Payment value cannot exceed the order value.', false, 400);
        }

        DB::beginTransaction();

        try {
            // Calculate remaining amount
            $remaining_amount = $order->value - $request->payment_value;

            // Determine the new payment status
            if ($remaining_amount == 0) {
                $payment_status = 2; // Completely paid
            } elseif ($remaining_amount < $order->value) {
                $payment_status = 1; // Partly paid
            } else {
                $payment_status = 0; // Not paid
            }

            // Create a new payment record
            Payment::create([
                'user_id' => Auth::id(),
                'payment_value' => $request->payment_value,
                'order_value' => $order->value,
                'remaining_amount' => $remaining_amount,
                'status' => $payment_status,
                'type' => $request->type,
                'order_id' => $order->id,
            ]);

            /// call tabby pay function here


            // Update order status based on remaining amount
            $order->update(['status' => $payment_status]);
            $this->handleStatus($order,$request->type);

            DB::commit();
            return $this->apiRespose([], 'Payment successful', true, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error occurred while processing payment: ' . $e->getMessage());
            return $this->apiRespose([], trans('messages.error_occurred'), false, 500);
        }
    }


    public function cancelHousekeeperOrder(Request $request)
    {

        $rules = [
            'note' => ['required'],
            'order_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errorMessage = implode(" ", array_map(fn($field) => $errors[$field][0], array_keys($errors)));
            return $this->apiRespose($errors, $errorMessage, false, 400);
        }

        $order = HouseKeeperOrder::find($request->order_id);

        $order->update(['status' => 4, 'note' => $request->note]);

        return $this->apiRespose(
            []
            , trans('messages.success'), true, 200);
    }



    public function violationsRecords(Request $request)
    {

        $query = Violation::where('user_id', Auth::id());
        $perPage = $request->input('per_page', 10);
        $orders = $query->paginate($perPage);

//        if ($orders->isEmpty()) {
//            return $this->ApiResponsePaginationTrait(
//                ViolationResources::collection($orders), trans('messages.not_found'), false, 404);
//        }

        return $this->ApiResponsePaginationTrait(
            ViolationResources::collection($orders)
            , trans('messages.success'), true, 200);
    }



    public function assurancesRecords(Request $request)
    {

        $query = AssuranceOrder::where('user_id', Auth::id());
        $perPage = $request->input('per_page', 10);
        $orders = $query->paginate($perPage);


//        if ($orders->isEmpty()) {
//            return $this->ApiResponsePaginationTrait(
//                AssuranceOrderResources::collection($orders), trans('messages.not_found'), false, 404);
//        }

        return $this->ApiResponsePaginationTrait(
            AssuranceOrderResources::collection($orders)
            , trans('messages.success'), true, 200);
    }


    public function housekeepersRecords(Request $request)
    {

        $query = HouseKeeperOrder::where('user_id', Auth::id());
        $perPage = $request->input('per_page', 10);
        $orders = $query->paginate($perPage);

//        if ($orders->isEmpty()) {
//            return $this->ApiResponsePaginationTrait(
//                HouseKeeperOrderResources::collection($orders), trans('messages.not_found'), false, 404);
//        }
        return $this->ApiResponsePaginationTrait(
            HouseKeeperOrderResources::collection($orders)
            , trans('messages.success'), true, 200);
    }

    public function housekeepersHourlyRecords(Request $request)
    {

        $query = HouseKeeperHourlyOrder::where('user_id', Auth::id());
        $perPage = $request->input('per_page', 10);
        $orders = $query->paginate($perPage);

//        if ($orders->isEmpty()) {
//            return $this->ApiResponsePaginationTrait(HouseKeeperHourlyOrderResources::collection($orders), trans('messages.not_found'), false, 404);
//        }
        return $this->ApiResponsePaginationTrait(
            HouseKeeperHourlyOrderResources::collection($orders)
            , trans('messages.success'), true, 200);
    }





}





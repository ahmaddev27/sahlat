<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AssuranceOrderResources;
use App\Http\Resources\HouseKeeperHourlyOrderResources;
use App\Http\Resources\HouseKeeperOrderResources;
use App\Http\Resources\ViolationResources;
use App\Models\StripePayment;
use App\Models\TabbyPayment;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\AssuranceOrder;
use App\Models\HouseKeeperHourlyOrder;
use App\Models\HouseKeeperOrder;
use App\Models\Payment;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Stripe\PaymentIntent;
use Stripe\Stripe;


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
            return $this->apiRespose($validator->errors(), trans('messages.order failed'), false, 400);
        }

        // Define the mapping of order types to their corresponding models and resources
        $orderMappings = [
            'violation' => ['model' => Violation::class, 'resource' => ViolationResources::class],
            'assurance' => ['model' => AssuranceOrder::class, 'resource' => AssuranceOrderResources::class],
            'housekeeper' => ['model' => HouseKeeperOrder::class, 'resource' => HouseKeeperOrderResources::class],
            'housekeeper_hourly_order' => ['model' => HouseKeeperHourlyOrder::class, 'resource' => HouseKeeperHourlyOrderResources::class],
        ];

        // Get the correct model and resource based on the provided type
        $orderMapping = $orderMappings[$request->type] ?? null;

        if (!$orderMapping) {
            return $this->apiRespose(['error'=>[trans('messages.invalid type')]], trans('messages.invalid type'), false, 400);
        }

        $orderModel = $orderMapping['model'];
        $orderResource = $orderMapping['resource'];

        // Retrieve the order by order_id
        $order = $orderModel::find($request->order_id);

        if (!$order) {
            return $this->apiRespose(['error' => [trans('messages.Order not found')]], trans('messages.Order not found'), false, 404);
        }

        // Check if a payment already exists for this order
        $latestPayment = Payment::where('order_id', $order->id)->where('type', $request->type)->first();

        if ($latestPayment) {
            return $this->apiRespose(['error' => [trans('messages.payed')]], trans('messages.payed'), false, 400);
        }

        // Ensure the payment value does not exceed the order value
        if ($request->payment_value > $order->value) {
            return $this->apiRespose(['error' => [trans('messages.pay>value')]], trans('messages.pay>value'), false, 400);
        }

        DB::beginTransaction();

        try {
            // Calculate remaining amount
            $remaining_amount = $order->value - $request->payment_value;

            // Determine the new payment status
            $payment_status = $remaining_amount == 0 ? 2 : ($remaining_amount < $order->value ? 1 : 0);

            // Create a new payment record
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'payment_value' => $request->payment_value,
                'order_value' => $order->value,
                'remaining_amount' => $remaining_amount,
                'status' => $payment_status,
                'type' => $request->type,
                'is_tabby' => 1,
                'order_id' => $order->id,
            ]);

            // Call Tabby pay function here
            TabbyPayment::create([
                'payment_id' => $payment->id,
                'paymentID' => 'fromTabby_id',
                'order_id' => $order->id,
                'amount' => $request->payment_value,
            ]);

            // Update order status based on remaining amount
            $order->update(['status' => $payment_status]);
            $this->handleStatus($order, $request->type);

            DB::commit();

            // Return the appropriate resource
            return $this->apiRespose(new $orderResource($order), trans('messages.pay-success'), true, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error occurred while processing payment: ' . $e->getMessage());
            return $this->apiRespose(['error'=>[ trans('messages.error_occurred')]], trans('messages.error_occurred'), false, 500);
        }
    }


//    public function cancelHousekeeperOrder(Request $request)
//    {
//
//        $rules = [
//            'note' => ['required'],
//            'order_id' => 'required',
//        ];
//
//        $validator = Validator::make($request->all(), $rules);
//
//        if ($validator->fails()) {
//            $errors = $validator->errors()->toArray();
//            $errorMessage = implode(" ", array_map(fn($field) => $errors[$field][0], array_keys($errors)));
//            return $this->apiRespose($errors, $errorMessage, false, 400);
//        }
//
//        $order = HouseKeeperOrder::find($request->order_id);
//
//        $order->update(['status' => 4, 'note' => $request->note]);
//
//        return $this->apiRespose(
//            []
//            , trans('messages.success'), true, 200);
//    }


    public function checkOrderStatusStripe(Request $request)
    {
        $rules = [
            'payment_intent_id' => 'required',
            'order_number'      => 'required|string',
            'type'              => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->apiRespose($validator->errors(), trans('messages.order_failed'), false, 400);
        }

        $paymentIntentId = $request->input('payment_intent_id');
        $orderNumber = $request->input('order_number');
        $requestType = strtolower($request->input('type'));

        try {
            $paymentIsLive = setting('payment_is_live');
            $stripeSecretKey = $paymentIsLive
                ? setting('stripe_secret_key_live')
                : setting('stripe_secret_key_test');

            Stripe::setApiKey($stripeSecretKey);
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            // Access metadata as an associative array
            $metadata = $paymentIntent->metadata;
            $stripeOrderNumber = isset($metadata['order_number']) ? $metadata['order_number'] : null;
            $stripeType = isset($metadata['type']) ? strtolower($metadata['type']) : null;

            if (!$stripeOrderNumber || $stripeOrderNumber !== $orderNumber) {
                Log::error("Order number mismatch: Request ($orderNumber) vs Metadata ($stripeOrderNumber)");
                return $this->apiRespose(['error' => [trans('messages.mismatch')]], trans('messages.mismatch'), false, 400);
            }

            if ($stripeType !== $requestType) {
                Log::error("Type mismatch: Request ($requestType) vs Metadata ($stripeType)");
                return $this->apiRespose(['error' => [trans('messages.mismatch')]], trans('messages.mismatch'), false, 400);
            }

            $orderMappings = [
                'violation'                => ['model' => Violation::class, 'resource' => ViolationResources::class],
                'assurance'                => ['model' => AssuranceOrder::class, 'resource' => AssuranceOrderResources::class],
                'housekeeper'              => ['model' => HouseKeeperOrder::class, 'resource' => HouseKeeperOrderResources::class],
                'housekeeper_hourly_order' => ['model' => HouseKeeperHourlyOrder::class, 'resource' => HouseKeeperHourlyOrderResources::class],
            ];

            if (!array_key_exists($requestType, $orderMappings)) {
                Log::error("Invalid type provided: $requestType");
                return $this->apiRespose(['error' => [trans('messages.invalid_type')]], trans('messages.invalid_type'), false, 400);
            }

            $modelClass = $orderMappings[$requestType]['model'];
            $resourceClass = $orderMappings[$requestType]['resource'];

            $order = $modelClass::find($orderNumber);
            if (!$order) {
                Log::error("Order not found: Order Number ($orderNumber) in Model ($modelClass)");
                return $this->apiRespose(['error' => [trans('messages.order_not_found')]], trans('messages.order_not_found'), false, 404);
            }

            // Begin the database transaction
            DB::beginTransaction();

            try {
                // Update order status if payment succeeded
                if ($paymentIntent->status === 'succeeded') {
                    $order->update(['status' => 2]);
                    Log::info("Order status updated to 2 for Order Number ($orderNumber)");
                } else {
                    Log::warning("Payment not succeeded for Order Number ($orderNumber). Order status remains unchanged.");
                }

                if ($order->payment) {
                    $order->payment->update([
                        'remaining_amount' => 0,
                        'payment_value'    => $order->value,
                        'is_stripe'        => 1,
                        'status'           => 2,
                    ]);
                    $payment = $order->payment;
                } else {
                    $payment = Payment::create([
                        'type'             => $requestType,
                        'status'           => 2,
                        'order_id'         => $orderNumber,
                        'order_value'      => $order->value,
                        'payment_value'    => $paymentIntent->amount / 100,
                        'remaining_amount' => 0,
                        'user_id'          => Auth::id(),
                        'is_stripe'        => 1,
                    ]);
                }

                // Check for an existing Stripe payment record without returning early
                $existingStripePayment = StripePayment::where('type', $requestType)
                    ->where('order_id', $orderNumber)
                    ->where('paymentID', $paymentIntentId)
                    ->first();

                if (!$existingStripePayment) {
                    DB::table('stripe_payments')->insert([
                        'order_id'   => $orderNumber,
                        'type'       => $requestType,
                        'payment_id' => $payment->id,
                        'paymentID'  => $paymentIntentId,
                        'amount'     => $paymentIntent->amount / 100,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                // Commit the transaction after all operations succeed
                DB::commit();
            } catch (\Exception $e) {
                // Rollback the transaction if any operation fails
                DB::rollback();
                Log::error("Transaction failed: " . $e->getMessage(), [
                    'payment_intent_id' => $paymentIntentId,
                    'order_number'      => $orderNumber,
                    'type'              => $requestType,
                    'trace'             => $e->getTraceAsString()
                ]);
                return $this->apiRespose(['error' => ['Transaction failed']], $e->getMessage(), false, 500);
            }

            // Post-transaction handling if needed
            $this->handleStatus($order, $requestType);

            return $this->apiRespose(new $resourceClass($order), trans('messages.pay_success'), true, 200);

        } catch (\Exception $e) {
            Log::error("Exception occurred: " . $e->getMessage(), [
                'payment_intent_id' => $paymentIntentId,
                'order_number'      => $orderNumber,
                'type'              => $requestType,
                'trace'             => $e->getTraceAsString()
            ]);

            return $this->apiRespose(['error' => ['Something went wrong']], $e->getMessage(), false, 500);
        }
    }



    public function deleteFailedOrder(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'type'         => 'required|string',
        ]);

        $orderNumber = $request->input('order_number');
        $requestType = $request->input('type');

        // التحقق مما إذا كان type يشير إلى مودل صالح

        // Define the mapping of order types to their corresponding models
        $orderModels = [
            'violation' => Violation::class,
            'assurance' => AssuranceOrder::class,
            'housekeeper' => HouseKeeperOrder::class,
            'housekeeper_hourly_order' => HouseKeeperHourlyOrder::class,
        ];


        // Get the correct model based on the provided type
        $orderModel = $orderModels[$request->type] ?? null;

        if (!class_exists($orderModel)) {
            Log::error("Invalid type provided in deleteFailedOrder: $requestType");
            return $this->apiRespose(
                ['error' => ['Invalid type provided']],
                'Invalid type provided',
                false,
                400
            );
        }

        // البحث عن الطلب في المودل الصحيح
        $order = $orderModel::whereId($orderNumber)->first();
        if (!$order) {
            Log::error("Order not found in deleteFailedOrder: Order Number ($orderNumber) in Model ($orderModel)");
            return $this->apiRespose(
                ['error' => ['Order not found']],
                'Order not found',
                false,
                404
            );
        }


        // التحقق مما إذا كانت حالة الطلب 0 ليتم حذفه
        if ($order->status == 0) {
            $order->delete();
            Log::warning("Order deleted: Order Number ($orderNumber) because payment failed and status was 0");
            return $this->apiRespose(['message'=>['Order deleted successfully']], 'Order deleted successfully', true, 200);
        } else {
            Log::warning("Order deletion failed: Order Number ($orderNumber) status is not 0");
            return $this->apiRespose(
                ['error' => ['Order cannot be deleted because its status is not 0']],
                'Order cannot be deleted because its status is not 0',
                false,
                400
            );
        }
    }

}





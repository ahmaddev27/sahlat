<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssuranceOrderResources;
use App\Http\Resources\HouseKeeperHourlyOrderResources;
use App\Http\Resources\HouseKeeperOrderResources;
use App\Http\Resources\ViolationResources;
use App\Models\Assurance;
use App\Models\AssuranceOrder;
use App\Models\Company;
use App\Models\HouseKeeper;
use App\Models\HouseKeeperHourlyOrder;
use App\Models\HouseKeeperOrder;
use App\Models\Payment;
use App\Models\Violation;
use App\Models\ViolationAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class OrderController extends Controller
{

    use ApiResponseTrait;
    use ApiResponsePaginationTrait;

    public function balance()
    {
        $user = Auth::user();
        return $this->apiRespose(['balance' => $user->tabby_balance], trans('messages.success'), true, 200);
    }


    public function PayViolation(Request $request)
    {
        $rules = [
            'number_id' => ['required', 'regex:/^784-\d{4}-\d{7}-\d{1}$/'],
            'name' => 'required',
            'violation_value' => 'required',
            'payment_value' => 'required',
            'attachments' => 'array',
            'phone' => 'required|regex:/^[5][0-9]{8}$/',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,docx',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errorMessage = implode(" ", array_map(fn($field) => $errors[$field][0], array_keys($errors)));
            return $this->apiRespose($errors, $errorMessage, false, 400);
        }


        if ($request->violation_value < $request->payment_value) {
            $errors = 'payment value  is greater than  violation value';
            return $this->apiRespose(['error' => [$errors]], 'what are you doing', false, 400);
        }


        $user = Auth::user();

        DB::beginTransaction();

        $remaining_amount = $request->violation_value > $request->payment_value ? ($request->violation_value - $request->payment_value) : 0;


        try {
            $v = Violation::create([
                'number_id' => $request->number_id,
                'n_id' => '#V' . (Violation::max('id') ?? 0) + 1,
                'name' => $request->name,
                'phone' => $request->phone,
                'user_id' => Auth::id(),
                'value' => $request->violation_value,
                'details' => $request->details,
                'status' => $remaining_amount > 1 ? 1 : 2,
            ]);


            // Handle file attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filePath = $file->store('violationsAttachments', 'public');

                    $fileName = $file->getClientOriginalName();
                    $fileTitle = pathinfo($fileName, PATHINFO_FILENAME);
                    $fileType = $file->getClientOriginalExtension();

                    ViolationAttachment::create([
                        'violation_id' => $v->id,
                        'file' => $filePath,
                        'title' => $fileTitle,
                        'type' => $fileType,
                    ]);
                }
            }

            Payment::create([
                'user_id' => Auth::id(),
                'payment_value' => $request->payment_value,
//                'order_value' => $request->violation_value + (int)setting('commission'),
                'order_value' => $request->violation_value,
                'remaining_amount' => $remaining_amount,
                'status' => $remaining_amount > 1 ? 1 : 2,
                'type' => 'app',
                'violation_id' => $v->id,
            ]);


            $message = 'Your payment for order has been processed successfully';
            $image = asset('icons/payment.png');
            $this->ViolationcreateNotification($user, 'Payment Processed', $message, '', $v->id, $image);

            DB::commit();

            return $this->apiRespose(
                new ViolationResources($v),
                trans('messages.success'),
                true,
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error occurred while processing payment: ' . $e->getMessage());
            return $this->apiRespose([], trans('messages.error_occurred'), false, 500);
        }
    }

    public function assuranceOrder(Request $request)
    {
        $rules = [
            'assurance_id' => 'required|exists:assurances,id',
            'assurance_number' => 'required',
            'payment_value' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return $this->apiRespose($errors, 'Validation failed.', false, 400);
        }

        $assurance = Assurance::find($request->assurance_id);

        if (!$assurance) {
            return $this->apiRespose(['assurance_id' => ['Invalid Assurance ID']], 'Assurance not found.', false, 404);
        }

        if ($request->payment_value > $assurance->price) {
            return $this->apiRespose(
                ['payment_value' => ['Payment value exceeds assurance price']],
                'Invalid payment value.',
                false,
                400
            );
        }

        $remainingAmount = max(0, $assurance->price - $request->payment_value);

        DB::beginTransaction();

        try {
            $order = AssuranceOrder::create([
                'number_id' => Auth::user()->number_id,
                'n_id' => '#A' . ((AssuranceOrder::max('id') ?? 0) + 1),
                'name' => Auth::user()->name,
                'user_id' => Auth::id(),
                'assurance_id' => $request->assurance_id,
                'details' => $request->details,
                'assurance_number' => $request->assurance_number,
                'price' => $assurance->price,
                'status' => 1,
            ]);

            Payment::create([
                'user_id' => Auth::id(),
                'payment_value' => $request->payment_value,
                'order_value' => $assurance->price,
                'remaining_amount' => $remainingAmount,
                'status' => $remainingAmount > 0 ? 1 : 2,
                'type' => 'app',
                'assurance_order_id' => $order->id,
            ]);


            $message = 'Your payment for order has been processed successfully';
            $image = asset('icons/payment.png');
            $this->createNotification(Auth::user(), 'Payment Processed', $message, '', $order->id, $image);


            DB::commit();

            return $this->apiRespose(
                new AssuranceOrderResources($order),
                trans('messages.success'),
                true,
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiRespose(
                ['error' => [$e->getMessage()]],
                'An error occurred while processing the order.',
                false,
                500
            );
        }
    }


    public function housekeeperOrder(Request $request)
    {
        $rules = [
            'housekeeper_id' => 'required|exists:housekeepers,id',
            'payment_value' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->apiRespose($validator->errors()->toArray(), 'Validation failed.', false, 400);
        }

        $housekeeper = HouseKeeper::find($request->housekeeper_id);

        if (!$housekeeper) {
            return $this->apiRespose(
                ['housekeeper_id' => ['Invalid housekeeper ID']],
                'Housekeeper not found.',
                false,
                404
            );
        }


        $salary = $housekeeper->salary;

        if ($request->payment_value > $salary) {
            return $this->apiRespose(
                ['payment_value' => ['Payment value exceeds the housekeeper salary']],
                'Invalid payment value.',
                false,
                400
            );
        }

        $remainingAmount = max(0, $salary - $request->payment_value);

        DB::beginTransaction();

        try {
            $houseOrder = HouseKeeperOrder::create([
                'number_id' => Auth::user()->number_id,
                'n_id' => '#H' . ((HouseKeeperOrder::max('id') ?? 0) + 1),
                'name' => Auth::user()->name,
                'user_id' => Auth::id(),
                'housekeeper_id' => $request->housekeeper_id,
                'value' => $salary,
                'status' => 1,
            ]);

            $houseOrder->housekeeper->update(['status' => 1]);


            Payment::create([
                'user_id' => Auth::id(),
                'type' => 'app',
                'remaining_amount' => $remainingAmount,
                'payment_value' => $request->payment_value,
                'order_value' => $salary,
                'house_keeper_order_id' => $houseOrder->id,
                'status' => $remainingAmount > 0 ? 1 : 2,
            ]);

            $message = 'Your payment for order has been processed successfully';
            $image = asset('icons/payment.png');
            $this->HouseKeepercreateNotification(Auth::user(), 'Payment Processed', $message, '', $houseOrder->id, $image);


            DB::commit();

            return $this->apiRespose(
                new HouseKeeperOrderResources($houseOrder),
                trans('messages.success'),
                true,
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiRespose(
                ['error' => [$e->getMessage()]],
                'An error occurred while processing the order.',
                false,
                500
            );
        }
    }


    public function housekeeperHourlyOrder(Request $request)
    {
        $rules = [
            'from' => 'required|date_format:H:i',
            'to' => 'required|date_format:H:i|after:from',
            'date' => 'required|date|after_or_equal:today',
            'location' => 'required',
            'company' => 'required|exists:companies,id',
            'payment_value' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errorMessage = implode(" ", array_map(fn($field) => $errors[$field][0], array_keys($errors)));
            return $this->apiRespose($errors, $errorMessage, false, 400);
        }

        try {
            DB::beginTransaction();

            $from = Carbon::createFromFormat('H:i', $request->from);
            $to = Carbon::createFromFormat('H:i', $request->to);
            $hours = $to->diffInHours($from);

            $company = Company::findOrFail($request->company);
            $pricePerHour = $company->hourly_price;
            $totalPrice = $pricePerHour * $hours;

            if ($request->payment_value > $totalPrice) {
                return $this->apiRespose(
                    ['payment_value' => ['Payment value exceeds the total price']],
                    'Invalid payment value.',
                    false,
                    400
                );
            }

            $remainingAmount = max(0, $totalPrice - $request->payment_value);

            $houseOrder = HouseKeeperHourlyOrder::create([
                'from' => $from,
                'to' => $to,
                'date' => $request->date,
                'location' => $request->location,
                'hours' => $hours,
                'company_id' => $request->company,
                'user_id' => Auth::id(),
                'value' => $totalPrice,
                'status' => $remainingAmount > 0 ? 1 : 2,
                'n_id' => '#H_h' . ((HouseKeeperHourlyOrder::max('id') ?? 0) + 1),
            ]);

            Payment::create([
                'user_id' => Auth::id(),
                'type' => 'app',
                'remaining_amount' => $remainingAmount,
                'payment_value' => $request->payment_value,
                'order_value' => $totalPrice,
                'house_keeper_hourly_order_id' => $houseOrder->id,
                'status' => $remainingAmount > 0 ? 1 : 2,
            ]);

            $message = 'Your payment for the order has been processed successfully.';
            $image = asset('icons/payment.png');
            $this->HourlycreateNotification(
                Auth::user(),
                'Payment Processed',
                $message,
                $houseOrder->id,
                $image
            );

            DB::commit();

            return $this->apiRespose(
                new HouseKeeperHourlyOrderResources($houseOrder),
                trans('messages.success'),
                true,
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiRespose(
                ['error' => [$e->getMessage()]],
                'An error occurred while processing the order.',
                false,
                500
            );
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


    public function getHouseKeeperOrder($id, Request $request)
    {

        $query = HouseKeeperOrder::where('user_id', Auth::id())->whereId($id);
        $perPage = $request->input('per_page', 10);
        $order = $query->paginate($perPage);

        if ($order->isEmpty()) {
            return $this->apiRespose([], trans('messages.not_found'), false, 404);
        }

        return $this->ApiResponsePaginationTrait(
            HouseKeeperOrderResources::collection($order)
            , trans('messages.success'), true, 200);
    }

    public function getHourlyHouseKeeperOrder($id, Request $request)
    {

        $query = HouseKeeperOrder::where('user_id', Auth::id())->whereId($id);
        $perPage = $request->input('per_page', 10);
        $order = $query->paginate($perPage);

        if ($order->isEmpty()) {
            return $this->apiRespose([], trans('messages.not_found'), false, 404);
        }

        return $this->ApiResponsePaginationTrait(
            HouseKeeperOrderResources::collection($order)
            , trans('messages.success'), true, 200);
    }


    public function getAssuranceOrder($id, Request $request)
    {

        $query = AssuranceOrder::where('user_id', Auth::id())->whereId($id);
        $perPage = $request->input('per_page', 10);
        $order = $query->paginate($perPage);

        if ($order->isEmpty()) {
            return $this->apiRespose([], trans('messages.not_found'), false, 404);
        }

        return $this->ApiResponsePaginationTrait(
            AssuranceOrderResources::collection($order)
            , trans('messages.success'), true, 200);
    }


}





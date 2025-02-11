<?php

namespace App\Http\Controllers\Api\Orders;
use App\Http\Controllers\Api\ApiResponsePaginationTrait;
use App\Http\Controllers\Api\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\HouseKeeperHourlyOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class HouseKeeperHourlyController extends Controller
{
    use ApiResponseTrait;
    use ApiResponsePaginationTrait;
        public function housekeeperHourlyOrder(Request $request)
    {
        $rules = [
            'from' => 'required|date_format:H:i',
            'to' => 'required|date_format:H:i|after:from',
            'date' => 'required|date|after_or_equal:today',
            'location' => 'required|string|max:255',
            'company' => 'required|exists:companies,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errorMessage = implode(" ", array_map(fn($field) => $errors[$field][0], array_keys($errors)));
            return $this->apiRespose($errors, $errorMessage, false, 400);
        }

        // Ensure the user doesn't already have an active order for this housekeeper
        $existingOrder = HouseKeeperHourlyOrder::where('user_id', Auth::id())
            ->whereNotIn('status', [3, 4]) // Status either pending or closed
            ->first();

        if ($existingOrder) {
            return $this->apiRespose([], 'You already have an active order .', false, 400);
        }

        try {
            DB::beginTransaction();

            $from = Carbon::createFromFormat('H:i', $request->from);
            $to = Carbon::createFromFormat('H:i', $request->to);
            $hours = $to->diffInHours($from);

            $company = Company::findOrFail($request->company);
            $pricePerHour = $company->hourly_price;
            $totalPrice = $pricePerHour * $hours;


            $houseOrder = HouseKeeperHourlyOrder::create([
                'from' => $from,
                'to' => $to,
                'date' => $request->date,
                'location' => $request->location,
                'hours' => $hours,
                'company_id' => $request->company,
                'user_id' => Auth::id(),
                'value' => $totalPrice,
                'status' =>  0 ,
                'n_id' => '#H_h' . ((HouseKeeperHourlyOrder::max('id') ?? 0) + 1),
            ]);



            DB::commit();
            return $this->apiRespose(
                [
                    'order_id' => $houseOrder->id,
                    'type' => 'housekeeper_hourly_order',
                    'message' => 'housekeeper order created successfully. Proceed to payment.',
                ],
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


}





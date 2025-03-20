<?php

namespace App\Http\Controllers\Api\Orders;
use App\Http\Controllers\Api\ApiResponsePaginationTrait;
use App\Http\Controllers\Api\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\HouseKeeperHourlyOrderResources;
use App\Http\Resources\HouseKeeperOrderResources;
use App\Models\Company;
use App\Models\HouseKeeperHourlyOrder;
use App\Models\HouseKeeperOrder;
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
            'location' => 'required',
            'company' => 'required|exists:companies,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return $this->apiRespose($errors, trans('messages.order failed'), false, 400);
        }

        // Check if user already has an active order for this company
//        $existingOrder = HouseKeeperHourlyOrder::where('user_id', Auth::id())
//            ->where('company_id', $request->company)
//            ->whereNotIn('status', [3, 4]) // Exclude completed/cancelled orders
//            ->exists();
//
//        if ($existingOrder) {
//            return $this->apiRespose(
//                ['error' => ['You already have an active order.']],
//                'You already have an active order.',
//                false,
//                400
//            );
//        }

        try {
            DB::beginTransaction();

            // Convert time strings to Carbon instances
            $from = Carbon::createFromFormat('H:i', $request->from);
            $to = Carbon::createFromFormat('H:i', $request->to);

            // Calculate the total duration in hours (including fractions)
            $hours = $to->diffInMinutes($from) / 60;

            $company = Company::findOrFail($request->company);
            $pricePerHour = $company->hourly_price;
            $totalPrice = round($pricePerHour * $hours, 2); // Ensure proper rounding

            // Create the order
            $houseOrder = HouseKeeperHourlyOrder::create([
                'from' => $from->format('H:i'), // Store time properly
                'to' => $to->format('H:i'),
                'date' => Carbon::parse($request->date)->format('Y-m-d'), // Ensure proper date format
                'location' => $request->location,
                'hours' => $hours,
                'company_id' => $request->company,
                'user_id' => Auth::id(),
                'value' => $totalPrice,
                'status' => 0, // Pending
                'n_id' => '#H_h' . ((HouseKeeperHourlyOrder::max('id') ?? 0) + 1),
            ]);

            DB::commit();

            return $this->apiRespose(
                [
                    'order_id' => $houseOrder->id,
                    'type' => 'housekeeper_hourly_order',
                    'totalPrice' => $totalPrice,
                    'message' =>trans('messages.success'),
                ],
                trans('messages.success'),
                true,
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiRespose(
                ['error' => [$e->getMessage()]],
                'messages.error_occurred',
                false,
                500
            );
        }
    }


    public function getHourlyHouseKeeperOrder($id, Request $request)
    {

        $order = HouseKeeperHourlyOrder::where('user_id', Auth::id())->whereId($id)->first();

        if (!$order) {
            return $this->apiRespose(['error'=>[trans('messages.not_found')]], trans('messages.not_found'), false, 404);
        }

        return $this->apiRespose(
           new HouseKeeperHourlyOrderResources($order)
            , trans('messages.success'), true, 200);
    }



    public function housekeepersHourlyRecords(Request $request)
    {

        $query = HouseKeeperHourlyOrder::where('user_id', Auth::id())->whereIn('status',[2,3])->orderBy('created_at', 'desc');
        $perPage = $request->input('per_page', 5);
        $orders = $query->paginate($perPage);

//        if ($orders->isEmpty()) {
//            return $this->ApiResponsePaginationTrait(HouseKeeperHourlyOrderResources::collection($orders), trans('messages.not_found'), false, 404);
//        }
        return $this->ApiResponsePaginationTrait(
            HouseKeeperHourlyOrderResources::collection($orders)
            , trans('messages.success'), true, 200);
    }




}





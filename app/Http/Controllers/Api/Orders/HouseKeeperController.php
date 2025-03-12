<?php

namespace App\Http\Controllers\Api\Orders;
use App\Http\Controllers\Api\ApiResponsePaginationTrait;
use App\Http\Controllers\Api\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\HouseKeeperOrderResources;
use App\Models\HouseKeeper;
use App\Models\HouseKeeperOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class HouseKeeperController extends Controller
{
    use ApiResponseTrait;
    use ApiResponsePaginationTrait;

    public function housekeeperOrder(Request $request)
    {
        // Validation rules
        $rules = [
            'housekeeper_id' => 'required|exists:housekeepers,id',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->apiRespose($validator->errors()->toArray(),  trans('messages.order failed'), false, 400);
        }

        // Retrieve housekeeper
        $housekeeper = HouseKeeper::find($request->housekeeper_id);

        if (!$housekeeper) {
            return $this->apiRespose(
                ['housekeeper_id' => ['Invalid housekeeper ID']],
                'Housekeeper not found.',
                false,
                404
            );
        }

      $housekeeper = HouseKeeper::where('id', $request->housekeeper_id)->where('status', 0)->first();

        if (!$housekeeper) {
            return $this->apiRespose(['error'=> [trans('messages.housekeeper not found')]], trans('messages.housekeeper not found'), false, 400);
        }
        // Ensure the user doesn't already have an active order for this housekeeper
        $existingOrder = HouseKeeperOrder::where('user_id', Auth::id())
            ->where('housekeeper_id', $request->housekeeper_id)
            ->whereNotIn('status', [3, 4]) // Status either pending or closed
            ->first();

        if ($existingOrder) {
            return $this->apiRespose(['error'=>[ trans('messages.pending-order')]],  trans('messages.pending-order'), false, 400);
        }

        $salary = $housekeeper->salary;



        DB::beginTransaction();

        try {
            // Create a new housekeeper order
            $houseOrder = HouseKeeperOrder::create([
                'number_id' => Auth::user()->number_id,
                'n_id' => '#H' . ((HouseKeeperOrder::max('id') ?? 0) + 1),
                'name' => Auth::user()->name,
                'user_id' => Auth::id(),
                'housekeeper_id' => $request->housekeeper_id,
                'value' => $salary,
                'status' => 0, // Order created and pending
            ]);

            // Update housekeeper status to indicate they are assigned
//            $houseOrder->housekeeper->update(['status' => 1]);


            DB::commit();
            return $this->apiRespose(
                [
                    'order_id' => $houseOrder->id,
                    'type' => 'housekeeper',
                    trans('messages.success'),

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

    public function getHouseKeeperOrder($id, Request $request)
    {

        $order = HouseKeeperOrder::where('user_id', Auth::id())->whereId($id)->first();

        if ($order->isEmpty()) {
            return $this->apiRespose(['error'=>['messages.not_found']], trans('messages.not_found'), false, 404);
        }

        return $this->apiRespose(
            HouseKeeperOrderResources::collection($order)
            , trans('messages.success'), true, 200);
    }


}





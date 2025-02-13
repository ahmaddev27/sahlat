<?php

namespace App\Http\Controllers\Api\Orders;
use App\Http\Controllers\Api\ApiResponsePaginationTrait;
use App\Http\Controllers\Api\ApiResponseTrait;
use App\Http\Controllers\Controller;
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
            return $this->apiRespose($validator->errors()->toArray(), 'Validation failed.', false, 400);
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

        // Ensure the user doesn't already have an active order for this housekeeper
        $existingOrder = HouseKeeperOrder::where('user_id', Auth::id())
            ->where('housekeeper_id', $request->housekeeper_id)
            ->whereNotIn('status', [3, 4]) // Status either pending or closed
            ->first();

        if ($existingOrder) {
            return $this->apiRespose(['error'=>['You already have an active order for this housekeeper']], 'You already have an active order for this housekeeper.', false, 400);
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
            $houseOrder->housekeeper->update(['status' => 1]);


            DB::commit();
            return $this->apiRespose(
                [
                    'order_id' => $houseOrder->id,
                    'type' => 'housekeeper',
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





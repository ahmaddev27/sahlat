<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Api\ApiResponsePaginationTrait;
use App\Http\Controllers\Api\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\AssuranceOrderResources;
use App\Models\Assurance;
use App\Models\AssuranceOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;




class AssuranceController extends Controller
{

    use ApiResponseTrait;
    use ApiResponsePaginationTrait;


    public function OrderAssurance(Request $request)
    {
        // Validation rules
        $rules = [
            'assurance_id' => 'required|exists:assurances,id',
            'assurance_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return $this->apiRespose($errors, trans('messages.order failed'), false, 400);
        }

        // Find the assurance
        $assurance = Assurance::find($request->assurance_id);

        if (!$assurance) {
            return $this->apiRespose(['error' => [trans('messages.Assurance not found')]], trans('messages.Assurance not found'), false, 404);
        }

        // Ensure user doesn't already have an assurance order
        $existingOrder = AssuranceOrder::where('user_id', Auth::id())
            ->where('assurance_id', $request->assurance_id)
             ->whereNotIn('status', [3, 4]) // Status either pending or closed
            ->first();

        if ($existingOrder) {
            return $this->apiRespose(['error'=>[trans('messages.pending-order')]], trans('messages.pending-order'), false, 400);
        }



        // Begin transaction
        DB::beginTransaction();

        try {
            // Create the new order
            $order = AssuranceOrder::create([
                'number_id' => Auth::user()->number_id,
                'n_id' => '#A' . ((AssuranceOrder::max('id') ?? 0) + 1),
                'name' => Auth::user()->name,
                'user_id' => Auth::id(),
                'assurance_id' => $request->assurance_id,
                'details' => $request->details,
                'assurance_number' => $request->assurance_number,
                'value' => $assurance->price,
                'status' => 0, // Order created, awaiting processing
            ]);


            // Commit transaction
            DB::commit();
            return $this->apiRespose(
                [
                    'order_id' => $order->id,
                    'type' => 'assurance',
                    'message' => trans('messages.success'),
                ],
                trans('messages.success'),
                true,
                200
            );


        } catch (\Exception $e) {
            // Rollback transaction if there's an error
            DB::rollBack();
            return $this->apiRespose(
                ['error' => [$e->getMessage()]],
                'messages.error_occurred',
                false,
                500
            );
        }
    }



    public function assurancesRecords(Request $request)
    {

        $query = AssuranceOrder::where('user_id', Auth::id())->whereIn('status',[2,3])->orderBy('created_at', 'desc');
        $perPage = $request->input('per_page', 5);
        $orders = $query->paginate($perPage);


//        if ($orders->isEmpty()) {
//            return $this->ApiResponsePaginationTrait(
//                AssuranceOrderResources::collection($orders), trans('messages.not_found'), false, 404);
//        }

        return $this->ApiResponsePaginationTrait(
            AssuranceOrderResources::collection($orders)
            , trans('messages.success'), true, 200);
    }


    public function getAssuranceOrder($id, Request $request)
    {

        $order = AssuranceOrder::where('user_id', Auth::id())->whereId($id)->first();
        if (!$order) {
            return $this->apiRespose(['error'=>[trans('messages.not_found')]], trans('messages.not_found'), false, 404);
        }

        return $this->apiRespose(
            new AssuranceOrderResources($order)
            , trans('messages.success'), true, 200);
    }
}





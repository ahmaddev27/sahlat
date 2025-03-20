<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Api\ApiResponsePaginationTrait;
use App\Http\Controllers\Api\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\ViolationResources;
use App\Models\Violation;
use App\Models\ViolationAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ViolationController extends Controller
{

    use ApiResponseTrait;
    use ApiResponsePaginationTrait;


    public function OrderViolation(Request $request)
    {
        $rules = [
            'number_id' => ['required', 'regex:/^784-\d{4}-\d{7}-\d{1}$/'],
            'name' => 'required',
            'violation_value' => 'required|numeric|min:1',
            'attachments' => 'array',
            'phone' => 'required|regex:/^[5][0-9]{8}$/',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,docx',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return $this->apiRespose($errors, trans('messages.order failed'), false, 400);
        }


//        $existingOrder = Violation::where('user_id', Auth::id())
//            ->whereNotIn('status', [3, 4]) // Status either pending or closed
//            ->first();
//
//        if ($existingOrder) {
//            return $this->apiRespose(['error'=>['You already have an active or pending assurance order for this type.']], 'You already have an active or pending assurance order for this type.', false, 400);
//        }


        DB::beginTransaction();

        try {
            // Create Violation (Order)
            $violation = Violation::create([
                'number_id' => $request->number_id,
                'n_id' => '#V' . ((Violation::max('id') ?? 0) + 1),
                'name' => $request->name,
                'phone' => $request->phone,
                'user_id' => Auth::id(),
                'value' => $request->violation_value,
                'details' => $request->details,
                'status' => 0, // Not Payed
            ]);

            // Handle file attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filePath = $file->store('violationsAttachments', 'public');

                    ViolationAttachment::create([
                        'violation_id' => $violation->id,
                        'file' => $filePath,
                        'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                        'type' => $file->getClientOriginalExtension(),
                    ]);
                }
            }

            DB::commit();

            return $this->apiRespose(
                [
                    'order_id' => $violation->id,
                    'type' => 'violation',
                    'message' => trans('messages.success'),
                ],
                trans('messages.success'),
                true,
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error occurred while creating violation: ' . $e->getMessage());
            return $this->apiRespose([], trans('messages.error_occurred'), false, 500);
        }
    }


    public function violationsRecords(Request $request)
    {

        $query = Violation::where('user_id', Auth::id())->whereIn('status',[2,3])->orderBy('created_at', 'desc');
        $perPage = $request->input('per_page', 5);
        $orders = $query->paginate($perPage);

//        if ($orders->isEmpty()) {
//            return $this->ApiResponsePaginationTrait(
//                ViolationResources::collection($orders), trans('messages.not_found'), false, 404);
//        }

        return $this->ApiResponsePaginationTrait(
            ViolationResources::collection($orders)
            , trans('messages.success'), true, 200);
    }

    public function getViolationOrder($id, Request $request)
    {

        $order = Violation::where('user_id', Auth::id())->whereId($id)->first();

        if (!$order) {
            return $this->apiRespose(['error' => [trans('messages.messages')]], trans('messages.not_found'), false, 404);
        }

        return $this->apiRespose(
          new  ViolationResources ($order)
            , trans('messages.success'), true, 200);
    }


}





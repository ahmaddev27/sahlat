<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Api\ApiResponsePaginationTrait;
use App\Http\Controllers\Api\ApiResponseTrait;
use App\Http\Controllers\Controller;
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
            $errorMessage = implode(" ", array_map(fn($field) => $errors[$field][0], array_keys($errors)));
            return $this->apiRespose($errors, $errorMessage, false, 400);
        }

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
                    'message' => 'Violation created successfully. Proceed to payment.',
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




}





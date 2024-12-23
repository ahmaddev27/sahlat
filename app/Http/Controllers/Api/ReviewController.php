<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use GPBMetadata\Google\Api\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    use ApiResponseTrait;

    public function housekeeperReview(Request $request)
    {

        $rules = [
            'value' => 'required',
            'housekeeper_id' => 'required',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errorMessage = implode(" ", array_map(fn($field) => $errors[$field][0], array_keys($errors)));
            return $this->apiRespose($errors, $errorMessage, false, 400);
        }


        Review::create([
            'value'=>$request->value,
            'housekeeper_id'=>$request->housekeeper_id,
            'user_id'=>auth()->id()
        ]);

        return $this->apiRespose(
        [],trans('messages.success'), true, 200);
    }

}

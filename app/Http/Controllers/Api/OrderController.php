<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssuranceOrderResources;
use App\Http\Resources\HouseKeeperHourlyOrderResources;
use App\Http\Resources\HouseKeeperOrderResources;
use App\Http\Resources\ViolationResources;
use App\Models\AssuranceOrder;
use App\Models\HouseKeeperHourlyOrder;
use App\Models\HouseKeeperOrder;
use App\Models\Payment;
use App\Models\Violation;
use App\Models\ViolationAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class OrderController extends Controller
{

    use ApiResponseTrait;
    use ApiResponsePaginationTrait;


    public function PayViolation(Request $request)
    {
        $rules = [
            'number_id' => 'required',
            'name' => 'required',
//            'details' => 'required',
            'violation_number' => 'required',
            'attachments' => 'array', // Ensure attachments is an array if multiple files are uploaded
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,docx|max:2048', // Validate each file
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errorMessage = implode(" ", array_map(fn($field) => $errors[$field][0], array_keys($errors)));
            return $this->apiRespose($errors, $errorMessage, false, 400);
        }


        $v = Violation::create([
            'number_id' => $request->number_id,
            'n_id' => '#V' . (Violation::max('id') ?? 0) + 1,
            'name' => $request->name,
            'user_id' => Auth::id(),
            'details' => $request->details,
            'violation_number' => $request->violation_number,
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


        return $this->apiRespose(
            new ViolationResources($v)
            , trans('messages.success'), true, 200);
    }


    public function housekeeperHourlyOrder(Request $request)
    {

        $rules = [
            'from' => 'required',
            'to' => 'required',
            'date' => 'required',
            'location' => 'required',
            'company' => 'required',
//            'type' => 'required',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errorMessage = implode(" ", array_map(fn($field) => $errors[$field][0], array_keys($errors)));
            return $this->apiRespose($errors, $errorMessage, false, 400);
        }

        // Calcular la diferencia en horas entre 'from' y 'to'
        $from = Carbon::createFromFormat('H:i', $request->from);
        $to = Carbon::createFromFormat('H:i', $request->to);
        $hours = $to->diffInHours($from);

        $HouseOrder = HouseKeeperHourlyOrder::create([
            'from' => $from,
            'location' => $request->location,
            'to' => $to,
            'date' => $request->date,
            'hours' => $hours,
            'company_id' => $request->company,
            'user_id' => Auth::id(),
            'n_id' => '#H_h' . (HouseKeeperHourlyOrder::max('id') ?? 0) + 1,

        ]);

        Payment::create([
            'user_id' => Auth::id(),
            'status' =>1,
            'type' =>'api',
            'value' => ((double)$HouseOrder->company->hourly_price * (double)$hours) + (double)setting('commission'),
            'house_keeper_hourly_order_id' =>$HouseOrder->id,
        ]);


        return $this->apiRespose(
            new HouseKeeperHourlyOrderResources($HouseOrder)
            , trans('messages.success'), true, 200);

    }


    public function assuranceOrder(Request $request)
    {

        $rules = [
//            'number_id' => 'required',
//            'name' => 'required',
            'assurance_id' => 'required',
            'assurance_number' => 'required',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errorMessage = implode(" ", array_map(fn($field) => $errors[$field][0], array_keys($errors)));
            return $this->apiRespose($errors, $errorMessage, false, 400);
        }

        $HouseOrder = AssuranceOrder::create([
            'number_id' => Auth::user()->number_id,
            'n_id' => '#A' . (AssuranceOrder::max('id') ?? 0) + 1,
            'name' => Auth::user()->name,
            'user_id' => Auth::id(),
            'assurance_id' => $request->assurance_id,
            'details' => $request->details,
            'assurance_number' => $request->assurance_number,
            'discount' => $request->discount,
        ]);


        return $this->apiRespose(
            new AssuranceOrderResources($HouseOrder)
            , trans('messages.success'), true, 200);
    }


    public function housekeeperOrder($id)
    {


        $exsites = HouseKeeperOrder::where('housekeeper_id', $id)->where('user_id', Auth::id())->whereIn('status', [0, 1, 2, 3])->first();
        if ($exsites) {

            $errors[] = trans('messages.ordered-before');

            return $this->apiRespose(
                ['errors' => $errors],
                trans('messages.ordered-before'),
                false,
                400
            );

        } else {

            $HouseOrder = HouseKeeperOrder::create([
                'number_id' => Auth::user()->number_id,
                'n_id' => '#H' . (HouseKeeperOrder::max('id') ?? 0) + 1,
                'name' => Auth::user()->name,
                'user_id' => Auth::id(),
                'housekeeper_id' => $id,
//            'details' => $request->details,
//            'type' => $request->type,
//            'discount' => $request->discount,
            ]);


            return $this->apiRespose(
                new HouseKeeperOrderResources($HouseOrder)
                , trans('messages.success'), true, 200);
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

        $order->update(['status'=> 4,'note'=>$request->note]);

        return $this->apiRespose(
            []
            , trans('messages.success'), true, 200);
    }



    public function assurancesRecords(Request $request)
    {

        $query = AssuranceOrder::where('user_id', Auth::id());
        $perPage = $request->input('per_page', 10);
        $orders = $query->paginate($perPage);


        if ($orders->isEmpty()) {
            return $this->ApiResponsePaginationTrait(
                AssuranceOrderResources::collection($orders), trans('messages.not_found'), false, 404);
        }

        return $this->ApiResponsePaginationTrait(
            AssuranceOrderResources::collection($orders)
            , trans('messages.success'), true, 200);
    }


    public function housekeepersRecords(Request $request)
    {

        $query = HouseKeeperOrder::where('user_id', Auth::id());
        $perPage = $request->input('per_page', 10);
        $orders = $query->paginate($perPage);

        if ($orders->isEmpty()) {
            return $this->ApiResponsePaginationTrait(
                HouseKeeperOrderResources::collection($orders), trans('messages.not_found'), false, 404);
        }
        return $this->ApiResponsePaginationTrait(
            HouseKeeperOrderResources::collection($orders)
            , trans('messages.success'), true, 200);
    }
    public function housekeepersHourlyRecords(Request $request)
    {

        $query = HouseKeeperHourlyOrder::where('user_id', Auth::id());
        $perPage = $request->input('per_page', 10);
        $orders = $query->paginate($perPage);

        if ($orders->isEmpty()) {
            return $this->ApiResponsePaginationTrait(HouseKeeperHourlyOrderResources::collection($orders), trans('messages.not_found'), false, 404);
        }
        return $this->ApiResponsePaginationTrait(
            HouseKeeperHourlyOrderResources::collection($orders)
            , trans('messages.success'), true, 200);
    }


    public function violationsRecords(Request $request)
    {

        $query = Violation::where('user_id', Auth::id());
        $perPage = $request->input('per_page', 10);
        $orders = $query->paginate($perPage);

        if ($orders->isEmpty()) {
            return $this->ApiResponsePaginationTrait(
                ViolationResources::collection($orders), trans('messages.not_found'), false, 404);
        }

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




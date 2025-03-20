<?php

namespace App\Http\Resources;

use App\Models\Payment;
use Illuminate\Http\Resources\Json\JsonResource;

class ViolationResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        $Payment=Payment::where('order_id',$this->id)->where('type','violation')->first();

        if($Payment){
            $payment_value=$Payment->payment_value;
            $remaining=$Payment->remaining_amount;
        }else{
            $payment_value='0';
            $remaining='0';
        }

        return [
            'id'=>$this->id,
            'order_number'=>$this->n_id,
            'number_id'=>$this->number_id,
            'name'=>$this->name,
//            'user'=> UserResources::collection([$this->user]),
            'details'=>$this->details,
//            'violation_number'=>$this->violation_number,
            'status'=>['status'=>StatusesViolations((int)$this->status),'id'=>(int)$this->status],
            'order_value'     => (string) $this->value,
            'payment_value'    => (string) $payment_value,
            'remaining_amount' => (string) $remaining,
//            'payment_status'=>paymentStatus($this->payment?->status),
            'date'=>$this->created_at->format('Y-m-d'),
            'type'=>'violation',
            'date_ForHumans'=>$this->created_at->diffForHumans(),
        ];


    }

}

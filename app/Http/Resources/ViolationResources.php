<?php

namespace App\Http\Resources;

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
        return [
            'id'=>$this->id,
            'order_number'=>$this->n_id,
            'number_id'=>$this->number_id,
            'name'=>$this->name,
//            'user'=> UserResources::collection([$this->user]),
            'details'=>$this->details,
            'violation_number'=>$this->violation_number,
            'status'=>OrderStatus((int)$this->Status),
            'payment_value'=>$this->payment?->value,
            'payment_status'=>paymentStatus($this->payment?->status)
        ];


    }

}

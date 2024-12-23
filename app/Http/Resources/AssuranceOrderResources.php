<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssuranceOrderResources extends JsonResource
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
//            'name'=>$this->name,

            'details'=>$this->details,
            'assurance_number'=>$this->assurance_number,
            'status'=>['status'=>OrderStatus((int)$this->status),'id'=>(int)$this->status],


            'note'=>$this->note,
            'payment_value'=>$this->payment?->value,
            'payment_status'=>paymentStatus($this->payment?->status),
            'date'=>$this->created_at->format('Y-m-d'),
            'date_ForHumans'=>$this->created_at->diffForHumans(),
            'assurance'=> new  AssuranceResources($this->assurance),


        ];

    }

}

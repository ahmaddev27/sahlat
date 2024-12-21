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
            'name'=>$this->name,
//            'user'=> UserResources::collection([$this->user]),
            'details'=>$this->details,
            'date'=>$this->created_at->format('d/m/Y'),
            'assurance_number'=>$this->assurance_number,
            'status'=>OrderStatus((int)$this->Status),
            'note'=>$this->note,
            'payment_value'=>$this->payment?->value,
            'payment_status'=>paymentStatus($this->payment?->status)


        ];

    }

}

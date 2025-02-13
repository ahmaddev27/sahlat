<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HouseKeeperHourlyOrderResources extends JsonResource
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
            'order_number'=>$this->n_id,
            'from'=>$this->from->format('H:i'),
            'to'=>$this->to->format('H:i'),
            'date'=>$this->date->format('Y/m/d'),
            'hours'=>$this->hours,
            'status'=>['status'=>HouseKeeperHourlyStatuses((int)$this->status),'id'=>(int)$this->status],
            'location'=>$this->location?cities($this->location):null,
            'company'=> new CompanyResources($this->company),
            'created_date'=>$this->created_at->format('Y-m-d'),
            'date_ForHumans'=>$this->created_at->diffForHumans(),
            'order_value'=>$this->payment?->order_value,
            'payment_value'=>$this->payment?->payment_value,
            'remaining_amount'=>$this->payment?->remaining_amount,

        ];



    }

}


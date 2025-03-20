<?php

namespace App\Http\Resources;

use App\Models\Payment;
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
        $Payment=Payment::where('order_id',$this->id)->where('type','housekeeper_hourly_order')->first();

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
            'from'=>$this->from->format('H:i'),
            'to'=>$this->to->format('H:i'),
            'date'=>$this->date->format('Y/m/d'),
            'hours'=>$this->hours,
            'status'=>['status'=>HouseKeeperHourlyStatuses((int)$this->status),'id'=>(int)$this->status],

            'location'=>$this->location?cities($this->location):null,
            'company'=> new CompanyResources($this->company),
            'housekeeper'=> new HouseKeeperResources($this->housekeeper),

            'created_date'=>$this->created_at->format('Y-m-d'),
            'date_ForHumans'=>$this->created_at->diffForHumans(),
            'order_value'     => (string) $this->value,
            'payment_value'    => (string) $payment_value,
            'remaining_amount' => (string) $remaining,


//            'payment_status'=>paymentStatus($this->payment?->status),

            'type'=>'housekeeper_hourly_order',

        ];



    }

}


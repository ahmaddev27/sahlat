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
            'location'=>$this->location?cities($this->location):null,
//            'user'=> new UserResources ($this->user),


        ];



    }

}


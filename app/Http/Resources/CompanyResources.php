<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResources extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'experience' => $this->experience,
            'lat' => $this->lat,
            'long' => $this->long,
            'reviews' => $this->averageHousekeeperReview(),
            'reviews_count' => $this->reviews(),
            'address'=>$this->address?cities($this->address):null,
            'hourly_price' => $this->hourly_price,
            'avatar' => $this->getAvatar(),
            'views' => $this->views->count(),
            'housekeepers_count' => $this->housekeepers->count(),
            'bio' => $this->bio,
            'services' => $this->services,

            ];
    }


}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResources extends JsonResource
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
            'email' => $this->email,
            'name' => $this->name,
            'number_id' => $this->number_id,  // Using the formatted number_id
            'phone' => $this->phone,
            'location' => $this->location ? cities($this->location) : null,
            'gender' => gender($this->gender),
            'avatar' => $this->getAvatar(),
            'profile_status' => (int) $this->profile_status,
        ];
    }


}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsResources extends JsonResource
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
//            'title'=>$this->title,
            'title'=>$this->type!='general'? trans('notifications.'.$this->title): $this->title,

            'message'=>$this->type!='general'? trans('notifications.'.$this->message): $this->message,
            'status '=>$this->status ,
            'type'=>$this->type,
            'date'=>$this->created_at->diffforhumans(),
            'image'=>url('storage/'.$this->image),

        ];

    }

}

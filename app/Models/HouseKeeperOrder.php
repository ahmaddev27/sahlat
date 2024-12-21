<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseKeeperOrder extends Model
{
    protected $guarded=[];
    use HasFactory;

    public function housekeeper()
    {
        return $this->belongsTo(HouseKeeper::class,'housekeeper_id');
    }

    public function user()
    {
        return $this->belongsTo(AppUser::class,'user_id');
    }


    public function payment()
    {
        return $this->hasOne(Payment::class,'house_keeper_order_id');
    }


    public function orderattAchments()
    {
        return $this->hasMany(OrderAttachment::class,'house_keeper_order_id');
    }

}

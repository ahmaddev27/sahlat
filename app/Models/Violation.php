<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{

    use HasFactory;

    protected $guarded=[];



    public function user()
    {
        return $this->belongsTo(AppUser::class,'user_id');
    }

    public function attachments()
    {
        return $this->hasMany(ViolationAttachment::class);
    }

    public function orderattAchments()
    {
        return $this->hasMany(OrderAttachment::class,'violation_id');
    }


    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id')->where('type', 'violation');
    }

}


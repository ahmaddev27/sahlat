<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssuranceOrder extends Model
{
    use HasFactory;

    protected $guarded=[];


    public function assurance()
    {
        return $this->belongsTo(Assurance::class,'assurance_id');
    }

    public function user()
    {
        return $this->belongsTo(AppUser::class,'user_id');
    }



    public function attachments()
    {
        return $this->hasMany(AssurancesAttachment::class);
    }

   public function orderattAchments()
    {
        return $this->hasMany(OrderAttachment::class,'assurance_order_id');
    }


    public function payment()
    {
        return $this->hasOne(Payment::class);
    }


}

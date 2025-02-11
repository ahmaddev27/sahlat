<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseKeeperHourlyOrder extends Model
{
    protected $guarded=[];


    use HasFactory;



  protected $casts = [
      'date' => 'datetime',
      'from' => 'date',
      'to' => 'date',
  ];



    public function user()
    {
        return $this->belongsTo(AppUser::class,'user_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }


    public function payment(){
    return $this->hasOne(Payment::class, 'order_id')->where('type', 'housekeeper_hourly_order');

    }


    public function orderattAchments()
    {
        return $this->hasMany(OrderAttachment::class,'house_keeper_hourly_order_id');
    }


    public function housekeeper()
    {
        return $this->belongsTo(HouseKeeper::class,'house_keeper_id');
    }


    public function getNumberIdAttribute($value)
    {
        // Check if number_id has exactly 15 characters
        if (strlen($this->attributes['number_id'] ?? '') == 15) {
            return substr($this->attributes['number_id'], 0, 3) . '-' .
                substr($this->attributes['number_id'], 3, 4) . '-' .
                substr($this->attributes['number_id'], 7, 7) . '-' .
                substr($this->attributes['number_id'], 14, 1);
        }

        // Return the number_id as is if it's not in the expected format
        return $this->attributes['number_id'] ?? null;
    }


}

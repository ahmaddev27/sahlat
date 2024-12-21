<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseKeeper extends Model
{
    protected $guarded=[];
    use HasFactory;
    protected $table='housekeepers';

    public function company(){
        return $this->belongsTo(Company::class);
    }


    public function getAvatar()
    {
        // Check if the avatar exists, return the full URL, or return a placeholder
        return $this->avatar ? url('storage/' . $this->avatar) : url('blank.png');
    }


    public function reviews()
    {
        return $this->hasMany(Review::class,'housekeeper_id');
    }



    public function orderd()
    {
        return $this->hasMany(HouseKeeperOrder::class,'housekeeper_id');
    }


    public function Hourlyorderd()
    {
        return $this->hasMany(HouseKeeperHourlyOrder::class,'house_keeper_id');
    }



    public function averageReview()
    {
        $total = $this->reviews()->sum('value');
        $count = $this->reviews()->count();
        if ($count > 0) {
            $average = $total / $count;
            $normalizedAverage = ($average > 5) ? 5 : $average;
            return number_format($normalizedAverage, 1);
        }

        return number_format(0, 1);
    }


    public function views(){

        return $this->hasMany(HouseKeeperViewer::class,'houseKeeper_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Authenticatable
{
    protected $guarded=[];
    use HasFactory;

    public function housekeepers()
    {

        return $this->hasMany(HouseKeeper::class);
    }


    public function getAvatar(){
        if ($this->avatar){
            return url('storage/'.$this->avatar);
        }else{
            return  url('blank.png');
        }
    }



    public function views(){

        return $this->hasMany(CompanyViewer::class);
    }

    public function services(){

        return $this->hasMany(CompanyService::class);
    }



    public function reviews()
    {
        // Count all reviews from housekeepers who have reviews
        return Review::whereHas('housekeeper', function ($query) {
            $query->where('company_id', $this->id); // Assuming you have company_id in the housekeeper model
        })->count();
    }


    public function HouseKeeperOrders()
    {
        return $this->hasMany(HouseKeeperOrder::class, 'housekeeper_id') // Relationship on housekeeper_id
        ->whereHas('housekeeper', function ($query) {
            $query->where('company_id', $this->id); // Ensure housekeeper is part of the current company
        });
    }

    public function HouseKeeperHourlyOrders()
    {
        return $this->hasMany(HouseKeeperHourlyOrder::class, 'company_id');
    }


    public function averageHousekeeperReview()
    {
        // Get all housekeepers belonging to this company who have reviews
        $housekeepers = $this->housekeepers()->whereHas('reviews')->get();

        // Calculate the total and count of average reviews
        $total = 0;
        $count = 0;

        foreach ($housekeepers as $housekeeper) {
            // Add each housekeeper's average review value to the total
            $total += (float) $housekeeper->averageReview();
            $count++;
        }

        // If there are any housekeepers with reviews, calculate the overall average
        if ($count > 0) {
            $average = $total / $count;
            return number_format($average, 1);
        }

        return number_format(0, 1); // Return 0 if no housekeepers have reviews
    }

}

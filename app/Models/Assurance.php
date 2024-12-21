<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assurance extends Model
{
    protected $guarded=[];
    use HasFactory;


    public function getAvatar(){
        if ($this->image){
            return url('storage/'.$this->image);
        }else{
            return  url('blank.png');
        }
    }

    public function getCompanyAvatar(){
        if ($this->company_logo){
            return url('storage/'.$this->company_logo);
        }else{
            return  url('blank.png');
        }
    }


    public function AssuranceOrders()
    {

        return $this->hasMany(AssuranceOrder::class);
    }
}

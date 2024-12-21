<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
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
}


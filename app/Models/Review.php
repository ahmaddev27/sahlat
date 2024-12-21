<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    protected $guarded=[];
    use HasFactory;

    public function housekeeper()
    {
        return $this->belongsTo(HouseKeeper::class);
    }
}

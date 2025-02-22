<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded=[];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(AppUser::class,'user_id');
    }
}



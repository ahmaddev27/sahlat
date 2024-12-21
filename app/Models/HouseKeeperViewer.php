<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseKeeperViewer extends Model
{
    protected $table='housekeeper_viewers';
    protected $guarded=[];
    use HasFactory;
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssurancesAttachment extends Model
{
    protected $guarded=[];
    use HasFactory;
    protected $table='assurance_order_attachments';

    public function getFile(){
        if ($this->file){
            return url('storage/'.$this->file);
        }else{
            return  null;
        }
    }
}


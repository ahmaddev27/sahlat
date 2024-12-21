<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViolationAttachment extends Model
{
    protected $guarded=[];
    use HasFactory;
    protected $table='violations_attachments';

    public function getFile(){
        if ($this->file){
            return url('storage/'.$this->file);
        }else{
            return  null;
        }
    }

}


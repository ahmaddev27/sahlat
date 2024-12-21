<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAttachment extends Model
{
    protected $table='orders_attachments';
    protected $guarded=[];
    use HasFactory;

    public function getFile(){
        if ($this->file){
            return url('storage/'.$this->file);
        }else{
            return  null;
        }
    }
}

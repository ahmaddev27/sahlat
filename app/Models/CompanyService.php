<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyService extends Model
{
    protected $fillable=['title','company_id'];
    protected $hidden=['company_id','created_at','updated_at'];
    use HasFactory;

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


}

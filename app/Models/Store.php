<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    public function Company()
    {
        return $this->belongsTo(Company::class,'company_id','id');
    }
}

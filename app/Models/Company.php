<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public function getCountryAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setCountryAttribute($value)
    {
        $this->attributes['country'] = json_encode($value);
    }

    public function store()
    {
        return $this->hasMany(Store::class,'company_id','id');
    }
}

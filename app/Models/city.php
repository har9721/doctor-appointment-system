<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class city extends Model
{
    use HasFactory;

    protected $table = 'cities';

    public function patients()
    {
        return $this->hasOne(Patients::class,'city_ID');    
    }

    public function person()
    {
        return $this->hasOne(Person::class,'city_ID');    
    }
}

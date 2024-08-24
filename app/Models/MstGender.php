<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstGender extends Model
{
    use HasFactory;

    protected $table = 'mst_genders';

    public function patients()
    {
        return $this->hasMany(Patients::class,'gender_ID');    
    }

    public function person()
    {
        return $this->hasOne(Person::class,'gender_ID');    
    }
}

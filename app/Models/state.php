<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class state extends Model
{
    use HasFactory;

    protected $table = 'states';

    protected $guarded = [];

    public function city()
    {
        return $this->hasOne(city::class,'state_id');    
    }
}

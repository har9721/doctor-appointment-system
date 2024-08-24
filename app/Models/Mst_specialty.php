<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mst_specialty extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'mst_specialties';

    protected $fillable = ['specialtyName','created_at'];

    public static function addSpecialty($data)
    {
        return Mst_specialty::create([
            'specialtyName' => ucfirst(trim($data['name'])),
            'created_at' => now(),
        ]);
    }

    public static function getAllSpecialty() 
    {
        return Mst_specialty::where(['isActive' => 1, 'isDeleted' => 0])->latest('id','desc')->get(['id','specialtyName'])->toArray();
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class,'specialty_ID');
    }
}

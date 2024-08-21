<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientsLifeStyleInformation extends Model
{
    use HasFactory;

    protected $table = 'patients_life_style_information';

    public $timestamps = false;

    protected $fillable = ['patient_ID','smokingStatus_ID','alcoholStatus_ID','exercise','createdBy'];

    public static function addLifeStyleInformation($data)
    {
        $addLifeStyleInfo = PatientsLifeStyleInformation::create([
            'patient_ID' => $data['patient_ID'],
            'smokingStatus_ID' => $data['smoking_status'],
            'alcoholStatus_ID' => $data['alcohol_status'],
            'exercise' => $data['exercise'],
            'createdBy' => $data['patient_ID']
        ]);  
        
        return $addLifeStyleInfo;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientsMedicalHistory extends Model
{
    use HasFactory;

    protected $table = 'patients_medical_histories';

    public $timestamps = false;

    protected $fillable = ['patient_ID','illness','surgery','allergies','chronicDisease','medication','createdBy'];

    public static function insertMedicalHistory($data)
    {
        $addMedicalHistoy = PatientsMedicalHistory::create([
            'patient_ID' => $data['patient_ID'],
            'illness' => $data['past_illness'],
            'surgery' => $data['surgeries'],
            'allergies' => $data['allergies'],
            'chronicDisease' => $data['chronic_condition'],
            'medication' => $data['medication'],
            'createdBy' => $data['patient_ID']
        ]);

        return $addMedicalHistoy;
    }
}

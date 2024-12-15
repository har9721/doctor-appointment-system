<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function patients()
    {
        return $this->belongsTo(Patients::class,'patient_ID');    
    }

    public static function updateMedicalInformation($data)
    {
        info('inside medical info:');
        return PatientsMedicalHistory::where('id',$data['medical_history_id'])
        ->update([
            'illness' => $data['past_illness'],
            'surgery' => $data['surgeries'],
            'allergies' => $data['allergies'],
            'chronicDisease' => $data['chronic_condition'],
            'medication' => $data['medication'],
            'updatedBy' => Auth::user()->id,
            'updated_at' => now()
        ]);
    }
}

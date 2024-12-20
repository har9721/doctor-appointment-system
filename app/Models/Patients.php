<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Patients extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'patients';

    public $timestamps = false;

    protected $fillable = ['user_ID','created_at'];

    public static function addPatients($data)
    {
        $addPatients = Patients::create([
            'user_ID' => $data['user_ID'],
            'created_at' => now(),
        ]);

        if(!empty($addPatients))
        {
            $data['patient_ID'] = $addPatients->id;
            $data['role'] = config('constant.patients_role_ID');

            // save other information of patients
            PatientsEmergencyContacts::addEmergencyContacts($data);
            PatientsMedicalHistory::insertMedicalHistory($data);
            PatientsLifeStyleInformation::addLifeStyleInformation($data);
        }

        return (!empty($addPatients)) ? $addPatients->id : '';
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_ID')->where('isActive',1)->select('id','first_name','last_name','email','mobile','age','address','city_ID','gender_ID','isActive');
    }

    public static function getLoginPatientsId()
    {
        return Patients::where('user_ID',Auth::user()->id)->first('id');
    }

    public function emergencyContact()
    {
        return $this->hasOne(PatientsEmergencyContacts::class,'patient_ID')->where('isActive',1)->select('id','patient_ID','contact_name','contact_relation','phone_no',);    
    }

    public function lifeStyleInformation()
    {
        return $this->hasOne(PatientsLifeStyleInformation::class,'patient_ID')->where('isActive',1)->select('id','patient_ID','smokingStatus_ID','alcoholStatus_ID','exercise');    
    }

    public function medicalHistory()
    {
        return $this->hasOne(PatientsMedicalHistory::class,'patient_ID')->where('isActive',1)->select('id','patient_ID','illness','surgery','allergies','chronicDisease','medication');    
    }

    public static function updatePaymentStatus($id,$pending_payment)
    {
        return Patients::where('id',$id)->update([
            'has_pending_payment' => $pending_payment,
            'updatedBy' => Auth::user()->id,
            'updated_at' => now()
        ]);
    }
}
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

    protected $fillable = ['person_ID','created_at'];

    public static function addPatients($data)
    {
        $addPatients = Patients::create([
            'person_ID' => $data['person_ID'],
            'created_at' => now(),
        ]);

        if(!empty($addPatients))
        {
            $data['patient_ID'] = $addPatients->id;
            $data['role'] = config('constant.patients_role_ID');

            // add entry in user table
            User::addUser($data);

            // save other information of patients
            PatientsEmergencyContacts::addEmergencyContacts($data);
            PatientsMedicalHistory::insertMedicalHistory($data);
            PatientsLifeStyleInformation::addLifeStyleInformation($data);
        }

        return (!empty($addPatients)) ? $addPatients->id : '';
    }

    public function person()
    {
        return $this->belongsTo(Person::class,'person_ID')->where('isActive',1)->select('id','first_name','last_name','email','mobile','age','address','city_ID','gender_ID','isActive');
    }

    public static function getLoginPatientsId()
    {
        return Patients::join('users','users.person_ID','patients.person_ID')->where('users.id',Auth::user()->id)->first('patients.id');
    }
}
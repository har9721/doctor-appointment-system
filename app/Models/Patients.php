<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Patients extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'patients';

    protected $fillable = ['first_name','last_name','email','password','mobile','address','city_ID','gender_ID'];

    public static function addPatients($data)
    {
        $addPatients = Patients::create([
            'first_name' => trim($data['first_name']),
            'last_name' => trim($data['last_name']),
            'email' => trim($data['email']),
            'password' => Hash::make('12345678'),
            'mobile' => trim($data['mobile']),
            'address' => trim($data['address']),
            'city_ID' => $data['city'],
            'gender_ID' => $data['gender'],
            'created_at' => now(),
        ]);

        if(!empty($addPatients))
        {
            $data['patient_ID'] = $addPatients->id;
            PatientsEmergencyContacts::addEmergencyContacts($data);
            PatientsMedicalHistory::insertMedicalHistory($data);
            PatientsLifeStyleInformation::addLifeStyleInformation($data);
        }

        return (!empty($addPatients)) ? $addPatients->id : '';
    }
}

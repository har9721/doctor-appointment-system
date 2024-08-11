<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientsEmergencyContacts extends Model
{
    use HasFactory;

    protected $table = 'patients_emergency_contacts';

    protected $fillable = ['patient_ID','contact_name','contact_relation','phone_no','createdBy'];

    public static function addEmergencyContacts($data)
    {
        $addContacts = PatientsEmergencyContacts::create([
            'patient_ID' => $data['patient_ID'],
            'contact_name' => trim($data['name']),
            'contact_relation' => trim($data['contact_relation']),
            'phone_no' => trim($data['contact_no']),
            'createdBy' => trim($data['patient_ID']),
        ]);

        return $addContacts;
    }
}

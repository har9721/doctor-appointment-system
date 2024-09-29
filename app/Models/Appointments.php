<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Appointments extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    public $timestamps = false;

    protected $fillable = ['doctorTimeSlot_ID','patient_ID','appointmentDate'];

    public static function bookPatientAppointment($data)
    {
        Appointments::create([
            'doctorTimeSlot_ID' => $data['timeSlot'],
            'patient_ID' => $data['patient_ID'],
            'appointmentDate' => date('Y-m-d',strtotime($data['date'])),
            'created_at' => now(),
            'createdBy' => Auth::user()->id
        ]);

        return DoctorTimeSlots::updateIsBookTimeSlot($data);
    }

}

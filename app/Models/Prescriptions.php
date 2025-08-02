<?php

namespace App\Models;

use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Prescriptions extends Model
{
    use HasFactory;

    protected $table = 'prescriptions';

    protected $fillable = [
        'appointment_ID',
        'doctor_ID',
        'patient_ID',
        'medicines',
        'instructions',
        'createdBy'
    ];

    public static function addPrescriptions($data)
    {
        $medicines_data = Prescriptions::frameMedicineArray($data);

        return Prescriptions::create([
            'appointment_ID' => $data['appointment_id'],
            'doctor_ID' => $data['doctor_id'],
            'patient_ID' => $data['patient_id'],
            'medicines' => json_encode($medicines_data),
            'instructions' => $data['general_instructions'],
            'createdBy' => $data['doctor_id']
        ]);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointments::class,'appointment_ID')->select('id','doctorTimeSlot_ID','status','patient_ID','appointmentDate','created_at','originalAppointmentDate','isRescheduled','archived_reason','createdBy');    
    }

    public function patients()
    {
        return $this->belongsTo(Patients::class,'patient_ID')->select('id','user_ID');    
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class,'doctor_ID')->select('id','user_ID');    
    }

    public static function getPrescriptionDetails($appointmentID)
    {
        $prescriptionDetails = Prescriptions::with([
            'appointment.doctorTimeSlot',
            'patients.user',
            'doctor.user',
        ])
        ->where('appointment_ID',$appointmentID)
        ->get();

        if(!empty($prescriptionDetails))
        {
            $prescriptions = $prescriptionDetails->map(function($details)
            {
                return [
                    'id' => $details->id,
                    'patientName' => $details->patients->user->full_name ?? '',
                    'email' => $details->patients->user->email ?? '',
                    'mobile' => $details->patients->user->mobile ?? '',
                    'doctorName' => $details->doctor->user->full_name ?? '',
                    'appointmentDate' => date('d-m-Y',strtotime($details->appointment->appointmentDate)),
                    'time' => $details->appointment->doctorTimeSlot->time,
                    'medicines' => $details->medicines ?? json_decode($details->medicines,true),
                    'instructions' => $details->instructions,
                    'appointment_no' => $details->appointment->appointment_no ?? null
                ];
            });

            return $prescriptions->first();
        }
    }

    public function getMedicinesAttribute($value)
    {
        return json_decode($value,true);
    }

    public static function updatePrescriptions($data)
    {
        $medicines = Prescriptions::frameMedicineArray($data);

        $updatePrescriptions = Prescriptions::where('id', $data['prescription_id'])
            ->update([
                'medicines' => $medicines ?? json_encode($medicines),
                'instructions' => $data['general_instructions'],
                'updatedBy' => Auth::user()->id,
            ]);

        return ($updatePrescriptions == 1) ? Prescriptions::findOrFail($data['prescription_id']) : 0;
    }

    public static function frameMedicineArray($data)
    {
        $medicines = [];

        foreach ($data['medicines'] as $index => $medicine) {
            $medicines[] = [
                'medicine' => $medicine,
                'dosage' => $data['dosage'][$index] ?? '',
                'instruction' => $data['instructions'][$index] ?? '',
            ];
        }

        return $medicines;
    }
}

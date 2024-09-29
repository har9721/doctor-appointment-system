<?php

namespace App\Observers;

use App\Jobs\sendBookingMail;
use App\Models\Appointments;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AppointmentsObserver
{
    public function created(Appointments $appointments)
    {
        $doctorDetails = Doctor::join('doctor_time_slots','doctor_time_slots.doctor_ID','doctors.id')
        ->join('person','person.id','doctors.person_ID')
        ->where('doctor_time_slots.id',$appointments->doctorTimeSlot_ID)
        ->get(['person.first_name','person.last_name','person.email','doctor_time_slots.start_time','doctor_time_slots.end_time'])->toArray();

        $emailData['doctor_name'] = (!empty($doctorDetails)) ? $doctorDetails[0]['first_name'].' '.$doctorDetails[0]['last_name'] : null;
        $emailData['doctor_email'] = (!empty($doctorDetails)) ? $doctorDetails[0]['email'] : null;
        $emailData['date'] = $appointments->appointmentDate;
        $emailData['time'] = (!empty($doctorDetails)) ? $doctorDetails[0]['start_time'] : null;

        $patientsDetails = User::where('id',Auth::user()->id)->get(['first_name','last_name','email'])->toArray();

        $emailData['patientsName'] = (!empty($patientsDetails)) ? $patientsDetails[0]['first_name'].' '.$patientsDetails[0]['last_name'] : null;
        $emailData['patientsEmail'] = (!empty($patientsDetails)) ? $patientsDetails[0]['email'] : null;
        info($emailData);

        dispatch(new sendBookingMail($emailData));
    }

    public function updated(Appointments $appointments)
    {
        //
    }

    public function deleted(Appointments $appointments)
    {
        //
    }

    public function restored(Appointments $appointments)
    {
        //
    }

    public function forceDeleted(Appointments $appointments)
    {
        //
    }
}

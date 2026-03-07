<?php

namespace App\Observers;

use App\Jobs\SendAppointmentStatus;
use App\Jobs\sendBookingMail;
use App\Models\Appointments;
use App\Models\DoctorTimeSlots;
use App\Models\Patients;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AppointmentsObserver
{
    public function creating(Appointments $appointments)
    {
        $doctor_ID = DoctorTimeSlots::where('id', $appointments->doctorTimeSlot_ID)->value('doctor_ID');

        $count = Appointments::where('appointment_no', 'LIKE', 'APT-'. date('dmY'). '-'. $doctor_ID . '-%')->latest('appointment_no')->first('appointment_no');

        $getNumber = substr($count->appointment_no ?? 'APT-'. date('dmY'). '-'. $doctor_ID . '-000', -3);

        $incrementedValue = strval(intval($getNumber)+1);

        $formattedCount = str_pad($incrementedValue, 3, '0', STR_PAD_LEFT);

        $appointments->appointment_no = "APT-". date('dmY'). "-". $doctor_ID . "-" . $formattedCount;
    }

    public function created(Appointments $appointments)
    {
        dispatch(new sendBookingMail(Appointments::getEmailData($appointments, Auth::user()->role_ID),'create'));
    }

    public function updated(Appointments $appointments)
    {
        info('appointment observer update methods');

        $roleID = User::where('id', $appointments->createdBy)->first('role_ID');

        if ($appointments->wasChanged('status') || $appointments->wasChanged('isRescheduled')) 
        {
            $new_status = $appointments->status;

            // Handle specific status change logic
            if ($new_status === 'Pending') {
                info('inside a pending status');
                dispatch(new SendAppointmentStatus(Appointments::getEmailData($appointments, $roleID->role_ID), $new_status));
            }

            if ($new_status === 'completed' && !$appointments->getOriginal('status') === 'completed') {
                // Update payment status only if newly completed
                Patients::updatePaymentStatus($appointments->patient_ID, 1);
            }

            // Send email for all status changes
            dispatch(new SendAppointmentStatus(Appointments::getEmailData($appointments, $roleID->role_ID), $new_status));
        }

        if($appointments->wasChanged(['appointmentDate','patient_ID', 'doctorTimeSlot_ID']))
        {
            info('inside a appointment observer update methods for reschedule');
            dispatch(new sendBookingMail(Appointments::getEmailData($appointments, $roleID->role_ID),'update'));
        }
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

<?php

namespace App\Observers;

use App\Jobs\SendAppointmentStatus;
use App\Jobs\sendBookingMail;
use App\Models\Appointments;
use App\Models\Patients;

class AppointmentsObserver
{
    public function created(Appointments $appointments)
    {
        dispatch(new sendBookingMail(Appointments::getEmailData($appointments),'create'));
    }

    public function updated(Appointments $appointments)
    {
        info('inside a update observer class');

        if ($appointments->wasChanged('status') || $appointments->wasChanged('isRescheduled')) 
        {
            $new_status = $appointments->status;

            // Handle specific status change logic
            if ($new_status === 'Pending') {
                dispatch(new SendAppointmentStatus(Appointments::getEmailData($appointments), $new_status));
            }

            if ($new_status === 'completed' && !$appointments->getOriginal('status') === 'completed') {
                // Update payment status only if newly completed
                Patients::updatePaymentStatus($appointments->patient_ID, 1);
            }

            // Send email for all status changes
            dispatch(new SendAppointmentStatus(Appointments::getEmailData($appointments), $new_status));
        }

        if($appointments->wasChanged(['appointmentDate','patient_ID', 'doctorTimeSlot_ID']))
        {
            dispatch(new sendBookingMail(Appointments::getEmailData($appointments),'update'));
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

<?php

namespace App\Observers;

use App\Jobs\SendAppointmentStatus;
use App\Jobs\sendBookingMail;
use App\Models\Appointments;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AppointmentsObserver
{
    public function created(Appointments $appointments)
    {
        dispatch(new sendBookingMail(Appointments::getEmailData($appointments)));
    }

    public function updated(Appointments $appointments)
    {
        info('inside a update observer class');

        if($appointments->wasChanged('status') || $appointments->wasChanged('isRescheduled'))
        {
            $new_status = $appointments->status;

            dispatch(new SendAppointmentStatus(Appointments::getEmailData($appointments),$new_status));
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

<?php

namespace App\Observers;

use App\Jobs\SendDoctorMail;
use App\Models\Doctor;
use App\Models\User;

class DoctorObserver
{
    public function created(Doctor $doctor)
    {
        info('inside a doctor observer class');
        info('--------------------start here---------------------------------');
        info($doctor->toArray());

        $getDoctorDetails = User::getUserInfo($doctor->user_ID);
        info($getDoctorDetails->toArray());
        info('--------------------end here ----------------------------------');

        if(!empty($getDoctorDetails))
        {
            dispatch(new SendDoctorMail($getDoctorDetails->toArray(),'observer'));
        }
    }

    public function updated(Doctor $doctor)
    {
        //
    }

    public function deleted(Doctor $doctor)
    {
        //
    }

    public function restored(Doctor $doctor)
    {
        //
    }

    public function forceDeleted(Doctor $doctor)
    {
        //
    }
}

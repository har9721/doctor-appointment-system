<?php

namespace App\Observers;

use App\Jobs\SendMessage;
use App\Models\Patients;

class PatientsObserver
{
    public function created(Patients $patients)
    {
        info('in observer');
        dispatch(new SendMessage($patients));
    }

    public function updated(Patients $patients)
    {
        //
    }

    public function deleted(Patients $patients)
    {
        //
    }

    public function restored(Patients $patients)
    {
        //
    }

    public function forceDeleted(Patients $patients)
    {
        //
    }
}

<?php

namespace App\Jobs;

use App\Mail\AppointmentConfirmationPatient;
use App\Mail\NewAppointmentForDoctor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class sendBookingMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $emailData;

    public function __construct($data)
    {
        info('inside job constructor ');
        info($data);
        $this->emailData = $data;
    }

    public function handle()
    {
        info('in send boook mail job class');
        
        info($this->emailData);
        // send mail to patient 
        Mail::to($this->emailData['patientsEmail'])->send(new AppointmentConfirmationPatient($this->emailData));

        // send mail to doctor
        Mail::to($this->emailData['doctor_email'])->send(new NewAppointmentForDoctor($this->emailData));
    }
}

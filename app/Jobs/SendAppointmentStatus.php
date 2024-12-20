<?php

namespace App\Jobs;

use App\Mail\AppointmentPaymentNotification;
use App\Mail\AppointmentRevertMail;
use App\Mail\AppointmentStatusMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAppointmentStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    protected $mailData;
    protected $status;

    public function __construct($data,$status)
    {
        info('inside a send appointment status job class');
        info('--------------------start here---------------------------------');
        info($data);
        info('--------------------end here ----------------------------------');

        $this->mailData = $data;
        $this->status = $status;
    }

    public function handle()
    {
        if($this->status == 'pending' && $this->mailData['isRescheduled'] == 0)
            Mail::to($this->mailData['patientsEmail'])->send(new AppointmentRevertMail($this->mailData,$this->status));
        else if($this->status != 'completed')
            Mail::to($this->mailData['patientsEmail'])->send(new AppointmentStatusMail($this->mailData,$this->status));
        else
            Mail::to($this->mailData['patientsEmail'])->send(new AppointmentPaymentNotification($this->mailData));
    }
}

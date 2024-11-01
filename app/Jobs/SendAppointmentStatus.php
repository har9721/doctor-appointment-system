<?php

namespace App\Jobs;

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
        Mail::to($this->mailData['patientsEmail'])->send(new AppointmentStatusMail($this->mailData,$this->status));
    }
}

<?php

namespace App\Jobs;

use App\Mail\sendAddPrescriptionMail;
use App\Mail\sendPaymentDone;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPaymentSummary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    protected $mailData;

    public function __construct($data)
    {
        info($data);
        $this->mailData = $data->first();
        info('-----------------------inside payment summary---------------------');
        info($this->mailData['email']);
    }

    public function handle()
    {
        if(isset($this->mailData))
        {
            Mail::to($this->mailData['email'])->send(new sendPaymentDone($this->mailData));
            Mail::to($this->mailData['doctorEmail'])->send(new sendAddPrescriptionMail($this->mailData));
        }
    }
}

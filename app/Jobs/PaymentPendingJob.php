<?php

namespace App\Jobs;

use App\Mail\SendPaymentPendingMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class PaymentPendingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $email;
    public $data;

    public function __construct($email,$appointment_data)
    {
        info('inside payment pending job class');
        $this->email = $email;
        $this->data = $appointment_data;
    }

    public function handle()
    {
        info($this->data);
        info('--------------------------------------------------------');
        Mail::to($this->email)->send(new SendPaymentPendingMail($this->data));
    }
}

<?php

namespace App\Jobs;

use App\Mail\SendTimeSlotMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendDoctorMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $data;
    public $key;

    public function __construct($data,$key)
    {
        info('inside a send doctor mail job class');
        info('--------------------start here---------------------------------');
        info($data);
        info('--------------------end here ----------------------------------');

        $this->data = $data;
        $this->key = $key;
    }


    public function handle()
    {
        Mail::to($this->data['email'])
            ->send(new SendTimeSlotMail($this->data,$this->key));
    }
}

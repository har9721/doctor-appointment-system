<?php

namespace App\Jobs;

use App\Mail\sendWelcomeMail;
use App\Notifications\welcomeEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    public $tries = 3;
    
    public function __construct($userData)
    {
        $this->data = $userData;
    }

    public function handle()
    {
        info('in jobs class');
        Mail::to($this->data['email'])->send(new sendWelcomeMail($this->data));
    }
}

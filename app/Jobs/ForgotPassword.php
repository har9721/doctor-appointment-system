<?php

namespace App\Jobs;

use App\Mail\sendPasswordResetLink;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ForgotPassword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;

    protected $token;

    public $tries = 3;

    public function __construct($email,$token)
    {
        $this->email = $email;
        $this->token = $token;
    }

    public function handle()
    {
        Mail::to($this->email)->send(new sendPasswordResetLink($this->token));
    }
}

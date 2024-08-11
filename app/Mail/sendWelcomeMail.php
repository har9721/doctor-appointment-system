<?php

namespace App\Mail;

use App\Models\Patients;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    public function __construct(Patients $patients)
    {
        $this->data = $patients;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Send Welcome Mail',
        );
    }

    public function content()
    {
        return new Content(
            view: 'mail.welcome',
            with: [
                'name' => $this->data['first_name']. ' '.$this->data['last_name'],
                'password' => '12345678',
                'url' => "http://127.0.0.1:8000/",
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}

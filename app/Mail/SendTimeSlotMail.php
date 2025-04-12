<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendTimeSlotMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;
    public $key;

    public function __construct($data,$key)
    {
        info('inside a send time slot mail class');
        info('--------------------start here---------------------------------');
        info($data);

        $this->emailData = $data;
        $this->key = $key;

        info($this->emailData);
        info('--------------------end here ----------------------------------');
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Set Your Availability For Appointments',
        );
    }

    public function content()
    {
        if($this->key == 'observer')
        {
            $m1 = "You’ve been successfully added to our system.";
            $m2 = "To start accepting appointments, please log in and set your available time slots.";
            $m3 = "Patients will not be able to book appointments with you until this is done.";
        }
        else
        {
            $m1 = "";
            $m2 = "To start accepting appointments, please log in and set your available time slots.";
            $m3 = "Patients will not be able to book appointments with you until this is done.";
        }

        return new Content(
            view: 'mail.SendTimeSlotMail',
            with: [
                'name' => $this->emailData['first_name'],
                'm1' => $m1,
                'm2' => $m2,
                'm3' => $m3,
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}

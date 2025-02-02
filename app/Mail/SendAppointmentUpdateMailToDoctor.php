<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendAppointmentUpdateMailToDoctor extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    public function __construct($data)
    {
        $this->mailData = $data;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Your Appointment Has Been Updated',
        );
    }

    public function content()
    {
        return new Content(
            view: 'mail.sendAppointmentUpdateMailToDoctor',
            with: [
                'name' => $this->mailData['doctor_name'],
                'patientName' => $this->mailData['patientsName'],
                'date' => date('d-m-Y',strtotime($this->mailData['date'])),
                'time' => Carbon::parse($this->mailData['time'])->format('h:i A'),
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}

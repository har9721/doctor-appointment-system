<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentRevertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    public $status;

    public function __construct($data,$status)
    {
        info('-------------------------inside the constructor of revert mail-----------------------');
        $this->mailData = $data;
        $this->status = $status;
        info($this->mailData);
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Appointment Revert Mail',
        );
    }

    public function content()
    {
        return new Content(
            view: 'mail.sendAppointmentRevert',
            with: [
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

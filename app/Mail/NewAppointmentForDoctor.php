<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAppointmentForDoctor extends Mailable
{
    use Queueable, SerializesModels;

    public $doctorData;

    public function __construct($data)
    {
        $this->doctorData = $data;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'New Appointment For Doctor',
        );
    }

    public function content()
    {
        return new Content(
            view: 'view.sendNewAppointment',
            with: [
                'name' => $this->doctorData['doctor_name'],
                'patientName' => $this->doctorData['patientsName'],
                'date' => $this->doctorData['date'],
                'time' => $this->doctorData['time'],
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}

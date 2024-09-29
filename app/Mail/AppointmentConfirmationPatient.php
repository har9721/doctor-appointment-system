<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmationPatient extends Mailable
{
    use Queueable, SerializesModels;

    public $patientsData;

    public function __construct($data)
    {
        $this->patientsData = $data;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Appointment Confirmation Patient',
        );
    }

    public function content()
    {
        return new Content(
            view: 'mail.sendBookingConfirmation',
            with: [
                'name' => $this->patientsData['doctor_name'],
                'patientName' => $this->patientsData['patientsName'],
                'date' => $this->patientsData['date'],
                'time' => $this->patientsData['time'],
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}

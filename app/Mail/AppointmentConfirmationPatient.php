<?php

namespace App\Mail;

use Carbon\Carbon;
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
        info('inside appointment confirmation mailable class');
        info('-----------------------------start here-----------------------------------------------');
        info($data);
        info('-----------------------------end here-------------------------------------------------');
        $this->patientsData = $data;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Appointment Confirmation',
        );
    }

    public function content()
    {
        info('final data to send mail');
        info('-----------------------------start here-----------------------------------------------');
        info($this->patientsData);
        info('-----------------------------end here-------------------------------------------------');

        return new Content(
            view: 'mail.sendBookingConfirmation',
            with: [
                'name' => $this->patientsData['doctor_name'],
                'patientName' => $this->patientsData['patientsName'],
                'date' => date('d-m-Y',strtotime($this->patientsData['date'])),
                'time' => Carbon::parse($this->patientsData['time'])->format('h:i A'),
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}

<?php

namespace App\Mail;

use Carbon\Carbon;
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
        info('get doctor data from construcotr');
        info('----------------------------start here-------------------------------------');
        info($data);
        info('----------------------------end here-------------------------------------');

        $this->doctorData = $data;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'New Appointment',
        );
    }

    public function content()
    {
        info('get doctor data from global variable');
        info('----------------------------start here-------------------------------------');
        info($this->doctorData);
        info('----------------------------end here-------------------------------------');

        return new Content(
            view: 'mail.sendNewAppointment',
            with: [
                'name' => $this->doctorData['doctor_name'],
                'patientName' => $this->doctorData['patientsName'],
                'date' => date('d-m-Y',strtotime($this->doctorData['date'])),
                'time' => Carbon::parse($this->doctorData['time'])->format('h:i A'),
                'appointmentNo' => $this->doctorData['appointment_no'] ?? null,
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}

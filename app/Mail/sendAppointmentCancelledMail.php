<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendAppointmentCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    public $status;

    public function __construct($data,$status)
    {
        info('--------------------------inside the cancelled mail class---------------------');
        $this->mailData = $data;
        $this->status = $status;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Send Appointment Cancelled Mail',
        );
    }

    public function content()
    {
        $msg1 = "Your appointment with ".$this->mailData['patientsName']." has been ".$this->status." for ".date('d-m-Y',strtotime($this->mailData['date']))." at ".Carbon::parse($this->mailData['time'])->format('h:i A').".";

        return new Content(
            view: 'mail.sendAppointmentCancelledMailToDoctor',
            with: [
                'name' => $this->mailData['doctor_name'],
                'patientName' => $this->mailData['patientsName'],
                'date' => date('d-m-Y',strtotime($this->mailData['date'])),
                'time' => Carbon::parse($this->mailData['time'])->format('h:i A'),
                'msg1' => $msg1,
                'appointmentNo' => $this->mailData['appointment_no'] ?? null,
            ],
        );
    }


    public function attachments()
    {
        return [];
    }
}

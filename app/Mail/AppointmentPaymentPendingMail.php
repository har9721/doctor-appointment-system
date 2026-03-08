<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentPaymentPendingMail extends Mailable
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
            subject: 'Payment Required to Confirm Your Appointment',
        );
    }

    public function content()
    {
        return new Content(
            view: 'mail.appointmentPaymentPending',
            with: [
                'name' => $this->mailData['doctor_name'],
                'patientName' => $this->mailData['patientsName'],
                'date' => date('d-m-Y',strtotime($this->mailData['date'])),
                'time' => Carbon::parse($this->mailData['time'])->format('h:i A'),
                'appointmentNo' => $this->mailData['appointment_no'],
                'amount' => $this->mailData['amount']
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}

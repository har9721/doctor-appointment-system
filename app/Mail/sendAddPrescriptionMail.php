<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendAddPrescriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $mailData;
    
    public function __construct($data)
    {
        $this->mailData = $data;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Payment Received',
        );
    }

    public function content()
    {
        return new Content(
            view: 'mail.paymentReceivedMail',
            with: [
                'doctorName' => $this->mailData['doctorName'] ?? '',
                'patientName' => $this->mailData['patientName'] ?? '',
                'date' => $this->mailData['appointmentDate'] ?? '',
                'paymentDate' => $this->mailData['paymentDate'] ?? '',
                'prescriptionLink' => route('appointments.my-appointments'),
                'appointmentNo' => $this->mailData['appointment_no'] ?? null,
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendPaymentPendingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Send Payment Pending Mail',
        );
    }

    public function content()
    {
        return new Content(
            view: 'mail.sendPaymentPendingMail',
            with: [
                'patientName' => $this->data->patients->user->first_name,
                'doctorName' => $this->data->doctorTimeSlot->doctor->user->full_name,
                'date' => $this->data->appointmentDate,
                'time' => $this->data->doctorTimeSlot->time,
                'amount' => $this->data->amount,
                'appointmentNo' => $this->data->appointment_no ?? null,
            ]
        );
    }

    public function attachments()
    {
        return [];
    }
}

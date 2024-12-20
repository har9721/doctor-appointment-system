<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentPaymentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    public function __construct($data)
    {
        info('---------------------inside a appointment payment notification-----------------------------');
        $this->mailData = $data;

        info($this->mailData);
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Appointment Payment Notification',
        );
    }

    public function content()
    {
        return new Content(
            view: 'mail.sendAppointmentPayment',
            with: [
                'doctorName' => $this->mailData['doctor_name'],
                'patientName' => $this->mailData['patientsName'],
                'specialty' => $this->mailData['specialty'],
                'amount' => $this->mailData['amount'],
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

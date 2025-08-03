<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendAppointmentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $role;

    public function __construct($data, $role)
    {
        info('----------------------inside send appointment reminder mail-------------------------------'); 
        $this->data = $data;
        $this->role = $role;
        info($this->data);
        info($this->role);
        info('----------------------end of send appointment reminder mail-------------------------------');
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Send Appointment Reminder Mail',
        );
    }

    public function content()
    {
        return new Content(
            view: 'mail.appointmentReminderMail',
            with: [
                'name' => ($this->role == 'patient') ? $this->data['patient_name'] : 'Dr.'. $this->data['doctor_name'],
                'appointmentDate' => $this->data['appointmentDate'],
                'time' => $this->data['time'],
                'partner_name' => ($this->role == 'doctor') ? $this->data['patient_name'] : $this->data['doctor_name'],
                'supportEmail' => config('app.support_email'),
                'label' => ($this->role == 'patient') ? 'Doctor' : 'Patient',
                'appointmentNo' => $this->data['appointment_no'] ?? null,
            ]
        );
    }

    public function attachments()
    {
        return [];
    }
}

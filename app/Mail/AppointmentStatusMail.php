<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $mailData;
    protected $status;

    public function __construct($data,$status)
    {
        info('inside a send appointment status mail class');
        info('--------------------star here-------------------------------');
        info($data);
        info('------------------end here-----------------------------');
        info($status);
        $this->mailData = $data;
        $this->status = $status;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Appointment Status Mail',
        );
    }

    public function content()
    {
        info('inside a content function');
        info('--------------------star here-------------------------------');
        info($this->mailData);
        info('------------------end here-----------------------------');

        $doctorName = $this->mailData['doctor_name'];
        $patientName = $this->mailData['patientsName'];
        $date = date('d-m-Y', strtotime($this->mailData['date']));
        $time = Carbon::parse($this->mailData['time'])->format('h:i A');
        $appointmentNo = $this->mailData['appointment_no'] ?? null;
        $msg1 = '';
        $msg2 = '';

        if ($this->status === 'confirmed') {
            info('---------------- inside confirmed ----------------');
            $msg1 = "Your appointment with Dr. {$doctorName} has been confirmed for {$date} at {$time}.";
            $msg2 = "Kindly ensure you are available on time for your appointment.";

        } elseif ($this->status === 'cancelled') {
            info('---------------- inside cancelled ----------------');
            $msg1 = "Your appointment with Dr. {$doctorName} has been cancelled for {$date} at {$time}.";
            // No msg2

        } elseif (!empty($this->mailData['isRescheduled'])) {
            info('---------------- inside rescheduled ----------------');
            $msg1 = "Your appointment with Dr. {$doctorName} has been rescheduled for {$date} at {$time}.";
            $msg2 = "Kindly ensure you are available on time for your appointment.";

        } else {
            info('---------------- inside default ----------------');
            $msg1 = '';
            $msg2 = '';
        }

        return new Content(
            view: 'mail.sendApointmentStatus',
            with: [
                'name' => $doctorName,
                'patientName' => $patientName,
                'date' => $date,
                'time' => $time,
                'msg1' => $msg1,
                'msg2' => $msg2,
                'appointmentNo' => $appointmentNo,
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}

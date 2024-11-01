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

        if($this->status == 'confirmed')
        {
            info('-------------------------------inside if-----------------------------------');
            $msg1 = "Your appointment with Dr. ".$this->mailData['doctor_name']." has been ".$this->status." for ".date('d-m-Y',strtotime($this->mailData['date']))." at ".Carbon::parse($this->mailData['time'])->format('h:i A').".";

            $msg2 = "Kindly ensure you are available on time for your appointment.";
        }elseif($this->status == 'cancelled')
        {
            info('-------------------------------inside else if-----------------------------------');

            $msg1 = "Your appointment with Dr. ".$this->mailData['doctor_name']." has been ".$this->status." for ".date('d-m-Y',strtotime($this->mailData['date']))." at ".Carbon::parse($this->mailData['time'])->format('h:i A').".";

            $msg2 = "";
        }elseif($this->mailData['isRescheduled'] == 1)
        {
            info('-------------------------------inside is reschedule-----------------------------------');

            $msg1 = "Your appointment with Dr. ".$this->mailData['doctor_name']." has been rescheduled for ".date('d-m-Y',strtotime($this->mailData['date']))." at ".Carbon::parse($this->mailData['time'])->format('h:i A').".";

            $msg2 = "Kindly ensure you are available on time for your appointment.";
        }else{
            info('-------------------------------inside else-----------------------------------');

            $msg1 = '';
            $msg2 = '';
        }

        return new Content(
            view: 'mail.sendApointmentStatus',
            with: [
                'name' => $this->mailData['doctor_name'],
                'patientName' => $this->mailData['patientsName'],
                'date' => date('d-m-Y',strtotime($this->mailData['date'])),
                'time' => Carbon::parse($this->mailData['time'])->format('h:i A'),
                'msg1' => $msg1,
                'msg2' => $msg2
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}

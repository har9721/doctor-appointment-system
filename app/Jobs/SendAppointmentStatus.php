<?php

namespace App\Jobs;

use App\Mail\AppointmentPaymentNotification;
use App\Mail\AppointmentRevertMail;
use App\Mail\AppointmentStatusMail;
use App\Mail\sendAppointmentCancelledMail;
use App\Services\GoogleCalendarService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAppointmentStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    protected $mailData;
    protected $status;

    public function __construct($data,$status)
    {
        info('inside a send appointment status job class');
        info('--------------------start here---------------------------------');
        info($data);
        info('--------------------end here ----------------------------------');
        info($status);

        $this->mailData = $data;
        $this->status = $status;
    }

    public function handle()
    {
        // create an instance of google calendar service
        $calendarService = new GoogleCalendarService();

        if($this->status == 'cancelled')
        {
            Mail::to($this->mailData['doctor_email'])->send(new sendAppointmentCancelledMail($this->mailData,$this->status));

            Mail::to($this->mailData['patientsEmail'])->send(new AppointmentStatusMail($this->mailData,$this->status));
        }else if($this->status == 'pending' && $this->mailData['isRescheduled'] == 0){
            Mail::to($this->mailData['patientsEmail'])->send(new AppointmentRevertMail($this->mailData,$this->status));
        }else if($this->status != 'completed'){
            Mail::to($this->mailData['patientsEmail'])->send(new AppointmentStatusMail($this->mailData,$this->status));

            // create an event in google calendar for patient
            info('creating event in google calendar for patient');
            $calendarService->createEvent([
                'summary' => 'Doctor Appointment with ' . $this->mailData['doctor_name'],
                'location' => "Near to Bawla Masjid, Room no 102, 1st Floor, Opposite to Bawla Masjid, Bawla Road, Bawla, Mumbai 400013",
                'description' => 'Your appointment with scheduled.',
                'start_date_time' => '2025-06-02T10:00:00+05:30',
                // 'start_date_time' => $this->mailData['start_date_time'],
                'end_date_time' => '2025-06-02T10:30:00+05:30',
                // 'end_date_time' => $this->mailData['end_date_time'],
                'attendee_email' => $this->mailData['patientsEmail'],
                'attendee_name' => $this->mailData['patientsName']
            ]);

        }else{
            Mail::to($this->mailData['patientsEmail'])->send(new AppointmentPaymentNotification($this->mailData));
        }
    }
}

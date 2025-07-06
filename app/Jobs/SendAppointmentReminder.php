<?php

namespace App\Jobs;

use App\Mail\SendAppointmentReminderMail;
use App\Models\Appointments;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAppointmentReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public function __construct()
    {
        info('----------------------inside send appointment reminder  job-------------------------------');
    }

    public function handle()
    {
        $currentDate = Carbon::now()->format('Y-m-d');  

        $appointments = Appointments::with(['patients.user', 'doctorTimeSlot.doctor.user'])
            ->where('appointmentDate',$currentDate)
            ->where('payment_status','pending')
            ->whereStatus('confirmed')
            ->where('isActive',1)
            ->get()
            ->map(function ($appointments)
            {
                return [
                    'id' => $appointments->id,
                    'patient_name' => $appointments->patients->user->patients_name ?? 'N/A',
                    'doctor_name' => $appointments->doctorTimeSlot->doctor->user->doctor_name ?? 'N/A', 
                    'appointmentDate' => $appointments->appointmentDate,
                    'time' => $appointments->doctorTimeSlot->time ?? '',
                    'start_time' => $appointments->doctorTimeSlot->start_time ?? '',
                    'end_time' => $appointments->doctorTimeSlot->end_time ?? '',
                    'patient_email' => $appointments->patients->user->email ?? 'N/A',
                    'doctor_email' => $appointments->doctorTimeSlot->doctor->user->email ?? 'N/A',
                    'appointment_reminder_time' => $appointments->appointment_reminder_time ?? null,
                    'isReminderSent' => $appointments->isReminderSent ?? 0
                ];
            })->toArray();

        info($appointments);

        if(!empty($appointments))
        {
            $time = Carbon::now();

            $currentTime = $time->addHours(5)->addMinutes(30);

            foreach($appointments as $appointment)
            {
                if(
                    !empty($appointment['appointment_reminder_time']) && $appointment['isReminderSent'] == 0 &&
                    $appointment['appointment_reminder_time'] < $currentTime
                )
                {
                    $patientEmail = $appointment['patient_email'];
                    $doctorEmail = $appointment['doctor_email'];

                    // send reminder email to patient
                    Mail::to($patientEmail)
                        ->send(
                            new SendAppointmentReminderMail($appointment, 'patient')
                        );

                    // send reminder email to doctor
                    Mail::to($doctorEmail)
                        ->send(
                            new SendAppointmentReminderMail($appointment, 'doctor')
                        );

                    // update appointment reminder status
                    Appointments::where('id', $appointment['id'])
                    ->update([
                        'isReminderSent' => 1
                    ]);
                }
            }
        }
    }
}

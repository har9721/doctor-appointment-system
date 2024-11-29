<?php

namespace App\Jobs;

use Carbon\Carbon;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddRecursiveTimeSlot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $timeSlot;

    public function __construct($data)
    {
        $this->timeSlot = $data;
    }

    public function handle()
    {
        info($this->timeSlot);
        $currentDate = Carbon::parse($this->timeSlot['date']);
        $monthLastDate = $currentDate->copy()->endOfMonth();
        $timeSlotArray = [];

        for ($date = $currentDate->copy(); $date->lte($monthLastDate); $date->addDay()) 
        {
            if($this->timeSlot['recurrence'] == 'daily')
            {
                if($date->format('D') != 'Sun')
                {
                    $timeSlotArray[] = [
                        'doctor_ID' => $this->timeSlot['doctor_ID'],
                        'availableDate' => $date->toDateString(),
                        'start_time' => $this->timeSlot['startTime'],
                        'end_time' => $this->timeSlot['endTime'],
                        'created_at' => now(),
                        'createdBy' => $this->timeSlot['createdBy']
                    ];
                }
            }

            if($this->timeSlot['recurrence'] == 'weekly')
            {
                if(in_array($date->dayName, $this->timeSlot['days']))
                {
                    $timeSlotArray[] = [
                        'doctor_ID' => $this->timeSlot['doctor_ID'],
                        'availableDate' => $date->toDateString(),
                        'start_time' => $this->timeSlot['startTime'],
                        'end_time' => $this->timeSlot['endTime'],
                        'created_at' => now(),
                        'createdBy' => $this->timeSlot['createdBy']
                    ];
                }
            }
        }

        // insert record in the table
        DB::table('doctor_time_slots')->insert($timeSlotArray);
    }
}

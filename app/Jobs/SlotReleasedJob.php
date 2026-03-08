<?php

namespace App\Jobs;

use App\Models\Appointments;
use App\Models\DoctorTimeSlots;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SlotReleasedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $appointmentId
    )
    {
        info("this is inside slot released time");
    }

    public function handle()
    {
        $appointmentDetails = Appointments::findOrFail($this->appointmentId);

        if($appointmentDetails->status == 'awaiting for payment' && $appointmentDetails->payment_status == 'pending')
        {
            DB::transaction(function() use($appointmentDetails){
                info('inside DB transation');
                $appointment = Appointments::where('id', $appointmentDetails->id)
                    ->lockForUpdate()
                    ->first();

                if ($appointment->status !== 'confirmed' && $appointment->payment_status === 'pending') {

                    $appointment->update([
                        'status' => 'cancelled',
                        'payment_status' => 'failed',
                        'updatedBy' => 1
                    ]);

                    DoctorTimeSlots::where('id', $appointment->doctorTimeSlot_ID)
                    ->update([
                        'isBooked' => 0,
                        'updatedBy' => 1
                    ]);
                }
                info('this is at end of db transaction');
            });
        }
    }
}

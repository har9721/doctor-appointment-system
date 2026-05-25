<?php 

namespace App\Services;

use App\Models\Appointments;
use App\Models\DoctorTimeSlots;
use App\Models\PaymentDetails;
use Illuminate\Support\Facades\DB;

class PaymentHandlerService
{
    public function __construct()
    {
        
    }

    public function cancelAppointment($data)
    {
        return DB::transaction(function() use($data) {
            // Get and lock the appointment for update to prevent race conditions
            $appointment = Appointments::where('id', $data['appointment_id'])
                ->lockForUpdate()
                ->first();

            if (!$appointment) {
                throw new \Exception("Appointment not found");
            }

            // 1. Mark the appointment as cancelled or mark payment as pending
            if($appointment->status !== 'completed') {
                // If appointment is not completed, mark as cancelled and payment as failed
                $appointment->update([
                    'status' => 'cancelled',
                    'payment_status' => 'failed',
                    'isCancel' => 1,
                    'updatedBy' => auth()->id()
                ]);
            } 
            // else {
            //     // If appointment is already completed, only mark payment as pending (remaining amount not paid)
            //     $appointment->update([
            //         'payment_status' => 'pending',
            //         'updatedBy' => auth()->id()
            //     ]);
            // }

            // 2. Mark the payment as failed
            $payment = PaymentDetails::where('id', $data['payment_details_id'])
                ->where('appointment_ID', $data['appointment_id'])
                ->lockForUpdate()
                ->first();

            if ($payment) {
                // If appointment is completed, only update 'remaining' payment entry
                // If appointment is not completed, update the single payment entry
                if($appointment->status === 'completed') {
                    // Update only remaining payment entry for completed appointments
                    PaymentDetails::where('id', $data['payment_details_id'])
                        ->where('appointment_ID', $data['appointment_id'])
                        ->where('payment_type', 'remaining')
                        ->update([
                            'status' => 'failed'
                        ]);
                } else {
                    // For fresh bookings or non-completed appointments, update the payment entry as is
                    $payment->update([
                        'status' => 'failed'
                    ]);
                }
            }

            // 3. Release the timeslot only if appointment is NOT completed
            if($appointment->status !== 'completed') {
                DoctorTimeSlots::where('id', $appointment->doctorTimeSlot_ID)
                    ->update([
                        'isBooked' => 0,
                        'updatedBy' => auth()->id()
                    ]);
            }

            return [
                'success' => true,
                'message' => 'Appointment cancelled successfully',
                'appointment_id' => $appointment->id
            ];
        });
    }
}
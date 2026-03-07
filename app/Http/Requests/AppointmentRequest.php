<?php

namespace App\Http\Requests;

use App\Models\DoctorTimeSlots;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AppointmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'appointment_date' => ['required','date_format:d-m-Y',
                function($attributes,$value,$fail)
                {
                    $currentDate = Carbon::now()->addHour(5)->addMinute(30);

                    $timeSlotDetails = DoctorTimeSlots::getSlotTime($this->timeSlot);

                    if(empty($timeSlotDetails))
                    {
                        $fail("The time slot is not found.");
                    }

                    $appointmentDate = Carbon::parse(
                        $timeSlotDetails->appointmentDate.''.$timeSlotDetails->start_time
                    );

                    if($appointmentDate->gte($currentDate) && in_array($this->status, ['completed'])) //'confirmed'
                    {
                        $fail("The appointment cannot be marked as complete before the scheduled appointment date.");
                    }
                    else if($appointmentDate->lte($currentDate) && in_array($this->status, ['confirmed']))
                    {
                        $fail("You cannot confirm an appointment scheduled in the past.");
                    }
                }
            ],
            'patient_ID' => 'required',
            'reason' => 'nullable',
            'timeSlot' => 'sometimes',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'sucess' => false,
            'message' => $validator->errors()->first(),
        ],422));
    }
}

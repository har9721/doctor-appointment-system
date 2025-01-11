<?php

namespace App\Http\Requests;

use App\Models\DoctorTimeSlots;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class AppointmentBooking extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date' => ['required','date_format:d-m-Y','after_or_equal:'.date('d-m-Y')],
            'timeSlot' => [
                Rule::unique('appointments','doctorTimeSlot_ID')->where('appointmentDate',$this->date)->where('patient_ID',$this->patient_ID)->where('doctorTimeSlot_ID',$this->timeSlot),
                function($atttributes,$value,$fail){
                    $timeSlot = DoctorTimeSlots::find($value);

                    $currentTime = Carbon::now()->addHours(5)->addMinutes(30);

                    if(($timeSlot->start_time <= $currentTime) && ($timeSlot->availableDate <= date('Y-m-d')))
                    {
                        $fail('The selected time slot must be greater than the current time.');
                    }
                }
            ],
            'patient_ID' => 'required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' =>$validator->errors()->first(),
        ], 422));
    }
}

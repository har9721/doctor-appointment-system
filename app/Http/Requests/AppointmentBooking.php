<?php

namespace App\Http\Requests;

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
                Rule::unique('appointments','doctorTimeSlot_ID')->where('appointmentDate',$this->date)->where('patient_ID',$this->patient_ID)->where('doctorTimeSlot_ID',$this->timeSlot)
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

<?php

namespace App\Http\Requests;

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
                    $currentDate = Carbon::now()->format('d-m-Y');
                    $appointmentDate = Carbon::parse($value);

                    if($appointmentDate->gte($currentDate) && in_array($this->status, ['completed','confirmed']))
                    {
                        $fail("The appointment cannot be marked as complete before the scheduled appointment date.");
                    }
                    else if($appointmentDate->lte($currentDate) && in_array($this->status, ['completed','confirmed']))
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

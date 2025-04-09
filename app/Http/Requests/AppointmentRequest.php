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

                    if($value >= $currentDate && $this->status === 'completed')
                    {
                        $fail("The appointment cannot be marked as complete before the scheduled appointment date.");
                    }
                }
            ],
            'patient_ID' => 'required',
            'reason' => 'nullable',
            'timeSlot' => 'required|exists:doctor_time_slots,id',
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

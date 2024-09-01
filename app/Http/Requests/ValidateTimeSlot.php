<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ValidateTimeSlot extends FormRequest
{
    protected $stopOnFirstFailure  = true;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if($this->routeIs('doctor.updateTimeSlot'))
        {
            return [
                'date' => ['required','date_format:Y-m-d','after_or_equal:'.date('Y-m-d')],
                'startTime' => ['required','date_format:H:i:s',
                    Rule::unique('doctor_time_slots','start_time')->where('availableDate', $this->date)->where('start_time', $this->startTime)->where('doctor_ID', $this->doctor_ID),
                ],
            ];
        }else{
            if($this->isEdit == 1)
            {
                return [
                    'date' => ['required','date_format:Y-m-d','after_or_equal:'.date('Y-m-d')],
                    'startTime' => ['required','date_format:H:i',
                        Rule::unique('doctor_time_slots','start_time')->where('availableDate', $this->date)->where('start_time', $this->startTime)->where('doctor_ID', $this->doctor_ID)->ignore($this->hidden_timeslot_id),
                    ],
                    'endTime' => 'required|date_format:H:i|after:startTime'
                ];
            }else
            {
                return [
                    'date' => ['required','date_format:Y-m-d','after_or_equal:'.date('Y-m-d')],
                    'startTime' => ['required','date_format:H:i',
                        Rule::unique('doctor_time_slots','start_time')->where('availableDate', $this->date)->where('start_time', $this->startTime)->where('doctor_ID', $this->doctor_ID)->where('isDeleted',0),
                    ],
                    'endTime' => 'required|date_format:H:i|after:startTime'
                ];
            }
        }
    }

    public function messages()
    {
        return [
            'startTime.after' => 'The start time must be after 10:00.',
            'endTime.after' => 'The end time must be after start time.',
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

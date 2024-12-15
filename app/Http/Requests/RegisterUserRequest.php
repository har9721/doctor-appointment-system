<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class RegisterUserRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if($this->routeIs('admin.patientsUpdate'))
        {
            if($this->isPatients == 1)
            {
                return [
                    'first_name' => 'required|min:3|regex:/^[\pL\s_]+$/u',
                    'last_name' => 'required|min:3|regex:/^[\pL\s_]+$/u',
                    'email' => ['required','email',Rule::unique('users','email')->ignore($this->user_ID)],
                    'mobile' => ['required','numeric',Rule::unique('users','mobile')->ignore($this->user_ID),'digits:10'],
                    'gender_ID' => 'required|numeric',
                    'age' => 'required|numeric|lt:110',
                    'city_ID' => 'required',
                    'address' => 'required|min:3',
                    'user_ID' => 'required',
                    'name' => 'required|min:3|regex:/^[\pL\s_]+$/u',
                    'contact_relation' => 'required|min:3|regex:/^[\pL\s_]+$/u',
                    'contact_no' => ['required','numeric',Rule::unique('patients_emergency_contacts','phone_no')->ignore($this->emergency_contact_id),'digits:10'],
                    'past_illness' => 'required|min:2',
                    'chronic_condition' => 'required|min:2',
                    'surgeries' => 'required|min:2',
                    'allergies' => 'required|min:2',
                    'medication' => 'required|min:2',
                    'smoking_status' => 'required|in:1,2,3',
                    'alcohol_status' => 'required|in:1,2,3',
                    'exercise' => 'required',
                    'isPatients' => 'required',
                    'emergency_contact_id' => 'required',
                    'medical_history_id' => 'required',
                    'lifestyle_hidden_id' => 'required',
                ];

            }else{ 
                return [
                    'first_name' => 'required|min:3|regex:/^[\pL\s_]+$/u',
                    'last_name' => 'required|min:3|regex:/^[\pL\s_]+$/u',
                    'email' => ['required','email',Rule::unique('users','email')->ignore($this->user_ID)],
                    'mobile' => ['required','numeric',Rule::unique('users','mobile')->ignore($this->user_ID),'digits:10'],
                    'gender_ID' => 'required|numeric',
                    'age' => 'required|numeric|lt:110',
                    'city_ID' => 'required',
                    'address' => 'required|min:3',
                    'user_ID' => 'required',
                    'isPatients' => 'required',
                ];
            }
        }else
        {
            return [
                'first_name' => 'required|min:3|regex:/^[\pL\s_]+$/u',
                'last_name' => 'required|min:3|regex:/^[\pL\s_]+$/u',
                'email' => 'required|email|unique:users,email',
                'mobile' => 'required|numeric|unique:users,mobile|digits:10',
                'gender' => 'required|numeric',
                'age' => 'required|numeric|lt:110',
                'city' => 'required',
                'address' => 'required|min:3',
                'name' => 'required|min:3|regex:/^[\pL\s_]+$/u',
                'contact_relation' => 'required|min:3|regex:/^[\pL\s_]+$/u',
                'contact_no' => 'required|numeric|unique:patients_emergency_contacts,phone_no|digits:10',
                'past_illness' => 'required|min:2',
                'chronic_condition' => 'required|min:2',
                'surgeries' => 'required|min:2',
                'allergies' => 'required|min:2',
                'medication' => 'required|min:2',
                'smoking_status' => 'required|in:1,2,3',
                'alcohol_status' => 'required|in:1,2,3',
                'exercise' => 'required',
                'isPatients' => 'required',
            ];

        }
    }

    public function messages()
    {
        return [
            'first_name.regex' => 'The first name contains only alphabets.',
            'last_name.regex' => 'The last name contains only alphabets.',
            'contact_relation.regex' => 'The contact relation name contains only alphabets.',
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

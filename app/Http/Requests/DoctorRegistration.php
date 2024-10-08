<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DoctorRegistration extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if(!isset($this->user_ID))
        {
            return [
                'first_name' => 'required|min:3|regex:/^[\pL\s\-]+$/u',
                'last_name' => 'required|min:3|regex:/^[\pL\s\-]+$/u',
                'email' => ['required','email',Rule::unique('users','email')->whereNull('deletedAt')],
                'mobile' => ['required','numeric',Rule::unique('users','mobile')->whereNull('deletedAt'),'digits:10'],
                'gender' => 'required|in:1,2,3',
                'age' => 'required|numeric|lt:110',
                'state' => 'required|numeric',
                'city' => 'required|numeric',
                'speciality' => 'required|numeric',
                'licenseNumber' => 'required',
                'isPatients' => 'sometimes',
                'experience' => 'required|numeric',
                'profile_image' => 'required|mimes:jpg,jpeg,png'
            ];
        }else if(isset($this->user_ID))
        {
            return [
                'first_name' => 'required|min:3|regex:/^[\pL\s\-]+$/u',
                'last_name' => 'required|min:3|regex:/^[\pL\s\-]+$/u',
                'email' => ['required','email',Rule::unique('users','email')->ignore($this->user_ID)],
                'mobile' => ['required','numeric',Rule::unique('users','mobile')->ignore($this->user_ID),'digits:10'],
                'gender' => 'required|in:1,2,3',
                'age' => 'required|numeric|lt:110',
                'state' => 'required|numeric',
                'city' => 'required|numeric',
                'speciality' => 'required|numeric',
                'licenseNumber' => 'required',
                'isPatients' => 'sometimes',
                'experience' => 'required|numeric',
                'profile_image' => 'required_if:imageUpdateOption,Yes|mimes:jpg,jpeg,png',
                'user_ID' => 'sometimes',
                'isPatients' => 'sometimes',
                'imageUpdateOption' => 'required'
            ];
        }
    }

    public function messages()
    {
        return [
            'first_name.regex' => 'The first name contains only alphabets.',
            'last_name.regex' => 'The last name contains only alphabets.',
            'profile_image.required_if' => 'The profile image field is required.'
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

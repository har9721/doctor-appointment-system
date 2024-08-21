<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DoctorRegistation extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|min:3|regex:/^[\pL\s\-]+$/u',
            'last_name' => 'required|min:3|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|numeric|unique:users,mobile|digits:10',
            'gender' => 'required|in:1,2,3',
            'age' => 'required|numeric|lt:110',
            'state' => 'required|numeric',
            'city' => 'required|numeric',
            'speacility' => 'required|numeric',
            'licenseNumber' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'first_name.regex' => 'The first name contains only alphabets.',
            'last_name.regex' => 'The last name contains only alphabets.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // print_r($validator);
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' =>$validator->errors()->first(),
        ], 422));
    }
}

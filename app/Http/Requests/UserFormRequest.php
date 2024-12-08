<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UserFormRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|min:3|regex:/^[\pL\s\-]+$/u',
            'last_name' => 'required|min:3|regex:/^[\pL\s\-]+$/u',
            'email' => ['required'],
            'mobile' => ['required','numeric',Rule::unique('users','mobile')->whereNull('deletedAt')->ignore($this->user()->id),'digits:10'],
            'gender_ID' => 'required|in:1,2,3',
            'age' => 'required|numeric|lt:110',
            'state' => 'required|numeric',
            'city_ID' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'first_name.regex' => 'The first name contains only alphabets.',
            'last_name.regex' => 'The last name contains only alphabets.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
        ], 422));
    }

    protected function prepareForValidation()
    {
        $this->merge(array_map('trim', $this->all()));
    }

}

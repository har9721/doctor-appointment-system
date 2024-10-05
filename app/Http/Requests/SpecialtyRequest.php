<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class SpecialtyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if(empty($this->hidden_id))
        {
            return [
                'name' => 'required|min:2|unique:mst_specialties,specialtyName|regex:/^[A-Za-z., -]+$/'
            ];
        }else{
            return [
                'name' => ['required','min:2',Rule::unique('mst_specialties','specialtyName')->ignore($this->hidden_id),'regex:/^[A-Za-z., -]+$/'],
                'hidden_id' => 'required'
            ];
        }
    }

    public function messages()
    {
        return [
            'name.regex' => 'The first name contains only alphabets.',
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

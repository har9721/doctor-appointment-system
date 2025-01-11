<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'appointment_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'cardNumber' => ['required','numeric','digits:16',],
            'expiryDate' => ['required','date_format:Y-m','after_or_equal:'.date('Y-m')],
            'cvv' => ['required','numeric','digits:3'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {       
        throw new HttpResponseException

        (response()->json([
            'success' => false,
            'message' =>$validator->errors()->first(),
        ], 422));
    }
}

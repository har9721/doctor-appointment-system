<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class validatePrescriptions extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'appointment_id' => ['required','numeric'],
            'hidden_mode' => ['required','string'],
            'prescription_id' => ['required_if:hidden_mode,==,edit'],
            'doctor_id' => ['required','numeric'],
            'patient_id' => ['required','numeric'],
            'medicines' => ['required', 'array', 'min:1'],
            'medicines.*' => ['required', 'string', 'max:255'],
            'dosage' => ['required', 'array', 'min:1'],
            'dosage.*' => ['required', 'string', 'max:255'],
            'instructions' => ['required', 'array', 'min:1'],
            'instructions.*' => ['required', 'string', 'max:255'],
            'general_instructions' => ['nullable','max:255']
        ];
    }

    public function messages()
    {
        return [
            'medicines.min' => 'Please provide at least one medicines.',
            'medicines.*.required' => 'Each medicines entry must have a value.',
            'dosage.min' => 'Please provide at least one dosage instruction.',
            'dosage.*.required' => 'Each dosage entry must have a value.',
            'instructions.min' => 'Please provide at least one instruction.',
            'instructions.*.required' => 'Each instructions entry must have a value.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
        ], 422));
    }
}

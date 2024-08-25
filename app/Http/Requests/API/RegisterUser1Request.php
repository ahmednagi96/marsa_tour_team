<?php

namespace App\Http\Requests\API;



use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class RegisterUser1Request extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }
    protected function prepareForValidation()
    {
        // Normalize phone by removing leading zero
        $this->merge([
            'phone' => ltrim($this->input('phone'), '0'),
        ]);
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'unique:users,phone,'],
            'country_code' => ['required', 'string'],
          //  'password'=>['required','confirmed',Password::min(8)->numbers()->uncompromised()]
        ];
    }
     protected function failedValidation(Validator $validator){
        throw new ValidationException($validator,SendResponse(422,'validation error...',$validator->errors()));
    }
}

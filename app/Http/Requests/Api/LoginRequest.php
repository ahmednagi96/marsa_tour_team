<?php

namespace App\Http\Requests\API;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'login' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    } elseif (preg_match('/^\d+$/', $value)) {
                    } else {
                        $fail('The login field must be a valid email or a phone number');
                    }
                },
            ],
            'password' => 'required|string|min:8',
        ];
    }

    public function messages(){
        return [
            'phone.regex'=>'this phone must be start with 0'
        ];
    }
     protected function failedValidation(Validator $validator){
        throw new ValidationException($validator,SendResponse(422,'validation error...',$validator->errors()));
    }
}

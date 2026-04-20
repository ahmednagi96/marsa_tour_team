<?php

namespace App\Http\Requests\API;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class RegisterUser2Request extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'=>'required|string',
            'email'=>'required|email|string|unique:users,email',
            'country'=>'required|string',
            'photo'=>'nullable|image|mimes:jpeg,png,jpg,gif,jfif,svg',
            'password'=>['required','confirmed',Password::min(8)->numbers()->uncompromised()]
        ];
    }
     protected function failedValidation(Validator $validator){
        throw new ValidationException($validator,SendResponse(422,'validation error...',$validator->errors()));
    }
}

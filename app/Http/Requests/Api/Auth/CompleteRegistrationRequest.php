<?php

namespace App\Http\Requests\Api\Auth; 

use App\Http\Requests\Api\BaseRequest;

class CompleteRegistrationRequest extends BaseRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [ 
            "name"               =>"required|string|min:6",
            "email"              =>"required|unique:users,email|email",
            "country_id"         =>"required|exists:countries,id",
            'registration_token' => 'required',
        ];
    }
}

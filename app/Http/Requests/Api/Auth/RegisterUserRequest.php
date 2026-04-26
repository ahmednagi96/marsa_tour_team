<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\BaseRequest;

class RegisterUserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
          //  'name' => 'required|string',
            'phone' => 'required|',
            //'password' => 'required|min:8|confirmed'
        ];
    }

    
}

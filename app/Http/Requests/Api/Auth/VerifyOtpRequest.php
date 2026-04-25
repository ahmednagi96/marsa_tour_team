<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\BaseRequest;

class VerifyOtpRequest extends BaseRequest
{
    public function rules(): array
    {
        return ['phone' => 'required', 'otp' => 'required|numeric|digits:6'];
    }
}

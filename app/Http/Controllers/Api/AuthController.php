<?php

namespace App\Http\Controllers\Api;

use App\Actions\CompleteRegisterUserAction;
use App\Http\Requests\Api\Auth\CompleteRegistrationRequest;
use App\Http\Requests\Api\Auth\RegisterUserRequest;
use App\Http\Requests\Api\Auth\VerifyOtpRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function __construct(
        protected AuthService $authService,
        protected CompleteRegisterUserAction $completeUserRegisteration
    ) {}

    public function sendOtp(RegisterUserRequest $request)
    {
        $data = $request->validated();
        $this->authService->sendSmsNotify($data['phone']);
        return $this->success([
          //  'otp' => config('app.env') == 'local' ? $this->authService->getOtp($data['phone']) : false,
            'phone' => $data['phone'],
        ], 'OTP sent to your phone.', 201);
    }

    // 2. Verify OTP
    public function verifyOtp(VerifyOtpRequest $request)
    {
        $result = $this->authService->verifyPhoneOtp(
            phone: $request->phone,
            otp: $request->otp
        );
        return $this->success(
            $result['data'],
            $result['message'],
            $result['is_new'] ? 201 : 200
        );
    }



    public function completeRegistration(CompleteRegistrationRequest $request)
    {
        $data = $request->validated();
        $response = $this->completeUserRegisteration->handle($data);
        return $this->success($response, __("auth.registration_completed"), 201);
    }

    public function show()
    {
        $user = Auth::user();
        return $this->success(new AuthResource([
            'user'  => $user,
            'token' => $user->token(),
        ]), "user retrieved successfully ", 200);
    }
}

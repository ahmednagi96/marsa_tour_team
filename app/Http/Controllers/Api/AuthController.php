<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidOtpException;
use App\Http\Requests\Api\Auth\RegisterUserRequest;
use App\Http\Requests\Api\Auth\VerifyOtpRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class AuthController extends BaseController
{
    public function __construct(protected AuthService $authService) {}

    public function sendOtp(RegisterUserRequest $request)
    {
        $data = $request->validated();

        // $this->authService->sendSmsNotify($data['phone']);

        return $this->success([
            'otp' => config('app.env') == 'local' ? $this->authService->getOtp($data['phone']) : false,
            'phone' => $data['phone'],
        ], 'OTP sent to your phone.', 201);
    }

    // 2. Verify OTP
    public function verifyOtp(VerifyOtpRequest $request)
    {
        // The service handles all the logic and throws specific exceptions
        $result = $this->authService->verifyPhoneOtp(
            phone: $request->phone,
            otp: $request->otp
        );

        // Dynamic status code: 200 for login, 201 for "created intent" (new user)
        $statusCode = $result['is_new'] ? 201 : 200;

        return $this->success(
            $result['data'],
            $result['message'],
            $statusCode
        );
    }

    public function completeRegistration(Request $request)
    {
        $request->validate([
            'registration_token' => 'required',
            'name' => 'required',
            // باقي البيانات...
        ]);

        // تأكد أن الـ Token موجود في Redis وصحيح
        $phone = Redis::get('reg_token_' . $request->registration_token);

        if (!$phone) {
            return response()->json(['error' => 'انتهت صلاحية التسجيل أو الرمز غير صالح'], 422);
        }

        // الآن ننشئ اليوزر بأمان لأننا ضمنا أنه مر بمرحلة الـ OTP
        $user = User::create([
            'name' => $request->name,
            'phone' => $phone,
            'password' => Hash::make($request->password),
            'profile_completed' => true
        ]);

        Redis::del('reg_token_' . $request->registration_token); // حذف التوكن المؤقت

        return response()->json(['token' => $user->createToken('auth')->plainTextToken]);
    }
}

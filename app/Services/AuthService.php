<?php

namespace App\Services;

use App\Exceptions\InvalidOtpException;
use App\Exceptions\SmsRateLimitException;
use App\Exceptions\TooManyAttemptsException;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\SMSVonageNotification;
use App\Warehouses\Auth\UserManager;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class AuthService
{
   
    // 1. All keys now use the constants
    private const OTP_PREFIX = 'auth:otp:';
    private const ATTEMPTS_PREFIX = 'auth:attempts:';
    private const REG_TOKEN_PREFIX = 'auth:reg_token:';
    private const LIMITER_PREFIX = 'auth:sms_limit:'; // New: Protects your wallet

    private function getOtpKey(string $phone): string
    {
        return self::OTP_PREFIX . $phone;
    }

    private function getAttemptsKey(string $phone): string
    {
        return self::ATTEMPTS_PREFIX . $phone;
    }

    public function getOtp(string $phone): int
    {
        $limitKey = self::LIMITER_PREFIX . $phone;
        if (Redis::exists($limitKey)) {
            throw new SmsRateLimitException("استني دقيقه ي معلم");
        }
        $otp = rand(111111, 666666);
        Redis::setex($this->getOtpKey($phone), 300, $otp);
        Redis::setex($limitKey, 60, 'blocked');
        return $otp;
    }

    public function verifyPhoneOtp(string $phone, string $otp): array
    {
        $otpKey = $this->getOtpKey($phone);
        $attemptsKey = $this->getAttemptsKey($phone);

        $cachedOtp = Redis::get($otpKey);
        $attempts  = (int) Redis::get($attemptsKey);

        if ($attempts >= 5) {
            $ttl = Redis::ttl($attemptsKey);
            throw new TooManyAttemptsException("Locked out.", $ttl > 0 ? $ttl : 900);
        }
        if (!$cachedOtp || (string)$otp !== (string)$cachedOtp) {
            Redis::incr($attemptsKey);
            if ($attempts == 0) Redis::expire($attemptsKey, 900); // Set expiry on first fail
            throw new InvalidOtpException();
        }
        Redis::del([$otpKey, $attemptsKey, self::LIMITER_PREFIX . $phone]);

        $user = User::where('phone', $phone)->first();
        

        return $user ? $this->handleExistingUser($user) : $this->handleNewUser($phone);
    }

    private function handleExistingUser(User $user): array
    {
        $user->updateLastLoginAt();
        return [
            'is_new' => false,
            'message' => 'Login successful',
            'data' => [
                'user' => new UserResource($user),
                'token' => $user->createToken("auth-{$user->phone}", ['*'], now()->addMonths(2))->plainTextToken
            ]
        ];
    }

    private function handleNewUser(string $phone): array
    {
        $registrationToken = Str::random(64);
        Redis::setex(self::REG_TOKEN_PREFIX . $registrationToken, 900, $phone);

        return [
            'is_new' => true,
            'message' => 'OTP verified. Please complete registration.',
            'data' => [
                'registration_token' => $registrationToken
            ]
        ];
    }
    public function sendSmsNotify(string $phone)
    {
        try {
            $otp = $this->getOtp($phone);

            Notification::route('vonage', $phone)
                ->notify(new SMSVonageNotification($otp));
        } catch (Exception $e) {
            Log::error("فشل إرسال رسالة الـ SMS للرقم $phone. الخطأ: " . $e->getMessage());
            throw new Exception("عذراً، حدث خطأ أثناء إرسال كود التحقق. يرجى المحاولة لاحقاً.");
        }
    }
}

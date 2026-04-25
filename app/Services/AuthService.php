<?php

namespace App\Services;

use App\Exceptions\InvalidOtpException;
use App\Exceptions\SmsRateLimitException;
use App\Exceptions\TooManyAttemptsException;
use App\Models\User;
use App\Notifications\SMSVonageNotification;
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

    private function getOtpKey(string $phone): string {
        return self::OTP_PREFIX . $phone;
    }

    private function getAttemptsKey(string $phone): string {
        return self::ATTEMPTS_PREFIX . $phone;
    }

    public function getOtp(string $phone): int
    {
        // Fix: Don't let users spam "Resend SMS" (Rate Limit: 1 SMS per minute)
        $limitKey = self::LIMITER_PREFIX . $phone;
        if (Redis::exists($limitKey)) {
            throw new SmsRateLimitException("يرجى الانتظار دقيقة قبل طلب كود جديد.");      
                }

        $otp = rand(111111, 666666);
        
        // Save OTP
        Redis::setex($this->getOtpKey($phone), 300, $otp);

        // Set a 60-second lock on sending another SMS
        Redis::setex($limitKey, 60, 'blocked');


        // Logic Change: DO NOT delete attempts here. 
        // Let the lockout period (15 mins) expire naturally to prevent brute-force resending.
        
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

        // Fix: Compare as strings to be safe
        if (!$cachedOtp || (string)$otp !== (string)$cachedOtp) {
            Redis::incr($attemptsKey);
            if ($attempts == 0) Redis::expire($attemptsKey, 900); // Set expiry on first fail
            throw new InvalidOtpException();
        }

        // On Success: NOW clear everything
        Redis::del([$otpKey, $attemptsKey, self::LIMITER_PREFIX . $phone]);

        $user = User::where('phone', $phone)->first();

        return $user ? $this->handleExistingUser($user) : $this->handleNewUser($phone);
    }

    private function handleExistingUser(User $user): array
    {
        return [
            'is_new' => false,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $user->createToken("auth-{$user->phone}",['*'],now()->addMonths(2))->plainTextToken
            ]
        ];
    }

    private function handleNewUser(string $phone): array
    {
        $registrationToken = Str::random(64);
        
        // Logic fix: Use the constant for the key
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

<?php 
namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class InvalidOtpException extends Exception
{
    use ApiResponse;

    // 1. يفضل تحديد الرسالة والكود هنا بشكل افتراضي
    protected $message = 'Invalid or expired OTP';
    protected $code = 422;

    /**
     * Render the exception into an HTTP response.
     */
    public function render(): JsonResponse
    {
        return $this->error($this->message, $this->code);
    }
}
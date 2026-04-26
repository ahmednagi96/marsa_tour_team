<?php 
namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class InvalidOtpException extends Exception
{
    use ApiResponse;
    protected $message;
    protected $code;
    public function __construct(?string $message = null,int $code=422)
    {
        $this->message= $message ??  'Invalid or expired OTP';
        $this->code=$code ?? 422;
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(): JsonResponse
    {
        return $this->error($this->message, $this->code);
    }
}
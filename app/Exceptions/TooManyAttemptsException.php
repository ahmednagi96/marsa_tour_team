<?php 
namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class TooManyAttemptsException extends Exception
{
    use ApiResponse;

    protected $seconds;

    public function __construct($message = "", $seconds = 0)
    {
        parent::__construct($message);
        $this->seconds = $seconds;
    }

    public function render($request): JsonResponse
    {
        // هنا نقوم بترتيب البيانات لتناسب معامل $errors في التريت الخاص بك
        $errorDetails = [
            'error_code' => 'AUTH_LOCKED_OUT',
            'retry_after_seconds' => (int) $this->seconds,
        ];

        // استدعاء الدالة: المعامل الأول الرسالة، الثاني الكود (429)، الثالث مصفوفة الأخطاء
        return $this->error(
            $this->getMessage() ?: 'Too many attempts. Please try again later.',
            429,
            $errorDetails
        );
    }
    public function getSeconds() {
        return (int) $this->seconds;
    }
}
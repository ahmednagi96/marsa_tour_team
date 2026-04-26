<?php

namespace App\Traits;

use App\Exceptions\InvalidOtpException;
use App\Exceptions\PhoneAlreadyExistsException;
use App\Exceptions\SmsRateLimitException;
use App\Exceptions\TooManyAttemptsException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

trait HandleException
{
    use ApiResponse;

    public function handleApiExceptions(Throwable $e): JsonResponse
    {
        $status = $this->getStatusCode($e);
        $errors = null;
        $message = $e->getMessage();



        switch (true) {
            case $e instanceof ValidationException:
                // Validation usually needs a specific header message
                $message = $e->getMessage() ?: __('exceptions.validation_error');
                $errors = $e->errors();
                break;

            case $e instanceof TooManyAttemptsException:                    // 1. استخراج الرسالة المخصصة (إذا كتبت رسالة عند استدعاء الاستثناء)
                $message = $e->getMessage() ?: "لقد تجاوزت عدد المحاولات المسموح بها.";
                $errors = [
                    'error_code' => 'AUTH_LOCKED_OUT',
                    'retry_after_seconds' => $e->getSeconds(),
                ];
                break;
            case $e instanceof InvalidOtpException:
                // Validation usually needs a specific header message
                $message = $e->getMessage();
                break;

            case $e instanceof ModelNotFoundException:
                // Only use friendly message if no custom message was passed to findOrFail()
                $message = $this->getFriendlyModelNotFoundMessage($e);
                break;

            case $e instanceof AuthenticationException:
                $message = $e->getMessage() !== 'Unauthenticated.' ? $e->getMessage() : __('exceptions.unauthenticated');
                break;

            case $e instanceof ThrottleRequestsException:
                // For throttling, we usually prefer the calculated time message
                $message = $this->getThrottleMessage($e);
                break;
            case $e instanceof PhoneAlreadyExistsException:
                // For throttling, we usually prefer the calculated time message
                $message = $e->getMessage();
                break;

            case $status === 500:
                $message = config('app.debug') ? $e->getMessage() : __('exceptions.server_error');
                break;

            default:
                // For any other exception, if getMessage() is empty, use a fallback
                $message = $e->getMessage() ?: __('exceptions.default_error');
                break;
        }

        return $this->error($message, $status, $errors);
    }

    /**
     * Determine the status code accurately
     */
    private function getStatusCode(Throwable $e): int
    {
        return match (true) {
            $e instanceof ValidationException,
            $e instanceof InvalidOtpException => 422,
            $e instanceof AuthenticationException => 401,
            $e instanceof AuthorizationException => 403,
            $e instanceof ModelNotFoundException => 404,
            $e instanceof ThrottleRequestsException,
            $e instanceof TooManyAttemptsException,
            $e instanceof SmsRateLimitException => 429,
            $e instanceof PhoneAlreadyExistsException => 409,
            $e instanceof HttpExceptionInterface => $e->getStatusCode(),
            default => 500,
        };
    }

    /**
     * Extract a user-friendly message for 404 Model Not Found
     */
    private function getFriendlyModelNotFoundMessage(ModelNotFoundException $e): string
    {
        // If the message is NOT the default Laravel "No query results...", show it!
        if (!empty($e->getMessage()) && !str_contains($e->getMessage(), 'No query results')) {
            return $e->getMessage();
        }

        $modelName = class_basename($e->getModel());
        $translatedModel = __("models.$modelName");

        if ($translatedModel === "models.$modelName") {
            $translatedModel = $modelName;
        }

        return __('exceptions.model_not_found', ['model' => $translatedModel]);
    }

    /**
     * Calculate remaining time for Throttle exceptions
     */
    private function getThrottleMessage(ThrottleRequestsException $e): string
    {
        $headers = $e->getHeaders();
        $seconds = $headers['Retry-After'] ?? 60;

        return __('exceptions.throttle', ['seconds' => $seconds]);
    }
}

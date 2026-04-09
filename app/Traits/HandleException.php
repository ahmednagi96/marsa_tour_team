<?php

namespace App\Traits;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

trait HandleException
{
    use ApiResponse;

    public function handleApiExceptions(Throwable $e): JsonResponse
    {
        $status = match (true) {
            $e instanceof ValidationException => 422,
            $e instanceof ThrottleRequestsException => 429,
            $e instanceof ModelNotFoundException => 404,
            $e instanceof AuthenticationException => 401,
            default => 500
        };

        // تجهيز الأخطاء (Errors Payload)
        $errors = ($e instanceof ValidationException) ? $e->errors() : null;

        // صياغة الرسالة باستخدام Localization
        $message = match ($status) {
            422 => __('exceptions.validation_error'),
            404 => __('exceptions.not_found'),
            401 => __('exceptions.unauthenticated'),
            429 => $e instanceof ThrottleRequestsException
                ? $this->getThrottleMessage($e)
                : __('exceptions.throttle', ['seconds' => 60]),
            500 => config('app.debug') ? $e->getMessage() : __('exceptions.server_error'),
            default => $e->getMessage(),
        };

        return $this->error($message, $status, $errors);
    }

    private function getThrottleMessage(ThrottleRequestsException $e): string
    {
        $seconds = $e->getHeaders()['Retry-After'] ?? 60;

        return __('api.exceptions.throttle', ['seconds' => $seconds]);
    }
}

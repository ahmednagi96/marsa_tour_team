<?php

namespace App\Exceptions;

use App\Traits\HandleException; // تأكد من المسار الصح للـ Trait
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    use HandleException; // بنستخدم الـ Trait هنا مباشرة

    /**
     * A list of exception types with their corresponding custom log levels.
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * الميثود المسؤولة عن شكل الرد (Response)
     */
    public function render($request, Throwable $e)
    {
        // لو الطلب جاي لـ API أو باعت Header "Accept: application/json"
        if ($request->is('api/*') || $request->expectsJson()) {
            return $this->handleApiExceptions($e);
        }

        return parent::render($request, $e);
    }
}
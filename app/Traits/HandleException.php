<?php

namespace App\Traits;

use App\Exceptions\SmsRateLimitException;
use App\Exceptions\TooManyAttemptsException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

trait HandleException
{
    use ApiResponse;

    public function handleApiExceptions(Throwable $e): JsonResponse
    {
        // 1. تحديد الـ HTTP Status Code
        $status = match (true) {
            $e instanceof ValidationException => 422,
            $e instanceof ThrottleRequestsException => 429,
            $e instanceof TooManyAttemptsException => 429,
            $e instanceof SmsRateLimitException => 429,
            $e instanceof ModelNotFoundException => 404,
            $e instanceof AuthenticationException => 401,
            $e instanceof AuthorizationException => 403,
            default => 500
        };

        // 2. تجهيز الأخطاء (في حالة الـ Validation فقط)
        $errors = match (true) {
            $e instanceof ValidationException => $e->errors(),
            // هنا نجلب الثواني المتبقية من الإكسيبشن الخاص بك
            $e instanceof TooManyAttemptsException => ['retry_after_seconds' => $e->getSeconds()],
            default => null
        };
        // 3. صياغة الرسالة النهائية
        $message = match ($status) {
            422 => __('exceptions.validation_error'),
            $e instanceof \App\Exceptions\TooManyAttemptsException => $e->getMessage(),
            
            // تعديل الـ 404 لجلب رسالة ذكية ومترجمة
            404 => $e instanceof ModelNotFoundException 
                ? $this->getFriendlyModelNotFoundMessage($e) 
                : __('exceptions.not_found'),
                
            401 => __('exceptions.unauthenticated'),
            403 => __('exceptions.unauthorized'),
            
            429 => $e instanceof ThrottleRequestsException
                ? $this->getThrottleMessage($e)
                : __('exceptions.throttle', ['seconds' => 60]),
                
            // في حالة الـ Server Error (500) بنظهر الخطأ الحقيقي فقط في وضع الـ Debug
            500 => config('app.debug') ? $e->getMessage() : __('exceptions.server_error'),
            $e instanceof \App\Exceptions\SmsRateLimitException => $e->getMessage()   
        ,

            
            default => $e->getMessage(),
        };

        return $this->error($message, $status, $errors);
    }

    /**
     * استخراج رسالة خطأ صديقة للمستخدم عند عدم وجود موديل (404)
     */
    private function getFriendlyModelNotFoundMessage(ModelNotFoundException $e): string
    {
        // إذا كان هناك رسالة مخصصة تم تمريرها يدوياً (مثلاً في findOrFail("Message"))
        if (!empty($e->getMessage()) && !str_contains($e->getMessage(), 'No query results')) {
            return $e->getMessage();
        }

        // استخراج اسم الموديل من المسار (App\Models\Tour -> Tour)
        $modelPath = $e->getModel();
        $modelName = class_basename($modelPath);

        // محاولة ترجمة اسم الموديل من ملف الـ Translation
        // تأكد من وجود ملف lang/ar/models.php يحتوي على: 'Tour' => 'الجولة'
        $translatedModel = __("models.$modelName");

        // إذا لم توجد ترجمة لاسم الموديل، استخدم الاسم كما هو
        if ($translatedModel === "models.$modelName") {
            $translatedModel = $modelName;
        }

        return __('exceptions.model_not_found', ['model' => $translatedModel]);
    }

    /**
     * حساب الوقت المتبقي لمحاولات تسجيل الدخول أو الـ API Rate Limit
     */
    private function getThrottleMessage(ThrottleRequestsException $e): string
    {
        $headers = $e->getHeaders();
        $seconds = $headers['Retry-After'] ?? 60;

        return __('exceptions.throttle', ['seconds' => $seconds]);
    }
}
<?php 


namespace App\Traits;

trait HandleException
{
    public function handleApiExceptions(\Throwable $e)
    {
        // 1. تحديد الـ Status Code
        $status = match (true) {
            $e instanceof \Illuminate\Validation\ValidationException => 422,
            $e instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException => 429,
            $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException => 404,
            $e instanceof \Illuminate\Auth\AuthenticationException => 401,
            default => 500
        };

        // 2. تجهيز الأخطاء (Errors Payload)
        $errors = null;
        if ($e instanceof \Illuminate\Validation\ValidationException) {
            $errors = $e->errors();
        } elseif ($status == 429) {
            $errors = ['wait' => 'يرجى المحاولة لاحقاً'];
        }

        // 3. صياغة الرسالة
        $message = match ($status) {
            422 => 'البيانات المرسلة غير صحيحة',
            // 429 => 'طلبات كثيرة جداً، اهدى شوية!',
            404 => 'المورد غير موجود',
            401 => 'غير مصرح لك بالدخول',
            500 => config('app.debug') ? $e->getMessage() : 'خطأ داخلي في السيرفر',
            default => $e->getMessage(),
        };
        if ($e instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException) {
            $seconds = $e->getHeaders()['Retry-After'] ?? 60;
            $message = "يرجى الانتظار {$seconds} ثانية قبل المحاولة مرة أخرى";
        }

        // 4. الرد الموحد "الأسطوري"
        return response()->json([
            'success'     => false,
            'status_code' => $status,
            'message'     => $message,
            'data'        => null,
            'errors'      => $errors,
        ], $status);
    }
}
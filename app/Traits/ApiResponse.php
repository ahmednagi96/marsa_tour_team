<?php 
namespace App\Traits;

use Illuminate\Http\JsonResponse;


trait ApiResponse
{
    protected function success($data = [], ?string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'success'     => true,
            'status_code' => $code, // إضافة الكود هنا
            'message'     => $message,
            'data'        => $data,
            'errors'      => null
        ], $code);
    }

    protected function error(?string $message = null, int $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'success'     => false,
            'status_code' => $code, // إضافة الكود هنا
            'message'     => $message,
            'data'        => null,
            'errors'      => $errors
        ], $code);
    }

    
}
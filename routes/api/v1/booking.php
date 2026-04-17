<?php

use App\Http\Controllers\API\V1\Booking\BookingController;
use Illuminate\Support\Facades\Route;

// لاحظ إضافة النقطة في الـ "as" لتنظيم الأسماء

Route::controller(BookingController::class)->middleware("auth:sanctum")->prefix('bookings')->as('bookings.')->group(function () {

    // إنشاء حجز جديد (POST /api/v1/bookings)>
    Route::post('/', 'checkout')
    ->withoutMiddleware('throttle:api')
    ->middleware('throttle:bookings')
    ->name('store');

    // جلب تفاصيل حجز معين (GET /api/v1/bookings/{id})
    Route::get('/{id}', 'show')->name('show');

    // جلب كل حجوزات المستخدم الحالي (GET /api/v1/bookings)
    Route::get('/', 'index')->name('index');

    Route::get('/{booking}/pay', 'getPaymentLink')
    ->middleware('throttle:10,1')
    ->name('pay'); // الراوت الجديد
});

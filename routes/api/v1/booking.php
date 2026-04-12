<?php

use App\Http\Controllers\API\V1\Booking\BookingController;
use Illuminate\Support\Facades\Route;

// لاحظ إضافة النقطة في الـ "as" لتنظيم الأسماء

Route::controller(BookingController::class)->middleware("auth:sanctum")->prefix('bookings')->as('bookings.')->group(function () {

    // إنشاء حجز جديد (POST /api/v1/bookings)>
    Route::post('/', 'store')->name('store');

    // جلب تفاصيل حجز معين (GET /api/v1/bookings/{id})
    Route::get('/{id}', 'show')->name('show');

    // جلب كل حجوزات المستخدم الحالي (GET /api/v1/bookings)
    Route::get('/', 'index')->name('index');
});

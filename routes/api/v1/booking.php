<?php

use App\Http\Controllers\Api\V1\Booking\BookingController;
use Illuminate\Support\Facades\Route;

// لاحظ إضافة النقطة في الـ "as" لتنظيم الأسماء

Route::controller(BookingController::class)->middleware("auth:sanctum")->prefix('bookings')->as('bookings.')->group(function () {

    Route::post('/checkout', 'checkout')
    ->middleware(['throttle:api'])
    ->name('store');

    
    Route::get('/{id}', 'show')->name('show');

    // جلب كل حجوزات المستخدم الحالي (GET /api/v1/bookings)
    Route::get('/', 'index')->name('index');

    Route::get('/{booking}/pay', 'getPaymentLink')
    ->middleware('throttle:10,1')
    ->name('pay'); // الراوت الجديد
});

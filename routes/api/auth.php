<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('otp/send', 'sendOtp');
    Route::post('otp/verify', 'verifyOtp');
    Route::post('register/complete', 'completeRegistration');
});

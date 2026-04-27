<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::middleware("guest:sanctum")->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('otp/send', 'sendOtp');
        Route::post('otp/verify', 'verifyOtp');
        Route::post('register/complete', 'completeRegistration');
    });
    Route::controller(SocialiteController::class)->group(function () {
        Route::get('/{provider}/redirect',  'redirect')
            ->name('socialite.redirect')
            ->whereIn('provider', ['google', 'github']);

        Route::get('/{provider}/callback', [SocialiteController::class, 'callback'])
            ->name('socialite.callback')
            ->whereIn('provider', ['google', 'github']);
    });
});

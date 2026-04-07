<?php

use App\Http\Controllers\Dashboard\ContactusController;
use App\Http\Controllers\Dashboard\PrivacyController;
use App\Http\Controllers\Dashboard\SocialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OfferControleller;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\Dashboard\TourController;
use App\Http\Controllers\Dashboard\TripController;
use App\Http\Controllers\Dashboard\LoginController;
use App\Http\Controllers\Dashboard\OfferController;
use App\Http\Controllers\Dashboard\WidgetController;





Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//middleware('auth:sanctum')->

Route::prefix('Dashboard')->group(function(){
        Route::post('/AdminLogin',[LoginController::class,'login']);
        Route::post('/register',[LoginController::class,'register']);
    });

    Route::apiResource('policys',PrivacyController::class)->except('update');

    Route::post('policys/{id}',[PrivacyController::class,'update'])->missing(function(){
        return sendResponse(404,'not found Policys',null);
    });

    Route::post('contactus/{id}',[ContactusController::class,'update'])->missing(function(){
        return sendResponse(404,'not found contactus',null);
    });

Route::middleware('auth:sanctum')->name('dashboard.')->prefix('Dashboard')->group(function(){
            Route::get('/Admins',[LoginController::class,'Admins']);
            Route::delete('/Admins/{id}',[LoginController::class,'delete']);

            Route::post('trips/{trip}',[TripController::class,'update'])->missing(function(){
            return sendResponse(404,'not found trip',null);
            });
            Route::apiResource("trips",TripController::class)->except('update')->missing(function(){
            return sendResponse(404,'not found trip',null);
            });
            Route::get('tours/favourite',[TourController::class,'is_favourite']);
            Route::post('tours/{id}',[TourController::class,'update'])->missing(function(){
            return sendResponse(404,'not found trip',null);
            });

            Route::apiResource("tours",TourController::class)->except('update')->missing(function(){
            return sendResponse(404,'not found trip',null);
            });
            Route::Post("offers/{id}",[OfferController::class,'update'])->missing(function(){
                return sendResponse(404,'not found trip',null);
                });
            Route::apiResource("offers",OfferController::class)->except('update')->missing(function(){
            return sendResponse(404,'not found trip',null);
            });

            Route::get('/widget',[WidgetController::class,'widget']);

            Route::get('/counts',[WidgetController::class,'counts']);

            Route::get('/search', [TripController::class, 'search']);

            Route::apiResource('socials',SocialController::class)->except('update');

            Route::post('socials/{id}',[SocialController::class,'update'])->missing(function(){
                return sendResponse(404,'not found socials',null);
            });

        });

Route::prefix('mobile')->group(function(){


    Route::controller(AuthController::class)
    ->middleware('guest:sanctum')
    ->name('api.auth.')
    ->prefix('auth')
    ->group(function () {
        Route::post('/register-user-page1', 'registerUser1')->name('registerUser_page1');
        Route::post('/register-user-page2/{user}', 'registerUser2')
        ->name('registerUser_page2')->missing(function () {
            return sendResponse(404,'Phone Not Found',null);
        });
        Route::post('/verify-phone-code/{user}', 'verifyPhoneCode')->name('verifyPhoneCode')->missing(function () {
            return sendResponse(404,'Phone Not Found',null);
        });
        Route::post('/login', 'login')->name('login');
        Route::get('/refresh-token', 'refreshToken')->name('refresh')->withoutMiddleware('guest:sanctum')->middleware('auth:sanctum');
        Route::post('/logout', 'logout')->name('logout')->withoutMiddleware('guest:sanctum')->middleware('auth:sanctum');
        Route::post('/forgot-password', 'forgotPassword')->name('forgotPassword');
        Route::post('/verify-code-password/{user}', 'verifyCodeForPassword')->name('verifyCodeForPassword')->missing(function () {
            return sendResponse(404,'Phone Not Found',null);
        });
        Route::post('/reset-password/{user}', 'resetPassword')->name('reset.password')->missing(function () {
            return sendResponse(404,'Phone Not Found',null);
        });
        Route::post('resend-code/{user}','resendCode')->name('resendCode')->name('reset.password')->missing(function () {
            return sendResponse(404,'Phone Not Found',null);
        });
        Route::get('get-auth-user','getAuthUser')->name('get.auth.user')->withoutMiddleware('guest:sanctum')->middleware('auth:sanctum');
    });

    Route::controller(HomeController::class)
    ->prefix('home')
    ->name('api.home.')
    ->group(function () {
        Route::get('/get-some-trips', 'getSomeTrips')->name('getSomeTrips');
        Route::get('/get-all-trips', 'getAllTrips')->name('getAllTrips');
        Route::get('/get-favourite-tours', 'getFavouriteTours')->name('getFavouriteTours');
        Route::get('/get-all-tours', 'getAllTours')->name('getAllTours');
        Route::get('/get-banners', 'banners')->name('banners');
        Route::get('/get-limited-offers', 'limitedOffers')->name('limitedOffers');
        Route::get('/get-all-offers', 'allOffers')->name('allOffers');
    });

});



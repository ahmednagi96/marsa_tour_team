<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\TourController;
use App\Http\Controllers\API\TripController;
use App\Http\Controllers\API\OfferController;
use App\Http\Controllers\API\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
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
    Route::controller(TripController::class)
    ->prefix('trips')
    ->name('api.trips.')
    ->group(function () {
        Route::get('/get-tours/{trip}', 'getToursForEachTrip')->name('getToursForEachTrip')->missing(function () {
            return sendResponse(404,'Trip Not Found',null);
        })->whereNumber('trip');
        Route::get('get-cities','getTourCities')->name('getTourCities');
        Route::get('filter-toursByCity','filterToursByCity')->name('filterToursByCity');
        Route::get('search-toursByName','searchToursByName')->name('searchToursByName');
    });
    Route::controller(TourController::class)
    ->prefix('tours')
    ->name('api.tours.')
    ->group(function () {
        Route::get('/get-tour/{tour}', 'getTour')
            ->name('getTour')
            ->whereNumber('tour')
            ->missing(function () {
                return sendResponse(404, 'Tour Not Found', null);
            });
        Route::post('/toggle-favouriteTour/{tour}', 'toggleAuthFavourite')
            ->name('toggleAuthFavourite')
            ->whereNumber('tour')
            ->missing(function () {
                return sendResponse(404, 'Tour Not Found', null);
            })->middleware('auth:sanctum');
    });
    Route::controller(OfferController::class)
    ->prefix('offers')
    ->name('api.offers.')
    ->group(function () {
        Route::post('/toggle-favouriteOffer/{offer}', 'toggleAuthFavourite')
            ->name('toggleAuthFavourite')
            ->whereNumber('offer')
            ->missing(function () {
                return sendResponse(404, 'Offer Not Found', null);
            })->middleware('auth:sanctum');
    });
    Route::controller(ProfileController::class)
    ->prefix('profile')
    ->name('api.profile.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/get-favourite', 'getAuthFavourite')
            ->name('getAuthFavourite');
        Route::get('/get-auth', 'index')
            ->name('index');
        Route::post('/update', 'update')
            ->name('update');
        Route::post('/update-password', 'updatePassword')
            ->name('updatePassword');
        Route::delete('/delete-auth', 'delete')
            ->name('delete');
    });

});
Route::get('/test_user',function(){
    if(auth('sanctum')->check()){
        return auth('sanctum')->user();
    }
});

<?php

use App\Http\Controllers\API\V1\TripController;
use Illuminate\Support\Facades\Route;

Route::controller(TripController::class)->as("trips.")
    ->group(function () {
       // Trips
    Route::get('/trips',  'index')->name('index');
  #  Route::get('/trips/trending', [HomeController::class, 'trendingTrips'])->name('trips.trending');

    // Tours
   # Route::get('/tours', [HomeController::class, 'indexTours'])->name('tours.index');
   # Route::get('/tours/favorites', [HomeController::class, 'favoriteTours'])->name('tours.favorites');

    // Banners & Offers
   # Route::get('/banners', [HomeController::class, 'banners'])->name('banners');
   # Route::get('/offers', [HomeController::class, 'indexOffers'])->name('offers.index');
    });
  
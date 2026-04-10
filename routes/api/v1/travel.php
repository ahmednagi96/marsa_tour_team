<?php

use App\Http\Controllers\API\V1\Travel\TourController;
use App\Http\Controllers\API\V1\Travel\TripController;
use Illuminate\Support\Facades\Route;

Route::controller(TripController::class)->as("trips.")
   ->group(function () {
      // Trips
      Route::get('/trips',  'index')->name('index');
      Route::get('/trips/{trip:id}',"show");
      Route::get('/trips/{trip:id}/tours',"tours");
      Route::get('/trips/{trip:id}/tours/{tour:id}');

   });
Route::controller(TourController::class)->as('tours.')
   ->group(function () {
      Route::get("/tours", "index")->name("index");
      Route::get("/tours/{tour:id}")->name("index");
      Route::get("/tours/discounts")->name("index");
      
});

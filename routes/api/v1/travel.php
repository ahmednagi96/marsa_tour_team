<?php

use App\Http\Controllers\API\V1\Travel\TourController;
use App\Http\Controllers\API\V1\Travel\TripController;
use Illuminate\Support\Facades\Route;

Route::controller(TripController::class)->prefix("/trips")->as("trips.")
   ->group(function () {
      // Trips
      Route::get('/',  'index')->name('index');
      Route::get('/{trip:id}',"show");
      Route::get('/{trip:id}/tours',"tours");
      Route::get('/{trip:id}/tours/{tour:id}',"tripTour")->scopeBindings();

   });
Route::controller(TourController::class)->prefix("/tours")->as('tours.')
   ->group(function () {
      Route::get("/", "index")->name("index");
      Route::get("/{tour:id}","show")->name("index");
      Route::get("/{tour:id}/daily","checkDate")->name("checkDate");

      
});

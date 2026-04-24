<?php

use App\Http\Controllers\Api\V1\Payemnt\PaymentController;
use Illuminate\Support\Facades\Route;


Route::controller(PaymentController::class)->prefix("/payment")->as("payment.")
 ->group(function () {  

   Route::get("callback","callback")->name("callback");
   Route::post("webhook","webhook")->name("webhook");



 });


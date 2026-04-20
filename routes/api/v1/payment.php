<?php

use App\Infrastructure\Payment\TapSDK;
use Illuminate\Support\Facades\Route;


Route::controller(TapSDK::class)->prefix("/payment")->as("payment.")
 ->group(function () {  
   Route::post("charge","charge")->name("chrage");
   Route::get("callback","callback")->name("callback");
   Route::post("webhook","webhook")->name("webhook");



 });


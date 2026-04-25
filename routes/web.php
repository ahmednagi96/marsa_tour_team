<?php

use App\Events\BookingProcessed;
use App\Listeners\HandleBookingNotification;
use App\Mail\BookingStatusMail;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\SMSVonageNotification;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get("test-url",function(Dispatcher $events){
   // return route("v1.payment.webhook");
  // $booking=Booking::latest()->first();
   #Mail::to("ahmednagi_96@icloud.com")->send(new BookingStatusMail($booking,"success","https://test.com"));
  // $events->dispatch(new BookingProcessed($booking,'success'));

 $user=User::find(1);
$user->notify(new SMSVonageNotification());
Log('user ....');
});
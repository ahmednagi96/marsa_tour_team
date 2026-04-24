<?php 

namespace App\Infrastructure\Payment;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface PaymentCallbackInterface{
    
    public function callback():JsonResponse;
    public function webhook(Request $request):void;

}
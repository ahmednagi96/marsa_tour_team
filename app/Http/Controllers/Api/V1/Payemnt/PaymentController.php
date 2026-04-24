<?php 

namespace App\Http\Controllers\Api\V1\Payemnt;

use App\Http\Controllers\Api\BaseController;
use App\Infrastructure\Payment\PaymentCallbackInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends BaseController
{
    public function __construct(protected PaymentCallbackInterface $paymentCallback)
    {
        
    }
    public function callback(){
      return $this->paymentCallback->callback();
    }
    
    public function webhook(Request $request){
        Log::info("webhook work successfully !");
        return $this->paymentCallback->webhook($request);
    }

}
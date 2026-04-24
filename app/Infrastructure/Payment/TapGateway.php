<?php 

namespace App\Infrastructure\Payment;

use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TapGateway implements PaymentInterface,PaymentCallbackInterface
{
    public function __construct(private TapSDK $tapSdk)
    {
        
    }

    public function chrage(Booking $booking):array{
       return $this->tapSdk->charge($booking);
    }

    public function callback():JsonResponse{
        return $this->tapSdk->callback();
    }

    public function provider():PaymentProvider
    {
        return PaymentProvider::TAP;
    }

    public function webhook(Request $request): void
    {
        $this->tapSdk->webhook($request);
    }

}
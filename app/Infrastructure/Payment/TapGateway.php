<?php 

namespace App\Infrastructure\Payment;

use App\Models\Booking;

class TapGateway implements PaymentInterface
{
    public function __construct(private TapSDK $tapSdk)
    {
        
    }

    public function chrage(Booking $booking):array{
       return $this->tapSdk->charge($booking);
    }

    public function provider():PaymentProvider
    {
        return PaymentProvider::TAP;
    }

}
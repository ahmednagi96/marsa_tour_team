<?php 

namespace App\Actions;

use App\Infrastructure\Payment\PaymentInterface;
use App\Models\Booking;

class ChargePaymentAction{
     public function __construct(protected PaymentInterface $payment)
     {
        //
     }
    public function handle(Booking $booking):array{
      return  $this->payment->chrage($booking);
    }
}
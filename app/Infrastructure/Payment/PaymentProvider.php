<?php 

namespace App\Infrastructure\Payment;

enum PaymentProvider:string
{
    case TAP = 'tap';
    public function label(): string
    {
        return match($this) {
            self::TAP => "tap",
           
        };
    }
}
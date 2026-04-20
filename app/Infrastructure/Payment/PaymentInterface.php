<?php 

namespace App\Infrastructure\Payment;

use App\Models\Booking;

interface PaymentInterface
{
    public function chrage(Booking $data):array;

}
<?php 

namespace App\Actions;

use App\Enums\PaymentStatus;
use App\Http\Resources\Payment\PaymentResource;
use App\Models\Payment;

class CreatePendingPayment
{
    public function handle(array $response,int $bookingId,string $provider):PaymentResource{
        $payemnt= Payment::create([
            'booking_id'     => $bookingId,
            'amount'         => $response['amount'],
            'currency'       => $response['currency'],
            'gateway'        => $provider,
            'transaction_id' => $response['id'], // اللي هو الـ chg_xxxx
            'status'         =>PaymentStatus::PENDING,
            'payload'        => $response, 
        ]);
    return new PaymentResource($payemnt->load('booking'));
    }
}
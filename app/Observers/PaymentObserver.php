<?php

namespace App\Observers;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Events\BookingProcessed;
use App\Models\Payment;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class PaymentObserver
{
    public function __construct(protected Dispatcher $events)
    {
    }
    /**
     * Handle the Tour "saving" event.
     */
    public function updated(Payment $payment): void
    {
        if ($payment->wasChanged('status')) {
            $booking = $payment->booking;
            if (!$booking) return;
            // داخل الـ PaymentObserver أو الـ Service
            if ($payment->status === PaymentStatus::CAPTURED) {
                $booking = $payment->booking;

                if (in_array($booking->status, [BookingStatus::EXPIRED, BookingStatus::CANCELLED])) {

                    $booking->tourAvailability()->increment('booked', $booking->adults_count);

                    Log::info("Booking {$booking->id} was re-activated after late payment.");
                }

                $booking->update(['status' => BookingStatus::COMPLETED]);
              $this->events->dispatch(new BookingProcessed($booking, 'success'));
            } else if ($payment->status === PaymentStatus::FAILED) {
                $booking->update(['status' => BookingStatus::CANCELLED]);
                $booking->tourAvailability()->decrement('booked', $booking->adults_count);
              $this->events->dispatch(new BookingProcessed($booking, 'failed'));
            }
        }
    }
}

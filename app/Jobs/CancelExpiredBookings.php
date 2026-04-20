<?php 

namespace App\Jobs;

use App\Models\Booking;
use App\Enums\BookingStatus; // تأكد من استخدام الـ Enum اللي عملناه
use App\Enums\PaymentStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CancelExpiredBookings implements ShouldQueue
{
    use Queueable;

    public function handle()
    {
        $hasChanges = false;
    
        Booking::where('status', BookingStatus::PENDING)
            ->where('created_at', '<', now()->subMinutes(15))
            ->with(['tourAvailability', 'payments']) 
            ->chunk(100, function ($expiredBookings) use (&$hasChanges) {
                
                foreach ($expiredBookings as $booking) {
                    $hasSuccessfulPayment = $booking->payments->contains('status', PaymentStatus::CAPTURED);
                    if ($hasSuccessfulPayment) continue;
                    DB::transaction(function () use ($booking, &$hasChanges) {
                        // 1. إرجاع الأماكن (Update مباشر أسرع)
                        if ($booking->tourAvailability) {
                            $booking->tourAvailability()->decrement('booked', $booking->adults_count);
                            $hasChanges = true;
                        }
                        // 2. تحديث الحجز والدفع بـ Query واحدة لكل منهما
                        $booking->update(['status' => BookingStatus::EXPIRED]);
    
                        $booking->payments()
                            ->where('status', PaymentStatus::PENDING)
                            ->update(['status' => PaymentStatus::CANCELLED]);
                    });
                }
            });
    
        if ($hasChanges) {
            Cache::tags(['tour_availabilties'])->flush();
        }
    }
}
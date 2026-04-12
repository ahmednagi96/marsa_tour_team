<?php 

namespace App\Jobs;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CancelExpiredBookings implements ShouldQueue
{
    use Queueable;

    public function handle()
    {
        // جلب الحجوزات المعلقة لأكثر من 15 دقيقة
        $expiredBookings = Booking::where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(15))
            ->get();

        foreach ($expiredBookings as $booking) {
            DB::transaction(function () use ($booking) {
                // 1. إرجاع الأماكن المتاحة
                $booking->tourAvailability()->increment('booked', -($booking->adults_count + $booking->children_count));

                // 2. تحديث الحالة
                $booking->update(['status' => 'expired']);
                
                // 3. مسح كاش المواعيد لتحديث الأرقام للجمهور
                Cache::forget("tour_slots_{$booking->tour_id}");
            });
        }
    }
}
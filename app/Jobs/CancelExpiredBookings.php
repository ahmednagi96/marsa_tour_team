<?php 

namespace App\Jobs;

use App\Models\Booking;
use App\Enums\BookingStatus; // تأكد من استخدام الـ Enum اللي عملناه
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CancelExpiredBookings implements ShouldQueue
{
    use Queueable;

    public function handle()
    {
        // 1. جلب الحجوزات اللي حالتها pending فقط 
        // 2. وبشرط إن وسيلة دفعها مش كاش (لان الكاش مش محتاج دفع لحظي)
        // 3. وعدى عليها 15 دقيقة
        $expiredBookings = Booking::where('status', BookingStatus::PENDING)
            ->where('payment_method', '!=', 'cash') 
            ->where('created_at', '<', now()->subMinutes(15))
            ->get();

        foreach ($expiredBookings as $booking) {
            DB::transaction(function () use ($booking) {
                
                // التأكد إن الحجز لسه pending (لحماية إضافية)
                if ($booking->status === BookingStatus::PENDING) {
                    
                    // 1. إرجاع الأماكن المتاحة للمخزن
                    $totalGuests = $booking->adults_count + $booking->children_count;
                    $booking->tourAvailability()->decrement('booked', $totalGuests);

                    // 2. تحديث حالة الحجز لـ Expired
                    $booking->update(['status' => BookingStatus::EXPIRED]);

                    // 3. تحديث حالة الدفع لـ Failed (اختياري بس احترافي)
                    $booking->payments()->where('status', 'pending')->update(['status' => 'failed']);

                    // 4. مسح الكاش عشان الناس تشوف إن فيه أماكن رجعت توفرت
                    Cache::forget("tour_slots_{$booking->tour_id}");
                }
            });
        }
    }
}
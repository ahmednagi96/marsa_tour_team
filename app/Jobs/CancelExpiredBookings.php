<?php 

namespace App\Jobs;

use App\Models\Booking;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelExpiredBookings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $hasChanges = false;
        
        // جلب الحجوزات المعلقة التي مر عليها 15 دقيقة
        // استخدمنا whereDoesntHave للتأكد من عدم وجود دفع ناجح برمجياً في الكويري نفسها (أسرع)
        Booking::where('status', BookingStatus::PENDING)
            ->where('created_at', '<', now()->subMinutes(15))
            ->whereDoesntHave('payments', function ($query) {
                $query->where('status', PaymentStatus::CAPTURED);
            })
            ->with(['tourAvailability']) 
            ->chunk(100, function ($expiredBookings) use (&$hasChanges) {
                
                foreach ($expiredBookings as $booking) {
                    try {
                        DB::transaction(function () use ($booking, &$hasChanges) {
                            
                            // 1. إرجاع الأماكن للـ Availability
                            if ($booking->tourAvailability) {
                                $booking->tourAvailability()->decrement('booked', $booking->adults_count);
                                $hasChanges = true;
                            }

                            // 2. تحديث حالة الحجز إلى EXPIRED
                            $booking->update(['status' => BookingStatus::EXPIRED]);
        
                            // 3. إلغاء أي دفعات معلقة مرتبطة بهذا الحجز
                            $booking->payments()
                                ->where('status', PaymentStatus::PENDING)
                                ->update(['status' => PaymentStatus::CANCELLED]);
                        });
                    } catch (\Exception $e) {
                        Log::error("Failed to expire booking ID: {$booking->id}. Error: {$e->getMessage()}");
                    }
                }
            });
    
        // تنظيف الكاش مرة واحدة في النهاية إذا حدثت تغييرات
        if ($hasChanges) {
            Cache::tags(['tour_availabilties'])->flush();
            Log::info("Expired bookings processed and cache flushed.");
        }
    }
}
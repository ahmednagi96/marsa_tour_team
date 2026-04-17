<?php

namespace App\Services\Booking;

use App\Models\Booking;
use App\Models\TourAvailability;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Exception;

class BookingService
{
    // داخل BookingService.php

public function createBooking(array $data, $user)
{
    $lockKey = "booking_lock_availability_{$data['availability_id']}";

    return Cache::lock($lockKey, 10)->block(5, function () use ($data, $user) {
        return DB::transaction(function () use ($data, $user) {
            
            $availability = TourAvailability::findOrFail($data['availability_id']);
            $totalGuests = $data['adults_count'] + $data['children_count'];

            if ($availability->available_seats < $totalGuests) {
                throw new Exception("عفواً، لا توجد أماكن كافية.");
            }

            // الحسابات المالية (نفس الكود السابق)
            $childPrice = $availability->child_price ?? $availability->tour->default_child_price;
            $grandTotal = ($data['adults_count'] * $availability->adult_price) + ($data['children_count'] * $childPrice);

            // تحديد حالة الحجز بناءً على وسيلة الدفع
            // لو كاش، ممكن نخليه confirmed فوراً أو pending_approval
            $status = ($data['payment_gateway'] === 'cash') ? 'confirmed' : 'pending';

            $booking = Booking::create([
                'user_id' => $user->id,
                'tour_id' => $availability->tour_id,
                'tour_availability_id' => $availability->id,
                'travel_date' => $availability->date,
                'adults_count' => $data['adults_count'],
                'children_count' => $data['children_count'],
                'adult_price' => $availability->adult_price,
                'child_price' => $childPrice,
                'total_price' => $grandTotal,
                'status' => $status,
                'payment_method' => $data['payment_gateway'], // تأكد من وجود العمود ده
            ]);

            // إنشاء سجل دفع فقط إذا كان أونلاين
            if ($data['payment_gateway'] !== 'cash') {
                $booking->payments()->create([
                    'amount' => $grandTotal,
                    'gateway' => $data['payment_gateway'],
                    'status' => 'pending',
                ]);
            }

            $availability->increment('booked', $totalGuests);
            Cache::forget("tour_slots_{$availability->tour_id}");

            return $booking;
        });
    });
}
}

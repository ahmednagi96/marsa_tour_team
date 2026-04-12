<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\TourAvailability;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Exception;

class BookingService
{
    public function createBooking(array $data, $user)
    {
        // اسم مفتاح القفل الفريد بناءً على اليوم المختار
        $lockKey = "booking_lock_availability_{$data['availability_id']}";

        // 1. محاولة الحصول على "قفل" من Redis لمدة 10 ثواني
        // ده بيمنع أي Request تاني لنفس اليوم يدخل الكود ده في نفس الوقت
        return Cache::lock($lockKey, 10)->block(5, function () use ($data, $user) {

            return DB::transaction(function () use ($data, $user) {

                // 2. التحقق من التوفر (من الداتابيز مباشرة لضمان الدقة)
                $availability = TourAvailability::findOrFail($data['availability_id']);
                $totalGuests = $data['adults_count'] + $data['children_count'];

                if ($availability->available_seats < $totalGuests) {
                    throw new Exception("عفواً، تم خطف الأماكن المتاحة للتو! المتبقي: {$availability->available_seats}");
                }

                // 3. الحسابات المالية
                $childPrice = $availability->child_price ?? $availability->tour->default_child_price;
                $grandTotal = ($data['adults_count'] * $availability->adult_price) +
                    ($data['children_count'] * $childPrice);

                // 4. إنشاء الحجز
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
                    'status' => 'pending',
                ]);

                // 5. إنشاء محاولة الدفع
                $booking->payments()->create([
                    'amount' => $grandTotal,
                    'gateway' => $data['payment_gateway'],
                    'status' => 'pending',
                ]);

                // 6. تحديث المخزون (Inventory Update)
                $availability->increment('booked', $totalGuests);

                // 7. مسح كاش عرض المواعيد (عشان اليوزر اللي جاي يشوف الرقم الجديد)
                Cache::forget("tour_slots_{$availability->tour_id}");

                return $booking;
            });
        });
    }
}

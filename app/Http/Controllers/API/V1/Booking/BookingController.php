<?php

namespace App\Http\Controllers\API\V1\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Booking\StoreBookingRequest;
use App\Services\BookingService;
use App\Services\PaymentService; // الخدمة اللي بتكلم بوابة الدفع
use Illuminate\Http\JsonResponse;
use Exception;

class BookingController extends Controller
{
    protected $bookingService;
    protected $paymentService;

    public function __construct(BookingService $bookingService, 
    PaymentService $paymentService
    )
    {
        $this->bookingService = $bookingService;
        $this->paymentService = $paymentService;
    }

    /**
     * إنشاء حجز جديد
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        try {
            // 1. تنفيذ عملية الحجز (بداخلها الـ Redis Lock والـ Transaction)
            $booking = $this->bookingService->createBooking(
                $request->validated(),
                $request->user()
            );

            // 2. طلب رابط الدفع من بوابة الدفع (Stripe/Paymob)
            // بنبعت أول سجل دفع مرتبط بالحجز
            $payment = $booking->payments->first();
            $paymentResponse = $this->paymentService->generateLink($payment);

            return response()->json([
                'status'  => 'success',
                'message' => 'تم إنشاء الحجز المبدئي بنجاح، برجاء إتمام الدفع.',
                'data'    => [
                    'booking_id'   => $booking->id,
                    'total_price'  => $booking->total_price,
                    'payment_url'  => $paymentResponse['url'], // الرابط اللي اليوزر هيدفع عليه
                    'expires_at'   => now()->addMinutes(15)->toDateTimeString(), // تنبيه لليوزر بالوقت
                ]
            ], 201);
        } catch (Exception $e) {
            // في حالة فشل الـ Lock أو عدم وجود أماكن
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }
}

<?php

namespace App\Http\Controllers\API\V1\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Booking\StoreBookingRequest;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\PaymentService; // الخدمة اللي بتكلم بوابة الدفع
use Illuminate\Http\JsonResponse;
use Exception;

class BookingController extends Controller
{
    protected $bookingService;
    protected $paymentService;

    public function __construct(BookingService $bookingService, 
   // PaymentService $paymentService
    )
    {
        $this->bookingService = $bookingService;
        //$this->paymentService = $paymentService;
    }

    /**
     * إنشاء حجز جديد
     */
  // داخل BookingController.php

public function store(StoreBookingRequest $request): JsonResponse
{
    try {
        $booking = $this->bookingService->createBooking($request->validated(), $request->user());

        $responseData = [
            'booking_id'  => $booking->id,
            'total_price' => $booking->total_price,
            'status'      => $booking->status,
        ];

        // لو الدفع أونلاين، نولد الرابط ونضيفه للرد
        if ($booking->payment_method !== 'cash') {
            $payment = $booking->payments->first();
            $responseData['payment_url'] = $this->paymentService->generateLink($payment);
            $responseData['expires_at']  = $booking->created_at->addMinutes(15)->toDateTimeString();
        }

        return response()->json([
            'status'  => 'success',
            'message' => $booking->payment_method === 'cash' ? 'تم الحجز بنجاح (دفع نقدي).' : 'برجاء إتمام الدفع.',
            'data'    => $responseData
        ], 201);

    } catch (Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
    }
}

/**
 * رابط الدفع لحجز موجود مسبقاً (إعادة المحاولة)
 * GET /api/v1/bookings/{id}/pay
 */
public function getPaymentLink(Booking $booking): JsonResponse
{
    // التأكد إن الحجز لسه pending ومن حق المستخدم الحالي
    if ($booking->user_id !== auth()->id() || $booking->status !== 'pending') {
        return response()->json(['message' => 'هذا الحجز غير متاح للدفع حالياً.'], 403);
    }

    // التأكد إن الـ 15 دقيقة مخلصوش
    if ($booking->created_at->addMinutes(15)->isPast()) {
        return response()->json(['message' => 'عفواً، انتهت صلاحية الحجز.'], 422);
    }

    $payment = $booking->payments()->where('status', 'pending')->first();
    
    if (!$payment) {
        return response()->json(['message' => 'لا توجد عملية دفع معلقة لهذا الحجز.'], 404);
    }

    return response()->json([
        'payment_url' => $this->paymentService->generateLink($payment),
        'expires_at'  => $booking->created_at->addMinutes(15)->toDateTimeString()
    ]);
}
}

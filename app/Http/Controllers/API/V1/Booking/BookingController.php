<?php

namespace App\Http\Controllers\API\V1\Booking;

use App\Actions\BookingTour;
use App\DTOs\TourBookingRequestDto;
use App\DTOs\UserDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Booking\CheckoutRequest;
use App\Models\Booking;
use App\Services\Booking\BookingService;
use App\Services\PaymentService; // الخدمة اللي بتكلم بوابة الدفع
use Illuminate\Http\JsonResponse;
use Exception;

class BookingController extends Controller
{
    protected $paymentService;

    public function __construct(public BookingService $bookingService,
    public BookingTour $bookingTour 
   // PaymentService $paymentService
    )
    {
       // $this->bookingService = $bookingService;
        //$this->paymentService = $paymentService;
    }

    /**
     * إنشاء حجز جديد
     */
  // داخل BookingController.php

public function checkout(CheckoutRequest $request): JsonResponse
{

    $data=$request->validated();


    /** @var UserDto $user
     * @param \App\Models\User $request->user()
     */
    $userDto=UserDto::fromEloquentModel($request->user());


    /** 
     * @var $TourBookingRequestDto $tourBooking
     *  @param CheckoutRequest $data
     */
    $tourBookingDto=TourBookingRequestDto::fromEloquentModel($data['tour_id'],$data['adults_count'],$data['children_count']);


    $bookingDto=$this->bookingTour->handle(
        tourBookingRequestDto: $tourBookingDto,
        userDto: $userDto);


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

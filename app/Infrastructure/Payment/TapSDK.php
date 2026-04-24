<?php

namespace App\Infrastructure\Payment;

use App\Enums\PaymentStatus;
use App\Exceptions\PaymentFailedException;
use App\Models\Booking;
use App\Models\Payment;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

final class TapSDK
{
    use ApiResponse;
    protected $apiClient;
    protected $url;
    protected $chrageUrl;
    public function __construct()
    {
        $this->apiClient = Http::withHeaders([
            "Authorization" => "Bearer " . config("payment.TAP_HEADER"),
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'lang_code' => app()->getLocale()
        ]);
        $this->url = config('payment.TAP_URL');
        $this->chrageUrl=$this->url."charges";
    }

    protected function initailPostRequest($url, $data)
    {
        return  $this->apiClient->post($url, $data);
    }
    protected function initailGetRequest($url)
    {
        return  $this->apiClient->get($url);
    }

    public function charge(Booking $booking):array
    {
        $response = $this->initailPostRequest($this->chrageUrl, $this->prepareChargeData($booking));
        if ($response->failed()) {
            throw new PaymentFailedException(
                __('exceptions.payment_initiation_failed')
            );
        }
        return $response->json();
    }
    
    public function prepareChargeData(Booking $booking): array
    {
        return [
            "amount" => $booking->total_price, // المبلغ من جدول الحجز
            "currency" => config("app.currency"), // أو العملة اللي شغال بيها
            "customer_initiated" => true,
            "threeDSecure" => true,
            "save_card" => false,
            "description" => "حجز رحلة: " . $booking->tour->name,
            "metadata" => [
                "booking_id" => $booking->id,
                "tour_id"    => $booking->tour_id,
                "adults"     => $booking->adults_count,
                "children"   => $booking->children_count,
            ],
            "reference" => [
                "transaction" => "TRNS_" . $booking->id . "_" . time(), // رقم فريد للعملية
                "order"       => "ORD_" . $booking->id // رقم الحجز عندك
            ],
            "customer" => [
                "first_name" => $booking->user->name ?? 'Guest', // يفضل يكون عندك اسم العميل
                "email"      => $booking->user->email ?? 'test@test.com',
                "phone" => [
                    "country_code" => "20", // كود مصر مثلاً
                    "number"       => $booking->user->phone ?? "0123456789"
                ]
            ],
            "source" => ["id" => "src_all"],
            "post" => ["url" =>route('v1.payemnt.webhook')],
            "redirect" => ["url" => route("v1.payment.callback")]
        ];
    }

    public function callback():JsonResponse
    {
        $tapId=request()->get("tap_id");
        $response = $this->initailGetRequest($this->chrageUrl."/{$tapId}");

        if ($response->json()['status'] !== "CAPTURED") {
            throw new PaymentFailedException(
                __('payments.callback_failed')
            );
        }
        return $this->success([],__('payments.callback_success'),200);

    }
    public function webhook(Request $request): void
    {
        $payload = $request->all();
        $tapId = $payload['id'] ?? null;
        $status = $payload['status'] ?? null;
    
        if (!$tapId) return;
    
        // استخدام DB Transaction لضمان إن مفيش حاجة تضرب في النص
        DB::transaction(function () use ($tapId, $status) {
            // lockForUpdate بيمنع أي عملية تانية تعدل على الـ Payment في نفس اللحظة
            $payment = Payment::where("transaction_id", $tapId)->lockForUpdate()->first();
    
            if (!$payment) return;
    
            // لو الدفع متسجل فعلاً إنه CAPTURED قبل كدة، ما تعملش حاجة (Idempotency)
            if ($payment->status === PaymentStatus::CAPTURED) return;
    
            if ($status === "CAPTURED") {
                $payment->completeBooking(); 
            } else if (in_array($status, ['FAILED', 'CANCELLED'])) {
                $payment->failedBooking();
            }
        });
    }

}

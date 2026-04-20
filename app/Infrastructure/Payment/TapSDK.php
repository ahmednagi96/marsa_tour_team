<?php

namespace App\Infrastructure\Payment;

use App\Exceptions\PaymentFailedException;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

final class TapSDK
{
    protected $apiClient;
    protected $url;
    public function __construct()
    {
        $this->apiClient = Http::withHeaders([
            "Authorization" => "Bearer " . env("TAP_HEADER"),
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'lang_code' => app()->getLocale()
        ]);
        $this->url = env('TAP_URL');
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
        $response = $this->initailPostRequest($this->url, $this->prepareChargeData($booking));
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
            "post" => ["url" => route('v1.payment.webhook')],
            "redirect" => ["url" => route("v1.payment.callback")]
        ];
    }

    public function callback()
    {
        $tapId=request()->get("tap_id");
        $response = $this->initailGetRequest("https://api.tap.company/v2/charges/{$tapId}");
        $paymentStatus = $response->json()['status'];

        if ($paymentStatus == 'CAPTURED') {
            return response()->json(['payment.success', ['message' => 'تم الحجز بنجاح يا بطل!']]);
        } else {
            return response()->json(['payment.failed', ['error' => 'للأسف العملية فشلت بسبب: ']]);
        }
    }
    public function webhook(Request $request) {
        if(request()->get("status") == "CAPTURED"){
            User::first()->update(['name'=>"success"]);      
        }else{
            User::first()->update(['name'=>"test"]);
        }
           
        return response()->json([], 200);
    }
}

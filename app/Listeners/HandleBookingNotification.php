<?php 
namespace App\Listeners;

use App\Events\BookingProcessed;
use Illuminate\Contracts\Queue\ShouldQueue; // لجعل العملية في الخلفية
use Illuminate\Support\Facades\Http;
use App\Mail\BookingStatusMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HandleBookingNotification implements ShouldQueue
{
    public function __construct(public string $baseTapUrl)
    {
        $this->baseTapUrl=config('payment.TAP_URL');
    }
    public function handle(BookingProcessed $event)
    {
        Log::info("event and lishtener worked successfully !");
        $booking = $event->booking;
        $invoiceUrl = null;

        if ($event->type === 'success') {
            // 1. إنشاء فاتورة عبر Tap API
            $response = Http::withToken(config("payment.TAP_HEADER"))
                ->post($this->baseTapUrl.'invoices', [
                    'customer' => [
                        'first_name' => $booking->user->name,
                        'email' => $booking->user->email,
                    ],
                    'currencies' => config("app.currency"), // أو عملتك
                    'order' => [
                        'id' => $booking->id,
                        'amount' => $booking->total_price,
                        'currency' => config('app.currency'),
                        'items' => [
                            ['name' => $booking->tour->name, 'amount' => $booking->total_price, 'quantity' => 1]
                        ]
                    ],
                 //   'post' => ['url' => route('webhooks.tap.invoices')], // اختياري
                 //   'redirect' => ['url' => route('bookings.show', $booking->id)]
                ]);

            if ($response->successful()) {
                $invoiceUrl = $response->json()['url']; // رابط فاتورة Tap
                // حفظ رابط الفاتورة في الحجز للرجوع إليه لاحقاً
                $booking->update(['invoice_url' => $invoiceUrl]);
            }
        }

        // 2. إرسال الإيميل (سواء نجاح أو فشل)
        Log::info($booking->user->email ."there is my email");

        Mail::to($booking->user->email)->send(new BookingStatusMail($booking, $event->type, $invoiceUrl));
    }
}
<?php

namespace App\Http\Resources\Payment;

use App\Http\Resources\Booking\BookingResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transaction_id' => $this->transaction_id, // الـ chg_xxxx
            
            // تفاصيل المبلغ بتنسيق شيك
            'amount' => [
                'decimal'  => (float) $this->amount,
                'formatted' => number_format($this->amount, 2) . ' ' . $this->currency,
                'currency'  => $this->currency,
            ],

            // حالة الدفع باستخدام الـ Enum
            'status' => [
                'key'   => $this->status->value,       // INITIATED
                'label' => $this->status->label(),     // جاري بدء الدفع (المترجمة)
               # 'color' => $this->getStatusColor(),    // لون الحالة للفرونت إند
            ],

            // تفاصيل بوابة الدفع (استخراج من الـ Payload)
            'gateway' => [
                'name' => $this->gateway,
                'method' => $this->payload['source']['payment_method'] ?? 'All', // Visa, Mada, etc.
                // بنجيب لينك الدفع لو الحالة لسه INITIATED
                'checkout_url' => $this->payload['transaction']['url'] ?? null,
            ],

            // بيانات العميل اللي اندفع بيها (من الـ Payload)
            'customer' => [
                'name'  => ($this->payload['customer']['first_name'] ?? '') . ' ' . ($this->payload['customer']['last_name'] ?? ''),
                'email' => $this->payload['customer']['email'] ?? null,
            ],
            'booking_details'=>new BookingResource($this->whenLoaded('booking')),
            'created_at_human' => $this->created_at->diffForHumans(),
            'is_completed'     => $this->status->value === 'CAPTURED',
        ];
    }

    /**
     * دالة مساعدة عشان الفرونت إند يلون الـ UI أوتوماتيك
     */
    private function getStatusColor(): string
    {
        return match($this->status->value) {
            'CAPTURED'  => 'success',
            'FAILED'    => 'danger',
            'CANCELLED' => 'warning',
            default     => 'info',
        };
    }
}
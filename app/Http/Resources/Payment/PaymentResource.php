<?php

namespace App\Http\Resources\Payment;

use App\Http\Resources\Booking\BookingResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * تحويل البيانات لتوافق معايير JSON:API
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'payments',
            'id'   => (string) $this->id,
            'attributes' => [
                'transaction_id' => $this->transaction_id,
                
                // تفاصيل المبلغ
                'amount' => [
                    'decimal'   => (float) $this->amount,
                    'formatted' => number_format($this->amount, 2) . ' ' . $this->currency,
                    'currency'  => $this->currency,
                ],

                // حالة الدفع
                'status' => [
                    'key'   => $this->status->value,
                    'label' => $this->status->label(),
                    'color' => $this->getStatusColor(),
                ],

                // بوابة الدفع
                'gateway' => [
                    'name'         => $this->gateway,
                    'method'       => $this->payload['source']['payment_method'] ?? 'All',
                    'checkout_url' => $this->payload['transaction']['url'] ?? null,
                ],

                // بيانات العميل
                'customer' => [
                    'name'  => trim(($this->payload['customer']['first_name'] ?? '') . ' ' . ($this->payload['customer']['last_name'] ?? '')),
                    'email' => $this->payload['customer']['email'] ?? null,
                ],

                'is_completed'     => $this->status->value === 'CAPTURED',
                'created_at_human' => $this->created_at->diffForHumans(),
                'created_at'       => $this->created_at->toIso8601String(),
            ],
            
            // العلاقات (Relationships) حسب معيار JSON:API
            'relationships' => [
                'booking' => [
                    'links' => [
                        //'related' => route('v1.bookings.show', ['booking' => $this->booking_id]),
                    ],
                    'data' => $this->whenLoaded('booking', function() {
                        return [
                            'type' => 'bookings',
                            'id'   => (string) $this->booking_id,
                        ];
                    }),
                ],
            ],

            'links' => [
               // 'self' => route('v1.payments.show', ['payment' => $this->id]),
            ],
        ];
    }

    /**
     * دالة مساعدة لتحديد الألوان
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
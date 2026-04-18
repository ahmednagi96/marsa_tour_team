<?php

namespace App\Http\Resources\Booking;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            
            // بيانات الرحلة (بافتراض وجود Relationships)
            'tour' => [
                'id' => $this->tour_id,
                'title' => $this->whenLoaded('tour', fn() => $this->tour->name),
                'date' => $this->travel_date->format('Y-m-d'), // تنسيق نظيف للتاريخ
            ],

            // تفاصيل العدد
            'guests' => [
                'adults' => (int) $this->adults_count,
                'children' => (int) $this->children_count,
                'total' => $this->adults_count + $this->children_count,
            ],

            // تفاصيل الأسعار (دائماً رجعها كـ Floats أو Strings منسقة)
            'pricing' => [
                'adult_price' => number_format($this->adult_price, 2),
                'child_price' => number_format($this->child_price, 2),
                'total_price' => number_format($this->total_price, 2),
                'currency' => 'EGP', // أو هاتها من كونفيج
            ],

            // الحالة (استخدام الـ Enum اللي عملناه)
            'status' => [
                'key' => $this->status->value,
                'label' => $this->status->label(), // الترجمة اللي عملناها في الـ Enum
            ],

            // التواريخ
            'booked_at' => $this->created_at->diffForHumans(),
            # 'expires_at' => $this->expires_at ? $this->expires_at->toDateTimeString() : null,

            // روابط سريعة (HATEOAS style) - بتخلي الـ Frontend يتبسط منك
          #  'links' => [
               # 'self' => route('bookings.show', $this->id),
               # 'payment' => $this->when($this->status->value === 'pending', route('payments.pay', $this->id)),
           # ],
        ];
    }
    
}

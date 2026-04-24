<?php

namespace App\Http\Resources\Travel;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourAvailabilityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    
    public function toArray(Request $request): array
    {
        return [
            // 1. الهوية الأساسية (Identity)
            'id'   => (string) $this->id,
            'type' => 'tour_schedules',

            'attributes' => [
                'date'    => Carbon::parse($this->date)->format('d-m-Y'), // تنسيق Y-m-d
                'pricing' => [
                    'adult_price' => (float) $this->adult_price,
                    'child_price' => (float) $this->final_child_price,
                ],
                'inventory' => [
                    'total_capacity'  => (int) $this->capacity,
                    'booked_seats'    => (int) $this->booked,
                    'available_seats' => (int) $this->available_seats,
                ],
                // حالة الحجز (Logic flags)
                'can_book' => (bool) ($this->is_active && $this->available_seats > 0),
               # 'badge'    => $this->badge ?? null,
            ],

            // 3. العلاقات (Relationships) - الربط مع الموارد التانية
            'relationships' => [
                'tour' => [
                    'links' => [
                        'related' => route('v1.tours.show', $this->tour_id),
                    ],
                    'data' => [
                        'type' => 'tours',
                        'id'   => (string) $this->tour_id,
                    ],
                ],
            ],

            // 4. الروابط (Links) - عشان الـ API يكون HATEOAS Compliant
          #  'links' => [
           #     'self' => route('v1.schedules.show', $this->id),
           # ],
        ];
    }
}

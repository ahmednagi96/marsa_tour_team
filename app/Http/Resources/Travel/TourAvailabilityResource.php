<?php

namespace App\Http\Resources\Travel;

use Illuminate\Http\Resources\Json\JsonResource;

class TourAvailabilityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'date'  => $this->date, // تنسيق Y-m-d
            
            'pricing' => [
                'adult_price' => (float) $this->adult_price,
                'child_price' => (float) $this->final_child_price,
               
            ],
            
            'inventory' => [
                'total_capacity'  => $this->capacity,
                'booked_seats'    => $this->booked,
                'available_seats' => $this->available_seats,
            ],
            // التسويق
           # 'badge' => $this->badge,
            // روابط سريعة (HATEOAS Style) - اختياري
            'can_book' => $this->is_active && $this->available_seats > 0,
        ];
    }
}

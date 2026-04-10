<?php

namespace App\Http\Resources\Travel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use function Livewire\str;

class TourListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => str($this->description)->limit(100), // الـ Casting في الموديل هيقوم بالواجب
            'location' => [
                'country' => $this->country,
                'city'    => $this->city,
                'street'  => $this->street,
            ],
            'pricing' => [
                'original_price'  => (float) $this->price,
                'current_price'   => (float) $this->current_price,
                'currency'        => config('app.currency'), // سينيور تريك: دايماً ابعت العملة
                'is_on_sale'      => $this->is_on_sale,
                // بنعرض تفاصيل الخصم "فقط" لو فيه خصم فعلي حالياً
                'discount' => $this->when($this->is_on_sale, [
                    'type'   => $this->discount_type,
                    'value'  => (float) $this->discount_value,
                    'saved'  => (float) ($this->saved_amount),
                    'ends_at' => $this->sale_end,
                ]),
            ],
            'media' => [
              "photos"=> $this->photos ? asset("images/tours/photos/".$this?->photos[0]) : null,
            ],

            'details' => [
                'duration'        => $this->duration . ' ' . __('days'),
            ],

            // التعامل مع العلاقات بشكل آمن (Avoid N+1 queries)
            'trip' => [
                'id'   => $this->trip_id,
                'name' => $this->whenLoaded('trip', fn() => $this->trip->name),
            ],
            'status' => [
                'is_active'    => $this->is_active,
                'is_favourite' => $this->is_favourite,
            ],

            'created_at_human' => $this->created_at?->diffForHumans(),
        ];
    }
}

<?php

namespace App\Http\Resources\Travel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $isDetailsPage = $request->routeIs("v1.tours.show");

        return [
            'id'   => (string) $this->id, // القياس يفرض أن يكون الـ ID نصياً
            'type' => 'tours',            // نوع المورد

            'attributes' => [
                'name'        => $this->name,
                'description' => $this->when(
                    $isDetailsPage,
                    $this->description,
                    str($this->description)->limit(100)
                ),
                'location' => [
                    'country' => $this->country,
                    'city'    => $this->city,
                    'street'  => $this->street,
                ],
                'pricing' => [
                    'original_price' => (float) $this->price,
                    'current_price'  => (float) $this->current_price,
                    'currency'       => config('app.currency'),
                    'is_on_sale'     => $this->is_on_sale,
                    'discount'       => $this->when($this->is_on_sale, [
                        'type'    => $this->discount_type,
                        'value'   => (float) $this->discount_value,
                        'saved'   => (float) $this->saved_amount,
                        'ends_at' => $this->sale_end,
                    ]),
                ],
                // دمج الحقول الإضافية بناءً على الصفحة
                'children' => $this->when($isDetailsPage, [
                    "allows_children"     => $this->allows_children,
                    "default_child_price" => $this->default_child_price,
                    "child_age_limit"     => __('messages.child_age_limit', ['age' => $this->child_age_limit])
                ]),
                'media' => [
                    "photos" => $this->when(
                        $isDetailsPage,
                        collect($this->photos)->map(fn($p) => asset("images/tours/photos/" . $p))->toArray() ?? [],
                        $this->photos ? asset("images/tours/photos/" . $this->photos[0]) : null
                    ),
                    'video' => $this->when($isDetailsPage && $this->video, asset("images/tours/videos/" . $this->video)),
                ],
                'details' => $this->when($isDetailsPage, [
                    'duration'        => $this->duration . ' ' . __('days'),
                    'services'        => $this->services ?? [],
                    'additional_info' => $this->additional_data ?? [],
                ]),
                'status' => [
                    'is_active'    => $this->is_active,
                    'is_favourite' => $this->is_favourite,
                ],
                'created_at' => $this->created_at?->toIso8601String(), // القياس يفضل صيغة ISO8601
            ],

            // العلاقات يتم فصلها هنا
            'relationships' => [
                'trip' => [
                    'links' => [
                        'related' => route('v1.trips.show', $this->trip_id),
                    ],
                    'data' => $this->whenLoaded('trip', function () {
                        return [
                            'type' => 'trips',
                            'id'   => (string) $this->trip_id,
                            'attributes' => [
                                'name' => $this->trip->name
                            ]
                        ];
                    }),
                ],
            ],

            'links' => [
                'self' => route('v1.tours.show', $this->id),
            ],
        ];
    }
}

<?php

namespace App\Http\Resources\Travel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'         => (string) $this->id, // في JSON:API الـ ID لازم يكون String
            'type'       => 'trips',            // تحديد نوع المورد (Resources Type)
            
            'attributes' => [
                'name'        => $this->name,
                'description' => $this->description,
                'trending'    => $this->trending?->label(),
                'photo'       => $this->photo_url,
            ],
    
            $this->mergeWhen($request->routeIs("v1.trips.show"),[   
            'relationships' => [
                'tours' => [
                    'data' => TourResource::collection($this->whenLoaded('tours')),
                ],
            ]   
        ]),
            'links' => [
                'self' => route('v1.trips.show', $this->id),
            ],
        ];
    }
}

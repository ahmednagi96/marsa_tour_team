<?php

namespace App\Http\Resources\Travel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'trending'    => $this->trending?->label(),
            'photo'       => $this->photo_url,
            'tours'       => TourResource::collection($this->whenLoaded('tours'))
        ];
    }
}

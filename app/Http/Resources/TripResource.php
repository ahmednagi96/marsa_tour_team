<?php

namespace App\Http\Resources;

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
            'photo'       => $this->photo_url,
            'tours'       => $this->mergeWhen(
                $request->routeIs('dashboard.*'),
                fn() => TourResource::collection($this->whenLoaded('tours'))
            ),
        ];
    }
}

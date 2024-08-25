<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'trip_id' => $this->trip_id,
            'trip_name' => $this->trip->name,
            'duration'=>$this->duration,
            'price'=>$this->price,
            'offer_price_percent'=>isset($this->offer)?$this->offer->offer_price_percent.'%':null,
            'offer_price_value'=>isset($this->offer)?$this->offer->offer_price_value:null,
            'photos' => $this->formatPhotos(),
            'name' => $this->name,
            'description' => $this->description,
            'services' => json_decode($this->services,true),
            'additional_data' => json_decode($this->additional_data,true),
            'country'=>$this->country,
            'city'=>$this->city,
            'street'=>$this->street,
            'is_favourite'=>$this->checkUserFavourite(),
        ];
    }

}

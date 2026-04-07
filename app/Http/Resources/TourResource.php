<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $data= [
            'id' => $this->id,
            'trip_id' => $this->trip_id,
            // 'trip_name' => $this->tours->name,
            'duration'=>$this->duration,
            // 'price'=>$this->price,
            'offer_price_percent'=>isset($this->offer)?$this->offer->offer_price_percent:null,
            'price'=>isset($this->offer)?$this->offer->offer_price_value:$this->price,
            'photos' => $this->formatPhotos(),
            "is_active"=>$this->is_active,
            "is_favourite"=>$this->is_favourite,
            'name' => $this->name,
            'description' => $this->description,
            'services' => json_decode($this->services,true),
            'additional_data' => json_decode($this->additional_data,true),
            'country'=>$this->country,
            'city'=>$this->city,
            'street'=>$this->street,
            'offers'=> new OfferResource($this->offer),
        ];
      if ($request->routeIs("dashboard.*")){
          return $data;
      }
      return  [
        'id' => $this->id,
        'trip_id' => $this->trip_id,
        // 'trip_name' => $this->tours->name,
        'duration'=>$this->duration,
        // 'price'=>$this->price,
        'offer_price_percent'=>isset($this->offer)?$this->offer->offer_price_percent:null,
        'price'=>isset($this->offer)?$this->offer->offer_price_value:$this->price,
        'photos' => $this->formatPhotos(),
        'video'=>$this->video,
        'name' => $this->name,
        'description' => $this->description,
        'services' => json_decode($this->services,true),
        'additional_data' => json_decode($this->additional_data,true),
        "is_active"=>$this->is_active,
        "is_favourite"=>$this->is_favourite,
        'country'=>$this->country,
        'city'=>$this->city,
        'street'=>$this->street,
        'offers'=> new OfferResource($this->offer),
      ];
    }

}

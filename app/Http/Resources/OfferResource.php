<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return  [
            'offer_id'=>$this->id,
            'tour_id'=>$this->tour_id,
        //    'tour_name'=>$this->tour->name,
            'photo' =>$this->getSinglePhoto(),
            'offer_price_percent'=>$this->offer_price_percent.'%',
            'offer_price_value'=>$this->offer_price_value,
            'price'=>$this->tour->price,
            'offer_name'=>$this->offer_name,
            'special_price_start'=>$this->special_price_start,
            'special_price_end'=>$this->special_price_end,
        ];



    }
}

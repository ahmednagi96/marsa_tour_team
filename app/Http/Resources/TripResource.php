<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'photo'=>is_null($this->photo)?null:BASEURLPHOTO.$this->photo,
            'name'=>$this->name,
            'description'=>$this->description
        ];
    }
}

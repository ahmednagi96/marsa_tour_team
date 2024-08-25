<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email'=>$this->email,
            'country'=>$this->country,
            'phone' => $this->phone,
            'country_code'=>$this->country_code,
            'photo' =>is_null($this->photo)?null: BASEURLPHOTO . $this->photo,
        ];
    }
}

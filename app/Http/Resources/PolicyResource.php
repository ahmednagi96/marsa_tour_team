<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            "id"=>$this->id,
            // "text"=> json_decode($this->des,true),
            "text"=> $this->text,
            "file"=>is_null($this->file)?null:BASEURLPHOTO.$this->file
        ]
        ;
    }
}

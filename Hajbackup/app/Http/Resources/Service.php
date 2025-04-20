<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Service extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            "id"=>$this->id,
            "service"=>$this->service,
            "duration"=>$this->duration,
            "price"=>$this->price,
            "description"=>$this->description,
            "active"=>$this->active
        ];
    }
}

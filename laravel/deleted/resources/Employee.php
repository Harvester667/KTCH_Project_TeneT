<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Employee extends JsonResource
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
            "user_id"=>$this->user_id,
            "phone"=>$this->phone,
            "gender"=>$this->gender,
            "qualifications"=>$this->qualifications,
            "description"=>$this->description
        ];
    }
}

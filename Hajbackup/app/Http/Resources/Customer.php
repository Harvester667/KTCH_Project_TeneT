<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Customer extends JsonResource
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
            "invoice_address"=>$this->invoice_address,
            "invoice_postcode"=>$this->invoice_postcode,
            "invoice_city"=>$this->invoice_city,
            "birth_date"=>$this->birth_date
        ];
    }
}

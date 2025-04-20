<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Booking extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
        return [
            "id"=>$this->id,

            "booking_time"=>$this->booking_time,

            "service_id"=>$this->service_id,

            "user_id_1"=>$this->user_id_1,
            "phone"=>$this->phone,
            "qualifications"=>$this->qualifications,
            "description"=>$this->description,

            "user_id_0"=>$this->user_id_0,
            "phone"=>$this->phone,
            "birth_date"=>$this->birth_date,
            "gender"=>$this->gender,
            "invoice_address"=>$this->invoice_address,
            "invoice_postcode"=>$this->invoice_postcode,
            "invoice_city"=>$this->invoice_city,

            "active"=>$this->active
        ];
    }
}

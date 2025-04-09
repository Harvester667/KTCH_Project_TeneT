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
        //return parent::toArray($request);
        return [
            "id"=>$this->id,
            // "booking"=>$this->booking,
            "booking" =>$this->duration,
            // "customer"=>$this->customer->customer,
            // "employee"=>$this->employee->employee,
            "service"=>$this->service->service,

            "employee_id"=>$this->employee_id,
            "service_id"=>$this->service_id,
            "phone"=>$this->phone,
            "gender"=>$this->gender,
            "invoice_address"=>$this->invoice_address,
            "invoice_postcode"=>$this->invoice_postcode,
            "invoice_city"=>$this->invoice_city,
            "birth_date"=>$this->birth_date,
            "qualifications"=>$this->qualifications,
            "description"=>$this->description
        ];
    }
}

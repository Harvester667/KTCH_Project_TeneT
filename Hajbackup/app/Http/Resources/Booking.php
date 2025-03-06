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
            "booking"=>$this->booking,
            "booking" =>$this->duration,
            "customer"=>$this->customer->customer,
            "employee"=>$this->employee->employee,
            "services"=>$this->service->service,
        ];
    }
}

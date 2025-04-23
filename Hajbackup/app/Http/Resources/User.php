<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,

            "name" => $this->name,
            "email" => $this->email,
            "password" => $this->password,

            "admin" => $this->admin,
            "role" => $this->role,

            "phone"=>$this->phone,
            "qualifications"=>$this->qualifications,
            "description"=>$this->description,
            
            "birth_date"=>$this->birth_date,
            "gender"=>$this->gender,
            "invoice_address"=>$this->invoice_address,
            "invoice_postcode"=>$this->invoice_postcode,
            "invoice_city"=>$this->invoice_city,

            "active" => $this->active
        ];
    }
}

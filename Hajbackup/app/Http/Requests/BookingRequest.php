<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "booking" => "required|unique:bookings,id",
            "customer" => "required",
            "employee" => "required",
            "service" => "required"
        ];
    }

    public function messages(){
        return [
            "booking.required" => "Foglalás azonosító elvárt.",
            "customer.required" => "Vendég név elvárt.",
            "employee.required" => "Dolgozó név elvárt.",
            "service.required" => "Szolgáltatás elvárt."
        ];
    }

    public function failedValidation( Validator $validator ){
        throw new HttpResponseException( response()->json([
            "success" => false,
            "message" => "Beviteli hiba.",
            "data" => $validator->errors()
        ]));
    }
}

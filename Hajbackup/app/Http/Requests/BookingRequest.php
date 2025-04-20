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

            "booking_time" => "sometimes|required|date_format:Y-m-d H:i:s",
            "user_id_1" => "sometimes|required|exists:users,id",
            "user_id_0" => "sometimes|required|exists:users,id",
            "service_id" => "sometimes|required|exists:services,id",
            "active"=>"sometimes|required|regex:/[0-1]/"
        ];
    }

    public function messages(){
        return [

            "booking_time.required" => "Foglalás időpontja elvárt.",
            "booking_time.date_format" => "Év-hónap-nap óra:perc:másodperc forma elvárt.",
            "user_id_0.required" => "Vendég azonosító elvárt.",
            "user_id_1.required" => "Dolgozó azonosító elvárt.",
            "service_id.required" => "Szolgáltatás azonosító elvárt.",
            "user_id_1.exists" => "Nincs ilyen felhasználó.",
            "user_id_0.exists" => "Nincs ilyen felhasználó.",
            "service_id.exists" => "Nincs ilyen szolgáltatás.",
            "active.required" => "Az aktiv mező kötelező.",
            "active.regex" => "Csak 0 vagy 1 elfogadott."
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

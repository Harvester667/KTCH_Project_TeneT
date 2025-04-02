<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerRequest extends FormRequest
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
            "phone" => "required",
            "gender" => "regex:/^(férfi|nő|szabadon választott)$/",
            "invoice_address" => "required",
            "invoice_postcode" => "required",
            "invoice_city" => "required",
            "birth_date" => "required|date"
        ];
    }

    public function messages(){
        return [
            "phone.required" => "A telefonszám megadása elvárt.",
            "gender.regex" => "A gender mezőnek a következő értékek egyikét kell tartalmaznia: férfi, nő, szabadon választott.",
            "invoice_address.required" => "A cím elvárt.",
            "invoice_postcode.required" => "Az irányítószám elvárt.",
            "invoice_city.required" => "A város elvárt.",
            "birth_date.required" => "A születési időpont elvárt.",
            "birth_date.date" => "Év-hónap-nap formátum elvárt."
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

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ServiceRequest extends FormRequest
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
            "service" => "sometimes|required",
            // "duration"=>"sometimes|required|regex:/[0-9]/",
            "price"=>"sometimes|required|regex:/[0-9]/",
            // "description"=>"sometimes|required|max:300",
            "active"=>"sometimes|required|regex:/[0-1]/"
        ];
    }

    public function messages(){
        return [
            "service.required" => "A szolgáltatás neve elvárt.",
            // "duration.required" => "Az időtartam elvárt.",
            // "duration.regex" => "Csak szám elfogadható",
            "price.required" => "Az ár elvárt.",
            "price.regex" => "Csak szám elfogadható.",
            // "description.required" => "Kitöltése elvárt.",
            // "description.max" => "300 karaktert meghaladó.",
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

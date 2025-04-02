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
            "service" => "required",
            "duration"=>"required|regex:/[0-9]/",
            "price"=>"required|regex:/[0-9]/",
            "description"=>"max:300"
        ];
    }

    public function messages(){
        return [
            "service.required" => "A szolgáltatás neve elvárt.",
            "duration.required" => "Az időtartam elvárt.",
            "duration.regex" => "Csak szám elfogadható",
            "price.required" => "Az ár elvárt.",
            "price.regex" => "Csak szám elfogadható.",
            "description.max" => "300 karaktert meghaladó."
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

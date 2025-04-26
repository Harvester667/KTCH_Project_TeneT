<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;



class SetProfileRequest extends FormRequest
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

        $user = auth("sanctum")->user();

        return [
            "phone" => [
                "sometimes",
                "string",
                "max:20",
                Rule::unique('users', 'phone')->ignore($user?->id),
            ],
            "gender" => "sometimes|in:férfi,nő,szabadon választott",
            "invoice_address" => "sometimes|string",
            "invoice_postcode" => "sometimes|integer",
            "invoice_city" => "sometimes|string",
            "birth_date" => "sometimes|date_format:Y-m-d",
            "qualifications" => "sometimes|string|max:300",
            "description" => "sometimes|string|max:300"
        ];
    }
    public function messages(){
        return [
            "phone.string" => "Szöveges bevitel elvárt.", // //"The phone field must be a string."
            "phone.max" => "Legfeljebb húsz karakter.",
            "phone.unique" => "Ez a telefonszám már foglalt.",
            "gender.in" => "Csak a : férfi, nő, szabadon választott kifejezések egyike megadható.", // //"The selected gender is invalid."
            "birth_date.date_format" => "A születési dátum formátuma legyen: év-hónap-nap (pl. 1990-12-31).", // //"The birth date field must match the format Y-m-d."
            "invoice_postcode.integer" => "Az irányítószám szám formátumú legyen.", // //"The invoice postcode field must be an integer."
            // "invoice_postcode.max" => "Legfeljebb tíz karakter.",
            "qualifications" => "A qualifikáció szöveges formátumú legyen.", // //"The qualifications field must be a string."
            "qualifications.max" => "Legfeljebb 300 karakter hosszú.",
            "description" => "A leírás szöveges formátumú legyen.",// //"The description field must be a string."
            "description.max" => "Legfeljebb 300 karakter hosszú.",
            "invoice_address.string" => "A számlázási cím szöveges formátumú legyen.", // //"The invoice address field must be a string."
            "invoice_city.string" => "A város szöveges formátumú legyen." // //"The invoice city field must be a string."
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

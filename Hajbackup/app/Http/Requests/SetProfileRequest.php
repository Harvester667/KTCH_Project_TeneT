<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

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
        return [
            "phone" => "nullable|string|max:20|unique:users,phone",
            "gender" => "nullable|in:férfi,nő,szabadon választott",
            "invoice_address" => "nullable|string",
            "invoice_postcode" => "nullable|string|max:10",
            "invoice_city" => "nullable|string",
            "birth_date" => "nullable|date_format:Y-m-d",
            "qualifications" => "nullable|string|max:300",
            "description" => "nullable|string|max:300"
        ];
    }
    public function messages(){
        return [
            "phone.max" => "Legfeljebb húsz karakter.",
            "phone.unique" => "Ez a telefonszám már foglalt.",
            "gender.in" => "Csak a : férfi, nő, szabadon választott kifejezések egyike megadható.",
            "birth_date.date_format" => "Év-hónap-nap forma elvárt.",
            "invoice_postcode.max" => "Legfeljebb tíz karakter.",
            "qualifications.max" => "Legfeljebb 300 karakter hosszú.",
            "description.max" => "Legfeljebb 300 karakter hosszú.",
            // "invoice_address" => "",
            // "invoice_city" => "",
            // "birth_date" => "",
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

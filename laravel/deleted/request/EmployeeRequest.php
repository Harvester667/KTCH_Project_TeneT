<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class EmployeeRequest extends FormRequest
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
            "qualifications" => "max:300",
            "description" => "max:300"
        ];
    }

    public function messages(){
        return [
            "phone.required" => "A telefonszám megadása elvárt.",
            "gender.regex" => "A gender mezőnek a következő értékek egyikét kell tartalmaznia: férfi, nő, szabadon választott.",
            "qualifications.max" => "Több mint 300 karakter.",
            "description" => "Több mint 300 karakter."
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

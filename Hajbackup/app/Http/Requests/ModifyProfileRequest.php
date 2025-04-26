<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class ModifyProfileRequest extends FormRequest
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
        $userId = $this->route('id') ?? null;
    
        return [
            "name" => "sometimes|required|min:3|max:51",
            "email" => [
                "sometimes",
                "required",
                "email",
                Rule::unique('users', 'email')->ignore($userId),
            ],
            "password" => [
                "sometimes",
                "required_with:password",
                "required",
                "min:8",
                "max:20",
                "regex:/[a-z]/",
                "regex:/[A-Z]/",
                "regex:/[0-9]/"
            ],
            "confirm_password" => "required_with:password|same:password",
            "phone" => [
                "sometimes",
                "string",
                "max:20",
                Rule::unique('users', 'phone')->ignore($userId),
            ],
            "gender" => "sometimes|in:férfi,nő,szabadon választott",
            "invoice_address" => "sometimes|string",
            "invoice_postcode" => "sometimes|numeric|digits_between:4,10",
            "invoice_city" => "sometimes|string",
            "birth_date" => "sometimes|date_format:Y-m-d",
            "qualifications" => "sometimes|string|max:300",
            "description" => "sometimes|string|max:300"
        ];
    }

    public function messages(){
        return [
            "name.required" => "A név mező nem lehet üres.",
            "name.min" => "A név túl rövid.",
            "name.max" => "A név túl hosszú.",
            // "name.unique" => "Ez a név már foglalt.", //|unique:users,name
            
            "email.required" => "Az email mező nem lehet üres.",
            "email.unique" => "Az email cím már foglalt.",
            "email.email" => "Email@formátum.elvárt",

            // 'password.required_with' => 'A jelszó megadása kötelező, ha megerősítés is történik.',
            'confirm_password.same' => 'A megerősítő jelszónak egyeznie kell a jelszóval.',
            'confirm_password.required_with' => 'A jelszó megerősítése kötelező.',
            "password.required_with" => "A jelszó mező nem lehet üres.",
            "password.required" => "A jelszó mező nem lehet üres.",
            "password.min" => "A jelszó túl rövid.",
            "password.max" => "A jelszó túl hosszú.",
            "password.regex" => "A jelszónak tartalmaznia kell kisbetűt, nagybetűt és számot is.",
            "confirm_password" => "Nem megegyező jelszó.",

            "phone.string" => "Szöveges bevitel elvárt.",
            "phone.max" => "Legfeljebb húsz karakter.",
            "phone.unique" => "Ez a telefonszám már foglalt.",
            "gender.in" => "Csak a : férfi, nő, szabadon választott kifejezések egyike megadható.",
            "birth_date.date_format" => "Év-hónap-nap forma elvárt.",
            "invoice_postcode.digits_between" => "Az irányítószám hossza négy és tíz karakter között elfogadott.", //"The invoice postcode field must be between 4 and 10 digits."
            "invoice_postcode.numeric" => "Az irányítószámot kérjük számmal megadni.",
            "qualifications.max" => "Legfeljebb 300 karakter hosszú.",
            "qualifications.string" => "A szöveges kitöltés elvárt.",
            "description.max" => "Legfeljebb 300 karakter hosszú.",
            "description.string" => "A szöveges kitöltés elvárt.",
            "invoice_address.string" => "A szöveges kitöltés elvárt",
            "invoice_city.string" => "A szöveges kitöltés elvárt",
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

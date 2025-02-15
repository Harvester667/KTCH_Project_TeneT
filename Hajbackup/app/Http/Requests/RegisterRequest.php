<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
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
    public function rules(): array{
        
        return [
            
            "name" => "required|min:3|max:51|unique:users, name",
            "email" => ["required",
                        "email",
                        "unique:users, email"],
            "password" => ["required",
                            "min:8",
                            "regex:/[a-z]/",
                            "regex:/[A-Z]/",
                            "regex:/[0-9]/"],
            "confirm_password" => "required|same:password"
        ];
    }

    public function messages() {

        return [

            "name.required" => "Kérjük a név mező kitöltését!",
            "name.min" => "Túl rövid név.",
            "name.max" => "Túl hosszú név.",
            "name.unique" => "Ez a név már foglalt.", //UPGRADE: Azonos nevek engedélyezáse megszorításokkal

            "email.required" => "Kérjük az email cím megadását!",
            "email.email" => "Kérjük a megfelelő email formátum megtartását!",
            "email.unique" => "Ez az email cím már foglalt.",

            "password.required" => "Kérjük a jelszó megadását!",
            "password.min" => "Legalább nyolc karakter hosszú legyen a jelszó!",
            "password.regex" => "Ez a jelszó nem felel meg a feltételeknek. (Elfogadott karakterek: a-z, A-Z, 0-9)",

            "confirm_password.required" => "Kérjük erősítse meg a beírt jelszót!",
            "confirm_password.same" => "Nem azonos a két beírt jelszó."
        ];
    }

    public function failedValidation( Validator $validator) {

        throw new HttpResponseException ( response() -> json([

            "success" => false,
            "message" => "Beviteli hiba",
            "data" => $validator -> errors()
        ]));
    }
}

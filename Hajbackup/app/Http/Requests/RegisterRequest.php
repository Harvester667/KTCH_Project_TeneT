<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
    public function rules(): array
    {
        return [
            "name" => "required|min:3|max:51|unique:users,name",
            "email" => "required|email|unique:users,email",
            "password" => [
                            "required",
                            "min:8",
                            "max:20",
                            "regex:/[a-z]/",
                            "regex:/[A-Z]/",
                            "regex:/[0-9]/"],
            "confirm_password" => "required|same:password"
        ];
    }

    public function messages(){
        return [
            "name.required" => "A név mező nem lehet üres.",
            "name.min" => "A név túl rövid.",
            "name.max" => "A név túl hosszú.",
            "name.unique" => "Ez a név már foglalt.",
            
            "email.required" => "Az email mező nem lehet üres.",
            "email.unique" => "Az email cím már foglalt.",

            "password.required" => "A jelszó mező nem lehet üres.",
            "password.min" => "A jelszó túl rövid.",
            "password.max" => "A jelszó túl hosszú.",
            "password.regex" => "A jelszónak tartalmaznia kell kisbetűt, nagybetűt és számot is.",
            "confirm_password" => "Nem megegyező jelszó."
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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
// use App\Http\Resources\User as UserResource;
use App\Http\Controllers\Api\ResponseController;
use App\Http\Requests\SetProfileRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SetPasswordRequest;

class ProfileController extends ResponseController
{
    public function getProfile(){

        $user = auth( "sanctum" )->user();
        $data = [
            "name" => $user->name,
            "email" => $user->email,
            "phone" => $user->phone,
            "gender" => $user->gender,
            "birth_date" => $user->birth_date,
            "invoice_address" => $user->invoice_address,
            "invoice_postcode" => $user->invoice_postcode,
            "invoice_city" => $user->invoice_city,
            "qualifications" => $user->qualifications,
            "description" => $user->description,
        ];
        return $this->sendResponse( $data, "Felhasználói profil betöltve.");
    }

    public function setProfile( SetProfileRequest $request ){

        $request->validated();

        $user = auth("sanctum")->user();
        $user->phone = $request->phone;
        $user->gender = $request->gender; // itt már legyen validált érték: férfi/nő/szabadon választott.
        $user->invoice_address = $request->invoice_address;
        $user->invoice_postcode = $request->invoice_postcode;
        $user->invoice_city = $request->invoice_city;
        $user->birth_date = $request->birth_date;
        $user->qualifications = $request->qualifications;
        $user->description = $request->description;

        $user->save();

        return $this->sendResponse( $user, "Profil adatok módosítva.");
    }

    public function setPassword( SetPasswordRequest $request ){

        $user = auth( "sanctum" )->user();
        $user->password = bcrypt( $request[ "password" ]);

        $user->update();

        return $this->sendResponse( $user, "Sikeres jelszócsere.");
    }
}

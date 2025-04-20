<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Api\ResponseController;

class ProfileController extends ResponseController
{
    public function getProfile(){

        $user = auth( "sanctum" )->user();
        $data = [
            "name" => $user->name,
            "email" => $user->email,
        ];
        return $this->sendResponse( $data, "Felhasználó profil.");
    }

    public function setProfile( Request $request ){

        $user = auth( "sanctum" )->user();
        $user->name = $request[ "name" ];
        $user->email = $request[ "email" ];

        $user->update();

        return $this->sendResponse( $user, "Profil módosítva.");
    }

    public function setPassword( Request $request ){

        $user = auth( "sanctum" )->user();
        $user->password = bcrypt( $request[ "password" ]);

        $user->update();

        return $this->sendResponse( $user, "Sikeres jelszócsere.");
    }

    public function deleteProfil(){

        $user = auth( "sanctum" )->user();
        $user->delete();

        return $this->sendResponse( $user, "Profil törölve." );
    }

}

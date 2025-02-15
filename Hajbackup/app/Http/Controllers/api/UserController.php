<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Request\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\api\ResponseController;


class UserController extends ResponseController {

    public function register ( RegisterRequest $request ) {

        $request -> validated();

        $user = User::create([
            "name" => $request[ "name" ],
            "email" => $request[ "email" ],
            "password" => bcrypt ( $request[ "password" ] )
        ]);

        return $user;   //megírni response-ba, user neve objektumban
    }

    public function login ( Request $request ) {    //validálás után saját Request-et kap mint a Register

        //$request -> validated();

        if ( Auth::attempt([ "name" => $request[ "name" ], "password" => $request[ "password" ] ])) {

            $authUser =Auth::user();
            $token = $authUser -> createToken( $authUser -> name."token" ) -> plainTextToken;
            $data = [
                "name" => $authUser -> name,
                "token" => $token
            ];

            return $this -> sendResponse( $data, "Sikeres bejelentkezés" );
        }
    }
    //? tesztelés!
    public function logout(Request $request) {
      // Az aktuális felhasználó lekérdezése
      $user = Auth::user();
  
      // A felhasználó által használt tokenek lekérdezése
      $token = $request -> user() -> currentAccessToken();
  
      // A token visszavonása
      $token -> delete();
  
      // Válaszadás
      return $this -> sendResponse( [], "Sikeres kijelentkezés" );
  }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserController extends ResponseController
{
    public function register( RegisterRequest $request ) {

        $request->validated();

        $adminLevel = User::count() === 0 ? 2 : 0;  // Ellenőrzi a felhasználók számát, Ha az első felhasználó, adminisztrátor szint 2, Ha már van felhasználó, adminisztrátor szint 0.

        // $isFirstUser = User::count() === 0;  // Ellenőrzi, hogy van e már user az adatbázisban.
        // $adminLevel = $isFirstUser ? 2 : 0;  // Ha az első felhasználó, adminisztrátor szint 2, Ha már van felhasználó, adminisztrátor szint 0.

        // $isFirstUser = User::count() === 0;
        //     if ($isFirstUser) {
        //         $adminLevel = 2; // Ha az első felhasználó, adminisztrátor szint 2
        //     } else {
        //         $adminLevel = 0; // Ha már van felhasználó, adminisztrátor szint 0
        //     }

        $user = User::create([

            "name" => $request["name"],
            "email" => $request["email"],
            "password" => bcrypt( $request["password"]),
            //"city_id" => ( new CityController )->getCityId( $request[ "city" ]),
            "admin" => $adminLevel

        ]);

        return $this->sendResponse( $user->name, "Sikeres regisztráció.");
    }

    public function login( LoginRequest $request ) {

        $request->validated();

        if( Auth::attempt([ "email" => $request["email"], "password" => $request["password"]])) {

            $actualTime = Carbon::now();
            $authUser = Auth::user();
            $bannedTime = ( new BannerController )->getBannedTime( $authUser->email );
            ( new BannerController )->reSetLoginCounter( $authUser->email );

            if( $bannedTime < $actualTime ) {

                ( new BannerController )->resetBannedTime( $authUser->email );
                $token = $authUser->createToken( $authUser->email."Token" )->plainTextToken;
                $data[ "user" ] = [ "email" => $authUser->email ];
                $data[ "time" ] = $bannedTime;
                $data[ "admin" ] = $authUser->admin;
                $data[ "token" ] = $token;

                return $this->sendResponse( $data, "Sikeres bejelentkezés.");

            }else {

                return $this->sendError( "Autentikációs hiba.", [ "Következő lehetőség: ", $bannedTime ], 401 );
            }
        }else {

            $loginCounter = ( new BannerController )->getLoginCounter( $request[ "email" ]);
            if( $loginCounter < 3 ) {

                ( new BannerController )->setLoginCounter( $request[ "email" ]);

                return $this->sendError( "Autentikációs hiba.", [ "Hibás felhasználónév vagy jelszó.", "Hibák száma: " .$loginCounter], 401 ); 


            }else {

                ( new BannerController )->setBannedTime( $request[ "email" ]);
                $bannedTime = ( new BannerController )->getBannedTime( $request[ "email" ]);
                ( new MailController )->sendMail( $request[ "email" ], $bannedTime );

                $errorMessage = [ "message" => "Következő lehetőség: ", "time" => $bannedTime ];

                return $this->sendError( "Autentikációs hiba.", [$errorMessage], 401 );
            }


        }
    }

    public function logout() {

        auth( "sanctum" )->user()->currentAccessToken()->delete();
        $name = auth( "sanctum" )->user()->name;

        return $this->sendResponse( $name, "Sikeres kijelentkezés.");
    }



    //     public function getTokens() {

    //     $tokens = DB::table( "personal_access_tokens" )->get();

    //     return $tokens;
    // }
}

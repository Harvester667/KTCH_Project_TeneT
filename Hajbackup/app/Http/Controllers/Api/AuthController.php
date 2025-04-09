<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Api\ResponseController;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class AuthController extends ResponseController {

    public function getUsers() {

        // if ( !Gate::allows( "super" )) {
        //     return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        // }
        // Először ellenőrizd, hogy a felhasználó super admin jogosultsággal rendelkezik
        if (Gate::allows("super")) {
        // Ha super admin, akkor engedélyezd a működést
        $users = User::all();
        return $this->sendResponse($users, "Betöltve.");
        }

        // Ha nem super admin, ellenőrizd, hogy admin jogosultsággal rendelkezik-e
        if (!Gate::allows("admin")) {
        return $this->sendError("Autentikációs hiba.", ["Nincs jogosultsága."], 401);
        }

        $users = User::all();
        return $this->sendResponse( $users, "Betöltve." );
    }

    public function setAdmin( Request $request ) {

        if ( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        }

        $user = User::find( $request[ "id" ]);
        
            // Ellenőrizzük, hogy a felhasználó létezik-e
        if (!$user) {
        return $this->sendError("Beviteli hiba.", ["A megadott felhasználó nem található."], 406);
    }

        $user->admin = 1;

        $user->update();

        return $this->sendResponse( $user->name, "Admin jog megadva." );
    }

    public function setEmployee( Request $request ) {

        if ( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        }

        $user = User::find( $request[ "id" ]);
        
            // Ellenőrizzük, hogy a felhasználó létezik-e
        if (!$user) {
        return $this->sendError("Beviteli hiba.", ["A megadott felhasználó nem található."], 406);
    }

        $user->role = 1;

        $user->update();

        return $this->sendResponse( $user->name, "Employee jog megadva." );
    }

    public function setCustomer( Request $request ) {

        if ( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        }

        $user = User::find( $request[ "id" ]);
        
            // Ellenőrizzük, hogy a felhasználó létezik-e
        if (!$user) {
        return $this->sendError("Beviteli hiba.", ["A megadott felhasználó nem található."], 406);
    }

        $user->role = 0;

        $user->update();

        return $this->sendResponse( $user->name, "Customer jog megadva." );
    }

    public function demotivate( Request $request ) {

        if ( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        }

        $user = User::find( $request[ "id" ]);
        // $user->admin = $request[ "admin" ]; //Hibás minta!!?
        $user->admin = 0;

        $user->update();

        return $this->sendResponse( $user->name, "Admin jog megvonva." );
    }

    public function updateUser( Request $request ) {

        if( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        }

        $user = User::find( $request[ "id" ]);
        $user->name = $request[ "name" ];
        $user->email = $request[ "email" ];
        //$user->city_id = ( new CityController )->getCityId( $request[ "city" ]);
        $user->update();

        return $this->sendResponse( $user, "Felhasználó frissítve." );
    }

    public function newUser( RegisterRequest $request ) {

        if( !Gate::allows( "admin" )) {

            return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        }
        $request->validated();

        $adminLevel = User::count() === 0 ? 2 : 0;
        $user = User::create([

            "name" => $request["name"],
            "email" => $request["email"],
            "password" => bcrypt( $request["password"]),
            //"city_id" => ( new CityController )->getCityId( $request[ "city" ]),
            "admin" => $adminLevel,
            "role" => 0

        ]);
        //$user->city_id = ( new CityController )->getCityId( $request[ "city" ]);
        $user->update();

        return $this->sendResponse( $user, "Felhasználó létrehozva." );
    }

    public function getTokens() {

        if ( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        }
        
        $tokens = DB::table( "personal_access_tokens" )->get();

        return $tokens;
    }

    public function avadaKedavra( Request $request ) {

        if( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        }

        $user =  User::find( $request[ "id" ]);
        $user->delete();

        return $this->sendResponse( $user->name, "Felhasználó törölve." );
    }
}
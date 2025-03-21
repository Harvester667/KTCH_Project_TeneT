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

        if ( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        }

        $users = User::all();
        return $this->sendResponse( $users, "Betöltve." );
    }

    public function setAdmin( Request $request ) {

        if ( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        }

        $user = User::find( $request[ "id" ]);
        // $user->admin = $request[ "admin" ]; //Hibás minta!!?
        $user->admin = 1;

        $user->update();

        return $this->sendResponse( $user->name, "Admin jog megadva." );
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
            "admin" => $adminLevel

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
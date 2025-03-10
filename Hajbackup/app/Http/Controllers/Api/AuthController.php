<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Api\ResponseController;
use Illuminate\Support\Facades\Gate;

class AuthController extends ResponseController {

    public function getUsers() {

        if ( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", "Nincs jogosultsága.", 401 );
        }

        $users = User::all();
        return $this->sendResponse( $users, "Betöltve." );
    }

    public function setAdmin( Request $request ) {

        if ( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", "Nincs jogosultsága.", 401 );
        }

        $user = User::find( $request[ "id" ]);
        $user->admin = $request[ "admin" ];

        $user->update();

        return $this->sendResponse( $user->name, "Admin jog megadva." );
    }

    public function updateUser( Request $request ) {

        if( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", "Nincs jogosultság.", 401 );
        }

        $user = User::find( $request[ "id" ]);
        $user->name = $request[ "name" ];
        $user->email = $request[ "email" ];
        //$user->city_id = ( new CityController )->getCityId( $request[ "city" ]);
        $user->update();

        return $this->sendResponse( $user, "Felhasználó frissítve." );
    }

    public function destroyUser( Request $request ) {

        if( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", "Nincs jogosultsága.", 401 );
        }

        $user =  User::find( $request[ "id" ]);
        $user->delete();

        return $this->sendResponse( $user->name, "Felhasználó törölve." );
    }
}
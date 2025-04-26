<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\User as UserResource;
use App\Http\Controllers\Api\ResponseController;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ModifyProfileRequest;
use Illuminate\Support\Facades\Gate;
// use Illuminate\Support\Facades\DB;

class AuthController extends ResponseController {

    public function getUsers() {

        // Gate::before(function () {

        //     $user = auth("sanctum")->user();
        //     if ($user->admin == 2) {
        //         return true;
        //     }
        // });

        // if (!Gate::allows("admin")) {
        //     return $this->sendError("Autentikációs hiba.", ["Nincs jogosultsága."], 401);
        // }

        $users = User::all();
        return $this->sendResponse( $users, "Felhasználók betöltve." );
    }

    public function setAdmin( Request $request ) {

        if ( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        }

        $user = User::find( $request[ "id" ]);
        
            // Ellenőrizzük, hogy a felhasználó létezik-e
        if (!$user) {
        return $this->sendError( "Beviteli hiba.", ["A megadott felhasználó nem található."], 406);
        }

        if ($user->isProtectedAdmin()) {
            return $this->sendError("Tiltott művelet.", ["Ez a felhasználó védett admin jogosultsággal rendelkezik."], 403);
        }
        // // if ($user->admin == 2) {
        // //     return $this->sendError("Tiltott művelet.", ["Ez a felhasználó védett admin jogosultsággal rendelkezik."], 403);
        // // }

        $user->admin = 1;

        $user->update();

        return $this->sendResponse( $user->name, "Admin jog megadva." );
    }

    public function demotivate( Request $request ) {

        if ( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        }

        $user = User::find( $request[ "id" ]);

        // Ellenőrizzük, hogy a felhasználó létezik-e
        if (!$user) {
        return $this->sendError( "Beviteli hiba.", ["A megadott felhasználó nem található." ], 406);
        }

        if ($user->isProtectedAdmin()) {
            return $this->sendError("Tiltott művelet.", ["Ez a felhasználó védett admin jogosultsággal rendelkezik."], 403);
        }
        // // if ($user->admin == 2) {
        // //     return $this->sendError("Tiltott művelet.", ["Ez a felhasználó védett admin jogosultsággal rendelkezik."], 403);
        // // }

        $user->admin = 0;

        $user->update();

        return $this->sendResponse( $user->name, "Admin jog megvonva." );
    }

    public function employee( Request $request ) {

        if ( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        }

        $user = User::find( $request[ "id" ]);
        
        // Ellenőrizzük, hogy a felhasználó létezik-e
        if (!$user) {
        return $this->sendError( "Beviteli hiba.", ["A megadott felhasználó nem található."], 406);
        }

        $user->role = 1;

        $user->update();

        return $this->sendResponse( $user->name, "Employee jog megadva." );
    }

    public function customer( Request $request ) {

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

        return $this->sendResponse( $user->name, "Customer jog beállítva." );
    }

    public function modifyProfile( ModifyProfileRequest $request ) {

        Gate::before(function () {

            $user = auth("sanctum")->user();
            if ($user->admin == 2) {
                return true;
            }
        });

        if (!Gate::allows("admin")) {
            return $this->sendError("Autentikációs hiba.", ["Nincs jogosultsága."], 401);
        }

        $request->validated();


        $user = User::find($request["id"]);

        $fields = [
            'name',
            'email',
            'phone',
            'gender',
            'invoice_address',
            'invoice_postcode',
            'invoice_city',
            'birth_date',
            'qualifications',
            'description',
        ];

        foreach ($fields as $field) {
            if ($request->filled($field)) {
                $user->$field = $request->$field;
            }
        }

        $passwordChanged = false;
        // Speciális kezelés a jelszónál (kódolás szükséges)
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
            $passwordChanged = true;
        }

        $user->save();

        // // $user = User::find( $request[ "id" ]);
        // // $user->name = $request->name;
        // // $user->email = $request->email;
        // // $user->password = bcrypt( $request[ "password" ]);
        // // $user->phone = $request->phone;
        // // $user->gender = $request->gender;
        // // $user->invoice_address = $request->invoice_address;
        // // $user->invoice_postcode = $request->invoice_postcode;
        // // $user->invoice_city = $request->invoice_city;
        // // $user->birth_date = $request->birth_date;
        // // $user->qualifications = $request->qualifications;
        // // $user->description = $request->description;

        // // $user->update();
        $message = $passwordChanged
        ? "Felhasználó adatai frissítve, jelszó módosítva."
        : "Felhasználó adatai frissítve.";
    
        return $this->sendResponse($user, $message);
        // return $this->sendResponse( $user, "Felhasználó frissítve." );
    }

    public function newUser( RegisterRequest $request ) {

        // Auth és jogosultsági ellenőrzés
        Gate::before(function () {
            $user = auth("sanctum")->user();
            if ($user->admin == 2) {
                return true;
            }
        });
    
        if (!Gate::allows("admin")) {
            return $this->sendError("Autentikációs hiba.", ["Nincs jogosultsága."], 401);
        }
        
        $request->validated();
        
        $user = User::create([
            "name" => $request[ "name" ],
            "email" => $request[ "email" ],
            "password" => bcrypt( $request[ "password" ]),
            "admin" => $request[ "admin" ],
            "role" => $request[ "role" ],
            "active" => $request[ "active" ]
        ]);

        return $this->sendResponse( $user, "Felhasználó létrehozva." );
    }

    public function toggleUserActive($id){
        if( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        }
        $user = User::find($id);

        if (!$user) {
        return $this->sendError("Nincs ilyen felhasználó.", [], 404);
        }

        if ($user->isProtectedAdmin()) {
            return $this->sendError("Tiltott művelet.", ["Ez a felhasználó védett admin jogosultsággal rendelkezik."], 403);
        }
        // // if ($user->admin == 2) {
        // //     return $this->sendError("Tiltott művelet.", ["Ez a felhasználó védett admin jogosultsággal rendelkezik."], 403);
        // // }

        // Aktív érték váltása
        $user->active = !$user->active;
        $user->save();

    return $this->sendResponse(new UserResource($user), "A felhasználó státusza frissítve.");
    }

    public function avadaKedavra( Request $request ) {

        if( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        }

        $user =  User::find( $request[ "id" ]);
        if(!$user){
            return $this->sendError( "Adathiba.", [ "Nincs ilyen felhasználó." ], 406);
        }

        if ($user->isProtectedAdmin()) {
            return $this->sendError("Tiltott művelet.", ["Ez a felhasználó védett admin jogosultsággal rendelkezik."], 403);
        }
        // // if ($user->admin == 2) {
        // //     return $this->sendError("Tiltott művelet.", ["Ez a felhasználó védett admin jogosultsággal rendelkezik."], 403);
        // // }

        $user->delete();

        return $this->sendResponse( $user->name, "Felhasználó törölve." );
    }
}
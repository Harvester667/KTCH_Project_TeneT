<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Http\Requests\BookingRequest;
use App\Http\Controllers\Api\ResponseController;
use App\Http\Resources\Booking as BookingResource;
use Illuminate\Support\Facades\Gate;

class BookingController extends ResponseController
{
    //C.R.U.D.
    public function getBookings(){

        $bookings = Booking::with( "employee_id", "customer_id", "service_id" )->get();

        return $this->sendResponse( BookingResource::collection( $bookings ), "Adatok betöltve.");
    }

    public function getOneBooking( Request $request ){

        $booking = Booking::where( "booking", $request[ "booking" ])->first();

        if( !$booking){
            return $this->sendError( "Adathiba.", [ "Nincs ilyen foglalás." ], 400 );
        }

        return $this->sendResponse( new BookingResource( $booking ), "Foglalás betöltve." );
    }

    public function addBooking(BookingRequest $request)
{
    // Auth és jogosultsági ellenőrzés
    Gate::before(function () {
        $user = auth("sanctum")->user();
        if ($user->admin == 0) {
            return true;
        }
    });

    if (!Gate::allows("admin")) {
        return $this->sendError("Autentikációs hiba.", ["Nincs jogosultsága."], 401);
    }

    // Kérés validálása
    $request->validated();

    $booking = new Booking();
    $booking->booking_time = $request["booking_time"]; // Időpont
    // $booking->save(); // Foglalás mentése

    // Felhasználók és szolgáltatások hozzárendelése
    if (!empty($request["employee_id"])) {
        $booking->users()->attach($request["user_id"]); // Felhasználók hozzárendelése
    }

    if (!empty($request["customer_id"])) {
        $booking->users()->attach($request["user_id"]); // Felhasználók hozzárendelése
    }

    if (!empty($request["service_ids"])) {
        $booking->services()->attach($request["service_ids"]); // Szolgáltatások hozzárendelése
    }

    return $this->sendResponse(new BookingResource($booking), "A foglalás rögzítve.");
}

    // public function addBooking( BookingRequest $request ){
        
    //     Gate::before( function(){
    //         $user = auth( "sanctum" )->user();
    //         if( $user->admin == 2 ){
    //             return true;
    //         }
    //     });
    //     if( !Gate::allows( "admin" )){
    //         return $this->sendError( "Autentikációs hiba.", [ "Nincs jogosultsága." ], 401 );
    //     }

    //     $request->validated();

    //     $booking = new Booking;
    //     // $booking -> booking = $request[ "booking" ];
    //     $booking-> datetime = $request[ "datetime" ];
    //     // $booking -> customer_id = ( new CustomerController )->getCustomerId( $request[ "customer" ]);
    //     // $booking -> employee_id = ( new EmployeeController )->getEmployeeId( $request[ "employee" ]);
    //     $booking -> service_id = ( new ServiceController )->getServiceId( $request[ "service" ]);

    //     $booking->save();

    //     return $this->sendResponse( new BookingResource( $booking ), "A foglalás rögzítve." );
    // }

    public function updateBooking(BookingRequest $request)
    {
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
    
        // Kérés validálása
        $request->validated();
    
        $booking = Booking::find($request["id"]);
        if (is_null($booking)) {
            return $this->sendError("Adathiba.", ["Nincs ilyen foglalás."], 400);
        } else {
            $booking->booking_time = $request["booking_time"];
            $booking->save(); // Foglalás mentése
    
            // Felhasználók és szolgáltatások szinkronizálása
            if (!empty($request["user_ids"])) {
                $booking->users()->sync($request["user_ids"]); // Felhasználók frissítése
            }
    
            if (!empty($request["service_ids"])) {
                $booking->services()->sync($request["service_ids"]); // Szolgáltatások frissítése
            }
    
            return $this->sendResponse(new BookingResource($booking), "A foglalás módosítva.");
        }
    }

    // public function updateBooking( BookingRequest $request ){

    //     Gate::before( function(){

    //         $user = auth( "sanctum" )->user();
    //         if( $user->admin == 2 ){

    //             return true;
    //         }
    //     });
    //     if ( !Gate::allows( "admin" )) {

    //         return $this->sendError( "Autentikációs hiba.", [ "Nincs jogosultsága." ], 401 );
    //     }

    //     $request->validated();

    //     $booking = Booking::find( $request[ "id" ]);
    //     if( is_null( $booking )){
    //         return $this->sendError( "Adathiba.", [ "Nincs ilyen foglalás." ], 400 );
    //     }else{
    //         $booking->booking = $request[ "booking" ];
    //         //$booking-> datetime = $request[ "" ];
    //         $booking -> customer_id = ( new CustomerController )->getCustomerId( $request[ "customer" ]);
    //         $booking -> employee_id = ( new EmployeeController )->getEmployeeId( $request[ "employee" ]);
    //         $booking -> service_id = ( new ServiceController )->getServiceId( $request[ "service" ]);

    //         $booking->update();

    //         //return $this->sendResponse( new BookingResource( $booking ), "A foglalás módosítva." ); SZIT.HU verzio?
    //         return $this->sendResponse( $booking, "A foglalás módosítva." );
    //     }
    // }

    public function deleteBooking( Request $request ){

        Gate::before( function(){

            $user = auth( "sanctum" )->user();
            if( $user->admin == 2 ){

                return true;
            }
        });
        if ( !Gate::allows( "admin" )) {

            return $this->sendError( "Autentikációs hiba.", [ "Nincs jogosultsága." ], 401 );
        }

        $booking = Booking::find( $request[ "id" ]);
        $booking->delete();

        return $this->sendResponse( $booking, "A foglalás törölve." );

        // if( is_null( $booking )){
        //     return $this->sendError( "Nincs ilyen foglalás." );
        // }else{
        //     $booking->delete();
        //     return $this->sendResponse( new BookingResource( $booking ), "A foglalás törölve." );
        // }
    }
}

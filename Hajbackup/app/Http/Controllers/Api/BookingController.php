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

        $bookings = Booking::all();

        return $this->sendResponse( BookingResource::collection( $bookings ), "Adatok betöltve.");
    }

    public function whoIsBooking(Request $request) {
        // $bookings = null;

        // Foglalások lekérdezése többféle szűrési lehetőséggel
        if (isset($request['user_id_0'])) {
            $bookings = Booking::where('user_id_0', $request['user_id_0'])
                              ->when(isset($request['booking_time']), function ($query) use ($request) {
                                  return $query->where('booking_time', $request['booking_time']);
                              })->get();
        } elseif (isset($request['user_id_1'])) {
            $bookings = Booking::where('user_id_1', $request['user_id_1'])
                              ->when(isset($request['booking_time']), function ($query) use ($request) {
                                  return $query->where('booking_time', $request['booking_time']);
                              })->get();
        } elseif (isset($request['booking_time'])) {
            // Ha csak a booking_time van megadva
            $bookings = Booking::where('booking_time', $request['booking_time'])->get();
        } elseif (isset($request['active'])) {
            // Ha csak az active van megadva
            $bookings = Booking::where('active', $request['active'])->get();
        } else {
            return $this->sendError("Adathiba.", ["Kérjük, adja meg a vendég vagy az alkalmazott azonosítóját, vagy a foglalás idejét."], 400);
        }
    
        if ($bookings->isEmpty()) {
            return $this->sendError("Adathiba.", ["Nincs ilyen foglalás."], 400);
        }
    
        return $this->sendResponse(BookingResource::collection($bookings), "Foglalások betöltve.");
    }
    
    public function addBooking(BookingRequest $request){
        //Felhasználó validálása
        $request->validated();
        $active = true;

        $booking = Booking::create([
            "booking_time" => $request["booking_time"],
            "user_id_1" => $request["user_id_1"],
            "user_id_0" => auth("sanctum")->user()->id,
            "service_id" => $request["service_id"],
            "active" => $active     
    ]);

        return $this->sendResponse( new BookingResource( $booking ), "Foglalás rögzítve." );
    }

    public function forceBooking(BookingRequest $request){
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

        $booking = new Booking();
        $booking->booking_time = $request[ "booking_time" ];
        $booking->user_id_1 = $request[ "user_id_1" ];
        $booking->user_id_0 = $request[ "user_id_0" ];
        $booking->service_id = $request[ "service_id" ];
        $booking->active = $request[ "active" ];
        $booking->save();

        return $this->sendResponse( new BookingResource( $booking ), "Új szolgáltatás rögzítve." );
    }

    public function updateBooking(BookingRequest $request, $id) {

        $request->validated();
    
        // Foglalás megkeresése
        $booking = Booking::find($id);
    
        if (!$booking) {
            return $this->sendError("Adathiba", ["Nem található foglalás ezzel az azonosítóval."], 404);
        }
    
        // // Jogosultság ellenőrzése (ha kell)
        // if ($booking->user_id_0 !== auth("sanctum")->user()->id) {
        //     return $this->sendError("Hozzáférés megtagadva", ["Nem módosíthatod ezt a foglalást."], 403);
        // }
    
        // Módosítás
        $booking->fill($request->only(['booking_time', 'user_id_0', 'service_id']))->update();
    
        return $this->sendResponse(new BookingResource($booking), "Foglalás módosítva.");
    }
    

    public function toggleBookingActive($id){
        if( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        }
        $booking = Booking::find($id);

        if (!$booking) {
        return $this->sendError("Nincs ilyen foglalás.", [], 404);
        }

        // Aktív érték váltása
        $booking->active = !$booking->active;
        $booking->save();

        return $this->sendResponse(new BookingResource($booking), "A foglalás státusza frissítve.");
    }

    public function delBooking( Request $request ){

        Gate::before(function () {

            $user = auth("sanctum")->user();
            if ($user->admin == 2) {
                return true;
            }
        });

        if (!Gate::allows("admin")) {
            return $this->sendError("Autentikációs hiba.", ["Nincs jogosultsága."], 401);
        }

        $booking = Booking::find( $request[ "id" ]);
        if(!$booking){ //Ellenőrizni kell, hogy van-e booking, mielőtt törölnénk
            return $this->sendError( "Adathiba.", [ "Nincs ilyen foglalás." ], 406); //Hibaüzenetet adunk vissza, ha nincs foglalás
        }else{
            $booking->delete();

            return $this->sendResponse( $booking, "A foglalás törölve." );
        }
    }
}

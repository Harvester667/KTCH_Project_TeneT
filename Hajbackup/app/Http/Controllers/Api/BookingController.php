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
    
    private function addBookingTimeFilter($query, $bookingTime)
    {
        if (strlen($bookingTime) === 10) {
            return $query->whereDate('booking_time', $bookingTime);
        } else {
            return $query->where('booking_time', $bookingTime);
        }
    }

        public function whoIsBooking(Request $request)
    {
        $query = Booking::query();

        // Definiáljuk, hogy mely mezők alapján akarunk szűrni
        $filters = [
            'user_id_0',
            'user_id_1',
            'service_id',
            'active',
        ];

        // Végigmegyünk az összes filteren és ha meg van adva, hozzáadjuk a query-hez
        foreach ($filters as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request[$field]);
            }
        }

        // Külön kezeljük a booking_time-ot
        if ($request->filled('booking_time')) {
            $this->addBookingTimeFilter($query, $request['booking_time']);
        }

        $bookings = $query->get();

        if ($bookings->isEmpty()) {
            return $this->sendError("Adathiba.", ["Nincs ilyen foglalás."], 400);
        }

        return $this->sendResponse(BookingResource::collection($bookings), "Foglalások betöltve.");
    }
    
    // public function whoIsBooking(Request $request)
    // {
    //     $query = Booking::query();
    
    //     // Dinamikusan hozzáadjuk a szűrőfeltételeket
    //     if (isset($request['user_id_0'])) {
    //         $query->where('user_id_0', $request['user_id_0']);
    //     }
    
    //     if (isset($request['user_id_1'])) {
    //         $query->where('user_id_1', $request['user_id_1']);
    //     }
    
    //     if (isset($request['service_id'])) {
    //         $query->where('service_id', $request['service_id']);
    //     }
    
    //     if (isset($request['active'])) {
    //         $query->where('active', $request['active']);
    //     }
    
    //     if (isset($request['booking_time'])) {
    //         $this->addBookingTimeFilter($query, $request['booking_time']);
    //     }
    
    //     $bookings = $query->get();
    
    //     if ($bookings->isEmpty()) {
    //         return $this->sendError("Adathiba.", ["Nincs ilyen foglalás."], 400);
    //     }
    
    //     return $this->sendResponse(BookingResource::collection($bookings), "A kiválasztott keresési értékeknek megfelelő foglalások betöltve.");
    // }

    
    public function addBooking(BookingRequest $request){

        //Ellenőrizzük, hogy van e foglalás az adott időpontra a fodrászhoz.
        $exists = Booking::where('user_id_1', $request->user_id_1)
        ->where('booking_time', $request->booking_time)
        ->exists();

        if ($exists) {
            return response()->json(['message' => 'Erre az időpontra már van foglalás!'], 409); // 409 Conflict
        }

        //Adatok validálása
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

    //Admin jogosultságú foglalás.
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

        //Ellenőrizzük, hogy van e foglalás az adott időpontra a fodrászhoz.
        $exists = Booking::where('user_id_1', $request->user_id_1)
        ->where('booking_time', $request->booking_time)
        ->exists();

        if ($exists) {
            return response()->json(['message' => 'Erre az időpontra már van foglalás!'], 409); // 409 Conflict
        }

        $request->validated();

        $booking = new Booking();
        $booking->booking_time = $request[ "booking_time" ];
        $booking->user_id_1 = $request[ "user_id_1" ];
        $booking->user_id_0 = $request[ "user_id_0" ];
        $booking->service_id = $request[ "service_id" ];
        $booking->active = $request[ "active" ];
        $booking->save();

        return $this->sendResponse( new BookingResource( $booking ), "Új foglalás rögzítve." );
    }

    public function updateBooking(BookingRequest $request, $id) {

        $request->validated();
    
        // Foglalás megkeresése
        $booking = Booking::find($id);
    
        if (!$booking) {
            return $this->sendError("Adathiba", ["Nem található foglalás ezzel az azonosítóval."], 404);
        }
    
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

        // Gate::before(function () {

        //     $user = auth("sanctum")->user();
        //     if ($user->admin == 2) {
        //         return true;
        //     }
        // });

        // if (!Gate::allows("admin")) {
        //     return $this->sendError("Autentikációs hiba.", ["Nincs jogosultsága."], 401);
        // }

        $booking = Booking::find( $request[ "id" ]);
        if(!$booking){ //Ellenőrizni kell, hogy van-e booking, mielőtt törölnénk
            return $this->sendError( "Adathiba.", [ "Nincs ilyen foglalás." ], 406); //Hibaüzenetet adunk vissza, ha nincs foglalás
        }else{
            $booking->delete();

            return $this->sendResponse( $booking, "A foglalás törölve." );
        }
    }
}

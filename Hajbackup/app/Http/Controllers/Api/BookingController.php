<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Http\Requests\BookingRequest;
use App\Http\Controllers\Api\ResponseController;
use App\Http\Resources\Booking as BookingResource;

class BookingController extends ResponseController
{
    //C.R.U.D.
    public function getBookings(){
        $bookings = Booking::with( "customer", "employee", "service" )->get();

        return $this->sendResponse( BookingResource::collection( $bookings ), "Adatok betöltve.");
    }

    public function getOneBooking( $request ){
        $booking = Booking::with( "customer", "employee", "service" )->where( "booking", $request[ "booking" ])->first();

        if( is_null($booking)){
            return $this->sendError( "Nincs ilyen foglalás.");
        }

        return $this->sendResponse( new BookingResource( $booking ), "Foglalás kiválasztva." );
    }

    public function addBooking( BookingRequest $request ){
        //$request->validated();

        $booking = new Booking;
        $booking -> booking = $request[ "booking" ];
        $booking -> customer_id = ( new CustomerController )->getCustomerId( $request[ "customer" ]);
        $booking -> employee_id = ( new EmployeeController )->getEmployeeId( $request[ "employee" ]);
        $booking -> service_id = ( new ServiceController )->getServiceId( $request[ "service" ]);

        $booking->save();

        return $this->sendResponse( new BookingResource( $booking ), "A foglalás kiírva." );
    }

    public function updateBooking( BookingRequest $request ){
        //$request->validated();

        $booking = Booking::find( $request[ "booking" ]);
        if( is_null( $booking )){
            return $this->sendError( "Nincs ilyen foglalás.");
        }else{
            $booking->booking = $request[ "booking" ];
            $booking -> customer_id = ( new CustomerController )->getCustomerId( $request[ "customer" ]);
            $booking -> employee_id = ( new EmployeeController )->getEmployeeId( $request[ "employee" ]);
            $booking -> service_id = ( new ServiceController )->getServiceId( $request[ "service" ]);

            $booking->update();

            return $this->sendResponse( new BookingResource( $booking ), "A foglalás frissítve." );
        }
    }

    public function deleteBooking( BookingRequest $request ){
        $booking = Booking::find( $request[ "Booking" ]);
        
        if( is_null( $booking )){
            return $this->sendError( "Nincs ilyen foglalás" );
        }else{
            $booking->delete();
            return $this->sendResponse( new BookingResource( $booking ), "A foglalás törölve." );
        }
    }
}

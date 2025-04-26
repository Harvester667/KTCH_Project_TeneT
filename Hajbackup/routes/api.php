<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Végpont csoport aminek eléréséhez minimum bejelentkezés szükséges.
Route::middleware( "auth:sanctum" )->group( function(){

    Route::post( "/addbooking", [ BookingController::class, "addBooking" ]);
    Route::patch( "/updatebooking/{id}", [ BookingController::class, "updateBooking" ]);
    Route::get( "/bookings", [ BookingController::class, "getBookings" ]);
    Route::get( "/whoisbooking", [ BookingController::class, "whoIsBooking" ]);
    Route::post( "/forcebooking", [ BookingController::class, "forceBooking" ]);
    Route::put( "/toggleBookingActive/{id}", [ BookingController::class, "toggleBookingActive" ]); 
    Route::delete( "/delbooking/{id}", [ BookingController::class, "delBooking" ]);
    
    Route::post( "/addservice", [ ServiceController::class, "addService" ]);
    Route::get( "/services", [ ServiceController::class, "getServices" ]);
    Route::patch("/updateservice/{id}", [ ServiceController::class, "updateService" ]);
    Route::put( "/toggleServiceActive/{id}", [ ServiceController::class, "toggleServiceActive" ]);
    Route::delete( "/delservice/{id}", [ ServiceController::class, "delService" ]);
    Route::get( "/userprofile", [ ProfileController::class, "getProfile" ]);
    Route::put( "/setprofile", [ ProfileController::class, "setProfile" ]);
    Route::put( "/changepassword", [ ProfileController::class, "setPassword" ]);
    // Route::post("/deleteprofile", [ ProfileController::class, "deleteProfile" ]);

    Route::post( "/logout", [ UserController::class, "logout" ]);

    Route::get( "/getusers", [ AuthController::class, "getUsers" ]);
    Route::put( "/admin/{id}", [ AuthController::class, "setAdmin" ]);
    Route::put( "/polymorph/{id}", [ AuthController::class, "demotivate" ]);
    Route::put( "/modifyprofile/{id}", [ AuthController::class, "modifyProfile" ]);
    Route::put( "/toggleUserActive/{id}", [ AuthController::class, "toggleUserActive" ]);
    Route::delete( "/voldemort/{id}", [ AuthController::class, "avadaKedavra" ]);
    Route::post( "/newuser", [ AuthController::class, "newUser" ]);
    Route::put( "/employee/{id}", [ AuthController::class, "employee" ]);
    Route::put( "/customer/{id}", [ AuthController::class, "customer" ]);

    Route::get( "/booking", [ BookingController::class, "getOneBooking" ]);

    //Route::get( "/seed", [ BookingController::class, "runSeeder" ]);
});

Route::post( "/register", [ UserController::class, "register" ]);
Route::post( "/login", [ UserController::class, "login" ]);

// Route::get("/services", [ServiceController::class, "getServices"]);
// Route::get("/oneservice/{id}", [ServiceController::class, "getOneService"]);
// Route::post("/addservice", [ServiceController::class, "addService"]);

// Route::delete("/deleteservice/{id}", [ServiceController::class, "deleteService"]);


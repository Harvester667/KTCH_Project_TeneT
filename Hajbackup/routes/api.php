<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware( "auth:sanctum" )->group( function(){

    Route::post( "/logout", [ UserController::class, "logout" ]);

    Route::post( "/addbooking", [ BookingController::class, "addBooking" ]);
    Route::put( "/updatebooking", [ BookingController::class, "updateBooking" ]);
    Route::delete( "/deletebooking", [ BookingController::class, "deleteBooking" ]);
    
    Route::post( "/addemployee", [ EmployeeController::class, "addEmployee" ]);
    Route::put( "/updateemployee", [ EmployeeController::class, "updateEmployee" ]);
    Route::delete( "/destemployee", [ EmployeeController::class, "deleteEmployee" ]);
    

    Route::post( "/addcustomer", [ CustomerController::class, "addCustomer" ]);
    Route::put( "/updatecustomer", [ CustomerController::class, "updateCustomer" ]);
    Route::delete( "/destcustomer", [ CustomerController::class, "deleteCustomer" ]);
    

    //Route::post( "/addcity", [ CityController::class, "addCity" ]);
    // Route::get( "/userprofile", [ ProfileController::class, "getProfile" ]);
    // Route::put( "/updateprofile", [ ProfileController::class, "setProfile" ]);
    // Route::put( "/updatepassword", [ ProfileController::class, "setPassword" ]);
    // Route::post("/deleteprofile", [ ProfileController::class, "deleteProfile" ]);

    Route::get( "/users", [ AuthController::class, "getUsers" ]);
    Route::put( "/admin", [ AuthController::class, "setAdmin" ]);
    Route::put( "updateuser", [ AuthController::class, "updateUser" ]);
    Route::delete( "/deleteuser", [ AuthController::class, "deleteUser" ]);
});

Route::post( "/register", [ UserController::class, "register" ]);
Route::post( "/login", [ UserController::class, "login" ]);

Route::get( "/bookings", [ BookingController::class, "getBookings" ]);
Route::get( "/booking", [ BookingController::class, "getOneBooking" ]);
//Route::get( "/amount", [ BookingController::class, "getAmount" ]);

Route::get( "/employees", [ EmployeeController::class, "getEmployees" ]);
Route::get( "/oneemployee", [ EmployeeController::class, "getEmployee" ]);

Route::get( "/customers", [ CustomerController::class, "getCustomers" ]);
Route::get( "/onecustomer", [ CustomerController::class, "getCustomer" ]);

Route::get( "/tokens", [ UserController::class, "getTokens" ]);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
// Route::middleware( "auth:sanctum" )->group( function(){
//     Route::post( "/logout", [ UserController::class, "logout" ]);
// });

// Route::get("/bookings", [BookingController::class, "getBookings"]);
// Route::get("/onebooking/{id}", [BookingController::class, "getOneBooking"]);
// Route::post("/addbooking", [BookingController::class, "addBooking"]);
// Route::put("/updatebooking/{id}", [BookingController::class, "updateBooking"]);
// Route::delete("/deletebooking/{id}", [BookingController::class, "deleteBooking"]);
// Route::get("/getbookingid", [BookingController::class, "getBookingId"]);

// Route::get("/customers", [CustomerController::class, "getCustomers"]);
// Route::get("/onecustomer/{id}", [CustomerController::class, "getOneCustomer"]);
// Route::post("/addcustomer", [CustomerController::class, "addCustomer"]);
// Route::put("/updatecustomer/{id}", [CustomerController::class, "updateCustomer"]);
// Route::delete("/deletecustomer/{id}", [CustomerController::class, "deleteCustomer"]);
// Route::get("/getcustomerid", [CustomerController::class, "getCustomerId"]);

// Route::get("/employees", [EmployeeController::class, "getEmployees"]);
// Route::get("/oneemployee/{id}", [EmployeeController::class, "getOneEmployee"]);
// Route::post("/addemployee", [EmployeeController::class, "addEmployee"]);
// Route::put("/updateemployee/{id}", [EmployeeController::class, "updateEmployee"]);
// Route::delete("/deleteemployee/{id}", [EmployeeController::class, "deleteEmployee"]);
// Route::get("/getemployeeid", [EmployeeController::class, "getEmployeeId"]);

// Route::get("/services", [ServiceController::class, "getServices"]);
// Route::get("/oneservice/{id}", [ServiceController::class, "getOneService"]);
// Route::post("/addservice", [ServiceController::class, "addService"]);
// Route::put("/updateservice/{id}", [ServiceController::class, "updateService"]);
// Route::delete("/deleteservice/{id}", [ServiceController::class, "deleteService"]);
// Route::get("/getserviceid", [ServiceController::class, "getServiceId"]);

// Route::post("/register", [UserController::class, "register"]);
// Route::get("/getusers", [AuthController::class, "getUsers"]);
// Route::post("/login", [UserController::class, "login"]);
// Route::post("/logout", [UserController::class, "logout"]);
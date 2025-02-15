<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post( "/register", [ UserController::class, "register" ]);
Route::post( "/login", [ UserController::class, "login" ]);


// Route::get( "/drinks", [ DrinkController::class, "getDrinks" ]);
// Route::get( "/drink", [ DrinkController::class, "getDrink" ]);
// Route::post( "/newdrink", [ DrinkController::class, "newDrink" ]);
// Route::put( "/updatedrink", [ DrinkController::class, "updateDrink" ]);
// Route::delete( "/deletedrink", [ DrinkController::class, "destroyDrink" ]);

// Route::get( "/types", [ TypeController::class, "getTypes" ]);

// Route::get( "/packages", [ PackageController::class, "getPackages" ]);
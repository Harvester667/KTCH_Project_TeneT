<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class BannerController extends Controller {

    public function getLoginCounter( $name ) {

        $user = User::where( "name", $name )->first();
        $counter = $user->login_counter;

        return $counter;
    }

    public function resetLoginCounter( $name ) {

        $user = User::where( "name", $name )->first();
        $user->login_counter = 0;

        $user->update();
    }

    public function setLoginCounter( $name ) {

        User::where( "name", $name )->increment( "login_counter" );
    }

    public function getBannedTime( $name ) {

         $user = User::where( "name", $name )->first();
         $bannedTime = $user->banning_time;

         return $bannedTime;
    }

    public function setBannedTime( $name ) {

        $user = User::where( "name", $name )->first();
        $user->banning_time = Carbon::now()->addSeconds( 60 );

        $user->update();
    }

    public function resetBannedTime( $name ) {

        $user = User::where( "name", $name )->first();
        $user->banning_time = null;

        $user->update();
    }
}
<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class BannerController extends Controller {

    public function getLoginCounter( $email ) {

        $user = User::where( "email", $email )->first();
        $counter = $user->login_counter;

        return $counter;
    }

    public function resetLoginCounter( $email ) {

        $user = User::where( "email", $email )->first();
        $user->login_counter = 0;

        $user->update();
    }

    public function setLoginCounter( $email ) {

        User::where( "email", $email )->increment( "login_counter" );
    }

    public function getBannedTime( $email ) {

         $user = User::where( "email", $email )->first();
         $bannedTime = $user->banning_time;

         return $bannedTime;
    }

    public function setBannedTime( $email ) {

        $user = User::where( "email", $email )->first();
        $user->banning_time = Carbon::now()->addSeconds( 60 );

        $user->update();
    }

    public function resetBannedTime( $email ) {

        $user = User::where( "email", $email )->first();
        $user->banning_time = null;

        $user->update();
    }
}
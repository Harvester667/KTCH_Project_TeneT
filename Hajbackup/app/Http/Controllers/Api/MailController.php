<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\BannerMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class MailController extends Controller {

    public function sendMail( $userName, $bannedTime ) { //tesztelni a másik formát $user
        // public function sendMail( User $user, $bannedTime ) {

        $content = [
            "title" => "Felhasználó blokkolása",
            "user" => $userName,
            // "user" => $user->name,
            "time" => $bannedTime
        ];

        Mail::to( "harvester667@gmail.com" )->send( new BannerMail( $content ));
        //Mail::to( $user->email )->send( new BannerMail( $content )); tesztelésre vár
    }
}
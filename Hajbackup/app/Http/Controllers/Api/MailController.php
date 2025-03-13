<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\BannerMail;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller {

    public function sendMail( $userName, $bannedTime ) {

        $content = [
            "title" => "Felhasználó blokkolása",
            "user" => $userName,
            "time" => $bannedTime
        ];

        Mail::to( "laravel11fejlesztes@gmail.com" )->send( new BannerMail( $content ));
    }
}
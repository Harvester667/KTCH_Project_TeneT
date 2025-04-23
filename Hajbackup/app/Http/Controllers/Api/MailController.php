<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\BannerMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class MailController extends Controller {

    public function sendMail($userEmail, $userName, $bannedTime) {
    // public function sendMail( $userName, $bannedTime ) { 

        $content = [
            "title" => "Felhasználó blokkolása",
            "user" => $userName,
            // "user" => $user->name,
            "time" => $bannedTime
        ];

        Mail::to( "harvester667@gmail.com" )->send( new BannerMail( $content ));
        // Felhasználó saját e-mail címére
        Mail::to($userEmail)->send(new BannerMail($content));
    }
}
<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function sendResponse ( $data, $message ) {

        $response = [

            "success" => true,
            "data" => $data,
            "message" => $message
        ];

        return response() -> json ( $response, 200 );
    }

    public function sendError ( $error, $errorMessages = [], $code = 404 ) {

        $response = [

            "success" => false,
            "error" => $error
        ];

        if ( !empty( $errorMessages )) {

            $response[ "errorMessage" ] = $errorMessages;
        }

        return response() -> json( $response, $code );
    }
}

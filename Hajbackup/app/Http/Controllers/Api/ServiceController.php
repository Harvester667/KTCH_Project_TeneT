<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Http\Requests\ServiceRequest;
use App\Http\Controllers\Api\ResponseController;
use App\Http\Resources\Service as ServiceResource;

class ServiceController extends ResponseController
{
    //C.R.u.D.
    public function getServices(){
        $services = Service::all();

        return $this->sendResponse( ServiceResource::collection( $services ), "Szolgáltatások listázva.");
    }

    public function getService( Request $request ){
        $service = Service::where( "service", $request[ "service "])->first();
        if( is_null( $service )){
            return $this->sendError( "Beviteli hiba.", [ "Nincs ilyen szolgálatás." ], 406);
        }else{
            return $this->sendResponse( $service, "Szolgáltatás listázva." );
        }
    }

    public function addService( ServiceRequest $request ){
        $request->validated();

        $service = new Service();
        $service->service = $request[ "service" ];
        $service->duration = $request[ "duration" ];
        $service->price = $request[ "price" ];
        $service->description = $request[ "description" ];
        $service->save();

        return $this->sendResponse( new ServiceResource( $service ), "Új szolgáltatás rögzítve." );
    }

    public function updateService( ServiceRequest $request ){
        $request->validated();
        
        $service = Service::find( $request[ "id" ]);
        if( is_null( $service )){
            $this->sendError( "Beviteli hiba.", [ "Nincs ilyen szolgáltatás." ], 406 );
        }else{
            $service->service = $request[ "service" ];
            $service->update();

            return $this->sendResponse( new Service( $service ), "Szolgáltatás adatai módosítva." );
        }
    }

    public function deleteService( Request $request ){
        $service = Service::find( $request[ "id "]);
        if( is_null( $service )){
            return $this->sendError( "Beviteli hiba.", [ "Szolgáltatás nem létezik."], 406 );
        }else{
            $service->delete();

            return $this->sendResponse( new ServiceResource( $service ), "Szolgáltatás törölve." );
        }
    }

    public function getServiceId( $serviceName ){
        $service = Service::where( "service", $serviceName )->first();
        $id = $service->id;

        return $id;
    }

}

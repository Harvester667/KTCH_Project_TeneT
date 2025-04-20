<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Http\Requests\ServiceRequest;
use App\Http\Controllers\Api\ResponseController;
use App\Http\Resources\Service as ServiceResource;
use Illuminate\Support\Facades\Gate;

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

        // Auth és jogosultsági ellenőrzés
        Gate::before(function () {
            $user = auth("sanctum")->user();
            if ($user->admin == 2) {
                return true;
            }
        });
    
        if (!Gate::allows("admin")) {
            return $this->sendError("Autentikációs hiba.", ["Nincs jogosultsága."], 401);
        }

        $request->validated();

        $service = new Service();
        $service->service = $request[ "service" ];
        $service->duration = $request[ "duration" ];
        $service->price = $request[ "price" ];
        $service->description = $request[ "description" ];
        $service->active = $request[ "active" ];
        $service->save();

        return $this->sendResponse( new ServiceResource( $service ), "Új szolgáltatás rögzítve." );
    }

    public function updateService(ServiceRequest $request, $id) {
        // Auth és jogosultsági ellenőrzés
        Gate::before(function () {
            $user = auth("sanctum")->user();
            if ($user->admin == 2) {
                return true;
            }
        });
    
        if (!Gate::allows("admin")) {
            return $this->sendError("Autentikációs hiba.", ["Nincs jogosultsága."], 401);
        }

        $request->validated();
    
        // Foglalás megkeresése
        $service = Service::find($id);
    
        if (!$service) {
            return $this->sendError("Adathiba", ["Nincs ilyen szolgáltatás."], 404);
        }
    
        // // Jogosultság ellenőrzése (ha kell)
        // if ($service->user_id_0 !== auth("sanctum")->user()->id) {
        //     return $this->sendError("Hozzáférés megtagadva", ["Nem módosíthatod ezt a foglalást."], 403);
        // }
    
        // Módosítás
        $service->fill($request->only(['service', 'duration', 'price', 'description']))->update();

        return $this->sendResponse(new ServiceResource($service), "Szolgáltatás módosítva.");
    }
    // public function updateService( ServiceRequest $request ){
    //     // Auth és jogosultsági ellenőrzés
    //     Gate::before(function () {
    //         $user = auth("sanctum")->user();
    //         if ($user->admin == 2) {
    //             return true;
    //         }
    //     });
    
    //     if (!Gate::allows("admin")) {
    //         return $this->sendError("Autentikációs hiba.", ["Nincs jogosultsága."], 401);
    //     }
    //     $service = Service::find( $request[ "id" ]);
    //     if(!$service){
    //         return $this->sendError( "Beviteli hiba.", [ "Nincs ilyen szolgáltatás." ], 406 );
    //     }
    //     $request->validated();
        

    //         $service->service = $request[ "service" ];
    //         $service->duration = $request[ "duration" ];
    //         $service->price = $request[ "price" ];
    //         $service->description = $request[ "description" ];
    //         $service->active = $request[ "active" ];
    //         $service->update();

    //         return $this->sendResponse( $service, "Szolgáltatás adatai módosítva." );
        
    // }

    public function toggleServiceActive($id){
        if( !Gate::allows( "super" )) {

            return $this->sendError( "Autentikációs hiba.", ["Nincs jogosultsága."], 401 );
        }
        $service = Service::find($id);

        if (!$service) {
        return $this->sendError("Nincs ilyen szolgáltatás.", [], 404);
        }

        // Aktív érték váltása
        $service->active = !$service->active;
        $service->save();

        return $this->sendResponse(new ServiceResource($service), "A szolgáltatás státusza frissítve.");
    }

    public function delService( Request $request ){
        // Auth és jogosultsági ellenőrzés
        if (!Gate::allows("super")) {
            return $this->sendError("Autentikációs hiba.", ["Nincs jogosultsága."], 401);
        }
        
        $service = Service::find( $request[ "id" ]);
        if(!$service){
            return $this->sendError( "Beviteli hiba.", [ "Nincs ilyen szolgáltatás." ], 406 );
        }else{
            $service->delete();

            return $this->sendResponse( $service, "Szolgáltatás törölve." );
        }
    }
}

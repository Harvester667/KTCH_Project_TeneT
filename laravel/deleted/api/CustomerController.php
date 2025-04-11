<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Controllers\Api\ResponseController as ResponseController;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\Customer as CustomerResource;

class CustomerController extends ResponseController
{
    //C.R.U.D.
    public function getCustomers(){
        $customers = Customer::all();

        return $this->sendResponse( CustomerResource::collection( $customers ), "Vendégek listázva." );

    }

    public function getCustomer( Request $request){
        $customer = Customer::where( "customer", $request[ "customer" ])->first();
        if( is_null( $customer )){
             return $this->sendError( "Hibás adat.", [ "Nincs ilyen vendég."], 406 );
        }else{
            return $this->sendResponse( $customer, "Vendég listázva." );
        }
    }

    public function addCustomer( CustomerRequest $request ){
        $request->validated();

        $customer = new Customer();
        $customer->customer = $request[ "customer" ];
        $customer->save();

        return $this->sendResponse( new CustomerResource( $customer ), "Új vendég rögzítve.");
    }

    public function updateCustomer( CustomerRequest $request ){
        $request->validated();

        $customer = Customer::find( $request[ "id" ]);
        if( is_null( $customer )){

            return $this->sendError( "Adathiba.", [ "Nincs ilyen vendég." ], 406 );
        }else{
            $customer->customer = $request[ "customer" ];
            $customer->update();

            return $this->sendResponse( new CustomerResource( $customer ), "Vendég adatai módosítva.");
        }
    }

    public function deleteCustomer( Request $request ){
        $customer = Customer::find( $request[ "id" ]);
        if( is_null( $customer )){

            return $this->sendError( "Adathiba.", [ "Nincs ilyen vendég." ], 406 );
        }else{
            $customer->delete();
            return $this->sendResponse( new CustomerResource( $customer ), "Vendég törölve." );
        }
    }

    public function getCustomerId( $customerName ){
        $customer = Customer::where( "customer", $customerName )->first();
        $id = $customer->id;

        return $id;
    }
}

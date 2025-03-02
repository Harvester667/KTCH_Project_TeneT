<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Controllers\Api\ResponseController as ResponseController;
use App\Http\Resources\Customer as CustomerResource;
use App\Http\Requests\TypeRequest;

class CustomerController extends ResponseController
{
    //C.R.U.D.
    public function getCustomers(){
        $customers = Customer::all();

        return $this->sendResponse( CustomerResource::collection( $customer ), "Vendégek listázva." );

    }

    public function addCustomer( CustomerRequest $request ){
        //$request->validated();

        $customer = Customer::create( $input );

        return $this->sendResponse( new CustomerResource( $customer ), "Sikeres rögzítés.");
    }

    public function updateCustomer( CustomerAddChecker $request ){
        //$request->validated();

        $customer = Customer::find( $request[ "customer" ]);
        if( is_null( $customer )){

            return $this->sendError( "Nincs ilyen vendég" );
        }else{
            $customer->customer = $request[ "customer" ];
            $customer->update();

            return $this->sendResponse( new CustomerResource( $customer ), "Vendég adatok frissítve.");
        }
    }

    public function deleteCustomer( Request $request ){
        $customer = Customer::find( $request[ "customer" ]);
        if( is_null( $customer )){

            return $this->sendError( "Nincs ilyen vendég." );
        }else{
            $customer->delete();
            return $this->sendResponse( new CustomerResource( $customer ), "Vendég törölve." );
        }
    }

    public function getCustomerId( $typeName ){
        $customer = Customer::where( "customer", $typeName )->first();

        return $customer->id;
    }
}

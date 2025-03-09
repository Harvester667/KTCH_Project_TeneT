<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Http\Controllers\Api\ResponseController;
use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\Employee as EmployeeResource;

class EmployeeController extends ResponseController
{
    //C.R.U.D
    public function getEmployees(){
        $employees = Employee::all();

        return $this->sendResponse( EmployeeResource::collection( $employees ), "Dolgozók listázva." );
    }

    public function getEmployee( Request $request ){
        $employee = Employee::where( "employee", $request[ "employee" ])->first();
        if( is_null( $employee )){
            return $this->sendError( "Adathiba.", [ "Nincs ilyen dolgozó." ], 406 );
        }else{
            return $this->sendResponse( $employee, "Dolgozó listázva." );
        }
    }

    public function addEmployee( EmployeeRequest $request ){
        //$request->validated;

        $employee = new Employee();
        $employee->employee = $request[ "employee" ];
        $employee->save();

        return $this->sendResponse( new EmployeeResource( $employee ), "Új dolgozó rögzítve.");
    }

    public function updateEmployee( EmployeeRequest $request ){
        //$request->validated();
        
        $employee = Employee::find( $request[ "id" ]);
        if( is_null( $employee )){
            return $this->sendError( "Adathiba.", [ "Nincs ilyen dolgozó."], 406 );
        }else{
            $employee->employee = $request[ "employee" ];
            $employee->update();

            return $this->sendResponse( new EmployeeResource( $employee ), "Dolgozó adatai módosítva." );
        }
    }

    public function deleteEmployee( Request $request ){
        $employee = Employee::find( $request[ "id" ]);
        if( is_null( $employee )){
            return $this->sendError( "Adathiba.", [ "Nincs ilyen dolgozó." ], 406 );
        }else{
            $employee->delete();

            return $this->sendResponse( new Employee( $employee ), "Dolgozó törölve." );
        }
    }

    public function getEmployeeId( $employeeName ){
        $employee = Employee::where( "employee", $employeeName )->first();
        $id = $employee->id;

        return $id;
    }
}

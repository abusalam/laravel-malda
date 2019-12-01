<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tbl_grievance;

class GrievanceStatusController extends Controller
{
    public function grievance_status(){
    	return view("grievance_status");
    }

    public function grievance_statuss(Request $request){


       $statusCode = 200;
        $response = [
            'options' => [] //Should be changed #9
        ];
        if (!$request->ajax()) {
            $statusCode = 400;
            $response = array('error' => 'Error occured in form submit.');
            return response()->json($response, $statusCode);
        }
           if(env("CAPTCHA")==1){  
        $this->validate($request, [
            'grievance_id' => 'required',
             'capcha' => 'required|captcha',

                ], [
            'grievance_id.required' => 'Grievance ID is required',
            'capcha.required' => 'Captcha is required',
            'capcha.captcha' => 'Captcha Missmatch',
        ]);
    }else{
         $this->validate($request, [
            'grievance_id' => 'required',             
                ], [
            'grievance_id.required' => 'Grievance ID is required',
            
        ]);

    }


        try {

            $result=tbl_grievance::where('griev_auto_id',$request->grievance_id)->select('*')->get();

            $response = array(
                'options' => $result
            );


           
        } catch (\Exception $e) {
            $response = array(
                'exception' => true,
                'exception_message' => $e->getMessage(),
            );
            $statusCode = 400;
        } finally {
            return response()->json($response, $statusCode);
        }

    }
}

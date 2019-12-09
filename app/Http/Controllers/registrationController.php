<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tbl_user;
use App\tbl_mobile_verify;
use DB;

class registrationController extends Controller {

    public function userList(){
        return view("userlist");
    }

    public function userlist_datatable(Request $request){

        $draw = $request->draw;
        $offset = $request->start;
        $length = $request->length;
        $search = $request->search ["value"];
        $order = $request->order;

        $this->validate($request, [
            'search.*' => 'nullable|regex:/^[A-Za-z\s]+$/i',
                ], [
            
            'search.*.regex' => 'Search value accept only Alphabatic character',
        ]);

        $data = array();
        $record = tbl_user::select('*')->where('user_type','<>',0)
                ->orderby('code', 'desc')
        ->where(function($q) use ($search) {
        $q->orwhere('name', 'like', '%' . $search . '%');
        $q->orwhere('designation', 'like', '%' . $search . '%');
        $q->orwhere('mobile_no', 'like', '%' . $search . '%');
        });

        $filtered_count = $record->count();

        for ($i = 0; $i < count($order); $i ++) {
               $record = $record->orderBy($request->columns [$order [$i] ['column']] ['data'], strtoupper($order [$i] ['dir']));
           }

           
        $page_displayed = $record->offset($offset)->limit($length)->get();
        $count = $offset + 1;
        foreach ($page_displayed as $row) {
            $nestedData['id'] = $count;
            $nestedData['code'] = $row->code;
            $nestedData['designation'] = $row->designation;
            $nestedData['name'] = $row->name;
            $nestedData['mobile_no'] = $row->mobile_no;

            $edit_button = $delete_button = $view_button = $row->code;
            $nestedData['action'] = array('e' => $edit_button, 'd' => $delete_button, 'v' => $view_button);
            $count++;
            $data[] = $nestedData;
        }
        $response = array(
            "draw" => $draw,
            "recordsTotal" => $filtered_count,
            "recordsFiltered" => $filtered_count,
            'record_details' => $data
        );
        return response()->json($response);



    }

    public function user_edit(Request $request){
            $statusCode = 200;
        $response = array();

        $this->validate($request, [
            'user_code' => 'required|integer',
                ], [
            'user_code.required' => 'User Code is required',
            'user_code.integer' => 'User Code Accepted Only Integer',
        ]);


        try {
            $user_code = $request->user_code;
            $result = tbl_user::where('code', '=', $user_code)
                    ->select('*')
                    ->first();
             
        } catch (\Exception $e) {
            $response = array(
                'exception' => true,
                'exception_message' => $e->getMessage(),
            );
            $statusCode = 400;
        } finally {

            return view('user_create')->with('user_details', $result);
        }



    }

    public function user_delete(Request $request){

             $statusCode = 200;
        $response = [
            'options' => [] //Should be changed #9
        ];
        if (!$request->ajax()) {
            $statusCode = 400;
            $response = array('error' => 'Error occured in form submit.');
            return response()->json($response, $statusCode);
        }

        $this->validate($request, [
            'user_code' => 'required|integer',
                ], [
            'user_code.required' => 'User Code is required',
            'user_code.integer' => 'User Code Accepted Only Integer',
        ]);


        try {

            $DELETE = tbl_user::where('code', '=', $request->user_code)->delete(); //Should be changed #27


            $response = array(
                'status' => 1 //Should be changed #32
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

    public function save_otp(Request $request) {
        $statusCode = 200;
        $mobile_verification = null;
        if (!$request->ajax()) {
            $statusCode = 400;
            $response = array('error' => 'Error occured in form submit.');
            return response()->json($response, $statusCode);
        }
        $response = [
            'mobile_verification' => [] //Should be changed #9
        ];

        try {
         $mobile_no = $request->mobile_no;
            $mobile_no_checking = tbl_user::select('*')->where('mobile_no', '=', $mobile_no)->get();

            if (count($mobile_no_checking) > 0) {
                $response = array(
                    'status' => 2
                );
            } else {
                $mobile_verification = new tbl_mobile_verify();
                $mobile_verification->mobile_no = $mobile_no;
                $mobile_verification->otp = rand(1000, 9999);
                $mobile_verification->save();
                if ($mobile_no != '') {
                    $Destination = $mobile_no;
                    $Message = 'Your OTP  is:' . $mobile_verification->otp;
                    $SEND_SMS = 'TRUE';
                    $mobile_no = $Destination;

                     // include_once("sms/test_sms.php");
                }

                $response = array(
                    'status' => 1
                );
            }
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

    public function check_otp(Request $request) {
        $statusCode = 200;
        $mobile_verification = null;
        if (!$request->ajax()) {
            $statusCode = 400;
            $response = array('error' => 'Error occured in form submit.');
            return response()->json($response, $statusCode);
        }
        $response = [
            'mobile_verification' => [] //Should be changed #9
        ];
//$dt = new Carbon\Carbon();
//$before = $dt->subYears(13)->format('Y-m-d');
        $this->validate($request, [
            'otp' => 'required|integer',
            'mob' => 'required|digits:10|unique:tbl_user,mobile_no',
                ], [
            'otp.required' => 'OTP is required',
            'otp.integer' => ' OTP must be an integer',
            'mob.required' => 'Mobile No is required',
            'mob.digits' => ' Mobile no. must be 10 digit',
            'mob.unique' => 'Mobile no. must be Unique'
        ]);
        try {
            $mobile_no = $request->mob;
            $maxValue = tbl_mobile_verify::select('otp')->where('code', DB::raw("(select max(code) from tbl_mobile_verify where mobile_no=$mobile_no)"))->get();
            if ($request->otp == $maxValue[0]->otp) {

                $response = array(
                    'status' => 1
                );
            } else {
                $response = array(
                    'status' => 2
                );
            }
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

   

    

    

    
         
         
         public function forgotPassword(){
             return view('forgotPassword');
         }
         
         public function saveOtpForLogin(request $request){

             $statusCode = 200;
        $mobile_verification = null;
        if (!$request->ajax()) {
            $statusCode = 400;
            $response = array('error' => 'Error occured in form submit.');
            return response()->json($response, $statusCode);
        }
        $response = [
            'mobile_verification' => [] //Should be changed #9
        ];

        try {
         $cenvertedTime=0;
         $date_time=0;
         $mobile_no = $request->mobile_no;
            $mobile_no_checking = tbl_user::select('*')->where('mobile_no', '=', $mobile_no)->get();

            $mobile_no_verify = tbl_mobile_verify::select('*')->where('mobile_no', '=', $mobile_no)->get();


            if(count($mobile_no_verify) >0){

            $maxValue = tbl_mobile_verify::select('otp_creation_time','otp')->where('code', DB::raw("(select max(code) from tbl_mobile_verify where mobile_no=$mobile_no)"))->first();
           // echo $maxValue->created_at;die;

            $cenvertedTime = date('Y-m-d H:i:s',strtotime('+12 hour',strtotime($maxValue->otp_creation_time)));

           // echo $cenvertedTime;die;
             

            date_default_timezone_set('Asia/Kolkata');
            $date_time=date('Y-m-d H:i:s');
            //echo $date_time;die;
        }

        if($request->data!=1){



          if(count($mobile_no_verify)==0 || $cenvertedTime < $date_time ){

            if (count($mobile_no_checking) > 0 ) {
                date_default_timezone_set('Asia/Kolkata');
                
                $mobile_verification = new tbl_mobile_verify();
                $mobile_verification->mobile_no = $mobile_no;
                $mobile_verification->otp = rand(1000, 9999);
                $mobile_verification->otp_creation_time = date('Y-m-d H:i:s');

                $mobile_verification->save();
                if ($mobile_no != '') {
                    $Destination = $mobile_no;
                    $Message = 'Your OTP  is:' . $mobile_verification->otp;
                    $SEND_SMS = 'TRUE';
                    $mobile_no = $Destination;

                      include_once("sms/test_sms.php");
                }

                $response = array(
                    'status' => 1,'otp'=>1
                );
                
            }
            else{
                $response = array(
                    'status' => 2
                );
            }
        }else{
             if(config('app.otp')==0){
            $response = array(
                    'status' => 1,'otp'=>1
                );
           }else{

                $mobile_verification = new tbl_mobile_verify();
                $mobile_verification->mobile_no = $mobile_no;
                $mobile_verification->otp = rand(1000, 9999);
                $mobile_verification->otp_creation_time = date('Y-m-d H:i:s');

                $mobile_verification->save();


            $response = array(
                    'status' => 1,'otp'=>$mobile_verification->otp
                );

           }

        }

    }else{

                    $Destination = $mobile_no;
                    $Message = 'Your OTP  is:' . $maxValue->otp;
                    $SEND_SMS = 'TRUE';
                    $mobile_no = $Destination;

                      include_once("sms/test_sms.php");
                    $response = array(
                    'status' => 1
                );


    }
                
           
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
         
         public function checkOtpForLogin(Request $request){
                 $statusCode = 200;
        $mobile_verification = null;
        if (!$request->ajax()) {
            $statusCode = 400;
            $response = array('error' => 'Error occured in form submit.');
            return response()->json($response, $statusCode);
        }
        $response = [
            'mobile_verification' => [] //Should be changed #9
        ];
//$dt = new Carbon\Carbon();
//$before = $dt->subYears(13)->format('Y-m-d');
        $this->validate($request, [
            'otp' => 'required|integer',
            'mob' => 'required|digits:10',
                ], [
            'otp.required' => 'OTP is required',
            'otp.integer' => ' OTP must be an integer',
            'mob.required' => 'Mobile No is required',
            'mob.digits' => ' Mobile no. must be 10 digit',
           
        ]);
        try {
            $mobile_no = $request->mob;
            $maxValue = tbl_mobile_verify::select('otp')->where('code', DB::raw("(select max(code) from tbl_mobile_verify where mobile_no=$mobile_no)"))->get();
            if ($request->otp == $maxValue[0]->otp) {

                $response = array(
                    'status' => 1
                );

                $result=tbl_user::where('mobile_no',$mobile_no)->select('code','mobile_no','name','designation','user_type')->first();

                session(['user_code' =>  $result->code]);
                session(['user_mobile_no' =>  $result->mobile_no]);
                session(['user_designation' =>  $result->designation]);
                session(['user_name' =>  $result->name]);
                session(['user_type' =>  $result->user_type]);
                //session(['expire' => $now + (60 * 1)]);
                
            } else {
                $response = array(
                    'status' => 2
                );
            }
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
         
         public function passwordChange(Request $request){
              $statusCode = 200;
        $usermaster = null;
      
        if (!$request->ajax()) {
            $statusCode = 400;
            $response = array('error' => 'Error occured in form submit.');
            return response()->json($response, $statusCode);
        }
        $response = [
            'usermaster' => [] //Should be changed #9
        ];
         
        /*         * ****Validation****** */
        $this->validate($request, [
            'pin' => "required|digits:4",
              'mob' => 'required|digits:10',
             
            
                ], [
            'pin.required' => 'Pin is required.',
           'pin.digits' => 'Pin should be in 4 digits.',
                 'mob.required' => 'Mobile No is required',
            'mob.digits' => ' Mobile no. must be 10 digit',  
            
        ]);
      
        try {
         
            $Pin = md5($request->pin);
             $mob = $request->mob;
          $usermaster = tbl_user::where('mobile_no', '=', $mob)->update(['password' => $Pin]);
          if($usermaster==1){
                $response = array(
                    'status' => 1 //Should be changed #13
                );
          }
          else{
               $response = array(
                    'status' => 2 //Should be changed #13
                );
          }
       
           
           
           
            
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

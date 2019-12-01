<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tbl_user;
use Cache;


class LoginController extends Controller {

    public function login() {
        return view('login');
    }

    public function userRegisration() {
        return view('user_create');
    }

    public function loginAction(Request $request) {
//        dd($request->all());

        $this->validate($request, [
            'username' => "required|digits:10",
                ], [
            'username.required' => 'Mobile Number is Required',
            'username.digits' => 'Mobile Number Should be 10 Digits',        
        ]);





        $username = $request->username;
        $check1 = tbl_user::where('mobile_no', $username)->first();

        if ($check1 != null) {
            $response = array(
                'status' => 1
            );
        } else {
            $response = array('login_error' => "Mobile No Does't Exist", 'status' => 2);
        }
        return response()->json($response);
    }

    public function userRegistrationAction(Request $request) {
        //dd($request->all());
        $statuscode = 200;
        if (!$request->ajax()) {
            $statuscode = 400;
            $response = array('error' => 'Error occer in ajax call');
            return response()->json($response, $statuscode);
        }
        try {

            $mobile_no = $request->mobile_no;
            $designation = $request->designation;
            $name = $request->name;
            $edit_code = $request->edit_code;

            if ($request->edit_code == '') {
                $this->validate($request, [
                    'name' => "required|regex:/^[A-Za-z\s]+$/i|min:1|max:30",
                    'mobile_no' => "required|digits:10|unique:tbl_user,mobile_no",
                    'designation' => "required|regex:/^[A-Za-z\s]+$/i|min:1|max:30",
                        ], [
                    'name.required' => 'Name is Required',
                    'name.regex' => 'Only Alphabate and Space allowed in Name',
                    'name.min' => 'Name must be between 1 to 30 character',
                    'name.max' => 'Name must be between 1 to 30 character',
                    'mobile_no.required' => 'Moibile No is Required',
                    'mobile_no.digits' => 'Moibile No must be 10 Digits',
                    'mobile_no.unique' => 'Moibile No is Already Exist',
                    'designation.required' => 'Designation is required',
                    'designation.regex' => 'Only Alphabate and Space allowed Designation',
                    'designation.min' => 'Designation Must be between 1 to 30 Character',
                    'designation.max' => 'Designation Must be between 1 to 30 Character',
                ]);
            } else {

                $this->validate($request, [
                    'name' => "required|regex:/^[A-Za-z\s]+$/i|min:1|max:30",
                    'mobile_no' => "required|digits:10|unique:tbl_user,mobile_no," . $edit_code . ",code",
                    'designation' => "required|regex:/^[A-Za-z\s]+$/i|min:1|max:30",
                        ], [
                    'name.required' => 'Name is Required',
                    'name.regex' => 'Only Alphabate and Space allowed in Name',
                    'name.min' => 'Name must be between 1 to 30 character',
                    'name.max' => 'Name must be between 1 to 30 character',
                    'mobile_no.required' => 'Moibile No is Required',
                    'mobile_no.digits' => 'Moibile No must be 10 Digits',
                    'mobile_no.unique' => 'Moibile No is Already Exist',
                    'designation.required' => 'Designation is required',
                    'designation.regex' => 'Only Alphabate and Space allowed Designation',
                    'designation.min' => 'Designation Must be between 1 to 30 Character',
                    'designation.max' => 'Designation Must be between 1 to 30 Character',
                ]);
            }


            if ($edit_code == '') {
                $tbl_user = new tbl_user();

                $tbl_user->mobile_no = $mobile_no;
                $tbl_user->name = $name;
                $tbl_user->designation = $designation;
                $tbl_user->save();
                $response = array(
                    'status' => 1
                );
            } else {

                $save = tbl_user::where('code', '=', $edit_code)->update(['name' => $name, 'designation' => $designation, 'mobile_no' => $mobile_no]);

                $response = array(
                    'status' => 2
                );
            }
        } catch (\Exception $e) {

            $response = array(
                'exception' => true,
                'exception_message' => $e->getMessage(),
            );
            $statuscode = 400;
        } finally {
            return response()->json($response, $statuscode);
        }
    }

    public function logout() {
        session()->flush();
        Cache::flush();
        
        return redirect()->route('login');
    }

}

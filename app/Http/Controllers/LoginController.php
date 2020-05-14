<?php

namespace App\Http\Controllers;

use App\tbl_user;
use Cache;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login()
    {
        if (session()->has('user_code') == false) {
            return view('login');
        } else {
            return redirect('index');
        }
    }

    public function userRegisration()
    {
        return view('user_create');
    }

    public function loginAction(Request $request)
    {
        //dd($request->all());

        //echo config('app.captcha');die;
        if (config('app.captcha') == 0) {
            $this->validate(
                $request,
                [
                    'username' => 'required|alpha_num|max:10|min:10',
                    'captcha'  => 'required|captcha',
                ],
                [
                    'username.required'  => 'Mobile Number is Required',
                    'username.alpha_num' => 'Mobile Number Should be Digits',
                    'username.max'       => 'Mobile Number must be 10 Digits',
                    'username.min'       => 'Mobile Number must be 10 Digits',
                    'captcha.required'   => 'Captcha is Required',
                    'captcha.captcha'    => 'Captcha Missmatch',
                ]
            );
        } else {
            $this->validate(
                $request,
                [
                    'username' => 'required|alpha_num|max:10|min:10',

                ],
                [
                    'username.required'  => 'Mobile Number is Required',
                    'username.alpha_num' => 'Mobile Number Should be Digits',
                    'username.max'       => 'Mobile Number must be 10 Digits',
                    'username.min'       => 'Mobile Number must be 10 Digits',

                ]
            );
        }

        $username = $request->username;
        $check1 = tbl_user::where('mobile_no', $username)->first();

        if ($check1 != null) {
            $response = [
                'status' => 1,
            ];
        } else {
            $response = ['login_error' => "Mobile No Does't Exist", 'status' => 2];
        }

        return response()->json($response);
    }

    public function userRegistrationAction(Request $request)
    {
        //dd($request->all());
        $statuscode = 200;
        if (!$request->ajax()) {
            $statuscode = 400;
            $response = ['error' => 'Error occer in ajax call'];

            return response()->json($response, $statuscode);
        }

        $edit_code = $request->edit_code;

        if ($request->edit_code == '') {
            $this->validate(
                $request,
                [
                    'name'        => "required|regex:/^[A-Za-z\s]+$/i|min:1|max:30",
                    'mobile_no'   => 'required|alpha_num|max:10|min:10|unique:tbl_user,mobile_no',
                    'designation' => "required|regex:/^[A-Za-z\s]+$/i|min:1|max:30",
                ],
                [
                    'name.required'        => 'Name is Required',
                    'name.regex'           => 'Only Alphabate and Space allowed in Name',
                    'name.min'             => 'Name must be between 1 to 30 character',
                    'name.max'             => 'Name must be between 1 to 30 character',
                    'mobile_no.required'   => 'Moibile No is Required',
                    'mobile_no.alpha_num'  => 'Mobile Number Should be Digits',
                    'mobile_no.max'        => 'Mobile Number must be 10 Digits',
                    'mobile_no.min'        => 'Mobile Number must be 10 Digits',
                    'mobile_no.unique'     => 'Moibile No is Already Exist',
                    'designation.required' => 'Designation is required',
                    'designation.regex'    => 'Only Alphabate and Space allowed Designation',
                    'designation.min'      => 'Designation Must be between 1 to 30 Character',
                    'designation.max'      => 'Designation Must be between 1 to 30 Character',
                ]
            );
        } else {
            $this->validate(
                $request,
                [
                    'name'        => "required|regex:/^[A-Za-z\s]+$/i|min:1|max:30",
                    'mobile_no'   => 'required|alpha_num|max:10|min:10|unique:tbl_user,mobile_no,'.$edit_code.',code',
                    'designation' => "required|regex:/^[A-Za-z\s]+$/i|min:1|max:30",
                ],
                [
                    'name.required'        => 'Name is Required',
                    'name.regex'           => 'Only Alphabate and Space allowed in Name',
                    'name.min'             => 'Name must be between 1 to 30 character',
                    'name.max'             => 'Name must be between 1 to 30 character',
                    'mobile_no.required'   => 'Moibile No is Required',
                    'mobile_no.alpha_num'  => 'Mobile Number Should be Digits',
                    'mobile_no.max'        => 'Mobile Number must be 10 Digits',
                    'mobile_no.min'        => 'Mobile Number must be 10 Digits',
                    'mobile_no.unique'     => 'Moibile No is Already Exist',
                    'designation.required' => 'Designation is required',
                    'designation.regex'    => 'Only Alphabate and Space allowed Designation',
                    'designation.min'      => 'Designation Must be between 1 to 30 Character',
                    'designation.max'      => 'Designation Must be between 1 to 30 Character',
                ]
            );
        }

        try {
            $mobile_no = $request->mobile_no;
            $designation = $request->designation;
            $name = $request->name;

            if ($edit_code == '') {
                $tbl_user = new tbl_user();

                $tbl_user->mobile_no = $mobile_no;
                $tbl_user->name = $name;
                $tbl_user->designation = $designation;
                $tbl_user->save();
                $response = [
                    'status' => 1,
                ];
            } else {
                $save = tbl_user::where('code', '=', $edit_code)->update(['name' => $name, 'designation' => $designation, 'mobile_no' => $mobile_no]);

                $response = [
                    'status' => 2,
                ];
            }
        } catch (\Exception $e) {
            $response = [
                'exception' => true,

            ];
            $statuscode = 400;
        } finally {
            $res = response()->json($response, $statuscode);
        }

        return $res;
    }

    public function logout(Request $request)
    {
        session()->flush();
        Cache::flush();

        return redirect('login');
    }
}

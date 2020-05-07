<?php

namespace App\Http\Controllers;

use App\tbl_mobile_verify;
use App\tbl_user;
use DB;
use Illuminate\Http\Request;

class registrationController extends Controller
{
    public function userList()
    {
        return view('userlist');
    }

    public function userlist_datatable(Request $request)
    {
        $this->validate(
            $request,
            [
                'draw'           => 'required|integer|between:0,9999999999',
                'start'          => 'required|integer|between:0,999999999',
                'length'         => 'required|integer|between:0,100',
                'order'          => 'array',
                'search.*'       => 'nullable|regex:/^[A-Za-z0-9\s]+$/i',
                'order.*.column' => 'required|integer|between:0,4',
                'order.*.dir'    => 'required|in:asc,desc',
            ],
            [
                'draw.required' => 'Invalid Input',
                'draw.between'  => 'Invalid Input',
                'draw.integer'  => 'Invalid Input',

                'start.required' => 'Invalid Input',
                'start.between'  => 'Invalid Input',
                'start.integer'  => 'Invalid Input',

                'length.required' => 'Invalid Input',
                'length.between'  => 'Invalid Input',
                'length.integer'  => 'Invalid Input',

                'order.*.column.required' => 'Invalid Input',
                'order.*.column.integer'  => 'Invalid Input',
                'order.*.column.between'  => 'Invalid Input',

                'order.array' => 'Invalid Input',

                'order.*.dir.required' => 'Invalid Input',
                'order.*.dir.in'       => 'Invalid Input',

                'search.*.regex' => 'Invalid Input',
            ]
        );

        $draw = $request->draw;
        $offset = $request->start;
        $length = $request->length;
        $search = isset($request->search['value']) ? $request->search['value'] : '';
        $order = $request->order;

        $data = [];
        $record = tbl_user::select('*')->where('user_type', '<>', 0)
                ->orderby('code', 'desc')
        ->where(
            function ($q) use ($search) {
                $q->orwhere('name', 'like', '%'.$search.'%');
                $q->orwhere('designation', 'like', '%'.$search.'%');
                $q->orwhere('mobile_no', 'like', '%'.$search.'%');
            }
        );

        $filtered_count = $record->count();

        $page_displayed = $record->offset($offset)->limit($length)->get();
        $count = $offset + 1;
        foreach ($page_displayed as $row) {
            $nestedData['id'] = $count;
            $nestedData['code'] = $row->code;
            $nestedData['designation'] = $row->designation;
            $nestedData['name'] = $row->name;
            $nestedData['mobile_no'] = $row->mobile_no;

            $edit_button = $delete_button = $view_button = $row->code;
            $nestedData['action'] = ['e' => $edit_button, 'd' => $delete_button, 'v' => $view_button];
            $count++;
            $data[] = $nestedData;
        }
        $response = [
            'draw'            => $draw,
            'recordsTotal'    => $filtered_count,
            'recordsFiltered' => $filtered_count,
            'record_details'  => $data,
        ];

        return response()->json($response);
    }

    public function user_edit(Request $request)
    {
        $statusCode = 200;
        $response = [];

        $this->validate(
            $request,
            [
                'user_code' => 'required|integer',
            ],
            [
                'user_code.required' => 'User Code is required',
                'user_code.integer'  => 'User Code Accepted Only Integer',
            ]
        );

        try {
            $user_code = $request->user_code;
            $result = tbl_user::where('code', '=', $user_code)
                    ->select('*')
                    ->first();
        } catch (\Exception $e) {
            $response = [
                'exception' => true,

            ];
            $statusCode = 400;
        } finally {
            $res = view('user_create')->with('user_details', $result);
        }

        return $res;
    }

    public function user_delete(Request $request)
    {
        $statusCode = 200;
        $response = [
            'options' => [], //Should be changed #9
        ];
        if (!$request->ajax()) {
            $statusCode = 400;
            $response = ['error' => 'Error occured in form submit.'];

            return response()->json($response, $statusCode);
        }

        $this->validate(
            $request,
            [
                'user_code' => 'required|integer',
            ],
            [
                'user_code.required' => 'User Code is required',
                'user_code.integer'  => 'User Code Accepted Only Integer',
            ]
        );

        try {
            $DELETE = tbl_user::where('code', '=', $request->user_code)->delete(); //Should be changed #27

            $response = [
                'status' => 1, //Should be changed #32
            ];
        } catch (\Exception $e) {
            $response = [
                'exception' => true,

            ];
            $statusCode = 400;
        } finally {
            $res = response()->json($response, $statusCode);
        }

        return $res;
    }

    public function forgotPassword()
    {
        return view('forgotPassword');
    }

    public function saveOtpForLogin(request $request)
    {
        $statusCode = 200;
        $mobile_verification = null;
        if (!$request->ajax()) {
            $statusCode = 400;
            $response = ['error' => 'Error occured in form submit.'];

            return response()->json($response, $statusCode);
        }
        $this->validate(
            $request,
            [

                'mobile_no' => 'required|alpha_num|max:10|min:10',
            ],
            [
                'mobile_no.required'  => 'Mobile Number is required',
                'mobile_no.alpha_num' => 'Mobile Number Should be Digits',
                'mobile_no.max'       => 'Mobile Number must be 10 Digits',
                'mobile_no.min'       => 'Mobile Number must be 10 Digits',

            ]
        );

        $response = [
            'mobile_verification' => [], //Should be changed #9
        ];

        try {
            $cenvertedTime = 0;
            $date_time = 0;
            $mobile_no = $request->mobile_no;
            $mobile_no_checking = tbl_user::select('*')->where('mobile_no', '=', $mobile_no)->get();

            $mobile_no_verify = tbl_mobile_verify::select('*')->where('mobile_no', '=', $mobile_no)->get();

            if (count($mobile_no_verify) > 0) {
                $maxValue = tbl_mobile_verify::select('otp_creation_time', 'otp')->where('code', DB::raw("(select max(code) from tbl_mobile_verify where mobile_no=$mobile_no)"))->first();
                // echo $maxValue->created_at;die;

                $cenvertedTime = date('Y-m-d H:i:s', strtotime('+12 hour', strtotime($maxValue->otp_creation_time)));

                // echo $cenvertedTime;die;

                date_default_timezone_set('Asia/Kolkata');
                $date_time = date('Y-m-d H:i:s');
                //echo $date_time;die;
            }

            if ($request->data != 1) {
                if (count($mobile_no_verify) == 0 || $cenvertedTime < $date_time) {
                    if (count($mobile_no_checking) > 0) {
                        date_default_timezone_set('Asia/Kolkata');

                        $mobile_verification = new tbl_mobile_verify();
                        $mobile_verification->mobile_no = $mobile_no;
                        $mobile_verification->otp = random_int(1000, 9999);
                        $mobile_verification->otp_creation_time = date('Y-m-d H:i:s');

                        $mobile_verification->save();
                        if (config('app.otp') == 0) {
                            if ($mobile_no != '') {
                                $Destination = $mobile_no;
                                $Message = 'Your OTP  is:'.$mobile_verification->otp;
                                $SEND_SMS = 'TRUE';
                                $mobile_no = $Destination;

                                include_once 'sms/test_sms.php';
                                $response = [
                                    'status' => 1, 'otp'=>1,
                                ];
                            }
                        } else {
                            $response = [
                                'status' => 1, 'otp'=>$mobile_verification->otp,
                            ];
                        }
                    } else {
                        $response = [
                            'status' => 2,
                        ];
                    }
                } else {
                    if (config('app.otp') == 0) {
                        $response = [
                            'status' => 1, 'otp'=>1,
                        ];
                    } else {
                        $mobile_verification = new tbl_mobile_verify();
                        $mobile_verification->mobile_no = $mobile_no;
                        $mobile_verification->otp = random_int(1000, 9999);
                        $mobile_verification->otp_creation_time = date('Y-m-d H:i:s');

                        $mobile_verification->save();

                        $response = [
                            'status' => 1, 'otp'=>$mobile_verification->otp,
                        ];
                    }
                }
            } else {
                $Destination = $mobile_no;
                $Message = 'Your OTP  is:'.$maxValue->otp;
                $SEND_SMS = 'TRUE';
                $mobile_no = $Destination;

                include_once 'sms/test_sms.php';
                $response = [
                    'status' => 1,
                ];
            }
        } catch (\Exception $e) {
            $response = [
                'exception' => true,

            ];
            $statusCode = 400;
        } finally {
            $res = response()->json($response, $statusCode);
        }

        return $res;
    }

    public function checkOtpForLogin(Request $request)
    {
        $statusCode = 200;
        $mobile_verification = null;
        if (!$request->ajax()) {
            $statusCode = 400;
            $response = ['error' => 'Error occured in form submit.'];

            return response()->json($response, $statusCode);
        }
        $response = [
            'mobile_verification' => [], //Should be changed #9
        ];
        //$dt = new Carbon\Carbon();
        //$before = $dt->subYears(13)->format('Y-m-d');
        $this->validate(
            $request,
            [
                'otp' => 'required|integer',
                'mob' => 'required|alpha_num|max:10|min:10',
            ],
            [
                'otp.required'  => 'OTP is required',
                'otp.integer'   => ' OTP must be an integer',
                'mob.required'  => 'Mobile No is required',
                'mob.alpha_num' => 'Mobile Number Should be Digits',
                'mob.max'       => 'Mobile Number must be 10 Digits',
                'mob.min'       => 'Mobile Number must be 10 Digits',

            ]
        );

        try {
            $mobile_no = $request->mob;
            $maxValue = tbl_mobile_verify::select('otp')->where('code', DB::raw("(select max(code) from tbl_mobile_verify where mobile_no=$mobile_no)"))->get();
            if ($request->otp == $maxValue[0]->otp) {
                $response = [
                    'status' => 1,
                ];

                $result = tbl_user::where('mobile_no', $mobile_no)->select('code', 'mobile_no', 'name', 'designation', 'user_type')->first();

                session(['user_code' =>  $result->code]);
                session(['user_mobile_no' =>  $result->mobile_no]);
                session(['user_designation' =>  $result->designation]);
                session(['user_name' =>  $result->name]);
                session(['user_type' =>  $result->user_type]);
            //session(['expire' => $now + (60 * 1)]);
            } else {
                $response = [
                    'status' => 2,
                ];
            }
        } catch (\Exception $e) {
            $response = [
                'exception' => true,

            ];
            $statusCode = 400;
        } finally {
            $res = response()->json($response, $statusCode);
        }

        return $res;
    }
}

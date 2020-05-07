<?php

namespace App\Http\Controllers;

use App\tbl_grievance;
use App\tbl_grievence_forwored;
use App\tbl_mobile_verify;
use DB;
use Illuminate\Http\Request;

class GrievanceStatusController extends Controller
{
    public function grievance_status()
    {
        return view('grievance_status');
    }

    public function grievance_statuss(Request $request)
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
        if (config('app.captcha') == 0) {
            $this->validate(
                $request,
                [
                    'grievance_id' => 'required',
                    'mobileNo'     => 'required',
                    'capcha'       => 'required|captcha',
                ],
                [
                    'grievance_id.required' => 'Grievance ID is required',
                    'capcha.required'       => 'Captcha is required',
                    'capcha.captcha'        => 'Captcha Missmatch',
                ]
            );
        } else {
            $this->validate(
                $request,
                [
                    'grievance_id' => 'required',
                    'mobileNo'     => 'required',

                ],
                [
                    'grievance_id.required' => 'Grievance ID is required',
                    'capcha.required'       => 'Captcha is required',

                ]
            );
        }

        try {
            $checkMobile = tbl_grievance::where('mobile_no', '=', $request->mobileNo)->where('code', '=', $request->grievance_id)->first();

            if ($checkMobile != null) {
                $gData = tbl_grievance::where('code', $request->grievance_id)->select('*')->first();
                $created_at = \Carbon\Carbon::parse($gData->created_at)->format('d/m/Y');

                $grievanceData = tbl_grievence_forwored::join('tbl_user', 'tbl_user.code', 'tbl_grievence_forwored.from_forword')
                ->select('remark', 'tbl_user.name', 'tbl_grievence_forwored.created_at', 'tbl_user.designation')
                ->where('griv_code', $request->grievance_id)
                ->get();
                //print_r($grievanceData[0]->created_at);die;
                $remarkData = [];
                foreach ($grievanceData as $grievance) {
                    $data['name'] = $grievance->name;
                    $data['designation'] = $grievance->designation;
                    $data['date'] = \Carbon\Carbon::parse($grievance->created_at)->format('d/m/Y');

                    $remarkData[] = $data;
                }

                $response = [
                    'gData'      => $gData,
                    'remarkData' => $remarkData,
                    'created_at' => $created_at,
                ];
            } else {
                $response = [
                    'flag' => 1,
                ];
            }

            $result = tbl_grievance::where('code', $request->code)->select('*')->get();
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

    public function resolve_grievance_list()
    {
        return view('resolve_grievance_list');
    }

    public function resolve_grievance_datatable(Request $request)
    {
        $this->validate(
            $request,
            [
                'draw'           => 'required|integer|between:0,9999999999',
                'start'          => 'required|integer|between:0,999999999',
                'length'         => 'required|integer|between:0,100',
                'order'          => 'array',
                'search.*'       => 'nullable|regex:/^[A-Za-z0-9\s]+$/i',
                'order.*.column' => 'required|integer|between:0,6',
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

        if (session()->get('user_type') == 0) {
            $record = tbl_grievance::where('close_status', 2)->select('name', 'mobile_no', 'email', 'complain', 'code', 'created_at')->orderby('code', 'desc')
            ->where(
                function ($q) use ($search) {
                    $q->orwhere('name', 'like', '%'.$search.'%');
                    $q->orwhere('mobile_no', 'like', '%'.$search.'%');
                    $q->orwhere('code', 'like', '%'.$search.'%');
                }
            );
        } else {
            $record = tbl_grievance::join('tbl_grievence_forwored', 'tbl_grievence_forwored.griv_code', 'tbl_grivense.code')

            ->where('tbl_grievence_forwored.from_forword', '=', session()->get('user_code'))->where('tbl_grivense.close_status', 2)->wherenull('tbl_grievence_forwored.to_forword')
            //->wherenotnull('tbl_grievence_forwored.griv_code')
            ->select('tbl_grivense.name', 'tbl_grivense.mobile_no', 'tbl_grivense.email', 'tbl_grivense.complain', 'tbl_grivense.code', 'tbl_grivense.created_at', 'tbl_grivense.updated_at')
            ->orderby('code', 'desc')
            ->where(
                function ($q) use ($search) {
                    $q->orwhere('tbl_grivense.name', 'like', '%'.$search.'%');
                    $q->orwhere('tbl_grivense.mobile_no', 'like', '%'.$search.'%');
                    $q->orwhere('tbl_grivense.code', 'like', '%'.$search.'%');
                }
            );
        }

        // if ($case_data!= '') {
        //            $record = $record->where('case_no', '=', $case_data);
        //          }

        $filtered_count = $record->count();
        $page_displayed = $record->offset($offset)->limit($length)->get();
        $count = $offset + 1;
        foreach ($page_displayed as $row) {
            $nestedData['id'] = $count;
            $nestedData['code'] = $row->code;
            $nestedData['name'] = $row->name;
            $nestedData['mobile_no'] = $row->mobile_no;
            $nestedData['email'] = $row->email;
            $nestedData['complain'] = $row->complain;

            $nestedData['created_at'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('d/m/Y');
            // $nestedData['updated_at'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->updated_at)->format('d/m/Y');

            $view_button = $row->code;
            $nestedData['action'] = ['v' => $view_button];
            $count++;
            $data[] = $nestedData;
        }
        //print_r($data);die;
        $response = [
            'draw'            => $draw,
            'recordsTotal'    => $filtered_count,
            'recordsFiltered' => $filtered_count,
            'record_details'  => $data,
        ];

        return response()->json($response);
    }

    public function save_otp_for_grievancestatus(Request $request)
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
            'mobile_verification' => [],
        ];

        try {
            $mobile_no = $request->mobile_no;

            $mobile_verification = new tbl_mobile_verify();
            $mobile_verification->mobile_no = $mobile_no;
            $mobile_verification->otp = random_int(1000, 9999);
            $mobile_verification->save();
            if (config('app.otp') == 0) {
                if ($mobile_no != '') {
                    $Destination = $mobile_no;
                    $Message = 'Your OTP  is:'.$mobile_verification->otp;
                    $SEND_SMS = 'TRUE';
                    $mobile_no = $Destination;

                    include_once 'sms/test_sms.php';
                }

                $response = [
                    'status' => 1, 'otp'=>1,
                ];
            } else {
                $response = [
                    'status' => 1, 'otp'=>$mobile_verification->otp,
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

    public function check_otp_for_grievancestatus(Request $request)
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
            //echo $mobile_no;die;
            $maxValue = tbl_mobile_verify::select('otp')->where('code', DB::raw("(select max(code) from tbl_mobile_verify where mobile_no=$mobile_no)"))->get();
            //echo $maxValue[0]->otp; die;
            if ($request->otp == $maxValue[0]->otp) {
                $response = [
                    'status' => 1,
                ];
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

        return  $res;
    }
}

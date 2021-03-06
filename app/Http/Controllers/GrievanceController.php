<?php

namespace App\Http\Controllers;

use App\tbl_grievance;
use App\tbl_grievence_forwored;
use App\tbl_mobile_verify;
use App\tbl_user;
use DB;
use Illuminate\Http\Request;

class GrievanceController extends Controller
{
    public function grivense()
    {
        return view('grivense');
    }

    public function grivanceSave(Request $request)
    {
        $filename_upload = '';
        $statuscode = 200;
        if (!$request->ajax()) {
            $statuscode = 400;
            $response = ['error' => 'Error occer in ajax call'];

            return response()->json($response, $statuscode);
        }

        if (config('app.captcha') == 0) {
            $this->validate($request, [
                'grivense_name'     => "required|regex:/^[\pL\s]+$/u",
                'mobile_no'         => 'required|alpha_num|max:10|min:10',
                'grivense_email'    => 'required|email',
                'grivense_complain' => 'required|regex:/^[A-Za-z0-9\/.,\s()-]+$/i',
                'captcha'           => 'required|captcha',
                //'attatchment' => 'nullable|mimes:pdf| max:1024',

            ], [
                'grivense_name.required'     => 'Name is Required',
                'grivense_name.regex'        => 'Name consist of alphabatical characters and spaces only',
                'mobile_no.required'         => 'Moibile No is Required',
                'mobile_no.alpha_num'        => 'Mobile Number Should be Digits',
                'mobile_no.max'              => 'Mobile Number must be 10 Digits',
                'mobile_no.min'              => 'Mobile Number must be 10 Digits',
                'grivense_email.required'    => 'Email Id Is Required',
                'grivense_email.email'       => 'Enter correct email Format',
                'grivense_complain.required' => 'Please enter complain',
                'grivense_complain.regex'    => 'Alphanumric and some special characters like ()./- allow',
                'captcha.captcha'            => 'Captcha Missmatch',
                // 'attatchment.mimes' => 'Attatchment file must be a pdf format.',
                // 'attatchment.max' => 'Upload Document Maximum file Size should be 1 MB.'

            ]);
        } else {
            $this->validate($request, [
                'grivense_name'     => "required|regex:/^[\pL\s]+$/u",
                'mobile_no'         => 'required|alpha_num|max:10|min:10',
                'grivense_email'    => 'required|email',
                'grivense_complain' => 'required|regex:/^[A-Za-z0-9\/.,\s()-]+$/i',
                //'attatchment' => 'nullable|mimes:pdf| max:1024',

            ], [
                'grivense_name.required'     => 'Name is Required',
                'grivense_name.regex'        => 'Name consist of alphabatical characters and spaces only',
                'mobile_no.required'         => 'Moibile No is Required',
                'mobile_no.alpha_num'        => 'Mobile Number Should be Digits',
                'mobile_no.max'              => 'Mobile Number must be 10 Digits',
                'mobile_no.min'              => 'Mobile Number must be 10 Digits',
                'grivense_email.required'    => 'Email Id Is Required',
                'grivense_email.email'       => 'Enter correct email Format',
                'grivense_complain.required' => 'Please enter complain',
                'grivense_complain.regex'    => 'Alphanumric and some special characters like ()./- allow',
                // 'attatchment.mimes' => 'Attatchment file must be a pdf format.',
                // 'attatchment.max' => 'Upload Document Maximum file Size should be 1 MB.',

            ]);
        }

        try {
            $n = random_int(1111111111, 9999999999);

            $griv_idd = $n;

            //echo $griv_idd; die;

            // if (!empty($request->file('attatchment'))) {
            //     $file_attatchment = $request->file('attatchment');
            //     $file_ext = $file_attatchment->getClientOriginalExtension();
            //     $filename_upload = date('dmYhms').random_int(101, 99999).'.'.$file_ext;
            //     $destination_path_attatchment = 'upload/grievance_attatchment';
            //     $file_attatchment->move($destination_path_attatchment, $filename_upload);
            // }

            $tbl_grivense = new tbl_grievance();
            $tbl_grivense->name = $request->grivense_name;
            $tbl_grivense->mobile_no = $request->mobile_no;
            $tbl_grivense->email = $request->grivense_email;
            $tbl_grivense->complain = $request->grivense_complain;
            $tbl_grivense->griev_auto_id = $griv_idd;
            //$tbl_grivense->attatchment = $filename_upload;
            $tbl_grivense->save();
            //$inserted_id = $tbl_grivense->code;
            $maxValue = tbl_grievance::select('code')->where('code', DB::raw("(select max(code) from tbl_grivense where mobile_no=$request->mobile_no)"))->first();
            //echo $maxValue->code;die;

            $Destination = $request->mobile_no;
            $Message = 'Your Grievance ID is:'.$maxValue->code;
            $SEND_SMS = 'TRUE';
            $mobile_no = $Destination;

            include_once 'sms/test_sms.php';

            $response = [
                'status' => 1,
            ];
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

    public function grievance_list()
    {
        return view('grievance_list');
    }

    public function grievance_datatable(Request $request)
    {
        $this->validate($request, [
            'draw'           => 'required|integer|between:0,9999999999',
            'start'          => 'required|integer|between:0,999999999',
            'length'         => 'required|integer|between:0,100',
            'search.*'       => 'nullable|regex:/^[A-Za-z0-9\s]+$/i',
            'order'          => 'array',
            'order.*.column' => 'required|integer|between:0,5',
            'order.*.dir'    => 'required|in:asc,desc',
        ], [
            'draw.required' => 'Invalid Input',
            'draw.between'  => 'Invalid Input',
            'draw.integer'  => 'Invalid Input',

            'start.required' => 'Invalid Input',
            'start.between'  => 'Invalid Input',
            'start.integer'  => 'Invalid Input',

            'length.required' => 'Invalid Input',
            'length.between'  => 'Invalid Input',
            'length.integer'  => 'Invalid Input',

            'order.array' => 'Invalid Input',

            'order.*.column.required' => 'Invalid Input',
            'order.*.column.integer'  => 'Invalid Input',
            'order.*.column.between'  => 'Invalid Input',

            'order.*.dir.required' => 'Invalid Input',
            'order.*.dir.in'       => 'Invalid Input',

            'search.*.regex' => 'Invalid Input',

        ]);

        $draw = $request->draw;
        $offset = $request->start;
        $length = $request->length;
        $search = isset($request->search['value']) ? $request->search['value'] : '';
        $order = $request->order;
        $user_cd = session()->get('user_code');

        $data = [];

        if (session()->get('user_type') == 0) {
            $record = tbl_grievance::leftjoin('tbl_grievence_forwored', 'tbl_grievence_forwored.griv_code', 'tbl_grivense.code')
                ->wherenull('tbl_grievence_forwored.griv_code')
                ->select('tbl_grivense.name', 'tbl_grivense.mobile_no', 'tbl_grivense.email', 'tbl_grivense.complain', 'tbl_grivense.code')
                ->orderby('code', 'desc')
                ->where(function ($q) use ($search) {
                    $q->orwhere('tbl_grivense.name', 'like', '%'.$search.'%');
                    $q->orwhere('tbl_grivense.mobile_no', 'like', '%'.$search.'%');
                    $q->orwhere('tbl_grivense.code', 'like', '%'.$search.'%');
                });
        } else {
            $record = tbl_grievence_forwored::join('tbl_user', 'tbl_user.code', 'tbl_grievence_forwored.to_forword')
                ->join('tbl_grivense', 'tbl_grivense.code', 'tbl_grievence_forwored.griv_code')
                ->select('tbl_grivense.name', 'tbl_grivense.mobile_no', 'tbl_grievence_forwored.from_forword', 'tbl_grievence_forwored.to_forword', 'tbl_grivense.email', 'tbl_grivense.complain', 'tbl_grivense.code')
                ->where('tbl_user.code', $user_cd)

                ->orderby('code', 'desc')
                ->where(function ($q) use ($search) {
                    $q->orwhere('tbl_grivense.name', 'like', '%'.$search.'%');
                    $q->orwhere('tbl_grivense.mobile_no', 'like', '%'.$search.'%');
                    $q->orwhere('tbl_grivense.code', 'like', '%'.$search.'%');
                });
        }

        // if ($case_data!= '') {
        //            $record = $record->where('case_no', '=', $case_data);
        //          }

        $all_record = $record;
        $filtered_count = $record->count();

        $page_displayed = $all_record->offset($offset)->limit($length)->get();
        $count = $offset + 1;
        $newarray = [];
        foreach ($page_displayed as $row) {
            $get = tbl_grievence_forwored::where('griv_code', '=', $row->code)
                ->where('from_forword', '=', session()->get('user_code'))
                ->exists();

            $nestedData['id'] = $count;
            $nestedData['code'] = $row->code; //grievance code
            $nestedData['name'] = $row->name;
            $nestedData['mobile_no'] = $row->mobile_no;
            $nestedData['email'] = $row->email;
            $nestedData['complain'] = $row->complain;

            $view_button = $row->code;
            $nestedData['action'] = ['v' => $view_button];
            $count++;

            if ($get != 1) {
                $data[] = $nestedData;
            }
        }
        $response = [
            'draw'            => $draw,
            'recordsTotal'    => $filtered_count,
            'recordsFiltered' => $filtered_count,
            'record_details'  => $data,
        ];

        return response()->json($response);
    }

    public function save_otp_for_grievance(Request $request)
    {
        $statusCode = 200;
        $mobile_verification = null;
        if (!$request->ajax()) {
            $statusCode = 400;
            $response = ['error' => 'Error occured in form submit.'];

            return response()->json($response, $statusCode);
        }

        $this->validate($request, [

            'mobile_no' => 'required|alpha_num|max:10|min:10',
        ], [
            'mobile_no.required'  => 'Mobile Number is required',
            'mobile_no.alpha_num' => 'Mobile Number Should be Digits',
            'mobile_no.max'       => 'Mobile Number must be 10 Digits',
            'mobile_no.min'       => 'Mobile Number must be 10 Digits',

        ]);
        $response = [
            'mobile_verification' => [], //Should be changed #9
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

    public function check_otp_for_grievance(Request $request)
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
        $this->validate($request, [
            'otp' => 'required|integer',
            'mob' => 'required|alpha_num|max:10|min:10',
        ], [
            'otp.required'  => 'OTP is required',
            'otp.integer'   => ' OTP must be an integer',
            'mob.required'  => 'Mobile No is required',
            'mob.alpha_num' => 'Mobile Number Should be Digits',
            'mob.max'       => 'Mobile Number must be 10 Digits',
            'mob.min'       => 'Mobile Number must be 10 Digits',
        ]);

        try {
            $mobile_no = $request->mob;
            $maxValue = tbl_mobile_verify::select('otp')->where('code', DB::raw("(select max(code) from tbl_mobile_verify where mobile_no=$mobile_no)"))->get();
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

        return $res;
    }

    public function view_user(Request $request)
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

        $this->validate($request, [
            'grievance_code' => 'required|integer',
        ], [
            'grievance_code.required' => 'Grievance Code is required',
            'grievance_code.integer'  => 'Grievance Code Accepted Only Integer',
        ]);

        try {
            $all_data = tbl_grievance::where('code', $request->grievance_code)->select('*')->first();

            $grievanceData = tbl_grievence_forwored::join('tbl_user', 'tbl_user.code', 'tbl_grievence_forwored.from_forword')
                ->select('remark', 'tbl_user.name', 'tbl_grievence_forwored.created_at')
                ->where('griv_code', $request->grievance_code)
                ->get();
            //print_r($grievanceData[0]->created_at);die;
            $gdata = [];
            foreach ($grievanceData as $grievance) {
                $data['remark'] = $grievance->remark;
                $data['name'] = $grievance->name;
                $data['date'] = \Carbon\Carbon::parse($grievance->created_at)->format('d/m/Y');

                $gdata[] = $data;
            }
            $response = [
                'options'    => $all_data,
                'remarkData' => $gdata,
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

    public function user_list(Request $request)
    {
        $statusCode = 200;
        if (!$request->ajax()) {
            $statusCode = 400;
            $response = ['error' => 'Error occered in Json call.'];

            return response()->json($response, $statusCode);
        }

        try {
            $loginUser = session()->get('user_code');
            //echo $loginUser;die;
            $dist = tbl_user::select(DB::raw("CONCAT('Name: ',name,',Designation: ',designation,',Mobile No :',mobile_no) AS user_details"), 'code')->where('user_type', '<>', 0)
                ->where('code', '!=', $loginUser)
                ->pluck('user_details', 'code')->all();

            $response = [
                'options' => $dist, 'status' => 1,
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

    public function save_forword(Request $request)
    {

        //echo $request->attatchment;die;
        $filename_upload = '';

        $statusCode = 200;
        if (!$request->ajax()) {
            $statusCode = 400;
            $response = ['error' => 'Error occered in Json call.'];

            return response()->json($response, $statusCode);
        }
        $this->validate($request, [
            'grievance_code' => 'required|integer',
            'to_forword'     => 'nullable|integer',
            'remark'         => 'required',
            //'attatchment' => 'nullable|mimes:pdf|max:1024',
            'forwardresolved' => 'required|integer',
        ], [
            'grievance_code.required'  => 'Grievance Code is Required',
            'grievance_code.integer'   => 'Grievance Code Should be Integer',
            'forwardresolved.required' => 'Please Choose Resolved or Forward',
            'forwardresolved.integer'  => 'Please Choose Resolved or Forward',
            'to_forword.required'      => 'To Forword is required',
            'to_forword.integer'       => 'To Forword Should be Integer',
            'remark.required'          => 'Remark is Required',
            //'attatchment.max' => 'Upload Document Maximum file Size should be 1 MB.',
        ]);
        if ($request->attatchment != 'undefined') {
            $this->validate($request, [
                'attatchment' => 'nullable|mimes:pdf|max:1024',

            ], [
                'attatchment.mimes' => 'Attachment file Should be pdf format',
                'attatchment.max'   => 'Upload Document Maximum file Size should be 1 MB.',
            ]);
        }

        try {
            $result = tbl_grievence_forwored::where('griv_code', $request->grievance_code)->where('to_forword', $request->to_forword)->get();

            if ($result->count() > 0) {
                $response = ['status' => 2];
            } else {
                if (!empty($request->file('attatchment'))) {
                    $file_attatchment = $request->file('attatchment');
                    $file_ext = $file_attatchment->getClientOriginalExtension();
                    $filename_upload = date('dmYhms').random_int(101, 99999).'.'.$file_ext;
                    $destination_path_attatchment = 'upload/forward_attatchment';
                    $file_attatchment->move($destination_path_attatchment, $filename_upload);
                }
                //1 - Resolved
                //0 - forward
                // echo $request->forwardresolved;die;
                $tbl_grivense_frd = new tbl_grievence_forwored();
                $tbl_grivense_frd->griv_code = $request->grievance_code;

                $tbl_grivense_frd->from_forword = session()->get('user_code');
                $tbl_grivense_frd->remark = $request->remark;
                $tbl_grivense_frd->attatchment = $filename_upload;

                if ($request->forwardresolved == 1) {
                    $tbl_grivense_frd->to_forword = null;

                    // $tbl_grievance = tbl_grievance::firstOrCreate(['code' =>$request->grievance_code]);
                    // $tbl_grievance->close_status = 2;
                    // $tbl_grievance->save();
                    // $tbl_grievance = new tbl_grievance();
                    // $tbl_grievance->exists = true;
                    // $tbl_grievance->remark = $request->remark;
                    // $tbl_grievance->code = $request->grievance_code;
                    // $tbl_grievance->close_status = 2;
                    // $tbl_grievance->save();

                    $tbl_grievance = tbl_grievance::where('code', $request->grievance_code)
->update(['remark' => $request->remark, 'close_status' => 2]);

                    $tbl_grivense_frd->save();

                    $response = ['status' => 3];
                } else {
                    $tbl_grivense_frd->to_forword = $request->to_forword;
                    $tbl_grivense_frd->save();
                    $tbl_grievance = tbl_grievance::where('code', $request->grievance_code)
->update(['close_status' => 3]);

                    $tbl_grivense_frd = tbl_grievence_forwored::where('griv_code', $request->grievance_code)->where('to_forword', session()->get('user_code'))
->update(['status' => 1]);

                    $response = ['status' => 1];
                }
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

    public function forwarded_grievance_list()
    {
        return view('forword_grievance_list');
    }

    public function forwored_grievance_datatable(Request $request)
    {

      // if(is_array($request->search) =='' || is_array($request->order) =='' || is_array($request->draw) ==1 || is_array($request->start) ==1 || is_array($request->length) ==1 ){
        //       $statusCode = 400;
        //       $response = array('errors' => 'Invalid Input');
        //       return response()->json($response, $statusCode);

        // }

        //$result1 = isset($request->search["value"]) ? $request->search["value"] :'';
        // $result2 = isset($request->search["regex"]) ? $request->search["regex"] :'';
        //$result2 = isset($request->search) ? $request->search :'';

        // if( $result2 == ''){

        //       $statusCode = 400;
        //       $response = array('errors' => 'Invalid Input');
        //       return response()->json($response, $statusCode);

        // }

        $this->validate($request, [
            'draw'           => 'required|integer|between:0,9999999999',
            'start'          => 'required|integer|between:0,999999999',
            'length'         => 'required|integer|between:0,100',
            'search.*'       => 'nullable|regex:/^[A-Za-z0-9\s]+$/i',
            'order'          => 'array',
            'order.*.column' => 'required|integer|between:0,7',
            'order.*.dir'    => 'required|in:asc,desc',
            'from_date'      => 'nullable|date_format:d/m/Y',
            'to_date'        => 'nullable|date_format:d/m/Y',
        ], [
            'draw.required' => 'Invalid Input',
            'draw.between'  => 'Invalid Input',
            'draw.integer'  => 'Invalid Input',

            'start.required' => 'Invalid Input',
            'start.between'  => 'Invalid Input',
            'start.integer'  => 'Invalid Input',

            'length.required' => 'Invalid Input',
            'length.between'  => 'Invalid Input',
            'length.integer'  => 'Invalid Input',

            'order.array' => 'Invalid Input',

            'order.*.column.required' => 'Invalid Input',
            'order.*.column.integer'  => 'Invalid Input',
            'order.*.column.between'  => 'Invalid Input',

            'order.*.dir.required' => 'Invalid Input',
            'order.*.dir.in'       => 'Invalid Input',
            'search.*.regex'       => 'Invalid Input',

            'from_date.date_format' => 'Invalid Input',
            'to_date.date_format'   => 'Invalid Input',

        ]);

        $draw = $request->draw;
        $offset = $request->start;
        $length = $request->length;
        $order = $request->order;
        $search = isset($request->search['value']) ? $request->search['value'] : '';
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $from_dt = date('Y-m-d', strtotime(trim(str_replace('/', '-', $from_date))));
        $to_dt = date('Y-m-d', strtotime(trim(str_replace('/', '-', $to_date))));

        $data = [];
        $record = tbl_grievance::leftjoin('tbl_grievence_forwored', 'tbl_grievence_forwored.griv_code', 'tbl_grivense.code')
            ->join('tbl_user', 'tbl_user.code', 'tbl_grievence_forwored.to_forword')
            ->where('tbl_grievence_forwored.from_forword', '=', session()->get('user_code'))->where('tbl_grivense.close_status', '<>', 1)
            //->wherenotnull('tbl_grievence_forwored.griv_code')
            ->select('tbl_grivense.name as gname', 'tbl_grivense.mobile_no', 'tbl_grivense.email', 'tbl_grivense.complain', 'tbl_grivense.code', 'tbl_user.name', 'tbl_grivense.created_at')
            ->orderby('code', 'desc')
            ->where(function ($q) use ($search) {
                $q->orwhere('tbl_grivense.name', 'like', '%'.$search.'%');
                $q->orwhere('tbl_grivense.mobile_no', 'like', '%'.$search.'%');
                $q->orwhere('tbl_grivense.code', 'like', '%'.$search.'%');
                $q->orwhere('tbl_user.name', 'like', '%'.$search.'%');
            });

        if ($from_date != '') {
            $record = $record->where(DB::raw('DATE(tbl_grievence_forwored.created_at)'), '>=', $from_dt);
        }
        if ($to_date != '') {
            $record = $record->where(DB::raw('DATE(tbl_grievence_forwored.created_at)'), '<=', $to_dt);
        }

        $all_record = $record;
        $filtered_count = $record->count();

        $page_displayed = $all_record->offset($offset)->limit($length)->get();
        $count = $offset + 1;
        foreach ($page_displayed as $row) {
            $nestedData['id'] = $count;
            $nestedData['code'] = $row->code;
            $nestedData['name'] = $row->gname;
            $nestedData['mobile_no'] = $row->mobile_no;
            $nestedData['email'] = $row->email;
            $nestedData['complain'] = $row->complain;
            $nestedData['to_forword'] = $row->name;
            $nestedData['created_at'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('d/m/Y');

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

    public function view_user_for_forward(Request $request)
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

        $this->validate($request, [
            'grievance_code' => 'required|integer',
        ], [
            'grievance_code.required' => 'Grievance Code is required',
            'grievance_code.integer'  => 'Grievance Code Accepted Only Integer',
        ]);

        try {
            $all_data = tbl_grievance::where('code', $request->grievance_code)->select('*')->first();

            $grievanceData = tbl_grievence_forwored::join('tbl_user', 'tbl_user.code', 'tbl_grievence_forwored.from_forword')
                ->select('remark', 'tbl_user.name', 'tbl_grievence_forwored.attatchment', 'tbl_grievence_forwored.created_at')
                ->where('griv_code', $request->grievance_code)
                ->get();
            //print_r($grievanceData[0]->created_at);die;
            $gdata = [];
            foreach ($grievanceData as $grievance) {
                $data['remark'] = $grievance->remark;
                $data['name'] = $grievance->name;
                $data['date'] = \Carbon\Carbon::parse($grievance->created_at)->format('d/m/Y');
                $data['attatchment'] = $grievance->attatchment;
                $gdata[] = $data;
            }
            $response = [
                'options'    => $all_data,
                'remarkData' => $gdata,
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

    public function close_grievance(Request $request)
    {
        $statusCode = 200;
        if (!$request->ajax()) {
            $statusCode = 400;
            $response = ['error' => 'Error occered in Json call.'];

            return response()->json($response, $statusCode);
        }
        $this->validate($request, [
            'grievance_code' => 'required|integer',

            'remark' => 'nullable',
        ], [
            'grievance_code.required' => 'Grievance Code is Required',
            'grievance_code.integer'  => 'Grievance Code Should be Integer',
            'remark.nullable'         => 'Remark can be Nullable',
        ]);

        try {
            $grivance_code = $request->grievance_code;
            $remarks = $request->remark;
            $result = tbl_grievance::where('code', $grivance_code)->update(['remark'=>$request->remark, 'close_status'=>1]);

            if ($result != '') {
                $response = ['status' => 1];
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

    public function close_grievance_list()
    {
        return view('closed_grievance_list');
    }

    public function closed_grievance_datatable(Request $request)
    {
        //dd($request->all());

        $this->validate($request, [
            'draw'           => 'required|integer|between:0,9999999999',
            'start'          => 'required|integer|between:0,999999999',
            'length'         => 'required|integer|between:0,100',
            'search.*'       => 'nullable|regex:/^[A-Za-z0-9\s]+$/i',
            'order'          => 'array',
            'order.*.column' => 'required|integer|between:0,6',
            'order.*.dir'    => 'required|in:asc,desc',
        ], [
            'draw.required' => 'Invalid Input',
            'draw.between'  => 'Invalid Input',
            'draw.integer'  => 'Invalid Input',

            'start.required' => 'Invalid Input',
            'start.between'  => 'Invalid Input',
            'start.integer'  => 'Invalid Input',

            'length.required' => 'Invalid Input',
            'length.between'  => 'Invalid Input',
            'length.integer'  => 'Invalid Input',

            'order.array' => 'Invalid Input',

            'order.*.column.required' => 'Invalid Input',
            'order.*.column.integer'  => 'Invalid Input',
            'order.*.column.between'  => 'Invalid Input',

            'order.*.dir.required' => 'Invalid Input',
            'order.*.dir.in'       => 'Invalid Input',

            'search.*.regex' => 'Invalid Input',
        ]);
        $draw = $request->draw;
        $offset = $request->start;
        $length = $request->length;
        $search = isset($request->search['value']) ? $request->search['value'] : '';
        $order = $request->order;

        $data = [];

        if (session()->get('user_type') == 0) {
            $record = tbl_grievance::where('close_status', 1)->select('name', 'mobile_no', 'email', 'complain', 'code', 'created_at', 'updated_at')->orderby('code', 'desc')
            ->where(function ($q) use ($search) {
                $q->orwhere('name', 'like', '%'.$search.'%');
                $q->orwhere('mobile_no', 'like', '%'.$search.'%');
                $q->orwhere('email', 'like', '%'.$search.'%');
                $q->orwhere('code', 'like', '%'.$search.'%');
            });
        } else {
            $record = tbl_grievance::leftjoin('tbl_grievence_forwored', 'tbl_grievence_forwored.griv_code', 'tbl_grivense.code')
            ->join('tbl_user', 'tbl_user.code', 'tbl_grievence_forwored.to_forword')
            ->where('tbl_grievence_forwored.to_forword', '=', session()->get('user_code'))->where('tbl_grivense.close_status', 1)
            //->wherenotnull('tbl_grievence_forwored.griv_code')
            ->select('tbl_grivense.name', 'tbl_grivense.mobile_no', 'tbl_grivense.email', 'tbl_grivense.complain', 'tbl_grivense.code', 'tbl_grivense.created_at', 'tbl_grivense.updated_at')
            ->orderby('code', 'desc')
            ->where(function ($q) use ($search) {
                $q->orwhere('tbl_grivense.name', 'like', '%'.$search.'%');
                $q->orwhere('tbl_grivense.mobile_no', 'like', '%'.$search.'%');
                $q->orwhere('tbl_grivense.code', 'like', '%'.$search.'%');
            });
        }

        // if ($case_data!= '') {
        //            $record = $record->where('case_no', '=', $case_data);
        //          }

        $all_record = $record;
        $filtered_count = $record->count();

        $page_displayed = $all_record->offset($offset)->limit($length)->get();
        $count = $offset + 1;
        foreach ($page_displayed as $row) {
            $nestedData['id'] = $count;
            $nestedData['code'] = $row->code;
            $nestedData['name'] = $row->name;
            $nestedData['mobile_no'] = $row->mobile_no;
            $nestedData['email'] = $row->email;
            $nestedData['complain'] = $row->complain;

            $nestedData['created_at'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('d/m/Y');
            $nestedData['updated_at'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->updated_at)->format('d/m/Y');

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
}

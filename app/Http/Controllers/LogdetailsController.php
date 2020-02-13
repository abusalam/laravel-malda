<?php
namespace App\Http\Controllers;

use App\tbl_user_log_details;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use DB;
class LogdetailsController extends Controller
{
    public function logdetails(Request $request){
        $case_data=$request->case_data;


        $draw = $request->draw;
        $offset = $request->start;
        $length = $request->length;
        $search = $request->search ["value"];
        $order = $request->order;

         $this->validate($request, [
            'draw'=>'required|digits_between:1,11|not_in:0|regex:/^[0-9]+$/',
            'start'=>'required|digits_between:1,11|regex:/^[0-9]+$/',
            'length'=>'required|digits_between:1,11|regex:/^[0-9]+$/',
            'search.*' => 'nullable|regex:/^[A-Za-z0-9\s]+$/i',
            'order.*.column' => 'required|digits_between:1,11|regex:/^[0-9]+$/',
            'order.*.dir' => 'required|in:asc,desc'
            ], [
            'draw.required' => 'Something going wrong',
            'draw.digits_between' => 'Something going wrong',
            'draw.not_in' => 'Something going wrong',
            'draw.regex' => 'Something going wrong',
            'draw.regex' => 'Something going wrong',

            'start.required' => 'Something going wrong', 
            'start.digits_between' => 'Something going wrong',
            'start.regex' => 'Something going wrong',

            'length.required' => 'Something going wrong', 
            'length.digits_between' => 'Something going wrong', 
            'length.regex' => 'Something going wrong',

            'order.*.column.required' => 'Something going wrong',
            'order.*.column.digits_between' => 'Something going wrong',
            'order.*.column.regex' => 'Something going wrong',

            'order.*.dir.required' => 'Something going wrong',
            'order.*.dir.in' => 'Something going wrong',

            'search.*.regex' => 'Search value accept only Alphanumeric character',
        ]); 

        $data = array();
        $record = tbl_user_log_details::leftjoin('tbl_user','tbl_user.code','tbl_user_log_details.userCode')
        ->select('tbl_user_log_details.code','tbl_user.name','userCode','userIp','visitedPage','tbl_user_log_details.created_at')
                ->orderby('tbl_user_log_details.code', 'desc')
        ->where(function($q) use ($search) {
        $q->orwhere('tbl_user.name', 'like', '%' . $search . '%');
        $q->orwhere('userIp', 'like', '%' . $search . '%');
        $q->orwhere('visitedPage', 'like', '%' . $search . '%');
        });

      
        $filtered_count = $record->count();
        $page_displayed = $record->offset($offset)->limit($length)->get();
        $count = $offset + 1;
        
        foreach ($page_displayed as $row) {
          
            $nestedData['code'] = $count;
            if($row->userCode == null){
                $nestedData['userCode'] = "Anonymous USer";    
            }else{
            $nestedData['userCode'] = $row->name;
            }
            $nestedData['userIp'] = $row->userIp;
            $nestedData['visitedPage'] = $row->visitedPage;
            $nestedData['created_at'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('d/m/Y H:i:s');
            $view_button = $row->code;
            $nestedData['action'] = array('v' => $view_button);
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
    public function logview(Request $request){
        //  dd($request->all());
        $view = tbl_user_log_details::leftjoin('tbl_user','tbl_user.code','tbl_user_log_details.userCode')
        ->select('tbl_user_log_details.*','tbl_user.name','tbl_user.mobile_no','tbl_user.designation','tbl_user.user_type',DB::raw('DATE_FORMAT(tbl_user_log_details.created_at, "%d/%m/%Y") as created'),DB::raw('DATE_FORMAT(tbl_user_log_details.created_at, "%d/%m/%Y") as updated'))
        ->where('tbl_user_log_details.code',$request->code)
        ->first();

        $details=array('options'=>$view);
        return response()->json($details); 
     
    
    
    
    
    }
}

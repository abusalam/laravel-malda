<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tbl_case_details;
use DB;
class SDOCourtController extends Controller
{
    public function case_entry(){
    	return view("Sdo_court_entry");
    }

    public function save_case(Request $request){

    	$statuscode = 200;
        if (!$request->ajax()) {
            $statuscode = 400;
            $response = array('error' => 'Error occer in ajax call');
            return response()->json($response, $statuscode);
        }

         $edit_code=$request->edit_code;

         if($request->edit_code==''){
            $this->validate($request, [
                'case_number' => "required|regex:/^[A-Za-z0-9.-_\s]+$/i|min:1|max:40",
                'nxt_hearing_date' => "required|date_format:d/m/Y",
                'description' => "required|regex:/^[A-Za-z0-9.-_\s]+$/i|min:1|max:100",
                
                    ], [
                'case_number.required' => 'Case Number is Required',
                'case_number.regex' => 'Only Alphanumeric Space and (./-_) allowed here',
                'case_number.min' => 'Case Number Must be between 1 to 40 Character',
                'case_number.max' => 'Case Number Must be between 1 to 40 Character',

                'nxt_hearing_date.required' => 'Next hearing Date is Required',
                'nxt_hearing_date.date_format' => 'Next Hearing Date Should be DD/MM/YYYY Format',
                
                'description.required' => 'Description is required',
                'description.regex' => 'Only Alphanumeric Space and (./-_) allowed in Description',
                'description.min' => 'Description Must be between 1 to 100 Character',
                'description.max' => 'Description Must be between 1 to 100 Character',
                
                
            ]);
       }else{

       $this->validate($request, [
                'case_number' => "required|regex:/^[A-Za-z0-9.-_\s]+$/i|min:1|max:40",
                'nxt_hearing_date' => "required|date_format:d/m/Y",
                'description' => "required|regex:/^[A-Za-z0-9.-_\s]+$/i|min:1|max:100",
                
                    ], [
                'case_number.required' => 'Case Number is Required',
                'case_number.regex' => 'Only Alphanumeric Space and (./-_) allowed here',
                'case_number.min' => 'Case Number Must be between 1 to 40 Character',
                'case_number.max' => 'Case Number Must be between 1 to 40 Character',

                'nxt_hearing_date.required' => 'Next hearing Date is Required',
                'nxt_hearing_date.date_format' => 'Next Hearing Date Should be DD/MM/YYYY Format',
                
                'description.required' => 'Description is required',
                'description.regex' => 'Only Alphanumeric Space and (./-_) allowed in Description',
                'description.min' => 'Description Must be between 1 to 100 Character',
                'description.max' => 'Description Must be between 1 to 100 Character',
                
                
            ]);
    }
        try {

            $case_number=$request->case_number;
            $nxt_hearing_date=$request->nxt_hearing_date;
            $description=$request->description;
           

            $date = str_replace('/', '-', $nxt_hearing_date );
           $newDate = date("Y-m-d", strtotime($date));



           
        
            
            if($edit_code ==''){
            $tbl_case_details = new tbl_case_details();

            $tbl_case_details->case_no = $case_number;
            $tbl_case_details->nxt_hearing_date = $newDate; 
            $tbl_case_details->description = $description;           
            $tbl_case_details->save();
             $response = array(
                'status' => 1
            );
        }else{

            $save = tbl_case_details::where('code','=',$edit_code)->update(['case_no' => $case_number, 'nxt_hearing_date' => $newDate,'description'=> $description]);

                $response = array(
                    'status' => 2
                );

        }


           
        } catch (\Exception $e) {

            $response = array(
                'exception' => true,
                
            );
            $statuscode = 400;
        } finally {
             $res= response()->json($response, $statuscode);
        }
        return $res; 

    }

    public function case_list(){
        return view("sdo_court_list");
    }

    public function caselist_datatable(Request $request){
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
        $record = tbl_case_details::select('*',DB::raw('DATE_FORMAT(nxt_hearing_date, "%d/%m/%Y") as nxt_hearing_date '))
                ->orderby('code', 'desc')
        ->where(function($q) use ($search) {
        $q->orwhere('description', 'like', '%' . $search . '%');
        $q->orwhere('nxt_hearing_date', 'like', '%' . $search . '%');
        $q->orwhere('case_no', 'like', '%' . $search . '%');
        });

         if ($case_data!= '') {
                    $record = $record->where('case_no', '=', $case_data);
                  }

        $filtered_count = $record->count();
        $page_displayed = $record->offset($offset)->limit($length)->get();
        $count = $offset + 1;
        foreach ($page_displayed as $row) {
            $nestedData['id'] = $count;
            $nestedData['code'] = $row->code;
            $nestedData['description'] = $row->description;
            $nestedData['case_no'] = $row->case_no;
            $nestedData['nxt_hearing_date'] = $row->nxt_hearing_date;

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

    public function case_edit(Request $request){

    	  $statusCode = 200;
        $response = array();

        $this->validate($request, [
            'case_code' => 'required|integer',
                ], [
            'case_code.required' => 'Case Code is required',
            'case_code.integer' => 'Case Code Accepted Only Integer',
        ]);


        try {
            $case_code = $request->case_code;
            $result = tbl_case_details::where('code', '=', $case_code)
                    ->select('*',DB::raw('DATE_FORMAT(nxt_hearing_date, "%d/%m/%Y") as nxt_hearing_date '))
                    ->first();
             
        } catch (\Exception $e) {
            $response = array(
                'exception' => true,
                
            );
            $statusCode = 400;
        } finally {

             $res= view('Sdo_court_entry')->with('case_details', $result);
        }
        return $res; 


    }

    public function case_delete(Request $request){


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
            'case_code' => 'required|integer',
                ], [
            'case_code.required' => 'Case Code is required',
            'case_code.integer' => 'Case Code Accepted Only Integer',
        ]);


        try {

            $DELETE = tbl_case_details::where('code', '=', $request->case_code)->delete(); //Should be changed #27


            $response = array(
                'status' => 1 //Should be changed #32
            );
        } catch (\Exception $e) {
            $response = array(
                'exception' => true,
               
            );
            $statusCode = 400;
        } finally {
             $res= response()->json($response, $statusCode);
        }
        return $res;

    }

    public function search_case(){

        return view("search_case");
    }

    public function refreshCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }

    public function capchavalidation(Request $request){


       $statusCode = 200;
        $response = [
            'options' => [] //Should be changed #9
        ];
        if (!$request->ajax()) {
            $statusCode = 400;
            $response = array('error' => 'Error occured in form submit.');
            return response()->json($response, $statusCode);
        }
           if(config('app.captcha')==0){  
        $this->validate($request, [
            'case_number' => 'required',
             'capcha' => 'required|captcha',

                ], [
            'case_number.required' => 'Case Number is required',
            'capcha.required' => 'Captcha is required',
            'capcha.captcha' => 'Captcha Missmatch',
        ]);
    }else{
         $this->validate($request, [
            'case_number' => 'required',             
                ], [
            'case_number.required' => 'Case Number is required',
            
        ]);

    }


        try {

            $result=tbl_case_details::where('case_no',$request->case_number)->select('*',DB::raw('DATE_FORMAT(nxt_hearing_date, "%d/%m/%Y") as nxt_hearing_date '))->get();
            if($result->count()>0){

            $response = array(
                'options' => $result,'status'=>1
            );
        }else{

            $response = array(
                'status' => 2
            );

        }


           
        } catch (\Exception $e) {
            $response = array(
                'exception' => true,
                
            );
            $statusCode = 400;
        } finally {

             $res=response()->json($response, $statusCode);
        }
        return $res; 

    }

    public function todays_hearing(){

        return view("todays_hearing");

    }

    public function caselist_datatable_for_todays_hearing(Request $request){

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

         $date=Date("Y-m-d");
         

        $data = array();
        $record = tbl_case_details::where('nxt_hearing_date',$date)->select('*',DB::raw('DATE_FORMAT(nxt_hearing_date, "%d/%m/%Y") as nxt_hearing_date '))
                ->orderby('code', 'desc')
        ->where(function($q) use ($search) {
        $q->orwhere('description', 'like', '%' . $search . '%');
        $q->orwhere('nxt_hearing_date', 'like', '%' . $search . '%');
        $q->orwhere('case_no', 'like', '%' . $search . '%');
        });

        
        $filtered_count = $record->count();
        $page_displayed = $record->offset($offset)->limit($length)->get();
        $count = $offset + 1;
        foreach ($page_displayed as $row) {
            $nestedData['id'] = $count;
            $nestedData['code'] = $row->code;
            $nestedData['description'] = $row->description;
            $nestedData['case_no'] = $row->case_no;
            $nestedData['nxt_hearing_date'] = $row->nxt_hearing_date;

            
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



   


}

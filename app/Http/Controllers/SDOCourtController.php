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
                'exception_message' => $e->getMessage(),
            );
            $statuscode = 400;
        } finally {
            return response()->json($response, $statuscode);
        }

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
            'search.*' => 'nullable|regex:/^[A-Za-z\s]+$/i',
                ], [
            
            'search.*.regex' => 'Search value accept only Alphabatic character',
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
                'exception_message' => $e->getMessage(),
            );
            $statusCode = 400;
        } finally {

            return view('Sdo_court_entry')->with('case_details', $result);
        }


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
                'exception_message' => $e->getMessage(),
            );
            $statusCode = 400;
        } finally {
            return response()->json($response, $statusCode);
        }

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
           if(env("CAPTCHA")==1){  
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
            'search.*' => 'nullable|regex:/^[A-Za-z\s]+$/i',
                ], [
            
            'search.*.regex' => 'Search value accept only Alphabatic character',
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

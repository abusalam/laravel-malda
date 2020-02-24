<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tbl_grievance;
use DB;

class ReportController extends Controller
{

	// SELECT tbl_grievence_forwored.status,tbl_grivense.close_status,COUNT(tbl_grievence_forwored.status) FROM  `tbl_grivense` inner join `tbl_grievence_forwored` on `tbl_grievence_forwored`.`griv_code` = `tbl_grivense`.`code` WHERE tbl_grievence_forwored.to_forword=2 GROUP BY tbl_grievence_forwored.status,tbl_grivense.close_status

    public static function grievance_report(){
    	
    	$pending_griv=0;
    	$close_griv=0;
    	$froward_griv=0;
    	$resolve_griv=0;
    	if(session()->get('user_type')==0){

    		$grievance_report=tbl_grievance::get();
    		$total_griv=$grievance_report->count();
    		foreach($grievance_report as $griv){
    			if($griv->close_status==0){
    				$pending_griv++;
    			}else if($griv->close_status==1){
    				$close_griv++;
    			}else if($griv->close_status==2){
    				$resolve_griv++;
    			}else if($griv->close_status==3){
    				$froward_griv++;
    			}


    		}

    		
    	}else{

    		

    		
$grievance_report=tbl_grievance::join('tbl_grievence_forwored','tbl_grievence_forwored.griv_code','tbl_grivense.code')->where('tbl_grievence_forwored.to_forword',session()->get('user_code'))->select('tbl_grievence_forwored.status','tbl_grivense.close_status',DB::raw('COUNT(tbl_grievence_forwored.status) as griv_status'))->groupby('tbl_grievence_forwored.status','tbl_grivense.close_status')->get();
    	
    		
    		foreach ($grievance_report as $report) {
    			
    			$status = $report->status;
    			$close_status= $report->close_status;
    			$griv_status = $report->griv_status;

    			if($status == 0 && $close_status == 3){
    				$pending_griv = $griv_status;
    			}

    			if($status == 0 && $close_status == 1){
    				$close_griv = $griv_status;
    			}

    			if($status == 0 && $close_status == 2){
    				$resolve_griv = $griv_status;
    			}

    			if($status == 1){
    				$froward_griv+=$griv_status;
    			}

    		}

			$total_griv = $froward_griv+$resolve_griv+$close_griv+$pending_griv;


    	


    	}

    	

    	 $report=array('total_griv'=>$total_griv,'pending_griv'=>$pending_griv,'close_griv'=>$close_griv,'forward_griv'=>$froward_griv,'resolve_griv'=>$resolve_griv);

    		return $report;


    }

    public function pending_report(){

    	return view("pending_report");

    }

    public function pending_grievance_datatable(Request $request){

		$this->validate($request, [
            'draw'=>'required|integer|between:0,9999999999',
            'start'=>'required|integer|between:0,999999999',
            'length'=>'required|integer|between:0,100',
            'order' => 'array',
            'search.*' => 'nullable|regex:/^[A-Za-z0-9\s]+$/i',
            'order.*.column' => 'required|integer|between:0,6',
            'order.*.dir' => 'required|in:asc,desc'
            ], [
            'draw.required' => 'Invalid Input',
            'draw.between' => 'Invalid Input',
            'draw.integer' => 'Invalid Input',

            'start.required' => 'Invalid Input', 
            'start.between' => 'Invalid Input',
            'start.integer' => 'Invalid Input',

            'length.required' => 'Invalid Input', 
            'length.between' => 'Invalid Input', 
            'length.integer' => 'Invalid Input',

            'order.*.column.required' => 'Invalid Input',
            'order.*.column.integer' => 'Invalid Input',
            'order.*.column.between' => 'Invalid Input',

            'order.array' => 'Invalid Input',

            'order.*.dir.required' => 'Invalid Input',
            'order.*.dir.in' => 'Invalid Input',

            'search.*.regex' => 'Invalid Input',
        ]);

        $draw = $request->draw;
        $offset = $request->start;
        $length = $request->length;
        $search=  isset($request->search["value"]) ? $request->search["value"] :'';
        $order = $request->order;
		$data = array();

		

			$record=tbl_grievance::join('tbl_grievence_forwored','tbl_grievence_forwored.griv_code','tbl_grivense.code')->join('tbl_user','tbl_user.code','tbl_grievence_forwored.to_forword')->where('tbl_grievence_forwored.status',0)->where('tbl_grivense.close_status',3)->groupby('tbl_user.name','tbl_user.code','tbl_user.designation')->select('tbl_user.code','tbl_user.name','tbl_user.designation',DB::raw('COUNT(tbl_grievence_forwored.griv_code) as griv_code'))


			->where(function($q) use ($search) {
			$q->orwhere('tbl_user.name', 'like', '%' . $search . '%');
            $q->orwhere('tbl_user.designation', 'like', '%' . $search . '%');
			
			
			
		});

		
		

		
       $all_record = $record;
		$filtered_count = $record->count();


		


		$page_displayed = $all_record->offset($offset)->limit($length)->get();
		$count = $offset + 1;
		foreach ($page_displayed as $row) {
			$nestedData['id'] = $count;
			$nestedData['code'] = $row->code;
			$nestedData['name'] = $row->name;
            $nestedData['designation'] = $row->designation;
			$nestedData['griv_code'] = $row->griv_code;
			
			

			$view_button = $row->code;
			$nestedData['action'] = array('v' => $view_button);
			$count++;
			$data[] = $nestedData;
		}
		//print_r($data);die;
		  $response = array(
			"draw" => $draw,
			"recordsTotal" => $filtered_count,
			"recordsFiltered" => $filtered_count,
			'record_details' => $data
		);

		  return response()->json($response);


    }

    public function show_pending_grievance(Request $request){

    	$statusCode = 200;
		if (!$request->ajax()) {

			$statusCode = 400;
			$response = array('error' => 'Error occered in Json call.');
			return response()->json($response, $statusCode);
		}
		$this->validate($request, [
			'user_code' => "required|integer",
						], [
			'user_code.required' => 'User Code is Required',
			'user_code.integer' => 'User Code Should be Integer',		
			
		]);
		
		try {

			$record=tbl_grievance::join('tbl_grievence_forwored','tbl_grievence_forwored.griv_code','tbl_grivense.code')->join('tbl_user','tbl_user.code','tbl_grievence_forwored.to_forword')->where('tbl_grievence_forwored.status',0)->where('tbl_grivense.close_status',3)->where('tbl_user.code',$request->user_code)->select('tbl_grivense.name','tbl_grivense.complain','tbl_grivense.code')->get();

			$response = array(
			"record" => $record,
			
		);




			
		}
		catch (\Exception $e) {

			$response = array(
				'exception' => true,
				
			);
			$statusCode = 400;
		} finally {
			 $res= response()->json($response, $statusCode);
		}
        return $res;



    }


    

}

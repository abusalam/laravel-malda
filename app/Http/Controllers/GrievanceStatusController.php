<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tbl_grievance;
use App\tbl_grievence_forwored;


class GrievanceStatusController extends Controller {

	public function grievance_status() {
		return view("grievance_status");
	}

	public function grievance_statuss(Request $request) {


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
			'grievance_id' => 'required',
			'mobileNo' => 'required',
			//'capcha' => 'required|captcha',
			], [
			'grievance_id.required' => 'Grievance ID is required',
			'capcha.required' => 'Captcha is required',
			'capcha.captcha' => 'Captcha Missmatch',
		]);



		try {

			$checkMobile = tbl_grievance::where('mobile_no', '=', $request->mobileNo)->where('code', '=', $request->grievance_id)->first();

			if ($checkMobile != null) {
				
				$gData = tbl_grievance::where('code', $request->grievance_id)->select('*')->first();
				$created_at = \Carbon\Carbon::parse($gData->created_at)->format('d/m/Y'); 
			
				$grievanceData = tbl_grievence_forwored::join('tbl_user','tbl_user.code','tbl_grievence_forwored.from_forword')
				->select('remark','tbl_user.name','tbl_grievence_forwored.created_at','tbl_user.designation')
				->where('griv_code', $request->grievance_id)
				->get();
			//print_r($grievanceData[0]->created_at);die;
			$remarkData = array();
				foreach ($grievanceData as $grievance){
					
					$data['name'] = $grievance->name;
					$data['designation'] = $grievance->designation;
					$data['date'] = \Carbon\Carbon::parse($grievance->created_at)->format('d/m/Y');
					
					$remarkData[] = $data;
				}
				
				$response = array(
					'gData' => $gData,
					'remarkData' => $remarkData,
					'created_at'=>$created_at,
				);
			}
			else {
				$response = array(
					'flag' => 1
				);
			}

			$result = tbl_grievance::where('code', $request->code)->select('*')->get();


		}
		catch (\Exception $e) {
			$response = array(
				'exception' => true,
				'exception_message' => $e->getMessage(),
			);
			$statusCode = 400;
		} finally {
			return response()->json($response, $statusCode);
		}
	}

	public function resolve_grievance_list(){

		return view("resolve_grievance_list");

	}

	public function resolve_grievance_datatable(Request $request){


		$draw = $request->draw;
		$offset = $request->start;
		$length = $request->length;
		$search = $request->search ["value"];
		$order = $request->order;

		$this->validate($request, [
			'search.*' => 'nullable|regex:/^[A-Za-z0-9\s]+$/i',
			], [
			'search.*.regex' => 'Search value accept only Alphanumeric character',
		]);

		$data = array();

		 if(session()->get('user_type')==0){

			$record=tbl_grievance::where('close_status',2)->select('name','mobile_no','email','complain','code','created_at')->orderby('code', 'desc')
			->where(function($q) use ($search) {
			$q->orwhere('name', 'like', '%' . $search . '%');
			$q->orwhere('mobile_no', 'like', '%' . $search . '%');
			$q->orwhere('code', 'like', '%' . $search . '%');
			
			
		});

		}else{

			$record = tbl_grievance::join('tbl_grievence_forwored', 'tbl_grievence_forwored.griv_code', 'tbl_grivense.code')
			
			->where('tbl_grievence_forwored.from_forword','=', session()->get('user_code'))->where('tbl_grivense.close_status',2)->wherenull('tbl_grievence_forwored.to_forword')
			//->wherenotnull('tbl_grievence_forwored.griv_code')
			->select('tbl_grivense.name', 'tbl_grivense.mobile_no', 'tbl_grivense.email', 'tbl_grivense.complain', 'tbl_grivense.code','tbl_grivense.created_at','tbl_grivense.updated_at')
			->orderby('code', 'desc')
			->where(function($q) use ($search) {
			$q->orwhere('tbl_grivense.name', 'like', '%' . $search . '%');
			$q->orwhere('tbl_grivense.mobile_no', 'like', '%' . $search . '%');
			$q->orwhere('tbl_grivense.code', 'like', '%' . $search . '%');
			
			
		});

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

}

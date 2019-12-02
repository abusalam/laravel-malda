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
			
				$grievanceData = tbl_grievence_forwored::join('tbl_user','tbl_user.code','tbl_grievence_forwored.from_forword')
				->select('remark','tbl_user.name','tbl_grievence_forwored.created_at')
				->where('griv_code', $request->grievance_id)
				->get();
			//print_r($grievanceData[0]->created_at);die;
			$remarkData = array();
				foreach ($grievanceData as $grievance){
					$data['remark']= $grievance->remark;
					$data['name'] = $grievance->name;
					$data['date'] = \Carbon\Carbon::parse($grievance->created_at)->format('d/m/Y');
					
					$remarkData[] = $data;
				}
				
				$response = array(
					'gData' => $gData,
					'remarkData' => $remarkData,
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

}

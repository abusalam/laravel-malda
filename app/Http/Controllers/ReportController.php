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


    

}

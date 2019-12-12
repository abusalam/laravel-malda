@extends('layout.frontmaster')
@section('content')

 <?php

  use App\Http\Controllers\ReportController;

  $grievance_report = ReportController::grievance_report();
 
 
 ?>
 <style type="text/css">

 .box-element{

 }

 .block{
  background: #5391af;
  min-height: 100px;
  color: white;
  padding: 10px;
  border-radius: 5px;
  border: 1px solid black;
 }


 </style>
     

<div class="row" id="row-content">
    <div class="col-12">                        
        <div class="card">

          <div class="card-body" style="min-height: 200px;"> 
  
       <div class="row">
          <div class="col-md-1"></div>
          <div class="col-md-2 text-center box-element">
            
                 <div class="block">
                   <div class="ssb-title">{{__('text.total_grievances')}}</div>
                 <h2 class="ssb-icon">{{$grievance_report['total_griv']}}</h2>  
                 </div>
               
          </div>
          <div class="col-md-2 text-center box-element">
              <div class="block">
                 <div class="ssb-title">{{__('text.pending_grievances')}}</div>
                 <h2 class="ssb-icon">{{$grievance_report['pending_griv']}}</h2>  
               </div>
          </div>
          <div class="col-md-2 text-center box-element">
             <div class="block">
                 <div class="ssb-title">{{__('text.forwarded_grievances')}}</div>
                 <h2 class="ssb-icon">{{$grievance_report['forward_griv']}}</h2>  
              </div>
          </div>
      
          <div class="col-md-2 text-center box-element">
              <div class="block">
                 <div class="ssb-title">{{__('text.resolved_grievances')}}</div>
                 <h2 class="ssb-icon">{{$grievance_report['resolve_griv']}}</h2>  
               </div>
          </div>
          <div class="col-md-2 text-center box-element">
              <div class="block">
                 <div class="ssb-title">{{__('text.closed_grievances')}}</div>
                 <h2 class="ssb-icon">{{$grievance_report['close_griv']}}</h2>  
              </div>
          </div>
          <div class="col-md-1"></div>
  
        </div>
      </div>
            {{-- <div class="card-body" style="min-height: 400px;">
                <h3 class="card-title">Welcome To Admin Panel</h3>
                <div class="text-center">
                    <span style="font-size: 50px">Malda District Admin Portal</span>
                </div>
                   <table class="brik">
                <tbody>
                    <tr><td width="60%">Total Grievance</td><td width="40%">{{$grievance_report['total_griv']}}</td></tr>
                    <tr><td >Pending Grievance</td><td>{{$grievance_report['pending_griv']}}</td></tr>
                    <tr><td>Total Fowrarded Grievance</td><td>{{$grievance_report['forward_griv']}}</td></tr>
                    <tr><td>Total Resolved Grievance</td><td>{{$grievance_report['resolve_griv']}}</td></tr>
                    <tr><td>Total Closed Grievance</td><td>{{$grievance_report['close_griv']}}</td></tr>

                </tbody>
            </table>
                    
            </div> --}}
            

        </div>
    </div>
</div>
@endsection
@section('script')

@endsection 



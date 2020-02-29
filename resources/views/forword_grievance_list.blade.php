@extends('layout.master')
@section('content')
<div class="row" id="row-content">
    <div class="col-12">                        
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">{{__('text.forwarded_table_heading')}}</h3>

                {{Form::open(['name'=>'to_form_search','id'=>'case_search','url' => '', 'method' => 'post'])}}
                <div class="form-group row ">
                    
                    <div class="col-sm-2 mg-t-10 text-sm-right text-left">{{Form::label('case_number', 'From Date', ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}</div>
                    <div class="col-sm-3 mg-t-10">
                        {{Form::text('from_date', '', ['id'=>'from_date','placeholder'=>'Enter From Date','autocomplete'=>'off', 'class' => 'form-control']) }}
                    </div>
                     <div class="col-sm-2 mg-t-10 text-sm-right text-left">{{Form::label('case_number', 'To Date', ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}</div>
                    <div class="col-sm-3 mg-t-10">
                        {{Form::text('to_date', '', ['id'=>'to_date','placeholder'=>'Enter To Date','autocomplete'=>'off', 'class' => 'form-control']) }}
                    </div>
                    <div class="col-sm-2 mg-t-10">
                        {{Form::button( 'Search', ['type'=>'button','id'=>'Search','class' => 'btn btn-primary btn-block']) }}
                    </div>
                   
                </div>
                {!! Form::close() !!}
                {{csrf_field()}}
                <div class="datatbl  " style="width: 96%;margin-left: 20px;">
                    <table class="table table-striped table-bordered table-hover notice-types-table" id="tbl_grievance_list" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 10%;"> ID</th>
                                <th style="width: 20%;">Grievance Date</th>
                                <th style="width: 20%;">Name</th>              
                                <th style="width: 20%;">Mobile No</th>                                                    
                                <th style="width: 20%;">Forwarded To</th>
                                <th style="width: 5%;">Action</th>
                                
                                

                           

                            </tr>

                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('/app/js/forward_grievance_list.js')}}"></script>

@endsection
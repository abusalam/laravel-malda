@extends('layout.master')
@section('content')


<div class="row" id="row-content">
    <div class="col-12">                        
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">{{__('text.case_list')}}</h3>
                {{Form::open(['name'=>'case_search','id'=>'case_search','url' => '', 'method' => 'post'])}}
                <div class="form-group row ">
                    <div class="col-sm-1">&nbsp;</div>
                    <div class="col-sm-2 mg-t-10">{{Form::label('case_number', __('text.case_number'), ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}</div>
                    <div class="col-sm-4">
                        {{Form::text('case_number', '', ['id'=>'case_number','placeholder'=>__('text.enter_case_number'),'autocomplete'=>'off', 'class' => 'form-control']) }}
                    </div>
                    <div class="col-sm-3">
                        {{Form::button( __('text.search'), ['type'=>'button','id'=>'Search','class' => 'btn btn-primary btn-block']) }}
                    </div>
                    <div class="col-sm-2">&nbsp;</div>
                </div>
                {!! Form::close() !!}

                <div class="datatbl  " style="width: 96%;margin-left: 20px;">
                    <table class="table table-striped table-bordered table-hover notice-types-table" id="tbl_case_list" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 15%;">Case Number</th>              
                                <th style="width: 20%;">Next Hearing Date</th> 
                                <th style="width: 45%;">Description</th>   
                                <th style="width: 15%;">Action</th>
                            </tr>

                        </thead>
                        <tbody></tbody>
                        <!-- Table Footer -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




@endsection

@section('script')

<script src="{{asset('/app/js/sdo_court_list.js')}}"></script>



@endsection 
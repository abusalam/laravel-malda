@extends('layout.master')
@section('content')
<div class="row" id="row-content">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title text-center"><h3>{{__('text.case_entry')}}</h3></div>
                <div class="col-sm-2">&nbsp;</div>
                <div class="col-sm-8 mt-5">
                    {{Form::open(['name'=>'sdocourt_entry','id'=>'sdocourt_entry','url' => '', 'method' => 'post'])}}
                    {!! Form::hidden('edit_code',isset($case_details)?$case_details->code:'',['id'=>'edit_code']) !!}
                    <div class="form-group row ">
                        <div class="col-sm-4 mg-t-10">{{Form::label('case_number', __('text.case_number'), ['class' => 'form-label mg-b-0 required']) }}</div>
                        <div class="col-sm-8">
                            {{Form::text('case_number', isset($case_details)?$case_details->case_no:'', ['id'=>'case_number','placeholder'=>__('text.enter_case_number'),'autocomplete'=>'off', 'class' => 'form-control','maxlength'=>'40']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4 mg-t-10">{{Form::label('nxt_hearing_date', __('text.next_hearing_date'), ['class' => 'form-label mg-b-0 required']) }}</div>
                        <div class="col-sm-8">
                            {{Form::text('nxt_hearing_date', isset($case_details)?$case_details->nxt_hearing_date:'', ['id'=>'nxt_hearing_date','placeholder'=>__('text.enter_next_hearing_date'),'autocomplete'=>'off','class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4 mg-t-10">{{Form::label('description',__('text.description'), ['class' => 'form-label mg-b-0 required']) }}</div>
                        <div class="col-sm-8">
                            {{Form::textarea('description', isset($case_details)?$case_details->description:'', ['id'=>'description','placeholder'=>__('text.enter_description'),'autocomplete'=>'off','class' => 'form-control','rows'=>'5','maxlength'=>'100']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4 mg-t-10">&nbsp;</div>
                        <div class="col-sm-8">
                            {{Form::submit( __('text.save'), ['id'=>'save','class' => 'btn btn-primary btn-block']) }}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="col-sm-2">&nbsp;</div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('/app/js/sdo_court_entry.js')}}"></script>
@endsection

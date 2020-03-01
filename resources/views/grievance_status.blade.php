@extends('layout.frontmaster')
@section('content')
<div class="row" id="row-content">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">{{__('text.grievance_status')}}</h3>
                <div id="search_data">
                    <div class="alert error"></div>
                    {{Form::open(['name'=>'grievance_status','id'=>'grievance_status','url' => '', 'method' => 'post'])}}
                    <div class="form-group row">
                        <div class="col-sm-2">&nbsp;</div>
                        <div class="col-sm-2 mg-t-10">{{Form::label('grievance_id', __('text.grievance_id'), ['class' => 'form-label mg-b-0 required']) }}</div>
                        <div class="col-sm-4">
                            {{Form::text('grievance_id', '', ['id'=>'grievance_id','placeholder'=>__('text.enter_grievance_id'),'autocomplete'=>'off', 'class' => 'form-control']) }}
                        </div>
                        <div class="col-sm-1">&nbsp;</div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2">&nbsp;</div>
                        <div class="col-sm-2 mg-t-10">{{Form::label('mobileNo', __('text.mobile_no'), ['class' => 'form-label mg-b-0 required']) }}</div>
                        <div class="col-sm-4">
                            {{Form::text('mobileNo', '', ['id'=>'mobileNo','placeholder'=>__('text.enter_mobile_number'),'autocomplete'=>'off', 'class' => 'form-control','maxLength'=>10, 'onkeypress'=>'return isNumberKey(event)']) }}
                        </div>
                        <div class="col-sm-1">&nbsp;</div>
                    </div>
                    <?php if(config('app.captcha')==0){  ?>
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="form-group col-md-7">
                            <div class="captcha">
                                <span>{!! captcha_img() !!}</span>
                                <button type="button" class="btn btn-success"  id="refresh"><i class="fa fa-refresh"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="form-group col-md-4">
                            <input id="captcha" type="text" class="form-control" placeholder="{{__('text.enter_captcha')}}" name="captcha"></div>
                            <div class="col-md-4"></div>
                    </div>
                    <?php } ?>

                        <div class="row form-group">
                            <div class="col-sm-4">
                                &nbsp;
                            </div>
                            <div class="col-sm-4">
                                {{Form::button( __('text.search'), ['type'=>'button','id'=>'Search','class' => 'btn btn-primary btn-block']) }}
                            </div>
                        </div>

                    {!! Form::close() !!}
                </div>
                <div id="tbl_t">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('/app/js/grievance_status.js')}}"></script>
@endsection

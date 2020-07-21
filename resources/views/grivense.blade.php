@extends('layout.frontmaster')
@section('content')
<div class="row" id="row-content">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">{{__('text.grievance')}}</h3>
                <div class="row">
                    <div class="col-sm-4  offset-sm-4">
                        <div class="alert alert-danger" id="error"></div>
                    </div>
                </div>

                {{Form::open(['name'=>'grivense_form','id'=>'grivense_form','url' => '', 'method' => 'post'])}}
                <div class="form-group row">
                    <div class="col-sm-2">&nbsp;</div>
                    <div class="col-sm-2 mg-t-10">
                        {{Form::label('grivense_name', __('text.name'), ['class' => 'form-label mg-b-0 required']) }}
                    </div>
                    <div class="col-sm-4">
                        {{Form::text('grivense_name', '', ['id'=>'grivense_name','placeholder'=>__('text.enter_name'),'autocomplete'=>'off', 'class' => 'form-control', 'maxlength'=>'30']) }}
                    </div>
                    <div class="col-sm-1">&nbsp;</div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">&nbsp;</div>
                    <div class="col-sm-2 mg-t-10">
                        {{Form::label('mobile_no', __('text.mobile_no'), ['class' => 'form-label mg-b-0 required']) }}
                    </div>
                    <div class="col-sm-4">
                        {{Form::text('mobile_no', '', ['id'=>'mobile_no','placeholder'=>__('text.enter_mobile_number'),'autocomplete'=>'off', 'class' => 'form-control', 'maxlength'=>'10']) }}
                    </div>
                    <div class="col-sm-1">&nbsp;</div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">&nbsp;</div>
                    <div class="col-sm-2 mg-t-10">
                        {{Form::label('grivense_email', __('text.email'), ['class' => 'form-label mg-b-0 required']) }}
                    </div>
                    <div class="col-sm-4">
                        {{Form::text('grivense_email', '', ['id'=>'grivense_email','placeholder'=>__('text.enter_email'),'autocomplete'=>'off', 'class' => 'form-control', 'maxlength'=>'30']) }}
                    </div>
                    <div class="col-sm-1">&nbsp;</div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">&nbsp;</div>
                    <div class="col-sm-2 mg-t-10">
                        {{Form::label('grivense_complain', __('text.complain'), ['class' => 'form-label mg-b-0 required']) }}
                    </div>
                    <div class="col-sm-4">
                        {{Form::textarea('grivense_complain', '', ['id'=>'grivense_complain','rows'=>"4", 'cols'=>"50",'autocomplete'=>'off', 'class' => 'form-control', 'maxlength'=>'300','placeholder'=>__('text.enter_complain')]) }}
                    </div>
                    <div class="col-sm-1">&nbsp;</div>
                </div>

               {{--  <div class="form-group row">
                    <div class="col-sm-2">&nbsp;</div>
                    <div class="col-sm-2 mg-t-10">
                        {!! Form::label('attatchment', __('text.attachment'), ['class'=>'form-label mg-b-0 required']) !!}
                    </div>
                    <div class="col-sm-4">
                        {!! Form::file('attatchment',['id'=>'attatchment','class'=>'form-control form-control-file','autocomplete'=>'off']) !!}
                    </div>
                    <div class="col-sm-1">&nbsp;</div>
                </div> --}}


                <?php if(config('app.captcha')==0) {  ?>

                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="form-group col-md-4">
                        <div class="captcha">
                            <span>{!! captcha_img() !!}</span>
                            <button type="button" class="btn btn-success"><i class="fa fa-refresh" id="refresh"></i></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="form-group col-md-4">
                        <input id="captcha" type="text" class="form-control" placeholder="{{__('text.enter_captcha')}}" name="captcha"></div>
                </div>
                <?php } ?>
                <div class="row">

                    <div class="col-sm-4">&nbsp;</div>
                    <div class="col-sm-4">
                        {{ Form::button(__('text.submit'), ['class' => 'btn btn-primary btn-block', 'type' => 'submit','id'=>'Submit']) }}
                    </div>

                </div>

                <div id="tbl_t"></div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('/app/js/grievance.js')}}"></script>

@endsection

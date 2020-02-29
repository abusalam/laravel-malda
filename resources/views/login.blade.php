@extends('layout.frontmaster')
@section('content')
<div class="row" id="row-content">
    <div class="col-sm-8 offset-sm-2">
        <div class="card">
            <div class="card-body" style="min-height: 250px;">
                <div class="card-title text-center">
                    <h3>{{__('text.login')}}</h3>
                </div>
               
                <div class="col-sm-10 offset-sm-1 mt-5">
                    <div class="alert alert-danger" style="display: none" id="error"></div>
                    {{Form::open(['name'=>'userlogin','id'=>'userlogin','url' => '', 'method' => 'post'])}}
                    <div class="form-group row">
                        <div class="col-sm-3" style="font-weight: bold; font-size: 17px;">
                            {{Form::label('username', __('text.mobile_no'), ['class' => 'form-label mg-b-0 required']) }}
                        </div>
                        <div class="col-sm-9">
                            {{Form::text('username', '', ['id'=>'username','autocomplete'=>'off','placeholder'=>__('text.enter_mobile_number'),'class' => 'form-control','maxlength'=>'10', 'onkeypress'=>'return isNumberKey(event)']) }}
                        </div>
                    </div>
                    <?php if(config('app.captcha')==0){  ?>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="form-group col-md-7">
                            <div class="captcha">
                                <span>{!! captcha_img() !!}</span>
                                <button type="button" class="btn btn-success"  id="refresh"><i class="fa fa-refresh"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="form-group col-md-9">
                            <input id="captcha" type="text" class="form-control" placeholder="{{__('text.enter_captcha')}}" name="captcha"></div>
                    </div>
                    <?php } ?>
                    <div class="form-group row">
                        <div class="col-sm-3">&nbsp;</div>
                        <div class="col-sm-9">
                            {{Form::submit( __('text.sign_in'), ['id'=>'login','class' => 'btn btn-primary btn-block']) }}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('/app/js/login.js')}}"></script>
@endsection

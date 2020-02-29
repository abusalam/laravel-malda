@extends('layout.master')
@section('content')
<div class="row" id="row-content">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title text-center"><h3>{{__('text.user_create')}}</h3></div>
                <div class="row">
                    <div class="col-sm-2 ">&nbsp</div>
                    <div class="col-sm-8 mt-5">
                        {{Form::open(['name'=>'userCreate','id'=>'userCreate','url' => '', 'method' => 'post'])}}
                        {!! Form::hidden('edit_code',isset($user_details)?$user_details->code:'',['id'=>'edit_code']) !!}
                        <div class="form-group row">
                            <div class="col-sm-4 mg-t-10">{{Form::label('name', __('text.name'), ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}</div>
                            <div class="col-sm-8">
                                {{Form::text('name', isset($user_details)?$user_details->name:'', ['id'=>'name','placeholder'=>__('text.enter_name'),'autocomplete'=>'off', 'class' => 'form-control', 'maxlength'=>'30']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 mg-t-10">{{Form::label('mobile_no',__('text.mobile_no'), ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}</div>
                            <div class="col-sm-8">
                                {{Form::text('mobile_no', isset($user_details)?$user_details->mobile_no:'', ['id'=>'mobile_no','placeholder'=>__('text.enter_mobile_number'),'autocomplete'=>'off','class' => 'form-control','pattern'=>'[0-9]*', 'inputmode'=>'numeric','onkeypress'=>'return isNumberKey(event)','maxlength'=>'10']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 mg-t-10">{{Form::label('designation', __('text.designation'), ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}</div>
                            <div class="col-sm-8">
                                {{Form::text('designation', isset($user_details)?$user_details->designation:'', ['id'=>'designation','placeholder'=>__('text.enter_designation'),'autocomplete'=>'off','class' => 'form-control', 'maxlength'=>'30']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 mg-t-10">&nbsp;</div>
                            <div class="col-sm-8">
                                {{Form::submit( __('text.save'), ['id'=>'login','class' => 'btn btn-success btn-block']) }}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="col-sm-2">&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
 <?php if (isset($user_details)) { ?>
 
 <input name="edit_data" type="hidden" value="1" id="edit_data">
 <?php } ?>


@endsection

@section('script')

<script src="{{asset('/app/js/user_create.js')}}"></script>

@endsection 
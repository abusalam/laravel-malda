@extends('layout.frontmaster')
@section('content')


<div class="row" id="row-content">
    <div class="col-12">                        
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">{{__('text.case_search')}}</h3>
                <div id="search_data">
                    {{Form::open(['name'=>'case_search','id'=>'case_search','url' => '', 'method' => 'post'])}}
                    <div class="form-group row">
                        <div class="col-sm-2">&nbsp;</div>
                        <div class="col-sm-2 mg-t-10">{{Form::label('case_number',  __('text.case_number'), ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}</div>
                        <div class="col-sm-4">
                            {{Form::text('case_number', '', ['id'=>'case_number','placeholder'=> __('text.enter_case_number'),'autocomplete'=>'off', 'class' => 'form-control']) }}
                        </div>
                        <div class="col-sm-1">&nbsp;</div>
                    </div>
                    <?php if(config('app.captcha')==0){  ?>
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
                    <?php }?>
                    <div>
                        <div class="row form-group">
                            <div class="col-sm-4">
                                &nbsp;
                            </div>
                            <div class="col-sm-4">
                                {{Form::button(  __('text.search'), ['type'=>'button','id'=>'Search','class' => 'btn btn-primary btn-block']) }}
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div id="tbl_t" style="padding-left: 10px;">
                </div> 
            </div> 
        </div> 
    </div> 
</div> 

@endsection

@section('script')
<script src="{{asset('/app/js/case_search.js')}}"></script>
@endsection
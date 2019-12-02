@extends('layout.frontmaster')
@section('content')


<div class="row" id="row-content">
    <div class="col-12">                        
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Grievance Status</h3>
                <div id="search_data">
									<div class="alert message" style="display: none"></div>
                    {{Form::open(['name'=>'grievance_status','id'=>'grievance_status','url' => '', 'method' => 'post'])}}
                    <div class="form-group row">
                        <div class="col-sm-2">&nbsp;</div>
                        <div class="col-sm-2 mg-t-10">{{Form::label('grievance_id', 'Grievance ID:', ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}</div>
                        <div class="col-sm-4">
                            {{Form::text('grievance_id', '', ['id'=>'grievance_id','placeholder'=>'Enter Grievance ID','autocomplete'=>'off', 'class' => 'form-control']) }}
                        </div>
                        <div class="col-sm-1">&nbsp;</div>
                    </div>
										 <div class="form-group row">
                        <div class="col-sm-2">&nbsp;</div>
                        <div class="col-sm-2 mg-t-10">{{Form::label('mobileNo', 'Mobile No:', ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}</div>
                        <div class="col-sm-4">
                            {{Form::text('mobileNo', '', ['id'=>'mobileNo','placeholder'=>'Enter Registered Mobile No','autocomplete'=>'off', 'class' => 'form-control']) }}
                        </div>
                        <div class="col-sm-1">&nbsp;</div>
                    </div>
                   
<!--                    <div class="row">
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
                            <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha"></div>
                    </div>-->
                   
                    <div>
                        <div class="row form-group">
                            <div class="col-sm-4">
                                &nbsp;
                            </div>
                            <div class="col-sm-4">
                                {{Form::button( 'Search', ['type'=>'button','id'=>'Search','class' => 'btn btn-primary btn-block']) }}
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
<script type="text/javascript">

   
    $('#refresh').click(function () {
        $.ajax({
            type: 'GET',
            url: 'refreshcaptcha',
            dataType: 'json',
            success: function (data) {
                $(".captcha span").html(data.captcha);
                $("#grievance_id").val('');
                $("#captcha").val('');
            }
        });
    });

    $("#Search").click(function () {

        var grievance_id = $("#grievance_id").val();
				var mobileNo = $("#mobileNo").val();
        var capcha = $("#captcha").val();
        $.ajax({
            type: 'POST',
            url: 'grievance_statuss',
            data: {'_token': $('input[name="_token"]').val(), 'grievance_id': grievance_id, 'capcha': capcha,'mobileNo':mobileNo},
            success: function (data) {

              if(data.flag == 1){
								$('.message').html("");
								$('.message').append("Grievance ID and Mobile No Not Found");
								$('.message').removeClass('alert-success');
								$('.message').addClass('alert-danger');
								$('.message').show();
							}else{
								$('.message').hide();
								$('#search_data').hide();
								
								var msg = "";
								var i;
								msg += '<table class="table table-striped table-bordered table-hover">';
								msg += '<tr><th width="20%">Grievance  ID</th><td width="80%">'+data.gData.code+'</td></tr>';
								msg += '<tr><th>Name</th><td>'+data.gData.name+'</td></tr>';
								msg += '<tr><th>Mobile No</th><td>'+data.gData.mobile_no+'</td></tr>';
								msg += '<tr><th>Email</th><td>'+data.gData.email+'</td></tr>';
								msg += '<tr>';
								msg += '<th>Remark</th>';
								msg += '<td>';
								msg += '<table class="table table-striped table-bordered table-hover">';
								msg += '<tr><th>Name</th><th>Date</th><th>Remark</th></tr>';
								for(i=0; i<data.remarkData.length; i++){
													msg += '<tr><td>' + data.remarkData[i].name + '</td>';
													msg += '<td>' + data.remarkData[i].date + '</td>';
													msg += '<td>' + data.remarkData[i].remark + '</td></tr>';
												}
								msg += '</table>';
								msg += '</td>';
								msg += '</tr>';
								if(data.gData.close_status == 1){
									
								msg += '<tr><th>Close Status</th><td>Closed</td></tr>';
									if(data.gData.remark == null){
										msg += '<tr><th>Close Remark</th><td>N/A</td></tr>';
									}else{
										msg += '<tr><th>Close Remark</th><td>'+data.gData.remark+'</td></tr>';
									}
									
								}
								msg += '</table>';
								
								
							
								
								$('#tbl_t').html("");
								$('#tbl_t').append(msg);
							}

									

               




            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(".se-pre-con").fadeOut("slow");
                var msg = "";
                if (jqXHR.status !== 422 && jqXHR.status !== 400) {
                    msg += "<strong>" + jqXHR.status + ": " + errorThrown + "</strong>";
                } else {
                    if (jqXHR.responseJSON.hasOwnProperty('exception')) {
                        msg += "Exception: <strong>" + jqXHR.responseJSON.exception_message + "</strong>";
                    } else {
                        msg += "Error(s):<strong><ul>";
                        $.each(jqXHR.responseJSON['errors'], function (key, value) {
                            msg += "<li>" + value + "</li>";
                        });
                        msg += "</ul></strong>";
                    }
                }
                $.alert({
                    title: 'Error!!',
                    type: 'red',
                    icon: 'fa fa-warning',
                    content: msg,
                });
                //$("#save_app").attr('disabled',false);
            }

        });

    });






</script>  
@endsection
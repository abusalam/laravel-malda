@extends('layout.frontmaster')
@section('content')


<div class="row" id="row-content">
    <div class="col-12">                        
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Grievance Status</h3>
                <div id="search_data">
                    {{Form::open(['name'=>'grievance_status','id'=>'grievance_status','url' => '', 'method' => 'post'])}}
                    <div class="form-group row">
                        <div class="col-sm-2">&nbsp;</div>
                        <div class="col-sm-2 mg-t-10">{{Form::label('grievance_id', 'Grievance ID:', ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}</div>
                        <div class="col-sm-4">
                            {{Form::text('grievance_id', '', ['id'=>'grievance_id','placeholder'=>'Enter Grievance ID','autocomplete'=>'off', 'class' => 'form-control']) }}
                        </div>
                        <div class="col-sm-1">&nbsp;</div>
                    </div>
                    <?php if(env("CAPTCHA")==1){  ?>
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
                            <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha"></div>
                    </div>
                    <?php }?>
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
        var capcha = $("#captcha").val();
        $.ajax({
            type: 'POST',
            url: 'grievance_statuss',
            data: {'_token': $('input[name="_token"]').val(), 'grievance_id': grievance_id, 'capcha': capcha},
            success: function (data) {

                $("#search_data").hide();

                var i = 0;

                var tbl = '<table id="user_details" class="table table-striped table-hover dataTable"  >';
                tbl += '<thead >';
                tbl += '<tr>';
                tbl += '<th style="width: 3%;">SL#</th>';
                tbl += '<th style="width: 15%;">Name</th>';
                tbl += '<th style="width: 15%;">Mobile Number</th>';
                tbl += '<th style="width: 15%;">Email</th>';
                tbl += '<th style="width: 50%;">Complain</th>';

                tbl += '</tr>';
                tbl += '</thead>';

                //tbl+='</table>';



                $.each(data.options, function (key, value) {
                    i = i + 1;
                    tbl += '<tbody>';
                    tbl += '<tr>';
                    tbl += '<td>' + i + '</td>';
                    tbl += '<td>' + value.name + '</td>';
                    tbl += '<td>' + value.mobile_no + '</td>';
                    tbl += '<td>' + value.email + '</td>';
                    tbl += '<td>' + value.complain + '</td>';



                    tbl += '</tr>';
                    tbl += '</tbody>';



                });
                tbl += '</table>';
                //link.addClass("link");

                $("#tbl_t").append(tbl);




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
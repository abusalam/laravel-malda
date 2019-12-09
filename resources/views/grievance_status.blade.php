@extends('layout.frontmaster')
@section('content')
<div class="row" id="row-content">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Grievance Status</h3>
                <div id="search_data">
                    <div class="alert error" style="display: none"></div>
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
                            {{Form::text('mobileNo', '', ['id'=>'mobileNo','placeholder'=>'Enter Registered Mobile No','autocomplete'=>'off', 'class' => 'form-control','maxLength'=>10, 'onkeypress'=>'return isNumberKey(event)']) }}
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
                            <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha"></div>
                            <div class="col-md-4"></div>
                    </div>
                    <?php } ?>
                    
                        <div class="row form-group">
                            <div class="col-sm-4">
                                &nbsp;
                            </div>
                            <div class="col-sm-4">
                                {{Form::button( 'Search', ['type'=>'button','id'=>'Search','class' => 'btn btn-primary btn-block']) }}
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
$('#refresh').click(function() {
    $.ajax({
        type: 'GET',
        url: 'refreshcaptcha',
        dataType: 'json',
        success: function(data) {
            $(".captcha span").html(data.captcha);
            $("#grievance_id").val('');
            $("#captcha").val('');
        }
    });
});

$("#Search").click(function() {

    var grievance_id = $("#grievance_id").val();
    var mobileNo = $("#mobileNo").val();
    var capcha = $("#captcha").val();
    if (grievance_id == '' || mobileNo == '') {
        $.alert({
            title: 'Error!!',
            type: 'red',
            icon: 'fa fa-warning',
            content: "Enter Mobile Number and Grievance ID",
        });


    } else {

        $.ajax({
            type: 'POST',
            url: "{{route('save_otp_for_grievancestatus')}}",
            data: { 'mobile_no': mobileNo, '_token': $("input[name='_token']").val() },
            dataType: "json",
            success: function(data) {
                //alert(data.status);
                if (data.status == 1) {
                    otp_call(mobileNo,grievance_id,capcha);
                } else {
                   
                    $('#error').html('');
                    $('#error').append('Mobile no is already register');
                    $('#error').show();

                }

                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $(".se-pre-con").fadeOut("slow");
                var msg = "";
                if (jqXHR.status !== 422 && jqXHR.status !== 400) {
                    msg += "<strong>" + jqXHR.status + ": " + errorThrown + "</strong>";
                } else {
                    if (jqXHR.responseJSON.hasOwnProperty('exception')) {
                        msg += "Exception: <strong>" + jqXHR.responseJSON.exception_message + "</strong>";
                    } else {
                        msg += "Error(s):<strong><ul>";
                        $.each(jqXHR.responseJSON['errors'], function(key, value) {
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
                $('#error').html('');
                $('#error').append(msg);
                $('#error').show();
            }
        });
    }



});

function otp_call(mobileNo,grievance_id,capcha){

    var jc = $.confirm({
                        title: 'Please enter OTP to continue',
                        content: '<input type="hidden" class="form-control" id="mob_no_new" name="mob_no_new"  autocomplete="off" value="' + mobileNo + '"><br><input type="text" class="form-control" id="otp" name="otp"  autocomplete="off" placeholder="OTP">',
                        type: 'green',
                        typeAnimated: true,
                        buttons: {
                            resend: {
                                btnClass: 'btn-danger',
                                action: function() {
                                    $.ajax({
                                        type: 'POST',
                                        url: "{{route('save_otp_for_grievancestatus')}}",
                                        data: { 'mobile_no': mobileNo, '_token': $("input[name='_token']").val() },
                                        dataType: "json",
                                        success: function(data) {
                                            jc.open(true);
                                        },
                                        error: function(jqXHR, textStatus, errorThrown) {
                                            $(".se-pre-con").fadeOut("slow");
                                            var msg = "";
                                            if (jqXHR.status !== 422 && jqXHR.status !== 400) {
                                                msg += "<strong>" + jqXHR.status + ": " + errorThrown + "</strong>";
                                            } else {
                                                if (jqXHR.responseJSON.hasOwnProperty('exception')) {
                                                    msg += "Exception: <strong>" + jqXHR.responseJSON.exception_message + "</strong>";
                                                } else {
                                                    msg += "Error(s):<strong><ul>";
                                                    $.each(jqXHR.responseJSON['errors'], function(key, value) {
                                                        msg += "<li>" + value + "</li>";
                                                    });
                                                    msg += "</ul></strong>";
                                                }
                                            }
                                            //                                                $.alert({
                                            //                                                    title: 'Error!!',
                                            //                                                    type: 'red',
                                            //                                                    icon: 'fa fa-warning',
                                            //                                                    content: msg,
                                            //                                                });
                                            $('#error').html('');
                                            $('#error').append(msg);
                                            $('#error').show();
                                            // $("#save_app").attr('disabled',false);
                                        }
                                    });
                                }
                            },
                            next: {
                                btnClass: 'btn-primary',
                                action: function() {
                                    jc.showLoading(true);
                                    var mob_no_new = $("#mob_no_new").val();
                                    var otp = $("#otp").val();
                                    if (isNaN(otp)) {
                                        jc.hideLoading(true);
                                        $.alert('Otp must be an integer');
                                        return false;
                                        jc.open(true);
                                    }
                                    if (isNaN(mob_no_new)) {
                                        jc.hideLoading(true);
                                        $.alert('Mobile No must be an integer');
                                        return false;
                                        jc.open(true);
                                    }
                                    return $.ajax({
                                        url: "{{route('check_otp_for_grievancestatus')}}",
                                        dataType: 'json',
                                        data: { 'mob': $("#mob_no_new").val(), 'otp': $("#otp").val(), '_token': $("input[name='_token']").val() },
                                        method: 'post'
                                    }).done(function(response) {
                                        //alert('hi');
                                        jc.hideLoading(true);
                                        if (response.status == 1) {
                                            jc.close(true);

                                            $.ajax({
                                                type: 'POST',
                                                url: 'grievance_statuss',
                                                data: { '_token': $('input[name="_token"]').val(), 'grievance_id': grievance_id, 'capcha': capcha, 'mobileNo': mobileNo },
                                                success: function(data) {

                                                    if (data.flag == 1) {
                                                        $('.error').html("");
                                                        $('.error').append("Grievance ID and Mobile No Not Found");
                                                        $('.error').removeClass('alert-success');
                                                        $('.error').addClass('alert-danger');
                                                        $('.error').show();
                                                    } else {
                                                        $('.error').hide();
                                                        $('#search_data').hide();

                                                        var msg = "";
                                                        var i;
                                                        msg += '<table class="table table-striped table-bordered table-hover">';
                                                        msg += '<tr><th width="20%">Grievance  ID</th><td width="80%">' + data.gData.code + '</td></tr>';
                                                        msg += '<tr><th>Name</th><td>' + data.gData.name + '</td></tr>';
                                                        msg += '<tr><th>Mobile No</th><td>' + data.gData.mobile_no + '</td></tr>';
                                                        msg += '<tr><th>Complain</th><td>' + data.gData.complain + '</td></tr>';
                                                        msg += '<tr><th>Grievance Date</th><td>' + data.created_at + '</td></tr>';
                                                        msg += '<tr>';
                                                        msg += '<th>Forwarded</th>';
                                                        msg += '<td>';
                                                        msg += '<table class="table table-striped table-bordered table-hover">';
                                                        msg += '<tr><th>Name</th><th>Designation</th><th>Date</th></tr>';
                                                        for (i = 0; i < data.remarkData.length; i++) {
                                                            msg += '<tr><td>' + data.remarkData[i].name + '</td>';
                                                            msg += '<td>' + data.remarkData[i].designation + '</td>';
                                                            msg += '<td>' + data.remarkData[i].date + '</td></tr>';

                                                        }
                                                        msg += '</table>';
                                                        msg += '</td>';
                                                        msg += '</tr>';
                                                        if (data.gData.close_status == 1) {

                                                            msg += '<tr><th>Status</th><td class="text-success" style="font-weight:bold;">Closed</td></tr>';
                                                            if (data.gData.remark == null) {
                                                                msg += '<tr><th>Remark</th><td>N/A</td></tr>';
                                                            } else {
                                                                msg += '<tr><th>Remark</th><td>' + data.gData.remark + '</td></tr>';
                                                            }

                                                        } else {

                                                            msg += '<tr><th>Status</th><td class="text-danger" style="font-weight:bold;">Under Process</td></tr>';

                                                        }
                                                        msg += '</table>';




                                                        $('#tbl_t').html("");
                                                        $('#tbl_t').append(msg);
                                                    }








                                                },
                                                error: function(jqXHR, textStatus, errorThrown) {
                                                    $(".se-pre-con").fadeOut("slow");
                                                    var msg = "";
                                                    if (jqXHR.status !== 422 && jqXHR.status !== 400) {
                                                        msg += "<strong>" + jqXHR.status + ": " + errorThrown + "</strong>";
                                                    } else {
                                                        if (jqXHR.responseJSON.hasOwnProperty('exception')) {
                                                            msg += "Exception: <strong>" + jqXHR.responseJSON.exception_message + "</strong>";
                                                        } else {
                                                            msg += "Error(s):<strong><ul>";
                                                            $.each(jqXHR.responseJSON['errors'], function(key, value) {
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
                                        } else {
                                            $.confirm({
                                                title: 'Error!!',
                                                type: 'red',
                                                icon: 'fa fa-warning',
                                                content: "Please Enter Corretct Otp To continue",
                                                buttons: {
                                                    Ok: function() {
                                                        jc.open(true);
                                                    },
                                                }
                                            });
                                        }
                                    }).fail(function(jqXHR, textStatus, errorThrown) {
                                        $(".se-pre-con").fadeOut("slow");
                                        var msg = "";
                                        if (jqXHR.status !== 422 && jqXHR.status !== 400) {
                                            msg += "<strong>" + jqXHR.status + ": " + errorThrown + "</strong>";
                                        } else {
                                            if (jqXHR.responseJSON.hasOwnProperty('exception')) {
                                                msg += "Exception: <strong>" + jqXHR.responseJSON.exception_message + "</strong>";
                                            } else {
                                                msg += "Error(s):<strong><ul>";
                                                $.each(jqXHR.responseJSON, function(key, value) {
                                                    msg += "<li>" + value + "</li>";
                                                });
                                                msg += "</ul></strong>";
                                            }
                                        }
                                        //                                            $.alert({
                                        //                                                title: 'Error!!',
                                        //                                                type: 'red',
                                        //                                                icon: 'fa fa-warning',
                                        //                                                content: msg,
                                        //                                            });
                                        $('#error').html('');
                                        $('#error').append(msg);
                                        $('#error').show();
                                    });
                                }
                            },
                            close: function() {}
                        },
                        onOpen: function() {
                            startTimer(jc);
                        }
                    });
}

function startTimer(jc) {
                    var counter = 30;
                    setInterval(function() {
                        counter--;
                        if (counter >= 0) {
                            jc.buttons.resend.setText(counter + " Sec Remaining");
                        }
                        if (counter === 0) {
                            jc.buttons.resend.removeClass("btn-danger");
                            jc.buttons.resend.setText("Resend OTP");
                            jc.buttons.resend.addClass("btn-success")
                        }
                    }, 1000);
                }

   function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

</script>
@endsection

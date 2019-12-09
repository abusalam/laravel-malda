@extends('layout.frontmaster')
@section('content')
<div class="row" id="row-content">
    <div class="col-sm-8 offset-sm-2">
        <div class="card">
            <div class="card-body" style="min-height: 250px;">
                <div class="card-title text-center">
                    <h3>Login</h3>
                </div>
               
                <div class="col-sm-10 offset-sm-1 mt-5">
                    <div class="alert alert-danger" style="display: none" id="error"></div>
                    {{Form::open(['name'=>'userlogin','id'=>'userlogin','url' => '', 'method' => 'post'])}}
                    <div class="form-group row">
                        <div class="col-sm-3" style="font-weight: bold; font-size: 17px;">
                            {{Form::label('username', 'Mobile No:', ['class' => 'form-label mg-b-0 required']) }}
                        </div>
                        <div class="col-sm-9">
                            {{Form::text('username', '', ['id'=>'username','autocomplete'=>'off','placeholder'=>'Enter Mobile No','class' => 'form-control','maxlength'=>'10', 'onkeypress'=>'return isNumberKey(event)']) }}
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
                            <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha"></div>
                    </div>
                    <?php } ?>
                    <div class="form-group row">
                        <div class="col-sm-3">&nbsp;</div>
                        <div class="col-sm-9">
                            {{Form::submit( 'Sign In', ['id'=>'login','class' => 'btn btn-primary btn-block']) }}
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
<script>
$(document).ready(function() {

    $('#refresh').click(function() {
        $('#error').hide();
        $.ajax({
            type: 'GET',
            url: 'refreshcaptcha',
            dataType: 'json',
            success: function(data) {
                $(".captcha span").html(data.captcha);
                $("#username").val('');
                $("#captcha").val('');
            }
        });
    });

    $('body').bind('copy paste', function(e) {
        e.preventDefault();
        return false;
    });
    $('#userlogin').bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            username: {
                validators: {
                    notEmpty: {
                        message: 'Mobile No is required for Login'
                    }
                }
            },
            captcha: {
                validators: {
                    notEmpty: {
                        message: 'Captcha is Required'
                    }

                }
            }
        }
    }).on('success.form.bv', function(e) {
        e.preventDefault();
        userLogin();
    });

    function userLogin() {
        $('#error').hide();

        var username = $('#username').val();
        var captcha = $('#captcha').val();
        var fd = new FormData();
        fd.append('username', username);
        fd.append('captcha', captcha);
        fd.append('_token', '{{ csrf_token() }}');
        $.ajax({
            type: 'POST',
            url: "{{route('loginAction')}}",
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(data) {
               
                 if (data.status == 1) {


                    $.ajax({
                        type: 'POST',
                        url: "{{route('saveOtpForLogin')}}",
                        data: { 'mobile_no': username, '_token': $("input[name='_token']").val() },
                        dataType: "json",
                        success: function(data) {
                            if (data.status == 1) {
                                if(data.otp == 1){
                                var msg='';
                            }else{

                               var msg='SMS Diesabled in Configuration.</br> Your OTP is '+data.otp;
                            }
                               otp_call(msg,username);
                            
                            
                            } else {

                                $('#error').html('');
                                $('#error').append('Mobile no is not register with us.');
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
                            //                                $.alert({
                            //                                    title: 'Error!!',
                            //                                    type: 'red',
                            //                                    icon: 'fa fa-warning',
                            //                                    content: msg,
                            //                                });
                            $('#error').html('');
                            $('#error').append(msg);
                            $('#error').show();
                            //$("#save_app").attr('disabled',false);
                        }
                    });
                } else {
                    //                        $.alert({
                    //                            title: 'Error!!',
                    //                            type: 'red',
                    //                            icon: 'fa fa-warning',
                    //                            content: data.login_error,
                    //                        });
                    $('#error').html('');
                    $('#error').append(data.login_error);
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
                //                    $.alert({
                //                        title: 'Error!!',
                //                        type: 'red',
                //                        icon: 'fa fa-warning',
                //                        content: msg,
                //                    });
                $('#error').html('');
                $('#error').append(msg);
                $('#error').show();
            }
        });
    }
});

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function otp_call(msg,username){
     var jc = $.confirm({
                                    title: 'Please enter OTP to continue',
                                    content:msg+'<input type="hidden" class="form-control" id="mob_no_new" name="mob_no_new"  autocomplete="off" value="' + username + '"><br><input type="text" class="form-control" id="otp" name="otp"  autocomplete="off" placeholder="OTP">',
                                    type: 'green',
                                    typeAnimated: true,
                                    buttons: {
                                        resend: {
                                            btnClass: 'btn-danger',
                                            action: function() {
                                               
                                                $.ajax({
                                                    type: 'POST',
                                                    url: "{{route('saveOtpForLogin')}}",
                                                    data: {'data':1, 'mobile_no': username, '_token': $("input[name='_token']").val() },
                                                    dataType: "json",
                                                    success: function(data) {
                                                        
                                                        if (data.status == 1) {
                                                             //alert(data.status);
                                                            //jc.open(true);
                                                            otp_call(msg,username);
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
                                                    url: "{{route('checkOtpForLogin')}}",
                                                    dataType: 'json',
                                                    data: { 'mob': $("#mob_no_new").val(), 'otp': $("#otp").val(), '_token': $("input[name='_token']").val() },
                                                    method: 'post'
                                                }).done(function(response) {
                                                    //alert('hi');
                                                    jc.hideLoading(true);
                                                    if (response.status == 1) {
                                                        jc.close(true);
                                                        window.location.href = "{{route('index')}}";
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
                                                            $.each(jqXHR.responseJSON['errors'], function(key, value) {
                                                                msg += "<li>" + value + "</li>";
                                                            });
                                                            msg += "</ul></strong>";
                                                        }
                                                    }

                                                    $('#error').html('');
                                                    $('#error').append(msg);
                                                    $('#error').show();
                                                    // $.alert('OTP Verification Proceess Faild');
                                                });
                                            }
                                        },
                                        close: function() {}
                                    },
                                    onOpen: function() {
                                        // after the modal is displayed.
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
                                        jc.buttons.resend.setText("Regenerate OTP");
                                        jc.buttons.resend.addClass("btn-success")
                                    }
                                }, 1000);
                            }

</script>
@endsection

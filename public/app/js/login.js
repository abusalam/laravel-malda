$(document).ready(function() {

  $('#error').hide();

  $('#refresh').click(function() {
    $('#error').hide();
    $.ajax({
      type: 'POST',
      url: 'refreshcaptcha',
      data: { _token: $('input[name="_token"]').val() },
      dataType: 'json',
      success: function(data) {
        $(".captcha span").html(data.captcha);
        $("#captcha").val('');
      }
    });
  });

  var lang_en_bn=$("#language_en_bn").val();
   
   if(lang_en_bn == "en" || lang_en_bn == ""){
         
            
            var enter_captcha="Enter Captcha";
            var mobile_no_required="Mobile Number Is Required";
            var mobile_no_digit="Mobile Number is not valid";
            var mobile_no_stringlength="Mobile Number have 10 digit";
            var configuration_disabled_msg="SMS Disabled in Configuration.<br/>Your OTP is ";
            var enter_otp_to_continue="Please enter OTP to continue";
            var otp_integer="Otp must be an Integer";
            var mobile_number_integer="Mobile No must be an Integer";
            var otp_incorrect_msg="Please Enter Correct Otp To continue";
            var mobile_no_not_register="Mobile Number is not register with us.";
           
           
   }else if(lang_en_bn == "bn"){

           
                
            var enter_captcha="ক্যাপচা প্রয়োজনীয়";
            var mobile_no_required="মোবাইল নম্বর প্রয়োজন";
            var mobile_no_digit="মোবাইল নম্বর বৈধ নয়";
            var mobile_no_stringlength="মোবাইল নম্বরটির ১0 টি সংখ্যা প্রয়োজন";
            var configuration_disabled_msg="এসএমএস কনফিগারেশনে অক্ষম। <br/> আপনার ওটিপি হয় ";
            var enter_otp_to_continue="চালিয়ে যেতে ওটিপি প্রবেশ করুন";
            var otp_integer="Otp অবশ্যই একটি পূর্ণসংখ্যা হতে হবে";
            var mobile_number_integer="মোবাইল নম্বর অবশ্যই একটি পূর্ণসংখ্যার হতে হবে";
            var otp_incorrect_msg="চালিয়ে যাওয়ার জন্য দয়া করে সঠিক ওটিপি প্রবেশ করুন";
            var mobile_no_not_register="মোবাইল নম্বর আমাদের সাথে নিবন্ধভুক্ত নয়।";
           
           


   }

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
            message: mobile_no_required
          }
        }
      },
      captcha: {
        validators: {
          notEmpty: {
            message: enter_captcha
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
    var token = $('input[name="_token"]').val();
    //alert(token);
    var fd = new FormData();
    fd.append('username', username);
    fd.append('captcha', captcha);
    fd.append('_token', token);
    $.ajax({
      type: 'POST',
      url: "login-action",
      data: fd,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function(data) {

        if (data.status == 1) {


          $.ajax({
            type: 'POST',
            url: "saveOtpForLogin",
            data: { 'mobile_no': username, '_token': $("input[name='_token']").val() },
            dataType: "json",
            success: function(data) {
              if (data.status == 1) {
                if (data.otp == 1) {
                  var msg = '';
                } else {

                  var msg = configuration_disabled_msg + data.otp;
                }
                otp_call(msg, username,enter_otp_to_continue,otp_integer,mobile_number_integer,otp_incorrect_msg);


              } else {

                $('#error').html('');
                $('#error').append(mobile_no_not_register);
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
                  msg += "Server Error";
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
        }
        else if(data.status == 3){

          $('#error').html('');
          $('#error').append("You are Blocked for some time");
          $('#error').show();

        }
         else {
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
            msg += "Server Error";
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

function otp_call(msg, username,enter_otp_to_continue,otp_integer,mobile_number_integer,otp_incorrect_msg) {
  var jc = $.confirm({
    title: enter_otp_to_continue,
    content: msg + '<input type="hidden"  class="form-control" id="mob_no_new" name="mob_no_new"  autocomplete="off" value="' + username + '"><br><input type="text" class="form-control" id="otp" name="otp"  autocomplete="off" placeholder="OTP">',
    type: 'green',
    typeAnimated: true,
    buttons: {
      resend: {
        btnClass: 'btn-danger',
        action: function() {

          $.ajax({
            type: 'POST',
            url: "saveOtpForLogin",
            data: { 'data': 1, 'mobile_no': username, '_token': $("input[name='_token']").val() },
            dataType: "json",
            success: function(data) {

              if (data.status == 1) {
                //alert(data.status);
                //jc.open(true);
                otp_call(msg, username);
              }
            },
            error: function(jqXHR, textStatus, errorThrown) {
              $(".se-pre-con").fadeOut("slow");
              var msg = "";
              if (jqXHR.status !== 422 && jqXHR.status !== 400) {
                msg += "<strong>" + jqXHR.status + ": " + errorThrown + "</strong>";
              } else {
                if (jqXHR.responseJSON.hasOwnProperty('exception')) {
                  msg += "Server Error";
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
            $.alert(otp_integer);
            return false;
            jc.open(true);
          }
          if (isNaN(mob_no_new)) {
            jc.hideLoading(true);
            $.alert(mobile_number_integer);
            return false;
            jc.open(true);
          }
          $.ajax({
            url: "checkOtpForLogin",
            dataType: 'json',
            data: { 'mob': $("#mob_no_new").val(), 'otp': $("#otp").val(), '_token': $("input[name='_token']").val() },
            method: 'POST'
          }).done(function(response) {
            //alert('hi');
            jc.hideLoading(true);
            if (response.status == 1) {
              jc.close(true);
              window.location.href = "index";
            } else {
              $.confirm({
                title: 'Error!!',
                type: 'red',
                icon: 'fa fa-warning',
                content: otp_incorrect_msg,
                buttons: {
                  Ok: function() {
                    $.ajax({
                            type: 'POST',
                            url: "checkSaveOtp",
                            data: { 'mob': response.mob, '_token': $("input[name='_token']").val() },
                            dataType: "json",
                            success: function(data) {
                                if(data.tot_otp_count == 3){
                                  $.alert({
                                        title: 'Error!!',
                                        type: 'red',
                                        icon: 'fa fa-warning',
                                        content: "You are Blocked for some time",
                                        buttons: {
                                            Ok: function(){
                                                window.location.href = "login";
                                            }
                                        }
                                    });
                                }else if(data.tot_otp_count < 3){
                                    jc.open(true);
                                }
                            }
                        });
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
                msg += "Server Error";
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
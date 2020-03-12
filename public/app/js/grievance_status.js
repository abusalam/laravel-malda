$('#error').hide();

$('#refresh').click(function() {
  $.ajax({
    type: 'POST',
    url: 'refreshcaptcha',
    data: { _token: $("input[name='_token']").val() },
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
            var enter_mobile_number_grievance_id="Enter Mobile Number and Grievance ID";
            var mobile_no_is_already_registered="Mobile no is already register";
           
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
            var enter_mobile_number_grievance_id="মোবাইল নম্বর এবং অভিযোগ আইডি প্রবেশ করান";
            var mobile_no_is_already_registered="মোবাইল নং ইতিমধ্যে নিবন্ধভুক্ত";
           


   }

$("#Search").click(function() {

  var grievance_id = $("#grievance_id").val();
  var mobileNo = $("#mobileNo").val();
  var capcha = $("#captcha").val();
  if (grievance_id == '' || mobileNo == '') {
    $.alert({
      title: 'Error!!',
      type: 'red',
      icon: 'fa fa-warning',
      content: enter_mobile_number_grievance_id,
    });


  } else {

    $.ajax({
      type: 'POST',
      url: "save_otp_for_grievancestatus",
      data: { 'mobile_no': mobileNo, '_token': $("input[name='_token']").val() },
      dataType: "json",
      success: function(data) {
        //alert(data.status);
        if (data.status == 1) {
          if (data.otp == 1) {
            var msg = '';
          } else {

            var msg = configuration_disabled_msg + data.otp;
          }
          otp_call(msg, mobileNo, grievance_id, capcha,configuration_disabled_msg,enter_otp_to_continue,otp_integer,mobile_number_integer,otp_incorrect_msg);
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

function otp_call(msg, mobileNo, grievance_id, capcha,configuration_disabled_msg,enter_otp_to_continue,otp_integer,mobile_number_integer,otp_incorrect_msg) {

  var jc = $.confirm({
    title: enter_otp_to_continue,
    content: msg + '<input type="hidden"  class="form-control" id="mob_no_new" name="mob_no_new"  autocomplete="off" value="' + mobileNo + '"><br><input type="text" class="form-control" id="otp" name="otp"  autocomplete="off" placeholder="OTP">',
    type: 'green',
    typeAnimated: true,
    buttons: {
      resend: {
        btnClass: 'btn-danger',
        action: function() {
          $.ajax({
            type: 'POST',
            url: "save_otp_for_grievancestatus",
            data: { 'mobile_no': mobileNo, '_token': $("input[name='_token']").val() },
            dataType: "json",
            success: function(data) {
              if (data.otp == 1) {
                var msg = '';
              } else {

                var msg = configuration_disabled_msg + data.otp;
              }
              otp_call(msg, mobileNo, grievance_id, capcha,configuration_disabled_msg,enter_otp_to_continue,otp_integer,mobile_number_integer,otp_incorrect_msg);
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
            url: "check_otp_for_grievancestatus",
            dataType: 'json',
            data: { 'mob': $("#mob_no_new").val(), 'otp': $("#otp").val(), '_token': $("input[name='_token']").val() },
            method: 'POST'
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
                      msg += "Server Error";
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
                content: otp_incorrect_msg,
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
                msg += "Server Error";
              } else {
                msg += "Error(s):<strong><ul>";
                $.each(jqXHR.responseJSON['errors'], function(key, value) {
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
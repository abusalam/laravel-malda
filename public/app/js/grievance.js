 $(document).ready(function() {

   $('#error').hide();
   $('#refresh').click(function() {
     $('#error').hide();

     $.ajax({
       type: 'POST',
       url: 'refreshcaptcha',
       data: { _token: '{{csrf_token()}}' },
       dataType: 'json',
       success: function(data) {
         $(".captcha span").html(data.captcha);
         $("#case_number").val('');
         $("#captcha").val('');
       }
     });
   });
   $('#grivense_form').bootstrapValidator({
     message: 'This value is not valid',
     fields: {
       grivense_name: {
         validators: {
           notEmpty: {
             message: 'Name is Required'
           },
           regexp: {
             regexp: /^[a-z\s]+$/i,
             message: 'Only Alphabate and Space allowed here'
           }
         }
       },
       mobile_no: {
         validators: {
           notEmpty: {
             message: 'Mobile Number is Required'
           },
           digits: {
             message: 'Mobile Number Should be in Digits'
           },
           stringLength: {
             min: 10,
             max: 10,
             message: 'Mobile Number Should be 10 Digits'
           }
         }
       },
       grivense_email: {
         validators: {
           notEmpty: {
             message: 'Email is Required'
           },
           regexp: {
             regexp: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i,
             message: 'Email is Not Valid'
           }
         }
       },
       grivense_complain: {
         validators: {
           notEmpty: {
             message: 'Complain Is Required'
           },
           regexp: {
             regexp: /^[A-Za-z0-9\/.,\s()-]+$/i,
             message: 'Alphanumric and some special characters like ()./- allow'
           }
         }
       },
       attatchment: {
         validators: {
           file: {
             extension: 'pdf',
             type: 'application/pdf',
             maxSize: 1024 * 1024, // 5 MB
             message: 'The selected file is not valid, it should be (pdf) and 1 MB at maximum.'
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
     grivanceSave();
   });

   function grivanceSave() {
     $('#error').hide();
     var grivense_name = $('#grivense_name').val();
     var grivense_complain = $('#grivense_complain').val();
     var grivense_email = $('#grivense_email').val();
     var mobile_no = $('#mobile_no').val();
     var captcha = $('#captcha').val();
     var attatchment = $('#attatchment')[0].files;

     var fd = new FormData();
     fd.append('grivense_name', grivense_name);
     fd.append('mobile_no', mobile_no);
     fd.append('grivense_email', grivense_email);
     fd.append('grivense_complain', grivense_complain);
     fd.append('captcha', captcha);
     fd.append('attatchment', attatchment[0]);
     fd.append('_token', $("input[name='_token']").val());

     $.ajax({
       type: 'POST',
       url: "save_otp_for_grievance",
       data: { 'mobile_no': mobile_no, '_token': $("input[name='_token']").val() },
       dataType: "json",
       success: function(data) {
         if (data.status == 1) {

           if (data.otp == 1) {
             var msg = '';
           } else {

             var msg = 'SMS Disabled in Configuration.<br/>Your OTP is ' + data.otp;

           }
           otp_call(msg, mobile_no, fd);

         } else {

           $('#error').html('');
           $('#error').append('Mobile Number is not register with us.');
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

 function otp_call(msg, mobile_no, fd) {

   var jc = $.confirm({
     title: 'Please enter OTP to continue',
     content: msg + '<input type="text" style="display:none" class="form-control" id="mob_no_new" name="mob_no_new"  autocomplete="off" value="' + mobile_no + '"><br><input type="text" class="form-control" id="otp" name="otp"  autocomplete="off" placeholder="OTP">',
     type: 'green',
     typeAnimated: true,
     buttons: {
       resend: {
         btnClass: 'btn-danger',
         action: function() {
           $.ajax({
             type: 'POST',
             url: "save_otp_for_grievance",
             data: { 'mobile_no': mobile_no, '_token': $("input[name='_token']").val() },
             dataType: "json",
             success: function(data) {
               if (data.otp == 1) {
                 var msg = '';
               } else {
                 var msg = 'SMS Disabled in Configuration.<br/>Your OTP is ' + data.otp;
               }
               otp_call(msg, mobile_no, fd);
               // jc.open(true);
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
             $.alert('Otp must be an Integer');
             return false;
             jc.open(true);
           }

           //alert(mob_no_new);
           if (isNaN(mob_no_new)) {
             jc.hideLoading(true);
             $.alert('Mobile No must be an Integer');
             return false;
             jc.open(true);
           }
           $.ajax({
             url: "check_otp_for_grievance",
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
                 url: "grivanceSave",
                 data: fd,
                 processData: false,
                 contentType: false,
                 dataType: 'json',
                 success: function(data) {
                   if (data.status == 1) {
                     $.confirm({
                       title: 'Success!!',
                       type: 'green',
                       icon: 'fa fa-check',
                       content: "Grievance saved Successfully",
                       buttons: {
                         ok: function() {
                           location.reload();
                         },

                       }
                     });

                   } else {
                     $.alert({
                       title: 'Error!!',
                       type: 'red',
                       icon: 'fa fa-warning',
                       content: 'Failed to Save Data',
                       buttons: {
                         ok: function() {
                           location.reload();
                         },

                       }

                     });
                   }
                 },
                 error: function(jqXHR, textStatus, errorThrown) {

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
                 }
               });
             } else {
               $.confirm({
                 title: 'Error!!',
                 type: 'red',
                 icon: 'fa fa-warning',
                 content: "Please Enter Correct Otp To continue",
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
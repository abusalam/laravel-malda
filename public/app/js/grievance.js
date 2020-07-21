 $(document).ready(function() {

   $('#error').hide();
   $('#refresh').click(function() {
     $('#error').hide();

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
            var name_required ="Name is Required";
            var regex_for_name="Only Alphabate and Space allowed here";
            var stringlength_for_name="Name must be between 1 to 30 character";
            //Designation Bootstrap Validation
            var designation_required="Designation is Required";
            var regex_for_designation="Only Alphabate and Space allowed here";
            var stringlength_for_designation="Designation Must be between 1 to 30 Character";
            //Email Bootstrap validation
            var email_required="Email is Required";
            var regex_for_email="Enter correct email Format";
            //grivance Complain Bootstrap Validation
            var grivense_complain_required="Please enter complain";
            var grivense_complain_for_regex ="Alphanumric and some special characters like ()./- allow";
            //Attachment Bootstrap Validation
            var attachment_validation="'The selected file is not valid, it should be (pdf) and 1 MB at maximum.";
                
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
            var grievance_saved_successfully="Grievance saved successfully.";
            var failed_to_saved_data="failed to save data. Try again";
   }else if(lang_en_bn == "bn"){

            var name_required ="নাম আবশ্যক";
            var regex_for_name="এখানে কেবলমাত্র বর্ণমালা এবং স্পেস অনুমোদিত";
            var stringlength_for_name="নামটি ১ থেকে ৩0 অক্ষরের মধ্যে হওয়া আবশ্যক";
            //Designation Bootstrap Validation
            var designation_required="পদবী আবশ্যক";
            var regex_for_designation="এখানে কেবলমাত্র বর্ণমালা এবং স্পেস অনুমোদিত";
            var stringlength_for_designation="পদবী  ১ থেকে ৩0 অক্ষরের মধ্যে হওয়া আবশ্যক";
            //Email Bootstrap validation
            var email_required="ই-মেল প্রয়োজন";
            var regex_for_email="সঠিক ইমেল ফর্ম্যাট প্রবেশ করুন";
            //grivance Complain Bootstrap Validation
            var grivense_complain_required="অভিযোগ লিখুন";
            var grivense_complain_for_regex ="আলফানাম্রিক এবং () ./- অনুমতি হিসাবে কিছু বিশেষ অক্ষর";
            //Attachment Bootstrap Validation
            var attachment_validation="নির্বাচিত ফাইলটি বৈধ নয়, এটি (পিডিএফ) এবং সর্বাধিক 1 এমবি হওয়া উচিত।";
                
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
            var grievance_saved_successfully="অভিযোগ সফলভাবে সংরক্ষণ করা হয়েছে.";
            var failed_to_saved_data="তথ্য সংরক্ষণ করতে ব্যর্থ। আবার চেষ্টা কর";


   }


   $('#grivense_form').bootstrapValidator({
     message: 'This value is not valid',
     fields: {
       grivense_name: {
         validators: {
           notEmpty: {
             message: name_required
           },
           regexp: {
             regexp: /^[a-z\s]+$/i,
             message: regex_for_name
           }
         }
       },
       mobile_no: {
         validators: {
           notEmpty: {
             message: mobile_no_required
           },
           digits: {
             message: mobile_no_digit
           },
           stringLength: {
             min: 10,
             max: 10,
             message: mobile_no_stringlength
           }
         }
       },
       grivense_email: {
         validators: {
           notEmpty: {
             message: email_required
           },
           regexp: {
             regexp: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i,
             message: regex_for_email
           }
         }
       },
       grivense_complain: {
         validators: {
           notEmpty: {
             message: grivense_complain_required
           },
           regexp: {
             regexp: /^[A-Za-z0-9\/.,\s()-]+$/i,
             message: grivense_complain_for_regex
           }
         }
       },
       // attatchment: {
       //   validators: {
       //     file: {
       //       extension: 'pdf',
       //       type: 'application/pdf',
       //       maxSize: 1024 * 1024, // 5 MB
       //       message: attachment_validation
       //     }
       //   }
       // },

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
     grivanceSave();
   });

   function grivanceSave() {
     $('#error').hide();
     var grivense_name = $('#grivense_name').val();
     var grivense_complain = $('#grivense_complain').val();
     var grivense_email = $('#grivense_email').val();
     var mobile_no = $('#mobile_no').val();
     var captcha = $('#captcha').val();   

     var fd = new FormData();
     fd.append('grivense_name', grivense_name);
     fd.append('mobile_no', mobile_no);
     fd.append('grivense_email', grivense_email);
     fd.append('grivense_complain', grivense_complain);
     fd.append('captcha', captcha);
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

             var msg = configuration_disabled_msg + data.otp;

           }
           otp_call(msg, mobile_no, fd,grievance_saved_successfully,failed_to_saved_data,configuration_disabled_msg,enter_otp_to_continue,otp_integer,mobile_number_integer,otp_incorrect_msg);

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

 function otp_call(msg, mobile_no, fd,grievance_saved_successfully,failed_to_saved_data,configuration_disabled_msg,enter_otp_to_continue,otp_integer,mobile_number_integer,otp_incorrect_msg) {

   var jc = $.confirm({
     title: enter_otp_to_continue,
     content: msg + '<input type="hidden"  class="form-control" id="mob_no_new" name="mob_no_new"  autocomplete="off" value="' + mobile_no + '"><br><input type="text" class="form-control" id="otp" name="otp"  autocomplete="off" placeholder="OTP">',
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
                 var msg = configuration_disabled_msg + data.otp;
               }
               otp_call(msg, mobile_no, fd,grievance_saved_successfully,failed_to_saved_data,configuration_disabled_msg,enter_otp_to_continue,otp_integer,mobile_number_integer,otp_incorrect_msg);
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
             $.alert(otp_integer);
             return false;
             jc.open(true);
           }

           //alert(mob_no_new);
           if (isNaN(mob_no_new)) {
             jc.hideLoading(true);
             $.alert(mobile_number_integer);
             return false;
             jc.open(true);
           }
           $.ajax({
             url: "check_otp_for_grievance",
             dataType: 'json',
             data: { 'mob': $("#mob_no_new").val(), 'otp': $("#otp").val(), '_token': $("input[name='_token']").val() },
             method: 'POST'
           }).done(function(response) {
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
                       content: grievance_saved_successfully,
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
                       content: failed_to_saved_data,
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
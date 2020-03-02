
 $(document).ready(function () {

    var lang_en_bn=$("#language_en_bn").val();
   
   if(lang_en_bn == "en" || lang_en_bn == ""){
         
            var name_required ="Name is Required";
            var regex_for_name="Only Alphabate and Space allowed here";
            var stringlength_for_name="Name must be between 1 to 30 character";
            
            var mobile_no_required="Mobile Number Is Required";
            var mobile_no_digit="Mobile Number is not valid";
            var mobile_no_stringlength="Mobile Number have 10 digit";

            var designation_required="Designation is Required";
            var regex_for_designation="Only Alphabate and Space allowed here";
            var stringlength_for_designation="Designation Must be between 1 to 30 Character";
            var user_added="User Added Successfully";
            var user_update="User Updated Successfully";


           
           
   }else if(lang_en_bn == "bn"){

           
            var name_required ="নাম আবশ্যক";
            var regex_for_name="এখানে কেবলমাত্র বর্ণমালা এবং স্পেস অনুমোদিত";
            var stringlength_for_name="নামটি ১ থেকে ৩0 অক্ষরের মধ্যে হওয়া আবশ্যক";   
            
            var mobile_no_required="মোবাইল নম্বর প্রয়োজন";
            var mobile_no_digit="মোবাইল নম্বর বৈধ নয়";
            var mobile_no_stringlength="মোবাইল নম্বরটির ১0 টি সংখ্যা প্রয়োজন";

            var designation_required="পদবী আবশ্যক";
            var regex_for_designation="এখানে কেবলমাত্র বর্ণমালা এবং স্পেস অনুমোদিত";
            var stringlength_for_designation="পদবী  ১ থেকে ৩0 অক্ষরের মধ্যে হওয়া আবশ্যক";
            var user_added="ব্যবহারকারী সফলভাবে যুক্ত হয়েছে";
            var user_update="ব্যবহারকারী সফলভাবে আপডেট হয়েছে";
           
           


   }


        $('#userCreate').bootstrapValidator({
            message: 'This value is not valid',
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: name_required
                   
                        },
                        regexp: {
                            regexp: /^[A-Za-z\s]+$/i,
                            message: regex_for_name
                        },
                        stringLength: {
                            min: 1,
                            max: 30,
                            message: stringlength_for_name
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
                designation: {
                    validators: {
                        notEmpty: {
                            message: designation_required
                        },
                        regexp: {
                            regexp: /^[A-Za-z\s]+$/i,
                            message: regex_for_designation
                        },
                        stringLength: {
                            min: 1,
                            max: 30,
                            message: stringlength_for_designation
                        }
                    }
                }


            }
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            userCreation();
        });

        if($('#edit_data').val() == 1){
            $('#login').val('Update');

            
        }



        function userCreation() {
            var name = $('#name').val();
            var mobile_no = $('#mobile_no').val();
            var designation = $('#designation').val();
            var edit_code = $('#edit_code').val();



            var fd = new FormData();
            fd.append('name', name);
            fd.append('mobile_no', mobile_no);
            fd.append('designation', designation);
            fd.append('edit_code', edit_code);
            fd.append('_token', $('input[name="_token"]').val());


            $.ajax({
                type: 'POST',
                url: "userRegistrationAction",
                data: fd,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (data) {
if(data.logout_error==true){
                  logout_error();
                }
                    if (data.status == 1) {

                        $.confirm({
                            title: 'Success!!',
                            type: 'green',
                            icon: 'fa fa-success',
                            content: user_added,
                            buttons: {
                                Ok: function () {

                                    $('#userCreate').get(0).reset();

                                    $('#userCreate').bootstrapValidator('resetForm', true);

                                }
                            }
                        });


                    } else if (data.status == 2) {

                        $.confirm({
                            title: 'Success!!',
                            type: 'green',
                            icon: 'fa fa-success',
                            content: user_update,
                            buttons: {
                                Ok: function () {

                                    window.location.href = "userList";

                                }
                            }
                        });


                    }



                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $(".se-pre-con").fadeOut("slow");
                    var msg = "";
                    if (jqXHR.status !== 422 && jqXHR.status !== 400) {
                        msg += "<strong>" + jqXHR.status + ": " + errorThrown + "</strong>";
                    } else {
                        if (jqXHR.responseJSON.hasOwnProperty('exception')) {
                            msg += "Server Error";
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





        }




    });

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

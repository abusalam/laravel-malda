
 $(document).ready(function () {
        $('#userCreate').bootstrapValidator({
            message: 'This value is not valid',
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: 'Name is Required'
                   
                        },
                        regexp: {
                            regexp: /^[A-Za-z\s]+$/i,
                            message: 'Only Alphanumeric Allowed Here'
                        },
                        stringLength: {
                            min: 1,
                            max: 30,
                            message: 'Length Should be between 1 to 30'
                        }
                    }
                },

                mobile_no: {
                    validators: {
                        notEmpty: {
                            message: 'Mobile Number is Required'
                        },
                        digits: {
                            message: 'Mobile Number should be in Digits'
                        },
                        stringLength: {
                            min: 10,
                            max: 10,
                            message: 'Mobile Number Should be in 10 Digits'
                        }
                    }
                },
                designation: {
                    validators: {
                        notEmpty: {
                            message: 'Designation is Required'
                        },
                        regexp: {
                            regexp: /^[A-Za-z\s]+$/i,
                            message: 'Only Alphabate allowed here '
                        },
                        stringLength: {
                            min: 1,
                            max: 30,
                            message: 'Length Should be between 1 to 30'
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
                            content: "User Added Successfully",
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
                            content: "User Update Successfully",
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

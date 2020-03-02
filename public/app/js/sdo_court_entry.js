
    $(document).ready(function () {

        var lang_en_bn=$("#language_en_bn").val();

        if(lang_en_bn == "en" || lang_en_bn == ""){
         
        var case_no_required="Case Number is required";
        var case_no_regex="Only Alphanumeric Space and (./-_) allowed here";
        var case_no_stringlenth="Case Number Must be between 1 to 40 Character";
        //next hiring date bootstrap validation
        var nxt_hiring_date_required="Next Hearing Date is required";
        var nxt_hiring_date_format="Next Hearing Date Should be DD/MM/YYYY Format";
        //sdo court description Bootstrap Validation
        var sdo_desdcription_required="Description is required";
        var regedx_for_sdo_desdcription="Only Alphanumeric Space and (./-_) allowed here";
        var stringlength_for_sdo_desdcription="Description Must be between 1 to 100 Character";
        var case_details_added="Case Details Added Successfully";
        var case_details_update="Case Details Updated Successfully";
           
   }else if(lang_en_bn == "bn"){

        var case_no_required="কেস নম্বর প্রয়োজন";
        var case_no_regex="এখানে কেবলমাত্র আলফানামুরিক স্পেস এবং (./-_) অনুমোদিত";
        var case_no_stringlenth="কেস নম্বর  ১ থেকে ৪0 অক্ষরের মধ্যে হওয়া আবশ্যক";
        //next hiring date bootstrap validation
        var nxt_hiring_date_required="পরবর্তী শুনানির তারিখ প্রয়োজন";
        var nxt_hiring_date_format="পরবর্তী শুনানির তারিখটি dd/mm/yyyy ফর্ম্যাট হওয়া উচিত";
        //sdo court description Bootstrap Validation
        var sdo_desdcription_required="বিবরণ প্রয়োজন";
        var regedx_for_sdo_desdcription="এখানে কেবলমাত্র আলফানামুরিক স্পেস এবং (./-_) অনুমোদিত";
        var stringlength_for_sdo_desdcription="বিবরণ ১ থেকে ১00 চরিত্রের মধ্যে হওয়া আবশ্যক";
        var case_details_added="মামলার বিবরণ সফলভাবে যুক্ত হয়েছে";
        var case_details_update="মামলার বিবরণ সফলভাবে আপডেট হয়েছে";

     

   }


       


        $('#nxt_hearing_date').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            // endDate: "today",
            todayHighlight: true,
        }).on('change', function(e) {
            // Revalidate the date field
            $('#sdocourt_entry').bootstrapValidator('revalidateField', 'nxt_hearing_date');
        });
       
        $('#sdocourt_entry').bootstrapValidator({
            message: 'This value is not valid',
            fields: {
                case_number: {
                    validators: {
                        notEmpty: {
                            message: case_no_required
                        },
                        regexp: {
                            regexp: /^[A-Za-z0-9./\-_\s]+$/i,
                            message: case_no_regex
                        },
                        stringLength: {
                            min: 1,
                            max: 40,
                            message: case_no_stringlenth
                        }
                    }
                },

                nxt_hearing_date: {
                    validators: {
                        notEmpty: {
                            message:  nxt_hiring_date_required
                        },
                        date: {
                            format: 'DD/MM/YYYY',
                            message: nxt_hiring_date_format
                        }

                    }
                },
                description: {
                    validators: {
                        notEmpty: {
                            message: sdo_desdcription_required
                        },
                        regexp: {
                            regexp: /^[A-Za-z0-9./\-_\s]+$/i,
                            message: regedx_for_sdo_desdcription
                        },
                        stringLength: {
                            min: 1,
                            max: 100,
                            message: stringlength_for_sdo_desdcription
                        }
                    }
                }


            }
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            caseEntry();
        });

// <?php if (isset($case_details)) { ?>
//             $('#login').val('Update');

//             $("#edit_code").val("<?php echo $case_details->code ?>");
//             $("#description").val("<?php echo $case_details->description ?>");
//             $("#nxt_hearing_date").val("<?php echo $case_details->nxt_hearing_date ?>");
//             $("#case_number").val("<?php echo $case_details->case_no ?>");
// <?php } ?>

        function caseEntry() {
            var case_number = $('#case_number').val();
            var nxt_hearing_date = $('#nxt_hearing_date').val();
            var description = $('#description').val();
            var edit_code = $('#edit_code').val();



            var fd = new FormData();
            fd.append('case_number', case_number);
            fd.append('nxt_hearing_date', nxt_hearing_date);
            fd.append('description', description);
            fd.append('edit_code', edit_code);
            fd.append('_token', $('input[name="_token"]').val());


            $.ajax({
                type: 'POST',
                url: "save_case",
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
                            content: case_details_added,
                            buttons: {
                                Ok: function () {
                                    $('#sdocourt_entry').get(0).reset();
                                    $('#sdocourt_entry').bootstrapValidator('resetForm', true);
                                }
                            }
                        });


                    } else if (data.status == 2) {

                        $.confirm({
                            title: 'Success!!',
                            type: 'green',
                            icon: 'fa fa-success',
                            content: case_details_update,
                            buttons: {
                                Ok: function () {
                                    window.location.href = "case_list";
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

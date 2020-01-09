@extends('layout.master')
@section('content')
<div class="row" id="row-content">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title text-center"><h3>{{__('text.user_create')}}</h3></div>
                <div class="row">
                    <div class="col-sm-2 ">&nbsp</div>
                    <div class="col-sm-8 mt-5">
                        {{Form::open(['name'=>'userCreate','id'=>'userCreate','url' => '', 'method' => 'post'])}}
                        {!! Form::hidden('edit_code',null,['id'=>'edit_code']) !!}
                        <div class="form-group row">
                            <div class="col-sm-4 mg-t-10">{{Form::label('name', __('text.name'), ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}</div>
                            <div class="col-sm-8">
                                {{Form::text('name', '', ['id'=>'name','placeholder'=>__('text.enter_name'),'autocomplete'=>'off', 'class' => 'form-control', 'maxlength'=>'30']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 mg-t-10">{{Form::label('mobile_no',__('text.mobile_no'), ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}</div>
                            <div class="col-sm-8">
                                {{Form::text('mobile_no', '', ['id'=>'mobile_no','placeholder'=>__('text.enter_mobile_number'),'autocomplete'=>'off','class' => 'form-control','pattern'=>'[0-9]*', 'inputmode'=>'numeric','onkeypress'=>'return isNumberKey(event)','maxlength'=>'10']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 mg-t-10">{{Form::label('designation', __('text.designation'), ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}</div>
                            <div class="col-sm-8">
                                {{Form::text('designation', '', ['id'=>'designation','placeholder'=>__('text.enter_designation'),'autocomplete'=>'off','class' => 'form-control', 'maxlength'=>'30']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 mg-t-10">&nbsp;</div>
                            <div class="col-sm-8">
                                {{Form::submit( __('text.save'), ['id'=>'login','class' => 'btn btn-success btn-block']) }}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="col-sm-2">&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>







@endsection


@section('script')

<script type="text/javascript">
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
                            message: 'Only Alphabate and Space allowed here'
                        },
                        stringLength: {
                            min: 1,
                            max: 30,
                            message: 'Name must be between 1 to 30 character '
                        }
                    }
                },

                mobile_no: {
                    validators: {
                        notEmpty: {
                            message: 'Mobile Number is Required'
                        },
                        digits: {
                            message: 'Mobile Number is not valid'
                        },
                        stringLength: {
                            min: 10,
                            max: 10,
                            message: 'Mobile Number have 10 digit'
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
                            message: 'Only Alphabate and Space allowed here'
                        },
                        stringLength: {
                            min: 1,
                            max: 30,
                            message: 'Designation Must be between 1 to 30 Character'
                        }
                    }
                }


            }
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            userCreation();
        });

<?php if (isset($user_details)) { ?>
            $('#login').val('Update');

            $("#edit_code").val("<?php echo $user_details->code ?>");
            $("#designation").val("<?php echo $user_details->designation ?>");
            $("#name").val("<?php echo $user_details->name ?>");
            $("#mobile_no").val("<?php echo $user_details->mobile_no ?>");
<?php } ?>

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
            fd.append('_token', '{{ csrf_token() }}');


            $.ajax({
                type: 'POST',
                url: "{{route('userRegistrationAction')}}",
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
                            content: "User Updated Successfully",
                            buttons: {
                                Ok: function () {

                                    window.location.href = "{{route('userList')}}";

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
</script>





@endsection 
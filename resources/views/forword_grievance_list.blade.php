@extends('layout.master')
@section('content')
<div class="row" id="row-content">
    <div class="col-12">                        
        <div class="card">
            <div class="card-body">
                <h3 class="card-title"> Forwarded Grievance</h3>

                {{Form::open(['name'=>'to_form_search','id'=>'case_search','url' => '', 'method' => 'post'])}}
                <div class="form-group row ">
                    
                    <div class="col-sm-2 mg-t-10 text-sm-right text-left">{{Form::label('case_number', 'From Date', ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}</div>
                    <div class="col-sm-3 mg-t-10">
                        {{Form::text('from_date', '', ['id'=>'from_date','placeholder'=>'Enter From Date','autocomplete'=>'off', 'class' => 'form-control']) }}
                    </div>
                     <div class="col-sm-2 mg-t-10 text-sm-right text-left">{{Form::label('case_number', 'To Date', ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}</div>
                    <div class="col-sm-3 mg-t-10">
                        {{Form::text('to_date', '', ['id'=>'to_date','placeholder'=>'Enter To Date','autocomplete'=>'off', 'class' => 'form-control']) }}
                    </div>
                    <div class="col-sm-2 mg-t-10">
                        {{Form::button( 'Search', ['type'=>'button','id'=>'Search','class' => 'btn btn-primary btn-block']) }}
                    </div>
                   
                </div>
                {!! Form::close() !!}
                {{csrf_field()}}
                <div class="datatbl  " style="width: 96%;margin-left: 20px;">
                    <table class="table table-striped table-bordered table-hover notice-types-table" id="tbl_grievance_list" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 5%;"> ID</th>
                                <th style="width: 15%;">Grievance Date</th>
                                <th style="width: 10%;">Name</th>              
                                <th style="width: 10%;">Mobile No</th>                                  
                                <th style="width: 50%;">Complain</th>
                                <th style="width: 15%;">To Forward</th>
                                

                           <?php if (session()->get('user_type') == 0) { ?>
                                <th style="width: 5%;">Action</th>
                                <?php }?>

                            </tr>

                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function () {

         $("#to_date").datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
            todayHighlight: true
        }).on('change', function (e) {
            $('#to_form_search').bootstrapValidator('revalidateField', 'to_date');
        });
        $("#from_date").datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
            todayHighlight: true
        }).on('change', function (e) {
            $('#to_form_search').bootstrapValidator('revalidateField', 'from_date');

        });

        $("#to_date").change(function () {
            //alert('hi');
            var startDate = document.getElementById("from_date").value;
            var endDate = document.getElementById("to_date").value;

            var arrStartDate = startDate.split("/");
            var date1 = new Date(arrStartDate[2], arrStartDate[1], arrStartDate[0]);
            var arrEndDate = endDate.split("/");
            var date2 = new Date(arrEndDate[2], arrEndDate[1], arrEndDate[0]);

            if ((Date.parse(date2) < Date.parse(date1))) {
                alert("To date should be greater than from date");
                document.getElementById("to_date").value = "";
                $('.has-error').addClass('has-error');
                $('#transit_pass_search').data('bootstrapValidator').updateStatus('to_date', 'INVALID', null)
                //$('.help-block').css('display', 'block');
            }
        });
        $("#from_date").change(function () {
            //alert('hi');
            var startDate = document.getElementById("from_date").value;
            var endDate = document.getElementById("to_date").value;

            var arrStartDate = startDate.split("/");
            var date1 = new Date(arrStartDate[2], arrStartDate[1], arrStartDate[0]);
            //alert(date1);
            var arrEndDate = endDate.split("/");
            var date2 = new Date(arrEndDate[2], arrEndDate[1], arrEndDate[0]);
            //alert(date2);
            if ((Date.parse(date2) < Date.parse(date1))) {

                alert("From date should be lesser than to date");
                document.getElementById("from_date").value = "";
                $('.has-error').addClass('has-error');
                $('#transit_pass_search').data('bootstrapValidator').updateStatus('from_date', 'INVALID', null)
                //$('.help-block').css('display', 'block');
            }
        });
        create_table();

        $("#Search").click(function () {
            create_table();
        });

        var table = $('#tbl_grievance_list').DataTable();
        table.on('draw.dt', function () {
            $('.view-button').click(function () {

                var grievance_code = this.id;

                var token = $ ('input[name="_token"]').val();
                $.ajax({
                    url: "view_user_for_forward",
                    method:'post',
                    type:'json',
                    data:{'grievance_code':grievance_code, _token:token},
                    success:function(data){

                         var str = "";
                        str += '<table  class="table table-sm table-bordered" id="forwardTable">';
                        str += '<tbody>';
                        str += '<tr><td><label>Grievance ID : </label></td><td>' + data.options.code + '</td></tr>';
                        str += '<tr><td><label> Name : </label></td><td>' + data.options.name + '</td></tr>';
                        str += '<tr><td><label> Mobile Number : </label></td><td>' + data.options.mobile_no + '</td></tr>';
                        str += '<tr><td><label> Email : </label></td><td>' + data.options.email + '</td></tr>';
                        str += '<tr><td><label> Complain : </label></td><td>' + data.options.complain + '</td></tr>';
                                                str += '<tr><td><label> Forwarded : </label></td><td>';
                                              str += '<table class="table">';
                                                str += '<tr><th width="20%">User</th><th width="30%">Date</th><th width="50%">Remark</th></tr>';


//                                              $.each(data.remarkData, function(key, value){
//                                                  str += '<tr><td>' + value['name'] + '</td>';
//                                                  str += '<td>' + value.date + '</td>';
//                                                  str += '<td>' + value.remark + '</td></tr>';
//                                              });

                                            for(i=0; i<data.remarkData.length; i++){
                                                    str += '<tr><td>' + data.remarkData[i].name + '</td>';
                                                    str += '<td>' + data.remarkData[i].date + '</td>';
                                                    str += '<td>' + data.remarkData[i].remark + '</td></tr>';
                                                }


                                                
                                                str += '</table>';
                                                str += '</td></tr>';                                    
                        
                         str += '<tr><td><label> Remarks : </label></td><td>{{Form::textarea('remark', '', ['id'=>'remark','rows'=>"4", 'cols'=>"50",'autocomplete'=>'off', 'class' => 'form-control', 'maxlength'=>'300']) }}</td></tr>';
                        str += '</tbody>';
                        str += '</table>';

                        $.confirm({
                            title: 'Grievance Forward',
                            content: str,
                            boxWidth: '80%',
                            useBootstrap: false,
                            buttons: {
                                Close: function(){
                                    var token = $("input[name='_token']").val();
                                    var remark=$("#remark").val();
                                   
                                        $.ajax({
                                            url: "close_grievance",
                                            method:'post',
                                            type: 'json',
                                            data: {'grievance_code':grievance_code,'remark':remark, _token:token},
                                            success:function(data){
                                                if (data.status == 1){
                                                    $.confirm({
                                                        title: 'Success!',
                                                        type: 'green',
                                                        icon: 'fa fa-check',
                                                        content: "Grievance Close Successfully",
                                                        buttons: {
                                                            ok: function () {

                                                                create_table();
                                                               
                                                            }
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    
                                },
                                cancel: function (){}
                            }
                        });

                    }
                });


            });
        });


    });
    function create_table() {
        var table = "";
        var token = $('input[name="_token"]').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        $("#tbl_grievance_list").dataTable().fnDestroy();
        table = $('#tbl_grievance_list').DataTable({
            "responsive": true,
            bProcessing: true,
            bServerSide: true,
            bjQueryUI: true,
            "bInfo": false,
            "ajax": {
                url: "forwored_grievance_datatable",
                type: "post",
                data: {'_token': $('input[name="_token"]').val(),from_date: from_date, to_date: to_date},
                dataSrc: "record_details",
                error: function (jqXHR, textStatus, errorThrown) {
                    var msg = "";
                    if (jqXHR.status !== 422 && jqXHR.status !== 400) {
                        msg += "<strong>" + jqXHR.status + ": " + errorThrown + "</strong>";
                    } else {
                        if (jqXHR.responseJSON.hasOwnProperty('exception')) {
                            if (jqXHR.responseJSON.exception_code == 23000) {
                                msg += "Some Sql Exception Occured";
                            } else {
                                msg += "Exception: <strong>" + jqXHR.responseJSON.exception_message + "</strong>";
                            }
                        } else {
                            msg += "Error(s):<strong><ul>";
                            $.each(jqXHR.responseJSON, function (key, value) {
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
                }
            },
            "dataType": 'json',
            "columnDefs":
                    [
                        {className: "table-text", "targets": "_all"},
                        {
                            "targets": 0,
                            "data": "id",
                            "searchable": false,
                            "sortable": false,
                            "defaultContent": ""
                        },
                        {
                            "targets": 1,
                            "data": "code",

                        },
                        {
                            "targets": 2,
                            "data": "created_at",

                        },
                        {
                            "targets": 3,
                            "data": "name",

                        },
                        {
                            "targets": 4,
                            "data": "mobile_no",

                        },
                        
                        {
                            "targets": 5,
                            "data": "complain",

                        },
                        {
                            "targets": 6,
                            "data": "to_forword",

                        },

                     <?php if (session()->get('user_type') == 0) { ?>   
                        
                        {
                   "targets": - 1,
                "data": 'action',
                "searchable": false,
                "sortable": false,
                "render": function (data, type, full, meta) {
                var str_btns = "";
                str_btns += '<button type="button"  class="btn btn-primary  view-button btn_new1" id="' + data.v + '" title="View"><i class="fa fa-eye"></i></button>&nbsp;';
                return str_btns;
                }
        }
             <?php } ?>

                    ],

            "order": [[1, 'asc']]
        });
        table.on('order.dt search.dt draw.dt', function () {
            $('[data-toggle="tooltip"]').tooltip();
            table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = table.page() * table.page.len() + (i + 1);
            });
        });
    }

    function redirectPost(url, data1) {
        var $form = $("<form />");
        $form.attr("action", url);
        $form.attr("method", "post");
        //         $form.attr("target", "_blank");
        for (var data in data1)
            $form.append('<input type="hidden" name="' + data + '" value="' + data1[data] + '" />');
        $("body").append($form);
        $form.submit();
    }
</script>

@endsection
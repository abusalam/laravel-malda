@extends('layout.master')
@section('content')
<div class="row" id="row-content">
    <div class="col-12">
        <div class="card">
            
            <div class="card-body">
                <h3 class="card-title">Grievance</h3>
                {!! csrf_field() !!}
                <div class="datatbl  " style="width: 96%;margin-left: 20px;">
                    <table class="table table-striped table-bordered table-hover notice-types-table" id="tbl_grievance_list" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 10%;">Name</th>              
                                <th style="width: 15%;">Mobile Number</th> 
                                <th style="width: 15%;">Email</th> 
                                <th style="width: 40%;">Complain</th>
                                <?php //if (session()->get('user_type') == 0) { ?>

                                    <th style="width: 10%;">Action</th>
                                </tr>
                            <?php //} ?>
                        </thead>
                        <tbody></tbody>
                        <!-- Table Footer -->
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
        create_table();
        var table = $('#tbl_grievance_list').DataTable();
        table.on('draw.dt', function () {
            $('.view-button').click(function () {
                var grievance_code = this.id;
                var token = $ ('input[name="_token"]').val();
                $.ajax({
                    url: "view_user",
                    method:'post',
                    type:'json',
                    data:{'grievance_code':grievance_code, _token:token},
                    success:function(data){
                        var str = "";
                        str += '<table  class="table table-sm table-bordered" id="forwardTable">';
                        str += '<tbody>';
                        str += '<tr><td><label> Name : </label></td><td>' + data.options.name + '</td></tr>';
                        str += '<tr><td><label> Mobile Number : </label></td><td>' + data.options.mobile_no + '</td></tr>';
                        str += '<tr><td><label> Email : </label></td><td>' + data.options.email + '</td></tr>';
                        str += '<tr><td><label> Complain : </label></td><td>' + data.options.complain + '</td></tr>';
												str += '<tr><td><label> Forwarded : </label></td><td>';
											  str += '<table class="table">';
												str += '<tr><th width="20%">User</th><th width="30%">Date</th><th width="50%">Remark</th></tr>';
												
//												$.each(data.remarkData, function(key, value){
//													str += '<tr><td>' + value['name'] + '</td>';
//													str += '<td>' + value.date + '</td>';
//													str += '<td>' + value.remark + '</td></tr>';
//												});

											for(i=0; i<data.remarkData.length; i++){
													str += '<tr><td>' + data.remarkData[i].name + '</td>';
													str += '<td>' + data.remarkData[i].date + '</td>';
													str += '<td>' + data.remarkData[i].remark + '</td></tr>';
												}
												str += '</table>';
												str += '</td></tr>';									
                        str += '<tr><td><label> User : </label></td><td>{!!Form::select('user',[''=>'Select All'],null,['id'=>'user','class'=>'form - control','placeholder'=>'Select All'])!!}</td></tr>';
												str += '<tr><td><label> Remark : </label></td><td>{!! Form::textarea('remark', null, ['class'=>'form-control', 'rows'=>'2','id'=>'remark']) !!}</td></tr>';
                        str += '</tbody>';
                        str += '</table>';
                        get_user();
                        $.confirm({
                            title: 'Grievance Forward',
                            content: str,
                            boxWidth: '80%',
                            useBootstrap: false,
                            buttons: {
                                forwoad: function(){
                                    var token = $("input[name='_token']").val();
                                    var to_forword = $("#user").val();
																		var remark = $("#remark").val();
																		var msg="";
																		var f = 0;
																		if(to_forword == ''){
																			msg+= '<li>Please Select User.</li>';
																			f=1;
																		}
//																		if(remark == ''){
//																			msg+= '<li>Please Enter Remark.</li>';
//																			f=1;
//																		}
//																		
                                    if (f==1){
                                        $.confirm({
                                            title: 'Warning!',
                                            type: 'orange',
                                            icon: 'fa fa-times',
                                            content: "<ul>"+msg+"</ul>",
                                            buttons: {
                                                ok: function () {
                                                }
                                            }
                                        });
                                    } else{
                                        $.ajax({
                                            url: "save_forword",
                                            method:'post',
                                            type: 'json',
                                            data: {'grievance_code':grievance_code, 'to_forword':to_forword, _token:token, remark:remark},
                                            success:function(data){
                                                if (data.status == 1){
                                                    $.confirm({
                                                        title: 'Success!',
                                                        type: 'green',
                                                        icon: 'fa fa-check',
                                                        content: "Forworded Successfully",
                                                        buttons: {
                                                            ok: function () {
                                                                create_table();
                                                            }
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    }
                                },
                                cancel: function (){}
                            }
                        });
                    }                
                });
            });
        });    
    });
    function get_user(){
    var token = $("input[name='_token']").val();
        $.ajax({
            url:"user_list",
            method:'post',
            type: 'json',
            data: { _token:token},
            success:function(data){      
                $('#user').html('<option value="">Select All</option>');
                $.each(data.options, function(key, value){
                    $("#user").append('<option value=' + key + '>' + value + '</option>');
                });
            }
        });
    }  
    
    function create_table() {
    var table = "";
    var token = $('input[name="_token"]').val();
    //var case_data=$("#case_number").val();




    $("#tbl_grievance_list").dataTable().fnDestroy();
    table = $('#tbl_grievance_list').DataTable({
    "responsive": true,
            bProcessing: true,
            bServerSide: true,
            bjQueryUI: true,
            "bInfo": false,
            "ajax": {
            url: "grievance_datatable",
                    type: "post",
                    data: {'_token': $('input[name="_token"]').val()},
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
            "data": "name",
    },
    {
    "targets": 2,
            "data": "mobile_no",
    },
    {
    "targets": 3,
            "data": "email",
    },
    {
    "targets": 4,
            "data": "complain",
    },
<?php //if (session()->get('user_type') == 0) { ?>

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
<?php //} ?>
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
@extends('layout.master')
@section('content')
<div class="row" id="row-content">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title"> {{__('text.closed_table_heading')}}</h3>
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
                                <th style="width: 15%;">Closed Date</th>
                                <th style="width: 15%;">Action</th>
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
$(document).ready(function() {
    create_table();

    var table = $('#tbl_grievance_list').DataTable();
    table.on('draw.dt', function() {
        $('.view-button').click(function() {

            var grievance_code = this.id;

            var token = $('input[name="_token"]').val();
            $.ajax({
                url: "view_user_for_forward",
                method: 'POST',
                type: 'json',
                data: { 'grievance_code': grievance_code, _token: token },
                success: function(data) {
if(data.logout_error==true){
                  logout_error();
                }
                    var str = "";
                    str += '<table  class="table table-sm table-bordered" id="forwardTable">';
                    str += '<tbody>';
                    str += '<tr><td><label>Grievance ID : </label></td><td>' + data.options.code + '</td></tr>';
                    str += '<tr><td><label> Name : </label></td><td>' + data.options.name + '</td></tr>';
                    str += '<tr><td><label> Mobile Number : </label></td><td>' + data.options.mobile_no + '</td></tr>';
                    str += '<tr><td><label> Email : </label></td><td>' + data.options.email + '</td></tr>';
                    str += '<tr><td><label> Complain : </label></td><td>' + data.options.complain + '</td></tr>';
                    str += '<tr><td><label> Attatchment : </label></td><td> <a href ="upload/grievance_attatchment/' + data.options.attatchment + '" target="_blank"> View Attatchment </a></td></tr>';
                    str += '<tr><td><label> Forwarded : </label></td><td>';
                    str += '<table class="table">';
                    str += '<tr><th width="20%">User</th><th width="30%">Date</th><th width="50%">Remark</th></tr>';


                    //                                              $.each(data.remarkData, function(key, value){
                    //                                                  str += '<tr><td>' + value['name'] + '</td>';
                    //                                                  str += '<td>' + value.date + '</td>';
                    //                                                  str += '<td>' + value.remark + '</td></tr>';
                    //                                              });

                    for (i = 0; i < data.remarkData.length; i++) {
                        str += '<tr><td>' + data.remarkData[i].name + '</td>';
                        str += '<td>' + data.remarkData[i].date + '</td>';
                        str += '<td>' + data.remarkData[i].remark + '</td></tr>';
                    }
                    str += '</table>';
                    str += '</td></tr>';
                    if (data.options.close_status == 1) {
                        str += '<tr><td>Close Status</label></td><td> Closed </td></tr>';

                        if (data.options.remark == null) {
                            str += '<tr><td>Close Remark</label></td><td>N/A</td></tr>';
                        } else {
                            str += '<tr><td>Close Remark</label></td><td>' + data.options.remark + '</td></tr>';
                        }
                    }





                    str += '</tbody>';
                    str += '</table>';

                    $.confirm({
                        title: 'Closed Grievance ',
                        content: str,
                        boxWidth: '80%',
                        useBootstrap: false,
                        buttons: {

                            cancel: function() {}
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
    $("#tbl_grievance_list").dataTable().fnDestroy();
    table = $('#tbl_grievance_list').DataTable({
        "responsive": true,
        bProcessing: true,
        bServerSide: true,
        bjQueryUI: true,
        "bInfo": false,

        "ajax": {
            url: "closed_grievance_datatable",
            type: "post",
            data: { '_token': $('input[name="_token"]').val() },
            dataSrc: "record_details",
            error: function(jqXHR, textStatus, errorThrown) {
                var msg = "";
                if (jqXHR.status !== 422 && jqXHR.status !== 400) {
                    msg += "<strong>" + jqXHR.status + ": " + errorThrown + "</strong>";
                } else {
                    if (jqXHR.responseJSON.hasOwnProperty('exception')) {
                        if (jqXHR.responseJSON.exception_code == 23000) {
                            msg += "Server Error";
                        } else {
                            msg += "Server Error";
                        }
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
            }
        },
        "dataType": 'json',
        "columnDefs": [
            { className: "table-text", "targets": "_all" },
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
                "sortable": true,

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
                "data": "updated_at",

            },
            {
                "targets": -1,
                "data": 'action',
                "searchable": false,
                "sortable": false,
                "render": function(data, type, full, meta) {
                    var str_btns = "";
                    str_btns += '<button type="button"  class="btn btn-primary  view-button btn_new1" id="' + data.v + '" title="View Closed Grievance"><i class="fa fa-eye"></i></button>&nbsp;';
                    return str_btns;
                }
            }




        ],

        "order": [
            [1, 'asc']
        ]
    });
    table.on('order.dt search.dt draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
        table.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
            cell.innerHTML = table.page() * table.page.len() + (i + 1);
        });
    });
}



</script>
@endsection

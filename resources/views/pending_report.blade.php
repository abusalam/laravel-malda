@extends('layout.master')
@section('content')
<div class="row" id="row-content">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Pending Grievance</h3>
                {{csrf_field()}}
                <div class="datatbl  " style="width: 96%;margin-left: 20px;">
                    <table class="table table-striped table-bordered table-hover notice-types-table" id="tbl_grievance_list" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 40%;">User</th>
                                <th style="width: 40%;">Designation</th>
                                <th style="width: 40%;">No of Grievance</th>
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

            var user_code = this.id;


            var token = $('input[name="_token"]').val();
            $.ajax({
                url: "show_pending_grievance",
                method: 'POST',
                type: 'json',
                data: { 'user_code': user_code, _token: token },
                success: function(data) {
                if(data.logout_error==true){
                  logout_error();
                }
                    var i = 0;

                    var tbl = '<table id="user_details" class="table table-striped table-hover dataTable"  >';
                    tbl += '<thead >';
                    tbl += '<tr>';
                    tbl += '<th style="width: 3%;">SL#</th>';
                    tbl += '<th style="width: 10%;">ID</th>';
                    tbl += '<th style="width: 15%;">Name</th>';
                    tbl += '<th style="width: 15%;">Complain</th>';

                    tbl += '</tr>';
                    tbl += '</thead>';

                    //tbl+='</table>';



                    $.each(data.record, function(key, value) {
                        i = i + 1;
                        tbl += '<tbody>';
                        tbl += '<tr>';
                        tbl += '<td>' + i + '</td>';
                        tbl += '<td>' + value.code + '</td>';
                        tbl += '<td>' + value.name + '</td>';
                        tbl += '<td>' + value.complain + '</td>';

                        tbl += '</tr>';
                        tbl += '</tbody>';

                        //$("#tbl_body").append(tbl_body);
                        //alert(tbl_body);



                    });
                    tbl += '</table>';
                    //link.addClass("link");

                    $.confirm({
                        title: '',

                        boxWidth: '60%',
                        useBootstrap: false,
                        content: tbl,
                        buttons: {
                            ok: function() {


                            }
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
            url: "pending_grievance_datatable",
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
                "sortable": true,
                "defaultContent": ""
            },
            {
                "targets": 1,
                "data": "name",
                "sortable": true,

            },
            {
                "targets": 2,
                "data": "designation",
                "sortable": true,

            },
            {
                "targets": 3,
                "data": "griv_code",
                "sortable": true,


            },
            {
                "targets": -1,
                "data": 'action',
                "searchable": false,
                "sortable": false,
                "render": function(data, type, full, meta) {
                    var str_btns = "";
                    str_btns += '<button type="button"  class="btn btn-primary  view-button btn_new1" id="' + data.v + '" title="View pending Grievance"><i class="fa fa-eye"></i></button>&nbsp;';
                    return str_btns;
                }
            }





        ],

       "order": [[1, 'asc']]
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

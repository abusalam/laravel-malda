@extends('layout.master')
@section('content')
<div class="row" id="row-content">
    <div class="col-12">                        
        <div class="card">
            <div class="card-body">
                <h3 class="card-title"> Closed Grievance</h3>
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
                                <th style="width: 15%;">Closed Date</th>
                                


                                

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
        create_table();

        


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
                        {
                            "targets": 7,
                            "data": "updated_at",

                        }
                        
                        
                       
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
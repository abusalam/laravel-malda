@extends('layout.frontmaster')
@section('content')


<div class="row" id="row-content">
    <div class="col-12">                        
        <div class="card">
            <div class="card-body">
               
                    
                    <div class="push-right" style="margin-bottom: 20px;">
                        <a class="btn btn-info" href="{{route('search_case')}}" >Search Case by Number</a>
                    </div>
                    <div class="push-left" style="margin-bottom: 20px;">
                        <h3 class="card-title">{{__('text.todays_hearing')}}</h3>
                    </div>
                    
                
                
                
                {{csrf_field()}}
                  <div class="datatbl  " style="width: 96%;margin-left: 20px;">
                    <table class="table table-striped table-bordered table-hover notice-types-table" id="tbl_case_list" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 15%;">Case Number</th>              
                                <th style="width: 20%;">Next Hearing Date</th> 
                                <th style="width: 45%;">Description</th>   
                                
                            </tr>

                        </thead>
                        <tbody></tbody>
                        <!-- Table Footer -->
                    </table>
                </div>

                
                <div id="tbl_t" style="padding-left: 10px;">
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

        $("#tbl_case_list").dataTable().fnDestroy();
        table = $('#tbl_case_list').DataTable({
            "responsive": true,
            bProcessing: true,
            bServerSide: true,
            bjQueryUI: true,
            "bInfo": false,
            "searching": false,

            "ajax": {
                url: "caselist_datatable_for_todays_hearing",
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
                                msg += "Server Error";
                            } else {
                                msg += "Server Error";
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
                            "data": "case_no",

                        },
                        {
                            "targets": 2,
                            "data": "nxt_hearing_date",

                        },
                        {
                            "targets": 3,
                            "data": "description",

                        },

                        
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

</script> 
@endsection
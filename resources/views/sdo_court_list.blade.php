@extends('layout.master')
@section('content')


<div class="row" id="row-content">
    <div class="col-12">                        
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">{{__('text.case_list')}}</h3>
                {{Form::open(['name'=>'case_search','id'=>'case_search','url' => '', 'method' => 'post'])}}
                <div class="form-group row ">
                    <div class="col-sm-1">&nbsp;</div>
                    <div class="col-sm-2 mg-t-10">{{Form::label('case_number', __('text.case_number'), ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}</div>
                    <div class="col-sm-4">
                        {{Form::text('case_number', '', ['id'=>'case_number','placeholder'=>__('text.enter_case_number'),'autocomplete'=>'off', 'class' => 'form-control']) }}
                    </div>
                    <div class="col-sm-3">
                        {{Form::button( __('text.search'), ['type'=>'button','id'=>'Search','class' => 'btn btn-primary btn-block']) }}
                    </div>
                    <div class="col-sm-2">&nbsp;</div>
                </div>
                {!! Form::close() !!}

                <div class="datatbl  " style="width: 96%;margin-left: 20px;">
                    <table class="table table-striped table-bordered table-hover notice-types-table" id="tbl_case_list" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 15%;">Case Number</th>              
                                <th style="width: 20%;">Next Hearing Date</th> 
                                <th style="width: 45%;">Description</th>   
                                <th style="width: 15%;">Action</th>
                            </tr>

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

        $("#Search").click(function () {

            create_table();

        });

        var table = $('#tbl_case_list').DataTable();
        table.on('draw.dt', function () {

            $('.edit-button').click(function () {
                var case_code = this.id;

               var token = $('input[name="_token"]').val();
             if((/^([1-9]{1,})$/.test(case_code)) && token != '' ){
      
                var datas = {'case_code': case_code, '_token': $('input[name="_token"]').val()};
                redirectPost('{{url("case_edit")}}', datas);
            }else{
                location.reload();
             }
            
            });

            $('.delete-button').click(function () {

                var reply = confirm('Are you sure to delete the record?');
                if (!reply) {
                    return false;
                }
                var case_code = this.id;
                // alert(road_challan_code);
                $.ajax({
                    type: 'post',
                    url: 'case_delete',
                    data: {'case_code': case_code, '_token': $('input[name="_token"]').val()},
                    dataType: 'json',
                    success: function (datam) {
                      if(datam.logout_error==true){
                  logout_error();
                }
                        if (datam.status == 1) {
                            create_table();
                            $.alert({
                                type: 'green',
                                icon: 'fa fa-check',
                                title: 'Success!!',
                                content: '<strong>SUCCESS:</strong> User deleted successfully.'
                            });
                        } else {
                            $.alert({
                                type: 'red',
                                icon: 'fa fa-warning',
                                title: 'Error!!',
                                content: '<strong>SUCCESS:</strong> Failed to delete data.'
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        var msg = "<strong>Failed to Delete data.</strong><br/>";
                        if (jqXHR.status !== 422 && jqXHR.status !== 400) {
                            msg += "<strong>" + jqXHR.status + ": " + errorThrown + "</strong>";
                        } else {
                            if (jqXHR.responseJSON.hasOwnProperty('exception')) {
                                if (jqXHR.responseJSON.exception_code == 23000) {
                                    msg += "Data Already Used!! Cannot Be Deleted.";
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
                            type: 'red',
                            icon: 'fa fa-warning',
                            title: 'Error!!',
                            content: msg
                        });

                    }
                });
            });


        });





    });






    function create_table() {
        var table = "";
        var token = $('input[name="_token"]').val();

        var case_data = $("#case_number").val();




        $("#tbl_case_list").dataTable().fnDestroy();
        table = $('#tbl_case_list').DataTable({
            "responsive": true,
            bProcessing: true,
            bServerSide: true,
            bjQueryUI: true,
            "bInfo": false,

            "ajax": {
                url: "caselist_datatable",
                type: "post",
                data: {'case_data': case_data, '_token': $('input[name="_token"]').val()},
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

                        {
                            "targets": -1,
                            "data": 'action',
                            "searchable": false,
                            "sortable": false,
                            "render": function (data, type, full, meta) {
                                var str_btns = "";
                                str_btns += '<button type="button"  class="btn btn-success  edit-button btn_new1" id="' + data.e + '" title="Edit"><i class="fa fa-edit"></i></button>&nbsp;';

                                str_btns += '<button type="button"  class="btn btn-info delete-button btn_new1" id="' + data.d + '" title="Delete"><i class="fa fa-trash"></i></button>&nbsp;';



                                return str_btns;
                            }
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
            $form.append('<input type="text" name="' + data + '" value="' + data1[data] + '" />');
        $("body").append($form);
        $form.submit();
    }
</script>



@endsection 
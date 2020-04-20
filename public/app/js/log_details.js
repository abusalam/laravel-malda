



    $(document).ready(function () {




        var table = $('#tbl_log_details').DataTable();
        table.on('draw.dt', function () {

            $(".view-button").click(function() {
                        var code = this.id;
                        var token = $('input[name="_token"]').val();
                    
                        $.ajax({
                            url: 'logview',
                            method: 'POST',
                            data: {
                                'code': code,
                                '_token': token,
                            },
                            success: function(datas) {

                                var str = "";
                                str += '<table class="table table-sm table-3">';
                                str += '<tbody>';
                                if(datas.options.name == null){
                                    str += '<tr><td width="40%">Name : </td><td width="60%">Anonymous USer</td></tr>';
                                }else{
                                    str += '<tr><td>  Name : </td><td>' + datas.options.name + '</td></tr>';
                                }
                                if(datas.options.mobile_no == null){
                                    str += '<tr><td> Mobile No : </td><td>NA</td></tr>';
                                }
                                else{
                                    str += '<tr><td>  Mobile No : </td><td>' + datas.options.mobile_no + '</td></tr>';

                                }
                                
                                if(datas.options.mobile_no == null){
                                    str += '<tr><td> Designation : </td><td>NA</td></tr>';
                                }
                                else{
                                    str += '<tr><td> Designation : </td><td>' + datas.options.designation + '</td></tr>';

                                }

                                
                                str += '<tr><td>  Session Id : </td><td>' + datas.options.sessionId + '</td></tr>';
                                str += '<tr><td>  User Ip : </td><td>' + datas.options.userIp + '</td></tr>';
                                str += '<tr><td>  Visited Page : </td><td>' + datas.options.visitedPage + '</td></tr>';
                                str += '<tr><td>  Browser : </td><td>' + datas.options.browser + '</td></tr>';
                                str += '<tr><td>  Description : </td><td>' + datas.options.description + '</td></tr>';
                                str += '<tr><td>  Created At : </td><td>' + datas.options.created + '</td></tr>';
                                str += '<tr><td>  Updated At : </td><td>' + datas.options.updated + '</td></tr>';
                                str += '</tbody>';
                                str += '</table>';
                                $('#view_log_details').html("");
                                    $('#view_log_details').append(str);
                                    $('#log').modal('show');

                            },

                        });

                    });
        });









 });


         $("#tbl_log_details").dataTable().fnDestroy();
        table = $('#tbl_log_details').DataTable({
            "responsive": true,
            bProcessing: true,
            bServerSide: true,
            bjQueryUI: true,
            "bInfo": false,
            

            "ajax": {
                url: "logdetails",
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
                            "data": "userCode",

                        },
                    
                        {
                            "targets": 2,
                            "data": "userIp",

                        },
                        {
                            "targets": 3,
                            "data": "visitedPage",

                        },
                        {
                            "targets": 4,
                            "data": "browser",

                        },
                       
                        {
                            "targets": 5,
                            "data": "created_at",

                        },

                        {
                            "targets": -1,
                            "data": 'action',
                            "searchable": false,
                            "sortable": false,
                            "render": function (data, type, full, meta) {
                               var str_btns="";
                               
                               str_btns += '<button type="button"  class="btn btn-info  view-button" id="' + data.v+ '" title="View"><i class="fa fa-view fa-eye"></i></button>&nbsp;';




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
        
    

  

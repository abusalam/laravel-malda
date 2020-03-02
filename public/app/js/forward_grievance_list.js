
    $(document).ready(function () {

         var lang_en_bn=$("#language_en_bn").val();
          if(lang_en_bn == "en" || lang_en_bn == ""){
         
            var to_date_should_be_greater_than_from_date="To date should be greater than from date";
            var from_date_should_be_lesser_than_to_date="From date should be lesser than to date";
           
           
   }else if(lang_en_bn == "bn"){

           var to_date_should_be_greater_than_from_date="To Date , From Date চেয়ে বেশি হওয়া উচিত";
           var from_date_should_be_lesser_than_to_date="From Date , To Date চেয়ে কম হওয়া উচিত";
           
           


   }

        



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
                alert(to_date_should_be_greater_than_from_date);
                document.getElementById("to_date").value = "";
                $('.has-error').addClass('has-error');
                $('#case_search').data('bootstrapValidator').updateStatus('to_date', 'INVALID', null)
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

                alert(from_date_should_be_lesser_than_to_date);
                document.getElementById("from_date").value = "";
                $('.has-error').addClass('has-error');
                $('#case_search').data('bootstrapValidator').updateStatus('from_date', 'INVALID', null)
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
                    method:'POST',
                    type:'json',
                    data:{'grievance_code':grievance_code, _token:token},
                    
                    success:function(data){
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
                        str += '<tr><td><label> Attatchment : </label></td><td> <a href ="upload/grievance_attatchment/'+ data.options.attatchment + '" target="_blank"> View Attatchment </a></td></tr>';
                                                str += '<tr><td><label> Forwarded : </label></td><td>';
                                              str += '<table class="table">';
                                                str += '<tr><th width="20%">User</th><th width="20%">Date</th><th width="50%">Remark</th><th width="10%">Attachment</th></tr>';


//                                              $.each(data.remarkData, function(key, value){
//                                                  str += '<tr><td>' + value['name'] + '</td>';
//                                                  str += '<td>' + value.date + '</td>';
//                                                  str += '<td>' + value.remark + '</td></tr>';
//                                              });

                                            for(i=0; i<data.remarkData.length; i++){
                                                    str += '<tr><td>' + data.remarkData[i].name + '</td>';
                                                    str += '<td>' + data.remarkData[i].date + '</td>';
                                                    str += '<td>' + data.remarkData[i].remark + '</td>';
                                                    if(data.remarkData[i].attatchment == null){
                                                     str += '<td> N/A </td></tr>';
                                                    }else{
                                                    str += '<td> <a href ="upload/forward_attatchment/'+ data.remarkData[i].attatchment + '" target="_blank"> View </a></td></tr>';
                                                }
                                            }


                                                
                                                str += '</table>';
                                                str += '</td></tr>';                                    
                        
                        str += '</tbody>';

                        str += '</table>';
                        

                        $.confirm({
                            title: 'Grievance Forward',
                            content: str,
                            boxWidth: '80%',
                            useBootstrap: false,
                            buttons: {
                                
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
                            "data": "to_forword",

                        },

        //           
                        
                        {
                   "targets": - 1,
                "data": 'action',
                "searchable": false,
                "sortable": false,
                "render": function (data, type, full, meta) {

                var str_btns = "";
                str_btns += '<button type="button"  class="btn btn-primary  view-button btn_new1" id="' + data.v + '" title="Forwarded Grievance"><i class="fa fa-eye"></i></button>&nbsp;';
                return str_btns;
                }
        }
        //     

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

   

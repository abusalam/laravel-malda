

    var close;
    $(document).ready(function () {

        var lang_en_bn=$("#language_en_bn").val();
          if(lang_en_bn == "en" || lang_en_bn == ""){
         
            var grievance_closed_successfully="Grievance saved successfully.";
            var check_confirmation_for_close="Please Check confirmation for Close the Grievance";
           
           
   }else if(lang_en_bn == "bn"){

           
            var grievance_closed_successfully="অভিযোগ সফলভাবে সংরক্ষণ করা হয়েছে।";
            var check_confirmation_for_close="অভিযোগ বন্ধ করার জন্য দয়া করে নিশ্চিতকরণ পরীক্ষা করুন";
           


   }

        create_table();

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
                        str += '<tr><td><label> Attachment : </label></td><td> <a href ="upload/grievance_attatchment/'+ data.options.attatchment + '" target="_blank"> View Attachment </a></td></tr>';
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
                                                

                          if($("#session_data").val()==0){
                         str += '<tr><td colspan="2">';
                         str += '<table class="table table-bordered table-striped">'
                        
                        
                        str+= '<div class="text-center alert-danger"><h2>Grievance Close</h2></div>';
                          
                          
                        
                          
                         str += '<tr><td><label> Remarks : </label></td><td><textarea id="remark" rows="4" cols="300" autocomplete="off" class="form-control" maxlength="300"></textarea></td></tr>';
                         str+= ' <tr><td colspan="2"><input type="checkbox" class="form-check-input" style="width: 20px;height: 20px;" id="check">&emsp;&emsp;<label class="form-check-label" for="check">I Agree to Close The Grievance</label><div class="pull-right"><button value="'+grievance_code+'" id="closed_submit" class="btn btn-danger grievance_close"><span style="font-size: 20px">Close<span></button></div></td></tr>';

                         str += '</table>'
                         str += '</td></tr>';
                     }

                        // <?php  } ?>

                         
                         
                           

                        str += '</tbody>';

                        str += '</table>';
                        

                      close=  $.confirm({
                            title: 'Close Grievance',
                            content: str,
                            boxWidth: '80%',
                            useBootstrap: false,
                            buttons: {
                                
                                cancel: function (){}
                            },

                        });
                      close.open();

                      $("#closed_submit").click(function(){

                        var closed_data = $("#closed_submit").val();
                        close_grievance(closed_data,grievance_closed_successfully,check_confirmation_for_close);


                      });

                    }
                });


            });
        });

        


    });

    function close_grievance(grievance_code,grievance_closed_successfully,check_confirmation_for_close){
    if($("#check").prop('checked') == true){

        var token = $("input[name='_token']").val();
                                    var remark=$("#remark").val();
                                    
                                   
                                        $.ajax({
                                            url: "close_grievance",
                                            method:'post',
                                            type: 'json',
                                            data: {'grievance_code':grievance_code,'remark':remark, _token:token},
                                            success:function(data){
                                              if(data.logout_error==true){
                  logout_error();
                }
                                                if (data.status == 1){
                                                    $.confirm({
                                                        title: 'Success!',
                                                        type: 'green',
                                                        icon: 'fa fa-check',
                                                        content: grievance_closed_successfully,
                                                        buttons: {
                                                            ok: function () {

                                                                create_table();
                                                               
                                                            }
                                                        }
                                                    });
                                                }
                                            }
                                        });

    
}else{

$.confirm({
        title: 'Unsuccess !!',
        content: check_confirmation_for_close,
        boxWidth: '20%',
        useBootstrap: false,
        buttons: {
            
            cancel: function (){}
        }
    });



}
close.close();

    
}
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
                url: "resolve_grievance_datatable",
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

    

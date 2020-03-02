
    $(document).ready(function () {

        var lang_en_bn=$("#language_en_bn").val();
   
   if(lang_en_bn == "en" || lang_en_bn == ""){
         
          var want_to_delete="Are you sure to delete the record?";
          var user_delete="User deleted successfully.";
          var failed_to_delete_data="failed to Delete data. Try again";
           
   }else if(lang_en_bn == "bn"){

          var want_to_delete="আপনি কি রেকর্ডটি মোছার বিষয়ে নিশ্চিত?";
          var user_delete="ব্যবহারকারী সফলভাবে মোছা হয়েছে।";
          var failed_to_delete_data="ডেটা মুছতে ব্যর্থ। আবার চেষ্টা কর";

     

   }


        create_table();

        // $("#search_button").click(function(){

        // 	create_table();

        // });

        var table = $('#tbl_user_list').DataTable();
        table.on('draw.dt', function () {

            $('.edit-button').click(function () {
                var user_code = this.id;
                var token = $('input[name="_token"]').val();
             if((/^([1-9]{1,})$/.test(user_code)) && token != '' ){
                

                var datas = {'user_code': encodeURI(user_code), '_token': encodeURI($('input[name="_token"]').val())};
                redirectPost('user_edit', datas);

               
             }else{
                location.reload();
             }

                
            });

            $('.delete-button').click(function () {

                var reply = confirm(want_to_delete);
                if (!reply) {
                    return false;
                }
                var user_code = this.id;
                // alert(road_challan_code);
                $.ajax({
                    type: 'post',
                    url: 'user_delete',
                    data: {'user_code': user_code, '_token': $('input[name="_token"]').val()},
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
                                content: user_delete
                            });
                        } else {
                            $.alert({
                                type: 'red',
                                icon: 'fa fa-warning',
                                title: 'Error!!',
                                content: failed_to_delete_data
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



        $("#tbl_user_list").dataTable().fnDestroy();
        table = $('#tbl_user_list').DataTable({
            "responsive": true,
            bProcessing: true,
            bServerSide: true,
            bjQueryUI: true,
            "bInfo": false,

            "ajax": {
                url: "userlist_datatable",
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
                            "sortable": true,
                            "data": "name",

                        },
                        {
                            "targets": 2,
                            "sortable": true,
                            "data": "mobile_no",

                        },
                        {
                            "targets": 3,
                            "sortable": true,
                            "data": "designation",

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
        $form.attr("name", "editForm");
        $form.attr("id", "editForm");
        $form.attr("method", "post");
        //         $form.attr("target", "_blank");
        for (var data in data1)
        {
            $form.append('<input type="hidden" name="' + data + '" id="' + data + '" value="' + data1[data] + '" />');
        }
        $("body").append($form);

        var user_code = encodeURI($('#user_code').val());
        var token = encodeURI($('#_token').val());
        if((/^([1-9]{1,})$/.test(user_code)) && token != '' ){              
            $form.submit();               
        }else{
            location.reload();
        }              
    }


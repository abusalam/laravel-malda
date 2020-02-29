
    $('#refresh').click(function () {
        $.ajax({
            type: 'POST',
            url: 'refreshcaptcha',
            data: {_token: $('input[name="_token"]').val()},
            dataType: 'json',
            success: function (data) {
              if(data.logout_error==true){
                  logout_error();
                }
                $(".captcha span").html(data.captcha);
                $("#case_number").val('');
                $("#captcha").val('');
            }
        });
    });

    $("#Search").click(function () {

        var case_number = $("#case_number").val();
        var capcha = $("#captcha").val();
        $.ajax({
            type: 'POST',
            url: 'capchavalidation',
            data: {'_token': $('input[name="_token"]').val(), 'case_number': case_number, 'capcha': capcha},
            success: function (data) {
              if(data.logout_error==true){
                  logout_error();
                }
                if(data.status==1){
                $("#search_data").hide();

                var i = 0;

                var tbl = '<table id="user_details" class="table table-striped table-hover dataTable"  >';
                tbl += '<thead >';
                tbl += '<tr>';
                tbl += '<th style="width: 3%;">SL#</th>';
                tbl += '<th style="width: 20%;">Case Number</th>';
                tbl += '<th style="width: 20%;">Next Hearing Date</th>';
                tbl += '<th style="width: 50%;">Description</th>';

                tbl += '</tr>';
                tbl += '</thead>';

                //tbl+='</table>';



                $.each(data.options, function (key, value) {
                    i = i + 1;
                    tbl += '<tbody>';
                    tbl += '<tr>';
                    tbl += '<td>' + i + '</td>';
                    tbl += '<td>' + value.case_no + '</td>';
                    tbl += '<td>' + value.nxt_hearing_date + '</td>';
                    tbl += '<td>' + value.description + '</td>';


                    tbl += '</tr>';
                    tbl += '</tbody>';



                });
                tbl += '</table>';
                //link.addClass("link");

                $("#tbl_t").append(tbl);
            }else{

                $.alert({
                    title: 'Error!!',
                    type: 'red',
                    icon: 'fa fa-warning',
                    content: "Case Number is not Valid",
                });
            }




            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(".se-pre-con").fadeOut("slow");
                var msg = "";
                if (jqXHR.status !== 422 && jqXHR.status !== 400) {
                    msg += "<strong>" + jqXHR.status + ": " + errorThrown + "</strong>";
                } else {
                    if (jqXHR.responseJSON.hasOwnProperty('exception')) {
                        msg += "Server Error";
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
                //$("#save_app").attr('disabled',false);
            }

        });

    });








        $("#logout_user").click(function(){

               var token = $('input[name="_token"]').val();
             if(token != '' ){
      
                var datas = {'_token': encodeURI($('input[name="_token"]').val())};
                redirectPost('logout', datas);
            }else{
                location.reload();
             }

      });

      function redirectPost(url, data1) {
        var $form = $("<form />");
        $form.attr("action", url);
        $form.attr("method", "post");
        //         $form.attr("target", "_blank");
        for (var data in data1)
            $form.append('<input type="hidden" name="' + data + '"  id="' + data + '" value="' + data1[data] + '" />');
        $("body").append($form);
                
        var token = encodeURI($('#_token').val());
        if( token != '' ){              
            $form.submit();               
        }else{
            location.reload();
        }  
        
    }
   
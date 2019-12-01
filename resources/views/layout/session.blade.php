<link rel="stylesheet" href="./front/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('/css/jquery-confirm.min.css') }}">

<script src="./front/js/jquery-3.4.1.min.js"></script>
<script src="{{ asset('/js/jquery-confirm.min.js') }}"></script>

   <script>
          $(document).ready(function(){
              $.confirm({
   title: '<span style="color:red;">OPPS!</span>',
   content: '<span style="color:green;">Session has Expired. </span>',
   buttons: {
     
     
       Login: {
           btnClass: 'btn-red',
           action: function(){
               window.location="./";
           }
       }
   }
});
          });
          </script>
           
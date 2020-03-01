<link rel="stylesheet" href="./front/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('/css/style.css')}}">
<link rel="stylesheet" href="{{ asset('/css/jquery-confirm.min.css') }}">

<script src="./front/js/jquery-3.4.1.min.js"></script>
<script src="{{ asset('/js/jquery-confirm.min.js') }}"></script>


   <script>
          $(document).ready(function(){
              $.confirm({
   title: '<span class="alert-title">OPPS!</span>',
   content: '<span class="alert-body">Session has Expired. </span>',
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

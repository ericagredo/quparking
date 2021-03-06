<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">
  
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.css') }}">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="{{ asset('plugins/timepicker/bootstrap-timepicker.min.css') }}">
  
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
  
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{ asset('plugins/iCheck/all.css') }}">
  
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('css/AdminLTE.css') }}">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
  -->
  <link rel="stylesheet" href="{{ asset('css/skin-blue.min.css') }}">
  
  <!-- Datatable css  ---->
  <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables/responsive.bootstrap.min.css') }}">
  
  <!----- Responsive css ------->
  <link rel="stylesheet" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">

  <!-- Custom css ---->
  <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">

  @yield('css')

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini">

 
<div class="wrapper">
 <!-- Main Header -->
 @include('layouts.header')
 
 <!-- Left side column. contains the logo and sidebar -->
 @include('layouts.sidebar')
    
 @yield('content')
   
  <!-- Main Footer -->
 @include('layouts.footer')
</div>  

 
<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->
<script src="{{ asset('plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('js/bootstrap.min.js') }}"></script>

<!-- date-range-picker -->
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

<!-- bootstrap datepicker -->
<script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<!-- bootstrap color picker -->
<script src="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.js') }}"></script>

<!-- bootstrap time picker -->
<script src="{{ asset('plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>

<!-- iCheck 1.0.1 -->
<script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>


<!-- AdminLTE App -->
<script src="{{ asset('js/app.min.js') }}"></script>

<!-- For Form Validation ---->
<script src="{{ asset('js/bootstrapValidator.min.js') }}"></script> 
<script src="{{ asset('js/custom_validate.js') }}"></script> 
<!-- <script src="{{ asset('js/custom.js') }}"></script>  --->  
<script src="{{ asset('js/grid_function.js') }}"></script> 

<!-- Datatable js  ---->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.columnFilter.js') }}"></script>

<!----- Responsive js ------->
<script src="{{ asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>

<!-- ComboDate js  ---->
<script src="{{ asset('plugins/combodate/combodate.js') }}"></script>

<script src="{{ asset('js/date-timepicker.js') }}"></script>
<script src="{{ asset('js/common.js') }}"></script>

<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
	
	//Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
	//Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });
	
	//Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
	
	//Colorpicker
    $(".my-colorpicker").colorpicker();
	
	//iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
  });

  /*AJAX for admin notification*/
  var login = '<?php echo $_COOKIE['username']; ?>';
  if(login){
      function notify() {
          $.ajax({
              type: "GET",
              url: '/admin/notification_admin',
              async: true,
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(data) {
                  var data = JSON.parse(data);
                  if(data){
                      $(".noti_count").html(data.all_count);
                      $(".noti_manu").html('') ;
                      if(data.new_spot > 0){
                          var html =  '<li>';
                          html += '<a href="/parkingspot/parkingspotList" onclick="show_notification(\'new_spot\')">';
                          html += '<i class="fa fa-users text-aqua"></i> '+data.new_spot+' New spot added.';
                          html += '</a>';
                          html += '</li>';
                          $(".noti_manu").append(html) ;
                      }
                      if(data.new_booking > 0){
                          var html =  '<li>';
                          html += '<a href="/booking/managebooking" onclick="show_notification(\'new_booking\')">';
                          html += '<i class="fa fa-shopping-cart text-green"></i> '+data.new_booking+' New booking added.';
                          html += '</a>';
                          html += '</li>';
                          $(".noti_manu").append(html) ;
                      }
                      if(data.cancelled_booking > 0){
                          var html =  '<li>';
                          html += '<a href="/refund/RefundListAdmin" onclick="show_notification(\'cancelled_booking\')">';
                          html += '<i class="fa fa-user text-red"></i> '+data.cancelled_booking+' booking is cancelled.';
                          html += '</a>';
                          html += '</li>';
                          $(".noti_manu").append(html) ;
                      }
                      if(data.surcharge_amount > 0){
                          var html =  '<li>';
                          html += '<a href="/booking/managebooking" onclick="show_notification(\'surcharge_amount\')">';
                          html += '<i class="fa fa-warning text-yellow"></i> '+data.surcharge_amount+' booking surcharge added.';
                          html += '</a>';
                          html += '</li>';
                          $(".noti_manu").append(html) ;
                      }

                  }
              }
          });
          setTimeout(function(){ notify(); }, 20000);
      }
      notify();
  }
  function show_notification(pars) {
      $.ajax({
          type: "POST",
          url: '/admin/notification_admin_show',
          data: "notification_for=" + pars,
          async: true,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (data) {
              if (data == 1) {
                  notify();
              }
          }
      });
  }
  /*AJAX for admin notification*/
</script>

@yield('javascript')

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>

@stack('scripts')  <!---- Added For You Can use added script on View page ------->

</html>
    
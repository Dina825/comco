<html xmlns="http://www.w3.org/1999/xhtml">
<title><?php echo $title?></title>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<link href="<?php echo URL::to('assets')?>/css/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo URL::to('assets')?>/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo URL::to('assets')?>/css/style-responsive.css" rel="stylesheet" type="text/css" />
<link href="<?php echo URL::to('assets')?>/css/on_off.css" rel="stylesheet" type="text/css" />
<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets')?>/datatables/media/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets')?>/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets')?>/css/fixedHeader.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets')?>/css/pretty-checkbox.css">
<link rel="stylesheet" href="<?php echo URL::to('assets')?>/css/res-menu/menu.css">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ?>">
<script type="text/javascript" src="<?php echo URL::to('assets')?>/js/jquery.min-1-9-1.js"></script>
<script type="text/javascript" src="<?php echo URL::to('assets')?>/js/jquery.validate.js"></script>
<script src="<?php echo URL::to('assets/vendors/moment/min/moment.min.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo URL::to('assets')?>/js/popper.min.js"></script>
<script src="<?php echo URL::to('assets')?>/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo URL::to('assets')?>/datatables/media/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo URL::to('assets')?>/datatables-responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo URL::to('assets')?>/js/jquery.dataTables.min.js"></script>
<script src="<?php echo URL::to('assets')?>/js/dataTables.fixedHeader.min.js"></script>

</head>
<body>
<div class="d-flex justify-content-center loading_css" >
  <div class="spinner-border" role="status">
    <span class="sr-only">Loading...</span>
  </div>
</div>


<div class="container-fluid wrap push">

<div class="row">
  <div class="col-lg-1 col-md-1 col-sm-1 col-1 left_panel">
   
  <div class="logo_icon">
    <a href="<?php echo URL::to('admin/dashboard/')?>"><img src="<?php echo URL::to('assets')?>/images/logo.png" style="width: 100%"></a>
  </div>
  <div class="welcome_text">
    Welcome<br/>
    <span>Admin</span><br/>
    <a href="<?php echo URL::to('admin/logout')?>">Logout</a>
  </div>
  <div class="menu">
    <ul>
      <?php
      $segment = Request::segment(2); ?>
      <li><a href="<?php echo URL::to('admin/area')?>"><i class="fas fa-globe-europe"></i>Manage<br/>Area / Route</a></li>
      <li><a href="<?php echo URL::to('admin/area_manager')?>"><i class="fas fa-user-secret"></i>Manage Area Manager</a></li>     
      <li><a href="<?php echo URL::to('admin/sales_rep')?>"><i class="fas fa-users"></i>Manage Sales REP</a></li>      
      <li><a href="<?php echo URL::to('admin/shop')?>"><i class="fas fa-building"></i>Manage Shop</a></li>
      <li><a href="<?php echo URL::to('admin/accessories')?>"><i class="fas fa-box-open"></i>Manage Accessories</a></li>
      <li><a href="<?php echo URL::to('admin/shop_month_on_month')?>"><i class="fas fa-calendar-alt"></i>Month on Month</a></li>
      <li><a href="<?php echo URL::to('admin/commission')?>"><i class="fas fa-pound-sign"></i>Manage Commission</a></li>      
      <li><a href="<?php echo URL::to('admin/simcards')?>"><i class="fas fa-sim-card"></i>Manage Simacards</a></li>      
      <li><a href="<?php echo URL::to('admin/setting')?>"><i class="fas fa-cogs"></i>Settings</a></li>
    </ul>
  </div>
</div>
<div class="col-md-12 col-sm-12 responsive_head">
  <div class="row">
    <div class="col-md-4 col-sm-4 col-4">
      <nav id="menu" class="menu_panel" role="navigation">
            <ul>
                <li><a href="<?php echo URL::to('admin/area')?>"><i class="fas fa-globe-europe"></i>Manage<br/>Area / Route</a></li>
                <li><a href="<?php echo URL::to('admin/area_manager')?>"><i class="fas fa-user-secret"></i>Manage Area Manager</a></li>     
                <li><a href="<?php echo URL::to('admin/sales_rep')?>"><i class="fas fa-users"></i>Manage Sales REP</a></li>
                <li><a href="<?php echo URL::to('admin/shop')?>"><i class="fas fa-building"></i>Manage Shop</a></li>
                <li><a href="<?php echo URL::to('admin/accessories')?>"><i class="fas fa-box-open"></i>Manage Accessories</a></li>
                <li><a href="<?php echo URL::to('admin/shop_month_on_month')?>"><i class="fas fa-calendar-alt"></i>Month on Month</a></li>
                <li><a href="<?php echo URL::to('admin/commission')?>"><i class="fas fa-pound-sign"></i>Manage Commission</a></li>
                <li><a href="<?php echo URL::to('admin/simcards')?>"><i class="fas fa-sim-card"></i>Manage Simacards</a></li>
                <li><a href="<?php echo URL::to('admin/setting')?>"><i class="fas fa-cogs"></i>Settings</a></li>
            </ul>
        </nav>
      <a href="#menu" class="menu-link"><i class="fas fa-bars"></i></a>
    </div>
    <div class="col-md-4 col-sm-4 col-4 logo">
      <img src="<?php echo URL::to('assets')?>/images/logo.png">
    </div>
  </div>
</div>
@yield('content')


<script type="text/javascript">
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

</script>

<script src="<?php echo URL::to('assets')?>/js/res-menu/bigSlide_admin.js"></script>
<script>
    $(document).ready(function() {
        $('.menu-link').bigSlide();
    });
</script>
<script>
$(function(){
    $('#data_table').DataTable({
        
      autoWidth: true,
      scrollX: false,
      fixedColumns: false,
      searching: false,
      paging: false,
      info: false
    });
    $('#data_table2').DataTable({
        
      autoWidth: true,
      scrollX: false,
      fixedColumns: false,
      searching: false,
      paging: false,
      info: false
    });
    $('#data_table3').DataTable({
        
      autoWidth: true,
      scrollX: false,
      fixedColumns: false,
      searching: false,
      paging: false,
      info: false
    });
    $('#data_table4').DataTable({
        
      autoWidth: true,
      scrollX: false,
      fixedColumns: false,
      searching: false,
      paging: false,
      info: false
    });
    $('#data_table5').DataTable({
        
      autoWidth: true,
      scrollX: false,
      fixedColumns: false,
      searching: false,
      paging: false,
      info: false
    });
});
</script>

<script type="text/javascript" src="<?php echo URL::to('assets')?>/js/bootstrap.min.js"></script> 
</body>
</html>

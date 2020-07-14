<html xmlns="http://www.w3.org/1999/xhtml">
<title><?php echo $title?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<link href="<?php echo URL::to('assets')?>/css/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo URL::to('assets')?>/js/jquery.min-1-9-1.js"></script>

<link href="<?php echo URL::to('assets')?>/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo URL::to('assets')?>/css/style-responsive.css" rel="stylesheet" type="text/css" />
<link href="<?php echo URL::to('assets')?>/css/on_off.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo URL::to('assets')?>/js/jquery.validate.js"></script>

<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

<script type="text/javascript" src="<?php echo URL::to('assets')?>/js/popper.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets')?>/datatables/media/css/dataTables.bootstrap4.min.css">
<script src="<?php echo URL::to('assets')?>/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo URL::to('assets')?>/datatables/media/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo URL::to('assets')?>/datatables-responsive/js/dataTables.responsive.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets')?>/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets')?>/css/fixedHeader.dataTables.min.css">

<script src="<?php echo URL::to('assets')?>/js/jquery.dataTables.min.js"></script>
<script src="<?php echo URL::to('assets')?>/js/dataTables.fixedHeader.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets')?>/css/pretty-checkbox.css">
 





<link rel="stylesheet" href="<?php echo URL::to('assets')?>/css/res-menu/menu.css">

<head>

</head>
<body>
<div class="d-flex justify-content-center loading_css" >
  <div class="spinner-border" role="status">
    <span class="sr-only">Loading...</span>
  </div>
</div>
<div class="container-fluid wrap push">
<div class="row">  
<div class="col-md-12 col-sm-12 responsive_head sales_rep_menu">
  <div class="row">
    <div class="col-md-4 col-sm-4 col-4">
      <nav id="menu" class="menu_panel" role="navigation">

        <?php
        $date = date('Y-m-d');                
        $start_end = DB::table('start_day')->where('sales_id', $user_id)->where('start_date', $date)->first();
        if(count($start_end)){
          $menu_view ='<li><a href="'.URL::to('sales/end_day_stock').'"><i class="fas fa-layer-group"></i>End day stock</a></li>';
        }
        else{
          $menu_view = '';
        }
        
        ?>


          <ul>
              <li><a href="<?php echo URL::to('sales/route')?>"><i class="fas fa-route"></i>Route</a></li>
              <li><a href="<?php echo URL::to('sales/shop')?>"><i class="fas fa-building"></i>Shop</a></li>
              <li><a href="<?php echo URL::to('sales/start_day_stock')?>"><i class="fas fa-cubes"></i>Start day stock</a></li>
              <?php echo $menu_view;?>
              <li><a href="<?php echo URL::to('sales/time_sheet')?>"><i class="fas fa-clock"></i></i>Time Sheet</a></li>
              <li style="display: none;"><a href="<?php echo URL::to('sales/stock_month')?>"><i class="fas fa-calendar-alt"></i>Stock take of the month</a></li>
              <li><a href="<?php echo URL::to('sales/check_sim')?>"><i class="fas fa-sim-card"></i></i>Check SIM</a></li>
              <li><a href="<?php echo URL::to('sales/search')?>"><i class="fas fa-search"></i>Search</a></li>

              
              <li><a href="<?php echo URL::to('sales/logout')?>"><i class="fas fa-sign-out-alt"></i>Logout</a></li>                   
          </ul>
      </nav>
      <a href="#menu" class="menu-link"><i class="fas fa-bars"></i></a>
    </div>
    <div class="col-md-4 col-sm-4 col-4 logo">
      
      <a href="<?php echo URL::to('sales/dashboard')?>"><img src="<?php echo URL::to('assets')?>/images/logo.png"></a>
    </div>
    <div class="col-md-4 col-sm-4 col-4 logo">
     
        <?php
        $stock_month_count = DB::table('stock_month')->where('sales_id', $user_id)->where('status', 0)->count();
        if(count($stock_month_count)){
          if($stock_month_count != '0'){
            $notification = '<a href="'.URL::to('sales/stock_month').'" class="notification_bell"><div class="count">'.$stock_month_count.'</div><i class="fas fa-bell"></i></a>';
          }
          else{
            $notification='<a href="javascript:" class="notification_bell"><i class="fas fa-bell"></i></a>';
          }
          
        }
        else{
          $notification = '<a href="javascript:" class="notification_bell"><i class="fas fa-bell"></i></a>';
        }
        echo $notification
        ?>


        
        
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
<!-- <script>
$(document).ready(function() {
    window.history.pushState(null, "", window.location.href);        
    window.onpopstate = function() {
        window.history.pushState(null, "", window.location.href);
    };
});  
</script> -->
</body>
</html>

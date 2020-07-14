@extends('salesheader')
@section('content')
<!-- Content Header (Page header) -->




<div class="col-lg-12 col-md-12 col-sm-12 col-12 sales_right_panel">
	<div class="row">
		<div class="col-lg-12 col-sm-12 col-12">
			<div class="main_title">Manage <span>Shop</span></div>
		</div>
	</div>
	<div class="row">
        <div class="col-lg-12">
            <?php
              if(Session::has('message')) { ?>
                  <p class="alert alert-info">
                     <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo Session::get('message'); ?>
                        
                    </p>
              <?php }
              ?>
        </div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-12">
      <div class="row">
        <div class="col-lg-2 col-md-12 col-12">
          <div class="form-group">
            <label>Filter By Area</label>
            <select class="form-control filter_area">
              <option value="">Select Area</option>

              <?php              
              $arealist = explode(',', $userdetails->area);
              $output_area='';
              if(count($arealist)){
                foreach ($arealist as $area) {
                  $area_details = DB::table('area')->where('area_id', $area)->first();
                  $output_area.='
                  <option value="'.base64_encode($area_details->area_id).'">'.$area_details->area_name.'</option>';
                }
              }
              else{
                $output_area='<div class="col-lg-12">Empty</div>';
              }
              echo $output_area;
              ?>
            </select>
          </div>
        </div>

      </div>      
    </div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-12">
      <div class="route_list_ul">
        <ul id="route_tbody">
          

            

            <?php
            $area_route_list = explode(',', $userdetails->area);                 
            $output_route='';  
            $i=1;                
            
            if(count($area_route_list)){
              foreach ($area_route_list as $area_route) {                     

                $route_list = DB::table('route')->where('area_id', $area_route)->where('status',0)->get();
                
                if(count($route_list)){
                  foreach ($route_list as $route) {
                    $explode_sales = explode(',', $route->sales_rep_id);
                    
                    $area_details = DB::table('area')->where('area_id',$route->area_id)->first();
                    $salesrep_details = DB::table('sales_rep')->where('user_id', $user_id)->first();

                    $explode_rep_id = explode(',', $route->sales_rep_id);

                    if(in_array($user_id, $explode_rep_id)){                      
                        $shop_count = DB::table('shop')->where('route', $route->route_id)->count();
                        $output_route.='
                          <li>
                            <a href="'.URL::to('sales/shop_list_route/'.base64_encode($route->route_id)).'">
                              <div class="icon"><i class="fas fa-folder-open"></i></div>
                              <div class="text">'.$route->route_name.' ('.$shop_count.')</div>
                            </a>            
                          </li>
                          ';
                          $i++;                      
                    }                                             
                  }

                }
                
              }
              if($i==1){
                $output_route='
                <li>
                  <a href="javascript:">
                    <div class="icon"><i class="fas fa-folder-open"></i></div>
                    <div class="text">No Route</div>
                  </a>            
                </li>
                ';
              }
            }
            else{
              $output_route='
              <li>
                <a href="javascript:">
                  <div class="icon"><i class="fas fa-folder-open"></i></div>
                  <div class="text">No Route</div>
                </a>            
              </li>
              ';
            }
            echo $output_route;
            ?>


        </ul>
      </div>
		</div>
	</div>
		</div>
	</div>
</div>                
<!-- /.content -->
<script>
$(window).change(function(e) {
if($(e.target).hasClass("filter_area")){
  var id = $(e.target).val();
  $(".loading_css").addClass("loading");
  setTimeout(function() {
  $.ajax({
    url:"<?php echo URL::to('sales/filter_shop_route')?>",
    dataType:'json',
    data:{id:id},
    type:"post",
    success: function(result)
    {
      $("#route_tbody").html(result['output_route']);

      var select = $('#route_tbody');
      select.html(select.find('li').sort(function(x, y) {
        // to change to descending order switch "<" for ">"
        return $(x).text() > $(y).text() ? 1 : -1;
      }));

      $(".loading_css").removeClass("loading");      


    }
  })
  },500);

  
}
});
$(function() {
  // choose target dropdown
  var select = $('#route_tbody');
  select.html(select.find('li').sort(function(x, y) {
    // to change to descending order switch "<" for ">"
    return $(x).text() > $(y).text() ? 1 : -1;
  }));

  // select default item after sorting (first item)
  // $('select').get(0).selectedIndex = 0;
});
</script>
@stop
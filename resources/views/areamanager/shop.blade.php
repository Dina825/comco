@extends('areamanagerheader')
@section('content')
<!-- Content Header (Page header) -->
<style type="text/css">
.search_icon{top: 24px;}
</style>
<div class="modal fade sim_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="min-width: 75%;">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title sim_title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="table-responsive">
                <table class="table table-striped" id="sim_table">
                  <thead class="thead-dark">
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Network Id</th>
                      <th scope="col">Product Id</th>                     
                      <th scope="col">SSN</th>
                      <th scope="col">Cli</th>
                      <th scope="col">Allocation Date</th>
                      <th scope="col">Sales Rep</th>
                      <th scope="col">Shop Name</th>
                      <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody class="output_import">
                  
                  
                </tbody>
            </table>
          </div>

                </div>
                <div class="modal-footer">                    
                    
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade last_3month_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title 3moth_sim_title" id="exampleModalLabel">Last 3 Month Active List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="table-responsive">
                    <table class="own_table" id="output_last_3month"></table>
                  </div>
                </div>
                <div class="modal-footer">                    
                    
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade add_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="<?php echo URL::to('areamanager/shop_add')?>" id="add-form">
                <div class="modal-header">
                    <h5 class="modal-title active_deactive_title" id="exampleModalLabel">Add Shop</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                      <div class="form-group">
                        <label>Select Area Name</label>
                        <select class="form-control area_class add_input" name="area_name" required>
                          <option value="">Select Area</option>
                          <?php

                          $user_details = DB::table('area_manager')->where('user_id', $user_id)->first();
                          $explodearea = explode(',', $user_details->area);
                          $arealist = DB::table('area')->whereIn('area_id', $explodearea)->get();


                          $output_area='';
                          if(count($arealist)){
                            foreach ($arealist as $area) {                              
                              $output_area.='
                              <option value="'.$area->area_id.'">'.$area->area_name.'</option>';
                            }
                          }
                          else{
                            $output_area='<option value="">Empty</option>';
                          }
                          echo $output_area;
                          ?>                          
                        </select>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                      <div class="form-group">
                        <label>Select Sales REP</label>
                        <select class="form-control salesrep_class add_input" name="sales_rep" disabled required>
                        <option value="">Select Sales REP</option>
                              
                        </select>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                    <div class="form-group">
                      <label>Select Route</label>
                      <select class="form-control route_class add_input" name="route" disabled required>
                        <option value="">Select Route</option>                        
                      </select>
                    </div>
                  </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                      <div class="form-group">
                          <label>Enter Shop Name</label>
                          <input type="type" class="form-control add_input" name="shop_name" placeholder="Enter Shop Name" required >
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Address line 1</label>
                          <input type="type" class="form-control add_input" name="address1" placeholder="Enter Address line 1" required >
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Address line 2</label>
                          <input type="type" class="form-control add_input" name="address2" placeholder="Enter Address line 2" >
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Address line 3</label>
                          <input type="type" class="form-control add_input" name="address3" placeholder="Enter Address line 3" >
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter City</label>
                          <input type="type" class="form-control add_input" name="city" placeholder="Enter City" required >
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Postcode</label>
                          <input type="text" class="form-control add_input" name="postcode" placeholder="Enter Postcode" required >
                        </div> 
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Phone Number</label>
                          <input type="number" maxlength="11" minlength="11" name="phone_number" class="form-control add_input" placeholder="Enter Phone Number">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Customer Name</label>
                          <input type="type" class="form-control add_input" name="customer_name" placeholder="Enter Customer Name" >
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Select Mode of payment</label>
                          <select class="form-control add_input" name="payment_mode">
                            <option value="">Select Mode of payment</option>
                            <?php
                            $output_payment_mode='';
                            if(count($payment_mode_list)){
                              foreach ($payment_mode_list as $payment_mode) {
                                $output_payment_mode.='<option value="'.$payment_mode->payment_id.'">'.$payment_mode->mode_text.'</option>';
                              }
                            }
                            else{
                              $output_payment_mode.='<option value="">No Payment Mode</option>';
                            }
                            echo $output_payment_mode;
                            ?>
                          </select>                         
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Payee name</label>
                          <input type="type" class="form-control add_input" name="payee_name" placeholder="Enter Payee Name" >
                        </div>
                    </div>
                    
                    
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Select Shop type</label>
                          <select class="form-control add_input" name="shop_type">
                            <option value="">Select Shop type</option>
                            <?php
                            $output_shop_type='';
                            if(count($shop_type_list)){
                              foreach ($shop_type_list as $shop_type) {
                                $output_shop_type.='<option value="'.$shop_type->type_id.'">'.$shop_type->shop_type.'</option>';
                              }
                            }
                            else{
                              $output_shop_type.='<option value="">No Shop Type</option>';
                            }
                            echo $output_shop_type;
                            ?>
                          </select>                         
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Select Shop potential sales</label>
                          <select class="form-control add_input" name="shop_potential">
                            <option value="">Select Shop potential sales</option>
                            <?php
                            $output_potential='';
                            if(count($potentiallist)){
                              foreach ($potentiallist as $potential) {
                                $output_potential.='<option value="'.$potential->potential_id.'">'.$potential->shop_potential.'</option>';
                              }
                            }
                            else{
                              $output_potential.='<option value="">No Shop Potential Sales</option>';
                            }
                            echo $output_potential;
                            ?>
                          </select>                         
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">                    
                        <div class="form-group">
                          <label>Enter Email Id / Username</label>
                          <input type="email" class="form-control add_input" placeholder="Enter Email Id / Username" name="email_id">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Password</label>
                          <input type="password" class="form-control add_input" placeholder="Enter Password" name="password">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="row">
                          <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <label>Select Payment Plan</label>
                          </div>
                          <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                            <label class="form_radio">None
                               <input type="radio" name="tier" id="tier_add_zero" value="0" required>
                               <span class="checkmark_radio"></span>
                            </label>
                          </div>
                          <?php 
                          $tiers = DB::table('commission')->where('status',0)->where('copy_status',0)->get();
                          if(count($tiers))
                          {
                            foreach($tiers as $tier)
                            {

                              ?>
                              <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                                <label class="form_radio"><?php echo $tier->plan_name; ?>
                                   <input type="radio" name="tier" value="<?php echo $tier->commission_id; ?>" required>
                                   <span class="checkmark_radio"></span>
                                </label>
                              </div>
                              <?php
                            }
                          }
                          ?>
                        </div>
                    </div>                
                  </div>
                </div>
                <div class="modal-footer">                    
                    <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary model_add_button">Add New</button>
                </div>
            </form>
        </div>
    </div>
</div>






<div class="col-lg-12 col-md-12 col-sm-12 col-12 areamanager_right_panel">
	<div class="row">
		<div class="col-lg-9 col-md-6 col-sm-12 col-12">
			<div class="main_title">Manage <span>Shop</span></div>
		</div>
    <div class="col-lg-2 col-md-4 col-sm-6 col-6" style="padding-top: 15px;">
      <a href="javascript:" class="common_button float_right add_button_class" data-toggle="modal" data-target=".add_modal">Add Shop</a>
      <a href="<?php echo URL::to('areamanager/shop_month_on_month')?>" class="common_button float_right" style="margin-right: 10px; display: none;">Month on Month</a>
    </div>
		<div class="col-lg-1 col-md-2 col-sm-6 col-6">
			<a href="<?php echo URL::to('areamanager/logout')?>" class="logout_button"><i class="fas fa-sign-in-alt"></i> &nbsp;Logout</a>
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
        <div class="col-lg-2 col-md-6 col-12">
          <div class="form-group">
            <label>Filter By Area</label>
            <select class="form-control filter_area">
              <option value="">Select Area</option>

              <?php
              $output_area='';
              if(count($arealist)){
                foreach ($arealist as $area) {                  
                  $output_area.='
                  <option value="'.$area->area_id.'">'.$area->area_name.'</option>';
                }
              }
              else{
                $output_area='<option value="">Empty</option>';
              }
              echo $output_area;
              ?>
            </select>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 col-12">
          <div class="form-group">
            <label>Filter Sales REP</label>
            <select class="form-control filter_sales_rep" disabled>
              <option value="">Select Sales REP</option>
              
              
            </select>
          </div>
        </div>
        
        
        
        

      </div>      
    </div>

    <div class="col-lg-2 col-md-2 col-sm-12 col-12">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
          <div class="form-group">
            <label>Filter Direct By Sales REP</label>
            <select class="form-control filter_salesreponly">
              <option value="">Select Sales REP</option>

              <?php
              $sales_rep='';
              $userlistarea=array();
              if(count($explodearea)){
                foreach ($explodearea as $area) {             
                  $sales_count = DB::table('sales_rep')->whereRaw('FIND_IN_SET('.$area.',area)')->get();
                  if(count($sales_count))
                  {
                    foreach($sales_count as $person)
                    {
                      if(!in_array($person->user_id, $userlistarea)){
                        $sales_rep = $sales_rep+1;
                        array_push($userlistarea, $person->user_id);
                      }
                    }
                  }
                }
              }    
              sort($userlistarea);               
              print_r($userlistarea);
              
              ?>



              <?php
              

              $output_salesrep='';
              if(count($userlistarea)){
                foreach ($userlistarea as $usersingle) {
                  $user = DB::table('sales_rep')->where('user_id', $usersingle)->first();
                  $output_salesrep.='<option value="'.$user->user_id.'">'.$user->firstname.' '.$user->surname.'</option>';
                }
              }
              else{
                $output_salesrep='<option value="">Empty</option>';
              }
              echo $output_salesrep;
              ?>
            </select>
          </div>
        </div>
      </div>
    </div>
		
		<div class="col-lg-12">
			<div class="route_list_ul">
        <ul id="route_tbody">
                  <?php

                  $routelist = DB::table('route')->whereIn('area_id', $explodearea)->get();


                  $output_route='';
                  $i=1;
                  if(count($routelist)){
                    foreach ($routelist as $route) {

                        $explode_sales = explode(',', $route->sales_rep_id);
                        $area_salerep='';
                        if(count($explode_sales)){
                            foreach ($explode_sales as $sales) {
                                $salesrep_details = DB::table('sales_rep')->where('user_id', $sales)->first();
                                if($area_salerep == ''){
                                    $area_salerep = $salesrep_details->firstname;
                                }
                                else{
                                    $area_salerep  = $salesrep_details->firstname.', '.$area_salerep;
                                }
                                
                            }
                        }
                        else{
                            $area_salerep='';
                        }

                        $area_details = DB::table('area')->where('area_id',$route->area_id)->first();

                        $shop_count = DB::table('shop')->where('route', $route->route_id)->count();


                        

                        $output_route.='
                        <li>
                          <a href="'.URL::to('areamanager/shop_list_route/'.base64_encode($route->route_id)).'">
                            <div class="icon"><i class="fas fa-folder-open"></i></div>
                            <div class="text">'.$route->route_name.' ('.$shop_count.')</div>
                          </a>            
                        </li>
                        ';
                        $i++;
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
<script type="text/javascript">
$(window).click(function(e) {

if($(e.target).hasClass("active_class")){
    var value = $(e.target).attr("data-element");
    $(".loading_css").addClass("loading"); 
    setTimeout(function() {
    $.ajax({
        url:"<?php echo URL::to('areamanager/shop_details')?>",
        dataType:'json',
        data:{id:value, type:1},
        type:"post",
        success: function(result)
        {
          $(".active_deactive_title").html(result['title']);
          $(".id_filed").val(result['id']);
          $(".active_deactive_content").html(result['content']);
          $(".status_filed").val(result['status']);
          $(".status_modal").modal('show');
          $(".loading_css").removeClass("loading");
        }
      })
    },500);
}
if($(e.target).hasClass("deactive_class")){
    var value = $(e.target).attr("data-element");
    $(".loading_css").addClass("loading"); 
    setTimeout(function() {
    $.ajax({
        url:"<?php echo URL::to('areamanager/shop_details')?>",
        dataType:'json',
        data:{id:value, type:2},
        type:"post",
        success: function(result)
        {
          $(".active_deactive_title").html(result['title']);
          $(".id_filed").val(result['id']);
          $(".active_deactive_content").html(result['content']);
          $(".status_filed").val(result['status']);
          $(".status_modal").modal('show');
          $(".loading_css").removeClass("loading");
        }
      })
    },500);
}







if($(e.target).hasClass("add_button_class")){
	$(".add_input").val('');  
  //$('label[.error]').hide();
  $(".salesrep_class").prop("disabled", true);
  $(".route_class").prop("disabled", true);
  $("#tier_add_zero").prop("checked",true);
	
}


if($(e.target).hasClass("refresh_icon")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {  
  var value = $(e.target).attr("data-element");
  $.ajax({
      url:"<?php echo URL::to('areamanager/refresh_shop_icon')?>",
      dataType:'json',
      data:{id:value},
      type:"post",
      success: function(result)
      {
        $(".total_"+result['shop_id']).html(result['total']);
        $(".active_"+result['shop_id']).html(result['active']);
        $(".inactive_"+result['shop_id']).html(result['inactive']);
        $(".last3month_"+result['shop_id']).html(result['last3month']);
                    
        $(".loading_css").removeClass("loading");
      }
    })
  },500);

}



if($(e.target).hasClass("total_sim")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {
    $("#sim_table").dataTable().fnDestroy();
    var value = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('areamanager/total_sim_shop')?>",
        dataType:'json',
        data:{id:value, type:4},
        type:"post",
        success: function(result)
        {
          $(".output_import").html(result['output']);
          $(".sim_title").html(result['title']);
          $(".sim_modal").modal("show");
          $('#sim_table').DataTable({        
              autoWidth: true,
              scrollX: false,
              fixedColumns: false,
              searching: true,
              paging: true,
              info: false
          });            
          $(".loading_css").removeClass("loading");
        }
      })
  },500);

}

if($(e.target).hasClass("active_sim")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {
    $("#sim_table").dataTable().fnDestroy();
    var value = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('areamanager/active_sim_shop')?>",
        dataType:'json',
        data:{id:value, type:4},
        type:"post",
        success: function(result)
        {
          $(".output_import").html(result['output']);
          $(".sim_title").html(result['title']);
          $(".sim_modal").modal("show");
          $('#sim_table').DataTable({        
              autoWidth: true,
              scrollX: false,
              fixedColumns: false,
              searching: true,
              paging: true,
              info: false
          });            
          $(".loading_css").removeClass("loading");
        }
      })
  },500);

}

if($(e.target).hasClass("inactive_sim")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {
    $("#sim_table").dataTable().fnDestroy();
    var value = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('areamanager/inactive_sim_shop')?>",
        dataType:'json',
        data:{id:value, type:4},
        type:"post",
        success: function(result)
        {
          $(".output_import").html(result['output']);
          $(".sim_title").html(result['title']);
          $(".sim_modal").modal("show");
          $('#sim_table').DataTable({        
              autoWidth: true,
              scrollX: false,
              fixedColumns: false,
              searching: true,
              paging: true,
              info: false
          });            
          $(".loading_css").removeClass("loading");
        }
      })
  },500);

}

if($(e.target).hasClass("last_3month")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {
    $("#sim_table").dataTable().fnDestroy();
    var value = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('areamanager/shop_last_3moth')?>",
        dataType:'json',
        data:{id:value, type:4},
        type:"post",
        success: function(result)
        {
          $("#output_last_3month").html(result['output']);          
          $(".last_3month_modal").modal("show");
          $(".3moth_sim_title").html(result['title']);
          $(".loading_css").removeClass("loading");
        }
      })
  },500);

}


})


$(window).change(function(e) {
if($(e.target).hasClass("area_class")){
  var id = $(e.target).val();
  $.ajax({
    url:"<?php echo URL::to('areamanager/shop_form_filter_area')?>",
    dataType:'json',
    data:{id:id},
    type:"post",
    success: function(result)
    {
      $(".salesrep_class").attr("disabled", false);
      $(".salesrep_class").html(result['output_salesrep']);
      $(".route_class").attr("disabled", true);
      $(".route_class").val('');
    }
  })  
}

if($(e.target).hasClass("salesrep_class")){
  var salerep_id = $(e.target).val();
  var area_id = $(".area_class").val();
  $.ajax({
    url:"<?php echo URL::to('areamanager/shop_form_filter_sales')?>",
    dataType:'json',
    data:{salerep_id:salerep_id,area_id:area_id},
    type:"post",
    success: function(result)
    {
      $(".route_class").attr("disabled", false);
      $(".route_class").html(result['output_route']);
    }
  })  
}


if($(e.target).hasClass("filter_area")){
  var id = $(e.target).val();
  $(".loading_css").addClass("loading"); 
  setTimeout(function() {
    $("#data_table").dataTable().fnDestroy(); 
    $.ajax({
      url:"<?php echo URL::to('areamanager/filter_for_shop')?>",
      dataType:'json',
      data:{id:id,type:1},
      type:"post",
      success: function(result)
      {
        $(".filter_sales_rep").prop("disabled", false);
        $(".filter_sales_rep").html(result['output_salesrep']);
        
        $("#route_tbody").html(result['output_route']); 
        
        if(id ==''){
          $(".filter_sales_rep").attr("disabled", true);
          
        }
        $('[data-toggle="tooltip"]').tooltip();
        $(".loading_css").removeClass("loading");  
      }
    })  
  },500);
}

if($(e.target).hasClass("filter_sales_rep")){
  var salerep_id = $(e.target).val();
  var area_id = $(".filter_area").val();
  $(".loading_css").addClass("loading"); 
    setTimeout(function() {
    $("#data_table").dataTable().fnDestroy(); 
    $.ajax({
      url:"<?php echo URL::to('areamanager/filter_for_shop')?>",
      dataType:'json',
      data:{salerep_id:salerep_id,area_id:area_id,type:2},
      type:"post",
      success: function(result)
      {
        
        
        $("#route_tbody").html(result['output_route']);  
          
        
        $('[data-toggle="tooltip"]').tooltip();
        $(".loading_css").removeClass("loading");
      }
    })  
  },500);
}



if($(e.target).hasClass("filter_salesreponly")){
  var id = $(e.target).val();
  //$(".loading_css").addClass("loading"); 
  setTimeout(function() {
    $("#data_table").dataTable().fnDestroy(); 
    $.ajax({
      url:"<?php echo URL::to('areamanager/filter_for_shop')?>",
      dataType:'json',
      data:{id:id, type:3},
      type:"post",
      success: function(result)
      {
        
        $("#route_tbody").html(result['output_route']); 
        
        $('[data-toggle="tooltip"]').tooltip();
        
        $(".loading_css").removeClass("loading");  
      }
    })  
  },500);
}











});




</script>

<script type="text/javascript">
$.ajaxSetup({async:false});
$('#add-form').validate({
    rules: {        	
    	area_name:{required: true},
      sales_rep:{required: true},
      route:{required: true},
      shop_name:{required: true},            
      address1:{required: true},
      city:{required: true},
      postcode:{required: true},
      
    },
    messages: {            	
    	area_name:{required:"Please Select Area"},
      sales_rep:{required:"Please Select Sales REP"},
      route:{required:"Please Select Route"},
      shop_name:{required:"Enter Shop Name"},
      address1:{required:"Enter Address"},
      city:{required:"Enter City"},
      postcode:{required:"Enter Postcode"},
    },
});


$.ajaxSetup({async:false});
$(".update_button").click(function(){
   if($("#edit-form").valid()){
    $("#edit-form").submit();
   }

})

$.ajaxSetup({async:false});
$(".add_new_button").click(function(){
   if($("#add-form").valid()){
    $("#add-form").submit();
   }

})


</script>
@stop
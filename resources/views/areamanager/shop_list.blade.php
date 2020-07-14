@extends('areamanagerheader')
@section('content')
<!-- Content Header (Page header) -->
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
    <div class="col-lg-2 col-md-3 col-sm-12 col-12">
        <div class="form-group">
          <label style="display: none;">Search by Shop</label>
          <input type="text" class="form-control search_shop" placeholder="Search by Shop"  style="padding-right: 50px;">
          <label id="shop_search-error" class="error" for="area_name" style="display: none;">Please Enter Shop</label>

          <a href="javascript:" class="my-1 search_icon search_shop_icon"><i class="fas fa-search search_shop_icon"></i></a>
        </div>
      </div>
      <div class="col-lg-2 col-md-3 col-sm-12 col-12">
        <div class="form-group">
          <label style="display: none;">Search Account Number</label>
          <input type="number" class="form-control search_account" placeholder="Search Account Number"  style="padding-right: 50px;">
          <label id="account_search-error" class="error" for="area_name" style="display: none;">Please Enter Account Number</label>

          <a href="javascript:" class="my-1 search_icon search_account_icon"><i class="fas fa-search search_account_icon"></i></a>
        </div>
      </div>
      <div class="col-lg-2 col-md-3 col-sm-12 col-12">
        <div class="form-group">
          <label style="display: none;">Search Postcode</label>
          <input type="text" class="form-control search_postcode" placeholder="Search Postcode"  style="padding-right: 50px;">
          <label id="postcode_search-error" class="error" for="area_name" style="display: none;">Please Enter Postcode</label>

          <a href="javascript:" class="my-1 search_icon search_postcode_icon"><i class="fas fa-search search_postcode_icon"></i></a>
        </div>
      </div>
		<div class="col-lg-6 col-md-3 col-sm-12 col-12">
			<a href="javascript:" class="common_button float_right add_button_class" data-toggle="modal" data-target=".add_modal">Add Shop</a>
		</div>
		<div class="col-lg-12">
      <input type="hidden" class="route_id" value="<?php echo base64_encode($route_id) ?>" name="">
			<div class="table-responsive">
        <table class="table table-striped" id="shop_table">
          <thead class="thead-dark">
            <tr>              
              <th>Shop Details</th>
              
            </tr>
          </thead>
          <tbody id="shop_tbody">
            

            <?php
            $shop_output='';
            $i=1;
            if(count($shoplist)){
              foreach ($shoplist as $shop) {

                if($shop->status == 0){
                    $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Deactive" style="color: #33CC66"><i class="fas fa-check deactive_class" data-element="'.base64_encode($shop->shop_id).'"></i></a>';
                }
                else{
                    $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Active" style="color: #E11B1C"><i class="fas fa-times active_class" data-element="'.base64_encode($shop->shop_id).'"></i></a>';
                }

                $edit_icon = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Area" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($shop->shop_id).'"></i></a>&nbsp;&nbsp;&nbsp;';

                if($shop->address2 != ''){
                  $address2 = $shop->address2.'<br/>';
                }
                else{
                  $address2 = $shop->address2;
                }
                if($shop->address3 != ''){
                  $address3 = $shop->address3.'<br/>';
                }
                else{
                  $address3 = $shop->address3;
                }

                $shop_output.='
                <tr>                  
                  <td>
                    <a href="'.URL::to('areamanager/shop_view_details/'.base64_encode($shop->shop_id)).'">
                      '.$shop->shop_name.'<br/>
                      CC-'.$shop->shop_id.'<br/>
                      '.$shop->address1.'<br/>
                      '.$address2.'
                      '.$address3.'
                      '.$shop->postcode.'<br/>

                    </a>
                  </td>
                  
                </tr>';
                $i++;
              }
            }
            else{
              $shop_output='
              <tr>
                <td colspan="3" align="center">Empty</td>
              </tr>
              ';
            }
            echo $shop_output;
            ?>


                        
          </tbody>
      </table>
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
        }
      })
}
if($(e.target).hasClass("deactive_class")){
    var value = $(e.target).attr("data-element");
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
        }
      })
}
if($(e.target).hasClass("edit_icon")){
    var value = $(e.target).attr("data-element");
    var base_url = "<?php echo URL::to('/')?>"+'/';
    $.ajax({
        url:"<?php echo URL::to('areamanager/shop_details')?>",
        dataType:'json',
        data:{id:value, type:3},
        type:"post",
        success: function(result)
        {
          $(".id_filed").val(result['id']);          
          $(".shop_class").val(result['shop_name']);
          $(".customer_class").val(result['customer_name']);
          $(".payee_class").val(result['payee_name']);
          $(".address1_class").val(result['address1']);
          $(".address2_class").val(result['address2']);
          $(".address3_class").val(result['address3']);
          $(".city_class").val(result['city']);
          $(".postcode_class").val(result['postcode']);
          $(".phone_class").val(result['phone_number']);
          $(".payment_mode_class").val(result['payment_mode']);
          $(".shop_type_class").val(result['shop_type']);
          $(".potential_class").val(result['shop_potential']);
          $(".email_class").val(result['email_id']);
        	$(".edit_modal").modal("show");
        }
      })
}






if($(e.target).hasClass("add_button_class")){
	$(".add_input").val('');  
  //$('label[.error]').hide();
  $(".salesrep_class").prop("checked", false);
  $(".route_class").prop("checked", false);
  $("#tier_add_zero").prop("checked",true);
	
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
        $(".filter_route").attr("disabled", true);
        $(".filter_route").val('');
        $("#shop_tbody").html(result['output_shop']); 
        $('#data_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });
        if(id ==''){
          $(".filter_sales_rep").attr("disabled", true);
          $(".filter_route").attr("disabled", true);
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
        $(".filter_route").attr("disabled", false);
        $(".filter_route").html(result['output_route']);
        $("#shop_tbody").html(result['output_shop']); 
        $('#data_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });  
        if(salerep_id ==''){
          
          $(".filter_route").attr("disabled", true);
        }
        $('[data-toggle="tooltip"]').tooltip();
        $(".loading_css").removeClass("loading");
      }
    })  
  },500);
}

if($(e.target).hasClass("filter_route")){
  var route_id = $(e.target).val();
  var salerep_id = $(".filter_sales_rep").val();
  var area_id = $(".filter_area").val();
  $(".loading_css").addClass("loading"); 
  setTimeout(function() {
    $("#data_table").dataTable().fnDestroy(); 
    $.ajax({
      url:"<?php echo URL::to('areamanager/filter_for_shop')?>",
      dataType:'json',
      data:{salerep_id:salerep_id,area_id:area_id,route_id:route_id,type:3},
      type:"post",
      success: function(result)
      {      
        $("#shop_tbody").html(result['output_shop']); 
        $('#data_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });
        $('[data-toggle="tooltip"]').tooltip();       
        $(".loading_css").removeClass("loading");
      }
    })  
  },500);
}



if($(e.target).hasClass("filter_salesreponly")){
  var id = $(e.target).val();
  $(".loading_css").addClass("loading"); 
  setTimeout(function() {
    $("#data_table").dataTable().fnDestroy(); 
    $.ajax({
      url:"<?php echo URL::to('areamanager/filter_for_shop')?>",
      dataType:'json',
      data:{id:id, type:4},
      type:"post",
      success: function(result)
      {
        
        $("#shop_tbody").html(result['output_shop']); 
        $('#data_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });
        $('[data-toggle="tooltip"]').tooltip();
        
        $(".loading_css").removeClass("loading");  
      }
    })  
  },500);
}




if($(e.target).hasClass("area_class_edit")){
  var id = $(e.target).val();
  $.ajax({
    url:"<?php echo URL::to('areamanager/shop_form_filter_area')?>",
    dataType:'json',
    data:{id:id},
    type:"post",
    success: function(result)
    {
      $(".salesrep_class_edit").attr("disabled", false);
      $(".salesrep_class_edit").html(result['output_salesrep']);
      $(".route_class_edit").attr("disabled", true);
      $(".route_class_edit").val('');
    }
  })  
}


if($(e.target).hasClass("salesrep_class_edit")){
  var salerep_id = $(e.target).val();
  var area_id = $(".area_class_edit").val();
  $.ajax({
    url:"<?php echo URL::to('areamanager/shop_form_filter_sales')?>",
    dataType:'json',
    data:{salerep_id:salerep_id,area_id:area_id},
    type:"post",
    success: function(result)
    {
      $(".route_class_edit").attr("disabled", false);
      $(".route_class_edit").html(result['output_route']);
    }
  })  
}







});





</script>

<script type="text/javascript">
$.ajaxSetup({async:false});
$('#add-form').validate({
    rules: {        	    	      
      shop_name:{required: true},            
      address1:{required: true},
      city:{required: true},
      postcode:{required: true},
    },
    messages: {    	
      shop_name:{required:"Enter Shop Name"},      
      address1:{required:"Enter Address"},
      city:{required:"Enter City"},
      postcode:{required:"Enter Postcode"},
    },
});




$.ajaxSetup({async:false});
$(".add_new_button").click(function(){
   if($("#add-form").valid()){
    $("#add-form").submit();
   }

})


$('#shop_table').DataTable({        
  autoWidth: true,
  scrollX: false,
  fixedColumns: false,
  searching: false,
  paging: false,
  info: false
});

$(window).click(function(e) {

if($(e.target).hasClass("search_shop_icon")){  
  var value = $(".search_shop").val(); 
  var route_id = $(".route_id").val(); 
  if(value == ''){
    $("#shop_search-error").show();
  }
  else{
    $("#shop_search-error").hide();
    $(".loading_css").addClass("loading");
    $("#data_table").dataTable().fnDestroy();
    $.ajax({
    url:"<?php echo URL::to('areamanager/search_common_areamanager')?>",
    type:"post",
    dataType:"json",
    data:{value:value,route_id:route_id,type:1},
    success: function(result) { 
        $("#shop_tbody").html(result['output_shop']); 
        $('#data_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });       
        $(".loading_css").removeClass("loading");        
      } 
    })
  }
}

if($(e.target).hasClass("search_account_icon")){  
  var value = $(".search_account").val(); 
  var route_id = $(".route_id").val(); 
  if(value == ''){
    $("#account_search-error").show();
  }
  else{
    $("#account_search-error").hide();
    $(".loading_css").addClass("loading");
    $("#data_table").dataTable().fnDestroy();
    $.ajax({
    url:"<?php echo URL::to('areamanager/search_common_areamanager')?>",
    type:"post",
    dataType:"json",
    data:{value:value,route_id:route_id,type:2},
    success: function(result) { 
        $("#shop_tbody").html(result['output_shop']); 
        $('#data_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });       
        $(".loading_css").removeClass("loading");        
      } 
    })
  }
}


if($(e.target).hasClass("search_postcode_icon")){  
  var value = $(".search_postcode").val();
  var route_id = $(".route_id").val();  
  if(value == ''){
    $("#postcode_search-error").show();
  }
  else{
    $("#postcode_search-error").hide();
    $(".loading_css").addClass("loading");
    $("#data_table").dataTable().fnDestroy();
    $.ajax({
    url:"<?php echo URL::to('areamanager/search_common_areamanager')?>",
    type:"post",
    dataType:"json",
    data:{value:value,route_id:route_id,type:3},
    success: function(result) { 
        $("#shop_tbody").html(result['output_shop']); 
        $('#data_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });       
        $(".loading_css").removeClass("loading");        
      } 
    })
  }
}

})



$(".search_shop").keypress(function( event ) {
  var value = $(this).val();
  var route_id = $(".route_id").val();
  if(event.which == 13 ) {
    if(value == ''){
      $("#shop_search-error").show();
    }
    else{
      $("#shop_search-error").hide();
      $(".loading_css").addClass("loading");
      $("#data_table").dataTable().fnDestroy();
      $.ajax({
      url:"<?php echo URL::to('areamanager/search_common_areamanager')?>",
      type:"post",
      dataType:"json",
      data:{value:value,route_id:route_id,type:1},
      success: function(result) { 
        $("#shop_tbody").html(result['output_shop']); 
        $('#data_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });       
        $(".loading_css").removeClass("loading");        
      } 
    })
    }    
  }  
})

$(".search_account").keypress(function( event ) {
  var value = $(this).val();
  var route_id = $(".route_id").val();
  if(event.which == 13 ) {
    if(value == ''){
      $("#account_search-error").show();
    }
    else{
      $("#account_search-error").hide();
      $(".loading_css").addClass("loading");
      $("#data_table").dataTable().fnDestroy();
      $.ajax({
      url:"<?php echo URL::to('areamanager/search_common_areamanager')?>",
      type:"post",
      dataType:"json",
      data:{value:value,route_id:route_id,type:2},
      success: function(result) { 
        $("#shop_tbody").html(result['output_shop']); 
        $('#data_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });       
        $(".loading_css").removeClass("loading");        
      } 
    })
    }    
  }  
})



$(".search_postcode").keypress(function( event ) {
  var value = $(this).val();
  var route_id = $(".route_id").val();
  if(event.which == 13 ) {
    if(value == ''){
      $("#postcode_search-error").show();
    }
    else{
      $("#postcode_search-error").hide();
      $(".loading_css").addClass("loading");
      $("#data_table").dataTable().fnDestroy();
      $.ajax({
      url:"<?php echo URL::to('areamanager/search_common_areamanager')?>",
      type:"post",
      dataType:"json",
      data:{value:value,route_id:route_id,type:3},
      success: function(result) { 
        $("#shop_tbody").html(result['output_shop']); 
        $('#data_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });       
        $(".loading_css").removeClass("loading");        
      } 
    })
    }    
  }  
})

</script>
@stop
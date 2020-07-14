@extends('salesheader')
@section('content')
<!-- Content Header (Page header) -->
<div class="modal fade add_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="<?php echo URL::to('sales/shop_add')?>" id="add-form">
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
                          $arealist = explode(',', $userdetails->area);
                          $output_area='';
                          if(count($arealist)){
                            foreach ($arealist as $area) {
                              $area_details = DB::table('area')->where('area_id', $area)->first();
                              $output_area.='
                              <option value="'.$area_details->area_id.'">'.$area_details->area_name.'</option>';
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
                          <input type="type" class="form-control add_input" name="shop_name" placeholder="Enter Shop Name" required name="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Customer Name</label>
                          <input type="type" class="form-control add_input" name="customer_name" placeholder="Enter Customer Name" required name="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Payee name</label>
                          <input type="type" class="form-control add_input" name="payee_name" placeholder="Enter Payee Name" required name="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Address line 1</label>
                          <input type="type" class="form-control add_input" name="address1" placeholder="Enter Address line 1" required name="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Address line 2</label>
                          <input type="type" class="form-control add_input" name="address2" placeholder="Enter Address line 2" name="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Address line 3</label>
                          <input type="type" class="form-control add_input" name="address3" placeholder="Enter Address line 3" name="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter City</label>
                          <input type="type" class="form-control add_input" name="city" placeholder="Enter City" required name="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Postcode</label>
                          <input type="number" class="form-control add_input" name="postcode" placeholder="Enter Postcode" required name="">
                        </div> 
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Phone Number</label>
                          <input type="number" maxlength="10" minlength="10" name="phone_number" class="form-control add_input" placeholder="Enter Phone Number" required name="">
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
                          <select class="form-control add_input" name="shop_potential" required="">
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
                  </div>
                </div>
                <div class="modal-footer">
                  <input type="text" value="<?php echo base64_encode($userdetails->user_id) ?>" id="salerep_id" name="sales_rep">                    
                    <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary model_add_button">Add New</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade edit_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="<?php echo URL::to('sales/shop_update')?>" id="edit-form">
                <div class="modal-header">
                    <h5 class="modal-title active_deactive_title" id="exampleModalLabel">Edit Shop</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                      <div class="form-group">
                        <label>Select Area Name</label>
                        <select class="form-control area_class area_class_edit" name="area_name" required>
                          <option value="">Select Area</option>
                          <?php              
                          $arealist = explode(',', $userdetails->area);
                          $output_area='';
                          if(count($arealist)){
                            foreach ($arealist as $area) {
                              $area_details = DB::table('area')->where('area_id', $area)->first();
                              $output_area.='
                              <option value="'.$area_details->area_id.'">'.$area_details->area_name.'</option>';
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
                  <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                    <div class="form-group">
                      <label>Select Route</label>
                      <select class="form-control route_class route_class_edit add_input" name="route" disabled required>
                        <option value="">Select Route</option>                        
                      </select>
                    </div>
                  </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                      <div class="form-group">
                          <label>Enter Shop Name</label>
                          <input type="type" class="form-control shop_class" name="shop_name" placeholder="Enter Shop Name" required name="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Customer Name</label>
                          <input type="type" class="form-control customer_class" name="customer_name" placeholder="Enter Customer Name" required name="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Payee name</label>
                          <input type="type" class="form-control payee_class" name="payee_name" placeholder="Enter Payee Name" required name="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Address line 1</label>
                          <input type="type" class="form-control address1_class" name="address1" placeholder="Enter Address line 1" required name="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Address line 2</label>
                          <input type="type" class="form-control address2_class" name="address2" placeholder="Enter Address line 2" name="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Address line 3</label>
                          <input type="type" class="form-control address3_class" name="address3" placeholder="Enter Address line 3" name="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter City</label>
                          <input type="type" class="form-control city_class" name="city" placeholder="Enter City" required name="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Postcode</label>
                          <input type="number" class="form-control postcode_class" name="postcode" placeholder="Enter Postcode" required name="">
                        </div> 
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Phone Number</label>
                          <input type="number" maxlength="10" minlength="10" name="phone_number" class="form-control phone_class" placeholder="Enter Phone Number" required name="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Select Mode of payment</label>
                          <select class="form-control payment_mode_class" name="payment_mode">
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
                          <label>Select Shop type</label>
                          <select class="form-control shop_type_class" name="shop_type">
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
                          <select class="form-control potential_class" name="shop_potential" required="">
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
                          <input type="email" class="form-control email_class" placeholder="Enter Email Id / Username" name="email_id">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Password</label>
                          <input type="password" class="form-control password_class" placeholder="Enter Password" name="password">
                        </div>
                    </div>                   
                  </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" class="id_filed" name="id">
                    <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary model_add_button">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade status_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="<?php echo URL::to('sales/shop_status')?>">
                <div class="modal-header">
                    <h5 class="modal-title active_deactive_title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="sub_title3 active_deactive_content" style="line-height: 25px;"></div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" class="id_filed" value="" name="id">
                    <input type="hidden" class="status_filed" value="" name="status">
                    <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-primary model_add_button">Yes</button>
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
    <div class="col-lg-10 col-md-10 col-sm-6 col-6">
      <div class="row">
        <div class="col-lg-2 col-md-6 col-12">
          <div class="form-group">
            <label>Filter By Area</label>
            <select class="form-control">
              <option value="">Select Area</option>

              <?php              
              $arealist = explode(',', $userdetails->area);
              $output_area='';
              if(count($arealist)){
                foreach ($arealist as $area) {
                  $area_details = DB::table('area')->where('area_id', $area)->first();
                  $output_area.='
                  <option value="'.$area_details->area_id.'">'.$area_details->area_name.'</option>';
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
        <div class="col-lg-2 col-md-6 col-12">
          <div class="form-group">
            <label>Filter Route</label>
            <select class="form-control">
              <option value="">Select Route</option>
              <?php
              $output_route='';
              $i=1;                  
              if(count($routelist)){
                foreach ($routelist as $route) {
                  $explode_salesrep = explode(',', $route->sales_rep_id);
                  if(in_array($userdetails->user_id, $explode_salesrep)){                        
                    $output_route.='
                    <option value="'.base64_encode($route->route_id).'">'.$route->route_name.'</option>';
                    $i++;
                  }
                  if($i==1){
                    $output_route='<option value="">No Route</option>';
                  }
                }
              }
              else{
                $output_route='';
              }
              echo $output_route;
              ?>
              
            </select>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 col-12">
          <div class="form-group">
            <label>Search by Shop</label>
            <input type="text" class="form-control" placeholder="Enter Shop Name" name="" style="padding-right: 50px;">

            <a href="javascript:" class="my-1 search_icon "><i class="fas fa-search"></i></a>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 col-12">
          <div class="form-group">
            <label>Search Account Number</label>
            <input type="text" class="form-control" placeholder="Enter Account Number" name="" style="padding-right: 50px;">

            <a href="javascript:" class="my-1 search_icon "><i class="fas fa-search"></i></a>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 col-12">
          <div class="form-group">
            <label>Search Postcode</label>
            <input type="text" class="form-control" placeholder="Enter Postcode" name="" style="padding-right: 50px;">

            <a href="javascript:" class="my-1 search_icon "><i class="fas fa-search"></i></a>
          </div>
        </div>

      </div>      
    </div>
		<div class="col-lg-2 col-md-2 col-sm-6 col-6">
			<a href="javascript:" class="common_button float_right add_button_class" data-toggle="modal" data-target=".add_modal">Add Shop</a>
		</div>
		<div class="col-lg-12">
			<div class="table-responsive">
		        <table class="table table-striped" id="data_table">
		          <thead class="thead-dark">
		            <tr>
		              <th scope="col">#</th>
                  <th scope="col">Shop Name</th>
                  <th scope="col">Account Number</th>
                  <th scope="col">Customer Name</th>
                  <th scope="col">Route</th>
		              <th scope="col">Sales REP</th>		              
		              <th scope="col">Area Name</th>
		              <th scope="col">Total SIM</th>
                  <th scope="col">Active SIM</th>
		              <th scope="col">Inactive SIM</th>                  
		              <th scope="col" width="100px" style="text-align: center;">Action</th>
		            </tr>
		          </thead>
		          <tbody>
                  <?php
                  $output_shop='';
                  $i=1;
                  if(count($shoplist)){
                    foreach ($shoplist as $shop) {
                      $route_details = DB::table('route')->where('route_id', $shop->route)->first();
                      $salesrep_details = DB::table('sales_rep')->where('user_id', $shop->sales_rep)->first();
                      $area_details = DB::table('area')->where('area_id', $shop->area_name)->first();

                      if($shop->status == 0){
                          $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Deactive" style="color: #33CC66"><i class="fas fa-check deactive_class" data-element="'.base64_encode($shop->shop_id).'"></i></a>';
                      }
                      else{
                          $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Active" style="color: #E11B1C"><i class="fas fa-times active_class" data-element="'.base64_encode($shop->shop_id).'"></i></a>';
                      }

                      $output_shop.='
                      <tr>
                        <td>'.$i.'</td>
                        <td>'.$shop->shop_name.'</td>
                        <td>CC-'.$shop->shop_id.'</td>
                        <td>'.$shop->customer_name.'</td>
                        <td>'.$route_details->route_name.'</td>
                        <td>'.$salesrep_details->firstname.'</td>
                        <td>'.$area_details->area_name.'</td>
                        <td></td>
                        <td></td>
                        <td></td>                        
                        <td align="center">
                        <a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Area" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($shop->shop_id).'"></i></a>&nbsp;&nbsp;&nbsp;    
                          '.$status.'
                        </td>
                      </tr>';
                      $i++;
                    }                    
                  }
                  else{
                    $output_shop='
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td align="center">Empty</td>
                      <td></td>
                      <td></td>
                      <td></td>                      
                      <td></td>
                      <td></td>
                    </tr>
                    ';
                  }
                  echo $output_shop;
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
        url:"<?php echo URL::to('sales/shop_details')?>",
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
        url:"<?php echo URL::to('sales/shop_details')?>",
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
        url:"<?php echo URL::to('sales/shop_details')?>",
        dataType:'json',
        data:{id:value, type:3},
        type:"post",
        success: function(result)
        {
          $(".id_filed").val(result['id']);
          $(".area_class_edit").val(result['area_name']);
          $(".area_class_edit").prop("disabled", true);           
          $(".route_class_edit").prop("disabled", true);          
          $(".route_class_edit").html(result['output_route']);
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
	
}
})


$(window).change(function(e) {
if($(e.target).hasClass("area_class")){
  var area_id = $(e.target).val();
  var salerep_id = $("#salerep_id").val();
  $.ajax({
    url:"<?php echo URL::to('sales/shop_form_filter_sales')?>",
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

/*if($(e.target).hasClass("salesrep_class")){
  var salerep_id = $(e.target).val();
  var area_id = $(".area_class").val();
  $.ajax({
    url:"<?php echo URL::to('admin/shop_form_filter_sales')?>",
    dataType:'json',
    data:{salerep_id:salerep_id,area_id:area_id},
    type:"post",
    success: function(result)
    {
      $(".route_class").attr("disabled", false);
      $(".route_class").html(result['output_route']);
    }
  })  
}*/


});




</script>

<script type="text/javascript">
$.ajaxSetup({async:false});
$('#add-form').validate({
    rules: {        	
    	area_name:{required: true},
      /*sales_rep:{required: true},*/
      route:{required: true},
      shop_name:{required: true},
      customer_name:{required: true},
      payee_name:{required: true},
      address1:{required: true},
      city:{required: true},
      postcode:{required: true},
      phone_number:{required: true},
      payment_mode:{required: true},
      shop_type:{required: true},
      shop_potential:{required: true},
    },
    messages: {            	
    	area_name:{required:"Please Select Area"},
      /*sales_rep:{required:"Please Select Sales REP"},*/
      route:{required:"Please Select Route"},
      shop_name:{required:"Enter Shop Name"},
      customer_name:{required:"Enter Customer Name"},
      payee_name:{required:"Enter Payee Name"},
      address1:{required:"Enter Address"},
      city:{required:"Enter City"},
      postcode:{required:"Enter Postcode"},
      phone_number:{required:"Enter Phone Number"},
      payment_mode:{required:"Please Select Mode of Payment"},
      shop_type:{required:"Please Select Shop Type"},
      shop_potential:{required:"Please Select Shop Potential Sales"},
    },
});
$.ajaxSetup({async:false});
$('#edit-form').validate({
    rules: {          
      area_name:{required: true},
      /*sales_rep:{required: true},*/
      route:{required: true},
      shop_name:{required: true},
      customer_name:{required: true},
      payee_name:{required: true},
      address1:{required: true},
      city:{required: true},
      postcode:{required: true},
      phone_number:{required: true},
      payment_mode:{required: true},
      shop_type:{required: true},
      shop_potential:{required: true},
    },
    messages: {             
      area_name:{required:"Please Select Area"},
      /*sales_rep:{required:"Please Select Sales REP"},*/
      route:{required:"Please Select Route"},
      shop_name:{required:"Enter Shop Name"},
      customer_name:{required:"Enter Customer Name"},
      payee_name:{required:"Enter Payee Name"},
      address1:{required:"Enter Address"},
      city:{required:"Enter City"},
      postcode:{required:"Enter Postcode"},
      phone_number:{required:"Enter Phone Number"},
      payment_mode:{required:"Please Select Mode of Payment"},
      shop_type:{required:"Please Select Shop Type"},
      shop_potential:{required:"Please Select Shop Potential Sales"},
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
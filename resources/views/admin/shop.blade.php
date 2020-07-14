@extends('adminheader')
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
                    <div style="float: left; width: 100%;" class="inactive_move_group">
                      <div class="row">
                        <div class="col-lg-12">            
                          <p class="alert alert-info message_alert" style="display: none;">

                            
                                         
                          </p>            
                        </div>
                      </div>
                      <div class="inactive_move" style="float: left;"></div>                      
                      <div class="inactive_move_button" style="float: left; margin: 9px;">
                        <a href="javascript:" class="common_button button_move">Assign Sales REP</a></div>
                      <label id="move-error" class="error" style="clear: both; float: left; display: none;">Please Select Sales REP</label>
                      <input type="hidden" value="" class="inactive_shop" name="">
                    </div>
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
            <form method="post" action="<?php echo URL::to('admin/shop_add')?>" id="add-form">
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
                          $output_area='';
                          if(count($arealist)){
                            foreach ($arealist as $area) {
                              $output_area.='
                              <option value="'.$area->area_id.'">'.$area->area_name.'</option>';
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


<div class="modal fade edit_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="<?php echo URL::to('admin/shop_update')?>" id="edit-form">
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
                        <select class="form-control area_class_edit" name="area_name" required>
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
                            $output_area='<div class="col-lg-12">Empty</div>';
                          }
                          echo $output_area;
                          ?>                          
                        </select>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                      <div class="form-group">
                        <label>Select Sales REP</label>
                        <select class="form-control salesrep_class_edit" name="sales_rep" required>
                        <option value="">Select Sales REP</option>
                              
                        </select>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                    <div class="form-group">
                      <label>Select Route</label>
                      <select class="form-control route_class_edit add_input" name="route" required>
                        <option value="">Select Route</option>                        
                      </select>
                    </div>
                  </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                      <div class="form-group">
                          <label>Enter Shop Name</label>
                          <input type="type" class="form-control shop_class" name="shop_name" placeholder="Enter Shop Name" required >
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Address line 1</label>
                          <input type="type" class="form-control address1_class" name="address1" placeholder="Enter Address line 1" required >
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Address line 2</label>
                          <input type="type" class="form-control address2_class" name="address2" placeholder="Enter Address line 2" >
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Address line 3</label>
                          <input type="type" class="form-control address3_class" name="address3" placeholder="Enter Address line 3" >
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter City</label>
                          <input type="type" class="form-control city_class" name="city" placeholder="Enter City" required >
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Postcode</label>
                          <input type="text" class="form-control postcode_class" name="postcode" placeholder="Enter Postcode" required >
                        </div> 
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Phone Number</label>
                          <input type="number" maxlength="11" minlength="11" name="phone_number" class="form-control phone_class" placeholder="Enter Phone Number">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Customer Name</label>
                          <input type="type" class="form-control customer_class" name="customer_name" placeholder="Enter Customer Name" >
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
                          <label>Enter Payee name</label>
                          <input type="type" class="form-control payee_class" name="payee_name" placeholder="Enter Payee Name" >
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
                          <select class="form-control potential_class" name="shop_potential">
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
                          <input type="text" class="form-control password_class" placeholder="Enter Password" name="password">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="row">
                          <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <label>Select Payment Plan</label>
                          </div>
                          <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                            <label class="form_radio">None
                               <input type="radio" name="tier_edit" id="tier_0" value="0" required>
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
                                   <input type="radio" name="tier_edit" id="tier_<?php echo $tier->commission_id; ?>" value="<?php echo $tier->commission_id; ?>" required>
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
            <form action="<?php echo URL::to('admin/shop_status')?>">
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






<div class="col-lg-11 col-md-12 col-sm-12 col-12 offset-lg-1 right_panel">
	<div class="row">
		<div class="col-lg-9 col-md-6 col-sm-12 col-12">
			<div class="main_title">Manage <span>Shop</span></div>
		</div>
    <div class="col-lg-2 col-md-4 col-sm-6 col-6" style="padding-top: 15px;">
      <a href="javascript:" class="common_button float_right add_button_class" data-toggle="modal" data-target=".add_modal">Add Shop</a>
      <a href="<?php echo URL::to('admin/shop_month_on_month')?>" class="common_button float_right" style="margin-right: 10px;">Month on Month</a>
    </div>
		<div class="col-lg-1 col-md-2 col-sm-6 col-6">
			<a href="<?php echo URL::to('admin/logout')?>" class="logout_button"><i class="fas fa-sign-in-alt"></i> &nbsp;Logout</a>
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
                $output_area='<div class="col-lg-12">Empty</div>';
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
        <div class="col-lg-2 col-md-6 col-12">
          <div class="form-group">
            <label>Filter Route</label>
            <select class="form-control filter_route" disabled>
              <option value="">Select Route</option>
              
              
            </select>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 col-12">
          <div class="form-group">
            <label>Search by Shop</label>
            <input type="text" class="form-control search_shop" placeholder="Enter Shop Name"  style="padding-right: 50px;">
            <label id="shop_search-error" class="error" for="area_name" style="display: none;">Please Enter Shop</label>

            <a href="javascript:" class="my-1 search_icon search_shop_icon"><i class="fas fa-search search_shop_icon"></i></a>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 col-12">
          <div class="form-group">
            <label>Search Account Number</label>
            <input type="number" class="form-control search_account" placeholder="Enter Account Number"  style="padding-right: 50px;">
            <label id="account_search-error" class="error" for="area_name" style="display: none;">Please Enter Account Number</label>

            <a href="javascript:" class="my-1 search_icon search_account_icon"><i class="fas fa-search search_account_icon"></i></a>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 col-12">
          <div class="form-group">
            <label>Search Postcode</label>
            <input type="text" class="form-control search_postcode" placeholder="Enter Postcode"  style="padding-right: 50px;">
            <label id="postcode_search-error" class="error" for="area_name" style="display: none;">Please Enter Postcode</label>

            <a href="javascript:" class="my-1 search_icon search_postcode_icon"><i class="fas fa-search search_postcode_icon"></i></a>
          </div>
        </div>

      </div>      
    </div>

    <div class="col-lg-2 col-md-2 col-sm-12 col-12">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
          <div class="form-group">
            <label>Filter By Sales REP</label>
            <select class="form-control filter_salesreponly">
              <option value="">Select Sales REP</option>

              <?php
              $salesreplist = DB::table('sales_rep')->get();

              $output_salesrep='';
              if(count($salesreplist)){
                foreach ($salesreplist as $rep) {
                  $output_salesrep.='<option value="'.$rep->user_id.'">'.$rep->firstname.' '.$rep->surname.'</option>';
                }
              }
              else{
                $output_salesrep='<div class="col-lg-12">Empty</div>';
              }
              echo $output_salesrep;
              ?>
            </select>
          </div>
        </div>
      </div>
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
                  <th scope="col">Postcode</th>
                  <th scope="col">Route</th>
		              <th scope="col">Sales REP</th>		              
		              <th scope="col">Area Name</th>
		              <th scope="col">Total SIM</th>
                  <th scope="col">Active SIM</th>
		              <th scope="col">Inactive SIM</th>   
                  <th scope="col">3 Month Active</th>   
                  <th scope="col"></th>

                  <th scope="col">Pending Commission</th>                  
		              <th scope="col" width="100px" style="text-align: center;">Action</th>
		            </tr>
		          </thead>
		          <tbody id="shop_tbody">
                  <?php
                  $output_shop='';
                  $i=1;                  
                  if(count($shoplist)){
                    foreach ($shoplist as $shop) {
                      $route_details = DB::table('route')->where('route_id', $shop->route)->first();
                      $salesrep_details = DB::table('sales_rep')->where('user_id', $shop->sales_rep)->first();
                      $area_details = DB::table('area')->where('area_id', $shop->area_name)->first();

                      if($shop->status == 0){
                          $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Deactive" style="color: #33CC66"><i class="fas fa-check deactive_class" data-element="'.base64_encode($shop->shop_id).'"></i></a>&nbsp;&nbsp;&nbsp;';
                      }
                      else{
                          $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Active" style="color: #E11B1C"><i class="fas fa-times active_class" data-element="'.base64_encode($shop->shop_id).'"></i></a>&nbsp;&nbsp;&nbsp;';
                      }

                      

                      

                      if(count($route_details)){
                        $route = $route_details->route_name;

                        $explode_route = explode(',', $route_details->sales_rep_id);
                        $salesrep='';
                        if(count($explode_route)){
                          foreach ($explode_route as $single_route) {
                            $sales_rep = DB::table('sales_rep')->where('user_id', $single_route)->first();

                            if(count($sales_rep)){
                              if($salesrep == ''){
                                  $salesrep = $sales_rep->firstname;
                              }
                              else{
                                  $salesrep  = $sales_rep->firstname.', '.$salesrep;
                              }
                            }
                            else{
                              $salesrep = '';
                            }

                            
                          }
                        }
                        else{
                          $salesrep = '';
                        }
                      }
                      else{
                        $route='';
                        $salesrep ='';
                      }

                      $pending_cost = DB::table('shop_commission')
                      ->select(DB::raw('SUM(one_cost + two_cost + three_cost + four_cost + five_cost + six_cost + seven_cost + eight_cost + nine_cost + ten_cost) AS pending_cost'))
                      ->where('shop_id',$shop->shop_id)
                      ->first();
                      if(count($pending_cost))
                      {
                        $pending_cost_value = $pending_cost->pending_cost;
                      }
                      else{
                        $pending_cost_value = 0;
                      }

                      $bonus_cost = DB::table('shop_commission')->where('shop_id',$shop->shop_id)->sum('bonus_cost');
                      $total_pending_cost = $pending_cost_value - $bonus_cost;






                      $output_shop.='
                      <tr>
                        <td>'.$i.'</td>
                        <td><a href="'.URL::to('admin/shop_view_details'.'/'.base64_encode($shop->shop_id)).'">'.$shop->shop_name.'</a></td>
                        <td>CC-'.$shop->shop_id.'</td>
                        <td>'.$shop->customer_name.'</td>
                        <td>'.$shop->postcode.'</td>
                        <td>'.$route.'</td>
                        <td>'.$salesrep.'</td>
                        <td>'.$area_details->area_name.'</td>

                        <td align="center" class="total_'.$shop->shop_id.'"></td>
                        <td align="center" class="active_'.$shop->shop_id.'"></td>
                        <td align="center" class="inactive_'.$shop->shop_id.'"></td>
                        <td align="center" class="last3month_'.$shop->shop_id.'">

                        </td>

                        <td><a href="javascript:"><i class="fas fa-sync-alt refresh_icon" data-element="'.base64_encode($shop->shop_id).'"></i></a></td>  
                        <td align="center">
                        £ '.$total_pending_cost.'
                        </td>                        
                        <td align="center">
                        <a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Shop" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($shop->shop_id).'"></i></a>&nbsp;&nbsp;&nbsp;    
                          '.$status.'
                          <a href="'.URL::to('admin/shop_review_commission?shop_id='.$shop->shop_id.'').'" data-toggle="tooltip" data-placement="top" data-original-title="Shop Commission" ><i class="fas fa-pound-sign"></i></a>
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
                      <td></td>
                      <td align="center">Empty</td>
                      <td></td>
                      <td></td>
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
    $(".loading_css").addClass("loading"); 
    setTimeout(function() {
    $.ajax({
        url:"<?php echo URL::to('admin/shop_details')?>",
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
        url:"<?php echo URL::to('admin/shop_details')?>",
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
if($(e.target).hasClass("edit_icon")){
    var value = $(e.target).attr("data-element");
    var base_url = "<?php echo URL::to('/')?>"+'/';
    $(".loading_css").addClass("loading"); 
    setTimeout(function() {
    $.ajax({
        url:"<?php echo URL::to('admin/shop_details')?>",
        dataType:'json',
        data:{id:value, type:3},
        type:"post",
        success: function(result)
        {
          $(".id_filed").val(result['id']);
          $(".area_class_edit").val(result['area_name']);
          /*$(".area_class_edit").prop("disabled", true); 
          $(".salesrep_class_edit").prop("disabled", true);
          $(".route_class_edit").prop("disabled", true);         */
          $(".salesrep_class_edit").html(result['output_salesrep']);
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
          $("#tier_"+result['plan']).prop("checked",true);
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

if($(e.target).hasClass("search_shop_icon")){  
  var value = $(".search_shop").val();  
  if(value == ''){
    $("#shop_search-error").show();
  }
  else{
    $("#shop_search-error").hide();
    $(".loading_css").addClass("loading");
    setTimeout(function() {
      $("#data_table").dataTable().fnDestroy();
      $.ajax({
      url:"<?php echo URL::to('admin/search_common')?>",
      type:"post",
      dataType:"json",
      data:{value:value,type:1},
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
          $('[data-toggle="tooltip"]').tooltip();
          $(".loading_css").removeClass("loading");        
        } 
      })
    },500);
  }
}

if($(e.target).hasClass("search_account_icon")){  
  var value = $(".search_account").val();  
  if(value == ''){
    $("#account_search-error").show();
  }
  else{
    $("#account_search-error").hide();
    $(".loading_css").addClass("loading");
    setTimeout(function() {
      $("#data_table").dataTable().fnDestroy();
      $.ajax({
      url:"<?php echo URL::to('admin/search_common')?>",
      type:"post",
      dataType:"json",
      data:{value:value,type:2},
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
          $('[data-toggle="tooltip"]').tooltip();
          $(".loading_css").removeClass("loading");        
        } 
      })
    },500);
  }
}


if($(e.target).hasClass("search_postcode_icon")){  
  var value = $(".search_postcode").val();  
  if(value == ''){
    $("#postcode_search-error").show();
  }
  else{
    $("#postcode_search-error").hide();
    $(".loading_css").addClass("loading");
    setTimeout(function() {
      $("#data_table").dataTable().fnDestroy();
      $.ajax({
      url:"<?php echo URL::to('admin/search_common')?>",
      type:"post",
      dataType:"json",
      data:{value:value,type:3},
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
          $('[data-toggle="tooltip"]').tooltip();

          $(".loading_css").removeClass("loading");        
        } 
      })
    },500);
  }
}


if($(e.target).hasClass("refresh_icon")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {  
  var value = $(e.target).attr("data-element");
  $.ajax({
      url:"<?php echo URL::to('admin/refresh_shop_icon')?>",
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
        url:"<?php echo URL::to('admin/total_sim_shop')?>",
        dataType:'json',
        data:{id:value, type:4},
        type:"post",
        success: function(result)
        {
          $(".output_import").html(result['output']);
          $(".sim_title").html(result['title']);
          $(".inactive_move_group").hide();
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
        url:"<?php echo URL::to('admin/active_sim_shop')?>",
        dataType:'json',
        data:{id:value, type:4},
        type:"post",
        success: function(result)
        {
          $(".output_import").html(result['output']);
          $(".sim_title").html(result['title']);
          $(".inactive_move_group").hide();
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
        url:"<?php echo URL::to('admin/inactive_sim_shop')?>",
        dataType:'json',
        data:{id:value, type:4},
        type:"post",
        success: function(result)
        {
          $(".output_import").html(result['output']);
          $(".sim_title").html(result['title']);
          $(".inactive_move_group").show();
          $(".message_alert").hide();
          $(".sim_modal").modal("show");
          $(".inactive_shop").val(result['shop_id']);
          
          $(".inactive_move").html(result['inactive_move']);
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
        url:"<?php echo URL::to('admin/shop_last_3moth')?>",
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

if($(e.target).hasClass("button_move")){
  var salesrep = $(".salesrep_inactive").val();
  if(salesrep == ''){
    $("#move-error").show();
  }
  else{
    $("#move-error").hide();
    var shopid = $(".inactive_shop").val();
    $("#sim_table").dataTable().fnDestroy();
    $(".loading_css").addClass("loading");
    setTimeout(function() {
    $.ajax({
        url:"<?php echo URL::to('admin/shop_inactive_move_salesrep')?>",
        dataType:'json',
        data:{salesrep:salesrep, shopid:shopid},
        type:"post",
        success: function(result)
        {
          var close = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
          $(".message_alert").html(result['message']+close);
          $(".message_alert").show();
          $(".output_import").html(result['output']);
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
}


})


$(window).change(function(e) {
if($(e.target).hasClass("area_class")){
  var id = $(e.target).val();
  $.ajax({
    url:"<?php echo URL::to('admin/shop_form_filter_area')?>",
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
}


if($(e.target).hasClass("filter_area")){
  var id = $(e.target).val();
  $(".loading_css").addClass("loading"); 
  setTimeout(function() {
    $("#data_table").dataTable().fnDestroy(); 
    $.ajax({
      url:"<?php echo URL::to('admin/filter_for_shop')?>",
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
      url:"<?php echo URL::to('admin/filter_for_shop')?>",
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
      url:"<?php echo URL::to('admin/filter_for_shop')?>",
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
      url:"<?php echo URL::to('admin/filter_for_shop')?>",
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
    url:"<?php echo URL::to('admin/shop_form_filter_area')?>",
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
    url:"<?php echo URL::to('admin/shop_form_filter_sales')?>",
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


$(".search_shop").keypress(function( event ) {
  var value = $(this).val();
  if(event.which == 13 ) {
    if(value == ''){
      $("#shop_search-error").show();
    }
    else{
      $("#shop_search-error").hide();
      $(".loading_css").addClass("loading");
      setTimeout(function() {
        $("#data_table").dataTable().fnDestroy();
        $.ajax({
        url:"<?php echo URL::to('admin/search_common')?>",
        type:"post",
        dataType:"json",
        data:{value:value,type:1},
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
          $('[data-toggle="tooltip"]').tooltip();
          $(".loading_css").removeClass("loading");        
        } 
      })
      },500);
    }    
  }  
})

$(".search_account").keypress(function( event ) {
  var value = $(this).val();
  if(event.which == 13 ) {
    if(value == ''){
      $("#account_search-error").show();
    }
    else{
      $("#account_search-error").hide();
      $(".loading_css").addClass("loading");
      setTimeout(function() {
        $("#data_table").dataTable().fnDestroy();
        $.ajax({
        url:"<?php echo URL::to('admin/search_common')?>",
        type:"post",
        dataType:"json",
        data:{value:value,type:2},
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
          $('[data-toggle="tooltip"]').tooltip();
          $(".loading_css").removeClass("loading");        
        } 
      })
      },500);
    }    
  }  
})



$(".search_postcode").keypress(function( event ) {
  var value = $(this).val();
  if(event.which == 13 ) {
    if(value == ''){
      $("#postcode_search-error").show();
    }
    else{
      $("#postcode_search-error").hide();
      $(".loading_css").addClass("loading");
      setTimeout(function() {
        $("#data_table").dataTable().fnDestroy();
        $.ajax({
        url:"<?php echo URL::to('admin/search_common')?>",
        type:"post",
        dataType:"json",
        data:{value:value,type:3},
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
          $('[data-toggle="tooltip"]').tooltip();
          $(".loading_css").removeClass("loading");        
        } 
      })
      },500);
    }    
  }  
})


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
      email_id: {required: false, remote:"<?php echo URL::to('admin/shop_email_check')?>"},
      
    },
    messages: {            	
    	area_name:{required:"Please Select Area"},
      sales_rep:{required:"Please Select Sales REP"},
      route:{required:"Please Select Route"},
      shop_name:{required:"Enter Shop Name"},
      address1:{required:"Enter Address"},
      city:{required:"Enter City"},
      postcode:{required:"Enter Postcode"},
      email_id : {
          required : "Enter Email Id",
          remote : "Email Id is already exists",
      },
    },
});
$.ajaxSetup({async:false});
$('#edit-form').validate({
    rules: {          
      area_name:{required: true},
      sales_rep:{required: true},
      route:{required: true},
      shop_name:{required: true},
      address1:{required: true},
      city:{required: true},
      postcode:{required: true},
      email_id : {required: false, remote: { url:"<?php echo URL::to('admin/shop_email_check')?>", 
                  data: {'user_id':function(){return $('.id_filed').val()}},
                  async:false 
              },
          },
    },
    messages: {             
      area_name:{required:"Please Select Area"},
      sales_rep:{required:"Please Select Sales REP"},
      route:{required:"Please Select Route"},
      shop_name:{required:"Enter Shop Name"},
      address1:{required:"Enter Address"},
      city:{required:"Enter City"},
      postcode:{required:"Enter Postcode"},
      email_id: {
          required : "Enter Email Id",
          remote : "Email Id is already exists",
      },
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
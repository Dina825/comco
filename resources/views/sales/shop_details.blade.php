@extends('salesheader')
@section('content')
<style type="text/css">
#carouselExampleIndicators .carousel-indicators{position: fixed; right: 0px; top: 180px;  margin:0px; width: 30px; bottom: unset; left: unset; display: unset; }
#carouselExampleIndicators .carousel-indicators li{background: #f26e46; width: 100%; float: left; clear: both; margin-left: 0px; margin-right: 0px; margin-bottom: 2px;}
</style>


<div class="modal fade cheque_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="post" action="<?php echo URL::to('sales/cheque_update')?>" id="cheque-form">
                <div class="modal-header">
                    <h5 class="modal-title cheque_title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="sub_title3 cheque_content" style="line-height: 25px;"></div>     

                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                      <label class="form_radio">Cheque Issued
                         <input type="radio" value=""  class="area_radio cheque_issed_class" style="width:1px; height:1px" name="status_type"  required>
                         <span class="checkmark_radio"></span>
                      </label>
                    </div>              

                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                      <label class="form_radio">Cheque Return
                         <input type="radio" value=""  class="area_radio cheque_return_class" style="width:1px; height:1px" name="status_type"  required>
                         <span class="checkmark_radio"></span>
                      </label>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                      <label id="status_type-error" class="error" for="status_type" style="display: none;">Please select one of these options</label>
                    </div>
                    
                    <input type="hidden" id="cheque_status" name="cheque_status">

                </div>
                <div class="modal-footer">
                    <input type="hidden" class="id_filed" value="" name="id">                    
                    <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary model_add_button cheque_submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade add_sim_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" >
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add SIM Cards to shop</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <input type="text" placeholder="Please Scan" class="form-control sim_input_scan">
                        <input type="text" class="result_ssi_class" value="" name="sim_details" style="width:0px; height: 0px; border: 0px;">
                        <label class="result_ssi_class_error" style="display: none; color: #f00; width: 100%; margin-top: 10px; margin-bottom:0px; ">Please scan atleast one SIM</label>
                        <div style="float: left; margin-top: 10px; margin-bottom: 10px;">Count:<span class="count_class">0</span></div>
                        <input type="hidden" class="count_class_input" name="">
                        
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="sim_messaga_result" style="color: #f00;"></div>
                      <div class="sim_card_ul">
                        <ul class="sim_card_ul_class">
                          
                        </ul>
                      </div>
                    </div>
                  </div>
                  

                </div>
                <div class="modal-footer">
                  <input type="hidden" class="shop_id" value="<?php echo base64_encode($shop_details->shop_id) ?>" name="shop_id">
                  <input type="hidden" class="sales_rep_id" value="<?php echo base64_encode($userdetails->user_id)?>" name="sales_rep_id">
                  <input type="hidden" class="area_id" value="<?php echo base64_encode($shop_details->area_name)?>" name="area_id">
                  <input type="hidden" class="route_id" value="<?php echo base64_encode($shop_details->route)?>" name="route_id">
                  <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-primary model_add_button sim_save">Save</button>
                </div>
            
        </div>
    </div>
</div>

<div class="modal fade return_sim_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" >
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Return SIM Cards</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <input type="text" placeholder="Please Scan" class="form-control return_sim_input_scan">
                        <input type="text" class="return_result_ssi_class" value="" name="sim_details" style="width:0px; height: 0px; border: 0px;">
                        <label class="return_result_ssi_class_error" style="display: none; color: #f00; width: 100%; margin-top: 10px; margin-bottom:0px; ">Please scan atleast one SIM</label>
                        <div style="float: left; margin-top: 10px; margin-bottom: 10px;">Count:<span class="return_count_class">0</span></div>
                        <input type="hidden" class="return_count_class_input" name="">
                        
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="return_sim_messaga_result" style="color: #f00;"></div>
                      <div class="sim_card_ul">
                        <ul class="return_sim_card_ul_class">
                          
                        </ul>
                      </div>
                    </div>
                  </div>
                  

                </div>
                <div class="modal-footer">
                  <input type="hidden" class="return_shop_id" value="<?php echo base64_encode($shop_details->shop_id) ?>" name="shop_id">
                  <input type="hidden" class="return_sales_rep_id" value="<?php echo base64_encode($userdetails->user_id)?>" name="sales_rep_id">
                  <input type="hidden" class="return_area_id" value="<?php echo base64_encode($shop_details->area_name)?>" name="area_id">
                  <input type="hidden" class="return_route_id" value="<?php echo base64_encode($shop_details->route)?>" name="route_id">
                  <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-primary model_add_button return_sim">Return</button>
                </div>
            
        </div>
    </div>
</div>


<div class="modal fade visit_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" >
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Visit Only</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <h6>Are you sure want visit Only?</h6>
                      </div>
                      <div class="visit_sim_messaga_result" style="color: #f00;"></div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">No</button>
                  <button type="button" class="btn btn-primary model_add_button visit_save">Yes</button>
                </div>
            
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
                          <label>Select Route</label>
                          <select class="form-control route_class" name="route">
                            <option value="">Select Route</option>                            
                          </select>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                      <div class="form-group">
                          <label>Enter Shop Name</label>
                          <input type="type" class="form-control shop_class" name="shop_name" placeholder="Enter Shop Name" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Address line 1</label>
                          <input type="type" class="form-control address1_class" name="address1" placeholder="Enter Address line 1" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Address line 2</label>
                          <input type="type" class="form-control address2_class" name="address2" placeholder="Enter Address line 2">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Address line 3</label>
                          <input type="type" class="form-control address3_class" name="address3" placeholder="Enter Address line 3">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter City</label>
                          <input type="type" class="form-control city_class" name="city" placeholder="Enter City" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                          <label>Enter Postcode</label>
                          <input type="text" class="form-control postcode_class" name="postcode" placeholder="Enter Postcode" required>
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
                          <input type="type" class="form-control customer_class" name="customer_name" placeholder="Enter Customer Name">
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
                          <input type="type" class="form-control payee_class" name="payee_name" placeholder="Enter Payee Name">
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
                        <div class="form-group resetpassword" style="margin-top:27px; display: none;">
                          <a href="javascript:" class="btn btn-primary model_add_button reset_href" style="padding: 7px 15px">Reset Password</a>
                        </div>
                    </div>
                                      
                  </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" class="id_filed" name="id">
                    <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary model_add_button update_button">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-12 sales_right_panel">
  <input type="hidden" id="latitude" value="" name="">
  <input type="hidden" id="longitude" value="" name="">

  <?php $shop_id = $shop_details->shop_id?>

	<div class="row">
		<div class="col-lg-6 col-sm-6 col-6">
			<div class="main_title">Shop Details for <span><?php echo $shop_details->shop_name;?></span></div>
		</div>
    <div class="col-lg-6 col-sm-6 col-6 text-right" style="padding-top: 20px;">
      <div class="dropdown">
        <button class="btn btn-secondary common_button dropdown-toggle" style="border-radius: 0px; border: 0px; padding: 5px 10px" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Menu
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <a class="dropdown-item sim_scan_class" href="javascript:">Scan SIM</a>
          <a class="dropdown-item visit_class" href="javascript:">Visit Only</a>
          <a class="dropdown-item edit_icon" href="javascript:" data-element="<?php echo base64_encode($shop_details->shop_id)?>">Edit Shop</a>
          <!-- <a class="dropdown-item return_class" href="#">Return SIM</a> -->
          <a class="dropdown-item" href="<?php echo URL::to('sales/accessories/'.base64_encode($shop_details->shop_id))?>">Accessories order</a>
        </div>
      </div>
      <div class="div_commission" style="display: none;">Commission: &#163; <span class="span_commission"></span></div>
      <div class="div_bonus" style="display: none;">Bonus: &#163; <span class="span_bonus"></span></div>
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
        <div class="col-lg-12">            
          <p class="alert alert-info message_sim_save" style="display: none;">

            
                         
          </p>            
        </div>
    
		
		
    
    
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-type="multi" data-interval="false">
        <ol class="carousel-indicators">
          <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active" data-toggle="tooltip" data-placement="left" title="Shop Details"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="1" data-toggle="tooltip" data-placement="left" title="Network Activations Report"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="2" data-toggle="tooltip" data-placement="left" title="Sim Activation report"></li> 
          <li data-target="#carouselExampleIndicators" data-slide-to="3" data-toggle="tooltip" data-placement="left" title="Shop Commission"></li>          
        </ol>
        <div class="carousel-inner">
      <div id="shop_details" class="carousel-item active">
      <div class="table-responsive">
            <table class="own_table">
                <tr>
                  <td><b>Account Name:</b> <?php echo $shop_details->shop_name;?></td>
                </tr>
                <tr>
                  <td><b>Account Number:</b> CC-<?php echo $shop_details->shop_id;?></td>
                </tr>
                <tr>
                  <td><b>Account Created:</b> <?php echo date('d-M-Y', strtotime($shop_details->account_created));?></td>
                </tr>
                <tr>
                  <td><b style="float: left;">Address:&nbsp;</b> 
                    <div style="float: left;">
                      <?php
                      if($shop_details->address2 != ''){
                        $address2 = $shop_details->address2.'<br/>';
                      }
                      else{
                        $address2 = $shop_details->address2;
                      }
                      if($shop_details->address3 != ''){
                        $address3 = $shop_details->address3.'<br/>';
                      }
                      else{
                        $address3 = $shop_details->address3;
                      }
                      ?>
                      <?php echo $shop_details->address1;?><br/>
                      <?php echo $address2;?>
                      <?php echo $address3;?>
                      <?php echo $shop_details->city;?><br/>
                      <?php echo $shop_details->postcode;?>
                    </div></td>
                </tr>
                <tr>
                  <td><b>route:</b> <?php echo $route_details->route_name?></td>
                </tr>
                <tr>
                  <td><b>Contact name:</b> <?php echo $shop_details->customer_name ?></td>
                </tr>
                <tr>
                  <td><b>Phone number:</b> <?php echo $shop_details->phone_number ?></td>
                </tr>
                <tr>
                  <td><b>Shop type:</b> 
                    <?php 
                    if($shop_details->shop_type != '') {                      
                      $shoptype = DB::table('shop_type')->where('type_id', $shop_details->shop_type)->first();                      
                      echo $shoptype->shop_type;
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td><b>Mode of payment:</b>
                    <?php 
                    if($shop_details->payment_mode != '') {
                      $payment_mode = DB::table('mode_payment')->where('payment_id', $shop_details->payment_mode)->first();
                      echo $payment_mode->mode_text;
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td><b>Payee Name:</b> <?php echo $shop_details->payee_name ?></td>                  
                </tr>
                <tr>
                  <td><b>Email address:</b> <?php echo $shop_details->email_id ?></td>
                </tr>
                <tr>
                  <td><b>Shop potential sales:</b>
                    <?php 
                    if($shop_details->shop_potential != '') {
                      $potential = DB::table('potential_sale')->where('potential_id', $shop_details->shop_potential)->first();
                      echo $potential->shop_potential;
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td><b>Sales Rep name:</b>
                    <?php 

                    /*$sales_rep_details = DB::table('sales_rep')->where('user_id', $shop_details->sales_rep)->first();
                    echo $sales_rep_details->firstname*/

                    $route_details = DB::table('route')->where('route_id', $shop_details->route)->first();
                    $explode_route = explode(',', $route_details->sales_rep_id);
                    $salesrep='';
                    if(count($explode_route)){
                      foreach ($explode_route as $route) {
                        $sales_rep = DB::table('sales_rep')->where('user_id', $route)->first();

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
                    echo $salesrep;


                    ?>
                  </td>
                </tr>
                <tr>
                  <td><b>Last visit date :</b> 
                    <?php                    
                    $last_vist = DB::table('sim_allocate')->where('shop_id', $shop_details->shop_id)->orderBy('date', 'DESC')->first();
                    if(count($last_vist)){
                      echo date('d-M-Y', strtotime($last_vist->date));
                    }                    

                    
                    
                    ?>
                  </td>
                </tr>


          </table>
      </div>
      </div>

      <div id="network_activation" class="carousel-item">

      <div class="sub_title2">Network Activations</div>

      <div class="table-responsive">
        <table class="own_table">
            <thead>
              <tr>                
                <th style="text-align: center;">Network</th>  

                <?php
                for ($j = 0; $j <= 2; $j++) {
                    $last_three_month[] =  date("M-Y-m", strtotime(" -$j month"));                    
                }
                krsort($last_three_month);

                $output_month_three='';
                $month_three_array = array();
                $month_total_array = array();
                if(count($last_three_month)){
                  foreach ($last_three_month as $three_month) {
                    $explode_three_month_year = explode('-', $three_month);

                    $sim_count_three_details = DB::table('sim')->where('shop_id', $shop_details->shop_id)->where('activity_date', '!=','0000-00-00')->get();
                    $month_three_year = $explode_three_month_year[1].'-'.$explode_three_month_year[2];
                    array_push($month_three_array,$month_three_year);                    
                    $count_three_month=0;
                    if(count($sim_count_three_details)){
                      foreach ($sim_count_three_details as $sim_count_three) {
                        $activity_date = substr($sim_count_three->activity_date,0,7);

                        if($month_three_year == $activity_date){
                          $count_three_month = $count_three_month+1;                          
                        }
                      }
                    }
                    array_push($month_total_array,$count_three_month);

                    $output_month_three.='
                    <th style="text-align: center;">'.$explode_three_month_year[0].'&nbsp;('.$count_three_month.')</th>
                    ';
                  }
                }
                echo $output_month_three;
                ?>
                <th style="text-align: center;">Updated</th>
                <th style="text-align: center;">FC</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $output_network='';   

              if(count($networklist)){
                foreach ($networklist as $network) {  
                $output_total='';                
                  if(count($month_three_array))
                  {
                    $output_network.='<tr><td align="left">'.$network->network_name.'</td>';
                    foreach($month_three_array as $mon_three)
                    {                      
                      $sim_network_count = DB::table('sim')->where('shop_id', $shop_details->shop_id)->where('network_id', $network->network_name)->where('activity_date','like',$mon_three.'%')->count();
                      
                      if($sim_network_count == 0){
                        $sim_network_count='-';
                      }
                      $output_network.='<td align="center">'.$sim_network_count.'</td>'; 
                      $output_total.='<td align="center"></td>';
                    }
                    $output_network.='<td></td><td></td></tr>';                 
                  }

                }
                $output_total='';
                if(count($month_total_array)){
                  foreach ($month_total_array as $total_array) {
                    $output_total.='<td align="center">'.$total_array.'</td>';
                  }
                }

                $output_network.='<tr style="background: #eaeaea; font-weight:bold;">
                                  <td style="background: #eaeaea;">Total</td>'.$output_total.'
                                  <td></td>
                                  <td></td>
                                  </tr>';
              }
              else{
                $output_network='
                <tr>
                <td colspan="7" align="center">Empty</td>
              </tr>';
              }

              echo $output_network;
              ?>
            </tbody>
          </table>
      </div>
    </div>

    <div id="sim_activation_report" class="carousel-item">

      <div class="sub_title2">Sim Activation Report</div>

      <div class="table-responsive">
        <table class="own_table">
            <thead>
              <tr>
                <th>#</th>
                <?php
                for ($j = 0; $j <= 5; $j++) {
                    $last_six_month[] =  date("M-Y-m", strtotime(" -$j month"));                    
                }
                krsort($last_six_month);

                $output_month='';

                $month_array = array();
                $month_six_total_array = array();
                if(count($last_six_month)){
                  foreach ($last_six_month as $month) {
                    $explode_month_year = explode('-', $month);

                    $sim_count_details = DB::table('sim')->where('shop_id', $shop_details->shop_id)->where('activity_date', '!=','0000-00-00')->get();
                    $month_year = $explode_month_year[1].'-'.$explode_month_year[2];
                    array_push($month_array,$month_year);
                    $count_month=0;
                    if(count($sim_count_details)){
                      foreach ($sim_count_details as $sim_count) {
                        $activity_date = substr($sim_count->activity_date,0,7);

                        if($month_year == $activity_date){
                          $count_month = $count_month+1;                          
                        }
                      }
                    }
                    array_push($month_six_total_array,$count_month);
                    $output_month.='
                    <th style="text-align: center;">'.$explode_month_year[0].'&nbsp;('.$count_month.')</th>
                    ';
                  }
                }
                echo $output_month;
                ?>
              </tr>
            </thead>
            <tbody>
              <?php
                $output_network='';
                if(count($networklist)){
                  foreach ($networklist as $network) {
                    $explode_product=explode(',', $network->product_id);
                    $output_product='';                    
                    if(count($explode_product)){
                      foreach ($explode_product as $product) {

                        if(count($month_array))
                        {
                          $output_product.='<tr><td align="left">'.$product.'</td>';
                          foreach($month_array as $mon)
                          {
                            $sim_count_network = DB::table('sim')->where('shop_id', $shop_details->shop_id)->where('product_id', $product)->where('activity_date','like',$mon.'%')->count();

                            if($sim_count_network == 0){
                              $sim_count_network='-';
                            }

                            $output_product.='<td align="center">'.$sim_count_network.'</td>';
                          }
                          $output_product.='</tr>';
                        }
                      }                      
                    }
                    else{
                      $output_product='
                        <tr>
                        <td colspan="7" align="center">Empty</td>
                      </tr>';
                    }


                    $output_network.='<tr>
                      <td colspan="7">'.$network->network_name.'</td>
                    </tr>'.$output_product;
                  }
                  $output_total='';
                  if(count($month_six_total_array)){
                    foreach ($month_six_total_array as $total_array) {
                      $output_total.='<td align="center">'.$total_array.'</td>';
                    }
                  }

                  $output_network.='<tr style="background: #eaeaea; font-weight:bold;"><td style="background: #eaeaea;">Total</td>'.$output_total.'</tr>';
                }
                else{
                  $output_network='
                  <tr>
                    <td colspan="7" align="center">Empty</td>
                  </tr>';
                }
              echo $output_network;
              ?>
              
            </tbody>
          </table>
      </div>
</div>
      



<div id="shop_commission" class="carousel-item">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-12">
      <div class="sub_title2">Shop Commission Details </div>

      <div class="table-responsive">
          <table class="own_table">
            <thead>
              <tr>
                <th>Month</th>
                <th style="text-align: right;">Commission</th>
                <th style="text-align: right;">Bonus</th>                
              </tr>
            </thead>
            <tbody>
              <?php
              $get_shop_dates = DB::table('sim_processed')->where('shop_id',$shop_details->shop_id)->orderBy('month_year','desc')->groupBy('month_year')->get();
              $commission_total='';
              $bonus_total='';
              if(count($get_shop_dates))
              {
                foreach($get_shop_dates as $date)
                {
                  ?>
                  <tr>
                      <td><?php echo date('F-Y', strtotime($date->date)); ?></td>
                      <td align="right">&#163; 
                        <?php
                        $sum_commission = DB::table('sim_processed')->where('shop_id',$shop_details->shop_id)->where('month_year',$date->month_year)->sum('commission');
                        echo $sum_commission;
                        ?>
                      </td>
                      <td align="right">&#163; 
                        <?php
                        $sum_bonus = DB::table('sim_processed')->where('shop_id',$shop_details->shop_id)->where('month_year',$date->month_year)->sum('bonus');
                        echo $sum_bonus;
                        $commission_total = $commission_total+$sum_commission;
                        $bonus_total = $bonus_total+$sum_bonus;
                        ?>
                      </td>
                    </tr>
                  <?php
                }
                
              ?>
              <tr>
                <td><b>Total</b></td>
                <td align="right"><b>&#163; <?php echo $commission_total?></b></td>
                <td align="right"><b>&#163; <?php echo $bonus_total?></b></td>
              </tr>
              <?php
              }
              else
              {
                ?>
                <tr>
                  <td colspan="4" align="center">Empty</td>
                </tr>
                <?php
              }
              ?>
              </tbody>
        </table>
      </div>
      </div>
    </div>
     <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="sub_title2">Shop Commission Paid Details </div>
            <div class="table-responsive">
              <table class="own_table">
                <thead>
                  <tr>
                    <th style="font-size:13px;">S.No</th>
                    <th style="font-size:13px;">Date</th>                                        
                    <th style="text-align: center; font-size:13px;">Commission</th>
                    <th style="text-align: center; font-size:13px;">Bonus</th>                    
                    <th style="text-align: center;font-size:13px; ">Status</th>                 
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $paid_details = DB::table('commission_paid')->where('salesrep', $user_id)->where('shop_id', $shop_id)->get();
                  $paid_output='';
                  $total_paid_bonus='';
                  $total_paid_commission='';
                  $return_amount='';
                  $i=1;
                  if(count($paid_details)){
                    foreach ($paid_details as $paid) {
                      if($paid->bonus != ''){
                        $bonus = $paid->bonus;
                        $symbol = '&#163;';
                      }
                      else{
                        $bonus = '';
                        $symbol ='';
                      }

                      if($paid->status == 0){
                        $color = "";
                        $minus = '';
                        $status = '<a href="javascript:" class="cheque_class" data-element="'.base64_encode($paid->paid_id).'" style="color:green">In Process</a>';
                      }
                      elseif($paid->status == 1){
                        $color = '';
                        $minus = '';
                        $status = 'Issued';
                      }
                      elseif($paid->status == 2){
                        $color = "color:#f00";
                        $minus = '-';
                        $status = 'Cheque Return';
                      }
                      elseif($paid->status == 3){
                        $color = "color:#f00";
                        $minus = '-';
                        $status = 'Cheque Received';
                      }
                      elseif($paid->status == 4){
                        $color = "";
                        $minus = '';
                        $status = 'Sales REP Orders';
                      }

                      if($paid->status == 2 || $paid->status == 3){
                        $return_amount = $return_amount+$paid->commission;
                      }

                      $total_paid_commission = $total_paid_commission+$paid->commission;
                      $total_paid_bonus = $total_paid_bonus+$paid->bonus; 


                      $paid_output.='
                    <tr style="'.$color.'">
                      <td style="font-size:13px; padding:8px;">'.$i.'</td>
                      <td style="width:100px;font-size:13px; padding:8px;">'.date('d-M-Y', strtotime($paid->date)).'</td>                      
                      <td style="text-align: right;font-size:13px; padding:8px;">'.$minus.' &#163; '.$paid->commission.'</td>
                      <td style="text-align: right;font-size:13px; padding:8px;">'.$symbol.' '.$bonus.'</td>
                      
                      <td style="width:150px; font-size:13px; padding:8px;">'.$status.'</td>
                    </tr>';
                    $i++;
                    
                    }

                    if($return_amount == ''){
                      $total_paid_commission = $total_paid_commission;
                    }
                    else{
                      $total_paid_commission = $total_paid_commission-$return_amount;
                    }


                    if($total_paid_bonus != ''){
                      $total_paid_bonus = $total_paid_bonus;
                      $paid_symbol = '&#163; ';
                    }
                    else{
                      $total_paid_bonus = '';
                      $paid_symbol ='';
                    }

                    $pending_commission = $commission_total-$total_paid_commission;
                    $pending_bonus = $bonus_total-$total_paid_bonus;
                    $paid_output.='
                    <tr>
                      <td style="font-size:13px;" colspan="2"><b>Total</b></td>                      
                      <td style="text-align: right; font-size:13px; "><b>&#163; '.$total_paid_commission.'</b></td>
                      <td style="text-align: right; font-size:13px;"><b>'.$paid_symbol.''.$total_paid_bonus.'</b></td>
                      <td></td>
                      
                    </tr>';
                  }
                  else{
                    $paid_output='
                    <tr>
                      <td colspan="8" align="center">Empty</td>
                    </tr>';
                    $peding_details='';
                    $pending_commission = $commission_total;
                    $pending_bonus = $bonus_total;
                  }
                  echo $paid_output;                
                  ?>
                  <input type="hidden" value="<?php echo number_format_invoice($pending_commission)?>" class="commission_class">
                  <input type="hidden" value="<?php echo number_format_invoice($pending_bonus)?>" class="bonus_class">
                </tbody>
              </table>
              
            </div>
        </div>
    </div>


</div>
</div>
</div>
    </div>


	</div>
		</div>
	</div>
</div>                
<script>
    $(document).ready(function() {      
        $('.carousel').carousel('pause');
    });
</script>
<script type="text/javascript">
/*$(document).keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
});*/
$(window).click(function(e) {
if($(e.target).hasClass("sim_save")){  
  var sim = $(".result_ssi_class").val();
  var latitude = $("#latitude").val();
  if(sim == ''){
    $(".result_ssi_class_error").show();
  }
  else if(latitude == ''){
    $(".sim_messaga_result").show();
    $(".sim_messaga_result").html('Please GPS on your device, then allow to access and Refresh page.')
  }
  else{
    $(".sim_messaga_result").hide();
    $(".loading_css").addClass("loading");
    $(".result_ssi_class_error").hide();
    var shop_id = $(".shop_id").val();
    var sales_rep_id = $(".sales_rep_id").val();
    var area_id = $(".area_id").val();
    var route_id = $(".route_id").val();
    var latitude = $("#latitude").val();
    var longitude = $("#longitude").val();

    console.log(latitude);
    $.ajax({
      url:"<?php echo URL::to('sales/sim_save')?>",
      dataType:'json',
      data:{sim:sim, shop_id:shop_id, sales_rep_id:sales_rep_id, area_id:area_id, route_id:route_id, latitude:latitude, longitude:longitude },
      type:"post",
      success: function(result)
      {
        var close = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        $(".message_sim_save").html(result['message']+close);
        $(".message_sim_save").show();
        $(".add_sim_modal").modal('hide');
        $(".loading_css").removeClass("loading");
      }
    })
  }  
}


if($(e.target).hasClass("return_sim")){  
  var sim = $(".return_result_ssi_class").val();
  var latitude = $("#latitude").val();
  if(sim == ''){
    $(".return_result_ssi_class_error").show();
  }
  else if(latitude == ''){
    $(".return_sim_messaga_result").show();
    $(".return_sim_messaga_result").html('Please GPS on your device, then allow to access and Refresh page.')
  }
  else{
    $(".return_sim_messaga_result").hide();
    $(".loading_css").addClass("loading");
    $(".return_result_ssi_class_error").hide();
    var shop_id = $(".return_shop_id").val();
    var sales_rep_id = $(".return_sales_rep_id").val();
    var area_id = $(".return_area_id").val();
    var route_id = $(".return_route_id").val();
    var latitude = $("#latitude").val();
    var longitude = $("#longitude").val();
    $.ajax({
      url:"<?php echo URL::to('sales/return_sim')?>",
      dataType:'json',
      data:{sim:sim, shop_id:shop_id, sales_rep_id:sales_rep_id, area_id:area_id, route_id:route_id, latitude:latitude, longitude:longitude},
      type:"post",
      success: function(result)
      {
        var close = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        $(".message_sim_save").html(result['message']+close);
        $(".message_sim_save").show();
        $(".return_sim_modal").modal('hide');
        $(".loading_css").removeClass("loading");
      }
    })
  }  
}


if($(e.target).hasClass("visit_save")){
  var latitude = $("#latitude").val();
  if(latitude == ''){
    $(".visit_sim_messaga_result").show();
    $(".visit_sim_messaga_result").html('Please GPS on your device, then allow to access and Refresh page.')
  }
  else{


  $(".loading_css").addClass("loading");
  
  var shop_id = $(".shop_id").val();
  var sales_rep_id = $(".sales_rep_id").val();
  var area_id = $(".area_id").val();
  var route_id = $(".route_id").val();
  var latitude = $("#latitude").val();
  var longitude = $("#longitude").val();

  $.ajax({
    url:"<?php echo URL::to('sales/visit_only')?>",
    dataType:'json',
    data:{shop_id:shop_id, sales_rep_id:sales_rep_id, area_id:area_id, route_id:route_id, latitude:latitude, longitude:longitude},
    type:"post",
    success: function(result)
    {
      var close = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
      $(".message_sim_save").html(result['message']+close);
      $(".message_sim_save").show();
      $(".visit_modal").modal('hide');
      $(".loading_css").removeClass("loading");
    }
  })
  }


    
}



if($(e.target).hasClass("sim_scan_class")){  
  $(".add_sim_modal").modal('show');
  $(".sim_messaga_result").html('');
  $(".sim_card_ul_class").html('');
  $(".result_ssi_class").val('');
  $(".count_class_input").val('');
  $(".count_class").html('0');
  $(".sim_input_scan").val('');
}

if($(e.target).hasClass("visit_class")){  
  $(".visit_modal").modal('show');
}
if($(e.target).hasClass("return_class")){  
  $(".return_sim_modal").modal('show');
  $(".return_sim_messaga_result").html('');
  $(".return_sim_card_ul_class").html('');
  $(".return_result_ssi_class").val('');
  $(".return_count_class_input").val('');
  $(".return_count_class").html('0');
  $(".return_sim_input_scan").val('');

}

if($(e.target).hasClass("edit_icon")){
    var value = $(e.target).attr("data-element");
    var base_url = "<?php echo URL::to('/')?>"+'/';
    $(".loading_css").addClass("loading");
    $.ajax({
        url:"<?php echo URL::to('sales/shop_details')?>",
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
          $(".route_class").html(result['output_route']);

          if(result['email_id'] != ''){
            $(".email_class").prop("disabled", true);
            $(".resetpassword").show();            
            $(".reset_href").attr("href", base_url+'sales/shop_request_password/'+result['email_encode']);
          }

          $(".edit_modal").modal("show");
          $(".loading_css").removeClass("loading");
        }
      })
}

if($(e.target).hasClass("cheque_class")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {
    var value = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('sales/cheque_details')?>",
        dataType:'json',
        data:{id:value, type:4},
        type:"post",
        success: function(result)
        {
          $(".cheque_title").html(result['title']);
          $(".id_filed").val(result['id']);
          $(".cheque_content").html(result['content']);          
          $(".cheque_model").modal('show');
          $(".loading_css").removeClass("loading");
        }
      })
    },500);
}




})


$( ".sim_input_scan" ).keypress(function( event ) {
  if ( event.which == 13 ) {
    $(".loading_css").addClass("loading");
    var sim_value = $('.sim_input_scan').val();    
    var result_ssi_class = $(".result_ssi_class").val();
    var count = $(".count_class_input").val();
    $.ajax({
      url:"<?php echo URL::to('sales/sim_allocate_shop')?>",
      type:"post",
      dataType:"json",
      data:{value:sim_value,already_sim:result_ssi_class,count:count},
      success: function(result) { 
        $(".result_ssi_class_error").hide();
        if(result['type'] == 1){
          $(".sim_card_ul_class").html(result['result_sim']);        
          $(".result_ssi_class").val(result['id']);
          $(".sim_input_scan").val('');
          $(".sim_messaga_result").hide();
          $(".sim_messaga_result").html(result['message']);
          $(".count_class").html(result['count']);
          $(".count_class_input").val(result['count']);          
        }
        else{
          $(".sim_messaga_result").html(result['message']);
          $(".sim_messaga_result").show();
          $(".sim_input_scan").val('');          
        }
        $(".loading_css").removeClass("loading");
        
      } 
    })
  }
  
 
});




/*$( ".return_sim_input_scan" ).keypress(function( event ) {
  if ( event.which == 13 ) {
    $(".loading_css").addClass("loading");
    var sim_value = $('.return_sim_input_scan').val();    
    var return_result_ssi_class = $(".return_result_ssi_class").val();
    var shop_id = $(".return_shop_id").val();
    var count = $(".return_count_class_input").val();
    $.ajax({
      url:"<?php echo URL::to('sales/sim_return_shop')?>",
      type:"post",
      dataType:"json",
      data:{value:sim_value,already_sim:return_result_ssi_class,count:count,shop_id:shop_id},
      success: function(result) { 
        $(".return_result_ssi_class_error").hide();
        if(result['type'] == 1){
          $(".return_sim_card_ul_class").html(result['result_sim']);        
          $(".return_result_ssi_class").val(result['id']);
          $(".return_sim_input_scan").val('');
          $(".return_sim_messaga_result").hide();
          $(".return_sim_messaga_result").html(result['message']);
          $(".return_count_class").html(result['count']);
          $(".return_count_class_input").val(result['count']);          
        }
        else{
          $(".return_sim_messaga_result").html(result['message']);
          $(".return_sim_messaga_result").show();
          $(".return_sim_input_scan").val('');          
        }
        $(".loading_css").removeClass("loading");
        
      } 
    })
  }
  
 
});*/



$( document ).ready(function(getLocation) {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
      x.innerHTML = "Geolocation is not supported by this browser.";
    } 
});



function showPosition(position) {

  var latitude = position.coords.latitude;
  var longitude = position.coords.longitude;
  $("#latitude").val(latitude);
  $("#longitude").val(longitude);
}


$.ajaxSetup({async:false});
$('#edit-form').validate({
    rules: {                
      shop_name:{required: true},      
      address1:{required: true},
      city:{required: true},
      postcode:{required: true},
      email_id : {required: false, remote: { url:"<?php echo URL::to('sales/users_email_check')?>", 
                  data: {'user_id':function(){return $('.id_filed').val()}},
                  async:false 
              },
          },
    },
    messages: {                   
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

$(".cheque_issed_class").click(function(){
  $("#cheque_status").val(1);
  
})
$(".cheque_return_class").click(function(){
  $("#cheque_status").val(2);
  
})


$.ajaxSetup({async:false});
$('#cheque-form').validate({
    rules: {          
      status_type:{required: true},      
    },
    messages: {             
      status_type:{required:"Please select one of these options"},     
    },
});



$(document).ready(function() {
    var commission = $(".commission_class").val();
    var bonus = $(".bonus_class").val();
    if(commission != ''){
      $(".div_commission").show();
      $(".span_commission").html(commission);
    }
    else{
      $(".div_commission").hide();
    }

    if(bonus != ''){
      $(".div_bonus").show();
      $(".span_bonus").html(bonus);
    }
    else{
      $(".div_bonus").hide();
    }


});


</script>

@stop
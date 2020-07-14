@extends('userheaderlogin')
@section('content')
<style type="text/css">
	.own_table b{color: #022222}
</style>

<div class="width_100 margin_top_20">
	<div class="container">
		<div class="row inner_content">
  			<div class="col-lg-12">
		  		<?php
			      if(Session::has('message_login')) { ?>
			          <p class="alert alert-info">
			          	 <button type="button" class="close" data-dismiss="alert">&times;</button>
			          	<?php echo Session::get('message_login'); ?>
			          		
			          	</p>
			      <?php }
			      ?>
		  	</div>

			<div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
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

                    $sales_rep_details = DB::table('sales_rep')->where('user_id', $shop_details->sales_rep)->first();
                    echo $sales_rep_details->firstname
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

				<div class="width_100 big_price margin_top_20">
					<a href="javascript:" style="text-decoration: none;" class="btn btn-primary submit_button" data-toggle="modal" data-target="#change_password_modal">Change Password</a>					
				</div>

			</div>			

		</div>
	</div>
</div>



@stop
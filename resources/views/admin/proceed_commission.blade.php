@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->
<style>
.upload_img{
  position: absolute;
    top: 0px;
    z-index: 1;
    background: rgb(226, 226, 226);
    padding: 19% 0%;
    text-align: center;
    overflow: hidden;
}
.upload_text{
  font-size: 15px;
    font-weight: 800;
    color: #631500;
}
.error_message_xls{
  padding: 10px;
    background: #dfdfdf;
}
.input_rate{width: 100%; float:left; padding: 5px; }
</style>
<div class="upload_img" style="width: 100%;z-index:1"><p class="upload_text">Please wait while loading the page.</p><img src="<?php echo URL::to('assets/loading.gif'); ?>" width="100px" height="100px"></div>

	<div class="col-lg-11 col-md-12 col-sm-12 col-12 offset-lg-1 right_panel">
	<div class="row">
		<div class="col-lg-6 col-sm-6 col-6">
			<div class="main_title">Commission <span>Review</span></div>
		</div>
		<div class="col-lg-6 col-sm-6 col-6">
			<a href="#" class="logout_button"><i class="fas fa-sign-in-alt"></i> &nbsp;Logout</a>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<a href="#" class="common_button float_right add_button_class">Print</a>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 margin_top_20">
			<?php
			$details = DB::table('shop')->where('shop_id',$shop_id)->first();
            $area_details = DB::table('area')->where('area_id',$details->area_name)->first();
            $rep_details = DB::table('sales_rep')->where('sales_rep_id',$details->sales_rep)->first();
            $route_details = DB::table('route')->where('route_id',$details->route)->first();
            ?>
			<b>Shop Name:</b> <?php echo $details->shop_name; ?><br>
			<b>Area:</b> <?php echo $area_details->area_name; ?><br>
			<b>Route:</b> <?php echo $route_details->route_name; ?><br>
			<b>Sales REP:</b><?php echo $rep_details->firstname.' '.$rep_details->surname; ?>

			
			
			<form id="commission_form" action="<?php echo URL::to('admin/update_commission_for_date'); ?>" method="post">
			<div class="table-responsive margin_top_50">
				<b class="margin_top_40"><?php echo date('d-M-Y', strtotime($date_pending)); ?></b>

				<?php
				$today_output = '';
				$get_pending_network = DB::table('shop_commission')->where('shop_id',$shop_id)->where('commission_date',$date_pending)->where('status',0)->get();
				if(count($get_pending_network))
				{
					$today_output.='<table class="table table-striped">
			        	<thead>
			        		<tr>       			
			        			<th colspan="12" style="text-align: center;">Commssion Details</th>
			        		</tr>
			        		<tr>		        			
			        			<th>Network</th>
			        			<th>1st Connections</th>
			        			<th>2 Topup</th>		        					        			
			        			<th>3 Topup</th>
			        			<th>4 Topup</th>
			        			<th>5 Topup</th>
			        			<th>6 Topup</th>
			        			<th>7 Topup</th>
			        			<th>8 Topup</th>
			        			<th>9 Topup</th>
			        			<th>10 Topup</th>
			        			<th>Total Rate</th>
			        		</tr>
			        	</thead>
			        	<tbody>';
			        	$sub_total = 0;
						foreach($get_pending_network as $network)
						{
							$get_plan = DB::table('commission')->where('commission_id',$network->plan_id)->first();
							if(count($get_plan))
							{
								$one_array = unserialize($get_plan->first);
								$bonus_array = unserialize($get_plan->bonus);
								$two_array = unserialize($get_plan->second);
								$three_array = unserialize($get_plan->third);
								$four_array = unserialize($get_plan->fourth);
								$five_array = unserialize($get_plan->fifth);
								$six_array = unserialize($get_plan->sixth);
								$seven_array = unserialize($get_plan->seventh);
								$eight_array = unserialize($get_plan->eighth);
								$nine_array = unserialize($get_plan->ninth);
								$ten_array = unserialize($get_plan->tenth);

								$one_pending_euro = $network->one_connection * $one_array[$network->network_id];
								$bonus_pending_euro = $network->one_connection * $bonus_array[$network->network_id];
								$one_total_pending_euro = $one_pending_euro + $bonus_pending_euro;
								$two_pending_euro = $network->two_topup * $two_array[$network->network_id];
								$three_pending_euro = $network->three_topup * $three_array[$network->network_id];
								$four_pending_euro = $network->four_topup * $four_array[$network->network_id];
								$five_pending_euro = $network->five_topup * $five_array[$network->network_id];
								$six_pending_euro = $network->six_topup * $six_array[$network->network_id];
								$seven_pending_euro = $network->seven_topup * $seven_array[$network->network_id];
								$eight_pending_euro = $network->eight_topup * $eight_array[$network->network_id];
								$nine_pending_euro = $network->nine_topup * $nine_array[$network->network_id];
								$ten_pending_euro = $network->ten_topup * $ten_array[$network->network_id];

								$total_pending_euro = $one_total_pending_euro + $two_pending_euro + $three_pending_euro + $four_pending_euro + $five_pending_euro + $six_pending_euro + $seven_pending_euro + $eight_pending_euro + $nine_pending_euro + $ten_pending_euro;


								$sub_total = $sub_total + $total_pending_euro;
								$one_euro = $network->one_allotted * $one_array[$network->network_id];
								$bonus_euro = $network->one_allotted * $bonus_array[$network->network_id];
								$one_total_euro = $one_euro + $bonus_euro;
								$two_euro = $network->two_allotted * $two_array[$network->network_id];
								$three_euro = $network->three_allotted * $three_array[$network->network_id];
								$four_euro = $network->four_allotted * $four_array[$network->network_id];
								$five_euro = $network->five_allotted * $five_array[$network->network_id];
								$six_euro = $network->six_allotted * $six_array[$network->network_id];
								$seven_euro = $network->seven_allotted * $seven_array[$network->network_id];
								$eight_euro = $network->eight_allotted * $eight_array[$network->network_id];
								$nine_euro = $network->nine_allotted * $nine_array[$network->network_id];
								$ten_euro = $network->ten_allotted * $ten_array[$network->network_id];
							}
							
							$today_output.='<tr>
			        			<td>
			        			'.$network->network_id.'
			        			<input type="hidden" name="hidden_network[]" value="'.$network->network_id.'">
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->one_connection.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->one_allotted.'" class="form-control input_rate alloted_sim" data-element="one" data-value="'.$network->id.'" name="one_allotted[]" min="0" max="'.$network->one_connection.'" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->two_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->two_allotted.'" class="form-control input_rate alloted_sim" data-element="two" data-value="'.$network->id.'" name="two_allotted[]" min="0" max="'.$network->two_topup.'" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->three_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->three_allotted.'" class="form-control input_rate alloted_sim" data-element="three" data-value="'.$network->id.'" name="three_allotted[]" min="0" max="'.$network->three_topup.'" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->four_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->four_allotted.'" class="form-control input_rate alloted_sim" data-element="four" data-value="'.$network->id.'" name="four_allotted[]" min="0" max="'.$network->four_topup.'" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->five_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->five_allotted.'" class="form-control input_rate alloted_sim" data-element="five" data-value="'.$network->id.'" name="five_allotted[]" min="0" max="'.$network->five_topup.'" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->six_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->six_allotted.'" class="form-control input_rate alloted_sim" data-element="six" data-value="'.$network->id.'" name="six_allotted[]" min="0" max="'.$network->six_topup.'" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->seven_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->seven_allotted.'" class="form-control input_rate alloted_sim" data-element="seven" data-value="'.$network->id.'" name="seven_allotted[]" min="0" max="'.$network->seven_topup.'" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->eight_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->eight_allotted.'" class="form-control input_rate alloted_sim" data-element="eight" data-value="'.$network->id.'" name="eight_allotted[]" min="0" max="'.$network->eight_topup.'" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->nine_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->nine_allotted.'" class="form-control input_rate alloted_sim" data-element="nine" data-value="'.$network->id.'" name="nine_allotted[]" min="0" max="'.$network->nine_topup.'" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->ten_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->ten_allotted.'" class="form-control input_rate alloted_sim" data-element="ten" data-value="'.$network->id.'" name="ten_allotted[]" min="0" max="'.$network->ten_topup.'" required>
			        			</td>
			        			<td>
			        				&euro; '.$total_pending_euro.'
			        			</td>		        			
			        		</tr>
			        		<tr>
			        			<td></td>
			        			<td align="center">&euro;'.$one_total_pending_euro.' - &euro;<span class="one_allotted_euro">'.$one_total_euro.'</span></td>
			        			<td align="center">&euro;'.$two_pending_euro.' - &euro;<span class="two_allotted_euro">'.$two_euro.'</span></td>
			        			<td align="center">&euro;'.$three_pending_euro.' - &euro;<span class="three_allotted_euro">'.$three_euro.'</span></td>
			        			<td align="center">&euro;'.$four_pending_euro.' - &euro;<span class="four_allotted_euro">'.$four_euro.'</span></td>
			        			<td align="center">&euro;'.$five_pending_euro.' - &euro;<span class="five_allotted_euro">'.$five_euro.'</span></td>
			        			<td align="center">&euro;'.$six_pending_euro.' - &euro;<span class="six_allotted_euro">'.$six_euro.'</span></td>
			        			<td align="center">&euro;'.$seven_pending_euro.' - &euro;<span class="seven_allotted_euro">'.$seven_euro.'</span></td>
			        			<td align="center">&euro;'.$eight_pending_euro.' - &euro;<span class="eight_allotted_euro">'.$eight_euro.'</span></td>
			        			<td align="center">&euro;'.$nine_pending_euro.' - &euro;<span class="nine_allotted_euro">'.$nine_euro.'</span></td>
			        			<td align="center">&euro;'.$ten_pending_euro.' - &euro;<span class="ten_allotted_euro">'.$ten_euro.'</span></td>

			        			<td align="center"></td>		        			
			        		</tr>';
						}
						$today_output.='<tr>
		        			<td>Sub Total</td>
		        			<td></td>
		        			<td></td>
		        			<td></td>
		        			<td></td>
		        			<td></td>
		        			<td></td>
		        			<td></td>
		        			<td></td>
		        			<td></td>
		        			<td></td>
		        			<td align="center">&euro; '.$sub_total.'</td>		        			
		        		</tr>';
					$today_output.='</tbody>
					        </table>';
				}
				echo $today_output;
				?>
			</div>
			<div class="col-lg-12 text-right" style="margin-bottom: 30px;">
				<input type="hidden" name="hidden_date" id="hidden_date" value="<?php echo $_GET['date']; ?>">
				<input type="hidden" name="hidden_shop_id" id="hidden_shop_id" value="<?php echo $_GET['shop_id']; ?>">
				<input type="submit" name="update_commission" class="btn btn-primary model_add_button" value="Proceed">
			</div>
			</form>
	</div>
		</div>


		
		
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$("#commission_form").submit();
});
</script>

@stop
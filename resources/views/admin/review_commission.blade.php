@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->
<style type="text/css">
.input_rate{width: 100%; float:left; padding: 5px; }
</style>
	<div class="col-lg-11 col-md-12 col-sm-12 col-12 offset-lg-1 right_panel">
		<?php
			$details = DB::table('shop')->where('shop_id',$shop_id)->first();
            $area_details = DB::table('area')->where('area_id',$details->area_name)->first();
            $rep_details = DB::table('sales_rep')->where('sales_rep_id',$details->sales_rep)->first();
            $route_details = DB::table('route')->where('route_id',$details->route)->first();
            ?>
	<div class="row">
		<div class="col-lg-6 col-sm-6 col-6">
			<div class="main_title">Commission Review for <span><?php echo $details->shop_name; ?></span></div>
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
			
			<b>Shop Name:</b> <?php echo $details->shop_name; ?><br>
			<b>Area:</b> <?php echo $area_details->area_name; ?><br>
			<b>Route:</b> <?php echo $route_details->route_name; ?><br>
			<b>Sales REP:</b><?php echo $rep_details->firstname.' '.$rep_details->surname; ?>

			
			<?php
			$pending_output = '';
			$get_pending_networks = DB::table('shop_commission')->where('shop_id',$shop_id)->where('commission_date','!=',$date_pending)->orderBy('commission_date','asc')->groupBy('commission_date')->get();
			if(count($get_pending_networks))
			{
				$pending_output.='<h6 class="margin_top_40">Pending Commission</h6>';
				foreach($get_pending_networks as $networks)
				{
					$get_pending_network = DB::table('shop_commission')->where('shop_id',$shop_id)->where('commission_date',$networks->commission_date)->where('status',0)->get();
					if(count($get_pending_network))
					{
						$pending_output.='<b class="margin_top_40">'.date('d-M-Y', strtotime($networks->commission_date)).'</b>
							<div class="table-responsive margin_top_20">
						        <table class="table table-striped">
						        	<thead>
						        		<tr>
						        			<th>Network</th>
						        			<th>1st Connections</th>
						        			<th>Bonus</th>
						        			<th>2 topup</th>
						        			<th>3 topup</th>
						        			<th>4 topup</th>
						        			<th>5 topup</th>
						        			<th>6 topup</th>
						        			<th>7 topup</th>
						        			<th>8 topup</th>
						        			<th>9 topup</th>
						        			<th>10 topup</th>
						        		</tr>
						        	</thead>
						        	<tbody>';
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
							
							$pending_output.='<tr>
						        			<td>'.$network->network_id.'</td>
						        			<td>'.$network->one_allotted.' - &euro; '.$one_euro.'</td>
						        			<td>'.$network->one_allotted.' - &euro; '.$bonus_euro.'</td>
						        			<td>'.$network->two_allotted.' - &euro; '.$two_euro.'</td>
						        			<td>'.$network->three_allotted.' - &euro; '.$three_euro.'</td>
						        			<td>'.$network->four_allotted.' - &euro; '.$four_euro.'</td>
						        			<td>'.$network->five_allotted.' - &euro; '.$five_euro.'</td>
						        			<td>'.$network->six_allotted.' - &euro; '.$six_euro.'</td>
						        			<td>'.$network->seven_allotted.' - &euro; '.$seven_euro.'</td>
						        			<td>'.$network->eight_allotted.' - &euro; '.$eight_euro.'</td>
						        			<td>'.$network->nine_allotted.' - &euro; '.$nine_euro.'</td>
						        			<td>'.$network->ten_allotted.' - &euro; '.$ten_euro.'</td>
						        		</tr>';
						}
						$pending_output.='</tbody>
						        </table>
						    </div>';
					}
				}
			}
			echo $pending_output;
			?>
			

				<?php
				$today_output = '';
				$get_pending_network = DB::table('shop_commission')->where('shop_id',$shop_id)->where('commission_date',$date_pending)->where('status',0)->get();
				if(count($get_pending_network))
				{
					$url = URL::to('admin/update_commission_for_date');
					$today_output.='<form action="'.$url.'" method="post">
					<div class="table-responsive margin_top_50">
						<b class="margin_top_40">'.date('d-M-Y', strtotime($date_pending)).'</b>
					<table class="table table-striped">
			        	<thead>
			        		<tr>       			
			        			<th colspan="12" style="text-align: center;">Commssion Details</th>
			        		</tr>
			        		<tr>		        			
			        			<th>Network</th>
			        			<th>1st Connections</th>
			        			<th>Bonus</th>
			        			<th>2 Topup</th>		        					        			
			        			<th>3 Topup</th>
			        			<th>4 Topup</th>
			        			<th>5 Topup</th>
			        			<th>6 Topup</th>
			        			<th>7 Topup</th>
			        			<th>8 Topup</th>
			        			<th>9 Topup</th>
			        			<th>10 Topup</th>
			        			<th style="width:90px">Total Rate</th>
			        		</tr>
			        	</thead>
			        	<tbody>';
			        	$sub_pending_total = 0;
			        	$sub_allotted_total = 0;
			        	$pending_bonus_total = 0;
			        	$allotted_bonus_total = 0;
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
								$two_pending_euro = $network->two_topup * $two_array[$network->network_id];
								$three_pending_euro = $network->three_topup * $three_array[$network->network_id];
								$four_pending_euro = $network->four_topup * $four_array[$network->network_id];
								$five_pending_euro = $network->five_topup * $five_array[$network->network_id];
								$six_pending_euro = $network->six_topup * $six_array[$network->network_id];
								$seven_pending_euro = $network->seven_topup * $seven_array[$network->network_id];
								$eight_pending_euro = $network->eight_topup * $eight_array[$network->network_id];
								$nine_pending_euro = $network->nine_topup * $nine_array[$network->network_id];
								$ten_pending_euro = $network->ten_topup * $ten_array[$network->network_id];

								$total_pending_euro = $one_pending_euro + $two_pending_euro + $three_pending_euro + $four_pending_euro + $five_pending_euro + $six_pending_euro + $seven_pending_euro + $eight_pending_euro + $nine_pending_euro + $ten_pending_euro;
								$sub_pending_total = $sub_pending_total + $total_pending_euro;
								$pending_bonus_total = $pending_bonus_total + $bonus_pending_euro;


								$one_euro = $network->one_allotted * $one_array[$network->network_id];
								$bonus_euro = $network->one_allotted * $bonus_array[$network->network_id];
								$two_euro = $network->two_allotted * $two_array[$network->network_id];
								$three_euro = $network->three_allotted * $three_array[$network->network_id];
								$four_euro = $network->four_allotted * $four_array[$network->network_id];
								$five_euro = $network->five_allotted * $five_array[$network->network_id];
								$six_euro = $network->six_allotted * $six_array[$network->network_id];
								$seven_euro = $network->seven_allotted * $seven_array[$network->network_id];
								$eight_euro = $network->eight_allotted * $eight_array[$network->network_id];
								$nine_euro = $network->nine_allotted * $nine_array[$network->network_id];
								$ten_euro = $network->ten_allotted * $ten_array[$network->network_id];

								$total_allotted_euro = $one_euro + $two_euro + $three_euro + $four_euro + $five_euro + $six_euro + $seven_euro + $eight_euro + $nine_euro + $ten_euro;

								$sub_allotted_total = $sub_allotted_total + $total_allotted_euro;
								$allotted_bonus_total = $allotted_bonus_total + $bonus_euro;
							}
							
							$today_output.='<tr>
			        			<td>
			        			'.$network->network_id.'
			        			<input type="hidden" name="hidden_network[]" value="'.$network->network_id.'">
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->one_connection.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->one_allotted.'" class="form-control input_rate alloted_sim" data-element="one" data-value="'.$network->id.'" name="one_allotted[]" min="0" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->one_connection.'" class="form-control input_rate" name="">
			        				<input type="number" readonly value="'.$network->one_allotted.'" class="form-control input_rate bonus_rate alloted_sim" data-element="bonus" data-value="'.$network->id.'" name="bonus_allotted[]" min="0" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->two_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->two_allotted.'" class="form-control input_rate alloted_sim" data-element="two" data-value="'.$network->id.'" name="two_allotted[]" min="0" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->three_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->three_allotted.'" class="form-control input_rate alloted_sim" data-element="three" data-value="'.$network->id.'" name="three_allotted[]" min="0" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->four_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->four_allotted.'" class="form-control input_rate alloted_sim" data-element="four" data-value="'.$network->id.'" name="four_allotted[]" min="0" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->five_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->five_allotted.'" class="form-control input_rate alloted_sim" data-element="five" data-value="'.$network->id.'" name="five_allotted[]" min="0" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->six_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->six_allotted.'" class="form-control input_rate alloted_sim" data-element="six" data-value="'.$network->id.'" name="six_allotted[]" min="0" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->seven_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->seven_allotted.'" class="form-control input_rate alloted_sim" data-element="seven" data-value="'.$network->id.'" name="seven_allotted[]" min="0" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->eight_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->eight_allotted.'" class="form-control input_rate alloted_sim" data-element="eight" data-value="'.$network->id.'" name="eight_allotted[]" min="0" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->nine_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->nine_allotted.'" class="form-control input_rate alloted_sim" data-element="nine" data-value="'.$network->id.'" name="nine_allotted[]" min="0" required>
			        			</td>
			        			<td>
			        				<input type="text" readonly value="'.$network->ten_topup.'" class="form-control input_rate" name="">
			        				<input type="number" value="'.$network->ten_allotted.'" class="form-control input_rate alloted_sim" data-element="ten" data-value="'.$network->id.'" name="ten_allotted[]" min="0" required>
			        			</td>
			        			<td>
			        				&euro; '.$total_pending_euro.'<br/>
			        				&euro; <spam class="pending_euros" id="euros_'.$shop_id.'_'.$network->id.'" style="line-height:80px">'.$total_allotted_euro.'</spam>
			        			</td>		        			
			        		</tr>
			        		<tr class="tr_total tr_'.$shop_id.'_'.$network->id.'" data-element="'.$shop_id.'_'.$network->id.'">
			        			<td></td>
			        			<td align="center">&euro;'.$one_pending_euro.' - &euro;<span class="allotted_euro one_allotted_euro">'.$one_euro.'</span></td>
			        			<td align="center">&euro;'.$bonus_pending_euro.' - &euro;<span class="allotted_bonus_euro bonus_allotted_euro">'.$bonus_euro.'</span></td>
			        			<td align="center">&euro;'.$two_pending_euro.' - &euro;<span class="allotted_euro two_allotted_euro">'.$two_euro.'</span></td>
			        			<td align="center">&euro;'.$three_pending_euro.' - &euro;<span class="allotted_euro three_allotted_euro">'.$three_euro.'</span></td>
			        			<td align="center">&euro;'.$four_pending_euro.' - &euro;<span class="allotted_euro four_allotted_euro">'.$four_euro.'</span></td>
			        			<td align="center">&euro;'.$five_pending_euro.' - &euro;<span class="allotted_euro five_allotted_euro">'.$five_euro.'</span></td>
			        			<td align="center">&euro;'.$six_pending_euro.' - &euro;<span class="allotted_euro six_allotted_euro">'.$six_euro.'</span></td>
			        			<td align="center">&euro;'.$seven_pending_euro.' - &euro;<span class="allotted_euro seven_allotted_euro">'.$seven_euro.'</span></td>
			        			<td align="center">&euro;'.$eight_pending_euro.' - &euro;<span class="allotted_euro eight_allotted_euro">'.$eight_euro.'</span></td>
			        			<td align="center">&euro;'.$nine_pending_euro.' - &euro;<span class="allotted_euro nine_allotted_euro">'.$nine_euro.'</span></td>
			        			<td align="center">&euro;'.$ten_pending_euro.' - &euro;<span class="allotted_euro ten_allotted_euro">'.$ten_euro.'</span></td>

			        			<td align="center">

			        			</td>		        			
			        		</tr>';
						}
						$today_output.='<tr>
		        			<td colspan="12">Sub Total</td>
		        			<td align="center">&euro; '.$sub_pending_total.' - &euro; <spam class="total_allotted_euros">'.$sub_allotted_total.'</spam></td>		        			
		        		</tr>
		        		<tr style="background-color: rgba(0,0,0,.05);">
		        			<td colspan="12">Bonus</td>
		        			<td align="center">&euro; '.$pending_bonus_total.' - &euro; <spam class="total_allotted_bonus_euros">'.$allotted_bonus_total.'</spam></td>		        			
		        		</tr>
		        		';
					$today_output.='</tbody>
					        </table>
					        </div>
						<div class="col-lg-12 text-right" style="margin-bottom: 30px;">
							<input type="hidden" name="hidden_date" id="hidden_date" value="'.$_GET['date'].'">
							<input type="hidden" name="hidden_shop_id" id="hidden_shop_id" value="'.$_GET['shop_id'].'">
							<input type="submit" name="update_commission" class="btn btn-primary model_add_button" value="Proceed">
						</div>
						</form>';
				}
				echo $today_output;
				?>
	</div>
		</div>


		
		
	</div>
</div>
<script type="text/javascript">
$(window).keyup(function(e) {
    var valueTimmer;                //timer identifier
    var valueInterval = 500;  //time in ms, 5 second for example
    if($(e.target).hasClass('alloted_sim'))
    {
        var that = $(e.target);
        var input_val = $(e.target).val();  
        var period_id = $(e.target).attr("data-element");
        var network_id = $(e.target).attr("data-value");
    	clearTimeout(valueTimmer);
    	valueTimmer = setTimeout(doneTyping, valueInterval,input_val, period_id,network_id,that);
    }
});
$(window).change(function(e) {
    if($(e.target).hasClass('alloted_sim'))
    {
        var that = $(e.target);
        var input_val = $(e.target).val();  
        var period_id = $(e.target).attr("data-element");
        var network_id = $(e.target).attr("data-value");
        doneTyping(input_val, period_id,network_id,that);
    }
});
function doneTyping (value,period,network,that) {
	if(period == "one" || period == "bonus")
	{
		$.ajax({
		    url:"<?php echo URL::to('admin/check_commission_plan')?>",
		    type:"post",
		    data:{value:value, period:period,network:network},
		    success: function(result) {
		    	var split_result = result.split("||");
		    	that.parents("tbody").find(".tr_<?php echo $shop_id; ?>_"+network).find("."+period+"_allotted_euro").html(split_result[0]);
		    	that.parents("tbody").find(".tr_<?php echo $shop_id; ?>_"+network).find(".bonus_allotted_euro").html(split_result[1]);

		    	that.parents("tr").find(".bonus_rate").val(value);
		    	var sumcount = 0;
		    	that.parents("tbody").find(".tr_<?php echo $shop_id; ?>_"+network).find(".allotted_euro").each(function() {
		    		var textvalue = $(this).html();
		    		sumcount = sumcount + parseFloat(textvalue);
		    	});
		    	that.parents("tbody").find("#euros_<?php echo $shop_id; ?>_"+network).html(sumcount);
		    	var sumcountval = 0;
		    	that.parents("tbody").find(".pending_euros").each(function() {
		    		var textvalue = $(this).html();
		    		sumcountval = sumcountval + parseFloat(textvalue);
		    	})
		    	that.parents("tbody").find(".total_allotted_euros").html(sumcountval);

		    	var sumbonusval = 0;
		    	that.parents("tbody").find(".allotted_bonus_euro").each(function() {
		    		var textvalue = $(this).html();
		    		sumbonusval = sumbonusval + parseFloat(textvalue);
		    	})
		    	that.parents("tbody").find(".total_allotted_bonus_euros").html(sumbonusval);
		    }
		});
	}
	else{
		$.ajax({
		    url:"<?php echo URL::to('admin/check_commission_plan')?>",
		    type:"post",
		    data:{value:value, period:period,network:network},
		    success: function(result) {
		    	that.parents("tbody").find(".tr_<?php echo $shop_id; ?>_"+network).find("."+period+"_allotted_euro").html(result);
		    	var sumcount = 0;
		    	that.parents("tbody").find(".tr_<?php echo $shop_id; ?>_"+network).find(".allotted_euro").each(function() {
		    		var textvalue = $(this).html();
		    		sumcount = sumcount + parseFloat(textvalue);
		    	});
		    	that.parents("tbody").find("#euros_<?php echo $shop_id; ?>_"+network).html(sumcount);
		    	var sumcountval = 0;
		    	that.parents("tbody").find(".pending_euros").each(function() {
		    		var textvalue = $(this).html();
		    		sumcountval = sumcountval + parseFloat(textvalue);
		    	})
		    	that.parents("tbody").find(".total_allotted_euros").html(sumcountval);
		    }
		});
	}
}
$(".deactive_class").click(function(){
	var value = $(this).attr("data-element");
	$(".active_deactive_title").html("Deactive");
	$(".active_deactive_content").html("Are you sure want deactive "+value+"?");	
	$(".status_modal").modal();
})
$(".active_class").click(function(){
	var value = $(this).attr("data-element");
	$(".active_deactive_title").html("Active");
	$(".active_deactive_content").html("Are you sure want Active "+value+"?");	
	$(".status_modal").modal();
})
/*$(".edit_icon").click(function(){
	var value = $(this).attr("data-element");	
	$(".add_input").val(value);
	$(".model_add_button").html('Update');
	$(".add_modal").modal();	
})*/
$(".add_button_class").click(function(){	
	$(".add_input").val('');
	$(".model_add_button").html('Add New');
	$(".add_modal").modal();	
})
</script>

@stop
@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->


<style type="text/css">
.input_rate{width: 100%; float:left; padding: 5px; }
</style>


	<div class="col-lg-11 col-md-12 col-sm-12 col-12 offset-lg-1 right_panel">
	<div class="row">
		<div class="col-lg-6 col-sm-6 col-6">
			<div class="main_title">Shop Commission for <span><?php echo $shop_details->shop_name?></span></div>
		</div>
		<div class="col-lg-6 col-sm-6 col-6">
			<a href="#" class="logout_button"><i class="fas fa-sign-in-alt"></i> &nbsp;Logout</a>
		</div>
	</div>
	<div class="row">
		

		<?php
		$today_output = '';
		$get_pending_networks = DB::table('shop_commission')->where('shop_id',$shop_id)->where('status',0)->orderBy('commission_date','asc')->groupBy('commission_date')->get();
		if(count($get_pending_networks))
		{
			$today_output.='<div class="col-lg-12">
			<h6>Commission Pending</h6>
		</div>';
			foreach($get_pending_networks as $networks)
			{
				$get_pending_network = DB::table('shop_commission')->where('shop_id',$shop_id)->where('commission_date',$networks->commission_date)->where('status',0)->get();
				if(count($get_pending_network))
				{
					$url = URL::to('admin/update_commission_for_date_shop');
					$today_output.='<form action="'.$url.'" method="post">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<b class="margin_top_40">'.date('d-M-Y', strtotime($networks->commission_date)).'</b>
							<div class="table-responsive">
								<table class="table table-striped">
						        	<thead>
						        		<tr>       			
						        			<th colspan="12" style="text-align: center;">Commission Details</th>
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
						        			<th>Total Rate</th>
						        		</tr>
						        	</thead>
						        	<tbody>';
						        	$sub_pending_total = 0;
			        				$sub_allotted_total = 0;
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

											$total_pending_euro = $one_pending_euro + $bonus_pending_euro + $two_pending_euro + $three_pending_euro + $four_pending_euro + $five_pending_euro + $six_pending_euro + $seven_pending_euro + $eight_pending_euro + $nine_pending_euro + $ten_pending_euro;
											$sub_pending_total = $sub_pending_total + $total_pending_euro;


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

											$total_allotted_euro = $one_euro + $bonus_euro + $two_euro + $three_euro + $four_euro + $five_euro + $six_euro + $seven_euro + $eight_euro + $nine_euro + $ten_euro;

											$sub_allotted_total = $sub_allotted_total + $total_allotted_euro;
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
						        			<td align="center">&euro;'.$bonus_pending_euro.' - &euro;<span class="allotted_euro bonus_allotted_euro">'.$bonus_euro.'</span></td>
						        			<td align="center">&euro;'.$two_pending_euro.' - &euro;<span class="allotted_euro two_allotted_euro">'.$two_euro.'</span></td>
						        			<td align="center">&euro;'.$three_pending_euro.' - &euro;<span class="allotted_euro three_allotted_euro">'.$three_euro.'</span></td>
						        			<td align="center">&euro;'.$four_pending_euro.' - &euro;<span class="allotted_euro four_allotted_euro">'.$four_euro.'</span></td>
						        			<td align="center">&euro;'.$five_pending_euro.' - &euro;<span class="allotted_euro five_allotted_euro">'.$five_euro.'</span></td>
						        			<td align="center">&euro;'.$six_pending_euro.' - &euro;<span class="allotted_euro six_allotted_euro">'.$six_euro.'</span></td>
						        			<td align="center">&euro;'.$seven_pending_euro.' - &euro;<span class="allotted_euro seven_allotted_euro">'.$seven_euro.'</span></td>
						        			<td align="center">&euro;'.$eight_pending_euro.' - &euro;<span class="allotted_euro eight_allotted_euro">'.$eight_euro.'</span></td>
						        			<td align="center">&euro;'.$nine_pending_euro.' - &euro;<span class="allotted_euro nine_allotted_euro">'.$nine_euro.'</span></td>
						        			<td align="center">&euro;'.$ten_pending_euro.' - &euro;<span class="allotted_euro ten_allotted_euro">'.$ten_euro.'</span></td>

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
					        			<td></td>
					        			<td align="center">&euro; '.$sub_pending_total.' - &euro; <spam class="total_allotted_euros">'.$sub_allotted_total.'</spam></td>      			
					        		</tr>';
								$today_output.='</tbody>
						        </table>
						    </div>
						</div>
						<div class="col-lg-12 text-right">
							<input type="text" class="form-control hidden_changed_date" style="max-width: 250px; float: right;" placeholder="Select Date" name="hidden_changed_date" required>
						</div>
						<br/><br/>
						<div class="col-lg-12 text-right">
							<input type="hidden" name="hidden_date" id="hidden_date" value="'.$networks->commission_date.'">
							<input type="hidden" name="hidden_shop_id" id="hidden_shop_id" value="'.$_GET['shop_id'].'">
							<input type="submit" name="update_commission" class="btn btn-primary model_add_button" value="Proceed">
						</div>
						</form>';
				}
			}
		}
		echo $today_output;
		?>

		<div class="col-lg-12 margin_top_30">
			<h6>Commission Paid</h6>			

			<div class="table-responsive">
		        <table class="own_table">
		        	<thead>
		        		<tr>
		        			<th>Month</th>
		        			<th style="text-align: right;">1st Connection</th>
		        			<th style="text-align: right;">Topup Connection</th>
		        			<th style="text-align: right;">Commission</th>
		        			<th style="text-align: right;">Bonus</th>
		        			
		        		</tr>
		        	</thead>
		        	<tbody>
		        		<?php
		        		$get_shop_dates = DB::table('sim_processed')->where('shop_id',$_GET['shop_id'])->orderBy('month_year','desc')->groupBy('month_year')->get();
		        		if(count($get_shop_dates))
		        		{
		        			foreach($get_shop_dates as $date)
		        			{
		        				?>
		        				<tr>
					          		<td><?php echo date('F-Y', strtotime($date->date)); ?></td>
					          		<td>
					          			<?php
					          			$get_networks = DB::table('sim_processed')->where('shop_id',$_GET['shop_id'])->where('month_year',$date->month_year)->groupBy('network_id')->get();
					          			if(count($get_networks))
					          			{
					          				foreach($get_networks as $network)
					          				{
					          					$get_network_first_count = DB::table('sim_processed')->where('shop_id',$_GET['shop_id'])->where('month_year',$date->month_year)->where('network_id',$network->network_id)->sum('first_connection');

					          					echo $network->network_id.':'.$get_network_first_count.'<br/>';
					          				}
					          			}
					          			?>
					          		</td>
					          		<td>
					          			<?php
					          			$get_networks = DB::table('sim_processed')->where('shop_id',$_GET['shop_id'])->where('month_year',$date->month_year)->groupBy('network_id')->get();
					          			if(count($get_networks))
					          			{
					          				foreach($get_networks as $network)
					          				{
					          					$get_network_first_count = DB::table('sim_processed')->where('shop_id',$_GET['shop_id'])->where('month_year',$date->month_year)->where('network_id',$network->network_id)->sum('topups');

					          					echo $network->network_id.':'.$get_network_first_count.'<br/>';
					          				}
					          			}
					          			?>
					          		</td>
					          		<td align="right">&#163; 
					          			<?php
					          			$sum_commission = DB::table('sim_processed')->where('shop_id',$_GET['shop_id'])->where('month_year',$date->month_year)->sum('commission');
					          			echo $sum_commission;
					          			?>
					          		</td>
					          		<td align="right">&#163; 
					          			<?php
					          			$sum_bonus = DB::table('sim_processed')->where('shop_id',$_GET['shop_id'])->where('month_year',$date->month_year)->sum('bonus');
					          			echo $sum_bonus;
					          			?>
					          		</td>
					          		
					          	</tr>
		        				<?php
		        			}
		        		}
		        		else{
		        			echo '<tr><td align="center" colspan="6">Empty</td><tr>';
		        		}
		        		?>
		          	</tbody>

		      </table>
		  </div>	
		  </div>		



	</div>


		
		
	</div>
</div>
<script type="text/javascript">
$(function () {
  $(".hidden_changed_date").datetimepicker({
     format: 'L',
     format: 'YYYY-MM-DD',
     icons: {
        time: "fa fa-clock-o",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down",
        previous: "fa fa-chevron-left",
        next: "fa fa-chevron-right",
        today: "fa fa-clock-o",
        clear: "fa fa-trash-o"
    },
    minDate: moment().startOf('day').millisecond(0).second(0).minute(0).hour(0),
  });
});
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
		    		sumcount = sumcount + parseInt(textvalue);
		    	});
		    	that.parents("tbody").find("#euros_<?php echo $shop_id; ?>_"+network).html(sumcount);
		    	var sumcountval = 0;
		    	that.parents("tbody").find(".pending_euros").each(function() {
		    		var textvalue = $(this).html();
		    		sumcountval = sumcountval + parseInt(textvalue);
		    	})
		    	that.parents("tbody").find(".total_allotted_euros").html(sumcountval);
		    }
		});
	}
	else{
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
		    		sumcount = sumcount + parseInt(textvalue);
		    	});
		    	that.parents("tbody").find("#euros_<?php echo $shop_id; ?>_"+network).html(sumcount);
		    	var sumcountval = 0;
		    	that.parents("tbody").find(".pending_euros").each(function() {
		    		var textvalue = $(this).html();
		    		sumcountval = sumcountval + parseInt(textvalue);
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
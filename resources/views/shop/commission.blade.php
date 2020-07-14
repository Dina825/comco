@extends('userheaderlogin')
@section('content')
<div class="width_100 margin_top_20">
	<div class="container">
		<div class="row inner_content">
			
			<div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
				
				<div class="table-responsive">					
					<table  class="own_table">
						<thead>
							<tr>
								<th style="width: 100px;">Month</th>
								<th>1st Connection</th>
								<th>Topup Connection</th>
								<th>Commission</th>
								<th style="width: 100px;">Bonus</th>
							</tr>
						</thead>
						<tbody>
							<?php
			        		$get_shop_dates = DB::table('sim_processed')->where('shop_id',$shop_id)->orderBy('month_year','desc')->groupBy('month_year')->get();
			        		if(count($get_shop_dates))
			        		{
			        			foreach($get_shop_dates as $date)
			        			{
			        				?>
			        				<tr>
						          		<td><?php echo date('F-Y', strtotime($date->date)); ?></td>
						          		<td>
						          			<?php
						          			$get_networks = DB::table('sim_processed')->where('shop_id',$shop_id)->where('month_year',$date->month_year)->groupBy('network_id')->get();
						          			if(count($get_networks))
						          			{
						          				foreach($get_networks as $network)
						          				{
						          					$get_network_first_count = DB::table('sim_processed')->where('shop_id',$shop_id)->where('month_year',$date->month_year)->where('network_id',$network->network_id)->sum('first_connection');

						          					echo $network->network_id.':'.$get_network_first_count.'<br/>';
						          				}
						          			}
						          			?>
						          		</td>
						          		<td>
						          			<?php
						          			$get_networks = DB::table('sim_processed')->where('shop_id',$shop_id)->where('month_year',$date->month_year)->groupBy('network_id')->get();
						          			if(count($get_networks))
						          			{
						          				foreach($get_networks as $network)
						          				{
						          					$get_network_first_count = DB::table('sim_processed')->where('shop_id',$shop_id)->where('month_year',$date->month_year)->where('network_id',$network->network_id)->sum('topups');

						          					echo $network->network_id.':'.$get_network_first_count.'<br/>';
						          				}
						          			}
						          			?>
						          		</td>
						          		<td align="right">&#163; 
						          			<?php
						          			$sum_commission = DB::table('sim_processed')->where('shop_id',$shop_id)->where('month_year',$date->month_year)->sum('commission');
						          			echo $sum_commission;
						          			?>
						          		</td>
						          		<td align="right">&#163; 
						          			<?php
						          			$sum_bonus = DB::table('sim_processed')->where('shop_id',$shop_id)->where('month_year',$date->month_year)->sum('bonus');
						          			echo $sum_bonus;
						          			?>
						          		</td>					          		
						          	</tr>
			        				<?php
			        			}
			        		}
			        		else{
			        			echo '<tr><td colspan="6" align="center">Empty</td></tr>';
			        		}
			        		?>
							
							
							
						</tbody>
					</table>					
				</div>
			</div>			

		</div>
	</div>
</div>



@stop
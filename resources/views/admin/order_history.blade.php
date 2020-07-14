@extends('adminheader')
@section('content')
<style type="text/css">
.heading_class{width: 100%; color: #f26e46; text-align: left; }
.heading_class:hover{text-decoration: none; color: #f26e46;}
.card_header_class{padding: 5px; border-radius: 0px; }
.card_class{border-radius:0px; margin-bottom: 2px; box-shadow: none; }
.card_body_class{padding: 3px;}
.own_table td{padding: 8px;}
</style>
<!-- Content Header (Page header) -->
	<div class="col-lg-11 col-md-12 col-sm-12 col-12 offset-lg-1 right_panel">
	<div class="row">
		<div class="col-lg-6 col-sm-6 col-6">
			<div class="main_title">Order <span>History</span></div>
		</div>
		<div class="col-lg-6 col-sm-6 col-6">	      
			<a href="<?php echo URL::to('admin/logout')?>" class="logout_button"><i class="fas fa-sign-in-alt"></i> &nbsp;Logout</a>
	    </div>
	</div>
	<div class="row">
		<div class="col-lg-12 text-left">
			<div class="dropdown dropdown_admin">
		        <button class="btn btn-secondary common_button dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		          Menu
		        </button>
		        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
		          <a class="dropdown-item" href="<?php echo URL::to('admin/accessories')?>">Dashboard</a>
		          <a class="dropdown-item" href="<?php echo URL::to('admin/manage_category')?>">Manage Category</a>
		          <a class="dropdown-item" href="<?php echo URL::to('admin/manage_products')?>">Manage Products</a>          
		          <a class="dropdown-item" href="<?php echo URL::to('admin/order_history')?>">Manage Orders</a>
		          <a class="dropdown-item" href="<?php echo URL::to('admin/manage_coupon')?>">Manage Coupon</a>
		          <a class="dropdown-item" href="<?php echo URL::to('admin/accessories_salesrep')?>">Sales REP</a>
		          <a class="dropdown-item" href="<?php echo URL::to('admin/accessories_setting')?>">Setting</a>
		        </div>
		      </div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-12">
			<div class="table-responsive">
				 <table class="table table-striped" id="data_table">
					<thead class="thead-dark">
						<tr>
							<th>S.NO</th>
							<th>Order ID</th>
							<th>Shop Name</th>
							<th>Order Date</th>
							<th>Order Type</th>
							<th style="width: 130px;">Order Status</th>
						</tr>
					</thead>
					<tbody>						
						<?php
						$i=1;
						$outputhistory='';
						if(count($historylist)){
							foreach ($historylist as $history) {
								if($history->order_types == 1){
									$type= 'Inhand Orders';
								}
								elseif($history->order_types == 2){
									$type= 'Online Orders';
								}
								$shop_details = DB::table('shop')->where('shop_id', $history->shop_ids)->first();
								$outputhistory.='
								<tr>
									<td>'.$i.'</td>
									<td><a href="'.URL::to('admin/order_details?order_id='.base64_encode($history->order_id)).'">'.$history->order_id.'</a></td>
									<td><a href="'.URL::to('admin/order_details?order_id='.base64_encode($history->order_id)).'">'.$shop_details->shop_name.'</a></td>
									<td><a href="'.URL::to('admin/order_details?order_id='.base64_encode($history->order_id)).'">'.date('d-M-Y', strtotime($history->date)).' - '.date('h-i A', strtotime($history->time)).'</a></td>
									<td>'.$type.'</td>
									<td></td>
								</tr>';
								$i++;
							}
							
						}
						else{
							$outputhistory.='
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td align="left">Empty</td>
									<td></td>
									<td></td>
								</tr>';
						}
						echo $outputhistory;
						?>

						
					</tbody>
				</table>
			</div>
		</div>
		
		
</div>
		
		
	</div>
</div>              
<!-- /.content -->

@stop
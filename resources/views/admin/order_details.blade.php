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
				<table class="own_table">
					<tr>
						<td><b>Order ID:</b><?php echo $orders->order_id?></td>
						<td><b>Sales REP:</b>
							<?php
							$salesrep = DB::table('sales_rep')->where('user_id', $orders->sales_reps)->first();
							echo $salesrep->firstname.' '.$salesrep->surname?>
						</td>
					</tr>
					<tr>
						<td>
							<b>Shop Name:</b>
							<?php
							$shop_details = DB::table('shop')->where('shop_id', $orders->shop_ids)->first();
							echo $shop_details->shop_name;
							?>
						</td>
						<td><b>Discount Category:</b> <?php if($orders->discount_name != ''){echo $orders->discount_name;}?></td>
					</tr>
					<tr>
						<td><b>Order Type:</b>
							<?php
								if($orders->order_types == 1){
									echo 'Inhand Orders';
								}
								else{
									echo 'Online Order';
								}
							?>
						</td>
						<td><b>Discount Category:</b> <?php if($orders->discount_percentage != '') {echo $orders->discount_percentage.'%';}?></td>
					</tr>					
					<tr>
						<td><b>Date:</b> <?php echo date('d-M-Y', strtotime($orders->date))?> - <?php echo date('h:i A', strtotime($orders->time))?></td>
						<td></td>
					</tr>

				</table>
			
			
				<table class="own_table">
					<thead>
          				<tr>
          					<th>Product Name</th>
          					<th style="max-width:100px;" >Quantity</th>
          					<th>Price</th>		          					
          					<th>Total</th>          					
          				</tr>
          			</thead>
					<tbody>
						<?php
						$orderlist = explode(',', $orders->order_process_id);
						$output='';
						$sub_total='';
						if(count($orderlist)){
				        	foreach ($orderlist as $order ) {	        		
				        			$order_details = DB::table('order_process')->where('process_id', $order)->first();
				        			
				        			$product_details = DB::table('products')->where('product_id', $order_details->products_id)->first();
				        			$output.='
				        			<tr class="row_class_'.base64_encode($order_details->process_id).'">
				        				<td>'.$product_details->product_name.'</td>
				        				<td>
				        				'.$order_details->qty.'
				        				<input type="hidden" min="1" value="'.$order_details->qty.'" readonly class="qty_value form-control" style="max-width:80px; float:left" />	        				
				        					
				        				</td>
				        				<td>&#163; '.number_format_invoice($order_details->price).'</td>
				        				<td>&#163; '.number_format_invoice($order_details->total).'</td>       				

				        			</tr>';
				        			$sub_total = $sub_total+$order_details->total;
				        	}
				        	$button_display='block';
				        	$admin_setting = DB::table('admin')->first();
				        	$vat = $admin_setting->vat_percentage;
				        	$vat_value = ($sub_total*$vat)/100;

				        	$total = $sub_total+$vat_value;

				        	$output.='
				        	<tr>
				        		<td></td>
				        		<td></td>
				        		<td align="right"><b>Sub Total:<b></td>
				        		<td>&#163; <span class="sub_total">'.number_format_invoice($orders->subtotal).'</span>
				        		<input type="hidden" id="sub_total_class" value="'.$sub_total.'" readonly>
				        		</td>
				        	</tr>
				        	<tr class="vat_row">
				        		<td></td>
				        		<td></td>
				        		<td align="right"><b>VAT '.$orders->vat_percentage.'%:<b></td>
				        		<td>&#163; '.number_format_invoice($orders->vat_value).'</td>
				        	</tr>
				        	<tr class="total_row">
				        		<td></td>
				        		<td></td>
				        		<td align="right"><b>Total :<b></td>
				        		<td>&#163; <span class="total">'.number_format_invoice($orders->total).'</spa>
				        		
				        		</td>
				        	</tr>
				        	<tr class="discount_row" >
				        		<td></td>
				        		<td></td>
				        		<td align="right"><b>Discount :<b></td>
				        		<td>&#163; <span class="discount_span">
				        		'.number_format_invoice($orders->discount_amount).'
				        		</span></td>
				        	</tr>
				        	<tr class="grand_row" >
				        		<td></td>
				        		<td></td>
				        		<td align="right"><b>Grand Total:<b></td>
				        		<td>&#163; <span class="grand_span">
				        		'.number_format_invoice($orders->grand_total).'
				        		</span></td>
				        	</tr>';
				        }
						echo $output;
						?>
						

						
					</tbody>
				</table>
				<?php
				if($orders->commission_bonus == 0 || $orders->commission_bonus == 4 ){
					$display_table = 'display:none';
				}
				else{
					$display_table = 'display:table-row';
				}
				?>

				<table class="own_table" >
					<tr style="<?php echo $display_table; ?>">
						<td>Grand Total</td>
						<td>&#163; <?php echo number_format_invoice($orders->grand_total)?></td>
					</tr>
					<tr style="<?php echo $display_table; ?>">
						<td>Commission</td>
						<td>&#163; <?php echo number_format_invoice($orders->commission)?></td>
					</tr>
					<tr style="<?php echo $display_table; ?>">
						<td>Bonus</td>
						<td>&#163; <?php echo number_format_invoice($orders->bonus)?></td>
					</tr>
					<tr style="<?php echo $display_table; ?>">
						<td>Final</td>
						<td>&#163; <?php echo number_format_invoice($orders->final)?></td>
					</tr>
				</table>
			</div>
		</div>
		
		
</div>
		
		
	</div>
</div>              
<!-- /.content -->

@stop
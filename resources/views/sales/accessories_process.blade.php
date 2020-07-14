@extends('salesheader')
@section('content')
<style type="text/css">
.heading_class{width: 100%; color: #f26e46; text-align: left; }
.heading_class:hover{text-decoration: none; color: #f26e46;}
.card_header_class{padding: 5px; border-radius: 0px; }
.card_class{border-radius:0px; margin-bottom: 2px; box-shadow: none; }
.card_body_class{padding: 0px;}
.own_table td{padding: 8px; font-size: 13px;}
.own_table th{font-size: 13px;}
</style>
<!-- Content Header (Page header) -->
	<div class="col-lg-12 col-md-12 col-sm-12 col-12 sales_right_panel">
	<div class="row">
		<div class="col-lg-9 col-sm-9 col-8">
			<div class="main_title">Accessories for
				<span> 
					<?php 
					$id= base64_decode($shop_id);
					$shop_details = DB::table('shop')->where('shop_id', $id)->first();
					echo $shop_details->shop_name;
					?>
				</span>
			</div>
		</div>
		<div class="col-lg-3 col-sm-3 col-4 text-right" style="padding-top: 20px;">
	      <div class="dropdown">
	        <button class="btn btn-secondary common_button dropdown-toggle" style="border-radius: 0px; border: 0px; padding: 5px 10px" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	          Menu
	        </button>
	        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
	          <a class="dropdown-item" href="<?php echo URL::to('sales/shop_view_details/'.$shop_id)?>">Shop Details</a>
	          <a class="dropdown-item" href="<?php echo URL::to('sales/accessories/'.$shop_id)?>">Accessories order</a>
	          <a class="dropdown-item" href="<?php echo URL::to('sales/order_process/'.$shop_id)?>">View Cart</a>	          
	          <a class="dropdown-item" href="<?php echo URL::to('sales/order_history/'.$shop_id)?>">Order History</a>
	        </div>
	      </div>
	    </div>
	</div>
	<div class="row margin_top_20">		

		
		
		<div class="col-lg-12 col-md-12 col-sm-12 col-12">
			<input type="hidden" id="shop_id" value="<?php echo $shop_id?>" name="">
			<div class="accordion" id="accordionExample">
			  <div class="card card_class">
			    <div class="card-header card_header_class" id="headingOne">
			      <h2 class="mb-0">
			        <button class="btn btn-link heading_class" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
			          Inhand Order
			        </button>
			      </h2>
			    </div>

			    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
			      <div class="card-body card_body_class">
			        <?php

			        $output_inhand='<div class="table-responsive">
		          		<table class="own_table inhand_table_class">
		          			<thead>
		          				<tr>
		          					<th>Product</th>
		          					<th style="min-width:90px; padding:0px;">Quantity</th>
		          					<th>Price</th>		          					
		          					<th>Total</th>
		          					<th style="text-align:center; padding:0px;"></th>
		          				</tr>
		          			</thead><tbody class="inhand_body_class">';
			        if(count($orderlisthand)){
			        	foreach ($orderlisthand as $orderhand ) {			        		
			        			$product_details = DB::table('products')->where('product_id', $orderhand->products_id)->first();
			        			$output_inhand.='
			        			<tr class="row_class_'.base64_encode($orderhand->process_id).'">
			        				<td>'.$product_details->product_name.'</td>
			        				<td style="padding:0px;">
			        				<input type="number" min="1" value="'.$orderhand->qty.'" class="qty_value form-control" style="max-width:45px; padding:7px; float:left" />
			        				<a href="javascript:" class="common_button add_button" data-element="'.base64_encode($product_details->product_id).'" style="float:left; padding:6px 10px; font-size:13px;">Add</a>
			        				<input type="hidden" value="'.$orderhand->order_type.'" class="order_type">
			        				<label class="error error_label" style="display:none; width:100%; clear:both"></label>
			        				</td>
			        				<td><span class="class_price">&#163; '.$orderhand->price.'</span></td>
			        				<td><span class="total_class">&#163; '.$orderhand->total.'</span></td>
			        				<td align="center" style="padding:0px;">
			        					<a href="javascript:"><i class="fas fa-trash-alt delete_icon" data-element="'.base64_encode($orderhand->process_id).'"></i></a>
			        				</td>

			        			</tr>
			        			<tr>
		          					<td colspan="5" style="font-size:11px; background:#fff; padding:5px;">
		          						'.$product_details->plan1_start.' - '.$product_details->plan1_end.' = &#163; '.$product_details->plan1_price.',
		          					'.$product_details->plan2_start.' - '.$product_details->plan2_end.' = &#163; '.$product_details->plan2_price.',
		          					'.$product_details->plan3_above.' => &#163; '.$product_details->plan3_price.'<br/>
		          					</td>
		          				</tr>
			        			';			        		
			        	}
			        	$output_inhand.='</tbody></table></div>';
			        	$button_inhand = 'block';
			        }
			        else{
			        	$output_inhand='<div class="col-lg-12 text-center" style="margin-top:20px; margin-bottom:20px;"><b>Inhand Cart is empty</b></div>';
			        	$button_inhand = 'none';
			        }
			        echo $output_inhand;
			        ?>
			        <div class="col-lg-12 text-center inhand_empty"></div>
			        <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center inhand_process">
						<a href="<?php echo URL::to('sales/process_payment?type=1&shop_id='.$shop_id)?>" class="common_button" style="font-size: 25px; margin-top: 50px; margin-bottom: 50px; display: <?php echo $button_inhand?>">Process Payment</a>
					</div>
			      </div>
			    </div>
			  </div>
			  <div class="card card_class">
			    <div class="card-header card_header_class" id="headingTwo">
			      <h2 class="mb-0">
			        <button class="btn btn-link collapsed heading_class" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
			          Online Order
			        </button>
			      </h2>
			    </div>
			    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
			      <div class="card-body card_body_class">
			        <?php

			        $output_online='<div class="table-responsive">
		          		<table class="own_table online_table_class">
		          			<thead>
		          				<tr>
		          					<th>Product</th>
		          					<th style="min-width:90px; padding:0px;">Quantity</th>
		          					<th>Price</th>		          					
		          					<th>Total</th>
		          					<th style="text-align:center; padding:0px;"></th>
		          				</tr>
		          			</thead><tbody class="online_body_class">';
			        if(count($orderlistonline)){
			        	foreach ($orderlistonline as $orderonline ) {
			        		
		        			$product_details = DB::table('products')->where('product_id', $orderonline->products_id)->first();
		        			$output_online.='
		        			<tr class="row_class_'.base64_encode($orderonline->process_id).'">
		        				<td>'.$product_details->product_name.'</td>
		        				<td style="padding:0px;">
		        				<input type="number" min="1" value="'.$orderonline->qty.'" class="qty_value form-control" style="max-width:45px; padding:7px; float:left" />
		        				<a href="javascript:" class="common_button add_button" data-element="'.base64_encode($product_details->product_id).'" style="float:left; padding:6px 10px; font-size:13px;">Add</a>
		        				<input type="hidden" value="'.$orderonline->order_type.'" class="order_type">
		        				<label class="error error_label" style="display:none; width:100%; clear:both"></label>
		        				</td>
		        				<td><span class="class_price">&#163; '.$orderonline->price.'</span></td>
		        				<td><span class="total_class">&#163; '.$orderonline->total.'</span></td>
		        				<td align="center">
		        					<a href="javascript:"><i class="fas fa-trash-alt delete_icon" data-element="'.base64_encode($orderonline->process_id).'"></i></a>
		        				</td>

		        			</tr>
		        			<tr>
	          					<td colspan="5" style="font-size:11px; background:#fff; padding:5px;">
	          						'.$product_details->plan1_start.' - '.$product_details->plan1_end.' = &#163; '.$product_details->plan1_price.',
	          					'.$product_details->plan2_start.' - '.$product_details->plan2_end.' = &#163; '.$product_details->plan2_price.',
	          					'.$product_details->plan3_above.' => &#163; '.$product_details->plan3_price.'<br/>
	          					</td>
	          				</tr>
		        			';
			        		
			        	}
			        	$output_online.='</tbody></table></div>';
			        	$button_online = 'block';
			        }
			        else{
			        	$output_online='<div class="col-lg-12 text-center" style="margin-top:20px; margin-bottom:20px;"><b>Online Cart is empty</b></div>';
			        	$button_online = 'none';
			        }
			        echo $output_online;
			        ?>
			        <div class="col-lg-12 text-center online_empty"></div>

			        <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center online_process" style="">
						<a href="<?php echo URL::to('sales/process_payment?type=2&shop_id='.$shop_id)?>" class="common_button" style="font-size: 25px; margin-top: 50px; margin-bottom: 50px; display: <?php echo $button_online?>">Process Payment</a>
					</div>
			      </div>
			    </div>
			  </div>
			  
			</div>
		


		


	</div>

</div>
		
		
	</div>
</div>              
<!-- /.content -->
<script>
$(window).click(function(e) {

if($(e.target).hasClass("add_button")){
	var id = $(e.target).attr("data-element");	
	var qty_value = $(e.target).parent().find(".qty_value").val();
	var order_type = $(e.target).parent().find(".order_type").val();
	var shop_id = $("#shop_id").val();

	console.log(order_type);

	if(qty_value == ''){		
		$(e.target).parent().find(".error_label").html("Enter Qty");
		$(e.target).parent().find(".error_label").show();
	}
	else{	

		$(e.target).parent().find(".error_label").hide();
		$(e.target).parent().find(".type_label").hide();
		$(".loading_css").addClass("loading");
		$.ajax({
	      url:"<?php echo URL::to('sales/accessories_qty_check')?>",
	      dataType:'json',
	      data:{qty_value:qty_value, id:id, type:order_type, shop_id:shop_id },
	      type:"post",
	      success: function(result)
	      {
	      	if(result['answer'] == 'false'){	      		
	      		$(e.target).parent().find(".error_label").show();
	      		$(e.target).parent().find(".error_label").html(result['message']);	      			      		
	      	}
	      	else{	      		
	      		$(e.target).parent().find(".error_label").hide();
	      		$(e.target).parent().parent().find(".class_price").html("&#163; "+result['price']);
	      		$(e.target).parent().parent().find(".total_class").html("&#163; "+result['total']);
	      		
	      	}
	        $(".loading_css").removeClass("loading");
	      }
	    })
	}
}

if($(e.target).hasClass("delete_icon")){
	var del = confirm("Are you sure delete?");

	if(del == true){
		var id = $(e.target).attr("data-element");
		var order_type = $(e.target).parent().parent().parent().find(".order_type").val();
		var shop_id = $("#shop_id").val();
		$(".loading_css").addClass("loading");
		$.ajax({
	      url:"<?php echo URL::to('sales/accessories_delete')?>",
	      dataType:'json',
	      data:{id:id, type:order_type, shop_id:shop_id},
	      type:"post",
	      success: function(result)
	      {
	      	if(result['answer'] == 'true'){
	      		$(e.target).parent().parent().parent().remove();
	      	}

	      	if(result['count'] == '0'){
	      		if(order_type == '1'){
	      			$(".inhand_process").hide();
	      			$(".inhand_table_class").remove();
	      			$(".inhand_empty").css({"margin-top":"20px", "margin-bottom":"20px"});
	      			$(".inhand_empty").html("<b>Inhand Cart is empty</b>")
	      		}
	      		else{
	      			$(".online_process").hide();
	      			$(".online_table_class").find("thead").remove();
	      			$(".online_empty").css({"margin-top":"20px", "margin-bottom":"20px"});
	      			$(".online_empty").html("<b>Online Cart is empty</b>")
	      		}
	      	}
	      	else{
	      		if(order_type == '1'){
	      			$(".inhand_process").show();
	      		}
	      		else{
	      			$(".online_process").show();
	      		}
	      	}
	      	
	        $(".loading_css").removeClass("loading");
	      }
	    })
	}
	else{
		console.log('No');
	}

}

});

</script>

@stop
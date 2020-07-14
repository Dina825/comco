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
			<h6>Order type</h6>
			<input type="hidden" id="order_type" name="">
			<input type="hidden" id="shop_id" value="<?php echo $shop_id?>" name="">
			
		</div>

		<div class="col-lg-3 col-md-6 col-sm-6 col-6">
			<label class="form_radio">In Hand
			   <input type="radio" value=""  class="area_radio inhand_class" style="width:1px; height:1px" name="order_type"  required>
			   <span class="checkmark_radio"></span>
			</label>
		</div>

		<div class="col-lg-3 col-md-6 col-sm-6 col-6">
			<label class="form_radio">Online
			   <input type="radio" value="" class="area_radio online_class" style="width:1px; height:1px" name="order_type"  required>
			   <span class="checkmark_radio"></span>
			</label>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-12">
			<label class="error type_label" style="display:none; margin-bottom: 15px; "></label>
		</div>
		
		<div class="col-lg-12 col-md-12 col-sm-12 col-12">
			
			<div id="accordion">


			  

			  	  <?php
			  	  $output_category='';
		          if(count($categorylist)){
		          	foreach ($categorylist as $category) {

		          		$product_list = DB::table('products')->where('category_id', $category->category_id)->where('status', 0)->get();

		          		$output_product='
		          		<div class="table-responsive">
		          		<table class="own_table">
		          			<thead>
		          				<tr>
		          					<th>Product</th>
		          					<th style="min-width:105px;" >Quantity</th>
		          					<th>Price</th>		          					
		          					<th>Total</th>
		          				</tr>
		          			</thead><tbody>';

		          		if(count($product_list)){
		          			foreach ($product_list as $product) {
		          				
		          				$output_product.='
		          				<tr>
		          					<td>'.$product->product_name.'		          					
		          					</td>		          					
		          					<td><input type="number" min="0" class="form-control qty_value" placeholder="Qty" style="max-width:45px; padding:7px; float:left" />
		          						<a href="javascript:" class="common_button add_button" data-element="'.base64_encode($product->product_id).'" style="float:left; padding:6px 10px; font-size:13px;">Add</a>
		          						<label class="error error_label" style="display:none; width:100%; clear:both"></label>

		          						
		          					</td>
		          					<td>&#163; <span class="class_price">'.$product->plan1_price.'</span></td>
		          					<td>
		          						<span class="total_class"></span>
		          					</td>
		          				</tr>
		          				<tr>
		          					<td colspan="4" style="font-size:11px; background:#fff; padding:5px;">
		          						'.$product->plan1_start.' - '.$product->plan1_end.' = &#163; '.$product->plan1_price.',
		          					'.$product->plan2_start.' - '.$product->plan2_end.' = &#163; '.$product->plan2_price.',
		          					'.$product->plan3_above.' => &#163; '.$product->plan3_price.'<br/>
		          					</td>
		          				</tr>
		          				';
		          			}
		          			$output_product.='</tbody></table></div>';
		          		}
		          		else{
		          			$output_product ='No Products';
		          		}



		          		$output_category.='

		          		<div class="card card_class">
					    <div class="card-header card_header_class" id="heading'.$category->category_id.'">
					      <h5 class="mb-0">
					        <button class="btn btn-link collapsed heading_class" data-toggle="collapse" data-target="#collapse'.$category->category_id.'" aria-expanded="false" aria-controls="collapse'.$category->category_id.'">
					          '.$category->category_name.'
					        </button>
					      </h5>
					    </div>
					    <div id="collapse'.$category->category_id.'" class="collapse" aria-labelledby="heading'.$category->category_id.'" data-parent="#accordion">
					      <div class="card-body card_body_class">
					        '.$output_product.'
					      </div>
					    </div>
					  </div>
		          		';
		          	}
		          }
		          else{
		          	$output_category='No Category';
		          }

		          echo $output_category;
		          ?>		  
			  
			
		</div>

		<div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center" style="margin-top: 30px; padding: 0px; ">
			<a href="<?php echo URL::to('sales/order_process/'.$shop_id)?>" class="common_button order_process" style="font-size: 25px; width: 100%; float:left">Order Process</a>
		</div>


		


	</div>

</div>
		
		
	</div>
</div>              
<!-- /.content -->
<script type="text/javascript">
$( document ).ready(function() {
    $(".card .collapse:first").addClass("show");
});
</script>
<script>
$(window).click(function(e) {

if($(e.target).hasClass("add_button")){
	var id = $(e.target).attr("data-element");	
	var qty_value = $(e.target).parent().find(".qty_value").val();
	var order_type = $("#order_type").val();
	var shop_id = $("#shop_id").val();

	if(order_type == ''){
		$(".type_label").html("Please select order type");
		$(".type_label").show();
	}
	else if(qty_value == ''){		
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
	      		$(e.target).parent().parent().find(".class_price").html(result['price']);
	      		$(e.target).parent().parent().find(".total_class").html("&#163; "+result['total']);	      		
	      	}
	      	
	        $(".loading_css").removeClass("loading");
	      }
	    })
	}
}

});

$(".inhand_class").click(function(){
	$("#order_type").val(1);
	$(".type_label").hide();
})
$(".online_class").click(function(){
	$("#order_type").val(2);
	$(".type_label").hide();
})
</script>
@stop
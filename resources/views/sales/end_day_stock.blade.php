@extends('salesheader')
@section('content')
<!-- Content Header (Page header) -->
	<div class="col-lg-12 col-md-12 col-sm-12 col-12 sales_right_panel">
	<div class="row">
		<div class="col-lg-12 col-sm-12 col-12">
			<div class="main_title">End <span>Day Stock</span></div>
		</div>
	</div>
	<div class="row margin_top_20">
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

		<div class="col-lg-3 col-md-2 col-sm-12 col-12"></div>
		<div class="col-lg-6 col-md-8 col-sm-12 col-12">
			<form method="post" action="<?php echo URL::to('sales/add_end_day_stock')?>">
			

				<?php
				$output='<div class="row" style="font-size: 15px; font-weight: 500; background: #ccc ;line-height: 35px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-4 text-center">NETWORK</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-4 text-center">PRODUCT ID</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-4 text-center">QUANTITY</div>
			</div>
			<div class="row">';
				if(count($networklist)){
					foreach ($networklist as $network) {

						$product_list = explode(',', $network->product_id);

						$output_product='<div class="col-lg-8 col-md-8 col-sm-8 col-8 padding_00">
					<div class="sim_allocation_product_ul">
						<ul>';
						if(count($product_list)){
							foreach ($product_list as $product) {

								if($product != ''){
									$product_view = '<li>
		    					<div class="product_text">
		    						'.$product.'
		    						<input type="hidden" readonly name="product_'.$network->network_name.'[]" value="'.$product.'" />
		    					</div>
		    					<div class="field">
		    						<input type="number" class="form_input" name="quantity_'.$network->network_name.'[]" placeholder="Enter Quantity" >
		    					</div>		                					
		    				</li>';
								}
								else{
									$product_view='<li>
		    					<div class="product_text">
		    						Empty
		    					</div>
		    					<div class="field">
		    						Empty
		    					</div>		                					
		    				</li>';
								}

							


								$output_product.=$product_view;
							}
							$output_product.='</ul>
								</div>	                		
					    	</div>';
						}
						else{
							$output_product='Empty';
						}

						$output.='<div class="col-lg-4 col-md-4 col-sm-4 col-4 sim_allocation_left">'.$network->network_name.'
						<input type="hidden" value="'.$network->network_name.'" readonly name="network[]" />
						</div>'.$output_product;
					}
				}
				else{
					$output='<div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center"><h6>Network is Empty</h6></div>';
				}
				echo $output;
				?>
			<div class="width_100 margin_top_20">
				<input type="hidden" value="<?php echo base64_encode($user_id)?>" name="sales_id">
				<button type="submit" class="btn btn-primary model_add_button" style="float: right;">End</button>
			</div>
		</div>
		</form>			
			
		</div>

		

		<div class="col-lg-3 col-md-2 col-sm-12 col-12"></div>



		
	</div>

</div>
		
		
	</div>
</div>              
<!-- /.content -->
@stop
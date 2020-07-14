<?php
		$output_test='
							<div class="row">';
		$start_details = DB::table('start_day')->get();
		if(count($start_details)){
			foreach ($start_details as $start) {
				$network_explode = explode(',', $start->network);
				if(count($network_explode)){
					foreach ($network_explode as $network) {
						$unserialize_product = unserialize($start->product);
						$product_list = $unserialize_product[$network];

						$unserialize_quantity = unserialize($start->quantity);
						$quantity_list = $unserialize_quantity[$network];
						

						$output_product='<div class="col-lg-8 col-md-8 col-sm-8 col-8 padding_00">
					<div class="sim_allocation_product_ul">
						<ul>';
						if(count($product_list)){
							foreach ($product_list as $key => $product) {
								$output_product.='
								<li>
		    					<div class="product_text">
		    						'.$product.'		    						
		    					</div>
		    					<div class="field">
		    						'.$quantity_list[$key].'
		    					</div>		                					
		    				</li>
								';
							}
							$output_product.='</ul>
								</div>	                		
					    	</div>';
						}
						$output_test.='<div class="col-lg-4 col-md-4 col-sm-4 col-4 sim_allocation_left">'.$network.'</div>'.$output_product;
					}
				}
			}
		}

		echo $output_test;
		?>





		<?php
		$output_test='
							<div class="row">';
		$start_details = DB::table('end_day')->get();
		if(count($start_details)){
			foreach ($start_details as $start) {
				$network_explode = explode(',', $start->network);
				if(count($network_explode)){
					foreach ($network_explode as $network) {
						$unserialize_product = unserialize($start->product);
						$product_list = $unserialize_product[$network];

						$unserialize_quantity = unserialize($start->quantity);
						$quantity_list = $unserialize_quantity[$network];
						

						$output_product='<div class="col-lg-8 col-md-8 col-sm-8 col-8 padding_00">
					<div class="sim_allocation_product_ul">
						<ul>';
						if(count($product_list)){
							foreach ($product_list as $key => $product) {

								if($quantity_list[$key] != ''){
									$quantity = $quantity_list[$key];
								}
								else{
									$quantity = '0';
								}


								$output_product.='
								<li>
		    					<div class="product_text">
		    						'.$product.'		    						
		    					</div>
		    					<div class="field">
		    						'.$quantity.'
		    					</div>		                					
		    				</li>
								';
							}
							$output_product.='</ul>
								</div>	                		
					    	</div>';
						}
						$output_test.='<div class="col-lg-4 col-md-4 col-sm-4 col-4 sim_allocation_left">'.$network.'</div>'.$output_product;
					}
				}
			}
		}

		echo $output_test;
		?>
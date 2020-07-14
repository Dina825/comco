@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->

<style>
.sim_allocation_product_ul .field{padding: 5px 15px;}
</style>

<div class="col-lg-11 col-md-12 col-sm-12 col-12 offset-lg-1 right_panel">
	<div class="row">
		<div class="col-lg-6 col-sm-6 col-6">
			<div class="main_title">Manage <span>Stock</span></div>
		</div>
		<div class="col-lg-6 col-sm-6 col-6">
			<a href="<?php echo URL::to('admin/logout')?>" class="logout_button"><i class="fas fa-sign-in-alt"></i> &nbsp;Logout</a>
		</div>
	</div>
	<div class="row">
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
		<?php
        $output_stock='
       
        <div class="row" style="font-size: 15px; font-weight: 500; background: #ccc ;line-height: 35px;">
          <div class="col-lg-4 col-md-3 col-sm-3 col-3 text-center">PRODUCT</div>
          <div class="col-lg-4 col-md-5 col-sm-5 col-5 text-center" style="line-height: 30px;">PRODUCT ID / QUANTITY</div>
          <div class="col-lg-4 col-md-4 col-sm-4 col-4 text-center">Message</div>
        </div><div class="row">';        
        if(count($stock)){
          $network_explode = explode(',', $stock->network);
          if(count($network_explode)){
            foreach ($network_explode as $network) {
              $unserialize_product = unserialize($stock->product);
              $product_list = $unserialize_product[$network];

              $unserialize_quantity = unserialize($stock->quantity);
              $quantity_list = $unserialize_quantity[$network];
              
              $unserialize_correct = unserialize($stock->correct);
              $correct_list = $unserialize_correct[$network];

              $unserialize_message = unserialize($stock->message);
              $message_list = $unserialize_message[$network];
              

              $output_product='<div class="col-lg-8 col-md-8 col-sm-8 col-8 padding_00">
            <div class="sim_allocation_product_ul">
              <ul>';
              if(count($product_list)){
                foreach ($product_list as $key => $product) {

                	if($message_list[$key] != ''){
                		$message = $message_list[$key];
                	}
                	else{
                		$message = '&nbsp;';
                	}

                	if($correct_list[$key] == 2){
                		$correct = 'Correct';
                	}
                	elseif($correct_list[$key] == 1){
                		$correct = 'Incorrect';
                	}
                  else{
                    $correct='';
                  }
                	

                  $output_product.='
                  <li>
                    <div class="product_text">
                      '.$product.' - '.$quantity_list[$key].'    

                      <div style="float: right; line-height: 18px;">
                        '.$correct.'
                      </div>                
                    </div>
                    <div class="field">
                      '.$message.'
                    </div>                              
                  </li>
                  ';
                }
                $output_product.='</ul>
                  </div>                      
                  </div>';
              }
              $output_stock.='<div class="col-lg-4 col-md-4 col-sm-4 col-4 sim_allocation_left">'.$network.'
              
              </div>'.$output_product;
            }
            $output_stock.='
            
            </div>
            
            ';
        }
        }
        else{
          $output_stock='<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-12 padding_00 text-center"><b>Empty</b></div></div>';
        }

        echo $output_stock;


        ?>
        </div>
      <div class="col-lg-3 col-md-2 col-sm-12 col-12"></div>
	</div>
		</div>
	</div>
</div>                
<!-- /.content -->
<script type="text/javascript">
$(window).click(function(e) {

})


</script>

@stop
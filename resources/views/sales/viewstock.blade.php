@extends('salesheader')
@section('content')
<!-- Content Header (Page header) -->

<style type="text/css">
.readonlycss, .readonlycss input{background: #e8e8e8}
</style>


<div class="col-lg-12 col-md-12 col-sm-12 col-12 sales_right_panel">
	<div class="row">
		<div class="col-lg-12 col-sm-12 col-12">
			<div class="main_title">Stock of <span>Month</span></div>
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
        <form method="post" action="'.URL::to('sales/stock_update').'">
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
              

              $output_product='<div class="col-lg-8 col-md-8 col-sm-8 col-8 padding_00">
            <div class="sim_allocation_product_ul">
              <ul>';
              if(count($product_list)){
                foreach ($product_list as $key => $product) {

                  if($quantity_list[$key] != ''){
                    $correct = '<div style="float: right; line-height: 18px;">
                        <label class="form_checkbox" style="margin-bottom: 0px; margin-top: 5px;">Correct
                         <input type="checkbox" class="correct_class" data-element="'.$network.'_'.$product.'" >                         
                         <span class="checkmark_checkbox"></span>
                      </label>
                      <input type="hidden" class="correct_'.$network.'_'.$product.'" name="correct_'.$network.'[]" value="1" />
                      </div>';
                    $message = '';
                    $readonly_css = '';
                  }
                  else{
                    $correct = '<div style="float: right; line-height: 18px;">
                        
                      <input type="hidden" class="correct_'.$network.'_'.$product.'" name="correct_'.$network.'[]" value="0" />
                      </div>';
                    $message = 'readonly';
                    $readonly_css = 'readonlycss';
                  }


                  $output_product.='
                  <li>
                    <div class="product_text">
                      '.$product.' - '.$quantity_list[$key].'    
                      '.$correct.'
                                     
                    </div>
                    <div class="field '.$readonly_css.'">
                      <input type="text" class="form_input" name="message_'.$network.'[]" placeholder="Enter Message" '.$message.'>
                    </div>                              
                  </li>
                  ';
                }
                $output_product.='</ul>
                  </div>                      
                  </div>';
              }
              $output_stock.='<div class="col-lg-4 col-md-4 col-sm-4 col-4 sim_allocation_left">'.$network.'
              <input type="hidden" value="'.$network.'" readonly name="network[]" />              
              </div>'.$output_product;
            }
            $output_stock.='
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-right">
              <div class="width_100 margin_top_20">
                <input type="hidden" value="'.base64_encode($stock_id).'" readonly name="stock_id" />
                <button type="submit" class="btn btn-primary model_add_button" style="float: right;">Submit</button>
              </div>
            </div>
            </div>
            </form>
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

if($(e.target).hasClass("correct_class")){
  var id = $(e.target).attr("data-element");
  
  if($(e.target).is(":checked")){
    $(".correct_"+id).val('2');
  }
  else{
    $(".correct_"+id).val('1');
  }
}

})

</script>
@stop
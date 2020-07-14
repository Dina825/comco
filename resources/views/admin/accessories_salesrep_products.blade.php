@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->
<div class="modal fade history_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="min-width: 80%">
    <div class="modal-content">
      <form method="post" action="<?php echo URL::to('admin/area_add')?>"  enctype="multipart/form-data" id="add-form">
       <div class="modal-header">
        <h5 class="modal-title history_title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">                    
                  <div class="table-responsive">
                    <table class="table table-striped" id="history_table">
                      <thead  class="thead-dark">
                        <tr>
                          <th>#</th>
                          <th>Product Name</th>
                          <th>Category</th>
                          <th>Description</th>
                          <th>Order ID</th>
                          <th>Shop Name / Account Number</th>
                          <th>Qty</th>
                          <th>Balance</th>
                        </tr>
                      </thead>
                      <tbody class="history_content">
                      </tbody>
                    </table>
                  </div>
                </div>                                      
            </div>                
      </div>
      <div class="modal-footer">       
        <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
        
      </div>
      </form>
    </div>
  
  </div>
</div>

<div class="col-lg-11 col-md-12 col-sm-12 col-12 offset-lg-1 right_panel">
	<div class="row">
		<div class="col-lg-6 col-sm-6 col-6">
			<div class="main_title">Accessories for Sales REP<span>
        <?php
        $user_details = DB::table('sales_rep')->where('user_id', $user_id)->first();
        echo $user_details->firstname.' '.$user_details->surname;
        echo '<input type="hidden" class="user_id" value="'.base64_encode($user_id).'"/>';
        ?>
        
      </span></div>
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
		<div class="col-lg-12 margin_top_20">
			<?php
      $ouput_category='';
      if(count($categorylist)){
        foreach ($categorylist as $category) {
          $productlist = DB::table("products")->where('category_id', $category->category_id)->where('status', 0)->get();
          $ouput_product='<div class="table-responsive"><table class="own_table">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Available Qty</th>
              <th>Enter Qty</th>
              <th>Action</th>
            </tr>
          </thead>';

          if(count($productlist)){
            foreach ($productlist as $product) {
              $sales_rep_product = DB::table('sales_rep_products')->where('user_id', $user_id)->where('product_id', $product->product_id)->first();



              if(count($sales_rep_product)){
                $current_qty = $sales_rep_product->qty;
              }
              else{
                $current_qty = 'No Qty';
              }


              $ouput_product.='<tr>
                <td>'.$product->product_name.'</td>
                <td class="current_qty" style="width:130px;"> '.$current_qty.'</td>
                <td style="width:130px;"><input type="number" min="0" class="form-control qty_value" />
                <label class="error error_label qty_label" style="display:none;"></label></td>
                
                <td style="width:130px;"><a href="javascript:" class="common_button add_button" data-element="'.base64_encode($product->product_id).'" >Add</a>
                &nbsp;&nbsp;                         

                            <a href="javascript:" data-placement="top" data-original-title="Products History" data-toggle="tooltip"><i class="fas fa-history products_history" data-element="'.base64_encode($product->product_id).'"></i></a>
                </td>
              </tr>';
            }
            $ouput_product.='</table></div>';
          }
          else{
            $ouput_product='No Products';
          }


          $ouput_category.='
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12"><h5>'.$category->category_name.'</h5></div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 product_row">
            '.$ouput_product.'
            <input type="hidden" class="category_class" value="'.base64_encode($category->category_id).'" />
            </div>
          </div>
          ';
        }
      }
      else{
        $ouput_category = 'Empty';
      }
      echo $ouput_category;      
      ?>
		</div>
	</div>
		</div>
	</div>
</div>                
<!-- /.content -->
<script>
$(window).click(function(e) {

if($(e.target).hasClass("add_button")){
  var productid = $(e.target).attr("data-element");  
  var qty_value = $(e.target).parent().parent().find(".qty_value").val();
  var categoryid  = $(e.target).parent().parent().parent().parent().parent().parent().find(".category_class").val();
  var user_id = $(".user_id").val();  

  if(qty_value == ''){
    $(e.target).parent().parent().find(".error_label").html("Enter Qty");
    $(e.target).parent().parent().find(".error_label").show();
  }
  else if(qty_value == 0){
    $(e.target).parent().parent().find(".error_label").html("Do not enter 0");
    $(e.target).parent().parent().find(".error_label").show();
  }
  else{
    $(e.target).parent().parent().find(".error_label").hide();
    $(".loading_css").addClass("loading");
    $.ajax({
        url:"<?php echo URL::to('admin/accessories_add_salesrep')?>",
        dataType:'json',
        data:{qty_value:qty_value, productid:productid, categoryid:categoryid, user_id:user_id },
        type:"post",
        success: function(result)
        {
          $(e.target).parent().parent().find(".current_qty").html(result['qty']);
          $(e.target).parent().parent().find(".qty_value").val('');
          $(".loading_css").removeClass("loading");
        }
      })
  }
}


if($(e.target).hasClass("products_history")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {
    $("#history_table").dataTable().fnDestroy();
    var product_id = $(e.target).attr("data-element");
    var user_id = $(".user_id").val();
    
    $.ajax({
        url:"<?php echo URL::to('admin/sales_rep_products_history')?>",
        dataType:'json',
        data:{product_id:product_id, type:2, user_id:user_id},
        type:"post",
        success: function(result)
        {
          $(".history_title").html(result['title']);                    
          $(".history_content").html(result['content']);          
          $(".history_model").modal('show');
          $('#history_table').DataTable({        
              autoWidth: true,
              scrollX: false,
              fixedColumns: false,
              searching: true,
              paging: true,
              info: false
          });
          $(".loading_css").removeClass("loading");
        }
      })
  },500);
}

});
</script>

@stop
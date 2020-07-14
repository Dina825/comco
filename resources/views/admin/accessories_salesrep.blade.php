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
			<div class="main_title">Accessories for <span>Sales REP</span></div>
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
		<div class="col-lg-12">
			<div class="table-responsive">
            <table class="table table-striped" id="data_table">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Name</th>
                  <th scope="col">Email Id / Username</th>                  
                  <th scope="col" width="150px" style="text-align: center;">Action</th>
                </tr>
              </thead>
              <tbody id="salesrep_tbody">
                  <?php
                  $output_salesrep='';
                  $i=1;
                  if(count($userlist)){
                    foreach ($userlist as $user) {
                        $user_details = DB::table('sales_rep')->where('user_id',$user->user_id)->first();

                        $output_salesrep.='
                        <tr>
                          <td>'.$i.'</td>
                          <td>'.$user_details->firstname.'</td>
                          <td>'.$user->email_id.'</td>
                          <td align="center">
                          <a href="'.URL::to('admin/accessories_salesrep_products/'.base64_encode($user->user_id)).'" data-placement="top" data-original-title="View Sales REP Products" data-toggle="tooltip"><i class="fas fa-eye"></i></a>
                          &nbsp;&nbsp;<a href="'.URL::to('admin/sales_rep_payments/'.base64_encode($user->user_id)).'" data-placement="top" data-original-title="Sales REP Payments" data-toggle="tooltip"><i class="fas fa-wallet"></i></a>&nbsp;&nbsp;                         

                            <a href="javascript:" data-placement="top" data-original-title="Products History" data-toggle="tooltip"><i class="fas fa-history products_history" data-element="'.base64_encode($user->user_id).'"></i></a>
                          </td>
                        </tr>
                        ';
                        $i++;
                    }
                  }
                  else{
                    $output_salesrep='
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td align="center">Empty</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>                                  
                      <td></td>
                    </tr>
                    ';
                  }
                  echo $output_salesrep;
                  ?>                
              </tbody>
            </table>
        </div>
		</div>
	</div>
		</div>
	</div>
</div>                
<!-- /.content -->
<script type="text/javascript">
$(window).click(function(e) {

if($(e.target).hasClass("products_history")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {
    $("#history_table").dataTable().fnDestroy();
    var user_id = $(e.target).attr("data-element");
    
    $.ajax({
        url:"<?php echo URL::to('admin/sales_rep_products_history')?>",
        dataType:'json',
        data:{user_id:user_id, type:1},
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



})

$('#history_table').DataTable({        
    autoWidth: true,
    scrollX: false,
    fixedColumns: false,
    searching: true,
    paging: true,
    info: false
});
</script>

@stop
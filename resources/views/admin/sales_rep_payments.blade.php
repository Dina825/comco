@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->
<div class="modal fade payment_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm ">
    <div class="modal-content">
      <form method="post" action="<?php echo URL::to('admin/salesrep_payment_add')?>"  enctype="multipart/form-data" id="payment-form">
       <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Payment Received</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                  <div class="form-group">
                      <label>Enter Amount</label>
                      <input type="number" class="form-control add_input" placeholder="Enter Amount" name="amount">
                      <input type="hidden" readonly value="<?php echo $user_id?>" name="user_id">
                  </div>
                  <div class="form-group">
                      <label>Transaction Details</label>
                      <input type="text" class="form-control add_input" placeholder="Transaction Details" name="transaction">                     
                  </div>
                </div>                                      
            </div>                
      </div>
      <div class="modal-footer">       
        <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn model_add_button add_button">Release</button>
      </div>
      </form>
    </div>
  
  </div>
</div>

<div class="modal fade payment_edit_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm ">
    <div class="modal-content">
      <form method="post" action="<?php echo URL::to('admin/salesrep_payment_update')?>"  enctype="multipart/form-data" id="payment-form">
       <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Payment Received</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                  <div class="form-group">
                      <label>Enter Amount</label>
                      <input type="number" class="form-control amount_class" placeholder="Enter Amount" name="amount">
                  </div>
                  <div class="form-group">
                      <label>Transaction Details</label>
                      <input type="text" class="form-control transaction_class" placeholder="Transaction Details" name="transaction">                     
                  </div>
                </div>                                      
            </div>                
      </div>
      <div class="modal-footer">
        <input type="hidden" readonly class="class_id" value="" name="id">
        <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn model_add_button add_button">Update</button>
      </div>
      </form>
    </div>
  
  </div>
</div>

<div class="col-lg-11 col-md-12 col-sm-12 col-12 offset-lg-1 right_panel">
	<div class="row">
		<div class="col-lg-6 col-sm-6 col-6">
			<div class="main_title">Payment Details for <span><?php echo $salesrep->firstname.' '.$salesrep->surname?></span></div>
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

		<div class="col-lg-6 col-md-6 col-sm-6 col-6 text-left">
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
    <div class="col-lg-6 col-md-6 col-sm-6 col-6 text-right">
      <a href="javascript:" data-toggle="modal" data-target=".payment_model" class="btn btn-primary model_add_button add_button_class">Payment Received</a>
    </div>
		<div class="col-lg-12">
      <h6 class="margin_top_20">Payment Received</h6>
			<div class="table-responsive">
            <table class="table table-striped" id="data_table">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Date</th>
                  <th scope="col">Description</th>
                  <th scope="col">Transaction</th>                  
                  <th scope="col">Payment</th>
                  <th scope="col" width="150px" style="text-align: center;">Action</th>
                </tr>
              </thead>
              <tbody>
                    <?php
                    $i=1;
                    $output_received='';
                    $total_received='';
                    if(count($received_payment)){
                      foreach ($received_payment as $received) {
                        $output_received.='
                        <tr>
                          <td>'.$i.'</td>
                          <td>'.date('d-M-Y', strtotime($received->date)).'</td>
                          <td>'.$received->description.'</td>
                          <td>'.$received->transaction_details.'</td>
                          <td>&#163; '.number_format_invoice($received->payment).'</td>
                          <td style="width:100px; text-align:center"><a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Payment" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($received->payment_id).'"></i></a></td>
                        </tr>';
                        $i++;

                        $total_received = $total_received+$received->payment;
                      }
                      $output_received.='
                      <tr>
                        <td><b>Total</b></td>
                        <td></td>
                        <td></td>
                        <td></td>                       
                        <td><b>&#163; '.number_format_invoice($total_received).'</b></td>
                         <td></td>
                      </tr>
                      ';

                    }
                    else{
                      $output_received='
                        <tr>
                          <td></td>
                          <td></td>
                          <td align="center">Empty</td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>';
                    }

                    echo $output_received;
                    ?>
              </tbody>
            </table>
        </div>
        <h6 class="margin_top_20">Inhand Order Payments</h6>

        <div class="table-responsive">
            <table class="table table-striped" id="data_table2">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Date</th>
                  <th scope="col">Description</th>
                  <th scope="col">Order ID</th>                  
                  <th scope="col">Payment</th>
                  
                </tr>
              </thead>
              <tbody>
                    <?php
                    $i=1;
                    $output_inhand='';
                    $total_inhand='';
                    if(count($inhand_payment)){
                      foreach ($inhand_payment as $inhand) {
                        $output_inhand.='
                        <tr>
                          <td>'.$i.'</td>
                          <td>'.date('d-M-Y', strtotime($inhand->date)).'</td>
                          <td>'.$inhand->description.'</td>
                          <td>'.$inhand->order_id.'</td>
                          <td>&#163; '.number_format_invoice($inhand->payment).'</td>
                          
                        </tr>';
                        $i++;

                        $total_inhand = $total_inhand+$inhand->payment;
                      }
                      $output_inhand.='
                      <tr>
                        <td><b>Total</b></td>
                        <td></td>
                        <td></td>
                        <td></td>                       
                        <td><b>&#163; '.number_format_invoice($total_inhand).'</b></td>
                         
                      </tr>
                      ';

                    }
                    else{
                      $output_inhand='
                        <tr>
                          <td></td>
                          <td></td>
                          <td align="center">Empty</td>
                          <td></td>
                          <td></td>
                          
                        </tr>';
                    }

                    echo $output_inhand;
                    ?>
              </tbody>
            </table>
        </div>
         <h6 class="margin_top_20">Payments Details</h6>
         <div class="table-responsive">
           <table class="own_table">
             <tr>
               <td>Inhand Amounts</td>               
               <td><?php echo '&#163; '.number_format_invoice($total_inhand)?></td>
             </tr>
             <tr>
               <td>Received Payments</td>               
               <td><?php echo '&#163; '.number_format_invoice($total_received);?></td>
             </tr>
             <tr>
               <td><b>Pending Payments</b></td>
               <td>
                 <?php
                 $pending_payment = $total_inhand-$total_received;
                 echo '<b>&#163; '.number_format_invoice($pending_payment).'</b>';
                 ?>
               </td>
             </tr>
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

if($(e.target).hasClass("edit_icon")){  
  //$(".loading_css").addClass("loading");
  setTimeout(function() {
    var value = $(e.target).attr("data-element");  
    $.ajax({
        url:"<?php echo URL::to('admin/sales_rep_payment_details')?>",
        dataType:'json',
        data:{id:value},
        type:"post",
        success: function(result)
        { 
          $(".class_id").val(result['id']);
          $(".transaction_class").val(result['transaction']);
          $(".amount_class").val(result['amount']);
          $(".payment_edit_model").modal("show");
          $(".loading_css").removeClass("loading");
        }
      })
    },500);
}



})

$(".add_button_class").click(function(){  
  $(".add_input").val('');
})

$.ajaxSetup({async:false});
$('#payment-form').validate({
    rules: {                
        amount : {required: true},
        transaction : {required: true},
    },
    messages: {                
        amount : {required : "Enter Amount"},        
        transaction : {required : "Enter Transaction Details"},        
    },
});

</script>

@stop
@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->

<div class="modal fade" id="error_from_xls" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Imported List.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    if(Session::has('success_error')){
                      $entries = Session::get('success_error');
                      $duplicated = $entries['duplicated'];
                      $network_dint_match = $entries['network_dint_match'];
                      $field_empty = $entries['field_empty'];
                      $topup_zero = $entries['topup_zero'];
                      $ssn_dint_match = $entries['ssn_dint_match'];
                      $inserted = $entries['inserted'];
                      $ignored = $entries['ignored'];
                      $not_ignored = $entries['not_ignored'];

                      if($inserted != "0")
                      {
                        echo '<label class="error_message_xls"><strong>'.$inserted.'</strong> Number of Simcard(s) are uploaded from Excel sheet. </label>';
                      }
                      if($duplicated != "0")
                      {
                        echo '<label class="error_message_xls"><strong>'.$duplicated.'</strong> Number of Simcard(s) are not uploaded. Because ssn number already exists. </label><hr>';
                      }
                      if($network_dint_match != "0")
                      {
                        echo '<label class="error_message_xls"><strong>'.$network_dint_match.'</strong> Number of Simcard(s) are not uploaded. Because Network Id is not matched with our database. </label><hr>';
                      }
                      if($field_empty != "0")
                      {
                        echo '<label class="error_message_xls"><strong>'.$field_empty.'</strong> Number of Simcard(s) are not uploaded. Some Fields are Empty. </label>';
                      }
                      if($topup_zero != "0")
                      {
                        echo '<label class="error_message_xls"><strong>'.$topup_zero.'</strong> Number of Simcard(s) are not uploaded. Because Topup no Contains zero value. </label>';
                      }
                      if($ssn_dint_match != "0")
                      {
                        echo '<label class="error_message_xls"><strong>'.$ssn_dint_match.'</strong> Number of Simcard(s) are not uploaded. Because ssn is not matched with our database. </label><hr>';
                      }

                      if($ignored != "0")
                      {
                        echo '<label class="error_message_xls"><strong>'.$ignored.'</strong> Number of Simcard(s) are ignored for Shop review. Because Topup value is minimum compared to commission plan. </label><hr>';
                      }
                    }
                    ?>
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
			<div class="main_title">Manage <span>Commission</span></div>
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

		<div class="col-lg-12">
			<!-- <a href="#" class="common_button float_right add_button_class" data-toggle="modal" data-target=".add_model">Add New Commission</a>
      <a href="#" style="margin-right: 10px;" class="common_button float_right" data-toggle="modal" data-target=".upload_connection">Upload Commission File</a> -->
		</div>
		<div class="col-lg-12">
			<div class="table-responsive">
		        <table class="table table-striped" id="data_table">
		          <thead class="thead-dark">
		            <tr>
		              <th scope="col" style="width: 100px;">#</th>
		              <th scope="col">Shop Name</th>	
                  <th scope="col">Account number</th>  	
                  <th scope="col">Area</th>    
                  <th scope="col">Sales REP</th>    
                  <th scope="col"># Connection</th>    
                  <th scope="col">Total Amount</th> 
                  <th scope="col">Bonus</th>            
		              <th scope="col" width="100px" style="text-align: center;">Action</th>
		            </tr>
		          </thead>
		          <tbody>

		          	<?php
		          	$output='';
		          	$i=1;
		          	if(count($shops)){
		          		foreach ($shops as $shop) {
		          			$details = DB::table('shop')->where('shop_id',$shop->shop_id)->first();
                    $area_details = DB::table('area')->where('area_id',$details->area_name)->first();
                    $rep_details = DB::table('sales_rep')->where('sales_rep_id',$details->sales_rep)->first();
                    $one_connection = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('one_connection');
                    $two_connection = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('two_topup');
                    $three_connection = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('three_topup');
                    $four_connection = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('four_topup');
                    $five_connection = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('five_topup');
                    $six_connection = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('six_topup');
                    $seven_connection = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('seven_topup');
                    $eight_connection = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('eight_topup');
                    $nine_connection = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('nine_topup');
                    $ten_connection = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('ten_topup');

                    $one_connection_sum = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('one_cost');
                    $two_connection_sum = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('two_cost');
                    $three_connection_sum = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('three_cost');
                    $four_connection_sum = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('four_cost');
                    $five_connection_sum = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('five_cost');
                    $six_connection_sum = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('six_cost');
                    $seven_connection_sum = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('seven_cost');
                    $eight_connection_sum = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('eight_cost');
                    $nine_connection_sum = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('nine_cost');
                    $ten_connection_sum = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('ten_cost');

                    $bonus_sum = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->sum('bonus_cost');

                    $total_topups = $two_connection + $three_connection + $four_connection + $five_connection + $six_connection + $seven_connection + $eight_connection + $nine_connection + $ten_connection;

                    $total_topups_sum = $one_connection_sum + $two_connection_sum + $three_connection_sum + $four_connection_sum + $five_connection_sum + $six_connection_sum + $seven_connection_sum + $eight_connection_sum + $nine_connection_sum + $ten_connection_sum;

                    $total_topups_sum = $total_topups_sum - $bonus_sum;

                    $total_status = DB::table('shop_commission')->where('commission_date',$_GET['comm_date'])->where('shop_id',$shop->shop_id)->where('status',0)->count();

		          			$output.='
		          			<tr>
				          		<td>'.$i.'</td>
				          		<td>'.$details->shop_name.'</td>	
                      <td>CC_'.$details->shop_id.'</td>  
                      <td>'.$area_details->area_name.'</td> 
                      <td>'.$rep_details->firstname.' '.$rep_details->surname.'</td>
                      <td>1st Connection = '.$one_connection.'<br/>Topup = '.$total_topups.'</td>
                      <td>€ '.$total_topups_sum.'</td>
                      <td>€ '.$bonus_sum.'</td> 		          		
				          		<td align="center">
                        <a href="'.URL::to('admin/review_commission?shop_id='.$shop->shop_id.'&date='.$_GET['comm_date'].'').'" data-toggle="tooltip" data-placement="top" title="Review"><i class="fas fa-comments"></i></a>&nbsp;&nbsp;&nbsp;';
                        if($total_status > 0) {
				          		    $output.='<a href="'.URL::to('admin/proceed_commission?shop_id='.$shop->shop_id.'&date='.$_GET['comm_date'].'').'" data-toggle="tooltip" data-placement="top" title="Proceed"><i class="far fa-calendar-check"></i></a>';
                        }
				          		$output.='</td>
				          	</tr>
		          			';
		          			$i++;
		          		}
		          	}
		          	else{
		          		$output.='<tr>
					          		<td></td>					          		
					          		<td align="center">Empty</td>					          							          		
					          		<td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
					          	</tr>';
		          	}
		          	echo $output;
		          	?>
		          </tbody>
		        </table>
		    </div>
		</div>
	</div>
		</div>
	</div>
</div>                
<script>
<?php
if(Session::has('success_error')){
  ?>
  $(document).ready(function() {
    $("#error_from_xls").modal("show");
  });
  <?php
}
?>
</script>
@stop
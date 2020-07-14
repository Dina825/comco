@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->



<div class="col-lg-11 col-md-12 col-sm-12 col-12 offset-lg-1 right_panel">
	<div class="row">
		<div class="col-lg-6 col-sm-6 col-6">
			<div class="main_title">Manage <span>Stock for <?php echo $user_details->firstname.' '.$user_details->surname?></span></div>
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
			<div class="table-responsive">
		        <table class="table table-striped" id="data_table">
		          <thead class="thead-dark">
		            <tr>
		              <th scope="col" style="width: 100px;">#</th>
		              <th scope="col">Date</th>		              
		              <th scope="col" width="100px" style="text-align: center;">Message</th>
		            </tr>
		          </thead>
		          <tbody>
		          	<?php
		          	$output_stock='';
		          	$i=1;
		          	if(count($stocklist)){
		          		foreach ($stocklist as $stock) {
		          			if($stock->status == 0){
		          				$status = 'No Replied';
		          			}
		          			else{
		          				$status = 'Replied';
		          			}

		          			if($stock->submitted == 0){
		          				$send = 'Submitted by admin.';
		          			}
		          			else{
		          				$area_manager = DB::table('area_manager')->where('user_id', $stock->submitted)->first();
		          				$send = 'Submitted by '.$area_manager->firstname.' '.$area_manager->surname;

		          			}

		          			$output_stock.='
		          			<tr>
		          				<td>'.$i.'</td>
		          				<td><a href="'.URL::to('admin/view_stock_details/'.base64_encode($stock->stock_id)).'">'.date('d-M-Y', strtotime($stock->stock_date)).' '.$send.'</a> </td>
		          				<td align="center">'.$status.'</td>
		          			</tr>
		          			';
		          			$i++;
		          		}
		          	}
		          	else{
		          		$output_stock.='
		          		<tr>
		          			<td></td>
		          			<td align="center">Empty</td>
		          			<td></td>
		          		</tr>
		          		';
		          	}
		          	echo $output_stock;
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




})
</script>

@stop
@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->


		<div class="col-lg-11 col-md-12 col-sm-12 col-12 offset-lg-1 right_panel">
			<div class="row">
				<div class="col-lg-6 col-sm-6 col-6">
					<div class="main_title">Commission <span>Settings</span></div>
				</div>
				<div class="col-lg-6 col-sm-6 col-6">
					<a href="<?php echo URL::to('admin/logout')?>" class="logout_button"><i class="fas fa-sign-in-alt"></i> &nbsp;Logout</a>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-6 -col-6">
			      <div class="dropdown dropdown_admin">
			        <button class="btn btn-secondary common_button dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			          Menu
			        </button>
			        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
			          <a class="dropdown-item" href="<?php echo URL::to('admin/commission')?>">Manage Commission</a>
			          <a class="dropdown-item" href="<?php echo URL::to('admin/connection_level')?>">Manage Connection Level</a>
			          <a class="dropdown-item" href="<?php echo URL::to('admin/commission_settings')?>">Settings</a>
			        </div>
			      </div>
			    </div>
				<div class="col-lg-12">					
					<div class="row">
					  	<div class="col-lg-3 col-sm-6 col-12">
					  		<div class="main_content_setting">
					  			<form method="post" action="<?php echo URL::to('admin/setting_update')?>" id="minimum-form">
						  			<div class="sub_title2" style="margin-bottom: 20px;">
						  				Commission Minimum
						  			</div>
						  			<div class="row">
							  			<div class="col-lg-12">
									  		<?php
										      if(Session::has('message_mv')) { ?>
										          <p class="alert alert-info">
										          	 <button type="button" class="close" data-dismiss="alert">&times;</button>
										          	<?php echo Session::get('message_mv'); ?>
										          		
										          	</p>
										      <?php }
										      ?>
									  	</div>
									</div>
						  			<div class="form-group">
						  				<label>Commission Minimum</label>
						  				<input type="number" class="form-control" required name="minimum" placeholder="Enter Minimum Value" value="<?php echo $admin->minimum_value?>">
						  						  				
						  			</div>
						  			<div class="form-group text-right">
						  				<input type="hidden" value="5" name="type">
						  				<input type="submit" value="Update" class="btn btn-primary model_add_button" name="">
						  			</div>
						  		</form>
					  		</div>
					  	</div>

					  	<div class="col-lg-3 col-sm-6 col-12">
					  		<div class="main_content_setting">
					  			<form method="post" action="<?php echo URL::to('admin/setting_update')?>" id="network-minimum-form">
						  			<div class="sub_title2" style="margin-bottom: 20px;">
						  				Commission Network Minimum Value
						  			</div>
						  			<div class="row">
							  			<div class="col-lg-12">
									  		<?php
										      if(Session::has('message_mnv')) { ?>
										          <p class="alert alert-info">
										          	 <button type="button" class="close" data-dismiss="alert">&times;</button>
										          	<?php echo Session::get('message_mnv'); ?>
										          		
										          	</p>
										      <?php }
										      ?>
									  	</div>
									</div>
						  			<div class="form-group">
						  				<table class="table">
						  					<thead>
						  						<th>S.No</th>
						  						<th>Network</th>
						  						<th>Minimum Value</th>
						  					</thead>
						  					<tbody>
						  						<?php
						  						$i = 1;
						  						$networks = DB::table('network')->where('status',0)->orderBy('network_id','asc')->get();
						  						if(count($networks))
						  						{
						  							foreach($networks as $network)
						  							{
						  								if($network->minimum_value == "")
						  								{
						  									$min = 0;
						  								}
						  								else{
						  									$min = $network->minimum_value;
						  								}
						  								?>
						  								<tr>
							  								<td><?php echo $i; ?></td>
							  								<td><?php echo $network->network_name; ?></td>
							  								<td><input type="number" required name="network_minimum_value[]" class="form-control network_minimum_value"  value="<?php echo $min; ?>"></td>
							  							</tr>
						  								<?php
						  								$i++;
						  							}
						  						}
						  						?>
						  					</tbody>
						  				</table>						  						  				
						  			</div>
						  			<div class="form-group text-right">
						  				<input type="hidden" value="6" name="type">
						  				<input type="submit" value="Update" class="btn btn-primary model_add_button" name="">
						  			</div>
						  		</form>
					  		</div>
					  	</div>


					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>   
<script type="text/javascript">
$.ajaxSetup({async:false});


$('#minimum-form').validate({
    rules: {        
        
    },
    messages: {        
        
    },
});








</script>  

        
<!-- /.content -->
@stop
@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->


		<div class="col-lg-11 col-md-12 col-sm-12 col-12 offset-lg-1 right_panel">
			<div class="row">
				<div class="col-lg-6 col-sm-6 col-6">
					<div class="main_title">Accessories <span>Settings</span></div>
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
					<div class="row">
					  	<div class="col-lg-3 col-sm-6 col-12">
					  		<div class="main_content_setting">
					  			<form method="post" action="<?php echo URL::to('admin/setting_update')?>" id="vat-form">
						  			<div class="sub_title2" style="margin-bottom: 20px;">
						  				VAT Percentage
						  			</div>
						  			<div class="row">
							  			<div class="col-lg-12">
									  		<?php
										      if(Session::has('message_vat')) { ?>
										          <p class="alert alert-info">
										          	 <button type="button" class="close" data-dismiss="alert">&times;</button>
										          	<?php echo Session::get('message_vat'); ?>
										          		
										          	</p>
										      <?php }
										      ?>
									  	</div>
									</div>
						  			<div class="form-group">						  				
						  				<input type="number" class="form-control" required name="vat" placeholder="Enter VAT Percentage" value="<?php echo $admin->vat_percentage?>">
						  						  				
						  			</div>
						  			<div class="form-group text-right">
						  				<input type="hidden" value="7" name="type">
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


$('#vat-form').validate({
    rules: {        
        
    },
    messages: {        
        
    },
});








</script>  

        
<!-- /.content -->
@stop
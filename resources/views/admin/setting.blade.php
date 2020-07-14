@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->


		<div class="col-lg-11 col-md-12 col-sm-12 col-12 offset-lg-1 right_panel">
			<div class="row">
				<div class="col-lg-6 col-sm-6 col-6">
					<div class="main_title">Settings</div>
				</div>
				<div class="col-lg-6 col-sm-6 col-6">
					<a href="<?php echo URL::to('admin/logout')?>" class="logout_button"><i class="fas fa-sign-in-alt"></i> &nbsp;Logout</a>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">					
					<div class="row">							
					  	<div class="col-lg-3 col-sm-6 col-12">
					  		<div class="main_content_setting">
					  			<form action="<?php echo URL::to('admin/setting_update')?>" method="post" id="login-form">
						  			<div class="sub_title2" style="margin-bottom: 20px;">
						  				Admin Login Setting
						  			</div>
						  			<div class="row">
							  			<div class="col-lg-12">
									  		<?php
										      if(Session::has('message_login')) { ?>
										          <p class="alert alert-info">
										          	 <button type="button" class="close" data-dismiss="alert">&times;</button>
										          	<?php echo Session::get('message_login'); ?>
										          		
										          	</p>
										      <?php }
										      ?>
									  	</div>
									</div>
						  			<div class="form-group">
						  				<label>User Name</label>
						  				<input type="text" class="form-control" placeholder="Enter User Name" value="<?php echo $admin->username?>" readonly name="">
						  			</div>
						  			<div class="form-group">
						  				<label>Old Password</label>
						  				<input type="password" required class="form-control" placeholder="Enter Old Password" name="opassword">
						  			</div>
						  			<div class="form-group">
						  				<label>New Password</label>
						  				<input type="password" required class="form-control" placeholder="Enter New Password" name="password" id="password">
						  			</div>
						  			<div class="form-group">
						  				<label>Confirm New Password</label>
						  				<input type="password" required class="form-control" placeholder="Enter Confirm New Password" name="cpassword">
						  			</div>
						  			<div class="form-group text-right">
						  				<input type="hidden" value="1" name="type">
						  				<input type="submit" value="Update" class="btn btn-primary model_add_button" name="">
						  			</div>
						  		</form>
					  		</div>
					  	</div>
					  	<div class="col-lg-3 col-sm-6 col-12">
					  		<div class="main_content_setting">					  			
					  			<form method="post" action="<?php echo URL::to('admin/setting_update')?>" id="social-form">
						  			<div class="sub_title2" style="margin-bottom: 20px;">
						  				Social Media Links
						  			</div>
						  			<div class="row">
							  			<div class="col-lg-12">
									  		<?php
										      if(Session::has('message_social')) { ?>
										          <p class="alert alert-info">
										          	 <button type="button" class="close" data-dismiss="alert">&times;</button>
										          	<?php echo Session::get('message_social'); ?>
										          		
										          	</p>
										      <?php }
										      ?>
									  	</div>
									</div>

						  			<div class="form-group">
						  				<label>Facebook</label>
						  				<input type="text" class="form-control" required placeholder="Enter Facebook URL" value="<?php echo $admin->facebook?>" name="facebook">
						  			</div>
						  			<div class="form-group">
						  				<label>Twitter</label>
						  				<input type="text" class="form-control" required placeholder="Enter Twitter URL" value="<?php echo $admin->twitter?>" name="twitter">
						  			</div>
						  			<div class="form-group">
						  				<label>LinkedIn</label>
						  				<input type="text" class="form-control" required placeholder="Enter LinkedIn URL" value="<?php echo $admin->linkedin?>" name="linkedin">
						  			</div>
						  			<div class="form-group">
						  				<label>Instagram</label>
						  				<input type="text" class="form-control" required placeholder="Enter Instagram URL" value="<?php echo $admin->instagram?>"  name="instagram">
						  			</div>
						  			<div class="form-group text-right">
						  				<input type="hidden" value="2" name="type">
						  				<input type="submit" value="Update" class="btn btn-primary model_add_button" name="">
						  			</div>
					  			</form>
					  		</div>
					  	</div>
					  	<div class="col-lg-3 col-sm-6 col-12">
					  		<div class="main_content_setting">
					  			<form method="post" action="<?php echo URL::to('admin/setting_update')?>" id="email-form">
						  			<div class="sub_title2" style="margin-bottom: 20px;">
						  				Email Setting
						  			</div>
						  			<div class="row">
							  			<div class="col-lg-12">
									  		<?php
										      if(Session::has('message_email')) { ?>
										          <p class="alert alert-info">
										          	 <button type="button" class="close" data-dismiss="alert">&times;</button>
										          	<?php echo Session::get('message_email'); ?>
										          		
										          	</p>
										      <?php }
										      ?>
									  	</div>
									</div>
						  			<div class="form-group">
						  				<label>Admin Email</label>
						  				<input type="email" class="form-control" required placeholder="Enter Admin Email ID" value="<?php echo $admin->email?>"   name="email">
						  			</div>
						  			<div class="form-group text-right">
						  				<input type="hidden" value="3" name="type">
						  				<input type="submit" value="Update" class="btn btn-primary model_add_button" name="">
						  			</div>
						  		</form>
					  		</div>
					  	</div>
					  	<div class="col-lg-3 col-sm-6 col-12">
					  		<div class="main_content_setting">
					  			<form method="post" action="<?php echo URL::to('admin/setting_update')?>" id="seo-form">
						  			<div class="sub_title2" style="margin-bottom: 20px;">
						  				SEO Setting
						  			</div>
						  			<div class="row">
							  			<div class="col-lg-12">
									  		<?php
										      if(Session::has('message_seo')) { ?>
										          <p class="alert alert-info">
										          	 <button type="button" class="close" data-dismiss="alert">&times;</button>
										          	<?php echo Session::get('message_seo'); ?>
										          		
										          	</p>
										      <?php }
										      ?>
									  	</div>
									</div>
						  			<div class="form-group">
						  				<label>Keywords</label>
						  				<textarea class="form-control" required name="keywords" style="height: 115px" placeholder="Enter Keywords"><?php echo $admin->keywords?></textarea>					  				
						  			</div>
						  			<div class="form-group">
						  				<label>Description</label>
						  				<textarea class="form-control" name="description" required style="height: 115px" placeholder="Enter Description"><?php echo $admin->description?></textarea>
						  			</div>
						  			<div class="form-group text-right">
						  				<input type="hidden" value="4" name="type">
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
$('#login-form').validate({
    rules: {        
        opassword : {required: true, remote:"<?php echo URL::to('admin/admin_password_check')?>"},
        password:{required: true},
        cpassword:{required: true, equalTo: password},        
    },
    messages: {        
        opassword : {
          required : "This field is required",
          remote : "Old password incorrect",
        },
        password:{required:"Enter Password"},      
        cpassword : {
            required: "Confirm Password Field is required",
            equalTo : "Should match to above field",
        },
    },
});
$('#social-form').validate({
    rules: {        
        
    },
    messages: {        
        
    },
});
$('#email-form').validate({
    rules: {        
        
    },
    messages: {        
        
    },
});
$('#seo-form').validate({
    rules: {        
        
    },
    messages: {        
        
    },
});


</script>  
       
<!-- /.content -->
@stop
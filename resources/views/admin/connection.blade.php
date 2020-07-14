@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->
<div class="modal fade add_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm ">
    <div class="modal-content">
      <form method="post" action="<?php echo URL::to('admin/connection_add')?>"  enctype="multipart/form-data" id="add-form">
       <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Connection Level</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">                    
      						<div class="form-group">
      						    <label>Enter Connection Level</label>
      						    <input type="number" class="form-control title name_class" name="level" required placeholder="Enter Connection Level">
      						</div>
      				  </div>                                      
            </div>                
      </div>
      <div class="modal-footer">       
        <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn model_add_button add_button">Add New</button>
      </div>
      </form>
    </div>
  
  </div>
</div>

<div class="modal fade edit_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-sm ">
    <div class="modal-content">
      <form method="post" action="<?php echo URL::to('admin/connection_update')?>"  enctype="multipart/form-data" id="edit-form">
       <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Connection Level</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
            <div class="row">
            	<div class="col-lg-12 col-md-12 col-sm-12 col-12">                    
      						<div class="form-group">
      						    <label>Enter Connection Level</label>
      						    <input type="text" class="form-control class_name" name="level" required placeholder="Enter Connection Level">
      						</div>
      				</div>
            </div>                
      </div>
      <div class="modal-footer">       
      	<input type="hidden" class="class_id" name="id">
        <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn model_add_button update_button">Update</button>
      </div>
      </form>
    </div>
  
  </div>
</div>



<div class="modal fade status_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="<?php echo URL::to('admin/connection_status')?>">
                <div class="modal-header">
                    <h5 class="modal-title active_deactive_title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="sub_title3 active_deactive_content" style="line-height: 25px;"></div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" class="id_filed" value="" name="id">
                    <input type="hidden" class="status_filed" value="" name="status">
                    <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-primary model_add_button">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="col-lg-11 col-md-12 col-sm-12 col-12 offset-lg-1 right_panel">
	<div class="row">
		<div class="col-lg-6 col-sm-6 col-6">
			<div class="main_title">Manage <span>Connection Level</span></div>
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
		<div class="col-lg-10 col-md-10 col-sm-6 -col-6">
			<a href="#" class="common_button float_right add_button_class" data-toggle="modal" data-target=".add_model">Add New Connection Level</a>
		</div>
		<div class="col-lg-12">
			<div class="table-responsive">
		        <table class="table table-striped" id="data_table">
		          <thead class="thead-dark">
		            <tr>
		              <th scope="col" style="width: 100px;">#</th>
		              <th scope="col">Connection Level</th>		              
		              <th scope="col" width="100px" style="text-align: center;">Action</th>
		            </tr>
		          </thead>
		          <tbody>

		          	<?php
		          	$output='';
		          	$i=1;
		          	if(count($connection_list)){
		          		foreach ($connection_list as $connection) {
		          			if($connection->status == 0){
		          				$status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Deactive" style="color: #33CC66"><i class="fas fa-check deactive_class" data-element="'.base64_encode($connection->connection_id).'"></i></a>';
		          			}
		          			else{
		          				$status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Active" style="color: #E11B1C"><i class="fas fa-times active_class" data-element="'.base64_encode($connection->connection_id).'"></i></a>';
		          			}
		          			$output.='
		          			<tr>
				          		<td>'.$i.'</td>
				          		<td>'.$connection->level.'</td>				          		
				          		<td align="center">
				          		<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Connection Level" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($connection->connection_id).'"></i></a>&nbsp;&nbsp;&nbsp;				          		
				          		'.$status.'
				          		</td>
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
<!-- /.content -->
<script type="text/javascript">
$(window).click(function(e) {

if($(e.target).hasClass("active_class")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {
    var value = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('admin/connection_details')?>",
        dataType:'json',
        data:{id:value, type:1},
        type:"post",
        success: function(result)
        {
          $(".active_deactive_title").html(result['title']);
          $(".id_filed").val(result['id']);
          $(".active_deactive_content").html(result['content']);
          $(".status_filed").val(result['status']);
          $(".status_model").modal('show');
          $(".loading_css").removeClass("loading");
        }
      })
  },500);
}
if($(e.target).hasClass("deactive_class")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {
    var value = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('admin/connection_details')?>",
        dataType:'json',
        data:{id:value, type:2},
        type:"post",
        success: function(result)
        {
          $(".active_deactive_title").html(result['title']);
          $(".id_filed").val(result['id']);
          $(".active_deactive_content").html(result['content']);
          $(".status_filed").val(result['status']);
          $(".status_model").modal('show');
          $(".loading_css").removeClass("loading");
        }
      })
    },500);
}
if($(e.target).hasClass("edit_icon")){	
  $(".loading_css").addClass("loading");
  setTimeout(function() {
    var value = $(e.target).attr("data-element");  
    $.ajax({
        url:"<?php echo URL::to('admin/connection_details')?>",
        dataType:'json',
        data:{id:value, type:3},
        type:"post",
        success: function(result)
        {
        	$(".class_id").val(result['id']);
        	$(".class_name").val(result['name']);
          $(".edit_model").modal("show");
          $(".loading_css").removeClass("loading");
        }
      })
    },500);
}

if($(e.target).hasClass("add_button_class")){
	$(".name_class").val('');
}



})
</script>

<script type="text/javascript">


$.ajaxSetup({async:false});
$('#add-form').validate({
    rules: { 
        level: {required: true, remote:"<?php echo URL::to('admin/connection_level_check')?>"},               
    },
    messages: {        
        level : {
          required : "Enter Connection Level",
          remote : "Connection Level is already exists",
      },
    },
});
$.ajaxSetup({async:false});
$('#edit-form').validate({
    rules: {        
        level : {required: true, remote: { url:"<?php echo URL::to('admin/connection_level_check')?>", 
                  data: {'connection_id':function(){return $('.class_id').val()}},
                  async:false 
              },
          },
    },
    messages: {        
        level : {
          required : "Enter Connection Level",
          remote : "Connection Level is already exists",
        },
    },
});

</script>
@stop
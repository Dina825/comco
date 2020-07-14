@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->
<div class="modal fade add_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm ">
    <div class="modal-content">
      <form method="post" action="<?php echo URL::to('admin/network_add')?>"  enctype="multipart/form-data" id="add-form">
       <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Network</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">                    
      						<div class="form-group">
      						    <label>Enter Network ID</label>
      						    <input type="text" class="form-control add_input" name="network" required placeholder="Enter Area Name">
      						</div>
                  <div class="form-group">
                      <label>Enter Product ID</label>
                      <input type="text" class="form-control add_input" name="product_id" required placeholder="Enter Product ID">                      
                  </div>
                  <div class="form-group">
                      <label>Enter Name</label>
                      <input type="text" class="form-control add_input" name="name" required placeholder="Enter Name">                      
                  </div>
                  <div class="form-group">
                      <label>Enter Minimum Value</label>
                      <input type="number" class="form-control minimum_value" name="minimum_value" required placeholder="Enter Minimum Value">                      
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
      <form method="post" action="<?php echo URL::to('admin/network_update')?>"  enctype="multipart/form-data" id="edit-form">
       <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Area</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
            <div class="row">
            	<div class="col-lg-12 col-md-12 col-sm-12 col-12">                    
      						<div class="form-group">
      						    <label>Enter Network ID</label>
      						    <input type="text" class="form-control network_class" name="network" required placeholder="Enter Name">
      						</div>
                  <div class="form-group">
                      <label>Enter Product ID</label>
                      <input type="text" class="form-control product_class" name="product_id" required placeholder="Enter Product ID">
                  </div>
                  <div class="form-group">
                      <label>Enter Name</label>
                      <input type="text" class="form-control name_class" name="name" required placeholder="Enter Name">
                  </div>
                  <div class="form-group">
                      <label>Enter Minimum Value</label>
                      <input type="number" class="form-control minimum_class" name="minimum_value" required placeholder="Enter Minimum Value">
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
            <form action="<?php echo URL::to('admin/network_status')?>">
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
			<div class="main_title">Manage <span>Network</span></div>
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
            <a class="dropdown-item" href="<?php echo URL::to('admin/simcards')?>">Manage Simcards</a>
            <a class="dropdown-item" href="<?php echo URL::to('admin/network')?>">Manage Network</a>              
          </div>
        </div>
      </div>
		<div class="col-lg-10 col-md-10 col-sm-6 -col-6">
			<a href="#" class="common_button float_right add_button_class" data-toggle="modal" data-target=".add_model">Add New Area</a>
		</div>
		<div class="col-lg-12">
			<div class="table-responsive">
		        <table class="table table-striped" id="data_table">
		          <thead class="thead-dark">
		            <tr>
		              <th scope="col" style="width: 100px;">#</th>
		              <th scope="col">Network ID</th>
                  <th scope="col">Product ID</th>
                  <th scope="col">Name</th>
                  <th scope="col">Minimum value</th>
		              <th scope="col" width="100px" style="text-align: center;">Action</th>
		            </tr>
		          </thead>
		          <tbody>

		          	<?php
		          	$output='';
		          	$i=1;
		          	if(count($network_list)){
		          		foreach ($network_list as $network) {
		          			if($network->status == 0){
		          				$status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Deactive" style="color: #33CC66"><i class="fas fa-check deactive_class" data-element="'.base64_encode($network->network_id).'"></i></a>';
		          			}
		          			else{
		          				$status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Active" style="color: #E11B1C"><i class="fas fa-times active_class" data-element="'.base64_encode($network->network_id).'"></i></a>';
		          			}
		          			$output.='
		          			<tr>
				          		<td>'.$i.'</td>
				          		<td>'.$network->network_name.'</td>
                      <td>'.$network->product_id.'</td>
                      <td>'.$network->name.'</td>
                      <td>'.$network->minimum_value.'</td>
				          		<td align="center">
				          		<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Network" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($network->network_id).'"></i></a>&nbsp;&nbsp;&nbsp;				          		
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
    var value = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('admin/network_details')?>",
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
        }
      })
}
if($(e.target).hasClass("deactive_class")){
    var value = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('admin/network_details')?>",
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
        }
      })
}
if($(e.target).hasClass("edit_icon")){	
    var value = $(e.target).attr("data-element");  
    $.ajax({
        url:"<?php echo URL::to('admin/network_details')?>",
        dataType:'json',
        data:{id:value, type:3},
        type:"post",
        success: function(result)
        {
        	$(".class_id").val(result['id']);
        	$(".network_class").val(result['network_name']);
          $(".product_class").val(result['product_id']);
          $(".name_class").val(result['name']);
          $(".minimum_class").val(result['minimum_value']);
          $(".edit_model").modal("show");
        }
      })
}

if($(e.target).hasClass("add_button_class")){
	$(".add_input").val('');
}



})
</script>

<script type="text/javascript">


$.ajaxSetup({async:false});
$('#add-form').validate({
    rules: {        
        network : {required: true,  remote:"<?php echo URL::to('admin/network_check')?>"},        
        product_id : {required: true},        
        name : {required: true},
        minimum_value : {required: true},
    },
    messages: {        
        network : {required : "Enter Network ID", remote : "Network ID is already exists"},        
        product_id : {required : "Enter Product ID"},        
        name : {required : "Enter Name"},
        minimum_value : {required : "Enter Minimum Value"},
    },
});
$.ajaxSetup({async:false});
$('#edit-form').validate({
    rules: {  
        network : {required: true, remote: { url:"<?php echo URL::to('admin/network_check')?>", 
                  data: {'network_id':function(){return $('.class_id').val()}},
                  async:false 
              },
          },      
        product_id : {required: true},
        name : {required: true},
        minimum_value : {required: true},
    },
    messages: {        
        network : {required : "Enter Network ID", remote : "Network ID is already exists",},        
        product_id : {required : "Enter Product ID"},        
        name : {required : "Enter Name"},
        minimum_value : {required : "Enter Minimum Value"},
    },
});

</script>
@stop
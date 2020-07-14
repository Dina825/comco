@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->
<div class="modal fade add_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm ">
    <div class="modal-content">
      <form method="post" action="<?php echo URL::to('admin/coupon_add')?>"  enctype="multipart/form-data" id="add-form">
       <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Coupon</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">                    
      						<div class="form-group">
      						    <label>Enter Coupon Name</label>
      						    <input type="text" class="form-control add_input" name="name" required placeholder="Enter Coupon Name">
      						</div>
                  <div class="form-group">
                      <label>Select Category</label>
                      <?php
                        $output_category='<div class="row">';
                        if(count($category_list)){
                          foreach ($category_list as $category) {
                            $output_category.='

                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label class="form_checkbox">'.$category->category_name.'
                           <input type="checkbox" value="'.$category->category_id.'" class="category_checkbox" style="width:1px; height:1px" name="category[]"  required>
                           <span class="checkmark_checkbox"></span>
                        </label>
                              </div>';
                          }
                          $output_category.='<div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <label id="category[]-error" class="error" style="display: none" for="category[]">Please choose atleast one Category.</label>
                          </div> </div>';
                        }
                        else{
                          $output_category='<option value="">No Catgory</option>';
                        }
                        echo $output_category;
                        ?>                                        
                  </div>
                  <div class="form-group">
                      <label>Enter Coupon Code</label>
                      <input type="text" class="form-control add_input" name="code" required placeholder="Enter Coupon Code">
                  </div>
                  <div class="form-group">
                      <label>Select Validate Date</label>
                      <input type="text" class="form-control add_input validate_date" name="date" required placeholder="Enter Validate Date">
                  </div>
                  <div class="form-group">
                      <label>Enter Discount Percentage</label>
                      <input type="number" class="form-control add_input" name="discount" required placeholder="Enter Discount Percentage">
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
      <form method="post" action="<?php echo URL::to('admin/coupon_update')?>"  enctype="multipart/form-data" id="edit-form">
       <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Coupon</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
            <div class="row">
            	<div class="col-lg-12 col-md-12 col-sm-12 col-12">                    
      						<div class="form-group">
      						    <label>Enter Coupon Name</label>
      						    <input type="text" class="form-control class_name" name="name" required placeholder="Enter Coupon Name">
      						</div> 
                  <div class="form-group">
                      <label>Select Category</label>
                      <div class="category_class"></div>
                  </div>
                  <div class="form-group">
                      <label>Enter Coupon Code</label>
                      <input type="text" class="form-control code_class" name="code" required placeholder="Enter Coupon Code">
                  </div>
                  <div class="form-group">
                      <label>Select Validate Date</label>
                      <input type="text" class="form-control date_class validate_date" name="date" required placeholder="Enter Validate Date">
                  </div>
                  <div class="form-group">
                      <label>Enter Discount Percentage</label>
                      <input type="number" class="form-control discount_class" name="discount" required placeholder="Enter Discount Percentage">
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
            <form action="<?php echo URL::to('admin/coupon_status')?>">
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
			<div class="main_title">Manage <span>Coupon</span></div>
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
			<a href="#" class="common_button float_right add_button_class" data-toggle="modal" data-target=".add_model">Add New Coupon</a>
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
		              <th scope="col" style="width: 100px;">#</th>
		              <th scope="col">Coupon Name</th>
                  <th scope="col">Category</th>                  
                  <th scope="col">Code</th>
                  <th scope="col">Validate Date</th>
                  <th scope="col">Discount</th>
		              <th scope="col" width="100px" style="text-align: center;">Action</th>
		            </tr>
		          </thead>
		          <tbody>

		          	<?php
		          	$output='';
		          	$i=1;
		          	if(count($coupon_list)){
		          		foreach ($coupon_list as $coupon) {
		          			if($coupon->status == 0){
		          				$status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Deactive" style="color: #33CC66"><i class="fas fa-check deactive_class" data-element="'.base64_encode($coupon->coupon_id).'"></i></a>';
		          			}
		          			else{
		          				$status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Active" style="color: #E11B1C"><i class="fas fa-times active_class" data-element="'.base64_encode($coupon->coupon_id).'"></i></a>';
		          			}

                    $explode_category = explode(',', $coupon->coupon_category);

                    $category='';
                    if(count($explode_category)){
                      foreach ($explode_category as $explode) {
                        $category_details = DB::table('category')->where('category_id', $explode)->first();
                        if($category == ''){
                          $category = $category_details->category_name;
                        }
                        else{
                          $category = $category.', '.$category_details->category_name;
                        }
                      }
                    }

                    
		          			$output.='
		          			<tr>
				          		<td>'.$i.'</td>
				          		<td>'.$coupon->coupon_name.'</td>
                      <td>'.$category.'</td>
                      <td>'.$coupon->coupon_code.'</td>
                      <td>'.date('d-m-Y', strtotime($coupon->coupon_date)).'</td>
                      <td>'.$coupon->coupon_discount.' %</td>
				          		<td align="center">
				          		<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Coupon" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($coupon->coupon_id).'"></i></a>&nbsp;&nbsp;&nbsp;				          		
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
                        <td></td>
                        <td></td>
					          		<td align="center">Empty</td>					          							          		
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
<!-- /.content -->
<script type="text/javascript">
$(window).click(function(e) {

if($(e.target).hasClass("active_class")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {
    var value = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('admin/coupon_details')?>",
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
        url:"<?php echo URL::to('admin/coupon_details')?>",
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
        url:"<?php echo URL::to('admin/coupon_details')?>",
        dataType:'json',
        data:{id:value, type:3},
        type:"post",
        success: function(result)
        {
        	$(".class_id").val(result['id']);
        	$(".class_name").val(result['name']);
          $(".category_class").html(result['category']);
          $(".code_class").val(result['code']);
          $(".date_class").val(result['date']);
          $(".discount_class").val(result['discount']);
          $(".edit_model").modal("show");
          $(".loading_css").removeClass("loading");
        }
      })
    },500);
}

if($(e.target).hasClass("add_button_class")){
	$(".add_input").val('');
  $(".category_checkbox").prop("checked", false);
}



})
</script>

<script type="text/javascript">


$.ajaxSetup({async:false});
$('#add-form').validate({
    rules: {        
        name : {required: true},
        "category[]" : {required: true},
        code : {required: true, remote:"<?php echo URL::to('admin/coupon_code_check')?>"},
        date : {required: true},
        discount : {required: true},
    },
    messages: {        
        name : {required : "Enter Coupon Name"},
        "category[]"  : {required : "Please choose atleast one Category."},
        code : {required : "Enter Coupon Code", remote : "Coupon Code is already exists",},
        date : {required : "Please Select validate date"},
        discount : {required : "Enter Discount Percentage"},
    },
});
$.ajaxSetup({async:false});
$('#edit-form').validate({
    rules: {        
        name : {required: true},
        "category[]" : {required: true},
        code : {required: true, remote: { url:"<?php echo URL::to('admin/coupon_code_check')?>", 
                  data: {'coupon_id':function(){return $('.class_id').val()}},
                  async:false 
              },
          },        
        date : {required: true},
        discount : {required: true},
    },
    messages: {        
        name : {required : "Enter Coupon Name"},
        "category[]" : {required : "Please choose atleast one Category."},
        code : {required : "Enter Coupon Code", remote : "Coupon Code is already exists",},
        date : {required : "Please Select validate date"},
        discount : {required : "Enter Discount Percentage"},
    },
});


$(function () {
  $(".validate_date").datetimepicker({
     format: 'L',
     format: 'DD-MM-YYYY',
     icons: {
        time: "fa fa-clock-o",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down",
        previous: "fa fa-chevron-left",
        next: "fa fa-chevron-right",
        today: "fa fa-clock-o",
        clear: "fa fa-trash-o"
    },
  });
});
</script>
@stop
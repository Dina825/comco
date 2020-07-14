@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->

<div class="modal fade sim_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="min-width: 75%;">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title sim_title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="table-responsive">
                <table class="table table-striped" id="sim_table">
                  <thead class="thead-dark">
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Network Id</th>
                      <th scope="col">Product Id</th>                     
                      <th scope="col">SSN</th>
                      <th scope="col">Cli</th>
                      <th scope="col">Allocation Date</th>
                      <th scope="col">Sales Rep</th>
                      <th scope="col">Shop Name</th>
                      <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody class="output_import">
                  
                  
                </tbody>
            </table>
          </div>

                </div>
                <div class="modal-footer">                    
                    
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade add_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="add-form" method="post" action="<?php echo URL::to('admin/route_add')?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title active_deactive_title" id="exampleModalLabel">Add Route</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                	<div class="row">
                		<div class="col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 15px;">
                			<label>Choose Area</label>
                			<div class="row">

                				<div class="col-lg-12 col-md-12 col-sm-12 col-12">
                          <select class="form-control select_area_class" required name="select_area">
                            <option value="">Select Area</option>
                            <?php
                            $output_area='';
                            if(count($arealist)){
                              foreach ($arealist as $area) {
                                $output_area.='
                                <option value="'.$area->area_id.'">'.$area->area_name.'</option>';
                              }
                            }
                            else{
                              $output_area='<div class="col-lg-12">Empty</div>';
                            }
                            echo $output_area;
                            ?>
                          </select>    
                        </div>

                			</div>
                		</div>
                    <div class="sales_rep_class"></div>
                		<div class="col-lg-12 col-md-12 col-sm-12">
                			<div class="form-group">
		                    	<label>Enter Route Name</label>
		                    	<input type="text" class="form-control add_input" placeholder="Enter Route Name" required name="routename">
		                    </div>
		                </div>

                	</div>
                    
                    


                </div>
                <div class="modal-footer">                    
                    <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary model_add_button add_new_button">Add New</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade edit_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="edit-form" method="post" action="<?php echo URL::to('admin/route_update')?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title active_deactive_title" id="exampleModalLabel">Edit Route</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 15px;">
                      <label>Choose Area</label>
                      <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                          <select class="form-control select_edit_area_class" required name="select_area">
                            <option value="">Select Area</option>
                            <?php
                            $output_area='';
                            if(count($arealist)){
                              foreach ($arealist as $area) {
                                $output_area.='
                                <option value="'.$area->area_id.'">'.$area->area_name.'</option>';
                              }
                            }
                            else{
                              $output_area='<div class="col-lg-12">Empty</div>';
                            }
                            echo $output_area;
                            ?>
                          </select>    
                        </div>

                      </div>
                    </div>
                    <div class="sales_rep_edit_class"></div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                      <div class="form-group">
                          <label>Enter Route Name</label>
                          <input type="text" class="form-control add_input edit_route_class" placeholder="Enter Route Name" required name="routename">
                        </div>
                    </div>

                  </div>
                    
                    


                </div>
                <div class="modal-footer">
                      <input type="hidden" class="id_filed" name="id">
                    <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary model_add_button update_button">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>




<div class="modal fade status_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="<?php echo URL::to('admin/route_status')?>">
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
			<div class="main_title">Manage <span>Route</span></div>
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
        <div class="col-lg-1 col-md-2 col-sm-6 -col-6">
          <div class="dropdown dropdown_admin">
            <button class="btn btn-secondary common_button dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Menu
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="<?php echo URL::to('admin/area')?>">Manage Area</a>
              <a class="dropdown-item" href="<?php echo URL::to('admin/route')?>">Manage Route</a>          
            </div>
          </div>
        </div>
        
    
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
          <div class="form-group">
            <label>Filter By Area</label>
            <select class="form-control filter_by_area">
              <option value="">Select Area</option>

              <?php
              $output_area='';
              if(count($arealist)){
                foreach ($arealist as $area) {
                  $output_area.='
                  <option value="'.$area->area_id.'">'.$area->area_name.'</option>';
                }
              }
              else{
                $output_area='<div class="col-lg-12">Empty</div>';
              }
              echo $output_area;
              ?>
            </select>
          </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-6">
          <div class="form-group">
            <label>Filter Sales REP</label>
            <select class="form-control filter_sales_rep" disabled>
              <option value="">Select Sales REP</option>
            </select>
          </div>
        </div>

        
		<div class="col-lg-7 col-md-4 col-sm-6 col-6">
			<a href="javascript:" class="common_button float_right add_button_class" data-toggle="modal" data-target=".add_modal">Add New Route</a>
		</div>
		<div class="col-lg-12">
			<div class="table-responsive">
		        <table class="table table-striped" id="data_table">
		          <thead class="thead-dark">
		            <tr>
		              <th scope="col">#</th>
                  <th scope="col">Route</th>
		              <th scope="col">Sales REP</th>		              
		              <th scope="col">Area Name</th>
		              <th scope="col">Total SIM</th>
                  <th scope="col">Active SIM</th>
		              <th scope="col">Inactive SIM</th>
                  <th scope="col">Shop</th>
                  <th scope="col"></th>
		              <th scope="col" width="100px" style="text-align: center;">Action</th>
		            </tr>
		          </thead>
		          <tbody id="output_route">
                  <?php
                  $output_route='';
                  $i=1;
                  if(count($routelist)){
                    foreach ($routelist as $route) {

                        $explode_sales = explode(',', $route->sales_rep_id);
                        $area_salerep='';
                        if(count($explode_sales)){
                            foreach ($explode_sales as $sales) {
                                $salesrep_details = DB::table('sales_rep')->where('user_id', $sales)->first();
                                if($area_salerep == ''){
                                    $area_salerep = $salesrep_details->firstname;
                                }
                                else{
                                    $area_salerep  = $salesrep_details->firstname.', '.$area_salerep;
                                }
                                
                            }
                        }
                        else{
                            $area_salerep='';
                        }

                        $area_details = DB::table('area')->where('area_id',$route->area_id)->first();

                        
                        if($route->status == 0){
                            $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Deactive" style="color: #33CC66"><i class="fas fa-check deactive_class" data-element="'.base64_encode($route->route_id).'"></i></a>';
                        }
                        else{
                            $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Active" style="color: #E11B1C"><i class="fas fa-times active_class" data-element="'.base64_encode($route->route_id).'"></i></a>';
                        }

                        


                        

                        $output_route.='
                        <tr>
                          <td>'.$i.'</td>
                          <td>'.$route->route_name.'</td>
                          <td>'.$area_salerep.'</td>
                          <td>'.$area_details->area_name.'</td>                          
                          <td align="center" class="total_'.$route->route_id.'"></td>
                          <td align="center" class="active_'.$route->route_id.'"></td>
                          <td align="center" class="inactive_'.$route->route_id.'"></td>
                          <td align="center" class="shop_'.$route->route_id.'"></td>
                          <td><a href="javascript:"><i class="fas fa-sync-alt refresh_icon" data-element="'.base64_encode($route->route_id).'"></i></a></td>
                          <td align="center">
                          <a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Route" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($route->route_id).'"></i></a>&nbsp;&nbsp;&nbsp;    
                          '.$status.'
                          </td>
                        </tr>
                        ';
                        $i++;
                    }
                  }
                  else{
                    $output_route='
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td align="center">Empty</td>                      
                      <td></td>
                      <td></td>
                      <td></td>                                  
                      <td></td>
                    </tr>
                    ';
                  }
                  echo $output_route;
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
    $(".loading_css").addClass("loading"); 
    setTimeout(function() {
      $.ajax({
          url:"<?php echo URL::to('admin/route_details')?>",
          dataType:'json',
          data:{id:value, type:1},
          type:"post",
          success: function(result)
          {
            $(".active_deactive_title").html(result['title']);
            $(".id_filed").val(result['id']);
            $(".active_deactive_content").html(result['content']);
            $(".status_filed").val(result['status']);
            $(".status_modal").modal('show');
            $(".loading_css").removeClass("loading");
          }
        })
    },500);
}
if($(e.target).hasClass("deactive_class")){
    var value = $(e.target).attr("data-element");
    $(".loading_css").addClass("loading");
    setTimeout(function() { 
      $.ajax({
          url:"<?php echo URL::to('admin/route_details')?>",
          dataType:'json',
          data:{id:value, type:2},
          type:"post",
          success: function(result)
          {
            $(".active_deactive_title").html(result['title']);
            $(".id_filed").val(result['id']);
            $(".active_deactive_content").html(result['content']);
            $(".status_filed").val(result['status']);
            $(".status_modal").modal('show');
            $(".loading_css").removeClass("loading");
          }
        })
    },500);
}
if($(e.target).hasClass("edit_icon")){
    var value = $(e.target).attr("data-element");
    $(".loading_css").addClass("loading"); 
    setTimeout(function() {
    var base_url = "<?php echo URL::to('/')?>"+'/';
    $.ajax({
        url:"<?php echo URL::to('admin/route_details')?>",
        dataType:'json',
        data:{id:value, type:3},
        type:"post",
        success: function(result)
        {
          $(".id_filed").val(result['id']);
          $(".select_edit_area_class").val(result['area_name']);
          /*$(".select_edit_area_class").prop("disabled", true);*/
          $(".edit_route_class").val(result['route_name']);
          $(".sales_rep_edit_class").html(result['output_salesrep']);
        	$(".edit_modal").modal("show");
          $(".loading_css").removeClass("loading");
        }
      })
    },500);
}


if($(e.target).hasClass("total_sim")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {
    $("#sim_table").dataTable().fnDestroy();
    var value = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('admin/total_sim_route')?>",
        dataType:'json',
        data:{id:value, type:3},
        type:"post",
        success: function(result)
        {
          $(".output_import").html(result['output']);
          $(".sim_title").html(result['title']);
          $(".sim_modal").modal("show");
          $('#sim_table').DataTable({        
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

if($(e.target).hasClass("active_sim")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {
    $("#sim_table").dataTable().fnDestroy();
    var value = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('admin/active_sim_route')?>",
        dataType:'json',
        data:{id:value, type:3},
        type:"post",
        success: function(result)
        {
          $(".output_import").html(result['output']);
          $(".sim_title").html(result['title']);
          $(".sim_modal").modal("show");
          $('#sim_table').DataTable({        
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

if($(e.target).hasClass("inactive_sim")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {
    $("#sim_table").dataTable().fnDestroy();
    var value = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('admin/inactive_sim_route')?>",
        dataType:'json',
        data:{id:value, type:3},
        type:"post",
        success: function(result)
        {
          $(".output_import").html(result['output']);
          $(".sim_title").html(result['title']);
          $(".sim_modal").modal("show");
          $('#sim_table').DataTable({        
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





if($(e.target).hasClass("add_button_class")){
	$(".add_input").val('');  
  //$('label[.error]').hide();
  $(".area_checkbox").prop("checked", false);
  $(".select_area_class").val('');
  $(".sales_rep_class").hide();
	
}

if($(e.target).hasClass("refresh_icon")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {  
  var value = $(e.target).attr("data-element");
  $.ajax({
      url:"<?php echo URL::to('admin/refresh_route_icon')?>",
      dataType:'json',
      data:{id:value},
      type:"post",
      success: function(result)
      {
        $(".total_"+result['route_id']).html(result['total']);
        $(".active_"+result['route_id']).html(result['active']);
        $(".inactive_"+result['route_id']).html(result['inactive']);
        $(".shop_"+result['route_id']).html(result['total_shop']);
                    
        $(".loading_css").removeClass("loading");
      }
    })
  },500);

}


})


$(window).change(function(e) {
if($(e.target).hasClass("select_area_class")){
  var id = $(e.target).val();
  $.ajax({
    url:"<?php echo URL::to('admin/route_select_rep')?>",
    dataType:'json',
    data:{id:id},
    type:"post",
    success: function(result)
    {
      $(".sales_rep_class").html(result['output_result']);
      $(".sales_rep_class").show();
    }
  })  
}

if($(e.target).hasClass("filter_by_area")){
  var id = $(e.target).val();
  $(".loading_css").addClass("loading");
  setTimeout(function() { 
    $.ajax({
      url:"<?php echo URL::to('admin/filter_route')?>",
      dataType:'json',
      data:{id:id,type:1},
      type:"post",
      success: function(result)
      {
        $(".filter_sales_rep").html(result['output_selesrep']);
        $(".filter_sales_rep").prop("disabled", false);
        $("#output_route").html(result['output_route']);
        if(id == ''){
          $(".filter_sales_rep").prop("disabled", true);
        }
        $('[data-toggle="tooltip"]').tooltip();
        $(".loading_css").removeClass("loading");
      }
    }) 
  },500); 
}

if($(e.target).hasClass("filter_sales_rep")){
  var id = $(e.target).val();
  var area_id = $(".filter_by_area").val();
  $(".loading_css").addClass("loading"); 
  setTimeout(function() {
    $.ajax({
      url:"<?php echo URL::to('admin/filter_route')?>",
      dataType:'json',
      data:{id:id,area_id:area_id,type:2},
      type:"post",
      success: function(result)
      {      
        $("#output_route").html(result['output_route']);
        $('[data-toggle="tooltip"]').tooltip();
        $(".loading_css").removeClass("loading");      
      }
    }) 
  },500); 
}

if($(e.target).hasClass("select_edit_area_class")){
  var id = $(e.target).val();
  var route_id = $(".id_filed").val();
  $.ajax({
    url:"<?php echo URL::to('admin/route_select_rep_edit')?>",
    dataType:'json',
    data:{id:id,route_id:route_id},
    type:"post",
    success: function(result)
    {

      $(".sales_rep_edit_class").html(result['output_result']);
      $(".sales_rep_edit_class").show();
    }
  })  
}









});


</script>

<script type="text/javascript">
$.ajaxSetup({async:false});
$('#add-form').validate({
    rules: {        	
    	select_area:{required: true},
      routename:{required: true},
      "sales_checkbox[]":{required: true},
      "sales_checkbox_empty[]":{required: true},
    },
    messages: {            	
    	select_area:{required:"Please Select Area"},
      routename:{required:"Enter Route"},
      "sales_checkbox[]":{required:"Please choose atleast one Sales REP"},
      "sales_checkbox_empty[]":{required:""},
    },
});
$.ajaxSetup({async:false});
$('#edit-form').validate({
    rules: {    	
      routename:{required: true},
      "sales_checkbox_edit[]":{required: true},        
    },
    messages: {
    	routename:{required:"Enter Route"},
      "sales_checkbox_edit[]":{required:"Please choose atleast one Sales REP"},
    },
});

$.ajaxSetup({async:false});
$(".update_button").click(function(){
   if($("#edit-form").valid()){
    $("#edit-form").submit();
   }

})

$.ajaxSetup({async:false});
$(".add_new_button").click(function(){
   if($("#add-form").valid()){
    $("#add-form").submit();
   }

})

$.ajaxSetup({async:false});
$(".update_button").click(function(){
   if($("#edit-form").valid()){
    $("#edit-form").submit();
   }

})


</script>
@stop
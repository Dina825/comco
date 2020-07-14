@extends('salesheader')
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
            <form id="add-form" method="post" action="<?php echo URL::to('sales/route_add')?>" enctype="multipart/form-data">
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
                          <select class="form-control" required name="select_area">
                            <option value="">Select Area</option>
                            <?php              
                            $arealist = explode(',', $userdetails->area);
                            $output_area='';
                            if(count($arealist)){
                              foreach ($arealist as $area) {
                                $area_details = DB::table('area')->where('area_id', $area)->first();
                                $output_area.='
                                <option value="'.$area_details->area_id.'">'.$area_details->area_name.'</option>';
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
                    
                		<div class="col-lg-12 col-md-12 col-sm-12">
                			<div class="form-group">
		                    	<label>Enter Route Name</label>
		                    	<input type="text" class="form-control add_input" placeholder="Enter Route Name" required name="routename">
		                    </div>
		                </div>

                	</div>
                    
                    


                </div>
                <div class="modal-footer">
                    <input type="hidden" value="<?php echo base64_encode($user_id)?>"  name="sales_rep">
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
            <form id="edit-form" method="post" action="<?php echo URL::to('sales/route_update')?>" enctype="multipart/form-data">
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
                            $arealist = explode(',', $userdetails->area);
                            $output_area='';
                            if(count($arealist)){
                              foreach ($arealist as $area) {
                                $area_details = DB::table('area')->where('area_id', $area)->first();
                                $output_area.='
                                <option value="'.$area_details->area_id.'">'.$area_details->area_name.'</option>';
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
            <form action="<?php echo URL::to('sales/route_status')?>">
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






<div class="col-lg-12 col-md-12 col-sm-12 col-12 sales_right_panel">
	<div class="row">
		<div class="col-lg-12 col-sm-12 col-12">
			<div class="main_title">Manage <span>Route</span></div>
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
    <div class="col-lg-9 col-md-9 col-sm-6 col-6">
      <div class="row">
        <div class="col-lg-2 col-md-6 col-12">
          <div class="form-group">
            <label>Filter By Area</label>
            <select class="form-control filter_area">
              <option value="">Select Area</option>             
              <?php              
              $arealist = explode(',', $userdetails->area);
              $output_area='';
              if(count($arealist)){
                foreach ($arealist as $area) {
                  $area_details = DB::table('area')->where('area_id', $area)->first();
                  $output_area.='
                  <option value="'.base64_encode($area_details->area_id).'">'.$area_details->area_name.'</option>';
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
    </div>
		<div class="col-lg-3">
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
		              <th scope="col" width="100px" style="text-align: center;">Action</th>
		            </tr>
		          </thead>
		          <tbody id="route_tbody">
                  

                  <?php
                  $area_route_list = explode(',', $userdetails->area);                 
                  $output_route='';     
                  $i=1;             
                  
                  if(count($area_route_list)){
                    foreach ($area_route_list as $area_route) {                     

                      $route_list = DB::table('route')->where('area_id', $area_route)->get();
                      
                      if(count($route_list)){
                        foreach ($route_list as $route) {
                          $explode_sales = explode(',', $route->sales_rep_id);                         

                          $area_details = DB::table('area')->where('area_id',$route->area_id)->first();
                          $salesrep_details = DB::table('sales_rep')->where('user_id', $user_id)->first();

                          

                          if($route->status == 0){
                              $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Deactive" style="color: #33CC66"><i class="fas fa-check deactive_class" data-element="'.base64_encode($route->route_id).'"></i></a>';
                          }
                          else{
                              $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Active" style="color: #E11B1C"><i class="fas fa-times active_class" data-element="'.base64_encode($route->route_id).'"></i></a>';
                          }
                          $explode_rep_id = explode(',', $route->sales_rep_id);

                          $total_sim_user = DB::table('sim_allocate')->where('sales_rep_id', $user_id)->where('route_id', $route->route_id)->where('sim', '!=', '')->get();

                          $total_sim='';                        
                          $total_active_sim='';
                          $total_inactive_sim='';
                          if(count($total_sim_user)){
                            foreach ($total_sim_user as $sim_user) {
                              $explode_sim = explode(',', $sim_user->sim);
                              $total_sim = $total_sim+count($explode_sim);
                              
                              if(count($explode_sim)){
                                foreach ($explode_sim as $sim) {

                                  $active_sim = DB::table('sim')->where('id',$sim)->where('activity_date', '!=', '0000-00-00')->where('shop_id', $sim_user->shop_id)->first();

                                  $inactive_sim = DB::table('sim')->where('id',$sim)->where('activity_date', '0000-00-00')->where('shop_id', $sim_user->shop_id)->first();

                                  if(count($active_sim)){
                                    $total_active_sim = $total_active_sim+1 ;
                                  }

                                  if(count($inactive_sim)){
                                    $total_inactive_sim = $total_inactive_sim+1 ;
                                  }

                                }                              
                              }

                            }
                          }

                          $total_shop = DB::table('sim_allocate')->where('sales_rep_id', $user_id)->where('route_id', $route->route_id)->groupBy('shop_id')->get();

                          if(count($total_shop)){
                            $total_shop = count($total_shop);
                          }
                          else{
                            $total_shop = '';
                          }

                          if(in_array($user_id, $explode_rep_id)){                            
                              $output_route.='
                                <tr>
                                  <td>'.$i.'</td>
                                  <td>'.$route->route_name.'</td>
                                  <td>'.$salesrep_details->firstname.'</td>
                                  <td>'.$area_details->area_name.'</td>                          
                                  <td align="center"><a href="javascript:" class="total_sim"  data-element="'.base64_encode($route->route_id).'">'.$total_sim.'</a></td>
                                  <td align="center"><a href="javascript:" class="active_sim"  data-element="'.base64_encode($route->route_id).'">'.$total_active_sim.'</a></td>
                                  <td align="center"><a href="javascript:" class="inactive_sim"  data-element="'.base64_encode($route->route_id).'">'.$total_inactive_sim.'</a></td>                          
                                  <td align="center">'.$total_shop.'</td>
                                  <td align="center">
                                  <a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Area" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($route->route_id).'"></i></a>&nbsp;&nbsp;&nbsp;    
                                  '.$status.'
                                  </td>
                                </tr>
                                ';
                                $i++;                            
                          }

                        }
                      }
                    }
                    if($i==1){
                      $output_route='
                        <tr>
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
                  }
                  else{
                    $output_route='
                    <tr>
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
    $.ajax({
        url:"<?php echo URL::to('sales/route_details')?>",
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
        }
      })
}
if($(e.target).hasClass("deactive_class")){
    var value = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('sales/route_details')?>",
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
        }
      })
}
if($(e.target).hasClass("edit_icon")){
    var value = $(e.target).attr("data-element");
    var base_url = "<?php echo URL::to('/')?>"+'/';
    $.ajax({
        url:"<?php echo URL::to('sales/route_details')?>",
        dataType:'json',
        data:{id:value, type:3},
        type:"post",
        success: function(result)
        {
          $(".id_filed").val(result['id']);
          $(".select_edit_area_class").val(result['area_name']);
          $(".select_edit_area_class").prop("disabled", true);
          $(".edit_route_class").val(result['route_name']);
          //$(".sales_rep_edit_class").html(result['output_salesrep']);
        	$(".edit_modal").modal("show");
        }
      })
}

if($(e.target).hasClass("total_sim")){
  $(".loading_css").addClass("loading");
  $("#sim_table").dataTable().fnDestroy();
  var value = $(e.target).attr("data-element");
  $.ajax({
      url:"<?php echo URL::to('sales/total_sim')?>",
      dataType:'json',
      data:{id:value, type:1},
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

}


if($(e.target).hasClass("active_sim")){
  $(".loading_css").addClass("loading");
  $("#sim_table").dataTable().fnDestroy();
  var value = $(e.target).attr("data-element");
  $.ajax({
      url:"<?php echo URL::to('sales/active_sim')?>",
      dataType:'json',
      data:{id:value, type:1},
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

}

if($(e.target).hasClass("inactive_sim")){
  $(".loading_css").addClass("loading");
  $("#sim_table").dataTable().fnDestroy();
  var value = $(e.target).attr("data-element");
  $.ajax({
      url:"<?php echo URL::to('sales/inactive_sim')?>",
      dataType:'json',
      data:{id:value, type:1},
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

}







if($(e.target).hasClass("add_button_class")){
	$(".add_input").val('');  
  //$('label[.error]').hide();
  $(".area_checkbox").prop("checked", false);
	
}
})


$(window).change(function(e) {
if($(e.target).hasClass("filter_area")){
  var id = $(e.target).val();
  $(".loading_css").addClass("loading");
  setTimeout(function() {
  $.ajax({
    url:"<?php echo URL::to('sales/filter_route')?>",
    dataType:'json',
    data:{id:id},
    type:"post",
    success: function(result)
    {
      $("#route_tbody").html(result['output_result']);
      $(".loading_css").removeClass("loading");      
    }
  })
  },500);

  
}
});


</script>

<script type="text/javascript">
$.ajaxSetup({async:false});
$('#add-form').validate({
    rules: {        	
    	select_area:{required: true},
      routename:{required: true},
      /*"sales_checkbox[]":{required: true},
      "sales_checkbox_empty[]":{required: true},*/
    },
    messages: {            	
    	select_area:{required:"Please Select Area"},
      routename:{required:"Enter Route"},/*
      "sales_checkbox[]":{required:"Please choose atleast one Sales REP"},
      "sales_checkbox_empty[]":{required:""},*/
    },
});
$.ajaxSetup({async:false});
$('#edit-form').validate({
    rules: {    	
      routename:{required: true},
      /*"sales_checkbox_edit[]":{required: true},        */
    },
    messages: {
    	routename:{required:"Enter Route"},
      /*"sales_checkbox_edit[]":{required:"Please choose atleast one Sales REP"},*/
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


</script>
@stop
@extends('adminheader')
@section('content')

<div class="modal fade sim_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="min-width: 75%;">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title active_deactive_title" id="exampleModalLabel">SIM Cards</h5>
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
<!-- Content Header (Page header) -->
	<div class="col-lg-11 col-md-12 col-sm-12 col-12 offset-lg-1 right_panel">
	<div class="row">
		<div class="col-lg-6 col-sm-6 col-6">
			<div class="main_title">Dashboard</div>
		</div>
		<div class="col-lg-6 col-sm-6 col-6">
			<a href="<?php echo URL::to('admin/logout')?>" class="logout_button"><i class="fas fa-sign-in-alt"></i> &nbsp;Logout</a>
		</div>
	</div>
	<div class="row margin_top_20">
		<div class="col-lg-3 col-md-6 col-sm-12">
			<div class="width_100 dashbaord_elements dashboard_back_commmon">
				<div class="title width_100">Areas</div>
				<div class="count width_100">
					<?php
					$area = DB::table('area')->count();
					echo $area;
					?>
				</div>
			</div>
			<div class="width_100 dashboard_a dashboard_back_commmon_a">
					<a href="<?php echo URL::to('admin/area')?>" class="width_100">Go <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-12">
			<div class="width_100 dashbaord_elements dashboard_back_commmon">
				<div class="title width_100">Area Managers</div>
				<div class="count width_100">
					<?php
					$area_manager = DB::table('users')->where('user_type', 1)->count();
					echo $area_manager;
					?>
				</div>
			</div>
			<div class="width_100 dashboard_a dashboard_back_commmon_a">
					<a href="<?php echo URL::to('admin/area_manager')?>" class="width_100">Go <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-12">
			<div class="width_100 dashbaord_elements dashboard_back_commmon">
				<div class="title width_100">Sales REP</div>
				<div class="count width_100">
					<?php
					$sales_rep = DB::table('users')->where('user_type', 2)->count();
					echo $sales_rep;
					?>
				</div>
			</div>
			<div class="width_100 dashboard_a dashboard_back_commmon_a">
					<a href="<?php echo URL::to('admin/sales_rep')?>" class="width_100">Go <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>

		<div class="col-lg-3 col-md-6 col-sm-12">
			<div class="width_100 dashbaord_elements dashboard_back_commmon">
				<div class="title width_100">Route</div>
				<div class="count width_100">
					<?php
					$route = DB::table('route')->count();
					echo $route;
					?>
				</div>
			</div>
			<div class="width_100 dashboard_a dashboard_back_commmon_a">
					<a href="<?php echo URL::to('admin/route')?>" class="width_100">Go <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-12">
			<div class="width_100 dashbaord_elements dashboard_back_commmon">
				<div class="title width_100">Shop</div>
				<div class="count width_100">
					<?php
					$shop = DB::table('shop')->count();
					echo $shop;
					?>
				</div>
			</div>
			<div class="width_100 dashboard_a dashboard_back_commmon_a">
					<a href="<?php echo URL::to('admin/shop')?>" class="width_100">Go <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-12">
			<div class="width_100 dashbaord_elements dashboard_back_commmon">
				<div class="title width_100">Networks</div>
				<div class="count width_100">
					<?php
					$network = DB::table('network')->count();
					echo $network;
					?>
				</div>
			</div>
			<div class="width_100 dashboard_a dashboard_back_commmon_a">
					<a href="<?php echo URL::to('admin/network')?>" class="width_100">Go <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-6 col-md-12 col-sm-12 col-12">
			<div class="row">				
				<div class="col-lg-4 col-md-12 col-sm-12 col-12">
					<h5>Check a SIM</h5>
					<input type="text" class="form-control sim_scan" name="" placeholder="Please Scan OR Enter SSN Number">
					<div class="sim_messaga_result" style="color: #f00; margin-top: 10px;"></div>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-12 col-12 margin_top_20">
					<div class="table-responsive">
						<table class="table table-striped">					
							<tbody id="result_tbody">
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="sub_title2">Sim Cards</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="table-responsive">
		        <table class="table table-striped" id="data_table">
		          <thead class="thead-dark">
		            <tr>
		              <th scope="col">#</th>
		              <th scope="col">Import Date</th>
		              <th scope="col">Total Sim</th>
		              <th scope="col">Active Sim</th>
		              <th scope="col">Inactive Sim</th>
		          	</tr>
			      </thead>
			      <tbody>
			      	<?php
			      	$output_sim='';
			      	$i=1;
			      	if(count($simlist)){
			      		foreach ($simlist as $sim) {
			      			$count = DB::table('sim')->where('import_date', $sim->import_date)->count();
			      			$count_inactive = DB::table('sim')->where('import_date', $sim->import_date)->where('activity_date', '0000-00-00')->count();
			      			$count_active = DB::table('sim')->where('import_date', $sim->import_date)->where('activity_date', '!=','0000-00-00')->count();
			      			$output_sim.='<tr>
					      		<td>'.$i.'</td>
					      		<td><a href="javascript:" class="import_date_class" data-element="'.$sim->import_date.'" />'.date('d-M-Y', strtotime($sim->import_date)).'</a></td>
					      		<td>'.$count.'</td>
					      		<td>'.$count_active.'</td>
					      		<td>'.$count_inactive.'</td>
					      	</tr>';
					      	$i++;
			      		}
			      	}
			      	else{
			      		$output_sim='<tr>
			      		<td></td>
			      		<td></td>
			      		<td align="center">Empty</td>
			      		<td></td>
			      		<td></td>
			      	</tr>';
			      	}
			      	echo $output_sim;
			      	?>
			      </tbody>
			  </table>
			</div>
	</div>
		</div>


		
		
	</div>
</div>              
<!-- /.content -->
<script>
$(window).click(function(e) {
if($(e.target).hasClass("import_date_class")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {
	  var date = $(e.target).attr("data-element");
	  $("#sim_table").dataTable().fnDestroy();
	  $.ajax({
		  url:"<?php echo URL::to('admin/sim_import_details')?>",
		  dataType:'json',
		  data:{date:date},
		  type:"post",
		  success: function(result)
		  {
		  	$(".output_import").html(result['output']);
		    $(".sim_modal").modal('show');
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
})
</script>
<script>
$( ".sim_scan" ).keypress(function( event ) {
  if ( event.which == 13 ) {
    $(".loading_css").addClass("loading");
    var sim_value = $('.sim_scan').val();

    if(sim_value == ''){
    	$(".sim_messaga_result").html('Please scan or Enter SSN Number')
    	$(".loading_css").removeClass("loading");
    }
    else{
    	$(".sim_messaga_result").hide();
    	 $.ajax({
	      url:"<?php echo URL::to('admin/check_sim_scan')?>",
	      type:"post",
	      dataType:"json",
	      data:{value:sim_value},
	      success: function(result) {	        
	        if(result['type'] == 1){
	        	$("#result_tbody").html(result['result']);
	        }
	        else{
	        	$(".sim_messaga_result").show();
	        	$(".sim_messaga_result").html(result['message']);
	        	$("#result_tbody").html('');
	        }
	        $(".sim_scan").val('');

	        $(".loading_css").removeClass("loading");
	        
	      } 
	    })
    }  
  } 
});
</script>
@stop
@extends('areamanagerheader')
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
	<div class="col-lg-12 col-md-12 col-sm-12 col-12 areamanager_right_panel">
	<div class="row">
		<div class="col-lg-6 col-sm-6 col-6">
			<div class="main_title">Dashboard</div>
		</div>
		<div class="col-lg-6 col-sm-6 col-6">
			<a href="<?php echo URL::to('areamanager/logout')?>" class="logout_button"><i class="fas fa-sign-in-alt"></i> &nbsp;Logout</a>
		</div>
	</div>
	<div class="row margin_top_20">

		<div class="col-lg-12 col-md-12 col-sm-12 col-12 sales_dahboard_ul">
			<ul>
				<li>
					<a href="<?php echo URL::to('areamanager/sales_rep')?>">
						<div class="icon"><i class="fas fa-users"></i></div>
						<div class="text">Sales REP</div>
					</a>
				</li>
				<li>
					<a href="<?php echo URL::to('areamanager/route')?>">
						<div class="icon"><i class="fas fa-route"></i></div>
						<div class="text">Route</div>
					</a>
				</li>
				<li>
					<a href="<?php echo URL::to('areamanager/shop')?>">
						<div class="icon"><i class="fas fa-building"></i></div>
						<div class="text">Shop</div>
					</a>
				</li>
				
				<li>
					<a href="<?php echo URL::to('areamanager/check_sim')?>">
						<div class="icon"><i class="fas fa-sim-card"></i></div>
						<div class="text">Check SIM</div>
					</a>
				</li>
				<li>
					<a href="<?php echo URL::to('areamanager/search')?>">
						<div class="icon"><i class="fas fa-search"></i></div>
						<div class="text">Search</div>
					</a>
				</li>

			</ul>
		</div>
		
		
		<div class="col-lg-3 col-md-6 col-sm-12" style="display:none ">
			<div class="width_100 dashbaord_elements dashboard_back_commmon">
				<div class="title width_100">Sales REP</div>
				<div class="count width_100">
					<?php
					$user_details = DB::table('area_manager')->where('user_id', $user_id)->first();
					$explodearea = explode(',', $user_details->area);

					$sales_rep='';
					$empty_array=array();
					if(count($explodearea)){
						foreach ($explodearea as $area) {							
							$sales_count = DB::table('sales_rep')->whereRaw('FIND_IN_SET('.$area.',area)')->get();
							if(count($sales_count))
							{
								foreach($sales_count as $person)
								{
									if(!in_array($person->user_id, $empty_array)){
										$sales_rep = $sales_rep+1;
										array_push($empty_array, $person->user_id);
									}
								}
							}
						}
					}
					
					
					
					echo count($empty_array);
					
					?>
				</div>
			</div>
			<div class="width_100 dashboard_a dashboard_back_commmon_a">
					<a href="<?php echo URL::to('areamanager/sales_rep')?>" class="width_100">Go <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>

		<div class="col-lg-3 col-md-6 col-sm-12" style="display: none;">
			<div class="width_100 dashbaord_elements dashboard_back_commmon">
				<div class="title width_100">Route</div>
				<div class="count width_100">
					<?php
					$route = DB::table('route')->whereIn('area_id',$explodearea)->get();
					echo count($route);
					?>
				</div>
			</div>
			<div class="width_100 dashboard_a dashboard_back_commmon_a">
					<a href="<?php echo URL::to('areamanager/route')?>" class="width_100">Go <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-12" style="display: none;">
			<div class="width_100 dashbaord_elements dashboard_back_commmon">
				<div class="title width_100">Shop</div>
				<div class="count width_100">
					<?php
					$shop = DB::table('shop')->whereIn('area_name', $explodearea)->get();
					echo count($shop);
					?>
				</div>
			</div>
			<div class="width_100 dashboard_a dashboard_back_commmon_a">
					<a href="<?php echo URL::to('areamanager/shop')?>" class="width_100">Go <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		
		<div class="col-lg-6 col-md-12 col-sm-12 col-12" style="display: none;">
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
		
		</div>


		
		
	</div>
</div>              
<!-- /.content -->

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
	      url:"<?php echo URL::to('areamanager/check_sim_scan')?>",
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
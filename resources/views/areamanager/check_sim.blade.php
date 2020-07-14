@extends('areamanagerheader')
@section('content')
<!-- Content Header (Page header) -->
	<div class="col-lg-12 col-md-12 col-sm-12 col-12 areamanager_right_panel">
	<div class="row">
		<div class="col-lg-12 col-sm-12 col-12">
			<div class="main_title">Check SIM</div>
		</div>
	</div>
	<div class="row margin_top_20">
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

		<div class="col-lg-5 col-md-3 col-sm-12 col-12">
			
		</div>
		<div class="col-lg-2 col-md-6 col-sm-12 col-12">
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
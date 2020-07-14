@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->
<style>
.upload_img{
  position: absolute;
    top: 0px;
    z-index: 1;
    background: rgb(226, 226, 226);
    padding: 19% 0%;
    text-align: center;
    overflow: hidden;
}
.upload_text{
  font-size: 15px;
    font-weight: 800;
    color: #631500;
}
.error_message_xls{
	padding: 10px;
    background: #dfdfdf;
}
</style>
<?php
if(isset($_GET['import_type_new']) && $_GET['import_type_new'] == 1)
{
  if(!empty($_GET['round']))
  {
    $filename = $_GET['filename'];
    $height = $_GET['height'];
    $highestrow = $_GET['highestrow'];
    $round = $_GET['round'];
    $duplicated = $_GET['duplicated'];
    $network_dint_match = $_GET['network_dint_match'];
    $productid_dint_match = $_GET['productid_dint_match'];
    $field_empty = $_GET['field_empty'];
    ?>

    <div class="upload_img" style="width: 100%;z-index:1"><p class="upload_text">Uploading Please wait...</p><img src="<?php echo URL::to('assets/loading.gif'); ?>" width="100px" height="100px"><p class="upload_text">Finished Uploading <?php echo $height; ?> of <?php echo $highestrow; ?></p></div>
    <script>
      $(document).ready(function() {
        var base_url = "<?php echo URL::to('/'); ?>";

        window.location.replace(base_url+'/admin/import_sim_one?filename=<?php echo $filename; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&import_type_new=1&duplicated=<?php echo $duplicated; ?>&network_dint_match=<?php echo $network_dint_match; ?>&productid_dint_match=<?php echo $productid_dint_match; ?>&field_empty=<?php echo $field_empty; ?>');

      })

    </script>

    <?php



  }

}

if(isset($_GET['import_type_new']) && $_GET['import_type_new'] == 2)
{
  if(!empty($_GET['round']))
  {
    $filename = $_GET['filename'];
    $height = $_GET['height'];
    $highestrow = $_GET['highestrow'];
    $round = $_GET['round'];
    $duplicated = $_GET['duplicated'];
    $network_dint_match = $_GET['network_dint_match'];
    $productid_dint_match = $_GET['productid_dint_match'];
    $field_empty = $_GET['field_empty'];
    $activated = $_GET['activated'];
    $no_ssn = $_GET['no_ssn'];
    ?>

    <div class="upload_img" style="width: 100%;z-index:1"><p class="upload_text">Uploading Please wait...</p><img src="<?php echo URL::to('assets/loading.gif'); ?>" width="100px" height="100px"><p class="upload_text">Finished Uploading <?php echo $height; ?> of <?php echo $highestrow; ?></p></div>
    <script>
      $(document).ready(function() {
        var base_url = "<?php echo URL::to('/'); ?>";

        window.location.replace(base_url+'/admin/import_activation_sim_one?filename=<?php echo $filename; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&import_type_new=2&duplicated=<?php echo $duplicated; ?>&network_dint_match=<?php echo $network_dint_match; ?>&productid_dint_match=<?php echo $productid_dint_match; ?>&field_empty=<?php echo $field_empty; ?>&activated=<?php echo $activated; ?>&no_ssn=<?php echo $no_ssn; ?>');

      })

    </script>

    <?php



  }

}

?>

<div class="modal fade" id="error_from_xls" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Imported List.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    if(Session::has('success_error')){
                    	$entries = Session::get('success_error');
                    	$duplicated = $entries['duplicated'];
                    	$network_dint_match = $entries['network_dint_match'];
                    	$productid_dint_match = $entries['productid_dint_match'];
                    	$field_empty = $entries['field_empty'];
                        $activated = $entries['activated'];

                    	if($duplicated != "0")
                    	{
                    		echo '<label class="error_message_xls"><strong>'.$duplicated.'</strong> Number of Simcard(s) are not uploaded. Because ssn number already exists. </label><hr>';
                    	}
                    	if($network_dint_match != "0")
                    	{
                    		echo '<label class="error_message_xls"><strong>'.$network_dint_match.'</strong> Number of Simcard(s) are not uploaded. Because Network Id is not matched with our database. </label><hr>';
                    	}
                    	if($productid_dint_match != "0")
                    	{
                    		echo '<label class="error_message_xls"><strong>'.$productid_dint_match.'</strong> Number of Simcard(s) are not uploaded. Because Product Id is not matched with our database. </label><hr>';
                    	}
                    	if($field_empty != "0")
                    	{
                    		echo '<label class="error_message_xls"><strong>'.$field_empty.'</strong> Number of Simcard(s) are not uploaded. Some Fields are Empty. </label>';
                    	}
                        if($activated != "0")
                        {
                            echo '<label class="error_message_xls"><strong>'.$activated.'</strong> Number of Simcard(s) are Acivated Successfully. </label>';
                        }
                    }
                    if(Session::has('success_error_one')){
                        $entries = Session::get('success_error_one');
                        $duplicated = $entries['duplicated'];
                        $network_dint_match = $entries['network_dint_match'];
                        $productid_dint_match = $entries['productid_dint_match'];
                        $field_empty = $entries['field_empty'];
                        $activated = $entries['activated'];
                        $no_ssn = $entries['no_ssn'];
                        if($duplicated != "0")
                        {
                            echo '<label class="error_message_xls"><strong>'.$duplicated.'</strong> Number of Simcard(s) are not uploaded. Because ssn number already activated. </label><hr>';
                        }
                        if($network_dint_match != "0")
                        {
                            echo '<label class="error_message_xls"><strong>'.$network_dint_match.'</strong> Number of Simcard(s) are not uploaded. Because Network Id is not matched with our database. </label><hr>';
                        }
                        if($productid_dint_match != "0")
                        {
                            echo '<label class="error_message_xls"><strong>'.$productid_dint_match.'</strong> Number of Simcard(s) are not uploaded. Because Product Id is not matched with our database. </label><hr>';
                        }
                        if($field_empty != "0")
                        {
                            echo '<label class="error_message_xls"><strong>'.$field_empty.'</strong> Number of Simcard(s) are not uploaded. Some Fields are Empty. </label>';
                        }
                        if($activated != "0")
                        {
                            echo '<label class="error_message_xls"><strong>'.$activated.'</strong> Number of Simcard(s) are Acivated Successfully. </label>';
                        }
                        if($no_ssn != "0")
                        {
                            echo '<label class="error_message_xls"><strong>'.$no_ssn.'</strong> Number of Simcard(s) are not uploaded. Because SSN is not matched with our database.  </label>';
                        }
                    }
                    ?>
                </div>
                <div class="modal-footer">                    
                    <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade add_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="<?php echo URL::to('admin/import_sim'); ?>" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title active_deactive_title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                    	<label>Choose Excel CSV</label>
                    	<input type="file" class="form-control add_input" style="height: 46px; padding: 7px" placeholder="Enter Network Id" required name="import_file">
                    </div>

                </div>
                <div class="modal-footer">                    
                    <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary model_add_button">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade add_activation_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="<?php echo URL::to('admin/import_activation_sim'); ?>" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title active_deactive_title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                    	<label>Choose Excel CSV</label>
                    	<input type="file" class="form-control add_input" style="height: 46px; padding: 7px" placeholder="Enter Network Id" required name="activation_file">
                    </div>

                </div>
                <div class="modal-footer">                    
                    <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary model_add_button">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

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



	<div class="col-lg-11 col-md-12 col-sm-12 col-12 offset-lg-1 right_panel">

	<div class="row">
		<div class="col-lg-6 col-sm-6 col-6">
			<div class="main_title">Manage <span>Simcards</span></div>
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
			<a href="#" class="common_button float_right import_sim" data-toggle="modal" data-target=".add_modal">Import SIM</a>
			<a href="#" class="common_button float_right import_activation" style="margin-right: 5px;">Import Activation</a>
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

<script type="text/javascript">
<?php
if(Session::has('success_error')){
	?>
	$(document).ready(function() {
		$("#error_from_xls").modal("show");
	});
	<?php
}
?>
<?php
if(Session::has('success_error_one')){
    ?>
    $(document).ready(function() {
        $("#error_from_xls").modal("show");
    });
    <?php
}
?>
$(".import_sim").click(function(){		
	$(".model_add_button").html('Import');
	$(".active_deactive_title").html('Import SIM Cards');
	$(".add_modal").modal();	
})
$(".import_activation").click(function(){		
	$(".model_add_button").html('Import');
	$(".active_deactive_title").html('Import Activation SIM');
	$(".add_activation_modal").modal();	
})

$(window).click(function(e) {
if($(e.target).hasClass("import_date_class")){
   $(".loading_css").addClass("loading");
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
}
})
</script>

@stop
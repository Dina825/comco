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
    $comm_date = $_GET['comm_date'];
    $import_date = $_GET['import_date'];

    $duplicated = $_GET['duplicated'];
    $network_dint_match = $_GET['network_dint_match'];
    $field_empty = $_GET['field_empty'];
    $topup_zero = $_GET['topup_zero'];
    $ssn_dint_match = $_GET['ssn_dint_match'];
    $inserted = $_GET['inserted'];
    $ignored = $_GET['ignored'];
    $not_ignored = $_GET['not_ignored'];
    ?>

    <div class="upload_img" style="width: 100%;z-index:1"><p class="upload_text">Uploading Please wait...</p><img src="<?php echo URL::to('assets/loading.gif'); ?>" width="100px" height="100px"><p class="upload_text">Finished Uploading <?php echo $height; ?> of <?php echo $highestrow; ?></p></div>
    <script>
      $(document).ready(function() {
        var base_url = "<?php echo URL::to('/'); ?>";

        window.location.replace(base_url+'/admin/upload_commission_one?filename=<?php echo $filename; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&import_type_new=1&comm_date=<?php echo $comm_date; ?>&import_date=<?php echo $import_date; ?>&duplicated=<?php echo $duplicated; ?>&network_dint_match=<?php echo $network_dint_match; ?>&field_empty=<?php echo $field_empty; ?>&topup_zero=<?php echo $topup_zero; ?>&ssn_dint_match=<?php echo $ssn_dint_match; ?>&inserted=<?php echo $inserted; ?>&ignored=<?php echo $ignored; ?>&not_ignored=<?php echo $not_ignored; ?>');

      })

    </script>

    <?php



  }

}
?>

<div class="modal fade add_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 70%;">
        <div class="modal-content">
           <form method="post" action="<?php echo URL::to('admin/commission_add')?>">
                <div class="modal-header">
                    <h5 class="modal-title active_deactive_title" id="exampleModalLabel">Add New Commission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <label>Enter Plan Name</label>
                                <input type="type" class="form-control add_input" placeholder="Enter Plan Name" required name="plan_name">
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <label>Choose connection level</label>
                                <select class="form-control" name="connection_level" required>
                                    
                                    <?php
                                    $output_connect='<option value="">Select Connections Level</option>';
                                    if(count($connectionlist)){
                                      foreach ($connectionlist as $connection) {
                                        $output_connect.='
                                        <option value="'.$connection->connection_id.'">'.$connection->level.'</option>
                                        ';
                                      }
                                    }
                                    else{
                                      $output_connect='<option value="">Connections Level Empty</option>';
                                    }
                                    echo $output_connect;
                                    ?>
                                </select>
                            </div>
                        </div>
                      
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="table-responsive">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>Network</th>
                                <th>1st</th>
                                <th>Bonus</th>
                                <th>2nd</th>
                                <th>3rd</th>
                                <th>4th</th>
                                <th>5th</th>
                                <th>6th</th>
                                <th>7th</th>
                                <th>8th</th>
                                <th>9th</th>
                                <th>10th</th>
                              </tr>
                            </thead>
                            <tbody>
                            <?php
                            $output_network='';
                            if(count($networklist)){
                              foreach ($networklist as $network) {
                                $output_network.='
                                <tr>
                                  <td>'.$network->network_name.'
                                  <input type="hidden" value="'.$network->network_name.'" name="network[]" />
                                  </td>
                                  <td><input type="text" class="plan_input" placeholder="1st" required name="first_'.$network->network_name.'"></td>
                                  <td><input type="text" class="plan_input" placeholder="Bonus" required name="bonus_'.$network->network_name.'" ></td>
                                  <td><input type="text" class="plan_input" placeholder="2nd" required name="second_'.$network->network_name.'" ></td>
                                  <td><input type="text" class="plan_input" placeholder="3rd" required name="third_'.$network->network_name.'" ></td>
                                  <td><input type="text" class="plan_input" placeholder="4th" required name="fourth_'.$network->network_name.'" ></td>
                                  <td><input type="text" class="plan_input" placeholder="5th" required name="fifth_'.$network->network_name.'" ></td>
                                  <td><input type="text" class="plan_input" placeholder="6th" required name="sixth_'.$network->network_name.'" ></td>
                                  <td><input type="text" class="plan_input" placeholder="7th" required name="seventh_'.$network->network_name.'" ></td>
                                  <td><input type="text" class="plan_input" placeholder="8th" required name="eighth_'.$network->network_name.'" ></td>
                                  <td><input type="text" class="plan_input" placeholder="9th" required name="ninth_'.$network->network_name.'" ></td>
                                  <td><input type="text" class="plan_input" placeholder="10th" required name="tenth_'.$network->network_name.'" ></td>
                                </tr>';
                              }
                            }
                            else{
                             $output_network='<tr><td colspan="13" align="center">Empty</td></tr>';
                            }
                            echo $output_network;
                            ?>
                            </tbody>
                          </table>
                        </div>
                      </div>                      
                    </div>
                </div>
                <div class="modal-footer">                    
                    <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary model_add_button">Add New</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade edit_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 70%;">
        <div class="modal-content">
           <form method="post" action="<?php echo URL::to('admin/commission_update')?>">
                <div class="modal-header">
                    <h5 class="modal-title active_deactive_title" id="exampleModalLabel">Edit Commission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <label>Enter Plan Name</label>
                                <input type="type" class="form-control class_name" placeholder="Enter Plan Name" required name="plan_name">
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <label>Choose connection level</label>
                                <select class="form-control level_class" name="connection_level" required>
                                    
                                </select>
                            </div>
                        </div>
                      
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="table-responsive">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                  <th>Network</th>
                                  <th>1st</th>
                                  <th>Bonus</th>
                                  <th>2nd</th>
                                  <th>3rd</th>
                                  <th>4th</th>
                                  <th>5th</th>
                                  <th>6th</th>
                                  <th>7th</th>
                                  <th>8th</th>
                                  <th>9th</th>
                                  <th>10th</th>
                              </tr>
                            </thead>
                            <tbody id="table_result">
                              
                            </tbody>                            
                          </table>
                        </div>
                      </div>                      
                    </div>
                </div>
                <div class="modal-footer">                    
                    <input type="hidden" class="class_id" name="id">
                    <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary model_add_button">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade upload_connection" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="post" action="<?php echo URL::to('admin/upload_commission')?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title active_deactive_title" id="exampleModalLabel">Upload Connection</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-12"> 
                            <div class="form-group">                                 
                                <label>Choose Date</label>
                                <input type="text" class="form-control add_input commission_date" placeholder="Choose Date" required name="commission_date">
                            </div>
                            <div class="form-group">                            
                                <label>Select File</label>
                                <input type="file" class="form-control add_input commission_file" style="height: 46px; padding: 7px" required name="commission_file" accept=".xlsx, .xls, .csv">
                            </div>
                            </div>
                        </div>
                        
                    
                </div>
                <div class="modal-footer">                    
                    <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
                    <input type="submit" name="upload_commission" class="btn btn-primary model_add_button" value="Upload">      
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade status_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="<?php echo URL::to('admin/commission_status')?>">
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
			<div class="main_title">Manage <span>Commission</span></div>
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
		<div class="col-lg-10 col-md-10 col-sm-6 col-6">
			<a href="#" class="common_button float_right add_button_class" data-toggle="modal" data-target=".add_model">Add New Commission</a>
      <a href="#" style="margin-right: 10px;" class="common_button float_right" data-toggle="modal" data-target=".upload_connection">Upload Commission File</a>
		</div>
		<div class="col-lg-12">
			<div class="table-responsive">
		        <table class="table table-striped" id="data_table">
		          <thead class="thead-dark">
		            <tr>
		              <th scope="col" style="width: 100px;">#</th>
		              <th scope="col">Commission</th>		              
		              <th scope="col" width="100px" style="text-align: center;">Action</th>
		            </tr>
		          </thead>
		          <tbody>

		          	<?php
		          	$output='';
		          	$i=1;
		          	if(count($commission_list)){
		          		foreach ($commission_list as $commission) {
		          			if($commission->status == 0){
		          				$status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Deactive" style="color: #33CC66"><i class="fas fa-check deactive_class" data-element="'.base64_encode($commission->commission_id).'"></i></a>';
		          			}
		          			else{
		          				$status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Active" style="color: #E11B1C"><i class="fas fa-times active_class" data-element="'.base64_encode($commission->commission_id).'"></i></a>';
		          			}
		          			$output.='
		          			<tr>
				          		<td>'.$i.'</td>
				          		<td>'.$commission->plan_name.'</td>				          		
				          		<td align="center">
				          		<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Commission" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($commission->commission_id).'"></i></a>&nbsp;&nbsp;&nbsp;				          		
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
    <?php
    $shop_commission = DB::table('shop_commission')->where('proceeded',0)->groupBy('commission_date')->get();
    if(count($shop_commission))
    { ?>
    <h5 style="margin-top:20px;margin-left:20px">Pending Commission</h5>
    <div class="col-lg-12">
      <div class="table-responsive">
            <table class="table table-striped" id="data_table">
              <thead class="thead-dark">
                <tr>
                  <th scope="col" style="width: 100px;">#</th>
                  <th scope="col">Date</th>
                  <th scope="col">No of Shops Pending</th>
                  <th scope="col">No of Shops Completed</th>
                  <th scope="col" width="100px" style="text-align: center;">Action</th>
                </tr>
              </thead>
              <tbody>
                  <?php
                  $i = 1;
                  foreach($shop_commission as $shop)
                  {
                    $shop_counts = DB::table('shop_commission')->where('proceeded',0)->where('commission_date',$shop->commission_date)->groupBy('shop_id')->get();
                    $shop_completed = DB::table('shop_commission')->where('proceeded',1)->where('commission_date',$shop->commission_date)->groupBy('shop_id')->get();
                    ?>
                    <tr>
                      <th scope="col" style="width: 100px;"><?php echo $i; ?></th>
                      <th scope="col"><?php echo date('d-F-Y H:i:s', strtotime($shop->commission_date)); ?></th>
                      <th scope="col"><?php echo count($shop_counts); ?></th>
                      <th scope="col"><a href="<?php echo URL::to('admin/shops_commission_completed?comm_date='.$shop->commission_date); ?>" title="Shop Commission list that are completed"><?php echo count($shop_completed); ?></a></th>
                      <th scope="col" style="text-align: center;">
                        <a href="<?php echo URL::to('admin/upload_commission_page?comm_date='.$shop->commission_date); ?>" class="fa fa-arrow-right"></a>&nbsp;&nbsp;&nbsp;
                        <a href="<?php echo URL::to('admin/delete_commission_page?comm_date='.$shop->commission_date); ?>" class="fa fa-trash delete_commission" data-element="<?php echo date('d-M-Y H:i:s',strtotime($shop->commission_date)); ?>" title="Delete Commission"></a>
                      </th>
                    </tr>
                    <?php
                    $i++;
                  }
                  ?>
              </tbody>
            </table>
      </div>
    </div>
    <?php } ?>
	</div>
		</div>
	</div>
</div>                
<!-- /.content -->
<script type="text/javascript">

$(function () {
  $(".commission_date").datetimepicker({
     format: 'L',
     format: 'YYYY-MM-DD',
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
$(window).click(function(e) {
if($(e.target).hasClass('delete_commission'))
{
  e.preventDefault();
  var dateval = $(e.target).attr("data-element");
  var hrefval = $(e.target).attr("href");
  var r = confirm("Are you sure you want to delete this commission for date '"+dateval+"'.");
  if(r)
  {
    window.location.replace(hrefval);
  }
}
if($(e.target).hasClass("active_class")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {
    var value = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('admin/commission_details')?>",
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
        url:"<?php echo URL::to('admin/commission_details')?>",
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
  //$(".loading_css").addClass("loading");
  setTimeout(function() {
    var value = $(e.target).attr("data-element");  
    $.ajax({
        url:"<?php echo URL::to('admin/commission_details')?>",
        dataType:'json',
        data:{id:value, type:3},
        type:"post",
        success: function(result)
        {
        	$(".class_id").val(result['id']);
        	$(".class_name").val(result['name']);
          $(".level_class").html(result['connection_level']);
          $("#table_result").html(result['output_table']);
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

@stop
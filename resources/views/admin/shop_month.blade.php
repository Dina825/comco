@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->
<style type="text/css">
.search_icon{top: 24px;}
.exporting_div{
    display:none;
    text-align: center;
    position: fixed;
    top: 52%;
    width: 100%;
    font-size: 30px;
    line-height: 50px;
    font-weight: 800;
    z-index: 999999999;
}
</style>

<div class="modal fade report_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="post" action="<?php echo URL::to('admin/report_excel')?>" enctype="multipart/form-data" id="report_form">
                <div class="modal-header">
                    <h5 class="modal-title active_deactive_title" id="exampleModalLabel">Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-12"> 
                            <div class="form-group">                                 
                                <label>From Date</label>
                                <input type="text" class="form-control add_input from_date" placeholder="Choose Date" required name="from_date">
                            </div>
                            <div class="form-group">                            
                                <label>To Date</label>
                                <input type="text" class="form-control add_input to_date" placeholder="Choose Date" required name="to_date">
                            </div>
                            </div>
                        </div>
                        
                    
                </div>
                <div class="modal-footer">                    
                    <button type="button" class="btn btn-secondary cancel_button" data-dismiss="modal">Cancel</button>
                    <input type="button" name="upload_commission" id="upload_commission" class="btn btn-primary model_add_button" value="Export">      
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade last_3month_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title 3moth_sim_title" id="exampleModalLabel">Last 3 Month Active List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="table-responsive">
                    <table class="own_table" id="output_last_3month"></table>
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
			<div class="main_title">Shop <span>Month on Month</span></div>
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
        <div class="col-lg-2 col-md-2 col-2">
          <div class="form-group">
            <label>Filter By Sales REP</label>
            <select class="form-control filter_salesreponly">
              <option value="">Select Sales REP</option>

              <?php
              $salesreplist = DB::table('sales_rep')->get();

              $output_salesrep='';
              if(count($salesreplist)){
                foreach ($salesreplist as $rep) {
                  $output_salesrep.='<option value="'.$rep->user_id.'">'.$rep->firstname.' '.$rep->surname.'</option>';
                }
              }
              else{
                $output_salesrep='<div class="col-lg-12">Empty</div>';
              }
              echo $output_salesrep;
              ?>
            </select>
          </div>
        </div>
        <div class="col-lg-10">
          <a href="#" class="common_button float_right add_button_class" data-toggle="modal" data-target=".report_model">Report</a>
        </div>
<style type="text/css">
.heading_row{line-height: 20px; padding: 10px 0px; background: #2b2b2b; color: #fff; font-size: 14px; font-weight: 500; text-align: center}
.row_text{line-height: 20px; padding: 10px 0px; font-size: 14px; font-weight: 500; text-align: left; border-bottom: 1px solid #2b2b2b}
</style>
		<div class="col-lg-12" style="margin-top: 15px;">
      

			<div class="row" style="padding: 0px 15px;">
		        
		              <div class="col-lg-1 col-md-1 col-sm-1 col-1 heading_row">#</div>
                  <div class="col-lg-4 col-md-4 col-sm-4 col-4 heading_row">Shop Name</div>
                  <div class="col-lg-1 col-md-1 col-sm-1 col-1 heading_row">Account Number</div>
                  <div class="col-lg-3 col-md-3 col-sm-3 col-2 heading_row">Sales REP</div>
                  <?php
                  for ($j = 0; $j <= 2; $j++) {
                      $last_three_month[] =  date("M-Y-m", strtotime(" -$j month"));                    
                  }
                  krsort($last_three_month);
                  $output_month_three_heading ='';                  
                    if(count($last_three_month)){
                      foreach ($last_three_month as $three_month) {
                        $explode_three_month_year = explode('-', $three_month);
                        $output_month_three_heading.='
                        <div class="col-lg-1 col-md-1 col-sm-1 col-1 heading_row">'.$explode_three_month_year[0].'</div>
                        ';
                      }                      
                    }
                    echo $output_month_three_heading;
                  ?>
                  
		    </div>  

		      <div class="filter_sales_row">    
                  <?php
                  $output_shop='';
                  $i=1;
                   
                  for ($am = 0; $am <= 2; $am++) {
                    $last_three_month_active[] =  date("M-Y-m", strtotime(" -$am month"));                    
                  }
                  krsort($last_three_month_active);

                  $month_three_array = array();
                  $month_total_array = array();

                  if(count($shoplist)){
                    foreach ($shoplist as $shop) {
                      $output_month_three='';
                     
                      if(count($last_three_month_active)){
                        foreach ($last_three_month_active as $three_month_active) {

                          $explode_three_month_year = explode('-', $three_month_active);

                          $sim_count_three_details = DB::table('sim')->select('shop_id', 'activity_date')->where('shop_id', $shop->shop_id)->where('activity_date', '!=','0000-00-00')->get();
                          $month_three_year = $explode_three_month_year[1].'-'.$explode_three_month_year[2];
                          array_push($month_three_array,$month_three_year);                    
                          $count_three_month=0;
                          if(count($sim_count_three_details)){
                            foreach ($sim_count_three_details as $sim_count_three) {
                              $activity_date = substr($sim_count_three->activity_date,0,7);

                              if($month_three_year == $activity_date){
                                $count_three_month = $count_three_month+1;                          
                              }
                            }
                          }
                          array_push($month_total_array,$count_three_month);

                          $output_month_three.= '<div class="col-lg-1 col-md-1 col-sm-1 col-1 row_text"><a href="javascript:" class="last_3month" data-element="'.base64_encode($shop->shop_id).'">'.$explode_three_month_year[0].'-'.$count_three_month.'</a></div>';
                        }          
                      }

                      $route_details = DB::table('route')->where('route_id', $shop->route)->first();

                      if(count($route_details)){
                        $route = $route_details->route_name;

                        $explode_route = explode(',', $route_details->sales_rep_id);
                        $salesrep='';
                        if(count($explode_route)){
                          foreach ($explode_route as $single_route) {
                            $sales_rep = DB::table('sales_rep')->where('user_id', $single_route)->first();

                            if(count($sales_rep)){
                              if($salesrep == ''){
                                  $salesrep = $sales_rep->firstname;
                              }
                              else{
                                  $salesrep  = $sales_rep->firstname.', '.$salesrep;
                              }
                            }
                            else{
                              $salesrep = '';
                            }

                            
                          }
                        }
                        else{
                          $salesrep = '';
                        }
                      }
                      else{
                        $route='';
                        $salesrep ='';
                      }
                      
                      $output_shop.='
                      <div class="row" style="padding: 0px 20px;">
                        <div class="col-lg-1 col-md-1 col-sm-1 col-1 row_text">'.$i.'</div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-4 row_text"><a href="'.URL::to('admin/shop_view_details'.'/'.base64_encode($shop->shop_id)).'">'.$shop->shop_name.'</a></div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-1 row_text">CC-'.$shop->shop_id.'</div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-3 row_text">'.$salesrep.'</div>
                        '.$output_month_three.'
                      </div>';
                      $last_i = $i;
                      $i++;
                    }                    
                    
                    $output_shop.='<div  class="last_tr_'.$last_i.'"><div>';
                  }
                  else{
                    $last_i='';
                    $output_shop='
                    <div class="col-lg-12 text-center">Empty</div>
                    ';
                  } 
                                   
                  echo $output_shop;                  
                  ?>
                  
		          </div>
		        
		    
		</div>
    
	</div>
  <input type="hidden" id="last_i" value="<?php echo $last_i;?>" name="">
  
  
		</div>

	</div>

</div>
<?php $shops_count = DB::table('shop')->count(); ?>
<div class="exporting_div">Please Wait... <br/>Exporting <spam id="export_count"></spam> of <?php echo $shops_count; ?></div>
<!-- /.content -->
<script>
$(window).on('scroll', function() { 
    if ($(window).scrollTop() >= $('.right_panel').offset().top + $('.right_panel'). 
        outerHeight() - window.innerHeight) { 
        var value = $("#last_i").val(); 
        var salesrep = $(".filter_salesreponly").val();
        if(value != ''){
          $(".loading_css").addClass("loading");
          setTimeout(function() {           
            $.ajax({
                url:"<?php echo URL::to('admin/month_on_month_scroll')?>",
                dataType:'json',
                data:{value:value,user_id:salesrep},
                type:"post",
                success: function(result)
                {
                  $("#last_i").val(result['last_i']);
                  $(".last_tr_"+value).html(result['output']); 
                  $(".loading_css").removeClass("loading");
                  
                }
              })
            },500);
        }        
    } 
});
function detectPopupBlocker() {
  var myTest = window.open("about:blank","","directories=no,height=100,width=100,menubar=no,resizable=no,scrollbars=no,status=no,titlebar=no,top=0,location=no");
  if (!myTest) {
    return 1;
  } else {
    myTest.close();
    return 0;
  }
}
function SaveToDisk(fileURL, fileName) {
  
	var idval = detectPopupBlocker();
	if(idval == 1)
	{
		alert("A popup blocker was detected. Please Allow the popups to download the file.");
	}
	else{
		// for non-IE
		if (!window.ActiveXObject) {
		  var save = document.createElement('a');
		  save.href = fileURL;
		  save.target = '_blank';
		  save.download = fileName || 'unknown';
		  var evt = new MouseEvent('click', {
		    'view': window,
		    'bubbles': true,
		    'cancelable': false
		  });
		  save.dispatchEvent(evt);
		  (window.URL || window.webkitURL).revokeObjectURL(save.href);
		}
		// for IE < 11
		else if ( !! window.ActiveXObject && document.execCommand)     {
		  var _window = window.open(fileURL, '_blank');
		  _window.document.close();
		  _window.document.execCommand('SaveAs', true, fileName || fileURL)
		  _window.close();
		}
	}
	$(".loading_css").removeClass("loading");
}
function report_month(page,from,to,index)
{
  $.ajax({
    url:"<?php echo URL::to('admin/report_shop_month_on_month'); ?>",
    type:"post",
    dataType:"json",
    data:{from_date:from,to_date:to,page:page,index:index},
    success:function(result)
    {
      if(result['page'] == "")
      {
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result['filename'],result['filename']);
        $(".loading_css").removeClass("loading");
        $(".exporting_div").hide();
        $("#export_count").html("0");
      }
      else{
        var uploaded = parseInt(result['index']) - 1;
        $("#export_count").html(uploaded);
        report_month(result['page'],from,to,result['index']);
      }
    }
  })
}
$(window).click(function(e) {
if(e.target.id == "upload_commission")
{
	if($("#report_form").valid())
	{
		$(".loading_css").addClass("loading");
    $(".exporting_div").show();
		var from_date = $(".from_date").val();
		var to_date = $(".to_date").val();
		$.ajax({
			url:"<?php echo URL::to('admin/report_shop_month_on_month'); ?>",
			type:"post",
      dataType:"json",
			data:{from_date:from_date,to_date:to_date,page:"0",index:"0"},
			success:function(result)
			{
        $(".report_model").modal("hide");
        if(result['page'] == "")
        {
          SaveToDisk("<?php echo URL::to('papers'); ?>/"+result['filename'],result['filename']);
          $(".loading_css").removeClass("loading");
          $(".exporting_div").hide();
          $("#export_count").html("0");
        }
				else{
          var uploaded = parseInt(result['index']) - 1;
          $("#export_count").html(uploaded);
          report_month(result['page'],from_date,to_date,result['index']);
        }
			}
		})
	}
}
if($(e.target).hasClass("last_3month")){
  $(".loading_css").addClass("loading");
  setTimeout(function() {
    $("#sim_table").dataTable().fnDestroy();
    var value = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('admin/shop_last_3moth')?>",
        dataType:'json',
        data:{id:value, type:4},
        type:"post",
        success: function(result)
        {
          $("#output_last_3month").html(result['output']);          
          $(".last_3month_modal").modal("show");
          $(".3moth_sim_title").html(result['title']);
          $(".loading_css").removeClass("loading");
        }
      })
  },500);
}
})

$(window).change(function(e) {

  if($(e.target).hasClass("filter_salesreponly")){
  var id = $(e.target).val();
  $(".loading_css").addClass("loading"); 
  setTimeout(function() {
    $("#data_table").dataTable().fnDestroy(); 
    $.ajax({
      url:"<?php echo URL::to('admin/filter_for_shop_month')?>",
      dataType:'json',
      data:{id:id, type:4},
      type:"post",
      success: function(result)
      {
        $("#last_i").val(result['last_i']);
        $(".filter_sales_row").html(result['output']);        
        $(".loading_css").removeClass("loading");  
      }
    })  
  },500);
}

})


$(function () {
  $(".from_date").datetimepicker({
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
  $(".to_date").datetimepicker({
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

  $(".from_date").on("dp.change", function (e) {
  		$(".to_date").val("");
        $('.to_date').data("DateTimePicker").minDate(e.date);
    });
});
</script>
@stop
@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->
<style type="text/css">
.search_icon{top: 24px;}
</style>


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
    
		<div class="col-lg-12">
			<div class="table-responsive">
		        <table class="table table-striped">
		          <thead class="thead-dark">
		            <tr>
		              <th scope="col">#</th>
                  <th scope="col">Shop Name</th>
                  <th scope="col">Account Number</th>
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
                        <th style="text-align: center;">'.$explode_three_month_year[0].'</th>
                        ';
                      }                      
                    }
                    echo $output_month_three_heading;
                  ?>
                  
		            </tr>
		          </thead>
		         
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

                          $output_month_three.= '<td><a href="javascript:" class="last_3month" data-element="'.base64_encode($shop->shop_id).'">'.$explode_three_month_year[0].'-'.$count_three_month.'</a></td>';
                        }          
                      }
                      
                      $output_shop.='
                      <tr>
                        <td>'.$i.'</td>
                        <td><a href="'.URL::to('admin/shop_view_details'.'/'.base64_encode($shop->shop_id)).'">'.$shop->shop_name.'</a></td>
                        <td>CC-'.$shop->shop_id.'</td>
                        '.$output_month_three.'
                      </tr>';
                      $i++;
                    }                    
                    $last_i = $i;
                    $output_shop.='<tr  class="last_tr_'.$last_i.'"><tr>';
                  }
                  else{
                    $output_shop='
                    <tr>
                      <td></td>
                      <td></td>
                      <td align="right">Empty</td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    ';
                  } 
                                   
                  echo $output_shop;                  
                  ?>
                  <input type="text" id="last_i" value="<?php echo $last_i;?>" name="">
		          
		        </table>
		    </div>
		</div>
	</div>
		</div>
	</div>
</div>                
<!-- /.content -->
<script>
$(window).scroll(function () {
  if ($(window).scrollTop() == $(document).height() - $(window).height() ) {
      $(".loading_css").addClass("loading");

      setTimeout(function() {

        var value = $("#last_i").val();  
        $.ajax({
            url:"<?php echo URL::to('admin/month_on_month_scroll')?>",
            dataType:'json',
            data:{value:value},
            type:"post",
            success: function(result)
            {
              $("#last_i").val(result['last_i']);
              $(".last_tr_"+value).html(result['output']); 
              /*$(".last_tr_"+value).removeClass();*/

              $(".loading_css").removeClass("loading");
              
            }
          })
        },500);
  }
});



$(window).click(function(e) {
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
</script>
@stop
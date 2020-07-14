@extends('salesheader')
@section('content')
<!-- Content Header (Page header) -->
	<div class="col-lg-12 col-md-12 col-sm-12 col-12 sales_right_panel">
	<div class="row">
		<div class="col-lg-12 col-sm-12 col-12">
			<div class="main_title">Time <span>Sheet</span></div>
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

		
		<div class="col-lg-12 col-md-12 col-sm-12 col-12">
				<?php
/* Set the default timezone */


/* Set the date */
$date = strtotime($date_current);

$day = date('d', $date);
$month = date('m', $date);
$year = date('Y', $date);
$firstDay = mktime(0,0,0,$month, 1, $year);
$table_title = strftime('%B', $firstDay);
$dayOfWeek = date('D', $firstDay);
$daysInMonth = cal_days_in_month(0, $month, $year);
/* Get the name of the week days */
$timestamp = strtotime('next Sunday');
$weekDays = array();
for ($i = 0; $i < 7; $i++) {
  $weekDays[] = strftime('%a', $timestamp);
  $timestamp = strtotime('+1 day', $timestamp);
}
$blank = date('w', strtotime("{$year}-{$month}-01"));
?>

<?php
$previous_month = date("Y-m", strtotime("-1 months", $date));
?>

<div class="table-responsive table_responsive_timesheet" style="margin-bottom: 50px;">
<table class='time_sheet_table' border=1>
  <tr>
    <th colspan="2" class="text-center"><a href="<?php echo URL::to('sales/time_sheet_previous/'.base64_encode($previous_month))?>">Previous</a></th>
    <th colspan="7" class="text-center"> <?php echo $table_title ?> <?php echo $year ?> </th>
    <th colspan="2" class="text-center">
      <?php

      
      if($today_href != ''){
        $today = '<a href="'.URL::to('sales/time_sheet').'">Today</a>';
      }
      else{
        $today = '';
      }
      echo $today?>
      
    </th>

  </tr>
  <tr>
  	<?php
  	$output='<tr>
	  	<td align="center"><b>Date</b></td>
	  	<td align="center"><b>First Visit</b></td>
	  	<td align="center"><b>Last Vist</b></td>
	  	<td align="center" style="border-right:1px solid #5f5f5f"><b>Field Hours</b></td>
	  	<td align="center"><b>Shop Visited</b></td>
	  	<td align="center"  style="border-right:1px solid #5f5f5f"><b>>1 Sim</b></td>	  	
	  	<td align="center"><b>Start of Day</b></td>
	  	<td align="center"><b>Scanned</b></td>
	  	<td align="center"><b>End of day</b></td>
	  	<td align="center" style="border-right:1px solid #5f5f5f"><b>Diff</b></td>
	  	<td align="center" width="300px"><b>Area Visits</b></td>
  	</tr>';
    

    $sub_total='';
    $sub_colon='';
    $sub_hour_calculate='';
    $sub_minutes_calculate='';
    $shop_visit_total='';
    $shop_visit_only_total='';
    $sub_total_scanned='';
    $total_different='';
    
  	if(count($daysInMonth)){
  		for ($i = 1; $i <= $daysInMonth; $i++) {

        if($i <= 9){
          $i = '0'.$i;
        }
        else{
          $i=$i;
        }

  			$current_date = date('Y', $date).'-'.date('m', $date).'-'.$i;

  			$day = date('D', strtotime($current_date));

  			if($day == 'Sat' || $day == 'Sun' ){
  				$color = 'style="background:#f3f3f3"';
  			}
  			else{
  				$color= '';
  			}

        $first_visit = DB::table('sim_allocate')->where('sales_rep_id', $user_id)->where('date', $current_date)->first();
        
        if(count($first_visit)){
          $first = $first_visit->time;
          if($first != '00:00:00'){
            $first = date('h:i A', strtotime($first_visit->time));
            $explode_first = explode(':', $first_visit->time);            
          }
          else{
            $first='';
          }
        }
        else{
          $first='';
        }

        $last_visit = DB::table('sim_allocate')->where('sales_rep_id', $user_id)->where('date', $current_date)->orderBy('time', 'desc')->first();
        
        if(count($last_visit)){
          $last = $last_visit->time;
          if($last != '00:00:00'){
            $last = date('h:i A', strtotime($last_visit->time));
            $explode_last = explode(':', $last_visit->time);

            

            $colon = ':';

            $first_visit_minutes = ($explode_first[0]*60)+$explode_first[1];
            $last_visit_minutes = ($explode_last[0]*60)+$explode_last[1];

            $total_minutes = $last_visit_minutes-$first_visit_minutes;

            $hour_calculate = strtok(($total_minutes/60), '.');
            $minutes_calculate = $total_minutes-($hour_calculate*60);

            if($hour_calculate <= 9){
              $hour_calculate = '0'.$hour_calculate;
            }
            else{
              $hour_calculate = $hour_calculate;
            }
            
            if($minutes_calculate <= 9){
              $minutes_calculate = '0'.$minutes_calculate;
            }
            else{
              $minutes_calculate = $minutes_calculate;
            }


          }
          else{
            $last='';            
            $colon='';
            $first_visit_minutes='';
            $last_visit_minutes='';
            $total_minutes='';
            $hour_calculate='';
            $minutes_calculate='';
          }
        }
        else{
          $last='';          
          $colon='';
          $first_visit_minutes='';
          $last_visit_minutes='';
          $total_minutes='';
          $hour_calculate='';
          $minutes_calculate='';
        }

        $view_date = $i.'/'.date('m', $date).'/'.date('Y', $date);

        $active_date = date('d/m/Y');

        if($active_date == $view_date){
          $bold = 'style="font-weight:bold;"';
        }
        else{
          $bold='';
        }

        $shop_visit = DB::table('sim_allocate')->where('sales_rep_id', $user_id)->where('date', $current_date)->groupBy('shop_id')->get();
        $shop_visit_only = DB::table('sim_allocate')->where('sales_rep_id', $user_id)->where('date', $current_date)->where('sim', '!=', '')->groupBy('shop_id')->get();

        if(count($shop_visit)){
          $shop_visit = count($shop_visit);
        }
        else{
          $shop_visit = '';
        }

        if(count($shop_visit_only)){
          $shop_visit_only = count($shop_visit_only);
        }
        else{
          $shop_visit_only = '';
        }

        $start_day = DB::table('start_day')->where('sales_id', $user_id)->where('start_date', $current_date)->get();

        $total_start_quantity='';
        if(count($start_day)){
          foreach ($start_day as $start) {
            $explode_network = explode(',', $start->network);            
            if(count($explode_network)){
              foreach ($explode_network as $network) {
                $quantity = unserialize($start->quantity);
                $quantitylist = $quantity[$network];

                if(count($quantitylist)){
                  foreach ($quantitylist as $quantity) {
                    $total_start_quantity = $total_start_quantity+$quantity;
                  }
                }
                
              }
            }
          }
        }

        $sim_scanned = DB::table('sim_allocate')->where('sales_rep_id', $user_id)->where('date', $current_date)->where('sim', '!=', '')->get();
        $total_scanned = '';
        if(count($sim_scanned)){
          foreach ($sim_scanned as $scanned) {
            $scannlist = explode(',', $scanned->sim);
            $total_scanned = $total_scanned+count($scannlist);
          }
        }

        $end_day = DB::table('end_day')->where('sales_id', $user_id)->where('end_date', $current_date)->get();

        $total_end_quantity='';
        if(count($end_day)){
          foreach ($end_day as $end) {
            $explode_network = explode(',', $end->network);            
            if(count($explode_network)){
              foreach ($explode_network as $network) {
                $quantity = unserialize($end->quantity);
                $quantitylist = $quantity[$network];

                if(count($quantitylist)){
                  foreach ($quantitylist as $quantity) {
                    $total_end_quantity = $total_end_quantity+$quantity;
                  }
                }
                
              }
            }
          }
        }

        if($total_end_quantity == '0'){
          $total_end_quantity = $total_end_quantity;
        }

        $total_scanned_end = $total_end_quantity+$total_scanned;        

        $different = $total_start_quantity-$total_scanned_end;

        if($different != ''){
          $different = $different;
        }
        elseif($different == '0'){
          $different='';
        }
        else{
          $different='';
        }

        $area_visit_list = DB::table('sim_allocate')->where('sales_rep_id', $user_id)->where('date', $current_date)->groupBy('area_id')->get();
        $area_for_salesrep='';
        if(count($area_visit_list)){
          foreach ($area_visit_list as $area) {
            $area_details = DB::table('area')->where('area_id', $area->area_id)->first();
            if($area_for_salesrep == ''){
                $area_for_salesrep = $area_details->area_name;
            }
            else{
                $area_for_salesrep  = $area_details->area_name.', '.$area_for_salesrep;
            }
            
          }
        }


        $returnsim_list = DB::table('return_sim')->where('sales_rep_id', $user_id)->where('date', $current_date)->where('sim', '!=', '')->get();
        $return_total = '';
        if(count($returnsim_list)){
          foreach ($returnsim_list as $returnsim) {
            $explode_return = explode(',', $returnsim->sim);
            $return_total = $return_total+count($explode_return);
          }
          $total_start_quantity = $total_start_quantity+$return_total;
          $different = $different+$return_total;
        }




  			$output.='<tr '.$color.'>
          <td align="center" '.$bold.'>'.$day.' '.$view_date.'</td>
          <td align="center">'.$first.'</td>
          <td align="center">'.$last.'</td>
          <td align="center" style="border-right:1px solid #5f5f5f">'.$hour_calculate.''.$colon.''.$minutes_calculate.'</td>
          <td align="center">'.$shop_visit.'</td>
          <td align="center" style="border-right:1px solid #5f5f5f">'.$shop_visit_only.'</td>
          <td align="center">'.$total_start_quantity.'</td>
          <td align="center">'.$total_scanned.'</td>
          <td align="center">'.$total_end_quantity.'</td>
          <td align="center" style="border-right:1px solid #5f5f5f">'.$different.'</td>
          <td align="left">'.$area_for_salesrep.'</td>

          
        </tr>';

        $sub_total = $sub_total+$total_minutes;
        if($sub_total > 0){
          $sub_colon = ':';
          $sub_hour_calculate = strtok(($sub_total/60), '.');
          $sub_minutes_calculate = $sub_total-($sub_hour_calculate*60);

          if($sub_hour_calculate <= 9){
            $sub_hour_calculate = '0'.$sub_hour_calculate;
          }
          else{
            $sub_hour_calculate = $sub_hour_calculate;
          }
          
          if($sub_minutes_calculate <= 9){
            $sub_minutes_calculate = '0'.$sub_minutes_calculate;
          }
          else{
            $sub_minutes_calculate = $sub_minutes_calculate;
          }
        }

        $shop_visit_total = $shop_visit_total+$shop_visit;
        $shop_visit_only_total = $shop_visit_only_total+$shop_visit_only;
        $sub_total_scanned = $sub_total_scanned+$total_scanned;
        $total_different = $total_different+$different;

        if($shop_visit_total > 0){
          $shop_visit_total = $shop_visit_total;
        }
        else{
          $shop_visit_total='';
        }

        if($shop_visit_only_total > 0){
          $shop_visit_only_total = $shop_visit_only_total;
        }
        else{
          $shop_visit_only_total='';
        }

        if($sub_total_scanned > 0){
          $sub_total_scanned = $sub_total_scanned;
        }
        else{
          $sub_total_scanned='';
        }

        if($total_different > 0){
          $total_different = $total_different;
        }
        else{
          $total_different='';
        }

  		}

      


      $output.='
      <tr style="background:#dadada; font-weight:bold">
        <td>Total</td>
        <td></td>
        <td></td>
        <td align="center" style="border-right:1px solid #5f5f5f">'.$sub_hour_calculate.''.$sub_colon.''.$sub_minutes_calculate.'</td>
        <td align="center">'.$shop_visit_total.'</td>
        <td align="center" style="border-right:1px solid #5f5f5f">'.$shop_visit_only_total.'</td>
        <td></td>
        <td align="center">'.$sub_total_scanned.'</td>
        <td></td>
        <td align="center" style="border-right:1px solid #5f5f5f">'.$total_different.'</td>        
        <td></td>
      </tr>
      ';
  	}
  	echo $output;
  ?>
  </tr>
</table>
</div>

			
		</div>
		



		
	</div>

</div>
		
		
	</div>
</div>              
<!-- /.content -->
@stop

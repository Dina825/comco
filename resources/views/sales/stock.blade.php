@extends('salesheader')
@section('content')
<!-- Content Header (Page header) -->




<div class="col-lg-12 col-md-12 col-sm-12 col-12 sales_right_panel">
	<div class="row">
		<div class="col-lg-12 col-sm-12 col-12">
			<div class="main_title">Stock of <span>Month</span></div>
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
    
		<div class="col-lg-12 col-md-12 col-sm-12 col-12">
      <div class="table-responsive">
        <table class="table table-striped" id="shop_table">
          <thead class="thead-dark">
            <tr>          
              <th style="width: 100px">#</th>    
              <th>Stock Date</th>              
            </tr>
          </thead>
          <tbody>
            <?php
            $output_stock='';
            $i=1;
            if(count($stocklist)){
              foreach ($stocklist as $stock) {

                if($stock->submitted == 0){
                  $send = 'Submitted by admin.';
                }
                else{
                  $area_manager = DB::table('area_manager')->where('user_id', $stock->submitted)->first();
                  $send = 'Submitted by Area Manager '.$area_manager->firstname.' '.$area_manager->surname;

                }


                $output_stock.='<tr/>
                  <td>'.$i.'</td>
                  <td><a href="'.URL::to('sales/view_stock/'.base64_encode($stock->stock_id)).'">'.date('d-M-Y', strtotime($stock->stock_date)).' - '.$send.'</a></td>
                  
                <tr>';
                $i++;
              }              
            }
            else{
              $output_stock='<tr>
              
              <td align="center" colspan="2">Empty</td>
              
              </tr>';
            }
            echo $output_stock;

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

@stop
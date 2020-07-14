<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
use URL;
use Hash;

class ShopController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Admin $admin)
	{
		$this->middleware('adminauth');
		$this->admin = $admin;
		date_default_timezone_set("Europe/London");
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	
	public function shop()
	{
		$user = DB::table('users')->where('user_type', 2)->get();
		$area = DB::table('area')->where('status', 0)->get();
		$route = DB::table('route')->get();
		$payment_mode = DB::table('mode_payment')->where('status', 0)->get();
		$shop_type = DB::table('shop_type')->where('status', 0)->get();
		$potential = DB::table('potential_sale')->where('status', 0)->get();

		$shop = DB::table('shop')->get();
		return view('admin/shop', array('title' => 'Manage Shop', 'arealist' => $area, 'userlist' => $user, 'routelist' => $route, 'payment_mode_list' => $payment_mode, 'shop_type_list' => $shop_type, 'potentiallist' => $potential, 'shoplist' => $shop));
	}

	public function shopadd(){	

		$email_id = Input::get('email_id');
		$password = Input::get('password');

		if($email_id != ''){
			$data['email_id'] = $email_id;
		}
		if($password != ''){
			$data['password'] = Hash::make($password);
		}
		$data['area_name'] = Input::get('area_name');
		$data['sales_rep'] = Input::get('sales_rep');
		$data['route'] = Input::get('route');
		$data['shop_name'] = Input::get('shop_name');
		$data['customer_name'] = Input::get('customer_name');
		$data['payee_name'] = Input::get('payee_name');
		$data['address1'] = Input::get('address1');
		$data['address2'] = Input::get('address2');
		$data['address3'] = Input::get('address3');
		$data['city'] = Input::get('city');
		$data['postcode'] = Input::get('postcode');
		$data['phone_number'] = Input::get('phone_number');
		$data['payment_mode'] = Input::get('payment_mode');
		$data['shop_type'] = Input::get('shop_type');
		$data['shop_potential'] = Input::get('shop_potential');
		$data['commission_plan'] = Input::get('tier');
		$data['account_created'] = date('Y-m-d h:i:s');
		
		DB::table('shop')->insert($data);

		return redirect::back()->with('message', 'Shop was add succusfully');
	}
	
	public function shopdetails(){
		$id = base64_decode(Input::get('id'));
		$type = Input::get('type');

		$shop = DB::table('shop')->where('shop_id', $id)->first();
		
		if($type==1){
			$content = 'Are you sure want Active '.$shop->shop_name.' Shop?';
			$title = 'Active Shop';
			$status = $shop->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==2){
			$content = 'Are you sure want Deactivate '.$shop->shop_name.' Shop?';
			$title = 'Deactivate Shop';
			$status = $shop->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==3){

			$salesreplist = DB::table('users')->where('user_type', 2)->where('status', 0)->get();
			$output_salesrep='<option value="">Please Select Sales REP</option>';
			$i=0;
			if(count($salesreplist)){
				foreach ($salesreplist as $salesrep) {
					$salesrep_details = DB::table('sales_rep')->where('user_id', $salesrep->user_id)->first();
					$explode_area = explode(',', $salesrep_details->area);
					if(in_array($shop->area_name, $explode_area)){
						if($salesrep->user_id == $shop->sales_rep){
							$selected='selected';
						}
						else{
							$selected='';
						}
						$output_salesrep.='
						<option value="'.$salesrep->user_id.'" '.$selected.'>'.$salesrep_details->firstname.'</option>
						';
						$i++;
					}				
				}
				if($i==0){
					$output_salesrep ='<option value="">Sales REP not available</option>';
				}
				
			}

			$routelist = DB::table('route')->where('area_id', $shop->area_name)->where('status', 0)->get();
			$output_route='<option value="">Select Route</option>';
			$i=0;
			if(count($routelist)){
				foreach ($routelist as $route) {
					$explode_sales_id = explode(',', $route->sales_rep_id);
					if(in_array($shop->sales_rep, $explode_sales_id)){
						if($route->route_id == $shop->route){
							$selected= 'selected';
						}
						else{
							$selected='';
						}
						$output_route.='<option value="'.$route->route_id.'" '.$selected.'>'.$route->route_name.'</option>';
						$i++;
					}				
				}
				if($i==0){
					$output_route ='<option value="">Route not available in Sales REP</option>';
				}
			}
			else{
				$output_route ='<option value="">Route not available in Sales REP</option>';
			}
			

			echo json_encode(array('id' => base64_encode($id), 'area_name' => $shop->area_name, 'output_salesrep' => $output_salesrep, 'output_route' => $output_route, 'shop_name' => $shop->shop_name, 'customer_name' => $shop->customer_name, 'payee_name' => $shop->payee_name, 'address1' => $shop->address1, 'address2' => $shop->address2, 'address3' => $shop->address3, 'city' => $shop->city, 'postcode' => $shop->postcode, 'phone_number' => $shop->phone_number, 'payment_mode' => $shop->payment_mode, 'shop_type' => $shop->shop_type, 'shop_potential' => $shop->shop_potential, 'email_id' => $shop->email_id,'plan' => $shop->commission_plan));
		}	

	}

	public function shopstatus(){
		$id = base64_decode(Input::get('id'));
		$status = Input::get('status');

		if($status == 0){
			$statuschange = 1;
			DB::table('shop')->where('shop_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Shop was successfully deactived');
		}
		if($status == 1){
			$statuschange = 0;
			DB::table('shop')->where('shop_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Shop was successfully actived');
		}

	}

	public function shopupdate(){
		$id = base64_decode(Input::get('id'));

		$email_id = Input::get('email_id');
		$password = Input::get('password');

		if($email_id != ''){
			$data['email_id'] = $email_id;
		}
		if($password != ''){
			$data['password'] = Hash::make($password);
		}
		/*$data['area_name'] = Input::get('area_name');
		$data['sales_rep'] = Input::get('sales_rep');
		$data['route'] = Input::get('route');*/
		$data['shop_name'] = Input::get('shop_name');
		$data['customer_name'] = Input::get('customer_name');
		$data['payee_name'] = Input::get('payee_name');
		$data['address1'] = Input::get('address1');
		$data['address2'] = Input::get('address2');
		$data['address3'] = Input::get('address3');
		$data['city'] = Input::get('city');
		$data['postcode'] = Input::get('postcode');
		$data['phone_number'] = Input::get('phone_number');
		$data['payment_mode'] = Input::get('payment_mode');
		$data['shop_type'] = Input::get('shop_type');
		$data['shop_potential'] = Input::get('shop_potential');
		$data['commission_plan'] = Input::get('tier_edit');

		DB::table('shop')->where('shop_id', $id)->update($data);
		return Redirect::back()->with('message', 'Shop was successfully updated');

	}

	public function shopformfilterarea(){
		$id = Input::get('id');
		
		$salesreplist = DB::table('users')->where('user_type', 2)->where('status', 0)->get();
		$output_salesrep='<option value="">Please Select Sales REP</option>';
		$i=0;
		if(count($salesreplist)){
			foreach ($salesreplist as $salesrep) {
				$salesrep_details = DB::table('sales_rep')->where('user_id', $salesrep->user_id)->first();
				$explode_area = explode(',', $salesrep_details->area);
				if(in_array($id, $explode_area)){
					$output_salesrep.='
					<option value="'.$salesrep->user_id.'">'.$salesrep_details->firstname.'</option>
					';
					$i++;
				}				
			}
			if($i==0){
				$output_salesrep ='<option value="">Sales REP not available</option>';
			}
			
		}

		echo json_encode(array('output_salesrep' => $output_salesrep));
	}

	public function shopformfiltersales(){
		$sales_id = Input::get('salerep_id');
		$area_id = Input::get('area_id');

		$routelist = DB::table('route')->where('area_id', $area_id)->where('status', 0)->get();
		$output_route='<option value="">Select Route</option>';
		$i=0;
		if(count($routelist)){
			foreach ($routelist as $route) {
				$explode_sales_id = explode(',', $route->sales_rep_id);
				if(in_array($sales_id, $explode_sales_id)){
					$output_route.='<option value="'.$route->route_id.'">'.$route->route_name.'</option>';
					$i++;
				}				
			}
			if($i==0){
				$output_route ='<option value="">Route not available in Sales REP</option>';
			}
		}
		else{
			$output_route ='<option value="">Route not available in Sales REP</option>';
		}

		echo json_encode(array('output_route' => $output_route));
	}

	public function searchcommon(){
		$type = Input::get('type');
		$value = Input::get('value');

		if($type == 1){
			$shoplist = DB::table('shop')->where('shop_name', 'like', '%'.$value.'%')->get();			
		}
		if($type == 2){
			$shoplist = DB::table('shop')->where('shop_id', 'like', '%'.$value.'%')->get();
		}
		if($type == 3){
			$shoplist = DB::table('shop')->where('postcode', 'like', '%'.$value.'%')->get();
		}
		$output_shop='';
	  	$i=1;
      	if(count($shoplist)){
        	foreach ($shoplist as $shop) {
	          $route_details = DB::table('route')->where('route_id', $shop->route)->first();
	          $salesrep_details = DB::table('sales_rep')->where('user_id', $shop->sales_rep)->first();
	          $area_details = DB::table('area')->where('area_id', $shop->area_name)->first();

	          if($shop->status == 0){
	              $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Deactive" style="color: #33CC66"><i class="fas fa-check deactive_class" data-element="'.base64_encode($shop->shop_id).'"></i></a>&nbsp;&nbsp;&nbsp;';
	          }
	          else{
	              $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Active" style="color: #E11B1C"><i class="fas fa-times active_class" data-element="'.base64_encode($shop->shop_id).'"></i></a>&nbsp;&nbsp;&nbsp;';
	          }

	          

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

              $pending_cost = DB::table('shop_commission')
              ->select(DB::raw('SUM(one_cost + two_cost + three_cost + four_cost + five_cost + six_cost + seven_cost + eight_cost + nine_cost + ten_cost) AS pending_cost'))
              ->where('shop_id',$shop->shop_id)
              ->first();
              if(count($pending_cost))
              {
                $pending_cost_value = $pending_cost->pending_cost;
              }
              else{
                $pending_cost_value = 0;
              }

              $bonus_cost = DB::table('shop_commission')->where('shop_id',$shop->shop_id)->sum('bonus_cost');
              $total_pending_cost = $pending_cost_value - $bonus_cost;



	          $output_shop.='
	          <tr>
	            <td>'.$i.'</td>
	            <td><a href="'.URL::to('admin/shop_view_details'.'/'.base64_encode($shop->shop_id)).'">'.$shop->shop_name.'</a></td>
	            <td>CC-'.$shop->shop_id.'</td>
	            <td>'.$shop->customer_name.'</td>
	            <td>'.$shop->postcode.'</td>
	            <td>'.$route_details->route_name.'</td>
	            <td>'.$salesrep.'</td>
	            <td>'.$area_details->area_name.'</td>
	            <td align="center" class="total_'.$shop->shop_id.'"></td>
                <td align="center" class="active_'.$shop->shop_id.'"></td>
                <td align="center" class="inactive_'.$shop->shop_id.'"></td>
                <td align="center" class="last3month_'.$shop->shop_id.'"></td>

                <td><a href="javascript:"><i class="fas fa-sync-alt refresh_icon" data-element="'.base64_encode($shop->shop_id).'"></i></a></td>
                <td align="center">
                £ '.$total_pending_cost.'
                </td>
	            <td align="center">
	            <a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Area" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($shop->shop_id).'"></i></a>&nbsp;&nbsp;&nbsp;    
	              '.$status.'
	            <a href="'.URL::to('admin/shop_review_commission?shop_id='.$shop->shop_id.'').'" data-toggle="tooltip" data-placement="top" data-original-title="Shop Commission" ><i class="fas fa-pound-sign"></i></a>
	            </td>
	          </tr>';
	          $i++;
        }                    
      }
      else{
        $output_shop='
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td align="center" width="300px;">Search '.$value.' Not found</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>                      
          <td></td>
          <td></td>
          <td></td>

        </tr>
        ';
      }
      echo json_encode(array('output_shop' => $output_shop));

	}

	public function filterforshop(){
		$type = Input::get('type');

		if($type == 1){
			$id = Input::get('id');
		
			$salesreplist = DB::table('users')->where('user_type', 2)->where('status', 0)->get();
			$output_salesrep='<option value="">Please Select Sales REP</option>';
			$i=0;
			if(count($salesreplist)){
				foreach ($salesreplist as $salesrep) {
					$salesrep_details = DB::table('sales_rep')->where('user_id', $salesrep->user_id)->first();
					$explode_area = explode(',', $salesrep_details->area);
					if(in_array($id, $explode_area)){
						$output_salesrep.='
						<option value="'.$salesrep->user_id.'">'.$salesrep_details->firstname.'</option>
						';
						$i++;
					}
					$shoplist = DB::table('shop')->where('area_name', $id)->get();				
				}
				if($i==0){
					$output_salesrep ='<option value="">Sales REP not available</option>';
					$shoplist = DB::table('shop')->get();
				}
				
			}

			
			$output_route='';
		}
		elseif($type == 2){
			$sales_id = Input::get('salerep_id');
			$area_id = Input::get('area_id');

			$routelist = DB::table('route')->where('area_id', $area_id)->where('status', 0)->get();
			$output_route='<option value="">Select Route</option>';
			$i=0;
			if(count($routelist)){
				foreach ($routelist as $route) {
					$explode_sales_id = explode(',', $route->sales_rep_id);
					if(in_array($sales_id, $explode_sales_id)){
						$output_route.='<option value="'.$route->route_id.'">'.$route->route_name.'</option>';
						$i++;
					}	
					$shoplist = DB::table('shop')->where('area_name', $area_id)->where('sales_rep', $sales_id)->get();			
				}
				if($i==0){
					$output_route ='<option value="">Route not available in Sales REP</option>';
					if($sales_id != ''){
						$shoplist = DB::table('shop')->where('area_name', $area_id)->where('sales_rep', $sales_id)->get();
					}
					else{
						$shoplist = DB::table('shop')->where('area_name', $area_id)->get();
					}
					
				}
			}
			else{
				$output_route ='<option value="">Route not available in Sales REP</option>';
			}

			
			$output_salesrep='';			
		}
		elseif($type == 3){
			$sales_id = Input::get('salerep_id');
			$area_id = Input::get('area_id');
			$route_id = Input::get('route_id');

			if($route_id != ''){
				$shoplist = DB::table('shop')->where('area_name', $area_id)->where('sales_rep', $sales_id)->where('route', $route_id)->get();
			}
			else{
				$shoplist = DB::table('shop')->where('area_name', $area_id)->where('sales_rep', $sales_id)->get();
			}

			
			$output_salesrep='';
			$output_route='';
		}
		elseif($type == 4){
			$user_id = Input::get('id');



			$salesrep_details = DB::table('sales_rep')->where('user_id', $user_id)->first();
			$explode_area_sales = explode(',', $salesrep_details->area);


			$route_id='';
			if(count($explode_area_sales)){
				foreach ($explode_area_sales as $area) {
					$route_details = DB::table('route')->where('area_id', $area)->get();
					if(count($route_details)){
						foreach ($route_details as $route) {
							$explode_route_sales = explode(',', $route->sales_rep_id);
							if(in_array($user_id, $explode_route_sales)){					

								if($route_id == ''){
									$route_id = $route->route_id;
								}
								else{
									$route_id = $route->route_id.','.$route_id;
								}
							}
						}
					}
				}
			}

			$explode_route = explode(',', $route_id);
			$shoplist = DB::table('shop')->whereIn('route', $explode_route)->get();

			$output_salesrep='';
			$output_route='';

		}


		$output_shop='';
	  	$i=1;
      	if(count($shoplist)){
        	foreach ($shoplist as $shop) {
	          $route_details = DB::table('route')->where('route_id', $shop->route)->first();
	          $salesrep_details = DB::table('sales_rep')->where('user_id', $shop->sales_rep)->first();
	          $area_details = DB::table('area')->where('area_id', $shop->area_name)->first();

	          if($shop->status == 0){
	              $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Deactive" style="color: #33CC66"><i class="fas fa-check deactive_class" data-element="'.base64_encode($shop->shop_id).'"></i></a>&nbsp;&nbsp;&nbsp;';
	          }
	          else{
	              $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Active" style="color: #E11B1C"><i class="fas fa-times active_class" data-element="'.base64_encode($shop->shop_id).'"></i></a>&nbsp;&nbsp;&nbsp;';
	          }

	          
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

              $pending_cost = DB::table('shop_commission')
              ->select(DB::raw('SUM(one_cost + two_cost + three_cost + four_cost + five_cost + six_cost + seven_cost + eight_cost + nine_cost + ten_cost) AS pending_cost'))
              ->where('shop_id',$shop->shop_id)
              ->first();
              if(count($pending_cost))
              {
                $pending_cost_value = $pending_cost->pending_cost;
              }
              else{
                $pending_cost_value = 0;
              }

              $bonus_cost = DB::table('shop_commission')->where('shop_id',$shop->shop_id)->sum('bonus_cost');
              $total_pending_cost = $pending_cost_value - $bonus_cost;

	          $output_shop.='
	          <tr>
	            <td>'.$i.'</td>
	            <td><a href="'.URL::to('admin/shop_view_details'.'/'.base64_encode($shop->shop_id)).'">'.$shop->shop_name.'</a></td>
	            <td>CC-'.$shop->shop_id.'</td>
	            <td>'.$shop->customer_name.'</td>
	            <td>'.$shop->postcode.'</td>
	            <td>'.$route_details->route_name.'</td>
	            <td>'.$salesrep.'</td>
	            <td>'.$area_details->area_name.'</td>

	            <td align="center" class="total_'.$shop->shop_id.'"></td>
                <td align="center" class="active_'.$shop->shop_id.'"></td>
                <td align="center" class="inactive_'.$shop->shop_id.'"></td> 
                <td align="center" class="last3month_'.$shop->shop_id.'"></td>                       

                <td><a href="javascript:"><i class="fas fa-sync-alt refresh_icon" data-element="'.base64_encode($shop->shop_id).'"></i></a></td>
	             
                <td align="center">
                £ '.$total_pending_cost.'
                </td>
	            <td align="center">
	            <a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Area" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($shop->shop_id).'"></i></a>&nbsp;&nbsp;&nbsp;    
	              '.$status.'
	            <a href="'.URL::to('admin/shop_review_commission?shop_id='.$shop->shop_id.'').'" data-toggle="tooltip" data-placement="top" data-original-title="Shop Commission" ><i class="fas fa-pound-sign"></i></a>
	            </td>
	          </tr>';
	          $i++;
        }                    
      }
      else{
        $output_shop='
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td align="center" width="300px;">Empty</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>                      
          <td></td>
          <td></td>
          <td></td>
        </tr>
        ';
      }

		echo json_encode(array('output_salesrep' => $output_salesrep, 'output_route' => $output_route, 'output_shop' => $output_shop));
	}

	public function shopviewdetails($id=""){
		$id = base64_decode($id);
		$shop = DB::table('shop')->where('shop_id', $id)->first();
		$route_details = DB::table('route')->where('route_id', $shop->route)->first();
		$network = DB::table('network')->where('status', 0)->get();


		return view('admin/shop_details', array('title' => 'Manage Shop', 'shop_details' => $shop, 'route_details' => $route_details, 'networklist' => $network));
	}

	public function refreshshop (){
		$shop_id = base64_decode(Input::get('id'));

		$total_sim = DB::table('sim')->where('shop_id', $shop_id)->count();
	    $active_sim = DB::table('sim')->where('shop_id', $shop_id)->where('activity_date', '!=', '0000-00-00')->count();
	    $inactive_sim = DB::table('sim')->where('shop_id', $shop_id)->where('activity_date', '0000-00-00')->count();

	    

	    $output_month_three='';
        for ($j = 0; $j <= 2; $j++) {
	        $last_three_month[] =  date("M-Y-m", strtotime(" -$j month"));                    
	    }
	    krsort($last_three_month);

	    $month_three_array = array();
        $month_total_array = array();
        if(count($last_three_month)){
          foreach ($last_three_month as $three_month) {
            $explode_three_month_year = explode('-', $three_month);

            $sim_count_three_details = DB::table('sim')->where('shop_id', $shop_id)->where('activity_date', '!=','0000-00-00')->get();
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

            $output_month_three.= $explode_three_month_year[0].'-'.$count_three_month.'<br/>';
          }          
        }

        if($total_sim != 0){
	      $total_sim = $total_sim;
	      $output_month_three = $output_month_three;
	    }
	    else{
	      $total_sim = '';
	      $output_month_three = '';
	    }

	    if($active_sim != 0){
	      $active_sim = $active_sim;
	    }
	    else{
	      $active_sim='';
	    }

	    if($inactive_sim != 0){
	      $inactive_sim = $inactive_sim;
	    }
	    else{
	      $inactive_sim='';
	    }
		
		$output_total = '<a href="javascript:" class="total_sim"  data-element="'.base64_encode($shop_id).'">'.$total_sim.'</a>';
        $output_active ='<a href="javascript:" class="active_sim"  data-element="'.base64_encode($shop_id).'">'.$active_sim.'</a>';
        $output_inactive ='<a href="javascript:" class="inactive_sim"  data-element="'.base64_encode($shop_id).'">'.$inactive_sim.'</a>';
        $output_3month = '<a href="javascript:" class="last_3month"  data-element="'.base64_encode($shop_id).'">'.$output_month_three.'</a>';

        echo json_encode(array('shop_id' => $shop_id, 'total' => $output_total, 'active' => $output_active, 'inactive' => $output_inactive, 'last3month' => $output_3month));
	}

	public function totalsim(){
		$type =Input::get('type');
		$shop_id = base64_decode(Input::get('id'));

		$sim_list_details = DB::table('shop')->join('sim', 'shop.shop_id', '=', 'sim.shop_id')->select('sim.*','shop.shop_name')->where('shop.shop_id', $shop_id)->get();


		$output='';
		$i=1;

		if(count($sim_list_details)){
			foreach ($sim_list_details as $sim_details) {
				if($sim_details->activity_date == '0000-00-00'){
					$status ='Inactive';
				}
				else{
					$status = 'Active('.date('d-M-Y', strtotime($sim_details->activity_date)).')';
				}

				$sim_allocate_details = DB::table('sim_allocate')->whereRaw('FIND_IN_SET('.$sim_details->id.',sim)')->first();
				$sales_rep = DB::table('sales_rep')->where('user_id', $sim_allocate_details->sales_rep_id)->first();

				if(count($sales_rep)){
					$sales_name = $sales_rep->firstname.''.$sales_rep->surname;
				}
				else{
					$sales_name='';
				}
				
				$output.='
				<tr>
		      		<td>'.$i.'</td>
		      		<td>'.$sim_details->network_id.'</td>
		      		<td>'.$sim_details->product_id.'</td>
		      		<td>'.$sim_details->ssn.'</td>
		      		<td>'.$sim_details->cli.'</td>
		      		<td>'.date('d-M-Y', strtotime($sim_details->allocated)).'</td>
		      		<td>'.$sales_name.'</td>
		      		<td>'.$sim_details->shop_name.'</td>
		      		<td>'.$status.'</td>
		      	</tr>
				';
				$i++;
									
			}
		}

		$shop = DB::table('shop')->where('shop_id', $shop_id)->first();
			$title = 'Total sim for Shop '.$shop->shop_name;
		echo json_encode(array('output' => $output, 'title' => $title));
	}

	public function activesim(){
		$type =Input::get('type');
		$shop_id = base64_decode(Input::get('id'));

		$sim_list_details = DB::table('shop')->join('sim', 'shop.shop_id', '=', 'sim.shop_id')->select('sim.*','shop.shop_name')->where('shop.shop_id', $shop_id)->where('sim.activity_date', '!=', '0000-00-00')->get();

		
		$output='';
		$i=1;

		if(count($sim_list_details)){
			foreach ($sim_list_details as $sim_details) {
				if($sim_details->activity_date == '0000-00-00'){
					$status ='Inactive';
				}
				else{
					$status = 'Active('.date('d-M-Y', strtotime($sim_details->activity_date)).')';
				}

				$sim_allocate_details = DB::table('sim_allocate')->whereRaw('FIND_IN_SET('.$sim_details->id.',sim)')->first();
				$sales_rep = DB::table('sales_rep')->where('user_id', $sim_allocate_details->sales_rep_id)->first();

				if(count($sales_rep)){
					$sales_name = $sales_rep->firstname.''.$sales_rep->surname;
				}
				else{
					$sales_name='';
				}
				
				$output.='
				<tr>
		      		<td>'.$i.'</td>
		      		<td>'.$sim_details->network_id.'</td>
		      		<td>'.$sim_details->product_id.'</td>
		      		<td>'.$sim_details->ssn.'</td>
		      		<td>'.$sim_details->cli.'</td>
		      		<td>'.date('d-M-Y', strtotime($sim_details->allocated)).'</td>
		      		<td>'.$sales_name.'</td>
		      		<td>'.$sim_details->shop_name.'</td>
		      		<td>'.$status.'</td>
		      	</tr>
				';
				$i++;
									
			}
		}

		$shop = DB::table('shop')->where('shop_id', $shop_id)->first();
			$title = 'Active sim for Shop '.$shop->shop_name;
		echo json_encode(array('output' => $output, 'title' => $title));
	}
	public function inactivesim(){
		$type =Input::get('type');
		$shop_id = base64_decode(Input::get('id'));

		$sim_list_details = DB::table('shop')->join('sim', 'shop.shop_id', '=', 'sim.shop_id')->select('sim.*','shop.shop_name')->where('shop.shop_id', $shop_id)->where('sim.activity_date', '0000-00-00')->get();

		
		$output='';
		$i=1;

		if(count($sim_list_details)){
			foreach ($sim_list_details as $sim_details) {
				if($sim_details->activity_date == '0000-00-00'){
					$status ='Inactive';
				}
				else{
					$status = 'Active('.date('d-M-Y', strtotime($sim_details->activity_date)).')';
				}

				$sim_allocate_details = DB::table('sim_allocate')->whereRaw('FIND_IN_SET('.$sim_details->id.',sim)')->first();
				$sales_rep = DB::table('sales_rep')->where('user_id', $sim_allocate_details->sales_rep_id)->first();

				if(count($sales_rep)){
					$sales_name = $sales_rep->firstname.''.$sales_rep->surname;
				}
				else{
					$sales_name='';
				}
				
				$output.='
				<tr>
		      		<td>'.$i.'</td>
		      		<td>'.$sim_details->network_id.'</td>
		      		<td>'.$sim_details->product_id.'</td>
		      		<td>'.$sim_details->ssn.'</td>
		      		<td>'.$sim_details->cli.'</td>
		      		<td>'.date('d-M-Y', strtotime($sim_details->allocated)).'</td>
		      		<td>'.$sales_name.'</td>
		      		<td>'.$sim_details->shop_name.'</td>
		      		<td>'.$status.'</td>
		      	</tr>
				';
				$i++;
									
			}
		}

		$shop = DB::table('shop')->where('shop_id', $shop_id)->first();
			$title = 'Inactive sim for Shop '.$shop->shop_name;
		echo json_encode(array('output' => $output, 'title' => $title));
	}

	public function last3month(){
		
		$shop_id = base64_decode(Input::get('id'));

		$output_month_three='<div class="table-responsive">
        <table class="own_table">
            <thead>
              <tr>                
                <th style="text-align: center;">Network</th> ';
        for ($j = 0; $j <= 2; $j++) {
	        $last_three_month[] =  date("M-Y-m", strtotime(" -$j month"));                    
	    }
	    krsort($last_three_month);

	    $month_three_array = array();
        $month_total_array = array();
        if(count($last_three_month)){
          foreach ($last_three_month as $three_month) {
            $explode_three_month_year = explode('-', $three_month);

            $sim_count_three_details = DB::table('sim')->where('shop_id', $shop_id)->where('activity_date', '!=','0000-00-00')->get();
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

            $output_month_three.='
            <th style="text-align: center;">'.$explode_three_month_year[0].'&nbsp;('.$count_three_month.')</th>
            ';
          }
          $output_month_three.='</tr></thead>';
        }
        $output_month_three.='';
        $networklist = DB::table('network')->get();
        if(count($networklist)){
                foreach ($networklist as $network) {  
                $output_total='';                
                  if(count($month_three_array))
                  {
                    $output_month_three.='<tr><td align="left">'.$network->network_name.'</td>';
                    foreach($month_three_array as $mon_three)
                    {                      
                      $sim_network_count = DB::table('sim')->where('shop_id', $shop_id)->where('network_id', $network->network_name)->where('activity_date','like',$mon_three.'%')->count();
                      
                      if($sim_network_count == 0){
                        $sim_network_count='-';
                      }
                      $output_month_three.='<td align="center">'.$sim_network_count.'</td>'; 
                      $output_total.='<td align="center"></td>';
                    }
                                     
                  }

                }
                $output_total='';
                if(count($month_total_array)){
                  foreach ($month_total_array as $total_array) {
                    $output_total.='<td align="center">'.$total_array.'</td>';
                  }
                }

                $output_month_three.='<tr style="background: #eaeaea; font-weight:bold;">
                                  <td style="background: #eaeaea;">Total</td>'.$output_total.'
                                  
                                  </tr>';
              }
              else{
                $output_month_three='
                <tr>
                <td colspan="7" align="center">Empty</td>
              </tr>';
              }

		$shop = DB::table('shop')->where('shop_id', $shop_id)->first();
		$title = 'Last 3 month Active sim for Shop '.$shop->shop_name;
		echo json_encode(array('output' => $output_month_three, 'title' => $title));
	}

	public function monthonmonth()
	{
		$user = DB::table('users')->where('user_type', 2)->get();
		$area = DB::table('area')->where('status', 0)->get();
		$route = DB::table('route')->get();
		$payment_mode = DB::table('mode_payment')->where('status', 0)->get();
		$shop_type = DB::table('shop_type')->where('status', 0)->get();
		$potential = DB::table('potential_sale')->where('status', 0)->get();

		$shop = DB::table('shop')->select('shop_name', 'shop_id', 'status')->limit(25)->get();
		return view('admin/shop_month', array('title' => 'Month on Month', 'arealist' => $area, 'userlist' => $user, 'routelist' => $route, 'payment_mode_list' => $payment_mode, 'shop_type_list' => $shop_type, 'potentiallist' => $potential, 'shoplist' => $shop));
	}

	public function monthscroll(){
		$value = Input::get('value');
		$shoplist = DB::table('shop')->select('shop_name', 'shop_id', 'status')->offset($value)->limit(25)->get();

		$output_shop='';
		
	      $i=$value;
	       
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
	        $output_shop.='<tr class="last_tr_'.$last_i.'"></tr>';        
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


	      echo json_encode(array('output' => $output_shop, 'last_i' => $last_i));
	}

	

}

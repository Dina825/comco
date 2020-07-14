<?php namespace App\Http\Controllers\areamanager;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
use URL;
use Hash;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;

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
		$this->middleware('areamangerauth');
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

		$user_id = Session::get("areamanager_userid");

		
		return view('areamanager/shop', array('title' => 'Manage Shop', 'arealist' => $area, 'userlist' => $user, 'routelist' => $route, 'payment_mode_list' => $payment_mode, 'shop_type_list' => $shop_type, 'potentiallist' => $potential, 'user_id' => $user_id));
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

		$user_id = Session::get("areamanager_userid");
		$user_details = DB::table('area_manager')->where('user_id', $user_id)->first();
        $explodearea = explode(',', $user_details->area);	

		if($type == 1){
			$shoplist = DB::table('shop')->whereIn('area_name', $explodearea)->where('shop_name', 'like', '%'.$value.'%')->get();			
		}
		if($type == 2){
			$shoplist = DB::table('shop')->whereIn('area_name', $explodearea)->where('shop_id', 'like', '%'.$value.'%')->get();
		}
		if($type == 3){
			$shoplist = DB::table('shop')->whereIn('area_name', $explodearea)->where('postcode', 'like', '%'.$value.'%')->get();
		}
		$output_shop='';
	  	$i=1;
      	if(count($shoplist)){
        	foreach ($shoplist as $shop) {
	          $route_details = DB::table('route')->where('route_id', $shop->route)->first();
	          $salesrep_details = DB::table('sales_rep')->where('user_id', $shop->sales_rep)->first();
	          $area_details = DB::table('area')->where('area_id', $shop->area_name)->first();

	          

	          

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

              if($shop->address2 != ''){
	              $address2 = $shop->address2.'<br/>';
	            }
	            else{
	              $address2 = $shop->address2;
	            }
	            if($shop->address3 != ''){
	              $address3 = $shop->address3.'<br/>';
	            }
	            else{
	              $address3 = $shop->address3;
	            }



	          $output_shop.='
	          <tr>
	          <td>
	          <a href="'.URL::to('areamanager/shop_view_details/'.base64_encode($shop->shop_id)).'">
                  '.$shop->shop_name.'<br/>
                  CC-'.$shop->shop_id.'<br/>
                  '.$shop->address1.'<br/>
                  '.$address2.'
                  '.$address3.'
                  '.$shop->postcode.'<br/>

                </a>
                </td>
	            
	            
	          </tr>';
	          $i++;
        }                    
      }
      else{
        $output_shop='
        <tr>          
          <td align="center" width="300px;">Search '.$value.' Not found</td>
        </tr>
        ';
      }
      echo json_encode(array('output_shop' => $output_shop));

	}

	public function filterforshopold(){
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
					$output_salesrep ='<option value="">Sales REP not available 000</option>';

					$user_id = Session::get("areamanager_userid");
					$user_details = DB::table('area_manager')->where('user_id', $user_id)->first();
                    $explodearea = explode(',', $user_details->area);

					$shoplist = DB::table('shop')->whereIn('area_name', $explodearea)->get();
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

			if($user_id == ''){
				$user_id = Session::get("areamanager_userid");
				$user_details = DB::table('area_manager')->where('user_id', $user_id)->first();
	            $explodearea = explode(',', $user_details->area);

				$shoplist = DB::table('shop')->whereIn('area_name', $explodearea)->get();
			}
			else{
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
			}

			

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
	            <td><a href="'.URL::to('areamanager/shop_view_details'.'/'.base64_encode($shop->shop_id)).'">'.$shop->shop_name.'</a></td>
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
	            <a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Shop" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($shop->shop_id).'"></i></a>&nbsp;&nbsp;&nbsp;    
	              
	            <a href="'.URL::to('areamanager/shop_review_commission?shop_id='.$shop->shop_id.'').'" data-toggle="tooltip" data-placement="top" data-original-title="Shop Commission"  style="display:none" ><i class="fas fa-pound-sign"></i></a>
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

		$payment_mode = DB::table('mode_payment')->where('status', 0)->get();
		$shop_type = DB::table('shop_type')->where('status', 0)->get();
		$potential = DB::table('potential_sale')->where('status', 0)->get();
		
		$user_id = Session::get("areamanager_userid");


		return view('areamanager/shop_details', array('title' => 'Manage Shop', 'shop_details' => $shop, 'route_details' => $route_details, 'payment_mode_list' => $payment_mode, 'shop_type_list' => $shop_type, 'potentiallist' => $potential, 'networklist' => $network, 'user_id' => $user_id));
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

				$sim_allocate_details = DB::table('sim_allocate')->whereRaw('FIND_IN_SET('.$sim_details->id.',sim)')->orderBy('allocate_id', 'desc')->first();
				$sales_rep = DB::table('sales_rep')->where('user_id', $sim_allocate_details->sales_rep_id)->first();

				if(count($sales_rep)){
					$sales_name = $sales_rep->firstname.' '.$sales_rep->surname;
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

				$sim_allocate_details = DB::table('sim_allocate')->whereRaw('FIND_IN_SET('.$sim_details->id.',sim)')->orderBy('allocate_id', 'desc')->first();
				$sales_rep = DB::table('sales_rep')->where('user_id', $sim_allocate_details->sales_rep_id)->first();

				if(count($sales_rep)){
					$sales_name = $sales_rep->firstname.' '.$sales_rep->surname;
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

				$sim_allocate_details = DB::table('sim_allocate')->whereRaw('FIND_IN_SET('.$sim_details->id.',sim)')->orderBy('allocate_id', 'desc')->first();
				$sales_rep = DB::table('sales_rep')->where('user_id', $sim_allocate_details->sales_rep_id)->first();

				if(count($sales_rep)){
					$sales_name = $sales_rep->firstname.' '.$sales_rep->surname;
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

		$shop = DB::table('shop')->select('shop_name', 'shop_id', 'status', 'route')->limit(50)->get();
		return view('areamanager/shop_month', array('title' => 'Month on Month', 'arealist' => $area, 'userlist' => $user, 'routelist' => $route, 'payment_mode_list' => $payment_mode, 'shop_type_list' => $shop_type, 'potentiallist' => $potential, 'shoplist' => $shop));
	}

	public function monthscroll(){
		$value = Input::get('value');
		$user_id = Input::get('user_id');
		
		if($user_id == ''){
			$shoplist = DB::table('shop')->select('shop_name', 'shop_id', 'status', 'route')->offset($value)->limit(50)->get();
		}
		else{
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
		$shoplist = DB::table('shop')->select('shop_name', 'shop_id', 'status', 'route')->whereIn('route', $explode_route)->offset($value)->limit(50)->get();
		}

		

		$output_shop='';
		
	      $i=$value+1;
	       
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
                <div class="col-lg-4 col-md-4 col-sm-4 col-4 row_text"><a href="'.URL::to('areamanager/shop_view_details'.'/'.base64_encode($shop->shop_id)).'">'.$shop->shop_name.'</a></div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-1 row_text">CC-'.$shop->shop_id.'</div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-3 row_text">'.$salesrep.'</div>
                '.$output_month_three.'
              </div>';
              $last_i = $i;
	          $i++;
	        }	        
	        	
	        $output_shop.='<div class="last_tr_'.$last_i.'"><div>';        
	      }
	      else{
	      	$last_i='';
	        $output_shop='
	        <div class="col-lg-12 text-center row_text" style="color:#f00">Shop Not Found</div>
	        ';
	      }


	      echo json_encode(array('output' => $output_shop, 'last_i' => $last_i));
	}
	public function report_shop_month_on_month()
	{
		$from = Input::get('from_date');
		$to = Input::get('to_date');

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->setTitle('Comco Report');

		$networks = DB::table('network')->get();
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Acc number');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Shop Name');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Address');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Area');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Route');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Sales Person Name');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'No of Shop Visit');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Total Supply Sim Month');
		$col = array("I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ");
		$colkey = 0;
		if(count($networks))
		{
			foreach($networks as $key => $network)
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$key].'1', $network->name);
				$colkey = $key + 1;
			}
		}
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$colkey].'1', 'Total Sim');


		$get_sims = DB::table('sim')
					->where('activity_date','>=',$from)
					->where('activity_date','<=',$to)
					->groupBy('shop_id')
					->orderBy('shop_id','desc')
					->get();
		if(count($get_sims))
		{
			foreach($get_sims as $shopkey => $sim)
			{
				$excel_index = $shopkey + 2;
				$shop_id = $sim->shop_id;
				$shop_details = DB::table('shop')->where('shop_id',$shop_id)->first();
				$address = '';
				$shop_name = '';
				$area_name = '';
				$route_name = '';
				$sales_person = '';
				$sim_count = 0;
				$shop_visit_count = 0;
				$shop_total_sim_count = 0;
				if(count($shop_details))
				{
					$shop_name = $shop_details->shop_name;
					if($shop_details->address1 != ""){ $address.= $shop_details->address1; }
					if($shop_details->address2 != ""){ $address.= ','.$shop_details->address2; }
					if($shop_details->address3 != ""){ $address.= ','.$shop_details->address3; }
					if($shop_details->city != ""){ $address.= ','.$shop_details->city; }
					if($shop_details->postcode != ""){ $address.= ','.$shop_details->postcode; }

					$area_details = DB::table('area')->where('area_id',$shop_details->area_name)->first();
					if(count($area_details))
					{
						$area_name = $area_details->area_name;
					}

					$route_details = DB::table('route')->where('route_id',$shop_details->route)->first();
					if(count($route_details))
					{
						$route_name = $route_details->route_name;
						$exp_sales_person = explode(',',$route_details->sales_rep_id);

						$sales_person_details = DB::table('sales_rep')->whereIn('sales_rep_id',$exp_sales_person)->get();
						if(count($sales_person_details))
						{
							foreach($sales_person_details as $sales)
							{
								if($sales_person == "")
								{
									$sales_person = $sales->firstname;
								}
								else{
									$sales_person = $sales_person.','.$sales->firstname;
								}
							}
						}
					}
					$shop_visit = DB::table('sim_allocate')->where('date','>=',$from)->where('date','<=',$to)->where('shop_id',$shop_id)->get();
					if(count($shop_visit))
					{
						$shop_visit_count = count($shop_visit);
						foreach($shop_visit as $visit)
						{
							if($visit->sim == "")
							{
								$sim_count = $sim_count + 0;
							}
							else{
								$exp_count = explode(',',$visit->sim);
								$sim_count = $sim_count + count($exp_count);
							}
						}
					}
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$excel_index, 'CC-'.$shop_details->shop_id);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$excel_index, $shop_name);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$excel_index, $address);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$excel_index, $area_name);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$excel_index, $route_name);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$excel_index, $sales_person);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$excel_index, $shop_visit_count);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$excel_index, $sim_count);
					if(count($networks))
					{
						foreach($networks as $key => $network)
						{
							$network_count = DB::table('sim')->where('activity_date','>=',$from)->where('activity_date','<=',$to)->where('shop_id',$shop_id)->where('network_id',$network->network_name)->count();

							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$key].$excel_index, $network_count);
							$shop_total_sim_count = $shop_total_sim_count + $network_count;
							$colkey = $key + 1;
						}
					}
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$colkey].$excel_index, $shop_total_sim_count);
				}
				else{
					$shop_name = "No Shop";
					$address = "";
					$area_name = "";
					$route_name = "";
					$sales_person = "";
					$shop_visit_count = 0;
					$sim_count = 0;
					
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$excel_index, '');
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$excel_index, $shop_name);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$excel_index, $address);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$excel_index, $area_name);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$excel_index, $route_name);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$excel_index, $sales_person);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$excel_index, $shop_visit_count);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$excel_index, $sim_count);

					if(count($networks))
					{
						foreach($networks as $key => $network)
						{
							$network_count = DB::table('sim')->where('activity_date','>=',$from)->where('activity_date','<=',$to)->where('shop_id',"")->where('network_id',$network->network_name)->count();

							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$key].$excel_index, $network_count);
							$shop_total_sim_count = $shop_total_sim_count + $network_count;
							$colkey = $key + 1;
						}
					}
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$colkey].$excel_index, $shop_total_sim_count);
				}
			}
		}

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$from_format = date('d-M-Y',strtotime($from));
		$to_format = date('d-M-Y',strtotime($to));

		$objWriter->save('papers/comco_report_'.$from_format.'_to_'.$to_format.'.xlsx');
		echo 'comco_report_'.$from_format.'_to_'.$to_format.'.xlsx';	
	}
	public function filterforshopmonth(){
		$user_id = Input::get('id');

		if($user_id == ''){
			$shoplist = DB::table('shop')->select('shop_name', 'shop_id', 'status', 'route')->limit(50)->get();
		}
		else{
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
		$shoplist = DB::table('shop')->select('shop_name', 'shop_id', 'status', 'route')->whereIn('route', $explode_route)->limit(50)->get();
		}

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
            <div class="col-lg-4 col-md-4 col-sm-4 col-4 row_text"><a href="'.URL::to('areamanager/shop_view_details'.'/'.base64_encode($shop->shop_id)).'">'.$shop->shop_name.'</a></div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-1 row_text">CC-'.$shop->shop_id.'</div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3 row_text">'.$salesrep.'</div>
            '.$output_month_three.'
          </div>';
          $last_i = $i;
          $i++;
        }	        
        	
        $output_shop.='<div class="last_tr_'.$last_i.'"><div>';        
      }
      else{
      	$last_i='';
        $output_shop='
        <div class="col-lg-12 text-center row_text" style="color:#f00">Shop Not Found</div>
        ';
      }


      echo json_encode(array('output' => $output_shop, 'last_i' => $last_i));
	}

	public function search(){
		$user_id = Session::get("areamanager_userid");
		$user = DB::table('sales_rep')->where('user_id', $user_id)->first();		
		$route = DB::table('route')->get();

		return view('areamanager/search', array('title' => 'Search', 'userdetails' => $user, 'routelist' => $route, 'user_id' => $user_id));
	}

	public function shoplistroute($id=""){
		$id = base64_decode($id);
		$user_id = Session::get("areamanager_userid");

		$user_details = DB::table('area_manager')->where('user_id', $user_id)->first();
        $explodearea = explode(',', $user_details->area);
        $arealist = DB::table('area')->whereIn('area_id', $explodearea)->get();

		$route_details = DB::table('route')->where('route_id', $id)->first();

		$payment_mode = DB::table('mode_payment')->where('status', 0)->get();
		$shop_type = DB::table('shop_type')->where('status', 0)->get();
		$potential = DB::table('potential_sale')->where('status', 0)->get();

		$shop = DB::table('shop')->where('route', $id)->orderBy('shop_name', 'ASC')->get();

		$explode_route = explode(',', $route_details->sales_rep_id);

		return view('areamanager/shop_list', array('title' => 'Manage Shop', 'userdetails' => $user_details, 'route_details' => $route_details, 'payment_mode_list' => $payment_mode, 'shop_type_list' => $shop_type, 'potentiallist' => $potential, 'shoplist' => $shop, 'user_id' => $user_id, 'route_id' => $id));

		
		
	}

	public function searchcommonareamanager(){
		$value = Input::get('value');
		$type = Input::get('type');
		$route_id = base64_decode(Input::get('route_id'));
		$user_id = Session::get('sales_userid');

		if($type == 1){
			$shoplist = DB::table('shop')->where('route', $route_id)->where('shop_name', 'like', '%'.$value.'%')->get();
		}
		if($type == 2){
			$shoplist = DB::table('shop')->where('route', $route_id)->where('shop_id', 'like', '%'.$value.'%')->get();
		}
		if($type == 3){
			$shoplist = DB::table('shop')->where('route', $route_id)->where('postcode', 'like', '%'.$value.'%')->get();
		}


		$shop_output='';
        $i=1;
        if(count($shoplist)){
          foreach ($shoplist as $shop) {

            if($shop->status == 0){
                $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Deactive" style="color: #33CC66"><i class="fas fa-check deactive_class" data-element="'.base64_encode($shop->shop_id).'"></i></a>';
            }
            else{
                $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Active" style="color: #E11B1C"><i class="fas fa-times active_class" data-element="'.base64_encode($shop->shop_id).'"></i></a>';
            }

            $edit_icon = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Area" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($shop->shop_id).'"></i></a>&nbsp;&nbsp;&nbsp;';

            if($shop->address2 != ''){
              $address2 = $shop->address2.'<br/>';
            }
            else{
              $address2 = $shop->address2;
            }
            if($shop->address3 != ''){
              $address3 = $shop->address3.'<br/>';
            }
            else{
              $address3 = $shop->address3;
            }

            $shop_output.='
            <tr>                  
              <td>
                <a href="'.URL::to('sales/shop_view_details/'.base64_encode($shop->shop_id)).'">
                  '.$shop->shop_name.'<br/>
                  CC-'.$shop->shop_id.'<br/>
                  '.$shop->address1.'<br/>
                  '.$address2.'
                  '.$address3.'
                  '.$shop->postcode.'<br/>

                </a>
              </td>
              
            </tr>';
            $i++;
          }
        }
        else{
          $shop_output='
          <tr>
            <td colspan="3" align="center">Empty</td>
          </tr>
          ';
        }

        echo json_encode(array('output_shop' => $shop_output));


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
					$routelist = DB::table('route')->where('area_id', $id)->where('status', 0)->get();
					

				}
				if($i==0){
					$output_salesrep ='<option value="">Sales REP not available 000</option>';

					$user_id = Session::get("areamanager_userid");
					$user_details = DB::table('area_manager')->where('user_id', $user_id)->first();
                    $explodearea = explode(',', $user_details->area);

					$routelist = DB::table('route')->whereIn('area_id', $explodearea)->where('status', 0)->get();
				}
				
			}			
			
		}
		elseif($type == 2){
			$sales_id = Input::get('salerep_id');
			$area_id = Input::get('area_id');

			if($sales_id != ''){
				$routelist = DB::table('route')->where('area_id', $area_id)->whereRaw('FIND_IN_SET('.$sales_id.',sales_rep_id)')->where('status', 0)->get();
			}
			else{
				$routelist = DB::table('route')->where('area_id', $area_id)->where('status', 0)->get();
			}

			$output_salesrep='';			
		}
		
		elseif($type == 3){

			$filter_id = Input::get('id');

			$user_id = Session::get("areamanager_userid");
			$user_details = DB::table('area_manager')->where('user_id', $user_id)->first();
            $explodearea = explode(',', $user_details->area);

			if($filter_id == ''){
	            $routelist = DB::table('route')->whereIn('area_id', $explodearea)->where('status', 0)->get();
			}
			else{
				$routelist = DB::table('route')->whereIn('area_id', $explodearea)->whereRaw('FIND_IN_SET('.$filter_id.',sales_rep_id)')->where('status', 0)->get();
			}

			

			$output_salesrep='';
			

		}

		$output_route='';
	      $i=1;
	      if(count($routelist)){
	        foreach ($routelist as $route) {

	            $explode_sales = explode(',', $route->sales_rep_id);
	            $area_salerep='';
	            if(count($explode_sales)){
	                foreach ($explode_sales as $sales) {
	                    $salesrep_details = DB::table('sales_rep')->where('user_id', $sales)->first();
	                    if($area_salerep == ''){
	                        $area_salerep = $salesrep_details->firstname;
	                    }
	                    else{
	                        $area_salerep  = $salesrep_details->firstname.', '.$area_salerep;
	                    }
	                    
	                }
	            }
	            else{
	                $area_salerep='';
	            }

	            $area_details = DB::table('area')->where('area_id',$route->area_id)->first();

	            $shop_count = DB::table('shop')->where('route', $route->route_id)->count();


	            

	            $output_route.='
	            <li>
	              <a href="'.URL::to('areamanager/shop_list_route/'.base64_encode($route->route_id)).'">
	                <div class="icon"><i class="fas fa-folder-open"></i></div>
	                <div class="text">'.$route->route_name.' ('.$shop_count.')</div>
	              </a>            
	            </li>
	            ';
	            $i++;
	        }
	      }
	      else{
	        $output_route='
	        <li>
	          <a href="javascript:">
	            <div class="icon"><i class="fas fa-folder-open"></i></div>
	            <div class="text">No Route</div>
	          </a>            
	        </li>
	        ';
	      }


		

		echo json_encode(array('output_salesrep' => $output_salesrep, 'output_route' => $output_route));
	}





}

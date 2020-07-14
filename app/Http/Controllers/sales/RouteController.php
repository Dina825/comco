<?php namespace App\Http\Controllers\sales;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use Session;
use URL;
use Hash;

class RouteController extends Controller {

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
	public function __construct()
	{
		$this->middleware('salesauth');		
		date_default_timezone_set("Europe/London");
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	
	public function route()
	{
		$user_id = Session::get('sales_userid');
		$user = DB::table('sales_rep')->where('user_id', $user_id)->first();
		
		$route = DB::table('route')->where('status',0)->get();

		return view('sales/route', array('title' => 'Manage Route', 'userdetails' => $user, 'routelist' => $route, 'user_id' => $user_id));
	}

	public function routeadd(){	

		$select_area = Input::get('select_area');
		$routename = Input::get('routename');	
		$sales_rep = base64_decode(Input::get('sales_rep'));
		
		$data['sales_rep_id'] = $sales_rep;
		$data['area_id'] = $select_area;
		$data['route_name'] = $routename;
		

		DB::table('route')->insert($data);

		return redirect::back()->with('message', 'Route was add succusfully');
	}
	
	public function routedetails(){
		$id = base64_decode(Input::get('id'));
		$type = Input::get('type');

		$route = DB::table('route')->where('route_id', $id)->first();
		//$details = DB::table('sales_rep')->where('user_id', $users->user_id)->first();

		
		if($type==1){
			$content = 'Are you sure want Active '.$route->route_name.' Route?';
			$title = 'Active Route';
			$status = $route->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==2){
			$content = 'Are you sure want Deactivate '.$route->route_name.' Route?';
			$title = 'Deactivate Route';
			$status = $route->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==3){

			$explode_sales = explode(',', $route->sales_rep_id);			
			$area_details = DB::table('area')->where('area_id', $route->area_id)->first();

			$salesreplist = DB::table('users')->where('user_type', 2)->get();

			

			echo json_encode(array('id' => base64_encode($id), 'area_name' => $area_details->area_id, 'route_name' => $route->route_name));
		}	

	}

	public function routestatus(){
		$id = base64_decode(Input::get('id'));
		$status = Input::get('status');

		if($status == 0){
			$statuschange = 1;
			DB::table('route')->where('route_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Route was successfully deactived');
		}
		if($status == 1){
			$statuschange = 0;
			DB::table('route')->where('route_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Route was successfully actived');
		}

	}

	public function routeupdate(){
		$id = base64_decode(Input::get('id'));

		$routename = Input::get('routename');	
		$sales_checkbox = Input::get('sales_checkbox_edit');

		$data['route_name'] = $routename;		

		DB::table('route')->where('route_id', $id)->update($data);
		return Redirect::back()->with('message', 'Route was successfully updated');

	}

	public function routeselectrep(){
		$id = Input::get('id');

		$userlist = DB::table('users')->where('user_type',2)->get();
		$output_result='<div class="col-lg-12 col-md-12 col-sm-12"><div class="form-group"><label>Choose Sales REP</label>';
		$i=0;
		if(count($userlist)){
			foreach ($userlist as $user) {				
				$salesrep_details = DB::table('sales_rep')->where('user_id', $user->user_id)->first();				
				$explode_area = explode(',', $salesrep_details->area);				
				if(in_array($id, $explode_area)){
					$output_result.='
					<label class="form_checkbox">'.$salesrep_details->firstname.'
					   <input type="checkbox" style="width:0.5px; height:0px"  name="sales_checkbox[]"  value="'.$salesrep_details->user_id.'">
					   <span class="checkmark_checkbox"></span>
					</label>';
					$i++;
				}	
			}
			if($i==0){
				$output_result.='<br/><input type="checkbox" style="width:0.5px; height:0px" value=""  name="sales_checkbox_empty[]"><b style="color:#f00;">Sales REP not available in this area.</b>';
			}
			$output_result.='<label id="sales_checkbox[]-error" class="error" style="display:none" for="sales_checkbox[]">Please choose atleast one Sales REP</label><div><div>';
		}
		else{
			$output_result='Empty';
		}

		echo json_encode(array('output_result' => $output_result));
	}	
	public function totalsim(){
		$type = Input::get('type');
		$id = base64_decode(Input::get('id'));
		$user_id = Session::get('sales_userid');		

		if($type == 1){
			$total_sim_user = DB::table('sim_allocate')->where('sales_rep_id', $user_id)->where('route_id',$id)->where('sim', '!=', '')->get();

			$sim_list='';
			if(count($total_sim_user)){
				foreach ($total_sim_user as $sim_user) {
					$explode_sim = explode(',', $sim_user->sim);
					if(count($explode_sim)){
						foreach ($explode_sim as $sim) {									
							if($sim_list == ''){
								$sim_list = $sim;
							}
							else{
								$sim_list = $sim.','.$sim_list;	
							}									
						}
					}
                    
				}
			}
			$route = DB::table('route')->where('route_id', $id)->first();
			$title = 'Total sim for Route '.$route->route_name;
		}

		$explode_sim_list = explode(',', $sim_list);

		$output='';
		$i=1;
		if(count($explode_sim_list)){
			foreach ($explode_sim_list as $single_sim) {
				$sim_details = DB::table('sim')->where('id', $single_sim)->first();

				if($sim_details->activity_date == '0000-00-00'){
					$status ='Inactive';
				}
				else{
					$status = 'Active('.date('d-M-Y', strtotime($sim_details->activity_date)).')';
				}

				$shop_details = DB::table('shop')->where('shop_id', $sim_details->shop_id)->first();

				if(count($shop_details)){
					$shop_name = $shop_details->shop_name.' CC-'.$shop_details->shop_id;
				}
				else{
					$shop_name = '';
				}

				$sales_name = '';
				if($sim_details->shop_id != ''){
					$sim_allocate = DB::table('sim_allocate')->where('sim', 'like', '%'.$sim_details->id.'%')->first();
					$sales_rep = DB::table('sales_rep')->where('user_id', $sim_allocate->sales_rep_id)->first();
					if(count($sales_rep)){
						$sales_name = $sales_rep->firstname;
					}
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
		      		<td>'.$shop_name.'</td>
		      		<td>'.$status.'</td>
		      	</tr>
				';
				$i++;
			}
		}
		else{
			$output='<tr>
		      		<td></td>
		      		<td></td>
		      		<td></td>
		      		<td align="center">Empty</td>
		      		<td></td>
		      		<td></td>
		      		<td></td>
		      	</tr>';
		}

		echo json_encode(array('output' => $output, 'title' => $title));
	}

	public function activesim(){
		$type = Input::get('type');
		$id = base64_decode(Input::get('id'));
		$user_id = Session::get('sales_userid');
		
		if($type == 1){
			$total_sim_user = DB::table('sim_allocate')->where('sales_rep_id', $user_id)->where('route_id',$id)->where('sim', '!=', '')->get();

			$sim_list='';
			if(count($total_sim_user)){
				foreach ($total_sim_user as $sim_user) {
					$explode_sim = explode(',', $sim_user->sim);
					if(count($explode_sim)){
						foreach ($explode_sim as $sim) {

							$active_sim = DB::table('sim')->where('id',$sim)->where('activity_date', '!=', '0000-00-00')->first();

							if(count($active_sim)){
								if($sim_list == ''){
								$sim_list = $sim;
								}
								else{
									$sim_list = $sim.','.$sim_list;	
								}	
							}

						}
					}
                    
				}
			}

			$route = DB::table('route')->where('route_id', $id)->first();
			$title = 'Active sim for Route '.$route->route_name;
		}

		$explode_sim_list = explode(',', $sim_list);


		$output='';
		$i=1;
		if(count($explode_sim_list)){
			foreach ($explode_sim_list as $single_sim) {
				$sim_details = DB::table('sim')->where('id', $single_sim)->first();
				

				if($sim_details->activity_date == '0000-00-00'){
					$status ='Inactive';
				}
				else{
					$status = 'Active('.date('d-M-Y', strtotime($sim_details->activity_date)).')';
				}

				$shop_details = DB::table('shop')->where('shop_id', $sim_details->shop_id)->first();

				if(count($shop_details)){
					$shop_name = $shop_details->shop_name.' CC-'.$shop_details->shop_id;
				}
				else{
					$shop_name = '';
				}

				$sales_name = '';
				if($sim_details->shop_id != ''){
					$sim_allocate = DB::table('sim_allocate')->where('sim', 'like', '%'.$sim_details->id.'%')->first();
					$sales_rep = DB::table('sales_rep')->where('user_id', $sim_allocate->sales_rep_id)->first();
					if(count($sales_rep)){
						$sales_name = $sales_rep->firstname;
					}
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
		      		<td>'.$shop_name.'</td>
		      		<td>'.$status.'</td>
		      	</tr>
				';
				$i++;
			}
		}
		else{
			$output='<tr>
		      		<td></td>
		      		<td></td>
		      		<td></td>
		      		<td align="center">Empty</td>
		      		<td></td>
		      		<td></td>
		      		<td></td>
		      	</tr>';
		}

		echo json_encode(array('output' => $output, 'title' => $title));
	}

	public function inactivesim(){
		$type = Input::get('type');
		$id = base64_decode(Input::get('id'));
		$user_id = Session::get('sales_userid');
		

		if($type == 1){
			$total_sim_user = DB::table('sim_allocate')->where('sales_rep_id', $user_id)->where('route_id',$id)->where('sim', '!=', '')->get();

			$sim_list='';
			if(count($total_sim_user)){
				foreach ($total_sim_user as $sim_user) {
					$explode_sim = explode(',', $sim_user->sim);
					if(count($explode_sim)){
						foreach ($explode_sim as $sim) {

							$active_sim = DB::table('sim')->where('id',$sim)->where('activity_date', '0000-00-00')->first();

							if(count($active_sim)){
								if($sim_list == ''){
								$sim_list = $sim;
								}
								else{
									$sim_list = $sim.','.$sim_list;	
								}	
							}

						}
					}
                    
				}
			}
			$route = DB::table('route')->where('route_id', $id)->first();
			$title = 'Inactive sim for Route '.$route->route_name;
		}

		$explode_sim_list = explode(',', $sim_list);


		$output='';
		$i=1;
		if(count($explode_sim_list)){
			foreach ($explode_sim_list as $single_sim) {
				$sim_details = DB::table('sim')->where('id', $single_sim)->first();
				

				if($sim_details->activity_date == '0000-00-00'){
					$status ='Inactive';
				}
				else{
					$status = 'Active('.date('d-M-Y', strtotime($sim_details->activity_date)).')';
				}

				$shop_details = DB::table('shop')->where('shop_id', $sim_details->shop_id)->first();

				if(count($shop_details)){
					$shop_name = $shop_details->shop_name.' CC-'.$shop_details->shop_id;
				}
				else{
					$shop_name = '';
				}

				$sales_name = '';
				if($sim_details->shop_id != ''){
					$sim_allocate = DB::table('sim_allocate')->where('sim', 'like', '%'.$sim_details->id.'%')->first();
					$sales_rep = DB::table('sales_rep')->where('user_id', $sim_allocate->sales_rep_id)->first();
					if(count($sales_rep)){
						$sales_name = $sales_rep->firstname;
					}
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
		      		<td>'.$shop_name.'</td>
		      		<td>'.$status.'</td>
		      	</tr>
				';
				$i++;
			}
		}
		else{
			$output='<tr>
		      		<td></td>
		      		<td></td>
		      		<td></td>
		      		<td align="center">Empty</td>
		      		<td></td>
		      		<td></td>
		      		<td></td>
		      	</tr>';
		}

		echo json_encode(array('output' => $output, 'title' => $title));


	}



	public function filterroute(){
		$id = base64_decode(Input::get('id'));
		$user_id = Session::get('sales_userid');

		if($id != ''){
			$route_list = DB::table('route')->where('area_id', $id)->where('sales_rep_id', 'like', '%'.$user_id.'%')->get();
			$i=1;
			$output_route='';
			if(count($route_list)){
				foreach ($route_list as $route) {
					$explode_sales = explode(',', $route->sales_rep_id);                         

		              $area_details = DB::table('area')->where('area_id',$route->area_id)->first();
		              $salesrep_details = DB::table('sales_rep')->where('user_id', $user_id)->first();

		              

		              if($route->status == 0){
		                  $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Deactive" style="color: #33CC66"><i class="fas fa-check deactive_class" data-element="'.base64_encode($route->route_id).'"></i></a>';
		              }
		              else{
		                  $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Active" style="color: #E11B1C"><i class="fas fa-times active_class" data-element="'.base64_encode($route->route_id).'"></i></a>';
		              }

		              $total_sim_user = DB::table('sim_allocate')->where('sales_rep_id', $user_id)->where('route_id', $route->route_id)->where('sim', '!=', '')->get();

	                  $total_sim='';                        
	                  $total_active_sim='';
	                  $total_inactive_sim='';
	                  if(count($total_sim_user)){
	                    foreach ($total_sim_user as $sim_user) {
	                      $explode_sim = explode(',', $sim_user->sim);
	                      $total_sim = $total_sim+count($explode_sim);
	                      
	                      if(count($explode_sim)){
	                        foreach ($explode_sim as $sim) {

	                          $active_sim = DB::table('sim')->where('id',$sim)->where('activity_date', '!=', '0000-00-00')->where('shop_id', $sim_user->shop_id)->first();

	                          $inactive_sim = DB::table('sim')->where('id',$sim)->where('activity_date', '0000-00-00')->where('shop_id', $sim_user->shop_id)->first();

	                          if(count($active_sim)){
	                            $total_active_sim = $total_active_sim+1 ;
	                          }

	                          if(count($inactive_sim)){
	                            $total_inactive_sim = $total_inactive_sim+1 ;
	                          }

	                        }                              
	                      }

	                    }
	                  }

	                  $total_shop = DB::table('sim_allocate')->where('sales_rep_id', $user_id)->where('route_id', $route->route_id)->groupBy('shop_id')->get();

	                  if(count($total_shop)){
	                    $total_shop = count($total_shop);
	                  }
	                  else{
	                    $total_shop = '';
	                  }


					$output_route.='
						<tr>
			              <td>'.$i.'</td>
			              <td>'.$route->route_name.'</td>
			              <td>'.$salesrep_details->firstname.'</td>
			              <td>'.$area_details->area_name.'</td>                          
			              <td align="center"><a href="javascript:" class="total_sim"  data-element="'.base64_encode($route->route_id).'">'.$total_sim.'</a></td>
			              <td align="center"><a href="javascript:" class="active_sim"  data-element="'.base64_encode($route->route_id).'">'.$total_active_sim.'</a></td>
			              <td align="center"><a href="javascript:" class="inactive_sim"  data-element="'.base64_encode($route->route_id).'">'.$total_inactive_sim.'</a></td>                          
			              <td align="center">'.$total_shop.'</td>
			              <td align="center">
			              <a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Area" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($route->route_id).'"></i></a>&nbsp;&nbsp;&nbsp;    
			              '.$status.'
			              </td>
			            </tr>';
			            $i++;
				}
			}
			else{
	            $output_route='
	            <tr>
	              <td></td>
	              <td></td>
	              <td></td>
	              <td></td>
	              <td align="center">Empty</td>                      
	              <td></td>
	              <td></td>
	              <td></td>                                  
	              <td></td>
	            </tr>
	            ';
	          }

			echo json_encode(array('output_result' => $output_route));
		}
		else{
			$user_details = DB::table('sales_rep')->where('user_id', $user_id)->first();
			$area_route_list = explode(',', $user_details->area);  
			
			$output_route='';     
                  $i=1;             
                  
                  if(count($area_route_list)){
                    foreach ($area_route_list as $area_route) {                     

                      $route_list = DB::table('route')->where('area_id', $area_route)->get();
                      
                      if(count($route_list)){
                        foreach ($route_list as $route) {
                          $explode_sales = explode(',', $route->sales_rep_id);                         

                          $area_details = DB::table('area')->where('area_id',$route->area_id)->first();
                          $salesrep_details = DB::table('sales_rep')->where('user_id', $user_id)->first();

                          

                          if($route->status == 0){
                              $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Deactive" style="color: #33CC66"><i class="fas fa-check deactive_class" data-element="'.base64_encode($route->route_id).'"></i></a>';
                          }
                          else{
                              $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Active" style="color: #E11B1C"><i class="fas fa-times active_class" data-element="'.base64_encode($route->route_id).'"></i></a>';
                          }
                          $explode_rep_id = explode(',', $route->sales_rep_id);

                          $total_sim_user = DB::table('sim_allocate')->where('sales_rep_id', $user_id)->where('route_id', $route->route_id)->where('sim', '!=', '')->get();

                          $total_sim='';                        
                          $total_active_sim='';
                          $total_inactive_sim='';
                          if(count($total_sim_user)){
                            foreach ($total_sim_user as $sim_user) {
                              $explode_sim = explode(',', $sim_user->sim);
                              $total_sim = $total_sim+count($explode_sim);
                              
                              if(count($explode_sim)){
                                foreach ($explode_sim as $sim) {

                                  $active_sim = DB::table('sim')->where('id',$sim)->where('activity_date', '!=', '0000-00-00')->where('shop_id', $sim_user->shop_id)->first();

                                  $inactive_sim = DB::table('sim')->where('id',$sim)->where('activity_date', '0000-00-00')->where('shop_id', $sim_user->shop_id)->first();

                                  if(count($active_sim)){
                                    $total_active_sim = $total_active_sim+1 ;
                                  }

                                  if(count($inactive_sim)){
                                    $total_inactive_sim = $total_inactive_sim+1 ;
                                  }

                                }                              
                              }

                            }
                          }

                          $total_shop = DB::table('sim_allocate')->where('sales_rep_id', $user_id)->where('route_id', $route->route_id)->groupBy('shop_id')->get();

                          if(count($total_shop)){
                            $total_shop = count($total_shop);
                          }
                          else{
                            $total_shop = '';
                          }

                          if(in_array($user_id, $explode_rep_id)){                            
                              $output_route.='
                                <tr>
                                  <td>'.$i.'</td>
                                  <td>'.$route->route_name.'</td>
                                  <td>'.$salesrep_details->firstname.'</td>
                                  <td>'.$area_details->area_name.'</td>                          
                                  <td align="center"><a href="javascript:" class="total_sim"  data-element="'.base64_encode($route->route_id).'">'.$total_sim.'</a></td>
                                  <td align="center"><a href="javascript:" class="active_sim"  data-element="'.base64_encode($route->route_id).'">'.$total_active_sim.'</a></td>
                                  <td align="center"><a href="javascript:" class="inactive_sim"  data-element="'.base64_encode($route->route_id).'">'.$total_inactive_sim.'</a></td>                          
                                  <td align="center">'.$total_shop.'</td>
                                  <td align="center">
                                  <a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Area" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($route->route_id).'"></i></a>&nbsp;&nbsp;&nbsp;    
                                  '.$status.'
                                  </td>
                                </tr>
                                ';
                                $i++;                            
                          }

                        }
                      }
                    }
                    if($i==1){
                      $output_route='
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td align="center">Empty</td>                      
                          <td></td>
                          <td></td>
                          <td></td>                                  
                          <td></td>
                        </tr>
                        ';
                    }
                  }
                  else{
                    $output_route='
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td align="center">Empty</td>                      
                      <td></td>
                      <td></td>
                      <td></td>                                  
                      <td></td>
                    </tr>
                    ';
                  }
                  echo json_encode(array('output_result' => $output_route));
		}
		
		
		

	}


}

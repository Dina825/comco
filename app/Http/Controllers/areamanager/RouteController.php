<?php namespace App\Http\Controllers\areamanager;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
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
	
	public function route()
	{
		$user_id = Session::get("areamanager_userid");
		
		return view('areamanager/route', array('title' => 'Manage Route', 'user_id' => $user_id));
	}

	public function routeadd(){	

		$select_area = Input::get('select_area');
		$routename = Input::get('routename');	
		$sales_checkbox = Input::get('sales_checkbox');
		
		$data['sales_rep_id'] = implode(',', $sales_checkbox);
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

			$output_salesrep='<div class="col-lg-12 col-md-12 col-sm-12"><div class="form-group"><label>Choose Sales REP</label>';
            if(count($salesreplist)){
              foreach ($salesreplist as $sales) {
              	$sales_details = DB::table('sales_rep')->where('user_id', $sales->user_id)->first();
              	$checked = '';
              	if(count($explode_sales)){
              		foreach ($explode_sales as $explode) {              			
              			if($sales->user_id == $explode ){
              				$checked = 'checked';
              			}
              		}
              		
              	}
              	$explode_area = explode(',', $sales_details->area);

              	if(in_array($route->area_id, $explode_area)){              	
	                $output_salesrep.='
	                
			          	<label class="form_checkbox">'.$sales_details->firstname.'
				            <input type="checkbox" '.$checked.' value="'.$sales_details->user_id.'" style="width:1px; height:1px" name="sales_checkbox_edit[]"  required>
				            <span class="checkmark_checkbox"></span>
				        </label>
	                ';
	            }
              }
              $output_salesrep.='
                                    <label id="sales_checkbox_edit[]-error" class="error" style="display: none;" for="sales_checkbox_edit[]" style="display: inline-block;">Please choose atleast one area</label>
                                    
                                ';
            }
            else{
              $output_salesrep='<div class="col-lg-12">Empty</div>';
            }

			echo json_encode(array('id' => base64_encode($id), 'area_name' => $area_details->area_id, 'route_name' => $route->route_name, 'output_salesrep' => $output_salesrep ));
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
		$area_id = Input::get('select_area');
		
		$data['sales_rep_id'] = implode(',', $sales_checkbox);		
		$data['route_name'] = $routename;
		$data['area_id'] = $area_id;

		DB::table('route')->where('route_id', $id)->update($data);

		$shop_list = DB::table('shop')->select('route', 'shop_id')->where('route',$id)->get();
		if(count($shop_list)){
			foreach ($shop_list as $shop) {
				$dataarea['area_name'] = $area_id;
				DB::table('shop')->where('shop_id', $shop->shop_id)->update($dataarea);
			}
		}



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
	public function filterroute(){
		$id = Input::get('id');
		$type = Input::get('type');

		if($type == 1){
			$userlist = DB::table('users')->where('user_type',2)->get();
			$output_selesrep='<option value="">Please Select Sales REP</option>';
			$i=0;
			if(count($userlist)){
				foreach ($userlist as $user) {				
					$salesrep_details = DB::table('sales_rep')->where('user_id', $user->user_id)->first();				
					$explode_area = explode(',', $salesrep_details->area);				
					if(in_array($id, $explode_area)){
						$output_selesrep.='
						<option value="'.$salesrep_details->user_id.'">'.$salesrep_details->firstname.'</option>';
						$i++;
					}
					$routelist = DB::table('route')->where('area_id', $id)->get();
				}
				if($i==0){
					$output_selesrep ='<option value="">Sales REP not available in this area.</option>';
					if($id != ''){
						$routelist = DB::table('route')->where('area_id', $id)->get();
					}
					else{
						$user_id = Session::get("areamanager_userid");
						$user_details = DB::table('area_manager')->where('user_id', $user_id)->first();
                        $explodearea = explode(',', $user_details->area);

						$routelist = DB::table('route')->whereIn('area_id', $explodearea)->get();
					}
				}
				
			}
			else{
				$output_selesrep='<option value="">Empty</option>';
			}
		}
		elseif($type == 2){
			$area_id = Input::get('area_id');
			if($id != ''){
				$routelist = DB::table('route')->where('area_id', $area_id)->where('sales_rep_id', 'like', '%'.$id.'%')->get();
			}
			else{
				$routelist = DB::table('route')->where('area_id', $area_id)->get();
			}
			$output_selesrep='';
			
			
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

            
            

            


            

            $output_route.='
            <tr>
              <td>'.$i.'</td>
              <td>'.$route->route_name.'</td>
              <td>'.$area_salerep.'</td>
              <td>'.$area_details->area_name.'</td>                          
              <td align="center" class="total_'.$route->route_id.'"></td>
              <td align="center" class="active_'.$route->route_id.'"></td>
              <td align="center" class="inactive_'.$route->route_id.'"></td>
              <td align="center" class="shop_'.$route->route_id.'"></td>
              <td><a href="javascript:"><i class="fas fa-sync-alt refresh_icon" data-element="'.base64_encode($route->route_id).'"></i></a></td>
              <td align="center">
              <a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Route" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($route->route_id).'"></i></a>&nbsp;&nbsp;&nbsp;    
              
              </td>
            </tr>
            ';
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
          <td></td>
        </tr>
        ';
      }



		

		echo json_encode(array('output_selesrep' => $output_selesrep, 'output_route' => $output_route));
	}

	public function routeselectrepedit(){
		$id = Input::get('id');
		$route_id = base64_decode(Input::get('route_id'));

		$already_route = DB::table('route')->where('route_id', $route_id)->first();
		$explode_already = explode(',', $already_route->sales_rep_id);

		$userlist = DB::table('users')->where('user_type',2)->get();
		$output_result='<div class="col-lg-12 col-md-12 col-sm-12"><div class="form-group"><label>Choose Sales REP</label>';
		$i=0;
		if(count($userlist)){
			foreach ($userlist as $user) {				
				$salesrep_details = DB::table('sales_rep')->where('user_id', $user->user_id)->first();				
				$explode_area = explode(',', $salesrep_details->area);				
				

				if(in_array($id, $explode_area)){
					
					if(in_array($user->user_id, $explode_already)){	
						$checked = 'checked';
					}
					else{
						$checked = '';
					}
					$output_result.='
					<label class="form_checkbox">'.$salesrep_details->firstname.'
					   <input type="checkbox" style="width:0.5px; height:0px" '.$checked.'  name="sales_checkbox_edit[]"  value="'.$salesrep_details->user_id.'">
					   <span class="checkmark_checkbox"></span>
					</label>';
					$i++;

				}	
			}
			if($i==0){
				$output_result.='<br/><input type="checkbox" style="width:0.5px; height:0px" value=""  name="sales_checkbox_empty[]"><b style="color:#f00;">Sales REP not available in this area.</b>';
			}
			$output_result.='<label id="sales_checkbox_edit[]-error" class="error" style="display:none" for="sales_checkbox_edit[]">Please choose atleast one Sales REP</label><div><div>';
		}
		else{
			$output_result='Empty';
		}

		echo json_encode(array('output_result' => $output_result));
	}

	public function refreshroute (){
		$route_id = base64_decode(Input::get('id'));

		$shop_list = DB::table('shop')->where('route', $route_id)->get();

		$total_sim = DB::table('shop')->join('sim', 'shop.shop_id', '=', 'sim.shop_id')->select('sim.*','shop.shop_name')->where('shop.route', $route_id)->count();
		$total_active_sim = DB::table('shop')->join('sim', 'shop.shop_id', '=', 'sim.shop_id')->select('sim.*','shop.shop_name')->where('shop.route', $route_id)->where('sim.activity_date', '!=', '0000-00-00')->count();

		$total_inactive_sim = DB::table('shop')->join('sim', 'shop.shop_id', '=', 'sim.shop_id')->select('sim.*','shop.shop_name')->where('shop.route', $route_id)->where('sim.activity_date', '0000-00-00')->count();

		

        if($total_sim != 0){
        	$total_sim = $total_sim;
        }
        else{
        	$total_sim = '';
        }

        if($total_active_sim != 0){
        	$total_active_sim = $total_active_sim;
        }
        else{
        	$total_active_sim = '';
        }

        if($total_inactive_sim != 0){
        	$total_inactive_sim = $total_inactive_sim;
        }
        else{
        	$total_inactive_sim = '';
        }

        $output_shop = count($shop_list);

        if($output_shop != 0){
        	$output_shop = $output_shop;
        }
        else{
        	$output_shop = '';
        }



        $output_total = '<a href="javascript:" class="total_sim"  data-element="'.base64_encode($route_id).'">'.$total_sim.'</a>';
        $output_active ='<a href="javascript:" class="active_sim"  data-element="'.base64_encode($route_id).'">'.$total_active_sim.'</a>';
        $output_inactive ='<a href="javascript:" class="inactive_sim"  data-element="'.base64_encode($route_id).'">'.$total_inactive_sim.'</a>';


		
        


        echo json_encode(array('route_id' => $route_id, 'total_shop' => $output_shop, 'total' => $output_total, 'active' => $output_active, 'inactive' => $output_inactive));
	}

	public function totalsim(){
		$type =Input::get('type');
		$route_id = base64_decode(Input::get('id'));

		$sim_list_details = DB::table('shop')->join('sim', 'shop.shop_id', '=', 'sim.shop_id')->select('sim.*','shop.shop_name')->where('shop.route', $route_id)->get();
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

		$route = DB::table('route')->where('route_id', $route_id)->first();
		$title = 'Total sim for Route '.$route->route_name;
		echo json_encode(array('output' => $output, 'title' => $title));
	}

	public function activesimroute(){
		$type =Input::get('type');
		$route_id = base64_decode(Input::get('id'));

		$sim_list_details = DB::table('shop')->join('sim', 'shop.shop_id', '=', 'sim.shop_id')->select('sim.*','shop.shop_name')->where('shop.route', $route_id)->where('sim.activity_date', '!=', '0000-00-00')->get();
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

		$route = DB::table('route')->where('route_id', $route_id)->first();
		$title = 'Active sim for Route '.$route->route_name;
		echo json_encode(array('output' => $output, 'title' => $title));
	}

	public function inactivesimroute(){
		$type =Input::get('type');
		$route_id = base64_decode(Input::get('id'));

		$sim_list_details = DB::table('shop')->join('sim', 'shop.shop_id', '=', 'sim.shop_id')->select('sim.*','shop.shop_name')->where('shop.route', $route_id)->where('sim.activity_date', '0000-00-00')->get();
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

		$route = DB::table('route')->where('route_id', $route_id)->first();
		$title = 'Inactive sim for Route '.$route->route_name;
		echo json_encode(array('output' => $output, 'title' => $title));
	}

	

}

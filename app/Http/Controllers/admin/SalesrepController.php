<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
use URL;
use Hash;
class SalesrepController extends Controller {

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
	
	public function salesrep()
	{
		$user = DB::table('users')->where('user_type', 2)->get();
		$area = DB::table('area')->where('status', 0)->get();
		return view('admin/salesrep', array('title' => 'Manage Sales REP', 'arealist' => $area, 'userlist' => $user));
	}

	public function salesrepadd(){	

			
		$email_id = Input::get('email_id');
		$password = Input::get('password');
		$password_hash = Hash::make($password);

		$data['user_type'] = 2;
		$data['email_id'] = $email_id;
		$data['password'] = $password_hash;
		$data['account_created'] = date('Y-m-d h:i:s');

		$id = DB::table('users')->insertGetId($data);
		$area_checkbox = Input::get('area_checkbox');
		
		$datasalesrep['area'] = implode(',', $area_checkbox);
		$datasalesrep['user_id'] = $id;
		$datasalesrep['firstname'] = Input::get('firstname');
		$datasalesrep['surname'] = Input::get('surename');
		$datasalesrep['personal_email'] = Input::get('personal_email');
		$datasalesrep['address1'] = Input::get('addressone');
		$datasalesrep['address2'] = Input::get('addresstwo');
		$datasalesrep['address3'] = Input::get('addressthree');
		$datasalesrep['city'] = Input::get('city');
		$datasalesrep['postcode'] = Input::get('postcode');
		$datasalesrep['mobile_number'] = Input::get('mobile');

		DB::table('sales_rep')->insert($datasalesrep);

		return redirect::back()->with('message', 'Sales REP was add succusfully');
	}
	
	public function salesrepdetails(){
		$id = base64_decode(Input::get('id'));
		$type = Input::get('type');

		$users = DB::table('users')->where('user_id', $id)->first();
		$details = DB::table('sales_rep')->where('user_id', $users->user_id)->first();

		
		if($type==1){
			$content = 'Are you sure want Active '.$details->firstname.' Sales REP?';
			$title = 'Active Sales REP';
			$status = $users->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==2){
			$content = 'Are you sure want Deactivate '.$details->firstname.' Sales REP?';
			$title = 'Deactivate Sales REP';
			$status = $users->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==3){

			$explode_area = explode(',', $details->area);			
			$arealist = DB::table('area')->where('status', 0)->get();

			$output_area='';
            if(count($arealist)){
              foreach ($arealist as $area) {
              	$checked = '';
              	if(count($explode_area)){
              		foreach ($explode_area as $explode) {              			
              			if($area->area_id == $explode ){
              				$checked = 'checked';
              			}
              		}
              		
              	}
              	
                $output_area.='
                <div class="col-lg-3 col-md-6 col-sm-12 col-12">
		          	<label class="form_checkbox">'.$area->area_name.'
			            <input type="checkbox" '.$checked.' value="'.$area->area_id.'" style="width:1px; height:1px" name="edit_area_checkbox[]"  required>
			            <span class="checkmark_checkbox"></span>
			        </label>
                </div>';
              }
              $output_area.='<div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <label id="edit_area_checkbox[]-error" class="error" style="display: none;" for="edit_area_checkbox[]" style="display: inline-block;">Please choose atleast one area</label>
                                    
                                </div>';
            }
            else{
              $output_area='<div class="col-lg-12">Empty</div>';
            }

			echo json_encode(array('id' => base64_encode($id), 'firstname' => $details->firstname, 'surname' => $details->surname, 'personal_email' => $details->personal_email, 'address1' => $details->address1, 'address2' => $details->address2, 'address3' => $details->address3, 'city' => $details->city, 'postcode' => $details->postcode, 'mobile_number' => $details->mobile_number, 'email_id' => $users->email_id, 'output_area' => $output_area));
		}	

	}

	public function salesrepstatus(){
		$id = base64_decode(Input::get('id'));
		$status = Input::get('status');

		if($status == 0){
			$statuschange = 1;
			DB::table('users')->where('user_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Sales REP was successfully deactived');
		}
		if($status == 1){
			$statuschange = 0;
			DB::table('users')->where('user_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Sales REP was successfully actived');
		}

	}

	public function salesrepupdate(){
		$id = base64_decode(Input::get('id'));
		$area_checkbox = Input::get('edit_area_checkbox');
		$data['area'] = implode(',', $area_checkbox);		
		$data['firstname'] = Input::get('firstname');
		$data['surname'] = Input::get('surename');
		$data['personal_email'] = Input::get('personal_email');
		$data['address1'] = Input::get('addressone');
		$data['address2'] = Input::get('addresstwo');
		$data['address3'] = Input::get('addressthree');
		$data['city'] = Input::get('city');
		$data['postcode'] = Input::get('postcode');
		$data['mobile_number'] = Input::get('mobile');

		$datausers['email_id'] = Input::get('email_id');
		$password = Input::get('password');
		if($password != ''){
			$password_hash = Hash::make($password);
			$datausers['password'] = $password_hash;
		}

		DB::table('users')->where('user_id', $id)->update($datausers);
		DB::table('sales_rep')->where('user_id', $id)->update($data);
		return Redirect::back()->with('message', 'Sales REP was successfully updated');

	}

	public function filterforsalesrep(){
		$area_id = Input::get('id');		
		$userlist = DB::table('sales_rep')->get();

		  $output_salesrep='';
	      $i=1;
	      if(count($userlist)){
	        foreach ($userlist as $user) {
	            $user_details = DB::table('users')->where('user_id',$user->user_id)->first();
	            $explode_area = explode(',', $user->area);

	            $area_for_salesrep='';
	            if(count($explode_area)){
	                foreach ($explode_area as $area) {
	                    $area_details = DB::table('area')->where('area_id', $area)->first();
	                    if($area_for_salesrep == ''){
	                        $area_for_salesrep = $area_details->area_name;
	                    }
	                    else{
	                        $area_for_salesrep  = $area_details->area_name.', '.$area_for_salesrep;
	                    }
	                    
	                }
	            }
	            else{
	                $area_for_salesrep='';
	            }

	            if($user_details->status == 0){
	                $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Deactive" style="color: #33CC66"><i class="fas fa-check deactive_class" data-element="'.base64_encode($user->user_id).'"></i></a>';
	            }
	            else{
	                $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Active" style="color: #E11B1C"><i class="fas fa-times active_class" data-element="'.base64_encode($user->user_id).'"></i></a>';
	            }

	            $total_sim_user = DB::table('sim_allocate')->where('sales_rep_id', $user->user_id)->where('sim', '!=', '')->get();

	            

	            $total_route = DB::table('sim_allocate')->where('sales_rep_id', $user->user_id)->groupBy('route_id')->get();

	            if(count($total_route)){
	              $total_route = count($total_route);
	            }
	            else{
	              $total_route = '';
	            }

	            $total_shop = DB::table('sim_allocate')->where('sales_rep_id', $user->user_id)->groupBy('shop_id')->get();

	            if(count($total_shop)){
	              $total_shop = count($total_shop);
	            }
	            else{
	              $total_shop = '';
	            }

	            if($area_id != ''){
	            	if(in_array($area_id, $explode_area)){
			            $output_salesrep.='
			            <tr>
			              <td>'.$i.'</td>
			              <td>'.$user->firstname.'</td>
			              <td>'.$user_details->email_id.'</td>
			              <td>'.$area_for_salesrep.'</td>
			              <td align="center" class="total_'.$user->user_id.'"></td>
			              <td align="center" class="active_'.$user->user_id.'"></td>
			              <td align="center" class="inactive_'.$user->user_id.'"></td>
			              <td align="center"><a href="javascript:"><i class="fas fa-sync-alt refresh_icon" data-element="'.base64_encode($user->user_id).'"></i></a></td>
			              <td align="center">'.$total_route.'</td>
			              <td align="center">'.$total_shop.'</td>
			              <td align="center">
			              <a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Sales REP" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($user->user_id).'"></i></a>&nbsp;&nbsp;&nbsp;
			              '.$status.'&nbsp;&nbsp;&nbsp;
			              <a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Sim Allocation"><i class="fas fa-sim-card sim_allocation"  data-element="'.base64_encode($user->user_id).'"></i></a> &nbsp;&nbsp;&nbsp;  

			              <a href="'.URL::to('admin/view_stock/'.base64_encode($user->user_id)).'" data-toggle="tooltip" data-placement="top" data-original-title="View Stock of Month"><i class="far fa-calendar-alt"></i></a>
			              &nbsp;&nbsp;&nbsp;

                          <a href="'.URL::to('admin/time_sheet/'.base64_encode($user->user_id)).'" data-toggle="tooltip" data-placement="top" data-original-title="Time Sheet"><i class="fas fa-clock"></i></a>

			              </td>
			            </tr>
			            ';
			            $i++;
			        }
	            }
	            else{
	            	$output_salesrep.='
			            <tr>
			              <td>'.$i.'</td>
			              <td>'.$user->firstname.'</td>
			              <td>'.$user_details->email_id.'</td>
			              <td>'.$area_for_salesrep.'</td>
			              <td align="center" class="total_'.$user->user_id.'"></td>
			              <td align="center" class="active_'.$user->user_id.'"></td>
			              <td align="center" class="inactive_'.$user->user_id.'"></td>
			              <td align="center"><a href="javascript:"><i class="fas fa-sync-alt refresh_icon" data-element="'.base64_encode($user->user_id).'"></i></a></td>
			              <td align="center">'.$total_route.'</td>
			              <td align="center">'.$total_shop.'</td>
			              <td align="center">
			              <a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Sales REP" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($user->user_id).'"></i></a>&nbsp;&nbsp;&nbsp;
			              '.$status.'&nbsp;&nbsp;&nbsp;
			              <a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Sim Allocation"><i class="fas fa-sim-card sim_allocation"  data-element="'.base64_encode($user->user_id).'"></i></a> &nbsp;&nbsp;&nbsp;  

			              <a href="'.URL::to('admin/view_stock/'.base64_encode($user->user_id)).'" data-toggle="tooltip" data-placement="top" data-original-title="View Stock of Month"><i class="far fa-calendar-alt"></i></a>   

			              </td>
			            </tr>
			            ';
			            $i++;
	            }

	            		        
	        }
	        if($i==1){
	        	$output_salesrep='
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
	      }
	      else{
	        $output_salesrep='
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

	      echo json_encode(array('output_salesrep' => $output_salesrep));

	}

	public function timesheet($id=""){		
		$sales_id=array('sales_id' => base64_decode($id));
		Session::put($sales_id);
		$user_id = Session::get("sales_id");

		$current_date = date("Y-m-d");		
		$user = DB::table('sales_rep')->where('user_id', $user_id)->first();
		$today_href='';

		return view('admin/time_sheet', array('title' => 'Time Sheet', 'userdetails' => $user, 'user_id' => $user_id, 'date_current' => $current_date, 'today_href' => $today_href ));
	}

	public function timesheetprevious($id=""){
		$user_id = Session::get("sales_id");
		$current_date = base64_decode($id);		
		$user = DB::table('sales_rep')->where('user_id', $user_id)->first();		

		return view('admin/time_sheet', array('title' => 'Time Sheet', 'userdetails' => $user, 'user_id' => $user_id, 'date_current' => $current_date, 'today_href' => $id ));
	}
	public function salesreplocation(){
		$user_id = Session::get("sales_id");
		$current_date = Input::get('id');

		$location_details = DB::table('sim_allocate')->where('sales_rep_id', $user_id)->where('date', $current_date)->orderBy('time', 'asc')->get();



		$output='';
		$i=1;
		if(count($location_details)){
			foreach ($location_details as $location) {
				$shop = DB::table('shop')->where('shop_id', $location->shop_id)->first();				

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

                if($location->sim != ''){
                	$status = 'SIM Allocated';
                }
                else{
                	$status = 'Visit Only';
                }



                $map_address = '';
                if($location->latitude != ''){
                	$lat = $location->latitude;
					$lng = $location->longitude;

					$geocode = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=false&key=AIzaSyCYe-0d24ubmrrh5sZHF0ak9-nTGXMaV3o";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $geocode);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					$response = curl_exec($ch);
					curl_close($ch);
					$outputval = json_decode($response);
				    $dataarray = get_object_vars($outputval);
				    if ($dataarray['status'] != 'ZERO_RESULTS' && $dataarray['status'] != 'INVALID_REQUEST') {
				        if (isset($dataarray['results'][0]->formatted_address)) {

				            $map_address = $dataarray['results'][0]->formatted_address;

				        } else {
				            $map_address = 'Not Found';

				        }
				    } else {
				        $map_address = 'Not Found';
				    }
                }
                else{
                	$map_address = 'Not Found';
                }

                

                if($location->sim == ''){
                	$sim_counts = '0';
                }
                else{
                	$explode_sim = explode(',', $location->sim);
                	$sim_counts = count($explode_sim);
                }
                
                

                $output.='<tr>
				<td>'.$i.'</td>				
				<td><b>'.$shop->shop_name.'</b><br/>
				CC-'.$shop->shop_id.' '.$shop->address1.' '.$address2.' '.$address3.' '.$shop->postcode.'
				</td>
				<td>'.date('h:i A', strtotime($location->time)).'</td>
				<td>'.$map_address.'</td>
				<td>'.$sim_counts.'</td>
				<td>'.$status.'</td>
				</tr>';				
				$i++;
			}
		}
		else{
			$output='<tr>
			<td></td>
			<td></td>
			<td align="right">Empty</td>
			<td></td>
			<td></td>
			<td></td>
			</tr>';
		}

		$location_return = DB::table('return_sim')->where('sales_rep_id', $user_id)->where('date', $current_date)->orderBy('time', 'asc')->get();

		$output_return='';
		$i=1;
		if(count($location_return)){
			foreach ($location_return as $return) {
				$shop = DB::table('shop')->where('shop_id', $return->shop_id)->first();				

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

                $status = 'Return SIM';



                $map_address = '';
                if($return->latitude != ''){
                	$lat = $return->latitude;
					$lng = $return->longitude;

					$geocode = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=false&key=AIzaSyCYe-0d24ubmrrh5sZHF0ak9-nTGXMaV3o";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $geocode);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					$response = curl_exec($ch);
					curl_close($ch);
					$outputval = json_decode($response);
				    $dataarray = get_object_vars($outputval);
				    if ($dataarray['status'] != 'ZERO_RESULTS' && $dataarray['status'] != 'INVALID_REQUEST') {
				        if (isset($dataarray['results'][0]->formatted_address)) {

				            $map_address = $dataarray['results'][0]->formatted_address;

				        } else {
				            $map_address = 'Not Found';

				        }
				    } else {
				        $map_address = 'Not Found';
				    }
                }
                else{
                	$map_address = 'Not Found';
                }

                if($return->sim == ''){
                	$return_sim_counts = '0';
                }
                else{
                	$explode_sim = explode(',', $return->sim);
                	$return_sim_counts = count($explode_sim);
                }


                $output_return.='<tr>
				<td>'.$i.'</td>
				<td><b>'.$shop->shop_name.'</b><br/>
				CC-'.$shop->shop_id.' '.$shop->address1.' '.$address2.' '.$address3.' '.$shop->postcode.'
				</td>
				<td>'.date('h:i A', strtotime($return->time)).'</td>
				<td>'.$map_address.'</td>
				<td>'.$return_sim_counts.'</td>
				<td>'.$status.'</td>
				</tr>';				
				$i++;
			}
		}
		else{
			$output_return='<tr>
					<td></td>
					<td></td>
					<td align="right">Empty</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>';

		}



		echo json_encode(array('output' => $output, 'output_return' => $output_return));
	}
	public function refreshsalesrep(){
		$user_id = base64_decode(Input::get('id'));
		
		$total_sim_user = DB::table('sim_allocate')->select('sales_rep_id', 'sim', 'date', 'shop_id')->where('sales_rep_id', $user_id)->where('sim', '!=', '')->get();

        $total_sim='';                        
        $total_active_sim='';
        $total_inactive_sim='';
        $sim_array = array();
        $exp_sim = '';
        if(count($total_sim_user)){
          foreach ($total_sim_user as $keyval => $sim_user) {
            $explode_sim = explode(',', $sim_user->sim);
            $check_sims = DB::table('sim')->select('id','shop_id')->whereIn('id',$explode_sim)->get();
			if(count($check_sims))
			{
				foreach($check_sims as $sims)
				{
					if($sim_user->shop_id == $sims->shop_id)
					{
						if($exp_sim == "")
						{
							$exp_sim = $sims->id;
						}
						else{
							$exp_sim = $exp_sim.','.$sims->id;
						}
					}
				}
			}
		  }
		}
			$implode_sim = $exp_sim;
            if($implode_sim != ""){
            	$explode = explode(',',$implode_sim);

            	$total = DB::table('sim')->whereIn('id',$explode)->count();
            	$active_sim = DB::table('sim')->whereIn('id',$explode)->where('activity_date', '!=', '0000-00-00')->count();
                $inactive_sim = DB::table('sim')->whereIn('id',$explode)->where('activity_date', '0000-00-00')->count();


                if($total > 0){
	            	$total_sim = $total_sim + $total;
	            }
	            if($active_sim > 0){
	            	$total_active_sim = $total_active_sim + $active_sim;
	            }
	            if($inactive_sim > 0){
	            	$total_inactive_sim = $total_inactive_sim + $inactive_sim;
	            }
	            
            }


        $output_total = '<a href="javascript:" class="total_sim"  data-element="'.base64_encode($user_id).'">'.$total_sim.'</a>';
        $output_active ='<a href="javascript:" class="active_sim"  data-element="'.base64_encode($user_id).'">'.$total_active_sim.'</a>';
        $output_inactive ='<a href="javascript:" class="inactive_sim"  data-element="'.base64_encode($user_id).'">'.$total_inactive_sim.'</a>';

        echo json_encode(array('user_id' => $user_id, 'total' => $output_total, 'active' => $output_active, 'inactive' => $output_inactive));
	}
	public function salesrepsimtotal(){
		$user_id = base64_decode(Input::get('id'));
		$type = Input::get('type');
		$sim_allocate = DB::table('sim_allocate')->select('sales_rep_id', 'sim', 'date','shop_id')->where('sales_rep_id', $user_id)->where('sim', '!=', '')->get();
		$sim_list='';
		$simrow_month = array();
		$sim_array = array();
		if(count($sim_allocate)){
			foreach ($sim_allocate as $single_sim) {
				$explode_date = explode('-', $single_sim->date);
				$explodee_sim = explode(',', $single_sim->sim);
				$check_sims = DB::table('sim')->select('id','shop_id')->whereIn('id',$explodee_sim)->get();
				$exp_sim = '';
				if(count($check_sims))
				{
					foreach($check_sims as $sims)
					{
						if($single_sim->shop_id == $sims->shop_id)
						{
							if($exp_sim == "")
							{
								$exp_sim = $sims->id;
							}
							else{
								$exp_sim = $exp_sim.','.$sims->id;
							}
						}
					}
				}
				$implode_sim = $exp_sim;

				if($implode_sim != "")
				{
					$month_year = $explode_date[0].'-'.$explode_date[1];
					if(isset($simrow_month[$month_year]))
					{
						if($simrow_month[$month_year] == "")
						{
							$simrow_month[$month_year] = $implode_sim;
						}
						else{
							$simrow_month[$month_year] = $simrow_month[$month_year].','.$implode_sim;
						}
					}
					else{
						$simrow_month[$month_year] = $implode_sim;
					}
				}
			}
		}
		krsort($simrow_month);
		// foreach($simrow_month as $key => $sim)
		// {
		// 	$exp = explode(',',$sim);
		// 	echo '<pre>';
		// 	print_r($exp);
		// }
		// exit;
		$output_count='';
		if(count($simrow_month)){
			foreach ($simrow_month as $key => $simrow) {
				$explode_sim = explode(',', $simrow);
				$output_count.='
				<style>
				.table td, .table th{padding:0.25rem 0.50rem}
				</style>
				<table class="table table-striped margin_top_20"><thead class="thead-dark"><tr><th colspan="2" align="center" style="text-align:center">'.date('M-Y', strtotime($key)).'</th></tr></thead><tbody>';				
				$networklist = DB::table('network')->get();
				$sim_list='';
				$sub_total='';
				if(count($networklist)){
					foreach ($networklist as $network) {
						$total=0;
						if(count($explode_sim)){
							$sim_details = DB::table('sim')->select('id','network_id', 'shop_id')->whereIn('id', $explode_sim)->where('network_id',$network->network_name)->count();
							$total = $total+$sim_details;							
						}
						$output_count.='<tr>
								<td>'.$network->network_name.'</td>
								<td>'.$total.'</td>
							</tr>';
						$sub_total = $total+$sub_total;
					}
					$output_count.='<tr style="background:#dadada; font-weight:bold;"><td>Total</td><td>'.$sub_total.'</td></tr></tbody></table>';
				}
			}
		}
		$user_details = DB::table('sales_rep')->where('user_id', $user_id)->first();

		$title = 'Total SIM for '.$user_details->firstname.' '.$user_details->surname;

		echo json_encode(array('output' => $output_count, 'title' => $title));
	}

	

	public function salesrepsiminactive(){
		$user_id = base64_decode(Input::get('id'));
		$type = Input::get('type');

		$sim_allocate = DB::table('sim_allocate')->select('sales_rep_id', 'sim', 'date','shop_id')->where('sales_rep_id', $user_id)->where('sim', '!=', '')->get();
		$sim_list='';
		$simrow_month = array();
		$sim_array = array();
		if(count($sim_allocate)){
			foreach ($sim_allocate as $single_sim) {
				$explode_date = explode('-', $single_sim->date);
				$explodee_sim = explode(',', $single_sim->sim);
				$check_sims = DB::table('sim')->select('id','shop_id')->whereIn('id',$explodee_sim)->get();
				$exp_sim = '';
				if(count($check_sims))
				{
					foreach($check_sims as $sims)
					{
						if($single_sim->shop_id == $sims->shop_id)
						{
							if($exp_sim == "")
							{
								$exp_sim = $sims->id;
							}
							else{
								$exp_sim = $exp_sim.','.$sims->id;
							}
						}
					}
				}
				$implode_sim = $exp_sim;

				if($implode_sim != "")
				{
					$month_year = $explode_date[0].'-'.$explode_date[1];
					if(isset($simrow_month[$month_year]))
					{
						if($simrow_month[$month_year] == "")
						{
							$simrow_month[$month_year] = $implode_sim;
						}
						else{
							$simrow_month[$month_year] = $simrow_month[$month_year].','.$implode_sim;
						}
					}
					else{
						$simrow_month[$month_year] = $implode_sim;
					}
				}
			}
		}

		krsort($simrow_month);

		$output_count='';
		
		if(count($simrow_month)){
			foreach ($simrow_month as $key => $simrow) {

				$explode_sim = explode(',', $simrow);
				$output_count.='
				<style>
				.table td, .table th{padding:0.25rem 0.50rem}
				</style>
				<table class="table table-striped margin_top_20"><thead class="thead-dark"><tr><th colspan="2" align="center" style="text-align:center">'.date('M-Y', strtotime($key)).'</th></tr></thead><tbody>';
				
				$networklist = DB::table('network')->get();
				$sim_list='';
				$sub_total=0;
				if(count($networklist)){
					foreach ($networklist as $network) {
						$total=0;
						if(count($explode_sim)){
							$sim_details = DB::table('sim')->select('id','network_id','activity_date', 'shop_id')->whereIn('id', $explode_sim)->where('network_id',$network->network_name)->where('activity_date', '0000-00-00')->count();

							$total = $total+$sim_details;
						}
						$output_count.='
							<tr>
								<td>'.$network->network_name.'</td>
								<td>'.$total.'</td>
							</tr>
						';

						$sub_total = $total+$sub_total;
					}
					$output_count.='<tr  style="background:#dadada; font-weight:bold;"><td>Total</td><td>'.$sub_total.'</td></tr></tbody></table>';
				}
			}
		}
		$user_details = DB::table('sales_rep')->where('user_id', $user_id)->first();

		$title = 'Inactive SIM for '.$user_details->firstname.' '.$user_details->surname;

		echo json_encode(array('output' => $output_count, 'title' => $title));
	}
	public function salesrepsimactive(){
		$user_id = base64_decode(Input::get('id'));
		$type = Input::get('type');

		$sim_allocate = DB::table('sim_allocate')->select('sales_rep_id', 'sim', 'date')->where('sales_rep_id', $user_id)->where('sim', '!=', '')->get();

		$sim_list='';		
		
		if(count($sim_allocate)){
			foreach ($sim_allocate as $single_sim) {
				if($sim_list == ''){
					$sim_list = $single_sim->sim;
				}
				else{
					$sim_list = $sim_list.','.$single_sim->sim;	
				}
			}
		}
		$explode_simlist = explode(',', $sim_list);
		
		$active_sim_month='';
		$simrow_month = array();
		if(count($explode_simlist)){
			foreach ($explode_simlist as $sim) {
				$sim_details = DB::table('sim')->select('id','activity_date')->where('id', $sim)->where('activity_date', '!=', '0000-00-00')->first();
				if(count($sim_details)){
					$explode_date = explode('-', $sim_details->activity_date);				
					$month_year = $explode_date[0].'-'.$explode_date[1];
					if(isset($simrow_month[$month_year]))
					{
						if($simrow_month[$month_year] == "")
						{
							$simrow_month[$month_year] = $sim_details->id;
						}
						else{
							$simrow_month[$month_year] = $simrow_month[$month_year].','.$sim_details->id;
						}
					}
					else{
						$simrow_month[$month_year] = $sim_details->id;
					}
				}
			}





			
		}

		krsort($simrow_month);		
		$output_count='';
		
		if(count($simrow_month)){
			foreach ($simrow_month as $key => $simrow) {

				$explode_sim = explode(',', $simrow);
				$output_count.='
				<style>
				.table td, .table th{padding:0.25rem 0.50rem}
				</style>
				<table class="table table-striped margin_top_20"><thead class="thead-dark"><tr><th colspan="2" align="center" style="text-align:center">'.date('M-Y', strtotime($key)).'</th></tr></thead><tbody>';
				
				$networklist = DB::table('network')->get();
				$sim_list='';
				$sub_total='';
				if(count($networklist)){
					foreach ($networklist as $network) {
						$total='';
						if(count($explode_sim)){
							$sim_details = DB::table('sim')->whereIn('id', $explode_sim)->where('network_id',$network->network_name)->where('activity_date', '!=', '0000-00-00')->where('shop_id', '!=', '')->get();

							if(count($sim_details)){
								$total = $total+count($sim_details);	
							}
							
						}
						$output_count.='
							<tr>
								<td>'.$network->network_name.'</td>
								<td>'.$total.'</td>
							</tr>
						';

						$sub_total = $total+$sub_total;
					}
					$output_count.='<tr  style="background:#dadada; font-weight:bold;"><td>Total</td><td>'.$sub_total.'</td></tr></tbody></table>';
				}
			}
		}
		$user_details = DB::table('sales_rep')->where('user_id', $user_id)->first();

		$title = 'Active SIM for '.$user_details->firstname.' '.$user_details->surname;

		echo json_encode(array('output' => $output_count, 'title' => $title));
	}

	

}

<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
use Hash;
class AdminController extends Controller {

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
	
	public function dashboard()
	{
		$simlist = DB::table('sim')->groupBy('import_date')->get();
		return view('admin/dashboard', array('title' => 'Dashboard', 'simlist' => $simlist));
	}

	public function logout(){
		Session::forget("admin_userid");
		return redirect('/admin/');
	}

	public function useremailcheck(){
		if(isset($_GET['user_id']))
		{			
			$user_id = base64_decode(Input::get('user_id'));
			$email = Input::get('email_id');
			$validate = DB::table('users')->where('email_id', $email)->where('user_id','!=',$user_id)->count();
		}	
		else{			
			$email = Input::get('email_id');
			$validate = DB::table('users')->where('email_id', $email)->count();
		}
		if($validate != 0 )
			$valid = false;
		else
			$valid = true;
		echo json_encode($valid);
		exit;
	}

	public function setting()
	{
		$admin = DB::table('admin')->first();
		return view('admin/setting', array('title' => 'Setting', 'admin' => $admin));
	}

	public function settingupdate(){
		$type = Input::get('type');
		if($type == 1){
			$password = Input::get('password');
			$password_hash = Hash::make($password);
			$data['password'] = $password_hash;
			DB::table('admin')->update($data);
			return redirect::back()->with('message_login', 'Admin password was successfully updated');
		}
		if($type == 2){
			$data['facebook'] = Input::get('facebook');
			$data['twitter'] = Input::get('twitter');
			$data['linkedin'] = Input::get('linkedin');
			$data['instagram'] = Input::get('instagram');
			DB::table('admin')->update($data);
			return redirect::back()->with('message_social', 'Social Media Links was successfully updated');
		}
		if($type == 3){
			$data['email'] = Input::get('email');			
			DB::table('admin')->update($data);
			return redirect::back()->with('message_email', 'Email setting was successfully updated');
		}
		if($type == 4){
			$data['keywords'] = Input::get('keywords');
			$data['description'] = Input::get('description');
			DB::table('admin')->update($data);
			return redirect::back()->with('message_seo', 'SEO setting was successfully updated');
		}
		if($type == 5){
			$data['minimum_value'] = Input::get('minimum');
			DB::table('admin')->update($data);
			return redirect::back()->with('message_mv', 'Minimum value setting was successfully updated');
		}
		if($type == 6){
			$network_minimum = Input::get('network_minimum_value');
			$networks = DB::table('network')->where('status',0)->orderBy('network_id','asc')->get();
			if(count($networks))
			{
				foreach($networks as $key => $network)
				{
					$data['minimum_value'] = $network_minimum[$key];
					DB::table('network')->where('network_id',$network->network_id)->update($data);
				}
			}
			return redirect::back()->with('message_mnv', 'Minimum value setting was successfully updated');
		}
		if($type == 7){			
			$data['vat_percentage'] = Input::get('vat');
			DB::table('admin')->update($data);
			return redirect::back()->with('message_vat', 'VAT percentage setting was successfully updated');
		}
	}

	public function adminpasswordcheck(){
		$password = Input::get('opassword');
		$admin_details = DB::table('admin')->first();		

		$admin_details = DB::table('admin')->first();		
		if(count($admin_details))
		{
			if(Hash::check($password,$admin_details->password))
				{
					$valid = true;
				}
				else{
					$valid = false;
				}
		}
		else{
			$valid = false;
		}

		echo json_encode($valid);
		exit;


	}

	public function checksimscan(){
		$value = Input::get('value');		
		$sim_details = DB::table('sim')->where('ssn', $value)->first();

		if(count($sim_details)){
			$sim_message ='';			
			$type = 1;

			if($sim_details->activity_date != '0000-00-00'){
				$status = 'Active';
				$activity_date = date('d-M-Y', strtotime($sim_details->activity_date));
			}
			else{
				$status = 'Inactive';
				$activity_date = '';
			}

			if($sim_details->shop_id != ''){
				$shop_details = DB::table('shop')->where('shop_id', $sim_details->shop_id)->first();
				$shop_name = $shop_details->shop_name;
				
				$sim_allocate = DB::table('sim_allocate')->whereRaw('FIND_IN_SET('.$sim_details->id.',sim)')->orderBy('date', 'DESC')->orderBy('time', 'DESC')->first();

				$sales_rep = DB::table('sales_rep')->where('user_id', $sim_allocate->sales_rep_id)->first();

				$sales_rep_name = $sales_rep->firstname.' '.$sales_rep->surname;
				$sim_allocate_date = date('d-M-Y', strtotime($sim_allocate->date));
				$sim_allocate_time = date('h:i A', strtotime($sim_allocate->time));
				$account_id = 'CC-'.$shop_details->shop_id;
				$route_details = DB::table('route')->where('route_id', $shop_details->route)->first();
				$route = $route_details->route_name;
				
			}
			else{
				$shop_name='';
				$sales_rep_name = '';
				$sim_allocate_date='';
				$account_id='';
				$sim_allocate_time='';
				$route = '';
			}


			
			$result ='
				<tr>
					<th>SSN</th>
					<td>'.$sim_details->ssn.'</td>
				</tr>			
				<tr>
					<th>Network Id</th>
					<td>'.$sim_details->network_id.'</td>
				</tr>
				<tr>
					<th>Product Id</th>
					<td>'.$sim_details->product_id.'</td>
				</tr>
				<tr>
					<th>Status</th>
					<td>'.$status.'</td>
				</tr>
				<tr>
					<th>SIM Activity Date</th>
					<td>'.$activity_date.'</td>
				</tr>
				<tr>
					<th>Shop Account Number</th>
					<td>'.$account_id.'</td>
				</tr>
				<tr>
					<th>Shop Name</th>
					<td>'.$shop_name.'</td>
				</tr>
				<tr>
					<th>Route</th>
					<td>'.$route.'</td>
				</tr>
				<tr>
					<th>Sales REP</th>
					<td>'.$sales_rep_name.'</td>
				</tr>
				<tr>
					<th>SIM allocation date for Shop</th>
					<td>'.$sim_allocate_date.'</td>
				</tr>
				<tr>
					<th>SIM allocation time for Shop</th>
					<td>'.$sim_allocate_time.'</td>
				</tr>
			';
		}
		else{
			$sim_message = $value. ' SSN not available in COMCO database';
			$type = 2;
			$result='';
		}

		echo json_encode(array('message' => $sim_message, 'type' => $type, 'result' => $result));
	}

	public function admin_query_check(){
		$commission_manager = DB::table('commission_manager')->get();
		$ssn_list='';
		if(count($commission_manager)){
			foreach ($commission_manager as $commission) {
				$ssn_count = DB::table('commission_manager')->select('ssn', 'month', 'topup_no')->where('ssn', $commission->ssn)->where('month', $commission->month)->where('topup_no', $commission->topup_no)->count();

				if($ssn_count > 1){
					if($ssn_list != '') {						
						$expldoe_ssn = explode(',', $ssn_list);						
						if(!in_array($commission->ssn,$expldoe_ssn )){
							$ssn_list = $ssn_list.','.$commission->ssn;
						}
						
					}
					else{
						$ssn_list = $commission->ssn;
					}
				}
			}
		}

		print_r($ssn_list);
		exit;


	}	
}

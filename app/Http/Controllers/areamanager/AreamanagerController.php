<?php namespace App\Http\Controllers\areamanager;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
class AreamanagerController extends Controller {

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
		$this->middleware('areamangerauth');		
		date_default_timezone_set("Europe/London");
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	
	public function dashboard()
	{
		$user_id = Session::get("areamanager_userid");
		return view('areamanager/dashboard', array('title' => 'Dashboard', 'user_id' => $user_id));
	}

	public function logout(){
		Session::forget("areamanager_userid");
		return redirect('/areamanager/');
	}

	public function checksim()
	{
		$user_id = Session::get("areamanager_userid");
		return view('areamanager/check_sim', array('title' => 'Check SIM', 'user_id' => $user_id));
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

	



	
	
}

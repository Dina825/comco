<?php namespace App\Http\Controllers\areamanager;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use DB;
use Response;
use Mail;
use Session;
use PHPMailer;
use Auth;
use Hash;

class AreamanagerauthenticateController extends Controller {

	public function __construct()
	{
	    $this->flag = 0;
		$this->middleware('areamanagerredirect', ['except' => 'getLogout']);
		date_default_timezone_set("Europe/London");
	}
	public function index()
	{
		return view('areamanager/login');
	}

	public function login()
	{
		
		$username = Input::get('userid');
		$password = Input::get('password');

		$user_details = DB::table('users')->where('email_id',$username)->where('user_type', '1')->first();
		if(count($user_details))
		{
			if(Hash::check($password,$user_details->password))
				{
					if($user_details->status != 0)
					{
						return redirect('areamanager')->withInput()->with('message','Your Account has been deactivated.Please contact your admin.');
					}
					else{

						$sessn=array('areamanager_userid' => $user_details->user_id);
						Session::put($sessn); 
						return redirect('/areamanager/dashboard');

						/*$user_id = $user_details->user_id;
						$current_date = date('Y-m-d');
						$day_check = DB::table('end_day')->where('sales_id', $user_id)->where('end_date', $current_date)->first();

						if(count($day_check)){
							return redirect('sales')->withInput()->with('message','Today your session was closed. Please login tomorrow. ');
						}
						else{
							$sessn=array('sales_userid' => $user_details->user_id);
							Session::put($sessn); 
							return redirect('/sales/dashboard');
						}*/
						
					}
				}
				else{
					return redirect('areamanager')->withInput()->with('message','Invalid Username or Password');
				}
		}
		else{
			return redirect('areamanager')->withInput()->with('message','Please Check your login Credentials');
		}

		

		
	}
}

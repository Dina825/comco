<?php namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use DB;
use Response;
use Mail;
use Session;
use PHPMailer;
use Hash;

class AdminauthenticateController extends Controller {

	public function __construct()
	{
	    $this->flag = 0;
		$this->middleware('adminredirect', ['except' => 'getLogout']);
	}
	public function index()
	{
		return view('admin/login');
	}

	public function login()
	{
		
		/*$username = Input::get('userid');
		$password = base64_encode(Input::get('password'));
		$array = array('username' => $username, 'password' => $password);
		$check_login = DB::table('admin')->where($array)->first();
		if(count($check_login))
		{
			$session = array('admin_userid' => $check_login);
			Session::put($session);
			return redirect('/admin/dashboard');
		}
		else{
			return redirect('/admin')->with('message', 'login failed');
		}*/

		$username = Input::get('userid');
		$password = Input::get('password');
		
		$admin_details = DB::table('admin')->first();
		$pin = $admin_details->password;

		if ((Hash::check($password, $pin)) && ($username == $admin_details->username)) {
			$details = [];
			$admin = DB::table('admin')->where('id',$admin_details->id)->first();  
			if(!empty($admin))
			{
				$details = $admin;
			}
			if(!empty($details))
			{	
				$sessn=array('admin_userid' => $details->id);
				Session::put($sessn); 
				return redirect('/admin/dashboard');
			}
			else
			{
				return redirect('/admin')->withInput()->with('message','Invalid Username or Password');
			}
		}
		else{
			return redirect('/admin')->withInput()->with('message','Invalid Username or Password');
		}
	}
}

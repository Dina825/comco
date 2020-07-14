<?php namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use DB;
use Response;
use Mail;
use Session;
use PHPMailer;
use URL;

class UserauthenticateController extends Controller {

	public function __construct()
	{
		$this->middleware('userredirect', ['except' => 'getLogout']);	
			
	}
	public function index()
	{
		
		return view('user/home', array('title' => 'Welcome'));
	}
	public function loginuser(){
		$username = Input::get('user_id');
		$password = base64_encode(Input::get('password'));		

		$array = array('email_id' => $username, 'password' => $password);		
		$check_login = DB::table('delivery_boy')->where($array)->first();		
		
		if(count($check_login))
		{
			if($check_login->status == 0){
				$session = array('delivery_user_id' => $check_login->id);
				Session::put($session);
				return redirect('/delivery/ongoing');
			}
			else{
				return redirect('/')->with('login_error', 'Your account was deactivated. Please contact admin.');
			}
			
		}
		else{
			return redirect('/')->with('login_error', 'Username and password incorrect.');
		}
	}
}

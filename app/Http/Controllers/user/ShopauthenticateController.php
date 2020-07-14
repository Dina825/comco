<?php namespace App\Http\Controllers\user;

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

class ShopauthenticateController extends Controller {

	public function __construct()
	{
	    $this->flag = 0;
		$this->middleware('shopredirect', ['except' => 'getLogout']);
		date_default_timezone_set("Europe/London");
	}
	

	public function login()
	{
		
		$username = Input::get('userid');
		$password = Input::get('password');

		$user_details = DB::table('shop')->where('email_id',$username)->first();
		if(count($user_details))
		{
			if(Hash::check($password,$user_details->password))
				{
					$sessn=array('shop_userid' => $user_details->shop_id);
					Session::put($sessn); 
					return redirect('/shop/my_account');
				}
				else{
					return redirect('/')->withInput()->with('login_error','Invalid Username or Password');
				}
		}
		else{
			return redirect('/')->withInput()->with('login_error','Please Check your login Credentials');
		}

		

		
	}
}

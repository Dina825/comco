<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
use Hash;
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
	public function __construct()
	{
		$this->middleware('shopauth');		
		date_default_timezone_set("Europe/London");
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	
	public function myaccount()
	{
		$user_id = Session::get("shop_userid");
		$shop_details = DB::table('shop')->where('shop_id', $user_id)->first();
		$route_details = DB::table('route')->where('route_id', $shop_details->route)->first();
		
		return view('shop/my_account', array('title' => 'My Account', 'user_id' => $user_id, 'shop_details' => $shop_details, 'route_details' => $route_details));
	}

	public function logout(){
		Session::forget("shop_userid");
		return redirect('/');
	}

	public function passwordcheck(){
		$user_id = Session::get("shop_userid");

		$password = Input::get('opassword');
		$shop_details = DB::table('shop')->where('shop_id', $user_id)->first();		

		
		if(count($shop_details))
		{
			if(Hash::check($password,$shop_details->password))
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

	public function passwordupdate(){
		$user_id = Session::get("shop_userid");

		$password = Input::get('password');
		$password_hash = Hash::make($password);
		$data['password'] = $password_hash;
		DB::table('shop')->where('shop_id', $user_id)->update($data);
		return redirect::back()->with('message_login', 'Shop password was successfully updated');
	}

	public function shopcommission(){
		$shop_id = Session::get("shop_userid");
		

		$get_shops = DB::table('shop_commission')->where('shop_id',$shop_id)->get();
		$shop_details = DB::table('shop')->where('shop_id', $shop_id)->first();				
		
		return view('shop/commission', array('title' => 'Commission', 'shops' => $get_shops, 'shop_id' => $shop_id, 'shop_details' => $shop_details));

	}

	


	
	
}

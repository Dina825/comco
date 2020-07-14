<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use URL;
use App\User;

use Session;
class UserController extends Controller {

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
		date_default_timezone_set("Asia/Kolkata");
		
	}

	public function about(){		
		return view('user/about', array('title' => 'About Us'));
	}

	public function service(){		
		return view('user/service', array('title' => 'Services'));
	}

	public function why_choose_us(){		
		return view('user/why_choose_us', array('title' => 'Why Choose Us'));
	}

	public function career(){		
		return view('user/career', array('title' => 'Career'));
	}

	public function products(){		
		return view('user/products', array('title' => 'Products'));
	}

	public function contact(){		
		return view('user/contact', array('title' => 'Get in Touch '));
	}

	public function terms_conditions(){		
		return view('user/terms_conditions', array('title' => 'Terms and Conditions'));
	}

	public function delivery_return(){		
		return view('user/delivery_return', array('title' => 'Delivery & Returns'));
	}

	public function privacy_policy(){		
		return view('user/privacy_policy', array('title' => 'Privacy Policy'));
	}
}

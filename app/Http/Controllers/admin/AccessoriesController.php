<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
use Hash;
class AccessoriesController extends Controller {

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
		require_once(app_path('Http/helpers.php'));
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	
	public function accessories()
	{
		
		return view('admin/accessories', array('title' => 'Accessories'));
	}

	public function accessoriessetting()
	{
		$admin = DB::table('admin')->first();
		return view('admin/accessories_setting', array('title' => 'Accessories Setting', 'admin' => $admin));
	}
	public function orderhistory($id=""){		

		$history = DB::table('order_confirm')->where('shop_orders', '0')->orderBy('date', 'DESC')->orderBy('time', 'DESC')->get();

		return view('admin/order_history', array('title'=> 'Order history', 'historylist' => $history));
	}

	public function orderdetails($id=""){				
		$order_id = base64_decode(Input::get('order_id'));
		$orders = DB::table('order_confirm')->where('order_id', $order_id)->first();
		
		return view('admin/order_details', array('title'=> 'Order history', 'orders' => $orders));
		
	}

	public function salesreppayments($id=""){	
		$user_id = $id;
		$salesrep = DB::table('sales_rep')->where('user_id', base64_decode($user_id))->first();
		$received_payment = DB::table('sales_rep_payments')->where('salesrepid', base64_decode($user_id))->where('status', '1')->get();

		$inhand_payment = DB::table('sales_rep_payments')->where('salesrepid', base64_decode($user_id))->where('status', '0')->get();

		return view('admin/sales_rep_payments', array('title' => 'Sales REP Payments', 'user_id' => $user_id, 'salesrep' => $salesrep, 'received_payment' => $received_payment, 'inhand_payment' => $inhand_payment));
	}

	public function salesreppaymentadd(){
		$data['salesrepid'] = base64_decode(Input::get('user_id'));
		$data['payment'] = Input::get('amount');
		$data['transaction_details'] = Input::get('transaction');
		$data['date'] = date('Y-m-d');
		$data['time'] = date('h:i');
		$data['description'] = 'Admin add payment';
		$data['status'] = '1';

		DB::table('sales_rep_payments')->insert($data);

		return redirect::back()->with('message', 'Payment was successfully added.');
	}

	public function salesreppaymentdetails(){
		$id = base64_decode(Input::get('id'));
		$details = DB::table('sales_rep_payments')->where('payment_id', $id)->first();

		echo json_encode(array('amount' => $details->payment, 'transaction' => $details->transaction_details, 'id' => base64_encode($details->payment_id)));
	}

	public function salesreppaymentupdate(){
		$id = base64_decode(Input::get('id'));
		$data['payment'] = Input::get('amount');
		$data['transaction_details'] = Input::get('transaction');

		DB::table('sales_rep_payments')->where('payment_id', $id)->update($data);

		return redirect::back()->with('message', 'Payment was successfully added.');
	}

	
	
}

<?php namespace App\Http\Controllers\sales;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use Session;
use URL;
use Hash;

class StockController extends Controller {

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
		$this->middleware('salesauth');		
		date_default_timezone_set("Europe/London");
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	
	public function startdaystock()
	{
		$current_date = date('Y-m-d');
		$user_id = Session::get('sales_userid');
		$user = DB::table('sales_rep')->where('user_id', $user_id)->first();		
		$network = DB::table('network')->where('status', 0)->get();

		

		return view('sales/start_day_stock', array('title' => 'Start Day Stock', 'userdetails' => $user, 'user_id' => $user_id, 'networklist' => $network));	
	}

	public function addstartdaystock(){
		$networklist = Input::get('network');
		$network_implode = implode(',', $networklist);
		
		$sales_id = base64_decode(Input::get('sales_id'));
		$date = date('Y-m-d');


		if(count($networklist)){
			foreach ($networklist as $key => $network) {
				$data_network[$network] = Input::get('product_'.$network);
				$data_quantity[$network] = Input::get('quantity_'.$network);
			}
			$dataval['product'] = serialize($data_network);
			$dataval['quantity'] = serialize($data_quantity);
			$dataval['sales_id'] = $sales_id;
			$dataval['start_date'] = $date;
			$dataval['network'] = $network_implode;
			DB::table('start_day')->insert($dataval);
		}

		return redirect('sales/dashboard')->with('message', 'Start day was updated');
	}

	public function enddaystock()
	{
		$current_date = date('Y-m-d');
		$user_id = Session::get('sales_userid');
		$user = DB::table('sales_rep')->where('user_id', $user_id)->first();		
		$network = DB::table('network')->where('status', 0)->get();

		$day_check = DB::table('start_day')->where('sales_id', $user_id)->where('start_date', $current_date)->first();	

		if(count($day_check)){
			return view('sales/end_day_stock', array('title' => 'End Day Stock', 'userdetails' => $user, 'user_id' => $user_id, 'networklist' => $network));			
		}
		else{
			return redirect('sales/start_day_stock')->with('message');			
		}		
	}

	public function addenddaystock(){
		$networklist = Input::get('network');
		$network_implode = implode(',', $networklist);
		
		$sales_id = base64_decode(Input::get('sales_id'));
		$date = date('Y-m-d');


		if(count($networklist)){
			foreach ($networklist as $key => $network) {
				$data_network[$network] = Input::get('product_'.$network);
				$data_quantity[$network] = Input::get('quantity_'.$network);				
			}
			$dataval['product'] = serialize($data_network);
			$dataval['quantity'] = serialize($data_quantity);			
			$dataval['sales_id'] = $sales_id;
			$dataval['end_date'] = $date;
			$dataval['network'] = $network_implode;
			DB::table('end_day')->insert($dataval);
		}

		Session::forget("sales_userid");
		return redirect('/sales/')->with('message','End day was updated. Today your session was closed. You can login tomorrow.');
	}

	public function timesheet()
	{
		$current_date = date("Y-m-d");
		$user_id = Session::get('sales_userid');
		$user = DB::table('sales_rep')->where('user_id', $user_id)->first();

		$today_href='';

		return view('sales/time_sheet', array('title' => 'Time Sheet', 'userdetails' => $user, 'user_id' => $user_id, 'date_current' => $current_date, 'today_href' => $today_href ));	
	}

	public function timesheetprevious($id="")
	{
		$current_date = base64_decode($id);
		$user_id = Session::get('sales_userid');
		$user = DB::table('sales_rep')->where('user_id', $user_id)->first();		

		return view('sales/time_sheet', array('title' => 'Time Sheet', 'userdetails' => $user, 'user_id' => $user_id, 'date_current' => $current_date, 'today_href' => $id ));	
	}
	public function stockmonth(){

		$user_id = Session::get('sales_userid');
		$user = DB::table('sales_rep')->where('user_id', $user_id)->first();	

		$stocklist = DB::table('stock_month')->where('sales_id', $user_id)->where('status', 0)->orderBy('stock_id', 'DESC')->get();

		return view('sales/stock', array('title' => 'Stock of Month', 'userdetails' => $user, 'user_id' => $user_id, 'stocklist' => $stocklist));	

	}

	public function viewstock($id=""){
		$id = base64_decode($id);
		$user_id = Session::get('sales_userid');
		$user = DB::table('sales_rep')->where('user_id', $user_id)->first();	

		$stock = DB::table('stock_month')->where('sales_id', $user_id)->where('stock_id', $id)->where('status', 0)->first();
		return view('sales/viewstock', array('title' => 'View Stock', 'userdetails' => $user, 'user_id' => $user_id, 'stock' => $stock, 'stock_id' => $id));

	}

	public function stockupdate(){
		$stock_id = base64_decode(Input::get('stock_id'));		
		$networklist = Input::get('network');
		$network_implode = implode(',', $networklist);

		if(count($networklist)){
			foreach ($networklist as $key => $network) {				
				$data_correct[$network] = Input::get('correct_'.$network);
				$data_message[$network] = Input::get('message_'.$network);
			}
			$dataval['correct'] = serialize($data_correct);
			$dataval['message'] = serialize($data_message);
			$dataval['status'] = 1;
			

			DB::table('stock_month')->where('stock_id', $stock_id)->update($dataval);
		}

		return redirect('sales/stock_month')->with('message', 'Stock a month was updated');
	}


}

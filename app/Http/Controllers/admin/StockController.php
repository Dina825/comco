<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
use URL;
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
	
	public function addstock()
	{
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
			$dataval['stock_date'] = $date;
			$dataval['network'] = $network_implode;
			DB::table('stock_month')->insert($dataval);
		}

		$sales_id = base64_decode(Input::get('sales_id'));

		$sales_details = DB::table('sales_rep')->where('user_id', $sales_id)->first();

		return Redirect::back()->with('message', 'Stock was successfully updated to '.$sales_details->firstname.' '.$sales_details->surname.'');
	}

	public function viewstock($id=""){
		$id = base64_decode($id);

		$user_details = DB::table('sales_rep')->where('user_id', $id)->first();
		$stocklist = DB::table('stock_month')->where('sales_id', $id)->get();
		
		return view('admin/viewstock', array('title' => 'Stock of Month', 'user_details' => $user_details, 'stocklist' => $stocklist));
	}
	public function viewstockdetails($id=""){
		$id = base64_decode($id);

		$user_details = DB::table('sales_rep')->where('user_id', $id)->first();
		$stock = DB::table('stock_month')->where('stock_id', $id)->first();
		
		return view('admin/viewstockdetails', array('title' => 'Stock of Month', 'user_details' => $user_details, 'stock' => $stock));
	}
	


}

<?php namespace App\Http\Controllers\sales;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use Session;
use URL;
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
		$this->middleware('salesauth');		
		date_default_timezone_set("Europe/London");
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	
	public function shop()
	{
		$user_id = Session::get('sales_userid');
		$user = DB::table('sales_rep')->where('user_id', $user_id)->first();		
		$route = DB::table('route')->get();

		$payment_mode = DB::table('mode_payment')->where('status', 0)->get();
		$shop_type = DB::table('shop_type')->where('status', 0)->get();
		$potential = DB::table('potential_sale')->where('status', 0)->get();

		$shop = DB::table('shop')->where('sales_rep', $user_id)->get();
		return view('sales/shop', array('title' => 'Manage Shop', 'userdetails' => $user, 'routelist' => $route, 'payment_mode_list' => $payment_mode, 'shop_type_list' => $shop_type, 'potentiallist' => $potential, 'shoplist' => $shop));
	}

	public function shopadd(){	

		$email_id = Input::get('email_id');
		$password = Input::get('password');

		if($email_id != ''){
			$data['email_id'] = $email_id;
		}
		if($password != ''){
			$data['password'] = Hash::make($password);
		}
		$data['area_name'] = Input::get('area_name');
		$data['sales_rep'] = base64_decode(Input::get('sales_rep'));
		$data['route'] = Input::get('route');
		$data['shop_name'] = Input::get('shop_name');
		$data['customer_name'] = Input::get('customer_name');
		$data['payee_name'] = Input::get('payee_name');
		$data['address1'] = Input::get('address1');
		$data['address2'] = Input::get('address2');
		$data['address3'] = Input::get('address3');
		$data['city'] = Input::get('city');
		$data['postcode'] = Input::get('postcode');
		$data['phone_number'] = Input::get('phone_number');
		$data['payment_mode'] = Input::get('payment_mode');
		$data['shop_type'] = Input::get('shop_type');
		$data['shop_potential'] = Input::get('shop_potential');
		$data['account_created'] = date('Y-m-d h:i:s');
		
		DB::table('shop')->insert($data);

		return redirect::back()->with('message', 'Shop was add succusfully');
	}
	
	public function shopdetails(){
		$id = base64_decode(Input::get('id'));
		$type = Input::get('type');

		$shop = DB::table('shop')->where('shop_id', $id)->first();
		
		if($type==1){
			$content = 'Are you sure want Active '.$shop->shop_name.' Shop?';
			$title = 'Active Shop';
			$status = $shop->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==2){
			$content = 'Are you sure want Deactivate '.$shop->shop_name.' Shop?';
			$title = 'Deactivate Shop';
			$status = $shop->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==3){

			/*$salesreplist = DB::table('users')->where('user_type', 2)->where('status', 0)->get();
			$output_salesrep='<option value="">Please Select Sales REP</option>';
			$i=0;
			if(count($salesreplist)){
				foreach ($salesreplist as $salesrep) {
					$salesrep_details = DB::table('sales_rep')->where('user_id', $salesrep->user_id)->first();
					$explode_area = explode(',', $salesrep_details->area);
					if(in_array($shop->area_name, $explode_area)){
						if($salesrep->user_id == $shop->sales_rep){
							$selected='selected';
						}
						else{
							$selected='';
						}
						$output_salesrep.='
						<option value="'.$salesrep->user_id.'" '.$selected.'>'.$salesrep_details->firstname.'</option>
						';
						$i++;
					}				
				}
				if($i==0){
					$output_salesrep ='<option value="">Sales REP not available</option>';
				}
				
			}*/

			$routelist = DB::table('route')->where('area_id', $shop->area_name)->where('status', 0)->get();
			$output_route='<option value="">Select Route</option>';
			$i=0;
			if(count($routelist)){
				foreach ($routelist as $route) {
					$explode_sales_id = explode(',', $route->sales_rep_id);
					if(in_array($shop->sales_rep, $explode_sales_id)){
						if($route->route_id == $shop->route){
							$selected= 'selected';
						}
						else{
							$selected='';
						}
						$output_route.='<option value="'.$route->route_id.'" '.$selected.'>'.$route->route_name.'</option>';
						$i++;
					}				
				}
				if($i==0){
					$output_route ='<option value="">Route not available in Sales REP</option>';
				}
			}
			else{
				$output_route ='<option value="">Route not available in Sales REP</option>';
			}
			

			echo json_encode(array('id' => base64_encode($id), 'area_name' => $shop->area_name, 'output_route' => $output_route, 'shop_name' => $shop->shop_name, 'customer_name' => $shop->customer_name, 'payee_name' => $shop->payee_name, 'address1' => $shop->address1, 'address2' => $shop->address2, 'address3' => $shop->address3, 'city' => $shop->city, 'postcode' => $shop->postcode, 'phone_number' => $shop->phone_number, 'payment_mode' => $shop->payment_mode, 'shop_type' => $shop->shop_type, 'shop_potential' => $shop->shop_potential, 'email_id' => $shop->email_id));
		}	

	}

	public function shopstatus(){
		$id = base64_decode(Input::get('id'));
		$status = Input::get('status');

		if($status == 0){
			$statuschange = 1;
			DB::table('shop')->where('shop_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Shop was successfully deactived');
		}
		if($status == 1){
			$statuschange = 0;
			DB::table('shop')->where('shop_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Shop was successfully actived');
		}

	}

	public function shopupdate(){
		$id = base64_decode(Input::get('id'));

		$email_id = Input::get('email_id');
		$password = Input::get('password');

		if($email_id != ''){
			$data['email_id'] = $email_id;
		}
		if($password != ''){
			$data['password'] = Hash::make($password);
		}
		/*$data['area_name'] = Input::get('area_name');
		$data['sales_rep'] = Input::get('sales_rep');
		$data['route'] = Input::get('route');*/
		$data['shop_name'] = Input::get('shop_name');
		$data['customer_name'] = Input::get('customer_name');
		$data['payee_name'] = Input::get('payee_name');
		$data['address1'] = Input::get('address1');
		$data['address2'] = Input::get('address2');
		$data['address3'] = Input::get('address3');
		$data['city'] = Input::get('city');
		$data['postcode'] = Input::get('postcode');
		$data['phone_number'] = Input::get('phone_number');
		$data['payment_mode'] = Input::get('payment_mode');
		$data['shop_type'] = Input::get('shop_type');
		$data['shop_potential'] = Input::get('shop_potential');
		

		DB::table('shop')->where('shop_id', $id)->update($data);
		return Redirect::back()->with('message', 'Shop was successfully updated');

	}

	public function shopformfilterarea(){
		$id = Input::get('id');
		
		$salesreplist = DB::table('users')->where('user_type', 2)->where('status', 0)->get();
		$output_salesrep='<option value="">Please Select Sales REP</option>';
		$i=0;
		if(count($salesreplist)){
			foreach ($salesreplist as $salesrep) {
				$salesrep_details = DB::table('sales_rep')->where('user_id', $salesrep->user_id)->first();
				$explode_area = explode(',', $salesrep_details->area);
				if(in_array($id, $explode_area)){
					$output_salesrep.='
					<option value="'.$salesrep->user_id.'">'.$salesrep_details->firstname.'</option>
					';
					$i++;
				}				
			}
			if($i==0){
				$output_salesrep ='<option value="">Sales REP not available</option>';
			}
			
		}

		echo json_encode(array('output_salesrep' => $output_salesrep));
	}

	public function shopformfiltersales(){
		$sales_id = base64_decode(Input::get('salerep_id'));
		$area_id = Input::get('area_id');

		$routelist = DB::table('route')->where('area_id', $area_id)->where('status', 0)->get();
		$output_route='<option value="">Select Route</option>';
		$i=0;
		if(count($routelist)){
			foreach ($routelist as $route) {
				$explode_sales_id = explode(',', $route->sales_rep_id);
				if(in_array($sales_id, $explode_sales_id)){
					$output_route.='<option value="'.$route->route_id.'">'.$route->route_name.'</option>';
					$i++;
				}				
			}
			if($i==0){
				$output_route ='<option value="">Route not available in Sales REP</option>';
			}
		}
		else{
			$output_route ='<option value="">Route not available in Sales REP</option>';
		}

		echo json_encode(array('output_route' => $output_route));
	}

}

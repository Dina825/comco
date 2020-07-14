<?php namespace App\Http\Controllers\sales;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
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
	public function __construct()
	{
		$this->middleware('salesauth');		
		date_default_timezone_set("Europe/London");
		require_once(app_path('Http/helpers.php'));
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	
	public function accessories($id="")
	{
		$shop_id = $id;
		$user_id = Session::get("sales_userid");
		$category = DB::table('category')->where('status', 0)->get();


		$shop = DB::table('shop')->where('shop_id', base64_decode($id))->first();
		$route_details = DB::table('route')->where('route_id', $shop->route)->first();
		$explode_route = explode(',', $route_details->sales_rep_id);

		if(in_array($user_id, $explode_route)){
			return view('sales/accessories', array('title' => 'Accessories', 'user_id' => $user_id, 'categorylist' => $category, 'shop_id' => $shop_id ));
		}
		else{
			return redirect('sales/shop');
		}
		
		
	}

	public function accessoriesqtycheck(){
		$id = base64_decode(Input::get('id'));
		$qty_value = Input::get('qty_value');
		$type = Input::get('type');
		$user_id = Session::get("sales_userid");
		$shop_id = base64_decode(Input::get('shop_id'));	

		$products_details = DB::table('products')->where('product_id', $id)->first();

		if(count($products_details)){
			if($qty_value <= $products_details->plan1_end){
				$price =  $products_details->plan1_price;				
			}
			elseif($qty_value <= $products_details->plan2_end){
				$price = $products_details->plan2_price;
			}
			else{
				$price = $products_details->plan3_price;
			}
		}

		$shop_details = DB::table('shop')->where('shop_id', $shop_id)->first();

		if($type == 1){			
			$sales_rep_products = DB::table('sales_rep_products')->where('product_id', $id)->where('user_id', $user_id)->first();
			
			if(count($sales_rep_products)){
				if($qty_value <= $sales_rep_products->qty){
					$result = 'true';
					$message='';					
				}
				else{
					$result = 'false';
					$message = 'You have only '.$sales_rep_products->qty.' products Qty. Please enter below.';
				}
				
			}
			else{
				$result = 'false';
				$message = 'You do not have this products. Please contact admin or change order type to online .';
			}
		}
		else{
			$products_details = DB::table('products')->where('product_id', $id)->first();
			if($qty_value <= $products_details->qty){
				$result = 'true';
				$message='';
			}
			else{
				$result = 'false';
				$message = 'We have only '.$products_details->qty.' products Qty. Please enter below.';
			}			
		}

		if($result == 'true'){

			$product_check = DB::table('order_process')->where('shop_id', $shop_id)->where('products_id', $id)->where('shop_order', '0')->where('order_type', $type)->where('status', 0)->first();



			if(count($product_check)){
				$data['qty'] = $qty_value;
				$data['price'] = $price;
				$data['total'] = $qty_value*$price;

				DB::table('order_process')->where('shop_id', $shop_id)->where('products_id', $id)->where('shop_order', '0')->where('order_type', $type)->update($data);				
			}
			else{
				$data['area_id'] = $shop_details->area_name;
				$data['route_id'] = $shop_details->route;
				$data['shop_id'] = $shop_details->shop_id;
				$data['sales_rep_id'] = $user_id;
				$data['order_type'] = $type;
				$data['category_id'] = $products_details->category_id;
				$data['products_id'] = $id;
				$data['qty'] = $qty_value;
				$data['price'] = $price;
				$data['total'] = $qty_value*$price;
				$data['date'] = date('Y-m-d');
				$data['time'] = date('h:i:s');
				$insertGetid = DB::table('order_process')->insertGetid($data);

			}
			$order_process= DB::table('order_process')->where('shop_id', $shop_id)->where('products_id', $id)->where('shop_order', '0')->where('order_type', $type)->where('status',0)->first();

			$total = $order_process->total;
		}
		else{
			$total = '';
		}		

		echo json_encode(array('answer' => $result, 'message' => $message, 'price' => $price, 'total' => $total ));
	}

	public function orderprocess($id="")
	{
		$shop_id = $id;
		$user_id = Session::get("sales_userid");

		$ordershand = DB::table('order_process')->where('shop_id', base64_decode($shop_id))->where('shop_order', '0')->where('order_type', '1')->where('status', 0)->get();

		$ordersonline = DB::table('order_process')->where('shop_id', base64_decode($shop_id))->where('shop_order', '0')->where('order_type', '2')->where('status', 0)->get();
		
		$shop = DB::table('shop')->where('shop_id', base64_decode($id))->first();
		$route_details = DB::table('route')->where('route_id', $shop->route)->first();
		$explode_route = explode(',', $route_details->sales_rep_id);

		if(in_array($user_id, $explode_route)){
			return view('sales/accessories_process', array('title' => 'Order Process', 'user_id' => $user_id, 'shop_id' => $shop_id, 'orderlisthand' => $ordershand, 'orderlistonline' => $ordersonline));
		}
		else{
			return redirect('sales/shop');
		}			
		
	}

	public function accessoriesdelete(){
		$id = base64_decode(Input::get('id'));
		$type = Input::get('type');
		$shop_id = base64_decode(Input::get('shop_id'));

		DB::table('order_process')->where('process_id', $id)->delete();

		$count_check = DB::table('order_process')->where('shop_id', $shop_id)->where('order_type', $type)->where('shop_order', '0')->where('status', 0)->count();

		echo json_encode(array('answer' => 'true', 'count' => $count_check));
	}

	public function processpayment()
	{
		$shop_id = Input::get('shop_id');
		$type = Input::get('type');

		
		$user_id = Session::get("sales_userid");

		$orders = DB::table('order_process')->where('shop_id', base64_decode($shop_id))->where('shop_order', '0')->where('order_type', $type)->where('status', 0)->get();

		$coupon = DB::table('coupon')->where('status', 0)->get();


		$shop = DB::table('shop')->where('shop_id', base64_decode($shop_id))->first();
		$route_details = DB::table('route')->where('route_id', $shop->route)->first();
		$explode_route = explode(',', $route_details->sales_rep_id);

		if(in_array($user_id, $explode_route)){
			return view('sales/process_payment', array('title' => 'Payment Process', 'user_id' => $user_id, 'shop_id' => $shop_id, 'orderlist' => $orders, 'type' => $type, 'coupon_list' => $coupon, 'order_type' => $type));
		}
		else{
			return redirect('sales/shop');
		}		
	}

	public function coupondiscount(){
		$coupon_id = Input::get('coupon_id');
		$shop_id = Input::get('shop_id');
		$type = Input::get('order_type');
		$invoice = Input::get('invoice');
		$subtotal = Input::get('sub_total');

		$coupon_details = DB::table('coupon')->where('coupon_id', $coupon_id)->first();

		$explode_coupon = explode(',', $coupon_details->coupon_category);			

		$orderlistfilter = DB::table('order_process')->where('shop_id', base64_decode($shop_id))->where('shop_order', '0')->where('order_type', $type)->whereIn('category_id', $explode_coupon)->where('status', 0)->get();

		$sub_total_filter='';
		if(count($orderlistfilter)){
			foreach ($orderlistfilter as $orderfilter) {
				$sub_total_filter = $sub_total_filter+$orderfilter->total;
			}
		}
		$admin_setting = DB::table('admin')->first();
		$vat = $admin_setting->vat_percentage;

		if($invoice != 2){				        
			$main_vat = ($subtotal*$vat)/100;        
	        $discount_vat = ($sub_total_filter*$vat)/100;		        
		}
		else{
			$main_vat = '';
			$discount_vat = '';			
		}

		$total_vat = $sub_total_filter+$discount_vat;
		$discount = ($total_vat*$coupon_details->coupon_discount)/100;

		
	    $main_total = $subtotal+$main_vat;

		$grand_total = $main_total-$discount;		

		echo json_encode(array('discount' => number_format_invoice($discount), 'grand_total' => number_format_invoice($grand_total), 'grand_total_class' => $grand_total));		
	}

	public function orderconfirm(){
		$coupon_id = Input::get('coupon_id');
		$shop_id = Input::get('shop_id');
		$type = Input::get('order_type');
		
		$user_id = Session::get("sales_userid");
		$commission_or_bonus = Input::get('commission_or_bonus');

		$get_shop_dates = DB::table('sim_processed')->where('shop_id',base64_decode($shop_id))->orderBy('month_year','desc')->groupBy('month_year')->get();
		$commission_total = '';
		$bonus_total = '';
		if(count($get_shop_dates))
		{
			foreach($get_shop_dates as $date)
			{
				$sum_commission = DB::table('sim_processed')->where('shop_id',base64_decode($shop_id) )->where('month_year',$date->month_year)->sum('commission');

				$sum_bonus = DB::table('sim_processed')->where('shop_id',base64_decode($shop_id) )->where('month_year',$date->month_year)->sum('bonus');

				$commission_total = $commission_total+$sum_commission;
		    	$bonus_total = $bonus_total+$sum_bonus;
			}

			
		}

		$paid_details = DB::table('commission_paid')->where('shop_id', base64_decode($shop_id))->get();
		$total_paid_commission='';
		$total_paid_bonus='';		
		$return_amount='';

		if(count($paid_details)){
			foreach ($paid_details as $paid) {
				if($paid->status == 2 || $paid->status == 3){
					$return_amount = $return_amount+$paid->commission;
				}

				$total_paid_commission = $total_paid_commission+$paid->commission;
				$total_paid_bonus = $total_paid_bonus+$paid->bonus;
			}
			if($return_amount == ''){
				$total_paid_commission = $total_paid_commission;
			}
			else{
				$total_paid_commission = $total_paid_commission-$return_amount;
			}

			$pending_commission = $commission_total-$total_paid_commission;
		    $pending_bonus = $bonus_total-$total_paid_bonus;
		}
		
		else{
			$pending_commission = $commission_total;
			$pending_bonus = $bonus_total;
		}	



		$orderslist = DB::table('order_process')->where('shop_id', base64_decode($shop_id))->where('shop_order', '0')->where('order_type', $type)->where('status', 0)->get();	

		$not_enough='';
		$order_confirm_ids='';
		if(count($orderslist)){
			foreach ($orderslist as $order) {				
				$order_prcoess_id = DB::table('order_process')->where('process_id', $order->process_id)->first();
				if($type == 1){
					$sales_rep_products = DB::table('sales_rep_products')->where('product_id', $order_prcoess_id->products_id )->where('user_id', $user_id)->first();					
					if(count($sales_rep_products)){
						if($order_prcoess_id->qty <= $sales_rep_products->qty){							
							$update_qty = $sales_rep_products->qty-$order_prcoess_id->qty;
							DB::table('sales_rep_products')->where('id', $sales_rep_products->id)->where('user_id', $user_id)->update(['qty' => $update_qty]);

							if($order_confirm_ids == ''){
								$order_confirm_ids = $order->process_id;
							}
							else{
								$order_confirm_ids = $order_confirm_ids.','.$order->process_id;
							}
						}
						else{
							if($not_enough == ''){
								$not_enough = $order->process_id;
							}
							else{
								$not_enough = $not_enough.','.$order->process_id;
							}							
						}						
					}					
				}
				else{
					$products = DB::table('products')->where('product_id', $order_prcoess_id->products_id)->first();

					if(count($products)){
						if($order_prcoess_id->qty <= $products->qty){
							$update_qty = $products->qty-$order_prcoess_id->qty;
							DB::table('products')->where('product_id', $products->product_id)->update(['qty' => $update_qty]);						

							if($order_confirm_ids == ''){
								$order_confirm_ids = $order->process_id;
							}
							else{
								$order_confirm_ids = $order_confirm_ids.','.$order->process_id;
							}
						}
						else{
							if($not_enough == ''){
								$not_enough = $order->process_id;
							}
							else{
								$not_enough = $not_enough.','.$order->process_id;
							}							
						}
						
					}
				}
			}
		}

		$explode_confirm_ids = explode(',', $order_confirm_ids);


		$orderslistnew = DB::table('order_process')->where('shop_id', base64_decode($shop_id))->whereIn('process_id', $explode_confirm_ids)->where('status', 0)->get();

		$sub_total='';
		if(count($orderslistnew)){
			foreach ($orderslistnew as $ordernew) {
				$sub_total = $sub_total+$ordernew->total;				
			}
		}

		if($order_confirm_ids != ''){			

			$shop_details = DB::table('shop')->where('shop_id', base64_decode($shop_id))->first();

			$admin_setting = DB::table('admin')->first();
			$vat = $admin_setting->vat_percentage;	

			
			if($coupon_id != 0)	{
				$coupon_details = DB::table('coupon')->where('coupon_id', $coupon_id)->first();
				$explode_coupon = explode(',', $coupon_details->coupon_category);
				$orderlistfilter = DB::table('order_process')->where('shop_id', base64_decode($shop_id))->whereIn('process_id', $explode_confirm_ids)->whereIn('category_id', $explode_coupon)->where('status', 0)->get();

				$sub_total_filter='';
				if(count($orderlistfilter)){
					foreach ($orderlistfilter as $orderfilter) {
						$sub_total_filter = $sub_total_filter+$orderfilter->total;
					}
				}

				$discount_vat = ($sub_total_filter*$vat)/100;
				$total_vat = $sub_total_filter+$discount_vat;
				$discount = ($total_vat*$coupon_details->coupon_discount)/100;
			}
			else{
				$sub_total_filter = '';
				$discount_vat = '';
				$discount = '';
			}		

			$main_vat = ($sub_total*$vat)/100;
			$total = $sub_total+$main_vat;			
			
		    $main_total = $sub_total+$main_vat;
			$grand = $main_total-$discount;	



			if($commission_total != ''){
				if($commission_or_bonus == 1){
					

					if($grand <= $pending_commission){
						$pending_commission_final = $grand;
						$pending_bonus_final = '0';
					}
					else{
						$grand_pending = $grand-$pending_commission;

						if($grand_pending <= $pending_bonus){
							$pending_commission_final = $pending_commission;
							$pending_bonus_final = $grand_pending;
						}
						else{
							$pending_commission_final = $pending_commission;
							$pending_bonus_final = $pending_bonus;
						}				
					}

					$bonus_commission = $pending_commission_final+$pending_bonus_final;


					$final = $grand-$bonus_commission;

					if($final <= 0){
						$final = 0;
					}
					else{
						$final = $final;
					}
				}
				elseif($commission_or_bonus == 2){				
					$final = $grand-$pending_commission;

					if($grand <= $pending_commission){
						$pending_commission_final = $grand;
						$pending_bonus_final = '0';
					}
					else{
						$pending_commission_final = $pending_commission;
						$pending_bonus_final = '0';
					}

					if($final <= 0){
						$final = 0;
					}
					else{
						$final = $final;
					}					
				}
				elseif($commission_or_bonus == 3){
					$final = $grand-$pending_bonus;

					if($grand <= $pending_bonus){
						$pending_bonus_final = $grand;
						$pending_commission_final = '0';
					}
					else{
						$pending_bonus_final = $pending_bonus;
						$pending_commission_final = '0';
					}

					if($final <= 0){
						$final = 0;
					}
					else{
						$final = $final;
					}
					
				}
				else{			
					$pending_commission_final = '0';
					$pending_bonus_final = '0';
					$final = $grand;
				}		
			}
			else{
				$pending_commission_final = '0';
				$pending_bonus_final = '0';
				$final = $grand;
			}



			$data['commission_bonus'] = $commission_or_bonus;
			$data['commission'] = $pending_commission_final;
			$data['bonus'] = $pending_bonus_final;
			$data['final'] = $final;


			$data['order_process_id'] = $order_confirm_ids;
			$data['area_ids'] = $shop_details->area_name;
			$data['route_ids'] = $shop_details->route;
			$data['shop_ids'] = $shop_details->shop_id;
			$data['sales_reps']= $user_id;
			$data['order_types']= $type;

			$data['subtotal']= $sub_total;
			$data['vat_percentage']= $admin_setting->vat_percentage;
			$data['vat_value']= $main_vat;
			$data['total']= $total;

			if($coupon_id != 0){
				$data['discount_id']= $coupon_details->coupon_id;
				$data['discount_coupon']= $coupon_details->coupon_code;
				$data['discount_name']= $coupon_details->coupon_name;
				$data['discount_category']= $coupon_details->coupon_category;
				$data['discount_percentage']= $coupon_details->coupon_discount;		
				$data['discount_amount']= $discount;
			}
			else{
				$data['discount_id']= $coupon_id;
			}


			
			$data['grand_total']= $grand;
			$data['date'] = date('Y-m-d');
			$data['time'] = date('h:i:s');

			$order_confirm_id = DB::table('order_confirm')->insertGetid($data);

			if($commission_or_bonus == 1 || $commission_or_bonus == 2 || $commission_or_bonus == 3){
				$datapaid['area'] = $shop_details->area_name;
				$datapaid['route'] = $shop_details->route;
				$datapaid['salesrep'] = $user_id;
				$datapaid['shop_id'] = $shop_details->shop_id;
				$datapaid['type'] = '2';
				$datapaid['date'] = date('Y-m-d');
				$datapaid['time'] = date('h:i');
				$datapaid['given_type'] = '3';
				$datapaid['given_date'] = date('Y-m-d');
				$datapaid['given_time'] = date('h:i');
				$datapaid['commission'] = $pending_commission_final;
				$datapaid['bonus'] = $pending_bonus_final;
				$datapaid['order_id'] = $order_confirm_id;
				$datapaid['status'] = '4';

				$datapaid['type'] = '2';
				if($type == 1){
					$datapaid['description'] = 'Inhand orders for '.$shop_details->shop_name;
				}
				else{
					$datapaid['description'] = 'Online orders for '.$shop_details->shop_name;
				}

				DB::table('commission_paid')->insert($datapaid);
			}

			

			if($type == 1){
				if($final > 0){
					$datasales['date'] = date('Y-m-d');
					$datasales['time'] = date('h:i');
					$datasales['salesrepid'] = $user_id;
					$datasales['description'] = 'Inhand orders for '.$shop_details->shop_name;
					$datasales['shop_id'] = $shop_details->shop_id;
					$datasales['order_id'] = $order_confirm_id;
					$datasales['payment'] = $final;		

					DB::table('sales_rep_payments')->insert($datasales);			
				}
			}
			
			

			if(count($explode_confirm_ids)){
				foreach ($explode_confirm_ids as $single_confirm) {
					$dataprocess['order_confirm_id'] = $order_confirm_id;
					$dataprocess['status'] = '1';
					DB::table('order_process')->where('process_id', $single_confirm)->update($dataprocess);
				}
			}
			if($type == 1){
				if(count($orderslistnew)){
					foreach ($orderslistnew as $ordernew) {

						$shop_details = DB::table('shop')->where('shop_id',$ordernew->shop_id)->first();

						$history['date'] = date('Y-m-d');
						$history['time'] = date('h:i:s');

						$description = 'Order shop for '.$shop_details->shop_name;
						$history['user_id'] = $user_id;
						
						$history['product_id'] = $ordernew->products_id;
						$history['qty'] = $ordernew->qty;
						$history['description'] = $description;
						$history['order_id'] = $order_confirm_id;
						$history['shop_id'] = $ordernew->shop_id;
						$history['status'] = '1';
						

						DB::table('sales_rep_products_history')->insert($history);
					}
				}
			}

		}
		else{
			return redirect::back()->with('message', 'Sorry! Your order was cancelled. Please check the QTY');
		}

		if($not_enough != ''){
			return redirect::back()->with('message', 'Sorry! Below products qty not available, Otherwise your order was successfully completed. Please check the order history page');
		}
		else{
			return redirect::back()->with('message', 'Accessories order was successfully completed');
		}	

	}
	
	public function orderhistory($id=""){
		$shop_id = $id;
		$user_id = Session::get("sales_userid");

		$history = DB::table('order_confirm')->where('shop_ids', base64_decode($shop_id))->where('sales_reps', $user_id)->where('shop_orders', '0')->orderBy('date', 'DESC')->orderBy('time', 'DESC')->get();


		$shop = DB::table('shop')->where('shop_id', base64_decode($shop_id))->first();
		$route_details = DB::table('route')->where('route_id', $shop->route)->first();
		$explode_route = explode(',', $route_details->sales_rep_id);

		if(in_array($user_id, $explode_route)){
			return view('sales/order_history', array('title'=> 'Order history', 'shop_id' => $shop_id, 'user_id' => $user_id, 'historylist' => $history));
		}
		else{
			return redirect('sales/shop');
		}		
	}

	public function orderdetails($id=""){		
		$shop_id = Input::get('shop_id');
		$order_id = base64_decode(Input::get('order_id'));
		$user_id = Session::get("sales_userid");

		$orders = DB::table('order_confirm')->where('order_id', $order_id)->first();


		$shop = DB::table('shop')->where('shop_id', base64_decode($shop_id))->first();
		$route_details = DB::table('route')->where('route_id', $shop->route)->first();
		$explode_route = explode(',', $route_details->sales_rep_id);

		if(in_array($user_id, $explode_route)){
			return view('sales/order_details', array('title'=> 'Order history', 'shop_id' => $shop_id, 'user_id' => $user_id, 'orders' => $orders));
		}
		else{
			return redirect('sales/shop');
		}		
	}

	public function commissionbonuscheck(){
		$shop_id = base64_decode(Input::get('shop_id'));
		$commission = Input::get('commission');
		$bonus = Input::get('bonus');
		$grand = Input::get('grand');

		$get_shop_dates = DB::table('sim_processed')->where('shop_id',$shop_id)->orderBy('month_year','desc')->groupBy('month_year')->get();
		$commission_total = '';
		$bonus_total = '';
		if(count($get_shop_dates))
		{
			foreach($get_shop_dates as $date)
			{
				$sum_commission = DB::table('sim_processed')->where('shop_id',$shop_id )->where('month_year',$date->month_year)->sum('commission');

				$sum_bonus = DB::table('sim_processed')->where('shop_id',$shop_id )->where('month_year',$date->month_year)->sum('bonus');

				$commission_total = $commission_total+$sum_commission;
		    	$bonus_total = $bonus_total+$sum_bonus;
			}

			
		}

		$paid_details = DB::table('commission_paid')->where('shop_id', $shop_id)->get();
		$total_paid_commission='';
		$total_paid_bonus='';		
		$return_amount='';

		if(count($paid_details)){
			foreach ($paid_details as $paid) {
				if($paid->status == 2 || $paid->status == 3){
					$return_amount = $return_amount+$paid->commission;
				}

				$total_paid_commission = $total_paid_commission+$paid->commission;
				$total_paid_bonus = $total_paid_bonus+$paid->bonus;
			}
			if($return_amount == ''){
				$total_paid_commission = $total_paid_commission;
			}
			else{
				$total_paid_commission = $total_paid_commission-$return_amount;
			}

			$pending_commission = $commission_total-$total_paid_commission;
		    $pending_bonus = $bonus_total-$total_paid_bonus;
		}
		
		else{
			$pending_commission = $commission_total;
			$pending_bonus = $bonus_total;
		}

		if($commission_total != ''){
			if($commission == 1 && $bonus == 2){
				

				if($grand <= $pending_commission){
					$pending_commission_final = $grand;
					$pending_bonus_final = '0';
				}
				else{
					$grand_pending = $grand-$pending_commission;

					if($grand_pending <= $pending_bonus){
						$pending_commission_final = $pending_commission;
						$pending_bonus_final = $grand_pending;
					}
					else{
						$pending_commission_final = $pending_commission;
						$pending_bonus_final = $pending_bonus;
					}				
				}

				$bonus_commission = $pending_commission_final+$pending_bonus_final;


				$final = $grand-$bonus_commission;

				if($final <= 0){
					$final = 0;
				}
				else{
					$final = $final;
				}


				$output='
				<tr>
					<td>Grand Total</td><td width="175px">&#163; '.number_format_invoice($grand).'</td>
				</tr>
				<tr>
					<td>Commossion</td><td>-&#163; '.number_format_invoice($pending_commission_final).'</td>
				</tr>
				<tr>
					<td>Bonus</td><td>-&#163; '.number_format_invoice($pending_bonus_final).'</td>
				</tr>
				
				<tr>
					<td>Final</td><td>&#163; '.number_format_invoice($final).'
					
					</td>
				</tr>';

				$type='1';
			}
			elseif($commission == 1 && $bonus == 0){				
				$final = $grand-$pending_commission;

				if($grand <= $pending_commission){
					$pending_commission_final = $grand;
				}
				else{
					$pending_commission_final = $pending_commission;
				}

				if($final <= 0){
					$final = 0;
				}
				else{
					$final = $final;
				}
				$output='
				<tr>
					<td>Grand Total</td><td width="175px">&#163; '.number_format_invoice($grand).'</td>
				</tr>
				<tr>
					<td>Commossion</td><td>-&#163; '.number_format_invoice($pending_commission_final).'</td>
				</tr>
							
				<tr>
					<td>Final</td><td>&#163; '.number_format_invoice($final).'
					
				</tr>';			
				$type='2';
			}
			elseif($commission == 0 && $bonus == 2){
				$final = $grand-$pending_bonus;

				if($grand <= $pending_bonus){
					$pending_bonus_final = $grand;
				}
				else{
					$pending_bonus_final = $pending_bonus;
				}

				if($final <= 0){
					$final = 0;
				}
				else{
					$final = $final;
				}

				$output='
				<tr>
					<td>Grand Total</td><td width="175px">&#163; '.number_format_invoice($grand).'</td>
				</tr>

				<tr>
					<td>Bonus</td><td>-&#163; '.number_format_invoice($pending_bonus_final).'</td>
				</tr>
				
				<tr>
					<td>Final</td><td>&#163; '.number_format_invoice($final).'
					
				</tr>';			
				$type='3';
			}
			elseif($commission == 0 && $bonus == 0){			
				$output='';
				$type='4';
			}		
		}
		else{
			$output='Sorry, you do not have commission.';
			$type = '0';
		}

		echo json_encode(array('type' => $type, 'output' => $output));
	}
}

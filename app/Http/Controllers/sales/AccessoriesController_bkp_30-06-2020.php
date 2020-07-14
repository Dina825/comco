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
		
		return view('sales/accessories', array('title' => 'Accessories', 'user_id' => $user_id, 'categorylist' => $category, 'shop_id' => $shop_id ));
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
				
		return view('sales/accessories_process', array('title' => 'Order Process', 'user_id' => $user_id, 'shop_id' => $shop_id, 'orderlisthand' => $ordershand, 'orderlistonline' => $ordersonline));
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

		return view('sales/process_payment', array('title' => 'Payment Process', 'user_id' => $user_id, 'shop_id' => $shop_id, 'orderlist' => $orders, 'type' => $type, 'coupon_list' => $coupon, 'order_type' => $type));
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

		echo json_encode(array('discount' => number_format_invoice($discount), 'grand_total' => number_format_invoice($grand_total)));		
	}

	public function orderconfirm(){
		$coupon_id = Input::get('coupon_id');
		$shop_id = Input::get('shop_id');
		$type = Input::get('order_type');
		$invoice = Input::get('invoice');


		$orderslist = DB::table('order_process')->where('shop_id', base64_decode($shop_id))->where('shop_order', '0')->where('order_type', $type)->where('status', 0)->get();
		/*$sub_total='';
		$impode_process_id='';
		if(count($orderslist)){
			foreach ($orderslist as $order) {
				$sub_total = $sub_total+$order->total;

				if($impode_process_id == ''){
					$impode_process_id = $order->process_id;
				}
				else{
					$impode_process_id = $impode_process_id.','.$order->process_id;
				}
			}
		}

		$explode_process = explode(',', $impode_process_id);*/

		$not_enough='';
		$order_confirm_ids='';
		if(count($orderslist)){
			foreach ($orderslist as $order) {				
				$order_prcoess_id = DB::table('order_process')->where('process_id', $order->process_id)->first();
				if($type == 1){
					$sales_rep_products = DB::table('sales_rep_products')->where('product_id', $order_prcoess_id->products_id )->first();					
					if(count($sales_rep_products)){
						if($order_prcoess_id->qty <= $sales_rep_products->qty){							
							$update_qty = $sales_rep_products->qty-$order_prcoess_id->qty;
							DB::table('sales_rep_products')->where('id', $sales_rep_products->id)->update(['qty' => $update_qty]);						

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

			$coupon_details = DB::table('coupon')->where('coupon_id', $coupon_id)->first();
			$explode_coupon = explode(',', $coupon_details->coupon_category);
			$orderlistfilter = DB::table('order_process')->where('shop_id', base64_decode($shop_id))->whereIn('process_id', $explode_confirm_ids)->whereIn('category_id', $explode_coupon)->where('status', 0)->get();

			$sub_total_filter='';
			if(count($orderlistfilter)){
				foreach ($orderlistfilter as $orderfilter) {
					$sub_total_filter = $sub_total_filter+$orderfilter->total;
				}
			}

			if($invoice != 2){				        
		        $main_vat = ($sub_total*$vat)/100;
		        $discount_vat = ($sub_total_filter*$vat)/100;
			}
			else{			
				$main_vat = '';
				$discount_vat ='';
				
			}

			$total = $sub_total+$main_vat;

			$total_vat = $sub_total_filter+$discount_vat;
			$discount = ($total_vat*$coupon_details->coupon_discount)/100;
			
		    $main_total = $sub_total+$main_vat;
			$grand_total = $main_total-$discount;
			$user_id = Session::get("sales_userid");

			$data['order_process_id'] = $order_confirm_ids;
			$data['area_ids'] = $shop_details->area_name;
			$data['route_ids'] = $shop_details->route;
			$data['shop_ids'] = $shop_details->shop_id;
			$data['sales_reps']= $user_id;
			$data['order_types']= $type;
			$data['invoice']= $invoice;
			$data['subtotal']= $sub_total;
			$data['vat_percentage']= $admin_setting->vat_percentage;
			$data['vat_value']= $main_vat;
			$data['total']= $total;
			$data['discount_id']= $coupon_details->coupon_id;
			$data['discount_coupon']= $coupon_details->coupon_code;
			$data['discount_category']= $coupon_details->coupon_category;
			$data['discount_percentage']= $coupon_details->coupon_discount;		
			$data['discount_amount']= $discount;
			$data['grand_total']= $grand_total;

			$order_confirm_id = DB::table('order_confirm')->insertGetid($data);
			

			if(count($explode_confirm_ids)){
				foreach ($explode_confirm_ids as $single_confirm) {
					$dataprocess['order_confirm_id'] = $order_confirm_id;
					$dataprocess['status'] = '1';
					DB::table('order_process')->where('process_id', $single_confirm)->update($dataprocess);
				}
			}

		}
		else{
			return redirect::back()->with('message', 'Sorry! Your order was cancelled. Please check the QTY');
		}

		if($not_enough != ''){
			return redirect::back()->with('message', 'Sorry! Below products qty not available, Otherwise your order was successfully completed.');
		}
		else{
			return redirect::back()->with('message', 'Accessories order was successfully completed');
		}

		

	}
	
}

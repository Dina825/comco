<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
use URL;
class ProductController extends Controller {

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
	
	public function product()
	{
		$product = DB::table('products')->get();
		return view('admin/product', array('title' => 'Manage Product', 'product_list' => $product));
	}

	public function productadd(){
		$data['product_name'] = Input::get('name');		
		$data['category_id'] = Input::get('category');		
		$data['description'] = Input::get('description');		
		$data['qty'] = Input::get('qty');

		$data['plan1_start'] = Input::get('plan1_start');
		$data['plan1_end'] = Input::get('plan1_end');
		$data['plan1_price'] = Input::get('plan1_price');
		$data['plan2_start'] = Input::get('plan2_start');
		$data['plan2_end'] = Input::get('plan2_end');
		$data['plan2_price'] = Input::get('plan2_price');
		$data['plan3_above'] = Input::get('plan3_above');
		$data['plan3_price'] = Input::get('plan3_price');

		$id = DB::table('products')->insertGetid($data);
		$uploaddir = 'uploads/product'.'/'.base64_encode($id);

		if($_FILES['picture']['name'] != ''){
			$photo_name = $_FILES['picture']['name'];		
			$tmp_name = $_FILES['picture']['tmp_name'];		

			if(!file_exists($uploaddir)){
				mkdir($uploaddir);
			}
			move_uploaded_file($tmp_name, $uploaddir.'/'.$photo_name);			
		}

		$dataimage['url'] = $uploaddir;
		$dataimage['picture'] = $photo_name;

		DB::table('products')->where('product_id', $id)->update($dataimage);

		return redirect::back()->with('message', 'Product Add Succusfully');
	}

	public function productdetails(){
		$id = base64_decode(Input::get('id'));
		$type = Input::get('type');

		$details = DB::table('products')->where('product_id', $id)->first();
		if($type==1){
			$content = 'Are you sure want Active '.$details->product_name.' Product?';
			$title = 'Active Product';
			$status = $details->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==2){
			$content = 'Are you sure want Deactivate '.$details->product_name.' Product?';
			$title = 'Deactivate Product';
			$status = $details->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==3){

			$picture = $details->url.'/'.$details->picture;

			echo json_encode(array('id' => base64_encode($details->product_id), 'name' => $details->product_name, 'category' => $details->category_id, 'qty' => $details->qty, 'description' => $details->description, 'picture' => $picture, 'plan1_start' => $details->plan1_start, 'plan1_end' => $details->plan1_end, 'plan1_price' => $details->plan1_price, 'plan2_start' => $details->plan2_start,  'plan2_end' => $details->plan2_end, 'plan2_price' => $details->plan2_price, 'plan3_above' => $details->plan3_above, 'plan3_price' => $details->plan3_price));
		}

	}

	public function productstatus(){
		$id = base64_decode(Input::get('id'));		
		$status = Input::get('status');

		if($status == 0){
			$statuschange = 1;
			DB::table('products')->where('product_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Product was successfully deactived');
		}
		if($status == 1){
			$statuschange = 0;
			DB::table('products')->where('product_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Product was successfully actived');
		}
	}

	public function productupdate(){
		$id = base64_decode(Input::get('id'));
		$data['product_name'] = Input::get('name');		
		$data['category_id'] = Input::get('category');		
		$data['description'] = Input::get('description');		
		$data['qty'] = Input::get('qty');

		$data['plan1_start'] = Input::get('plan1_start');
		$data['plan1_end'] = Input::get('plan1_end');
		$data['plan1_price'] = Input::get('plan1_price');
		$data['plan2_start'] = Input::get('plan2_start');
		$data['plan2_end'] = Input::get('plan2_end');
		$data['plan2_price'] = Input::get('plan2_price');
		$data['plan3_above'] = Input::get('plan3_above');
		$data['plan3_price'] = Input::get('plan3_price');	

		$uploaddir = 'uploads/product'.'/'.base64_encode($id);

		if($_FILES['picture']['name'] != ''){
			$photo_name = $_FILES['picture']['name'];		
			$tmp_name = $_FILES['picture']['tmp_name'];		

			if(!file_exists($uploaddir)){
				mkdir($uploaddir);
			}
			move_uploaded_file($tmp_name, $uploaddir.'/'.$photo_name);
			$data['picture'] = $photo_name;			
		}		
		

		DB::table('products')->where('product_id', $id)->update($data);
		return Redirect::back()->with('message', 'Product was successfully updated');
	}

	public function salesrep()
	{
		$salesrep = DB::table('users')->where('user_type', 2)->where('status', 0)->get();
		return view('admin/accessories_salesrep', array('title' => 'Manage Accessories Sales REP', 'userlist' => $salesrep));
	}

	public function salesrepproducts($id=""){
		$id = base64_decode($id);
		$category = DB::table('category')->where('status', 0)->get();
		return view('admin/accessories_salesrep_products', array('title' => 'Sales REP In Hand Products', 'categorylist' => $category, 'user_id' => $id));
	}

	public function accessoriesaddsalesrep(){
		$productid = base64_decode(Input::get('productid'));
		$categoryid = base64_decode(Input::get('categoryid'));
		$qty_value = Input::get('qty_value');
		$user_id = base64_decode(Input::get('user_id'));

		$sales_rep_product = DB::table('sales_rep_products')->where('user_id', $user_id)->where('product_id', $productid)->first();

		if(count($sales_rep_product)){
			$update_value = $sales_rep_product->qty+$qty_value;
			DB::table('sales_rep_products')->where('user_id', $user_id)->where('product_id', $productid)->update(['qty' => $update_value]);
		}
		else{
			$data['user_id'] = $user_id;
			
			$data['product_id'] = $productid;
			$data['qty'] = $qty_value;
			DB::table('sales_rep_products')->insert($data);
		}

		$description = 'Admin add qty';
		$datahistoy['user_id'] = $user_id;
		
		$datahistoy['product_id'] = $productid;
		$datahistoy['qty'] = $qty_value;
		$datahistoy['description'] = $description;
		$datahistoy['date'] = date('Y-m-d');
		$datahistoy['time'] = date('h:i:s');
		DB::table('sales_rep_products_history')->insert($datahistoy);



		$sales_rep_product = DB::table('sales_rep_products')->where('user_id', $user_id)->where('product_id', $productid)->first();

		echo json_encode(array('qty' => $sales_rep_product->qty, 'product_id' => base64_encode($productid) ));
	}

	public function salesrepproductshistory(){
		$user_id = base64_decode(Input::get('user_id'));
		$type = Input::get('type');

		if($type == 1){
			$historylist = DB::table('sales_rep_products_history')->where('user_id', $user_id)->get();
			$salesrep_details = DB::table('sales_rep')->where('user_id', $user_id)->first();

			$title = 'Products History for '.$salesrep_details->firstname.' '.$salesrep_details->surname;
		}
		elseif($type == 2){
			$product_id = base64_decode(Input::get('product_id'));
			$historylist = DB::table('sales_rep_products_history')->where('user_id', $user_id)->where('product_id', $product_id)->get();

			$products_details = DB::table('products')->where('product_id', $product_id)->first();
			$salesrep_details = DB::table('sales_rep')->where('user_id', $user_id)->first();

			$title = 'Product history of '.$products_details->product_name.' for '.$salesrep_details->firstname.' '.$salesrep_details->surname;
		}
		$output='';
		$i=1;
		$balance='';
		if(count($historylist)){
			foreach ($historylist as $history) {
				$products = DB::table('products')->where('product_id', $history->product_id)->first();
				$category_details = DB::table('category')->where('category_id', $products->category_id)->first();
				if($history->shop_id != ''){
					$shop_details = DB::table('shop')->where('shop_id', $history->shop_id)->first();
					$shop = $shop_details->shop_name.' / CC-'.$shop_details->shop_id;
				}
				else{
					$shop='';
				}

				if($history->status == 1){
					$symbol = '-';
					$color = 'color:#f00';
				}
				else{
					$symbol = '';
					$color = '';
				}

				if(isset($product{$history->product_id}))
				{
					if($history->status == 0)
					{
						$product{$history->product_id} = $product{$history->product_id} + $history->qty;
					}
					else{
						$product{$history->product_id} = $product{$history->product_id} - $history->qty;
					}
				}
				else{
					if($history->status == 0)
					{
						$product{$history->product_id} = $history->qty;
					}
					else{
						$product{$history->product_id} = $history->qty;
					}
				}			
				
				
				$output.='
				<tr style="'.$color.'">
					<td>'.$i.'</td>
					<td>'.$products->product_name.'</td>
					<td>'.$category_details->category_name.'</td>
					<td>'.$history->description.'</td>
					<td>'.$history->order_id.'</td>
					<td>'.$shop.'</td>
					<td>'.$symbol.''.$history->qty.'</td>
					<td>'.$product{$history->product_id}.'</td>
				</tr>
				';
				$i++;
				
			}
		}
		else{
			$output='
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td>Empty</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			';
		}

		echo json_encode(array('title' => $title, 'content' => $output));





		
	}


}

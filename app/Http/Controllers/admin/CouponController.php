<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
use URL;
class CouponController extends Controller {

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
	
	public function coupon()
	{
		$coupon = DB::table('coupon')->get();
		$category = DB::table('category')->get();
		return view('admin/coupon', array('title' => 'Manage coupon', 'coupon_list' => $coupon, 'category_list' => $category));
	}

	public function couponadd(){		

		$data['coupon_name'] = Input::get('name');
		$data['coupon_category'] = implode(',', Input::get('category'));
		$data['coupon_code'] = Input::get('code');
		$data['coupon_date'] = date('Y-m-d', strtotime(Input::get('date')));
		$data['coupon_discount'] = Input::get('discount');

		DB::table('coupon')->insert($data);
		return redirect::back()->with('message', 'Coupon Add Succusfully');
	}

	public function coupondetails(){
		$id = base64_decode(Input::get('id'));
		$type = Input::get('type');

		$details = DB::table('coupon')->where('coupon_id', $id)->first();
		if($type==1){
			$content = 'Are you sure want Active '.$details->coupon_name.' Coupon?';
			$title = 'Active Coupon';
			$status = $details->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==2){
			$content = 'Are you sure want Deactivate '.$details->coupon_name.' Coupon?';
			$title = 'Deactivate Coupon';
			$status = $details->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==3){
			$category_list = DB::table('category')->get();
			$explode_category = explode(',', $details->coupon_category);

			$output_category='<div class="row">';
            if(count($category_list)){
              foreach ($category_list as $category) {
              	$checked='';

              	if(count($explode_category)){
              		foreach ($explode_category as $explode) {
              			if($explode == $category->category_id){
              				$checked='checked';
              			}
              		}
              	}

                $output_category.='

                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <label class="form_checkbox">'.$category->category_name.'
               <input type="checkbox" '.$checked.' value="'.$category->category_id.'" style="width:1px; height:1px" name="category[]"  required>
               <span class="checkmark_checkbox"></span>
            </label>
                  </div>';
              }
              $output_category.='<div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label id="category[]-error" class="error" style="display: none" for="category[]">Please choose atleast one Category.</label>
              </div> </div>';
            }
            else{
              $output_category='<option value="">No Catgory</option>';
            }

			echo json_encode(array('id' => base64_encode($details->coupon_id), 'name' => $details->coupon_name, 'category' => $output_category, 'code' => $details->coupon_code, 'date' => date('d-m-Y', strtotime($details->coupon_date)), 'discount' => $details->coupon_discount));
		}

	}

	public function couponstatus(){
		$id = base64_decode(Input::get('id'));		
		$status = Input::get('status');

		if($status == 0){
			$statuschange = 1;
			DB::table('coupon')->where('coupon_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Coupon was successfully deactived');
		}
		if($status == 1){
			$statuschange = 0;
			DB::table('coupon')->where('coupon_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Coupon was successfully actived');
		}
	}

	public function couponupdate(){
		$id = base64_decode(Input::get('id'));
		$data['coupon_name'] = Input::get('name');
		$data['coupon_category'] = implode(',', Input::get('category'));
		$data['coupon_code'] = Input::get('code');
		$data['coupon_date'] = date('Y-m-d', strtotime(Input::get('date')));
		$data['coupon_discount'] = Input::get('discount');		

		DB::table('coupon')->where('coupon_id', $id)->update($data);
		return Redirect::back()->with('message', 'Coupon was successfully updated');
	}

	public function couponcode(){
		if(isset($_GET['coupon_id']))
		{			
			$coupon_id = base64_decode(Input::get('coupon_id'));			
			$code = Input::get('code');
			$validate = DB::table('coupon')->where('coupon_code', $code)->where('coupon_id','!=', $coupon_id)->count();			
		}	
		else{			
			$code = Input::get('code');
			$validate = DB::table('coupon')->where('coupon_code', $code)->count();
		}
		if($validate != 0 )
			$valid = false;
		else
			$valid = true;
		echo json_encode($valid);
		exit;
	}


}

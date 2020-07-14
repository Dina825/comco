<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
use URL;
class CategoryController extends Controller {

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
	
	public function category()
	{
		$category = DB::table('category')->get();
		return view('admin/category', array('title' => 'Manage Category', 'category_list' => $category));
	}

	public function categoryadd(){
		$data['category_name'] = Input::get('name');		

		DB::table('category')->insert($data);
		return redirect::back()->with('message', 'Category Add Succusfully');
	}

	public function categorydetails(){
		$id = base64_decode(Input::get('id'));
		$type = Input::get('type');

		$details = DB::table('category')->where('category_id', $id)->first();
		if($type==1){
			$content = 'Are you sure want Active '.$details->category_name.' Category?';
			$title = 'Active Category';
			$status = $details->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==2){
			$content = 'Are you sure want Deactivate '.$details->category_name.' Category?';
			$title = 'Deactivate Category';
			$status = $details->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==3){
			echo json_encode(array('id' => base64_encode($details->category_id), 'name' => $details->category_name));
		}

	}

	public function categorystatus(){
		$id = base64_decode(Input::get('id'));		
		$status = Input::get('status');

		if($status == 0){
			$statuschange = 1;
			DB::table('category')->where('category_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Category was successfully deactived');
		}
		if($status == 1){
			$statuschange = 0;
			DB::table('category')->where('category_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Category was successfully actived');
		}
	}

	public function categoryupdate(){
		$id = base64_decode(Input::get('id'));
		$data['category_name'] = Input::get('name');		

		DB::table('category')->where('category_id', $id)->update($data);
		return Redirect::back()->with('message', 'Category was successfully updated');
	}


}

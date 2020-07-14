<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
use URL;
class AreaController extends Controller {

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
	
	public function area()
	{
		$area = DB::table('area')->get();
		return view('admin/area', array('title' => 'Manage Area', 'area_list' => $area));
	}

	public function areaadd(){
		$data['area_name'] = Input::get('name');		

		DB::table('area')->insert($data);
		return redirect::back()->with('message', 'Area Add Succusfully');
	}

	public function areadetails(){
		$id = base64_decode(Input::get('id'));
		$type = Input::get('type');

		$details = DB::table('area')->where('area_id', $id)->first();
		if($type==1){
			$content = 'Are you sure want Active '.$details->area_name.' Area?';
			$title = 'Active Area';
			$status = $details->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==2){
			$content = 'Are you sure want Deactivate '.$details->area_name.' Area?';
			$title = 'Deactivate Area';
			$status = $details->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==3){
			echo json_encode(array('id' => base64_encode($details->area_id), 'name' => $details->area_name));
		}

	}

	public function areastatus(){
		$id = base64_decode(Input::get('id'));		
		$status = Input::get('status');

		if($status == 0){
			$statuschange = 1;
			DB::table('area')->where('area_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Area was successfully deactived');
		}
		if($status == 1){
			$statuschange = 0;
			DB::table('area')->where('area_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Area was successfully actived');
		}
	}

	public function areaupdate(){
		$id = base64_decode(Input::get('id'));
		$data['area_name'] = Input::get('name');		

		DB::table('area')->where('area_id', $id)->update($data);
		return Redirect::back()->with('message', 'Area was successfully updated');
	}


}

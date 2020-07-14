<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
use URL;
class NetworkController extends Controller {

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
	
	public function network()
	{
		$network = DB::table('network')->get();
		return view('admin/network', array('title' => 'Manage Network', 'network_list' => $network));
	}

	public function networkadd(){
		$data['network_name'] = Input::get('network');
		$data['product_id'] = Input::get('product_id');
		$data['name'] = Input::get('name');
		$data['minimum_value'] = Input::get('minimum_value');

		DB::table('network')->insert($data);
		return redirect::back()->with('message', 'Network Add Succusfully');
	}

	public function networkdetails(){
		$id = base64_decode(Input::get('id'));
		$type = Input::get('type');

		$details = DB::table('network')->where('network_id', $id)->first();
		if($type==1){
			$content = 'Are you sure want Active '.$details->network_name.' Network?';
			$title = 'Active Area';
			$status = $details->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==2){
			$content = 'Are you sure want Deactivate '.$details->network_name.' Network?';
			$title = 'Deactivate Area';
			$status = $details->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==3){
			echo json_encode(array('id' => base64_encode($details->network_id), 'network_name' => $details->network_name, 'product_id' => $details->product_id, 'name' => $details->name, 'minimum_value' => $details->minimum_value));
		}

	}	

	public function networkstatus(){
		$id = base64_decode(Input::get('id'));		
		$status = Input::get('status');

		if($status == 0){
			$statuschange = 1;
			DB::table('network')->where('network_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Network was successfully deactived');
		}
		if($status == 1){
			$statuschange = 0;
			DB::table('network')->where('network_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Network was successfully actived');
		}
	}

	public function networkupdate(){
		$id = base64_decode(Input::get('id'));
		$data['network_name'] = Input::get('network');
		$data['product_id'] = Input::get('product_id');
		$data['name'] = Input::get('name');
		$data['minimum_value'] = Input::get('minimum_value');

		DB::table('network')->where('network_id', $id)->update($data);
		return Redirect::back()->with('message', 'Network was successfully updated');
	}

	public function networkcheck(){

		if(isset($_GET['network_id']))
		{			
			$network_id = base64_decode(Input::get('network_id'));						
			$network = Input::get('network');			
			$validate = DB::table('network')->where('network_name', $network)->where('network_id','!=',$network_id)->count();
		}	
		else{			
			$network = Input::get('network');
			$validate = DB::table('network')->where('network_name', $network)->count();
		}
		if($validate != 0 )
			$valid = false;
		else
			$valid = true;
		echo json_encode($valid);
		exit;
	}


}

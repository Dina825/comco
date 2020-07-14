<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
use URL;
class ConnectionController extends Controller {

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
	
	public function connection()
	{
		$connection = DB::table('connection')->get();
		return view('admin/connection', array('title' => 'Manage Connection Level', 'connection_list' => $connection));
	}

	public function connectionadd(){
		$data['level'] = Input::get('level');		

		DB::table('connection')->insert($data);
		return redirect::back()->with('message', 'Connection Level Add Succusfully');
	}

	public function connectiondetails(){
		$id = base64_decode(Input::get('id'));
		$type = Input::get('type');

		$details = DB::table('connection')->where('connection_id', $id)->first();
		if($type==1){
			$content = 'Are you sure want Active '.$details->level.' Connection Level?';
			$title = 'Active Area';
			$status = $details->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==2){
			$content = 'Are you sure want Deactivate '.$details->level.' Connection Level?';
			$title = 'Deactivate Area';
			$status = $details->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==3){
			echo json_encode(array('id' => base64_encode($details->connection_id), 'name' => $details->level));
		}

	}

	public function connectionstatus(){
		$id = base64_decode(Input::get('id'));		
		$status = Input::get('status');

		if($status == 0){
			$statuschange = 1;
			DB::table('connection')->where('connection_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Connection Level was successfully deactived');
		}
		if($status == 1){
			$statuschange = 0;
			DB::table('connection')->where('connection_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Connection Level was successfully actived');
		}
	}

	public function connectionupdate(){
		$id = base64_decode(Input::get('id'));
		$data['level'] = Input::get('level');		

		DB::table('connection')->where('connection_id', $id)->update($data);
		return Redirect::back()->with('message', 'Connection Level was successfully updated');
	}

	public function connectionlevelcheck(){
		if(isset($_GET['connection_id']))
		{			
			$connection_id = base64_decode(Input::get('connection_id'));			
			$level = Input::get('level');			
			$validate = DB::table('connection')->where('level', $level)->where('connection_id','!=',$connection_id)->count();
		}	
		else{			
			$level = Input::get('level');
			$validate = DB::table('connection')->where('level', $level)->count();
		}
		if($validate != 0 )
			$valid = false;
		else
			$valid = true;
		echo json_encode($valid);
		exit;
	}


}

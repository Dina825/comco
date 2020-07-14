<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
use URL;
use Hash;
class ManagerController extends Controller {

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
	
	public function areamanager()
	{
		$user = DB::table('users')->where('user_type', 1)->get();
		$area = DB::table('area')->where('status', 0)->get();
		return view('admin/manager', array('title' => 'Manage Area Manager', 'arealist' => $area, 'userlist' => $user));
	}

	public function areamanageradd(){	

			
		$email_id = Input::get('email_id');
		$password = Input::get('password');
		$password_hash = Hash::make($password);

		$data['user_type'] = 1;
		$data['email_id'] = $email_id;
		$data['password'] = $password_hash;
		$data['account_created'] = date('Y-m-d h:i:s');

		$id = DB::table('users')->insertGetId($data);
		$area_checkbox = Input::get('area_checkbox');
		
		$datamanager['area'] = implode(',', $area_checkbox);
		$datamanager['user_id'] = $id;
		$datamanager['firstname'] = Input::get('firstname');
		$datamanager['surname'] = Input::get('surename');
		$datamanager['personal_email'] = Input::get('personal_email');
		$datamanager['address1'] = Input::get('addressone');
		$datamanager['address2'] = Input::get('addresstwo');
		$datamanager['address3'] = Input::get('addressthree');
		$datamanager['city'] = Input::get('city');
		$datamanager['postcode'] = Input::get('postcode');
		$datamanager['mobile_number'] = Input::get('mobile');

		DB::table('area_manager')->insert($datamanager);

		return redirect::back()->with('message', 'Area Manager was add succusfully');
	}
	
	public function areamanagerdetails(){
		$id = base64_decode(Input::get('id'));
		$type = Input::get('type');

		$users = DB::table('users')->where('user_id', $id)->first();
		$details = DB::table('area_manager')->where('user_id', $users->user_id)->first();

		
		if($type==1){
			$content = 'Are you sure want Active '.$details->firstname.' Area Manager?';
			$title = 'Active Area Manager';
			$status = $users->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==2){
			$content = 'Are you sure want Deactivate '.$details->firstname.' Area Manager?';
			$title = 'Deactivate Area Manager';
			$status = $users->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==3){

			$explode_area = explode(',', $details->area);			
			$arealist = DB::table('area')->where('status', 0)->get();

			$output_area='';
            if(count($arealist)){
              foreach ($arealist as $area) {
              	$checked = '';
              	if(count($explode_area)){
              		foreach ($explode_area as $explode) {              			
              			if($area->area_id == $explode ){
              				$checked = 'checked';
              			}
              		}
              		
              	}
              	
                $output_area.='
                <div class="col-lg-3 col-md-6 col-sm-12 col-12">
		          	<label class="form_checkbox">'.$area->area_name.'
			            <input type="checkbox" '.$checked.' value="'.$area->area_id.'" style="width:1px; height:1px" name="edit_area_checkbox[]"  required>
			            <span class="checkmark_checkbox"></span>
			        </label>
                </div>';
              }
              $output_area.='<div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <label id="edit_area_checkbox[]-error" class="error" style="display: none;" for="edit_area_checkbox[]" style="display: inline-block;">Please choose atleast one area</label>
                                    
                                </div>';
            }
            else{
              $output_area='<div class="col-lg-12">Empty</div>';
            }

			echo json_encode(array('id' => base64_encode($id), 'firstname' => $details->firstname, 'surname' => $details->surname, 'personal_email' => $details->personal_email, 'address1' => $details->address1, 'address2' => $details->address2, 'address3' => $details->address3, 'city' => $details->city, 'postcode' => $details->postcode, 'mobile_number' => $details->mobile_number, 'email_id' => $users->email_id, 'output_area' => $output_area));
		}



		

	}

	public function areamanagerstatus(){
		$id = base64_decode(Input::get('id'));
		$status = Input::get('status');

		if($status == 0){
			$statuschange = 1;
			DB::table('users')->where('user_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Area Manager was successfully deactived');
		}
		if($status == 1){
			$statuschange = 0;
			DB::table('users')->where('user_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Area Manager was successfully actived');
		}

	}

	public function areamanagerupdate(){
		$id = base64_decode(Input::get('id'));
		$area_checkbox = Input::get('edit_area_checkbox');
		$data['area'] = implode(',', $area_checkbox);		
		$data['firstname'] = Input::get('firstname');
		$data['surname'] = Input::get('surename');
		$data['personal_email'] = Input::get('personal_email');
		$data['address1'] = Input::get('addressone');
		$data['address2'] = Input::get('addresstwo');
		$data['address3'] = Input::get('addressthree');
		$data['city'] = Input::get('city');
		$data['postcode'] = Input::get('postcode');
		$data['mobile_number'] = Input::get('mobile');

		$datausers['email_id'] = Input::get('email_id');
		$password = Input::get('password');
		if($password != ''){
			$password_hash = Hash::make($password);
			$datausers['password'] = $password_hash;
		}

		DB::table('users')->where('user_id', $id)->update($datausers);
		DB::table('area_manager')->where('user_id', $id)->update($data);
		return Redirect::back()->with('message', 'Area Manager was successfully updated');

	}

	
}

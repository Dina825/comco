<?php namespace App\Http\Controllers\sales;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use Session;
use Mail;

use URL;
use Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
		require_once(app_path('Http/helpers.php'));
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
		$route = DB::table('route')->where('status',0)->get();

		return view('sales/shop', array('title' => 'Manage Shop', 'userdetails' => $user, 'routelist' => $route, 'user_id' => $user_id));
	}

	public function shopadd(){	

		$email_id = Input::get('email_id');
		$password = Input::get('password');

		if($email_id != ''){
			$data['email_id'] = $email_id;
		}		
		$data['area_name'] = base64_decode(Input::get('area_name'));
		$data['sales_rep'] = base64_decode(Input::get('sales_rep'));
		$data['route'] = base64_decode(Input::get('route'));
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
		
		$insert_getid = DB::table('shop')->insertGetId($data);
		$shop_details = DB::table('shop')->where('shop_id', $insert_getid)->first();



		if($email_id != ''){
			$base_emaild = base64_encode($email_id);
			$shop_name = Input::get('shop_name');

			$from = 'admin@comco-retail.co.uk';
			$to = trim($email_id);	
			$subject_email = 'Welcome to comco';

			$contentmessage = '
			<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

			<div style="width:100%; min-height:600px; height:100%; float:left; background:#f1f1f1">
				<div style="max-width:600px; font-family: Roboto, sans-serif; width:100%; margin:0px auto;">

					<div style="max-width:600px; font-family: Roboto, sans-serif; font-size: 14px; width:90%; line-height:20px; float:left">
						<div style="width:83%; max-width:600px; padding:0px 20px; height:auto; float:left; text-align:center; margin:50px 20px 30px 20px;">
							<img src="https://comcotel.co.uk/assets/images/comco_logo_red.png" width="130px;" />
						</div>
						<div style="width:83%; max-width:600px; margin:0px 20px; height:auto; padding:20px; background:#fff; float:left;">
							<b>Hi '.$shop_name.', </b><br/><br/>
							Kindly follow the link to register your online account.<br/><br/>

							<a href="https://comcotel.co.uk/shop_create_password/'.$base_emaild.'" target="_blank" style="float:left; padding:10px 15px; color:#fff; background:#dc2c1d; text-decoration:none">Create Password</a><br/><br/><br/>

							<b>Need help?</b><br/>
							Please talk to our team on 020 3322 5259<br/>
							Mon to Sat 9am – 6pm<br/><br/>

							Please do not reply to this automatically generated email.
						</div>

							<div style="width:83%; padding:0px 20px; max-width:600px; height:auto; float:left; text-align:center; margin:20px 20px 30px 20px;">
							<b>Comco group UK Ltd</b> | 4th Floor | 18 Cross Street | London | EC1N 8UN | <br/>
						Registration No.12336312 (England & Wales)
						</div>
					</div>
				</div>
			</div>';


			

			
			$email = new PHPMailer();
			$email->SetFrom($from, 'Comco');
			$email->Subject   = $subject_email;
			$email->Body      = $contentmessage;			
			$email->IsHTML(true);
			$email->AddAddress( $to );			
			$email->Send();			
		}

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

			$user_id = Session::get('sales_userid');

			

			$routelist = DB::table('route')->where('area_id', $shop->area_name)->where('status', 0)->get();
			$output_route='<option value="">Select Route</option>';
			$i=0;
			if(count($routelist)){
				foreach ($routelist as $route) {
					$explode_sales_id = explode(',', $route->sales_rep_id);
					if(in_array($user_id, $explode_sales_id)){
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

			$email_encode = base64_encode($shop->email_id);						

			echo json_encode(array('id' => base64_encode($id), 'area_name' => $shop->area_name, 'output_route' => $output_route, 'shop_name' => $shop->shop_name, 'customer_name' => $shop->customer_name, 'payee_name' => $shop->payee_name, 'address1' => $shop->address1, 'address2' => $shop->address2, 'address3' => $shop->address3, 'city' => $shop->city, 'postcode' => $shop->postcode, 'phone_number' => $shop->phone_number, 'payment_mode' => $shop->payment_mode, 'shop_type' => $shop->shop_type, 'shop_potential' => $shop->shop_potential, 'email_id' => $shop->email_id, 'email_encode' => $email_encode));
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

		if($email_id != ''){
			$data['email_id'] = $email_id;

			$base_emaild = base64_encode($email_id);
			$shop_name = Input::get('shop_name');

			$shop_details = DB::table('shop')->where('shop_id', $id)->first();

			$from = 'admin@comco-retail.co.uk';
			$to = trim($email_id);	
			$subject_email = 'Welcome to comco';

			$contentmessage = '
			<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

			<div style="width:100%; min-height:600px; height:100%; float:left; background:#f1f1f1">
				<div style="max-width:600px; font-family: Roboto, sans-serif; width:100%; margin:0px auto;">

					<div style="max-width:600px; font-family: Roboto, sans-serif; font-size: 14px; width:90%; line-height:20px; float:left">
						<div style="width:83%; max-width:600px; padding:0px 20px; height:auto; float:left; text-align:center; margin:50px 20px 30px 20px;">
							<img src="https://comcotel.co.uk/assets/images/comco_logo_red.png" width="130px;" />
						</div>
						<div style="width:83%; max-width:600px; margin:0px 20px; height:auto; padding:20px; background:#fff; float:left;">
							<b>Hi '.$shop_name.', </b><br/><br/>
							Kindly follow the link to register your online account.<br/><br/>

							<a href="https://comcotel.co.uk/shop_create_password/'.$base_emaild.'" target="_blank" style="float:left; padding:10px 15px; color:#fff; background:#dc2c1d; text-decoration:none">Create Password</a><br/><br/><br/>

							<b>Need help?</b><br/>
							Please talk to our team on 020 3322 5259<br/>
							Mon to Sat 9am – 6pm<br/><br/>

							Please do not reply to this automatically generated email.
						</div>

							<div style="width:83%; padding:0px 20px; max-width:600px; height:auto; float:left; text-align:center; margin:20px 20px 30px 20px;">
							<b>Comco group UK Ltd</b> | 4th Floor | 18 Cross Street | London | EC1N 8UN | <br/>
						Registration No.12336312 (England & Wales)
						</div>
					</div>
				</div>
			</div>';	

			
			$email = new PHPMailer();
			$email->SetFrom($from, 'Comco');
			$email->Subject   = $subject_email;
			$email->Body      = $contentmessage;			
			$email->IsHTML(true);
			$email->AddAddress( $to );			
			$email->Send();

			
		}


		

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

	public function shoplistroute($id=""){
		$id = base64_decode($id);
		$user_id = Session::get('sales_userid');
		$user = DB::table('sales_rep')->where('user_id', $user_id)->first();		
		$route_details = DB::table('route')->where('route_id', $id)->first();

		$payment_mode = DB::table('mode_payment')->where('status', 0)->get();
		$shop_type = DB::table('shop_type')->where('status', 0)->get();
		$potential = DB::table('potential_sale')->where('status', 0)->get();

		$shop = DB::table('shop')->where('route', $id)->orderBy('shop_name', 'ASC')->where('status',0)->get();

		$explode_route = explode(',', $route_details->sales_rep_id);

		if(in_array($user_id, $explode_route)){
			return view('sales/shop_list', array('title' => 'Manage Shop', 'userdetails' => $user, 'route_details' => $route_details, 'payment_mode_list' => $payment_mode, 'shop_type_list' => $shop_type, 'potentiallist' => $potential, 'shoplist' => $shop, 'user_id' => $user_id, 'route_id' => $id));
		}
		else{
			return redirect('sales/shop');
		}

		
		
	}

	public function shopviewdetails($id=""){
		$id = base64_decode($id);
		$user_id = Session::get('sales_userid');
		$user = DB::table('sales_rep')->where('user_id', $user_id)->first();		
		
		$shop = DB::table('shop')->where('shop_id', $id)->first();
		$route_details = DB::table('route')->where('route_id', $shop->route)->first();
		
		$payment_mode = DB::table('mode_payment')->where('status', 0)->get();
		$shop_type = DB::table('shop_type')->where('status', 0)->get();
		$potential = DB::table('potential_sale')->where('status', 0)->get();
		$network = DB::table('network')->where('status', 0)->get();

		$explode_route = explode(',', $route_details->sales_rep_id);

		if(in_array($user_id, $explode_route)){
			return view('sales/shop_details', array('title' => 'Manage Shop', 'userdetails' => $user, 'shop_details' => $shop, 'route_details' => $route_details, 'payment_mode_list' => $payment_mode, 'shop_type_list' => $shop_type, 'potentiallist' => $potential, 'networklist' => $network, 'user_id' => $user_id));
		}
		else{
			return redirect('sales/shop');
		}
		
	}

	public function simallocateshop(){
		$id = Input::get('value');
		$count = Input::get('count');

		$already_sim = Input::get('already_sim');
		$sim_details = DB::table('sim')->where('ssn', $id)->first();
		if(count($sim_details)){
			if($sim_details->shop_id == ''){

				if($already_sim == ''){
					$result_sim = '<li>
	                        <div class="number">
	                          '.$sim_details->network_id.' - '.$sim_details->ssn.'
	                        </div>
	                        <div class="icon">
	                          <i class="fas fa-check-circle"></i>
	                        </div>
	                      </li>';
		            $message = '';
		            $type = 1;
		            $id = $sim_details->id;
		            $count = 1;
				}
				else{

					

		            $explode_already = explode(',', $already_sim);
		            krsort($explode_already);

		            $sim_new_details = DB::table('sim')->where('ssn', $id)->first();
		            if(in_array($sim_new_details->id,$explode_already)){
		            	$result_sim='';
		            	if(count($explode_already)){
		            		foreach ($explode_already as $single) {
		            			$sim_details = DB::table('sim')->where('id', $single)->first();							
								$result_sim.= '<li>
			                        <div class="number">
			                          '.$sim_details->network_id.' - '.$sim_details->ssn.'
			                        </div>
			                        <div class="icon">
			                          <i class="fas fa-check-circle"></i>
			                        </div>
			                      </li>';
		            		}
		            	}
		            	$message = 'SIM already scan...';
		            	$type = 4;
		            	$id = $already_sim;
		            	$count = count($explode_already);
		            }
		            else{
		            	$sim_new_details = DB::table('sim')->where('ssn', $id)->first();
		            	$commo = $already_sim.','.$sim_new_details->id;		            	
		            	$explode = explode(',', $commo);
		            	krsort($explode);
					
						$result_sim='';
						if(count($explode)){
							foreach ($explode as $single) {
								$sim_details = DB::table('sim')->where('id', $single)->first();							
								$result_sim.= '<li>
			                        <div class="number">
			                          '.$sim_details->network_id.' - '.$sim_details->ssn.'
			                        </div>
			                        <div class="icon">
			                          <i class="fas fa-check-circle"></i>
			                        </div>
			                      </li>';
				                  
							}
						}
						$id = $commo;					
			            $message = '';
			            $type = 1;
			            $count = count($explode);
		            }
					
				}

			}
			else{
				$result_sim='';
				$message = $id. ' SSN already allocated in shop.';
				$type= 2;
			}
			
		}
		else{
			$result_sim='';
			$message = $id. ' SSN not available in COMCO database';
			$type = 3;
		}

		echo json_encode(array('result_sim' => $result_sim, 'message' => $message, 'type' => $type, 'id' => $id, 'count' => $count));
		
	}
	public function simsave(){
		$sim = Input::get('sim');
		$shop_id = base64_decode(Input::get('shop_id'));
		$sales_rep_id = base64_decode(Input::get('sales_rep_id'));
		$area_id = base64_decode(Input::get('area_id'));
		$route_id = base64_decode(Input::get('route_id'));
		$date = date('Y-m-d');
		$time = date('H:i:s');

		$latitude = Input::get('latitude');
		$longitude = Input::get('longitude');

		$data['area_id'] = $area_id;
		$data['sales_rep_id'] = $sales_rep_id;
		$data['route_id'] = $route_id;
		$data['shop_id'] = $shop_id;
		$data['date'] = $date;
		$data['time'] = $time;
		$data['sim'] = $sim;
		$data['latitude'] = $latitude;
		$data['longitude'] = $longitude;

		DB::table('sim_allocate')->insert($data);

		$explode_sim = explode(',', $sim);
		if(count($explode_sim)){
			foreach ($explode_sim as $sim) {
				DB::table('sim')->where('id', $sim)->update(['shop_id' => $shop_id]);
			}
		}

		$shop_details = DB::table('shop')->where('shop_id', $shop_id)->first();

		$result = 'SIM was successfully allocated to '.$shop_details->shop_name;

		echo json_encode(array('message' => $result));

	}

	public function visitonly(){
		
		$shop_id = base64_decode(Input::get('shop_id'));
		$sales_rep_id = base64_decode(Input::get('sales_rep_id'));
		$area_id = base64_decode(Input::get('area_id'));
		$route_id = base64_decode(Input::get('route_id'));
		$date = date('Y-m-d');
		$time = date('H:i:s');

		$latitude = Input::get('latitude');
		$longitude = Input::get('longitude');

		
		

		$data['area_id'] = $area_id;
		$data['sales_rep_id'] = $sales_rep_id;
		$data['route_id'] = $route_id;
		$data['shop_id'] = $shop_id;
		$data['date'] = $date;
		$data['time'] = $time;

		$data['latitude'] = $latitude;
		$data['longitude'] = $longitude;
		

		DB::table('sim_allocate')->insert($data);

		
		$shop_details = DB::table('shop')->where('shop_id', $shop_id)->first();

		$result = 'Visit Only was successfully update to '.$shop_details->shop_name;

		echo json_encode(array('message' => $result));

	}

	public function filtershoproute(){
		$id = base64_decode(Input::get('id'));
		$user_id = Session::get('sales_userid');

		if($id != ''){
			$route_list = DB::table('route')->where('area_id', $id)->where('sales_rep_id', 'like', '%'.$user_id.'%')->get();
			$output_route='';
			if(count($route_list)){
				foreach ($route_list as $route) {
					$shop_count = DB::table('shop')->where('route', $route->route_id)->count();
					$output_route.='
                      <li>
                        <a href="'.URL::to('sales/shop_list_route/'.base64_encode($route->route_id)).'">
                          <div class="icon"><i class="fas fa-folder-open"></i></div>
                          <div class="text">'.$route->route_name.' ('.$shop_count.')</div>
                        </a>            
                      </li>
                      ';
				}
			}
			else{
				$output_route='
	              <li>
	                <a href="javascript:">
	                  <div class="icon"><i class="fas fa-folder-open"></i></div>
	                  <div class="text">No Route</div>
	                </a>            
	              </li>
	              ';
			}

			echo json_encode(array('output_route' => $output_route));
		}
		else{
			$userdetails = DB::table('sales_rep')->where('user_id', $user_id)->first();
			$area_route_list = explode(',', $userdetails->area);                 
            $output_route='';  
            $i=1;                
            
            if(count($area_route_list)){
              foreach ($area_route_list as $area_route) {                     

                $route_list = DB::table('route')->where('area_id', $area_route)->get();
                
                if(count($route_list)){
                  foreach ($route_list as $route) {
                    $explode_sales = explode(',', $route->sales_rep_id);
                    
                    $area_details = DB::table('area')->where('area_id',$route->area_id)->first();
                    $salesrep_details = DB::table('sales_rep')->where('user_id', $user_id)->first();

                    $explode_rep_id = explode(',', $route->sales_rep_id);

                    if(in_array($user_id, $explode_rep_id)){                      
                        $shop_count = DB::table('shop')->where('route', $route->route_id)->count();
                        $output_route.='
                          <li>
                            <a href="'.URL::to('sales/shop_list_route/'.base64_encode($route->route_id)).'">
                              <div class="icon"><i class="fas fa-folder-open"></i></div>
                              <div class="text">'.$route->route_name.' ('.$shop_count.')</div>
                            </a>            
                          </li>
                          ';
                          $i++;                      
                    }                                             
                  }

                }
                
              }
              if($i==1){
                $output_route='
                <li>
                  <a href="javascript:">
                    <div class="icon"><i class="fas fa-folder-open"></i></div>
                    <div class="text">No Route</div>
                  </a>            
                </li>
                ';
              }
            }
            else{
              $output_route='
              <li>
                <a href="javascript:">
                  <div class="icon"><i class="fas fa-folder-open"></i></div>
                  <div class="text">No Route</div>
                </a>            
              </li>
              ';
            }
            echo json_encode(array('output_route' => $output_route));
		}
	}

	
	public function searchcommon(){
		$value = Input::get('value');
		$type = Input::get('type');
		$route_id = base64_decode(Input::get('route_id'));
		$user_id = Session::get('sales_userid');

		if($type == 1){
			$shoplist = DB::table('shop')->where('route', $route_id)->where('shop_name', 'like', '%'.$value.'%')->where('status',0)->get();
		}
		if($type == 2){
			$shoplist = DB::table('shop')->where('route', $route_id)->where('shop_id', 'like', '%'.$value.'%')->where('status',0)->get();
		}
		if($type == 3){
			$shoplist = DB::table('shop')->where('route', $route_id)->where('postcode', 'like', '%'.$value.'%')->where('status',0)->get();
		}


		$shop_output='';
        $i=1;
        if(count($shoplist)){
          foreach ($shoplist as $shop) {

            if($shop->status == 0){
                $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Deactive" style="color: #33CC66"><i class="fas fa-check deactive_class" data-element="'.base64_encode($shop->shop_id).'"></i></a>';
            }
            else{
                $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Active" style="color: #E11B1C"><i class="fas fa-times active_class" data-element="'.base64_encode($shop->shop_id).'"></i></a>';
            }

            $edit_icon = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Area" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($shop->shop_id).'"></i></a>&nbsp;&nbsp;&nbsp;';

            if($shop->address2 != ''){
              $address2 = $shop->address2.'<br/>';
            }
            else{
              $address2 = $shop->address2;
            }
            if($shop->address3 != ''){
              $address3 = $shop->address3.'<br/>';
            }
            else{
              $address3 = $shop->address3;
            }

            $shop_output.='
            <tr>                  
              <td>
                <a href="'.URL::to('sales/shop_view_details/'.base64_encode($shop->shop_id)).'">
                  '.$shop->shop_name.'<br/>
                  CC-'.$shop->shop_id.'<br/>
                  '.$shop->address1.'<br/>
                  '.$address2.'
                  '.$address3.'
                  '.$shop->postcode.'<br/>

                </a>
              </td>
              
            </tr>';
            $i++;
          }
        }
        else{
          $shop_output='
          <tr>
            <td colspan="3" align="center">Empty</td>
          </tr>
          ';
        }

        echo json_encode(array('output_shop' => $shop_output));


	}
	public function simreturnshop(){
		$id = Input::get('value');
		$count = Input::get('count');
		$already_sim = Input::get('already_sim');
		$shop_id = base64_decode(Input::get('shop_id'));

		$sim_details = DB::table('sim')->where('ssn', $id)->first();

		if(count($sim_details)){
			$compare_shop_sim = $sim_details = DB::table('sim')->where('ssn', $id)->where('shop_id', $shop_id)->first();
			if(count($compare_shop_sim)){
				if($already_sim == ''){
					$result_sim = '<li>
	                        <div class="number">
	                          '.$sim_details->network_id.' - '.$sim_details->ssn.'
	                        </div>
	                        <div class="icon">
	                          <i class="fas fa-check-circle"></i>
	                        </div>
	                      </li>';
		            $message = '';
		            $type = 1;
		            $id = $compare_shop_sim->id;
		            $count = 1;
				}
				else{

					

		            $explode_already = explode(',', $already_sim);
		            krsort($explode_already);

		            $sim_new_details = DB::table('sim')->where('ssn', $id)->first();
		            if(in_array($sim_new_details->id,$explode_already)){
		            	$result_sim='';
		            	if(count($explode_already)){
		            		foreach ($explode_already as $single) {
		            			$sim_details = DB::table('sim')->where('id', $single)->first();							
								$result_sim.= '<li>
			                        <div class="number">
			                          '.$sim_details->network_id.' - '.$sim_details->ssn.'
			                        </div>
			                        <div class="icon">
			                          <i class="fas fa-check-circle"></i>
			                        </div>
			                      </li>';
		            		}
		            	}
		            	$message = 'SIM already scan...';
		            	$type = 4;
		            	$id = $already_sim;
		            	$count = count($explode_already);
		            }
		            else{
		            	$sim_new_details = DB::table('sim')->where('ssn', $id)->first();
		            	$commo = $already_sim.','.$sim_new_details->id;		            	
		            	$explode = explode(',', $commo);
		            	krsort($explode);
					
						$result_sim='';
						if(count($explode)){
							foreach ($explode as $single) {
								$sim_details = DB::table('sim')->where('id', $single)->first();							
								$result_sim.= '<li>
			                        <div class="number">
			                          '.$sim_details->network_id.' - '.$sim_details->ssn.'
			                        </div>
			                        <div class="icon">
			                          <i class="fas fa-check-circle"></i>
			                        </div>
			                      </li>';
				                  
							}
						}
						$id = $commo;					
			            $message = '';
			            $type = 1;
			            $count = count($explode);
		            }
					
				}
				
			}
			else{
				$result_sim='';
				$message = $id. ' SSN not allocated in this Shop';
				$type = 2;
			}

		}
		else{
			$result_sim='';
			$message = $id. ' SSN not available in COMCO database';
			$type = 3;
		}


		echo json_encode(array('result_sim' => $result_sim, 'message' => $message, 'type' => $type, 'id' => $id, 'count' => $count));
		
	}

	public function returnsim(){
		$sim = Input::get('sim');		
		$shop_id = base64_decode(Input::get('shop_id'));
		$sales_rep_id = base64_decode(Input::get('sales_rep_id'));
		$area_id = base64_decode(Input::get('area_id'));
		$route_id = base64_decode(Input::get('route_id'));
		$date = date('Y-m-d');
		$time = date('H:i:s');
		$latitude = Input::get('latitude');
		$longitude = Input::get('longitude');
		

		$data['area_id'] = $area_id;
		$data['sales_rep_id'] = $sales_rep_id;
		$data['route_id'] = $route_id;
		$data['shop_id'] = $shop_id;
		$data['date'] = $date;
		$data['time'] = $time;
		$data['sim'] = $sim;
		$data['latitude'] = $latitude;
		$data['longitude'] = $longitude;

		DB::table('return_sim')->insert($data);

		$explode_sim = explode(',', $sim);
		if(count($explode_sim)){
			foreach ($explode_sim as $sim) {
				$shop_id_empty = '';
				DB::table('sim')->where('id', $sim)->update(['shop_id' => $shop_id_empty]);
			}
		}

		$shop_details = DB::table('shop')->where('shop_id', $shop_id)->first();

		$result = 'SIM was successfully return from '.$shop_details->shop_name;

		echo json_encode(array('message' => $result));

	}

	public function search(){
		$user_id = Session::get('sales_userid');
		$user = DB::table('sales_rep')->where('user_id', $user_id)->first();		
		$route = DB::table('route')->get();

		return view('sales/search', array('title' => 'Search', 'userdetails' => $user, 'routelist' => $route, 'user_id' => $user_id));
	}

	public function searchcommonsales(){
		$value = Input::get('value');
		$type = Input::get('type');
		$user_id = Session::get('sales_userid');


		

		$salesrep_details = DB::table('sales_rep')->where('user_id', $user_id)->first();
		$explode_area_sales = explode(',', $salesrep_details->area);


		$route_id='';
		if(count($explode_area_sales)){
			foreach ($explode_area_sales as $area) {
				$route_details = DB::table('route')->where('area_id', $area)->get();
				if(count($route_details)){
					foreach ($route_details as $route) {
						$explode_route_sales = explode(',', $route->sales_rep_id);
						if(in_array($user_id, $explode_route_sales)){					

							if($route_id == ''){
								$route_id = $route->route_id;
							}
							else{
								$route_id = $route->route_id.','.$route_id;
							}
						}
					}
				}
			}
		}

		$explode_route = explode(',', $route_id);
		
		

		if($type == 1){
			$shoplist = DB::table('shop')->whereIn('route', $explode_route)->where('shop_name', 'like', '%'.$value.'%')->where('status',0)->get();
		}
		if($type == 2){
			$shoplist = DB::table('shop')->whereIn('route', $explode_route)->where('shop_id', 'like', '%'.$value.'%')->where('status',0)->get();
		}
		if($type == 3){
			$shoplist = DB::table('shop')->whereIn('route', $explode_route)->where('postcode', 'like', '%'.$value.'%')->where('status',0)->get();
		}

		$shop_output='';
        $i=1;
        if(count($shoplist)){
          foreach ($shoplist as $shop) {

            if($shop->status == 0){
                $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Deactive" style="color: #33CC66"><i class="fas fa-check deactive_class" data-element="'.base64_encode($shop->shop_id).'"></i></a>';
            }
            else{
                $status = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Active" style="color: #E11B1C"><i class="fas fa-times active_class" data-element="'.base64_encode($shop->shop_id).'"></i></a>';
            }

            $edit_icon = '<a href="javascript:" data-toggle="tooltip" data-placement="top" data-original-title="Edit Area" ><i class="far fa-edit edit_icon" data-element="'.base64_encode($shop->shop_id).'"></i></a>&nbsp;&nbsp;&nbsp;';

            if($shop->address2 != ''){
              $address2 = $shop->address2.'<br/>';
            }
            else{
              $address2 = $shop->address2;
            }
            if($shop->address3 != ''){
              $address3 = $shop->address3.'<br/>';
            }
            else{
              $address3 = $shop->address3;
            }

            $shop_output.='
            <tr>                  
              <td>
                <a href="'.URL::to('sales/shop_view_details/'.base64_encode($shop->shop_id)).'">
                  '.$shop->shop_name.'<br/>
                  CC-'.$shop->shop_id.'<br/>
                  '.$shop->address1.'<br/>
                  '.$address2.'
                  '.$address3.'
                  '.$shop->postcode.'<br/>

                </a>
              </td>
              
            </tr>';
            $i++;
          }
        }
        else{
          $shop_output='
          <tr>
            <td colspan="3" align="center">Empty</td>
          </tr>
          ';
        }

        echo json_encode(array('output_shop' => $shop_output));
	}
	public function resetpassword($id=""){
		$email_id = $id;
		$base_emaild = base64_decode($email_id);
		
		$shop_details = DB::table('shop')->where('email_id', $base_emaild)->first();

		$from = 'admin@comco-retail.co.uk';			
		$to = trim($base_emaild);	
		$subject_email = 'Let us reset your password';


		$contentmessage = '
		<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

		<div style="width:100%; min-height:600px; height:100%; float:left; background:#f1f1f1">
			<div style="max-width:600px; font-family: Roboto, sans-serif; width:100%; margin:0px auto;">

				<div style="max-width:600px; font-family: Roboto, sans-serif; font-size: 14px; width:90%; line-height:20px; float:left">
					<div style="width:83%; padding:0px 20px; max-width:600px; height:auto; float:left; text-align:center; margin:50px 20px 30px 20px;">
						<img src="https://comcotel.co.uk/assets/images/comco_logo_red.png" width="130px;" />
					</div>
					<div style="width:83%; max-width:600px; margin:0px 20px; height:auto; padding:20px; background:#fff; float:left;">
						<b>Dear '.$shop_details->shop_name.', </b><br/><br/>
						As requested, here’s a link to rest the password for your Comco online account. This link will only work once and must be used within 24 hours. <br/><br/>

						<a href="https://comcotel.co.uk/reset_password/'.$email_id.'" target="_blank" style="float:left; padding:10px 15px; color:#fff; background:#dc2c1d; text-decoration:none">Reset Password</a><br/><br/><br/>

						If you did not request this then please delete this email!<br/><br/>

						<b>Need help?</b><br/>
						Please talk to our team on 020 3322 5259<br/>
						Mon to Sat 9am – 6pm<br/><br/>

						Please do not reply to this automatically generated email.
					</div>

					<div style="width:83%; padding:0px 20px; max-width:600px; height:auto; float:left; text-align:center; margin:20px 20px 30px 20px;">
							<b>Comco group UK Ltd</b> | 4th Floor | 18 Cross Street | London | EC1N 8UN | <br/>
						Registration No.12336312 (England & Wales)
						</div>
				</div>
			</div>
		</div>';

		

		$email = new PHPMailer();
		$email->SetFrom($from, 'Comco');
		$email->Subject   = $subject_email;
		$email->Body      = $contentmessage;			
		$email->IsHTML(true);
		$email->AddAddress( $to );			
		$email->Send();		

		return Redirect::back()->with('message', 'Shop password reset link sent, Please check the shop email id');
	}

	public function useremailcheck(){
		if(isset($_GET['user_id']))
		{			
			$user_id = base64_decode(Input::get('user_id'));
			$email = Input::get('email_id');
			$validate = DB::table('shop')->where('email_id', $email)->where('shop_id','!=',$user_id)->count();
		}	
		else{			
			$email = Input::get('email_id');
			$validate = DB::table('shop')->where('email_id', $email)->count();
		}
		if($validate != 0 )
			$valid = false;
		else
			$valid = true;
		echo json_encode($valid);
		exit;
	}

	public function chequedetails(){
		$id = base64_decode(Input::get('id'));
		$type = Input::get('type');

		$details = DB::table('commission_paid')->where('paid_id', $id)->first();

		if($type == 4){
			$shop_details = DB::table('shop')->where('shop_id', $details->shop_id)->first();
			$content = 'You have cheque for shop '.$shop_details->shop_name.' commission of &#163; '.$details->commission.'. Please click the below for cheque status.';
			$title = 'Check Received';			
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title));
		}

	}
	public function chequeupdate(){
		$id = base64_decode(Input::get('id'));
		$data['status'] = Input::get('cheque_status');
		$data['given_type'] = Input::get('cheque_status');
		$data['given_date'] = date('Y-m-d');
		$data['given_time'] = date('h:i');

		DB::table('commission_paid')->where('paid_id', $id)->update($data);

		return redirect::back()->with('message', 'Cheque status was successfully updated');


	}



}

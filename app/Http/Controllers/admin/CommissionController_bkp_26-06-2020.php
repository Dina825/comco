<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
use URL;
use PDF;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use DateTime;
use ZipArchive;
class CommissionController extends Controller {

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
	
	public function commission()
	{
		$commission = DB::table('commission')->where('copy_status',0)->get();		
		$network = DB::table('network')->where('status', 0)->get();
		$connection = DB::table('connection')->where('commission', '')->get();
		return view('admin/commission', array('title' => 'Manage Commission', 'commission_list' => $commission, 'networklist' => $network, 'connectionlist' => $connection));
	}

	public function commissionadd(){		
		$plan_name = Input::get('plan_name');
		$connection_level = Input::get('connection_level');

		$networklist = Input::get('network');
		$network_implode = implode(',', $networklist);

		if(count($networklist)){
			foreach ($networklist as $key => $network) {
				$data_first[$network] = Input::get('first_'.$network);
				$data_bonus[$network] = Input::get('bonus_'.$network);
				$data_second[$network] = Input::get('second_'.$network);
				$data_third[$network] = Input::get('third_'.$network);
				$data_fourth[$network] = Input::get('fourth_'.$network);
				$data_fifth[$network] = Input::get('fifth_'.$network);
				$data_sixth[$network] = Input::get('sixth_'.$network);
				$data_seventh[$network] = Input::get('seventh_'.$network);
				$data_eighth[$network] = Input::get('eighth_'.$network);
				$data_ninth[$network] = Input::get('ninth_'.$network);
				$data_tenth[$network] = Input::get('tenth_'.$network);
			}
			$dataval['first'] = serialize($data_first);
			$dataval['bonus'] = serialize($data_bonus);
			$dataval['second'] = serialize($data_second);
			$dataval['third'] = serialize($data_third);
			$dataval['fourth'] = serialize($data_fourth);
			$dataval['fifth'] = serialize($data_fifth);
			$dataval['sixth'] = serialize($data_sixth);
			$dataval['seventh'] = serialize($data_seventh);
			$dataval['eighth'] = serialize($data_eighth);
			$dataval['ninth'] = serialize($data_ninth);
			$dataval['tenth'] = serialize($data_tenth);
			
			
			$dataval['network'] = $network_implode;
			$dataval['plan_name'] = $plan_name;
			$dataval['connection_level'] = $connection_level;
			$dataval['network'] = $network_implode;
			$id = DB::table('commission')->insertGetId($dataval);
		}

		$connection_id = Input::get('connection_level');

		DB::table('connection')->where('connection_id', $connection_id)->update(['commission' => $id]);		
		return redirect::back()->with('message', 'Commission was Add Succusfully');
	}

	public function commissiondetails(){
		$id = base64_decode(Input::get('id'));
		$type = Input::get('type');

		$details = DB::table('commission')->where('commission_id', $id)->first();
		if($type==1){
			$content = 'Are you sure want Active '.$details->plan_name.' Commission?';
			$title = 'Active Commission';
			$status = $details->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==2){
			$content = 'Are you sure want Deactivate '.$details->plan_name.' Commission?';
			$title = 'Deactivate Commission';
			$status = $details->status;
			echo json_encode(array('content' => $content, 'id' => base64_encode($id), 'title' => $title, 'status' => $status));
		}
		if($type==3){

			$connectionlist = DB::table('connection')->where('commission', '')->get();
			$connection_select = DB::table('connection')->where('connection_id', $details->connection_level)->first();
			$ii = 0;
			if(count($connection_select))
			{
				$output_connect='<option value="">Select Connections Level</option>';
            	$output_connect.='<option value="'.$connection_select->connection_id.'" selected>'.$connection_select->level.'</option>';
            	$ii++;
			}
			
            if(count($connectionlist)){
	            foreach ($connectionlist as $connection) {
	                $output_connect.='<option value="'.$connection->connection_id.'">'.$connection->level.'</option>';
	                $ii++;
	            }
            }

            if($ii == 0)
            {
            	$output_connect ='<option value="">Connections Level Empty</option>';
            }

            $networklist = explode(',', $details->network);

            $output_table='';
            if(count($networklist)){
            	foreach ($networklist as $network) {
            		$unserlize_first = unserialize($details->first);
            		$unserlize_bonus = unserialize($details->bonus);
            		$unserlize_second = unserialize($details->second);
            		$unserlize_third = unserialize($details->third);
            		$unserlize_fourth = unserialize($details->fourth);
            		$unserlize_fifth = unserialize($details->fifth);
            		$unserlize_sixth = unserialize($details->sixth);
            		$unserlize_seventh = unserialize($details->seventh);
            		$unserlize_eighth = unserialize($details->eighth);
            		$unserlize_ninth = unserialize($details->ninth);
            		$unserlize_tenth = unserialize($details->tenth);

            		$output_table.='
            		<tr>
            			<td>'.$network.' <input type="hidden" value="'.$network.'" name="network[]" /> </td>
            			<td><input type="text" required class="plan_input" value="'.$unserlize_first[$network].'" name="first_'.$network.'" /></td>
            			<td><input type="text" required class="plan_input" value="'.$unserlize_bonus[$network].'"  name="bonus_'.$network.'"/></td>
            			<td><input type="text" required class="plan_input" value="'.$unserlize_second[$network].'" name="second_'.$network.'"/></td>
            			<td><input type="text" required class="plan_input" value="'.$unserlize_third[$network].'" name="third_'.$network.'"/></td>
            			<td><input type="text" required class="plan_input" value="'.$unserlize_fourth[$network].'" name="fourth_'.$network.'"/></td>
            			<td><input type="text" required class="plan_input" value="'.$unserlize_fifth[$network].'" name="fifth_'.$network.'"/></td>
            			<td><input type="text" required class="plan_input" value="'.$unserlize_sixth[$network].'" name="sixth_'.$network.'" /></td>
            			<td><input type="text" required class="plan_input" value="'.$unserlize_seventh[$network].'" name="seventh_'.$network.'" /></td>
            			<td><input type="text" required class="plan_input" value="'.$unserlize_eighth[$network].'" name="eighth_'.$network.'" /></td>
            			<td><input type="text" required class="plan_input" value="'.$unserlize_ninth[$network].'" name="ninth_'.$network.'" /></td>
            			<td><input type="text" required class="plan_input" value="'.$unserlize_tenth[$network].'" name="tenth_'.$network.'" /></td>
            		</tr>
            		';
            	}
            }


			echo json_encode(array('id' => base64_encode($details->commission_id), 'name' => $details->plan_name, 'connection_level' => $output_connect, 'output_table' => $output_table));
		}

	}

	public function commissionstatus(){
		$id = base64_decode(Input::get('id'));		
		$status = Input::get('status');

		if($status == 0){
			$statuschange = 1;
			DB::table('commission')->where('commission_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Commission was successfully deactived');
		}
		if($status == 1){
			$statuschange = 0;
			DB::table('commission')->where('commission_id', $id)->update(['status'=> $statuschange]);
			return Redirect::back()->with('message', 'Commission was successfully actived');
		}
	}	

	public function commissionupdate(){
		$commission_id = base64_decode(Input::get('id'));
		$details_commission = DB::table('commission')->where('commission_id', $commission_id)->first();	

		$old_connection = $details_commission->connection_level;
		DB::table('connection')->where('connection_id', $old_connection)->update(['commission' => '']);


		$connection_id = Input::get('connection_level');
		DB::table('connection')->where('connection_id', $connection_id)->update(['commission' => $commission_id]);		



		$plan_name = Input::get('plan_name');
		$connection_level = Input::get('connection_level');

		$networklist = Input::get('network');
		$network_implode = implode(',', $networklist);

		if(count($networklist)){
			foreach ($networklist as $key => $network) {
				$data_first[$network] = Input::get('first_'.$network);
				$data_bonus[$network] = Input::get('bonus_'.$network);
				$data_second[$network] = Input::get('second_'.$network);
				$data_third[$network] = Input::get('third_'.$network);
				$data_fourth[$network] = Input::get('fourth_'.$network);
				$data_fifth[$network] = Input::get('fifth_'.$network);
				$data_sixth[$network] = Input::get('sixth_'.$network);
				$data_seventh[$network] = Input::get('seventh_'.$network);
				$data_eighth[$network] = Input::get('eighth_'.$network);
				$data_ninth[$network] = Input::get('ninth_'.$network);
				$data_tenth[$network] = Input::get('tenth_'.$network);
			}
			$dataval['first'] = serialize($data_first);
			$dataval['bonus'] = serialize($data_bonus);
			$dataval['second'] = serialize($data_second);
			$dataval['third'] = serialize($data_third);
			$dataval['fourth'] = serialize($data_fourth);
			$dataval['fifth'] = serialize($data_fifth);
			$dataval['sixth'] = serialize($data_sixth);
			$dataval['seventh'] = serialize($data_seventh);
			$dataval['eighth'] = serialize($data_eighth);
			$dataval['ninth'] = serialize($data_ninth);
			$dataval['tenth'] = serialize($data_tenth);
			
			
			$dataval['network'] = $network_implode;
			$dataval['plan_name'] = $plan_name;
			$dataval['connection_level'] = $connection_level;
			$dataval['network'] = $network_implode;
			DB::table('commission')->where('commission_id', $commission_id)->update($dataval);
		}

		
		return redirect::back()->with('message', 'Commission was updated Succusfully');
	}
	public function upload_commission()
	{
		$timedate = date('H:i:s');
		$upload_datetime = date('Y-m-d H:i:s');
		$commission_datetime = $_POST['commission_date'].' '.$timedate;
		if($_FILES['commission_file']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';

			$create_upload_dir = 'uploads/importfiles';
			if(!file_exists($create_upload_dir))
			{
				mkdir($create_upload_dir);
			}

			$tmp_name = $_FILES['commission_file']['tmp_name'];
			$name=$_FILES['commission_file']['name'];

			$duplicated = 0;
			$network_dint_match = 0;
			$field_empty = 0;
			$topup_zero = 0;
			$ssn_dint_match = 0;
			$inserted = 0;
			$not_ignored = 0;
			$ignored = 0;

			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){
				$filepath = $uploads_dir.'/'.$name;
				$objPHPExcel = PHPExcel_IOFactory::load($filepath);
				foreach ($objPHPExcel->getWorksheetIterator() as $keysheet => $worksheet) {
					if($keysheet == 2)
					{
						$worksheetTitle     = $worksheet->getTitle();
						$highestRow         = $worksheet->getHighestRow(); // e.g. 10
						$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
						$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
						$nrColumns = ord($highestColumn) - 64;
						if($highestRow > 100)
						{
							$height = 100;
						}
						else{
							$height = $highestRow;
						}

						$payrun_title = $worksheet->getCellByColumnAndRow(0, 2); $payrun_title = trim($payrun_title->getValue());
						$network_title = $worksheet->getCellByColumnAndRow(1, 2); $network_title = trim($network_title->getValue());
						$xssn_title = $worksheet->getCellByColumnAndRow(2, 2); $xssn_title = trim($xssn_title->getValue());
						$ssn_title = $worksheet->getCellByColumnAndRow(3, 2); $ssn_title = trim($ssn_title->getValue());
						$cli_title = $worksheet->getCellByColumnAndRow(4, 2); $cli_title = trim($cli_title->getValue());
						$connection_title = $worksheet->getCellByColumnAndRow(5, 2); $connection_title = trim($connection_title->getValue());
						$topup_title = $worksheet->getCellByColumnAndRow(6, 2); $topup_title = trim($topup_title->getValue());

						if($payrun_title == "Payrun" && $network_title == "Network" && $xssn_title == "xSSN" && $ssn_title == "SSN" && $cli_title == "CLI" && $connection_title == "Connection Date" && $topup_title == "Top Up Date")
						{
							for ($row = 3; $row <= $height; ++ $row) {
								$payrun = $worksheet->getCellByColumnAndRow(0, $row); $payrun = trim($payrun->getValue());
								$network = $worksheet->getCellByColumnAndRow(1, $row); $network = trim($network->getValue());
								$xssn = $worksheet->getCellByColumnAndRow(2, $row); $xssn = trim($xssn->getValue());
								$ssn = $worksheet->getCellByColumnAndRow(3, $row); $ssn = trim($ssn->getValue());
								$cli = $worksheet->getCellByColumnAndRow(4, $row); $cli = trim($cli->getValue());
								$connection_date = $worksheet->getCellByColumnAndRow(5, $row); $connection_date = trim($connection_date->getValue());
								$topup_date = $worksheet->getCellByColumnAndRow(6, $row); $topup_date = trim($topup_date->getValue());

								$month = $worksheet->getCellByColumnAndRow(7, $row); $month = trim($month->getValue());
								$topup_no = $worksheet->getCellByColumnAndRow(8, $row); $topup_no = trim($topup_no->getValue());
								$topup_value = $worksheet->getCellByColumnAndRow(9, $row); $topup_value = trim($topup_value->getValue());
								$ga = $worksheet->getCellByColumnAndRow(10, $row); $ga = trim($ga->getValue());
								$revenue = $worksheet->getCellByColumnAndRow(11, $row); $revenue = trim($revenue->getValue());
								$rev_share = $worksheet->getCellByColumnAndRow(12, $row); $rev_share = trim($rev_share->getValue());
								$tiered = $worksheet->getCellByColumnAndRow(13, $row); $tiered = trim($tiered->getValue());
								$bonus = $worksheet->getCellByColumnAndRow(14, $row); $bonus = trim($bonus->getValue());
								$additional = $worksheet->getCellByColumnAndRow(15, $row); $additional = trim($additional->getValue());
								$additional_bonus = $worksheet->getCellByColumnAndRow(16, $row); $additional_bonus = trim($additional_bonus->getValue());
								$total = $worksheet->getCellByColumnAndRow(17, $row); $total = trim($total->getValue());
								$shop_id = $worksheet->getCellByColumnAndRow(18, $row); $shop_id = trim($shop_id->getValue());
								$shop_name = $worksheet->getCellByColumnAndRow(19, $row); $shop_name = trim($shop_name->getValue());
								$master_name = $worksheet->getCellByColumnAndRow(20, $row); $master_name = trim($master_name->getValue());

								


								$unix_date = ($connection_date - 25569) * 86400;
								$excel_date = 25569 + ($unix_date / 86400);
								$unix_date = ($excel_date - 25569) * 86400;
								$connection_date = gmdate("Y-m-d", $unix_date);


								$unix_date = ($topup_date - 25569) * 86400;
								$excel_date = 25569 + ($unix_date / 86400);
								$unix_date = ($excel_date - 25569) * 86400;
								$topup_date = gmdate("Y-m-d", $unix_date);

								if($network == "" || $ssn == "" || $topup_date == "" || $topup_no == "")
								{
									$field_empty++;
								}
								else{
									if($topup_no == "0" || $topup_no == 0) { $topup_zero++; }
									else{
										$check_ssn = DB::table('commission_manager')->where('network_id',$network)->where('ssn',$ssn)->where('cli',$cli)->where('topup_date',$topup_date)->where('month',$month)->where('topup_no',$topup_no)->first();
										if(count($check_ssn))
										{
											$duplicated++;
										}
										else{
											$get_network_details = DB::table('network')->where('network_name',$network)->first();
											if(count($get_network_details))
											{
												$get_shop_id = DB::table('sim')->where('ssn',$ssn)->first();
												if(count($get_shop_id))
												{
													$datasim['payrun'] = $payrun;
													$datasim['network_id'] = $network;
													$datasim['xssn'] = $xssn;
													$datasim['ssn'] = $ssn;
													$datasim['cli'] = $cli;
													$datasim['connection_date'] = $connection_date;
													$datasim['topup_date'] = $topup_date;
													$datasim['month'] = $month;
													$datasim['topup_no'] = $topup_no;
													$datasim['topup_value'] = $topup_value;
													$datasim['ga'] = $ga;
													$datasim['revenue'] = $revenue;
													$datasim['rev_share'] = $rev_share;
													$datasim['tiered'] = $tiered;
													$datasim['bonus'] = $bonus;
													$datasim['additional'] = $additional;
													$datasim['additional_bonus'] = $additional_bonus;
													$datasim['total'] = $total;
													$datasim['shop_id'] = $shop_id;
													$datasim['shop_name'] = $shop_name;
													$datasim['master_name'] = $master_name;
													$datasim['uploaded_date'] = $commission_datetime;
													$datasim['import_date'] = $upload_datetime;
													$datasim['sim_shop_id'] = $get_shop_id->shop_id;
													DB::table('commission_manager')->insert($datasim);
													$inserted++;
												}
												else{
													$ssn_dint_match++;
												}
												
											}
											else{
												$network_dint_match++;
											}
										}
									}
								}
							}
						}
						else{
							return redirect('admin/commission')->with('message', 'Import Failed! Invalid File');
						}
					}
				}
				if($height >= $highestRow)
				{
					$plan_array = array();
					$new_plan_array = array();
					$get_date_sim_cards = DB::table('commission_manager')->where('uploaded_date',$commission_datetime)->where('sim_shop_id','!=',0)->groupBy('sim_shop_id')->get();
					if(count($get_date_sim_cards))
					{
						foreach($get_date_sim_cards as $sim)
						{
							$get_date_sim_networks = DB::table('commission_manager')->where('uploaded_date',$commission_datetime)->where('sim_shop_id',$sim->sim_shop_id)->groupBy('network_id')->get();
							if(count($get_date_sim_networks))
							{
								foreach($get_date_sim_networks as $networks)
								{
									$network_details = DB::table('network')->where('network_name',$networks->network_id)->first();
									if(count($network_details))
									{
										$minimum_value = $network_details->minimum_value;
									}
									else{
										$minimum_value = 0;
									}
									$get_date_sim_networks_value = DB::table('commission_manager')->where('uploaded_date',$commission_datetime)->where('sim_shop_id',$sim->sim_shop_id)->where('network_id',$networks->network_id)->get();
									if(count($get_date_sim_networks_value))
									{
										foreach($get_date_sim_networks_value as $network_value)
										{
											if($network_value->topup_value >= $minimum_value)
											{
												$dataminimum['ignore_sim'] = 0;
												$not_ignored++;
											}
											else{
												$dataminimum['ignore_sim'] = 1;
												$ignored++;
											}
											DB::table('commission_manager')->where('id',$network_value->id)->update($dataminimum);
										}
									}
								}
							}
							$get_shop_details = DB::table('shop')->where('shop_id',$sim->sim_shop_id)->first();
							if($get_shop_details->commission_plan != 0)
							{
								$plan = $get_shop_details->commission_plan;
							}
							else{
								$get_first_connection_for_shop_count = DB::table('commission_manager')->where('uploaded_date',$commission_datetime)->where('sim_shop_id',$sim->sim_shop_id)->where('ignore_sim',0)->where('topup_no',1)->count();
								if($get_first_connection_for_shop_count == 0)
								{
									$plan = 1;
								}
								else{
									$get_tier = DB::table('connection')->where('level','<=',$get_first_connection_for_shop_count)->where('status',0)->orderBy('level','desc')->first();
									if(count($get_tier))
									{
										$plan = $get_tier->commission;
									}
									else{
										$plan = 1;
									}
								}
							}
							if(in_array($plan,$plan_array))
							{
								$keyvalue = array_search($plan,$plan_array);
								$plan_id = $new_plan_array[$keyvalue];
							}
							else{
								$get_plan_details = DB::table('commission')->where('commission_id',$plan)->first();
								if(count($get_plan_details))
								{
									$plan_det['plan_name'] = $get_plan_details->plan_name;
									$plan_det['connection_level'] = $get_plan_details->connection_level;
									$plan_det['network'] = $get_plan_details->network;
									$plan_det['first'] = $get_plan_details->first;
									$plan_det['bonus'] = $get_plan_details->bonus;
									$plan_det['second'] = $get_plan_details->second;
									$plan_det['third'] = $get_plan_details->third;
									$plan_det['fourth'] = $get_plan_details->fourth;
									$plan_det['fifth'] = $get_plan_details->fifth;
									$plan_det['sixth'] = $get_plan_details->sixth;
									$plan_det['seventh'] = $get_plan_details->seventh;
									$plan_det['eighth'] = $get_plan_details->eighth;
									$plan_det['ninth'] = $get_plan_details->ninth;
									$plan_det['tenth'] = $get_plan_details->tenth;
									$plan_det['status'] = $get_plan_details->status;
									$plan_det['copy_status'] = 1;
									$plan_id = DB::table('commission')->insertGetId($plan_det);
									array_push($plan_array,$plan);
									array_push($new_plan_array,$plan_id);
								}
							}

							$commission_details = DB::table('commission')->where('commission_id',$plan_id)->first();
							if(count($commission_details))
							{
								$one_array = unserialize($commission_details->first);
								$bonus_array = unserialize($commission_details->bonus);
								$two_array = unserialize($commission_details->second);
								$three_array = unserialize($commission_details->third);
								$four_array = unserialize($commission_details->fourth);
								$five_array = unserialize($commission_details->fifth);
								$six_array = unserialize($commission_details->sixth);
								$seven_array = unserialize($commission_details->seventh);
								$eight_array = unserialize($commission_details->eighth);
								$nine_array = unserialize($commission_details->ninth);
								$ten_array = unserialize($commission_details->tenth);
							}
							$plannn['plan_id'] = $plan_id;
							DB::table('commission_manager')->where('uploaded_date',$commission_datetime)->where('sim_shop_id',$sim->sim_shop_id)->update($plannn);
							if(count($get_date_sim_networks))
							{
								foreach($get_date_sim_networks as $networks)
								{
									$data_commission['plan_id'] = $plan_id;
									$data_commission['commission_date'] = $commission_datetime;
									$data_commission['import_date'] = $upload_datetime;
									$data_commission['shop_id'] = $networks->sim_shop_id;
									$data_commission['network_id'] = $networks->network_id;

									$data_commission['one_connection'] = DB::table('commission_manager')->where('uploaded_date',$commission_datetime)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',1)->where('ignore_sim',0)->count();
									$data_commission['two_topup'] = DB::table('commission_manager')->where('uploaded_date',$commission_datetime)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',2)->where('ignore_sim',0)->count();
									$data_commission['three_topup'] = DB::table('commission_manager')->where('uploaded_date',$commission_datetime)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',3)->where('ignore_sim',0)->count();
									$data_commission['four_topup'] = DB::table('commission_manager')->where('uploaded_date',$commission_datetime)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',4)->where('ignore_sim',0)->count();
									$data_commission['five_topup'] = DB::table('commission_manager')->where('uploaded_date',$commission_datetime)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',5)->where('ignore_sim',0)->count();
									$data_commission['six_topup'] = DB::table('commission_manager')->where('uploaded_date',$commission_datetime)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',6)->where('ignore_sim',0)->count();
									$data_commission['seven_topup'] = DB::table('commission_manager')->where('uploaded_date',$commission_datetime)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',7)->where('ignore_sim',0)->count();
									$data_commission['eight_topup'] = DB::table('commission_manager')->where('uploaded_date',$commission_datetime)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',8)->where('ignore_sim',0)->count();
									$data_commission['nine_topup'] = DB::table('commission_manager')->where('uploaded_date',$commission_datetime)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',9)->where('ignore_sim',0)->count();
									$data_commission['ten_topup'] = DB::table('commission_manager')->where('uploaded_date',$commission_datetime)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',10)->where('ignore_sim',0)->count();

									$data_commission['one_allotted'] = $data_commission['one_connection'];
									$data_commission['two_allotted'] = $data_commission['two_topup'];
									$data_commission['three_allotted'] = $data_commission['three_topup'];
									$data_commission['four_allotted'] = $data_commission['four_topup'];
									$data_commission['five_allotted'] = $data_commission['five_topup'];
									$data_commission['six_allotted'] = $data_commission['six_topup'];
									$data_commission['seven_allotted'] = $data_commission['seven_topup'];
									$data_commission['eight_allotted'] = $data_commission['eight_topup'];
									$data_commission['nine_allotted'] = $data_commission['nine_topup'];
									$data_commission['ten_allotted'] = $data_commission['ten_topup'];

									$one_cost = $data_commission['one_connection'] * $one_array[$networks->network_id];
									$one_bonus = $data_commission['one_connection'] * $bonus_array[$networks->network_id];

									$two_cost = $data_commission['two_topup'] * $two_array[$networks->network_id];
									$three_cost = $data_commission['three_topup'] * $three_array[$networks->network_id];
									$four_cost = $data_commission['four_topup'] * $four_array[$networks->network_id];
									$five_cost = $data_commission['five_topup'] * $five_array[$networks->network_id];
									$six_cost = $data_commission['six_topup'] * $six_array[$networks->network_id];
									$seven_cost = $data_commission['seven_topup'] * $seven_array[$networks->network_id];
									$eight_cost = $data_commission['eight_topup'] * $eight_array[$networks->network_id];
									$nine_cost = $data_commission['nine_topup'] * $nine_array[$networks->network_id];
									$ten_cost = $data_commission['ten_topup'] * $ten_array[$networks->network_id];
									$data_commission['one_cost'] = $one_cost + $one_bonus;
									$data_commission['bonus_cost'] = $one_bonus;

									$data_commission['one_allotted_cost'] = $one_cost + $one_bonus;
									$data_commission['bonus_allotted_cost'] = $one_bonus;

									$data_commission['two_cost'] = $two_cost;
									$data_commission['two_allotted_cost'] = $two_cost;

									$data_commission['three_cost'] = $three_cost;
									$data_commission['three_allotted_cost'] = $three_cost;

									$data_commission['four_cost'] = $four_cost;
									$data_commission['four_allotted_cost'] = $four_cost;

									$data_commission['five_cost'] = $five_cost;
									$data_commission['five_allotted_cost'] = $five_cost;

									$data_commission['six_cost'] = $six_cost;
									$data_commission['six_allotted_cost'] = $six_cost;

									$data_commission['seven_cost'] = $seven_cost;
									$data_commission['seven_allotted_cost'] = $seven_cost;

									$data_commission['eight_cost'] = $eight_cost;
									$data_commission['eight_allotted_cost'] = $eight_cost;

									$data_commission['nine_cost'] = $nine_cost;
									$data_commission['nine_allotted_cost'] = $nine_cost;

									$data_commission['ten_cost'] = $ten_cost;
									$data_commission['ten_allotted_cost'] = $ten_cost;
									DB::table('shop_commission')->insert($data_commission);
								}
							}
						}
					}

					DB::table('shop_commission')->where('one_connection',0)->where('two_topup',0)->where('three_topup',0)->where('four_topup',0)->where('five_topup',0)->where('six_topup',0)->where('seven_topup',0)->where('eight_topup',0)->where('nine_topup',0)->where('ten_topup',0)->where('proceeded',0)->delete();
					$dataarray['message'] = 'Commission Imported successfully.';
					$dataarray['duplicated'] = $duplicated;
					$dataarray['network_dint_match'] = $network_dint_match;
					$dataarray['field_empty'] = $field_empty;
					$dataarray['topup_zero'] = $topup_zero;
					$dataarray['ssn_dint_match'] = $ssn_dint_match;
					$dataarray['inserted'] = $inserted;
					$dataarray['ignored'] = $ignored;
					$dataarray['not_ignored'] = $not_ignored;

					return redirect('admin/upload_commission_page?comm_date='.$commission_datetime.'')->with('success_error', $dataarray);
				}
				else{
					return redirect('admin/commission?filename='.$name.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type_new=1&comm_date='.$commission_datetime.'&import_date='.$upload_datetime.'&duplicated='.$duplicated.'&network_dint_match='.$network_dint_match.'&field_empty='.$field_empty.'&topup_zero='.$topup_zero.'&ssn_dint_match='.$ssn_dint_match.'&inserted='.$inserted.'&ignored='.$ignored.'&not_ignored='.$not_ignored.'');
				}
			}
		}
	}
	public function upload_commission_one()
	{
		$name = Input::get('filename');
		$comm_date = Input::get('comm_date');
		$import_date = Input::get('import_date');

		$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
		$filepath = $uploads_dir.'/'.$name;
		$objPHPExcel = PHPExcel_IOFactory::load($filepath);
		
		$duplicated = $_GET['duplicated'];
		$network_dint_match = $_GET['network_dint_match'];
		$field_empty = $_GET['field_empty'];
		$topup_zero = $_GET['topup_zero'];
		$ssn_dint_match = $_GET['ssn_dint_match'];
		$inserted = $_GET['inserted'];
		$ignored = $_GET['ignored'];
		$not_ignored = $_GET['not_ignored'];
		

		foreach ($objPHPExcel->getWorksheetIterator() as $keysheet => $worksheet) {
			if($keysheet == 2)
			{
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow(); // e.g. 10
				$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

				$nrColumns = ord($highestColumn) - 64;
				$round = Input::get('round');
				$last_height = Input::get('height');
				$offset = $round - 1;
				$offsetcount = $last_height + 1;
				$roundcount = $round * 100;
				$nextround = $round + 1;
				if($highestRow > $roundcount)
				{
					$height = $roundcount;
				}
				else{
					$height = $highestRow;
				}
				for ($row = $offsetcount; $row <= $height; ++ $row) {
					$payrun = $worksheet->getCellByColumnAndRow(0, $row); $payrun = trim($payrun->getValue());
					$network = $worksheet->getCellByColumnAndRow(1, $row); $network = trim($network->getValue());
					$xssn = $worksheet->getCellByColumnAndRow(2, $row); $xssn = trim($xssn->getValue());
					$ssn = $worksheet->getCellByColumnAndRow(3, $row); $ssn = trim($ssn->getValue());
					$cli = $worksheet->getCellByColumnAndRow(4, $row); $cli = trim($cli->getValue());
					$connection_date = $worksheet->getCellByColumnAndRow(5, $row); $connection_date = trim($connection_date->getValue());
					$topup_date = $worksheet->getCellByColumnAndRow(6, $row); $topup_date = trim($topup_date->getValue());

					$month = $worksheet->getCellByColumnAndRow(7, $row); $month = trim($month->getValue());
					$topup_no = $worksheet->getCellByColumnAndRow(8, $row); $topup_no = trim($topup_no->getValue());
					$topup_value = $worksheet->getCellByColumnAndRow(9, $row); $topup_value = trim($topup_value->getValue());
					$ga = $worksheet->getCellByColumnAndRow(10, $row); $ga = trim($ga->getValue());
					$revenue = $worksheet->getCellByColumnAndRow(11, $row); $revenue = trim($revenue->getValue());
					$rev_share = $worksheet->getCellByColumnAndRow(12, $row); $rev_share = trim($rev_share->getValue());
					$tiered = $worksheet->getCellByColumnAndRow(13, $row); $tiered = trim($tiered->getValue());
					$bonus = $worksheet->getCellByColumnAndRow(14, $row); $bonus = trim($bonus->getValue());
					$additional = $worksheet->getCellByColumnAndRow(15, $row); $additional = trim($additional->getValue());
					$additional_bonus = $worksheet->getCellByColumnAndRow(16, $row); $additional_bonus = trim($additional_bonus->getValue());
					$total = $worksheet->getCellByColumnAndRow(17, $row); $total = trim($total->getValue());
					$shop_id = $worksheet->getCellByColumnAndRow(18, $row); $shop_id = trim($shop_id->getValue());
					$shop_name = $worksheet->getCellByColumnAndRow(19, $row); $shop_name = trim($shop_name->getValue());
					$master_name = $worksheet->getCellByColumnAndRow(20, $row); $master_name = trim($master_name->getValue());

					


					$unix_date = ($connection_date - 25569) * 86400;
					$excel_date = 25569 + ($unix_date / 86400);
					$unix_date = ($excel_date - 25569) * 86400;
					$connection_date = gmdate("Y-m-d", $unix_date);


					$unix_date = ($topup_date - 25569) * 86400;
					$excel_date = 25569 + ($unix_date / 86400);
					$unix_date = ($excel_date - 25569) * 86400;
					$topup_date = gmdate("Y-m-d", $unix_date);

					if($network == "" || $ssn == "" || $topup_date == "" || $topup_no == "")
					{
						$field_empty++;
					}
					else{
						if($topup_no == "0" || $topup_no == 0) { $topup_zero++; }
						else{
							$check_ssn = DB::table('commission_manager')->where('network_id',$network)->where('ssn',$ssn)->where('cli',$cli)->where('topup_date',$topup_date)->where('month',$month)->where('topup_no',$topup_no)->first();
							if(count($check_ssn))
							{
								$duplicated++;
							}
							else{
								$get_network_details = DB::table('network')->where('network_name',$network)->first();
								if(count($get_network_details))
								{
									$get_shop_id = DB::table('sim')->where('ssn',$ssn)->first();
									if(count($get_shop_id))
									{
										$datasim['payrun'] = $payrun;
										$datasim['network_id'] = $network;
										$datasim['xssn'] = $xssn;
										$datasim['ssn'] = $ssn;
										$datasim['cli'] = $cli;
										$datasim['connection_date'] = $connection_date;
										$datasim['topup_date'] = $topup_date;
										$datasim['month'] = $month;
										$datasim['topup_no'] = $topup_no;
										$datasim['topup_value'] = $topup_value;
										$datasim['ga'] = $ga;
										$datasim['revenue'] = $revenue;
										$datasim['rev_share'] = $rev_share;
										$datasim['tiered'] = $tiered;
										$datasim['bonus'] = $bonus;
										$datasim['additional'] = $additional;
										$datasim['additional_bonus'] = $additional_bonus;
										$datasim['total'] = $total;
										$datasim['shop_id'] = $shop_id;
										$datasim['shop_name'] = $shop_name;
										$datasim['master_name'] = $master_name;
										$datasim['uploaded_date'] = $comm_date;
										$datasim['import_date'] = $import_date;
										$datasim['sim_shop_id'] = $get_shop_id->shop_id;
										DB::table('commission_manager')->insert($datasim);
										$inserted++;
									}
									else{
										$ssn_dint_match++;
									}
								}
								else{
									$network_dint_match++;
								}
							}
						}
					}
				}
			}
		}
		if($height >= $highestRow)
		{
			$plan_array = array();
			$new_plan_array = array();
			$get_date_sim_cards = DB::table('commission_manager')->where('uploaded_date',$comm_date)->where('sim_shop_id','!=',0)->groupBy('sim_shop_id')->get();
			if(count($get_date_sim_cards))
			{
				foreach($get_date_sim_cards as $sim)
				{
					$get_date_sim_networks = DB::table('commission_manager')->where('uploaded_date',$comm_date)->where('sim_shop_id',$sim->sim_shop_id)->groupBy('network_id')->get();
					if(count($get_date_sim_networks))
					{
						foreach($get_date_sim_networks as $networks)
						{
							$network_details = DB::table('network')->where('network_name',$networks->network_id)->first();
							if(count($network_details))
							{
								$minimum_value = $network_details->minimum_value;
							}
							else{
								$minimum_value = 0;
							}
							$get_date_sim_networks_value = DB::table('commission_manager')->where('uploaded_date',$comm_date)->where('sim_shop_id',$sim->sim_shop_id)->where('network_id',$networks->network_id)->get();
							if(count($get_date_sim_networks_value))
							{
								foreach($get_date_sim_networks_value as $network_value)
								{
									if($network_value->topup_value >= $minimum_value)
									{
										$dataminimum['ignore_sim'] = 0;
										$not_ignored++;
									}
									else{
										$dataminimum['ignore_sim'] = 1;
										$ignored++;
									}
									DB::table('commission_manager')->where('id',$network_value->id)->update($dataminimum);
								}
							}
						}
					}
					$get_shop_details = DB::table('shop')->where('shop_id',$sim->sim_shop_id)->first();
					if($get_shop_details->commission_plan != 0)
					{
						$plan = $get_shop_details->commission_plan;
					}
					else{
						$get_first_connection_for_shop_count = DB::table('commission_manager')->where('uploaded_date',$comm_date)->where('sim_shop_id',$sim->sim_shop_id)->where('topup_no',1)->where('ignore_sim',0)->count();
						if($get_first_connection_for_shop_count == 0)
						{
							$plan = 1;
						}
						else{
							$get_tier = DB::table('connection')->where('level','<=',$get_first_connection_for_shop_count)->where('status',0)->orderBy('level','desc')->first();
							if(count($get_tier))
							{
								$plan = $get_tier->commission;
							}
							else{
								$plan = 1;
							}
						}
					}

					if(in_array($plan,$plan_array))
					{
						$keyvalue = array_search($plan,$plan_array);
						$plan_id = $new_plan_array[$keyvalue];
					}
					else{
						$get_plan_details = DB::table('commission')->where('commission_id',$plan)->first();
						if(count($get_plan_details))
						{
							$plan_det['plan_name'] = $get_plan_details->plan_name;
							$plan_det['connection_level'] = $get_plan_details->connection_level;
							$plan_det['network'] = $get_plan_details->network;
							$plan_det['first'] = $get_plan_details->first;
							$plan_det['bonus'] = $get_plan_details->bonus;
							$plan_det['second'] = $get_plan_details->second;
							$plan_det['third'] = $get_plan_details->third;
							$plan_det['fourth'] = $get_plan_details->fourth;
							$plan_det['fifth'] = $get_plan_details->fifth;
							$plan_det['sixth'] = $get_plan_details->sixth;
							$plan_det['seventh'] = $get_plan_details->seventh;
							$plan_det['eighth'] = $get_plan_details->eighth;
							$plan_det['ninth'] = $get_plan_details->ninth;
							$plan_det['tenth'] = $get_plan_details->tenth;
							$plan_det['status'] = $get_plan_details->status;
							$plan_det['copy_status'] = 1;
							$plan_id = DB::table('commission')->insertGetId($plan_det);
							array_push($plan_array,$plan);
							array_push($new_plan_array,$plan_id);
						}
						
					}
					$commission_details = DB::table('commission')->where('commission_id',$plan_id)->first();
					if(count($commission_details))
					{
						$one_array = unserialize($commission_details->first);
						$bonus_array = unserialize($commission_details->bonus);
						$two_array = unserialize($commission_details->second);
						$three_array = unserialize($commission_details->third);
						$four_array = unserialize($commission_details->fourth);
						$five_array = unserialize($commission_details->fifth);
						$six_array = unserialize($commission_details->sixth);
						$seven_array = unserialize($commission_details->seventh);
						$eight_array = unserialize($commission_details->eighth);
						$nine_array = unserialize($commission_details->ninth);
						$ten_array = unserialize($commission_details->tenth);
					}
					$plannn['plan_id'] = $plan_id;
					DB::table('commission_manager')->where('uploaded_date',$comm_date)->where('sim_shop_id',$sim->sim_shop_id)->update($plannn);
					if(count($get_date_sim_networks))
					{
						foreach($get_date_sim_networks as $networks)
						{
							$data_commission['plan_id'] = $plan_id;
							$data_commission['commission_date'] = $comm_date;
							$data_commission['import_date'] = $import_date;
							$data_commission['shop_id'] = $networks->sim_shop_id;
							$data_commission['network_id'] = $networks->network_id;

							$data_commission['one_connection'] = DB::table('commission_manager')->where('uploaded_date',$comm_date)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',1)->where('ignore_sim',0)->count();
							$data_commission['two_topup'] = DB::table('commission_manager')->where('uploaded_date',$comm_date)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',2)->where('ignore_sim',0)->count();
							$data_commission['three_topup'] = DB::table('commission_manager')->where('uploaded_date',$comm_date)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',3)->where('ignore_sim',0)->count();
							$data_commission['four_topup'] = DB::table('commission_manager')->where('uploaded_date',$comm_date)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',4)->where('ignore_sim',0)->count();
							$data_commission['five_topup'] = DB::table('commission_manager')->where('uploaded_date',$comm_date)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',5)->where('ignore_sim',0)->count();
							$data_commission['six_topup'] = DB::table('commission_manager')->where('uploaded_date',$comm_date)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',6)->where('ignore_sim',0)->count();
							$data_commission['seven_topup'] = DB::table('commission_manager')->where('uploaded_date',$comm_date)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',7)->where('ignore_sim',0)->count();
							$data_commission['eight_topup'] = DB::table('commission_manager')->where('uploaded_date',$comm_date)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',8)->where('ignore_sim',0)->count();
							$data_commission['nine_topup'] = DB::table('commission_manager')->where('uploaded_date',$comm_date)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',9)->where('ignore_sim',0)->count();
							$data_commission['ten_topup'] = DB::table('commission_manager')->where('uploaded_date',$comm_date)->where('sim_shop_id',$networks->sim_shop_id)->where('network_id',$networks->network_id)->where('topup_no',10)->where('ignore_sim',0)->count();

							$data_commission['one_allotted'] = $data_commission['one_connection'];
							$data_commission['two_allotted'] = $data_commission['two_topup'];
							$data_commission['three_allotted'] = $data_commission['three_topup'];
							$data_commission['four_allotted'] = $data_commission['four_topup'];
							$data_commission['five_allotted'] = $data_commission['five_topup'];
							$data_commission['six_allotted'] = $data_commission['six_topup'];
							$data_commission['seven_allotted'] = $data_commission['seven_topup'];
							$data_commission['eight_allotted'] = $data_commission['eight_topup'];
							$data_commission['nine_allotted'] = $data_commission['nine_topup'];
							$data_commission['ten_allotted'] = $data_commission['ten_topup'];

							$one_cost = $data_commission['one_connection'] * $one_array[$networks->network_id];
							$one_bonus = $data_commission['one_connection'] * $bonus_array[$networks->network_id];

							$two_cost = $data_commission['two_topup'] * $two_array[$networks->network_id];
							$three_cost = $data_commission['three_topup'] * $three_array[$networks->network_id];
							$four_cost = $data_commission['four_topup'] * $four_array[$networks->network_id];
							$five_cost = $data_commission['five_topup'] * $five_array[$networks->network_id];
							$six_cost = $data_commission['six_topup'] * $six_array[$networks->network_id];
							$seven_cost = $data_commission['seven_topup'] * $seven_array[$networks->network_id];
							$eight_cost = $data_commission['eight_topup'] * $eight_array[$networks->network_id];
							$nine_cost = $data_commission['nine_topup'] * $nine_array[$networks->network_id];
							$ten_cost = $data_commission['ten_topup'] * $ten_array[$networks->network_id];
							$data_commission['one_cost'] = $one_cost + $one_bonus;
							$data_commission['bonus_cost'] = $one_bonus;

							$data_commission['one_allotted_cost'] = $one_cost + $one_bonus;
							$data_commission['bonus_allotted_cost'] = $one_bonus;

							$data_commission['two_cost'] = $two_cost;
							$data_commission['two_allotted_cost'] = $two_cost;

							$data_commission['three_cost'] = $three_cost;
							$data_commission['three_allotted_cost'] = $three_cost;

							$data_commission['four_cost'] = $four_cost;
							$data_commission['four_allotted_cost'] = $four_cost;

							$data_commission['five_cost'] = $five_cost;
							$data_commission['five_allotted_cost'] = $five_cost;

							$data_commission['six_cost'] = $six_cost;
							$data_commission['six_allotted_cost'] = $six_cost;

							$data_commission['seven_cost'] = $seven_cost;
							$data_commission['seven_allotted_cost'] = $seven_cost;

							$data_commission['eight_cost'] = $eight_cost;
							$data_commission['eight_allotted_cost'] = $eight_cost;

							$data_commission['nine_cost'] = $nine_cost;
							$data_commission['nine_allotted_cost'] = $nine_cost;

							$data_commission['ten_cost'] = $ten_cost;
							$data_commission['ten_allotted_cost'] = $ten_cost;

							DB::table('shop_commission')->insert($data_commission);
						}
					}
				}
			}

			DB::table('shop_commission')->where('one_connection',0)->where('two_topup',0)->where('three_topup',0)->where('four_topup',0)->where('five_topup',0)->where('six_topup',0)->where('seven_topup',0)->where('eight_topup',0)->where('nine_topup',0)->where('ten_topup',0)->where('proceeded',0)->delete();
			
			$dataarray['message'] = 'Commission Imported successfully.';
			$dataarray['duplicated'] = $duplicated;
			$dataarray['network_dint_match'] = $network_dint_match;
			$dataarray['field_empty'] = $field_empty;
			$dataarray['topup_zero'] = $topup_zero;
			$dataarray['ssn_dint_match'] = $ssn_dint_match;
			$dataarray['inserted'] = $inserted;
			$dataarray['ignored'] = $ignored;
			$dataarray['not_ignored'] = $not_ignored;

			return redirect('admin/upload_commission_page?comm_date='.$comm_date.'')->with('success_error', $dataarray);
		}
		else{
			return redirect('admin/commission?filename='.$name.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&import_type_new=1&comm_date='.$comm_date.'&import_date='.$import_date.'&duplicated='.$duplicated.'&network_dint_match='.$network_dint_match.'&field_empty='.$field_empty.'&topup_zero='.$topup_zero.'&ssn_dint_match='.$ssn_dint_match.'&inserted='.$inserted.'&ignored='.$ignored.'&not_ignored='.$not_ignored.'');
		}
	}
	public function upload_commission_page()
	{
		$date = Input::get('comm_date');
		$get_shops = DB::table('shop_commission')->where('commission_date',$date)->where('proceeded',0)->groupBy('shop_id')->get();
		return view('admin/upload_commission_page', array('title' => 'Manage Commission',  'shops' => $get_shops));
	}
	public function review_commission()
	{
		$date = Input::get('date');
		$shop_id = Input::get('shop_id');
		$get_shops = DB::table('shop_commission')->where('commission_date',$date)->where('shop_id',$shop_id)->get();
		return view('admin/review_commission', array('title' => 'Manage Commission',  'shops' => $get_shops, 'shop_id' => $shop_id, 'date_pending' => $date));
	}
	public function proceed_commission()
	{
		$date = Input::get('date');
		$shop_id = Input::get('shop_id');
		$get_shops = DB::table('shop_commission')->where('commission_date',$date)->where('shop_id',$shop_id)->get();
		return view('admin/proceed_commission', array('title' => 'Manage Commission',  'shops' => $get_shops, 'shop_id' => $shop_id, 'date_pending' => $date));
	}
	public function shop_review_commission()
	{
		$date = Input::get('date');
		$shop_id = Input::get('shop_id');
		$get_shops = DB::table('shop_commission')->where('commission_date',$date)->where('shop_id',$shop_id)->get();
		$shop_details = DB::table('shop')->where('shop_id', $shop_id)->first();
		return view('admin/shop_review_commission', array('title' => 'Manage Commission',  'shops' => $get_shops, 'shop_id' => $shop_id, 'shop_details' => $shop_details));
	}
	public function check_commission_plan()
	{
		$value = Input::get('value');
		$period = Input::get('period');
		$network = Input::get('network');
		$shop_commission = DB::table('shop_commission')->where('id',$network)->first();
		if(count($shop_commission))
		{
			$plan = $shop_commission->plan_id;
			$get_plan = DB::table('commission')->where('commission_id',$plan)->first();
			if(count($get_plan))
			{
				$one_array = unserialize($get_plan->first);
				$bonus_array = unserialize($get_plan->bonus);
				$two_array = unserialize($get_plan->second);
				$three_array = unserialize($get_plan->third);
				$four_array = unserialize($get_plan->fourth);
				$five_array = unserialize($get_plan->fifth);
				$six_array = unserialize($get_plan->sixth);
				$seven_array = unserialize($get_plan->seventh);
				$eight_array = unserialize($get_plan->eighth);
				$nine_array = unserialize($get_plan->ninth);
				$ten_array = unserialize($get_plan->tenth);

				if($period == "one" || $period == "bonus"){ 
					$one_euro = $value * $one_array[$shop_commission->network_id];
					$bonus_euro = $value * $bonus_array[$shop_commission->network_id];
					echo $one_euro.'||'.$bonus_euro;
				}
				elseif($period == "two"){ $two_euro = $value * $two_array[$shop_commission->network_id]; echo $two_euro;  }
				elseif($period == "three"){ $three_euro = $value * $three_array[$shop_commission->network_id]; echo $three_euro; }
				elseif($period == "four"){ $four_euro = $value * $four_array[$shop_commission->network_id]; echo $four_euro; }
				elseif($period == "five"){ $five_euro = $value * $five_array[$shop_commission->network_id]; echo $five_euro; }
				elseif($period == "six"){ $six_euro = $value * $six_array[$shop_commission->network_id]; echo $six_euro; }
				elseif($period == "seven"){ $seven_euro = $value * $seven_array[$shop_commission->network_id]; echo $seven_euro; }
				elseif($period == "eight"){ $eight_euro = $value * $eight_array[$shop_commission->network_id]; echo $eight_euro; }
				elseif($period == "nine"){ $nine_euro = $value * $nine_array[$shop_commission->network_id]; echo $nine_euro; }
				else { $ten_euro = $value * $ten_array[$shop_commission->network_id]; echo $ten_euro; }
			}
		}
	}
	public function update_commission_for_date()
	{
		$date = Input::get('hidden_date');
		$shop_id = Input::get('hidden_shop_id');
		$networks = Input::get('hidden_network');

		$one_allotted = Input::get('one_allotted');
		$two_allotted = Input::get('two_allotted');
		$three_allotted = Input::get('three_allotted');
		$four_allotted = Input::get('four_allotted');
		$five_allotted = Input::get('five_allotted');
		$six_allotted = Input::get('six_allotted');
		$seven_allotted = Input::get('seven_allotted');
		$eight_allotted = Input::get('eight_allotted');
		$nine_allotted = Input::get('nine_allotted');
		$ten_allotted = Input::get('ten_allotted');

		$dateval2 = date('Y-m-d', strtotime($date));

		$check_already_proceed = DB::table('sim_processed')->where('month_year',date('m-Y',strtotime($dateval2)))->where('shop_id',$shop_id)->where('proceed_count',1)->count();
		if($check_already_proceed == 0)
		{
			$dataalloted['proceed_count'] = 1;
		}

		if(count($networks))
		{
			$comm_cost_value = 0;
			foreach($networks as $key => $network)
			{
				$get_shop_network = DB::table('shop_commission')->where('shop_id',$shop_id)->where('network_id',$network)->where('commission_date', $date)->first();
				
				$get_plan_value = DB::table('commission')->where('commission_id',$get_shop_network->plan_id)->first();
				if(count($get_plan_value))
				{
					$one_array_value = unserialize($get_plan_value->first);
					$bonus_array_value = unserialize($get_plan_value->bonus);
					$two_array_value = unserialize($get_plan_value->second);
					$three_array_value = unserialize($get_plan_value->third);
					$four_array_value = unserialize($get_plan_value->fourth);
					$five_array_value = unserialize($get_plan_value->fifth);
					$six_array_value = unserialize($get_plan_value->sixth);
					$seven_array_value = unserialize($get_plan_value->seventh);
					$eight_array_value = unserialize($get_plan_value->eighth);
					$nine_array_value = unserialize($get_plan_value->ninth);
					$ten_array_value = unserialize($get_plan_value->tenth);
				}

			if($one_allotted[$key] != '0') { $comm_cost_value = $comm_cost_value + ($one_allotted[$key] * $one_array_value[$network]); }
			else{ $comm_cost_value = $comm_cost_value; }

			if($two_allotted[$key] != '0') { $comm_cost_value = $comm_cost_value + ($two_allotted[$key] * $two_array_value[$network]); }
			else{ $comm_cost_value = $comm_cost_value; }

			if($three_allotted[$key] != '0') { $comm_cost_value = $comm_cost_value + ($three_allotted[$key] * $three_array_value[$network]); }
			else{ $comm_cost_value = $comm_cost_value; }

			if($four_allotted[$key] != '0') { $comm_cost_value = $comm_cost_value + ($four_allotted[$key] * $four_array_value[$network]); }
			else{ $comm_cost_value = $comm_cost_value; }

			if($five_allotted[$key] != '0') { $comm_cost_value = $comm_cost_value + ($five_allotted[$key] * $five_array_value[$network]); }
			else{ $comm_cost_value = $comm_cost_value; }

			if($six_allotted[$key] != '0') { $comm_cost_value = $comm_cost_value + ($six_allotted[$key] * $six_array_value[$network]); }
			else{ $comm_cost_value = $comm_cost_value; }

			if($seven_allotted[$key] != '0') { $comm_cost_value = $comm_cost_value + ($seven_allotted[$key] * $seven_array_value[$network]); }
			else{ $comm_cost_value = $comm_cost_value; }

			if($eight_allotted[$key] != '0') { $comm_cost_value = $comm_cost_value + ($eight_allotted[$key] * $eight_array_value[$network]); }
			else{ $comm_cost_value = $comm_cost_value; }

			if($nine_allotted[$key] != '0') { $comm_cost_value = $comm_cost_value + ($nine_allotted[$key] * $nine_array_value[$network]); }
			else{ $comm_cost_value = $comm_cost_value; }

			if($ten_allotted[$key] != '0') { $comm_cost_value = $comm_cost_value + ($ten_allotted[$key] * $ten_array_value[$network]); }
			else{ $comm_cost_value = $comm_cost_value; }
			}
			$get_minimum_value = DB::table('admin')->first();
			$minimum_value = $get_minimum_value->minimum_value;

			if($comm_cost_value >= $minimum_value)
			{
				foreach($networks as $key => $network)
				{
					$other_conn = 0;
					$comm_cost = 0;
					$bonus_cost = 0;
					$get_shop_network = DB::table('shop_commission')->where('shop_id',$shop_id)->where('network_id',$network)->where('commission_date', $date)->first();
					
					$get_plan = DB::table('commission')->where('commission_id',$get_shop_network->plan_id)->first();
					if(count($get_plan))
					{
						$one_array = unserialize($get_plan->first);
						$bonus_array = unserialize($get_plan->bonus);
						$two_array = unserialize($get_plan->second);
						$three_array = unserialize($get_plan->third);
						$four_array = unserialize($get_plan->fourth);
						$five_array = unserialize($get_plan->fifth);
						$six_array = unserialize($get_plan->sixth);
						$seven_array = unserialize($get_plan->seventh);
						$eight_array = unserialize($get_plan->eighth);
						$nine_array = unserialize($get_plan->ninth);
						$ten_array = unserialize($get_plan->tenth);
					}

					if($one_allotted[$key] != '0')
					{
						$data['one_connection'] = $get_shop_network->one_connection - $one_allotted[$key];
						$data['one_allotted'] = $get_shop_network->one_connection - $one_allotted[$key];
	 
						$one_pending_euro = $data['one_connection'] * $one_array[$network];
						$bonus_pending_euro = $data['one_connection'] * $bonus_array[$network];
						$data['one_cost'] = $one_pending_euro + $bonus_pending_euro;
						$data['one_allotted_cost'] = $one_pending_euro + $bonus_pending_euro;

						$data['bonus_cost'] = $bonus_pending_euro;
						$data['bonus_allotted_cost'] = $bonus_pending_euro;

						$first_connection = $one_allotted[$key];
						$comm_cost = $comm_cost + ($one_allotted[$key] * $one_array[$network]);
						$bonus_cost = $bonus_cost + ($one_allotted[$key] * $bonus_array[$network]);

						$dataalloted['first_commission'] = $one_allotted[$key] * $one_array[$network];
					}
					else{
						$data['one_connection'] = $get_shop_network->one_connection;
						$data['one_allotted'] = $get_shop_network->one_allotted;
						$data['one_cost'] = $get_shop_network->one_cost;
						$data['one_allotted_cost'] = $get_shop_network->one_allotted_cost;

						$data['bonus_cost'] = $get_shop_network->bonus_cost;
						$data['bonus_allotted_cost'] = $get_shop_network->bonus_allotted_cost;

						$first_connection = 0;
						$comm_cost = $comm_cost;
						$bonus_cost = $bonus_cost;

						$dataalloted['first_commission'] = 0;
					}

					if($two_allotted[$key] != '0')
					{
						$data['two_topup'] = $get_shop_network->two_topup - $two_allotted[$key];
						$data['two_allotted'] = $get_shop_network->two_topup - $two_allotted[$key];
						$data['two_cost'] = $data['two_topup'] * $two_array[$network];
						$data['two_allotted_cost'] = $data['two_topup'] * $two_array[$network];

						$other_conn = $other_conn + $two_allotted[$key];
						$comm_cost = $comm_cost + ($two_allotted[$key] * $two_array[$network]);
					}
					else{
						$data['two_topup'] = $get_shop_network->two_topup;
						$data['two_allotted'] = $get_shop_network->two_allotted;
						$data['two_cost'] = $get_shop_network->two_cost;
						$data['two_allotted_cost'] = $get_shop_network->two_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}

					if($three_allotted[$key] != '0')
					{
						$data['three_topup'] = $get_shop_network->three_topup - $three_allotted[$key];
						$data['three_allotted'] = $get_shop_network->three_topup - $three_allotted[$key];
						$data['three_cost'] = $data['three_topup'] * $three_array[$network];
						$data['three_allotted_cost'] = $data['three_topup'] * $three_array[$network];
						$other_conn = $other_conn + $three_allotted[$key];
						$comm_cost = $comm_cost + ($three_allotted[$key] * $three_array[$network]);
					}
					else{
						$data['three_topup'] = $get_shop_network->three_topup;
						$data['three_allotted'] = $get_shop_network->three_allotted;
						$data['three_cost'] = $get_shop_network->three_cost;
						$data['three_allotted_cost'] = $get_shop_network->three_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}

					if($four_allotted[$key] != '0')
					{
						$data['four_topup'] = $get_shop_network->four_topup - $four_allotted[$key];
						$data['four_allotted'] = $get_shop_network->four_topup - $four_allotted[$key];
						$data['four_cost'] = $data['four_topup'] * $four_array[$network];
						$data['four_allotted_cost'] = $data['four_topup'] * $four_array[$network];
						$other_conn = $other_conn + $four_allotted[$key];
						$comm_cost = $comm_cost + ($four_allotted[$key] * $four_array[$network]);
					}
					else{
						$data['four_topup'] = $get_shop_network->four_topup;
						$data['four_allotted'] = $get_shop_network->four_allotted;
						$data['four_cost'] = $get_shop_network->four_cost;
						$data['four_allotted_cost'] = $get_shop_network->four_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}

					if($five_allotted[$key] != '0')
					{
						$data['five_topup'] = $get_shop_network->five_topup - $five_allotted[$key];
						$data['five_allotted'] = $get_shop_network->five_topup - $five_allotted[$key];
						$data['five_cost'] = $data['five_topup'] * $five_array[$network];
						$data['five_allotted_cost'] = $data['five_topup'] * $five_array[$network];
						$other_conn = $other_conn + $five_allotted[$key];
						$comm_cost = $comm_cost + ($five_allotted[$key] * $five_array[$network]);
					}
					else{
						$data['five_topup'] = $get_shop_network->five_topup;
						$data['five_allotted'] = $get_shop_network->five_allotted;
						$data['five_cost'] = $get_shop_network->five_cost;
						$data['five_allotted_cost'] = $get_shop_network->five_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}
					if($six_allotted[$key] != '0')
					{
						$data['six_topup'] = $get_shop_network->six_topup - $six_allotted[$key];
						$data['six_allotted'] = $get_shop_network->six_topup - $six_allotted[$key];
						$data['six_cost'] = $data['six_topup'] * $six_array[$network];
						$data['six_allotted_cost'] = $data['six_topup'] * $six_array[$network];
						$other_conn = $other_conn + $six_allotted[$key];
						$comm_cost = $comm_cost + ($six_allotted[$key] * $six_array[$network]);
					}
					else{
						$data['six_topup'] = $get_shop_network->six_topup;
						$data['six_allotted'] = $get_shop_network->six_allotted;
						$data['six_cost'] = $get_shop_network->six_cost;
						$data['six_allotted_cost'] = $get_shop_network->six_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}
					if($seven_allotted[$key] != '0')
					{
						$data['seven_topup'] = $get_shop_network->seven_topup - $seven_allotted[$key];
						$data['seven_allotted'] = $get_shop_network->seven_topup - $seven_allotted[$key];
						$data['seven_cost'] = $data['seven_topup'] * $seven_array[$network];
						$data['seven_allotted_cost'] = $data['seven_topup'] * $seven_array[$network];
						$other_conn = $other_conn + $seven_allotted[$key];
						$comm_cost = $comm_cost + ($seven_allotted[$key] * $seven_array[$network]);
					}
					else{
						$data['seven_topup'] = $get_shop_network->seven_topup;
						$data['seven_allotted'] = $get_shop_network->seven_allotted;
						$data['seven_cost'] = $get_shop_network->seven_cost;
						$data['seven_allotted_cost'] = $get_shop_network->seven_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}
					if($eight_allotted[$key] != '0')
					{
						$data['eight_topup'] = $get_shop_network->eight_topup - $eight_allotted[$key];
						$data['eight_allotted'] = $get_shop_network->eight_topup - $eight_allotted[$key];
						$data['eight_cost'] = $data['eight_topup'] * $eight_array[$network];
						$data['eight_allotted_cost'] = $data['eight_topup'] * $eight_array[$network];
						$other_conn = $other_conn + $eight_allotted[$key];
						$comm_cost = $comm_cost + ($eight_allotted[$key] * $eight_array[$network]);
					}
					else{
						$data['eight_topup'] = $get_shop_network->eight_topup;
						$data['eight_allotted'] = $get_shop_network->eight_allotted;
						$data['eight_cost'] = $get_shop_network->eight_cost;
						$data['eight_allotted_cost'] = $get_shop_network->eight_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}
					if($nine_allotted[$key] != '0')
					{
						$data['nine_topup'] = $get_shop_network->nine_topup - $nine_allotted[$key];
						$data['nine_allotted'] = $get_shop_network->nine_topup - $nine_allotted[$key];
						$data['nine_cost'] = $data['nine_topup'] * $nine_array[$network];
						$data['nine_allotted_cost'] = $data['nine_topup'] * $nine_array[$network];
						$other_conn = $other_conn + $nine_allotted[$key];
						$comm_cost = $comm_cost + ($nine_allotted[$key] * $nine_array[$network]);
					}
					else{
						$data['nine_topup'] = $get_shop_network->nine_topup;
						$data['nine_allotted'] = $get_shop_network->nine_allotted;
						$data['nine_cost'] = $get_shop_network->nine_cost;
						$data['nine_allotted_cost'] = $get_shop_network->nine_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}
					if($ten_allotted[$key] != '0')
					{
						$data['ten_topup'] = $get_shop_network->ten_topup - $ten_allotted[$key];
						$data['ten_allotted'] = $get_shop_network->ten_topup - $ten_allotted[$key];
						$data['ten_cost'] = $data['ten_topup'] * $ten_array[$network];
						$data['ten_allotted_cost'] = $data['ten_topup'] * $ten_array[$network];
						$other_conn = $other_conn + $ten_allotted[$key];
						$comm_cost = $comm_cost + ($ten_allotted[$key] * $ten_array[$network]);
					}
					else{
						$data['ten_topup'] = $get_shop_network->ten_topup;
						$data['ten_allotted'] = $get_shop_network->ten_allotted;
						$data['ten_cost'] = $get_shop_network->ten_cost;
						$data['ten_allotted_cost'] = $get_shop_network->ten_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}

					if($data['one_connection'] <= 0 && $data['two_topup'] <= 0 && $data['three_topup'] <= 0 && $data['four_topup'] <= 0 && $data['five_topup'] <= 0 && $data['six_topup'] <= 0 && $data['seven_topup'] <= 0 && $data['eight_topup'] <= 0 && $data['nine_topup'] <= 0 && $data['ten_topup'] <= 0)
					{
						$data['status'] = 1;
					}
					else{
						$data['status'] = 0;
					}
					$data['proceeded'] = 1;
					DB::table('shop_commission')->where('shop_id',$shop_id)->where('network_id',$network)->where('commission_date', $date)->update($data);


					$dateval = date('Y-m-d', strtotime($date));

					$dataalloted['date'] = $dateval;
					$dataalloted['import_date'] = $get_shop_network->import_date;
					$dataalloted['uploaded_date'] = $get_shop_network->commission_date;
					$dataalloted['month_year'] = date('m-Y',strtotime($dateval));
					$dataalloted['shop_id'] = $shop_id;
					$dataalloted['network_id'] = $network;
					$dataalloted['first_connection'] = $first_connection;
					$dataalloted['topups'] = $other_conn;
					$dataalloted['commission'] = $comm_cost;
					$dataalloted['bonus'] = $bonus_cost;

					$dataalloted['topups_commission'] = $comm_cost - $dataalloted['first_commission'];
					$dataalloted['first_rate'] = $one_array[$network];
					$dataalloted['plan_id'] = $get_shop_network->plan_id;

					DB::table('sim_processed')->insert($dataalloted);
				}
				return redirect('admin/upload_commission_page?comm_date='.$date.'')->with('message', 'Proceed successfully.');
			}
			else{
				return redirect('admin/upload_commission_page?comm_date='.$date.'')->with('message', 'you cant able to Proceed this commission. Because the you should reach the minimum value to Proceed');
			}
		}
	}
	public function update_commission_for_date_shop()
	{
		$loop_count = Input::get('loop_count');
		$shopidval = Input::get('hidden_shop_id_1');
		for($i=$loop_count;$i>=1;$i--)
		{
			$date = Input::get('hidden_date_'.$i.'');
			$changed_date = Input::get('hidden_changed_date_'.$i.'');
			$shop_id = Input::get('hidden_shop_id_'.$i.'');
			$networks = Input::get('hidden_network_'.$i.'');

			$one_allotted = Input::get('one_allotted_'.$i.'');
			$two_allotted = Input::get('two_allotted_'.$i.'');
			$three_allotted = Input::get('three_allotted_'.$i.'');
			$four_allotted = Input::get('four_allotted_'.$i.'');
			$five_allotted = Input::get('five_allotted_'.$i.'');
			$six_allotted = Input::get('six_allotted_'.$i.'');
			$seven_allotted = Input::get('seven_allotted_'.$i.'');
			$eight_allotted = Input::get('eight_allotted_'.$i.'');
			$nine_allotted = Input::get('nine_allotted_'.$i.'');
			$ten_allotted = Input::get('ten_allotted_'.$i.'');

			$check_already_proceed = DB::table('sim_processed')->where('month_year',date('m-Y',strtotime($changed_date)))->where('shop_id',$shop_id)->where('proceed_count',1)->count();
			if($check_already_proceed == 0)
			{
				$dataalloted['proceed_count'] = 1;
			}
			else{
				$dataalloted['proceed_count'] = 0;
			}

			if(count($networks))
			{
				foreach($networks as $key => $network)
				{
					$other_conn = 0;
					$comm_cost = 0;
					$bonus_cost = 0;
					$get_shop_network = DB::table('shop_commission')->where('shop_id',$shop_id)->where('network_id',$network)->where('commission_date', $date)->first();
					
					$get_plan = DB::table('commission')->where('commission_id',$get_shop_network->plan_id)->first();
					if(count($get_plan))
					{
						$one_array = unserialize($get_plan->first);
						$bonus_array = unserialize($get_plan->bonus);
						$two_array = unserialize($get_plan->second);
						$three_array = unserialize($get_plan->third);
						$four_array = unserialize($get_plan->fourth);
						$five_array = unserialize($get_plan->fifth);
						$six_array = unserialize($get_plan->sixth);
						$seven_array = unserialize($get_plan->seventh);
						$eight_array = unserialize($get_plan->eighth);
						$nine_array = unserialize($get_plan->ninth);
						$ten_array = unserialize($get_plan->tenth);
					}

					if($one_allotted[$key] != '0')
					{
						$data['one_connection'] = $get_shop_network->one_connection - $one_allotted[$key];
						$data['one_allotted'] = $get_shop_network->one_connection - $one_allotted[$key];
	 
						$one_pending_euro = $data['one_connection'] * $one_array[$network];
						$bonus_pending_euro = $data['one_connection'] * $bonus_array[$network];
						$data['one_cost'] = $one_pending_euro + $bonus_pending_euro;
						$data['one_allotted_cost'] = $one_pending_euro + $bonus_pending_euro;

						$data['bonus_cost'] = $bonus_pending_euro;
						$data['bonus_allotted_cost'] = $bonus_pending_euro;

						$first_connection = $one_allotted[$key];
						$comm_cost = $comm_cost + ($one_allotted[$key] * $one_array[$network]);
						$bonus_cost = $bonus_cost + ($one_allotted[$key] * $bonus_array[$network]);

						$dataalloted['first_commission'] = $one_allotted[$key] * $one_array[$network];
					}
					else{
						$data['one_connection'] = $get_shop_network->one_connection;
						$data['one_allotted'] = $get_shop_network->one_allotted;
						$data['one_cost'] = $get_shop_network->one_cost;
						$data['one_allotted_cost'] = $get_shop_network->one_allotted_cost;

						$data['bonus_cost'] = $get_shop_network->bonus_cost;
						$data['bonus_allotted_cost'] = $get_shop_network->bonus_allotted_cost;

						$first_connection = 0;
						$comm_cost = $comm_cost;
						$bonus_cost = $bonus_cost;

						$dataalloted['first_commission'] = 0;
					}

					if($two_allotted[$key] != '0')
					{
						$data['two_topup'] = $get_shop_network->two_topup - $two_allotted[$key];
						$data['two_allotted'] = $get_shop_network->two_topup - $two_allotted[$key];
						$data['two_cost'] = $data['two_topup'] * $two_array[$network];
						$data['two_allotted_cost'] = $data['two_topup'] * $two_array[$network];

						$other_conn = $other_conn + $two_allotted[$key];
						$comm_cost = $comm_cost + ($two_allotted[$key] * $two_array[$network]);
					}
					else{
						$data['two_topup'] = $get_shop_network->two_topup;
						$data['two_allotted'] = $get_shop_network->two_allotted;
						$data['two_cost'] = $get_shop_network->two_cost;
						$data['two_allotted_cost'] = $get_shop_network->two_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}

					if($three_allotted[$key] != '0')
					{
						$data['three_topup'] = $get_shop_network->three_topup - $three_allotted[$key];
						$data['three_allotted'] = $get_shop_network->three_topup - $three_allotted[$key];
						$data['three_cost'] = $data['three_topup'] * $three_array[$network];
						$data['three_allotted_cost'] = $data['three_topup'] * $three_array[$network];
						$other_conn = $other_conn + $three_allotted[$key];
						$comm_cost = $comm_cost + ($three_allotted[$key] * $three_array[$network]);
					}
					else{
						$data['three_topup'] = $get_shop_network->three_topup;
						$data['three_allotted'] = $get_shop_network->three_allotted;
						$data['three_cost'] = $get_shop_network->three_cost;
						$data['three_allotted_cost'] = $get_shop_network->three_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}

					if($four_allotted[$key] != '0')
					{
						$data['four_topup'] = $get_shop_network->four_topup - $four_allotted[$key];
						$data['four_allotted'] = $get_shop_network->four_topup - $four_allotted[$key];
						$data['four_cost'] = $data['four_topup'] * $four_array[$network];
						$data['four_allotted_cost'] = $data['four_topup'] * $four_array[$network];
						$other_conn = $other_conn + $four_allotted[$key];
						$comm_cost = $comm_cost + ($four_allotted[$key] * $four_array[$network]);
					}
					else{
						$data['four_topup'] = $get_shop_network->four_topup;
						$data['four_allotted'] = $get_shop_network->four_allotted;
						$data['four_cost'] = $get_shop_network->four_cost;
						$data['four_allotted_cost'] = $get_shop_network->four_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}

					if($five_allotted[$key] != '0')
					{
						$data['five_topup'] = $get_shop_network->five_topup - $five_allotted[$key];
						$data['five_allotted'] = $get_shop_network->five_topup - $five_allotted[$key];
						$data['five_cost'] = $data['five_topup'] * $five_array[$network];
						$data['five_allotted_cost'] = $data['five_topup'] * $five_array[$network];
						$other_conn = $other_conn + $five_allotted[$key];
						$comm_cost = $comm_cost + ($five_allotted[$key] * $five_array[$network]);
					}
					else{
						$data['five_topup'] = $get_shop_network->five_topup;
						$data['five_allotted'] = $get_shop_network->five_allotted;
						$data['five_cost'] = $get_shop_network->five_cost;
						$data['five_allotted_cost'] = $get_shop_network->five_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}
					if($six_allotted[$key] != '0')
					{
						$data['six_topup'] = $get_shop_network->six_topup - $six_allotted[$key];
						$data['six_allotted'] = $get_shop_network->six_topup - $six_allotted[$key];
						$data['six_cost'] = $data['six_topup'] * $six_array[$network];
						$data['six_allotted_cost'] = $data['six_topup'] * $six_array[$network];
						$other_conn = $other_conn + $six_allotted[$key];
						$comm_cost = $comm_cost + ($six_allotted[$key] * $six_array[$network]);
					}
					else{
						$data['six_topup'] = $get_shop_network->six_topup;
						$data['six_allotted'] = $get_shop_network->six_allotted;
						$data['six_cost'] = $get_shop_network->six_cost;
						$data['six_allotted_cost'] = $get_shop_network->six_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}
					if($seven_allotted[$key] != '0')
					{
						$data['seven_topup'] = $get_shop_network->seven_topup - $seven_allotted[$key];
						$data['seven_allotted'] = $get_shop_network->seven_topup - $seven_allotted[$key];
						$data['seven_cost'] = $data['seven_topup'] * $seven_array[$network];
						$data['seven_allotted_cost'] = $data['seven_topup'] * $seven_array[$network];
						$other_conn = $other_conn + $seven_allotted[$key];
						$comm_cost = $comm_cost + ($seven_allotted[$key] * $seven_array[$network]);
					}
					else{
						$data['seven_topup'] = $get_shop_network->seven_topup;
						$data['seven_allotted'] = $get_shop_network->seven_allotted;
						$data['seven_cost'] = $get_shop_network->seven_cost;
						$data['seven_allotted_cost'] = $get_shop_network->seven_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}
					if($eight_allotted[$key] != '0')
					{
						$data['eight_topup'] = $get_shop_network->eight_topup - $eight_allotted[$key];
						$data['eight_allotted'] = $get_shop_network->eight_topup - $eight_allotted[$key];
						$data['eight_cost'] = $data['eight_topup'] * $eight_array[$network];
						$data['eight_allotted_cost'] = $data['eight_topup'] * $eight_array[$network];
						$other_conn = $other_conn + $eight_allotted[$key];
						$comm_cost = $comm_cost + ($eight_allotted[$key] * $eight_array[$network]);
					}
					else{
						$data['eight_topup'] = $get_shop_network->eight_topup;
						$data['eight_allotted'] = $get_shop_network->eight_allotted;
						$data['eight_cost'] = $get_shop_network->eight_cost;
						$data['eight_allotted_cost'] = $get_shop_network->eight_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}
					if($nine_allotted[$key] != '0')
					{
						$data['nine_topup'] = $get_shop_network->nine_topup - $nine_allotted[$key];
						$data['nine_allotted'] = $get_shop_network->nine_topup - $nine_allotted[$key];
						$data['nine_cost'] = $data['nine_topup'] * $nine_array[$network];
						$data['nine_allotted_cost'] = $data['nine_topup'] * $nine_array[$network];
						$other_conn = $other_conn + $nine_allotted[$key];
						$comm_cost = $comm_cost + ($nine_allotted[$key] * $nine_array[$network]);
					}
					else{
						$data['nine_topup'] = $get_shop_network->nine_topup;
						$data['nine_allotted'] = $get_shop_network->nine_allotted;
						$data['nine_cost'] = $get_shop_network->nine_cost;
						$data['nine_allotted_cost'] = $get_shop_network->nine_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}
					if($ten_allotted[$key] != '0')
					{
						$data['ten_topup'] = $get_shop_network->ten_topup - $ten_allotted[$key];
						$data['ten_allotted'] = $get_shop_network->ten_topup - $ten_allotted[$key];
						$data['ten_cost'] = $data['ten_topup'] * $ten_array[$network];
						$data['ten_allotted_cost'] = $data['ten_topup'] * $ten_array[$network];
						$other_conn = $other_conn + $ten_allotted[$key];
						$comm_cost = $comm_cost + ($ten_allotted[$key] * $ten_array[$network]);
					}
					else{
						$data['ten_topup'] = $get_shop_network->ten_topup;
						$data['ten_allotted'] = $get_shop_network->ten_allotted;
						$data['ten_cost'] = $get_shop_network->ten_cost;
						$data['ten_allotted_cost'] = $get_shop_network->ten_allotted_cost;
						$other_conn = $other_conn + 0;
						$comm_cost = $comm_cost + 0;
					}

					if($data['one_connection'] <= 0 && $data['two_topup'] <= 0 && $data['three_topup'] <= 0 && $data['four_topup'] <= 0 && $data['five_topup'] <= 0 && $data['six_topup'] <= 0 && $data['seven_topup'] <= 0 && $data['eight_topup'] <= 0 && $data['nine_topup'] <= 0 && $data['ten_topup'] <= 0)
					{
						$data['status'] = 1;
					}
					else{
						$data['status'] = 0;
					}

					$data['proceeded'] = 1;
					DB::table('shop_commission')->where('shop_id',$shop_id)->where('network_id',$network)->where('commission_date', $date)->update($data);

					$dataalloted['date'] = $changed_date;
					$dataalloted['import_date'] = $get_shop_network->import_date;
					$dataalloted['uploaded_date'] = $get_shop_network->commission_date;
					$dataalloted['month_year'] = date('m-Y',strtotime($changed_date));
					$dataalloted['shop_id'] = $shop_id;
					$dataalloted['network_id'] = $network;
					$dataalloted['first_connection'] = $first_connection;
					$dataalloted['topups'] = $other_conn;
					$dataalloted['commission'] = $comm_cost;
					$dataalloted['bonus'] = $bonus_cost;

					$dataalloted['topups_commission'] = $comm_cost - $dataalloted['first_commission'];
					$dataalloted['first_rate'] = $one_array[$network];
					$dataalloted['plan_id'] = $get_shop_network->plan_id;

					DB::table('sim_processed')->insert($dataalloted);
				}
			}
		}
		return redirect('admin/shop_review_commission?shop_id='.$shopidval.'')->with('message', 'Proceed successfully.');
	}
	public function print_pdf_for_shop()
	{
		$shop_id = Input::get('shop_id');
		$value = Input::get('dateval');
		
		$explode = explode('-',$value);
		if($explode[0] == "1") { $month = 'January'; }
		elseif($explode[0] == "2") { $month = 'February'; }
		elseif($explode[0] == "3") { $month = 'March'; }
		elseif($explode[0] == "4") { $month = 'April'; }
		elseif($explode[0] == "5") { $month = 'May'; }
		elseif($explode[0] == "6") { $month = 'June'; }
		elseif($explode[0] == "7") { $month = 'July'; }
		elseif($explode[0] == "8") { $month = 'August'; }
		elseif($explode[0] == "9") { $month = 'September'; }
		elseif($explode[0] == "10") { $month = 'October'; }
		elseif($explode[0] == "11") { $month = 'November'; }
		elseif($explode[0] == "12") { $month = 'December'; }

		$shop_details = DB::table('shop')->where('shop_id',$shop_id)->first();
		$address = '';
		if($shop_details->address1 != "") { $address.= $shop_details->address1.'<br/>'; }
		if($shop_details->address2 != "") { $address.= $shop_details->address2.'<br/>'; }
		if($shop_details->address3 != "") { $address.= $shop_details->address3.'<br/>'; }
		if($shop_details->city != "") { $address.= $shop_details->city.'<br/>'; }
		if($shop_details->postcode != "") { $address.= $shop_details->postcode; }

		$font_family = "font-family: font-family: 'Roboto', sans-serif;";
		$output = '<div style="width:100%;float:left;'.$font_family.'">
			<table style="width:100%;border-collapse:collapse;border:0px solid;'.$font_family.'">
				<tr>
					<td style="'.$font_family.'"><img src="'.URL::to('assets/images/comco_logo_red.png').'"></td>
					<td style="text-align:right;'.$font_family.'"><strong style="font-weight:800">Comco Group UK Ltd</strong><br/>
						4th Floor 18 St. Cross Street London EC1N 8UN<br/>
						T: +44 (0) 20 3322 5259 E:sales@comco-retail.co.uk
					</td>
				</tr>
				<tr>
					<td colspan="2" style="height:30px;'.$font_family.'">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;font-size:20px;font-weight:800;'.$font_family.'">Commission Statement for '.$month.' '.$explode[1].'</td>
				</tr>
				<tr>
					<td colspan="2" style="height:30px;'.$font_family.'">&nbsp;</td>
				</tr>
				<tr>
					<td style="'.$font_family.'">
					<strong style="font-weight:800">CC-'.$shop_details->shop_id.' : '.$shop_details->shop_name.'</strong><br/>
					'.$address.'
					</td>
					<td style="text-align:right;vertical-align:top;'.$font_family.'">
						
					</td>
				</tr>
			</table>

			<table style="width:100%;border-collapse:collapse;margin-top:20px;'.$font_family.'">
				<tr>
					<td rowspan="2" style="font-weight:600;padding:7px;border:1px solid #000; text-align:center;'.$font_family.'">Network</td>
					<td colspan="3" style="font-weight:600;padding:7px;border:1px solid #000; text-align:center;'.$font_family.'">Connections</td>
					<td colspan="2" style="font-weight:600;padding:7px;border:1px solid #000; text-align:center;'.$font_family.'">Topups</td>
					<td rowspan="2" style="font-weight:600;padding:7px;border:1px solid #000; text-align:center;'.$font_family.'">Grand<br/>Total(£)</td>
				</tr>
				<tr>
					<td style="font-weight:600;padding:7px;border:1px solid #000; text-align:center;'.$font_family.'">1st</td>
					<td style="font-weight:600;padding:7px;border:1px solid #000; text-align:center;'.$font_family.'">Rate (£)</td>
					<td style="font-weight:600;padding:7px;border:1px solid #000; text-align:center;'.$font_family.'">Total (£)</td>
					<td style="font-weight:600;padding:7px;border:1px solid #000; text-align:center;'.$font_family.'">Total (2-10)</td>
					<td style="font-weight:600;padding:7px;border:1px solid #000; text-align:center;'.$font_family.'">Total (£)</td>
				</tr>';
				$networks = DB::table('network')->orderBy('network_name','asc')->get();
				$sub_first_count = 0;
				$sub_first_commission_total = 0;
				$sub_topups_count = 0;
				$sub_topups_commission_total = 0;
				$subtotalvalue = 0;
				$sub_carry_count = 0;
				$sub_carry_cost = 0;
				if(count($networks))
				{
					foreach($networks as $network)
					{
						$first_count = DB::table('sim_processed')->where('shop_id',$shop_id)->where('month_year',$value)->where('network_id',$network->network_name)->where('proceed_count','1')->sum('first_connection');
						$carry_forward_first = DB::table('sim_processed')->where('shop_id',$shop_id)->where('month_year',$value)->where('network_id',$network->network_name)->where('proceed_count','!=','1')->sum('first_connection');
						$topups_count = DB::table('sim_processed')->where('shop_id',$shop_id)->where('month_year',$value)->where('network_id',$network->network_name)->where('proceed_count','1')->sum('topups');
						$carry_forward_topups = DB::table('sim_processed')->where('shop_id',$shop_id)->where('month_year',$value)->where('network_id',$network->network_name)->where('proceed_count','!=','1')->sum('topups');
						$first_commission_total = DB::table('sim_processed')->where('shop_id',$shop_id)->where('month_year',$value)->where('network_id',$network->network_name)->where('proceed_count','1')->sum('first_commission');
						$carry_forward_first_cost = DB::table('sim_processed')->where('shop_id',$shop_id)->where('month_year',$value)->where('network_id',$network->network_name)->where('proceed_count','!=','1')->sum('first_commission');

						$topups_commission_total = DB::table('sim_processed')->where('shop_id',$shop_id)->where('month_year',$value)->where('network_id',$network->network_name)->where('proceed_count','1')->sum('topups_commission');
						$carry_forward_topup_cost = DB::table('sim_processed')->where('shop_id',$shop_id)->where('month_year',$value)->where('network_id',$network->network_name)->where('proceed_count','!=','1')->sum('topups_commission');
						$first_count_query = DB::table('sim_processed')->where('shop_id',$shop_id)->where('month_year',$value)->where('network_id',$network->network_name)->where('proceed_count','1')->first();
						if(count($first_count_query))
						{
							$first_rate = $first_count_query->first_rate;

						}
						else{
							$get_plan_id = DB::table('sim_processed')->where('shop_id',$shop_id)->where('month_year',$value)->where('proceed_count','1')->first();
							if(count($get_plan_id))
							{
								$plan_id = $get_plan_id->plan_id;
							
								$commission_details = DB::table('commission')->where('commission_id',$plan_id)->first();
								if(count($commission_details))
								{
									$one_array = unserialize($commission_details->first);
								}
								if(in_array($network->network_name,$one_array))
								{
									$first_rate = $one_array[$network->network_name];
								}
								else{
									$first_rate = 0;
								}
							}
							else{
								$first_rate = 0;
							}
						}

						$totalvalue = $first_commission_total + $topups_commission_total;

						$sub_first_count = $sub_first_count + $first_count;
						$sub_first_commission_total = $sub_first_commission_total + $first_commission_total;
						$sub_topups_count = $sub_topups_count + $topups_count;
						$sub_topups_commission_total = $sub_topups_commission_total + $topups_commission_total;
						$subtotalvalue = $subtotalvalue + $totalvalue;

						$carry_forward_count = $carry_forward_first + $carry_forward_topups;
						$sub_carry_count = $sub_carry_count + $carry_forward_count;

						$carry_forward_cost = $carry_forward_first_cost + $carry_forward_topup_cost;
						$sub_carry_cost = $sub_carry_cost + $carry_forward_cost;

						$output.='<tr>
							<td style="padding:6px;border:1px solid #000; text-align:center;'.$font_family.'">'.$network->name.'</td>
							<td style="padding:6px;border:1px solid #000; text-align:center;'.$font_family.'">'.$first_count.'</td>
							<td style="padding:6px;border:1px solid #000; text-align:center;'.$font_family.'">£ '.$first_rate.'</td>
							<td style="padding:6px;border:1px solid #000; text-align:center;'.$font_family.'">£ '.$first_commission_total.'</td>
							<td style="padding:6px;border:1px solid #000; text-align:center;'.$font_family.'">'.$topups_count.'</td>
							<td style="padding:6px;border:1px solid #000; text-align:center;'.$font_family.'">£ '.$topups_commission_total.'</td>
							<td style="padding:6px;border:1px solid #000; text-align:center;'.$font_family.'">£ '.$totalvalue.'</td>
						</tr>';
					}

					$output.='<tr>
							<td style="padding:6px;border:1px solid #000; text-align:center;'.$font_family.'">Sub Total</td>
							<td style="padding:6px;border:1px solid #000; text-align:center;'.$font_family.'">'.$sub_first_count.'</td>
							<td style="padding:6px;border:1px solid #000; text-align:center;'.$font_family.'"></td>
							<td style="padding:6px;border:1px solid #000; text-align:center;'.$font_family.'">£ '.$sub_first_commission_total.'</td>
							<td style="padding:6px;border:1px solid #000; text-align:center;'.$font_family.'">'.$sub_topups_count.'</td>
							<td style="padding:6px;border:1px solid #000; text-align:center;'.$font_family.'">£ '.$sub_topups_commission_total.'</td>
							<td style="padding:6px;border:1px solid #000; text-align:center;'.$font_family.'">£ '.$subtotalvalue.'</td>
						</tr>
						<tr>
							<td colspan="7" style="font-weight:600;padding:6px;border:1px solid #000; text-align:right;'.$font_family.'">&nbsp;</td>
						</tr>';
						if($sub_carry_cost != 0){
							$output.='<tr>
								<td colspan="6" style="font-weight:600;padding:6px;border:1px solid #000; text-align:right;'.$font_family.'">Carry Forward</td>
								<td style="padding:6px;border:1px solid #000; text-align:center;'.$font_family.'">£ '.$sub_carry_cost.'</td>
							</tr>';
						}
						$subtotalvalue = $subtotalvalue + $sub_carry_cost;
						$output.='<tr>
							<td colspan="5" style="font-weight:600;padding:6px;border:1px solid #000; text-align:center;'.$font_family.'"></td>
							<td style="padding:6px;border:1px solid #000; text-align:center;'.$font_family.'">Total</td>
							<td style="padding:6px;border:1px solid #000; text-align:center;'.$font_family.'">£ '.$subtotalvalue.'</td>
						</tr>';
				}
			$output.= '</table>

			<table style="width:100%;border-collapse:collapse;border:0px solid;margin-top:20px;'.$font_family.'">
				<tr>
					<td style="text-align:right;color:#000;font-weight:100;'.$font_family.'">
					<span style="font-size:13px;">All commissions are inculsive of VAT</span><br/>
					Registered address as above. Registered in England & Wales No. 12317304
					</td>
				</tr>
			</table>
		</div>';

		$pdf = PDF::loadHTML($output);
	    $pdf->setPaper('A4', 'portrait');
	    $pdf->save('papers/Commission Statement for '.$month.' '.$explode[1].'.pdf');
	    echo 'Commission Statement for '.$month.' '.$explode[1].'.pdf';
	}
	public function delete_commission_page()
	{
		$comm_date = Input::get('comm_date');
		DB::table('commission_manager')->where('uploaded_date',$comm_date)->delete();
		DB::table('shop_commission')->where('commission_date',$comm_date)->delete();
		DB::table('sim_processed')->where('uploaded_date',$comm_date)->delete();
		return redirect::back()->with('message', 'Commission for date '.date('d-M-Y H:i:s',strtotime($comm_date)).' have been deleted successfully.');
	}
	public function shops_commission_completed()
	{
		$comm_date = Input::get('comm_date');
		$shop_completed = DB::table('shop_commission')->where('proceeded',1)->where('commission_date',$comm_date)->groupBy('shop_id')->get();
		$array_shops = array();
		if(count($shop_completed))
		{
			foreach($shop_completed as $complete)
			{
				array_push($array_shops,$complete->shop_id);
			}
		}
		if(count($array_shops))
		{
			$shops = DB::table('shop')->whereIn('shop_id',$array_shops)->get();
		}
		else{
			$shops = array();
		}

		return view('admin/shop_commission_completed', array('title' => 'Shop Commission', 'shop_completed' => $shop_completed, 'shoplist' => $shops, 'comm_date' => $comm_date));
	}
	public function commissionsettings()
	{
		$admin = DB::table('admin')->first();
		return view('admin/commission_setting', array('title' => 'Setting', 'admin' => $admin));
	}
}
	
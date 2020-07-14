<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
use URL;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use DateTime;
use ZipArchive;
class SimcardController extends Controller {

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
	
	public function simcards()
	{
		$simlist = DB::table('sim')->groupBy('import_date')->get();		
		return view('admin/simcards', array('title' => 'Manage Simcards', 'simlist' => $simlist));
	}
	public function import_sim()
	{
		if($_FILES['import_file']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';

			$create_upload_dir = 'uploads/importfiles';
			if(!file_exists($create_upload_dir))
			{
				mkdir($create_upload_dir);
			}

			$tmp_name = $_FILES['import_file']['tmp_name'];
			$name=$_FILES['import_file']['name'];

			$duplicated = 0;
			$network_dint_match = 0;
			$productid_dint_match = 0;
			$field_empty = 0;

			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){
				$filepath = $uploads_dir.'/'.$name;
				$objPHPExcel = PHPExcel_IOFactory::load($filepath);
				foreach ($objPHPExcel->getWorksheetIterator() as $keysheet => $worksheet) {
					if($keysheet == 0)
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

						$ssn_title = $worksheet->getCellByColumnAndRow(0, 1); $ssn_title = trim($ssn_title->getValue());
						$cli_title = $worksheet->getCellByColumnAndRow(1, 1); $cli_title = trim($cli_title->getValue());
						$network_id_title = $worksheet->getCellByColumnAndRow(2, 1); $network_id_title = trim($network_id_title->getValue());
						$product_id_title = $worksheet->getCellByColumnAndRow(3, 1); $product_id_title = trim($product_id_title->getValue());
						$allocated_title = $worksheet->getCellByColumnAndRow(4, 1); $allocated_title = trim($allocated_title->getValue());



						if($ssn_title == "ssn" && $cli_title == "cli" && $network_id_title == "network_id" && $product_id_title == "product_id" && $allocated_title == "allocated")
						{
							
							

							for ($row = 2; $row <= $height; ++ $row) {
								$ssn = $worksheet->getCellByColumnAndRow(0, $row); $ssn = trim($ssn->getValue());
								$cli = $worksheet->getCellByColumnAndRow(1, $row); $cli = trim($cli->getValue());
								$network_id = $worksheet->getCellByColumnAndRow(2, $row); $network_id = trim($network_id->getValue());
								$product_id = $worksheet->getCellByColumnAndRow(3, $row); $product_id = trim($product_id->getValue());
								$allocated = $worksheet->getCellByColumnAndRow(4, $row); $allocated = trim($allocated->getValue());

								$unix_date = ($allocated - 25569) * 86400;
								$excel_date = 25569 + ($unix_date / 86400);
								$unix_date = ($excel_date - 25569) * 86400;
								$allocated_date = gmdate("Y-m-d", $unix_date);

								if($ssn == "" || $network_id == "" || $product_id == "" || $allocated == "")
								{
									$field_empty++;
								}
								else{
									$check_ssn = DB::table('sim')->where('ssn',$ssn)->first();
									if(count($check_ssn))
									{
										$duplicated++;
									}
									else{
										$get_network_details = DB::table('network')->where('network_name',$network_id)->first();
										if(count($get_network_details))
										{
											$get_products = explode(",",$get_network_details->product_id);
											if(in_array($product_id, $get_products))
											{
													$datasim['ssn'] = $ssn;
													$datasim['cli'] = $cli;
													$datasim['network_id'] = $network_id;
													$datasim['product_id'] = $product_id;
													$datasim['allocated'] = $allocated_date;
													$datasim['import_date'] = date('Y-m-d');

													DB::table('sim')->insert($datasim);
											}
											else{
												$productid_dint_match++;
											}
										}
										else{
											$network_dint_match++;
										}
									}
								}
							}
						}
						else{
							return redirect('admin/simcards')->with('message', 'Import Failed! Invalid File');
						}
					}
				}
				if($height >= $highestRow)
				{
					if($duplicated == 0 && $network_dint_match == 0 && $productid_dint_match == 0 && $field_empty == 0)
					{
						return redirect('admin/simcards')->with('message', 'SimCards Imported successfully.');
					}
					else{
						$dataarray['duplicated'] = $duplicated;
						$dataarray['network_dint_match'] = $network_dint_match;
						$dataarray['productid_dint_match'] = $productid_dint_match;
						$dataarray['field_empty'] = $field_empty;
						$dataarray['activated'] = 0;

						return redirect('admin/simcards')->with('success_error', $dataarray);
					}
				}
				else{
					return redirect('admin/simcards?filename='.$name.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type_new=1&duplicated='.$duplicated.'&network_dint_match='.$network_dint_match.'&productid_dint_match='.$productid_dint_match.'&field_empty='.$field_empty.'');
				}
			}
		}
	}
	public function import_sim_one()
	{
		$name = Input::get('filename');
		$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
		$filepath = $uploads_dir.'/'.$name;
		$objPHPExcel = PHPExcel_IOFactory::load($filepath);
		
		$duplicated = $_GET['duplicated'];
		$network_dint_match = $_GET['network_dint_match'];
		$productid_dint_match = $_GET['productid_dint_match'];
		$field_empty = $_GET['field_empty'];

		foreach ($objPHPExcel->getWorksheetIterator() as $keysheet => $worksheet) {
			if($keysheet == 0)
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

				$ssn_title = $worksheet->getCellByColumnAndRow(0, 1); $ssn_title = trim($ssn_title->getValue());
				$cli_title = $worksheet->getCellByColumnAndRow(1, 1); $cli_title = trim($cli_title->getValue());
				$network_id_title = $worksheet->getCellByColumnAndRow(2, 1); $network_id_title = trim($network_id_title->getValue());
				$product_id_title = $worksheet->getCellByColumnAndRow(3, 1); $product_id_title = trim($product_id_title->getValue());
				$allocated_title = $worksheet->getCellByColumnAndRow(4, 1); $allocated_title = trim($allocated_title->getValue());

				
				for ($row = $offsetcount; $row <= $height; ++ $row) {
					$ssn = $worksheet->getCellByColumnAndRow(0, $row); $ssn = trim($ssn->getValue());
					$cli = $worksheet->getCellByColumnAndRow(1, $row); $cli = trim($cli->getValue());
					$network_id = $worksheet->getCellByColumnAndRow(2, $row); $network_id = trim($network_id->getValue());
					$product_id = $worksheet->getCellByColumnAndRow(3, $row); $product_id = trim($product_id->getValue());
					$allocated = $worksheet->getCellByColumnAndRow(4, $row); $allocated = trim($allocated->getValue());

					$unix_date = ($allocated - 25569) * 86400;
					$excel_date = 25569 + ($unix_date / 86400);
					$unix_date = ($excel_date - 25569) * 86400;
					$allocated_date = gmdate("Y-m-d", $unix_date);

					if($ssn == "" || $network_id == "" || $product_id == "" || $allocated == "")
					{
						$field_empty++;
					}
					else{
						$check_ssn = DB::table('sim')->where('ssn',$ssn)->first();
						if(count($check_ssn))
						{
							$duplicated++;
						}
						else{
							$get_network_details = DB::table('network')->where('network_name',$network_id)->first();
							if(count($get_network_details))
							{
								$get_products = explode(",",$get_network_details->product_id);
								if(in_array($product_id, $get_products))
								{
										$datasim['ssn'] = $ssn;
										$datasim['cli'] = $cli;
										$datasim['network_id'] = $network_id;
										$datasim['product_id'] = $product_id;
										$datasim['allocated'] = $allocated_date;
										$datasim['import_date'] = date('Y-m-d');

										DB::table('sim')->insert($datasim);
								}
								else{
									$productid_dint_match++;
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
		if($height >= $highestRow)
		{
			if($duplicated == 0 && $network_dint_match == 0 && $productid_dint_match == 0 && $field_empty == 0)
			{
				return redirect('admin/simcards')->with('message', 'SimCards Imported successfully.');
			}
			else{
				$dataarray['duplicated'] = $duplicated;
				$dataarray['network_dint_match'] = $network_dint_match;
				$dataarray['productid_dint_match'] = $productid_dint_match;
				$dataarray['field_empty'] = $field_empty;
				$dataarray['activated'] = 0;

				return redirect('admin/simcards')->with('success_error', $dataarray);
			}
		}
		else{
			return redirect('admin/simcards?filename='.$name.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&import_type_new=1&duplicated='.$duplicated.'&network_dint_match='.$network_dint_match.'&productid_dint_match='.$productid_dint_match.'&field_empty='.$field_empty.'');
		}
	}

	public function import_activation_sim()
	{
		if($_FILES['activation_file']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';

			$create_upload_dir = 'uploads/importfiles';
			if(!file_exists($create_upload_dir))
			{
				mkdir($create_upload_dir);
			}

			$tmp_name = $_FILES['activation_file']['tmp_name'];
			$name=$_FILES['activation_file']['name'];

			$duplicated = 0;
			$network_dint_match = 0;
			$productid_dint_match = 0;
			$field_empty = 0;
			$activated = 0;
			$no_ssn = 0;
			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){
				$filepath = $uploads_dir.'/'.$name;
				$objPHPExcel = PHPExcel_IOFactory::load($filepath);
				foreach ($objPHPExcel->getWorksheetIterator() as $keysheet => $worksheet) {
					if($keysheet == 0)
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

						$network_id_title = $worksheet->getCellByColumnAndRow(0, 1); $network_id_title = trim($network_id_title->getValue());
						$product_id_title = $worksheet->getCellByColumnAndRow(1, 1); $product_id_title = trim($product_id_title->getValue());
						$ssn_title = $worksheet->getCellByColumnAndRow(2, 1); $ssn_title = trim($ssn_title->getValue());
						$cli_title = $worksheet->getCellByColumnAndRow(3, 1); $cli_title = trim($cli_title->getValue());
						$activity_date_title = $worksheet->getCellByColumnAndRow(4, 1); $activity_date_title = trim($activity_date_title->getValue());

						if($ssn_title == "ssn2" && $cli_title == "cli" && $network_id_title == "network_id" && $product_id_title == "product_id" && $activity_date_title == "activity_date")
						{
							for ($row = 2; $row <= $height; ++ $row) {
								$network_id = $worksheet->getCellByColumnAndRow(0, $row); $network_id = trim($network_id->getValue());
								$product_id = $worksheet->getCellByColumnAndRow(1, $row); $product_id = trim($product_id->getValue());
								$ssn = $worksheet->getCellByColumnAndRow(2, $row); $ssn = trim($ssn->getValue());
								$cli = $worksheet->getCellByColumnAndRow(3, $row); $cli = trim($cli->getValue());
								$activity_date = $worksheet->getCellByColumnAndRow(4, $row); $activity_date = trim($activity_date->getValue());

								if($ssn == "" || $network_id == "" || $product_id == "" || $activity_date == "")
								{
									$field_empty++;
								}
								else{
									$check_ssn = DB::table('sim')->where('ssn',$ssn)->first();
									if(count($check_ssn))
									{
										$get_network_details = DB::table('network')->where('network_name',$network_id)->first();
										if(count($get_network_details))
										{
											$get_products = explode(",",$get_network_details->product_id);
											if(in_array($product_id, $get_products))
											{
												$check_ssn = DB::table('sim')->where('ssn',$ssn)->where('network_id',$network_id)->where('product_id',$product_id)->first();
												if(count($check_ssn))
												{
													$explode_activity_date = explode("/",$activity_date);
													$explode_hyphen_activity_date = explode("-",$activity_date);
													if(count($explode_activity_date) == 3)
													{
														$inc_date = $explode_activity_date[2].'-'.$explode_activity_date[1].'-'.$explode_activity_date[0];
														$data['activity_date'] = date('Y-m-d',strtotime($inc_date));
													}
													elseif(count($explode_hyphen_activity_date) == 3){
														$data['activity_date'] = date('Y-m-d',strtotime($activity_date));
													}
													else{
														$unix_date = ($activity_date - 25569) * 86400;
														$excel_date = 25569 + ($unix_date / 86400);
														$unix_date = ($excel_date - 25569) * 86400;
														$data['activity_date'] = gmdate("Y-m-d", $unix_date);
													}
													$data['activation_date'] = date('Y-m-d');

													DB::table('sim')->where('id',$check_ssn->id)->update($data);
													$activated++;
												}
											}
											else{
												$productid_dint_match++;
											}
										}
										else{
											$network_dint_match++;
										}
									}
									else{
										$no_ssn++;
									}
								}
							}
						}
						else{
							return redirect('admin/simcards')->with('message', 'Import Failed! Invalid File');
						}
					
						if($height >= $highestRow)
						{
							$dataarray['duplicated'] = $duplicated;
							$dataarray['network_dint_match'] = $network_dint_match;
							$dataarray['productid_dint_match'] = $productid_dint_match;
							$dataarray['field_empty'] = $field_empty;
							$dataarray['activated'] = $activated;
							$dataarray['no_ssn'] = $no_ssn;

							return redirect('admin/simcards')->with('success_error_one', $dataarray);
						}
						else{
							return redirect('admin/simcards?filename='.$name.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type_new=2&duplicated='.$duplicated.'&network_dint_match='.$network_dint_match.'&productid_dint_match='.$productid_dint_match.'&field_empty='.$field_empty.'&activated='.$activated.'&no_ssn='.$no_ssn.'');
						}
					}
				}
			}
		}
	}
	public function import_activation_sim_one()
	{
		$name = Input::get('filename');
		$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
		$filepath = $uploads_dir.'/'.$name;
		$objPHPExcel = PHPExcel_IOFactory::load($filepath);
		
		$duplicated = $_GET['duplicated'];
		$network_dint_match = $_GET['network_dint_match'];
		$productid_dint_match = $_GET['productid_dint_match'];
		$field_empty = $_GET['field_empty'];
		$activated = $_GET['activated'];
		$no_ssn = $_GET['no_ssn'];
		foreach ($objPHPExcel->getWorksheetIterator() as $keysheet => $worksheet) {
			if($keysheet == 0)
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
					$network_id = $worksheet->getCellByColumnAndRow(0, $row); $network_id = trim($network_id->getValue());
					$product_id = $worksheet->getCellByColumnAndRow(1, $row); $product_id = trim($product_id->getValue());
					$ssn = $worksheet->getCellByColumnAndRow(2, $row); $ssn = trim($ssn->getValue());
					$cli = $worksheet->getCellByColumnAndRow(3, $row); $cli = trim($cli->getValue());
					$activity_date = $worksheet->getCellByColumnAndRow(4, $row); $activity_date = trim($activity_date->getValue());

					if($ssn == "" || $network_id == "" || $product_id == "" || $activity_date == "")
					{
						$field_empty++;
					}
					else{
						$check_ssn = DB::table('sim')->where('ssn',$ssn)->first();
						if(count($check_ssn))
						{
							$get_network_details = DB::table('network')->where('network_name',$network_id)->first();
							if(count($get_network_details))
							{
								$get_products = explode(",",$get_network_details->product_id);
								if(in_array($product_id, $get_products))
								{
									$check_ssn = DB::table('sim')->where('ssn',$ssn)->where('network_id',$network_id)->where('product_id',$product_id)->first();
									if(count($check_ssn))
									{
										$explode_activity_date = explode("/",$activity_date);
										$explode_hyphen_activity_date = explode("-",$activity_date);
										if(count($explode_activity_date) == 3)
										{
											$inc_date = $explode_activity_date[2].'-'.$explode_activity_date[1].'-'.$explode_activity_date[0];
											$data['activity_date'] = date('Y-m-d',strtotime($inc_date));
										}
										elseif(count($explode_hyphen_activity_date) == 3){
											$data['activity_date'] = date('Y-m-d',strtotime($activity_date));
										}
										else{
											$unix_date = ($activity_date - 25569) * 86400;
											$excel_date = 25569 + ($unix_date / 86400);
											$unix_date = ($excel_date - 25569) * 86400;
											$data['activity_date'] = gmdate("Y-m-d", $unix_date);
										}
										$data['activation_date'] = date('Y-m-d');

										DB::table('sim')->where('id',$check_ssn->id)->update($data);
										$activated++;
									}
								}
								else{
									$productid_dint_match++;
								}
							}
							else{
								$network_dint_match++;
							}
						}
						else{
							$no_ssn++;
						}
					}
				}
				
			}
			if($height >= $highestRow)
			{
				$dataarray['duplicated'] = $duplicated;
				$dataarray['network_dint_match'] = $network_dint_match;
				$dataarray['productid_dint_match'] = $productid_dint_match;
				$dataarray['field_empty'] = $field_empty;
				$dataarray['activated'] = $activated;
				$dataarray['no_ssn'] = $no_ssn;

				return redirect('admin/simcards')->with('success_error_one', $dataarray);
			}
			else{
				return redirect('admin/simcards?filename='.$name.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&import_type_new=2&duplicated='.$duplicated.'&network_dint_match='.$network_dint_match.'&productid_dint_match='.$productid_dint_match.'&field_empty='.$field_empty.'&activated='.$activated.'&no_ssn='.$no_ssn.'');
			}
		}
	}

	public function simimportdetails(){
		$date = Input::get('date');
		
		$list_date = DB::table('sim')->where('import_date', $date)->get();
		$output='';
		$i=1;
		if(count($list_date)){
			foreach ($list_date as $date) {
				if($date->activity_date == '0000-00-00'){
					$status ='Inactive';
				}
				else{
					$status = 'Active('.date('d-M-Y', strtotime($date->activity_date)).')';
				}

				$shop_details = DB::table('shop')->where('shop_id', $date->shop_id)->first();

				if(count($shop_details)){
					$shop_name = $shop_details->shop_name.' CC-'.$shop_details->shop_id;
				}
				else{
					$shop_name = '';
				}

				$sales_name = '';
				if($date->shop_id != ''){
					//$sim_allocate = DB::table('sim_allocate')->where('sim', 'like', '%'.$date->id.'%')->first();
					$sim_allocate = DB::table('sim_allocate')->whereRaw('FIND_IN_SET('.$date->id.',sim)')->first();

					$sales_rep = DB::table('sales_rep')->where('user_id', $sim_allocate->sales_rep_id)->first();
					if(count($sales_rep)){
						$sales_name = $sales_rep->firstname;
					}
				}

				$output.='
				<tr>
		      		<td>'.$i.'</td>
		      		<td>'.$date->network_id.'</td>
		      		<td>'.$date->product_id.'</td>
		      		<td>'.$date->ssn.'</td>
		      		<td>'.$date->cli.'</td>
		      		<td>'.date('d-M-Y', strtotime($date->allocated)).'</td>
		      		<td>'.$sales_name.'</td>
		      		<td>'.$shop_name.'</td>
		      		<td>'.$status.'</td>
		      	</tr>
				';
				$i++;
			}
		}
		else{
			$output='<tr>
		      		<td></td>
		      		<td></td>
		      		<td></td>
		      		<td align="center">Empty</td>
		      		<td></td>
		      		<td></td>
		      		<td></td>
		      	</tr>';
		}

		echo json_encode(array('output' => $output));

	}

	public function totalsim(){
		$type =Input::get('type');
		$id = base64_decode(Input::get('id'));

		// if type 1 Total Sim for Area Manager
		// if type 2 Total Sim for Sales REP
		// if type 3 Total Sim for Route
		// if type 4 Total Sim Shop

		if($type == 1){			
			$user_details = DB::table('area_manager')->where('user_id', $id)->first();
			$explode_area_sim = explode(',', $user_details->area);

			$sim_list='';
			if(count($explode_area_sim)){
				foreach ($explode_area_sim as $area_sim) {
					$total_sim_user = DB::table('sim_allocate')->where('area_id',$area_sim)->where('sim', '!=', '')->get();
					if(count($total_sim_user)){
						foreach ($total_sim_user as $sim_user) {
							$explode_sim = explode(',', $sim_user->sim);
							if(count($explode_sim)){
								foreach ($explode_sim as $sim) {									
									if($sim_list == ''){
										$sim_list = $sim;
									}
									else{
										$sim_list = $sim.','.$sim_list;	
									}									
								}
							}
                            
						}
					}
				}
			}

			$title = 'Total sim for '.$user_details->firstname.' '.$user_details->surname;
						
		}
		elseif($type == 2){
			$user_details = DB::table('sales_rep')->where('user_id', $id)->first();
			$explode_area_sim = explode(',', $user_details->area);

			$sim_list='';
			if(count($explode_area_sim)){
				foreach ($explode_area_sim as $area_sim) {					
					$total_sim_user = DB::table('sim_allocate')->where('area_id',$area_sim)->where('sales_rep_id', $id)->where('sim', '!=', '')->get();
					if(count($total_sim_user)){
						foreach ($total_sim_user as $sim_user) {
							$explode_sim = explode(',', $sim_user->sim);
							if(count($explode_sim)){
								foreach ($explode_sim as $sim) {									
									if($sim_list == ''){
										$sim_list = $sim;
									}
									else{
										$sim_list = $sim.','.$sim_list;	
									}									
								}
							}
                            
						}
					}
				}
			}

			$title = 'Total sim for '.$user_details->firstname.' '.$user_details->surname;

		}
		elseif($type == 3){
			$total_sim_user = DB::table('sim_allocate')->where('route_id',$id)->where('sim', '!=', '')->get();

			$sim_list='';
			if(count($total_sim_user)){
				foreach ($total_sim_user as $sim_user) {
					$explode_sim = explode(',', $sim_user->sim);
					if(count($explode_sim)){
						foreach ($explode_sim as $sim) {									
							if($sim_list == ''){
								$sim_list = $sim;
							}
							else{
								$sim_list = $sim.','.$sim_list;	
							}									
						}
					}
                    
				}
			}
			$route = DB::table('route')->where('route_id', $id)->first();
			$title = 'Total sim for Route '.$route->route_name;
		}
		elseif($type == 4){
			$total_sim_user = DB::table('sim_allocate')->where('shop_id',$id)->where('sim', '!=', '')->get();

			$sim_list='';
			if(count($total_sim_user)){
				foreach ($total_sim_user as $sim_user) {
					$explode_sim = explode(',', $sim_user->sim);
					if(count($explode_sim)){
						foreach ($explode_sim as $sim) {									
							if($sim_list == ''){
								$sim_list = $sim;
							}
							else{
								$sim_list = $sim.','.$sim_list;	
							}									
						}
					}
                    
				}
			}
			$shop = DB::table('shop')->where('shop_id', $id)->first();
			$title = 'Total sim for Shop '.$shop->shop_name;
		}	

		$explode_sim_list = explode(',', $sim_list);

		
		$output='';
		$i=1;
		if(count($explode_sim_list)){
			foreach ($explode_sim_list as $single_sim) {
				$sim_details = DB::table('sim')->where('id', $single_sim)->first();

				if($sim_details->activity_date == '0000-00-00'){
					$status ='Inactive';
				}
				else{
					$status = 'Active('.date('d-M-Y', strtotime($sim_details->activity_date)).')';
				}

				$shop_details = DB::table('shop')->where('shop_id', $sim_details->shop_id)->first();

				if(count($shop_details)){
					$shop_name = $shop_details->shop_name.' CC-'.$shop_details->shop_id;
				}
				else{
					$shop_name = '';
				}

				$sales_name = '';
				if($sim_details->shop_id != ''){
					//$sim_allocate = DB::table('sim_allocate')->where('sim', 'like', '%'.$sim_details->id.'%')->first();
					$sim_allocate = DB::table('sim_allocate')->whereRaw('FIND_IN_SET('.$sim_details->id.',sim)')->first();

					$sales_rep = DB::table('sales_rep')->where('user_id', $sim_allocate->sales_rep_id)->first();
					if(count($sales_rep)){
						$sales_name = $sales_rep->firstname;
					}
					else{
						$sales_name='';
					}					

				}

				$output.='
				<tr>
		      		<td>'.$i.'</td>
		      		<td>'.$sim_details->network_id.'</td>
		      		<td>'.$sim_details->product_id.'</td>
		      		<td>'.$sim_details->ssn.'</td>
		      		<td>'.$sim_details->cli.'</td>
		      		<td>'.date('d-M-Y', strtotime($sim_details->allocated)).'</td>
		      		<td>'.$sales_name.'</td>
		      		<td>'.$shop_name.'</td>
		      		<td>'.$status.'</td>
		      	</tr>
				';
				$i++;
			}
		}
		else{
			$output='<tr>
		      		<td></td>
		      		<td></td>
		      		<td></td>
		      		<td align="center">Empty</td>
		      		<td></td>
		      		<td></td>
		      		<td></td>
		      	</tr>';
		}

		echo json_encode(array('output' => $output, 'title' => $title));
	}

	public function activesim(){
		$type = Input::get('type');
		$id = base64_decode(Input::get('id'));
		

		// if type 1 Active Sim for Area Manager
		// if type 2 Active Sim for Sales REP
		// if type 3 Active Sim for Route
		// if type 4 Active Sim Shop

		if($type == 1){
			$user_details = DB::table('area_manager')->where('user_id', $id)->first();
			$explode_area_sim = explode(',', $user_details->area);

			$sim_list='';
			if(count($explode_area_sim)){
				foreach ($explode_area_sim as $area_sim) {
					$total_sim_user = DB::table('sim_allocate')->where('area_id',$area_sim)->where('sim', '!=', '')->get();
					if(count($total_sim_user)){
						foreach ($total_sim_user as $sim_user) {
							$explode_sim = explode(',', $sim_user->sim);
							if(count($explode_sim)){
								foreach ($explode_sim as $sim) {

									$active_sim = DB::table('sim')->where('id',$sim)->where('activity_date', '!=', '0000-00-00')->first();

									if(count($active_sim)){
										if($sim_list == ''){
										$sim_list = $sim;
										}
										else{
											$sim_list = $sim.','.$sim_list;	
										}	
									}

								}
							}
                            
						}
					}
				}
			}

			$title = 'Active sim for '.$user_details->firstname.' '.$user_details->surname;
		}
		elseif($type == 2){
			$user_details = DB::table('sales_rep')->where('user_id', $id)->first();
			$explode_area_sim = explode(',', $user_details->area);

			$sim_list='';
			if(count($explode_area_sim)){
				foreach ($explode_area_sim as $area_sim) {
					$total_sim_user = DB::table('sim_allocate')->where('area_id',$area_sim)->where('sales_rep_id', $id)->where('sim', '!=', '')->get();
					if(count($total_sim_user)){
						foreach ($total_sim_user as $sim_user) {
							$explode_sim = explode(',', $sim_user->sim);
							if(count($explode_sim)){
								foreach ($explode_sim as $sim) {

									$active_sim = DB::table('sim')->where('id',$sim)->where('activity_date', '!=', '0000-00-00')->first();

									if(count($active_sim)){
										if($sim_list == ''){
										$sim_list = $sim;
										}
										else{
											$sim_list = $sim.','.$sim_list;	
										}	
									}

								}
							}
                            
						}
					}
				}
			}

			$title = 'Active sim for '.$user_details->firstname.' '.$user_details->surname;
		}
		elseif($type == 3){
			$total_sim_user = DB::table('sim_allocate')->where('route_id',$id)->where('sim', '!=', '')->get();

			$sim_list='';
			if(count($total_sim_user)){
				foreach ($total_sim_user as $sim_user) {
					$explode_sim = explode(',', $sim_user->sim);
					if(count($explode_sim)){
						foreach ($explode_sim as $sim) {

							$active_sim = DB::table('sim')->where('id',$sim)->where('activity_date', '!=', '0000-00-00')->first();

							if(count($active_sim)){
								if($sim_list == ''){
								$sim_list = $sim;
								}
								else{
									$sim_list = $sim.','.$sim_list;	
								}	
							}

						}
					}
                    
				}
			}

			$route = DB::table('route')->where('route_id', $id)->first();
			$title = 'Active sim for Route '.$route->route_name;
		}
		elseif($type == 4){
			$total_sim_user = DB::table('sim_allocate')->where('shop_id',$id)->where('sim', '!=', '')->get();

			$sim_list='';
			if(count($total_sim_user)){
				foreach ($total_sim_user as $sim_user) {
					$explode_sim = explode(',', $sim_user->sim);
					if(count($explode_sim)){
						foreach ($explode_sim as $sim) {

							$active_sim = DB::table('sim')->where('id',$sim)->where('activity_date', '!=', '0000-00-00')->first();

							if(count($active_sim)){
								if($sim_list == ''){
								$sim_list = $sim;
								}
								else{
									$sim_list = $sim.','.$sim_list;	
								}	
							}

						}
					}
                    
				}
			}

			$shop = DB::table('shop')->where('shop_id', $id)->first();
			$title = 'Active sim for Shop '.$shop->shop_name;
		}

		$explode_sim_list = explode(',', $sim_list);


		$output='';
		$i=1;
		if(count($explode_sim_list)){
			foreach ($explode_sim_list as $single_sim) {
				$sim_details = DB::table('sim')->where('id', $single_sim)->first();
				

				if($sim_details->activity_date == '0000-00-00'){
					$status ='Inactive';
				}
				else{
					$status = 'Active('.date('d-M-Y', strtotime($sim_details->activity_date)).')';
				}

				$shop_details = DB::table('shop')->where('shop_id', $sim_details->shop_id)->first();

				if(count($shop_details)){
					$shop_name = $shop_details->shop_name.' CC-'.$shop_details->shop_id;
				}
				else{
					$shop_name = '';
				}

				$sales_name = '';
				if($sim_details->shop_id != ''){
					//$sim_allocate = DB::table('sim_allocate')->where('sim', 'like', '%'.$sim_details->id.'%')->first();
					$sim_allocate = DB::table('sim_allocate')->whereRaw('FIND_IN_SET('.$sim_details->id.',sim)')->first();
					$sales_rep = DB::table('sales_rep')->where('user_id', $sim_allocate->sales_rep_id)->first();
					if(count($sales_rep)){
						$sales_name = $sales_rep->firstname;
					}
				}

				$output.='
				<tr>
		      		<td>'.$i.'</td>
		      		<td>'.$sim_details->network_id.'</td>
		      		<td>'.$sim_details->product_id.'</td>
		      		<td>'.$sim_details->ssn.'</td>
		      		<td>'.$sim_details->cli.'</td>
		      		<td>'.date('d-M-Y', strtotime($sim_details->allocated)).'</td>
		      		<td>'.$sales_name.'</td>
		      		<td>'.$shop_name.'</td>
		      		<td>'.$status.'</td>
		      	</tr>
				';
				$i++;
			}
		}
		else{
			$output='<tr>
		      		<td></td>
		      		<td></td>
		      		<td></td>
		      		<td align="center">Empty</td>
		      		<td></td>
		      		<td></td>
		      		<td></td>
		      	</tr>';
		}

		echo json_encode(array('output' => $output, 'title' => $title));


	}

	public function inactivesim(){
		$type = Input::get('type');
		$id = base64_decode(Input::get('id'));
		

		// if type 1 Inactive Sim for Area Manager
		// if type 2 Inactive Sim for Sales REP
		// if type 3 Inactive Sim for Route
		// if type 4 Inactive Sim Shop

		if($type == 1){
			$user_details = DB::table('area_manager')->where('user_id', $id)->first();
			$explode_area_sim = explode(',', $user_details->area);

			$sim_list='';
			if(count($explode_area_sim)){
				foreach ($explode_area_sim as $area_sim) {
					$total_sim_user = DB::table('sim_allocate')->where('area_id',$area_sim)->where('sim', '!=', '')->get();
					if(count($total_sim_user)){
						foreach ($total_sim_user as $sim_user) {
							$explode_sim = explode(',', $sim_user->sim);
							if(count($explode_sim)){
								foreach ($explode_sim as $sim) {

									$inactive_sim = DB::table('sim')->where('id',$sim)->where('activity_date', '0000-00-00')->first();

									if(count($inactive_sim)){
										if($sim_list == ''){
										$sim_list = $sim;
										}
										else{
											$sim_list = $sim.','.$sim_list;	
										}	
									}

								}
							}
                            
						}
					}
				}
			}

			$title = 'Inactive sim for '.$user_details->firstname.' '.$user_details->surname;
		}
		elseif($type == 2){
			$user_details = DB::table('sales_rep')->where('user_id', $id)->first();
			$explode_area_sim = explode(',', $user_details->area);

			$sim_list='';
			if(count($explode_area_sim)){
				foreach ($explode_area_sim as $area_sim) {
					$total_sim_user = DB::table('sim_allocate')->where('area_id',$area_sim)->where('sales_rep_id', $id)->where('sim', '!=', '')->get();
					if(count($total_sim_user)){
						foreach ($total_sim_user as $sim_user) {
							$explode_sim = explode(',', $sim_user->sim);
							if(count($explode_sim)){
								foreach ($explode_sim as $sim) {

									$inactive_sim = DB::table('sim')->where('id',$sim)->where('activity_date', '0000-00-00')->first();

									if(count($inactive_sim)){
										if($sim_list == ''){
										$sim_list = $sim;
										}
										else{
											$sim_list = $sim.','.$sim_list;	
										}	
									}

								}
							}
                            
						}
					}
				}
			}

			$title = 'Inactive sim for '.$user_details->firstname.' '.$user_details->surname;
		}
		elseif($type == 3){
			$total_sim_user = DB::table('sim_allocate')->where('route_id',$id)->where('sim', '!=', '')->get();

			$sim_list='';
			if(count($total_sim_user)){
				foreach ($total_sim_user as $sim_user) {
					$explode_sim = explode(',', $sim_user->sim);
					if(count($explode_sim)){
						foreach ($explode_sim as $sim) {

							$inactive_sim = DB::table('sim')->where('id',$sim)->where('activity_date', '0000-00-00')->first();

							if(count($inactive_sim)){
								if($sim_list == ''){
								$sim_list = $sim;
								}
								else{
									$sim_list = $sim.','.$sim_list;	
								}	
							}

						}
					}
                    
				}
			}
			$route = DB::table('route')->where('route_id', $id)->first();
			$title = 'Inactive sim for Route '.$route->route_name;
		}
		elseif($type == 4){
			$total_sim_user = DB::table('sim_allocate')->where('shop_id',$id)->where('sim', '!=', '')->get();

			$sim_list='';
			if(count($total_sim_user)){
				foreach ($total_sim_user as $sim_user) {
					$explode_sim = explode(',', $sim_user->sim);
					if(count($explode_sim)){
						foreach ($explode_sim as $sim) {

							$inactive_sim = DB::table('sim')->where('id',$sim)->where('activity_date', '0000-00-00')->first();

							if(count($inactive_sim)){
								if($sim_list == ''){
								$sim_list = $sim;
								}
								else{
									$sim_list = $sim.','.$sim_list;	
								}	
							}

						}
					}
                    
				}
			}
			$shop = DB::table('shop')->where('shop_id', $id)->first();
			$title = 'Inactive sim for Shop '.$shop->shop_name;
		}

		$explode_sim_list = explode(',', $sim_list);


		$output='';
		$i=1;
		if(count($explode_sim_list)){
			foreach ($explode_sim_list as $single_sim) {
				$sim_details = DB::table('sim')->where('id', $single_sim)->first();
				

				if($sim_details->activity_date == '0000-00-00'){
					$status ='Inactive';
				}
				else{
					$status = 'Active('.date('d-M-Y', strtotime($sim_details->activity_date)).')';
				}

				$shop_details = DB::table('shop')->where('shop_id', $sim_details->shop_id)->first();

				if(count($shop_details)){
					$shop_name = $shop_details->shop_name.' CC-'.$shop_details->shop_id;
				}
				else{
					$shop_name = '';
				}

				$sales_name = '';
				if($sim_details->shop_id != ''){
					//$sim_allocate = DB::table('sim_allocate')->where('sim', 'like', '%'.$sim_details->id.'%')->first();
					$sim_allocate = DB::table('sim_allocate')->whereRaw('FIND_IN_SET('.$sim_details->id.',sim)')->first();

					$sales_rep = DB::table('sales_rep')->where('user_id', $sim_allocate->sales_rep_id)->first();
					if(count($sales_rep)){
						$sales_name = $sales_rep->firstname;
					}
				}

				$output.='
				<tr>
		      		<td>'.$i.'</td>
		      		<td>'.$sim_details->network_id.'</td>
		      		<td>'.$sim_details->product_id.'</td>
		      		<td>'.$sim_details->ssn.'</td>
		      		<td>'.$sim_details->cli.'</td>
		      		<td>'.date('d-M-Y', strtotime($sim_details->allocated)).'</td>
		      		<td>'.$sales_name.'</td>
		      		<td>'.$shop_name.'</td>
		      		<td>'.$status.'</td>
		      	</tr>
				';
				$i++;
			}
		}
		else{
			$output='<tr>
		      		<td></td>
		      		<td></td>
		      		<td></td>
		      		<td align="center">Empty</td>
		      		<td></td>
		      		<td></td>
		      		<td></td>
		      	</tr>';
		}

		echo json_encode(array('output' => $output, 'title' => $title));


	}
}

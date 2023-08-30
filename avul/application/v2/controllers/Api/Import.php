<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class Import extends CI_Controller {

		public function __construct()
		{
			parent::__construct();

			$this->load->model('outlets_model');
			$this->load->model('commom_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Beat Loyalty
		// ***************************************************
		public function beat_import($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');
			$state  = $this->input->post('state');
			$city   = $this->input->post('city');

			if($method == '_uploadBeat')
			{
				$error    = FALSE;
			    $errors   = array();
				$required = array('state', 'city');
			    foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error)
			    {
			        $response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(count($errors)==0)
			    {
			    	$filename   = $_FILES['import_file']['name'];
					$exp_value  = explode(".",$filename);
					$exp_result = end($exp_value);

					if($exp_result =="csv")
					{
						$fileData = file_get_contents($_FILES['import_file']['tmp_name']);
						if($fileData)
						{
							$data_val = str_getcsv($fileData, "\n"); 
						    unset($data_val[0]);
							$data_cnt = !empty($data_val)?count($data_val):0;
							if($data_cnt != 0)
							{
								$ins_data = array();
								foreach($data_val as $rawRow)
								{
									$s_whr = array('id' => $state);
									$s_col = 'state_code';
									$s_qry = $this->commom_model->getState($s_whr, '', '', 'row', '', '', '', '', $s_col);
									$s_cod = !empty($s_qry->state_code)?$s_qry->state_code:NULL;

									$c_whr = array('id' => $state);
									$c_col = 'city_code';
									$c_qry = $this->commom_model->getCity($c_whr, '', '', 'row', '', '', '', '', $c_col);
									$c_cod = !empty($c_qry->city_code)?$c_qry->city_code:NULL;

									$rowData    = str_getcsv($rawRow, ",");

									// Check exist data
									$ex_whr = array('name' => $s_cod.'/'.$c_cod.'/'.$rowData[1], 'published' => '1');
									$ex_col = 'COUNT(id) AS autoid';
									$ex_qry = $this->commom_model->getZone($ex_whr, '', '', 'row', '', '', '', '', $ex_col);
									$ex_val = ($ex_qry->autoid)?$ex_qry->autoid:0;

									if($ex_val == 0)
									{
										$ins_data[] = array(
											'state_id'   => $state,
											'city_id'    => $city,
											'beat_name'  => $rowData[0],
											'name'       => $s_cod.'/'.$c_cod.'/'.$rowData[1],
											'createdate' => date('Y-m-d H:i:s')
										);
									}
								}

								$ins_cnt = count($ins_data);

								if($ins_cnt > 0)
								{
									$insert = $this->commom_model->zone_insert_batch($ins_data);

									if($insert)
									{
										$response['status']  = TRUE;
					        			$response['message'] = "Success";
					        			echo json_encode($response);
					        			return;
									}
									else
									{
										$response['status']  = FALSE;
					        			$response['message'] = "Oops! something went wrong";
					        			echo json_encode($response);
					        			return;	
									}
								}
								else
								{
									$response['status']  = FALSE;
				        			$response['message'] = "Oops! data already exist";
				        			echo json_encode($response);
				        			return;
								}
							}
							else
							{
								$response['status']  = FALSE;
			        			$response['message'] = "Oops! data not found";
			        			echo json_encode($response);
			        			return;
							}
						}
						else
						{
							$response['status']  = FALSE;
		        			$response['message'] = "Oops! something went wrong";
		        			echo json_encode($response);
		        			return;
						}
					}
					else
					{
						$response['status']  = FALSE;
	        			$response['message'] = "Oops! invalid file";
	        			echo json_encode($response);
	        			return;
					}
			    }
			}
		}

		// Outlet Loyalty
		// ***************************************************
		public function outlet_import($param1="",$param2="",$param3="")
		{
			$method         = $this->input->post('method');
			$distributor_id = $this->input->post('distributor_id');

			if($method == '_uploadOutlet')
			{
				$filename   = $_FILES['import_file']['name'];
				$exp_value  = explode(".",$filename);
				$exp_result = end($exp_value);

				if($_FILES['import_file']['name'])
				{
					if($exp_result =="csv")
					{
						$fileData = file_get_contents($_FILES['import_file']['tmp_name']);
						if($fileData)
						{
							$data_val = str_getcsv($fileData, "\n"); 
						    unset($data_val[0]);
							$data_cnt = !empty($data_val)?count($data_val):0;
							if($data_cnt != 0)
							{
								$ins_data = array();
								foreach($data_val as $rawRow)
								{
									$rowData    = str_getcsv($rawRow, ",");

									// Beat details
									$beat_whr = array('A.name' => $rowData[14], 'A.published' => '1');
									$beat_col = 'A.id AS zone_id, A.state_id, A.city_id, B.country_id';
									$beat_col = $this->commom_model->getZoneJoin($beat_whr, '', '', 'row', '', '', '', '', $beat_col);

									$zone_id    = !empty($beat_col->zone_id)?$beat_col->zone_id:NULL;
								    $state_id   = !empty($beat_col->state_id)?$beat_col->state_id:NULL;
								    $city_id    = !empty($beat_col->city_id)?$beat_col->city_id:NULL;
								    $country_id = !empty($beat_col->country_id)?$beat_col->country_id:NULL;

								    // Check exist
								    $str_name = ($rowData[1])?urlSlug($rowData[1]):null;
								    $gst_num  = ($rowData[0]=='Registered')?$rowData[5]:'GSTIN';
								    $ex_whr   = array('short_code' => $str_name, 'gst_no' => $gst_num, 'published' => 1);
								    $ex_col = 'COUNT(id) AS autoid';
									$ex_qry = $this->outlets_model->getOutlets($ex_whr, '', '', 'row', '', '', '', '', $ex_col);
									$ex_val = ($ex_qry->autoid)?$ex_qry->autoid:0;

									if($ex_val == 0)
									{
										$ins_data[] = array(
											'outlet_status'   => ($distributor_id!=0)?2:1,
											'distributor_id'  => ($distributor_id)?$distributor_id:0,
											'gst_type'        => ($rowData[0]=='Registered')?2:1,
											'company_name'    => ($rowData[1])?$rowData[1]:null,
											'short_code'      => ($rowData[1])?urlSlug($rowData[1]):null,
											'contact_name'    => ($rowData[2])?$rowData[2]:null,
											'mobile'          => ($rowData[3])?$rowData[3]:null,
											'email'           => ($rowData[4])?$rowData[4]:null,
											'gst_no'          => ($rowData[0]=='Registered')?$rowData[5]:'GSTIN',
											'credit_limit'    => ($rowData[6])?$rowData[6]:0,
											'available_limit' => ($rowData[6])?$rowData[6]:0,
											'pre_limit'       => 0,
											'current_balance' => 0,
											'due_days'        => ($rowData[7])?$rowData[7]:0,
											'account_name'    => ($rowData[8])?$rowData[8]:null,
											'account_no'      => ($rowData[9])?$rowData[9]:null,
											'account_type'    => ($rowData[10])?$rowData[10]:null,
											'ifsc_code'       => ($rowData[11])?$rowData[11]:null,
											'bank_name'       => ($rowData[12])?$rowData[12]:null,
											'branch_name'     => ($rowData[13])?$rowData[13]:null,
											'country_id'      => $country_id,
											'state_id'        => $state_id,
											'city_id'         => $city_id,
											'zone_id'         => $zone_id,
											'pincode'         => ($rowData[15])?$rowData[15]:null,
											'address'         => ($rowData[16])?$rowData[16]:null,
											'location_status' => 2,
										);
									}
								}

								$ins_cnt = count($ins_data);

								if($ins_cnt > 0)
								{
									$insert = $this->outlets_model->outlets_insert_batch($ins_data);

									if($insert)
									{
										$response['status']  = TRUE;
					        			$response['message'] = "Success";
					        			echo json_encode($response);
					        			return;
									}
									else
									{
										$response['status']  = FALSE;
					        			$response['message'] = "Oops! something went wrong";
					        			echo json_encode($response);
					        			return;	
									}
								}
								else
								{
									$response['status']  = FALSE;
				        			$response['message'] = "Oops! data already exist";
				        			echo json_encode($response);
				        			return;
								}
							}
							else
							{
								$response['status']  = FALSE;
			        			$response['message'] = "Oops! data not found";
			        			echo json_encode($response);
			        			return;
							}
						}
						else
						{
							$response['status']  = FALSE;
		        			$response['message'] = "Oops! something went wrong";
		        			echo json_encode($response);
		        			return;
						}
					}
					else
					{
						$response['status']  = FALSE;
	        			$response['message'] = "Oops! invalid file";
	        			echo json_encode($response);
	        			return;
					}
				}
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Please upload required fill"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}
			else
			{
				$response['status']  = 0;
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}
	}
?>
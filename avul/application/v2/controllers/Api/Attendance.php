<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class Attendance extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('attendance_model');
			$this->load->model('outlets_model');
			$this->load->model('employee_model');
			$this->load->model('invoice_model');
			$this->load->model('order_model');
			$this->load->model('payment_model');
			$this->load->model('distributors_model');
			$this->load->model('user_model');
			$this->load->model('commom_model');
			$this->load->model('assignshop_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Attendance Type
		// ***************************************************
		public function attendance_type($param1="",$param2="",$param3="")
		{
			$method      = $this->input->post('method');
			$employee_id = $this->input->post('employee_id');
			$cur_date    = date('Y-m-d');

			if($method == '_attendanceType')
			{	
				$attendance_type = [];
				for ($i=1; $i <= 2 ; $i++) { 

					if($i == 1)
					{
						$type_val = 'Sales Order';
					}
					// else if($i == 2)
					// {
					// 	$type_val = 'Payment Collection';
					// }
					else
					{
						$type_val = 'No Order';
					}

					$attendance_type[] = array(
						'type_id'  => strval($i),
						'type_val' => $type_val,
					);

				}

				$response['status']  = 1;
		        $response['message'] = "Success"; 
		        $response['data']    = $attendance_type;
		        echo json_encode($response);
		        return;
			}

			else if($method == '_presenceType')
			{
				$error    = FALSE;
			    $errors   = array();
				$required = array('employee_id');
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
			    	// Meeting details
			    	$meet_whr  = array('employee_id' => $employee_id, 'assign_date' => $cur_date, 'published' => 1);
			    	$meet_col  = 'meeting_val';
			    	$meet_res  = $this->assignshop_model->getAssignshopDetails($meet_whr, '', '', 'row', '', '', '', '', $meet_col);
			    	$meet_sta  = ($meet_res->meeting_val)?$meet_res->meeting_val:'0';
			    	$click_val = ($meet_sta == 0) ? '1' : '0';

			    }

				$presence_val = array(
					array('autoid' => '1', 'att_val' => 'Distributor point', 'att_code' => 'D', 'click_val' => $meet_sta), 
					array('autoid' => '2', 'att_val' => 'First call', 'att_code' => 'S', 'click_val' => $click_val), 
					array('autoid' => '3', 'att_val' => 'Leave', 'att_code' => 'L', 'click_val' => '1')
				);

				$response['status']  = 1;
		        $response['message'] = "Success"; 
		        $response['data']    = $presence_val;
		        echo json_encode($response);
		        return;

				
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

		// Add / Edit Attendance
		// ***************************************************
		public function add_attendance($param1="",$param2="",$param3="")
		{
			$attendance_id = $this->input->post('attendance_id');
			$employee_id   = $this->input->post('employee_id');
			$store_id      = $this->input->post('store_id');
			$latitude_1    = $this->input->post('latitude');
			$longitude_1   = $this->input->post('longitude');
			$att_type      = $this->input->post('attendance_type');
			$reason        = $this->input->post('reason');
			$order_id      = $this->input->post('order_id');
			$invoice_id    = $this->input->post('invoice_id');
			$upload_status = $this->input->post('upload_status');
			$method        = $this->input->post('method');

			if($method == '_addAttendance')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('employee_id', 'store_id', 'latitude', 'longitude', 'upload_status');
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
			    	$where_1 = array(
						'emp_id'     => $employee_id,
						'store_id'   => $store_id,
						'c_date'     => date('Y-m-d'),
						'log_status' => '1',
						'published'  => '1',
						'status'     => '1',
					);

					$column_1 = 'id';

					$attendance_data = $this->attendance_model->getAttendance($where_1, '', '', 'result', '', '', '', '', $column_1);

					if(!empty($attendance_data))
					{
						$response['status']  = 0;
				        $response['message'] = "Attendance Already Exist"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
					}
					else
					{
						// Check outlet location
						$str_whr = array('id' => $store_id, 'published' => '1', 'status' => '1');
						$str_col = 'id, location_status';
						$str_res = $this->outlets_model->getOutlets($str_whr, '', '', 'row', '', '', '', '', $str_col);
						$loc_sta = ($str_res->location_status)?$str_res->location_status:2;
						
						if($loc_sta == 2)
						{
							// Update location
							$upt_val = array('latitude' => $latitude_1, 'longitude' => $longitude_1, 'location_status' => 1);
							$upt_whr = array('id' => $store_id);
							$upt_res = $this->outlets_model->outlets_update($upt_val, $upt_whr);
						}

						$where_2 = array(
							'id'        => $store_id,
							'published' => '1',
							'status'    => '1',
						);

				    	$column_2 = 'company_name, latitude, longitude';

						$store_data = $this->outlets_model->getOutlets($where_2, '', '', 'result', '', '', '', '', $column_2);

						// Store Details
						$company_name = !empty($store_data[0]->company_name)?$store_data[0]->company_name:'';
						$latitude_2   = !empty($store_data[0]->latitude)?$store_data[0]->latitude:'';
						$longitude_2  = !empty($store_data[0]->longitude)?$store_data[0]->longitude:'';

						$distance_cal = new_dis( (double)$latitude_1, (double)$longitude_1, (double)$latitude_2, (double)$longitude_2);
						$distance_val = (int)$distance_cal['meters'];

						// Get maximum distance value
						$col_1      = 'distance_val';
						$where_1    = array('id' => 1, 'status' => '1', 'published' => '1');
						$admin_data = $this->user_model->getUser($where_1, '', '', 'row', '', '', '', '', $col_1);
						$admin_dis  = ($admin_data->distance_val != '') ? zero_check($admin_data->distance_val) : 500;

						if($admin_dis >= $distance_val)
						{
							$where_3 = array(
								'id'        => $employee_id,
								'published' => '1',
							);

							$column_3 = 'first_name,last_name, log_type';

							$emp_data = $this->employee_model->getEmployee($where_3, '', '', 'result', '', '', '', '', $column_3);

							// Employee Details
							$first_name = !empty($emp_data[0]->first_name)?$emp_data[0]->first_name:'';
							$last_name = !empty($emp_data[0]->last_name)?$emp_data[0]->last_name:'';
							$emp_type = !empty($emp_data[0]->log_type)?$emp_data[0]->log_type:'';
							$position_id = !empty($emp_data[0]->position_id)?$emp_data[0]->position_id:'';
							$arr = array($first_name,$last_name);
							$username =join(" ",$arr);
							$ins_data = array(
								'emp_id'     => $employee_id,
								'emp_name'   => $username,
								'emp_type'   => $emp_type,
								'store_id'   => $store_id,
								'store_name' => $company_name,
								'reason'     => $reason,
								'position_id'=> $position_id,
								'c_date'     => date('Y-m-d'),
								'in_time'    => date('H:i:s'),
								'createdate' => date('Y-m-d H:i:s'),
							);

							// if($upload_status == 2)
							// {
							// 	if(!empty($_FILES['c_image']['name']))
							// 	{
							// 		$img_name  = $_FILES['c_image']['name'];
							// 		$img_val   = explode('.', $img_name);
							// 		$img_res   = $img_val[1];
							// 		$file_name = generateRandomString(13).'.'.$img_res;

							// 		$configImg['upload_path']   = 'upload/attendance/';
							// 		$configImg['max_size']      = '1024000';
							// 		$configImg['allowed_types'] = 'jpg|jpeg|png|gif';
							// 		$configImg['overwrite']     = FALSE;
							// 		$configImg['remove_spaces'] = TRUE;
							// 		$configImg['max_width']     = 4000;
				            //     	$configImg['max_height']    = 4000;
		                	// 		$configImg['file_name']     = $file_name;
							// 		$this->load->library('upload', $configImg);
							// 		$this->upload->initialize($configImg);

							// 		if(!$this->upload->do_upload('c_image'))
							// 		{
							// 	        $response['status']  = 0;
							// 	        $response['message'] = $this->upload->display_errors();
							// 	        $response['data']    = [];
							// 	        echo json_encode($response);
							// 	        return;
							// 		}
							// 		else
							// 		{
							// 			$ins_data['c_image'] = $file_name;
							// 		}
							// 	}
							// 	else
							// 	{
							// 		$response['status']  = 0;
							//         $response['message'] = "Upload Image"; 
							//         $response['data']    = [];
							//         echo json_encode($response);
							//         return; 
							// 	}
							// }

							$insert = $this->attendance_model->attendance_insert($ins_data);

							$att_data[] = array(
								'attendance_id'   => $insert,
								'employee_name'   => $username,
								'store_name'      => $company_name,
								'attendance_date' => date('Y-m-d'),
								'attendance_time' => date('H:i:s'),
							);

							if($insert)
						    {
			        			$response['status']  = 1;
						        $response['message'] = "Success"; 
						        $response['data']    = $att_data;
						        echo json_encode($response);
						        return; 
						    }
						    else
						    {
			        			$response['status']  = 0;
						        $response['message'] = "Not Success"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
						}
						else
						{
							$response['status']  = 0;
					        $response['message'] = "Location Invalid"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
						}
					}
			    }
			}

			else if($method == '_updateAttendance')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('attendance_id', 'employee_id', 'store_id', 'latitude', 'longitude', 'attendance_type');
				if($att_type == 2)
			    {
			    	array_push($required, 'reason');
			    }
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
			    	$where_1 = array(
						'id'        => $store_id,
						'published' => '1',
						'status'    => '1',
					);

			    	$column_1 = 'company_name, latitude, longitude';

					$store_data = $this->outlets_model->getOutlets($where_1, '', '', 'result', '', '', '', '', $column_1);

					// Store Details
					$company_name = !empty($store_data[0]->company_name)?$store_data[0]->company_name:'';
					$latitude_2   = !empty($store_data[0]->latitude)?$store_data[0]->latitude:'';
					$longitude_2  = !empty($store_data[0]->longitude)?$store_data[0]->longitude:'';

					$distance_cal = new_dis( (double)$latitude_1, (double)$longitude_1, (double)$latitude_2, (double)$longitude_2);
					$distance_val = (int)$distance_cal['meters'];

					// if(1500 >= $distance_val)
					// {
						$where_2 = array(
							'id'        => $employee_id,
							'published' => '1',
							'status'    => '1',
						);

						$column_2 = 'first_name,last_name, log_type';

						$emp_data = $this->employee_model->getEmployee($where_2, '', '', 'result', '', '', '', '', $column_2);

						// Employee Details
					
						$first_name = !empty($emp_data[0]->first_name)?$emp_data[0]->first_name:'';
						$last_name = !empty($emp_data[0]->last_name)?$emp_data[0]->last_name:'';
					
						$arr = array($first_name,$last_name);
						$username =join(" ",$arr);

						$upt_data = array(
							'order_id'        => $order_id,
							'invoice_id'      => $invoice_id,
							'out_time'        => date('H:i:s'),
							'attendance_type' => $att_type,
							'reason'          => $reason,
							'out_time'        => date('H:i:s'),
							'log_status'      => '0',
							'updatedate'      => date('Y-m-d H:i:s'),
						);

						$update_id  = array('id' => $attendance_id);

						$update = $this->attendance_model->attendance_update($upt_data, $update_id);

						$att_data[] = array(
							'attendance_id'   => $attendance_id,
							'employee_name'   => $username,
							'store_name'      => $company_name,
							'attendance_date' => date('Y-m-d'),
							'attendance_time' => date('H:i:s'),
						);

						if($update)
					    {
		        			$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $att_data;
					        echo json_encode($response);
					        return; 
					    }
					    else
					    {
		        			$response['status']  = 0;
					        $response['message'] = "Not Success"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
					    }
					// }
					// else
					// {
					// 	$response['status']  = 0;
				 //        $response['message'] = "Location Invalid"; 
				 //        $response['data']    = [];
				 //        echo json_encode($response);
				 //        return;
					// }
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

		// Add / Edit Attendance_managers
		// ***************************************************
		public function add_attendance_mg($param1="",$param2="",$param3="")
		{
			$attendance_id = $this->input->post('attendance_id');
			$employee_id   = $this->input->post('employee_id');
			$reason        = $this->input->post('reason');
			$method        = $this->input->post('method');
			$store_id      = $this->input->post('store_id');

			if($method == '_addAttendance')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('employee_id');
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
			    	$where_1 = array(
						'emp_id'     => $employee_id,
						'c_date'     => date('Y-m-d'),
						'log_status' => '1',
						'published'  => '1',
						'status'     => '1',
					);

					$column_1 = 'id';

					$attendance_data = $this->attendance_model->getAttendance($where_1, '', '', 'result', '', '', '', '', $column_1);

					if(!empty($attendance_data))
					{
						$response['status']  = 0;
				        $response['message'] = "Attendance Already Exist"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
					}
					else
					{
						$where_2 = array(
							'id'        => $store_id,
							'published' => '1',
							'status'    => '1',
						);

				    	$column_2 = 'company_name, latitude, longitude';

						$store_data = $this->outlets_model->getOutlets($where_2, '', '', 'result', '', '', '', '', $column_2);

						// Store Details
						$company_name = !empty($store_data[0]->company_name)?$store_data[0]->company_name:'';
						$latitude_2   = !empty($store_data[0]->latitude)?$store_data[0]->latitude:'';
						$longitude_2  = !empty($store_data[0]->longitude)?$store_data[0]->longitude:'';
						$distance_cal = new_dis( (double)$latitude_1, (double)$longitude_1, (double)$latitude_2, (double)$longitude_2);
						$distance_val = (int)$distance_cal['meters'];

						if(1500 >= $distance_val)
						{
							$where_3 = array(
								'id'        => $employee_id,
								'published' => '1',
							);

							$column_3 = 'first_name,last_name, log_type';

							$emp_data = $this->employee_model->getEmployee($where_3, '', '', 'result', '', '', '', '', $column_3);

							// Employee Details
							$first_name = !empty($emp_data[0]->first_name)?$emp_data[0]->first_name:'';
							$last_name = !empty($emp_data[0]->last_name)?$emp_data[0]->last_name:'';
							$emp_type = !empty($emp_data[0]->log_type)?$emp_data[0]->log_type:'';
							$position_id = !empty($emp_data[0]->position_id)?$emp_data[0]->position_id:'';
							$arr = array($first_name,$last_name);
							$username =join(" ",$arr);
							$ins_data = array(
								'emp_id'     => $employee_id,
								'emp_name'   => $username,
								'emp_type'   => $emp_type,
								'store_id'   => $store_id,
								'store_name' => $company_name,
								'reason'     => $reason,
								'position_id'=> $position_id,
								'c_date'     => date('Y-m-d'),
								'in_time'    => date('H:i:s'),
								'createdate' => date('Y-m-d H:i:s'),
							);

							// if($upload_status == 2)
							// {
							// 	if(!empty($_FILES['c_image']['name']))
							// 	{
							// 		$img_name  = $_FILES['c_image']['name'];
							// 		$img_val   = explode('.', $img_name);
							// 		$img_res   = $img_val[1];
							// 		$file_name = generateRandomString(13).'.'.$img_res;

							// 		$configImg['upload_path']   = 'upload/attendance/';
							// 		$configImg['max_size']      = '1024000';
							// 		$configImg['allowed_types'] = 'jpg|jpeg|png|gif';
							// 		$configImg['overwrite']     = FALSE;
							// 		$configImg['remove_spaces'] = TRUE;
							// 		$configImg['max_width']     = 4000;
				            //          	$configImg['max_height']    = 4000;
		                    //            			$configImg['file_name']     = $file_name;
							// 		$this->load->library('upload', $configImg);
							// 		$this->upload->initialize($configImg);

							// 		if(!$this->upload->do_upload('c_image'))
							// 		{
							// 	        $response['status']  = 0;
							// 	        $response['message'] = $this->upload->display_errors();
							// 	        $response['data']    = [];
							// 	        echo json_encode($response);
							// 	        return;
							// 		}
							// 		else
							// 		{
							// 			$ins_data['c_image'] = $file_name;
							// 		}
							// 	}
							// 	else
							// 	{
							// 		$response['status']  = 0;
							//         $response['message'] = "Upload Image"; 
							//         $response['data']    = [];
							//         echo json_encode($response);
							//         return; 
							// 	}
							// }

							$insert = $this->attendance_model->attendance_insert($ins_data);

							$att_data[] = array(
								'attendance_id'   => $insert,
								'employee_name'   => $username,
								'store_name'      => $company_name,
								'attendance_date' => date('Y-m-d'),
								'attendance_time' => date('H:i:s'),
							);

							if($insert)
						    {
			        			$response['status']  = 1;
						        $response['message'] = "Success"; 
						        $response['data']    = $att_data;
						        echo json_encode($response);
						        return; 
						    }
						    else
						    {
			        			$response['status']  = 0;
						        $response['message'] = "Not Success"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
						}
						else
						{
							$response['status']  = 0;
					        $response['message'] = "Location Invalid"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
						}
					}
			    }
			}

			else if($method == '_updateAttendance')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('attendance_id', 'employee_id', 'store_id', 'latitude', 'longitude', 'attendance_type');
				if($att_type == 2)
			    {
			    	array_push($required, 'reason');
			    }
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
			    	$where_1 = array(
						'id'        => $store_id,
						'published' => '1',
						'status'    => '1',
					);

			    	$column_1 = 'company_name, latitude, longitude';

					$store_data = $this->outlets_model->getOutlets($where_1, '', '', 'result', '', '', '', '', $column_1);

					// Store Details
					$company_name = !empty($store_data[0]->company_name)?$store_data[0]->company_name:'';
					$latitude_2   = !empty($store_data[0]->latitude)?$store_data[0]->latitude:'';
					$longitude_2  = !empty($store_data[0]->longitude)?$store_data[0]->longitude:'';

					$distance_cal = new_dis( (double)$latitude_1, (double)$longitude_1, (double)$latitude_2, (double)$longitude_2);
					$distance_val = (int)$distance_cal['meters'];

					// if(1500 >= $distance_val)
					// {
						$where_2 = array(
							'id'        => $employee_id,
							'published' => '1',
							'status'    => '1',
						);

						$column_2 = 'first_name,last_name, log_type';

						$emp_data = $this->employee_model->getEmployee($where_2, '', '', 'result', '', '', '', '', $column_2);

						// Employee Details
						$first_name = !empty($emp_data[0]->first_name)?$emp_data[0]->first_name:'';
						$last_name = !empty($emp_data[0]->last_name)?$emp_data[0]->last_name:'';

						$arr = array($first_name,$last_name);
								$username =join(" ",$arr);
						$upt_data = array(
							'order_id'        => $order_id,
							'invoice_id'      => $invoice_id,
							'out_time'        => date('H:i:s'),
							'attendance_type' => $att_type,
							'reason'          => $reason,
							'out_time'        => date('H:i:s'),
							'log_status'      => '0',
							'updatedate'      => date('Y-m-d H:i:s'),
						);

						$update_id  = array('id' => $attendance_id);

						$update = $this->attendance_model->attendance_update($upt_data, $update_id);

						$att_data[] = array(
							'attendance_id'   => $attendance_id,
							'employee_name'   => $username,
							'store_name'      => $company_name,
							'attendance_date' => date('Y-m-d'),
							'attendance_time' => date('H:i:s'),
						);

						if($update)
					    {
		        			$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $att_data;
					        echo json_encode($response);
					        return; 
					    }
					    else
					    {
		        			$response['status']  = 0;
					        $response['message'] = "Not Success"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
					    }
					// }
					// else
					// {
					// 	$response['status']  = 0;
				 //        $response['message'] = "Location Invalid"; 
				 //        $response['data']    = [];
				 //        echo json_encode($response);
				 //        return;
					// }
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

		// Manage Attendance
		// ***************************************************
		public function manage_attendance($param1="",$param2="",$param3="")
		{	
			$attendance_id = $this->input->post('attendance_id');
			$employee_id   = $this->input->post('employee_id');
			$current_date  = date('Y-m-d');
			$method        = $this->input->post('method');

			if($method == '_listEmployeeAttendance')
			{
				if(!empty($employee_id))
				{
					$where = array(
						'emp_id'    => $employee_id,
						'c_date'    => $current_date,
						'published' => '1',
						'status'    => '1',
					);

					$attendance_data = $this->attendance_model->getAttendance($where);

					if($attendance_data)
					{
						$attendance_list = [];

						foreach ($attendance_data as $key => $value) {
							
							$att_id     = !empty($value->id)?$value->id:'';
							$emp_name   = !empty($value->emp_name)?$value->emp_name:'';
							$store_name = !empty($value->store_name)?$value->store_name:'';
							$att_type   = !empty($value->attendance_type)?$value->attendance_type:'';
							$reason     = !empty($value->reason)?$value->reason:'';
							$c_date     = !empty($value->c_date)?$value->c_date:'';
							$in_time    = !empty($value->in_time)?$value->in_time:'';
							$out_time   = !empty($value->out_time)?$value->out_time:'';
							$log_status = !empty($value->log_status)?$value->log_status:'0';

							$attendance_list[] = array(
								'attendance_id'   => $att_id,
								'emp_name'        => $emp_name,
								'store_name'      => $store_name,
								'attendance_type' => $att_type,
								'reason'          => $reason,
								'c_date'          => date('d-M-Y', strtotime($c_date)),
								'in_time'         => $in_time,
								'out_time'        => $out_time,
								'log_status'      => $log_status,
							);
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $attendance_list;
				        echo json_encode($response);
				        return;
					}
					else
					{
						$response['status']  = 0;
				        $response['message'] = "Not Found"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
					}
				}
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}

			else if($method == '_listAttendancePaginate')
			{
				$limit  = $this->input->post('limit');
	    		$offset = $this->input->post('offset');

	    		if($limit !='' && $offset !='')
				{
					$limit  = $limit;
					$offset = $offset;
				}
				else
				{
					$limit  = 10;
					$offset = 0;
				}

				$search = $this->input->post('search');
	    		if($search !='')
	    		{
	    			$like['name'] = $search;
	    			$where = array('published'=>'1');
	    		}
	    		else
	    		{
	    			$like = [];
	    			$where = array('published'=>'1');
	    		}

	    		$column = 'id';
				$overalldatas = $this->attendance_model->getAttendance($where, '', '', 'result', $like, '', '', '', $column);

				if($overalldatas)
				{
					$totalc = count($overalldatas);
				}
				else
				{
					$totalc = 0;
				}

				$option['order_by']   = 'id';
				$option['disp_order'] = 'DESC';

				$data_list = $this->attendance_model->getAttendance($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$attendance_list = [];
					
					foreach ($data_list as $key => $value) {

						$att_id     = !empty($value->id)?$value->id:'';
						$emp_name   = !empty($value->emp_name)?$value->emp_name:'';
						$emp_type   = !empty($value->emp_type)?$value->emp_type:'';
						$store_name = !empty($value->store_name)?$value->store_name:'';
						$att_type   = !empty($value->attendance_type)?$value->attendance_type:'';
						$reason     = !empty($value->reason)?$value->reason:'';
						$c_date     = !empty($value->c_date)?$value->c_date:'';
						$in_time    = !empty($value->in_time)?$value->in_time:'';
						$out_time   = !empty($value->out_time)?$value->out_time:'';
						$log_status = !empty($value->log_status)?$value->log_status:'0';

						$attendance_list[] = array(
							'attendance_id'   => $att_id,
							'emp_name'        => $emp_name,
							'emp_type'        => $emp_type,
							'store_name'      => $store_name,
							'attendance_type' => $att_type,
							'reason'          => $reason,
							'c_date'          => date('d-M-Y', strtotime($c_date)),
							'in_time'         => $in_time,
							'out_time'        => $out_time,
							'log_status'      => $log_status,
						);

					}

					if($offset !='' && $limit !='') {
						$offset = $offset + $limit;
						$limit  = $limit;
					} 
					else {
						$offset = $limit;
						$limit  = 10;
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['total_record'] = $totalc;
			        $response['offset']       = (int)$offset;
		    		$response['limit']        = (int)$limit;
			        $response['data']         = $attendance_list;
		    		echo json_encode($response);
			        return;
				}
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Not Found"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}

			else if($method == '_attendanceDetails')
			{
				if(!empty($attendance_id))
				{
					$where = array(
						'id'        => $attendance_id,
						'published' => '1',
					);

					$attendance_data = $this->attendance_model->getAttendance($where);

					if(!empty($attendance_data))
					{
						$attendance_list = [];
						foreach ($attendance_data as $key => $value) {
							$attendance_id   = !empty($value->id)?$value->id:'';
							$emp_id          = !empty($value->emp_id)?$value->emp_id:'';
							$emp_name        = !empty($value->emp_name)?$value->emp_name:'';
							$emp_type        = !empty($value->emp_type)?$value->emp_type:'';
							$store_id        = !empty($value->store_id)?$value->store_id:'';
							$store_name      = !empty($value->store_name)?$value->store_name:'';
							$attendance_type = !empty($value->attendance_type)?$value->attendance_type:'';
							$reason          = !empty($value->reason)?$value->reason:'';
							$c_date          = !empty($value->c_date)?$value->c_date:'';
							$in_time         = !empty($value->in_time)?$value->in_time:'';
							$out_time        = !empty($value->out_time)?$value->out_time:'';
							$log_status      = !empty($value->log_status)?$value->log_status:'';
							$published       = !empty($value->published)?$value->published:'';
							$status          = !empty($value->status)?$value->status:'';
							$createdate      = !empty($value->createdate)?$value->createdate:'';
							$updatedate      = !empty($value->updatedate)?$value->updatedate:'';

							$attendance_list = array(
								'attendance_id'   => $attendance_id,
								'emp_id'          => $emp_id,
								'emp_name'        => $emp_name,
								'emp_type'        => $emp_type,
								'store_id'        => $store_id,
								'store_name'      => $store_name,
								'attendance_type' => $attendance_type,
								'reason'          => $reason,
								'c_date'          => $c_date,
								'in_time'         => $in_time,
								'out_time'        => $out_time,
								'log_status'      => $log_status,
								'published'       => $published,
								'status'          => $status,
								'createdate'      => $createdate,
								'updatedate'      => $updatedate,
							);
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $attendance_list;
				        echo json_encode($response);
				        return;
					}
					else
					{
						$response['status']  = 0;
				        $response['message'] = "Not Found"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
					}
				}
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Not Found"; 
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

		// Outlet Histroy
		// ***************************************************
		public function outlet_history($param1="",$param2="",$param3="")
		{		
			$date_type  = $this->input->post('date_type');
			$dis_id     = $this->input->post('distributor_id');
			$outlet_id  = $this->input->post('outlet_id');
			$method     = $this->input->post('method');

			if($method == '_outletHistory')
			{
				if(!empty($outlet_id))
				{
					// Master Details
					$limit  = 1;
					$offset = 0;

					$option['order_by']   = 'id';
					$option['disp_order'] = 'DESC';

					// Attendace Details
					$where_1 = array(
						'store_id'  => $outlet_id,
						'status'    => '1',
						'published' => '1',
					);

					$column_1 = 'id, emp_name, attendance_type, c_date, in_time';

					$attendance_list = $this->attendance_model->getAttendance($where_1, $limit, $offset, 'result', '', '', $option, '', $column_1);

					$attendance_data = [];
					if($attendance_list)
					{
						foreach ($attendance_list as $key => $val_1) {
							$attendance_id   = !empty($val_1->id)?$val_1->id:'';
							$emp_name        = !empty($val_1->emp_name)?$val_1->emp_name:'';
							$attendance_type = !empty($val_1->attendance_type)?$val_1->attendance_type:'3';
							$c_date          = !empty($val_1->c_date)?$val_1->c_date:'';
							$in_time         = !empty($val_1->in_time)?$val_1->in_time:'';

							if($attendance_type == 1)
							{
								$type_view = 'Sales Order';
							}
							else if($attendance_type == 2)
							{
								$type_view = 'No Order';
							}
							else
							{
								$type_view = 'Pending';
							}

							$attendance_data[] = array(
								'attendance_id'   => $attendance_id,
								'employee_name'   => $emp_name,
								'attendance_type' => $type_view,
								'attendance_date' => date('d-M-Y', strtotime($c_date)),
								'attendance_time' => date('h:i:s', strtotime($in_time)),
							);
						}
					}

					// Last Order Details
					$where_2 = array(
						'store_id'  => $outlet_id,
						'status'    => '1',
						'published' => '1',
					);

					$column_2 = 'id, order_no, emp_name, order_status, _ordered, random_value';

					$order_list = $this->order_model->getOrder($where_2, $limit, $offset, 'result', '', '', $option, '', $column_2);

					$order_data = [];
					if($order_list)
					{
						foreach ($order_list as $key => $val_2) {
							$order_id     = !empty($val_2->id)?$val_2->id:'';
							$order_no     = !empty($val_2->order_no)?$val_2->order_no:'';
							$emp_name     = !empty($val_2->emp_name)?$val_2->emp_name:'Admin';
							$order_status = !empty($val_2->order_status)?$val_2->order_status:'';
							$_ordered     = !empty($val_2->_ordered)?$val_2->_ordered:'';
							$random_value = !empty($val_2->random_value)?$val_2->random_value:'';

							$order_data[] = array(
								'order_id'     => $order_id,
								'order_no'     => $order_no,
								'emp_name'     => $emp_name,
								'order_status' => $order_status,
								'_ordered'     => date('d-M-Y', strtotime($_ordered)),
								'random_value' => $random_value,
							);
						}
					}

					// Payment Details
					$where_3 = array(
						'outlet_id'  => $outlet_id,	
						'value_type' => '2',
						'published'  => '1',
					);

					$column_3 = 'id, distributor_id, bill_code, bill_no, amount, discount, amt_type, collection_type, date';

					$payment_list = $this->payment_model->getOutletPayment($where_3, $limit, $offset, 'result', '', '', $option, '', $column_3);

					$payment_data = [];
					if($payment_list)
					{
						foreach ($payment_list as $key => $val_3) {
							$payment_id      = !empty($val_3->id)?$val_3->id:'';
							$distributor_id  = !empty($val_3->distributor_id)?$val_3->distributor_id:'';
							$bill_code       = !empty($val_3->bill_code)?$val_3->bill_code:'';
							$bill_no         = !empty($val_3->bill_no)?$val_3->bill_no:'';
							$amount          = !empty($val_3->amount)?$val_3->amount:'0';
							$discount        = !empty($val_3->discount)?$val_3->discount:'0';
							$amt_type        = !empty($val_3->amt_type)?$val_3->amt_type:'';
							$collection_type = !empty($val_3->collection_type)?$val_3->collection_type:'';
							$date            = !empty($val_3->date)?$val_3->date:'';

							// Amount Type
							if($amt_type == 1)
							{
								$amount_view = 'Cash';
							}
							else if($amt_type == 2)
							{
								$amount_view = 'Cheque';
							}
							else
							{
								$amount_view = 'Others';
							}

							// Collection Type
							if($collection_type == 1)
							{
								$collection_view = 'Pending';
							}
							else
							{
								$collection_view = 'Collected';
							}

							// Distributor Details
							$where_4  = array('id'  => $distributor_id);
							$column_4 = 'company_name';

							$distri_data = $this->distributors_model->getDistributors($where_4, '', '', 'result', '', '', '', '', $column_4);

							$distri_name = !empty($distri_data[0]->company_name)?$distri_data[0]->company_name:'';

							$payment_data[] = array(
								'payment_id'       => $payment_id,
								'distributor_name' => $distri_name,
								'bill_code'        => $bill_code,
								'bill_no'          => $bill_no,
								'amount'           => $amount,
								'discount'         => $discount,
								'amt_type'         => $amount_view,
								'collection_type'  => $collection_view,
								'date'             => date('d-M-Y', strtotime($date)),
							);
						}
					}

					// Invoice Details
					$where_4 = array(
						'store_id'      => $outlet_id,	
						'cancel_status' => '1',
						'published'     => '1',
					);

					$column_4 = 'invoice_no, distributor_id, delivery_employee, date, delivery_date';

					$invoice_list = $this->invoice_model->getInvoice($where_4, $limit, $offset, 'result', '', '', $option, '', $column_4);

					$invoice_data = [];
					if($invoice_list)
					{
						foreach ($invoice_list as $key => $val_4) {
							$inv_no   = !empty($val_4->invoice_no)?$val_4->invoice_no:'';
				            $dis_id   = !empty($val_4->distributor_id)?$val_4->distributor_id:'';
				            $del_id   = !empty($val_4->delivery_employee)?$val_4->delivery_employee:'';
				            $inv_date = !empty($val_4->date)?date('d-M-Y', strtotime($val_4->date)):'';
				            $del_date = !empty($val_4->delivery_date)?date('d-M-Y', strtotime($val_4->delivery_date)):'';

				            // Distributor Details
				            $whr_5 = array('id' => $dis_id);
				            $col_5 = 'company_name';
				            $res_5 = $this->distributors_model->getDistributors($whr_5, '', '', 'result', '', '', '', '', $col_5);

				            $dis_name = !empty($res_5[0]->company_name)?$res_5[0]->company_name:'';

				            // Employee Details
				            $whr_6 = array('id' => $del_id);
				            $col_6 = 'first_name,last_name';
				            $res_6 = $this->employee_model->getEmployee($whr_6, '', '', 'result', '', '', '', '', $col_6);

				            $first_name = !empty($res_6[0]->first_name)?$res_6[0]->first_name:'';
							$last_name = !empty($res_6[0]->last_name)?$res_6[0]->last_name:'';
				            
							$arr = array($first_name,$last_name);
							$del_emp =join(" ",$arr);
							$invoice_data[] = array(
				            	'invoice_no'        => $inv_no,
					            'distributor_name'  => $dis_name,
					            'delivery_employee' => $del_emp,
					            'invoice_date'      => $inv_date,
					            'delivery_date'     => $del_date,
				            );
						}
					}

					$outlet_data = array(
						'attendance_data' => $attendance_data,
						'order_data'      => $order_data,
						'payment_data'    => $payment_data,
						'invoice_data'    => $invoice_data,
					);

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $outlet_data;
			        echo json_encode($response);
			        return;
				}
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}

			else if($method == '_dateList')
			{
				$date_list = array(
					array('date_id'  => strval('1'), 'date_val' => 'Last 15 Days'),
					array('date_id'  => strval('2'), 'date_val' => 'Last 30 Days'),
					array('date_id'  => strval('3'), 'date_val' => 'Last 45 Days'),
					array('date_id'  => strval('4'), 'date_val' => 'Last 60 Days'),
					array('date_id'  => strval('5'), 'date_val' => 'Last 90 Days'),
				);

				$response['status']  = 1;
		        $response['message'] = "Success"; 
		        $response['data']    = $date_list;
		        echo json_encode($response);
		        return;
			}

			else if($method == '_outletHistoryNew')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('date_type', 'distributor_id', 'outlet_id');
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
			    	$start_date = date('Y-m-d', strtotime('-'.$date_type.' days', strtotime(date('Y-m-d'))));
	            	$end_date   = date('Y-m-d');
			    	
		    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
			    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

		    		$whr = array(
		    			'A.distributor_id' => $dis_id,
		    			'A.store_id'       => $outlet_id,
						'A.createdate >='  => $start_value,
						'A.createdate <='  => $end_value,
						'A.published'      => '1',
					);

					$col = 'A.order_no, A.store_name, E.name AS outlet_category, ROUND(B.order_qty * B.price, 2) as order_value, CONCAT(C.first_name, C.last_name) AS employee_name, DATE_FORMAT(A.createdate, "%d-%M-%Y") AS order_date, A.order_status, A.random_value';
					$qry = $this->order_model->getOrderMerge($whr, '', '', 'result', '', '', '', '', $col);


					if($qry)
					{
						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $qry;
				        echo json_encode($response);
				        return; 
					}
					else
					{
						$response['status']  = 0;
				        $response['message'] = "Data not found"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
					}
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

		// Attendance upload
		// ***************************************************
		public function upload_images($param1="",$param2="",$param3="")
		{
			$employee_id   = $this->input->post('employee_id');
			$attendance_id = $this->input->post('attendance_id');
			$outlet_id     = $this->input->post('outlet_id');
			$method        = $this->input->post('method');

			if($method == '_attendanceUpload')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('employee_id', 'attendance_id', 'outlet_id');
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
			    	if(!empty($_FILES))
					{
						$ImageCount = count($_FILES['images']['name']);

						if($ImageCount > 0)
						{
							// Attendance details
					    	$att_col = 'emp_name, store_name, DATE_FORMAT(c_date, "%d-%M-%Y") as c_date';
					    	$att_whr = array('id' => $attendance_id, 'published' => '1', 'status' => '1');
							$att_res = $this->attendance_model->getAttendance($att_whr, '', '', 'row', '', '', '', '', $att_col);

							if($att_res)
							{
								$file_data = array();
								for($i = 0; $i < $ImageCount; $i++)
								{
									$img_name  = $_FILES['images']['name'][$i];
									$img_val   = explode('.', $img_name);
									$img_res   = end($img_val);
									$file_name = generateRandomString(13).'.'.$img_res;

									$fileName   = basename($_FILES["images"]["name"][$i]); 
							 		$UploadPath = 'upload/files/'. $file_name; 
							 		$fileType   = pathinfo($UploadPath, PATHINFO_EXTENSION); 

									$allowTypes = array('jpg','png','jpeg','gif'); 
		 							if(in_array($fileType, $allowTypes))
		 							{
		 								$imageTemp   = $_FILES["images"]["tmp_name"][$i]; 
		 								$imageSize   = convert_filesize($_FILES["images"]["size"][$i]); 
		 								$imgCompress = compressImage($imageTemp, $UploadPath, 75); 

		 								if($imgCompress)
		 								{
		 									$compressedImageSize = filesize($imgCompress); 
		 									$compressedImageSize = convert_filesize($compressedImageSize);

		 									$this->zip->read_file(FCPATH.'/upload/files/'.$file_name);

		 									$file_data[] = array(
												'n_attendance'    => $attendance_id,
												'n_employee'      => $employee_id,
												'n_outlet'        => $outlet_id,
							    				'c_store_img'     => $file_name,
							    				'dt_created'      => date('Y-m-d H:i:s'),
						    				);
		 								}
		 							}
								}

								$ins_list = $this->attendance_model->file_insert_batch($file_data);

								if($ins_list)
							    {

							    	// Create zip file
							    	$file_name = generateRandomString(48).'.zip';
							    	$this->zip->archive('upload/files/zip_files/'.$file_name);

							    	$download_link = FILE_URL.'files/zip_files/'.$file_name;

									$emp_name   = empty_check($att_res->emp_name);
									$store_name = empty_check($att_res->store_name);
									$c_date     = empty_check($att_res->c_date);

							    	$buffer = "
			                            <html>
										    <head>
										        <title>Bdel Wellness Pvt. Ltd.</title>
										    </head>
										    <body>
										        <span>Dear Sir / Madam,</span>
										        <br>
										        <p style='margin-left: 30px;'> Here I have attached a picture of the outlet I visited today and how they displayed our product. So please find the below details. Let me know. </p>

										        <span>Visited details:</span><br>
										        <span style='margin-left: 30px;'><b>BDE Name :</b> ".$emp_name."</span><br>
										        <span style='margin-left: 30px;'><b>Outlet Name :</b> ".$store_name."</span><br>
										        <span style='margin-left: 30px;'><b>Visited Date :</b> ".$c_date."</span><br>
										        <span style='margin-left: 30px;'><b>File Link :</b> <a href=".$download_link.">Click here</a></span><br><br>

										        <span>Regards,</span><br>
										        <span style='margin-left: 30px;'>".SITE_NAME."</span><br>
										        <span style='margin-left: 30px;'>".SITE_ADDRESS."</span><br>
										        <span style='margin-left: 30px;'>Call :".SITE_CONTACT."</span><br>
										    </body>
										</html>
									"; 

			                        $data_1 = array(
										'to'       => 'abdulvahid@datasense.in',
										'subject'  => 'Reg : Share upload files',
										'sitename' => SITE_NAME,
										'site'     => SITE_LINK,
										'message'  => $buffer,
									);
			                        
			                        $sendMail = avul_sendmail($data_1);

				        			$response['status']  = 1;
							        $response['message'] = "Success"; 
							        $response['data']    = [];
							        echo json_encode($response);
							        return; 
							    }
							    else
							    {
				        			$response['status']  = 0;
							        $response['message'] = "Not Success"; 
							        $response['data']    = [];
							        echo json_encode($response);
							        return; 
							    }
							}
							else
						    {
			        			$response['status']  = 0;
						        $response['message'] = "Not Success"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
						}
						else
						{
							$response['status']  = 0;
					        $response['message'] = "Please fill all required fields"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
						}
					}
					else
					{
						$response['status']  = 0;
				        $response['message'] = "Please fill all required fields"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
					}
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

		// Stock list
		// ***************************************************
		public function stock_list($param1="",$param2="",$param3="")
		{
			$method      = $this->input->post('method');
			$pdt_type    = $this->input->post('pdt_type');
			$category_id = $this->input->post('category_id');
			$employee_id = $this->input->post('employee_id');
			$outlet_id   = $this->input->post('outlet_id');
			$entry_date  = $this->input->post('entry_date');
			$stock_data  = $this->input->post('stock_data');
			$state_id    = $this->input->post('state_id');
			$city_id     = $this->input->post('city_id');
			$zone_id     = $this->input->post('zone_id');
			$month_id    = $this->input->post('month_id');
			$year_id     = $this->input->post('year_id');
			$search      = $this->input->post('search');

			if($method == '_listProductType')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('category_id');
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
			    	$whr_1 = array('A.published' => '1');
					$col_1 = 'A.id, A.description, A.product_type, A.product_unit, B.name AS unit_name';
					$in_1  = array('A.category_id' => $category_id);
					$res_1 = $this->commom_model->getProductTypeJoin($whr_1,'','','result','','','','',$col_1,$in_1);

					if($res_1)
					{
						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $res_1;
				        echo json_encode($response);
				        return;
					}
					else
					{
						$response['status']  = 0;
				        $response['message'] = "Data Not Found"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
					}
			    }
			}

			else if($method == '_listProductTypeNew')
			{
		    	$whr_1 = array('A.published' => '1');
				$like  = array('name' => $search);
				$col_1 = 'A.id, A.description, A.product_type, A.product_unit, B.name AS unit_name';
				$res_1 = $this->commom_model->getProductTypeJoin($whr_1,'','','result',$like,'','','',$col_1);

				if($res_1)
				{
					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $res_1;
			        echo json_encode($response);
			        return;
				}
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Data Not Found"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
				}
			}

			else if($method == '_listMonth')
			{
				$month_list = array(
					array('month_id'  => strval('1'), 'month_val' => 'January'),
					array('month_id'  => strval('2'), 'month_val' => 'February'),
					array('month_id'  => strval('3'), 'month_val' => 'March'),
					array('month_id'  => strval('4'), 'month_val' => 'April'),
					array('month_id'  => strval('5'), 'month_val' => 'May'),
					array('month_id'  => strval('6'), 'month_val' => 'June'),
					array('month_id'  => strval('7'), 'month_val' => 'July'),
					array('month_id'  => strval('8'), 'month_val' => 'August'),
					array('month_id'  => strval('9'), 'month_val' => 'September'),
					array('month_id'  => strval('10'), 'month_val' => 'October'),
					array('month_id'  => strval('11'), 'month_val' => 'November'),
					array('month_id'  => strval('12'), 'month_val' => 'December'),
				);

				$response['status']  = 1;
		        $response['message'] = "Success"; 
		        $response['data']    = $month_list;
		        echo json_encode($response);
		        return;
			}

			else if($method == '_listYear')
			{
				$whr_1 = array('status'=>'1', 'published'=>'1');
				$col_1 = 'id, year_value';
				$res_1 = $this->commom_model->getYear($whr_1,'','','result','','','','',$col_1);

				if($res_1)
				{
					$year_list = [];
					foreach ($res_1 as $key => $val_1) {
			            $year_list[] = array(
	      					'year_id'    => isset($val_1->id)?$val_1->id:'',
				            'year_name'  => isset($val_1->year_value)?$val_1->year_value:'',
	      				);
					}

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $year_list;
		    		echo json_encode($response);
			        return;
				}
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Not Found"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}

			else if($method == '_addOutletStock')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('employee_id', 'outlet_id', 'stock_data');
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
			    	$stock_val = json_decode($stock_data);		

			    	$ins_data = array();

			    	foreach ($stock_val as $key => $val) {

			    		$outlet_id = zero_check($outlet_id);
						$type_id   = zero_check($val->type_id);

						$whr_2   = array('outlet_id' => $outlet_id, 'type_id' => $type_id, 'published' => '1');
						$col_2   = 'closeing_stk';
						$limit   = 1;
						$offset  = 0;
						$option  = array('order_by' => 'id', 'disp_order' => 'DESC');
						$res_2   = $this->attendance_model->getOutletStock($whr_2, $limit, $offset, 'row', '', '', $option, '', $col_2);

						$open_stk  = ($res_2)?zero_check($res_2->closeing_stk):'0';
						$sales_stk = 0;
						$close_stk = 0;

						if($open_stk > 0)
						{
							if($open_stk > zero_check($val->pdt_stock))
							{
								$sales_stk = $open_stk - zero_check($val->pdt_stock);
								$close_stk = $open_stk - $sales_stk;
							}
							else if($open_stk == zero_check($val->pdt_stock))
							{
								$sales_stk = zero_check($val->pdt_stock);
								$close_stk = 0;
							}
							else if($open_stk < zero_check($val->pdt_stock))
							{
								$sales_stk = 0;
								$close_stk = zero_check($val->pdt_stock);
							}

						}

					    $ins_data[] = array(
					    	'employee_id'  => zero_check($employee_id),
					    	'outlet_id'    => zero_check($outlet_id),
					    	'type_id'      => zero_check($val->type_id),
						    'pdt_type'     => zero_check($val->pdt_type),
						    'pdt_unit'     => zero_check($val->pdt_unit),
						    'pdt_stock'    => zero_check($val->pdt_stock),
						    'opening_stk'  => $open_stk,
							'pur_val'      => 0,
							'entry_val'    => zero_check($val->pdt_stock),
							'sales_val'    => zero_check($sales_stk),
							'closeing_stk' => zero_check($close_stk),
						    'entry_type'   => 2, // Balance stock entry
						    'entry_date'   => date('Y-m-d'),
						    'createdate'   => date('Y-m-d H:i:s'),
					    );
			    	}

			    	$insert = $this->attendance_model->outletStock_insertBatch($ins_data);

			    	if($insert)
				    {
	        			$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
				    else
				    {
	        			$response['status']  = 0;
				        $response['message'] = "Not Success"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
			    }
			}

			else if($method == '_outletStockReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('category_id', 'month_id', 'year_id', 'outlet_id');
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

		public function emp_checkpoint($param1="",$param2="",$param3="")
		{
			$method   = $this->input->post('method');
			$emp_id   = $this->input->post('emp_id');
			$att_code = $this->input->post('att_code');
			$cur_date = date('Y-m-d');

			if($method == '_empCheckpoint')
			{
				// Meeting details
				$whr_1 = array('employee_id'=>$emp_id, 'assign_date'=>$cur_date, 'published'=>1);
				$col_1 = 'distributor_id';
				$qry_1 = $this->assignshop_model->getAssignshopDetails($whr_1, '', '', 'row', '', '', '', '', $col_1);
				if(!empty($qry_1))
				{
					// Check check in details
					$whr_2 = array('emp_id'=>$emp_id, 'c_date'=>$cur_date, 'published'=>1);
					$col_2 = 'id, log_status';
					$qry_2 = $this->attendance_model->getCheckPoint($whr_2, '', '', 'row', '', '', '', '', $col_2);

					if($qry_2)
					{
						if($qry_2->log_status == 1)
						{
							// Update
							$upt_val = array(
								'out_time'   => date('H:i:s'),
								'log_status' => 2,
								'updatedate' => date('Y-m-d H:i:s'),
							);

							$upt_id  = array('id' => $qry_2->id);
							$upt_res = $this->attendance_model->checkPoint_update($upt_val, $upt_id);

							if($upt_res)
						    {
			        			$response['status']  = 1;
						        $response['message'] = "Success"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
						    else
						    {
			        			$response['status']  = 0;
						        $response['message'] = "Not Success"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
						}
						else
						{
							$response['status']  = 0;
					        $response['message'] = "Attendance Already Exist"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
						}
					}
					else
					{
						// Insert 
						$ins_val = array(
							'dis_id'       => $qry_1->distributor_id,
							'emp_id'       => $emp_id,
							'c_attendance' => $att_code,
							'c_date'       => date('Y-m-d'),
							'in_time'      => date('H:i:s'),
							'createdate'   => date('Y-m-d H:i:s'),
						);

						if($att_code == 'L')
						{
							$ins_val['out_time']   = date('H:i:s');
							$ins_val['log_status'] = 2;
						}

						$ins_res = $this->attendance_model->checkPoint_insert($ins_val);

						if($ins_res)
					    {
		        			$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
					    }
					    else
					    {
		        			$response['status']  = 0;
					        $response['message'] = "Not Success"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
					    }
					}
				}
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Data not found"; 
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
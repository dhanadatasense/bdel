<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class Login extends CI_Controller {

		public function __construct()
		{
			parent::__construct();

			$this->load->model('login_model');
			$this->load->model('commom_model');
			$this->load->model('assignshop_model');
			$this->load->model('outlets_model');
			$this->load->model('attendance_model');
			$this->load->model('target_model');
			$this->load->model('order_model');
			$this->load->model('payment_model');
			$this->load->model('distributors_model');
			$this->load->model('invoice_model');
			$this->load->model('employee_model');
			$this->load->model('assigninvoice_model');
			$this->load->model('invoice_model');
			$this->load->model('user_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Admin Login
		// ***************************************************
		public function admin_login($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_adminLogin')
			{
				$username = $this->input->post('username');
				$password = $this->input->post('password');

				$error    = FALSE;
			    $errors   = array();
				$required = array('username', 'password');

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
			        $response['message'] = "Please fill required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }
			    if(count($errors)==0)
			    {
			    	$where = array(
			        	'email'     => $username,
			        	'password'  => $password,
			        	'status'    => '1',
			        	'published' => '1',
			        );

			        $admin_data  = $this->login_model->getLoginStatus($where);
			        $db_username = !empty($admin_data[0]->email)?$admin_data[0]->email:'';
			        if(!empty($admin_data))
			        {

			        	if(strcmp($db_username, $username) == 0)
				        {
				        	$user_role = empty_check($admin_data[0]->user_role);

				        	$role_list = '';
				        	if($user_role)
				        	{
				        		$whr_1 = array('id' => $user_role);
				        		$col_1 = 'role_list';
				        		$res_1 = $this->user_model->getUserRole($whr_1, '', '', 'row', '', '', '', '', $col_1);

				        		$role_list = empty_check($res_1->role_list);
				        	}

				        	// Insert year list
				        	$current_year = date('Y');

				        	$year_whr  = array(
				        		'year_value' => $current_year,
				        		'status'     => '1',
				        		'published'  => '1',
				        	);

				        	$year_col  = 'id';

				        	$year_data = $this->commom_model->getYear($year_whr, '', '', 'result', '', '', '', '', $year_col);

				        	if(empty($year_data))
				        	{
				        		$year_val = array(
				        			'year_value' => $current_year,
				        			'createdate' => date('Y-m-d H:i:s'),
				        		);

				        		$year_ins = $this->commom_model->year_insert($year_val);
				        	}

				        	$login_data = array(
				        		'id'         => empty_check($admin_data[0]->id),
					            'username'   => empty_check($admin_data[0]->username),
					            'mobile'     => empty_check($admin_data[0]->mobile),
					            'email'      => empty_check($admin_data[0]->email),
					            'address'    => empty_check($admin_data[0]->address),
					            'pincode'    => empty_check($admin_data[0]->pincode),
					            'state_id'   => empty_check($admin_data[0]->state_id),
					            'city_id'    => empty_check($admin_data[0]->city_id),
					            'gst_no'     => empty_check($admin_data[0]->gst_no),
					            'password'   => empty_check($admin_data[0]->password),
					            'user_role'  => empty_check($admin_data[0]->user_role),
					            'permission' => empty_check($admin_data[0]->permission),
					          	'role_list'  => $role_list,
				        	);
							
							$data      = array('login_status'  => '2');
							$update_id = array('id' => empty_check($admin_data[0]->id));
							$update    = $this->employee_model->employee_update($data, $update_id);

				        	$response['status']  = TRUE;
					        $response['message'] = "Login Successfully"; 
					        $response['data']    = $login_data;
					        echo json_encode($response);
					        return; 
				        }
				        else
				        {
				        	$response['status']  = FALSE;
					        $response['message'] = "Invalid username or password"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
				        }
			        }
			        else
			        { 
			        	$column = 'id, vendor_no, bill_no, company_name, gst_no, contact_no, email, state_id, city_id, address, vendor_type, distributor_id, permission';
		        		$vendor_data = $this->login_model->getVendors($where, '', '', 'result', '', '', '', '', $column);

			        	$vendors_det = !empty($vendor_data[0]->email)?$vendor_data[0]->email:'';

			        	if(!empty($vendor_data))
			        	{	
			        		$login_data = array(
			        			'id'             => empty_check($vendor_data[0]->id),
					            'vendor_no'      => empty_check($vendor_data[0]->vendor_no),
					            'bill_no'        => empty_check($vendor_data[0]->bill_no),
					            'company_name'   => empty_check($vendor_data[0]->company_name),
					            'gst_no'         => empty_check($vendor_data[0]->gst_no),
					            'contact_no'     => empty_check($vendor_data[0]->contact_no),
					            'email'          => empty_check($vendor_data[0]->email),
					            'state_id'       => empty_check($vendor_data[0]->state_id),
					            'city_id'        => empty_check($vendor_data[0]->city_id),
					            'address'        => empty_check($vendor_data[0]->address),
					            'vendor_type'    => empty_check($vendor_data[0]->vendor_type),
					            'distributor_id' => empty_check($vendor_data[0]->distributor_id),
					            'permission'     => empty_check($vendor_data[0]->permission),
			        		);

			        		if(strcmp($vendors_det, $username) == 0)
			        		{
			        			$response['status']  = TRUE;
						        $response['message'] = "Login Successfully"; 
						        $response['data']    = $login_data;
						        echo json_encode($response);
						        return; 
			        		}
			        		else
			        		{
			        			$response['status']  = FALSE;
						        $response['message'] = "Invalid username or password"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
			        		}
			        	}
			        	else
			        	{
			        		$column = 'id, company_name, mobile, email, state_id, city_id, zone_id, gst_no, address, pincode, permission, vendor_id, distributor_type, distributor_status';
			        		$dis_data = $this->login_model->getDistributors($where, '', '', 'result', '', '', '', '', $column);
				        	$distributor_det = !empty($dis_data[0]->email)?$dis_data[0]->email:'';

				        	if(!empty($dis_data))
				        	{
				        		$login_data = array(
				        			'id'                 => empty_check($dis_data[0]->id),
						            'company_name'       => empty_check($dis_data[0]->company_name),
						            'mobile'             => empty_check($dis_data[0]->mobile),
						            'email'              => empty_check($dis_data[0]->email),
						            'state_id'           => empty_check($dis_data[0]->state_id),
						            'city_id'            => empty_check($dis_data[0]->city_id),
						            'zone_id'            => empty_check($dis_data[0]->zone_id),
						            'gst_no'             => empty_check($dis_data[0]->gst_no),
						            'address'            => empty_check($dis_data[0]->address),
						            'pincode'            => empty_check($dis_data[0]->pincode),
						            'permission'         => empty_check($dis_data[0]->permission),
						            'vendor_id'          => empty_check($dis_data[0]->vendor_id),
						            'distributor_type'   => empty_check($dis_data[0]->distributor_type),
						            'distributor_status' => empty_check($dis_data[0]->distributor_status),
				        		);

				        		if(strcmp($distributor_det, $username) == 0)
				        		{
				        			$response['status']  = TRUE;
							        $response['message'] = "Login Successfully"; 
							        $response['data']    = $login_data;
							        echo json_encode($response);
							        return; 
				        		}
				        		else
				        		{
				        			$response['status']  = FALSE;
							        $response['message'] = "Invalid username or password"; 
							        $response['data']    = [];
							        echo json_encode($response);
							        return; 
				        		}
				        	}
							else{
								
								$column = 'id, mobile, email, pincode, permission, position_id,designation_code';
								$mang_data = $this->login_model->getEmployee($where, '', '', 'result', '', '', '', '', $column);
								
								$manager_det = !empty($mang_data[0]->email)?$mang_data[0]->email:'';
								$hierarchy_det = !empty($mang_data[0]->permission)?$mang_data[0]->permission:'';
								$db_number     = !empty($mang_data[0]->id)?$mang_data[0]->id:'';
								if($hierarchy_det == 5||$hierarchy_det == 2)
								{
									$login_data = array(
										'id'                 => empty_check($mang_data[0]->id),
										'mobile'             => empty_check($mang_data[0]->mobile),
										'email'              => empty_check($mang_data[0]->email),
										'pincode'            => empty_check($mang_data[0]->pincode),
										'permission'         => empty_check($mang_data[0]->permission),
										'position_id'        => empty_check($mang_data[0]->position_id),
										'designation_code'   => empty_check($mang_data[0]->designation_code),
									);
	
									if(strcmp($manager_det, $username) == 0)
									{   
										$data      = array('login_status'  => '2');
										$update_id = array('id' => $db_number);
										$update    = $this->employee_model->employee_update($data, $update_id);

										$response['status']  = TRUE;
										$response['message'] = "Login Successfully"; 
										$response['data']    = $login_data;
										echo json_encode($response);
										return; 
									}
									else
									{
										$response['status']  = FALSE;
										$response['message'] = "Invalid username or password"; 
										$response['data']    = [];
										echo json_encode($response);
										return; 
									}
									
							    }
				        	    else
				    			{
								
				        			$response['status']  = FALSE;
						        	$response['message'] = "Invalid username or password"; 
						        	$response['data']    = [];
						        	echo json_encode($response);
						        	return; 
				    			}
							}	
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
		}
		// Employee Login && Mobile APP
		// ***************************************************
		public function employee_login($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_employeeLogin')
			{
				$username = $this->input->post('username');
				$password = $this->input->post('password');

				$error    = FALSE;
			    $errors   = array();
				$required = array('username', 'password');

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
			        $response['message'] = "Please fill required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }
			    if(count($errors)==0)
			    {
			    	$where = array(
			        	'mobile'       => $username,
			        	'password'     => $password,
			        	// 'login_status' => '1',
			        	'status'       => '1',
			        	'published'    => '1',
			        );

			        $page_data   = $this->login_model->getEmployee($where);
			        $db_username = !empty($page_data[0]->mobile)?$page_data[0]->mobile:'';
			        $db_number   = !empty($page_data[0]->id)?$page_data[0]->id:'';

			        if(strcmp($db_username, $username) == 0)
			        {
			        	$data      = array('login_status'  => '2');
			    		$update_id = array('id' => $db_number);
					    $update    = $this->employee_model->employee_update($data, $update_id);

			        	if($page_data)
				        {
				        	$response['status']  = TRUE;
					        $response['message'] = "Login Successfully"; 
					        $response['data']    = $page_data;
					        echo json_encode($response);
					        return; 
				        }
				        else
				        {
				        	$response['status']  = FALSE;
					        $response['message'] = "Invalid username or password"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
				        }
			        }
			        else
			        {
			        	$response['status']  = FALSE;
				        $response['message'] = "Invalid username or password"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
			        }
			    }
			}	

			else if($method == '_employeeDashboard')
			{
				$employee_id = $this->input->post('employee_id');
				$cur_date    = date('Y-m-d');
				$next_date   = date('Y-m-d', strtotime($cur_date.'+1 Days'));
				$cur_month   = date('m'); 
				$cur_year    = date('Y'); 

				if($employee_id)
				{
					$where_1  = array(
						'id'     => $employee_id,
						'status' => '1',
					);

					$column_1 = 'id, log_type, company_id';
					$data_1   = $this->login_model->getEmployee($where_1, '', '', 'result', '', '', '', '', $column_1);

					if($data_1)
					{
						$result_1 = $data_1[0];

						$employee_id = !empty($result_1->id)?$result_1->id:'';
	    				$log_type    = !empty($result_1->log_type)?$result_1->log_type:'';
	    				$company_id  = !empty($result_1->company_id)?$result_1->company_id:'';

	    				$dashboard_value = [];
	    				if($log_type == 1)
	    				{
	    					$where_2 = array(
								'employee_id' => $employee_id,
								'assign_date' => $cur_date,
								'published'   => '1',
								'status'      => '1',
							);

							$column_2 = 'id, distributor_id, assign_invoice';
							$data_2   = $this->assigninvoice_model->getAssignInvoiceDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

							$total_invoice = 0;
							$pending_count = 0;
							$present_count = 0;

							if($data_2)
							{
								foreach ($data_2 as $key => $val_2) {
									$auto_id      = !empty($val_2->id)?$val_2->id:'';
						            $assign_inv   = !empty($val_2->assign_invoice)?$val_2->assign_invoice:'';
						            $distribut_id = !empty($val_2->distributor_id)?$val_2->distributor_id:'';

						            if($assign_inv)
						            {
						            	$where_3 = array(
						            		'distributor_id'  => $distribut_id,
						            		'zone_id'         => $assign_inv,
						            		'delivery_status' => '1',
						            		'invoice_status'  => '1',
						            		'cancel_status'   => '1',
						            		'published'       => '1',
						            	);

						            	$column_3 = 'id';
						            	$result_3 = $this->invoice_model->getInvoiceImplode($where_3, '', '', 'result', '', '', '', '', $column_3);

						            	if($result_3)
						            	{
						            		$inv_val = '';
						            		foreach ($result_3 as $key => $val_3) {

						            			$inv_id   = !empty($val_3->id)?$val_3->id:'';
						            			$inv_val .= $inv_id.',';
						            		}

						            		$inv_res = substr($inv_val, 0, -1);

						            		$where_4 = array(
												'distributor_id'  => $distribut_id,
												'invoice_id'      => $inv_res,
												'delivery_status' => '1',
												'invoice_status'  => '1',
							            		'cancel_status'   => '1',
							            		'published'       => '1',
											);

							            	$column = 'id';
											$inv_total = $this->invoice_model->getInvoiceImplode($where_4, '', '', 'result', '', '', '', '', $column);

											if($inv_total)
											{
												$pending_count = count($inv_total);
											}
											else
											{
												$pending_count = 0;
											}

											// Complete Invoice
											$where_5 = array(
												'delivery_employee' => $employee_id,
												'delivery_date'     => $cur_date,
												'delivery_status'   => '2',
												'published'         => '1',
												'status'            => '1',
											);

											$data_5  = $this->invoice_model->getInvoice($where_5,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

											$present_count = !empty($data_5[0]->autoid)?$data_5[0]->autoid:'0';

											$total_invoice  = $pending_count + $present_count;
						            	}
						            }
								}
							}

							$dashboard_value[] = array(
	    						'total_invoice'   => strval($total_invoice),
								'visit_invoice'   => strval($present_count),
								'pending_invoice' => strval($pending_count),
								'total_outlet'    => '0',
								'visit_outlet'    => '0',
								'pending_outlet'  => '0',
								'target_value'    => '0',
								'achievement'     => '0',
								'order_count'     => '0',
								'order_total'     => '0',
							);
	    				}

						else if($log_type == 2)
						{
							$where_2 = array(
								'employee_id' => $employee_id,
								'assign_date' => $cur_date,
								'published'   => '1',
								'status'      => '1',
							);

							$column_2 = 'assign_store';
							$data_2   = $this->assignshop_model->getAssignshopDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

							$assign_store = !empty($data_2[0]->assign_store)?$data_2[0]->assign_store:'';

							$outlet_count  = 0;
							$present_count = 0;
							$absent_count  = 0;
							$target_val    = 0;
							$achieve_val   = 0;
							$order_count   = 0;
							$total_value   = 0;

							if($assign_store)
							{
								$where_3 = array(
									'zone_id'   => $assign_store,
									'published' => '1',
									'status'    => '1',
								);

								$data_3  = $this->outlets_model->getOutletsList($where_3,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

								$outlet_count = !empty($data_3[0]->autoid)?$data_3[0]->autoid:'0';

								$where_4 = array(
									'emp_id'    => $employee_id,
									'c_date'    => $cur_date,
									'published' => '1',
								);

								$groupby = 'store_id';

								$data_4  = $this->attendance_model->getAttendance($where_4,'','',"result",'','','',TRUE,'COUNT(id) AS autoid', $groupby);

								if(!empty($data_4))
								{
									$present_count = count($data_4);
								}
								else
								{
									$present_count = 0;
								}

								// $present_count = !empty($data_4[0]->autoid)?$data_4[0]->autoid:'0';
								$absent_count  = $outlet_count - $present_count;

								// Target Details
								$dateObj   = DateTime::createFromFormat('!m', $cur_month);
								$monthName = $dateObj->format('F'); // March

								$where_5 = array(
									'employee_id' => $employee_id,
									'month_name'  => $monthName, 
									'year_name'   => $cur_year, 
									'published'   => '1', 
								);

								$column_5 = 'target_val, achieve_val';
								$data_5   = $this->target_model->getTargetDetails($where_5, '', '', 'result', '', '', '', '', $column_5);

								if(!empty($data_5))
								{
									$result_5 = $data_5[0];

									$target_val  = !empty($result_5->target_val)?$result_5->target_val:'0';
									$achieve_val = !empty($result_5->achieve_val)?$result_5->achieve_val:'0';
								}

								// Order Value
								$where_6 = array(
									'emp_id'    => $employee_id,
									'date'      => $cur_date,
									'published' => '1',
									'status'    => '1',
								);

								$column_6 = 'id';

								$data_6   = $this->order_model->getOrder($where_6, '', '', 'result', '', '', '', '', $column_6);

								$order_count = 0;
								$order_total = 0;
								
								if($data_6)
								{
									$order_val   = '';							
									foreach ($data_6 as $key => $val_6) {
										$ord_id = !empty($val_6->id)?$val_6->id:'';

										$order_val .= $ord_id.',';

										$order_count++;
									}

									$order_res = substr_replace($order_val, "", -1);

									$where_7 = array(
										'order_id'  => $order_res,
										'published' => '1',
										'status'    => '1',
									);

									$column_7 = 'price, order_qty';

									$data_7   = $this->order_model->getOrderListDetails($where_7, '', '', 'result', '', '', '', '', $column_7);

									if($data_7)
									{
										foreach ($data_7 as $key => $val_7) {
											$price        = !empty($val_7->price)?$val_7->price:'';
											$order_qty    = !empty($val_7->order_qty)?$val_7->order_qty:'';
											$price_tot    = $order_qty * $price;
											$order_total += $price_tot;
										}
									}
								}

								$total_value = round($order_total);
							}

							// Meeting details
							$mWhr_1 = array(
								'employee_id' => $employee_id,
								'assign_date' => $cur_date,
								'published'   => '1',
								'status'      => '1',
							);

							$mCol_1 = 'meeting_val';
							$mQry_1 = $this->assignshop_model->getAssignshopDetails($mWhr_1, '', '', 'row', '', '', '', '', $mCol_1);
							$mRes_1 = !empty($mQry_1->meeting_val)?$mQry_1->meeting_val:0;

							$mWhr_2 = array(
								'employee_id' => $employee_id,
								'assign_date' => $next_date,
								'published'   => '1',
								'status'      => '1',
							);

							$mCol_2 = 'meeting_val';
							$mQry_2 = $this->assignshop_model->getAssignshopDetails($mWhr_2, '', '', 'row', '', '', '', '', $mCol_2);
							$mRes_2 = !empty($mQry_2->meeting_val)?$mQry_2->meeting_val:0;

							// Check point status
							$cWhr_3 = array('emp_id' => $employee_id, 'c_date' => $cur_date, 'published' => 1);
							$cCol_3 = 'c_attendance';
							$cQry_3 = $this->attendance_model->getCheckPoint($cWhr_3,'','','row','','','','',$cCol_3);
							$cRes_3 = !empty($cQry_3->c_attendance)?$cQry_3->c_attendance:NULL;
							$cCnt_3 = !empty($cQry_3->c_attendance)?'1':'0';

							$dashboard_value[] = array(
								'total_invoice'           => '0',
								'visit_invoice'           => '0',
								'pending_invoice'         => '0',
								'total_outlet'            => strval($outlet_count),
								'visit_outlet'            => strval($present_count),
								'pending_outlet'          => strval($absent_count),
								'target_value'            => strval($target_val),
								'achievement'             => strval($achieve_val),
								'order_count'             => strval($order_count),
								'order_total'             => strval($total_value),
								'today_meeting_status'    => strval($mRes_1),
								'tomorrow_meeting_status' => strval($mRes_2),
								'attendance_status'       => strval($cCnt_3),
								'attendance_value'        => strval($cRes_3),
							);
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $dashboard_value;
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
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
				}
			}

			else if($method == '_outletCollectionReport')
			{
				$outlet_id = $this->input->post('outlet_id');
				$limit     = $this->input->post('limit');
	    		$offset    = $this->input->post('offset');

				if($outlet_id)
				{
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

					$where_2 = array(
						'outlet_id'  => $outlet_id,
						'value_type' => '2',
						'published'  => '1',
					);

					$column_2 = 'id';

					$overalldatas = $this->payment_model->getOutletPayment($where_2, '', '', 'result', '', '', '', '', $column_2);

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

					$column_3  = 'distributor_id, payment_id, bill_no, amount, discount, amt_type, date';

					$data_list = $this->payment_model->getOutletPayment($where_2, $limit, $offset, 'result', '', '', $option, '', $column_3);

					if($data_list)
					{
						$payment_list = [];
						foreach ($data_list as $key => $val) 
						{
							$distributor_id  = !empty($val->distributor_id)?$val->distributor_id:'';
							$payment_id      = !empty($val->payment_id)?$val->payment_id:'';
				            $bill_no         = !empty($val->bill_no)?$val->bill_no:'';
				            $amount          = !empty($val->amount)?$val->amount:'0';
				            $discount        = !empty($val->discount)?$val->discount:'0';
				            $amt_type        = !empty($val->amt_type)?$val->amt_type:'';
				            $date            = !empty($val->date)?$val->date:'';

				            if($amt_type == 1)
				            {
				            	$amount_type = 'Cash';
				            }
				            else if($amt_type == 2)
				            {
				            	$amount_type = 'Cheque';
				            }
				            else
				            {
				            	$amount_type = 'Others';
				            }

				            // Distributor Details
				            $where_4  = array('id' => $distributor_id);			   
							$column_4 = 'company_name';
							$dis_data = $this->distributors_model->getDistributors($where_4, '', '', 'result', '', '', '', '', $column_4);
							$dis_name = !empty($dis_data[0]->company_name)?$dis_data[0]->company_name:'';

							// Invoice Details
				            $where_5  = array('id' => $payment_id);			   
							$column_5 = 'invoice_no';
							$dis_data = $this->invoice_model->getInvoice($where_5, '', '', 'result', '', '', '', '', $column_5);
							$inv_no   = !empty($dis_data[0]->invoice_no)?$dis_data[0]->invoice_no:'';

				            $payment_list[] = array(
				            	'distributor_id'   => $distributor_id,
				            	'distributor_name' => $dis_name,
					            'bill_no'          => $bill_no,
					            'invoice_no'       => $inv_no,
					            'amount'           => $amount,
					            'discount'         => $discount,
					            'amount_type'      => $amount_type,
					            'payment_date'     => date('d-M-Y', strtotime($date)),
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
				        $response['data']         = $payment_list;
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

			else if($method == '_orderReport')
			{
				$employee_id = $this->input->post('employee_id');
				$cur_date    = date('Y-m-d');
				$cur_month   = date('m'); 
				$cur_year    = date('Y'); 

				$where_1  = array(
					'id'        => $employee_id,
					'log_type'  => '2',
					'status'    => '1',
					'published' => '1',
				);

				$column_1 = 'id, log_type';
				$data_1   = $this->login_model->getEmployee($where_1, '', '', 'result', '', '', '', '', $column_1);

				if($data_1)
				{
					$where_2  = array(
						'emp_id'    => $employee_id,
						'date'      => $cur_date,
						'status'    => '1',
						'published' => '1',
					);

					$column_2   = 'id, bill_type, order_no, store_name, createdate, order_status';

					$order_data = $this->order_model->getOrder($where_2, '', '', 'result', '', '', '', '', $column_2);

					if($order_data)
					{
						$order_list = [];
						foreach ($order_data as $key => $val_1) {
							
							$order_id     = !empty($val_1->id)?$val_1->id:'';
				            $bill_type    = !empty($val_1->bill_type)?$val_1->bill_type:'';
				            $order_no     = !empty($val_1->order_no)?$val_1->order_no:'';
				            $store_name   = !empty($val_1->store_name)?$val_1->store_name:'';
				            $order_status = !empty($val_1->order_status)?$val_1->order_status:'';
				            $createdate   = !empty($val_1->createdate)?$val_1->createdate:'';

				            $where_3 = array(
				            	'order_id'  => $order_id,
								'status'    => '1',
								'published' => '1',
				            );

				            $column_3 = 'id, price, order_qty';

				            $pdt_data = $this->order_model->getOrderDetails($where_3, '', '', 'result', '', '', '', '', $column_3);

				            $order_count = 0;
				            $order_value = 0;
				            if($pdt_data)
				            {
				            	$num = 0;
				            	foreach ($pdt_data as $key => $val_2) {
				            		$price   = !empty($val_2->price)?$val_2->price:'0';
            						$ord_qty = !empty($val_2->order_qty)?$val_2->order_qty:'0';
            						$ord_val = $ord_qty * $price;

            						$order_value += $ord_val;

            						$num++;
				            	}

				            	$order_count = $num;
				            }

				            $order_total = round($order_value);

				            if($order_status == '1')
						    {
						        $order_view = 'Success';
						    }
						    else if($order_status == '2')
						    {
						        $order_view = 'Process';
						    }
						    else if($order_status == '3')
						    {
						        $order_view = 'Packing';
						    }
						    else if($order_status == '4')
						    {
						        $order_view = 'Shipping';
						    }
						    else if($order_status == '5')
						    {
						        $order_view = 'Invoice';
						    }
						    else if($order_status == '6')
						    {
						        $order_view = 'Delivery';
						    }
						    else if($order_status == '7')
						    {
						        $order_view = 'Complete';
						    }
						    else if($order_status == '9')
						    {
						        $order_view = 'Cancel Invoice';
						    }
						    else
						    {
						        $order_view = 'Cancel';
						    }

				            $order_list[] = array(
				            	'order_id'     => $order_id,
					            'bill_type'    => $bill_type,
					            'order_no'     => $order_no,
					            'store_name'   => $store_name,
					            'order_status' => $order_view,
					            'order_count'  => strval($order_count),
					            'order_value'  => number_format((float)$order_total, 2, '.', ''),
				            );
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $order_list;
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
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Error"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}	

			else if($method == '_dayStartReport')
			{
				$employee_id = $this->input->post('employee_id');
				$sel_date    = $this->input->post('sel_date');

				if(!empty($sel_date))
				{
					$cur_date = date('Y-m-d', strtotime($sel_date));
				}
				else
				{
					$cur_date = date('Y-m-d');
				}

				if($employee_id)
				{
					// Employee Details
					$whr_1 = array('id' => $employee_id);
					$col_1 = 'first_name,last_name';
					$res_1 = $this->employee_model->getEmployee($whr_1, '', '', 'result', '', '', '', '', $col_1);

					$emp_name = '';
					if($res_1)
					{
						$first_name = !empty($res_1[0]->first_name)?$res_1[0]->first_name:'';
						$last_name = !empty($res_1[0]->last_name)?$res_1[0]->last_name:'';
						
								$arr = array($first_name,$last_name);
								$emp_name =join(" ",$arr);
					}

					// Today Beat
					$whr_2 = array(
						'employee_id' => $employee_id,
						'assign_date' => $cur_date,
						'published'   => '1',
					);

					$col_2 = 'assign_store';
					$res_2 = $this->assignshop_model->getAssignshopDetails($whr_2, '', '', 'result', '', '', '', '', $col_2);

					$beat_name    = '';
					$outlet_count = '0';
					$new_outlet   = '0';

					$assign_str = !empty($res_2[0]->assign_store)?$res_2[0]->assign_store:'';

					if(!empty($assign_str))
					{
						$exp_beat   = explode(',', $assign_str);
						$beat_count = count($exp_beat);

						for ($i=0; $i < $beat_count; $i++) { 
								
							// Get Beat Details
							$whr_3 = array('id' => $exp_beat[$i]);
							$col_3 = 'name';

							$res_3 = $this->commom_model->getZone($whr_3, '', '', 'result', '', '', '', '', $col_3);

							$zone_name  = !empty($res_3[0]->name)?$res_3[0]->name:'';
							$beat_name .= $zone_name.', ';
						}

						$whr_4 = array(
							'zone_id'   => $assign_str,
							'published' => '1',
							'status'    => '1',
						);

						$res_4  = $this->outlets_model->getOutletsList($whr_4,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

						$outlet_count = !empty($res_4[0]->autoid)?$res_4[0]->autoid:'0';


				    	$whr_5 = array(
							'zone_id'   => $assign_str,
							'date'      => date('Y-m-d'),
							'published' => '1',
							'status'    => '1',
						);

				    	$res_5  = $this->outlets_model->getOutletsList($whr_5,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

						$new_outlet = !empty($res_5[0]->autoid)?$res_5[0]->autoid:'0';
					}

					// Attendance Details
					$limit  = 1;
					$offset = 0;

					$option['order_by']   = 'id';
					$option['disp_order'] = 'ASC';

					$whr_6 = array(
	    				'emp_id'    => $employee_id,
	    				'c_date'    => $cur_date,
	    				'published' => '1'
	    			);

					$col_6 = 'in_time';
	    			$res_6 = $this->attendance_model->getAttendance($whr_6, $limit, $offset, 'result', '', '', $option, '', $col_6);

	    			$in_time = !empty($res_6[0]->in_time)?date('h:i A', strtotime($res_6[0]->in_time)):'';

					$emp_data[] = array(
						'name'         => $emp_name,
						'date'         => date('d-M-Y', strtotime($cur_date)),
						'beat'         => $beat_name,
						'total_outlet' => strval($outlet_count),
						'new_outlet'   => strval($new_outlet),
						'start_time'   => $in_time,
					);

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $emp_data;
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

			else if($method == '_dayEndReport')
			{
				$employee_id = $this->input->post('employee_id');
				$sel_date    = $this->input->post('sel_date');

				if(!empty($sel_date))
				{
					$cur_date = date('Y-m-d', strtotime($sel_date));
				}
				else
				{
					$cur_date = date('Y-m-d');
				}

				if($employee_id)
				{
					// Employee Details
					$whr_1 = array('id' => $employee_id);
					$col_1 = 'first_name,last_name';
					$res_1 = $this->employee_model->getEmployee($whr_1, '', '', 'result', '', '', '', '', $col_1);

					$emp_name = '';
					if($res_1)
					{
						$first_name = !empty($res_1[0]->first_name)?$res_1[0]->first_name:'';
						$last_name = !empty($res_1[0]->last_name)?$res_1[0]->last_name:'';
						$arr = array($first_name,$last_name);
								$emp_name =join(" ",$arr);
					}

					// Today Beat
					$whr_2 = array(
						'employee_id' => $employee_id,
						'assign_date' => $cur_date,
						'published'   => '1',
					);

					$col_2 = 'assign_store';
					$res_2 = $this->assignshop_model->getAssignshopDetails($whr_2, '', '', 'result', '', '', '', '', $col_2);

					$beat_name    = '';
					$outlet_count = '0';
					$new_outlet   = '0';
					$outlet_list  = [];

					$assign_str = !empty($res_2[0]->assign_store)?$res_2[0]->assign_store:'';

					if(!empty($assign_str))
					{
						$exp_beat   = explode(',', $assign_str);
						$beat_count = count($exp_beat);

						for ($i=0; $i < $beat_count; $i++) { 
								
							// Get Beat Details
							$whr_3 = array('id' => $exp_beat[$i]);
							$col_3 = 'name';

							$res_3 = $this->commom_model->getZone($whr_3, '', '', 'result', '', '', '', '', $col_3);

							$zone_name  = !empty($res_3[0]->name)?$res_3[0]->name:'';
							$beat_name .= $zone_name.', ';
						}

						$whr_4 = array(
							'zone_id'   => $assign_str,
							'published' => '1',
							'status'    => '1',
						);

						$res_4  = $this->outlets_model->getOutletsList($whr_4,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

						$outlet_count = !empty($res_4[0]->autoid)?$res_4[0]->autoid:'0';

						// Today Attendance Details
						$whr_5 = array(
							'zone_id'   => $assign_str,
							'published' => '1',
							'status'    => '1',
						);

						$col_5 = 'id, company_name, mobile';
						$res_5 = $this->outlets_model->getOutletsList($whr_5, '', '', 'result', '', '', '', '', $col_5);

						if($res_5)
						{
							foreach ($res_5 as $key => $val_5) {
								$outlet_id    = !empty($val_5->id)?$val_5->id:'';
								$company_name = !empty($val_5->company_name)?$val_5->company_name:'';
								$mobile       = !empty($val_5->mobile)?$val_5->mobile:'';

								// Attendace Details
								$whr_6 = array(
									'emp_id'    => $employee_id,
									'store_id'  => $outlet_id,
									'c_date'    => $cur_date,
									'published' => '1',
								);

								$col_6 = 'id, in_time, out_time, attendance_type, reason';
								$res_6 = $this->attendance_model->getAttendance($whr_6, '', '', 'result', '', '', '', '', $col_6);

								$attendance_list = [];
								if($res_6)
								{
									foreach ($res_6 as $key => $val_6) {

										$att_id   = !empty($val_6->id)?$val_6->id:'';
										$in_time  = !empty($val_6->in_time)?date('h:i A', strtotime($val_6->in_time)):'';
							            $out_time = !empty($val_6->out_time)?date('h:i A', strtotime($val_6->out_time)):'';
							            $att_type = !empty($val_6->attendance_type)?$val_6->attendance_type:'';
							            $reason   = !empty($val_6->reason)?$val_6->reason:'';

							            if($att_type == 1)
							            {
							            	$ord_whr = array('att_id' => $att_id);
											$ord_col = 'id, order_no';
											$ord_val = $this->order_model->getOrder($ord_whr, '', '', 'result', '', '', '', '', $ord_col);

											if($ord_val)
											{
												$ord_id  = !empty($ord_val[0]->id)?$ord_val[0]->id:'';
												$ord_num = !empty($ord_val[0]->order_no)?$ord_val[0]->order_no:'';
												
												// Order Details
												$ordDet_whr = array('order_id' => $ord_id);
												$ordDet_col = 'price, order_qty';
												$ordDet_val = $this->order_model->getOrderDetails($ordDet_whr, '', '', 'result', '', '', '', '', $ordDet_col);

												$ord_tot = 0;
												if($ordDet_val)
												{
													$order_tot = 0;
													foreach ($ordDet_val as $key => $val_2) {
														$price      = !empty($val_2->price)?$val_2->price:'0';
								            			$order_qty  = !empty($val_2->order_qty)?$val_2->order_qty:'0';
								            			$order_val  = $price * $order_qty;
								            			$order_tot += $order_val;
													}

													$ord_tot += round($order_tot);
												}
												
												$att_view = 'Sales Order';
											}
											else
											{
												$att_view = 'Sales Order';
												$ord_num  = '';
												$ord_tot  = '0';
											}
							            }
							            else
							            {
							            	$att_view = 'No Order';
							            	$ord_num  = '';
											$ord_tot  = '0';
							            }

							            $attendance_list[] = array(
							            	'in_time'         => $in_time,
								            'out_time'        => $out_time,
								            'attendance_type' => $att_view,
								            'order_no'        => $ord_num,
								            'order_total'     => strval($ord_tot),
								            'reason'          => $reason,
											'att_id'          => $att_id,
											'outlet_id'       => $outlet_id,
							            );
									}
								}

								$outlet_list[] = array(
									'outlet_id'       => $outlet_id,
									'company_name'    => $company_name,
									'mobile'          => $mobile,
									'attendance_list' => $attendance_list,
								);
							}
						}
					}

					// Attendance Details
					$limit  = 1;
					$offset = 0;

					$option['order_by']   = 'id';
					$option['disp_order'] = 'DESC';

					$whr_7 = array(
	    				'emp_id'    => $employee_id,
	    				'c_date'    => $cur_date,
	    				'published' => '1'
	    			);

					$col_7 = 'out_time';
	    			$res_7 = $this->attendance_model->getAttendance($whr_7, $limit, $offset, 'result', '', '', $option, '', $col_7);

	    			$out_time = !empty($res_7[0]->out_time)?date('h:i A', strtotime($res_7[0]->out_time)):'';

	    			// Order Details
					$whr_8 = array(
						'emp_id'    => $employee_id,
						'date'      => $cur_date,
						'published' => '1',
						'status'    => '1',
					);

					$col_8 = 'id';
					$res_8 = $this->order_model->getOrder($whr_8, '', '', 'result', '', '', '', '', $col_8);

					$order_count = 0;
					$order_total = 0;
					
					if($res_8)
					{
						$order_val   = '';							
						foreach ($res_8 as $key => $val_8) {
							$ord_id = !empty($val_8->id)?$val_8->id:'';

							$order_val .= $ord_id.',';

							$order_count++;
						}

						$order_res = substr_replace($order_val, "", -1);

						$whr_9 = array(
							'order_id'  => $order_res,
							'published' => '1',
							'status'    => '1',
						);

						$col_9 = 'price, order_qty';
						$res_9 = $this->order_model->getOrderListDetails($whr_9, '', '', 'result', '', '', '', '', $col_9);

						if($res_9)
						{
							foreach ($res_9 as $key => $val_9) {
								$price        = !empty($val_9->price)?$val_9->price:'';
								$order_qty    = !empty($val_9->order_qty)?$val_9->order_qty:'';
								$price_tot    = $order_qty * $price;
								$order_total += $price_tot;
							}
						}
					}

					$total_value = round($order_total);

					// Order Details
					$whr_10 = array(
						'emp_id'    => $employee_id,
						'date'      => $cur_date,
						'published' => '1',
						'status'    => '1',
					);

					$grp_10 = 'store_id';
					$col_10 = 'id';
					$res_10 = $this->order_model->getOrder($whr_10, '', '', 'result', '', '', '', '', $col_10, $grp_10);

					$outletOrd_count = 0;
					if($res_10)
					{
						$order_val   = '';							
						foreach ($res_10 as $key => $val_10) {
							$ord_id = !empty($val_10->id)?$val_10->id:'';

							$outletOrd_count++;
						}
					}

					// Outlet visit details
					$whr_11 = array(
						'emp_id'    => $employee_id,
						'c_date'    => $cur_date,
						'published' => '1',
					);

					$grp_11 = 'store_id';
					$col_11 = 'store_id';
					$res_11 = $this->attendance_model->getAttendance($whr_11, '', '', 'result', '', '', '', '', $col_11, $grp_11);

					$old_outlet = 0;
					$new_outlet = 0;

					if($res_11)
					{
						foreach ($res_11 as $key => $val_11) {
							$store_id = !empty($val_11->store_id)?$val_11->store_id:'';

							$whr_11 = array('id' => $store_id, 'date' => $cur_date, 'published' => '1');
							$res_11 = $this->outlets_model->getOutlets($whr_11,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

							$val_11 = !empty($res_11[0]->autoid)?$res_11[0]->autoid:'0';

							if($val_11 != 0)
							{
								$new_outlet++;
							}
							else
							{
								$old_outlet++;
							}
						}
					}

					// Attendance Start Details
					$limit  = 1;
					$offset = 0;

					$option['order_by']   = 'id';
					$option['disp_order'] = 'ASC';

					$whr_12 = array(
	    				'emp_id'    => $employee_id,
	    				'c_date'    => $cur_date,
	    				'published' => '1'
	    			);

					$col_12 = 'in_time';
	    			$res_12 = $this->attendance_model->getAttendance($whr_12, $limit, $offset, 'result', '', '', $option, '', $col_12);

	    			$in_time = !empty($res_12[0]->in_time)?date('h:i A', strtotime($res_12[0]->in_time)):'';

	    			// Collection details
					$grp_13 = 'outlet_id';
					$whr_13 = array('date' => $cur_date, 'employee_id' => $employee_id);
					$collection_cnt = $this->payment_model->getOutletPayment($whr_13,'','',"row",array(),array(),array(),TRUE,'COUNT(id) AS autoid', $grp_13);
					$cnt_value = !empty($collection_cnt->autoid)?$collection_cnt->autoid:'0';

					// Collection value
					$whr_14 = array('date' => $cur_date, 'employee_id' => $employee_id);
					$collection_amt = $this->payment_model->getOutletPayment($whr_14,'','',"row",array(),array(),array(),TRUE,'SUM(amount) AS coll_amt');
					$coll_value = !empty($collection_amt->coll_amt)?$collection_amt->coll_amt:'0';

					$emp_data[] = array(
						'name'           => $emp_name,
						'date'           => date('d-M-Y', strtotime($cur_date)),
						'beat'           => $beat_name,
						'total_outlet'   => strval(!empty($outlet_count)?$outlet_count:'0'),
						'new_outlet'     => strval(!empty($new_outlet)?$new_outlet:'0'),
						'old_outlet'     => strval(!empty($old_outlet)?$old_outlet:'0'),
						'order_outlet'   => strval(!empty($outletOrd_count)?$outletOrd_count:'0'),
						'order_count'    => strval(!empty($order_count)?$order_count:'0'),
						'order_total'    => strval(!empty($total_value)?$total_value:'0'),
						'collection_cnt' => $cnt_value,
						'collection_val' => $coll_value,
						'start_time'     => $in_time,
						'close_time'     => $out_time,
						'outlet_list'    => $outlet_list,
					);

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $emp_data;
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

			else if($method == '_overallDayEndReport')
			{
				$sel_date    = $this->input->post('sel_date');

				if(!empty($sel_date))
				{
					$cur_date = date('Y-m-d', strtotime($sel_date));
				}
				else
				{
					$cur_date = date('Y-m-d');
				}

				// Employee List
				$whr_1 = array('status' => '1', 'published' => '1');
				$col_1 = 'id, first_name, last_name';
				$res_1 = $this->employee_model->getEmployee($whr_1, '', '', 'result', '', '', '', '', $col_1);

				if($res_1)
				{
					$rpt_list = [];
					foreach ($res_1 as $key => $val_1) {
						$emp_id   = !empty($val_1->id)?$val_1->id:'';
						$first_name = !empty($val_1->first_name)?$val_1->first_name:'';
						$last_name = !empty($val_1->last_name)?$val_1->last_name:'';
						
								$arr = array($first_name,$last_name);
								$emp_name =join(" ",$arr);

						// Start Attendance Details
						$limit  = 1;
						$offset = 0;

						$str_option['order_by']   = 'id';
						$str_option['disp_order'] = 'ASC';

						$whr_2 = array(
		    				'emp_id'    => $emp_id,
		    				'c_date'    => $cur_date,
		    				'published' => '1'
		    			);

						$col_2 = 'in_time';
		    			$res_2 = $this->attendance_model->getAttendance($whr_2, $limit, $offset, 'result', '', '', $str_option, '', $col_2);

		    			$in_time = !empty($res_2[0]->in_time)?date('h:i A', strtotime($res_2[0]->in_time)):'';

						// End Attendance Details
						$end_option['order_by']   = 'id';
						$end_option['disp_order'] = 'DESC';

						$whr_2 = array(
		    				'emp_id'    => $emp_id,
		    				'c_date'    => $cur_date,
		    				'published' => '1'
		    			);

						$col_2 = 'out_time';
		    			$res_2 = $this->attendance_model->getAttendance($whr_2, $limit, $offset, 'result', '', '', $end_option, '', $col_2);

		    			$out_time = !empty($res_2[0]->out_time)?date('h:i A', strtotime($res_2[0]->out_time)):'';

						// Today Beat
						$whr_3 = array(
							'employee_id' => $emp_id,
							'assign_date' => $cur_date,
							'published'   => '1',
						);

						$col_3 = 'assign_store';
						$res_3 = $this->assignshop_model->getAssignshopDetails($whr_3, '', '', 'result', '', '', '', '', $col_3);

						$assign_str = !empty($res_3[0]->assign_store)?$res_3[0]->assign_store:'';

						$outlet_count = '0';
						$new_outlet   = '0';

						if(!empty($assign_str))
						{
							$exp_beat   = explode(',', $assign_str);
							$beat_count = count($exp_beat);

							for ($i=0; $i < $beat_count; $i++) { 

								$whr_4 = array(
									'zone_id'   => $assign_str,
									'published' => '1',
									'status'    => '1',
								);

								$res_4  = $this->outlets_model->getOutletsList($whr_4,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

								$outlet_count = !empty($res_4[0]->autoid)?$res_4[0]->autoid:'0';

							}
						}

						// Outlet visit details
						$whr_5 = array(
							'emp_id'    => $emp_id,
							'c_date'    => $cur_date,
							'published' => '1',
						);

						$grp_5 = 'store_id';
						$col_5 = 'store_id';
						$res_5 = $this->attendance_model->getAttendance($whr_5, '', '', 'result', '', '', '', '', $col_5, $grp_5);

						$old_outlet = 0;
						$new_outlet = 0;

						if($res_5)
						{
							foreach ($res_5 as $key => $val_5) {
								$store_id = !empty($val_5->store_id)?$val_5->store_id:'';

								$whr_6 = array('id' => $store_id, 'date' => $cur_date, 'published' => '1');
								$res_6 = $this->outlets_model->getOutlets($whr_6,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

								$val_6 = !empty($res_6[0]->autoid)?$res_6[0]->autoid:'0';

								if($val_6 != 0)
								{
									$new_outlet++;
								}
								else
								{
									$old_outlet++;
								}
							}
						}

						// Order Outlet Count
						$whr_7 = array(
							'emp_id'    => $emp_id,
							'date'      => $cur_date,
							'published' => '1',
							'status'    => '1',
						);

						$grp_7 = 'store_id';
						$col_7 = 'id';
						$res_7 = $this->order_model->getOrder($whr_7, '', '', 'result', '', '', '', '', $col_7, $grp_7);

						$outletOrd_count = 0;
						if($res_7)
						{
							$order_val   = '';							
							foreach ($res_7 as $key => $val_7) {
								$ord_id = !empty($val_7->id)?$val_7->id:'';

								$outletOrd_count++;
							}
						}

						// Order Details
						$whr_8 = array(
							'emp_id'    => $emp_id,
							'date'      => $cur_date,
							'published' => '1',
							'status'    => '1',
						);

						$col_8 = 'id';
						$res_8 = $this->order_model->getOrder($whr_8, '', '', 'result', '', '', '', '', $col_8);

						$order_count = 0;
						$order_total = 0;
						
						if($res_8)
						{
							$order_val   = '';							
							foreach ($res_8 as $key => $val_8) {
								$ord_id = !empty($val_8->id)?$val_8->id:'';

								$order_val .= $ord_id.',';

								$order_count++;
							}

							$order_res = substr_replace($order_val, "", -1);

							$whr_9 = array(
								'order_id'  => $order_res,
								'published' => '1',
								'status'    => '1',
							);

							$col_9 = 'price, order_qty';
							$res_9 = $this->order_model->getOrderListDetails($whr_9, '', '', 'result', '', '', '', '', $col_9);

							if($res_9)
							{
								foreach ($res_9 as $key => $val_9) {
									$price        = !empty($val_9->price)?$val_9->price:'';
									$order_qty    = !empty($val_9->order_qty)?$val_9->order_qty:'';
									$price_tot    = $order_qty * $price;
									$order_total += $price_tot;
								}
							}
						}

						$total_value = round($order_total);

						// Collection details
						$grp_9 = 'outlet_id';
						$whr_9 = array('date' => $cur_date, 'employee_id' => $emp_id);
						$collection_cnt = $this->payment_model->getOutletPayment($whr_9,'','',"row",array(),array(),array(),TRUE,'COUNT(id) AS autoid', $grp_9);
						$cnt_value = !empty($collection_cnt->autoid)?$collection_cnt->autoid:'0';

						// Collection value
						$whr_10 = array('date' => $cur_date, 'employee_id' => $emp_id);
						$collection_amt = $this->payment_model->getOutletPayment($whr_10,'','',"row",array(),array(),array(),TRUE,'SUM(amount) AS coll_amt');
						$coll_value = !empty($collection_amt->coll_amt)?$collection_amt->coll_amt:'0';

						$rpt_list[] = array(
							'emp_name'       => $emp_name,
							'date'           => date('d-M-Y', strtotime($cur_date)),
							'start_time'     => $in_time,
							'close_time'     => $out_time,
							'total_outlet'   => strval(!empty($outlet_count)?$outlet_count:'0'),
							'new_outlet'     => strval(!empty($new_outlet)?$new_outlet:'0'),
							'old_outlet'     => strval(!empty($old_outlet)?$old_outlet:'0'),
							'order_outlet'   => strval(!empty($outletOrd_count)?$outletOrd_count:'0'),
							'order_count'    => strval(!empty($order_count)?$order_count:'0'),
							'order_total'    => strval(!empty($total_value)?$total_value:'0'),
							'collection_cnt' => strval(!empty($cnt_value)?$cnt_value:'0'),
							'collection_val' => strval(!empty($coll_value)?$coll_value:'0'),
						);
					}

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $rpt_list;
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

			else if($method == '_targetDetails')
			{
				$employee_id = $this->input->post('employee_id');
				$cur_month   = date('m');
				$cur_year    = date('Y');

				if($employee_id)
				{
					// Target Details
					$dateObj   = DateTime::createFromFormat('!m', $cur_month);
					$monthName = $dateObj->format('F'); // March

					$where_5 = array(
						'employee_id' => $employee_id,
						'month_name'  => $monthName, 
						'year_name'   => $cur_year, 
						'published'   => '1', 
					);

					$column_5 = 'target_val, achieve_val';
					$data_5   = $this->target_model->getTargetDetails($where_5, '', '', 'result', '', '', '', '', $column_5);

					if($data_5)
					{
						$target_val  = !empty($data_5[0]->target_val)?$data_5[0]->target_val:'0';
						$achieve_val = !empty($data_5[0]->achieve_val)?$data_5[0]->achieve_val:'0';

						// Date Wise Target Achievement
						$month_count = cal_days_in_month(CAL_GREGORIAN, $cur_month, $cur_year);

						$val1 = array();
						// $val0 = array('Date', 'Value');
						// array_push($val1, $val0);

						for ($i=1; $i <= $month_count; $i++) {

							$date_val = date('Y-m-d', strtotime($i.'-'.$cur_month.'-'.$cur_year));

							// Order Details
							$start_value = date('Y-m-d H:i:s', strtotime($date_val. '00:00:00'));
							$end_value   = date('Y-m-d H:i:s', strtotime($date_val. '23:59:59'));

							// Get Success Order Details
							$whr_1 = array(
								'tbl_invoice.sales_employee' => $employee_id,
								'tbl_invoice_details.createdate >='  => $start_value,
								'tbl_invoice_details.createdate <='  => $end_value,
								'tbl_invoice_details.published'      => '1',
							);

							$col_1 = 'price, order_qty';

							$res_1 = $this->invoice_model->getOutletInvoiceDetails($whr_1, '', '', 'result', '', '', '', '', $col_1);

							$total_val = 0;
							if($res_1)
							{
								foreach ($res_1 as $key => $val) {
									$price     = !empty($val->price)?$val->price:'0';
	            					$order_qty = !empty($val->order_qty)?$val->order_qty:'0';
	            					$total_val = round($price * $order_qty);
								}
							}

							$val1[] = array(
								'date'  => date('d-M-Y', strtotime($date_val)),
								'value' => intval($total_val),
							);
						}

						$target_details[] = array(
							'target_val'  => $target_val,
							'achieve_val' => $achieve_val,
							'target_list' => $val1,
						);

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $target_details;
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
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
				}
			}

			else if($method == '_employeeTargetData')
			{
				$employee_id = $this->input->post('employee_id');
				
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
			    	$cur_month = date('m');
			    	$cur_year  = date('Y');
			    	$dateObj   = DateTime::createFromFormat('!m', $cur_month);
					$monthName = $dateObj->format('F'); // March

					// Overall Target Details
					$where_1 = array(
						'month_name'    => $monthName,
						'year_name'     => $cur_year,
						'employee_id'   => $employee_id,
						'target_val !=' => '0', 
						'published'     => '1',
					);

					$column_1 = 'employee_id, target_val, achieve_val';
					$data_1   = $this->target_model->getTargetDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

					if($data_1)
					{
						// Overall Target Details
						$target_val  = !empty($data_1[0]->target_val)?$data_1[0]->target_val:'0';
						$achieve_val = !empty($data_1[0]->achieve_val)?$data_1[0]->achieve_val:'0';
						$achieve_per = $achieve_val / $target_val * 100;

						$overall_target = array(
							'overall_target_val'  => $target_val,
							'overall_achieve_val' => $achieve_val,
							'overall_achieve_per' => round($achieve_per),
						);

						// Product Target Details
						$where_2 = array(
							'month_name'    => $monthName,
							'year_name'     => $cur_year,
							'emp_id'        => $employee_id,
							'target_val !=' => '0', 
							'published'     => '1',
						);

						$column_2 = 'description, target_val, achieve_val';
						$data_2   = $this->target_model->getProductTargetDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

						$product_target = [];
						if($data_2)
						{
							foreach ($data_2 as $key => $val_2) {
								$description     = !empty($val_2->description)?$val_2->description:'';
								$pdt_target_val  = !empty($val_2->target_val)?$val_2->target_val:'0';
								$pdt_achieve_val = !empty($val_2->achieve_val)?$val_2->achieve_val:'0';
								$pdt_achieve_per = $pdt_achieve_val / $pdt_target_val * 100;

								$product_target[] = array(
									'description'     => $description,
									'pdt_target_val'  => $pdt_target_val,
									'pdt_achieve_val' => $pdt_achieve_val,
									'pdt_achieve_per' => round($pdt_achieve_per),
								);
							}
						}

						// Beat Target Details
						$where_3 = array(
							'month_name'    => $monthName,
							'year_name'     => $cur_year,
							'emp_id'        => $employee_id,
							'target_val !=' => '0', 
							'published'     => '1',
						);

						$column_3 = 'zone_name, target_val, achieve_val';
						$data_3   = $this->target_model->getBeatTargetDetails($where_3, '', '', 'result', '', '', '', '', $column_3);

						$beat_target = [];
						if($data_3)
						{
							foreach ($data_3 as $key => $val_3) {
								$zone_name        = !empty($val_3->zone_name)?$val_3->zone_name:'';
								$beat_target_val  = !empty($val_3->target_val)?$val_3->target_val:'0';
								$beat_achieve_val = !empty($val_3->achieve_val)?$val_3->achieve_val:'0';
								$beat_achieve_per = $beat_achieve_val / $beat_target_val * 100;

								$beat_target[] = array(
									'zone_name'        => $zone_name,
									'beat_target_val'  => $beat_target_val,
									'beat_achieve_val' => $beat_achieve_val,
									'beat_achieve_per' => round($beat_achieve_per),
								);
							}
						}

						$target_details[] = array(
							'overall_target' => $overall_target,
							'product_target' => $product_target,
							'beat_target'    => $beat_target,
						);

						$response['status']  = 1;
                        $response['message'] = "Success"; 
                        $response['data']    = $target_details;
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

			else if($method == '_changePassword')
			{
				$employee_id = $this->input->post('employee_id');	
				$new_pword   = $this->input->post('new_pword');
				$c_pword     = $this->input->post('c_pword');

				$error    = FALSE;
			    $errors   = array();
				$required = array('employee_id', 'new_pword', 'c_pword');

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
			    	if($new_pword == $c_pword)
			    	{
			    		$data      = array('password'  => $c_pword);
			    		$update_id = array('id'=>$employee_id);
					    $update    = $this->employee_model->employee_update($data, $update_id);

					    if($update)
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
				        $response['message'] = "Password Mismatch"; 
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
	}
?>
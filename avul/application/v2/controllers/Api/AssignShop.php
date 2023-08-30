<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class AssignShop extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('assignshop_model');
			$this->load->model('attendance_model');
			$this->load->model('outlets_model');
			$this->load->model('commom_model');
			$this->load->model('managers_model');
			$this->load->model('employee_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Add Assign Shop
		// ***************************************************
		public function add_assign_shop($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addAssignShop')
			{
				$error          = FALSE;
				$assign_type    = $this->input->post('assign_type');
				$distributor_id = $this->input->post('distributor_id');
				$employee_id    = $this->input->post('employee_id');
				$employee_name  = $this->input->post('employee_name');
				$month_id       = $this->input->post('month_id');
				$financial_id   = $this->input->post('financial_id');
				$store_value    = $this->input->post('store_value');
				$log_id         = $this->input->post('log_id'); 
				$log_role       = $this->input->post('log_role'); 

				$error = FALSE;
			    $errors = array();
				$required = array('employee_id', 'employee_name', 'month_id', 'financial_id', 'store_value');
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
			    	$where=array(
				    	'employee_id'  => $employee_id,
				    	'month_id'     => $month_id,
				    	'financial_id' => $financial_id,
				    	'published'    => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->assignshop_model->getAssignshop($where, '', '', 'result', '', '', '', '', $column);

					if(!empty($overalldatas))
					{
						$response['status']  = 0;
				        $response['message'] = "Data Already Exist"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
					}
					else
					{
						$assign_value = !empty($assign_type)?$assign_type:1;
						$dis_value    = !empty($distributor_id)?$distributor_id:null;

						$data = array(
							'assign_type'    => $assign_value,
					    	'distributor_id' => $dis_value,
							'employee_id'    => $employee_id,
							'employee_name'  => $employee_name,
					    	'month_id'       => $month_id,
					    	'financial_id'   => $financial_id,
					    	'createdate'     => date('Y-m-d H:i:s'),
					    );

					    $insert = $this->assignshop_model->assignshop_insert($data);

					    $store_val = json_decode($store_value);	

					    foreach ($store_val as $key => $value) {
					    	$str_data = array(
								'assign_id'      => $insert,
								'assign_type'    => $assign_value,
					    		'distributor_id' => $dis_value,
								'employee_id'    => $employee_id,
								'assign_date'    => date('Y-m-d', strtotime($value->assign_date)),
								'assign_day'     => $value->assign_day,
								'assign_store'   => $value->assign_store,
								'meeting_val'    => ($value->meeting_val)?$value->meeting_val:0,
								'createdate'     => date('Y-m-d H:i:s'),
							);

							$assign_insert = $this->assignshop_model->assignshopDetails_insert($str_data);
					    }

						if ($log_role == 'TSI') {
							$log_data = array(
								'u_id'       => $log_id,
								'role'       => $log_role,
								'table'      => 'tbl_employee',
								'auto_id'    => $insert,
								'action'     => 'create',
								'date'       => date('Y-m-d'),
								'time'       => date('H:i:s'),
								'createdate' => date('Y-m-d H:i:s')
							);
	
							$log_val = $this->commom_model->log_insert($log_data);
						}

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
			}

			else if($method == '_updateAssignShop')
			{
				$error = FALSE;
				$assign_id      = $this->input->post('assign_id');
				$assign_type    = $this->input->post('assign_type');
				$distributor_id = $this->input->post('distributor_id');
				$employee_id    = $this->input->post('employee_id');
				$employee_name  = $this->input->post('employee_name');
				$month_id       = $this->input->post('month_id');
				$financial_id   = $this->input->post('financial_id');
				$store_value    = $this->input->post('store_value');
				$log_id         = $this->input->post('log_id'); 
				$log_role       = $this->input->post('log_role');

				$error = FALSE;
			    $errors = array();
				$required = array('employee_id', 'employee_name', 'month_id', 'financial_id', 'store_value');
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
			    	$where=array(
			    		'id !='        => $assign_id,
				    	'employee_id'  => $employee_id,
				    	'month_id'     => $month_id,
				    	'financial_id' => $financial_id,
				    	'published'    => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->assignshop_model->getAssignshop($where, '', '', 'result', '', '', '', '', $column);

					if(!empty($overalldatas))
					{
						$response['status']  = 0;
				        $response['message'] = "Data Already Exist"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
					}
					else
					{
						$assign_value = !empty($assign_type)?$assign_type:1;
						$dis_value    = !empty($distributor_id)?$distributor_id:null;

						// Assign Shop
						$data = array(
							'assign_type'    => $assign_value,
					    	'distributor_id' => $dis_value,
							'employee_id'    => $employee_id,
							'employee_name'  => $employee_name,
					    	'financial_id'   => $financial_id,
					    	'updatedate'     => date('Y-m-d H:i:s'),
					    );

			    		$update_id = array('id'=>$assign_id);
					    $update    = $this->assignshop_model->assignshop_update($data, $update_id);

					    // Assign Shop Details
					    $store_val = json_decode($store_value);	
					    foreach ($store_val as $key => $value) {

					    	$str_data = array(
								'assign_id'      => $assign_id,
								'assign_type'    => $assign_value,
					    		'distributor_id' => $dis_value,
								'employee_id'    => $employee_id,
								'assign_date'    => date('Y-m-d', strtotime($value->assign_date)),
								'assign_day'     => $value->assign_day,
								'assign_store'   => $value->assign_store,
								'meeting_val'    => ($value->meeting_val)?$value->meeting_val:0,
								'updatedate'     => date('Y-m-d H:i:s'),
							);

							$auto_id  = array('id' => $value->auto_id);
					    	$update_det = $this->assignshop_model->assignshopDetails_update($str_data, $auto_id);
					    }
						if ($log_role == 'TSI') {
							$log_data = array(
								'u_id'       => $log_id,
								'role'       => $log_role,
								'table'      => 'tbl_assign_shop',
								'auto_id'    => $assign_id,
								'action'     => 'update',
								'date'       => date('Y-m-d'),
								'time'       => date('H:i:s'),
								'createdate' => date('Y-m-d H:i:s')
							);
	
							$log_val = $this->commom_model->log_insert($log_data);
						}

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

		// Manage Assign Shop
		// ***************************************************
		public function manage_assign_shop($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listAssignShopPaginate')
			{
			    $emp_id         = $this->input->post('id');
				$limit          = $this->input->post('limit');
	    		$offset         = $this->input->post('offset');
	    		$assign_type    = $this->input->post('assign_type');
	    		$distributor_id = $this->input->post('distributor_id');
	    		$financ_year    = $this->input->post('financial_year');
				$type           = $this->input->post('type');
				$assign_status  = ($assign_type)?$assign_type:1;
				
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
			 if($type ==2){
				$where_mg = array(
						
					'employee_id' => $emp_id,
				);


				$mg_val = $this->managers_model->getManagers($where_mg);
				$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id)?$mg_val[0]->ctrl_zone_id:'0';
				$designation_code = !empty($mg_val[0]->designation_code)?$mg_val[0]->designation_code:'0';
			

				if($designation_code=='TSI'){
					if($ctrl_zone){
					
						$zone_id_finall = substr($ctrl_zone,1,-1);
				

						$d_zone = !empty($zone_id_finall)?$zone_id_finall:'';
				
						$d_zone_val = explode(',', $d_zone);
						$st_count = count($d_zone_val);
						$count_emp = [];
						for( $i=0; $i < $st_count; $i++){



							$wer = array(
								'designation_code'  => 'BDE', 
								'published'      => '1'
							);
							$like['ctrl_zone_id'] =','. $d_zone_val[$i].',';

							$co1 ='employee_id';

							$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);
							
							if(!empty($mg_val)){
									
								foreach ($mg_val as $key => $value) {
									array_push($count_emp,$value);
									
								
								}
							}
						}
					}	
				
				}
				$emp_c=count($count_emp);
				
				for( $i=0; $i < $emp_c; $i++){
					$new_st_id[]   = !empty($count_emp[$i]->employee_id)?$count_emp[$i]->employee_id:'';
				 
				}
			
				$search = $this->input->post('search');
	    		if($search !='')
	    		{
	    			$like['name']     = $search;

	    			$where = array(
	    				'financial_id' => $financ_year,
	    				'published'    => '1'
	    			);

					$where_in1['employee_id'] = $new_st_id;
	    		}
	    		else
	    		{
	    			$like = [];
	    			$where = array(
	    				'financial_id' => $financ_year,
	    				'published'    => '1'
	    			);

					$where_in1['employee_id'] = $new_st_id;
	    		}

	    		$column = 'id';
				$overalldatas = $this->assignshop_model->getAssignshop($where, '', '', 'result', $like, '', '', '', $column,$where_in1);
				
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

				$data_list = $this->assignshop_model->getAssignshop($where, $limit, $offset, 'result', $like, '', $option,'','',$where_in1);

			 }else{
				$search = $this->input->post('search');
	    		if($search !='')
	    		{
	    			$like['name']     = $search;

	    			$where = array(
	    				'financial_id' => $financ_year,
	    				'published'    => '1'
	    			);

	    			if($distributor_id)
	    			{
	    				$where['distributor_id'] = $distributor_id;
	    				$where['assign_type']    = $assign_status;
	    			}

	    			$where['assign_type']    = $assign_status;
	    		}
	    		else
	    		{
	    			$like = [];
	    			$where = array(
	    				'financial_id' => $financ_year,
	    				'published'    => '1'
	    			);

	    			if($distributor_id)
	    			{
	    				$where['distributor_id'] = $distributor_id;
	    				$where['assign_type']    = $assign_status;
	    			}

	    			$where['assign_type']    = $assign_status;
	    		}

	    		$column = 'id';
				$overalldatas = $this->assignshop_model->getAssignshop($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->assignshop_model->getAssignshop($where, $limit, $offset, 'result', $like, '', $option);

			 }
				
				if($data_list)
				{
					$assign_list = [];
					foreach ($data_list as $key => $value) {

						$assign_id     = !empty($value->id)?$value->id:'';
						$employee_id   = !empty($value->employee_id)?$value->employee_id:'';
						$employee_name = !empty($value->employee_name)?$value->employee_name:'';
						$month_id      = !empty($value->month_id)?$value->month_id:'';
						$financial_id  = !empty($value->financial_id)?$value->financial_id:'';
						$status        = !empty($value->status)?$value->status:'';
						$createdate    = !empty($value->createdate)?$value->createdate:'';

						$assign_list[] = array(
							'assign_id'     => $assign_id,
							'employee_id'   => $employee_id,
							'employee_name' => $employee_name,
							'month_id'      => $month_id,
							'financial_id'  => $financial_id,
							'status'        => $status,
							'createdate'    => $createdate,
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
			        $response['data']         = $assign_list;
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

			else if($method == '_detailAssignShop')
			{
				$assign_id = $this->input->post('assign_id');

				if(!empty($assign_id))
				{
					$where_1 = array('id'=>$assign_id);
				    $data    = $this->assignshop_model->getAssignshop($where_1);
				    if($data)
				    {
				    	$assign_details = array(
				    		'assign_id'  => !empty($data[0]->id)?$data[0]->id:'',
							'emp_id'     => !empty($data[0]->employee_id)?$data[0]->employee_id:'',
							'emp_name'   => !empty($data[0]->employee_name)?$data[0]->employee_name:'',
							'month_id'   => !empty($data[0]->month_id)?$data[0]->month_id:'',
							'finan_id'   => !empty($data[0]->financial_id)?$data[0]->financial_id:'',
							'status'     => !empty($data[0]->status)?$data[0]->status:'',
							'createdate' => !empty($data[0]->createdate)?$data[0]->createdate:'',
				    	);

				    	$where_2 = array('assign_id'=>$assign_id);
				    	$data_2  = $this->assignshop_model->getAssignshopDetails($where_2);

				    	$store_list = [];
				    	foreach ($data_2 as $key => $value) {
				    		$auto_id      = !empty($value->id)?$value->id:'';
							$assign_id    = !empty($value->assign_id)?$value->assign_id:'';
							$assign_date  = !empty($value->assign_date)?$value->assign_date:'';
							$assign_day   = !empty($value->assign_day)?$value->assign_day:'';
							$assign_store = !empty($value->assign_store)?$value->assign_store:'';
							$meeting_val  = !empty($value->meeting_val)?$value->meeting_val:'0';
							$status       = !empty($value->status)?$value->status:'';
							$createdate   = !empty($value->createdate)?$value->createdate:'';

							$store_list[] = array(
								'auto_id'      => $auto_id,
								'assign_id'    => $assign_id,
								'assign_date'  => date('d-m-Y', strtotime($assign_date)),
								'assign_day'   => $assign_day,
								'assign_store' => $assign_store,
								'meeting_val'  => $meeting_val,
								'status'       => $status,
								'createdate'   => $createdate,
							);
				    	}

				    	$assign_value = array(
				    		'assign_details' => $assign_details,
				    		'store_list'     => $store_list, 
				    	);

				    	$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $assign_value;
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

			else if($method == '_deleteAssignShop')
			{
				$assign_id = $this->input->post('assign_id');

				if(!empty($assign_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

		    		$whr_one = array('id' => $assign_id);
				    $upt_one = $this->assignshop_model->assignshop_delete($data, $whr_one);

				    $whr_two = array('assign_id' => $assign_id);
				    $upt_two = $this->assignshop_model->assignshopDetails_delete($data, $whr_two);
				    if($upt_one)
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
			        $response['message'] = "Please fill all required fields"; 
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

		// Employee wise shop
		// ***************************************************
		public function employee_wise_shop($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_employeeWiseList')
			{
				$limit        = $this->input->post('limit');
	    		$offset       = $this->input->post('offset');
	    		$search       = $this->input->post('search');
				$employee_id  = $this->input->post('employee_id');
				$current_date = date('Y-m-d');

				if(!empty($employee_id))
				{
					// Employee details
					$emp_whr = array('id' => $employee_id, 'published' => '1');
					$emp_col = 'company_id';
					$emp_res = $this->employee_model->getEmployee($emp_whr, '', '', 'row', '', '', '', '', $emp_col);

					if($emp_res)
					{
						$dis_res = ($emp_res->company_id)?$emp_res->company_id:0;

						$where_1 = array(
							'employee_id'    => $employee_id,
							'assign_date'    => $current_date,
							'distributor_id' => $dis_res,
							'published'      => '1',
							'status'         => '1',
						);

						$column_1 = 'assign_id, assign_store';

						$data_1   = $this->assignshop_model->getAssignshopDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

						$assign_id    = !empty($data_1[0]->assign_id)?$data_1[0]->assign_id:'';
						$assign_store = !empty($data_1[0]->assign_store)?$data_1[0]->assign_store:'';

						// Attendance Count
						$att_whr = array(
							'emp_id'    => $employee_id, 
							'c_date'    => $current_date, 
							'published' => '1'
						);

						$att_col = $this->attendance_model->getAttendance($att_whr,'','',"row",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

						$att_cnt = zero_check($att_col->autoid);
						$att_val = !empty($att_cnt)?'1':'2';

						if(!empty($assign_store))
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

							if($search !='')
				    		{
				    			$like['name'] = $search;
				    		}
				    		else
				    		{
				    			$like = [];
				    		}

							$where_2 = array(
								'distributor_id' => $dis_res,
								'zone_id'        => $assign_store,
								'published'      => '1',
								'status'         => '1',
							);

							$column_2 = 'id';

							$overalldatas = $this->outlets_model->getOutletsList($where_2, '', '', 'result', $like, '', '', '', $column_2);

							if($overalldatas)
							{
								$totalc = count($overalldatas);
							}
							else
							{
								$totalc = 0;
							}

							$option['order_by']   = 'company_name';
							$option['disp_order'] = 'ASC';

							$column_3 = 'id, company_name, contact_name, mobile, email, gst_no, pan_no, tan_no, due_days, address, state_id, city_id, zone_id, latitude, longitude, credit_limit, available_limit, pre_limit, credit_note, otp_type';

							$data_list = $this->outlets_model->getOutletsList($where_2, $limit, $offset, 'result', $like, '', $option, '', $column_3);

							if($data_list)
							{
								$store_list = [];
								foreach ($data_list as $key => $value) 
								{	
									$store_id      = !empty($value->id)?$value->id:'';
									$company_name  = !empty($value->company_name)?$value->company_name:'';
									$contact_name  = !empty($value->contact_name)?$value->contact_name:'';
									$mobile        = !empty($value->mobile)?$value->mobile:'';
									$email         = !empty($value->email)?$value->email:'';
									$gst_no        = !empty($value->gst_no)?$value->gst_no:'';
									$pan_no        = !empty($value->pan_no)?$value->pan_no:'';
									$tan_no        = !empty($value->tan_no)?$value->tan_no:'';
									$due_days      = !empty($value->due_days)?$value->due_days:'';
									$address       = !empty($value->address)?$value->address:'';
									$state_id      = !empty($value->state_id)?$value->state_id:'';
									$city_id       = !empty($value->city_id)?$value->city_id:'';
									$zone_id       = !empty($value->zone_id)?$value->zone_id:'';
									$latitude      = !empty($value->latitude)?$value->latitude:'';
									$longitude     = !empty($value->longitude)?$value->longitude:'';
									$credit_limit  = !empty($value->credit_limit)?$value->credit_limit:'';
									$available_lmt = !empty($value->available_limit)?$value->available_limit:'';
									$pre_limit     = !empty($value->pre_limit)?$value->pre_limit:'';
									$credit_note   = !empty($value->credit_note)?$value->credit_note:'';
									$otp_type      = !empty($value->otp_type)?$value->otp_type:'';

									// Attendance Details
									$where = array(
										'emp_id'    => $employee_id,
										'store_id'  => $store_id,
										'c_date'    => $current_date,
										'published' => '1',
									);

									$option['order_by']   = 'id';
									$option['disp_order'] = 'DESC';

									$column   = 'id, log_status';
									$att_list = $this->attendance_model->getAttendance($where, '', '', 'result', '', '', $option, '', $column);

									$log_sta = '0';
									$att_id  = '';
									if(!empty($att_list))
									{
										$att_data = $att_list[0];
							            $att_id   = !empty($att_data->id)?$att_data->id:'';
							            $log_sta  = !empty($att_data->log_status)?$att_data->log_status:'0';
									}

									$store_list[] = array(
										'store_id'          => $store_id,
										'company_name'      => $company_name,
										'contact_name'      => $contact_name,
										'mobile'            => $mobile,
										'email'             => $email,
										'gst_no'            => $gst_no,
										'pan_no'            => $pan_no,
										'tan_no'            => $tan_no,
										'due_days'          => $due_days,
										'address'           => $address,
										'state_id'          => $state_id,
										'city_id'           => $city_id,
										'zone_id'           => $zone_id,
										'latitude'          => $latitude,
										'longitude'         => $longitude,
										'credit_limit'      => $credit_limit,
										'available_limit'   => $available_lmt,
										'pre_limit'         => $pre_limit,
										'credit_note'       => $credit_note,
										'attendance_status' => $log_sta,
										'attendance_id'     => $att_id,
										'upload_status'     => $att_val,
										'otp_type'          => $otp_type,
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
						        $response['data']         = $store_list;
					    		echo json_encode($response);
						        return;
							}
							else
							{
								$response['status']  = 0;
						        $response['message'] = "No Outlet to work today!!!"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return;
							}
						}
						else
						{
							$response['status']  = 0;
					        $response['message'] = "No Outlet to work today!!!"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
						}
					}
					else
					{
						$response['status']  = 0;
				        $response['message'] = "No data found"; 
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

			else if($method == '_employeeWiseBeat')
			{
				$employee_id  = $this->input->post('employee_id');
				$current_date = date('Y-m-d');

				if(!empty($employee_id))
				{
					$where_1 = array(
						'employee_id' => $employee_id,
						'assign_date' => $current_date,
						'published'   => '1',
						'status'      => '1',
					);

					$column_1 = 'assign_store';

					$data_1   = $this->assignshop_model->getAssignshopDetails($where_1, '', '', 'row', '', '', '', '', $column_1);

					$assign_store = !empty($data_1->assign_store)?$data_1->assign_store:'';	

					if($assign_store)
					{
						$where_2 = array(
							'zone_id'   => $assign_store,
							'published' => '1',
							'status'    => '1',
						);

						$column_2 = 'id, state_id, city_id, name';
						$data_2   = $this->commom_model->getZoneImplode($where_2, '', '', 'result', '', '', '', '', $column_2);

						if($data_2)
						{	
							$zone_list = [];
							foreach ($data_2 as $key => $val_2) {
									
								$zone_id   = !empty($val_2->id)?$val_2->id:'';
							    $state_id  = !empty($val_2->state_id)?$val_2->state_id:'';
							    $city_id   = !empty($val_2->city_id)?$val_2->city_id:'';
							    $zone_name = !empty($val_2->name)?$val_2->name:'';

							    $zone_list[] = array(
							    	'zone_id'   => $zone_id,
								    'state_id'  => $state_id,
								    'city_id'   => $city_id,
								    'zone_name' => $zone_name,
							    );
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $zone_list;
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
			        $response['message'] = "Please fill all required fields"; 
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
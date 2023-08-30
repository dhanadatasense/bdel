<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Employee extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}
        //edit ref_id
		public function add_employee($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');
			$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$f_name     = $this->input->post('f_name');
				$l_name     = $this->input->post('l_name');
				$mobile       = $this->input->post('mobile');
				$email        = $this->input->post('email');
				$address      = $this->input->post('address');
				$password     = $this->input->post('password');
				$log_type     = $this->input->post('log_type');
				$login_status = $this->input->post('login_status');
				$method       = $this->input->post('method');
				$pan_no             = $this->input->post('pan_no');
				$aadhar_no             = $this->input->post('aadhar_no');
				$dob             = $this->input->post('dob');
				$o_educational_q             = $this->input->post('o_educational_q');
				$gender             = $this->input->post('gender');
				$account_name             = $this->input->post('account_name');
				$father_n             = $this->input->post('father_n');
				$mother_n             = $this->input->post('mother_n');
				$account_no             = $this->input->post('account_no');
				$account_type             = $this->input->post('account_type');
				$ifsc_code             = $this->input->post('ifsc_code');
				$bank_name             = $this->input->post('bank_name');
				$branch_name             = $this->input->post('branch_name');
				$Pincode             = $this->input->post('pincode');
				$street_name             = $this->input->post('street_name');
				$door             = $this->input->post('door');
				$grade_id             = $this->input->post('grade_id');
				$district_name     = $this->input->post('district_name');
				$post_name     = $this->input->post('post_name');
				$state_name     = $this->input->post('state_name');
				$password       = $this->input->post('password');
				$country_name   = $this->input->post('country_name');
				$required = array('grade_id', 'door', 'street_name', 'pincode', 'f_name', 'l_name', 'branch_name', 'bank_name', 'ifsc_code', 'account_type', 'account_no', 'mother_n', 'account_name', 'father_n', 'gender', 'aadhar_no', 'o_educational_q', 'pan_no', 'dob','mobile');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error == TRUE)
			    {
			    	$response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        $response['error']   = []; 
			        echo json_encode($response);
			        return;
			    }
			    else
			    {
			    	if($method == 'BTBM_X_C')
			    	{
			    		if(userAccess('employee-add'))
			    		{
						
							
								$log_type = 2;
						
			    			$data = array(
								'log_type'     => $log_type,
			    				'log_id'       => $log_id,
								'log_role'     => $log_role,
						    	'f_name'       => ucfirst($f_name),
								'l_name'       => ucfirst($l_name),
						    	'mobile'       => $mobile,
						    	'email'        => $email,
						    	'address'      => $address,
						    	'password'       => $password,
						    	'dob'            => $dob,
						    	'company_type'   => 1,
						    	'company_id'     => 0,
								'o_educational_q'=> $o_educational_q,
								'pan_no'         => $pan_no,
								'aadhar_no'    => $aadhar_no,
								'gender'       => $gender,
								'father_n'     => $father_n,
								'mother_n'     => $mother_n,
								'account_name' => $account_name,
								'account_no'   => $account_no,
								'account_type' => $account_type,
								'ifsc_code'    => $ifsc_code,
								'bank_name'    => $bank_name,
								'branch_name'  => $branch_name,
								'Pincode'      => $Pincode,
								'street_name'  => $street_name,
								'door'         => $door,
								'grade_id'     => $grade_id,
								'district_name'     => $district_name,
								'post_name'     => $post_name,
			    				'country_name'       => $country_name,
								'state_name'     => $state_name,
								'password'      =>$password,
						    	'method'       => '_addEmployee',
						    );

						    $data_save = avul_call(API_URL.'employee/api/employee',$data);

						    if($data_save['status'] == 1)
						    {
						    	$response['status']  = 1;
						        $response['message'] = $data_save['message']; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
						    else
						    {
						    	$response['status']  = 0;
						        $response['message'] = $data_save['message']; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 	
						    }
			    		}
			    		else
			    		{
			    			$response['status']  = 0;
					        $response['message'] = 'Access denied'; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
			    		}
			    	}
			    	else
			    	{
			    		if(userAccess('employee-edit'))
			    		{
			    			$employee_id = $this->input->post('employee_id');
				    		$pstatus     = $this->input->post('pstatus');
							
							$data = array(
								
			    				'log_id'       => $log_id,
								'log_role'     => $log_role,
								'employee_id'  =>$employee_id,
						    	'f_name'       => ucfirst($f_name),
								'l_name'       => ucfirst($l_name),
						    	'mobile'       => $mobile,
						    	'email'        => $email,
						    	'address'      => $address,
						    	'password'       => $password,
						    	'dob'            => $dob,
						    	'company_type'   => 1,
						    	'company_id'     => 0,
								'o_educational_q'=> $o_educational_q,
								'pan_no'         => $pan_no,
								'aadhar_no'    => $aadhar_no,
								'gender'       => $gender,
								'father_n'     => $father_n,
								'mother_n'     => $mother_n,
								'account_name' => $account_name,
								'account_no'   => $account_no,
								'account_type' => $account_type,
								'ifsc_code'    => $ifsc_code,
								'bank_name'    => $bank_name,
								'branch_name'  => $branch_name,
								'Pincode'      => $Pincode,
								'street_name'  => $street_name,
								'door'         => $door,
								'grade_id'     => $grade_id,
						    	'status'       => $pstatus,
								'district_name' => $district_name,
								'post_name'     => $post_name,
			    				'country_name'  => $country_name,
								'state_name'    => $state_name,
								'password'      => $password,
								'login_status'  => $login_status,
						    	'method'        => '_updateEmployee',
						    );
							
						    $data_save = avul_call(API_URL.'employee/api/employee',$data);

						    if($data_save['status'] == 1)
						    {
						    	$response['status']  = 1;
						        $response['message'] = $data_save['message']; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
						    else
						    {
						    	$response['status']  = 0;
						        $response['message'] = $data_save['message']; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 	
						    }
			    		}
			    		else
			    		{
			    			$response['status']  = 0;
					        $response['message'] = 'Access denied'; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
			    		}
			    	}
			    }
			}else if($param1 == 'get_pin'){
				$pincode             = $this->input->post('pincode');
	
	
				
	
				// if($role_id==1){
					$wher = array(
						'search' =>$pincode ,
						'method' => '_getPinDetails'
					);
				
					$pin = avul_call(API_URL . 'employee/api/employee', $wher);
	
					$data_status   = $pin['status'];
            	    $pin_det = $pin['data'];

            	if($data_status == 1)
            	{
            		$option = array(
						'country_name'=> 'India',
	            		'c_pincode'     => $pin_det[0]['c_pincode'],
	            		'c_district' => $pin_det[0]['c_district'],
	            		'c_state'    => $pin_det[0]['c_state'],
	            	);

	            	$response['status']  = 1;
			        $response['message'] = 'success'; 
			        $response['data']    = $option;
			        echo json_encode($response);
			        return; 
            	}else{
					$response['status']  = 0;
			        $response['message'] = 'success';
					echo json_encode($response);
			        return; 
				}
				
			}else
			{
				if($param1 =='Edit')
				{
					$employee_id = !empty($param2)?$param2:'';

					$where = array(
	            		'employee_id' => $employee_id,
	            		'method'      => '_detailEmployee'
	            	);

	            	$data_list  = avul_call(API_URL.'employee/api/employee',$where);
					$data_value = $data_list['data'];
                    
                    $role_id        = !empty($data_value[0]['designation_code']) ? $data_value[0]['designation_code'] : '';

					$where_1 = array(

						'designation_id' => $role_id,
						'method'         => '_listDesignation',
					);
		
					$hierarchy_list = avul_call(API_URL . 'employee/api/employee_designation', $where_1);
					$hierarchy_value = !empty($hierarchy_list['data']) ? $hierarchy_list['data'] : '';
				
					$page['hierarchy']     = $hierarchy_value;

					$page['dataval']      = $data_list['data'];
					$page['method']       = 'BTBM_X_U';
					$page['page_title']   = "Edit Employee";
				}
				else
				{
					$where_1 = array(
						'method'         => '_listDesignation',
					);
		
					$hierarchy_list = avul_call(API_URL . 'employee/api/employee_designation', $where_1);
					$hierarchy_value	 = !empty($hierarchy_list['data']) ? $hierarchy_list['data'] : '';
		
					$page['hierarchy']     = $hierarchy_value;
					$page['dataval']      = '';
					$page['method']       = 'BTBM_X_C';
					$page['page_title']   = "Add Employee";
				}


				
			
				$page['main_heading'] = "Employee";
				$page['sub_heading']  = "Employee";
				$page['pre_title']    = "List Employee";
				$page['page_access']  = userAccess('employee-view');
				$page['pre_menu']     = "index.php/admin/employee/list_employee";
				$data['page_temp']    = $this->load->view('admin/employee/add_employee',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_employee";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function list_employee($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Employee";
				$page['sub_heading']  = "Employee";
				$page['page_title']   = "List Employee";
				$page['pre_title']    = "Add Employee";
				$page['page_access']  = userAccess('employee-add');
				$page['pre_menu']     = "index.php/admin/employee/add_employee";
				$data['page_temp']    = $this->load->view('admin/employee/list_employee',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_employee";
				$this->bassthaya->load_admin_form_template($data);
			}
			else if($param1 == 'data_list')
			{
				if(userAccess('employee-view'))
				{
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
					$sort_column = $this->input->post('sort_column');
					$sort_type   = $this->input->post('sort_type');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
	            		'offset'       => $_offset,
	            		'limit'        => $limit,
	            		'search'       => $search,
						'page'         => $page,
						'sort_column'  => $sort_column,
						'sort_type'    => $sort_type,
	            		'company_type' => 1,
	            		'company_id'   => 0,
						'ref_id'       => 0,
	            		'method'       => '_listEmployeePaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'employee/api/employee',$where);
	            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

	            	if(!empty($data_value))
	            	{

	            		$count    = count($data_value);
		            	$total    = isset($data_list['total_record'])?$data_list['total_record']:'';
		            	$tot_page = ceil($total / $limit); 

	            		$status  = 1;
		            	$message = 'Success';
		            	$table   = '';

		            	$i=1;
		            	foreach ($data_value as $key => $value) {
		            		$employee_id   = !empty($value['employee_id'])?$value['employee_id']:'';
						    $username      = !empty($value['username'])?$value['username']:'';
							$last_name      = !empty($value['last_name'])?$value['last_name']:'';
						    $mobile        = !empty($value['mobile'])?$value['mobile']:'';
						    $email         = !empty($value['email'])?$value['email']:'';
						    $pincode      = !empty($value['pincode'])?$value['pincode']:'';
							$street_name      = !empty($value['street_name'])?$value['street_name']:'';
							$door_no      = !empty($value['door_no'])?$value['door_no']:'';
						    $active_status = !empty($value['status'])?$value['status']:'';
						    $designation_code    = !empty($value['designation_code'])?$value['designation_code']:'';

						    if($active_status == '1')
			                {
			                	$status_view = '<span class="badge badge-success">Active</span>';
			                }
			                else
			                {
			                	$status_view = '<span class="badge badge-danger">InActive</span>';
			                }

			                // if($log_type == '1')
			                // {
			                // 	$type_view = '<span class="badge badge-warning">Deliveryman</span>';
			                // }
			                // else
			                // {
			                // 	$type_view = '<span class="badge badge-info">Salesman</span>';
			                // }

			                $edit   = '';
				            $delete = '';
				            if(userAccess('employee-edit') == TRUE)
				            {
				            	$edit = '<a href="'.BASE_URL.'index.php/admin/employee/add_employee/Edit/'.$employee_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
				            }
				            if(userAccess('employee-delete') == TRUE)
				            {
				            	$delete = '<a data-row="'.$i.'" data-id="'.$employee_id.'" data-value="admin" data-cntrl="employee" data-func="list_employee" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
				            }

						    $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.mb_strimwidth($username, 0, 15, '...').'</td>
	                                <td class="line_height">'.$mobile.'</td>
	                                <td class="line_height">'.$designation_code.'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                                if(userAccess('employee-edit') == TRUE || userAccess('employee-delete') == TRUE):
		                            	$table .= '<td>'.$edit.$delete.'</td>';
		                        	endif;
	                            $table .='</tr>
						    ';
						    $i++;
		            	}

		            	$prev    = '';

		            	$next = '
			        		<tr>
			        			<td>';
			        				if($cur_page >= 2):
			        				$next .='<span data-page="'.$pre_page.'" class="pages btn btn-warning waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>';
			        				endif;
			        			$next .= '</td>
			        			<td>';
			        				if($tot_page > $cur_page):
			        				$next .='<span data-page="'.$nxt_page.'" class="pages btn btn-success waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
			        				endif;
			        			$next .='</td>
			        		</tr>
			        	';
	            	} 
	            	else
	            	{
	            		$status     = 0;
		            	$message    = 'No Records';
		            	$table      = '';
		            	$next       = '';
		            	$prev       = '';
	            	}
				}
				else
		    	{
		    		$status     = 0;
		        	$message    = 'Access denied';
		        	$table      = '';
		        	$next       = '';
		        	$prev       = '';
		    	}

            	$response['status']     = $status;  
                $response['result']     = $table;  
                $response['message']    = $message;
                $response['next']       = $next;
                $response['prev']       = $prev;
                echo json_encode($response);
                return;
			}
			else if($param1 == 'delete')
			{
				$id = $this->input->post('id');

				if(!empty($id))	
				{
					$data = array(
						'log_id'      => $log_id,
						'log_role'    => $log_role,
				    	'employee_id' => $id,
				    	'method'      => '_deleteEmployee'
				    );

				    $data_save = avul_call(API_URL.'employee/api/employee',$data);

				    if($data_save['status'] == 1)
				    {
				    	$response['status']  = 1;
				        $response['message'] = $data_save['message']; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
				    else
				    {
				    	$response['status']  = 0;
				        $response['message'] = $data_save['message']; 
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
			        $response['error']   = []; 
			        echo json_encode($response);
			        return;
				}
			}
		}
		public function add_designation($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');
			$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$position_id     = $this->input->post('position_id');
				$designation_code       = $this->input->post('designation_code');
				$designation        = $this->input->post('designation');
				$method       = $this->input->post('method');

				$required = array('position_id', 'designation_code', 'designation');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error == TRUE)
			    {
			    	$response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        $response['error']   = []; 
			        echo json_encode($response);
			        return;
			    }
			    else
			    {
			    	if($method == 'BTBM_X_C')
			    	{
			    		if(userAccess('employee-add'))
			    		{
							$log_role = $this->session->userdata('user_role');
							$log_id = $this->session->userdata('id');
			    			$data = array(
								'log'               => 2,
								'log_id'            => $log_id,
								'log_role'          => $log_role,
						    	'position_id'       =>$position_id,
						    	'designation_code'  => strtoupper($designation_code),
						    	'designation'       => $designation,
						    	'method'            => '_addDesignation',
						    );

						    $data_save = avul_call(API_URL.'employee/api/employee_designation',$data);

						    if($data_save['status'] == 1)
						    {
						    	$response['status']  = 1;
						        $response['message'] = $data_save['message']; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
						    else
						    {
						    	$response['status']  = 0;
						        $response['message'] = $data_save['message']; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 	
						    }
			    		}
			    		else
			    		{
			    			$response['status']  = 0;
					        $response['message'] = 'Access denied'; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
			    		}
			    	}
			    	else
			    	{
			    		if(userAccess('employee-edit'))
			    		{
							$log_role = $this->session->userdata('user_role');
							$log_id = $this->session->userdata('id');
			    			$designation_id=$this->input->post('designation_id');
				    		$pstatus     = $this->input->post('pstatus');
				    		$data = array(
								'log'               => 2,
								'log_id'            => $log_id,
								'log_role'          => $log_role,
								'designation_id'    => $designation_id,
						    	'position_id'     	=> $position_id,
						    	'designation_code'  => strtoupper($designation_code),
						    	'designation'       => $designation,
						    	'status'       		=> $pstatus,
						    	'method'       		=> '_updateDesignation',
						    );

						    $data_save = avul_call(API_URL.'employee/api/employee_designation',$data);

						    if($data_save['status'] == 1)
						    {
						    	$response['status']  = 1;
						        $response['message'] = $data_save['message']; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
						    else
						    {
						    	$response['status']  = 0;
						        $response['message'] = $data_save['message']; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 	
						    }
			    		}
			    		else
			    		{
			    			$response['status']  = 0;
					        $response['message'] = 'Access denied'; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
			    		}
			    	}
			    }
			}
			else
			{
				if($param1 =='Edit')
				{
					$position_id = !empty($param2)?$param2:'';

					$where = array(
	            		'position_id' => $position_id,
	            		'method'      => '_detailDesignation'
	            	);

	            	$data_list  = avul_call(API_URL.'employee/api/employee_designation',$where);
					
					$data_p = !empty($data_list['data']) ? $data_list['data'] : '';
					$position_data =[];
					foreach($data_p as $key => $value){
						$position_data[]  = !empty($value['position_id'])?$value['position_id']:'';
								
					}
					
					$where_1 = array(


						'method'         => '_listDesignation',
					);
		
					$head_list = avul_call(API_URL . 'employee/api/employee_designation', $where_1);
					$data_value = !empty($head_list['data']) ? $head_list['data'] : '';
					$existing_data =[];
					foreach($data_value as $key => $value){
						$existing_data[]  = !empty($value['position_id'])?$value['position_id']:'';
								
					}
					
					
					 $position=array(1,2,3,4,5,6,7,8,9,10);
				   
			   
				   $main_zone=array();
				   $avai_position = array();
				   foreach($position as $value){
					   
				   
					   if(in_array($value,$existing_data )){
						 array_push($main_zone,$value);
				   
					   }else{
						 array_push($avai_position,$value);
					   };
				   }
				   foreach( $position_data as $value){
					array_unshift($avai_position,$value);
					}
				
				    $page['avai_position'] = $avai_position;
					$page['dataval']      = $data_list['data'];
					$page['method']       = 'BTBM_X_U';
					$page['page_title']   = "Edit Designation";
				}
				else
				{

					$where_1 = array(


						'method'         => '_listDesignation',
					);
		
					$head_list = avul_call(API_URL . 'employee/api/employee_designation', $where_1);
					$data_value = !empty($head_list['data']) ? $head_list['data'] : '';
					$position=array(1,2,3,4,5,6,7,8,9,10);
				if(!empty($data_value)){
					$existing_data =[];
					foreach($data_value as $key => $value){
						$existing_data[]  = !empty($value['position_id'])?$value['position_id']:'';
								
					}
					
					
					
				   
			   
				   $main_zone=array();
				   $avai_position = array();
				   foreach($position as $value){
					   
				   
					   if(in_array($value,$existing_data )){
						 array_push($main_zone,$value);
				   
					   }else{
						 array_push($avai_position,$value);
					   };
				   }
				}else{
					$avai_position=$position;
				}
				

					$page['avai_position'] = $avai_position;
				
					$page['method']       = 'BTBM_X_C';
					$page['page_title']   = "Add Designation";
				}


				
			
		  
		       
			    $page['grade_val']     = $data_value;
				$page['main_heading'] = "Designation";
				$page['sub_heading']  = "Designation";
				$page['pre_title']    = "List Designation";
				$page['page_access']  = userAccess('employee-view');
				$page['pre_menu']     = "index.php/admin/employee/list_designation";
				$data['page_temp']    = $this->load->view('admin/employee/add_designation',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_designation";
				$this->bassthaya->load_admin_form_template($data);
			}
		}
		public function list_designation($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Designation";
				$page['sub_heading']  = "Designation";
				$page['page_title']   = "List Designation";
				$page['pre_title']    = "Add Designation";
				$page['page_access']  = userAccess('employee-add');
				$page['pre_menu']     = "index.php/admin/employee/add_designation";
				$data['page_temp']    = $this->load->view('admin/employee/list_designation',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_designation";
				$this->bassthaya->load_admin_form_template($data);
			}
			else if($param1 == 'data_list')
			{
				if(userAccess('employee-view'))
				{
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
					$sort_column = $this->input->post('sort_column');
					$sort_type   = $this->input->post('sort_type');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
	            		'offset'       => $_offset,
	            		'limit'        => $limit,
	            		'search'       => $search,
						'sort_column' => $sort_column,
						'sort_type' => $sort_type,
	            		'method'       => '_listDesignationPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'employee/api/employee_designation',$where);
	            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

	            	if(!empty($data_value))
	            	{

	            		$count    = count($data_value);
		            	$total    = isset($data_list['total_record'])?$data_list['total_record']:'';
		            	$tot_page = ceil($total / $limit); 

	            		$status  = 1;
		            	$message = 'Success';
		            	$table   = '';

		            	$i=1;
		            	foreach ($data_value as $key => $value) {
		            		$designation_id   = !empty($value['designation_id'])?$value['designation_id']:'';
						    $position_id      = !empty($value['position_id'])?$value['position_id']:'';
						    $designation_name        = !empty($value['designation_name'])?$value['designation_name']:'';
						    $designation_code         = !empty($value['designation_code'])?$value['designation_code']:'';
						    $published      = !empty($value['log_type'])?$value['log_type']:'';
						    $active_status = !empty($value['status'])?$value['status']:'';
						    $createdate    = !empty($value['createdate'])?$value['createdate']:'';

						    if($active_status == '1')
			                {
			                	$status_view = '<span class="badge badge-success">Active</span>';
			                }
			                else
			                {
			                	$status_view = '<span class="badge badge-danger">In Active</span>';
			                }

			               

			                $edit   = '';
				            $delete = '';
				            if(userAccess('employee-edit') == TRUE)
				            {
				            	$edit = '<a href="'.BASE_URL.'index.php/admin/employee/add_designation/Edit/'.$position_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
				            }
				            if(userAccess('employee-delete') == TRUE)
				            {
				            	$delete = '<a data-row="'.$i.'" data-id="'.$position_id.'" data-value="admin" data-cntrl="employee" data-func="list_designation" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
				            }

						    $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.mb_strimwidth($designation_name, 0, 15, '...').'</td>
	                                <td class="line_height">'.$designation_code.'</td>
	                                <td class="line_height">'.$position_id.'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                                if(userAccess('employee-edit') == TRUE || userAccess('employee-delete') == TRUE):
		                            	$table .= '<td>'.$edit.$delete.'</td>';
		                        	endif;
	                            $table .='</tr>
						    ';
						    $i++;
		            	}

		            	$prev    = '';

		            	$next = '
			        		<tr>
			        			<td>';
			        				if($cur_page >= 2):
			        				$next .='<span data-page="'.$pre_page.'" class="pages btn btn-warning waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>';
			        				endif;
			        			$next .= '</td>
			        			<td>';
			        				if($tot_page > $cur_page):
			        				$next .='<span data-page="'.$nxt_page.'" class="pages btn btn-success waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
			        				endif;
			        			$next .='</td>
			        		</tr>
			        	';
	            	} 
	            	else
	            	{
	            		$status     = 0;
		            	$message    = 'No Records';
		            	$table      = '';
		            	$next       = '';
		            	$prev       = '';
	            	}
				}
				else
		    	{
		    		$status     = 0;
		        	$message    = 'Access denied';
		        	$table      = '';
		        	$next       = '';
		        	$prev       = '';
		    	}

            	$response['status']     = $status;  
                $response['result']     = $table;  
                $response['message']    = $message;
                $response['next']       = $next;
                $response['prev']       = $prev;
                echo json_encode($response);
                return;
			}
			else if($param1 == 'delete')
			{
				$id = $this->input->post('id');

				if(!empty($id))	
				{
					$data = array(
						
				    	'designation_id' => $id,
				    	'method'      => '_deleteDesignation'
				    );

				    $data_save = avul_call(API_URL.'employee/api/employee_designation',$data);

				    if($data_save['status'] == 1)
				    {
				    	$response['status']  = 1;
				        $response['message'] = $data_save['message']; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
				    else
				    {
				    	$response['status']  = 0;
				        $response['message'] = $data_save['message']; 
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
			        $response['error']   = []; 
			        echo json_encode($response);
			        return;
				}
			}
		}
	}
?>
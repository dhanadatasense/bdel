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

		public function add_employee($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
			$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');
			$formpage = $this->input->post('formpage');

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
				$required = array( 'door', 'street_name', 'pincode', 'f_name', 'l_name', 'branch_name', 'bank_name', 'ifsc_code', 'account_type', 'account_no', 'mother_n', 'account_name', 'father_n', 'gender', 'aadhar_no', 'o_educational_q', 'pan_no', 'dob','mobile', 'log_type');
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
							'company_type'   => 2,
							'company_id'     => $this->session->userdata('id'),
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
							'district_name'     => $district_name,
							'post_name'     => $post_name,
							'country_name'       => $country_name,
							'state_name'     => $state_name,
							'password'      =>$password,
							'grade_id'     => 0,
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
			    		$employee_id = $this->input->post('employee_id');
			    		$pstatus     = $this->input->post('pstatus');
						
			    		$data = array(
							'log_type'     => $log_type,
							'log_id'       => $log_id,
							'log_role'     => $log_role,
							'employee_id'  => $employee_id,
							'f_name'       => ucfirst($f_name),
							'l_name'       => ucfirst($l_name),
							'mobile'       => $mobile,
							'email'        => $email,
							'address'      => $address,
							'password'       => $password,
							'dob'            => $dob,
							'company_type'   => 2,
							'company_id'     => $this->session->userdata('id'),
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
            	}
				
			}
			else
			{
				if($param1 =='Edit')
				{
					$employee_id = !empty($param2)?$param2:'';

					$where = array(
	            		'employee_id' => $employee_id,
	            		'method'      => '_detailEmployee'
	            	);

	            	$data_list  = avul_call(API_URL.'employee/api/employee',$where);

					$page['dataval']      = $data_list['data'];
					$page['method']       = 'BTBM_X_U';
					$page['page_title']   = "Edit Employee";
				}
				else
				{
					$page['dataval']      = '';
					$page['method']       = 'BTBM_X_C';
					$page['page_title']   = "Add Employee";
				}

				$page['main_heading'] = "Employee";
				$page['sub_heading']  = "Employee";
				$page['pre_title']    = "List Employee";
				$page['pre_menu']     = "index.php/distributors/employee/list_employee";
				$data['page_temp']    = $this->load->view('distributors/employee/add_employee',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_employee";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		public function list_employee($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Employee";
				$page['sub_heading']  = "Employee";
				$page['page_title']   = "List Employee";
				$page['pre_title']    = "Add Employee";
				$page['pre_menu']     = "index.php/distributors/employee/add_employee";
				$data['page_temp']    = $this->load->view('distributors/employee/list_employee',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_employee";
				$this->bassthaya->load_distributors_form_template($data);
			}
			else if($param1 == 'data_list')
			{
                $limit    = $this->input->post('limitval');
            	$page     = $this->input->post('page');
            	$search   = $this->input->post('search');
            	$cur_page = isset($page)?$page:'1';
            	$_offset  = ($cur_page-1) * $limit;
            	$nxt_page = $cur_page + 1;
            	$pre_page = $cur_page - 1;

            	$where = array(
            		'offset'       => $_offset,
            		'limit'        => $limit,
            		'search'       => $search,

            		// 'company_type' => 2,
            		'ref_id'   => $this->session->userdata('id'),
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
					    $mobile        = !empty($value['mobile'])?$value['mobile']:'';
					    $email         = !empty($value['email'])?$value['email']:'';
					    $log_type      = !empty($value['log_type'])?$value['log_type']:'';
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

		                if($log_type == '1')
		                {
		                	$type_view = '<span class="badge badge-warning">Deliveryman</span>';
		                }
		                else
		                {
		                	$type_view = '<span class="badge badge-info">Salesman</span>';
		                }

					    $table .= '
					    	<tr class="row_'.$i.'">
                                <td class="line_height">'.$i.'</td>
                                <td class="line_height">'.mb_strimwidth($username, 0, 15, '...').'</td>
                                <td class="line_height">'.$mobile.'</td>
                                <td class="line_height">'.$type_view.'</td>
                                <td class="line_height">'.$status_view.'</td>
                                <td>
                                	<a href="'.BASE_URL.'index.php/distributors/employee/add_employee/Edit/'.$employee_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>
                                	<a data-row="'.$i.'" data-id="'.$employee_id.'" data-value="distributors" data-cntrl="employee" data-func="list_employee" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>
                                </td>
                            </tr>
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
	}
?>
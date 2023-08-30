<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Settings extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function company_settings($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');

			if($formpage =='BTBM_X_P')
			{
				$user_id      = $this->input->post('user_id');
				$address      = $this->input->post('address');
				$state_id     = $this->input->post('state_id');
				$city_id      = $this->input->post('city_id');
				$gst_no       = $this->input->post('gst_no');
				$pan_no       = $this->input->post('pan_no');
				// $fssai_no     = $this->input->post('fssai_no');

				$error = FALSE;
			    $errors = array();
				$required = array('user_id', 'address', 'state_id', 'city_id', 'gst_no');
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
			    	$data = array(
			    		'user_id'      => $user_id,
				    	'address'      => $address,
				    	'state_id'     => $state_id,
				    	'city_id'      => $city_id,
				    	'gst_no'       => $gst_no,
				    	'pan_no'       => $pan_no,
				    	// 'fssai_no'     => $fssai_no,
				    	'method'       => '_companySettings',
				    );

				    $data_save = avul_call(API_URL.'user/api/profile_settings',$data);

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

			else if($param1 =='getCity_name')
			{
				$state_id = $this->input->post('state_id');

				$where = array(
            		'state_id' => $state_id,
            		'method'   => '_listCity'
            	);

            	$city_list   = avul_call(API_URL.'master/api/city',$where);
            	$city_result = $city_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($city_result))
        		{
        			foreach ($city_result as $key => $value) {
        				$city_id   = !empty($value['city_id'])?$value['city_id']:'';
                        $city_name = !empty($value['city_name'])?$value['city_name']:'';

                        $option .= '<option value="'.$city_id.'">'.$city_name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
			}

			else
			{
				$user_id = $this->session->userdata('id');

				$where_1 = array(
            		'user_id' => $user_id,
            		'method'    => '_userDetails'
            	);

            	$data_list  = avul_call(API_URL.'user/api/profile_settings',$where_1);

            	$where_2 = array(
            		'method'    => '_listState'
            	);

            	$state_list  = avul_call(API_URL.'master/api/state',$where_2);

            	$state_id = !empty($data_list['data']['state_id'])?$data_list['data']['state_id']:'';

            	$where_3 = array(
            		'state_id'  => $state_id,
            		'method'    => '_listCity'
            	);

            	$city_list  = avul_call(API_URL.'master/api/city',$where_3);

				$page['dataval']      = $data_list['data'];
				$page['state_val']    = $state_list['data'];
				$page['city_val']     = $city_list['data'];
				$page['main_heading'] = "Settings";
				$page['sub_heading']  = "Settings";
				$page['pre_title']    = "Company Settings";
				$page['method']       = 'BTBM_X_C';
				$page['page_title']   = "Company Settings";
				$page['pre_menu']     = "index.php/admin/settings/company_settings";
				$data['page_temp']    = $this->load->view('admin/settings/company_settings',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "company_settings";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function profile_settings($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$user_id           = $this->input->post('user_id');
				$username          = $this->input->post('username');
				$email             = $this->input->post('email');
				$mobile            = $this->input->post('mobile');
				$stock_status      = $this->input->post('stock_status');
				$attendance_status = $this->input->post('attendance_status');
				$max_time          = $this->input->post('max_time');
				$distance_val      = $this->input->post('distance_val');
				$method            = $this->input->post('method');

				$required = array('user_id', 'username', 'email');
				if ($this->session->userdata('permission') == 1) {
					array_push($required, 'stock_status', 'attendance_status', 'distance_val');

					if ($attendance_status == 1) {
						array_push($required, 'max_time');
					}
				}
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
			    			'user_id'           => $user_id,
				    		'username'          => $username,
						    'mobile'            => $mobile,
						    'email'             => $email,
						    'stock_status'      => $stock_status,
						    'attendance_status' => $attendance_status,
						    'max_time'          => $max_time,
						    'distance_val'      => $distance_val,
						    'method'            => '_profileSettings',
				    	);

				    	$data_save = avul_call(API_URL.'user/api/profile_settings',$data);

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
			}
			else
			{
				$user_id = $this->session->userdata('id');

				$where = array(
            		'user_id' => $user_id,
            		'method'    => '_userDetails'
            	);

            	$data_list  = avul_call(API_URL.'user/api/profile_settings',$where);

				$page['dataval']      = $data_list['data'];
				$page['main_heading'] = "Settings";
				$page['sub_heading']  = "Settings";
				$page['pre_title']    = "Profile Settings";
				$page['method']       = 'BTBM_X_C';
				$page['page_title']   = "Profile Settings";
				$page['pre_menu']     = "index.php/admin/settings/profile_settings";
				$data['page_temp']    = $this->load->view('admin/settings/profile_settings',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "profile_settings";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function change_password($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			$formpage = $this->input->post('formpage');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$old_password     = $this->input->post('old_password');
				$password         = $this->input->post('password');
				$confirm_password = $this->input->post('confirm_password');
				$method           = $this->input->post('method');

				$required = array('old_password', 'password', 'confirm_password');
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
			    		$user_id = $this->session->userdata('id');

			    		$data = array(
			    			'user_id'          => $user_id,
				    		'old_password'     => $old_password,
						    'password'         => $password,
						    'confirm_password' => $confirm_password,
						    'method'           => '_changePassword',
				    	);

				    	$data_save = avul_call(API_URL.'user/api/profile_settings',$data);

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
			}
			else
			{
				$page['main_heading'] = "Settings";
				$page['sub_heading']  = "Settings";
				$page['pre_title']    = "Change Password";
				$page['method']       = 'BTBM_X_C';
				$page['page_title']   = "Change Password";
				$page['pre_menu']     = "index.php/admin/settings/change_password";
				$data['page_temp']    = $this->load->view('admin/settings/change_password',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "change_password";
				$this->bassthaya->load_admin_form_template($data);
			}
		}
	}
?>
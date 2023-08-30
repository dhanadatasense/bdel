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

		public function profile_settings($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');

			if($formpage =='BTBM_X_P')
			{
				$error        = FALSE;
				$company_name = $this->input->post('company_name');
			    $email        = $this->input->post('email');
			    $mobile       = $this->input->post('mobile');
			    $stock_status = $this->input->post('stock_status');
			    $account_name = $this->input->post('account_name');
			    $account_no   = $this->input->post('account_no');
			    $account_type = $this->input->post('account_type');
			    $ifsc_code    = $this->input->post('ifsc_code');
			    $bank_name    = $this->input->post('bank_name');
			    $branch_name  = $this->input->post('branch_name');
			    $address      = $this->input->post('address');
			    $user_id      = $this->input->post('user_id');
			    $method       = $this->input->post('method');

				$required = array('user_id', 'company_name', 'email', 'mobile', 'stock_status', 'address');
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
			    			'user_id'      => $user_id,
				    		'username'     => $company_name,
						    'email'        => $email,
						    'mobile'       => $mobile,
						    'stock_status' => $stock_status,
						    'account_name' => $account_name,
						    'account_no'   => $account_no,
						    'account_type' => $account_type,
						    'ifsc_code'    => $ifsc_code,
						    'bank_name'    => $bank_name,
						    'branch_name'  => $branch_name,
						    'address'      => $address,
						    'method'       => '_profileSettings',
				    	);

				    	$data_save = avul_call(API_URL.'distributors/api/profile_settings',$data);

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

            	$data_list  = avul_call(API_URL.'distributors/api/profile_settings',$where);

				$page['dataval']      = $data_list['data'];
				$page['main_heading'] = "Settings";
				$page['sub_heading']  = "Settings";
				$page['pre_title']    = "Profile Settings";
				$page['method']       = 'BTBM_X_C';
				$page['page_title']   = "Profile Settings";
				$page['pre_menu']     = "index.php/distributors/settings/profile_settings";
				$data['page_temp']    = $this->load->view('distributors/settings/profile_settings',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "profile_settings";
				$this->bassthaya->load_distributors_form_template($data);
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

				    	$data_save = avul_call(API_URL.'distributors/api/profile_settings',$data);

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
				$page['pre_menu']     = "index.php/distributors/settings/change_password";
				$data['page_temp']    = $this->load->view('distributors/settings/change_password',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "change_password";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}
	}
?>
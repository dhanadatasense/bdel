<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('encryption');
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->model('login_model');
	}

	public function index()
	{
		if(!empty(get_cookie('username')) && !empty(get_cookie('password')))
		{
			$page_data = array(
				'log_name' => get_cookie('username'),
				'log_pwrd' => get_cookie('password'),
				'log_rmbr' => get_cookie('remember'),
			);
		}
		else
		{
			$page_data = array(
				'log_name' => '',
				'log_pwrd' => '',
				'log_rmbr' => '1',
			);
		}

		$this->load->view('login', $page_data);
	}
	public function admin_login($param1="", $param2="", $param3="")
	{
		$username=$this->input->post('username');
		$password=$this->input->post('password');
		$remember_val = $this->input->post('remember_val');

		$formfield =array('username','password');
		$message ='';
        foreach ($formfield as $field) 
        {
    		if($this->input->post($field) == '')
    		{
    			$message = "Please fill required fields";
    		}
        } 
        if($message=="")
		{
			$userss = htmlspecialchars($username);
		    $passs  = htmlspecialchars($password);

		    $where = array(
	        	'username' => $userss,
	        	'password' => $passs,
	        	'method'   => '_adminLogin'
	        );

			$_user = avul_call(API_URL.'login/api/admin_login',$where);

			if($_user['status'] == 1)
		    {
		    	if($remember_val == 2)
		    	{
		    		$user_det = array('name' => 'username','value' => $userss,'expire' => 86400*30,'secure' => TRUE);
			    	$pwrd_det = array('name' => 'password','value' => $passs,'expire' => 86400*30,'secure' => TRUE);
			    	$rmbr_det = array('name' => 'remember','value' => $remember_val,'expire' => 86400*30,'secure' => TRUE);

			       	set_cookie($user_det);
			       	set_cookie($pwrd_det);
			       	set_cookie($rmbr_det);
		    	}
		    	else
		    	{
		    		delete_cookie('username');
			       	delete_cookie('password');
			       	delete_cookie('remember');
		    	}
		    	
		    	$user_data = $_user['data'];

		    	$where_1 = array(
                    'method'    => '_listFinancial'
                );

                $financial_list = avul_call(API_URL.'master/api/financial',$where_1);
                $financial_data = $financial_list['data'];

				$sessionData = array(
			        'random_value' => generateRandomString(32),
			        'active_year'  => !empty($financial_data[0]['financial_id'])?$financial_data[0]['financial_id']:'',
				);

	        	$this->session->set_userdata($sessionData);

	        	if($user_data['permission'] == 1 || $user_data['permission'] == 2)
	        	{
	        		$user_value = !empty($user_data)?$user_data:'';

	        		$admin_id   = !empty($user_value['id'])?$user_value['id']:'';
                    $username   = !empty($user_value['username'])?$user_value['username']:'';
                    $mobile     = !empty($user_value['mobile'])?$user_value['mobile']:'';
                    $email      = !empty($user_value['email'])?$user_value['email']:'';
                    $address    = !empty($user_value['address'])?$user_value['address']:'';
                    $state_id   = !empty($user_value['state_id'])?$user_value['state_id']:'';
                    $city_id    = !empty($user_value['city_id'])?$user_value['city_id']:'';
                    $gst_no     = !empty($user_value['gst_no'])?$user_value['gst_no']:'';
                    $user_role  = !empty($user_value['user_role'])?$user_value['user_role']:'';
                    $permission = !empty($user_value['permission'])?$user_value['permission']:'';
                    $role_list  = !empty($user_value['role_list'])?$user_value['role_list']:'';

	        		// Session Data
	        		$sessionData = array(
				        'id'               => $admin_id,
	                    'vendor_no'        => '',
	                    'company_name'     => $username,
	                    'gst_no'           => $gst_no,
	                    'user_role'        => $user_role,
	                    'contact_no'       => $mobile,
	                    'email'            => $email,
	                    'state_id'         => $state_id,
	                    'city_id'          => $city_id,
	                    'zone_id'          => '',
	                    'address'          => $address,
	                    'vendor_type'      => '',
	                    'distributor_id'   => '',
	                    'permission'       => $permission,
	                    'distributor_type' => '',
	                    'role_list'        => $role_list,
					);

		        	$this->session->set_userdata($sessionData);

	        		$response['status']  = 1;
			        $response['message'] = $_user['message']; 
			        $response['url']     = 'index.php/admin/dashboard/index';
			        $response['data']    = $sessionData;
			        echo json_encode($response);
			        return;
	        	}
	        	else if($user_data['permission'] == 3)
	        	{
	        		$user_value   = !empty($user_data)?$user_data:'';

	        		$vendor_id    = !empty($user_value['id'])?$user_value['id']:'';
                    $vendor_no    = !empty($user_value['vendor_no'])?$user_value['vendor_no']:'';
                    $company_name = !empty($user_value['company_name'])?$user_value['company_name']:'';
                    $gst_no       = !empty($user_value['gst_no'])?$user_value['gst_no']:'';
                    $contact_no   = !empty($user_value['contact_no'])?$user_value['contact_no']:'';
                    $email        = !empty($user_value['email'])?$user_value['email']:'';
                    $state_id     = !empty($user_value['state_id'])?$user_value['state_id']:'';
                    $city_id      = !empty($user_value['city_id'])?$user_value['city_id']:'';
                    $address      = !empty($user_value['address'])?$user_value['address']:'';
                    $vendor_type  = !empty($user_value['vendor_type'])?$user_value['vendor_type']:'';
                    $distri_id    = !empty($user_value['distributor_id'])?$user_value['distributor_id']:'';
                    $permission   = !empty($user_value['permission'])?$user_value['permission']:'';

	        		// Session Data
	        		$sessionData = array(
				        'id'               => $vendor_id,
	                    'vendor_no'        => $vendor_no,
	                    'company_name'     => $company_name,
	                    'gst_no'           => $gst_no,
	                    'user_role'        => '',
	                    'contact_no'       => $contact_no,
	                    'email'            => $email,
	                    'state_id'         => $state_id,
	                    'city_id'          => $city_id,
	                    'zone_id'          => '',
	                    'address'          => $address,
	                    'vendor_type'      => $vendor_type,
	                    'distributor_id'   => $distri_id,
	                    'permission'       => $permission,
	                    'distributor_type' => '2',
	                    'role_list'        => '',
					);

		        	$this->session->set_userdata($sessionData);

	        		$response['status']  = 1;
			        $response['message'] = $_user['message']; 
			        $response['url']     = 'index.php/vendors/dashboard/index';
			        $response['data']    = $sessionData;
			        echo json_encode($response);
			        return;
	        	}
	        	else if($user_data['permission'] == 4)
	        	{
	        		$user_value   = !empty($user_data)?$user_data:'';

	        		$distri_id   = !empty($user_value['id'])?$user_value['id']:'';
                    $com_name    = !empty($user_value['company_name'])?$user_value['company_name']:'';
                    $mobile      = !empty($user_value['mobile'])?$user_value['mobile']:'';
                    $email       = !empty($user_value['email'])?$user_value['email']:'';
                    $state_id    = !empty($user_value['state_id'])?$user_value['state_id']:'';
                    $city_id     = !empty($user_value['city_id'])?$user_value['city_id']:'';
                    $zone_id     = !empty($user_value['zone_id'])?$user_value['zone_id']:'';
                    $gst_no      = !empty($user_value['gst_no'])?$user_value['gst_no']:'';
                    $address     = !empty($user_value['address'])?$user_value['address']:'';
                    $pincode     = !empty($user_value['pincode'])?$user_value['pincode']:'';
                    $permission  = !empty($user_value['permission'])?$user_value['permission']:'';
                    $vendor_id   = !empty($user_value['vendor_id'])?$user_value['vendor_id']:'';
                    $distri_type = !empty($user_value['distributor_type'])?$user_value['distributor_type']:'';
                    $distri_sta  = !empty($user_value['distributor_status'])?$user_value['distributor_status']:'';

                    // Session Data
	        		$sessionData = array(
				        'id'                 => $distri_id,
	                    'vendor_no'          => '',
	                    'company_name'       => $com_name,
	                    'gst_no'             => $gst_no,
	                    'user_role'          => '',
	                    'contact_no'         => $mobile,
	                    'email'              => $email,
	                    'state_id'           => $state_id,
	                    'city_id'            => $city_id,
	                    'zone_id'            => $zone_id,
	                    'address'            => $address,
	                    'vendor_type'        => '',
	                    'distributor_id'     => '',
	                    'permission'         => $permission,
	                    'vendor_id'          => $vendor_id,
	                    'distributor_type'   => '1',
	                    'distributor_status' => $distri_sta,
	                    'role_list'          => '',
					);

		        	$this->session->set_userdata($sessionData);

	        		$response['status']  = 1;
			        $response['message'] = $_user['message']; 
			        $response['url']     = 'index.php/distributors/dashboard/index';
			        $response['data']    = $sessionData;
			        echo json_encode($response);
			        return;
	        	}
				else if($user_data['permission'] == 5)
	        	{
	        		$user_value   = !empty($user_data)?$user_data:'';

	        		$manager_id   = !empty($user_value['id'])?$user_value['id']:'';
                    $mobile      = !empty($user_value['mobile'])?$user_value['mobile']:'';
                    $email       = !empty($user_value['email'])?$user_value['email']:'';
                    $pincode     = !empty($user_value['pincode'])?$user_value['pincode']:'';
                    $permission  = !empty($user_value['permission'])?$user_value['permission']:'';
                    $position_id       = !empty($user_value['position_id'])?$user_value['position_id']:'';
					$designation_code       = !empty($user_value['designation_code'])?$user_value['designation_code']:'';

                    // Session Data
	        		$sessionData = array(
				        'id'                 => $manager_id,
	                    'contact_no'         => $mobile,
	                    'email'              => $email,
	                    'pincode'            => $pincode,
	                    'permission'         => $permission,
	                    'position_id'        => $position_id,
						'designation_code'   => $designation_code
					);

		        	$this->session->set_userdata($sessionData);

					

	        		$response['status']  = 1;
			        $response['message'] = $_user['message']; 
			        $response['url']     = 'index.php/managers/dashboard/index';
			        $response['data']    = $sessionData;
			        echo json_encode($response);
			        return;
	        	}
		    }
		    else
		    {
		    	$response['status']  = 0;
		        $response['message'] = $_user['message']; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return; 	
		    }
		}
		else
		{
			$response['message']="Please fill fields required";
			echo json_encode($response);
			return;
		}
	}

	// Active Academic Year
	public function active_academic($param1="", $param2="", $param3="")
	{
    	if($this->input->post('method') == 'add')
		{
			$active_acad = $this->input->post('active_acad');

			$sessionSet = array(
				'active_year'  => isset($active_acad)?$active_acad:''
			);

	        $set = $this->session->set_userdata($sessionSet);

        	if($active_acad != 0)
        	{
        		$response['status']  = 1;
    			$response['message'] = "Financial Year Switch Successfully";
    			echo json_encode($response);
    			return;
        	}
        	else
        	{
    			$response['status']  = 0;
    			$response['message'] = "Oops! Can't Created";
    			echo json_encode($response);
    			return;
        	}
		}
	}

	public function logout()
    {   
        $this->session->sess_destroy();
        redirect(base_url() . 'index.php?login', 'refresh');
    }
}

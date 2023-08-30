<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Login extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('login_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Count List
		// ***************************************************
		public function admin_login($param1="", $param2="", $param3="")
		{
			$method   = $this->input->post('method');
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			if($method == '_adminLogin')
			{
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
			        $response['message'] = "Please fill all required fields"; 
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

			        $column = 'username, mobile, email, address, gst_no';
			        $result = $this->login_model->getLoginStatus($where, '', '', 'row', '', '', '', '', $column);

			        if($result)
			        {
			        	if(strcmp($result->email, $username) == 0)
			        	{
			        		$data[] = array(
			        			'username' => empty_check($result->username),
								'mobile'   => empty_check($result->mobile),
								'email'    => empty_check($result->email),
								'address'  => empty_check($result->address),
								'gst_no'   => empty_check($result->gst_no),
			        		);

			        		$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $data;
					        echo json_encode($response);
					        return;
			        	}
			        	else
				        {
				        	$response['status']  = 0;
					        $response['message'] = "invalid login details"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
				        }
			        }
			        else
			        {
			        	$response['status']  = 0;
				        $response['message'] = "invalid login details"; 
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
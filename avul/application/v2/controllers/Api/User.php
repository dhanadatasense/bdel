<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class User extends CI_Controller {

		public function __construct()
		{
			parent::__construct();

			$this->load->model('commom_model');
			$this->load->model('distributors_model');
			$this->load->model('vendors_model');
			$this->load->model('user_model');
		}

		public function index()
		{
			echo "Test";
		}

		// user
		// ***************************************************
		public function user($param1="",$param2="",$param3="")
		{

			$method   = $this->input->post('method');
			$log_id   = $this->input->post('log_id');
			$log_role = $this->input->post('log_role');

			if($method == '_addUser')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('email', 'user_role', 'address', 'password');
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

			    if($this->input->post('mobile'))
			    {
			        if (preg_match('#[^0-9]#', $this->input->post('mobile')) || strlen($this->input->post('mobile'))!=10)
			        {
			            $response['status']  = 0;
				        $response['message'] = "Mobile No. does not appear to be valid"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			        }
			    }

			    if(count($errors)==0)
			    {
			    	$mobile    = $this->input->post('mobile');
			    	$email     = $this->input->post('email');
			    	$user_role = $this->input->post('user_role');
			    	$address   = $this->input->post('address');
			    	$password  = $this->input->post('password');

			    	$where_1 = array(
				    	'email'    => $email,
				    	'status'   => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->user_model->getUser($where_1, '', '', 'result', '', '', '', '', $column);

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
						$where_2 = array(
							'email'  => $email,
					    	'status' => '1',
					    );

						$find_datas = $this->distributors_model->getDistributors($where_2, '', '', 'result', '', '', '', '', $column);

						if(!empty($find_datas))
						{
							$response['status']  = 0;
					        $response['message'] = "Data Already Exist"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
						}
						else
						{
							$where_3 = array(
								'email'  => $email,
						    	'status' => '1',
						    );

							$find_datas = $this->vendors_model->getVendors($where_3, '', '', 'result', '', '', '', '', $column);

							if(!empty($find_datas))
							{
								$response['status']  = 0;
						        $response['message'] = "Data Already Exist"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
							}
							else
							{
								$data = array(
							    	'email'      => $email,
							    	'mobile'     => $mobile,
							    	'user_role'  => $user_role,
							    	'password'   => $password,
							    	'address'    => $address,
							    	'createdate' => date('Y-m-d H:i:s')
							    );

							    $insert = $this->user_model->user_insert($data);

							    $log_data = array(
									'u_id'       => $log_id,
									'role'       => $log_role,
									'table'      => 'tbl_user',
									'auto_id'    => $insert,
									'action'     => 'create',
									'date'       => date('Y-m-d'),
									'time'       => date('H:i:s'),
									'createdate' => date('Y-m-d H:i:s')
								);

								$log_val = $this->commom_model->log_insert($log_data);

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
			    }
			}

			else if($method == '_listUserPaginate')
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

				$where = array('A.permission'=>'2', 'A.published'=>'1');

				$search = $this->input->post('search');
	    		if($search !='')
	    		{
	    			$like['name'] = $search;
	    		}
	    		else
	    		{
	    			$like = [];
	    		}

	    		$column = 'A.id';
				$overalldatas = $this->user_model->getUserJoin($where, '', '', 'result', $like, '', '', '', $column);

				if($overalldatas)
				{
					$totalc = count($overalldatas);
				}
				else
				{
					$totalc = 0;
				}

				$option['order_by']   = 'A.id';
				$option['disp_order'] = 'DESC';

				$column    = 'A.id, A.username, A.mobile, A.email, B.role_name, A.status, A.createdate';
				$data_list = $this->user_model->getUserJoin($where, $limit, $offset, 'result', $like, '', $option, '', $column);

				if($data_list)
				{
					$user_list = [];

					foreach ($data_list as $key => $value) {

						$user_id    = isset($value->id)?$value->id:'';
			            $username   = isset($value->username)?$value->username:'';
			            $mobile     = isset($value->mobile)?$value->mobile:'';
			            $email      = isset($value->email)?$value->email:'';
			            $user_role  = isset($value->role_name)?$value->role_name:'';
			            $status     = isset($value->status)?$value->status:'0';
			            $createdate = isset($value->createdate)?$value->createdate:'';

			            $user_list[] = array(
          					'user_id'      => $user_id,
				            'username'     => $username,
				            'mobile'       => $mobile,
				            'email'        => $email,
				            'user_role'    => $user_role,
				            'status'       => $status,
				            'createdate'   => $createdate,
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
			        $response['data']         = $user_list;
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

			else if($method == '_detailUser')
			{
				$user_id = $this->input->post('user_id');

		    	if(!empty($user_id))
		    	{

		    		$where = array('id'=>$user_id);
				    $data  = $this->user_model->getUser($where);
				    if($data)
				    {	

				    	$user_list = [];

						foreach ($data as $key => $value) {

				            $user_id    = isset($value->id)?$value->id:'';
				            $username   = isset($value->username)?$value->username:'';
				            $mobile     = isset($value->mobile)?$value->mobile:'';
				            $email      = isset($value->email)?$value->email:'';
				            $address    = isset($value->address)?$value->address:'';
				            $password   = isset($value->password)?$value->password:'';
				            $user_role  = isset($value->user_role)?$value->user_role:'';
				            $published  = isset($value->published)?$value->published:'';
				            $status     = isset($value->status)?$value->status:'';
				            $createdate = isset($value->createdate)?$value->createdate:'';

				            $user_list[] = array(
	          					'user_id'    => $user_id,
					            'username'   => $username,
					            'mobile'     => $mobile,
					            'email'      => $email,
					            'address'    => $address,
					            'password'   => $password,
					            'user_role'  => $user_role,
					            'published'  => $published,
					            'status'     => $status,
					            'createdate' => $createdate,
	          				);
						}

	        			$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $user_list;
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

			else if($method == '_listUser')
			{
				$where = array('permission'=>'2', 'status'=>'1', 'published'=>'1');

				$data_list = $this->user_model->getUser($where);

				if($data_list)
				{
					$user_list = [];

					foreach ($data_list as $key => $value) {

						$user_id    = isset($value->id)?$value->id:'';
			            $username   = isset($value->username)?$value->username:'';
			            $mobile     = isset($value->mobile)?$value->mobile:'';
			            $email      = isset($value->email)?$value->email:'';
			            $published  = isset($value->published)?$value->published:'';
			            $status     = isset($value->status)?$value->status:'';
			            $createdate = isset($value->createdate)?$value->createdate:'';

			            $user_list[] = array(
          					'user_id'    => $user_id,
				            'username'   => $username,
				            'mobile'     => $mobile,
				            'email'      => $email,
				            'published'  => $published,
				            'status'     => $status,
				            'createdate' => $createdate,
          				);
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $user_list;
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

			else if($method == '_updateUser')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('id', 'email', 'user_role', 'address', 'password');
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

			    if($this->input->post('mobile'))
			    {
			        if (preg_match('#[^0-9]#', $this->input->post('mobile')) || strlen($this->input->post('mobile'))!=10)
			        {
			            $response['status']  = 0;
				        $response['message'] = "Mobile No. does not appear to be valid"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			        }
			    }

			    if(count($errors)==0)
			    {	
			    	$user_id   = $this->input->post('id');
			    	$mobile    = $this->input->post('mobile');
			    	$email     = $this->input->post('email');
			    	$user_role = $this->input->post('user_role');
			    	$address   = $this->input->post('address');
			    	$password  = $this->input->post('password');
			    	$status    = $this->input->post('status');

			    	$where_1=array(
			    		'id !='    => $user_id,
				    	'email'    => $email,
				    	'status'   => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->user_model->getUser($where_1, '', '', 'result', '', '', '', '', $column);

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
						$where_2=array(
							'email'  => $email,
					    	'status' => '1',
					    );

						$find_datas = $this->distributors_model->getDistributors($where_2, '', '', 'result', '', '', '', '', $column);

						if(!empty($find_datas))
						{
							$response['status']  = 0;
					        $response['message'] = "Data Already Exist"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
						}
						else
						{	
							$where_3=array(
								'email'  => $email,
						    	'status' => '1',
						    );

							$find_datas = $this->vendors_model->getVendors($where_3, '', '', 'result', '', '', '', '', $column);

							if(!empty($find_datas))
							{
								$response['status']  = 0;
						        $response['message'] = "Data Already Exist"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
							}
							else
							{
								$data = array(
							    	'email'      => $email,
							    	'mobile'     => $mobile,
							    	'user_role'  => $user_role,
							    	'address'    => $address,
							    	'password'   => $password,
							    	'status'     => $status,
							    	'updatedate' => date('Y-m-d H:i:s')
							    );

					    		$update_id  = array('id'=>$user_id);
							    $update = $this->user_model->user_update($data, $update_id);

							    $log_data = array(
									'u_id'       => $log_id,
									'role'       => $log_role,
									'table'      => 'tbl_user',
									'auto_id'    => $user_id,
									'action'     => 'update',
									'date'       => date('Y-m-d'),
									'time'       => date('H:i:s'),
									'createdate' => date('Y-m-d H:i:s')
								);

								$log_val = $this->commom_model->log_insert($log_data);

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
			    }
			}

			else if($method == '_deleteUser')
			{
		    	$user_id  = $this->input->post('user_id');
		    	$login_id = $this->input->post('login_id');

		    	if(!empty($user_id) && !empty($login_id))
		    	{
		    		if($user_id != 1)
		    		{
		    			$data=array(
					    	'status'     => '0',
					    	'published'  => '0',
					    	'deleted_by' => $login_id,
					    	'deletedate' => date('Y-m-d H:i:s'),
					    );

			    		$where  = array('id'=>$user_id);
					    $update = $this->user_model->user_delete($data, $where);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_user',
							'auto_id'    => $user_id,
							'action'     => 'delete',
							'date'       => date('Y-m-d'),
							'time'       => date('H:i:s'),
							'createdate' => date('Y-m-d H:i:s')
						);

						$log_val = $this->commom_model->log_insert($log_data);

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

		// Profile Update
		// ***************************************************
		public function profile_update($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == 'profile_update')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('id', 'password', 'confirm_password');
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
			    	$user_id    = $this->input->post('id');
			    	$password   = $this->input->post('password');
			    	$c_password = $this->input->post('confirm_password');

			    	if($password == $c_password)
			    	{
			    		$data = array(
							'password'   => $password,
					    	'updatedate' => date('Y-m-d H:i:s')
					    );

			    		$update_id  = array('id'=>$user_id);
					    $update = $this->user_model->user_update($data, $update_id);
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
				        $response['message'] = "Password is mismatch"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
			    	}
			    }
			}

			else if($method == 'company_update')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('id', 'address', 'state_id', 'city_id', 'gst_no', 'pan_no');
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
			    	$user_id  = $this->input->post('id');
			    	$address  = $this->input->post('address');
			    	$state_id = $this->input->post('state_id');
			    	$city_id  = $this->input->post('city_id');
			    	$gst_no   = $this->input->post('gst_no');
			    	$pan_no   = $this->input->post('pan_no');

		    		$data = array(
						'address'    => $address,
						'state_id'   => $state_id,
						'city_id'    => $city_id,
						'gst_no'     => $gst_no,
						'pan_no'     => $pan_no,
				    	'updatedate' => date('Y-m-d H:i:s')
				    );

		    		$update_id  = array('id'=>$user_id);
				    $update = $this->user_model->user_update($data, $update_id);
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

			else
			{
				$response['status']  = 0;
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// Profile Settings
		// ***************************************************
		public function profile_settings($param1="",$param2="",$param3="")
		{
			$user_id  = $this->input->post('user_id');

			$username          = $this->input->post('username');
			$mobile            = $this->input->post('mobile');
			$email             = $this->input->post('email');
			$stock_status      = $this->input->post('stock_status');
			$attendance_status = $this->input->post('attendance_status');
			$max_time          = $this->input->post('max_time');
			$distance_val      = $this->input->post('distance_val');

			$company_name = $this->input->post('company_name');
			$address      = $this->input->post('address');
			$state_id     = $this->input->post('state_id');
			$city_id      = $this->input->post('city_id');
			$gst_no       = $this->input->post('gst_no');
			$pan_no       = $this->input->post('pan_no');
			$fssai_no     = $this->input->post('fssai_no');

			$old_password = $this->input->post('old_password');
			$password     = $this->input->post('password');
			$c_password   = $this->input->post('confirm_password');

			$method = $this->input->post('method');

			if($method == '_userDetails')
			{
				$user_id = $this->input->post('user_id');

				if(!empty($user_id))
				{
					$where = array('id' => $user_id);
					$data  = $this->user_model->getUser($where);

					if($data)
					{
						$user_list = [];

						foreach ($data as $key => $value) {
							$user_id      = isset($value->id)?$value->id:'';
						    $username     = isset($value->username)?$value->username:'';
						    $mobile       = isset($value->mobile)?$value->mobile:'';
						    $email        = isset($value->email)?$value->email:'';
						    $company_name = isset($value->company_name)?$value->company_name:'';
						    $address      = isset($value->address)?$value->address:'';
						    $state_id     = isset($value->state_id)?$value->state_id:'';
						    $city_id      = isset($value->city_id)?$value->city_id:'';
						    $gst_no       = isset($value->gst_no)?$value->gst_no:'';
						    $pan_no       = isset($value->pan_no)?$value->pan_no:'';
						    $fssai_no     = isset($value->fssai_no)?$value->fssai_no:'';
						    $password     = isset($value->password)?$value->password:'';
						    $permission   = isset($value->permission)?$value->permission:'';
						    $stock_status = isset($value->stock_status)?$value->stock_status:'';
						    $att_status   = isset($value->attendance_status)?$value->attendance_status:'';
						    $max_time     = isset($value->max_time)?$value->max_time:'';
						    $distance_val = isset($value->distance_val)?$value->distance_val:'0';
						    $status       = isset($value->status)?$value->status:'';
						    $published    = isset($value->published)?$value->published:'';

						    $user_list  = array(
						    	'user_id'           => $user_id,
							    'username'          => $username,
							    'mobile'            => $mobile,
							    'email'             => $email,
							    'company_name'      => $company_name,
							    'address'           => $address,
							    'state_id'          => $state_id,
							    'city_id'           => $city_id,
							    'gst_no'            => $gst_no,
							    'pan_no'            => $pan_no,
							    'fssai_no'          => $fssai_no,
							    'password'          => $password,
							    'permission'        => $permission,
							    'stock_status'      => $stock_status,
							    'attendance_status' => $att_status,
							    'max_time'          => $max_time,
							    'distance_val'      => $distance_val,
							    'status'            => $status,
							    'published'         => $published,
						    ); 
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $user_list;
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

			if($method == '_companySettings')
			{
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
				    	'address'      => $address,
				    	'state_id'     => $state_id,
				    	'city_id'      => $city_id,
				    	'gst_no'       => $gst_no,
				    	'pan_no'       => $pan_no,
				    	// 'fssai_no'     => $fssai_no,
				    	'updatedate'   => date('Y-m-d H:i:s')
				    );

				    $update_id = array('id' => $user_id);

				    $update    = $this->user_model->user_update($data, $update_id);
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

			else if($method == '_profileSettings')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('user_id', 'username', 'email');
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
				    	'username'          => $username,
				    	'mobile'            => $mobile,
				    	'email'             => $email, 
				    	'stock_status'      => $stock_status,
					    'attendance_status' => $attendance_status,
					    'max_time'          => $max_time,
					    'distance_val'      => $distance_val,
				    	'updatedate'        => date('Y-m-d H:i:s')
				    );

				    $update_id = array('id' => $user_id);

				    $update    = $this->user_model->user_update($data, $update_id);
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

			else if($method == '_changePassword')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('user_id', 'old_password', 'password', 'confirm_password');
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
			    	$where = array('id' => $user_id);
					$data  = $this->user_model->getUser($where);

					$previous_password = isset($data[0]->password)?$data[0]->password:'';

					if($old_password == $previous_password)
					{
						if($password == $c_password)
						{
							$data = array(
					    		'password'   => $password,
							    'updatedate' => date('Y-m-d H:i:s'),
					    	);

					    	$update_id = array('id' => $user_id);

					    	$update    = $this->user_model->user_update($data, $update_id);
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
					        $response['message'] = "New Password is mismatch"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
				    	}
					}
					else
					{
						$response['status']  = 0;
				        $response['message'] = "Old Password is mismatch"; 
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

		// user role
		// ***************************************************
		public function user_role($param1="",$param2="",$param3="")
		{
			$method   = $this->input->post('method');
			$log_id   = $this->input->post('log_id');
			$log_role = $this->input->post('log_role');

			if($method == '_addUserRole')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('role_name', 'role_heading', 'role_list', 'login_id');
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
			    	$role_name    = $this->input->post('role_name');
			    	$role_heading = $this->input->post('role_heading');
			    	$role_list    = $this->input->post('role_list');
			    	$login_id     = $this->input->post('login_id');

			    	$where_1 = array(
				    	'role_name' => ucfirst($role_name),
				    	'status'    => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->user_model->getUserRole($where_1, '', '', 'result', '', '', '', '', $column);

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
						$data = array(
					    	'role_name'    => ucfirst($role_name),
					    	'role_heading' => $role_heading,
							'role_list'    => $role_list,
							'created_by'   => $login_id,
							'createdate'   => date('Y-m-d H:i:s'),
					    );

					    $insert = $this->user_model->userRole_insert($data);

					    $log_data = array(
							'u_id'       => $log_id,	
							'role'       => $log_role,
							'table'      => 'tbl_user_role',
							'auto_id'    => $insert,
							'action'     => 'create',
							'date'       => date('Y-m-d'),
							'time'       => date('H:i:s'),
							'createdate' => date('Y-m-d H:i:s')
						);

						$log_val = $this->commom_model->log_insert($log_data);

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

			else if($method == '_listUserRolePaginate')
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
				$where  = array('id !=' => '1', 'published'=>'1');

	    		if($search !='')
	    		{
	    			$like['name'] = $search;
	    		}
	    		else
	    		{
	    			$like = [];
	    		}

	    		$column = 'id';
				$overalldatas = $this->user_model->getUserRole($where, '', '', 'result', $like, '', '', '', $column);

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

				$column    = 'id, role_name, status, createdate';
				$data_list = $this->user_model->getUserRole($where, $limit, $offset, 'result', $like, '', $option, '', $column);

				if($data_list)
				{
					$user_list = [];
					foreach ($data_list as $key => $value) {

						$role_id    = empty_check($value->id);
			            $role_name  = empty_check($value->role_name);
			            $status     = empty_check($value->status);
			            $createdate = empty_check($value->createdate);

			            $user_list[] = array(
          					'role_id'    => $role_id,
				            'role_name'  => $role_name,
				            'status'     => $status,
				            'createdate' => $createdate,
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
			        $response['data']         = $user_list;
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

			else if($method == '_listUserRole')
			{
				$where = array('id !=' => '1', 'status'=>'1', 'published'=>'1');

				$column    = 'id, role_name, status, createdate';
				$data_list = $this->user_model->getUserRole($where, '', '', 'result', '', '', '', '', $column);

				if($data_list)
				{
					$user_list = [];
					foreach ($data_list as $key => $value) {

						$role_id    = empty_check($value->id);
			            $role_name  = empty_check($value->role_name);
			            $status     = empty_check($value->status);
			            $createdate = empty_check($value->createdate);

			            $user_list[] = array(
          					'role_id'    => $role_id,
				            'role_name'  => $role_name,
				            'status'     => $status,
				            'createdate' => $createdate,
          				);
					}

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $user_list;
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

			else if($method == '_detailUserRole')
			{
				$role_id = $this->input->post('role_id');

		    	if(!empty($role_id))
		    	{
		    		$where  = array('id'=>$role_id);
		    		$column = 'id, role_name, role_heading, role_list, status';
				    $data   = $this->user_model->getUserRole($where, '', '', 'row', '', '', '', '', $column);

				    if($data)
				    {
				    	$role_id      = empty_check($data->id);
			            $role_name    = empty_check($data->role_name);
			            $role_heading = empty_check($data->role_heading);
			            $role_list    = empty_check($data->role_list);
			            $status       = empty_check($data->status);

			            $role_data = array(
	      					'role_id'      => $role_id,
				            'role_name'    => $role_name,
				            'role_heading' => $role_heading,
				            'role_list'    => $role_list,
				            'status'       => $status,
	      				);

	      				$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $role_data;
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

			else if($method == '_updateUserRole')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('role_id', 'role_name', 'role_heading', 'role_list', 'login_id');
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
			    	$role_id      = $this->input->post('role_id');
			    	$role_name    = $this->input->post('role_name');
			    	$role_heading = $this->input->post('role_heading');
			    	$role_list    = $this->input->post('role_list');
			    	$login_id     = $this->input->post('login_id');
			    	$status       = $this->input->post('status');

			    	$where=array(
			    		'id !='     => $role_id,
				    	'role_name' => $role_name,
				    	'status'    => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->user_model->getUserRole($where, '', '', 'result', '', '', '', '', $column);

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
						$data = array(
					    	'role_name'    => ucfirst($role_name),
					    	'role_heading' => $role_heading,
							'role_list'    => $role_list,
							'status'       => $status,
							'updated_by'   => $login_id,
							'updatedate'   => date('Y-m-d H:i:s'),
					    );

					    $where  = array('id' => $role_id);
				    	$update = $this->user_model->userRole_update($data, $where);

				    	$log_data = array(
							'u_id'       => $log_id,	
							'role'       => $log_role,
							'table'      => 'tbl_user_role',
							'auto_id'    => $role_id,
							'action'     => 'update',
							'date'       => date('Y-m-d'),
							'time'       => date('H:i:s'),
							'createdate' => date('Y-m-d H:i:s')
						);

						$log_val = $this->commom_model->log_insert($log_data);

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

			else if($method == '_deleteUserRole')
			{
				$role_id  = $this->input->post('role_id');
				$login_id = $this->input->post('login_id');

				if(!empty($role_id) && !empty($login_id))
				{
					$data=array(
				    	'status'     => '0',
				    	'published'  => '0',
				    	'deleted_by' => $login_id,
				    	'deletedate' => date('Y-m-d H:i:s'),
				    );

		    		$where  = array('id' => $role_id);
				    $update = $this->user_model->userRole_update($data, $where);

				    $log_data = array(
						'u_id'       => $log_id,	
						'role'       => $log_role,
						'table'      => 'tbl_user_role',
						'auto_id'    => $role_id,
						'action'     => 'delete',
						'date'       => date('Y-m-d'),
						'time'       => date('H:i:s'),
						'createdate' => date('Y-m-d H:i:s')
					);

					$log_val = $this->commom_model->log_insert($log_data);

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
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
		    	}
			}
		}
	}
?>
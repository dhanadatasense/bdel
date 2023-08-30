<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class User extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function add_role($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$login_id = $this->session->userdata('id');
			$formpage = $this->input->post('formpage');
			$method   = $this->input->post('method');
			$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');
	

			if($formpage =='BTBM_X_P')
			{
				$role_name   = $this->input->post('role_name');
				$heading_val = $this->input->post('heading_val');
				$check_val   = $this->input->post('check_val');

				$heading_res = '';
				if(!empty($heading_val))
		    	{
		    		$heading_res = implode(',', $heading_val);
		    	}

		    	$check_res = '';
		    	if(!empty($check_val))
		    	{
		    		$check_res = implode(',', $check_val);
		    	}

		    	$error = FALSE;

		    	$required = array('role_name');
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
			    		if(userAccess('user-role-add'))
			    		{
			    			$data = array(
			    				'log_id'       => $log_id,
								'log_role'     => $log_role,
						    	'role_name'    => ucfirst($role_name),
						    	'role_heading' => $heading_res,
								'role_list'    => $check_res,
								'login_id'     => $login_id,
								'createdate'   => date('Y-m-d H:i:s'),
								'method'       => '_addUserRole',
						    );

						    $data_save = avul_call(API_URL.'user/api/user_role',$data);

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
			    		if(userAccess('user-role-edit'))
			    		{
			    			$role_id = $this->input->post('role_id');
				    		$pstatus = $this->input->post('pstatus');

				    		$data = array(
				    			'log_id'       => $log_id,
								'log_role'     => $log_role,	
				    			'role_id'      => $role_id,
						    	'role_name'    => ucfirst($role_name),
						    	'role_heading' => $heading_res,
								'role_list'    => $check_res,
								'login_id'     => $login_id,
								'status'       => $pstatus,
								'method'       => '_updateUserRole',
						    );

						    $data_save = avul_call(API_URL.'user/api/user_role',$data);

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
					$where_1 = array(
						'role_id'  => $param2,
	            		'method'   => '_detailUserRole',
	            	);

	            	$data_list = avul_call(API_URL.'user/api/user_role',$where_1);

					$page['dataval']    = $data_list['data'];
					$page['method']     = 'BTBM_X_U';
					$page['page_title'] = "Edit User Role";
				}
				else
				{
					$page['dataval']    = '';
					$page['method']     = 'BTBM_X_C';
					$page['page_title'] = "Add User Role";
				}

				$where_2 = array(
            		'method'   => '_listPrivilege'
            	);

            	$role_data = avul_call(API_URL.'master/api/privilege',$where_2);
            	$role_list = !empty($role_data['data'])?$role_data['data']:'';

            	$page['role_list']    = $role_list;
				$page['main_heading'] = "User Role";
				$page['sub_heading']  = "User Role";
				$page['pre_title']    = "List User Role";
				$page['page_access']  = userAccess('user-role-view');
				$page['pre_menu']     = "index.php/admin/user/list_role";
				$data['page_temp']    = $this->load->view('admin/user/role/add_role',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_role";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function list_role($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$login_id = $this->session->userdata('id');

			if($param1 == '')
			{
				$page['main_heading'] = "User Role";
				$page['sub_heading']  = "User Role";
				$page['page_title']   = "List User Role";
				$page['pre_title']    = "Add User Role";
				$page['page_access']  = userAccess('user-role-add');
				$page['pre_menu']     = "index.php/admin/user/add_role";
				$data['page_temp']    = $this->load->view('admin/user/role/list_role',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_role";
				$this->bassthaya->load_admin_form_template($data);
			}
			else if($param1 == 'data_list')
			{
				if(userAccess('user-role-view'))
				{
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
	            		'offset'  => $_offset,
	            		'limit'   => $limit,
	            		'search'  => $search,
	            		'method'  => '_listUserRolePaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'user/api/user_role',$where);
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
		            		$role_id       = !empty($value['role_id'])?$value['role_id']:'';
						    $role_name     = !empty($value['role_name'])?$value['role_name']:'';
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
				            if(userAccess('user-role-edit') == TRUE)
				            {
				            	$edit = '<a href="'.BASE_URL.'index.php/admin/user/add_role/Edit/'.$role_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
				            }
				            if(userAccess('user-role-delete') == TRUE)
				            {
				            	$delete = '<a data-row="'.$i.'" data-id="'.$role_id.'" data-value="admin" data-cntrl="user" data-func="list_role" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
				            }

						    $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$role_name.'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                                if(userAccess('user-role-edit') == TRUE || userAccess('user-role-delete') == TRUE):
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
				$id       = $this->input->post('id');
				$log_id   = $this->session->userdata('id');
				$log_role = $this->session->userdata('user_role');

				if(!empty($id))	
				{
					$data = array(
						'log_id'   => $log_id,
						'log_role' => $log_role,
				    	'role_id'  => $id,
				    	'login_id' => $login_id,
				    	'method'   => '_deleteUserRole'
				    );

				    $data_save = avul_call(API_URL.'user/api/user_role',$data);

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

		public function add_user($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$login_id = $this->session->userdata('id');
			$formpage = $this->input->post('formpage');
			$method   = $this->input->post('method');
			$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$email     = $this->input->post('email');
			    $mobile    = $this->input->post('mobile');
			    $user_role = $this->input->post('user_role');
			    $password  = $this->input->post('password');
			    $address   = $this->input->post('address');
			    $method    = $this->input->post('method');

			    $required = array('email', 'user_role', 'password', 'method');
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
			    		if(userAccess('user-add'))
			    		{
			    			$data = array(
			    				'log_id'     => $log_id,
								'log_role'   => $log_role,
						    	'email'      => $email,
						    	'mobile'     => $mobile,
						    	'user_role'  => $user_role,
						    	'address'    => $address,
						    	'password'   => $password,
						    	'permission' => '2',
						    	'method'     => '_addUser',
						    );

						    $data_save = avul_call(API_URL.'user/api/user',$data);

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
			    		if(userAccess('user-edit'))
			    		{
			    			$user_id = $this->input->post('user_id');
			    			$pstatus = $this->input->post('pstatus');

			    			$data = array(
			    				'log_id'     => $log_id,
								'log_role'   => $log_role,
				    			'id'         => $user_id,
						    	'email'      => $email,
						    	'mobile'     => $mobile,
						    	'user_role'  => $user_role,
						    	'address'    => $address,
						    	'password'   => $password,
						    	'permission' => '2',
						    	'status'     => $pstatus,
						    	'method'     => '_updateUser',
						    );

						    $data_save = avul_call(API_URL.'user/api/user',$data);

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
					$where_1 = array(
						'user_id'  => $param2,
	            		'method'   => '_detailUser',
	            	);

	            	$data_list = avul_call(API_URL.'user/api/user',$where_1);

					$page['dataval']    = $data_list['data'];
					$page['method']     = 'BTBM_X_U';
					$page['page_title'] = "Edit User";
				}
				else
				{
					$page['dataval']    = '';
					$page['method']     = 'BTBM_X_C';
					$page['page_title'] = "Add User";
				}

				$where_2 = array(
            		'method'   => '_listUserRole'
            	);

            	$role_data = avul_call(API_URL.'user/api/user_role',$where_2);
            	$role_list = !empty($role_data['data'])?$role_data['data']:'';

            	$page['role_list']    = $role_list;
				$page['main_heading'] = "User";
				$page['sub_heading']  = "User";
				$page['pre_title']    = "List User";
				$page['page_access']  = userAccess('user-view');
				$page['pre_menu']     = "index.php/admin/user/list_user";
				$data['page_temp']    = $this->load->view('admin/user/account/add_user',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_user";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function list_user($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$login_id = $this->session->userdata('id');

			if($param1 == '')
			{
				$page['main_heading'] = "User";
				$page['sub_heading']  = "User";
				$page['page_title']   = "List User";
				$page['pre_title']    = "Add User";
				$page['page_access']  = userAccess('user-add');
				$page['pre_menu']     = "index.php/admin/user/add_user";
				$data['page_temp']    = $this->load->view('admin/user/account/list_user',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_user";
				$this->bassthaya->load_admin_form_template($data);
			}
			else if($param1 == 'data_list')
			{
				if(userAccess('user-view'))
				{
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
	            		'offset'  => $_offset,
	            		'limit'   => $limit,
	            		'search'  => $search,
	            		'method'  => '_listUserPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'user/api/user',$where);
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
		            		$user_id       = !empty($value['user_id'])?$value['user_id']:'';
						    $email         = !empty($value['email'])?$value['email']:'';
						    $user_role     = !empty($value['user_role'])?$value['user_role']:'';
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
				            if(userAccess('user-edit') == TRUE)
				            {
				            	$edit = '<a href="'.BASE_URL.'index.php/admin/user/add_user/Edit/'.$user_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
				            }
				            if(userAccess('user-delete') == TRUE)
				            {
				            	$delete = '<a data-row="'.$i.'" data-id="'.$user_id.'" data-value="admin" data-cntrl="user" data-func="list_user" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
				            }

						    $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$email.'</td>
	                                <td class="line_height">'.$user_role.'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                                if(userAccess('user-edit') == TRUE || userAccess('user-delete') == TRUE):
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
				$id       = $this->input->post('id');
				$log_id   = $this->session->userdata('id');
				$log_role = $this->session->userdata('user_role');

				if(!empty($id))	
				{
					$data = array(
						'log_id'   => $log_id,
						'log_role' => $log_role,
				    	'user_id'  => $id,
				    	'login_id' => $login_id,
				    	'method'   => '_deleteUser'
				    );

				    $data_save = avul_call(API_URL.'user/api/user',$data);

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
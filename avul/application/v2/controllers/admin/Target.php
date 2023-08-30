<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Target extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
			$this->load->model('target_model');
			$this->load->model('commom_model');
		}

		public function add_target($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');
			$method   = $this->input->post('method');

			if($formpage =='BTBM_X_P')
			{
				$month_id    = $this->input->post('month_id');
				$year_id     = $this->input->post('year_id');
				$target_val  = $this->input->post('target_val');
				$auto_id     = $this->input->post('auto_id');
				$employee_id = $this->input->post('employee_id');			

				$error    = FALSE;
				$required = array('month_id', 'year_id');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if(count(array_filter($auto_id))!==count($auto_id) || count(array_filter($employee_id))!==count($employee_id) || $error == TRUE)
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
			    		if(userAccess('employee-target-add'))
			    		{
			    			$target_value = [];
			            	$employee_cnt = count($employee_id); 

			            	for ($i=0; $i < $employee_cnt; $i++) {
								if(!empty($target_val[$i]))
								{
									$target_value[] = array(
										'employee_id' => $employee_id[$i],
										'target_val'  => $target_val[$i],
									);
								}
			            	}

			            	$employee_value = json_encode($target_value);

			            	$data = array(
			            		'month_id'     => $month_id,
						    	'year_id'      => $year_id,
						    	'target_value' => $employee_value,
						    	'financial_id' => $this->session->userdata('active_year'),
						    	'method'       => '_addTarget',
						    );

							// print_r($data);
							// exit;

						    $data_save = avul_call(API_URL.'target/api/add_target', $data);

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
			    		if(userAccess('employee-target-edit'))
			    		{
			    			$target_id  = $this->input->post('target_id');
				    		$auto_id    = $this->input->post('auto_id');
				    		$auto_count = count($auto_id);

							

			            	$target_value  = [];
			            	$j = 1;
			            	for ($i=0; $i < $auto_count; $i++) {

			            		$target_value[] = array(
			            			'auto_id'     => $auto_id[$i],
			            			'employee_id' => $employee_id[$i],
			            			'target_val'  => $target_val[$i],
			            		);
			            	}

			            	$employee_value = json_encode($target_value);

			            	$data = array(
			            		'target_id'    => $target_id,
						    	'month_id'     => $month_id,
						    	'year_id'      => $year_id,
						    	'target_value' => $employee_value,
						    	'financial_id' => $this->session->userdata('active_year'),
						    	'method'       => '_updateTarget',
						    );

						    $data_save = avul_call(API_URL.'target/api/add_target', $data);

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

			if($method == 'getEmployeeData')
			{
				$month_id = $this->input->post('month_id');
    			$year_id  = $this->input->post('year_id');

    			$error    = FALSE;
    			$required = array('month_id', 'year_id');
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
			    	
			    	$where_1 = array(
	            		'log_type' => '2',
	            		'method'   => '_typeWiseEmployee'
	            	);
					

	            	$data_list  = avul_call(API_URL.'employee/api/employee',$where_1);
	            	$emp_list   = !empty($data_list['data'])?$data_list['data']:'';

	            	if($emp_list)
	            	{	
	            		$num   = 1;
	            		$table = '';
	            		foreach ($emp_list as $key => $val_1) {
	            				
	            			$employee_id = !empty($val_1['employee_id'])?$val_1['employee_id']:'';
						    $username    = !empty($val_1['username'])?$val_1['username']:'';
						    $mobile      = !empty($val_1['mobile'])?$val_1['mobile']:'';

						    $table .= '
			    			<tr class="row_'.$num.'">
                                <td>'.$num.'</td>
                                <td>'.$username.'</td>
                                <td>'.$mobile.'</td>
                                <td>
                                	<input type="text" data-te="'.$num.'" name="target_val[]" id="target_val'.$num.'" class="form-control target_val'.$num.' target_val int_value" placeholder="Target Value"> 
                                	<input type="hidden" data-te="'.$num.'" name="employee_id[]" id="employee_id'.$num.'" class="form-control employee_id'.$num.' employee_id" placeholder="Target Value" value="'.$employee_id.'">
                                	<input type="hidden" data-te="'.$num.'" name="auto_id[]" id="auto_id'.$num.'" class="form-control auto_id'.$num.' auto_id" placeholder="Target Value" value="'.$num.'">
                                </td>
                                <td class="buttonlist p-l-0 text-center">
		                            <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6 remove_item"><span class="ft-minus-square"></span></button>
		                        </td>
                            </tr>';

                            $num++;
	            		}

	            		$response['status']  = 1;
				        $response['message'] = 'Success'; 
				        $response['data']    = $table;
				        echo json_encode($response);
				        return;
	            	}
	            	else
	            	{
	            		$response['status']  = 0;
				        $response['message'] = 'Data Not Found'; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
	            	}
			    }
			}

			else
			{
				if($param1 =='Edit')
				{
					$target_id = !empty($param2)?$param2:'';

					$where = array(
	            		'target_id' => $target_id,
	            		'method'    => '_detailTarget'
	            	);

	            	$data_list  = avul_call(API_URL.'target/api/manage_target',$where);

					$page['dataval']    = $data_list['data'];
					$page['method']     = 'BTBM_X_U';
					$page['page_title'] = "Edit Target";
				}
				else
				{
					$page['dataval']    = '';
					$page['method']     = 'BTBM_X_C';
					$page['page_title'] = "Add Target";
				}

				$where_2 = array(
            		'method'   => '_listMonth'
            	);

            	$month_data = avul_call(API_URL.'master/api/month',$where_2);
            	$month_list = !empty($month_data['data'])?$month_data['data']:'';

	        	$where_2 = array(
	        		'method'   => '_listYear'
	        	);

	        	$year_data = avul_call(API_URL.'master/api/year',$where_2);
	        	$year_list = !empty($year_data['data'])?$year_data['data']:'';

	        	$page['month_list']   = $month_list;
	        	$page['year_list']    = $year_list;
				$page['main_heading'] = "Target";
				$page['sub_heading']  = "Target";
				$page['pre_title']    = "List Target";
				$page['page_access']  = userAccess('employee-target-view');
				$page['pre_menu']     = "index.php/admin/target/list_target";
				$data['page_temp']    = $this->load->view('admin/target/add_target',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_target";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function list_target($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			if($param1 == 'data_list')
			{
				if(userAccess('employee-target-view'))
				{
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
	            		'offset'          => $_offset,
	            		'limit'           => $limit,
	            		'search'          => $search,
	            		'financial_year'  => $this->session->userdata('active_year'),
	            		'method'          => '_listTargetPaginate',
	            	);

	            	$data_list  = avul_call(API_URL.'target/api/manage_target',$where);
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
		            	foreach ($data_value as $key => $val) {

		            		$target_id     = !empty($val['target_id'])?$val['target_id']:'';
						    $month_name    = !empty($val['month_name'])?$val['month_name']:'';
						    $year_value    = !empty($val['year_value'])?$val['year_value']:'';
						    $active_status = !empty($val['status'])?$val['status']:'0';
						    $createdate    = !empty($val['createdate'])?$val['createdate']:'';

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
				            if(userAccess('employee-target-edit') == TRUE)
				            {
				            	$edit = '<a href="'.BASE_URL.'index.php/admin/target/add_target/Edit/'.$target_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
				            }
				            if(userAccess('employee-target-delete') == TRUE)
				            {
				            	$delete = '<a data-row="'.$i.'" data-id="'.$target_id.'" data-value="admin" data-cntrl="target" data-func="list_target" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
				            }

			                $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$month_name.'</td>
	                                <td class="line_height">'.$year_value.'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                                if(userAccess('employee-target-edit') == TRUE || userAccess('employee-target-delete') == TRUE):
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
				    	'target_id' => $id,
				    	'method'    => '_deleteTarget'
				    );

				    $data_save = avul_call(API_URL.'target/api/manage_target',$data);

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

			else
        	{
        		$page['random_val']   = $param1;
				$page['main_heading'] = "Target";
				$page['sub_heading']  = "Target";
				$page['page_title']   = "List Target";
				$page['pre_title']    = "Add Target";
				$page['page_access']  = userAccess('employee-target-add');
				$page['pre_menu']     = "index.php/admin/target/add_target";
				$data['page_temp']    = $this->load->view('admin/target/list_target',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_target";
				$this->bassthaya->load_admin_form_template($data);
			}	
		}

		public function assign_product($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');
			$method   = $this->input->post('method');

			if($formpage =='BTBM_X_P')
			{
				$templatename    = $this->input->post('templatename');
				$month_id    = $this->input->post('month_id');
				$year_id     = $this->input->post('year_id');
				$employee_id = $this->input->post('employee_id');
				$category_id = $this->input->post('category_id');
				$target_val  = $this->input->post('target_val');
				$product_id  = $this->input->post('product_id');
				$type_id     = $this->input->post('type_id');
				$auto_id     = $this->input->post('auto_id');

				$error    = FALSE;
				$required = array('templatename','category_id');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if(count(array_filter($type_id))!==count($type_id) || $error == TRUE)
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
			    		if(userAccess('product-target-add'))
			    		{
			    			$target_value = [];
			            	$type_cnt = count($type_id); 

			            	for ($i=0; $i < $type_cnt; $i++) {

			            		$target_value[] = array(
			            			'product_id' => $product_id[$i],
			            			'type_id'    => $type_id[$i],
			            			'target_val' => $target_val[$i],
			            		);
			            	}

			            	$target_result = json_encode($target_value);

			            	$data = array(
						    	'category_id'  => $category_id,
								'templatename'=> $templatename,
						    	'target_value' => $target_result,
						    	'financial_id' => $this->session->userdata('active_year'),
						    	'method'       => '_addEmployeeProductTarget',					
						    );
							// print_r($data);
							// exit;
						    $data_save = avul_call(API_URL.'target/api/assign_product_target', $data);

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
			    		if(userAccess('product-target-edit'))
			    		{
			    			$target_id  = $this->input->post('target_id');
				    		$auto_id    = $this->input->post('auto_id');
				    		$auto_count = count($auto_id);

			            	$target_value  = [];
			            	$j = 1;
			            	for ($i=0; $i < $auto_count; $i++) {

			            		$target_value[] = array(
			            			'auto_id'    => $auto_id[$i],
			            			'product_id' => $product_id[$i],
			            			'type_id'    => $type_id[$i],
			            			'target_val' => $target_val[$i],
			            		);
			            	}

			            	$target_result = json_encode($target_value);

			            	$data = array(
						    	'target_id'    => $target_id,
						    	'month_id'     => $month_id,
						    	'year_id'      => $year_id,
						    	'employee_id'  => $employee_id,
						    	'category_id'  => $category_id,
						    	'target_value' => $target_result,
						    	'financial_id' => $this->session->userdata('active_year'),
						    	'method'       => '_updateProductTarget',
						    );

						    $data_save = avul_call(API_URL.'target/api/assign_product_target', $data);

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

			if($method == '_getEmployeeList')
			{
				$month_id = $this->input->post('month_id');
				$year_id  = $this->input->post('year_id');

				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id');
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
			    	$emp_whr = array(
			    		'month_id' => $month_id,
			    		'year_id'  => $year_id,
			    		'method'   => '_getEmployeeList',
			    	);

			    	$emp_list = avul_call(API_URL.'target/api/assign_product_target', $emp_whr);
	            	$emp_res  = $emp_list['data'];


	        		$option ='<option value="">Select Value</option>';

	        		if(!empty($emp_res))
	        		{
	        			foreach ($emp_res as $key => $value) {
	        				$employee_id = !empty($value['employee_id'])?$value['employee_id']:'';
				            $emp_name    = !empty($value['emp_name'])?$value['emp_name']:'';
				            $target_val  = !empty($value['target_val'])?$value['target_val']:'';

	                        $option .= '<option value="'.$employee_id.'">'.$emp_name.'</option>';
	        			}
	        		}

	        		$response['status']  = 1;
			        $response['message'] = 'success'; 
			        $response['data']    = $option;
			        echo json_encode($response);
			        return; 
			    }
			}			

			else if($method == 'getProductData')
			{
				$month_id     = $this->input->post('month_id');
    			$year_id      = $this->input->post('year_id');
    			$category_id  = $this->input->post('category_id');

    			$error    = FALSE;
    			$required = array( 'category_id');
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
			    	
			    	// Product List
					$where_1  = array(
						'category_id'    => $category_id,
						'method'         => '_listCategoryProducts'
					);

					$cat_list = avul_call(API_URL.'catlog/api/product', $where_1);
					$cat_val  = $cat_list['data'];

	            	if($cat_val)
	            	{	
	            		$num   = 1;
	            		$table = '';
	            		foreach ($cat_val as $key => $val_1) {
	            				
						    $type_id     = !empty($val_1['type_id'])?$val_1['type_id']:'';
				            $product_id  = !empty($val_1['product_id'])?$val_1['product_id']:'';
				            $description = !empty($val_1['description'])?$val_1['description']:'';

						    $table .= '
			    			<tr class="row_'.$num.'">
                                <td>'.$num.'</td>
                                <td>'.$description.'</td>
                                <td>
                                	<input type="text" data-te="'.$num.'" name="target_val[]" id="target_val'.$num.'" class="form-control target_val'.$num.' target_val int_value" placeholder="Target Value"> 

                                	<input type="hidden" data-te="'.$num.'" name="product_id[]" id="product_id'.$num.'" class="form-control product_id'.$num.' product_id" placeholder="Target Value" value="'.$product_id.'">

                                	<input type="hidden" data-te="'.$num.'" name="type_id[]" id="type_id'.$num.'" class="form-control type_id'.$num.' type_id" placeholder="Target Value" value="'.$type_id.'">

                                	<input type="hidden" data-te="'.$num.'" name="auto_id[]" id="auto_id'.$num.'" class="form-control auto_id'.$num.' auto_id" placeholder="Target Value" value="'.$num.'">
                                </td>
                                <td class="buttonlist p-l-0 text-center">
		                            <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6 remove_item"><span class="ft-minus-square"></span></button>
		                        </td>
                            </tr>';

                            $num++;
	            		}

	            		$response['status']  = 1;
				        $response['message'] = 'Success'; 
				        $response['data']    = $table;
				        echo json_encode($response);
				        return;
	            	}
	            	else
	            	{
	            		$response['status']  = 0;
				        $response['message'] = 'Data Not Found'; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
	            	}
			    }
			}

			else
			{
				if($param1 =='Edit')
				{
					$target_id = !empty($param2)?$param2:'';

					$where = array(
	            		'target_id' => $target_id,
	            		'method'    => '_detailProductTarget'
	            	);

	            	$data_list   = avul_call(API_URL.'target/api/assign_product_target',$where);
	            	$data_value  = $data_list['data'];
	            	$target_data = !empty($data_value['target_data'])?$data_value['target_data']:'';

	            	$month_id = !empty($target_data['month_id'])?$target_data['month_id']:'';
            		$year_id  = !empty($target_data['year_id'])?$target_data['year_id']:'';

	            	$emp_whr = array(
			    		'month_id' => $month_id,
			    		'year_id'  => $year_id,
			    		'method'   => '_getEmployeeList',
			    	);

			    	$emp_list = avul_call(API_URL.'target/api/assign_product_target', $emp_whr);
	            	$emp_res  = $emp_list['data'];

					$page['dataval']    = $data_list['data'];
					$page['emp_val']    = $emp_res;
					$page['method']     = 'BTBM_X_U';
					$page['page_title'] = "Edit Product Target";
				}
				else
				{
					$page['dataval']    = '';
					$page['emp_val']    = '';
					$page['method']     = 'BTBM_X_C';
					$page['page_title'] = "Add Product Target";
				}

				$where_2 = array(
            		'method'   => '_listMonth'
            	);

            	$month_data = avul_call(API_URL.'master/api/month',$where_2);
            	$month_list = !empty($month_data['data'])?$month_data['data']:'';

	        	$where_2 = array(
	        		'method'   => '_listYear'
	        	);

	        	$year_data = avul_call(API_URL.'master/api/year',$where_2);
	        	$year_list = !empty($year_data['data'])?$year_data['data']:'';

	        	// Product List
				$where_3  = array(
					'method' => '_listCategory'
				);

				$pdt_list = avul_call(API_URL.'catlog/api/category', $where_3);
				$pdt_val  = $pdt_list['data'];

	        	$page['month_list']   = $month_list;
	        	$page['year_list']    = $year_list;
	        	$page['product_list'] = $pdt_val;
				$page['main_heading'] = "Target";
				$page['sub_heading']  = "Target";
				$page['page_title']    ="Create Target Template";
				$page['pre_title']    = "Add Product Target";
				$page['page_access']  = userAccess('product-target-add');
				$page['pre_menu']     = "index.php/admin/target/add_product_target";
				$data['page_temp']    = $this->load->view('admin/target/assign_product',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "assign_product";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function assign_employee($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');
			$method   = $this->input->post('method');

			if($formpage =='BTBM_X_P')
			{
				$month_id    = $this->input->post('month_id');
				$year_id     = $this->input->post('year_id');
				$employee_id = $this->input->post('employee_id');
				$template_id = $this->input->post('template_id');
				$auto_id     = $this->input->post('auto_id');

				$error    = FALSE;
				$required = array('month_id', 'year_id', 'employee_id' , 'template_id');
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
			    	if($method == 'BTBM_X_U')
			    	{
			    		if(userAccess('product-target-add'))
			    		{

							$employee_value = implode(',', $employee_id);

			            	$data = array(
			            		'month_id'     => $month_id,
						    	'year_id'      => $year_id,
								'employee_id'  => $employee_value,
								'template_id'  => $template_id,
						    	'financial_id' => $this->session->userdata('active_year'),
						    	'method'       => '_addEmployeeProductarget',
						    );

							// print_r($data);
							// exit;

						    $data_save = avul_call(API_URL.'target/api/assign_Employee_target', $data);

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
			    		if(userAccess('product-target-edit'))
			    		{
			    			$target_id  = $this->input->post('target_id');
				    		$auto_id    = $this->input->post('auto_id');
				    		$auto_count = count($auto_id);

			            	$target_value  = [];
			            	$j = 1;
			            	for ($i=0; $i < $auto_count; $i++) {

			            		$target_value[] = array(
			            			'auto_id'    => $auto_id[$i],
			            			'product_id' => $product_id[$i],
			            			'type_id'    => $type_id[$i],
			            			'target_val' => $target_val[$i],
			            		);
			            	}

			            	$target_result = json_encode($target_value);

			            	$data = array(
						    	'target_id'    => $target_id,
						    	'month_id'     => $month_id,
						    	'year_id'      => $year_id,
						    	'employee_id'  => $employee_id,
						    	'category_id'  => $category_id,
						    	'target_value' => $target_result,
						    	'financial_id' => $this->session->userdata('active_year'),
						    	'method'       => '_updateProductTarget',
						    );

						    $data_save = avul_call(API_URL.'target/api/assign_Employee_target', $data);

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
			if($method == '_getEmployeeList')
			{
				$month_id = $this->input->post('month_id');
				$year_id  = $this->input->post('year_id');

				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id');
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
			    	$emp_whr = array(
			    		'month_id' => $month_id,
			    		'year_id'  => $year_id,
			    		'method'   => '_getEmployeeList',
			    	);

			    	$emp_list = avul_call(API_URL.'target/api/assign_Employee_target', $emp_whr);
	            	$emp_res  = $emp_list['data'];


	        		$option ='';

	        		if(!empty($emp_res))
	        		{
	        			foreach ($emp_res as $key => $value) {
	        				$employee_id = !empty($value['employee_id'])?$value['employee_id']:'';
				            $emp_name    = !empty($value['emp_name'])?$value['emp_name']:'';
				            $target_val  = !empty($value['target_val'])?$value['target_val']:'';

	                        $option .= '<option value="'.$employee_id.'">'.$emp_name.'</option>';
	        			}
	        		}

	        		$response['status']  = 1;
			        $response['message'] = 'success'; 
			        $response['data']    = $option;
			        echo json_encode($response);
			        return; 
			    }
			}			

			else if($method == 'getProductData')
			{
				$month_id     = $this->input->post('month_id');
    			$year_id      = $this->input->post('year_id');
    			$category_id  = $this->input->post('category_id');

    			$error    = FALSE;
    			$required = array('month_id', 'year_id', 'category_id');
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
			    	
			    	// Product List
					$where_1  = array(
						'category_id'    => $category_id,
						'method'         => '_listCategoryProducts'
					);

					$cat_list = avul_call(API_URL.'catlog/api/product', $where_1);
					$cat_val  = $cat_list['data'];

	            	if($cat_val)
	            	{	
	            		$num   = 1;
	            		$table = '';
	            		foreach ($cat_val as $key => $val_1) {
	            				
						    $type_id     = !empty($val_1['type_id'])?$val_1['type_id']:'';
				            $product_id  = !empty($val_1['product_id'])?$val_1['product_id']:'';
				            $description = !empty($val_1['description'])?$val_1['description']:'';

						    $table .= '
			    			<tr class="row_'.$num.'">
                                <td>'.$num.'</td>
                                <td>'.$description.'</td>
                                <td>
                                	<input type="text" data-te="'.$num.'" name="target_val[]" id="target_val'.$num.'" class="form-control target_val'.$num.' target_val int_value" placeholder="Target Value"> 

                                	<input type="hidden" data-te="'.$num.'" name="product_id[]" id="product_id'.$num.'" class="form-control product_id'.$num.' product_id" placeholder="Target Value" value="'.$product_id.'">

                                	<input type="hidden" data-te="'.$num.'" name="type_id[]" id="type_id'.$num.'" class="form-control type_id'.$num.' type_id" placeholder="Target Value" value="'.$type_id.'">

                                	<input type="hidden" data-te="'.$num.'" name="auto_id[]" id="auto_id'.$num.'" class="form-control auto_id'.$num.' auto_id" placeholder="Target Value" value="'.$num.'">
                                </td>
                                <td class="buttonlist p-l-0 text-center">
		                            <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6 remove_item"><span class="ft-minus-square"></span></button>
		                        </td>
                            </tr>';

                            $num++;
	            		}

	            		$response['status']  = 1;
				        $response['message'] = 'Success'; 
				        $response['data']    = $table;
				        echo json_encode($response);
				        return;
	            	}
	            	else
	            	{
	            		$response['status']  = 0;
				        $response['message'] = 'Data Not Found'; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
	            	}
			    }
			}

			else
			{
				if($param1 =='Edit')
				{
					$target_id = !empty($param2)?$param2:'';

					$where = array(
	            		'target_id' => $target_id,
	            		'method'    => '_detailProductTarget'
	            	);

	            	$data_list   = avul_call(API_URL.'target/api/assign_Employee_target',$where);
	            	$data_value  = $data_list['data'];
	            	$target_data = !empty($data_value['target_data'])?$data_value['target_data']:'';

	            	$month_id = !empty($target_data['month_id'])?$target_data['month_id']:'';
            		$year_id  = !empty($target_data['year_id'])?$target_data['year_id']:'';

	            	$emp_whr = array(
			    		'month_id' => $month_id,
			    		'year_id'  => $year_id,
			    		'method'   => '_getEmployeeList',
			    	);

			    	$emp_list = avul_call(API_URL.'target/api/assign_Employee_target', $emp_whr);
	            	$emp_res  = $emp_list['data'];

					$page['dataval']    = $data_list['data'];
					$page['emp_val']    = $emp_res;
					$page['method']     = 'BTBM_X_U';
					$page['page_title'] = "Edit Product Target";
				}
				else
				{
					$page['dataval']    = '';
					$page['emp_val']    = '';
					$page['method']     = 'BTBM_X_C';
					$page['page_title'] = "Add Product Target";
				}

				$where_2 = array(
            		'method'   => '_listMonth'
            	);

            	$month_data = avul_call(API_URL.'master/api/month',$where_2);
            	$month_list = !empty($month_data['data'])?$month_data['data']:'';

	        	$where_2 = array(
	        		'method'   => '_listYear'
	        	);

	        	$year_data    = avul_call(API_URL.'target/api/assign_Employee_target',$where_2);
	        	$year_listt   = !empty($year_data['data'])?$year_data['data']:'';
				$lastyearlist = $year_listt[0]['year_name'];

	        	// Product List
				$where_3  = array(
					'method' 	   => '_template_list',
					'financial_id' => $this->session->userdata('active_year'),
				);

				$pdt_list = avul_call(API_URL.'target/api/assign_Employee_target', $where_3);
				$pdt_val  = $pdt_list['data'];

				// print_r($pdt_val);
				// exit;

	        	$page['month_list']   = $month_list;
	        	$page['year_list']    = $year_listt;
	        	$page['template_name'] = $pdt_val;
				$page['main_heading'] = "Target";
				$page['sub_heading']  = "Target";
				$page['page_title']    = "Assign Employee Product Target";
				$page['pre_title']    = "Assign Product Target";
				$page['page_access']  = userAccess('product-target-add');
				$page['pre_menu']     = "index.php/admin/target/assign_product";
				$data['page_temp']    = $this->load->view('admin/target/assign_employee',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "assign_employee";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function average_sales($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');
			$method   = $this->input->post('method');

			
			if($formpage =='BTBM_X_P')
			{
				
				$month_id    = $this->input->post('month_id');
				$year_id     = $this->input->post('year_id');
				$employee_id = $this->input->post('employee_id');
				$category_id = $this->input->post('category_id');
				$setaveragenxt = $this->input->post('setaveragenxt');

				// $auto_id     = $this->input->post('auto_id');

				$error    = FALSE;
				$required = array('month_id', 'year_id', 'employee_id','category_id','setaveragenxt');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    	if($method == 'BTBM_X_U')
			    	{
						
			    		if(userAccess('product-target-add'))
			    		{

							$month_value = implode(',', $month_id);

			            	$data = array(
			            		'month_id'     => $month_value,
						    	'year_id'      => $year_id,
								'employee_id'  => $employee_id,
								'category_id'  => $category_id,
								'setaveragenxt'=> $setaveragenxt,
						    	'financial_id' => $this->session->userdata('active_year'),
						    	'method'       => '_averageSalesProductarget',
						    );

						
						    $data_save = avul_call(API_URL.'target/api/average_sales', $data);

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
			    		// if(userAccess('product-target-edit'))
			    		// {
			    		// 	$target_id  = $this->input->post('target_id');
				    	// 	$auto_id    = $this->input->post('auto_id');
				    	// 	// $auto_count = count($auto_id);

			            // 	$target_value  = [];
			            // 	$j = 1;
			            // 	for ($i=0; $i < $auto_count; $i++) {

			            // 		$target_value[] = array(
			            // 			'auto_id'    => $auto_id[$i],
			            // 			'product_id' => $product_id[$i],
			            // 			'type_id'    => $type_id[$i],
			            // 			'target_val' => $target_val[$i],
			            // 		);
			            // 	}

			            // 	$target_result = json_encode($target_value);

			            // 	$data = array(
						//     	'target_id'    => $target_id,
						//     	'month_id'     => $month_id,
						//     	'year_id'      => $year_id,
						//     	'employee_id'  => $employee_id,
						//     	'category_id'  => $category_id,
						//     	'target_value' => $target_result,
						//     	'financial_id' => $this->session->userdata('active_year'),
						//     	'method'       => '_updateProductTarget',
						//     );

						//     $data_save = avul_call(API_URL.'target/api/assign_Employee_target', $data);

						//     if($data_save['status'] == 1)
						//     {
						//     	$response['status']  = 1;
						//         $response['message'] = $data_save['message']; 
						//         $response['data']    = [];
						//         echo json_encode($response);
						//         return; 
						//     }
						//     else
						//     {
						//     	$response['status']  = 0;
						//         $response['message'] = $data_save['message']; 
						//         $response['data']    = [];
						//         echo json_encode($response);
						//         return; 	
						//     }
			    		// }
			    		// else
			    		// {
			    		// 	$response['status']  = 0;
					    //     $response['message'] = 'Access denied'; 
					    //     $response['data']    = [];
					    //     echo json_encode($response);
					    //     return; 
			    		// }
			    	}	
			    
			}

			if($method == '_getEmployeeList')
			{
				$month_id = $this->input->post('month_id');
				$year_id  = $this->input->post('year_id');

				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id');
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
			    	$emp_whr = array(
			    		'month_id' => $month_id,
			    		'year_id'  => $year_id,
			    		'method'   => '_getEmployeeList',
			    	);

			    	$emp_list = avul_call(API_URL.'target/api/assign_Employee_target', $emp_whr);
	            	$emp_res  = $emp_list['data'];


	        		$option ='';

	        		if(!empty($emp_res))
	        		{
	        			foreach ($emp_res as $key => $value) {
	        				$employee_id = !empty($value['employee_id'])?$value['employee_id']:'';
				            $emp_name    = !empty($value['emp_name'])?$value['emp_name']:'';
				            $target_val  = !empty($value['target_val'])?$value['target_val']:'';

	                        $option .= '<option value="'.$employee_id.'">'.$emp_name.'</option>';
	        			}
	        		}

	        		$response['status']  = 1;
			        $response['message'] = 'success'; 
			        $response['data']    = $option;
			        echo json_encode($response);
			        return; 
			    }
			}	


			else if($method == 'getaverageData')
			{
				$month_id     = $this->input->post('month_id');
    			$year_id      = $this->input->post('year_id');
    			$category_id  = $this->input->post('category_id');
				$employee_id  = $this->input->post('employee_id');
				$setaveragenxt = $this->input->post('setaveragenxt');


    			$error    = FALSE;
    			$required = array('month_id', 'year_id', 'category_id','setaveragenxt');
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
					$count_month = count($month_id);

					if($count_month == 3)
					{

						$month_value = implode(',', $month_id);

						$data = array(
							'month_id'     => $month_value,
							'year_id'      => $year_id,
							'employee_id'  => $employee_id,
							'category_id'  => $category_id,
							'financial_id' => $this->session->userdata('active_year'),
							'method'       => '_listAverageTarget',
						);
						
						$cat_list = avul_call(API_URL.'target/api/average_sales', $data);
						$List_val  = $cat_list['data'];    
						$target_data  = $cat_list['target_data'];
						$avg_value    = $cat_list['avg_valuee'];
						// $achieve_val  = $cat_list['achieve_val'];

						
						$achieve_val1    = !empty($achieve_val[0]['achieve_val'])?$achieve_val[0]['achieve_val']:'';
						$achieve_val2    = !empty($achieve_val[1]['achieve_val'])?$achieve_val[1]['achieve_val']:'';
						$achieve_val3    = !empty($achieve_val[2]['achieve_val'])?$achieve_val[2]['achieve_val']:'';

						$total_target1  = !empty($target_data[0]['total_target'])?$target_data[0]['total_target']:'0';
						$total_target2  = !empty($target_data[1]['total_target'])?$target_data[1]['total_target']:'0';
						$total_target3  = !empty($target_data[2]['total_target'])?$target_data[2]['total_target']:'0';
						$month_name1    = !empty($target_data[0]['month_name'])?$target_data[0]['month_name']:'';
						$month_name2    = !empty($target_data[1]['month_name'])?$target_data[1]['month_name']:'';
						$month_name3    = !empty($target_data[2]['month_name'])?$target_data[2]['month_name']:'';
						
						if($List_val)
						{	
							$num   = 1;
							$table = '';
							$table .= '
								<tr class="row_'.$num.'">
									<th>Category Name</th>
									<th>Product Name</th>
									<th>Target-'.$month_name1.'</th>
									<th>'.$month_name2.'</th>
									<th>'.$month_name3.'</th>
									<th>Achieved-'.$month_name1.'</th>
									<th>'.$month_name2.'</th>
									<th>'.$month_name3.'</th>
								</tr>';
							foreach ($List_val as $key => $val_1) 
							{
								$type_id       = !empty($val_1['type_id'])?$val_1['type_id']:'';
								$product_id    = !empty($val_1['product_id'])?$val_1['product_id']:'';
								$description   = !empty($val_1['description'])?$val_1['description']:'';
								$month_name   = !empty($val_1['month_name'])?$val_1['month_name']:'';
								$category_name = !empty($val_1['category_name'])?$val_1['category_name']:'';

								$month_1 = !empty($val_1['m1'])?$val_1['m1']:'0';
								$month_2 = !empty($val_1['m2'])?$val_1['m2']:'0';
								$month_3 = !empty($val_1['m3'])?$val_1['m3']:'0';

								$achieve_1 = !empty($val_1['a1'])?$val_1['a1']:'0';
								$achieve_2 = !empty($val_1['a2'])?$val_1['a2']:'0';
								$achieve_3 = !empty($val_1['a3'])?$val_1['a3']:'0';


								$target_val    = !empty($val_1['target_val'])?$val_1['target_val']:'';
								$achieve_val   = !empty($val_1['achieve_val'])?$val_1['achieve_val']:'';

								$table .= '
								<tr>	
									<td>'.$category_name.'</td>
									<td>'.$description.'</td>
									<td>'.$month_1.'</td>
									<td>'.$month_2.'</td>
									<td>'.$month_3.'</td>
									<td>'.$achieve_1.'</td>
									<td>'.$achieve_2.'</td>
									<td>'.$achieve_3.'</td>
								</tr>
								';
								$num++;
							}

							$table .='
								<tr>
									<th colspan="1" class="text-right"></th>
									<td><b>Total</b></td>
									<td>'.$total_target1.'</td>
									<td>'.$total_target2.'</td>
									<td>'.$total_target3.'</td>
								</tr>
								
								<tr>
								<th colspan="5" class="text-right"></th>
								<td><b>Average</b></td>
								</tr>';
							foreach ($avg_value  as $key => $value) {
								$average_val = !empty($value['averagevalue'])?$value['averagevalue']:'';
								$description = !empty($value['description'])?$value['description']:'';

								//average_val
								$average = $average_val/3;

								//Multiply average_val
								$SKU_wise_target = $average * $setaveragenxt;

							$table .='
							
							<tr>
								<th colspan="5" class="text-right">'.$description.'  - Average Achieved Target Value &ensp;&ensp;&ensp;&ensp; -- </th>
								<td>'.round($average).'</td>
							</tr>
							<tr>
								<th colspan="5" class="text-right">Next Month - '.$description.'  - Target Value &ensp;&ensp;&ensp;&ensp; -- </th>
								<td>'.round($SKU_wise_target).'</td>
							</tr>
							'
							;
							}
							$response['status']  = 1;
							$response['message'] = 'Success'; 
							$response['data']    = $table;
							echo json_encode($response);
							return;
						}
						else
						{
							$response['status']  = 0;
							$response['message'] = 'Data Not Found'; 
							$response['data']    = [];
							echo json_encode($response);
							return;
						}
					}
					else
					{
						$response['status']  = 0;
						$response['message'] = "Invalid Month"; 
						$response['data']    = [];
						echo json_encode($response);
						return; 
						
					}
			    }
			}
                         
			else
			{
				if($param1 =='Edit')
				{
					$target_id = !empty($param2)?$param2:'';

					$where = array(
	            		'target_id' => $target_id,
	            		'method'    => '_detailProductTarget'
	            	);

	            	$data_list   = avul_call(API_URL.'target/api/assign_Employee_target',$where);
	            	$data_value  = $data_list['data'];
	            	$target_data = !empty($data_value['target_data'])?$data_value['target_data']:'';

	            	$month_id = !empty($target_data['month_id'])?$target_data['month_id']:'';
            		$year_id  = !empty($target_data['year_id'])?$target_data['year_id']:'';

	            	$emp_whr = array(
			    		'month_id' => $month_id,
			    		'year_id'  => $year_id,
			    		'method'   => '_getEmployeeList',
			    	);

			    	$emp_list = avul_call(API_URL.'target/api/assign_Employee_target', $emp_whr);
	            	$emp_res  = $emp_list['data'];

					$page['dataval']    = $data_list['data'];
					$page['emp_val']    = $emp_res;
					$page['method']     = 'BTBM_X_U';
					$page['page_title'] = "Edit Product Target";
				}
				else
				{
					$page['dataval']    = '';
					$page['emp_val']    = '';
					$page['method']     = 'BTBM_X_C';
					$page['page_title'] = "Add Product Target";
				}

				$where_2 = array(
            		'method'   => '_listMonth'
            	);

            	$month_data = avul_call(API_URL.'master/api/month',$where_2);
            	$month_list = !empty($month_data['data'])?$month_data['data']:'';

	        	$where_2 = array(
	        		'method'   => '_listYear'
	        	);

	        	$year_data = avul_call(API_URL.'master/api/year',$where_2);
	        	$year_list = !empty($year_data['data'])?$year_data['data']:'';

	        	// Product List
				$where_3  = array(
					'method' => '_listCategory'
				);

				$pdt_list = avul_call(API_URL.'catlog/api/category', $where_3);
				$pdt_val  = $pdt_list['data'];

				// Employee List
				$where_4  = array(
					'method' => '_listEmployee'
				);

				$emp_list = avul_call(API_URL.'employee/api/employee', $where_4);
				$emp_val  = $emp_list['data'];

				
	        	$page['month_list']   = $month_list;
	        	$page['year_list']    = $year_list;
	        	$page['product_list'] = $pdt_val;
	        	$page['Employee_list'] = $emp_val;
				$page['main_heading'] = "Target";
				$page['sub_heading']  = "Target";
				$page['page_title']    = "Average Sales Product Target";
				$page['pre_title']    = "Manage Product Target";
				$page['page_access']  = userAccess('product-target-add');
				$page['pre_menu']     = "index.php/admin/target/list_product_target";
				$data['page_temp']    = $this->load->view('admin/target/average_sales',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "average_sales";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function add_product_target($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');
			$method   = $this->input->post('method');

			if($formpage =='BTBM_X_P')
			{
				$month_id    = $this->input->post('month_id');
				$year_id     = $this->input->post('year_id');
				$employee_id = $this->input->post('employee_id');
				$category_id = $this->input->post('category_id');
				$target_val  = $this->input->post('target_val');
				$product_id  = $this->input->post('product_id');
				$type_id     = $this->input->post('type_id');
				$auto_id     = $this->input->post('auto_id');

				$error    = FALSE;
				$required = array('month_id', 'year_id', 'employee_id','category_id');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if(count(array_filter($type_id))!==count($type_id) || $error == TRUE)
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
			    		if(userAccess('product-target-add'))
			    		{
			    			$target_value = [];
			            	$type_cnt = count($type_id); 

			            	for ($i=0; $i < $type_cnt; $i++) {

			            		$target_value[] = array(
			            			'product_id' => $product_id[$i],
			            			'type_id'    => $type_id[$i],
			            			'target_val' => $target_val[$i],
			            		);
			            	}

			            	$target_result = json_encode($target_value);

			            	$data = array(
			            		'month_id'     => $month_id,
						    	'year_id'      => $year_id,
						    	'category_id'  => $category_id,
								'employee_id'  => $employee_id,
						    	'target_value' => $target_result,
						    	'financial_id' => $this->session->userdata('active_year'),
						    	'method'       => '_addProductTarget',
						    );


						    $data_save = avul_call(API_URL.'target/api/product_target', $data);

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
			    		if(userAccess('product-target-edit'))
			    		{

			    			$target_id  = $this->input->post('target_id');
				    		$auto_id    = $this->input->post('auto_id');

							//For my critical situation i use api in main controller // 
							$where_1  = array('id' => $year_id);
							$column_1 = 'year_value';
							$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
							$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';
							$id_value=[];
							$where_12  = array('target_id' => $target_id,'month_id'=> $month_id,'year_name'=>$year_val,'emp_id'=> $employee_id,'category_id'=>$category_id);
							$column_12 = 'id';
							$data_12   = $this->target_model->getProductTargetDetails($where_12, '', '', 'result', '', '', '', '', $column_12);

							foreach ($data_12 as  $val) 
							{
								$achieve_vall  = !empty($val->id)?$val->id:'';
								$id_value[]= $achieve_vall;
							}

				    		$auto_count = count($auto_id);

			            	$target_value  = [];
			            	$j = 1;
			            	for ($i=0; $i < $auto_count; $i++) {

			            		$target_value[] = array(
			            			'auto_id'    => $auto_id[$i],
			            			'product_id' => $product_id[$i],
			            			'type_id'    => $type_id[$i],
			            			'target_val' => $target_val[$i],
									'id_value'	 => $id_value[$i],
			            		);
			            	}
							
			            	$target_result = json_encode($target_value);

			            	$data = array(
						    	'target_id'    => $target_id,
						    	'month_id'     => $month_id,
						    	'year_id'      => $year_id,
						    	'employee_id'  => $employee_id,
						    	'category_id'  => $category_id,
						    	'target_value' => $target_result,
						    	'financial_id' => $this->session->userdata('active_year'),
						    	'method'       => '_updateProductTarget',
						    );
							// print_r($data);
							// exit;
						    $data_save = avul_call(API_URL.'target/api/product_target', $data);

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

			if($method == '_getEmployeeList')
			{
				$month_id = $this->input->post('month_id');
				$year_id  = $this->input->post('year_id');

				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id');
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
			    	$emp_whr = array(
			    		'month_id' => $month_id,
			    		'year_id'  => $year_id,
			    		'method'   => '_getEmployeeList',
			    	);

			    	$emp_list = avul_call(API_URL.'target/api/product_target', $emp_whr);
	            	$emp_res  = $emp_list['data'];


	        		$option ='<option value="">Select Value</option>';

	        		if(!empty($emp_res))
	        		{
	        			foreach ($emp_res as $key => $value) {
	        				$employee_id = !empty($value['employee_id'])?$value['employee_id']:'';
				            $emp_name    = !empty($value['emp_name'])?$value['emp_name']:'';
				            $target_val  = !empty($value['target_val'])?$value['target_val']:'';

	                        $option .= '<option value="'.$employee_id.'">'.$emp_name.'</option>';
	        			}
	        		}

	        		$response['status']  = 1;
			        $response['message'] = 'success'; 
			        $response['data']    = $option;
			        echo json_encode($response);
			        return; 
			    }
			}			

			else if($method == 'getProductData')
			{
				$month_id     = $this->input->post('month_id');
    			$year_id      = $this->input->post('year_id');
    			$category_id  = $this->input->post('category_id');

    			$error    = FALSE;
    			$required = array('month_id', 'year_id', 'category_id');
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
			    	
			    	// Product List
					$where_1  = array(
						'category_id'    => $category_id,
						'method'         => '_listCategoryProducts'
					);

					$cat_list = avul_call(API_URL.'catlog/api/product', $where_1);
					$cat_val  = $cat_list['data'];

	            	if($cat_val)
	            	{	
	            		$num   = 1;
	            		$table = '';
	            		foreach ($cat_val as $key => $val_1) {
	            				
						    $type_id     = !empty($val_1['type_id'])?$val_1['type_id']:'';
				            $product_id  = !empty($val_1['product_id'])?$val_1['product_id']:'';
				            $description = !empty($val_1['description'])?$val_1['description']:'';

						    $table .= '
			    			<tr class="row_'.$num.'">
                                <td>'.$num.'</td>
                                <td>'.$description.'</td>
                                <td>
                                	<input type="text" data-te="'.$num.'" name="target_val[]" id="target_val'.$num.'" class="form-control target_val'.$num.' target_val int_value" placeholder="Target Value"> 

                                	<input type="hidden" data-te="'.$num.'" name="product_id[]" id="product_id'.$num.'" class="form-control product_id'.$num.' product_id" placeholder="Target Value" value="'.$product_id.'">

                                	<input type="hidden" data-te="'.$num.'" name="type_id[]" id="type_id'.$num.'" class="form-control type_id'.$num.' type_id" placeholder="Target Value" value="'.$type_id.'">

                                	<input type="hidden" data-te="'.$num.'" name="auto_id[]" id="auto_id'.$num.'" class="form-control auto_id'.$num.' auto_id" placeholder="Target Value" value="'.$num.'">
                                </td>
                                <td class="buttonlist p-l-0 text-center">
		                            <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6 remove_item"><span class="ft-minus-square"></span></button>
		                        </td>
                            </tr>';

                            $num++;
	            		}

	            		$response['status']  = 1;
				        $response['message'] = 'Success'; 
				        $response['data']    = $table;
				        echo json_encode($response);
				        return;
	            	}
	            	else
	            	{
	            		$response['status']  = 0;
				        $response['message'] = 'Data Not Found'; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
	            	}
			    }
			}

			else
			{
				if($param1 =='Edit')
				{
					$target_id = !empty($param2)?$param2:'';

					$where = array(
	            		'target_id' => $target_id,
	            		'method'    => '_detailProductTarget'
	            	);

	            	$data_list   = avul_call(API_URL.'target/api/product_target',$where);
	            	$data_value  = $data_list['data'];
	            	$target_data = !empty($data_value['target_data'])?$data_value['target_data']:'';

	            	$month_id = !empty($target_data['month_id'])?$target_data['month_id']:'';
            		$year_id  = !empty($target_data['year_id'])?$target_data['year_id']:'';

	            	$emp_whr = array(
			    		'month_id' => $month_id,
			    		'year_id'  => $year_id,
			    		'method'   => '_getEmployeeList',
			    	);

			    	$emp_list = avul_call(API_URL.'target/api/product_target', $emp_whr);
	            	$emp_res  = $emp_list['data'];

					$page['dataval']    = $data_list['data'];
					$page['emp_val']    = $emp_res;
					$page['method']     = 'BTBM_X_U';
					$page['page_title'] = "Edit Product Target";
				}
				else
				{
					$page['dataval']    = '';
					$page['emp_val']    = '';
					$page['method']     = 'BTBM_X_C';
					$page['page_title'] = "Add Product Target";
				}

				$where_2 = array(
            		'method'   => '_listMonth'
            	);

            	$month_data = avul_call(API_URL.'master/api/month',$where_2);
            	$month_list = !empty($month_data['data'])?$month_data['data']:'';

	        	$where_2 = array(
	        		'method'   => '_listYear'
	        	);

	        	$year_data = avul_call(API_URL.'master/api/year',$where_2);
	        	$year_list = !empty($year_data['data'])?$year_data['data']:'';

	        	// Product List
				$where_3  = array(
					'method' => '_listCategory'
				);

				$pdt_list = avul_call(API_URL.'catlog/api/category', $where_3);
				$pdt_val  = $pdt_list['data'];

	        	$page['month_list']   = $month_list;
	        	$page['year_list']    = $year_list;
	        	$page['product_list'] = $pdt_val;
				$page['main_heading'] = "Target";
				$page['sub_heading']  = "Target";
				$page['pre_title']    = "List Product Target";
				$page['page_access']  = userAccess('product-target-view');
				$page['pre_menu']     = "index.php/admin/target/list_product_target";
				$data['page_temp']    = $this->load->view('admin/target/add_product_target',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_product_target";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function list_product_target($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			if($param1 == 'data_list')
			{
				if(userAccess('product-target-view'))
				{
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
	            		'offset'          => $_offset,
	            		'limit'           => $limit,
	            		'search'          => $search,
	            		'financial_year'  => $this->session->userdata('active_year'),
	            		'method'          => '_manageProductTarget',
	            	);

	            	$data_list  = avul_call(API_URL.'target/api/product_target',$where);
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
		            	foreach ($data_value as $key => $val) {
							$id=$_offset + $i;
		            		$target_id     = !empty($val['target_id'])?$val['target_id']:'';
						    $month_name    = !empty($val['month_name'])?$val['month_name']:'';
						    $year_value    = !empty($val['year_value'])?$val['year_value']:'';
						    $employee_name = !empty($val['emp_name'])?$val['emp_name']:'';
						    $total_target  = !empty($val['total_target'])?$val['total_target']:'';
						    $category_name = !empty($val['category_name'])?$val['category_name']:'';
						    $active_status = !empty($val['status'])?$val['status']:'';
						    $createdate    = !empty($val['createdate'])?$val['createdate']:'';

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
				            if(userAccess('product-target-edit') == TRUE)
				            {
				            	$edit = '<a href="'.BASE_URL.'index.php/admin/target/add_product_target/Edit/'.$target_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
				            }
				            if(userAccess('product-target-delete') == TRUE)
				            {
				            	$delete = '<a data-row="'.$i.'" data-id="'.$target_id.'" data-value="admin" data-cntrl="target" data-func="list_product_target" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
				            }

			                $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$id.'</td>
	                                <td class="line_height">'.$month_name.'</td>
	                                <td class="line_height">'.$year_value.'</td>
	                                <td class="line_height">'.mb_strimwidth($employee_name, 0, 10, '...').'</td>
	                                <td class="line_height">'.mb_strimwidth($category_name, 0, 10, '...').'</td>
	                                <td class="line_height">'.$total_target.'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                                if(userAccess('product-target-edit') == TRUE || userAccess('product-target-delete') == TRUE):
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
				    	'target_id' => $id,
				    	'method'    => '_deleteProductTarget'
				    );

				    $data_save = avul_call(API_URL.'target/api/product_target',$data);

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

			else
        	{
        		$page['random_val']   = $param1;
				$page['main_heading'] = "Target";
				$page['sub_heading']  = "Target";
				$page['page_title']   = "List Product Target";
				$page['pre_title']    = "Add Product Target";
				$page['page_access']  = userAccess('product-target-add');
				$page['pre_menu']     = "index.php/admin/target/add_product_target";
				$data['page_temp']    = $this->load->view('admin/target/list_product_target',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_product_target";
				$this->bassthaya->load_admin_form_template($data);
			}	
		}

		public function add_beat_target($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage    = $this->input->post('formpage');
			$method      = $this->input->post('method');
			$month_id    = $this->input->post('month_id');
		    $year_id     = $this->input->post('year_id');
		    $employee_id = $this->input->post('employee_id');
		    $target_val  = $this->input->post('target_val');
		    $zone_id     = $this->input->post('zone_id');


			if($formpage =='BTBM_X_P')
			{
				$error    = FALSE;
				$required = array('month_id', 'year_id', 'employee_id');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if(count(array_filter($zone_id))!==count($zone_id) || $error == TRUE)
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
				    	if(userAccess('beat-target-add'))
				    	{
				    		$target_value = [];
			            	$type_cnt = count($zone_id); 

			            	for ($i=0; $i < $type_cnt; $i++) {

			            		$target_value[] = array(
			            			'zone_id'    => $zone_id[$i],
			            			'target_val' => $target_val[$i],
			            		);
			            	}

			            	$target_result = json_encode($target_value);

			            	$data = array(
			            		'month_id'     => $month_id,
						    	'year_id'      => $year_id,
						    	'employee_id'  => $employee_id,
						    	'target_value' => $target_result,
						    	'financial_id' => $this->session->userdata('active_year'),
						    	'method'       => '_addBeatTarget',
						    );

						    $data_save = avul_call(API_URL.'target/api/beat_target', $data);

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
			    		if(userAccess('beat-target-edit'))
			    		{
			    			$target_id  = $this->input->post('target_id');
				    		$auto_id    = $this->input->post('auto_id');
				    		$auto_count = count($auto_id);

			            	$target_value  = [];
			            	$j = 1;
			            	for ($i=0; $i < $auto_count; $i++) {

			            		$target_value[] = array(
			            			'auto_id'    => $auto_id[$i],
			            			'zone_id'    => $zone_id[$i],
			            			'target_val' => $target_val[$i],
			            		);
			            	}

			            	$target_result = json_encode($target_value);

			            	$data = array(
						    	'target_id'    => $target_id,
						    	'month_id'     => $month_id,
						    	'year_id'      => $year_id,
						    	'employee_id'  => $employee_id,
						    	'target_value' => $target_result,
						    	'financial_id' => $this->session->userdata('active_year'),
						    	'method'       => '_updateBeatTarget',
						    );

						    $data_save = avul_call(API_URL.'target/api/beat_target', $data);

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

			if($method == '_getEmployeeList')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id');
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
			    	$emp_whr = array(
			    		'month_id' => $month_id,
			    		'year_id'  => $year_id,
			    		'method'   => '_getEmployeeList',
			    	);

			    	$emp_list = avul_call(API_URL.'target/api/product_target', $emp_whr);
	            	$emp_res  = $emp_list['data'];


	        		$option ='<option value="">Select Value</option>';

	        		if(!empty($emp_res))
	        		{
	        			foreach ($emp_res as $key => $value) {
	        				$employee_id = !empty($value['employee_id'])?$value['employee_id']:'';
				            $emp_name    = !empty($value['emp_name'])?$value['emp_name']:'';
				            $target_val  = !empty($value['target_val'])?$value['target_val']:'';

	                        $option .= '<option value="'.$employee_id.'">'.$emp_name.'</option>';
	        			}
	        		}

	        		$response['status']  = 1;
			        $response['message'] = 'success'; 
			        $response['data']    = $option;
			        echo json_encode($response);
			        return; 
			    }
			}	

			if($method == 'getBeatData')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id', 'employee_id');
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
			    	$beat_whr = array(
			    		'month_id'    => $month_id,
			    		'year_id'     => $year_id,
			    		'employee_id' => $employee_id,
			    		'method'      => '_getBeatList',
			    	);

			    	$beat_list = avul_call(API_URL.'target/api/beat_target', $beat_whr);
	            	$beat_res  = $beat_list['data'];

	            	if($beat_res)
	            	{	
	            		$table = '';
	            		$num   = 1;
	            		foreach ($beat_res as $key => $beat_val) {
	            			$zone_id   = !empty($beat_val['zone_id'])?$beat_val['zone_id']:'';
            				$zone_name = !empty($beat_val['zone_name'])?$beat_val['zone_name']:'';

            				$table .= '
			    			<tr class="row_'.$num.'">
                                <td>'.$num.'</td>
                                <td>'.$zone_name.'</td>
                                <td>
                                	<input type="text" data-te="'.$num.'" name="target_val[]" id="target_val'.$num.'" class="form-control target_val'.$num.' target_val int_value" placeholder="Target Value"> 

                                	<input type="hidden" data-te="'.$num.'" name="zone_id[]" id="zone_id'.$num.'" class="form-control zone_id'.$num.' zone_id" placeholder="Target Value" value="'.$zone_id.'">

                                	<input type="hidden" data-te="'.$num.'" name="auto_id[]" id="auto_id'.$num.'" class="form-control auto_id'.$num.' auto_id" placeholder="Target Value" value="'.$num.'">
                                </td>
                                <td class="buttonlist p-l-0 text-center">
		                            <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6 remove_item"><span class="ft-minus-square"></span></button>
		                        </td>
                            </tr>';

            				$num++;
	            		}

	            		$response['status']  = 1;
				        $response['message'] = 'Success'; 
				        $response['data']    = $table;
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

			else
			{
				if($param1 =='Edit')
				{
					$target_id = !empty($param2)?$param2:'';

					$where = array(
	            		'target_id' => $target_id,
	            		'method'    => '_detailBeatTarget'
	            	);

	            	$data_list   = avul_call(API_URL.'target/api/beat_target',$where);
	            	$data_value  = $data_list['data'];
	            	$target_data = !empty($data_value['target_data'])?$data_value['target_data']:'';

	            	$month_id = !empty($target_data['month_id'])?$target_data['month_id']:'';
            		$year_id  = !empty($target_data['year_id'])?$target_data['year_id']:'';

	            	$emp_whr = array(
			    		'month_id' => $month_id,
			    		'year_id'  => $year_id,
			    		'method'   => '_getEmployeeList',
			    	);

			    	$emp_list = avul_call(API_URL.'target/api/product_target', $emp_whr);
	            	$emp_res  = $emp_list['data'];

					$page['dataval']    = $data_list['data'];
					$page['emp_val']    = $emp_res;
					$page['method']     = 'BTBM_X_U';
					$page['page_title'] = "Edit Product Target";
				}
				else
				{
					$page['dataval']    = '';
					$page['emp_val']    = '';
					$page['method']     = 'BTBM_X_C';
					$page['page_title'] = "Add Beat Target";
				}

				$where_2 = array(
            		'method'   => '_listMonth'
            	);

            	$month_data = avul_call(API_URL.'master/api/month',$where_2);
            	$month_list = !empty($month_data['data'])?$month_data['data']:'';

	        	$where_2 = array(
	        		'method'   => '_listYear'
	        	);

	        	$year_data = avul_call(API_URL.'master/api/year',$where_2);
	        	$year_list = !empty($year_data['data'])?$year_data['data']:'';

	        	$page['month_list']   = $month_list;
	        	$page['year_list']    = $year_list;
				$page['main_heading'] = "Target";
				$page['sub_heading']  = "Target";
				$page['pre_title']    = "List Beat Target";
				$page['page_access']  = userAccess('beat-target-view');
				$page['pre_menu']     = "index.php/admin/target/list_beat_target";
				$data['page_temp']    = $this->load->view('admin/target/add_beat_target',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_beat_target";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function list_beat_target($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			if($param1 == 'data_list')
			{
				if(userAccess('beat-target-view'))
				{
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
	            		'offset'          => $_offset,
	            		'limit'           => $limit,
	            		'search'          => $search,
	            		'financial_year'  => $this->session->userdata('active_year'),
	            		'method'          => '_manageBeatTarget',
	            	);

	            	$data_list  = avul_call(API_URL.'target/api/beat_target',$where);
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
		            	foreach ($data_value as $key => $val) {

		            		$target_id     = !empty($val['target_id'])?$val['target_id']:'';
						    $month_name    = !empty($val['month_name'])?$val['month_name']:'';
						    $year_value    = !empty($val['year_value'])?$val['year_value']:'';
						    $employee_name = !empty($val['emp_name'])?$val['emp_name']:'';
						    $active_status = !empty($val['status'])?$val['status']:'';
						    $createdate    = !empty($val['createdate'])?$val['createdate']:'';

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
				            if(userAccess('beat-target-edit') == TRUE)
				            {
				            	$edit = '<a href="'.BASE_URL.'index.php/admin/target/add_beat_target/Edit/'.$target_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
				            }
				            if(userAccess('beat-target-delete') == TRUE)
				            {
				            	$delete = '<a data-row="'.$i.'" data-id="'.$target_id.'" data-value="admin" data-cntrl="target" data-func="list_beat_target" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
				            }

			                $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$month_name.'</td>
	                                <td class="line_height">'.$year_value.'</td>
	                                <td class="line_height">'.mb_strimwidth($employee_name, 0, 10, '...').'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                                if(userAccess('beat-target-edit') == TRUE || userAccess('beat-target-delete') == TRUE):
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
				    	'target_id' => $id,
				    	'method'    => '_deleteBeatTarget'
				    );

				    $data_save = avul_call(API_URL.'target/api/beat_target',$data);

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

			else
        	{
        		$page['random_val']   = $param1;
				$page['main_heading'] = "Target";
				$page['sub_heading']  = "Target";
				$page['page_title']   = "List Beat Target";
				$page['pre_title']    = "Add Beat Target";
				$page['page_access']  = userAccess('beat-target-add');
				$page['pre_menu']     = "index.php/admin/target/add_beat_target";
				$data['page_temp']    = $this->load->view('admin/target/list_beat_target',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_beat_target";
				$this->bassthaya->load_admin_form_template($data);
			}	
		}
	}
?>
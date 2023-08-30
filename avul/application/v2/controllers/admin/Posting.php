<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Posting extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function add_assign_product($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');
			$assign_id      = $this->input->post('assign_id');
			$distributor_id = $this->input->post('distributor_id');
			$state_id       = $this->input->post('state_id');
			$city_id        = $this->input->post('city_id');
			$zone_id        = $this->input->post('zone_id');
			$type_id        = $this->input->post('type_id');
			$minimum_stock  = $this->input->post('minimum_stock');

			if($formpage =='BTBM_X_P')
			{
				if($method == 'BTBM_X_C')
				{
					if(userAccess('assign-product-add'))
					{
						$error = FALSE;
					    $errors = array();
						$required = array('distributor_id', 'zone_id', 'category_id');
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
					    	$type_val = '';
					    	if(!empty($type_id))
					    	{
					    		$type_val = implode(',', $type_id);
					    	}

					    	$data = array(
								'distributor_id' => $distributor_id,
								'zone_id'        => $zone_id,
								'category_id'    => $category_id,
								'type_id'        => $type_val,
								'minimum_stock'  => $minimum_stock,
								'method'         => '_addAssignProduct',
							);

							$data_save = avul_call(API_URL.'assignproduct/api/add_assign_product',$data);

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
					if(userAccess('assign-product-edit'))
					{
						$error = FALSE;
					    $errors = array();
						$required = array('assign_id', 'distributor_id', 'state_id', 'city_id', 'zone_id', 'type_id');
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
					    	$city_val = '';
					    	if(!empty($city_id))
					    	{
					    		$city_val = implode(',', $city_id);
					    	}

					    	$zone_val = '';
					    	if(!empty($zone_id))
					    	{
					    		$zone_val = implode(',', $zone_id);
					    	}

					    	$pstatus     = $this->input->post('pstatus');

					    	$data = array(
					    		'assign_id'      => $assign_id,
					    		'distributor_id' => $distributor_id,
					    		'state_id'       => $state_id,
					    		'city_id'        => $city_val,
								'zone_id'        => $zone_val,
								'type_id'        => $type_id,
								'minimum_stock'  => $minimum_stock,
								'status'         => $pstatus,
								'ref_id'         => 0,
								'method'         => '_updateAssignProduct',
							);

							$data_save = avul_call(API_URL.'assignproduct/api/add_assign_product',$data);

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

			if($param1 =='getCity_name')
			{
				$state_id  = $this->input->post('state_id');
				$assign_id = $this->input->post('assign_id');

				// Get Assign Product Details
				$where_1 = array(
					'assign_id' => $assign_id,
					'method'    => '_detailAssignProduct',
				);

				$data_list = avul_call(API_URL.'assignproduct/api/list_assign_product',$where_1);
				$data_val  = $data_list['data'];
				$city_val  = !empty($data_val['city_id'])?$data_val['city_id']:'';

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

                        $select   = '';
        				$city_res = explode(',', $city_val);

                        if(in_array($city_id, $city_res))
                        {
                            $select = 'selected';
                        }

                        $option .= '<option value="'.$city_id.'" '.$select.'>'.$city_name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
			}

			if($param1 =='getZone_list')
			{
				$distributor_id = $this->input->post('distributor_id');

				$data = array(
			    	'distributor_id' => $distributor_id,
			    	'method'         => '_distributorZoneList',
			    );

			    $data_list = avul_call(API_URL.'distributors/api/distributors',$data);			
			    $data_val  = $data_list['data'];

			    $option ='<option value="">Select Value</option>';

			    if(!empty($data_val))
        		{
        			foreach ($data_val as $key => $value) {
        				$zone_id   = !empty($value['zone_id'])?$value['zone_id']:'';
        				$zone_name = !empty($value['zone_name'])?$value['zone_name']:'';

                        $option .= '<option value="'.$zone_id.'">'.$zone_name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 
			}

			if($param1 =='getZone_name')
			{
				$state_id  = $this->input->post('state_id');
				$city_id   = $this->input->post('city_id');
				$assign_id = $this->input->post('assign_id');

				$option ='';

				if(!empty($city_id))
				{
					$city_val  = implode(',', $city_id);

					// Get Assign Product Details
					$where_1 = array(
						'assign_id' => $assign_id,
						'method'    => '_detailAssignProduct',
					);

					$data_list = avul_call(API_URL.'assignproduct/api/list_assign_product',$where_1);
					$data_val  = $data_list['data'];
					$zone_val  = !empty($data_val['zone_id'])?$data_val['zone_id']:'';

					$where = array(
	            		'state_id' => $state_id,
	            		'city_id'  => $city_val,
	            		'method'   => '_listZone'
	            	);

	            	$zone_list   = avul_call(API_URL.'master/api/zone',$where);
	            	$zone_result = $zone_list['data'];

	        		if(!empty($zone_result))
	        		{
	        			foreach ($zone_result as $key => $value) {
	        				$zone_id   = !empty($value['zone_id'])?$value['zone_id']:'';
	                        $zone_name = !empty($value['zone_name'])?$value['zone_name']:'';

	                        $select   = '';
	        				$zone_res = explode(',', $zone_val);

	                        if(in_array($zone_id, $zone_res))
	                        {
	                            $select = 'selected';
	                        }

	                        $option .= '<option value="'.$zone_id.'" '.$select.'>'.$zone_name.'</option>';
	        			}
	        		}
				}

				$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 
			}

			if($param1 =='getProduct_list')
			{
				$category_id = $this->input->post('category_id');

				$data = array(
			    	'category_id' => $category_id,
			    	'method'      => '_listCategoryProducts',
			    );

			    $data_list = avul_call(API_URL.'catlog/api/product',$data);					
			    $data_val  = $data_list['data'];

			    $option ='<option value="">Select Value</option>';

			    if(!empty($data_val))
        		{
        			foreach ($data_val as $key => $value) {
        				$type_id     = !empty($value['type_id'])?$value['type_id']:'';
        				$description = !empty($value['description'])?$value['description']:'';
        				$product_id  = !empty($value['product_id'])?$value['product_id']:'';

                        $option .= '<option value="'.$type_id.'">'.$description.'</option>';
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
				if($param1 =='Edit')
				{
					// Assign Product Details
					$where_1 = array(
						'assign_id' => $param2,
						'method'    => '_detailAssignProduct',
					);

					$data_list   = avul_call(API_URL.'assignproduct/api/list_assign_product',$where_1);
					$data_val    = $data_list['data'];

					$dis_id   = !empty($data_val['distributor_id'])?$data_val['distributor_id']:'';
					$state_id = !empty($data_val['state_id'])?$data_val['state_id']:'';	
					$city_id  = !empty($data_val['city_id'])?$data_val['city_id']:'';	

					// City List
					$where_2 = array(
	            		'state_id' => $state_id,
	            		'method'   => '_listCity'
	            	);

	            	$city_list   = avul_call(API_URL.'master/api/city',$where_2);

	            	// Beat List
	            	$where_3 = array(
	            		'state_id' => $state_id,
	            		'city_id'  => $city_id,
	            		'method'   => '_listZone'
	            	);

	            	$zone_list   = avul_call(API_URL.'master/api/zone',$where_3);

	            	// Distributor Details
					$where_4 = array(
						'distributor_id' => $dis_id,
	            		'method'         => '_detailDistributors',
	            	);

	            	$distributor_list = avul_call(API_URL.'distributors/api/distributors', $where_4);

	            	$dis_state = !empty($distributor_list['data'][0]['state_id'])?$distributor_list['data'][0]['state_id']:'';

					$page['dataval']    = $data_list['data'];
					$page['state_res']  = $dis_state;
					$page['city_val']   = $city_list['data'];
					$page['zone_val']   = $zone_list['data'];
					$page['method']     = 'BTBM_X_U';
					$page['page_title'] = "Edit Assign Product";
				}
				else
				{
					$page['dataval']    = '';
					$page['method']     = 'BTBM_X_C';
					$page['page_title'] = "Add Assign Product";
				}

				// State List
				$where_1 = array(
            		'method' => '_listState',
            	);

            	$state_list = avul_call(API_URL.'master/api/state',$where_1);
            	$state_data = $state_list['data'];

            	$page['state_val']    = $state_data;
				$page['main_heading'] = "Assign Product";
				$page['sub_heading']  = "Assign Product";
				$page['pre_title']    = "List Assign Product";
				$page['page_access']  = userAccess('assign-product-view');
				$page['pre_menu']     = "index.php/admin/assignproduct/list_assign_product";
				$data['page_temp']    = $this->load->view('admin/assign_product/add_assign_product',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "distributor_list";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function list_assign_product($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			if($param1 == 'data_list')
			{
				if(userAccess('assign-product-view'))
				{
					$limit      = $this->input->post('limitval');
	            	$page       = $this->input->post('page');
	            	$search     = $this->input->post('search');
					$random_val = $this->input->post('random_val');
	            	$cur_page   = isset($page)?$page:'1';
	            	$_offset    = ($cur_page-1) * $limit;
	            	$nxt_page   = $cur_page + 1;
	            	$pre_page   = $cur_page - 1;

	            	$where = array(
	            		'offset'         => $_offset,
	            		'limit'          => $limit,
	            		'search'         => $search,
	            		'distributor_id' => $random_val,
	            		'method'         => '_listAssignProductPaginate',
	            	);

					

	            	$data_list  = avul_call(API_URL.'assignproduct/api/list_assign_product',$where);
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

				            $assign_id        = !empty($value['assign_id'])?$value['assign_id']:'';
				            $distributor_name = !empty($value['distributor_name'])?$value['distributor_name']:'';
				            $category_name    = !empty($value['category_name'])?$value['category_name']:'';
				            $description      = !empty($value['description'])?$value['description']:'';
				            $stock            = !empty($value['stock'])?$value['stock']:'0';
				            $zone_status      = !empty($value['zone_status'])?$value['zone_status']:'';
				            $active_status    = !empty($value['status'])?$value['status']:'0';
				            $createdate       = !empty($value['createdate'])?$value['createdate']:'';

				            if($active_status == '1')
			                {
			                	$status_view = '<span class="badge badge-success">Active</span>';
			                }
			                else
			                {
			                	$status_view = '<span class="badge badge-danger">In Active</span>';
			                }

			                if($zone_status == 1)
			                {
			                	$beat_view = '<span class="badge badge-success">Beat Assign</span>';
			                }
			                else
			                {
			                	$beat_view = '<span class="badge badge-danger">Beat Not Assign</span>';
			                }

			                $edit   = '';
				            $delete = '';
				            if(userAccess('assign-product-edit') == TRUE)
				            {
				            	$edit = '<a href="'.BASE_URL.'index.php/admin/assignproduct/add_assign_product/Edit/'.$assign_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
				            }
				            if(userAccess('assign-product-delete') == TRUE)
				            {
				            	$delete = '<a data-row="'.$i.'" data-id="'.$assign_id.'" data-value="admin" data-cntrl="assignproduct" data-func="list_assign_product" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
				            }

			                $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.mb_strimwidth($description, 0, 40, '...').'</td>
	                                <td class="line_height">'.$beat_view.'</td>
	                                <td class="line_height">'.$stock.'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                                if(userAccess('assign-product-edit') == TRUE || userAccess('assign-product-delete') == TRUE):
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
				    	'assign_id' => $id,
				    	'method'    => '_deleteAssignProduct'
				    );

				    $data_save = avul_call(API_URL.'assignproduct/api/list_assign_product',$data);

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
				$page['main_heading'] = "Assign Product";
				$page['sub_heading']  = "Assign Product";
				$page['page_title']   = "List Assign Product";
				$page['pre_title']    = "Add Assign Product";
				$page['pre_menu']     = "index.php/admin/assignproduct/add_assign_product";
				$data['page_temp']    = $this->load->view('admin/assign_product/list_assign_product',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "distributor_list";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function employee_list($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Employee Posting";
				$page['sub_heading']  = "Employee Posting";
				$page['page_title']   = "List Employee";
				$page['pre_title']    = "Add Employee";
				$page['pre_menu']     = "index.php/admin/Employee/add_employee";
				$data['page_temp']    = $this->load->view('admin/posting/list_employee',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "employee_list";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('assign-product-view'))
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
	            		'company_type' => 1,
	            		'company_id'   => 0,
						'ref_id'       => 0,
						'sort_column' => $sort_column,
						'sort_type' => $sort_type,
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
						    $pincode      = !empty($value['pincode'])?$value['pincode']:'';
							$street_name      = !empty($value['street_name'])?$value['street_name']:'';
							$door_no      = !empty($value['door_no'])?$value['door_no']:'';
						    $active_status = !empty($value['status'])?$value['status']:'';
						    $designation_code    = !empty($value['designation_code'])?$value['designation_code']:'';
							$posting_status = !empty($value['posting_status'])?$value['posting_status']:'';

		            	
						    if($active_status == '1')
			                {
			                	$status_view = '<span class="badge badge-success">Active</span>';
			                }
			                else
			                {
			                	$status_view = '<span class="badge badge-danger">In Active</span>';
			                }
							
						    if($posting_status == '1')
			                {
			                	$post_status = '<span class="badge badge-success">Allocated</span>';
			                }
			                else
			                {
			                	$post_status = '<span class="badge badge-danger">not Allocated</span>';
			                }

			                $list_1   = '';
			                $list_2   = '';
				            if(userAccess('assign-product-view') == TRUE)
				            {
				            	$list_1 = '<a href="' . BASE_URL . 'index.php/admin/posting/add_posting/Edit/' . $employee_id . '" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';

				            	
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.mb_strimwidth($username, 0, 20, '...').'</td>
									<td class="line_height">'.$mobile.'</td>
	                                <td class="line_height">'.mb_strimwidth($designation_code, 0, 20, '...').'</td>
									<td class="line_height">'.$status_view.'</td>
	                                <td class="line_height">'.$post_status.'</td>';
	                                if(userAccess('assign-product-view') == TRUE):
		                            	$table .= '<td>'.$list_1.'</td>';
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
		}

        public function add_posting($param1 = "", $param2 = "", $param3 = "")
        {
            if ($this->session->userdata('random_value') == '')
                redirect(base_url() . 'index.php?login', 'refresh');
    
            $formpage = $this->input->post('formpage');
    
            if ($formpage == 'BTBM_X_P') {
                $error = FALSE;
                $asmstate_id       = $this->input->post('asmstate_id');
                $astate_id       = $this->input->post('astate_id');
                $password           = $this->input->post('password');
                $employee_id     = $this->input->post('employee_id');
			
                $grade_id        = $this->input->post('grade_id');
                $method             = $this->input->post('method');
                $mzone                = $this->input->post('mzone');
				$bmzone                = $this->input->post('bmzone');
                $ascity_id              = $this->input->post('ascity_id');
                $acity_id                = $this->input->post('acity_id');
              


				if($grade_id=="RSM"){
					$required = array( 'grade_id','astate_id');
					
				}else if($grade_id=="ASM"){
					$required = array( 'grade_id','asmstate_id');
					
				}else if($grade_id=="SO"){
					$required = array( 'grade_id','asmstate_id','acity_id');
				
				}else if($grade_id=="TSI"){
					$required = array( 'grade_id','asmstate_id','ascity_id','mzone');
			
				}else if($grade_id=="BDE"){
					$required = array( 'grade_id','asmstate_id','ascity_id','bmzone');
					
				}
            
               
    
                foreach ($required as $field) {
                    if (empty($this->input->post($field))) {
                        $error = TRUE;
                    }
                }
    
                if ($error == TRUE) {
                    $response['status']  = 0;
                    $response['message'] = "Please fill all required fields";
                    $response['data']    = [];
                    $response['error']   = [];
                    echo json_encode($response);
                    return;
                } else {
    
					if($grade_id=="RSM"){
					
						$control_st_id                      =(",".implode(",",$astate_id).",");
						$control_ct_id                      ='';
						$control_zn_id                      ='';
					}else if($grade_id=="ASM"){
					
						$control_st_id                      =(",".$asmstate_id.",");
						$control_ct_id                      ='';
						$control_zn_id                      ='';
					}else if($grade_id=="SO"){
						
						$control_st_id                      =(",".$asmstate_id.",");
						$control_ct_id                      =(",".implode(",",$acity_id).",");
						$control_zn_id                      ='';
					}else if($grade_id=="TSI"){
						
						$control_st_id                      =(",".$asmstate_id.",");
						$control_ct_id                      =(",".$ascity_id.",");
						$control_zn_id                      =(",".implode(",",$mzone).",");
					}else if($grade_id=="BDE"){
					
						$control_st_id                      =(",".$asmstate_id.",");
						$control_ct_id                      =(",".$ascity_id.",");
						$control_zn_id                      = (",".$bmzone.",");
					}
    
                    if ($method == 'BTBM_X_C') {
                       
    
    
                            $data = array(
                                'control_zn_id'      => $control_zn_id,
                                'control_ct_id'      => $control_ct_id,
                                'control_st_id'      => $control_st_id,
                                'employee_id'        => $employee_id,
                                'method'             => '_addManagers',
                                'grade'              => $grade_id
                            );

                            $data_save = avul_call(API_URL . 'managers/api/managers', $data);
    
    
    
    
                            if ($data_save['status'] == 1) {
                                $response['status']  = 1;
                                $response['message'] = $data_save['message'];
                                $response['data']    = [];
                                echo json_encode($response);
                                return;
                            } else {
                                $response['status']  = 0;
                                $response['message'] = $data_save['message'];
                                $response['data']    = [];
                                echo json_encode($response);
                                return;
                            }
                       
                    }
                    
                    else {
                        
                            
                           
    
                            
                            $data = array(
                                'employee_id'     => $employee_id,
                                'control_zn_id'      => $control_zn_id,
                                'control_ct_id'      => $control_ct_id,
                                'control_st_id'      => $control_st_id,
                                'method'             => '_updateManagers',
                                'grade'              => $grade_id
                            );
						

                            $data_save = avul_call(API_URL . 'managers/api/managers', $data);
    
                            if ($data_save['status'] == 1) {
                                $response['status']  = 1;
                                $response['message'] = $data_save['message'];
                                $response['data']    = [];
                                echo json_encode($response);
                                return;
                            } else {
                                $response['status']  = 0;
                                $response['message'] = $data_save['message'];
                                $response['data']    = [];
                                echo json_encode($response);
                                return;
                            }
                       
                    }
                }
            }else if($param1 == 'get_state'){
                $role_id             = $this->input->post('role_id');

    
                $option ='<option value="">Select Value</option>';
    
                // if($role_id==1){
                    $wher = array(
                        'grade' =>$role_id ,
                        'method' => 'getstate'
                    );
                    $state_rsm = avul_call(API_URL . 'managers/api/managers', $wher);
    
                    if(!empty($state_rsm['data']))
                        {
                    // print_r($state_rsm['data']);
                            
                            foreach ($state_rsm['data'] as $key => $value) {
                    
                                
                                $ast_id   = !empty($value['id'])?$value['id']:'';
                                $ast_name = !empty($value['state_name'])?$value['state_name']:'';
                                
                                
                                $option .= '<option value="'.$ast_id.'" >'.$ast_name.'</option>';
                            }
                            
                            
                        }
                        
                    $response['grade']  = $role_id;
                    $response['status']  = 1;
                    $response['message'] = 'success'; 
                    $response['data']    = $option;
                    echo json_encode($response);
                    return; 
                // }
                // else if($role_id==2){
    
                // }
                // else if($role_id==3){
    
                // }
                // else if($role_id==4){
    
                // }else{
    
                // }
                
            }else if($param1 == 'get_city'){
                $role_id             = $this->input->post('role_id');
                $state_id             = $this->input->post('state_id');
				
                $option ='<option value="">Select Value</option>';
				
                 if($role_id=="RSM"||$role_id=="ASM"){
                   
                 }else{

                    $wher = array(
                        'grade' =>$role_id ,
                        'state_id' => $state_id,
                        'method' => 'getCity'
                    );
				
                    $state_rsm = avul_call(API_URL . 'managers/api/managers', $wher);
				
                    if(!empty($state_rsm['data']))
                        {
                    // print_r($state_rsm['data']);
                            
                            foreach ($state_rsm['data'] as $key => $value) {
                    
                                
                                $acity_id   = !empty($value['id'])?$value['id']:'';
                                $acity_name = !empty($value['city_name'])?$value['city_name']:'';
                                
                                
                                $option .= '<option value="'.$acity_id.'" >'.$acity_name.'</option>';
                            }
                            
                            
                        }
					
                    $response['grade']  = $role_id;
                    $response['status']  = 1;
                    $response['message'] = 'success'; 
                    $response['data']    = $option;
                    echo json_encode($response);
                    return;} 
                // }
                // else if($role_id==2){
    
                // }
                // else if($role_id==3){
    
                // }
                // else if($role_id==4){
    
                // }else{
    
                // }
                
            }else if($param1 == 'get_zone'){
                $role_id             = $this->input->post('role_id');
                $state_id             = $this->input->post('state_id');
                $city_id             = $this->input->post('city_id');
    
                $option ='<option value="">Select Value</option>';
    
                 if($role_id=="RSM"||$role_id=="ASM"||$role_id == "SO"){
                    
                 }else{
				
                    $wher = array(
                        'grade' =>$role_id ,
                        'state_id' => $state_id,
                        'city_id' => $city_id,
                        'method' => 'getZone'
                    );
                
                    $state_rsm = avul_call(API_URL . 'managers/api/managers', $wher);
				
                    if(!empty($state_rsm['data']))
                        {
                    // print_r($state_rsm['data']);
                            
                            foreach ($state_rsm['data'] as $key => $value) {
                    
                                
                                $azone_id   = !empty($value['id'])?$value['id']:'';
                                $azone_name = !empty($value['name'])?$value['name']:'';
                                
                                
                                $option .= '<option value="'.$azone_id.'" >'.$azone_name.'</option>';
                            }
                            
                            
                        }
                        
                    $response['grade']  = $role_id;
                    $response['status']  = 1;
                    $response['message'] = 'success'; 
                    $response['data']    = $option;
                    echo json_encode($response);
                    return;} 
                // }
                // else if($role_id==2){
    
                // }
                // else if($role_id==3){
    
                // }
                // else if($role_id==4){
    
                // }else{
    
                // }
                
            }
             else {
                if ($param1 == 'Edit') {
                    $employee_id = !empty($param2) ? $param2 : '';
                   
                  
                    $where = array(
                        'employee_id' => $employee_id,
                        'method'         => '_detailEmployee'
                    );
					
                    $data_list  = avul_call(API_URL . 'employee/api/employee', $where);
                    
                    $data_value = $data_list['data'];
                
                    $role_id        = !empty($data_value[0]['designation_id']) ? $data_value[0]['designation_id'] : '';
					$posting_status        = !empty($data_value[0]['posting_status']) ? $data_value[0]['posting_status'] : '';
					$designation_code      = !empty($data_value[0]['designation_code']) ? $data_value[0]['designation_code'] : '';
					
					if($posting_status==1){

						$where = array(
							'employee_id' => $employee_id,
							'method'         => '_detailmanagers'
						);
					
						$data3_list  = avul_call(API_URL . 'managers/api/managers', $where);
						
						$data_value =$data3_list['data'];
						
						$avai_state_val = !empty($data_value[0]['ctrl_state_id']) ? $data_value[0]['ctrl_state_id'] : '';
						
						if(!empty($avai_state_val)){
							foreach ($avai_state_val as $key => $value) {
							   $st   = !empty($value['id'])?$value['id']:'';	
							}
						}
						
						$avai_city_val  = !empty($data_value[0]['ctrl_city_id']) ? $data_value[0]['ctrl_city_id'] : '';
						if(!empty($avai_city_val)){
							foreach ($avai_city_val as $key => $value) {
							   $ct   = !empty($value['id'])?$value['id']:'';	
							}
						}
						$avai_zone_val  = !empty($data_value[0]['ctrl_zone_id']) ? $data_value[0]['ctrl_zone_id'] : '';
					
						if(!empty($avai_state_val)){
						
							$wher = array(
								'grade' =>$designation_code ,
								'method' => 'getstate'
							);
						
							$state_by = avul_call(API_URL . 'managers/api/managers', $wher);
							$data_state = $state_by['data'];
							
							if($designation_code !='RSM' || $designation_code !='ASM'){
								foreach( $data_state as $value){
									array_push($avai_state_val,$value);
								}
							}else{
								$avai_state_val=$data_state;
							
							}
							
						}
						
						if(!empty($avai_city_val)){
							$wher_c = array(
								'grade' =>$designation_code ,
								"state_id" => $st,
		
								'method' => 'getCity'
							);
							
							$city_by = avul_call(API_URL . 'managers/api/managers', $wher_c);
							$data_city = $city_by['data'];

							if($designation_code =="SO"){
							 foreach( $data_city as $value){
								array_push($avai_city_val,$value);
							 }
						    }else{
								$avai_city_val =$data_city;
							}
			
						}
						
						if(!empty($avai_zone_val)){
							$wher_z = array(
								'grade' =>$designation_code ,
								"state_id" => $st,
								"city_id" => $ct,
								'method' => 'getZone'
							);
							$zone_by = avul_call(API_URL . 'managers/api/managers', $wher_z);
							$data_zone = $zone_by['data'];
							foreach( $data_zone as $value){
								array_push($avai_zone_val,$value);
								}
			
						}
						
					   
							
				   
						// print_r($avai_state_val);exit;
						
						$page['dataval']          = $data_value;
						// $page['type_val']         = $type_list['data'];
						$page['avai_state_val']   = $avai_state_val;
						$page['avai_city_val']   = $avai_city_val;
						$page['avai_zone_val']   = $avai_zone_val;
						$page['method']     = 'BTBM_X_U';
						$page['page_title'] = "Edit Posting";
					
					}else{
						
						$wher = array(
							'grade' =>$designation_code ,
							'method' => 'getstate'
						);
						$state_rsm = avul_call(API_URL . 'managers/api/managers', $wher);
						
						$page['dataval']          = $data_list['data'];
						$page['avai_state_val']   = $state_rsm['data'];
					
						$page['city_val']   = '';
						$page['zone_val']   = '';
						$page['method']     = 'BTBM_X_C';
						$page['page_title'] = "Create Posting";
					}
				}
                   
    
                $where_1 = array(
    
    
                    'method'         => '_listDesignation',
                );
    
                $designation_list = avul_call(API_URL . 'employee/api/employee_designation', $where_1);
                $designation_value = !empty($designation_list['data']) ? $designation_list['data'] : '';
    
                // State List
                $where_2 = array(
                    'method' => '_listState',
                );
    
                $state_list = avul_call(API_URL . 'master/api/state', $where_2);
                $state_data = $state_list['data'];
    
                // // Vendor List
                // $where_3 = array(
                // 	'method' => '_listManufacturerVendors',
                // );
    
                // $vendor_list = avul_call(API_URL.'vendors/api/vendors',$where_3);
                // $vendor_data = $vendor_list['data'];
    
                $page['state_val']    = $state_data;
                $page['grade_val']     = $designation_value;
                
                $page['main_heading'] = "Manager";
                $page['sub_heading']  = "Manager";
                $page['pre_title']    = "List Empolyees";
                $page['page_access']  = userAccess('distributors-view');
                $page['pre_menu']     = "index.php/admin/Posting/employee_list";
                $data['page_temp']    = $this->load->view('admin/managers/add_managers', $page, TRUE);
                $data['view_file']    = "Page_Template";
                $data['currentmenu']  = "employee_list";
                $this->bassthaya->load_admin_form_template($data);
            }
        }
	}
?>
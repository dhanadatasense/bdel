<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Catlog extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function add_category($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage  = $this->input->post('formpage');
			$log_id    = $this->session->userdata('id');
			$log_role  = $this->session->userdata('user_role');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$category_name = $this->input->post('category_name');
				$method        = $this->input->post('method');

				$required = array('category_name');
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
			    		if(userAccess('category-add'))
			    		{
			    			$data = array(
			    				'log_id'         => $log_id,
								'log_role'       => $log_role,
						    	'name'           => ucfirst($category_name),
						    	'item_type'      => '1',
						    	'salesagents_id' => '0',
						    	'method'         => '_addCategory',
						    );

						    $data_save = avul_call(API_URL.'catlog/api/category',$data);

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
			    		if(userAccess('category-edit'))
			    		{
			    			$category_id = $this->input->post('category_id');
				    		$pstatus     = $this->input->post('pstatus');

				    		$data = array(
				    			'log_id'   => $log_id,
								'log_role' => $log_role,
				    			'id'       => $category_id,
						    	'name'     => ucfirst($category_name),
						    	'status'   => $pstatus,
						    	'method'   => '_updateCategory'
						    );

						    $data_save = avul_call(API_URL.'catlog/api/category',$data);

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
					$category_id = !empty($param2)?$param2:'';

					$where = array(
	            		'category_id' => $category_id,
	            		'method'      => '_detailCategory'
	            	);

	            	$data_list  = avul_call(API_URL.'catlog/api/category',$where);

					$page['dataval']    = $data_list['data'];
					$page['method']     = 'BTBM_X_U';
					$page['page_title'] = "Edit Category";
				}
				else
				{
					$page['dataval']    = '';
					$page['method']     = 'BTBM_X_C';
					$page['page_title'] = "Add Category";
				}
				
				$page['main_heading'] = "Catalogue";
				$page['sub_heading']  = "Category";
				$page['pre_title']    = "List Category";
				$page['page_access']  = userAccess('category-view');
				$page['pre_menu']     = "index.php/admin/catlog/list_category";
				$data['page_temp']    = $this->load->view('admin/catlog/category/add_category',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_category";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function list_category($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			if($param1 == '')
			{
				$page['main_heading'] = "Catalogue";
				$page['sub_heading']  = "Category";
				$page['page_title']   = "List Category";
				$page['pre_title']    = "Add Category";
				$page['page_access']  = userAccess('category-add');
				$page['pre_menu']     = "index.php/admin/catlog/add_category";
				$data['page_temp']    = $this->load->view('admin/catlog/category/list_category',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_category";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('category-view'))
				{
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
	            		'offset'    => $_offset,
	            		'limit'     => $limit,
	            		'search'    => $search,
	            		'item_type' => '1',
	            		'method'    => '_listCategoryPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'catlog/api/category',$where);
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
		            		$category_id   = !empty($value['category_id'])?$value['category_id']:'';
						    $category_name = !empty($value['category_name'])?$value['category_name']:'';
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
				            if(userAccess('category-edit') == TRUE)
				            {
				            	$edit = '<a href="'.BASE_URL.'index.php/admin/catlog/add_category/Edit/'.$category_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
				            }
				            if(userAccess('category-delete') == TRUE)
				            {
				            	$delete = '<a data-row="'.$i.'" data-id="'.$category_id.'" data-value="admin" data-cntrl="catlog" data-func="list_category" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
				            }

						    $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$category_name.'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                                if(userAccess('category-edit') == TRUE || userAccess('category-delete') == TRUE):
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
						'log_id'      => $log_id,
						'log_role'    => $log_role,
				    	'category_id' => $id,
				    	'method'      => '_deleteCategory'
				    );

				    $data_save = avul_call(API_URL.'catlog/api/category',$data);

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

		public function add_sub_category($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage  = $this->input->post('formpage');
			$log_id    = $this->session->userdata('id');
			$log_role  = $this->session->userdata('user_role');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$sub_category_name = $this->input->post('sub_category_name');
				$category_id = $this->input->post('category_id');
				$method        = $this->input->post('method');

				$required = array('sub_category_name','category_id');
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
			    		if(userAccess('category-add'))
			    		{
			    			$data = array(
			    				'log_id'         => $log_id,
								'log_role'       => $log_role,
						    	'name'           => ucfirst($sub_category_name),
								'category_id'    => $category_id,
						    	'item_type'      => '2',
						    	'salesagents_id' => '0',
						    	'method'         => '_addSubCategory',
						    );

						    $data_save = avul_call(API_URL.'catlog/api/sub_category',$data);

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
			    		if(userAccess('category-edit'))
			    		{
			    			$id = $this->input->post('sub_category_id');
				    		$pstatus     = $this->input->post('pstatus');

				    		$data = array(
				    			'log_id'   => $log_id,
								'log_role' => $log_role,
				    			'id'       => $id,
						    	'name'     => ucfirst($sub_category_name),
								'category_id'    => $category_id,
						    	'status'   => $pstatus,
						    	'method'   => '_updateSubCategory'
						    );

						    $data_save = avul_call(API_URL.'catlog/api/sub_category',$data);

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
					
					$category_id = !empty($param2)?$param2:'';

					$where = array(
	            		'category_id' => $category_id,
	            		'method'      => '_detailSubCategory'
	            	);
				
	            	$data_list  = avul_call(API_URL.'catlog/api/sub_category',$where);
					
					$page['dataval']    = $data_list['data'];
					$page['method']     = 'BTBM_X_U';
					$page['page_title'] = "Edit Sub Category";
				}
				else
				{
					$page['dataval']    = '';
					$page['method']     = 'BTBM_X_C';
					$page['page_title'] = "Add Sub Category";
				}
				$where_1 = array(
					'item_type'      => '1',
					'salesagents_id' => '0',
            		'method'         => '_listCategory',
            	);

            	$category_list = avul_call(API_URL.'catlog/api/category',$where_1);

				$page['category_val']  = $category_list['data'];
				$page['main_heading'] = "Catalogue";
				$page['sub_heading']  = "Sub Category";
				$page['pre_title']    = "List Sub Category";
				$page['page_access']  = userAccess('category-view');
				$page['pre_menu']     = "index.php/admin/catlog/list_sub_category";
				$data['page_temp']    = $this->load->view('admin/catlog/sub_category/add_sub_category',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_sub_category";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function list_sub_category($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			if($param1 == '')
			{
				$page['main_heading'] = "Catalogue";
				$page['sub_heading']  = "Sub Category";
				$page['page_title']   = "List Sub Category";
				$page['pre_title']    = "Add Sub Category";
				$page['page_access']  = userAccess('category-add');
				$page['pre_menu']     = "index.php/admin/catlog/add_sub_category";
				$data['page_temp']    = $this->load->view('admin/catlog/sub_category/list_sub_category',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_sub_category";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('category-view'))
				{
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
					$sort_column =$this->input->post('sort_column');
					$sort_type =$this->input->post('sort_type');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
	            		'offset'    => $_offset,
	            		'limit'     => $limit,
	            		'search'    => $search,
	            		'item_type' => '1',
						'sort_column' => $sort_column,
						'sort_type'  => $sort_type,
	            		'method'    => '_listSubCategoryPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'catlog/api/sub_category',$where);
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
		            		$category_id   = !empty($value['category_id'])?$value['category_id']:'';
							$id   = !empty($value['id'])?$value['id']:'';
							$cat_name   = !empty($value['cat_name'])?$value['cat_name']:'';
						    $sub_category_name = !empty($value['sub_category_name'])?$value['sub_category_name']:'';
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
				            if(userAccess('category-edit') == TRUE)
				            {
				            	$edit = '<a href="'.BASE_URL.'index.php/admin/catlog/add_sub_category/Edit/'.$id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
				            }
				            if(userAccess('category-delete') == TRUE)
				            {
				            	$delete = '<a data-row="'.$i.'" data-id="'.$id.'" data-value="admin" data-cntrl="catlog" data-func="list_sub_category" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
				            }

						    $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$sub_category_name.'</td>
									<td class="line_height">'.$cat_name.'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                                if(userAccess('category-edit') == TRUE || userAccess('category-delete') == TRUE):
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
						'log_id'      => $log_id,
						'log_role'    => $log_role,
				    	'category_id' => $id,
				    	'method'      => '_deleteCategory'
				    );

				    $data_save = avul_call(API_URL.'catlog/api/category',$data);

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

		public function add_product($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage  = $this->input->post('formpage');
			$log_id    = $this->session->userdata('id');
			$log_role  = $this->session->userdata('user_role');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$product_name = $this->input->post('product_name');
				$unique_code  = $this->input->post('unique_code');
				$vendor_id    = $this->input->post('vendor_id');
				$category_id  = $this->input->post('category_id');
				$unit         = $this->input->post('unit');
				$hsn_code     = $this->input->post('hsn_code');
				$price        = $this->input->post('price');
				$sub_cat_id    = $this->input->post('sub_cat_id');
				// $dis_price    = $this->input->post('dis_price');
				$gst          = $this->input->post('gst');
				$stock        = $this->input->post('stock');
				$vend_stock   = $this->input->post('vend_stock');
				$method       = $this->input->post('method');

				$description   = $this->input->post('description');
				$pro_type      = $this->input->post('pro_type');
				$pro_unit      = $this->input->post('pro_unit');
				$mrp_price     = $this->input->post('mrp_price');
				$pro_price     = $this->input->post('pro_price');
				$ven_price     = $this->input->post('ven_price');
				$dis_price     = $this->input->post('dis_price');
				$pro_stock     = $this->input->post('pro_stock');
				$type_stock    = $this->input->post('type_stock');
				$minimum_stock = $this->input->post('minimum_stock');
				$type_id       = $this->input->post('type_id');

				$required = array('product_name', 'vendor_id', 'category_id', 'unit', 'hsn_code','sub_cat_id');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if(count(array_filter($pro_type))!==count($pro_type) || $error == TRUE)
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
			    		if(userAccess('product-add'))
			    		{
			    			$product_type  = [];
				    		$product_count = count($pro_type);

				    		for($j = 0; $j < $product_count; $j++)
				    		{

				    			$product_type[] = array(
				    				'description'   => $description[$j],
				    				'pro_type'      => $pro_type[$j],
				    				'pro_unit'      => $pro_unit[$j],
				    				'mrp_price'     => $mrp_price[$j],
				    				'pro_price'     => $pro_price[$j],
				    				'ven_price'     => $ven_price[$j],
				    				'dis_price'     => $dis_price[$j],
				    				'pro_stock'     => $pro_stock[$j],
				    				'type_stock'    => $type_stock[$j],
				    				'minimum_stock' => $minimum_stock[$j],
				    			);
				    		}

				    		$product_value = json_encode($product_type);

				    		$postData = array(
				    			'log_id'         => $log_id,
								'log_role'       => $log_role,
						    	'name'           => ucfirst($product_name),
						    	'unique_code'    => $unique_code,
						    	'vendor_id'      => $vendor_id,
								'sub_cat_id'    => $sub_cat_id,
						    	'category_id'    => $category_id,
						    	'unit'           => $unit,
						    	'price'          => $price,
						    	'hsn_code'       => $hsn_code,
						    	'gst'            => $gst,
						    	'stock'          => $stock,
						    	'vend_stock'     => $vend_stock,
						    	'item_type'      => '1',
						    	'salesagents_id' => '0',
						    	'product_type'   => $product_value,
						    	'createdate'     => date('Y-m-d H:i:s'),
						    	'method'         => '_addProduct',
						    );

						    $data_save = avul_fileUpload(API_URL.'catlog/api/product',$postData);

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
			    		if(userAccess('product-edit'))
			    		{
			    			$product_id   = $this->input->post('product_id');
				    		$product_code = $this->input->post('product_code');
				    		$pstatus      = $this->input->post('pstatus');

				    		$product_type  = [];
				    		$product_count = count($pro_type);

				    		for($j = 0; $j < $product_count; $j++)
				    		{
				    			$product_type[] = array(
				    				'type_id'       => $type_id[$j],
				    				'description'   => $description[$j],
				    				'pro_type'      => $pro_type[$j],
				    				'pro_unit'      => $pro_unit[$j],
				    				'mrp_price'     => $mrp_price[$j],
				    				'pro_price'     => $pro_price[$j],
				    				'ven_price'     => $ven_price[$j],
				    				'dis_price'     => $dis_price[$j],
				    				'pro_stock'     => $pro_stock[$j],
				    				'type_stock'    => $type_stock[$j],
				    				'minimum_stock' => $minimum_stock[$j],
				    			);
				    		}

				    		$product_value = json_encode($product_type);

				    		$postData = array(
				    			'log_id'         => $log_id,
								'log_role'       => $log_role,
				    			'id'             => $product_id,
				    			'product_code'   => $product_code,
						    	'name'           => ucfirst($product_name),
						    	'unique_code'    => $unique_code,
						    	'vendor_id'      => $vendor_id,
						    	'category_id'    => $category_id,
						    	'unit'           => $unit,
						    	'price'          => $price,
						    	'hsn_code'       => $hsn_code,
						    	'gst'            => $gst,
						    	'stock'          => $stock,
						    	'vend_stock'     => $vend_stock,
						    	'item_type'      => '1',
						    	'salesagents_id' => '0',
						    	'status'         => $pstatus,
						    	'product_type'   => $product_value,
						    	'method'         => '_updateProduct'
						    );

						    $data_save = avul_fileUpload(API_URL.'catlog/api/product',$postData);

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

			else if($param1 =='getProduct_row')
			{
				$rowCount = $this->input->post('rowCount');
				$newCount = $rowCount + 1;

				$where_1 = array(
            		'method'    => '_listUnit'
            	);

	            $unit_list  = avul_call(API_URL.'master/api/unit',$where_1);
	            $unit_val   = $unit_list['data'];

	            $where_2 = array(
            		'method' => '_listVariation'
            	);

            	$variation_list = avul_call(API_URL.'master/api/variation',$where_2);
            	$variation_val  = $variation_list['data'];

	            $option = '
					<tr class="row_'.$newCount.'">
						<script src="'.BASE_URL.'app-assets/js/select2.full.js"></script>
						<script>
							var baseurl = $(".geturl").val();

							if($(".js-select2-multi").length)
						    {
						        $(".js-select2-multi").select2({
						            placeholder: "Select Value",
						        });
						    }

						</script>
                        <td data-te="'.$newCount.'" class="p-l-0 product_list">
                            <input type="text" data-te="'.$rowCount.'" name="description[]" id="description'.$rowCount.'" class="form-control description'.$rowCount.' description" placeholder="Description">  
                        </td>
                        <td class="p-l-0">
                            <select data-te="'.$newCount.'" name="pro_type[]" id="pro_type'.$newCount.'" class="form-control pro_type'.$newCount.' pro_type js-select2-multi" style="width: 100%;" >
                                <option value="">Select Variation Name</option>';
                                if(!empty($variation_val))
	                            {
	                            	foreach ($variation_val as $key => $value) {

	                            		$variation_id   = !empty($value['variation_id'])?$value['variation_id']:'';
                                        $variation_name = !empty($value['variation_name'])?$value['variation_name']:'';

	                                    $option .="<option value=".$variation_name.">".$variation_name."</option>";
	                            	}
	                            }
                            $option .='</select> 
                        </td>
                        <td class="p-l-0">
                            <select data-te="'.$newCount.'" name="pro_unit[]" id="pro_unit'.$newCount.'" class="form-control pro_unit'.$newCount.' pro_unit js-select2-multi" style="width: 100%;" >
                                <option value="">Select Unit Name</option>';
                                if(!empty($unit_val))
	                            {
	                            	foreach ($unit_val as $key => $value) {
	                            		$unit_id   = !empty($value['unit_id'])?$value['unit_id']:'';
	                                    $unit_name = !empty($value['unit_name'])?$value['unit_name']:'';

	                                    $option .="<option value=".$unit_id.">".$unit_name."</option>";
	                            	}
	                            }
                            $option .='</select> 
                        </td>
                        <td class="p-l-0">
                            <input type="text" data-te="'.$rowCount.'" name="mrp_price[]" id="mrp_price'.$rowCount.'" class="form-control mrp_price'.$rowCount.' mrp_price int_value" placeholder="MRP"> 
                        </td>
                        <td class="p-l-0">
                            <input type="text" data-te="'.$rowCount.'" name="pro_price[]" id="pro_price'.$rowCount.'" class="form-control pro_price'.$rowCount.' pro_price int_value" placeholder="Price"> 
                        </td>
                        <td class="p-l-0">
                            <input type="text" data-te="'.$rowCount.'" name="ven_price[]" id="ven_price'.$rowCount.'" class="form-control ven_price'.$rowCount.' ven_price int_value" placeholder="Vendor Price"> 
                        </td>
                        <td class="p-l-0">
                            <input type="text" data-te="'.$rowCount.'" name="dis_price[]" id="dis_price'.$rowCount.'" class="form-control dis_price'.$rowCount.' dis_price int_value" placeholder="Distributor Price"> 
                        </td>
                        <td class="p-l-0">
                        	<input type="text" data-te="'.$rowCount.'" name="pro_stock[]" id="pro_stock'.$rowCount.'" class="form-control pro_stock'.$rowCount.' pro_stock int_value" placeholder="Stock" value="0">

                            <input type="hidden" data-te="'.$rowCount.'" name="type_id[]" id="type_id'.$rowCount.'" class="form-control type_id'.$rowCount.' type_id" placeholder="Price">

                            <input type="hidden" data-te="'.$rowCount.'" name="type_stock[]" id="type_stock'.$rowCount.'" class="form-control type_stock'.$rowCount.' type_stock" placeholder="Price">
                        </td>
                        <td class="p-l-0">
                            <input type="text" data-te="'.$rowCount.'" name="minimum_stock[]" id="minimum_stock'.$rowCount.'" class="form-control minimum_stock'.$rowCount.' minimum_stock int_value" placeholder="Minimum Stock" value="0"> 
                        </td>
                        <td class="buttonlist p-l-0">
                            <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6 remove_item"><span class="ft-minus-square"></span></button>
                        </td>
                    </tr>
				';

				$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        $response['count']   = $newCount;
		        echo json_encode($response);
		        return;
			}
			if($param1 == 'sub_cat')
        	{
        		$cat_id  = $this->input->post('cat_id');

				
			    
				
			    $att_whr = array(
			    	'cat_id'  => $cat_id,
					'admin'  =>  $this->session->userdata('id'),
			    	'method'      => '_listSubCategory',
			    );
			
			    $data_list  = avul_call(API_URL.'catlog/api/sub_category',$att_whr);
		    	
		    	$data_val  = $data_list['data'];
				
			
        		$option ='<option value="">Select Sub Category</option>';

        		if(!empty($data_val))
        		{
        			foreach ($data_val as $key => $value) {
        				$id   = !empty($value['id']) ?$value['id']:'';
                        $category_id = !empty($value['category_id'])?$value['category_id']:'';
					
						$name =!empty($value['sub_cat_name'])?$value['sub_cat_name']:'';

                        $select   = '';
        				
						

                        $option .= '<option value="'.$id.'" '.$select.'>'.$name.'</option>';
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
					$product_id = !empty($param2)?$param2:'';

					$where_1 = array(
	            		'product_id' => $product_id,
	            		'method'     => '_detailProduct'
	            	);

	            	$data_list  = avul_call(API_URL.'catlog/api/product',$where_1);
					$data_val = $data_list['data'];
					$category_id = !empty($data_val[0]['category_id']) ? $data_val[0]['category_id'] : '';

	            	$where_2 = array(
	            		'product_id' => $product_id,
	            		'method'     => '_listProductType'
	            	);

	            	$type_list  = avul_call(API_URL.'catlog/api/productType',$where_2);
					$where_2 = array(
	            		'cat_id'     => $category_id,
	            		'method'     => '_listSubCategory'
	            	);

	            	$sub_cat_list  = avul_call(API_URL.'catlog/api/sub_category',$where_2);

					$page['sub_cat_val']  = $sub_cat_list['data'];
					$page['dataval']      = $data_list['data'];
					$page['type_val']     = $type_list['data'];
					$page['method']       = 'BTBM_X_U';
					$page['page_title']   = "Edit Product";
				}
				else
				{
					$page['dataval']      = '';
					$page['method']       = 'BTBM_X_C';
					$page['page_title']   = "Add Product";
				}

				$where_1 = array(
					'item_type'      => '1',
					'salesagents_id' => '0',
            		'method'         => '_listCategory',
            	);

            	$category_list = avul_call(API_URL.'catlog/api/category',$where_1);

            	$where_3 = array(
            		'method' => '_listUnit'
            	);

            	$unit_list  = avul_call(API_URL.'master/api/unit',$where_3);

            	$where_4 = array(
            		'method' => '_listVariation'
            	);

            	$variation_list  = avul_call(API_URL.'master/api/variation',$where_4);

            	$where_5 = array(
            		'method' => '_listVendors'
            	);

            	$vendor_list  = avul_call(API_URL.'vendors/api/vendors',$where_5);

            	$page['category_val']  = $category_list['data'];
				$page['unit_val']      = $unit_list['data'];
				$page['variation_val'] = $variation_list['data'];
				$page['vendor_val']    = $vendor_list['data'];
				$page['main_heading']  = "Catalogue";
				$page['sub_heading']   = "Product";
				$page['pre_title']     = "List Product";
				$page['page_access']  = userAccess('product-view');
				$page['pre_menu']      = "index.php/admin/catlog/list_product";
				$data['page_temp']     = $this->load->view('admin/catlog/product/add_product',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "add_product";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function list_product($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Catalogue";
				$page['sub_heading']  = "Product";
				$page['page_title']   = "List Product";
				$page['pre_title']    = "Add Product";
				$page['page_access']  = userAccess('product-add');
				$page['pre_menu']     = "index.php/admin/catlog/add_product";
				$data['page_temp']    = $this->load->view('admin/catlog/product/list_product',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_product";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('product-view'))
				{
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
	            		'offset'         => $_offset,
	            		'limit'          => $limit,
	            		'search'         => $search,
	            		'item_type'      => '1',
	            		'method'         => '_listProductPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'catlog/api/product',$where);
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
		            		$product_id    = !empty($value['product_id'])?$value['product_id']:'';
						    $product_name  = !empty($value['product_name'])?$value['product_name']:'';
						    $category_name = !empty($value['category_name'])?$value['category_name']:'';
						    $brand_name    = !empty($value['brand_name'])?$value['brand_name']:'';
						    $hsn_code      = !empty($value['hsn_code'])?$value['hsn_code']:'';
						    $price         = !empty($value['price'])?$value['price']:'-';
						    $gst           = !empty($value['gst'])?$value['gst']:'';
						    $product_img   = !empty($value['product_img'])?$value['product_img']:'';
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

			                if(!empty($product_img))
						    {
						        $pdt_img = FILE_URL.'product/'.$product_img;
						    }
						    else
						    {
						        $pdt_img = BASE_URL.'app-assets/images/img_icon.png';
						    }

			                $edit   = '';
				            $delete = '';
				            if(userAccess('product-edit') == TRUE)
				            {
				            	$edit = '<a href="'.BASE_URL.'index.php/admin/catlog/add_product/Edit/'.$product_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
				            }
				            if(userAccess('product-delete') == TRUE)
				            {
				            	$delete = '<a data-row="'.$i.'" data-id="'.$product_id.'" data-value="admin" data-cntrl="catlog" data-func="list_product" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
				            }

						    $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td><img src="'.$pdt_img.'" class="rounded me-50" alt="profile image" height="45" width="45"/></td>
	                                <td class="line_height">'.mb_strimwidth($product_name, 0, 40, '...').'</td>
	                                <td class="line_height">'.$hsn_code.'</td>
	                                <td class="line_height">'.$gst.'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                                if(userAccess('product-edit') == TRUE || userAccess('product-delete') == TRUE):
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
				$id        = $this->input->post('id');
				$log_id    = $this->session->userdata('id');
				$log_role  = $this->session->userdata('user_role');

				if(!empty($id))	
				{
					$data = array(
						'log_id'     => $log_id,
						'log_role'   => $log_role,
				    	'product_id' => $id,
				    	'method'     => '_deleteProduct'
				    );

				    $data_save = avul_call(API_URL.'catlog/api/product',$data);

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

		public function low_stock($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Catalogue";
				$page['sub_heading']  = "Product";
				$page['page_title']   = "Low stock product";
				$page['pre_title']    = "Add Product";
				$page['page_access']  = userAccess('product-add');
				$page['pre_menu']     = "index.php/admin/catlog/add_product";
				$data['page_temp']    = $this->load->view('admin/catlog/product/low_stock',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_product";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('product-view'))
				{
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
	            		'offset'         => $_offset,
	            		'limit'          => $limit,
	            		'search'         => $search,
	            		'item_type'      => '1',
	            		'method'         => '_lowStockProduct'
	            	);

	            	$data_list  = avul_call(API_URL.'catlog/api/productType',$where);
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

						    $description   = empty_check($val['description']);
						    $product_price = empty_check($val['product_price']);
						    $product_stock = zero_check($val['product_stock']);
						    $minimum_stock = zero_check($val['minimum_stock']);
						    $product_unit  = empty_check($val['product_unit']);

						    $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.mb_strimwidth($description, 0, 40, '...').'</td>
	                                <td class="line_height">'.$product_price.'</td>
	                                <td class="line_height">'.$minimum_stock.' <b>'.$product_unit.'</b></td>
	                                <td class="line_height">'.$product_stock.' <b>'.$product_unit.'</b></td>
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
				    	'product_id' => $id,
				    	'method'     => '_deleteProduct'
				    );

				    $data_save = avul_call(API_URL.'catlog/api/product',$data);

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
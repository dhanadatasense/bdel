<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Distributors extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function add_distributors($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$dis_code           = $this->input->post('dis_code');
				$company_name       = $this->input->post('company_name');
				$contact_name       = $this->input->post('contact_name');
				$mobile             = $this->input->post('mobile');
				$email              = $this->input->post('email');
				$gst_no             = $this->input->post('gst_no');
				$pan_no             = $this->input->post('pan_no');
				$tan_no             = $this->input->post('tan_no');
				$bill_no            = $this->input->post('bill_no');
				$password           = $this->input->post('password');
				$credit_limit       = $this->input->post('credit_limit');
				$discount           = $this->input->post('discount');
				$due_days           = $this->input->post('due_days');
				$account_name       = $this->input->post('account_name');
				$account_no         = $this->input->post('account_no');
				$account_type       = $this->input->post('account_type');
				$ifsc_code          = $this->input->post('ifsc_code');
				$bank_name          = $this->input->post('bank_name');
				$branch_name        = $this->input->post('branch_name');
				$pincode            = $this->input->post('pincode');
				$distributor_status = $this->input->post('distributor_status');
				$state_id           = $this->input->post('state_id');
				// $city_id            = $this->input->post('city_id');
				// $zone_id            = $this->input->post('zone_id');
				$address            = $this->input->post('address');
				$category_id        = $this->input->post('category_id');
				$sub_cat_id        = $this->input->post('sub_cat_id');
				$type_id            = $this->input->post('type_id');
				$distributor_id     = $this->input->post('distributor_id');
				$pstatus            = $this->input->post('pstatus');
				$method             = $this->input->post('method');
				// $zone_value     = implode(',', $zone_id);
				$dis_grade          = $this->input->post('distributor_grade');

				$required = array('dis_code', 'company_name', 'sub_cat_id', 'mobile', 'email', 'gst_no', 'distributor_grade', 'distributor_status', 'state_id', 'password', 'category_id', 'type_id', 'address');
			
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
			    	$category_value = implode(',', $category_id);
					$sub_cat_value = implode(',', $sub_cat_id);
					$type_value     = implode(',', $type_id);

			    	if($method == 'BTBM_X_C')
			    	{
			    		if(userAccess('distributors-add'))
			    		{
			    			$data = array(
								'sub_cat_id'         => $sub_cat_value,
				    			'dis_code'           => $dis_code,
				    			'company_name'       => $company_name,
							    'contact_name'       => $contact_name,
							    'mobile'             => $mobile,
							    'email'              => $email,
							    'gst_no'             => $gst_no,
							    'pan_no'             => $pan_no,
							    'tan_no'             => $tan_no,
							    'bill_no'            => $bill_no,
							    'credit_limit'       => $credit_limit,
								'discount'           => $discount,
								'due_days'           => $due_days,
							    'password'           => $password,
							    'account_name'       => $account_name,
							    'account_no'         => $account_no,
							    'account_type'       => $account_type,
							    'ifsc_code'          => $ifsc_code,
							    'bank_name'          => $bank_name,
							    'branch_name'        => $branch_name,
							    'pincode'            => $pincode,
							    'category_id'        => $category_value,
							    'type_id'            => $type_value,
							    'state_id'           => $state_id,
							    'distributor_status' => $distributor_status,
							    // 'city_id'            => $city_id,
							    // 'zone_id'            => $zone_value,
							    'address'            => $address,
							    'distributor_type'   => '1',
							    'method'             => '_addDistributors',
								'ref_id'            => '0',
								'distributor_grade' => $dis_grade
				    		);

				    		$data_save = avul_call(API_URL.'distributors/api/distributors',$data);

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
			    		if(userAccess('distributors-edit'))
			    		{
			    			$data = array(
								'sub_cat_id'         => $sub_cat_value,
				    			'distributor_id'     => $distributor_id,
				    			'dis_code'           => $dis_code,
				    			'company_name'       => $company_name,
							    'contact_name'       => $contact_name,
							    'mobile'             => $mobile,
							    'email'              => $email,
							    'gst_no'             => $gst_no,
							    'pan_no'             => $pan_no,
							    'tan_no'             => $tan_no,
							    'bill_no'            => $bill_no,
							    'credit_limit'       => $credit_limit,
								'discount'           => $discount,
								'due_days'           => $due_days,
							    'password'           => $password,
							    'account_name'       => $account_name,
							    'account_no'         => $account_no,
							    'account_type'       => $account_type,
							    'ifsc_code'          => $ifsc_code,
							    'bank_name'          => $bank_name,
							    'branch_name'        => $branch_name,
							    'pincode'            => $pincode,
							    'state_id'           => $state_id,
							    'distributor_status' => $distributor_status,
							    // 'city_id'            => $city_id,
							    // 'zone_id'            => $zone_value,
							    'category_id'        => $category_value,
							    'type_id'            => $type_value,
							    'address'            => $address,
							    'status'             => $pstatus,
							    'distributor_type'   => '1',
								'distributor_grade' => $dis_grade,
							    'method'             => '_updateDistributors',
				    		);
							$finnall= json_encode($data);
							print_r($finnall);  exit;
				    		$data_save = avul_call(API_URL.'distributors/api/distributors', $data);

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

			if($param1 =='get_sub_cat_list')
			{
				$category_id    = $this->input->post('category_id');
				$distributor_id = $this->input->post('distributor_id');
				if(!empty($category_id)){
						$category_val   = implode(',', $category_id);

					$data = array(
						'category_id' => $category_val,
						'method'      => '_listCategory_sub_cat',
					);

					// Distributor Details
					$where = array(
						'distributor_id' => $distributor_id,
						'method'         => '_detailDistributors'
					);

					$dis_list  = avul_call(API_URL.'distributors/api/distributors',$where);
					$dis_value = $dis_list['data'];	
					$sub_cat_id  = !empty($dis_value[0]['sub_cat_id'])?$dis_value[0]['sub_cat_id']:'';

					$data_list = avul_call(API_URL.'catlog/api/sub_category',$data);					
					$data_val  = $data_list['data'];

					$option ='';

					if(!empty($data_val))
					{
						foreach ($data_val as $key => $value) {
							$id     = !empty($value['id'])?$value['id']:'';
							$name = !empty($value['name'])?$value['name']:'';
							

							$select   = '';
							$sub_cat_res = explode(',', $sub_cat_id);
							if(in_array($id, $sub_cat_res))
							{
								$select = 'selected';
							}

							$option .= '<option value="'.$id.'" '.$select.'>'.$name.'</option>';
						}
					}

					$response['status']  = 1;
					$response['message'] = 'success'; 
					$response['data']    = $option;
					echo json_encode($response);
					return; 
				}else{
					$option ='';
					$response['status']  = 0;
					$response['message'] = 'success'; 
					$response['data']    = $option;
					echo json_encode($response);
					return; 
				}
				
			}

			if($param1 =='getProduct_list')
			{
				$sub_cat_id    = $this->input->post('sub_cat_id');
				$distributor_id = $this->input->post('distributor_id');
				if(!empty($sub_cat_id)){
					$subcategory_val   = implode(',', $sub_cat_id);
			

					$data = array(
						'sub_cat_id' => $subcategory_val,
						'method'      => '_listSubCategoryProducts',
					);
	
					// Distributor Details
					$where = array(
						'distributor_id' => $distributor_id,
						'method'         => '_detailDistributors'
					);
	
					$dis_list  = avul_call(API_URL.'distributors/api/distributors',$where);
					$dis_value = $dis_list['data'];	
					$type_val  = !empty($dis_value[0]['type_id'])?$dis_value[0]['type_id']:'';
	
					$data_list = avul_call(API_URL.'catlog/api/product',$data);					
					$data_val  = $data_list['data'];
	
					$option ='';
	
					if(!empty($data_val))
					{
						foreach ($data_val as $key => $value) {
							$type_id     = !empty($value['type_id'])?$value['type_id']:'';
							$description = !empty($value['description'])?$value['description']:'';
							$product_id  = !empty($value['product_id'])?$value['product_id']:'';
	
							$select   = '';
							$type_res = explode(',', $type_val);
							if(in_array($type_id, $type_res))
							{
								$select = 'selected';
							}
	
							$option .= '<option value="'.$type_id.'" '.$select.'>'.$description.'</option>';
						}
					}
					
					$response['status']  = 1;
					$response['message'] = 'success'; 
					$response['data']    = $option;
					echo json_encode($response);
					return; 
				}else{
					$option ='';
					$response['status']  = 0;
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
					$distributor_id = !empty($param2)?$param2:'';
					$approval       = !empty($param3)?$param3:'';
					
					$where = array(
	            		'distributor_id' => $distributor_id,
	            		'method'         => '_detailDistributors'
	            	);

	            	$data_list  = avul_call(API_URL.'distributors/api/distributors',$where);
	            	$data_value = $data_list['data'];	

					$category_val = !empty($data_value[0]['category_id'])?$data_value[0]['category_id']:'';
					$sub_cat_id_val = !empty($data_value[0]['sub_cat_id'])?$data_value[0]['sub_cat_id']:'';
					
					
					// Type Details
					if(empty($approval)){
						if($category_val)
						{
							$sub_whr = array(
								'cat_id' => $category_val,
								'method'      => '_listSubCategory'
							);
	
							$sub_list   = avul_call(API_URL.'catlog/api/sub_category',$sub_whr);
						}

						if($sub_cat_id_val)
						{
							$type_whr = array(
								'sub_cat_id' => $sub_cat_id_val,
								'method'      => '_listSubCategoryProducts'
							);
		
							$type_list   = avul_call(API_URL.'catlog/api/product',$type_whr);
						}	
	
						$page['dataval']    = $data_list['data'];
						$page['type_val']   = [];
						$page['sub_val']   = [];
						$page['method']     = '_updateDistributors';
						$page['page_title'] = "Edit Distributors";
					}else{
						
	
						$page['dataval']    = $data_list['data'];
						$page['method']     = '_updateDistributors';
						$page['page_title'] = "Edit Distributors";
					}
					
	            	
				}
				else
				{
					$page['dataval']    = '';
					$page['city_val']   = '';
					$page['zone_val']   = '';
					$page['method']     = '_addDistributors';
					$page['page_title'] = "Add Distributors";
				}

				$where_1 = array(
					'item_type'      => '1',
					'salesagents_id' => '0',
            		'method'         => '_listCategory',
            	);

            	$category_list = avul_call(API_URL.'catlog/api/category',$where_1);

            	// State List
				$where_2 = array(
            		'method' => '_listState',
            	);

            	$state_list = avul_call(API_URL.'master/api/state',$where_2);
            	$state_data = $state_list['data'];

            	// Vendor List
				$where_3 = array(
            		'method' => '_listManufacturerVendors',
            	);

            	$vendor_list = avul_call(API_URL.'vendors/api/vendors',$where_3);
            	$vendor_data = $vendor_list['data'];
				$page['submit_url']   = "distributors/api/distributors";
            	$page['state_val']    = $state_data;
            	$page['category_val'] = $category_list['data'];
            	$page['vendor_val']   = $vendor_list['data'];
				$page['main_heading'] = "Distributors";
				$page['sub_heading']  = "Distributors";
				$page['pre_title']    = "List Distributors";
				$page['page_access']  = userAccess('distributors-view');
				$page['pre_menu']     = "index.php/admin/distributors/list_distributors";
				$data['page_temp']    = $this->load->view('admin/distributors/add_distributors',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_distributors";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function list_distributors($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Distributors";
				$page['sub_heading']  = "Distributors";
				$page['page_title']   = "List Distributors";
				$page['pre_title']    = "Add Distributors";
				$page['page_access']  = userAccess('distributors-add');
				$page['pre_menu']     = "index.php/admin/distributors/add_distributors";
				$data['page_temp']    = $this->load->view('admin/distributors/list_distributors',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_distributors";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('distributors-view'))
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
						'page'         => $page,
						'sort_column'  => $sort_column,
						'sort_type'    => $sort_type,
	            		'company_type' => 1,
	            		'company_id'   => 0,
						'ref_id'       => 0,
	            		'method'       => '_listDistributorsPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'distributors/api/distributors',$where);
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
		            		$distributor_id = !empty($value['distributor_id'])?$value['distributor_id']:'';
						    $company_name   = !empty($value['company_name'])?$value['company_name']:'';
						    $mobile         = !empty($value['mobile'])?$value['mobile']:'';
						    $email          = !empty($value['email'])?$value['email']:'';
						    $log_type       = !empty($value['log_type'])?$value['log_type']:'';
						    $active_status  = !empty($value['status'])?$value['status']:'';
						    $createdate     = !empty($value['createdate'])?$value['createdate']:'';

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
				            if(userAccess('distributors-edit') == TRUE)
				            {
				            	$edit = '<a href="'.BASE_URL.'index.php/admin/distributors/add_distributors/Edit/'.$distributor_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
				            }
				            if(userAccess('distributors-delete') == TRUE)
				            {
				            	$delete = '<a data-row="'.$i.'" data-id="'.$distributor_id.'" data-value="admin" data-cntrl="distributors" data-func="list_distributors" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
				            }

						    $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.$mobile.'</td>
	                                <td class="line_height">'.mb_strimwidth($email, 0, 20, '...').'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                                if(userAccess('distributors-edit') == TRUE || userAccess('distributors-delete') == TRUE):
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
				    	'distributor_id' => $id,
				    	'method'         => '_deleteDistributors'
				    );

				    $data_save = avul_call(API_URL.'distributors/api/distributors',$data);

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
		public function list_dis_approval($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Approval Distributors";
				$page['sub_heading']  = "Distributors";
				$page['page_title']   = "Approval Distributors";
				$page['pre_title']    = "Add Distributors";
				$page['pre_menu']     = "index.php/admin/distributors/add_distributors";
				$data['page_temp']    = $this->load->view('admin/distributors/list_dis_approve',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_dis_approval";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
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
						'page'         => $page,
						'sort_column'  => $sort_column,
						'sort_type'    => $sort_type,
	            		'company_type' => 1,
	            		'company_id'   => 0,
						
	            		'method'       => '_listDistributorsPaginate_mg'
	            	);

	            	$data_list  = avul_call(API_URL.'distributors/api/distributors',$where);
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
		            		$distributor_id = !empty($value['distributor_id'])?$value['distributor_id']:'';
						    $company_name   = !empty($value['company_name'])?$value['company_name']:'';
						    $mobile         = !empty($value['mobile'])?$value['mobile']:'';
						    $email          = !empty($value['email'])?$value['email']:'';
						    $log_type       = !empty($value['log_type'])?$value['log_type']:'';
						    $active_status  = !empty($value['status'])?$value['status']:'';
						    $createdate     = !empty($value['createdate'])?$value['createdate']:'';
							
							

									
									if($active_status == '3')
									{
										$status_view = '<span class="badge badge-success">Pending</span>';
										
										
										if(userAccess('distributors-edit') == TRUE)
										{
											$edit = '<a href="'.BASE_URL.'index.php/admin/distributors/add_distributors/Edit/'.$distributor_id.'/'.'1'.'" class="button_clr btn btn-success"><i class="ft-user-check"></i> Approve </a>';
										}
										if(userAccess('distributors-delete') == TRUE)
										{
											$delete = '<a data-row="'.$i.'" data-id="'.$distributor_id.'" data-value="admin" data-cntrl="distributors" data-func="list_dis_approval" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Reject </a>';
										}
									}
									else if($active_status == '6')
									{
										$status_view = '<span class="badge badge-danger">Reject</span>';
										if(userAccess('distributors-edit') == TRUE)
										{
											$edit = '<a href="'.BASE_URL.'index.php/admin/distributors/add_distributors/Edit/'.$distributor_id.'" class="button_clr btn btn-primary"><i class="ft-user-check"></i> Re-create </a>';
										}
									}else if($active_status == '2')
									{
										$status_view = '<span class="badge badge-warning">In Active</span>';
									}
			               
									$view = '<a href="'.BASE_URL.'index.php/admin/distributors/list_dis_approval/view/'.$distributor_id.'" class="button_clr btn btn-success"><i class="ft-file-text"></i></a>';
				           
				            	
				           
				            	

						    $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.$mobile.'</td>
	                                <td class="line_height">'.mb_strimwidth($email, 0, 20, '...').'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                               
		                            	$table .= '<td>'.$view.$edit.$delete.'</td>';
		                        	
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
				    	'distributor_id' => $id,
				    	'method'         => '_deleteDistributors',
						'status'         => 6,
				    );

				    $data_save = avul_call(API_URL.'distributors/api/distributors',$data);

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
			else if($param1 == 'approve')
			{
				$id = $this->input->post('id');
			
				
				
					$status = 1;
				
				if(!empty($id))	
				{

					$data = array(
				    	'distributor_id' => $id,
				    	'method'         => '_deleteDistributors',
						'status'         => $status,
				    );

				    $data_save = avul_call(API_URL.'distributors/api/distributors',$data);

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
			}else if($param1 == 'view')
			{
				$distributor_id = $param2;

				$where = array(
			    	'distributor_id'  => $distributor_id,
			    	'view_type' 	  => 1,
			    	'method'    => '_detailDistributors'
			    );

			    $data_list  = avul_call(API_URL.'distributors/api/distributors',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "View Distributor Details";
				$page['sub_heading']   = "View Distributor Details";
				$page['page_title']    = "Distributor Details";
				$page['pre_title']     = "Distributor list";
				$page['pre_menu']      = "index.php/admin/distributors/data_list";
				$data['page_temp']     = $this->load->view('admin/distributors/view_distributor',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "list_dis_approval";
				$this->bassthaya->load_Managers_form_template($data);
			}
			
		}
	}
?>
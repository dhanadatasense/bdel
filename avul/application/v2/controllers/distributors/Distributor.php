<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Distributor extends CI_Controller {

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
            $distributor_id = $this->session->userdata('id');
			
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
				$sub_cat_id        = $this->input->post('sub_cat_id');
				// $city_id            = $this->input->post('city_id');
				// $zone_id            = $this->input->post('zone_id');
				$address            = $this->input->post('address');
				$category_id        = $this->input->post('category_id');
				$type_id            = $this->input->post('type_id');
				$distributor_id     = $this->input->post('distributor_id');
				$pstatus            = $this->input->post('pstatus');
				$method             = $this->input->post('method');
				// $zone_value     = implode(',', $zone_id);

				$required = array('dis_code', 'company_name', 'sub_cat_id', 'mobile', 'email', 'gst_no',  'state_id', 'password', 'category_id', 'type_id', 'address');
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
			    		
			    			$data = array(
								'sub_cat_id'         => $sub_cat_value,
								'distributor_grade'  => 3,
								'ref_id'             =>  $this->session->userdata('id'),
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
			    		
			    		
			    			$data = array(
								'sub_cat_id'         => $sub_cat_value,
								'distributor_grade'   => '3',
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
							    'method'             => '_updateDistributors',
				    		);

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
			    }
			}
			if($param1 =='getSubCategotry_list')
			{
				$category_id    = $this->input->post('category_id');
				$distributor_id = $this->input->post('distributor_id');
				$category_val   = implode(',', $category_id);

			
				$where_ed = array(
            		'distributor_id' => $distributor_id,
            		'method'         => '_detailDistributors'
            	);

            	$dis_list_ed  = avul_call(API_URL.'distributors/api/distributors',$where_ed);
            	$dis_value_ed = $dis_list_ed['data'];
				$type_val_ed  = !empty($dis_value_ed[0]['type_id'])?$dis_value_ed[0]['type_id']:'';	
				$sub_val_ed  = !empty($dis_value_ed[0]['sub_cat_id'])?$dis_value_ed[0]['sub_cat_id']:'';	
			    // Distributor Details
			    $where = array(
            		'distributor_id' => $this->session->userdata('id'),
            		'method'         => '_detailDistributors'
            	);

            	$dis_list  = avul_call(API_URL.'distributors/api/distributors',$where);
            	$dis_value = $dis_list['data'];	
            	$type_val  = !empty($dis_value[0]['type_id'])?$dis_value[0]['type_id']:'';
				$sub_val  = !empty($dis_value[0]['sub_cat_id'])?$dis_value[0]['sub_cat_id']:'';
				// $ty_arr   = explode(',', $type_val);
				// $category_arr   = explode(',', $category_val);
				$data = array(
					'sub_cat_id'  => $sub_val,
					'type_id'     => $type_val,
			    	'category_id' => $category_val,
			    	'method'      => '_listCategory_sub_cat_dis',
			    );
			
			    $data_list = avul_call(API_URL.'catlog/api/sub_category',$data);					
			    $data_val  = $data_list['data'];
				
			    $option ='';
				
				

			    if(!empty($data_val))
        		{
        			foreach ($data_val as $key => $value) {
        				$sub_id     = !empty($value['sub_id'])?$value['sub_id']:'';
        				$name = !empty($value['name'])?$value['name']:'';
        				

        				$select   = '';
        				$type_res = explode(',', $sub_val_ed);
                        if(in_array($sub_id, $type_res))
                        {
                            $select = 'selected';
                        }

                        $option .= '<option value="'.$sub_id.'" '.$select.'>'.$name.'</option>';
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
				$sub_cat_id    = $this->input->post('sub_cat_id');
				$distributor_id = $this->input->post('distributor_id');
				$s_category_val   = implode(',', $sub_cat_id);

			   if($distributor_id == 0){
				
				$type_val_ed  = '';	
				
			   }else{
				$where_ed = array(
            		'distributor_id' => $distributor_id,
            		'method'         => '_detailDistributors'
            	);

            	$dis_list_ed  = avul_call(API_URL.'distributors/api/distributors',$where_ed);
            	$dis_value_ed = $dis_list_ed['data'];
				$type_val_ed  = !empty($dis_value_ed[0]['type_id'])?$dis_value_ed[0]['type_id']:'';
			   }
				
			    // Distributor Details
			    $where = array(
            		'distributor_id' => $this->session->userdata('id'),
            		'method'         => '_detailDistributors'
            	);

            	$dis_list  = avul_call(API_URL.'distributors/api/distributors',$where);
            	$dis_value = $dis_list['data'];	
            	$type_val  = !empty($dis_value[0]['type_id'])?$dis_value[0]['type_id']:'';
				// $ty_arr   = explode(',', $type_val);
				// $category_arr   = explode(',', $category_val);
				$data = array(
					
					'type_id'     => $type_val,
			    	'sub_cat_id'    => $s_category_val,
			    	'method'      => '_listSubCategoryProducts_dis',
			    );
				
			    $data_list = avul_call(API_URL.'catlog/api/product',$data);					
			    $data_val  = $data_list['data'];
				
			    $option ='';
				
				

			    if(!empty($data_val))
        		{
        			foreach ($data_val as $key => $value) {
        				$type_id     = !empty($value['type_id'])?$value['type_id']:'';
        				$description = !empty($value['description'])?$value['description']:'';
        				
        				$select   = '';
        				$type_res = explode(',', $type_val_ed);
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
			}

			else
			{
				if($param1 =='Edit')
				{
					$distributor_id = !empty($param2)?$param2:'';

					$where = array(
	            		'distributor_id' => $distributor_id,
	            		'method'         => '_detailDistributors'
	            	);

	            	$data_list  = avul_call(API_URL.'distributors/api/distributors',$where);
	            	$data_value = $data_list['data'];	

					$category_val = !empty($data_value[0]['category_id'])?$data_value[0]['category_id']:'';
					$s_category_val = !empty($data_value[0]['sub_cat_id'])?$data_value[0]['sub_cat_id']:'';
					$where_me = array(
						'category_id'    => $category_val,
	            		'distributor_id' => $this->session->userdata('id'),
	            		'method'         => '_distributorSubCategoryList'
	            	);

	            	$sub_list_dt  = avul_call(API_URL.'distributors/api/distributors',$where_me);
	            	$data_dt_value = $sub_list_dt['data'];	

					$where_ee = array(
	            		'distributor_id' => $this->session->userdata('id'),
	            		'method'         => '_detailDistributors'
	            	);

	            	$data_list1  = avul_call(API_URL.'distributors/api/distributors',$where_ee);
	            	$data_value1 = $data_list1['data'];
					$type_id = !empty($data_value1[0]['type_id'])?$data_value1[0]['type_id']:'';
					

					// Type Details
	            	$type_whr = array(
	            		'sub_cat_id' => $s_category_val,
						'type_id'    => $type_id,
	            		'method'      => '_listSubCategoryProducts_dis'
	            	);

	            	$type_list   = avul_call(API_URL.'catlog/api/product',$type_whr);

					$page['dataval']    = $data_list['data'];
					$page['type_val']   = $type_list['data'];
					$page['sub_val']   = $sub_list_dt['data'];
					$page['method']     = 'BTBM_X_U';
					$page['page_title'] = "Edit Distributors";
				}
				else
				{
					$page['dataval']    = '';
					$page['city_val']   = '';
					$page['zone_val']   = '';
					$page['method']     = 'BTBM_X_C';
					$page['page_title'] = "Add Distributors";
				}
				$where = array(
        			'distributor_id' => $this->session->userdata('id'),
        			'method'         => '_distributorCategoryList',
        		);

        		$category_list = avul_call(API_URL.'distributors/api/distributors',$where);
				
        		$category_res  = $category_list['data'];

				$where_1 = array(
					'distributor_id'      => $distributor_id,
            		'method'         => '_getDetails'
            	);

            	$initial_list = avul_call(API_URL.'subdistributors/api/initial_details',$where_1);
            	

            	
            	$page['intial_data'] = $initial_list;
			    $page['category_val']  = $category_res;
				$page['main_heading'] = "Distributors";
				$page['sub_heading']  = "Distributors";
				$page['pre_title']    = "List Distributors";
				$page['pre_menu']     = "index.php/distributors/sub_distributors/list_distributors";
				$data['page_temp']    = $this->load->view('distributors/sub_distributors/add_distributors',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_sub_distributors";
				$this->bassthaya->load_distributors_form_template($data);
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
				$page['pre_menu']     = "index.php/distributors/sub_distributors/add_distributors";
				$data['page_temp']    = $this->load->view('distributors/sub_distributors/list_distributors',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_sub_distributors";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
	            		'offset'       => $_offset,
	            		'limit'        => $limit,
	            		'search'       => $search,
						'ref_id'       => $this->session->userdata('id'),
	            		'company_type' => 1,
	            		'company_id'   => 0,
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
				            
				            	$edit = '<a href="'.BASE_URL.'index.php/distributors/distributor/add_distributors/Edit/'.$distributor_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
				           
				            	$delete = '<a data-row="'.$i.'" data-id="'.$distributor_id.'" data-value="distributors" data-cntrl="distributor" data-func="list_distributors" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
				            

						    $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.$mobile.'</td>
	                                <td class="line_height">'.mb_strimwidth($email, 0, 20, '...').'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                               
		                            	$table .= '<td>'.$edit.$delete.'</td>';
		                        	
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
	}
?>
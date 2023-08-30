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
				
				$company_name       = $this->input->post('company_name');
				$contact_name       = $this->input->post('contact_name');
				$mobile             = $this->input->post('mobile');
				$email              = $this->input->post('email');
				$gst_no             = $this->input->post('gst_no');
				$pan_no             = $this->input->post('pan_no');
				$tan_no             = $this->input->post('tan_no');
				$bill_no            = $this->input->post('bill_no');
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
				$state_id           = $this->input->post('state_id');
				$city_id           = $this->input->post('city_id');
				$address            = $this->input->post('address');
				$distributor_id     = $this->input->post('distributor_id');
				$method             = $this->input->post('method');
			

				$required = array('company_name', 'mobile', 'email', 'gst_no', 'state_id', 'city_id', 'address');
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
							    'account_name'       => $account_name,
							    'account_no'         => $account_no,
							    'account_type'       => $account_type,
							    'ifsc_code'          => $ifsc_code,
							    'bank_name'          => $bank_name,
							    'branch_name'        => $branch_name,
							    'pincode'            => $pincode,
							    'state_id'           => $state_id,
								'city_id'           => $city_id,
							    'address'            => $address,
							    'distributor_type'   => '1',
							    'method'             => '_addDistributors',
								'mg_role'            => $this->session->userdata('designation_code'),
								
                                'ref_by'              => $this->session->userdata('id'),
								
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
							'account_name'       => $account_name,
							'account_no'         => $account_no,
							'account_type'       => $account_type,
							'ifsc_code'          => $ifsc_code,
							'bank_name'          => $bank_name,
							'branch_name'        => $branch_name,
							'pincode'            => $pincode,
							'state_id'           => $state_id,
							'city_id'           => $city_id,
							'address'            => $address,
							'ref_id'              => 0,
							'mg_role'            => $this->session->userdata('designation_code'),
							'ref_by'              => $this->session->userdata('id'),
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
			 if($param1 =='getCity_name')
			{
				$state_id = $this->input->post('state_id');

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

                        $option .= '<option value="'.$city_id.'">'.$city_name.'</option>';
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
					$state_id     = !empty($data_value[0]['state_id'])?$data_value[0]['state_id']:'';	
					$where_state = array(
						'state_id' => $state_id,
						'method'   => '_listCity'
					);
	
					$city_list   = avul_call(API_URL.'master/api/city',$where_state);
					$city_result = $city_list['data'];

					$page['dataval']    = $data_list['data'];
					$page['city']       = $city_list['data'];
					$page['method']     = '_updateDistributors';
					$page['page_title'] = "Edit Distributors";
				}
				else
				{
					$page['dataval']    = '';
					$page['city_val']   = '';
					$page['zone_val']   = '';
					$page['method']     = '_addDistributors';
					$page['page_title'] = "Add Distributors";
				}

				

            	// State List
				$where_2 = array(
            		'method' => '_listState',
            	);

            	$state_list = avul_call(API_URL.'master/api/state',$where_2);
            	$state_data = $state_list['data'];

            	// Vendor List
				
				$page['submit_url']   = "distributors/api/distributors";
            	$page['state_val']    = $state_data;
				$page['main_heading'] = "Distributors";
				$page['sub_heading']  = "Distributors";
				$page['pre_title']    = "List Distributors";
				$page['page_access']  = userAccess('distributors-view');
				$page['pre_menu']     = "index.php/managers/distributors/list_distributor";
				$data['page_temp']    = $this->load->view('managers/distributors/create_distributor',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_distributors";
				$this->bassthaya->load_Managers_form_template($data);
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
				$page['pre_menu']     = "index.php/managers/distributors/add_distributors";
				$data['page_temp']    = $this->load->view('managers/distributors/list_distri',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_distributors";
				$this->bassthaya->load_Managers_form_template($data);
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
						'mg_id'       => $this->session->userdata('id'),
	            		'method'       => '_listDistributorsPaginate_mg2'
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
							$designation_code = $this->session->userdata('designation_code');
						
							if($designation_code == 'RSM'){
								if($active_status == '3')
								{
									$status_view = '<span class="badge badge-primary">Pending</span>';
								}else if($active_status == '6'){
									$status_view = '<span class="badge badge-danger">Rejected</span>';
								}else if($active_status == '1'){
									$status_view = '<span class="badge badge-success">success</span>';
								}else if($active_status == '2'){
									$status_view = '<span class="badge badge-warning">In Active</span>';
								}
							}else if($designation_code == 'ASM'){
								if($active_status == '3'||$active_status == '4')
								{
									$status_view = '<span class="badge badge-primary">Pending</span>';
								}else if($active_status == '6'){
									$status_view = '<span class="badge badge-danger">Rejected</span>';
								}else if($active_status == '1'){
									$status_view = '<span class="badge badge-success">success</span>';
								}else if($active_status == '2'){
									$status_view = '<span class="badge badge-warning">In Active</span>';
								}
							}else if($designation_code == 'SO'){
								if($active_status == '3'||$active_status == '4'||$active_status == '5')
								{
									$status_view = '<span class="badge badge-primary">Pending</span>';
								}else if($active_status == '6'){
									$status_view = '<span class="badge badge-danger">Rejected</span>';
								}else if($active_status == '1'){
									$status_view = '<span class="badge badge-success">success</span>';
								}else if($active_status == '2'){
									$status_view = '<span class="badge badge-warning">In Active</span>';
								}
							}else if($designation_code == 'TSI'){
								if($active_status == '3'||$active_status == '4'||$active_status == '5')
								{
									$status_view = '<span class="badge badge-primary">Pending</span>';
								}else if($active_status == '6'){
									$status_view = '<span class="badge badge-danger">Rejected</span>';
								}else if($active_status == '1'){
									$status_view = '<span class="badge badge-success">success</span>';
								}else if($active_status == '2'){
									$status_view = '<span class="badge badge-warning">In Active</span>';
								}
							}
						   

			                $edit   = '';
				          
				            
				            	$edit = '<a href="'.BASE_URL.'index.php/managers/distributors/add_distributors/Edit/'.$distributor_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
				           
				            	
				           

						    $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.$mobile.'</td>
	                                <td class="line_height">'.mb_strimwidth($email, 0, 20, '...').'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                               
		                            	$table .= '<td>'.$edit.'</td>';
		                        	
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
				$page['pre_menu']     = "index.php/managers/distributors/add_distributors";
				$data['page_temp']    = $this->load->view('managers/distributors/list_distributors',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_dis_approval";
				$this->bassthaya->load_Managers_form_template($data);
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
						'mg_id'       => $this->session->userdata('id'),
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
							$yes =1;
							$no =0;
							$designation_code = $this->session->userdata('designation_code');
							$edit   = '';
				            $delete = '';
							if($designation_code == 'ASM'){
								if($active_status == '4' || $active_status == '3')
								{
									$status_view = '<span class="badge badge-success">pending</span>';
								}
								else if($active_status == '5')
								{
									$status_view = '<span class="badge badge-primary">Not Approve</span>';

									$delete = '<a data-row="'.$i.'" data-id="'.$distributor_id.'" data-value="managers" data-cntrl="distributors" data-func="list_dis_approval" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Reject </a>';
									$edit = '<a data-row="'.$i.'" data-row="'.$yes.'" data-id="'.$distributor_id.'" data-value="managers" data-cntrl="distributors" data-func="list_dis_approval" class="approve-btn button_clr btn btn-primary"><i class="ft-user-check"></i> Approval </a>';
								}else if($active_status == '6')
								{
									$status_view = '<span class="badge badge-danger">Rejected</span>';

									
									$edit = '<a data-row="'.$i.'" data-row="'.$yes.'" data-id="'.$distributor_id.'" data-value="managers" data-cntrl="distributors" data-func="list_dis_approval" class="approve-btn button_clr btn btn-primary"><i class="ft-user-check"></i> Re-create </a>';
								}
								
							}if($designation_code == 'RSM'){
								if($active_status == '3')
								{
									$status_view = '<span class="badge badge-success">pending</span>';
								}
								else if($active_status == '4')
								{
									$status_view = '<span class="badge badge-primary">Not Approve</span>';

									$delete = '<a data-row="'.$i.'" data-id="'.$distributor_id.'" data-value="managers" data-cntrl="distributors" data-func="list_dis_approval" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Reject </a>';
									$edit = '<a data-row="'.$i.'" data-row="'.$yes.'" data-id="'.$distributor_id.'" data-value="managers" data-cntrl="distributors" data-func="list_dis_approval" class="approve-btn button_clr btn btn-primary"><i class="ft-user-check"></i> Approval </a>';
								}else if($active_status == '6')
								{
									$status_view = '<span class="badge badge-danger">Rejected</span>';

									
									$edit = '<a data-row="'.$i.'" data-row="'.$yes.'" data-id="'.$distributor_id.'" data-value="managers" data-cntrl="distributors" data-func="list_dis_approval" class="approve-btn button_clr btn btn-primary"><i class="ft-user-check"></i> Re-create </a>';
								}
							}
						 
							$view = '<a href="'.BASE_URL.'index.php/managers/distributors/list_dis_approval/view/'.$distributor_id.'" class="button_clr btn btn-success"><i class="ft-file-text"></i></a>';
			               
				            
				           
				            	
				           
				            	

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
			
				$designation_code = $this->session->userdata('designation_code');
				if($designation_code=='ASM'){
					$status = 4;
				}else{
					$status = 3;
				}
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
				$page['pre_menu']      = "index.php/managers/distributors/data_list";
				$data['page_temp']     = $this->load->view('managers/distributors/view_distributor',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "list_dis_approval";
				$this->bassthaya->load_Managers_form_template($data);
			}
		}
	}
?>
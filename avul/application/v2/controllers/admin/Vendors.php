<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Vendors extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function add_vendor($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage  = $this->input->post('formpage');
			$log_id    = $this->session->userdata('id');
			$log_role  = $this->session->userdata('user_role');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
	            $company_name   = $this->input->post('company_name');
	            $vendor_no      = $this->input->post('vendor_no');
				$contact_name   = $this->input->post('contact_name');
				$contact_no     = $this->input->post('contact_no');
				$email          = $this->input->post('email');
				$gst_no         = $this->input->post('gst_no');
				$vendor_type    = $this->input->post('vendor_type');
				$due_days       = $this->input->post('due_days');
				$account_name   = $this->input->post('account_name');
				$account_no     = $this->input->post('account_no');
				$account_type   = $this->input->post('account_type');
				$ifsc_code      = $this->input->post('ifsc_code');
				$bank_name      = $this->input->post('bank_name');
				$branch_name    = $this->input->post('branch_name');
				$password       = $this->input->post('password');
				$state_id       = $this->input->post('state_id');
				$city_id        = $this->input->post('city_id');
				$purchase_type  = $this->input->post('purchase_type');
				$msme_type      = $this->input->post('msme_type');
				$address        = $this->input->post('address');
				$distributor_id = $this->input->post('distributor_id');	
				$method         = $this->input->post('method');

				$required = array('company_name', 'gst_no', 'email', 'contact_no', 'vendor_type', 'state_id', 'city_id', 'password', 'purchase_type', 'msme_type');
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
			    		if(userAccess('manufacture-add'))
			    		{
			    			$postData = array(
			    				'log_id'        => $log_id,
								'log_role'      => $log_role,
						    	'company_name'  => ucfirst($company_name),
						    	'contact_name'  => $contact_name,
								'contact_no'    => $contact_no,
								'email'         => $email,
								'gst_no'        => $gst_no,
								'vendor_type'   => $vendor_type,
								'due_days'      => $due_days,
								'account_name'  => $account_name,
								'account_no'    => $account_no,
								'account_type'  => $account_type,
								'ifsc_code'     => $ifsc_code,
								'bank_name'     => $bank_name,
								'branch_name'   => $branch_name,
								'state_id'      => $state_id,
								'city_id'       => $city_id,
								'purchase_type' => $purchase_type,
								'msme_type'     => $msme_type,
								'password'      => $password,
								'address'       => $address,
								'createdate'    => date('Y-m-d H:i:s'),
								'method'        => '_addVendors',
						    );

						    $data_save = avul_fileUpload(API_URL.'vendors/api/vendors',$postData);

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
			    		if(userAccess('manufacture-edit'))
			    		{	
			    			$vendor_id = $this->input->post('vendor_id');
				    		$pstatus   = $this->input->post('pstatus');

				    		$postData = array(
				    			'log_id'         => $log_id,
								'log_role'       => $log_role,
				    			'id'             => $vendor_id,
						    	'company_name'   => ucfirst($company_name),
						    	'vendor_no'      => $vendor_no,
								'contact_no'     => $contact_no,
								'email'          => $email,
								'gst_no'         => $gst_no,
								'vendor_type'    => $vendor_type,
								'due_days'       => $due_days,
								'account_name'   => $account_name,
								'account_no'     => $account_no,
								'account_type'   => $account_type,
								'ifsc_code'      => $ifsc_code,
								'bank_name'      => $bank_name,
								'branch_name'    => $branch_name,
								'state_id'       => $state_id,
								'city_id'        => $city_id,
								'purchase_type'  => $purchase_type,
								'msme_type'      => $msme_type,
								'password'       => $password,
								'address'        => $address,
								'distributor_id' => $distributor_id,
						    	'status'         => $pstatus,
						    	'method'         => '_updateVendors',
						    );

						    $data_save = avul_fileUpload(API_URL.'vendors/api/vendors',$postData);

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

			else if($param1 =='getCity_name')
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
					$vendor_id = !empty($param2)?$param2:'';

					$where_1 = array(
	            		'vendor_id' => $vendor_id,
	            		'method'     => '_detailVendors'
	            	);

	            	$data_list   = avul_call(API_URL.'vendors/api/vendors',$where_1);

	            	$start_value = !empty($data_list['data'][0]['state_id'])?$data_list['data'][0]['state_id']:'';

	            	$where_2 = array(
	            		'state_id' => $start_value,
	            		'method'   => '_listCity'
	            	);

	            	$city_list = avul_call(API_URL.'master/api/city',$where_2);

	            	$page['city_val']   = $city_list['data'];
					$page['dataval']    = $data_list['data'];
					$page['method']     = 'BTBM_X_U';
					$page['page_title'] = "Edit Manufacture";
					$page['api_method'] = '_updateVendors';
				}
				else
				{	
					$page['city_val']   = '';
					$page['dataval']    = '';
					$page['method']     = 'BTBM_X_C';
					$page['page_title'] = "Add Manufacture";
					$page['api_method'] = '_addVendors';
				}

				$where_1 = array(
            		'method'   => '_listState'
            	);

            	$state_list  = avul_call(API_URL.'master/api/state',$where_1);

				$page['state_val']    = $state_list['data'];
				$page['main_heading'] = "Manufacture";
				$page['sub_heading']  = "Manufacture";
				$page['pre_title']    = "List Manufacture";
				$page['page_access']  = userAccess('manufacture-view');
				$page['pre_menu']     = "index.php/admin/vendors/list_vendor";
				$data['page_temp']    = $this->load->view('admin/vendor/add_vendor',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_vendor";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function list_vendor($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Manufacture";
				$page['sub_heading']  = "Manufacture";
				$page['page_title']   = "List Manufacture";
				$page['pre_title']    = "Add Manufacture";
				$page['page_access']  = userAccess('manufacture-add');
				$page['pre_menu']     = "index.php/admin/vendors/add_vendor";
				$data['page_temp']    = $this->load->view('admin/vendor/list_vendor',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_vendor";
				$this->bassthaya->load_admin_form_template($data);
			}
			else if($param1 == 'data_list')
			{
				if(userAccess('manufacture-view'))
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
	            		'method'  => '_listVendorsPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'vendors/api/vendors',$where);
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
		            		$vendor_id     = !empty($value['vendor_id'])?$value['vendor_id']:'';
						    $company_name  = !empty($value['company_name'])?$value['company_name']:'';
						    $gst_no        = !empty($value['gst_no'])?$value['gst_no']:'';
						    $contact_no    = !empty($value['contact_no'])?$value['contact_no']:'';
						    $email         = !empty($value['email'])?$value['email']:'';
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
				            if(userAccess('manufacture-edit') == TRUE)
				            {
				            	$edit = '<a href="'.BASE_URL.'index.php/admin/vendors/add_vendor/Edit/'.$vendor_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
				            }
				            if(userAccess('manufacture-delete') == TRUE)
				            {
				            	$delete = '<a data-row="'.$i.'" data-id="'.$vendor_id.'" data-value="admin" data-cntrl="vendors" data-func="list_vendor" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
				            }

						    $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 15, '...').'</td>
	                                <td class="line_height">'.$contact_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($email, 0, 15, '...').'</td>
	                                <td class="line_height">'.mb_strimwidth($gst_no, 0, 10, '...').'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                                if(userAccess('manufacture-edit') == TRUE || userAccess('manufacture-delete') == TRUE):
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
						'log_id'    => $log_id,
						'log_role'  => $log_role,
				    	'vendor_id' => $id,
				    	'method'    => '_deleteVendors'
				    );

				    $data_save = avul_call(API_URL.'vendors/api/vendors',$data);

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
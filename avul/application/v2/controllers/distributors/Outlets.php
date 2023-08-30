<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Outlets extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function add_outlets($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');
			$log_id   = $this->session->userdata('id');
			$log_role = 'Distribuotr';

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$outlet_category = $this->input->post('outlet_category');
				$company_name    = $this->input->post('company_name');
				$contact_name    = $this->input->post('contact_name');
				$mobile          = $this->input->post('mobile');
				$email           = $this->input->post('email');
				$gst_type        = $this->input->post('gst_type');
				$gst_no          = $this->input->post('gst_no');
				$pan_no          = $this->input->post('pan_no');
				$tan_no          = $this->input->post('tan_no');
				$credit_limit    = $this->input->post('credit_limit');
				$discount        = $this->input->post('discount');
				$due_days        = $this->input->post('due_days');
				$pincode         = $this->input->post('pincode');
				$account_name    = $this->input->post('account_name');
				$account_no      = $this->input->post('account_no');
				$account_type    = $this->input->post('account_type');
				$ifsc_code       = $this->input->post('ifsc_code');
				$bank_name       = $this->input->post('bank_name');
				$branch_name     = $this->input->post('branch_name');
				$state_id        = $this->input->post('state_id');
				$city_id         = $this->input->post('city_id');
				$zone_id         = $this->input->post('zone_id');
				$outlet_type     = $this->input->post('outlet_type');
				$address         = $this->input->post('address');
				$latitude        = $this->input->post('latitude');
				$longitude       = $this->input->post('longitude');
				$otp_type        = $this->input->post('otp_type');	
				$sales_type      = $this->input->post('sales_type');	
				$method          = $this->input->post('method');

				$required = array('outlet_category', 'company_name', 'mobile', 'gst_type', 'state_id', 'city_id', 'zone_id', 'outlet_type', 'latitude', 'longitude', 'otp_type', 'sales_type');
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
		    				'log_id'          => $log_id,
							'log_role'        => $log_role,
							'outlet_status'   => 2,
							'distributor_id'  => $this->session->userdata('id'),
							'outlet_category' => $outlet_category,
					    	'company_name'    => ucfirst($company_name),
							'contact_name'    => ucfirst($contact_name),
							'mobile'          => $mobile,
							'email'           => $email,
							'gst_type'        => $gst_type,
							'gst_no'          => $gst_no,
							'pan_no'          => $pan_no,
							'tan_no'          => $tan_no,
							'credit_limit'    => $credit_limit,
							'discount'        => $discount,
							'due_days'        => $due_days,
							'pincode'         => $pincode,
							'account_name'    => $account_name,
							'account_no'      => $account_no,
							'account_type'    => $account_type,
							'ifsc_code'       => $ifsc_code,
							'bank_name'       => $bank_name,
							'branch_name'     => $branch_name,
							'state_id'        => $state_id,
							'city_id'         => $city_id,
							'zone_id'         => $zone_id,
							'outlet_type'     => $outlet_type,
							'address'         => $address,
							'latitude'        => $latitude,
							'longitude'       => $longitude,
							'otp_type'        => $otp_type,
							'sales_type'      => $sales_type,
							'status'          => '1',
					    	'createdate'      => date('Y-m-d H:i:s'),
					    	'method'          => '_addOutlets',
					    );

					    $data_save = avul_call(API_URL.'outlets/api/outlets',$data);

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
		    			$outlets_id = $this->input->post('outlets_id');
			    		$pstatus    = $this->input->post('pstatus');

			    		$data = array(
			    			'log_id'          => $log_id,
							'log_role'        => $log_role,
							'outlet_status'   => 2,
							'distributor_id'  => $this->session->userdata('id'),
			    			'id'              => $outlets_id,
			    			'outlet_category' => $outlet_category,
					    	'company_name'    => ucfirst($company_name),
							'contact_name'    => ucfirst($contact_name),
							'mobile'          => $mobile,
							'email'           => $email,
							'gst_type'        => $gst_type,
							'gst_no'          => $gst_no,
							'pan_no'          => $pan_no,
							'tan_no'          => $tan_no,
							'credit_limit'    => $credit_limit,
							'discount'        => $discount,
							'due_days'        => $due_days,
							'pincode'         => $pincode,
							'account_name'    => $account_name,
							'account_no'      => $account_no,
							'account_type'    => $account_type,
							'ifsc_code'       => $ifsc_code,
							'bank_name'       => $bank_name,
							'branch_name'     => $branch_name,
							'state_id'        => $state_id,
							'city_id'         => $city_id,
							'zone_id'         => $zone_id,
							'outlet_type'     => $outlet_type,
							'address'         => $address,
							'latitude'        => $latitude,
							'longitude'       => $longitude,
							'otp_type'        => $otp_type,
							'sales_type'      => $sales_type,
							'status'          => $pstatus,
					    	'method'          => '_updateOutlets',
					    );

					    $data_save = avul_call(API_URL.'outlets/api/outlets',$data);

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

			else if($param1 =='getZone_name')
			{
				$state_id = $this->input->post('state_id');
				$city_id  = $this->input->post('city_id');

				$where = array(
            		'state_id' => $state_id,
            		'city_id'  => $city_id,
            		'method'   => '_listZone'
            	);

            	$zone_list   = avul_call(API_URL.'master/api/zone',$where);
            	$zone_result = $zone_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($zone_result))
        		{
        			foreach ($zone_result as $key => $value) {
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

			else
			{
				if($param1 =='Edit')
				{
					$outlets_id = !empty($param2)?$param2:'';

					$where = array(
	            		'outlets_id' => $outlets_id,
	            		'method'     => '_detailOutlets'
	            	);

	            	$data_list  = avul_call(API_URL.'outlets/api/outlets',$where);

					$where_1 = array(
	            		'method'   => '_listState'
	            	);

	            	$state_list  = avul_call(API_URL.'master/api/state',$where_1);

	            	$start_value = !empty($data_list['data'][0]['state_id'])?$data_list['data'][0]['state_id']:'';

	            	$where_2 = array(
	            		'state_id' => $start_value,
	            		'method'   => '_listCity'
	            	);

	            	$city_list  = avul_call(API_URL.'master/api/city',$where_2);

	            	$city_value = !empty($data_list['data'][0]['city_id'])?$data_list['data'][0]['city_id']:'';

	            	$where_3 = array(
	            		'state_id' => $start_value,
	            		'city_id'  => $city_value,
	            		'method'   => '_listZone'
	            	);

	            	$zone_list   = avul_call(API_URL.'master/api/zone',$where_3);

	            	$page['state_val']  = $state_list['data'];
					$page['city_val']   = $city_list['data'];
					$page['zone_val']   = $zone_list['data'];
					$page['dataval']    = $data_list['data'];
					$page['method']     = '_updateOutlets';
					$page['page_title'] = "Edit Outlets";
				}
				else
				{

					$where_1 = array(
	            		'method'   => '_listState'
	            	);

	            	$state_list  = avul_call(API_URL.'master/api/state',$where_1);

	            	$page['state_val']    = $state_list['data'];
					$page['dataval']      = '';
					$page['method']       = '_addOutlets';
					$page['page_title']   = "Add Outlets";
				}

				$where_2 = array(
            		'method'   => '_listOutletCategoty'
            	);
				
            	$category_list  = avul_call(API_URL.'master/api/outlet_category',$where_2);
				$page['submit_url']   = "outlets/api/outlets";
            	$page['category_val'] = $category_list['data'];
				$page['main_heading'] = "Outlets";
				$page['sub_heading']  = "Outlets";
				$page['pre_title']    = "List Outlets";
				$page['page_access']  = userAccess('outlets-view');
				$page['pre_menu']     = "index.php/distributors/outlets/list_outlets";
				$data['page_temp']    = $this->load->view('distributors/outlets/add_outlets',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_outlets";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		// public function list_outlets($param1="", $param2="", $param3="")
		// {
		// 	if ($this->session->userdata('random_value') == '')
  //       	redirect(base_url() . 'index.php?login', 'refresh');

		// 	if($param1 == '')
		// 	{
		// 		$page['main_heading'] = "Outlets";
		// 		$page['sub_heading']  = "Outlets";
		// 		$page['page_title']   = "List Outlets";
		// 		$page['pre_title']    = "Add Outlets";
		// 		$page['pre_menu']     = "index.php/distributors/outlets/add_outlets";
		// 		$data['page_temp']    = $this->load->view('distributors/outlets/list_outlets',$page,TRUE);
		// 		$data['view_file']    = "Page_Template";
		// 		$data['currentmenu']  = "list_outlets";
		// 		$this->bassthaya->load_distributors_form_template($data);
		// 	}
			
		// 	else if($param1 == 'data_list')
		// 	{
  //               $limit    = $this->input->post('limitval');
  //           	$page     = $this->input->post('page');
  //           	$search   = $this->input->post('search');
  //           	$cur_page = isset($page)?$page:'1';
  //           	$_offset  = ($cur_page-1) * $limit;
  //           	$nxt_page = $cur_page + 1;
  //           	$pre_page = $cur_page - 1;

  //           	$where = array(
  //           		'offset'         => $_offset,
  //           		'limit'          => $limit,
  //           		'search'         => $search,
  //           		'distributor_id' => $this->session->userdata('id'),
  //           		'method'         => '_listDistributorOutletsPaginate'
  //           	);

  //           	$data_list  = avul_call(API_URL.'outlets/api/distributor_outlet_list',$where);
  //           	$data_value = !empty($data_list['data'])?$data_list['data']:'';

  //           	if(!empty($data_value))
  //           	{
  //           		$count    = count($data_value);
	 //            	$total    = isset($data_list['total_record'])?$data_list['total_record']:'';
	 //            	$tot_page = ceil($total / $limit); 

  //           		$status  = 1;
	 //            	$message = 'Success';
	 //            	$table   = '';

	 //            	$i=1;
	 //            	foreach ($data_value as $key => $value) {

	 //            		$assign_id      = !empty($value['assign_id'])?$value['assign_id']:'';
		// 	            $distributor_id = !empty($value['distributor_id'])?$value['distributor_id']:'';
		// 	            $outlet_id      = !empty($value['outlet_id'])?$value['outlet_id']:'';
		// 	            $outlet_name    = !empty($value['outlet_name'])?$value['outlet_name']:'';
		// 	            $mobile         = !empty($value['mobile'])?$value['mobile']:'';
		// 	            $pre_bal        = !empty($value['pre_bal'])?$value['pre_bal']:'';
		// 	            $cur_bal        = !empty($value['cur_bal'])?$value['cur_bal']:'';
		// 	            $active_status  = !empty($value['status'])?$value['status']:'';

		// 			    if($active_status == '1')
		//                 {
		//                 	$status_view = '<span class="badge badge-success">Active</span>';
		//                 }
		//                 else
		//                 {
		//                 	$status_view = '<span class="badge badge-danger">In Active</span>';
		//                 }

		// 			    $table .= '
		// 			    	<tr class="row_'.$i.'">
  //                               <td class="line_height">'.$i.'</td>
  //                               <td class="line_height">'.mb_strimwidth($outlet_name, 0, 35, '...').'</td>
  //                               <td class="line_height">'.$mobile.'</td>
  //                               <td class="line_height">'.$status_view.'</td>
  //                               <td>
  //                               	<a data-row="'.$i.'" data-id="'.$assign_id.'" data-value="distributors" data-cntrl="outlets" data-func="list_outlets" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>
  //                               </td>
  //                           </tr>
		// 			    ';
		// 			    $i++;
	 //            	}

	 //            	$prev    = '';

	 //            	$next = '
		//         		<tr>
		//         			<td>';
		//         				if($cur_page >= 2):
		//         				$next .='<span data-page="'.$pre_page.'" class="pages btn btn-warning waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>';
		//         				endif;
		//         			$next .= '</td>
		//         			<td>';
		//         				if($tot_page > $cur_page):
		//         				$next .='<span data-page="'.$nxt_page.'" class="pages btn btn-success waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
		//         				endif;
		//         			$next .='</td>
		//         		</tr>
		//         	';
  //           	} 
  //           	else
  //           	{
  //           		$status     = 0;
	 //            	$message    = 'No Records';
	 //            	$table      = '';
	 //            	$next       = '';
	 //            	$prev       = '';
  //           	}

  //           	$response['status']     = $status;  
  //               $response['result']     = $table;  
  //               $response['message']    = $message;
  //               $response['next']       = $next;
  //               $response['prev']       = $prev;
  //               echo json_encode($response);
  //               return;
		// 	}

		// 	else if($param1 == 'delete')
		// 	{
		// 		$id = $this->input->post('id');

		// 		if(!empty($id))	
		// 		{
		// 			$data = array(
		// 		    	'assign_id' => $id,
		// 		    	'method'    => '_deleteDistributorOutlets'
		// 		    );

		// 		    $data_save = avul_call(API_URL.'outlets/api/distributor_outlet_list',$data);

		// 		    if($data_save['status'] == 1)
		// 		    {
		// 		    	$response['status']  = 1;
		// 		        $response['message'] = $data_save['message']; 
		// 		        $response['data']    = [];
		// 		        echo json_encode($response);
		// 		        return; 
		// 		    }
		// 		    else
		// 		    {
		// 		    	$response['status']  = 0;
		// 		        $response['message'] = $data_save['message']; 
		// 		        $response['data']    = [];
		// 		        echo json_encode($response);
		// 		        return; 	
		// 		    }
		// 		}
		// 		else
		// 		{
		// 			$response['status']  = 0;
		// 	        $response['message'] = "Please fill all required fields"; 
		// 	        $response['data']    = [];
		// 	        $response['error']   = []; 
		// 	        echo json_encode($response);
		// 	        return;
		// 		}
		// 	}
		// }

		public function list_outlets($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Outlets";
				$page['sub_heading']  = "Outlets";
				$page['page_title']   = "List Outlets";
				$page['pre_title']    = "Add Outlets";
				$page['page_access']  = "";
				$page['pre_menu']     = "index.php/distributors/outlets/add_outlets";
				$data['page_temp']    = $this->load->view('distributors/outlets/list_outlets',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_outlets";
				$this->bassthaya->load_distributors_form_template($data);
			}
			
			else if($param1 == 'data_list')
			{
				$dis_id   = $this->session->userdata('id');
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
            		'distributor_id' => $dis_id,
            		'outlet_status'  => 2,
            		'method'         => '_listOutletsPaginate'
            	);

            	$data_list  = avul_call(API_URL.'outlets/api/outlets',$where);
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
	            		$outlets_id    = !empty($value['outlets_id'])?$value['outlets_id']:'';
					    $category_name = !empty($value['category_name'])?$value['category_name']:'---';
					    $outlet_image  = !empty($value['outlet_image'])?$value['outlet_image']:null;
					    $company_name  = !empty($value['company_name'])?$value['company_name']:'---';
					    $gst_no        = !empty($value['gst_no'])?$value['gst_no']:'---';
					    $mobile        = !empty($value['mobile'])?$value['mobile']:'---';
					    $email         = !empty($value['email'])?$value['email']:'---';
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

		                if(is_null($outlet_image))
		                {
		                	$img_value = BASE_URL.'app-assets/images/img_icon.png';
		                }
		                else
		                {
		                	$img_value = FILE_URL.'outlet/'.$outlet_image;
		                }

		            	$edit = '<a href="'.BASE_URL.'index.php/distributors/outlets/add_outlets/Edit/'.$outlets_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';

		            	$delete = '<a data-row="'.$i.'" data-id="'.$outlets_id.'" data-value="distributors" data-cntrl="outlets" data-func="list_outlets" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';

					    $table .= '
					    	<tr class="row_'.$i.'">
                                <td class="line_height">'.$i.'</td>
                                <td><img src="'.$img_value.'" id="account-upload-img" class="uploadedAvatar rounded me-50" alt="profile image" height="45" width="45"/></td>
                                <td class="line_height">'.mb_strimwidth($company_name, 0, 25, '...').'</td>
                                <td class="line_height">'.$category_name.'</td>
                                <td class="line_height">'.$mobile.'</td>
                                <td class="line_height">'.$status_view.'</td>
                                <td>';
		                        $table .= $edit.$delete;
                                $table .='</td>
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
				$log_role = 'distributors';

				if(!empty($id))	
				{
					$data = array(
						'log_id'     => $log_id,
						'log_role'   => $log_role,
				    	'outlets_id' => $id,
				    	'method'     => '_deleteOutlets'
				    );

				    $data_save = avul_call(API_URL.'outlets/api/outlets',$data);

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

		public function upload_outlets($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			if($param1 == '')
			{
				$page['submit_url']   = "import/api/outlet_import";
				$page['main_heading'] = "Outlets";
				$page['sub_heading']  = "Outlets";
				$page['page_title']   = "Upload Outlets";
				$page['pre_title']    = "List Outlets";
				$page['page_access']  = "";
				$page['pre_menu']     = "index.php/distributors/outlets/list_outlets";
				$data['page_temp']    = $this->load->view('distributors/outlets/upload_outlets',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "upload_outlets";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}
	}
?>
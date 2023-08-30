<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class Outlets extends CI_Controller {

		public function __construct()
		{
			parent::__construct();

			$this->load->model('outlets_model');
			$this->load->model('distributors_model');
			$this->load->model('payment_model');
			$this->load->model('employee_model');
			$this->load->model('loyalty_model');
			$this->load->model('assignshop_model');
			$this->load->model('commom_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Outlets
		// ***************************************************
		public function outlets($param1="",$param2="",$param3="")
		{
			$method   = $this->input->post('method');
			$log_id   = $this->input->post('user_id');
			$log_role = $this->input->post('user_role');

			if($method == '_addOutlets')
			{
				
				$error = FALSE;
			    $errors = array();
				
				$required = array('outlet_category', 'company_name', 'mobile', 'gst_type', 'credit_limit', 'pincode', 'state_id', 'city_id', 'zone_id', 'outlet_type', 'latitude', 'longitude', 'otp_type', 'sales_type');
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
			    if($this->input->post('email'))
			    {
				    if (mb_strlen($this->input->post('email')) > 254 || !filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL))
				    {
				        $response['status']  = 0;
				        $response['message'] = "E-mail address does not appear to be valid"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
			    }

			    if(count($errors)==0)
			    {
			    	$outlet_status   = $this->input->post('outlet_status');
			    	$distributor_id  = $this->input->post('distributor_id');
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
					$available_limit = $this->input->post('available_limit');
					$pre_limit       = $this->input->post('pre_limit');
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
			    	$status          = $this->input->post('status');

			    	if($gst_type == 1)
			    	{
			    		$gst_num = 'GSTIN';
			    	}
			    	else
			    	{
			    		$gst_num = $gst_no;
			    	}

			    	$where = array(
			    		'short_code'=> urlSlug($company_name),
						'mobile'    => $mobile,
						'status'    => '1',
						'published' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->outlets_model->getOutlets($where, '', '', 'result', '', '', '', '', $column);

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
							'outlet_status'   => $outlet_status,
							'distributor_id'  => $distributor_id,
							'outlet_category' => $outlet_category,
							'company_name'    => ucfirst($company_name),
							'short_code'      => urlSlug($company_name),
							'contact_name'    => ucfirst($contact_name),
							'mobile'          => $mobile,
							'email'           => $email,
							'gst_type'        => $gst_type,
							'gst_no'          => $gst_num,
							'pan_no'          => $pan_no,
							'tan_no'          => $tan_no,
							'credit_limit'    => $credit_limit,
							'available_limit' => $credit_limit,
							'pre_limit'       => '0',
							'discount'        => $discount,
							'due_days'        => $due_days,
							'pincode'         => $pincode,
							'account_name'    => $account_name,
							'account_no'      => $account_no,
							'account_type'    => $account_type,
							'ifsc_code'       => $ifsc_code,
							'bank_name'       => $bank_name,
							'branch_name'     => $branch_name,
							'state_id'        => (int)$state_id,
							'city_id'         => (int)$city_id,
							'zone_id'         => (int)$zone_id,
							'outlet_type'     => $outlet_type,
							'address'         => $address,
							'latitude'        => $latitude,
							'longitude'       => $longitude,
							'otp_type'        => $otp_type,
							'sales_type'      => $sales_type,
							'status'          => 1,
							'date'            => date('Y-m-d'),
					    	'createdate'      => date('Y-m-d H:i:s'),
					    );

						if(!empty($_FILES['outlet_image']['name']))
					    {
					    	$img_name  = $_FILES['outlet_image']['name'];
							$img_val   = explode('.', $img_name);
							$img_res   = $img_val[1];
							$file_name = generateRandomString(13).'.'.$img_res;

						    $configImg['upload_path']   ='upload/outlet/';
							// $configImg['max_size']      = '1024000';
							$configImg['allowed_types'] = 'jpg|jpeg|png|gif';
							$configImg['overwrite']     = FALSE;
							$configImg['remove_spaces'] = TRUE;
							// $configImg['max_width']     = 120;
                			// $configImg['max_height']    = 120;
                			$configImg['file_name']     = $file_name;
							$this->load->library('upload', $configImg);
							$this->upload->initialize($configImg);

							if(!$this->upload->do_upload('outlet_image'))
							{
						        $response['status']  = 0;
						        $response['message'] = $this->upload->display_errors();
						        $response['data']    = [];
						        echo json_encode($response);
						        return;
							}
							else
							{
								$data['outlet_image'] = $file_name;
							}

						
					    }
					    else
					    {
					    	$response['status']  = 0;
					        $response['message'] = 'overall_required'; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
					    }

					    $insert=$this->outlets_model->outlets_insert($data);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_outlets',
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

			else if($method == '_listOutletsPaginate')
			{
				$limit      = $this->input->post('limit');
	    		$offset     = $this->input->post('offset');
	    		$dis_id     = $this->input->post('distributor_id');
	    		$str_status = $this->input->post('outlet_status');

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
	    		if($search !='')
	    		{
	    			$like['name'] = $search;
	    		}
	    		else
	    		{
	    			$like = [];
	    		}

	    		$str_result = ($str_status)?$str_status:1;

	    		$where = array('A.published'=>'1');
	    		if($dis_id)
	    		{
	    			$where['A.distributor_id'] = $dis_id;
	    			$where['A.outlet_status']  = $str_result;
	    		}
	    		else
	    		{
	    			$where['A.outlet_status']  = $str_result;
	    		}

	    		$column = 'A.id';
				$overalldatas = $this->outlets_model->getOutletsJoin($where, '', '', 'result', $like, '', '', '', $column);

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

				$column    = 'A.id, A.outlet_image, A.company_name, A.contact_name, A.mobile, A.email, A.gst_no, A.pan_no, E.name AS category_name, A.published, A.status, A.createdate';
				$data_list = $this->outlets_model->getOutletsJoin($where, $limit, $offset, 'result', $like, '', $option, '', $column);
				
				if($data_list)
				{
					$outlets_list = [];
					foreach ($data_list as $key => $value) {

						$outlets_id    = isset($value->id)?$value->id:'';
			            $outlet_image  = isset($value->outlet_image)?$value->outlet_image:'';
			            $company_name  = isset($value->company_name)?$value->company_name:'';
						$contact_name  = isset($value->contact_name)?$value->contact_name:'';
						$mobile        = isset($value->mobile)?$value->mobile:'';
						$email         = isset($value->email)?$value->email:'';
						$gst_no        = isset($value->gst_no)?$value->gst_no:'';
						$pan_no        = isset($value->pan_no)?$value->pan_no:'';
						$category_name = isset($value->category_name)?$value->category_name:'';
						$published     = isset($value->published)?$value->published:'';
						$status        = isset($value->status)?$value->status:'';
						$createdate    = isset($value->createdate)?$value->createdate:'';

			            $outlets_list[] = array(
          					'outlets_id'    => $outlets_id,
          					'outlet_image'  => $outlet_image,
							'company_name'  => $company_name,
							'contact_name'  => $contact_name,
							'mobile'        => $mobile,
							'email'         => $email,
							'gst_no'        => $gst_no,
							'pan_no'        => $pan_no,
							'category_name' => $category_name,
							'published'     => $published,
							'status'        => $status,
							'createdate'    => $createdate,
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
			        $response['data']         = $outlets_list;
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

			else if($method == '_detailOutlets')
			{
				$outlets_id = $this->input->post('outlets_id');

		    	if(!empty($outlets_id))
		    	{

		    		$where = array('id'=>$outlets_id);
				    $data  = $this->outlets_model->getOutlets($where);
				    if($data)
				    {	

				    	$outlets_list = [];

						foreach ($data as $key => $value) {

							$outlets_id      = isset($value->id)?$value->id:'';
							$outlet_category = isset($value->outlet_category)?$value->outlet_category:'';
							$outlet_image = isset($value->outlet_image)?$value->outlet_image:'';
				            $company_name    = isset($value->company_name)?$value->company_name:'';
							$contact_name    = isset($value->contact_name)?$value->contact_name:'';
							$mobile          = isset($value->mobile)?$value->mobile:'';
							$email           = isset($value->email)?$value->email:'';
							$gst_type        = isset($value->gst_type)?$value->gst_type:'';
							$gst_no          = isset($value->gst_no)?$value->gst_no:'';
							$pan_no          = isset($value->pan_no)?$value->pan_no:'';
							$tan_no          = isset($value->tan_no)?$value->tan_no:'';
							$credit_limit    = isset($value->credit_limit)?$value->credit_limit:'';
							$available_lmt   = isset($value->available_limit)?$value->available_limit:'';
							$pre_limit       = isset($value->pre_limit)?$value->pre_limit:'';
							$current_bal     = isset($value->current_balance)?$value->current_balance:'';
							$discount        = isset($value->discount)?$value->discount:'';
							$due_days        = isset($value->due_days)?$value->due_days:'';
							$pincode         = isset($value->pincode)?$value->pincode:'';
							$account_name    = isset($value->account_name)?$value->account_name:'';
							$account_no      = isset($value->account_no)?$value->account_no:'';
							$account_type    = isset($value->account_type)?$value->account_type:'';
							$ifsc_code       = isset($value->ifsc_code)?$value->ifsc_code:'';
							$bank_name       = isset($value->bank_name)?$value->bank_name:'';
							$branch_name     = isset($value->branch_name)?$value->branch_name:'';
							$state_id        = isset($value->state_id)?$value->state_id:'';
							$city_id         = isset($value->city_id)?$value->city_id:'';
							$zone_id         = isset($value->zone_id)?$value->zone_id:'';
							$outlet_type     = isset($value->outlet_type)?$value->outlet_type:'';
							$address         = isset($value->address)?$value->address:'';
							$latitude        = isset($value->latitude)?$value->latitude:'';
							$longitude       = isset($value->longitude)?$value->longitude:'';
							$otp_type        = isset($value->otp_type)?$value->otp_type:'';
							$sales_type      = isset($value->sales_type)?$value->sales_type:'';
							$published       = isset($value->published)?$value->published:'';
							$status          = isset($value->status)?$value->status:'';
							$createdate      = isset($value->createdate)?$value->createdate:'';

				            $outlets_list[] = array(
	          					'outlets_id'      => $outlets_id,
	          					'outlet_category' => $outlet_category,
								'outlet_image' => $outlet_image,
								'company_name'    => $company_name,
								'contact_name'    => $contact_name,
								'mobile'          => $mobile,
								'email'           => $email,
								'gst_type'        => $gst_type,
								'gst_no'          => $gst_no,
								'pan_no'          => $pan_no,
								'tan_no'          => $tan_no,
								'credit_limit'    => $credit_limit,
								'available_limit' => $available_lmt,
								'pre_limit'       => $pre_limit,
								'current_balance' => $current_bal,
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
								'published'       => $published,
								'otp_type'        => $otp_type,
								'sales_type'      => $sales_type,
								'status'          => $status,
								'createdate'      => $createdate,
	          				);
						}

	        			$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $outlets_list;
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

			else if($method == '_listOutlets')
			{
				$where = array('status'=>'1', 'published'=>'1');

				$data_list = $this->outlets_model->getOutlets($where);

				if($data_list)
				{
					$outlets_list = [];

					foreach ($data_list as $key => $value) {

						$outlets_id   = isset($value->id)?$value->id:'';
			            $company_name = isset($value->company_name)?$value->company_name:'';
						$contact_name = isset($value->contact_name)?$value->contact_name:'';
						$mobile       = isset($value->mobile)?$value->mobile:'';
						$email        = isset($value->email)?$value->email:'';
						$gst_no       = isset($value->gst_no)?$value->gst_no:'';
						$pan_no       = isset($value->pan_no)?$value->pan_no:'';
						$published    = isset($value->published)?$value->published:'';
						$status       = isset($value->status)?$value->status:'';
						$createdate   = isset($value->createdate)?$value->createdate:'';

			            $outlets_list[] = array(
          					'outlets_id'   => $outlets_id,
							'company_name' => $company_name,
							'contact_name' => $contact_name,
							'mobile'       => $mobile,
							'email'        => $email,
							'gst_no'       => $gst_no,
							'pan_no'       => $pan_no,
							'published'    => $published,
							'status'       => $status,
							'createdate'   => $createdate,
          				);
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $outlets_list;
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

			else if($method == '_updateOutlets')
			{
				
				$error = FALSE;
			    $errors = array();
				$required = array('outlets_id', 'outlet_category', 'company_name', 'mobile', 'credit_limit', 'state_id', 'city_id', 'zone_id', 'latitude', 'longitude');
				$required = array('outlets_id');
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

			    if($this->input->post('email'))
			    {
				    if (mb_strlen($this->input->post('email')) > 254 || !filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL))
				    {
				        $response['status']  = 0;
				        $response['message'] = "E-mail address does not appear to be valid"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
			    }

			    if(count($errors)==0)
			    {	
			    	$outlets_id      = $this->input->post('outlets_id');
			    	$outlet_status   = $this->input->post('outlet_status');
			    	$distributor_id  = $this->input->post('distributor_id');
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
			    	$status          = $this->input->post('status');

			    	if($gst_type == 1)
			    	{
			    		$gst_num = 'GSTIN';
			    	}
			    	else
			    	{
			    		$gst_num = $gst_no;
			    	}

			    	$where=array(
			    		'id !='        => $outlets_id,
				    	'company_name' => ucfirst($company_name),
						'contact_name' => ucfirst($contact_name),
						'mobile'       => $mobile,
				    	'published'    => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->outlets_model->getOutlets($where, '', '', 'result', '', '', '', '', $column);

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
						// Outlet Details
						$out_whr    = array('id='    => $outlets_id);			   

						$out_col    = 'credit_limit, available_limit, current_balance';

						$outlet_val = $this->outlets_model->getOutlets($out_whr, '', '', 'result', '', '', '', '', $out_col);

						$old_credit    = !empty($outlet_val[0]->credit_limit)?strval($outlet_val[0]->credit_limit):'0';

						$old_available = !empty($outlet_val[0]->available_limit)?strval($outlet_val[0]->available_limit):'0';

						$old_balance   = !empty($outlet_val[0]->current_balance)?strval($outlet_val[0]->current_balance):'0';

						if((int)$old_credit <= (int)$credit_limit)
						{
							$new_credit_lmt    = (int)$credit_limit - (int)$old_credit;
							$new_available_lmt = (int)$old_available + (int)$new_credit_lmt;
						}
						else
						{
							if((int)$old_available <= (int)$credit_limit)
							{
								$extra_value       = (int)$old_credit - (int)$credit_limit;
								$new_available_lmt = (int)$old_available + (int)$extra_value;
							}
							else
							{
								$extra_value       = (int)$credit_limit - (int)$old_balance;
								$new_available_lmt = (int)$extra_value;
							}
						}

						$data = array(
							'outlet_category' => $outlet_category,
							'outlet_status'   => $outlet_status,
							'distributor_id'  => $distributor_id,
					    	'company_name'    => ucfirst($company_name),
					    	'short_code'      => urlSlug($company_name),
							'contact_name'    => ucfirst($contact_name),
							'mobile'          => $mobile,
							'email'           => $email,
							'gst_type'        => $gst_type,
							'gst_no'          => $gst_num,
							'pan_no'          => $pan_no,
							'tan_no'          => $tan_no,
							'credit_limit'    => $credit_limit,
							'available_limit' => $new_available_lmt,
							'pre_limit'       => $old_credit,
							'discount'        => $discount,
							'due_days'        => $due_days,
							'pincode'         => $pincode,
							'account_name'    => $account_name,
							'account_no'      => $account_no,
							'account_type'    => $account_type,
							'ifsc_code'       => $ifsc_code,
							'bank_name'       => $bank_name,
							'branch_name'     => $branch_name,
							'state_id'        => (int)$state_id,
							'city_id'         => (int)$city_id,
							'zone_id'         => (int)$zone_id,
							'outlet_type'     => $outlet_type,
							'address'         => $address,
							'latitude'        => $latitude,
							'longitude'       => $longitude,
							'otp_type'        => $otp_type,
							'sales_type'      => $sales_type,
					    	'status'          => $status,
					    	'updatedate'      => date('Y-m-d H:i:s')
					    );
						if(!empty($_FILES['outlet_image']['name']))
					    {
					    	$img_name  = $_FILES['outlet_image']['name'];
							$img_val   = explode('.', $img_name);
							$img_res   = $img_val[1];
							$file_name = generateRandomString(13).'.'.$img_res;

						    $configImg['upload_path']   ='upload/outlet/';
							// $configImg['max_size']      = '1024000';
							$configImg['allowed_types'] = 'jpg|jpeg|png|gif';
							$configImg['overwrite']     = FALSE;
							$configImg['remove_spaces'] = TRUE;
							// $configImg['max_width']     = 120;
                			// $configImg['max_height']    = 120;
                			$configImg['file_name']     = $file_name;
							$this->load->library('upload', $configImg);
							$this->upload->initialize($configImg);

							if(!$this->upload->do_upload('outlet_image'))
							{
						        $response['status']  = 0;
						        $response['message'] = $this->upload->display_errors();
						        $response['data']    = [];
						        echo json_encode($response);
						        return;
							}
							else
							{
								$data['outlet_image'] = $file_name;
							}

						
					    }

			    		$update_id  = array('id'=>$outlets_id);
					    $update     = $this->outlets_model->outlets_update($data, $update_id);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_outlets',
							'auto_id'    => $outlets_id,
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

			else if($method == '_deleteOutlets')
			{	
		    	$outlets_id = $this->input->post('outlets_id');

		    	if(!empty($outlets_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

		    		$where  = array('id'=>$outlets_id);
				    $update = $this->outlets_model->outlets_delete($data, $where);

				    $log_data = array(
						'u_id'       => $log_id,
						'role'       => $log_role,
						'table'      => 'tbl_outlets',
						'auto_id'    => $outlets_id,
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

			else if($method == '_newOutlets')
			{
				$error    = FALSE;
			    $errors   = array();
				$required = array('employee_id', 'outlet_category', 'store_name', 'contact_name', 'mobile', 'address', 'state_id', 'city_id', 'zone_id', 'latitude', 'longitude', 'gst_no');
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

			    if($this->input->post('email'))
			    {
				    if (mb_strlen($this->input->post('email')) > 254 || !filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL))
				    {
				        $response['status']  = 0;
				        $response['message'] = "E-mail address does not appear to be valid"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
			    }

			    if($this->input->post('gst_no'))
			    {
				    if(gst_validation($this->input->post('gst_no')) == 0)
				    {
				        $response['status']  = 0;
				        $response['message'] = "GSTIN does not appear to be valid"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
			    }

			    if(count($errors)==0)
			    {
			    	$employee_id     = $this->input->post('employee_id');
			    	$outlet_category = $this->input->post('outlet_category');
			    	$store_name      = $this->input->post('store_name');
					$contact_name    = $this->input->post('contact_name');
					$mobile          = $this->input->post('mobile');
					$email           = $this->input->post('email');
					$address         = $this->input->post('address');
					$state_id        = $this->input->post('state_id');
					$city_id         = $this->input->post('city_id');
					$zone_id         = $this->input->post('zone_id');
					$latitude        = $this->input->post('latitude');
					$longitude       = $this->input->post('longitude');
					$gst_no          = $this->input->post('gst_no');

			    	$where = array(
			    		'short_code'=> urlSlug($store_name),
						'mobile'    => $mobile,
						'status'    => '1',
						'published' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->outlets_model->getOutlets($where, '', '', 'result', '', '', '', '', $column);

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
							'outlet_category' => $outlet_category,
							'company_name'    => ucfirst($store_name),
							'short_code'      => urlSlug($store_name),
							'contact_name'    => ucfirst($contact_name),
							'mobile'          => $mobile,
							'email'           => $email,
							'address'         => $address,
							'state_id'        => $state_id,
							'city_id'         => $city_id,
							'zone_id'         => $zone_id,
							'latitude'        => $latitude,
							'longitude'       => $longitude,
							'gst_type'        => 2,
							'gst_no'          => $gst_no,
							'credit_limit'    => 5000,
							'available_limit' => 5000,
							'current_balance' => 0,
							'employee_id'     => $employee_id,
							'date'            => date('Y-m-d'),
					    	'createdate'      => date('Y-m-d H:i:s'),
					    );

					    if(!empty($_FILES['outlet_image']['name']))
					    {
					    	$img_name  = $_FILES['outlet_image']['name'];
							$img_val   = explode('.', $img_name);
							$img_res   = end($img_val);
							$file_name = generateRandomString(13).'.'.$img_res;

						    $configImg['upload_path']   = 'upload/outlet/';
							$configImg['max_size']      = '1024000';
							$configImg['allowed_types'] = 'jpg|jpeg|png|gif';
							$configImg['overwrite']     = FALSE;
							$configImg['remove_spaces'] = TRUE;
                			$configImg['file_name']     = $file_name;
							$this->load->library('upload', $configImg);
							$this->upload->initialize($configImg);

							if(!$this->upload->do_upload('outlet_image'))
							{
						        $response['status']  = 0;
						        $response['message'] = $this->upload->display_errors();
						        $response['data']    = [];
						        echo json_encode($response);
						        return;
							}
							else
							{
								$data['outlet_image'] = $file_name;
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

					    $insert=$this->outlets_model->outlets_insert($data);
					    
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

			else if($method == '_zoneWiseOutlets')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('state_id', 'city_id', 'zone_id');
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
			    	$state_id = $this->input->post('state_id');
			    	$city_id  = $this->input->post('city_id');
			    	$zone_id  = $this->input->post('zone_id');

			    	$where = array(
			    		'state_id'  => $state_id, 
			    		'city_id'   => $city_id, 
		    			'zone_id'   => $zone_id, 
		    			'status'    => '1', 
		    			'published' => '1'
		    		);

					$data_list = $this->outlets_model->getOutlets($where);

					if($data_list)
					{
						$outlets_list = [];

						foreach ($data_list as $key => $value) {

							$outlets_id    = isset($value->id)?$value->id:'';
				            $company_name  = isset($value->company_name)?$value->company_name:'';
							$contact_name  = isset($value->contact_name)?$value->contact_name:'';
							$mobile        = isset($value->mobile)?$value->mobile:'';
							$email         = isset($value->email)?$value->email:'';
							$gst_no        = isset($value->gst_no)?$value->gst_no:'';
							$pan_no        = isset($value->pan_no)?$value->pan_no:'';
							$credit_limit  = isset($value->credit_limit)?$value->credit_limit:'';
							$available_lmt = isset($value->available_limit)?$value->available_limit:'';
							$pre_limit     = isset($value->pre_limit)?$value->pre_limit:'';
							$address       = isset($value->address)?$value->address:'';
							$published     = isset($value->published)?$value->published:'';
							$status        = isset($value->status)?$value->status:'';
							$createdate    = isset($value->createdate)?$value->createdate:'';

				            $outlets_list[] = array(
	          					'outlets_id'      => $outlets_id,
								'company_name'    => $company_name,
								'contact_name'    => $contact_name,
								'mobile'          => $mobile,
								'email'           => $email,
								'gst_no'          => $gst_no,
								'pan_no'          => $pan_no,
								'credit_limit'    => $credit_limit,
								'available_limit' => $available_lmt,
								'pre_limit'       => $pre_limit,
								'address'         => $address,
								'published'       => $published,
								'status'          => $status,
								'createdate'      => $createdate,
	          				);
						}

						$response['status']       = 1;
				        $response['message']      = "Success"; 
				        $response['data']         = $outlets_list;
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
			}

			else if($method == '_todayOutlets')
			{
				$limit    = $this->input->post('limit');
	    		$offset   = $this->input->post('offset');
	    		$cur_date = date('Y-m-d');

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

				$where  = array('published'=>'1', 'date' => $cur_date);

	    		if($search !='')
	    		{
	    			$like['name'] = $search;
	    		}
	    		else
	    		{
	    			$like = [];
	    		}

	    		$column = 'id';
				$overalldatas = $this->outlets_model->getOutlets($where, '', '', 'result', $like, '', '', '', $column);

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

				$column    = 'id, company_name, contact_name, mobile, email, gst_no, pan_no, published, status, createdate';
				$data_list = $this->outlets_model->getOutlets($where, $limit, $offset, 'result', $like, '', $option, '', $column);

				if($data_list)
				{
					$outlets_list = [];
					foreach ($data_list as $key => $value) {

						$outlets_id   = isset($value->id)?$value->id:'';
			            $company_name = isset($value->company_name)?$value->company_name:'';
						$contact_name = isset($value->contact_name)?$value->contact_name:'';
						$mobile       = isset($value->mobile)?$value->mobile:'';
						$email        = isset($value->email)?$value->email:'';
						$gst_no       = isset($value->gst_no)?$value->gst_no:'';
						$pan_no       = isset($value->pan_no)?$value->pan_no:'';
						$published    = isset($value->published)?$value->published:'';
						$status       = isset($value->status)?$value->status:'';
						$createdate   = isset($value->createdate)?$value->createdate:'';

			            $outlets_list[] = array(
          					'outlets_id'   => $outlets_id,
							'company_name' => $company_name,
							'contact_name' => $contact_name,
							'mobile'       => $mobile,
							'email'        => $email,
							'gst_no'       => $gst_no,
							'pan_no'       => $pan_no,
							'published'    => $published,
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
			        $response['data']         = $outlets_list;
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
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// Outlet Payment
		// ***************************************************
		public function outletPayment($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listOutletPayment')
			{
				$outlet_id = $this->input->post('outlet_id');

				if(!empty($outlet_id))
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
		    		if($search !='')
		    		{
		    			$like['name'] = $search;

		    			$where = array(
		    				'outlet_id'  => $outlet_id,
		    				'value_type' => '2',
		    				'published'  => '1',
		    			);
		    		}
		    		else
		    		{
		    			$like = [];

		    			$where = array(
		    				'outlet_id'  => $outlet_id,
		    				'value_type' => '2',
		    				'published'  => '1',
		    			);
		    		}

		    		$column = 'id';
					$overalldatas = $this->payment_model->getOutletPayment($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->payment_model->getOutletPayment($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$payment_list = [];
						foreach ($data_list as $key => $value) {
							$payment_id     = !empty($value->id)?$value->id:'';
							$assign_id      = !empty($value->assign_id)?$value->assign_id:'';
							$employee_id    = !empty($value->employee_id)?$value->employee_id:'';
							$distributor_id = !empty($value->distributor_id)?$value->distributor_id:'';
							$outlet_id      = !empty($value->outlet_id)?$value->outlet_id:'';
							$pre_bal        = !empty($value->pre_bal)?$value->pre_bal:'';
							$cur_bal        = !empty($value->cur_bal)?$value->cur_bal:'';
							$amount         = !empty($value->amount)?$value->amount:'';
							$discount       = !empty($value->discount)?$value->discount:'';
							$pay_type       = !empty($value->pay_type)?$value->pay_type:'';
							$description    = !empty($value->description)?$value->description:'';
							$amt_type       = !empty($value->amt_type)?$value->amt_type:'';
							$date           = !empty($value->date)?$value->date:'';
							$time           = !empty($value->time)?$value->time:'';

							if(!empty($employee_id))
							{
								// Employee Details
								$emp_whr  = array(
									'id'        => $employee_id,
									'status'    => '1',
			    					'published' => '1',
								);

								$emp_col  = 'first_name,last_name';

								$emp_data = $this->employee_model->getEmployee($emp_whr, '', '', 'result', '', '', '', '', $emp_col);

								$first_name = !empty($emp_data[0]->first_name)?$emp_data[0]->first_name:'';
								$last_name = !empty($emp_data[0]->last_name)?$emp_data[0]->last_name:'';
								
								$arr = array($first_name,$last_name);
								$emp_name =join(" ",$arr);
							}
							else
							{
								$emp_name = 'Admin';
							}

							// Outlet Details
							$outlet_whr  = array(
								'id'        => $outlet_id,
								'status'    => '1',
		    					'published' => '1',
							);

							$outlet_col  = 'company_name';

							$outlet_data = $this->outlets_model->getOutlets($outlet_whr, '', '', 'result', '', '', '', '', $outlet_col);

							$outlet_name = !empty($outlet_data[0]->company_name)?$outlet_data[0]->company_name:'';

							// Distributor Details
							$distributor_whr  = array(
								'id'        => $distributor_id,
								'status'    => '1',
		    					'published' => '1',
							);

							$distributor_col  = 'company_name';

							$dist_data = $this->distributors_model->getDistributors($distributor_whr, '', '', 'result', '', '', '', '', $distributor_col);

							$dist_name = !empty($dist_data[0]->company_name)?$dist_data[0]->company_name:'';

							// Payment typ details
							if($pay_type == 1)
							{
								$payment_type = 'Debit';
							}
							else
							{
								$payment_type = 'Credit';
							}

							// Amount Type
							if($amt_type == 1)
							{
								$amount_type = 'Cash';
							}
							else if($amt_type == 2)
							{
								$amount_type = 'Cheque';
							}
							else if($amt_type == 3)
							{
								$amount_type = 'Others';
							}
							else
							{
								$amount_type = '---';	
							}

							$payment_list[] = array(
								'payment_id'       => $payment_id,
								'assign_id'        => $assign_id,
								'employee_id'      => $employee_id,
								'employee_name'    => $emp_name,
								'distributor_id'   => $distributor_id,
								'distributor_name' => $dist_name,
								'outlet_id'        => $outlet_id,
								'outlet_name'      => $outlet_name,
								'pre_bal'          => $pre_bal,
								'cur_bal'          => $cur_bal,
								'amount'           => $amount,
								'discount'         => $discount,
								'pay_type'         => $pay_type,
								'payment_type'     => $payment_type,
								'description'      => $description,
								'amt_type'         => $amt_type,
								'amount_type'      => $amount_type,
								'date'             => date('d-m-Y', strtotime($date)),
								'time'             => date('h:i:s', strtotime($time)),
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
				        $response['data']         = $payment_list;
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

			else if($method == '_outletPaymentList')
			{
				$limit       = $this->input->post('limit');
	    		$offset      = $this->input->post('offset');
	    		$search      = $this->input->post('search');
	    		$employee_id = $this->input->post('employee_id');
	    		$cur_date    = date('Y-m-d');

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

				if($search !='')
	    		{
	    			$like['str_name'] = $search;
	    		}
	    		else
	    		{
	    			$like = [];
	    		}

				$cur_date  = date('Y-m-d');
				$find_date = date('Y-m-d', strtotime('-15 day', strtotime($cur_date)));

				$whr_1 = array(
					'A.date <='    => $find_date,
					'A.bal_amt !=' => '0',
					'A.status'     => '1',
					'A.published'  => '1',
				);

				$whr_in = '';
				if($employee_id != '')
				{
					$whr_3 = array(
						'employee_id' => $employee_id,
						'assign_date' => $cur_date,
						'published'   => '1',
						'status'      => '1',
					);

					$col_3 = 'assign_store';
					$res_1 = $this->assignshop_model->getAssignshopDetails($whr_3, '', '', 'row', '', '', '', '', $col_3);

					if($res_1)
					{
						$assign_store = empty_check($res_1->assign_store);

						$whr_in = $assign_store;
					}
				}

				$col_1 = 'A.id';
				$overalldatas = $this->payment_model->getOutletPaymentJoinDetails($whr_1, '', '', 'result', $like, '', '', '', $col_1, $whr_in);

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

				$col_2 = 'A.id, A.distributor_id, A.outlet_id, A.bill_no, A.amount, A.bal_amt, A.date, B.company_name, B.dis_code, C.company_name AS store_name, C.sales_type';
				$data_res = $this->payment_model->getOutletPaymentJoinDetails($whr_1, $limit, $offset, 'result', $like, '', $option, '', $col_2, $whr_in);

				// echo $this->db->last_query(); exit;

				if($data_res)
				{
					$data_list = [];
					foreach ($data_res as $key => $val) {

						$data_list[] = array(
							'auto_id'        => empty_check($val->id),
						    'distributor_id' => empty_check($val->distributor_id),
						    'outlet_id'      => empty_check($val->outlet_id),
						    'bill_no'        => empty_check($val->bill_no),
						    'amount'         => empty_check($val->amount),
						    'bal_amt'        => empty_check($val->bal_amt),
						    'date'           => date_check($val->date),
						    'dis_code'       => empty_check($val->dis_code),
						    'company_name'   => empty_check($val->company_name),
						    'store_name'     => empty_check($val->store_name),
						    'sales_type'     => empty_check($val->sales_type),
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
			        $response['data']         = $data_list;
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

			else if($method == '_outletSalesBlock')
			{
				$outlet_id    = $this->input->post('outlet_id');
				$sales_status = $this->input->post('sales_status');

				$error    = FALSE;
			    $errors   = array();
				$required = array('outlet_id', 'sales_status');
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
			    	$upt_val = array(
			    		'sales_type' => $sales_status,
			    		'updatedate' => date('Y-m-d H:i:s')
			    	);

			    	$upt_whr = array('id' => $outlet_id);
			    	$upt_res = $this->outlets_model->outlets_update($upt_val, $upt_whr);

				    if($upt_res)
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

		// Distributor Outlet List
		// ***************************************************
		public function distributor_outlet_list($param1="",$param2="",$param3="")
		{
			$method  = $this->input->post('method');

			if($method == '_listDistributorOutletsPaginate')
			{
				$distributor_id  = $this->input->post('distributor_id');

				if(!empty($distributor_id))
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
		    		if($search !='')
		    		{
		    			$like['name'] = $search;
		    			$where = array(
		    				'distributor_id' => $distributor_id,
		    				'published'      => '1'
		    			);
		    		}
		    		else
		    		{
		    			$like = [];
		    			$where = array(
		    				'distributor_id' => $distributor_id,
		    				'published'      => '1'
		    			);
		    		}

		    		$column = 'id';
					$overalldatas = $this->outlets_model->getDistributorOutlets($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->outlets_model->getDistributorOutlets($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$outlets_list = [];
						foreach ($data_list as $key => $value) {

							$assign_id      = !empty($value->id)?$value->id:'';
						    $distributor_id = !empty($value->distributor_id)?$value->distributor_id:'';
						    $outlet_id      = !empty($value->outlet_id)?$value->outlet_id:'';
						    $outlet_name    = !empty($value->outlet_name)?$value->outlet_name:'';
						    $pre_bal        = !empty($value->pre_bal)?$value->pre_bal:'';
						    $cur_bal        = !empty($value->cur_bal)?$value->cur_bal:'';
						    $status         = !empty($value->status)?$value->status:'';

						    $outlet_whr  = array(
						    	'id'        => $outlet_id,
						    	'published' => '1'
						    );

						    $outlet_col  = 'mobile';

							$outlet_data = $this->outlets_model->getOutlets($outlet_whr, '', '', 'result', '', '', '', '', $outlet_col);

							$mobile_no   = !empty($outlet_data[0]->mobile)?$outlet_data[0]->mobile:'';

							$outlets_list[] = array(
								'assign_id'      => $assign_id,
								'distributor_id' => $distributor_id,
								'outlet_id'      => $outlet_id,
								'outlet_name'    => $outlet_name,
								'mobile'         => $mobile_no,
								'pre_bal'        => $pre_bal,
								'cur_bal'        => $cur_bal,
								'status'         => $status,
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
				        $response['data']         = $outlets_list;
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

			else if($method == '_listDistributorOutlets')
			{
				$distributor_id  = $this->input->post('distributor_id');

				if(!empty($distributor_id))
				{
					$where = array(
	    				'distributor_id' => $distributor_id,
	    				'published'      => '1'
	    			);

	    			$column = 'id, distributor_id, outlet_id, outlet_name, status';
					$data   = $this->outlets_model->getDistributorOutlets($where, '', '', 'result', '', '', '', '', $column);

					if($data)
					{
						$outlets_list = [];
						foreach ($data as $key => $val) {
							$assign_id      = !empty($val->id)?$val->id:'';
						    $distributor_id = !empty($val->distributor_id)?$val->distributor_id:'';
						    $outlet_id      = !empty($val->outlet_id)?$val->outlet_id:'';
						    $outlet_name    = !empty($val->outlet_name)?$val->outlet_name:'';
						    $status         = !empty($val->status)?$val->status:'';

						    $outlets_list[] = array(
								'assign_id'      => $assign_id,
								'distributor_id' => $distributor_id,
								'outlet_id'      => $outlet_id,
								'outlet_name'    => $outlet_name,
								'status'         => $status,
							);
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $outlets_list;
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
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
				}
			}

			else if($method == '_detailsDistributorOutlets')
			{
				$assign_id  = $this->input->post('assign_id');

				if(!empty($assign_id))
				{
					$outlet_whr  = array(
						'id'        => $assign_id,
						'status'    => '1',
						'published' => '1'
					);

					$outlet_data = $this->outlets_model->getDistributorOutlets($outlet_whr);

					if(!empty($outlet_data))
					{	
						$outlet_detail = [];

						foreach ($outlet_data as $key => $value) {

							$assign_id      = !empty($value->id)?$value->id:'';
				            $distributor_id = !empty($value->distributor_id)?$value->distributor_id:'';
				            $outlet_id      = !empty($value->outlet_id)?$value->outlet_id:'';
				            $outlet_name    = !empty($value->outlet_name)?$value->outlet_name:'';
				            $pre_bal        = !empty($value->pre_bal)?$value->pre_bal:'';
				            $cur_bal        = !empty($value->cur_bal)?$value->cur_bal:'';

				            $outlet_detail = array(
				            	'assign_id'      => $assign_id,
				            	'distributor_id' => $distributor_id,
				            	'outlet_id'      => $outlet_id,
				            	'outlet_name'    => $outlet_name,
				            	'pre_bal'        => $pre_bal,
				            	'cur_bal'        => $cur_bal,
				            );
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $outlet_detail;
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

			else if($method == '_deleteDistributorOutlets')
			{
				$assign_id  = $this->input->post('assign_id');

				if(!empty($assign_id))
				{
					$outlet_whr  = array(
						'id'        => $assign_id,
						'status'    => '1',
						'published' => '1'
					);

					$outlet_data = $this->outlets_model->getDistributorOutlets($outlet_whr);

					$cur_bal     = !empty($outlet_data[0]->cur_bal)?$outlet_data[0]->cur_bal:'0';

					if($cur_bal == 0)
					{
						$data=array(
					    	'published' => '0',
					    );

			    		$where  = array('id' => $assign_id);
					    $update = $this->outlets_model->distributorOutlets_delete($data, $where);
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
				        $response['message'] = "Please close old balance amount"; 
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

		// Outlet Loyalty
		// ***************************************************
		public function outlet_loyalty($param1="",$param2="",$param3="")
		{
			$method   = $this->input->post('method');
			$log_id   = $this->input->post('log_id');
			$log_role = $this->input->post('log_role');

			if($method == '_outletLoyalty')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('outlets_id', 'loyalty_result');
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
			    	$outlets_id     = $this->input->post('outlets_id');
			    	$loyalty_result = $this->input->post('loyalty_result');

			    	$loyalty_data = json_decode($loyalty_result);

			    	foreach ($loyalty_data as $key => $val) {

			    		$invoice_count  = !empty($val->invoice_count)?$val->invoice_count:'0';
			            $discount_value = !empty($val->discount_value)?$val->discount_value:'0';
			            $loyalty_id     = !empty($val->loyalty_id)?$val->loyalty_id:'0';

			            if($loyalty_id == 0)
			            {
			            	$ins_data = array(
			            		'outlet_id'  => $outlets_id,
			            		'inv_count'  => $invoice_count,
			            		'dis_value'  => $discount_value,
			            		'date'       => date('Y-m-d'),
			            		'createdate' => date('Y-m-d H:i:s'),
			            	);

			            	$loyalty_ins = $this->loyalty_model->outletLoyalty_insert($ins_data);

			            	$log_data = array(
								'u_id'       => $log_id,
								'role'       => $log_role,
								'table'      => 'tbl_order_loyalty',
								'auto_id'    => $loyalty_ins,
								'action'     => 'create',
								'date'       => date('Y-m-d'),
								'time'       => date('H:i:s'),
								'createdate' => date('Y-m-d H:i:s')
							);

							$log_val = $this->commom_model->log_insert($log_data);
			            }
			            else
			            {
			            	$upt_data = array(
			            		'outlet_id'  => $outlets_id,
			            		'inv_count'  => $invoice_count,
			            		'dis_value'  => $discount_value,
			            		'date'       => date('Y-m-d'),
			            		'updatedate' => date('Y-m-d H:i:s'),
			            	);

			            	$upt_id      = array('id' => $loyalty_id);
					    	$loyalty_upt = $this->loyalty_model->outletLoyalty_update($upt_data, $upt_id);

					    	$log_data = array(
								'u_id'       => $log_id,
								'role'       => $log_role,
								'table'      => 'tbl_order_loyalty',
								'auto_id'    => $loyalty_id,
								'action'     => 'update',
								'date'       => date('Y-m-d'),
								'time'       => date('H:i:s'),
								'createdate' => date('Y-m-d H:i:s')
							);

							$log_val = $this->commom_model->log_insert($log_data);
			            }
			    	}

			    	$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
			    }
			}

			else if($method == '_outletLoyaltyList')
			{
				$outlet_id = $this->input->post('outlet_id');

				$whr_1 = array('outlet_id' => $outlet_id, 'published' => '1', 'status'    => '1');
				$col_1 = 'id, inv_count, dis_value';
				$val_1 = $this->loyalty_model->getOutletLoyalty($whr_1, '', '', 'result', '', '', '', '', $col_1);

				if($val_1)
				{
					$data_list = [];
					foreach ($val_1 as $key => $res_1) {
						$auto_id   = !empty($res_1->id)?$res_1->id:'';
			            $inv_count = !empty($res_1->inv_count)?$res_1->inv_count:'';
			            $dis_value = !empty($res_1->dis_value)?$res_1->dis_value:'';

			            $data_list[] = array(
			            	'auto_id'   => $auto_id,
				            'inv_count' => $inv_count,
				            'dis_value' => $dis_value,
			            );
					}

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $data_list;
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
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}
	}
?>
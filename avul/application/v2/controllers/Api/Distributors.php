<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class Distributors extends CI_Controller {

		public function __construct()
		{
			parent::__construct();

			$this->load->model('commom_model');
			$this->load->model('distributors_model');
			$this->load->model('vendors_model');
			$this->load->model('user_model');
			$this->load->model('assignproduct_model');
			$this->load->model('managers_model');
		}

		public function index()
		{
			echo "Test";
		}

		// distributors
		// ***************************************************
		public function distributors($param1="",$param2="",$param3="")
		{
			$ref_by      = $this->input->post('ref_by');
			$einv_status = $this->input->post('einv_status');
			$msme_status = $this->input->post('msme_status');
			$method      = $this->input->post('method');

			// Create Distributors
			if($method == '_addDistributors')
			{
				$error = FALSE;
			    $errors = array();
				if($ref_by==0){
					$required = array('dis_code', 'company_name', 'mobile', 'email', 'distributor_grade', 'gst_no', 'address', 'pincode', 'credit_limit', 'distributor_status', 'state_id', 'password', 'distributor_type', 'einv_status', 'msme_status');
				}else{
					$required = array('company_name', 'mobile', 'email', 'state_id', 'city_id', 'address', 'pincode', 'credit_limit', 'gst_no', 'einv_status', 'msme_status');
				}

				if($einv_status == 1)
				{
					array_push($required, 'access_token');
				}

				if($msme_status == 1)
				{
					array_push($required, 'msme_number');
				}

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
			        $response['message'] = "Please fill required fields"; 
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

			    if(count($errors)==0)
			    {

			    	$dis_code           = $this->input->post('dis_code');
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
					$password           = $this->input->post('password');
					$address            = $this->input->post('address');
					$pincode            = $this->input->post('pincode');
					$state_id           = $this->input->post('state_id');
					$distributor_status = $this->input->post('distributor_status');
					$city_id            = $this->input->post('city_id');
					$category_id        = $this->input->post('category_id');
					$sub_cat_id         = $this->input->post('sub_cat_id');
					$type_id            = $this->input->post('type_id');
					$dis_type           = $this->input->post('distributor_type');
					$ref_id             = $this->input->post('ref_id');
					$ref_by             = $this->input->post('ref_by');
					$dis_grade          = $this->input->post('distributor_grade');
					$mg_role            = $this->input->post('mg_role');
					$access_token       = $this->input->post('access_token');
					$msme_number        = $this->input->post('msme_number');

					if($mg_role =='RSM'){
						$mg_status  = 3;
					}else if($mg_role == 'ASM'){
						$mg_status  = 4;
					}else{
						$mg_status  = 5;
					}

			    	$where_1=array(
						'email'     => $email,
				    	'status'    => '1',
				    	'published' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column);

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
						$where_2 = array(
							'email'     => $email,
					    	'status'    => '1',
					    	'published' => '1',
					    );

						$find_datas = $this->user_model->getUser($where_2, '', '', 'result', '', '', '', '', $column);

						if(!empty($find_datas))
						{
							$response['status']  = 0;
					        $response['message'] = "Data Already Exist"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
						}
						else
						{
							$where_3 = array(
								'email'     => $email,
						    	'status'    => '1',
						    	'published' => '1',
						    );

							$find_datas = $this->vendors_model->getVendors($where_3, '', '', 'result', '', '', '', '', $column);

							if(!empty($find_datas))
							{
								$response['status']  = 0;
						        $response['message'] = "Data Already Exist"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
							}
							else
							{
								if($ref_by == 0){
									// $category_val = implode(',', $category_id);
									// $sub_cat_val = implode(',', $sub_cat_id);
									// $type_val = implode(',', $type_id);
									
									$data = array(
										'dis_code'           => strtoupper($dis_code),
										'company_name'       => ucfirst($company_name),
										'contact_name'       => ucfirst($contact_name),
										'mobile'             => $mobile,
										'email'              => $email,
										'gst_no'             => $gst_no,
										'pan_no'             => $pan_no,
										'tan_no'             => $tan_no,
										'bill_no'            => $bill_no,
										'credit_limit'       => $credit_limit,
										'available_limit'    => $credit_limit,
										'pre_limit'          => '0',
										'discount'           => $discount,
										'due_days'           => $due_days,
										'account_name'       => $account_name,
										'account_no'         => $account_no,
										'account_type'       => $account_type,
										'ifsc_code'          => $ifsc_code,
										'bank_name'          => $bank_name,
										'branch_name'        => $branch_name,
										'password'           => $password,
										'pincode'            => $pincode,
										'city_id'            => $city_id,
										'state_id'           => $state_id,
										'distributor_status' => $distributor_status,
										// 'category_id'        => $category_val,
										// 'sub_cat_id'         => $sub_cat_val,
										// 'type_id'            => $type_val,
										'distributor_type'   => $dis_type,
										'einv_status'        => $einv_status,
										'access_token'       => $access_token,
										'msme_status'        => $msme_status,
										'msme_number'        => $msme_number,
										'address'            => $address,
										'stock_status'       => 2,
										'createdate'         => date('Y-m-d H:i:s'),
										'grade'              => $dis_grade,
										'ref_by'             => 0,
										'ref_id'             => ($ref_id)?$ref_id:0,
										
									);
								}else{
									
									$data = array(
										
										'company_name'       => ucfirst($company_name),
										'contact_name'       => ucfirst($contact_name),
										'mobile'             => $mobile,
										'email'              => $email,
										'gst_no'             => $gst_no,
										'pan_no'             => $pan_no,
										'tan_no'             => $tan_no,
										'bill_no'            => $bill_no,
										'credit_limit'       => $credit_limit,
										'available_limit'    => $credit_limit,
										'pre_limit'          => '0',
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
										'city_id'            => $city_id,
										'status'             => $mg_status,
										'ref_by'             => $ref_by,
										'ref_id'             => $ref_id,
										'einv_status'        => $einv_status,
										'access_token'       => $access_token,
										'msme_status'        => $msme_status,
										'msme_number'        => $msme_number,
									);
								}

								if(!empty($_FILES['gst_image']['name']))
								{
									$img_name  = $_FILES['gst_image']['name'];
									$img_val   = explode('.', $img_name);
									$img_res   = $img_val[1];
									$file_name = generateRandomString(13).'.'.$img_res;
		
									$configImg['upload_path']   ='upload/distributor/gst/';
									// $configImg['max_size']      = '1024000';
									$configImg['allowed_types'] = 'jpg|jpeg|png';
									$configImg['overwrite']     = FALSE;
									$configImg['remove_spaces'] = TRUE;
									// $configImg['max_width']     = 120;
									// $configImg['max_height']    = 120;
									$configImg['file_name']     = $file_name;
									$this->load->library('upload', $configImg);
									$this->upload->initialize($configImg);
		
									if(!$this->upload->do_upload('gst_image'))
									{
										$response['status']  = 0;
										$response['message'] = $this->upload->display_errors();
										$response['data']    = [];
										echo json_encode($response);
										return;
									}
									else
									{
										$data['gst_image'] = $file_name;
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

								if(!empty($_FILES['cheque_image']['name']))
								{
									$img_name  = $_FILES['cheque_image']['name'];
									$img_val   = explode('.', $img_name);
									$img_res   = $img_val[1];
									$file_name = generateRandomString(13).'.'.$img_res;
		
									$configImg['upload_path']   ='upload/distributor/cheque/';
									// $configImg['max_size']      = '1024000';
									$configImg['allowed_types'] = 'jpg|jpeg|png';
									$configImg['overwrite']     = FALSE;
									$configImg['remove_spaces'] = TRUE;
									// $configImg['max_width']     = 120;
									// $configImg['max_height']    = 120;
									$configImg['file_name']     = $file_name;
									$this->load->library('upload', $configImg);
									$this->upload->initialize($configImg);
		
									if(!$this->upload->do_upload('cheque_image'))
									{
										$response['status']  = 0;
										$response['message'] = $this->upload->display_errors();
										$response['data']    = [];
										echo json_encode($response);
										return;
									}
									else
									{
										$data['cheque_img'] = $file_name;
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

								if(!empty($_FILES['qr_image']['name']))
								{
									$img_name  = $_FILES['qr_image']['name'];
									$img_val   = explode('.', $img_name);
									$img_res   = $img_val[1];
									$file_name = generateRandomString(13).'.'.$img_res;
		
									$configImg['upload_path']   ='upload/distributor/qr_code/';
									// $configImg['max_size']      = '1024000';
									$configImg['allowed_types'] = 'jpg|jpeg|png';
									$configImg['overwrite']     = FALSE;
									$configImg['remove_spaces'] = TRUE;
									// $configImg['max_width']     = 120;
									// $configImg['max_height']    = 120;
									$configImg['file_name']     = $file_name;
									$this->load->library('upload', $configImg);
									$this->upload->initialize($configImg);
		
									if(!$this->upload->do_upload('qr_image'))
									{
										$response['status']  = 0;
										$response['message'] = $this->upload->display_errors();
										$response['data']    = [];
										echo json_encode($response);
										return;
									}
									else
									{
										$data['qr_image'] = $file_name;
									}
								}

								if(!empty($_FILES['logo_image']['name']))
								{
									$img_name  = $_FILES['logo_image']['name'];
									$img_val   = explode('.', $img_name);
									$img_res   = $img_val[1];
									$file_name = generateRandomString(13).'.'.$img_res;
		
									$configImg['upload_path']   ='upload/distributor/logo/';
									// $configImg['max_size']      = '1024000';
									$configImg['allowed_types'] = 'jpg|jpeg|png';
									$configImg['overwrite']     = FALSE;
									$configImg['remove_spaces'] = TRUE;
									// $configImg['max_width']     = 120;
									// $configImg['max_height']    = 120;
									$configImg['file_name']     = $file_name;
									$this->load->library('upload', $configImg);
									$this->upload->initialize($configImg);
		
									if(!$this->upload->do_upload('logo_image'))
									{
										$response['status']  = 0;
										$response['message'] = $this->upload->display_errors();
										$response['data']    = [];
										echo json_encode($response);
										return;
									}
									else
									{
										$data['logo_image'] = $file_name;
									}
								}
							    
								
								$insert = $this->distributors_model->distributors_insert($data);
							
							    if($ref_by == 0){
									// $type_list = '';
									// if(!empty($type_id))
									// {
									// 	$type_value    = explode(',', $type_val);  
									// 	$type_count  = count($type_value);

									// 	for ($i=0; $i < $type_count; $i++) {

									// 		// Product Details
									// 		$where_4  = array(
									// 			'id'        => $type_value[$i],
									// 			'status'    => '1', 
									// 			'published' => '1'
									// 		);

									// 		$product_detail = $this->commom_model->getProductType($where_4);

									// 		$category_id  = !empty($product_detail[0]->category_id)?$product_detail[0]->category_id:'';
									// 		$sub_cat_id  = !empty($product_detail[0]->sub_cat_id)?$product_detail[0]->sub_cat_id:'';
									// 		$product_id   = !empty($product_detail[0]->product_id)?$product_detail[0]->product_id:'';
									// 		$description  = !empty($product_detail[0]->description)?$product_detail[0]->description:'';
									// 		$product_unit = !empty($product_detail[0]->product_unit)?$product_detail[0]->product_unit:'';

									// 		$data_2 = array(
									// 			'distributor_id' => $insert,
									// 			'sub_cat_id'     => $sub_cat_id,
									// 			'category_id'    => $category_id,
									// 			'product_id'     => $product_id,
									// 			'type_id'        => $type_value[$i],
									// 			'description'    => $description,
									// 			'product_unit'   => $product_unit,
									// 			'stock'          => '0',
									// 			'createdate'     => date('Y-m-d H:i:s'),
									// 		);

									// 		// Assign Product Details
									// 		$insert_2 = $this->assignproduct_model->assignProductDetails_insert($data_2);
									// 	}
									// }

								}

								// Insert prodyct to distributors
								$type_whr = array('published' => 1, 'status' => 1);
								$type_col = 'id, product_id, category_id, sub_cat_id, description, product_unit';
								$type_qry = $this->commom_model->getProductType($type_whr, '', '', 'result', '', '', '', '', $type_col);

								if($type_qry)
								{
									$pdt_list = array();

									foreach ($type_qry as $key => $res) {
										$type_id      = !empty($res->id)?$res->id:'';
										$product_id   = !empty($res->product_id)?$res->product_id:'';
							            $category_id  = !empty($res->category_id)?$res->category_id:'';
							            $sub_cat_id   = !empty($res->sub_cat_id)?$res->sub_cat_id:'';
							            $description  = !empty($res->description)?$res->description:'';
							            $product_unit = !empty($res->product_unit)?$res->product_unit:'';

							            $pdt_list[] = array(
							            	'ref_id'         => $ref_by,
							            	'distributor_id' => $insert,
							            	'category_id'    => $category_id,
							            	'sub_cat_id'     => $sub_cat_id,
							            	'product_id'     => $product_id,
							            	'type_id'        => $type_id,
							            	'description'    => $description,
							            	'product_unit'   => $product_unit,
							            	'stock'          => 0,
							            	'view_stock'     => 0,
							            );
									}

									$pdt_ins = $this->assignproduct_model->assignProductDetails_insert_batch($pdt_list);
								}

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
			    }
			}

			// List Distributors Pagination
			else if($method == '_listDistributorsPaginate')
			{
				$limit  = $this->input->post('limit');
	    		$offset = $this->input->post('offset');
				$ref_id = $this->input->post('ref_id');
				$sort_column = $this->input->post('sort_column');
				$sort_type   = $this->input->post('sort_type');
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

				if(!empty($ref_id))
				{
					$ref_value = $ref_id;
				}
				else
				{
					$ref_value = 0;
				}

				$search = $this->input->post('search');
				$array =array(1,2);
	    		if($search !='')
	    		{
	    			$like['name'] = $search;
	    			$where = array(
	    				'distributor_type' => '1',
	    				'published'        => '1',
						'ref_id'           => $ref_id
	    			);
					$wher_in['status'] = $array;
	    		}
	    		else
	    		{
	    			$like = [];
	    			$where = array(
	    				'distributor_type' => '1',
	    				'published'        => '1',
						'ref_id'           => $ref_id
					);
					$wher_in['status'] = $array;
	    		}
				$sort_col_ = array('id', 'company_name', 'mobile', 'email','status');
				array_unshift($sort_col_,"");
				unset($sort_col_[0]);
	
				$option['order_by']   = !empty($sort_column) ? $sort_col_[$sort_column] : 'id';
				$option['disp_order'] = !empty($sort_type) ? ($sort_type==1? 'DESC' : 'ASC') : 'DESC';
	    		$column = 'id';
				$overalldatas = $this->distributors_model->getDistributors($where, '', '', 'result', $like, '', '', '', $column,$wher_in);

				if($overalldatas)
				{
					$totalc = count($overalldatas);
				}
				else
				{
					$totalc = 0;
				}

				

				$data_list = $this->distributors_model->getDistributors($where, $limit, $offset, 'result', $like, '', $option,'','',$wher_in);

				if($data_list)
				{
					$distributor_list = [];
					foreach ($data_list as $key => $value) {

						$distributor_id = isset($value->id)?$value->id:'';
			            $company_name   = isset($value->company_name)?$value->company_name:'';
			            $gst_no         = isset($value->gst_no)?$value->gst_no:'';
			            $mobile         = isset($value->mobile)?$value->mobile:'';
			            $current_bal    = isset($value->current_balance)?$value->current_balance:'';
			            $available_lmt  = isset($value->available_limit)?$value->available_limit:'';
			            $email          = isset($value->email)?$value->email:'';
			            $address        = isset($value->address)?$value->address:'';
			            $published      = isset($value->published)?$value->published:'';
			            $status         = isset($value->status)?$value->status:'';
			            $createdate     = isset($value->createdate)?$value->createdate:'';

			            $distributor_list[] = array(
          					'distributor_id'  => $distributor_id,
				            'company_name'    => $company_name,
				            'gst_no'          => $gst_no,
				            'mobile'          => $mobile,
				            'current_balance' => $current_bal,
				            'available_limit' => $available_lmt,
				            'email'           => $email,
				            'address'         => $address,
				            'published'       => $published,
				            'status'          => $status,
				            'createdate'      => $createdate,
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
			        $response['data']         = $distributor_list;
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
			// List Distributors Pagination for managers
			else if($method == '_listDistributorsPaginate_mg')
			{
				$limit  = $this->input->post('limit');
	    		$offset = $this->input->post('offset');
				$mg_id = $this->input->post('mg_id');
				$sort_column = $this->input->post('sort_column');
				$sort_type   = $this->input->post('sort_type');

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
				if(!empty($mg_id)){
					$where_mg = array(
						
						'employee_id' => $mg_id,
					);
		
		
					$mg_val = $this->managers_model->getManagers($where_mg);
					$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id)?$mg_val[0]->ctrl_zone_id:'0';
					$ctrl_state = !empty($mg_val[0]->ctrl_state_id)?$mg_val[0]->ctrl_state_id:'0';
					$ctrl_city = !empty($mg_val[0]->ctrl_city_id)?$mg_val[0]->ctrl_city_id:'0';
					$designation_code = !empty($mg_val[0]->designation_code)?$mg_val[0]->designation_code:'0';
					if($designation_code=='ASM'){
						
						$city_id_finall = substr($ctrl_city, 1, -1);
	
	
							$d_city = !empty($city_id_finall) ? $city_id_finall : '';
	
							$d_city_val = explode(',', $d_city);
					}else if($designation_code=='RSM'){
						$state_id_finall = substr($ctrl_state, 1, -1);
	
	
							$d_state = !empty($state_id_finall) ? $state_id_finall : '';
	
							$d_state_val = explode(',', $d_state);
					}
					
					
					
	
					if(!empty($designation_code == 'RSM'))
					{ 
						$array =array(3,4,6);
						$where = array(
							'distributor_type' => '1',
							'published'        => '1',
							
						);
						$where_in['state_id'] = $d_state_val;
						$where_in['status'] = $array;
						
					}
					else if($designation_code == 'ASM')
					{
						
						$array =array(5,4,6,3);
						$where = array(
							'distributor_type' => '1',
							'published'        => '1',
					
						);
						$where_in['city_id'] = $d_city_val;
						$where_in['status'] = $array;
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
					$sort_col_ = array('id', 'company_name', 'mobile', 'email','status');
					array_unshift($sort_col_,"");
					unset($sort_col_[0]);
		
					$option['order_by']   = !empty($sort_column) ? $sort_col_[$sort_column] : 'id';
					$option['disp_order'] = !empty($sort_type) ? ($sort_type==1? 'DESC' : 'ASC') : 'DESC';
					$column = 'id';
					$overalldatas = $this->distributors_model->getDistributors($where, '', '', 'result', $like, '', '', '', $column,$where_in);
	
					if($overalldatas)
					{
						$totalc = count($overalldatas);
					}
					else
					{
						$totalc = 0;
					}
	
					
	
					$data_list = $this->distributors_model->getDistributors($where, $limit, $offset, 'result', $like, '', $option,'','',$where_in);
					
					if($data_list)
					{
						$distributor_list = [];
						foreach ($data_list as $key => $value) {
	
							$distributor_id = isset($value->id)?$value->id:'';
							$company_name   = isset($value->company_name)?$value->company_name:'';
							$gst_no         = isset($value->gst_no)?$value->gst_no:'';
							$mobile         = isset($value->mobile)?$value->mobile:'';
							$current_bal    = isset($value->current_balance)?$value->current_balance:'';
							$available_lmt  = isset($value->available_limit)?$value->available_limit:'';
							$email          = isset($value->email)?$value->email:'';
							$address        = isset($value->address)?$value->address:'';
							$published      = isset($value->published)?$value->published:'';
							$status         = isset($value->status)?$value->status:'';
							$createdate     = isset($value->createdate)?$value->createdate:'';
	
							$distributor_list[] = array(
								  'distributor_id'  => $distributor_id,
								'company_name'    => $company_name,
								'gst_no'          => $gst_no,
								'mobile'          => $mobile,
								'current_balance' => $current_bal,
								'available_limit' => $available_lmt,
								'email'           => $email,
								'address'         => $address,
								'published'       => $published,
								'status'          => $status,
								'createdate'      => $createdate,
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
						$response['data']         = $distributor_list;
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
				}else{
					$where = array(
						'distributor_type' => '1',
						'published'        => '1',
						'status'           => '3',
				
					);

					$search = $this->input->post('search');
					if($search !='')
					{
						$like['name'] = $search;
						
					}
					else
					{
						$like = [];
						
					}
					$sort_col_ = array('id', 'company_name', 'mobile', 'email','status');
					array_unshift($sort_col_,"");
					unset($sort_col_[0]);
		
					$option['order_by']   = !empty($sort_column) ? $sort_col_[$sort_column] : 'id';
					$option['disp_order'] = !empty($sort_type) ? ($sort_type==1? 'DESC' : 'ASC') : 'DESC';
					$column = 'id';
					$overalldatas = $this->distributors_model->getDistributors($where, '', '', 'result', $like, '', '', '', $column);
	
					if($overalldatas)
					{
						$totalc = count($overalldatas);
					}
					else
					{
						$totalc = 0;
					}
	
					
	
					$data_list = $this->distributors_model->getDistributors($where, $limit, $offset, 'result', $like, '', $option);
					
					if($data_list)
					{
						$distributor_list = [];
						foreach ($data_list as $key => $value) {
	
							$distributor_id = isset($value->id)?$value->id:'';
							$company_name   = isset($value->company_name)?$value->company_name:'';
							$gst_no         = isset($value->gst_no)?$value->gst_no:'';
							$mobile         = isset($value->mobile)?$value->mobile:'';
							$current_bal    = isset($value->current_balance)?$value->current_balance:'';
							$available_lmt  = isset($value->available_limit)?$value->available_limit:'';
							$email          = isset($value->email)?$value->email:'';
							$address        = isset($value->address)?$value->address:'';
							$published      = isset($value->published)?$value->published:'';
							$status         = isset($value->status)?$value->status:'';
							$createdate     = isset($value->createdate)?$value->createdate:'';
	
							$distributor_list[] = array(
								  'distributor_id'  => $distributor_id,
								'company_name'    => $company_name,
								'gst_no'          => $gst_no,
								'mobile'          => $mobile,
								'current_balance' => $current_bal,
								'available_limit' => $available_lmt,
								'email'           => $email,
								'address'         => $address,
								'published'       => $published,
								'status'          => $status,
								'createdate'      => $createdate,
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
						$response['data']         = $distributor_list;
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
			// List Distributors Pagination for managers
			else if($method == '_listDistributorsPaginate_mg2')
			{
				$limit  = $this->input->post('limit');
	    		$offset = $this->input->post('offset');
				$mg_id = $this->input->post('mg_id');
				$sort_column = $this->input->post('sort_column');
				$sort_type   = $this->input->post('sort_type');
				
				
				
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
	    				'distributor_type' => '1',
	    				'published'        => '1',
						'ref_by'           => $mg_id,
						
	    			);
	    		}
	    		else
	    		{
	    			$like = [];
	    			$where = array(
	    				'distributor_type' => '1',
	    				'published'        => '1',
						'ref_by'           => $mg_id,
					
	    			);
	    		}
				$sort_col_ = array('id', 'company_name', 'mobile', 'email','status');
				array_unshift($sort_col_,"");
				unset($sort_col_[0]);
	
				$option['order_by']   = !empty($sort_column) ? $sort_col_[$sort_column] : 'id';
				$option['disp_order'] = !empty($sort_type) ? ($sort_type==1? 'DESC' : 'ASC') : 'DESC';
	    		$column = 'id';
				$overalldatas = $this->distributors_model->getDistributors($where, '', '', 'result', $like, '', '', '', $column);

				if($overalldatas)
				{
					$totalc = count($overalldatas);
				}
				else
				{
					$totalc = 0;
				}

				

				$data_list = $this->distributors_model->getDistributors($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$distributor_list = [];
					foreach ($data_list as $key => $value) {

						$distributor_id = isset($value->id)?$value->id:'';
			            $company_name   = isset($value->company_name)?$value->company_name:'';
			            $gst_no         = isset($value->gst_no)?$value->gst_no:'';
			            $mobile         = isset($value->mobile)?$value->mobile:'';
			            $current_bal    = isset($value->current_balance)?$value->current_balance:'';
			            $available_lmt  = isset($value->available_limit)?$value->available_limit:'';
			            $email          = isset($value->email)?$value->email:'';
			            $address        = isset($value->address)?$value->address:'';
			            $published      = isset($value->published)?$value->published:'';
			            $status         = isset($value->status)?$value->status:'';
			            $createdate     = isset($value->createdate)?$value->createdate:'';

			            $distributor_list[] = array(
          					'distributor_id'  => $distributor_id,
				            'company_name'    => $company_name,
				            'gst_no'          => $gst_no,
				            'mobile'          => $mobile,
				            'current_balance' => $current_bal,
				            'available_limit' => $available_lmt,
				            'email'           => $email,
				            'address'         => $address,
				            'published'       => $published,
				            'status'          => $status,
				            'createdate'      => $createdate,
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
			        $response['data']         = $distributor_list;
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

			// Distributors Details
			else if($method == '_detailDistributors')
			{
				$distributor_id = $this->input->post('distributor_id');

		    	if(!empty($distributor_id))
		    	{

		    		$where = array('id' => $distributor_id);
				    $data  = $this->distributors_model->getDistributors($where);
					
				    if($data)
				    {	

				    	$distributor_list = [];
						foreach ($data as $key => $value) {
							$ref_id             = !empty($value->ref_id)?$value->ref_id:'';
							$distributor_id     = !empty($value->id)?$value->id:'';
							$dis_code           = !empty($value->dis_code)?$value->dis_code:'';
						    $company_name       = !empty($value->company_name)?$value->company_name:'';
						    $contact_name       = !empty($value->contact_name)?$value->contact_name:'';
						    $mobile             = !empty($value->mobile)?$value->mobile:'';
						    $email              = !empty($value->email)?$value->email:'';
						    $pincode            = !empty($value->pincode)?$value->pincode:'';
						    $state_id           = !empty($value->state_id)?$value->state_id:'';
						    $gst_image          = !empty($value->gst_image)?$value->gst_image:'';
						    $cheque_img         = !empty($value->cheque_img)?$value->cheque_img:'';
						    $qr_image           = !empty($value->qr_image)?$value->qr_image:'';
						    $logo_image         = !empty($value->logo_image)?$value->logo_image:'';
						    $distributor_status = !empty($value->distributor_status)?$value->distributor_status:'';
						    $gst_no             = !empty($value->gst_no)?$value->gst_no:'';
						    $pan_no             = !empty($value->pan_no)?$value->pan_no:'';
						    $tan_no             = !empty($value->tan_no)?$value->tan_no:'';
						    $bill_no            = !empty($value->bill_no)?$value->bill_no:'';
						    $due_days           = !empty($value->due_days)?$value->due_days:'';
						    $discount           = !empty($value->discount)?$value->discount:'';
						    $credit_limit       = !empty($value->credit_limit)?$value->credit_limit:'';
						    $account_name       = !empty($value->account_name)?$value->account_name:'';
						    $account_no         = !empty($value->account_no)?$value->account_no:'';
						    $account_type       = !empty($value->account_type)?$value->account_type:'';
						    $ifsc_code          = !empty($value->ifsc_code)?$value->ifsc_code:'';
						    $bank_name          = !empty($value->bank_name)?$value->bank_name:'';
						    $branch_name        = !empty($value->branch_name)?$value->branch_name:'';
						    $address            = !empty($value->address)?$value->address:'';
						    $password           = !empty($value->password)?$value->password:'';
						    $category_id        = !empty($value->category_id)?$value->category_id:'';
							$sub_cat_id         = !empty($value->sub_cat_id)?$value->sub_cat_id:'';
						    $type_id            = !empty($value->type_id)?$value->type_id:'';
						    $permission         = !empty($value->permission)?$value->permission:'';
						    $vendor_no          = !empty($value->vendor_no)?$value->vendor_no:'';
						    $stock_status       = !empty($value->stock_status)?$value->stock_status:'0';
						    $einv_status        = !empty($value->einv_status)?$value->einv_status:'';
						    $access_token       = !empty($value->access_token)?$value->access_token:'';
						    $msme_status        = !empty($value->msme_status)?$value->msme_status:'';
						    $msme_number        = !empty($value->msme_number)?$value->msme_number:'';
						    $status             = !empty($value->status)?$value->status:'';
						    $published          = !empty($value->published)?$value->published:'';
						    $createdate         = !empty($value->createdate)?$value->createdate:'';
							$grade              = !empty($value->grade)?$value->grade:'';
				            $distributor_list[] = array(
								'ref_id'             => $ref_id,
	          					'distributor_id'     => $distributor_id,
	          					'dis_code'           => $dis_code,
							    'company_name'       => $company_name,
							    'contact_name'       => $contact_name,
							    'mobile'             => $mobile,
							    'email'              => $email,
							    'pincode'            => $pincode,
							    'state_id'           => $state_id,
							    'gst_image'          => $gst_image,
							    'cheque_img'         => $cheque_img,
							    'qr_image'           => $qr_image,
							    'logo_image'         => $logo_image,
							    'distributor_status' => $distributor_status,
							    'gst_no'             => $gst_no,
							    'pan_no'             => $pan_no,
							    'tan_no'             => $tan_no,
							    'bill_no'            => $bill_no,
							    'due_days'           => $due_days,
							    'discount'           => $discount,
							    'credit_limit'       => $credit_limit,
							    'account_name'       => $account_name,
							    'account_no'         => $account_no,
							    'account_type'       => $account_type,
							    'ifsc_code'          => $ifsc_code,
							    'bank_name'          => $bank_name,
							    'branch_name'        => $branch_name,
							    'address'            => $address,
							    'password'           => $password,
							    'category_id'        => $category_id,
								'sub_cat_id'         => $sub_cat_id,
							    'type_id'            => $type_id,
							    'permission'         => $permission,
							    'vendor_no'          => $vendor_no,
							    'einv_status'        => $einv_status,
							    'access_token'       => $access_token,
							    'msme_status'        => $msme_status,
							    'msme_number'        => $msme_number,
							    'stock_status'       => $stock_status,
							    'status'             => $status,
							    'published'          => $published,
							    'createdate'         => $createdate,
								'grade'              => $grade
	          				);
						}

	        			$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $distributor_list;
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

			// List Distributors
			else if($method == '_listDistributors')
			{ 
				 $ref_id =  $this->input->post('ref_id');
				$where = array(
					'ref_id'             => $ref_id,
					'distributor_type'   => '1', 
					//'distributor_status' => '1',
					'status'             => '1', 
					'published'          => '1'
				);
				
				$data_list = $this->distributors_model->getDistributors($where);
				
				if($data_list)
				{
					$distributor_list = [];
					foreach ($data_list as $key => $value) {

						$distributor_id = isset($value->id)?$value->id:'';
			            $company_name   = isset($value->company_name)?$value->company_name:'';
			            $gst_no         = isset($value->gst_no)?$value->gst_no:'';
			            $mobile         = isset($value->mobile)?$value->mobile:'';
			            $email          = isset($value->email)?$value->email:'';
			            $address        = isset($value->address)?$value->address:'';
			            $published      = isset($value->published)?$value->published:'';
			            $status         = isset($value->status)?$value->status:'';
			            $createdate     = isset($value->createdate)?$value->createdate:'';

			            $distributor_list[] = array(
          					'distributor_id' => $distributor_id,
				            'company_name'   => $company_name,
				            'gst_no'         => $gst_no,
				            'mobile'         => $mobile,
				            'email'          => $email,
				            'address'        => $address,
				            'published'      => $published,
				            'status'         => $status,
				            'createdate'     => $createdate,
          				);
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $distributor_list;
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

			// List Distributors
			else if($method == '_listOverallDistributors')
			{    // sathish 'ref_id'
				$ref_id             = $this->input->post('ref_id');
				$where = array(
					'ref_id'    => $ref_id,
					'status'    => '1', 
					'published' => '1'
				);

				$data_list = $this->distributors_model->getDistributors($where);

				if($data_list)
				{
					$distributor_list = [];
					foreach ($data_list as $key => $value) {

						$distributor_id = isset($value->id)?$value->id:'';
			            $company_name   = isset($value->company_name)?$value->company_name:'';
			            $gst_no         = isset($value->gst_no)?$value->gst_no:'';
			            $mobile         = isset($value->mobile)?$value->mobile:'';
			            $email          = isset($value->email)?$value->email:'';
			            $address        = isset($value->address)?$value->address:'';
			            $published      = isset($value->published)?$value->published:'';
			            $status         = isset($value->status)?$value->status:'';
			            $createdate     = isset($value->createdate)?$value->createdate:'';

			            $distributor_list[] = array(
          					'distributor_id' => $distributor_id,
				            'company_name'   => $company_name,
				            'gst_no'         => $gst_no,
				            'mobile'         => $mobile,
				            'email'          => $email,
				            'address'        => $address,
				            'published'      => $published,
				            'status'         => $status,
				            'createdate'     => $createdate,
          				);
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $distributor_list;
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

			// Update Distributors
			else if($method == '_updateDistributors')
			{
				
				$error = FALSE;
			    $errors = array();
				if($ref_by==0){
					$required = array('dis_code', 'company_name', 'mobile', 'email', 'distributor_grade', 'gst_no', 'address', 'distributor_status', 'state_id', 'password', 'distributor_type','pstatus', 'category_id', 'type_id', 'einv_status', 'msme_status');
				}else{
					$required = array('company_name', 'mobile', 'email', 'einv_status', 'msme_status');
				}

				if($einv_status == 1)
				{
					array_push($required, 'access_token');
				}

				if($msme_status == 1)
				{
					array_push($required, 'msme_number');
				}
				
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

			    if(count($errors)==0)
			    {	
			    	$distributor_id     = $this->input->post('distributor_id');
			    	$dis_code           = $this->input->post('dis_code');
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
					$password           = $this->input->post('password');
					$address            = $this->input->post('address');
					$pincode            = $this->input->post('pincode');
					$state_id           = $this->input->post('state_id');
					$distributor_status = $this->input->post('distributor_status');
					$city_id            = $this->input->post('city_id');
					$category_id        = $this->input->post('category_id');
					$sub_cat_id         = $this->input->post('sub_cat_id');
					$type_id            = $this->input->post('type_id');
					$dis_type           = $this->input->post('distributor_type');
					$dis_type           = $this->input->post('distributor_type');
					$status             = $this->input->post('pstatus');
					$grade              = $this->input->post('distributor_grade');
					$mg_role            = $this->input->post('mg_role');
					$access_token       = $this->input->post('access_token');
					$msme_number        = $this->input->post('msme_number');
					
					$category_value = implode(',', $category_id);
					$sub_cat_value = implode(',', $sub_cat_id);
					$type_value     = implode(',', $type_id);
					if($mg_role =='RSM'){
						$mg_status  = 3;
					}else if($mg_role == 'ASM'){
						$mg_status  = 4;
					}else{
						$mg_status  = 5;
					}

					$column = 'id';

			    	$where_1=array(
			    		'id !='     => $distributor_id,
						'email'     => $email,
				    	'status'    => '1',
				    	'published' => '1',
				    );			   

					$overalldatas = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column);

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
						$where_2 = array(
							'email'     => $email,
					    	'status'    => '1',
					    	'published' => '1',
					    );

						$find_datas = $this->vendors_model->getVendors($where_2, '', '', 'result', '', '', '', '', $column);
						if(!empty($find_datas))
						{
							$response['status']  = 0;
					        $response['message'] = "Data Already Exist"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
						}
						else
						{
							$where_3 = array(
								'email'     => $email,
						    	'status'    => '1',
						    	'published' => '1',
						    );

							$find_datas = $this->user_model->getUser($where_3, '', '', 'result', '', '', '', '', $column);

							if(!empty($find_datas))
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
								$dis_whr    = array('id='    => $distributor_id);			   

								$dis_col    = 'credit_limit, available_limit, current_balance';

								$dist_val   = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);

								$old_credit    = !empty($dist_val[0]->credit_limit)?$dist_val[0]->credit_limit:'0';

								$old_available = !empty($dist_val[0]->available_limit)?$dist_val[0]->available_limit:'0';

								$old_balance   = !empty($dist_val[0]->current_balance)?$dist_val[0]->current_balance:'0';

								if($old_credit <= $credit_limit)
								{
									$new_credit_lmt    = $credit_limit - $old_credit;
									$new_available_lmt = $old_available + $new_credit_lmt;
								}
								else
								{
									if($old_available <= $credit_limit)
									{
										$extra_value       = $old_credit - $credit_limit;
										$new_available_lmt = $old_available + $extra_value;
									}
									else
									{
										$extra_value       = $credit_limit - $old_balance;
										$new_available_lmt = $extra_value;
									}
								}
								if($ref_by == 0){
									$data = array(
										'dis_code'           => strtoupper($dis_code),
										'company_name'       => ucfirst($company_name),
										'contact_name'       => ucfirst($contact_name),
										'mobile'             => $mobile,
										'email'              => $email,
										'gst_no'             => $gst_no,
										'pan_no'             => $pan_no,
										'tan_no'             => $tan_no,
										'bill_no'            => $bill_no,
										'credit_limit'       => $credit_limit,
										'available_limit'    => $new_available_lmt,
										'pre_limit'          => $old_credit,
										'discount'           => $discount,
										'due_days'           => $due_days,
										'account_name'       => $account_name,
										'account_no'         => $account_no,
										'account_type'       => $account_type,
										'ifsc_code'          => $ifsc_code,
										'bank_name'          => $bank_name,
										'branch_name'        => $branch_name,
										'password'           => $password,
										'pincode'            => $pincode,
										'city_id'            => $city_id,
										'state_id'           => $state_id,
										'distributor_status' => $distributor_status,
										'category_id'        => $category_value,
										'sub_cat_id'         => $sub_cat_value,
										'type_id'            => $type_value,
										'distributor_type'   => $dis_type,
										'einv_status'        => $einv_status,
										'access_token'       => $access_token,
										'msme_status'        => $msme_status,
										'msme_number'        => $msme_number,
										'address'            => $address,
										'status'             => $status,
										'grade'              => $grade,
										'ref_id'             => 0,
										'updatedate'         => date('Y-m-d H:i:s'),

									);
								}else{
									$data = array(
										
										'company_name'       => ucfirst($company_name),
										'contact_name'       => ucfirst($contact_name),
										'mobile'             => $mobile,
										'email'              => $email,
										'gst_no'             => $gst_no,
										'pan_no'             => $pan_no,
										'tan_no'             => $tan_no,
										'bill_no'            => $bill_no,
										'credit_limit'       => $credit_limit,
										'available_limit'    => $credit_limit,
										'pre_limit'          => '0',
										'discount'           => $discount,
										'due_days'           => $due_days,
										'account_name'       => $account_name,
										'account_no'         => $account_no,
										'account_type'       => $account_type,
										'ifsc_code'          => $ifsc_code,
										'bank_name'          => $bank_name,
										'branch_name'        => $branch_name,
										'pincode'            => $pincode,
										'city_id'            => $city_id,
										'state_id'           => $state_id,
										'status'             => $mg_status,
										'ref_by'             => $ref_by,
										'einv_status'        => $einv_status,
										'access_token'       => $access_token,
										'msme_status'        => $msme_status,
										'msme_number'        => $msme_number,
									);
								}

								if(!empty($_FILES['gst_image']['name']))
								{
									$img_name  = $_FILES['gst_image']['name'];
									$img_val   = explode('.', $img_name);
									$img_res   = $img_val[1];
									$file_name = generateRandomString(13).'.'.$img_res;
		
									$configImg['upload_path']   ='upload/distributor/gst/';
									// $configImg['max_size']      = '1024000';
									$configImg['allowed_types'] = 'jpg|jpeg|png';
									$configImg['overwrite']     = FALSE;
									$configImg['remove_spaces'] = TRUE;
									// $configImg['max_width']     = 120;
									// $configImg['max_height']    = 120;
									$configImg['file_name']     = $file_name;
									$this->load->library('upload', $configImg);
									$this->upload->initialize($configImg);
		
									if(!$this->upload->do_upload('gst_image'))
									{
										$response['status']  = 0;
										$response['message'] = $this->upload->display_errors();
										$response['data']    = [];
										echo json_encode($response);
										return;
									}
									else
									{
										$data['gst_image'] = $file_name;
									}
		
								
								}
								
								if(!empty($_FILES['cheque_image']['name']))
								{
									
									$img_name  = $_FILES['cheque_image']['name'];
									$img_val   = explode('.', $img_name);
									$img_res   = $img_val[1];
									$file_name = generateRandomString(13).'.'.$img_res;
		
									$configImg['upload_path']   ='upload/distributor/cheque/';
									// $configImg['max_size']      = '1024000';
									$configImg['allowed_types'] = 'jpg|jpeg|png';
									$configImg['overwrite']     = FALSE;
									$configImg['remove_spaces'] = TRUE;
									// $configImg['max_width']     = 120;
									// $configImg['max_height']    = 120;
									$configImg['file_name']     = $file_name;
									$this->load->library('upload', $configImg);
									$this->upload->initialize($configImg);
		
									if(!$this->upload->do_upload('cheque_image'))
									{
										$response['status']  = 0;
										$response['message'] = $this->upload->display_errors();
										$response['data']    = [];
										echo json_encode($response);
										return;
									}
									else
									{
										$data['cheque_img'] = $file_name;
									}
		
								
								}

								if(!empty($_FILES['qr_image']['name']))
								{
									$img_name  = $_FILES['qr_image']['name'];
									$img_val   = explode('.', $img_name);
									$img_res   = $img_val[1];
									$file_name = generateRandomString(13).'.'.$img_res;
		
									$configImg['upload_path']   ='upload/distributor/qr_code/';
									// $configImg['max_size']      = '1024000';
									$configImg['allowed_types'] = 'jpg|jpeg|png';
									$configImg['overwrite']     = FALSE;
									$configImg['remove_spaces'] = TRUE;
									// $configImg['max_width']     = 120;
									// $configImg['max_height']    = 120;
									$configImg['file_name']     = $file_name;
									$this->load->library('upload', $configImg);
									$this->upload->initialize($configImg);
		
									if(!$this->upload->do_upload('qr_image'))
									{
										$response['status']  = 0;
										$response['message'] = $this->upload->display_errors();
										$response['data']    = [];
										echo json_encode($response);
										return;
									}
									else
									{
										$data['qr_image'] = $file_name;
									}
								}

								if(!empty($_FILES['logo_image']['name']))
								{
									$img_name  = $_FILES['logo_image']['name'];
									$img_val   = explode('.', $img_name);
									$img_res   = $img_val[1];
									$file_name = generateRandomString(13).'.'.$img_res;
		
									$configImg['upload_path']   ='upload/distributor/logo/';
									// $configImg['max_size']      = '1024000';
									$configImg['allowed_types'] = 'jpg|jpeg|png';
									$configImg['overwrite']     = FALSE;
									$configImg['remove_spaces'] = TRUE;
									// $configImg['max_width']     = 120;
									// $configImg['max_height']    = 120;
									$configImg['file_name']     = $file_name;
									$this->load->library('upload', $configImg);
									$this->upload->initialize($configImg);
		
									if(!$this->upload->do_upload('logo_image'))
									{
										$response['status']  = 0;
										$response['message'] = $this->upload->display_errors();
										$response['data']    = [];
										echo json_encode($response);
										return;
									}
									else
									{
										$data['logo_image'] = $file_name;
									}
								}
								

							    $upt_whr = array('id' => $distributor_id);
							    $update  = $this->distributors_model->distributors_update($data, $upt_whr);
								if($ref_by == 0){
									// Remove all assign product
									$data_2 = array(
										'published' => '0'
									);
	
									 $delete_id = array('distributor_id' => $distributor_id);   
									 $update_1  = $this->assignproduct_model->assignProductDetails_delete($data_2, $delete_id);
	
									 if(!empty($type_id))
									{
										$type_val    = explode(',', $type_value);
										$type_count  = count($type_val);
	
										for ($i=0; $i < $type_count; $i++) {
	
											// Product Details
											$where_4  = array(
												'id'        => $type_val[$i],
												'status'    => '1', 
												'published' => '1'
											);
	
											$product_detail = $this->commom_model->getProductType($where_4);
	
											$category_id  = !empty($product_detail[0]->category_id)?$product_detail[0]->category_id:'';
											$product_id   = !empty($product_detail[0]->product_id)?$product_detail[0]->product_id:'';
											$description  = !empty($product_detail[0]->description)?$product_detail[0]->description:'';
											$product_unit = !empty($product_detail[0]->product_unit)?$product_detail[0]->product_unit:'';
											$sub_cat_id  = !empty($product_detail[0]->sub_cat_id)?$product_detail[0]->sub_cat_id:'';
											// Product Details
											$where_5  = array(
												 'distributor_id' => $distributor_id,
												 'category_id'    => $category_id,
												'sub_cat_id'     => $sub_cat_id,
												 'type_id'        => $type_val[$i],
											);
	
											$type_data = $this->assignproduct_model->getAssignProductDetails($where_5);
	
											if(!empty($type_data))
											{
												// Auto id
												 $assign_auto_id = !empty($type_data[0]->id)?$type_data[0]->id:'';
	
												 $data_3 = array(
													 'description' => $description,
													 'published'   => '1',
													 'updatedate'  => date('Y-m-d H:i:s'),
												 );
	
												 $auto_id   = array('id' => $assign_auto_id);
												 $update_2  = $this->assignproduct_model->assignProductDetails_update($data_3, $auto_id);
											}
											else
											{
												// Product Check
												 $data_2 = array(
													'distributor_id' => $distributor_id,
													'category_id'    => $category_id,
													'sub_cat_id'     => $sub_cat_id,
													'product_id'     => $product_id,
													'type_id'        => $type_val[$i],
													'description'    => $description,
													'product_unit'   => $product_unit,
													'stock'          => '0',
													'createdate'     => date('Y-m-d H:i:s'),
												);
	
												// Assign Product Details
												$insert_2 = $this->assignproduct_model->assignProductDetails_insert($data_2);
											}
										}	
									}
								}
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
			    }
			}

			// Delete Distributors
			else if($method == '_deleteDistributors')
			{	
		    	$distributor_id = $this->input->post('distributor_id');
				$status = $this->input->post('status');

		    	if(!empty($distributor_id))
		    	{
					if(empty($status)){
						$data = array(
							'published' => '0',
						);
	
						// Distributors Product Delete
						$where_1  = array('distributor_id' => $distributor_id);
						$update_1 = $this->assignproduct_model->assignproduct_delete($data, $where_1);
						$update_1 = $this->assignproduct_model->assignProductDetails_delete($data, $where_1);
	
						// Distributors Delete
						$where_2  = array('id' => $distributor_id);
						$update_2 = $this->distributors_model->distributors_delete($data, $where_2);
					}else{
						$data = array(
							'status' => $status,
						);
						$where_2  = array('id' => $distributor_id);
						$update_2 = $this->distributors_model->distributors_update($data, $where_2);
					}
		    		
				    if($update_2)
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

			
			else if($method == '_distributorZoneList')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id');
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
			    	$distributor_id = $this->input->post('distributor_id');

			    	// Outlet Details
					$where_1 = array(
						'id'        => $distributor_id,
						'status'    => '1',
						'published' => '1',
					);

					$column_1 = 'zone_id';

					$dis_data = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column_1);

					$zone_id  = !empty($dis_data[0]->zone_id)?$dis_data[0]->zone_id:'';

					$where_2 = array(
						'id'        => $zone_id,
						'status'    => '1',
						'published' => '1',
					);

					$column_2 = 'id, name';

					$zone_data = $this->commom_model->getDistributoeZone($where_2, '', '', 'result', '', '', '', '', $column_2);

					if($zone_data)
					{
						$zone_list = [];
						foreach ($zone_data as $key => $value) {
							$zone_id   = !empty($value->id)?$value->id:'';
				            $zone_name = !empty($value->name)?$value->name:'';

				            $zone_list[] = array(
				            	'zone_id'   => $zone_id,
				            	'zone_name' => $zone_name,

				            );
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $zone_list;
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

			else if($method == '_distributorCategoryList')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id');
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
			    	$distributor_id = $this->input->post('distributor_id');

			    	$whr_1  = array('id' => $distributor_id);
			    	$col_1  = 'category_id';
				    $data_1 = $this->distributors_model->getDistributors($whr_1, '', '', 'result', '', '', '', '', $col_1);

				    if($data_1)
				    {
				    	$category_id = !empty($data_1[0]->category_id)?$data_1[0]->category_id:'';

				    	$whr_2 = array(
				    		'category_id' => $category_id,
				    		'published'   => '1',
				    		'status'      => '1',
				    	);

				    	$col_2  = 'id, name';
				    	$data_2 = $this->commom_model->getCategoryImplode($whr_2, '', '', 'result', '', '', '', '', $col_2);

				    	$category_list = [];
				    	if($data_2)
				    	{
				    		foreach ($data_2 as $key => $val) {
					    		$cat_id   = !empty($val->id)?$val->id:'';
	            				$cat_name = !empty($val->name)?$val->name:'';

	            				$category_list[] = array(
	            					'category_id'   => $cat_id,
	            					'category_name' => $cat_name,
	            				);
					    	}
				    	}

				    	$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $category_list;
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
			else if($method == '_distributorSubCategoryList')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id','category_id');
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
			    	$distributor_id = $this->input->post('distributor_id');
					$category_id 	= $this->input->post('category_id');

			    	$whr_1  = array('id' => $distributor_id);
			    	$col_1  = 'sub_cat_id';
				    $data_1 = $this->distributors_model->getDistributors($whr_1, '', '', 'result', '', '', '', '', $col_1);

				    if($data_1)
				    {
				    	
						$sub_cat_id = !empty($data_1[0]->sub_cat_id)?$data_1[0]->sub_cat_id:'';
						
						
				    	
						$whre_2 = array(
				    		'id'          => $sub_cat_id,
							'category_id' => $category_id,
				    		'published'   => '1',
				    		'status'      => '1',
				    	);
						
				    	$coll_2  = 'id, name';
				    	$dataa_2 = $this->commom_model->getSubCategoryImplode($whre_2, '', '', 'result', '', '', '', '', $coll_2);

						$sub_cat_list=[];
						if($dataa_2){
							foreach ($dataa_2 as $key => $vall) {
					    		$s_cat_id   = !empty($vall->id)?$vall->id:'';
	            				$s_cat_name = !empty($vall->name)?$vall->name:'';

	            				$sub_cat_list[] = array(
	            					's_cat_id'   => $s_cat_id,
	            					's_cat_name' => $s_cat_name,
	            				);
					    	}
						}


				    	$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $sub_cat_list;
						
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
			else if($method == '_access')
			{
				$distributor_id = $this->input->post('distributor_id');
				$where = array(
					'id'    => $distributor_id, 
					
				);
                $col_g='grade';
				$data = $this->distributors_model->getDistributors($where, '', '', 'result', '', '', '', '', $col_g);
                $grade = !empty($data[0]->grade)?$data[0]->grade:'';

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $grade;
		    		echo json_encode($response);
			        return;
			}

			else if($method == '_newDistributors')
			{
				$company_name = $this->input->post('company_name');
				$contact_name = $this->input->post('contact_name');
				$mobile       = $this->input->post('mobile');
				$gst_no       = $this->input->post('gst_no');

				$error    = FALSE;
			    $errors   = array();
				$required = array('company_name', 'contact_name', 'mobile', 'gst_no');
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
				    if(contact_validation($this->input->post('mobile')) == 0)
				    {
				        $response['status']  = 0;
				        $response['message'] = "Contact number not appear to be valid"; 
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

			    if(empty($_FILES['gst_image']['name']))
			    {
			    	$response['status']  = 0;
			        $response['message'] = "GSTIN certification is required"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(empty($_FILES['cheque_img']['name']))
			    {
			    	$response['status']  = 0;
			        $response['message'] = "Cheque image is required"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(count($errors)==0)
			    {
			    	$where_1=array(
						'mobile'    => $mobile,
				    	'status'    => '1',
				    	'published' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column);

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
						$ins_1 = array(
							'company_name' => $company_name,
							'contact_name' => $contact_name, 
							'mobile'       => $mobile,
							'gst_no'       => $gst_no,
							'stock_status' => 2,
							'createdate'   => date('Y-m-d H:i:s'),
						);

						if(!empty($_FILES['gst_image']['name']))
					    {
					    	$img_name  = $_FILES['gst_image']['name'];
							$img_val   = explode('.', $img_name);
							$img_res   = end($img_val);
							$file_name = generateRandomString(13).'.'.$img_res;

						    $configImg['upload_path']   = 'upload/distributor/gst/';
							$configImg['max_size']      = '1024000';
							$configImg['allowed_types'] = 'jpg|jpeg|png|gif';
							$configImg['overwrite']     = FALSE;
							$configImg['remove_spaces'] = TRUE;
                			$configImg['file_name']     = $file_name;
							$this->load->library('upload', $configImg);
							$this->upload->initialize($configImg);

							if(!$this->upload->do_upload('gst_image'))
							{
						        $response['status']  = 0;
						        $response['message'] = $this->upload->display_errors();
						        $response['data']    = [];
						        echo json_encode($response);
						        return;
							}
							else
							{
								$ins_1['gst_image'] = $file_name;
							}
					    }

					    if(!empty($_FILES['cheque_img']['name']))
					    {
					    	$img_name  = $_FILES['cheque_img']['name'];
							$img_val   = explode('.', $img_name);
							$img_res   = end($img_val);
							$file_name = generateRandomString(13).'.'.$img_res;

						    $configImg['upload_path']   = 'upload/distributor/cheque/';
							$configImg['max_size']      = '1024000';
							$configImg['allowed_types'] = 'jpg|jpeg|png|gif';
							$configImg['overwrite']     = FALSE;
							$configImg['remove_spaces'] = TRUE;
                			$configImg['file_name']     = $file_name;
							$this->load->library('upload', $configImg);
							$this->upload->initialize($configImg);

							if(!$this->upload->do_upload('cheque_img'))
							{
						        $response['status']  = 0;
						        $response['message'] = $this->upload->display_errors();
						        $response['data']    = [];
						        echo json_encode($response);
						        return;
							}
							else
							{
								$ins_1['cheque_img'] = $file_name;
							}
					    }

					    $insert = $this->distributors_model->distributors_insert($ins_1);
					    
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

			else
			{
				$response['status']  = 0;
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// distributors wise outlet list
		// ***************************************************
		public function distributor_outlet_list($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_distributorOutletList')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id');
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
			    	$distributor_id = $this->input->post('distributor_id');

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
		    				'status'         => '1',
		    				'published'      => '1',
		    			);
		    		}
		    		else
		    		{
		    			$like = [];

		    			$where = array(
		    				'distributor_id' => $distributor_id,
		    				'status'         => '1',
		    				'published'      => '1',
		    			);
		    		}

		    		$column = 'id';
					$overalldatas = $this->distributors_model->getDistributorOutlet($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->distributors_model->getDistributorOutlet($where, $limit, $offset, 'result', $like, '', $option);

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
							$createdate     = !empty($value->createdate)?$value->createdate:'';
							$updatedate     = !empty($value->updatedate)?$value->updatedate:'';

							if(!empty($createdate))
							{
								$view_date = date('d-m-Y H:i:s', strtotime($createdate));
							}
							else
							{
								$view_date = date('d-m-Y H:i:s', strtotime($updatedate));
							}

							$outlets_list[] = array(
								'assign_id'      => $assign_id,
								'distributor_id' => $distributor_id,
								'outlet_id'      => $outlet_id,
								'outlet_name'    => $outlet_name,
								'pre_bal'        => $pre_bal,
								'cur_bal'        => $cur_bal,
								'update_date'    => $view_date,
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
			}

			else if($method == '_distributorOverallOutletList')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id');
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
			    	$distributor_id = $this->input->post('distributor_id');

			    	$where = array(
	    				'distributor_id' => $distributor_id,
	    				'status'         => '1',
	    				'published'      => '1',
	    			);
			    	
			    	$data_list = $this->distributors_model->getDistributorOutlet($where);

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
							$createdate     = !empty($value->createdate)?$value->createdate:'';
							$updatedate     = !empty($value->updatedate)?$value->updatedate:'';

							if(!empty($createdate))
							{
								$view_date = date('d-m-Y H:i:s', strtotime($createdate));
							}
							else
							{
								$view_date = date('d-m-Y H:i:s', strtotime($updatedate));
							}

							$outlets_list[] = array(
								'assign_id'      => $assign_id,
								'distributor_id' => $distributor_id,
								'outlet_id'      => $outlet_id,
								'outlet_name'    => $outlet_name,
								'pre_bal'        => $pre_bal,
								'cur_bal'        => $cur_bal,
								'update_date'    => $view_date,
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
				        $response['message'] = "Not Found"; 
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

		// Profile Settings
		// ***************************************************
		public function profile_settings($param1="",$param2="",$param3="")
		{
			$user_id      = $this->input->post('user_id');
			$method       = $this->input->post('method');

			$old_password = $this->input->post('old_password');
			$password     = $this->input->post('password');
			$c_password   = $this->input->post('confirm_password');

			$username     = $this->input->post('username');
		    $email        = $this->input->post('email');
		    $mobile       = $this->input->post('mobile');
		    $stock_status = $this->input->post('stock_status');
		    $account_name = $this->input->post('account_name');
		    $account_no   = $this->input->post('account_no');
		    $account_type = $this->input->post('account_type');
		    $ifsc_code    = $this->input->post('ifsc_code');
		    $bank_name    = $this->input->post('bank_name');
		    $branch_name  = $this->input->post('branch_name');
		    $address      = $this->input->post('address');
		    $user_id      = $this->input->post('user_id');

			if($method == '_changePassword')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('user_id', 'old_password', 'password', 'confirm_password');
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
			    	$where = array('id' => $user_id);
					$data  = $this->distributors_model->getDistributors($where);

					$previous_password = isset($data[0]->password)?$data[0]->password:'';

					if($old_password == $previous_password)
					{
						if($password == $c_password)
						{
							$data = array(
					    		'password'   => $password,
							    'updatedate' => date('Y-m-d H:i:s'),
					    	);

					    	$update_id = array('id' => $user_id);

					    	$update    = $this->distributors_model->distributors_update($data, $update_id);
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
					        $response['message'] = "New Password is mismatch"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
				    	}
					}
					else
					{
						$response['status']  = 0;
				        $response['message'] = "Old Password is mismatch"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
					}
			    }
			}

			else if($method == '_userDetails')
			{
				$user_id = $this->input->post('user_id');

				if(!empty($user_id))
				{
					$where = array('id' => $user_id);
					$data  = $this->distributors_model->getDistributors($where);

					if($data)
					{
						$user_list = [];

						foreach ($data as $key => $value) {
							$user_id      = isset($value->id)?$value->id:'';
						    $company_name = isset($value->company_name)?$value->company_name:'';
						    $mobile       = isset($value->mobile)?$value->mobile:'';
						    $email        = isset($value->email)?$value->email:'';
						    $account_name = isset($value->account_name)?$value->account_name:'';
						    $account_no   = isset($value->account_no)?$value->account_no:'';
						    $account_type = isset($value->account_type)?$value->account_type:'';
						    $ifsc_code    = isset($value->ifsc_code)?$value->ifsc_code:'';
						    $bank_name    = isset($value->bank_name)?$value->bank_name:'';
						    $branch_name  = isset($value->branch_name)?$value->branch_name:'';
						    $address      = isset($value->address)?$value->address:'';
						    $stock_status = isset($value->stock_status)?$value->stock_status:'';

						    $user_list  = array(
						    	'user_id'      => $user_id,
							    'company_name' => $company_name,
							    'mobile'       => $mobile,
							    'email'        => $email,
							    'account_name' => $account_name,
							    'account_no'   => $account_no,
							    'account_type' => $account_type,
							    'ifsc_code'    => $ifsc_code,
							    'bank_name'    => $bank_name,
							    'branch_name'  => $branch_name,
							    'address'      => $address,
							    'stock_status' => $stock_status,
						    ); 
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $user_list;
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

			else if($method == '_profileSettings')
			{
				$error    = FALSE;
			    $errors   = array();
				$required = array('user_id', 'username', 'email', 'mobile', 'stock_status', 'address');
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
			    	$data = array(
			    		'company_name' => $username,
					    'email'        => $email,
					    'mobile'       => $mobile,
					    'stock_status' => $stock_status,
					    'account_name' => $account_name,
					    'account_no'   => $account_no,
					    'account_type' => $account_type,
					    'ifsc_code'    => $ifsc_code,
					    'bank_name'    => $bank_name,
					    'branch_name'  => $branch_name,
					    'address'      => $address,
			    	);

			    	$update_id = array('id' => $user_id);

			    	$update    = $this->distributors_model->distributors_update($data, $update_id);
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

<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class Vendors extends CI_Controller {

		public function __construct()
		{
			parent::__construct();

			$this->load->model('commom_model');
			$this->load->model('distributors_model');
			$this->load->model('vendors_model');
			$this->load->model('user_model');
		}

		public function index()
		{
			echo "Test";
		}

		// vendors
		// ***************************************************
		public function vendors($param1="",$param2="",$param3="")
		{
			$method     = $this->input->post('method');
			$log_id     = $this->input->post('log_id');
			$log_role   = $this->input->post('log_role');

			if($method == '_addVendors')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('company_name', 'gst_no', 'email', 'contact_no', 'vendor_type', 'state_id', 'city_id', 'password', 'purchase_type', 'msme_type');
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
			    	$company_name  = $this->input->post('company_name');
			    	$contact_name  = $this->input->post('contact_name');
					$contact_no    = $this->input->post('contact_no');
					$email         = $this->input->post('email');
					$gst_no        = $this->input->post('gst_no');
					$vendor_type   = $this->input->post('vendor_type');
					$due_days      = $this->input->post('due_days');
					$account_name  = $this->input->post('account_name');
					$account_no    = $this->input->post('account_no');
					$account_type  = $this->input->post('account_type');
					$ifsc_code     = $this->input->post('ifsc_code');
					$bank_name     = $this->input->post('bank_name');
					$branch_name   = $this->input->post('branch_name');
					$password      = $this->input->post('password');
					$state_id      = $this->input->post('state_id');
					$city_id       = $this->input->post('city_id');
					$purchase_type = $this->input->post('purchase_type');
					$msme_type     = $this->input->post('msme_type');
					$address       = $this->input->post('address');

			    	$where_1 = array(
				    	'company_name' => ucfirst($company_name),
				    	'gst_no'       => $gst_no,
				    	'email'        => $email,
				    	'status'       => '1',
				    	'published'    => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->vendors_model->getVendors($where_1, '', '', 'result', '', '', '', '', $column);

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

						$find_datas = $this->distributors_model->getDistributors($where_2, '', '', 'result', '', '', '', '', $column);

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
								$vandor_value  = $this->vendors_model->getVendors('','','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

								$vendor_count  = !empty($vandor_value[0]->autoid)?$vandor_value[0]->autoid:'0';
								$vendor_number = 'VENDOR'.leadingZeros($vendor_count, 5);

								$data = array(
									'vendor_no'     => $vendor_number,
							    	'company_name'  => ucfirst($company_name),
							    	'contact_name'  => ucfirst($contact_name),
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
							    	'createdate'    => date('Y-m-d H:i:s')
							    );

								if($msme_type == 1)
								{
									if(!empty($_FILES['image']['name']))
								    {
								    	$img_name  = $_FILES['image']['name'];
										$img_val   = explode('.', $img_name);
										$img_res   = $img_val[1];
										$file_name = generateRandomString(13).'.'.$img_res;

									    $configImg['upload_path']   ='../upload/vendors/';
										$configImg['max_size']      = '1024000';
										$configImg['allowed_types'] = 'jpg|jpeg|png|gif';
										$configImg['overwrite']     = FALSE;
										$configImg['remove_spaces'] = TRUE;
										$configImg['max_width']     = 1366;
			                			$configImg['max_height']    = 768;
			                			$configImg['file_name']     = $file_name;
										$this->load->library('upload', $configImg);
										$this->upload->initialize($configImg);

										if(!$this->upload->do_upload('image'))
										{
									        $response['status']  = 0;
									        $response['message'] = $this->upload->display_errors();
									        $response['data']    = [];
									        echo json_encode($response);
									        return;
										}
										else
										{
											$data['msme_file'] = $file_name;
										}
								    }

								    $insert = $this->vendors_model->vendors_insert($data);
								}
								else
								{
									$insert = $this->vendors_model->vendors_insert($data);

								    if($vendor_type == 2)
								    {
								    	$data_1 = array(
								    		'company_name'     => ucfirst($company_name),
											'mobile'           => $contact_no,
											'email'            => $email,
											'gst_no'           => $gst_no,
											'account_name'     => $account_name,
											'account_no'       => $account_no,
											'account_type'     => $account_type,
											'ifsc_code'        => $ifsc_code,
											'bank_name'        => $bank_name,
											'branch_name'      => $branch_name,
											'distributor_type' => $vendor_type,
											'vendor_id'        => $insert,
											'vendor_no'        => $vendor_number,
											'state_id'         => $state_id,
											'city_id'          => $city_id,
											'password'         => $password,
											'address'          => $address,
									    	'createdate'       => date('Y-m-d H:i:s')
								    	);

								    	$dis_insert = $this->distributors_model->distributors_insert($data_1);

								    	// Distributors Details Update
								    	$data_2 = array(
								    		'distributor_id' => $dis_insert,
								    	);

								    	$update_id  = array('id' => $insert);
							    		$update_val = $this->vendors_model->vendors_update($data_2, $update_id);
								    }
								}

								$log_data = array(
									'u_id'       => $log_id,
									'role'       => $log_role,
									'table'      => 'tbl_vendors',
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
			    }
			}

			else if($method == '_listVendorsPaginate')
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
	    			$where = array('published'=>'1');
	    		}
	    		else
	    		{
	    			$like = [];
	    			$where = array('published'=>'1');
	    		}

	    		$column = 'id';
				$overalldatas = $this->vendors_model->getVendors($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->vendors_model->getVendors($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$vendor_list = [];

					foreach ($data_list as $key => $value) {

						$vendor_id    = isset($value->id)?$value->id:'';
			            $company_name = isset($value->company_name)?$value->company_name:'';
			            $gst_no       = isset($value->gst_no)?$value->gst_no:'';
			            $contact_no   = isset($value->contact_no)?$value->contact_no:'';
			            $email        = isset($value->email)?$value->email:'';
			            $address      = isset($value->address)?$value->address:'';
			            $pre_balance  = isset($value->pre_balance)?$value->pre_balance:'';
			            $cur_balance  = isset($value->cur_balance)?$value->cur_balance:'';
			            $vendor_type  = isset($value->vendor_type)?$value->vendor_type:'';
			            $published    = isset($value->published)?$value->published:'';
			            $status       = isset($value->status)?$value->status:'';
			            $createdate   = isset($value->createdate)?$value->createdate:'';

			            $vendor_list[] = array(
          					'vendor_id'    => $vendor_id,
				            'company_name' => $company_name,
				            'gst_no'       => $gst_no,
				            'contact_no'   => $contact_no,
				            'email'        => $email,
				            'address'      => $address,
				            'vendor_type'  => $vendor_type,
				            'pre_balance'  => $pre_balance,
				            'cur_balance'  => $cur_balance,
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
			        $response['data']         = $vendor_list;
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

			else if($method == '_detailVendors')
			{
				$vendor_id = $this->input->post('vendor_id');

		    	if(!empty($vendor_id))
		    	{
		    		$where = array('id'=>$vendor_id);
		    		
				    $data  = $this->vendors_model->getVendors($where);

				    if($data)
				    {	
				    	$vendor_list = [];
						foreach ($data as $key => $value) {
							$vendor_id      = isset($value->id)?$value->id:'';
							$vendor_no      = isset($value->vendor_no)?$value->vendor_no:'';
				            $company_name   = isset($value->company_name)?$value->company_name:'';
				            $contact_name   = isset($value->contact_name)?$value->contact_name:'';
							$contact_no     = isset($value->contact_no)?$value->contact_no:'';
							$email          = isset($value->email)?$value->email:'';
							$gst_no         = isset($value->gst_no)?$value->gst_no:'';
							$vendor_type    = isset($value->vendor_type)?$value->vendor_type:'';
							$due_days       = isset($value->due_days)?$value->due_days:'';
							$account_name   = isset($value->account_name)?$value->account_name:'';
							$account_no     = isset($value->account_no)?$value->account_no:'';
							$account_type   = isset($value->account_type)?$value->account_type:'';
							$ifsc_code      = isset($value->ifsc_code)?$value->ifsc_code:'';
							$bank_name      = isset($value->bank_name)?$value->bank_name:'';
							$branch_name    = isset($value->branch_name)?$value->branch_name:'';
							$state_id       = isset($value->state_id)?$value->state_id:'';
							$city_id        = isset($value->city_id)?$value->city_id:'';
							$purchase_type  = isset($value->purchase_type)?$value->purchase_type:'';
							$msme_type      = isset($value->msme_type)?$value->msme_type:'';
							$msme_file      = isset($value->msme_file)?$value->msme_file:'';
							$password       = isset($value->password)?$value->password:'';
							$address        = isset($value->address)?$value->address:'';
							$distributor_id = isset($value->distributor_id)?$value->distributor_id:'';
				            $published      = isset($value->published)?$value->published:'';
				            $status         = isset($value->status)?$value->status:'';
				            $createdate     = isset($value->createdate)?$value->createdate:'';

				            $vendor_list[] = array(
	          					'vendor_id'      => $vendor_id,
	          					'vendor_no'      => $vendor_no,
					            'company_name'   => $company_name,
					            'contact_name'   => $contact_name,
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
								'address'        => $address,
								'distributor_id' => $distributor_id,
								'state_id'       => $state_id,
								'city_id'        => $city_id,
								'purchase_type'  => $purchase_type,
								'msme_type'      => $msme_type,
								'msme_file'      => $msme_file,
								'password'       => $password,
					            'published'      => $published,
					            'status'         => $status,
					            'createdate'     => $createdate,
	          				);
						}

	        			$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $vendor_list;
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

			else if($method == '_listVendors')
			{
				$where = array(
					'status'      => '1',
					'published'   => '1'
				);

				$data_list = $this->vendors_model->getVendors($where);

				if($data_list)
				{
					$vendor_list = [];

					foreach ($data_list as $key => $value) {

						$vendor_id    = isset($value->id)?$value->id:'';
			            $company_name = isset($value->company_name)?$value->company_name:'';
			            $contact_no   = isset($value->contact_no)?$value->contact_no:'';
			            $gst_no       = isset($value->gst_no)?$value->gst_no:'';
			            $published    = isset($value->published)?$value->published:'';
			            $status       = isset($value->status)?$value->status:'';
			            $createdate   = isset($value->createdate)?$value->createdate:'';

			            $vendor_list[] = array(
          					'vendor_id'    => $vendor_id,
				            'company_name' => $company_name,
				            'contact_no'   => $contact_no,
				            'gst_no'       => $gst_no,
				            'published'    => $published,
				            'status'       => $status,
				            'createdate'   => $createdate,
          				);
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $vendor_list;
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

			else if($method == '_listManufacturerVendors')
			{
				$where = array(
					'vendor_type' => '1',
					'status'      => '1',
					'published'   => '1'
				);

				$data_list = $this->vendors_model->getVendors($where);

				if($data_list)
				{
					$vendor_list = [];

					foreach ($data_list as $key => $value) {

						$vendor_id    = isset($value->id)?$value->id:'';
			            $company_name = isset($value->company_name)?$value->company_name:'';
			            $contact_no   = isset($value->contact_no)?$value->contact_no:'';
			            $gst_no       = isset($value->gst_no)?$value->gst_no:'';
			            $published    = isset($value->published)?$value->published:'';
			            $status       = isset($value->status)?$value->status:'';
			            $createdate   = isset($value->createdate)?$value->createdate:'';

			            $vendor_list[] = array(
          					'vendor_id'    => $vendor_id,
				            'company_name' => $company_name,
				            'contact_no'   => $contact_no,
				            'gst_no'       => $gst_no,
				            'published'    => $published,
				            'status'       => $status,
				            'createdate'   => $createdate,
          				);
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $vendor_list;
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

			else if($method == '_listPurchaseVendor')
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
	    				'vendor_type' => '1',
	    				'status'      => '1',
	    				'published'   => '1',
	    			);
	    		}
	    		else
	    		{
	    			$like = [];
	    			$where = array(
	    				'vendor_type' => '1',
	    				'status'      => '1',
	    				'published'   => '1',
	    			);
	    		}

	    		$column = 'id';
				$overalldatas = $this->vendors_model->getVendors($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->vendors_model->getVendors($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$vendor_list = [];

					foreach ($data_list as $key => $value) {

						$vendor_id    = isset($value->id)?$value->id:'';
			            $company_name = isset($value->company_name)?$value->company_name:'';
			            $gst_no       = isset($value->gst_no)?$value->gst_no:'';
			            $contact_no   = isset($value->contact_no)?$value->contact_no:'';
			            $email        = isset($value->email)?$value->email:'';
			            $address      = isset($value->address)?$value->address:'';
			            $vendor_type  = isset($value->vendor_type)?$value->vendor_type:'';
			            $published    = isset($value->published)?$value->published:'';
			            $status       = isset($value->status)?$value->status:'';
			            $createdate   = isset($value->createdate)?$value->createdate:'';

			            $vendor_list[] = array(
          					'vendor_id'    => $vendor_id,
				            'company_name' => $company_name,
				            'gst_no'       => $gst_no,
				            'contact_no'   => $contact_no,
				            'email'        => $email,
				            'address'      => $address,
				            'vendor_type'  => $vendor_type,
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
			        $response['data']         = $vendor_list;
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

			else if($method == '_updateVendors')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('vendor_no', 'company_name', 'email', 'gst_no', 'contact_no', 'vendor_type', 'state_id', 'city_id', 'purchase_type', 'msme_type', 'password');
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
			    	$vendor_id      = $this->input->post('vendor_id');
			    	$vendor_no      = $this->input->post('vendor_no');
			    	$company_name   = $this->input->post('company_name');
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
					$state_id       = $this->input->post('state_id');
					$city_id        = $this->input->post('city_id');
					$purchase_type  = $this->input->post('purchase_type');
					$msme_type      = $this->input->post('msme_type');
					$password       = $this->input->post('password');
					$address        = $this->input->post('address');
					$distributor_id = $this->input->post('distributor_id');
			    	$status         = $this->input->post('pstatus');

			    	$where_1 = array(
			    		'id !='        => $vendor_id,
				    	'company_name' => ucfirst($company_name),
				    	'gst_no'       => $gst_no,
				    	'email'        => $email,
				    	'status'       => '1',
				    	'published'    => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->vendors_model->getVendors($where_1, '', '', 'result', '', '', '', '', $column);

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
							'vendor_no !=' => $vendor_no,
							'email'        => $email,
					    	'status'       => '1',
					    	'published'    => '1',
					    );

						$find_datas = $this->distributors_model->getDistributors($where_2, '', '', 'result', '', '', '', '', $column);

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
								$data = array(
							    	'company_name'  => ucfirst($company_name),
							    	'contact_name'  => ucfirst($contact_name),
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
							    	'status'        => $status,
							    	'updatedate'    => date('Y-m-d H:i:s')
							    );

								if($msme_type == 1)
								{
									if(!empty($_FILES['image']['name']))
								    {
								    	$img_name  = $_FILES['image']['name'];
										$img_val   = explode('.', $img_name);
										$img_res   = $img_val[1];
										$file_name = generateRandomString(13).'.'.$img_res;

									    $configImg['upload_path']   ='../upload/vendors/';
										$configImg['max_size']      = '1024000';
										$configImg['allowed_types'] = 'jpg|jpeg|png|gif';
										$configImg['overwrite']     = FALSE;
										$configImg['remove_spaces'] = TRUE;
										$configImg['max_width']     = 1366;
			                			$configImg['max_height']    = 768;
			                			$configImg['file_name']     = $file_name;
										$this->load->library('upload', $configImg);
										$this->upload->initialize($configImg);

										if(!$this->upload->do_upload('image'))
										{
									        $response['status']  = 0;
									        $response['message'] = $this->upload->display_errors();
									        $response['data']    = [];
									        echo json_encode($response);
									        return;
										}
										else
										{
											$data['msme_file'] = $file_name;
										}
								    }
								}

					    		$update_id  = array('id' => $vendor_id);
							    $update = $this->vendors_model->vendors_update($data, $update_id);

							    // Distributors Details Update
							    if($vendor_type == 2)
							    {
							    	$data_1 = array(
							    		'company_name'     => ucfirst($company_name),
										'mobile'           => $contact_no,
										'email'            => $email,
										'gst_no'           => $gst_no,
										'account_name'     => $account_name,
										'account_no'       => $account_no,
										'account_type'     => $account_type,
										'ifsc_code'        => $ifsc_code,
										'bank_name'        => $bank_name,
										'branch_name'      => $branch_name,
										'distributor_type' => $vendor_type,
										'state_id'         => $state_id,
										'city_id'          => $city_id,
										'password'         => $password,
										'address'          => $address,
										'status'           => $status,
								    	'updatedate'       => date('Y-m-d H:i:s')
							    	);

							    	$update_id  = array('id' => $distributor_id);
						    		$update_val = $this->distributors_model->distributors_update($data_1, $update_id);
							    }

							    $log_data = array(
									'u_id'       => $log_id,
									'role'       => $log_role,
									'table'      => 'tbl_vendors',
									'auto_id'    => $vendor_id,
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
			    }
			}

			else if($method == '_deleteVendors')
			{	
		    	$vendor_id = $this->input->post('vendor_id');

		    	if(!empty($vendor_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

		    		// Distributors Delete
		    		$where_1  = array('vendor_id'=>$vendor_id);
				    $update_1 = $this->distributors_model->distributors_delete($data, $where_1);

		    		// Vendors Delete
		    		$where_2  = array('id'=>$vendor_id);
				    $update_2 = $this->vendors_model->vendors_delete($data, $where_2);

				    $log_data = array(
						'u_id'       => $log_id,
						'role'       => $log_role,
						'table'      => 'tbl_vendors',
						'auto_id'    => $vendor_id,
						'action'     => 'delete',
						'date'       => date('Y-m-d'),
						'time'       => date('H:i:s'),
						'createdate' => date('Y-m-d H:i:s')
					);

					$log_val = $this->commom_model->log_insert($log_data);

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
<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class DistributorDelivery extends CI_Controller {

		public function __construct()
		{
			parent::__construct();

			$this->load->model('commom_model');
			$this->load->model('distributorpurchase_model');
			$this->load->model('assignproduct_model');
			$this->load->model('distributors_model');
			$this->load->model('invoice_model');
			$this->load->model('payment_model');
			$this->load->model('login_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Add Purchase
		// ***************************************************
		public function add_delivery($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addDeliverychallan')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id', 'purchase_value', 'active_financial');
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
			    	$distributor_id   = $this->input->post('distributor_id');
			    	$purchase_value   = $this->input->post('purchase_value');
			    	$active_financial = $this->input->post('active_financial');
			    	$date             = date('Y-m-d');
					$time             = date('H:i:s');
					$c_date           = date('Y-m-d H:i:s');

					$product_value = json_decode($purchase_value);

					$where = array(
			    		'financial_year' => $active_financial,
						'published'      => '1',
						'status'         => '1',
					);

					$bill_val   = $this->distributorpurchase_model->getDistributorDelivery($where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

					$bill_count = !empty($bill_val[0]->autoid)?$bill_val[0]->autoid:'0';
					$bill_num   = 'ORD'.leadingZeros($bill_count, 5);

					$data = array(
						'order_no'         => $bill_num,
				    	'distributor_id'   => $distributor_id,
				    	'order_date'       => $date,
				    	'_ordered'         => $c_date,
				    	'financial_year'   => $active_financial,
				    	'createdate'       => $c_date
				    );

				    $insert = $this->distributorpurchase_model->distributorDelivery_insert($data);

				    foreach ($product_value as $key => $val_2) {

				    	$dis_product_id  = !empty($val_2->dis_product_id)?$val_2->dis_product_id:'';
					    $dis_product_qty = !empty($val_2->dis_product_qty)?$val_2->dis_product_qty:'';
					    $dis_price_val   = !empty($val_2->dis_price_val)?$val_2->dis_price_val:'0';
					    $dis_unit_id     = !empty($val_2->dis_unit_id)?$val_2->dis_unit_id:'';

					    // Product Details
				    	$where_1 = array(
				    		'id'        => $dis_product_id,
				    		'status'    => '1', 
							'published' => '1'
				    	);

				    	$product_data = $this->assignproduct_model->getAssignProductDetails($where_1);
				    	$product_val  = $product_data[0];

				    	$category_id = !empty($product_val->category_id)?$product_val->category_id:'';
			            $product_id  = !empty($product_val->product_id)?$product_val->product_id:'';
			            $type_id     = !empty($product_val->type_id)?$product_val->type_id:'';

			            // Product Details
				    	$where_2  = array(
				    		'id' => $product_id,
				    	);

				    	$column_2 = 'vendor_id';

				    	$vendor_data = $this->commom_model->getProduct($where_2, '', '', 'result', '', '', '', '', $column_2);

				    	$vendor_id   = !empty($vendor_data[0]->vendor_id)?$vendor_data[0]->vendor_id:'';

				    	$value = array(
							'order_id'       => $insert,
							'distributor_id' => $distributor_id,
							'vendor_id'      => $vendor_id,
							'assproduct_id'  => $dis_product_id,
							'category_id'    => $category_id,
							'product_id'     => $product_id,
							'type_id'        => $type_id,
							'product_price'  => digit_val($dis_price_val),
							'enret_qty'      => $dis_product_qty,
							'product_qty'    => $dis_product_qty,
							'receive_qty'    => '0',
							'product_unit'   => $dis_unit_id,
							'financial_year' => $active_financial,
				    		'createdate'     => $c_date
						);

						$type_insert = $this->distributorpurchase_model->distributorDeliveryDetails_insert($value);
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
			else
			{
				$response['status']  = 0;
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// Manage Purchase
		// ***************************************************
		public function manage_delivery($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listDeliveryPaginate')
			{
				$limit       = $this->input->post('limit');
	    		$offset      = $this->input->post('offset');
	    		$financ_year = $this->input->post('financial_year');

	    		if(!empty($financ_year))
	    		{
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

					$search    = $this->input->post('search');
					$load_data = $this->input->post('load_data');

		    		if($search !='')
		    		{
		    			$like['name']     = $search;
		    		}
		    		else
		    		{
		    			$like = [];
		    		}

		    		if($load_data != '')
		    		{
		    			$where = array(
		    				'order_status'   => $load_data,
		    				// 'financial_year' => $financ_year,
		    				'published'      => '1'
		    			);
		    		}
		    		else
		    		{
		    			$where = array(
		    				// 'financial_year' => $financ_year,
		    				'published'      => '1'
		    			);
		    		}

		    		$column = 'id';
					$overalldatas = $this->distributorpurchase_model->getDistributorDelivery($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->distributorpurchase_model->getDistributorDelivery($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$purchase_list = [];
						foreach ($data_list as $key => $value) {
							$order_id       = !empty($value->id)?$value->id:'';
				            $order_no       = !empty($value->order_no)?$value->order_no:'';
				            $distributor_id = !empty($value->distributor_id)?$value->distributor_id:'';
				            $order_date     = !empty($value->order_date)?$value->order_date:'';
				            $order_status   = !empty($value->order_status)?$value->order_status:'';
				            $_ordered       = !empty($value->_ordered)?$value->_ordered:'';
				            $financial_year = !empty($value->financial_year)?$value->financial_year:'';
				            $bill           = !empty($value->bill)?$value->bill:'';
				            $dc_no          = !empty($value->dc_no)?$value->dc_no:'';
				            $published      = !empty($value->published)?$value->published:'';
				            $status         = !empty($value->status)?$value->status:'';
				            $createdate     = !empty($value->createdate)?$value->createdate:'';

				            // Distributor Details
				            $where_1 = array(
				            	'id' => $distributor_id,
				            );

				            $column_1 = 'company_name';

							$distri_data  = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column_1);

							$company_name = !empty($distri_data[0]->company_name)?$distri_data[0]->company_name:'';

				            $purchase_list[] = array(
				            	'order_id'       => $order_id,
				            	'order_no'       => $order_no,
				            	'distributor_id' => $distributor_id,
				            	'company_name'   => $company_name,
				            	'order_date'     => $order_date,
				            	'order_status'   => $order_status,
				            	'_ordered'       => $_ordered,
				            	'financial_year' => $financial_year,
				            	'bill'           => $bill,
				            	'dc_no'          => $dc_no,
				            	'published'      => $published,
				            	'status'         => $status,
				            	'createdate'	 => $createdate,
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
				        $response['data']         = $purchase_list;
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

			else if($method == '_listDistributorDeliveryPaginate')
			{
				$limit          = $this->input->post('limit');
	    		$offset         = $this->input->post('offset');
	    		$financ_year    = $this->input->post('financial_year');
	    		$distributor_id = $this->input->post('distributor_id');

	    		$error = FALSE;
			    $errors = array();
				$required = array('financial_year', 'distributor_id');
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

					$search    = $this->input->post('search');
					$load_data = $this->input->post('load_data');

		    		if($search !='')
		    		{
		    			$like['name']     = $search;
		    		}
		    		else
		    		{
		    			$like = [];
		    		}

		    		if($load_data != '')
		    		{
		    			$where = array(
		    				'distributor_id' => $distributor_id,
		    				'order_status'   => $load_data,
		    				// 'financial_year' => $financ_year,
		    				'published'      => '1'
		    			);
		    		}
		    		else
		    		{
		    			$where = array(
		    				'distributor_id' => $distributor_id,
		    				// 'financial_year' => $financ_year,
		    				'published'      => '1'
		    			);
		    		}

		    		$column = 'id';
					$overalldatas = $this->distributorpurchase_model->getDistributorDelivery($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->distributorpurchase_model->getDistributorDelivery($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$purchase_list = [];
						foreach ($data_list as $key => $value) {
							$order_id       = !empty($value->id)?$value->id:'';
				            $order_no       = !empty($value->order_no)?$value->order_no:'';
				            $distributor_id = !empty($value->distributor_id)?$value->distributor_id:'';
				            $order_date     = !empty($value->order_date)?$value->order_date:'';
				            $order_status   = !empty($value->order_status)?$value->order_status:'';
				            $_ordered       = !empty($value->_ordered)?$value->_ordered:'';
				            $financial_year = !empty($value->financial_year)?$value->financial_year:'';
				            $bill           = !empty($value->bill)?$value->bill:'';
				            $dc_no     = !empty($value->dc_no)?$value->dc_no:'';
				            $published      = !empty($value->published)?$value->published:'';
				            $status         = !empty($value->status)?$value->status:'';
				            $createdate     = !empty($value->createdate)?$value->createdate:'';

				            // Distributor Details
				            $where_1 = array(
				            	'id' => $distributor_id,
				            );

				            $column_1 = 'company_name';

							$distri_data  = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column_1);

							$company_name = !empty($distri_data[0]->company_name)?$distri_data[0]->company_name:'';

				            $purchase_list[] = array(
				            	'order_id'       => $order_id,
				            	'order_no'       => $order_no,
				            	'distributor_id' => $distributor_id,
				            	'company_name'   => $company_name,
				            	'order_date'     => $order_date,
				            	'order_status'   => $order_status,
				            	'_ordered'       => $_ordered,
				            	'financial_year' => $financial_year,
				            	'bill'           => $bill,
				            	'dc_no'     => $dc_no,
				            	'published'      => $published,
				            	'status'         => $status,
				            	'createdate'	 => $createdate,
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
				        $response['data']         = $purchase_list;
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

			else if($method == '_viewDistributorDelivery')
			{
				$order_id       = $this->input->post('order_id');
				$distributor_id = $this->input->post('distributor_id');
				$view_type      = $this->input->post('view_type');

				
				$error    = FALSE;
			    $errors   = array();
				$required = array('order_id', 'view_type');
				if($view_type == 2)
			    {
			    	array_push($required, 'distributor_id');
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
			    if(count($errors)==0)
			    {
					// Bill Details
					if($view_type == 1)
					{
						$where_1 = array(
							'id'             => $order_id,
							'published'      => '1',
							'status'         => '1',
						);
					}
					else
					{
						$where_1 = array(
							'id'             => $order_id,
							'distributor_id' => $distributor_id,
							'published'      => '1',
							'status'         => '1',
						);
					}

					$bill_data = $this->distributorpurchase_model->getDistributorDelivery($where_1);

					if($bill_data)
					{
						$bill_val       = $bill_data[0];
						$order_id       = !empty($bill_val->id)?$bill_val->id:'';
			            $order_no       = !empty($bill_val->order_no)?$bill_val->order_no:'';
			            $distributor_id = !empty($bill_val->distributor_id)?$bill_val->distributor_id:'';
			            $order_date     = !empty($bill_val->order_date)?$bill_val->order_date:'';
			            $order_status   = !empty($bill_val->order_status)?$bill_val->order_status:'';
			            $_ordered       = !empty($bill_val->_ordered)?$bill_val->_ordered:'';
			            $_processing    = !empty($bill_val->_processing)?$bill_val->_processing:'';
			            $_packing       = !empty($bill_val->_packing)?$bill_val->_packing:'';
			            $_shiped        = !empty($bill_val->_shiped)?$bill_val->_shiped:'';
			            $_delivery      = !empty($bill_val->_delivery)?$bill_val->_delivery:'';
			            $_complete      = !empty($bill_val->_complete)?$bill_val->_complete:'';
			            $_canceled      = !empty($bill_val->_canceled)?$bill_val->_canceled:'';
			            $reason         = !empty($bill_val->reason)?$bill_val->reason:'';
						$dc_id          = !empty($bill_val->dc_id)?$bill_val->dc_id:'';
						$dc_no          = !empty($bill_val->dc_no)?$bill_val->dc_no:'';
						$dc_random      = !empty($bill_val->dc_random)?$bill_val->dc_random:'';

			            // Bill Details
			            $bill_details = array(
			            	'order_id'       => $order_id,
							'order_no'       => $order_no,
							'distributor_id' => $distributor_id,
							'order_date'     => $order_date,
							'order_status'   => $order_status,
							'_ordered'       => $_ordered,
							'_processing'    => $_processing,
							'_packing'       => $_packing,
							'_shiped'        => $_shiped,
							'_delivery'      => $_delivery,
							'_complete'      => $_complete,
							'_canceled'      => $_canceled,
							'reason'         => $reason,
							'dc_id'          => $dc_id,
							'dc_no'          => $dc_no,
							'dc_random'      => $dc_random,
			            );

			            // Distributor Details
			            $where_1 = array(
			            	'id' => $distributor_id,
			            );

			            $column_1 = 'company_name, mobile, email, gst_no, address, state_id';

						$distri_data  = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column_1);

						$distri_val   = $distri_data[0];
						$company_name = !empty($distri_val->company_name)?$distri_val->company_name:'';
					    $mobile       = !empty($distri_val->mobile)?$distri_val->mobile:'';
					    $email        = !empty($distri_val->email)?$distri_val->email:'';
					    $gst_no       = !empty($distri_val->gst_no)?$distri_val->gst_no:'';
					    $address      = !empty($distri_val->address)?$distri_val->address:'';
					    $state_id     = !empty($distri_val->state_id)?$distri_val->state_id:'';

					    $distributor_details = array(
					    	'company_name' => $company_name,
						    'mobile'       => $mobile,
						    'email'        => $email,
						    'gst_no'       => $gst_no,
						    'address'      => $address,
						    'state_id'     => $state_id,
					    );

					    // Admin Details
					    $where_2 = array(
					    	'id' => '1'
					    );

					    $column_2 = 'username, mobile, address, gst_no, state_id';

					    $admin_data  = $this->login_model->getLoginStatus($where_2, '', '', 'result', '', '', '', '', $column_2);

					    $admin_val   = $admin_data[0];

					    $username = !empty($admin_val->username)?$admin_val->username:'';
			            $mobile   = !empty($admin_val->mobile)?$admin_val->mobile:'';
			            $address  = !empty($admin_val->address)?$admin_val->address:'';
			            $gst_no   = !empty($admin_val->gst_no)?$admin_val->gst_no:'';
			            $state_id = !empty($admin_val->state_id)?$admin_val->state_id:'';

			            $admin_details = array(
			            	'username' => $username,
			            	'mobile'   => $mobile,
			            	'address'  => $address,
			            	'gst_no'   => $gst_no,
			            	'state_id' => $state_id,
			            );

			            // Order Details
			            $where_3 = array(
			            	'order_id'         => $order_id,
			            	'delete_status' => '1',
			            	'status'        => '1',
			            	'published'     => '1',
			            );

			            $order_data  = $this->distributorpurchase_model->getDistributorDeliveryDetails($where_3);

			            $order_details = [];
			            foreach ($order_data as $key => $value) {
			            	
			            	$auto_id         = !empty($value->id)?$value->id:'';
				            $order_id        = !empty($value->order_id)?$value->order_id:'';
				            $assproduct_id   = !empty($value->assproduct_id)?$value->assproduct_id:'';
				            $category_id     = !empty($value->category_id)?$value->category_id:'';
				            $product_id      = !empty($value->product_id)?$value->product_id:'';
				            $type_id         = !empty($value->type_id)?$value->type_id:'';
				            $product_price   = !empty($value->product_price)?$value->product_price:'0';
				            $product_qty     = !empty($value->product_qty)?$value->product_qty:'0';
				            $receive_qty     = !empty($value->receive_qty)?$value->receive_qty:'0';
				            $product_unit    = !empty($value->product_unit)?$value->product_unit:'';
				            $product_type    = !empty($value->product_type)?$value->product_type:'';
				            $product_process = !empty($value->product_process)?$value->product_process:'';
				            $pack_status     = !empty($value->pack_status)?$value->pack_status:'';

				            // Assign Product Details
				            $where_4 = array(
					    		'id' => $assproduct_id,
					    	);

				            $column_4 = 'description';

				            $assProduct_data = $this->assignproduct_model->getAssignProductDetails($where_4, '', '', 'result', '', '', '', '', $column_4);

				            $assProduct_val = $assProduct_data[0];
				            $description    = !empty($assProduct_val->description)?$assProduct_val->description:'';

				            // Unit Details
				            $where_5 = array(
					    		'id' => $product_unit,
					    	);

					    	$data_val      = $this->commom_model->getUnit($where_5);
					    	$unit_name     = isset($data_val[0]->name)?$data_val[0]->name:'';

					    	// Product Details
					    	$where_6 = array(
					    		'id' => $product_id,
					    	);

					    	$column_6 = 'hsn_code, gst';

					    	$product_data = $this->commom_model->getProduct($where_6, '', '', 'result', '', '', '', '', $column_6);

					    	$product_val  = $product_data[0];

					    	$hsn_code = !empty($product_val->hsn_code)?$product_val->hsn_code:'';
            				$gst_val  = !empty($product_val->gst)?$product_val->gst:'0';

            				// Product Type Details
            				$where_7 = array(
					    		'id' => $type_id,
					    	);
					    	
					    	$column_7 = 'product_stock';

					    	$pdtType_data = $this->commom_model->getProductType($where_7, '', '', 'row', '', '', '', '', $column_7);

					    	$pdtType_stk  = !empty($pdtType_data->product_stock)?$pdtType_data->product_stock:'0';

				            $order_details[] = array(
				            	'auto_id'         => $auto_id,
								'order_id'        => $order_id,
								'assproduct_id'   => $assproduct_id,
								'description'     => $description,
								'category_id'     => $category_id,
								'product_id'      => $product_id,
								'type_id'         => $type_id,
								'product_price'   => $product_price,
								'product_qty'     => $product_qty,
								'receive_qty'     => $receive_qty,
								'product_unit'    => $product_unit,
								'unit_name'       => $unit_name,
								'hsn_code'        => $hsn_code,
								'gst_val'         => $gst_val,
								'product_type'    => $product_type,
								'product_stock'   => $pdtType_stk,
								'product_process' => $product_process,
								'pack_status'     => $pack_status,
				            );
			            }

			            $purchase_details = array(
			            	'bill_details'        => $bill_details,
			            	'admin_details'       => $admin_details,
			            	'distributor_details' => $distributor_details,
			            	'order_details'       => $order_details,
			            );

			            $response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $purchase_details;
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

			else
			{
				$response['status']  = 0;
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// Order Process
		// ***************************************************
		public function order_process($param1="",$param2="",$param3="")
		{	
			$method     = $this->input->post('method');
			$auto_id    = $this->input->post('auto_id');
			$invoice_id = $this->input->post('invoice_id');
			$price      = $this->input->post('price');
			$quantity   = $this->input->post('quantity');
			$progress   = $this->input->post('progress');
			$reason     = $this->input->post('reason');
			$length     = $this->input->post('length');
		    $breadth    = $this->input->post('breadth');
		    $height     = $this->input->post('height');
		    $weight     = $this->input->post('weight');
			$rand_val   = generateRandomString(32);
			$date       = date('Y-m-d');
			$time       = date('H:i:s');
			$c_date     = date('Y-m-d H:i:s');

			// Financial Year Details
			$option['order_by']   = 'id';
			$option['disp_order'] = 'DESC';

			$where = array(
				'status'    => '1', 
				'published' => '1',
			);

			$data_list = $this->commom_model->getfinancial($where, '1', '0', 'result', '', '', $option);

			$financial_id = !empty($data_list[0]->id)?$data_list[0]->id:'';

			if($method == '_updateOrderProgress')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('auto_id', 'progress');
				if($progress == 8)
				{
					array_push($required, 'reason');
				}
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
			    	// Order Details
			    	$bill_where = array(
			    		'id'        => $auto_id,
			    		'status'    => '1',
			    		'published' => '1',
			    	);

			    	$bill_col   = 'order_status';
			    	$bill_det   = $this->distributorpurchase_model->getDistributorDelivery($bill_where, '', '', 'result', '', '', '', '', $bill_col);
			    	$ord_status = !empty($bill_det[0]->order_status)?$bill_det[0]->order_status:'';

			    	if($ord_status != $progress)
			    	{
			    		// Success => 1
			    		if($progress == '1')
				    	{
				    		$update_data = array(
				    			'order_status' => $progress,
				    			'_ordered'     => date('Y-m-d H:i:s'),
				    		);
				    	}

				    	// Approved => 2
				    	else if($progress == '2')
				    	{
				    		$update_data = array(
				    			'order_status' => $progress,
				    			'_processing'  => date('Y-m-d H:i:s'),
				    		);
				    	}

				    	// Packing => 3
				    	else if($progress == '3')
				    	{
				    		$update_data = array(
				    			'order_status' => $progress,
				    			'_packing'     => date('Y-m-d H:i:s'),
				    		);
				    	}

				    	// Invoice => 4
				    	else if($progress == '4')
				    	{
				    		$whr_one = array(
				    			'po_id'         => $auto_id,
				    			'delete_status' => '1',
				    			'published'     => '1',
							    'status'        => '1',
				    		);

				    		$value_one = $this->distributorpurchase_model->getDistributorPurchaseDetails($whr_one,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				            $count_one = !empty($value_one[0]->autoid)?$value_one[0]->autoid:'0';

				            $whr_two = array(
				    			'po_id'         => $auto_id,
				    			'pack_status'   => '2',
				    			'delete_status' => '1',
				    			'published'     => '1',
							    'status'        => '1',
				    		);

				    		$value_two = $this->distributorpurchase_model->getDistributorPurchaseDetails($whr_two,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				            $count_two = !empty($value_two[0]->autoid)?$value_two[0]->autoid:'0';

				            if($count_one == $count_two)
				            {
				            	// Create Distributor Invoice
					    		$where_1 = array(
					    			'id'        => $auto_id,
						    		'published' => '1',
						    		'status'    => '1',
					    		);

					    		$column_1 = 'id, distributor_id, financial_year';

					    		$value_1  = $this->distributorpurchase_model->getDistributorPurchase($where_1, '', '', 'result', '', '', '', '', $column_1);		    		

					    		$order_val = $value_1[0];
					    		$order_id  = !empty($order_val->id)?$order_val->id:'';
							    $distri_id = !empty($order_val->distributor_id)?$order_val->distributor_id:'';
							    $fin_year  = !empty($order_val->financial_year)?$order_val->financial_year:'';

							    // Distributor Details
					    		$where_2 = array(
					    			'id' => $distri_id,
					    		);

					    		$column_2 = 'due_days';

					    		$value_2  = $this->distributors_model->getDistributors($where_2, '', '', 'result', '', '', '', '', $column_2);

					    		$dist_val = $value_2[0];
					    		$due_days = !empty($dist_val->due_days)?$dist_val->due_days:'';

					    		// Invoice Details
					    		$inv_whr = array(
					    			'financial_year' => $fin_year,
					    			'published'      => '1',
					    			'status'         => '1',
					    		);

					    		$bill_val  = $this->invoice_model->getDistributorInvoice($inv_whr,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

					    		$count_val  = leadingZeros($bill_val[0]->autoid, 5);
				            	$invoice_no = 'INV'.$count_val;

				            	$distributorInvoice_data = array(
					    			'order_id'       => $order_id,
					    			'invoice_no'     => $invoice_no,
					    			'distributor_id' => $distri_id,
					    			'due_days'       => $due_days,
					    			'financial_year' => $fin_year,
					    			'random_value'   => $rand_val,
					    			'date'           => $date,
					    			'time'           => $time,
					    			'createdate'     => $c_date,
					    		);

					    		$dis_insert = $this->invoice_model->distributorInvoice_insert($distributorInvoice_data);

					    		$where_3 = array(
					    			'po_id'         => $auto_id,
					    			'delete_status' => '1',
						    		'published'     => '1',
						    		'status'        => '1',
					    		);

					    		$column_3 = 'id, po_id, assproduct_id, product_id, type_id, product_price, product_qty, product_unit, product_type';

					    		$value_3  = $this->distributorpurchase_model->getDistributorPurchaseDetails($where_3, '', '', 'result', '', '', '', '', $column_3);

					    		foreach ($value_3 as $key => $value) {

					    			$po_auto_id    = !empty($value->id)?$value->id:'';
								    $po_id         = !empty($value->po_id)?$value->po_id:'';
								    $assproduct_id = !empty($value->assproduct_id)?$value->assproduct_id:'';
								    $product_id    = !empty($value->product_id)?$value->product_id:'';
								    $type_id       = !empty($value->type_id)?$value->type_id:'';
								    $product_price = !empty($value->product_price)?$value->product_price:'';
								    $product_qty   = !empty($value->product_qty)?$value->product_qty:'';
								    $product_unit  = !empty($value->product_unit)?$value->product_unit:'';
								    $product_type  = !empty($value->product_type)?$value->product_type:'';

								    // Product Details
								    $where_4 = array(
								    	'id'        => $product_id,
								    	'published' => '1',
								    );

								    $column_4 = 'hsn_code, gst';

								    $value_4  = $this->commom_model->getProduct($where_4, '', '', 'result', '', '', '', '', $column_4);

								    $pdt_val  = $value_4[0];

								    $hsn_code = !empty($pdt_val->hsn_code)?$pdt_val->hsn_code:'';
								    $gst_val  = !empty($pdt_val->gst)?$pdt_val->gst:'';

								    // Product stock details
								    $whr_2  = array(
								    	'id'        => $type_id,
								    	'published' => '1',
								    );

								    $col_2  = 'id, product_type, product_stock, view_stock';

								    $res_2  = $this->commom_model->getProductType($whr_2, '', '', 'result', '', '', '', '', $col_2);

								    $data_2 = $res_2[0];

						            $pdt_type   = !empty($data_2->product_type)?$data_2->product_type:'';
						            $pdt_stock  = !empty($data_2->product_stock)?$data_2->product_stock:'';
						            $view_stock = !empty($data_2->view_stock)?$data_2->view_stock:'';

						            // View Stock
							    	if($product_unit == 1 || $product_unit == 11)
						    		{
						    			$multiple_stk   = $product_qty * $pdt_type; // 5 X 1 = 5 Kg
						    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
						    			$received_stock = $product_qty; // 5 Kg
						    		}
						    		else if($product_unit == 2 || $product_unit == 4)
						    		{
						    			$product_stock  = $product_qty * $pdt_type; // 5 X 100 = 500 Gram
						    			$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
						    			$received_stock = number_format($received_value, 2);
						    		}
						    		else
						    		{
						    			$product_stock  = $product_qty * $pdt_type; // 5 X 1 = 5 Nos
						    			$received_stock = $product_qty; // 5 Nos
						    		}

					    			// Order Stock Details Insert
						    		$ins_data = array(
								    	'po_id'          => $po_id,
								    	'po_auto_id'     => $po_auto_id,
								    	'distributor_id' => $distri_id,
								    	'assproduct_id'  => $assproduct_id,
								    	'product_id'     => $product_id,
								    	'type_id'        => $type_id,
								    	'product_unit'   => $product_unit,
								    	'received_qty'   => $product_qty,
								    	'received_date'  => date('Y-m-d'),
								    	'createdate'     => date('Y-m-d H:i:s')
								    );

								    $insert = $this->distributorpurchase_model->distributorPurchaseStkDetails_insert($ins_data);

								    $distributorInvoice_details = array(
								    	'invoice_id'     => $dis_insert,
								    	'invoice_no'     => $invoice_no,
								    	'product_id'     => $product_id,
								    	'type_id'        => $type_id,
								    	'distributor_id' => $distri_id,
								    	'hsn_code'       => $hsn_code,
								    	'gst_val'        => $gst_val,
								    	'unit_val'       => $product_unit,
								    	'price'          => $product_price,
								    	'order_qty'      => $product_qty,
								    	'createdate'     => $c_date,
								    );

								    $insert_det = $this->invoice_model->distributorInvoiceDetails_insert($distributorInvoice_details);
					    		}

				            	// Order Details
			        			$where_5 = array(
			        				'po_id'         => $auto_id,
			        				'delete_status' => '1',
			        				'published'     => '1',
			        				'status'        => '1',
			        			);

						    	$order_data = $this->distributorpurchase_model->getDistributorPurchaseDetails($where_5);

						    	if(!empty($order_data))
						    	{
						    		$total_val = 0;
							    	foreach ($order_data as $key => $value_5)
							    	{
							    		$product_price = !empty($value_5->product_price)?$value_5->product_price:'';
	    								$product_qty   = !empty($value_5->product_qty)?$value_5->product_qty:'';
	    								$total_amt     = $product_qty * $product_price;
								    	$total_val     += $total_amt;
							    	}

							    	// Bill Details
									$where_6 = array(
										'id'        => $auto_id,
										'published' => '1',
										'status'    => '1',
									);

									$column_6 = 'po_no, distributor_id';

									$bill_data = $this->distributorpurchase_model->getDistributorPurchase($where_6, '', '', 'result', '', '', '', '', $column_6);

									$po_no   = !empty($bill_data[0]->po_no)?$bill_data[0]->po_no:'';
	            					$dist_id = !empty($bill_data[0]->distributor_id)?$bill_data[0]->distributor_id:'';

	            					// Distributor Details
	            					$where_7 = array(
	            						'id' => $dist_id,
	            					);

	            					$column_7  = 'company_name, discount, credit_limit, available_limit, pre_limit, current_balance';

	            					$dist_data = $this->distributors_model->getDistributors($where_7, '', '', 'result', '', '', '', '', $column_7);

	            					$company_name    = !empty($dist_data[0]->company_name)?$dist_data[0]->company_name:'';
						            $discount        = !empty($dist_data[0]->discount)?$dist_data[0]->discount:'0';
						            $credit_limit    = !empty($dist_data[0]->credit_limit)?$dist_data[0]->credit_limit:'0';
						            $available_limit = !empty($dist_data[0]->available_limit)?$dist_data[0]->available_limit:'0';
						            $pre_limit       = !empty($dist_data[0]->pre_limit)?$dist_data[0]->pre_limit:'0';
						            $current_balance = !empty($dist_data[0]->current_balance)?$dist_data[0]->current_balance:'0';

						            $bill_amount = 0;
									$bill_value  = round($total_val);
									if($discount != 0)
									{
										$amount_val  = round($total_val);
										$bill_amount = $amount_val * $discount / 100;
										$bill_value  = round($bill_amount);
									}

									if($available_limit >= $bill_value)
									{
										$new_avl_bal = $available_limit - $bill_value;
							            $new_cur_bal = $current_balance + $bill_value;

										$distributor_data = array(
							            	'available_limit' => $new_avl_bal,
							            	'current_balance' => $new_cur_bal,
							            );

							            $dist_whr = array('id' => $dist_id);
					    				$dist_upt = $this->distributors_model->distributors_update($distributor_data, $dist_whr);

					    				// Distributor wise balance sheet
					    				$balance_data = array(
					    					'distributor_id' => $dist_id,
					    					'bill_code'      => 'INV',
					    					'bill_id'        => $auto_id,
					    					'bill_no'        => $invoice_no,
					    					'pre_bal'        => '0',
					    					'cur_bal'        => $bill_value,
					    					'amount'         => round($total_val),
					    					'bal_amt'        => round($total_val),
					    					'pay_type'       => '4',
					    					'financial_id'   => $financial_id,
					    					'date'           => date('Y-m-d'),
					    					'time'           => date('H:i:s'),
					    					'createdate'     => date('Y-m-d H:i:s'),
					    				);

					    				$balance_insert = $this->payment_model->distributorPaymentDetails_insert($balance_data);

					    				// Balance Sheet
					    				$ins_data = array(
					    					'distributor_id'  => $dist_id,
					    					'bill_code'       => 'INV',
					    					'bill_id'         => $auto_id,
					    					'bill_no'         => $invoice_no,
					    					'available_limit' => strval($available_limit),
					    					'pre_bal'         => strval($current_balance),
					    					'cur_bal'         => strval($new_cur_bal),
					    					'amount'          => round($total_val),
					    					'discount'        => $bill_amount,
					    					'value_type'      => 1,
					    					'financial_id'    => $financial_id,
					    					'date'            => date('Y-m-d'),
					    					'time'            => date('H:i:s'),
					    					'createdate'      => date('Y-m-d H:i:s'),
					    				);

					    				$payment_insert = $this->payment_model->distributorPayment_insert($ins_data);

					    				$update_data = array(
					    					'invoice_id'     => $dis_insert,
				    						'invoice_no'     => $invoice_no,
				    						'invoice_random' => $rand_val,
							    			'order_status'   => $progress,
							    			'_invoice'       => date('Y-m-d H:i:s'),
							    		);
									}
									else
									{
										$response['status']  = 0;
								        $response['message'] = "Invalide Balance Value"; 
								        $response['data']    = [];
								        echo json_encode($response);
								        return;	
									}
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
						        $response['message'] = "Please fill order quantity"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return;
				            }
				    	}

				    	// Completed => 5
				    	else if($progress == '5')
				    	{
				    		$whr_1 = array(
			                    'po_id'         => $auto_id,
			                    'pack_status'   => '2',
			                    'delete_status' => '1',
			                    'published'     => '1',
			                );

			                $col_1 = 'id, po_id, assproduct_id, product_id, type_id, product_price, product_qty, product_unit, product_type';
			                $res_1 = $this->distributorpurchase_model->getDistributorPurchaseDetails($whr_1, '', '', 'result', '', '', '', '', $col_1);

			                if($res_1)
			                {
			                	foreach ($res_1 as $key => $val_1) {
			                		$po_auto_id    = !empty($val_1->id)?$val_1->id:'';
								    $po_id         = !empty($val_1->po_id)?$val_1->po_id:'';
								    $assproduct_id = !empty($val_1->assproduct_id)?$val_1->assproduct_id:'';
								    $product_id    = !empty($val_1->product_id)?$val_1->product_id:'';
								    $type_id       = !empty($val_1->type_id)?$val_1->type_id:'';
								    $product_price = !empty($val_1->product_price)?$val_1->product_price:'';
								    $product_qty   = !empty($val_1->product_qty)?$val_1->product_qty:'0';
								    $product_unit  = !empty($val_1->product_unit)?$val_1->product_unit:'';

								    // Product stock details
			                        $whr_2  = array(
			                            'id'        => $type_id,
			                            'published' => '1',
			                        );

			                        $col_2  = 'id, product_type, product_stock, view_stock';

			                        $res_2  = $this->commom_model->getProductType($whr_2, '', '', 'result', '', '', '', '', $col_2);

			                        $data_2 = $res_2[0];

			                        $pdt_type   = !empty($data_2->product_type)?$data_2->product_type:'';
			                        $pdt_stock  = !empty($data_2->product_stock)?$data_2->product_stock:'0';
			                        $view_stock = !empty($data_2->view_stock)?$data_2->view_stock:'0';

			                        // View Stock
									if($product_unit == 1 || $product_unit == 11)
			                        {
			                            $multiple_stk   = $product_qty * $pdt_type; // 5 X 1 = 5 Kg
			                            $product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
			                            $received_stock = $product_qty; // 5 Kg
			                        }
									else if($product_unit == 2 || $product_unit == 4)
			                        {
			                            $product_stock  = $product_qty * $pdt_type; // 5 X 100 = 500 Gram
			                            $received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
			                            $received_stock = number_format($received_value, 2);
			                        }
			                        else
			                        {
			                            $product_stock  = $product_qty * $pdt_type; // 5 X 1 = 5 Nos
			                            $received_stock = $product_qty; // 5 Nos
			                        }

			                        // Distributor Stock Process
								    $whr_3  = array(
								    	'id'        => $assproduct_id,
								    	'published' => '1',
								    );

								    $col_3  = 'stock, view_stock';

								    $res_3  = $this->assignproduct_model->getAssignProductDetails($whr_3, '', '', 'result', '', '', '', '', $col_3);

								    $data_3 = $res_3[0];

								    $stock_val    = !empty($data_3->stock)?$data_3->stock:'0';
					    			$viewStk_val  = !empty($data_3->view_stock)?$data_3->view_stock:'0';

					    			$pdtStock_val  = $stock_val + $received_stock;
						    		$viewStock_val = $viewStk_val + $product_stock;

						    		$stock_data = array(
						    			'stock'      => strval($pdtStock_val),
						    			'view_stock' => strval($viewStock_val),
						    		);

						    		$stock_whr  = array('id' => $assproduct_id);
					    			$update_prodc = $this->assignproduct_model->assignProductDetails_update($stock_data, $stock_whr);
			                	}
			                }

				    		$update_data = array(
				    			'order_status' => $progress,
				    			'_complete'    => date('Y-m-d H:i:s'),
				    		);
				    	}

				    	// Cancel Invoice => 7
				    	else if($progress == '7')
				    	{
				    		$inv_whr = array('order_id'  => $auto_id);
				    		$inv_col = 'id, distributor_id';
				    		$inv_val = $this->invoice_model->getDistributorInvoice($inv_whr, '', '', 'result', '', '', '', '', $inv_col);

				    		$inv_id = !empty($inv_val[0]->id)?$inv_val[0]->id:'';
							$dis_id = !empty($inv_val[0]->distributor_id)?$inv_val[0]->distributor_id:'';

							// Payment Details
							$whr_one = array('bill_id' => $auto_id, 'distributor_id' => $dis_id);
							$col_one = 'amount';
							$res_one = $this->payment_model->getDistributorPaymentDetails($whr_one, '', '', 'result', '', '', '', '', $col_one);
							$amt_res = !empty($res_one[0]->amount)?$res_one[0]->amount:'0';

							// Payment Data
							$whr_two = array(
								'bill_code'      => 'PAY', 
								'bill_id'        => $auto_id, 
								'distributor_id' => $dis_id, 
								'published'      => '1',
							);

							$col_two = 'amount, discount';

							$res_two = $this->payment_model->getDistributorPayment($whr_two, '', '', 'result', '', '', '', '', $col_two);

							$tot_amt = 0;
							if($res_two)
							{
								foreach ($res_two as $key => $val_2) {
									$amount   = !empty($val_2->amount)?$val_2->amount:'0';
									$discount = !empty($val_2->discount)?$val_2->discount:'0';
									$amt_val  = $amount + $discount;
									$tot_amt += $amt_val;
								}
							}


							// Distributor Balance Details
							$whr_three = array('id' => $dis_id);
							$col_three = 'available_limit, current_balance';
							$res_three = $this->distributors_model->getDistributors($whr_three, '', '', 'result', '', '', '', '', $col_three);

							$avl_limit   = !empty($res_three[0]->available_limit)?$res_three[0]->available_limit:'0';
            				$cur_balance = !empty($res_three[0]->current_balance)?$res_three[0]->current_balance:'0';

            				$invoice_amt = $amt_res - $tot_amt;
            				$new_avl_bal = $avl_limit + $invoice_amt;
			            	$new_cur_bal = $cur_balance - $invoice_amt;

            				$bal_val = array(
								'available_limit' => $new_avl_bal,
    							'current_balance' => $new_cur_bal,
							);

							$bal_whr = array('id' => $dis_id);
							$bal_upt = $this->distributors_model->distributors_update($bal_val, $bal_whr);

							// Invoice Details
							$whr_four = array(
								'invoice_id'    => $inv_id, 
								'cancel_status' => '1', 
								'published'     => '1'
							);

							$col_four = 'product_id, type_id, order_qty, unit_val';

							$ord_val  = $this->invoice_model->getDistributorInvoiceDetails($whr_four, '', '', 'result', '', '', '', '', $col_four);

							if($ord_val)
							{
								foreach ($ord_val as $key => $val_4) {
									$pdt_id   = !empty($val_4->product_id)?$val_4->product_id:'';
						            $type_id  = !empty($val_4->type_id)?$val_4->type_id:'';
						            $ord_qty  = !empty($val_4->order_qty)?$val_4->order_qty:'0';
						            $unit_val = !empty($val_4->unit_val)?$val_4->unit_val:'';

						            // Product Type Details
									$type_whr  = array('id' => $type_id);
									$type_col  = 'product_type, product_stock, view_stock';
									$type_data = $this->commom_model->getProductType($type_whr, '', '', 'result', '', '', '', '', $type_col);

									$pdt_type  = !empty($type_data[0]->product_type)?$type_data[0]->product_type:'0';
									$stock_val = !empty($type_data[0]->product_stock)?$type_data[0]->product_stock:'0';
									$view_stk  = !empty($type_data[0]->view_stock)?$type_data[0]->view_stock:'0';

									// View Stock
							    	if($unit_val == 1)
						    		{
						    			$multiple_stk   = $ord_qty * $pdt_type; // 5 X 1 = 5 Kg
						    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
						    			$received_stock = $ord_qty; // 5 Kg
						    		}
						    		else if($unit_val == 2)
						    		{
						    			$product_stock  = $ord_qty * $pdt_type; // 5 X 100 = 500 Gram
						    			$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
						    			$received_stock = number_format($received_value, 2);
						    		}
						    		else
						    		{
						    			$product_stock  = $ord_qty * $pdt_type; // 5 X 1 = 5 Nos
						    			$received_stock = $ord_qty; // 5 Nos
						    		}

						    		// Product Stock Update
						    		$new_type_stock    = $stock_val + $received_stock;
						    		$new_type_view_stk = $view_stk + $product_stock;

						    		$stock_data = array(
						    			'product_stock' => $new_type_stock,
						    			'view_stock'    => $new_type_view_stk,
						    		);

						    		$stock_val = array('id' => $type_id);
						    		$stock_upt = $this->commom_model->productType_update($stock_data, $stock_val);

						    		// Distributor Product Type Details
						    		$disPdt_whr  = array(
						    			'distributor_id' => $dis_id, 
						    			'product_id'     => $pdt_id,
						    			'type_id'        => $type_id,
						    			'published'      => '1',
						    			'status'         => '1'
						    		);

						    		$disPdt_col = 'id, stock, view_stock';
						    		$disPdt_val = $this->assignproduct_model->getAssignProductDetails($disPdt_whr, '', '', 'result', '', '', '', '', $disPdt_col);

						    		$disPdt_id   = !empty($disPdt_val[0]->id)?$disPdt_val[0]->id:'0';
						    		$disPdt_stk  = !empty($disPdt_val[0]->stock)?$disPdt_val[0]->stock:'0';
									$disPdt_view = !empty($disPdt_val[0]->view_stock)?$disPdt_val[0]->view_stock:'0';

									// Product Stock Update
						    		$new_type_stock    = $disPdt_stk - $received_stock;
						    		$new_type_view_stk = $disPdt_view - $product_stock;

						    		$disUpt_data = array(
						    			'stock'      => $new_type_stock,
						    			'view_stock' => $new_type_view_stk,
						    		);

						    		$disUpt_whr  = array('id' => $type_id);
						    		$disUpt_val  = $this->assignproduct_model->assignProductDetails_update($disUpt_data, $disUpt_whr);
								}
							}

				    		// Invoice Update
				    		$val_one  = array('cancel_status' => '2');
		    				$data_one = array('id' => $inv_id);
		    				$upt_one  = $this->invoice_model->distributorInvoice_update($val_one, $data_one);

		    				// Invocie Details Update
		    				$val_two  = array('cancel_status' => '2');
		    				$data_two = array('invoice_id' => $inv_id);
		    				$upt_two  = $this->invoice_model->distributorInvoiceDetails_update($val_two, $data_two);

		    				// Payment Data Update
				    		$val_three  = array('status' => '0', 'published' => '0');
		    				$data_three = array('bill_id' => $auto_id);
		    				$upt_three  = $this->payment_model->distributorPayment_update($val_three, $data_three);

		    				// Payment Details Update
				    		$val_four  = array('status' => '0', 'published' => '0');
		    				$data_four = array('bill_id' => $auto_id);
		    				$upt_four  = $this->payment_model->distributorPaymentDetails_update($val_four, $data_four);

				    		$update_data = array(
				    			'order_status' => $progress,
				    			'_delete'      => date('Y-m-d H:i:s'),
				    		);
				    	}

				    	// Shipping => 10
				    	else if($progress == '10')
				    	{
				    		$update_data = array(
				    			'order_status' => $progress,
				    			'_shipping'    => date('Y-m-d H:i:s'),
				    		);

				    		$inv_data = array(
				    			'length'     => $length,
							    'breadth'    => $breadth,
							    'height'     => $height,
							    'weight'     => $weight,
				    			'updatedate' => date('Y-m-d H:i:s'),
				    		);

				    		$inv_whr = array('id' => $invoice_id);
				    		$inv_upt = $this->invoice_model->distributorInvoice_update($inv_data, $inv_whr);
				    	}

				    	// Delivered => 11
				    	else if($progress == '11')
				    	{
				    		$update_data = array(
				    			'order_status' => $progress,
				    			'_delivered'    => date('Y-m-d H:i:s'),
				    		);
				    	}
				    	
				    	// Cancel => 8
				    	else
				    	{
				    		$update_data = array(
				    			'order_status' => $progress,
				    			'_canceled'    => date('Y-m-d H:i:s'),
				    		);
				    	}

				    	// Distributor Purchase Details
				    	$update_pdtData = array(
			    			'order_status' => $progress,
			    			'updatedate'   => date('Y-m-d H:i:s'),
			    		);	

				    	$wherePdt  = array('order_id' => $auto_id);
				    	
			    		$updatePdt = $this->distributorpurchase_model->distributorDeliveryDetails_update($update_pdtData, $wherePdt);

				    	// Distributor Purchase Table
				    	$where  = array('id' => $auto_id);
					    $update = $this->distributorpurchase_model->distributorDelivery_update($update_data, $where);

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
				        $response['message'] = "Data Already Exist"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
			    }
			}

			else if($method == '_updateOrderDetails')
			{
				$error    = FALSE;
				$required = array('auto_id', 'price', 'quantity');
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
			    	$order_data = array(
						'product_price' => $price,
						'product_qty'   => $quantity,
						'updatedate'    => date('Y-m-d H:i:s'),
					);

					$update_id = array('id' => $auto_id);

					$update=$this->distributorpurchase_model->distributorDeliveryDetails_update($order_data, $update_id);

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

			else if($method == '_DeleteOrderDetails')
			{
				$auto_id = $this->input->post('auto_id');

		    	if(!empty($auto_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

		    		$where  = array('id' => $auto_id);

				    $update = $this->distributorpurchase_model->distributorDeliveryDetails_delete($data, $where);

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

			else if($method == '_changePackStatus')
			{
				$error    = FALSE;
				$required = array('auto_id');
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
			    	$whr_1 = array(
			    		'id'          => $auto_id,
			    		'pack_status' => '1',
			    		'published'   => '1',
			    		'status'      => '1',
			    	);

			    	$col_1 = 'id, type_id, product_qty, receive_qty, product_unit';
			    	$res_1 = $this->distributorpurchase_model->getDistributorPurchaseDetails($whr_1, '', '', 'result', '', '', '', '', $col_1);

			    	if($res_1)
			    	{
		    			$po_auto_id   = !empty($res_1[0]->id)?$res_1[0]->id:'';	
		    			$type_id      = !empty($res_1[0]->type_id)?$res_1[0]->type_id:'';
					    $product_qty  = !empty($res_1[0]->product_qty)?$res_1[0]->product_qty:'0';
					    $receive_qty  = !empty($res_1[0]->receive_qty)?$res_1[0]->receive_qty:'0';
					    $pdt_unit     = !empty($res_1[0]->product_unit)?$res_1[0]->product_unit:'';
					    $pdt_qty      = $product_qty - $receive_qty;

					    // Product stock details
					    $whr_2  = array(
					    	'id'        => $type_id,
					    	'published' => '1',
					    );

					    $col_2  = 'id, product_type, product_stock, view_stock';

					    $res_2  = $this->commom_model->getProductType($whr_2, '', '', 'result', '', '', '', '', $col_2);

					    $data_2 = $res_2[0];

			            $pdt_type   = !empty($data_2->product_type)?$data_2->product_type:'';
			            $pdt_stock  = !empty($data_2->product_stock)?$data_2->product_stock:'0';
			            $view_stock = !empty($data_2->view_stock)?$data_2->view_stock:'0';

			            // View Stock
						if($pdt_unit == 1 || $pdt_unit == 11)
			    		{
			    			$multiple_stk   = $pdt_qty * $pdt_type; // 5 X 1 = 5 Kg
			    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
			    			$received_stock = $pdt_qty; // 5 Kg
			    		}
			    		else if($pdt_unit == 2 || $pdt_unit == 4)
			    		{
			    			$product_stock  = $pdt_qty * $pdt_type; // 5 X 100 = 500 Gram
			    			$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
			    			$received_stock = number_format($received_value, 2);
			    		}
			    		else
			    		{
			    			$product_stock  = $pdt_qty * $pdt_type; // 5 X 1 = 5 Nos
			    			$received_stock = $pdt_qty; // 5 Nos
			    		}

			    		if($view_stock >= $product_stock)
			    		{
			    			// Update Product Stock
			    			$new_type_stock   = $pdt_stock - $received_stock;
				    		$new_stock_detail = $view_stock - $product_stock;

				    		$upt_stk  = array(
				    			'product_stock' => $new_type_stock,
				    			'view_stock'    => $new_stock_detail,
				    		);

				    		$upt_type = array('id'=>$type_id);
							$update   = $this->commom_model->productType_update($upt_stk, $upt_type);

			    			$upt_data = array(
			    				'receive_qty' => $product_qty,
			    				'pack_status' => '2',
			    				'updatedate'  => $c_date,
			    			);

			    			$whr_one = array('id' => $po_auto_id);
						    $upt_one = $this->distributorpurchase_model->distributorPurchaseDetails_update($upt_data, $whr_one);

						    if($res_1)
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
					        $response['message'] = "Invalide Stock"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
			    		}
			    	}
			    }
			}

			else if($method == '_changePackProcess')
			{
				$whr_1 = array(
		    		'po_id'           => $auto_id,
		    		'pack_status'     => '1',
		    		'published'       => '1',
		    	);

		    	$col_1 = 'id, type_id, product_qty, receive_qty, product_unit';
		    	$res_1 = $this->distributorpurchase_model->getDistributorPurchaseDetails($whr_1, '', '', 'result', '', '', '', '', $col_1);

		    	if($res_1)
		    	{
		    		foreach ($res_1 as $key => $val_1) {
		    			$po_auto_id   = !empty($val_1->id)?$val_1->id:'';	
		    			$type_id      = !empty($val_1->type_id)?$val_1->type_id:'';
					    $product_qty  = !empty($val_1->product_qty)?$val_1->product_qty:'0';
					    $receive_qty  = !empty($val_1->receive_qty)?$val_1->receive_qty:'0';
					    $pdt_unit     = !empty($val_1->product_unit)?$val_1->product_unit:'';
					    $pdt_qty      = $product_qty - $receive_qty;

					    // Product stock details
					    $whr_2  = array(
					    	'id'        => $type_id,
					    	'published' => '1',
					    );

					    $col_2  = 'id, product_type, product_stock, view_stock';

					    $res_2  = $this->commom_model->getProductType($whr_2, '', '', 'result', '', '', '', '', $col_2);

					    $data_2 = $res_2[0];

			            $pdt_type   = !empty($data_2->product_type)?$data_2->product_type:'';
			            $pdt_stock  = !empty($data_2->product_stock)?$data_2->product_stock:'0';
			            $view_stock = !empty($data_2->view_stock)?$data_2->view_stock:'0';

			            // View Stock
						if($pdt_unit == 1 || $pdt_unit == 11)
			    		{
			    			$multiple_stk   = $pdt_qty * $pdt_type; // 5 X 1 = 5 Kg
			    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
			    			$received_stock = $pdt_qty; // 5 Kg
			    		}
						else if($pdt_unit == 2 || $pdt_unit == 4)
			    		{
			    			$product_stock  = $pdt_qty * $pdt_type; // 5 X 100 = 500 Gram
			    			$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
			    			$received_stock = number_format($received_value, 2);
			    		}
			    		else
			    		{
			    			$product_stock  = $pdt_qty * $pdt_type; // 5 X 1 = 5 Nos
			    			$received_stock = $pdt_qty; // 5 Nos
			    		}

			    		if($view_stock >= $product_stock)
			    		{
			    			$new_type_stock   = $pdt_stock - $received_stock;
				    		$new_stock_detail = $view_stock - $product_stock;

				    		$upt_stk  = array(
				    			'product_stock' => $new_type_stock,
				    			'view_stock'    => $new_stock_detail,
				    		);

				    		$upt_type = array('id'=>$type_id);
							$update   = $this->commom_model->productType_update($upt_stk, $upt_type);

			    			$upt_data = array(
			    				'pack_status' => '2',
			    				'updatedate'  => $c_date,
			    			);

			    			$whr_one = array('id' => $po_auto_id);
						    $upt_one = $this->distributorpurchase_model->distributorPurchaseDetails_update($upt_data, $whr_one);
				    	}
					}

					if($res_1)
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
			        $response['message'] = "Data Not Found"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
		    	}
			}

			else if($method == '_deletePackStatus')
			{
				$error    = FALSE;
				$required = array('auto_id');
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
			    	$upt_data = array(
	    				'delete_status' => '2',
	    				'updatedate'    => $c_date,
	    			);

	    			$whr_one = array('id' => $auto_id);
				    $upt_one = $this->distributorpurchase_model->distributorPurchaseDetails_update($upt_data, $whr_one);

				    if($upt_one)
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
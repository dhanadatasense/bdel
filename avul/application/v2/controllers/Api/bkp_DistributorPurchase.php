<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class DistributorPurchase extends CI_Controller {

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
			$this->load->model('return_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Add Purchase
		// ***************************************************
		public function add_purchase($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addDistributorPurchase')
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

					$bill_val   = $this->distributorpurchase_model->getDistributorPurchase($where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

					$bill_count = !empty($bill_val[0]->autoid)?$bill_val[0]->autoid:'0';
					$bill_num   = 'PO'.leadingZeros($bill_count, 5);

					$total_value = 0;
					foreach ($product_value as $key => $val_1) {
						$dis_product_qty = !empty($val_1->dis_product_qty)?$val_1->dis_product_qty:'';
					    $dis_price_val   = !empty($val_1->dis_price_val)?$val_1->dis_price_val:'0';
					    $price_value     = $dis_product_qty * $dis_price_val;
					    $total_value    += $price_value;
					}

					// Distributor Details
					$distri_whr = array(
				    	'id' => $distributor_id,
				    );			   

					$distri_col = 'company_name, available_limit';

					$distri_data = $this->distributors_model->getDistributors($distri_whr, '', '', 'result', '', '', '', '', $distri_col);

					$distri_name = !empty($distri_data[0]->company_name)?$distri_data[0]->company_name:'';
					$distri_lmt  = !empty($distri_data[0]->available_limit)?$distri_data[0]->available_limit:'';

					if($distri_lmt >= $total_value)
					{
						$data = array(
							'po_no'            => $bill_num,
					    	'distributor_id'   => $distributor_id,
					    	'distributor_name' => $distri_name,
					    	'order_date'       => $date,
					    	'_ordered'         => $c_date,
					    	'financial_year'   => $active_financial,
					    	'createdate'       => $c_date
					    );

					    $insert = $this->distributorpurchase_model->distributorPurchase_insert($data);

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
								'po_id'          => $insert,
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

							$type_insert = $this->distributorpurchase_model->distributorPurchaseDetails_insert($value);
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
					else
				    {
	        			$response['status']  = 0;
				        $response['message'] = "Invalide Balance"; 
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
		public function manage_purchase($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listPurchasePaginate')
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
					$overalldatas = $this->distributorpurchase_model->getDistributorPurchase($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->distributorpurchase_model->getDistributorPurchase($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$purchase_list = [];
						foreach ($data_list as $key => $value) {
							$po_id          = !empty($value->id)?$value->id:'';
				            $po_no          = !empty($value->po_no)?$value->po_no:'';
				            $distributor_id = !empty($value->distributor_id)?$value->distributor_id:'';
				            $order_date     = !empty($value->order_date)?$value->order_date:'';
				            $order_status   = !empty($value->order_status)?$value->order_status:'';
				            $_ordered       = !empty($value->_ordered)?$value->_ordered:'';
				            $financial_year = !empty($value->financial_year)?$value->financial_year:'';
				            $bill           = !empty($value->bill)?$value->bill:'';
				            $invoice_no     = !empty($value->invoice_no)?$value->invoice_no:'';
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
				            	'po_id'          => $po_id,
				            	'po_no'          => $po_no,
				            	'distributor_id' => $distributor_id,
				            	'company_name'   => $company_name,
				            	'order_date'     => $order_date,
				            	'order_status'   => $order_status,
				            	'_ordered'       => $_ordered,
				            	'financial_year' => $financial_year,
				            	'bill'           => $bill,
				            	'invoice_no'     => $invoice_no,
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

			else if($method == '_listDistributorPurchasePaginate')
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

					$search = $this->input->post('search');
		    		if($search !='')
		    		{
		    			$like['name']     = $search;

		    			$where = array(
		    				'distributor_id' => $distributor_id,
		    				// 'financial_year' => $financ_year,
		    				'published'      => '1'
		    			);
		    		}
		    		else
		    		{
		    			$like = [];
		    			$where = array(
		    				'distributor_id' => $distributor_id,
		    				// 'financial_year' => $financ_year,
		    				'published'      => '1'
		    			);
		    		}

		    		$column = 'id';
					$overalldatas = $this->distributorpurchase_model->getDistributorPurchase($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->distributorpurchase_model->getDistributorPurchase($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$purchase_list = [];
						foreach ($data_list as $key => $value) {
							$po_id          = !empty($value->id)?$value->id:'';
				            $po_no          = !empty($value->po_no)?$value->po_no:'';
				            $distributor_id = !empty($value->distributor_id)?$value->distributor_id:'';
				            $order_date     = !empty($value->order_date)?$value->order_date:'';
				            $order_status   = !empty($value->order_status)?$value->order_status:'';
				            $_ordered       = !empty($value->_ordered)?$value->_ordered:'';
				            $financial_year = !empty($value->financial_year)?$value->financial_year:'';
				            $bill           = !empty($value->bill)?$value->bill:'';
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
				            	'po_id'          => $po_id,
				            	'po_no'          => $po_no,
				            	'distributor_id' => $distributor_id,
				            	'company_name'   => $company_name,
				            	'order_date'     => $order_date,
				            	'order_status'   => $order_status,
				            	'_ordered'       => $_ordered,
				            	'financial_year' => $financial_year,
				            	'bill'           => $bill,
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

			else if($method == '_viewDistributorPurchase')
			{
				$purchase_id    = $this->input->post('purchase_id');
				$distributor_id = $this->input->post('distributor_id');
				$view_type      = $this->input->post('view_type');

				
				$error    = FALSE;
			    $errors   = array();
				$required = array('purchase_id', 'view_type');
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
							'id'             => $purchase_id,
							'published'      => '1',
							'status'         => '1',
						);
					}
					else
					{
						$where_1 = array(
							'id'             => $purchase_id,
							'distributor_id' => $distributor_id,
							'published'      => '1',
							'status'         => '1',
						);
					}

					$bill_data = $this->distributorpurchase_model->getDistributorPurchase($where_1);

					if($bill_data)
					{
						$bill_val       = $bill_data[0];
						$po_id          = !empty($bill_val->id)?$bill_val->id:'';
			            $po_no          = !empty($bill_val->po_no)?$bill_val->po_no:'';
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
						$invoice_id     = !empty($bill_val->invoice_id)?$bill_val->invoice_id:'';
						$invoice_no     = !empty($bill_val->invoice_no)?$bill_val->invoice_no:'';
						$inv_random     = !empty($bill_val->invoice_random)?$bill_val->invoice_random:'';

			            // Bill Details
			            $bill_details = array(
			            	'po_id'          => $po_id,
							'po_no'          => $po_no,
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
							'invoice_id'     => $invoice_id,
							'invoice_no'     => $invoice_no,
							'inv_random'     => $inv_random,
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
			            	'po_id'         => $po_id,
			            	'delete_status' => '1',
			            	'status'        => '1',
			            	'published'     => '1',
			            );

			            $order_data  = $this->distributorpurchase_model->getDistributorPurchaseDetails($where_3);

			            $order_details = [];
			            foreach ($order_data as $key => $value) {
			            	
			            	$auto_id         = !empty($value->id)?$value->id:'';
				            $po_id           = !empty($value->po_id)?$value->po_id:'';
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
								'po_id'           => $po_id,
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

			            // Sales Return Details
	        			$whr_6 = array(
        					'invoice_id' => $invoice_id,
        					'published'  => '1',
        				);

        				$col_6  = 'price, return_qty';

        				$data_6 = $this->return_model->getDistributorReturnDetails($whr_6, '', '', 'result', '', '', '', '', $col_6);

        				$re_val = 0;
        				if($data_6)
        				{
        					foreach ($data_6 as $key => $val_6) {
	        					$ret_price = !empty($val_6->price)?$val_6->price:'0';
								$ret_qty   = !empty($val_6->return_qty)?$val_6->return_qty:'0';
								$ret_total = $ret_price * $ret_qty;
								$re_val   += $ret_total;
	        				}
        				}

        				$return_details = array(
	        				'return_total' => round($re_val)
	        			);

			            $purchase_details = array(
			            	'bill_details'        => $bill_details,
			            	'admin_details'       => $admin_details,
			            	'distributor_details' => $distributor_details,
			            	'order_details'       => $order_details,
			            	'return_details'      => $return_details,
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
			    	$bill_det   = $this->distributorpurchase_model->getDistributorPurchase($bill_where, '', '', 'result', '', '', '', '', $bill_col);
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
							    	if($product_unit == 1)
						    		{
						    			$multiple_stk   = $product_qty * $pdt_type; // 5 X 1 = 5 Kg
						    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
						    			$received_stock = $product_qty; // 5 Kg
						    		}
						    		else if($product_unit == 2)
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
			                        if($product_unit == 1)
			                        {
			                            $multiple_stk   = $product_qty * $pdt_type; // 5 X 1 = 5 Kg
			                            $product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
			                            $received_stock = $product_qty; // 5 Kg
			                        }
			                        else if($product_unit == 2)
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

				    	$wherePdt  = array('po_id' => $auto_id);
				    	
			    		$updatePdt = $this->distributorpurchase_model->distributorPurchaseDetails_update($update_pdtData, $wherePdt);

				    	// Distributor Purchase Table
				    	$where  = array('id' => $auto_id);
					    $update = $this->distributorpurchase_model->distributorPurchase_update($update_data, $where);

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

					$update=$this->distributorpurchase_model->distributorPurchaseDetails_update($order_data, $update_id);

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

				    $update = $this->distributorpurchase_model->distributorPurchaseDetails_delete($data, $where);

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
				    	if($pdt_unit == 1)
			    		{
			    			$multiple_stk   = $pdt_qty * $pdt_type; // 5 X 1 = 5 Kg
			    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
			    			$received_stock = $pdt_qty; // 5 Kg
			    		}
			    		else if($pdt_unit == 2)
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
				    	if($pdt_unit == 1)
			    		{
			    			$multiple_stk   = $pdt_qty * $pdt_type; // 5 X 1 = 5 Kg
			    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
			    			$received_stock = $pdt_qty; // 5 Kg
			    		}
			    		else if($pdt_unit == 2)
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

		// Manage Production Stock Details
		// ***************************************************
		public function manage_orderStkDetails($param1="",$param2="",$param3="")
		{
			$method        = $this->input->post('method');
			$po_id         = $this->input->post('po_id');
	    	$po_auto_id    = $this->input->post('po_auto_id');
	    	$product_id    = $this->input->post('product_id');
	    	$type_id       = $this->input->post('type_id');
	    	$received_qty  = $this->input->post('received_qty');
	    	$received_date = $this->input->post('received_date');
	    	$stock_id      = $this->input->post('stock_id');
	    	$date          = date('Y-m-d');
			$time          = date('H:i:s');
			$c_date        = date('Y-m-d H:i:s');

			if($method == '_addOrderStockDetails')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('po_id', 'po_auto_id', 'product_id', 'type_id', 'received_qty', 'received_date');
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
			    	// Get Order Qty
			    	$where_1 = array(
			    		'id'         => $po_auto_id,
			    		'po_id'      => $po_id,
			    		'product_id' => $product_id,
			    		'type_id'    => $type_id,
			    		'published'  => '1',
			    		'status'     => '1',
			    	);

			    	$column_1 = 'distributor_id, assproduct_id, product_qty';

			    	$order_data = $this->distributorpurchase_model->getDistributorPurchaseDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

			    	if($order_data)
			    	{
			    		$distributor_id = !empty($order_data[0]->distributor_id)?$order_data[0]->distributor_id:'0';
			    		$assproduct_id  = !empty($order_data[0]->assproduct_id)?$order_data[0]->assproduct_id:'0';
			    		$product_qty    = !empty($order_data[0]->product_qty)?$order_data[0]->product_qty:'0';

				    	// Collect Order Qty
				    	$where_2 = array(
				    		'po_id'         => $po_id,
				    		'po_auto_id'    => $po_auto_id,
				    		'product_id'    => $product_id,
				    		'type_id'       => $type_id,
				    		'published'     => '1',
				    		'status'        => '1',
				    	);

				    	$column_2 = 'received_qty';

				    	$collect_data = $this->distributorpurchase_model->getDistributorPurchaseStkDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

				    	$received_cou = 0;
				    	if(!empty($collect_data))
				    	{
				    		foreach ($collect_data as $key => $value) {
					    		$received_val  = !empty($value->received_qty)?$value->received_qty:'0';
					    		$received_cou += $received_val; 
					    	}
				    	}

				    	// Overall Collect Data
				    	$over_collect = $product_qty - $received_cou;

				    	if($over_collect >= $received_qty)
				    	{
				    		// Product Type Stock Minus
					    	$where_3 = array('id' => $type_id, 'product_id' => $product_id);

					    	$productType_val = $this->commom_model->getProductType($where_3);

					    	$product_type  = !empty($productType_val[0]->product_type)?$productType_val[0]->product_type:'';
					    	$product_unit  = !empty($productType_val[0]->product_unit)?$productType_val[0]->product_unit:'';
					    	$product_stock = !empty($productType_val[0]->product_stock)?$productType_val[0]->product_stock:'0';	
					    	$view_stock    = !empty($productType_val[0]->view_stock)?$productType_val[0]->view_stock:'0';

					    	// View Stock
					    	if($product_unit == 1)
				    		{
				    			$multiple_stk   = $received_qty * $product_type; // 5 X 1 = 5 Kg
				    			$stock_value    = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
				    			$received_stock = $received_qty; // 5 Kg
				    		}
				    		else if($product_unit == 2)
				    		{
				    			$stock_value   = $received_qty * $product_type; // 5 X 100 = 500 Gram
				    			$received_value = $stock_value / 1000; // 500 / 1000 = 0.50 Kg
				    			$received_stock = number_format($received_value, 2);
				    		}
				    		else
				    		{
				    			$stock_value    = $received_qty * $product_type; // 5 X 1 = 5 Nos
				    			$received_stock = $received_qty; // 5 Nos
				    		}

				    		if($product_stock >= $received_stock)
				    		{
				    			// Stock Process
					    		$new_pdt_stock = $product_stock - $received_stock;
					    		$new_view_stk  = $view_stock - $stock_value;

					    		$type_data = array(
					    			'product_stock' => $new_pdt_stock,
					    			'view_stock'    => $new_view_stk,
					    		);

					    		$type_whr = array('id' => $type_id);
					    		$update   = $this->commom_model->productType_update($type_data, $type_whr);

					    		// Order Stock Process
					    		$overColl_qty = $received_cou + $received_qty;

					    		$produc_data = array(
					    			'receive_qty' => strval($overColl_qty),
					    		);

					    		$produc_whr   = array('id' => $po_auto_id);
					    		$update_prodc = $this->distributorpurchase_model->distributorPurchaseDetails_update($produc_data, $produc_whr);

					    		// Distributor Assign Product Details
					    		$where_4 = array(
					    			'id'        => $assproduct_id,
					    			'published' => '1',
					    			'status'    => '1',
					    		);

					    		$product_data = $this->assignproduct_model->getAssignProductDetails($where_4);

					    		$stock_val    = !empty($product_data[0]->stock)?$product_data[0]->stock:'0';
					    		$viewStk_val  = !empty($product_data[0]->view_stock)?$product_data[0]->view_stock:'0';

					    		$pdtStock_val  = $stock_val + $received_stock;
					    		$viewStock_val = $viewStk_val + $stock_value;

					    		$stock_data = array(
					    			'stock'      => strval($pdtStock_val),
					    			'view_stock' => strval($viewStock_val),
					    		);

					    		$stock_whr  = array('id' => $assproduct_id);
					    		$update_prodc = $this->assignproduct_model->assignProductDetails_update($stock_data, $stock_whr);

					    		// Order Stock Details Insert
					    		$ins_data = array(
							    	'po_id'          => $po_id,
							    	'po_auto_id'     => $po_auto_id,
							    	'distributor_id' => $distributor_id,
							    	'assproduct_id'  => $assproduct_id,
							    	'product_id'     => $product_id,
							    	'type_id'        => $type_id,
							    	'product_unit'   => $product_unit,
							    	'received_qty'   => $received_qty,
							    	'received_date'  => date('Y-m-d', strtotime($received_date)),
							    	'createdate'     => date('Y-m-d H:i:s')
							    );

							    $insert = $this->distributorpurchase_model->distributorPurchaseStkDetails_insert($ins_data);

							    // Production order qty details
						    	$where_5 = array(
						    		'id'         => $po_auto_id,
						    		'po_id'      => $po_id,
						    		'product_id' => $product_id,
						    		'type_id'    => $type_id,
						    		'published'  => '1',
						    		'status'     => '1',
						    	);

						    	$column_5 = 'product_qty';

						    	$order_data = $this->distributorpurchase_model->getDistributorPurchaseDetails($where_5, '', '', 'result', '', '', '', '', $column_5);

						    	$product_qty = !empty($order_data[0]->product_qty)?$order_data[0]->product_qty:'';

						    	// Collect Order Qty
						    	$where_6 = array(
						    		'po_id'         => $po_id,
						    		'po_auto_id'    => $po_auto_id,
						    		'product_id'    => $product_id,
						    		'type_id'       => $type_id,
						    		'published'     => '1',
						    		'status'        => '1',
						    	);

						    	$column_6 = 'received_qty';

						    	$collect_data = $this->distributorpurchase_model->getDistributorPurchaseStkDetails($where_6, '', '', 'result', '', '', '', '', $column_6);

						    	$new_received_cou = 0;
						    	if(!empty($collect_data))
						    	{
						    		foreach ($collect_data as $key => $value) {
							    		$received_val  = !empty($value->received_qty)?$value->received_qty:'0';
							    		$new_received_cou += $received_val; 
							    	}
						    	}

						    	if($product_qty == $new_received_cou)
						    	{
						    		$ord_data = array('production_status' => '2');
				            		$ord_whr  = array('id' => $po_auto_id);
				            		$ord_upt  = $this->distributorpurchase_model->distributorPurchaseDetails_update($ord_data, $ord_whr);	
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
				    		else
				    		{
				    			$response['status']  = 0;
						        $response['message'] = "Invalide Stock"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
				    		}
				    	}	
				    	else
				    	{
				    		$response['status']  = 0;
					        $response['message'] = "Invalide Quantity"; 
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
			}

			else if($method == '_listOrderStockDetails')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('po_id', 'po_auto_id', 'product_id', 'type_id');
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
			    	$where_1 = array(
			    		'po_id'      => $po_id,
			    		'po_auto_id' => $po_auto_id,
			    		'product_id' => $product_id,
			    		'type_id'    => $type_id,
			    		'published'  => '1',
			    		'status'     => '1',
			    	);

			    	$stock_details = $this->distributorpurchase_model->getDistributorPurchaseStkDetails($where_1);

			    	if($stock_details)
			    	{
			    		$stock_list = [];
			    		foreach ($stock_details as $key => $value) {
			    			$stock_id      = !empty($value->id)?$value->id:'';
			    			$product_unit  = !empty($value->product_unit)?$value->product_unit:'';
						    $received_qty  = !empty($value->received_qty)?$value->received_qty:'';
						    $received_date = !empty($value->received_date)?$value->received_date:'';

						    // Unit Type Details
					    	$where_1   = array('id' => $product_unit);
					    	$unit_det  = $this->commom_model->getUnit($where_1);
					    	$unit_name = isset($unit_det[0]->name)?$unit_det[0]->name:'';

						    $stock_list[] = array(
						    	'stock_id'      => $stock_id,
						    	'product_unit'  => $product_unit,
						    	'unit_name'     => $unit_name,
						    	'received_qty'  => $received_qty,
						    	'received_date' => $received_date,
						    );
			    		}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $stock_list;
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

			else if($method == '_detailOrderStockDetails')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('po_id', 'po_auto_id');
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
			    	// Distributor Order Details
			    	$where_1 = array(
			    		'id'        => $po_auto_id,
			    		'po_id'     => $po_id,
			    		'published' => '1',
			    		'status'    => '1',
			    	);

			    	$column_1 = 'distributor_id, product_id, type_id, product_unit, product_qty, receive_qty';

			    	$order_data  = $this->distributorpurchase_model->getDistributorPurchaseDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

			    	if($order_data)
			    	{
			    		$distributor_id = !empty($order_data[0]->distributor_id)?$order_data[0]->distributor_id:'';
			    		$product_id     = !empty($order_data[0]->product_id)?$order_data[0]->product_id:'';
			            $type_id        = !empty($order_data[0]->type_id)?$order_data[0]->type_id:'';
			            $product_unit   = !empty($order_data[0]->product_unit)?$order_data[0]->product_unit:'';

			            $product_qty    = !empty($order_data[0]->product_qty)?$order_data[0]->product_qty:'0';
			            $receive_qty    = !empty($order_data[0]->receive_qty)?$order_data[0]->receive_qty:'0';
			            $balance_qty    = $product_qty - $receive_qty;

			            // Distributor Order Bill Details
			            $where_2 = array(
			            	'id'        => $po_id,
			            	'published' => '1',
				    		'status'    => '1',
			            );	

			            $column_2  = 'po_no';

			            $bill_data = $this->distributorpurchase_model->getDistributorPurchase($where_2, '', '', 'result', '', '', '', '', $column_2);

			            $po_no = !empty($bill_data[0]->po_no)?$bill_data[0]->po_no:'';

			            // Product Details
			            $where_3  = array(
			            	'id' => $type_id,
			            );

			            $column_3 = 'description';

			            $productType_val = $this->commom_model->getProductType($where_3, '', '', 'result', '', '', '', '', $column_3);

			            $description = !empty($productType_val[0]->description)?$productType_val[0]->description:'';

			            // Unit Details
			            $where_4 = array(
				    		'id' => $product_unit,
				    	);

				    	$unit_val  = $this->commom_model->getUnit($where_4);
				    	$unit_name = isset($unit_val[0]->name)?$unit_val[0]->name:'';

				    	$product_details = array(
				    		'po_id'          => $po_id,
				    		'po_no'          => $po_no,
				    		'po_auto_id'     => $po_auto_id,
				    		'distributor_id' => $distributor_id,
				    		'product_id'     => $product_id,
				    		'type_id'        => $type_id,
				    		'product_unit'   => $product_unit,
				    		'description'    => $description,
				    		'unit_name'      => $unit_name,
				    		'balance_qty'    => $balance_qty,
				    	);

				    	$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $product_details;
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

			else if($method == '_deleteOrderStockDetails')
			{
				if(!empty($stock_id))
				{
					$where_1 = array(
						'id'        => $stock_id,
						'published' => '1',
			    		'status'    => '1',
					);

					$stock_details = $this->distributorpurchase_model->getDistributorPurchaseStkDetails($where_1);

					if($stock_details)
					{
						$po_id          = !empty($stock_details[0]->po_id)?$stock_details[0]->po_id:'';
			            $po_auto_id     = !empty($stock_details[0]->po_auto_id)?$stock_details[0]->po_auto_id:'';
			            $distributor_id = !empty($stock_details[0]->distributor_id)?$stock_details[0]->distributor_id:'';
			            $assproduct_id  = !empty($stock_details[0]->assproduct_id)?$stock_details[0]->assproduct_id:'';
			            $product_id     = !empty($stock_details[0]->product_id)?$stock_details[0]->product_id:'';
			            $type_id        = !empty($stock_details[0]->type_id)?$stock_details[0]->type_id:'';
			            $product_unit   = !empty($stock_details[0]->product_unit)?$stock_details[0]->product_unit:'';
			            $receive_qty   = !empty($stock_details[0]->received_qty)?$stock_details[0]->received_qty:'';

			            // Distributor Assign Product Details
			    		$where_2 = array(
			    			'id'        => $assproduct_id,
			    			'published' => '1',
			    			'status'    => '1',
			    		);

			    		$pdt_data = $this->assignproduct_model->getAssignProductDetails($where_2);	

			    		$stock_val   = !empty($pdt_data[0]->stock)?$pdt_data[0]->stock:'0';
	            		$viewStk_val = !empty($pdt_data[0]->view_stock)?$pdt_data[0]->view_stock:'0';

			    		// Product Type Stock Minus
				    	$where_3 = array(
				    		'id'         => $type_id, 
				    		'product_id' => $product_id
				    	);

				    	$productType_val = $this->commom_model->getProductType($where_3);

				    	$product_type  = !empty($productType_val[0]->product_type)?$productType_val[0]->product_type:'';
				    	$product_stock = !empty($productType_val[0]->product_stock)?$productType_val[0]->product_stock:'';
				    	$view_stock    = !empty($productType_val[0]->view_stock)?$productType_val[0]->view_stock:'';

				    	// View Stock
				    	if($product_unit == 1)
			    		{
			    			$multiple_stk   = $receive_qty * $product_type; // 5 X 1 = 5 Kg
			    			$stock_value    = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
			    			$received_stock = $receive_qty; // 5 Kg
			    		}
			    		else if($product_unit == 2)
			    		{
			    			$stock_value   = $receive_qty * $product_type; // 5 X 100 = 500 Gram
			    			$received_value = $stock_value / 1000; // 500 / 1000 = 0.50 Kg
			    			$received_stock = number_format($received_value, 2);
			    		}
			    		else
			    		{
			    			$stock_value    = $receive_qty * $product_type; // 5 X 1 = 5 Nos
			    			$received_stock = $receive_qty; // 5 Nos
			    		}

			    		$pdtStock_val  = $stock_val - $received_stock;
			    		$viewStock_val = $viewStk_val - $stock_value;

			    		$stock_data = array(
			    			'stock'      => strval($pdtStock_val),
			    			'view_stock' => strval($viewStock_val),
			    		);

			    		$stock_whr     = array('id' => $assproduct_id);
			    		$update_assign = $this->assignproduct_model->assignProductDetails_update($stock_data, $stock_whr);

			    		$pdtStock_val  = $product_stock + $received_stock;
			    		$viewStock_val = $view_stock + $stock_value;

			    		$type_data = array(
			    			'product_stock' => strval($pdtStock_val),
			    			'view_stock'    => strval($viewStock_val),
			    		);

			    		$type_whr      = array('id' => $type_id);
			    		$update_assign = $this->commom_model->productType_update($type_data, $type_whr);

			    		$where_4 = array(
			    			'id'    => $po_auto_id,
			    		 	'po_id' => $po_id
			    		);

			    		$order_details = $this->distributorpurchase_model->getDistributorPurchaseDetails($where_4);

			    		$receive_cou   = !empty($order_details[0]->receive_qty)?$order_details[0]->receive_qty:'0';	

			    		// Order Stock Process
			    		$overColl_qty = $receive_cou - $receive_qty;

			    		$produc_data = array(
			    			'receive_qty'       => strval($overColl_qty),
			    			'production_status' => '1',
			    		);

			    		$produc_whr   = array('id' => $po_auto_id);
			    		$update_prodc = $this->distributorpurchase_model->distributorPurchaseDetails_update($produc_data, $produc_whr);

			    		// Delete Stock List
						$data = array(
					    	'published' => '0',
					    );

			    		$where  = array('id' => $stock_id);
					    $delete = $this->distributorpurchase_model->distributorPurchaseStkDetails_delete($data, $where);

					    if($delete)
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

		// Distributor Invoice
		// ***************************************************
		public function distributor_invoice($param1="",$param2="",$param3="")
		{
			$method     = $this->input->post('method');
			$inv_random = $this->input->post('inv_random');

			if($method == '_distributorPrintInvoice')
			{
				if(!empty($inv_random))
				{
					$where_1 = array(
						'random_value' => $inv_random,
						'published'    => '1',
						'status'       => '1',
					);

					$column_1 = 'id, order_id, invoice_no, distributor_id, due_days, date, length, breadth, height, weight';
					$data_1   = $this->invoice_model->getDistributorInvoice($where_1, '', '', 'result', '', '', '', '', $column_1);

					if($data_1)
					{
						$result_1 = $data_1[0];

						$invoice_id     = !empty($result_1->id)?$result_1->id:'';
						$order_id       = !empty($result_1->order_id)?$result_1->order_id:'';
						$invoice_no     = !empty($result_1->invoice_no)?$result_1->invoice_no:'';
			            $distributor_id = !empty($result_1->distributor_id)?$result_1->distributor_id:'';
			            $due_days       = !empty($result_1->due_days)?$result_1->due_days:'';
			            $invoice_date   = !empty($result_1->date)?$result_1->date:'';
			            $length         = !empty($result_1->length)?$result_1->length:'0';
			            $breadth        = !empty($result_1->breadth)?$result_1->breadth:'0';
			            $height         = !empty($result_1->height)?$result_1->height:'0';
			            $weight         = !empty($result_1->weight)?$result_1->weight:'0';

			            $where_2 = array(
							'id'        => $order_id,
							'published' => '1',
							'status'    => '1',
						);

						$column_2 = 'po_no, order_date';
						$data_2   = $this->distributorpurchase_model->getDistributorPurchase($where_2, '', '', 'result', '', '', '', '', $column_2);
						$result_2 = $data_2[0];

						$po_no      = !empty($result_2->po_no)?$result_2->po_no:'';
    					$order_date = !empty($result_2->order_date)?$result_2->order_date:'';

						$where_3 = array(
							'id' => $distributor_id,
						);

						$column_3 = 'company_name, mobile, email, state_id, gst_no, address, pincode';
						$data_3   = $this->distributors_model->getDistributors($where_3, '', '', 'result', '', '', '', '', $column_3);
						$result_3 = $data_3[0];

						$company_name = !empty($result_3->company_name)?$result_3->company_name:'';
					    $mobile       = !empty($result_3->mobile)?$result_3->mobile:'';
					    $email        = !empty($result_3->email)?$result_3->email:'';
					    $dis_state    = !empty($result_3->state_id)?$result_3->state_id:'';
					    $gst_no       = !empty($result_3->gst_no)?$result_3->gst_no:'';
					    $address      = !empty($result_3->address)?$result_3->address:'';
					    $pincode      = !empty($result_3->pincode)?$result_3->pincode:'';

					    // Distributor State Code Details
					    $dis_std_whr = array('id' => $dis_state);
					    $dis_std_col = 'gst_code';
					    $dis_std_val = $this->commom_model->getState($dis_std_whr, '', '', 'result', '', '', '', '', $dis_std_col);
					    $dis_std_cod = !empty($dis_std_val[0]->gst_code)?$dis_std_val[0]->gst_code:'';

						$invoice_details = array(
							'order_id'     => $order_id,
							'due_days'     => $due_days,
							'invoice_no'   => $invoice_no,
							'invoice_date' => $invoice_date,
							'length'       => $length,
							'breadth'      => $breadth,
							'height'       => $height,
							'weight'       => $weight,
							'po_no'        => $po_no,
							'order_date'   => $order_date,
							'company_name' => $company_name,
							'mobile'       => $mobile,
							'email'        => $email,
							'state_id'     => $dis_state,
							'dis_state'    => $dis_std_cod,
							'gst_no'       => $gst_no,
							'address'      => $address,
							'pincode'      => $pincode,
						);

						// Admin Details
					    $where_4 = array(
					    	'id' => '1'
					    );

					    $column_4 = 'username, mobile, address, pincode, gst_no, state_id';

					    $admin_data  = $this->login_model->getLoginStatus($where_4, '', '', 'result', '', '', '', '', $column_4);

					    $admin_val   = $admin_data[0];

					    $username = !empty($admin_val->username)?$admin_val->username:'';
			            $mobile   = !empty($admin_val->mobile)?$admin_val->mobile:'';
			            $address  = !empty($admin_val->address)?$admin_val->address:'';
			            $pincode  = !empty($admin_val->pincode)?$admin_val->pincode:'';
			            $gst_no   = !empty($admin_val->gst_no)?$admin_val->gst_no:'';
			            $state_id = !empty($admin_val->state_id)?$admin_val->state_id:'';

			            // Distributor State Code Details
					    $adm_std_whr = array('id' => $state_id);
					    $adm_std_col = 'gst_code';
					    $adm_std_val = $this->commom_model->getState($adm_std_whr, '', '', 'result', '', '', '', '', $adm_std_col);
					    $adm_std_cod = !empty($adm_std_val[0]->gst_code)?$adm_std_val[0]->gst_code:'';

			            $admin_details = array(
			            	'username'  => $username,
			            	'mobile'    => $mobile,
			            	'address'   => $address,
			            	'pincode'   => $pincode,
			            	'gst_no'    => $gst_no,
			            	'state_id'  => $state_id,
			            	'adm_state' => $adm_std_cod,
			            );

			            $where_5 = array(
			            	'invoice_id' => $invoice_id,
			            	'published'  => '1'
			            );

			            $column_5 = 'type_id, hsn_code, gst_val, price, order_qty, unit_val';

			            $data_5   = $this->invoice_model->getDistributorInvoiceDetails($where_5, '', '', 'result', '', '', '', '', $column_5);

			            $product_details = [];
			            if(!empty($data_5))
			            {
			            	foreach ($data_5 as $key => $val_5) {
				            	$type_id   = !empty($val_5->type_id)?$val_5->type_id:'';
								$hsn_code  = !empty($val_5->hsn_code)?$val_5->hsn_code:'';
								$gst_val   = !empty($val_5->gst_val)?$val_5->gst_val:'';
								$price     = !empty($val_5->price)?$val_5->price:'';
								$order_qty = !empty($val_5->order_qty)?$val_5->order_qty:'';
								$unit_val  = !empty($val_5->unit_val)?$val_5->unit_val:'';

								$where_6  = array(
									'id' => $type_id
								);

								$column_6 = 'description';

								$data_6   = $this->commom_model->getProductType($where_6, '', '', 'result', '', '', '', '', $column_6);

								$description = !empty($data_6[0]->description)?$data_6[0]->description:'';

								$product_details[] = array(
									'description' => $description,
									'hsn_code'    => $hsn_code,
									'gst_val'     => $gst_val,
									'price'       => $price,
									'order_qty'   => $order_qty,
									'unit_val'    => $unit_val,
								);
				            }
			            }

			            $tax_where  = array('invoice_id' => $invoice_id, 'published'  => '1');
	        			$tax_column = 'hsn_code, gst_val';
	        			$tax_group  = 'hsn_code, gst_val';

	        			$data_6 = $this->invoice_model->getDistributorInvoiceDetails($tax_where, '', '', 'result', '', '', '', '', $tax_column, $tax_group);

	        			$tax_details = [];
	        			if(!empty($data_6))
	        			{
	        				foreach ($data_6 as $key => $val_6) {
	        					$hsn_code = !empty($val_6->hsn_code)?$val_6->hsn_code:'';
	        					$gst_val  = !empty($val_6->gst_val)?$val_6->gst_val:'';

	        					// Price Details
	        					$whr_7  = array(
			        				'invoice_id' => $invoice_id,
			        				'hsn_code'   => $hsn_code,
			        				'gst_val'    => $gst_val,
			        				'published'  => '1',
			        			);

			        			$col_7  = 'price, order_qty, gst_val';

			        			$data_7 = $this->invoice_model->getDistributorInvoiceDetails($whr_7, '', '', 'result', '', '', '', '', $col_7);

			        			$product_price = 0;
			        			$gst_price     = 0;
			        			foreach ($data_7 as $key => $val_7) {
			        				$price     = !empty($val_7->price)?$val_7->price:'';
			        				$order_qty = !empty($val_7->order_qty)?$val_7->order_qty:'';
			        				$gst_val   = !empty($val_7->gst_val)?$val_7->gst_val:'0';

			        				$gst_data    = $price - ($price * (100 / (100 + $gst_val)));
                            		$price_val   = $price - $gst_data;
                            		$total_price = $order_qty * $price_val;
                            		$total_gst   = $order_qty * $gst_data;

			        				$gst_price     += $total_gst;
			        				$product_price += $total_price;
			        			}

			        			$tax_details[] = array(
	        						'hsn_code'    => $hsn_code,
	        						'gst_val'     => $gst_val,
	        						'gst_value'   => $gst_price,
	        						'price_value' => $product_price,
	        					);
	        				}
	        			}

	        			// Sales Return Details
	        			$whr_8 = array(
        					'invoice_id' => $invoice_id,
        					'published'  => '1',
        				);

        				$col_8  = 'price, return_qty';

        				$data_8 = $this->return_model->getDistributorReturnDetails($whr_8, '', '', 'result', '', '', '', '', $col_8);

        				$re_val = 0;
        				if($data_8)
        				{
        					foreach ($data_8 as $key => $val_8) {
	        					$ret_price = !empty($val_8->price)?$val_8->price:'0';
								$ret_qty   = !empty($val_8->return_qty)?$val_8->return_qty:'0';
								$ret_total = $ret_price * $ret_qty;
								$re_val   += $ret_total;
	        				}
        				}

        				$return_details = array(
	        				'return_total' => round($re_val)
	        			);

	        			$data_details = array(
			            	'invoice_details' => $invoice_details,
			            	'admin_details'   => $admin_details,
			            	'product_details' => $product_details,
			            	'tax_details'     => $tax_details,
			            	'return_details'  => $return_details,
			            );

			            $response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $data_details;
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
			else
			{
				$response['status']  = 0;
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// Add Purchase
		// ***************************************************
		public function add_purchase_return($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addDistributorPurchaseReturn')
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
			    	$order_date       = $this->input->post('order_date');
			    	$active_financial = $this->input->post('active_financial');
			    	$purchase_value   = $this->input->post('purchase_value');
			    	$c_date           = date('Y-m-d H:i:s');

			    	$where_1 = array(
			    		'distributor_id' => $distributor_id,
				    	'financial_year' => $active_financial,
				    );	

				    $order_val = $this->distributorpurchase_model->getDistributorPurchaseReturn($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

					$order_count = !empty($order_val[0]->autoid)?$order_val[0]->autoid:'0';		   

					$newbill_count = $order_count + 1;
					$bill_number   = 'ORD'.leadingZeros($newbill_count, 5);

					// Distributor Details
					$where_2  = array('id' => $distributor_id);			   
					$column_2 = 'company_name';
					$dis_daa  = $this->distributors_model->getDistributors($where_2, '', '', 'result', '', '', '', '', $column_2);
					$dis_name = !empty($dis_daa[0]->company_name)?$dis_daa[0]->company_name:'';

					$data = array(
						'order_no'         => $bill_number,
				    	'distributor_id'   => $distributor_id,
				    	'distributor_name' => $dis_name,
				    	'order_date'       => date('Y-m-d'),
				    	'_ordered'         => date('Y-m-d H:i:s'),
				    	'financial_year'   => $active_financial,
				    	'createdate'       => date('Y-m-d H:i:s')
				    );

				    $insert = $this->distributorpurchase_model->distributorPurchaseReturn_insert($data);

					$product_value = json_decode($purchase_value);	

					foreach ($product_value as $key => $val_2) {
						$dis_product_id  = !empty($val_2->dis_product_id)?$val_2->dis_product_id:'';
					    $dis_product_qty = !empty($val_2->dis_product_qty)?$val_2->dis_product_qty:'';
					    $dis_price_val   = !empty($val_2->dis_price_val)?$val_2->dis_price_val:'0';
					    $dis_unit_id     = !empty($val_2->dis_unit_id)?$val_2->dis_unit_id:'';

					    // Product Details
				    	$where_3 = array(
				    		'id'        => $dis_product_id,
				    		'status'    => '1', 
							'published' => '1'
				    	);

				    	$product_data = $this->assignproduct_model->getAssignProductDetails($where_3);
				    	$product_val  = $product_data[0];

				    	$category_id = !empty($product_val->category_id)?$product_val->category_id:'';
			            $product_id  = !empty($product_val->product_id)?$product_val->product_id:'';
			            $type_id     = !empty($product_val->type_id)?$product_val->type_id:'';

					    $value = array(
							'order_id'       => $insert,
							'distributor_id' => $distributor_id,
							'assproduct_id'  => $dis_product_id,
							'category_id'    => $category_id,
							'product_id'     => $product_id,
							'type_id'        => $type_id,
							'product_price'  => $dis_price_val,
							'product_qty'    => $dis_product_qty,
							'receive_qty'    => '0',
							'product_unit'   => $dis_unit_id,
							'financial_year' => $active_financial,
				    		'createdate'     => $c_date
						);

						$type_insert = $this->distributorpurchase_model->distributorPurchaseReturnDetails_insert($value);
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

		// Manage Purchase Return
		// ***************************************************
		public function manage_purchase_return($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listPurchaseReturnPaginate')
			{
				$limit          = $this->input->post('limit');
	    		$offset         = $this->input->post('offset');
	    		$distributor_id = $this->input->post('distributor_id');
	    		$financ_year    = $this->input->post('financial_year');

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

					$search = $this->input->post('search');
		    		if($search !='')
		    		{
		    			$like['name']     = $search;
		    			// $like['hsn_code'] = $search;
		    			// $like['price']    = $search;

		    			$where = array(
		    				'distributor_id' => $distributor_id,
		    				// 'financial_year' => $financ_year,
		    				'published'      => '1'
		    			);
		    		}
		    		else
		    		{
		    			$like = [];
		    			$where = array(
		    				'distributor_id' => $distributor_id,
		    				// 'financial_year' => $financ_year,
		    				'published'      => '1'
		    			);
		    		}

		    		$column = 'id';
					$overalldatas = $this->distributorpurchase_model->getDistributorPurchaseReturn($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->distributorpurchase_model->getDistributorPurchaseReturn($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$purchase_list = [];
						foreach ($data_list as $key => $value) {
								
							$order_id         = isset($value->id)?$value->id:'';
						    $order_no         = isset($value->order_no)?$value->order_no:'';
						    $distributor_id   = isset($value->distributor_id)?$value->distributor_id:'';
						    $distributor_name = isset($value->distributor_name)?$value->distributor_name:'';
						    $order_date       = isset($value->order_date)?$value->order_date:'';
						    $order_status     = isset($value->order_status)?$value->order_status:'';
						    $financial_year   = isset($value->financial_year)?$value->financial_year:'';
						    $bill             = isset($value->bill)?$value->bill:'';
						    $published        = isset($value->published)?$value->published:'';
						    $status           = isset($value->status)?$value->status:'';
						    $createdate       = isset($value->createdate)?$value->createdate:'';

					    	$purchase_list[] = array(
		      					'order_id'         => $order_id,
							    'order_no'         => $order_no,
							    'distributor_id'   => $distributor_id,
							    'distributor_name' => $distributor_name,
							    'order_date'       => date('d-M-Y', strtotime($order_date)),
							    'order_status'     => $order_status,
							    'financial_year'   => $financial_year,
							    'bill'             => $bill,
							    'published'        => $published,
							    'status'           => $status,
							    'createdate'       => $createdate,
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

			else if($method == '_detailsPurchaseReturn')
			{
				$order_id = $this->input->post('order_id');

				if(!empty($order_id))
				{
					// Invoice Details
		    		$order_whr = array(
		    			'id'        => $order_id,
		    			'published' => '1',
		    			'status'    => '1',
		    		);

		    		$order_col = 'id, order_no, return_no, order_status, reason, distributor_id, _ordered, _complete, , _canceled';
			    	$order_det = $this->distributorpurchase_model->getDistributorPurchaseReturn($order_whr, '', '', 'result', '', '', '', '', $order_col);

			    	if(!empty($order_det))
			    	{
			    		$order_res      = $order_det[0];
			    		$order_id       = !empty($order_res->id)?$order_res->id:'';
			            $order_no       = !empty($order_res->order_no)?$order_res->order_no:'';
			            $return_no      = !empty($order_res->return_no)?$order_res->return_no:'';
			            $order_status   = !empty($order_res->order_status)?$order_res->order_status:'';
			            $reason         = !empty($order_res->reason)?$order_res->reason:'';
			            $distributor_id = !empty($order_res->distributor_id)?$order_res->distributor_id:'';
			            $ordered        = !empty($order_res->_ordered)?$order_res->_ordered:'';
			            $complete       = !empty($order_res->_complete)?$order_res->_complete:'';
			            $canceled       = !empty($order_res->_canceled)?$order_res->_canceled:'';

			            // Distributor Name
			            $distributor_whr  = array('id' => $distributor_id);
			            $distributor_col  = 'company_name, mobile, address, gst_no, state_id';
				    	$data_val    = $this->distributors_model->getDistributors($distributor_whr, '', '', 'result', '', '', '', '', $distributor_col);
				    	$distri_name = isset($data_val[0]->company_name)?$data_val[0]->company_name:'';
				    	$mobile      = isset($data_val[0]->mobile)?$data_val[0]->mobile:'';
				    	$address     = isset($data_val[0]->address)?$data_val[0]->address:'';
				    	$gst_no      = isset($data_val[0]->gst_no)?$data_val[0]->gst_no:'';
				    	$state_id    = isset($data_val[0]->state_id)?$data_val[0]->state_id:'';

				    	$distributor_details = array(
				    		'order_id'     => $order_id,
				    		'order_no'     => $order_no,
				    		'return_no'    => $return_no,
				    		'order_status' => $order_status,
				    		'reason'       => $reason,
				    		'ordered'      => $ordered,
				    		'complete'     => $complete,
				    		'canceled'     => $canceled,
				    		'distri_name'  => $distri_name,
				    		'contact_no'   => $mobile,
				    		'address'      => $address,
				    		'gst_no'       => $gst_no,
				    		'state_id'     => $state_id,
				    	);

				    	// Order Details
				    	$where_2 = array(
				    		'order_id'  => $order_id,
				    		'published' => '1',
		    				'status'    => '1',
				    	);

				    	$column_2 = 'id, product_id, type_id, product_price, product_qty, receive_qty, product_unit';

			    		$data_2   = $this->distributorpurchase_model->getDistributorPurchaseReturnDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

			    		$order_details = [];
			    		foreach ($data_2 as $key => $value) {
			    			$auto_id       = !empty($value->id)?$value->id:'';
				            $product_id    = !empty($value->product_id)?$value->product_id:'';
				            $type_id       = !empty($value->type_id)?$value->type_id:'';
				            $product_price = !empty($value->product_price)?$value->product_price:'';
				            $product_qty   = !empty($value->product_qty)?$value->product_qty:'';
				            $product_unit  = !empty($value->product_unit)?$value->product_unit:'';

				            // Product Details
				            $pdt_whr = array('id' => $product_id);
				            $pdt_col = 'hsn_code, gst';
				            $pdt_det = $this->commom_model->getProduct($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);

				            $pdt_res = $pdt_det[0];
				            $pdt_hsn = !empty($pdt_res->hsn_code)?$pdt_res->hsn_code:'';
				            $pdt_gst = !empty($pdt_res->gst)?$pdt_res->gst:'';

				            // Product Details
				            $type_whr  = array('id' => $type_id);
				            $type_col  = 'description';
				            $type_det  = $this->commom_model->getProductType($type_whr, '', '', 'result', '', '', '', '', $type_col);

				            $type_res  = $type_det[0];
				            $type_desc = !empty($type_res->description)?$type_res->description:'';

				            $order_details[] = array(
				            	'auto_id'       => $auto_id,
				            	'type_id'       => $type_id,
				            	'description'   => $type_desc,
				            	'gst_value'     => $pdt_gst,
				            	'hsn_code'      => $pdt_hsn,
				            	'product_price' => $product_price,
				            	'product_qty'   => $product_qty,
				            );
			    		}

			    		$return_details = array(
			    			'distributor_details' => $distributor_details,
			    			'order_details'       => $order_details,
			    		);

			    		$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $return_details;
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

			else if($method == '_changePurchaseReturn')
			{
				$auto_id  = $this->input->post('auto_id');
				$progress = $this->input->post('progress');
				$reason   = $this->input->post('reason');
				$date     = date('Y-m-d');
				$time     = date('H:i:s');
				$c_date   = date('Y-m-d H:i:s');

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
			    	$where_1 = array(
			    		'id'        => $auto_id,
			    		'status'    => '1',
			    		'published' => '1',
			    	);

			    	$bill_det = $this->distributorpurchase_model->getDistributorPurchaseReturn($where_1);

			    	$distributor_id = !empty($bill_det[0]->distributor_id)?$bill_det[0]->distributor_id:'';
			    	$ord_status     = !empty($bill_det[0]->order_status)?$bill_det[0]->order_status:'';

			    	if($ord_status != $progress)
			    	{
			    		if($progress == '1')
			    		{
			    			$update_data = array(
				    			'order_status' => $progress,
				    			'_ordered'     => date('Y-m-d H:i:s'),
				    		);
			    		}	
			    		else if($progress == '5')
			    		{
			    			// Product Stock Process
					    	$whr_1 = array(
					    		'order_id'  => $auto_id,
					    		'published' => '1',
			    				'status'    => '1',
					    	);

					    	$col_1 = 'id, assproduct_id, type_id, product_qty, product_unit';

				    		$val_1 = $this->distributorpurchase_model->getDistributorPurchaseReturnDetails($whr_1, '', '', 'result', '', '', '', '', $col_1);

				    		if($val_1)
				    		{
				    			foreach ($val_1 as $key => $res_1) {
				    				$return_id     = !empty($res_1->id)?$res_1->id:'';
						            $assproduct_id = !empty($res_1->assproduct_id)?$res_1->assproduct_id:'';
						            $type_id       = !empty($res_1->type_id)?$res_1->type_id:'';
						            $product_qty   = !empty($res_1->product_qty)?$res_1->product_qty:'0';
						            $product_unit  = !empty($res_1->product_unit)?$res_1->product_unit:'';

						            // Product Type Details
									$whr_2 = array('id' => $type_id);
									$col_2 = 'product_type';
									$val_2 = $this->commom_model->getProductType($whr_2, '', '', 'result', '', '', '', '', $col_2);

									$pdt_typ = !empty($val_2[0]->product_type)?$val_2[0]->product_type:'0';

									// View Stock
							    	if($product_unit == 1)
						    		{
						    			$multiple_stk   = $product_qty * $pdt_typ; // 5 X 1 = 5 Kg
						    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
						    			$received_stock = $product_qty; // 5 Kg
						    		}
						    		else if($product_unit == 2)
						    		{
						    			$product_stock  = $product_qty * $pdt_typ; // 5 X 100 = 500 Gram
						    			$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
						    			$received_stock = number_format($received_value, 2);
						    		}
						    		else
						    		{
						    			$product_stock  = $product_qty * $pdt_typ; // 5 X 1 = 5 Nos
						    			$received_stock = $product_qty; // 5 Nos
						    		}

						    		$whr_3 = array('id' => $assproduct_id);
						    		$col_3 = 'stock, view_stock';
						    		$val_3 = $this->assignproduct_model->getAssignProductDetails($whr_3, '', '', 'result', '', '', '', '', $col_3);

						    		$pdt_stk = !empty($val_3[0]->stock)?$val_3[0]->stock:'0';
									$viw_stk = !empty($val_3[0]->view_stock)?$val_3[0]->view_stock:'0';

									// Stock 
						    		$new_stock    = $pdt_stk - $received_stock;
						    		$new_view_stk = $viw_stk - $product_stock;

						    		$upt_data = array(
						    			'stock' => $new_stock,
						    			'view_stock'    => $new_view_stk,
						    		);

						    		$update_id = array('id' => $assproduct_id);
									$update    = $this->assignproduct_model->assignProductDetails_update($upt_data, $update_id);
				    			}
				    		}

			    			$update_data = array(
				    			'order_status' => $progress,
				    			'_complete'    => date('Y-m-d H:i:s'),
				    		);
			    		}
			    		else
			    		{
			    			$update_data = array(
				    			'order_status' => $progress,
				    			'reason'       => $reason,
				    			'_canceled'    => date('Y-m-d H:i:s'),
				    		);
			    		}

			    		$whr_one = array('id' => $auto_id);
					    $upt_one = $this->distributorpurchase_model->distributorPurchaseReturn_update($update_data, $whr_one);

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
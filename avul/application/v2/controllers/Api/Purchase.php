<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class Purchase extends CI_Controller {

		public function __construct()
		{
			parent::__construct();

			$this->load->model('commom_model');
			$this->load->model('purchase_model');
			$this->load->model('vendors_model');
			$this->load->model('payment_model');
			$this->load->model('invoice_model');
			$this->load->model('login_model');
			$this->load->model('assignproduct_model');
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

			if($method == '_addPurchase')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('vendor_id', 'order_date', 'purchase_value', 'active_financial');
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
			    	$vendor_id        = $this->input->post('vendor_id');
			    	$order_date       = $this->input->post('order_date');
			    	$active_financial = $this->input->post('active_financial');
			    	$purchase_value   = $this->input->post('purchase_value');

			    	$where_1 = array(
				    	'financial_year' => $active_financial,
				    );			   

					$column = 'id';

					$overalldatas  = $this->purchase_model->getPurchase($where_1, '', '', 'result', '', '', '', '', $column);

					if(!empty($overalldatas))
					{
						$overall_count = count($overalldatas);
					}
					else
					{
						$overall_count = 0;
					}

					$newbill_count = $overall_count + 1;
					$bill_number   = 'PO'.leadingZeros($newbill_count, 6);

					// Vendor Details
					$where_2     = array('id' => $vendor_id);
					$column_2    = 'company_name';
			    	$data_val    = $this->vendors_model->getVendors($where_2, '', '', 'result', '', '', '', '', $column_2);
			    	$vendor_name = isset($data_val[0]->company_name)?$data_val[0]->company_name:'';

					$data = array(
						'po_no'          => $bill_number,
				    	'vendor_id'      => $vendor_id,
				    	'vendor_name'    => $vendor_name,
				    	'order_date'     => date('Y-m-d', strtotime($order_date)),
				    	'_ordered'       => date('Y-m-d H:i:s', strtotime($order_date)),
				    	'financial_year' => $active_financial,
				    	'createdate'     => date('Y-m-d H:i:s')
				    );

				    $insert = $this->purchase_model->purchase_insert($data);

				    $product_value = json_decode($purchase_value);	

				    foreach ($product_value as $key => $value) {

				    	$value = array(
					    	'po_id'          => $insert,
					    	'vendor_id'      => $vendor_id,
					    	'product_id'     => $value->product_id,
					    	'type_id'        => $value->type_id,
					    	'product_price'  => digit_val($value->product_price),
					    	'enret_qty'      => $value->product_qty,
					    	'product_qty'    => $value->product_qty,
					    	'receive_qty'    => '0',
					    	'product_unit'   => $value->product_unit,
					    	'order_status'   => '1',
					    	'financial_year' => $active_financial,
					    	'createdate'     => date('Y-m-d H:i:s'),
					    );

					    $type_insert = $this->purchase_model->purchaseDetails_insert($value);
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

					$search = $this->input->post('search');
		    		if($search !='')
		    		{
		    			$like['name']     = $search;
		    			// $like['hsn_code'] = $search;
		    			// $like['price']    = $search;

		    			$where = array(
		    				// 'financial_year' => $financ_year,
		    				'published'      => '1'
		    			);
		    		}
		    		else
		    		{
		    			$like = [];
		    			$where = array(
		    				// 'financial_year' => $financ_year,
		    				'published'      => '1'
		    			);
		    		}

		    		$column = 'id';
					$overalldatas = $this->purchase_model->getPurchase($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->purchase_model->getPurchase($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$purchase_list = [];
						foreach ($data_list as $key => $value) {
								
							$po_id          = isset($value->id)?$value->id:'';
						    $po_no          = isset($value->po_no)?$value->po_no:'';
						    $vendor_id      = isset($value->vendor_id)?$value->vendor_id:'';
						    $order_date     = isset($value->order_date)?$value->order_date:'';
						    $order_status   = isset($value->order_status)?$value->order_status:'';
						    $financial_year = isset($value->financial_year)?$value->financial_year:'';
						    $bill           = isset($value->bill)?$value->bill:'';
						    $published      = isset($value->published)?$value->published:'';
						    $status         = isset($value->status)?$value->status:'';
						    $createdate     = isset($value->createdate)?$value->createdate:'';

						    // Vendor Name
				            $where_1     = array('id'=>$vendor_id);
					    	$data_val    = $this->vendors_model->getVendors($where_1);
					    	$vendor_name = isset($data_val[0]->company_name)?$data_val[0]->company_name:'';
					    	$contact_no  = isset($data_val[0]->contact_no)?$data_val[0]->contact_no:'';

					    	$purchase_list[] = array(
		      					'po_id'          => $po_id,
							    'po_no'          => $po_no,
							    'vendor_id'      => $vendor_id,
							    'vendor_name'    => $vendor_name,
							    'contact_no'     => $contact_no,
							    'order_date'     => date('d-M-Y', strtotime($order_date)),
							    'order_status'   => $order_status,
							    'financial_year' => $financial_year,
							    'bill'           => $bill,
							    'published'      => $published,
							    'status'         => $status,
							    'createdate'     => $createdate,
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

			else if($method == '_listVendorsPurchasePaginate')
			{
				$limit       = $this->input->post('limit');
	    		$offset      = $this->input->post('offset');
	    		$financ_year = $this->input->post('financial_year');
	    		$vendor_id   = $this->input->post('vendor_id');

	    		$error = FALSE;
			    $errors = array();
				$required = array('vendor_id', 'financial_year');
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
		    				'vendor_id'       => $vendor_id,
		    				'order_status !=' => '1',
		    				// 'financial_year'  => $financ_year,
		    				'published'       => '1'
		    			);
		    		}
		    		else
		    		{
		    			$like = [];
		    			$where = array(
		    				'vendor_id'       => $vendor_id,
		    				'order_status !=' => '1',
		    				// 'financial_year'  => $financ_year,
		    				'published'       => '1'
		    			);
		    		}

		    		$column = 'id';
					$overalldatas = $this->purchase_model->getPurchase($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->purchase_model->getPurchase($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$purchase_list = [];

						foreach ($data_list as $key => $value) {
								
							$po_id          = isset($value->id)?$value->id:'';
						    $po_no          = isset($value->po_no)?$value->po_no:'';
						    $vendor_id      = isset($value->vendor_id)?$value->vendor_id:'';
						    $order_date     = isset($value->order_date)?$value->order_date:'';
						    $order_status   = isset($value->order_status)?$value->order_status:'';
						    $financial_year = isset($value->financial_year)?$value->financial_year:'';
						    $bill           = isset($value->bill)?$value->bill:'';
						    $invoice_no     = isset($value->invoice_no)?$value->invoice_no:'';
						    $published      = isset($value->published)?$value->published:'';
						    $status         = isset($value->status)?$value->status:'';
						    $createdate     = isset($value->createdate)?$value->createdate:'';

						    // Vendor Name
				            $where_1     = array('id'=>$vendor_id);
					    	$data_val    = $this->vendors_model->getVendors($where_1);
					    	$vendor_name = isset($data_val[0]->company_name)?$data_val[0]->company_name:'';
					    	$contact_no  = isset($data_val[0]->contact_no)?$data_val[0]->contact_no:'';

					    	$purchase_list[] = array(
		      					'po_id'          => $po_id,
							    'po_no'          => $po_no,
							    'vendor_id'      => $vendor_id,
							    'vendor_name'    => $vendor_name,
							    'contact_no'     => $contact_no,
							    'order_date'     => date('d-M-Y', strtotime($order_date)),
							    'order_status'   => $order_status,
							    'financial_year' => $financial_year,
							    'bill'           => $bill,
							    'invoice_no'     => $invoice_no,
							    'published'      => $published,
							    'status'         => $status,
							    'createdate'     => $createdate,
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

			else if($method == '_detailPurchase')
			{
				$purchase_id = $this->input->post('purchase_id');

		    	if(!empty($purchase_id))
		    	{
		    		$where = array('id'=>$purchase_id);
				    $data  = $this->purchase_model->getPurchase($where);
				    if($data)
				    {
				    	$purchase_list = [];

				    	foreach ($data as $key => $value) {
				    		$po_id          = !empty($value->id)?$value->id:'';
						    $po_no          = !empty($value->po_no)?$value->po_no:'';
						    $vendor_id      = !empty($value->vendor_id)?$value->vendor_id:'';
						    $order_date     = !empty($value->order_date)?$value->order_date:'';
						    $order_status   = !empty($value->order_status)?$value->order_status:'';
						    $financial_year = !empty($value->financial_year)?$value->financial_year:'';
						    $bill           = !empty($value->bill)?$value->bill:'';
						    $published      = !empty($value->published)?$value->published:'';
						    $status         = !empty($value->status)?$value->status:'';
						    $createdate     = !empty($value->createdate)?$value->createdate:'';
						    $updatedate     = !empty($value->updatedate)?$value->updatedate:'';

						    $purchase_list[] = array(
						    	'po_id'          => $po_id,
							    'po_no'          => $po_no,
							    'vendor_id'      => $vendor_id,
							    'order_date'     => $order_date,
							    'order_status'   => $order_status,
							    'financial_year' => $financial_year,
							    'bill'           => $bill,
							    'published'      => $published,
							    'status'         => $status,
							    'createdate'     => $createdate,
							    'updatedate'     => $updatedate,
						    );
				    	}

				    	$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $purchase_list;
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

			else if($method == '_deletePurchase')
			{
				$po_id = $this->input->post('po_id');

				if(!empty($po_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

		    		$where  = array('id' => $po_id);
				    $update = $this->purchase_model->purchase_delete($data, $where);
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

			else
			{
				$response['status']  = 0;
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// Manage Purchase Details
		// ***************************************************
		public function manage_purchaseDetails($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listPurchaseDetails')
			{
				$purchase_id = $this->input->post('purchase_id');				

				if(!empty($purchase_id))
				{
					$where_1 = array(
						'id'        => $purchase_id,
						'published' => '1',
						'status'    => '1',
					);

					$bill_details = $this->purchase_model->getPurchase($where_1);

					if($bill_details)
					{
						$vendor_id    = !empty($bill_details[0]->vendor_id)?$bill_details[0]->vendor_id:'';
						$_ordered     = !empty($bill_details[0]->_ordered)?$bill_details[0]->_ordered:'';
						$_processing  = !empty($bill_details[0]->_processing)?$bill_details[0]->_processing:'';
						$_packing     = !empty($bill_details[0]->_packing)?$bill_details[0]->_packing:'';
						$_shiped      = !empty($bill_details[0]->_shiped)?$bill_details[0]->_shiped:'';
						$_delivery    = !empty($bill_details[0]->_delivery)?$bill_details[0]->_delivery:'';
						$_complete    = !empty($bill_details[0]->_complete)?$bill_details[0]->_complete:'';
						$_canceled    = !empty($bill_details[0]->_canceled)?$bill_details[0]->_canceled:'';
						$reason       = !empty($bill_details[0]->reason)?$bill_details[0]->reason:'';
						$inv_value    = !empty($bill_details[0]->inv_value)?$bill_details[0]->inv_value:'';

						$where_2 = array(
							'id'        => $vendor_id,
							'published' => '1',
						);

						$vendor_data = $this->vendors_model->getVendors($where_2);

						$where_3 = array(
							'po_id'         => $purchase_id,
							'delete_status' => '1',
							'published'     => '1',
							'status'        => '1',
						);

						$purchase_data = $this->purchase_model->getPurchaseDetails($where_3);

						// Bill Details
						$purchase_id  = !empty($bill_details[0]->id)?$bill_details[0]->id:'';
			            $purchase_no  = !empty($bill_details[0]->po_no)?$bill_details[0]->po_no:'';
			            $vendor_id    = !empty($bill_details[0]->vendor_id)?$bill_details[0]->vendor_id:'';
			            $order_status = !empty($bill_details[0]->order_status)?$bill_details[0]->order_status:'';
			            $order_date   = !empty($bill_details[0]->order_date)?$bill_details[0]->order_date:'';

			            $bill_details = array(
			            	'purchase_id'  => $purchase_id,
				            'purchase_no'  => $purchase_no,
				            'vendor_id'    => $vendor_id,
				            'order_status' => $order_status,
				            '_ordered'     => $_ordered,
							'_processing'  => $_processing,
							'_packing'     => $_packing,
							'_shiped'      => $_shiped,
							'_delivery'    => $_delivery,
							'_complete'    => $_complete,
							'_canceled'    => $_canceled,
							'reason'       => $reason,
							'inv_value'    => $inv_value,
				            'order_date'   => date('d-M-Y', strtotime($order_date)),
			            );

			            // Vendor Details
			            $comp_name = !empty($vendor_data[0]->company_name)?$vendor_data[0]->company_name:'';
			            $gst_no    = !empty($vendor_data[0]->gst_no)?$vendor_data[0]->gst_no:'';
			            $contact   = !empty($vendor_data[0]->contact_no)?$vendor_data[0]->contact_no:'';
			            $email     = !empty($vendor_data[0]->email)?$vendor_data[0]->email:'';
			            $address   = !empty($vendor_data[0]->address)?$vendor_data[0]->address:'';
			            $due_days  = !empty($vendor_data[0]->due_days)?$vendor_data[0]->due_days:'';
			            $state_id  = !empty($vendor_data[0]->state_id)?$vendor_data[0]->state_id:'';
			            $pur_type  = !empty($vendor_data[0]->purchase_type)?$vendor_data[0]->purchase_type:'';

			            $due_value = '';
			            if(!empty($due_days))
			            {
			            	$due_value = $due_days.' Days';
			            }

			            $vendor_details = array(
			            	'company_name'  => $comp_name,
				            'gst_no'        => $gst_no,
				            'contact_no'    => $contact,
				            'email'         => $email,
				            'address'       => $address,
				            'due_days'      => $due_value,
				            'state_id'      => $state_id,
				            'purchase_type' => $pur_type,
			            );

			            // Purchase Details
			            $purchase_details = [];

			            if($purchase_data)
			            {
			            	foreach ($purchase_data as $key => $value) {

				            	$item_id       = isset($value->id)?$value->id:'';
					            $product_id    = isset($value->product_id)?$value->product_id:'';
					            $type_id        = isset($value->type_id)?$value->type_id:'';
					            $product_price = isset($value->product_price)?$value->product_price:'';
					            $product_qty   = isset($value->product_qty)?$value->product_qty:'';
					            $product_unit  = isset($value->product_unit)?$value->product_unit:'';
					            $pack_status   = isset($value->pack_status)?$value->pack_status:'';

					            // Received Stock Details
						    	$where_2 = array(
						    		'po_id'      => $purchase_id,
						    		'po_auto_id' => $item_id,
						    		'product_id' => $product_id,
						    		'published'  => '1',
						    		'status'     => '1',
						    	);

						    	$column_2 = 'received_qty';

						    	$collect_data = $this->purchase_model->getPurchaseStkDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

						    	$received_cou = 0;
						    	if(!empty($collect_data))
						    	{
						    		foreach ($collect_data as $key => $qty_val) {
						    			$received_val  = !empty($qty_val->received_qty)?$qty_val->received_qty:'0';
			    						$received_cou += $received_val; 
						    		}
						    	}

								// Product Name
						    	$where_4      = array('id' => $product_id);
						    	$product_val  = $this->commom_model->getProduct($where_4);
						    	$product_name = isset($product_val[0]->name)?$product_val[0]->name:'';		
						    	$hsn_code     = isset($product_val[0]->hsn_code)?$product_val[0]->hsn_code:'';
						    	$gst_value    = isset($product_val[0]->gst)?$product_val[0]->gst:'';
						    	$category_id  = isset($product_val[0]->category_id)?$product_val[0]->category_id:'';	

						    	// Category Name
						    	$where_5       = array('id' => $category_id);
						    	$category_val  = $this->commom_model->getCategory($where_5);
						    	$category_name = isset($category_val[0]->name)?$category_val[0]->name:'';

						    	// Product Type Details
						    	$where_6    = array('id' => $type_id);
						    	$type_val   = $this->commom_model->getProductType($where_6);
						    	$type_name  = isset($type_val[0]->description)?$type_val[0]->description:'';
						    	$type_stock = isset($type_val[0]->type_stock)?$type_val[0]->type_stock:'';

					            // Unit Name
						    	$where_7   = array('id' => $product_unit);
						    	$unit_val  = $this->commom_model->getUnit($where_7);
						    	$unit_name = isset($unit_val[0]->name)?$unit_val[0]->name:'';

						    	$purchase_details[] = array(
						    		'item_id'       => $item_id,
			      					'product_id'    => $product_id,
			      					'product_name'  => $product_name,
			      					'type_id'       => $type_id,
			      					'type_name'     => $type_name,
			      					'hsn_code'      => $hsn_code,
			      					'gst_value'     => $gst_value,
			      					'category_name' => $category_name,
			      					'unit_name'     => $unit_name,
			      					'product_price' => $product_price,
			      					'product_qty'   => $product_qty,
			      					'stock_qty'     => $type_stock,
			      					'product_unit'  => $product_unit,
			      					'pack_status'   => $pack_status,
			      					'received_qty'  => strval($received_cou),
			      				);
				            }
			            }

			            $inventory_data = array(
			            	'bill_details'     => $bill_details,
			            	'vendor_details'   => $vendor_details,
			            	'purchase_details' => $purchase_details,
			            );

		            	$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $inventory_data;
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

			if($method == '_listProductDetails')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('purchase_id', 'auto_id');
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
			    	$purchase_id = $this->input->post('purchase_id');
			    	$auto_id     = $this->input->post('auto_id');

			    	$where_1 = array(
						'id'        => $auto_id,
						'po_id'     => $purchase_id,
						'published' => '1',
						'status'    => '1',
					);

					$purchase_data = $this->purchase_model->getPurchaseDetails($where_1);

					if($purchase_data)
					{
						$item_id      = !empty($purchase_data[0]->id)?$purchase_data[0]->id:'';
						$po_id        = !empty($purchase_data[0]->po_id)?$purchase_data[0]->po_id:'';
						$product_id   = !empty($purchase_data[0]->product_id)?$purchase_data[0]->product_id:'';
						$type_id      = !empty($purchase_data[0]->type_id)?$purchase_data[0]->type_id:'';
						$product_unit = !empty($purchase_data[0]->product_unit)?$purchase_data[0]->product_unit:'';
						$product_qty  = !empty($purchase_data[0]->product_qty)?$purchase_data[0]->product_qty:'0';
						$receive_qty  = !empty($purchase_data[0]->receive_qty)?$purchase_data[0]->receive_qty:'0';
						$balance_qty  = $product_qty - $receive_qty;

						// Product Name
				    	$where_2      = array('id' => $product_id);
				    	$product_val  = $this->commom_model->getProduct($where_2);
				    	$product_name = isset($product_val[0]->name)?$product_val[0]->name:'';	

				    	// Product Name
				    	$where_3   = array('id' => $type_id);
				    	$type_val  = $this->commom_model->getProductType($where_3);
				    	$type_name = isset($type_val[0]->description)?$type_val[0]->description:'';	

				    	$purchase_details = array(
				    		'item_id'       => $item_id,
				    		'po_id'         => $po_id,
	      					'product_id'    => $product_id,
	      					'product_name'  => $product_name,
	      					'type_id'       => $type_id,
	      					'type_name'     => $type_name,
	      					'product_unit'  => $product_unit,
	      					'balance_qty'   => $balance_qty,
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
			}

			if($method == '_invoiceDetails')
			{
				$inv_value = $this->input->post('inv_value');

				$error = FALSE;
			    $errors = array();
				$required = array('inv_value');
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
			    	// Invoice Details
		    		$inv_whr = array(
		    			'random_value' => $inv_value,
		    			'published'    => '1',
		    			'status'       => '1',
		    		);

		    		$inv_col = 'id, invoice_no, vendor_id, due_days, order_id, createdate';
			    	$inv_det = $this->invoice_model->getVendorInvoice($inv_whr, '', '', 'result', '', '', '', '', $inv_col);

			    	if(!empty($inv_det))
			    	{
			    		$inv_res = $inv_det[0];

			    		$invoice_id = !empty($inv_res->id)?$inv_res->id:'';
			    		$invoice_no = !empty($inv_res->invoice_no)?$inv_res->invoice_no:'';
			            $vendor_id  = !empty($inv_res->vendor_id)?$inv_res->vendor_id:'';
			            $due_days   = !empty($inv_res->due_days)?$inv_res->due_days:'';
			            $order_id   = !empty($inv_res->order_id)?$inv_res->order_id:'';
			            $inv_date   = !empty($inv_res->createdate)?$inv_res->createdate:'';

			            $pur_whr = array(
			            	'id' => $order_id,
			            );

			            $pur_col = 'po_no, createdate';
			            $pur_det = $this->purchase_model->getPurchase($pur_whr, '', '', 'result', '', '', '', '', $pur_col);

			            $pur_res = $pur_det[0];

			            $po_no   = !empty($pur_res->po_no)?$pur_res->po_no:'';
			            $po_date = !empty($pur_res->createdate)?$pur_res->createdate:'';

			            $ved_whr = array(
			    			'id' => $vendor_id,
			    		);

			    		$ved_col = 'company_name, gst_no, contact_no, email, address, account_name, account_no, ifsc_code, bank_name, branch_name, state_id';
			    		$ved_det = $this->vendors_model->getVendors($ved_whr, '', '', 'result', '', '', '', '', $ved_col);

			    		$ved_res = $ved_det[0];

			    		$company_name = !empty($ved_res->company_name)?$ved_res->company_name:'';
			            $gst_no       = !empty($ved_res->gst_no)?$ved_res->gst_no:'';
			            $contact_no   = !empty($ved_res->contact_no)?$ved_res->contact_no:'';
			            $email        = !empty($ved_res->email)?$ved_res->email:'';
			            $address      = !empty($ved_res->address)?$ved_res->address:'';
			            $account_name = !empty($ved_res->account_name)?$ved_res->account_name:'';
			            $account_no   = !empty($ved_res->account_no)?$ved_res->account_no:'';
			            $ifsc_code    = !empty($ved_res->ifsc_code)?$ved_res->ifsc_code:'';
			            $bank_name    = !empty($ved_res->bank_name)?$ved_res->bank_name:'';
			            $branch_name  = !empty($ved_res->branch_name)?$ved_res->branch_name:'';
			            $vendor_state = !empty($ved_res->state_id)?$ved_res->state_id:'';

			            $adm_whr = array(
			    			'id' => '1',
			    		);

			    		$adm_col = 'state_id';
			    		$adm_det = $this->login_model->getLoginStatus($adm_whr, '', '', 'result', '', '', '', '', $adm_col);

			    		$adm_res = $adm_det[0];

			    		$admin_state = !empty($adm_res->state_id)?$adm_res->state_id:'';

			            $vendor_details = array(
			            	'invoice_no'   => $invoice_no,
			            	'inv_date'     => date('d-m-Y H:i:s', strtotime($inv_date)),
			            	'po_no'        => $po_no,
			            	'po_date'      => date('d-m-Y H:i:s', strtotime($po_date)),
			            	'company_name' => $company_name,
			            	'gst_no'       => $gst_no,
			            	'email'        => $email,
			            	'address'      => $address,
			            	'account_name' => $account_name,
			            	'account_no'   => $account_no,
			            	'ifsc_code'    => $ifsc_code,
			            	'bank_name'    => $bank_name,
			            	'branch_name'  => $branch_name,
			            	'vendor_state' => $vendor_state,
			            	'admin_state'  => $admin_state,
			            );

			            // Product Details
			            $ord_whr = array(
			            	'invoice_id' => $invoice_id,
			            	'published'  => '1',
		    				'status'     => '1',
			            );

			            $ord_col = 'type_id, hsn_code, gst_val, unit_val, price, order_qty';
			            $ord_det = $this->invoice_model->getVendInvoiceDetails($ord_whr, '', '', 'result', '', '', '', '', $ord_col);

			            $order_details = [];
			            foreach ($ord_det as $key => $value) {
			            	$type_id   = !empty($value->type_id)?$value->type_id:'';
				            $hsn_code  = !empty($value->hsn_code)?$value->hsn_code:'';
				            $gst_val   = !empty($value->gst_val)?$value->gst_val:'0';
				            $unit_val  = !empty($value->unit_val)?$value->unit_val:'0';
				            $price     = !empty($value->price)?$value->price:'0';
				            $order_qty = !empty($value->order_qty)?$value->order_qty:'0';

				            // Product Details
				            $type_whr = array(
				            	'id' => $type_id,
				            );

				            $type_col = 'description';
				            $type_det = $this->commom_model->getProductType($type_whr, '', '', 'result', '', '', '', '', $type_col);

				            $type_res = $type_det[0];

				            $pdt_desc = !empty($type_res->description)?$type_res->description:'';

				            $order_details[] = array(
				            	'description' => $pdt_desc,
				            	'hsn_code'    => $hsn_code,
				            	'gst_val'     => $gst_val,
				            	'unit_val'    => $unit_val,
				            	'price'       => $price,
				            	'order_qty'   => $order_qty,
				            );
			            }

			            // Tax Details
	        			$tax_where = array(
	        				'invoice_id' => $invoice_id,
	        				'published'  => '1',
	        				'status'     => '1',
	        			);
	        			$tax_column = 'hsn_code, gst_val';
	        			$tax_group  = 'hsn_code, gst_val';

	        			$tax_data   = $this->invoice_model->getVendInvoiceDetails($tax_where, '', '', 'result', '', '', '', '', $tax_column, $tax_group);

	        			$tax_details = [];
	        			if(!empty($tax_data))
	        			{
	        				foreach ($tax_data as $key => $value) {
	        					$hsn_code = !empty($value->hsn_code)?$value->hsn_code:'';
	        					$gst_val  = !empty($value->gst_val)?$value->gst_val:'';

	        					// Price Details
	        					$price_where = array(
			        				'invoice_id' => $invoice_id,
			        				'hsn_code'   => $hsn_code,
			        				'gst_val'    => $gst_val,
			        				'published'  => '1',
			        				'status'     => '1',
			        			);

			        			$price_column = 'price, order_qty, gst_val';

			        			$price_data   = $this->invoice_model->getVendInvoiceDetails($price_where, '', '', 'result', '', '', '', '', $price_column);

			        			$product_price = 0;
			        			$gst_price     = 0;
			        			foreach ($price_data as $key => $pd_val) {

			        				$price     = !empty($pd_val->price)?$pd_val->price:'';
			        				$order_qty = !empty($pd_val->order_qty)?$pd_val->order_qty:'';
			        				$gst_val   = !empty($pd_val->gst_val)?$pd_val->gst_val:'';

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

	        			$invoice_details = array(
	        				'vendor_details' => $vendor_details,
	        				'order_details'  => $order_details,
	        				'tax_details'    => $tax_details,
	        			);

	        			$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $invoice_details;
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

			else
			{
				$response['status']  = 0;
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// Manage Purchase Stock Details
		// ***************************************************
		public function manage_purchaseStkDetails($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addPurchaseStockDetails')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('po_id', 'po_auto_id', 'product_id', 'type_id', 'product_unit', 'bill_no', 'received_qty', 'received_date');
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
			    	$po_id         = $this->input->post('po_id');
			    	$po_auto_id    = $this->input->post('po_auto_id');
			    	$product_id    = $this->input->post('product_id');
			    	$type_id       = $this->input->post('type_id');
			    	$product_unit  = $this->input->post('product_unit');
			    	$bill_no       = $this->input->post('bill_no');
			    	$received_qty  = $this->input->post('received_qty');
			    	$received_date = $this->input->post('received_date');

			    	// Get Order Qty
			    	$where_1 = array(
			    		'id'         => $po_auto_id,
			    		'po_id'      => $po_id,
			    		'product_id' => $product_id,
			    		'type_id'    => $type_id,
			    		'published'  => '1',
			    		'status'     => '1',
			    	);

			    	$column_1 = 'product_qty';

			    	$order_data  = $this->purchase_model->getPurchaseDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

			    	$product_qty = !empty($order_data[0]->product_qty)?$order_data[0]->product_qty:'';

			    	// Collect Order Qty
			    	$where_2 = array(
			    		'po_id'      => $po_id,
			    		'po_auto_id' => $po_auto_id,
			    		'product_id' => $product_id,
			    		'type_id'    => $type_id,
			    		'published'  => '1',
			    		'status'     => '1',
			    	);

			    	$column_2 = 'received_qty';

			    	$collect_data = $this->purchase_model->getPurchaseStkDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

			    	$received_cou = 0;
			    	if(!empty($collect_data))
			    	{
			    		foreach ($collect_data as $key => $value) {
				    		$received_val  = !empty($value->received_qty)?$value->received_qty:'';
				    		$received_cou += $received_val; 
				    	}
			    	}

			    	// Overall Collect Data
			    	$over_collect = $product_qty - $received_cou;

			    	if($over_collect >= $received_qty)
			    	{
			    		// Product Stock Add
				    	$where_3 = array('id' => $type_id);

				    	$product_val = $this->commom_model->getProductType($where_3);

				    	$pdt_type    = !empty($product_val[0]->product_type)?$product_val[0]->product_type:'0';	
				    	$pdt_stock   = !empty($product_val[0]->product_stock)?$product_val[0]->product_stock:'0';	
				    	$view_stock  = !empty($product_val[0]->view_stock)?$product_val[0]->view_stock:'0';	
				    	$type_stock  = !empty($product_val[0]->type_stock)?$product_val[0]->type_stock:'0';	
				    	$stk_detail  = !empty($product_val[0]->stock_detail)?$product_val[0]->stock_detail:'0';	

				    	// View Stock
						if($product_unit == 1 || $product_unit == 11)
			    		{
			    			$multiple_stk   = $received_qty * $pdt_type; // 5 X 1 = 5 Kg
			    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
			    			$received_stock = $received_qty; // 5 Kg
			    		}
			    		else if($product_unit == 2 || $product_unit == 4)
			    		{
			    			$product_stock  = $received_qty * $pdt_type; // 5 X 100 = 500 Gram
			    			$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
			    			$received_stock = number_format($received_value, 2);
			    		}
			    		else
			    		{
			    			$product_stock  = $received_qty * $pdt_type; // 5 X 1 = 5 Nos
			    			$received_stock = $received_qty; // 5 Nos
			    		}

			    		// Stock 
			    		$new_stock        = $pdt_stock + $received_stock;
			    		$new_view_stk     = $view_stock + $product_stock;
			    		$new_type_stock   = $type_stock - $received_stock;
			    		$new_stock_detail = $stk_detail - $product_stock;


			    		$upt_data = array(
			    			'product_stock' => $new_stock,
			    			'view_stock'    => $new_view_stk,
			    			'type_stock'    => $new_type_stock,
			    			'stock_detail'  => $new_stock_detail,
			    		);

			    		$update_id = array('id'=>$type_id);
						$update    = $this->commom_model->productType_update($upt_data, $update_id);

						// Production Details
			    		$overColl_qty = $received_cou + $received_qty;

			    		$produc_data = array(
			    			'receive_qty' => strval($overColl_qty),
			    		);

			    		$produc_whr   = array('id' => $po_auto_id);
			    		$update_prodc = $this->purchase_model->purchaseDetails_update($produc_data, $produc_whr);

				    	$ins_data = array(
					    	'po_id'         => $po_id,
					    	'po_auto_id'    => $po_auto_id,
					    	'product_id'    => $product_id,
					    	'type_id'       => $type_id,
					    	'product_unit'  => $product_unit,
					    	'bill_no'       => $bill_no,
					    	'received_qty'  => $received_qty,
					    	'received_date' => date('Y-m-d', strtotime($received_date)),
					    	'createdate'    => date('Y-m-d H:i:s')
					    );

					    $insert = $this->purchase_model->purchaseStkDetails_insert($ins_data);

					    // Collect Order Qty
				    	$where_4 = array(
				    		'po_id'      => $po_id,
				    		'po_auto_id' => $po_auto_id,
				    		'product_id' => $product_id,
				    		'type_id'    => $type_id,
				    		'published'  => '1',
				    		'status'     => '1',
				    	);

				    	$column_4 = 'received_qty';

				    	$new_collect_data = $this->purchase_model->getPurchaseStkDetails($where_4, '', '', 'result', '', '', '', '', $column_4);

				    	$new_received_cou = 0;
				    	if(!empty($new_collect_data))
				    	{
				    		foreach ($new_collect_data as $key => $value) {
					    		$received_val  = !empty($value->received_qty)?$value->received_qty:'';
					    		$new_received_cou += $received_val; 
					    	}
				    	}

				    	// Product Process Update
				    	if($product_qty == $new_received_cou)
				    	{
		            		$pur_data = array('product_process' => '2');
		            		$pur_whr  = array('id' => $po_auto_id);
		            		$pur_upt  = $this->purchase_model->purchaseDetails_update($pur_data, $pur_whr);
				    	}

				    	$whr_one = array(
		            		'po_id'           => $po_id,
		            		'product_process' => '2',
		            		'delete_status'   => '1',
		            		'status'          => '1',
		            		'published'       => '1',
		            	);

		            	$value_one = $this->purchase_model->getPurchaseDetails($whr_one,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

			            $count_one = !empty($value_one[0]->autoid)?$value_one[0]->autoid:'0';

			            $whr_two = array(
		            		'po_id'           => $po_id,
		            		'delete_status'   => '1',
		            		'status'          => '1',
		            		'published'       => '1',
		            	);

		            	$value_two = $this->purchase_model->getPurchaseDetails($whr_two,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

			            $count_two = !empty($value_two[0]->autoid)?$value_two[0]->autoid:'0';

				    	if($count_one == $count_two)
				    	{
				    		$pur_data = array('bill_process' => '2');
		            		$pur_whr  = array('id' => $po_id);
		            		$pur_upt  = $this->purchase_model->purchase_update($pur_data, $pur_whr);
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
				        $response['message'] = "Invalide Quantity"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			    }
			}

			else if($method == '_listPurchaseStockDetails')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('po_id', 'po_auto_id', 'product_id');
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
			    	$po_id      = $this->input->post('po_id');
			    	$po_auto_id = $this->input->post('po_auto_id');
			    	$product_id = $this->input->post('product_id');

			    	$where_1 = array(
			    		'po_id'      => $po_id,
			    		'po_auto_id' => $po_auto_id,
			    		'product_id' => $product_id,
			    		'published'  => '1',
			    		'status'     => '1',
			    	);

			    	$stock_details = $this->purchase_model->getPurchaseStkDetails($where_1);

			    	if($stock_details)
			    	{
			    		$stock_list = [];
			    		foreach ($stock_details as $key => $value) {
			    				
			    			$stock_id       = !empty($value->id)?$value->id:'';
							$product_unit  = !empty($value->product_unit)?$value->product_unit:'';
							$bill_no       = !empty($value->bill_no)?$value->bill_no:'';
							$received_qty  = !empty($value->received_qty)?$value->received_qty:'';
							$received_date = !empty($value->received_date)?$value->received_date:'';

							// Unit Name
					    	$where_2   = array('id' => $product_unit);
					    	$unit_val  = $this->commom_model->getUnit($where_2);
					    	$unit_name = isset($unit_val[0]->name)?$unit_val[0]->name:'';

					    	$stock_list[] = array(
					    		'stock_id'      => $stock_id,
								'product_unit'  => $product_unit,
								'unit_name'     => $unit_name,
								'bill_no'       => $bill_no,
								'received_qty'  => $received_qty,
								'received_date' => date('d-M-Y', strtotime($received_date)),
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

			else if($method == '_deletePurchaseStockDetails')
			{
				$stock_id = $this->input->post('stock_id');

				if(!empty($stock_id))
				{
					$where_1 = array(
						'id'        => $stock_id,
						'published' => '1',
			    		'status'    => '1',
					);

					$stk_det      = $this->purchase_model->getPurchaseStkDetails($where_1);
					$po_id        = !empty($stk_det[0]->po_id)?$stk_det[0]->po_id:'';
					$po_auto_id   = !empty($stk_det[0]->po_auto_id)?$stk_det[0]->po_auto_id:'';
					$product_id   = !empty($stk_det[0]->product_id)?$stk_det[0]->product_id:'';
					$type_id      = !empty($stk_det[0]->type_id)?$stk_det[0]->type_id:'';
			    	$product_unit = !empty($stk_det[0]->product_unit)?$stk_det[0]->product_unit:'';
			    	$received_qty = !empty($stk_det[0]->received_qty)?$stk_det[0]->received_qty:'';


		    		// Collect Order Qty
			    	$where_2 = array(
			    		'po_id'      => $po_id,
			    		'po_auto_id' => $po_auto_id,
			    		'product_id' => $product_id,
			    		'type_id'    => $type_id,
			    		'published'  => '1',
			    		'status'     => '1',
			    	);

		    		$column_2 = 'received_qty';

			    	$collect_data = $this->purchase_model->getPurchaseStkDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

			    	$received_cou = 0;
			    	if(!empty($collect_data))
			    	{
			    		foreach ($collect_data as $key => $value) {
				    		$received_val  = !empty($value->received_qty)?$value->received_qty:'';
				    		$received_cou += $received_val; 
				    	}
			    	}

			    	// Product Stock Add
			    	$where_3 = array('id' => $type_id);
			    	$pdt_val = $this->commom_model->getProductType($where_3);

			    	$pdt_type    = !empty($pdt_val[0]->product_type)?$pdt_val[0]->product_type:'0';	
			    	$pdt_stock   = !empty($pdt_val[0]->product_stock)?$pdt_val[0]->product_stock:'0';	
			    	$view_stock  = !empty($pdt_val[0]->view_stock)?$pdt_val[0]->view_stock:'0';	
			    	$type_stock  = !empty($pdt_val[0]->type_stock)?$pdt_val[0]->type_stock:'0';	
			    	$stk_detail  = !empty($pdt_val[0]->stock_detail)?$pdt_val[0]->stock_detail:'0';	

			    	// View Stock
			    	if($product_unit == 1 || $product_unit == 11)
		    		{
		    			$multiple_stk   = $received_qty * $pdt_type; // 5 X 1 = 5 Kg
		    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
		    			$received_stock = $received_qty; // 5 Kg
		    		}
		    		else if($product_unit == 2 || $product_unit == 4)
		    		{
		    			$product_stock  = $received_qty * $pdt_type; // 5 X 100 = 500 Gram
		    			$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
		    			$received_stock = number_format($received_value, 2);
		    		}
		    		else
		    		{
		    			$product_stock  = $received_qty * $pdt_type; // 5 X 1 = 5 Nos
		    			$received_stock = $received_qty; // 5 Nos
		    		}

		    		// Stock 
		    		$new_stock        = $pdt_stock - $received_stock;
		    		$new_view_stk     = $view_stock - $product_stock;
		    		$new_type_stock   = $type_stock + $received_stock;
		    		$new_stock_detail = $stk_detail + $product_stock;

		    		$upt_data = array(
		    			'product_stock' => $new_stock,
		    			'view_stock'    => $new_view_stk,
		    			'type_stock'    => $new_type_stock,
		    			'stock_detail'  => $new_stock_detail,
		    		);

		    		$update_id = array('id' => $type_id);
					$update    = $this->commom_model->productType_update($upt_data, $update_id);

					// Production Details
		    		$overColl_qty = $received_cou - $received_qty;

		    		$product_data = array(
		    			'receive_qty' => strval($overColl_qty),
		    		);

		    		$product_whr = array('id' => $po_auto_id);
		    		$update_stk  = $this->purchase_model->purchaseDetails_update($product_data, $product_whr);

					// Delete Stock List
					$data = array(
				    	'published' => '0',
				    );

		    		$where  = array('id' => $stock_id);
				    $update = $this->purchase_model->purchaseStkDetails_delete($data, $where);

				    // Product Process Update
            		$pro_data = array('product_process' => '1');
            		$pro_whr  = array('id' => $po_auto_id);
            		$pro_upt  = $this->purchase_model->purchaseDetails_update($pro_data, $pro_whr);

            		// Purchase Process Update
            		$pur_data = array('bill_process' => '1');
            		$pur_whr  = array('id' => $po_id);
            		$pur_upt  = $this->purchase_model->purchase_update($pur_data, $pur_whr);

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

			else if($method == '_addPurchaseEntry')
			{
				$error = FALSE;
				$vendor_id        = $this->input->post('vendor_id');
				$purchase_id      = $this->input->post('purchase_id');
			    $bill_no          = $this->input->post('bill_no');
			    $received_date    = $this->input->post('received_date');
			    $stock_type       = $this->input->post('stock_type');
			    $active_financial = $this->input->post('active_financial');
			    $date             = date('Y-m-d');
				$time             = date('H:i:s');
				$c_date           = date('Y-m-d H:i:s');

			    $required = array('vendor_id', 'purchase_id', 'bill_no', 'received_date', 'stock_type');
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
			    	$stock_detail = json_decode($stock_type);

			    	foreach ($stock_detail as $key => $val) {
			    		$item_id      = !empty($val->item_id)?$val->item_id:'';
			            $product_id   = !empty($val->product_id)?$val->product_id:'';
			            $type_id      = !empty($val->type_id)?$val->type_id:'';
			            $product_unit = !empty($val->product_unit)?$val->product_unit:'';
			            $product_qty  = !empty($val->product_qty)?$val->product_qty:'';
			            $received_qty = !empty($val->received_qty)?$val->received_qty:'';

			            if(!empty($received_qty))
			            {
			            	 // Collect Order Qty
					    	$where_1 = array(
					    		'po_id'      => $purchase_id,
					    		'po_auto_id' => $item_id,
					    		'product_id' => $product_id,
					    		'type_id'    => $type_id,
					    		'published'  => '1',
					    		'status'     => '1',
					    	);

					    	$stock_val = $this->purchase_model->getPurchaseStkDetails($where_1,'','',"result",array(),array(),array(),TRUE,'SUM(received_qty) AS rec_qty');

							$stock_count = !empty($stock_val[0]->rec_qty)?$stock_val[0]->rec_qty:'0';

							// Overall Collect Data
					    	$over_collect = $product_qty - $stock_count;

					    	if($over_collect >= $received_qty)
					    	{
					    		// Product Stock Add
						    	$where_2 = array('id' => $type_id);

						    	$product_val = $this->commom_model->getProductType($where_2);

						    	$pdt_type    = !empty($product_val[0]->product_type)?$product_val[0]->product_type:'0';	
						    	$pdt_stock   = !empty($product_val[0]->product_stock)?$product_val[0]->product_stock:'0';	
						    	$view_stock  = !empty($product_val[0]->view_stock)?$product_val[0]->view_stock:'0';	
						    	$type_stock  = !empty($product_val[0]->type_stock)?$product_val[0]->type_stock:'0';	
						    	$stk_detail  = !empty($product_val[0]->stock_detail)?$product_val[0]->stock_detail:'0';	

						    	// View Stock
						    	if($product_unit == 1 || $product_unit == 11)
					    		{
					    			$multiple_stk   = $received_qty * $pdt_type; // 5 X 1 = 5 Kg
					    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
					    			$received_stock = $received_qty; // 5 Kg
					    		}
					    		else if($product_unit == 2 || $product_unit == 4)
					    		{
					    			$product_stock  = $received_qty * $pdt_type; // 5 X 100 = 500 Gram
					    			$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
					    			$received_stock = number_format($received_value, 2);
					    		}
					    		else
					    		{
					    			$product_stock  = $received_qty * $pdt_type; // 5 X 1 = 5 Nos
					    			$received_stock = $received_qty; // 5 Nos
					    		}

					    		// Stock 
					    		$new_stock        = $pdt_stock + $received_stock;
					    		$new_view_stk     = $view_stock + $product_stock;
					    		
					    		$upt_data = array(
					    			'product_stock' => $new_stock,
					    			'view_stock'    => $new_view_stk,
					    		);

					    		$update_id = array('id'=>$type_id);
								$update    = $this->commom_model->productType_update($upt_data, $update_id);

								// Production Details
					    		$overColl_qty = $stock_count + $received_qty;

					    		$produc_data = array(
					    			'receive_qty' => strval($overColl_qty),
					    		);

					    		$produc_whr   = array('id' => $item_id);
					    		$update_prodc = $this->purchase_model->purchaseDetails_update($produc_data, $produc_whr);

						    	$ins_data = array(
							    	'po_id'         => $purchase_id,
					    			'po_auto_id'    => $item_id,
							    	'product_id'    => $product_id,
							    	'type_id'       => $type_id,
							    	'product_unit'  => $product_unit,
							    	'bill_no'       => $bill_no,
							    	'received_qty'  => $received_qty,
							    	'received_date' => date('Y-m-d', strtotime($received_date)),
							    	'createdate'    => date('Y-m-d H:i:s')
							    );

							    $insert = $this->purchase_model->purchaseStkDetails_insert($ins_data);

							    // Collect Order Qty
						    	$where_3 = array(
						    		'po_id'      => $purchase_id,
					    			'po_auto_id' => $item_id,
						    		'product_id' => $product_id,
						    		'type_id'    => $type_id,
						    		'published'  => '1',
						    		'status'     => '1',
						    	);

						    	$column_3 = 'received_qty';

						    	$new_collect_data = $this->purchase_model->getPurchaseStkDetails($where_3, '', '', 'result', '', '', '', '', $column_3);

						    	$new_received_cou = 0;
						    	if(!empty($new_collect_data))
						    	{
						    		foreach ($new_collect_data as $key => $value) {
							    		$received_val  = !empty($value->received_qty)?$value->received_qty:'0';
							    		$new_received_cou += $received_val; 
							    	}
						    	}

						    	// Product Process Update
						    	if($product_qty == $new_received_cou)
						    	{
				            		$pur_data = array('product_process' => '2');
				            		$pur_whr  = array('id' => $item_id);
				            		$pur_upt  = $this->purchase_model->purchaseDetails_update($pur_data, $pur_whr);
						    	}

						    	$whr_one = array(
				            		'po_id'           => $purchase_id,
				            		'product_process' => '2',
				            		'delete_status'   => '1',
				            		'status'          => '1',
				            		'published'       => '1',
				            	);

				            	$value_one = $this->purchase_model->getPurchaseDetails($whr_one,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

					            $count_one = !empty($value_one[0]->autoid)?$value_one[0]->autoid:'0';

					            $whr_two = array(
				            		'po_id'           => $purchase_id,
				            		'status'          => '1',
				            		'delete_status'   => '1',
				            		'published'       => '1',
				            	);

				            	$value_two = $this->purchase_model->getPurchaseDetails($whr_two,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

					            $count_two = !empty($value_two[0]->autoid)?$value_two[0]->autoid:'0';

						    	if($count_one == $count_two)
						    	{
						    		$pur_data = array('bill_process' => '2');
				            		$pur_whr  = array('id' => $purchase_id);
				            		$pur_upt  = $this->purchase_model->purchase_update($pur_data, $pur_whr);
						    	}
					    	}
			            }
			    	}

			    	// Invoice Details
			    	$net_total = 0;
			    	if(!empty($stock_detail))
			    	{
			    		foreach ($stock_detail as $key => $qty_val) {
			    			$item_id      = !empty($qty_val->item_id)?$qty_val->item_id:'';
			    			$received_qty = !empty($qty_val->received_qty)?$qty_val->received_qty:'0';

			    			// Product Price Details
			    			$whr_3 = array('id' => $item_id);
			    			$col_3 = 'product_price';
			    			$val_3 = $this->purchase_model->getPurchaseDetails($whr_3, '', '', 'result', '', '', '', '', $col_3);

			    			$pdt_price   = !empty($val_3[0]->product_price)?$val_3[0]->product_price:'0';
			    			$price_total = $received_qty * $pdt_price;
							$net_total  += $price_total;
			    		}
			    	}

			    	// Vendor Outstading Value
			    	$where_4 = array(
			    		'id'        => $vendor_id,
			    		'published' => '1',
			    		'status'    => '1',
			    	);

			    	$column_4 = 'cur_balance, purchase_type';

			    	$vendor_data = $this->vendors_model->getVendors($where_4, '', '', 'result', '', '', '', '', $column_4);

			    	$cur_bal  = !empty($vendor_data[0]->cur_balance)?$vendor_data[0]->cur_balance:'0';
			    	$pur_type = !empty($vendor_data[0]->purchase_type)?$vendor_data[0]->purchase_type:'0';
			    	$cur_val  = $cur_bal + round($net_total);

			    	if($pur_type == 2)
			    	{
			    		$credit_data = array(
				    		'pre_balance' => strval($cur_bal),
				    		'cur_balance' => strval($cur_val),
				    		'updatedate'  => date('Y-m-d H:i:s'),
				    	);

				    	$vendor_whr = array('id' => $vendor_id);
						$upt_vendor = $this->vendors_model->vendors_update($credit_data, $vendor_whr);

						// Payment Details Insert
				    	$pay_data = array(
				    		'vendor_id'  => $vendor_id,
				    		'bill_code'  => 'INV',
				    		'bill_id'	 => $purchase_id,
				    		'bill_no'    => $bill_no,
				    		'pre_bal'    => strval($cur_bal),
				    		'cur_bal'    => strval($cur_val),
				    		'amount'     => round($net_total),
				    		'pay_type'   => 2,
				    		'amt_type'   => 4,
				    		'value_type' => 1,
				    		'date'       => $date,
				    		'time'       => $time,
				    		'createdate' => $c_date,
				    	);

				    	$payment_insert = $this->payment_model->vendorPayment_insert($pay_data);

				    	$ins_data = array(
				    		'vendor_id'  => $vendor_id,
				    		'bill_code'  => 'INV',
				    		'bill_id'	 => $purchase_id,
				    		'bill_no'    => $bill_no,
				    		'pre_bal'    => strval($cur_bal),
				    		'cur_bal'    => strval($cur_val),
				    		'amount'     => round($net_total),
				    		'bal_amt'    => round($net_total),
				    		'pay_type'   => 2,
				    		'date'       => $date,
				    		'time'       => $time,
				    		'createdate' => $c_date,
				    	);

				    	$paymentDet_insert = $this->payment_model->vendorPaymentDetails_insert($ins_data);
			    	}

			    	// Upload invoice copy
			    	if(!empty($_FILES))
				    {
				    	$doc_data = array(
				    		'po_id'          => $purchase_id,
				    		'inv_no'         => $bill_no,
				    		'entry_date'     => $received_date,
				    		'financial_year' => $active_financial,
				    		'createdate'     => date('Y-m-d H:i:s'),
				    	);

				    	$fileCount = count($_FILES['image']['name']);

			    		for ($i=0; $i < $fileCount; $i++) 
			    		{
			    			$exnimall  = array('jpg','jpeg','png','gif','doc','pdf');
							$exnimtemp = explode('.', $_FILES['image']['name'][$i]);
							$extension = end($exnimtemp); 

							if (($_FILES['image']['size'][$i] < 1000000) && in_array($extension, $exnimall)) 
							{
								if($_FILES['image']['error'][$i] > 0)
								{
									$response['status']  = 0;
							        $response['message'] = $_FILES['image']['error'][$i];
							        $response['data']    = [];
							        echo json_encode($response);
							        return;
								}
								else{
									$image  = generateRandomString(13).'.'.$extension;
									$folder = "upload/documents/";

									post_img($image, $_FILES['image']['tmp_name'][$i], $folder);

									$doc_data['c_file'] = $image;
								}
							}
							else
							{
								$response['status']  = 0;
						        $response['message'] = "File should be upload Less then 1Mb"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return;
							}
			    		}

						$doc_ins = $this->purchase_model->purchaseDocument_insert($doc_data);
				    }

			    	if($stock_detail)
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

		// Order Process
		// ***************************************************
		public function order_process($param1="",$param2="",$param3="")
		{
			// Financial Year Details
			$option['order_by']   = 'id';
			$option['disp_order'] = 'DESC';

			$where = array(
				'status'    => '1', 
				'published' => '1',
			);

			$data_list = $this->commom_model->getfinancial($where, '1', '0', 'result', '', '', $option);

			$financial_id = !empty($data_list[0]->id)?$data_list[0]->id:'';
			
			$auto_id  = $this->input->post('auto_id');
			$price    = $this->input->post('price');
			$quantity = $this->input->post('quantity');
			$progress = $this->input->post('progress');
			$reason   = $this->input->post('reason');
			$method   = $this->input->post('method');
			$rand_val = generateRandomString(32);
			$date     = date('Y-m-d');
			$time     = date('H:i:s');
			$c_date   = date('Y-m-d H:i:s');

			if($method == '_updateOrderDetails')
			{
				$error = FALSE;
			    $errors = array();
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

					$update=$this->purchase_model->purchaseDetails_update($order_data, $update_id);
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

			else if($method == '_updateOrderProgress')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('auto_id', 'progress');
				if($progress == 8 || $progress == 9)
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

			    	$bill_det   = $this->purchase_model->getPurchase($where_1);

			    	$invoice_no = !empty($bill_det[0]->invoice_no)?$bill_det[0]->invoice_no:'';
			    	$vendor_id  = !empty($bill_det[0]->vendor_id)?$bill_det[0]->vendor_id:'';
			    	$ord_status = !empty($bill_det[0]->order_status)?$bill_det[0]->order_status:'';
			    	$inv_value  = !empty($bill_det[0]->invoice_id)?$bill_det[0]->invoice_id:'';

			    	if($ord_status != $progress)
			    	{
			    		if($progress == '1')
				    	{
				    		$update_data = array(
				    			'order_status' => $progress,
				    			'_ordered'     => date('Y-m-d H:i:s'),
				    		);

				    		$order_data = array(
				    			'order_status' => $progress,
				    		);
				    	}

				    	else if($progress == '2')
				    	{
				    		$update_data = array(
				    			'order_status' => $progress,
				    			'_processing'  => date('Y-m-d H:i:s'),
				    		);

				    		$order_data = array(
				    			'order_status' => $progress,
				    		);
				    	}

				    	else if($progress == '3')
				    	{
				    		$update_data = array(
				    			'order_status' => $progress,
				    			'_packing'     => date('Y-m-d H:i:s'),
				    		);

				    		$order_data = array(
				    			'order_status' => $progress,
				    		);
				    	}

				    	else if($progress == '4')
				    	{
				    		// Purchase Details
				    		$whr_one = array(
				    			'po_id'         => $auto_id,
				    			'delete_status' => '1',
					    		'published'     => '1',
					    		'status'        => '1',
				    		);

				    		$value_one = $this->purchase_model->getPurchaseDetails($whr_one,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				            $count_one = !empty($value_one[0]->autoid)?$value_one[0]->autoid:'0';

				    		$whr_two = array(
				    			'po_id'         => $auto_id,
				    			'delete_status' => '1',
				    			'pack_status'   => '2',
					    		'published'     => '1',
					    		'status'        => '1',
				    		);

				    		$value_two = $this->purchase_model->getPurchaseDetails($whr_two,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				            $count_two = !empty($value_two[0]->autoid)?$value_two[0]->autoid:'0';

				            if($count_one == $count_two)
				            {
				            	// Create Vendor Invoice
								$where_1 = array(
					    			'id'        => $auto_id,
						    		'published' => '1',
						    		'status'    => '1',
					    		);	

					    		$column_1 = 'id, vendor_id, financial_year';

					    		$value_1  = $this->purchase_model->getPurchase($where_1, '', '', 'result', '', '', '', '', $column_1);		    		

					    		$order_val = $value_1[0];
					    		$order_id  = !empty($order_val->id)?$order_val->id:'';
							    $vendor_id = !empty($order_val->vendor_id)?$order_val->vendor_id:'';
							    $fin_year  = !empty($order_val->financial_year)?$order_val->financial_year:'';

					    		// Vendor Details
					    		$where_2 = array(
					    			'id' => $vendor_id,
					    		);

					    		$column_2 = 'bill_no, due_days';

					    		$value_2  = $this->vendors_model->getVendors($where_2, '', '', 'result', '', '', '', '', $column_2);

					    		$vndr_val = $value_2[0];
					    		$bill_no  = !empty($vndr_val->bill_no)?$vndr_val->bill_no:'INV';
					    		$due_days = !empty($vndr_val->due_days)?$vndr_val->due_days:'0';

					    		// Invoice Details
					    		$inv_whr = array(
					    			'vendor_id'      => $vendor_id,
					    			'financial_year' => $fin_year,
					    			'published'      => '1',
					    			'status'         => '1',
					    		);

					    		$bill_val  = $this->invoice_model->getVendorInvoice($inv_whr,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

					    		$count_val  = leadingZeros($bill_val[0]->autoid, 5);
				            	$invoice_no = $bill_no.$count_val;

					    		$vendorInvoice_data = array(
					    			'order_id'       => $order_id,
					    			'invoice_no'     => $invoice_no,
					    			'vendor_id'      => $vendor_id,
					    			'due_days'       => $due_days,
					    			'financial_year' => $fin_year,
					    			'random_value'   => $rand_val,
					    			'date'           => $date,
					    			'time'           => $time,
					    			'createdate'     => $c_date,
					    		);

					    		$insert = $this->invoice_model->vendorInvoice_insert($vendorInvoice_data);

					    		$where_3 = array(
					    			'po_id'            => $auto_id,
					    			'delete_status'    => '1',
						    		'published'        => '1',
						    		'status'           => '1',
					    		);

					    		$column_3 = 'id, po_id, vendor_id, product_id, type_id, product_price, product_qty, product_unit, product_type';

					    		$value_3  = $this->purchase_model->getPurchaseDetails($where_3, '', '', 'result', '', '', '', '', $column_3);

					    		foreach ($value_3 as $key => $value) {

								    $po_id         = !empty($value->po_id)?$value->po_id:'';
								    $vendor_id     = !empty($value->vendor_id)?$value->vendor_id:'';
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

								    $vendorInvoice_details = array(
								    	'invoice_id' => $insert,
								    	'invoice_no' => $invoice_no,
								    	'product_id' => $product_id,
								    	'type_id'    => $type_id,
								    	'vendor_id'  => $vendor_id,
								    	'hsn_code'   => $hsn_code,
								    	'gst_val'    => $gst_val,
								    	'unit_val'   => $product_unit,
								    	'price'      => $product_price,
								    	'order_qty'  => $product_qty,
								    	'createdate' => $c_date,
								    );

								    $insert_det = $this->invoice_model->vendorInvoiceDetails_insert($vendorInvoice_details);
					    		}

					    		$update_data = array(
					    			'invoice_id'   => $insert,
					    			'invoice_no'   => $invoice_no,
					    			'inv_value'    => $rand_val,
					    			'order_status' => $progress,
					    			'_delivery'    => date('Y-m-d H:i:s'),
					    		);

					    		$order_data = array(
					    			'order_status' => $progress,
					    		);
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

				    	else if($progress == '5')
				    	{
				    		// Purchase Details
				    		$whr_one = array(
				    			'po_id'         => $auto_id,
				    			'delete_status' => '1',
					    		'published'     => '1',
					    		'status'        => '1',
				    		);

				    		$value_one = $this->purchase_model->getPurchaseDetails($whr_one,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				            $count_one = !empty($value_one[0]->autoid)?$value_one[0]->autoid:'0';

				    		$whr_two = array(
				    			'po_id'           => $auto_id,
				    			'delete_status'   => '1',
				    			'product_process' => '2',
					    		'published'       => '1',
					    		'status'          => '1',
				    		);

				    		$value_two = $this->purchase_model->getPurchaseDetails($whr_two,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				            $count_two = !empty($value_two[0]->autoid)?$value_two[0]->autoid:'0';

				            if($count_one == $count_two)
				            {
				            	$column_2 = 'id, invoice_id, invoice_no';

				            	$where_2 = array(
					    			'id'            => $auto_id,
					    			'bill_process'  => '2',
					    			'published'     => '1',
						    		'status'        => '1',
					    		);

					    		$purchase_data = $this->purchase_model->getPurchase($where_2, '', '', 'result', '', '', '', '', $column_2);

								if($purchase_data)
								{
									$inv_id = !empty($purchase_data[0]->invoice_id)?$purchase_data[0]->invoice_id:'';
									$inv_no = !empty($purchase_data[0]->invoice_no)?$purchase_data[0]->invoice_no:'';

									// Order Update
									$where_3 = array(
							    		'po_id'         => $auto_id,
							    		'delete_status' => '1',
							    		'published'     => '1',
							    		'status'        => '1',
							    	);

							    	$column_3 = 'product_price, product_qty';

									$collect_data = $this->purchase_model->getPurchaseDetails($where_3, '', '', 'result', '', '', '', '', $column_3);

							    	$net_total = 0;
							    	if(!empty($collect_data))
							    	{
							    		foreach ($collect_data as $key => $qty_val) {
							    			
							    			$product_price = !empty($qty_val->product_price)?$qty_val->product_price:'0';
											$product_qty   = !empty($qty_val->product_qty)?$qty_val->product_qty:'0';
											$price_total   = $product_qty * $product_price;
											$net_total    += $price_total;

							    		}
							    	}

							    	// Vendor Outstading Value
							    	$where_4 = array(
							    		'id'        => $vendor_id,
							    		'published' => '1',
							    		'status'    => '1',
							    	);

							    	$column_4 = 'cur_balance, purchase_type';

							    	$vendor_data = $this->vendors_model->getVendors($where_4, '', '', 'result', '', '', '', '', $column_4);

							    	$cur_bal  = !empty($vendor_data[0]->cur_balance)?$vendor_data[0]->cur_balance:'0';
							    	$pur_type = !empty($vendor_data[0]->purchase_type)?$vendor_data[0]->purchase_type:'0';

							    	$cur_val = $cur_bal + round($net_total);

							    	if($pur_type == 1)
							    	{	
							    		$credit_data = array(
								    		'pre_balance' => strval($cur_bal),
								    		'cur_balance' => strval($cur_val),
								    		'updatedate'  => date('Y-m-d H:i:s'),
								    	);

										$vendor_whr = array('id' => $vendor_id);
										$upt_vendor = $this->vendors_model->vendors_update($credit_data, $vendor_whr);

										// Payment Details Insert
								    	$pay_data = array(
								    		'vendor_id'    => $vendor_id,
								    		'bill_code'    => 'INV',
								    		'bill_id'	   => $auto_id,
								    		'bill_no'      => $inv_no,
								    		'pre_bal'      => strval($cur_bal),
								    		'cur_bal'      => strval($cur_val),
								    		'amount'       => round($net_total),
								    		'pay_type'     => 2,
								    		'amt_type'     => 5,
								    		'value_type'   => 1,
								    		'financial_id' => $financial_id,
								    		'date'         => $date,
								    		'time'         => $time,
								    		'createdate'   => $c_date,
								    	);

								    	$payment_insert = $this->payment_model->vendorPayment_insert($pay_data);

								    	$ins_data = array(
								    		'vendor_id'    => $vendor_id,
								    		'bill_code'    => 'INV',
								    		'bill_id'	   => $auto_id,
								    		'bill_no'      => $inv_no,
								    		'pre_bal'      => strval($cur_bal),
								    		'cur_bal'      => strval($cur_val),
								    		'amount'       => round($net_total),
								    		'bal_amt'      => round($net_total),
								    		'pay_type'     => 2,
								    		'financial_id' => $financial_id,
								    		'date'         => $date,
								    		'time'         => $time,
								    		'createdate'   => $c_date,
								    	);

								    	$paymentDet_insert = $this->payment_model->vendorPaymentDetails_insert($ins_data);
							    	}

									$update_data  = array(
										'order_status' => $progress,
										'_complete'    => date('Y-m-d H:i:s'),
									);

									$order_data = array(
										'order_status' => $progress,
									);
								}
								else
								{
									$response['status']  = 0;
							        $response['message'] = "Please fill balance product"; 
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

				    	else if($progress == '9')
				    	{
				    		// Order Details
							$whr_1 = array(
								'po_id'     => $auto_id,
								'published' => '1',
								'status'    => '1',
							);				    		

							$col_1 = 'type_id, product_qty';

							$res_1 = $this->purchase_model->getPurchaseDetails($whr_1, '', '', 'result', '', '', '', '', $col_1);

							if($res_1)
							{
								foreach ($res_1 as $key => $val) {
									$type_id = !empty($val->type_id)?$val->type_id:'';
            						$pdt_qty = !empty($val->product_qty)?$val->product_qty:'0';

            						// Product Type Details
            						$whr_2 = array(
										'id' => $type_id,
									);	

									$col_2 = 'product_type, product_unit, type_stock, stock_detail';

									$res_2 = $this->commom_model->getProductType($whr_2, '', '', 'result', '', '', '', '', $col_2);

									$pdt_type = !empty($res_2[0]->product_type)?$res_2[0]->product_type:'';
									$pdt_unit = !empty($res_2[0]->product_unit)?$res_2[0]->product_unit:'';
						            $type_stk = !empty($res_2[0]->type_stock)?$res_2[0]->type_stock:'0';
						            $stk_det  = !empty($res_2[0]->stock_detail)?$res_2[0]->stock_detail:'0';

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

						    		// Update Product Stock
					    			$new_type_stock   = $type_stk + $received_stock;
						    		$new_stock_detail = $stk_det + $product_stock;

						    		$upt_stk  = array(
						    			'type_stock'   => $new_type_stock,
						    			'stock_detail' => $new_stock_detail,
						    		);

						    		$upt_type = array('id' => $type_id);
									$update   = $this->commom_model->productType_update($upt_stk, $upt_type);
								}

								// Invoice Cancel
								$inv_val = array(
					    			'cancel_status' => '2',
					    		);

					    		$inv_whr = array('id' => $inv_value);
					    		$inv_upt  = $this->invoice_model->vendorInvoice_update($inv_val, $inv_whr);

					    		$invDet_whr = array('invoice_id' => $inv_value);
					    		$invDet_upt  = $this->invoice_model->vendorInvoiceDetails_update($inv_val, $invDet_whr);
							}

				    		$update_data = array(
				    			'order_status' => $progress,
				    			'reason'       => $reason,
				    			'_canceled'    => date('Y-m-d H:i:s'),
				    		);

				    		$order_data = array(
				    			'order_status' => $progress,
				    		);
				    	}

				    	else
				    	{
				    		$update_data = array(
				    			'order_status' => $progress,
				    			'reason'       => $reason,
				    			'_canceled'    => date('Y-m-d H:i:s'),
				    		);

				    		$order_data = array(
				    			'order_status' => $progress,
				    		);
				    	}

				    	$whr_one = array('id' => $auto_id);
					    $upt_one  = $this->purchase_model->purchase_update($update_data, $whr_one);

					    $whr_two = array('po_id' => $auto_id);
					    $upt_two = $this->purchase_model->purchaseDetails_update($order_data, $whr_two);

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

			else if($method == '_DeleteOrderDetails')
			{
				$auto_id = $this->input->post('auto_id');

		    	if(!empty($auto_id))
		    	{
		    		// Order details
		    		$where_1 = array(
		    			'id'        => $auto_id,
		    			'published' => '1',
		    			'status'    => '1',
		    		);

		    		$purchase_data = $this->purchase_model->getPurchaseDetails($where_1);

		    		$data=array(
				    	'published' => '0',
				    );

		    		$where  = array('id' => $auto_id);

				    $update = $this->purchase_model->purchaseDetails_delete($data, $where);

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
			    	$pur_whr = array(
			    		'id'          => $auto_id,
			    		'pack_status' => '1',
			    		'published'   => '1',
			    		'status'      => '1',
			    	);

			    	$pur_col = 'type_id, product_qty';
			    	$pur_det = $this->purchase_model->getPurchaseDetails($pur_whr, '', '', 'result', '', '', '', '', $pur_col);

			    	if($pur_det)
			    	{
			    		$type_id = !empty($pur_det[0]->type_id)?$pur_det[0]->type_id:'0';
            			$pdt_qty = !empty($pur_det[0]->product_qty)?$pur_det[0]->product_qty:'0';

            			// Product type details
            			$pdt_whr = array(
				    		'id'        => $type_id,
				    		'published' => '1',
				    		'status'    => '1',
				    	);

				    	$pdt_col = 'id, product_type, product_unit, product_stock, view_stock, type_stock, stock_detail';
				    	$pdt_det = $this->commom_model->getProductType($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);

				    	if($pdt_det)
				    	{	
				    		$pdt_res = $pdt_det[0];
				            $pdt_type = !empty($pdt_res->product_type)?$pdt_res->product_type:'';
				            $pdt_unit = !empty($pdt_res->product_unit)?$pdt_res->product_unit:'';
				            $pdt_stk  = !empty($pdt_res->product_stock)?$pdt_res->product_stock:'';
				            $view_stk = !empty($pdt_res->view_stock)?$pdt_res->view_stock:'';
				            $type_stk = !empty($pdt_res->type_stock)?$pdt_res->type_stock:'';
				            $stk_det  = !empty($pdt_res->stock_detail)?$pdt_res->stock_detail:'';

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

				    		if($stk_det >= $product_stock)
				    		{
				    			// Update Product Stock
				    			$new_type_stock   = $type_stk - $received_stock;
					    		$new_stock_detail = $stk_det - $product_stock;

					    		$upt_stk  = array(
					    			'type_stock'    => $new_type_stock,
					    			'stock_detail'  => $new_stock_detail,
					    		);

					    		$upt_type = array('id' => $type_id);
								$update   = $this->commom_model->productType_update($upt_stk, $upt_type);

				    			$upt_data = array(
				    				'pack_status' => '2',
				    				'updatedate'  => $c_date,
				    			);

				    			$whr_one = array('id' => $auto_id);
							    $upt_one = $this->purchase_model->purchaseDetails_update($upt_data, $whr_one);

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
						        $response['message'] = "Invalide Stock"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return;
				    		}
				    	}
				    	else
				    	{
				    		$response['status']  = 0;
					        $response['message'] = "Invalide Product"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
				    	}
			    	}
			    	else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Invalide Product"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			    }
			}

			else if($method == '_changePackProcess')
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
			    		'po_id'           => $auto_id,
			    		'pack_status'     => '1',
			    		'published'       => '1',
			    	);

			    	$col_1 = 'id, type_id, product_qty, receive_qty, product_unit';
			    	$res_1 = $this->purchase_model->getPurchaseDetails($whr_1, '', '', 'result', '', '', '', '', $col_1);

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

						    $col_2  = 'id, product_type, type_stock, stock_detail';

						    $res_2  = $this->commom_model->getProductType($whr_2, '', '', 'result', '', '', '', '', $col_2);

						    $data_2 = $res_2[0];

				            $pdt_type      = !empty($data_2->product_type)?$data_2->product_type:'';
				            $type_stock    = !empty($data_2->type_stock)?$data_2->type_stock:'';
				            $stock_detail  = !empty($data_2->stock_detail)?$data_2->stock_detail:'';

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

				    		if($stock_detail >= $product_stock)
				    		{
				    			// Update Product Stock
				    			$new_type_stock   = $type_stock - $received_stock;
					    		$new_stock_detail = $stock_detail - $product_stock;

					    		$upt_stk  = array(
					    			'type_stock'    => $new_type_stock,
					    			'stock_detail'  => $new_stock_detail,
					    		);

					    		$upt_type = array('id'=>$type_id);
								$update   = $this->commom_model->productType_update($upt_stk, $upt_type);

							    $upt_data = array(
				    				'pack_status' => '2',
				    				'updatedate'  => $c_date,
				    			);

				    			$whr_one = array('id' => $po_auto_id);
							    $upt_one = $this->purchase_model->purchaseDetails_update($upt_data, $whr_one);
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
				    $upt_one = $this->purchase_model->purchaseDetails_update($upt_data, $whr_one);

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

		// Add Purchase
		// ***************************************************
		public function add_purchase_return($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addPurchaseReturn')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('vendor_id', 'order_date', 'purchase_value', 'active_financial');
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
			    	$vendor_id        = $this->input->post('vendor_id');
			    	$order_date       = $this->input->post('order_date');
			    	$active_financial = $this->input->post('active_financial');
			    	$purchase_value   = $this->input->post('purchase_value');

			    	$where_1 = array(
				    	'financial_year' => $active_financial,
				    );			   

					$column = 'id';

					$overalldatas  = $this->purchase_model->getPurchaseReturn($where_1, '', '', 'result', '', '', '', '', $column);

					if(!empty($overalldatas))
					{
						$overall_count = count($overalldatas);
					}
					else
					{
						$overall_count = 0;
					}

					$newbill_count = $overall_count + 1;
					$bill_number   = 'ORD'.leadingZeros($newbill_count, 5);

					// Vendor Details
					$where_2     = array('id' => $vendor_id);
					$column_2    = 'company_name';
			    	$data_val    = $this->vendors_model->getVendors($where_2, '', '', 'result', '', '', '', '', $column_2);
			    	$vendor_name = isset($data_val[0]->company_name)?$data_val[0]->company_name:'';

					$data = array(
						'order_no'       => $bill_number,
				    	'vendor_id'      => $vendor_id,
				    	'vendor_name'    => $vendor_name,
				    	'order_date'     => date('Y-m-d', strtotime($order_date)),
				    	'_ordered'       => date('Y-m-d H:i:s'),
				    	'financial_year' => $active_financial,
				    	'createdate'     => date('Y-m-d H:i:s')
				    );

				    $insert = $this->purchase_model->purchaseReturn_insert($data);

				    $product_value = json_decode($purchase_value);	

				    foreach ($product_value as $key => $val) {

				    	$product_id    = !empty($val->product_id)?$val->product_id:'';
						$type_id       = !empty($val->type_id)?$val->type_id:'';
						$product_price = !empty($val->product_price)?$val->product_price:'';
						$product_qty   = !empty($val->product_qty)?$val->product_qty:'';
						$product_unit  = !empty($val->product_unit)?$val->product_unit:'';

				    	$value = array(
					    	'order_id'       => $insert,
					    	'vendor_id'      => $vendor_id,
					    	'product_id'     => $product_id,
					    	'type_id'        => $type_id,
					    	'product_price'  => $product_price,
					    	'product_qty'    => $product_qty,
					    	'product_unit'   => $product_unit,
					    	'order_status'   => '1',
					    	'financial_year' => $active_financial,
					    	'createdate'     => date('Y-m-d H:i:s'),
					    );

					    $type_insert = $this->purchase_model->purchaseReturnDetails_insert($value);
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
		public function manage_purchase_return($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listPurchaseReturnPaginate')
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

					$search = $this->input->post('search');
		    		if($search !='')
		    		{
		    			$like['name']     = $search;
		    			// $like['hsn_code'] = $search;
		    			// $like['price']    = $search;

		    			$where = array(
		    				// 'financial_year' => $financ_year,
		    				'published'      => '1'
		    			);
		    		}
		    		else
		    		{
		    			$like = [];
		    			$where = array(
		    				// 'financial_year' => $financ_year,
		    				'published'      => '1'
		    			);
		    		}

		    		$column = 'id';
					$overalldatas = $this->purchase_model->getPurchaseReturn($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->purchase_model->getPurchaseReturn($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$purchase_list = [];
						foreach ($data_list as $key => $value) {
								
							$order_id       = isset($value->id)?$value->id:'';
						    $order_no       = isset($value->order_no)?$value->order_no:'';
						    $vendor_id      = isset($value->vendor_id)?$value->vendor_id:'';
						    $order_date     = isset($value->order_date)?$value->order_date:'';
						    $order_status   = isset($value->order_status)?$value->order_status:'';
						    $financial_year = isset($value->financial_year)?$value->financial_year:'';
						    $bill           = isset($value->bill)?$value->bill:'';
						    $published      = isset($value->published)?$value->published:'';
						    $status         = isset($value->status)?$value->status:'';
						    $createdate     = isset($value->createdate)?$value->createdate:'';

						    // Vendor Name
				            $where_1     = array('id'=>$vendor_id);
					    	$data_val    = $this->vendors_model->getVendors($where_1);
					    	$vendor_name = isset($data_val[0]->company_name)?$data_val[0]->company_name:'';
					    	$contact_no  = isset($data_val[0]->contact_no)?$data_val[0]->contact_no:'';

					    	$purchase_list[] = array(
		      					'order_id'       => $order_id,
							    'order_no'       => $order_no,
							    'vendor_id'      => $vendor_id,
							    'vendor_name'    => $vendor_name,
							    'contact_no'     => $contact_no,
							    'order_date'     => date('d-M-Y', strtotime($order_date)),
							    'order_status'   => $order_status,
							    'financial_year' => $financial_year,
							    'bill'           => $bill,
							    'published'      => $published,
							    'status'         => $status,
							    'createdate'     => $createdate,
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

		    		$order_col = 'id, order_no, return_no, order_status, reason, vendor_id, _ordered, _complete, , _canceled';
			    	$order_det = $this->purchase_model->getPurchaseReturn($order_whr, '', '', 'result', '', '', '', '', $order_col);

			    	if(!empty($order_det))
			    	{
			    		$order_res    = $order_det[0];
			    		$order_id     = !empty($order_res->id)?$order_res->id:'';
			            $order_no     = !empty($order_res->order_no)?$order_res->order_no:'';
			            $return_no    = !empty($order_res->return_no)?$order_res->return_no:'';
			            $order_status = !empty($order_res->order_status)?$order_res->order_status:'';
			            $reason       = !empty($order_res->reason)?$order_res->reason:'';
			            $vendor_id    = !empty($order_res->vendor_id)?$order_res->vendor_id:'';
			            $ordered      = !empty($order_res->_ordered)?$order_res->_ordered:'';
			            $complete     = !empty($order_res->_complete)?$order_res->_complete:'';
			            $canceled     = !empty($order_res->_canceled)?$order_res->_canceled:'';

			            // Vendor Name
			            $vendor_whr  = array('id'=>$vendor_id);
			            $vendor_col  = 'company_name, contact_no, address, gst_no, state_id';
				    	$data_val    = $this->vendors_model->getVendors($vendor_whr, '', '', 'result', '', '', '', '', $vendor_col);
				    	$vendor_name = isset($data_val[0]->company_name)?$data_val[0]->company_name:'';
				    	$contact_no  = isset($data_val[0]->contact_no)?$data_val[0]->contact_no:'';
				    	$address     = isset($data_val[0]->address)?$data_val[0]->address:'';
				    	$gst_no      = isset($data_val[0]->gst_no)?$data_val[0]->gst_no:'';
				    	$state_id    = isset($data_val[0]->state_id)?$data_val[0]->state_id:'';

				    	$vendor_details = array(
				    		'order_id'     => $order_id,
				    		'order_no'     => $order_no,
				    		'return_no'    => $return_no,
				    		'order_status' => $order_status,
				    		'reason'       => $reason,
				    		'ordered'      => $ordered,
				    		'complete'     => $complete,
				    		'canceled'     => $canceled,
				    		'vendor_name'  => $vendor_name,
				    		'contact_no'   => $contact_no,
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

			    		$data_3   = $this->purchase_model->getPurchaseReturnDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

			    		$order_details = [];
			    		foreach ($data_3 as $key => $value) {
			    			$auto_id       = !empty($value->id)?$value->id:'';
				            $product_id    = !empty($value->product_id)?$value->product_id:'';
				            $type_id       = !empty($value->type_id)?$value->type_id:'';
				            $product_price = !empty($value->product_price)?$value->product_price:'0';
				            $product_qty   = !empty($value->product_qty)?$value->product_qty:'0';
				            $product_unit  = !empty($value->product_unit)?$value->product_unit:'';

				            // Product Details
				            $pdt_whr = array('id' => $product_id);
				            $pdt_col = 'hsn_code, gst';
				            $pdt_det = $this->commom_model->getProduct($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);

				            $pdt_res = $pdt_det[0];
				            $pdt_hsn = !empty($pdt_res->hsn_code)?$pdt_res->hsn_code:'';
				            $pdt_gst = !empty($pdt_res->gst)?$pdt_res->gst:'0';

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
			    			'vendor_details' => $vendor_details,
			    			'order_details'  => $order_details,
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

			    	$bill_det   = $this->purchase_model->getPurchaseReturn($where_1);

			    	$vendor_id  = !empty($bill_det[0]->vendor_id)?$bill_det[0]->vendor_id:'';
			    	$ord_status = !empty($bill_det[0]->order_status)?$bill_det[0]->order_status:'';

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

					    	$col_1 = 'id, type_id, product_qty, product_unit';

				    		$val_1 = $this->purchase_model->getPurchaseReturnDetails($whr_1, '', '', 'result', '', '', '', '', $col_1);

				    		if($val_1)
				    		{
				    			foreach ($val_1 as $key => $res_1) {
				    				$type_id  = !empty($res_1->type_id)?$res_1->type_id:'0';
									$pdt_qty  = !empty($res_1->product_qty)?$res_1->product_qty:'0';
									$pdt_unit = !empty($res_1->product_unit)?$res_1->product_unit:'0';

									// Product Type Details
									$whr_2 = array('id' => $type_id);
									$col_2 = 'product_type, product_stock, view_stock';
									$val_2 = $this->commom_model->getProductType($whr_2, '', '', 'result', '', '', '', '', $col_2);

									$pdt_typ = !empty($val_2[0]->product_type)?$val_2[0]->product_type:'0';
									$pdt_stk = !empty($val_2[0]->product_stock)?$val_2[0]->product_stock:'0';
									$viw_stk = !empty($val_2[0]->view_stock)?$val_2[0]->view_stock:'0';

									// View Stock
							    	if($pdt_unit == 1 || $pdt_unit == 11)
						    		{
						    			$multiple_stk   = $pdt_qty * $pdt_typ; // 5 X 1 = 5 Kg
						    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
						    			$received_stock = $pdt_qty; // 5 Kg
						    		}
						    		else if($pdt_unit == 2 || $pdt_unit == 4)
						    		{
						    			$product_stock  = $pdt_qty * $pdt_typ; // 5 X 100 = 500 Gram
						    			$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
						    			$received_stock = number_format($received_value, 2);
						    		}
						    		else
						    		{
						    			$product_stock  = $pdt_qty * $pdt_typ; // 5 X 1 = 5 Nos
						    			$received_stock = $pdt_qty; // 5 Nos
						    		}

						    		// Stock 
						    		$new_stock    = $pdt_stk - $received_stock;
						    		$new_view_stk = $viw_stk - $product_stock;

						    		$upt_data = array(
						    			'product_stock' => $new_stock,
						    			'view_stock'    => $new_view_stk,
						    		);

						    		$update_id = array('id' => $type_id);
									$update    = $this->commom_model->productType_update($upt_data, $update_id);
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
					    $upt_one  = $this->purchase_model->purchaseReturn_update($update_data, $whr_one);

					    $update_val = array(
			    			'order_status' => $progress,
			    		);

					    $whr_two = array('order_id' => $auto_id);
					    $upt_two  = $this->purchase_model->purchaseReturnDetails_update($update_val, $whr_two);

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

		// Add Inventory
		// ***************************************************
		public function add_inventory($param1="",$param2="",$param3="")
		{
			$distributor_id   = $this->input->post('distributor_id');
			$inventory_value  = $this->input->post('inventory_value');
			$active_financial = $this->input->post('active_financial');
			$method           = $this->input->post('method');

			if($method == '_addInventory')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id', 'inventory_value', 'active_financial');
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
			    	$entry_value = json_decode($inventory_value);	

				    foreach ($entry_value as $key => $val)
				    {
				    	$dis_cat_id  = !empty($val->dis_cat_id)?$val->dis_cat_id:'0';
						$dis_pdt_id  = !empty($val->dis_pdt_id)?$val->dis_pdt_id:'0';
						$dis_pdt_qty = !empty($val->dis_pdt_qty)?$val->dis_pdt_qty:'0';

						// Distributor Product Details
						$whr_1 = array('id' => $dis_pdt_id);
						$col_1 = 'product_id, type_id, description, stock, view_stock';
						$res_1 = $this->assignproduct_model->getAssignProductDetails($whr_1, '', '', 'result', '', '', '', '', $col_1);

						$product_id  = !empty($res_1[0]->product_id)?$res_1[0]->product_id:'';
			            $type_id     = !empty($res_1[0]->type_id)?$res_1[0]->type_id:'';
			            $description = !empty($res_1[0]->description)?$res_1[0]->description:'';
			            $dis_stock   = !empty($res_1[0]->stock)?$res_1[0]->stock:'0';
			            $dis_viewStk = !empty($res_1[0]->view_stock)?$res_1[0]->view_stock:'0';

			            // Product Type Details
			            $whr_2 = array('id' => $type_id);
						$col_2 = 'product_type, type_stock, stock_detail, product_unit';
						$res_2 = $this->commom_model->getProductType($whr_2, '', '', 'result', '', '', '', '', $col_2);

						$pdt_type     = !empty($res_2[0]->product_type)?$res_2[0]->product_type:'0';
						$type_stock   = !empty($res_2[0]->type_stock)?$res_2[0]->type_stock:'0';
						$stock_detail = !empty($res_2[0]->stock_detail)?$res_2[0]->stock_detail:'0';
						$product_unit = !empty($res_2[0]->product_unit)?$res_2[0]->product_unit:'';

						// View Stock
						if($product_unit == 1 || $product_unit == 11)
			    		{
			    			$multiple_stk   = $dis_pdt_qty * $pdt_type; // 5 X 1 = 5 Kg
			    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
			    			$received_stock = $dis_pdt_qty; // 5 Kg
			    		}
						else if($product_unit == 2 || $product_unit == 4)
			    		{
			    			$product_stock  = $dis_pdt_qty * $pdt_type; // 5 X 100 = 500 Gram
			    			$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
			    			$received_stock = number_format($received_value, 2);
			    		}
			    		else
			    		{
			    			$product_stock  = $dis_pdt_qty * $pdt_type; // 5 X 1 = 5 Nos
			    			$received_stock = $dis_pdt_qty; // 5 Nos
			    		}

			    		if($type_stock >= $dis_pdt_qty)
			    		{
			    			// Stock Process
				    		$new_pdt_stock = $type_stock - $received_stock;
				    		$new_view_stk  = $stock_detail - $product_stock;

				    		$type_data = array(
				    			'type_stock'   => $new_pdt_stock,
				    			'stock_detail' => $new_view_stk,
				    		);

				    		$type_whr    = array('id' => $type_id);
				    		$update_type = $this->commom_model->productType_update($type_data, $type_whr);

				    		// Stock Process
				    		$new_pdt_stock = $dis_stock + $received_stock;
				    		$new_view_stk  = $dis_viewStk + $product_stock;

				    		$stk_data   = array('stock' => $new_pdt_stock, 'view_stock' => $new_view_stk);
				    		$stk_whr    = array('id' => $dis_pdt_id);
				    		$update_stk = $this->assignproduct_model->assignProductDetails_update($stk_data, $stk_whr);

				    		$ins_data = array(
				    			'distributor_id' => $distributor_id,
				    			'assign_id'      => $dis_pdt_id,
				    			'category_id'    => $dis_cat_id,
				    			'product_id'     => $product_id,
				    			'type_id'        => $type_id,
				    			'description'    => $description,
				    			'entry_qty'      => $dis_pdt_qty,
				    			'createdate'     => date('Y-m-d H:i:s'),
				    		);

				    		$insert = $this->purchase_model->inventory_insert($ins_data);
			    		}
				    }

				    if($entry_value)
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
<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class Production extends CI_Controller {

		public function __construct()
		{
			parent::__construct();

			$this->load->model('commom_model');
			$this->load->model('production_model');
			$this->load->model('assignproduct_model');
			$this->load->model('order_model');
			$this->load->model('distributors_model');
			$this->load->model('vendors_model');
			$this->load->model('purchase_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Add Production
		public function add_production($param1="",$param2="",$param3="")
		{
			$method          = $this->input->post('method');
			$start_date      = $this->input->post('start_date');
			$end_date        = $this->input->post('end_date');
			$production_val  = $this->input->post('production_value');
			$production_type = $this->input->post('production_type');
			$vendor_id       = $this->input->post('vendor_id');
			$distributor_id  = $this->input->post('distributor_id');
			$order_type      = $this->input->post('order_type');
    		$financial_year  = $this->input->post('financial_year');;

    		// Admin Production Insert
			if($method == '_addAdminProduction')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date', 'production_value', 'vendor_id', 'order_type', 'financial_year');

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
			    	$production_whr = array(
		    			'vendor_id' => $vendor_id,
					);

			    	// Bill Number
			    	$bill_val = $this->production_model->getAdminProduction($production_whr,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

		            $count_val = leadingZeros($bill_val[0]->autoid, 5);
		            $bill_num  = 'WO'.$count_val;

		            // Production Data
		            $production_data = array(
		            	'production_no'    => $bill_num,
		            	'vendor_id'        => !empty($vendor_id)?$vendor_id:'',
		            	'financial_year'   => $financial_year,
		            	'start_date'       => date('Y-m-d', strtotime($start_date)),
		            	'end_date'         => date('Y-m-d', strtotime($end_date)),
		            	'order_type'       => $order_type,
		            	'date'             => date('Y-m-d'),
						'time'             => date('H:i:s'),
						'createdate'       => date('Y-m-d H:i:s'),
		            );

		            $production_insert = $this->production_model->adminProduction_insert($production_data);

		            // Production Details
		            $i = 0;
		            $production_value = json_decode($production_val);
		            foreach ($production_value as $key => $value) {

		            	$product_id = $value->product_id;
					    $type_id    = $value->type_id;
					    $unit_val   = $value->unit_val;
					    $order_qty  = $value->order_qty;

					    $production_details = array(
					    	'production_id' => $production_insert,
					    	'production_no' => $bill_num,
					    	'product_id'    => $product_id,
					    	'type_id'       => $type_id,
					    	'unit_val'      => $unit_val,
					    	'order_qty'     => $order_qty,
					    	'receive_qty'   => '0',
					    	'createdate'    => date('Y-m-d H:i:s'),
					    );

					    $productionDetails = $this->production_model->adminProductionDetails_insert($production_details);

					    // Admin Product Details
				    	$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
			    		$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

			    		$where = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'vendor_id'     => $vendor_id,
							'product_id'    => $product_id,
				    		'type_id'       => $type_id,
							'item_status'   => '1',
							'status'        => '1', 
							'published'     => '1',
						);

						$column = 'id';

						$order_data = $this->purchase_model->getPurchaseDetails($where, '', '', 'result', '', '', '', '', $column);

						if($order_data)
						{
							foreach ($order_data as $key => $order_val) {

								$auto_id   = !empty($order_val->id)?$order_val->id:'';
								$upt_data  = array('item_status' => '2');
								$upt_where = array('id' => $auto_id);

								$order_update = $this->purchase_model->purchaseDetails_update($upt_data, $upt_where);
							}
						}

						$i++;
		            }

		            if($production_insert)
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

    		// Vendor Production Insert
			else if($method == '_addProduction')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date', 'production_value', 'production_type', 'order_type', 'financial_year');

				if($production_type == 1)
			    {
			    	array_push($required, 'vendor_id');
			    }
			    else if($production_type == 2)
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
			    	if($production_type == 1)
			    	{
			    		$production_whr = array(
			    			'vendor_id' => $vendor_id,
						);
			    	}
			    	else
			    	{
			    		$production_whr = array(
			    			'distributor_id' => $distributor_id,
						);
			    	}

			    	// Bill Number
			    	$bill_val = $this->production_model->getProduction($production_whr,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

		            $count_val = leadingZeros($bill_val[0]->autoid, 5);
		            $bill_num  = 'WO'.$count_val;

		            // Production Data
		            $production_data = array(
		            	'production_no'    => $bill_num,
		            	'production_value' => $production_type,
		            	'vendor_id'        => !empty($vendor_id)?$vendor_id:'',
		            	'distributor_id'   => !empty($distributor_id)?$distributor_id:'',
		            	'financial_year'   => $financial_year,
		            	'start_date'       => date('Y-m-d', strtotime($start_date)),
		            	'end_date'         => date('Y-m-d', strtotime($end_date)),
		            	'order_type'       => $order_type,
		            	'date'             => date('Y-m-d'),
						'time'             => date('H:i:s'),
						'createdate'       => date('Y-m-d H:i:s'),
		            );

		            $production_insert = $this->production_model->production_insert($production_data);

		            // Production Details
		            $i = 0;
		            $production_value = json_decode($production_val);
		            foreach ($production_value as $key => $value) {

		            	$product_id = $value->product_id;
					    $type_id    = $value->type_id;
					    $unit_val   = $value->unit_val;
					    $order_qty  = $value->order_qty;

					    $production_details = array(
					    	'production_id' => $production_insert,
					    	'production_no' => $bill_num,
					    	'product_id'    => $product_id,
					    	'type_id'       => $type_id,
					    	'unit_val'      => $unit_val,
					    	'order_qty'     => $order_qty,
					    	'receive_qty'   => '0',
					    	'createdate'    => date('Y-m-d H:i:s'),
					    );

					    $productionDetails = $this->production_model->productionDetails_insert($production_details);

					    // Order details update
					    if($production_type == 1)
					    {
					    	// Vendor Product Details
					    	$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    		$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    		$where = array(
								'createdate >=' => $start_value,
								'createdate <=' => $end_value,
								'vendor_id'     => $vendor_id,
								'product_id'    => $product_id,
					    		'type_id'       => $type_id,
								'order_status'  => '3',
								'item_status'   => '1',
								'status'        => '1', 
								'published'     => '1',
							);

							$column = 'id';

							$order_data = $this->order_model->getOrderDetails($where, '', '', 'result', '', '', '', '', $column);

							if($order_data)
							{
								foreach ($order_data as $key => $order_val) {

									$order_id  = !empty($order_val->id)?$order_val->id:'';

									$upt_data  = array('item_status' => '2');
									$upt_where = array('id' => $order_id);

									$order_update = $this->order_model->orderDetails_update($upt_data, $upt_where);
								}
							}
					    }

					    else
					    {
					    	// Distributor Product Details
					    	$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    		$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    		// Distributor product details
					    	$ass_col  = 'category_id, product_id';

							$ass_whr = array(
								'distributor_id' => $distributor_id,
								'published'      => '1',
							);

							$ass_data = $this->assignproduct_model->getAssignshopDetails($ass_whr, '', '', 'result', '', '', '', '', $ass_col);

							$product_value = '';
							if(!empty($ass_data))
							{
								foreach ($ass_data as $key => $ass_val) {
									$category_id = !empty($ass_val->category_id)?$ass_val->category_id:'';
						            $product_id  = !empty($ass_val->product_id)?$ass_val->product_id:'';

						            $product_value .= $product_id.',';
								}
							}

							$product_list  = substr_replace($product_value, '', -1);

					    	// Distributor zone details
							$dis_col = 'zone_id';

							$dis_whr = array(
								'id'        => $distributor_id,
								'published' => '1',
							);

							$dis_data = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);

							$zone_list = !empty($dis_data[0]->zone_id)?$dis_data[0]->zone_id:'';

							if(!empty($product_list))
							{
								$where = array(
									'tbl_order.zone_id'                 => $zone_list,
									'tbl_order_details.product_id'      => $product_list,
									'tbl_order_details.order_status'    => '3',
									'tbl_order_details.item_status'     => '1', 
									'tbl_order_details.createdate >='   => $start_value,
									'tbl_order_details.createdate <='   => $end_value,
									'tbl_order_details.status'          => '1', 
									'tbl_order_details.published'       => '1',
								);	

								$column = 'tbl_order_details.id';

								$order_data = $this->order_model->getDistributorOrderStatus($where, '', '', 'result', '', '', '', '', $column);

								if($order_data)
								{	
									foreach ($order_data as $key => $order_val) {

										$order_id  = !empty($order_val->id)?$order_val->id:'';

										$upt_data  = array('item_status' => '2');
										$upt_where = array('id' => $order_id);

										$order_update = $this->order_model->orderDetails_update($upt_data, $upt_where);
									}
								}
							}
					    }

						$i++;
		            }

		            if($production_insert)
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

		// List Production
		// ***************************************************
		public function list_production($param1="",$param2="",$param3="")
		{
			$method          = $this->input->post('method');
			$start_date      = $this->input->post('start_date');
			$end_date        = $this->input->post('end_date');
			$production_type = $this->input->post('production_type');
			$vendor_id       = $this->input->post('vendor_id');
			$distributor_id  = $this->input->post('distributor_id');
			$order_type      = $this->input->post('order_type');
    		$financial_year  = $this->input->post('financial_year');;
    		$auto_id         = $this->input->post('auto_id');;
    		$production_id   = $this->input->post('production_id');;
    		$product_id      = $this->input->post('product_id');;
			$limit           = $this->input->post('limit');
	    	$offset          = $this->input->post('offset');

	    	// Admin Production Details
	    	if($method == '_listAdminProductionProduct')
	    	{
	    		$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date', 'vendor_id');
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
			    	$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
			    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

			    	$where = array(
						'createdate >=' => $start_value,
						'createdate <=' => $end_value,
						'vendor_id'     => $vendor_id,
						'order_status'  => '3',
						'item_status'   => '1',
						'status'        => '1', 
						'published'     => '1',
					);

					$column = 'id, product_id, type_id, product_unit, product_qty ';

					$order_data = $this->purchase_model->getPurchaseDetails($where, '', '', 'result', '', '', '', '', $column);

					if($order_data)
					{
						foreach ($order_data as $key => $value) {
							$auto_id      = !empty($value->id)?$value->id:'';
				            $product_id   = !empty($value->product_id)?$value->product_id:'';
				            $type_id      = !empty($value->type_id)?$value->type_id:'';
				            $product_unit = !empty($value->product_unit)?$value->product_unit:'';
				            $product_qty  = !empty($value->product_qty)?$value->product_qty:'';

				            // Product Details
							$where_1      = array('id' => $product_id);	
							$product_det  = $this->commom_model->getProduct($where_1);
					    	$product_name = isset($product_det[0]->name)?$product_det[0]->name:'';		

							// Product Type Details
					    	$where_3     = array('id' => $type_id);
					    	$type_det    = $this->commom_model->getProductType($where_3);
					    	$description = isset($type_det[0]->description)?$type_det[0]->description:'';
					    	$type_name   = isset($type_det[0]->product_type)?$type_det[0]->product_type:'';

					    	// Unit Type Details
					    	$where_2   = array('id' => $product_unit);
					    	$unit_det  = $this->commom_model->getUnit($where_2);
					    	$unit_name = isset($unit_det[0]->name)?$unit_det[0]->name:'';

					    	$production_list[] = array(
					    		'auto_id'      => $auto_id,
					    		'product_id'   => $product_id,
					    		'product_name' => $product_name,
					    		'type_id'      => $type_id,
					    		'description'  => $description,
					    		'type_name'    => $type_name,
					    		'product_qty'  => $product_qty,
					    		'product_unit' => $product_unit,
					    		'unit_name'    => $unit_name,
					    	);
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $production_list;
				        echo json_encode($response);
				        return;
					}
					else
					{
						$response['status']  = 0;
				        $response['message'] = "No Data Found"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
					}
			    }
	    	}

	    	// Vendor Production Details
			else if($method == '_listVendorProductionProduct')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date', 'vendor_id');
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
			    	$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
			    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

			    	$where = array(
						'createdate >=' => $start_value,
						'createdate <=' => $end_value,
						'vendor_id'     => $vendor_id,
						'order_status'  => '3',
						'item_status'   => '1',
						'status'        => '1', 
						'published'     => '1',
					);

					$column = 'id, product_id, type_id, unit_val, SUM(order_qty) AS order_qty';

					$groupby = 'type_id';

					$order_data = $this->order_model->getOrderDetails($where, '', '', 'result', '', '', '', '', $column, $groupby);

					if($order_data)
					{
						$production_list = [];
						foreach ($order_data as $key => $value) {
								
							$auto_id    = !empty($value->id)?$value->id:'';
						    $product_id = !empty($value->product_id)?$value->product_id:'';
						    $type_id    = !empty($value->type_id)?$value->type_id:'';
						    $unit_val   = !empty($value->unit_val)?$value->unit_val:'';
						    $order_qty  = !empty($value->order_qty)?$value->order_qty:'';

						    // Product Details
							$where_1      = array('id' => $product_id);	
							$product_det  = $this->commom_model->getProduct($where_1);
					    	$product_name = isset($product_det[0]->name)?$product_det[0]->name:'';		

							// Product Type Details
					    	$where_3     = array('id' => $type_id);
					    	$type_det    = $this->commom_model->getProductType($where_3);
					    	$description = isset($type_det[0]->description)?$type_det[0]->description:'';
					    	$type_name   = isset($type_det[0]->product_type)?$type_det[0]->product_type:'';

					    	// Unit Type Details
					    	$where_2   = array('id' => $unit_val);
					    	$unit_det  = $this->commom_model->getUnit($where_2);
					    	$unit_name = isset($unit_det[0]->name)?$unit_det[0]->name:'';

					    	$production_list[] = array(
					    		'auto_id'      => $auto_id,
					    		'product_id'   => $product_id,
					    		'product_name' => $product_name,
					    		'type_id'      => $type_id,
					    		'description'  => $description,
					    		'type_name'    => $type_name,
					    		'order_qty'    => $order_qty,
					    		'unit_val'     => $unit_val,
					    		'unit_name'    => $unit_name,
					    	);
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $production_list;
				        echo json_encode($response);
				        return;
					}
					else
					{
						$response['status']  = 0;
				        $response['message'] = "No Data Found"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
					}
			    }
			}

			// Admin Production Pagination
			else if($method == '_listAdminProductionPaginate')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('vendor_id', 'financial_year');
				
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
		    			$like['name'] = $search;
		    		}
		    		else
		    		{
		    			$like = [];
		    		}

		    		$where = array(
	    				'vendor_id'      => $vendor_id,
	    				// 'financial_year' => $financial_year,
	    				'published'      => '1'
	    			);

		    		$column = 'id';
					$overalldatas = $this->production_model->getAdminProduction($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->production_model->getAdminProduction($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$production_list = [];
						foreach ($data_list as $key => $value) {

							$production_id = !empty($value->id)?$value->id:'';
				            $production_no = !empty($value->production_no)?$value->production_no:'';
				            $start_date    = !empty($value->start_date)?$value->start_date:'';
				            $end_date      = !empty($value->end_date)?$value->end_date:'';
				            $status        = !empty($value->status)?$value->status:'';
				            $createdate    = !empty($value->createdate)?$value->createdate:'';

				            $production_list[] = array(
				            	'production_id' => $production_id,
				            	'production_no' => $production_no,
				            	'start_date'    => date('d-m-Y', strtotime($start_date)),
				            	'end_date'      => date('d-m-Y', strtotime($end_date)),
				            	'status'        => $status,
				            	'createdate'    => date('d-m-Y H:i:s', strtotime($createdate)),
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
				        $response['data']         = $production_list;
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

			// Vendor Production Pagination
			else if($method == '_listProductionPaginate')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('production_type', 'financial_year');
				if($production_type == 1)
			    {
			    	array_push($required, 'vendor_id');
			    }
			    else if($production_type == 2)
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

		    			if($production_type == 1)
		    			{
		    				$where = array(
			    				'vendor_id'      => $vendor_id,
			    				// 'financial_year' => $financial_year,
			    				'published'      => '1'
			    			);
		    			}
		    			else
		    			{
		    				$where = array(
			    				'distributor_id' => $distributor_id,
			    				// 'financial_year' => $financial_year,
			    				'published'      => '1'
			    			);
		    			}
		    		}
		    		else
		    		{
		    			$like = [];
		    			
		    			if($production_type == 1)
		    			{
		    				$where = array(
			    				'vendor_id'      => $vendor_id,
			    				// 'financial_year' => $financial_year,
			    				'published'      => '1'
			    			);
		    			}
		    			else
		    			{
		    				$where = array(
			    				'distributor_id' => $distributor_id,
			    				// 'financial_year' => $financial_year,
			    				'published'      => '1'
			    			);
		    			}
		    		}

		    		$column = 'id';
					$overalldatas = $this->production_model->getProduction($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->production_model->getProduction($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$production_list = [];
						foreach ($data_list as $key => $value) {

							$production_id = !empty($value->id)?$value->id:'';
				            $production_no = !empty($value->production_no)?$value->production_no:'';
				            $start_date    = !empty($value->start_date)?$value->start_date:'';
				            $end_date      = !empty($value->end_date)?$value->end_date:'';
				            $status        = !empty($value->status)?$value->status:'';
				            $createdate    = !empty($value->createdate)?$value->createdate:'';

				            $production_list[] = array(
				            	'production_id' => $production_id,
				            	'production_no' => $production_no,
				            	'start_date'    => date('d-m-Y', strtotime($start_date)),
				            	'end_date'      => date('d-m-Y', strtotime($end_date)),
				            	'status'        => $status,
				            	'createdate'    => date('d-m-Y H:i:s', strtotime($createdate)),
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
				        $response['data']         = $production_list;
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

			// Admin Production Details
			else if($method == '_adminProductionDetails')
			{
				if(!empty($production_id))
				{
					$where_1 = array(
	    				'id'        => $production_id, 
	    				'published' => '1'
	    			);

	    			$billdata = $this->production_model->getAdminProduction($where_1);

		            $production_no = !empty($billdata[0]->production_no)?$billdata[0]->production_no:'';
		            $start_date    = !empty($billdata[0]->start_date)?$billdata[0]->start_date:'';
		            $end_date      = !empty($billdata[0]->end_date)?$billdata[0]->end_date:'';

		            $production_details = array(
		            	'production_no' => $production_no,
		            	'start_date'    => date('d-m-Y', strtotime($start_date)),
		            	'end_date'      => date('d-m-Y', strtotime($end_date)),
		            );

					$where_2 = array(
	    				'production_id' => $production_id, 
	    				'published'     => '1'
	    			);

	    			$overalldatas = $this->production_model->getAdminProductionDetails($where_2);

	    			if(!empty($overalldatas))
	    			{
	    				$production_list = [];

	    				foreach ($overalldatas as $key => $value) {
	    					$auto_id     = !empty($value->id)?$value->id:'';
						    $product_id  = !empty($value->product_id)?$value->product_id:'';
						    $type_id     = !empty($value->type_id)?$value->type_id:'';
						    $unit_val    = !empty($value->unit_val)?$value->unit_val:'';
						    $order_qty   = !empty($value->order_qty)?$value->order_qty:'';
						    $receive_qty = !empty($value->receive_qty)?$value->receive_qty:'';

						    // Product Details
							$where_1      = array('id' => $product_id);	
							$product_det  = $this->commom_model->getProduct($where_1);
					    	$product_name = isset($product_det[0]->name)?$product_det[0]->name:'';		

					    	// Unit Type Details
					    	$where_2   = array('id' => $unit_val);
					    	$unit_det  = $this->commom_model->getUnit($where_2);
					    	$unit_name = isset($unit_det[0]->name)?$unit_det[0]->name:'';

							// Product Type Details
					    	$where_3     = array('id' => $type_id);
					    	$type_det    = $this->commom_model->getProductType($where_3);
					    	$description = isset($type_det[0]->description)?$type_det[0]->description:'';
					    	$type_name   = isset($type_det[0]->product_type)?$type_det[0]->product_type:'';

					    	$production_list[] = array(
					    		'auto_id'       => $auto_id,
					    		'production_id' => $production_id,
					    		'product_id'    => $product_id,
					    		'product_name'  => $product_name,
					    		'type_id'       => $type_id,
					    		'description'   => $description,
					    		'unit_val'      => $unit_val,
					    		'unit_name'     => $unit_name,
					    		'order_qty'     => $order_qty,
					    		'receive_qty'   => $receive_qty,
					    	);
	    				}

	    				$production_data = array(
	    					'production_details' => $production_details,
	    					'production_list'    => $production_list
	    				);

	    				$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $production_data;
				        echo json_encode($response);
				        return;
	    			}
	    			else
	    			{
	    				$response['status']  = 0;
				        $response['message'] = "No Records Found"; 
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

			// Vendor Production Details
			else if($method == '_productionDetails')
			{
				if(!empty($production_id))
				{
					$where_1 = array(
	    				'id'        => $production_id, 
	    				'published' => '1'
	    			);

	    			$billdata = $this->production_model->getProduction($where_1);

		            $production_no = !empty($billdata[0]->production_no)?$billdata[0]->production_no:'';
		            $start_date    = !empty($billdata[0]->start_date)?$billdata[0]->start_date:'';
		            $end_date      = !empty($billdata[0]->end_date)?$billdata[0]->end_date:'';

		            $production_details = array(
		            	'production_no' => $production_no,
		            	'start_date'    => date('d-m-Y', strtotime($start_date)),
		            	'end_date'      => date('d-m-Y', strtotime($end_date)),
		            );

					$where_2 = array(
	    				'production_id' => $production_id, 
	    				'published'     => '1'
	    			);

	    			$overalldatas = $this->production_model->getProductionDetails($where_2);

	    			if(!empty($overalldatas))
	    			{
	    				$production_list = [];

	    				foreach ($overalldatas as $key => $value) {
	    					$auto_id     = !empty($value->id)?$value->id:'';
						    $product_id  = !empty($value->product_id)?$value->product_id:'';
						    $type_id     = !empty($value->type_id)?$value->type_id:'';
						    $unit_val    = !empty($value->unit_val)?$value->unit_val:'';
						    $order_qty   = !empty($value->order_qty)?$value->order_qty:'';
						    $receive_qty = !empty($value->receive_qty)?$value->receive_qty:'';

						    // Product Details
							$where_1      = array('id' => $product_id);	
							$product_det  = $this->commom_model->getProduct($where_1);
					    	$product_name = isset($product_det[0]->name)?$product_det[0]->name:'';		

					    	// Unit Type Details
					    	$where_2   = array('id' => $unit_val);
					    	$unit_det  = $this->commom_model->getUnit($where_2);
					    	$unit_name = isset($unit_det[0]->name)?$unit_det[0]->name:'';

							// Product Type Details
					    	$where_3     = array('id' => $type_id);
					    	$type_det    = $this->commom_model->getProductType($where_3);
					    	$description = isset($type_det[0]->description)?$type_det[0]->description:'';
					    	$type_name   = isset($type_det[0]->product_type)?$type_det[0]->product_type:'';

					    	$production_list[] = array(
					    		'auto_id'       => $auto_id,
					    		'production_id' => $production_id,
					    		'product_id'    => $product_id,
					    		'product_name'  => $product_name,
					    		'type_id'       => $type_id,
					    		'description'   => $description,
					    		'unit_val'      => $unit_val,
					    		'unit_name'     => $unit_name,
					    		'order_qty'     => $order_qty,
					    		'receive_qty'   => $receive_qty,
					    	);
	    				}

	    				$production_data = array(
	    					'production_details' => $production_details,
	    					'production_list'    => $production_list
	    				);

	    				$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $production_data;
				        echo json_encode($response);
				        return;
	    			}
	    			else
	    			{
	    				$response['status']  = 0;
				        $response['message'] = "No Records Found"; 
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

			// Admin Production Overall Details
			else if($method == '_detailAdminProduction')
			{
				if(!empty($production_id))
				{
					$where_1 = array(
	    				'id'        => $production_id, 
	    				'published' => '1'
	    			);

	    			$billdata = $this->production_model->getAdminProduction($where_1);

	    			if(!empty($billdata))
	    			{
	    				$production_no = !empty($billdata[0]->production_no)?$billdata[0]->production_no:'';
			            $start_date    = !empty($billdata[0]->start_date)?$billdata[0]->start_date:'';
			            $end_date      = !empty($billdata[0]->end_date)?$billdata[0]->end_date:'';

			            $production_details = array(
			            	'production_id' => $production_id,
			            	'production_no' => $production_no,
			            	'start_date'    => date('d-m-Y', strtotime($start_date)),
			            	'end_date'      => date('d-m-Y', strtotime($end_date)),
			            );

			            $response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $production_details;
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

			// Vendor Production Overall Details
			else if($method == '_detailProduction')
			{
				if(!empty($production_id))
				{
					$where_1 = array(
	    				'id'        => $production_id, 
	    				'published' => '1'
	    			);

	    			$billdata = $this->production_model->getProduction($where_1);

	    			if(!empty($billdata))
	    			{
	    				$production_no = !empty($billdata[0]->production_no)?$billdata[0]->production_no:'';
			            $start_date    = !empty($billdata[0]->start_date)?$billdata[0]->start_date:'';
			            $end_date      = !empty($billdata[0]->end_date)?$billdata[0]->end_date:'';

			            $production_details = array(
			            	'production_id' => $production_id,
			            	'production_no' => $production_no,
			            	'start_date'    => date('d-m-Y', strtotime($start_date)),
			            	'end_date'      => date('d-m-Y', strtotime($end_date)),
			            );

			            $response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $production_details;
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

			// Admin Production Product Details
			else if($method == '_detailAdminProductionProduct')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('auto_id', 'production_id');
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
			    		'id'            => $auto_id,
			    		'production_id' => $production_id,
			    		'published'     => '1',
			    		'status'        => '1',
			    	);

			    	$product_list = $this->production_model->getAdminProductionDetails($where_1);

			    	if(!empty($product_list))
			    	{
			    		$produc_no   = !empty($product_list[0]->production_no)?$product_list[0]->production_no:'';
			            $product_id  = !empty($product_list[0]->product_id)?$product_list[0]->product_id:'';
			            $type_id     = !empty($product_list[0]->type_id)?$product_list[0]->type_id:'';
			            $unit_val    = !empty($product_list[0]->unit_val)?$product_list[0]->unit_val:'';
			            $order_qty   = !empty($product_list[0]->order_qty)?$product_list[0]->order_qty:'';
			            $receive_qty = !empty($product_list[0]->receive_qty)?$product_list[0]->receive_qty:'';

			            // Product Details
			    		$where_3      = array('id' => $product_id);
				    	$product_val  = $this->commom_model->getProduct($where_3);
				    	$product_name = isset($product_val[0]->name)?$product_val[0]->name:'';

				    	// Product Type Stock Plus
				    	$where_4 = array('id' => $type_id, 'product_id' => $product_id);

				    	$productType_val = $this->commom_model->getProductType($where_4);
				    	$description     = !empty($productType_val[0]->description)?$productType_val[0]->description:'';
				    	

				    	$production_list = array(
				    		'auto_id'       => $auto_id,
				    		'production_id' => $production_id,
				    		'production_no' => $produc_no,
				    		'product_id'    => $product_id,
				    		'product_name'  => $product_name,
				    		'type_id'       => $type_id,
				    		'description'   => $description,
				    		'unit_val'      => $unit_val,
				    		'order_qty'     => $order_qty,
				    		'receive_qty'   => $receive_qty,
				    	);

				    	$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $production_list;
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

			// Vendor Production Product Details
			else if($method == '_detailProductionProduct')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('auto_id', 'production_id');
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
			    		'id'            => $auto_id,
			    		'production_id' => $production_id,
			    		'published'     => '1',
			    		'status'        => '1',
			    	);

			    	$product_list = $this->production_model->getProductionDetails($where_1);

			    	if(!empty($product_list))
			    	{
			    		$produc_no   = !empty($product_list[0]->production_no)?$product_list[0]->production_no:'';
			            $product_id  = !empty($product_list[0]->product_id)?$product_list[0]->product_id:'';
			            $type_id     = !empty($product_list[0]->type_id)?$product_list[0]->type_id:'';
			            $unit_val    = !empty($product_list[0]->unit_val)?$product_list[0]->unit_val:'';
			            $order_qty   = !empty($product_list[0]->order_qty)?$product_list[0]->order_qty:'';
			            $receive_qty = !empty($product_list[0]->receive_qty)?$product_list[0]->receive_qty:'';

			            // Product Details
			    		$where_3      = array('id' => $product_id);
				    	$product_val  = $this->commom_model->getProduct($where_3);
				    	$product_name = isset($product_val[0]->name)?$product_val[0]->name:'';

				    	// Product Type Stock Plus
				    	$where_4 = array('id' => $type_id, 'product_id' => $product_id);

				    	$productType_val = $this->commom_model->getProductType($where_4);
				    	$description     = !empty($productType_val[0]->description)?$productType_val[0]->description:'';
				    	

				    	$production_list = array(
				    		'auto_id'       => $auto_id,
				    		'production_id' => $production_id,
				    		'production_no' => $produc_no,
				    		'product_id'    => $product_id,
				    		'product_name'  => $product_name,
				    		'type_id'       => $type_id,
				    		'description'   => $description,
				    		'unit_val'      => $unit_val,
				    		'order_qty'     => $order_qty,
				    		'receive_qty'   => $receive_qty,
				    	);

				    	$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $production_list;
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

		// Manage Production Stock Details
		// ***************************************************
		public function manage_productionStkDetails($param1="",$param2="",$param3="")
		{
			$method        = $this->input->post('method');
			$pro_id        = $this->input->post('pro_id');
	    	$pro_auto_id   = $this->input->post('pro_auto_id');
	    	$product_id    = $this->input->post('product_id');
	    	$type_id       = $this->input->post('type_id');
	    	$received_qty  = $this->input->post('received_qty');
	    	$received_date = $this->input->post('received_date');
	    	$stock_id      = $this->input->post('stock_id');

	    	// Add Admin Stock
			if($method == '_addAdminWorkorderStockDetails')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('pro_id', 'pro_auto_id', 'product_id', 'type_id', 'received_qty', 'received_date');
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
			    	$where = array(
			    		'id'            => $pro_auto_id,
			    		'production_id' => $pro_id,
			    		'product_id'    => $product_id,
			    		'type_id'       => $type_id,
			    		'published'     => '1',
			    		'status'        => '1',
			    	);

			    	$order_data = $this->production_model->getAdminProductionDetails($where);

		            $unit_val    = !empty($order_data[0]->unit_val)?$order_data[0]->unit_val:'';
		            $order_qty   = !empty($order_data[0]->order_qty)?$order_data[0]->order_qty:'0';
		            $receive_qty = !empty($order_data[0]->receive_qty)?$order_data[0]->receive_qty:'0';

		            // Collect Order Qty
			    	$where_2 = array(
			    		'pro_id'      => $pro_id,
			    		'pro_auto_id' => $pro_auto_id,
			    		'product_id'  => $product_id,
			    		'type_id'     => $type_id,
			    		'published'   => '1',
			    		'status'      => '1',
			    	);

			    	$column_2 = 'received_qty';

			    	$collect_data = $this->production_model->getAdminProductionStkDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

			    	$received_cou = 0;
			    	if(!empty($collect_data))
			    	{
			    		foreach ($collect_data as $key => $value) {
				    		$received_val  = !empty($value->received_qty)?$value->received_qty:'';
				    		$received_cou += $received_val; 
				    	}
			    	}

			    	// Overall Collect Data
			    	$over_collect = $order_qty - $received_cou;

			    	if($over_collect >= $received_qty)
			    	{
			    		// Product Stock Minus
				    	$where_3 = array('id' => $product_id);

				    	$product_val = $this->commom_model->getProduct($where_3);

				    	$main_unit  = !empty($product_val[0]->unit)?$product_val[0]->unit:'';	
				    	$stock      = !empty($product_val[0]->vend_stock)?$product_val[0]->vend_stock:'0';	
				    	$view_stock = !empty($product_val[0]->stock_detail)?$product_val[0]->stock_detail:'0';	

				    	
				    	// Product Type Stock Plus
				    	$where_4 = array('id' => $type_id, 'product_id' => $product_id);

				    	$productType_val = $this->commom_model->getProductType($where_4);

				    	$product_type    = !empty($productType_val[0]->product_type)?$productType_val[0]->product_type:'0';	
				    	$typeStock       = !empty($productType_val[0]->type_stock)?$productType_val[0]->type_stock:'0';	
				    	$typeView_stock  = !empty($productType_val[0]->stock_detail)?$productType_val[0]->stock_detail:'0';		

				    	// View Stock
				    	if($unit_val == 1 || $unit_val == 11)
			    		{
			    			$multiple_stk   = $received_qty * $product_type; // 5 X 1 = 5 Kg
			    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
			    			$received_stock = $received_qty; // 5 Kg
			    		}
			    		else if($unit_val == 2 || $unit_val == 4)
			    		{
			    			$product_stock  = $received_qty * $product_type; // 5 X 100 = 500 Gram
			    			$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
			    			$received_stock = number_format($received_value, 2);
			    		}
			    		else
			    		{
			    			$product_stock  = $received_qty * $product_type; // 5 X 1 = 5 Nos
			    			$received_stock = $received_qty; // 5 Nos
			    		}

			    		if($view_stock >= $product_stock)
			    		{
			    			// Stock Process
				    		$new_stock    = $stock - $received_stock;
				    		$new_view_stk = $view_stock - $product_stock;

				    		$product_data = array(
				    			'vend_stock'   => $new_stock,
				    			'stock_detail' => $new_view_stk,
				    		);

				    		$product_whr = array('id' => $product_id);
							$update      = $this->commom_model->product_update($product_data, $product_whr);

					    	// Stock Process
				    		$new_type_stock    = $typeStock + $received_stock;
				    		$new_type_view_stk = $typeView_stock + $product_stock;

				    		$type_data = array(
				    			'type_stock'   => $new_type_stock,
				    			'stock_detail' => $new_type_view_stk,
				    		);

				    		$type_whr = array('id' => $type_id);
				    		$update   = $this->commom_model->productType_update($type_data, $type_whr);

				    		// Production Details
				    		$overColl_qty = $received_cou + $received_qty;

				    		$produc_data = array(
				    			'receive_qty' => strval($overColl_qty),
				    		);

				    		$produc_whr   = array('id' => $pro_auto_id);
				    		$update_prodc = $this->production_model->adminProductionDetails_update($produc_data, $produc_whr);

				    		// Production Details Insert
				    		$ins_data = array(
						    	'pro_id'        => $pro_id,
						    	'pro_auto_id'   => $pro_auto_id,
						    	'product_id'    => $product_id,
						    	'type_id'       => $type_id,
						    	'product_unit'  => $unit_val,
						    	'received_qty'  => $received_qty,
						    	'received_date' => date('Y-m-d', strtotime($received_date)),
						    	'createdate'    => date('Y-m-d H:i:s')
						    );

						    $insert = $this->production_model->adminProductionStkDetails_insert($ins_data);

						    // Production order qty details
						    $where_5 = array(
						    	'id'        => $pro_auto_id,
						    	'published' => '1',
						    	'status'    => '1',
						    );

						    $column_5 = 'order_qty';

				    		$order_data = $this->production_model->getAdminProductionDetails($where_5, '', '', 'result', '', '', '', '', $column_5);

				    		$product_qty = !empty($order_data[0]->order_qty)?$order_data[0]->order_qty:'';

						    // Production receive qty details
						    $where_6 = array(
						    	'pro_id'      => $pro_id,
						    	'pro_auto_id' => $pro_auto_id, 
						    	'product_id'  => $product_id,
						    	'type_id'     => $type_id,
						    	'published'   => '1',
						    	'status'      => '1',
						    );

						    $column_6 = 'received_qty';

						    $ovr_collect_data = $this->production_model->getAdminProductionStkDetails($where_6, '', '', 'result', '', '', '', '', $column_6);

						    $new_received_cou = 0;
					    	if(!empty($ovr_collect_data))
					    	{
					    		foreach ($ovr_collect_data as $key => $value) {
						    		$received_val  = !empty($value->received_qty)?$value->received_qty:'';
						    		$new_received_cou += $received_val; 
						    	}
					    	}

					    	if($product_qty == $new_received_cou)
					    	{
					    		$pro_data = array('item_process' => '2');
			            		$pro_whr  = array('id' => $pro_auto_id);
			            		$pro_upt  = $this->production_model->adminProductionDetails_update($pro_data, $pro_whr);	
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
			}

	    	// Add Vendor Stock
			if($method == '_addVendorWorkorderStockDetails')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('pro_id', 'pro_auto_id', 'product_id', 'type_id', 'received_qty', 'received_date');
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
			    	$where = array(
			    		'id'            => $pro_auto_id,
			    		'production_id' => $pro_id,
			    		'product_id'    => $product_id,
			    		'type_id'       => $type_id,
			    		'published'     => '1',
			    		'status'        => '1',
			    	);

			    	$order_data = $this->production_model->getProductionDetails($where);

		            $unit_val    = !empty($order_data[0]->unit_val)?$order_data[0]->unit_val:'';
		            $order_qty   = !empty($order_data[0]->order_qty)?$order_data[0]->order_qty:'0';
		            $receive_qty = !empty($order_data[0]->receive_qty)?$order_data[0]->receive_qty:'0';

		            // Collect Order Qty
			    	$where_2 = array(
			    		'pro_id'      => $pro_id,
			    		'pro_auto_id' => $pro_auto_id,
			    		'product_id'  => $product_id,
			    		'type_id'     => $type_id,
			    		'published'   => '1',
			    		'status'      => '1',
			    	);

			    	$column_2 = 'received_qty';

			    	$collect_data = $this->production_model->getproductionStkDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

			    	$received_cou = 0;
			    	if(!empty($collect_data))
			    	{
			    		foreach ($collect_data as $key => $value) {
				    		$received_val  = !empty($value->received_qty)?$value->received_qty:'';
				    		$received_cou += $received_val; 
				    	}
			    	}

			    	// Overall Collect Data
			    	$over_collect = $order_qty - $received_cou;

			    	if($over_collect >= $received_qty)
			    	{
			    		// Product Stock Minus
				    	$where_3 = array('id' => $product_id);

				    	$product_val = $this->commom_model->getProduct($where_3);

				    	$main_unit  = !empty($product_val[0]->unit)?$product_val[0]->unit:'';	
				    	$stock      = !empty($product_val[0]->vend_stock)?$product_val[0]->vend_stock:'0';	
				    	$view_stock = !empty($product_val[0]->stock_detail)?$product_val[0]->stock_detail:'0';	

				    	
				    	// Product Type Stock Plus
				    	$where_4 = array('id' => $type_id, 'product_id' => $product_id);

				    	$productType_val = $this->commom_model->getProductType($where_4);

				    	$product_type    = !empty($productType_val[0]->product_type)?$productType_val[0]->product_type:'0';	
				    	$typeStock       = !empty($productType_val[0]->type_stock)?$productType_val[0]->type_stock:'0';	
				    	$typeView_stock  = !empty($productType_val[0]->stock_detail)?$productType_val[0]->stock_detail:'0';		

				    	// View Stock
				    	if($unit_val == 1 || $unit_val == 11)
			    		{
			    			$multiple_stk   = $received_qty * $product_type; // 5 X 1 = 5 Kg
			    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
			    			$received_stock = $received_qty; // 5 Kg
			    		}
			    		else if($unit_val == 2 || $unit_val == 4)
			    		{
			    			$product_stock  = $received_qty * $product_type; // 5 X 100 = 500 Gram
			    			$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
			    			$received_stock = number_format($received_value, 2);
			    		}
			    		else
			    		{
			    			$product_stock  = $received_qty * $product_type; // 5 X 1 = 5 Nos
			    			$received_stock = $received_qty; // 5 Nos
			    		}

			    		if($view_stock >= $product_stock)
			    		{
			    			// Stock Process
				    		$new_stock    = $stock - $received_stock;
				    		$new_view_stk = $view_stock - $product_stock;

				    		$product_data = array(
				    			'vend_stock'   => $new_stock,
				    			'stock_detail' => $new_view_stk,
				    		);

				    		$product_whr = array('id' => $product_id);
							$update      = $this->commom_model->product_update($product_data, $product_whr);

					    	// Stock Process
				    		$new_type_stock    = $typeStock + $received_stock;
				    		$new_type_view_stk = $typeView_stock + $product_stock;

				    		$type_data = array(
				    			'type_stock'   => $new_type_stock,
				    			'stock_detail' => $new_type_view_stk,
				    		);

				    		$type_whr = array('id' => $type_id);
				    		$update   = $this->commom_model->productType_update($type_data, $type_whr);

				    		// Production Details
				    		$overColl_qty = $received_cou + $received_qty;

				    		$produc_data = array(
				    			'receive_qty' => strval($overColl_qty),
				    		);

				    		$produc_whr   = array('id' => $pro_auto_id);
				    		$update_prodc = $this->production_model->productionDetails_update($produc_data, $produc_whr);

				    		// Production Details Insert
				    		$ins_data = array(
						    	'pro_id'        => $pro_id,
						    	'pro_auto_id'   => $pro_auto_id,
						    	'product_id'    => $product_id,
						    	'type_id'       => $type_id,
						    	'product_unit'  => $unit_val,
						    	'received_qty'  => $received_qty,
						    	'received_date' => date('Y-m-d', strtotime($received_date)),
						    	'createdate'    => date('Y-m-d H:i:s')
						    );

						    $insert = $this->production_model->productionStkDetails_insert($ins_data);

						    // Production order qty details
						    $where_5 = array(
						    	'id'        => $pro_auto_id,
						    	'published' => '1',
						    	'status'    => '1',
						    );

						    $column_5 = 'order_qty';

				    		$order_data = $this->production_model->getProductionDetails($where_5, '', '', 'result', '', '', '', '', $column_5);

				    		$product_qty = !empty($order_data[0]->order_qty)?$order_data[0]->order_qty:'';

						    // Production receive qty details
						    $where_6 = array(
						    	'pro_id'      => $pro_id,
						    	'pro_auto_id' => $pro_auto_id, 
						    	'product_id'  => $product_id,
						    	'type_id'     => $type_id,
						    	'published'   => '1',
						    	'status'      => '1',
						    );

						    $column_6 = 'received_qty';

						    $ovr_collect_data = $this->production_model->getproductionStkDetails($where_6, '', '', 'result', '', '', '', '', $column_6);

						    $new_received_cou = 0;
					    	if(!empty($ovr_collect_data))
					    	{
					    		foreach ($ovr_collect_data as $key => $value) {
						    		$received_val  = !empty($value->received_qty)?$value->received_qty:'';
						    		$new_received_cou += $received_val; 
						    	}
					    	}

					    	if($product_qty == $new_received_cou)
					    	{
					    		$pro_data = array('item_process' => '2');
			            		$pro_whr  = array('id' => $pro_auto_id);
			            		$pro_upt  = $this->production_model->productionDetails_update($pro_data, $pro_whr);	
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
			}

			// Admin Stock Details
			else if($method == '_listAdminProductionStockDetails')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('pro_id', 'pro_auto_id', 'product_id', 'type_id');
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
			    		'pro_id'      => $pro_id,
			    		'pro_auto_id' => $pro_auto_id,
			    		'product_id'  => $product_id,
			    		'type_id'     => $type_id,
			    		'published'   => '1',
			    		'status'      => '1',
			    	);

			    	$stock_details = $this->production_model->getAdminProductionStkDetails($where_1);

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

			// Vendor Stock Details
			else if($method == '_listProductionStockDetails')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('pro_id', 'pro_auto_id', 'product_id', 'type_id');
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
			    		'pro_id'      => $pro_id,
			    		'pro_auto_id' => $pro_auto_id,
			    		'product_id'  => $product_id,
			    		'type_id'     => $type_id,
			    		'published'   => '1',
			    		'status'      => '1',
			    	);

			    	$stock_details = $this->production_model->getproductionStkDetails($where_1);

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

			// Delete Vendor Stock Details
			else if($method == '_deleteVendorProductionStockDetails')
			{
				if(!empty($stock_id))
				{
					$where_1 = array(
						'id'        => $stock_id,
						'published' => '1',
			    		'status'    => '1',
					);

					$stock_details = $this->production_model->getproductionStkDetails($where_1);

		            $pro_id       = !empty($stock_details[0]->pro_id)?$stock_details[0]->pro_id:'';
		            $pro_auto_id  = !empty($stock_details[0]->pro_auto_id)?$stock_details[0]->pro_auto_id:'';
		            $product_id   = !empty($stock_details[0]->product_id)?$stock_details[0]->product_id:'';
		            $type_id      = !empty($stock_details[0]->type_id)?$stock_details[0]->type_id:'';
		            $product_unit = !empty($stock_details[0]->product_unit)?$stock_details[0]->product_unit:'';
		            $received_qty = !empty($stock_details[0]->received_qty)?$stock_details[0]->received_qty:'';

		            // Product Type Stock Plus
			    	$where_2 = array('id' => $type_id, 'product_id' => $product_id);

			    	$productType_val = $this->commom_model->getProductType($where_2);

			    	$product_type    = !empty($productType_val[0]->product_type)?$productType_val[0]->product_type:'0';	
			    	$typeStock       = !empty($productType_val[0]->type_stock)?$productType_val[0]->type_stock:'0';	
			    	$typeView_stock  = !empty($productType_val[0]->stock_detail)?$productType_val[0]->stock_detail:'0';	

		            // View Stock
			    	if($product_unit == 1 || $product_unit == 11)
		    		{
		    			$multiple_stk   = $received_qty * $product_type; // 5 X 1 = 5 Kg
		    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
		    			$received_stock = $received_qty; // 5 Kg
		    		}
		    		else if($product_unit == 2 ||$product_unit == 4)
		    		{
		    			$product_stock  = $received_qty * $product_type; // 5 X 100 = 500 Gram
		    			$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
		    			$received_stock = number_format($received_value, 2);
		    		}
		    		else
		    		{
		    			$product_stock  = $received_qty * $product_type; // 5 X 1 = 5 Nos
		    			$received_stock = $received_qty; // 5 Nos
		    		}

		            // Product Type Stock Minus
		    		$new_type_stock    = $typeStock - $received_stock;
		    		$new_type_view_stk = $typeView_stock - $product_stock;

		    		$type_data = array(
		    			'type_stock'   => $new_type_stock,
		    			'stock_detail' => $new_type_view_stk,
		    		);

		    		$type_whr = array('id' => $type_id);
		    		$update   = $this->commom_model->productType_update($type_data, $type_whr);

		    		// Product Stock Plus
			    	$where_3 = array('id' => $product_id);

			    	$product_val = $this->commom_model->getProduct($where_3);
			    	$stock       = !empty($product_val[0]->vend_stock)?$product_val[0]->vend_stock:'0';	
			    	$view_stock  = !empty($product_val[0]->stock_detail)?$product_val[0]->stock_detail:'0';	

			    	// Stock Process
		    		$new_stock    = $stock + $received_stock;
		    		$new_view_stk = $view_stock + $product_stock;

		    		$product_data = array(
		    			'vend_stock'   => $new_stock,
		    			'stock_detail' => $new_view_stk,
		    		);

		    		$product_whr = array('id' => $product_id);
					$update      = $this->commom_model->product_update($product_data, $product_whr);

					// Production Details Stock Minus
		    		$where_4 = array('id' => $pro_auto_id, 'production_id' => $pro_id);
		    		$order_details = $this->production_model->getProductionDetails($where_4);
		    		$receive_qty   = !empty($order_details[0]->receive_qty)?$order_details[0]->receive_qty:'0';	

		    		// Stock Details
		    		$new_stock  = $receive_qty - $received_qty;
		    		$order_data = array(
		    			'receive_qty'  => $new_stock,
		    			'item_process' => '1',
		    		); 

		    		$order_whr  = array('id' => $pro_auto_id);
		    		$update_ord = $this->production_model->productionDetails_update($order_data, $order_whr);

		            // Delete Stock List
					$data = array(
				    	'published' => '0',
				    );

		    		$where  = array('id' => $stock_id);
				    $delete = $this->production_model->productionStkDetails_delete($data, $where);

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
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
				}
			}

			// Delete Admin Stock Details
			else if($method == '_deleteAdminProductionStockDetails')
			{
				if(!empty($stock_id))
				{
					$where_1 = array(
						'id'        => $stock_id,
						'published' => '1',
			    		'status'    => '1',
					);

					$stock_details = $this->production_model->getAdminProductionStkDetails($where_1);

		            $pro_id       = !empty($stock_details[0]->pro_id)?$stock_details[0]->pro_id:'';
		            $pro_auto_id  = !empty($stock_details[0]->pro_auto_id)?$stock_details[0]->pro_auto_id:'';
		            $product_id   = !empty($stock_details[0]->product_id)?$stock_details[0]->product_id:'';
		            $type_id      = !empty($stock_details[0]->type_id)?$stock_details[0]->type_id:'';
		            $product_unit = !empty($stock_details[0]->product_unit)?$stock_details[0]->product_unit:'';
		            $received_qty = !empty($stock_details[0]->received_qty)?$stock_details[0]->received_qty:'';

		            // Product Type Stock Plus
			    	$where_2 = array('id' => $type_id, 'product_id' => $product_id);

			    	$productType_val = $this->commom_model->getProductType($where_2);

			    	$product_type    = !empty($productType_val[0]->product_type)?$productType_val[0]->product_type:'0';	
			    	$typeStock       = !empty($productType_val[0]->type_stock)?$productType_val[0]->type_stock:'0';	
			    	$typeView_stock  = !empty($productType_val[0]->stock_detail)?$productType_val[0]->stock_detail:'0';	

		            // View Stock
			    	if($product_unit == 1 || $product_unit == 11)
		    		{
		    			$multiple_stk   = $received_qty * $product_type; // 5 X 1 = 5 Kg
		    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
		    			$received_stock = $received_qty; // 5 Kg
		    		}
		    		else if($product_unit == 2 || $product_unit == 4)
		    		{
		    			$product_stock  = $received_qty * $product_type; // 5 X 100 = 500 Gram
		    			$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
		    			$received_stock = number_format($received_value, 2);
		    		}
		    		else
		    		{
		    			$product_stock  = $received_qty * $product_type; // 5 X 1 = 5 Nos
		    			$received_stock = $received_qty; // 5 Nos
		    		}

		            // Product Type Stock Minus
		    		$new_type_stock    = $typeStock - $received_stock;
		    		$new_type_view_stk = $typeView_stock - $product_stock;

		    		$type_data = array(
		    			'type_stock'   => $new_type_stock,
		    			'stock_detail' => $new_type_view_stk,
		    		);

		    		$type_whr = array('id' => $type_id);
		    		$update   = $this->commom_model->productType_update($type_data, $type_whr);

		    		// Product Stock Plus
			    	$where_3 = array('id' => $product_id);

			    	$product_val = $this->commom_model->getProduct($where_3);
			    	$stock       = !empty($product_val[0]->vend_stock)?$product_val[0]->vend_stock:'0';	
			    	$view_stock  = !empty($product_val[0]->stock_detail)?$product_val[0]->stock_detail:'0';	

			    	// Stock Process
		    		$new_stock    = $stock + $received_stock;
		    		$new_view_stk = $view_stock + $product_stock;

		    		$product_data = array(
		    			'vend_stock'   => $new_stock,
		    			'stock_detail' => $new_view_stk,
		    		);

		    		$product_whr = array('id' => $product_id);
					$update      = $this->commom_model->product_update($product_data, $product_whr);

					// Production Details Stock Minus
		    		$where_4 = array('id' => $pro_auto_id, 'production_id' => $pro_id);
		    		$order_details = $this->production_model->getAdminProductionDetails($where_4);
		    		$receive_qty   = !empty($order_details[0]->receive_qty)?$order_details[0]->receive_qty:'0';	

		    		// Stock Details
		    		$new_stock  = $receive_qty - $received_qty;
		    		$order_data = array(
		    			'receive_qty'  => $new_stock,
		    			'item_process' => '1',
		    		); 

		    		$order_whr  = array('id' => $pro_auto_id);
		    		$update_ord = $this->production_model->adminProductionDetails_update($order_data, $order_whr);

		            // Delete Stock List
					$data = array(
				    	'published' => '0',
				    );

		    		$where  = array('id' => $stock_id);
				    $delete = $this->production_model->adminProductionStkDetails_delete($data, $where);

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
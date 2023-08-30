<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Stock extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('commom_model');
			$this->load->model('distributors_model');
			$this->load->model('stock_model');
			$this->load->model('assignproduct_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Add Distributor Stock
		// ***************************************************
		public function add_distributor_stock($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addDistributorStock')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id', 'stock_value', 'order_date', 'active_financial');
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
			    	$stock_value      = $this->input->post('stock_value');
			    	$order_date       = $this->input->post('order_date');
			    	$active_financial = $this->input->post('active_financial');

			    	// Return Number
			    	$where_1   = array(
			    		'distributor_id' => $distributor_id,
			    		'financial_year' => $active_financial,
			    	);

					$stock_val = $this->stock_model->getDistributorEntry($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

					$stock_count = !empty($stock_val[0]->autoid)?$stock_val[0]->autoid:'0';
					$stock_num   = 'STK'.leadingZeros($stock_count, 5);

					// Distributor Details
					$where_2  = array('id' => $distributor_id);
					$column_2 = 'company_name';
					$dis_val  = $this->distributors_model->getDistributors($where_2, '', '', 'result', '', '', '', '', $column_2);

					$dis_name = !empty($dis_val[0]->company_name)?$dis_val[0]->company_name:'';

					// Stock Data
					$stk_data = array(
						'stock_no'         => $stock_num,
						'distributor_id'   => $distributor_id,
						'distributor_name' => $dis_name,
						'order_date'       => date('Y-m-d', strtotime($order_date)),
						'financial_year'   => $active_financial,
						'createdate'       => date('Y-m-d H:i:s'),
					);

					$insert = $this->stock_model->distributorEntry_insert($stk_data);

					$stock_result = json_decode($stock_value);	

				    foreach ($stock_result as $key => $val) 
				    {
				    	$stock_val  = !empty($val->stock_val)?$val->stock_val:'0';
					    $damage_val = !empty($val->damage_val)?$val->damage_val:'0';
					    $expiry_val = !empty($val->expiry_val)?$val->expiry_val:'0';
					    $assign_val = !empty($val->assign_val)?$val->assign_val:'';

					    // Assing Product Details
					    $where_3  = array('id' => $assign_val);
					    $column_3 = 'category_id, product_id, type_id, description, product_unit, stock, view_stock';
					    $ass_val  = $this->assignproduct_model->getAssignProductDetails($where_3, '', '', 'result', '', '', '', '', $column_3);

					    $category_id    = !empty($ass_val[0]->category_id)?$ass_val[0]->category_id:'';
			            $product_id     = !empty($ass_val[0]->product_id)?$ass_val[0]->product_id:'';
			            $type_id        = !empty($ass_val[0]->type_id)?$ass_val[0]->type_id:'';
			            $description    = !empty($ass_val[0]->description)?$ass_val[0]->description:'';
			            $product_unit   = !empty($ass_val[0]->product_unit)?$ass_val[0]->product_unit:'';
			            $typeStock      = !empty($ass_val[0]->stock)?$ass_val[0]->stock:'0';
			            $typeView_stock = !empty($ass_val[0]->view_stock)?$ass_val[0]->view_stock:'0';

			            // Product Details
			            $where_4  = array('id' => $type_id);
			            $column_4 = 'product_type';
			            $type_val = $this->commom_model->getProductType($where_4, '', '', 'result', '', '', '', '', $column_4);

			            $pdt_type = !empty($type_val[0]->product_type)?$type_val[0]->product_type:'';

			            if($stock_val != 0)
			            {
			            	// View Stock
					    	if($product_unit == 1 || $product_unit == 11)
				    		{
				    			$multiple_stk   = $stock_val * $pdt_type; // 5 X 1 = 5 Kg
				    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
				    			$received_stock = $stock_val; // 5 Kg
				    		}
				    		else if($product_unit == 2 || $product_unit == 4)
				    		{
				    			$product_stock  = $stock_val * $pdt_type; // 5 X 100 = 500 Gram
				    			$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
				    			$received_stock = number_format($received_value, 2);
				    		}
				    		else
				    		{
				    			$product_stock  = $stock_val * $pdt_type; // 5 X 1 = 5 Nos
				    			$received_stock = $stock_val; // 5 Nos
				    		}

				    		// Stock Process
				    		$new_stock    = $typeStock + $received_stock;
				    		$new_view_stk = $typeView_stock + $product_stock;

				    		$product_data = array(
				    			'stock'      => $new_stock,
				    			'view_stock' => $new_view_stk,
				    		);

				    		$product_whr = array('id' => $assign_val);
							$update      = $this->assignproduct_model->assignProductDetails_update($product_data, $product_whr);
			            }

					    $stk_detail = array(
					    	'stock_id'       => $insert,
					    	'distributor_id' => $distributor_id,
					    	'assign_id'      => $assign_val,
					    	'category_id'    => $category_id,
					    	'product_id'     => $product_id,
					    	'type_id'        => $type_id,
					    	'description'    => $description,
					    	'product_unit'   => $product_unit,
					    	'stock_val'      => $stock_val,
					    	'damage_val'     => $damage_val,
					    	'expiry_val'     => $expiry_val,
					    	'date'           => date('Y-m-d', strtotime($order_date)),
					    	'createdate'     => date('Y-m-d H:i:s'),
					    );

					    $stk_ins = $this->stock_model->distributorEntryDetails_insert($stk_detail);
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

		// Manage Distributor Stock
		// ***************************************************
		public function manage_distributor_stock($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listDistributorStockPaginate')
			{
				$limit          = $this->input->post('limit');
	    		$offset         = $this->input->post('offset');
	    		$financ_year    = $this->input->post('financial_year');
	    		$distributor_id = $this->input->post('distributor_id');

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
				$overalldatas = $this->stock_model->getDistributorEntry($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->stock_model->getDistributorEntry($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$entry_list = [];
					foreach ($data_list as $key => $val) {
						$stock_id         = !empty($val->id)?$val->id:'';
						$stock_no         = !empty($val->stock_no)?$val->stock_no:'';
						$distributor_id   = !empty($val->distributor_id)?$val->distributor_id:'';
						$distributor_name = !empty($val->distributor_name)?$val->distributor_name:'';
						$order_date       = !empty($val->order_date)?$val->order_date:'';
						$status           = !empty($val->status)?$val->status:'';

						$entry_list[] = array(
							'stock_id'         => $stock_id,
							'stock_no'         => $stock_no,
							'distributor_id'   => $distributor_id,
							'distributor_name' => $distributor_name,
							'order_date'       => date('d-M-Y', strtotime($order_date)),
							'status'           => $status,
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
			        $response['data']         = $entry_list;
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

			else if($method == '_viewDistributorStock')
			{
				$stock_id = $this->input->post('stock_id');

				if(!empty($stock_id))
				{
					// Stock Data
					$where_1  = array('id' => $stock_id);
					$column_1 = 'stock_no, distributor_name, order_date';
					$data_1   = $this->stock_model->getDistributorEntry($where_1, '', '', 'result', '', '', '', '', $column_1);

					$stock_no   = !empty($data_1[0]->stock_no)?$data_1[0]->stock_no:'';
		            $dis_name   = !empty($data_1[0]->distributor_name)?$data_1[0]->distributor_name:'';
		            $order_date = !empty($data_1[0]->order_date)?$data_1[0]->order_date:'';

		            $stock_data = array(
		            	'stock_no'         => $stock_no,
			            'distributor_name' => $dis_name,
			            'order_date'       => date('d-M-Y', strtotime($order_date)),
		            );

		            // Stock Details
		            $where_2  = array('stock_id' => $stock_id);
		            $column_2 = 'description, stock_val, damage_val, expiry_val';
		            $data_2   = $this->stock_model->getDistributorEntryDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

		            $stock_details = [];
		            if($data_2)
		            {
		            	foreach ($data_2 as $key => $val_2) {
		            		$description = !empty($val_2->description)?$val_2->description:'';
							$stock_val   = !empty($val_2->stock_val)?$val_2->stock_val:'0';
							$damage_val  = !empty($val_2->damage_val)?$val_2->damage_val:'0';
							$expiry_val  = !empty($val_2->expiry_val)?$val_2->expiry_val:'0';

							$stock_details[] = array(
								'description' => $description,
								'stock_val'   => $stock_val,
								'damage_val'  => $damage_val,
								'expiry_val'  => $expiry_val,
							);
		            	}
		            }

		            $stock_res = array(
		            	'stock_data'    => $stock_data,
		            	'stock_details' => $stock_details,
		            );

		            $response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $stock_res;
			        echo json_encode($response);
			        return;
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

		// Add Stock
		// ***************************************************
		public function add_stock($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addStockEntry')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('stock_value', 'order_date', 'active_financial');
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
			    	$category_id      = $this->input->post('category_id');
			    	$stock_value      = $this->input->post('stock_value');
			    	$order_date       = $this->input->post('order_date');
			    	$active_financial = $this->input->post('active_financial');

			    	// Return Number
			    	$where_1   = array(
			    		'financial_year' => $active_financial,
			    	);

					$stock_val = $this->stock_model->getProductEntry($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

					$stock_count = !empty($stock_val[0]->autoid)?$stock_val[0]->autoid:'0';
					$stock_num   = 'STK'.leadingZeros($stock_count, 5);

					// Stock Data
					$stk_data = array(
						'stock_no'         => $stock_num,
						'order_date'       => date('Y-m-d', strtotime($order_date)),
						'financial_year'   => $active_financial,
						'createdate'       => date('Y-m-d H:i:s'),
					);

					$insert = $this->stock_model->productEntry_insert($stk_data);

					$stock_result = json_decode($stock_value);	

				    foreach ($stock_result as $key => $val) 
				    {
				    	$stock_val   = !empty($val->stock_val)?$val->stock_val:'0';
					    $damage_val  = !empty($val->damage_val)?$val->damage_val:'0';
					    $expiry_val  = !empty($val->expiry_val)?$val->expiry_val:'0';
					    $type_id     = !empty($val->type_id)?$val->type_id:'';
					    $product_id  = !empty($val->product_id)?$val->product_id:'';
					    $category_id = !empty($val->category_id)?$val->category_id:'';

					    // Product Details
			            $where_3  = array('id' => $type_id);
			            $column_3 = 'description, product_type, product_unit, product_stock, view_stock';
			            $type_val = $this->commom_model->getProductType($where_3, '', '', 'result', '', '', '', '', $column_3);

			            $description    = !empty($type_val[0]->description)?$type_val[0]->description:'';
			            $product_type   = !empty($type_val[0]->product_type)?$type_val[0]->product_type:'0';
			            $product_unit   = !empty($type_val[0]->product_unit)?$type_val[0]->product_unit:'';
			            $typeStock      = !empty($type_val[0]->product_stock)?$type_val[0]->product_stock:'0';
			            $typeView_stock = !empty($type_val[0]->view_stock)?$type_val[0]->view_stock:'0';

			            if($stock_val != 0)
			            {
			            	// View Stock
					    	if($product_unit == 1 || $product_unit == 11)
				    		{
				    			$multiple_stk   = $stock_val * $product_type; // 5 X 1 = 5 Kg
				    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
				    			$received_stock = $stock_val; // 5 Kg
				    		}
				    		else if($product_unit == 2 || $product_unit == 4)
				    		{
				    			$product_stock  = $stock_val * $product_type; // 5 X 100 = 500 Gram
				    			$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
				    			$received_stock = number_format($received_value, 2);
				    		}
				    		else
				    		{
				    			$product_stock  = $stock_val * $product_type; // 5 X 1 = 5 Nos
				    			$received_stock = $stock_val; // 5 Nos
				    		}

				    		// Stock Process
				    		$new_stock    = $typeStock + $received_stock;
				    		$new_view_stk = $typeView_stock + $product_stock;

				    		$product_data = array(
				    			'product_stock'      => $new_stock,
				    			'view_stock' => $new_view_stk,
				    		);

				    		$product_whr = array('id' => $type_id);
							$update      = $this->commom_model->productType_update($product_data, $product_whr);
			            }

			            $stk_detail = array(
					    	'stock_id'     => $insert,
					    	'category_id'  => $category_id,
					    	'product_id'   => $product_id,
					    	'type_id'      => $type_id,
					    	'category_id'  => $category_id,
					    	'description'  => $description,
					    	'product_unit' => $product_unit,
					    	'stock_val'    => $stock_val,
					    	'damage_val'   => $damage_val,
					    	'expiry_val'   => $expiry_val,
					    	'date'         => date('Y-m-d', strtotime($order_date)),
					    	'createdate'   => date('Y-m-d H:i:s'),
					    );

					    $stk_ins = $this->stock_model->productEntryDetails_insert($stk_detail);
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

		// Manage Stock
		// ***************************************************
		public function manage_stock($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listProductStockPaginate')
			{
				$limit       = $this->input->post('limit');
	    		$offset      = $this->input->post('offset');
	    		$financ_year = $this->input->post('financial_year');

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
				$overalldatas = $this->stock_model->getProductEntry($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->stock_model->getProductEntry($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$entry_list = [];
					foreach ($data_list as $key => $val) {
						$stock_id         = !empty($val->id)?$val->id:'';
						$stock_no         = !empty($val->stock_no)?$val->stock_no:'';
						$order_date       = !empty($val->order_date)?$val->order_date:'';
						$status           = !empty($val->status)?$val->status:'';

						$entry_list[] = array(
							'stock_id'         => $stock_id,
							'stock_no'         => $stock_no,
							'order_date'       => date('d-M-Y', strtotime($order_date)),
							'status'           => $status,
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
			        $response['data']         = $entry_list;
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

			else if($method == '_viewProductStock')
			{
				$stock_id = $this->input->post('stock_id');

				if(!empty($stock_id))
				{
					// Stock Data
					$where_1  = array('id' => $stock_id);
					$column_1 = 'stock_no, order_date';
					$data_1   = $this->stock_model->getProductEntry($where_1, '', '', 'result', '', '', '', '', $column_1);

					$stock_no   = !empty($data_1[0]->stock_no)?$data_1[0]->stock_no:'';
		            $order_date = !empty($data_1[0]->order_date)?$data_1[0]->order_date:'';

		            $stock_data = array(
		            	'stock_no'   => $stock_no,
			            'order_date' => date('d-M-Y', strtotime($order_date)),
		            );

		            // Stock Details
		            $where_2  = array('stock_id' => $stock_id);
		            $column_2 = 'description, stock_val, damage_val, expiry_val';
		            $data_2   = $this->stock_model->getProductEntryDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

		            $stock_details = [];
		            if($data_2)
		            {
		            	foreach ($data_2 as $key => $val_2) {
		            		$description = !empty($val_2->description)?$val_2->description:'';
							$stock_val   = !empty($val_2->stock_val)?$val_2->stock_val:'0';
							$damage_val  = !empty($val_2->damage_val)?$val_2->damage_val:'0';
							$expiry_val  = !empty($val_2->expiry_val)?$val_2->expiry_val:'0';

							$stock_details[] = array(
								'description' => $description,
								'stock_val'   => $stock_val,
								'damage_val'  => $damage_val,
								'expiry_val'  => $expiry_val,
							);
		            	}
		            }

		            $stock_res = array(
		            	'stock_data'    => $stock_data,
		            	'stock_details' => $stock_details,
		            );

		            $response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $stock_res;
			        echo json_encode($response);
			        return;
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

		// Add Vendor Stock
		// ***************************************************
		public function add_vendor_stock($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addVendorStockEntry')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('vendor_id', 'stock_value', 'order_date', 'active_financial');
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
			    	$stock_value      = $this->input->post('stock_value');
			    	$order_date       = $this->input->post('order_date');
			    	$active_financial = $this->input->post('active_financial');

			    	// Return Number
			    	$where_1   = array(
			    		'financial_year' => $active_financial,
			    	);

					$stock_val = $this->stock_model->getVendorEntry($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

					$stock_count = !empty($stock_val[0]->autoid)?$stock_val[0]->autoid:'0';
					$stock_num   = 'STK'.leadingZeros($stock_count, 5);

					// Stock Data
					$stk_data = array(
						'stock_no'         => $stock_num,
						'vendor_id'        => $vendor_id,
						'order_date'       => date('Y-m-d', strtotime($order_date)),
						'financial_year'   => $active_financial,
						'createdate'       => date('Y-m-d H:i:s'),
					);

					$insert = $this->stock_model->vendorEntry_insert($stk_data);

					$stock_result = json_decode($stock_value);	

				    foreach ($stock_result as $key => $val) 
				    {
				    	$stock_val   = !empty($val->stock_val)?$val->stock_val:'0';
					    $damage_val  = !empty($val->damage_val)?$val->damage_val:'0';
					    $expiry_val  = !empty($val->expiry_val)?$val->expiry_val:'0';
					    $type_id     = !empty($val->type_id)?$val->type_id:'';
					    $category_id = !empty($val->category_id)?$val->category_id:'';
					    $product_id  = !empty($val->product_id)?$val->product_id:'';

					    // Product Details
			            $where_3  = array('id' => $type_id);
			            $column_3 = 'description, product_type, product_unit, type_stock, stock_detail';
			            $type_val = $this->commom_model->getProductType($where_3, '', '', 'result', '', '', '', '', $column_3);

			            $description    = !empty($type_val[0]->description)?$type_val[0]->description:'';
			            $product_type   = !empty($type_val[0]->product_type)?$type_val[0]->product_type:'0';
			            $product_unit   = !empty($type_val[0]->product_unit)?$type_val[0]->product_unit:'';
			            $typeStock      = !empty($type_val[0]->type_stock)?$type_val[0]->type_stock:'0';
			            $typeView_stock = !empty($type_val[0]->stock_detail)?$type_val[0]->stock_detail:'0';

			            if($stock_val != 0)
			            {
			            	// View Stock
					    	if($product_unit == 1 || $product_unit == 11)
				    		{
				    			$multiple_stk   = $stock_val * $product_type; // 5 X 1 = 5 Kg
				    			$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
				    			$received_stock = $stock_val; // 5 Kg
				    		}
				    		else if($product_unit == 2 || $product_unit == 4)
				    		{
				    			$product_stock  = $stock_val * $product_type; // 5 X 100 = 500 Gram
				    			$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
				    			$received_stock = number_format($received_value, 2);
				    		}
				    		else
				    		{
				    			$product_stock  = $stock_val * $product_type; // 5 X 1 = 5 Nos
				    			$received_stock = $stock_val; // 5 Nos
				    		}

				    		// Stock Process
				    		$new_stock    = $typeStock + $received_stock;
				    		$new_view_stk = $typeView_stock + $product_stock;

				    		$product_data = array(
				    			'type_stock'   => $new_stock,
				    			'stock_detail' => $new_view_stk,
				    		);

				    		$product_whr = array('id' => $type_id);
							$update      = $this->commom_model->productType_update($product_data, $product_whr);
			            }

			            $stk_detail = array(
					    	'stock_id'       => $insert,
					    	'vendor_id'      => $vendor_id,
					    	'category_id'    => $category_id,
					    	'product_id'     => $product_id,
					    	'category_id'    => $category_id,
					    	'type_id'        => $type_id,
					    	'description'    => $description,
					    	'product_unit'   => $product_unit,
					    	'stock_val'      => $stock_val,
					    	'damage_val'     => $damage_val,
					    	'expiry_val'     => $expiry_val,
					    	'date'           => date('Y-m-d', strtotime($order_date)),
					    	'createdate'     => date('Y-m-d H:i:s'),
					    );

					    $stk_ins = $this->stock_model->vendorEntryDetails_insert($stk_detail);
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

		// Manage Vendor Stock
		// ***************************************************
		public function manage_vendor_stock($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listVendorStockPaginate')
			{
				$limit       = $this->input->post('limit');
	    		$offset      = $this->input->post('offset');
	    		$financ_year = $this->input->post('financial_year');

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
				$overalldatas = $this->stock_model->getVendorEntry($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->stock_model->getVendorEntry($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$entry_list = [];
					foreach ($data_list as $key => $val) {
						$stock_id   = !empty($val->id)?$val->id:'';
						$stock_no   = !empty($val->stock_no)?$val->stock_no:'';
						$order_date = !empty($val->order_date)?$val->order_date:'';
						$status     = !empty($val->status)?$val->status:'';

						$entry_list[] = array(
							'stock_id'   => $stock_id,
							'stock_no'   => $stock_no,
							'order_date' => date('d-M-Y', strtotime($order_date)),
							'status'     => $status,
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
			        $response['data']         = $entry_list;
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

			else if($method == '_viewProductStock')
			{
				$stock_id = $this->input->post('stock_id');

				if(!empty($stock_id))
				{
					// Stock Data
					$where_1  = array('id' => $stock_id);
					$column_1 = 'stock_no, order_date';
					$data_1   = $this->stock_model->getVendorEntry($where_1, '', '', 'result', '', '', '', '', $column_1);

					$stock_no   = !empty($data_1[0]->stock_no)?$data_1[0]->stock_no:'';
		            $order_date = !empty($data_1[0]->order_date)?$data_1[0]->order_date:'';

		             $stock_data = array(
		            	'stock_no'   => $stock_no,
			            'order_date' => date('d-M-Y', strtotime($order_date)),
		            );

		            // Stock Details
		            $where_2  = array('stock_id' => $stock_id);
		            $column_2 = 'description, stock_val, damage_val, expiry_val';
		            $data_2   = $this->stock_model->getVendorEntryDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

		            $stock_details = [];
		            if($data_2)
		            {
		            	foreach ($data_2 as $key => $val_2) {
		            		$description = !empty($val_2->description)?$val_2->description:'';
							$stock_val   = !empty($val_2->stock_val)?$val_2->stock_val:'0';
							$damage_val  = !empty($val_2->damage_val)?$val_2->damage_val:'0';
							$expiry_val  = !empty($val_2->expiry_val)?$val_2->expiry_val:'0';

							$stock_details[] = array(
								'description' => $description,
								'stock_val'   => $stock_val,
								'damage_val'  => $damage_val,
								'expiry_val'  => $expiry_val,
							);
		            	}
		            }

		            $stock_res = array(
		            	'stock_data'    => $stock_data,
		            	'stock_details' => $stock_details,
		            );

		            $response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $stock_res;
			        echo json_encode($response);
			        return;
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
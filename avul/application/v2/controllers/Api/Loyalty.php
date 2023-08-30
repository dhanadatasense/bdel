<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class Loyalty extends CI_Controller {

		public function __construct()
		{
			parent::__construct();

			$this->load->model('loyalty_model');
			$this->load->model('commom_model');
			$this->load->model('outlets_model');
		}

		public function index()
		{
			echo "Test";
		}

		// product_loyalty
		// ***************************************************
		public function product_loyalty($param1="",$param2="",$param3="")
		{	
			$error = FALSE;
			$method       = $this->input->post('method');
			$log_id       = $this->input->post('log_id');
			$log_role     = $this->input->post('log_role');
			$loyalty_id   = $this->input->post('loyalty_id');
			$auto_id      = $this->input->post('auto_id');
			$state_id     = $this->input->post('state_id');
			$city_id      = $this->input->post('city_id');
			$zone_id      = $this->input->post('zone_id');
			$outlet_id    = $this->input->post('outlet_id');
			$vendor_id    = $this->input->post('vendor_id');
			$start_date   = $this->input->post('start_date');
			$end_date     = $this->input->post('end_date');
			$category_val = $this->input->post('category_val');
			$category_id  = $this->input->post('category_id');
			$price_value  = $this->input->post('price_value');
			$limit        = $this->input->post('limit');
	    	$offset       = $this->input->post('offset');

			if($method == '_overallProductList')
			{
				$required = array('start_date', 'end_date', 'category_id');
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
			    	if($start_date <= $end_date)
			    	{
			    		$start_val = date('Y-m-d', strtotime($start_date));
						$end_val   = date('Y-m-d', strtotime($end_date));

			    		$data_whr = array(
							'category_id'  => $category_id,
							'published'    => '1',
							'status'       => '1',
						);	

						if($vendor_id != 0)
						{
							$data_whr['vendor_id'] = $vendor_id;
						}

				    	$data_col = 'id, category_id, product_id, description, product_price';
						$data_res = $this->commom_model->getProductTypeImplode($data_whr, '', '', 'result', '', '', '', '', $data_col);
						if($data_res)
						{
							$product_details = [];
							foreach ($data_res as $key => $data_val) {
								
								$type_id       = zero_check($data_val->id);
								$category_id   = zero_check($data_val->category_id);
					            $product_id    = zero_check($data_val->product_id);
					            $description   = empty_check($data_val->description);
					            $product_price = zero_check($data_val->product_price);

					            // Overall wise
						    	if($state_id == 0 && $city_id == 0 && $zone_id == 0 && $outlet_id == 0)
						    	{
						    		$whr_1 = array(
					            		'state_id'           => 0,
					            		'city_id'            => 0,
					            		'beat_id'            => 0,
					            		'outlet_id'          => 0,
					            		'type_id'            => $type_id,
					            		'published'          => '1',
					            	);

						    		$like  = array('start_val'=>$start_val, 'end_val'=>$end_val);
					            	$col_1 = 'id';
						    		$res_1 = $this->loyalty_model->getProductLoyaltyDetails($whr_1, '', '', 'result', $like, '', '', '', $col_1);

						    		if(empty($res_1))
						    		{
						    			$overall_wise = array(
						    				'type_id'       => $type_id,
						    				'category_id'   => $category_id,
						    				'product_id'    => $product_id,
		                                    'description'   => $description,
		                                    'product_price' => $product_price,
		                                );

		                                array_push($product_details, $overall_wise);
						    		}
						    	}

						    	// Outlet wise
						    	else if($state_id != 0 && $city_id != 0 && $zone_id != 0 && $outlet_id != 0)
						    	{
					            	$whr_2 = array(
					            		'state_id'  => $state_id,
					            		'city_id'   => $city_id,
					            		'beat_id'   => $zone_id,
					            		'outlet_id' => $outlet_id,
					            		'type_id'   => $type_id,
					            		'published' => '1',
					            	);

					            	$like  = array('start_val'=>$start_val, 'end_val'=>$end_val);
					            	$col_2 = 'id';
						    		$res_2 = $this->loyalty_model->getProductLoyaltyDetails($whr_2, '', '', 'result', $like, '', '', '', $col_2);

						    		if(empty($res_2))
						    		{
						    			$overall_wise = array(
		                                    'type_id'       => $type_id,
						    				'category_id'   => $category_id,
						    				'product_id'    => $product_id,
		                                    'description'   => $description,
		                                    'product_price' => $product_price,
		                                );

		                                array_push($product_details, $overall_wise);
						    		}
						    	}

						    	// Beat wise
						    	else if($state_id != 0 && $city_id != 0 && $zone_id != 0 && $outlet_id == 0)
						    	{
						    		$whr_3 = array(
					            		'state_id'  => $state_id,
					            		'city_id'   => $city_id,
					            		'beat_id'   => $zone_id,
					            		'outlet_id' => 0,
					            		'type_id'   => $type_id,
					            		'published' => '1',
					            	);

					            	$like  = array('start_val'=>$start_val, 'end_val'=>$end_val);
					            	$col_3 = 'id';
						    		$res_3 = $this->loyalty_model->getProductLoyaltyDetails($whr_3, '', '', 'result', $like, '', '', '', $col_3);

						    		if(empty($res_3))
						    		{
						    			$city_wise = array(
		                                    'type_id'       => $type_id,
						    				'category_id'   => $category_id,
						    				'product_id'    => $product_id,
		                                    'description'   => $description,
		                                    'product_price' => $product_price,
		                                );

		                                array_push($product_details, $city_wise);
						    		}
						    	}

						    	// City wise
						    	else if($state_id != 0 && $city_id != 0 && $zone_id == 0 && $outlet_id == 0)
						    	{
						    		$whr_4 = array(
					            		'state_id'  => $state_id,
					            		'city_id'   => $city_id,
					            		'beat_id'   => 0,
					            		'outlet_id' => 0,
					            		'type_id'   => $type_id,
					            		'published' => '1',
					            	);

					            	$like  = array('start_val'=>$start_val, 'end_val'=>$end_val);
					            	$col_4 = 'id';
						    		$res_4 = $this->loyalty_model->getProductLoyaltyDetails($whr_4, '', '', 'result', $like, '', '', '', $col_4);

						    		if(empty($res_4))
						    		{
						    			$city_wise = array(
		                                    'type_id'       => $type_id,
						    				'category_id'   => $category_id,
						    				'product_id'    => $product_id,
		                                    'description'   => $description,
		                                    'product_price' => $product_price,
		                                );

		                                array_push($product_details, $city_wise);
						    		}
						    	}

						    	// State wise
						    	else if($state_id != 0 && $city_id == 0 && $zone_id == 0 && $outlet_id == 0)
						    	{
						    		$whr_5 = array(
					            		'state_id'  => $state_id,
					            		'city_id'   => 0,
					            		'beat_id'   => 0,
					            		'outlet_id' => 0,
					            		'type_id'   => $type_id,
					            		'published' => '1',
					            	);

					            	$like  = array('start_val'=>$start_val, 'end_val'=>$end_val);
					            	$col_5 = 'id';
						    		$res_5 = $this->loyalty_model->getProductLoyaltyDetails($whr_5, '', '', 'result', $like, '', '', '', $col_5);

						    		if(empty($res_5))
						    		{
						    			$city_wise = array(
		                                    'type_id'       => $type_id,
						    				'category_id'   => $category_id,
						    				'product_id'    => $product_id,
		                                    'description'   => $description,
		                                    'product_price' => $product_price,
		                                );

		                                array_push($product_details, $city_wise);
						    		}
						    	}
					        }

					        if($product_details)
					        {
					        	$response['status']  = 1;
						        $response['message'] = "Success"; 
						        $response['data']    = $product_details;
						        echo json_encode($response);
						        return;
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
				        $response['message'] = "Date incorrect"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			    }
			}

			else if($method == '_addProductLoyalty')
			{
				$required = array('start_date', 'end_date', 'category_val', 'price_value');
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
			    	// get description detials
			    	$description = '';

			    	if($outlet_id != 0)
			    	{
			    		// Get outlet details
			    		$whr_1 = array('id' => $outlet_id);
			    		$res_1 = $this->outlets_model->getOutlets($whr_1, '', '', 'row', '', '', '', '', 'company_name');

			    		$description = empty_check($res_1->company_name);
			    	}
			    	else if($zone_id != 0)
			    	{
			    		// Get beat details
			    		$whr_4 = array('id' => $zone_id);
			    		$res_4 = $this->commom_model->getZone($whr_4, '', '', 'row', '', '', '', '', 'name');

			    		$description = empty_check($res_4->name);
			    	}
			    	else if($city_id != 0)
			    	{
			    		// Get city details
			    		$whr_3 = array('id' => $city_id);
			    		$res_3 = $this->commom_model->getCity($whr_3, '', '', 'row', '', '', '', '', 'city_name');

			    		$description = empty_check($res_3->city_name);
			    	}
			    	else if($state_id != 0)
			    	{
			    		// Get state details
			    		$whr_2 = array('id' => $state_id);
			    		$res_2 = $this->commom_model->getState($whr_2, '', '', 'row', '', '', '', '', 'state_name');

			    		$description = empty_check($res_2->state_name);
			    	}
			    	else
			    	{
			    		$description = 'Overall';
			    	}

			    	$start_val    = date('Y-m-d', strtotime($start_date));
			    	$end_val      = date('Y-m-d', strtotime($end_date));
			    	$date_result  = getDatesFromRange($start_val, $end_val);
			    	$loyalty_date = implode(",", $date_result);

			    	$whr_5 = array(
	            		'state_id'  => zero_check($state_id),
	            		'city_id'   => zero_check($city_id),
	            		'beat_id'   => zero_check($zone_id),
	            		'outlet_id' => zero_check($outlet_id),
	            		'published' => '1',
	            	);

	            	$like  = array('start_val'=>$start_val, 'end_val'=>$end_val);
	            	$col_5 = 'id';
		    		$res_5 = $this->loyalty_model->getProductLoyaltyMerge($whr_5, '', '', 'row', $like, '', '', '', 'COUNT(id) AS count');

		    		if(zero_check($res_5->count) == 0)
		    		{
		    			$loyalty_data = array(
				    		'state_id'     => zero_check($state_id),
				    		'city_id'      => zero_check($city_id), 
				    		'beat_id'      => zero_check($zone_id), 
				    		'outlet_id'    => zero_check($outlet_id),
				    		'vendor_id'    => zero_check($vendor_id),
				    		'start_date'   => date('Y-m-d', strtotime($start_date)),
				    		'end_date'     => date('Y-m-d', strtotime($end_date)),
				    		'loyalty_date' => $loyalty_date,
				    		'category_val' => $category_val,
				    		'description'  => $description,
				    		'date'         => date('Y-m-d'),
				    		'createdate'   => date('Y-m-d H:i:s'),
				    	);

				    	$insert = $this->loyalty_model->productLoyalty_insert($loyalty_data);

				    	$log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_product_loyalty',
							'auto_id'    => $insert,
							'action'     => 'create',
							'date'       => date('Y-m-d'),
							'time'       => date('H:i:s'),
							'createdate' => date('Y-m-d H:i:s')
						);

						$log_val = $this->commom_model->log_insert($log_data);

				    	$price_result = json_decode($price_value);

				    	foreach ($price_result as $key => $val) {

				    		$category_id   = zero_check($val->category_id);
						    $product_id    = zero_check($val->product_id);
						    $type_id       = zero_check($val->type_id);
						    $product_price = zero_check($val->product_price);
						    $loyalty_type  = zero_check($val->loyalty_type);

						    if($product_price != 0)
						    {
						    	$loyaltyDet_data = array(
							    	'pdt_loyalty_id' => $insert,
							    	'state_id'       => zero_check($state_id),
						    		'city_id'        => zero_check($city_id), 
						    		'beat_id'        => zero_check($zone_id), 
						    		'outlet_id'      => zero_check($outlet_id),
						    		'vendor_id'      => zero_check($vendor_id),
						    		'start_date'     => date('Y-m-d', strtotime($start_date)),
						    		'end_date'       => date('Y-m-d', strtotime($end_date)),
						    		'loyalty_date'   => $loyalty_date,
						    		'description'    => $description,
						    		'category_id'    => zero_check($category_id),
						    		'product_id'     => zero_check($product_id),
						    		'type_id'        => zero_check($type_id),
						    		'loyalty_type'   => zero_check($loyalty_type),
						    		'product_price'  => zero_check($product_price),
						    		'date'           => date('Y-m-d'),
					    			'createdate'     => date('Y-m-d H:i:s'),
							    );

							    $ins_Det = $this->loyalty_model->productLoyaltyDetails_insert($loyaltyDet_data);
						    }
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
				        $response['message'] = "Data Already Exist"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
		    		}
			    }
			}

			else if($method == '_listProductLoyaltyPaginate')
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

	    		$where = array('published'=>'1');

	    		$column_1     = 'id';
				$overalldatas = $this->loyalty_model->getProductLoyalty($where, '', '', 'result', $like, '', '', '', $column_1);

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

				$column_2  = 'id, start_date, end_date, description, status, createdate';
				$data_list = $this->loyalty_model->getProductLoyalty($where, $limit, $offset, 'result', $like, '', $option, '', $column_2);

				if($data_list)
				{
					$loyalty_list = [];
					foreach ($data_list as $key => $val) {
						$loyalty_id  = empty_check($val->id);
			            $start_date  = date_check(empty_check($val->start_date));
			            $end_date    = date_check(empty_check($val->end_date));
			            $description = empty_check($val->description);
			            $status      = empty_check($val->status);
			            $createdate  = empty_check($val->createdate);

			            $loyalty_list[] = array(
			            	'loyalty_id'  => $loyalty_id,
				            'start_date'  => $start_date,
				            'end_date'    => $end_date,
				            'description' => $description,
				            'status'      => $status,
				            'createdate'  => $createdate,
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
			        $response['data']         = $loyalty_list;
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

			else if($method == '_productLoyaltyDetails')
			{
				if($loyalty_id)
				{
					// Loyalty header
					$whr_1 = array('id' => $loyalty_id, 'published' => '1');
					$col_1 = 'state_id, city_id, beat_id, outlet_id, vendor_id, start_date, end_date, category_val, status';
					$res_1 = $this->loyalty_model->getProductLoyalty($whr_1, '', '', 'row', '', '', '', '', $col_1);

					if($res_1)
					{
						$state_id     = zero_check($res_1->state_id);
					    $city_id      = zero_check($res_1->city_id);
					    $beat_id      = zero_check($res_1->beat_id);
					    $outlet_id    = zero_check($res_1->outlet_id);
					    $vendor_id    = zero_check($res_1->vendor_id);
					    $start_date   = empty_check($res_1->start_date);
					    $end_date     = empty_check($res_1->end_date);
					    $category_val = empty_check($res_1->category_val);
					    $status       = empty_check($res_1->status);

					    $loyalty_header = array(
					    	'loyalty_id'   => $loyalty_id,
					    	'state_id'     => $state_id,
						    'city_id'      => $city_id,
						    'beat_id'      => $beat_id,
						    'outlet_id'    => $outlet_id,
						    'vendor_id'    => $vendor_id,
						    'start_date'   => date_check($start_date),
						    'end_date'     => date_check($end_date),
						    'category_val' => $category_val,
						    'status'       => $status,
					    );

					    // Loyalty body
					    $loyalty_body = [];

					    $whr_2 = array('A.pdt_loyalty_id' => $loyalty_id, 'A.published' => '1');
					    $col_2 = 'A.id, A.category_id, A.product_id, A.type_id, A.product_price, A.loyalty_type, B.description';
					    $res_2 = $this->loyalty_model->getProductLoyaltyDetailsJoin($whr_2, '', '', 'result', '', '', '', '', $col_2);

					    if($res_2)
					    {
					    	foreach ($res_2 as $key => $val_1) {
					    		$auto_id       = empty_check($val_1->id);
					    		$category_id   = empty_check($val_1->category_id);
					            $product_id    = empty_check($val_1->product_id);
					            $type_id       = empty_check($val_1->type_id);
					            $description   = empty_check($val_1->description);
					            $loyalty_type  = empty_check($val_1->loyalty_type);
					            $product_price = empty_check($val_1->product_price);

					            $loyalty_body[] = array(
					            	'auto_id'       => $auto_id,
					            	'loyalty_id'    => $loyalty_id,
					            	'category_id'   => $category_id,
									'product_id'    => $product_id,
									'type_id'       => $type_id,
									'loyalty_type'  => $loyalty_type,
									'product_price' => $product_price,
									'description'   => $description,
					            );
					    	}
					    }

					    $data_result = array(
					    	'loyalty_header' => $loyalty_header,
					    	'loyalty_body'   => $loyalty_body,
					    );

					    $response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $data_result;
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
			        $response['error']   = []; 
			        echo json_encode($response);
			        return;
				}
			}

			else if($method == '_updateProductLoyalty')
			{
				$required = array('loyalty_id', 'start_date', 'end_date', 'category_val', 'price_value');
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
			    	// get description detials
			    	$description = '';

			    	if($outlet_id != 0)
			    	{
			    		// Get outlet details
			    		$whr_1 = array('id' => $outlet_id);
			    		$res_1 = $this->outlets_model->getOutlets($whr_1, '', '', 'row', '', '', '', '', 'company_name');

			    		$description = empty_check($res_1->company_name);
			    	}
			    	else if($zone_id != 0)
			    	{
			    		// Get beat details
			    		$whr_4 = array('id' => $zone_id);
			    		$res_4 = $this->commom_model->getZone($whr_4, '', '', 'row', '', '', '', '', 'name');

			    		$description = empty_check($res_4->name);
			    	}
			    	else if($city_id != 0)
			    	{
			    		// Get city details
			    		$whr_3 = array('id' => $city_id);
			    		$res_3 = $this->commom_model->getCity($whr_3, '', '', 'row', '', '', '', '', 'city_name');

			    		$description = empty_check($res_3->city_name);
			    	}
			    	else if($state_id != 0)
			    	{
			    		// Get state details
			    		$whr_2 = array('id' => $state_id);
			    		$res_2 = $this->commom_model->getState($whr_2, '', '', 'row', '', '', '', '', 'state_name');

			    		$description = empty_check($res_2->state_name);
			    	}
			    	else
			    	{
			    		$description = 'Overall';
			    	}

			    	$start_val    = date('Y-m-d', strtotime($start_date));
			    	$end_val      = date('Y-m-d', strtotime($end_date));
			    	$date_result  = getDatesFromRange($start_val, $end_val);
			    	$loyalty_date = implode(",", $date_result);

			    	$whr_5 = array(
			    		'id !='     => $loyalty_id,
	            		'state_id'  => zero_check($state_id),
	            		'city_id'   => zero_check($city_id),
	            		'beat_id'   => zero_check($zone_id),
	            		'outlet_id' => zero_check($outlet_id),
	            		'published' => '1',
	            	);

	            	$like  = array('start_val'=>$start_val, 'end_val'=>$end_val);
	            	$col_5 = 'id';
		    		$res_5 = $this->loyalty_model->getProductLoyaltyMerge($whr_5, '', '', 'row', $like, '', '', '', 'COUNT(id) AS count');

		    		if(zero_check($res_5->count) == 0)
		    		{
		    			$loyalty_data = array(
				    		'state_id'     => zero_check($state_id),
				    		'city_id'      => zero_check($city_id), 
				    		'beat_id'      => zero_check($zone_id), 
				    		'outlet_id'    => zero_check($outlet_id),
				    		'vendor_id'    => zero_check($vendor_id),
				    		'start_date'   => date('Y-m-d', strtotime($start_date)),
				    		'end_date'     => date('Y-m-d', strtotime($end_date)),
				    		'loyalty_date' => $loyalty_date,
				    		'category_val' => $category_val,
				    		'description'  => $description,
				    		'date'         => date('Y-m-d'),
				    		'updatedate'   => date('Y-m-d H:i:s'),
				    	);

				    	$upt_id = array('id' => $loyalty_id);
						$update = $this->loyalty_model->productLoyalty_update($loyalty_data, $upt_id);

						$log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_product_loyalty',
							'auto_id'    => $loyalty_id,
							'action'     => 'update',
							'date'       => date('Y-m-d'),
							'time'       => date('H:i:s'),
							'createdate' => date('Y-m-d H:i:s')
						);

						$log_val = $this->commom_model->log_insert($log_data);

				    	$price_result = json_decode($price_value);

				    	foreach ($price_result as $key => $val) {

				    		$auto_id       = zero_check($val->auto_id);
				    		$category_id   = zero_check($val->category_id);
						    $product_id    = zero_check($val->product_id);
						    $type_id       = zero_check($val->type_id);
						    $product_price = zero_check($val->product_price);

						    $loyaltyDet_data = array(
						    	'pdt_loyalty_id' => $loyalty_id,
						    	'state_id'       => zero_check($state_id),
					    		'city_id'        => zero_check($city_id), 
					    		'beat_id'        => zero_check($zone_id), 
					    		'outlet_id'      => zero_check($outlet_id),
					    		'vendor_id'      => zero_check($vendor_id),
					    		'start_date'     => date('Y-m-d', strtotime($start_date)),
					    		'end_date'       => date('Y-m-d', strtotime($end_date)),
					    		'loyalty_date'   => $loyalty_date,
					    		'description'    => $description,
					    		'category_id'    => zero_check($category_id),
					    		'product_id'     => zero_check($product_id),
					    		'type_id'        => zero_check($type_id),
					    		'product_price'  => zero_check($product_price),
				    			'updatedate'     => date('Y-m-d H:i:s'),
						    );

						    $upt_id  = array('id' => $auto_id);
							$pdt_upt = $this->loyalty_model->productLoyaltyDetails_update($loyaltyDet_data, $upt_id);
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

			else if($method == '_deleteProductLoyalty')
			{
				if($loyalty_id)
				{	
					$val_1 = array('published' => '0');
					
		    		$whr_1 = array('id' => $loyalty_id);
				    $upt_1 = $this->loyalty_model->productLoyalty_delete($val_1, $whr_1);

				    $whr_2 = array('pdt_loyalty_id' => $loyalty_id);
				    $upt_2 = $this->loyalty_model->productLoyaltyDetails_delete($val_1, $whr_2);

				    $log_data = array(
						'u_id'       => $log_id,
						'role'       => $log_role,
						'table'      => 'tbl_product_loyalty',
						'auto_id'    => $loyalty_id,
						'action'     => 'delete',
						'date'       => date('Y-m-d'),
						'time'       => date('H:i:s'),
						'createdate' => date('Y-m-d H:i:s')
					);

					$log_val = $this->commom_model->log_insert($log_data);

				    if($upt_1)
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
			        $response['error']   = []; 
			        echo json_encode($response);
			        return;
				}
			}	

			else if($method == '_deleteSingleLoyalty')
			{
				if($auto_id)
				{	
					$val_1 = array('published' => '0');
				    $whr_1 = array('id' => $auto_id);
				    $upt_1 = $this->loyalty_model->productLoyaltyDetails_delete($val_1, $whr_1);

				    $log_data = array(
						'u_id'       => $log_id,
						'role'       => $log_role,
						'table'      => 'tbl_product_loyalty',
						'auto_id'    => $auto_id,
						'action'     => 'delete_single',
						'date'       => date('Y-m-d'),
						'time'       => date('H:i:s'),
						'createdate' => date('Y-m-d H:i:s')
					);

					$log_val = $this->commom_model->log_insert($log_data);

				    if($upt_1)
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
			        $response['error']   = []; 
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

		// store_loyalty
		// ***************************************************
		public function store_loyalty($param1="",$param2="",$param3="")
		{
			$error     = FALSE;
			$method    = $this->input->post('method');
			$outlet_id = $this->input->post('outlet_id');
			$limit     = $this->input->post('limit');
	    	$offset    = $this->input->post('offset');

			if($method == '_outletLoyalty')
			{
				if($outlet_id)
				{
					$where  = array(
						'outlet_id' => $outlet_id,
						'status'    => '1',
						'published' => '1',
					);

					$column    = 'id, inv_count, dis_value, status, date';
					$data_res = $this->loyalty_model->getOutletLoyalty($where, '', '', 'result', '', '', '', '', $column);

					if($data_res)
					{
						$data_list = [];

						foreach ($data_res as $key => $val) {
							$data_list[] = array(
								'id'        => zero_check($val->id),
					            'inv_count' => zero_check($val->inv_count),
					            'dis_value' => zero_check($val->dis_value),
					            'date'      => date_check($val->date),
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
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}

			else if($method == '_productLoyalty')
			{
				if($outlet_id)
				{
					$str_whr = array(
		    			'id'        => $outlet_id, 
		    			'status'    => '1', 
		    			'published' => '1'
		    		);

		    		$str_col = 'state_id, city_id, zone_id';

		    		$str_res = $this->outlets_model->getOutlets($str_whr, '', '', 'row', '', '', '', '', $str_col);

		    		$state_id = empty_check($str_res->state_id);
					$city_id  = empty_check($str_res->city_id);
					$zone_id  = empty_check($str_res->zone_id);

					$product_list = [];

					$whr_1 = array(
	            		'A.state_id'    => 0,
	            		'A.city_id'     => 0,
	            		'A.beat_id'     => 0,
	            		'A.outlet_id'   => 0,
	            		'A.end_date >=' => date('Y-m-d'),
	            		'A.published'   => '1',
	            	);

	            	$col_1 = 'B.description, A.product_price';
        			$res_1 = $this->loyalty_model->getProductLoyaltyDetailsJoin($whr_1, '', '', 'result', '', '', '', '', $col_1);

        			if($res_1)
        			{
        				foreach ($res_1 as $key => $val_1) {
	            			$description   = empty_check($val_1->description);
							$product_price = empty_check($val_1->product_price);

							$overall_wise = array(
				        		'description'   => $description,
				        		'product_price' => $product_price,
				        	);

				        	array_push($product_list, $overall_wise);
	            		}
        			}

					if($outlet_id != 0)
					{
						$whr_2 = array(
		            		'A.state_id'    => $state_id,
		            		'A.city_id'     => $city_id,
		            		'A.beat_id'     => $zone_id,
		            		'A.outlet_id'   => $outlet_id,
		            		'A.end_date >=' => date('Y-m-d'),
		            		'A.published'   => '1',
		            	);

		            	$col_2 = 'B.description, A.product_price';
		            	$res_2 = $this->loyalty_model->getProductLoyaltyDetailsJoin($whr_2, '', '', 'result', '', '', '', '', $col_2);

		            	if($res_2)
		            	{
		            		foreach ($res_2 as $key => $val_2) {
		            			$description   = empty_check($val_2->description);
								$product_price = empty_check($val_2->product_price);

								$outlet_wise = array(
					        		'description'   => $description,
					        		'product_price' => $product_price,
					        	);

					        	array_push($product_list, $outlet_wise);
		            		}
		            	}
					}

					if($zone_id != 0)
            		{
            			$whr_3 = array(
		            		'A.state_id'    => $state_id,
		            		'A.city_id'     => $city_id,
		            		'A.beat_id'     => $zone_id,
		            		'A.outlet_id'   => 0,
		            		'A.end_date >=' => date('Y-m-d'),
		            		'A.published'   => '1',
		            	);	

		            	$col_3 = 'B.description, A.product_price';
            			$res_3 = $this->loyalty_model->getProductLoyaltyDetailsJoin($whr_3, '', '', 'result', '', '', '', '', $col_3);

            			if($res_3)
            			{
            				foreach ($res_3 as $key => $val_3) {
		            			$description   = empty_check($val_3->description);
								$product_price = empty_check($val_3->product_price);

								$beat_wise = array(
					        		'description'   => $description,
					        		'product_price' => $product_price,
					        	);

					        	array_push($product_list, $beat_wise);
		            		}
            			}
            		}

            		if($city_id != 0)
            		{
            			$whr_4 = array(
		            		'A.state_id'    => $state_id,
		            		'A.city_id'     => $city_id,
		            		'A.beat_id'     => 0,
		            		'A.outlet_id'   => 0,
		            		'A.end_date >=' => date('Y-m-d'),
		            		'A.published'   => '1',
		            	);

		            	$col_4 = 'B.description, A.product_price';
            			$res_4 = $this->loyalty_model->getProductLoyaltyDetailsJoin($whr_4, '', '', 'result', '', '', '', '', $col_4);

            			if($res_4)
            			{
            				foreach ($res_4 as $key => $val_4) {
		            			$description   = empty_check($val_4->description);
								$product_price = empty_check($val_4->product_price);

								$city_wise = array(
					        		'description'   => $description,
					        		'product_price' => $product_price,
					        	);

					        	array_push($product_list, $city_wise);
		            		}
            			}
            		}

            		if($state_id != 0)
            		{
            			$whr_5 = array(
		            		'A.state_id'    => $state_id,
		            		'A.city_id'     => 0,
		            		'A.beat_id'     => 0,
		            		'A.outlet_id'   => 0,
		            		'A.end_date >=' => date('Y-m-d'),
		            		'A.published'   => '1',
		            	);

		            	$col_5 = 'B.description, A.product_price';
            			$res_5 = $this->loyalty_model->getProductLoyaltyDetailsJoin($whr_5, '', '', 'result', '', '', '', '', $col_5);

            			if($res_5)
            			{
            				foreach ($res_5 as $key => $val_5) {
		            			$description   = empty_check($val_5->description);
								$product_price = empty_check($val_5->product_price);

								$state_wise = array(
					        		'description'   => $description,
					        		'product_price' => $product_price,
					        	);

					        	array_push($product_list, $state_wise);
		            		}
            			}
            		}

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $product_list;
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
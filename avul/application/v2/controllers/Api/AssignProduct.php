<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class AssignProduct extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('assignproduct_model');
			$this->load->model('distributors_model');
			$this->load->model('commom_model');
			$this->load->model('pricemaster_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Add Assign Product
		// ***************************************************
		public function add_assign_product($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addAssignProduct')
			{
				$distributor_id = $this->input->post('distributor_id');
				$zone_id        = $this->input->post('zone_id');
				$category_id    = $this->input->post('category_id');
				$type_id        = $this->input->post('type_id');
				$minimum_stock  = $this->input->post('minimum_stock');
				$minimum_order  = $this->input->post('minimum_order');
				$ref_id        = $this->input->post('ref_id');

				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id', 'zone_id', 'category_id', 'type_id');
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
			    		'distributor_id' => $distributor_id,
			    		'zone_id'        => $zone_id,
			    		'category_id'    => $category_id,
				    	'published'      => '1',
			    	);

			    	$column_1 = 'id';

					$overalldatas = $this->assignproduct_model->getAssignproduct($where_1, '', '', 'result', '', '', '', '', $column_1);

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
						$column_2 = 'company_name';

				    	$where_2  = array(
				    		'id'        => $distributor_id,
			    			'status'    => '1', 
							'published' => '1'
				    	);

				    	$distri_whr   = $this->distributors_model->getDistributors($where_2, '', '', 'result', '', '', '', '', $column_2);

				    	$company_name = !empty($distri_whr[0]->company_name)?$distri_whr[0]->company_name:'';

						$data_1 = array(
				    		'distributor_id'   => $distributor_id,
				    		'distributor_name' => $company_name,
				    		'zone_id'          => $zone_id,
				    		'category_id'      => $category_id,
				    		'createdate'       => date('Y-m-d H:i:s'),
				    	);

				    	// Assign Product
				    	$insert_1 = $this->assignproduct_model->assignproduct_insert($data_1);

						$type_list = '';
				    	if(!empty($type_id))
				    	{
				    		$type_val    = explode(',', $type_id);
							$type_count  = count($type_val);

							for ($i=0; $i < $type_count; $i++) {

								// Product Details
						    	$where_3  = array(
						    		'id'        => $type_val[$i],
					    			'status'    => '1', 
									'published' => '1'
						    	);

								$product_detail = $this->commom_model->getProductType($where_3);

								$product_id   = !empty($product_detail[0]->product_id)?$product_detail[0]->product_id:'';
								$description  = !empty($product_detail[0]->description)?$product_detail[0]->description:'';
								$product_unit = !empty($product_detail[0]->product_unit)?$product_detail[0]->product_unit:'';

								// Product Check
						 		$where_4 = array(
						 			'assign_id !='      => $insert_1,
						 			'distributor_id !=' => $distributor_id,
						 			'zone_id'           => $zone_id,
						 			'category_id'       => $category_id,
						 			'product_id'        => $product_id,
						 			'type_id'           => $type_val[$i],
						 			'minimum_stock'     => $minimum_stock,
						 			'minimum_order'     => $minimum_order,
						 			'status'            => '1', 
									'published'         => '1',
						 		);

						 		$type_whr = $this->assignproduct_model->getAssignProductDetails($where_4);

						 		if(empty($type_whr))
						 		{
						 			$type_list .= $type_val[$i].',';

						 			$data_2 = array(
						    			'assign_id'      => $insert_1,
						    			'distributor_id' => $distributor_id,
						    			'zone_id'        => $zone_id,
						    			'category_id'    => $category_id,
						    			'product_id'     => $product_id,
						    			'type_id'        => $type_val[$i],
						    			'description'    => $description,
						    			'product_unit'   => $product_unit,
						    			'stock'          => '0',
						    			'minimum_stock'  => $minimum_stock,
						    			'minimum_order'  => $minimum_order,
						    			'createdate'     => date('Y-m-d H:i:s'),
						    		);

						    		// Assign Product Details
						    		$insert_2 = $this->assignproduct_model->assignProductDetails_insert($data_2);
						 		}
							}
						}

				    	$type_details = substr_replace($type_list, '', -1);

				    	// Distributor wise product table update
				    	$data_1 = array(
				    		'type_id'    => $type_details,
				    		'updatedate' => date('Y-m-d H:i:s'),
				    	);

				    	$update_id = array('id' => $insert_1);
					    $update    = $this->assignproduct_model->assignproduct_update($data_1, $update_id);

					    if($insert_1)
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

			else if($method == '_updateAssignProduct')
			{	
				$ref_id        = $this->input->post('ref_id');
				$assign_id      = $this->input->post('assign_id');
				$distributor_id = $this->input->post('distributor_id');
				$state_id       = $this->input->post('state_id');
				$city_id        = $this->input->post('city_id');
				$zone_id        = $this->input->post('zone_id');
				$type_id        = $this->input->post('type_id');
				$minimum_stock  = $this->input->post('minimum_stock');
				$minimum_order  = $this->input->post('minimum_order');
				$status         = $this->input->post('status');

				$error = FALSE;
			    $errors = array();
				$required = array('assign_id', 'distributor_id', 'state_id', 'city_id', 'zone_id');
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
			    {   $dist_zone_id=',';
			    	$zone_list = ',';
					if($ref_id==0){
						
					 if(!empty($zone_id))
					 { 
							if($ref_id==0){
								$zone_val   = explode(',', $zone_id);
								$zone_count = count($zone_val);
							
								for ($i=0; $i < $zone_count; $i++) {
		
									// Beat Details
									$where_3  = array(
										'distributor_id !=' => $distributor_id,
										'ref_id'         => $ref_id,
										'state_id'       => $state_id,
										'type_id'        => $type_id,
										'status'         => '1', 
										'published'      => '1'
									);
		
									$like['zone_id'] = ','.$zone_val[$i].',';
		
									//$column = 'zone_id';
									$column = 'id';
		
									$assign_data = $this->assignproduct_model->getAssignProductAddtionalDetails($where_3, '', '', 'result', $like, '', '', '', $column);
		
									if(empty($assign_data))
									{
										$zone_list .= $zone_val[$i].',';
									}
								}
					
							
				            }
						
						 // Distributor wise beat table update
						$data_5 = array(
							'state_id'      => $state_id,
							'city_id'       => $city_id,
							'zone_id'       => $zone_list,
							'minimum_stock' => $minimum_stock,
							'minimum_order' => $minimum_order,
							'updatedate'    => date('Y-m-d H:i:s'),
							'ref_id'        => 0
						);
	
						$update_id = array('id' => $assign_id);
						$update    = $this->assignproduct_model->assignProductDetails_update($data_5, $update_id);
					 }
					 
						
	
					
					}else{
						
						$zone_val   = explode(',', $zone_id);

						$zone_count = count($zone_val);
					
						
								for ($i=0; $i < $zone_count; $i++) {
		
									// Beat Details
									$where_3  = array(
										'distributor_id !=' => $distributor_id,
										'ref_id'         => $ref_id,
										'state_id'       => $state_id,
										'type_id'        => $type_id,
										'status'         => '1', 
										'published'      => '1'
									);
		
									$like['zone_id'] = ','.$zone_val[$i].',';
		
									//$column = 'zone_id';
									$column = 'id';
		
									$assign_data = $this->assignproduct_model->getAssignProductAddtionalDetails($where_3, '', '', 'result', $like, '', '', '', $column);
		
									if(empty($assign_data))
									{
										$zone_list .= $zone_val[$i].',';
									}
								}
					
						// $d_whr= array(
						// 	'distributor_id' => $ref_id,
						// 	'type_id'        => $type_id,
						// );
						// print_r($d_whr);exit;
						
						// $d_data  = $this->assignproduct_model->getAssignSubProductDetails($d_whr, '', '', 'result', '', '', '', '', '');
						// $zone_id_finall=substr($d_data[0]->zone_id,1);
						// $d_zone=!empty($zone_id_finall)?$zone_id_finall:'';
						
						// $d_zone_val = explode(',', $d_zone);
						
						
						// $dis_zone =array();
						// $sub_zone=array();
						
						// foreach($d_zone_val as $value){
						
						// 	if(in_array($value,$zone_val )){
						// 	  array_push($sub_zone,$value);
						
						// 	}else{
						// 	  array_push($dis_zone,$value);
						// 	};
						// }
						
						
						// $zone_list  = implode(",",$sub_zone);
						// $dist_zone_id = implode(",",$dis_zone);
						// $update_d_zone= ",,".$dist_zone_id;
						// $insert_zone= ",".$zone_list;

					
						$data_5 = array(
							'state_id'      => $state_id,
							'city_id'       => $city_id,
							'zone_id'       => $zone_list,
							'minimum_stock' => $minimum_stock,
							'minimum_order' => $minimum_order,
							'updatedate'    => date('Y-m-d H:i:s'),
							'ref_id'        => $ref_id
						);
	
						$update_id = array('id' => $assign_id);
						$update    = $this->assignproduct_model->assignProductDetails_update($data_5, $update_id);	
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

			else
			{
				$response['status']  = 0;
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// List Assign Product
		// ***************************************************
		public function list_assign_product($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listAssignProductPaginate')
			{
				$limit  = $this->input->post('limit');
	    		$offset = $this->input->post('offset');
	    		$dis_id = $this->input->post('distributor_id');

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
	    				'distributor_id' => $dis_id,
	    				'published'      => '1'
	    			);
	    		}
	    		else
	    		{
	    			$like = [];
	    			$where = array(
	    				'distributor_id' => $dis_id,
	    				'published'      => '1'
	    			);
	    		}

	    		$column = 'id';
				$overalldatas = $this->assignproduct_model->getAssignProductDetails($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->assignproduct_model->getAssignProductDetails($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$assign_list = [];
					foreach ($data_list as $key => $value) {

						$assign_id      = !empty($value->id)?$value->id:'';
			            $zone_id        = !empty($value->zone_id)?$value->zone_id:'';
			            $distributor_id = !empty($value->distributor_id)?$value->distributor_id:'';
			            $category_id    = !empty($value->category_id)?$value->category_id:'';
			            $description    = !empty($value->description)?$value->description:'';
			            $stock          = !empty($value->stock)?$value->stock:'0';
			            $view_stock     = !empty($value->view_stock)?$value->view_stock:'0';
			            $status         = !empty($value->status)?$value->status:'';
			            $createdate     = !empty($value->createdate)?$value->createdate:'';

					    // Category Details
					    $where_1       = array('id' => $category_id);
				    	$category_val  = $this->commom_model->getCategory($where_1);
				    	$category_name = isset($category_val[0]->name)?$category_val[0]->name:'';

				    	// Distributor Details
					    $where_2          = array('id' => $distributor_id);
				    	$distributor_val  = $this->distributors_model->getDistributors($where_2);
				    	$distributor_name = isset($distributor_val[0]->company_name)?$distributor_val[0]->company_name:'';

				    	// Beat Assign Details
				    	$zone_status = '2';
				    	if(!empty($zone_id))
				    	{
				    		$zone_status = '1';
				    	}

				    	$assign_list[] = array(
							'assign_id'        => $assign_id,
							'distributor_id'   => $distributor_id,
							'distributor_name' => $distributor_name,
							'category_id'      => $category_id,
							'category_name'    => $category_name,
							'description'      => $description,
							'stock'            => $stock,
							'zone_status'      => $zone_status,
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
			        $response['data']         = $assign_list;
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

			else if($method == '_detailAssignProduct')
			{
				$assign_id = $this->input->post('assign_id');

				if(!empty($assign_id))
				{	
					$where_1 = array('id' => $assign_id);
				    $data    = $this->assignproduct_model->getAssignProductDetails($where_1);
				    if($data)
				    {	
				    	$assign_details = array(
				    		'assign_id'      => !empty($data[0]->id)?$data[0]->id:'',
				            'distributor_id' => !empty($data[0]->distributor_id)?$data[0]->distributor_id:'',
				            'state_id'       => !empty($data[0]->state_id)?$data[0]->state_id:'',
				            'city_id'        => !empty($data[0]->city_id)?$data[0]->city_id:'',
				            'zone_id'        => !empty($data[0]->zone_id)?$data[0]->zone_id:'',
				            'type_id'        => !empty($data[0]->type_id)?$data[0]->type_id:'',
				            'description'    => !empty($data[0]->description)?$data[0]->description:'',
				            'minimum_stock'  => !empty($data[0]->minimum_stock)?$data[0]->minimum_stock:'',
				            'minimum_order'  => !empty($data[0]->minimum_order)?$data[0]->minimum_order:'',
				            'status'         => !empty($data[0]->status)?$data[0]->status:'',
				            'createdate'     => !empty($data[0]->createdate)?$data[0]->createdate:'',
				    	);

				    	$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $assign_details;
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
			else if($method == '_detailAssignProductZone')
			{
				
				$type_id = $this->input->post('type_id');
				$ref_id = $this->input->post('ref_id');
				$city_id = $this->input->post('city_id');
			
				        $where_2 = array(
			     			'city_id' => $city_id,
						);
						
						$col='id';
			     		$data_1    = $this->commom_model->getZone($where_2, '', '', 'result', '', '', '','', $col,);

						$city_zone=[];
						 foreach($data_1 as $key => $value){
                        
						   array_push($city_zone,$value->id);
						 };

						
						$where_1 = array(
							'distributor_id' => $ref_id,
							'type_id'        => $type_id
						);
				    	$data    = $this->assignproduct_model->getAssignProductDetails($where_1);
					
				    	$zone_id_finall=substr($data[0]->zone_id,1);
						$d_zone=!empty($zone_id_finall)?$zone_id_finall:'';
						
						$d_zone_val = explode(',', $d_zone);
						
						$main_zone = array();
						$not_zone = array();
						foreach($city_zone as $value){
							
						
							if(in_array($value,$d_zone_val )){
							  array_push($main_zone,$value);
						
							}else{
							  array_push($not_zone,$value);
							};
						}
					
                           if(!empty($main_zone)){
							$ver =array(
								'published' => 1
							);
							
							$were['id'] = $main_zone;
							
						

							$col_1='id,name';
							$data_2   = $this->commom_model->getZone($ver, "", "", "result", "", "", "", "",$col_1,$were,);
						
							$response['status']  = 1;
				  		    $response['message'] = "Success"; 
				  		    $response['data']    = $data_2;
				  		    echo json_encode($response);
				  		    return;
						   }else{
							$response['status']  = 0;
			                $response['message'] = "Not Found"; 
			                $response['data']    = [];
			                echo json_encode($response);
			                return;
						   }
							
				  		
				
				
			}
			else if($method == '_distributorAssignProductPaginate')
			{
				$distributor_id = $this->input->post('distributor_id');

				if(!empty($distributor_id))
				{
					$limit       = $this->input->post('limit');
		    		$offset      = $this->input->post('offset');

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
			    		'distributor_id' => $distributor_id,
			    		'status'         => '1', 
						'published'      => '1'
			    	);

			    	$column = 'id';
					$overalldatas = $this->assignproduct_model->getAssignProductDetails($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->assignproduct_model->getAssignProductDetails($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$assign_list = [];
						foreach ($data_list as $key => $value) {
							$assproduct_id  = !empty($value->id)?$value->id:'';
							$assign_id      = !empty($value->assign_id)?$value->assign_id:'';
							$distributor_id = !empty($value->distributor_id)?$value->distributor_id:'';
							$category_id    = !empty($value->category_id)?$value->category_id:'';
							$product_id     = !empty($value->product_id)?$value->product_id:'';
							$type_id        = !empty($value->type_id)?$value->type_id:'';
							$description    = !empty($value->description)?$value->description:'';
							$stock          = !empty($value->stock)?$value->stock:'0';
							$published      = !empty($value->published)?$value->published:'';
							$status         = !empty($value->status)?$value->status:'';
							$createdate     = !empty($value->createdate)?$value->createdate:'';

							// Product Details
							$pdt_whr = array(
								'id'        => $product_id,
								'status'    => '1', 
								'published' => '1'
							);

							$pdt_col = 'hsn_code, gst';

							$pdt_data  = $this->commom_model->getProduct($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);

							$hsn_code = !empty($pdt_data[0]->hsn_code)?$pdt_data[0]->hsn_code:'---';
							$gst_val  = !empty($pdt_data[0]->gst)?$pdt_data[0]->gst:'---';

							$assign_list[] = array(
								'assproduct_id'  => $assproduct_id,
								'assign_id'      => $assign_id,
								'distributor_id' => $distributor_id,
								'category_id'    => $category_id,
								'product_id'     => $product_id,
								'type_id'        => $type_id,
								'description'    => $description,
								'hsn_code'       => $hsn_code,
								'gst_val'        => $gst_val,
								'stock'          => $stock,
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
				        $response['data']         = $assign_list;
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

			else if($method == '_listDistributorAssignProduct')
			{
				$distributor_id = $this->input->post('distributor_id');
				$vendor_id      = $this->input->post('vendor_id');

				if(!empty($distributor_id))
				{
					$where = array(
			    		'distributor_id' => $distributor_id,
			    		'status'         => '1', 
						'published'      => '1'
			    	);

			    	$data_list = $this->assignproduct_model->getAssignProductDetails($where);

					if($data_list)
					{
						$assign_list = [];
						foreach ($data_list as $key => $value) {
							$assproduct_id  = !empty($value->id)?$value->id:'';
							$assign_id      = !empty($value->assign_id)?$value->assign_id:'';
							$distributor_id = !empty($value->distributor_id)?$value->distributor_id:'';
							$category_id    = !empty($value->category_id)?$value->category_id:'';
							$product_id     = !empty($value->product_id)?$value->product_id:'';
							$type_id        = !empty($value->type_id)?$value->type_id:'';
							$description    = !empty($value->description)?$value->description:'';
							$stock          = !empty($value->stock)?$value->stock:'0';
							$published      = !empty($value->published)?$value->published:'';
							$status         = !empty($value->status)?$value->status:'';
							$createdate     = !empty($value->createdate)?$value->createdate:'';

							// Product Details
							$pdt_whr = array(
								'id'        => $product_id,
								'status'    => '1', 
								'published' => '1'
							);

							$pdt_col = 'hsn_code, gst';

							$pdt_data  = $this->commom_model->getProduct($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);

							$hsn_code = !empty($pdt_data[0]->hsn_code)?$pdt_data[0]->hsn_code:'---';
							$gst_val  = !empty($pdt_data[0]->gst)?$pdt_data[0]->gst:'---';

							// Product Type Details
							$type_whr = array(
								'id'        => $type_id,
								'vendor_id' => $vendor_id,
								'published' => '1',
							);

							$type_val = $this->commom_model->getProductType($type_whr,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

							$type_count = !empty($type_val[0]->autoid)?$type_val[0]->autoid:'0';

							if($type_count == 0)
							{
								$assign_list[] = array(
									'assproduct_id'  => $assproduct_id,
									'assign_id'      => $assign_id,
									'distributor_id' => $distributor_id,
									'category_id'    => $category_id,
									'product_id'     => $product_id,
									'type_id'        => $type_id,
									'description'    => $description,
									'hsn_code'       => $hsn_code,
									'gst_val'        => $gst_val,
									'stock'          => $stock,
									'published'      => $published,
									'status'         => $status,
									'createdate'     => $createdate,
								);
							}
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $assign_list;
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

		

			else if($method == '_listDistributorAssignCategory')
			{
				$distributor_id = $this->input->post('distributor_id');

				if(!empty($distributor_id))
				{
					$where_1  = array('id' => $distributor_id, 'published' => '1');	
					$column_1 = 'id, category_id';
					$dis_data = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column_1);

					if($dis_data)
					{
						$category_id  = !empty($dis_data[0]->category_id)?$dis_data[0]->category_id:'';
						
						$where_2  = array('category_id' => $category_id, 'published' => '1');	
						$column_2 = 'id, name';
						$cat_data = $this->commom_model->getCategoryImplode($where_2, '', '', 'result', '', '', '', '', $column_2);

						if($cat_data)
						{
							$category_list = [];
							foreach ($cat_data as $key => $val_2) {
								$cat_id   = !empty($val_2->id)?$val_2->id:'';
            					$cat_name = !empty($val_2->name)?$val_2->name:'';

            					$category_list[] = array(
            						'category_id'   => $cat_id,
            						'category_name' => $cat_name,
            					);
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

			else if($method == '_listDistributorAssignInventroyProduct')
			{
				$distributor_id = $this->input->post('distributor_id');
				$category_id    = $this->input->post('category_id');
				$vendor_id      = $this->input->post('vendor_id');

				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id', 'vendor_id', 'category_id');
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
			    	$where_1  = array(
			    		'distributor_id' => $distributor_id,
			    		'category_id'    => $category_id,
			    		'published'      => '1',
			    	);

			    	$column_1 = 'id, type_id, description';

			    	$ass_data = $this->assignproduct_model->getAssignProductDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

			    	if($ass_data)
			    	{
			    		$assign_list = [];
			    		foreach ($ass_data as $key => $val_1) {

			    			$assign_id   = !empty($val_1->id)?$val_1->id:'';
			    			$type_id     = !empty($val_1->type_id)?$val_1->type_id:'';
            				$description = !empty($val_1->description)?$val_1->description:'';

            				// Product Type Details
							$type_whr = array(
								'id'        => $type_id,
								'vendor_id' => $vendor_id,
								'published' => '1',
							);

							$type_val = $this->commom_model->getProductType($type_whr,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

							$type_count = !empty($type_val[0]->autoid)?$type_val[0]->autoid:'0';

							if($type_count != 0)
							{
								$assign_list[] = array(
	            					'assign_id'   => $assign_id,
	            					'description' => $description,
	            				);
							}
			    		}

			    		$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $assign_list;
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

			else if($method == '_listDistributorAssignCategoryProduct')
			{
				$distributor_id = $this->input->post('distributor_id');
				$category_id    = $this->input->post('category_id');
				$sub_cat_id    = $this->input->post('sub_cat_id');
				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id', 'category_id');
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
					if(empty($sub_cat_id)){
						$where_1  = array(
							'distributor_id' => $distributor_id,
							'category_id'    => $category_id,
							'published'      => '1',
						);
					}else{
						$where_1  = array(
							'distributor_id' => $distributor_id,
							'category_id'    => $category_id,
							'sub_cat_id'     => $sub_cat_id,
							'published'      => '1',
						);
					}
			    	

			    	$column_1 = 'id, description';

			    	$ass_data = $this->assignproduct_model->getAssignProductDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

			    	if($ass_data)
			    	{
			    		$assign_list = [];
			    		foreach ($ass_data as $key => $val_1) {

			    			$assign_id   = !empty($val_1->id)?$val_1->id:'';
            				$description = !empty($val_1->description)?$val_1->description:'';

            				$assign_list[] = array(
            					'assign_id'   => $assign_id,
            					'description' => $description,
            				);
			    		}

			    		$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $assign_list;
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

			else if($method == '_detailsDistributorAssignProduct_Dc')
			{
				$distributor_id = $this->input->post('distributor_id');
				$assproduct_id  = $this->input->post('assproduct_id');

				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id', 'assproduct_id');
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
						'id'        => $assproduct_id,
			    		'status'    => '1', 
						'published' => '1'
					);

					$data_list = $this->assignproduct_model->getAssignProductDetails($where);

					if($data_list)
					{
						$data_val = $data_list[0];

						$assproduct_id = !empty($data_val->id)?$data_val->id:'';
						$category_id   = !empty($data_val->category_id)?$data_val->category_id:'';
						$product_id    = !empty($data_val->product_id)?$data_val->product_id:'';
						$type_id       = !empty($data_val->type_id)?$data_val->type_id:'';
						$product_unit  = !empty($data_val->product_unit)?$data_val->product_unit:'';
						$minimum_order = !empty($data_val->minimum_order)?$data_val->minimum_order:'0';

						// Unit Name
				    	$where_1       = array('id' => $product_unit);
				    	$unit_val      = $this->commom_model->getUnit($where_1);
				    	$unit_name     = isset($unit_val[0]->name)?$unit_val[0]->name:'';

				    	// General Price Details
				    	$where_2       = array('id' => $type_id);
				    	$product_val   = $this->commom_model->getProductType($where_2);
				    	$dis_price     = isset($product_val[0]->dis_price)?$product_val[0]->dis_price:'0';

				    	// Distributor Price Details
				    	// $where_3  = array(
				    	// 	'distributor_id' => $distributor_id,
				    	// 	'category_id'    => $category_id,
				    	// 	'product_id'     => $product_id,
				    	// 	'type_id'        => $type_id,
				    	// 	'published'      => '1',
				    	// 	'status'         => '1',
				    	// );

				    	// $option['order_by']   = 'id';
						// $option['disp_order'] = 'DESC';

						// $limit  = 1;
						// $offset = 0;

						// $column = 'product_price';

				    	// $price_val = $this->pricemaster_model->getDistributorPrice($where_3, $limit, $offset, 'result', '', '', $option, '', $column);

				    	// $pdt_price = isset($price_val[0]->product_price)?$price_val[0]->product_price:'0';

						$where_3  = array(
				    		
				    		'product_id'     => $product_id,
				    		'id'             => $type_id,
				    		'published'      => '1',
				    		'status'         => '1',
				    	);

				    	
						$limit =1;
					

						$column = 'ven_price';

				    	$price_val = $this->commom_model->getProductType($where_3, $limit, '', 'result', '', '','', '', $column);

				    	$pdt_price = isset($price_val[0]->ven_price)?$price_val[0]->ven_price:'0';

				    	if(!empty($pdt_price))
				    	{
				    		$distributor_price = $pdt_price;
				    	}
				    	else
				    	{
				    		$distributor_price = $dis_price;
				    	}

						$assign_list = array(
							'assproduct_id' => $assproduct_id,
							'category_id'   => $category_id,
							'product_id'    => $product_id,
							'type_id'       => $type_id,
							'product_unit'  => $product_unit,
							'unit_name'     => $unit_name,
							'product_price' => $distributor_price,
							'minimum_order' => $minimum_order,
						);

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $assign_list;
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

			else if($method == '_detailsDistributorAssignProduct')
			{
				$distributor_id = $this->input->post('distributor_id');
				$assproduct_id  = $this->input->post('assproduct_id');

				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id', 'assproduct_id');
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
						'id'        => $assproduct_id,
			    		'status'    => '1', 
						'published' => '1'
					);

					$data_list = $this->assignproduct_model->getAssignProductDetails($where);

					if($data_list)
					{
						$data_val = $data_list[0];

						$assproduct_id = !empty($data_val->id)?$data_val->id:'';
						$category_id   = !empty($data_val->category_id)?$data_val->category_id:'';
						$product_id    = !empty($data_val->product_id)?$data_val->product_id:'';
						$type_id       = !empty($data_val->type_id)?$data_val->type_id:'';
						$product_unit  = !empty($data_val->product_unit)?$data_val->product_unit:'';
						$minimum_order = !empty($data_val->minimum_order)?$data_val->minimum_order:'0';

						// Unit Name
				    	$where_1       = array('id' => $product_unit);
				    	$unit_val      = $this->commom_model->getUnit($where_1);
				    	$unit_name     = isset($unit_val[0]->name)?$unit_val[0]->name:'';

				    	// General Price Details
				    	$where_2       = array('id' => $type_id);
				    	$product_val   = $this->commom_model->getProductType($where_2);
				    	$dis_price     = isset($product_val[0]->dis_price)?$product_val[0]->dis_price:'0';

				    	// Distributor Price Details
				    	$where_3  = array(
				    		'distributor_id' => $distributor_id,
				    		'category_id'    => $category_id,
				    		'product_id'     => $product_id,
				    		'type_id'        => $type_id,
				    		'published'      => '1',
				    		'status'         => '1',
				    	);

				    	$option['order_by']   = 'id';
						$option['disp_order'] = 'DESC';

						$limit  = 1;
						$offset = 0;

						$column = 'product_price';

				    	$price_val = $this->pricemaster_model->getDistributorPrice($where_3, $limit, $offset, 'result', '', '', $option, '', $column);

				    	$pdt_price = isset($price_val[0]->product_price)?$price_val[0]->product_price:'0';

						

				    	if(!empty($pdt_price))
				    	{
				    		$distributor_price = $pdt_price;
				    	}
				    	else
				    	{
				    		$distributor_price = $dis_price;
				    	}

						$assign_list = array(
							'assproduct_id' => $assproduct_id,
							'category_id'   => $category_id,
							'product_id'    => $product_id,
							'type_id'       => $type_id,
							'product_unit'  => $product_unit,
							'unit_name'     => $unit_name,
							'product_price' => $distributor_price,
							'minimum_order' => $minimum_order,
						);

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $assign_list;
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
			else if($method == '_deleteAssignProduct')
			{
				$assign_id = $this->input->post('assign_id');

				if(!empty($assign_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

		    		// Assign Product Details
		    		$where_1  = array('id' => $assign_id);
		    		$update_1 = $this->assignproduct_model->assignProductDetails_delete($data, $where_1);

					// Delete Distributor Product List		    		
					// $where_3  = array(
			    	// 	'id'             => $assign_id,
			    	// 	'distributor_id' => $distributor_id,
		    		// 	'status'         => '1', 
					// 	'published'      => '1'
			    	// );

			    	// $like['zone_id'] = $zone_val[$i];

			    	// $column = 'id';

					// $assign_data = $this->assignproduct_model->getAssignProductDetails($where_3, '', '', 'result', $like, '', '', '', $column);

		    		// Assign Product Table
		    		// $where_2  = array('id' => $assign_id);
				    // $update_2 = $this->assignproduct_model->assignproduct_delete($data, $where_2);
				    if($update_1)
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

		// List Distributor Product
		// ***************************************************
		public function list_distributor_product($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listDistributorCategory')
			{
				$distributor_id = $this->input->post('distributor_id');

				$whr_1  = array(
					'id'        => $distributor_id,
					'published' => '1',
				);

				$col_1  = 'category_id';

				$data_1 = $this->distributors_model->getDistributors($whr_1, '', '', 'result', '', '', '', '', $col_1);

				if($data_1)
				{
					$category_id = !empty($data_1[0]->category_id)?$data_1[0]->category_id:'';

					$whr_2 = array(
						'category_id' => $category_id,
						'published'   => '1',
					);

					$col_2  = 'id, name';

					$data_2 = $this->commom_model->getCategoryImplode($whr_2, '', '', 'result', '', '', '', '', $col_2);

					if($data_2)
					{
						$category_list = [];
						foreach ($data_2 as $key => $val_2) {

							$cat_id   = !empty($val_2->id)?$val_2->id:'';
    						$cat_name = !empty($val_2->name)?$val_2->name:'';

    						$category_list[] = array(
    							'category_id'   => $cat_id,
    							'category_name' => $cat_name,
    						);
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
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Data Not Found"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}

			else if($method == '_listDistributorProduct')
			{
				$distributor_id = $this->input->post('distributor_id');
				$category_id    = $this->input->post('category_id');

				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id', 'category_id');
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
			    	$whr_1 = array(
			    		'distributor_id' => $distributor_id,
			    		'category_id'    => $category_id,
			    		'published'      => '1',
			    		'status'         => '1',
			    	);

			    	$col_1  = 'id, category_id, product_id, type_id, description, product_unit';

			    	$data_1 = $this->assignproduct_model->getAssignProductDetails($whr_1, '', '', 'result', '', '', '', '', $col_1);

			    	if($data_1)
			    	{
			    		$product_list = [];
			    		foreach ($data_1 as $key => $val_1) {
			    			$assign_id    = !empty($val_1->id)?$val_1->id:'';
							$category_id  = !empty($val_1->category_id)?$val_1->category_id:'';
							$product_id   = !empty($val_1->product_id)?$val_1->product_id:'';
							$type_id      = !empty($val_1->type_id)?$val_1->type_id:'';
							$description  = !empty($val_1->description)?$val_1->description:'';
							$product_unit = !empty($val_1->product_unit)?$val_1->product_unit:'';

							$product_list[] = array(
								'assign_id'    => $assign_id,
								'category_id'  => $category_id,
								'product_id'   => $product_id,
								'type_id'      => $type_id,
								'description'  => $description,
								'product_unit' => $product_unit,
							);
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
				        $response['message'] = "Data Not Found"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
					}
			    }
			}

			else if($method == '_distributorProductDetails')
			{
				$assign_id = $this->input->post('assign_id');

				if($assign_id != '')
				{
					$whr_1 = array(
			    		'id'        => $assign_id,
			    		'published' => '1',
			    		'status'    => '1',
			    	);

			    	$col_1  = 'id, category_id, product_id, type_id, description, product_unit';

			    	$data_1 = $this->assignproduct_model->getAssignProductDetails($whr_1, '', '', 'result', '', '', '', '', $col_1);

			    	if($data_1)
			    	{
			    		$product_list = [];
			    		foreach ($data_1 as $key => $val_1) {
			    			$assign_id      = !empty($val_1->id)?$val_1->id:'';
			    			$distributor_id = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
							$category_id    = !empty($val_1->category_id)?$val_1->category_id:'';
							$product_id     = !empty($val_1->product_id)?$val_1->product_id:'';
							$type_id        = !empty($val_1->type_id)?$val_1->type_id:'';
							$description    = !empty($val_1->description)?$val_1->description:'';
							$product_unit   = !empty($val_1->product_unit)?$val_1->product_unit:'';

							// Unit Details
							$where_2 = array(
								'id'        => $product_unit,
								'published' => '1',
							);

							$unit_det  = $this->commom_model->getUnit($where_2);
						    $unit_name = isset($unit_det[0]->name)?$unit_det[0]->name:'';

						    // General Price Details
					    	$where_3       = array('id' => $type_id);
					    	$product_val   = $this->commom_model->getProductType($where_3);
					    	$dis_price     = isset($product_val[0]->dis_price)?$product_val[0]->dis_price:'0';

					    	// Distributor Price Details
					    	$where_4  = array(
					    		'distributor_id' => $distributor_id,
					    		'category_id'    => $category_id,
					    		'product_id'     => $product_id,
					    		'type_id'        => $type_id,
					    		'published'      => '1',
					    		'status'         => '1',
					    	);

					    	$option['order_by']   = 'id';
							$option['disp_order'] = 'DESC';

							$limit  = 1;
							$offset = 0;

							$column = 'product_price';

					    	$price_val = $this->pricemaster_model->getDistributorPrice($where_4, $limit, $offset, 'result', '', '', $option, '', $column);

					    	$pdt_price = isset($price_val[0]->product_price)?$price_val[0]->product_price:'0';

					    	if(!empty($pdt_price))
					    	{
					    		$distributor_price = $pdt_price;
					    	}
					    	else
					    	{
					    		$distributor_price = $dis_price;
					    	}

							$product_list = array(
								'assign_id'     => $assign_id,
								'category_id'   => $category_id,
								'product_id'    => $product_id,
								'type_id'       => $type_id,
								'description'   => $description,
								'product_unit'  => $product_unit,
								'unit_name'     => $unit_name,
								'product_price' => $distributor_price,
							);
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

			else if($method == '_outletProductDetails')
			{
				$assign_id = $this->input->post('assign_id');

				if($assign_id != '')
				{
					$whr_1 = array(
			    		'id'        => $assign_id,
			    		'published' => '1',
			    		'status'    => '1',
			    	);

			    	$col_1  = 'id, category_id, product_id, type_id, description, product_unit';

			    	$data_1 = $this->assignproduct_model->getAssignProductDetails($whr_1, '', '', 'result', '', '', '', '', $col_1);

			    	if($data_1)
			    	{
			    		$product_list = [];
			    		foreach ($data_1 as $key => $val_1) {
			    			$assign_id      = !empty($val_1->id)?$val_1->id:'';
			    			$distributor_id = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
							$category_id    = !empty($val_1->category_id)?$val_1->category_id:'';
							$product_id     = !empty($val_1->product_id)?$val_1->product_id:'';
							$type_id        = !empty($val_1->type_id)?$val_1->type_id:'';
							$description    = !empty($val_1->description)?$val_1->description:'';
							$product_unit   = !empty($val_1->product_unit)?$val_1->product_unit:'';

							// Unit Details
							$where_2 = array(
								'id'        => $product_unit,
								'published' => '1',
							);

							$unit_det  = $this->commom_model->getUnit($where_2);
						    $unit_name = isset($unit_det[0]->name)?$unit_det[0]->name:'';

						    // General Price Details
					    	$where_3       = array('id' => $type_id);
					    	$product_val   = $this->commom_model->getProductType($where_3);
					    	$product_price = isset($product_val[0]->product_price)?$product_val[0]->product_price:'0';

							$product_list = array(
								'assign_id'     => $assign_id,
								'category_id'   => $category_id,
								'product_id'    => $product_id,
								'type_id'       => $type_id,
								'description'   => $description,
								'product_unit'  => $product_unit,
								'unit_name'     => $unit_name,
								'product_price' => $product_price,
							);
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

		// List Distributor Product
		// ***************************************************
		public function assign_multiple_beat($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_assignMultipleBeat')
			{
				$distributor_id = $this->input->post('distributor_id');
				$state_id       = $this->input->post('state_id');
				$city_id        = $this->input->post('city_id');
				$zone_id        = $this->input->post('zone_id');
				$category_id    = $this->input->post('category_id');
				$type_id        = $this->input->post('type_id');
				$minimum_stock  = $this->input->post('minimum_stock');
				$ref_id        = $this->input->post('ref_id');
				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id', 'state_id', 'city_id', 'zone_id', 'type_id');
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
			    	
			    	if(!empty($zone_id))
			    	{
			    		$type_val   = explode(',', $type_id);
						$type_count = count($type_val);

						for ($j=0; $j < $type_count; $j++) {

							$zone_val   = explode(',', $zone_id);
							$zone_count = count($zone_val);
							$zone_list = '';
							for ($i=0; $i < $zone_count; $i++) {

								// Get product type list
								$whr_1 = array('id' => $type_val[$j]);
								$col_1 = 'type_id';
								$res_1 = $this->assignproduct_model->getAssignProductDetails($whr_1, '', '', 'row', '', '', '', '', $col_1);

								$pdt_type = !empty($res_1->type_id)?$res_1->type_id:'';

								// Beat Details
						    	$where_1  = array(
						    		'type_id'        => $pdt_type,
						    		'distributor_id' => $distributor_id,
						    		'state_id'       => $state_id,
					    			'status'         => '1', 
									'published'      => '1'
						    	);

						    	$like['zone_id'] = ','.$zone_val[$i].',';

						    	$column = 'id';

								$assign_data = $this->assignproduct_model->getAssignProductAddtionalDetails($where_1, '', '', 'result', $like, '', '', '','');

								if(empty($assign_data))
								{
									$zone_list .= $zone_val[$i].',';
								}
							}
										// array_unique
								$zone_explode = explode(',', $zone_list);
								$zone_unique  = array_unique($zone_explode);
								$zone_result  = ','.implode(',', $zone_unique);

								// Distributor wise beat table update
								$data_5 = array(
									'ref_id'        => 0,
									'state_id'      => $state_id,
									'city_id'       => $city_id,	
									'zone_id'       => $zone_result,
									'updatedate'    => date('Y-m-d H:i:s'),
								);
								$update_id = array('id' => $type_val[$j]);
				    			$update    = $this->assignproduct_model->assignProductDetails_update($data_5, $update_id);
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

			if($method == '_assignMultipleBeat_dis')
			{
				$distributor_id = $this->input->post('distributor_id');
				$state_id       = $this->input->post('state_id');
				$city_id        = $this->input->post('city_id');
				$zone_id        = $this->input->post('zone_id');
				$category_id    = $this->input->post('category_id');
				$type_id        = $this->input->post('type_id');
				$minimum_stock  = $this->input->post('minimum_stock');
				$ref_id        = $this->input->post('ref_id');

				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id', 'state_id', 'city_id', 'zone_id', 'type_id');
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
			    	
			    	if(!empty($zone_id))
			    	{
			    		$type_val   = explode(',', $type_id);
						$type_count = count($type_val);

						for ($j=0; $j < $type_count; $j++) {

							$zone_val   = explode(',', $zone_id);
							$zone_count = count($zone_val);
							$zone_list = '';
							$zone_dt   = [];
							for ($i=0; $i < $zone_count; $i++) {

								// Get product type list
								$whr_1 = array('id' => $type_val[$j]);
								$col_1 = 'type_id';
								$res_1 = $this->assignproduct_model->getAssignProductDetails($whr_1, '', '', 'row', '', '', '', '', $col_1);

								$pdt_type = !empty($res_1->type_id)?$res_1->type_id:'';

								// Beat Details
						    	$where_1  = array(
						    		'type_id'        => $pdt_type,
						    		'distributor_id' => $ref_id,
									
						    		'state_id'       => $state_id,
					    			'status'         => '1', 
									'published'      => '1'
						    	);

						    	$like['zone_id'] = ','.$zone_val[$i].',';

						    	$column = 'id';

								$assign_data = $this->assignproduct_model->getAssigDistributorProductDetailsRef($where_1, '', '', 'result', $like, '', '', '', $column);
							
								
								if(!empty($assign_data))
								{
									array_push($zone_dt, $zone_val[$i]);

								}
							}
							
						
							 $count_hd_zn = count($zone_dt);
							
							for ($k=0; $k < $count_hd_zn; $k++) {

								// Get product type list
								$whr_1 = array('id' => $type_val[$j]);
								$col_1 = 'type_id';
								$res_1 = $this->assignproduct_model->getAssignProductDetails($whr_1, '', '', 'row', '', '', '', '', $col_1);

								$pdt_type = !empty($res_1->type_id)?$res_1->type_id:'';

								// Beat Details
						    	$where_1  = array(
						    		'type_id'        => $pdt_type,
						    		'distributor_id' => $distributor_id,
									'ref_id'         => $ref_id,
						    		'state_id'       => $state_id,
					    			'status'         => '1', 
									'published'      => '1'
						    	);

						    	$like['zone_id'] = ','.$zone_dt[$k].',';

						    	$column = 'id';

								$assign_data = $this->assignproduct_model->getAssignProductAddtionalDetails($where_1, '', '', 'result', $like, '', '', '', $column);
							
								if($assign_data == '')
								{
									$zone_list .= $zone_dt[$k].',';

								}
							}
						

							$zone_new = ','.$zone_list;
							$data_5 = array(
								'state_id'      => $state_id,
								'city_id'       => $city_id,	
								'zone_id'       => $zone_new,
								'ref_id'        => $ref_id,
								'updatedate'    => date('Y-m-d H:i:s'),
							);
							$update_id = array('id' => $type_val[$j]);
				    		$update    = $this->assignproduct_model->assignProductDetails_update($data_5, $update_id);
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

			else if($method == '_detailAssignProduct')
			{
				
				$type_id = $this->input->post('id');
				$ref_id = $this->input->post('ref_id');
				$city_id = $this->input->post('city_id');
				$id_val = explode(',', $type_id);
				$count_id = count($id_val);
				$main_zone = array();
				for ($j=0; $j < $count_id; $j++) {

					$wer_1 = array(
						'id' => $id_val[$j]
					);
					
					$col ='type_id';
					$data_id  = $this->assignproduct_model->getAssignProductDetails($wer_1,'','','result','','','','',$col);
					
					
					$pdt_type = [];
						foreach ($data_id as $key => $value) {
						    array_push($pdt_type, $value->type_id);
						}
						$type_val  = implode(',', $pdt_type);
					$wer_2 = array(
						
						'type_id' => $type_val,
						'distributor_id' => $ref_id
					);
					$col = 'zone_id';
					$dis_zone = $this->assignproduct_model->getAssignProductDetails($wer_2);
					
					$zone_id_finall = substr($dis_zone[0]->zone_id,1);
						$d_zone = !empty($zone_id_finall)?$zone_id_finall:'';
						
						$d_zone_val = explode(',', $d_zone);
						$city_val = explode(',', $city_id);
						
						
						$where_1['city_id'] =  $city_val;
					
						
						$col='id';
			     		$data_1    = $this->commom_model->getZoneSecond($where_1,'','',"result",'','','','',$col);
						
						 
						$city_zone=[];
						 foreach($data_1 as $key => $value){
                        
						   array_push($city_zone,$value->id);
						 };
						 
			 		
						
					
						
						$not_zone = array();
						foreach($city_zone as $value){
							
						
							if(in_array($value,$d_zone_val )){
							  array_push($main_zone,$value);
						
							}else{
							  array_push($not_zone,$value);
							};
						}

						
						
                }
				$zone=array_unique($main_zone);
				if(!empty($zone)){
					
							$were = array(
								'published'=>1,
							);
					
							$whr_in = $zone;
							
						

							$col_1='id,name';
							$data_2   = $this->commom_model->getZone($were, '', '', 'result', '', '', '','', $col_1, $whr_in);

							
				
						
							$response['status']  = 1;
				  		    $response['message'] = "Success"; 
				  		    $response['data']    = $data_2;
				  		    echo json_encode($response);
				  		    return;
				}else{
							$response['status']  = 0;
			                $response['message'] = "Not Found"; 
			                $response['data']    = [];
			                echo json_encode($response);
			                return;
			    }
							
				  		
				
				
			}

		}
	}
?>

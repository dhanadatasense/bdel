<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class PriceMaster extends CI_Controller {

		public function __construct()
		{
			parent::__construct();

			$this->load->model('pricemaster_model');
			$this->load->model('distributors_model');
			$this->load->model('commom_model');
			$this->load->model('assignproduct_model');
			$this->load->model('outlets_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Distributor Price Master
		// ***************************************************
		public function distributor_price_master($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addDistributorPrice')
			{
				$distributor_id = $this->input->post('distributor_id');
				$product_value  = $this->input->post('product_value');
				$ref_id  = $this->input->post('ref_id');
				$date           = date('Y-m-d');
				$time           = date('H:i:s');
				$c_date         = date('Y-m-d H:i:s');

				$error    = FALSE;
			    $errors   = array();
				$required = array('distributor_id', 'product_value');

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
			    	// Distributor Details
			    	$dis_whr = array(
	    				'id'        => $distributor_id,
	    				'status'    => '1',
	    				'published' => '1',
	    			);

	    			$dis_col  = 'company_name';
	    			$dis_data = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);

	    			$dis_name = !empty($dis_data[0]->company_name)?$dis_data[0]->company_name:'';

			    	$product_val = json_decode($product_value);	
			    	$product_cou = count($product_val);

			    	if($product_cou != 0)
			    	{
			    		foreach ($product_val as $key => $value) {
				    			
				    		$category_id   = !empty($value->category_id)?$value->category_id:'';
						    $type_id       = !empty($value->type_id)?$value->type_id:'';
						    $product_price = !empty($value->product_price)?$value->product_price:'0';

				    		// Product Details
				    		$pdt_whr = array(
			    				'id'        => $type_id,
			    				'status'    => '1',
			    				'published' => '1'
			    			);

			    			$pdt_col  = 'product_id,sub_cat_id';
							$pdt_data = $this->commom_model->getProductType($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);
							$sub_cat_id   = !empty($pdt_data[0]->sub_cat_id)?$pdt_data[0]->sub_cat_id:'';
							$pdt_id   = !empty($pdt_data[0]->product_id)?$pdt_data[0]->product_id:'';

							if(!empty($product_price))
							{
								$ins_data = array(
									'distributor_id'   => $distributor_id,
									'distributor_name' => $dis_name,
									'category_id'      => $category_id,
									'sub_cat_id'       => $sub_cat_id,
									'product_id'       => $pdt_id,
									'type_id'          => $type_id,
									'product_price'    => $product_price,
									'date'             => $date,
									'createdate'       => $c_date,
									'ref_id'           => $ref_id
								);

								$distributor_price = $this->pricemaster_model->distributorPrice_insert($ins_data);
							}
				    	}

				    	if($product_val)
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
			}

			if($method == '_listDistributorPrice')
			{
				$distributor_id = $this->input->post('distributor_id');
				$category_id    = $this->input->post('category_id');
				$sub_cat_id     = $this->input->post('sub_cat_id');

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
			    	if(!empty($sub_cat_id)){
						$where_1 = array(
							'distributor_id' => $distributor_id,
							'category_id'    => $category_id,
							'sub_cat_id'     => $sub_cat_id,
							'published'      => '1',
							'status'         => '1',
						);
					}else{
						$where_1 = array(
							'distributor_id' => $distributor_id,
							'category_id'    => $category_id,
							'published'      => '1',
							'status'         => '1',
						);
					}
			    	

			    	$data_list = $this->assignproduct_model->getAssignProductDetails($where_1);

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

							$assign_list[] = array(
								'assproduct_id'  => $assproduct_id,
								'assign_id'      => $assign_id,
								'distributor_id' => $distributor_id,
								'category_id'    => $category_id,
								'product_id'     => $product_id,
								'type_id'        => $type_id,
								'description'    => $description,
								'stock'          => $stock,
								'product_price'  => $distributor_price,
								'published'      => $published,
								'status'         => $status,
								'createdate'     => $createdate,
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

		// Outlet Price Master
		// ***************************************************
		public function outlet_price_master($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addOutletPrice')
			{
				$outlet_id      = $this->input->post('outlet_id');
				$product_value  = $this->input->post('product_value');
				$date           = date('Y-m-d');
				$time           = date('H:i:s');
				$c_date         = date('Y-m-d H:i:s');

				$error    = FALSE;
			    $errors   = array();
				$required = array('outlet_id', 'product_value');

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
			    	// Outlet Details
			    	$str_whr = array(
	    				'id'        => $outlet_id,
	    				'status'    => '1',
	    				'published' => '1',
	    			);

	    			$str_col  = 'company_name';
	    			$str_data = $this->outlets_model->getOutlets($str_whr, '', '', 'result', '', '', '', '', $str_col);

	    			$str_name = !empty($str_data[0]->company_name)?$str_data[0]->company_name:'';

			    	$product_val = json_decode($product_value);	
			    	$product_cou = count($product_val);

					$pdt_whr = array(
						'product_id'        => $product_id,
						'status'    => '1',
						'published' => '1'
					);

					$pdt_col  = 'sub_cat_id';
					$pdt_data = $this->commom_model->getProductType($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);

					
					$sub_cat_id   = !empty($pdt_data[0]->sub_cat_id)?$pdt_data[0]->sub_cat_id:'';
			    	if($product_cou != 0)
			    	{
			    		foreach ($product_val as $key => $val) {
			    			$category_id   = !empty($val->category_id)?$val->category_id:'';
						    $product_id    = !empty($val->product_id)?$val->product_id:'';
						    $type_id       = !empty($val->type_id)?$val->type_id:'';
						    $product_price = !empty($val->product_price)?$val->product_price:'';

						    if(!empty($product_price))
						    {
						    	$ins_data = array(
									'outlet_id'     => $outlet_id,
									'outlet_name'   => $str_name,
									'category_id'   => $category_id,
									'sub_cat_id'    => $sub_cat_id,
									'product_id'    => $product_id,
									'type_id'       => $type_id,
									'product_price' => $product_price,
									'date'          => $date,
									'createdate'    => $c_date,
								);

								$outlet_price = $this->pricemaster_model->outletPrice_insert($ins_data);
						    }
			    		}

			    		if($outlet_price)
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
			}

			else if($method == '_listOutletPrice')
			{	
				$outlet_id   = $this->input->post('outlet_id');
				$category_id = $this->input->post('category_id');
				$sub_cat_id  = $this->input->post('sub_cat_id');

				$error    = FALSE;
			    $errors   = array();
				$required = array('outlet_id', 'category_id');

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
					if($sub_cat_id){
						$where = array(
							'category_id' => $category_id,
							'sub_cat_id' => $sub_cat_id,
							'vendor_type' => '1', 
							'status'      => '1', 
							'published'   => '1'
						);
					}else{
						$where = array(
							'category_id' => $category_id,
							'vendor_type' => '1', 
							'status'      => '1', 
							'published'   => '1'
						);
					}

					
					$column = 'id, category_id, product_id, description, mrp_price, product_price';
					$data   = $this->commom_model->getProductType($where, '', '', 'result', '', '', '', '', $column);

					if($data)
					{
						$product_list = [];
						foreach ($data as $key => $val) {
								
							$type_id       = !empty($val->id)?$val->id:'';
				            $category_id   = !empty($val->category_id)?$val->category_id:'';
				            $product_id    = !empty($val->product_id)?$val->product_id:'';
				            $description   = !empty($val->description)?$val->description:'';
				            $mrp_price     = !empty($val->mrp_price)?$val->mrp_price:'';
				            $product_price = !empty($val->product_price)?$val->product_price:'';

				            // Distributor Price Details
					    	$where_3  = array(
					    		'outlet_id'   => $outlet_id,
					    		'category_id' => $category_id,
					    		'product_id'  => $product_id,
					    		'type_id'     => $type_id,
					    		'published'   => '1',
					    		'status'      => '1',
					    	);

					    	$option['order_by']   = 'id';
							$option['disp_order'] = 'DESC';

							$limit  = 1;
							$offset = 0;

							$column = 'product_price';

					    	$price_val = $this->pricemaster_model->getOutletPrice($where_3, $limit, $offset, 'result', '', '', $option, '', $column);

					    	$pdt_price = isset($price_val[0]->product_price)?$price_val[0]->product_price:'0';

					    	if(!empty($pdt_price))
					    	{
					    		$outlet_price = $pdt_price;
					    	}
					    	else
					    	{
					    		$outlet_price = $product_price;
					    	}

				            $product_list[] = array(
				            	'type_id'       => $type_id,
					            'category_id'   => $category_id,
					            'product_id'    => $product_id,
					            'description'   => $description,
					            'mrp_price'     => $mrp_price,
					            'product_price' => $outlet_price,
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
				        $response['message'] = "No Data Found"; 
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
		}
	}
?>
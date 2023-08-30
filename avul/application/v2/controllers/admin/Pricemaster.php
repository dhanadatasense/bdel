<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Pricemaster extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function distributor_price_master($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method   = $this->input->post('method');
        	$formpage = $this->input->post('formpage');

			if($formpage =='BTBM_X_P')
			{
				$distributor_id = $this->input->post('distributor_id');
				$category_id    = $this->input->post('category_id');
				$product_price  = $this->input->post('product_price');
				$product_id     = $this->input->post('product_id');
				$type_id        = $this->input->post('type_id');

				$error    = FALSE;
				$required = array('distributor_id', 'category_id');
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
			    	if($method =='BTBM_X_C')
					{
						if(userAccess('distributor-price-master-edit'))
						{
							$price_count = count($product_price);
							$price_value = [];

							for ($i=0; $i < $price_count; $i++) { 

								$price_value[] = array(
			            			'category_id'   => $category_id,
			            			'type_id'       => $type_id[$i],
			            			'product_price' => $product_price[$i],
			            		);
							}

							$product_value = json_encode($price_value);

							$data = array(
						    	'distributor_id' => $distributor_id,
						    	'product_value'  => $product_value,
								'ref_id'         => 0,
						    	'method'         => '_addDistributorPrice',
						    );

						    $data_save = avul_call(API_URL.'pricemaster/api/distributor_price_master', $data);

						    if($data_save['status'] == 1)
						    {
						    	$response['status']  = 1;
						        $response['message'] = $data_save['message']; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
						    else
						    {
						    	$response['status']  = 0;
						        $response['message'] = $data_save['message']; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 	
						    }
						}
						else
			    		{
			    			$response['status']  = 0;
					        $response['message'] = 'Access denied'; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
			    		}
					}
			    }
			}

			if($method =='getDistributorPriceList')
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
			    	if(userAccess('distributor-price-master-view'))
			    	{
			    		$pdt_whr  = array(
				    		'distributor_id' => $distributor_id,
				    		'category_id'    => $category_id,
							'sub_cat_id'     => $sub_cat_id,
				    		'method'         => '_listDistributorPrice',
				    	);
					

				    	$pdt_data  = avul_call(API_URL.'pricemaster/api/distributor_price_master', $pdt_whr);
				    	$pdt_value = !empty($pdt_data['data'])?$pdt_data['data']:'';

				    	if($pdt_value)
				    	{
				    		$html = '';
				    		$num  = 1;
				    		foreach ($pdt_value as $key => $value) {
							    $product_id    = !empty($value['product_id'])?$value['product_id']:'';
							    $type_id       = !empty($value['type_id'])?$value['type_id']:'';
				    			$description   = !empty($value['description'])?$value['description']:'';
							    $stock         = !empty($value['stock'])?$value['stock']:'';
							    $product_price = !empty($value['product_price'])?$value['product_price']:'0';

							    $html .= '
				    				<tr class="row_'.$num.'">
				    					<td>'.$num.'</td>
				    					<td>'.$description.'</td>
				    					<td><input type="text" data-te="'.$num.'" name="product_price[]" id="product_price'.$num.'" class="form-control product_price'.$num.' product_price int_value" placeholder="'.$product_price.'"></td>

				    						<input type="hidden" data-te="'.$num.'" name="product_id[]" id="product_id'.$num.'" class="form-control product_id'.$num.' product_id" placeholder="Price" value="'.$product_id.'">

				    						<input type="hidden" data-te="'.$num.'" name="type_id[]" id="type_id'.$num.'" class="form-control type_id'.$num.' type_id" placeholder="Price" value="'.$type_id.'">
				    					</td>
				    					<td>
				                            <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6 remove_item"><span class="ft-minus-square"></span></button>
				                        </td>
				    				</tr>
				    			';

							    $num++;
				    		}

				    		$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $html;
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
				        $response['message'] = 'Access denied'; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
		    		}
			    }
			}else if($param1 == 'get_sub_cat_list')
        	{
        		$cat_id  = $this->input->post('category_id');
				$distributor_id  = $this->input->post('distributor_id');
				
				
			    $att_whr = array(
			    	'category_id'  => $cat_id,
					'distributor_id'  =>  $distributor_id,
			    	'method'      => '_distributorSubCategoryList',
			    );
				
				
			    $data_list  = avul_call(API_URL.'distributors/api/distributors',$att_whr);
		    	
		    	$data_val  = $data_list['data'];
				
			
        		$option ='<option value="">Select Sub Category</option>';

        		if(!empty($data_val))
        		{
        			foreach ($data_val as $key => $value) {
        				$id   = !empty($value['s_cat_id']) ?$value['s_cat_id']:'';
                        
						$name =!empty($value['s_cat_name'])?$value['s_cat_name']:'';

                        $select   = '';
        				
						

                        $option .= '<option value="'.$id.'" '.$select.'>'.$name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
        	}else if($param1 == 'get_dis_cat_list')
        	{
        		
				$distributor_id  = $this->input->post('distributor_id');
				
				
			    $att_whr = array(
			    	
					'distributor_id'  =>  $distributor_id,
			    	'method'      => '_distributorCategoryList',
			    );
				
				
			    $data_list  = avul_call(API_URL.'distributors/api/distributors',$att_whr);
		    	
		    	$data_val  = $data_list['data'];
				
			
        		$option ='<option value="">Select Sub Category</option>';

        		if(!empty($data_val))
        		{
        			foreach ($data_val as $key => $value) {
        				$id   = !empty($value['category_id']) ?$value['category_id']:'';
                        
						$name =!empty($value['category_name'])?$value['category_name']:'';

                        $select   = '';
        				
						

                        $option .= '<option value="'.$id.'" '.$select.'>'.$name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
        	}

			else
			{
				// Distributors Details
				$where_1 = array(
					'ref_id'         => 0,
            		'method'         => '_listOverallDistributors',
            	);

            	$distributor_list = avul_call(API_URL.'distributors/api/distributors',$where_1);
            	$distributor_data = $distributor_list['data'];

				// // Categoty List
				// $where_2 = array(
				// 	'item_type'      => '1',
				// 	'salesagents_id' => '0',
            	// 	'method'         => '_listCategory',
            	// );

            	// $category_list = avul_call(API_URL.'catlog/api/category',$where_2);
            	// $category_data = $category_list['data'];

            	$page['distributor_val'] = $distributor_data;
            	// $page['category_val']    = $category_data;

				$page['dataval']      = '';
				$page['method']       = 'BTBM_X_C';
				$page['page_title']   = "Distributor Price Master";
				$page['main_heading'] = "Price Master";
				$page['sub_heading']  = "Price Master";
				$page['pre_title']    = "List Price Master";
				$page['page_access']  = userAccess('distributor-price-master-view');
				$page['pre_menu']     = "index.php/admin/pricemaster/list_category";
				$data['page_temp']    = $this->load->view('admin/pricemaster/distributor_price_master',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "distributor_price_master";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function outlet_price_master($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method   = $this->input->post('method');
        	$formpage = $this->input->post('formpage');

        	if($formpage =='BTBM_X_P')
        	{
        		$outlet_id     = $this->input->post('outlet_id');
				$category_id   = $this->input->post('category_id');
				$product_price = $this->input->post('product_price');
				$product_id    = $this->input->post('product_id');
				$type_id       = $this->input->post('type_id');

				$error    = FALSE;
				$required = array('outlet_id', 'category_id');
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
			    	if($method =='BTBM_X_C')
					{
						if(userAccess('outlet-price-master-edit'))
						{
							$price_count = count($product_price);
							$price_value = [];

							for ($i=0; $i < $price_count; $i++) { 

								$price_value[] = array(
			            			'category_id'   => $category_id,
			            			'product_id'    => $product_id[$i],
			            			'type_id'       => $type_id[$i],
			            			'product_price' => $product_price[$i],
			            		);
							}

							$product_value = json_encode($price_value);

							$data = array(
						    	'outlet_id'     => $outlet_id,
						    	'product_value' => $product_value,
						    	'method'        => '_addOutletPrice',
						    );

						    $data_save = avul_call(API_URL.'pricemaster/api/outlet_price_master', $data);

						    if($data_save['status'] == 1)
						    {
						    	$response['status']  = 1;
						        $response['message'] = $data_save['message']; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
						    else
						    {
						    	$response['status']  = 0;
						        $response['message'] = $data_save['message']; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 	
						    }
						}
						else
			    		{
			    			$response['status']  = 0;
					        $response['message'] = 'Access denied'; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
			    		}
					}
				}
        	}

        	if($method =='getOutletPriceList')
			{
				$outlet_id   = $this->input->post('outlet_id');
				$category_id = $this->input->post('category_id');
				$sub_cat_id  = $this->input->post('sub_cat');
				$error = FALSE;
			    $errors = array();
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
			    	if(userAccess('outlet-price-master-view'))
			    	{
			    		$pdt_whr  = array(
				    		'outlet_id'   => $outlet_id,
				    		'category_id' => $category_id,
							'sub_cat_id' => $sub_cat_id,
				    		'method'      => '_listOutletPrice',
				    	);

				    	$pdt_data  = avul_call(API_URL.'pricemaster/api/outlet_price_master', $pdt_whr);
				    	$pdt_value = !empty($pdt_data['data'])?$pdt_data['data']:'';

				    	if($pdt_value)
				    	{
				    		$html = '';
				    		$num  = 1;
				    		foreach ($pdt_value as $key => $val) {
				    				
				    			$type_id       = !empty($val['type_id'])?$val['type_id']:'';
							    $category_id   = !empty($val['category_id'])?$val['category_id']:'';
							    $product_id    = !empty($val['product_id'])?$val['product_id']:'';
							    $description   = !empty($val['description'])?$val['description']:'';
							    $mrp_price     = !empty($val['mrp_price'])?$val['mrp_price']:'';
							    $product_price = !empty($val['product_price'])?$val['product_price']:'';

							    $html .= '
				    				<tr class="row_'.$num.'">
				    					<td>'.$num.'</td>
				    					<td>'.$description.'</td>
				    					<td>
				    						<input type="text" data-te="'.$num.'" name="product_price[]" id="product_price'.$num.'" class="form-control product_price'.$num.' product_price int_value" placeholder="'.$product_price.'"></td>

				    						<input type="hidden" data-te="'.$num.'" name="product_id[]" id="product_id'.$num.'" class="form-control product_id'.$num.' product_id" placeholder="Price" value="'.$product_id.'">

				    						<input type="hidden" data-te="'.$num.'" name="type_id[]" id="type_id'.$num.'" class="form-control type_id'.$num.' type_id" placeholder="Price" value="'.$type_id.'">
				    					</td>
				    					<td>
				                            <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6 remove_item"><span class="ft-minus-square"></span></button>
				                        </td>
				    				</tr>
				    			';

				    			$num++;
				    		}

				    		$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $html;
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
				        $response['message'] = 'Access denied'; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
		    		}
			    }
			}

        	if($param1 =='getCity_name')
			{
				$state_id = $this->input->post('state_id');

				$where = array(
            		'state_id' => $state_id,
            		'method'   => '_listCity'
            	);

            	$city_list   = avul_call(API_URL.'master/api/city',$where);
            	$city_result = $city_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($city_result))
        		{
        			foreach ($city_result as $key => $value) {
        				$city_id   = !empty($value['city_id'])?$value['city_id']:'';
                        $city_name = !empty($value['city_name'])?$value['city_name']:'';

                        $option .= '<option value="'.$city_id.'">'.$city_name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
			}

			else if($param1 =='getZone_name')
			{
				$state_id = $this->input->post('state_id');
				$city_id  = $this->input->post('city_id');

				$where = array(
            		'state_id' => $state_id,
            		'city_id'  => $city_id,
            		'method'   => '_listZone'
            	);

            	$zone_list   = avul_call(API_URL.'master/api/zone',$where);
            	$zone_result = $zone_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($zone_result))
        		{
        			foreach ($zone_result as $key => $value) {
        				$zone_id   = !empty($value['zone_id'])?$value['zone_id']:'';
                        $zone_name = !empty($value['zone_name'])?$value['zone_name']:'';

                        $option .= '<option value="'.$zone_id.'">'.$zone_name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
			}

			else if($param1 =='getOutlet_name')
			{
				$state_id = $this->input->post('state_id');
				$city_id  = $this->input->post('city_id');
				$zone_id  = $this->input->post('zone_id');

				$where = array(
            		'state_id' => $state_id,
            		'city_id'  => $city_id,
            		'zone_id'  => $zone_id,
            		'method'   => '_zoneWiseOutlets'
            	);

            	$outlet_list   = avul_call(API_URL.'outlets/api/outlets',$where);
            	$outlet_result = $outlet_list['data'];

            	$option ='<option value="">Select Value</option>';

        		if(!empty($outlet_result))
        		{
        			foreach ($outlet_result as $key => $value) {
        				$outlets_id   = !empty($value['outlets_id'])?$value['outlets_id']:'';
                        $company_name = !empty($value['company_name'])?$value['company_name']:'';

                        $option .= '<option value="'.$outlets_id.'">'.$company_name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
			}else if($param1 == 'sub_cat')
        	{
        		$cat_id  = $this->input->post('cat_id');
				
				
				
			    $att_whr = array(
			    	'cat_id'  => $cat_id,
					'admin'  =>  $this->session->userdata('id'),
			    	'method'      => '_listSubCategory',
			    );
				
				
			    $data_list  = avul_call(API_URL.'catlog/api/sub_category',$att_whr);
		    	
		    	$data_val  = $data_list['data'];
				
			
        		$option ='<option value="">Select Sub Category</option>';

        		if(!empty($data_val))
        		{
        			foreach ($data_val as $key => $value) {
        				$id   = !empty($value['id']) ?$value['id']:'';
                        
						$name =!empty($value['sub_cat_name'])?$value['sub_cat_name']:'';

                        $select   = '';
        				
						

                        $option .= '<option value="'.$id.'" '.$select.'>'.$name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
        	} 

        	else
			{
				// State List
				$where_1 = array(
            		'method'   => '_listState'
            	);

            	$state_list = avul_call(API_URL.'master/api/state',$where_1);
            	$state_data = $state_list['data'];

				// Categoty List
				$where_2 = array(
					'item_type'      => '1',
					'salesagents_id' => '0',
            		'method'         => '_listCategory',
            	);

            	$category_list = avul_call(API_URL.'catlog/api/category',$where_2);
            	$category_data = $category_list['data'];

            	$page['category_val'] = $category_data;
            	$page['state_val']    = $state_data;
				$page['dataval']      = '';
				$page['method']       = 'BTBM_X_C';
				$page['page_title']   = "Outlet Price Master";
				$page['main_heading'] = "Price Master";
				$page['sub_heading']  = "Price Master";
				$page['pre_title']    = "List Price Master";
				$page['page_access']  = userAccess('outlet-price-master-view');
				$page['pre_menu']     = "index.php/admin/pricemaster/list_category";
				$data['page_temp']    = $this->load->view('admin/pricemaster/outlet_price_master',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "outlet_price_master";
				$this->bassthaya->load_admin_form_template($data);
			}
		}
	}
?>
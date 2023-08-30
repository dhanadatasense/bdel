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
                                'ref_id'         => $this->session->userdata('id'),
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
            		'method'         => '_listOverallDistributors',
                    'ref_id'         => $this->session->userdata('id'),
            	);

            	$distributor_list = avul_call(API_URL.'distributors/api/distributors',$where_1);
            	$distributor_data = $distributor_list['data'];

				

            	$page['distributor_val'] = $distributor_data;
            	
				$page['dataval']      = '';
				$page['method']       = 'BTBM_X_C';
				$page['page_title']   = "Distributor Price Master";
				$page['main_heading'] = "Price Master";
				$page['sub_heading']  = "Price Master";
				$page['pre_title']    = "List Price Master";
				//$page['page_access']  = userAccess('distributor-price-master-view');
				$page['pre_menu']     = "index.php/distributors/pricemaster/list_category";
				$data['page_temp']    = $this->load->view('distributors/pricemaster/distributor_price_master',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "distributor_price_master";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

	
	}
?>
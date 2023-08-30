<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Loyalty extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function add_product_loyalty($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');
			$method   = $this->input->post('method');
			$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');

			$loyalty_id   = $this->input->post('loyalty_id');
			$state_id     = $this->input->post('state_id');
			$city_id      = $this->input->post('city_id');
			$zone_id      = $this->input->post('zone_id');
			$outlet_id    = $this->input->post('outlet_id');
			$vendor_id    = $this->input->post('vendor_id');
			$start_date   = $this->input->post('start_date');
			$end_date     = $this->input->post('end_date');
			$category_val = $this->input->post('category_val');

			$auto_id      = $this->input->post('auto_id');
			$loyalty_type = $this->input->post('loyalty_type');
			$category_id  = $this->input->post('category_id');
			$product_id   = $this->input->post('product_id');
			$type_id      = $this->input->post('type_id');
			$pdt_price    = $this->input->post('pdt_price');

			if($formpage =='BTBM_X_P')
			{
				$error    = FALSE;
				$required = array('start_date', 'end_date', 'category_val', 'type_id', 'pdt_price');
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
			    	if($method == 'BTBM_X_C')
			    	{
			    		if(userAccess('product-loyalty-add'))
			    		{
			    			// loyalty details
					    	$price_list  = [];
						    $price_count = count($pdt_price);

						    for($j = 0; $j < $price_count; $j++)
						    {
						    	if($pdt_price[$j] != 0)
						    	{
						    		$price_list[] = array(
						    			'loyalty_type'  => $loyalty_type[$j],
						    			'category_id'   => $category_id[$j],
						    			'product_id'    => $product_id[$j],
						    			'type_id'       => $type_id[$j],
						    			'product_price' => $pdt_price[$j],
						    		);
						    	}
						    }

						    $price_value    = json_encode($price_list);
						    $category_value = implode(',', $category_val);

					    	$ins_data = array(
					    		'log_id'       => $log_id,
								'log_role'     => $log_role,
					    		'state_id'     => $state_id,
					    		'city_id'      => $city_id,
					    		'zone_id'      => $zone_id,
					    		'vendor_id'    => $vendor_id,
					    		'outlet_id'    => $outlet_id,
					    		'start_date'   => $start_date,
					    		'end_date'     => $end_date,
					    		'category_val' => $category_value,
					    		'price_value'  => $price_value,
					    		'method'       => '_addProductLoyalty',
					    	);

					    	// print_r($ins_data); exit;

					    	$data_save = avul_call(API_URL.'loyalty/api/product_loyalty',$ins_data);

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
			    	else
			    	{
			    		if(userAccess('product-loyalty-edit'))
			    		{
			    			// loyalty details
					    	$price_list  = [];
						    $price_count = count($pdt_price);

						    for($j = 0; $j < $price_count; $j++)
						    {
						    	if($pdt_price[$j] != 0)
						    	{
						    		$price_list[] = array(
						    			'auto_id'       => $auto_id[$j],
						    			'category_id'   => $category_id[$j],
						    			'product_id'    => $product_id[$j],
						    			'type_id'       => $type_id[$j],
						    			'product_price' => $pdt_price[$j],
						    		);
						    	}
						    }

						    $price_value    = json_encode($price_list);
						    $category_value = implode(',', $category_val);

					    	$upt_data = array(
					    		'log_id'       => $log_id,
								'log_role'     => $log_role,
					    		'loyalty_id'   => $loyalty_id,
					    		'state_id'     => $state_id,
					    		'city_id'      => $city_id,
					    		'zone_id'      => $zone_id,
					    		'vendor_id'    => $vendor_id,
					    		'outlet_id'    => $outlet_id,
					    		'start_date'   => $start_date,
					    		'end_date'     => $end_date,
					    		'category_val' => $category_value,
					    		'price_value'  => $price_value,
					    		'method'       => '_updateProductLoyalty',
					    	);

					    	print_r($upt_data); exit;

						    $data_save = avul_call(API_URL.'loyalty/api/product_loyalty',$upt_data);

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

			if($method =='getProductList')
			{	
				$error    = FALSE;
				$required = array('start_date', 'end_date', 'category_val');
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
			    	$category_res = '';

			    	if($category_val)
			    	{
			    		$category_res = implode(',', $category_val);
			    	}

			    	$whr_1 = array(
						'state_id'    => zero_check($state_id),
						'city_id'     => zero_check($city_id),
						'zone_id'     => zero_check($zone_id),
						'outlet_id'   => zero_check($outlet_id),
						'vendor_id'   => zero_check($vendor_id),
						'start_date'  => date('Y-m-d', strtotime($start_date)),
						'end_date'    => date('Y-m-d', strtotime($end_date)),
						'category_id' => $category_res,
						'method'      => '_overallProductList',
					);

					$data_list = avul_call(API_URL.'loyalty/api/product_loyalty',$whr_1);
	            	$data_res  = $data_list['data'];

	            	if($data_list['status'] == 1)
				    {
				    	$html     = '';
			    		$data_val = $data_list['data'];
			    		$num      = 1;

			    		foreach ($data_res as $key => $val) {

						    $type_id       = zero_check($val['type_id']);
			    			$description   = empty_check($val['description']);
						    $category_id   = zero_check($val['category_id']);
						    $product_id    = zero_check($val['product_id']);
						    $product_price = zero_check($val['product_price']);

						    $html .= '
		                    	<tr>
	                                <td>'.$num.'</td>
	                                <td>'.mb_strimwidth($description, 0, 50, '...').'</td>
	                                <td style="padding-top: 12px;">
	                                	<select class="form-control loyalty_type loyalty_type_'.$num.'" data-te="'.$num.'" id="loyalty_type" name="loyalty_type[]" style="height: calc(2em + 1.0rem + 0px); padding: 10px; width: 120px;">
			                                <option value="1">Percentage</option>
                                			<option value="2">Amount</option>	
			                            </select>
	                                </td>
	                                <td style="padding-top: 12px;">
	                                	<input data-val="" type="hidden" id="category_id" class="form-control category_id category_id_'.$num.' int_value" name="category_id[]" value="'.$category_id.'" style="height: calc(2em + 1.0rem + 0px); padding: 10px; width: 100px;">
	                                	<input data-val="" type="hidden" id="product_id" class="form-control product_id product_id_'.$num.' int_value" name="product_id[]" value="'.$product_id.'" style="height: calc(2em + 1.0rem + 0px); padding: 10px; width: 100px;">
	                                	<input data-val="" type="hidden" id="type_id" class="form-control type_id type_id_'.$num.' int_value" name="type_id[]" value="'.$type_id.'" style="height: calc(2em + 1.0rem + 0px); padding: 10px; width: 100px;">
	                                	<input data-val="" type="text" id="pdt_price" class="form-control pdt_price pdt_price_'.$num.' int_value" name="pdt_price[]" placeholder="'.$product_price.'" style="height: calc(2em + 1.0rem + 0px); padding: 10px; width: 100px;" maxlength="2">
	                                </td>
	                                <td class="buttonlist p-l-0 text-center">
			                            <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6 remove_item"><span class="ft-minus-square"></span></button>
			                        </td>
	                            </tr>
		                    ';

		                    $num++;
			    		}

			    		$response['status']  = 1;
				        $response['message'] = $data_list['message']; 
				        $response['data']    = $html;
				        $response['error']   = []; 
				        echo json_encode($response);
				        return;
				    }
				    else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = $data_list['message']; 
				        $response['data']    = [];
				        $response['error']   = []; 
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

            	$option ='';
        		if(!empty($city_result))
        		{
        			$option .='<option value="0">Select Value</option>';

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

        		$option ='';

        		if(!empty($zone_result))
        		{
        			$option .='<option value="0">Select Value</option>';

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

            	$option ='';

        		if(!empty($outlet_result))
        		{
        			$option .='<option value="0">Select Value</option>';

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
			} 

			else
			{
				$where_1 = array(
            		'method'   => '_listState'
            	);

            	$state_list  = avul_call(API_URL.'master/api/state',$where_1);

            	$where_2 = array(
					'item_type'      => '1',
					'salesagents_id' => '0',
            		'method'         => '_listCategory',
            	);

            	$category_list = avul_call(API_URL.'catlog/api/category',$where_2);

            	$where_3 = array(
            		'method'    => '_listManufacturerVendors'
            	);

            	$vendor_list = avul_call(API_URL.'vendors/api/vendors',$where_3);

            	if($param1 =='Edit')
            	{
            		$loyalty_id = !empty($param2)?$param2:'';

					$where = array(
	            		'loyalty_id' => $loyalty_id,
	            		'method'     => '_productLoyaltyDetails'
	            	);

	            	$data_list = avul_call(API_URL.'loyalty/api/product_loyalty',$where);
	            	$data_val  = !empty($data_list['data'])?$data_list['data']:'';

	            	$loyalty_header = !empty($data_val['loyalty_header'])?$data_val['loyalty_header']:'';

	            	$state_id = zero_check($loyalty_header['state_id']);
		            $city_id  = zero_check($loyalty_header['city_id']);
		            $beat_id  = zero_check($loyalty_header['beat_id']);

		            $city_result = '';
		            if($state_id != 0)
		            {
		            	$where = array(
		            		'state_id' => $state_id,
		            		'method'   => '_listCity'
		            	);

		            	$city_list      = avul_call(API_URL.'master/api/city',$where);
		            	$city_result    = $city_list['data'];
		            }

		            $zone_result = '';
		            if($city_id != 0)
		            {
		            	$where = array(
		            		'state_id' => $state_id,
		            		'city_id'  => $city_id,
		            		'method'   => '_listZone'
		            	);

		            	$zone_list   = avul_call(API_URL.'master/api/zone',$where);
		            	$zone_result = $zone_list['data'];
		            }

		            $outlet_result = '';
		            if($beat_id != 0)
		            {
						$where = array(
		            		'state_id' => $state_id,
		            		'city_id'  => $city_id,
		            		'zone_id'  => $beat_id,
		            		'method'   => '_zoneWiseOutlets'
		            	);

		            	$outlet_list   = avul_call(API_URL.'outlets/api/outlets',$where);
		            	$outlet_result = $outlet_list['data'];
		            }

            		$page['method']     = 'BTBM_X_U';
					$page['page_title'] = "Edit Product Loyalty";
					$page['dataval']    = $data_val;
					$page['city_val']   = $city_result;
					$page['beat_val']   = $zone_result;
					$page['outlet_val'] = $outlet_result;
            	}
            	else
            	{
            		$page['method']     = 'BTBM_X_C';
					$page['page_title'] = "Add Product Loyalty";
					$page['dataval']    = '';
					$page['city_val']   = '';
					$page['beat_val']   = '';
					$page['outlet_val'] = '';
            	}

				$page['state_val']    = $state_list['data'];
				$page['category_val'] = $category_list['data'];
				$page['vendor_val']   = $vendor_list['data'];
				$page['main_heading'] = "Loyalty";
				$page['sub_heading']  = "Product Loyalty";
				$page['pre_title']    = "List Product Loyalty";
				$page['page_access']  = userAccess('product-loyalty-view');
				$page['pre_menu']     = "index.php/admin/loyalty/list_product_loyalty";
				$data['page_temp']    = $this->load->view('admin/loyalty/add_product_loyalty',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_product_loyalty";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function list_product_loyalty($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');

        	if($param1 == '')
			{
				$page['value']        = "admin";
	        	$page['controller']   = "loyalty";
	        	$page['function']     = "list_product_loyalty";
	        	$page['load_data']    = "";
				$page['main_heading'] = "Loyalty";
				$page['sub_heading']  = "Product Loyalty";
				$page['page_title']   = "List Product Loyalty";
				$page['pre_title']    = "Add Product Loyalty";
				$page['page_access']  = userAccess('product-loyalty-add');
				$page['pre_menu']     = "index.php/admin/loyalty/add_product_loyalty";
				$data['page_temp']    = $this->load->view('admin/loyalty/list_product_loyalty',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_product_loyalty";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('product-loyalty-view'))
				{
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
	            		'offset'    => $_offset,
	            		'limit'     => $limit,
	            		'search'    => $search,
	            		'method'    => '_listProductLoyaltyPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'loyalty/api/product_loyalty',$where);
	            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

	            	if(!empty($data_value))
	            	{
	            		$count    = count($data_value);
		            	$total    = isset($data_list['total_record'])?$data_list['total_record']:'';
		            	$tot_page = ceil($total / $limit); 

	            		$status  = 1;
		            	$message = 'Success';
		            	$table   = '';

		            	$i=1;
		            	foreach ($data_value as $key => $value) {

				            $loyalty_id    = empty_check($value['loyalty_id']);
				            $start_date    = empty_check($value['start_date']);
				            $end_date      = empty_check($value['end_date']);
				            $description   = empty_check($value['description']);
				            $active_status = empty_check($value['status']);
				            $createdate    = empty_check($value['createdate']);

				            if($active_status == '1')
			                {
			                	$status_view = '<span class="badge badge-success">Active</span>';
			                }
			                else
			                {
			                	$status_view = '<span class="badge badge-danger">In Active</span>';
			                }

			                $edit   = '';
				            $delete = '';
				            if(userAccess('product-loyalty-edit') == TRUE)
				            {
				            	$edit = '<a href="'.BASE_URL.'index.php/admin/loyalty/add_product_loyalty/Edit/'.$loyalty_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
				            }

				            if(userAccess('product-loyalty-delete') == TRUE)
				            {
				            	$delete = '<a data-row="'.$i.'" data-id="'.$loyalty_id.'" data-value="admin" data-cntrl="loyalty" data-func="list_product_loyalty" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
				            }

			                $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.mb_strimwidth($description, 0, 25, '...').'</td>
	                                <td class="line_height">'.$start_date.'</td>
	                                <td class="line_height">'.$end_date.'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                                if(userAccess('product-loyalty-edit') == TRUE || userAccess('product-loyalty-delete') == TRUE):
		                            	$table .= '<td>'.$edit.$delete.'</td>';
		                        	endif;
	                            $table .='</tr>
						    ';
						    $i++;
		            	}

		            	$prev = '';
		            	$next = '
			        		<tr>
			        			<td>';
			        				if($cur_page >= 2):
			        				$next .='<span data-page="'.$pre_page.'" class="pages btn btn-warning waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>';
			        				endif;
			        			$next .= '</td>
			        			<td>';
			        				if($tot_page > $cur_page):
			        				$next .='<span data-page="'.$nxt_page.'" class="pages btn btn-success waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
			        				endif;
			        			$next .='</td>
			        		</tr>
			        	';
	            	}
	            	else
	            	{
	            		$status     = 0;
		            	$message    = 'No Records';
		            	$table      = '';
		            	$next       = '';
		            	$prev       = '';
	            	}
				}
				else
		    	{
		    		$status     = 0;
		        	$message    = 'Access denied';
		        	$table      = '';
		        	$next       = '';
		        	$prev       = '';
		    	}

            	$response['status']     = $status;  
                $response['result']     = $table;  
                $response['message']    = $message;
                $response['next']       = $next;
                $response['prev']       = $prev;
                echo json_encode($response);
                return;
			}

			else if($param1 == 'delete')
			{
				$id = $this->input->post('id');

				if(!empty($id))	
				{
					$data = array(
						'log_id'     => $log_id,
						'log_role'   => $log_role,
				    	'loyalty_id' => $id,
				    	'method'     => '_deleteProductLoyalty'
				    );

				    $data_save = avul_call(API_URL.'loyalty/api/product_loyalty', $data);

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
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        $response['error']   = []; 
			        echo json_encode($response);
			        return;
				}
			}

			else if($param1 == 'single_delete')
			{
				$id = $this->input->post('id');

				if(!empty($id))	
				{
					$data = array(
						'log_id'   => $log_id,
						'log_role' => $log_role,
				    	'auto_id'  => $id,
				    	'method'   => '_deleteSingleLoyalty'
				    );

				    $data_save = avul_call(API_URL.'loyalty/api/product_loyalty', $data);

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
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        $response['error']   = []; 
			        echo json_encode($response);
			        return;
				}
			}
		}
	}
?>
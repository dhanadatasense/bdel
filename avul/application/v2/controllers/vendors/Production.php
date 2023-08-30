<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Production extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function add_production($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$vendor_id = $this->session->userdata('id');	
			$formpage  = $this->input->post('formpage');
			$method    = $this->input->post('method');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;	
				$product_id = $this->input->post('product_id');
				$start_date = $this->input->post('start_date');
				$end_date   = $this->input->post('end_date');
				$order_qty  = $this->input->post('order_qty');
				$product_id = $this->input->post('product_id');
				$type_id    = $this->input->post('type_id');
				$unit_val   = $this->input->post('unit_val');

				$required = array('start_date', 'end_date');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if(count(array_filter($order_qty))!==count($order_qty) || count(array_filter($product_id))!==count($product_id) || count(array_filter($type_id))!==count($type_id) || count(array_filter($unit_val))!==count($unit_val) || $error == TRUE)
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
			    		$production_item = [];
			    		$product_count   = count($product_id);

			    		for($j = 0; $j < $product_count; $j++)
			    		{
			    			$production_item[] = array(
			    				'product_id' => $product_id[$j],
			    				'type_id'    => $type_id[$j],
			    				'unit_val'   => $unit_val[$j],
			    				'order_qty'  => $order_qty[$j],
			    			);
			    		}

			    		$production_value = json_encode($production_item);

			    		$data = array(
			    			'start_date'       => $start_date,
			    			'end_date'         => $end_date,
			    			'order_type'       => '1',
			    			'production_type'  => '2',
			    			'vendor_id'        => $vendor_id,	
            				'financial_year'   => $this->session->userdata('active_year'),
			    			'production_value' => $production_value,
			    			'method'           => '_addAdminProduction',
			    		);

			    		$insert  = avul_call(API_URL.'production/api/add_production',$data);

			    		if($insert['status'] == 1)
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

			if($method =='getProductionData')
			{
				$error = FALSE;
				$start_date = $this->input->post('start_date');
				$end_date   = $this->input->post('end_date');

				$required = array('start_date', 'end_date');
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
			    	$where = array(
						'start_date' => date('Y-m-d', strtotime($start_date)),
						'end_date'   => date('Y-m-d', strtotime($end_date)),
						'vendor_id'  => $vendor_id,	
 			    		'method'     => '_listAdminProductionProduct',
			    	);

			    	$production_qry  = avul_call(API_URL.'production/api/list_production',$where);
			    	$production_data = $production_qry['data'];

			    	if($production_data)
			    	{
			    		$table = '';

			    		$i=1;
			    		foreach ($production_data as $key => $value) {
			    			
			    			$product_id   = !empty($value['product_id'])?$value['product_id']:'';
						    $product_name = !empty($value['product_name'])?$value['product_name']:'';
						    $type_id      = !empty($value['type_id'])?$value['type_id']:'';
						    $description  = !empty($value['description'])?$value['description']:'';
						    $type_name    = !empty($value['type_name'])?$value['type_name']:'';
						    $product_qty  = !empty($value['product_qty'])?$value['product_qty']:'';
						    $product_unit = !empty($value['product_unit'])?$value['product_unit']:'';
						    $unit_name    = !empty($value['unit_name'])?$value['unit_name']:'';

						    $table .= '
						    	<tr class="row_'.$i.'">
						    		<td class="p-t-20">'.$i.'</td>
						    		<td class="p-t-20">'.$description.'</td>
						    		<td><input type="text" id="order_qty" class="form-control order_qty int_value" placeholder="Brand Name" name="order_qty[]" value="'.$product_qty.'" ></td>
						    		<td class="p-t-20">nos</td>
						    		<td>
						    			<button type="button" name="remove" class="btn btn-danger btn-sm  button_size remove_store m-t-6"><span class="ft-minus-square"></span></button>

						    			<input type="hidden" name="product_id[]" value="'.$product_id.'">
						    			<input type="hidden" name="type_id[]" value="'.$type_id.'">
						    			<input type="hidden" name="unit_val[]" value="'.$product_unit.'">
						    		</td>
						    	</tr>
						    ';

						    $i++;
			    		}

			    		$response['status']  = 1;
				        $response['message'] = 'success'; 
				        $response['data']    = $table;
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

			else
			{
				$page['dataval']      = '';
				$page['method']       = 'BTBM_X_C';
				$page['page_title']   = "Add Work order";
				$page['main_heading'] = "Work order";
				$page['sub_heading']  = "Work order";
				$page['pre_title']    = "List Work order";
				$page['pre_menu']     = "index.php/vendors/production/list_production";
				$data['page_temp']    = $this->load->view('vendors/production/add_production',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_production";
				$this->bassthaya->load_vendors_form_template($data);
			}
		}

		public function list_production($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$vendor_id = $this->session->userdata('id');	

			if($param1 == '')
			{
				$page['main_heading'] = "Work order";
				$page['sub_heading']  = "Work order";
				$page['page_title']   = "List Work order";
				$page['pre_title']    = "Add Work order";
				$page['pre_menu']     = "index.php/vendors/production/add_production";
				$data['page_temp']    = $this->load->view('vendors/production/list_production',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_production";
				$this->bassthaya->load_vendors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				$limit    = $this->input->post('limitval');
            	$page     = $this->input->post('page');
            	$search   = $this->input->post('search');
            	$cur_page = isset($page)?$page:'1';
            	$_offset  = ($cur_page-1) * $limit;
            	$nxt_page = $cur_page + 1;
            	$pre_page = $cur_page - 1;

            	$where = array(
            		'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
	    			'vendor_id'      => $vendor_id,	
    				'financial_year' => $this->session->userdata('active_year'),
            		'method'         => '_listAdminProductionPaginate',
            	);

            	$data_list  = avul_call(API_URL.'production/api/list_production',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	if(!empty($data_value))
            	{
            		$count    = count($data_value);
	            	$total    = isset($data_list['total_record'])?$data_list['total_record']:'';
	            	$tot_page = ceil($total / $limit); 

            		$status  = 1;
            		$message = 'Success';
            		$table   = '';

            		$i = 1;
            		foreach ($data_value as $key => $value) {

            			$production_id = !empty($value['production_id'])?$value['production_id']:'';
					    $production_no = !empty($value['production_no'])?$value['production_no']:'';
					    $start_date    = !empty($value['start_date'])?$value['start_date']:'';
					    $end_date      = !empty($value['end_date'])?$value['end_date']:'';
					    $active_status = !empty($value['status'])?$value['status']:'';
					    $createdate    = !empty($value['createdate'])?$value['createdate']:'';

					    if($active_status == '1')
		                {
		                	$status_view = '<span class="badge badge-success">Active</span>';
		                }
		                else
		                {
		                	$status_view = '<span class="badge badge-danger">In Active</span>';
		                }

					    $table .= '
					    	<tr>
					    		<td>'.$i.'</td>
                                <td>'.$production_no.'</td>
		                        <td>'.date('d-m-Y', strtotime($start_date)).'</td>
		                        <td>'.date('d-m-Y', strtotime($end_date)).'</td>
		                        <td class="line_height">'.$status_view.'</td>
		                        <td>
		                        	<a href="'.BASE_URL.'index.php/vendors/production/production_stock/stock_list/'.$production_id.'" class="button_clr btn btn-success"><i class="ft-shopping-cart"></i> Stock </a>

                                	<a class="btn btn-warning" target="_blank" href="'.BASE_URL.'index.php/vendors/production/list_production/excel_print/'.$production_id.'" style="color: #fff; padding: 6px;"><i class="icon-grid"></i> Excel</a>
		                        </td>
                            </tr>
					    ';

            			$i++;
            		}
            		
            		$prev    = '';

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

            	$response['status']     = $status;  
                $response['result']     = $table;  
                $response['message']    = $message;
                $response['next']       = $next;
                $response['prev']       = $prev;
                echo json_encode($response);
                return;
        	}

        	else if($param1 == 'excel_print')
        	{
        		$production_id = $param2;

        		// Product Details
				$where_1 = array(
			    	'production_id' => $production_id,
			    	'method'        => '_adminProductionDetails'
			    );

			    $product_val  = avul_call(API_URL.'production/api/list_production',$where_1);
			    $product_data = $product_val['data']['production_details'];
			    $product_list = $product_val['data']['production_list'];

			    $production_no = !empty($product_data['production_no'])?$product_data['production_no']:'';
                $start_date    = !empty($product_data['start_date'])?$product_data['start_date']:'';
                $end_date      = !empty($product_data['end_date'])?$product_data['end_date']:'';

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename='.$production_no.'_work_order_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Product Name', 'Order Qty'));

			    if($product_val['status'] == 1)
		    	{	
		    		foreach ($product_list as $key => $value) {

                        $description = !empty($value['description'])?$value['description']:'';
                        $order_qty   = !empty($value['order_qty'])?$value['order_qty']:'';

		    			$num = array(
                        	$description,
                        	$order_qty,
                        );

                        fputcsv($output, $num);
		    		}
		    	}

		    	fclose($output);
      			exit();

        	}
		}

		public function production_stock($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			$formpage = $this->input->post('formpage');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$received_date = $this->input->post('received_date');
				$received_qty  = $this->input->post('received_qty');
				$wo_id         = $this->input->post('wo_id');
				$wo_auto_id    = $this->input->post('wo_auto_id');
				$product_id    = $this->input->post('product_id');
				$type_id       = $this->input->post('type_id');
				$product_unit  = $this->input->post('product_unit');
				$method        = $this->input->post('method');

				$required = array('received_date', 'received_qty', 'wo_auto_id', 'wo_id', 'product_id', 'type_id', 'product_unit');
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
			    		$data = array(
			    			'pro_id'        => $wo_id,
			    			'pro_auto_id'   => $wo_auto_id,
			    			'product_id'    => $product_id,
			    			'type_id'       => $type_id,
			    			'received_qty'  => $received_qty,
			    			'received_date' => $received_date,
			    			'product_unit'  => $product_unit,
			    			'method'        => '_addAdminWorkorderStockDetails',
			    		);

			    		$data_save = avul_call(API_URL.'production/api/manage_production_stock',$data);

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

			if($param1 =='stock_add')
			{
				$production_id = $param3;
				$auto_id       = $param2;

				// Production Details
				$where_1 = array(
			    	'production_id' => $production_id,
			    	'method'        => '_detailAdminProduction'
			    );

			    $production_val = avul_call(API_URL.'production/api/list_production', $where_1);

			    // Production Product Details
			    $where_2 = array(
			    	'auto_id'       => $auto_id,
			    	'production_id' => $production_id,
			    	'method'        => '_detailAdminProductionProduct',
			    );

			    $productDetails_val  = avul_call(API_URL.'production/api/list_production', $where_2);

			    $productDetails_data = $productDetails_val['data'];

			    $product_id = !empty($productDetails_data['product_id'])?$productDetails_data['product_id']:'';
			    $type_id    = !empty($productDetails_data['type_id'])?$productDetails_data['type_id']:'';

			    // Stock Details
			    $where_3 = array(
			    	'pro_id'      => $production_id,
			    	'pro_auto_id' => $auto_id,
			    	'product_id'  => $product_id,
			    	'type_id'     => $type_id,
			    	'method'      => '_listAdminProductionStockDetails'
			    );

			    $stock_val  = avul_call(API_URL.'production/api/manage_production_stock',$where_3);
			    $stock_data = $stock_val['data'];

			    $page['production_data'] = $productDetails_data;
			    $page['stock_data']      = $stock_data;
				$page['main_heading']    = "Work order";
				$page['sub_heading']     = "Manage Work order";
				$page['page_title']      = "Work order Invoice";
				$page['pre_title']       = "Work order";
				$page['pre_menu']        = "index.php/vendors/production/production_stock/stock_list/$production_id";
				$data['page_temp']       = $this->load->view('vendors/production/stock_add',$page,TRUE);
				$data['view_file']       = "Page_Template";
				$data['currentmenu']     = "list_production";
				$this->bassthaya->load_vendors_form_template($data);
			}

			if($param1 =='stock_list')
			{
				$production_id = $param2;

				// Product Details
				$where_1 = array(
			    	'production_id' => $production_id,
			    	'method'        => '_adminProductionDetails'
			    );

			    $product_val = avul_call(API_URL.'production/api/list_production',$where_1);

			    $page['production_data'] = $product_val['data'];
				$page['main_heading']    = "Work order";
				$page['sub_heading']     = "Manage Work order";
				$page['page_title']      = "Work order Invoice";
				$page['pre_title']       = "Work order";
				$page['pre_menu']        = "index.php/vendors/production/list_production";
				$data['page_temp']       = $this->load->view('vendors/production/production_stock',$page,TRUE);
				$data['view_file']       = "Page_Template";
				$data['currentmenu']     = "list_production";
				$this->bassthaya->load_vendors_form_template($data);
			}

			if($param1 == 'delete')
			{
				$id = $this->input->post('id');

				if(!empty($id))	
				{
					$data = array(
				    	'stock_id' => $id,
				    	'method'   => '_deleteAdminProductionStockDetails'
				    );

				    $data_save = avul_call(API_URL.'production/api/manage_production_stock',$data);

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
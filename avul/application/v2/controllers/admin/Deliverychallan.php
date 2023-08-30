<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Deliverychallan extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function dis_overall_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');

			if($param1 == '')
			{
				$page['main_heading']  = "Delivery Challan";
				$page['sub_heading']   = "Delivery Challan";
				$page['page_title']    = "Overall Order";
				$page['pre_title']     = "Add Purchase";
				$page['page_access']   = userAccess('distributors-order-view');
				$page['load_data']     = "";
				$page['function_name'] = "dis_overall_order";
				$page['pre_menu']      = "index.php/admin/delivery/list_order";
				$data['page_temp']     = $this->load->view('admin/delivery/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dc_overall_order";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('distributors-order-view'))
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
	            		'financial_year' => $this->session->userdata('active_year'),
	            		'method'         => '_listDeliveryPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'distributordelivery/api/manage_delivery',$where);
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

		            		$order_id       = !empty($value['order_id'])?$value['order_id']:'';
				            $order_no       = !empty($value['order_no'])?$value['order_no']:'';
				            $distributor_id = !empty($value['distributor_id'])?$value['distributor_id']:'';
				            $company_name   = !empty($value['company_name'])?$value['company_name']:'';
				            $order_date     = !empty($value['order_date'])?$value['order_date']:'';
				            $order_status   = !empty($value['order_status'])?$value['order_status']:'';
				            $_ordered       = !empty($value['_ordered'])?$value['_ordered']:'';
				            $financial_year = !empty($value['financial_year'])?$value['financial_year']:'';
				            $bill           = !empty($value['bill'])?$value['bill']:'';
				            $dc_number      = !empty($value['dc_number'])?$value['dc_number']:'---';
				            $published      = !empty($value['published'])?$value['published']:'';
				            $active_status  = !empty($value['status'])?$value['status']:'';
				            $createdate     = !empty($value['createdate'])?$value['createdate']:'';

						    if($active_status == '1')
			                {
			                	$status_view = '<span class="badge badge-success">Active</span>';
			                }
			                else
			                {
			                	$status_view = '<span class="badge badge-danger">In Active</span>';
			                }

			                // Order Status
						    if($order_status == '1')
						    {
						        $order_view = '<span class="badge badge-success">Success</span>';

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="deliverychallan" data-func="order_process" data-id="'.$order_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
						    }
						    else if($order_status == '2')
						    {
						        $order_view = '<span class="badge badge-warning">Approved</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '3')
						    {
						        $order_view = '<span class="badge badge-primary">Packing</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '4')
						    {
						        $order_view = '<span class="badge badge-info">Invoice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '10')
						    {
						        $order_view = '<span class="badge badge-warning">Shipping</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '11')
						    {
						        $order_view = '<span class="badge badge-primary">Delivered</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '5')
						    {
						        $order_view = '<span class="badge badge-success">Complete</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '7')
						    {
						        $order_view = '<span class="badge badge-danger">Cancel Invice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else
						    {
						        $order_view = '<span class="badge badge-danger">Cancel</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    $edit   = '';
				            $view = '';
				            if(userAccess('distributors-order-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('distributors-order-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/deliverychallan/dis_overall_order/view/'.$order_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$order_no.'</td>
	                                <td class="line_height">'.$dc_number.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	endif;
	                            $table .='</tr>
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

			else if($param1 == 'view')
			{
				$order_id = $param2;

				$where = array(
			    	'order_id'  => $order_id,
			    	'view_type' => 1,
			    	'method'    => '_viewDistributorDelivery'
			    );

			    $data_list  = avul_call(API_URL.'distributordelivery/api/manage_delivery',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Delivery challan";
				$page['sub_heading']   = "Manage Delivery challan";
				$page['page_title']    = "Delivery challan";
				$page['pre_title']     = "Delivery challan";
				$page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/admin/deliverychallan/view_order";
				$data['page_temp']     = $this->load->view('admin/delivery/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dc_overall_order";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function order_process($param1="", $param2="", $param3="")
		{
			$method = $this->input->post('method');

			if($method == 'changeOrder_status')
			{
				$order_id = $this->input->post('order_id');

				$order_data = array(
		    		'auto_id'  => $order_id,
					'progress' => '2',
					'method'   => '_updateOrderProgress',
				);

				$data_save = avul_call(API_URL.'distributordelivery/api/order_process',$order_data);

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

			else if($method == '_changePackProcess')
			{
				$error    = FALSE;
				$required = array('order_id');
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
			    	$order_id = $this->input->post('order_id');

			    	$order_data = array(
			    		'auto_id' => $order_id,
						'method'  => '_changePackProcess',
					);

					$data_save = avul_call(API_URL.'distributorpurchase/api/order_process',$order_data);

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

			else if($method == '_deletePackStatus')
			{
    			$error    = FALSE;
				$required = array('order_id');
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
			    	$order_id = $this->input->post('order_id');

			    	$order_data = array(
			    		'auto_id' => $order_id,
						'method'  => '_deletePackStatus',
					);

					$data_save = avul_call(API_URL.'distributorpurchase/api/order_process',$order_data);

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

			if($param1 == 'changeOrder_process')
			{
				$error        = FALSE;
				$order_id     = $this->input->post('order_id');
				$dc_id        = $this->input->post('dc_id');
			    $order_status = $this->input->post('order_status');
			    $message      = $this->input->post('message');
			    $length       = $this->input->post('length');
			    $breadth      = $this->input->post('breadth');
			    $height       = $this->input->post('height');
			    $weight       = $this->input->post('weight');

				$required = array('order_id', 'order_status');
				if($order_status == 8)
				{
					array_push($required, 'message');
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
			    	$order_data = array(
			    		'auto_id'    => $order_id,
			    		'dc_id'      => $dc_id,
						'progress'   => $order_status,
						'reason'     => $message,
						'length'     => $length,
						'breadth'    => $breadth,
						'height'     => $height,
						'weight'     => $weight,
						'method'     => '_updateOrderProgress',
					);

					$data_save = avul_call(API_URL.'distributordelivery/api/order_process',$order_data);

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

			else if($param1 == 'order_update')
			{	
				$error = FALSE;
				$id    = $this->input->post('id');
				$rate  = $this->input->post('rate');
				$qty   = $this->input->post('qty');

				$required = array('id', 'rate', 'qty');
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
			    		'auto_id'  => $id,
						'price'    => $rate,
						'quantity' => $qty,
						'method'   => '_updateOrderDetails',
					);

					$update = avul_call(API_URL.'distributordelivery/api/order_process',$order_data);

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

			else if($param1 == '_changePackStatus')
			{
				$error    = FALSE;
				$required = array('order_id');
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
			    	$order_id = $this->input->post('order_id');

			    	$order_data = array(
			    		'auto_id' => $order_id,
						'method'  => '_changePackStatus',
					);

					$data_save = avul_call(API_URL.'distributorpurchase/api/order_process',$order_data);

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

			else if($param1 == 'delete')
    		{
    			$error = FALSE;
    			$auto_id  = $this->input->post('id');
    			$progress = $this->input->post('progress');

    			$required = array('id', 'progress');
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
			    	if($progress == '1')
			    	{
			    		$data = array(
					    	'auto_id' => $auto_id,
					    	'method'  => '_DeleteOrderDetails',
					    );

					    $data_save = avul_call(API_URL.'distributordelivery/api/order_process',$data);

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
		}

		public function order_stock($param1="", $param2="", $param3="")
    	{
    		if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			$formpage = $this->input->post('formpage');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$received_date = $this->input->post('received_date');
				$received_qty  = $this->input->post('received_qty');
				$order_id      = $this->input->post('order_id');
				$auto_id       = $this->input->post('auto_id');
				$product_id    = $this->input->post('product_id');
				$type_id       = $this->input->post('type_id');
				$method        = $this->input->post('method');

				$required = array('received_date', 'received_qty', 'auto_id', 'order_id', 'product_id', 'type_id');
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
			    	$stock_data = array(
			    		'po_id'         => $order_id,
			    		'po_auto_id'    => $auto_id,
			    		'product_id'    => $product_id,
			    		'type_id'       => $type_id,
			    		'received_date' => $received_date,
			    		'received_qty'  => $received_qty,
			    		'method'        => '_addOrderStockDetails',
			    	);

			    	$data_save  = avul_call(API_URL.'distributorpurchase/api/manage_order_stock',$stock_data);

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

			if($param1 =='stock_add')
			{
				$order_id = $param3;
				$auto_id  = $param2;

				// Order details
				$order_details = array(
					'po_id'      => $order_id,
					'po_auto_id' => $auto_id,
					'method'     => '_detailOrderStockDetails',
				);

				$order_data  = avul_call(API_URL.'distributorpurchase/api/manage_order_stock',$order_details);

				$order_value = $order_data['data'];

				$product_id = !empty($order_value['product_id'])?$order_value['product_id']:'';
    			$type_id    = !empty($order_value['type_id'])?$order_value['type_id']:'';

				// Stock entry list
				$stock_details = array(
					'po_id'      => $order_id,
					'po_auto_id' => $auto_id,
					'product_id' => $product_id,
					'type_id'    => $type_id,
					'method'     => '_listOrderStockDetails',
				);

				$stock_data  = avul_call(API_URL.'distributorpurchase/api/manage_order_stock',$stock_details);

				$stock_value = $stock_data['data'];

				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Manage Outlet Order";
				$page['page_title']    = "Add Stock";
				$page['pre_title']     = "View Order";
				$page['order_value']   = $order_value;
				$page['stock_value']   = $stock_value;
				$page['pre_menu']      = "index.php/admin/distributorsorder/dis_packing_order/View/$order_id";
				$data['page_temp']     = $this->load->view('admin/distributors/stock_add',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_packing_order";
				$this->bassthaya->load_admin_form_template($data);
			}

			if($param1 == 'delete')
			{
				$id = $this->input->post('id');

				if(!empty($id))	
				{
					$data = array(
				    	'stock_id' => $id,
				    	'method'   => '_deleteOrderStockDetails'
				    );

				    $data_save = avul_call(API_URL.'distributorpurchase/api/manage_order_stock',$data);

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
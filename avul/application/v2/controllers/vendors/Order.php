<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Order extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

    	public function process_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$vendor_id      = $this->session->userdata('id');
        	$distributor_id = $this->session->userdata('distributor_id');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Approved Order";
				$page['pre_title']     = "";
				$page['load_data']     = "2";
				$page['function_name'] = "process_order";
				$page['pre_menu']      = "index.php/vendors/order/list_order";
				$data['page_temp']     = $this->load->view('vendors/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "process_order";
				$this->bassthaya->load_vendors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				$load_data = $this->input->post('load_data');
				$limit     = $this->input->post('limitval');
            	$page      = $this->input->post('page');
            	$search    = $this->input->post('search');
            	$cur_page  = isset($page)?$page:'1';
            	$_offset   = ($cur_page-1) * $limit;
            	$nxt_page  = $cur_page + 1;
            	$pre_page  = $cur_page - 1;

            	$where = array(
            		'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
            		'load_data'      => $load_data,
            		'vendor_id'      => $vendor_id,
            		'financial_year' => $this->session->userdata('active_year'),
            		'method'         => '_listVendorOrderPaginate'
            	);

            	$data_list  = avul_call(API_URL.'order/api/vendor_manage_order',$where);
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
            			$order_id     = !empty($value['order_id'])?$value['order_id']:'';
			            $order_no     = !empty($value['order_no'])?$value['order_no']:'';
			            $emp_name     = !empty($value['emp_name'])?$value['emp_name']:'';
			            $store_name   = !empty($value['store_name'])?$value['store_name']:'';
			            $contact_name = !empty($value['contact_name'])?$value['contact_name']:'';
			            $order_status = !empty($value['order_status'])?$value['order_status']:'';
			            $_ordered     = !empty($value['_ordered'])?$value['_ordered']:'';
			            $_processing  = !empty($value['_processing'])?$value['_processing']:'';
			            $_shiped      = !empty($value['_shiped'])?$value['_shiped']:'';
			            $_canceled    = !empty($value['_canceled'])?$value['_canceled']:'';
			            $_delivery    = !empty($value['_delivery'])?$value['_delivery']:'';
			            $random_value = !empty($value['random_value'])?$value['random_value']:'';
			            $published    = !empty($value['published'])?$value['published']:'';
			            $status       = !empty($value['status'])?$value['status']:'';
			            $createdate   = !empty($value['createdate'])?$value['createdate']:'';

			            // Order Status
			            if($order_status == '1')
					    {
					        $order_view = '<span class="badge badge-success">Success</span>';
					    }
					    else if($order_status == '2')
					    {
					        $order_view = '<span class="badge badge-warning">Approved</span>';
					    }
					    else if($order_status == '3')
					    {
					        $order_view = '<span class="badge badge-primary">Packing</span>';
					    }
					    else if($order_status == '4')
					    {
					        $order_view = '<span class="badge badge-info">Shipping</span>';
					    }
					    else if($order_status == '5')
					    {
					        $order_view = '<span class="badge badge-warning">Invoice</span>';
					    }
					    else if($order_status == '6')
					    {
					        $order_view = '<span class="badge badge-success">Delivered</span>';
					    }
					    else if($order_status == '7')
					    {
					        $order_view = '<span class="badge badge-success">Complete</span>';
					    }
					    else
					    {
					        $order_view = '<span class="badge badge-danger">Cancel</span>';
					    }

		                $table .= '
					    	<tr>
                                <td>'.$order_no.'</td>
		                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
		                        <td>'.mb_strimwidth($emp_name, '0', '12', '...').'</td>
                                <td style="display:none;">'.mb_strimwidth($store_name, '0', '23', '...').'</td>
		                        <td>'.$order_view.'</td>
		                        <td><a href="'.BASE_URL.'index.php/vendors/order/process_order/view/'.$random_value.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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

			else if($param1 == 'view')
			{
				$random_value = $param2;

				$where = array(
					'vendor_id'    => $vendor_id,
			    	'random_value' => $random_value,
			    	'method'       => '_detailVendorOrder'
			    );

				$data_val = avul_call(API_URL.'order/api/vendor_manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Process Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/vendors/order/process_order";
				$data['page_temp']     = $this->load->view('vendors/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "process_order";
				$this->bassthaya->load_vendors_form_template($data);
			}
    	}

    	public function packing_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$vendor_id      = $this->session->userdata('id');
        	$distributor_id = $this->session->userdata('distributor_id');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Packing Order";
				$page['pre_title']     = "";
				$page['load_data']     = "3";
				$page['function_name'] = "packing_order";
				$page['pre_menu']      = "index.php/vendors/order/list_order";
				$data['page_temp']     = $this->load->view('vendors/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "packing_order";
				$this->bassthaya->load_vendors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				$load_data = $this->input->post('load_data');
				$limit     = $this->input->post('limitval');
            	$page      = $this->input->post('page');
            	$search    = $this->input->post('search');
            	$cur_page  = isset($page)?$page:'1';
            	$_offset   = ($cur_page-1) * $limit;
            	$nxt_page  = $cur_page + 1;
            	$pre_page  = $cur_page - 1;

            	$where = array(
            		'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
            		'load_data'      => $load_data,
            		'vendor_id'      => $vendor_id,
            		'financial_year' => $this->session->userdata('active_year'),
            		'method'         => '_listVendorOrderPaginate'
            	);

            	$data_list  = avul_call(API_URL.'order/api/vendor_manage_order',$where);
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
            			$order_id     = !empty($value['order_id'])?$value['order_id']:'';
			            $order_no     = !empty($value['order_no'])?$value['order_no']:'';
			            $emp_name     = !empty($value['emp_name'])?$value['emp_name']:'';
			            $store_name   = !empty($value['store_name'])?$value['store_name']:'';
			            $contact_name = !empty($value['contact_name'])?$value['contact_name']:'';
			            $order_status = !empty($value['order_status'])?$value['order_status']:'';
			            $_ordered     = !empty($value['_ordered'])?$value['_ordered']:'';
			            $_processing  = !empty($value['_processing'])?$value['_processing']:'';
			            $_shiped      = !empty($value['_shiped'])?$value['_shiped']:'';
			            $_canceled    = !empty($value['_canceled'])?$value['_canceled']:'';
			            $_delivery    = !empty($value['_delivery'])?$value['_delivery']:'';
			            $random_value = !empty($value['random_value'])?$value['random_value']:'';
			            $published    = !empty($value['published'])?$value['published']:'';
			            $status       = !empty($value['status'])?$value['status']:'';
			            $createdate   = !empty($value['createdate'])?$value['createdate']:'';

			            // Order Status
			            if($order_status == '1')
					    {
					        $order_view = '<span class="badge badge-success">Success</span>';
					    }
					    else if($order_status == '2')
					    {
					        $order_view = '<span class="badge badge-warning">Approved</span>';
					    }
					    else if($order_status == '3')
					    {
					        $order_view = '<span class="badge badge-primary">Packing</span>';
					    }
					    else if($order_status == '4')
					    {
					        $order_view = '<span class="badge badge-info">Shipping</span>';
					    }
					    else if($order_status == '5')
					    {
					        $order_view = '<span class="badge badge-warning">Invoice</span>';
					    }
					    else if($order_status == '6')
					    {
					        $order_view = '<span class="badge badge-success">Delivered</span>';
					    }
					    else if($order_status == '7')
					    {
					        $order_view = '<span class="badge badge-success">Complete</span>';
					    }
					    else
					    {
					        $order_view = '<span class="badge badge-danger">Cancel</span>';
					    }

		                $table .= '
					    	<tr>
                                <td>'.$order_no.'</td>
		                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
		                        <td>'.mb_strimwidth($emp_name, '0', '12', '...').'</td>
                                <td style="display:none;">'.mb_strimwidth($store_name, '0', '23', '...').'</td>
		                        <td>'.$order_view.'</td>
		                        <td><a href="'.BASE_URL.'index.php/vendors/order/packing_order/view/'.$random_value.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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

			else if($param1 == 'view')
			{
				$random_value = $param2;

				$where = array(
					'vendor_id'    => $vendor_id,
			    	'random_value' => $random_value,
			    	'method'       => '_detailVendorOrder'
			    );

				$data_val = avul_call(API_URL.'order/api/vendor_manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Packing Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/vendors/order/packing_order";
				$data['page_temp']     = $this->load->view('vendors/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "packing_order";
				$this->bassthaya->load_vendors_form_template($data);
			}

			else if($param1 == 'stock_list')
			{
				$auto_id  = $param2;
				$order_id = $param3;

				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Packing Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/vendors/order/packing_order";
				$data['page_temp']     = $this->load->view('vendors/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "packing_order";
				$this->bassthaya->load_vendors_form_template($data);
			}
    	}

    	public function shipping_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$vendor_id      = $this->session->userdata('id');
        	$distributor_id = $this->session->userdata('distributor_id');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Shipping Order";
				$page['pre_title']     = "";
				$page['load_data']     = "4";
				$page['function_name'] = "shipping_order";
				$page['pre_menu']      = "index.php/vendors/order/list_order";
				$data['page_temp']     = $this->load->view('vendors/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "shipping_order";
				$this->bassthaya->load_vendors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				$load_data = $this->input->post('load_data');
				$limit     = $this->input->post('limitval');
            	$page      = $this->input->post('page');
            	$search    = $this->input->post('search');
            	$cur_page  = isset($page)?$page:'1';
            	$_offset   = ($cur_page-1) * $limit;
            	$nxt_page  = $cur_page + 1;
            	$pre_page  = $cur_page - 1;

            	$where = array(
            		'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
            		'load_data'      => $load_data,
            		'vendor_id'      => $vendor_id,
            		'financial_year' => $this->session->userdata('active_year'),
            		'method'         => '_listVendorOrderPaginate'
            	);

            	$data_list  = avul_call(API_URL.'order/api/vendor_manage_order',$where);
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
            			$order_id     = !empty($value['order_id'])?$value['order_id']:'';
			            $order_no     = !empty($value['order_no'])?$value['order_no']:'';
			            $emp_name     = !empty($value['emp_name'])?$value['emp_name']:'';
			            $store_name   = !empty($value['store_name'])?$value['store_name']:'';
			            $contact_name = !empty($value['contact_name'])?$value['contact_name']:'';
			            $order_status = !empty($value['order_status'])?$value['order_status']:'';
			            $_ordered     = !empty($value['_ordered'])?$value['_ordered']:'';
			            $_processing  = !empty($value['_processing'])?$value['_processing']:'';
			            $_shiped      = !empty($value['_shiped'])?$value['_shiped']:'';
			            $_canceled    = !empty($value['_canceled'])?$value['_canceled']:'';
			            $_delivery    = !empty($value['_delivery'])?$value['_delivery']:'';
			            $random_value = !empty($value['random_value'])?$value['random_value']:'';
			            $published    = !empty($value['published'])?$value['published']:'';
			            $status       = !empty($value['status'])?$value['status']:'';
			            $createdate   = !empty($value['createdate'])?$value['createdate']:'';

			            // Order Status
			            if($order_status == '1')
					    {
					        $order_view = '<span class="badge badge-success">Success</span>';
					    }
					    else if($order_status == '2')
					    {
					        $order_view = '<span class="badge badge-warning">Approved</span>';
					    }
					    else if($order_status == '3')
					    {
					        $order_view = '<span class="badge badge-primary">Packing</span>';
					    }
					    else if($order_status == '4')
					    {
					        $order_view = '<span class="badge badge-info">Shipping</span>';
					    }
					    else if($order_status == '5')
					    {
					        $order_view = '<span class="badge badge-warning">Invoice</span>';
					    }
					    else if($order_status == '6')
					    {
					        $order_view = '<span class="badge badge-success">Delivered</span>';
					    }
					    else if($order_status == '7')
					    {
					        $order_view = '<span class="badge badge-success">Complete</span>';
					    }
					    else
					    {
					        $order_view = '<span class="badge badge-danger">Cancel</span>';
					    }

		                $table .= '
					    	<tr>
                                <td>'.$order_no.'</td>
		                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
		                        <td>'.mb_strimwidth($emp_name, '0', '12', '...').'</td>
                                <td style="display:none;">'.mb_strimwidth($store_name, '0', '23', '...').'</td>
		                        <td>'.$order_view.'</td>
		                        <td><a href="'.BASE_URL.'index.php/vendors/order/shipping_order/view/'.$random_value.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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

			else if($param1 == 'view')
			{
				$random_value = $param2;

				$where = array(
					'vendor_id'    => $vendor_id,
			    	'random_value' => $random_value,
			    	'method'       => '_detailVendorOrder'
			    );

				$data_val = avul_call(API_URL.'order/api/vendor_manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Shipping Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/vendors/order/shipping_order";
				$data['page_temp']     = $this->load->view('vendors/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "shipping_order";
				$this->bassthaya->load_vendors_form_template($data);
			}
    	}
    	
    	public function invoice_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$vendor_id      = $this->session->userdata('id');
        	$distributor_id = $this->session->userdata('distributor_id');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Invoice Order";
				$page['pre_title']     = "";
				$page['load_data']     = "5";
				$page['function_name'] = "invoice_order";
				$page['pre_menu']      = "index.php/vendors/order/list_order";
				$data['page_temp']     = $this->load->view('vendors/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "invoice_order";
				$this->bassthaya->load_vendors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				$load_data = $this->input->post('load_data');
				$limit     = $this->input->post('limitval');
            	$page      = $this->input->post('page');
            	$search    = $this->input->post('search');
            	$cur_page  = isset($page)?$page:'1';
            	$_offset   = ($cur_page-1) * $limit;
            	$nxt_page  = $cur_page + 1;
            	$pre_page  = $cur_page - 1;

            	$where = array(
            		'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
            		'load_data'      => $load_data,
            		'vendor_id'      => $vendor_id,
            		'financial_year' => $this->session->userdata('active_year'),
            		'method'         => '_listVendorOrderPaginate'
            	);

            	$data_list  = avul_call(API_URL.'order/api/vendor_manage_order',$where);
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
            			$order_id     = !empty($value['order_id'])?$value['order_id']:'';
			            $order_no     = !empty($value['order_no'])?$value['order_no']:'';
			            $emp_name     = !empty($value['emp_name'])?$value['emp_name']:'';
			            $store_name   = !empty($value['store_name'])?$value['store_name']:'';
			            $contact_name = !empty($value['contact_name'])?$value['contact_name']:'';
			            $order_status = !empty($value['order_status'])?$value['order_status']:'';
			            $_ordered     = !empty($value['_ordered'])?$value['_ordered']:'';
			            $_processing  = !empty($value['_processing'])?$value['_processing']:'';
			            $_shiped      = !empty($value['_shiped'])?$value['_shiped']:'';
			            $_canceled    = !empty($value['_canceled'])?$value['_canceled']:'';
			            $_delivery    = !empty($value['_delivery'])?$value['_delivery']:'';
			            $random_value = !empty($value['random_value'])?$value['random_value']:'';
			            $published    = !empty($value['published'])?$value['published']:'';
			            $status       = !empty($value['status'])?$value['status']:'';
			            $createdate   = !empty($value['createdate'])?$value['createdate']:'';

			            if($order_status == '1')
					    {
					        $order_view = '<span class="badge badge-success">Success</span>';
					    }
					    else if($order_status == '2')
					    {
					        $order_view = '<span class="badge badge-warning">Approved</span>';
					    }
					    else if($order_status == '3')
					    {
					        $order_view = '<span class="badge badge-primary">Packing</span>';
					    }
					    else if($order_status == '4')
					    {
					        $order_view = '<span class="badge badge-info">Shipping</span>';
					    }
					    else if($order_status == '5')
					    {
					        $order_view = '<span class="badge badge-warning">Invoice</span>';
					    }
					    else if($order_status == '6')
					    {
					        $order_view = '<span class="badge badge-success">Delivered</span>';
					    }
					    else if($order_status == '7')
					    {
					        $order_view = '<span class="badge badge-success">Complete</span>';
					    }
					    else
					    {
					        $order_view = '<span class="badge badge-danger">Cancel</span>';
					    }

		                $table .= '
					    	<tr>
                                <td>'.$order_no.'</td>
		                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
		                        <td>'.mb_strimwidth($emp_name, '0', '12', '...').'</td>
                                <td style="display:none;">'.mb_strimwidth($store_name, '0', '23', '...').'</td>
		                        <td>'.$order_view.'</td>
		                        <td><a href="'.BASE_URL.'index.php/vendors/order/invoice_order/view/'.$random_value.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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

			else if($param1 == 'view')
			{
				$random_value = $param2;

				$where = array(
					'vendor_id'    => $vendor_id,
			    	'random_value' => $random_value,
			    	'method'       => '_detailVendorOrder'
			    );

				$data_val = avul_call(API_URL.'order/api/vendor_manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Invoice Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/vendors/order/invoice_order";
				$data['page_temp']     = $this->load->view('vendors/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "invoice_order";
				$this->bassthaya->load_vendors_form_template($data);
			}
    	}

    	public function delivery_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$vendor_id      = $this->session->userdata('id');
        	$distributor_id = $this->session->userdata('distributor_id');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Delivered Order";
				$page['pre_title']     = "";
				$page['load_data']     = "6";
				$page['function_name'] = "delivery_order";
				$page['pre_menu']      = "index.php/vendors/order/list_order";
				$data['page_temp']     = $this->load->view('vendors/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "delivery_order";
				$this->bassthaya->load_vendors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				$load_data = $this->input->post('load_data');
				$limit     = $this->input->post('limitval');
            	$page      = $this->input->post('page');
            	$search    = $this->input->post('search');
            	$cur_page  = isset($page)?$page:'1';
            	$_offset   = ($cur_page-1) * $limit;
            	$nxt_page  = $cur_page + 1;
            	$pre_page  = $cur_page - 1;

            	$where = array(
            		'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
            		'load_data'      => $load_data,
            		'vendor_id'      => $vendor_id,
            		'financial_year' => $this->session->userdata('active_year'),
            		'method'         => '_listVendorOrderPaginate'
            	);

            	$data_list  = avul_call(API_URL.'order/api/vendor_manage_order',$where);
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
            			$order_id     = !empty($value['order_id'])?$value['order_id']:'';
			            $order_no     = !empty($value['order_no'])?$value['order_no']:'';
			            $emp_name     = !empty($value['emp_name'])?$value['emp_name']:'';
			            $store_name   = !empty($value['store_name'])?$value['store_name']:'';
			            $contact_name = !empty($value['contact_name'])?$value['contact_name']:'';
			            $order_status = !empty($value['order_status'])?$value['order_status']:'';
			            $_ordered     = !empty($value['_ordered'])?$value['_ordered']:'';
			            $_processing  = !empty($value['_processing'])?$value['_processing']:'';
			            $_shiped      = !empty($value['_shiped'])?$value['_shiped']:'';
			            $_canceled    = !empty($value['_canceled'])?$value['_canceled']:'';
			            $_delivery    = !empty($value['_delivery'])?$value['_delivery']:'';
			            $random_value = !empty($value['random_value'])?$value['random_value']:'';
			            $published    = !empty($value['published'])?$value['published']:'';
			            $status       = !empty($value['status'])?$value['status']:'';
			            $createdate   = !empty($value['createdate'])?$value['createdate']:'';

			            // Order Status
			            if($order_status == '1')
					    {
					        $order_view = '<span class="badge badge-success">Success</span>';
					    }
					    else if($order_status == '2')
					    {
					        $order_view = '<span class="badge badge-warning">Approved</span>';
					    }
					    else if($order_status == '3')
					    {
					        $order_view = '<span class="badge badge-primary">Packing</span>';
					    }
					    else if($order_status == '4')
					    {
					        $order_view = '<span class="badge badge-info">Shipping</span>';
					    }
					    else if($order_status == '5')
					    {
					        $order_view = '<span class="badge badge-warning">Invoice</span>';
					    }
					    else if($order_status == '6')
					    {
					        $order_view = '<span class="badge badge-success">Delivered</span>';
					    }
					    else if($order_status == '7')
					    {
					        $order_view = '<span class="badge badge-success">Complete</span>';
					    }
					    else
					    {
					        $order_view = '<span class="badge badge-danger">Cancel</span>';
					    }

		                $table .= '
					    	<tr>
                                <td>'.$order_no.'</td>
		                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
		                        <td>'.mb_strimwidth($emp_name, '0', '12', '...').'</td>
                                <td style="display:none;">'.mb_strimwidth($store_name, '0', '23', '...').'</td>
		                        <td>'.$order_view.'</td>
		                        <td><a href="'.BASE_URL.'index.php/vendors/order/delivery_order/view/'.$random_value.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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

			else if($param1 == 'view')
			{
				$random_value = $param2;

				$where = array(
					'vendor_id'    => $vendor_id,
			    	'random_value' => $random_value,
			    	'method'       => '_detailVendorOrder'
			    );

				$data_val = avul_call(API_URL.'order/api/vendor_manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Delivered Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/vendors/order/delivery_order";
				$data['page_temp']     = $this->load->view('vendors/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "delivery_order";
				$this->bassthaya->load_vendors_form_template($data);
			}
    	}

    	public function order_process($param1="", $param2="", $param3="")
    	{
    		if($param1 == 'changeOrder_process')
    		{
    			$error = FALSE;
    			$order_id     = $this->input->post('order_id');
    			$vendor_id    = $this->input->post('vendor_id');
				$order_status = $this->input->post('order_status');

				$required = array('order_id', 'vendor_id', 'order_status');
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
			    		'auto_id'     => $order_id,
			    		'vendor_id'   => $vendor_id,
						'progress'    => $order_status,
						'submit_type' => 2,
						'method'      => '_updateVendorOrderProgress',
					);

					$data_save = avul_call(API_URL.'order/api/order_process',$order_data);

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

    		else if($param1 == '_subadminOrderlist')
    		{
    			$error = FALSE;
    			$id    = $this->input->post('id');
    			$qty   = $this->input->post('qty');

    			$required = array('id', 'qty');
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
			    		'quantity' => $qty,
			    		'method'   => '_updateOrderDetails',
			    	);

			    	$data_save = avul_call(API_URL.'order/api/manage_order_details',$order_data);

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
    			$id    = $this->input->post('id');

    			if(!empty($id))
    			{
    				$order_data = array(
			    		'auto_id'  => $id,
			    		'method'   => '_deleteOrderDetails',
			    	);

			    	$data_save = avul_call(API_URL.'order/api/manage_order_details',$order_data);

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
				$product_unit  = $this->input->post('product_unit');
				$method        = $this->input->post('method');

				$required = array('received_date', 'received_qty', 'auto_id', 'order_id', 'product_id', 'type_id', 'product_unit');
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
			    		'order_id'      => $order_id,
			    		'order_auto_id' => $auto_id,
			    		'product_id'    => $product_id,
			    		'type_id'       => $type_id,
			    		'product_unit'  => $product_unit,
			    		'received_date' => $received_date,
			    		'received_qty'  => $received_qty,
			    		'method'        => '_addVendorOrderStockDetails',
			    	);

			    	$data_save  = avul_call(API_URL.'order/api/manage_order_stock',$stock_data);

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
					'order_id' => $order_id,
					'method'   => '_orderData',
				);

				$order_data  = avul_call(API_URL.'order/api/manage_order',$order_details);
				$order_value = $order_data['data'];

				$order_no     = !empty($order_value['order_no'])?$order_value['order_no']:'';
    			$random_value = !empty($order_value['random_value'])?$order_value['random_value']:'';

    			// Order Product Details
    			$product_details = array(
    				'auto_id'  => $auto_id,
    				'order_id' => $order_id,
    				'method'   => '_orderProductData',
    			);

    			$product_data  = avul_call(API_URL.'order/api/manage_order',$product_details);
				$product_value = $product_data['data'];

				$product_id = !empty($product_value['product_id'])?$product_value['product_id']:'';
			    $type_id    = !empty($product_value['type_id'])?$product_value['type_id']:'';

				// Stock List
				$product_stock = array(
    				'order_id'      => $order_id,
    				'order_auto_id' => $auto_id,
    				'product_id'    => $product_id,
    				'type_id'       => $type_id,
    				'method'        => '_listOrderStockDetails',
    			);

    			$stock_data  = avul_call(API_URL.'order/api/manage_order_stock',$product_stock);
				$stock_value = $stock_data['data'];

				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Manage Outlet Order";
				$page['page_title']    = "Add Stock";
				$page['pre_title']     = "View Order";
				$page['order_no']      = $order_no;
				$page['product_value'] = $product_value;
				$page['stock_value']   = $stock_value;
				$page['pre_menu']      = "index.php/vendors/order/packing_order/view/$random_value";
				$data['page_temp']     = $this->load->view('vendors/order/stock_add',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "packing_order";
				$this->bassthaya->load_vendors_form_template($data);
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

				    $data_save = avul_call(API_URL.'order/api/manage_order_stock',$data);

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

    	public function print_invoice($param1="", $param2="", $param3="")
    	{
    		// Get Bamini Font 
            $font_loc  = $_SERVER['DOCUMENT_ROOT']."/project/2021/beta_retailvend/v10/fonts/Quicksand.ttf";

    		if(!empty($param1))
    		{
    			$bill_data = array(
    				'random_value' => $param1,
    				'method'       => '_detailInvoice',
    			);

    			$bill_val  = avul_call(API_URL.'order/api/invoice_manage_order', $bill_data);

    			if(!empty($bill_val))
    			{
    				$bill_value = $bill_val['data'];

    				$bill_details    = !empty($bill_value['bill_details'])?$bill_value['bill_details']:'';
    				$buyer_details   = !empty($bill_value['buyer_details'])?$bill_value['buyer_details']:'';
    				$distri_details  = !empty($bill_value['distributor_details'])?$bill_value['distributor_details']:'';
    				$store_details   = !empty($bill_value['store_details'])?$bill_value['store_details']:'';
    				$product_details = !empty($bill_value['product_details'])?$bill_value['product_details']:'';
    				$tax_details     = !empty($bill_value['tax_details'])?$bill_value['tax_details']:'';

    				// Bill Details
    				$invoice_id  = !empty($bill_details['invoice_id'])?$bill_details['invoice_id']:'';
    				$bill_type   = !empty($bill_details['bill_type'])?$bill_details['bill_type']:'';
                    $invoice_no  = !empty($bill_details['invoice_no'])?$bill_details['invoice_no']:'';
                    $vendor_id   = !empty($bill_details['vendor_id'])?$bill_details['vendor_id']:'';
                    $store_id    = !empty($bill_details['store_id'])?$bill_details['store_id']:'';
                    $due_days    = !empty($bill_details['due_days'])?$bill_details['due_days']:'';
                    $discount    = !empty($bill_details['discount'])?$bill_details['discount']:'0';
                    $outlet_type = !empty($bill_details['outlet_type'])?$bill_details['outlet_type']:'';
                    $createdate  = !empty($bill_details['createdate'])?$bill_details['createdate']:'';

                    // Buyer Details
                    $order_no = !empty($buyer_details['order_no'])?$buyer_details['order_no']:'';
            		$_ordered = !empty($buyer_details['_ordered'])?$buyer_details['_ordered']:'';

                    // Distributor Details
                    $dis_company_name = !empty($distri_details['company_name'])?$distri_details['company_name']:'';
                    $dis_gst_no       = !empty($distri_details['gst_no'])?$distri_details['gst_no']:'';
                    $dis_contact_no   = !empty($distri_details['contact_no'])?$distri_details['contact_no']:'';
                    $dis_email        = !empty($distri_details['email'])?$distri_details['email']:'';
                    $dis_tan_no       = !empty($distri_details['tan_no'])?$distri_details['tan_no']:'';
                    $dis_address      = !empty($distri_details['address'])?$distri_details['address']:'';
                    $dis_state_name   = !empty($distri_details['state_name'])?$distri_details['state_name']:'';
                    $dis_gst_code     = !empty($distri_details['gst_code'])?$distri_details['gst_code']:'';

                    // Store Details
                    $str_company_name = !empty($store_details['company_name'])?$store_details['company_name']:'';
                    $str_mobile       = !empty($store_details['mobile'])?$store_details['mobile']:'';
                    $str_email        = !empty($store_details['email'])?$store_details['email']:'';
                    $str_gst_no       = !empty($store_details['gst_no'])?$store_details['gst_no']:'';
                    $str_address      = !empty($store_details['address'])?$store_details['address']:'';
                    $str_country_id   = !empty($store_details['country_id'])?$store_details['country_id']:'';
                    $str_state_id     = !empty($store_details['state_id'])?$store_details['state_id']:'';
                    $str_city_id      = !empty($store_details['city_id'])?$store_details['city_id']:'';
                    $str_zone_id      = !empty($store_details['zone_id'])?$store_details['zone_id']:'';
                    $str_state_name   = !empty($store_details['state_name'])?$store_details['state_name']:'';
                    $str_gst_code     = !empty($store_details['gst_code'])?$store_details['gst_code']:'';
                    $str_outlet_type  = !empty($store_details['outlet_type'])?$store_details['outlet_type']:'';

                    if($bill_type == 1)
                    {
                    	$bill_view = 'COD';
                    }
                    else
                    {
                    	$bill_view = 'Credit';
                    }


    				$this->load->library('Pdf');
	          		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'mm', array(216,356), TRUE, 'UTF-8', FALSE);
		          	$pdf->SetTitle('Transfer Certificate');
		          	$pdf->SetPrintHeader(false);
		          	$pdf->SetPrintFooter(false);
		          		
		          	// Add Fonts
		          	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
					$fontname = TCPDF_FONTS::addTTFfont($font_loc, 'TrueTypeUnicode', '', 32);

		          	$pdf->SetFont($fontname, '', 9, '', false); 

		          	// Call before the addPage() method
					$pdf->SetPrintHeader(false);
					$pdf->SetPrintFooter(false);

		          	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		          	$pdf->SetFont('times');
		          	$pdf->AddPage('P');

		          	$html = '
		          		<table border="1">
		          			<tr>
						        <th colspan="3" style="text-align: center;">
						            <span style="font-family: Quicksand;">Tax Invoice</span><br>
						        </th>
						    </tr>
							<tr>
						        <th rowspan="3">
						            <span style="font-family: Quicksand;">Distributor Details</span><br>
						            <span style="font-family: Quicksand;">'.$dis_company_name.'</span><br>
						            <span style="font-family: Quicksand;">'.$dis_address.'</span><br>
						            <span style="font-family: Quicksand;">Contact No : '.$dis_contact_no.'</span><br>
						            <span style="font-family: Quicksand;">GSTIN/UIN : '.$dis_gst_no.'</span><br>
						            <span style="font-family: Quicksand;">State Name : '.$dis_state_name.'</span><br>
						            <span style="font-family: Quicksand;">State Name : '.$dis_gst_code.'</span>
						        </th>
						        <th>
						            <span style="font-family: Quicksand;">Invoice No.</span><br>
						            <span style="font-family: Quicksand;">'.$invoice_no.'</span>
						        </th>
						        <th>
						            <span style="font-family: Quicksand;">Dated</span><br>
						            <span style="font-family: Quicksand;">'.date('d-M-Y h:i:s A', strtotime($createdate)).'</span>
						        </th>
						    </tr>
						    <tr>
						    	<th>
						            <span style="font-family: Quicksand;">Delivery Note</span><br>
						        </th>
						        <th>
						            <span style="font-family: Quicksand;">Mode/Terms of Payment</span><br>
						            <span style="font-family: Quicksand;">'.$bill_view.'</span>
						        </th>
						    </tr>
						    <tr>
						    	<th>
						            <span style="font-family: Quicksand;">Supplier\'s Ref.</span><br>
						        </th>
						        <th>
						            <span style="font-family: Quicksand;">Other Reference(s)</span><br>
						        </th>
						    </tr>
						    <tr>
						        <th rowspan="4">
						            <span style="font-family: Quicksand;">Buyer Details</span><br>
						            <span style="font-family: Quicksand;">'.$str_company_name.'</span><br>
						            <span style="font-family: Quicksand;">'.$str_address.'</span><br>
						            <span style="font-family: Quicksand;">Contact No : '.$str_mobile.'</span><br>
						            <span style="font-family: Quicksand;">GSTIN/UIN : '.$str_gst_no.'</span><br>
						            <span style="font-family: Quicksand;">State Name : '.$str_state_name.'</span><br>
						            <span style="font-family: Quicksand;">State Name : '.$str_gst_code.'</span>
						        </th>
						        <th>
						            <span style="font-family: Quicksand;">Buyer\'s Order No.</span><br>
						            <span style="font-family: Quicksand;">'.$order_no.'</span>
						        </th>
						        <th>
						            <span style="font-family: Quicksand;">Dated</span><br>
						            <span style="font-family: Quicksand;">'.date('d-M-Y h:i:s A', strtotime($_ordered)).'</span>
						        </th>
						    </tr>
						    <tr>
						    	<th>
						            <span style="font-family: Quicksand;">Despatch Document No.</span><br>
						        </th>
						        <th>
						            <span style="font-family: Quicksand;">Delivery Note Date</span><br>
						        </th>
						    </tr>
						    <tr>
						    	<th>
						            <span style="font-family: Quicksand;">Despatched through</span><br>
						        </th>
						        <th>
						            <span style="font-family: Quicksand;">Destination</span><br>
						        </th>
						    </tr>
						    <tr>
						    	<th colspan="2">
						            <span style="font-family: Quicksand;">Terms of Delivery</span><br><br><br>
						        </th>
						    </tr>
						</table>
						<table border="1">
							<tr>
						    	<th width="5%">
						            <span style="font-family: Quicksand;">S.No</span><br>
						        </th>
						        <th width="42%">
						            <span style="font-family: Quicksand;">Description of Goods</span><br>
						        </th>
						        <th width="12%">
						            <span style="font-family: Quicksand;">HSN/SAC</span><br>
						        </th>
						        <th width="12%">
						            <span style="font-family: Quicksand;">Quantity</span><br>
						        </th>
						        <th width="12%">
						            <span style="font-family: Quicksand;">Rate</span><br>
						        </th>
						        <th width="5%">
						            <span style="font-family: Quicksand;">per</span><br>
						        </th>
						        <th width="12%">
						            <span style="font-family: Quicksand;">Amount</span><br>
						        </th>
						    </tr>';

						    if(!empty($product_details))
						    {
						    	$i=1;
						    	$total_qty = 0;
			                    $sub_total = 0;
			                    $total_gst = 0;
			                    $gst_value = 0;
						    	foreach ($product_details as $key => $value) {
				                    $desc      = !empty($value['description'])?$value['description']:'';
				                    $hsn_code  = !empty($value['hsn_code'])?$value['hsn_code']:'';
				                    $gst_val   = !empty($value['gst_val'])?$value['gst_val']:'0';
				                    $unit_val  = !empty($value['unit_val'])?$value['unit_val']:'0';
				                    $price     = !empty($value['price'])?$value['price']:'0';
				                    $order_qty = !empty($value['order_qty'])?$value['order_qty']:'0';

				                    $total_price  = $order_qty * $price;
			                        $total_qty   += $order_qty;
			                        $sub_total   += $total_price;

			                        // GST Calculation
			                        $gst_price  = $total_price * $gst_val / 100;
			                        $pre_gst    = $total_price - $gst_price;
			                        $total_gst += $pre_gst;
			                        $gst_value += $gst_price;

				                    $html .= '
				                    	<tr>
									    	<th width="5%">
									            <span style="font-family: Quicksand;">'.$i.'</span><br>
									        </th>
									        <th width="42%">
									            <span style="font-family: Quicksand;">'.$desc.'</span><br>
									        </th>
									        <th width="12%">
									            <span style="font-family: Quicksand;">'.$gst_val.'</span><br>
									        </th>
									        <th width="12%">
									            <span style="font-family: Quicksand;">'.$order_qty.'</span><br>
									        </th>
									        <th width="12%">
									            <span style="font-family: Quicksand;">'.$price.'</span><br>
									        </th>
									        <th width="5%">
									            <span style="font-family: Quicksand;">nos</span><br>
									        </th>
									        <th width="12%">
									            <span style="font-family: Quicksand;">'.number_format((float)$pre_gst, 2, '.', '').'</span><br>
									        </th>
									    </tr>
				                    ';

				                    $i++;
						    	}
						    }

						    $html .= '
						    	<tr>
						    		<th colspan="6" style="text-align: right;">
							            <span style="font-family: Quicksand;">Quantity </span><br>
							        </th>
							        <th colspan="6">
							            <span style="font-family: Quicksand;">'.$total_qty.' </span><br>
							        </th>
						    	</tr>
						    	<tr>
						    		<th colspan="6" style="text-align: right;">
							            <span style="font-family: Quicksand;">Sub Total </span><br>
							        </th>
							        <th colspan="6">
							            <span style="font-family: Quicksand;">'.number_format((float)$total_gst, 2, '.', '').' </span><br>
							        </th>
							    </tr>
						    ';
						    if($outlet_type == 1)
						    {
						    	$state_gst = $gst_value / 2;

						    	$html .= '
						    		<tr>
							    		<th colspan="6" style="text-align: right;">
								            <span style="font-family: Quicksand;">CGST </span><br>
								        </th>
								        <th colspan="6">
								            <span style="font-family: Quicksand;">'.number_format((float)$state_gst, 2, '.', '').' </span><br>
								        </th>
							    	</tr>
							    	<tr>
							    		<th colspan="6" style="text-align: right;">
								            <span style="font-family: Quicksand;">SGST </span><br>
								        </th>
								        <th colspan="6">
								            <span style="font-family: Quicksand;">'.number_format((float)$state_gst, 2, '.', '').' </span><br>
								        </th>
							    	</tr>
						    	';
						    }
						    else
						    {
						    	if($dis_gst_code == $str_gst_code)
						    	{
						    		$state_gst = $gst_value / 2;

						    		$html .= '
						    			<tr>
								    		<th colspan="6" style="text-align: right;">
									            <span style="font-family: Quicksand;">CGST </span><br>
									        </th>
									        <th colspan="6">
									            <span style="font-family: Quicksand;">'.number_format((float)$state_gst, 2, '.', '').' </span><br>
									        </th>
								    	</tr>
								    	<tr>
								    		<th colspan="6" style="text-align: right;">
									            <span style="font-family: Quicksand;">SGST </span><br>
									        </th>
									        <th colspan="6">
									            <span style="font-family: Quicksand;">'.number_format((float)$state_gst, 2, '.', '').' </span><br>
									        </th>
								    	</tr>
						    		';
						    	}
						    	else
						    	{
						    		$html .= '
						    			<tr>
								    		<th colspan="6" style="text-align: right;">
									            <span style="font-family: Quicksand;">IGST </span><br>
									        </th>
									        <th colspan="6">
									            <span style="font-family: Quicksand;">'.number_format((float)$gst_value, 2, '.', '').' </span><br>
									        </th>
								    	</tr>
						    		';
						    	}
						    }

						    if($discount != 0)
						    {
						    	$html .= '
						    		<tr>
							    		<th colspan="6" style="text-align: right;">
								            <span style="font-family: Quicksand;">Discount </span><br>
								        </th>
								        <th colspan="6">
								            <span style="font-family: Quicksand;">'.$discount.' </span><br>
								        </th>
							    	</tr>
						    	';
						    }

						    $total_val  = $sub_total - $discount;
		                    $last_total = round($total_val);
		                    $round_val  = $last_total - $total_val;

		                    $html .= '
		                    	<tr>
						    		<th colspan="6" style="text-align: right;">
							            <span style="font-family: Quicksand;">Round off </span><br>
							        </th>
							        <th colspan="6">
							            <span style="font-family: Quicksand;">'.$round_val.' </span><br>
							        </th>
						    	</tr>
						    	<tr>
						    		<th colspan="6" style="text-align: right;">
							            <span style="font-family: Quicksand;">Total </span><br>
							        </th>
							        <th colspan="6">
							            <span style="font-family: Quicksand;">'.$last_total.' </span><br>
							        </th>
						    	</tr>
		                    ';
						$html .= '</table>
		          	';

		          	$pdf->writeHTML($html, true, false, true, false, '');
	        		$pdf->Output($param1.'_'.date('d-F-Y H:i:s').'.pdf', 'I');
    			}
    			else
    			{

    			}
    		}
    	}
	}
?>
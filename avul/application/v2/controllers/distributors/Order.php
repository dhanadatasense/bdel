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

		public function overall_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$distributor_id = $this->session->userdata('id');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Overall Order";
				$page['pre_title']     = "";
				$page['load_data']     = "";
				$page['function_name'] = "overall_order";
				$page['pre_menu']      = "index.php/distributors/order/list_order";
				$data['page_temp']     = $this->load->view('distributors/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "overall_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				$load_data = $this->input->post('load_data');
				$limit     = $this->input->post('limitval');
            	$page      = $this->input->post('page');
            	$search    = $this->input->post('search');
            	$filter    = $this->input->post('filterval');
            	$cur_page  = isset($page)?$page:'1';
            	$_offset   = ($cur_page-1) * $limit;
            	$nxt_page  = $cur_page + 1;
            	$pre_page  = $cur_page - 1;
            	$cur_date  = date('Y-m-d');

            	$start_date = date('Y-m-d', strtotime('-'.$filter.' days', strtotime($cur_date)));
            	$end_date   = $cur_date;

            	$where = array(
            		'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
            		'start_date'     => $start_date,
            		'end_date'       => $end_date,
            		'load_data'      => $load_data,
            		'distributor_id' => $distributor_id,
            		'financial_year' => $this->session->userdata('active_year'),
            		'method'         => '_listDistributorOrderPaginate'
            	);

            	$data_list  = avul_call(API_URL.'order/api/distributor_manage_order',$where);
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
			            $inv_val      = !empty($value['inv_val'])?$value['inv_val']:'';
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
					        $order_view = '<span class="badge badge-info">Ready to shipping</span>';
					    }
					    else if($order_status == '5')
					    {
					        $order_view = '<span class="badge badge-success">Invoice</span>';
					    }
					    else if($order_status == '10')
					    {
					        $order_view = '<span class="badge badge-warning">Shipping</span>';
					    }
					    else if($order_status == '11')
					    {
					        $order_view = '<span class="badge badge-primary">Delivered</span>';
					    }
					    else if($order_status == '7')
					    {
					        $order_view = '<span class="badge badge-success">Complete</span>';
					    }
					    else
					    {
					        $order_view = '<span class="badge badge-danger">Cancel Invoice</span>';
					    }

		                $table .= '
					    	<tr>
                                <td>'.$order_no.'</td>
                                <td>'.$inv_val.'</td>
		                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
		                        <td style="display: none;">'.mb_strimwidth($emp_name, 0, 10, '...').'</td>
                                <td>'.mb_strimwidth($store_name, 0, 25, '...').'</td>
		                        <td>'.$order_view.'</td>
		                        <td><a href="'.BASE_URL.'index.php/distributors/order/overall_order/view/'.$random_value.'/'.$order_status.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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
				$order_status = $param3;

				$where = array(
					'distributor_id' => $distributor_id,
			    	'random_value'   => $random_value,
			    	'load_data'      => $order_status,
			    	'method'         => '_detailDistributorOrder'
			    );

				$data_val = avul_call(API_URL.'order/api/distributor_manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Process Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/distributors/order/process_order";
				$data['page_temp']     = $this->load->view('distributors/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "overall_order";
				$this->bassthaya->load_distributors_form_template($data);
			}
    	}

    	public function process_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$distributor_id = $this->session->userdata('id');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Approved Order";
				$page['pre_title']     = "";
				$page['load_data']     = "2";
				$page['function_name'] = "process_order";
				$page['pre_menu']      = "index.php/distributors/order/list_order";
				$data['page_temp']     = $this->load->view('distributors/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "process_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				$load_data = $this->input->post('load_data');
				$limit     = $this->input->post('limitval');
				$filter    = $this->input->post('filterval');
            	$page      = $this->input->post('page');
            	$search    = $this->input->post('search');
            	$cur_page  = isset($page)?$page:'1';
            	$_offset   = ($cur_page-1) * $limit;
            	$nxt_page  = $cur_page + 1;
            	$pre_page  = $cur_page - 1;
            	$cur_date  = date('Y-m-d');

            	$start_date = date('Y-m-d', strtotime('-'.$filter.' days', strtotime($cur_date)));
            	$end_date   = $cur_date;

            	$where = array(
            		'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
            		'start_date'     => $start_date,
            		'end_date'       => $end_date,
            		'load_data'      => $load_data,
            		'distributor_id' => $distributor_id,
            		'financial_year' => $this->session->userdata('active_year'),
            		'method'         => '_listDistributorOrderPaginate'
            	);

            	$data_list  = avul_call(API_URL.'order/api/distributor_manage_order',$where);
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
			            $inv_val      = !empty($value['inv_val'])?$value['inv_val']:'';
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
					        $order_view = '<span class="badge badge-info">Ready to shipping</span>';
					    }
					    else if($order_status == '5')
					    {
					        $order_view = '<span class="badge badge-success">Invoice</span>';
					    }
					    else if($order_status == '10')
					    {
					        $order_view = '<span class="badge badge-warning">Shipping</span>';
					    }
					    else if($order_status == '11')
					    {
					        $order_view = '<span class="badge badge-primary">Delivered</span>';
					    }
					    else if($order_status == '7')
					    {
					        $order_view = '<span class="badge badge-success">Complete</span>';
					    }
					    else
					    {
					        $order_view = '<span class="badge badge-danger">Cancel Invoice</span>';
					    }

		                $table .= '
					    	<tr>
                                <td>'.$order_no.'</td>
                                <td>'.$inv_val.'</td>
		                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
		                        <td style="display: none;">'.mb_strimwidth($emp_name, 0, 10, '...').'</td>
                                <td>'.mb_strimwidth($store_name, 0, 25, '...').'</td>
		                        <td>'.$order_view.'</td>
		                        <td><a href="'.BASE_URL.'index.php/distributors/order/process_order/view/'.$random_value.'/'.$order_status.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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
				$order_status = $param3;

				$where = array(
					'distributor_id' => $distributor_id,
			    	'random_value'   => $random_value,
			    	'load_data'      => $order_status,
			    	'method'         => '_detailDistributorOrder'
			    );

				$data_val = avul_call(API_URL.'order/api/distributor_manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Process Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/distributors/order/process_order";
				$data['page_temp']     = $this->load->view('distributors/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "process_order";
				$this->bassthaya->load_distributors_form_template($data);
			}
    	}

    	public function packing_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$distributor_id = $this->session->userdata('id');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Process Order";
				$page['pre_title']     = "";
				$page['load_data']     = "3";
				$page['function_name'] = "packing_order";
				$page['pre_menu']      = "index.php/distributors/order/list_order";
				$data['page_temp']     = $this->load->view('distributors/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "packing_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				$load_data = $this->input->post('load_data');
				$limit     = $this->input->post('limitval');
            	$page      = $this->input->post('page');
            	$filter    = $this->input->post('filterval');
            	$search    = $this->input->post('search');
            	$cur_page  = isset($page)?$page:'1';
            	$_offset   = ($cur_page-1) * $limit;
            	$nxt_page  = $cur_page + 1;
            	$pre_page  = $cur_page - 1;
            	$cur_date  = date('Y-m-d');

            	$start_date = date('Y-m-d', strtotime('-'.$filter.' days', strtotime($cur_date)));
            	$end_date   = $cur_date;

            	$where = array(
            		'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
            		'start_date'     => $start_date,
            		'end_date'       => $end_date,
            		'load_data'      => $load_data,
            		'distributor_id' => $distributor_id,
            		'financial_year' => $this->session->userdata('active_year'),
            		'method'         => '_listDistributorOrderPaginate'
            	);

            	$data_list  = avul_call(API_URL.'order/api/distributor_manage_order',$where);
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
			            $inv_val      = !empty($value['inv_val'])?$value['inv_val']:'';
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
					        $order_view = '<span class="badge badge-info">Ready to shipping</span>';
					    }
					    else if($order_status == '5')
					    {
					        $order_view = '<span class="badge badge-success">Invoice</span>';
					    }
					    else if($order_status == '10')
					    {
					        $order_view = '<span class="badge badge-warning">Shipping</span>';
					    }
					    else if($order_status == '11')
					    {
					        $order_view = '<span class="badge badge-primary">Delivered</span>';
					    }
					    else if($order_status == '7')
					    {
					        $order_view = '<span class="badge badge-success">Complete</span>';
					    }
					    else
					    {
					        $order_view = '<span class="badge badge-danger">Cancel Invoice</span>';
					    }

		                $table .= '
					    	<tr>
                                <td>'.$order_no.'</td>
                                <td>'.$inv_val.'</td>
		                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
		                        <td style="display: none;">'.mb_strimwidth($emp_name, 0, 10, '...').'</td>
                                <td>'.mb_strimwidth($store_name, 0, 25, '...').'</td>
		                        <td>'.$order_view.'</td>
		                        <td><a href="'.BASE_URL.'index.php/distributors/order/packing_order/view/'.$random_value.'/'.$order_status.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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
				$order_status = $param3;

				$where = array(
					'distributor_id' => $distributor_id,
			    	'random_value'   => $random_value,
			    	'load_data'      => $order_status,
			    	'method'         => '_detailDistributorOrder'
			    );

				$data_val = avul_call(API_URL.'order/api/distributor_manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Process Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/distributors/order/packing_order";
				$data['page_temp']     = $this->load->view('distributors/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "packing_order";
				$this->bassthaya->load_distributors_form_template($data);
			}
    	}

    	public function ready_to_shipping_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$distributor_id = $this->session->userdata('id');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Ready to shipping";
				$page['pre_title']     = "";
				$page['load_data']     = "4";
				$page['function_name'] = "ready_to_shipping_order";
				$page['pre_menu']      = "index.php/distributors/order/list_order";
				$data['page_temp']     = $this->load->view('distributors/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "ready_to_shipping_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				$load_data = $this->input->post('load_data');
				$limit     = $this->input->post('limitval');
            	$page      = $this->input->post('page');
            	$search    = $this->input->post('search');
            	$filter    = $this->input->post('filterval');
            	$cur_page  = isset($page)?$page:'1';
            	$_offset   = ($cur_page-1) * $limit;
            	$nxt_page  = $cur_page + 1;
            	$pre_page  = $cur_page - 1;
            	$cur_date  = date('Y-m-d');

            	$start_date = date('Y-m-d', strtotime('-'.$filter.' days', strtotime($cur_date)));
            	$end_date   = $cur_date;

            	$where = array(
            		'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
            		'start_date'     => $start_date,
            		'end_date'       => $end_date,
            		'load_data'      => $load_data,
            		'distributor_id' => $distributor_id,
            		'financial_year' => $this->session->userdata('active_year'),
            		'method'         => '_listDistributorOrderPaginate'
            	);

            	$data_list  = avul_call(API_URL.'order/api/distributor_manage_order',$where);
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
			            $inv_val      = !empty($value['inv_val'])?$value['inv_val']:'';
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
					        $order_view = '<span class="badge badge-info">Ready to shipping</span>';
					    }
					    else if($order_status == '5')
					    {
					        $order_view = '<span class="badge badge-success">Invoice</span>';
					    }
					    else if($order_status == '10')
					    {
					        $order_view = '<span class="badge badge-warning">Shipping</span>';
					    }
					    else if($order_status == '11')
					    {
					        $order_view = '<span class="badge badge-primary">Delivered</span>';
					    }
					    else if($order_status == '7')
					    {
					        $order_view = '<span class="badge badge-success">Complete</span>';
					    }
					    else
					    {
					        $order_view = '<span class="badge badge-danger">Cancel Invoice</span>';
					    }

		                $table .= '
					    	<tr>
                                <td>'.$order_no.'</td>
                                <td>'.$inv_val.'</td>
		                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
		                        <td style="display: none;">'.mb_strimwidth($emp_name, 0, 10, '...').'</td>
                                <td>'.mb_strimwidth($store_name, 0, 25, '...').'</td>
		                        <td>'.$order_view.'</td>
		                        <td><a href="'.BASE_URL.'index.php/distributors/order/ready_to_shipping_order/view/'.$random_value.'/'.$order_status.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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
				$order_status = $param3;

				$where = array(
					'distributor_id' => $distributor_id,
			    	'random_value'   => $random_value,
			    	'load_data'      => $order_status,
			    	'method'         => '_detailDistributorOrder'
			    );

				$data_val = avul_call(API_URL.'order/api/distributor_manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Process Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/distributors/order/packing_order";
				$data['page_temp']     = $this->load->view('distributors/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "ready_to_shipping_order";
				$this->bassthaya->load_distributors_form_template($data);
			}
    	}

    	public function invoice_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$distributor_id = $this->session->userdata('id');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Invoice Order";
				$page['pre_title']     = "";
				$page['load_data']     = "5";
				$page['function_name'] = "invoice_order";
				$page['pre_menu']      = "index.php/distributors/order/list_order";
				$data['page_temp']     = $this->load->view('distributors/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "invoice_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				$load_data = $this->input->post('load_data');
				$limit     = $this->input->post('limitval');
            	$page      = $this->input->post('page');
            	$filter    = $this->input->post('filterval');
            	$search    = $this->input->post('search');
            	$cur_page  = isset($page)?$page:'1';
            	$_offset   = ($cur_page-1) * $limit;
            	$nxt_page  = $cur_page + 1;
            	$pre_page  = $cur_page - 1;
            	$cur_date  = date('Y-m-d');

            	$start_date = date('Y-m-d', strtotime('-'.$filter.' days', strtotime($cur_date)));
            	$end_date   = $cur_date;

            	$where = array(
            		'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
            		'start_date'     => $start_date,
            		'end_date'       => $end_date,
            		'load_data'      => $load_data,
            		'distributor_id' => $distributor_id,
            		'financial_year' => $this->session->userdata('active_year'),
            		'method'         => '_listDistributorOrderPaginate'
            	);

            	$data_list  = avul_call(API_URL.'order/api/distributor_manage_order',$where);
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
			            $inv_val      = !empty($value['inv_val'])?$value['inv_val']:'';
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
					        $order_view = '<span class="badge badge-info">Ready to shipping</span>';
					    }
					    else if($order_status == '5')
					    {
					        $order_view = '<span class="badge badge-success">Invoice</span>';
					    }
					    else if($order_status == '10')
					    {
					        $order_view = '<span class="badge badge-warning">Shipping</span>';
					    }
					    else if($order_status == '11')
					    {
					        $order_view = '<span class="badge badge-primary">Delivered</span>';
					    }
					    else if($order_status == '7')
					    {
					        $order_view = '<span class="badge badge-success">Complete</span>';
					    }
					    else
					    {
					        $order_view = '<span class="badge badge-danger">Cancel Invoice</span>';
					    }

		                $table .= '
					    	<tr>
                                <td>'.$order_no.'</td>
                                <td>'.$inv_val.'</td>
		                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
		                        <td style="display: none;">'.mb_strimwidth($emp_name, 0, 10, '...').'</td>
                                <td>'.mb_strimwidth($store_name, 0, 25, '...').'</td>
		                        <td>'.$order_view.'</td>
		                        <td><a href="'.BASE_URL.'index.php/distributors/order/invoice_order/view/'.$random_value.'/'.$order_status.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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
				$order_status = $param3;

				$where = array(
					'distributor_id' => $distributor_id,
			    	'random_value'   => $random_value,
			    	'load_data'      => $order_status,
			    	'method'         => '_detailDistributorOrder'
			    );

				$data_val = avul_call(API_URL.'order/api/distributor_manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Process Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/distributors/order/invoice_order";
				$data['page_temp']     = $this->load->view('distributors/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "invoice_order";
				$this->bassthaya->load_distributors_form_template($data);
			}
    	}

    	public function shipping_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$distributor_id = $this->session->userdata('id');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Shipping Order";
				$page['pre_title']     = "";
				$page['load_data']     = "10";
				$page['function_name'] = "shipping_order";
				$page['pre_menu']      = "index.php/distributors/order/list_order";
				$data['page_temp']     = $this->load->view('distributors/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "shipping_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				$load_data = $this->input->post('load_data');
				$limit     = $this->input->post('limitval');
            	$page      = $this->input->post('page');
            	$filter    = $this->input->post('filterval');
            	$search    = $this->input->post('search');
            	$cur_page  = isset($page)?$page:'1';
            	$_offset   = ($cur_page-1) * $limit;
            	$nxt_page  = $cur_page + 1;
            	$pre_page  = $cur_page - 1;
            	$cur_date  = date('Y-m-d');

            	$start_date = date('Y-m-d', strtotime('-'.$filter.' days', strtotime($cur_date)));
            	$end_date   = $cur_date;

            	$where = array(
            		'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
            		'start_date'     => $start_date,
            		'end_date'       => $end_date,
            		'load_data'      => $load_data,
            		'distributor_id' => $distributor_id,
            		'financial_year' => $this->session->userdata('active_year'),
            		'method'         => '_listDistributorOrderPaginate'
            	);

            	$data_list  = avul_call(API_URL.'order/api/distributor_manage_order',$where);
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
			            $inv_val      = !empty($value['inv_val'])?$value['inv_val']:'';
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
					        $order_view = '<span class="badge badge-info">Ready to shipping</span>';
					    }
					    else if($order_status == '5')
					    {
					        $order_view = '<span class="badge badge-success">Invoice</span>';
					    }
					    else if($order_status == '10')
					    {
					        $order_view = '<span class="badge badge-warning">Shipping</span>';
					    }
					    else if($order_status == '11')
					    {
					        $order_view = '<span class="badge badge-primary">Delivered</span>';
					    }
					    else if($order_status == '7')
					    {
					        $order_view = '<span class="badge badge-success">Complete</span>';
					    }
					    else
					    {
					        $order_view = '<span class="badge badge-danger">Cancel Invoice</span>';
					    }

		                $table .= '
					    	<tr>
                                <td>'.$order_no.'</td>
                                <td>'.$inv_val.'</td>
		                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
		                        <td style="display: none;">'.mb_strimwidth($emp_name, 0, 10, '...').'</td>
                                <td>'.mb_strimwidth($store_name, 0, 25, '...').'</td>
		                        <td>'.$order_view.'</td>
		                        <td><a href="'.BASE_URL.'index.php/distributors/order/shipping_order/view/'.$random_value.'/'.$order_status.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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
				$order_status = $param3;

				$where = array(
					'distributor_id' => $distributor_id,
			    	'random_value'   => $random_value,
			    	'load_data'      => $order_status,
			    	'method'         => '_detailDistributorOrder'
			    );

				$data_val = avul_call(API_URL.'order/api/distributor_manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Process Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/distributors/order/shipping_order";
				$data['page_temp']     = $this->load->view('distributors/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "shipping_order";
				$this->bassthaya->load_distributors_form_template($data);
			}
    	}

    	public function delivery_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$distributor_id = $this->session->userdata('id');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Delivered Order";
				$page['pre_title']     = "";
				$page['load_data']     = "11";
				$page['function_name'] = "delivery_order";
				$page['pre_menu']      = "index.php/distributors/order/list_order";
				$data['page_temp']     = $this->load->view('distributors/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "delivery_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				$load_data = $this->input->post('load_data');
				$limit     = $this->input->post('limitval');
            	$page      = $this->input->post('page');
            	$filter    = $this->input->post('filterval');
            	$search    = $this->input->post('search');
            	$cur_page  = isset($page)?$page:'1';
            	$_offset   = ($cur_page-1) * $limit;
            	$nxt_page  = $cur_page + 1;
            	$pre_page  = $cur_page - 1;
            	$cur_date  = date('Y-m-d');

            	$start_date = date('Y-m-d', strtotime('-'.$filter.' days', strtotime($cur_date)));
            	$end_date   = $cur_date;

            	$where = array(
            		'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
            		'start_date'     => $start_date,
            		'end_date'       => $end_date,
            		'load_data'      => $load_data,
            		'distributor_id' => $distributor_id,
            		'financial_year' => $this->session->userdata('active_year'),
            		'method'         => '_listDistributorOrderPaginate'
            	);

            	$data_list  = avul_call(API_URL.'order/api/distributor_manage_order',$where);
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
			            $inv_val      = !empty($value['inv_val'])?$value['inv_val']:'';
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
					        $order_view = '<span class="badge badge-info">Ready to shipping</span>';
					    }
					    else if($order_status == '5')
					    {
					        $order_view = '<span class="badge badge-success">Invoice</span>';
					    }
					    else if($order_status == '10')
					    {
					        $order_view = '<span class="badge badge-warning">Shipping</span>';
					    }
					    else if($order_status == '11')
					    {
					        $order_view = '<span class="badge badge-primary">Delivered</span>';
					    }
					    else if($order_status == '7')
					    {
					        $order_view = '<span class="badge badge-success">Complete</span>';
					    }
					    else
					    {
					        $order_view = '<span class="badge badge-danger">Cancel Invoice</span>';
					    }

		                $table .= '
					    	<tr>
                                <td>'.$order_no.'</td>
                                <td>'.$inv_val.'</td>
		                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
		                        <td style="display: none;">'.mb_strimwidth($emp_name, 0, 10, '...').'</td>
                                <td>'.mb_strimwidth($store_name, 0, 25, '...').'</td>
		                        <td>'.$order_view.'</td>
		                        <td><a href="'.BASE_URL.'index.php/distributors/order/shipping_order/view/'.$random_value.'/'.$order_status.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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
				$order_status = $param3;

				$where = array(
					'distributor_id' => $distributor_id,
			    	'random_value'   => $random_value,
			    	'load_data'      => $order_status,
			    	'method'         => '_detailDistributorOrder'
			    );

				$data_val = avul_call(API_URL.'order/api/distributor_manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Process Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/distributors/order/shipping_order";
				$data['page_temp']     = $this->load->view('distributors/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "shipping_order";
				$this->bassthaya->load_distributors_form_template($data);
			}
    	}

    	public function cancel_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$distributor_id = $this->session->userdata('id');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Cancel Order";
				$page['pre_title']     = "";
				$page['load_data']     = "9";
				$page['function_name'] = "cancel_order";
				$page['pre_menu']      = "index.php/distributors/order/list_order";
				$data['page_temp']     = $this->load->view('distributors/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "cancel_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				$load_data = $this->input->post('load_data');
				$limit     = $this->input->post('limitval');
            	$page      = $this->input->post('page');
            	$filter    = $this->input->post('filterval');
            	$search    = $this->input->post('search');
            	$cur_page  = isset($page)?$page:'1';
            	$_offset   = ($cur_page-1) * $limit;
            	$nxt_page  = $cur_page + 1;
            	$pre_page  = $cur_page - 1;
            	$cur_date  = date('Y-m-d');

            	$start_date = date('Y-m-d', strtotime('-'.$filter.' days', strtotime($cur_date)));
            	$end_date   = $cur_date;

            	$where = array(
            		'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
            		'start_date'     => $start_date,
            		'end_date'       => $end_date,
            		'load_data'      => $load_data,
            		'distributor_id' => $distributor_id,
            		'financial_year' => $this->session->userdata('active_year'),
            		'method'         => '_listDistributorOrderPaginate'
            	);

            	$data_list  = avul_call(API_URL.'order/api/distributor_manage_order',$where);
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
			            $inv_val      = !empty($value['inv_val'])?$value['inv_val']:'';
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
					        $order_view = '<span class="badge badge-info">Ready to shipping</span>';
					    }
					    else if($order_status == '5')
					    {
					        $order_view = '<span class="badge badge-success">Invoice</span>';
					    }
					    else if($order_status == '10')
					    {
					        $order_view = '<span class="badge badge-warning">Shipping</span>';
					    }
					    else if($order_status == '11')
					    {
					        $order_view = '<span class="badge badge-primary">Delivered</span>';
					    }
					    else if($order_status == '7')
					    {
					        $order_view = '<span class="badge badge-success">Complete</span>';
					    }
					    else
					    {
					        $order_view = '<span class="badge badge-danger">Cancel Invoice</span>';
					    }

		                $table .= '
					    	<tr>
                                <td>'.$order_no.'</td>
                                <td>'.$inv_val.'</td>
		                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
		                        <td style="display: none;">'.mb_strimwidth($emp_name, 0, 10, '...').'</td>
                                <td>'.mb_strimwidth($store_name, 0, 25, '...').'</td>
		                        <td>'.$order_view.'</td>
		                        <td><a href="'.BASE_URL.'index.php/distributors/order/cancel_order/view/'.$random_value.'/'.$order_status.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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
				$order_status = $param3;

				$where = array(
					'distributor_id' => $distributor_id,
			    	'random_value'   => $random_value,
			    	'load_data'      => $order_status,
			    	'method'         => '_detailDistributorOrder'
			    );

				$data_val = avul_call(API_URL.'order/api/distributor_manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Delivered Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/distributors/order/cancel_order";
				$data['page_temp']     = $this->load->view('distributors/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "cancel_order";
				$this->bassthaya->load_distributors_form_template($data);
			}
    	}

    	public function order_process($param1="", $param2="", $param3="")
    	{
    		$method    = $this->input->post('method');
    		$distri_id = $this->session->userdata('id');

    		if($method == '_changePackProcess')
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
			    		'auto_id'        => $order_id,
			    		'distributor_id' => $distri_id,
						'method'         => '_changePackProcess',
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
			    		'auto_id'        => $order_id,
			    		'distributor_id' => $distri_id,
						'method'         => '_deletePackStatus',
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

    		if($param1 == 'changeOrder_process')
    		{
    			$error = FALSE;
    			$order_id                = $this->input->post('order_id');
    			$inv_value               = $this->input->post('inv_value');
    			$inv_random              = $this->input->post('inv_random');
    			$pre_status              = $this->input->post('pre_status');
    			$distributor_id          = $this->input->post('distributor_id');
				$order_status            = $this->input->post('order_status');
				$zone_value              = $this->input->post('zone_value');
				$message                 = $this->input->post('message');
				$length                  = $this->input->post('length');
			    $breadth                 = $this->input->post('breadth');
			    $height                  = $this->input->post('height');
			    $weight                  = $this->input->post('weight');
			    $e_inv_status            = $this->input->post('e_inv_status');
			    $e_way_status            = $this->input->post('e_way_status');
			    $transporter_id          = $this->input->post('transporter_id');
			    $transporter_name        = $this->input->post('transporter_name');
			    $transportation_mode     = $this->input->post('transportation_mode');
			    $transportation_distance = $this->input->post('transportation_distance');
			    $transporter_doc_number  = $this->input->post('transporter_document_number');
			    $transporter_doc_date    = $this->input->post('transporter_document_date');
			    $vehicle_number          = $this->input->post('vehicle_number');
			    $vehicle_type            = $this->input->post('vehicle_type');

				$required = array('order_id', 'pre_status', 'distributor_id', 'order_status', 'zone_value');
				if($order_status == 9)
				{
					array_push($required, 'message');
				}
				if($order_status == 10)
				{
					array_push($required, 'e_inv_status');
				}
				if($e_inv_status == 1)
				{
					array_push($required, 'e_way_status');
				}
				if($e_way_status == 1)
				{
					array_push($required, 'transporter_id', 'transporter_name', 'transportation_mode', 'vehicle_number', 'vehicle_type');
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
			    		'auto_id'                 => $order_id,
			    		'inv_value'               => $inv_value,
			    		'inv_random'              => $inv_random,
			    		'pre_status'              => $pre_status,
			    		'distributor_id'          => $distributor_id,
						'progress'                => $order_status,
						'zone_value'              => $zone_value,
						'reason'                  => $message,
						'length'                  => $length,
						'breadth'                 => $breadth,
						'height'                  => $height,
						'weight'                  => $weight,
						'e_inv_status'            => $e_inv_status,
						'e_way_status'            => $e_way_status,
						'transporter_id'          => $transporter_id,
						'transporter_name'        => $transporter_name,
						'transportation_mode'     => $transportation_mode,
						'transportation_distance' => $transportation_distance,
						'transporter_doc_number'  => $transporter_doc_number,
						'transporter_doc_date'    => $transporter_doc_date,
						'vehicle_number'          => $vehicle_number,
						'vehicle_type'            => $vehicle_type,
						'method'                  => '_updateDistributorOrderProgress',
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
    			$error     = FALSE;
    			$auto_id   = $this->input->post('id');
    			$quantity  = $this->input->post('qty');
    			$distri_id = $this->session->userdata('id');

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
			    		'auto_id'        => $auto_id,
			    		'quantity'       => $quantity,
			    		'distributor_id' => $distri_id,
			    		'method'         => '_updateDistributorOrderDetails',
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
    			$id        = $this->input->post('id');
    			$distri_id = $this->session->userdata('id');

    			if(!empty($id))
    			{
    				$order_data = array(
			    		'auto_id'        => $id,
			    		'distributor_id' => $distri_id,
			    		'method'         => '_deleteDistributorOrderDetails',
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
			    		'auto_id'        => $order_id,
			    		'distributor_id' => $distri_id,
						'method'         => '_changePackStatus',
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
    	}

    	public function order_stock($param1="", $param2="", $param3="")
    	{
    		if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        	
        	$distributor_id = $this->session->userdata('id');
			$formpage       = $this->input->post('formpage');

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
			    		'order_id'       => $order_id,
			    		'order_auto_id'  => $auto_id,
			    		'distributor_id' => $distributor_id,
			    		'product_id'     => $product_id,
			    		'type_id'        => $type_id,
			    		'product_unit'   => $product_unit,
			    		'received_date'  => $received_date,
			    		'received_qty'   => $received_qty,
			    		'method'         => '_addDistributorOrderStockDetails',
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
					'order_auto_id' => $auto_id,
					'order_id'      => $order_id,
					'method'        => '_detailDistributorOrderDetails',
				);

				$order_data  = avul_call(API_URL.'order/api/manage_order_stock',$order_details);
				$order_value = $order_data['data'];

				$product_id = !empty($order_value['product_id'])?$order_value['product_id']:'';
				$type_id    = !empty($order_value['type_id'])?$order_value['type_id']:'';

				// Stock details
				$stock_details = array(
					'order_auto_id' => $auto_id,
					'order_id'      => $order_id,
					'product_id'    => $product_id,
					'type_id'       => $type_id,
					'method'        => '_listOrderStockDetails',
				);

				$stock_data  = avul_call(API_URL.'order/api/manage_order_stock',$stock_details);
				$stock_value = $stock_data['data'];

				// Order details
				$order_details = array(
					'order_id' => $order_id,
					'method'   => '_orderData',
				);

				$order_data = avul_call(API_URL.'order/api/manage_order',$order_details);
				$bill_value = $order_data['data'];

				$order_no     = !empty($bill_value['order_no'])?$bill_value['order_no']:'';
    			$random_value = !empty($bill_value['random_value'])?$bill_value['random_value']:'';

				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Manage Outlet Order";
				$page['page_title']    = "Add Stock";
				$page['pre_title']     = "View Order";
				$page['order_value']   = $order_value;
				$page['stock_value']   = $stock_value;
				$page['pre_menu']      = "index.php/distributors/order/packing_order/view/$random_value";
				$data['page_temp']     = $this->load->view('distributors/order/stock_add',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "packing_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			if($param1 == 'delete')
			{
				$id = $this->input->post('id');

				if(!empty($id))	
				{
					$data = array(
				    	'stock_id'       => $id,
				    	'distributor_id' => $distributor_id,
				    	'method'         => '_deleteDistributorStockDetails'
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

		public function print_order($param1="", $param2="", $param3="")
		{
			if(!empty($param1))
    		{
    			$distributor_id = $this->session->userdata('id');

    			$where = array(
					'distributor_id' => $distributor_id,
			    	'random_value'   => $param1,
			    	'method'         => '_detailDistributorOrder'
			    );

				$data_val = avul_call(API_URL.'order/api/distributor_manage_order',$where);

				if($data_val)
				{
					$ord_val = $data_val['data'];

					$bil_det = !empty($ord_val['bill_details'])?$ord_val['bill_details']:'';
					$dis_det = !empty($ord_val['distributor_details'])?$ord_val['distributor_details']:'';
					$str_det = !empty($ord_val['store_details'])?$ord_val['store_details']:'';
					$pdt_det = !empty($ord_val['product_details'])?$ord_val['product_details']:'';
					$tax_det = !empty($ord_val['tax_details'])?$ord_val['tax_details']:'';

					// Distributor Details
					$dis_name  = !empty($dis_det['company_name'])?$dis_det['company_name']:'';
                    $dis_gst   = !empty($dis_det['gst_no'])?$dis_det['gst_no']:'';
                    $dis_cont  = !empty($dis_det['contact_no'])?$dis_det['contact_no']:'';
                    $dis_email = !empty($dis_det['email'])?$dis_det['email']:'';
                    $dis_adrs  = !empty($dis_det['address'])?$dis_det['address']:'';
                    $dis_state = !empty($dis_det['state_id'])?$dis_det['state_id']:'';

                    // Store Details
                    $str_name    = !empty($str_det['company_name'])?$str_det['company_name']:'';
                    $str_cont    = !empty($str_det['mobile'])?$str_det['mobile']:'';
                    $str_gst     = !empty($str_det['gst_no'])?$str_det['gst_no']:'';
                    $str_adrs    = !empty($str_det['address'])?$str_det['address']:'';
                    $str_state   = !empty($str_det['state_id'])?$str_det['state_id']:'';
                    $str_sta_val = !empty($str_det['state_name'])?$str_det['state_name']:'';

                    // Order Details
                    $order_no   = !empty($bil_det['order_no'])?$bil_det['order_no']:'';
		            $bill_type  = !empty($bil_det['bill_type'])?$bil_det['bill_type']:'';
		            $emp_name   = !empty($bil_det['emp_name'])?$bil_det['emp_name']:'';
		            $store_name = !empty($bil_det['store_name'])?$bil_det['store_name']:'';
		            $due_days   = !empty($bil_det['due_days'])?$bil_det['due_days']:'';
		            $discount   = !empty($bil_det['discount'])?$bil_det['discount']:'0';
		            $_ordered   = !empty($bil_det['_ordered'])?$bil_det['_ordered']:'';

		            // Order Type
				    if($bill_type == 1)
				    {
				        $type_view = 'COD';
				    }
				    else
				    {
				        $type_view = 'Credit';
				    }

				    $this->load->library('Pdf');
		      		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'mm', array(216,356), TRUE, 'UTF-8', FALSE);
		          	$pdf->SetTitle('Manufacturer Invice');
		          	$pdf->SetPrintHeader(false);
		          	$pdf->SetPrintFooter(false);
		          		
					$pdf->SetPrintHeader(false);
					$pdf->SetPrintFooter(false);

		          	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		          	$pdf->SetFont('');
		          	$pdf->AddPage('P');
		          	$html = '';

		          	$html .= '<p style="color:black; font-size:12px; text-align: center;"><strong style="font-size:18px; padding-bottom:1000px;">'.$dis_name.'</strong><br/>'.$dis_adrs.', Contact No: '.$dis_cont.'<br>GSTIN\UIN : '.$dis_gst.'<br><strong style="color:black; text-align:center; font-size:17px;"> OUTLET ORDER</strong><br></p>';

		          	$html .='<br><br><br>
					<table border= "1" cellpadding="1" top="100">
				        <tr>
				            <td rowspan="6" style="font-size:12px; width: 55%; margin-left:10px;">Shipped To: <br> '.$str_name.'<br> '.$str_adrs.'<br> Contact No: '.$str_cont.'<br> GSTIN\UIN :'.$str_gst.'</td>
				            <td style="font-size:12px; width: 20%;"> Outlet(s) Order No</td>
				            <td style="font-size:12px; width: 25%;">'.$order_no.'</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Outlet(s) Order Date</td>
				            <td style="font-size:12px; width: 25%;">'.date('d-M-Y', strtotime($_ordered)).'</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Invoice No</td>
				            <td style="font-size:12px; width: 25%;"></td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Invoice Date</td>
				            <td style="font-size:12px; width: 25%;"></td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Bill Type</td>
				            <td style="font-size:12px; width: 25%;">'.$type_view.'</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Due Days</td>
				            <td style="font-size:12px; width: 25%;"></td>
				        </tr>
				    </table>
				    ';

				    $html .='<br><br>
						<table border= "1" cellpadding="1" top="100">
					        <tr>
					            <td style="font-size:12px; width: 5%;">S.No</td>
					            <td style="font-size:12px; width: 44%;">Description</td>
					            <td style="font-size:12px; text-align: center; width: 10%;">HSN</td>
					            <td style="font-size:12px; text-align: center; width: 10%;">Rate</td>
					            <td style="font-size:12px; text-align: center; width: 8%;">Qty</td>
					            <td style="font-size:12px; text-align: center; width: 8%;">Per</td>
					            <td style="font-size:12px; text-align: center; width: 15%;">Amount</td>
					        </tr>';

					        $num     = 1;
					        $sub_tot = 0;
			                $tot_gst = 0;
			                $net_tot = 0;
			                $tot_qty = 0;
					        foreach ($pdt_det as $key => $val) {
					        	$description = !empty($val['description'])?$val['description']:'';
			                    $hsn_code    = !empty($val['hsn_code'])?$val['hsn_code']:'';
			                    $gst_value   = !empty($val['gst_val'])?$val['gst_val']:'0';
			                    $unit_val    = !empty($val['unit_val'])?$val['unit_val']:'0';
			                    $price       = !empty($val['price'])?$val['price']:'0';
			                    $order_qty   = !empty($val['order_qty'])?$val['order_qty']:'0';

			                    $gst_data  = $price - ($price * (100 / (100 + $gst_value)));
			                    $price_val = $price - $gst_data;
			                    $tot_price = $order_qty * $price_val;
			                    $sub_tot  += $tot_price;

			                    // GST Calculation
			                    $gst_val   = $order_qty * $gst_data;
			                    $tot_gst  += $gst_val;
			                    $total_val = $order_qty * $price;
			                    $net_tot  += $total_val;
			                    $tot_qty  += $order_qty;

			                    $html .= '
			                    	<tr>
							            <td style="font-size:12px; width: 5%;">'.$num.'</td>
							            <td style="font-size:12px; width: 44%;">'.$description.'</td>
							            <td style="font-size:12px; text-align: center; width: 10%;">'.$hsn_code.'</td>
							            <td style="font-size:12px; text-align: center; width: 10%;">'.number_format((float)$price_val, 2, '.', '').'</td>
							            <td style="font-size:12px; text-align: center; width: 8%;">'.$order_qty.'</td>
							            <td style="font-size:12px; text-align: center; width: 8%;">nos</td>
							            <td style="font-size:12px; text-align: center; width: 15%;">'.number_format((float)$tot_price, 2, '.', '').'</td>
							        </tr>
			                    ';

			                    $num++;
					        }

					        $rowspan = '7';
						    if($str_state == $dis_state)
						    {
						    	$rowspan = '8';
						    }

						    // Round Val Details
		                    $net_value  = round($net_tot);
		                    $total_dis  = $net_value * $discount / 100;
		                    $total_val  = $net_value - $total_dis;

		                    // Round Val Details
		                    $last_value = round($total_val);
		                    $rond_total = $last_value - $total_val;

		                    $html .= '
		                    	<tr>
					                <td rowspan ="'.$rowspan.'"  colspan="4"></td>
					                <td colspan="2" style="font-size:12px; text-align: right;">Qty</td>
					                <td style="font-size:12px; text-align: right;"> '.$tot_qty.'</td>
					                
					            </tr>
					            <tr>
					                <td colspan="2" style="font-size:12px; text-align: right;">Sub Total</td>
					                <td style="font-size:12px; text-align: right;"> '.number_format((float)$sub_tot, 2, '.', '').'</td>
					            </tr>
		                    ';

		                    if($str_state == $dis_state)
				            {
				            	$gst_value = $tot_gst / 2;

				            	$html .= '
				            		<tr>
						                <td colspan="2" style="font-size:12px; text-align: right;">SGST</td>
						                <td style="font-size:12px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
						            </tr>
						            <tr>
						                <td colspan="2" style="font-size:12px; text-align: right;">CGST</td>
						                <td style="font-size:12px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
						            </tr>
				            	';
				            }
				            else
				            {
				            	$html .= '
				            		<tr>
						                <td colspan="2" style="font-size:12px; text-align: right;">IGST</td>
						                <td style="font-size:12px; text-align: right;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
						            </tr>
				            	';
				            }

				            if($discount != 0)
				            {
				            	$html .='<tr>
					                <td colspan="2" style="font-size:12px; text-align: right;">Discount</td>
					                <td style="font-size:12px; text-align: right;"> '.number_format((float)$total_dis, 2, '.', '').'</td>
					            </tr>';	
				            }

				            $html .='<tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">Round off</td>
				                <td style="font-size:12px; text-align: right;"> '.number_format((float)$rond_total, 2, '.', '').'</td>
				            </tr>
				            <tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">Net Total</td>
				                <td style="font-size:12px; text-align: right;"> '.number_format((float)$last_value, 2, '.', '').'</td>
				            </tr>';

					$html .='</table>';

				    $pdf->writeHTML($html, true, false, true, false, '');
	       			$pdf->Output($order_no.'_'.date('d-F-Y H:i:s').'.pdf', 'I');
				}
    		}
		}

		public function print_invoice($param1="", $param2="", $param3="")
    	{
    		if(!empty($param1))
    		{
    			$bill_data = array(
    				'random_value' => $param1,
    				'method'       => '_detailInvoice',
    			);

    			$bill_val  = avul_call(API_URL.'order/api/invoice_manage_order', $bill_data);

    			if(!empty($bill_val))
    			{
    				$inv_data = $bill_val['data'];

    				$bill_det    = !empty($inv_data['bill_details'])?$inv_data['bill_details']:'';
    				$buyer_det   = !empty($inv_data['buyer_details'])?$inv_data['buyer_details']:'';
    				$dis_det     = !empty($inv_data['distributor_details'])?$inv_data['distributor_details']:'';
    				$store_det   = !empty($inv_data['store_details'])?$inv_data['store_details']:'';
    				$product_det = !empty($inv_data['product_details'])?$inv_data['product_details']:'';
    				$tax_det     = !empty($inv_data['tax_details'])?$inv_data['tax_details']:'';
    				$return_det  = !empty($inv_data['return_details'])?$inv_data['return_details']:'';

    				// Return Details
    				$return_total = !empty($return_det['return_total'])?$return_det['return_total']:'0';

    				// Invoice Details
    				$invoice_id  = !empty($bill_det['invoice_id'])?$bill_det['invoice_id']:'';
                    $bill_type   = !empty($bill_det['bill_type'])?$bill_det['bill_type']:'';
                    $invoice_no  = !empty($bill_det['invoice_no'])?$bill_det['invoice_no']:'';
                    $due_days    = !empty($bill_det['due_days'])?$bill_det['due_days']:'0';
                    $discount    = !empty($bill_det['discount'])?$bill_det['discount']:'0';
                    $length      = !empty($bill_det['length'])?$bill_det['length']:'0';
		            $breadth     = !empty($bill_det['breadth'])?$bill_det['breadth']:'0';
		            $height      = !empty($bill_det['height'])?$bill_det['height']:'0';
		            $weight      = !empty($bill_det['weight'])?$bill_det['weight']:'0';
                    $outlet_type = !empty($bill_det['outlet_type'])?$bill_det['outlet_type']:'';
                    $createdate  = !empty($bill_det['createdate'])?$bill_det['createdate']:'';

                    // Order Details
                    $order_no    = !empty($buyer_det['order_no'])?$buyer_det['order_no']:'';
                    $ordered     = !empty($buyer_det['_ordered'])?$buyer_det['_ordered']:'';

                    // Distributor Details
                    $company_name = !empty($dis_det['company_name'])?$dis_det['company_name']:'';
                    $gst_no       = !empty($dis_det['gst_no'])?$dis_det['gst_no']:'';
                    $contact_no   = !empty($dis_det['contact_no'])?$dis_det['contact_no']:'';
                    $email        = !empty($dis_det['email'])?$dis_det['email']:'';
                    $address      = !empty($dis_det['address'])?$dis_det['address']:'';
                    $state_name   = !empty($dis_det['state_name'])?$dis_det['state_name']:'';
                    $gst_code     = !empty($dis_det['gst_code'])?$dis_det['gst_code']:'';
                    $account_name = !empty($dis_det['account_name'])?$dis_det['account_name']:'';
                    $account_no   = !empty($dis_det['account_no'])?$dis_det['account_no']:'';
                    $bank_name    = !empty($dis_det['bank_name'])?$dis_det['bank_name']:'';
                    $branch_name  = !empty($dis_det['branch_name'])?$dis_det['branch_name']:'';
                    $ifsc_code    = !empty($dis_det['ifsc_code'])?$dis_det['ifsc_code']:'';
                    $msme_status  = !empty($dis_det['msme_status'])?$dis_det['msme_status']:'';
                    $msme_number  = !empty($dis_det['msme_number'])?$dis_det['msme_number']:'';
                    $logo_image   = !empty($dis_det['logo_image'])?$dis_det['logo_image']:'';

                    // Store Details
                    $store_name      = !empty($store_det['company_name'])?$store_det['company_name']:'';
                    $str_mobile      = !empty($store_det['mobile'])?$store_det['mobile']:'';
                    $str_email       = !empty($store_det['email'])?$store_det['email']:'';
                    $str_gst_no      = !empty($store_det['gst_no'])?$store_det['gst_no']:'';
                    $str_address     = !empty($store_det['address'])?$store_det['address']:'';
                    $str_state_name  = !empty($store_det['state_name'])?$store_det['state_name']:'';
                    $str_gst_code    = !empty($store_det['gst_code'])?$store_det['gst_code']:'';
                    $str_outlet_type = !empty($store_det['outlet_type'])?$store_det['outlet_type']:'';

                    // Order Type
				    if($bill_type == 1)
				    {
				        $type_view = 'COD';
				    }
				    else
				    {
				        $type_view = 'Credit';
				    }

				    $due_date = '';

				    if(!empty($due_days))
				    {
				    	$due_date = date('d-M-Y', strtotime($createdate. '+ '.$due_days.' day'));
				    }

                    $this->load->library('Pdf');
		      		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'mm', array(216,356), TRUE, 'UTF-8', FALSE);
		          	$pdf->SetTitle('Manufacturer Invice');
		          	$pdf->SetPrintHeader(false);
		          	$pdf->SetPrintFooter(false);
		          		
					$pdf->SetPrintHeader(false);
					$pdf->SetPrintFooter(false);

		          	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		          	$pdf->SetFont('');

		          	if(!empty($msme_number))
		          	{
		          		$msme_value = ',<br> MSME No: '.$msme_number;
		          	}
		          	else
		          	{
		          		$msme_value = '';
		          	}

		          	if(!empty($logo_image))
		          	{
		          		$logo_val = '<img src="'.FILE_URL.'distributor/logo/'.$logo_image.'" style="width: 80px;">';
		          	}
		          	else
		          	{
		          		$logo_val = '<img src="'.FILE_URL.'distributor/logo/logo.jpg" style="width: 80px;">';
		          	}

		          	// $bill_type = array('Original Copy', 'Duplicate Copy', 'Triplicate Copy');
		          	$bill_type = array('Original Copy', 'Duplicate Copy');

		          	// for ($i=0; $i <= 2; $i++) { 
		          	for ($i=0; $i <= 1; $i++) { 

		          		$pdf->AddPage('P');
			          	$html_1  = '';
			          	$html_1 .= '
			          	<table>
			          		<tr style="width: 100%;">
			          			<td><span style="color:black; text-align:right; font-size:13px; text-transform: uppercase;">'.$bill_type[$i].'</span></td>
			          		</tr>
			          		<tr>
			          			<td style="width: 14%;">'.$logo_val.'</td>
			          			<td style="width: 86%;"><p style="color:black; font-size:11px; text-align: right;"><strong style="font-size:16px; padding-bottom:1000px;">'.$company_name.'</strong><br/>'.$address.',<br>Contact No: '.$contact_no.', GSTIN\UIN : '.$gst_no.$msme_value.'<br></p></td>
			          		</tr>
			          	</table>
			          	<p><strong style="color:black; text-align:center; font-size:17px;"> TAX INVOICE</strong></p>
			          	';

			          	$html_1 .='<br>
						<table border= "1" cellpadding="1" top="100">
					        <tr>
					            <td rowspan="7" style="font-size:12px; width: 50%; margin-left:10px;">Shipped To: <br> '.$store_name.'<br> '.$str_address.'<br> Contact No: '.$str_mobile.'<br> GSTIN\UIN :'.$str_gst_no.'</td>
					            <td style="font-size:12px; width: 20%;"> Invoice No</td>
					            <td style="font-size:12px; width: 30%;">'.$invoice_no.'</td>
					        </tr>
					        <tr>
					            <td style="font-size:12px; width: 20%;"> Invoice Date</td>
					            <td style="font-size:12px; width: 30%;">'.date('d-M-Y', strtotime($createdate)).'</td>
					        </tr>
					        <tr>
					            <td style="font-size:12px; width: 20%;"> Outlet(s) Order No</td>
					            <td style="font-size:12px; width: 30%;">'.$order_no.'</td>
					        </tr>
					        <tr>
					            <td style="font-size:12px; width: 20%;"> Outlet(s) Order Date</td>
					            <td style="font-size:12px; width: 30%;">'.date('d-M-Y', strtotime($ordered)).'</td>
					        </tr>
					        <tr>
					            <td style="font-size:12px; width: 20%;"> Bill Type</td>
					            <td style="font-size:12px; width: 30%;">'.$type_view.'</td>
					        </tr>
					        <tr>
					            <td style="font-size:12px; width: 20%;"> Due Days</td>
					            <td style="font-size:12px; width: 30%;">'.$due_date.'</td>
					        </tr>
					        <tr>
					            <td style="font-size:12px; width: 20%;"> Dimensions</td>
					            <td style="font-size:12px; width: 30%;"> '.$length.' cm X '.$breadth.' cm X '.$height.' cm X '.$weight.' kg</td>
					        </tr>
					    </table>
					    ';

					    $html_1 .='<br><br>
							<table border= "1" cellpadding="1" top="100">
						        <tr>
						            <td style="font-size:11px; width: 5%;">S.No</td>
					            <td style="font-size:11px; width: 30%;">Description</td>
					            <td style="font-size:11px; text-align: center; width: 10%;">HSN</td>
					            <td style="font-size:11px; text-align: center; width: 10%;">MRP</td>
					            <td style="font-size:11px; text-align: center; width: 8%;">GST %</td>
					            <td style="font-size:11px; text-align: center; width: 9%;">Rate</td>
					            <td style="font-size:11px; text-align: center; width: 8%;">Qty</td>
					            <td style="font-size:11px; text-align: center; width: 8%;">Per</td>
					            <td style="font-size:11px; text-align: center; width: 12%;">Amount</td>
						        </tr>';

						        $num     = 1;
						        $sub_tot = 0;
				                $tot_gst = 0;
				                $net_tot = 0;
				                $tot_qty = 0;
						        foreach ($product_det as $key => $val) {
						        	$description = !empty($val['description'])?$val['description']:'';
				                    $hsn_code    = !empty($val['hsn_code'])?$val['hsn_code']:'';
				                    $gst_value   = !empty($val['gst_val'])?$val['gst_val']:'0';
				                    $unit_val    = !empty($val['unit_val'])?$val['unit_val']:'0';
				                    $price       = !empty($val['price'])?number_format((float)$val['price'], 2, '.', ''):'0';
				                    $order_qty   = !empty($val['order_qty'])?$val['order_qty']:'0';

				                    $gst_data  = $price - ($price * (100 / (100 + $gst_value)));
				                    $price_val = $price - $gst_data;
				                    $tot_price = $order_qty * $price_val;
				                    $sub_tot  += $tot_price;

				                    // GST Calculation
				                    $gst_val   = $order_qty * $gst_data;
				                    $tot_gst  += $gst_val;
				                    $total_val = $order_qty * $price;
				                    $net_tot  += $total_val;
				                    $tot_qty  += $order_qty;

				                    $html_1 .= '
				                    	<tr>
								            <td style="font-size:11px; width: 5%;">'.$num.'</td>
								            <td style="font-size:11px; width: 30%;">'.$description.'</td>
								            <td style="font-size:11px; text-align: center; width: 10%;">'.$hsn_code.'</td>
								            <td style="font-size:11px; text-align: center; width: 10%;">'.number_format((float)$price, 2, '.', '').'</td>
							            <td style="font-size:11px; text-align: center; width: 8%;">'.$gst_value.'</td>
								            <td style="font-size:11px; text-align: center; width: 9%;">'.number_format((float)$price_val, 2, '.', '').'</td>
								            <td style="font-size:11px; text-align: center; width: 8%;">'.$order_qty.'</td>
								            <td style="font-size:11px; text-align: center; width: 8%;">nos</td>
								            <td style="font-size:11px; text-align: center; width: 12%;">'.number_format((float)$tot_price, 2, '.', '').'</td>
								        </tr>
				                    ';

				                    $num++;
						        }

			          	$rowspan = '7';
					    if($gst_code == $str_gst_code)
					    {
					    	$rowspan = '8';
					    }

			            // Round Val Details
	                    $net_value  = round($net_tot);
	                    $total_dis  = $net_value * $discount / 100;
	                    $total_val  = $net_value - $total_dis - round($return_total);

	                    // Round Val Details
	                    $last_value = round($total_val);
	                    $rond_total = $last_value - $total_val;

					    $html_1 .= '
				            <tr>
				                <td rowspan ="'.$rowspan.'"  colspan="6"></td>
				                <td colspan="2" style="font-size:11px; text-align: right;">Qty</td>
				                <td style="font-size:11px; text-align: right;"> '.$tot_qty.'</td>
				                
				            </tr>
				            <tr>
				                <td colspan="2" style="font-size:11px; text-align: right;">Sub Total</td>
				                <td style="font-size:11px; text-align: right;"> '.number_format((float)$sub_tot, 2, '.', '').'</td>
				            </tr>';
				            if($gst_code == $str_gst_code)
				            {
				            	$gst_value = $tot_gst / 2;

				            	$html_1 .= '
				            		<tr>
						                <td colspan="2" style="font-size:11px; text-align: right;">SGST</td>
						                <td style="font-size:11px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
						            </tr>
						            <tr>
						                <td colspan="2" style="font-size:11px; text-align: right;">CGST</td>
						                <td style="font-size:11px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
						            </tr>
				            	';
				            }
				            else
				            {
				            	$html_1 .= '
				            		<tr>
						                <td colspan="2" style="font-size:11px; text-align: right;">IGST</td>
						                <td style="font-size:11px; text-align: right;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
						            </tr>
				            	';
				            }

				            if($discount != 0)
				            {
				            	$html_1 .='<tr>
					                <td colspan="2" style="font-size:11px; text-align: right;">Discount</td>
					                <td style="font-size:11px; text-align: right;"> '.number_format((float)$total_dis, 2, '.', '').'</td>
					            </tr>';	
				            }

				            if($return_total != 0)
				            {
				            	$return_data = round($return_total);
				            	
				            	$html_1 .='<tr>
					                <td colspan="2" style="font-size:11px; text-align: right;">Credit Note</td>
					                <td style="font-size:11px; text-align: right;"> '.number_format((float)$return_data, 2, '.', '').'</td>
					            </tr>';	
				            }

				            $html_1 .='<tr>
				                <td colspan="2" style="font-size:11px; text-align: right;">Round off</td>
				                <td style="font-size:11px; text-align: right;"> '.number_format((float)$rond_total, 2, '.', '').'</td>
				            </tr>
				            <tr>
				                <td colspan="2" style="font-size:11px; text-align: right;">Net Total</td>
				                <td style="font-size:11px; text-align: right;"> '.number_format((float)$last_value, 2, '.', '').'</td>
				            </tr>';
					    $html_1 .='</table>';

					    $html_1 .='<br><br>
						<table border= "1" cellpadding="1" top="100">
					        <tr>
					            <td rowspan="2" style="font-size:11px; width: 10%;">HSN</td>
					            <td rowspan="2" style="font-size:11px; width: 15%;">Taxable Value</td>';
					            if($gst_code == $str_gst_code)
					            {
					            	$html_1 .= '
					            		<td colspan="2" style="font-size:11px; width: 30%; text-align:center;">CGST</td>
					            		<td colspan="2" style="font-size:11px; width: 30%; text-align:center;">SGST</td>
					            	';
					            }
					            else
					            {
					            	$html_1 .= '
					            		<td colspan="2" style="font-size:11px; width: 60%; text-align:center;">IGST</td>
					            	';
					            }
						            
						       $html_1 .= '<td rowspan="2" style="font-size:11px; width: 15%;">Total Tax Amount</td>
					        </tr>
					        <tr>';
					        	if($gst_code == $str_gst_code)
					        	{
					        		$html_1 .= '
					        			<td style="font-size: 11px; text-align:center;">Rate</td>
							            <td style="font-size: 11px; text-align:center;">Amt</td>
							            <td style="font-size: 11px; text-align:center;">Rate</td>
							            <td style="font-size: 11px; text-align:center;">Amt</td>
					        		';
					        	}
					        	else
					        	{
					        		$html_1 .= '
					        			<td style="font-size: 11px; text-align:center;">Rate</td>
					            		<td style="font-size: 11px; text-align:center;">Amt</td>
					        		';
					        	}
					        $html_1 .='</tr>';
					        $tot_price = 0;
							$tot_gst   = 0;
					        foreach ($tax_det as $key => $value) {
					        	$hsn_code    = !empty($value['hsn_code'])?$value['hsn_code']:'';
			                    $gst_val     = !empty($value['gst_val'])?$value['gst_val']:'0';
			                    $gst_value   = !empty($value['gst_value'])?$value['gst_value']:'0';
			                    $price_value = !empty($value['price_value'])?$value['price_value']:'0';

			                    $tot_gst    += $gst_value;
					            $tot_price  += $price_value;

			                    $html_1 .= '
			                    	<tr>
			                    		<td style="font-size: 11px; text-align:left;"> '.$hsn_code.'</td>
			                    		<td style="font-size: 11px; text-align:left;"> '.number_format((float)$price_value, 2, '.', '').'</td>';
			                    		if($gst_code == $str_gst_code)
			                    		{
			                    			$state_value = $gst_value / 2;
					                        $gst_calc    = $gst_val / 2;
			                    			$html_1 .= '
			                    				<td style="font-size: 11px; text-align:left;"> '.$gst_calc.' %</td>
					                    		<td style="font-size: 11px; text-align:left;"> '.number_format((float)$state_value, 2, '.', '').'</td>
					                    		<td style="font-size: 11px; text-align:left;"> '.$gst_calc.' %</td>
					                    		<td style="font-size: 11px; text-align:left;"> '.number_format((float)$state_value, 2, '.', '').'</td>
			                    			';
			                    		}
			                    		else
			                    		{
			                    			$html_1 .= '
			                    				<td style="font-size: 11px; text-align:left;"> '.$gst_val.' %</td>
				                    			<td style="font-size: 11px; text-align:left;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
			                    			';
			                    		}
			                    		$html_1 .='<td style="font-size: 11px; text-align:left;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
			                    	</tr>
			                    ';
					        }
					        $html_1 .= '
					        	<tr>
					        		<td style="font-size: 11px; text-align:right;"> Total </td>
					        		<td style="font-size: 11px; text-align:left;"> '.number_format((float)$tot_price, 2, '.', '').'</td>';
					        		if($gst_code == $str_gst_code)
					        		{
					        			$state_val = $tot_gst / 2;

					        			$html_1 .= '
						        			<td style="font-size: 11px; text-align:left;"> </td>
							        		<td style="font-size: 11px; text-align:left;"> '.number_format((float)$state_val, 2, '.', '').'</td>
							        		<td style="font-size: 11px; text-align:left;"> </td>
							        		<td style="font-size: 11px; text-align:left;"> '.number_format((float)$state_val, 2, '.', '').'</td>
						        		';
					        		}
					        		else
					        		{
					        			$html_1 .= '
						        			<td style="font-size: 11px; text-align:left;"> </td>
							        		<td style="font-size: 11px; text-align:left;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
						        		';
					        		}

					        		$html_1 .='<td style="font-size: 11px; text-align:left;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
					        	</tr>
					        ';
					    $html_1 .='</table>';

					    $html_1 .='<br><br>
						<table border= "1" cellpadding="1" top="100">
					        <tr>
					            <td colspan="5" style="font-size:11px; width: 13%;"> Account Name</td>
					            <td colspan="5" style="font-size:10px; width: 42%;"> '.$account_name.'</td>
					            <td rowspan="5" style="font-size:10px; width: 45%;">
					            	<span> for '.$company_name.'</span>
					            	<br><br><br>
					            	<p style="text-align: right; "> Authorised signature </p>
					            </td>
					        </tr>
					        <tr>
					        	<td colspan="5" style="font-size:11px; width: 13%;"> Account No</td>
					            <td colspan="5" style="font-size:10px; width: 42%;"> '.$account_no.'</td>
					        </tr>
					        <tr>
					        	<td colspan="5" style="font-size:11px; width: 13%;"> Bank Name</td>
					            <td colspan="5" style="font-size:10px; width: 42%;"> '.$bank_name.'</td>
					        </tr>
					        <tr>
					        	<td colspan="5" style="font-size:11px; width: 13%;"> Branch Name</td>
					            <td colspan="5" style="font-size:10px; width: 42%;"> '.$branch_name.'</td>
					        </tr>
					        <tr>
					        	<td colspan="5" style="font-size:11px; width: 13%;"> IFSC Code</td>
					            <td colspan="5" style="font-size:10px; width: 42%;"> '.$ifsc_code.'</td>
					        </tr>
					    </table>';
			          	$pdf->writeHTML($html_1, true, false, true, false, '');

		          	}


	       			$pdf->Output($invoice_no.'_'.date('d-F-Y H:i:s').'.pdf', 'I');
    			}
    		}
    	}

    	public function print_json($param1="", $param2="", $param3="")
    	{
    		$bill_data = array(
				'random_value' => $param1,
				'method'       => '_detailInvoice',
			);

			$bill_val  = avul_call(API_URL.'order/api/invoice_manage_order', $bill_data);
			$data_res  = $bill_val['data'];

			if($data_res)
			{
				$bill_det = !empty($data_res['bill_details'])?$data_res['bill_details']:'';
				$dis_det  = !empty($data_res['distributor_details'])?$data_res['distributor_details']:'';
				$str_det  = !empty($data_res['store_details'])?$data_res['store_details']:'';
				$pdt_det  = !empty($data_res['product_details'])?$data_res['product_details']:'';
				$tax_det  = !empty($data_res['tax_details'])?$data_res['tax_details']:'';

				// Bill Details
                $invoice_no   = !empty($bill_det['invoice_no'])?$bill_det['invoice_no']:'';
                $invoice_date = !empty($bill_det['createdate'])?$bill_det['createdate']:'';

                // Distributor Details
                $dis_name     = !empty($dis_det['company_name'])?$dis_det['company_name']:'';
                $dis_gst_no   = !empty($dis_det['gst_no'])?$dis_det['gst_no']:'';
                $dis_address  = !empty($dis_det['address'])?$dis_det['address']:'';
                $dis_gst_code = !empty($dis_det['gst_code'])?$dis_det['gst_code']:'0';
                $dis_pincode  = !empty($dis_det['pincode'])?$dis_det['pincode']:'';

                // Store Details
                $str_name     = !empty($str_det['company_name'])?$str_det['company_name']:'';
                $str_gst_no   = !empty($str_det['gst_no'])?$str_det['gst_no']:'';
                $str_address  = !empty($str_det['address'])?$str_det['address']:'';
                $str_gst_code = !empty($str_det['gst_code'])?$str_det['gst_code']:'0';
                $str_pincode  = !empty($str_det['pincode'])?$str_det['pincode']:'';

                $itemList   = [];
		    	$num        = 1;
		    	$total_val  = 0;
		    	$total_gst  = 0;
		    	$total_bill = 0;
		    	foreach ($pdt_det as $key => $pdt_val) {
                    $description = !empty($pdt_val['description'])?$pdt_val['description']:'';
                    $hsn_code    = !empty($pdt_val['hsn_code'])?$pdt_val['hsn_code']:'';
                    $gst_val     = !empty($pdt_val['gst_val'])?$pdt_val['gst_val']:'';
                    $pdt_price   = !empty($pdt_val['price'])?number_format((float)$pdt_val['price'], 2, '.', ''):'';
                    $order_qty   = !empty($pdt_val['order_qty'])?$pdt_val['order_qty']:'';
                    $unit_val    = !empty($pdt_val['unit_val'])?$pdt_val['unit_val']:'';

                    $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_val)));
	                $price_val  = $pdt_price - $gst_data;
	                $tot_price  = $order_qty * $price_val;
	                $total_val += $tot_price;

	                // GST Calculation
	                $pdt_gst    = $pdt_price - ($pdt_price * (100 / (100 + $gst_val)));
	                $tot_gst    = $order_qty * $pdt_gst;
	                $total_gst += $tot_gst;

	                // Total Bill Calculation
	                $tot_amt     = $order_qty * $pdt_price;
	                $total_bill += $tot_amt;

	                if($unit_val == 1)
	                {
	                	$qtyUnit = 'KGS';
	                }
	                else if($unit_val == 1)
	                {
	                	$qtyUnit = 'KGS';
	                }
	                else
	                {
	                	$qtyUnit = 'NOS';
	                }

	                if($dis_gst_code == $str_gst_code)
	                {
	                	$gst_res  = $gst_val / 2;
	                	$sgstRate = (float)$gst_res;
	                	$cgstRate = (float)$gst_res;
	                	$igstRate = 0;
	                }
	                else
	                {
	                	$sgstRate = 0;
	                	$cgstRate = 0;
	                	$igstRate = (float)$gst_val;
	                }

	                $pdt_value = number_format((float)$tot_price, 2, '.', '');

	                $itemList[] = array(
	                	'itemNo'        => $num,
	                	'productName'   => $description,
	                	'productDesc'   => $description,
	                	'hsnCode'       => (float)$hsn_code,
	                	'quantity'      => (float)$order_qty,
	                	'qtyUnit'       => $qtyUnit,
	                	'taxableAmount' => (float)$pdt_value,
	                	'sgstRate'      => $sgstRate,
	                	'cgstRate'      => $cgstRate,
	                	'igstRate'      => $igstRate,
	                	'cessRate'      => 0,
	                	'cessNonAdvol'  => 0,
	                );

	                $num++;
		    	}

		    	if($dis_gst_code == $str_gst_code)
	            {
	            	$gst_value = $total_gst / 2;
	            	$cgstValue = number_format((float)$gst_value, 2, '.', '');
	            	$sgstValue = number_format((float)$gst_value, 2, '.', '');
	            	$igstValue = 0;
	            }
	            else
	            {
	            	$cgstValue = 0;
	            	$sgstValue = 0;
	            	$igstValue = number_format((float)$total_gst, 2, '.', '');
	            }

	            $bill_total = number_format((float)$total_val, 2, '.', '');

	            $net_value  = round($total_bill);
	           	$rond_total = $net_value - $total_bill;

	           	$bill_round = number_format((float)$rond_total, 2, '.', '');
	           	$bill_net   = number_format((float)$net_value, 2, '.', '');

		    	$bill_details[] = array(
		    		'userGstin'           => $dis_gst_no,
		    		'supplyType'          => '',
		    		'subSupplyType'       => '',
		    		'docType'             => 'INV',
		    		'docNo'               => $invoice_no,
		    		'docDate'             => date('d/m/Y', strtotime($invoice_date)),
		    		'transType'           => '',
		    		'fromGstin'           => $dis_gst_no,
		    		'fromTrdName'         => $dis_name,
		    		'fromAddr1'           => $dis_address,
		    		'fromAddr2'           => '',
		    		'fromPlace'           => '',
		    		'fromPincode'         => (int)$dis_pincode,
		    		'fromStateCode'       => (int)$dis_gst_code,
		    		'actualFromStateCode' => (int)$dis_gst_code,
		    		'toGstin'             => $dis_gst_no,
		    		'toTrdName'           => $str_name,
		    		'toAddr1'             => $str_address,
		    		'toAddr2'             => '',
		    		'toPlace'             => '',
		    		'toPincode'           => (int)$str_pincode,
		    		'toStateCode'         => (int)$str_gst_code,
		    		'actualToStateCode'   => (int)$str_gst_code,
		    		'totalValue'          => (float)$bill_total,
		    		'cgstValue'           => (float)$cgstValue,
		    		'sgstValue'           => (float)$sgstValue,
		    		'igstValue'           => (float)$igstValue,
		    		'cessValue'           => 0,
		    		'TotNonAdvolVal'      => 0,
		    		'OthValue'            => (float)$bill_round,
		    		'totInvValue'         => (float)$bill_net,
		    		'transMode'           => '',
		    		'transDistance'       => '',
		    		'transporterName'     => '',
		    		'transporterId'       => '',
		    		'transDocNo'          => '',
		    		'transDocDate'        => '',
		    		'vehicleNo'           => '',
		    		'vehicleType'         => '',
		    		'mainHsnCode'         => '',
		    		'itemList'            => $itemList,
		    	);

		    	// Header Value
		    	$file_name = 'Ewaybill_'.$invoice_no.'_'.date('d-M-Y');
		    	
		    	header('Content-Type: application/json');
		    	header('Content-Disposition: filename="'.$file_name.'.json"');

		    	$response['version']   = '1.0.0219';
		        $response['billLists'] = $bill_details;
		        echo json_encode($response);
		        return;
			}
    	}
	}
?>
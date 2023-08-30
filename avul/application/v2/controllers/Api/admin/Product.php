<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Product extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('order_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Count List
		// ***************************************************
		public function order_product($param1="", $param2="", $param3="")
		{
			$method      = $this->input->post('method');

			// Month Details
			$month_val   = date('m');
			$year_val    = date('Y');
			$month_count = cal_days_in_month(CAL_GREGORIAN, $month_val, $year_val);
			$start_date  = '01-'.$month_val.'-'.$year_val;
			$end_date    = $month_count.'-'.$month_val.'-'.$year_val;
			$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
		    $end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

			if($method == '_orderProduct')
			{
				// Master Value
		    	// **********************************************
		    	$where_1 = array(
					'A._ordered >='     => $start_value,
					'A._ordered <='     => $end_value,
					'A.invoice_process' => '1',
					'A.order_status !=' => '8',
    				'A.published'       => '1'
				);

				$limit  = 10;
				$offset = 0;

				$option['order_by']   = 'A.id';
				$option['disp_order'] = 'DESC';

				$group_by  = 'A.type_id';
				$column    = 'B.description, SUM(A.order_qty) AS order_qty';
				$data_list = $this->order_model->getOrderDetailsJoin($where_1, $limit, $offset, 'result', '', '', $option, '', $column, $group_by);

				if($data_list)
				{
					$order_data  = [];
					foreach ($data_list as $key => $val) {

            			$order_data[] = array(
            				'description' => empty_check($val->description),
            				'order_qty'   => zero_check($val->order_qty),
            			);
					}

					$invoicesort = array();
				    foreach ($order_data as $key => $row)
				    {
				        $invoicesort[$key] = $row['order_qty'];
				    }

				    array_multisort($invoicesort, SORT_DESC, $order_data);

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $order_data;
		    		echo json_encode($response);
			        return;
				}
				else
				{
					$response['status']  = 0;
			        $response['message'] = "No data found"; 
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

		// Count List
		// ***************************************************
		public function invoice_product($param1="", $param2="", $param3="")
		{
			$method      = $this->input->post('method');

			// Month Details
			$month_val   = date('m');
			$year_val    = date('Y');
			$month_count = cal_days_in_month(CAL_GREGORIAN, $month_val, $year_val);
			$start_date  = '01-'.$month_val.'-'.$year_val;
			$end_date    = $month_count.'-'.$month_val.'-'.$year_val;
			$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
		    $end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

		    if($method == '_invoiceProduct')
		    {
		    	// Master Value
		    	// **********************************************
		    	$where_1 = array(
					'A._invoice >='     => $start_value,
					'A._invoice <='     => $end_value,
					'A.invoice_process' => '1',
					'A.order_status !=' => '8',
    				'A.published'       => '1'
				);

				$limit  = 10;
				$offset = 0;

				$option['order_by']   = 'A.id';
				$option['disp_order'] = 'DESC';

				$group_by  = 'A.type_id';
				$column    = 'B.description, SUM(A.order_qty) AS order_qty';
				$data_list = $this->order_model->getOrderDetailsJoin($where_1, $limit, $offset, 'result', '', '', $option, '', $column, $group_by);

				if($data_list)
				{
					$order_data  = [];
					foreach ($data_list as $key => $val) {

            			$order_data[] = array(
            				'description' => empty_check($val->description),
            				'invoice_qty' => zero_check($val->order_qty),
            			);
					}

					$invoicesort = array();
				    foreach ($order_data as $key => $row)
				    {
				        $invoicesort[$key] = $row['invoice_qty'];
				    }

				    array_multisort($invoicesort, SORT_DESC, $order_data);

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $order_data;
		    		echo json_encode($response);
			        return;
				}
				else
				{
					$response['status']  = 0;
			        $response['message'] = "No data found"; 
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
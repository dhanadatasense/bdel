<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Chart extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('target_model');
			$this->load->model('order_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Count List
		// ***************************************************
		public function target_chart($param1="", $param2="", $param3="")
		{
			$method      = $this->input->post('method');

			if($method == '_targetChart')
			{
				$month_val   = date('m');
				$year_val    = date('Y');

				// Get month name
				$dateObj     = DateTime::createFromFormat('!m', $month_val);
				$monthName   = $dateObj->format('F'); // March

				// Get month count
				$month_count = cal_days_in_month(CAL_GREGORIAN, $month_val, $year_val);

				// Month Details
				$str_val = date('d-m-Y', strtotime('01-'.$month_val.'-'.$year_val));
				$end_val = date('d-m-Y', strtotime($month_count.'-'.$month_val.'-'.$year_val));

				// Target Value
				$whr_1 = array(
					'month_name' => $monthName,
    				'year_name'  => $year_val,
    				'published'  => '1'
				);

				$res_1 = $this->target_model->getTargetDetails($whr_1,'','',"row",array(),array(),array(),TRUE,'SUM(target_val) AS target_val, SUM(achieve_val) AS achieve_val');

				if($res_1)
				{
					$chart_data[] = array(
						'target_val'  => zero_check($res_1->target_val),
						'achieve_val' => zero_check($res_1->achieve_val),
					);

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $chart_data;
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
		public function performance_chart($param1="", $param2="", $param3="")
		{
			$method      = $this->input->post('method');

			if($method == '_performanceChart')
			{
				$month_val   = date('m');
				$year_val    = date('Y');

				// Get month name
				$dateObj     = DateTime::createFromFormat('!m', $month_val);
				$monthName   = $dateObj->format('F'); // March

				// Get month count
				$month_count = cal_days_in_month(CAL_GREGORIAN, $month_val, $year_val);

				// Month Details
				$str_val = date('d-m-Y', strtotime('01-'.$month_val.'-'.$year_val));
				$end_val = date('d-m-Y', strtotime($month_count.'-'.$month_val.'-'.$year_val));

				$start_value = date('Y-m-d H:i:s', strtotime($str_val. '00:00:00'));
				$end_value   = date('Y-m-d H:i:s', strtotime($end_val. '23:59:59'));

				$column  = 'SUM(price * order_qty) AS `order_value`';

				// Success Order
				$whr_1 = array(
					'_ordered >=' => $start_value,
					'_ordered <=' => $end_value,
					'published'   => '1',
				);

				$success_data = $this->order_model->getOrderDetails($whr_1, '', '', 'row', '', '', '', '', $column);

				$success_val  = round($success_data->order_value);

				// Process Order
				$whr_2 = array(
					'_processing >=' => $start_value,
					'_processing <=' => $end_value,
					'published'      => '1',
				);

				$process_data = $this->order_model->getOrderDetails($whr_2, '', '', 'row', '', '', '', '', $column);

				$process_val  = round($process_data->order_value);

				// Cancel Order
				$whr_3 = array(
					'_canceled >=' => $start_value,
					'_canceled <=' => $end_value,
					'published'    => '1',
				);

				$cancel_data = $this->order_model->getOrderDetails($whr_3, '', '', 'row', '', '', '', '', $column);

				$cancel_val  = round($cancel_data->order_value);

				// Invoice Order
				$whr_4 = array(
					'_invoice >='     => $start_value,
					'_invoice <='     => $end_value,
					'invoice_process' => '1',
					'cancel_status'   => '1',
					'published'       => '1',
				);

				$invoice_data = $this->order_model->getOrderDetails($whr_4, '', '', 'row', '', '', '', '', $column);

				$invoice_val  = round($invoice_data->order_value);

				// Delivery Order
				$whr_5 = array(
					'_delivery >='    => $start_value,
					'_delivery <='    => $end_value,
					'invoice_process' => '2',
					'published'       => '1',
				);

				$delivery_data = $this->order_model->getOrderDetails($whr_5, '', '', 'row', '', '', '', '', $column);

				$delivery_val  = round($delivery_data->order_value);

				// Invoice Cancel Order
				$whr_6 = array(
					'_delete >='      => $start_value,
					'_delete <='      => $end_value,
					'cancel_status'   => '2',
					'published'       => '1',
				);

				$invCancel_data = $this->order_model->getOrderDetails($whr_6, '', '', 'row', '', '', '', '', $column);

				$invCancel_val  = round($invCancel_data->order_value);

				$chart_data[] = array(
					'success_val'    => $success_val,
					'process_val'    => $process_val,
					'cancel_val'     => $cancel_val,
					'invoice_val'    => $invoice_val,
					'delivery_val'   => $delivery_val,
					'invCancel_val'  => $invCancel_val,
				);

				$response['status']  = 1;
		        $response['message'] = "Success"; 
		        $response['data']    = $chart_data;
		        echo json_encode($response);
		        return;
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
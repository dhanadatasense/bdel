<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Dashboard extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('commom_model');
			$this->load->model('distributors_model');
			$this->load->model('vendors_model');
			$this->load->model('outlets_model');
			$this->load->model('employee_model');
			$this->load->model('attendance_model');
			$this->load->model('order_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Count List
		// ***************************************************
		public function count_list($param1="", $param2="", $param3="")
		{	
			$method      = $this->input->post('method');

			if($method == '_adminDashboard')
			{
				// Master Value
		    	// **********************************************
		    	$where_1 = array(
					'published' => '1',
					'status'    => '1',
				);

				// State Count
				$state_val = $this->commom_model->getState($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				$state_count = !empty($state_val[0]->autoid)?$state_val[0]->autoid:'0';

				// City Count
				$city_val = $this->commom_model->getCity($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				$city_count = !empty($city_val[0]->autoid)?$city_val[0]->autoid:'0';

				// Zone Count
				$zone_val = $this->commom_model->getZone($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				$zone_count = !empty($zone_val[0]->autoid)?$zone_val[0]->autoid:'0';

				// Unit Count
				$unit_val = $this->commom_model->getUnit($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				$unit_count = !empty($unit_val[0]->autoid)?$unit_val[0]->autoid:'0';

				// Category Count
				$category_val = $this->commom_model->getCategory($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				$category_count = !empty($category_val[0]->autoid)?$category_val[0]->autoid:'0';

				// Product Count
				$product_val = $this->commom_model->getProduct($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				$product_count = !empty($product_val[0]->autoid)?$product_val[0]->autoid:'0';

				// Vendors Count
				$vendor_val = $this->vendors_model->getVendors($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				$vendor_count = !empty($vendor_val[0]->autoid)?$vendor_val[0]->autoid:'0';

				// Outlets Count
				$outlet_val = $this->outlets_model->getOutlets($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				$outlet_count = !empty($outlet_val[0]->autoid)?$outlet_val[0]->autoid:'0';

				// Distributors Count
				$distributor_val = $this->distributors_model->getDistributors($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				$distributor_count = !empty($distributor_val[0]->autoid)?$distributor_val[0]->autoid:'0';

				// Employee Count
				$employee_val = $this->employee_model->getEmployee($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				$employee_count = !empty($employee_val[0]->autoid)?$employee_val[0]->autoid:'0';

				$dashboard_count = array(
					'state_count'       => $state_count,
					'city_count'        => $city_count,
					'zone_count'        => $zone_count,
					'unit_count'        => $unit_count,
					'category_count'    => $category_count,
					'product_count'     => $product_count,
					'vendor_count'      => $vendor_count,
					'outlet_count'      => $outlet_count,
					'distributor_count' => $distributor_count,
					'employee_count'    => $employee_count,
				);


				// Attendance Details
				// **********************************************

				// Today
				$today_value = date('Y-m-d');

				// Employee Count
				$where_1 = array(
					'log_type'  => '2',
					'published' => '1',
				);

				$tot_employee_val = $this->employee_model->getEmployee($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				$tot_employee_count = !empty($tot_employee_val[0]->autoid)?$tot_employee_val[0]->autoid:'0';

				// Active Employee Count
				$where_2 = array(
					'log_type'  => '2',
					'status'    => '1',
					'published' => '1',
				);

				$act_employee_val = $this->employee_model->getEmployee($where_2,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				$acv_employee_count = !empty($act_employee_val[0]->autoid)?$act_employee_val[0]->autoid:'0';

				// Present Employee Count
				$where_3 = array(
					'c_date'    => $today_value,
					'status'    => '1',
					'published' => '1',
				);

				$column    = 'id';
	        	$groupby   = 'emp_id';
	        	$att_data  = $this->attendance_model->getAttendance($where_3, '', '', 'result', '', '', '', '', $column, $groupby);

	        	$pre_count = 0;
	        	if(!empty($att_data))
	        	{
	        		$pre_count = count($att_data);
	        	}

	        	// Absent Employee Count
	        	$abs_count = $acv_employee_count - $pre_count;

	        	$attendance_report = array(
	        		'total_employee'   => strval($tot_employee_count),
	        		'active_employee'  => strval($acv_employee_count),
	        		'present_employee' => strval($pre_count),
	        		'absent_employee'  => strval($abs_count),
	        	);

	        	// Order Count
				$month = date('m');
				$year  = date('Y');

				// Today Value
				$today = date('Y-m-d');

				$today_start = date('Y-m-d H:i:s', strtotime($today. '00:00:00'));
			    $today_end   = date('Y-m-d H:i:s', strtotime($today. '23:59:59'));

				$today_where = array(
					'createdate >='      => $today_start,
					'createdate <='      => $today_end,
					'product_process !=' => '8',
					'published'          => '1',
					'status'             => '1',
				);

				$today_col = 'id, order_no, price, order_qty';

				$today_val = $this->order_model->getOrderDetails($today_where, '', '', 'result', '', '', '', '', $today_col);

				$today_total = 0;
				if($today_val)
				{
					foreach ($today_val as $key => $today_data) {
						$order_no     = !empty($today_data->order_no)?$today_data->order_no:'';
						$price        = !empty($today_data->price)?$today_data->price:'0';
						$order_qty    = !empty($today_data->order_qty)?$today_data->order_qty:'0';
						$total_val    = $order_qty * number_format((float)$price, 2, '.', '');
						$today_total += $total_val;
					}
				}

				$today_value = number_format((float)round($today_total), 2, '.', '');

				$todayCount_whr = array(
					'createdate >='   => $today_start,
					'createdate <='   => $today_end,
					'order_status !=' => '8',
					'published'       => '1',
					'status'          => '1',
				);

				$today_count = $this->order_model->getOrder($todayCount_whr,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				$todayCount = !empty($today_count[0]->autoid)?$today_count[0]->autoid:'0';

				// Week Value
				$date_val   = new DateTime($today);
				$week_val   = $date_val->format("W");
				$week_array = getStartAndEndDate($week_val, $year);
				$week_start = $week_array['week_start'];
				$week_end   = $week_array['week_end'];

				$week_start = date('Y-m-d H:i:s', strtotime($week_start. '00:00:00'));
			    $week_end   = date('Y-m-d H:i:s', strtotime($week_end. '23:59:59'));

				$week_where = array(
					'createdate >='      => $week_start,
					'createdate <='      => $week_end,
					'product_process !=' => '8',
					'published'          => '1',
					'status'             => '1',
				);

				$week_col = 'id, order_no, price, order_qty';

				$week_val = $this->order_model->getOrderDetails($week_where, '', '', 'result', '', '', '', '', $week_col);

				$week_total = 0;
				if($week_val)
				{
					foreach ($week_val as $key => $week_data) {
						$price       = !empty($week_data->price)?$week_data->price:'0';
						$order_qty   = !empty($week_data->order_qty)?$week_data->order_qty:'0';
						$total_val   = $order_qty * number_format((float)$price, 2, '.', '');
						$week_total += $total_val;
					}
				}

				$week_value = number_format((float)round($week_total), 2, '.', '');

				$weekCount_whr = array(
					'createdate >='   => $week_start,
					'createdate <='   => $week_end,
					'order_status !=' => '8',
					'published'       => '1',
					'status'          => '1',
				);

				$week_count = $this->order_model->getOrder($weekCount_whr,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				$weekCount = !empty($week_count[0]->autoid)?$week_count[0]->autoid:'0';

				// Month Value
				$month_count = cal_days_in_month(CAL_GREGORIAN, $month, $year);
				$month_start = '01'.'-'.$month.'-'.$year;
				$start_date  = date('Y-m-d', strtotime($month_start));
				$month_end   = $month_count.'-'.$month.'-'.$year;
				$end_date    = date('Y-m-d', strtotime($month_end));

				$month_start = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
			    $month_end   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				$month_where = array(
					'createdate >='      => $month_start,
					'createdate <='      => $month_end,
					'product_process !=' => '8',
					'published'          => '1',
					'status'             => '1',
				);

				$month_col = 'id, order_no, price, order_qty';

				$month_val = $this->order_model->getOrderDetails($month_where, '', '', 'result', '', '', '', '', $month_col);

				$month_total = 0;
				if($month_val)
				{
					foreach ($month_val as $key => $month_data) {
						$price        = !empty($month_data->price)?$month_data->price:'0';
						$order_qty    = !empty($month_data->order_qty)?$month_data->order_qty:'0';
						$total_val    = $order_qty * number_format((float)$price, 2, '.', '');
						$month_total += $total_val;
					}
				}

				$month_value = number_format((float)round($month_total), 2, '.', '');

				$monthCount_whr = array(
					'createdate >='   => $month_start,
					'createdate <='   => $month_end,
					'order_status !=' => '8',
					'published'       => '1',
					'status'          => '1',
				);

				$month_count = $this->order_model->getOrder($monthCount_whr,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				$monthCount  = !empty($month_count[0]->autoid)?$month_count[0]->autoid:'0';

				$sale_report = array(
					'today_count' => strval($todayCount),
					'today_value' => strval($today_value),
					'week_count'  => strval($weekCount),
					'week_value'  => strval($week_value),
					'month_count' => strval($monthCount),
					'month_value' => strval($month_value),
				);

				$data_list = array(
					'dashboard_count'   => $dashboard_count,
					'attendance_report' => $attendance_report,
					'sale_report'       => $sale_report,
				);

				$response['status']  = 1;
		        $response['message'] = "Success"; 
		        $response['data']    = $data_list;
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
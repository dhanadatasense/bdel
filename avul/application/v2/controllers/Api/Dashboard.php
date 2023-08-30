<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('commom_model');
		$this->load->model('vendors_model');
		$this->load->model('invoice_model');
		$this->load->model('payment_model');
		$this->load->model('purchase_model');
		$this->load->model('outlets_model');
		$this->load->model('distributors_model');
		$this->load->model('employee_model');
		$this->load->model('attendance_model');
		$this->load->model('workorder_model');
		$this->load->model('distributorpurchase_model');
		$this->load->model('order_model');
		$this->load->model('production_model');
		$this->load->model('assignproduct_model');
		$this->load->model('target_model');
		$this->load->model('managers_model');
	}

	public function index()
	{
		echo "Test";
	}

	// Admin Dashboard
	// ***************************************************
	public function admin_dashboard($param1 = "", $param2 = "", $param3 = "")
	{
		$method      = $this->input->post('method');
		$financ_year = $this->input->post('financial_year');
		$month_val   = date('m');
		$year_val    = date('Y');
		$month_count = cal_days_in_month(CAL_GREGORIAN, $month_val, $year_val);

		if ($method == '_adminDashboard') {
			$error = FALSE;
			$errors = array();
			$required = array('financial_year');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}

			if (count($errors) == 0) {
				// Master Value
				// **********************************************
				$where_1 = array(
					'published' => '1',
					'status'    => '1',
				);

				$where_2 = array(
					// 'financial_year' => $financ_year,
					'published'      => '1'
				);

				$dateObj   = DateTime::createFromFormat('!m', $month_val);
				$monthName = $dateObj->format('F'); // March

				$where_3 = array(
					'target_val !=' => '0',
					'month_name'    => $monthName,
					'year_name'     => $year_val,
					'published'     => '1'
				);

				$start_date  = '01-' . $month_val . '-' . $year_val;
				$end_date    = $month_count . '-' . $month_val . '-' . $year_val;
				$start_value = date('Y-m-d H:i:s', strtotime($start_date . '00:00:00'));
				$end_value   = date('Y-m-d H:i:s', strtotime($end_date . '23:59:59'));

				$where_4 = array(
					'_ordered >='     => $start_value,
					'_ordered <='     => $end_value,
					'invoice_process' => '1',
					'order_status !=' => '8',
					'published'       => '1'
				);

				$where_5 = array(
					'_invoice >='     => $start_value,
					'_invoice <='     => $end_value,
					'invoice_process' => '1',
					'order_status !=' => '8',
					'published'       => '1'
				);

				$limit  = 5;
				$offset = 0;

				$option['order_by']   = 'id';
				$option['disp_order'] = 'DESC';

				// **********************************************

				// State Count
				$state_val = $this->commom_model->getState($where_1, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

				$state_count = !empty($state_val[0]->autoid) ? $state_val[0]->autoid : '0';

				// City Count
				$city_val = $this->commom_model->getCity($where_1, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

				$city_count = !empty($city_val[0]->autoid) ? $city_val[0]->autoid : '0';

				// Zone Count
				$zone_val = $this->commom_model->getZone($where_1, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

				$zone_count = !empty($zone_val[0]->autoid) ? $zone_val[0]->autoid : '0';

				// Unit Count
				$unit_val = $this->commom_model->getUnit($where_1, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

				$unit_count = !empty($unit_val[0]->autoid) ? $unit_val[0]->autoid : '0';

				// Category Count
				$category_val = $this->commom_model->getCategory($where_1, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

				$category_count = !empty($category_val[0]->autoid) ? $category_val[0]->autoid : '0';

				// Product Count
				$product_val = $this->commom_model->getProduct($where_1, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

				$product_count = !empty($product_val[0]->autoid) ? $product_val[0]->autoid : '0';

				// Vendors Count
				$vendor_val = $this->vendors_model->getVendors($where_1, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

				$vendor_count = !empty($vendor_val[0]->autoid) ? $vendor_val[0]->autoid : '0';

				// Outlets Count
				$outlet_val = $this->outlets_model->getOutlets($where_1, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

				$outlet_count = !empty($outlet_val[0]->autoid) ? $outlet_val[0]->autoid : '0';

				// Distributors Count
				$distributor_val = $this->distributors_model->getDistributors($where_1, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

				$distributor_count = !empty($distributor_val[0]->autoid) ? $distributor_val[0]->autoid : '0';

				// Employee Count
				$employee_val = $this->employee_model->getEmployee($where_1, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

				$employee_count = !empty($employee_val[0]->autoid) ? $employee_val[0]->autoid : '0';

				// Attendace List
				$attendance_list = $this->attendance_model->getAttendance($where_1, $limit, $offset, 'result', '', '', $option);

				$attendance_data = [];
				if ($attendance_list) {
					foreach ($attendance_list as $key => $val_1) {
						$att_id     = !empty($val_1->id) ? $val_1->id : '';
						$emp_name   = !empty($val_1->emp_name) ? $val_1->emp_name : '';
						$emp_type   = !empty($val_1->emp_type) ? $val_1->emp_type : '';
						$store_name = !empty($val_1->store_name) ? $val_1->store_name : '';
						$att_type   = !empty($val_1->attendance_type) ? $val_1->attendance_type : '';
						$reason     = !empty($val_1->reason) ? $val_1->reason : '';
						$c_date     = !empty($val_1->c_date) ? $val_1->c_date : '';
						$in_time    = !empty($val_1->in_time) ? $val_1->in_time : '';
						$out_time   = !empty($val_1->out_time) ? $val_1->out_time : '';
						$log_status = !empty($val_1->log_status) ? $val_1->log_status : '0';

						$attendance_data[] = array(
							'attendance_id'   => $att_id,
							'emp_name'        => $emp_name,
							'emp_type'        => $emp_type,
							'store_name'      => $store_name,
							'attendance_type' => $att_type,
							'reason'          => $reason,
							'c_date'          => date('d-M-Y', strtotime($c_date)),
							'in_time'         => $in_time,
							'out_time'        => $out_time,
							'log_status'      => $log_status,
						);
					}
				}

				// Purchase List
				$purchase_list = $this->purchase_model->getPurchase($where_2, $limit, $offset, 'result', '', '', $option);

				$purchase_data = [];
				if ($purchase_list) {
					foreach ($purchase_list as $key => $val_2) {

						$po_id          = isset($val_2->id) ? $val_2->id : '';
						$po_no          = isset($val_2->po_no) ? $val_2->po_no : '';
						$vendor_id      = isset($val_2->vendor_id) ? $val_2->vendor_id : '';
						$order_date     = isset($val_2->order_date) ? $val_2->order_date : '';
						$order_status   = isset($val_2->order_status) ? $val_2->order_status : '';
						$financial_year = isset($val_2->financial_year) ? $val_2->financial_year : '';
						$bill           = isset($val_2->bill) ? $val_2->bill : '';
						$published      = isset($val_2->published) ? $val_2->published : '';
						$status         = isset($val_2->status) ? $val_2->status : '';
						$createdate     = isset($val_2->createdate) ? $val_2->createdate : '';

						// Vendor Name
						$where_1     = array('id' => $vendor_id);
						$data_val    = $this->vendors_model->getVendors($where_1);
						$vendor_name = isset($data_val[0]->company_name) ? $data_val[0]->company_name : '';
						$contact_no  = isset($data_val[0]->contact_no) ? $data_val[0]->contact_no : '';

						$purchase_data[] = array(
							'po_id'          => $po_id,
							'po_no'          => $po_no,
							'vendor_id'      => $vendor_id,
							'vendor_name'    => $vendor_name,
							'contact_no'     => $contact_no,
							'order_date'     => date('d-M-Y', strtotime($order_date)),
							'order_status'   => $order_status,
							'financial_year' => $financial_year,
							'bill'           => $bill,
							'published'      => $published,
							'status'         => $status,
							'createdate'     => $createdate,
						);
					}
				}

				// Order List
				$order_list = $this->order_model->getOrder($where_2, $limit, $offset, 'result', '', '', $option);

				$order_data = [];
				if ($order_list) {
					foreach ($order_list as $key => $val_3) {

						$order_id     = !empty($val_3->id) ? $val_3->id : '';
						$order_no     = !empty($val_3->order_no) ? $val_3->order_no : '';
						$emp_name     = !empty($val_3->emp_name) ? $val_3->emp_name : 'Admin';
						$store_name   = !empty($val_3->store_name) ? $val_3->store_name : '';
						$contact_name = !empty($val_3->contact_name) ? $val_3->contact_name : '';
						$order_status = !empty($val_3->order_status) ? $val_3->order_status : '';
						$_ordered     = !empty($val_3->_ordered) ? $val_3->_ordered : '';
						$_processing  = !empty($val_3->_processing) ? $val_3->_processing : '';
						$_packing     = !empty($val_3->_packing) ? $val_3->_packing : '';
						$_shiped      = !empty($val_3->_shiped) ? $val_3->_shiped : '';
						$_invoice     = !empty($val_3->_invoice) ? $val_3->_invoice : '';
						$_delivery    = !empty($val_3->_delivery) ? $val_3->_delivery : '';
						$_complete    = !empty($val_3->_complete) ? $val_3->_complete : '';
						$_canceled    = !empty($val_3->_canceled) ? $val_3->_canceled : '';
						$random_value = !empty($val_3->random_value) ? $val_3->random_value : '';
						$published    = !empty($val_3->published) ? $val_3->published : '';
						$status       = !empty($val_3->status) ? $val_3->status : '';
						$createdate   = !empty($val_3->createdate) ? $val_3->createdate : '';

						$order_data[] = array(
							'order_id'     => $order_id,
							'order_no'     => $order_no,
							'emp_name'     => $emp_name,
							'store_name'   => $store_name,
							'contact_name' => $contact_name,
							'order_status' => $order_status,
							'_ordered'     => $_ordered,
							'_processing'  => $_processing,
							'_packing'     => $_packing,
							'_shiped'      => $_shiped,
							'_invoice'     => $_invoice,
							'_delivery'    => $_delivery,
							'_complete'    => $_complete,
							'_canceled'    => $_canceled,
							'random_value' => $random_value,
							'published'    => $published,
							'status'       => $status,
							'createdate'   => $createdate,
						);
					}
				}

				// Distributors Purchase List
				$disPurchase_list = $this->distributorpurchase_model->getDistributorPurchase($where_2, $limit, $offset, 'result', '', '', $option);

				$disPurchase_data = [];
				if ($disPurchase_list) {
					foreach ($disPurchase_list as $key => $val_4) {
						$po_id          = !empty($val_4->id) ? $val_4->id : '';
						$po_no          = !empty($val_4->po_no) ? $val_4->po_no : '';
						$distributor_id = !empty($val_4->distributor_id) ? $val_4->distributor_id : '';
						$order_date     = !empty($val_4->order_date) ? $val_4->order_date : '';
						$order_status   = !empty($val_4->order_status) ? $val_4->order_status : '';
						$_ordered       = !empty($val_4->_ordered) ? $val_4->_ordered : '';
						$financial_year = !empty($val_4->financial_year) ? $val_4->financial_year : '';
						$bill           = !empty($val_4->bill) ? $val_4->bill : '';
						$published      = !empty($val_4->published) ? $val_4->published : '';
						$status         = !empty($val_4->status) ? $val_4->status : '';
						$createdate     = !empty($val_4->createdate) ? $val_4->createdate : '';

						// Distributor Details
						$where_1 = array(
							'id' => $distributor_id,
						);

						$column_1 = 'company_name';

						$distri_data  = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column_1);

						$company_name = !empty($distri_data[0]->company_name) ? $distri_data[0]->company_name : '';

						$disPurchase_data[] = array(
							'po_id'          => $po_id,
							'po_no'          => $po_no,
							'distributor_id' => $distributor_id,
							'company_name'   => $company_name,
							'order_date'     => $order_date,
							'order_status'   => $order_status,
							'_ordered'       => $_ordered,
							'financial_year' => $financial_year,
							'bill'           => $bill,
							'published'      => $published,
							'status'         => $status,
							'createdate'	 => $createdate,
						);
					}
				}

				// Product Target Details
				$pdtTarget_group = 'type_id';
				$pdtTarget_col   = 'type_id, description';
				$pdtTarget_list  = $this->target_model->getProductTargetDetails($where_3, $limit, $offset, 'result', '', '', $option, '', $pdtTarget_col, $pdtTarget_group);

				$pdtTarget_data  = [];
				if ($pdtTarget_list) {
					foreach ($pdtTarget_list as $key => $val_5) {
						$type_id = !empty($val_5->type_id) ? $val_5->type_id : '';
						$desc    = !empty($val_5->description) ? $val_5->description : '';

						// Target Value
						$whr_one = array(
							'type_id'    => $type_id,
							'month_name' => $monthName,
							'year_name'  => $year_val,
							'published'  => '1'
						);

						$pdtTarget_one = $this->target_model->getProductTargetDetails($whr_one, '', '', "result", array(), array(), array(), TRUE, 'SUM(target_val) AS target_val');

						$pdtTarget_val = !empty($pdtTarget_one[0]->target_val) ? $pdtTarget_one[0]->target_val : '0';

						// Achieve Value
						$whr_two = array(
							'type_id'    => $type_id,
							'month_name' => $monthName,
							'year_name'  => $year_val,
							'published'  => '1'
						);

						$pdtTarget_two = $this->target_model->getProductTargetDetails($whr_two, '', '', "result", array(), array(), array(), TRUE, 'SUM(achieve_val) AS achieve_val');

						$pdtAchieve_val = !empty($pdtTarget_two[0]->achieve_val) ? $pdtTarget_two[0]->achieve_val : '0';

						$pdtTarget_data[] = array(
							'description'    => $desc,
							'pdtTarget_val'  => $pdtTarget_val,
							'pdtAchieve_val' => $pdtAchieve_val,
						);
					}
				}

				// Beat Target Details
				$beatTarget_group = 'zone_id';
				$beatTarget_col   = 'zone_id, zone_name';
				$beatTarget_list  = $this->target_model->getBeatTargetDetails($where_3, $limit, $offset, 'result', '', '', $option, '', $beatTarget_col, $beatTarget_group);

				$beatTarget_data  = [];
				if ($beatTarget_list) {
					foreach ($beatTarget_list as $key => $val_6) {
						$zone_id   = !empty($val_6->zone_id) ? $val_6->zone_id : '';
						$zone_name = !empty($val_6->zone_name) ? $val_6->zone_name : '';

						// Target Value
						$whr_one = array(
							'zone_id'    => $zone_id,
							'month_name' => $monthName,
							'year_name'  => $year_val,
							'published'  => '1'
						);

						$beatTarget_one = $this->target_model->getBeatTargetDetails($whr_one, '', '', "result", array(), array(), array(), TRUE, 'SUM(target_val) AS target_val');

						$beatTarget_val = !empty($beatTarget_one[0]->target_val) ? $beatTarget_one[0]->target_val : '0';

						// Achieve Value
						$whr_two = array(
							'zone_id'    => $zone_id,
							'month_name' => $monthName,
							'year_name'  => $year_val,
							'published'  => '1'
						);

						$beatTarget_two = $this->target_model->getBeatTargetDetails($whr_two, '', '', "result", array(), array(), array(), TRUE, 'SUM(achieve_val) AS achieve_val');

						$beatAchieve_val = !empty($beatTarget_two[0]->achieve_val) ? $beatTarget_two[0]->achieve_val : '0';

						$beatTarget_data[] = array(
							'zone_name'       => $zone_name,
							'beatTarget_val'  => $beatTarget_val,
							'beatAchieve_val' => $beatAchieve_val,
						);
					}
				}

				// Mostrly Order Product
				$mostOrder_group = 'type_id';
				$mostOrder_col   = 'type_id';
				$mostOrder_data = $this->order_model->getOrderDetails($where_4, $limit, $offset, 'result', '', '', $option, '', $mostOrder_col, $mostOrder_group);

				$mostOrder_list = [];
				if ($mostOrder_data) {
					foreach ($mostOrder_data as $key => $val_7) {
						$type_id  = !empty($val_7->type_id) ? $val_7->type_id : '0';

						$type_whr = array(
							'type_id'         => $type_id,
							'_ordered >='     => $start_value,
							'_ordered <='     => $end_value,
							'invoice_process' => '1',
							'order_status !=' => '8',
							'published'       => '1'
						);

						$pdtOrder_one = $this->order_model->getOrderDetails($type_whr, '', '', "result", array(), array(), array(), TRUE, 'SUM(order_qty) AS order_qty');

						$pdtOrder_val = !empty($pdtOrder_one[0]->order_qty) ? $pdtOrder_one[0]->order_qty : '0';

						// Description Details
						$pdt_whr = array('id' => $type_id);
						$pdt_col = 'description';
						$pdt_res = $this->commom_model->getProductType($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);
						$desc    = !empty($pdt_res[0]->description) ? $pdt_res[0]->description : '';

						$mostOrder_list[] = array(
							'description' => $desc,
							'order_value' => $pdtOrder_val,
						);
					}
				}

				// Mostly Cancel Product
				$mostInv_group = 'type_id';
				$mostInv_col   = 'type_id';
				$mostInv_data  = $this->order_model->getOrderDetails($where_5, $limit, $offset, 'result', '', '', $option, '', $mostInv_col, $mostInv_group);

				$mostInv_list = [];
				if ($mostInv_data) {
					foreach ($mostInv_data as $key => $val_7) {
						$type_id  = !empty($val_7->type_id) ? $val_7->type_id : '0';

						$type_whr = array(
							'type_id'         => $type_id,
							'_invoice >='     => $start_value,
							'_invoice <='     => $end_value,
							'invoice_process' => '1',
							'order_status !=' => '8',
							'published'       => '1'
						);

						$pdtOrder_one = $this->order_model->getOrderDetails($type_whr, '', '', "result", array(), array(), array(), TRUE, 'SUM(order_qty) AS order_qty');

						$pdtOrder_val = !empty($pdtOrder_one[0]->order_qty) ? $pdtOrder_one[0]->order_qty : '0';

						// Description Details
						$pdt_whr = array('id' => $type_id);
						$pdt_col = 'description';
						$pdt_res = $this->commom_model->getProductType($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);
						$desc    = !empty($pdt_res[0]->description) ? $pdt_res[0]->description : '';

						$mostInv_list[] = array(
							'description' => $desc,
							'order_value' => $pdtOrder_val,
						);
					}
				}

				$admin_dashboard = array(
					'state_count'        => $state_count,
					'city_count'         => $city_count,
					'zone_count'         => $zone_count,
					'unit_count'         => $unit_count,
					'category_count'     => $category_count,
					'product_count'      => $product_count,
					'vendor_count'       => $vendor_count,
					'outlet_count'       => $outlet_count,
					'distributor_count'  => $distributor_count,
					'employee_count'     => $employee_count,
					'attendance_data'    => $attendance_data,
					'purchase_data'      => $purchase_data,
					'order_data'         => $order_data,
					'disPurchase_data'   => $disPurchase_data,
					'productTarget_data' => $pdtTarget_data,
					'beatTarget_data'    => $beatTarget_data,
					'mostOrder_list'     => $mostOrder_list,
					'mostInv_list'       => $mostInv_list,
				);

				$response['status']  = 1;
				$response['message'] = "Success";
				$response['data']    = $admin_dashboard;
				echo json_encode($response);
				return;
			}
		} else {
			$response['status']  = 0;
			$response['message'] = "Error";
			$response['data']    = [];
			echo json_encode($response);
			return;
		}
	}

	// Admin Chart
	// ***************************************************
	public function admin_chart($param1 = "", $param2 = "", $param3 = "")
	{

		$permission      = $this->input->post('permission');
		$employee_id      = $this->input->post('id');
		$designation_code      = $this->input->post('designation_code');
		$method      = $this->input->post('method');
		$month_val   = date('m');
		$year_val    = date('Y');

		// Get month name
		$dateObj     = DateTime::createFromFormat('!m', $month_val);
		$monthName   = $dateObj->format('F'); // March

		// Get month count
		$month_count = cal_days_in_month(CAL_GREGORIAN, $month_val, $year_val);

		// Month Details
		$str_val = date('d-m-Y', strtotime('01-' . $month_val . '-' . $year_val));
		$end_val = date('d-m-Y', strtotime($month_count . '-' . $month_val . '-' . $year_val));

		$start_value = date('Y-m-d H:i:s', strtotime($str_val . '00:00:00'));
		$end_value   = date('Y-m-d H:i:s', strtotime($end_val . '23:59:59'));

		if ($method == '_adminTargetDashboard') {
			if ($permission == 5) {

				if ($designation_code == 'ASM') {

					$where_mg = array(

						'employee_id' => $employee_id,
					);


					$mg_val = $this->managers_model->getManagers($where_mg);
					$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id) ? $mg_val[0]->ctrl_zone_id : '0';
					$ctrl_state = !empty($mg_val[0]->ctrl_state_id) ? $mg_val[0]->ctrl_state_id : '0';
					$ctrl_city = !empty($mg_val[0]->ctrl_city_id) ? $mg_val[0]->ctrl_city_id : '0';

					if ($ctrl_state) {
						$state_id_finall = substr($ctrl_state, 1, -1);


						$d_state = !empty($state_id_finall) ? $state_id_finall : '';

						$d_state_val = explode(',', $d_state);
						$st_count = count($d_state_val);
						$count_emp = [];
						for ($i = 0; $i < $st_count; $i++) {



							$wer = array(
								'designation_code'  => 'BDE',
								'published'      => '1'
							);
							$like['ctrl_state_id'] = ',' . $d_state_val[$i] . ',';

							$co1 = 'employee_id';

							$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);

							if (!empty($mg_val)) {

								foreach ($mg_val as $key => $value) {
									array_push($count_emp, $value);
								}
							}
						}
					}
					$emp_c = count($count_emp);

					for ($i = 0; $i < $emp_c; $i++) {
						$new_emp_id[]   = !empty($count_emp[$i]->employee_id) ? $count_emp[$i]->employee_id : '';
					}
				} else if ($designation_code == 'RSM') {

					$where_mg = array(

						'employee_id' => $employee_id,
					);


					$mg_val = $this->managers_model->getManagers($where_mg);
					$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id) ? $mg_val[0]->ctrl_zone_id : '0';
					$ctrl_state = !empty($mg_val[0]->ctrl_state_id) ? $mg_val[0]->ctrl_state_id : '0';
					$ctrl_city = !empty($mg_val[0]->ctrl_city_id) ? $mg_val[0]->ctrl_city_id : '0';
					
					if ($ctrl_state) {
						$state_id_finall = substr($ctrl_state, 1, -1);


						$d_state = !empty($state_id_finall) ? $state_id_finall : '';

						$d_state_val = explode(',', $d_state);
						$st_count = count($d_state_val);
						$count_emp = [];
						for ($i = 0; $i < $st_count; $i++) {



							$wer = array(
								'designation_code'  => 'BDE',
								'published'      => '1'
							);
							$like['ctrl_state_id'] = ',' . $d_state_val[$i] . ',';

							$co1 = 'employee_id';

							$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);

							if (!empty($mg_val)) {

								foreach ($mg_val as $key => $value) {
									array_push($count_emp, $value);
								}
							}
						}
					}
					
					$emp_c = count($count_emp);
					for ($i = 0; $i < $emp_c; $i++) {
						$new_emp_id[]   = !empty($count_emp[$i]->employee_id) ? $count_emp[$i]->employee_id : '';
					}
				} else if ($designation_code == 'SO') {

					$where_mg = array(

						'employee_id' => $employee_id,
					);


					$mg_val = $this->managers_model->getManagers($where_mg);
					$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id) ? $mg_val[0]->ctrl_zone_id : '0';
					$ctrl_state = !empty($mg_val[0]->ctrl_state_id) ? $mg_val[0]->ctrl_state_id : '0';
					$ctrl_city = !empty($mg_val[0]->ctrl_city_id) ? $mg_val[0]->ctrl_city_id : '0';

					if ($ctrl_city) {
						$city_id_finall = substr($ctrl_city, 1, -1);


						$d_city = !empty($city_id_finall) ? $city_id_finall : '';

						$d_city_val = explode(',', $d_city);
						$st_count = count($d_city_val);
						$count_emp = [];
						for ($i = 0; $i < $st_count; $i++) {



							$wer = array(
								'designation_code'  => 'BDE',
								'published'      => '1'
							);
							$like['ctrl_city_id'] = ',' . $d_city_val[$i] . ',';

							$co1 = 'employee_id';

							$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);

							if (!empty($mg_val)) {

								foreach ($mg_val as $key => $value) {
									array_push($count_emp, $value);
								}
							}
						}
					}
					$emp_c = count($count_emp);

					for ($i = 0; $i < $emp_c; $i++) {
						$new_emp_id[]   = !empty($count_emp[$i]->employee_id) ? $count_emp[$i]->employee_id : '';
					}
				} else if ($designation_code == 'TSI') {

					$where_mg = array(

						'employee_id' => $employee_id,
					);


					$mg_val = $this->managers_model->getManagers($where_mg);
					$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id) ? $mg_val[0]->ctrl_zone_id : '0';
					$ctrl_state = !empty($mg_val[0]->ctrl_state_id) ? $mg_val[0]->ctrl_state_id : '0';
					$ctrl_city = !empty($mg_val[0]->ctrl_city_id) ? $mg_val[0]->ctrl_city_id : '0';
					
					if ($ctrl_zone) {
						$zone_id_finall = substr($ctrl_zone, 1, -1);


						$d_zone = !empty($zone_id_finall) ? $zone_id_finall : '';

						$d_zone_val = explode(',', $d_zone);
						$st_count = count($d_zone_val);
						
						$count_emp = [];
						for ($i = 0; $i < $st_count; $i++) {



							$wer = array(
								'designation_code'  => 'BDE',
								'published'      => '1'
							);
							$like['ctrl_zone_id'] = ',' . $d_zone_val[$i] . ',';

							$co1 = 'employee_id';

							$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);

							if (!empty($mg_val)) {

								foreach ($mg_val as $key => $value) {
									array_push($count_emp, $value);
								}
							}
						}
					}

					$emp_c = count($count_emp);

					for ($i = 0; $i < $emp_c; $i++) {
						$new_emp_id[]   = !empty($count_emp[$i]->employee_id) ? $count_emp[$i]->employee_id : '';
					}
				}

				$val1 = array();
				$val2 = array('Type', 'Value');
				array_push($val1, $val2);

				// Target Value
				$whr_one = array(
					'month_name' => $monthName,
					'year_name'  => $year_val,
					'published'  => '1'
				);
				$wher_in['employee_id'] = $new_emp_id;
				$targetVal_one = $this->target_model->getTargetDetails($whr_one, '', '', "result", array(), array(), array(), TRUE, 'SUM(target_val) AS target_val', '', $wher_in);
				
				$targetVal_val = !empty($targetVal_one[0]->target_val) ? $targetVal_one[0]->target_val : '0';
				$val3 = array('Target Value', intval($targetVal_val));
				array_push($val1, $val3);


				// Target Value
				$whr_two = array(
					'month_name' => $monthName,
					'year_name'  => $year_val,
					'published'  => '1'
				);
				$wher_in['employee_id'] = $new_emp_id;

				$targetAch_two = $this->target_model->getTargetDetails($whr_two, '', '', "result", array(), array(), array(), TRUE, 'SUM(achieve_val) AS achieve_val', '', $wher_in);

				$targetAch_val = !empty($targetAch_two[0]->achieve_val) ? $targetAch_two[0]->achieve_val : '0';
				$val4 = array('Achieve Value', intval($targetAch_val));
				array_push($val1, $val4);
				// $val1 = array(
				// 	'Target'   => $targetVal_val,
				// 	'Achieve'  => $targetAch_val,
				// );


				$response['status']  = 1;
				$response['message'] = "Success";
				$response['data']    = $val1;
				echo json_encode($response);
				return;
			} else {
				$val1 = array();
				$val2 = array('Type', 'Value');
				array_push($val1, $val2);

				// Target Value
				$whr_one = array(
					'month_name' => $monthName,
					'year_name'  => $year_val,
					'published'  => '1'
				);

				$targetVal_one = $this->target_model->getTargetDetails($whr_one, '', '', "result", array(), array(), array(), TRUE, 'SUM(target_val) AS target_val');

				$targetVal_val = !empty($targetVal_one[0]->target_val) ? $targetVal_one[0]->target_val : '0';

				$val3 = array('Target Value', intval($targetVal_val));
				array_push($val1, $val3);

				// Target Value
				$whr_two = array(
					'month_name' => $monthName,
					'year_name'  => $year_val,
					'published'  => '1'
				);

				$targetAch_two = $this->target_model->getTargetDetails($whr_two, '', '', "result", array(), array(), array(), TRUE, 'SUM(achieve_val) AS achieve_val');

				$targetAch_val = !empty($targetAch_two[0]->achieve_val) ? $targetAch_two[0]->achieve_val : '0';


				$val4 = array('Achieve Value', intval($targetAch_val));
				array_push($val1, $val4);

				$response['status']  = 1;
				$response['message'] = "Success";
				$response['data']    = $val1;
				echo json_encode($response);
				return;
			}
		} else if ($method == '_adminOrderDashboard') {
			if ($permission == 5) {

				if ($designation_code == 'ASM') {

					$where_mg = array(

						'employee_id' => $employee_id,
					);


					$mg_val = $this->managers_model->getManagers($where_mg);
					$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id) ? $mg_val[0]->ctrl_zone_id : '0';
					$ctrl_state = !empty($mg_val[0]->ctrl_state_id) ? $mg_val[0]->ctrl_state_id : '0';
					$ctrl_city = !empty($mg_val[0]->ctrl_city_id) ? $mg_val[0]->ctrl_city_id : '0';

					if ($ctrl_state) {
						$state_id_finall = substr($ctrl_state, 1, -1);


						$d_state = !empty($state_id_finall) ? $state_id_finall : '';

						$d_state_val = explode(',', $d_state);
						$st_count = count($d_state_val);
						$count_emp = [];




						$wer = array(

							'published'      => '1'
						);
						$wher_in['state_id']    = $d_state_val;
						$co1 = 'id';

						$mg_val = $this->commom_model->getZone($wer, '', '', 'result', '', '', '', '', $co1, $wher_in);
					}
					$zone_c = count($mg_val);

					for ($i = 0; $i < $zone_c; $i++) {
						$d_zone_val[]   = !empty($mg_val[$i]->id) ? $mg_val[$i]->id : '';
					}
				} else if ($designation_code == 'RSM') {

					$where_mg = array(

						'employee_id' => $employee_id,
					);


					$mg_val = $this->managers_model->getManagers($where_mg);
					$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id) ? $mg_val[0]->ctrl_zone_id : '0';
					$ctrl_state = !empty($mg_val[0]->ctrl_state_id) ? $mg_val[0]->ctrl_state_id : '0';
					$ctrl_city = !empty($mg_val[0]->ctrl_city_id) ? $mg_val[0]->ctrl_city_id : '0';

					if ($ctrl_state) {
						$state_id_finall = substr($ctrl_state, 1, -1);


						$d_state = !empty($state_id_finall) ? $state_id_finall : '';

						$d_state_val = explode(',', $d_state);
						$st_count = count($d_state_val);
						$count_emp = [];




						$wer = array(

							'published'      => '1'
						);
						$wher_in['state_id']    = $d_state_val;
						$co1 = 'id';

						$mg_val = $this->commom_model->getZone($wer, '', '', 'result', '', '', '', '', $co1, $wher_in);
					}
					$zone_c = count($mg_val);

					for ($i = 0; $i < $zone_c; $i++) {
						$d_zone_val[]   = !empty($mg_val[$i]->id) ? $mg_val[$i]->id : '';
					}
				} else if ($designation_code == 'SO') {

					$where_mg = array(

						'employee_id' => $employee_id,
					);


					$mg_val = $this->managers_model->getManagers($where_mg);
					$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id) ? $mg_val[0]->ctrl_zone_id : '0';
					$ctrl_state = !empty($mg_val[0]->ctrl_state_id) ? $mg_val[0]->ctrl_state_id : '0';
					$ctrl_city = !empty($mg_val[0]->ctrl_city_id) ? $mg_val[0]->ctrl_city_id : '0';

					if ($ctrl_city) {
						$city_id_finall = substr($ctrl_city, 1, -1);


						$d_city = !empty($city_id_finall) ? $city_id_finall : '';

						$d_city_val = explode(',', $d_city);
						$st_count = count($d_city_val);
						$count_emp = [];




						$wer = array(

							'published'      => '1'
						);
						$wher_in['city_id']    = $d_city_val;
						$co1 = 'id';

						$mg_val = $this->commom_model->getZone($wer, '', '', 'result', '', '', '', '', $co1, $wher_in);
					}
					$zone_c = count($mg_val);

					for ($i = 0; $i < $zone_c; $i++) {
						$d_zone_val[]   = !empty($mg_val[$i]->id) ? $mg_val[$i]->id : '';
					}
				} else if ($designation_code == 'TSI') {

					$where_mg = array(

						'employee_id' => $employee_id,
					);


					$mg_val = $this->managers_model->getManagers($where_mg);
					$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id) ? $mg_val[0]->ctrl_zone_id : '0';
					$ctrl_state = !empty($mg_val[0]->ctrl_state_id) ? $mg_val[0]->ctrl_state_id : '0';
					$ctrl_city = !empty($mg_val[0]->ctrl_city_id) ? $mg_val[0]->ctrl_city_id : '0';

					if ($ctrl_zone) {
						$zone_id_finall = substr($ctrl_zone, 1, -1);


						$d_zone = !empty($zone_id_finall) ? $zone_id_finall : '';

						$d_zone_val = explode(',', $d_zone);
					}
				}


				$val1 = array();
				$val2 = array('Process', 'Value');
				array_push($val1, $val2);

				// Success Order
				$whr_1 = array(
					'_ordered >=' => $start_value,
					'_ordered <=' => $end_value,
					'published'   => '1',
				);

				$wher_in['zone_id']    = $d_zone_val;
				$col_1 = 'price, order_qty';

				$success_data = $this->order_model->getOrderDetails($whr_1, '', '', 'result', '', '', '', '', $col_1, '', $wher_in);

				if ($success_data) {
					$order_tot = 0;
					foreach ($success_data as $key => $val_1) {
						$price      = !empty($val_1->price) ? $val_1->price : '0';
						$order_qty  = !empty($val_1->order_qty) ? $val_1->order_qty : '0';
						$order_val  = $price * $order_qty;
						$order_tot += round($order_val);
					}

					$success_val = round($order_tot);


					$val3 = array('Overall', intval($success_val));
					array_push($val1, $val3);
				}

				// Process Order
				$whr_2 = array(
					'_processing >=' => $start_value,
					'_processing <=' => $end_value,
					'published'      => '1',
				);
				$wher_in2['zone_id']    = $d_zone_val;
				$col_2 = 'price, order_qty';

				$process_data = $this->order_model->getOrderDetails($whr_2, '', '', 'result', '', '', '', '', $col_2, '', $wher_in2);

				if ($process_data) {
					$order_tot = 0;
					foreach ($process_data as $key => $val_2) {
						$price      = !empty($val_2->price) ? round($val_2->price) : '0';
						$order_qty  = !empty($val_2->order_qty) ? $val_2->order_qty : '0';
						$order_val  = $price * $order_qty;
						$order_tot += $order_val;
					}

					$process_val = round($order_tot);

					$val4 = array('Process', intval($process_val));
					array_push($val1, $val4);
				}

				// Cancel Order
				$whr_3 = array(
					'_canceled >=' => $start_value,
					'_canceled <=' => $end_value,
					'published'    => '1',
				);
				$wher_in1['zone_id']    = $d_zone_val;
				$col_3 = 'price, order_qty';

				$cancel_data = $this->order_model->getOrderDetails($whr_3, '', '', 'result', '', '', '', '', $col_3, '', $wher_in1);

				if ($cancel_data) {
					$order_tot = 0;
					foreach ($cancel_data as $key => $val_3) {
						$price      = !empty($val_3->price) ? round($val_3->price) : '0';
						$order_qty  = !empty($val_3->order_qty) ? $val_3->order_qty : '0';
						$order_val  = $price * $order_qty;
						$order_tot += $order_val;
					}

					$cancel_val = round($order_tot);

					$val5 = array('Cancel', intval($cancel_val));
					array_push($val1, $val5);
				}

				// Invoice Order
				$whr_4 = array(
					'_invoice >='     => $start_value,
					'_invoice <='     => $end_value,
					'invoice_process' => '1',
					'cancel_status'   => '1',
					'published'       => '1',
				);
				$wher_in4['zone_id']    = $d_zone_val;
				$col_4 = 'price, order_qty';

				$invoice_data = $this->order_model->getOrderDetails($whr_4, '', '', 'result', '', '', '', '', $col_4, '', $wher_in4);

				// echo $this->db->last_query();

				if ($invoice_data) {
					$order_tot = 0;
					foreach ($invoice_data as $key => $val_4) {
						$price      = !empty($val_4->price) ? round($val_4->price) : '0';
						$order_qty  = !empty($val_4->order_qty) ? $val_4->order_qty : '0';
						$order_val  = $price * $order_qty;
						$order_tot += $order_val;
					}

					$invoice_val = round($order_tot);

					$val6 = array('Invoice', intval($invoice_val));
					array_push($val1, $val6);
				}

				// Delivery Order
				$whr_5 = array(
					'_delivery >='    => $start_value,
					'_delivery <='    => $end_value,
					'invoice_process' => '2',
					'published'       => '1',
				);
				$wher_in5['zone_id']    = $d_zone_val;
				$col_5 = 'price, order_qty';

				$delivery_data = $this->order_model->getOrderDetails($whr_5, '', '', 'result', '', '', '', '', $col_5, '', $wher_in5);

				if ($delivery_data) {
					$order_tot = 0;
					foreach ($delivery_data as $key => $val_5) {
						$price      = !empty($val_5->price) ? round($val_5->price) : '0';
						$order_qty  = !empty($val_5->order_qty) ? $val_5->order_qty : '0';
						$order_val  = $price * $order_qty;
						$order_tot += $order_val;
					}

					$delivery_val = round($order_tot);

					$val7 = array('Delivery', intval($delivery_val));
					array_push($val1, $val7);
				}

				// Invoice Cancel Order
				$whr_6 = array(
					'_delete >='      => $start_value,
					'_delete <='      => $end_value,
					'cancel_status'   => '2',
					'published'       => '1',
				);
				$wher_in6['zone_id']    = $d_zone_val;
				$col_6 = 'price, order_qty';

				$invCancel_data = $this->order_model->getOrderDetails($whr_6, '', '', 'result', '', '', '', '', $col_6, '', $wher_in6);

				if ($invCancel_data) {
					$order_tot = 0;
					foreach ($invCancel_data as $key => $val_6) {
						$price      = !empty($val_6->price) ? round($val_6->price) : '0';
						$order_qty  = !empty($val_6->order_qty) ? $val_6->order_qty : '0';
						$order_val  = $price * $order_qty;
						$order_tot += $order_val;
					}

					$invCancel_val = round($order_tot);

					$val8 = array('Cancel Invoice', intval($invCancel_val));
					array_push($val1, $val8);
				}

				$response['status']  = 1;
				$response['message'] = "Success";
				$response['data']    = $val1;
				echo json_encode($response);
				return;
			} else {

				$val1 = array();
				$val2 = array('Process', 'Value');
				array_push($val1, $val2);

				// Success Order
				$whr_1 = array(
					'_ordered >=' => $start_value,
					'_ordered <=' => $end_value,
					'published'   => '1',
				);

				$col_1 = 'price, order_qty';

				$success_data = $this->order_model->getOrderDetails($whr_1, '', '', 'result', '', '', '', '', $col_1);

				if ($success_data) {
					$order_tot = 0;
					foreach ($success_data as $key => $val_1) {
						$price      = !empty($val_1->price) ? $val_1->price : '0';
						$order_qty  = !empty($val_1->order_qty) ? $val_1->order_qty : '0';
						$order_val  = $price * $order_qty;
						$order_tot += round($order_val);
					}

					$success_val = round($order_tot);

					$val3 = array('Overall', intval($success_val));
					array_push($val1, $val3);
				}

				// Process Order
				$whr_2 = array(
					'_processing >=' => $start_value,
					'_processing <=' => $end_value,
					'published'      => '1',
				);

				$col_2 = 'price, order_qty';

				$process_data = $this->order_model->getOrderDetails($whr_2, '', '', 'result', '', '', '', '', $col_2);

				if ($process_data) {
					$order_tot = 0;
					foreach ($process_data as $key => $val_2) {
						$price      = !empty($val_2->price) ? round($val_2->price) : '0';
						$order_qty  = !empty($val_2->order_qty) ? $val_2->order_qty : '0';
						$order_val  = $price * $order_qty;
						$order_tot += $order_val;
					}

					$process_val = round($order_tot);

					$val4 = array('Process', intval($process_val));
					array_push($val1, $val4);
				}

				// Cancel Order
				$whr_3 = array(
					'_canceled >=' => $start_value,
					'_canceled <=' => $end_value,
					'published'    => '1',
				);

				$col_3 = 'price, order_qty';

				$cancel_data = $this->order_model->getOrderDetails($whr_3, '', '', 'result', '', '', '', '', $col_3);

				if ($cancel_data) {
					$order_tot = 0;
					foreach ($cancel_data as $key => $val_3) {
						$price      = !empty($val_3->price) ? round($val_3->price) : '0';
						$order_qty  = !empty($val_3->order_qty) ? $val_3->order_qty : '0';
						$order_val  = $price * $order_qty;
						$order_tot += $order_val;
					}

					$cancel_val = round($order_tot);

					$val5 = array('Cancel', intval($cancel_val));
					array_push($val1, $val5);
				}

				// Invoice Order
				$whr_4 = array(
					'_invoice >='     => $start_value,
					'_invoice <='     => $end_value,
					'invoice_process' => '1',
					'cancel_status'   => '1',
					'published'       => '1',
				);

				$col_4 = 'price, order_qty';

				$invoice_data = $this->order_model->getOrderDetails($whr_4, '', '', 'result', '', '', '', '', $col_4);

				// echo $this->db->last_query();

				if ($invoice_data) {
					$order_tot = 0;
					foreach ($invoice_data as $key => $val_4) {
						$price      = !empty($val_4->price) ? round($val_4->price) : '0';
						$order_qty  = !empty($val_4->order_qty) ? $val_4->order_qty : '0';
						$order_val  = $price * $order_qty;
						$order_tot += $order_val;
					}

					$invoice_val = round($order_tot);

					$val6 = array('Invoice', intval($invoice_val));
					array_push($val1, $val6);
				}

				// Delivery Order
				$whr_5 = array(
					'_delivery >='    => $start_value,
					'_delivery <='    => $end_value,
					'invoice_process' => '2',
					'published'       => '1',
				);

				$col_5 = 'price, order_qty';

				$delivery_data = $this->order_model->getOrderDetails($whr_5, '', '', 'result', '', '', '', '', $col_5);

				if ($delivery_data) {
					$order_tot = 0;
					foreach ($delivery_data as $key => $val_5) {
						$price      = !empty($val_5->price) ? round($val_5->price) : '0';
						$order_qty  = !empty($val_5->order_qty) ? $val_5->order_qty : '0';
						$order_val  = $price * $order_qty;
						$order_tot += $order_val;
					}

					$delivery_val = round($order_tot);

					$val7 = array('Delivery', intval($delivery_val));
					array_push($val1, $val7);
				}

				// Invoice Cancel Order
				$whr_6 = array(
					'_delete >='      => $start_value,
					'_delete <='      => $end_value,
					'cancel_status'   => '2',
					'published'       => '1',
				);

				$col_6 = 'price, order_qty';

				$invCancel_data = $this->order_model->getOrderDetails($whr_6, '', '', 'result', '', '', '', '', $col_6);

				if ($invCancel_data) {
					$order_tot = 0;
					foreach ($invCancel_data as $key => $val_6) {
						$price      = !empty($val_6->price) ? round($val_6->price) : '0';
						$order_qty  = !empty($val_6->order_qty) ? $val_6->order_qty : '0';
						$order_val  = $price * $order_qty;
						$order_tot += $order_val;
					}

					$invCancel_val = round($order_tot);

					$val8 = array('Cancel Invoice', intval($invCancel_val));
					array_push($val1, $val8);
				}

				$response['status']  = 1;
				$response['message'] = "Success";
				$response['data']    = $val1;
				echo json_encode($response);
				return;
			}
		} else {
			$response['status']  = 0;
			$response['message'] = "Error";
			$response['data']    = [];
			echo json_encode($response);
			return;
		}
	}

	// Vendor Dashboard
	// ***************************************************
	public function vendor_dashboard($param1 = "", $param2 = "", $param3 = "")
	{
		$method      = $this->input->post('method');
		$financ_year = $this->input->post('financial_year');
		$vendor_id   = $this->input->post('vendor_id');

		if ($method == '_vendorDashboard') {
			$error = FALSE;
			$errors = array();
			$required = array('financial_year', 'vendor_id');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}

			if (count($errors) == 0) {
				// Master Value
				// **********************************************
				$where_1 = array(
					'vendor_id' => $vendor_id,
					'published' => '1',
					'status'    => '1',
				);

				$where_2 = array(
					'order_status !=' => '1',
					'vendor_id'       => $vendor_id,
					'financial_year'  => $financ_year,
					'published'       => '1'
				);

				$limit  = 10;
				$offset = 0;

				$option['order_by']   = 'id';
				$option['disp_order'] = 'DESC';

				// **********************************************

				// Product Count
				$product_val = $this->commom_model->getProduct($where_1, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

				$product_count = !empty($product_val[0]->autoid) ? $product_val[0]->autoid : '0';

				// Purchase Count
				$purchase_val = $this->purchase_model->getPurchase($where_1, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

				$purchase_count = !empty($purchase_val[0]->autoid) ? $purchase_val[0]->autoid : '0';

				// Production Count
				$production_val = $this->production_model->getAdminProduction($where_1, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

				$production_count = !empty($production_val[0]->autoid) ? $production_val[0]->autoid : '0';

				// Purchase List
				$purchase_list = $this->purchase_model->getPurchase($where_2, $limit, $offset, 'result', '', '', $option);

				$purchase_data = [];
				if ($purchase_list) {
					foreach ($purchase_list as $key => $val_2) {

						$po_id          = isset($val_2->id) ? $val_2->id : '';
						$po_no          = isset($val_2->po_no) ? $val_2->po_no : '';
						$vendor_id      = isset($val_2->vendor_id) ? $val_2->vendor_id : '';
						$order_date     = isset($val_2->order_date) ? $val_2->order_date : '';
						$order_status   = isset($val_2->order_status) ? $val_2->order_status : '';
						$financial_year = isset($val_2->financial_year) ? $val_2->financial_year : '';
						$bill           = isset($val_2->bill) ? $val_2->bill : '';
						$published      = isset($val_2->published) ? $val_2->published : '';
						$status         = isset($val_2->status) ? $val_2->status : '';
						$createdate     = isset($val_2->createdate) ? $val_2->createdate : '';

						// Vendor Name
						$where_1     = array('id' => $vendor_id);
						$data_val    = $this->vendors_model->getVendors($where_1);
						$vendor_name = isset($data_val[0]->company_name) ? $data_val[0]->company_name : '';
						$contact_no  = isset($data_val[0]->contact_no) ? $data_val[0]->contact_no : '';

						$purchase_data[] = array(
							'po_id'          => $po_id,
							'po_no'          => $po_no,
							'vendor_id'      => $vendor_id,
							'vendor_name'    => $vendor_name,
							'contact_no'     => $contact_no,
							'order_date'     => date('d-M-Y', strtotime($order_date)),
							'order_status'   => $order_status,
							'financial_year' => $financial_year,
							'bill'           => $bill,
							'published'      => $published,
							'status'         => $status,
							'createdate'     => $createdate,
						);
					}
				}

				$admin_dashboard = array(
					'product_count'    => $product_count,
					'purchase_count'   => $purchase_count,
					'production_count' => $production_count,
					'purchase_data'    => $purchase_data,
				);

				$response['status']  = 1;
				$response['message'] = "Success";
				$response['data']    = $admin_dashboard;
				echo json_encode($response);
				return;
			}
		} else {
			$response['status']  = 0;
			$response['message'] = "Error";
			$response['data']    = [];
			echo json_encode($response);
			return;
		}
	}

	// Distributor Dashboard
	// ***************************************************
	public function distributor_dashboard($param1 = "", $param2 = "", $param3 = "")
	{
		$method         = $this->input->post('method');
		$financ_year    = $this->input->post('financial_year');
		$distributor_id = $this->input->post('distributor_id');

		if ($method == '_distributorDashboard') {
			$error = FALSE;
			$errors = array();
			$required = array('financial_year', 'distributor_id');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}

			if (count($errors) == 0) {
				// Master Value
				// **********************************************
				$where_1 = array(
					'distributor_id' => $distributor_id,
					'published'      => '1',
					'status'         => '1',
				);

				$where_2 = array(
					'company_id' => $distributor_id,
					'published'  => '1',
					'status'     => '1',
				);

				$where_3 = array(
					'distributor_id' => $distributor_id,
					'value_type'     => '2',
					'published'      => '1',
				);

				$col_1 = 'id, po_no, order_date, invoice_no, order_status';
				$col_2 = 'outlet_id, bill_code, bill_no, amount, discount, amt_type, date';
				$col_3 = 'distributor_id, bill_code, bill_no, amount, discount, amt_type, date';

				$limit  = 10;
				$offset = 0;

				$option['order_by']   = 'id';
				$option['disp_order'] = 'DESC';


				// Product Count
				$product_val = $this->assignproduct_model->getAssignProductDetails($where_1, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

				$product_count = !empty($product_val[0]->autoid) ? $product_val[0]->autoid : '0';

				// Employee Count
				$employee_val = $this->employee_model->getEmployee($where_2, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

				$employee_count = !empty($employee_val[0]->autoid) ? $employee_val[0]->autoid : '0';

				// Employee Count
				$outlet_val = $this->outlets_model->getDistributorOutlets($where_1, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

				$outlet_count = !empty($outlet_val[0]->autoid) ? $outlet_val[0]->autoid : '0';

				// Recent Purchase Details
				$purchase_list = $this->distributorpurchase_model->getDistributorPurchase($where_1, $limit, $offset, 'result', '', '', '', '', $col_1);

				$purchase_data = [];
				if ($purchase_list) {
					foreach ($purchase_list as $key => $val_1) {
						$po_id        = !empty($val_1->id) ? $val_1->id : '';
						$po_no        = !empty($val_1->po_no) ? $val_1->po_no : '';
						$order_date   = !empty($val_1->order_date) ? $val_1->order_date : '';
						$invoice_no   = !empty($val_1->invoice_no) ? $val_1->invoice_no : '';
						$order_status = !empty($val_1->order_status) ? $val_1->order_status : '';

						$purchase_data[] = array(
							'po_id'        => $po_id,
							'po_no'        => $po_no,
							'order_date'   => $order_date,
							'invoice_no'   => $invoice_no,
							'order_status' => $order_status,
						);
					}
				}

				// Recent Outlet Payment Details
				$outletPayment_list = $this->payment_model->getOutletPayment($where_3, $limit, $offset, 'result', '', '', '', '', $col_2);

				$outletPayment_data = [];
				if ($outletPayment_list) {
					foreach ($outletPayment_list as $key => $val_2) {

						$outlet_id = !empty($val_2->outlet_id) ? $val_2->outlet_id : '';
						$bill_code = !empty($val_2->bill_code) ? $val_2->bill_code : '';
						$bill_no   = !empty($val_2->bill_no) ? $val_2->bill_no : '';
						$amount    = !empty($val_2->amount) ? $val_2->amount : '';
						$discount  = !empty($val_2->discount) ? $val_2->discount : '';
						$amt_type  = !empty($val_2->amt_type) ? $val_2->amt_type : '';
						$date      = !empty($val_2->date) ? $val_2->date : '';

						// Outlet Details
						$whr_4 = array(
							'id' => $outlet_id,
						);

						$col_4 = 'company_name';

						$outlet_val = $this->outlets_model->getOutlets($whr_4, '', '', 'result', '', '', '', '', $col_4);

						$str_name   = !empty($outlet_val[0]->company_name) ? $outlet_val[0]->company_name : '';

						$outletPayment_data[] = array(
							'str_name'  => $str_name,
							'bill_code' => $bill_code,
							'bill_no'   => $bill_no,
							'amount'    => $amount,
							'discount'  => $discount,
							'amt_type'  => $amt_type,
							'date'      => $date,
						);
					}
				}

				// Recent Distributor Payment Details
				$recentPayment_list = $this->payment_model->getDistributorPayment($where_3, $limit, $offset, 'result', '', '', '', '', $col_3);

				$recentPayment_data = [];
				if ($recentPayment_list) {
					foreach ($recentPayment_list as $key => $val_2) {

						$bill_code = !empty($val_2->bill_code) ? $val_2->bill_code : '';
						$bill_no   = !empty($val_2->bill_no) ? $val_2->bill_no : '';
						$amount    = !empty($val_2->amount) ? $val_2->amount : '';
						$discount  = !empty($val_2->discount) ? $val_2->discount : '';
						$amt_type  = !empty($val_2->amt_type) ? $val_2->amt_type : '';
						$date      = !empty($val_2->date) ? $val_2->date : '';

						$recentPayment_data[] = array(
							'bill_code' => $bill_code,
							'bill_no'   => $bill_no,
							'amount'    => $amount,
							'discount'  => $discount,
							'amt_type'  => $amt_type,
							'date'      => $date,
						);
					}
				}

				// Recent Outlet Orders
				$column_1 = 'type_id';
				$where_1  = array('id'  => $distributor_id);
				$data_1   = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column_1);

				$result_1 = $data_1[0];
				$type_id  = !empty($result_1->type_id) ? $result_1->type_id : '';

				$where_2 = array(
					'tbl_order_details.order_status'    => '2,3,4,5,6,9',
					'tbl_order.published'               => '1',
					'tbl_order_details.type_id'         => $type_id,
					'tbl_order_details.vendor_type != ' => '1'
				);

				$column_2  = 'tbl_order_details.order_id, tbl_order_details.zone_id, tbl_order_details.type_id';

				$data_2  = $this->order_model->getDistributorOrder($where_2, '', '', 'result', '', '', '', '', $column_2);

				$outletOrder_data = [];


				$distributor_dashboard = array(
					'product_count'      => $product_count,
					'employee_count'     => $employee_count,
					'outlet_count'       => $outlet_count,
					'purchase_data'      => $purchase_data,
					'outletPayment_data' => $outletPayment_data,
					'recentPayment_data' => $recentPayment_data,
					'outletOrder_data'   => $outletOrder_data,
				);

				$response['status']  = 1;
				$response['message'] = "Success";
				$response['data']    = $distributor_dashboard;
				echo json_encode($response);
				return;
			}
		} else {
			$response['status']  = 0;
			$response['message'] = "Error";
			$response['data']    = [];
			echo json_encode($response);
			return;
		}
	}

	// Order Count
	// ***************************************************
	public function order_count($param1 = "", $param2 = "", $param3 = "")
	{
		$method         = $this->input->post('method');
		$distributor_id = $this->input->post('distributor_id');
		$vendor_id      = $this->input->post('vendor_id');

		if ($method == '_adminOrderCount') {
			// Outlet Order Count
			$orderCount_whr = array(
				'order_status' => '1',
				'published'    => '1',
				'status'       => '1',
			);

			$order_count = $this->order_model->getOrder($orderCount_whr, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

			$orderValue = !empty($order_count[0]->autoid) ? $order_count[0]->autoid : '0';

			// Distributor Order Count
			$disOrdCount_whr = array(
				'order_status' => '1',
				'published'    => '1',
				'status'       => '1',
			);

			$disOrd_count = $this->distributorpurchase_model->getDistributorPurchase($disOrdCount_whr, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

			$disOrdValue  = !empty($disOrd_count[0]->autoid) ? $disOrd_count[0]->autoid : '0';

			$order_res = array(
				'outlet_order'      => $orderValue,
				'distributor_order' => $disOrdValue,
			);

			$response['status']  = 1;
			$response['message'] = "Success";
			$response['data']    = $order_res;
			echo json_encode($response);
			return;
		} else {
			$response['status']  = 0;
			$response['message'] = "Error";
			$response['data']    = [];
			echo json_encode($response);
			return;
		}
	}

	// manager Dashboard
	// ***************************************************
	public function manager_dashboard($param1 = "", $param2 = "", $param3 = "")
	{
		$method         = $this->input->post('method');
		$financ_year    = $this->input->post('financial_year');
		$manager_id = $this->input->post('manager_id');
		$month_val   = date('m');
		$year_val    = date('Y');
		$month_count = cal_days_in_month(CAL_GREGORIAN, $month_val, $year_val);

		$dateObj   = DateTime::createFromFormat('!m', $month_val);
		$monthName = $dateObj->format('F'); // March

		

		$start_date  = '01-' . $month_val . '-' . $year_val;
		$end_date    = $month_count . '-' . $month_val . '-' . $year_val;
		$start_value = date('Y-m-d H:i:s', strtotime($start_date . '00:00:00'));
		$end_value   = date('Y-m-d H:i:s', strtotime($end_date . '23:59:59'));

		if ($method == '_managerDashboard') {
			$error = FALSE;
			$errors = array();
			$required = array('manager_id');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}

			if (count($errors) == 0) {
				$where_mg = array(

					'employee_id' => $manager_id,
				);


				$mg_val = $this->managers_model->getManagers($where_mg);
				$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id) ? $mg_val[0]->ctrl_zone_id : '0';
				$ctrl_state = !empty($mg_val[0]->ctrl_state_id) ? $mg_val[0]->ctrl_state_id : '0';
				$ctrl_city = !empty($mg_val[0]->ctrl_city_id) ? $mg_val[0]->ctrl_city_id : '0';
				$designation_code = !empty($mg_val[0]->designation_code)?$mg_val[0]->designation_code:'0';

				if ($designation_code == 'RSM') {
					if (!empty($ctrl_state)) {
						$state_id_finall = substr($ctrl_state, 1, -1);


						$d_state = !empty($state_id_finall) ? $state_id_finall : '';

						$d_state_val = explode(',', $d_state);
						$st_count = count($d_state_val);


						$exsiting_ct = [];
						$exsiting_zn = [];
						$order_data = [];
						$count_emp = [];
						$mg_prasent = array();
						$total_out_let = [];
						for ($i = 0; $i < $st_count; $i++) {



							$wer = array(
								'published'      => '1'
							);
							
							$myArray = array("'ASM','SO','TSI','BDE'");
							$where_in['designation_code'] = $myArray;
							$like['ctrl_state_id'] = ',' . $d_state_val[$i] . ',';

							$co1 = 'employee_id';

							$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);

							if (!empty($mg_val)) {

								foreach ($mg_val as $key => $value) {
									array_push($count_emp, $value);
								}
							}
							$were_out = array(
								'state_id' =>  $d_state_val[$i],
								'published' => '1'
							);
							$col_out = 'id';
							$out_let = $this->outlets_model->getOutlets($were_out, '', '', 'result', '', '', '', '', $col_out);

							if (!empty($out_let)) {
								foreach ($out_let as $value) {
									array_push($total_out_let, $value);
								}
							}
							$wer1 = array(
								'state_id'        =>  $d_state_val[$i],
								'published' => '1'
							);


							$col = 'id';
							$control_ct    = $this->commom_model->getCity($wer1, '', '', "result", '', '', '', '', $col);

							if (!empty($control_ct)) {
								foreach ($control_ct as $value) {
									array_push($exsiting_ct, $value);
								}
								$wer11 = array(
									'state_id'        =>  $d_state_val[$i],
									'published' => '1'
								);


								$col = 'id';
								$control_zn    = $this->commom_model->getZone($wer11, '', '', "result", '', '', '', '', $col);
								if(!empty($control_zn)){
									foreach ($control_zn as $value) {
										array_push($exsiting_zn, $value);
									}
								}
							
							}


							$wer_att1 = array(

								'A.c_date' => date('Y-m-d'),
								'A.published'      => '1',
								'B.published'      => '1'
							);
							$like['B.ctrl_state_id'] = ',' . $d_state_val[$i] . ',';
							$limit  = 10;
							$offset = 0;

							$option['order_by']   = 'A.id';
							$option['disp_order'] = 'DESC';

							$col_att = 'A.id, A.emp_name, A.emp_type, A.store_name, A.attendance_type, A.reason, A.c_date, A.in_time, A.out_time, A.log_status';

							$attendance_list = $this->attendance_model->getAttendanceJoinMg($wer_att1, $limit, $offset, 'result', $like, '', $option, '', $col_att);

							$attendance_data = [];
							if ($attendance_list) {
								foreach ($attendance_list as $key => $val_1) {
									$att_id     = !empty($val_1->id) ? $val_1->id : '';
									$emp_name   = !empty($val_1->emp_name) ? $val_1->emp_name : '';
									$emp_type   = !empty($val_1->emp_type) ? $val_1->emp_type : '';
									$store_name = !empty($val_1->store_name) ? $val_1->store_name : '';
									$att_type   = !empty($val_1->attendance_type) ? $val_1->attendance_type : '';
									$reason     = !empty($val_1->reason) ? $val_1->reason : '';
									$c_date     = !empty($val_1->c_date) ? $val_1->c_date : '';
									$in_time    = !empty($val_1->in_time) ? $val_1->in_time : '';
									$out_time   = !empty($val_1->out_time) ? $val_1->out_time : '';
									$log_status = !empty($val_1->log_status) ? $val_1->log_status : '0';

									$attendance_data[] = array(
										'attendance_id'   => $att_id,
										'emp_name'        => $emp_name,
										'emp_type'        => $emp_type,
										'store_name'      => $store_name,
										'attendance_type' => $att_type,
										'reason'          => $reason,
										'c_date'          => date('d-M-Y', strtotime($c_date)),
										'in_time'         => $in_time,
										'out_time'        => $out_time,
										'log_status'      => $log_status,
									);
								}
							}
						}
						$where_2 = array(
							// 'financial_year' => $financ_year,
							'A.published'      => '1',

						);
						$whr_in['B.state_id'] = $d_state_val;

						$limit  = 10;
						$offset = 0;

						$option['order_by']   = 'A.id';
						$option['disp_order'] = 'DESC';
						$colm = 'A.id,A.order_no,A.emp_name,A.store_name,A.contact_name,A.order_status,A._ordered,A._processing,A._packing,A._shiped,A._invoice,A._delivery,A._complete,A._canceled,A.random_value,A.published,A.createdate';
						$order_list = $this->order_model->getOrderPostingJoin($where_2, $limit, $offset, 'result', '', '', $option, '', $colm, '', $whr_in);


						if ($order_list) {
							foreach ($order_list as $key => $val_3) {

								$order_id     = !empty($val_3->id) ? $val_3->id : '';
								$order_no     = !empty($val_3->order_no) ? $val_3->order_no : '';
								$emp_name     = !empty($val_3->emp_name) ? $val_3->emp_name : 'Admin';
								$store_name   = !empty($val_3->store_name) ? $val_3->store_name : '';
								$contact_name = !empty($val_3->contact_name) ? $val_3->contact_name : '';
								$order_status = !empty($val_3->order_status) ? $val_3->order_status : '';
								$_ordered     = !empty($val_3->_ordered) ? $val_3->_ordered : '';
								$_processing  = !empty($val_3->_processing) ? $val_3->_processing : '';
								$_packing     = !empty($val_3->_packing) ? $val_3->_packing : '';
								$_shiped      = !empty($val_3->_shiped) ? $val_3->_shiped : '';
								$_invoice     = !empty($val_3->_invoice) ? $val_3->_invoice : '';
								$_delivery    = !empty($val_3->_delivery) ? $val_3->_delivery : '';
								$_complete    = !empty($val_3->_complete) ? $val_3->_complete : '';
								$_canceled    = !empty($val_3->_canceled) ? $val_3->_canceled : '';
								$random_value = !empty($val_3->random_value) ? $val_3->random_value : '';
								$published    = !empty($val_3->published) ? $val_3->published : '';
								$status       = !empty($val_3->status) ? $val_3->status : '';
								$createdate   = !empty($val_3->createdate) ? $val_3->createdate : '';

								$order_data[] = array(
									'order_id'     => $order_id,
									'order_no'     => $order_no,
									'emp_name'     => $emp_name,
									'store_name'   => $store_name,
									'contact_name' => $contact_name,
									'order_status' => $order_status,
									'_ordered'     => $_ordered,
									'_processing'  => $_processing,
									'_packing'     => $_packing,
									'_shiped'      => $_shiped,
									'_invoice'     => $_invoice,
									'_delivery'    => $_delivery,
									'_complete'    => $_complete,
									'_canceled'    => $_canceled,
									'random_value' => $random_value,
									'published'    => $published,
									'status'       => $status,
									'createdate'   => $createdate,
								);
							}
						}
						$emp_c = count($count_emp);

						for ($i = 0; $i < $emp_c; $i++) {
							$new_emp_id[]   = !empty($count_emp[$i]->employee_id) ? $count_emp[$i]->employee_id : '';
						}

						$wer_att1 = array(

							'c_date' => date('Y-m-d'),
							'published'      => '1',

						);

						$limit  = 10;
						$offset = 0;
						$whr_in['emp_id'] = $new_emp_id;
						$option['order_by']   = 'id';
						$option['disp_order'] = 'DESC';

						$col_att = 'id, emp_name, emp_type, store_name, attendance_type, reason, c_date, in_time, out_time, log_status';

						$attendance_list = $this->attendance_model->getAttendance($wer_att1, $limit, $offset, 'result', '', '', $option, '', $col_att, '', $whr_in);

						$attendance_data = [];
						if ($attendance_list) {
							foreach ($attendance_list as $key => $val_1) {
								$att_id     = !empty($val_1->id) ? $val_1->id : '';
								$emp_name   = !empty($val_1->emp_name) ? $val_1->emp_name : '';
								$emp_type   = !empty($val_1->emp_type) ? $val_1->emp_type : '';
								$store_name = !empty($val_1->store_name) ? $val_1->store_name : '';
								$att_type   = !empty($val_1->attendance_type) ? $val_1->attendance_type : '';
								$reason     = !empty($val_1->reason) ? $val_1->reason : '';
								$c_date     = !empty($val_1->c_date) ? $val_1->c_date : '';
								$in_time    = !empty($val_1->in_time) ? $val_1->in_time : '';
								$out_time   = !empty($val_1->out_time) ? $val_1->out_time : '';
								$log_status = !empty($val_1->log_status) ? $val_1->log_status : '0';

								$attendance_data[] = array(
									'attendance_id'   => $att_id,
									'emp_name'        => $emp_name,
									'emp_type'        => $emp_type,
									'store_name'      => $store_name,
									'attendance_type' => $att_type,
									'reason'          => $reason,
									'c_date'          => date('d-M-Y', strtotime($c_date)),
									'in_time'         => $in_time,
									'out_time'        => $out_time,
									'log_status'      => $log_status,
								);
							}
						}

						$count_zn = count($exsiting_zn);
						$count_ct = count($exsiting_ct);
						$out_let_c = count($total_out_let);
					}
				} else if ($designation_code == 'ASM') {
					if (!empty($ctrl_state)) {
						$state_id_finall = substr($ctrl_state, 1, -1);
						//print_r($state_id_finall);exit;

						$d_state = !empty($state_id_finall) ? $state_id_finall : '';

						$d_state_val = explode(',', $d_state);
						$st_count = count($d_state_val);

						//print_r($st_count);exit;
						$exsiting_ct = [];
						$exsiting_zn = [];
						$order_data = [];
						$count_emp = [];
						$mg_prasent = array();
						$total_out_let = [];
						for ($i = 0; $i < $st_count; $i++) {


							$wer = array(
								'published'      => '1'
							);
							$myArray = array("'SO','TSI','BDE'");
							$where_in['designation_code'] = $myArray;
							$like['ctrl_state_id'] = ',' . $d_state_val[$i] . ',';

							$co1 = 'employee_id';

							$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);
							$count_emp = [];
							$mg_prasent = array();
							if (!empty($mg_val)) {

								foreach ($mg_val as $key => $value) {
									array_push($count_emp, $value);
								}
							}

							$were_out = array(
								'state_id' =>  $d_state_val[$i],
								'published' => '1'
							);
							$col_out = 'id';
							$out_let = $this->outlets_model->getOutlets($were_out, '', '', 'result', $like, '', '', '', $col_out);
							foreach ($out_let as $key => $value) {
								array_push($total_out_let, $value);
							}


							$wer1 = array(
								'state_id'        =>  $d_state_val[$i],
								'published' => '1'
							);


							$col = 'id';
							$control_ct    = $this->commom_model->getCity($wer1, '', '', "result", '', '', '', '', $col);

							if (!empty($control_ct)) {
								foreach ($control_ct as $value) {
									array_push($exsiting_ct, $value);
								}
								$wer11 = array(
									'state_id'        =>  $d_state_val[$i],
									'published' => '1'
								);


								$col = 'id';
								$control_zn    = $this->commom_model->getZone($wer11, '', '', "result", '', '', '', '', $col);

								foreach ($control_zn as $value) {
									array_push($exsiting_zn, $value);
								}
							}
						}
						$emp_c = count($count_emp);

						for ($i = 0; $i < $emp_c; $i++) {
							$new_emp_id[]   = !empty($count_emp[$i]->employee_id) ? $count_emp[$i]->employee_id : '';
						}
						// print_r($new_emp_id);exit;
						$wer_att1 = array(

							'c_date' => date('Y-m-d'),
							'published'      => '1',

						);

						$limit  = 10;
						$offset = 0;
						$whr_in['emp_id'] = $new_emp_id;
						$option['order_by']   = 'id';
						$option['disp_order'] = 'DESC';

						$col_att = 'id, emp_name, emp_type, store_name, attendance_type, reason, c_date, in_time, out_time, log_status';

						$attendance_list = $this->attendance_model->getAttendance($wer_att1, $limit, $offset, 'result', '', '', $option, '', $col_att, '', $whr_in);

						$attendance_data = [];
						if ($attendance_list) {
							foreach ($attendance_list as $key => $val_1) {
								$att_id     = !empty($val_1->id) ? $val_1->id : '';
								$emp_name   = !empty($val_1->emp_name) ? $val_1->emp_name : '';
								$emp_type   = !empty($val_1->emp_type) ? $val_1->emp_type : '';
								$store_name = !empty($val_1->store_name) ? $val_1->store_name : '';
								$att_type   = !empty($val_1->attendance_type) ? $val_1->attendance_type : '';
								$reason     = !empty($val_1->reason) ? $val_1->reason : '';
								$c_date     = !empty($val_1->c_date) ? $val_1->c_date : '';
								$in_time    = !empty($val_1->in_time) ? $val_1->in_time : '';
								$out_time   = !empty($val_1->out_time) ? $val_1->out_time : '';
								$log_status = !empty($val_1->log_status) ? $val_1->log_status : '0';

								$attendance_data[] = array(
									'attendance_id'   => $att_id,
									'emp_name'        => $emp_name,
									'emp_type'        => $emp_type,
									'store_name'      => $store_name,
									'attendance_type' => $att_type,
									'reason'          => $reason,
									'c_date'          => date('d-M-Y', strtotime($c_date)),
									'in_time'         => $in_time,
									'out_time'        => $out_time,
									'log_status'      => $log_status,
								);
							}
						}
						$where_2 = array(
							// 'financial_year' => $financ_year,
							'A.published'      => '1',

						);
						$whr_in['B.state_id'] = $d_state_val;

						$limit  = 10;
						$offset = 0;

						$option['order_by']   = 'A.id';
						$option['disp_order'] = 'DESC';
						$colm = 'A.id,A.order_no,A.emp_name,A.store_name,A.contact_name,A.order_status,A._ordered,A._processing,A._packing,A._shiped,A._invoice,A._delivery,A._complete,A._canceled,A.random_value,A.published,A.createdate';
						$order_list = $this->order_model->getOrderPostingJoin($where_2, $limit, $offset, 'result', '', '', $option, '', $colm, '', $whr_in);


						if ($order_list) {
							foreach ($order_list as $key => $val_3) {

								$order_id     = !empty($val_3->id) ? $val_3->id : '';
								$order_no     = !empty($val_3->order_no) ? $val_3->order_no : '';
								$emp_name     = !empty($val_3->emp_name) ? $val_3->emp_name : 'Admin';
								$store_name   = !empty($val_3->store_name) ? $val_3->store_name : '';
								$contact_name = !empty($val_3->contact_name) ? $val_3->contact_name : '';
								$order_status = !empty($val_3->order_status) ? $val_3->order_status : '';
								$_ordered     = !empty($val_3->_ordered) ? $val_3->_ordered : '';
								$_processing  = !empty($val_3->_processing) ? $val_3->_processing : '';
								$_packing     = !empty($val_3->_packing) ? $val_3->_packing : '';
								$_shiped      = !empty($val_3->_shiped) ? $val_3->_shiped : '';
								$_invoice     = !empty($val_3->_invoice) ? $val_3->_invoice : '';
								$_delivery    = !empty($val_3->_delivery) ? $val_3->_delivery : '';
								$_complete    = !empty($val_3->_complete) ? $val_3->_complete : '';
								$_canceled    = !empty($val_3->_canceled) ? $val_3->_canceled : '';
								$random_value = !empty($val_3->random_value) ? $val_3->random_value : '';
								$published    = !empty($val_3->published) ? $val_3->published : '';
								$status       = !empty($val_3->status) ? $val_3->status : '';
								$createdate   = !empty($val_3->createdate) ? $val_3->createdate : '';

								$order_data[] = array(
									'order_id'     => $order_id,
									'order_no'     => $order_no,
									'emp_name'     => $emp_name,
									'store_name'   => $store_name,
									'contact_name' => $contact_name,
									'order_status' => $order_status,
									'_ordered'     => $_ordered,
									'_processing'  => $_processing,
									'_packing'     => $_packing,
									'_shiped'      => $_shiped,
									'_invoice'     => $_invoice,
									'_delivery'    => $_delivery,
									'_complete'    => $_complete,
									'_canceled'    => $_canceled,
									'random_value' => $random_value,
									'published'    => $published,
									'status'       => $status,
									'createdate'   => $createdate,
								);
							}
						}
						// $emp_total = count($count_emp);
						// $prasent_count = count($mg_prasent);
						// $absent_count = $emp_total - $prasent_count;
						$count_zn = count($exsiting_zn);
						$count_ct = count($exsiting_ct);
						$out_let_c = count($total_out_let);
					}
				} else if ($designation_code == 'SO') {
					if (!empty($ctrl_city)) {
						$city_id_finall = substr($ctrl_city, 1, -1);
						//print_r($city_id_finall);exit;

						$d_city = !empty($city_id_finall) ? $city_id_finall : '';

						$d_city_val = explode(',', $d_city);
						$st_count = count($d_city_val);

						//print_r($st_count);exit;

						$exsiting_zn = [];
						$order_data = [];
						$count_emp = [];
						$mg_prasent = array();
						$total_out_let = [];
						for ($i = 0; $i < $st_count; $i++) {


							$wer = array(
								'published'      => '1'
							);
							$myArray = array("'TSI','BDE'");
							$where_in['designation_code'] = $myArray;
							$like['ctrl_city_id'] = ',' . $d_city_val[$i] . ',';

							$co1 = 'employee_id';

							$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1,$where_in);
							$count_emp = [];
							$mg_prasent = array();
							if (!empty($mg_val)) {

								foreach ($mg_val as $key => $value) {
									array_push($count_emp, $value);
								}
							}

							$were_out = array(
								'city_id' =>  $d_city_val[$i],
								'published' => '1'
							);
							$col_out = 'id';
							$out_let = $this->outlets_model->getOutlets($were_out, '', '', 'result', $like, '', '', '', $col_out);
							foreach ($out_let as $key => $value) {
								array_push($total_out_let, $value);
							}



							$wer11 = array(
								'city_id'        =>  $d_city_val[$i],
								'published' => '1'
							);


							$col = 'id';
							$control_zn    = $this->commom_model->getZone($wer11, '', '', "result", '', '', '', '', $col);

							foreach ($control_zn as $value) {
								array_push($exsiting_zn, $value);
							}
						}





						$emp_c = count($count_emp);

						for ($i = 0; $i < $emp_c; $i++) {
							$new_emp_id[]   = !empty($count_emp[$i]->employee_id) ? $count_emp[$i]->employee_id : '';
						}
						// print_r($new_emp_id);exit;
						$wer_att1 = array(

							'c_date' => date('Y-m-d'),
							'published'      => '1',

						);

						$limit  = 10;
						$offset = 0;
						$whr_in['emp_id'] = $new_emp_id;
						$option['order_by']   = 'id';
						$option['disp_order'] = 'DESC';

						$col_att = 'id, emp_name, emp_type, store_name, attendance_type, reason, c_date, in_time, out_time, log_status';

						$attendance_list = $this->attendance_model->getAttendance($wer_att1, $limit, $offset, 'result', '', '', $option, '', $col_att, '', $whr_in);

						$attendance_data = [];
						if ($attendance_list) {
							foreach ($attendance_list as $key => $val_1) {
								$att_id     = !empty($val_1->id) ? $val_1->id : '';
								$emp_name   = !empty($val_1->emp_name) ? $val_1->emp_name : '';
								$emp_type   = !empty($val_1->emp_type) ? $val_1->emp_type : '';
								$store_name = !empty($val_1->store_name) ? $val_1->store_name : '';
								$att_type   = !empty($val_1->attendance_type) ? $val_1->attendance_type : '';
								$reason     = !empty($val_1->reason) ? $val_1->reason : '';
								$c_date     = !empty($val_1->c_date) ? $val_1->c_date : '';
								$in_time    = !empty($val_1->in_time) ? $val_1->in_time : '';
								$out_time   = !empty($val_1->out_time) ? $val_1->out_time : '';
								$log_status = !empty($val_1->log_status) ? $val_1->log_status : '0';

								$attendance_data[] = array(
									'attendance_id'   => $att_id,
									'emp_name'        => $emp_name,
									'emp_type'        => $emp_type,
									'store_name'      => $store_name,
									'attendance_type' => $att_type,
									'reason'          => $reason,
									'c_date'          => date('d-M-Y', strtotime($c_date)),
									'in_time'         => $in_time,
									'out_time'        => $out_time,
									'log_status'      => $log_status,
								);
							}
						}
						$where_2 = array(
							// 'financial_year' => $financ_year,
							'A.published'      => '1',

						);
						$whr_in['B.city_id'] = $d_city_val;

						$limit  = 10;
						$offset = 0;

						$option['order_by']   = 'A.id';
						$option['disp_order'] = 'DESC';
						$colm = 'A.id,A.order_no,A.emp_name,A.store_name,A.contact_name,A.order_status,A._ordered,A._processing,A._packing,A._shiped,A._invoice,A._delivery,A._complete,A._canceled,A.random_value,A.published,A.createdate';
						$order_list = $this->order_model->getOrderPostingJoin($where_2, $limit, $offset, 'result', '', '', $option, '', $colm, '', $whr_in);


						if ($order_list) {
							foreach ($order_list as $key => $val_3) {

								$order_id     = !empty($val_3->id) ? $val_3->id : '';
								$order_no     = !empty($val_3->order_no) ? $val_3->order_no : '';
								$emp_name     = !empty($val_3->emp_name) ? $val_3->emp_name : 'Admin';
								$store_name   = !empty($val_3->store_name) ? $val_3->store_name : '';
								$contact_name = !empty($val_3->contact_name) ? $val_3->contact_name : '';
								$order_status = !empty($val_3->order_status) ? $val_3->order_status : '';
								$_ordered     = !empty($val_3->_ordered) ? $val_3->_ordered : '';
								$_processing  = !empty($val_3->_processing) ? $val_3->_processing : '';
								$_packing     = !empty($val_3->_packing) ? $val_3->_packing : '';
								$_shiped      = !empty($val_3->_shiped) ? $val_3->_shiped : '';
								$_invoice     = !empty($val_3->_invoice) ? $val_3->_invoice : '';
								$_delivery    = !empty($val_3->_delivery) ? $val_3->_delivery : '';
								$_complete    = !empty($val_3->_complete) ? $val_3->_complete : '';
								$_canceled    = !empty($val_3->_canceled) ? $val_3->_canceled : '';
								$random_value = !empty($val_3->random_value) ? $val_3->random_value : '';
								$published    = !empty($val_3->published) ? $val_3->published : '';
								$status       = !empty($val_3->status) ? $val_3->status : '';
								$createdate   = !empty($val_3->createdate) ? $val_3->createdate : '';

								$order_data[] = array(
									'order_id'     => $order_id,
									'order_no'     => $order_no,
									'emp_name'     => $emp_name,
									'store_name'   => $store_name,
									'contact_name' => $contact_name,
									'order_status' => $order_status,
									'_ordered'     => $_ordered,
									'_processing'  => $_processing,
									'_packing'     => $_packing,
									'_shiped'      => $_shiped,
									'_invoice'     => $_invoice,
									'_delivery'    => $_delivery,
									'_complete'    => $_complete,
									'_canceled'    => $_canceled,
									'random_value' => $random_value,
									'published'    => $published,
									'status'       => $status,
									'createdate'   => $createdate,
								);
							}
						}
						// $emp_total = count($count_emp);
						// $prasent_count = count($mg_prasent);
						// $absent_count = $emp_total - $prasent_count;
						$count_zn = count($exsiting_zn);
						$count_ct = count($d_city_val);
						$out_let_c = count($total_out_let);
						$st_count  =0;
					}
				} else if ($designation_code == 'TSI') { 
					if (!empty($ctrl_zone)) {
						$zone_id_finall = substr($ctrl_zone, 1, -1);
						//print_r($zone_id_finall);exit;

						$d_zone = !empty($zone_id_finall) ? $zone_id_finall : '';

						$d_zone_val = explode(',', $d_zone);
						$st_count = count($d_zone_val);

					

						$exsiting_zn = [];
						$order_data = [];
						$count_emp = [];
						$mg_prasent = array();
						$total_out_let = [];
						for ($i = 0; $i < $st_count; $i++) {


							$wer = array(
								'published'      => '1'
							);
							$myArray = array("'BDE'");
							$where_in['designation_code'] = $myArray;
							$like['ctrl_zone_id'] = ',' . $d_zone_val[$i] . ',';

							$co1 = 'employee_id';

							$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1,$where_in);
							
							// $count_emp = [];
							
							if (!empty($mg_val)) {
								
								foreach ($mg_val as $key => $value) {
								
									array_push($count_emp, $value);
								}
							}

							$were_out = array(
								'zone_id' =>  $d_zone_val[$i],
								'published' => '1'
							);
							$col_out = 'id';
							$out_let = $this->outlets_model->getOutlets($were_out, '', '', 'result', $like, '', '', '', $col_out);
							foreach ($out_let as $key => $value) {
								array_push($total_out_let, $value);
							}



							
						}

					


						$emp_c = count($count_emp);
				
						for ($i = 0; $i < $emp_c; $i++) {
							$new_emp_id[]   = !empty($count_emp[$i]->employee_id) ? $count_emp[$i]->employee_id : '';
						}
						
						
						$wer_att1 = array(

							'c_date' => date('Y-m-d'),
							'published'      => '1',

						);

						$limit  = 10;
						$offset = 0;
						$whr_in['emp_id'] = $new_emp_id;
						$option['order_by']   = 'id';
						$option['disp_order'] = 'DESC';

						$col_att = 'id, emp_name, emp_type, store_name, attendance_type, reason, c_date, in_time, out_time, log_status';

						$attendance_list = $this->attendance_model->getAttendance($wer_att1, $limit, $offset, 'result', '', '', $option, '', $col_att, '', $whr_in);

						$attendance_data = [];
						if ($attendance_list) {
							foreach ($attendance_list as $key => $val_1) {
								$att_id     = !empty($val_1->id) ? $val_1->id : '';
								$emp_name   = !empty($val_1->emp_name) ? $val_1->emp_name : '';
								$emp_type   = !empty($val_1->emp_type) ? $val_1->emp_type : '';
								$store_name = !empty($val_1->store_name) ? $val_1->store_name : '';
								$att_type   = !empty($val_1->attendance_type) ? $val_1->attendance_type : '';
								$reason     = !empty($val_1->reason) ? $val_1->reason : '';
								$c_date     = !empty($val_1->c_date) ? $val_1->c_date : '';
								$in_time    = !empty($val_1->in_time) ? $val_1->in_time : '';
								$out_time   = !empty($val_1->out_time) ? $val_1->out_time : '';
								$log_status = !empty($val_1->log_status) ? $val_1->log_status : '0';

								$attendance_data[] = array(
									'attendance_id'   => $att_id,
									'emp_name'        => $emp_name,
									'emp_type'        => $emp_type,
									'store_name'      => $store_name,
									'attendance_type' => $att_type,
									'reason'          => $reason,
									'c_date'          => date('d-M-Y', strtotime($c_date)),
									'in_time'         => $in_time,
									'out_time'        => $out_time,
									'log_status'      => $log_status,
								);
							}
						}
						$where_2 = array(
							// 'financial_year' => $financ_year,
							'A.published'      => '1',

						);
						$whr_in['B.id'] = $d_zone_val;

						$limit  = 10;
						$offset = 0;

						$option['order_by']   = 'A.id';
						$option['disp_order'] = 'DESC';
						$colm = 'A.id,A.order_no,A.emp_name,A.store_name, A.zone_id,A.contact_name,A.order_status,A._ordered,A._processing,A._packing,A._shiped,A._invoice,A._delivery,A._complete,A._canceled,A.random_value,A.published,A.createdate';
						$order_list = $this->order_model->getOrderPostingJoin($where_2, $limit, $offset, 'result', '', '', $option, '', $colm, '', $whr_in);


						if ($order_list) {
							foreach ($order_list as $key => $val_3) {

								$order_id     = !empty($val_3->id) ? $val_3->id : '';
								$order_no     = !empty($val_3->order_no) ? $val_3->order_no : '';
								$emp_name     = !empty($val_3->emp_name) ? $val_3->emp_name : 'Admin';
								$store_name   = !empty($val_3->store_name) ? $val_3->store_name : '';
								$contact_name = !empty($val_3->contact_name) ? $val_3->contact_name : '';
								$order_status = !empty($val_3->order_status) ? $val_3->order_status : '';
								$_ordered     = !empty($val_3->_ordered) ? $val_3->_ordered : '';
								$_processing  = !empty($val_3->_processing) ? $val_3->_processing : '';
								$_packing     = !empty($val_3->_packing) ? $val_3->_packing : '';
								$_shiped      = !empty($val_3->_shiped) ? $val_3->_shiped : '';
								$_invoice     = !empty($val_3->_invoice) ? $val_3->_invoice : '';
								$_delivery    = !empty($val_3->_delivery) ? $val_3->_delivery : '';
								$_complete    = !empty($val_3->_complete) ? $val_3->_complete : '';
								$_canceled    = !empty($val_3->_canceled) ? $val_3->_canceled : '';
								$random_value = !empty($val_3->random_value) ? $val_3->random_value : '';
								$published    = !empty($val_3->published) ? $val_3->published : '';
								$status       = !empty($val_3->status) ? $val_3->status : '';
								$createdate   = !empty($val_3->createdate) ? $val_3->createdate : '';

								$order_data[] = array(
									'order_id'     => $order_id,
									'order_no'     => $order_no,
									'emp_name'     => $emp_name,
									'store_name'   => $store_name,
									'contact_name' => $contact_name,
									'order_status' => $order_status,
									'_ordered'     => $_ordered,
									'_processing'  => $_processing,
									'_packing'     => $_packing,
									'_shiped'      => $_shiped,
									'_invoice'     => $_invoice,
									'_delivery'    => $_delivery,
									'_complete'    => $_complete,
									'_canceled'    => $_canceled,
									'random_value' => $random_value,
									'published'    => $published,
									'status'       => $status,
									'createdate'   => $createdate,
								);
							}
						}
						// $emp_total = count($count_emp);
						// $prasent_count = count($mg_prasent);
						// $absent_count = $emp_total - $prasent_count;
						$count_zn = count($d_zone_val);
						$count_ct = 0;
						$out_let_c = count($total_out_let);
						$st_count  =0;
					}
				}






				$manager_dashboard = array(
					'out_let_c'          => $out_let_c,
					'state_count'        => $st_count,
					'city_count'         => $count_ct,
					'zone_count'         => $count_zn,
					'order_data'         => $order_data,
					'attendance_data'     => $attendance_data,
					// 'prasent_count'       => $prasent_count,
					// 'absent_count'      => $absent_count,

				);

				$response['status']  = 1;
				$response['message'] = "Success";
				$response['data']    = $manager_dashboard;
				echo json_encode($response);
				return;
			}
		} else {
			$response['status']  = 0;
			$response['message'] = "Error";
			$response['data']    = [];
			echo json_encode($response);
			return;
		}
	}
}

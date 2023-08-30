<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('commom_model');
		$this->load->model('employee_model');
		$this->load->model('outlets_model');
		$this->load->model('assignproduct_model');
		$this->load->model('distributors_model');
		$this->load->model('order_model');
		$this->load->model('vendors_model');
		$this->load->model('payment_model');
		$this->load->model('invoice_model');
		$this->load->model('return_model');
		$this->load->model('target_model');
		$this->load->model('login_model');
		$this->load->model('loyalty_model');
		$this->load->model('attendance_model');
	}

	public function index()
	{
		echo "Test";
	}

	// Create / Edit Order
	// ***************************************************
	public function create_order($param1 = "", $param2 = "", $param3 = "")
	{
		$sales_order  = $this->input->post('sales_order');
		$employee_id  = $this->input->post('employee_id');
		$att_id       = $this->input->post('att_id');
		$store_id     = $this->input->post('store_id');
		$order_type   = $this->input->post('order_type');
		$bill_type    = $this->input->post('bill_type');
		$entry_type   = $this->input->post('entry_type');
		$due_days     = $this->input->post('due_days');
		$discount     = $this->input->post('discount');
		$method       = $this->input->post('method');
		$random_value = generateRandomString(32);
		$sales_value  = json_decode($sales_order);

		// Financial Year Details
		$option['order_by']   = 'id';
		$option['disp_order'] = 'DESC';

		$where = array(
			'status'    => '1',
			'published' => '1',
		);

		$data_list = $this->commom_model->getfinancial($where, '1', '0', 'result', '', '', $option);

		$financial_id = !empty($data_list[0]->id) ? $data_list[0]->id : '';

		// Create Outlet Order
		if ($method == '_addSalesOrder') {
			$error    = FALSE;
			$errors   = array();
			$required = array('store_id', 'order_type', 'sales_order', 'bill_type');
			if ($order_type == 1) {
				array_push($required, 'employee_id');
			}
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

			if (preg_match('#[^0-9]#', $due_days)) {
				$response['status']  = 0;
				$response['message'] = "Please enter valid due days";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}

			if (preg_match('#[^0-9]#', $discount)) {
				$response['status']  = 0;
				$response['message'] = "Please enter valid discount";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}

			if (count($errors) == 0) {

				// Employee details
				$emp_whr = array('id' => $employee_id, 'published' => 1);
				$emp_col = 'company_id';
				$emp_qry = $this->employee_model->getEmployee($emp_whr, '', '', 'row', '', '', '', '', $emp_col);
				$dis_id  = 0;

				if($emp_qry)
				{
					$dis_id = ($emp_qry->company_id)?$emp_qry->company_id:0;
				}

				// Bill Number
				$where = array(
					'distributor_id' => $dis_id,
					'financial_year' => $financial_id,
					'published'      => '1',
					'status'         => '1',
				);

				$bill_val  = $this->order_model->getOrder($where, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id)+1 AS autoid');

				$count_val = leadingZeros($bill_val[0]->autoid, 5);
				$bill_num  = 'ORD' . $count_val;

				$username = '';
				$emp_type = '';

				if ($order_type == 1) {
					$where_1 = array(
						'id'        => $employee_id,
						'published' => '1',
						'status'    => '1',
					);

					$column_1 = 'username, first_name, last_name, log_type';

					$emp_data = $this->employee_model->getEmployee($where_1, '', '', 'result', '', '', '', '', $column_1);

					// Employee Details
					$first_name = !empty($emp_data[0]->first_name) ? $emp_data[0]->first_name : '';
					$last_name = !empty($emp_data[0]->last_name) ? $emp_data[0]->last_name : '';
					$emp_type = !empty($emp_data[0]->log_type) ? $emp_data[0]->log_type : '';
					
						$arr = array($first_name,$last_name);
								$username =join(" ",$arr);
				}

				$where_2 = array(
					'id'        => $store_id,
					'published' => '1',
					'status'    => '1',
				);

				$column_2 = 'company_name, contact_name, zone_id, state_id, city_id, due_days, discount, outlet_type, available_limit';

				$out_data = $this->outlets_model->getOutlets($where_2, '', '', 'result', '', '', '', '', $column_2);

				$company_name = !empty($out_data[0]->company_name) ? $out_data[0]->company_name : '';
				$contact_name = !empty($out_data[0]->contact_name) ? $out_data[0]->contact_name : '';
				$state_id      = !empty($out_data[0]->state_id) ? $out_data[0]->state_id : '';
				$city_id      = !empty($out_data[0]->city_id) ? $out_data[0]->city_id : '';
				$zone_id      = !empty($out_data[0]->zone_id) ? $out_data[0]->zone_id : '';
				$out_due_days = !empty($out_data[0]->due_days) ? $out_data[0]->due_days : '0';
				$out_discount = !empty($out_data[0]->discount) ? $out_data[0]->discount : '0';
				$outlet_type  = !empty($out_data[0]->outlet_type) ? $out_data[0]->outlet_type : '0';
				$avl_limit    = !empty($out_data[0]->available_limit) ? $out_data[0]->available_limit : '0';

				// Outlet Details
				$dueValue = $out_due_days;
				if (!empty($due_days)) {
					$dueValue = $due_days;
				}

				$disValue = $out_discount;
				if (!empty($discount)) {
					$disValue = $discount;
				}

				// Order Details 
				$net_total = 0;
				foreach ($sales_value as $key => $value) {
					$qty        = !empty($value->qty) ? $value->qty : '0';
					$price      = !empty($value->price) ? $value->price : '0';
					$price_val  = $qty * number_format((float)$price, 2, '.', '');
					$net_total += $price_val;
				}

				$total_value = round($net_total);

				if ($avl_limit >= $total_value) {
					$order_data = array(
						'distributor_id' => $dis_id,
						'order_no'       => $bill_num,
						'bill_type'      => $bill_type,
						'order_type'     => $order_type,
						'emp_id'         => $employee_id,
						'emp_name'       => $username,
						'emp_type'       => $emp_type,
						'store_id'       => $store_id,
						'store_name'     => $company_name,
						'contact_name'   => $contact_name,
						'zone_id'        => $zone_id,
						'due_days'       => $dueValue,
						'discount'       => $disValue,
						'outlet_type'    => $outlet_type,
						'order_status'   => '2',
						'_ordered'       => date('Y-m-d H:i:s'),
						'_processing'    => date('Y-m-d H:i:s'),
						'financial_year' => $financial_id,
						'random_value'   => $random_value,
						'date'           => date('Y-m-d'),
						'time'           => date('H:i:s'),
						'createdate'     => date('Y-m-d H:i:s'),
					);

					if (!empty($employee_id)) {
						$order_data['att_id'] = $att_id;
					}

					$insert = $this->order_model->order_insert($order_data);

					// Order Details
					foreach ($sales_value as $key => $value) {
						$product_id = !empty($value->product_id) ? $value->product_id : '0';
						$type_id    = !empty($value->type_id) ? $value->type_id : '0';
						$unit_val   = !empty($value->unit_val) ? $value->unit_val : '0';
						$hsn_code   = !empty($value->hsn_code) ? $value->hsn_code : '';
						$gst_val    = !empty($value->gst_val) ? $value->gst_val : '0';
						$qty        = !empty($value->qty) ? $value->qty : '0';
						$price      = !empty($value->price) ? $value->price : '0';

						$where_3 = array(
							'id'        => $product_id,
							'published' => '1',
							'status'    => '1',
						);

						$column_3 = 'vendor_id, vendor_type, hsn_code, gst';

						$product_det = $this->commom_model->getProduct($where_3, '', '', 'result', '', '', '', '', $column_3);

						$product_val = $product_det[0];

						$vdr_id   = !empty($product_val->vendor_id) ? $product_val->vendor_id : '';
						$vdr_type = !empty($product_val->vendor_type) ? $product_val->vendor_type : '';
						$hsn_code = !empty($product_val->hsn_code) ? $product_val->hsn_code : '';
						$gst_val  = !empty($product_val->gst) ? $product_val->gst : '';

						$product_data = array(
							'order_id'     => $insert,
							'order_no'     => $bill_num,
							'dis_id'       => $dis_id,
							'zone_id'      => $zone_id,
							'product_id'   => $product_id,
							'type_id'      => $type_id,
							'vendor_id'    => $vdr_id,
							'vendor_type'  => $vdr_type,
							'hsn_code'     => $hsn_code,
							'gst_val'      => $gst_val,
							'unit_val'     => $unit_val,
							'price'        => digit_val($price),
							'entry_qty'    => $qty,
							'order_qty'    => $qty,
							'receive_qty'  => '0',
							'order_status' => '2',
							'_ordered'     => date('Y-m-d H:i:s'),
							'_processing'  => date('Y-m-d H:i:s'),
							'createdate'   => date('Y-m-d H:i:s'),
						);

						$order_details = $this->order_model->orderDetails_insert($product_data);
					}

					if ($insert) {
						$response['status']  = 1;
						$response['message'] = "Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					} else {
						$response['status']  = 0;
						$response['message'] = "Not Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				} else {
					$response['status']  = 0;
					$response['message'] = "Invalid Balance";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		} else {
			$response['status']  = 0;
			$response['message'] = "Error";
			$response['data']    = [];
			echo json_encode($response);
			return;
		}
	}

	// Manage Order
	// ***************************************************
	public function manage_order($param1 = "", $param2 = "", $param3 = "")
	{
		// JSON
		header('Content-Type: application/json');

		$method = $this->input->post('method');

		// List overall outlet order
		if ($method == '_listOrderPaginate') {
			$limit       = $this->input->post('limit');
			$offset      = $this->input->post('offset');
			$financ_year = $this->input->post('financial_year');

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
				if ($limit != '' && $offset != '') {
					$limit  = $limit;
					$offset = $offset;
				} else {
					$limit  = 10;
					$offset = 0;
				}

				$search    = $this->input->post('search');
				$load_data = $this->input->post('load_data');

				if ($search != '') {
					$like['name']     = $search;
				} else {
					$like = [];
				}

				if ($load_data != '') {
					$where = array(
						'order_status'   => $load_data,
						// 'financial_year' => $financ_year,
						'published'      => '1'
					);
				} else {
					$where = array(
						// 'financial_year' => $financ_year,
						'published'      => '1'
					);
				}

				$column = 'id';
				$overalldatas = $this->order_model->getOrder($where, '', '', 'result', $like, '', '', '', $column);

				if ($overalldatas) {
					$totalc = count($overalldatas);
				} else {
					$totalc = 0;
				}

				$option['order_by']   = 'id';
				$option['disp_order'] = 'DESC';

				$data_list = $this->order_model->getOrder($where, $limit, $offset, 'result', $like, '', $option);

				if ($data_list) {
					$order_list = [];
					foreach ($data_list as $key => $value) {

						$order_id     = !empty($value->id) ? $value->id : '';
						$order_no     = !empty($value->order_no) ? $value->order_no : '';
						$emp_name     = !empty($value->emp_name) ? $value->emp_name : 'Admin';
						$store_name   = !empty($value->store_name) ? $value->store_name : '';
						$contact_name = !empty($value->contact_name) ? $value->contact_name : '';
						$order_status = !empty($value->order_status) ? $value->order_status : '';
						$_ordered     = !empty($value->_ordered) ? $value->_ordered : '';
						$_processing  = !empty($value->_processing) ? $value->_processing : '';
						$_packing     = !empty($value->_packing) ? $value->_packing : '';
						$_shiped      = !empty($value->_shiped) ? $value->_shiped : '';
						$_invoice     = !empty($value->_invoice) ? $value->_invoice : '';
						$_delivery    = !empty($value->_delivery) ? $value->_delivery : '';
						$_complete    = !empty($value->_complete) ? $value->_complete : '';
						$_canceled    = !empty($value->_canceled) ? $value->_canceled : '';
						$random_value = !empty($value->random_value) ? $value->random_value : '';
						$published    = !empty($value->published) ? $value->published : '';
						$status       = !empty($value->status) ? $value->status : '';
						$createdate   = !empty($value->createdate) ? $value->createdate : '';

						$order_list[] = array(
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

					if ($offset != '' && $limit != '') {
						$offset = $offset + $limit;
						$limit  = $limit;
					} else {
						$offset = $limit;
						$limit  = 10;
					}

					$response['status']       = 1;
					$response['message']      = "Success";
					$response['total_record'] = $totalc;
					$response['offset']       = (int)$offset;
					$response['limit']        = (int)$limit;
					$response['data']         = $order_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		}

		// Sales employee wise outlet list
		else if ($method == '_listEmployeeOrderPaginate') {
			$employee_id = $this->input->post('employee_id');

			if (!empty($employee_id)) {
				$limit  = $this->input->post('limit');
				$offset = $this->input->post('offset');

				if ($limit != '' && $offset != '') {
					$limit  = $limit;
					$offset = $offset;
				} else {
					$limit  = 10;
					$offset = 0;
				}

				$search = $this->input->post('search');
				if ($search != '') {
					$like['name'] = $search;

					$where = array(
						'emp_id'    => $employee_id,
						'published' => '1'
					);
				} else {
					$like = [];
					$where = array(
						'emp_id'    => $employee_id,
						'published' => '1'
					);
				}

				$column = 'id, emp_id';
				$overalldatas = $this->order_model->getEmployeeOrder($where, '', '', 'result', $like, '', '', '', $column);

				if ($overalldatas) {
					$totalc = count($overalldatas);
				} else {
					$totalc = 0;
				}

				$option['order_by']   = 'id';
				$option['disp_order'] = 'DESC';

				$data_list = $this->order_model->getEmployeeOrder($where, $limit, $offset, 'result', $like, '', $option);

				// echo $this->db->last_query(); exit;

				if ($data_list) {
					$order_list = [];

					foreach ($data_list as $key => $value) {

						$order_id     = !empty($value->id) ? $value->id : '';
						$order_no     = !empty($value->order_no) ? $value->order_no : '';
						$emp_name     = !empty($value->emp_name) ? $value->emp_name : '';
						$store_id     = !empty($value->store_id) ? $value->store_id : '';
						$store_name   = !empty($value->store_name) ? $value->store_name : '';
						$contact_name = !empty($value->contact_name) ? $value->contact_name : '';
						$order_status = !empty($value->order_status) ? $value->order_status : '';
						$_ordered     = !empty($value->_ordered) ? $value->_ordered : '';
						$_processing  = !empty($value->_processing) ? $value->_processing : '';
						$_packing     = !empty($value->_packing) ? $value->_packing : '';
						$_shiped      = !empty($value->_shiped) ? $value->_shiped : '';
						$_invoice     = !empty($value->_invoice) ? $value->_invoice : '';
						$_delivery    = !empty($value->_delivery) ? $value->_delivery : '';
						$_complete    = !empty($value->_complete) ? $value->_complete : '';
						$_canceled    = !empty($value->_canceled) ? $value->_canceled : '';
						$random_value = !empty($value->random_value) ? $value->random_value : '';
						$published    = !empty($value->published) ? $value->published : '';
						$status       = !empty($value->status) ? $value->status : '';
						$createdate   = !empty($value->createdate) ? $value->createdate : '';

						// Outlet Address
						$str_whr  = array('id' => $store_id);
						$str_col  = 'address';
						$str_val  = $this->outlets_model->getOutlets($str_whr, '', '', 'result', '', '', '', '', $str_col);
						$str_adrs = !empty($str_val[0]->address) ? $str_val[0]->address : '';

						$order_list[] = array(
							'order_id'      => $order_id,
							'order_no'      => $order_no,
							'emp_name'      => $emp_name,
							'store_name'    => $store_name,
							'store_address' => $str_adrs,
							'contact_name'  => $contact_name,
							'order_status'  => $order_status,
							'_ordered'      => $_ordered,
							'_processing'   => $_processing,
							'_packing'      => $_packing,
							'_shiped'       => $_shiped,
							'_invoice'      => $_invoice,
							'_delivery'     => $_delivery,
							'_complete'     => $_complete,
							'_canceled'     => $_canceled,
							'random_value'  => $random_value,
							'published'     => $published,
							'status'        => $status,
							'createdate'    => $createdate,
						);
					}

					if ($offset != '' && $limit != '') {
						$offset = $offset + $limit;
						$limit  = $limit;
					} else {
						$offset = $limit;
						$limit  = 10;
					}

					$response['status']       = 1;
					$response['message']      = "Success";
					$response['total_record'] = $totalc;
					$response['offset']       = (int)$offset;
					$response['limit']        = (int)$limit;
					$response['data']         = $order_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		}

		// Outlet Order Details
		else if ($method == '_orderDetails') {
			$random_value = $this->input->post('random_value');

			if (!empty($random_value)) {
				// Bill Details
				$bill_where = array(
					'random_value' => $random_value,
					'published'    => '1',
					'status'       => '1',
				);

				$bill_data = $this->order_model->getOrder($bill_where);

				if (!empty($bill_data)) {
					$order_id     = !empty($bill_data[0]->id) ? $bill_data[0]->id : '';
					$order_no     = !empty($bill_data[0]->order_no) ? $bill_data[0]->order_no : '';
					$bill_type    = !empty($bill_data[0]->bill_type) ? $bill_data[0]->bill_type : '';
					$order_type   = !empty($bill_data[0]->order_type) ? $bill_data[0]->order_type : '';
					$emp_name     = !empty($bill_data[0]->emp_name) ? $bill_data[0]->emp_name : '';
					$store_id     = !empty($bill_data[0]->store_id) ? $bill_data[0]->store_id : '';
					$zone_id      = !empty($bill_data[0]->zone_id) ? $bill_data[0]->zone_id : '';
					$discount     = !empty($bill_data[0]->discount) ? $bill_data[0]->discount : '';
					$due_days     = !empty($bill_data[0]->due_days) ? $bill_data[0]->due_days : '';
					$store_name   = !empty($bill_data[0]->store_name) ? $bill_data[0]->store_name : '';
					$contact_name = !empty($bill_data[0]->contact_name) ? $bill_data[0]->contact_name : '';
					$outlet_type  = !empty($bill_data[0]->outlet_type) ? $bill_data[0]->outlet_type : '';
					$order_status = !empty($bill_data[0]->order_status) ? $bill_data[0]->order_status : '';
					$_ordered     = !empty($bill_data[0]->_ordered) ? $bill_data[0]->_ordered : '';
					$_processing  = !empty($bill_data[0]->_processing) ? $bill_data[0]->_processing : '';
					$_packing     = !empty($bill_data[0]->_packing) ? $bill_data[0]->_packing : '';
					$_shiped      = !empty($bill_data[0]->_shiped) ? $bill_data[0]->_shiped : '';
					$_invoice     = !empty($bill_data[0]->_invoice) ? $bill_data[0]->_invoice : '';
					$_delivery    = !empty($bill_data[0]->_delivery) ? $bill_data[0]->_delivery : '';
					$_complete    = !empty($bill_data[0]->_complete) ? $bill_data[0]->_complete : '';
					$_canceled    = !empty($bill_data[0]->_canceled) ? $bill_data[0]->_canceled : '';
					$random_value = !empty($bill_data[0]->random_value) ? $bill_data[0]->random_value : '';
					$reason       = !empty($bill_data[0]->reason) ? $bill_data[0]->reason : '';
					$published    = !empty($bill_data[0]->published) ? $bill_data[0]->published : '';
					$status       = !empty($bill_data[0]->status) ? $bill_data[0]->status : '';
					$createdate   = !empty($bill_data[0]->createdate) ? $bill_data[0]->createdate : '';

					$bill_details = array(
						'order_id'     => $order_id,
						'order_no'     => $order_no,
						'bill_type'    => $bill_type,
						'order_type'   => $order_type,
						'emp_name'     => $emp_name,
						'store_name'   => $store_name,
						'contact_name' => $contact_name,
						'outlet_type'  => $outlet_type,
						'order_status' => $order_status,
						'discount'     => $discount,
						'due_days'     => $due_days,
						'_ordered'     => $_ordered,
						'_processing'  => $_processing,
						'_packing'     => $_packing,
						'_shiped'      => $_shiped,
						'_invoice'     => $_invoice,
						'_delivery'    => $_delivery,
						'_complete'    => $_complete,
						'_canceled'    => $_canceled,
						'random_value' => $random_value,
						'reason'       => $reason,
						'published'    => $published,
						'status'       => $status,
						'createdate'   => $createdate,
					);

					// Store Details
					$store_where  = array(
						'id'        => $store_id,
						'published' => '1',
					);

					$store_column = 'company_name, contact_name, mobile, email, gst_no, pan_no, tan_no, address, state_id';

					$store_data   = $this->outlets_model->getOutlets($store_where, '', '', 'result', '', '', '', '', $store_column);

					$store_details = [];
					if (!empty($store_data)) {
						$store_value  = $store_data[0];
						$company_name = !empty($store_value->company_name) ? $store_value->company_name : '';
						$contact_name = !empty($store_value->contact_name) ? $store_value->contact_name : '';
						$mobile       = !empty($store_value->mobile) ? $store_value->mobile : '';
						$email        = !empty($store_value->email) ? $store_value->email : '';
						$gst_no       = !empty($store_value->gst_no) ? $store_value->gst_no : '';
						$pan_no       = !empty($store_value->pan_no) ? $store_value->pan_no : '';
						$tan_no       = !empty($store_value->tan_no) ? $store_value->tan_no : '';
						$address      = !empty($store_value->address) ? $store_value->address : '';
						$state_id     = !empty($store_value->state_id) ? $store_value->state_id : '';

						// State Details
						$state_where = array(
							'id'        => $state_id,
							'published' => '1',
						);

						$state_data  = $this->commom_model->getState($state_where);

						$state_name = !empty($state_data[0]->state_name) ? $state_data[0]->state_name : '';
						$state_code = !empty($state_data[0]->state_code) ? $state_data[0]->state_code : '';
						$gst_code   = !empty($state_data[0]->gst_code) ? $state_data[0]->gst_code : '';

						$store_details = array(
							'company_name' => $company_name,
							'contact_name' => $contact_name,
							'mobile'       => $mobile,
							'email'        => $email,
							'gst_no'       => $gst_no,
							'pan_no'       => $pan_no,
							'tan_no'       => $tan_no,
							'address'      => $address,
							'state_id'     => $state_id,
							'state_name'   => $state_name,
							'state_code'   => $state_code,
							'gst_code'     => $gst_code,
							'distributor'  => '',
						);
					}

					// Order Details
					$order_where = array(
						'order_id'      => $order_id,
						'delete_status' => '1',
						'published'     => '1',
						'status'        => '1',
					);

					$order_data = $this->order_model->getOrderDetails($order_where);

					$product_details = [];
					if (!empty($order_data)) {
						foreach ($order_data as $key => $value) {

							$auto_id        = !empty($value->id) ? $value->id : '';
							$order_id       = !empty($value->order_id) ? $value->order_id : '';
							$order_no       = !empty($value->order_no) ? $value->order_no : '';
							$product_id     = !empty($value->product_id) ? $value->product_id : '';
							$type_id        = !empty($value->type_id) ? $value->type_id : '';
							$hsn_code       = !empty($value->hsn_code) ? $value->hsn_code : '';
							$gst_val        = !empty($value->gst_val) ? $value->gst_val : '0';
							$unit_val       = !empty($value->unit_val) ? $value->unit_val : '0';
							$price          = !empty($value->price) ? $value->price : '0';
							$order_qty      = !empty($value->order_qty) ? $value->order_qty : '0';
							$receive_qty    = !empty($value->receive_qty) ? $value->receive_qty : '0';
							$product_status = !empty($value->order_status) ? $value->order_status : '';
							$published      = !empty($value->published) ? $value->published : '';
							$status         = !empty($value->status) ? $value->status : '';
							$createdate     = !empty($value->createdate) ? $value->createdate : '';

							// Product Details
							$where_1      = array('id' => $product_id);
							$product_det  = $this->commom_model->getProduct($where_1);
							$product_name = isset($product_det[0]->name) ? $product_det[0]->name : '';

							// Unit Type Details
							$where_2   = array('id' => $unit_val);
							$unit_det  = $this->commom_model->getUnit($where_2);
							$unit_name = isset($unit_det[0]->name) ? $unit_det[0]->name : '';

							// Product Type Details
							$where_3     = array('id' => $type_id);
							$type_det    = $this->commom_model->getProductType($where_3);
							$description = isset($type_det[0]->description) ? $type_det[0]->description : '';
							$type_name   = isset($type_det[0]->product_type) ? $type_det[0]->product_type : '';

							// Assign Product Details
							$where_4 = array(
								'type_id'   => $type_id,
								'zone_id'   => $zone_id,
								'status'    => '1',
								'published' => '1'
							);

							$column_4    = 'distributor_id';
							$assign_data = $this->assignproduct_model->getAssignProductDetails($where_4, '', '', 'result', '', '', '', '', $column_4);

							$distri_id   = !empty($assign_data[0]->distributor_id) ? $assign_data[0]->distributor_id : '';

							// Distributor Details
							$dis_where  = array('id' => $distri_id);
							$dis_column = 'dis_code';
							$dis_data   = $this->distributors_model->getDistributors($dis_where, '', '', 'result', '', '', '', '', $dis_column);
							$dis_name   = !empty($dis_data[0]->dis_code) ? $dis_data[0]->dis_code : '';

							$product_details[] = array(
								'auto_id'        => $auto_id,
								'order_id'       => $order_id,
								'order_no'       => $order_no,
								'dis_name'       => $dis_name,
								'product_id'     => $product_id,
								'product_name'   => $product_name,
								'type_id'        => $type_id,
								'type_name'      => $type_name,
								'description'    => $description,
								'hsn_code'       => $hsn_code,
								'gst_val'        => $gst_val,
								'unit_val'       => $unit_val,
								'unit_name'      => $unit_name,
								'price'          => $price,
								'order_qty'      => $order_qty,
								'receive_qty'    => $receive_qty,
								'product_status' => $product_status,
								'published'      => $published,
								'status'         => $status,
								'createdate'     => $createdate,
							);
						}
					}

					// Tax Details
					$tax_where = array(
						'order_id'  => $order_id,
						'published' => '1',
						'status'    => '1',
					);

					$tax_column = 'hsn_code, gst_val';

					$groupby = 'hsn_code';

					$tax_data   = $this->order_model->getOrderDetails($tax_where, '', '', 'result', '', '', '', '', $tax_column, $groupby);

					$tax_details = [];
					if (!empty($tax_data)) {
						foreach ($tax_data as $key => $value) {

							$hsn_code = !empty($value->hsn_code) ? $value->hsn_code : '';
							$gst_val  = !empty($value->gst_val) ? $value->gst_val : '';

							// Price Details
							$price_where = array(
								'order_id'  => $order_id,
								'hsn_code'  => $hsn_code,
								'published' => '1',
								'status'    => '1',
							);

							$price_column = 'price';

							$price_data   = $this->order_model->getOrderDetails($price_where, '', '', 'result', '', '', '', '', $price_column);

							$product_price = 0;
							foreach ($price_data as $key => $price_val) {
								$price = !empty($price_val->price) ? $price_val->price : '0';

								$product_price += number_format((float)$price, 2, '.', '');
							}

							$tax_details[] = array(
								'hsn_code'  => $hsn_code,
								'gst_val'   => $gst_val,
								'price_val' => strval($product_price),
							);
						}
					}

					$order_details = array(
						'bill_details'    => $bill_details,
						'store_details'   => $store_details,
						'product_details' => $product_details,
						'tax_details'     => $tax_details,
					);

					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = $order_details;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		} else if ($method == '_orderData') {
			$order_id = $this->input->post('order_id');

			if (!empty($order_id)) {
				// Bill Details
				$bill_where = array(
					'id'        => $order_id,
					'published' => '1',
					'status'    => '1',
				);

				$bill_data = $this->order_model->getOrder($bill_where);

				if ($bill_data) {
					$bill_type    = !empty($bill_data[0]->bill_type) ? $bill_data[0]->bill_type : '';
					$order_no     = !empty($bill_data[0]->order_no) ? $bill_data[0]->order_no : '';
					$random_value = !empty($bill_data[0]->random_value) ? $bill_data[0]->random_value : '';
					$published    = !empty($bill_data[0]->published) ? $bill_data[0]->published : '';
					$status       = !empty($bill_data[0]->status) ? $bill_data[0]->status : '';

					$bill_detail = array(
						'order_id'     => $order_id,
						'bill_type'    => $bill_type,
						'order_no'     => $order_no,
						'random_value' => $random_value,
						'published'    => $published,
						'status'       => $status,
					);

					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = $bill_detail;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		} else if ($method == '_orderProductData') {
			$auto_id  = $this->input->post('auto_id');
			$order_id = $this->input->post('order_id');

			$error    = FALSE;
			$errors   = array();
			$required = array('auto_id', 'order_id');
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
				$order_whr = array(
					'id'        => $auto_id,
					'order_id'  => $order_id,
					'published' => '1',
					'status'    => '1',
				);

				$order_column = 'product_id, type_id, unit_val';
				$order_data   = $this->order_model->getOrderDetails($order_whr, '', '', 'result', '', '', '', '', $order_column);

				if ($order_data) {
					$product_id = !empty($order_data[0]->product_id) ? $order_data[0]->product_id : '';
					$type_id    = !empty($order_data[0]->type_id) ? $order_data[0]->type_id : '';
					$unit_val   = !empty($order_data[0]->unit_val) ? $order_data[0]->unit_val : '';

					// Product Details
					$where_1      = array('id' => $product_id);
					$product_det  = $this->commom_model->getProduct($where_1);
					$product_name = isset($product_det[0]->name) ? $product_det[0]->name : '';

					// Product Type Details
					$where_3     = array('id' => $type_id);
					$type_det    = $this->commom_model->getProductType($where_3);
					$description = isset($type_det[0]->description) ? $type_det[0]->description : '';
					$type_name   = isset($type_det[0]->product_type) ? $type_det[0]->product_type : '';

					$order_details = array(
						'order_id'      => $order_id,
						'order_auto_id' => $auto_id,
						'product_id'    => $product_id,
						'product_name'  => $product_name,
						'type_id'       => $type_id,
						'type_name'     => $description,
						'unit_val'      => $unit_val,
					);

					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = $order_details;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		} else {
			$response['status']  = 0;
			$response['message'] = "Error";
			$response['data']    = [];
			echo json_encode($response);
			return;
		}
	}

	// Manage Items
	// ***************************************************
	public function manage_items($param1 = "", $param2 = "", $param3 = "")
	{
		$method = $this->input->post('method');

		$order_type     = $this->input->post('order_type');
		$salesagents_id = $this->input->post('salesagents_id');

		if ($method == '_listTypeWiseProduct') {
			$error = FALSE;
			$errors = array();
			$required = array('order_type');
			if ($order_type == 2) {
				array_push($required, 'salesagents_id');
			}
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
				$salesagents_val = '0';

				if ($salesagents_id != 0) {
					$salesagents_val = $salesagents_id;
				}

				$where = array(
					'item_type'      => $order_type,
					'salesagents_id' => $salesagents_val,
					'status'         => '1',
					'published'      => '1',
				);

				$column = 'id, name, category_id, hsn_code';

				$data_list = $this->commom_model->getProduct($where, '', '', 'result', '', '', '', '', $column);

				if ($data_list) {
					$product_list = [];

					foreach ($data_list as $key => $value) {

						$product_id   = isset($value->id) ? $value->id : '';
						$product_name = isset($value->name) ? $value->name : '';
						$hsn_code     = isset($value->hsn_code) ? $value->hsn_code : '';
						$gst_val      = isset($value->gst) ? $value->gst : '';

						$product_list[] = array(
							'product_id'   => $product_id,
							'product_name' => $product_name,
							'hsn_code'     => $hsn_code,
							'gst_val'      => $gst_val,
						);
					}

					$response['status']       = 1;
					$response['message']      = "Success";
					$response['data']         = $product_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		} else {
			$response['status']  = 0;
			$response['message'] = "Error";
			$response['data']    = [];
			echo json_encode($response);
			return;
		}
	}

	// Order Process
	// ***************************************************
	public function order_process($param1 = "", $param2 = "", $param3 = "")
	{
		$method                  = $this->input->post('method');
		$auto_id                 = $this->input->post('auto_id');
		$inv_value               = $this->input->post('inv_value');
		$zone_value              = $this->input->post('zone_value');
		$pre_status              = $this->input->post('pre_status');
		$vendor_id               = $this->input->post('vendor_id');
		$pre_status              = $this->input->post('pre_status');
		$distributor_id          = $this->input->post('distributor_id');
		$employee_id             = $this->input->post('employee_id');
		$submit_type             = $this->input->post('submit_type');
		$price                   = $this->input->post('price');
		$quantity                = $this->input->post('quantity');
		$progress                = $this->input->post('progress');
		$reason                  = $this->input->post('reason');
		$invoice_value           = $this->input->post('invoice_value');
		$discount                = $this->input->post('discount');
		$due_days                = $this->input->post('due_days');
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
		$transporter_doc_number  = $this->input->post('transporter_doc_number');
		$transporter_doc_date    = $this->input->post('transporter_doc_date');
		$vehicle_number          = $this->input->post('vehicle_number');
		$vehicle_type            = $this->input->post('vehicle_type');

		// Financial Year Details
		$option['order_by']   = 'id';
		$option['disp_order'] = 'DESC';

		$where = array(
			'status'    => '1',
			'published' => '1',
		);

		$data_list = $this->commom_model->getfinancial($where, '1', '0', 'result', '', '', $option);

		$financial_id = !empty($data_list[0]->id) ? $data_list[0]->id : '';

		$random_value = generateRandomString(32);

		// Admin Process Outlet Order
		if ($method == '_updateOrderProgress') {
			$error = FALSE;
			$errors = array();
			$required = array('pre_status', 'auto_id', 'progress');
			if ($progress == 8) {
				array_push($required, 'reason');
			}
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error == TRUE) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				if ($progress != $pre_status) {
					if ($progress == '1') {
						$update_data = array(
							'order_status' => $progress,
							'_ordered'     => date('Y-m-d H:i:s'),
						);

						// Order Details Update
						$upt_data = array(
							'order_status'    => $progress,
							'product_process' => $progress,
							'_ordered'        => date('Y-m-d H:i:s'),
						);

						$upt_whr = array('order_id' => $auto_id);
						$upt_det = $this->order_model->orderDetails_update($upt_data, $upt_whr);
					} else if ($progress == '2') {
						$update_data = array(
							'due_days'     => $due_days,
							'discount'     => $discount,
							'order_status' => $progress,
							'_processing'  => date('Y-m-d H:i:s'),
						);

						// Order Details Update
						$upt_data = array(
							'order_status'    => $progress,
							'product_process' => $progress,
							'_processing'     => date('Y-m-d H:i:s'),
						);

						$upt_whr = array('order_id' => $auto_id);
						$upt_det = $this->order_model->orderDetails_update($upt_data, $upt_whr);
					} else if ($progress == '7') {
						// Order process details
						$whr_one = array(
							'order_id'      => $auto_id,
							'delete_status' => '1',
							'published'     => '1',
							'status'        => '1',
						);

						$value_one = $this->order_model->getOrderDetails($whr_one, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

						$count_one = !empty($value_one[0]->autoid) ? $value_one[0]->autoid : '0';

						$whr_two = array(
							'order_id'        => $auto_id,
							'invoice_process' => '2',
							'delete_status'   => '1',
							'published'       => '1',
							'status'          => '1',
						);

						$value_two = $this->order_model->getOrderDetails($whr_two, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

						$count_two = !empty($value_two[0]->autoid) ? $value_two[0]->autoid : '0';

						if ($count_one == $count_two) {
							$update_data = array(
								'order_status' => $progress,
								'_complete'    => date('Y-m-d H:i:s'),
							);
						} else {
							$response['status']  = 0;
							$response['message'] = "Please fill order product";
							$response['data']    = [];
							echo json_encode($response);
							return;
						}
					} else if ($progress == '8') {
						$update_data = array(
							'order_status' => $progress,
							'reason'       => $reason,
							'_canceled'    => date('Y-m-d H:i:s'),
						);

						// Order Details Update
						$upt_data = array(
							'order_status' => $progress,
							'_canceled'    => date('Y-m-d H:i:s'),
						);

						$upt_whr = array('order_id' => $auto_id);
						$upt_det = $this->order_model->orderDetails_update($upt_data, $upt_whr);
					}

					$where  = array('id' => $auto_id);

					$update = $this->order_model->order_update($update_data, $where);

					if ($update) {
						$response['status']  = 1;
						$response['message'] = "Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					} else {
						$response['status']  = 0;
						$response['message'] = "Not Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				} else {
					$response['status']  = 0;
					$response['message'] = "Invalid Status";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		}

		// Vendor Change Order Details
		else if ($method == '_updateVendorOrderProgress') {
			$error = FALSE;
			$errors = array();
			$required = array('auto_id', 'vendor_id', 'progress', 'submit_type');
			if ($submit_type == 1) {
				array_push($required, 'employee_id', 'invoice_value');
			}
			if ($progress == 8) {
				array_push($required, 'reason');
			}
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error == TRUE) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				// Packing Process
				if ($progress == '3') {
					$update_data = array(
						'order_status'    => $progress,
						'product_process' => $progress,
						'_packing'        => date('Y-m-d H:i:s'),
					);
				}

				// Shiping Process
				else if ($progress == '4') {
					// Order process details
					$whr_one = array(
						'order_id'  => $auto_id,
						'vendor_id' => $vendor_id,
						'published' => '1',
						'status'    => '1',
					);

					$value_one = $this->order_model->getOrderDetails($whr_one, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

					$count_one = !empty($value_one[0]->autoid) ? $value_one[0]->autoid : '0';

					$whr_two = array(
						'order_id'          => $auto_id,
						'vendor_id'         => $vendor_id,
						'production_status' => '2',
						'published'         => '1',
						'status'            => '1',
					);

					$value_two = $this->order_model->getOrderDetails($whr_two, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id) AS autoid');

					$count_two = !empty($value_two[0]->autoid) ? $value_two[0]->autoid : '0';

					if ($count_one == $count_two) {
						$update_data = array(
							'order_status'    => $progress,
							'product_process' => $progress,
							'_shiping'        => date('Y-m-d H:i:s'),
						);
					} else {
						$response['status']  = 0;
						$response['message'] = "Please fill order quantity";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				}

				// Invoice Process
				else if ($progress == '5') {
					// Order Details
					$where_1 = array(
						'order_id'  => $auto_id,
						'vendor_id' => $vendor_id,
						'published' => '1',
						'status'    => '1',
					);

					$order_data = $this->order_model->getOrderDetails($where_1);

					if (!empty($order_data)) {
						$total_val = 0;
						foreach ($order_data as $key => $value_1) {
							$product_id = !empty($value_1->product_id) ? $value_1->product_id : '';
							$type_id    = !empty($value_1->type_id) ? $value_1->type_id : '';
							$order_qty  = !empty($value_1->order_qty) ? $value_1->order_qty : '0';
							$unit_val   = !empty($value_1->unit_val) ? $value_1->unit_val : '';
							$price_val  = !empty($value_1->price) ? $value_1->price : '0';
							$total_amt  = $order_qty * $price_val;
							$total_val += $total_amt;
						}

						// Bill Details
						$where_2 = array(
							'id'        => $auto_id,
							'published' => '1',
							'status'    => '1',
						);

						$column_2 = 'order_no, store_id, store_name, discount';

						$bill_data = $this->order_model->getOrder($where_2, '', '', 'result', '', '', '', '', $column_2);

						$order_no = !empty($bill_data[0]->order_no) ? $bill_data[0]->order_no : '';
						$store_id = !empty($bill_data[0]->store_id) ? $bill_data[0]->store_id : '';
						$str_name = !empty($bill_data[0]->store_name) ? $bill_data[0]->store_name : '';
						$discount = !empty($bill_data[0]->discount) ? $bill_data[0]->discount : '0';

						$bill_amount = 0;
						$bill_value  = round($total_val);
						if ($discount != 0) {
							$amount_val  = round($total_val);
							$bill_amount = $amount_val * $discount / 100;
							$bill_value  = round($bill_amount);
						}

						// Distributor Details
						$where_3 = array(
							'vendor_id' => $vendor_id,
							'status'    => '1',
							'published' => '1',
						);

						$column_3 = 'id,ref_id';

						$dis_data = $this->distributors_model->getDistributors($where_3, '', '', 'result', '', '', '', '', $column_3);

						$dis_id   = !empty($dis_data[0]->id) ? $dis_data[0]->id : '';
						$ref_id   = !empty($dis_data[0]->ref_id) ? $dis_data[0]->ref_id : '';

						// Outlet Details
						$where_4 = array(
							'id'        => $store_id,
							'published' => '1',
							'status'    => '1',
						);

						$column_4 = 'available_limit, current_balance';

						$out_data = $this->outlets_model->getOutlets($where_4, '', '', 'result', '', '', '', '', $column_4);

						$available_limit = !empty($out_data[0]->available_limit) ? $out_data[0]->available_limit : '0';
						$current_balance = !empty($out_data[0]->current_balance) ? $out_data[0]->current_balance : '0';

						if ($available_limit >= $bill_value) {
							$invoice_whr = array(
								'vendor_id' => $vendor_id,
								'published' => '1',
								'status'    => '1',
							);

							$invoice_val = $this->invoice_model->getInvoice($invoice_whr, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id)+1 AS autoid');

							$count_val   = leadingZeros($invoice_val[0]->autoid, 5);
							$invoice_num = 'INVOICE' . $count_val;

							// Order Data
							$where_5 = array(
								'id'        => $auto_id,
								'published' => '1',
								'status'    => '1',
							);

							$column_5  = 'id, bill_type, order_no, order_type, emp_id, emp_name, emp_type, store_id, store_name, contact_name, zone_id, due_days, discount, outlet_type, order_status';

							$order_val = $this->order_model->getOrder($where_5, '', '', 'result', '', '', '', '', $column_5);

							$bill_type    = !empty($order_val[0]->bill_type) ? $order_val[0]->bill_type : '';
							$order_no     = !empty($order_val[0]->order_no) ? $order_val[0]->order_no : '';
							$order_type   = !empty($order_val[0]->order_type) ? $order_val[0]->order_type : '';
							$emp_id       = !empty($order_val[0]->emp_id) ? $order_val[0]->emp_id : '';
							$store_id     = !empty($order_val[0]->store_id) ? $order_val[0]->store_id : '';
							$store_name   = !empty($order_val[0]->store_name) ? $order_val[0]->store_name : '';
							$contact_name = !empty($order_val[0]->contact_name) ? $order_val[0]->contact_name : '';
							$zone_id      = !empty($order_val[0]->zone_id) ? $order_val[0]->zone_id : '';
							$due_days     = !empty($order_val[0]->due_days) ? $order_val[0]->due_days : '0';
							$discount     = !empty($order_val[0]->discount) ? $order_val[0]->discount : '0';
							$outlet_type  = !empty($order_val[0]->outlet_type) ? $order_val[0]->outlet_type : '0';

							$invoice_data = array(
								'ref_id'         => $ref_id,
								'bill_type'      => $bill_type,
								'order_id'       => $auto_id,
								'invoice_no'     => $invoice_num,
								'order_type'     => $order_type,
								'vendor_id'      => $vendor_id,
								'sales_employee' => $emp_id,
								'store_id'       => $store_id,
								'store_name'     => $store_name,
								'contact_name'   => $contact_name,
								'zone_id'        => $zone_id,
								'due_days'       => $due_days,
								'discount'       => $discount,
								'outlet_type'    => $outlet_type,
								'financial_year' => $financial_id,
								'random_value'   => $random_value,
								'date'           => date('Y-m-d'),
								'time'           => date('H:i:s'),
								'createdate'     => date('Y-m-d H:i:s'),
							);

							$inv_insert = $this->invoice_model->invoice_insert($invoice_data);

							foreach ($order_data as $key => $value) {

								$order_auto_id = !empty($value->id) ? $value->id : '';
								$order_id      = !empty($value->order_id) ? $value->order_id : '';
								$zone_id       = !empty($value->zone_id) ? $value->zone_id : '';
								$product_id    = !empty($value->product_id) ? $value->product_id : '';
								$type_id       = !empty($value->type_id) ? $value->type_id : '';
								$vendor_id     = !empty($value->vendor_id) ? $value->vendor_id : '';
								$vendor_type   = !empty($value->vendor_type) ? $value->vendor_type : '';
								$hsn_code      = !empty($value->hsn_code) ? $value->hsn_code : '';
								$gst_val       = !empty($value->gst_val) ? $value->gst_val : '';
								$unit_val      = !empty($value->unit_val) ? $value->unit_val : '';
								$price         = !empty($value->price) ? $value->price : '';
								$order_qty     = !empty($value->order_qty) ? $value->order_qty : '';
								$receive_qty   = !empty($value->receive_qty) ? $value->receive_qty : '';

								$product_data = array(
									'ref_id'         => $ref_id,
									'order_id'      => $order_id,
									'order_auto_id' => $order_auto_id,
									'invoice_id'    => $inv_insert,
									'invoice_no'    => $invoice_num,
									'zone_id'       => $zone_id,
									'product_id'    => $product_id,
									'type_id'       => $type_id,
									'vendor_id'     => $vendor_id,
									'vendor_type'   => $vendor_type,
									'hsn_code'      => $hsn_code,
									'gst_val'       => $gst_val,
									'unit_val'      => $unit_val,
									'price'         => $price,
									'order_qty'     => $order_qty,
									'receive_qty'   => $receive_qty,
									'createdate'    => date('Y-m-d H:i:s'),
								);

								$ord_details = $this->invoice_model->invoiceDetails_insert($product_data);
							}

							// Order Bill Update
							$bill_data = array(
								'invoice_value' => $random_value,
							);

							$bill_whr  = array(
								'order_id'  => $auto_id,
								'vendor_id' => $vendor_id,
								'published' => '1',
							);

							$bill_upt = $this->order_model->orderDetails_update($bill_data, $bill_whr);

							// Outlet Payment Process
							// ***************************************
							$where_6 = array(
								'distributor_id' => $dis_id,
								'outlet_id'      => $store_id,
								'published'      => '1',
								'status'         => '1',
							);

							$outlet_list = $this->distributors_model->getDistributorOutlet($where_6);

							if (!empty($outlet_list)) {
								$assign_id = !empty($outlet_list[0]->id) ? $outlet_list[0]->id : '';
								$pre_bal   = !empty($outlet_list[0]->pre_bal) ? $outlet_list[0]->pre_bal : '0';
								$cur_bal   = !empty($outlet_list[0]->cur_bal) ? $outlet_list[0]->cur_bal : '0';
								$new_bal   = $cur_bal + $bill_value;

								$outlet_data = array(
									'pre_bal' => $cur_bal,
									'cur_bal' => $new_bal,
								);

								$outlet_whr = array('id' => $assign_id);
								$bal_update = $this->distributors_model->distributorOutlet_update($outlet_data, $outlet_whr);

								$new_avl_bal = $available_limit - $bill_value;
								$new_cur_bal = $current_balance + $bill_value;

								$balance_val = array(
									'available_limit' => $new_avl_bal,
									'current_balance' => $new_cur_bal,
								);

								$store_whr = array('id' => $store_id);
								$store_upt = $this->outlets_model->outlets_update($balance_val, $store_whr);

								// Outlet wise balance sheet
								$balance_data = array(
									'ref_id'         => $ref_id,
									'assign_id'       => $assign_id,
									'distributor_id'  => $dis_id,
									'outlet_id'       => $store_id,
									'bill_code'       => 'INV',
									'bill_id'         => $inv_insert,
									'bill_no'         => $invoice_num,
									'pre_bal'         => '0',
									'cur_bal'         => $bill_value,
									'amount'          => round($total_val),
									'bal_amt'         => round($total_val),
									'pay_type'        => '4',
									'date'            => date('Y-m-d'),
									'time'            => date('H:i:s'),
									'createdate'      => date('Y-m-d H:i:s'),
								);

								$balance_insert = $this->payment_model->outletPaymentDetails_insert($balance_data);

								// Balance Sheet
								$ins_data = array(
									'ref_id'         => $ref_id,
									'assign_id'       => $assign_id,
									'distributor_id'  => $dis_id,
									'outlet_id'       => $store_id,
									'bill_code'       => 'INV',
									'bill_id'         => $inv_insert,
									'bill_no'         => $invoice_num,
									'available_limit' => $available_limit,
									'pre_bal'         => $current_balance,
									'cur_bal'         => $new_cur_bal,
									'amount'          => round($total_val),
									'discount'        => $bill_amount,
									'value_type'      => 1,
									'date'            => date('Y-m-d'),
									'time'            => date('H:i:s'),
									'createdate'      => date('Y-m-d H:i:s'),
								);

								$payment_insert = $this->payment_model->outletPayment_insert($ins_data);
							} else {
								$outlet_data = array(
									'distributor_id' => $dis_id,
									'outlet_id'      => $store_id,
									'outlet_name'    => $str_name,
									'pre_bal'        => '0',
									'cur_bal'        => $bill_value,
									'createdate'     => date('Y-m-d H:i:s'),
								);

								$insert = $this->distributors_model->distributorOutlet_insert($outlet_data);

								$new_avl_bal = $available_limit - $bill_value;
								$new_cur_bal = $current_balance + $bill_value;

								$balance_val = array(
									'available_limit' => $new_avl_bal,
									'current_balance' => $new_cur_bal,
								);

								$store_whr = array('id' => $store_id);
								$store_upt = $this->outlets_model->outlets_update($balance_val, $store_whr);

								// Outlet wise balance sheet
								$balance_data = array(
									'ref_id'         => $ref_id,
									'assign_id'       => $insert,
									'distributor_id'  => $dis_id,
									'outlet_id'       => $store_id,
									'bill_code'       => 'INV',
									'bill_id'         => $inv_insert,
									'bill_no'         => $invoice_num,
									'pre_bal'         => '0',
									'cur_bal'         => $bill_value,
									'pay_type'        => '4',
									'date'            => date('Y-m-d'),
									'time'            => date('H:i:s'),
									'createdate'      => date('Y-m-d H:i:s'),
								);

								$balance_insert = $this->payment_model->outletPaymentDetails_insert($balance_data);

								// Balance Sheet
								$ins_data = array(
									'ref_id'         => $ref_id,
									'assign_id'       => $insert,
									'distributor_id'  => $dis_id,
									'outlet_id'       => $store_id,
									'bill_id'         => $inv_insert,
									'bill_no'         => $invoice_num,
									'available_limit' => $available_limit,
									'pre_bal'         => $current_balance,
									'cur_bal'         => $new_cur_bal,
									'amount'          => round($total_val),
									'discount'        => $bill_amount,
									'value_type'      => 1,
									'date'            => date('Y-m-d'),
									'time'            => date('H:i:s'),
									'createdate'      => date('Y-m-d H:i:s'),
								);

								$payment_insert = $this->payment_model->outletPayment_insert($ins_data);
							}

							// Order Process Update
							$update_data = array(
								'order_status'    => $progress,
								'product_process' => $progress,
								'_invoice'        => date('Y-m-d H:i:s'),
							);

							// ***************************************
						} else {
							$response['status']  = 0;
							$response['message'] = "Invalid Balance Value";
							$response['data']    = [];
							echo json_encode($response);
							return;
						}
					} else {
						$response['status']  = 0;
						$response['message'] = "Not Found";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				}

				// Delivery Process
				else if ($progress == '6') {
					$update_data = array(
						'order_status'    => $progress,
						'product_process' => $progress,
						'_delivery'       => date('Y-m-d H:i:s'),
					);

					// Update Invoice Table
					if (!empty($employee_id)) {
						// Invoice Details
						$inv_whr = array(
							'random_value' => $invoice_value,
							'published'    => '1',
							'status'       => '1',
						);

						$invoice_data = $this->invoice_model->getInvoice($inv_whr);
						$inv_data     = $invoice_data[0];
						$invoice_id   = !empty($inv_data->id) ? $inv_data->id : '';

						$upt_data = array('delivery_employee' => $employee_id);
						$upt_wht  = array('id' => $invoice_id);
						$upt_inv  = $this->invoice_model->invoice_update($upt_data, $upt_wht);
					}
				}

				$where  = array(
					'order_id'  => $auto_id,
					'vendor_id' => $vendor_id,
					'published' => '1',
				);

				$update = $this->order_model->orderDetails_update($update_data, $where);

				if ($update) {
					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		}

		// Distributor Change Order Details
		else if ($method == '_updateDistributorOrderProgress') {
			$error = FALSE;
			$errors = array();
			$required = array('auto_id', 'pre_status', 'distributor_id', 'progress');
			if ($progress == 8) {
				array_push($required, 'reason');
			} 
			else if ($progress == 9) {
				array_push($required, 'reason');
			}
			else if($progress == 10)
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
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error == TRUE) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				if ($pre_status != $progress) {
					// Packing Process
					if ($progress == '3') {
						$update_data = array(
							'order_status'    => $progress,
							'product_process' => $progress,
							'_packing'        => date('Y-m-d H:i:s'),
							'distributor_id'  => $distributor_id,
						);
					}

					// Ready to shipping Process
					else if ($progress == '4') {
						// Distributor Product Details
						$column_1 = 'type_id';

						$where_1  = array(
							'id'  => $distributor_id,
						);

						$data_1   = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column_1);

						$result_1 = $data_1[0];
						$type_id  = !empty($result_1->type_id) ? $result_1->type_id : '';

						$whr_one = array(
							'tbl_order_details.order_id'        => $auto_id,
							'tbl_order_details.delete_status'   => '1',
							'tbl_order_details.published'       => '1',
							'tbl_order_details.type_id'         => $type_id,
							'tbl_order_details.vendor_type != ' => '1'
						);

						$col_one = 'tbl_order_details.id, tbl_order_details.type_id';

						$res_one  = $this->order_model->getDistributorOrder($whr_one, '', '', 'result', '', '', '', '', $col_one);

						$count_one = 0;
						if (!empty($res_one)) {
							foreach ($res_one as $key => $val) {

								$ord_value  = !empty($val->id) ? $val->id : '';
								$type_value = !empty($val->type_id) ? $val->type_id : '';

								$where_4 = array(
									'distributor_id' => $distributor_id,
									'type_id'        => $type_value,
									'zone_id'        => $zone_value,
									'status'         => '1',
									'published'      => '1'
								);

								$column_4 = 'id';

								$assign_data = $this->assignproduct_model->getAssignProductDetails($where_4, '', '', 'result', '', '', '', '', $column_4);

								if (!empty($assign_data)) {
									$count_one++;
								}
							}
						}

						$whr_two = array(
							'tbl_order_details.order_id'          => $auto_id,
							'tbl_order_details.delete_status'     => '1',
							'tbl_order_details.published'         => '1',
							'tbl_order_details.production_status' => '2',
							'tbl_order_details.type_id'           => $type_id,
							'tbl_order_details.vendor_type != '   => '1'
						);

						$col_two = 'tbl_order_details.id, tbl_order_details.type_id';

						$res_two  = $this->order_model->getDistributorOrder($whr_two, '', '', 'result', '', '', '', '', $col_two);

						$count_two = 0;
						if (!empty($res_two)) {
							foreach ($res_two as $key => $val) {

								$ord_value  = !empty($val->id) ? $val->id : '';
								$type_value = !empty($val->type_id) ? $val->type_id : '';

								$where_4 = array(
									'distributor_id' => $distributor_id,
									'type_id'        => $type_value,
									'zone_id'        => $zone_value,
									'status'         => '1',
									'published'      => '1'
								);

								$column_4 = 'id';

								$assign_data = $this->assignproduct_model->getAssignProductDetails($where_4, '', '', 'result', '', '', '', '', $column_4);

								if (!empty($assign_data)) {
									$count_two++;
								}
							}
						}

						if ($count_one == $count_two) {
							$update_data = array(
								'order_status'    => $progress,
								'product_process' => $progress,
								'_ready'          => date('Y-m-d H:i:s'),
								'distributor_id'  => $distributor_id,
							);
						} else {
							$response['status']  = 0;
							$response['message'] = "Please fill order quantity";
							$response['data']    = [];
							echo json_encode($response);
							return;
						}
					}

					// Invoice Process
					else if ($progress == '5') {

						// Distributor Product Details
						$column_1 = 'id, bill_no, type_id,ref_id';

						$where_1  = array(
							'id'  => $distributor_id,
						);

						$data_1   = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column_1);

						$result_1 = $data_1[0];
						$dis_id   = !empty($result_1->id) ? $result_1->id : '';
						$bill_no  = !empty($result_1->bill_no) ? $result_1->bill_no : 'INV';
						$type_id  = !empty($result_1->type_id) ? $result_1->type_id : '';
						$ref_id  = !empty($result_1->ref_id) ? $result_1->ref_id : '';
						$whr_one = array(
							'tbl_order_details.order_id'        => $auto_id,
							'tbl_order_details.delete_status'   => '1',
							'tbl_order_details.published'       => '1',
							'tbl_order_details.type_id'         => $type_id,
							'tbl_order_details.vendor_type != ' => '1'
						);

						$col_one = 'tbl_order_details.id, tbl_order_details.type_id';

						$res_one  = $this->order_model->getDistributorOrder($whr_one, '', '', 'result', '', '', '', '', $col_one);

						$order_value = '';
						if (!empty($res_one)) {
							foreach ($res_one as $key => $val) {

								$ord_value  = !empty($val->id) ? $val->id : '';
								$type_value = !empty($val->type_id) ? $val->type_id : '';

								$where_4 = array(
									'distributor_id' => $distributor_id,
									'type_id'        => $type_value,
									'zone_id'        => $zone_value,
									'status'         => '1',
									'published'      => '1'
								);

								$column_4 = 'id';

								$assign_data = $this->assignproduct_model->getAssignProductDetails($where_4, '', '', 'result', '', '', '', '', $column_4);

								if (!empty($assign_data)) {
									$order_value .= $ord_value . ',';
								}
							}
						}

						$order_list = substr_replace($order_value, '', -1);

						if (!empty($order_list)) {
							$where = array(
								'tbl_order_details.id'            => $order_list,
								'tbl_order_details.delete_status' => '1',
								'tbl_order_details.published'     => '1',
							);

							$column = 'tbl_order_details.id, tbl_order_details.order_id, tbl_order_details.order_no, tbl_order_details.zone_id, tbl_order_details.product_id, tbl_order_details.type_id, tbl_order_details.vendor_id, tbl_order_details.vendor_type, tbl_order_details.hsn_code, tbl_order_details.gst_val, tbl_order_details.unit_val, tbl_order_details.price, tbl_order_details.order_qty, tbl_order_details.receive_qty, tbl_order_details._ordered';

							$overalldatas = $this->order_model->getDistributorOrder($where, '', '', 'result', '', '', '', '', $column);

							if (!empty($overalldatas)) {
								$total_val = 0;
								foreach ($overalldatas as $key => $val_1) {
									$product_id = !empty($val_1->product_id) ? $val_1->product_id : '';
									$type_id    = !empty($val_1->type_id) ? $val_1->type_id : '';
									$order_qty  = !empty($val_1->order_qty) ? $val_1->order_qty : '0';
									$unit_val   = !empty($val_1->unit_val) ? $val_1->unit_val : '';
									$price_val  = !empty($val_1->price) ? $val_1->price : '0';
									$total_amt  = $order_qty * $price_val;
									$total_val += $total_amt;
								}

								// Bill Details
								$where_2 = array(
									'id'        => $auto_id,
									'published' => '1',
									'status'    => '1',
								);

								$column_2 = 'order_no, store_id, store_name, discount';

								$bill_data = $this->order_model->getOrder($where_2, '', '', 'result', '', '', '', '', $column_2);

								$order_no = !empty($bill_data[0]->order_no) ? $bill_data[0]->order_no : '';
								$store_id = !empty($bill_data[0]->store_id) ? $bill_data[0]->store_id : '';
								$str_name = !empty($bill_data[0]->store_name) ? $bill_data[0]->store_name : '';
								$discount = !empty($bill_data[0]->discount) ? $bill_data[0]->discount : '0';

								// Get discount details
								$bill_whr = array(
									'store_id'         => $store_id,
									'financial_year'   => $financial_id,
									'cancel_status !=' => '2',
									'published'        => '1',
									'status'           => '1',
								);

								$bill_val = $this->invoice_model->getInvoice($bill_whr, '', '', "row", array(), array(), array(), TRUE, 'COUNT(id)+1 AS autoid');

								$strDiscount_whr = array(
									'outlet_id'        => $store_id,
									'inv_count'        => zero_check($bill_val->autoid),
									'published'        => '1',
									'status'           => '1',
								);

								$strDiscount_val = $this->loyalty_model->getOutletLoyalty($strDiscount_whr, '', '', "row", array(), array(), array(), TRUE, 'dis_value');

								$strDiscount_res = 0;
								if ($strDiscount_val) {
									$strDiscount_res = zero_check($strDiscount_val->dis_value);
								}

								if ($strDiscount_res != 0) {
									$amount_val   = round($total_val);
									$bill_amount  = $amount_val * $strDiscount_res / 100;
									$dis_amount   = $amount_val - $bill_amount;
									$bill_value   = round($dis_amount);
									$discount_val = $strDiscount_res;
								} else if ($discount != 0) {
									$amount_val   = round($total_val);
									$bill_amount  = $amount_val * $discount / 100;
									$dis_amount   = $amount_val - $bill_amount;
									$bill_value   = round($dis_amount);
									$discount_val = $discount;
								} else {
									$bill_amount  = 0;
									$bill_value   = round($total_val);
									$discount_val = 0;
								}

								// Outlet Details
								$where_3 = array(
									'id'        => $store_id,
									'published' => '1',
									'status'    => '1',
								);

								$column_3 = 'available_limit, current_balance, state_id, city_id';

								$out_data = $this->outlets_model->getOutlets($where_3, '', '', 'result', '', '', '', '', $column_3);

								$available_limit = !empty($out_data[0]->available_limit) ? $out_data[0]->available_limit : '0';
								$current_balance = !empty($out_data[0]->current_balance) ? $out_data[0]->current_balance : '0';
								$state_id        = !empty($out_data[0]->state_id) ? $out_data[0]->state_id : '0';
								$city_id         = !empty($out_data[0]->city_id) ? $out_data[0]->city_id : '0';

								if ($available_limit >= $bill_value) {
									if ($distributor_id == '25' && $financial_id == '3') {
										$invoice_whr = array(
											'distributor_id' => $distributor_id,
											'financial_year' => $financial_id,
											'published'      => '1',
											'status'         => '1',
										);

										$invoice_val = $this->invoice_model->getInvoice($invoice_whr, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id)+1 AS autoid');

										$count_res = 156 + $invoice_val[0]->autoid;
										$count_val = leadingZeros($count_res, 5);
									} else {
										$invoice_whr = array(
											'distributor_id' => $distributor_id,
											'financial_year' => $financial_id,
											'published'      => '1',
											'status'         => '1',
										);

										$invoice_val = $this->invoice_model->getInvoice($invoice_whr, '', '', "result", array(), array(), array(), TRUE, 'COUNT(id)+1 AS autoid');

										$count_val   = leadingZeros($invoice_val[0]->autoid, 5);
									}


									$invoice_num = $bill_no . $count_val;


									// Order Data
									$where_4 = array(
										'id'        => $auto_id,
										'published' => '1',
										'status'    => '1',
									);

									$column_4  = 'id, bill_type, order_no, order_type, emp_id, emp_name, emp_type, store_id, store_name, contact_name, zone_id, due_days, discount, outlet_type, order_status';

									$order_val = $this->order_model->getOrder($where_4, '', '', 'result', '', '', '', '', $column_4);

									$bill_type    = !empty($order_val[0]->bill_type) ? $order_val[0]->bill_type : '';
									$order_no     = !empty($order_val[0]->order_no) ? $order_val[0]->order_no : '';
									$order_type   = !empty($order_val[0]->order_type) ? $order_val[0]->order_type : '';
									$emp_id       = !empty($order_val[0]->emp_id) ? $order_val[0]->emp_id : '';
									$store_id     = !empty($order_val[0]->store_id) ? $order_val[0]->store_id : '';
									$store_name   = !empty($order_val[0]->store_name) ? $order_val[0]->store_name : '';
									$contact_name = !empty($order_val[0]->contact_name) ? $order_val[0]->contact_name : '';
									$zone_id      = !empty($order_val[0]->zone_id) ? $order_val[0]->zone_id : '';
									$due_days     = !empty($order_val[0]->due_days) ? $order_val[0]->due_days : '0';
									$discount     = !empty($order_val[0]->discount) ? $order_val[0]->discount : '0';
									$outlet_type  = !empty($order_val[0]->outlet_type) ? $order_val[0]->outlet_type : '0';

									$invoice_data = array(
										'ref_id'         => $ref_id,
										'bill_type'      => $bill_type,
										'order_id'       => $auto_id,
										'invoice_no'     => $invoice_num,
										'order_type'     => $order_type,
										'distributor_id' => $distributor_id,
										'sales_employee' => $emp_id,
										'store_id'       => $store_id,
										'store_name'     => $store_name,
										'contact_name'   => $contact_name,
										'state_id'       => $state_id,
										'city_id'        => $city_id,
										'zone_id'        => $zone_id,
										'due_days'       => $due_days,
										'discount'       => $discount_val,
										'outlet_type'    => $outlet_type,
										'financial_year' => $financial_id,
										'random_value'   => $random_value,
										'date'           => date('Y-m-d'),
										'time'           => date('H:i:s'),
										'createdate'     => date('Y-m-d H:i:s'),
									);

									$inv_insert = $this->invoice_model->invoice_insert($invoice_data);

									foreach ($overalldatas as $key => $val_2) {
										$order_auto_id = !empty($val_2->id) ? $val_2->id : '';
										$order_id      = !empty($val_2->order_id) ? $val_2->order_id : '';
										$zone_id       = !empty($val_2->zone_id) ? $val_2->zone_id : '';
										$product_id    = !empty($val_2->product_id) ? $val_2->product_id : '';
										$type_id       = !empty($val_2->type_id) ? $val_2->type_id : '';
										$vendor_id     = !empty($val_2->vendor_id) ? $val_2->vendor_id : '';
										$vendor_type   = !empty($val_2->vendor_type) ? $val_2->vendor_type : '';
										$hsn_code      = !empty($val_2->hsn_code) ? $val_2->hsn_code : '';
										$gst_val       = !empty($val_2->gst_val) ? $val_2->gst_val : '';
										$unit_val      = !empty($val_2->unit_val) ? $val_2->unit_val : '';
										$price         = !empty($val_2->price) ? $val_2->price : '';
										$order_qty     = !empty($val_2->order_qty) ? $val_2->order_qty : '';
										$receive_qty   = !empty($val_2->receive_qty) ? $val_2->receive_qty : '';

										$product_data = array(
											'ref_id'         => $ref_id,
											'order_id'       => $order_id,
											'order_auto_id'  => $order_auto_id,
											'invoice_id'     => $inv_insert,
											'invoice_no'     => $invoice_num,
											'distributor_id' => $distributor_id,
											'store_id'       => $store_id,
											'zone_id'        => $zone_id,
											'product_id'     => $product_id,
											'type_id'        => $type_id,
											'vendor_id'      => $vendor_id,
											'vendor_type'    => $vendor_type,
											'hsn_code'       => $hsn_code,
											'gst_val'        => $gst_val,
											'unit_val'       => $unit_val,
											'price'          => $price,
											'order_qty'      => $order_qty,
											'receive_qty'    => $receive_qty,
											'createdate'     => date('Y-m-d H:i:s'),
										);

										$ord_details = $this->invoice_model->invoiceDetails_insert($product_data);


										if (!empty($type_id)) {
											$cur_month = date('m');
											$cur_year  = date('Y');

											$dateObj   = DateTime::createFromFormat('!m', $cur_month);
											$monthName = $dateObj->format('F'); // March

											$pdtTar_whr = array(
												'emp_id'      => $emp_id,
												'type_id'     => $type_id,
												'month_name'  => $monthName,
												'year_name'   => $cur_year,
												'published'   => '1',
											);

											$pdtTar_col = 'id, achieve_val';
											$pdtTar_val = $this->target_model->getProductTargetDetails($pdtTar_whr, '', '', 'result', '', '', '', '', $pdtTar_col);

											if (!empty($pdtTar_val)) {
												$price_Val  = $price * $order_qty;
												if ($strDiscount_res != 0) {
													$amount_val   = round($price_Val);
													$bill_amount  = $amount_val * $strDiscount_res / 100;
													$dis_amount   = $amount_val - $bill_amount;
													$bill_value   = round($dis_amount);
													$discount_val = $strDiscount_res;
												} else if ($discount != 0) {
													$pdtAmt_val   = round($price_Val);
													$pdtBill_amt  = $pdtAmt_val * $discount / 100;
													$pdtDis_amt   = $pdtAmt_val - $pdtBill_amt;
													$pdtBill_val  = round($pdtDis_amt);
												} else {
													$pdtBill_amt = 0;
													$pdtBill_val = round($price_Val);
												}

												$pdtTar_id  = !empty($pdtTar_val[0]->id) ? $pdtTar_val[0]->id : '0';
												$pdtAch_val = !empty($pdtTar_val[0]->achieve_val) ? $pdtTar_val[0]->achieve_val : '0';

												$new_pdtAch = $pdtAch_val + $pdtBill_val;

												$pdtTar_data = array(
													'achieve_val' => $new_pdtAch,
													'updatedate'  => date('Y-m-d H:i:s')
												);

												$pdtTar_res = array('id' => $pdtTar_id);
												$pdtTar_upt = $this->target_model->ProductTargetDetails_update($pdtTar_data, $pdtTar_res);
											}
										}
									}

									// Target Upload
									if (!empty($emp_id)) {
										// Main target details
										$cur_month = date('m');
										$cur_year  = date('Y');

										$dateObj   = DateTime::createFromFormat('!m', $cur_month);
										$monthName = $dateObj->format('F'); // March

										$tar_whr = array(
											'employee_id' => $emp_id,
											'month_name'  => $monthName,
											'year_name'   => $cur_year,
											'published'   => '1',
										);

										$tar_col = 'id, achieve_val';
										$tar_val = $this->target_model->getTargetDetails($tar_whr, '', '', 'result', '', '', '', '', $tar_col);

										if (!empty($tar_val)) {
											$tar_id  = !empty($tar_val[0]->id) ? $tar_val[0]->id : '0';
											$ach_val = !empty($tar_val[0]->achieve_val) ? $tar_val[0]->achieve_val : '0';

											$new_ach = $ach_val + $bill_value;

											$tar_data = array(
												'achieve_val' => $new_ach,
												'updatedate'  => date('Y-m-d H:i:s')
											);

											$tar_res = array('id' => $tar_id);
											$tar_upt = $this->target_model->targetDetails_update($tar_data, $tar_res);
										}

										// //hierachy target update
										// $emp_hi=array(
										// 	'employee_id' => $emp_id,
										// 	'published'   => 1
										// );

										// $ctrl_zone_id = 'id, ctrl_state_id,ctrl_city_id,ctrl_zone_id';
										// $hi_val = $this->managers_model->getManagers($emp_hi, '', '', 'result', '', '', '', '', $ctrl_zone_id);

										// if (!empty($hi_val)) {
										// 	$ctrl_state_id  = !empty($hi_val[0]->ctrl_state_id) ? $hi_val[0]->ctrl_state_id : '0';
										// 	$ctrl_city_id = !empty($hi_val[0]->ctrl_city_id) ? $hi_val[0]->ctrl_city_id : '0';
										// 	$ctrl_city_id = !empty($hi_val[0]->ctrl_city_id) ? $hi_val[0]->ctrl_city_id : '0';

										// 	if(!empty($ctrl_state_id)){
										// 		$state_id_finall = substr($ctrl_state_id,1,-1);
						
		
										// 		$d_state = !empty($state_id_finall)?$state_id_finall:'';
										
										// 		$d_state_val = explode(',', $d_state);
										// 		$st_count = count($d_state_val);
										// 		$count_emp = [];
										// 		for( $i=0; $i < $st_count; $i++){
				
				
				
										// 			$whr_asm = array(
										// 				'position_id'  => 2, 
										// 				'published'      => '1'
										// 			);
										// 			$like['ctrl_state_id'] =','. $d_state_val[$i].',';
						
										// 			$co1_asm ='employee_id';
						
										// 			$asm_val = $this->managers_model->getAssignStateDetails($whr_asm, '', '', 'result', $like, '', '', '', $co1_asm);
										// 			if (!empty($asm_val)) {
										// 				$asm  = !empty($asm_val[0]->employee_id) ? $asm_val[0]->employee_id : '0';
										// 			}
										// 			$whr_rsm = array(
										// 				'position_id'  => 1, 
										// 				'published'      => '1'
										// 			);
										// 			$like['ctrl_state_id'] =','. $d_state_val[$i].',';
						
										// 			$co1_rsm ='employee_id';
						
										// 			$rsm_val = $this->managers_model->getAssignStateDetails($whr_rsm, '', '', 'result', $like, '', '', '', $co1_rsm);
										// 			if (!empty($rsm_val)) {
										// 				$rsm  = !empty($rsm_val[0]->employee_id) ? $rsm_val[0]->employee_id : '0';
										// 			}
													
										// 		}
										// 	}
										// 	if(!empty($ctrl_city_id)){
										// 		$city_id_finall = substr($ctrl_city_id,1,-1);
						
		
										// 		$d_city = !empty($city_id_finall)?$city_id_finall:'';
										
										// 		$d_city_val = explode(',', $d_city);
										// 		$ct_count = count($d_city_val);
										// 		$count_emp = [];
										// 		for( $i=0; $i < $st_count; $i++){
				
				
				
										// 			$whr_so = array(
										// 				'position_id'  => 2, 
										// 				'published'      => '1'
										// 			);
										// 			$like['ctrl_city_id'] =','. $d_city_val[$i].',';
						
										// 			$co1_so ='employee_id';
						
										// 			$so_val = $this->managers_model->getAssignStateDetails($whr_so, '', '', 'result', $like, '', '', '', $co1_so);
										// 			if (!empty($so_val)) {
										// 				$so  = !empty($so_val[0]->employee_id) ? $so_val[0]->employee_id : '0';
										// 			}
													
													
										// 		}
										// 	}
										// }

										// 	$new_ach = $ach_val + $bill_value;

										// 	$tar_data = array(
										// 		'achieve_val' => $new_ach,
										// 		'updatedate'  => date('Y-m-d H:i:s')
										// 	);

										// 	$tar_res = array('id' => $tar_id);
										// 	$tar_upt = $this->target_model->targetDetails_update($tar_data, $tar_res);
										// }

										// Beat wise target details
										$beatTar_whr = array(
											'emp_id'     => $emp_id,
											'zone_id'    => $zone_id,
											'month_name' => $monthName,
											'year_name'  => $cur_year,
											'published'  => '1',
										);

										$beatTar_col = 'id, achieve_val';
										$beatTar_val = $this->target_model->getBeatTargetDetails($beatTar_whr, '', '', 'result', '', '', '', '', $beatTar_col);

										if (!empty($beatTar_val)) {
											$beatTar_id  = !empty($beatTar_val[0]->id) ? $beatTar_val[0]->id : '0';
											$beatAch_val = !empty($beatTar_val[0]->achieve_val) ? $beatTar_val[0]->achieve_val : '0';

											$new_beatAch = $beatAch_val + $bill_value;

											$beatTar_data = array(
												'achieve_val' => $new_beatAch,
												'updatedate'  => date('Y-m-d H:i:s')
											);

											$beatTar_res = array('id' => $beatTar_id);
											$beatTar_upt = $this->target_model->beatTargetDetails_update($beatTar_data, $beatTar_res);
										}
									}

									// Outlet Payment Process
									// ***************************************
									$where_6 = array(
										'distributor_id' => $distributor_id,
										'outlet_id'      => $store_id,
										'published'      => '1',
										'status'         => '1',
									);

									$outlet_list = $this->distributors_model->getDistributorOutlet($where_6);

									if (!empty($outlet_list)) {
										$assign_id = !empty($outlet_list[0]->id) ? $outlet_list[0]->id : '';
										$pre_bal   = !empty($outlet_list[0]->pre_bal) ? $outlet_list[0]->pre_bal : '0';
										$cur_bal   = !empty($outlet_list[0]->cur_bal) ? $outlet_list[0]->cur_bal : '0';
										$new_bal   = $cur_bal + $bill_value;

										$outlet_data = array(
											'pre_bal' => $cur_bal,
											'cur_bal' => $new_bal,
										);

										$outlet_whr = array('id' => $assign_id);
										$bal_update = $this->distributors_model->distributorOutlet_update($outlet_data, $outlet_whr);

										$new_avl_bal = $available_limit - $bill_value;
										$new_cur_bal = $current_balance + $bill_value;

										$balance_val = array(
											'available_limit' => $new_avl_bal,
											'current_balance' => $new_cur_bal,
										);

										$store_whr = array('id' => $store_id);
										$store_upt = $this->outlets_model->outlets_update($balance_val, $store_whr);

										// Outlet wise balance sheet
										$balance_data = array(
											'ref_id'         => $ref_id,
											'assign_id'       => $assign_id,
											'distributor_id'  => $distributor_id,
											'outlet_id'       => $store_id,
											'bill_code'       => 'INV',
											'bill_id'         => $auto_id,
											'bill_no'         => $invoice_num,
											'pre_bal'         => '0',
											'cur_bal'         => round($bill_value),
											'amount'          => round($bill_value),
											'bal_amt'         => round($bill_value),
											'pay_type'        => '4',
											'financial_id'    => $financial_id,
											'date'            => date('Y-m-d'),
											'time'            => date('H:i:s'),
											'createdate'      => date('Y-m-d H:i:s'),
										);

										$balance_insert = $this->payment_model->outletPaymentDetails_insert($balance_data);

										// Balance Sheet
										$ins_data = array(
											'ref_id'         => $ref_id,
											'assign_id'       => $assign_id,
											'distributor_id'  => $distributor_id,
											'outlet_id'       => $store_id,
											'bill_id'         => $auto_id,
											'bill_no'         => $invoice_num,
											'bill_code'       => 'INV',
											'available_limit' => round($available_limit),
											'pre_bal'         => round($current_balance),
											'cur_bal'         => round($new_cur_bal),
											'amount'          => round($total_val),
											'discount'        => round($bill_amount),
											'value_type'      => 1,
											'financial_id'    => $financial_id,
											'date'            => date('Y-m-d'),
											'time'            => date('H:i:s'),
											'createdate'      => date('Y-m-d H:i:s'),
										);

										$payment_insert = $this->payment_model->outletPayment_insert($ins_data);
									} else {
										$outlet_data = array(
											'distributor_id' => $distributor_id,
											'outlet_id'      => $store_id,
											'outlet_name'    => $str_name,
											'pre_bal'        => '0',
											'cur_bal'        => $bill_value,
											'createdate'     => date('Y-m-d H:i:s'),
										);

										$insert = $this->distributors_model->distributorOutlet_insert($outlet_data);

										$new_avl_bal = $available_limit - $bill_value;
										$new_cur_bal = $current_balance + $bill_value;

										$balance_val = array(
											'available_limit' => $new_avl_bal,
											'current_balance' => $new_cur_bal,
										);

										$store_whr = array('id' => $store_id);
										$store_upt = $this->outlets_model->outlets_update($balance_val, $store_whr);

										// Outlet wise balance sheet
										$balance_data = array(
											'ref_id'         => $ref_id,
											'assign_id'       => $insert,
											'distributor_id'  => $distributor_id,
											'outlet_id'       => $store_id,
											'bill_code'       => 'INV',
											'bill_id'         => $auto_id,
											'bill_no'         => $invoice_num,
											'pre_bal'         => '0',
											'cur_bal'         => round($bill_value),
											'amount'          => round($bill_value),
											'bal_amt'         => round($bill_value),
											'pay_type'        => '4',
											'financial_id'    => $financial_id,
											'date'            => date('Y-m-d'),
											'time'            => date('H:i:s'),
											'createdate'      => date('Y-m-d H:i:s'),
										);

										$balance_insert = $this->payment_model->outletPaymentDetails_insert($balance_data);

										// Balance Sheet
										$ins_data = array(
											'ref_id'          => $ref_id,
											'assign_id'       => $insert,
											'distributor_id'  => $distributor_id,
											'outlet_id'       => $store_id,
											'bill_id'         => $auto_id,
											'bill_no'         => $invoice_num,
											'bill_code'       => 'INV',
											'available_limit' => round($available_limit),
											'pre_bal'         => round($current_balance),
											'cur_bal'         => round($new_cur_bal),
											'amount'          => round($total_val),
											'discount'        => round($bill_amount),
											'value_type'      => 1,
											'financial_id'    => $financial_id,
											'date'            => date('Y-m-d'),
											'time'            => date('H:i:s'),
											'createdate'      => date('Y-m-d H:i:s'),
										);

										$payment_insert = $this->payment_model->outletPayment_insert($ins_data);
									}

									// Order Process Update
									$update_data = array(
										'order_status'    => $progress,
										'product_process' => $progress,
										'invoice_num'     => $invoice_num,
										'invoice_value'   => $random_value,
										'_invoice'        => date('Y-m-d H:i:s'),
										'distributor_id'  => $distributor_id,
									);
								} else {
									$response['status']  = 0;
									$response['message'] = "Invalid Balance Value";
									$response['data']    = [];
									echo json_encode($response);
									return;
								}
							}
						} else {
							$response['status']  = 0;
							$response['message'] = "Data Not Found";
							$response['data']    = [];
							echo json_encode($response);
							return;
						}
					}

					// Shipping Process
					else if ($progress == '10') {

						if($e_inv_status == 1)
						{
							// Create E-invoice
							// ******************************************
							// Outlet details
							$whr_1 = array(
								'A.random_value' => $inv_value,
								'A.published'    => '1',
								'A.status'       => '1',
							);

							$col_1 = 'A.id, A.invoice_no, A.date AS inv_date, B.order_no, B.date AS order_date, C.gst_type, C.gst_no, C.company_name, C.address, C.pincode, C.mobile, C.email, D.id AS state_id, D.state_name, D.gst_code, E.city_name';
							$res_1 = $this->invoice_model->getOutletInvoiceJoin($whr_1, '', '', 'row', '', '', '', '', $col_1);

							$inv_no       = !empty($res_1->invoice_no)?$res_1->invoice_no:'';
						    $inv_date     = !empty($res_1->inv_date)?$res_1->inv_date:'';
						    $pur_no       = !empty($res_1->order_no)?$res_1->order_no:'';
						    $pur_date     = !empty($res_1->order_date)?$res_1->order_date:'';
						    $str_gst_type = !empty($res_1->gst_type)?$res_1->gst_type:'';
						    $str_gst_no   = !empty($res_1->gst_no)?$res_1->gst_no:'GSTIN';
						    $str_name     = !empty($res_1->company_name)?$res_1->company_name:'';
						    $str_adrs     = !empty($res_1->address)?$res_1->address:'';
						    $str_postal   = !empty($res_1->pincode)?$res_1->pincode:'';
						    $str_mobile   = !empty($res_1->mobile)?$res_1->mobile:'';
						    $str_email    = !empty($res_1->email)?$res_1->email:'';
						    $str_std_id   = !empty($res_1->state_id)?$res_1->state_id:'';
						    $str_state    = !empty($res_1->state_name)?$res_1->state_name:'';
						    $str_gst_cd   = !empty($res_1->gst_code)?$res_1->gst_code:'';
						    $str_city     = !empty($res_1->city_name)?$res_1->city_name:'None';
							
							// Distributor details
							$whr_2 = array(
								'A.id'        => $distributor_id,
								'A.published' => '1',
								'A.status'    => '1',
							);

							$col_2 = 'A.company_name, A.mobile, A.email, A.address, A.pincode, A.gst_no, B.country_code, B.state_name, B.gst_code, A.state_id, C.city_name, A.einv_status, A.access_token';
							$res_2 = $this->distributors_model->getDistributorsJoin($whr_2, '', '', 'row', '', '', '', '', $col_2);

							$dis_name   = !empty($res_2->company_name)?$res_2->company_name:'';
							$dis_mobile = !empty($res_2->mobile)?$res_2->mobile:'';
							$dis_email  = !empty($res_2->email)?$res_2->email:'';
							$dis_adrs   = !empty($res_2->address)?$res_2->address:'';
							$dis_postal = !empty($res_2->pincode)?$res_2->pincode:'';
							$dis_gst_no = !empty($res_2->gst_no)?$res_2->gst_no:'';
							$dis_cntry  = !empty($res_2->country_code)?$res_2->country_code:'';
							$dis_state  = !empty($res_2->state_name)?$res_2->state_name:'';
							$dis_gst_cd = !empty($res_2->gst_code)?$res_2->gst_code:'';
							$dis_std_id = !empty($res_2->state_id)?$res_2->state_id:'';
							$dis_city   = !empty($res_2->city_name)?$res_2->city_name:'None';
							$dis_einv   = !empty($res_2->einv_status)?$res_2->einv_status:'2';
							$dis_access = !empty($res_2->access_token)?$res_2->access_token:'';

							// Product details
							$whr_3 = array(
								'A.invoice_id' => ($res_1->id)?$res_1->id:'', 
								'A.published'  => '1',
								'A.status'     => '1'
							);

							$col_3 = 'B.product_code, C.description, A.hsn_code, A.gst_val, A.price, A.order_qty';
							$res_3 = $this->invoice_model->getOutletInvoiceDetJoin($whr_3, '', '', 'result', '', '', '', '', $col_3);

							$item_list  = array();
							$total_ass  = 0;
			            	$total_inv  = 0;
			            	$total_cgst = 0;
			            	$total_sgst = 0;
			            	$total_igst = 0;
							if($res_3)
							{
								foreach ($res_3 as $key => $val_3) {
									$pdt_code  = ($val_3->product_code)?$val_3->product_code:'';
									$pdt_name  = ($val_3->description)?$val_3->description:'';
									$hsn_code  = ($val_3->hsn_code)?$val_3->hsn_code:'0';
									$gst_val   = ($val_3->gst_val)?$val_3->gst_val:'0';
									$pdt_price = ($val_3->price)?$val_3->price:'0';
									$order_qty = ($val_3->order_qty)?$val_3->order_qty:'0';

									$gst_data    = $pdt_price - ($pdt_price * (100 / (100 + $gst_val)));
				                    $price_val   = $pdt_price - $gst_data;
				                    $tot_amt     = $order_qty * $price_val;
				                    $tot_gst     = $order_qty * $gst_data;
				                    $tot_val     = $order_qty * $pdt_price;
				                    $ass_val     = $tot_amt - 0;
				                    $total_ass  += $ass_val;
				            		$total_inv  += $tot_val;

				            		if($dis_std_id == $str_std_id)
					                {
					                	$gst_res     = $tot_gst / 2;
					                	$sgstRate    = (float)$gst_res;
					                	$cgstRate    = (float)$gst_res;
					                	$igstRate    = 0;
					                	$total_cgst += $sgstRate;
						            	$total_sgst += $cgstRate;
						            	$total_igst += 0;
					                }
					                else
					                {
					                	$sgstRate    = 0;
					                	$cgstRate    = 0;
					                	$igstRate    = (float)$tot_gst;
					                	$total_cgst += 0;
						            	$total_sgst += 0;
						            	$total_igst += $igstRate;
					                }

					                $item_list[] = array(
				                    	'item_serial_number'         => $key+1,
								        'product_description'        => $pdt_name,
								        'is_service'                 => 'N',
								        'hsn_code'                   => $hsn_code,
								        'bar_code'                   => '',
								        'quantity'                   => $order_qty,
								        'free_quantity'              => '0',
								        'unit'                       => 'NOS',
								        'unit_price'                 => number_format((float)$price_val, 2, '.', ''),
								        'total_amount'               => number_format((float)$tot_amt, 2, '.', ''),
								        'pre_tax_value'              => '0',
								        'discount'                   => '0',
								        'other_charge'               => '0',
								        'assessable_value'           => number_format((float)$ass_val, 2, '.', ''),
								        'gst_rate'                   => $gst_val,
								        'igst_amount'                => number_format((float)$igstRate, 2, '.', ''),
								        'cgst_amount'                => number_format((float)$cgstRate, 2, '.', ''),
								        'sgst_amount'                => number_format((float)$sgstRate, 2, '.', ''),
								        'cess_rate'                  => '0',
								        'cess_amount'                => '0',
								        'cess_nonadvol_amount'       => '0',
								        'state_cess_rate'            => '0',
								        'state_cess_amount'          => '0',
								        'state_cess_nonadvol_amount' => '0',
								        'total_item_value'           => number_format((float)$tot_val, 2, '.', ''),
								        'country_origin'             => $dis_cntry,
								        'order_line_reference'       => 'N',
								        'product_serial_number'      => 'N',
								        'batch_details'              => array(
								        	'name'          => $pdt_code,
								        	'expiry_date'   => '',
								        	'warranty_date' => '',
								        ),
								        'attribute_details'          => array(
								        	'item_attribute_details' => '',
								        	'item_attribute_value'   => '',
								        )
				                    );
								}
							}

							// Round Val Details
			                $net_value  = round($total_inv);
			                $rond_total = $net_value - $total_inv;

							$einv_data = array(
								'access_token'        => $dis_access,
								'user_gstin'          => $dis_gst_no,
								'data_source'         => 'erp',
								'transaction_details' => array(
									'supply_type'     => 'B2B', 
									'charge_type'     => 'N', 
									'igst_on_intra'   => 'N', 
									'ecommerce_gstin' => ''
								),
								'document_details'    => array(
									'document_type'   => 'INV', // INV, CRN, DBN
									'document_number' => $inv_no,
									'document_date'   => date('d/m/Y', strtotime($inv_date))
								),
								'seller_details'      => array(
									'gstin'        => $dis_gst_no,
									'legal_name'   => $dis_name,
									'trade_name'   => $dis_name,
									'address1'     => mb_strimwidth($dis_adrs, 0, 95, '...'),
									'address2'     => 'None',
									'location'     => $dis_city,
									'pincode'      => $dis_postal,
									'state_code'   => $dis_state,
									'phone_number' => $dis_mobile,
									'email'        => $dis_email,
								),
								'buyer_details'       => array(
									'gstin'           => $str_gst_no,
									'legal_name'      => $str_name,
									'trade_name'      => $str_name,
									'address1'        => mb_strimwidth($str_adrs, 0, 95, '...'),
									'address2'        => 'None',
									'location'        => 'None',
									'pincode'         => $str_postal,
									'place_of_supply' => $str_gst_cd,
									'state_code'      => $str_state,
									'phone_number'    => $str_mobile,
									'email'           => $str_email,
								),
								'dispatch_details'    => array(
									'company_name' => $dis_name,
									'address1'     => mb_strimwidth($dis_adrs, 0, 95, '...'),
									'address2'     => 'None',
									'location'     => $dis_city,
									'pincode'      => $dis_postal,
									'state_code'   => $dis_state,
								),
								'ship_details'        => array(
									'gstin'       => $str_gst_no,
									'legal_name'  => $str_name,
									'trade_name'  => $str_name,
									'address1'    => mb_strimwidth($str_adrs, 0, 95, '...'),
									'address2'    => 'None',
									'location'    => $str_city,
									'pincode'     => $str_postal,
									'state_code'  => $str_state,
								),
								'export_details'      => array(
									'ship_bill_number' => '',
									'ship_bill_date'   => '',
									'country_code'     => '',
									'foreign_currency' => '',
									'refund_claim'     => '',
									'port_code'        => '',
									'export_duty'      => '',
								),
								'payment_details'     => array(
									'bank_account_number' => '',
									'paid_balance_amount' => '',
									'credit_days'         => '',
									'credit_transfer'     => '',
									'direct_debit'        => '',
									'branch_or_ifsc'      => '',
									'payment_mode'        => '',
									'payee_name'          => '',
									'outstanding_amount'  => '',
									'payment_instruction' => '',
									'payment_term'        => '',
								),
								'reference_details'   => array(
									'invoice_remarks'            => '',
									'document_period_details'    => array(
										'invoice_period_start_date' => date('Y-m-d', strtotime($inv_date)),
										'invoice_period_end_date'   => date('Y-m-d', strtotime($inv_date.' +1 Days')),
									),
									'preceding_document_details' => array(
										'reference_of_original_invoice' => 'N',
										'preceding_invoice_date'        => 'N',
										'other_reference'               => 'N',	
									),
									'contract_details'           => array(
										'receipt_advice_number'      => '',
										'receipt_advice_date'        => '',
										'batch_reference_number'     => '',
										'contract_reference_number'  => '',
										'other_reference'            => '',
										'project_reference_number'   => '',
										'vendor_po_reference_number' => $pur_no,
										'vendor_po_reference_date'   => date('d/m/Y', strtotime($pur_date)),
									),
								),
								'additional_document_details'   => array(
									'supporting_document_url' => '',
									'supporting_document'     => '',
									'additional_information'  => '',
								),
								'value_details'       => array(
									'total_assessable_value'    => number_format((float)$total_ass, 2, '.', ''),
									'total_cgst_value'          => number_format((float)$total_cgst, 2, '.', ''),
									'total_sgst_value'          => number_format((float)$total_sgst, 2, '.', ''),
									'total_igst_value'          => number_format((float)$total_igst, 2, '.', ''),
									'total_cess_value'          => '0',
									'total_cess_value_of_state' => '0',
									'total_discount'            => '0',
									'total_other_charge'        => '0',
									'total_invoice_value'       => number_format((float)$net_value, 2, '.', ''),
									'round_off_amount'          => number_format((float)$rond_total, 2, '.', ''),
									'total_invoice_value_additional_currency' => '',
								),
								'ewaybill_details'    => array(
									'transporter_id'              => '',
									'transporter_name'            => '',
									'transportation_mode'         => '',
									'transportation_distance'     => '0',
									'transporter_document_number' => '',
									'transporter_document_date'   => '',
									'vehicle_number'              => '',
									'vehicle_type'                => '',
								),
								'item_list'           => $item_list,
							);

							if($dis_einv == 1)
							{
								$json_value   = json_encode($einv_data);
								$eInv_process = generateEinvoice(EINV_PORTAL.'generateEinvoice', $json_value);					
								$res_code     = $eInv_process->results->code;
								
								if($res_code == 200)
								{
									$res_ackno        = $eInv_process->results->message->AckNo;
								    $res_ackdt        = $eInv_process->results->message->AckDt;
								    $res_irn          = $eInv_process->results->message->Irn;
								    $res_ewbno        = $eInv_process->results->message->EwbNo;
								    $res_ewbdt        = $eInv_process->results->message->EwbDt;
								    $res_ewbvalidtill = $eInv_process->results->message->EwbValidTill;
								    $res_qrcodeurl    = $eInv_process->results->message->QRCodeUrl;
								    $res_einvoicepdf  = $eInv_process->results->message->EinvoicePdf;
								    $res_ewaybillpdf  = '';
								    $trans_doc_date   = ($transporter_doc_date)?date('d-m-Y', strtotime($transporter_doc_date)):'';

								    if($e_way_status == 1)
								    {
								    	// Create E-way bill
									    // ******************************************
									    $trans_date = ($transporter_doc_date)?date('d/m/Y', strtotime($transporter_doc_date)):'';

									    $eway_data = '{
										    "access_token": "'.$dis_access.'",
										    "user_gstin": "'.$dis_gst_no.'",
										    "irn": "'.$res_irn.'",
										    "transporter_id": "'.$transporter_id.'",
										    "transportation_mode": "'.$transportation_mode.'",
										    "transporter_document_number": "'.$transporter_doc_number.'",
										    "transporter_document_date": "'.$trans_date.'",
										    "vehicle_number": "'.$vehicle_number.'",
										    "distance": '.$transportation_distance.',
										    "vehicle_type": "'.$vehicle_type.'",
										    "transporter_name": "'.$transporter_name.'",
										    "data_source": "erp",
										    "ship_details": {
										        "address1": "'.mb_strimwidth($str_adrs, 0, 95, '...').'",
										        "address2": "None",
										        "location": "None",
										        "pincode": '.$str_postal.',
										        "state_code": "'.$str_state.'"
										    },
										    "dispatch_details": {
										        "company_name": "'.$str_name.'",
										        "address1": "'.mb_strimwidth($str_adrs, 0, 95, '...').'",
										        "address2": "None",
										        "location": "None",
										        "pincode": '.$str_postal.',
										        "state_code": "'.$str_state.'"
										    }
										}';

										$eWay_process = generateEinvoice(EINV_PORTAL.'generateEwaybillByIrn', $eway_data);
										$eway_code    = $eWay_process->results->code;

										if($eway_code == 200)
										{
											$res_ewaybillpdf  = $eWay_process->results->message->EwaybillPdf;
											$trans_doc_date   = ($transporter_doc_date)?date('d-m-Y', strtotime($transporter_doc_date)):'';
										}
										else
									    {
									    	$response['status']  = 0;
											$response['message'] = "Something went wrong please try again";
											$response['data']    = [];
											echo json_encode($response);
											return;
									    }
								    }
								}
								else
							    {
							    	$response['status']  = 0;
									$response['message'] = "Something went wrong please try again";
									$response['data']    = [];
									echo json_encode($response);
									return;
							    }
							}
							else
							{
								$res_ackno        = NULL;
							    $res_ackdt        = NULL;
							    $res_irn          = NULL;
							    $res_ewbno        = NULL;
							    $res_ewbdt        = NULL;
							    $res_ewbvalidtill = NULL;
							    $res_qrcodeurl    = NULL;
							    $res_einvoicepdf  = NULL;
							    $res_ewaybillpdf  = NULL;
							    $trans_doc_date   = ($transporter_doc_date)?date('d-m-Y', strtotime($transporter_doc_date)):'';
							}
						}
						else
						{
							$res_ackno        = NULL;
						    $res_ackdt        = NULL;
						    $res_irn          = NULL;
						    $res_ewbno        = NULL;
						    $res_ewbdt        = NULL;
						    $res_ewbvalidtill = NULL;
						    $res_qrcodeurl    = NULL;
						    $res_einvoicepdf  = NULL;
						    $res_ewaybillpdf  = NULL;
						    $trans_doc_date   = ($transporter_doc_date)?date('d-m-Y', strtotime($transporter_doc_date)):'';
						}

						$update_data = array(
							'order_status'    => $progress,
							'product_process' => $progress,
							'_shipping'       => date('Y-m-d H:i:s'),
							'distributor_id'  => $distributor_id,
						);

						// Dimensions details
						$inv_data = array(
							'length'                      => $length,
							'breadth'                     => $breadth,
							'height'                      => $height,
							'weight'                      => $weight,
							'transporter_id'              => $transporter_id,
							'transporter_name'            => $transporter_name,
							'transportation_mode'         => $transportation_mode,
							'transportation_distance'     => $transportation_distance,
							'transporter_document_number' => $transporter_doc_number,
							'transporter_document_date'   => $trans_doc_date,
							'vehicle_number'              => $vehicle_number,
							'vehicle_type'                => $vehicle_type,
							'ackno'                       => $res_ackno,        
						    'ackdt'                       => $res_ackdt,        
						    'irn'                         => $res_irn,          
						    'ewbno'                       => $res_ewbno,        
						    'ewbdt'                       => $res_ewbdt,        
						    'ewbvalidtill'                => $res_ewbvalidtill, 
						    'qrcodeurl'                   => $res_qrcodeurl,    
						    'einvoicepdf'                 => $res_einvoicepdf,
						    'ewaybillpdf'                 => $res_ewaybillpdf,
							'updatedate'                  => date('Y-m-d H:i:s'),
						);

						$inv_whr = array('random_value' => $inv_value);
						$inv_upt = $this->invoice_model->invoice_update($inv_data, $inv_whr);
					}

					// Delivery Process
					else if ($progress == '11') {

						$update_data = array(
							'invoice_process' => '2',
							'order_status'    => $progress,
							'product_process' => $progress,
							'_delivered'      => date('Y-m-d H:i:s'),
							'distributor_id'  => $distributor_id,
						);

						// Update Invoice Table
						$inv_whr = array(
							'random_value' => $inv_value,
							'published'    => '1',
							'status'       => '1',
						);

						$invoice_data = $this->invoice_model->getInvoice($inv_whr);

						$inv_data     = $invoice_data[0];
						$invoice_id   = !empty($inv_data->id) ? $inv_data->id : '';
						$emp_val      = !empty($employee_id) ? $employee_id : '0';

						$upt_data = array(
							'delivery_status'   => '2',
							'delivery_employee' => $emp_val,
							'delivery_date'     => date('Y-m-d H:i:s'),
						);

						$upt_wht  = array('id' => $invoice_id);
						$upt_inv  = $this->invoice_model->invoice_update($upt_data, $upt_wht);

						// Outlet invoice stock update
						// ***************************************
						$whr_1 = array('A.random_value' => $inv_value, 'A.published' => '1', 'A.status' => '1', 'A.cancel_status' => '1');
						$col_1 = 'A.store_id, B.type_id, C.product_type, C.product_unit, B.receive_qty';
						$res_1 = $this->invoice_model->getInvoiceMerge($whr_1, '', '', 'result', '', '', '', '', $col_1);

						if($res_1)
						{
							$ins_data = array();

							foreach ($res_1 as $key => $val_1)
							{
								$outlet_id = zero_check($val_1->store_id);
								$type_id   = zero_check($val_1->type_id);

								$whr_2   = array('outlet_id' => $outlet_id, 'type_id' => $type_id, 'published' => '1');
								$col_2   = 'closeing_stk';
								$limit   = 1;
								$offset  = 0;
								$option  = array('order_by' => 'id', 'disp_order' => 'DESC');
								$res_2   = $this->attendance_model->getOutletStock($whr_2, $limit, $offset, 'row', '', '', $option, '', $col_2);

								$open_stk  = ($res_2)?zero_check($val_2->closeing_stk):'0';
								$close_stk = $open_stk + zero_check($val_1->receive_qty);

								$ins_data[] = array(
							    	'employee_id'  => zero_check($emp_val),
							    	'outlet_id'    => zero_check($val_1->store_id),
							    	'type_id'      => zero_check($val_1->type_id),
								    'pdt_type'     => zero_check($val_1->product_type),
								    'pdt_unit'     => zero_check($val_1->product_unit),
								    'pdt_stock'    => zero_check($val_1->receive_qty),
								    'opening_stk'  => $open_stk,
									'pur_val'      => zero_check($val_1->receive_qty),
									'entry_val'    => 0,
									'sales_val'    => 0,
									'closeing_stk' => zero_check($close_stk),
								    'entry_type'   => 1, // Invoice stock entry
								    'entry_date'   => date('Y-m-d'),
								    'createdate'   => date('Y-m-d H:i:s'),
							    );
							}

							$ins_stk = $this->attendance_model->outletStock_insertBatch($ins_data);
						}						
					}

					// Invoice Cancel
					else if ($progress == '9') {
						// Distributor Product Details
						$column_1 = 'type_id';

						$where_1  = array(
							'id'  => $distributor_id,
						);

						$data_1   = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column_1);

						$result_1 = $data_1[0];
						$type_id  = !empty($result_1->type_id) ? $result_1->type_id : '';

						$ord_whr = array(
							'tbl_order_details.order_id'        => $auto_id,
							'tbl_order_details.delete_status'   => '1',
							'tbl_order_details.published'       => '1',
							'tbl_order_details.type_id'         => $type_id,
							'tbl_order_details.vendor_type != ' => '1'
						);

						$ord_col = 'tbl_order_details.id, tbl_order_details.type_id, tbl_order_details.order_qty, tbl_order_details.unit_val, tbl_order_details.invoice_value';

						$order_list  = $this->order_model->getDistributorOrder($ord_whr, '', '', 'result', '', '', '', '', $ord_col);

						if (!empty($order_list)) {
							$invoice_val = !empty($order_list[0]->invoice_value) ? $order_list[0]->invoice_value : '';

							$inv_whr = array(
								'order_id'     => $auto_id,
								'random_value' => $invoice_val,
							);

							$inv_col = 'id, distributor_id, store_id';
							$inv_res = $this->invoice_model->getInvoice($inv_whr, '', '', 'result', '', '', '', '', $inv_col);

							$inv_id = !empty($inv_res[0]->id) ? $inv_res[0]->id : '';
							$dis_id = !empty($inv_res[0]->distributor_id) ? $inv_res[0]->distributor_id : '';
							$str_id = !empty($inv_res[0]->store_id) ? $inv_res[0]->store_id : '';

							// Payment Details
							$whr_one = array(
								'distributor_id' => $dis_id,
								'outlet_id'      => $str_id,
								'bill_id'        => $auto_id,
							);

							$col_one = 'amount';
							$res_one = $this->payment_model->getOutletPaymentDetails($whr_one, '', '', 'result', '', '', '', '', $col_one);
							$amt_res = !empty($res_one[0]->amount) ? $res_one[0]->amount : '0';

							// Payment Data
							$whr_two = array(
								'bill_code'      => 'PAY',
								'bill_id'        => $auto_id,
								'outlet_id'      => $str_id,
								'distributor_id' => $dis_id,
								'published'      => '1',
							);

							$col_two = 'amount, discount';

							$res_two = $this->payment_model->getOutletPayment($whr_two, '', '', 'result', '', '', '', '', $col_two);

							$tot_amt = 0;
							if ($res_two) {
								foreach ($res_two as $key => $val_2) {
									$amount   = !empty($val_2->amount) ? $val_2->amount : '0';
									$discount = !empty($val_2->discount) ? $val_2->discount : '0';
									$amt_val  = $amount + $discount;
									$tot_amt += $amt_val;
								}
							}

							// Distributor Balance Details
							$whr_three = array('id' => $str_id);
							$col_three = 'available_limit, current_balance';
							$res_three = $this->outlets_model->getOutlets($whr_three, '', '', 'result', '', '', '', '', $col_three);

							$avl_limit   = !empty($res_three[0]->available_limit) ? $res_three[0]->available_limit : '0';
							$cur_balance = !empty($res_three[0]->current_balance) ? $res_three[0]->current_balance : '0';

							$invoice_amt = $amt_res - $tot_amt;
							$new_avl_bal = $avl_limit + $invoice_amt;
							$new_cur_bal = $cur_balance - $invoice_amt;

							$bal_val = array(
								'available_limit' => $new_avl_bal,
								'current_balance' => $new_cur_bal,
							);

							$bal_whr = array('id' => $str_id);
							$bal_upt = $this->outlets_model->outlets_update($bal_val, $bal_whr);

							$disStr_whr = array(
								'distributor_id' => $dis_id,
								'outlet_id'      => $str_id,
								'published'      => '1',
								'status'         => '1',
							);

							$disStr_col = 'id, pre_bal, cur_bal';

							$disStr_res = $this->distributors_model->getDistributorOutlet($disStr_whr, '', '', 'result', '', '', '', '', $disStr_col);

							$assign_id = !empty($disStr_res[0]->id) ? $disStr_res[0]->id : '';
							$pre_bal   = !empty($disStr_res[0]->pre_bal) ? $disStr_res[0]->pre_bal : '';
							$cur_bal   = !empty($disStr_res[0]->cur_bal) ? $disStr_res[0]->cur_bal : '';
							$new_bal   = $cur_bal - $invoice_amt;

							$outlet_data = array(
								'pre_bal' => $cur_bal,
								'cur_bal' => $new_bal,
							);

							$outlet_whr = array('id' => $assign_id);
							$bal_update = $this->distributors_model->distributorOutlet_update($outlet_data, $outlet_whr);

							// Invoice Details
							$whr_four = array(
								'invoice_id'    => $inv_id,
								'cancel_status' => '1',
								'published'     => '1'
							);

							$col_four = 'product_id, type_id, order_qty, unit_val';

							$ord_val  = $this->invoice_model->getInvoiceDetails($whr_four, '', '', 'result', '', '', '', '', $col_four);

							if ($ord_val) {
								foreach ($ord_val as $key => $val_4) {
									$pdt_id   = !empty($val_4->product_id) ? $val_4->product_id : '';
									$type_id  = !empty($val_4->type_id) ? $val_4->type_id : '';
									$ord_qty  = !empty($val_4->order_qty) ? $val_4->order_qty : '0';
									$unit_val = !empty($val_4->unit_val) ? $val_4->unit_val : '';

									// Product Type Details
									$type_whr  = array('id' => $type_id);
									$type_col  = 'product_type, product_stock, view_stock';
									$type_data = $this->commom_model->getProductType($type_whr, '', '', 'result', '', '', '', '', $type_col);

									$pdt_type  = !empty($type_data[0]->product_type) ? $type_data[0]->product_type : '0';

									// View Stock
									if ($unit_val == 1) {
										$multiple_stk   = $ord_qty * $pdt_type; // 5 X 1 = 5 Kg
										$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
										$received_stock = $ord_qty; // 5 Kg
									} else if ($unit_val == 2) {
										$product_stock  = $ord_qty * $pdt_type; // 5 X 100 = 500 Gram
										$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
										$received_stock = number_format($received_value, 2);
									} else {
										$product_stock  = $ord_qty * $pdt_type; // 5 X 1 = 5 Nos
										$received_stock = $ord_qty; // 5 Nos
									}

									// Distributor Product Type Details
									$disPdt_whr  = array(
										'distributor_id' => $dis_id,
										'product_id'     => $pdt_id,
										'type_id'        => $type_id,
										'published'      => '1',
										'status'         => '1'
									);

									$disPdt_col = 'id, stock, view_stock';
									$disPdt_val = $this->assignproduct_model->getAssignProductDetails($disPdt_whr, '', '', 'result', '', '', '', '', $disPdt_col);

									$disPdt_id   = !empty($disPdt_val[0]->id) ? $disPdt_val[0]->id : '0';
									$disPdt_stk  = !empty($disPdt_val[0]->stock) ? $disPdt_val[0]->stock : '0';
									$disPdt_view = !empty($disPdt_val[0]->view_stock) ? $disPdt_val[0]->view_stock : '0';

									// Product Stock Update
									$new_type_stock    = $disPdt_stk + $received_stock;
									$new_type_view_stk = $disPdt_view + $product_stock;

									$disUpt_data = array(
										'stock'      => $new_type_stock,
										'view_stock' => $new_type_view_stk,
									);

									$disUpt_whr  = array('id' => $disPdt_id);
									$disUpt_val  = $this->assignproduct_model->assignProductDetails_update($disUpt_data, $disUpt_whr);
								}
							}

							// Invoice Update
							$val_one  = array('cancel_status' => '2');
							$data_one = array('id' => $inv_id);
							$upt_one  = $this->invoice_model->invoice_update($val_one, $data_one);

							// Invocie Details Update
							$val_two  = array('cancel_status' => '2');
							$data_two = array('invoice_id' => $inv_id);
							$upt_two  = $this->invoice_model->invoiceDetails_update($val_two, $data_two);

							// Payment Data Update
							$val_three  = array('status' => '0', 'published' => '0');
							$data_three = array('bill_id' => $auto_id);
							$upt_three  = $this->payment_model->outletPayment_update($val_three, $data_three);

							// Payment Details Update
							$val_four  = array('status' => '0', 'published' => '0');
							$data_four = array('bill_id' => $auto_id);
							$upt_four  = $this->payment_model->outletPaymentDetails_update($val_four, $data_four);
						}

						$update_data = array(
							'cancel_status'   => '2',
							'order_status'    => $progress,
							'product_process' => $progress,
							'reason'          => $reason,
							'_delete'         => date('Y-m-d H:i:s'),
							'distributor_id'  => $distributor_id,
						);
					}

					// Distributor Product Details
					$column_1 = 'type_id';

					$where_1  = array(
						'id'  => $distributor_id,
					);

					$data_1   = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column_1);

					$result_1 = $data_1[0];
					$type_id  = !empty($result_1->type_id) ? $result_1->type_id : '';

					$ord_whr = array(
						'tbl_order_details.order_id'        => $auto_id,
						'tbl_order_details.delete_status'   => '1',
						'tbl_order_details.published'       => '1',
						'tbl_order_details.type_id'         => $type_id,
						'tbl_order_details.vendor_type != ' => '1'
					);

					$ord_col = 'tbl_order_details.id, tbl_order_details.type_id';

					$order_list  = $this->order_model->getDistributorOrder($ord_whr, '', '', 'result', '', '', '', '', $ord_col);

					if (!empty($order_list)) {
						foreach ($order_list as $key => $value) {

							$ord_value  = !empty($value->id) ? $value->id : '';
							$type_value = !empty($value->type_id) ? $value->type_id : '';

							$where_4 = array(
								'distributor_id' => $distributor_id,
								'type_id'        => $type_value,
								'zone_id'        => $zone_value,
								'status'         => '1',
								'published'      => '1'
							);

							$column_4 = 'id';

							$assign_data = $this->assignproduct_model->getAssignProductDetails($where_4, '', '', 'result', '', '', '', '', $column_4);

							if (!empty($assign_data)) {
								$upt_whr  = array(
									'id' => $ord_value,
								);

								$update = $this->order_model->orderDetails_update($update_data, $upt_whr);
							}
						}
					}

					if ($update_data) {
						$response['status']  = 1;
						$response['message'] = "Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					} else {
						$response['status']  = 0;
						$response['message'] = "Not Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				} else {
					$response['status']  = 0;
					$response['message'] = "Invalid order status";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		}

		// Admin Update Outlet Order Details
		else if ($method == '_updateOrderDetails') {
			$error = FALSE;
			$errors = array();
			$required = array('auto_id', 'price', 'quantity');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error == TRUE) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				$order_data = array(
					'price'      => $price,
					'order_qty'  => $quantity,
					'updatedate' => date('Y-m-d H:i:s'),
				);

				$update_id = array('id' => $auto_id);

				$update = $this->order_model->orderDetails_update($order_data, $update_id);

				if ($update) {
					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		}

		// Admin Delete Outlet Order Details
		else if ($method == '_DeleteOrderDetails') {
			$auto_id = $this->input->post('auto_id');

			if (!empty($auto_id)) {
				$data = array(
					'published' => '0',
				);

				$where  = array('id' => $auto_id);

				$update = $this->order_model->orderDetails_delete($data, $where);

				if ($update) {
					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		}

		// Distributor Add Stock to Product
		else if ($method == '_changePackStatus') {
			$error    = FALSE;
			$required = array('auto_id', 'distributor_id');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error == TRUE) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				$whr_1 = array(
					'id'          => $auto_id,
					'pack_status' => '1',
					'published'   => '1',
					'status'      => '1',
				);

				$col_1 = 'id, order_id, product_id, type_id, order_qty, receive_qty, unit_val';
				$res_1 = $this->order_model->getOrderDetails($whr_1, '', '', 'result', '', '', '', '', $col_1);

				if ($res_1) {
					$po_auto_id   = !empty($res_1[0]->id) ? $res_1[0]->id : '';
					$order_id     = !empty($res_1[0]->order_id) ? $res_1[0]->order_id : '';
					$product_id   = !empty($res_1[0]->product_id) ? $res_1[0]->product_id : '';
					$type_id      = !empty($res_1[0]->type_id) ? $res_1[0]->type_id : '';
					$product_qty  = !empty($res_1[0]->order_qty) ? $res_1[0]->order_qty : '0';
					$receive_qty  = !empty($res_1[0]->receive_qty) ? $res_1[0]->receive_qty : '0';
					$pdt_unit     = !empty($res_1[0]->unit_val) ? $res_1[0]->unit_val : '';
					$pdt_qty      = $product_qty - $receive_qty;

					// Product stock details
					$whr_2  = array(
						'distributor_id' => $distributor_id,
						'type_id'        => $type_id,
						'published'      => '1',
					);

					$col_2  = 'id, stock, view_stock';
					$res_2  = $this->assignproduct_model->getAssignProductDetails($whr_2, '', '', 'result', '', '', '', '', $col_2);

					$data_2 = $res_2[0];

					$assign_id  = !empty($data_2->id) ? $data_2->id : '';
					$stock_val  = !empty($data_2->stock) ? $data_2->stock : '';
					$view_stock = !empty($data_2->view_stock) ? $data_2->view_stock : '';

					// Product Type Details
					$whr_3  = array(
						'id'        => $type_id,
						'published' => '1',
					);

					$col_3  = 'product_type';
					$res_3  = $this->commom_model->getProductType($whr_3, '', '', 'result', '', '', '', '', $col_3);

					$data_3 = $res_3[0];

					$pdt_type = !empty($data_3->product_type) ? $data_3->product_type : '0';

					// View Stock
					if ($pdt_unit == 1 || $pdt_unit == 11) {
						$multiple_stk   = $pdt_qty * $pdt_type; // 5 X 1 = 5 Kg
						$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
						$received_stock = $pdt_qty; // 5 Kg
					} else if ($pdt_unit == 2 || $pdt_unit == 4) {
						$product_stock  = $pdt_qty * $pdt_type; // 5 X 100 = 500 Gram
						$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
						$received_stock = number_format($received_value, 2);
					} else {
						$product_stock  = $pdt_qty * $pdt_type; // 5 X 1 = 5 Nos
						$received_stock = $pdt_qty; // 5 Nos
					}

					if ($view_stock >= $product_stock) {
						// Stock Process
						$new_assign_stock    = $stock_val - $received_stock;
						$new_assign_view_stk = $view_stock - $product_stock;

						$assign_data = array(
							'stock'      => $new_assign_stock,
							'view_stock' => $new_assign_view_stk,
						);

						$assign_whr = array('id' => $assign_id);
						$update     = $this->assignproduct_model->assignProductDetails_update($assign_data, $assign_whr);

						// Order Stock Process
						$overColl_qty = $receive_qty + $pdt_qty;

						$produc_data = array(
							'receive_qty' => strval($overColl_qty),
						);

						$produc_whr   = array('id' => $auto_id);
						$update_prodc = $this->order_model->orderDetails_update($produc_data, $produc_whr);

						// Order Stock Details Insert
						$ins_data = array(
							'order_id'      => $order_id,
							'order_auto_id' => $auto_id,
							'product_id'    => $product_id,
							'type_id'       => $type_id,
							'product_unit'  => $pdt_unit,
							'received_qty'  => $pdt_qty,
							'received_date' => date('Y-m-d'),
							'createdate'    => date('Y-m-d H:i:s')
						);

						$insert = $this->order_model->orderStockDetails_insert($ins_data);

						// Production order qty details
						$where_6 = array(
							'id'        => $auto_id,
							'published' => '1',
							'status'    => '1',
						);

						$column_6 = 'order_qty';

						$order_data = $this->order_model->getOrderDetails($where_6, '', '', 'result', '', '', '', '', $column_6);

						$product_qty = !empty($order_data[0]->order_qty) ? $order_data[0]->order_qty : '';

						// Production receive qty details
						$where_7 = array(
							'order_id'      => $order_id,
							'order_auto_id' => $auto_id,
							'product_id'    => $product_id,
							'type_id'       => $type_id,
							'published'     => '1',
							'status'        => '1',
						);

						$column_7 = 'received_qty';

						$ovr_collect_data = $this->order_model->getOrderStockDetails($where_7, '', '', 'result', '', '', '', '', $column_7);

						$new_received_cou = 0;
						if (!empty($ovr_collect_data)) {
							foreach ($ovr_collect_data as $key => $value) {
								$received_val  = !empty($value->received_qty) ? $value->received_qty : '0';
								$new_received_cou += $received_val;
							}
						}

						if ($product_qty == $new_received_cou) {
							$ord_data = array(
								'production_status' => '2',
								'pack_status'       => '2'
							);

							$ord_whr  = array('id' => $auto_id);
							$ord_upt  = $this->order_model->orderDetails_update($ord_data, $ord_whr);
						}

						if ($insert) {
							$response['status']  = 1;
							$response['message'] = "Success";
							$response['data']    = [];
							echo json_encode($response);
							return;
						} else {
							$response['status']  = 0;
							$response['message'] = "Not Success";
							$response['data']    = [];
							echo json_encode($response);
							return;
						}
					}
				}
			}
		}

		// Distributor delete product 
		else if ($method == '_deletePackStatus') {
			$error    = FALSE;
			$required = array('auto_id');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error == TRUE) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				$upt_data = array(
					'delete_status' => '2',
					'updatedate'    => date('Y-m-d H:i:s'),
				);

				$whr_one = array('id' => $auto_id);
				$upt_one = $this->order_model->orderDetails_update($upt_data, $whr_one);

				if ($upt_one) {
					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		}

		// Distributor Process Order
		else if ($method == '_changePackProcess') {
			$error    = FALSE;
			$required = array('auto_id', 'distributor_id');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error == TRUE) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				// Order Details
				$ord_col  = 'zone_id';
				$ord_whr  = array('id'  => $auto_id);
				$ord_res  = $this->order_model->getOrder($ord_whr, '', '', 'result', '', '', '', '', $ord_col);
				$zone_val = !empty($ord_res[0]->zone_id) ? $ord_res[0]->zone_id : '';


				$column_1 = 'type_id';
				$where_1  = array('id'  => $distributor_id);

				$data_1   = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column_1);

				$result_1 = $data_1[0];
				$type_id  = !empty($result_1->type_id) ? $result_1->type_id : '';

				$ord_whr = array(
					'tbl_order_details.order_id'        => $auto_id,
					'tbl_order_details.pack_status'     => '1',
					'tbl_order_details.delete_status'   => '1',
					'tbl_order_details.published'       => '1',
					'tbl_order_details.type_id'         => $type_id,
					'tbl_order_details.vendor_type != ' => '1'
				);

				$ord_col = 'tbl_order_details.id, tbl_order_details.product_id, tbl_order_details.type_id, tbl_order_details.unit_val, tbl_order_details.order_qty, tbl_order_details.receive_qty, tbl_order_details.pack_status, tbl_order_details.delete_status';

				$order_list  = $this->order_model->getDistributorOrder($ord_whr, '', '', 'result', '', '', '', '', $ord_col);

				if (!empty($order_list)) {
					foreach ($order_list as $key => $value) {
						$auto_num      = !empty($value->id) ? $value->id : '';
						$product_id    = !empty($value->product_id) ? $value->product_id : '';
						$type_id       = !empty($value->type_id) ? $value->type_id : '';
						$pdt_unit      = !empty($value->unit_val) ? $value->unit_val : '';
						$pdt_qty       = !empty($value->order_qty) ? $value->order_qty : '0';
						$receive_qty   = !empty($value->receive_qty) ? $value->receive_qty : '0';
						$pack_status   = !empty($value->pack_status) ? $value->pack_status : '';
						$delete_status = !empty($value->delete_status) ? $value->delete_status : '';

						// Product Type Details
						$where_3  = array('id' => $type_id);
						$column_3 = 'product_type';
						$type_det = $this->commom_model->getProductType($where_3, '', '', 'result', '', '', '', '', $column_3);
						$pdt_type = isset($type_det[0]->product_type) ? $type_det[0]->product_type : '';

						$where_4 = array(
							'distributor_id' => $distributor_id,
							'product_id'     => $product_id,
							'type_id'        => $type_id,
							'published'      => '1',
							'status'         => '1',
						);

						$column_4 = 'id, stock, view_stock';
						$ass_data = $this->assignproduct_model->getAssignProductDetails($where_4, '', '', 'result', '', '', '', '', $column_4);

						$assPdt_id  = !empty($ass_data[0]->id) ? $ass_data[0]->id : '0';
						$stock      = !empty($ass_data[0]->stock) ? $ass_data[0]->stock : '0';
						$view_stock = !empty($ass_data[0]->view_stock) ? $ass_data[0]->view_stock : '0';

						// View Stock
						if ($pdt_unit == 1 || $pdt_unit == 11) {
							$multiple_stk   = $pdt_qty * $pdt_type; // 5 X 1 = 5 Kg
							$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
							$received_stock = $pdt_qty; // 5 Kg
						} else if ($pdt_unit == 2 || $pdt_unit == 4) {
							$product_stock  = $pdt_qty * $pdt_type; // 5 X 100 = 500 Gram
							$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
							$received_stock = number_format($received_value, 2);
						} else {
							$product_stock  = $pdt_qty * $pdt_type; // 5 X 1 = 5 Nos
							$received_stock = $pdt_qty; // 5 Nos
						}

						if ($view_stock >= $product_stock) {
							// Stock Process
							$new_assign_stock    = $stock - $received_stock;
							$new_assign_view_stk = $view_stock - $product_stock;

							$assign_data = array(
								'stock'      => $new_assign_stock,
								'view_stock' => $new_assign_view_stk,
							);

							$assign_whr = array('id' => $assPdt_id);
							$update     = $this->assignproduct_model->assignProductDetails_update($assign_data, $assign_whr);

							// Order Stock Process
							$overColl_qty = $receive_qty + $pdt_qty;

							$produc_data = array(
								'receive_qty' => strval($overColl_qty),
							);

							$produc_whr   = array('id' => $auto_num);
							$update_prodc = $this->order_model->orderDetails_update($produc_data, $produc_whr);

							// Order Stock Details Insert
							$ins_data = array(
								'order_id'      => $auto_id,
								'order_auto_id' => $auto_num,
								'product_id'    => $product_id,
								'type_id'       => $type_id,
								'product_unit'  => $pdt_unit,
								'received_qty'  => $pdt_qty,
								'received_date' => date('Y-m-d'),
								'createdate'    => date('Y-m-d H:i:s')
							);

							$insert = $this->order_model->orderStockDetails_insert($ins_data);

							// Production order qty details
							$where_6 = array(
								'id'            => $auto_num,
								'delete_status' => '1',
								'published'     => '1',
								'status'        => '1',
							);

							$column_6 = 'order_qty';

							$order_data = $this->order_model->getOrderDetails($where_6, '', '', 'result', '', '', '', '', $column_6);

							$product_qty = !empty($order_data[0]->order_qty) ? $order_data[0]->order_qty : '0';

							// Production receive qty details
							$where_7 = array(
								'order_id'      => $auto_id,
								'order_auto_id' => $auto_num,
								'product_id'    => $product_id,
								'type_id'       => $type_id,
								'published'     => '1',
								'status'        => '1',
							);

							$coll_data = $this->order_model->getOrderStockDetails($where_7, '', '', "result", array(), array(), array(), TRUE, 'SUM(received_qty) AS received_qty');

							$new_received_cou = !empty($coll_data[0]->received_qty) ? $coll_data[0]->received_qty : '0';

							if ($product_qty == $new_received_cou) {
								$ord_data = array('production_status' => '2', 'pack_status' => '2');
								$ord_whr  = array('id' => $auto_num);
								$ord_upt  = $this->order_model->orderDetails_update($ord_data, $ord_whr);
							}
						}
					}

					if ($order_list) {
						$response['status']  = 1;
						$response['message'] = "Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					} else {
						$response['status']  = 0;
						$response['message'] = "Not Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				} else {
					$response['status']  = 0;
					$response['message'] = "Data Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		} else {
			$response['status']  = 0;
			$response['message'] = "Error";
			$response['data']    = [];
			echo json_encode($response);
			return;
		}
	}

	// Create / Edit Order Details (Vendor / Distributor)
	// ***************************************************
	public function manage_orderDetails($param1 = "", $param2 = "", $param3 = "")
	{
		$method         = $this->input->post('method');
		$distributor_id = $this->input->post('distributor_id');
		$auto_id        = $this->input->post('auto_id');
		$quantity       = $this->input->post('quantity');

		// Vendor Order Details Update
		// ***********************************************
		if ($method == '_updateOrderDetails') {
			$error = FALSE;
			$required = array('auto_id', 'quantity');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error == TRUE) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				// Order Details
				$where_1 = array(
					'id'        => $auto_id,
					'published' => '1',
					'status'    => '1',
				);

				$column_1 = 'product_id, type_id, order_qty, unit_val';

				$order_data = $this->order_model->getOrderDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

				$product_id = !empty($order_data[0]->product_id) ? $order_data[0]->product_id : '';
				$type_id    = !empty($order_data[0]->type_id) ? $order_data[0]->type_id : '';
				$unit_val   = !empty($order_data[0]->unit_val) ? $order_data[0]->unit_val : '';
				$order_qty  = !empty($order_data[0]->order_qty) ? $order_data[0]->order_qty : '';

				// Product Type Stock Plus
				$where_2 = array(
					'
			    		id'          => $type_id,
					'product_id' => $product_id,
				);

				$productType_val = $this->commom_model->getProductType($where_2);

				$product_type    = !empty($productType_val[0]->product_type) ? $productType_val[0]->product_type : '0';
				$typeStock       = !empty($productType_val[0]->type_stock) ? $productType_val[0]->type_stock : '0';
				$typeView_stock  = !empty($productType_val[0]->stock_detail) ? $productType_val[0]->stock_detail : '0';

				if ($order_qty >= $quantity) {
					// Minus Qty
					$new_qty = $order_qty - $quantity;

					// View Stock
					if ($unit_val == 1) {
						$multiple_stk   = $new_qty * $product_type; // 5 X 1 = 5 Kg
						$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
						$received_stock = $new_qty; // 5 Kg
					} else if ($unit_val == 2) {
						$product_stock  = $new_qty * $product_type; // 5 X 100 = 500 Gram
						$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
						$received_stock = number_format($received_value, 2);
					} else {
						$product_stock  = $new_qty * $product_type; // 5 X 1 = 5 Nos
						$received_stock = $new_qty; // 5 Nos
					}

					// Stock Process
					$new_type_stock    = $typeStock + $received_stock;
					$new_type_view_stk = $typeView_stock + $product_stock;

					$type_data = array(
						'type_stock'   => $new_type_stock,
						'stock_detail' => $new_type_view_stk,
					);

					$type_whr = array('id' => $type_id);
					$update   = $this->commom_model->productType_update($type_data, $type_whr);

					$order_value = array(
						'order_qty'   => $quantity,
						'receive_qty' => $quantity,
					);

					$update_id  = array('id' => $auto_id);
					$odr_update = $this->order_model->orderDetails_update($order_value, $update_id);

					if ($odr_update) {
						$response['status']  = 1;
						$response['message'] = "Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					} else {
						$response['status']  = 0;
						$response['message'] = "Not Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				} else {
					$response['status']  = 0;
					$response['message'] = "Invalid Quantity";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		}

		// Vendor Order Details Update
		// ***********************************************
		else if ($method == '_deleteOrderDetails') {
			if (!empty($auto_id)) {
				$where_1 = array(
					'id'        => $auto_id,
					'published' => '1',
					'status'    => '1',
				);

				$column_1 = 'product_id, type_id, order_qty, unit_val';

				$order_data = $this->order_model->getOrderDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

				$product_id = !empty($order_data[0]->product_id) ? $order_data[0]->product_id : '';
				$type_id    = !empty($order_data[0]->type_id) ? $order_data[0]->type_id : '';
				$unit_val   = !empty($order_data[0]->unit_val) ? $order_data[0]->unit_val : '';
				$order_qty  = !empty($order_data[0]->order_qty) ? $order_data[0]->order_qty : '';

				// Product Type Stock Plus
				$where_2 = array(
					'
			    		id'          => $type_id,
					'product_id' => $product_id,
				);

				$productType_val = $this->commom_model->getProductType($where_2);

				$product_type    = !empty($productType_val[0]->product_type) ? $productType_val[0]->product_type : '0';
				$typeStock       = !empty($productType_val[0]->type_stock) ? $productType_val[0]->type_stock : '0';
				$typeView_stock  = !empty($productType_val[0]->stock_detail) ? $productType_val[0]->stock_detail : '0';

				// View Stock
				if ($unit_val == 1) {
					$multiple_stk   = $order_qty * $product_type; // 5 X 1 = 5 Kg
					$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
					$received_stock = $order_qty; // 5 Kg
				} else if ($unit_val == 2) {
					$product_stock  = $order_qty * $product_type; // 5 X 100 = 500 Gram
					$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
					$received_stock = number_format($received_value, 2);
				} else {
					$product_stock  = $order_qty * $product_type; // 5 X 1 = 5 Nos
					$received_stock = $order_qty; // 5 Nos
				}

				// Stock Process
				$new_type_stock    = $typeStock + $received_stock;
				$new_type_view_stk = $typeView_stock + $product_stock;

				$type_data = array(
					'type_stock'   => $new_type_stock,
					'stock_detail' => $new_type_view_stk,
				);

				$type_whr = array('id' => $type_id);
				$update   = $this->commom_model->productType_update($type_data, $type_whr);

				$order_data = array(
					'published'   => '0',
				);

				$order_whr  = array('id' => $auto_id);
				$odr_delete = $this->order_model->orderDetails_update($order_data, $order_whr);

				if ($odr_delete) {
					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			}
		}

		// Distributor Order Details Update
		// ***********************************************
		else if ($method == '_updateDistributorOrderDetails') {
			$error = FALSE;
			$required = array('distributor_id', 'auto_id', 'quantity');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error == TRUE) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				// Order Details
				$where_1 = array(
					'id'        => $auto_id,
					'published' => '1',
					'status'    => '1',
				);

				$column_1 = 'zone_id, order_id, product_id, type_id, order_qty, unit_val';

				$order_data = $this->order_model->getOrderDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

				$zone_id    = !empty($order_data[0]->zone_id) ? $order_data[0]->zone_id : '';
				$order_id   = !empty($order_data[0]->order_id) ? $order_data[0]->order_id : '';
				$product_id = !empty($order_data[0]->product_id) ? $order_data[0]->product_id : '';
				$type_id    = !empty($order_data[0]->type_id) ? $order_data[0]->type_id : '';
				$unit_val   = !empty($order_data[0]->unit_val) ? $order_data[0]->unit_val : '';
				$order_qty  = !empty($order_data[0]->order_qty) ? $order_data[0]->order_qty : '';

				// Product Type Stock Plus
				$where_2 = array(
					'
			    		id'          => $type_id,
					'product_id' => $product_id,
				);

				$productType_val = $this->commom_model->getProductType($where_2);

				$product_type    = !empty($productType_val[0]->product_type) ? $productType_val[0]->product_type : '0';

				if ($order_qty > $quantity) {
					// Minus Qty
					$new_qty = $order_qty - $quantity;

					// View Stock
					if ($unit_val == 1 || $unit_val == 11) {
						$multiple_stk   = $new_qty * $product_type; // 5 X 1 = 5 Kg
						$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
						$received_stock = $new_qty; // 5 Kg
					} else if ($unit_val == 2 || $unit_val == 4) {
						$product_stock  = $new_qty * $product_type; // 5 X 100 = 500 Gram
						$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
						$received_stock = number_format($received_value, 2);
					} else {
						$product_stock  = $new_qty * $product_type; // 5 X 1 = 5 Nos
						$received_stock = $new_qty; // 5 Nos
					}

					// Assign Product Details
					$where_3 = array(
						'distributor_id' => $distributor_id,
						'zone_id'        => $zone_id,
						'product_id'     => $product_id,
						'type_id'        => $type_id,
						'published'      => '1',
						'status'         => '1',
					);

					$assign_data = $this->assignproduct_model->getAssignProductDetails($where_3);

					$assign_id  = !empty($assign_data[0]->id) ? $assign_data[0]->id : '0';
					$stock_val  = !empty($assign_data[0]->stock) ? $assign_data[0]->stock : '0';
					$view_stock = !empty($assign_data[0]->view_stock) ? $assign_data[0]->view_stock : '0';

					// Stock Process
					$new_pdt_stock    = $stock_val + $received_stock;
					$new_pdt_view_stk = $view_stock + $product_stock;

					$stock_data = array(
						'stock'      => $new_pdt_stock,
						'view_stock' => $new_pdt_view_stk,
					);

					$assign_whr = array('id' => $assign_id);
					$update     = $this->assignproduct_model->assignProductDetails_update($stock_data, $assign_whr);

					$order_value = array(
						'order_qty'   => $quantity,
						'receive_qty' => $quantity,
					);

					$update_id  = array('id' => $auto_id);
					$odr_update = $this->order_model->orderDetails_update($order_value, $update_id);

					if ($odr_update) {
						$response['status']  = 1;
						$response['message'] = "Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					} else {
						$response['status']  = 0;
						$response['message'] = "Not Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				} else {
					$response['status']  = 0;
					$response['message'] = "Invalid Quantity";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		}

		// Distributor Order Details Update
		// ***********************************************
		else if ($method == '_deleteDistributorOrderDetails') {
			$error = FALSE;
			$required = array('distributor_id', 'auto_id');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error == TRUE) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				// Order Details
				$where_1 = array(
					'id'        => $auto_id,
					'published' => '1',
					'status'    => '1',
				);

				$column_1 = 'zone_id, order_id, product_id, type_id, order_qty, unit_val';

				$order_data = $this->order_model->getOrderDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

				$zone_id    = !empty($order_data[0]->zone_id) ? $order_data[0]->zone_id : '';
				$order_id   = !empty($order_data[0]->order_id) ? $order_data[0]->order_id : '';
				$product_id = !empty($order_data[0]->product_id) ? $order_data[0]->product_id : '';
				$type_id    = !empty($order_data[0]->type_id) ? $order_data[0]->type_id : '';
				$unit_val   = !empty($order_data[0]->unit_val) ? $order_data[0]->unit_val : '';
				$order_qty  = !empty($order_data[0]->order_qty) ? $order_data[0]->order_qty : '';

				// Product Type Stock Plus
				$where_2 = array(
					'
			    		id'          => $type_id,
					'product_id' => $product_id,
				);

				$productType_val = $this->commom_model->getProductType($where_2);

				$product_type    = !empty($productType_val[0]->product_type) ? $productType_val[0]->product_type : '0';

				// View Stock
				if ($unit_val == 1 || $unit_val == 11) {
					$multiple_stk   = $order_qty * $product_type; // 5 X 1 = 5 Kg
					$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
					$received_stock = $order_qty; // 5 Kg
				} else if ($unit_val == 2 || $unit_val == 4) {
					$product_stock  = $order_qty * $product_type; // 5 X 100 = 500 Gram
					$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
					$received_stock = number_format($received_value, 2);
				} else {
					$product_stock  = $order_qty * $product_type; // 5 X 1 = 5 Nos
					$received_stock = $order_qty; // 5 Nos
				}

				// Assign Product Details
				$where_3 = array(
					'distributor_id' => $distributor_id,
					'zone_id'        => $zone_id,
					'product_id'     => $product_id,
					'type_id'        => $type_id,
					'published'      => '1',
					'status'         => '1',
				);

				$assign_data = $this->assignproduct_model->getAssignProductDetails($where_3);

				$assign_id  = !empty($assign_data[0]->id) ? $assign_data[0]->id : '0';
				$stock_val  = !empty($assign_data[0]->stock) ? $assign_data[0]->stock : '0';
				$view_stock = !empty($assign_data[0]->view_stock) ? $assign_data[0]->view_stock : '0';

				// Stock Process
				$new_pdt_stock    = $stock_val + $received_stock;
				$new_pdt_view_stk = $view_stock + $product_stock;

				$stock_data = array(
					'stock'      => $new_pdt_stock,
					'view_stock' => $new_pdt_view_stk,
				);

				$assign_whr = array('id' => $assign_id);
				$update     = $this->assignproduct_model->assignProductDetails_update($stock_data, $assign_whr);

				$delete_data = array(
					'published'   => '0',
				);

				$delete_whr  = array('id' => $auto_id);
				$odr_delete  = $this->order_model->orderDetails_update($delete_data, $delete_whr);

				if ($odr_delete) {
					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		} else {
			$response['status']  = 0;
			$response['message'] = "Error";
			$response['data']    = [];
			echo json_encode($response);
			return;
		}
	}

	// Create / Edit Order Stock (Vendor / Distributor)
	// ***************************************************
	public function manage_orderStkDetails($param1 = "", $param2 = "", $param3 = "")
	{
		$method         = $this->input->post('method');
		$order_id       = $this->input->post('order_id');
		$order_auto_id  = $this->input->post('order_auto_id');
		$product_id     = $this->input->post('product_id');
		$type_id        = $this->input->post('type_id');
		$product_unit   = $this->input->post('product_unit');
		$received_qty   = $this->input->post('received_qty');
		$received_date  = $this->input->post('received_date');
		$distributor_id = $this->input->post('distributor_id');
		$stock_id       = $this->input->post('stock_id');

		if ($method == '_addOrderStockDetails') {
			$error = FALSE;
			$errors = array();
			$required = array('order_id', 'order_auto_id', 'product_id', 'type_id', 'received_qty', 'received_date');
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
				// Get Order Qty
				$where_1 = array(
					'id'         => $order_auto_id,
					'order_id'   => $order_id,
					'product_id' => $product_id,
					'type_id'    => $type_id,
					'published'  => '1',
					'status'     => '1',
				);

				$column_1 = 'order_qty';

				$order_data = $this->order_model->getOrderDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

				$order_qty  = !empty($order_data[0]->order_qty) ? $order_data[0]->order_qty : '';

				// Collect Order Qty
				$where_2 = array(
					'order_id'      => $order_id,
					'order_auto_id' => $order_auto_id,
					'product_id'    => $product_id,
					'type_id'       => $type_id,
					'published'     => '1',
					'status'        => '1',
				);

				$column_2 = 'received_qty';

				$collect_data = $this->order_model->getOrderStockDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

				$received_cou = 0;
				if (!empty($collect_data)) {
					foreach ($collect_data as $key => $value) {
						$received_val  = !empty($value->received_qty) ? $value->received_qty : '';
						$received_cou += $received_val;
					}
				}

				// Overall Collect Data
				$over_collect = $order_qty - $received_cou;

				if ($over_collect >= $received_qty) {
					// Product Type Stock Minus
					$where_3 = array('id' => $type_id, 'product_id' => $product_id);

					$productType_val = $this->commom_model->getProductType($where_3);

					$product_unit    = !empty($productType_val[0]->product_unit) ? $productType_val[0]->product_unit : '';
					$typeStock       = !empty($productType_val[0]->product_stock) ? $productType_val[0]->product_stock : '0';
					$typeView_stock  = !empty($productType_val[0]->view_stock) ? $productType_val[0]->view_stock : '0';

					// View Stock
					if ($product_unit == 1) {
						$product_stock = $received_qty * 1000;
					} else if ($product_unit == 2) {
						$product_stock = $received_qty;
					} else {
						$product_stock = $received_qty;
					}

					if ($typeStock >= $received_qty) {
						// Stock Process
						$new_type_stock    = $typeStock - $received_qty;
						$new_type_view_stk = $typeView_stock - $product_stock;

						$type_data = array(
							'product_stock' => $new_type_stock,
							'view_stock'    => $new_type_view_stk,
						);

						$type_whr = array('id' => $type_id);
						$update   = $this->commom_model->productType_update($type_data, $type_whr);

						// Order Stock Process
						$overColl_qty = $received_cou + $received_qty;

						$produc_data = array(
							'receive_qty' => strval($overColl_qty),
						);

						$produc_whr   = array('id' => $order_auto_id);
						$update_prodc = $this->order_model->orderDetails_update($produc_data, $produc_whr);

						// Order Stock Details Insert
						$ins_data = array(
							'order_id'      => $order_id,
							'order_auto_id' => $order_auto_id,
							'product_id'    => $product_id,
							'type_id'       => $type_id,
							'product_unit'  => $product_unit,
							'received_qty'  => $received_qty,
							'received_date' => date('Y-m-d', strtotime($received_date)),
							'createdate'    => date('Y-m-d H:i:s')
						);

						$insert = $this->order_model->orderStockDetails_insert($ins_data);


						if ($insert) {
							$response['status']  = 1;
							$response['message'] = "Success";
							$response['data']    = [];
							echo json_encode($response);
							return;
						} else {
							$response['status']  = 0;
							$response['message'] = "Not Success";
							$response['data']    = [];
							echo json_encode($response);
							return;
						}
					} else {
						$response['status']  = 0;
						$response['message'] = "Invalid Stock";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				} else {
					$response['status']  = 0;
					$response['message'] = "Invalid Quantity";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		}

		// Vendor Stock Update
		// ***************************************************
		else if ($method == '_addVendorOrderStockDetails') {
			$error = FALSE;
			$errors = array();
			$required = array('order_id', 'order_auto_id', 'product_id', 'type_id', 'product_unit', 'received_qty', 'received_date');
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
				// Get Order Qty
				$where_1 = array(
					'id'         => $order_auto_id,
					'order_id'   => $order_id,
					'product_id' => $product_id,
					'type_id'    => $type_id,
					'published'  => '1',
					'status'     => '1',
				);

				$column_1 = 'order_qty';

				$order_data = $this->order_model->getOrderDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

				if ($order_data) {
					$order_qty  = !empty($order_data[0]->order_qty) ? $order_data[0]->order_qty : '0';

					// Collect Order Qty
					$where_2 = array(
						'order_id'      => $order_id,
						'order_auto_id' => $order_auto_id,
						'product_id'    => $product_id,
						'type_id'       => $type_id,
						'published'     => '1',
						'status'        => '1',
					);

					$column_2 = 'received_qty';

					$collect_data = $this->order_model->getOrderStockDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

					$received_cou = 0;
					if (!empty($collect_data)) {
						foreach ($collect_data as $key => $value) {
							$received_val  = !empty($value->received_qty) ? $value->received_qty : '0';
							$received_cou += $received_val;
						}
					}

					// Overall Collect Data
					$over_collect = $order_qty - $received_cou;

					if ($over_collect >= $received_qty) {
						// Product Type Stock Minus
						$where_3 = array('id' => $type_id, 'product_id' => $product_id);

						$productType_val = $this->commom_model->getProductType($where_3);

						$product_type    = !empty($productType_val[0]->product_type) ? $productType_val[0]->product_type : '';
						$product_unit    = !empty($productType_val[0]->product_unit) ? $productType_val[0]->product_unit : '';
						$typeStock       = !empty($productType_val[0]->type_stock) ? $productType_val[0]->type_stock : '0';
						$typeView_stock  = !empty($productType_val[0]->stock_detail) ? $productType_val[0]->stock_detail : '0';

						// View Stock
						if ($product_unit == 1) {
							$multiple_stk   = $received_qty * $product_type; // 5 X 1 = 5 Kg
							$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
							$received_stock = $received_qty; // 5 Kg
						} else if ($product_unit == 2) {
							$product_stock  = $received_qty * $product_type; // 5 X 100 = 500 Gram
							$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
							$received_stock = number_format($received_value, 2);
						} else {
							$product_stock  = $received_qty * $product_type; // 5 X 1 = 5 Nos
							$received_stock = $received_qty; // 5 Nos
						}

						if ($typeStock >= $received_stock) {
							// Stock Process
							$new_type_stock    = $typeStock - $received_stock;
							$new_type_view_stk = $typeView_stock - $product_stock;

							$type_data = array(
								'type_stock'   => $new_type_stock,
								'stock_detail' => $new_type_view_stk,
							);

							$type_whr = array('id' => $type_id);
							$update   = $this->commom_model->productType_update($type_data, $type_whr);

							// Order Stock Process
							$overColl_qty = $received_cou + $received_qty;

							$produc_data = array(
								'receive_qty' => strval($overColl_qty),
							);

							$produc_whr   = array('id' => $order_auto_id);
							$update_prodc = $this->order_model->orderDetails_update($produc_data, $produc_whr);

							// Order Stock Details Insert
							$ins_data = array(
								'order_id'      => $order_id,
								'order_auto_id' => $order_auto_id,
								'product_id'    => $product_id,
								'type_id'       => $type_id,
								'product_unit'  => $product_unit,
								'received_qty'  => $received_qty,
								'received_date' => date('Y-m-d', strtotime($received_date)),
								'createdate'    => date('Y-m-d H:i:s')
							);

							$insert = $this->order_model->orderStockDetails_insert($ins_data);

							// Production order qty details
							$where_4 = array(
								'id'        => $order_auto_id,
								'published' => '1',
								'status'    => '1',
							);

							$column_4 = 'order_qty';

							$order_data = $this->order_model->getOrderDetails($where_4, '', '', 'result', '', '', '', '', $column_4);

							$product_qty = !empty($order_data[0]->order_qty) ? $order_data[0]->order_qty : '';

							// Production receive qty details
							$where_5 = array(
								'order_id'      => $order_id,
								'order_auto_id' => $order_auto_id,
								'product_id'    => $product_id,
								'type_id'       => $type_id,
								'published'     => '1',
								'status'        => '1',
							);

							$column_5 = 'received_qty';

							$ovr_collect_data = $this->order_model->getOrderStockDetails($where_5, '', '', 'result', '', '', '', '', $column_5);

							$new_received_cou = 0;
							if (!empty($ovr_collect_data)) {
								foreach ($ovr_collect_data as $key => $value) {
									$received_val  = !empty($value->received_qty) ? $value->received_qty : '';
									$new_received_cou += $received_val;
								}
							}

							if ($product_qty == $new_received_cou) {
								$ord_data = array('production_status' => '2');
								$ord_whr  = array('id' => $order_auto_id);
								$ord_upt  = $this->order_model->orderDetails_update($ord_data, $ord_whr);
							}

							if ($insert) {
								$response['status']  = 1;
								$response['message'] = "Success";
								$response['data']    = [];
								echo json_encode($response);
								return;
							} else {
								$response['status']  = 0;
								$response['message'] = "Not Success";
								$response['data']    = [];
								echo json_encode($response);
								return;
							}
						} else {
							$response['status']  = 0;
							$response['message'] = "Invalid Stock";
							$response['data']    = [];
							echo json_encode($response);
							return;
						}
					} else {
						$response['status']  = 0;
						$response['message'] = "Invalid Quantity";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		}

		// Distributor Stock Update
		// ***************************************************
		else if ($method == '_addDistributorOrderStockDetails') {
			$error = FALSE;
			$errors = array();
			$required = array('order_id', 'order_auto_id', 'distributor_id', 'product_id', 'type_id', 'product_unit', 'received_qty', 'received_date');
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
				// Get Order Qty
				$where_1 = array(
					'id'         => $order_auto_id,
					'order_id'   => $order_id,
					'product_id' => $product_id,
					'type_id'    => $type_id,
					'published'  => '1',
					'status'     => '1',
				);

				$column_1 = 'order_qty';

				$order_data = $this->order_model->getOrderDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

				if ($order_data) {
					$order_qty  = !empty($order_data[0]->order_qty) ? $order_data[0]->order_qty : '0';

					// Collect Order Qty
					$where_2 = array(
						'order_id'      => $order_id,
						'order_auto_id' => $order_auto_id,
						'product_id'    => $product_id,
						'type_id'       => $type_id,
						'published'     => '1',
						'status'        => '1',
					);

					$column_2 = 'received_qty';

					$collect_data = $this->order_model->getOrderStockDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

					$received_cou = 0;
					if (!empty($collect_data)) {
						foreach ($collect_data as $key => $value) {
							$received_val  = !empty($value->received_qty) ? $value->received_qty : '0';
							$received_cou += $received_val;
						}
					}

					// Overall Collect Data
					$over_collect = $order_qty - $received_cou;

					if ($over_collect >= $received_qty) {
						$where_3 = array(
							'id'        => $order_id,
							'published' => '1',
							'status'    => '1',
						);

						$column_3  = 'id, zone_id';

						$bill_data = $this->order_model->getOrder($where_3, '', '', 'result', '', '', '', '', $column_3);

						$zone_id   = !empty($bill_data[0]->zone_id) ? $bill_data[0]->zone_id : '';

						$where_4 = array(
							'distributor_id' => $distributor_id,
							'product_id'     => $product_id,
							'type_id'        => $type_id,
							'published'      => '1',
							'status'         => '1',
						);

						$ass_data = $this->assignproduct_model->getAssignProductDetails($where_4);

						$assPdt_id  = !empty($ass_data[0]->id) ? $ass_data[0]->id : '0';
						$stock      = !empty($ass_data[0]->stock) ? $ass_data[0]->stock : '0';
						$view_stock = !empty($ass_data[0]->view_stock) ? $ass_data[0]->view_stock : '0';

						// Product Type Stock Minus
						$where_5 = array('id' => $type_id, 'product_id' => $product_id);
						$productType_val = $this->commom_model->getProductType($where_5);

						$product_type    = !empty($productType_val[0]->product_type) ? $productType_val[0]->product_type : '';

						// View Stock
						if ($product_unit == 1 || $product_unit == 11) {
							$multiple_stk   = $received_qty * $product_type; // 5 X 1 = 5 Kg
							$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
							$received_stock = $received_qty; // 5 Kg
						}else if ($product_unit == 2 || $product_unit == 4) {
							$product_stock  = $received_qty * $product_type; // 5 X 100 = 500 Gram
							$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
							$received_stock = number_format($received_value, 2);
						} else {
							$product_stock  = $received_qty * $product_type; // 5 X 1 = 5 Nos
							$received_stock = $received_qty; // 5 Nos
						}

						if ($stock >= $received_stock) {
							// Stock Process
							$new_assign_stock    = $stock - $received_stock;
							$new_assign_view_stk = $view_stock - $product_stock;

							$assign_data = array(
								'stock'      => $new_assign_stock,
								'view_stock' => $new_assign_view_stk,
							);

							$assign_whr = array('id' => $assPdt_id);
							$update     = $this->assignproduct_model->assignProductDetails_update($assign_data, $assign_whr);

							// Order Stock Process
							$overColl_qty = $received_cou + $received_qty;

							$produc_data = array(
								'receive_qty' => strval($overColl_qty),
							);

							$produc_whr   = array('id' => $order_auto_id);
							$update_prodc = $this->order_model->orderDetails_update($produc_data, $produc_whr);

							// Order Stock Details Insert
							$ins_data = array(
								'order_id'      => $order_id,
								'order_auto_id' => $order_auto_id,
								'product_id'    => $product_id,
								'type_id'       => $type_id,
								'product_unit'  => $product_unit,
								'received_qty'  => $received_qty,
								'received_date' => date('Y-m-d', strtotime($received_date)),
								'createdate'    => date('Y-m-d H:i:s')
							);

							$insert = $this->order_model->orderStockDetails_insert($ins_data);

							// Production order qty details
							$where_6 = array(
								'id'        => $order_auto_id,
								'published' => '1',
								'status'    => '1',
							);

							$column_6 = 'order_qty';

							$order_data = $this->order_model->getOrderDetails($where_6, '', '', 'result', '', '', '', '', $column_6);

							$product_qty = !empty($order_data[0]->order_qty) ? $order_data[0]->order_qty : '';

							// Production receive qty details
							$where_7 = array(
								'order_id'      => $order_id,
								'order_auto_id' => $order_auto_id,
								'product_id'    => $product_id,
								'type_id'       => $type_id,
								'published'     => '1',
								'status'        => '1',
							);

							$column_7 = 'received_qty';

							$ovr_collect_data = $this->order_model->getOrderStockDetails($where_7, '', '', 'result', '', '', '', '', $column_7);

							$new_received_cou = 0;
							if (!empty($ovr_collect_data)) {
								foreach ($ovr_collect_data as $key => $value) {
									$received_val  = !empty($value->received_qty) ? $value->received_qty : '';
									$new_received_cou += $received_val;
								}
							}

							if ($product_qty == $new_received_cou) {
								$ord_data = array('production_status' => '2');
								$ord_whr  = array('id' => $order_auto_id);
								$ord_upt  = $this->order_model->orderDetails_update($ord_data, $ord_whr);
							}

							if ($insert) {
								$response['status']  = 1;
								$response['message'] = "Success";
								$response['data']    = [];
								echo json_encode($response);
								return;
							} else {
								$response['status']  = 0;
								$response['message'] = "Not Success";
								$response['data']    = [];
								echo json_encode($response);
								return;
							}
						} else {
							$response['status']  = 0;
							$response['message'] = "Invalid Stock";
							$response['data']    = [];
							echo json_encode($response);
							return;
						}
					} else {
						$response['status']  = 0;
						$response['message'] = "Invalid Quantity";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		}

		// List Stock Update
		// ***************************************************
		else if ($method == '_listOrderStockDetails') {
			$error = FALSE;
			$errors = array();
			$required = array('order_id', 'order_auto_id', 'product_id', 'type_id');
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
				$where_1 = array(
					'order_id'      => $order_id,
					'order_auto_id' => $order_auto_id,
					'product_id'    => $product_id,
					'type_id'       => $type_id,
					'published'     => '1',
					'status'        => '1',
				);

				$stock_details = $this->order_model->getOrderStockDetails($where_1);

				if ($stock_details) {
					$stock_list = [];
					foreach ($stock_details as $key => $value) {
						$stock_id      = !empty($value->id) ? $value->id : '';
						$product_unit  = !empty($value->product_unit) ? $value->product_unit : '';
						$received_qty  = !empty($value->received_qty) ? $value->received_qty : '';
						$received_date = !empty($value->received_date) ? $value->received_date : '';

						// Unit Type Details
						$where_1   = array('id' => $product_unit);
						$unit_det  = $this->commom_model->getUnit($where_1);
						$unit_name = isset($unit_det[0]->name) ? $unit_det[0]->name : '';

						$stock_list[] = array(
							'stock_id'      => $stock_id,
							'product_unit'  => $product_unit,
							'unit_name'     => $unit_name,
							'received_qty'  => $received_qty,
							'received_date' => $received_date,
						);
					}

					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = $stock_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		}

		// Delete Stock Update (Vendor)
		// ***************************************************
		else if ($method == '_deleteOrderStockDetails') {
			if (!empty($stock_id)) {
				$where_1 = array(
					'id'        => $stock_id,
					'published' => '1',
					'status'    => '1',
				);

				$stock_details = $this->order_model->getOrderStockDetails($where_1);

				$order_id      = !empty($stock_details[0]->order_id) ? $stock_details[0]->order_id : '';
				$order_auto_id = !empty($stock_details[0]->order_auto_id) ? $stock_details[0]->order_auto_id : '';
				$product_id    = !empty($stock_details[0]->product_id) ? $stock_details[0]->product_id : '';
				$type_id       = !empty($stock_details[0]->type_id) ? $stock_details[0]->type_id : '';
				$product_unit  = !empty($stock_details[0]->product_unit) ? $stock_details[0]->product_unit : '';
				$received_qty  = !empty($stock_details[0]->received_qty) ? $stock_details[0]->received_qty : '';

				// Product Type Stock Plus
				$where_2 = array('id' => $type_id, 'product_id' => $product_id);

				$productType_val = $this->commom_model->getProductType($where_2);

				$product_type    = !empty($productType_val[0]->product_type) ? $productType_val[0]->product_type : '';
				$typeStock       = !empty($productType_val[0]->type_stock) ? $productType_val[0]->type_stock : '0';
				$typeView_stock  = !empty($productType_val[0]->stock_detail) ? $productType_val[0]->stock_detail : '0';

				// View Stock
				if ($product_unit == 1) {
					$multiple_stk   = $received_qty * $product_type; // 5 X 1 = 5 Kg
					$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
					$received_stock = $received_qty; // 5 Kg
				} else if ($product_unit == 2) {
					$product_stock  = $received_qty * $product_type; // 5 X 100 = 500 Gram
					$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
					$received_stock = number_format($received_value, 2);
				} else {
					$product_stock  = $received_qty * $product_type; // 5 X 1 = 5 Nos
					$received_stock = $received_qty; // 5 Nos
				}


				// Stock Process
				$new_type_stock    = $typeStock + $received_stock;
				$new_type_view_stk = $typeView_stock + $product_stock;

				$type_data = array(
					'type_stock'   => $new_type_stock,
					'stock_detail' => $new_type_view_stk,
				);

				$type_whr = array('id' => $type_id);
				$update   = $this->commom_model->productType_update($type_data, $type_whr);

				// Order Details Stock Minus
				$where_3 = array('id' => $order_auto_id, 'order_id' => $order_id);
				$order_details = $this->order_model->getOrderDetails($where_3);
				$receive_qty   = !empty($order_details[0]->receive_qty) ? $order_details[0]->receive_qty : '0';

				// Stock Details
				$new_stock  = $receive_qty - $received_qty;
				$order_data = array(
					'receive_qty'       => $new_stock,
					'production_status' => '1',
				);
				$order_whr  = array('id' => $order_auto_id);
				$update_ord = $this->order_model->orderDetails_update($order_data, $order_whr);

				// Delete Stock List
				$data = array(
					'published' => '0',
				);

				$where  = array('id' => $stock_id);
				$delete = $this->order_model->orderStockDetails_delete($data, $where);

				if ($delete) {
					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		}

		// Detail Order Details (Distributor)
		// ***************************************************
		else if ($method == '_detailDistributorOrderDetails') {
			$error = FALSE;
			$errors = array();
			$required = array('order_id', 'order_auto_id');
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
				// Order Details
				$where_1 = array(
					'id'        => $order_auto_id,
					'order_id'  => $order_id,
					'published' => '1',
					'status'    => '1'
				);

				$column_1   = 'product_id, type_id, unit_val, order_qty, receive_qty';

				$order_data = $this->order_model->getOrderDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

				if ($order_data) {
					$product_id  = !empty($order_data[0]->product_id) ? $order_data[0]->product_id : '';
					$type_id     = !empty($order_data[0]->type_id) ? $order_data[0]->type_id : '';
					$unit_val    = !empty($order_data[0]->unit_val) ? $order_data[0]->unit_val : '';
					$order_qty   = !empty($order_data[0]->order_qty) ? $order_data[0]->order_qty : '0';
					$receive_qty = !empty($order_data[0]->receive_qty) ? $order_data[0]->receive_qty : '0';
					$bal_qty     = $order_qty - $receive_qty;

					// Bill Details
					$where_2 = array(
						'id'        => $order_id,
						'published' => '1',
						'status'    => '1'
					);

					$column_2  = 'order_no';
					$bill_data = $this->order_model->getOrder($where_2, '', '', 'result', '', '', '', '', $column_2);

					$order_no  = !empty($bill_data[0]->order_no) ? $bill_data[0]->order_no : '';

					// Product Details
					$where_3 = array(
						'id'        => $type_id,
						'published' => '1',
					);

					$column_3   = 'description';

					$productType_val = $this->commom_model->getProductType($where_3, '', '', 'result', '', '', '', '', $column_3);

					$description = !empty($productType_val[0]->description) ? $productType_val[0]->description : '';

					$product_details = array(
						'order_no'      => $order_no,
						'order_id'      => $order_id,
						'order_auto_id' => $order_auto_id,
						'product_id'    => $product_id,
						'type_id'       => $type_id,
						'unit_val'      => $unit_val,
						'description'   => $description,
						'bal_qty'       => $bal_qty,
					);

					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = $product_details;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		}

		// Delete Stock Update (Distributor)
		// ***************************************************
		else if ($method == '_deleteDistributorStockDetails') {
			$error = FALSE;
			$errors = array();
			$required = array('stock_id', 'distributor_id');
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
				$where_1 = array(
					'id'        => $stock_id,
					'published' => '1',
					'status'    => '1',
				);

				$stock_details = $this->order_model->getOrderStockDetails($where_1);

				$order_id      = !empty($stock_details[0]->order_id) ? $stock_details[0]->order_id : '';
				$order_auto_id = !empty($stock_details[0]->order_auto_id) ? $stock_details[0]->order_auto_id : '';
				$product_id    = !empty($stock_details[0]->product_id) ? $stock_details[0]->product_id : '';
				$type_id       = !empty($stock_details[0]->type_id) ? $stock_details[0]->type_id : '';
				$product_unit  = !empty($stock_details[0]->product_unit) ? $stock_details[0]->product_unit : '';
				$received_qty  = !empty($stock_details[0]->received_qty) ? $stock_details[0]->received_qty : '';

				// Product Type Stock Plus
				$where_2 = array('id' => $type_id, 'product_id' => $product_id);

				$productType_val = $this->commom_model->getProductType($where_2);

				$product_type    = !empty($productType_val[0]->product_type) ? $productType_val[0]->product_type : '';

				$where_3 = array(
					'id'        => $order_id,
					'published' => '1',
					'status'    => '1',
				);

				$column_3  = 'id, zone_id';

				$bill_data = $this->order_model->getOrder($where_3, '', '', 'result', '', '', '', '', $column_3);

				$zone_id   = !empty($bill_data[0]->zone_id) ? $bill_data[0]->zone_id : '';

				$where_4 = array(
					'distributor_id' => $distributor_id,
					'zone_id'        => $zone_id,
					'product_id'     => $product_id,
					'type_id'        => $type_id,
					'published'      => '1',
					'status'         => '1',
				);

				$ass_data = $this->assignproduct_model->getAssignProductDetails($where_4);

				$assPdt_id  = !empty($ass_data[0]->id) ? $ass_data[0]->id : '0';
				$stock      = !empty($ass_data[0]->stock) ? $ass_data[0]->stock : '0';
				$view_stock = !empty($ass_data[0]->view_stock) ? $ass_data[0]->view_stock : '0';

				// View Stock
				if ($product_unit == 1 || $product_unit == 11) {
					$multiple_stk   = $received_qty * $product_type; // 5 X 1 = 5 Kg
					$product_stock  = $multiple_stk * 1000; // 5 X 1000 = 5000 Gram
					$received_stock = $received_qty; // 5 Kg
				} else if ($product_unit == 2 || $product_unit == 4) {
					$product_stock  = $received_qty * $product_type; // 5 X 100 = 500 Gram
					$received_value = $product_stock / 1000; // 500 / 1000 = 0.50 Kg
					$received_stock = number_format($received_value, 2);
				} else {
					$product_stock  = $received_qty * $product_type; // 5 X 1 = 5 Nos
					$received_stock = $received_qty; // 5 Nos
				}

				// Stock Process
				$new_assign_stock    = $stock + $received_stock;
				$new_assign_view_stk = $view_stock + $product_stock;

				$assign_data = array(
					'stock'      => $new_assign_stock,
					'view_stock' => $new_assign_view_stk,
				);

				$assign_whr = array('id' => $assPdt_id);
				$update     = $this->assignproduct_model->assignProductDetails_update($assign_data, $assign_whr);

				// Order Details Stock Minus
				$where_5       = array('id' => $order_auto_id, 'order_id' => $order_id);
				$order_details = $this->order_model->getOrderDetails($where_5);
				$receive_qty   = !empty($order_details[0]->receive_qty) ? $order_details[0]->receive_qty : '0';

				// Order Stock Process
				$overColl_qty = $receive_qty - $received_qty;

				$produc_data = array(
					'receive_qty'       => strval($overColl_qty),
					'production_status' => '1',
				);

				$produc_whr   = array('id' => $order_auto_id);
				$update_prodc = $this->order_model->orderDetails_update($produc_data, $produc_whr);

				// Delete Stock List
				$data = array(
					'published' => '0',
				);

				$where  = array('id' => $stock_id);
				$delete = $this->order_model->orderStockDetails_delete($data, $where);

				if ($delete) {
					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
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

	// Vendors Manage Order 
	// ***************************************************
	public function vendor_manage_order($param1 = "", $param2 = "", $param3 = "")
	{
		$method       = $this->input->post('method');
		$random_value = $this->input->post('random_value');
		$vendor_id    = $this->input->post('vendor_id');
		$limit        = $this->input->post('limit');
		$offset       = $this->input->post('offset');
		$financ_year  = $this->input->post('financial_year');

		if ($method == '_listInvoiceVendorOrder') {
			$error = FALSE;
			$errors = array();
			$required = array('vendor_id', 'financial_year');
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
				$where = array(
					'tbl_order_details.order_status' => '5',
					'tbl_order_details.vendor_id'    => $vendor_id,
					// 'tbl_order.financial_year'       => $financ_year,
					'tbl_order.published'            => '1'
				);

				$column  = 'tbl_order.id, tbl_order.order_no, tbl_order.published, tbl_order.status';

				$groupby = 'tbl_order_details.order_id';

				$data_list = $this->order_model->getVendorOrder($where, '', '', 'result', '', '', '', '', $column, $groupby);

				if ($data_list) {
					$order_list = [];
					foreach ($data_list as $key => $value) {

						$order_id     = !empty($value->id) ? $value->id : '';
						$order_no     = !empty($value->order_no) ? $value->order_no : '';
						$published    = !empty($value->published) ? $value->published : '';
						$status       = !empty($value->status) ? $value->status : '';

						$order_list[] = array(
							'order_id'     => $order_id,
							'order_no'     => $order_no,
							'published'    => $published,
							'status'       => $status,
						);
					}

					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = $order_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		} else if ($method == '_listBothVendorOrder') {
			$error = FALSE;
			$errors = array();
			$required = array('vendor_id', 'financial_year');
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
				$where = array(
					'tbl_order_details.order_status' => '5,6',
					'tbl_order_details.vendor_id'    => $vendor_id,
					// 'tbl_order.financial_year'       => $financ_year,
					'tbl_order.published'            => '1'
				);

				$column  = 'tbl_order.id, tbl_order.order_no, tbl_order.published, tbl_order.status';

				$groupby = 'tbl_order_details.order_id';

				$data_list = $this->order_model->getVendorOrder($where, '', '', 'result', '', '', '', '', $column, $groupby);

				if ($data_list) {
					$order_list = [];
					foreach ($data_list as $key => $value) {

						$order_id     = !empty($value->id) ? $value->id : '';
						$order_no     = !empty($value->order_no) ? $value->order_no : '';
						$published    = !empty($value->published) ? $value->published : '';
						$status       = !empty($value->status) ? $value->status : '';

						$order_list[] = array(
							'order_id'     => $order_id,
							'order_no'     => $order_no,
							'published'    => $published,
							'status'       => $status,
						);
					}

					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = $order_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		} else if ($method == '_listVendorOrderPaginate') {
			$error = FALSE;
			$errors = array();
			$required = array('vendor_id', 'financial_year');
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
				if ($limit != '' && $offset != '') {
					$limit  = $limit;
					$offset = $offset;
				} else {
					$limit  = 10;
					$offset = 0;
				}

				$search    = $this->input->post('search');
				$load_data = $this->input->post('load_data');

				if ($search != '') {
					$like['order_no']     = $search;
					$like['emp_name']     = $search;
					$like['store_name']   = $search;
					$like['contact_name'] = $search;
				} else {
					$like = [];
				}

				if ($load_data != '') {
					$where = array(
						'tbl_order_details.order_status' => $load_data,
						'tbl_order_details.vendor_id'    => $vendor_id,
						// 'tbl_order.financial_year'       => $financ_year,
						'tbl_order.published'            => '1',
						'tbl_order_details.published'    => '1',
					);
				} else {
					$where = array(
						'tbl_order_details.order_status' => '2',
						'tbl_order_details.vendor_id'    => $vendor_id,
						// 'tbl_order.financial_year'       => $financ_year,
						'tbl_order.published'            => '1',
						'tbl_order_details.published'    => '1',
					);
				}

				$column  = 'tbl_order.id, tbl_order.order_no, tbl_order.emp_name, tbl_order.store_name, tbl_order.contact_name, tbl_order.due_days, tbl_order.discount, tbl_order.order_status, tbl_order._ordered, tbl_order._processing, tbl_order._packing, tbl_order._shiped, tbl_order._invoice, tbl_order._delivery, tbl_order._complete, tbl_order._canceled, tbl_order.financial_year, tbl_order.random_value, tbl_order.published, tbl_order.status, tbl_order.createdate, tbl_order_details.order_id, tbl_order_details.vendor_id, tbl_order_details.vendor_id';

				$groupby = 'tbl_order_details.order_id';

				$overalldatas = $this->order_model->getVendorOrder($where, '', '', 'result', $like, '', '', '', $column, $groupby);

				if ($overalldatas) {
					$totalc = count($overalldatas);
				} else {
					$totalc = 0;
				}

				$option['order_by']   = 'id';
				$option['disp_order'] = 'DESC';

				$data_list = $this->order_model->getVendorOrder($where, $limit, $offset, 'result', $like, '', $option, '', $column, $groupby);

				if ($data_list) {
					$order_list = [];
					foreach ($data_list as $key => $value) {

						$order_id     = !empty($value->id) ? $value->id : '';
						$order_no     = !empty($value->order_no) ? $value->order_no : '';
						$emp_name     = !empty($value->emp_name) ? $value->emp_name : 'Admin';
						$store_name   = !empty($value->store_name) ? $value->store_name : '';
						$contact_name = !empty($value->contact_name) ? $value->contact_name : '';
						$due_days     = !empty($value->due_days) ? $value->due_days : '';
						$discount     = !empty($value->discount) ? $value->discount : '';
						$order_status = !empty($value->order_status) ? $value->order_status : '';
						$_ordered     = !empty($value->_ordered) ? $value->_ordered : '';
						$_processing  = !empty($value->_processing) ? $value->_processing : '';
						$_packing     = !empty($value->_packing) ? $value->_packing : '';
						$_shiped      = !empty($value->_shiped) ? $value->_shiped : '';
						$_invoice     = !empty($value->_invoice) ? $value->_invoice : '';
						$_delivery    = !empty($value->_delivery) ? $value->_delivery : '';
						$_complete    = !empty($value->_complete) ? $value->_complete : '';
						$_canceled    = !empty($value->_canceled) ? $value->_canceled : '';
						$random_value = !empty($value->random_value) ? $value->random_value : '';
						$published    = !empty($value->published) ? $value->published : '';
						$status       = !empty($value->status) ? $value->status : '';
						$createdate   = !empty($value->createdate) ? $value->createdate : '';

						// Order Process Details
						$order_whr  = array(
							'order_id'  => $order_id,
							'vendor_id' => $vendor_id,
							'published' => '1',
							'status'    => '1',
						);

						$order_col  = 'order_status';
						$groupby    = 'order_status';

						$order_data = $this->order_model->getOrderDetails($order_whr, '', '', 'result', '', '', '', '', $order_col, $groupby);

						$ovrall_sta = !empty($order_data[0]->order_status) ? $order_data[0]->order_status : '';

						$order_list[] = array(
							'order_id'     => $order_id,
							'order_no'     => $order_no,
							'emp_name'     => $emp_name,
							'store_name'   => $store_name,
							'contact_name' => $contact_name,
							'due_days'     => $due_days,
							'discount'     => $discount,
							'order_status' => $ovrall_sta,
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

					if ($offset != '' && $limit != '') {
						$offset = $offset + $limit;
						$limit  = $limit;
					} else {
						$offset = $limit;
						$limit  = 10;
					}

					$response['status']       = 1;
					$response['message']      = "Success";
					$response['total_record'] = $totalc;
					$response['offset']       = (int)$offset;
					$response['limit']        = (int)$limit;
					$response['data']         = $order_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		} else if ($method == '_detailVendorOrder') {
			$error = FALSE;
			$errors = array();
			$required = array('vendor_id', 'random_value');
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
				$where = array(
					'random_value'  => $random_value,
				);

				$data_value = $this->order_model->getOrder($where);

				if ($data_value) {
					$data_val     = $data_value[0];
					$order_id     = !empty($data_val->id) ? $data_val->id : '';
					$order_no     = !empty($data_val->order_no) ? $data_val->order_no : '';
					$bill_type    = !empty($data_val->bill_type) ? $data_val->bill_type : '';
					$emp_name     = !empty($data_val->emp_name) ? $data_val->emp_name : 'Admin';
					$store_id     = !empty($data_val->store_id) ? $data_val->store_id : '';
					$store_name   = !empty($data_val->store_name) ? $data_val->store_name : '';
					$contact_name = !empty($data_val->contact_name) ? $data_val->contact_name : '';
					$due_days     = !empty($value->due_days) ? $value->due_days : '';
					$discount     = !empty($value->discount) ? $value->discount : '';
					$order_status = !empty($data_val->order_status) ? $data_val->order_status : '';
					$_ordered     = !empty($data_val->_ordered) ? $data_val->_ordered : '';
					$_processing  = !empty($data_val->_processing) ? $data_val->_processing : '';
					$_packing     = !empty($data_val->_packing) ? $data_val->_packing : '';
					$_shiped      = !empty($data_val->_shiped) ? $data_val->_shiped : '';
					$_invoice     = !empty($data_val->_invoice) ? $data_val->_invoice : '';
					$_delivery    = !empty($data_val->_delivery) ? $data_val->_delivery : '';
					$_complete    = !empty($data_val->_complete) ? $data_val->_complete : '';
					$_canceled    = !empty($data_val->_canceled) ? $data_val->_canceled : '';
					$published    = !empty($data_val->published) ? $data_val->published : '';
					$status       = !empty($data_val->status) ? $data_val->status : '';
					$createdate   = !empty($data_val->createdate) ? $data_val->createdate : '';

					// Order Process Details
					$order_whr  = array(
						'order_id'  => $order_id,
						'vendor_id' => $vendor_id,
						'published' => '1',
						'status'    => '1',
					);

					$order_col  = 'order_status';
					$groupby    = 'order_status';

					$order_data = $this->order_model->getOrderDetails($order_whr, '', '', 'result', '', '', '', '', $order_col, $groupby);

					$ovrall_sta = !empty($order_data[0]->order_status) ? $order_data[0]->order_status : '';

					// Bill Details
					$bill_details = array(
						'order_id'     => $order_id,
						'order_no'     => $order_no,
						'bill_type'    => $bill_type,
						'emp_name'     => $emp_name,
						'store_id'     => $store_id,
						'store_name'   => $store_name,
						'contact_name' => $contact_name,
						'due_days'     => $due_days,
						'discount'     => $discount,
						'order_status' => $ovrall_sta,
						'_ordered'     => $_ordered,
						'_processing'  => $_processing,
						'_packing'     => $_packing,
						'_shiped'      => $_shiped,
						'_invoice'     => $_invoice,
						'_delivery'    => $_delivery,
						'_complete'    => $_complete,
						'_canceled'    => $_canceled,
						'published'    => $published,
						'status'       => $status,
						'createdate'   => $createdate,
					);

					// Vendor Details
					$out_col = 'company_name, gst_no, contact_no, email, address, state_id, city_id, vendor_type';

					$vdr_whr  = array(
						'id'        => $vendor_id,
						'published' => '1',
						'status'    => '1'
					);

					$vdr_data = $this->vendors_model->getVendors($vdr_whr, '', '', 'result', '', '', '', '', $out_col);

					$vendor_details = [];
					if (!empty($vdr_data)) {
						$vdr_val  = $vdr_data[0];

						$vdr_company_name = !empty($vdr_val->company_name) ? $vdr_val->company_name : '';
						$vdr_gst_no       = !empty($vdr_val->gst_no) ? $vdr_val->gst_no : '';
						$vdr_contact_no   = !empty($vdr_val->contact_no) ? $vdr_val->contact_no : '';
						$vdr_email        = !empty($vdr_val->email) ? $vdr_val->email : '';
						$vdr_address      = !empty($vdr_val->address) ? $vdr_val->address : '';
						$vdr_state_id     = !empty($vdr_val->state_id) ? $vdr_val->state_id : '';
						$vdr_city_id      = !empty($vdr_val->city_id) ? $vdr_val->city_id : '';
						$vendor_type      = !empty($vdr_val->vendor_type) ? $vdr_val->vendor_type : '';

						$vendor_details = array(
							'company_name' => $vdr_company_name,
							'gst_no'       => $vdr_gst_no,
							'contact_no'   => $vdr_contact_no,
							'email'        => $vdr_email,
							'address'      => $vdr_address,
							'state_id'     => $vdr_state_id,
							'city_id'      => $vdr_city_id,
							'vendor_type'  => $vendor_type,
						);
					}

					// Outlet Details
					$out_col = 'company_name, contact_name, mobile, email, gst_no, address, country_id, state_id, city_id, zone_id, outlet_type';

					$out_whr = array(
						'id' => $store_id,
					);

					$out_data = $this->outlets_model->getOutlets($out_whr, '', '', 'result', '', '', '', '', $out_col);

					$store_details = [];
					if (!empty($out_data)) {
						$out_val      = $out_data[0];
						$company_name = !empty($out_val->company_name) ? $out_val->company_name : '';
						$contact_name = !empty($out_val->contact_name) ? $out_val->contact_name : '';
						$mobile       = !empty($out_val->mobile) ? $out_val->mobile : '';
						$email        = !empty($out_val->email) ? $out_val->email : '';
						$gst_no       = !empty($out_val->gst_no) ? $out_val->gst_no : '';
						$address      = !empty($out_val->address) ? $out_val->address : '';
						$country_id   = !empty($out_val->country_id) ? $out_val->country_id : '';
						$state_id     = !empty($out_val->state_id) ? $out_val->state_id : '';
						$city_id      = !empty($out_val->city_id) ? $out_val->city_id : '';
						$zone_id      = !empty($out_val->zone_id) ? $out_val->zone_id : '';
						$outlet_type  = !empty($out_val->outlet_type) ? $out_val->outlet_type : '';

						// State Details
						$state_whr  = array('id' => $state_id);
						$state_data = $this->commom_model->getState($state_whr);

						$state_val  = $state_data[0];
						$state_name = !empty($state_val->state_name) ? $state_val->state_name : '';
						$gst_code   = !empty($state_val->gst_code) ? $state_val->gst_code : '';

						$store_details = array(
							'company_name' => $company_name,
							'contact_name' => $contact_name,
							'mobile'       => $mobile,
							'email'        => $email,
							'gst_no'       => $gst_no,
							'address'      => $address,
							'country_id'   => $country_id,
							'state_id'     => $state_id,
							'city_id'      => $city_id,
							'zone_id'      => $zone_id,
							'state_name'   => $state_name,
							'gst_code'     => $gst_code,
							'outlet_type'  => $outlet_type,
						);
					}

					// Order Details
					$ord_whr = array(
						'order_id'  => $order_id,
						'vendor_id' => $vendor_id,
						'published' => '1',
					);

					$order_details = $this->order_model->getOrderDetails($ord_whr);

					$product_details = [];
					if (!empty($order_details)) {
						foreach ($order_details as $key => $value) {

							$auto_id     = !empty($value->id) ? $value->id : '';
							$order_id    = !empty($value->order_id) ? $value->order_id : '';
							$order_no    = !empty($value->order_no) ? $value->order_no : '';
							$product_id  = !empty($value->product_id) ? $value->product_id : '';
							$type_id     = !empty($value->type_id) ? $value->type_id : '';
							$hsn_code    = !empty($value->hsn_code) ? $value->hsn_code : '';
							$gst_val     = !empty($value->gst_val) ? $value->gst_val : '';
							$unit_val    = !empty($value->unit_val) ? $value->unit_val : '';
							$price       = !empty($value->price) ? $value->price : '';
							$order_qty   = !empty($value->order_qty) ? $value->order_qty : '';
							$receive_qty = !empty($value->receive_qty) ? $value->receive_qty : '';
							$invoice_val = !empty($value->invoice_value) ? $value->invoice_value : '';
							$published   = !empty($value->published) ? $value->published : '';
							$status      = !empty($value->status) ? $value->status : '';
							$createdate  = !empty($value->createdate) ? $value->createdate : '';

							// Product Details
							$where_1      = array('id' => $product_id);
							$product_det  = $this->commom_model->getProduct($where_1);
							$product_name = isset($product_det[0]->name) ? $product_det[0]->name : '';

							// Unit Type Details
							$where_2   = array('id' => $unit_val);
							$unit_det  = $this->commom_model->getUnit($where_2);
							$unit_name = isset($unit_det[0]->name) ? $unit_det[0]->name : '';

							// Product Type Details
							$where_3     = array('id' => $type_id);
							$type_det    = $this->commom_model->getProductType($where_3);
							$description = isset($type_det[0]->description) ? $type_det[0]->description : '';
							$type_name   = isset($type_det[0]->product_type) ? $type_det[0]->product_type : '';

							$product_details[] = array(
								'auto_id'       => $auto_id,
								'order_id'      => $order_id,
								'order_no'      => $order_no,
								'product_id'    => $product_id,
								'product_name'  => $product_name,
								'type_id'       => $type_id,
								'type_name'     => $type_name,
								'description'   => $description,
								'hsn_code'      => $hsn_code,
								'gst_val'       => $gst_val,
								'unit_val'      => $unit_val,
								'unit_name'     => $unit_name,
								'price'         => $price,
								'order_qty'     => $order_qty,
								'receive_qty'   => $receive_qty,
								'invoice_value' => $invoice_val,
								'published'     => $published,
								'status'        => $status,
								'createdate'    => $createdate,
							);
						}
					}

					// Tax Details
					$tax_where = array(
						'order_id'  => $order_id,
						'vendor_id' => $vendor_id,
						'published' => '1',
						'status'    => '1',
					);

					$tax_column = 'hsn_code, gst_val';

					$groupby = 'hsn_code';

					$tax_data   = $this->order_model->getOrderDetails($tax_where, '', '', 'result', '', '', '', '', $tax_column, $groupby);

					$tax_details = [];
					if (!empty($tax_data)) {
						foreach ($tax_data as $key => $value) {

							$hsn_code = !empty($value->hsn_code) ? $value->hsn_code : '';
							$gst_val  = !empty($value->gst_val) ? $value->gst_val : '';

							// Price Details
							$price_where = array(
								'order_id'  => $order_id,
								'hsn_code'  => $hsn_code,
								'published' => '1',
								'status'    => '1',
							);

							$price_column = 'price';

							$price_data   = $this->order_model->getOrderDetails($price_where, '', '', 'result', '', '', '', '', $price_column);

							$product_price = 0;
							foreach ($price_data as $key => $price_val) {
								$price = !empty($price_val->price) ? $price_val->price : '';

								$product_price += $price;
							}

							$tax_details[] = array(
								'hsn_code'  => $hsn_code,
								'gst_val'   => $gst_val,
								'price_val' => strval($product_price),
							);
						}
					}

					$order_details = array(
						'bill_details'        => $bill_details,
						'distributor_details' => $vendor_details,
						'store_details'       => $store_details,
						'product_details'     => $product_details,
						'tax_details'         => $tax_details,
					);

					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = $order_details;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		} else {
			$response['status']  = 0;
			$response['message'] = "Error";
			$response['data']    = [];
			echo json_encode($response);
			return;
		}
	}

	// Distributors Manage Order
	// ***************************************************
	public function distributor_manage_order($param1 = "", $param2 = "", $param3 = "")
	{
		$method         = $this->input->post('method');
		$random_value   = $this->input->post('random_value');
		$distributor_id = $this->input->post('distributor_id');
		$limit          = $this->input->post('limit');
		$offset         = $this->input->post('offset');
		$financ_year    = $this->input->post('financial_year');
		$search         = $this->input->post('search');
		$load_data      = $this->input->post('load_data');
		$start_date     = $this->input->post('start_date');
		$end_date       = $this->input->post('end_date');

		if ($method == '_listDistributorOrderPaginate') {
			$error = FALSE;
			$errors = array();
			$required = array('distributor_id', 'financial_year');
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
				$start_value = date('Y-m-d H:i:s', strtotime($start_date . '00:00:00'));
				$end_value   = date('Y-m-d H:i:s', strtotime($end_date . '23:59:59'));

				// Distributor Product Details
				$column_1 = 'type_id,grade';

				$where_1  = array(
					'id'  => $distributor_id,
				);

				$data_1   = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column_1);

				if (!empty($data_1)) {
					$result_1 = $data_1[0];
					$type_id  = !empty($result_1->type_id) ? $result_1->type_id : '';
					$grade  = !empty($result_1->grade) ? $result_1->grade : '';

					if ($load_data != '') {
						$where_2 = array(
							'tbl_order_details.order_status'    => $load_data,
							// 'tbl_order.financial_year'          => $financ_year,
							'tbl_order_details.delete_status'   => '1',
							'tbl_order.published'               => '1',
							'tbl_order_details.published'       => '1',
							'tbl_order_details.type_id'         => $type_id,
							'tbl_order_details.vendor_type != ' => '1',
							'tbl_order_details.start_date'      => $start_value,
							'tbl_order_details.end_date'        => $end_value,
						);
					} else {
						$where_2 = array(
							'tbl_order_details.order_status'    => '2,3,4,5,6,9',
							// 'tbl_order.financial_year'          => $financ_year,
							'tbl_order_details.delete_status'   => '1',
							'tbl_order.published'               => '1',
							'tbl_order_details.published'       => '1',
							'tbl_order_details.type_id'         => $type_id,
							'tbl_order_details.vendor_type != ' => '1',
							'tbl_order_details.start_date'      => $start_value,
							'tbl_order_details.end_date'        => $end_value,
						);
					}

					$column_2  = 'tbl_order_details.order_id, tbl_order_details.zone_id, tbl_order_details.type_id';

					$group_2   = 'tbl_order_details.order_id';

					$data_2    = $this->order_model->getDistributorOrder($where_2, '', '', 'result', '', '', '', '', $column_2, $group_2);

					if (!empty($data_2)) {
						$order_value = '';
						foreach ($data_2 as $key => $val_2) {
							$order_val = !empty($val_2->order_id) ? $val_2->order_id : '';
							$zone_val  = !empty($val_2->zone_id) ? $val_2->zone_id : '';
							$type_val  = !empty($val_2->type_id) ? $val_2->type_id : '';

							if ($grade == 1) {


								$whe_3 = array(

									'type_id'        => $type_val,
									'zone_id'        => $zone_val,
									'status'         => '1',
									'published'      => '1'
								);

								$col_3 = 'id';

								$data = $this->assignproduct_model->getAssignProductDetails($whe_3, '', '', 'result', '', '', '', '', $col_3);
								

								if (!empty($data)) {


									if (count($data) == 1) {
										$where_3 = array(
											'distributor_id' => $distributor_id,
											'type_id'        => $type_val,
											'zone_id'        => $zone_val,
											'status'         => '1',
											'published'      => '1'
										);

										$column_3 = 'id';

										$assign_data = $this->assignproduct_model->getAssignProductDetails($where_3, '', '', 'result', '', '', '', '', $column_3);
									} else if (count($data) == 2) {
										$assign_data = '';
									} else {
										$assign_data = '';
									}
								}
							} else {
								
								$where_3 = array(
									'distributor_id' => $distributor_id,
									'type_id'        => $type_val,
									'zone_id'        => $zone_val,
									'status'         => '1',
									'published'      => '1'
								);

								$column_3 = 'id';

								$assign_data = $this->assignproduct_model->getAssignProductDetails($where_3, '', '', 'result', '', '', '', '', $column_3);
							}
							if (!empty($assign_data)) {
								$order_value .= $order_val . ',';
							}
						}

						$order_list = substr_replace($order_value, '', -1);

						if (!empty($order_list)) {
							if ($search != '') {
								$like['name'] = $search;
							} else {
								$like = [];
							}

							if ($load_data != '') {
								$where = array(
									'tbl_order.id'                      => $order_list,
									'tbl_order_details.order_status'    => $load_data,
									// 'tbl_order.financial_year'          => $financ_year,
									'tbl_order_details.delete_status'   => '1',
									'tbl_order.published'               => '1',
									'tbl_order_details.published'       => '1',
									'tbl_order_details.vendor_type != ' => '1',
									'tbl_order_details.start_date'      => $start_value,
									'tbl_order_details.end_date'        => $end_value,
								);
							} else {
								$where = array(
									'tbl_order.id'                      => $order_list,
									'tbl_order_details.order_status'    => '2,3,4,5,6,9',
									// 'tbl_order.financial_year'          => $financ_year,
									'tbl_order_details.delete_status'   => '1',
									'tbl_order.published'               => '1',
									'tbl_order_details.published'       => '1',
									'tbl_order_details.vendor_type != ' => '1',
									'tbl_order_details.start_date'      => $start_value,
									'tbl_order_details.end_date'        => $end_value,
								);
							}

							$column  = 'tbl_order.id, tbl_order.order_no, tbl_order.emp_name, tbl_order.store_name, tbl_order.zone_id, tbl_order.contact_name, tbl_order.due_days, tbl_order.discount, tbl_order.order_status, tbl_order._ordered, tbl_order._processing, tbl_order._packing, tbl_order._shiped, tbl_order._delivery, tbl_order._complete, tbl_order._canceled, tbl_order.financial_year, tbl_order.random_value, tbl_order.published, tbl_order.status, tbl_order.createdate, tbl_order_details.order_id, tbl_order_details.vendor_id, tbl_order_details.invoice_value';

							$groupby = 'tbl_order_details.order_id';
							$col_1   = 'tbl_order.id';

							$overalldatas = $this->order_model->getDistributorOrder($where, '', '', 'result', $like, '', '', '', $col_1, $groupby);

							if ($overalldatas) {
								$totalc = count($overalldatas);
							} else {
								$totalc = 0;
							}

							$option['order_by']   = 'id';
							$option['disp_order'] = 'DESC';

							$data_list = $this->order_model->getDistributorOrder($where, $limit, $offset, 'result', $like, '', $option, '', $column, $groupby);

							if ($data_list) {
								$order_list = [];
								foreach ($data_list as $key => $value) {

									$order_id     = !empty($value->id) ? $value->id : '';
									$order_no     = !empty($value->order_no) ? $value->order_no : '';
									$emp_name     = !empty($value->emp_name) ? $value->emp_name : 'Admin';
									$store_name   = !empty($value->store_name) ? $value->store_name : '';
									$zone_value   = !empty($value->zone_id) ? $value->zone_id : '';
									$contact_name = !empty($value->contact_name) ? $value->contact_name : '';
									$due_days     = !empty($value->due_days) ? $value->due_days : '0';
									$discount     = !empty($value->discount) ? $value->discount : '0';
									$_ordered     = !empty($value->_ordered) ? $value->_ordered : '';
									$_processing  = !empty($value->_processing) ? $value->_processing : '';
									$_packing     = !empty($value->_packing) ? $value->_packing : '';
									$_shiped      = !empty($value->_shiped) ? $value->_shiped : '';
									$_delivery    = !empty($value->_delivery) ? $value->_delivery : '';
									$_complete    = !empty($value->_complete) ? $value->_complete : '';
									$_canceled    = !empty($value->_canceled) ? $value->_canceled : '';
									$random_value = !empty($value->random_value) ? $value->random_value : '';
									$published    = !empty($value->published) ? $value->published : '';
									$status       = !empty($value->status) ? $value->status : '';
									$createdate   = !empty($value->createdate) ? $value->createdate : '';
									$invoice_val  = !empty($value->invoice_value) ? $value->invoice_value : '';

									// Order Status
									$order_data = '';

									$ord_whr = array(
										'tbl_order_details.order_id'        => $order_id,
										'tbl_order_details.delete_status'   => '1',
										'tbl_order_details.published'       => '1',
										'tbl_order_details.type_id'         => $type_id,
										'tbl_order_details.vendor_type != ' => '1',
										'tbl_order_details.start_date'      => $start_value,
										'tbl_order_details.end_date'        => $end_value,
									);

									$ord_col = 'tbl_order_details.id, tbl_order_details.type_id';

									$ord_list  = $this->order_model->getDistributorOrder($ord_whr, '', '', 'result', '', '', '', '', $ord_col);

									if (!empty($ord_list)) {
										foreach ($ord_list as $key => $val) {

											$ord_value  = !empty($val->id) ? $val->id : '';
											$type_value = !empty($val->type_id) ? $val->type_id : '';

											$where_4 = array(
												'distributor_id' => $distributor_id,
												'type_id'        => $type_value,
												'zone_id'        => $zone_value,
												'status'         => '1',
												'published'      => '1'
											);

											$column_4    = 'id';
											$assign_data = $this->assignproduct_model->getAssignProductDetails($where_4, '', '', 'result', '', '', '', '', $column_4);

											if (!empty($assign_data)) {
												$order_data .= $ord_value . ',';
											}
										}
									}

									$order_res = substr($order_data, 0, -1);

									if($order_res)
									{
										$ord_whr = array(
											'tbl_order.id'                    => $order_id,
											'tbl_order_details.id'            => $order_res,
											'tbl_order_details.delete_status' => '1',
											'tbl_order_details.published'     => '1',
											'tbl_order_details.start_date'    => $start_value,
											'tbl_order_details.end_date'      => $end_value,
										);

										$ord_col  = 'tbl_order_details.order_status';
										$groupby  = 'tbl_order_details.order_status';
										$sta_data = $this->order_model->getDistributorOrder($ord_whr, '', '', 'result', '', '', '', '', $ord_col, $groupby);

										$sta_val  = !empty($sta_data[0]->order_status) ? $sta_data[0]->order_status : '';
									}
									else
									{
										$sta_val  = '---';
									}
										

									// Invoice Details
									$inv_val = '---';

									if (!empty($invoice_val)) {
										$inv_whr = array('random_value' => $invoice_val);
										$inv_col = 'invoice_no';
										$inv_res = $this->invoice_model->getInvoice($inv_whr, '', '', 'result', '', '', '', '', $inv_col);
										$inv_val = !empty($inv_res[0]->invoice_no) ? $inv_res[0]->invoice_no : '';
									}

									$order_list[] = array(
										'order_id'     => $order_id,
										'order_no'     => $order_no,
										'emp_name'     => $emp_name,
										'store_name'   => $store_name,
										'contact_name' => $contact_name,
										'due_days'     => $due_days,
										'discount'     => $discount,
										'order_status' => ($load_data)?$load_data:$sta_val,
										'_ordered'     => $_ordered,
										'_processing'  => $_processing,
										'_packing'     => $_packing,
										'_shiped'      => $_shiped,
										'_delivery'    => $_delivery,
										'_complete'    => $_complete,
										'_canceled'    => $_canceled,
										'random_value' => $random_value,
										'inv_val'      => $inv_val,
										'published'    => $published,
										'status'       => $status,
										'createdate'   => $createdate,
									);
								}

								if ($offset != '' && $limit != '') {
									$offset = $offset + $limit;
									$limit  = $limit;
								} else {
									$offset = $limit;
									$limit  = 10;
								}

								$response['status']       = 1;
								$response['message']      = "Success";
								$response['total_record'] = $totalc;
								$response['offset']       = (int)$offset;
								$response['limit']        = (int)$limit;
								$response['data']         = $order_list;
								echo json_encode($response);
								return;
							} else {
								$response['status']  = 0;
								$response['message'] = "Data Not Found";
								$response['data']    = [];
								echo json_encode($response);
								return;
							}
						} else {
							$response['status']  = 0;
							$response['message'] = "Data Not Found";
							$response['data']    = [];
							echo json_encode($response);
							return;
						}
					} else {
						$response['status']  = 0;
						$response['message'] = "Data Not Found";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				} else {
					$response['status']  = 0;
					$response['message'] = "Data Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		} 

		else if ($method == '_detailDistributorOrder') {
			$error = FALSE;
			$errors = array();
			$required = array('distributor_id', 'random_value', 'load_data');
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
				$where = array(
					'random_value'  => $random_value,
				);

				$data_value = $this->order_model->getOrder($where);

				if ($data_value) {
					$data_val = $data_value[0];

					$order_id     = !empty($data_val->id) ? $data_val->id : '';
					$order_no     = !empty($data_val->order_no) ? $data_val->order_no : '';
					$bill_type    = !empty($data_val->bill_type) ? $data_val->bill_type : '';
					$emp_name     = !empty($data_val->emp_name) ? $data_val->emp_name : 'Admin';
					$store_id     = !empty($data_val->store_id) ? $data_val->store_id : '';
					$zone_id      = !empty($data_val->zone_id) ? $data_val->zone_id : '';
					$store_name   = !empty($data_val->store_name) ? $data_val->store_name : '';
					$contact_name = !empty($data_val->contact_name) ? $data_val->contact_name : '';
					$zone_value   = !empty($data_val->zone_id) ? $data_val->zone_id : '';
					$due_days     = !empty($data_val->due_days) ? $data_val->due_days : '0';
					$discount     = !empty($data_val->discount) ? $data_val->discount : '0';
					$order_status = !empty($data_val->order_status) ? $data_val->order_status : '';
					$_ordered     = !empty($data_val->_ordered) ? $data_val->_ordered : '';
					$_processing  = !empty($data_val->_processing) ? $data_val->_processing : '';
					$_packing     = !empty($data_val->_packing) ? $data_val->_packing : '';
					$_shiped      = !empty($data_val->_shiped) ? $data_val->_shiped : '';
					$_delivery    = !empty($data_val->_delivery) ? $data_val->_delivery : '';
					$_complete    = !empty($data_val->_complete) ? $data_val->_complete : '';
					$_canceled    = !empty($data_val->_canceled) ? $data_val->_canceled : '';
					$published    = !empty($data_val->published) ? $data_val->published : '';
					$status       = !empty($data_val->status) ? $data_val->status : '';
					$createdate   = !empty($data_val->createdate) ? $data_val->createdate : '';

					// Distributor Product Details
					$typ_col = 'type_id';
					$typ_whr = array('id' => $distributor_id);

					$data_1   = $this->distributors_model->getDistributors($typ_whr, '', '', 'result', '', '', '', '', $typ_col);

					$result_1 = $data_1[0];
					$type_val = !empty($result_1->type_id) ? $result_1->type_id : '';

					// Order Status
					$order_data = '';

					$ord_whr = array(
						'tbl_order_details.order_id'        => $order_id,
						'tbl_order_details.delete_status'   => '1',
						'tbl_order_details.published'       => '1',
						'tbl_order_details.type_id'         => $type_val,
						'tbl_order_details.vendor_type != ' => '1'
					);

					$ord_col = 'tbl_order_details.id, tbl_order_details.type_id';

					$ord_list  = $this->order_model->getDistributorOrder($ord_whr, '', '', 'result', '', '', '', '', $ord_col);

					if (!empty($ord_list)) {
						foreach ($ord_list as $key => $val) {
							$ord_value  = !empty($val->id) ? $val->id : '';
							$type_value = !empty($val->type_id) ? $val->type_id : '';

							$where_4 = array(
								'distributor_id' => $distributor_id,
								'type_id'        => $type_value,
								'zone_id'        => $zone_value,
								'status'         => '1',
								'published'      => '1'
							);

							$column_4    = 'id';
							$assign_data = $this->assignproduct_model->getAssignProductDetails($where_4, '', '', 'result', '', '', '', '', $column_4);

							if (!empty($assign_data)) {
								$order_data .= $ord_value . ',';
							}
						}
					}

					$order_res = substr($order_data, 0, -1);

					$ord_whr = array(
						'tbl_order.id'                    => $order_id,
						'tbl_order_details.id'            => $order_res,
						'tbl_order_details.delete_status' => '1',
						'tbl_order_details.published'     => '1',
					);

					$ord_col  = 'tbl_order_details.order_status';
					$groupby  = 'tbl_order_details.order_status';
					$sta_data = $this->order_model->getDistributorOrder($ord_whr, '', '', 'result', '', '', '', '', $ord_col, $groupby);

					$sta_val  = !empty($sta_data[0]->order_status) ? $sta_data[0]->order_status : '';

					// Bill Details
					$bill_details = array(
						'order_id'     => $order_id,
						'order_no'     => $order_no,
						'bill_type'    => $bill_type,
						'emp_name'     => $emp_name,
						'store_id'     => $store_id,
						'store_name'   => $store_name,
						'contact_name' => $contact_name,
						'zone_value'   => $zone_value,
						'due_days'     => $due_days,
						'discount'     => $discount,
						'order_status' => ($load_data)?$load_data:$sta_val,
						'_ordered'     => $_ordered,
						'_processing'  => $_processing,
						'_packing'     => $_packing,
						'_shiped'      => $_shiped,
						'_delivery'    => $_delivery,
						'_complete'    => $_complete,
						'_canceled'    => $_canceled,
						'published'    => $published,
						'status'       => $status,
						'createdate'   => $createdate,
					);

					// Outlet Details
					$out_col = 'company_name, contact_name, mobile, email, gst_no, address, country_id, state_id, city_id, zone_id, outlet_type';

					$out_whr = array(
						'id' => $store_id,
					);

					$out_data = $this->outlets_model->getOutlets($out_whr, '', '', 'result', '', '', '', '', $out_col);

					$store_details = [];
					if (!empty($out_data)) {
						$out_val      = $out_data[0];
						$company_name = !empty($out_val->company_name) ? $out_val->company_name : '';
						$contact_name = !empty($out_val->contact_name) ? $out_val->contact_name : '';
						$mobile       = !empty($out_val->mobile) ? $out_val->mobile : '';
						$email        = !empty($out_val->email) ? $out_val->email : '';
						$gst_no       = !empty($out_val->gst_no) ? $out_val->gst_no : '';
						$address      = !empty($out_val->address) ? $out_val->address : '';
						$country_id   = !empty($out_val->country_id) ? $out_val->country_id : '';
						$state_id     = !empty($out_val->state_id) ? $out_val->state_id : '';
						$city_id      = !empty($out_val->city_id) ? $out_val->city_id : '';
						$zone_id      = !empty($out_val->zone_id) ? $out_val->zone_id : '';
						$outlet_type  = !empty($out_val->outlet_type) ? $out_val->outlet_type : '';

						// State Details
						$state_whr  = array('id' => $state_id);
						$state_data = $this->commom_model->getState($state_whr);

						$state_val  = $state_data[0];
						$state_name = !empty($state_val->state_name) ? $state_val->state_name : '';
						$gst_code   = !empty($state_val->gst_code) ? $state_val->gst_code : '';

						$store_details = array(
							'company_name' => $company_name,
							'contact_name' => $contact_name,
							'mobile'       => $mobile,
							'email'        => $email,
							'gst_no'       => $gst_no,
							'address'      => $address,
							'country_id'   => $country_id,
							'state_id'     => $state_id,
							'city_id'      => $city_id,
							'zone_id'      => $zone_id,
							'state_name'   => $state_name,
							'gst_code'     => $gst_code,
							'outlet_type'  => $outlet_type,
						);
					}

					// Distributors Details
					$distri_col = 'company_name, gst_no, mobile, email, state_id, city_id, address';

					$distri_whr = array(
						'id'        => $distributor_id,
						'published' => '1',
					);

					$distri_data = $this->distributors_model->getDistributors($distri_whr, '', '', 'result', '', '', '', '', $distri_col);

					$distributor_details = [];
					if (!empty($distri_data)) {
						$distri_val  = $distri_data[0];

						$distri_name     = !empty($distri_val->company_name) ? $distri_val->company_name : '';
						$distri_gst_no   = !empty($distri_val->gst_no) ? $distri_val->gst_no : '';
						$distri_mobile   = !empty($distri_val->mobile) ? $distri_val->mobile : '';
						$distri_email    = !empty($distri_val->email) ? $distri_val->email : '';
						$distri_state_id = !empty($distri_val->state_id) ? $distri_val->state_id : '';
						$distri_city_id  = !empty($distri_val->city_id) ? $distri_val->city_id : '';
						$distri_address  = !empty($distri_val->address) ? $distri_val->address : '';

						$distributor_details = array(
							'company_name' => $distri_name,
							'gst_no'       => $distri_gst_no,
							'contact_no'   => $distri_mobile,
							'email'        => $distri_email,
							'address'      => $distri_address,
							'state_id'     => $distri_state_id,
							'city_id'      => $distri_city_id,
							'vendor_type'  => '',
						);
					}

					// Product Details
					// ******************************************

					// Distributor Product Details
					$column_1 = 'type_id';

					$where_1  = array(
						'id'  => $distributor_id,
					);

					$data_1   = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column_1);

					$result_1 = $data_1[0];
					$type_id  = !empty($result_1->type_id) ? $result_1->type_id : '';

					$ord_whr = array(
						'tbl_order_details.order_id'        => $order_id,
						'tbl_order_details.delete_status'   => '1',
						'tbl_order_details.published'       => '1',
						'tbl_order_details.type_id'         => $type_id,
						'tbl_order_details.product_process' => ($load_data)?$load_data:$sta_val,
						'tbl_order_details.vendor_type != ' => '1'
					);

					$ord_col = 'tbl_order_details.id, tbl_order_details.order_id, tbl_order_details.order_no, tbl_order_details.product_id, tbl_order_details.type_id, tbl_order_details.hsn_code, tbl_order_details.gst_val, tbl_order_details.unit_val, tbl_order_details.price, tbl_order_details.order_qty, tbl_order_details.receive_qty, tbl_order_details.invoice_value, tbl_order_details.pack_status, tbl_order_details.delete_status, tbl_order_details.published, tbl_order_details.published, tbl_order_details.status, tbl_order_details.createdate';

					$order_list  = $this->order_model->getDistributorOrder($ord_whr, '', '', 'result', '', '', '', '', $ord_col);

					$invoice_res = !empty($order_list[0]->invoice_value) ? $order_list[0]->invoice_value : '';

					// Invoice details
					$einvoicepdf = '';
					$ewaybillpdf = '';

					if($invoice_res != '')
					{
						$inv_whr = array('random_value' => $invoice_res);
						$inv_col = 'einvoicepdf, ewaybillpdf';
						$inv_res = $this->invoice_model->getInvoice($inv_whr, '', '', 'row', '', '', '', '', $inv_col);

						$einvoicepdf = ($inv_res->einvoicepdf)?$inv_res->einvoicepdf:'';
						$ewaybillpdf = ($inv_res->ewaybillpdf)?$inv_res->ewaybillpdf:'';
					}

					$bill_details['einvoicepdf'] = $einvoicepdf;
					$bill_details['ewaybillpdf'] = $ewaybillpdf;

					$product_details = [];
					if (!empty($order_list)) {
						foreach ($order_list as $key => $value) {

							$auto_id       = !empty($value->id) ? $value->id : '';
							$order_id      = !empty($value->order_id) ? $value->order_id : '';
							$order_no      = !empty($value->order_no) ? $value->order_no : '';
							$product_id    = !empty($value->product_id) ? $value->product_id : '';
							$type_id       = !empty($value->type_id) ? $value->type_id : '';
							$hsn_code      = !empty($value->hsn_code) ? $value->hsn_code : '';
							$gst_val       = !empty($value->gst_val) ? $value->gst_val : '0';
							$unit_val      = !empty($value->unit_val) ? $value->unit_val : '';
							$price         = !empty($value->price) ? $value->price : '0';
							$order_qty     = !empty($value->order_qty) ? $value->order_qty : '0';
							$receive_qty   = !empty($value->receive_qty) ? $value->receive_qty : '0';
							$invoice_val   = !empty($value->invoice_value) ? $value->invoice_value : '';
							$pack_status   = !empty($value->pack_status) ? $value->pack_status : '';
							$delete_status = !empty($value->delete_status) ? $value->delete_status : '';
							$published     = !empty($value->published) ? $value->published : '';
							$status        = !empty($value->status) ? $value->status : '';
							$createdate    = !empty($value->createdate) ? $value->createdate : '';

							// Product Details
							$where_1      = array('id' => $product_id);
							$product_det  = $this->commom_model->getProduct($where_1);
							$product_name = isset($product_det[0]->name) ? $product_det[0]->name : '';

							// Unit Type Details
							$where_2   = array('id' => $unit_val);
							$unit_det  = $this->commom_model->getUnit($where_2);
							$unit_name = isset($unit_det[0]->name) ? $unit_det[0]->name : '';

							// Product Type Details
							$where_3     = array('id' => $type_id);
							$type_det    = $this->commom_model->getProductType($where_3);
							$description = isset($type_det[0]->description) ? $type_det[0]->description : '';
							$type_name   = isset($type_det[0]->product_type) ? $type_det[0]->product_type : '';

							$where_4 = array(
								'distributor_id' => $distributor_id,
								'type_id'        => $type_id,
								'zone_id'        => $zone_value,
								'status'         => '1',
								'published'      => '1'
							);

							$column_4 = 'id, stock';

							$assign_data = $this->assignproduct_model->getAssignProductDetails($where_4, '', '', 'row', '', '', '', '', $column_4);

							if (!empty($assign_data)) {
								$stock_qty = !empty($assign_data->stock) ? $assign_data->stock : '0';

								$product_details[] = array(
									'auto_id'       => $auto_id,
									'order_id'      => $order_id,
									'order_no'      => $order_no,
									'product_id'    => $product_id,
									'product_name'  => $product_name,
									'type_id'       => $type_id,
									'type_name'     => $type_name,
									'description'   => $description,
									'hsn_code'      => $hsn_code,
									'gst_val'       => $gst_val,
									'unit_val'      => $unit_val,
									'unit_name'     => $unit_name,
									'price'         => $price,
									'order_qty'     => $order_qty,
									'receive_qty'   => $receive_qty,
									'stock_qty'     => $stock_qty,
									'invoice_value' => $invoice_val,
									'pack_status'   => $pack_status,
									'delete_status' => $delete_status,
									'published'     => $published,
									'status'        => $status,
									'createdate'    => $createdate,
								);
							}
						}
					}

					$tax_whr  = array(
						'tbl_order_details.order_id'        => $order_id,
						'tbl_order_details.delete_status'   => '1',
						'tbl_order_details.published'       => '1',
						'tbl_order_details.type_id'         => $type_id,
						'tbl_order_details.vendor_type != ' => '1'
					);

					$tax_col  = 'tbl_order_details.hsn_code, tbl_order_details.gst_val';

					$groupby  = 'tbl_order_details.hsn_code';

					$tax_list = $this->order_model->getDistributorOrder($tax_whr, '', '', 'result', '', '', '', '', $tax_col, $groupby);

					$tax_details = [];
					if (!empty($tax_list)) {
						foreach ($tax_list as $key => $value) {
							$hsn_code = !empty($value->hsn_code) ? $value->hsn_code : '';
							$gst_val  = !empty($value->gst_val) ? $value->gst_val : '0';

							// Price Details
							$price_where = array(
								'order_id'  => $order_id,
								'hsn_code'  => $hsn_code,
								'published' => '1',
								'status'    => '1',
							);

							$price_column = 'price, order_qty, type_id';

							$price_data   = $this->order_model->getOrderDetails($price_where, '', '', 'result', '', '', '', '', $price_column);

							$product_price = 0;
							foreach ($price_data as $key => $price_val) {

								$price   = !empty($price_val->price) ? $price_val->price : '0';
								$ord_qty = !empty($price_val->order_qty) ? $price_val->order_qty : '0';
								$type_id = !empty($price_val->type_id) ? $price_val->type_id : '';

								$where_4 = array(
									'distributor_id' => $distributor_id,
									'type_id'        => $type_id,
									'zone_id'        => $zone_value,
									'status'         => '1',
									'published'      => '1'
								);

								$column_4 = 'id';

								$assign_data = $this->assignproduct_model->getAssignProductDetails($where_4, '', '', 'result', '', '', '', '', $column_4);

								if (!empty($assign_data)) {
									$pdt_price = number_format((float)$price, 2, '.', '') * $ord_qty;
									$product_price += $pdt_price;
								}
							}

							$tax_details[] = array(
								'hsn_code'  => $hsn_code,
								'gst_val'   => $gst_val,
								'price_val' => strval($product_price),
							);
						}
					}

					// Sales Return Details
					$whr_5  = array(
						'random_value' => $invoice_res,
						'published'    => '1',
					);

					$col_5  = 'id';
					$data_5 = $this->invoice_model->getInvoice($whr_5, '', '', 'result', '', '', '', '', $col_5);

					$inv_res = '';
					if ($data_5) {
						foreach ($data_5 as $key => $val_5) {
							$inv_id   = !empty($val_5->id) ? $val_5->id : '';
							$inv_res .= $inv_id . ',';
						}
					}

					$inv_data = substr($inv_res, 0, -1);

					$re_val = 0;
					if ($inv_data) {
						$whr_6 = array(
							'invoice_id' => $inv_data,
							'published'  => '1',
						);

						$col_6  = 'price, return_qty';

						$data_6 = $this->return_model->getOutletReturnImplodeDetails($whr_6, '', '', 'result', '', '', '', '', $col_6);

						if ($data_6) {
							foreach ($data_6 as $key => $val_6) {
								$ret_price = !empty($val_6->price) ? $val_6->price : '0';
								$ret_qty   = !empty($val_6->return_qty) ? $val_6->return_qty : '0';
								$ret_total = $ret_price * $ret_qty;
								$re_val   += $ret_total;
							}
						}
					}

					$return_details = array(
						'return_total' => $re_val
					);

					$order_details = array(
						'bill_details'        => $bill_details,
						'distributor_details' => $distributor_details,
						'store_details'       => $store_details,
						'product_details'     => $product_details,
						'tax_details'         => $tax_details,
						'return_details'      => $return_details,
					);

					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = $order_details;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		} 

		else if ($method == '_listInvoiceDistributorOrder') {
			$error = FALSE;
			$errors = array();
			$required = array('distributor_id', 'financial_year');
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
				$whr_1  = array(
					'distributor_id' => $distributor_id,
					'invoice_status' => '1',
					'cancel_status'  => '1',
					'published'      => '1',
				);

				$col_1  = 'zone_id';
				$grp_1  = 'zone_id';

				$res_1  = $this->invoice_model->getInvoice($whr_1, '', '', 'result', '', '', '', '', $col_1, $grp_1);

				if ($res_1) {
					$result_list = [];
					foreach ($res_1 as $key => $val_1) {
						$zone_id   = !empty($val_1->zone_id) ? $val_1->zone_id : '';

						// Order Details
						$whr_2  = array('id' => $zone_id);
						$col_2  = 'name';
						$res_2  = $this->commom_model->getZone($whr_2, '', '', 'result', '', '', '', '', $col_2);

						$zone_name = !empty($res_2[0]->name) ? $res_2[0]->name : '';

						$result_list[] = array(
							'zone_id'   => $zone_id,
							'zone_name' => $zone_name,
						);
					}

					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = $result_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Data Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		} 

		else if ($method == '_listBothDistributorOrder') {
			$error = FALSE;
			$errors = array();
			$required = array('distributor_id', 'financial_year');
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
				$whr_1  = array(
					'distributor_id' => $distributor_id,
					'cancel_status'  => '1',
					'published'      => '1',
				);

				$col_1  = 'zone_id';
				$grp_1  = 'zone_id';

				$res_1  = $this->invoice_model->getInvoice($whr_1, '', '', 'result', '', '', '', '', $col_1, $grp_1);

				if ($res_1) {
					$result_list = [];
					foreach ($res_1 as $key => $val_1) {
						$zone_id   = !empty($val_1->zone_id) ? $val_1->zone_id : '';

						// Order Details
						$whr_2  = array('id' => $zone_id);
						$col_2  = 'name';
						$res_2  = $this->commom_model->getZone($whr_2, '', '', 'result', '', '', '', '', $col_2);

						$zone_name = !empty($res_2[0]->name) ? $res_2[0]->name : '';

						$result_list[] = array(
							'zone_id'   => $zone_id,
							'zone_name' => $zone_name,
						);
					}

					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = $result_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Data Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		} 

		else {
			$response['status']  = 0;
			$response['message'] = "Error";
			$response['data']    = [];
			echo json_encode($response);
			return;
		}
	}

	// Invoice Manage Order
	// ***************************************************
	public function invoice_manage_order($param1 = "", $param2 = "", $param3 = "")
	{
		$method         = $this->input->post('method');
		$random_value   = $this->input->post('random_value');
		$view_type      = $this->input->post('view_type');
		$vendor_id      = $this->input->post('vendor_id');
		$distributor_id = $this->input->post('distributor_id');
		$employee_id    = $this->input->post('employee_id');
		$store_id       = $this->input->post('store_id');
		$limit          = $this->input->post('limit');
		$offset         = $this->input->post('offset');
		$financ_year    = $this->input->post('financial_year');

		if ($method == '_listInvoicePaginate') {
			$error = FALSE;
			$errors = array();
			$required = array('view_type', 'financial_year');
			if ($view_type == 1) {
				array_push($required, 'vendor_id');
			}
			if ($view_type == 2) {
				array_push($required, 'distributor_id');
			}
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error == TRUE) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				if ($limit != '' && $offset != '') {
					$limit  = $limit;
					$offset = $offset;
				} else {
					$limit  = 10;
					$offset = 0;
				}

				$search    = $this->input->post('search');
				$load_data = $this->input->post('load_data');

				if ($search != '') {
					$like['invoice_no'] = $search;
				} else {
					$like = [];
				}

				if ($view_type == 1) {
					$where = array(
						'vendor_id'      => $vendor_id,
						// 'financial_year' => $financ_year,
						'published'      => '1',
					);
				} else if ($view_type == 2) {
					$where = array(
						'distributor_id' => $distributor_id,
						// 'financial_year' => $financ_year,
						'published'      => '1',
					);
				} else {
					$where = array(
						// 'financial_year' => $financ_year,
						'published'      => '1',
					);
				}

				$column = 'id';
				$overalldatas = $this->invoice_model->getInvoice($where, '', '', 'result', $like, '', '', '', $column);

				if ($overalldatas) {
					$totalc = count($overalldatas);
				} else {
					$totalc = 0;
				}

				$option['order_by']   = 'id';
				$option['disp_order'] = 'DESC';

				$column    = 'id, invoice_no, distributor_id, vendor_id, store_name, random_value, createdate';
				$data_list = $this->invoice_model->getInvoice($where, $limit, $offset, 'result', $like, '', $option, '', $column);

				if ($data_list) {
					$invoice_list = [];
					foreach ($data_list as $key => $value) {

						$invoice_id     = !empty($value->id) ? $value->id : '';
						$invoice_no     = !empty($value->invoice_no) ? $value->invoice_no : '';
						$distributor_id = !empty($value->distributor_id) ? $value->distributor_id : '';
						$vendor_id      = !empty($value->vendor_id) ? $value->vendor_id : '';
						$store_name     = !empty($value->store_name) ? $value->store_name : '';
						$random_value   = !empty($value->random_value) ? $value->random_value : '';
						$createdate     = !empty($value->createdate) ? $value->createdate : '';

						if (!empty($vendor_id)) {
							// Vendor Details
							$vdr_col = 'company_name';

							$vdr_whr  = array(
								'id'  => $vendor_id,
							);

							$vdr_data = $this->vendors_model->getVendors($vdr_whr, '', '', 'result', '', '', '', '', $vdr_col);

							if (!empty($vdr_data)) {
								$vdr_val  = $vdr_data[0];

								$company_name = !empty($vdr_val->company_name) ? $vdr_val->company_name : '';
							}
						} else {
							// Distributor Details
							$dis_col = 'company_name';

							$dis_whr  = array(
								'id'  => $distributor_id,
							);

							$dis_data = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);

							if (!empty($dis_data)) {
								$dis_val  = $dis_data[0];

								$company_name = !empty($dis_val->company_name) ? $dis_val->company_name : '';
							}
						}

						$invoice_list[] = array(
							'invoice_id'     => $invoice_id,
							'invoice_no'     => $invoice_no,
							'distributor_id' => $distributor_id,
							'vendor_id'      => $vendor_id,
							'company_name'   => $company_name,
							'store_name'     => $store_name,
							'random_value'   => $random_value,
							'createdate'     => $createdate,
						);
					}

					if ($offset != '' && $limit != '') {
						$offset = $offset + $limit;
						$limit  = $limit;
					} else {
						$offset = $limit;
						$limit  = 10;
					}

					$response['status']       = 1;
					$response['message']      = "Success";
					$response['total_record'] = $totalc;
					$response['offset']       = (int)$offset;
					$response['limit']        = (int)$limit;
					$response['data']         = $invoice_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		} else if ($method == '_detailInvoice') {
			if (!empty($random_value)) {
				// Invoice Data
				$where_1 = array(
					'random_value' => $random_value,
					'published'    => '1',
				);

				$bill_data = $this->invoice_model->getInvoice($where_1);

				if (!empty($bill_data)) {
					// Bill Details
					$data_val    = $bill_data[0];
					$invoice_id  = !empty($data_val->id) ? $data_val->id : '';
					$order_id    = !empty($data_val->order_id) ? $data_val->order_id : '';
					$bill_type   = !empty($data_val->bill_type) ? $data_val->bill_type : '';
					$invoice_no  = !empty($data_val->invoice_no) ? $data_val->invoice_no : '';
					$distri_id   = !empty($data_val->distributor_id) ? $data_val->distributor_id : '';
					$vendor_id   = !empty($data_val->vendor_id) ? $data_val->vendor_id : '';
					$store_id    = !empty($data_val->store_id) ? $data_val->store_id : '';
					$due_days    = !empty($data_val->due_days) ? $data_val->due_days : '';
					$discount    = !empty($data_val->discount) ? $data_val->discount : '';
					$outlet_type = !empty($data_val->outlet_type) ? $data_val->outlet_type : '';
					$length      = !empty($data_val->length) ? $data_val->length : '0';
					$breadth     = !empty($data_val->breadth) ? $data_val->breadth : '0';
					$height      = !empty($data_val->height) ? $data_val->height : '0';
					$weight      = !empty($data_val->weight) ? $data_val->weight : '0';
					$createdate  = !empty($data_val->createdate) ? $data_val->createdate : '';

					$bill_details = array(
						'invoice_id'     => $invoice_id,
						'bill_type'      => $bill_type,
						'order_id'       => $order_id,
						'invoice_no'     => $invoice_no,
						'distributor_id' => $distri_id,
						'vendor_id'      => $vendor_id,
						'store_id'       => $store_id,
						'due_days'       => $due_days,
						'discount'       => $discount,
						'length'         => $length,
						'breadth'        => $breadth,
						'height'         => $height,
						'weight'         => $weight,
						'outlet_type'    => $outlet_type,
						'createdate'     => $createdate,
					);

					if (!empty($vendor_id)) {
						// Vendor Details
						$vdr_col = 'company_name, gst_no, contact_no, email, tan_no, state_id, address, account_name, account_no, bank_name, branch_name, ifsc_code, pincode';

						$vdr_whr = array(
							'id'  => $vendor_id,
						);

						$vdr_data = $this->vendors_model->getVendors($vdr_whr, '', '', 'result', '', '', '', '', $vdr_col);

						$distributor_details = [];
						if (!empty($vdr_data)) {
							$vdr_val      = $vdr_data[0];
							$com_name     = !empty($vdr_val->company_name) ? $vdr_val->company_name : '';
							$gst_no       = !empty($vdr_val->gst_no) ? $vdr_val->gst_no : '';
							$contact_no   = !empty($vdr_val->contact_no) ? $vdr_val->contact_no : '';
							$email        = !empty($vdr_val->email) ? $vdr_val->email : '';
							$tan_no       = !empty($vdr_val->tan_no) ? $vdr_val->tan_no : '';
							$state_id     = !empty($vdr_val->state_id) ? $vdr_val->state_id : '';
							$address      = !empty($vdr_val->address) ? $vdr_val->address : '';
							$account_name = !empty($vdr_val->account_name) ? $vdr_val->account_name : '';
							$account_no   = !empty($vdr_val->account_no) ? $vdr_val->account_no : '';
							$bank_name    = !empty($vdr_val->bank_name) ? $vdr_val->bank_name : '';
							$branch_name  = !empty($vdr_val->branch_name) ? $vdr_val->branch_name : '';
							$ifsc_code    = !empty($vdr_val->ifsc_code) ? $vdr_val->ifsc_code : '';
							$pincode      = !empty($vdr_val->pincode) ? $vdr_val->pincode : '';

							// State Details
							$state_whr  = array('id' => $state_id);
							$state_data = $this->commom_model->getState($state_whr);

							$state_val  = $state_data[0];
							$state_name = !empty($state_val->state_name) ? $state_val->state_name : '';
							$gst_code   = !empty($state_val->gst_code) ? $state_val->gst_code : '';

							$distributor_details = array(
								'company_name' => $com_name,
								'gst_no'       => $gst_no,
								'contact_no'   => $contact_no,
								'email'        => $email,
								'tan_no'       => $tan_no,
								'address'      => $address,
								'state_name'   => $state_name,
								'gst_code'     => $gst_code,
								'account_name' => $account_name,
								'account_no'   => $account_no,
								'bank_name'    => $bank_name,
								'branch_name'  => $branch_name,
								'ifsc_code'    => $ifsc_code,
								'pincode'      => $pincode,
							);
						}
					} else {
						// Distributor Details
						$dis_col = 'company_name, gst_no, mobile, email, tan_no, state_id, address, account_name, account_no, bank_name, branch_name, ifsc_code, pincode, msme_status, msme_number, logo_image';

						$dis_whr = array(
							'id'  => $distri_id,
						);

						$dis_data = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);

						$distributor_details = [];
						if (!empty($dis_data)) {
							$dis_val      = $dis_data[0];
							$com_name     = !empty($dis_val->company_name) ? $dis_val->company_name : '';
							$gst_no       = !empty($dis_val->gst_no) ? $dis_val->gst_no : '';
							$contact_no   = !empty($dis_val->mobile) ? $dis_val->mobile : '';
							$email        = !empty($dis_val->email) ? $dis_val->email : '';
							$tan_no       = !empty($dis_val->tan_no) ? $dis_val->tan_no : '';
							$state_id     = !empty($dis_val->state_id) ? $dis_val->state_id : '1';
							$address      = !empty($dis_val->address) ? $dis_val->address : '';
							$account_name = !empty($dis_val->account_name) ? $dis_val->account_name : '';
							$account_no   = !empty($dis_val->account_no) ? $dis_val->account_no : '';
							$bank_name    = !empty($dis_val->bank_name) ? $dis_val->bank_name : '';
							$branch_name  = !empty($dis_val->branch_name) ? $dis_val->branch_name : '';
							$ifsc_code    = !empty($dis_val->ifsc_code) ? $dis_val->ifsc_code : '';
							$pincode      = !empty($dis_val->pincode) ? $dis_val->pincode : '';
							$msme_status  = !empty($dis_val->msme_status) ? $dis_val->msme_status : '';
							$msme_number  = !empty($dis_val->msme_number) ? $dis_val->msme_number : '';
							$logo_image   = !empty($dis_val->logo_image) ? $dis_val->logo_image : '';

							// State Details
							$state_whr  = array('id' => $state_id);
							$state_data = $this->commom_model->getState($state_whr);

							$state_val  = $state_data[0];
							$state_name = !empty($state_val->state_name) ? $state_val->state_name : '';
							$gst_code   = !empty($state_val->gst_code) ? $state_val->gst_code : '';

							$distributor_details = array(
								'company_name' => $com_name,
								'gst_no'       => $gst_no,
								'contact_no'   => $contact_no,
								'email'        => $email,
								'tan_no'       => $tan_no,
								'address'      => $address,
								'state_name'   => $state_name,
								'gst_code'     => $gst_code,
								'account_name' => $account_name,
								'account_no'   => $account_no,
								'bank_name'    => $bank_name,
								'branch_name'  => $branch_name,
								'ifsc_code'    => $ifsc_code,
								'pincode'      => $pincode,
								'msme_status'  => $msme_status,
								'msme_number'  => $msme_number,
								'logo_image'   => $logo_image,
							);
						}
					}

					// Buyer order details
					$buyer_whr  = array(
						'id'        => $order_id,
						'published' => '1',
					);

					$buyer_col   = 'order_no, _ordered';

					$buyer_data  = $this->order_model->getOrder($buyer_whr, '', '', 'result', '', '', '', '', $buyer_col);

					$buyer_details = [];
					if (!empty($buyer_data)) {
						$order_no = !empty($buyer_data[0]->order_no) ? $buyer_data[0]->order_no : '';
						$_ordered = !empty($buyer_data[0]->_ordered) ? $buyer_data[0]->_ordered : '';

						$buyer_details = array(
							'order_no' => $order_no,
							'_ordered' => $_ordered,
						);
					}

					// Outlet Details
					$out_col = 'company_name, contact_name, mobile, email, gst_no, address, country_id, state_id, city_id, zone_id, outlet_type, pincode';

					$out_whr = array(
						'id' => $store_id,
					);

					$out_data = $this->outlets_model->getOutlets($out_whr, '', '', 'result', '', '', '', '', $out_col);

					$store_details = [];
					if (!empty($out_data)) {
						$out_val      = $out_data[0];
						$company_name = !empty($out_val->company_name) ? $out_val->company_name : '';
						$contact_name = !empty($out_val->contact_name) ? $out_val->contact_name : '';
						$mobile       = !empty($out_val->mobile) ? $out_val->mobile : '';
						$email        = !empty($out_val->email) ? $out_val->email : '';
						$gst_no       = !empty($out_val->gst_no) ? $out_val->gst_no : '';
						$address      = !empty($out_val->address) ? $out_val->address : '';
						$country_id   = !empty($out_val->country_id) ? $out_val->country_id : '';
						$state_id     = !empty($out_val->state_id) ? $out_val->state_id : '';
						$city_id      = !empty($out_val->city_id) ? $out_val->city_id : '';
						$zone_id      = !empty($out_val->zone_id) ? $out_val->zone_id : '';
						$outlet_type  = !empty($out_val->outlet_type) ? $out_val->outlet_type : '';
						$pincode      = !empty($out_val->pincode) ? $out_val->pincode : '';

						// State Details
						$state_whr  = array('id' => $state_id);
						$state_data = $this->commom_model->getState($state_whr);

						$state_val  = $state_data[0];
						$state_name = !empty($state_val->state_name) ? $state_val->state_name : '';
						$gst_code   = !empty($state_val->gst_code) ? $state_val->gst_code : '';

						$store_details = array(
							'company_name' => $company_name,
							'contact_name' => $contact_name,
							'mobile'       => $mobile,
							'email'        => $email,
							'gst_no'       => $gst_no,
							'address'      => $address,
							'country_id'   => $country_id,
							'state_id'     => $state_id,
							'city_id'      => $city_id,
							'zone_id'      => $zone_id,
							'state_name'   => $state_name,
							'gst_code'     => $gst_code,
							'outlet_type'  => $outlet_type,
							'pincode'      => $pincode,
						);
					}

					// Order Details
					$ord_whr = array(
						'invoice_id' => $invoice_id,
						'published'  => '1',
					);

					$order_details = $this->invoice_model->getInvoiceDetails($ord_whr);

					$total_qty = 0;
					$sub_total = 0;

					$product_details = [];
					if (!empty($order_details)) {
						foreach ($order_details as $key => $value) {

							$auto_id      = !empty($value->id) ? $value->id : '';
							$product_id   = !empty($value->product_id) ? $value->product_id : '';
							$type_id      = !empty($value->type_id) ? $value->type_id : '';
							$hsn_code     = !empty($value->hsn_code) ? $value->hsn_code : '';
							$gst_val      = !empty($value->gst_val) ? $value->gst_val : '0';
							$unit_val     = !empty($value->unit_val) ? $value->unit_val : '';
							$price        = !empty($value->price) ? $value->price : '';
							$order_qty    = !empty($value->order_qty) ? $value->order_qty : '';
							$total_price  = $order_qty * number_format((float)$price, 2, '.', '');
							$total_qty   += $order_qty;
							$sub_total   += $total_price;

							// Product Details
							$where_1      = array('id' => $product_id);
							$product_det  = $this->commom_model->getProduct($where_1);
							$product_name = isset($product_det[0]->name) ? $product_det[0]->name : '';

							// Unit Type Details
							$where_2   = array('id' => $unit_val);
							$unit_det  = $this->commom_model->getUnit($where_2);
							$unit_name = isset($unit_det[0]->name) ? $unit_det[0]->name : '';

							// Product Type Details
							$where_3     = array('id' => $type_id);
							$type_det    = $this->commom_model->getProductType($where_3);
							$description = isset($type_det[0]->description) ? $type_det[0]->description : '';
							$type_name   = isset($type_det[0]->product_type) ? $type_det[0]->product_type : '';

							$product_details[] = array(
								'auto_id'       => $auto_id,
								'product_id'    => $product_id,
								'product_name'  => $product_name,
								'type_id'       => $type_id,
								'type_name'     => $type_name,
								'description'   => $description,
								'hsn_code'      => $hsn_code,
								'gst_val'       => $gst_val,
								'unit_val'      => $unit_val,
								'unit_name'     => $unit_name,
								'price'         => $price,
								'order_qty'     => $order_qty,
							);
						}
					}

					// Tax Details
					$tax_where = array(
						'invoice_id' => $invoice_id,
						'published'  => '1',
						'status'     => '1',
					);

					$tax_column = 'id, order_id, hsn_code, gst_val';
					$tax_group  = 'hsn_code, gst_val';

					$tax_data   = $this->invoice_model->getInvoiceDetails($tax_where, '', '', 'result', '', '', '', '', $tax_column, $tax_group);

					// echo $this->db->last_query();

					$tax_details = [];
					if (!empty($tax_data)) {
						foreach ($tax_data as $key => $value) {

							$auto_id   = !empty($value->id) ? $value->id : '';
							$order_id  = !empty($value->order_id) ? $value->order_id : '';
							$hsn_code  = !empty($value->hsn_code) ? $value->hsn_code : '';
							$gst_value = !empty($value->gst_val) ? $value->gst_val : '0';

							// Price Details
							$price_where = array(
								'invoice_id' => $invoice_id,
								'hsn_code'   => $hsn_code,
								'gst_val'    => $gst_value,
								'published'  => '1',
								'status'     => '1',
							);

							$price_column = 'price, order_qty, gst_val';

							$price_data   = $this->invoice_model->getInvoiceDetails($price_where, '', '', 'result', '', '', '', '', $price_column);

							$product_price = 0;
							$gst_price     = 0;
							if ($price_data) {
								foreach ($price_data as $key => $price_val) {

									$price     = !empty($price_val->price) ? number_format((float)$price_val->price, 2, '.', '') : '0';
									$order_qty = !empty($price_val->order_qty) ? $price_val->order_qty : '';
									$gst_val   = !empty($price_val->gst_val) ? $price_val->gst_val : '0';

									// GST Calculation
									$gst_calc    = $price - ($price * (100 / (100 + $gst_val)));
									$price_value = $price - $gst_calc;
									$total_price = $order_qty * $price_value;
									$total_gst   = $order_qty * $gst_calc;

									$gst_price     += $total_gst;
									$product_price += $total_price;
								}
							}

							$tax_details[] = array(
								'hsn_code'    => $hsn_code,
								'gst_val'     => $gst_value,
								'gst_value'   => strval($gst_price),
								'price_value' => strval($product_price),
							);
						}
					}

					// Sales Return Details
					$whr_5  = array(
						'random_value' => $random_value,
						'published'    => '1',
					);

					$col_5  = 'id';
					$data_5 = $this->invoice_model->getInvoice($whr_5, '', '', 'result', '', '', '', '', $col_5);

					$inv_res = '';
					if ($data_5) {
						foreach ($data_5 as $key => $val_5) {
							$inv_id   = !empty($val_5->id) ? $val_5->id : '';
							$inv_res .= $inv_id . ',';
						}
					}

					$inv_data = substr($inv_res, 0, -1);

					$re_val = 0;
					if ($inv_data) {
						$whr_6 = array(
							'invoice_id' => $inv_data,
							'published'  => '1',
						);

						$col_6  = 'price, return_qty';

						$data_6 = $this->return_model->getOutletReturnImplodeDetails($whr_6, '', '', 'result', '', '', '', '', $col_6);

						if ($data_6) {
							foreach ($data_6 as $key => $val_6) {
								$ret_price = !empty($val_6->price) ? $val_6->price : '0';
								$ret_qty   = !empty($val_6->return_qty) ? $val_6->return_qty : '0';
								$ret_total = $ret_price * $ret_qty;
								$re_val   += $ret_total;
							}
						}
					}

					$return_details = array(
						'return_total' => $re_val
					);

					// Total Details
					$total_details = array(
						'total_qty' => strval($total_qty),
						'sub_total' => strval(round($sub_total)),
					);

					$order_details = array(
						'bill_details'        => $bill_details,
						'buyer_details'       => $buyer_details,
						'distributor_details' => $distributor_details,
						'store_details'       => $store_details,
						'product_details'     => $product_details,
						'tax_details'         => $tax_details,
						'total_details'       => $total_details,
						'return_details'      => $return_details,
						'print_invoice'       => BASE_URL . 'index.php/distributors/order/print_invoice/' . $random_value,
					);

					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = $order_details;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			}
		} else if ($method == '_distributorInvoice') {
			$error = FALSE;
			$errors = array();
			$required = array('distributor_id', 'store_id');
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
				$whr_1 = array(
					'distributor_id' => $distributor_id,
					'outlet_id'      => $store_id,
					'bal_amt !='     => '0',
					'pay_type'       => '4',
					'published'      => '1',
				);

				$col_1 = 'id, bill_id, bill_no, bal_amt';

				$res_1 = $this->payment_model->getOutletPaymentDetails($whr_1, '', '', 'result', '', '', '', '', $col_1);

				if ($res_1) {
					$invoice_list = [];
					foreach ($res_1 as $key => $val_1) {
						$invoice_id = !empty($val_1->id) ? $val_1->id : '';
						$bill_id    = !empty($val_1->bill_id) ? $val_1->bill_id : '';
						$invoice_no = !empty($val_1->bill_no) ? $val_1->bill_no : '';
						$bill_value = !empty($val_1->bal_amt) ? $val_1->bal_amt : '';

						$invoice_list[] = array(
							'invoice_id' => $invoice_id,
							'invoice_no' => $invoice_no,
							'bill_value' => strval($bill_value),
						);
					}

					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = $invoice_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Data Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		} else if ($method == '_employeeInvoice') {
			if ($employee_id) {
				if ($limit != '' && $offset != '') {
					$limit  = $limit;
					$offset = $offset;
				} else {
					$limit  = 10;
					$offset = 0;
				}

				$search    = $this->input->post('search');
				$load_data = $this->input->post('load_data');

				if ($search != '') {
					$like['name'] = $search;
				} else {
					$like = [];
				}

				$where = array(
					'delivery_employee' => $employee_id,
					'published'         => '1',
				);

				$column = 'id';
				$overalldatas = $this->invoice_model->getInvoice($where, '', '', 'result', $like, '', '', '', $column);

				if ($overalldatas) {
					$totalc = count($overalldatas);
				} else {
					$totalc = 0;
				}

				$option['order_by']   = 'id';
				$option['disp_order'] = 'DESC';

				$column    = 'id, invoice_no, distributor_id, vendor_id, store_id, store_name, random_value, createdate';
				$data_list = $this->invoice_model->getInvoice($where, $limit, $offset, 'result', $like, '', $option, '', $column);

				if ($data_list) {
					$invoice_list = [];
					foreach ($data_list as $key => $value) {

						$invoice_id     = !empty($value->id) ? $value->id : '';
						$invoice_no     = !empty($value->invoice_no) ? $value->invoice_no : '';
						$distributor_id = !empty($value->distributor_id) ? $value->distributor_id : '';
						$vendor_id      = !empty($value->vendor_id) ? $value->vendor_id : '';
						$store_id       = !empty($value->store_id) ? $value->store_id : '';
						$store_name     = !empty($value->store_name) ? $value->store_name : '';
						$random_value   = !empty($value->random_value) ? $value->random_value : '';
						$createdate     = !empty($value->createdate) ? $value->createdate : '';

						if (!empty($vendor_id)) {
							// Vendor Details
							$vdr_col = 'company_name';

							$vdr_whr  = array(
								'id'  => $vendor_id,
							);

							$vdr_data = $this->vendors_model->getVendors($vdr_whr, '', '', 'result', '', '', '', '', $vdr_col);

							if (!empty($vdr_data)) {
								$vdr_val  = $vdr_data[0];

								$company_name = !empty($vdr_val->company_name) ? $vdr_val->company_name : '';
							}
						} else {
							// Distributor Details
							$dis_col = 'company_name';

							$dis_whr  = array(
								'id'  => $distributor_id,
							);

							$dis_data = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);

							if (!empty($dis_data)) {
								$dis_val  = $dis_data[0];

								$company_name = !empty($dis_val->company_name) ? $dis_val->company_name : '';
							}
						}

						// Outlet Details
						$whr_1  = array('id' => $store_id);
						$col_1  = 'mobile, address, latitude, longitude';
						$res_1  = $this->outlets_model->getOutlets($whr_1, '', '', 'result', '', '', '', '', $col_1);

						$mobile = !empty($res_1[0]->mobile) ? $res_1[0]->mobile : '';
						$adrs   = !empty($res_1[0]->address) ? $res_1[0]->address : '';
						$lat    = !empty($res_1[0]->latitude) ? $res_1[0]->latitude : '';
						$long   = !empty($res_1[0]->longitude) ? $res_1[0]->longitude : '';

						$invoice_list[] = array(
							'invoice_id'     => $invoice_id,
							'invoice_no'     => $invoice_no,
							'distributor_id' => $distributor_id,
							'vendor_id'      => $vendor_id,
							'company_name'   => $company_name,
							'store_name'     => $store_name,
							'contact_no'     => $mobile,
							'address'        => $adrs,
							'latitude'       => $lat,
							'longitude'      => $long,
							'random_value'   => $random_value,
							'createdate'     => $createdate,
						);
					}

					if ($offset != '' && $limit != '') {
						$offset = $offset + $limit;
						$limit  = $limit;
					} else {
						$offset = $limit;
						$limit  = 10;
					}

					$response['status']       = 1;
					$response['message']      = "Success";
					$response['total_record'] = $totalc;
					$response['offset']       = (int)$offset;
					$response['limit']        = (int)$limit;
					$response['data']         = $invoice_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "No Data Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
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

	// Outlet OTP 
	// ***************************************************
	public function outlet_otp($param1 = "", $param2 = "", $param3 = "")
	{
		$method      = $this->input->post('method');
		$outlet_id   = $this->input->post('outlet_id');
		$employee_id = $this->input->post('employee_id');
		$otp_value   = $this->input->post('otp_value');

		if ($method == '_sendOtp') {
			$error = FALSE;
			$errors = array();
			$required = array('outlet_id', 'employee_id');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error == TRUE) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				// Outlet Details
				$whr_1 = array('id' => $outlet_id, 'status' => '1', 'published' => '1');
				$col_1 = 'mobile, otp_type';
				$res_1 = $this->outlets_model->getOutlets($whr_1, '', '', 'row', '', '', '', '', $col_1);

				if ($res_1) {
					$mobile   = !empty($res_1->mobile) ? $res_1->mobile : '';
					$otp_type = !empty($res_1->otp_type) ? $res_1->otp_type : '';

					if ($otp_type == 2) {
						// Send OTP
						$randomOtp    = generateRandomnumber(5);
						$current_date = date('Y-m-d');
						$create_time  = date('H:i:s');
						$create_date  = date('Y-m-d H:i:s');

						$ins_data = array(
							'outlet_id'   => $outlet_id,
							'employee_id' => $employee_id,
							'mobile'      => $mobile,
							'otp'         => $randomOtp,
							'date'        => $current_date,
							'time'        => $create_time,
							'createdate'  => $create_date,
						);

						$message  = $randomOtp . " is the OTP for your request with us - Ananya's Nana Nani Homes. Visit, www.nanananihomes.in for more details.";

						$send_msg   = sendSMS($mobile, $message);
						$otp_insert = $this->login_model->otp_insert($ins_data);

						if ($otp_insert) {
							$response['status']  = 1;
							$response['message'] = "Success";
							$response['data']    = [];
							echo json_encode($response);
							return;
						} else {
							$response['status']  = 0;
							$response['message'] = "Not Success";
							$response['data']    = [];
							echo json_encode($response);
							return;
						}
					} else {
						$response['status']  = 1;
						$response['message'] = "Invalid Outlet";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				} else {
					$response['status']  = 0;
					$response['message'] = "Data Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
		} else if ($method == '_verifyOtp') {
			$error = FALSE;
			$errors = array();
			$required = array('outlet_id', 'employee_id', 'otp_value');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error == TRUE) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				// Outlet Details
				$whr_1 = array('id' => $outlet_id, 'status' => '1', 'published' => '1');
				$col_1 = 'mobile, otp_type';
				$res_1 = $this->outlets_model->getOutlets($whr_1, '', '', 'row', '', '', '', '', $col_1);

				if ($res_1) {
					$mobile   = !empty($res_1->mobile) ? $res_1->mobile : '';
					$otp_type = !empty($res_1->otp_type) ? $res_1->otp_type : '';

					if ($otp_type == 2) {
						$current_date = date('Y-m-d');
						$current_time = date('H:i:s');
						$create_date  = date('Y-m-d H:i:s');

						$otp_whr = array(
							'outlet_id' => $outlet_id,
							'otp'       => $otp_value,
							'verify'    => '1',
							'date'      => $current_date,
						);

						$otp_col  = 'id';
						$otp_res  = $this->login_model->getOtp($otp_whr, '', '', 'row', '', '', '', '', $otp_col);

						if (!empty($otp_res)) {
							$otp_id  = !empty($otp_res->id) ? $otp_res->id : '';

							$upt_val = array(
								'verify'     => 2,
								'updatedate' => $create_date
							);

							$upt_whr = array('id' => $otp_id);
							$cus_upt = $this->login_model->otp_update($upt_val, $upt_whr);

							if ($cus_upt) {
								$response['status']  = 1;
								$response['message'] = "Success";
								$response['data']    = [];
								echo json_encode($response);
								return;
							} else {
								$response['status']  = 0;
								$response['message'] = "Not Success";
								$response['data']    = [];
								echo json_encode($response);
								return;
							}
						} else {
							$response['status']  = 0;
							$response['message'] = "Invalid OTP";
							$response['data']    = [];
							echo json_encode($response);
							return;
						}
					} else {
						$response['status']  = 1;
						$response['message'] = "Invalid Outlet";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				}
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

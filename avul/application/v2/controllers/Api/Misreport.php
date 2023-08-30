<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Misreport extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('commom_model');
			$this->load->model('order_model');
			$this->load->model('employee_model');
			$this->load->model('payment_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Employee overall order
		// ***************************************************
		public function order_report($param1="",$param2="",$param3="")
		{
			$month_id    = $this->input->post('month_id');
		    $year_id     = $this->input->post('year_id');
		    $employee_id = $this->input->post('employee_id');
		    $method      = $this->input->post('method');

		    if($method == '_getEmployeeOrderData')
		    {
		    	$error    = FALSE;
				$required = array('month_id', 'year_id', 'employee_id');
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
			    	// Year Details
			    	$year_whr = array('id' => $year_id);
					$year_col = 'year_value';
					$year_res = $this->commom_model->getYear($year_whr, '', '', 'row', '', '', '', '', $year_col);
					$year_val = empty_check($year_res->year_value);

			    	$month_count = cal_days_in_month(CAL_GREGORIAN, $month_id, $year_val);
			    	$start_date  = date('Y-m-d H:i:s', strtotime($year_val.'-'.$month_id.'-01 00:00:00'));
					$end_date    = date('Y-m-d H:i:s', strtotime($year_val.'-'.$month_id.'-'.$month_count.' 23:59:59'));

			    	$whr_1 = array(
			    		'A.emp_id'        => $employee_id,
						'A.createdate >=' => $start_date,
						'A.createdate <=' => $end_date,
						'A.published'     => '1',
					);

					$col_1 = 'A.id AS order_id, A.order_no, A.createdate AS order_date, A.att_id, A.store_name, SUM(B.entry_qty * B.price) AS order_value';

					$grp_1 = 'A.id';

			    	$qry_1 = $this->order_model->getOrderJoin($whr_1, '', '', 'result', '', '', '', '', $col_1, $grp_1);

			    	if($qry_1)
			    	{
			    		$order_list = [];
			    		foreach ($qry_1 as $key => $val_1) {
			    				
			    			$order_id = empty_check($val_1->order_id);	


			    			// admin delete product
					    	$whr_2 = array('order_id' => $order_id, 'published' => '0');
							$col_2 = 'SUM(entry_qty * price) AS admin_delete';
							$res_2 = $this->order_model->getOrderDetails($whr_2, '', '', 'row', '', '', '', '', $col_2);
							$val_2 = empty_check($res_2->admin_delete);

							// distributor delete product
					    	$whr_3 = array('order_id' => $order_id, 'delete_status' => '2');
							$col_3 = 'SUM(entry_qty * price) AS distributor_delete';
							$res_3 = $this->order_model->getOrderDetails($whr_3, '', '', 'row', '', '', '', '', $col_3);
							$val_3 = empty_check($res_3->distributor_delete);							

							// process order value
					    	$whr_4 = array('order_id' => $order_id, 'delete_status' => '1', 'published' => '1');
							$col_4 = 'SUM(receive_qty * price) AS actual_order';
							$res_4 = $this->order_model->getOrderDetails($whr_4, '', '', 'row', '', '', '', '', $col_4);
							$val_4 = empty_check($res_4->actual_order);	

							// invoice value
					    	$whr_5 = array('order_id' => $order_id, 'invoice_num IS NOT NULL' => NULL, 'delete_status' => '1', 'published' => '1');
							$col_5 = 'SUM(receive_qty * price) AS invoice_order';
							$res_5 = $this->order_model->getOrderDetails($whr_5, '', '', 'row', '', '', '', '', $col_5);
							$val_5 = empty_check($res_5->invoice_order);	

							// process order value
					    	$whr_6 = array('order_id' => $order_id, 'cancel_status' => '2', 'published' => '1');
							$col_6 = 'SUM(receive_qty * price) AS cancel_value';
							$res_6 = $this->order_model->getOrderDetails($whr_6, '', '', 'row', '', '', '', '', $col_6);
							$val_6 = empty_check($res_6->cancel_value);	

							$balance_val = round($val_4) - round($val_5);

							$order_list[] = array(
								'order_id'           => $order_id,
								'order_no'           => empty_check($val_1->order_no),
								'order_date'         => date('d-m-Y', strtotime($val_1->order_date)),
								'att_id'             => $val_1->att_id,
								'store_name'         => empty_check($val_1->store_name),
								'order_value'        => round($val_1->order_value),
								'admin_delete'       => round($val_2),
								'distributor_delete' => round($val_3),
								'actual_order'       => round($val_4),
								'balance_value'      => round($balance_val),
								'invoice_order'      => round($val_5),
								'cancel_value'       => round($val_6),
							);
			    		}

			    		// Employee Details
			    		$emp_whr  = array('id' => $employee_id);
			    		$emp_col  = 'first_name,last_name';
						$emp_res  = $this->employee_model->getEmployee($emp_whr, '', '', 'row', '', '', '', '', $emp_col);
						$first_name = empty_check($emp_res->first_name);
						$last_name = empty_check($emp_res->last_name);

						$arr = array($first_name,$last_name);
						$emp_name =join(" ",$arr);
						$dateObj   = DateTime::createFromFormat('!m', $month_id);
						$monthName = $dateObj->format('F'); // March

						$emp_data = array(
							'employee_name'  => $emp_name,
							'duration_month' => $monthName,
							'duration_year'  => $year_val,
						);

						$result_data = array(
							'ord_data' => $order_list,
							'emp_data' => $emp_data,
						);

			    		$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $result_data;
				        echo json_encode($response);
				        return;
			    	}
			    	else
					{
						$response['status']  = 0;
				        $response['message'] = "Data Not Found"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
					}
			    }	
		    }

		    else if($method == '_getEmployeeMonthOrderData')
		    {
		    	$error    = FALSE;
				$required = array('month_id', 'year_id', 'employee_id');
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
			    	// Year Details
			    	$year_whr = array('id' => $year_id);
					$year_col = 'year_value';
					$year_res = $this->commom_model->getYear($year_whr, '', '', 'row', '', '', '', '', $year_col);
					$year_val = empty_check($year_res->year_value);

			    	$month_count = cal_days_in_month(CAL_GREGORIAN, $month_id, $year_val);

			    	$order_list  = [];
			    	for ($i=1; $i <= $month_count; $i++) {

			    		$date_val = date('d-m-Y', strtotime($i.'-'.$month_id.'-'.$year_val));
						$day_val  = date('l', strtotime($date_val));

						$start_date  = date('Y-m-d H:i:s', strtotime($i.'-'.$month_id.'-'.$year_val.' 00:00:00'));
						$end_date    = date('Y-m-d H:i:s', strtotime($i.'-'.$month_id.'-'.$year_val.' 23:59:59'));

						// template order
				    	$whr_1 = array(
				    		'tbl_order.emp_id'                => $employee_id,
							'tbl_order_details.createdate >=' => $start_date,
							'tbl_order_details.createdate <=' => $end_date,
							'tbl_order_details.published'     => '1',
				    	);

						$col_1 = 'SUM(tbl_order_details.entry_qty * tbl_order_details.price) AS temp_value';
						$res_1 = $this->order_model->getProductionOrderDetails($whr_1, '', '', 'row', '', '', '', '', $col_1);
						$val_1 = empty_check($res_1->temp_value);

						// Admin delete product
						$whr_2 = array(
				    		'tbl_order.emp_id'                => $employee_id,
							'tbl_order_details.createdate >=' => $start_date,
							'tbl_order_details.createdate <=' => $end_date,
							'tbl_order_details.published'     => '0',
				    	);

						$col_2 = 'SUM(tbl_order_details.entry_qty * tbl_order_details.price) AS admin_delete';
						$res_2 = $this->order_model->getProductionOrderDetails($whr_2, '', '', 'row', '', '', '', '', $col_2);
						$val_2 = empty_check($res_2->admin_delete);	

						// Distributor delete product
						$whr_3 = array(
				    		'tbl_order.emp_id'                => $employee_id,
							'tbl_order_details.createdate >=' => $start_date,
							'tbl_order_details.createdate <=' => $end_date,
							'tbl_order_details.delete_status' => '2',
							'tbl_order_details.published'     => '1',
				    	);

						$col_3 = 'SUM(tbl_order_details.entry_qty * tbl_order_details.price) AS distributor_delete';
						$res_3 = $this->order_model->getProductionOrderDetails($whr_3, '', '', 'row', '', '', '', '', $col_3);
						$val_3 = empty_check($res_3->distributor_delete);	

						// Actual order
						$whr_4 = array(
				    		'tbl_order.emp_id'                => $employee_id,
							'tbl_order_details.createdate >=' => $start_date,
							'tbl_order_details.createdate <=' => $end_date,
							'tbl_order_details.delete_status' => '1',
							'tbl_order_details.published'     => '1',
				    	);

						$col_4 = 'SUM(tbl_order_details.receive_qty * tbl_order_details.price) AS actual_order';
						$res_4 = $this->order_model->getProductionOrderDetails($whr_4, '', '', 'row', '', '', '', '', $col_4);
						$val_4 = empty_check($res_4->actual_order);	

						// Invoice order
						$whr_5 = array(
							'tbl_order_details.invoice_num IS NOT NULL' => NULL,
				    		'tbl_order.emp_id'                          => $employee_id,
							'tbl_order_details.createdate >='           => $start_date,
							'tbl_order_details.createdate <='           => $end_date,
							'tbl_order_details.delete_status'           => '1',
							'tbl_order_details.published'               => '1',
				    	);

						$col_5 = 'SUM(tbl_order_details.receive_qty * tbl_order_details.price) AS invoice_order';
						$res_5 = $this->order_model->getProductionOrderDetails($whr_5, '', '', 'row', '', '', '', '', $col_5);
						$val_5 = empty_check($res_5->invoice_order);	

						// Cancel order
						$whr_6 = array(
				    		'tbl_order.emp_id'                          => $employee_id,
							'tbl_order_details.createdate >='           => $start_date,
							'tbl_order_details.createdate <='           => $end_date,
							'tbl_order_details.cancel_status'           => '2',
							'tbl_order_details.published'               => '1',
				    	);

						$col_6 = 'SUM(tbl_order_details.receive_qty * tbl_order_details.price) AS cancel_value';
						$res_6 = $this->order_model->getProductionOrderDetails($whr_6, '', '', 'row', '', '', '', '', $col_6);
						$val_6 = empty_check($res_6->cancel_value);		

						$balance_val = round($val_4) - round($val_5);

						$order_list[] = array(
							'date'               => $date_val,
							'day'                => $day_val,
							'temp_value'         => round($val_1),
							'admin_delete'       => round($val_2),
							'distributor_delete' => round($val_3),
							'actual_order'       => round($val_4),
							'balance_val'        => round($balance_val),
							'invoice_order'      => round($val_5),
							'cancel_value'       => round($val_6),
						);
			    	}

			    	// Employee Details
		    		$emp_whr  = array('id' => $employee_id);
		    		$emp_col  = 'first_name,last_name';
					$emp_res  = $this->employee_model->getEmployee($emp_whr, '', '', 'row', '', '', '', '', $emp_col);
					$first_name = empty_check($emp_res->first_name);
						$last_name = empty_check($emp_res->last_name);

						$arr = array($first_name,$last_name);
						$emp_name =join(" ",$arr);
					$dateObj   = DateTime::createFromFormat('!m', $month_id);
					$monthName = $dateObj->format('F'); // March

					$emp_data = array(
						'employee_name'  => $emp_name,
						'duration_month' => $monthName,
						'duration_year'  => $year_val,
					);

					$result_data = array(
						'ord_data' => $order_list,
						'emp_data' => $emp_data,
					);

			    	$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $result_data;
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

		// Employee overall collection
		// ***************************************************
		public function collection_report($param1="",$param2="",$param3="")
		{
			$month_id = $this->input->post('month_id');
		    $year_id  = $this->input->post('year_id');
		    $method   = $this->input->post('method');

		    if($method == '_getEmployeeCollectionData')
		    {
		    	$error    = FALSE;
				$required = array('month_id', 'year_id');
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
			    	// Year Details
			    	$year_whr = array('id' => $year_id);
					$year_col = 'year_value';
					$year_res = $this->commom_model->getYear($year_whr, '', '', 'row', '', '', '', '', $year_col);
					$year_val = empty_check($year_res->year_value);

			    	$month_count = cal_days_in_month(CAL_GREGORIAN, $month_id, $year_val);

			    	$collection_list  = [];
			    	for ($i=1; $i <= $month_count; $i++) {

			    		$date_val = date('d-m-Y', strtotime($i.'-'.$month_id.'-'.$year_val));
						$day_val  = date('l', strtotime($date_val));

						$start_date  = date('Y-m-d H:i:s', strtotime($i.'-'.$month_id.'-'.$year_val.' 00:00:00'));
						$end_date    = date('Y-m-d H:i:s', strtotime($i.'-'.$month_id.'-'.$year_val.' 23:59:59'));

						// Cash value
				    	$whr_1 = array(
				    		'A.createdate >='   => $start_date,
							'A.createdate <='   => $end_date,
				    		'A.bill_code'       => 'REC',
							'A.amt_type'        => '1',
							'A.collection_type' => '2',
							'A.published'       => '1',
				    	);

						$col_1 = 'SUM(A.amount) AS coll_amt';
						$res_1 = $this->payment_model->getOutletPaymentJoin($whr_1, '', '', 'row', '', '', '', '', $col_1);
						$val_1 = zero_check($res_1->coll_amt);

						// Cheque value
				    	$whr_2 = array(
				    		'A.createdate >='   => $start_date,
							'A.createdate <='   => $end_date,
				    		'A.bill_code'       => 'REC',
							'A.amt_type'        => '2',
							'A.collection_type' => '2',
							'A.published'       => '1',
				    	);

						$col_2 = 'SUM(A.amount) AS coll_amt';
						$res_2 = $this->payment_model->getOutletPaymentJoin($whr_2, '', '', 'row', '', '', '', '', $col_2);
						$val_2 = zero_check($res_2->coll_amt);

						// Others value
				    	$whr_3 = array(
				    		'A.createdate >='   => $start_date,
							'A.createdate <='   => $end_date,
				    		'A.bill_code'       => 'REC',
							'A.amt_type'        => '3',
							'A.collection_type' => '2',
							'A.published'       => '1',
				    	);

						$col_3 = 'SUM(A.amount) AS coll_amt';
						$res_3 = $this->payment_model->getOutletPaymentJoin($whr_3, '', '', 'row', '', '', '', '', $col_3);
						$val_3 = zero_check($res_3->coll_amt);

						$collection_list[] = array(
							'date'         => $date_val,
							'day'          => $day_val,
							'cash_value'   => round($val_1),
							'cheque_value' => round($val_2),
							'others_value' => round($val_3),
						);
			    	}

			    	$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $collection_list;
			        echo json_encode($response);
			        return;
			    }
		    }

		    else if($method == '_getEmployeeMonthCollectionData')
		    {
		    	$error    = FALSE;
				$required = array('month_id', 'year_id');
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
			    	// Year Details
			    	$year_whr = array('id' => $year_id);
					$year_col = 'year_value';
					$year_res = $this->commom_model->getYear($year_whr, '', '', 'row', '', '', '', '', $year_col);
					$year_val = empty_check($year_res->year_value);

			    	$month_count = cal_days_in_month(CAL_GREGORIAN, $month_id, $year_val);

			    	$data_list  = [];			    	
			    	for ($i=1; $i <= $month_count; $i++) {

			    		$date_val = date('d-m-Y', strtotime($i.'-'.$month_id.'-'.$year_val));
						$day_val  = date('l', strtotime($date_val));

						$start_date  = date('Y-m-d H:i:s', strtotime($i.'-'.$month_id.'-'.$year_val.' 00:00:00'));
						$end_date    = date('Y-m-d H:i:s', strtotime($i.'-'.$month_id.'-'.$year_val.' 23:59:59'));


						// Cash value
				    	$whr_1 = array(
				    		'A.createdate >='   => $start_date,
							'A.createdate <='   => $end_date,
				    		'A.bill_code'       => 'REC',
							'A.collection_type' => '2',
							'A.published'       => '1',
				    	);

						$col_1 = 'A.id, D.name AS zone_name, B.username AS emp_name, E.invoice_no, C.company_name AS outlet_name, A.bill_no AS receipt_no, A.amount, A.discount, A.amt_type, A.createdate';
						$res_1 = $this->payment_model->getOutletPaymentJoin($whr_1, '', '', 'result', '', '', '', '', $col_1);

						if($res_1)
						{
							$coll_list  = [];
							foreach ($res_1 as $key => $val) {

								$amt_type = zero_check($val->amt_type);
								$amount   = zero_check($val->amount);
								$cash     = 0;
							    $cheque   = 0;
							    $others   = 0;

								if($amt_type == 1)
							    {
							    	$cash      = $amount;
							    }
							    else if($amt_type == 2)
							    {
							    	$cheque      = $amount;
							    }
							    else if($amt_type == 3)
							    {
							    	$others      = $amount;
							    }

								$coll_list[] = array(
									'id'          => empty_check($val->id),
						            'zone_name'   => empty_check($val->zone_name),
						            'emp_name'    => !empty($val->emp_name)?$val->emp_name:'Admin',
						            'invoice_no'  => empty_check($val->invoice_no),
						            'outlet_name' => empty_check($val->outlet_name),
						            'receipt_no'  => empty_check($val->receipt_no),
						            'cash'        => zero_check($cash),
						            'cheque'      => zero_check($cheque),
						            'others'      => zero_check($others),
						            'discount'    => zero_check($val->discount),
						            'amt_type'    => zero_check($val->amt_type),
						            'createdate'  => date_check($val->createdate),
								);
							}
						}
						else
						{
							$coll_list  = [];
						}

						$data_list[] = array(
							'date_val'   => $date_val,
							'coll_data'  => $coll_list,
						);
			    	}

			    	$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $data_list;
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
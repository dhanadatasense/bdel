<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Accounts extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('commom_model');
			$this->load->model('vendors_model');
			$this->load->model('invoice_model');
			$this->load->model('payment_model');
			$this->load->model('purchase_model');
			$this->load->model('attendance_model');
			$this->load->model('outlets_model');
			$this->load->model('order_model');
			$this->load->model('distributors_model');
			$this->load->model('employee_model');
			$this->load->model('target_model');
			$this->load->model('assignproduct_model');
			$this->load->model('login_model');
			$this->load->model('user_model');
			$this->load->model('distributorpurchase_model');
			$this->load->model('stock_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Attendace Overall Report
		// ***************************************************
		public function accounts_data($param1="",$param2="",$param3="")
		{
			$method         = $this->input->post('method');
			$start_date     = $this->input->post('start_date');
			$end_date       = $this->input->post('end_date');
			$vendor_id      = $this->input->post('vendor_id');
			$outlet_id      = $this->input->post('outlet_id');
			$distributor_id = $this->input->post('distributor_id');

			if($method == '_CashbookReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date');
			    foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error)
			    {
			        $response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(count($errors)==0)
			    {
			    	if($start_date <= $end_date)
			    	{
			    		$start_value = date('Y-m-d', strtotime($start_date));
				    	$end_value   = date('Y-m-d', strtotime($end_date));
				    	$date_list   = getBetweenDates($start_value, $end_value);

				    	if(!empty($date_list))
				    	{

				    		$data_list  = [];
				    		$date_count = count($date_list);

				    		for ($i=0; $i < $date_count; $i++) { 
				    				
				    			$date_value = date('Y-m-d', strtotime($date_list[$i]));

				    			// Expense Entry
				    			$whr_1 = array(
				    				'expense_date' => $date_value,
				    				'expense_type' => '1',
				    				'published'    => '1',
				    			);

				    			$col_1 = 'expense_no, expense_id, expense_date, expense_val, expense_type';
				    			$res_1 = $this->commom_model->getExpensesEntry($whr_1, '', '', 'result', '', '', '', '', $col_1);

				    			if(!empty($res_1))
				    			{
				    				foreach ($res_1 as $key => $val_1) {
				    					$exp_no   = !empty($val_1->expense_no)?$val_1->expense_no:'';
							            $exp_id   = !empty($val_1->expense_id)?$val_1->expense_id:'';
							            $exp_date = !empty($val_1->expense_date)?$val_1->expense_date:'';
							            $exp_val  = !empty($val_1->expense_val)?$val_1->expense_val:'0';
							            $exp_type = !empty($val_1->expense_type)?$val_1->expense_type:'';

							            // Expense Details
							            $exp_whr  = array('id' => $exp_id);
							            $exp_col  = 'name';
							            $exp_res  = $this->commom_model->getExpenses($exp_whr, '', '', 'result', '', '', '', '', $exp_col);
							            $exp_name = !empty($exp_res[0]->name)?$exp_res[0]->name:'';


							            $exp_list = array(
							            	'voucher_no'    => $exp_no,
								            'particular'    => $exp_name,
								            'voucher_date'  => date('d-M-Y', strtotime($exp_date)),
								            'amount_value'  => number_format((float)$exp_val, 2, '.', ''),
								            'voucher_type'  => '1', // Debit
							            );

							            array_push($data_list, $exp_list);
				    				}
				    			}

						    	// Vendor Payment
				    			$whr_2 = array(
				    				'date'      => $date_value,
				    				'amt_type'  => '1',
				    				'published' => '1',
				    			);

				    			$col_2 = 'vendor_id, bill_no, amount, amt_type, date';
				    			$res_2 = $this->payment_model->getVendorPayment($whr_2, '', '', 'result', '', '', '', '', $col_2);	

				    			if(!empty($res_2))
				    			{
				    				foreach ($res_2 as $key => $val_2) {
				    					$vendor_id = !empty($val_2->vendor_id)?$val_2->vendor_id:'';
										$bill_no   = !empty($val_2->bill_no)?$val_2->bill_no:'';
										$amount    = !empty($val_2->amount)?$val_2->amount:'0';
										$amt_type  = !empty($val_2->amt_type)?$val_2->amt_type:'';
										$pay_date  = !empty($val_2->date)?$val_2->date:'';

										$ven_whr = array('id' => $vendor_id);
										$ven_col = 'company_name';
										$ven_res  = $this->vendors_model->getVendors($ven_whr, '', '', 'result', '', '', '', '', $ven_col);
							            $ven_name = !empty($ven_res[0]->company_name)?$ven_res[0]->company_name:'';

							            $ven_list = array(
											'voucher_no'   => $bill_no,
							            	'particular'   => $ven_name,
											'voucher_date' => date('d-M-Y', strtotime($pay_date)),
											'amount_value' => number_format((float)$amount, 2, '.', ''),
											'voucher_type' => '1', // Debit
							            );

							            array_push($data_list, $ven_list);
				    				}
				    			}

						    	// Distributor Receipt

						    	$whr_3 = array(
				    				'date'      => $date_value,
				    				'amt_type'  => '1',
				    				'published' => '1',
				    			);

				    			$col_3 = 'distributor_id, bill_no, amount, amt_type, date';
				    			$res_3 = $this->payment_model->getDistributorPayment($whr_3, '', '', 'result', '', '', '', '', $col_3);	

				    			if(!empty($res_3))
				    			{
				    				foreach ($res_3 as $key => $val_3) {
				    					$dis_id  = !empty($val_3->distributor_id)?$val_3->distributor_id:'';
										$bill_no = !empty($val_3->bill_no)?$val_3->bill_no:'';
										$amount  = !empty($val_3->amount)?$val_3->amount:'0';
										$date    = !empty($val_3->date)?$val_3->date:'';

										$dis_whr = array('id' => $dis_id);
										$dis_col = 'company_name';
										$dis_res  = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);
							            $dis_name = !empty($dis_res[0]->company_name)?$dis_res[0]->company_name:'';

							            $dis_list = array(
											'voucher_no'   => $bill_no,
							            	'particular'   => $dis_name,
											'voucher_date' => date('d-M-Y', strtotime($date)),
											'amount_value' => number_format((float)$amount, 2, '.', ''),
											'voucher_type' => '2', // Credit
							            );

							            array_push($data_list, $dis_list);
				    				}
				    			}
				    		}

				    		$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $data_list;
					        echo json_encode($response);
					        return;
				    	}
				    	else
				    	{
				    		$response['status']  = 0;
					        $response['message'] = "Date not found"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
				    	}
				    }
				    else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Date incorrect"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
				}
			}

			else if($method == '_BankEntryReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date');
			    foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error)
			    {
			        $response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(count($errors)==0)
			    {
			    	if($start_date <= $end_date)
			    	{
			    		$start_value = date('Y-m-d', strtotime($start_date));
				    	$end_value   = date('Y-m-d', strtotime($end_date));
				    	$date_list   = getBetweenDates($start_value, $end_value);

				    	if(!empty($date_list))
				    	{

				    		$data_list  = [];
				    		$date_count = count($date_list);

				    		for ($i=0; $i < $date_count; $i++) { 
				    				
				    			$date_value = date('Y-m-d', strtotime($date_list[$i]));

				    			// Expense Entry
				    			$whr_1 = array(
				    				'expense_date'    => $date_value,
				    				'expense_type !=' => '1',
				    				'published'       => '1',
				    			);

				    			$col_1 = 'expense_no, expense_id, expense_date, expense_val, expense_type';
				    			$res_1 = $this->commom_model->getExpensesEntry($whr_1, '', '', 'result', '', '', '', '', $col_1);

				    			if(!empty($res_1))
				    			{
				    				foreach ($res_1 as $key => $val_1) {
				    					$exp_no   = !empty($val_1->expense_no)?$val_1->expense_no:'';
							            $exp_id   = !empty($val_1->expense_id)?$val_1->expense_id:'';
							            $exp_date = !empty($val_1->expense_date)?$val_1->expense_date:'';
							            $exp_val  = !empty($val_1->expense_val)?$val_1->expense_val:'0';
							            $exp_type = !empty($val_1->expense_type)?$val_1->expense_type:'';

							            // Expense Details
							            $exp_whr  = array('id' => $exp_id);
							            $exp_col  = 'name';
							            $exp_res  = $this->commom_model->getExpenses($exp_whr, '', '', 'result', '', '', '', '', $exp_col);
							            $exp_name = !empty($exp_res[0]->name)?$exp_res[0]->name:'';


							            $exp_list = array(
							            	'voucher_no'    => $exp_no,
								            'particular'    => $exp_name,
								            'voucher_date'  => date('d-M-Y', strtotime($exp_date)),
								            'amount_value'  => number_format((float)$exp_val, 2, '.', ''),
								            'voucher_type'  => '1', // Debit
							            );

							            array_push($data_list, $exp_list);
				    				}
				    			}

						    	// Vendor Payment
				    			$whr_2 = array(
				    				'date'      => $date_value,
				    				'bill_code' => 'PAY',
				    				'amt_type'  => '2,3',
				    				'published' => '1',
				    			);

				    			$col_2 = 'vendor_id, bill_no, amount, amt_type, date';
				    			$res_2 = $this->payment_model->getVendorPaymentImplode($whr_2, '', '', 'result', '', '', '', '', $col_2);	

				    			if(!empty($res_2))
				    			{
				    				foreach ($res_2 as $key => $val_2) {
				    					$vendor_id = !empty($val_2->vendor_id)?$val_2->vendor_id:'';
										$bill_no   = !empty($val_2->bill_no)?$val_2->bill_no:'';
										$amount    = !empty($val_2->amount)?$val_2->amount:'0';
										$amt_type  = !empty($val_2->amt_type)?$val_2->amt_type:'';
										$pay_date  = !empty($val_2->date)?$val_2->date:'';

										$ven_whr = array('id' => $vendor_id);
										$ven_col = 'company_name';
										$ven_res  = $this->vendors_model->getVendors($ven_whr, '', '', 'result', '', '', '', '', $ven_col);
							            $ven_name = !empty($ven_res[0]->company_name)?$ven_res[0]->company_name:'';

							            $ven_list = array(
											'voucher_no'   => $bill_no,
							            	'particular'   => $ven_name,
											'voucher_date' => date('d-M-Y', strtotime($pay_date)),
											'amount_value' => number_format((float)$amount, 2, '.', ''),
											'voucher_type' => '1', // Debit
							            );

							            array_push($data_list, $ven_list);
				    				}
				    			}

						    	// Distributor Receipt
						    	$whr_3 = array(
				    				'date'      => $date_value,
				    				'bill_code' => 'REC',
				    				'amt_type'  => '2,3',
				    				'published' => '1',
				    			);

				    			$col_3 = 'distributor_id, bill_no, amount, amt_type, date';
				    			$res_3 = $this->payment_model->getDistributorPaymentImplode($whr_3, '', '', 'result', '', '', '', '', $col_3);	

				    			if(!empty($res_3))
				    			{
				    				foreach ($res_3 as $key => $val_3) {
				    					$dis_id  = !empty($val_3->distributor_id)?$val_3->distributor_id:'';
										$bill_no = !empty($val_3->bill_no)?$val_3->bill_no:'';
										$amount  = !empty($val_3->amount)?$val_3->amount:'0';
										$date    = !empty($val_3->date)?$val_3->date:'';

										$dis_whr = array('id' => $dis_id);
										$dis_col = 'company_name';
										$dis_res  = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);
							            $dis_name = !empty($dis_res[0]->company_name)?$dis_res[0]->company_name:'';

							            $dis_list = array(
											'voucher_no'   => $bill_no,
							            	'particular'   => $dis_name,
											'voucher_date' => date('d-M-Y', strtotime($date)),
											'amount_value' => number_format((float)$amount, 2, '.', ''),
											'voucher_type' => '2', // Credit
							            );

							            array_push($data_list, $dis_list);
				    				}
				    			}
				    		}

				    		$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $data_list;
					        echo json_encode($response);
					        return;
				    	}
				    	else
				    	{
				    		$response['status']  = 0;
					        $response['message'] = "Date not found"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
				    	}
				    }
				    else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Date incorrect"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
				}
			}

			else if($method == '_AdminPaymentReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date');
			    foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error)
			    {
			        $response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(count($errors)==0)
			    {
			    	if($start_date <= $end_date)
			    	{
			    		$start_value = date('Y-m-d', strtotime($start_date));
				    	$end_value   = date('Y-m-d', strtotime($end_date));
				    	$date_list   = getBetweenDates($start_value, $end_value);

				    	if(!empty($date_list))
				    	{
				    		$data_list  = [];
				    		$date_count = count($date_list);

				    		for ($i=0; $i < $date_count; $i++) {

				    			$date_value = date('Y-m-d', strtotime($date_list[$i]));

				    			// Expense Entry
				    			$whr_1 = array(
				    				'expense_date' => $date_value,
				    				'published'    => '1',
				    			);

				    			$col_1 = 'expense_no, expense_id, expense_date, expense_val, expense_type';
				    			$res_1 = $this->commom_model->getExpensesEntry($whr_1, '', '', 'result', '', '', '', '', $col_1);

				    			if(!empty($res_1))
				    			{
				    				foreach ($res_1 as $key => $val_1) {
				    					$exp_no   = !empty($val_1->expense_no)?$val_1->expense_no:'';
							            $exp_id   = !empty($val_1->expense_id)?$val_1->expense_id:'';
							            $exp_date = !empty($val_1->expense_date)?$val_1->expense_date:'';
							            $exp_val  = !empty($val_1->expense_val)?$val_1->expense_val:'0';
							            $exp_type = !empty($val_1->expense_type)?$val_1->expense_type:'';

							            // Expense Details
							            $exp_whr  = array('id' => $exp_id);
							            $exp_col  = 'name';
							            $exp_res  = $this->commom_model->getExpenses($exp_whr, '', '', 'result', '', '', '', '', $exp_col);
							            $exp_name = !empty($exp_res[0]->name)?$exp_res[0]->name:'';


							            $exp_list = array(
							            	'voucher_no'    => $exp_no,
								            'particular'    => $exp_name,
								            'voucher_date'  => date('d-M-Y', strtotime($exp_date)),
								            'amount_value'  => number_format((float)$exp_val, 2, '.', ''),
								            'voucher_type'  => '1', // Debit
								            'bill_no'       => '',
								            'payment_type'  => $exp_type,
								            'narration'     => 'Expense Entry',
								            'bank_name'     => '',
											'cheque_no'     => '',
											'collect_date'  => '',
							            );

							            array_push($data_list, $exp_list);
				    				}
				    			}

				    			// Vendor Payment
				    			$whr_2 = array(
				    				'date'      => $date_value,
				    				'amt_type'  => '1,2,3',
				    				'published' => '1',
				    			);

				    			$col_2 = 'vendor_id, bill_id, bill_no, amount, amt_type, date, bank_name, cheque_no, collect_date';
				    			$res_2 = $this->payment_model->getVendorPaymentImplode($whr_2, '', '', 'result', '', '', '', '', $col_2);	

				    			if(!empty($res_2))
				    			{
				    				foreach ($res_2 as $key => $val_2) {
				    					$vendor_id    = !empty($val_2->vendor_id)?$val_2->vendor_id:'';
										$bill_no      = !empty($val_2->bill_no)?$val_2->bill_no:'';
										$bill_id      = !empty($val_2->bill_id)?$val_2->bill_id:'';
										$amount       = !empty($val_2->amount)?$val_2->amount:'0';
										$amt_type     = !empty($val_2->amt_type)?$val_2->amt_type:'';
										$pay_date     = !empty($val_2->date)?$val_2->date:'';
										$bank_name    = !empty($val_2->bank_name)?$val_2->bank_name:'';
										$cheque_no    = !empty($val_2->cheque_no)?$val_2->cheque_no:'';
										$collect_date = !empty($val_2->collect_date)?$val_2->collect_date:'';

										$ven_whr = array('id' => $vendor_id);
										$ven_col = 'company_name';
										$ven_res  = $this->vendors_model->getVendors($ven_whr, '', '', 'result', '', '', '', '', $ven_col);
							            $ven_name = !empty($ven_res[0]->company_name)?$ven_res[0]->company_name:'';

							            // Bill number
							            $bill_whr = array('order_id' => $bill_id);
										$bill_col = 'invoice_no';
										$bill_res = $this->invoice_model->getVendorInvoice($bill_whr, '', '', 'result', '', '', '', '', $bill_col);
							            $bill_num = !empty($bill_res[0]->invoice_no)?$bill_res[0]->invoice_no:'';

							            $collect_value = '';
							            if($collect_date != '1970-01-01' && !empty($collect_date))
							            {
							            	$collect_value = date('d-M-Y', strtotime($collect_date));
							            }

							            $ven_list = array(
											'voucher_no'   => $bill_no,
							            	'particular'   => $ven_name,
											'voucher_date' => date('d-M-Y', strtotime($pay_date)),
											'amount_value' => number_format((float)$amount, 2, '.', ''),
											'voucher_type' => '1', // Debit
											'bill_no'      => $bill_num,
											'payment_type' => $amt_type,
											'narration'    => 'Paid for bill no '.$bill_num,
											'bank_name'    => $bank_name,
											'cheque_no'    => $cheque_no,
											'collect_date' => $collect_value,
							            );

							            array_push($data_list, $ven_list);
				    				}
				    			}

				    			// Distributor Sales return Receipt
						    	$whr_3 = array(
				    				'date'      => $date_value,
				    				'amt_type'  => '4',
				    				'published' => '1',
				    			);

				    			$col_3 = 'distributor_id, bill_id, bill_no, amount, amt_type, date';
				    			$res_3 = $this->payment_model->getDistributorPayment($whr_3, '', '', 'result', '', '', '', '', $col_3);	

				    			if(!empty($res_3))
				    			{
				    				foreach ($res_3 as $key => $val_3) {
				    					$dis_id   = !empty($val_3->distributor_id)?$val_3->distributor_id:'';
				    					$bill_id  = !empty($val_3->bill_id)?$val_3->bill_id:'';
										$bill_no  = !empty($val_3->bill_no)?$val_3->bill_no:'';
										$amount   = !empty($val_3->amount)?$val_3->amount:'0';
										$date     = !empty($val_3->date)?$val_3->date:'';
										$amt_type = !empty($val_3->amt_type)?$val_3->amt_type:'';

										$dis_whr = array('id' => $dis_id);
										$dis_col = 'company_name';
										$dis_res  = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);
							            $dis_name = !empty($dis_res[0]->company_name)?$dis_res[0]->company_name:'';

							            // Bill number
							            $bill_whr = array('order_id' => $bill_id);
										$bill_col = 'invoice_no';
										$bill_res = $this->invoice_model->getDistributorInvoice($bill_whr, '', '', 'result', '', '', '', '', $bill_col);
							            $bill_num = !empty($bill_res[0]->invoice_no)?$bill_res[0]->invoice_no:'';

							            $dis_list = array(
											'voucher_no'   => $bill_no,
							            	'particular'   => $dis_name,
											'voucher_date' => date('d-M-Y', strtotime($date)),
											'amount_value' => number_format((float)$amount, 2, '.', ''),
											'voucher_type' => '2', // Credit
											'bill_no'      => $bill_num,
											'payment_type' => $amt_type,
											'narration'    => 'Sales Return',
											'bank_name'    => '',
											'cheque_no'    => '',
											'collect_date' => '',
							            );

							            array_push($data_list, $dis_list);
				    				}
				    			}
				    		}

				    		$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $data_list;
					        echo json_encode($response);
					        return;
				    	}
				    	else
				    	{
				    		$response['status']  = 0;
					        $response['message'] = "Date not found"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
				    	}
				    }
				    else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Date incorrect"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
				}
			}

			else if($method == '_AdminReceiptReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date');
			    foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error)
			    {
			        $response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(count($errors)==0)
			    {
			    	if($start_date <= $end_date)
			    	{
			    		$start_value = date('Y-m-d', strtotime($start_date));
				    	$end_value   = date('Y-m-d', strtotime($end_date));
				    	$date_list   = getBetweenDates($start_value, $end_value);

				    	if(!empty($date_list))
				    	{
				    		$data_list  = [];
				    		$date_count = count($date_list);

				    		for ($i=0; $i < $date_count; $i++) {

				    			$date_value = date('Y-m-d', strtotime($date_list[$i]));

				    			// Distributor Receipt
						    	$whr_1 = array(
				    				'date'      => $date_value,
				    				'amt_type'  => '1,2,3',
				    				'published' => '1',
				    			);

				    			$col_1 = 'distributor_id, bill_id, bill_no, amount, amt_type, date, bank_name, cheque_no, collect_date';
				    			$res_1 = $this->payment_model->getDistributorPaymentImplode($whr_1, '', '', 'result', '', '', '', '', $col_1);	

				    			if(!empty($res_1))
				    			{
				    				foreach ($res_1 as $key => $val_1) {
				    					$dis_id       = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
				    					$bill_id      = !empty($val_1->bill_id)?$val_1->bill_id:'';
										$bill_no      = !empty($val_1->bill_no)?$val_1->bill_no:'';
										$amount       = !empty($val_1->amount)?$val_1->amount:'0';
										$date         = !empty($val_1->date)?$val_1->date:'';
										$amt_type     = !empty($val_1->amt_type)?$val_1->amt_type:'';
										$bank_name    = !empty($val_1->bank_name)?$val_1->bank_name:'';
										$cheque_no    = !empty($val_1->cheque_no)?$val_1->cheque_no:'';
										$collect_date = !empty($val_1->collect_date)?$val_1->collect_date:'';

										$dis_whr = array('id' => $dis_id);
										$dis_col = 'company_name';
										$dis_res  = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);
							            $dis_name = !empty($dis_res[0]->company_name)?$dis_res[0]->company_name:'';

							            // Bill number
							            $bill_whr = array('order_id' => $bill_id);
										$bill_col = 'invoice_no';
										$bill_res = $this->invoice_model->getDistributorInvoice($bill_whr, '', '', 'result', '', '', '', '', $bill_col);
							            $bill_num = !empty($bill_res[0]->invoice_no)?$bill_res[0]->invoice_no:'';

							            $collect_value = '';
							            if($collect_date != '1970-01-01' && !empty($collect_date))
							            {
							            	$collect_value = date('d-M-Y', strtotime($collect_date));
							            }

							            $dis_list = array(
											'voucher_no'   => $bill_no,
							            	'particular'   => $dis_name,
											'voucher_date' => date('d-M-Y', strtotime($date)),
											'amount_value' => number_format((float)$amount, 2, '.', ''),
											'voucher_type' => '2', // Credit
											'bill_no'      => $bill_num,
											'payment_type' => $amt_type,
											'narration'    => 'Received for bill on '.$bill_num,
											'bank_name'    => $bank_name,
											'cheque_no'    => $cheque_no,
											'collect_date' => $collect_value,
							            );

							            array_push($data_list, $dis_list);
				    				}
				    			}

				    			// Vendor Payment
				    			$whr_2 = array(
				    				'date'      => $date_value,
				    				'amt_type'  => '4',
				    				'published' => '1',
				    			);

				    			$col_2 = 'vendor_id, bill_id, bill_no, amount, amt_type, date';
				    			$res_2 = $this->payment_model->getVendorPaymentImplode($whr_2, '', '', 'result', '', '', '', '', $col_2);	

				    			if(!empty($res_2))
				    			{
				    				foreach ($res_2 as $key => $val_2) {
				    					$vendor_id = !empty($val_2->vendor_id)?$val_2->vendor_id:'';
										$bill_no   = !empty($val_2->bill_no)?$val_2->bill_no:'';
										$bill_id   = !empty($val_2->bill_id)?$val_2->bill_id:'';
										$amount    = !empty($val_2->amount)?$val_2->amount:'0';
										$amt_type  = !empty($val_2->amt_type)?$val_2->amt_type:'';
										$pay_date  = !empty($val_2->date)?$val_2->date:'';

										$ven_whr = array('id' => $vendor_id);
										$ven_col = 'company_name';
										$ven_res  = $this->vendors_model->getVendors($ven_whr, '', '', 'result', '', '', '', '', $ven_col);
							            $ven_name = !empty($ven_res[0]->company_name)?$ven_res[0]->company_name:'';

							            // Bill number
							            $bill_whr = array('order_id' => $bill_id);
										$bill_col = 'invoice_no';
										$bill_res = $this->invoice_model->getVendorInvoice($bill_whr, '', '', 'result', '', '', '', '', $bill_col);
							            $bill_num = !empty($bill_res[0]->invoice_no)?$bill_res[0]->invoice_no:'';

							            $ven_list = array(
											'voucher_no'   => $bill_no,
							            	'particular'   => $ven_name,
											'voucher_date' => date('d-M-Y', strtotime($pay_date)),
											'amount_value' => number_format((float)$amount, 2, '.', ''),
											'voucher_type' => '1', // Debit
											'bill_no'      => $bill_num,
											'payment_type' => $amt_type,
											'narration'    => 'Paid for bill on '.$bill_num,
											'bank_name'    => '',
											'cheque_no'    => '',
											'collect_date' => '',
							            );

							            array_push($data_list, $ven_list);
				    				}
				    			}
				    		}

				    		$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $data_list;
					        echo json_encode($response);
					        return;
				    	}
				    	else
				    	{
				    		$response['status']  = 0;
					        $response['message'] = "Date not found"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
				    	}
				    }
				    else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Date incorrect"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
				}
			}

			else if($method == '_VendorLedgerReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date', 'vendor_id');
			    foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error)
			    {
			        $response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(count($errors)==0)
			    {
			    	if($start_date <= $end_date)
			    	{
			    		$start_value = date('Y-m-d', strtotime($start_date));
				    	$end_value   = date('Y-m-d', strtotime($end_date));
				    	$date_list   = getBetweenDates($start_value, $end_value);

				    	if(!empty($date_list))
				    	{
				    		$data_list  = [];
				    		$date_count = count($date_list);

				    		for ($i=0; $i < $date_count; $i++) {

				    			$date_value = date('Y-m-d', strtotime($date_list[$i]));

					    		// Purchase Invoice
					    		$whr_1 = array(
				    				'date'      => $date_value,
				    				'vendor_id' => $vendor_id,
				    				'bill_code' => 'INV',
				    				'published' => '1',
				    			);

				    			$col_1 = 'bill_no, description, amount, discount, amt_type, date';
				    			$res_1 = $this->payment_model->getVendorPayment($whr_1, '', '', 'result', '', '', '', '', $col_1);

				    			if(!empty($res_1))
				    			{
				    				foreach ($res_1 as $key => $val_1) {
				    					$bill_no  = !empty($val_1->bill_no)?$val_1->bill_no:'';
				    					$desc     = !empty($val_1->description)?$val_1->description:'';
							            $amount   = !empty($val_1->amount)?$val_1->amount:'0';
							            $discount = !empty($val_1->discount)?$val_1->discount:'0';
							            $amt_type = !empty($val_1->amt_type)?$val_1->amt_type:'';
							            $date_val = !empty($val_1->date)?$val_1->date:'';
							            $amt_val  = $amount - $discount;

							            // Vendor Details
							            $ven_whr = array('id' => $vendor_id);
							            $ven_col = 'state_id';
							            $ven_res = $this->vendors_model->getVendors($ven_whr, '', '', 'result', '', '', '', '', $ven_col);
							            $ven_std = !empty($ven_res[0]->state_id)?$ven_res[0]->state_id:'';

							            // Admin Details
							            $adm_whr = array('id' => '1');
							            $adm_col = 'state_id';
							            $adm_res = $this->login_model->getLoginStatus($adm_whr, '', '', 'result', '', '', '', '', $adm_col);

							            $adm_std = !empty($adm_res[0]->state_id)?$adm_res[0]->state_id:'';

							            $particular = 'Invoice';

							            $inv_list = array(
							            	'voucher_no'    => $bill_no,
								            'particular'    => $particular,
								            'voucher_date'  => date('d-M-Y', strtotime($date_val)),
								            'amount_value'  => number_format((float)$amt_val, 2, '.', ''),
								            'voucher_type'  => $amt_type,
								            'data_type'     => '1', // Credit
							            );

							            array_push($data_list, $inv_list);
				    				}
				    			}

					    		// Vendor Payment
					    		$whr_2 = array(
				    				'date'      => $date_value,
				    				'vendor_id' => $vendor_id,
				    				'bill_code' => 'PAY',
				    				'published' => '1',
				    			);

				    			$col_2 = 'bill_no, description, amount, discount, amt_type, date';
				    			$res_2 = $this->payment_model->getVendorPayment($whr_2, '', '', 'result', '', '', '', '', $col_2);

				    			if(!empty($res_2))
				    			{
				    				foreach ($res_2 as $key => $val_2) {
				    					$bill_no  = !empty($val_2->bill_no)?$val_2->bill_no:'';
				    					$desc     = !empty($val_2->description)?$val_2->description:'Cash';
							            $amount   = !empty($val_2->amount)?$val_2->amount:'0';
							            $discount = !empty($val_2->discount)?$val_2->discount:'0';
							            $amt_type = !empty($val_2->amt_type)?$val_2->amt_type:'';
							            $date_val = !empty($val_2->date)?$val_2->date:'';
							            $amt_val  = $amount + $discount;

							            if($amt_type == 4)
							            {
							            	$desc_val = 'Purchase Return';
							            }
							            else
							            {
							            	$desc_val = $desc;
							            }

							            $pay_list = array(
							            	'voucher_no'    => $bill_no,
								            'particular'    => $desc_val,
								            'voucher_date'  => date('d-M-Y', strtotime($date_val)),
								            'amount_value'  => number_format((float)$amt_val, 2, '.', ''),
								            'voucher_type'  => $amt_type,
								            'data_type'     => '2', // Debit	
							            );

							            array_push($data_list, $pay_list);
							        }
				    			}

					    		// Cheque Failure
				    			$whr_3 = array(
				    				'date'      => $date_value,
				    				'vendor_id' => $vendor_id,
				    				'bill_code' => 'PEN',
				    				'published' => '1',
				    			);

				    			$col_3 = 'bill_no, description, amount, discount, penalty_amt, bank_charge, amt_type, date';
				    			$res_3 = $this->payment_model->getVendorPayment($whr_3, '', '', 'result', '', '', '', '', $col_3);

				    			if(!empty($res_3))
				    			{
				    				foreach ($res_3 as $key => $val_3) {
				    					$bill_no  = !empty($val_3->bill_no)?$val_3->bill_no:'';
				    					$desc     = !empty($val_3->description)?$val_3->description:'';
							            $amount   = !empty($val_3->amount)?$val_3->amount:'0';
							            $discount = !empty($val_3->discount)?$val_3->discount:'0';
							            $amt_type = !empty($val_3->amt_type)?$val_3->amt_type:'';
							            $date_val = !empty($val_3->date)?$val_3->date:'';

							            $penalty_amt = !empty($val_3->penalty_amt)?$val_3->penalty_amt:'0';
							            $bank_charge = !empty($val_3->bank_charge)?$val_3->bank_charge:'0';

							            $amt_val  = $penalty_amt + $bank_charge;

							            $pen_list = array(
							            	'voucher_no'    => $bill_no,
								            'particular'    => $desc,
								            'voucher_date'  => date('d-M-Y', strtotime($date_val)),
								            'amount_value'  => number_format((float)$amt_val, 2, '.', ''),
								            'voucher_type'  => $amt_type,
								            'data_type'     => '1', // Credit
							            );

							            array_push($data_list, $pen_list);
							        }
				    			}

				    			// Purchase Return
				    			$whr_4 = array(
				    				'date'      => $date_value,
				    				'vendor_id' => $vendor_id,
				    				'bill_code' => 'REC',
				    				'published' => '1',
				    			);

				    			$col_4 = 'bill_no, description, amount, discount, penalty_amt, bank_charge, amt_type, date';
				    			$res_4 = $this->payment_model->getVendorPayment($whr_4, '', '', 'result', '', '', '', '', $col_4);

				    			if(!empty($res_4))
				    			{
				    				foreach ($res_4 as $key => $val_4) {
				    					$bill_no  = !empty($val_4->bill_no)?$val_4->bill_no:'';
				    					$desc     = !empty($val_4->description)?$val_4->description:'';
							            $amount   = !empty($val_4->amount)?$val_4->amount:'0';
							            $discount = !empty($val_4->discount)?$val_4->discount:'0';
							            $amt_type = !empty($val_4->amt_type)?$val_4->amt_type:'';
							            $date_val = !empty($val_4->date)?$val_4->date:'';

							            $penalty_amt = !empty($val_4->penalty_amt)?$val_4->penalty_amt:'0';
							            $bank_charge = !empty($val_4->bank_charge)?$val_4->bank_charge:'0';

							            $amt_val  = $amount + $discount;

							            $ret_list = array(
							            	'voucher_no'    => $bill_no,
								            'particular'    => $desc,
								            'voucher_date'  => date('d-M-Y', strtotime($date_val)),
								            'amount_value'  => number_format((float)$amt_val, 2, '.', ''),
								            'voucher_type'  => $amt_type,
								            'data_type'     => '1', // Credit
							            );

							            array_push($data_list, $ret_list);
							        }
				    			}
				    		}

				    		$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $data_list;
					        echo json_encode($response);
					        return;
				    	}
				    	else
				    	{
				    		$response['status']  = 0;
					        $response['message'] = "Date not found"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
				    	}
			    	}
			    	else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Date incorrect"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			    }
			}

			else if($method == '_DistributorLedgerReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date', 'distributor_id');
			    foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error)
			    {
			        $response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(count($errors)==0)
			    {
			    	if($start_date <= $end_date)
			    	{
			    		$start_value = date('Y-m-d', strtotime($start_date));
				    	$end_value   = date('Y-m-d', strtotime($end_date));
				    	$date_list   = getBetweenDates($start_value, $end_value);

				    	if(!empty($date_list))
				    	{
				    		$data_list  = [];
				    		$date_count = count($date_list);

				    		for ($i=0; $i < $date_count; $i++) {

				    			$date_value = date('Y-m-d', strtotime($date_list[$i]));

					    		// Sales Invoice
					    		$whr_1 = array(
				    				'date'           => $date_value,
				    				'distributor_id' => $distributor_id,
				    				'bill_code'      => 'INV',
				    				'published'      => '1',
				    			);

				    			$col_1 = 'bill_no, description, amount, discount, amt_type, date';
				    			$res_1 = $this->payment_model->getDistributorPayment($whr_1, '', '', 'result', '', '', '', '', $col_1);

				    			if(!empty($res_1))
				    			{
				    				foreach ($res_1 as $key => $val_1) {
				    					$bill_no  = !empty($val_1->bill_no)?$val_1->bill_no:'';
				    					$desc     = !empty($val_1->description)?$val_1->description:'';
							            $amount   = !empty($val_1->amount)?$val_1->amount:'0';
							            $discount = !empty($val_1->discount)?$val_1->discount:'0';
							            $amt_type = !empty($val_1->amt_type)?$val_1->amt_type:'';
							            $date_val = !empty($val_1->date)?$val_1->date:'';
							            $amt_val  = $amount - $discount;

							            // Distributor Details
							            $dis_whr = array('id' => $distributor_id);
							            $dis_col = 'state_id';
							            $dis_res = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);
							            $dis_std = !empty($dis_res[0]->state_id)?$dis_res[0]->state_id:'';

							            // Admin Details
							            $adm_whr = array('id' => '1');
							            $adm_col = 'state_id';
							            $adm_res = $this->login_model->getLoginStatus($adm_whr, '', '', 'result', '', '', '', '', $adm_col);

							            $adm_std = !empty($adm_res[0]->state_id)?$adm_res[0]->state_id:'';

							            $particular = 'Inter Sales';
							            if($adm_std == $dis_std)
							            {
							            	$particular = 'Local Sales';
							            }

							            $inv_list = array(
							            	'voucher_no'    => $bill_no,
								            'particular'    => $particular,
								            'voucher_date'  => date('d-M-Y', strtotime($date_val)),
								            'amount_value'  => number_format((float)$amt_val, 2, '.', ''),
								            'voucher_type'  => $amt_type,
								            'data_type'     => '1', // Debit
							            );

							            array_push($data_list, $inv_list);
				    				}
				    			}

					    		// Distributor Payment
					    		$whr_2 = array(
				    				'date'           => $date_value,
				    				'distributor_id' => $distributor_id,
				    				'bill_code'      => 'REC',
				    				'published'      => '1',
				    			);

				    			$col_2 = 'bill_no, description, amount, discount, amt_type, date';
				    			$res_2 = $this->payment_model->getDistributorPayment($whr_2, '', '', 'result', '', '', '', '', $col_2);

				    			if(!empty($res_2))
				    			{
				    				foreach ($res_2 as $key => $val_2) {
				    					$bill_no  = !empty($val_2->bill_no)?$val_2->bill_no:'';
				    					$desc     = !empty($val_2->description)?$val_2->description:'Cash';
							            $amount   = !empty($val_2->amount)?$val_2->amount:'0';
							            $discount = !empty($val_2->discount)?$val_2->discount:'0';
							            $amt_type = !empty($val_2->amt_type)?$val_2->amt_type:'';
							            $date_val = !empty($val_2->date)?$val_2->date:'';
							            $amt_val  = $amount + $discount;

							            $pay_list = array(
							            	'voucher_no'    => $bill_no,
								            'particular'    => $desc,
								            'voucher_date'  => date('d-M-Y', strtotime($date_val)),
								            'amount_value'  => number_format((float)$amt_val, 2, '.', ''),
								            'voucher_type'  => $amt_type,
								            'data_type'     => '2', // Credit
							            );

							            array_push($data_list, $pay_list);
							        }
				    			}

					    		// Cheque Failure
					    		$whr_3 = array(
				    				'date'           => $date_value,
				    				'distributor_id' => $distributor_id,
				    				'bill_code'      => 'PEN',
				    				'published'      => '1',
				    			);

				    			$col_3 = 'bill_no, description, amount, discount, penalty_amt, bank_charge, amt_type, date';
				    			$res_3 = $this->payment_model->getDistributorPayment($whr_3, '', '', 'result', '', '', '', '', $col_3);

				    			if(!empty($res_3))
				    			{
				    				foreach ($res_3 as $key => $val_3) {
				    					$bill_no  = !empty($val_3->bill_no)?$val_3->bill_no:'';
				    					$desc     = !empty($val_3->description)?$val_3->description:'';
							            $amount   = !empty($val_3->amount)?$val_3->amount:'0';
							            $discount = !empty($val_3->discount)?$val_3->discount:'0';
							            $amt_type = !empty($val_3->amt_type)?$val_3->amt_type:'';
							            $date_val = !empty($val_3->date)?$val_3->date:'';

							            $penalty_amt = !empty($val_3->penalty_amt)?$val_3->penalty_amt:'0';
							            $bank_charge = !empty($val_3->bank_charge)?$val_3->bank_charge:'0';

							            $amt_val  = $penalty_amt + $bank_charge;

							            $pen_list = array(
							            	'voucher_no'    => $bill_no,
								            'particular'    => $desc,
								            'voucher_date'  => date('d-M-Y', strtotime($date_val)),
								            'amount_value'  => number_format((float)$amt_val, 2, '.', ''),
								            'voucher_type'  => $amt_type,
								            'data_type'     => '1', // Debit
							            );

							            array_push($data_list, $pen_list);
							        }
				    			}
				    		}

				    		$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $data_list;
					        echo json_encode($response);
					        return;
				    	}
				    	else
				    	{
				    		$response['status']  = 0;
					        $response['message'] = "Date not found"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
				    	}
			    	}
			    	else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Date incorrect"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			    }
			}

			else if($method == '_DistributorCashbookReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date', 'distributor_id');
			    foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error)
			    {
			        $response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(count($errors)==0)
			    {
			    	if($start_date <= $end_date)
			    	{
			    		$start_value = date('Y-m-d', strtotime($start_date));
				    	$end_value   = date('Y-m-d', strtotime($end_date));
				    	$date_list   = getBetweenDates($start_value, $end_value);

				    	if(!empty($date_list))
				    	{
				    		$data_list  = [];
				    		$date_count = count($date_list);

				    		for ($i=0; $i < $date_count; $i++) { 
				    				
				    			$date_value = date('Y-m-d', strtotime($date_list[$i]));

				    			// Admin Payment
				    			$whr_1 = array(
				    				'date'           => $date_value,
				    				'distributor_id' => $distributor_id,
				    				'amt_type'       => '1',
				    				'published'      => '1',
				    			);

				    			$col_1 = 'distributor_id, bill_no, amount, amt_type, date';
				    			$res_1 = $this->payment_model->getDistributorPayment($whr_1, '', '', 'result', '', '', '', '', $col_1);	

				    			if(!empty($res_1))
				    			{
				    				foreach ($res_1 as $key => $val_1) {
				    					$distri_id = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
										$bill_no   = !empty($val_1->bill_no)?$val_1->bill_no:'';
										$amount    = !empty($val_1->amount)?$val_1->amount:'0';
										$amt_type  = !empty($val_1->amt_type)?$val_1->amt_type:'';
										$pay_date  = !empty($val_1->date)?$val_1->date:'';

										$dis_whr = array('id' => $distri_id);
										$dis_col = 'company_name';
										$dis_res  = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);
							            $dis_name = !empty($dis_res[0]->company_name)?$dis_res[0]->company_name:'';

							            $dis_list = array(
											'voucher_no'   => $bill_no,
							            	'particular'   => $dis_name,
											'voucher_date' => date('d-M-Y', strtotime($pay_date)),
											'amount_value' => number_format((float)$amount, 2, '.', ''),
											'voucher_type' => '2', // Credit
							            );

							            array_push($data_list, $dis_list);
				    				}
				    			}

				    			// Outlet Receipt
				    			$whr_2 = array(
				    				'date'           => $date_value,
				    				'distributor_id' => $distributor_id,
				    				'amt_type'       => '1',
				    				'published'      => '1',
				    			);

				    			$col_2 = 'outlet_id, bill_no, amount, amt_type, date';
				    			$res_2 = $this->payment_model->getOutletPayment($whr_2, '', '', 'result', '', '', '', '', $col_2);	

				    			if(!empty($res_2))
				    			{
				    				foreach ($res_2 as $key => $val_2) {
				    					$str_id  = !empty($val_2->outlet_id)?$val_2->outlet_id:'';
										$bill_no = !empty($val_2->bill_no)?$val_2->bill_no:'';
										$amount  = !empty($val_2->amount)?$val_2->amount:'0';
										$date    = !empty($val_2->date)?$val_2->date:'';

										$str_whr = array('id' => $str_id);
										$str_col = 'company_name';
										$str_res  = $this->outlets_model->getOutlets($str_whr, '', '', 'result', '', '', '', '', $str_col);
							            $str_name = !empty($str_res[0]->company_name)?$str_res[0]->company_name:'';

							            $str_list = array(
											'voucher_no'   => $bill_no,
							            	'particular'   => $str_name,
											'voucher_date' => date('d-M-Y', strtotime($date)),
											'amount_value' => number_format((float)$amount, 2, '.', ''),
											'voucher_type' => '1', // Debit
							            );

							            array_push($data_list, $str_list);
				    				}
				    			}
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
				        $response['message'] = "Date incorrect"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			    }
			}

			else if($method == '_DistributorBankEntryReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date', 'distributor_id');
			    foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error)
			    {
			        $response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(count($errors)==0)
			    {
			    	if($start_date <= $end_date)
			    	{
			    		$start_value = date('Y-m-d', strtotime($start_date));
				    	$end_value   = date('Y-m-d', strtotime($end_date));
				    	$date_list   = getBetweenDates($start_value, $end_value);

				    	if(!empty($date_list))
				    	{
				    		$data_list  = [];
				    		$date_count = count($date_list);

				    		for ($i=0; $i < $date_count; $i++) { 
				    				
				    			$date_value = date('Y-m-d', strtotime($date_list[$i]));

				    			// Distributor Payment
				    			$whr_1 = array(
				    				'date'           => $date_value,
				    				'distributor_id' => $distributor_id,
				    				'bill_code'      => 'REC',
				    				'amt_type'       => '2,3',
				    				'published'      => '1',
				    			);

				    			$col_1 = 'distributor_id, bill_no, amount, amt_type, date';
				    			$res_1 = $this->payment_model->getDistributorPaymentImplode($whr_1, '', '', 'result', '', '', '', '', $col_1);	

				    			if(!empty($res_1))
				    			{
				    				foreach ($res_1 as $key => $val_1) {
				    					$dis_id  = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
										$bill_no = !empty($val_1->bill_no)?$val_1->bill_no:'';
										$amount  = !empty($val_1->amount)?$val_1->amount:'0';
										$date    = !empty($val_1->date)?$val_1->date:'';

										$dis_whr = array('id' => $dis_id);
										$dis_col = 'company_name';
										$dis_res  = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);
							            $dis_name = !empty($dis_res[0]->company_name)?$dis_res[0]->company_name:'';

							            $dis_list = array(
											'voucher_no'   => $bill_no,
							            	'particular'   => $dis_name,
											'voucher_date' => date('d-M-Y', strtotime($date)),
											'amount_value' => number_format((float)$amount, 2, '.', ''),
											'voucher_type' => '2', // Credit
							            );

							            array_push($data_list, $dis_list);
				    				}
				    			}

				    			// Outlet Receipt
				    			$whr_2 = array(
				    				'date'           => $date_value,
				    				'distributor_id' => $distributor_id,
				    				'bill_code'      => 'REC',
				    				'amt_type'       => '2,3',
				    				'published'      => '1',
				    			);

				    			$col_2 = 'outlet_id, bill_no, amount, amt_type, date';
				    			$res_2 = $this->payment_model->getOutletPaymentImplode($whr_2, '', '', 'result', '', '', '', '', $col_2);	

				    			if(!empty($res_2))
				    			{
				    				foreach ($res_2 as $key => $val_2) {
				    					$str_id  = !empty($val_2->outlet_id)?$val_2->outlet_id:'';
										$bill_no = !empty($val_2->bill_no)?$val_2->bill_no:'';
										$amount  = !empty($val_2->amount)?$val_2->amount:'0';
										$date    = !empty($val_2->date)?$val_2->date:'';

										$str_whr = array('id' => $str_id);
										$str_col = 'company_name';
										$str_res  = $this->outlets_model->getOutlets($str_whr, '', '', 'result', '', '', '', '', $str_col);
							            $str_name = !empty($str_res[0]->company_name)?$str_res[0]->company_name:'';

							            $str_list = array(
											'voucher_no'   => $bill_no,
							            	'particular'   => $str_name,
											'voucher_date' => date('d-M-Y', strtotime($date)),
											'amount_value' => number_format((float)$amount, 2, '.', ''),
											'voucher_type' => '1', // Debit
							            );

							            array_push($data_list, $str_list);
				    				}
				    			}
				    		}

				    		$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $data_list;
					        echo json_encode($response);
					        return;
				    	}
				    	else
				    	{
				    		$response['status']  = 0;
					        $response['message'] = "Date not found"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
				    	}
				    }
				    else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Date incorrect"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
				}
			}

			else if($method == '_DistributorPaymentReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date', 'distributor_id');
			    foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error)
			    {
			        $response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(count($errors)==0)
			    {
			    	if($start_date <= $end_date)
			    	{
			    		$start_value = date('Y-m-d', strtotime($start_date));
				    	$end_value   = date('Y-m-d', strtotime($end_date));
				    	$date_list   = getBetweenDates($start_value, $end_value);

				    	if(!empty($date_list))
				    	{
				    		$data_list  = [];
				    		$date_count = count($date_list);

				    		for ($i=0; $i < $date_count; $i++) {

				    			$date_value = date('Y-m-d', strtotime($date_list[$i]));

				    			// Distributor Receipt
						    	$whr_1 = array(
				    				'date'           => $date_value,
				    				'distributor_id' => $distributor_id,
				    				'amt_type'       => '1,2,3',
				    				'published'      => '1',
				    			);

				    			$col_1 = 'distributor_id, bill_id, bill_no, amount, amt_type, date, bank_name, cheque_no, collect_date';
				    			$res_1 = $this->payment_model->getDistributorPaymentImplode($whr_1, '', '', 'result', '', '', '', '', $col_1);	

				    			if(!empty($res_1))
				    			{
				    				foreach ($res_1 as $key => $val_1) {
				    					$dis_id       = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
				    					$bill_id      = !empty($val_1->bill_id)?$val_1->bill_id:'';
										$bill_no      = !empty($val_1->bill_no)?$val_1->bill_no:'';
										$amount       = !empty($val_1->amount)?$val_1->amount:'0';
										$date         = !empty($val_1->date)?$val_1->date:'';
										$amt_type     = !empty($val_1->amt_type)?$val_1->amt_type:'';
										$bank_name    = !empty($val_1->bank_name)?$val_1->bank_name:'';
										$cheque_no    = !empty($val_1->cheque_no)?$val_1->cheque_no:'';
										$collect_date = !empty($val_1->collect_date)?$val_1->collect_date:'';

										$dis_whr = array('id' => $dis_id);
										$dis_col = 'company_name';
										$dis_res  = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);
							            $dis_name = !empty($dis_res[0]->company_name)?$dis_res[0]->company_name:'';

							            // Bill number
							            $bill_whr = array('order_id' => $bill_id);
										$bill_col = 'invoice_no';
										$bill_res = $this->invoice_model->getDistributorInvoice($bill_whr, '', '', 'result', '', '', '', '', $bill_col);
							            $bill_num = !empty($bill_res[0]->invoice_no)?$bill_res[0]->invoice_no:'';

							            $collect_value = '';
							            if($collect_date != '1970-01-01' && !empty($collect_date))
							            {
							            	$collect_value = date('d-M-Y', strtotime($collect_date));
							            }

							            $dis_list = array(
											'voucher_no'   => $bill_no,
							            	'particular'   => $dis_name,
											'voucher_date' => date('d-M-Y', strtotime($date)),
											'amount_value' => number_format((float)$amount, 2, '.', ''),
											'voucher_type' => '2', // Credit
											'bill_no'      => $bill_num,
											'payment_type' => $amt_type,
											'narration'    => 'Paid for bill on '.$bill_num,
											'bank_name'    => $bank_name,
											'cheque_no'    => $cheque_no,
											'collect_date' => $collect_value,
							            );

							            array_push($data_list, $dis_list);
				    				}
				    			}

				    			// Sales Return
					    		$whr_2 = array(
				    				'date'           => $date_value,
				    				'distributor_id' => $distributor_id,
				    				'bill_code'      => 'PAY',
				    				'published'      => '1',
				    			);

				    			$col_2 = 'bill_no, payment_id, description, outlet_id, amount, discount, penalty_amt, bank_charge, amt_type, date';
				    			$res_2 = $this->payment_model->getOutletPayment($whr_2, '', '', 'result', '', '', '', '', $col_2);

				    			if(!empty($res_2))
				    			{
				    				foreach ($res_2 as $key => $val_2) {
				    					$bill_no  = !empty($val_2->bill_no)?$val_2->bill_no:'';
				    					$pay_id   = !empty($val_2->payment_id)?$val_2->payment_id:'';
				    					$desc     = !empty($val_2->description)?$val_2->description:'';

				    					$store_id = !empty($val_2->outlet_id)?$val_2->outlet_id:'';

							            $amount   = !empty($val_2->amount)?$val_2->amount:'0';
							            $discount = !empty($val_2->discount)?$val_2->discount:'0';
							            $penalty_amt = !empty($val_2->penalty_amt)?$val_2->penalty_amt:'0';
							            $bank_charge = !empty($val_2->bank_charge)?$val_2->bank_charge:'0';
							            $amt_type = !empty($val_2->amt_type)?$val_2->amt_type:'';
							            $date_val = !empty($val_2->date)?$val_2->date:'';
							            $amt_val  = $amount + $discount;

							            $str_whr = array('id' => $store_id);
										$str_col = 'company_name';
										$str_res  = $this->outlets_model->getOutlets($str_whr, '', '', 'result', '', '', '', '', $str_col);
							            $str_name = !empty($str_res[0]->company_name)?$str_res[0]->company_name:'';

							            // Bill number
							            $bill_whr = array('id' => $pay_id);
										$bill_col = 'invoice_no';
										$bill_res = $this->invoice_model->getInvoice($bill_whr, '', '', 'result', '', '', '', '', $bill_col);
							            $bill_num = !empty($bill_res[0]->invoice_no)?$bill_res[0]->invoice_no:'';

							            $pen_list = array(
							            	'voucher_no'   => $bill_no,
							            	'particular'   => $str_name,
											'voucher_date' => date('d-M-Y', strtotime($date_val)),
											'amount_value' => number_format((float)$amt_val, 2, '.', ''),
											'voucher_type' => '2', // Credit
											'bill_no'      => $bill_num,
											'payment_type' => $amt_type,
											'narration'    => 'Sales Return',
											'bank_name'    => '',
											'cheque_no'    => '',
											'collect_date' => '',
							            );

							            array_push($data_list, $pen_list);
							        }
				    			}
				    		}

				    		$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $data_list;
					        echo json_encode($response);
					        return;
				    	}
				    	else
				    	{
				    		$response['status']  = 0;
					        $response['message'] = "Date incorrect"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
				    	}
				    }
				    else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Date incorrect"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
				}
			}

			else if($method == '_DistributorReceiptReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date', 'distributor_id');
			    foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error)
			    {
			        $response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(count($errors)==0)
			    {
			    	if($start_date <= $end_date)
			    	{
			    		$start_value = date('Y-m-d', strtotime($start_date));
				    	$end_value   = date('Y-m-d', strtotime($end_date));
				    	$date_list   = getBetweenDates($start_value, $end_value);

				    	if(!empty($date_list))
				    	{
				    		$data_list  = [];
				    		$date_count = count($date_list);

				    		for ($i=0; $i < $date_count; $i++) {

				    			$date_value = date('Y-m-d', strtotime($date_list[$i]));

				    			// Distributor Payment
				    			$whr_1 = array(
				    				'date'           => $date_value,
				    				'distributor_id' => $distributor_id,
				    				'bill_code'      => 'REC',
				    				'published'      => '1',
				    			);

				    			$col_1 = 'payment_id, bill_no, outlet_id, description, amount, discount, amt_type, date, bank_name, cheque_no, collect_date';
				    			$res_1 = $this->payment_model->getOutletPayment($whr_1, '', '', 'result', '', '', '', '', $col_1);

				    			if(!empty($res_1))
				    			{
				    				foreach ($res_1 as $key => $val_1) {
				    					$payment_id   = !empty($val_1->payment_id)?$val_1->payment_id:'';
				    					$bill_no      = !empty($val_1->bill_no)?$val_1->bill_no:'';
				    					$outlet_id    = !empty($val_1->outlet_id)?$val_1->outlet_id:'';
				    					$desc         = !empty($val_1->description)?$val_1->description:'Cash';
							            $amount       = !empty($val_1->amount)?$val_1->amount:'0';
							            $discount     = !empty($val_1->discount)?$val_1->discount:'0';
							            $date_val     = !empty($val_1->date)?$val_1->date:'';
										$amt_type     = !empty($val_1->amt_type)?$val_1->amt_type:'';
										$bank_name    = !empty($val_1->bank_name)?$val_1->bank_name:'';
										$cheque_no    = !empty($val_1->cheque_no)?$val_1->cheque_no:'';
										$collect_date = !empty($val_1->collect_date)?$val_1->collect_date:'';

							            $amt_val  = $amount + $discount;

							            $collect_value = '';
							            if($collect_date != '1970-01-01' && !empty($collect_date))
							            {
							            	$collect_value = date('d-M-Y', strtotime($collect_date));
							            }

							            // Outlet Details
							            $str_whr = array('id' => $outlet_id);
										$str_col = 'company_name';
										$str_res  = $this->outlets_model->getOutlets($str_whr, '', '', 'result', '', '', '', '', $str_col);
							            $str_name = !empty($str_res[0]->company_name)?$str_res[0]->company_name:'';

							            // Bill number
							            $bill_whr = array('id' => $payment_id);
										$bill_col = 'invoice_no';
										$bill_res = $this->invoice_model->getInvoice($bill_whr, '', '', 'result', '', '', '', '', $bill_col);
							            $bill_num = !empty($bill_res[0]->invoice_no)?$bill_res[0]->invoice_no:'';

							            $pay_list = array(
							            	'voucher_no'   => $bill_no,
							            	'particular'   => $str_name,
											'voucher_date' => date('d-M-Y', strtotime($date_val)),
											'amount_value' => number_format((float)$amount, 2, '.', ''),
											'voucher_type' => '2', // Credit
											'bill_no'      => $bill_num,
											'payment_type' => $amt_type,
											'narration'    => 'Received for bill on '.$bill_num,
											'bank_name'    => $bank_name,
											'cheque_no'    => $cheque_no,
											'collect_date' => $collect_value,
							            );

							            array_push($data_list, $pay_list);
							        }
				    			}

				    			// Distributor Sales return Receipt
						    	$whr_2 = array(
				    				'date'           => $date_value,
				    				'distributor_id' => $distributor_id,
				    				'amt_type'       => '4',
				    				'published'      => '1',
				    			);

				    			$col_2 = 'distributor_id, bill_id, bill_no, amount, amt_type, date';
				    			$res_2 = $this->payment_model->getDistributorPayment($whr_2, '', '', 'result', '', '', '', '', $col_2);	

				    			if(!empty($res_2))
				    			{
				    				foreach ($res_2 as $key => $val_2) {
				    					$dis_id   = !empty($val_2->distributor_id)?$val_2->distributor_id:'';
				    					$bill_id  = !empty($val_2->bill_id)?$val_2->bill_id:'';
										$bill_no  = !empty($val_2->bill_no)?$val_2->bill_no:'';
										$amount   = !empty($val_2->amount)?$val_2->amount:'0';
										$date     = !empty($val_2->date)?$val_2->date:'';
										$amt_type = !empty($val_2->amt_type)?$val_2->amt_type:'';

										$dis_whr = array('id' => $dis_id);
										$dis_col = 'company_name';
										$dis_res  = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);
							            $dis_name = !empty($dis_res[0]->company_name)?$dis_res[0]->company_name:'';

							            // Bill number
							            $bill_whr = array('order_id' => $bill_id);
										$bill_col = 'invoice_no';
										$bill_res = $this->invoice_model->getDistributorInvoice($bill_whr, '', '', 'result', '', '', '', '', $bill_col);
							            $bill_num = !empty($bill_res[0]->invoice_no)?$bill_res[0]->invoice_no:'';

							            $dis_list = array(
											'voucher_no'   => $bill_no,
							            	'particular'   => $dis_name,
											'voucher_date' => date('d-M-Y', strtotime($date)),
											'amount_value' => number_format((float)$amount, 2, '.', ''),
											'voucher_type' => '2', // Credit
											'bill_no'      => $bill_num,
											'payment_type' => $amt_type,
											'narration'    => 'Sales Return',
											'bank_name'    => '',
											'cheque_no'    => '',
											'collect_date' => '',
							            );

							            array_push($data_list, $dis_list);
				    				}
				    			}
				    		}

				    		$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $data_list;
					        echo json_encode($response);
					        return;
				    	}
				    	else
				    	{
				    		$response['status']  = 0;
					        $response['message'] = "Date incorrect"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
				    	}
				    }
				    else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Date incorrect"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
				}
			}

			else if($method == '_OutletLedgerReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date', 'distributor_id', 'outlet_id');
			    foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error)
			    {
			        $response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(count($errors)==0)
			    {
			    	if($start_date <= $end_date)
			    	{
			    		$start_value = date('Y-m-d', strtotime($start_date));
				    	$end_value   = date('Y-m-d', strtotime($end_date));
				    	$date_list   = getBetweenDates($start_value, $end_value);

				    	if(!empty($date_list))
				    	{
				    		$data_list  = [];
				    		$date_count = count($date_list);

				    		for ($i=0; $i < $date_count; $i++) {
				    			$date_value = date('Y-m-d', strtotime($date_list[$i]));

				    			// Sales Invoice
				    			$whr_1 = array(
				    				'date'           => $date_value,
				    				'distributor_id' => $distributor_id,
				    				'outlet_id'      => $outlet_id,
				    				'bill_code'      => 'INV',
				    				'published'      => '1',
				    			);

				    			$col_1 = 'bill_no, description, amount, discount, amt_type, date';
				    			$res_1 = $this->payment_model->getOutletPayment($whr_1, '', '', 'result', '', '', '', '', $col_1);

				    			if(!empty($res_1))
				    			{
				    				foreach ($res_1 as $key => $val_1) {
				    					$bill_no  = !empty($val_1->bill_no)?$val_1->bill_no:'';
				    					$desc     = !empty($val_1->description)?$val_1->description:'';
							            $amount   = !empty($val_1->amount)?$val_1->amount:'0';
							            $discount = !empty($val_1->discount)?$val_1->discount:'0';
							            $amt_type = !empty($val_1->amt_type)?$val_1->amt_type:'';
							            $date_val = !empty($val_1->date)?$val_1->date:'';
							            $amt_val  = $amount - $discount;

							            // Distributor Details
							            $dis_whr = array('id' => $distributor_id);
							            $dis_col = 'state_id';
							            $dis_res = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);
							            $dis_std = !empty($dis_res[0]->state_id)?$dis_res[0]->state_id:'';

							            // Outlet Details
							            $str_whr = array('id' => $outlet_id);
							            $str_col = 'state_id';
							            $str_res = $this->outlets_model->getOutlets($str_whr, '', '', 'result', '', '', '', '', $str_col);

							            $str_std = !empty($str_res[0]->state_id)?$str_res[0]->state_id:'';

							            $particular = 'Inter Sales';
							            if($str_std == $dis_std)
							            {
							            	$particular = 'Local Sales';
							            }

							            $inv_list = array(
							            	'voucher_no'    => $bill_no,
								            'particular'    => $particular,
								            'voucher_date'  => date('d-M-Y', strtotime($date_val)),
								            'amount_value'  => number_format((float)$amt_val, 2, '.', ''),
								            'voucher_type'  => $amt_type,
								            'data_type'     => '1', // Debit
							            );

							            array_push($data_list, $inv_list);
				    				}
				    			}

				    			// Distributor Payment
				    			$whr_2 = array(
				    				'date'           => $date_value,
				    				'distributor_id' => $distributor_id,
				    				'outlet_id'      => $outlet_id,
				    				'bill_code'      => 'REC',
				    				'published'      => '1',
				    			);

				    			$col_2 = 'bill_no, description, amount, discount, amt_type, date';
				    			$res_2 = $this->payment_model->getOutletPayment($whr_2, '', '', 'result', '', '', '', '', $col_2);

				    			if(!empty($res_2))
				    			{
				    				foreach ($res_2 as $key => $val_2) {
				    					$bill_no  = !empty($val_2->bill_no)?$val_2->bill_no:'';
				    					$desc     = !empty($val_2->description)?$val_2->description:'Cash';
							            $amount   = !empty($val_2->amount)?$val_2->amount:'0';
							            $discount = !empty($val_2->discount)?$val_2->discount:'0';
							            $amt_type = !empty($val_2->amt_type)?$val_2->amt_type:'';
							            $date_val = !empty($val_2->date)?$val_2->date:'';
							            $amt_val  = $amount + $discount;

							            $pay_list = array(
							            	'voucher_no'    => $bill_no,
								            'particular'    => $desc,
								            'voucher_date'  => date('d-M-Y', strtotime($date_val)),
								            'amount_value'  => number_format((float)$amt_val, 2, '.', ''),
								            'voucher_type'  => $amt_type,
								            'data_type'     => '2', // Credit
							            );

							            array_push($data_list, $pay_list);
							        }
				    			}

				    			// Cheque Failure
					    		$whr_3 = array(
				    				'date'           => $date_value,
				    				'distributor_id' => $distributor_id,
				    				'outlet_id'      => $outlet_id,
				    				'bill_code'      => 'PEN',
				    				'published'      => '1',
				    			);

				    			$col_3 = 'bill_no, description, amount, discount, penalty_amt, bank_charge, amt_type, date';
				    			$res_3 = $this->payment_model->getOutletPayment($whr_3, '', '', 'result', '', '', '', '', $col_3);

				    			if(!empty($res_3))
				    			{
				    				foreach ($res_3 as $key => $val_3) {
				    					$bill_no  = !empty($val_3->bill_no)?$val_3->bill_no:'';
				    					$desc     = !empty($val_3->description)?$val_3->description:'';
							            $amount   = !empty($val_3->amount)?$val_3->amount:'0';
							            $discount = !empty($val_3->discount)?$val_3->discount:'0';
							            $penalty_amt = !empty($val_3->penalty_amt)?$val_3->penalty_amt:'0';
							            $bank_charge = !empty($val_3->bank_charge)?$val_3->bank_charge:'0';
							            $amt_type = !empty($val_3->amt_type)?$val_3->amt_type:'';
							            $date_val = !empty($val_3->date)?$val_3->date:'';
							            $amt_val  = $penalty_amt + $bank_charge;

							            $pen_list = array(
							            	'voucher_no'    => $bill_no,
								            'particular'    => $desc,
								            'voucher_date'  => date('d-M-Y', strtotime($date_val)),
								            'amount_value'  => number_format((float)$amt_val, 2, '.', ''),
								            'voucher_type'  => $amt_type,
								            'data_type'     => '1', // Debit
							            );

							            array_push($data_list, $pen_list);
							        }
				    			}

				    			// Sales Return
					    		$whr_4 = array(
				    				'date'           => $date_value,
				    				'distributor_id' => $distributor_id,
				    				'outlet_id'      => $outlet_id,
				    				'bill_code'      => 'PAY',
				    				'published'      => '1',
				    			);

				    			$col_4 = 'bill_no, description, amount, discount, penalty_amt, bank_charge, amt_type, date';
				    			$res_4 = $this->payment_model->getOutletPayment($whr_4, '', '', 'result', '', '', '', '', $col_4);

				    			if(!empty($res_4))
				    			{
				    				foreach ($res_4 as $key => $val_4) {
				    					$bill_no  = !empty($val_4->bill_no)?$val_4->bill_no:'';
				    					$desc     = !empty($val_4->description)?$val_4->description:'';
							            $amount   = !empty($val_4->amount)?$val_4->amount:'0';
							            $discount = !empty($val_4->discount)?$val_4->discount:'0';
							            $penalty_amt = !empty($val_4->penalty_amt)?$val_4->penalty_amt:'0';
							            $bank_charge = !empty($val_4->bank_charge)?$val_4->bank_charge:'0';
							            $amt_type = !empty($val_4->amt_type)?$val_4->amt_type:'';
							            $date_val = !empty($val_4->date)?$val_4->date:'';
							            $amt_val  = $amount + $discount;

							            $pen_list = array(
							            	'voucher_no'    => $bill_no,
								            'particular'    => $desc,
								            'voucher_date'  => date('d-M-Y', strtotime($date_val)),
								            'amount_value'  => number_format((float)$amt_val, 2, '.', ''),
								            'voucher_type'  => $amt_type,
								            'data_type'     => '1', // Debit
							            );

							            array_push($data_list, $pen_list);
							        }
				    			}
				    		}

				    		$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $data_list;
					        echo json_encode($response);
					        return;
				    	}
				    	else
				    	{
				    		$response['status']  = 0;
					        $response['message'] = "Date not found"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
				    	}
				    }
				    else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Date incorrect"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
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
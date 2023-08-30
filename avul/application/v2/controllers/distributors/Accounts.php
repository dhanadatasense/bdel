<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Accounts extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function cashbook($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
		{
			$distributor_id   = $this->session->userdata('id');

			$method = $this->input->post('method');

			if($method =='_getOverallCashData')
			{
				$start_date = $this->input->post('start_date');
			    $end_date   = $this->input->post('end_date');

			    $data = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'distributor_id' => $distributor_id,
			    	'method'         => '_DistributorCashbookReport',
			    );

			    $data_list = avul_call(API_URL.'accounts/api/accounts_data', $data);

			    if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'];

		    		$num     = 1;
		    		$tot_deb = 0;
		    		$tot_cre = 0;
		    		foreach ($data_val as $key => $val) {

		    			$voucher_no   = !empty($val['voucher_no'])?$val['voucher_no']:'';
					    $particular   = !empty($val['particular'])?$val['particular']:'';
					    $voucher_date = !empty($val['voucher_date'])?$val['voucher_date']:'';
					    $amount_value = !empty($val['amount_value'])?$val['amount_value']:'';
					    $voucher_type = !empty($val['voucher_type'])?$val['voucher_type']:'';

					    if($voucher_type == 2)
					    {
					    	$debit_val  = 0.00;
					    	$credit_val = $amount_value;
					    }
					    else
					    {
					    	$debit_val  = $amount_value;
					    	$credit_val = 0.00;
					    }

					    $tot_deb += $debit_val;
					    $tot_cre += $credit_val;

					    $html .= '
			            	<tr>
                                <td>'.$num.'</td>
                                <td>'.$voucher_no.'</td>
                                <td>'.mb_strimwidth($particular, 0, 40, '...').'</td>
                                <td>'.date('d-M-Y', strtotime($voucher_date)).'</td>
                                <td>'.$debit_val.'</td>
                                <td>'.$credit_val.'</td>
                            </tr>
			            ';

					    $num++;
		    		}

		    		$html .= '
		            	<tr>
		            		<td></td>
		            		<td></td>
		            		<td></td>
                            <td><b>Total</b></td>
                            <td>'.number_format((float)$tot_deb, 2, '.', '').'</td>
                            <td>'.number_format((float)$tot_cre, 2, '.', '').'</td>
                        </tr>
		            ';

		    		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/accounts/cashbook/excel_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		    		$new_pay = '<a class="btn btn-warning m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/accounts/cashbook/new_payment/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> New Payment</a>';

		    		$new_rec = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/accounts/cashbook/new_receipt/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> New Receipt</a>';

		    		$response['status']    = 1;
			        $response['message']   = $data_list['message']; 
			        $response['data']      = $html;
			        $response['excel_btn'] = $excel_btn;
			        $response['new_pay']   = $new_pay;
			        $response['new_rec']   = $new_rec;
			        $response['error']     = []; 
			        echo json_encode($response);
			        return;
			    }
			    else
		    	{
		    		$response['status']  = 0;
			        $response['message'] = $data_list['message']; 
			        $response['data']    = [];
			        $response['error']   = []; 
			        echo json_encode($response);
			        return;
		    	}
			}

			if($param1 == 'excel_print')
        	{
        		$start_date  = $param2; 
        		$end_date    = $param3;

        		$data = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'distributor_id' => $distributor_id,
			    	'method'         => '_DistributorCashbookReport',
			    );

			    $data_list = avul_call(API_URL.'accounts/api/accounts_data', $data);

			     header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=cashbook_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Vch No','Particulars', 'Date', 'Debit', 'Credit'));

		    	if($data_list['status'] == 1)
		    	{
		    		$data_val = $data_list['data'];	
		    		foreach ($data_val as $key => $val) {

		    			$voucher_no   = !empty($val['voucher_no'])?$val['voucher_no']:'';
					    $particular   = !empty($val['particular'])?$val['particular']:'';
					    $voucher_date = !empty($val['voucher_date'])?$val['voucher_date']:'';
					    $amount_value = !empty($val['amount_value'])?$val['amount_value']:'';
					    $voucher_type = !empty($val['voucher_type'])?$val['voucher_type']:'';

					    if($voucher_type == 2)
					    {
					    	$debit_val  = '';
					    	$credit_val = $amount_value;
					    }
					    else
					    {
					    	$debit_val  = $amount_value;
					    	$credit_val = '';
					    }

					    $num = array(
                        	$voucher_no,
                        	$particular,
                        	$voucher_date,
                        	$debit_val,
                        	$credit_val,
                        );

                        fputcsv($output, $num); 
		    		}
		    	}

		    	fclose($output);
      			exit();
        	}

        	if($param1 == 'new_payment')
        	{
        		$start_date  = $param2; 
        		$end_date    = $param3;

        		$data = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'distributor_id' => $distributor_id,
			    	'method'         => '_DistributorPaymentReport',
			    );

			    $data_list = avul_call(API_URL.'accounts/api/accounts_data', $data);

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=payment_export('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Date', 'Payment No', 'Bank / Cash', 'Debit Account', 'Amount', 'Cheque No', 'Chk Date', 'Narration', 'New Reference'));

			    if($data_list['status'] == 1)
			    {
			    	$data_val = $data_list['data'];	

			    	foreach ($data_val as $key => $val) {
			    		$voucher_no = empty_check($val['voucher_no']);
	                    $particular = empty_check($val['particular']);
	                    $voucher_date = empty_check($val['voucher_date']);
	                    $amount_value = empty_check($val['amount_value']);
	                    $voucher_type = empty_check($val['voucher_type']);
	                    $bill_no = empty_check($val['bill_no']);
	                    $payment_type = empty_check($val['payment_type']);
	                    $narration = empty_check($val['narration']);
	                    $bank_name = empty_check($val['bank_name']);
	                    $cheque_no = empty_check($val['cheque_no']);
	                    $collect_date = empty_check($val['collect_date']);

	                    if($narration == 'Expense Entry' || $narration == 'Sales Return')
	                    {
	                    	$pay_type = '';
	                    }
	                    else if(!empty($cheque_no))
	                    {
	                    	$pay_type = 'Bank';
	                    }
	                    else
	                    {
	                    	$pay_type = 'Cash';
	                    }

	                    $num = array(
	                    	$voucher_date,
	                    	$voucher_no,
	                    	$pay_type,
	                    	$particular,
	                    	$amount_value,
	                    	$cheque_no,
	                    	$collect_date,
	                    	$narration,
	                    	'',
	                    );

	                    fputcsv($output, $num);  
			    	}
			    }

			    fclose($output);
      			exit();
        	}

        	if($param1 == 'new_receipt')
        	{
        		$start_date  = $param2; 
        		$end_date    = $param3;

        		$data = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'distributor_id' => $distributor_id,
			    	'method'         => '_DistributorReceiptReport',
			    );

			    $data_list = avul_call(API_URL.'accounts/api/accounts_data', $data);

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=receipt_export('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Date', 'Rec No', 'Bank / Cash', 'Credit Account', 'Amount', 'Cheque No', 'Chk Date', 'Narration', 'New Reference'));

			    if($data_list['status'] == 1)
			    {
			    	$data_val = $data_list['data'];	

			    	foreach ($data_val as $key => $val) {
			    		$voucher_no = empty_check($val['voucher_no']);
	                    $particular = empty_check($val['particular']);
	                    $voucher_date = empty_check($val['voucher_date']);
	                    $amount_value = empty_check($val['amount_value']);
	                    $voucher_type = empty_check($val['voucher_type']);
	                    $bill_no = empty_check($val['bill_no']);
	                    $payment_type = empty_check($val['payment_type']);
	                    $narration = empty_check($val['narration']);
	                    $bank_name = empty_check($val['bank_name']);
	                    $cheque_no = empty_check($val['cheque_no']);
	                    $collect_date = empty_check($val['collect_date']);

	                    if($narration == 'Expense Entry' || $narration == 'Sales Return')
	                    {
	                    	$pay_type = '';
	                    }
	                    else if(!empty($cheque_no))
	                    {
	                    	$pay_type = 'Bank';
	                    }
	                    else
	                    {
	                    	$pay_type = 'Cash';
	                    }

	                    $num = array(
	                    	$voucher_date,
	                    	$voucher_no,
	                    	$pay_type,
	                    	$particular,
	                    	$amount_value,
	                    	$cheque_no,
	                    	$collect_date,
	                    	$narration,
	                    	'',
	                    );

	                    fputcsv($output, $num); 
			    	}
			    }

			    fclose($output);
      			exit();
        	}

			else
			{
            	$page['method']       = '_getOverallCashData';
				$page['main_heading'] = "Accounts";
				$page['sub_heading']  = "Accounts";
				$page['pre_title']    = "Cashbook";
				$page['page_title']   = "Cashbook";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('distributors/accounts/cashbook_report',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "cashbook_report";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		public function bank_entry($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        {
        	if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$distributor_id   = $this->session->userdata('id');

        	$method = $this->input->post('method');

        	if($method =='_getOverallBankData')
        	{
        		$start_date  = $this->input->post('start_date');
			    $end_date    = $this->input->post('end_date');

			    $data = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'distributor_id' => $distributor_id,
			    	'method'         => '_DistributorBankEntryReport',
			    );

			    $data_list = avul_call(API_URL.'accounts/api/accounts_data', $data);

			    if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'];

		    		$num     = 1;
		    		$tot_deb = 0;
		    		$tot_cre = 0;
		    		foreach ($data_val as $key => $val) {

		    			$voucher_no   = !empty($val['voucher_no'])?$val['voucher_no']:'';
					    $particular   = !empty($val['particular'])?$val['particular']:'';
					    $voucher_date = !empty($val['voucher_date'])?$val['voucher_date']:'';
					    $amount_value = !empty($val['amount_value'])?$val['amount_value']:'';
					    $voucher_type = !empty($val['voucher_type'])?$val['voucher_type']:'';

					    if($voucher_type == 2)
					    {
					    	$debit_val  = 0.00;
					    	$credit_val = $amount_value;
					    }
					    else
					    {
					    	$debit_val  = $amount_value;
					    	$credit_val = 0.00;
					    }

					    $tot_deb += $debit_val;
					    $tot_cre += $credit_val;

					    $html .= '
			            	<tr>
                                <td>'.$num.'</td>
                                <td>'.$voucher_no.'</td>
                                <td>'.mb_strimwidth($particular, 0, 40, '...').'</td>
                                <td>'.date('d-M-Y', strtotime($voucher_date)).'</td>
                                <td>'.$debit_val.'</td>
                                <td>'.$credit_val.'</td>
                            </tr>
			            ';

					    $num++;
		    		}

		    		$html .= '
		            	<tr>
		            		<td></td>
		            		<td></td>
		            		<td></td>
                            <td><b>Total</b></td>
                            <td>'.number_format((float)$tot_deb, 2, '.', '').'</td>
                            <td>'.number_format((float)$tot_cre, 2, '.', '').'</td>
                        </tr>
		            ';

		    		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/accounts/bank_entry/excel_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		    		$response['status']    = 1;
			        $response['message']   = $data_list['message']; 
			        $response['data']      = $html;
			        $response['excel_btn'] = $excel_btn;
			        $response['error']     = []; 
			        echo json_encode($response);
			        return;
			    }
			    else
		    	{
		    		$response['status']  = 0;
			        $response['message'] = $data_list['message']; 
			        $response['data']    = [];
			        $response['error']   = []; 
			        echo json_encode($response);
			        return;
		    	}
        	}

        	if($param1 == 'excel_print')
        	{
        		$start_date  = $param2; 
        		$end_date    = $param3;

        		$data = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'distributor_id' => $distributor_id,
			    	'method'         => '_DistributorBankEntryReport',
			    );

			    $data_list = avul_call(API_URL.'accounts/api/accounts_data', $data);

			     header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=bankentry_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Vch No','Particulars', 'Date', 'Debit', 'Credit'));

		    	if($data_list['status'] == 1)
		    	{
		    		$data_val = $data_list['data'];	
		    		foreach ($data_val as $key => $val) {

		    			$voucher_no   = !empty($val['voucher_no'])?$val['voucher_no']:'';
					    $particular   = !empty($val['particular'])?$val['particular']:'';
					    $voucher_date = !empty($val['voucher_date'])?$val['voucher_date']:'';
					    $amount_value = !empty($val['amount_value'])?$val['amount_value']:'';
					    $voucher_type = !empty($val['voucher_type'])?$val['voucher_type']:'';

					    if($voucher_type == 2)
					    {
					    	$debit_val  = '';
					    	$credit_val = $amount_value;
					    }
					    else
					    {
					    	$debit_val  = $amount_value;
					    	$credit_val = '';
					    }

					    $num = array(
                        	$voucher_no,
                        	$particular,
                        	$voucher_date,
                        	$debit_val,
                        	$credit_val,
                        );

                        fputcsv($output, $num); 
		    		}
		    	}

		    	fclose($output);
      			exit();
        	}

        	else
			{

            	$page['method']       = '_getOverallBankData';
				$page['main_heading'] = "Accounts";
				$page['sub_heading']  = "Accounts";
				$page['pre_title']    = "Bank Entry";
				$page['page_title']   = "Bank Entry";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('distributors/accounts/bank_entry',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "bank_entry";
				$this->bassthaya->load_distributors_form_template($data);
			}
        }

        public function outlet_ledger($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        {
        	$distributor_id   = $this->session->userdata('id');

			$method = $this->input->post('method');

			if($method =='_getOverallOutletData')
			{
				$start_date  = $this->input->post('start_date');
			    $end_date    = $this->input->post('end_date');
			    $outlet_id   = $this->input->post('outlet_id');

			    $data = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'distributor_id' => $distributor_id,
			    	'outlet_id'      => $outlet_id,
			    	'method'         => '_OutletLedgerReport',
			    );

			    $data_list = avul_call(API_URL.'accounts/api/accounts_data', $data);

			    if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'];

		    		$num     = 1;
		    		$tot_deb = 0;
		    		$tot_cre = 0;
		    		foreach ($data_val as $key => $val) {

		    			$voucher_no   = !empty($val['voucher_no'])?$val['voucher_no']:'';
					    $particular   = !empty($val['particular'])?$val['particular']:'';
					    $voucher_date = !empty($val['voucher_date'])?$val['voucher_date']:'';
					    $amount_value = !empty($val['amount_value'])?$val['amount_value']:'';
					    $data_type    = !empty($val['data_type'])?$val['data_type']:'';
					    $voucher_type = !empty($val['voucher_type'])?$val['voucher_type']:'';

					    if($data_type == 2)
					    {
					    	$debit_val  = $amount_value;
					    	$credit_val = 0.00;
					    }
					    else
					    {
					    	$debit_val  = 0.00;
					    	$credit_val = $amount_value;
					    }

					    $tot_deb += $debit_val;
					    $tot_cre += $credit_val;

					    $html .= '
			            	<tr>
                                <td>'.$num.'</td>
                                <td>'.$voucher_no.'</td>
                                <td>'.mb_strimwidth($particular, 0, 40, '...').'</td>
                                <td>'.date('d-M-Y', strtotime($voucher_date)).'</td>
                                <td>'.$debit_val.'</td>
                                <td>'.$credit_val.'</td>
                            </tr>
			            ';

					    $num++;
		    		}

		    		$html .= '
		            	<tr>
		            		<td></td>
		            		<td></td>
		            		<td></td>
                            <td><b>Total</b></td>
                            <td>'.number_format((float)$tot_deb, 2, '.', '').'</td>
                            <td>'.number_format((float)$tot_cre, 2, '.', '').'</td>
                        </tr>
		            ';

		    		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/accounts/outlet_ledger/excel_print/'.$start_date.'/'.$end_date.'/'.$outlet_id.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		    		$response['status']    = 1;
			        $response['message']   = $data_list['message']; 
			        $response['data']      = $html;
			        $response['excel_btn'] = $excel_btn;
			        $response['error']     = []; 
			        echo json_encode($response);
			        return;
			    }
			    else
		    	{
		    		$response['status']  = 0;
			        $response['message'] = $data_list['message']; 
			        $response['data']    = [];
			        $response['error']   = []; 
			        echo json_encode($response);
			        return;
		    	}
			}

			if($param1 == 'excel_print')
        	{
			    $start_date  = $param2; 
        		$end_date    = $param3;
        		$outlet_id    = $param4;

			    $data = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'distributor_id' => $distributor_id,
			    	'outlet_id'      => $outlet_id,
			    	'method'         => '_OutletLedgerReport',
			    );

			    $data_list = avul_call(API_URL.'accounts/api/accounts_data', $data);

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=outlet_ledger_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Vch No','Particulars', 'Date', 'Debit', 'Credit'));

		    	if($data_list['status'] == 1)
		    	{
		    		$data_val = $data_list['data'];	
		    		foreach ($data_val as $key => $val) {

		    			$voucher_no   = !empty($val['voucher_no'])?$val['voucher_no']:'';
					    $particular   = !empty($val['particular'])?$val['particular']:'';
					    $voucher_date = !empty($val['voucher_date'])?$val['voucher_date']:'';
					    $amount_value = !empty($val['amount_value'])?$val['amount_value']:'';
					    $data_type    = !empty($val['data_type'])?$val['data_type']:'';
					    $voucher_type = !empty($val['voucher_type'])?$val['voucher_type']:'';

					    if($data_type == 2)
					    {
					    	$debit_val  = $amount_value;
					    	$credit_val = '';
					    }
					    else
					    {
					    	$debit_val  = '';
					    	$credit_val = $amount_value;
					    }

					    $num = array(
                        	$voucher_no,
                        	$particular,
                        	$voucher_date,
                        	$debit_val,
                        	$credit_val,
                        );

                        fputcsv($output, $num); 
		    		}
		    	}

		    	fclose($output);
      			exit();
        	}

			else
			{
				$where_1 = array(
					'distributor_id' => $distributor_id,
	        		'method'         => '_distributorOverallOutletList'
	        	);

	        	$outlet_list = avul_call(API_URL.'distributors/api/distributor_outlet_list',$where_1);

				$page['outlet_val']   = $outlet_list['data'];
            	$page['method']       = '_getOverallOutletData';
				$page['main_heading'] = "Accounts";
				$page['sub_heading']  = "Accounts";
				$page['pre_title']    = "Outlet Ledger";
				$page['page_title']   = "Outlet Ledger";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('distributors/accounts/outlet_ledger',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "outlet_ledger";
				$this->bassthaya->load_distributors_form_template($data);
			}
        }
	}
?>
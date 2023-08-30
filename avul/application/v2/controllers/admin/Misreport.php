<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Misreport extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function employee_wise_order_view($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method == '_getEmployeeData')
        	{
        		$month_id    = $this->input->post('month_id');
			    $year_id     = $this->input->post('year_id');
			    $employee_id = $this->input->post('employee_id');
			    $month_name  = $this->input->post('month_name');
			    $year_name   = $this->input->post('year_name');
			    $emp_name    = $this->input->post('emp_name');

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
			    	$data_whr  = array(
	        			'month_id'    => $month_id,
	        			'year_id'     => $year_id,
	        			'employee_id' => $employee_id,
	            		'method'      => '_getEmployeeOrderData'
	            	);

			    	$data_list = avul_call(API_URL.'misreport/api/order_report',$data_whr);

			    	if($data_list['status'] == 1)
			    	{
			    		$html     = '';
		    			$data_val = $data_list['data']['ord_data'];	

		    			$num = 1;
		    			foreach ($data_val as $key => $value) {

				            $order_no      = empty_check($value['order_no']);
				            $order_date    = date_check($value['order_date']);
				            $order_value   = zero_check($value['order_value']);
				            $admin_delete  = zero_check($value['admin_delete']);
				            $dis_delete    = zero_check($value['distributor_delete']);
				            $actual_order  = zero_check($value['actual_order']);
				            $invoice_order = zero_check($value['invoice_order']);
				            $cancel_value  = zero_check($value['cancel_value']);

				            $html .= '
				            	<tr>
	                                <td>'.$num.'</td>
	                                <td>'.$order_no.'</td>
	                                <td>'.$order_date.'</td>
	                                <td>'.$order_value.'</td>
	                                <td>'.$admin_delete.'</td>
	                                <td>'.$dis_delete.'</td>
	                                <td>'.$actual_order.'</td>
	                                <td>'.$invoice_order.'</td>
	                                <td>'.$cancel_value.'</td>
	                            </tr>
				            ';

				            $num++;
		    			}

		    			$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/misreport/order_wise_report/excel_print/'.$month_id.'/'.$year_id.'/'.$employee_id.'/'.$month_name.'/'.$year_name.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

			    		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/misreport/overall_order_report/pdf_print/'.$month_id.'/'.$year_id.'/'.$employee_id.'/'.$month_name.'/'.$year_name.'" style="color: #fff;"><i class="ft-file-text"></i> Overall</a>';

			    		$response['status']    = 1;
				        $response['message']   = $data_list['message']; 
				        $response['data']      = $html;
				        $response['excel_btn'] = $excel_btn;
				        $response['pdf_btn']   = $pdf_btn;
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
        	}

        	else
        	{
        		$where_1 = array(
		    		'log_type' => '2',
		    		'method'   => '_typeWiseEmployee'
		    	);

		    	$data_list  = avul_call(API_URL.'employee/api/employee',$where_1);
		    	$emp_list   = !empty($data_list['data'])?$data_list['data']:'';

		    	$where_2 = array(
	        		'method'   => '_listYear'
	        	);

	        	$year_data = avul_call(API_URL.'master/api/year',$where_2);
	        	$year_list = !empty($year_data['data'])?$year_data['data']:'';

	        	$where_3 = array(
            		'method'   => '_listMonth'
            	);

            	$month_data = avul_call(API_URL.'master/api/month',$where_3);
            	$month_list = !empty($month_data['data'])?$month_data['data']:'';

		    	$page['method']       = '_getEmployeeData';
		    	$page['emp_list']     = $emp_list;
		    	$page['month_list']   = $month_list;
            	$page['year_list']    = $year_list;
				$page['main_heading'] = "MIS Report";
				$page['sub_heading']  = "MIS Report";
				$page['pre_title']    = "Employee Wise Order Report";
				$page['page_title']   = "Employee Wise Order Report";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('admin/mis_report/employee_wise_order_view',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "employee_wise_order_view";
				$this->bassthaya->load_admin_form_template($data);
        	}
        }

        public function order_wise_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        {
        	$month_id    = $param2; 
    		$year_id     = $param3;
    		$employee_id = $param4;
    		$month_name  = $param5; 
    		$year_name   = $param6;
    		$emp_name    = $param7;

    		$data_whr  = array(
    			'month_id'    => $month_id,
    			'year_id'     => $year_id,
    			'employee_id' => $employee_id,
        		'method'      => '_getEmployeeOrderData'
        	);

	    	$data_list = avul_call(API_URL.'misreport/api/order_report',$data_whr);

	    	header("Content-Type: application/xls");    
			header("Content-Disposition: attachment; filename=".$month_name."_order_data.xls");  
			header("Pragma: no-cache"); 
			header("Expires: 0");

	    	$output  = "
				<h2 style='text-align: center;'>VENDS 360 BUSINESS SOLUTIONS</h2>
				<p style='text-align: center;'>ORDER REPORT</p>";

			if($data_list['status'] == 1)
			{
				$emp_val  = $data_list['data']['emp_data'];	
	    		$data_val = $data_list['data']['ord_data'];	

	    		$employee_name  = empty_check($emp_val['employee_name']);
	    		$duration_month = empty_check($emp_val['duration_month']);
	    		$duration_year  = empty_check($emp_val['duration_year']);

	    		$output .= "
					<table border='1'>
						<thead>
							<tr>
								<td>Employee Name</td>
								<td>".$employee_name."</td>
							</tr>
							<tr>
								<td>Month</td>
								<td>".$duration_month."</td>
							</tr>
							<tr>
								<td>Year</td>
								<td>".$duration_year."</td>
							</tr>
						</thead>
					</table>
				";

				$output .= "
					<table border='1'>
						<thead>
							<tr>
								<td>Order no</td>
								<td>Order date</td>
								<td>Store name</td>
								<td>Order value</td>
								<td>Admin delete</td>
								<td>Distributor delete</td>
								<td>Actual order</td>
								<td>Balance value</td>
								<td>Invoice order</td>
								<td>Cancel value</td>
							</tr>
						</thead>
						<tbody>";
						$order_total   = 0;
			            $admin_total   = 0;
			            $dis_total     = 0;
			            $actual_total  = 0;
			            $balance_total = 0;
			            $invoice_total = 0;
			            $cancel_total  = 0;
						foreach ($data_val as $key => $val) {

							$order_no      = empty_check($val['order_no']);
				            $order_date    = date_check($val['order_date']);
				            $store_name    = empty_check($val['store_name']);
				            $order_value   = zero_check($val['order_value']);
				            $admin_delete  = zero_check($val['admin_delete']);
				            $dis_delete    = zero_check($val['distributor_delete']);
				            $actual_order  = zero_check($val['actual_order']);
				            $balance_value = zero_check($val['balance_value']);
				            $invoice_order = zero_check($val['invoice_order']);
				            $cancel_value  = zero_check($val['cancel_value']);

				            $order_total   += $order_value;
				            $admin_total   += $admin_delete;
				            $dis_total     += $dis_delete;
				            $actual_total  += $actual_order;
				            $balance_total += $balance_value;
				            $invoice_total += $invoice_order;
				            $cancel_total  += $cancel_value;

							$output .="
							<tr>
								<td>".$order_no."</td>
								<td>".$order_date."</td>
								<td>".$store_name."</td>
								<td>".$order_value."</td>
								<td>".$admin_delete."</td>
								<td>".$dis_delete."</td>
								<td>".$actual_order."</td>
								<td>".$balance_value."</td>
								<td>".$invoice_order."</td>
								<td>".$cancel_value."</td>
							</tr>";
						}
						$output .="
						</tbody>
						<thead>
							<tr>
								<td colspan='3'>Total</td>
								<td>".$order_total."</td>
								<td>".$admin_total."</td>
								<td>".$dis_total."</td>
								<td>".$actual_total."</td>
								<td>".$balance_total."</td>
								<td>".$invoice_total."</td>
								<td>".$cancel_total."</td>
							</tr>
						</thead>
					</table>
				";
			}


			echo $output;
        }

        public function overall_order_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        {
        	$month_id    = $param2; 
    		$year_id     = $param3;
    		$employee_id = $param4;
    		$month_name  = $param5; 
    		$year_name   = $param6;
    		$emp_name    = $param7;

    		$data_whr  = array(
    			'month_id'    => $month_id,
    			'year_id'     => $year_id,
    			'employee_id' => $employee_id,
        		'method'      => '_getEmployeeMonthOrderData'
        	);

	    	$data_list = avul_call(API_URL.'misreport/api/order_report',$data_whr);

	    	header("Content-Type: application/xls");    
			header("Content-Disposition: attachment; filename=overall_order_data.xls");  
			header("Pragma: no-cache"); 
			header("Expires: 0");

	    	$output  = "
				<h2 style='text-align: center;'>VENDS 360 BUSINESS SOLUTIONS</h2>
				<p style='text-align: center;'>OBERALL ORDER REPORT</p>";

			if($data_list['status'] == 1)
			{
				$emp_val  = $data_list['data']['emp_data'];	
		    	$data_val = $data_list['data']['ord_data'];	

		    	$employee_name  = empty_check($emp_val['employee_name']);
	    		$duration_month = empty_check($emp_val['duration_month']);
	    		$duration_year  = empty_check($emp_val['duration_year']);

	    		$output .= "
					<table border='1'>
						<thead>
							<tr>
								<td>Employee Name</td>
								<td>".$employee_name."</td>
							</tr>
							<tr>
								<td>Month</td>
								<td>".$duration_month."</td>
							</tr>
							<tr>
								<td>Year</td>
								<td>".$duration_year."</td>
							</tr>
						</thead>
					</table>
				";

				$output .= "
					<table border='1'>
						<thead>
							<tr>
								<td>Date</td>
								<td>Day</td>
								<td>Temp Order</td>
								<td>Admin Delete</td>
								<td>Distributor Delete</td>
								<td>Actual Order</td>
								<td>Pending Order</td>
								<td>Invoice Value</td>
								<td>Cancel Value</td>
							</tr>
						</thead>
						<tbody>";

							$temp_total    = 0;
							$admin_total   = 0;
							$dis_total     = 0;
							$actual_total  = 0;
							$balance_total = 0;
							$invoice_total = 0;
							$cancel_total  = 0;

							foreach ($data_val as $key => $val) {
								$date          = empty_check($val['date']);
								$day           = empty_check($val['day']);
								$temp_value    = zero_check($val['temp_value']);
								$admin_delete  = zero_check($val['admin_delete']);
								$dis_delete    = zero_check($val['distributor_delete']);
								$actual_order  = zero_check($val['actual_order']);
								$balance_val   = zero_check($val['balance_val']);
								$invoice_order = zero_check($val['invoice_order']);
								$cancel_value  = zero_check($val['cancel_value']);

								$temp_total    += $temp_value;    
								$admin_total   += $admin_delete;  
								$dis_total     += $dis_delete;    
								$actual_total  += $actual_order;  
								$balance_total += $balance_val;   
								$invoice_total += $invoice_order; 
								$cancel_total  += $cancel_value;  

								$output .= "
									<tr>
										<td>".$date."</td>
										<td>".$day."</td>
										<td>".$temp_value."</td>
										<td>".$admin_delete."</td>
										<td>".$dis_delete."</td>
										<td>".$actual_order."</td>
										<td>".$balance_val."</td>
										<td>".$invoice_order."</td>
										<td>".$cancel_value."</td>
									</tr>
								";
							}
						$output .= "
						</tbody>
						<thead>
							<tr>
								<td colspan='2'>Total</td>
								<td>".$temp_total."</td>
								<td>".$admin_total."</td>
								<td>".$dis_delete."</td>
								<td>".$actual_order."</td>
								<td>".$balance_val."</td>
								<td>".$invoice_total."</td>
								<td>".$cancel_total."</td>
							</tr>
						</thead>
					</table>";
			}

			echo $output;
        }

        public function collection_report_view($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        {
        	if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method == '_getCollectionData')
        	{
        		$month_id    = $this->input->post('month_id');
			    $year_id     = $this->input->post('year_id');

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
			    	$data_whr  = array(
	        			'month_id' => $month_id,
	        			'year_id'  => $year_id,
	            		'method'   => '_getEmployeeCollectionData'
	            	);

	            	$data_list = avul_call(API_URL.'misreport/api/collection_report',$data_whr);

			    	if($data_list['status'] == 1)
			    	{
			    		$html     = '';
			    		$data_val = $data_list['data'];	

			    		$num        = 1;
			    		$cash_tot   = 0;
			            $cheque_tot = 0;
			            $other_tot  = 0;

		    			foreach ($data_val as $key => $val) {
		    				$date       = empty_check($val['date']);
				            $day        = empty_check($val['day']);
				            $cash_val   = zero_check($val['cash_value']);
				            $cheque_val = zero_check($val['cheque_value']);
				            $others_val = zero_check($val['others_value']);

				            $cash_tot   += $cash_val;
				            $cheque_tot += $cheque_val;
				            $other_tot  += $others_val;

				            $html .= '
				            	<tr>
	                                <td>'.$num.'</td>
	                                <td>'.$date.'</td>
	                                <td>'.$day.'</td>
	                                <td>'.$cash_val.'</td>
	                                <td>'.$cheque_val.'</td>
	                                <td>'.$others_val.'</td>
	                                <td>0</td>
	                                <td>0</td>
	                                <td>0</td>
	                                <td>0</td>
	                                <td>0</td>
	                                <td>0</td>
	                            </tr>
				            ';

				            $num++;
		    			}

		    			$html .= '
		    				<tr>
                                <td colspan="3">Total</td>
                                <td>'.zero_check($cash_tot).'</td>
                                <td>'.zero_check($cheque_tot).'</td>
                                <td>'.zero_check($other_tot).'</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
		    			';

		    			$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/misreport/collection_export/excel_print/'.$month_id.'/'.$year_id.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

			    		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/misreport/overall_collection_export/pdf_print/'.$month_id.'/'.$year_id.'" style="color: #fff;"><i class="ft-file-text"></i> Overall</a>';

			    		$response['status']    = 1;
				        $response['message']   = $data_list['message']; 
				        $response['data']      = $html;
				        $response['excel_btn'] = $excel_btn;
				        $response['pdf_btn']   = $pdf_btn;
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
        	}


        	else
        	{
		    	$where_1 = array(
	        		'method'   => '_listYear'
	        	);

	        	$year_data = avul_call(API_URL.'master/api/year',$where_1);
	        	$year_list = !empty($year_data['data'])?$year_data['data']:'';

	        	$where_2 = array(
            		'method'   => '_listMonth'
            	);

            	$month_data = avul_call(API_URL.'master/api/month',$where_2);
            	$month_list = !empty($month_data['data'])?$month_data['data']:'';

		    	$page['method']       = '_getCollectionData';
		    	$page['month_list']   = $month_list;
            	$page['year_list']    = $year_list;
				$page['main_heading'] = "MIS Report";
				$page['sub_heading']  = "MIS Report";
				$page['pre_title']    = "Collection Report";
				$page['page_title']   = "Collection Report";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('admin/mis_report/collection_report_view',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "collection_report_view";
				$this->bassthaya->load_admin_form_template($data);
        	}
        }

        public function collection_export($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        {
        	if($param1 == 'excel_print')
        	{
        		$month_id    = $param2; 
        		$year_id     = $param3;


        		$data_whr  = array(
        			'month_id'    => $month_id,
        			'year_id'     => $year_id,
            		'method'      => '_getEmployeeCollectionData'
            	);

		    	$data_list = avul_call(API_URL.'misreport/api/collection_report',$data_whr);

		    	$dateObj   = DateTime::createFromFormat('!m', $month_id);
				$monthName = $dateObj->format('F'); // March

		    	header("Content-Type: application/xls");    
				header("Content-Disposition: attachment; filename=".$monthName."_month_collection_data.xls");  
				header("Pragma: no-cache"); 
				header("Expires: 0");
				$output = "";

		    	if($data_list['status'] == 1)
		    	{
		    		$data_val = $data_list['data'];	

		    		$output .= '
		    			<table border="1">
                            <thead>
                                <tr>
                                    <th rowspan="2">#</th>
                                    <th rowspan="2">Date</th>
                                    <th rowspan="2">Day</th>
                                    <th colspan="3" style="text-align: center;">Collecton amount Rs.</th>
                                    <th colspan="3" style="text-align: center;">Deposited amount Rs.</th>
                                    <th colspan="3" style="text-align: center;">Balance</th>
                                </tr>
                                <tr>
                                    <td>Cash</td>
                                    <td>Cheque</td>
                                    <td>Others</td>
                                    <td>Cash</td>
                                    <td>Cheque</td>
                                    <td>Others</td>
                                    <td>Cash</td>
                                    <td>Cheque</td>
                                    <td>Others</td>
                                </tr>
                            </thead>
                            <tbody>';

                            $num        = 1;
                            $cash_tot   = 0;
				            $cheque_tot = 0;
				            $other_tot  = 0;
			    			foreach ($data_val as $key => $val) {
			    				$date       = empty_check($val['date']);
					            $day        = empty_check($val['day']);
					            $cash_val   = zero_check($val['cash_value']);
					            $cheque_val = zero_check($val['cheque_value']);
					            $others_val = zero_check($val['others_value']);

					            $cash_tot   += $cash_val;
					            $cheque_tot += $cheque_val;
					            $other_tot  += $others_val;

					            $output .= '
					            	<tr>
		                                <td>'.$num.'</td>
		                                <td>'.$date.'</td>
		                                <td>'.$day.'</td>
		                                <td>'.$cash_val.'</td>
		                                <td>'.$cheque_val.'</td>
		                                <td>'.$others_val.'</td>
		                                <td>0</td>
		                                <td>0</td>
		                                <td>0</td>
		                                <td>0</td>
		                                <td>0</td>
		                                <td>0</td>
		                            </tr>
					            ';

					            $num++;
			    			}

			    			$output .= '
			    				<tr>
	                                <td colspan="3">Total</td>
	                                <td>'.zero_check($cash_tot).'</td>
	                                <td>'.zero_check($cheque_tot).'</td>
	                                <td>'.zero_check($other_tot).'</td>
	                                <td>0</td>
	                                <td>0</td>
	                                <td>0</td>
	                                <td>0</td>
	                                <td>0</td>
	                                <td>0</td>
	                            </tr>
			    			';

                            $output .='
                            </tbody>
                        </table>
		    		';
		    	}

		    	echo $output;
        	}
        }

        public function overall_collection_export($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        {
        	if($param1 == 'pdf_print')
        	{
        		$month_id    = $param2; 
        		$year_id     = $param3;


        		$data_whr  = array(
        			'month_id'    => $month_id,
        			'year_id'     => $year_id,
            		'method'      => '_getEmployeeMonthCollectionData'
            	);

            	$data_list = avul_call(API_URL.'misreport/api/collection_report',$data_whr);

            	$dateObj   = DateTime::createFromFormat('!m', $month_id);
				$monthName = $dateObj->format('F'); // March

		    	header("Content-Type: application/xls");    
				header("Content-Disposition: attachment; filename=".$monthName."_month_overall_collection_data.xls");  
				header("Pragma: no-cache"); 
				header("Expires: 0");

				$output = "
				<h2 style='text-align: center;'>VENDS 360 BUSINESS SOLUTIONS</h2>
				<p style='text-align: center;'>DAILY COLLECTION REPORT</p>";

				if($data_list['status'] == 1)
				{
					$data_val = $data_list['data'];	

					foreach ($data_val as $key => $val_1) {
						$date_val   = empty_check($val_1['date_val']);
			            $coll_data  = empty_check($val_1['coll_data']);

			            $output .= "<p style='text-align: center;'>".$date_val."</p>";

			            $output .= "
			            	<table border='1'>
								<thead>
									<tr>
										<td rowspan='2'>#</td>
										<td rowspan='2'>Location</td>
										<td rowspan='2'>BD Name</td>
										<td rowspan='2'>Bill No</td>
										<td rowspan='2'>Name of Customer</td>
										<td colspan='3'>Amount</td>
										<td colspan='3'>Deposited</td>
										<td colspan='3'>Balance</td>
										<td rowspan='2'>Date</td>
										<td rowspan='2'>Remark</td>
									</tr>
									<tr>
										<td>Cash</td>
										<td>Cheque</td>
										<td>Others</td>
										<td>Cash</td>
										<td>Cheque</td>
										<td>Others</td>
										<td>Cash</td>
										<td>Cheque</td>
										<td>Others</td>
									</tr>
								</thead>
								<tbody>";
									if($coll_data)
									{
										$num        = 1;
										$tot_cash   = 0;
									    $tot_cheque = 0;
									    $tot_others = 0;
										foreach ($coll_data as $key => $val_2) {

											$amt_type = zero_check($val_2['amt_type']);

											if($amt_type == 1)
										    {
										    	$tot_cash += zero_check($val_2['cash']);
										    }
										    else if($amt_type == 2)
										    {
										    	$tot_cheque += zero_check($val_2['cheque']);
										    }
										    else if($amt_type == 3)
										    {
										    	$tot_others += zero_check($val_2['others']);
										    }

											$output .= "
												<tr>
													<td>".$num."</td>
													<td>".empty_check($val_2['zone_name'])."</td>
													<td>".empty_check($val_2['emp_name'])."</td>
													<td>".empty_check($val_2['invoice_no'])."</td>
													<td>".empty_check($val_2['outlet_name'])."</td>
													<td>".zero_check($val_2['cash'])."</td>
													<td>".zero_check($val_2['cheque'])."</td>
													<td>".zero_check($val_2['others'])."</td>
													<td>0</td>
													<td>0</td>
													<td>0</td>
													<td>0</td>
													<td>0</td>
													<td>0</td>
													<td>".date_check($val_2['createdate'])."</td>
													<td></td>
												</tr>
											";

											$num++;
										}

										$output .= "
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td>".zero_check($tot_cash)."</td>
												<td>".zero_check($tot_cheque)."</td>
												<td>".zero_check($tot_others)."</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td></td>
												<td></td>
											</tr>
										";
									}
									else
									{	
										$output .= "
											<tr>
												<td colspan='16' style='text-align: center;'>No data found</td>
											</tr>
										";
									}
								$output .="
								</tbody>
							</table>
			            ";
					}
				}

				echo $output;
        	}
        }
	}
?>
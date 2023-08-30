<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Report extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function sales_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method == '_getSalesData')
        	{
        		$start_date  = $this->input->post('start_date');
			    $end_date    = $this->input->post('end_date');

			    $sales_whr = array(
			    	'start_date' => date('Y-m-d', strtotime($start_date)),
			    	'end_date'   => date('Y-m-d', strtotime($end_date)),
			    	'vendor_id'  => $this->session->userdata('id'),
			    	'method'     => '_overallInviceReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/vendor_report',$sales_whr);
		    	
		    	if($data_list['status'] == 1)
		    	{
		    		$html     = '';
		    		$tot_data = $data_list['data']['inv_total'];
		    		$data_val = $data_list['data']['inv_list'];

		    		$total_count = !empty($tot_data['total_count'])?$tot_data['total_count']:'0';
		    		$taxable_val = !empty($tot_data['total_taxable'])?$tot_data['total_taxable']:'0';
		    		$total_tax   = !empty($tot_data['total_tax'])?$tot_data['total_tax']:'0';
		    		$total_value = !empty($tot_data['total_value'])?$tot_data['total_value']:'0';

		    		$count_val = '
		    			<div class="card-body pt-0" style="margin-top: 25px;">
                            <div class="row">
                                <div class="col-sm-12 filter-design" style="display: inherit;">
                                    <div class="col-md-3 col-12 border-right-blue-grey border-right-lighten-5 text-center">
                                        <h4 class="info text-bold-600"><span class="icon-user"></span></h4>
                                        <h4 class="font-large-1 text-bold-400">'.$total_count.'</h4>
                                        <p class="blue-grey lighten-2 mb-0">Total Invoice</p>
                                    </div>
                                    <div class="col-md-3 col-12 border-right-blue-grey border-right-lighten-5 text-center">
                                        <h4 class="warning text-bold-600"><span class="icon-user-follow"></span></h4>
                                        <h4 class="font-large-1 text-bold-400">'.$taxable_val.'</h4>
                                        <p class="blue-grey lighten-2 mb-0">Total Taxable Value</p>
                                    </div>
                                    <div class="col-md-3 col-12 border-right-blue-grey border-right-lighten-5 text-center">
                                        <h4 class="danger text-bold-600"><span class="icon-user-follow"></span></h4>
                                        <h4 class="font-large-1 text-bold-400">'.$total_tax.'</h4>
                                        <p class="blue-grey lighten-2 mb-0">Total Tax Value</p>
                                    </div>
                                    <div class="col-md-3 col-12 text-center">
                                        <h4 class="success text-bold-600"><span class="icon-user-following"></span></h4>
                                        <h4 class="font-large-1 text-bold-400">'.$total_value.'</h4>
                                        <p class="blue-grey lighten-2 mb-0">Total Value</p>
                                    </div>
                                </div>
                            </div>
                        </div>
		    		';

		    		$num = 1;
		    		foreach ($data_val as $key => $val) {

						$order_no    = !empty($val['order_no'])?$val['order_no']:'';
			            $inv_no      = !empty($val['inv_no'])?$val['inv_no']:'';
			            $inv_date    = !empty($val['inv_date'])?$val['inv_date']:'';
			            $round_value = !empty($val['round_value'])?$val['round_value']:'0';
			            $inv_value   = !empty($val['inv_value'])?$val['inv_value']:'0';
			            $pur_random  = !empty($val['pur_random'])?$val['pur_random']:'';
			            $inv_random  = !empty($val['inv_random'])?$val['inv_random']:'';

						$html .= '
			            	<tr>
                                <td>'.$num.'</td>
                                <td><a target="_blank" href="'.BASE_URL.'index.php/vendors/purchase/print_invoice/'.$inv_random.'">'.$inv_no.'</a></td>
                                <td><a target="_blank" href="'.BASE_URL.'index.php/vendors/purchase/print_order/'.$pur_random.'">'.$order_no.'</a></td>
                                <td>'.date('d-M-Y', strtotime($inv_date)).'</td>
                                <td>'.number_format((float)$inv_value, 2, '.', '').'</td>
                            </tr>
			            ';

			            $num++;
		    		}		

		    		$tally_btn = '<a class="btn btn-warning m-t-27" target="_blank" href="'.BASE_URL.'index.php/vendors/report/sales_report/tally_export/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-shopping-cart"></i> Tally</a>';

		    		$invoice_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/vendors/report/sales_report/invoice_export/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-shopping-cart"></i> Invoice</a>';

		    		$gst_btn = '<a class="btn btn-info m-t-27" target="_blank" href="'.BASE_URL.'index.php/vendors/report/sales_report/gst_export/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-shopping-cart"></i> GST</a>';

		    		$new_btn = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/vendors/report/sales_report/new_export/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-shopping-cart"></i> New</a>';

		    		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/vendors/report/sales_report/pdf_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		    		$response['status']      = 1;
			        $response['message']     = $data_list['message']; 
			        $response['count_val']   = $count_val;
			        $response['data']        = $html;
			        $response['tally_btn']   = $tally_btn;
			        $response['invoice_btn'] = $invoice_btn;
			        $response['gst_btn']     = $gst_btn;
			        $response['new_btn']     = $new_btn;
			        $response['pdf_btn']     = $pdf_btn;
			        $response['error']       = []; 
			        echo json_encode($response);
			        return;
		    	}
		    	else
		    	{
		    		$response['status']  = 0;
			        $response['message'] = $data_list['message']; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
		    	}
        	}

        	if($param1 == 'tally_export')
        	{
        		$start_date  = $param2; 
        		$end_date    = $param3;

        		$sales_whr = array(
			    	'start_date' => date('Y-m-d', strtotime($start_date)),
					'end_date'   => date('Y-m-d', strtotime($end_date)),
			    	'vendor_id'  => $this->session->userdata('id'),
			    	'method'     => '_overallSalesReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/vendor_report',$sales_whr);
		    		
		    	header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=tally_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('InvNo', 'Inv_Dt', 'Pty_Name', 'Vch_Type', 'GSTIN', 'StateOfSupply', 'Product_Name', 'HSNCode', 'Qty', 'UOM', 'TaxPer', 'TaxableAmt', 'IGSTAmt', 'SGSTAmt', 'CGSTAmt', 'Cess', 'OtherAmt', 'Discount', 'Net_Amt', 'Narration', 'Group', 'Address', 'Country', 'St. Group', 'Salees Ledger'));

		    	if($data_list['status'] == 1)
		    	{
		    		$data_val = $data_list['data'];	

		    		$totQtyVal  = 0;
		    		$totTaxable = 0;
		    		$totIgstVal = 0;
		    		$totSgstVal = 0;
		    		$totCgstVal = 0;
		    		$totDisVal  = 0;
		    		$totNetVal  = 0;

		    		foreach ($data_val as $key => $val) {

		    			$ven_state_id = !empty($val['ven_state_id'])?$val['ven_state_id']:'';
			            $invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
			            $admin_name   = !empty($val['admin_name'])?$val['admin_name']:'';
			            $mobile       = !empty($val['mobile'])?$val['mobile']:'';
			            $gst_no       = !empty($val['gst_no'])?$val['gst_no']:'';
			            $address      = !empty($val['address'])?$val['address']:'';
			            $state_id     = !empty($val['state_id'])?$val['state_id']:'';
			            $state_name   = !empty($val['state_name'])?$val['state_name']:'';
			            $gst_code     = !empty($val['gst_code'])?$val['gst_code']:'';
			            $invoice_date = !empty($val['invoice_date'])?$val['invoice_date']:'';
			            $description  = !empty($val['description'])?$val['description']:'';
			            $hsn_code     = !empty($val['hsn_code'])?$val['hsn_code']:'';
			            $gst_value    = !empty($val['gst_val'])?$val['gst_val']:'0';
			            $pdt_price    = !empty($val['price'])?$val['price']:'0';
			            $pdt_qty      = !empty($val['order_qty'])?$val['order_qty']:'0';
			            $discount     = 0;

			            $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
                        $price_val  = $pdt_price - $gst_data;
                        $pdt_gst    = $pdt_qty * $gst_data;
                        $TaxableAmt = $pdt_qty * $price_val;
                        $tot_price  = $pdt_qty * $pdt_price;
                        $pdt_value  = round($tot_price);
                        $total_dis  = $pdt_value * $discount / 100;
                        $total_val  = $pdt_value - $total_dis;

                        if($ven_state_id == $state_id)
                        {
                        	$gst_res  = $pdt_gst / 2;
                        	$sgst_val = number_format((float)$gst_res, 2, '.', '');
                        	$cgst_val = number_format((float)$gst_res, 2, '.', '');
                        	$igst_val = '0';
                        	$vch_type = 'Local Sales';

                        	$totIgstVal += 0;
				    		$totSgstVal += $sgst_val;
				    		$totCgstVal += $cgst_val;
                        }
                        else
                        {
                        	$sgst_val = '0';
                        	$cgst_val = '0';
                        	$igst_val = number_format((float)$pdt_gst, 2, '.', '');
                        	$vch_type = 'Inter Sales';

                        	$totIgstVal += $igst_val;
				    		$totSgstVal += 0;
				    		$totCgstVal += 0;
                        }

                        $totQtyVal  += $pdt_qty;
                        $totTaxable += $TaxableAmt;
                        $totDisVal  += $total_dis;
                        $totNetVal  += $total_val;

			            $num = array(
			            	$invoice_no,
			            	date('d-m-Y', strtotime($invoice_date)),
			            	$admin_name,
			            	$vch_type,
			            	$gst_no,
			            	$state_name,
			            	$description,
			            	$hsn_code,
			            	$pdt_qty,
			            	'NOS',
			            	$gst_value,
			            	number_format((float)$TaxableAmt, 2, '.', ''),
			            	$igst_val,
			            	$sgst_val,
			            	$cgst_val,
			            	'0',
			            	'0',
			            	number_format((float)$total_dis, 2, '.', ''),
			            	number_format((float)$total_val, 2, '.', ''),
			            	'',
			            	'Sundry Debtors',
			            	$address,
			            	'India',
			            	'',
			            	'Sales'
			            );

			            fputcsv($output, $num);  
		    		}

		    		$num = array(
		            	'',
		            	'',
		            	'',
		            	'',
		            	'',
		            	'',
		            	'',
		            	'',
		            	$totQtyVal,
		            	'',
		            	'',
		            	number_format((float)$totTaxable, 2, '.', ''),
		            	number_format((float)$totIgstVal, 2, '.', ''),
		            	number_format((float)$totSgstVal, 2, '.', ''),
		            	number_format((float)$totCgstVal, 2, '.', ''),
		            	'0',
		            	'0',
		            	number_format((float)$totDisVal, 2, '.', ''),
		            	number_format((float)$totNetVal, 2, '.', ''),
		            	'',
		            	'',
		            	'',
		            	'',
		            	'',
		            	''
		            );

		            fputcsv($output, $num);  
		    	}

		    	fclose($output);
      			exit();
        	}

        	if($param1 == 'invoice_export')
        	{
        		$start_date  = $param2; 
        		$end_date    = $param3;

        		$sales_whr = array(
			    	'start_date' => date('Y-m-d', strtotime($start_date)),
			    	'end_date'   => date('Y-m-d', strtotime($end_date)),
			    	'vendor_id'  => $this->session->userdata('id'),
			    	'method'     => '_overallInviceReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/vendor_report',$sales_whr);

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=vendor_invoice_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Invoice No', 'Invoice Date', 'Order No', 'Company Name', 'Due Days', 'Discount', 'Round Val', 'Total Val'));

		    	if($data_list['status'] == 1)
		    	{
		    		$data_val  = $data_list['data']['inv_list'];	
		    		$totNetVal = 0;

		    		foreach ($data_val as $key => $val) {
		    			$order_no     = !empty($val['order_no'])?$val['order_no']:'';
			            $inv_no       = !empty($val['inv_no'])?$val['inv_no']:'';
			            $inv_date     = !empty($val['inv_date'])?$val['inv_date']:'';
			            $company_name = !empty($val['company_name'])?$val['company_name']:'';
			            $due_days     = !empty($val['due_days'])?$val['due_days']:'0';
			            $round_value  = !empty($val['round_value'])?$val['round_value']:'0';
			            $inv_value    = !empty($val['inv_value'])?$val['inv_value']:'0';
			            $totNetVal   += $inv_value;

			            $num = array(
			            	$inv_no,
			            	date('d-m-Y', strtotime($inv_date)),
			            	$order_no,
			            	$company_name,
			            	$due_days,
			            	0,
			            	number_format((float)$round_value, 2, '.', ''),
			            	number_format((float)$inv_value, 2, '.', ''),
			            );

			            fputcsv($output, $num);
		    		}

		    		$num = array(
		            	'',
		            	'',
		            	'',
		            	'',
		            	'',
		            	'',
		            	'',
		            	number_format((float)$totNetVal, 2, '.', ''),
		            );

		            fputcsv($output, $num);
		    	}

		    	fclose($output);
      			exit();
        	}

        	if($param1 == 'gst_export')
        	{
        		$start_date  = $param2; 
        		$end_date    = $param3;

        		$sales_whr = array(
			    	'start_date' => date('Y-m-d', strtotime($start_date)),
			    	'end_date'   => date('Y-m-d', strtotime($end_date)),
			    	'vendor_id'  => $this->session->userdata('id'),
			    	'method'     => '_overallGstReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/vendor_report',$sales_whr);

			    header('Content-Type: text/csv; charset=utf-8');  
                header('Content-Disposition: attachment; filename=vendor_tax_report('.$start_date.' to '.$end_date.').csv');  
                $output = fopen("php://output", "w");   
                fputcsv($output, array('GSTIN/UIN of Recipient', 'Invoice Number', 'Invoice Date', 'Invoice Value', 'Place Of Supply', 'Reverse Charge', 'Applicable % of Tax Rate', 'Invoice Type', 'E-Commerce GSTIN', 'Rate', 'Taxable Value', 'Cess Amount'));

                if($data_list['status'] == 1)
                {
                	$data_val  = $data_list['data']; 
                	$totTaxVal = 0;

                	foreach ($data_val as $key => $val) {
                		$company_gst  = !empty($val['company_gst'])?$val['company_gst']:'';
	                    $invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
	                    $invoice_date = !empty($val['invoice_date'])?$val['invoice_date']:'';
	                    $state_gst    = !empty($val['state_gst'])?$val['state_gst']:'';
	                    $state_name   = !empty($val['state_name'])?$val['state_name']:'';
	                    $gst_rate     = !empty($val['gst_rate'])?$val['gst_rate']:'';
	                    $invoice_val  = !empty($val['invoice_val'])?$val['invoice_val']:'';
	                    $product_val  = !empty($val['product_val'])?$val['product_val']:'';
	                    $taxable_val  = !empty($val['taxable_val'])?$val['taxable_val']:'';
	                    $supply_place = $state_gst.' - '.$state_name;
	                    $totTaxVal   += $taxable_val;

	                    $num = array(
	                    	$company_gst,
	                    	$invoice_no,
	                    	$invoice_date,
	                    	$invoice_val,
	                    	$supply_place,
	                    	'N',
	                    	'',
	                    	'Regular',
	                    	'',
	                    	$gst_rate,
	                    	$taxable_val,
	                    	''
	                    );

	                    fputcsv($output, $num);
                	}

                	$num = array(
                    	'',
                    	'',
                    	'',
                    	'',
                    	'',
                    	'',
                    	'',
                    	'',
                    	'',
                    	'',
                    	$totTaxVal,
                    	''
                    );

                    fputcsv($output, $num);
                }

                fclose($output);
                exit();
        	}

        	if($param1 == 'new_export')
        	{
        		$start_date  = $param2; 
        		$end_date    = $param3;

        		 $sales_whr = array(
			    	'start_date' => date('Y-m-d', strtotime($start_date)),
			    	'end_date'   => date('Y-m-d', strtotime($end_date)),
			    	'vendor_id'  => $this->session->userdata('id'),
			    	'method'     => '_overallSalesReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/vendor_report',$sales_whr);

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=new_tally_report('.generateRandomString(15).'_'.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('InvNo', 'Inv_Dt', 'Pty_Name', 'Vch_Type', 'GSTIN', 'StateOfSupply', 'Product_Name', 'HSNCode', 'Qty', 'UOM', 'TaxPer', 'TaxableAmt', 'IGSTAmt', 'SGSTAmt', 'CGSTAmt', 'Cess', 'OtherAmt', 'Net_Amt', 'Narration', 'Discount', 'Sales Ledger', 'PO No', 'PO Date', 'DC No', 'DC Date', 'Bill of Supply'));

			    if($data_list['status'] == 1)
		    	{
		    		$data_val = $data_list['data'];	

		    		$totQty     = 0;
		    		$totTaxable = 0;
		    		$totIgstAmt = 0;
		    		$totSgstAmt = 0;
		    		$totCgstAmt = 0;
		    		$totNetAmt  = 0;
		    		foreach ($data_val as $key => $val) {

		    			$pur_no       = !empty($val['pur_no'])?$val['pur_no']:'';
	                    $pur_date     = !empty($val['pur_date'])?$val['pur_date']:'';
	                    $ven_state_id = !empty($val['ven_state_id'])?$val['ven_state_id']:'';
	                    $invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
			            $admin_name   = !empty($val['admin_name'])?$val['admin_name']:'';
			            $mobile       = !empty($val['mobile'])?$val['mobile']:'';
			            $gst_no       = !empty($val['gst_no'])?$val['gst_no']:'';
			            $address      = !empty($val['address'])?$val['address']:'';
			            $state_id     = !empty($val['state_id'])?$val['state_id']:'';
			            $state_name   = !empty($val['state_name'])?$val['state_name']:'';
			            $gst_code     = !empty($val['gst_code'])?$val['gst_code']:'';
			            $invoice_date = !empty($val['invoice_date'])?$val['invoice_date']:'';
			            $description  = !empty($val['description'])?$val['description']:'';
			            $hsn_code     = !empty($val['hsn_code'])?$val['hsn_code']:'';
			            $gst_value    = !empty($val['gst_val'])?$val['gst_val']:'0';
	                    $pdt_price    = !empty($val['price'])?$val['price']:'0';
			            $pdt_qty      = !empty($val['order_qty'])?$val['order_qty']:'0';
			            $discount     = 0;

			            $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
                        $price_val  = $pdt_price - $gst_data;
                        $pdt_gst    = $pdt_qty * $gst_data;
                        $TaxableAmt = $pdt_qty * $price_val;
                        $tot_price  = $pdt_qty * $pdt_price;
                        $pdt_value  = $tot_price;
                        $total_dis  = $pdt_value * $discount / 100;
                        $total_val  = $pdt_value;

                        if($ven_state_id == $state_id)
                        {
                        	$gst_res  = $pdt_gst / 2;
                        	$sgst_val = number_format((float)$gst_res, 2, '.', '');
                        	$cgst_val = number_format((float)$gst_res, 2, '.', '');
                        	$igst_val = '0';
                        	$vch_type = 'Local Sales';
                        	$sale_led = 'Sales@'.$gst_value.'%';
                        }
                        else
                        {
                        	$sgst_val = '0';
                        	$cgst_val = '0';
                        	$igst_val = number_format((float)$pdt_gst, 2, '.', '');
                        	$vch_type = 'Inter Sales';
                        	$sale_led = 'Interstate Sales@'.$gst_value.'%';
                        }

                        $totQty     += $pdt_qty;
			    		$totTaxable += $TaxableAmt;
			    		$totIgstAmt += $igst_val;
			    		$totSgstAmt += $sgst_val;
			    		$totCgstAmt += $cgst_val;
			    		$totNetAmt  += $total_val;

                        $num = array(
			            	$invoice_no,
			            	date('d-m-Y', strtotime($invoice_date)),
			            	$admin_name,
			            	$vch_type,
			            	$gst_no,
			            	$state_name,
			            	$description,
			            	$hsn_code,
			            	$pdt_qty,
			            	'NOS',
			            	$gst_value,
			            	number_format((float)$TaxableAmt, 2, '.', ''),
			            	$igst_val,
			            	$sgst_val,
			            	$cgst_val,
			            	'0',
			            	'0',
			            	number_format((float)$total_val, 2, '.', ''),
			            	'',
			            	'0',
			            	$sale_led,
			            	$pur_no,
			            	date('d-m-Y', strtotime($pur_date)),
			            	'',
			            	'',
			            	'',
			            );

			            fputcsv($output, $num);
		    		}

		    		fputcsv($output, array('', '', '', '', '', '', '', '', $totQty, '', '', number_format((float)$totTaxable, 2, '.', ''), number_format((float)$totIgstAmt, 2, '.', ''), number_format((float)$totSgstAmt, 2, '.', ''), number_format((float)$totCgstAmt, 2, '.', ''), '0', '0', round($totNetAmt), '', '', '', '', '', '', '', ''));
		    	}

		    	fclose($output);
      			exit();
        	}	

        	else
        	{
		    	$page['method']       = '_getSalesData';
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Sales Report";
				$page['page_title']   = "Sales Report";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('vendors/report/sales_report',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "sales_report";
				$this->bassthaya->load_vendors_form_template($data);
        	}
		}

		public function stock_entry_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method    = $this->input->post('method');
        	$agents_id = $this->session->userdata('id');

        	if($method == '_getStockData')
        	{
        		$start_date  = $this->input->post('start_date');
			    $end_date    = $this->input->post('end_date');

			    $stock_whr = array(
			    	'start_date' => date('Y-m-d', strtotime($start_date)),
					'end_date'   => date('Y-m-d', strtotime($end_date)),
					'vendor_id'  => $agents_id,
			    	'method'     => '_vendorStockEntryReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/stock_entry_report',$stock_whr);
		    	
		    	if($data_list['status'] == 1)
		    	{
		    		$html     = '';
		    		$data_val = $data_list['data'];

		    		$num = 1;
		    		foreach ($data_val as $key => $val) {

		    			$description = !empty($val['description'])?$val['description']:'';
	                    $stock_val   = !empty($val['stock_val'])?$val['stock_val']:'0';
	                    $damage_val  = !empty($val['damage_val'])?$val['damage_val']:'0';
	                    $expiry_val  = !empty($val['expiry_val'])?$val['expiry_val']:'0';
	                    $entry_date  = !empty($val['entry_date'])?$val['entry_date']:'';

						$html .= '
			            	<tr>
                                <td>'.$num.'</td>
                                <td>'.mb_strimwidth($description, 0, 30, '...').'</td>
                                <td>'.$entry_date.'</td>
                                <td>'.$stock_val.'</td>
                                <td>'.$damage_val.'</td>
                                <td>'.$expiry_val.'</td>
                            </tr>
			            ';

			            $num++;
		    		}		

		    		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/vendors/report/stock_entry_report/excel_export/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Excel Export</a>';

		    		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/vendors/report/stock_entry_report/pdf_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

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
			        echo json_encode($response);
			        return;
		    	}
        	}

        	if($param1 == 'excel_export')
        	{
        		$start_date  = $param2; 
        		$end_date    = $param3;

        		$stock_whr = array(
			    	'start_date' => date('Y-m-d', strtotime($start_date)),
					'end_date'   => date('Y-m-d', strtotime($end_date)),
			    	'vendor_id'  => $agents_id,
			    	'method'     => '_vendorStockEntryReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/stock_entry_report',$stock_whr);
		    		
		    	header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=stock_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Description', 'Date', 'Stock Value', 'Damage Value', 'Expiry Value'));

		    	if($data_list['status'] == 1)
		    	{
		    		$data_val = $data_list['data'];	

		    		foreach ($data_val as $key => $val) {
		    			$description = !empty($val['description'])?$val['description']:'';
	                    $stock_val   = !empty($val['stock_val'])?$val['stock_val']:'0';
	                    $damage_val  = !empty($val['damage_val'])?$val['damage_val']:'0';
	                    $expiry_val  = !empty($val['expiry_val'])?$val['expiry_val']:'0';
	                    $entry_date  = !empty($val['entry_date'])?$val['entry_date']:'';

	                    $num = array(
			            	$description,
			            	$entry_date,
			            	$stock_val,
			            	$damage_val,
			            	$expiry_val,
			            );

			            fputcsv($output, $num); 
		    		}
		    	}

		    	fclose($output);
      			exit();
        	}

        	else
        	{
		    	$page['method']       = '_getStockData';
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Stock Entry Report";
				$page['page_title']   = "Stock Entry Report";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('vendors/report/stock_entry_report',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "stock_entry_report";
				$this->bassthaya->load_vendors_form_template($data);
        	}
		}

		public function product_stock($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method    = $this->input->post('method');
        	$vendor_id = $this->session->userdata('id');

			if($method =='_getProductStock')
			{
				$category_id  = $this->input->post('category_id');
        		$category_val = implode(',', $category_id);
        		$category_res = implode('_', $category_id);

				$stock_whr  = array(
        			'category_id' => $category_val,
        			'vendor_id'   => $vendor_id,
            		'method'      => '_overallVendorProductStockReport'
            	);

            	$data_list = avul_call(API_URL.'report/api/stock_report',$stock_whr);
            	$data_res  = $data_list['data'];

            	if($data_list['status'] == 1)
			    {
			    	$html = '';
		    		$num  = 1;
		    		foreach ($data_res as $key => $val) {

		    			$description  = !empty($val['description'])?$val['description']:'';
					    $product_type = !empty($val['product_type'])?$val['product_type']:'0';
					    $gst_value    = !empty($val['gst_value'])?$val['gst_value']:'0';
					    $ven_price    = !empty($val['ven_price'])?$val['ven_price']:'0';
					    $stock_detail = !empty($val['stock_detail'])?$val['stock_detail']:'0';

					    $html .= '
			            	<tr>
                                <td>'.$num.'</td>
                                <td>'.$description.'</td>
                                <td>'.number_format((float)$ven_price, 2, '.', '').'</td>
                                <td>'.$stock_detail.'</td>
                                <td>Nos</td>
                            </tr>
			            ';

			            $num++;
		    		}

		    		$excel_btn = '<a class="btn btn-warning m-t-27" target="_blank" href="'.BASE_URL.'index.php/vendors/report/product_stock/excel_export/'.$category_res.'" style="color: #fff;"><i class="ft-shopping-cart"></i> Excel</a>';

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

			if($param1 == 'excel_export')
			{
				$category_id  = $param2;
				$category_res = explode('_', $category_id);
				$category_val = implode(',', $category_res);

        		$stock_whr  = array(
        			'category_id' => $category_val,
        			'vendor_id'   => $vendor_id,
            		'method'      => '_overallVendorProductStockReport'
            	);

			    $data_list = avul_call(API_URL.'report/api/stock_report',$stock_whr);
            	$data_res  = $data_list['data'];

            	header('Content-Type: text/csv; charset=utf-8');  
                header('Content-Disposition: attachment; filename=vendor_stock_report.csv');  
                $output = fopen("php://output", "w");   
                fputcsv($output, array('Description', 'Tax', 'Stock', 'Sum of Taxable Value', 'Sum of SGST Value', 'Sum of CGST Value', 'Sum of Net Amount'));

                if($data_list['status'] == 1)
                {
                	$data_val = $data_list['data']; 

                	$totQty     = 0;
                	$totTaxable = 0;
                	$totSgst    = 0;
                	$totCgst    = 0;
                	$totValue   = 0;

                	foreach ($data_val as $key => $val) {

                		$description  = !empty($val['description'])?$val['description']:'';
			            $product_type = !empty($val['product_type'])?$val['product_type']:'';
			            $gst_value    = !empty($val['gst_value'])?$val['gst_value']:'0';
			            $pdt_price    = !empty($val['ven_price'])?$val['ven_price']:'0';
			            $pdt_qty      = !empty($val['stock_detail'])?$val['stock_detail']:'0';

			            $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
                        $price_val  = $pdt_price - $gst_data;
                        $pdt_gst    = $pdt_qty * $gst_data;
                        $TaxableAmt = $pdt_qty * $price_val;
                        $tot_price  = $pdt_qty * $pdt_price;
                        $pdt_value  = round($tot_price);
                        $gst_res    = $pdt_gst / 2;

                        $totQty     += $pdt_qty;
	                	$totTaxable += $TaxableAmt;
	                	$totSgst    += $gst_res;
	                	$totCgst    += $gst_res;
	                	$totValue   += $pdt_value;

                        $num = array(
			            	$description,
			            	$gst_value,
			            	$pdt_qty,
			            	number_format((float)$TaxableAmt, 2, '.', ''),
			            	number_format((float)$gst_res, 2, '.', ''),
			            	number_format((float)$gst_res, 2, '.', ''),
			            	number_format((float)$pdt_value, 2, '.', ''),
			            );

                        fputcsv($output, $num);
                	}

                	fputcsv($output, array('', '', $totQty, number_format((float)$totTaxable, 2, '.', ''), number_format((float)$totSgst, 2, '.', ''), number_format((float)$totCgst, 2, '.', ''), number_format((float)$totValue, 2, '.', '')));
                }

                fclose($output);
      			exit();
			}	

			else
        	{
        		$where_1 = array(
					'item_type'      => '1',
					'salesagents_id' => '0',
            		'method'         => '_listCategory',
            	);

            	$category_list = avul_call(API_URL.'catlog/api/category',$where_1);

        		$page['method']        = '_getProductStock';
        		$page['category_val']  = $category_list['data'];
				$page['main_heading']  = "Report";
				$page['sub_heading']   = "Report";
				$page['pre_title']     = "Product Stock";
				$page['page_title']    = "Product Stock";
				$page['pre_menu']      = "";
				$data['page_temp']     = $this->load->view('vendors/report/product_stock',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "product_stock";
				$this->bassthaya->load_vendors_form_template($data);
        	}
		}
	}
?>
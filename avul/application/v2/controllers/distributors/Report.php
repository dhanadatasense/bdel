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

		public function sub_distributor_sales_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        {
        	if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');	

        	if($method == '_getSalesData')
        	{	
        		$distributor_id = $this->input->post('distributor_id');
        		$start_date     = $this->input->post('start_date');
			    $end_date       = $this->input->post('end_date');

			    $sales_whr = array(
			    	'distributor_id' => $distributor_id,
					'ref_id'         => $this->session->userdata('id'),
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
					'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'method'         => '_overallInviceReport',
			    );
				//print_r($sales_whr);exit;
			    $data_list  = avul_call(API_URL.'report/api/sales_report', $sales_whr);

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
		    			// print_r($val); exit; 

                        $order_no     = !empty($val['order_no'])?$val['order_no']:'';
                        $inv_no       = !empty($val['inv_no'])?$val['inv_no']:'';
                        $inv_date     = !empty($val['inv_date'])?$val['inv_date']:'';
                        $company_name = !empty($val['company_name'])?$val['company_name']:'';
                        $inv_value    = !empty($val['inv_value'])?$val['inv_value']:'';
                        $pur_res      = !empty($val['purchase_res'])?$val['purchase_res']:'';
                        $inv_res      = !empty($val['invoice_res'])?$val['invoice_res']:'';

						$html .= '
			            	<tr>
                                <td>'.$num.'</td>
                                <td><a target="_blank" href="'.BASE_URL.'index.php/distributors/Branchpurchase/print_invoice/'.$inv_res.'">'.$inv_no.'</a></td>
                                <td><a target="_blank" href="'.BASE_URL.'index.php/distributors/Branchpurchase/print_order/'.$pur_res.'">'.$order_no.'</a></td>

                                <td>'.mb_strimwidth($company_name, 0, 25, '...').'</td>
                                <td>'.date('d-M-Y', strtotime($inv_date)).'</td>
                                <td>'.number_format((float)$inv_value, 2, '.', '').'</td>
                            </tr>
			            ';

			            $num++;
		    		}

		    		// $sales_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sub_distributor_sales_report/sales_export/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Sales Export</a>';

		    		$tally_btn = '<a class="btn btn-warning" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sub_distributor_sales_report/tally_export/'.$start_date.'/'.$end_date.'/'.$distributor_id.'" style="color: #fff;"><i class="icon-grid"></i> Tally</a>';

		    		$gst_btn = '<a class="btn btn-info" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sub_distributor_sales_report/gst_export/'.$start_date.'/'.$end_date.'/'.$distributor_id.'" style="color: #fff;"><i class="icon-grid"></i> GST</a>';

		    		$invoice_btn = '<a class="btn btn-success" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sub_distributor_sales_report/invoice_export/'.$start_date.'/'.$end_date.'/'.$distributor_id.'" style="color: #fff;"><i class="ft-shopping-cart"></i> Invoice</a>';

		    		$new_btn = '<a class="btn btn-danger" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sub_distributor_sales_report/new_export/'.$start_date.'/'.$end_date.'/'.$distributor_id.'" style="color: #fff;"><i class="icon-grid"></i> New</a>';

		    		$pdf_btn   = '<a class="btn btn-danger" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sub_distributor_sales_report/pdf_print/'.$start_date.'/'.$end_date.'/'.$distributor_id.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		    		$response['status']      = 1;
			        $response['message']     = $data_list['message']; 
			        $response['count_val']   = $count_val;
			        $response['data']        = $html;
			        // $response['sales_btn']   = $sales_btn;
			        $response['tally_btn']   = $tally_btn;
			        $response['invoice_btn'] = $invoice_btn;
			        $response['new_btn']     = $new_btn;
			        $response['gst_btn']     = $gst_btn;
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
        		$start_date     = $param2; 
        		$end_date       = $param3;
        		$distributor_id = $param4;

        		$sales_whr = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
					'end_date'       => date('Y-m-d', strtotime($end_date)),
					'distributor_id' => $distributor_id,
			    	'method'         => '_salesReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/sales_report', $sales_whr);

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

		    			$adm_state    = !empty($val['adm_state'])?$val['adm_state']:'';
					    $invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
					    $order_no     = !empty($val['order_no'])?$val['order_no']:'';
					    $dis_name     = !empty($val['dis_name'])?$val['dis_name']:'';
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

                        if($adm_state == $state_id)
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
			            	$dis_name,
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

        	if($param1 == 'gst_export')
        	{
        		$start_date     = $param2; 
        		$end_date       = $param3;
        		$distributor_id = $param4;

        		$sales_whr = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
					'end_date'       => date('Y-m-d', strtotime($end_date)),
					'distributor_id' => $distributor_id,
			    	'method'         => '_salesGstReport',
			    );

        		$data_list  = avul_call(API_URL.'report/api/sales_report', $sales_whr);
			    
			    header('Content-Type: text/csv; charset=utf-8');  
                header('Content-Disposition: attachment; filename=distributor_tax_report('.$start_date.' to '.$end_date.').csv');  
                $output = fopen("php://output", "w");   
                fputcsv($output, array('Company Name', 'GSTIN/UIN of Recipient', 'Invoice Number', 'Invoice Date', 'Invoice Value', 'Place Of Supply', 'Reverse Charge', 'Applicable % of Tax Rate', 'Invoice Type', 'E-Commerce GSTIN', 'Rate', 'Taxable Value', 'Cess Amount'));

                if($data_list['status'] == 1)
                {
                	$data_val  = $data_list['data']; 
                	$totTaxVal = 0;

                	foreach ($data_val as $key => $val) {
                		$company_name = !empty($val['company_name'])?$val['company_name']:'';
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
	                    	$company_name,
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
                    	'',
                    	$totTaxVal,
                    	''
                    );

                    fputcsv($output, $num);
                }

                fclose($output);
                exit();
        	}

        	if($param1 == 'invoice_export')
        	{
        		$start_date     = $param2; 
        		$end_date       = $param3;
        		$distributor_id = $param4;

        		$sales_whr = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'distributor_id' => $distributor_id,
			    	'method'         => '_overallInviceReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/sales_report',$sales_whr);

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=distributor_invoice_report('.$start_date.' to '.$end_date.').csv');  
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

        	if($param1 == 'new_export')
        	{
        		$start_date     = $param2; 
        		$end_date       = $param3;
        		$distributor_id = $param4;

        		$sales_whr = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'distributor_id' => $distributor_id,
			    	'method'         => '_overallSalesReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/sales_report',$sales_whr);

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=new_tally_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('InvNo', 'Inv_Dt', 'Pty_Name', 'Vch_Type', 'GSTIN', 'StateOfSupply', 'Product_Name', 'HSNCode', 'Qty', 'UOM', 'TaxPer', 'TaxableAmt', 'IGSTAmt', 'SGSTAmt', 'CGSTAmt', 'Cess', 'OtherAmt', 'Net_Amt', 'Narration' , 'Discount', 'Sales Ledger', 'PO No', 'PO Date', 'DC No', 'DC Date', 'Bill of Supply'));

			    if($data_list['status'] == 1)
			    {
			    	$data_val = $data_list['data'];	

			    	$totQty      = 0;
		    		$totTaxable  = 0;
		    		$totIgstAmt  = 0;
		    		$totSgstAmt  = 0;
		    		$totCgstAmt  = 0;
		    		$totNetAmt   = 0;
		    		$totDiscount = 0;

			    	foreach ($data_val as $key => $val) {
			    		$adm_state   = !empty($val['adm_state'])?$val['adm_state']:'';
	                    $invoice_no  = !empty($val['invoice_no'])?$val['invoice_no']:'';
	                    $order_no    = !empty($val['order_no'])?$val['order_no']:'';
	                    $order_date  = !empty($val['order_date'])?$val['order_date']:'';
	                    $dis_name    = !empty($val['dis_name'])?$val['dis_name']:'';
	                    $mobile      = !empty($val['mobile'])?$val['mobile']:'';
	                    $gst_no      = !empty($val['gst_no'])?$val['gst_no']:'';
	                    $address     = !empty($val['address'])?$val['address']:'';
	                    $state_id    = !empty($val['state_id'])?$val['state_id']:'';
	                    $state_name  = !empty($val['state_name'])?$val['state_name']:'';
	                    $gst_code    = !empty($val['gst_code'])?$val['gst_code']:'';
	                    $inv_date    = !empty($val['invoice_date'])?$val['invoice_date']:'';
	                    $description = !empty($val['description'])?$val['description']:'';
	                    $hsn_code    = !empty($val['hsn_code'])?$val['hsn_code']:'';
	                    $gst_value   = !empty($val['gst_val'])?$val['gst_val']:'0';
			            $pdt_price   = !empty($val['price'])?$val['price']:'0';
			            $pdt_qty     = !empty($val['order_qty'])?$val['order_qty']:'0';
			            $discount    = 0;

			            $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
                        $price_val  = $pdt_price - $gst_data;
                        $pdt_gst    = $pdt_qty * $gst_data;
                        $TaxableAmt = $pdt_qty * $price_val;
                        $tot_price  = $pdt_qty * $pdt_price;
                        $pdt_value  = $tot_price;
                        $total_dis  = $pdt_value * $discount / 100;
                        $total_val  = $pdt_value;

                        if($adm_state == $state_id)
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

                        $totQty      += $pdt_qty;
			    		$totTaxable  += $TaxableAmt;
			    		$totIgstAmt  += $igst_val;
			    		$totSgstAmt  += $sgst_val;
			    		$totCgstAmt  += $cgst_val;
			    		$totNetAmt   += $total_val;
			    		$totDiscount += $total_dis;

			    		$num = array(
			            	$invoice_no,
			            	date('d-m-Y', strtotime($inv_date)),
			            	$dis_name,
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
			            	'As pr Invoice No '.$invoice_no,
			            	number_format((float)$total_dis, 2, '.', ''),
			            	$sale_led,
			            	$order_no,
			            	date('d-m-Y', strtotime($order_date)),
			            	'',
			            	'',
			            	$address,
			            );

			            fputcsv($output, $num);
			    	}

			    	fputcsv($output, array('', '', '', '', '', '', '', '', $totQty, '', '', number_format((float)$totTaxable, 2, '.', ''), number_format((float)$totIgstAmt, 2, '.', ''), number_format((float)$totSgstAmt, 2, '.', ''), number_format((float)$totCgstAmt, 2, '.', ''), '', '', number_format((float)$totNetAmt, 2, '.', ''), '', number_format((float)$totDiscount, 2, '.', ''), '', '', '', '', '', ''));

			    	fclose($output);
      				exit();
			    }
        	}

        	else
        	{
        		$where_1 = array(
					'ref_id'   => $this->session->userdata('id'),
            		'method' => '_listOverallDistributors',
            	);

            	$distributor_list = avul_call(API_URL.'distributors/api/distributors',$where_1);

		    	$page['method']       = '_getSalesData';
		    	$page['dis_val']      = $distributor_list['data'];
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Distributor Sales Report";
				$page['page_title']   = "Distributor Sales Report";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('distributors/report/Distributor_Sales_Report',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "sub_distributor_sales_report";
				$this->bassthaya->load_distributors_form_template($data);
        	}
        }
		
		public function sub_dis_outlet_sales_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        {
        	if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');	

        	if($method == '_getSalesData')
        	{	
        		$distributor_id = $this->input->post('distributor_id');
        		$start_date     = $this->input->post('start_date');
			    $end_date       = $this->input->post('end_date');

			    $sales_whr = array(
			    	'distributor_id' => $distributor_id,
					'ref_id'         => $this->session->userdata('id'),
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
					'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'method'         => '_overallInviceReport',
			    );
			
			    $data_list  = avul_call(API_URL.'report/api/sub_dis_sales_report', $sales_whr);

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
		    			// print_r($val); exit; 

                        $order_no     = !empty($val['order_no'])?$val['order_no']:'';
                        $inv_no       = !empty($val['inv_no'])?$val['inv_no']:'';
                        $inv_date     = !empty($val['inv_date'])?$val['inv_date']:'';
                        $company_name = !empty($val['company_name'])?$val['company_name']:'';
						$store_name   = !empty($val['store_name'])?$val['store_name']:'';
                        $inv_value    = !empty($val['inv_value'])?$val['inv_value']:'';
                        $pur_res      = !empty($val['purchase_res'])?$val['purchase_res']:'';
                        $inv_res      = !empty($val['invoice_res'])?$val['invoice_res']:'';

						$html .= '
			            	<tr>
                                <td>'.$num.'</td>
                                <td><a target="_blank" href="'.BASE_URL.'index.php/distributors/Branchpurchase/print_invoice/'.$inv_res.'">'.$inv_no.'</a></td>
                                <td><a target="_blank" href="'.BASE_URL.'index.php/distributors/Branchpurchase/print_order/'.$pur_res.'">'.$order_no.'</a></td>

                                <td>'.mb_strimwidth($company_name, 0, 25, '...').'</td>
								<td>'.mb_strimwidth($store_name, 0, 25, '...').'</td>
                                <td>'.date('d-M-Y', strtotime($inv_date)).'</td>
                                <td>'.number_format((float)$inv_value, 2, '.', '').'</td>
                            </tr>
			            ';

			            $num++;
		    		}

		    		// $sales_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sub_dis_outlet_sales_report/sales_export/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Sales Export</a>';

		    		$tally_btn = '<a class="btn btn-warning" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sub_dis_outlet_sales_report/tally_export/'.$start_date.'/'.$end_date.'/'.$distributor_id.'" style="color: #fff;"><i class="icon-grid"></i> Tally</a>';

		    		$gst_btn = '<a class="btn btn-info" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sub_dis_outlet_sales_report/gst_export/'.$start_date.'/'.$end_date.'/'.$distributor_id.'" style="color: #fff;"><i class="icon-grid"></i> GST</a>';

		    		$invoice_btn = '<a class="btn btn-success" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sub_dis_outlet_sales_report/invoice_export/'.$start_date.'/'.$end_date.'/'.$distributor_id.'" style="color: #fff;"><i class="ft-shopping-cart"></i> Invoice</a>';

		    		$new_btn = '<a class="btn btn-danger" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sub_dis_outlet_sales_report/new_export/'.$start_date.'/'.$end_date.'/'.$distributor_id.'" style="color: #fff;"><i class="icon-grid"></i> New</a>';

		    		$pdf_btn   = '<a class="btn btn-danger" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sub_dis_outlet_sales_report/pdf_print/'.$start_date.'/'.$end_date.'/'.$distributor_id.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		    		$response['status']      = 1;
			        $response['message']     = $data_list['message']; 
			        $response['count_val']   = $count_val;
			        $response['data']        = $html;
			        // $response['sales_btn']   = $sales_btn;
			        $response['tally_btn']   = $tally_btn;
			        $response['invoice_btn'] = $invoice_btn;
			        $response['new_btn']     = $new_btn;
			        $response['gst_btn']     = $gst_btn;
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
        		$start_date     = $param2; 
        		$end_date       = $param3;
        		$distributor_id = $param4;

        		$sales_whr = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
					'end_date'       => date('Y-m-d', strtotime($end_date)),
					'distributor_id' => $distributor_id,
			    	'method'         => '_salesReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/sales_report', $sales_whr);

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

		    			$adm_state    = !empty($val['adm_state'])?$val['adm_state']:'';
					    $invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
					    $order_no     = !empty($val['order_no'])?$val['order_no']:'';
					    $dis_name     = !empty($val['dis_name'])?$val['dis_name']:'';
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

                        if($adm_state == $state_id)
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
			            	$dis_name,
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

        	if($param1 == 'gst_export')
        	{
        		$start_date     = $param2; 
        		$end_date       = $param3;
        		$distributor_id = $param4;

        		$sales_whr = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
					'end_date'       => date('Y-m-d', strtotime($end_date)),
					'distributor_id' => $distributor_id,
			    	'method'         => '_salesGstReport',
			    );

        		$data_list  = avul_call(API_URL.'report/api/sales_report', $sales_whr);
			    
			    header('Content-Type: text/csv; charset=utf-8');  
                header('Content-Disposition: attachment; filename=distributor_tax_report('.$start_date.' to '.$end_date.').csv');  
                $output = fopen("php://output", "w");   
                fputcsv($output, array('Company Name', 'GSTIN/UIN of Recipient', 'Invoice Number', 'Invoice Date', 'Invoice Value', 'Place Of Supply', 'Reverse Charge', 'Applicable % of Tax Rate', 'Invoice Type', 'E-Commerce GSTIN', 'Rate', 'Taxable Value', 'Cess Amount'));

                if($data_list['status'] == 1)
                {
                	$data_val  = $data_list['data']; 
                	$totTaxVal = 0;

                	foreach ($data_val as $key => $val) {
                		$company_name = !empty($val['company_name'])?$val['company_name']:'';
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
	                    	$company_name,
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
                    	'',
                    	$totTaxVal,
                    	''
                    );

                    fputcsv($output, $num);
                }

                fclose($output);
                exit();
        	}

        	if($param1 == 'invoice_export')
        	{
        		$start_date     = $param2; 
        		$end_date       = $param3;
        		$distributor_id = $param4;

        		$sales_whr = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'distributor_id' => $distributor_id,
			    	'method'         => '_overallInviceReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/sales_report',$sales_whr);

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=distributor_invoice_report('.$start_date.' to '.$end_date.').csv');  
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

        	if($param1 == 'new_export')
        	{
        		$start_date     = $param2; 
        		$end_date       = $param3;
        		$distributor_id = $param4;

        		$sales_whr = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'distributor_id' => $distributor_id,
			    	'method'         => '_overallSalesReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/sales_report',$sales_whr);

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=new_tally_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('InvNo', 'Inv_Dt', 'Pty_Name', 'Vch_Type', 'GSTIN', 'StateOfSupply', 'Product_Name', 'HSNCode', 'Qty', 'UOM', 'TaxPer', 'TaxableAmt', 'IGSTAmt', 'SGSTAmt', 'CGSTAmt', 'Cess', 'OtherAmt', 'Net_Amt', 'Narration' , 'Discount', 'Sales Ledger', 'PO No', 'PO Date', 'DC No', 'DC Date', 'Bill of Supply'));

			    if($data_list['status'] == 1)
			    {
			    	$data_val = $data_list['data'];	

			    	$totQty      = 0;
		    		$totTaxable  = 0;
		    		$totIgstAmt  = 0;
		    		$totSgstAmt  = 0;
		    		$totCgstAmt  = 0;
		    		$totNetAmt   = 0;
		    		$totDiscount = 0;

			    	foreach ($data_val as $key => $val) {
			    		$adm_state   = !empty($val['adm_state'])?$val['adm_state']:'';
	                    $invoice_no  = !empty($val['invoice_no'])?$val['invoice_no']:'';
	                    $order_no    = !empty($val['order_no'])?$val['order_no']:'';
	                    $order_date  = !empty($val['order_date'])?$val['order_date']:'';
	                    $dis_name    = !empty($val['dis_name'])?$val['dis_name']:'';
	                    $mobile      = !empty($val['mobile'])?$val['mobile']:'';
	                    $gst_no      = !empty($val['gst_no'])?$val['gst_no']:'';
	                    $address     = !empty($val['address'])?$val['address']:'';
	                    $state_id    = !empty($val['state_id'])?$val['state_id']:'';
	                    $state_name  = !empty($val['state_name'])?$val['state_name']:'';
	                    $gst_code    = !empty($val['gst_code'])?$val['gst_code']:'';
	                    $inv_date    = !empty($val['invoice_date'])?$val['invoice_date']:'';
	                    $description = !empty($val['description'])?$val['description']:'';
	                    $hsn_code    = !empty($val['hsn_code'])?$val['hsn_code']:'';
	                    $gst_value   = !empty($val['gst_val'])?$val['gst_val']:'0';
			            $pdt_price   = !empty($val['price'])?$val['price']:'0';
			            $pdt_qty     = !empty($val['order_qty'])?$val['order_qty']:'0';
			            $discount    = 0;

			            $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
                        $price_val  = $pdt_price - $gst_data;
                        $pdt_gst    = $pdt_qty * $gst_data;
                        $TaxableAmt = $pdt_qty * $price_val;
                        $tot_price  = $pdt_qty * $pdt_price;
                        $pdt_value  = $tot_price;
                        $total_dis  = $pdt_value * $discount / 100;
                        $total_val  = $pdt_value;

                        if($adm_state == $state_id)
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

                        $totQty      += $pdt_qty;
			    		$totTaxable  += $TaxableAmt;
			    		$totIgstAmt  += $igst_val;
			    		$totSgstAmt  += $sgst_val;
			    		$totCgstAmt  += $cgst_val;
			    		$totNetAmt   += $total_val;
			    		$totDiscount += $total_dis;

			    		$num = array(
			            	$invoice_no,
			            	date('d-m-Y', strtotime($inv_date)),
			            	$dis_name,
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
			            	'As pr Invoice No '.$invoice_no,
			            	number_format((float)$total_dis, 2, '.', ''),
			            	$sale_led,
			            	$order_no,
			            	date('d-m-Y', strtotime($order_date)),
			            	'',
			            	'',
			            	$address,
			            );

			            fputcsv($output, $num);
			    	}

			    	fputcsv($output, array('', '', '', '', '', '', '', '', $totQty, '', '', number_format((float)$totTaxable, 2, '.', ''), number_format((float)$totIgstAmt, 2, '.', ''), number_format((float)$totSgstAmt, 2, '.', ''), number_format((float)$totCgstAmt, 2, '.', ''), '', '', number_format((float)$totNetAmt, 2, '.', ''), '', number_format((float)$totDiscount, 2, '.', ''), '', '', '', '', '', ''));

			    	fclose($output);
      				exit();
			    }
        	}

        	else
        	{
        		$where_1 = array(
					'ref_id'   => $this->session->userdata('id'),
            		'method' => '_listOverallDistributors',
            	);

            	$distributor_list = avul_call(API_URL.'distributors/api/distributors',$where_1);

		    	$page['method']       = '_getSalesData';
		    	$page['dis_val']      = $distributor_list['data'];
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Sub-Distributor Sales Report";
				$page['page_title']   = "Sub-Distributor Sales Report";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('distributors/report/Distributor_outlet_sales_report',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "sub_dis_outlet_sales_report";
				$this->bassthaya->load_distributors_form_template($data);
        	}
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
			    	'start_date'     => $start_date,
			    	'end_date'       => $end_date,
			    	'distributor_id' => $this->session->userdata('id'),
			    	'method'         => '_overallInvoiceReport',
			    );

			    // print_r($sales_whr); exit();

			    $data_list  = avul_call(API_URL.'report/api/distributor_report',$sales_whr);
		    	
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

		    			$invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
		    			$order_no     = !empty($val['order_no'])?$val['order_no']:'';
						$str_name     = !empty($val['str_name'])?$val['str_name']:'';
						$invoice_date = !empty($val['invoice_date'])?$val['invoice_date']:'';
						$invoice_tot  = !empty($val['invoice_total'])?$val['invoice_total']:'';
						$inv_random   = !empty($val['inv_random'])?$val['inv_random']:'';
						$ord_random   = !empty($val['ord_random'])?$val['ord_random']:'';

						$html .= '
			            	<tr>
                                <td>'.$num.'</td>
                                <td><a target="_blank" href="'.BASE_URL.'index.php/distributors/order/print_invoice/'.$inv_random.'">'.$invoice_no.'</a></td>
                                <td><a target="_blank" href="'.BASE_URL.'index.php/distributors/order/print_order/'.$ord_random.'">'.$order_no.'</a></td>
                                <td>'.mb_strimwidth($str_name, 0, 30, '...').'</td>
                                <td>'.date('d-M-Y', strtotime($invoice_date)).'</td>
                                <td>'.number_format((float)$invoice_tot, 2, '.', '').'</td>
                            </tr>
			            ';

			            $num++;
		    		}		

		    		$sales_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sales_report/sales_export/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Sales</a>';

		    		$tally_btn = '<a class="btn btn-warning m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sales_report/tally_export/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-shopping-cart"></i> Tally</a>';

		    		$commission_btn = '<a class="btn btn-info m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sales_report/commission_export/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-cloud-download"></i> Commission</a>';

		    		$invoice_btn = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sales_report/invoice_export/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-notebook"></i> Invoice</a>';

		    		$xml_btn = '<a class="btn btn-primary m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sales_report/xml_report/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-notebook"></i> XML</a>';

		    		$gst_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sales_report/gst_report/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-notebook"></i> GST</a>';

		    		$new_btn = '<a class="btn btn-warning m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sales_report/new_export/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-shopping-cart"></i> New</a>';

		    		$cancel_btn = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sales_report/cancel_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-trash-2"></i> Cancel Invoice</a>';

		    		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sales_report/pdf_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		    		$response['status']         = 1;
			        $response['message']        = $data_list['message']; 
			        $response['count_val']      = $count_val;
			        $response['data']           = $html;
			        $response['sales_btn']      = $sales_btn;
			        $response['tally_btn']      = $tally_btn;
			        $response['commission_btn'] = $commission_btn;
			        $response['invoice_btn']    = $invoice_btn;
			        $response['new_btn']        = $new_btn;
			        $response['xml_btn']        = $xml_btn;
			        $response['gst_btn']        = $gst_btn;
			        $response['cancel_btn']     = $cancel_btn;
			        $response['pdf_btn']        = $pdf_btn;
			        $response['error']          = []; 
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

        	if($param1 == 'sales_export')
        	{
        		$start_date  = $param2; 
        		$end_date    = $param3;

        		 $sales_whr = array(
			    	'start_date'     => $start_date,
			    	'end_date'       => $end_date,
			    	'distributor_id' => $this->session->userdata('id'),
			    	'method'         => '_overallSalesReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/distributor_report',$sales_whr);
		    		
		    	header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=sales_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Invoice No', 'Order No', 'Employee Name', 'Store Name', 'Mobile', 'GSTIN', 'Address', 'GSTIN Code', 'Due Days', 'Invoice Date', 'Product Name', 'HSN Code', 'GST Value', 'Rate', 'Quantity', 'Per', 'CGST', 'SGST', 'IGST', 'Discount', 'Price'));

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
		    			$dis_state_id = !empty($val['dis_state_id'])?$val['dis_state_id']:'';
		    			$invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
			            $order_no     = !empty($val['order_no'])?$val['order_no']:'';
			            $emp_name     = !empty($val['emp_name'])?$val['emp_name']:'';
			            $str_name     = !empty($val['str_name'])?$val['str_name']:'';
			            $mobile       = !empty($val['mobile'])?$val['mobile']:'';
			            $gst_no       = !empty($val['gst_no'])?$val['gst_no']:'';
			            $address      = !empty($val['address'])?$val['address']:'';
			            $state_id     = !empty($val['state_id'])?$val['state_id']:'';
			            $gst_code     = !empty($val['gst_code'])?$val['gst_code']:'';
			            $due_days     = !empty($val['due_days'])?$val['due_days']:'';
			            $invoice_date = !empty($val['invoice_date'])?$val['invoice_date']:'';
			            $discount     = !empty($val['discount'])?$val['discount']:'0';
			            $description  = !empty($val['description'])?$val['description']:'';
			            $hsn_code     = !empty($val['hsn_code'])?$val['hsn_code']:'';
			            $gst_value    = !empty($val['gst_val'])?$val['gst_val']:'0';
			            $pdt_price    = !empty($val['price'])?$val['price']:'0';
			            $pdt_qty      = !empty($val['order_qty'])?$val['order_qty']:'0';

			            $gst_data  = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
                        $price_val = $pdt_price - $gst_data;
                        $tot_price = $pdt_qty * $pdt_price;
                        // $pdt_value  = round($tot_price);
                        $total_dis  = $tot_price * $discount / 100;
                        $total_val  = $tot_price - $total_dis;

                        if($dis_state_id == $state_id)
                        {
                        	$gst_value = $gst_data / 2;

                        	$sgst_val = number_format((float)$gst_value, 2, '.', '');
                        	$cgst_val = number_format((float)$gst_value, 2, '.', '');
                        	$igst_val = '-';
                        }
                        else
                        {
                        	$sgst_val = '-';
                        	$cgst_val = '-';
                        	$igst_val = number_format((float)$gst_data, 2, '.', '');
                        }

			            $num = array(
			            	$invoice_no,
			            	$order_no,
			            	$emp_name,
			            	$str_name,
			            	$mobile,
			            	$gst_no,
			            	$address,
			            	$gst_code,
			            	$due_days,
			            	date('d-m-Y', strtotime($invoice_date)),
			            	$description,
			            	$hsn_code,
			            	$gst_value,
			            	number_format((float)$price_val, 2, '.', ''),
			            	$pdt_qty,
			            	'nos',
			            	$sgst_val,
			            	$cgst_val,
			            	$igst_val,
			            	number_format((float)$total_dis, 2, '.', ''),
			            	number_format((float)$total_val, 2, '.', ''),
			            );

			            fputcsv($output, $num);  
		    		}
		    	}

		    	fclose($output);
      			exit();
        	}

        	if($param1 == 'tally_export')
        	{
        		$start_date  = $param2; 
        		$end_date    = $param3;

        		 $sales_whr = array(
			    	'start_date'     => $start_date,
			    	'end_date'       => $end_date,
			    	'distributor_id' => $this->session->userdata('id'),
			    	'method'         => '_overallSalesReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/distributor_report',$sales_whr);
		    		
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

		    			$dis_state_id = !empty($val['dis_state_id'])?$val['dis_state_id']:'';
		    			$invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
			            $order_no     = !empty($val['order_no'])?$val['order_no']:'';
			            $emp_name     = !empty($val['emp_name'])?$val['emp_name']:'';
			            $str_name     = !empty($val['str_name'])?$val['str_name']:'';
			            $mobile       = !empty($val['mobile'])?$val['mobile']:'';
			            $gst_no       = !empty($val['gst_no'])?$val['gst_no']:'';
			            $address      = !empty($val['address'])?$val['address']:'';
			            $state_id     = !empty($val['state_id'])?$val['state_id']:'';
			            $state_name   = !empty($val['state_name'])?$val['state_name']:'';
			            $gst_code     = !empty($val['gst_code'])?$val['gst_code']:'';
			            $due_days     = !empty($val['due_days'])?$val['due_days']:'';
			            $invoice_date = !empty($val['invoice_date'])?$val['invoice_date']:'';
			            $discount     = !empty($val['discount'])?$val['discount']:'0';
			            $description  = !empty($val['description'])?$val['description']:'';
			            $hsn_code     = !empty($val['hsn_code'])?$val['hsn_code']:'';
			            $gst_value    = !empty($val['gst_val'])?$val['gst_val']:'0';
			            $pdt_price    = !empty($val['price'])?$val['price']:'0';
			            $pdt_qty      = !empty($val['order_qty'])?$val['order_qty']:'0';

			            $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
                        $price_val  = $pdt_price - $gst_data;
                        $pdt_gst    = $pdt_qty * $gst_data;
                        $TaxableAmt = $pdt_qty * $price_val;
                        $tot_price  = $pdt_qty * $pdt_price;
                        // $pdt_value  = round($tot_price);
                        $total_dis  = $tot_price * $discount / 100;
                        $total_val  = $tot_price - $total_dis;

                        if($dis_state_id == $state_id)
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
			            	$str_name,
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
		            	'',
		            	'',
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

        	if($param1 == 'commission_export')
        	{
        		$start_date  = $param2; 
        		$end_date    = $param3;

        		 $sales_whr = array(
			    	'start_date'     => $start_date,
			    	'end_date'       => $end_date,
			    	'distributor_id' => $this->session->userdata('id'),
			    	'method'         => '_overallSalesReport',
			    );

        		$data_list  = avul_call(API_URL.'report/api/distributor_report',$sales_whr);

        		header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=commision_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Invoice No', 'Order No', 'Employee Name', 'Store Name', 'Mobile', 'GSTIN', 'Address', 'GSTIN Code', 'Due Days', 'Invoice Date', 'Product Name', 'HSN Code', 'GST Value', 'Rate', 'Quantity', 'Per', 'CGST', 'SGST', 'IGST', 'Discount', 'Discount Price', 'Actual Price', 'Discount Value'));

        		if($data_list['status'] == 1)
		    	{
		    		$data_val = $data_list['data'];	

		    		foreach ($data_val as $key => $val) {
		    			$dis_state_id = !empty($val['dis_state_id'])?$val['dis_state_id']:'';
		    			$invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
			            $order_no     = !empty($val['order_no'])?$val['order_no']:'';
			            $emp_name     = !empty($val['emp_name'])?$val['emp_name']:'';
			            $str_name     = !empty($val['str_name'])?$val['str_name']:'';
			            $mobile       = !empty($val['mobile'])?$val['mobile']:'';
			            $gst_no       = !empty($val['gst_no'])?$val['gst_no']:'';
			            $address      = !empty($val['address'])?$val['address']:'';
			            $state_id     = !empty($val['state_id'])?$val['state_id']:'';
			            $gst_code     = !empty($val['gst_code'])?$val['gst_code']:'';
			            $due_days     = !empty($val['due_days'])?$val['due_days']:'';
			            $invoice_date = !empty($val['invoice_date'])?$val['invoice_date']:'';
			            $discount     = !empty($val['discount'])?$val['discount']:'0';
			            $description  = !empty($val['description'])?$val['description']:'';
			            $hsn_code     = !empty($val['hsn_code'])?$val['hsn_code']:'';
			            $gst_value    = !empty($val['gst_val'])?$val['gst_val']:'0';
			            $pdt_price    = !empty($val['price'])?$val['price']:'0';
			            $pdt_qty      = !empty($val['order_qty'])?$val['order_qty']:'0';
			            $act_price    = !empty($val['product_price'])?$val['product_price']:'0';

			            $gst_data  = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
                        $price_val = $pdt_price - $gst_data;
                        $tot_price = $pdt_qty * $pdt_price;
                        // $pdt_value = round($tot_price);
                        $total_dis = $tot_price * $discount / 100;
                        $total_val = $tot_price - $total_dis;
                        
                        $nor_total = $pdt_qty * $act_price;
                        $nor_price = round($nor_total);

                        $new_val   = $nor_price - round($total_val);

                        if($dis_state_id == $state_id)
                        {
                        	$gst_value = $gst_data / 2;

                        	$sgst_val = number_format((float)$gst_value, 2, '.', '');
                        	$cgst_val = number_format((float)$gst_value, 2, '.', '');
                        	$igst_val = '-';
                        }
                        else
                        {
                        	$sgst_val = '-';
                        	$cgst_val = '-';
                        	$igst_val = number_format((float)$gst_data, 2, '.', '');
                        }

                        if($new_val != 0)
                        {
	                       	$num = array(
				            	$invoice_no,
				            	$order_no,
				            	$emp_name,
				            	$str_name,
				            	$mobile,
				            	$gst_no,
				            	$address,
				            	$gst_code,
				            	$due_days,
				            	date('d-m-Y', strtotime($invoice_date)),
				            	$description,
				            	$hsn_code,
				            	number_format((float)$gst_value, 2, '.', ''),
				            	number_format((float)$price_val, 2, '.', ''),
				            	$pdt_qty,
				            	'nos',
				            	$sgst_val,
				            	$cgst_val,
				            	$igst_val,
				            	number_format((float)$total_dis, 2, '.', ''),
				            	number_format((float)$total_val, 2, '.', ''),
				            	number_format((float)$nor_price, 2, '.', ''),
				            	number_format((float)$new_val, 2, '.', ''),
				            );

				            fputcsv($output, $num);
                        }
		    		}
		    	}

		    	fclose($output);
      			exit();
        	}

        	if($param1 == 'invoice_export')
        	{
        		$start_date  = $param2; 
        		$end_date    = $param3;

        		 $sales_whr = array(
			    	'start_date'     => $start_date,
			    	'end_date'       => $end_date,
			    	'distributor_id' => $this->session->userdata('id'),
			    	'method'         => '_overallInvoiceReport',
			    );

        		$data_list  = avul_call(API_URL.'report/api/distributor_report',$sales_whr);

        		header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=invoice_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('InvNo', 'Inv_Dt', 'Pty_Name', 'Vch_Type', 'GSTIN', 'StateOfSupply', 'TaxableAmt', 'IGSTAmt', 'SGSTAmt', 'CGSTAmt', 'Cess', 'OtherAmt', 'Discount', 'Round_Amt', 'Net_Amt', 'Narration', 'Group', 'Address', 'Country', 'St. Group', 'Salees Ledger'));

        		if($data_list['status'] == 1)
		    	{
		    		$data_val = $data_list['data']['inv_list'];	

		    		foreach ($data_val as $key => $val) {
		    			$dis_username = !empty($val['dis_username'])?$val['dis_username']:'';
	                    $dis_state_id = !empty($val['dis_state_id'])?$val['dis_state_id']:'';
	                    $invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
	                    $order_no     = !empty($val['order_no'])?$val['order_no']:'';
	                    $emp_name     = !empty($val['emp_name'])?$val['emp_name']:'';
	                    $str_name     = !empty($val['str_name'])?$val['str_name']:'';
	                    $mobile       = !empty($val['mobile'])?$val['mobile']:'';
	                    $gst_no       = !empty($val['gst_no'])?$val['gst_no']:'';
	                    $address      = !empty($val['address'])?$val['address']:'';
	                    $state_id     = !empty($val['state_id'])?$val['state_id']:'';
	                    $state_name   = !empty($val['state_name'])?$val['state_name']:'';
	                    $gst_code     = !empty($val['gst_code'])?$val['gst_code']:'';
	                    $due_days     = !empty($val['due_days'])?$val['due_days']:'';
	                    $invoice_date = !empty($val['invoice_date'])?$val['invoice_date']:'';
	                    $discount     = !empty($val['discount'])?$val['discount']:'0';
	                    $taxable_amt  = !empty($val['taxable_amt'])?$val['taxable_amt']:'0';
	                    $tax_amt      = !empty($val['tax_amt'])?$val['tax_amt']:'0';
	                    $invoice_tot  = !empty($val['invoice_tot'])?$val['invoice_tot']:'0';

	                    $invData_list = !empty($val['invData_list'])?$val['invData_list']:'';

	                    $sub_tot = 0;
	                    $tot_gst = 0;
	                    $net_tot = 0;
	                    if($invData_list)
	                    {
	                    	foreach ($invData_list as $key => $val_1) {
	                    		$gst_value = !empty($val_1['gst_val'])?$val_1['gst_val']:'0';
                                $pdt_price = !empty($val_1['price_val'])?$val_1['price_val']:'0';
                                $pdt_qty   = !empty($val_1['order_qty'])?$val_1['order_qty']:'0';

                                $gst_data  = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
	                            $price_val = $pdt_price - $gst_data;
	                            $tot_price = $pdt_qty * $price_val;
	                            $sub_tot  += $tot_price;
                                $gst_val   = $pdt_qty * $gst_data;
                            	$tot_gst  += $gst_val;

                                $total_val = $pdt_qty * $pdt_price;
                            	$net_tot  += $total_val;
	                    	}
	                    }

	                    if($dis_state_id == $state_id)
                        {
                        	$gst_res  = $tax_amt / 2;
                        	$sgst_val = number_format((float)$gst_res, 2, '.', '');
                        	$cgst_val = number_format((float)$gst_res, 2, '.', '');
                        	$igst_val = '0';
                        	$vch_type = 'Local Sales';
                        }
                        else
                        {
                        	$sgst_val = '0';
                        	$cgst_val = '0';
                        	$igst_val = number_format((float)$tax_amt, 2, '.', '');
                        	$vch_type = 'Inter Sales';
                        }

                        // Round Val Details
                        $net_value  = round($invoice_tot);
                        $total_dis  = $net_value * $discount / 100;
                        $total_val  = $net_value - $total_dis;

                        // Round Val Details
                        $last_value = round($total_val);
                        $rond_total = $last_value - $total_val;

	                    $num = array(
			            	$invoice_no,
			            	date('d-m-Y', strtotime($invoice_date)),
			            	$str_name,
			            	$vch_type,
			            	$gst_no,
			            	$state_name,
			            	number_format((float)$taxable_amt, 2, '.', ''),
			            	$igst_val,
			            	$sgst_val,
			            	$cgst_val,
			            	'0',
			            	'0',
			            	number_format((float)$total_dis, 2, '.', ''),
			            	number_format((float)$rond_total, 2, '.', ''),
			            	number_format((float)$last_value, 2, '.', ''),
			            	'',
			            	'Sundry Debtors',
			            	$address,
			            	'India',
			            	'',
			            	'Sales'
			            	
			            );

			            fputcsv($output, $num);
		    		}
		    	}

		    	fclose($output);
      			exit();
        	}

        	if($param1 == 'xml_report')
        	{
        		$start_date  = $param2; 
        		$end_date    = $param3;

        		$sales_whr = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'distributor_id' => $this->session->userdata('id'),
			    	'method'         => '_outletXmlExport',
			    );

        		$data_list   = avul_call(API_URL.'report/api/distributor_report',$sales_whr);
        		$data_status = $data_list['status'];
        		$data_value  = $data_list['data'];

        		if($data_status == 1)
        		{
        			$dis_data = !empty($data_value['dis_data'])?$data_value['dis_data']:'';
        			$inv_data = !empty($data_value['inv_data'])?$data_value['inv_data']:'';

        			$dis_name  = !empty($dis_data['distributor_name'])?$dis_data['distributor_name']:'';
		            $dis_state = !empty($dis_data['distributor_state'])?$dis_data['distributor_state']:'';
		            $dis_vch   = !empty($dis_data['distributor_vch'])?$dis_data['distributor_vch']:'';

		            $date_val  = date('d-M-Y', strtotime($start_date)).' to '.date('d-M-Y', strtotime($end_date));
		            $file_name = $dis_name.'_'.$dis_vch.' ('.$date_val.').xml';

    			    header("Content-type: text/xml");
					header('Content-Disposition: filename="'.$file_name.'"');

        			$xml_output = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
				    $xml_output = "<ENVELOPE>\n";
				    $xml_output .= "<HEADER>\n";
				    $xml_output .= "<TALLYREQUEST>Import Data</TALLYREQUEST>\n";
				    $xml_output .= "</HEADER>\n";
				    $xml_output .= "<BODY>\n";
				    $xml_output .= "<IMPORTDATA>\n";
				    $xml_output .= "<REQUESTDESC>\n";
				    $xml_output .= "<REPORTNAME>All Masters</REPORTNAME>\n";
				    $xml_output .= "</REQUESTDESC>\n";
				    $xml_output .= "<STATICVARIABLES>\n";
				    $xml_output .= "<SVCURRENTCOMPANY>".$dis_name."</SVCURRENTCOMPANY>\n";
				    $xml_output .= "</STATICVARIABLES>\n";
				    $xml_output .= "<REQUESTDATA>\n";

				    if(!empty($inv_data))
				    {
				    	foreach ($inv_data as $key => $val) {
					    	$invoive_id       = !empty($val['invoive_id'])?$val['invoive_id']:'';
		                    $invoive_no       = !empty($val['invoive_no'])?$val['invoive_no']:'';
		                    $store_name       = !empty($val['store_name'])?$val['store_name']:'';
		                    $store_mobile     = !empty($val['store_mobile'])?$val['store_mobile']:'';
		                    $store_email      = !empty($val['store_email'])?$val['store_email']:'';
		                    $store_gst_no     = !empty($val['store_gst_no'])?$val['store_gst_no']:'';
		                    $store_address    = !empty($val['store_address'])?$val['store_address']:'';
		                    $store_state_id   = !empty($val['store_state_id'])?$val['store_state_id']:'';
		                    $store_state_name = !empty($val['store_state_name'])?$val['store_state_name']:'';
		                    $invoice_date     = !empty($val['invoice_date'])?$val['invoice_date']:'';
		                    $createdate       = !empty($val['createdate'])?$val['createdate']:'';
		                    $invoice_details  = !empty($val['invoice_details'])?$val['invoice_details']:'';
		                    $tax_details      = !empty($val['tax_details'])?$val['tax_details']:'';
		                    $round_details    = !empty($val['round_details'])?$val['round_details']:'';

		                    // Round Details
		                    $round_val = !empty($round_details['round_val'])?$round_details['round_val']:'0';
                            $net_val   = !empty($round_details['net_val'])?$round_details['net_val']:'0';

		                    $xml_output .= "<TALLYMESSAGE>\n";
		                    $xml_output .= "<VOUCHER REMOTEID='' VCHKEY='".$dis_vch."' VCHTYPE='Sales' ACTION='Create' OBJVIEW='Invoice Voucher View'>\n";
						    $xml_output .= "<ADDRESS.LIST TYPE='String'>\n";
						    $xml_output .= "<ADDRESS>".$store_address."</ADDRESS>\n";
						    $xml_output .= "</ADDRESS.LIST>\n";
						    $xml_output .= "<BASICBUYERADDRESS.LIST TYPE='String'>\n";
						    $xml_output .= "<BASICBUYERADDRESS>".$store_address."</BASICBUYERADDRESS>\n";
						    $xml_output .= "</BASICBUYERADDRESS.LIST>\n";
						    $xml_output .= "<OLDAUDITENTRYIDS.LIST TYPE='String'>\n";
						    $xml_output .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
						    $xml_output .= "</OLDAUDITENTRYIDS.LIST>\n";

						    $xml_output .= "<DATE>".date('Ymd', strtotime($createdate))."</DATE>\n";
						    $xml_output .= "<GUID></GUID>\n";
						    $xml_output .= "<STATENAME>".$store_state_name."</STATENAME>\n";
						    $xml_output .= "<COUNTRYOFRESIDENCE>India</COUNTRYOFRESIDENCE>\n";
						    $xml_output .= "<PARTYGSTIN>".$store_gst_no."</PARTYGSTIN>\n";
						    $xml_output .= "<PLACEOFSUPPLY>".$store_state_name."</PLACEOFSUPPLY>\n";
						    $xml_output .= "<PARTYNAME>".$store_name."</PARTYNAME>\n";
						    $xml_output .= "<PARTYLEDGERNAME>".$store_name."</PARTYLEDGERNAME>\n";
						    $xml_output .= "<VOUCHERTYPENAME>Sales</VOUCHERTYPENAME>\n";
						    $xml_output .= "<VOUCHERNUMBER>1</VOUCHERNUMBER>\n";
						    $xml_output .= "<BASICBASEPARTYNAME>".$store_name."</BASICBASEPARTYNAME>\n";    
						    $xml_output .= "<CSTFORMISSUETYPE/>\n";
						    $xml_output .= "<CSTFORMRECVTYPE/>\n";
						    $xml_output .= "<FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>\n";
						    $xml_output .= "<PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>\n";
						    $xml_output .= "<CONSIGNEEGSTIN>".$store_gst_no."</CONSIGNEEGSTIN>\n";
						    $xml_output .= "<BASICBUYERNAME>".$store_name."</BASICBUYERNAME>\n";
						    $xml_output .= "<BASICDATETIMEOFINVOICE>".$invoice_date."</BASICDATETIMEOFINVOICE>\n";
						    $xml_output .= "<VCHGSTCLASS/>\n";
						    $xml_output .= "<CONSIGNEESTATENAME>".$store_state_name."</CONSIGNEESTATENAME>\n";
						    $xml_output .= "<DIFFACTUALQTY>No</DIFFACTUALQTY>\n";
						    $xml_output .= "<ISMSTFROMSYNC>No</ISMSTFROMSYNC>\n";
						    $xml_output .= "<ASORIGINAL>No</ASORIGINAL>\n";
						    $xml_output .= "<AUDITED>No</AUDITED>\n";
						    $xml_output .= "<FORJOBCOSTING>No</FORJOBCOSTING>\n";
						    $xml_output .= "<ISOPTIONAL>No</ISOPTIONAL>\n";
						    $xml_output .= "<EFFECTIVEDATE>20220402</EFFECTIVEDATE>\n";
						    $xml_output .= "<USEFOREXCISE>No</USEFOREXCISE>\n";
						    $xml_output .= "<ISFORJOBWORKIN>No</ISFORJOBWORKIN>\n";
						    $xml_output .= "<ALLOWCONSUMPTION>No</ALLOWCONSUMPTION>\n";
						    $xml_output .= "<USEFORINTEREST>No</USEFORINTEREST>\n";
						    $xml_output .= "<USEFORGAINLOSS>No</USEFORGAINLOSS>\n";
						    $xml_output .= "<USEFORGODOWNTRANSFER>No</USEFORGODOWNTRANSFER>\n";
						    $xml_output .= "<USEFORCOMPOUND>No</USEFORCOMPOUND>\n";
						    $xml_output .= "<USEFORSERVICETAX>No</USEFORSERVICETAX>\n";
						    $xml_output .= "<ISDELETED>No</ISDELETED>\n";
						    $xml_output .= "<ISONHOLD>No</ISONHOLD>\n";
						    $xml_output .= "<ISBOENOTAPPLICABLE>No</ISBOENOTAPPLICABLE>\n";
						    $xml_output .= "<ISEXCISEVOUCHER>No</ISEXCISEVOUCHER>\n";
						    $xml_output .= "<EXCISETAXOVERRIDE>No</EXCISETAXOVERRIDE>\n";
						    $xml_output .= "<USEFORTAXUNITTRANSFER>No</USEFORTAXUNITTRANSFER>\n";
						    $xml_output .= "<IGNOREPOSVALIDATION>No</IGNOREPOSVALIDATION>\n";
						    $xml_output .= "<EXCISEOPENING>No</EXCISEOPENING>\n";
						    $xml_output .= "<USEFORFINALPRODUCTION>No</USEFORFINALPRODUCTION>\n";
						    $xml_output .= "<ISTDSOVERRIDDEN>No</ISTDSOVERRIDDEN>\n";
						    $xml_output .= "<ISTCSOVERRIDDEN>No</ISTCSOVERRIDDEN>\n";
						    $xml_output .= "<ISTDSTCSCASHVCH>No</ISTDSTCSCASHVCH>\n";
						    $xml_output .= "<INCLUDEADVPYMTVCH>No</INCLUDEADVPYMTVCH>\n";
						    $xml_output .= "<ISSUBWORKSCONTRACT>No</ISSUBWORKSCONTRACT>\n";
						    $xml_output .= "<ISVATOVERRIDDEN>No</ISVATOVERRIDDEN>\n";
						    $xml_output .= "<IGNOREORIGVCHDATE>No</IGNOREORIGVCHDATE>\n";
						    $xml_output .= "<ISVATPAIDATCUSTOMS>No</ISVATPAIDATCUSTOMS>\n";
						    $xml_output .= "<ISDECLAREDTOCUSTOMS>No</ISDECLAREDTOCUSTOMS>\n";
						    $xml_output .= "<ISSERVICETAXOVERRIDDEN>No</ISSERVICETAXOVERRIDDEN>\n";
						    $xml_output .= "<ISISDVOUCHER>No</ISISDVOUCHER>\n";
						    $xml_output .= "<ISEXCISEOVERRIDDEN>No</ISEXCISEOVERRIDDEN>\n";
						    $xml_output .= "<ISEXCISESUPPLYVCH>No</ISEXCISESUPPLYVCH>\n";
						    $xml_output .= "<ISGSTOVERRIDDEN>No</ISGSTOVERRIDDEN>\n";
						    $xml_output .= "<GSTNOTEXPORTED>No</GSTNOTEXPORTED>\n";
						    $xml_output .= "<IGNOREGSTINVALIDATION>No</IGNOREGSTINVALIDATION>\n";
						    $xml_output .= "<ISGSTREFUND>No</ISGSTREFUND>\n";
						    $xml_output .= "<ISGSTSECSEVENAPPLICABLE>No</ISGSTSECSEVENAPPLICABLE>\n";
						    $xml_output .= "<ISVATPRINCIPALACCOUNT>No</ISVATPRINCIPALACCOUNT>\n";
						    $xml_output .= "<ISSHIPPINGWITHINSTATE>No</ISSHIPPINGWITHINSTATE>\n";
						    $xml_output .= "<ISOVERSEASTOURISTTRANS>No</ISOVERSEASTOURISTTRANS>\n";
						    $xml_output .= "<ISDESIGNATEDZONEPARTY>No</ISDESIGNATEDZONEPARTY>\n";
						    $xml_output .= "<ISCANCELLED>No</ISCANCELLED>\n";
						    $xml_output .= "<HASCASHFLOW>No</HASCASHFLOW>\n";
						    $xml_output .= "<ISPOSTDATED>No</ISPOSTDATED>\n";
						    $xml_output .= "<USETRACKINGNUMBER>No</USETRACKINGNUMBER>\n";
						    $xml_output .= "<ISINVOICE>Yes</ISINVOICE>\n";
						    $xml_output .= "<MFGJOURNAL>No</MFGJOURNAL>\n";
						    $xml_output .= "<HASDISCOUNTS>No</HASDISCOUNTS>\n";
						    $xml_output .= "<ASPAYSLIP>No</ASPAYSLIP>\n";
						    $xml_output .= "<ISCOSTCENTRE>No</ISCOSTCENTRE>\n";
						    $xml_output .= "<ISSTXNONREALIZEDVCH>No</ISSTXNONREALIZEDVCH>\n";
						    $xml_output .= "<ISEXCISEMANUFACTURERON>No</ISEXCISEMANUFACTURERON>\n";
						    $xml_output .= "<ISBLANKCHEQUE>No</ISBLANKCHEQUE>\n";
						    $xml_output .= "<ISVOID>No</ISVOID>\n";
						    $xml_output .= "<ORDERLINESTATUS>No</ORDERLINESTATUS>\n";
						    $xml_output .= "<VATISAGNSTCANCSALES>No</VATISAGNSTCANCSALES>\n";
						    $xml_output .= "<VATISPURCEXEMPTED>No</VATISPURCEXEMPTED>\n";
						    $xml_output .= "<ISVATRESTAXINVOICE>No</ISVATRESTAXINVOICE>\n";
						    $xml_output .= "<VATISASSESABLECALCVCH>No</VATISASSESABLECALCVCH>\n";
						    $xml_output .= "<ISVATDUTYPAID>Yes</ISVATDUTYPAID>\n";
						    $xml_output .= "<ISDELIVERYSAMEASCONSIGNEE>No</ISDELIVERYSAMEASCONSIGNEE>\n";
						    $xml_output .= "<ISDISPATCHSAMEASCONSIGNOR>No</ISDISPATCHSAMEASCONSIGNOR>\n";
						    $xml_output .= "<CHANGEVCHMODE>No</CHANGEVCHMODE>\n";
						    $xml_output .= "<ALTERID></ALTERID>\n";
						    $xml_output .= "<MASTERID></MASTERID>\n";
						    $xml_output .= "<VOUCHERKEY></VOUCHERKEY>\n";
						    $xml_output .= "<EWAYBILLDETAILS.LIST/>\n";
						    $xml_output .= "<EXCLUDEDTAXATIONS.LIST/>\n";
						    $xml_output .= "<OLDAUDITENTRIES.LIST/>\n";
						    $xml_output .= "<ACCOUNTAUDITENTRIES.LIST/>\n";
						    $xml_output .= "<AUDITENTRIES.LIST/>\n";
						    $xml_output .= "<DUTYHEADDETAILS.LIST/>\n";

						    if(!empty($invoice_details))
						    {
						    	foreach ($invoice_details as $key => $inv_val) {
						    		$pdt_name = !empty($inv_val['product_name'])?$inv_val['product_name']:'';
                                    $pdt_unit = !empty($inv_val['product_unit'])?$inv_val['product_unit']:'';
                                    $pdt_mrp  = !empty($inv_val['product_mrp'])?$inv_val['product_mrp']:'';
                                    $pdt_gst  = !empty($inv_val['product_gst'])?$inv_val['product_gst']:'';
                                    $pdt_pri = !empty($inv_val['product_price'])?$inv_val['product_price']:'';
                                    $tot_pri = !empty($inv_val['total_price'])?$inv_val['total_price']:'';
                                    $ord_qty = !empty($inv_val['order_qty'])?$inv_val['order_qty']:'';
                                    $rec_qty = !empty($inv_val['receive_qty'])?$inv_val['receive_qty']:'';

                                    $xml_output .= "<INVENTORYENTRIES.LIST>\n";
                                    $xml_output .= "<STOCKITEMNAME>".$pdt_name."</STOCKITEMNAME>\n";
								    $xml_output .= "<ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>\n";
								    $xml_output .= "<ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>\n";
								    $xml_output .= "<ISAUTONEGATE>No</ISAUTONEGATE>\n";
								    $xml_output .= "<ISCUSTOMSCLEARANCE>No</ISCUSTOMSCLEARANCE>\n";
								    $xml_output .= "<ISTRACKCOMPONENT>No</ISTRACKCOMPONENT>\n";
								    $xml_output .= "<ISTRACKPRODUCTION>No</ISTRACKPRODUCTION>\n";
								    $xml_output .= "<ISPRIMARYITEM>No</ISPRIMARYITEM>\n";
								    $xml_output .= "<ISSCRAP>No</ISSCRAP>\n";
								    $xml_output .= "<RATE>".number_format((float)$pdt_pri, 2, '.', '')."/".$pdt_unit."</RATE>\n";
								    $xml_output .= "<AMOUNT>".number_format((float)$tot_pri, 2, '.', '')."</AMOUNT>\n";
								    $xml_output .= "<ACTUALQTY>".$ord_qty." ".$pdt_unit."</ACTUALQTY>\n";
								    $xml_output .= "<BILLEDQTY>".$rec_qty." ".$pdt_unit."</BILLEDQTY>\n";
								    $xml_output .= "<INCLVATRATE>".number_format((float)$pdt_mrp, 2, '.', '')."/".$pdt_unit."</INCLVATRATE>\n";
								    $xml_output .= "<BATCHALLOCATIONS.LIST>\n";
								    $xml_output .= "<GODOWNNAME>Main Location</GODOWNNAME>\n";
								    $xml_output .= "<BATCHNAME>Primary Batch</BATCHNAME>\n";
								    $xml_output .= "<INDENTNO/>\n";
								    $xml_output .= "<ORDERNO/>\n";
								    $xml_output .= "<TRACKINGNUMBER/>\n";
								    $xml_output .= "<DYNAMICCSTISCLEARED>No</DYNAMICCSTISCLEARED>\n";
								    $xml_output .= "<AMOUNT>".number_format((float)$tot_pri, 2, '.', '')."</AMOUNT>\n";
								    $xml_output .= "<ACTUALQTY>".$ord_qty." ".$pdt_unit."</ACTUALQTY>\n";
								    $xml_output .= "<BILLEDQTY>".$rec_qty." ".$pdt_unit."</BILLEDQTY>\n";
								    $xml_output .= "<INCLVATRATE>".number_format((float)$pdt_mrp, 2, '.', '')."/".$pdt_unit."</INCLVATRATE>\n";
								    $xml_output .= "<ADDITIONALDETAILS.LIST/>\n";
								    $xml_output .= "<VOUCHERCOMPONENTLIST.LIST/>\n";
								    $xml_output .= "</BATCHALLOCATIONS.LIST>\n";
								    $xml_output .= "<ACCOUNTINGALLOCATIONS.LIST>\n";
								    $xml_output .= "<OLDAUDITENTRYIDS.LIST TYPE='Number'>\n";
								    $xml_output .= "<LEDGERNAME>Local GST Sales</LEDGERNAME>\n";
								    $xml_output .= "</OLDAUDITENTRYIDS.LIST>\n";
								    $xml_output .= "<GSTCLASS/>\n";   
								    $xml_output .= "<ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>\n";
								    $xml_output .= "<LEDGERFROMITEM>No</LEDGERFROMITEM>\n";
								    $xml_output .= "<REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>\n";
								    $xml_output .= "<ISPARTYLEDGER>No</ISPARTYLEDGER>\n";
								    $xml_output .= "<ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>\n";
								    $xml_output .= "<ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>\n";
								    $xml_output .= "<ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>\n";
								    $xml_output .= "<AMOUNT>".number_format((float)$tot_pri, 2, '.', '')."</AMOUNT>\n";
								    $xml_output .= "<SERVICETAXDETAILS.LIST/>\n";
								    $xml_output .= "<BANKALLOCATIONS.LIST/>\n";
								    $xml_output .= "<BILLALLOCATIONS.LIST/>\n";
								    $xml_output .= "<INTERESTCOLLECTION.LIST/>\n";
								    $xml_output .= "<OLDAUDITENTRIES.LIST/>\n";
								    $xml_output .= "<ACCOUNTAUDITENTRIES.LIST/>\n";
								    $xml_output .= "<AUDITENTRIES.LIST/>\n";
								    $xml_output .= "<INPUTCRALLOCS.LIST/>\n";
								    $xml_output .= "<DUTYHEADDETAILS.LIST/>\n";
								    $xml_output .= "<EXCISEDUTYHEADDETAILS.LIST/>\n";
								    $xml_output .= "<RATEDETAILS.LIST/>\n";
								    $xml_output .= "<SUMMARYALLOCS.LIST/>\n";
								    $xml_output .= "<STPYMTDETAILS.LIST/>\n";
								    $xml_output .= "<EXCISEPAYMENTALLOCATIONS.LIST/>\n";
								    $xml_output .= "<TAXBILLALLOCATIONS.LIST/>\n";
								    $xml_output .= "<TAXOBJECTALLOCATIONS.LIST/>\n";
								    $xml_output .= "<TDSEXPENSEALLOCATIONS.LIST/>\n";
								    $xml_output .= "<VATSTATUTORYDETAILS.LIST/>\n";
								    $xml_output .= "<COSTTRACKALLOCATIONS.LIST/>\n";
								    $xml_output .= "<REFVOUCHERDETAILS.LIST/>\n";
								    $xml_output .= "<INVOICEWISEDETAILS.LIST/>\n";
								    $xml_output .= "<VATITCDETAILS.LIST/>\n";
								    $xml_output .= "<ADVANCETAXDETAILS.LIST/>\n";
								    $xml_output .= "</ACCOUNTINGALLOCATIONS.LIST>\n";
								    $xml_output .= "<DUTYHEADDETAILS.LIST/>\n";
								    $xml_output .= "<SUPPLEMENTARYDUTYHEADDETAILS.LIST/>\n";
								    $xml_output .= "<TAXOBJECTALLOCATIONS.LIST/>\n";
								    $xml_output .= "<REFVOUCHERDETAILS.LIST/>\n";
								    $xml_output .= "<EXCISEALLOCATIONS.LIST/>\n";
								    $xml_output .= "<EXPENSEALLOCATIONS.LIST/>\n";
                                    $xml_output .= "</INVENTORYENTRIES.LIST>\n";
						    	}
						    }

						    $xml_output .= "<SUPPLEMENTARYDUTYHEADDETAILS.LIST/>\n";
						    $xml_output .= "<INVOICEDELNOTES.LIST/>\n";
						    $xml_output .= "<INVOICEORDERLIST.LIST/>\n";
						    $xml_output .= "<INVOICEINDENTLIST.LIST/>\n";
						    $xml_output .= "<ATTENDANCEENTRIES.LIST/>\n";
						    $xml_output .= "<ORIGINVOICEDETAILS.LIST/>\n";
						    $xml_output .= "<INVOICEEXPORTLIST.LIST/>\n";

						    // Start Invoice Details
						    $xml_output .= "<LEDGERENTRIES.LIST>\n";
						    $xml_output .= "<OLDAUDITENTRYIDS.LIST TYPE='Number'>\n";
						    $xml_output .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
						    $xml_output .= "</OLDAUDITENTRYIDS.LIST>\n";
						    $xml_output .= "<LEDGERNAME>".$store_name."</LEDGERNAME>\n";
						    $xml_output .= "<GSTCLASS/>\n";
						    $xml_output .= "<ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>\n";
						    $xml_output .= "<LEDGERFROMITEM>No</LEDGERFROMITEM>\n";
						    $xml_output .= "<REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>\n";
						    $xml_output .= "<ISPARTYLEDGER>Yes</ISPARTYLEDGER>\n";
						    $xml_output .= "<ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>\n";
						    $xml_output .= "<ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>\n";
						    $xml_output .= "<ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>\n";
						    $xml_output .= "<AMOUNT>".number_format((float)$net_val, 2, '.', '')."</AMOUNT>\n";
						    $xml_output .= "<SERVICETAXDETAILS.LIST/>\n";
						    $xml_output .= "<BANKALLOCATIONS.LIST/>\n";
						    $xml_output .= "<BILLALLOCATIONS.LIST>\n";
						    $xml_output .= "<NAME>1</NAME>\n";
						    $xml_output .= "<BILLTYPE>New Ref</BILLTYPE>\n";
						    $xml_output .= "<TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>\n";
						    $xml_output .= "<AMOUNT>".number_format((float)$net_val, 2, '.', '')."</AMOUNT>\n";
						    $xml_output .= "<INTERESTCOLLECTION.LIST/>\n";
						    $xml_output .= "<STBILLCATEGORIES.LIST/>\n";
						    $xml_output .= "</BILLALLOCATIONS.LIST>\n";
						    $xml_output .= "<INTERESTCOLLECTION.LIST/>\n";
						    $xml_output .= "<OLDAUDITENTRIES.LIST/>\n";
						    $xml_output .= "<ACCOUNTAUDITENTRIES.LIST/>\n";
						    $xml_output .= "<AUDITENTRIES.LIST/>\n";
						    $xml_output .= "<INPUTCRALLOCS.LIST/>\n";
						    $xml_output .= "<DUTYHEADDETAILS.LIST/>\n";
						    $xml_output .= "<EXCISEDUTYHEADDETAILS.LIST/>\n";
						    $xml_output .= "<RATEDETAILS.LIST/>\n";
						    $xml_output .= "<SUMMARYALLOCS.LIST/>\n";
						    $xml_output .= "<STPYMTDETAILS.LIST/>\n";
						    $xml_output .= "<EXCISEPAYMENTALLOCATIONS.LIST/>\n";
						    $xml_output .= "<TAXBILLALLOCATIONS.LIST/>\n";
						    $xml_output .= "<TAXOBJECTALLOCATIONS.LIST/>\n";
						    $xml_output .= "<TDSEXPENSEALLOCATIONS.LIST/>\n";
						    $xml_output .= "<VATSTATUTORYDETAILS.LIST/>\n";
						    $xml_output .= "<COSTTRACKALLOCATIONS.LIST/>\n";
						    $xml_output .= "<REFVOUCHERDETAILS.LIST/>\n";
						    $xml_output .= "<INVOICEWISEDETAILS.LIST/>\n";
						    $xml_output .= "<VATITCDETAILS.LIST/>\n";
						    $xml_output .= "<ADVANCETAXDETAILS.LIST/>\n";
						    $xml_output .= "</LEDGERENTRIES.LIST>\n";

						    if(!empty($tax_details))
						    {
						    	foreach ($tax_details as $key => $tax_val) {
						    		$hsn_code  = !empty($tax_val['hsn_code'])?$tax_val['hsn_code']:'0';
                                    $gst_val   = !empty($tax_val['gst_val'])?$tax_val['gst_val']:'0';
                                    $gst_value = !empty($tax_val['gst_value'])?$tax_val['gst_value']:'0';
                                    $price_val = !empty($tax_val['price_value'])?$tax_val['price_value']:'0';

                                    // GSTIN Details Start
                                    if($dis_state == $store_state_id)
                                    {
                                    	$state_value = $gst_value / 2;
				                        $gst_calc    = $gst_val / 2;

                                    	// SGST
                                    	$xml_output .= "<LEDGERENTRIES.LIST>\n";
									    $xml_output .= "<OLDAUDITENTRYIDS.LIST TYPE='Number'>\n";
									    $xml_output .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
									    $xml_output .= "</OLDAUDITENTRYIDS.LIST>\n";
									    $xml_output .= "<BASICRATEOFINVOICETAX.LIST TYPE='Number'>\n";
									    $xml_output .= "<BASICRATEOFINVOICETAX>".$gst_calc."</BASICRATEOFINVOICETAX>\n";
									    $xml_output .= "</BASICRATEOFINVOICETAX.LIST>\n";
									    $xml_output .= "<ROUNDTYPE/>\n";
									    $xml_output .= "<LEDGERNAME>SGST @ ".$gst_calc."%</LEDGERNAME>\n";
									    $xml_output .= "<GSTCLASS/>\n";
									    $xml_output .= "<ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>\n";
									    $xml_output .= "<LEDGERFROMITEM>No</LEDGERFROMITEM>\n";
									    $xml_output .= "<REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>\n";
									    $xml_output .= "<ISPARTYLEDGER>No</ISPARTYLEDGER>\n";
									    $xml_output .= "<ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>\n";
									    $xml_output .= "<ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>\n";
									    $xml_output .= "<ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>\n";
									    $xml_output .= "<AMOUNT>".number_format((float)$state_value, 2, '.', '')."</AMOUNT>\n";
									    $xml_output .= "<VATEXPAMOUNT>".number_format((float)$state_value, 2, '.', '')."</VATEXPAMOUNT>\n";
									    $xml_output .= "<SERVICETAXDETAILS.LIST/>\n";
									    $xml_output .= "<BANKALLOCATIONS.LIST/>\n";
									    $xml_output .= "<BILLALLOCATIONS.LIST/>\n";
									    $xml_output .= "<INTERESTCOLLECTION.LIST/>\n";
									    $xml_output .= "<OLDAUDITENTRIES.LIST/>\n";
									    $xml_output .= "<ACCOUNTAUDITENTRIES.LIST/>\n";
									    $xml_output .= "<AUDITENTRIES.LIST/>\n";
									    $xml_output .= "<INPUTCRALLOCS.LIST/>\n";
									    $xml_output .= "<DUTYHEADDETAILS.LIST/>\n";
									    $xml_output .= "<EXCISEDUTYHEADDETAILS.LIST/>\n";
									    $xml_output .= "<RATEDETAILS.LIST/>\n";
									    $xml_output .= "<SUMMARYALLOCS.LIST/>\n";
									    $xml_output .= "<STPYMTDETAILS.LIST/>\n";
									    $xml_output .= "<EXCISEPAYMENTALLOCATIONS.LIST/>\n";
									    $xml_output .= "<TAXBILLALLOCATIONS.LIST/>\n";
									    $xml_output .= "<TAXOBJECTALLOCATIONS.LIST/>\n";
									    $xml_output .= "<TDSEXPENSEALLOCATIONS.LIST/>\n";
									    $xml_output .= "<VATSTATUTORYDETAILS.LIST/>\n";
									    $xml_output .= "<COSTTRACKALLOCATIONS.LIST/>\n";
									    $xml_output .= "<REFVOUCHERDETAILS.LIST/>\n";
									    $xml_output .= "<INVOICEWISEDETAILS.LIST/>\n";
									    $xml_output .= "<VATITCDETAILS.LIST/>\n";
									    $xml_output .= "<ADVANCETAXDETAILS.LIST/>\n";
									    $xml_output .= "</LEDGERENTRIES.LIST>\n";

									    // CGST
									    $xml_output .= "<LEDGERENTRIES.LIST>\n";
									    $xml_output .= "<OLDAUDITENTRYIDS.LIST TYPE='Number'>\n";
									    $xml_output .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
									    $xml_output .= "</OLDAUDITENTRYIDS.LIST>\n";
									    $xml_output .= "<BASICRATEOFINVOICETAX.LIST TYPE='Number'>\n";
									    $xml_output .= "<BASICRATEOFINVOICETAX>".$gst_calc."</BASICRATEOFINVOICETAX>\n";
									    $xml_output .= "</BASICRATEOFINVOICETAX.LIST>\n";
									    $xml_output .= "<ROUNDTYPE/>\n";
									    $xml_output .= "<LEDGERNAME>CGST @ ".$gst_calc."%</LEDGERNAME>\n";
									    $xml_output .= "<GSTCLASS/>\n";
									    $xml_output .= "<ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>\n";
									    $xml_output .= "<LEDGERFROMITEM>No</LEDGERFROMITEM>\n";
									    $xml_output .= "<REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>\n";
									    $xml_output .= "<ISPARTYLEDGER>No</ISPARTYLEDGER>\n";
									    $xml_output .= "<ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>\n";
									    $xml_output .= "<ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>\n";
									    $xml_output .= "<ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>\n";
									    $xml_output .= "<AMOUNT>".number_format((float)$state_value, 2, '.', '')."</AMOUNT>\n";
									    $xml_output .= "<VATEXPAMOUNT>".number_format((float)$state_value, 2, '.', '')."</VATEXPAMOUNT>\n";
									    $xml_output .= "<SERVICETAXDETAILS.LIST/>\n";
									    $xml_output .= "<BANKALLOCATIONS.LIST/>\n";
									    $xml_output .= "<BILLALLOCATIONS.LIST/>\n";
									    $xml_output .= "<INTERESTCOLLECTION.LIST/>\n";
									    $xml_output .= "<OLDAUDITENTRIES.LIST/>\n";
									    $xml_output .= "<ACCOUNTAUDITENTRIES.LIST/>\n";
									    $xml_output .= "<AUDITENTRIES.LIST/>\n";
									    $xml_output .= "<INPUTCRALLOCS.LIST/>\n";
									    $xml_output .= "<DUTYHEADDETAILS.LIST/>\n";
									    $xml_output .= "<EXCISEDUTYHEADDETAILS.LIST/>\n";
									    $xml_output .= "<RATEDETAILS.LIST/>\n";
									    $xml_output .= "<SUMMARYALLOCS.LIST/>\n";
									    $xml_output .= "<STPYMTDETAILS.LIST/>\n";
									    $xml_output .= "<EXCISEPAYMENTALLOCATIONS.LIST/>\n";
									    $xml_output .= "<TAXBILLALLOCATIONS.LIST/>\n";
									    $xml_output .= "<TAXOBJECTALLOCATIONS.LIST/>\n";
									    $xml_output .= "<TDSEXPENSEALLOCATIONS.LIST/>\n";
									    $xml_output .= "<VATSTATUTORYDETAILS.LIST/>\n";
									    $xml_output .= "<COSTTRACKALLOCATIONS.LIST/>\n";
									    $xml_output .= "<REFVOUCHERDETAILS.LIST/>\n";
									    $xml_output .= "<INVOICEWISEDETAILS.LIST/>\n";
									    $xml_output .= "<VATITCDETAILS.LIST/>\n";
									    $xml_output .= "<ADVANCETAXDETAILS.LIST/>\n";
									    $xml_output .= "</LEDGERENTRIES.LIST>\n";
                                    }
                                    else
                                    {
                                    	$xml_output .= "<LEDGERENTRIES.LIST>\n";
									    $xml_output .= "<OLDAUDITENTRYIDS.LIST TYPE='Number'>\n";
									    $xml_output .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
									    $xml_output .= "</OLDAUDITENTRYIDS.LIST>\n";
									    $xml_output .= "<BASICRATEOFINVOICETAX.LIST TYPE='Number'>\n";
									    $xml_output .= "<BASICRATEOFINVOICETAX>".$gst_val."</BASICRATEOFINVOICETAX>\n";
									    $xml_output .= "</BASICRATEOFINVOICETAX.LIST>\n";
									    $xml_output .= "<ROUNDTYPE/>\n";
									    $xml_output .= "<LEDGERNAME>IGST @ ".$gst_val."%</LEDGERNAME>\n";
									    $xml_output .= "<GSTCLASS/>\n";
									    $xml_output .= "<ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>\n";
									    $xml_output .= "<LEDGERFROMITEM>No</LEDGERFROMITEM>\n";
									    $xml_output .= "<REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>\n";
									    $xml_output .= "<ISPARTYLEDGER>No</ISPARTYLEDGER>\n";
									    $xml_output .= "<ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>\n";
									    $xml_output .= "<ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>\n";
									    $xml_output .= "<ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>\n";
									    $xml_output .= "<AMOUNT>".number_format((float)$gst_value, 2, '.', '')."</AMOUNT>\n";
									    $xml_output .= "<VATEXPAMOUNT>".number_format((float)$gst_value, 2, '.', '')."</VATEXPAMOUNT>\n";
									    $xml_output .= "<SERVICETAXDETAILS.LIST/>\n";
									    $xml_output .= "<BANKALLOCATIONS.LIST/>\n";
									    $xml_output .= "<BILLALLOCATIONS.LIST/>\n";
									    $xml_output .= "<INTERESTCOLLECTION.LIST/>\n";
									    $xml_output .= "<OLDAUDITENTRIES.LIST/>\n";
									    $xml_output .= "<ACCOUNTAUDITENTRIES.LIST/>\n";
									    $xml_output .= "<AUDITENTRIES.LIST/>\n";
									    $xml_output .= "<INPUTCRALLOCS.LIST/>\n";
									    $xml_output .= "<DUTYHEADDETAILS.LIST/>\n";
									    $xml_output .= "<EXCISEDUTYHEADDETAILS.LIST/>\n";
									    $xml_output .= "<RATEDETAILS.LIST/>\n";
									    $xml_output .= "<SUMMARYALLOCS.LIST/>\n";
									    $xml_output .= "<STPYMTDETAILS.LIST/>\n";
									    $xml_output .= "<EXCISEPAYMENTALLOCATIONS.LIST/>\n";
									    $xml_output .= "<TAXBILLALLOCATIONS.LIST/>\n";
									    $xml_output .= "<TAXOBJECTALLOCATIONS.LIST/>\n";
									    $xml_output .= "<TDSEXPENSEALLOCATIONS.LIST/>\n";
									    $xml_output .= "<VATSTATUTORYDETAILS.LIST/>\n";
									    $xml_output .= "<COSTTRACKALLOCATIONS.LIST/>\n";
									    $xml_output .= "<REFVOUCHERDETAILS.LIST/>\n";
									    $xml_output .= "<INVOICEWISEDETAILS.LIST/>\n";
									    $xml_output .= "<VATITCDETAILS.LIST/>\n";
									    $xml_output .= "<ADVANCETAXDETAILS.LIST/>\n";
									    $xml_output .= "</LEDGERENTRIES.LIST>\n";
                                    }
						    	}
						    }

						    // Round off Start
						    $xml_output .= "<LEDGERENTRIES.LIST>\n";
						    $xml_output .= "<OLDAUDITENTRYIDS.LIST TYPE='Number'>\n";
						    $xml_output .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
						    $xml_output .= "</OLDAUDITENTRYIDS.LIST>\n";
						    $xml_output .= "<ROUNDTYPE>Normal Rounding</ROUNDTYPE>\n";
						    $xml_output .= "<LEDGERNAME>Round Off</LEDGERNAME>\n";
						    $xml_output .= "<GSTCLASS/>\n";
						    $xml_output .= "<ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>\n";
						    $xml_output .= "<LEDGERFROMITEM>No</LEDGERFROMITEM>\n";
						    $xml_output .= "<REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>\n";
						    $xml_output .= "<ISPARTYLEDGER>No</ISPARTYLEDGER>\n";
						    $xml_output .= "<ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>\n";
						    $xml_output .= "<ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>\n";
						    $xml_output .= "<ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>\n";
						    $xml_output .= "<ROUNDLIMIT>1</ROUNDLIMIT>\n";
						    $xml_output .= "<AMOUNT>".number_format((float)$round_val, 2, '.', '')."</AMOUNT>\n";
						    $xml_output .= "<VATEXPAMOUNT>".number_format((float)$round_val, 2, '.', '')."</VATEXPAMOUNT>\n";
						    $xml_output .= "<SERVICETAXDETAILS.LIST/>\n";
						    $xml_output .= "<BANKALLOCATIONS.LIST/>\n";
						    $xml_output .= "<BILLALLOCATIONS.LIST/>\n";
						    $xml_output .= "<INTERESTCOLLECTION.LIST/>\n";
						    $xml_output .= "<OLDAUDITENTRIES.LIST/>\n";
						    $xml_output .= "<ACCOUNTAUDITENTRIES.LIST/>\n";
						    $xml_output .= "<AUDITENTRIES.LIST/>\n";
						    $xml_output .= "<INPUTCRALLOCS.LIST/>\n";
						    $xml_output .= "<DUTYHEADDETAILS.LIST/>\n";
						    $xml_output .= "<EXCISEDUTYHEADDETAILS.LIST/>\n";
						    $xml_output .= "<RATEDETAILS.LIST/>\n";
						    $xml_output .= "<SUMMARYALLOCS.LIST/>\n";
						    $xml_output .= "<STPYMTDETAILS.LIST/>\n";
						    $xml_output .= "<EXCISEPAYMENTALLOCATIONS.LIST/>\n";
						    $xml_output .= "<TAXBILLALLOCATIONS.LIST/>\n";
						    $xml_output .= "<TAXOBJECTALLOCATIONS.LIST/>\n";
						    $xml_output .= "<TDSEXPENSEALLOCATIONS.LIST/>\n";
						    $xml_output .= "<VATSTATUTORYDETAILS.LIST/>\n";
						    $xml_output .= "<COSTTRACKALLOCATIONS.LIST/>\n";
						    $xml_output .= "<REFVOUCHERDETAILS.LIST/>\n";
						    $xml_output .= "<INVOICEWISEDETAILS.LIST/>\n";
						    $xml_output .= "<VATITCDETAILS.LIST/>\n";
						    $xml_output .= "<ADVANCETAXDETAILS.LIST/>\n";
						    $xml_output .= "</LEDGERENTRIES.LIST>\n";
						    $xml_output .= "<PAYROLLMODEOFPAYMENT.LIST/>\n";
						    $xml_output .= "<ATTDRECORDS.LIST/>\n";
						    $xml_output .= "<GSTEWAYCONSIGNORADDRESS.LIST/>\n";
						    $xml_output .= "<GSTEWAYCONSIGNEEADDRESS.LIST/>\n";
						    $xml_output .= "<TEMPGSTRATEDETAILS.LIST/>\n";

						    $xml_output .= "</VOUCHER>\n";
						    $xml_output .= "</TALLYMESSAGE>\n";
					    }
				    }

				    $xml_output .= "</REQUESTDATA>\n";
					$xml_output .= "</IMPORTDATA>\n";
					$xml_output .= "</BODY>\n";
					$xml_output .= "</ENVELOPE>\n";

        			echo $xml_output;
        		}
        	}

        	if($param1 == 'gst_report')
        	{
        		$start_date  = $param2; 
        		$end_date    = $param3;

        		$sales_whr = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'distributor_id' => $this->session->userdata('id'),
			    	'method'         => '_outletGstExport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/distributor_report',$sales_whr);

			    header('Content-Type: text/csv; charset=utf-8');  
                header('Content-Disposition: attachment; filename=outlet_tax_report('.$start_date.' to '.$end_date.').csv');  
                $output = fopen("php://output", "w");   
                fputcsv($output, array('Store Name' , 'GSTIN/UIN of Recipient', 'Invoice Number', 'Invoice Date', 'Invoice Value', 'Place Of Supply', 'Reverse Charge', 'Applicable % of Tax Rate', 'Invoice Type', 'E-Commerce GSTIN', 'Rate', 'Taxable Value', 'Cess Amount'));

                if($data_list['status'] == 1)
                {
                	$data_val = $data_list['data']; 

                	foreach ($data_val as $key => $val) {
                		$company_name = !empty($val['company_name'])?$val['company_name']:'';
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

	                    $num = array(
	                    	$company_name,
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
                }

                fclose($output);
                exit();
        	}

        	if($param1 == 'new_export')
        	{
        		$start_date  = $param2; 
        		$end_date    = $param3;

        		$sales_whr = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'distributor_id' => $this->session->userdata('id'),
			    	'method'         => '_overallSalesReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/distributor_report',$sales_whr);

			    header('Content-Type: text/csv; charset=utf-8');  
                header('Content-Disposition: attachment; filename=new_tally_report('.$start_date.' to '.$end_date.').csv');  

                $output = fopen("php://output", "w");   
                fputcsv($output, array('InvNo', 'Inv_Dt', 'Pty_Name', 'Vch_Type', 'GSTIN', 'StateOfSupply', 'Product_Name', 'HSNCode', 'Qty', 'UOM', 'TaxPer', 'TaxableAmt', 'IGSTAmt', 'SGSTAmt', 'CGSTAmt', 'Cess', 'OtherAmt', 'Net_Amt', 'Narration ', 'Discount', 'Sales Ledger', 'PO No', 'PO Date', 'DC No', 'DC Date', 'Bill of Supply'));

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
                		$dis_state_id = !empty($val['dis_state_id'])?$val['dis_state_id']:'';
	                    $invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
	                    $order_no     = !empty($val['order_no'])?$val['order_no']:'';
	                    $emp_name     = !empty($val['emp_name'])?$val['emp_name']:'';
	                    $str_name     = !empty($val['str_name'])?$val['str_name']:'';
	                    $mobile       = !empty($val['mobile'])?$val['mobile']:'';
	                    $gst_no       = !empty($val['gst_no'])?$val['gst_no']:'';
	                    $address      = !empty($val['address'])?$val['address']:'';
	                    $state_id     = !empty($val['state_id'])?$val['state_id']:'';
	                    $state_name   = !empty($val['state_name'])?$val['state_name']:'';
	                    $gst_code     = !empty($val['gst_code'])?$val['gst_code']:'';
	                    $due_days     = !empty($val['due_days'])?$val['due_days']:'';
	                    $order_date   = !empty($val['order_date'])?$val['order_date']:'';
	                    $invoice_date = !empty($val['invoice_date'])?$val['invoice_date']:'';
	                    $discount     = !empty($val['discount'])?$val['discount']:'0';
	                    $description  = !empty($val['description'])?$val['description']:'';
	                    $hsn_code     = !empty($val['hsn_code'])?$val['hsn_code']:'';
			            $gst_value    = !empty($val['gst_val'])?$val['gst_val']:'0';
			            $pdt_price    = !empty($val['price'])?$val['price']:'0';
			            $pdt_qty      = !empty($val['order_qty'])?$val['order_qty']:'0';

			            $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
                        $price_val  = $pdt_price - $gst_data;
                        $pdt_gst    = $pdt_qty * $gst_data;
                        $TaxableAmt = $pdt_qty * $price_val;
                        $tot_price  = $pdt_qty * $pdt_price;
                        // $pdt_value  = round($tot_price);
                        $total_dis  = $tot_price * $discount / 100;
                        $total_val  = $tot_price - $total_dis;

                        if($dis_state_id == $state_id)
                        {
                        	$gst_res  = $pdt_gst / 2;
                        	$sgst_val = number_format((float)$gst_res, 2, '.', '');
                        	$cgst_val = number_format((float)$gst_res, 2, '.', '');
                        	$igst_val = '0';
                        	$vch_type = 'Local Sales';
                        	$sale_led = 'Sales@'.$gst_value.'%';

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
                        	$sale_led = 'Sales@'.$gst_value.'%';

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
			            	$str_name,
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
			            	'As pr Invoice No '.$invoice_no,
			            	number_format((float)$total_dis, 2, '.', ''),
			            	$sale_led,
			            	$order_no,
			            	date('d-m-Y', strtotime($order_date)),
			            	'',
			            	'',
			            	$address
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
		            	'',
		            	'',
		            	number_format((float)$totNetVal, 2, '.', ''),
		            	'',
		            	number_format((float)$totDisVal, 2, '.', ''),
		            	'',
		            	'',
		            	'',
		            	'',
		            	'',
		            	'',
		            );

		            fputcsv($output, $num);  
                }

                fclose($output);
                exit();
        	}

        	if($param1 == 'cancel_print')
        	{
        		$start_date = $param2; 
        		$end_date   = $param3;

        		$outlet_whr = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
					'end_date'       => date('Y-m-d', strtotime($end_date)),
					'distributor_id' => $this->session->userdata('id'),
			    	'view_type'      => '2',
			    	'method'         => '_getOutletOverallReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/outlet_report',$outlet_whr);

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=cancel_invoice_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('InvNo', 'Inv_Dt', 'Pty_Name', 'Vch_Type', 'GSTIN', 'StateOfSupply', 'Product_Name', 'HSNCode', 'Qty', 'UOM', 'TaxPer', 'TaxableAmt', 'IGSTAmt', 'SGSTAmt', 'CGSTAmt', 'Cess', 'OtherAmt', 'Discount', 'Net_Amt', 'Narration', 'Group', 'Address', 'Country', 'St. Group', 'Salees Ledger'));

		    	if($data_list['status'] == 1)
		    	{
		    		$data_val = $data_list['data'];	

		    		foreach ($data_val as $key => $val) {

		    			$dis_username = !empty($val['dis_username'])?$val['dis_username']:'';
		    			$dis_state_id = !empty($val['dis_state_id'])?$val['dis_state_id']:'';
		    			$invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
			            $order_no     = !empty($val['order_no'])?$val['order_no']:'';
			            $emp_name     = !empty($val['emp_name'])?$val['emp_name']:'';
			            $str_name     = !empty($val['str_name'])?$val['str_name']:'';
			            $mobile       = !empty($val['mobile'])?$val['mobile']:'';
			            $gst_no       = !empty($val['gst_no'])?$val['gst_no']:'';
			            $address      = !empty($val['address'])?$val['address']:'';
			            $state_id     = !empty($val['state_id'])?$val['state_id']:'';
			            $state_name   = !empty($val['state_name'])?$val['state_name']:'';
			            $gst_code     = !empty($val['gst_code'])?$val['gst_code']:'';
			            $due_days     = !empty($val['due_days'])?$val['due_days']:'';
			            $invoice_date = !empty($val['invoice_date'])?$val['invoice_date']:'';
			            $discount     = !empty($val['discount'])?$val['discount']:'0';
			            $description  = !empty($val['description'])?$val['description']:'';
			            $hsn_code     = !empty($val['hsn_code'])?$val['hsn_code']:'';
			            $gst_value    = !empty($val['gst_val'])?$val['gst_val']:'0';
			            $pdt_price    = !empty($val['price'])?$val['price']:'0';
			            $pdt_qty      = !empty($val['order_qty'])?$val['order_qty']:'0';

			            $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
                        $price_val  = $pdt_price - $gst_data;
                        $pdt_gst    = $pdt_qty * $gst_data;
                        $TaxableAmt = $pdt_qty * $price_val;
                        $tot_price  = $pdt_qty * $pdt_price;
                        $pdt_value  = round($tot_price);

                        if($discount != 0)
                        {
                        	$total_dis  = $pdt_value * $discount / 100;
                        }
                        else
                        {
                        	$total_dis = 0;	
                        }

                        $total_val  = $pdt_value - $total_dis;

                        if($dis_state_id == $state_id)
                        {
                        	$gst_res  = $pdt_gst / 2;
                        	$sgst_val = number_format((float)$gst_res, 2, '.', '');
                        	$cgst_val = number_format((float)$gst_res, 2, '.', '');
                        	$igst_val = '0';
                        	$vch_type = 'Local Sales';
                        }
                        else
                        {
                        	$sgst_val = '0';
                        	$cgst_val = '0';
                        	$igst_val = number_format((float)$pdt_gst, 2, '.', '');
                        	$vch_type = 'Inter Sales';
                        }

			            $num = array(
			            	// $dis_username,
			            	$invoice_no,
			            	date('d-m-Y', strtotime($invoice_date)),
			            	$str_name,
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
				$data['page_temp']    = $this->load->view('distributors/report/sales_report',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "sales_report";
				$this->bassthaya->load_distributors_form_template($data);
        	}
		}

		public function outlet_outstanding($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
    	{
    		if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method =='_getOutletOutstandingData')
        	{
        		$start_date  = $this->input->post('start_date');
			    $end_date    = $this->input->post('end_date');

			    $sales_whr = array(
			    	'start_date'     => $start_date,
			    	'end_date'       => $end_date,
			    	'distributor_id' => $this->session->userdata('id'),
			    	'financial_year' => $this->session->userdata('active_year'),
			    	'method'         => '_getOutletOutstandingData',
			    );

            	$data_list = avul_call(API_URL.'report/api/outlet_report',$sales_whr);

            	if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'];
		    		$num      = 1;

		    		foreach ($data_val as $key => $value) {
						$invoice_date = !empty($value['invoice_date'])?$value['invoice_date']:'';
						$invoice_no   = !empty($value['invoice_no'])?$value['invoice_no']:'';
						$outlet_name  = !empty($value['outlet_name'])?$value['outlet_name']:'';
						$opening_bal  = !empty($value['opening_bal'])?$value['opening_bal']:'';
						$pending_amt  = !empty($value['pending_amt'])?$value['pending_amt']:'';
						$final_amt    = !empty($value['final_amt'])?$value['final_amt']:'';
						$age_of_bill  = !empty($value['age_of_bill'])?$value['age_of_bill']:'';

		    			$html .= '
			            	<tr>
                                <td>'.$num.'</td>
                                <td>'.$invoice_no.'</td>
                                <td>'.$invoice_date.'</td>
                                <td>'.mb_strimwidth($outlet_name, 0, 35, '...').'</td>
                                <td>'.$final_amt.'</td>
                                <td>'.$age_of_bill.'</td>
                            </tr>
			            ';

			            $num++;
		    		}

		    		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/outlet_outstanding/excel_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		    		$pdf_btn   = '<a class="btn btn-danger " target="_blank" href="'.BASE_URL.'index.php/distributors/report/outlet_outstanding/pdf_print/'.$start_date.'"'.$end_date.'#fff;"><i class="ft-file-text"></i> PDF</a>';

		    		$response['status']    = 1;
			        $response['message']   = $data_list['message']; 
			        $response['data']      = $html;
			        $response['new_btn']   = $excel_btn;
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

        	if($param1 == 'excel_print')
        	{
        		$start_date = $param2; 
        		$end_date   = $param3;
        		$zone_id    = $param4;
        		$status_val = $param5; 

        		$outlet_whr  = array(
        			'start_date'     => date('Y-m-d', strtotime($start_date)),
        			'end_date'       => date('Y-m-d', strtotime($end_date)),
        			'distributor_id' => $this->session->userdata('id'),
			    	'financial_year' => $this->session->userdata('active_year'),
			    	'method'         => '_getOutletOutstandingData',
            	);

            	$data_list = avul_call(API_URL.'report/api/outlet_report',$outlet_whr);

            	header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=outlet_outstanding_list.csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Order Date', 'Order No', 'BDE', 'Invoice Date', 'Invoice No', 'Outlet Name', 'Reagion', 'Beat Name', 'Opening Balance', 'Pending Amount', 'Post Dated Cheque', 'Cheque Date', 'Final Balance', 'Due On', 'Age of bill in days'));

			    if($data_list['status'] == 1)
			    {
			    	$html        = '';
		    		$data_val    = $data_list['data'];
		    		$num         = 1;
		    		$tot_opening = 0;
		    		$tot_pending = 0;
		    		$tot_value   = 0;

		    		foreach ($data_val as $key => $val) {
	                    
	                    $order_date       = !empty($val['order_date'])?$val['order_date']:'';
	                    $order_no         = !empty($val['order_no'])?$val['order_no']:'';
	                    $bde_name         = !empty($val['bde_name'])?$val['bde_name']:'';
	                    $invoice_date     = !empty($val['invoice_date'])?$val['invoice_date']:'';
	                    $invoice_no       = !empty($val['invoice_no'])?$val['invoice_no']:'';
	                    $outlet_name      = !empty($val['outlet_name'])?$val['outlet_name']:'';
	                    $reagion          = !empty($val['reagion'])?$val['reagion']:'';
	                    $beat_name        = !empty($val['beat_name'])?$val['beat_name']:'';
	                    $opening_bal      = !empty($val['opening_bal'])?$val['opening_bal']:'';
	                    $pending_amt      = !empty($val['pending_amt'])?$val['pending_amt']:'';
	                    $post_cheque_date = !empty($val['post_cheque_date'])?$val['post_cheque_date']:'';
	                    $coll_cheque_date = !empty($val['coll_cheque_date'])?$val['coll_cheque_date']:'';
	                    $final_amt        = !empty($val['final_amt'])?$val['final_amt']:'';
	                    $due_on           = !empty($val['due_on'])?$val['due_on']:'';
	                    $age_of_bill      = !empty($val['age_of_bill'])?$val['age_of_bill']:'';

	                    $tot_opening += $opening_bal;
			    		$tot_pending += $pending_amt;
			    		$tot_value   += $final_amt;

	                    $num = array(
                        	$order_date,
		                    $order_no,
		                    $bde_name,
		                    $invoice_date,
		                    $invoice_no,
		                    $outlet_name,
		                    $reagion,
		                    $beat_name,
		                    $opening_bal,
		                    $pending_amt,
		                    $post_cheque_date,
		                    $coll_cheque_date,
		                    $final_amt,
		                    $due_on,
		                    $age_of_bill,
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
	                    $tot_opening,
	                    $tot_pending,
	                    '',
	                    '',
	                    $tot_value,
	                    '',
	                    '',
                    );

                    fputcsv($output, $num);
			    }

			    fclose($output);
      			exit();
        	}

			else
			{
            	$page['method']       = '_getOutletOutstandingData';
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Outlet Outstanding Report";
				$page['page_title']   = "Outlet Outstanding Report";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('distributors/report/outlet_outstanding',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "outlet_outstanding";
				$this->bassthaya->load_distributors_form_template($data);
			}
    	}

		public function stock_entry_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method == '_getStockData')
        	{
        		$start_date  = $this->input->post('start_date');
			    $end_date    = $this->input->post('end_date');

			    $stock_whr = array(
			    	'start_date'     => $start_date,
			    	'end_date'       => $end_date,
			    	'distributor_id' => $this->session->userdata('id'),
			    	'method'         => '_distributorStockEntryReport',
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

		    		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/stock_entry_report/excel_export/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Excel Export</a>';

		    		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/stock_entry_report/pdf_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

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
			    	'start_date'     => $start_date,
			    	'end_date'       => $end_date,
			    	'distributor_id' => $this->session->userdata('id'),
			    	'method'         => '_distributorStockEntryReport',
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
				$data['page_temp']    = $this->load->view('distributors/report/stock_entry_report',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "stock_entry_report";
				$this->bassthaya->load_distributors_form_template($data);
        	}
		}

		public function inventory_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method == '_getInventoryData')
        	{
        		$start_date  = $this->input->post('start_date');
			    $end_date    = $this->input->post('end_date');

			    $inventory_whr = array(
			    	'start_date'     => $start_date,
			    	'end_date'       => $end_date,
			    	'distributor_id' => $this->session->userdata('id'),
			    	'method'         => '_inventoryReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/inventory_report',$inventory_whr);
		    	
		    	if($data_list['status'] == 1)
		    	{
		    		$html     = '';
		    		$data_val = $data_list['data'];

		    		$num = 1;
		    		foreach ($data_val as $key => $val) {

		    			$description = !empty($val['description'])?$val['description']:'';
	                    $entry_qty   = !empty($val['entry_qty'])?$val['entry_qty']:'0';
	                    $createdate  = !empty($val['createdate'])?$val['createdate']:'';

						$html .= '
			            	<tr>
                                <td>'.$num.'</td>
                                <td>'.mb_strimwidth($description, 0, 50, '...').'</td>
                                <td>'.$entry_qty.'</td>
                                <td>'.$createdate.'</td>
                            </tr>
			            ';

			            $num++;
		    		}		

		    		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/inventory_report/excel_export/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Excel Export</a>';

		    		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/inventory_report/pdf_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

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

        		$inventory_whr = array(
			    	'start_date'     => $start_date,
			    	'end_date'       => $end_date,
			    	'distributor_id' => $this->session->userdata('id'),
			    	'method'         => '_inventoryReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/inventory_report',$inventory_whr);

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=inventory_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Description', 'Entry Value', 'Date'));

			    if($data_list['status'] == 1)
			    {
			    	$data_val = $data_list['data'];	

			    	foreach ($data_val as $key => $val) {

		    			$description = !empty($val['description'])?$val['description']:'';
	                    $entry_qty   = !empty($val['entry_qty'])?$val['entry_qty']:'0';
	                    $createdate  = !empty($val['createdate'])?$val['createdate']:'';

	                    $num = array(
			            	$description,
			            	$entry_qty,
			            	$createdate,
			            );

			            fputcsv($output, $num); 
	                }
			    }

			    fclose($output);
      			exit();
        	}

        	else
        	{
		    	$page['method']       = '_getInventoryData';
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Inventory Report";
				$page['page_title']   = "Inventory Report";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('distributors/report/inventory_report',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "inventory_report";
				$this->bassthaya->load_distributors_form_template($data);
        	}
		}

		public function product_stock($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        {
        	if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method =='_getDisProductStock')
        	{
        		$distributor_id = $this->session->userdata('id');
        		$category_id    = $this->input->post('category_id');
				$sub_cat_id    = $this->input->post('sub_cat_id');
        		$category_val   = implode(',', $category_id);
        		$category_res   = implode('_', $category_id);
				if(!empty($sub_cat_id)){
					$sub_cat_val = implode(',', $sub_cat_id);
					$sub_cat_res = implode('_', $sub_cat_id);
				}else{
					$sub_cat_val ='';
					$sub_cat_res ='';
				}
				
        		$stock_whr = array(
        			'distributor_id' => $this->session->userdata('id'),
        			'category_id'    => $category_val,
					's_cat_id'       => $sub_cat_val,
            		'method'         => '_overallDistributorStockReport',
            	);
				
            	$data_list = avul_call(API_URL.'report/api/stock_report',$stock_whr);
            	
				$data_res  = $data_list['data'];
				
            	if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'];
		    		$num      = 1;

			    	foreach ($data_res as $key => $val) {
	                    $category_name = !empty($val['category_name'])?$val['category_name']:'';  
	                    $description   = !empty($val['description'])?$val['description']:'';  
	                    $unit_name     = !empty($val['unit_name'])?$val['unit_name']:'';  
	                    $view_stock    = !empty($val['view_stock'])?$val['view_stock']:'0';  
	                    $view_stock    = !empty($val['view_stock'])?$val['view_stock']:'0';  
	                    $minimum_stock = !empty($val['minimum_stock'])?$val['minimum_stock']:'0';  

	                    $table_row = '';
					    if($minimum_stock != 0 && $minimum_stock >= $view_stock)
					    {
					    	$table_row = 'class="alret alert-danger"';
					    }

	                    $html .= '
	                    	<tr '.$table_row.'>
                                <td>'.$num.'</td>
                                <td>'.mb_strimwidth($description, 0, 40, '...').'</td>
                                <td>'.$view_stock.'</td>
                                <td>'.$minimum_stock.'</td>
                                <td>Nos</td>
                            </tr>
	                    ';

	                    $num++;
			    	}

			    	$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/product_stock/excel_print/'.$distributor_id.'/'.$category_res.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		    		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/product_stock/pdf_print/'.$distributor_id.'/'.$category_res.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

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

        	if($param1 =='excel_print')
        	{
        		$distributor_id = $param2;
        		$category_id    = $param3;
        		$category_val   = explode('_', $category_id);
        		$category_res   = implode(',', $category_val);

        		$stock_whr = array(
        			'distributor_id' => $distributor_id,
        			'category_id'    => $category_res,
            		'method'         => '_overallDistributorStockReport',
            	);

            	$data_list = avul_call(API_URL.'report/api/stock_report',$stock_whr);
            	$data_res  = $data_list['data'];

            	$where = array(
            		'distributor_id' => $distributor_id,
            		'method'         => '_detailDistributors'
            	);

            	$data_list    = avul_call(API_URL.'distributors/api/distributors',$where);
            	$data_value   = $data_list['data'][0];	
            	$company_name = !empty($data_value['company_name'])?$data_value['company_name']:'';

				header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename='.$company_name.'_product_stock.csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Description', 'Tax', 'Stock', 'Sum of Taxable Value', 'Sum of SGST Value', 'Sum of CGST Value', 'Sum of Net Amount'));          	

            	if($data_list['status'] == 1)
			    {
			    	$totQty     = 0;
                	$totTaxable = 0;
                	$totSgst    = 0;
                	$totCgst    = 0;
                	$totValue   = 0;

			    	foreach ($data_res as $key => $val) {

	                    $desc      = !empty($val['description'])?$val['description']:'0';
					    $gst_value = !empty($val['gst_value'])?$val['gst_value']:'0';
					    $pdt_price = !empty($val['product_price'])?$val['product_price']:'0';
					    $pdt_qty   = !empty($val['view_stock'])?$val['view_stock']:'0';

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
			            	$desc,
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
        	}else if($param1 == 'sub_cat')
        	{
        		$cat_id  = $this->input->post('cat_id');
				
				$category_res = implode(',', $cat_id);
				
			    $att_whr = array(
			    	'category_id'  => $category_res,
					'distributor_id'  =>  $this->session->userdata('id'),
			    	'method'      => '_distributorSubCategoryList',
			    );
				
				
			    $data_list  = avul_call(API_URL.'distributors/api/distributors',$att_whr);
		    	
		    	$data_val  = $data_list['data'];
				
			
        		$option ='<option value="">Select Sub Category</option>';

        		if(!empty($data_val))
        		{
        			foreach ($data_val as $key => $value) {
        				$id   = !empty($value['s_cat_id']) ?$value['s_cat_id']:'';
                        
						$name =!empty($value['s_cat_name'])?$value['s_cat_name']:'';

                        $select   = '';
        				
						

                        $option .= '<option value="'.$id.'" '.$select.'>'.$name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
        	} 
 
        	else
        	{
        		$where = array(
        			'distributor_id' => $this->session->userdata('id'),
        			'method'         => '_distributorCategoryList',
        		);

        		$category_list = avul_call(API_URL.'distributors/api/distributors',$where);
        		$category_res  = $category_list['data'];

        		$page['method']        = '_getDisProductStock';
        		$page['category_val']  = $category_res;
				$page['main_heading']  = "Report";
				$page['sub_heading']   = "Report";
				$page['pre_title']     = "Product Stock";
				$page['page_title']    = "Product Stock";
				$page['pre_menu']      = "";
				$data['page_temp']     = $this->load->view('distributors/report/product_stock',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "product_stock";
				$this->bassthaya->load_distributors_form_template($data);
        	}
        }

        public function purchase_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        {
        	if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method =='_getPurchaseReturn')
        	{
        		$start_date = $this->input->post('start_date');
        		$end_date   = $this->input->post('end_date');

        		$dis_purchase_whr = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
					'end_date'       => date('Y-m-d', strtotime($end_date)),
					'distributor_id' => $this->session->userdata('id'),
			    	'method'         => '_overallPurchaseReport',
			    );

        		$data_list  = avul_call(API_URL.'report/api/distributor_report',$dis_purchase_whr);

        		if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'];

		    		$num = 1;
		    		foreach ($data_val as $key => $val) {
		    			$auto_id        = !empty($val['auto_id'])?$val['auto_id']:'';
			            $po_no          = !empty($val['po_no'])?$val['po_no']:'';
			            $_ordered       = !empty($val['_ordered'])?$val['_ordered']:'';
			            $_delivery      = !empty($val['_delivery'])?$val['_delivery']:'';
			            $invoice_no     = !empty($val['invoice_no'])?$val['invoice_no']:'';
			            $invoice_random = !empty($val['invoice_random'])?$val['invoice_random']:'';
			            $purchase_value = !empty($val['purchase_value'])?$val['purchase_value']:'';

	                    $html .= '
			            	<tr>
                                <td>'.$auto_id.'</td>
                                <td><a target="_blank" href="'.BASE_URL.'index.php/distributors/purchase/print_order/'.$auto_id.'">'.$po_no.'</a></td>
                                <td><a target="_blank" href="'.BASE_URL.'index.php/distributors/purchase/print_invoice/'.$invoice_random.'">'.$invoice_no.'</a></td>
                                <td>'.date('d-M-Y', strtotime($_ordered)).'</td>
                                <td>'.$purchase_value.'</td>
                            </tr>
			            ';

			            $num++;
		    		}

		    		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/purchase_report/excel_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		    		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/purchase_report/pdf_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

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

        	if($param1 == 'excel_print')
        	{
        		$start_date = $param2; 
        		$end_date   = $param3;

        		$dis_purchase_whr = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
					'end_date'       => date('Y-m-d', strtotime($end_date)),
					'distributor_id' => $this->session->userdata('id'),
			    	'method'         => '_overallPurchaseDetails',
			    );

        		$data_list  = avul_call(API_URL.'report/api/distributor_report',$dis_purchase_whr);

        		header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=new_tally_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('InvNo', 'Inv_Dt', 'Pty_Name', 'Vch_Type', 'GSTIN', 'StateOfSupply', 'Product_Name', 'HSNCode', 'Qty', 'UOM', 'TaxPer', 'TaxableAmt', 'IGSTAmt', 'SGSTAmt', 'CGSTAmt', 'Cess', 'OtherAmt', 'Net_Amt', 'Narration', 'Discount', 'Supplied In voice No', 'Supplied Date', 'Purchase Ledger'));

			    if($data_list['status'] == 1)
			    {
			    	$data_val   = $data_list['data'];	
			    	$totQty     = 0;
		    		$totTaxable = 0;
		    		$totIgstAmt = 0;
		    		$totSgstAmt = 0;
		    		$totCgstAmt = 0;
		    		$totNetAmt  = 0;

			    	foreach ($data_val as $key => $val) {
			    		$pur_no        = !empty($val['pur_no'])?$val['pur_no']:'';
	                    $pur_date      = !empty($val['pur_date'])?$val['pur_date']:'';
	                    $adm_username  = !empty($val['adm_username'])?$val['adm_username']:'';
	                    $adm_state_id  = !empty($val['adm_state_id'])?$val['adm_state_id']:'';
	                    $dis_username  = !empty($val['dis_username'])?$val['dis_username']:'';
	                    $dis_gst_no    = !empty($val['dis_gst_no'])?$val['dis_gst_no']:'';
	                    $dis_state_id  = !empty($val['dis_state_id'])?$val['dis_state_id']:'';
	                    $dis_state_val = !empty($val['dis_state_val'])?$val['dis_state_val']:'';
	                    $description   = !empty($val['product_name'])?$val['product_name']:'';
	                    $hsn_code      = !empty($val['hsn_code'])?$val['hsn_code']:'';
	                    $pdt_qty       = !empty($val['product_qty'])?$val['product_qty']:'0';
	                    $gst_value     = !empty($val['product_gst'])?$val['product_gst']:'0';
	                    $pdt_price     = !empty($val['product_price'])?$val['product_price']:'0';
	                    $inv_no        = !empty($val['invoice_no'])?$val['invoice_no']:'';
	                    $inv_date      = !empty($val['invoice_date'])?$val['invoice_date']:'';
	                    $discount      = 0;

	                    $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
                        $price_val  = $pdt_price - $gst_data;
                        $pdt_gst    = $pdt_qty * $gst_data;
                        $TaxableAmt = $pdt_qty * $price_val;
                        $tot_price  = $pdt_qty * $pdt_price;
                        $pdt_value  = round($tot_price);
                        $total_dis  = $pdt_value * $discount / 100;
                        $total_val  = $pdt_value;

                        if($adm_state_id == $dis_state_id)
                        {
                        	$gst_res  = $pdt_gst / 2;
                        	$sgst_val = number_format((float)$gst_res, 2, '.', '');
                        	$cgst_val = number_format((float)$gst_res, 2, '.', '');
                        	$igst_val = '0';
                        	$vch_type = 'Local Purchase';
                        }
                        else
                        {
                        	$sgst_val = '0';
                        	$cgst_val = '0';
                        	$igst_val = number_format((float)$pdt_gst, 2, '.', '');
                        	$vch_type = 'Inter Purchase';
                        }

                        $totQty     += $pdt_qty;
			    		$totTaxable += $TaxableAmt;
			    		$totIgstAmt += $igst_val;
			    		$totSgstAmt += $sgst_val;
			    		$totCgstAmt += $cgst_val;
			    		$totNetAmt  += $total_val;

			    		$num = array(
			    			$pur_no,
			    			$pur_date,
			    			$adm_username,
			    			$vch_type,
			    			$dis_gst_no,
			    			$dis_state_val,
			    			$description,
			    			$hsn_code,
			    			$pdt_qty,
			    			'Nos',
			    			$gst_value,
			    			number_format((float)$TaxableAmt, 2, '.', ''),
			    			$igst_val,
			    			$cgst_val,
			    			$sgst_val,
			    			'0',
			    			'0',
			    			number_format((float)$total_val, 2, '.', ''),
			    			'',
			    			'0',
			    			$inv_no,
			    			$inv_date,
			    			''
			    		);

			    		fputcsv($output, $num);
			    	}

			    	fputcsv($output, array('', '', '', '', '', '', '', '', $totQty, '', '', number_format((float)$totTaxable, 2, '.', ''), number_format((float)$totIgstAmt, 2, '.', ''), number_format((float)$totSgstAmt, 2, '.', ''), number_format((float)$totCgstAmt, 2, '.', ''), '0', '0', number_format((float)$totNetAmt, 2, '.', ''), '', '', '', '', ''));
			    }

			    fclose($output);
      			exit();
        	}

        	else
        	{
        		$page['method']        = '_getPurchaseReturn';
				$page['main_heading']  = "Report";
				$page['sub_heading']   = "Report";
				$page['pre_title']     = "Purchase Report";
				$page['page_title']    = "Purchase Report";
				$page['pre_menu']      = "";
				$data['page_temp']     = $this->load->view('distributors/report/purchase_report',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "purchase_report";
				$this->bassthaya->load_distributors_form_template($data);
        	}
        }

        public function sales_return($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        {
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method == '_getSalesReturnData')
        	{
        		$start_date = $this->input->post('start_date');
			    $end_date   = $this->input->post('end_date');

			    $sales_whr = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'distributor_id' => $this->session->userdata('id'),
			    	'method'         => '_overallOutletSalesReturnReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/sales_report',$sales_whr);

			    if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'];

		    		$num = 1;
		    		foreach ($data_val as $key => $val) {
		    			$return_no      = !empty($val['return_no'])?$val['return_no']:'';
					    $store_name     = !empty($val['store_name'])?$val['store_name']:'';
					    $invoice_no     = !empty($val['invoice_no'])?$val['invoice_no']:'';
					    $invoice_random = !empty($val['invoice_random'])?$val['invoice_random']:'';
					    $return_value   = !empty($val['return_value'])?$val['return_value']:'';
					    $return_random  = !empty($val['return_random'])?$val['return_random']:'';
					    $return_date    = !empty($val['return_date'])?$val['return_date']:'';

					    $html .= '
			            	<tr>
                                <td>'.$num.'</td>
                                <td><a target="_blank" href="'.BASE_URL.'index.php/distributors/salesreturn/list_sales_return/print_invoice/'.$return_random.'">'.$return_no.'</a></td>
                                <td><a target="_blank" href="'.BASE_URL.'index.php/distributors/order/print_invoice/'.$invoice_random.'">'.$invoice_no.'</a></td>
                                <td>'.mb_strimwidth($store_name, 0, 20, '...').'</td>
                                <td>'.date('d-M-Y', strtotime($return_date)).'</td>
                                <td>'.$return_value.'</td>
                            </tr>
			            ';

			            $num++;
		    		}

		    		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sales_return/excel_print/'.$start_date.'/'.$end_date.'/'.$this->session->userdata('id').'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		    		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/distributors/report/sales_return/pdf_print/'.$start_date.'/'.$end_date.'/'.$this->session->userdata('id').'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		    		$response['status']    = 1;
			        $response['message']   = $data_list['message']; 
			        $response['data']      = $html;
			        $response['tally_btn'] = $excel_btn;
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

        	if($param1 == 'excel_print')
        	{
        		$start_date = $param2; 
        		$end_date   = $param3;
        		$dis_id     = $param4;

        		$sales_whr = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'distributor_id' => $dis_id,
			    	'method'         => '_overallOutletSalesReturnDetails',
			    );

			    $data_list  = avul_call(API_URL.'report/api/sales_report',$sales_whr);

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=sales_return_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Credit note No', 'Inv Date', 'Original Inv No', 'Voucher Date', 'Pty_Name', 'Vch_Type', 'GSTIN', 'StateOfSupply', 'Product_Name', 'HSNCode', 'Qty', 'UOM', 'TaxPer', 'TaxableAmt', 'IGSTAmt', 'SGSTAmt', 'CGSTAmt', 'Cess', 'OtherAmt', 'Net_Amt', 'Narration', 'Discount'));

			    if($data_list['status'] == 1)
			    {
			    	$data_val   = $data_list['data'];	
			    	$totQty     = 0;
		    		$totTaxable = 0;
		    		$totIgstAmt = 0;
		    		$totSgstAmt = 0;
		    		$totCgstAmt = 0;
		    		$totNetAmt  = 0;

		    		foreach ($data_val as $key => $val) {

		    			$return_no   = !empty($val['return_no'])?$val['return_no']:'';
	                    $return_date = !empty($val['return_date'])?$val['return_date']:'';
	                    $inv_no      = !empty($val['inv_no'])?$val['inv_no']:'';
	                    $inv_date    = !empty($val['inv_date'])?$val['inv_date']:'';
	                    $str_name    = !empty($val['str_name'])?$val['str_name']:'';
	                    $gst_number  = !empty($val['gst_number'])?$val['gst_number']:'';
	                    $dis_state   = !empty($val['dis_state'])?$val['dis_state']:'';
	                    $state_id    = !empty($val['state_id'])?$val['state_id']:'';
	                    $str_state   = !empty($val['str_state'])?$val['str_state']:'';
	                    $description = !empty($val['pdt_desc'])?$val['pdt_desc']:'';
	                    $hsn_code    = !empty($val['hsn_code'])?$val['hsn_code']:'';
	                    $pdt_qty     = !empty($val['return_qty'])?$val['return_qty']:'0';
	                    $gst_value   = !empty($val['gst_val'])?$val['gst_val']:'0';
	                    $pdt_price   = !empty($val['price_val'])?$val['price_val']:'0';
	                    $discount    = 0;

	                    $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
                        $price_val  = $pdt_price - $gst_data;
                        $pdt_gst    = $pdt_qty * $gst_data;
                        $TaxableAmt = $pdt_qty * $price_val;
                        $tot_price  = $pdt_qty * $pdt_price;
                        $pdt_value  = round($tot_price);
                        $total_dis  = $pdt_value * $discount / 100;
                        $total_val  = $pdt_value;

                        if($dis_state == $state_id)
                        {
                        	$gst_res  = $pdt_gst / 2;
                        	$sgst_val = number_format((float)$gst_res, 2, '.', '');
                        	$cgst_val = number_format((float)$gst_res, 2, '.', '');
                        	$igst_val = '0';
                        	$vch_type = 'Credit Note';
                        }
                        else
                        {
                        	$sgst_val = '0';
                        	$cgst_val = '0';
                        	$igst_val = number_format((float)$pdt_gst, 2, '.', '');
                        	$vch_type = 'Credit Note';
                        }

                        $totQty     += $pdt_qty;
			    		$totTaxable += $TaxableAmt;
			    		$totIgstAmt += $igst_val;
			    		$totSgstAmt += $sgst_val;
			    		$totCgstAmt += $cgst_val;
			    		$totNetAmt  += $total_val;

			    		$num = array(
			    			$return_no,
			    			date('d-m-Y', strtotime($return_date)),
			    			$inv_no,
			    			date('d-m-Y', strtotime($inv_date)),
			    			$str_name,
			    			$vch_type,
			    			$gst_number,
			    			$str_state,
			    			$description,
			    			$hsn_code,
			    			$pdt_qty,
			    			'Nos',
			    			$gst_value,
			    			number_format((float)$TaxableAmt, 2, '.', ''),
			    			$igst_val,
			    			$cgst_val,
			    			$sgst_val,
			    			'0',
			    			'0',
			    			number_format((float)$total_val, 2, '.', ''),
			    			'',
			    			'0',
			    		);

			    		fputcsv($output, $num);
		    		}

		    		fputcsv($output, array('', '', '', '', '', '', '', '', '', '', $totQty, '', '', number_format((float)$totTaxable, 2, '.', ''), number_format((float)$totIgstAmt, 2, '.', ''), number_format((float)$totSgstAmt, 2, '.', ''), number_format((float)$totCgstAmt, 2, '.', ''), '0', '0', number_format((float)$totNetAmt, 2, '.', ''), '', '', '', '', ''));
			    }

			    fclose($output);
      			exit();
        	}
        	else
        	{
		    	$page['method']       = '_getSalesReturnData';
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Sales Return Report";
				$page['page_title']   = "Sales Return Report";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('distributors/report/sales_return',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "sales_return";
				$this->bassthaya->load_distributors_form_template($data);
        	}
    	}

    	public function overall_outstanding($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
    	{
    		if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');


        	if($param1 =='getCity_name')
			{
				$state_id = $this->input->post('state_id');

				$where = array(
            		'state_id' => $state_id,
            		'method'   => '_listCity'
            	);

            	$city_list   = avul_call(API_URL.'master/api/city',$where);
            	$city_result = $city_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($city_result))
        		{
        			foreach ($city_result as $key => $value) {
        				$city_id   = !empty($value['city_id'])?$value['city_id']:'';
                        $city_name = !empty($value['city_name'])?$value['city_name']:'';

                        $option .= '<option value="'.$city_id.'">'.$city_name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
			}

			else if($param1 =='getZone_name')
			{
				$state_id = $this->input->post('state_id');
				$city_id  = $this->input->post('city_id');

				$where = array(
            		'state_id' => $state_id,
            		'city_id'  => $city_id,
            		'method'   => '_listZone'
            	);

            	$zone_list   = avul_call(API_URL.'master/api/zone',$where);
            	$zone_result = $zone_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($zone_result))
        		{
        			foreach ($zone_result as $key => $value) {
        				$zone_id   = !empty($value['zone_id'])?$value['zone_id']:'';
                        $zone_name = !empty($value['zone_name'])?$value['zone_name']:'';

                        $option .= '<option value="'.$zone_id.'">'.$zone_name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
			}

			else if($param1 =='excel_print')
			{
				$state_id = zero_check($param2); 
        		$city_id  = zero_check($param3);
        		$zone_id  = zero_check($param4);

        		$outlet_whr  = array(
        			'state_id'       => $state_id,
        			'city_id'        => $city_id,
        			'zone_id'        => $zone_id,
        			'distributor_id' => $this->session->userdata('id'),
            		'method'         => '_getOutstandingData'
            	);

            	$data_list  = avul_call(API_URL.'report/api/outlet_outstanding',$outlet_whr);

            	header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=outlet_outstanding_report.csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Store Name', 'Contact No', 'State Name', 'City Name', 'Beat Name', 'Address', 'Current Balance'));

			    if($data_list['status'] == 1)
			    {
			    	$data_val   = $data_list['data'];	
			    	$totNetAmt  = 0;

			    	foreach ($data_val as $key => $val) {

	                    $outlet_name = !empty($val['outlet_name'])?$val['outlet_name']:'';
	                    $mobile      = !empty($val['mobile'])?$val['mobile']:'';
	                    $address     = !empty($val['address'])?$val['address']:'';
	                    $state_name  = !empty($val['state_name'])?$val['state_name']:'';
	                    $city_name   = !empty($val['city_name'])?$val['city_name']:'';
	                    $zone_name   = !empty($val['zone_name'])?$val['zone_name']:'';
	                    $cur_bal     = !empty($val['cur_bal'])?$val['cur_bal']:'0';
	                    $totNetAmt  += $cur_bal;

	                    $num = array(
			    			$outlet_name,
			    			$mobile,
			    			$state_name,
			    			$city_name,
			    			$zone_name,
			    			$address,
			    			number_format((float)$cur_bal, 2, '.', ''),
			    		);

			    		fputcsv($output, $num);
			    	}

			    	fputcsv($output, array('', '', '', '', '', '', number_format((float)$totNetAmt, 2, '.', '')));	
			    }

			    fclose($output);
      			exit();
			}

        	if($method == '_getOutstandingData')
        	{
        		$state_id   = $this->input->post('state_id');
        		$city_id    = $this->input->post('city_id');
        		$zone_id    = $this->input->post('zone_id');

        		$outlet_whr  = array(
        			'state_id'       => $state_id,
        			'city_id'        => $city_id,
        			'zone_id'        => $zone_id,
        			'distributor_id' => $this->session->userdata('id'),
            		'method'         => '_getOutstandingData'
            	);

            	$data_list  = avul_call(API_URL.'report/api/outlet_outstanding',$outlet_whr);

			    if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'];

		    		$num = 1;
		    		foreach ($data_val as $key => $val) {
		    			$outlet_id   = !empty($val['outlet_id'])?$val['outlet_id']:'';
	                    $outlet_name = !empty($val['outlet_name'])?$val['outlet_name']:'';
	                    $mobile      = !empty($val['mobile'])?$val['mobile']:'';
	                    $address     = !empty($val['address'])?$val['address']:'';
	                    $city_name   = !empty($val['city_name'])?$val['city_name']:'';
	                    $zone_name   = !empty($val['zone_name'])?$val['zone_name']:'';
	                    $cur_bal     = !empty($val['cur_bal'])?$val['cur_bal']:'0';

	                    $html .= '
			            	<tr>
                                <td>'.$num.'</td>
                                <td>'.mb_strimwidth($outlet_name, 0, 30, '...').'</td>
                                <td>'.$mobile.'</td>
                                <td>'.$city_name.'</td>
                                <td>'.$zone_name.'</td>
                                <td>'.$cur_bal.'</td>
                            </tr>
			            ';

			            $num++;
		    		}

		    		$excel_btn = '<a class="btn btn-success" target="_blank" href="'.BASE_URL.'index.php/distributors/report/overall_outstanding/excel_print/'.zero_check($state_id).'/'.zero_check($city_id).'/'.zero_check($zone_id).'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		    		$pdf_btn   = '<a class="btn btn-danger" target="_blank" href="'.BASE_URL.'index.php/distributors/report/overall_outstanding/pdf_print/'.zero_check($state_id).'/'.zero_check($city_id).'/'.zero_check($zone_id).'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

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

        	else
        	{
        		$where_1 = array(
            		'method'   => '_listState'
            	);

            	$state_list  = avul_call(API_URL.'master/api/state',$where_1);

        		$page['state_val']    = $state_list['data'];
		    	$page['method']       = '_getOutstandingData';
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Overall Outstanding Report";
				$page['page_title']   = "Overall Outstanding Report";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('distributors/report/overall_outstanding',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "overall_outstanding";
				$this->bassthaya->load_distributors_form_template($data);
        	}
    	}

    	public function attendance_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
    	{
    		if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method == '_getAttendanceData')
        	{
        		$start_date     = $this->input->post('start_date');
			    $end_date       = $this->input->post('end_date');
			    $employee_id    = $this->input->post('employee_id');


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
			    	$att_whr = array(
				    	'start_date'     => date('Y-m-d', strtotime($start_date)),
				    	'end_date'       => date('Y-m-d', strtotime($end_date)),
				    	'employee_id'    => $employee_id,
				    	'distributor_id' => $this->session->userdata('id'),
				    	'method'         => '_deliveryAttendanceReport',
				    );

				    $data_list  = avul_call(API_URL.'report/api/attendace_report',$att_whr);

				    if($data_list['status'] == 1)
				    {
				    	$html     = '';
			    		$data_val = $data_list['data'];

			    		$num = 1;
			    		foreach ($data_val as $key => $val) {

		                    $att_id        = !empty($val['att_id'])?$val['att_id']:'';
		                    $emp_name      = !empty($val['emp_name'])?$val['emp_name']:'Admin';
		                    $store_name    = !empty($val['store_name'])?$val['store_name']:'';
		                    $beat_name     = !empty($val['beat_name'])?$val['beat_name']:'';
		                    $order_no      = !empty($val['order_no'])?$val['order_no']:'';
		                    $order_date    = !empty($val['order_date'])?$val['order_date']:'';
		                    $invoice_no    = !empty($val['invoice_no'])?$val['invoice_no']:'';
		                    $invoice_date  = !empty($val['invoice_date'])?$val['invoice_date']:'';
		                    $delivery_date = !empty($val['delivery_date'])?$val['delivery_date']:'';
		                    $in_time       = !empty($val['in_time'])?$val['in_time']:'';
		                    $out_time      = !empty($val['out_time'])?$val['out_time']:'';

		                    $html .= '
				            	<tr>
	                                <td>'.$num.'</td>
	                                <td>'.mb_strimwidth($emp_name, 0, 30, '...').'</td>
	                                <td>'.mb_strimwidth($store_name, 0, 30, '...').'</td>
	                                <td>'.mb_strimwidth($beat_name, 0, 30, '...').'</td>
	                                <td>'.$order_no.'</td>
	                                <td>'.$invoice_no.'</td>
	                                <td>'.$delivery_date.'</td>
	                                <td>'.$in_time.'</td>
	                                <td>'.$out_time.'</td>
	                            </tr>
				            ';

				            $num++;
			    		}

			    		$excel_btn = '<a class="btn btn-success" target="_blank" href="'.BASE_URL.'index.php/distributors/report/attendance_report/excel_print/'.empty_check($start_date).'/'.empty_check($end_date).'/'.zero_check($employee_id).'" style="color: #fff; margin-top: 27px;"><i class="icon-grid"></i> Excel</a>';

			    		$pdf_btn   = '<a class="btn btn-danger" target="_blank" href="'.BASE_URL.'index.php/distributors/report/attendance_report/pdf_print/'.empty_check($start_date).'/'.empty_check($end_date).'/'.zero_check($employee_id).'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

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

        	if($param1 == 'excel_print')
        	{
        		$start_date     = $param2;
			    $end_date       = $param3;
			    $employee_id    = $param4;

			    $att_whr = array(
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
			    	'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'employee_id'    => $employee_id,
			    	'distributor_id' => $this->session->userdata('id'),
			    	'method'         => '_deliveryAttendanceReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/attendace_report',$att_whr);

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=deliveryman_attendance_report.csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Employee Name', 'Store Name', 'Beat Name', 'Order No', 'Order Date', 'Invoice No', 'Invoice Date', 'Delivery Date', 'In Time', 'Out Time'));

			    if($data_list['status'] == 1)
			    {
			    	$data_val   = $data_list['data'];	
			    	$totNetAmt  = 0;

			    	foreach ($data_val as $key => $val) {
	                    $num = array(
			    			empty_check($val['emp_name']),
		                    empty_check($val['store_name']),
		                    empty_check($val['beat_name']),
		                    empty_check($val['order_no']),
		                    empty_check($val['order_date']),
		                    empty_check($val['invoice_no']),
		                    empty_check($val['invoice_date']),
		                    empty_check($val['delivery_date']),
		                    empty_check($val['in_time']),
		                    empty_check($val['out_time']),
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
        			'company_id' => $this->session->userdata('id'),
		    		'log_type'   => '1',
		    		'method'     => '_typeWiseEmployee'
		    	);

		    	$data_list  = avul_call(API_URL.'employee/api/employee',$where_1);
		    	$emp_list   = !empty($data_list['data'])?$data_list['data']:'';

		    	$page['method']       = '_getAttendanceData';
		    	$page['emp_list']     = $emp_list;
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Attendance Report";
				$page['page_title']   = "Attendance Report";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('distributors/report/attendance_report',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "attendance_report";
				$this->bassthaya->load_distributors_form_template($data);
        	}
    	}
	}
?>
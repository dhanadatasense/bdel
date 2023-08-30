<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Report extends CI_Controller {

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
			$this->load->model('pricemaster_model');
			$this->load->model('return_model');
			$this->load->model('managers_model');
		}

		public function index()
		{
			echo "Test";
		}
		public function sub_dis_sales_report($param1="",$param2="",$param3="")
		{	
			$method         = $this->input->post('method');
			$start_date     = $this->input->post('start_date');
			$end_date       = $this->input->post('end_date');
			$distributor_id = $this->input->post('distributor_id');

			if($method == '_salesReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'cancel_status' => '1',
							'published'     => '1',
						);

						if(!empty($distributor_id))
						{
							$where_1['distributor_id'] = $distributor_id;
						}

						$column_1 = 'invoice_id, invoice_no, type_id, hsn_code, gst_val, price, order_qty';

						$inv_details = $this->invoice_model->getInvoiceDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($inv_details)
						{
							$sales_details = [];
							foreach ($inv_details as $key => $val_1) {

								$invoice_id = !empty($val_1->invoice_id)?$val_1->invoice_id:'';
								$invoice_no = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
								$type_id    = !empty($val_1->type_id)?$val_1->type_id:'';
								$hsn_code   = !empty($val_1->hsn_code)?$val_1->hsn_code:'';
								$gst_val    = !empty($val_1->gst_val)?$val_1->gst_val:'';
								$price      = !empty($val_1->price)?$val_1->price:'';
								$order_qty  = !empty($val_1->order_qty)?$val_1->order_qty:'';

								// Invoice Details
								$where_2  = array('id' => $invoice_id);
								$column_2 = 'order_id, distributor_id, date';
								$inv_data = $this->invoice_model->getInvoice($where_2, '', '', 'result', '', '', '', '', $column_2);

								$ord_id   = !empty($inv_data[0]->order_id)?$inv_data[0]->order_id:'';
								$dis_id   = !empty($inv_data[0]->distributor_id)?$inv_data[0]->distributor_id:'';
								$ord_date = !empty($inv_data[0]->date)?$inv_data[0]->date:'';

								// Distributor Details
								$where_3  = array('id' => $dis_id);
								$column_3 = 'company_name, mobile, gst_no, address, state_id';
								$dis_data = $this->distributors_model->getDistributors($where_3, '', '', 'result', '', '', '', '', $column_3);

								$com_name  = !empty($dis_data[0]->company_name)?$dis_data[0]->company_name:'';
					            $mobile    = !empty($dis_data[0]->mobile)?$dis_data[0]->mobile:'';
					            $gst_no    = !empty($dis_data[0]->gst_no)?$dis_data[0]->gst_no:'';
					            $address   = !empty($dis_data[0]->address)?$dis_data[0]->address:'';
					            $dis_state = !empty($dis_data[0]->state_id)?$dis_data[0]->state_id:'';

					            // State Details
								$where_4  = array('id' => $dis_state);
								$column_4 = 'gst_code, state_name';
								$gst_data = $this->commom_model->getState($where_4, '', '', 'result', '', '', '', '', $column_4);

								$state_name = !empty($gst_data[0]->state_name)?$gst_data[0]->state_name:'';
								$gst_code   = !empty($gst_data[0]->gst_code)?$gst_data[0]->gst_code:'';

								// Product Details
								$where_5  = array('id' => $type_id);
								$column_5 = 'description';
								$pdt_data = $this->commom_model->getProductType($where_5, '', '', 'result', '', '', '', '', $column_5);

								$description  = !empty($pdt_data[0]->description)?$pdt_data[0]->description:'';

								$where_6  = array('id' => '1');
								$column_6 = 'state_id';
								$adm_data = $this->user_model->getUser($where_6, '', '', 'result', '', '', '', '', $column_6);

								$adm_state = !empty($adm_data[0]->state_id)?$adm_data[0]->state_id:'';

								// Order Details
								$where_7  = array('id' => $ord_id);
								$column_7 = 'order_no';
								$pur_data = $this->Order_model->getOrder($where_7, '', '', 'result', '', '', '', '', $column_7);

								$pur_num  = !empty($pur_data[0]->order_no)?$pur_data[0]->order_no:'';

								$sales_details[] = array(
									'adm_state'    => $adm_state,
									'invoice_no'   => $invoice_no,
									'order_no'     => $pur_num,
									'dis_name'     => $com_name,
									'mobile'       => $mobile,
									'gst_no'       => $gst_no,
									'address'      => $address,
									'state_id'     => $dis_state,
									'state_name'   => $state_name,
									'gst_code'     => $gst_code,
									'invoice_date' => date('d-m-Y', strtotime($ord_date)),
									'description'  => $description,
									'hsn_code'     => $hsn_code,
									'gst_val'      => $gst_val,
									'price'        => $price,
									'order_qty'    => $order_qty,
								);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $sales_details;
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

			else if($method == '_overallInviceReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'cancel_status' => '1',
							'published'     => '1',
						);

						if(!empty($distributor_id))
						{
							$where_1['distributor_id'] = $distributor_id;
						}

						$column_1 = 'id, order_id, invoice_no, distributor_id, due_days, createdate, random_value,store_name';

						$inv_details = $this->invoice_model->getInvoice($where_1, '', '', 'result', '', '', '', '', $column_1);
						//print_r($this->db->last_query());
						if($inv_details)
						{
							$inv_list    = [];
							$inv_cnt     = !empty(count($inv_details))?count($inv_details):'0';
							$tot_amt     = 0;
							$tot_tax     = 0;
							$tot_taxable = 0;

							foreach ($inv_details as $key => $val_1) {
								$inv_id   = !empty($val_1->id)?$val_1->id:'';
					            $ord_id   = !empty($val_1->order_id)?$val_1->order_id:'';
					            $inv_no   = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
					            $dis_id   = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
					            $due_days = !empty($val_1->due_days)?$val_1->due_days:'';
					            $inv_date = !empty($val_1->createdate)?$val_1->createdate:'';
					            $inv_rdm  = !empty($val_1->random_value)?$val_1->random_value:'';
								$store_name  = !empty($val_1->store_name)?$val_1->store_name:'';

					            // Order No
					            $whr_2 = array('id' => $ord_id);
					            $col_2 = 'id, order_no';
					            $res_2 = $this->order_model->getOrder($whr_2, '', '', 'result', '', '', '', '', $col_2);

					            $order_rdm = !empty($res_2[0]->id)?$res_2[0]->id:'';
					            $order_no  = !empty($res_2[0]->order_no)?$res_2[0]->order_no:'';

					            // Invoice Details
					            $whr_4 = array('invoice_id' => $inv_id, 'cancel_status' => '1', 'published' => '1');
					            $col_4 = 'price, order_qty, gst_val';
					            $res_4 = $this->invoice_model->getInvoiceDetails($whr_4, '', '', 'result', '', '', '', '', $col_4);

					            $inv_total   = 0;
					            $taxable_amt = 0;
					            $tax_amt     = 0;
					            if($res_4)
					            {
					            	foreach ($res_4 as $key => $val_4) {
					            		$pdt_price  = !empty($val_4->price)?$val_4->price:'0';
										$order_qty  = !empty($val_4->order_qty)?$val_4->order_qty:'0';
										$gst_value  = !empty($val_4->gst_val)?$val_4->gst_val:'0';

										$price_tot  = $pdt_price * $order_qty;
										$inv_total += $price_tot;

										// GST Calculation
										$gst_data    = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
										$price_val   = $pdt_price - $gst_data;
										$tax_amt     += $order_qty * $gst_data;
                        				$taxable_amt += $order_qty * $price_val;
					            	}
					            }

					            // Distributor Details
					            $whr_5 = array('id' => $dis_id);
					            $col_5 = 'company_name';
					            $res_5 = $this->distributors_model->getDistributors($whr_5, '', '', 'result', '', '', '', '', $col_5);

					            $dis_name = !empty($res_5[0]->company_name)?$res_5[0]->company_name:'';

					            // Round Val Details
		                        $net_value = round($inv_total);
		                        $total_dis = 0;	
		                        $total_val = $net_value - $total_dis;

		                        // Round Val Details
		                        $last_value = round($total_val);

		                        // Round Val Details
		                        $last_value = round($total_val);
		                        $rond_total = $last_value - $total_val;

		                        $tot_amt     += $last_value;
		                        $tot_tax     += $tax_amt;
		                        $tot_taxable += $taxable_amt;

		                        $inv_list[] = array(
					            	'inv_id'       => $inv_id,
						            'order_no'     => $order_no,
						            'inv_no'       => $inv_no,
						            'inv_date'     => date('d-M-Y', strtotime($inv_date)),
						            'company_name' => $dis_name,
						            'due_days'     => $due_days,
						            'round_value'  => strval($rond_total),
						            'inv_value'    => strval($last_value),
						            'purchase_res' => $order_rdm,
						            'invoice_res'  => $inv_rdm,
									'store_name'   => $store_name
					            );
							}

							$total_value = array(
								'total_count'   => strval($inv_cnt),
								'total_taxable' => number_format((float)$tot_taxable, 2, '.', ''),
								'total_tax'     => number_format((float)$tot_tax, 2, '.', ''),
								'total_value'   => number_format((float)$tot_amt, 2, '.', ''),
							);

							$inv_data = array(
								'inv_total' => $total_value,
								'inv_list'  => $inv_list,
							);

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $inv_data;
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

			else if($method == '_salesGstReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'cancel_status' => '1',
							'published'     => '1',
						);

				    	if(!empty($distributor_id))
						{
							$where_1['distributor_id'] = $distributor_id;
						}

						$column_1 = 'id, invoice_no, distributor_id, due_days, createdate';

						$result_1 = $this->invoice_model->getDistributorInvoice($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($result_1)
						{
							$inv_data = [];
							foreach ($result_1 as $key => $val_1) {
								$inv_id   = !empty($val_1->id)?$val_1->id:'';
					            $inv_no   = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
					            $dis_id   = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
					            $due_days = !empty($val_1->due_days)?$val_1->due_days:'';
					            $inv_date = !empty($val_1->createdate)?$val_1->createdate:'';

					            // Store Details
	                            $where_2  = array('id' => $dis_id);
	                            $column_2 = 'company_name, gst_no, state_id';

	                            $res_2    = $this->distributors_model->getDistributors($where_2, '', '', 'result', '', '', '', '', $column_2);

	                            $dis_name     = !empty($res_2[0]->company_name)?$res_2[0]->company_name:'';
	                            $dis_gst_no   = !empty($res_2[0]->gst_no)?$res_2[0]->gst_no:'';
	                            $dis_state_id = !empty($res_2[0]->state_id)?$res_2[0]->state_id:'';

	                            // State Details
	                            $where_3  = array('id' => $dis_state_id);
	                            $column_3 = 'state_name, gst_code';
	                            $res_3    = $this->commom_model->getState($where_3, '', '', 'result', '', '', '', '', $column_3);

	                            $dis_state_name = !empty($res_3[0]->state_name)?$res_3[0]->state_name:'';
	                            $dis_gst_code   = !empty($res_3[0]->gst_code)?$res_3[0]->gst_code:'';

	                            $gst_per = array('0', '5', '12', '18', '28');
	                            $gst_cnt = count($gst_per);

	                            // Invoice Details
	                            $where_4  = array('invoice_id' => $inv_id, 'published' => '1');
	                            $column_4 = 'price, order_qty';
	                            $result_4 = $this->invoice_model->getDistributorInvoiceDetails($where_4, '', '', 'result', '', '', '', '', $column_4);

	                            $inv_tot  = 0;
	                            if($result_4)
	                            {
	                            	foreach ($result_4 as $key => $val_4) {
	                            		$price_val   = !empty($val_4->price)?$val_4->price:'0';
                                        $order_qty   = !empty($val_4->order_qty)?$val_4->order_qty:'0';
                                        $tot_price   = $order_qty * $price_val;
                                        $inv_tot    += $tot_price;
	                            	}
	                            }

	                            $inv_round = round($inv_tot);
	                            $inv_total = number_format((float)$inv_round, 2, '.', '');

	                            for ($i=0; $i < $gst_cnt; $i++) {

	                            	$gst_res = number_format((float)$gst_per[$i], 2, '.', '');

	                            	// GST Calculation Value
	                            	$where_5  = array(
	                            		'invoice_id' => $inv_id, 
	                            		'gst_val'    => $gst_per[$i],
	                            		'published'  => '1'
	                            	);

	                            	$column_5 = 'price, order_qty, gst_val';
	                            	$result_5 = $this->invoice_model->getDistributorInvoiceDetails($where_5, '', '', 'result', '', '', '', '', $column_5);

	                            	$pdt_price = 0;
                                    $gst_price = 0;
	                            	if($result_5)
	                            	{
	                            		foreach ($result_5 as $key => $val_5) {
	                            			$price     = !empty($val_5->price)?$val_5->price:'';
                                            $order_qty = !empty($val_5->order_qty)?$val_5->order_qty:'';
                                            $gst_val   = !empty($val_5->gst_val)?$val_5->gst_val:'0';

                                            $gst_data    = $price - ($price * (100 / (100 + $gst_val)));
                                            $price_val   = $price - $gst_data;
                                            $total_price = $order_qty * $price_val;
                                            $total_gst   = $order_qty * $gst_data;

                                            $gst_price += $total_gst;
                                            $pdt_price += $total_price;
	                            		}
	                            	}

	                            	$pdt_total = number_format((float)$pdt_price, 2, '.', ''); 
	                            	$gst_total = number_format((float)$gst_price, 2, '.', '');

	                            	$inv_data[] = array(
	                            		'company_name' => $dis_name,
		                            	'company_gst'  => $dis_gst_no,
		                            	'invoice_no'   => $inv_no,
		                            	'invoice_date' => date('d-M-Y', strtotime($inv_date)),
		                            	'state_gst'    => $dis_gst_code,
		                            	'state_name'   => $dis_state_name,
		                            	'gst_rate'     => $gst_res,
		                            	'invoice_val'  => $inv_total,
		                            	'product_val'  => $pdt_total,
		                            	'taxable_val'  => $gst_total,
		                            );
	                            }
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $inv_data;
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

			else if($method == '_overallSalesReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'cancel_status' => '1',
							'published'     => '1',
						);

				    	if(!empty($distributor_id))
						{
							$where_1['distributor_id'] = $distributor_id;
						}

						$column_1 = 'invoice_id, invoice_no, type_id, hsn_code, gst_val, price, order_qty';

						$inv_details = $this->invoice_model->getDistributorInvoiceDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($inv_details)
						{
							$sales_details = [];
							foreach ($inv_details as $key => $val_1) {

								$invoice_id = !empty($val_1->invoice_id)?$val_1->invoice_id:'';
								$invoice_no = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
								$type_id    = !empty($val_1->type_id)?$val_1->type_id:'';
								$hsn_code   = !empty($val_1->hsn_code)?$val_1->hsn_code:'';
								$gst_val    = !empty($val_1->gst_val)?$val_1->gst_val:'';
								$price      = !empty($val_1->price)?$val_1->price:'';
								$order_qty  = !empty($val_1->order_qty)?$val_1->order_qty:'';

								// Invoice Details
								$where_2  = array('id' => $invoice_id);
								$column_2 = 'order_id, distributor_id, date';
								$inv_data = $this->invoice_model->getDistributorInvoice($where_2, '', '', 'result', '', '', '', '', $column_2);

								$ord_id   = !empty($inv_data[0]->order_id)?$inv_data[0]->order_id:'';
								$dis_id   = !empty($inv_data[0]->distributor_id)?$inv_data[0]->distributor_id:'';
								$ord_date = !empty($inv_data[0]->date)?$inv_data[0]->date:'';

								// Distributor Details
								$where_3  = array('id' => $dis_id);
								$column_3 = 'company_name, mobile, gst_no, address, state_id';
								$dis_data = $this->distributors_model->getDistributors($where_3, '', '', 'result', '', '', '', '', $column_3);

								$com_name  = !empty($dis_data[0]->company_name)?$dis_data[0]->company_name:'';
					            $mobile    = !empty($dis_data[0]->mobile)?$dis_data[0]->mobile:'';
					            $gst_no    = !empty($dis_data[0]->gst_no)?$dis_data[0]->gst_no:'';
					            $address   = !empty($dis_data[0]->address)?$dis_data[0]->address:'';
					            $dis_state = !empty($dis_data[0]->state_id)?$dis_data[0]->state_id:'';

								// State Details
								$where_4  = array('id' => $dis_state);
								$column_4 = 'gst_code, state_name';
								$gst_data = $this->commom_model->getState($where_4, '', '', 'result', '', '', '', '', $column_4);

								$state_name = !empty($gst_data[0]->state_name)?$gst_data[0]->state_name:'';
								$gst_code   = !empty($gst_data[0]->gst_code)?$gst_data[0]->gst_code:'';

								// Product Details
								$where_5  = array('id' => $type_id);
								$column_5 = 'description';
								$pdt_data = $this->commom_model->getProductType($where_5, '', '', 'result', '', '', '', '', $column_5);

								$description  = !empty($pdt_data[0]->description)?$pdt_data[0]->description:'';

								$where_6  = array('id' => '1');
								$column_6 = 'state_id';
								$adm_data = $this->user_model->getUser($where_6, '', '', 'result', '', '', '', '', $column_6);

								$adm_state = !empty($adm_data[0]->state_id)?$adm_data[0]->state_id:'';

								// Order Details
								$where_7  = array('id' => $ord_id);
								$column_7 = 'po_no, _ordered';
								$pur_data = $this->distributorpurchase_model->getDistributorPurchase($where_7, '', '', 'result', '', '', '', '', $column_7);

								$pur_num  = !empty($pur_data[0]->po_no)?$pur_data[0]->po_no:'';
								$pur_date = !empty($pur_data[0]->_ordered)?$pur_data[0]->_ordered:'';

								$sales_details[] = array(
									'adm_state'    => $adm_state,
									'invoice_no'   => $invoice_no,
									'order_no'     => $pur_num,
									'order_date'   => $pur_date,
									'dis_name'     => $com_name,
									'mobile'       => $mobile,
									'gst_no'       => $gst_no,
									'address'      => $address,
									'state_id'     => $dis_state,
									'state_name'   => $state_name,
									'gst_code'     => $gst_code,
									'invoice_date' => date('d-m-Y', strtotime($ord_date)),
									'description'  => $description,
									'hsn_code'     => $hsn_code,
									'gst_val'      => $gst_val,
									'price'        => $price,
									'order_qty'    => $order_qty,
								);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $sales_details;
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

			else if($method == '_overallSalesReturnReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'published'     => '1',
						);

						if(!empty($distributor_id))
						{
							$where_1['distributor_id'] = $distributor_id;
						}

						$column_1 = 'id, return_no, invoice_id, distributor_name, random_value, createdate';
						$result_1 = $this->return_model->getDistributorReturn($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($result_1)
						{
							$data_list = [];
							foreach ($result_1 as $key => $val_1) {

								$ret_id   = !empty($val_1->id)?$val_1->id:'';
								$ret_no   = !empty($val_1->return_no)?$val_1->return_no:'';
					            $inv_id   = !empty($val_1->invoice_id)?$val_1->invoice_id:'';
					            $dis_name = !empty($val_1->distributor_name)?$val_1->distributor_name:'';
					            $rand_val = !empty($val_1->random_value)?$val_1->random_value:'';
					            $cre_date = !empty($val_1->createdate)?$val_1->createdate:'';

					            // Invoice Details
					            $whr_2 = array('id' => $inv_id);
					            $col_2 = 'invoice_no, random_value';
					            $res_2 = $this->invoice_model->getDistributorInvoice($whr_2, '', '', 'result', '', '', '', '', $col_2);

					            $inv_no     = !empty($res_2[0]->invoice_no)?$res_2[0]->invoice_no:'';
					            $inv_random = !empty($res_2[0]->random_value)?$res_2[0]->random_value:'';

					            // Return Details
					            $whr_3 = array('return_id' => $ret_id, 'published' => '1');
					            $col_3 = 'price, return_qty';
					            $res_3 = $this->return_model->getDistributorReturnDetails($whr_3, '', '', 'result', '', '', '', '', $col_3);

					            $ret_tot = 0;
					            if($res_3)
					            {
					            	foreach ($res_3 as $key => $val_3) {
					            		$price_val = !empty($val_3->price)?$val_3->price:'0';
            							$ret_qty   = !empty($val_3->return_qty)?$val_3->return_qty:'0';
            							$tot_val   = $ret_qty * $price_val;
            							$ret_tot  += $tot_val;
					            	}
					            }

					            $data_list[] = array(
					            	'return_no'        => $ret_no,
					            	'distributor_name' => $dis_name,
					            	'invoice_no'       => $inv_no,
					            	'invoice_random'   => $inv_random,
					            	'return_value'     => strval(round($ret_tot)),
					            	'return_random'    => $rand_val,
					            	'return_date'      => date('d-M-Y', strtotime($cre_date)),
					            );
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
					        $response['message'] = "Data Not Found"; 
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

			else if($method == '_overallSalesReturnDetails')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'published'     => '1',
						);

						if(!empty($distributor_id))
						{
							$where_1['distributor_id'] = $distributor_id;
						}

						$column_1 = 'id, return_no, invoice_id, distributor_id, type_id, hsn_code, gst_val, unit_val, price, return_qty, createdate';
						$result_1 = $this->return_model->getDistributorReturnDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($result_1)
						{
							$data_list = [];
							foreach ($result_1 as $key => $val_1) {
									
								$auto_id     = !empty($val_1->id)?$val_1->id:'';
							    $return_no   = !empty($val_1->return_no)?$val_1->return_no:'';
							    $invoice_id  = !empty($val_1->invoice_id)?$val_1->invoice_id:'';
							    $dis_value   = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
							    $type_id     = !empty($val_1->type_id)?$val_1->type_id:'';
							    $hsn_code    = !empty($val_1->hsn_code)?$val_1->hsn_code:'';
							    $gst_val     = !empty($val_1->gst_val)?$val_1->gst_val:'';
							    $unit_val    = !empty($val_1->unit_val)?$val_1->unit_val:'';
							    $price_val   = !empty($val_1->price)?$val_1->price:'';
							    $return_qty  = !empty($val_1->return_qty)?$val_1->return_qty:'';
							    $return_date = !empty($val_1->createdate)?$val_1->createdate:'';

							    // Invoice Details
					            $whr_2 = array('id' => $invoice_id);
					            $col_2 = 'invoice_no, date';
					            $res_2 = $this->invoice_model->getDistributorInvoice($whr_2, '', '', 'result', '', '', '', '', $col_2);

					            $inv_no   = !empty($res_2[0]->invoice_no)?$res_2[0]->invoice_no:'';
					            $inv_date = !empty($res_2[0]->date)?$res_2[0]->date:'';

					            // Distributor Details
					            $whr_3 = array('id' => $dis_value);
					            $col_3 = 'company_name, state_id, gst_no';
					            $res_3 = $this->distributors_model->getDistributors($whr_3, '', '', 'result', '', '', '', '', $col_3);

					            $dis_name   = !empty($res_3[0]->company_name)?$res_3[0]->company_name:'';
					            $state_id   = !empty($res_3[0]->state_id)?$res_3[0]->state_id:'';
					            $gst_number = !empty($res_3[0]->gst_no)?$res_3[0]->gst_no:'';

					            // State Details
					            $whr_4 = array('id' => $state_id);
					            $col_4 = 'state_name';
					            $res_4 = $this->commom_model->getState($whr_4, '', '', 'result', '', '', '', '', $col_4);

					            $dis_state = !empty($res_4[0]->state_name)?$res_4[0]->state_name:'';

					            // Admin Details
					            $whr_5 = array('id' => '1');
					            $col_5 = 'state_id';
					            $res_5 = $this->login_model->getLoginStatus($whr_5, '', '', 'result', '', '', '', '', $col_5);

					            $adm_state = !empty($res_5[0]->state_id)?$res_5[0]->state_id:'';


					            // Product Type Details
					            $whr_6 = array('id' => $type_id);
					            $col_6 = 'description';
					            $res_6 = $this->commom_model->getProductType($whr_6, '', '', 'result', '', '', '', '', $col_6);

					            $pdt_desc = !empty($res_6[0]->description)?$res_6[0]->description:'';

					            $data_list[] = array(
					            	'return_no'   => $return_no,
					            	'return_date' => date('d-m-Y', strtotime($return_date)),
					            	'inv_no'      => $inv_no,
					            	'inv_date'    => date('d-m-Y', strtotime($inv_date)),
					            	'dis_name'    => $dis_name,
					            	'gst_number'  => $gst_number,
					            	'adm_state'   => $adm_state,
					            	'state_id'    => $state_id,
					            	'dis_state'   => $dis_state,
					            	'pdt_desc'    => $pdt_desc,
					            	'hsn_code'    => $hsn_code,
					            	'return_qty'  => $return_qty,
					            	'gst_val'     => $gst_val,
					            	'price_val'   => $price_val,
					            );
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
					        $response['message'] = "Data Not Found"; 
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

			else if($method == '_overallOutletSalesReturnReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >='  => $start_value,
							'createdate <='  => $end_value,
							'distributor_id' => $distributor_id,
							'published'      => '1',
						);

						$column_1 = 'id, return_no, invoice_id, store_name, random_value, createdate';
						$result_1 = $this->return_model->getOutletReturn($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($result_1)
						{
							$data_list = [];
							foreach ($result_1 as $key => $val_1) {

								$ret_id   = !empty($val_1->id)?$val_1->id:'';
								$ret_no   = !empty($val_1->return_no)?$val_1->return_no:'';
					            $inv_id   = !empty($val_1->invoice_id)?$val_1->invoice_id:'';
					            $str_name = !empty($val_1->store_name)?$val_1->store_name:'';
					            $rand_val = !empty($val_1->random_value)?$val_1->random_value:'';
					            $cre_date = !empty($val_1->createdate)?$val_1->createdate:'';

					            // Invoice Details
					            $whr_2 = array('id' => $inv_id);
					            $col_2 = 'invoice_no, random_value';
					            $res_2 = $this->invoice_model->getInvoice($whr_2, '', '', 'result', '', '', '', '', $col_2);

					            $inv_no     = !empty($res_2[0]->invoice_no)?$res_2[0]->invoice_no:'';
					            $inv_random = !empty($res_2[0]->random_value)?$res_2[0]->random_value:'';

					            // Return Details
					            $whr_3 = array('return_id' => $ret_id, 'published' => '1');
					            $col_3 = 'price, return_qty';
					            $res_3 = $this->return_model->getOutletReturnDetails($whr_3, '', '', 'result', '', '', '', '', $col_3);

					            $ret_tot = 0;
					            if($res_3)
					            {
					            	foreach ($res_3 as $key => $val_3) {
					            		$price_val = !empty($val_3->price)?$val_3->price:'0';
            							$ret_qty   = !empty($val_3->return_qty)?$val_3->return_qty:'0';
            							$tot_val   = $ret_qty * $price_val;
            							$ret_tot  += $tot_val;
					            	}
					            }

					            $data_list[] = array(
					            	'return_no'      => $ret_no,
					            	'store_name'     => $str_name,
					            	'invoice_no'     => $inv_no,
					            	'invoice_random' => $inv_random,
					            	'return_value'   => strval(round($ret_tot)),
					            	'return_random'  => $rand_val,
					            	'return_date'    => date('d-M-Y', strtotime($cre_date)),
					            );
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
					        $response['message'] = "Data Not Found"; 
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

			else if($method == '_overallOutletSalesReturnDetails')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >='  => $start_value,
							'createdate <='  => $end_value,
							'distributor_id' => $distributor_id,
							'published'      => '1',
						);

						$column_1 = 'id, return_no, invoice_id, distributor_id, outlet_id, type_id, hsn_code, gst_val, unit_val, price, return_qty, createdate';
						$result_1 = $this->return_model->getOutletReturnDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($result_1)
						{
							$data_list = [];
							foreach ($result_1 as $key => $val_1) {

								$auto_id     = !empty($val_1->id)?$val_1->id:'';
							    $return_no   = !empty($val_1->return_no)?$val_1->return_no:'';
							    $invoice_id  = !empty($val_1->invoice_id)?$val_1->invoice_id:'';
							    $dis_value   = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
							    $outlet_id   = !empty($val_1->outlet_id)?$val_1->outlet_id:'';
							    $type_id     = !empty($val_1->type_id)?$val_1->type_id:'';
							    $hsn_code    = !empty($val_1->hsn_code)?$val_1->hsn_code:'';
							    $gst_val     = !empty($val_1->gst_val)?$val_1->gst_val:'';
							    $unit_val    = !empty($val_1->unit_val)?$val_1->unit_val:'';
							    $price_val   = !empty($val_1->price)?$val_1->price:'';
							    $return_qty  = !empty($val_1->return_qty)?$val_1->return_qty:'';
							    $return_date = !empty($val_1->createdate)?$val_1->createdate:'';

							    // Invoice Details
					            $whr_2 = array('id' => $invoice_id);
					            $col_2 = 'invoice_no, date';
					            $res_2 = $this->invoice_model->getInvoice($whr_2, '', '', 'result', '', '', '', '', $col_2);

					            $inv_no   = !empty($res_2[0]->invoice_no)?$res_2[0]->invoice_no:'';
					            $inv_date = !empty($res_2[0]->date)?$res_2[0]->date:'';

					            // Distributor Details
					            $whr_3 = array('id' => $dis_value);
					            $col_3 = 'company_name, state_id, gst_no';
					            $res_3 = $this->distributors_model->getDistributors($whr_3, '', '', 'result', '', '', '', '', $col_3);

					            $dis_name   = !empty($res_3[0]->company_name)?$res_3[0]->company_name:'';
					            $state_id   = !empty($res_3[0]->state_id)?$res_3[0]->state_id:'';
					            $gst_number = !empty($res_3[0]->gst_no)?$res_3[0]->gst_no:'';

					            // Outlet Details
					            $whr_4 = array('id' => $outlet_id);
					            $col_4 = 'company_name, state_id, gst_no';
					            $res_4 = $this->outlets_model->getOutlets($whr_4, '', '', 'result', '', '', '', '', $col_4);

					            $str_name  = !empty($res_4[0]->company_name)?$res_4[0]->company_name:'';
					            $str_state = !empty($res_4[0]->state_id)?$res_4[0]->state_id:'';
					            $str_gst   = !empty($res_4[0]->gst_no)?$res_4[0]->gst_no:'';

					            // State Details
					            $whr_5 = array('id' => $str_state);
					            $col_5 = 'state_name';
					            $res_5 = $this->commom_model->getState($whr_5, '', '', 'result', '', '', '', '', $col_5);

					            $std_val = !empty($res_5[0]->state_name)?$res_5[0]->state_name:'';

					            // Product Type Details
					            $whr_6 = array('id' => $type_id);
					            $col_6 = 'description';
					            $res_6 = $this->commom_model->getProductType($whr_6, '', '', 'result', '', '', '', '', $col_6);

					            $pdt_desc = !empty($res_6[0]->description)?$res_6[0]->description:'';

					            $data_list[] = array(
					            	'return_no'   => $return_no,
					            	'return_date' => date('d-m-Y', strtotime($return_date)),
					            	'inv_no'      => $inv_no,
					            	'inv_date'    => date('d-m-Y', strtotime($inv_date)),
					            	'str_name'    => $str_name,
					            	'gst_number'  => $str_gst,
					            	'dis_state'   => $state_id,
					            	'state_id'    => $str_state,
					            	'str_state'   => $std_val,
					            	'pdt_desc'    => $pdt_desc,
					            	'hsn_code'    => $hsn_code,
					            	'return_qty'  => $return_qty,
					            	'gst_val'     => $gst_val,
					            	'price_val'   => $price_val,
					            );
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
					        $response['message'] = "Data Not Found"; 
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
		// Attendace Overall Report
		// ***************************************************
		public function attendace_report($param1="",$param2="",$param3="")
		{
			$method         = $this->input->post('method');
			$start_date     = $this->input->post('start_date');
			$end_date       = $this->input->post('end_date');
			$distributor_id = $this->input->post('distributor_id');
			$employee_id    = $this->input->post('employee_id');
			$emp_type       = $this->input->post('emp_type');
			$mg_id          = $this->input->post('mg_id');

			if($method == '_attendanceReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date','emp_type');
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
						
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));
						 

				    	$where = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'emp_type'      => $emp_type,
							'published'     => '1',
						);

						if(!empty($employee_id))
						{
							$where['emp_id'] = $employee_id;
						}

						$att_data = $this->attendance_model->getAttendance_details($where);

						if(!empty($att_data))
						{
							$attendance_list = [];
							foreach ($att_data as $key => $val_1) {
								$att_id     = !empty($val_1->id)?$val_1->id:'';
								$emp_id     = !empty($val_1->emp_id)?$val_1->emp_id:'';
								$emp_name   = !empty($val_1->emp_name)?$val_1->emp_name:'';
								$emp_type   = !empty($val_1->emp_type)?$val_1->emp_type:'';
								$store_id   = !empty($val_1->store_id)?$val_1->store_id:'';
								$store_name = !empty($val_1->store_name)?$val_1->store_name:'';
								$att_type   = !empty($val_1->attendance_type)?$val_1->attendance_type:'';
								$reason     = !empty($val_1->reason)?$val_1->reason:'';
								$c_date     = !empty($val_1->c_date)?$val_1->c_date:'';
								$in_time    = !empty($val_1->in_time)?$val_1->in_time:'';
								$out_time   = !empty($val_1->out_time)?$val_1->out_time:'';
								$c_image    = !empty($val_1->c_image)?$val_1->c_image:'';

								// if($att_type == 1)
								// {
								// 	$ord_whr = array(
								// 		'createdate >=' => $start_value,
								// 		'createdate <=' => $end_value,
								// 		'emp_id' => $emp_id
								// 	);
								// 	$ord_col = 'id, order_no';
								// 	$ord_val = $this->order_model->getOrder($ord_whr, '', '', 'result', '', '', '', '', $ord_col);

								// 	if($ord_val)
								// 	{
								// 		$ord_id  = !empty($ord_val[0]->id)?$ord_val[0]->id:'';
								// 		$ord_num = !empty($ord_val[0]->order_no)?$ord_val[0]->order_no:'';
										
								// 		// Order Details
								// 		$ordDet_whr = array('order_id' => $ord_id);
								// 		$ordDet_col = 'price, order_qty';
								// 		$ordDet_val = $this->order_model->getOrderDetails($ordDet_whr, '', '', 'result', '', '', '', '', $ordDet_col);

								// 		$ord_tot = 0;
								// 		if($ordDet_val)
								// 		{
								// 			$order_tot = 0;
								// 			foreach ($ordDet_val as $key => $val_2) {
								// 				$price      = !empty($val_2->price)?$val_2->price:'0';
						        //     			$order_qty  = !empty($val_2->order_qty)?$val_2->order_qty:'0';
						        //     			$order_val  = $price * $order_qty;
						        //     			$order_tot += $order_val;
								// 			}

								// 			$ord_tot += round($order_tot);
								// 		}
								// 	}
								// 	else
								// 	{
								// 		$ord_num = '';
								// 		$ord_tot = '0';
								// 	}
								// }
								// else
								// {
								// 	$ord_num = '';
								// 	$ord_tot = '0';
								// }

								$attendance_list[] = array(
									'att_id'     => $att_id,
									'emp_id'     => $emp_id,
									'emp_name'   => $emp_name,
									'emp_type'   => $emp_type,
									'store_id'   => $store_id,
									'store_name' => $store_name,
									'att_type'   => $att_type,
									'reason'     => $reason,
									'c_date'     => $c_date,
									'c_image'    => $c_image,
									'in_time'    => $in_time,
									'out_time'   => $out_time,
									// 'order_num'  => $ord_num,
									// 'order_tot'  => strval($ord_tot),
								);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $attendance_list;
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
			
			else if($method == '_deliveryAttendanceReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where = array(
							'A.createdate >=' => $start_value,
							'A.createdate <=' => $end_value,
							'B.company_id'    => $distributor_id,
							'A.published'     => '1',
						);

						if(!empty($employee_id))
						{
							$where['A.emp_id'] = $employee_id;
						}

						$column = 'A.id, A.emp_name, A.store_name, D.name AS beat_name, E.order_no, E.date AS order_date, F.invoice_no, F.date AS invoice_date, A.c_date AS delivery_date, A.in_time, A.out_time';
						$result = $this->attendance_model->getAttendanceJoin($where, '', '', 'result', '', '', '', '', $column);

						if(!empty($result))
						{
							$attendance_list = [];
							foreach ($result as $key => $val) {

								$attendance_list[] = array(
									'att_id'        => !empty($val->id)?$val->id:'',
						            'emp_name'      => !empty($val->emp_name)?$val->emp_name:'',
						            'store_name'    => !empty($val->store_name)?$val->store_name:'',
						            'beat_name'     => !empty($val->beat_name)?$val->beat_name:'',
						            'order_no'      => !empty($val->order_no)?$val->order_no:'',
						            'order_date'    => date_check($val->order_date),
						            'invoice_no'    => !empty($val->invoice_no)?$val->invoice_no:'',
						            'invoice_date'  => date_check($val->invoice_date),
						            'delivery_date' => date_check($val->delivery_date),
						            'in_time'       => time_check($val->in_time),
						            'out_time'      => time_check($val->out_time),
								);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $attendance_list;
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

		public function attendace_details_report($param1="",$param2="",$param3="")
		{
			$method         = $this->input->post('method');
			$start_date     = $this->input->post('start_date');
			$end_date       = $this->input->post('end_date');
			$distributor_id = $this->input->post('distributor_id');
			$employee_id    = $this->input->post('employee_id');
			$emp_type       = $this->input->post('emp_type');
			$mg_id          = $this->input->post('mg_id');


			if($method == '_attendanceDetailsReport')
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
						
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'emp_type'      => $emp_type,
							'published'     => '1',
						);

						if(!empty($employee_id))
						{
							$where['emp_id'] = $employee_id;
						}

						$att_data = $this->attendance_model->getAttendance($where);

						if(!empty($att_data))
						{
							$attendance_list = [];
							foreach ($att_data as $key => $val_1) {
								$att_id     = !empty($val_1->id)?$val_1->id:'';
								$emp_id     = !empty($val_1->emp_id)?$val_1->emp_id:'';
								$emp_name   = !empty($val_1->emp_name)?$val_1->emp_name:'';
								$emp_type   = !empty($val_1->emp_type)?$val_1->emp_type:'';
								$store_id   = !empty($val_1->store_id)?$val_1->store_id:'';
								$store_name = !empty($val_1->store_name)?$val_1->store_name:'';
								$att_type   = !empty($val_1->attendance_type)?$val_1->attendance_type:'';
								$reason     = !empty($val_1->reason)?$val_1->reason:'';
								$c_date     = !empty($val_1->c_date)?$val_1->c_date:'';
								$in_time    = !empty($val_1->in_time)?$val_1->in_time:'';
								$out_time   = !empty($val_1->out_time)?$val_1->out_time:'';
								$c_image     = !empty($val_1->c_image)?$val_1->c_image:'';

								if($att_type == 1)
								{
									$ord_whr = array('att_id' => $att_id);
									$ord_col = 'id, order_no';
									$ord_val = $this->order_model->getOrder($ord_whr, '', '', 'result', '', '', '', '', $ord_col);

									if($ord_val)
									{
										$ord_id  = !empty($ord_val[0]->id)?$ord_val[0]->id:'';
										$ord_num = !empty($ord_val[0]->order_no)?$ord_val[0]->order_no:'';
										
										// Order Details
										$ordDet_whr = array('order_id' => $ord_id);
										$ordDet_col = 'price, order_qty';
										$ordDet_val = $this->order_model->getOrderDetails($ordDet_whr, '', '', 'result', '', '', '', '', $ordDet_col);

										$ord_tot = 0;
										if($ordDet_val)
										{
											$order_tot = 0;
											foreach ($ordDet_val as $key => $val_2) {
												$price      = !empty($val_2->price)?$val_2->price:'0';
						            			$order_qty  = !empty($val_2->order_qty)?$val_2->order_qty:'0';
						            			$order_val  = $price * $order_qty;
						            			$order_tot += $order_val;
											}

											$ord_tot += round($order_tot);
										}
									}
									else
									{
										$ord_num = '';
										$ord_tot = '0';
									}
								}
								else
								{
									$ord_num = '';
									$ord_tot = '0';
								}

								$attendance_list[] = array(
									'att_id'     => $att_id,
									'emp_id'     => $emp_id,
									'emp_name'   => $emp_name,
									'emp_type'   => $emp_type,
									'store_id'   => $store_id,
									'store_name' => $store_name,
									'att_type'   => $att_type,
									'reason'     => $reason,
									'c_image'    => $c_image,
									'c_date'     => $c_date,
									'in_time'    => $in_time,
									'out_time'   => $out_time,
									'order_num'  => $ord_num,
									'order_tot'  => strval($ord_tot),
								);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $attendance_list;
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

			else if($method == '_attendanceReportMg')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date','emp_type','mg_id');
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
						if(empty($employee_id)){
							$wr_mg = array(
								'employee_id' => $mg_id,
								'published'   => '1',
								'status'      => '1',
							);
							$data  = $this->managers_model->getManagers($wr_mg);
						
							if($data)
							{	
		
								$did_list = [];
								foreach ($data as $key => $value) {
									$employee_id     = !empty($value->employee_id)?$value->employee_id:'';
									$designation_code            = !empty($value->designation_code)?$value->designation_code:'';
									$ctrl_city_id         = !empty($value->ctrl_city_id)?$value->ctrl_city_id:'';
									$ctrl_zone_id          = !empty($value->ctrl_zone_id)?$value->ctrl_zone_id:'';
									$ctrl_state_id            = !empty($value->ctrl_state_id)?$value->ctrl_state_id:'';
									
									if($designation_code == 'RSM'){
										if(!empty($ctrl_state_id)){
											$state_id_finall = substr($ctrl_state_id,1,-1);
											
							
											$d_state = !empty($state_id_finall)?$state_id_finall:'';
									
											$d_state_val = explode(',', $d_state);
											$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
											$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));
											
					
											$where = array(
												'createdate >=' => $start_value,
												'createdate <=' => $end_value,
												'emp_type'      => $emp_type,
												'published'     => '1',
											);
					
											
											$where_in['state_id'] = $d_state_val;
											
					
											$att_data = $this->attendance_model->getAttendance_details($where,'','',"result",'','','','','','', $where_in);
					
											if(!empty($att_data))
											{
												$attendance_list = [];
												foreach ($att_data as $key => $val_1) {
													$att_id     = !empty($val_1->id)?$val_1->id:'';
													$emp_id     = !empty($val_1->emp_id)?$val_1->emp_id:'';
													$emp_name   = !empty($val_1->emp_name)?$val_1->emp_name:'';
													$emp_type   = !empty($val_1->emp_type)?$val_1->emp_type:'';
													$store_id   = !empty($val_1->store_id)?$val_1->store_id:'';
													$store_name = !empty($val_1->store_name)?$val_1->store_name:'';
													$att_type   = !empty($val_1->attendance_type)?$val_1->attendance_type:'';
													$reason     = !empty($val_1->reason)?$val_1->reason:'';
													$c_date     = !empty($val_1->c_date)?$val_1->c_date:'';
													$in_time    = !empty($val_1->in_time)?$val_1->in_time:'';
													$out_time   = !empty($val_1->out_time)?$val_1->out_time:'';
													$c_image    = !empty($val_1->c_image)?$val_1->c_image:'';
													
					
													$attendance_list[] = array(
														'att_id'     => $att_id,
														'emp_id'     => $emp_id,
														'emp_name'   => $emp_name,
														'emp_type'   => $emp_type,
														'store_id'   => $store_id,
														'store_name' => $store_name,
														'att_type'   => $att_type,
														'reason'     => $reason,
														'c_date'     => $c_date,
														'c_image'    => $c_image,
														'in_time'    => $in_time,
														'out_time'   => $out_time,
														// 'order_num'  => $ord_num,
														// 'order_tot'  => strval($ord_tot),
													);
												}
					
												$response['status']  = 1;
												$response['message'] = "Success"; 
												$response['data']    = $attendance_list;
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
									}else if($designation_code == 'ASM' || $designation_code == 'SO'){
										if(!empty($ctrl_city_id)){
											$ct_id_finall = substr($ctrl_city_id,1,-1);
											
							
											$d_ct = !empty($ct_id_finall)?$ct_id_finall:'';
									
											$d_ct_val = explode(',', $d_ct);
											$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
											$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));
											
					
											$where = array(
												'createdate >=' => $start_value,
												'createdate <=' => $end_value,
												'emp_type'      => $emp_type,
												'published'     => '1',
											);
					
											
											$where_in['city_id'] = $d_ct_val;
											
					
											$att_data = $this->attendance_model->getAttendance_details($where,'','',"result",'','','','','','', $where_in);
											
											if(!empty($att_data))
											{
												$attendance_list = [];
												foreach ($att_data as $key => $val_1) {
													$att_id     = !empty($val_1->id)?$val_1->id:'';
													$emp_id     = !empty($val_1->emp_id)?$val_1->emp_id:'';
													$emp_name   = !empty($val_1->emp_name)?$val_1->emp_name:'';
													$emp_type   = !empty($val_1->emp_type)?$val_1->emp_type:'';
													$store_id   = !empty($val_1->store_id)?$val_1->store_id:'';
													$store_name = !empty($val_1->store_name)?$val_1->store_name:'';
													$att_type   = !empty($val_1->attendance_type)?$val_1->attendance_type:'';
													$reason     = !empty($val_1->reason)?$val_1->reason:'';
													$c_date     = !empty($val_1->c_date)?$val_1->c_date:'';
													$in_time    = !empty($val_1->in_time)?$val_1->in_time:'';
													$out_time   = !empty($val_1->out_time)?$val_1->out_time:'';
					
													
					
													$attendance_list[] = array(
														'att_id'     => $att_id,
														'emp_id'     => $emp_id,
														'emp_name'   => $emp_name,
														'emp_type'   => $emp_type,
														'store_id'   => $store_id,
														'store_name' => $store_name,
														'att_type'   => $att_type,
														'reason'     => $reason,
														'c_date'     => $c_date,
														'in_time'    => $in_time,
														'out_time'   => $out_time,
														// 'order_num'  => $ord_num,
														// 'order_tot'  => strval($ord_tot),
													);
												}
					
												$response['status']  = 1;
												$response['message'] = "Success"; 
												$response['data']    = $attendance_list;
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
									}else if($designation_code == 'TSI'){
										if(!empty($ctrl_zone_id)){
											$zn_id_finall = substr($ctrl_zone_id,1,-1);
											
							
											$d_zn = !empty($zn_id_finall)?$zn_id_finall:'';
									
											$d_zn_val = explode(',', $d_zn);
											$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
											$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));
											
					
											$where = array(
												'createdate >=' => $start_value,
												'createdate <=' => $end_value,
												'emp_type'      => $emp_type,
												'published'     => '1',
											);
					
											
											$where_in['zone_id'] = $d_zn_val;
											
					
											$att_data = $this->attendance_model->getAttendance_details($where,'','',"result",'','','','','','', $where_in);
											
											if(!empty($att_data))
											{
												$attendance_list = [];
												foreach ($att_data as $key => $val_1) {
													$att_id     = !empty($val_1->id)?$val_1->id:'';
													$emp_id     = !empty($val_1->emp_id)?$val_1->emp_id:'';
													$emp_name   = !empty($val_1->emp_name)?$val_1->emp_name:'';
													$emp_type   = !empty($val_1->emp_type)?$val_1->emp_type:'';
													$store_id   = !empty($val_1->store_id)?$val_1->store_id:'';
													$store_name = !empty($val_1->store_name)?$val_1->store_name:'';
													$att_type   = !empty($val_1->attendance_type)?$val_1->attendance_type:'';
													$reason     = !empty($val_1->reason)?$val_1->reason:'';
													$c_date     = !empty($val_1->c_date)?$val_1->c_date:'';
													$in_time    = !empty($val_1->in_time)?$val_1->in_time:'';
													$out_time   = !empty($val_1->out_time)?$val_1->out_time:'';
					
													
					
													$attendance_list[] = array(
														'att_id'     => $att_id,
														'emp_id'     => $emp_id,
														'emp_name'   => $emp_name,
														'emp_type'   => $emp_type,
														'store_id'   => $store_id,
														'store_name' => $store_name,
														'att_type'   => $att_type,
														'reason'     => $reason,
														'c_date'     => $c_date,
														'in_time'    => $in_time,
														'out_time'   => $out_time,
														// 'order_num'  => $ord_num,
														// 'order_tot'  => strval($ord_tot),
													);
												}
					
												$response['status']  = 1;
												$response['message'] = "Success"; 
												$response['data']    = $attendance_list;
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
								}
							}
						}else{
								$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
							$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));
							

							$where = array(
								'createdate >=' => $start_value,
								'createdate <=' => $end_value,
								'emp_type'      => $emp_type,
								'published'     => '1',
							);

							if(!empty($employee_id))
							{
								$where['emp_id'] = $employee_id;
							}

							$att_data = $this->attendance_model->getAttendance_details($where);

							if(!empty($att_data))
							{
								$attendance_list = [];
								foreach ($att_data as $key => $val_1) {
									$att_id     = !empty($val_1->id)?$val_1->id:'';
									$emp_id     = !empty($val_1->emp_id)?$val_1->emp_id:'';
									$emp_name   = !empty($val_1->emp_name)?$val_1->emp_name:'';
									$emp_type   = !empty($val_1->emp_type)?$val_1->emp_type:'';
									$store_id   = !empty($val_1->store_id)?$val_1->store_id:'';
									$store_name = !empty($val_1->store_name)?$val_1->store_name:'';
									$att_type   = !empty($val_1->attendance_type)?$val_1->attendance_type:'';
									$reason     = !empty($val_1->reason)?$val_1->reason:'';
									$c_date     = !empty($val_1->c_date)?$val_1->c_date:'';
									$in_time    = !empty($val_1->in_time)?$val_1->in_time:'';
									$out_time   = !empty($val_1->out_time)?$val_1->out_time:'';

									

									$attendance_list[] = array(
										'att_id'     => $att_id,
										'emp_id'     => $emp_id,
										'emp_name'   => $emp_name,
										'emp_type'   => $emp_type,
										'store_id'   => $store_id,
										'store_name' => $store_name,
										'att_type'   => $att_type,
										'reason'     => $reason,
										'c_date'     => $c_date,
										'in_time'    => $in_time,
										'out_time'   => $out_time,
										
									);
								}

								$response['status']  = 1;
								$response['message'] = "Success"; 
								$response['data']    = $attendance_list;
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

			else if($method == '_attendanceDetailsReportMg')
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
						$mg_id = $this->input->post('mg_id');
						if(empty($employee_id)){
							$wr_mg = array(
								'employee_id' => $mg_id,
								'published'   => '1',
								'status'      => '1',
							);
							$data  = $this->managers_model->getManagers($wr_mg);
						
							if($data)
							{	
		
								$did_list = [];
								foreach ($data as $key => $value) {
									$employee_id     = !empty($value->employee_id)?$value->employee_id:'';
									$designation_code            = !empty($value->designation_code)?$value->designation_code:'';
									$ctrl_city_id         = !empty($value->ctrl_city_id)?$value->ctrl_city_id:'';
									$ctrl_zone_id          = !empty($value->ctrl_zone_id)?$value->ctrl_zone_id:'';
									$ctrl_state_id            = !empty($value->ctrl_state_id)?$value->ctrl_state_id:'';
									
									if($designation_code == 'RSM'){
										if(!empty($ctrl_state_id)){
											$state_id_finall = substr($ctrl_state_id,1,-1);
											
							
											$d_state = !empty($state_id_finall)?$state_id_finall:'';
									
											$d_state_val = explode(',', $d_state);
											$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
											$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));
											
					
											$where = array(
												'createdate >=' => $start_value,
												'createdate <=' => $end_value,
												'emp_type'      => $emp_type,
												'published'     => '1',
											);
					
											
											$where_in['state_id'] = $d_state_val;
											
					
											$att_data = $this->attendance_model->getAttendance($where,'','',"result",'','','','','','', $where_in);
					
											if(!empty($att_data))
											{
												$attendance_list = [];
												foreach ($att_data as $key => $val_1) {
													$att_id     = !empty($val_1->id)?$val_1->id:'';
													$emp_id     = !empty($val_1->emp_id)?$val_1->emp_id:'';
													$emp_name   = !empty($val_1->emp_name)?$val_1->emp_name:'';
													$emp_type   = !empty($val_1->emp_type)?$val_1->emp_type:'';
													$store_id   = !empty($val_1->store_id)?$val_1->store_id:'';
													$store_name = !empty($val_1->store_name)?$val_1->store_name:'';
													$att_type   = !empty($val_1->attendance_type)?$val_1->attendance_type:'';
													$reason     = !empty($val_1->reason)?$val_1->reason:'';
													$c_date     = !empty($val_1->c_date)?$val_1->c_date:'';
													$c_image     = !empty($val_1->c_image)?$val_1->c_image:'';
													$in_time    = !empty($val_1->in_time)?$val_1->in_time:'';
													$out_time   = !empty($val_1->out_time)?$val_1->out_time:'';
													$c_image     = !empty($val_1->c_image)?$val_1->c_image:'';
													if($att_type == 1)
													{
														$ord_whr = array('att_id' => $att_id);
														$ord_col = 'id, order_no';
														$ord_val = $this->order_model->getOrder($ord_whr, '', '', 'result', '', '', '', '', $ord_col);

														if($ord_val)
														{
															$ord_id  = !empty($ord_val[0]->id)?$ord_val[0]->id:'';
															$ord_num = !empty($ord_val[0]->order_no)?$ord_val[0]->order_no:'';
															
															// Order Details
															$ordDet_whr = array('order_id' => $ord_id);
															$ordDet_col = 'price, order_qty';
															$ordDet_val = $this->order_model->getOrderDetails($ordDet_whr, '', '', 'result', '', '', '', '', $ordDet_col);

															$ord_tot = 0;
															if($ordDet_val)
															{
																$order_tot = 0;
																foreach ($ordDet_val as $key => $val_2) {
																	$price      = !empty($val_2->price)?$val_2->price:'0';
																	$order_qty  = !empty($val_2->order_qty)?$val_2->order_qty:'0';
																	$order_val  = $price * $order_qty;
																	$order_tot += $order_val;
																}

																$ord_tot += round($order_tot);
															}
														}
														else
														{
															$ord_num = '';
															$ord_tot = '0';
														}
													}
													else
													{
														$ord_num = '';
														$ord_tot = '0';
													}
					
													$attendance_list[] = array(
														'att_id'     => $att_id,
														'emp_id'     => $emp_id,
														'emp_name'   => $emp_name,
														'emp_type'   => $emp_type,
														'store_id'   => $store_id,
														'store_name' => $store_name,
														'att_type'   => $att_type,
														'reason'     => $reason,
														'c_image'     => $c_image,
														'c_date'     => $c_date,
														'in_time'    => $in_time,
														'out_time'   => $out_time,
														'order_num'  => $ord_num,
														'order_tot'  => strval($ord_tot),
														'c_image'    => $c_image
													);
												}
					
												$response['status']  = 1;
												$response['message'] = "Success"; 
												$response['data']    = $attendance_list;
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
									}else if($designation_code == 'ASM' || $designation_code == 'SO'){
										if(!empty($ctrl_city_id)){
											$ct_id_finall = substr($ctrl_city_id,1,-1);
											
											
											$d_ct = !empty($ct_id_finall)?$ct_id_finall:'';
									
											$d_ct_val = explode(',', $d_ct);
											$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
											$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));
											
					
											$where = array(
												'createdate >=' => $start_value,
												'createdate <=' => $end_value,
												'emp_type'      => $emp_type,
												'published'     => '1',
											);
					
											
											$where_in['city_id'] = $d_ct_val;
											
					
											$att_data = $this->attendance_model->getAttendance($where,'','',"result",'','','','','','', $where_in);
											
											if(!empty($att_data))
											{
												$attendance_list = [];
												foreach ($att_data as $key => $val_1) {
													$att_id     = !empty($val_1->id)?$val_1->id:'';
													$emp_id     = !empty($val_1->emp_id)?$val_1->emp_id:'';
													$emp_name   = !empty($val_1->emp_name)?$val_1->emp_name:'';
													$emp_type   = !empty($val_1->emp_type)?$val_1->emp_type:'';
													$store_id   = !empty($val_1->store_id)?$val_1->store_id:'';
													$store_name = !empty($val_1->store_name)?$val_1->store_name:'';
													$att_type   = !empty($val_1->attendance_type)?$val_1->attendance_type:'';
													$reason     = !empty($val_1->reason)?$val_1->reason:'';
													$c_date     = !empty($val_1->c_date)?$val_1->c_date:'';
													$c_image     = !empty($val_1->c_image)?$val_1->c_image:'';
													$in_time    = !empty($val_1->in_time)?$val_1->in_time:'';
													$out_time   = !empty($val_1->out_time)?$val_1->out_time:'';
					
													if($att_type == 1)
													{
														$ord_whr = array('att_id' => $att_id);
														$ord_col = 'id, order_no';
														$ord_val = $this->order_model->getOrder($ord_whr, '', '', 'result', '', '', '', '', $ord_col);

														if($ord_val)
														{
															$ord_id  = !empty($ord_val[0]->id)?$ord_val[0]->id:'';
															$ord_num = !empty($ord_val[0]->order_no)?$ord_val[0]->order_no:'';
															
															// Order Details
															$ordDet_whr = array('order_id' => $ord_id);
															$ordDet_col = 'price, order_qty';
															$ordDet_val = $this->order_model->getOrderDetails($ordDet_whr, '', '', 'result', '', '', '', '', $ordDet_col);

															$ord_tot = 0;
															if($ordDet_val)
															{
																$order_tot = 0;
																foreach ($ordDet_val as $key => $val_2) {
																	$price      = !empty($val_2->price)?$val_2->price:'0';
																	$order_qty  = !empty($val_2->order_qty)?$val_2->order_qty:'0';
																	$order_val  = $price * $order_qty;
																	$order_tot += $order_val;
																}

																$ord_tot += round($order_tot);
															}
														}
														else
														{
															$ord_num = '';
															$ord_tot = '0';
														}
													}
													else
													{
														$ord_num = '';
														$ord_tot = '0';
													}
					
													$attendance_list[] = array(
														'att_id'     => $att_id,
														'emp_id'     => $emp_id,
														'emp_name'   => $emp_name,
														'emp_type'   => $emp_type,
														'store_id'   => $store_id,
														'store_name' => $store_name,
														'att_type'   => $att_type,
														'reason'     => $reason,
														'c_image'     => $c_image,
														'c_date'     => $c_date,
														'in_time'    => $in_time,
														'out_time'   => $out_time,
														'order_num'  => $ord_num,
														'order_tot'  => strval($ord_tot),
													);
												}
					
												$response['status']  = 1;
												$response['message'] = "Success"; 
												$response['data']    = $attendance_list;
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
									}else if($designation_code == 'TSI'){
										if(!empty($ctrl_zone_id)){
											$zn_id_finall = substr($ctrl_zone_id,1,-1);
											
							
											$d_zn = !empty($zn_id_finall)?$zn_id_finall:'';
									
											$d_zn_val = explode(',', $d_zn);
											$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
											$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));
											
					
											$where = array(
												'createdate >=' => $start_value,
												'createdate <=' => $end_value,
												'emp_type'      => $emp_type,
												'published'     => '1',
											);
					
											
											$where_in['zone_id'] = $d_zn_val;
											
					
											$att_data = $this->attendance_model->getAttendance($where,'','',"result",'','','','','','', $where_in);
											
											if(!empty($att_data))
											{
												$attendance_list = [];
												foreach ($att_data as $key => $val_1) {
													$att_id     = !empty($val_1->id)?$val_1->id:'';
													$emp_id     = !empty($val_1->emp_id)?$val_1->emp_id:'';
													$emp_name   = !empty($val_1->emp_name)?$val_1->emp_name:'';
													$emp_type   = !empty($val_1->emp_type)?$val_1->emp_type:'';
													$store_id   = !empty($val_1->store_id)?$val_1->store_id:'';
													$store_name = !empty($val_1->store_name)?$val_1->store_name:'';
													$att_type   = !empty($val_1->attendance_type)?$val_1->attendance_type:'';
													$reason     = !empty($val_1->reason)?$val_1->reason:'';
													$c_date     = !empty($val_1->c_date)?$val_1->c_date:'';
													$c_image     = !empty($val_1->c_image)?$val_1->c_image:'';
													$in_time    = !empty($val_1->in_time)?$val_1->in_time:'';
													$out_time   = !empty($val_1->out_time)?$val_1->out_time:'';
					
													if($att_type == 1)
													{
														$ord_whr = array('att_id' => $att_id);
														$ord_col = 'id, order_no';
														$ord_val = $this->order_model->getOrder($ord_whr, '', '', 'result', '', '', '', '', $ord_col);

														if($ord_val)
														{
															$ord_id  = !empty($ord_val[0]->id)?$ord_val[0]->id:'';
															$ord_num = !empty($ord_val[0]->order_no)?$ord_val[0]->order_no:'';
															
															// Order Details
															$ordDet_whr = array('order_id' => $ord_id);
															$ordDet_col = 'price, order_qty';
															$ordDet_val = $this->order_model->getOrderDetails($ordDet_whr, '', '', 'result', '', '', '', '', $ordDet_col);

															$ord_tot = 0;
															if($ordDet_val)
															{
																$order_tot = 0;
																foreach ($ordDet_val as $key => $val_2) {
																	$price      = !empty($val_2->price)?$val_2->price:'0';
																	$order_qty  = !empty($val_2->order_qty)?$val_2->order_qty:'0';
																	$order_val  = $price * $order_qty;
																	$order_tot += $order_val;
																}

																$ord_tot += round($order_tot);
															}
														}
														else
														{
															$ord_num = '';
															$ord_tot = '0';
														}
													}
													else
													{
														$ord_num = '';
														$ord_tot = '0';
													}
					
													$attendance_list[] = array(
														'att_id'     => $att_id,
														'emp_id'     => $emp_id,
														'emp_name'   => $emp_name,
														'emp_type'   => $emp_type,
														'store_id'   => $store_id,
														'store_name' => $store_name,
														'att_type'   => $att_type,
														'reason'     => $reason,
														'c_image'     => $c_image,
														'c_date'     => $c_date,
														'in_time'    => $in_time,
														'out_time'   => $out_time,
														'order_num'  => $ord_num,
														'order_tot'  => strval($ord_tot),
													);
												}
					
												$response['status']  = 1;
												$response['message'] = "Success"; 
												$response['data']    = $attendance_list;
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
								}
							}
						}else{
								$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
							$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

							$where = array(
								'createdate >=' => $start_value,
								'createdate <=' => $end_value,
								'emp_type'      => $emp_type,
								'emp_id'        => $employee_id,
								'published'     => '1',
							);

							
							$att_data = $this->attendance_model->getAttendance($where);

							if(!empty($att_data))
							{
								$attendance_list = [];
								foreach ($att_data as $key => $val_1) {
									$att_id     = !empty($val_1->id)?$val_1->id:'';
									$emp_id     = !empty($val_1->emp_id)?$val_1->emp_id:'';
									$emp_name   = !empty($val_1->emp_name)?$val_1->emp_name:'';
									$emp_type   = !empty($val_1->emp_type)?$val_1->emp_type:'';
									$store_id   = !empty($val_1->store_id)?$val_1->store_id:'';
									$store_name = !empty($val_1->store_name)?$val_1->store_name:'';
									$att_type   = !empty($val_1->attendance_type)?$val_1->attendance_type:'';
									$reason     = !empty($val_1->reason)?$val_1->reason:'';
									$c_date     = !empty($val_1->c_date)?$val_1->c_date:'';
									$c_image     = !empty($val_1->c_image)?$val_1->c_image:'';
									$in_time    = !empty($val_1->in_time)?$val_1->in_time:'';
									$out_time   = !empty($val_1->out_time)?$val_1->out_time:'';

									if($att_type == 1)
									{
										$ord_whr = array('att_id' => $att_id);
										$ord_col = 'id, order_no';
										$ord_val = $this->order_model->getOrder($ord_whr, '', '', 'result', '', '', '', '', $ord_col);

										if($ord_val)
										{
											$ord_id  = !empty($ord_val[0]->id)?$ord_val[0]->id:'';
											$ord_num = !empty($ord_val[0]->order_no)?$ord_val[0]->order_no:'';
											
											// Order Details
											$ordDet_whr = array('order_id' => $ord_id);
											$ordDet_col = 'price, order_qty';
											$ordDet_val = $this->order_model->getOrderDetails($ordDet_whr, '', '', 'result', '', '', '', '', $ordDet_col);

											$ord_tot = 0;
											if($ordDet_val)
											{
												$order_tot = 0;
												foreach ($ordDet_val as $key => $val_2) {
													$price      = !empty($val_2->price)?$val_2->price:'0';
													$order_qty  = !empty($val_2->order_qty)?$val_2->order_qty:'0';
													$order_val  = $price * $order_qty;
													$order_tot += $order_val;
												}

												$ord_tot += round($order_tot);
											}
										}
										else
										{
											$ord_num = '';
											$ord_tot = '0';
										}
									}
									else
									{
										$ord_num = '';
										$ord_tot = '0';
									}

									$attendance_list[] = array(
										'att_id'     => $att_id,
										'emp_id'     => $emp_id,
										'emp_name'   => $emp_name,
										'emp_type'   => $emp_type,
										'store_id'   => $store_id,
										'store_name' => $store_name,
										'att_type'   => $att_type,
										'reason'     => $reason,
										'c_date'     => $c_date,
										'c_image'    => $c_image,
										'in_time'    => $in_time,
										'out_time'   => $out_time,
										'order_num'  => $ord_num,
										'order_tot'  => strval($ord_tot),
									);
								}

								$response['status']  = 1;
								$response['message'] = "Success"; 
								$response['data']    = $attendance_list;
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

			else if($method == '_deliveryAttendanceReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where = array(
							'A.createdate >=' => $start_value,
							'A.createdate <=' => $end_value,
							'B.company_id'    => $distributor_id,
							'A.published'     => '1',
						);

						if(!empty($employee_id))
						{
							$where['A.emp_id'] = $employee_id;
						}

						$column = 'A.id, A.emp_name, A.store_name, D.name AS beat_name, E.order_no, E.date AS order_date, F.invoice_no, F.date AS invoice_date, A.c_date AS delivery_date, A.in_time, A.out_time';
						$result = $this->attendance_model->getAttendanceJoin($where, '', '', 'result', '', '', '', '', $column);

						if(!empty($result))
						{
							$attendance_list = [];
							foreach ($result as $key => $val) {

								$attendance_list[] = array(
									'att_id'        => !empty($val->id)?$val->id:'',
						            'emp_name'      => !empty($val->emp_name)?$val->emp_name:'',
						            'store_name'    => !empty($val->store_name)?$val->store_name:'',
						            'beat_name'     => !empty($val->beat_name)?$val->beat_name:'',
						            'order_no'      => !empty($val->order_no)?$val->order_no:'',
						            'order_date'    => date_check($val->order_date),
						            'invoice_no'    => !empty($val->invoice_no)?$val->invoice_no:'',
						            'invoice_date'  => date_check($val->invoice_date),
						            'delivery_date' => date_check($val->delivery_date),
						            'in_time'       => time_check($val->in_time),
						            'out_time'      => time_check($val->out_time),
								);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $attendance_list;
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

		// Vendor Overall Report
		// ***************************************************
		public function vendor_report($param1="",$param2="",$param3="")
		{
			$method     = $this->input->post('method');
			$start_date = $this->input->post('start_date');
			$end_date   = $this->input->post('end_date');
			$vendor_id  = $this->input->post('vendor_id');
			$product_id = $this->input->post('product_id');
			$type_id    = $this->input->post('type_id');

			if($method == '_overallReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'vendor_id'     => $vendor_id,
							'published'     => '1',
						);

						$column_1  = 'id, vendor_id, bill_code, bill_id, bill_no, pre_bal, cur_bal, amount, discount, pay_type, description, value_type, createdate';

						$payment_data = $this->payment_model->getVendorPayment($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($payment_data)
						{
							$payment_list = [];
							foreach ($payment_data as $key => $val_1) {
								$auto_id     = !empty($val_1->id)?$val_1->id:'';
					            $vendor_id   = !empty($val_1->vendor_id)?$val_1->vendor_id:'';
					            $bill_code   = !empty($val_1->bill_code)?$val_1->bill_code:'';
					            $bill_id     = !empty($val_1->bill_id)?$val_1->bill_id:'';
					            $bill_no     = !empty($val_1->bill_no)?$val_1->bill_no:'';
					            $pre_bal     = !empty($val_1->pre_bal)?$val_1->pre_bal:'0';
					            $cur_bal     = !empty($val_1->cur_bal)?$val_1->cur_bal:'0';
					            $amount      = !empty($val_1->amount)?$val_1->amount:'0';
					            $discount    = !empty($val_1->discount)?$val_1->discount:'0';
					            $pay_type    = !empty($val_1->pay_type)?$val_1->pay_type:'';
					            $description = !empty($val_1->description)?$val_1->description:'';
					            $value_type  = !empty($val_1->value_type)?$val_1->value_type:'';
					            $createdate  = !empty($val_1->createdate)?$val_1->createdate:'';

					            if($value_type == 1)
					            {
					            	$value_view = 'Order';
					            }
					            else if($value_type == 2)
					            {
					            	$value_view = 'Payment Collection';
					            }
					            else
					            {
					            	$value_view = 'Stock Return';
					            }

					            $payment_list[] = array(
					            	'auto_id'     => $auto_id,
						            'vendor_id'   => $vendor_id,
						            'bill_code'   => $bill_code,
						            'bill_id'     => $bill_id,
						            'bill_no'     => $bill_no,
						            'pre_bal'     => $pre_bal,
						            'cur_bal'     => $cur_bal,
						            'amount'      => $amount,
						            'discount'    => $discount,
						            'pay_type'    => $pay_type,
						            'description' => $description,
						            'value_type'  => $value_type,
						            'value_view'  => $value_view,
						            'createdate'  => date('d-m-Y H:i:s', strtotime($createdate)),
					            );

							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $payment_list;
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

			else if($method == '_overallInviceReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'vendor_id'     => $vendor_id,
							'cancel_status' => '1',
							'published'     => '1',
						);

						$column_1 = 'id, order_id, invoice_no, vendor_id, due_days, random_value, createdate';
						$result_1 = $this->invoice_model->getVendorInvoice($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($result_1)
						{
							$inv_list    = [];
							$inv_cnt     = !empty(count($result_1))?count($result_1):'0';
							$tot_amt     = 0;
							$tot_tax     = 0;
							$tot_taxable = 0;

							$num = 1;
							foreach ($result_1 as $key => $val_1) {

								$inv_id     = !empty($val_1->id)?$val_1->id:'';
								$order_id   = !empty($val_1->order_id)?$val_1->order_id:'';
								$invoice_no = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
								$vendor_id  = !empty($val_1->vendor_id)?$val_1->vendor_id:'';
								$due_days   = !empty($val_1->due_days)?$val_1->due_days:'0';
								$inv_random = !empty($val_1->random_value)?$val_1->random_value:'';
								$createdate = !empty($val_1->createdate)?$val_1->createdate:'';

								// Order No
					            $whr_2 = array('id' => $order_id);
					            $col_2 = 'po_no';
					            $res_2 = $this->purchase_model->getPurchase($whr_2, '', '', 'result', '', '', '', '', $col_2);

					            $order_no = !empty($res_2[0]->po_no)?$res_2[0]->po_no:'';

					            // Invoice Details
					            $whr_4 = array('invoice_id' => $inv_id, 'cancel_status' => '1', 'published' => '1');
					            $col_4 = 'price, order_qty, gst_val';
					            $res_4 = $this->invoice_model->getVendInvoiceDetails($whr_4, '', '', 'result', '', '', '', '', $col_4);

					            $inv_total   = 0;
					            $taxable_amt = 0;
					            $tax_amt     = 0;
					            if($res_4)
					            {
					            	foreach ($res_4 as $key => $val_4) {
					            		$pdt_price  = !empty($val_4->price)?$val_4->price:'0';
										$order_qty  = !empty($val_4->order_qty)?$val_4->order_qty:'0';
										$gst_value  = !empty($val_4->gst_val)?$val_4->gst_val:'0';

										$price_tot  = $pdt_price * $order_qty;
										$inv_total += $price_tot;

										// GST Calculation
										$gst_data    = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
										$price_val   = $pdt_price - $gst_data;
										$tax_amt     += $order_qty * $gst_data;
                        				$taxable_amt += $order_qty * $price_val;
					            	}
					            }

					            // Round Val Details
		                        $net_value = round($inv_total);
		                        $total_dis = 0;	
		                        $total_val = $net_value - $total_dis;

		                        // Round Val Details
		                        $last_value = round($total_val);

		                        // Round Val Details
		                        $rond_total = $last_value - $total_val;

		                        $tot_amt     += $last_value;
		                        $tot_tax     += $tax_amt;
		                        $tot_taxable += $taxable_amt;

		                        $inv_list[] = array(
					            	'inv_id'       => $inv_id,
						            'order_no'     => $order_no,
						            'inv_no'       => $invoice_no,
						            'inv_date'     => date('d-M-Y', strtotime($createdate)),
						            'company_name' => ACCOUNT_NAME,
						            'due_days'     => $due_days,
						            'round_value'  => strval($rond_total),
						            'inv_value'    => strval($last_value),
						            'pur_random'   => $order_id,
						            'inv_random'   => $inv_random,
					            );

					            $num++;
							}

							$total_value = array(
								'total_count'   => strval($inv_cnt),
								'total_taxable' => number_format((float)$tot_taxable, 2, '.', ''),
								'total_tax'     => number_format((float)$tot_tax, 2, '.', ''),
								'total_value'   => number_format((float)$tot_amt, 2, '.', ''),
							);

							$inv_data = array(
								'inv_total' => $total_value,
								'inv_list'  => $inv_list,
							);

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $inv_data;
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

			else if($method == '_purchaseReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'delete_status' => '1',
							'published'     => '1',
						);	

						if(!empty($vendor_id))
						{
							$where['vendor_id'] = $vendor_id;
						}

						if(!empty($product_id))
						{
							$where['product_id'] = $product_id;
						}

						if(!empty($type_id))
						{
							$where['type_id'] = $type_id;
						}
						$array = array(8,7,9);
						$wer_in['order_status'] = $array;

						$column = 'id, po_id, vendor_id, product_id, type_id, product_qty, receive_qty, product_unit, createdate';

						$purchaseDetails_data = $this->purchase_model->getPurchaseDetails($where, '', '', 'result', '', '', '', '', $column,$wer_in);

						if($purchaseDetails_data)
						{
							$purchase_list = [];
							foreach ($purchaseDetails_data as $key => $val_1) {
								$auto_id      = !empty($val_1->id)?$val_1->id:'';
								$po_id        = !empty($val_1->po_id)?$val_1->po_id:'';
								$vendor_id    = !empty($val_1->vendor_id)?$val_1->vendor_id:'';
								$product_id   = !empty($val_1->product_id)?$val_1->product_id:'';
								$type_id      = !empty($val_1->type_id)?$val_1->type_id:'';
								$product_qty  = !empty($val_1->product_qty)?$val_1->product_qty:'';
								$receive_qty  = !empty($val_1->receive_qty)?$val_1->receive_qty:'';
								$product_unit = !empty($val_1->product_unit)?$val_1->product_unit:'';
								$createdate   = !empty($val_1->createdate)?$val_1->createdate:'';

								// Purchase order details
								$whr_1 = array(
									'id'        => $po_id,
									'published' => '1',
								);

								$col_1 = 'po_no';

								$pur_data = $this->purchase_model->getPurchase($whr_1, '', '', 'result', '', '', '', '', $col_1);

								$pur_no   = !empty($pur_data[0]->po_no)?$pur_data[0]->po_no:'';

								// Product Details
								$whr_2 = array(
									'id'        => $type_id,
									'published' => '1',
								);

								$col_2 = 'description';

								$pro_data = $this->commom_model->getProductType($whr_2, '', '', 'result', '', '', '', '', $col_2);

								$pro_desc = !empty($pro_data[0]->description)?$pro_data[0]->description:'';

								// Vendor Details
								$whr_3 = array(
									'id'        => $vendor_id,
									'published' => '1',
								);

								$col_3 = 'company_name, gst_no, contact_no, email';

								$ven_data = $this->vendors_model->getVendors($whr_3, '', '', 'result', '', '', '', '', $col_3);

								$com_name = !empty($ven_data[0]->company_name)?$ven_data[0]->company_name:'';
								$gst_no   = !empty($ven_data[0]->gst_no)?$ven_data[0]->gst_no:'';
								$cont_no  = !empty($ven_data[0]->contact_no)?$ven_data[0]->contact_no:'';
								$email    = !empty($ven_data[0]->email)?$ven_data[0]->email:'';

								$purchase_list[] = array(
									'auto_id'      => $auto_id,
									'po_id'        => $po_id,
									'pur_no'       => $pur_no,
									'vendor_id'    => $vendor_id,
									'company_name' => $com_name,
									'gst_no'       => $gst_no,
									'contact_no'   => $cont_no,
									'email'        => $email,
									'product_id'   => $product_id,
									'type_id'      => $type_id,
									'description'  => $pro_desc,
									'product_qty'  => $product_qty,
									'receive_qty'  => $receive_qty,
									'product_unit' => $product_unit,
									'createdate'   => date('d-M-Y H:i:s', strtotime($createdate)),
								);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $purchase_list;
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

			else if($method == '_overallSalesReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >='  => $start_value,
							'createdate <='  => $end_value,
							'vendor_id'      => $vendor_id,
							'cancel_status'  => '1',
							'published'      => '1',
						);

						$column_1 = 'invoice_id, invoice_no, type_id, hsn_code, gst_val, price, order_qty, createdate';

						$inv_details = $this->invoice_model->getVendInvoiceDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($inv_details)
						{
							$sales_details = [];
							foreach ($inv_details as $key => $val) {

								$invoice_id = !empty($val->invoice_id)?$val->invoice_id:'0';
								$invoice_no = !empty($val->invoice_no)?$val->invoice_no:'0';
								$type_id    = !empty($val->type_id)?$val->type_id:'0';
								$hsn_code   = !empty($val->hsn_code)?$val->hsn_code:'0';
								$gst_value  = !empty($val->gst_val)?$val->gst_val:'0';
								$price      = !empty($val->price)?$val->price:'0';
								$order_qty  = !empty($val->order_qty)?$val->order_qty:'0';
								$createdate = !empty($val->createdate)?$val->createdate:'0';

								// Admin Details
								$whr_2 = array('id' => '1');
								$col_2 = 'username, mobile, address, state_id, gst_no';

								$user_res = $this->login_model->getLoginStatus($whr_2, '', '', 'result', '', '', '', '', $col_2);

								$adm_username = !empty($user_res[0]->username)?$user_res[0]->username:'';
								$adm_mobile   = !empty($user_res[0]->mobile)?$user_res[0]->mobile:'';
								$adm_address  = !empty($user_res[0]->address)?$user_res[0]->address:'';
								$adm_state_id = !empty($user_res[0]->state_id)?$user_res[0]->state_id:'';
								$adm_gst_no   = !empty($user_res[0]->gst_no)?$user_res[0]->gst_no:'';

								// State Details
								$where_3 = array(
									'id' => $adm_state_id
								);

								$column_3 = 'gst_code, state_name';

								$gst_val = $this->commom_model->getState($where_3, '', '', 'result', '', '', '', '', $column_3);

								$state_name = !empty($gst_val[0]->state_name)?$gst_val[0]->state_name:'';
								$gst_code   = !empty($gst_val[0]->gst_code)?$gst_val[0]->gst_code:'';

								// Vendor Details
								$whr_4 = array('id' => $vendor_id);
								$col_4 = 'company_name, contact_no, address, state_id, gst_no';

								$ven_res = $this->vendors_model->getVendors($whr_4, '', '', 'result', '', '', '', '', $col_4);

								$vend_name   = !empty($ven_res[0]->company_name)?$ven_res[0]->company_name:'';
								$ven_mobile  = !empty($ven_res[0]->contact_no)?$ven_res[0]->contact_no:'';
								$ven_address = !empty($ven_res[0]->address)?$ven_res[0]->address:'';
								$ven_state   = !empty($ven_res[0]->state_id)?$ven_res[0]->state_id:'';
								$ven_gst_no  = !empty($ven_res[0]->gst_no)?$ven_res[0]->gst_no:'';

								// Product Details
								$where_5 = array('id' => $type_id);

								$column_5 = 'description, product_price';

								$pdt_details = $this->commom_model->getProductType($where_5, '', '', 'result', '', '', '', '', $column_5);

								$pdt_res     = $pdt_details[0];

								$description   = !empty($pdt_res->description)?$pdt_res->description:'';
								$product_price = !empty($pdt_res->product_price)?$pdt_res->product_price:'';

								// Purchase Detials
								$where_6 = array(
									'id' => $invoice_id,
								);

								$column_6 = 'order_id';

								$pur_data = $this->invoice_model->getVendorInvoice($where_6, '', '', 'result', '', '', '', '', $column_6);

								$order_id = !empty($pur_data[0]->order_id)?$pur_data[0]->order_id:'';

								$where_7 = array(
									'id' => $invoice_id,
								);

								$column_7 = 'po_no, _ordered';

								$ord_data = $this->purchase_model->getPurchase($where_7, '', '', 'result', '', '', '', '', $column_7);

								$pur_no   = !empty($ord_data[0]->po_no)?$ord_data[0]->po_no:'';		
								$ordered  = !empty($ord_data[0]->_ordered)?$ord_data[0]->_ordered:'';								
								$sales_details[] = array(
									'pur_no'        => $pur_no,
									'pur_date'      => date('d-m-Y', strtotime($ordered)),
									'ven_state_id'  => $ven_state,
									'invoice_no'    => $invoice_no,
									'admin_name'    => $adm_username,
									'mobile'        => $adm_mobile,
									'gst_no'        => $adm_gst_no,
									'address'       => $adm_address,
									'state_id'      => $adm_state_id,
									'state_name'    => $state_name,
									'gst_code'      => $gst_code,
									'invoice_date'  => date('d-m-Y', strtotime($createdate)),
									'description'   => $description,
									'hsn_code'      => $hsn_code,
									'gst_val'       => $gst_value,
									'price'         => $price,
									'order_qty'     => $order_qty,
								);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $sales_details;
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
				    else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Invalid Date"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
				}
			}

			else if($method == '_overallGstReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'vendor_id'     => $vendor_id,
							'cancel_status' => '1',
							'published'     => '1',
						);

						$column_1 = 'id, order_id, invoice_no, vendor_id, due_days, createdate';
						$result_1 = $this->invoice_model->getVendorInvoice($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($result_1)
						{
							$inv_data = [];
                            foreach ($result_1 as $key => $val_1) {
                            	$inv_id    = !empty($val_1->id)?$val_1->id:'';
					            $ord_id    = !empty($val_1->order_id)?$val_1->order_id:'';
					            $inv_no    = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
					            $vendor_id = !empty($val_1->vendor_id)?$val_1->vendor_id:'';
					            $due_days  = !empty($val_1->due_days)?$val_1->due_days:'';
					            $inv_date  = !empty($val_1->createdate)?$val_1->createdate:'';

					            $where_2  = array('id' => '1');
	                            $column_2 = 'gst_no, state_id';

	                            $res_2    = $this->login_model->getLoginStatus($where_2, '', '', 'result', '', '', '', '', $column_2);

	                            $adm_gst_no   = !empty($res_2[0]->gst_no)?$res_2[0]->gst_no:'';
	                            $adm_state_id = !empty($res_2[0]->state_id)?$res_2[0]->state_id:'';

	                            // State Details
	                            $where_3  = array('id' => $adm_state_id);
	                            $column_3 = 'state_name, gst_code';
	                            $res_3    = $this->commom_model->getState($where_3, '', '', 'result', '', '', '', '', $column_3);

	                            $adm_state_name = !empty($res_3[0]->state_name)?$res_3[0]->state_name:'';
	                            $adm_gst_code   = !empty($res_3[0]->gst_code)?$res_3[0]->gst_code:'';

	                            // Invoice Details
	                            $where_4  = array('invoice_id' => $inv_id, 'published' => '1');
	                            $column_4 = 'price, order_qty';
	                            $result_4 = $this->invoice_model->getVendInvoiceDetails($where_4, '', '', 'result', '', '', '', '', $column_4);

	                            $inv_tot  = 0;
	                            if($result_4)
	                            {
	                            	foreach ($result_4 as $key => $val_4) {
	                            		$price_val   = !empty($val_4->price)?$val_4->price:'0';
                                        $order_qty   = !empty($val_4->order_qty)?$val_4->order_qty:'0';
                                        $tot_price   = $order_qty * $price_val;
                                        $inv_tot    += $tot_price;
	                            	}
	                            }

	                            $inv_round = round($inv_tot);
	                            $inv_total = number_format((float)$inv_round, 2, '.', '');

	                            $gst_per = array('0', '5', '12', '18', '28');
	                            $gst_cnt = count($gst_per);

	                            for ($i=0; $i < $gst_cnt; $i++) { 
	                            		
	                            	$gst_res = number_format((float)$gst_per[$i], 2, '.', '');

	                            	// GST Calculation Value
	                            	$where_5  = array(
	                            		'invoice_id' => $inv_id, 
	                            		'gst_val'    => $gst_per[$i],
	                            		'published'  => '1'
	                            	);

	                            	$column_5 = 'price, order_qty, gst_val';
	                            	$result_5 = $this->invoice_model->getVendInvoiceDetails($where_5, '', '', 'result', '', '', '', '', $column_5);

	                            	$pdt_price = 0;
                                    $gst_price = 0;
	                            	if($result_5)
	                            	{
	                            		foreach ($result_5 as $key => $val_5) {
	                            			$price     = !empty($val_5->price)?$val_5->price:'';
                                            $order_qty = !empty($val_5->order_qty)?$val_5->order_qty:'';
                                            $gst_val   = !empty($val_5->gst_val)?$val_5->gst_val:'';

                                            $gst_data    = $price - ($price * (100 / (100 + $gst_val)));
                                            $price_val   = $price - $gst_data;
                                            $total_price = $order_qty * $price_val;
                                            $total_gst   = $order_qty * $gst_data;

                                            $gst_price += $total_gst;
                                            $pdt_price += $total_price;
	                            		}
	                            	}

	                            	$pdt_total = number_format((float)$pdt_price, 2, '.', ''); 
	                            	$gst_total = number_format((float)$gst_price, 2, '.', '');

	                            	$inv_data[] = array(
		                            	'company_gst'  => $adm_gst_no,
		                            	'invoice_no'   => $inv_no,
		                            	'invoice_date' => date('d-M-Y', strtotime($inv_date)),
		                            	'state_gst'    => $adm_gst_code,
		                            	'state_name'   => $adm_state_name,
		                            	'gst_rate'     => $gst_res,
		                            	'invoice_val'  => $inv_total,
		                            	'product_val'  => $pdt_total,
		                            	'taxable_val'  => $gst_total,
		                            );
	                            }
                            }

                            $response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $inv_data;
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

			else if($method == '_overallPurchaseReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));


			    		$whr_1 = array(
							'createdate >='   => $start_value,
							'createdate <='   => $end_value,
							
							'published'       => '1',
						);
						$array = array(8,7,9);
						$wer_in['order_status'] = $array;

						$col_1 = 'id, po_no, vendor_name, _ordered, order_status, invoice_no, inv_value';

						$res_1 = $this->purchase_model->getPurchase($whr_1, '', '', 'result', '', '', '', '', $col_1,$wer_in);

						if($res_1)
						{
							$data_res = [];
							foreach ($res_1 as $key => $val_1) {
									
								$po_id    = !empty($val_1->id)?$val_1->id:'';
							    $po_no    = !empty($val_1->po_no)?$val_1->po_no:'';
							    $vend_val = !empty($val_1->vendor_name)?$val_1->vendor_name:'';
							    $_ordered = !empty($val_1->_ordered)?$val_1->_ordered:'';
							    $ord_sta  = !empty($val_1->order_status)?$val_1->order_status:'';
							    $inv_no   = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
							    $inv_val  = !empty($val_1->inv_value)?$val_1->inv_value:'';

							    // Invoice Total Value
							    $whr_2 = array(
							    	'po_id'         => $po_id, 
							    	'delete_status' => '1',
							    	'published'     => '1',
							    );

							    $col_2 = 'product_qty, product_price';

							    $res_2 = $this->purchase_model->getPurchaseDetails($whr_2, '', '', 'result', '', '', '', '', $col_2);

							    $pdt_total = 0;
							    if($res_2)
							    {
							    	foreach ($res_2 as $key => $val_2) {
							    		$pdt_qty    = !empty($val_2->product_qty)?$val_2->product_qty:'0';
            							$pdt_price  = !empty($val_2->product_price)?$val_2->product_price:'0';

            							$pdt_value  = $pdt_qty * $pdt_price;
            							$pdt_total += $pdt_value;
							    	}
							    }

							    $total_val = round($pdt_total);

							    $data_res[] = array(
							    	'po_id'        => $po_id,
								    'po_no'        => $po_no,
								    'vendor_name'  => $vend_val,
								    'ordered'      => date('d-M-Y', strtotime($_ordered)),
								    'order_status' => $ord_sta,
								    'invoice_no'   => $inv_no,
								    'inv_value'    => $inv_val,
								    'total_value'  => number_format((float)$total_val, 2, '.', ''),
							    );
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $data_res;
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

			else if($method == '_overallPurchaseDetails')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

			    		$whr_1 = array(
							'createdate >='   => $start_value,
							'createdate <='   => $end_value,
							
							'delete_status'   => '1',
							'published'       => '1',
						);
						$array = array(8,7,9);
						$wer_in['order_status'] = $array;
						$col_1 = 'id, po_id, vendor_id, product_id, type_id, product_price, product_qty, order_status';

						$res_1 = $this->purchase_model->getPurchaseDetails($whr_1, '', '', 'result', '', '', '', '', $col_1,$wer_in);

						if($res_1)
						{
							$data_res = [];
							foreach ($res_1 as $key => $val_1) {
								
								$auto_id   = !empty($val_1->id)?$val_1->id:'';
							    $pur_id    = !empty($val_1->po_id)?$val_1->po_id:'';
							    $vendor_id = !empty($val_1->vendor_id)?$val_1->vendor_id:'';
							    $pdt_id    = !empty($val_1->product_id)?$val_1->product_id:'';
							    $type_id   = !empty($val_1->type_id)?$val_1->type_id:'';
							    $pdt_price = !empty($val_1->product_price)?$val_1->product_price:'';
							    $pdt_qty   = !empty($val_1->product_qty)?$val_1->product_qty:'';

								// Admin Details
								$whr_2 = array('id' => '1');
								$col_2 = 'username, mobile, email, address, pincode, state_id, gst_no';
								$res_2 = $this->login_model->getLoginStatus($whr_2, '', '', 'result', '', '', '', '', $col_2);

								$adm_username = !empty($res_2[0]->username)?$res_2[0]->username:'';
					            $adm_mobile   = !empty($res_2[0]->mobile)?$res_2[0]->mobile:'';
					            $adm_email    = !empty($res_2[0]->email)?$res_2[0]->email:'';
					            $adm_address  = !empty($res_2[0]->address)?$res_2[0]->address:'';
					            $adm_pincode  = !empty($res_2[0]->pincode)?$res_2[0]->pincode:'';
					            $adm_state_id = !empty($res_2[0]->state_id)?$res_2[0]->state_id:'';
					            $adm_gst_no   = !empty($res_2[0]->gst_no)?$res_2[0]->gst_no:'';

					            // Vendor Details
								$whr_3 = array('id' => $vendor_id);
								$col_3 = 'company_name, gst_no, state_id';
								$res_3 = $this->vendors_model->getVendors($whr_3, '', '', 'result', '', '', '', '', $col_3);

								$ven_com_name = !empty($res_3[0]->company_name)?$res_3[0]->company_name:'';
								$ven_gst_no   = !empty($res_3[0]->gst_no)?$res_3[0]->gst_no:'';
								$ven_state_id = !empty($res_3[0]->state_id)?$res_3[0]->state_id:'';

					            // State Details
					            $whr_4 = array('id' => $ven_state_id);
								$col_4 = 'state_name';
								$res_4 = $this->commom_model->getState($whr_4, '', '', 'result', '', '', '', '', $col_4);

								$ven_state_val = !empty($res_4[0]->state_name)?$res_4[0]->state_name:'';

								// Order Details
								$whr_5 = array('id' => $pur_id);
								$col_5 = 'po_no, invoice_id, createdate';
								$res_5 = $this->purchase_model->getPurchase($whr_5, '', '', 'result', '', '', '', '', $col_5);

								$po_no    = !empty($res_5[0]->po_no)?$res_5[0]->po_no:'';
            					$inv_id   = !empty($res_5[0]->invoice_id)?$res_5[0]->invoice_id:'';
            					$cre_date = !empty($res_5[0]->createdate)?$res_5[0]->createdate:'';

            					// Invoice Details
            					$whr_5 = array('id' => $inv_id);
								$col_5 = 'invoice_no, createdate';
								$res_5 = $this->invoice_model->getVendorInvoice($whr_5, '', '', 'result', '', '', '', '', $col_5);

								$invoice_no = !empty($res_5[0]->invoice_no)?$res_5[0]->invoice_no:'';
            					$createdate = !empty($res_5[0]->createdate)?date($res_5[0]->createdate):'';

            					// Product Details
            					$whr_6 = array('id' => $pdt_id);
            					$col_6 = 'gst, hsn_code';
            					$res_6 = $this->commom_model->getProduct($whr_6, '', '', 'result', '', '', '', '', $col_6);

            					$gst_val = !empty($res_6[0]->gst)?$res_6[0]->gst:'';
            					$hsn_val = !empty($res_6[0]->hsn_code)?$res_6[0]->hsn_code:'';

            					// Product Type Details
            					$whr_7 = array('id' => $type_id);
            					$col_7 = 'description';
            					$res_7 = $this->commom_model->getProductType($whr_7, '', '', 'result', '', '', '', '', $col_7);

            					$desc_val = !empty($res_7[0]->description)?$res_7[0]->description:'';

            					$data_res[] = array(
            						'pur_no'        => $po_no,
            						'pur_date'      => date('d-M-Y', strtotime($cre_date)),
            						'admin_name'    => $adm_username,
            						'admin_gst'     => $adm_gst_no,
            						'adm_state_id'  => $adm_state_id,
            						'ven_com_name'  => $ven_com_name,
            						'ven_gst_no'    => $ven_gst_no,
            						'ven_state_id'  => $ven_state_id,
            						'ven_state'     => $ven_state_val,
            						'product_name'  => $desc_val, 
            						'hsn_code'      => $hsn_val,
            						'product_qty'   => $pdt_qty,
            						'product_gst'   => $gst_val,
            						'product_price' => $pdt_price,
            						'invoice_no'    => $invoice_no,
            						'invoice_date'  => $createdate,
            					);

							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $data_res;
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

			else if($method == '_overallPurchaseReturnReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$whr_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'order_status'  => '5',
							'published'     => '1',
						);

						$col_1 = 'id, order_no, vendor_name, _ordered, order_status';

						$res_1 = $this->purchase_model->getPurchaseReturn($whr_1, '', '', 'result', '', '', '', '', $col_1);

						if($res_1)
						{
							$data_res = [];
							foreach ($res_1 as $key => $val_1) {

								$ord_id   = !empty($val_1->id)?$val_1->id:'';
							    $ord_no   = !empty($val_1->order_no)?$val_1->order_no:'';
							    $vend_val = !empty($val_1->vendor_name)?$val_1->vendor_name:'';
							    $_ordered = !empty($val_1->_ordered)?$val_1->_ordered:'';
							    $ord_sta  = !empty($val_1->order_status)?$val_1->order_status:'';

							    // Invoice Total Value
							    $whr_2 = array(
							    	'order_id'  => $ord_id, 
							    	'published' => '1',
							    );

							    $col_2 = 'product_qty, product_price';

							    $res_2 = $this->purchase_model->getPurchaseReturnDetails($whr_2, '', '', 'result', '', '', '', '', $col_2);

							    $pdt_total = 0;
							    if($res_2)
							    {
							    	foreach ($res_2 as $key => $val_2) {
							    		$pdt_qty    = !empty($val_2->product_qty)?$val_2->product_qty:'0';
            							$pdt_price  = !empty($val_2->product_price)?$val_2->product_price:'0';

            							$pdt_value  = $pdt_qty * $pdt_price;
            							$pdt_total += $pdt_value;
							    	}
							    }

							    $total_val = round($pdt_total);

							    $data_res[] = array(
							    	'order_id'     => $ord_id,
								    'order_no'     => $ord_no,
								    'vendor_name'  => $vend_val,
								    'ordered'      => date('d-M-Y', strtotime($_ordered)),
								    'order_status' => $ord_sta,
								    'total_value'  => number_format((float)$total_val, 2, '.', ''),
							    );
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $data_res;
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

			else if($method == '_overallPurchaseReturnDetails')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$whr_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'order_status'  => '5',
							'published'     => '1',
						);

						$col_1 = 'id, order_id, vendor_id, product_id, type_id, product_price, product_qty';

						$res_1 = $this->purchase_model->getPurchaseReturnDetails($whr_1, '', '', 'result', '', '', '', '', $col_1);

						if($res_1)
						{
							$data_res = [];
							foreach ($res_1 as $key => $val_1) {
								$auto_id   = !empty($val_1->id)?$val_1->id:'';
							    $ord_id    = !empty($val_1->order_id)?$val_1->order_id:'';
							    $vendor_id = !empty($val_1->vendor_id)?$val_1->vendor_id:'';
							    $pdt_id    = !empty($val_1->product_id)?$val_1->product_id:'';
							    $type_id   = !empty($val_1->type_id)?$val_1->type_id:'';
							    $pdt_price = !empty($val_1->product_price)?$val_1->product_price:'';
							    $pdt_qty   = !empty($val_1->product_qty)?$val_1->product_qty:'';

							    // Admin Details
								$whr_2 = array('id' => '1');
								$col_2 = 'username, mobile, email, address, pincode, state_id, gst_no';
								$res_2 = $this->login_model->getLoginStatus($whr_2, '', '', 'result', '', '', '', '', $col_2);

								$adm_username = !empty($res_2[0]->username)?$res_2[0]->username:'';
					            $adm_mobile   = !empty($res_2[0]->mobile)?$res_2[0]->mobile:'';
					            $adm_email    = !empty($res_2[0]->email)?$res_2[0]->email:'';
					            $adm_address  = !empty($res_2[0]->address)?$res_2[0]->address:'';
					            $adm_pincode  = !empty($res_2[0]->pincode)?$res_2[0]->pincode:'';
					            $adm_state_id = !empty($res_2[0]->state_id)?$res_2[0]->state_id:'';
					            $adm_gst_no   = !empty($res_2[0]->gst_no)?$res_2[0]->gst_no:'';

					            // State Details
					            $whr_3 = array('id' => $adm_state_id);
								$col_3 = 'state_name';
								$res_3 = $this->commom_model->getState($whr_3, '', '', 'result', '', '', '', '', $col_3);

								$adm_state_val = !empty($res_3[0]->state_name)?$res_3[0]->state_name:'';

								// Vendor Details
								$whr_4 = array('id' => $vendor_id);
								$col_4 = 'state_id';
								$res_4 = $this->vendors_model->getVendors($whr_4, '', '', 'result', '', '', '', '', $col_4);

								$ven_state_id = !empty($res_4[0]->state_id)?$res_4[0]->state_id:'';

								// Order Details
								$whr_5 = array('id' => $ord_id);
								$col_5 = 'order_no, createdate';
								$res_5 = $this->purchase_model->getPurchaseReturn($whr_5, '', '', 'result', '', '', '', '', $col_5);

								$ord_no   = !empty($res_5[0]->order_no)?$res_5[0]->order_no:'';
            					$cre_date = !empty($res_5[0]->createdate)?$res_5[0]->createdate:'';

            					// Product Details
            					$whr_6 = array('id' => $pdt_id);
            					$col_6 = 'gst, hsn_code';
            					$res_6 = $this->commom_model->getProduct($whr_6, '', '', 'result', '', '', '', '', $col_6);

            					$gst_val = !empty($res_6[0]->gst)?$res_6[0]->gst:'';
            					$hsn_val = !empty($res_6[0]->hsn_code)?$res_6[0]->hsn_code:'';

            					// Product Type Details
            					$whr_7 = array('id' => $type_id);
            					$col_7 = 'description';
            					$res_7 = $this->commom_model->getProductType($whr_7, '', '', 'result', '', '', '', '', $col_7);

            					$desc_val = !empty($res_7[0]->description)?$res_7[0]->description:'';

            					$data_res[] = array(
            						'return_no'     => $ord_no, 
            						'return_date'   => $cre_date,
            						'admin_name'    => $adm_username,
            						'admin_gst'     => $adm_gst_no,
            						'admin_state'   => $adm_state_val,
            						'adm_state_id'  => $adm_state_id,
            						'ven_state_id'  => $ven_state_id,
            						'product_name'  => $desc_val, 
            						'hsn_code'      => $hsn_val,
            						'product_qty'   => $pdt_qty,
            						'product_gst'   => $gst_val,
            						'product_price' => $pdt_price,
            					);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $data_res;
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

		// Outlet Overall Report
		// ***************************************************
		public function outlet_report($param1="",$param2="",$param3="")
		{
			$outlet_id      = $this->input->post('outlet_id');
			$state_id       = $this->input->post('state_id');
			$city_id        = $this->input->post('city_id');
			$zone_id        = $this->input->post('zone_id');
			$distributor_id = $this->input->post('distributor_id');
			$financial_year = $this->input->post('financial_year');
			$status_val     = $this->input->post('status_val');
			$start_date     = $this->input->post('start_date');
			$end_date       = $this->input->post('end_date');
			$view_type      = $this->input->post('view_type');
			$method         = $this->input->post('method');

			if($method == '_outletHistory')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('outlet_id');
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
					// Master Details
					$limit  = 1;
					$offset = 0;

					$option['order_by']   = 'id';
					$option['disp_order'] = 'DESC';

					// Attendace Details
					$where_1 = array(
						'store_id'  => $outlet_id,
						'status'    => '1',
						'published' => '1',
					);

					$column_1 = 'id, emp_name, attendance_type, c_date, in_time';

					$attendance_list = $this->attendance_model->getAttendance($where_1, $limit, $offset, 'result', '', '', $option, '', $column_1);

					$attendance_data = [];
					if($attendance_list)
					{
						foreach ($attendance_list as $key => $val_1) {
							$attendance_id   = !empty($val_1->id)?$val_1->id:'';
							$emp_name        = !empty($val_1->emp_name)?$val_1->emp_name:'';
							$attendance_type = !empty($val_1->attendance_type)?$val_1->attendance_type:'3';
							$c_date          = !empty($val_1->c_date)?$val_1->c_date:'';
							$in_time         = !empty($val_1->in_time)?$val_1->in_time:'';

							if($attendance_type == 1)
							{
								$type_view = 'Sales Order';
							}
							else if($attendance_type == 2)
							{
								$type_view = 'No Order';
							}
							else
							{
								$type_view = 'Pending';
							}

							$attendance_data = array(
								'attendance_id'   => $attendance_id,
								'employee_name'   => $emp_name,
								'attendance_type' => $type_view,
								'attendance_date' => date('d-M-Y', strtotime($c_date)),
								'attendance_time' => date('H:i:s', strtotime($in_time)),
							);
						}
					}

					// Last Order Details
					$where_2 = array(
						'store_id'  => $outlet_id,
						'status'    => '1',
						'published' => '1',
					);

					$column_2 = 'id, order_no, emp_name, order_status, _ordered, random_value';

					$order_list = $this->order_model->getOrder($where_2, $limit, $offset, 'result', '', '', $option, '', $column_2);

					$order_data = [];
					if($order_list)
					{
						foreach ($order_list as $key => $val_2) {
							$order_id     = !empty($val_2->id)?$val_2->id:'';
							$order_no     = !empty($val_2->order_no)?$val_2->order_no:'';
							$emp_name     = !empty($val_2->emp_name)?$val_2->emp_name:'Admin';
							$order_status = !empty($val_2->order_status)?$val_2->order_status:'';
							$_ordered     = !empty($val_2->_ordered)?$val_2->_ordered:'';
							$random_value = !empty($val_2->random_value)?$val_2->random_value:'';

							// Order Details
							$ord_whr = array('order_id' => $order_id, 'delete_status' => '1', 'published' => '1');
							$ord_col = 'price, order_qty';
							$ord_res = $this->order_model->getOrderDetails($ord_whr, '', '', 'result', '', '', '', '', $ord_col);

							$ord_total = 0;
							if($ord_res)
							{
								foreach ($ord_res as $key => $ord_val) {
									$ord_price = !empty($ord_val->price)?$ord_val->price:'0';
									$ord_qty   = !empty($ord_val->order_qty)?$ord_val->order_qty:'0';
									$ord_value = $ord_price * $ord_qty;
									$ord_total += $ord_value;
								}
							}

							$order_data = array(
								'order_id'     => $order_id,
								'order_no'     => $order_no,
								'emp_name'     => $emp_name,
								'order_status' => $order_status,
								'_ordered'     => $_ordered,
								'random_value' => $random_value,
								'order_value'  => round($ord_total),
							);
						}
					}

					// Last Invoice Details
					$where_3 = array(
						'store_id'  => $outlet_id,
						'status'    => '1',
						'published' => '1',
					);

					$column_3 = 'id, invoice_no, distributor_id, store_name, random_value, createdate';

					$invoice_list = $this->invoice_model->getInvoice($where_3, $limit, $offset, 'result', '', '', $option, '', $column_3);

					$invoice_data = [];
					if($invoice_list)
					{
						foreach ($invoice_list as $key => $val_3) {
							$invoice_id     = !empty($val_3->id)?$val_3->id:'';
							$invoice_no     = !empty($val_3->invoice_no)?$val_3->invoice_no:'';
							$distributor_id = !empty($val_3->distributor_id)?$val_3->distributor_id:'';
							$store_name     = !empty($val_3->store_name)?$val_3->store_name:'';
							$random_value   = !empty($val_3->random_value)?$val_3->random_value:'';
							$createdate     = !empty($val_3->createdate)?$val_3->createdate:'';

							// Invoice Distributor
							$inv_dis_whr = array(
								'id'        => $distributor_id,
						    	'status'    => '1',
								'published' => '1',
						    );

							$inv_dis_col  = 'company_name';

							$inv_dis_data = $this->distributors_model->getDistributors($inv_dis_whr, '', '', 'result', '', '', '', '', $inv_dis_col);

							$inv_dis = !empty($inv_dis_data[0]->company_name)?$inv_dis_data[0]->company_name:'';

							// Invoice Details
							$inv_whr = array('invoice_id' => $invoice_id, 'published' => '1');
							$inv_col = 'price, order_qty';
							$inv_res = $this->invoice_model->getInvoiceDetails($inv_whr, '', '', 'result', '', '', '', '', $inv_col);

							$inv_total = 0;
							if($inv_res)
							{
								foreach ($inv_res as $key => $inv_val) {
									$inv_price = !empty($inv_val->price)?$inv_val->price:'0';
									$inv_qty   = !empty($inv_val->order_qty)?$inv_val->order_qty:'0';
									$inv_value = $inv_price * $inv_qty;
									$inv_total += $inv_value;
								}
							}

							$invoice_data = array(
								'invoice_no'       => $invoice_no,
								'store_name'       => $store_name,
								'distributor_name' => $inv_dis,
								'random_value'     => $random_value,
								'invoice_value'    => round($inv_total),
								'createdate'       => $createdate,
							);
						}
					}

					// Payment Details
					$where_4 = array(
						'outlet_id'  => $outlet_id,	
						'value_type' => '2',
						'published'  => '1',
					);

					$column_4 = 'id, distributor_id, bill_code, bill_no, amount, discount, amt_type, collection_type, date';

					$payment_list = $this->payment_model->getOutletPayment($where_4, $limit, $offset, 'result', '', '', $option, '', $column_4);

					$payment_data = [];
					if($payment_list)
					{
						foreach ($payment_list as $key => $val_4) {
						$payment_id      = !empty($val_4->id)?$val_4->id:'';
						$distributor_id  = !empty($val_4->distributor_id)?$val_4->distributor_id:'';
						$bill_code       = !empty($val_4->bill_code)?$val_4->bill_code:'';
						$bill_no         = !empty($val_4->bill_no)?$val_4->bill_no:'';
						$amount          = !empty($val_4->amount)?$val_4->amount:'0';
						$discount        = !empty($val_4->discount)?$val_4->discount:'0';
						$amt_type        = !empty($val_4->amt_type)?$val_4->amt_type:'';
						$collection_type = !empty($val_4->collection_type)?$val_4->collection_type:'';
						$date            = !empty($val_4->date)?$val_4->date:'';

						// Amount Type
						if($amt_type == 1)
						{
							$amount_view = 'Cash';
						}
						else if($amt_type == 2)
						{
							$amount_view = 'Cheque';
						}
						else
						{
							$amount_view = 'Others';
						}

						// Collection Type
						if($collection_type == 1)
						{
							$collection_view = 'Pending';
						}
						else
						{
							$collection_view = 'Collected';
						}

						// Invoice Distributor
						$col_dis_whr = array(
							'id'        => $distributor_id,
					    	'status'    => '1',
							'published' => '1',
					    );

						$col_dis_col  = 'company_name';

						$col_dis_data = $this->distributors_model->getDistributors($col_dis_whr, '', '', 'result', '', '', '', '', $col_dis_col);

						$col_dis = !empty($col_dis_data[0]->company_name)?$col_dis_data[0]->company_name:'';

						$payment_data = array(
							'payment_id'       => $payment_id,
							'distributor_name' => $col_dis,
							'bill_code'        => $bill_code,
							'bill_no'          => $bill_no,
							'amount'           => $amount,
							'discount'         => $discount,
							'amt_type'         => $amount_view,
							'collection_type'  => $collection_view,
							'date'             => date('d-M-Y', strtotime($date)),
						);
					}
					}

					$outlet_data = array(
						'attendance_data' => $attendance_data,
						'order_data'      => $order_data,
						'invoice_data'    => $invoice_data,
						'payment_data'    => $payment_data,
					);

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $outlet_data;
			        echo json_encode($response);
			        return;
				}
			}	

			else if($method == '_beatWiseOutlet')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('state_id');
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
			    	// Attendace Details
					$where_1 = array(
						'state_id'  => $state_id,
						'published' => '1',
					);

					if(!empty($city_id))
					{
						$where_1['city_id'] = $city_id;	
					}

					if(!empty($zone_id))
					{
						$where_1['zone_id'] = $zone_id;	
					}

					if($status_val == 1)
					{
						$where_1['status'] = $status_val;
					}

					else if($status_val == 2)
					{
						$where_1['status'] = 0;
					}

					$column_1  = 'id, company_name, mobile, email, gst_no, pan_no, tan_no, due_days, discount, account_name, account_no, address, credit_limit, available_limit, current_balance, country_id, state_id, city_id, zone_id';

					$data_list = $this->outlets_model->getOutlets($where_1, '', '', 'result', '', '', '', '', $column_1);

					if($data_list)
					{
						$outlet_list = [];
						foreach ($data_list as $key => $val_1) {
							
							$outlet_id       = !empty($val_1->id)?$val_1->id:'';
							$company_name    = !empty($val_1->company_name)?$val_1->company_name:'';
							$mobile          = !empty($val_1->mobile)?$val_1->mobile:'';
							$email           = !empty($val_1->email)?$val_1->email:'';
							$gst_no          = !empty($val_1->gst_no)?$val_1->gst_no:'';
							$pan_no          = !empty($val_1->pan_no)?$val_1->pan_no:'';
							$tan_no          = !empty($val_1->tan_no)?$val_1->tan_no:'';
							$due_days        = !empty($val_1->due_days)?$val_1->due_days:'';
							$discount        = !empty($val_1->discount)?$val_1->discount:'';
							$account_name    = !empty($val_1->account_name)?$val_1->account_name:'';
							$account_no      = !empty($val_1->account_no)?$val_1->account_no:'';
							$address         = !empty($val_1->address)?$val_1->address:'';
							$credit_limit    = !empty($val_1->credit_limit)?$val_1->credit_limit:'';
							$available_limit = !empty($val_1->available_limit)?$val_1->available_limit:'';
							$current_balance = !empty($val_1->current_balance)?$val_1->current_balance:'';
							$state_id        = !empty($val_1->state_id)?$val_1->state_id:'';
							$city_id         = !empty($val_1->city_id)?$val_1->city_id:'';
							$zone_id         = !empty($val_1->zone_id)?$val_1->zone_id:'';

							// State Details
							$state_col  = 'state_name';
				            $state_whr  = array('id' => $state_id);
				            $state_data = $this->commom_model->getState($state_whr, '', '', 'result', '', '', '', '', $state_col);
				            $state_val  = !empty($state_data[0]->state_name)?$state_data[0]->state_name:'';

				            // City Details
							$city_col  = 'city_name';
				            $city_whr  = array('id' => $city_id);
				            $city_data = $this->commom_model->getCity($city_whr, '', '', 'result', '', '', '', '', $city_col);
				            $city_val  = !empty($city_data[0]->city_name)?$city_data[0]->city_name:'';

				            // Zone Details
							$zone_col  = 'name';
				            $zone_whr  = array('id' => $zone_id);
				            $zone_data = $this->commom_model->getZone($zone_whr, '', '', 'result', '', '', '', '', $zone_col);
				            $zone_val  = !empty($zone_data[0]->name)?$zone_data[0]->name:'';

							$outlet_list[] = array(
								'outlet_id'       => $outlet_id,
								'company_name'    => $company_name,
								'mobile'          => $mobile,
								'email'           => $email,
								'gst_no'          => $gst_no,
								'pan_no'          => $pan_no,
								'tan_no'          => $tan_no,
								'due_days'        => $due_days,
								'discount'        => $discount,
								'account_name'    => $account_name,
								'account_no'      => $account_no,
								'address'         => $address,
								'state_name'      => $state_val,
								'city_name'       => $city_val,
								'beat_name'       => $zone_val,
								'credit_limit'    => $credit_limit,
								'available_limit' => $available_limit,
								'current_balance' => $current_balance,
							);
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $outlet_list;
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

			else if($method == '_overallReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date', 'outlet_id');
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'outlet_id'     => $outlet_id,
							'published'     => '1',
						);

						$column_1  = 'id, distributor_id, bill_code, bill_id, bill_no, pre_bal, cur_bal, amount, discount, pay_type, description, value_type, createdate';

						$payment_data = $this->payment_model->getOutletPayment($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($payment_data)
						{
							$payment_list = [];
							foreach ($payment_data as $key => $val_1) {
								$auto_id        = !empty($val_1->id)?$val_1->id:'';
					            $distributor_id = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
					            $bill_code      = !empty($val_1->bill_code)?$val_1->bill_code:'INV';
					            $bill_id        = !empty($val_1->bill_id)?$val_1->bill_id:'';
					            $bill_no        = !empty($val_1->bill_no)?$val_1->bill_no:'';
					            $pre_bal        = !empty($val_1->pre_bal)?$val_1->pre_bal:'0';
					            $cur_bal        = !empty($val_1->cur_bal)?$val_1->cur_bal:'0';
					            $amount         = !empty($val_1->amount)?$val_1->amount:'0';
					            $discount       = !empty($val_1->discount)?$val_1->discount:'0';
					            $pay_type       = !empty($val_1->pay_type)?$val_1->pay_type:'';
					            $description    = !empty($val_1->description)?$val_1->description:'';
					            $value_type     = !empty($val_1->value_type)?$val_1->value_type:'';
					            $createdate     = !empty($val_1->createdate)?$val_1->createdate:'';

					            if($value_type == 1)
					            {
					            	$value_view = 'Order';
					            }
					            else if($value_type == 2)
					            {
					            	$value_view = 'Payment Collection';
					            }
					            else
					            {
					            	$value_view = 'Stock Return';
					            }

					            $payment_list[] = array(
					            	'auto_id'        => $auto_id,
						            'distributor_id' => $distributor_id,
						            'bill_code'      => $bill_code,
						            'bill_id'        => $bill_id,
						            'bill_no'        => $bill_no,
						            'pre_bal'        => $pre_bal,
						            'cur_bal'        => $cur_bal,
						            'amount'         => $amount,
						            'discount'       => $discount,
						            'pay_type'       => $pay_type,
						            'description'    => $description,
						            'value_type'     => $value_type,
						            'value_view'     => $value_view,
						            'createdate'     => date('d-m-Y H:i:s', strtotime($createdate)),
					            );

							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $payment_list;
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

			else if($method == '_getOutletOverallReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date', 'view_type');
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
			    		// Commission / Sales Report
				    	if($view_type == 1)
				    	{
				    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
					    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

					    	$where_1 = array(
								'createdate >=' => $start_value,
								'createdate <=' => $end_value,
								'cancel_status' => '1',
								'published'     => '1',
							);

					    	if(!empty($distributor_id))
							{
								$where_1['distributor_id'] = $distributor_id;
							}

							if(!empty($outlet_id))
							{
								$where_1['store_id'] = $outlet_id;
							}

					    	$column_1 = 'invoice_no, invoice_no, distributor_id, order_id, type_id, hsn_code, gst_val, price, order_qty, createdate';

							$inv_details = $this->invoice_model->getInvoiceDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

							if($inv_details)
							{
								$sales_details = [];
								foreach ($inv_details as $key => $val) {

									$invoice_no   = !empty($val->invoice_no)?$val->invoice_no:'';
									$distribut_id = !empty($val->distributor_id)?$val->distributor_id:'';
									$order_id     = !empty($val->order_id)?$val->order_id:'';
									$type_value   = !empty($val->type_id)?$val->type_id:'';
									$hsn_code     = !empty($val->hsn_code)?$val->hsn_code:'';
									$gst_val      = !empty($val->gst_val)?$val->gst_val:'';
									$price        = !empty($val->price)?$val->price:'';
									$order_qty    = !empty($val->order_qty)?$val->order_qty:'';
									$createdate   = !empty($val->createdate)?$val->createdate:'';

									// Order Details
									$where_2 = array(
										'id' => $order_id
									);

									$column_2 = 'order_no, emp_name, store_id, discount, due_days, _ordered';

									$ord_details = $this->order_model->getOrder($where_2, '', '', 'result', '', '', '', '', $column_2);
									$ord_res     = $ord_details[0];

									$order_no = !empty($ord_res->order_no)?$ord_res->order_no:'';
									$emp_name = !empty($ord_res->emp_name)?$ord_res->emp_name:'Admin';
									$store_id = !empty($ord_res->store_id)?$ord_res->store_id:'';
									$discount = !empty($ord_res->discount)?$ord_res->discount:'0';
									$due_days = !empty($ord_res->due_days)?$ord_res->due_days:'0';
									$ordered  = !empty($ord_res->_ordered)?$ord_res->_ordered:'';

									// Outlet Details
									$where_3 = array(
										'id' => $store_id
									);

									$column_3 = 'company_name, mobile, gst_no, address, state_id';

									$str_details = $this->outlets_model->getOutlets($where_3, '', '', 'result', '', '', '', '', $column_3);
									$str_res     = $str_details[0];

									$str_name = !empty($str_res->company_name)?$str_res->company_name:'';
									$mobile   = !empty($str_res->mobile)?$str_res->mobile:'';
									$gst_no   = !empty($str_res->gst_no)?$str_res->gst_no:'';
									$address  = !empty($str_res->address)?$str_res->address:'';
									$state_id = !empty($str_res->state_id)?$str_res->state_id:'';

									// State Details
									$where_4 = array(
										'id' => $state_id
									);

									$column_4 = 'gst_code, state_name';

									$gst_details = $this->commom_model->getState($where_4, '', '', 'result', '', '', '', '', $column_4);
									$gst_res     = $gst_details[0];

									$state_name = !empty($gst_res->state_name)?$gst_res->state_name:'';
									$gst_code   = !empty($gst_res->gst_code)?$gst_res->gst_code:'';

									// Product Details
									$where_5 = array(
										'id' => $type_value
									);

									$column_5 = 'description, product_price';

									$pdt_details = $this->commom_model->getProductType($where_5, '', '', 'result', '', '', '', '', $column_5);

									$pdt_res   = $pdt_details[0];
									$descrip   = !empty($pdt_res->description)?$pdt_res->description:'';
									$pdt_price = !empty($pdt_res->product_price)?$pdt_res->product_price:'';

									$where_6 = array(
										'id' => $distribut_id
									);

									$column_6 = 'company_name, state_id';

									$dis_details  = $this->distributors_model->getDistributors($where_6, '', '', 'result', '', '', '', '', $column_6);
									$dis_res      = $dis_details[0];

									$dis_username = !empty($dis_res->company_name)?$dis_res->company_name:'';
									$dis_state_id = !empty($dis_res->state_id)?$dis_res->state_id:'';

									$sales_details[] = array(
										'dis_username'  => $dis_username,
										'dis_state_id'  => $dis_state_id,
										'invoice_no'    => $invoice_no,
										'order_no'      => $order_no,
										'emp_name'      => $emp_name,
										'str_name'      => $str_name,
										'mobile'        => $mobile,
										'gst_no'        => $gst_no,
										'address'       => $address,
										'state_id'      => $state_id,
										'state_name'    => $state_name,
										'gst_code'      => $gst_code,
										'due_days'      => $due_days,
										'invoice_date'  => date('d-m-Y', strtotime($createdate)),
										'discount'      => $discount,
										'description'   => $descrip,
										'hsn_code'      => $hsn_code,
										'gst_val'       => $gst_val,
										'price'         => $price,
										'order_qty'     => $order_qty,
										'product_price' => $pdt_price,
									);
								}

								$response['status']  = 1;
						        $response['message'] = "Success"; 
						        $response['data']    = $sales_details;
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

				    	// Cancel Invoice Report
				    	else if($view_type == 2)
				    	{
				    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
					    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

					    	$where_1 = array(
								'createdate >=' => $start_value,
								'createdate <=' => $end_value,
								'cancel_status' => '2',
								'published'     => '1',
							);

							if(!empty($distributor_id))
							{
								$where_1['distributor_id'] = $distributor_id;
							}

							if(!empty($outlet_id))
							{
								$where_1['store_id'] = $outlet_id;
							}

					    	$column_1 = 'invoice_no, invoice_no, distributor_id, order_id, type_id, hsn_code, gst_val, price, order_qty, createdate';

							$inv_details = $this->invoice_model->getInvoiceDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

							if($inv_details)
							{
								$sales_details = [];
								foreach ($inv_details as $key => $val) {

									$invoice_no   = !empty($val->invoice_no)?$val->invoice_no:'';
									$distribut_id = !empty($val->distributor_id)?$val->distributor_id:'';
									$order_id     = !empty($val->order_id)?$val->order_id:'';
									$type_value   = !empty($val->type_id)?$val->type_id:'';
									$hsn_code     = !empty($val->hsn_code)?$val->hsn_code:'';
									$gst_val      = !empty($val->gst_val)?$val->gst_val:'';
									$price        = !empty($val->price)?$val->price:'';
									$order_qty    = !empty($val->order_qty)?$val->order_qty:'';
									$createdate   = !empty($val->createdate)?$val->createdate:'';

									// Order Details
									$where_2 = array(
										'id' => $order_id
									);

									$column_2 = 'order_no, emp_name, store_id, discount, due_days, _ordered';

									$ord_details = $this->order_model->getOrder($where_2, '', '', 'result', '', '', '', '', $column_2);
									$ord_res     = $ord_details[0];

									$order_no = !empty($ord_res->order_no)?$ord_res->order_no:'';
									$emp_name = !empty($ord_res->emp_name)?$ord_res->emp_name:'Admin';
									$store_id = !empty($ord_res->store_id)?$ord_res->store_id:'';
									$discount = !empty($ord_res->discount)?$ord_res->discount:'0';
									$due_days = !empty($ord_res->due_days)?$ord_res->due_days:'0';
									$ordered  = !empty($ord_res->_ordered)?$ord_res->_ordered:'';

									// Outlet Details
									$where_3 = array(
										'id' => $store_id
									);

									$column_3 = 'company_name, mobile, gst_no, address, state_id';

									$str_details = $this->outlets_model->getOutlets($where_3, '', '', 'result', '', '', '', '', $column_3);
									$str_res     = $str_details[0];

									$str_name = !empty($str_res->company_name)?$str_res->company_name:'';
									$mobile   = !empty($str_res->mobile)?$str_res->mobile:'';
									$gst_no   = !empty($str_res->gst_no)?$str_res->gst_no:'';
									$address  = !empty($str_res->address)?$str_res->address:'';
									$state_id = !empty($str_res->state_id)?$str_res->state_id:'';

									// State Details
									$where_4 = array(
										'id' => $state_id
									);

									$column_4 = 'gst_code, state_name';

									$gst_details = $this->commom_model->getState($where_4, '', '', 'result', '', '', '', '', $column_4);
									$gst_res     = $gst_details[0];

									$state_name = !empty($gst_res->state_name)?$gst_res->state_name:'';
									$gst_code   = !empty($gst_res->gst_code)?$gst_res->gst_code:'';

									// Product Details
									$where_5 = array(
										'id' => $type_value
									);

									$column_5 = 'description';

									$pdt_details = $this->commom_model->getProductType($where_5, '', '', 'result', '', '', '', '', $column_5);

									$pdt_res     = $pdt_details[0];

									$description  = !empty($pdt_res->description)?$pdt_res->description:'';

									$where_6 = array(
										'id' => $distribut_id
									);

									$column_6 = 'company_name, state_id';

									$dis_details  = $this->distributors_model->getDistributors($where_6, '', '', 'result', '', '', '', '', $column_6);
									$dis_res      = $dis_details[0];

									$dis_username = !empty($dis_res->company_name)?$dis_res->company_name:'';
									$dis_state_id = !empty($dis_res->state_id)?$dis_res->state_id:'';

									$sales_details[] = array(
										'dis_username' => $dis_username,
										'dis_state_id' => $dis_state_id,
										'invoice_no'   => $invoice_no,
										'order_no'     => $order_no,
										'emp_name'     => $emp_name,
										'str_name'     => $str_name,
										'mobile'       => $mobile,
										'gst_no'       => $gst_no,
										'address'      => $address,
										'state_id'     => $state_id,
										'state_name'   => $state_name,
										'gst_code'     => $gst_code,
										'due_days'     => $due_days,
										'invoice_date' => date('d-m-Y', strtotime($createdate)),
										'discount'     => $discount,
										'description'  => $description,
										'hsn_code'     => $hsn_code,
										'gst_val'      => $gst_val,
										'price'        => $price,
										'order_qty'    => $order_qty,
									);
								}

								$response['status']  = 1;
						        $response['message'] = "Success"; 
						        $response['data']    = $sales_details;
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

				    	// Invoice Report
				    	else if($view_type == 3)
				    	{
				    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
					    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

					    	$where_1 = array(
								'createdate >='  => $start_value,
								'createdate <='  => $end_value,
								'cancel_status'  => '1',
								'published'      => '1',
							);

							if(!empty($distributor_id))
							{
								$where_1['distributor_id'] = $distributor_id;
							}

							if(!empty($outlet_id))
							{
								$where_1['store_id'] = $outlet_id;
							}

							$column_1 = 'id, invoice_no, invoice_no, distributor_id, order_id, store_id, due_days, discount, createdate, random_value';	

							$inv_details = $this->invoice_model->getInvoice($where_1, '', '', 'result', '', '', '', '', $column_1);

							if($inv_details)
							{
								$sales_details = [];	
								foreach ($inv_details as $key => $val_1) {
									$inv_id     = !empty($val_1->id)?$val_1->id:'';
						            $inv_no     = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
						            $dis_id     = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
						            $order_id   = !empty($val_1->order_id)?$val_1->order_id:'';

						            $order_id   = !empty($val_1->order_id)?$val_1->order_id:'';
						            $store_id   = !empty($val_1->store_id)?$val_1->store_id:'';
						            $due_days   = !empty($val_1->due_days)?$val_1->due_days:'0';
						            $discount   = !empty($val_1->discount)?$val_1->discount:'0';
						            $createdate = !empty($val_1->createdate)?$val_1->createdate:'';
						            $random_val = !empty($val_1->random_value)?$val_1->random_value:'';

						            // Order Details
									$where_2 = array(
										'id' => $order_id
									);

									$column_2 = 'order_no, emp_name, store_id, zone_id, discount, due_days, _ordered';

									$ord_details = $this->order_model->getOrder($where_2, '', '', 'result', '', '', '', '', $column_2);
									$ord_res     = $ord_details[0];

									$order_no = !empty($ord_res->order_no)?$ord_res->order_no:'';
									$emp_name = !empty($ord_res->emp_name)?$ord_res->emp_name:'Admin';
									$store_id = !empty($ord_res->store_id)?$ord_res->store_id:'';
									$zone_id  = !empty($ord_res->zone_id)?$ord_res->zone_id:'';
									$ordered  = !empty($ord_res->_ordered)?$ord_res->_ordered:'';

									// Beat Details
						            $whr_6 = array('id' => $zone_id);
						            $col_6 = 'name';
						            $beat_data = $this->commom_model->getZone($whr_6, '', '', 'result', '', '', '', '', $col_6);

						            $beat_name = !empty($beat_data[0]->name)?$beat_data[0]->name:'';

									// Outlet Details
									$where_3 = array(
										'id' => $store_id
									);

									$column_3 = 'company_name, mobile, gst_no, address, state_id';

									$str_details = $this->outlets_model->getOutlets($where_3, '', '', 'result', '', '', '', '', $column_3);
									$str_res     = $str_details[0];

									$str_name = !empty($str_res->company_name)?$str_res->company_name:'';
									$mobile   = !empty($str_res->mobile)?$str_res->mobile:'';
									$gst_no   = !empty($str_res->gst_no)?$str_res->gst_no:'';
									$address  = !empty($str_res->address)?$str_res->address:'';
									$state_id = !empty($str_res->state_id)?$str_res->state_id:'';

									// State Details
									$where_4 = array(
										'id' => $state_id
									);

									$column_4 = 'gst_code, state_name';

									$gst_details = $this->commom_model->getState($where_4, '', '', 'result', '', '', '', '', $column_4);
									$gst_res     = $gst_details[0];

									$state_name = !empty($gst_res->state_name)?$gst_res->state_name:'';
									$gst_code   = !empty($gst_res->gst_code)?$gst_res->gst_code:'';

									$where_5 = array(
										'id' => $dis_id
									);

									$column_5 = 'company_name, state_id';

									$dis_details  = $this->distributors_model->getDistributors($where_5, '', '', 'result', '', '', '', '', $column_5);
									$dis_res      = $dis_details[0];

									$dis_username = !empty($dis_res->company_name)?$dis_res->company_name:'';
									$dis_state_id = !empty($dis_res->state_id)?$dis_res->state_id:'';

									// Invoice Details
									$where_6  = array(
										'invoice_id' => $inv_id,
										'published'  => '1',
									);

									$column_6 = 'gst_val, price, order_qty';

									$invData_details = $this->invoice_model->getInvoiceDetails($where_6, '', '', 'result', '', '', '', '', $column_6);

									$invData_list = [];
									if($invData_details)
									{
										foreach ($invData_details as $key => $val_6) {
											$gst_val   = !empty($val_6->gst_val)?$val_6->gst_val:'0';
											$price_val = !empty($val_6->price)?$val_6->price:'0';
											$order_qty = !empty($val_6->order_qty)?$val_6->order_qty:'0';

											$invData_list[] = array(
												'gst_val'   => $gst_val,
												'price_val' => $price_val,
												'order_qty' => $order_qty,
											);
										}
									}

									$sales_details[] = array(
										'dis_username' => $dis_username,
										'dis_state_id' => $dis_state_id,
										'invoice_no'   => $inv_no,
										'order_no'     => $order_no,
										'emp_name'     => $emp_name,
										'str_name'     => $str_name,
										'mobile'       => $mobile,
										'gst_no'       => $gst_no,
										'address'      => $address,
										'state_id'     => $state_id,
										'state_name'   => $state_name,
										'beat_name'    => $beat_name,
										'gst_code'     => $gst_code,
										'due_days'     => $due_days,
										'invoice_date' => date('d-m-Y', strtotime($createdate)),
										'discount'     => $discount,
										'random_val'   => $random_val,
										'invData_list' => $invData_list,
									);
								}

								$response['status']  = 1;
						        $response['message'] = "Success"; 
						        $response['data']    = $sales_details;
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

				    	// New Report
				    	else if($view_type == 4)
				    	{
				    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
					    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

					    	$whr_1 = array(
								'createdate >='  => $start_value,
								'createdate <='  => $end_value,
								'cancel_status'  => '1',
								'published'      => '1',
							);

							if(!empty($distributor_id))
							{
								$whr_1['distributor_id'] = $distributor_id;
							}

							$col_1 = 'id, invoice_id, type_id, order_qty, price, createdate';	

							$inv_details = $this->invoice_model->getInvoiceDetails($whr_1, '', '', 'result', '', '', '', '', $col_1);

							if($inv_details)
							{
								$sales_details = [];	
								foreach ($inv_details as $key => $val_1) {

									$auto_id    = !empty($val_1->id)?$val_1->id:'';
									$invoice_id = !empty($val_1->invoice_id)?$val_1->invoice_id:'';
						            $type_id    = !empty($val_1->type_id)?$val_1->type_id:'';
						            $order_qty  = !empty($val_1->order_qty)?$val_1->order_qty:'0';
						            $price_val  = !empty($val_1->price)?$val_1->price:'0';
						            $createdate = !empty($val_1->createdate)?$val_1->createdate:'';

						            // Invoice Details
						            $whr_2 = array('id' => $invoice_id);
						            $col_2 = 'sales_employee, store_id, city_id, zone_id, discount, createdate';
						            $inv_data = $this->invoice_model->getInvoice($whr_2, '', '', 'result', '', '', '', '', $col_2);

						            $sales_emp = !empty($inv_data[0]->sales_employee)?$inv_data[0]->sales_employee:'';
									$store_id  = !empty($inv_data[0]->store_id)?$inv_data[0]->store_id:'';
									$city_id   = !empty($inv_data[0]->city_id)?$inv_data[0]->city_id:'';
									$zone_id   = !empty($inv_data[0]->zone_id)?$inv_data[0]->zone_id:'';
									$discount  = !empty($inv_data[0]->discount)?$inv_data[0]->discount:'0';
									$inv_date  = !empty($inv_data[0]->createdate)?$inv_data[0]->createdate:'';

									// Outlet Details
									$whr_3 = array('id' => $store_id);
						            $col_3 = 'company_name';
						            $str_data = $this->outlets_model->getOutlets($whr_3, '', '', 'result', '', '', '', '', $col_3);

						            $str_name = !empty($str_data[0]->company_name)?$str_data[0]->company_name:'';

						            // Employee Details
						            $whr_4 = array('id' => $sales_emp);
						            $col_4 = 'first_name,last_name';
						            $emp_data = $this->employee_model->getEmployee($whr_4, '', '', 'result', '', '', '', '', $col_4);

						            $first_name = !empty($emp_data[0]->first_name)?$emp_data[0]->first_name:'Admin';
									$last_name = !empty($emp_data[0]->last_name)?$emp_data[0]->last_name:'';
									
										$arr = array($first_name,$last_name);
										$emp_name =join(" ",$arr);

						            // City Details
						            $whr_5 = array('id' => $city_id);
						            $col_5 = 'city_name';
						            $city_data = $this->commom_model->getCity($whr_5, '', '', 'result', '', '', '', '', $col_5);

						            $city_name = !empty($city_data[0]->city_name)?$city_data[0]->city_name:'';

						            // Beat Details
						            $whr_6 = array('id' => $zone_id);
						            $col_6 = 'name';
						            $beat_data = $this->commom_model->getZone($whr_6, '', '', 'result', '', '', '', '', $col_6);

						            $beat_name = !empty($beat_data[0]->name)?$beat_data[0]->name:'';

						            // Product Details
						            $whr_7 = array('id' => $type_id);
						            $col_7 = 'description';
						            $pdt_data = $this->commom_model->getProductType($whr_7, '', '', 'result', '', '', '', '', $col_7);

						            $pdt_name = !empty($pdt_data[0]->description)?$pdt_data[0]->description:'';

						            if(!empty($discount))
						            {
						            	$tot_price = $order_qty * $price_val;
						            	$inv_value = $tot_price * $discount / 100;
						            }
						            else
						            {
						            	$inv_value = $order_qty * $price_val;
						            }

						            $sales_details[] = array(
						            	'invoice_date'  => date('d-m-Y H:i:s', strtotime($inv_date)),
						            	'product_name'  => $pdt_name,
						            	'employee_name' => $emp_name,
						            	'city_name'     => $city_name,
						            	'beat_name'     => $beat_name,
						            	'store_name'    => $str_name,
						            	'order_qty'     => $order_qty,
						            	'invoice_value' => number_format((float)$inv_value, 2, '.', ''),
						            );
								}

								$response['status']  = 1;
						        $response['message'] = "Success"; 
						        $response['data']    = $sales_details;
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

				    	// Delivery Report
				    	else if($view_type == 5)
				    	{
				    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
					    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

					    	$whr_1 = array(
								'A.createdate >='  => $start_value,
								'A.createdate <='  => $end_value,
								'A.cancel_status'  => '1',
								'A.published'      => '1',
							);

							if(!empty($distributor_id))
							{
								$whr_1['A.distributor_id'] = $distributor_id;
							}

							$col_1 = 'A.date AS invoice_date, A.invoice_no, A.store_name, A.discount, A.delivery_date, B.order_no, B.emp_name, B.date AS order_date, C.city_name, D.name AS beat_name, SUM(E.price * E.receive_qty) AS invoice_total, F.company_name AS distributor_name';

							$grp_by = 'A.id';

							$inv_details = $this->invoice_model->getInvoiceJoin($whr_1, '', '', 'result', '', '', '', '', $col_1, $grp_by);
 
							if($inv_details)
							{
								$sales_details = [];	
								foreach ($inv_details as $key => $val) {
								    $dis_val = zero_check($val->discount);
								    $inv_tot = zero_check($val->invoice_total);

								    $sales_details[] = array(
								    	'invoice_date'  => date_check($val->invoice_date),
									    'order_date'    => date_check($val->order_date),
									    'order_no'      => empty_check($val->order_no),
									    'distributor'   => empty_check($val->distributor_name),
									    'emp_name'      => empty_check($val->emp_name),
									    'invoice_no'    => empty_check($val->invoice_no),
									    'store_name'    => empty_check($val->store_name),
									    'city_name'     => empty_check($val->city_name),
									    'beat_name'     => empty_check($val->beat_name),
									    'invoice_total' => discount_check($inv_tot, $dis_val),
									    'delivery_date' => date_check($val->delivery_date),
								    );
								}

								$response['status']  = 1;
						        $response['message'] = "Success"; 
						        $response['data']    = $sales_details;
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

			else if($method == '_overallInvoiceReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date', 'outlet_id');
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'store_id'      => $outlet_id,
							'cancel_status' => '1',
							'published'     => '1',
						);

						$column_1 = 'id, bill_type, order_id, invoice_no, distributor_id, sales_employee, delivery_employee, store_name, due_days, discount, createdate';

						$result_1 = $this->invoice_model->getInvoice($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($result_1)
						{
							$inv_list = [];
							foreach ($result_1 as $key => $val_1) {
								$inv_id     = !empty($val_1->id)?$val_1->id:'';
					            $bill_type  = !empty($val_1->bill_type)?$val_1->bill_type:'';
					            $order_id   = !empty($val_1->order_id)?$val_1->order_id:'';
					            $inv_no     = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
					            $dis_id     = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
					            $sales_emp  = !empty($val_1->sales_employee)?$val_1->sales_employee:'';
					            $del_emp    = !empty($val_1->delivery_employee)?$val_1->delivery_employee:'';
					            $store_name = !empty($val_1->store_name)?$val_1->store_name:'';
					            $due_days   = !empty($val_1->due_days)?$val_1->due_days:'0';
					            $discount   = !empty($val_1->discount)?$val_1->discount:'0';
					            $createdate = !empty($val_1->createdate)?$val_1->createdate:'';

					            // Order No
					            $whr_2 = array('id' => $order_id);
					            $col_2 = 'order_no, emp_name';
					            $res_2 = $this->order_model->getOrder($whr_2, '', '', 'result', '', '', '', '', $col_2);

					            $order_no = !empty($res_2[0]->order_no)?$res_2[0]->order_no:'';
								$emp_name = !empty($res_2[0]->emp_name)?$res_2[0]->emp_name:'';

								// Distrributor Details
								$whr_3 = array('id' => $dis_id);
					            $col_3 = 'company_name';
					            $res_3 = $this->distributors_model->getDistributors($whr_3, '', '', 'result', '', '', '', '', $col_3);

					            $com_name = !empty($res_3[0]->company_name)?$res_3[0]->company_name:'';

					            // Invoice Details
					            $whr_4 = array('invoice_id' => $inv_id, 'cancel_status' => '1', 'published' => '1');
					            $col_4 = 'price, receive_qty';
					            $res_4 = $this->invoice_model->getInvoiceDetails($whr_4, '', '', 'result', '', '', '', '', $col_4);

					            $inv_total = 0;
					            if($res_4)
					            {
					            	foreach ($res_4 as $key => $val_4) {
					            		$price_val   = !empty($val_4->price)?$val_4->price:'0';
										$receive_qty = !empty($val_4->receive_qty)?$val_4->receive_qty:'0';
										$price_tot   = $price_val * $receive_qty;
										$inv_total  += $price_tot;
					            	}
					            }

					           	// Round Val Details
		                        $net_value  = round($inv_total);
		                        if($discount != 0)
		                        {
		                        	$total_dis  = $net_value * $discount / 100;
		                        }
		                        else
		                        {
		                        	$total_dis = 0;	
		                        }

		                        $total_val  = $net_value - $total_dis;

		                        // Round Val Details
		                        $last_value = round($total_val);

		                        // Round Val Details
		                        $last_value = round($total_val);
		                        $rond_total = $last_value - $total_val;

					            $inv_list[] = array(
					            	'inv_id'           => $inv_id,
						            'bill_type'        => $bill_type,
						            'order_no'         => $order_no,
						            'inv_no'           => $inv_no,
						            'inv_date'         => date('d-M-Y', strtotime($createdate)),
						            'distributor_name' => $com_name,
						            'sales_employee'   => $emp_name,
						            'store_name'       => $store_name,
						            'due_days'         => $due_days,
						            'discount'         => $discount,
						            'round_value'      => strval($rond_total),
						            'inv_value'        => strval($last_value),
					            );
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $inv_list;
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

			else if($method == '_overallOrderReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date', 'outlet_id');
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >='   => $start_value,
							'createdate <='   => $end_value,
							'store_id'        => $outlet_id,
							'order_status !=' => '8',
							'published'       => '1',
						);

						$column_1 = 'id, order_no, emp_name, store_name, due_days, discount, createdate';

						$result_1 = $this->order_model->getOrder($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($result_1)
						{
							$ord_list = [];
							foreach ($result_1 as $key => $val_1) {

								$order_id   = !empty($val_1->id)?$val_1->id:'';
					            $order_no   = !empty($val_1->order_no)?$val_1->order_no:'';
					            $emp_name   = !empty($val_1->emp_name)?$val_1->emp_name:'';
					            $store_name = !empty($val_1->store_name)?$val_1->store_name:'';
					            $due_days   = !empty($val_1->due_days)?$val_1->due_days:'0';
					            $discount   = !empty($val_1->discount)?$val_1->discount:'0';
					            $createdate = !empty($val_1->createdate)?$val_1->createdate:'';

					             // Order Details
					            $whr_2 = array('order_id' => $order_id, 'published' => '1');
					            $col_2 = 'price, order_qty';
					            $res_2 = $this->order_model->getOrderDetails($whr_2, '', '', 'result', '', '', '', '', $col_2);

					            $ord_total = 0;
					            if($res_2)
					            {
					            	foreach ($res_2 as $key => $val_2) {
					            		$price_val  = !empty($val_2->price)?$val_2->price:'0';
										$order_qty  = !empty($val_2->order_qty)?$val_2->order_qty:'0';
										$price_tot  = $price_val * $order_qty;
										$ord_total += $price_tot;
					            	}
					            }

					            // Round Val Details
		                        $net_value  = round($ord_total);
		                        if($discount != 0)
		                        {
		                        	$total_dis  = $net_value * $discount / 100;
		                        }
		                        else
		                        {
		                        	$total_dis = 0;	
		                        }

		                        $total_val  = $net_value - $total_dis;

		                        // Round Val Details
		                        $last_value = round($total_val);

		                        // Round Val Details
		                        $last_value = round($total_val);
		                        $rond_total = $last_value - $total_val;

		                        $ord_list[] = array(
		                        	'order_no'    => $order_no,
		                        	'order_date'  => date('d-M-Y', strtotime($createdate)),
		                        	'emp_name'    => $emp_name,
		                        	'store_name'  => $store_name,
		                        	'due_days'    => $due_days,
		                        	'discount'    => $discount,
		                        	'round_value' => strval($rond_total),
						            'order_value' => strval($last_value),
		                        );
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $ord_list;
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

			else if($method == '_getOutletOutstandingData')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >='  => $start_value,
							'createdate <='  => $end_value,
							'distributor_id' => $distributor_id,
							'financial_year' => $financial_year,
							'cancel_status'  => '1',
							'status'         => '1',
							'published'      => '1',
						);

						$column_1 = 'id, bill_type, order_id, invoice_no, distributor_id, sales_employee, delivery_employee, store_id, store_name, state_id, city_id, zone_id, due_days, discount, createdate';

						$result_1 = $this->invoice_model->getInvoice($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($result_1)
						{
							$inv_list = [];
							foreach ($result_1 as $key => $val_1) {

								$auto_id  = !empty($val_1->id)?$val_1->id:'';
					            $bil_type = !empty($val_1->bill_type)?$val_1->bill_type:'';
					            $order_id = !empty($val_1->order_id)?$val_1->order_id:'';
					            $inv_no   = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
					            $dis_id   = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
					            $sal_emp  = !empty($val_1->sales_employee)?$val_1->sales_employee:'';
					            $del_emp  = !empty($val_1->delivery_employee)?$val_1->delivery_employee:'';
					            $str_id   = !empty($val_1->store_id)?$val_1->store_id:'';
					            $str_name = !empty($val_1->store_name)?$val_1->store_name:'';
					            $state_id = !empty($val_1->state_id)?$val_1->state_id:'';
					            $city_id  = !empty($val_1->city_id)?$val_1->city_id:'';
					            $zone_id  = !empty($val_1->zone_id)?$val_1->zone_id:'';
					            $due_days = !empty($val_1->due_days)?$val_1->due_days:'0';
					            $discount = !empty($val_1->discount)?$val_1->discount:'';
					            $cre_date = !empty($val_1->createdate)?$val_1->createdate:'';

					            // Order Details
					            $whr_2 = array('id' => $order_id);
					            $col_2 = 'order_no, _ordered';
					            $res_2 = $this->order_model->getOrder($whr_2, '', '', 'result', '', '', '', '', $col_2);

					            $order_no = !empty($res_2[0]->order_no)?$res_2[0]->order_no:'';
								$_ordered = !empty($res_2[0]->_ordered)?$res_2[0]->_ordered:'';

								// Employee Details
								$whr_3 = array('id' => $sal_emp);
					            $col_3 = 'first_name,last_name';
					            $res_3 = $this->employee_model->getEmployee($whr_3, '', '', 'result', '', '', '', '', $col_3);

					            $first_name = !empty($res_3[0]->first_name)?$res_3[0]->first_name:'Admin';
								$last_name = !empty($res_3[0]->last_name)?$res_3[0]->last_name:'';
								$arr = array($first_name,$last_name);
								$emp_name =join(" ",$arr);
					            // City Details
					            $whr_4 = array('id' => $city_id);
					            $col_4 = 'city_name';
					            $res_4 = $this->commom_model->getCity($whr_4, '', '', 'result', '', '', '', '', $col_4);

					            $city_name = !empty($res_4[0]->city_name)?$res_4[0]->city_name:'';

					            // Zone Details
					            $whr_5 = array('id' => $zone_id);
					            $col_5 = 'name';
					            $res_5 = $this->commom_model->getZone($whr_5, '', '', 'result', '', '', '', '', $col_5);

					            $zone_name = !empty($res_5[0]->name)?$res_5[0]->name:'';

					            // Invoice Value
					            $whr_6 = array(
					            	'bill_id'        => $order_id, 
					            	'distributor_id' => $dis_id,
					            	'outlet_id'      => $str_id,
					            	'published'      => '1',
					            );

					            $col_6 = 'amount, bal_amt';
					            $res_6 = $this->payment_model->getOutletPaymentDetails($whr_6, '', '', 'result', '', '', '', '', $col_6);

					            $amount_val = !empty($res_6[0]->amount)?$res_6[0]->amount:'0';
					            $bal_amount = !empty($res_6[0]->bal_amt)?$res_6[0]->bal_amt:'0';

					            // Pre Invoice Details
					            $whr_7 = array(
					            	'distributor_id' => $dis_id,
					            	'outlet_id'      => $str_id,
					            	'createdate <'   => $cre_date,
					            	'financial_id'   => $financial_year,
									'published'      => '1',
					            );

					            $col_7 = 'bal_amt';
					            $res_7 = $this->payment_model->getOutletPaymentDetails($whr_7, '', '', 'result', '', '', '', '', $col_7);

					            $tot_preVal = 0;
					            if(!empty($res_7))
					            {
					            	foreach ($res_7 as $key => $val_7) {
					            		$pre_amount  = !empty($val_7->bal_amt)?$val_7->bal_amt:'0';
					            		$tot_preVal += $pre_amount;
					            	}
					            }

					            // Cheque Collection Details
					            $whr_8 = array(
					            	'bill_code'      => 'CHQ',
					            	'bill_id'        => $order_id,
					            	'distributor_id' => $dis_id,
					            	'outlet_id'      => $str_id,
									'published'      => '1',
					            );

					            $col_8 = 'collect_date, date';
					            $res_8 = $this->payment_model->getOutletPayment($whr_8, '', '', 'result', '', '', '', '', $col_8);

					            $entr_date = '';
					            $coll_date = '';

					            if(!empty($res_8))
					            {
					            	foreach ($res_8 as $key => $val_8) {
					            			
										$entr_val = !empty($val_8->date)?$val_8->date:'';
					            		$coll_val = !empty($val_8->collect_date)?$val_8->collect_date:'';

					            		$entr_date .= date('d-M-Y', strtotime($entr_val)).' ,';
					            		$coll_date .= date('d-M-Y', strtotime($coll_val)).' ,';
					            	}
					            }


					            // Total Amount
					            $total_amt = $tot_preVal + $amount_val;
					            $paid_amt  = $amount_val - $bal_amount;
					            $last_amt  = $total_amt - $paid_amt;

					            // Due Days
					            $due_date = '';
					            if($due_days != 0)
					            {
					            	$inv_date = date('Y-m-d', strtotime($cre_date));
					            	$due_date = date('d-M-Y', strtotime($inv_date. ' + '.$due_days.' days'));
					            }

					            // Age of bill
					            $startTimeStamp = date('Y-m-d', strtotime($cre_date));
								$endTimeStamp   = date('Y-m-d');
								$numberDays     = dateDiffInDays($startTimeStamp, $endTimeStamp);

								if($numberDays == 0)
								{
									$bill_age = 'Today';
								}
								else if($numberDays == 1)
								{
									$bill_age = strval($numberDays).' Day';
								}
								else
								{
									$bill_age = strval($numberDays).' Days';
								}

					            $inv_list[] = array(
					            	'order_date'       => date('d-M-Y', strtotime($_ordered)),
					            	'order_no'         => $order_no,
					            	'bde_name'         => $emp_name,
					            	'invoice_date'     => date('d-M-Y', strtotime($cre_date)),
					            	'invoice_no'       => $inv_no,
					            	'outlet_name'      => $str_name,
					            	'reagion'          => $city_name,
					            	'beat_name'        => $zone_name,
					            	'opening_bal'      => number_format((float)$tot_preVal, 2, '.', ''),
					            	'pending_amt'      => number_format((float)$amount_val, 2, '.', ''),
					            	'post_cheque_date' => substr($entr_date, 0, -1),
					            	'coll_cheque_date' => substr($coll_date, 0, -1),
					            	'final_amt'        => number_format((float)$last_amt, 2, '.', ''),
					            	'due_on'           => $due_date,
					            	'age_of_bill'      => $bill_age,
					            );

							}

							$response['status']  = 1;
					  		$response['message'] = "Success"; 
					  		$response['data']    = $inv_list;
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
			  // for employee daily report
			else if($method == '_getDisplayImg'){
				$option['order_by']   = 'n_id';
				$option['disp_order'] = 'DESC';

				$att_id = $this->input->post('att_id');
				$where = array(
					'n_attendance'=> $att_id,
					'n_status'=>'1',
					'n_delete'=>'1'
				);

				$data_list = $this->attendance_model->getFileDetails($where, '', '', 'result', '', '', $option);

				if($data_list)
				{
					$img_list = [];

					foreach ($data_list as $key => $value) {

						$id   = isset($value->n_id)?$value->n_id:'';
						$attendance_id = isset($value->n_attendance)?$value->n_attendance:'';
			            $outlet_img     = isset($value->c_store_img)?$value->c_store_img:'';
			           

			            $img_list[] = array(
          					'id'   			=> $id,
          					'attendance_id' => $attendance_id,
				            'outlet_img'     => $outlet_img,
				           
          				);
					}
					if(!empty($img_list)){
						$response['status']  = 1;
						$response['message'] = "Success"; 
						$response['data']    = $img_list;
						echo json_encode($response);
						return;
					}else{
						$response['status']  = 0;
						$response['message'] = "No Data Found.."; 
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
        			
			}}
			else
			{
				$response['status']  = 0;
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// Tally Report
		// ***************************************************
		public function tally_report($param1="",$param2="",$param3="")
		{
			$distributor_id = $this->input->post('distributor_id');
			$start_date     = $this->input->post('start_date');
			$end_date       = $this->input->post('end_date');
			$method         = $this->input->post('method');

			if($method == '_distributorOverallReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >='  => $start_value,
							'createdate <='  => $end_value,
							'published'      => '1',
						);

						if($distributor_id)
						{
							$where_1['distributor_id'] = $distributor_id;
						}

						$column_1  = 'id, payment_id, distributor_id, bill_code, bill_no, amount, discount, date, pay_type, description, amt_type, value_type, createdate';

						$payment_data = $this->payment_model->getDistributorPayment($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($payment_data)
						{
							$payment_details = [];
							foreach ($payment_data as $key => $val_1) {
									
								$auto_id     = !empty($val_1->id)?$val_1->id:'';
					            $payment_id  = !empty($val_1->payment_id)?$val_1->payment_id:'';
					            $distri_id   = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
					            $bill_code   = !empty($val_1->bill_code)?$val_1->bill_code:'';
					            $bill_no     = !empty($val_1->bill_no)?$val_1->bill_no:'';
					            $amount      = !empty($val_1->amount)?$val_1->amount:'0';
					            $discount    = !empty($val_1->discount)?$val_1->discount:'0';
					            $pay_date    = !empty($val_1->date)?$val_1->date:'';
					            $pay_type    = !empty($val_1->pay_type)?$val_1->pay_type:'';
					            $description = !empty($val_1->description)?$val_1->description:'';
					            $amt_type    = !empty($val_1->amt_type)?$val_1->amt_type:'';
					            $value_type  = !empty($val_1->value_type)?$val_1->value_type:'';
					            $createdate  = !empty($val_1->createdate)?$val_1->createdate:'';
					            $payment     = $amount - $discount;

					            // Distributor Details
					            $whr_2   = array('id' => $distri_id);
					            $col_2   = 'company_name, state_id';
					            $dis_val = $this->distributors_model->getDistributors($whr_2, '', '', 'result', '', '', '', '', $col_2);

					            if($dis_val)
					            {
					            	$dis_res   = $dis_val[0];
						            $dis_name  = !empty($dis_res->company_name)?$dis_res->company_name:'';
	            					$dis_state = !empty($dis_res->state_id)?$dis_res->state_id:'';
					            }
					            else
					            {
						            $dis_name  = '';
	            					$dis_state = '';
					            }

					            // Admin Details
            					$whr_3   = array('id' => '1');
            					$col_3   = 'state_id';
            					$adm_val = $this->login_model->getLoginStatus($whr_3, '', '', 'result', '', '', '', '', $col_3);

            					if($adm_val)
            					{
            						$adm_res   = $adm_val[0];
            						$adm_state = !empty($adm_res->state_id)?$adm_res->state_id:'';
            					}
            					else
            					{
            						$adm_state = '';
            					}

            					
            					$payment_details[] = array(
            						'bill_code'   => strval($bill_code),
            						'bill_no'     => strval($bill_no),
            						'pay_date'    => $pay_date,
            						'dr_ledger'   => '',
            						'ref_type'    => '',
            						'new_ref'     => '',
            						'cost_center' => '',
            						'payment'     => strval($payment),
            						'cr_ledger'   => strval($dis_name),
            						'amount'      => strval($payment),
            						'vhr_nar'     => '',
            					);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $payment_details;
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

		// Payment Overall Report
		// ***************************************************
		public function payment_report($param1="",$param2="",$param3="")
		{
			$start_date     = $this->input->post('start_date');
			$end_date       = $this->input->post('end_date');
			$vendor_id      = $this->input->post('vendor_id');
			$outlet_id      = $this->input->post('outlet_id');
			$distributor_id = $this->input->post('distributor_id');
			$method         = $this->input->post('method');

			if($method == '_vendorPaymentReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'vendor_id'     => $vendor_id,
							'value_type'    => '2',
							'published'     => '1',
						);

						$column_1  = 'id, vendor_id, bill_code, bill_id, bill_no, pre_bal, cur_bal, amount, discount, pay_type, description, value_type, createdate';

						$payment_data = $this->payment_model->getVendorPayment($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($payment_data)
						{
							$payment_list = [];
							foreach ($payment_data as $key => $val_1) {
								$auto_id     = !empty($val_1->id)?$val_1->id:'';
					            $vendor_id   = !empty($val_1->vendor_id)?$val_1->vendor_id:'';
					            $bill_code   = !empty($val_1->bill_code)?$val_1->bill_code:'';
					            $bill_id     = !empty($val_1->bill_id)?$val_1->bill_id:'';
					            $bill_no     = !empty($val_1->bill_no)?$val_1->bill_no:'';
					            $pre_bal     = !empty($val_1->pre_bal)?$val_1->pre_bal:'0';
					            $cur_bal     = !empty($val_1->cur_bal)?$val_1->cur_bal:'0';
					            $amount      = !empty($val_1->amount)?$val_1->amount:'0';
					            $discount    = !empty($val_1->discount)?$val_1->discount:'0';
					            $pay_type    = !empty($val_1->pay_type)?$val_1->pay_type:'';
					            $description = !empty($val_1->description)?$val_1->description:'';
					            $value_type  = !empty($val_1->value_type)?$val_1->value_type:'';
					            $createdate  = !empty($val_1->createdate)?$val_1->createdate:'';

					            if($value_type == 1)
					            {
					            	$value_view = 'Order';
					            }
					            else if($value_type == 2)
					            {
					            	$value_view = 'Payment Collection';
					            }
					            else
					            {
					            	$value_view = 'Stock Return';
					            }

					            $payment_list[] = array(
					            	'auto_id'     => $auto_id,
						            'vendor_id'   => $vendor_id,
						            'bill_code'   => $bill_code,
						            'bill_id'     => $bill_id,
						            'bill_no'     => $bill_no,
						            'pre_bal'     => $pre_bal,
						            'cur_bal'     => $cur_bal,
						            'amount'      => $amount,
						            'discount'    => $discount,
						            'pay_type'    => $pay_type,
						            'description' => $description,
						            'value_type'  => $value_type,
						            'value_view'  => $value_view,
						            'createdate'  => date('d-m-Y H:i:s', strtotime($createdate)),
					            );

							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $payment_list;
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
			    	else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Invalid Date"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			    }
			}

			else if($method == '_distributorPaymentReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >='  => $start_value,
							'createdate <='  => $end_value,
							'distributor_id' => $distributor_id,
							'value_type'     => '2',
							'published'      => '1',
						);

						$column_1  = 'id, distributor_id, bill_code, bill_id, bill_no, pre_bal, cur_bal, amount, discount, pay_type, description, value_type, createdate';

						$payment_data = $this->payment_model->getDistributorPayment($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($payment_data)
						{
							$payment_list = [];
							foreach ($payment_data as $key => $val_1) {
								$auto_id        = !empty($val_1->id)?$val_1->id:'';
					            $distributor_id = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
					            $bill_code      = !empty($val_1->bill_code)?$val_1->bill_code:'';
					            $bill_id        = !empty($val_1->bill_id)?$val_1->bill_id:'';
					            $bill_no        = !empty($val_1->bill_no)?$val_1->bill_no:'';
					            $pre_bal        = !empty($val_1->pre_bal)?$val_1->pre_bal:'0';
					            $cur_bal        = !empty($val_1->cur_bal)?$val_1->cur_bal:'0';
					            $amount         = !empty($val_1->amount)?$val_1->amount:'0';
					            $discount       = !empty($val_1->discount)?$val_1->discount:'0';
					            $pay_type       = !empty($val_1->pay_type)?$val_1->pay_type:'';
					            $description    = !empty($val_1->description)?$val_1->description:'';
					            $value_type     = !empty($val_1->value_type)?$val_1->value_type:'';
					            $createdate     = !empty($val_1->createdate)?$val_1->createdate:'';

					            if($value_type == 1)
					            {
					            	$value_view = 'Order';
					            }
					            else if($value_type == 2)
					            {
					            	$value_view = 'Payment Collection';
					            }
					            else
					            {
					            	$value_view = 'Stock Return';
					            }

					            $payment_list[] = array(
					            	'auto_id'        => $auto_id,
						            'distributor_id' => $distributor_id,
						            'bill_code'      => $bill_code,
						            'bill_id'        => $bill_id,
						            'bill_no'        => $bill_no,
						            'pre_bal'        => $pre_bal,
						            'cur_bal'        => $cur_bal,
						            'amount'         => $amount,
						            'discount'       => $discount,
						            'pay_type'       => $pay_type,
						            'description'    => $description,
						            'value_type'     => $value_type,
						            'value_view'     => $value_view,
						            'createdate'     => date('d-m-Y H:i:s', strtotime($createdate)),
					            );

							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $payment_list;
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
			    	else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Invalid Date"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			    }
			}

			else if($method == '_outletPaymentReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date', 'outlet_id');
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'outlet_id'     => $outlet_id,
							'value_type'    => '2',
							'published'     => '1',
						);

						$column_1  = 'id, distributor_id, bill_code, bill_id, bill_no, pre_bal, cur_bal, amount, discount, pay_type, description, value_type, createdate';

						$payment_data = $this->payment_model->getOutletPayment($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($payment_data)
						{
							$payment_list = [];
							foreach ($payment_data as $key => $val_1) {
								$auto_id        = !empty($val_1->id)?$val_1->id:'';
					            $distributor_id = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
					            $bill_code      = !empty($val_1->bill_code)?$val_1->bill_code:'INV';
					            $bill_id        = !empty($val_1->bill_id)?$val_1->bill_id:'';
					            $bill_no        = !empty($val_1->bill_no)?$val_1->bill_no:'';
					            $pre_bal        = !empty($val_1->pre_bal)?$val_1->pre_bal:'0';
					            $cur_bal        = !empty($val_1->cur_bal)?$val_1->cur_bal:'0';
					            $amount         = !empty($val_1->amount)?$val_1->amount:'0';
					            $discount       = !empty($val_1->discount)?$val_1->discount:'0';
					            $pay_type       = !empty($val_1->pay_type)?$val_1->pay_type:'';
					            $description    = !empty($val_1->description)?$val_1->description:'';
					            $value_type     = !empty($val_1->value_type)?$val_1->value_type:'';
					            $createdate     = !empty($val_1->createdate)?$val_1->createdate:'';

					            if($value_type == 1)
					            {
					            	$value_view = 'Order';
					            }
					            else if($value_type == 2)
					            {
					            	$value_view = 'Payment Collection';
					            }
					            else
					            {
					            	$value_view = 'Stock Return';
					            }

					            $payment_list[] = array(
					            	'auto_id'        => $auto_id,
						            'distributor_id' => $distributor_id,
						            'bill_code'      => $bill_code,
						            'bill_id'        => $bill_id,
						            'bill_no'        => $bill_no,
						            'pre_bal'        => $pre_bal,
						            'cur_bal'        => $cur_bal,
						            'amount'         => $amount,
						            'discount'       => $discount,
						            'pay_type'       => $pay_type,
						            'description'    => $description,
						            'value_type'     => $value_type,
						            'value_view'     => $value_view,
						            'createdate'     => date('d-m-Y H:i:s', strtotime($createdate)),
					            );

							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $payment_list;
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
			    	else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Invalid Date"; 
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

		// Target Report
		// ***************************************************
		public function target_report($param1="",$param2="",$param3="")
		{
			$month_id    = $this->input->post('month_id');
			$year_id     = $this->input->post('year_id');
			$employee_id = $this->input->post('employee_id');
			$view_type   = $this->input->post('view_type');
			$method      = $this->input->post('method');

			if($method == '_employeeTargetReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id');
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
			    	$dateObj   = DateTime::createFromFormat('!m', $month_id);
					$monthName = $dateObj->format('F'); // March

					// Year Details
					$whr_1  = array(
						'id' => $year_id
					);

					$col_1  = 'year_value';
					$year_1 = $this->commom_model->getYear($whr_1, '', '', 'result', '', '', '', '', $col_1);

					$year_value = !empty($year_1[0]->year_value)?$year_1[0]->year_value:'';

					$where_1 = array(
						'month_name' => $monthName,
						'year_name'  => $year_value,
					);

					if(!empty($employee_id))
					{
						$where_1['employee_id'] = $employee_id;
					}

					$column_1 = 'employee_id, target_val, achieve_val';
					$data_1   = $this->target_model->getTargetDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

					if($data_1)
					{
						$target_list = [];
						foreach ($data_1 as $key => $val_1) {
							$employee_id = !empty($val_1->employee_id)?$val_1->employee_id:'';
				            $target_val  = !empty($val_1->target_val)?$val_1->target_val:'0';
				            $achieve_val = !empty($val_1->achieve_val)?$val_1->achieve_val:'0';

				            // Employee Details
				            $emp_whr = array(
				            	'id' => $employee_id,
				            );

				            $emp_col = 'first_name, last_name, mobile, email';
							$emp_res = $this->employee_model->getEmployee($emp_whr, '', '', 'result', '', '', '', '', $emp_col);
							$emp_val = $emp_res[0];

							$first_name = !empty($emp_val->first_name)?$emp_val->first_name:'';
							$last_name = !empty($emp_val->last_name)?$emp_val->last_name:'';
				            $mobile   = !empty($emp_val->mobile)?$emp_val->mobile:'';
				            $email    = !empty($emp_val->email)?$emp_val->email:'';
							$arr = array($first_name,$last_name);
							$username =join(" ",$arr);

				            $target_list[] = array(
				            	'employee_id' => $employee_id,
				            	'username'    => $username,
				            	'mobile'      => $mobile,
				            	'email'       => $email,
				            	'target_val'  => $target_val,
				            	'achieve_val' => $achieve_val,
				            );
						}

						$response['status']  = 1;
				        $response['message'] = "Target"; 
				        $response['data']    = $target_list;
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

			else if($method == '_employeeOrderReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('employee_id', 'month_id', 'year_id');
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
			    	// Year Details
					$whr_1  = array(
						'id' => $year_id
					);

					$col_1  = 'year_value';
					$data_1 = $this->commom_model->getYear($whr_1, '', '', 'result', '', '', '', '', $col_1);

					$year_value = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';

			    	$month_count = cal_days_in_month(CAL_GREGORIAN, $month_id, $year_value);

			    	$month_value = [];
			    	for ($i=1; $i <= $month_count; $i++) { 

			    		$date_val = date('d-m-Y', strtotime($i.'-'.$month_id.'-'.$year_value));
			    		$day_val  = date('l', strtotime($date_val));
			    		$cur_date = date('Y-m-d', strtotime($i.'-'.$month_id.'-'.$year_value));

			    		// Order Value
						$whr_2 = array(
							'emp_id'    => $employee_id,
							'date'      => $cur_date,
							'published' => '1',
							'status'    => '1',
						);

						$col_2  = 'id';

						$data_2 = $this->order_model->getOrder($whr_2, '', '', 'result', '', '', '', '', $col_2);

			    		$order_count = 0;
						$order_total = 0;

						if($data_2)
						{
							$order_val   = '';							
							foreach ($data_2 as $key => $val_2) {
								$ord_id = !empty($val_2->id)?$val_2->id:'';

								$order_val .= $ord_id.',';

								$order_count++;
							}

							$order_res = substr_replace($order_val, "", -1);

							$whr_3  = array(
								'order_id'  => $order_res,
								'published' => '1',
								'status'    => '1',
							);

							$col_3  = 'price, order_qty';

							$data_3 = $this->order_model->getOrderListDetails($whr_3, '', '', 'result', '', '', '', '', $col_3);

							foreach ($data_3 as $key => $val_3) {
								$price        = !empty($val_3->price)?$val_3->price:'';
								$order_qty    = !empty($val_3->order_qty)?$val_3->order_qty:'';
								$price_tot    = $order_qty * $price;
								$order_total += $price_tot;
							}
						}

						$total_value = round($order_total);

			    		$month_value[] = array(
			    			'date_value'  => $date_val,
			    			'day_value'   => $day_val,
			    			'order_count' => strval($order_count),
			    			'order_total' => strval($total_value),
			    		);
			    	}

			    	$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $month_value;
			        echo json_encode($response);
			        return; 
			    }
			}

			else if($method == '_employeeTargetDetails')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id', 'view_type');
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
				
					$employee_id   = $this->input->post('employee_id');
			    	$dateObj   = DateTime::createFromFormat('!m', $month_id);
					$monthName = $dateObj->format('F'); // March

			    	// Year Details
					$whr_1  = array('id' => $year_id);
					$col_1  = 'year_value';
					$year_1 = $this->commom_model->getYear($whr_1, '', '', 'result', '', '', '', '', $col_1);

					$year_value = !empty($year_1[0]->year_value)?$year_1[0]->year_value:'';
					
					$where_mg = array(
							
						'employee_id' => $employee_id,
					);


					$mg_val = $this->managers_model->getManagers($where_mg);
					$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id)?$mg_val[0]->ctrl_zone_id:'0';
					$ctrl_state = !empty($mg_val[0]->ctrl_state_id)?$mg_val[0]->ctrl_state_id:'0';
					$ctrl_city = !empty($mg_val[0]->ctrl_city_id)?$mg_val[0]->ctrl_city_id:'0';
					$designation_code = !empty($mg_val[0]->designation_code)?$mg_val[0]->designation_code:'0';
					
						
					if($view_type == 1)
					{
						
						if($designation_code == 'ASM'){

							$where_mg = array(
							
								'employee_id' => $employee_id,
							);
		
		
							$mg_val = $this->managers_model->getManagers($where_mg);
							$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id)?$mg_val[0]->ctrl_zone_id:'0';
							$ctrl_state = !empty($mg_val[0]->ctrl_state_id)?$mg_val[0]->ctrl_state_id:'0';
							$ctrl_city = !empty($mg_val[0]->ctrl_city_id)?$mg_val[0]->ctrl_city_id:'0';
							
	
							if($ctrl_state){
								$state_id_finall = substr($ctrl_state,1,-1);
						
		
								$d_state = !empty($state_id_finall)?$state_id_finall:'';
						
								$d_state_val = explode(',', $d_state);
								$st_count = count($d_state_val);
								$count_emp = [];
								$new_emp_id=[];
								for( $i=0; $i < $st_count; $i++){
	
	
	
									$wer = array(
										'designation_code'  => 'BDE', 
										'published'      => '1'
									);
									$like['ctrl_state_id'] =','. $d_state_val[$i].',';
		
									$co1 ='employee_id';
		
									$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);
								
									if(!empty($mg_val)){
											
										foreach ($mg_val as $key => $value) {
											array_push($count_emp,$value);
											
										
										}
									}
								}
							}
							if(!empty($count_emp)){
								$emp_c=count($count_emp);
							
							for( $i=0; $i < $emp_c; $i++){
								$new_emp_id[]   = !empty($count_emp[$i]->employee_id)?$count_emp[$i]->employee_id:'';
							 
							}
							}
							
						}else if($designation_code == 'RSM'){

							$where_mg = array(
							
								'employee_id' => $employee_id,
							);
		
		
							$mg_val = $this->managers_model->getManagers($where_mg);
							$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id)?$mg_val[0]->ctrl_zone_id:'0';
							$ctrl_state = !empty($mg_val[0]->ctrl_state_id)?$mg_val[0]->ctrl_state_id:'0';
							$ctrl_city = !empty($mg_val[0]->ctrl_city_id)?$mg_val[0]->ctrl_city_id:'0';
							
	
							if($ctrl_state){
								$state_id_finall = substr($ctrl_state,1,-1);
						
		
								$d_state = !empty($state_id_finall)?$state_id_finall:'';
						
								$d_state_val = explode(',', $d_state);
								$st_count = count($d_state_val);
								$count_emp = [];
								for( $i=0; $i < $st_count; $i++){
	
	
	
									$wer = array(
										'designation_code'  => 'BDE', 
										'published'      => '1'
									);
									$like['ctrl_state_id'] =','. $d_state_val[$i].',';
		
									$co1 ='employee_id';
		
									$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);
								
									if(!empty($mg_val)){
											
										foreach ($mg_val as $key => $value) {
											array_push($count_emp,$value);
											
										
										}
									}
								}

							}
							if(!empty($count_emp)){
								$emp_c=count($count_emp);
							    for( $i=0; $i < $emp_c; $i++){
								$new_emp_id[]   = !empty($count_emp[$i]->employee_id)?$count_emp[$i]->employee_id:'';
							 
							}
							}
							
							
						}else if($designation_code=='SO'){

							$where_mg = array(
							
								'employee_id' => $employee_id,
							);
		
		
							$mg_val = $this->managers_model->getManagers($where_mg);
							$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id)?$mg_val[0]->ctrl_zone_id:'0';
							$ctrl_state = !empty($mg_val[0]->ctrl_state_id)?$mg_val[0]->ctrl_state_id:'0';
							$ctrl_city = !empty($mg_val[0]->ctrl_city_id)?$mg_val[0]->ctrl_city_id:'0';
							
	
							if($ctrl_city){
								$city_id_finall = substr($ctrl_city,1,-1);
						
		
								$d_city = !empty($city_id_finall)?$city_id_finall:'';
						
								$d_city_val = explode(',', $d_city);
								$st_count = count($d_city_val);
								$count_emp = [];
								for( $i=0; $i < $st_count; $i++){
	
	
	
									$wer = array(
										'designation_code'  => 'BDE', 
										'published'      => '1'
									);
									$like['ctrl_city_id'] =','. $d_city_val[$i].',';
		
									$co1 ='employee_id';
		
									$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);
								 
									if(!empty($mg_val)){
											
										foreach ($mg_val as $key => $value) {
											array_push($count_emp,$value);
											
										
										}
									}
								}
							}

							$emp_c=count($count_emp);
							
							for( $i=0; $i < $emp_c; $i++){
								$new_emp_id[]   = !empty($count_emp[$i]->employee_id)?$count_emp[$i]->employee_id:'';
							 
							}
						}else if($designation_code=='TSI'){

							$where_mg = array(
							
								'employee_id' => $employee_id,
							);
		
		
							$mg_val = $this->managers_model->getManagers($where_mg);
							$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id)?$mg_val[0]->ctrl_zone_id:'0';
							$ctrl_state = !empty($mg_val[0]->ctrl_state_id)?$mg_val[0]->ctrl_state_id:'0';
							$ctrl_city = !empty($mg_val[0]->ctrl_city_id)?$mg_val[0]->ctrl_city_id:'0';
							
	
							if($ctrl_zone){
								$zone_id_finall = substr($ctrl_zone,1,-1);
						
		
								$d_zone = !empty($zone_id_finall)?$zone_id_finall:'';
						
								$d_zone_val = explode(',', $d_zone);
								$st_count = count($d_zone_val);
								$count_emp = [];
								for( $i=0; $i < $st_count; $i++){
	
	
	
									$wer = array(
										'designation_code'  => 'BDE', 
										'published'      => '1'
									);
									$like['ctrl_zone_id'] =','. $d_zone_val[$i].',';
		
									$co1 ='employee_id';
		
									$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);
								 
									if(!empty($mg_val)){
											
										foreach ($mg_val as $key => $value) {
											array_push($count_emp,$value);
											
										
										}
									}
								}
							}
							if(!empty($count_emp)){
								$emp_c=count($count_emp);
							
							for( $i=0; $i < $emp_c; $i++){
								$new_emp_id[]   = !empty($count_emp[$i]->employee_id)?$count_emp[$i]->employee_id:'';
							 
							}
							}
							
							
						}else if($designation_code=='BDE'){

								$new_emp_id[]   = $employee_id;
							
						}
						
							
							
					if(!empty($new_emp_id)){
						$where_1 = array(
							'month_name'    => $monthName,
							'year_name'     => $year_value,
							'target_val !=' => '0', 
							'published'     => '1',
						);

						
						
						$wher_in444['employee_id']  = $new_emp_id;

						$column_1 = 'employee_id, target_val, achieve_val';
						$data_1   = $this->target_model->getTargetDetails($where_1, '', '', 'result', '', '', '', '', $column_1,'',$wher_in444);
						
						if($data_1)
						{
							$target_list = [];
							$target =0;
							$achieve =0;
	                        foreach ($data_1 as $key => $val_1) {
	                            
	                            $target_val  = !empty($val_1->target_val)?$val_1->target_val:'0';
	                            $achieve_val = !empty($val_1->achieve_val)?$val_1->achieve_val:'0';
								$target += $target_val;
								$achieve += $achieve_val;
	                          
	                        };
							
							  // Employee Details
							  $emp_whr = array('id' => $employee_id);
							  $emp_col = 'first_name,last_name, mobile, email';
							  $emp_res = $this->employee_model->getEmployee($emp_whr, '', '', 'result', '', '', '', '', $emp_col);
							  $emp_val = $emp_res[0];
							  

							  $first_name = !empty($emp_val->first_name)?$emp_val->first_name:'';
							  $last_name = !empty($emp_val->last_name)?$emp_val->last_name:'';
							  $mobile   = !empty($emp_val->mobile)?$emp_val->mobile:'';
							  $arr = array($first_name,$last_name);
							  $username =join(" ",$arr);
							  $target_list[] = array(
								  'employee_id' => $employee_id,
								  'username'    => $username,
								  'mobile'      => $mobile,
								  'description' => '',
								  'target_val'  => $target,
								  'achieve_val' => $achieve,
							  );

	                        $response['status']  = 1;
	                        $response['message'] = "Target"; 
	                        $response['data']    = $target_list;
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
					}else
					{
						$response['status']  = 0;
						$response['message'] = "Data Not Found"; 
						$response['data']    = [];
						echo json_encode($response);
						return; 
					}
						
					}

					// Product Target Details
					else if($view_type == 2)
					{
						$where_1 = array(
							'month_name'    => $monthName,
							'year_name'     => $year_value,
							'target_val !=' => '0', 
							'published'     => '1',
						);

						if(!empty($employee_id))
						{
							$where_1['emp_id'] = $employee_id;
						}

						$column_1 = 'emp_id, description, target_val, achieve_val';
						$data_1   = $this->target_model->getProductTargetDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($data_1)
						{
							$target_list = [];
	                        foreach ($data_1 as $key => $val_1) {
	                        	$employee_id = !empty($val_1->emp_id)?$val_1->emp_id:'';
					            $description = !empty($val_1->description)?$val_1->description:'';
					            $target_val  = !empty($val_1->target_val)?$val_1->target_val:'0';
					            $achieve_val = !empty($val_1->achieve_val)?$val_1->achieve_val:'0';

					            // Employee Details
	                            $emp_whr = array('id' => $employee_id);
	                            $emp_col = 'first_name,last_name, mobile, email';
	                            $emp_res = $this->employee_model->getEmployee($emp_whr, '', '', 'result', '', '', '', '', $emp_col);
	                            $emp_val = $emp_res[0];

	                            $first_name = !empty($emp_val->first_name)?$emp_val->first_name:'';
								$last_name = !empty($emp_val->last_name)?$emp_val->last_name:'';
	                            $mobile   = !empty($emp_val->mobile)?$emp_val->mobile:'';
								$arr = array($first_name,$last_name);
								$username =join(" ",$arr);

	                            $target_list[] = array(
	                            	'employee_id' => $employee_id,
	                                'username'    => $username,
	                                'mobile'      => $mobile,
						            'description' => $description,
						            'target_val'  => $target_val,
						            'achieve_val' => $achieve_val,
	                            );
	                        }

	                        $response['status']  = 1;
	                        $response['message'] = "Target"; 
	                        $response['data']    = $target_list;
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

					// Beat Target Details
					else if($view_type == 3)
					{
						$where_1 = array(
							'month_name'    => $monthName,
							'year_name'     => $year_value,
							'target_val !=' => '0', 
							'published'     => '1',
						);

						if(!empty($employee_id))
						{
							$where_1['emp_id'] = $employee_id;
						}

						$column_1 = 'emp_id, zone_name, target_val, achieve_val';
						$data_1   = $this->target_model->getBeatTargetDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($data_1)
						{
							$target_list = [];
	                        foreach ($data_1 as $key => $val_1) {
	                        	$employee_id = !empty($val_1->emp_id)?$val_1->emp_id:'';
								$zone_name   = !empty($val_1->zone_name)?$val_1->zone_name:'';
								$target_val  = !empty($val_1->target_val)?$val_1->target_val:'0';
								$achieve_val = !empty($val_1->achieve_val)?$val_1->achieve_val:'0';

								// Employee Details
	                            $emp_whr = array('id' => $employee_id);
	                            $emp_col = 'first_name,last_name, mobile, email';
	                            $emp_res = $this->employee_model->getEmployee($emp_whr, '', '', 'result', '', '', '', '', $emp_col);
	                            $emp_val = $emp_res[0];

	                            $first_name = !empty($emp_val->first_name)?$emp_val->first_name:'';
								$last_name = !empty($emp_val->last_name)?$emp_val->last_name:'';
	                            $mobile   = !empty($emp_val->mobile)?$emp_val->mobile:'';
								$arr = array($first_name,$last_name);
								$username =join(" ",$arr);

	                            $target_list[] = array(
	                            	'employee_id' => $employee_id,
	                                'username'    => $username,
	                                'mobile'      => $mobile,
									'description' => $zone_name,
									'target_val'  => $target_val,
									'achieve_val' => $achieve_val,
	                            );
	                        }

	                        $response['status']  = 1;
	                        $response['message'] = "Target"; 
	                        $response['data']    = $target_list;
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

					else
					{
						$response['status']  = 0;
				        $response['message'] = "Error"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
					}
					
					// // Overall Target Details
					// if($view_type == 1)
					// {
					// 	$where_1 = array(
					// 		'month_name'    => $monthName,
					// 		'year_name'     => $year_value,
					// 		'target_val !=' => '0', 
					// 		'published'     => '1',
					// 	);

					// 	if(!empty($employee_id))
					// 	{
					// 		$where_1['employee_id'] = $employee_id;
					// 	}

					// 	$column_1 = 'employee_id, target_val, achieve_val';
					// 	$data_1   = $this->target_model->getTargetDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

					// 	if($data_1)
					// 	{
					// 		$target_list = [];
	                //         foreach ($data_1 as $key => $val_1) {
	                //             $employee_id = !empty($val_1->employee_id)?$val_1->employee_id:'';
	                //             $target_val  = !empty($val_1->target_val)?$val_1->target_val:'0';
	                //             $achieve_val = !empty($val_1->achieve_val)?$val_1->achieve_val:'0';

	                //             // Employee Details
	                //             $emp_whr = array('id' => $employee_id);
	                //             $emp_col = 'first_name,last_name, mobile, email';
	                //             $emp_res = $this->employee_model->getEmployee($emp_whr, '', '', 'result', '', '', '', '', $emp_col);
	                //             $emp_val = $emp_res[0];

	                //             $first_name = !empty($emp_val->first_name)?$emp_val->first_name:'';
					// 			$last_name = !empty($emp_val->last_name)?$emp_val->last_name:'';
	                //             $mobile   = !empty($emp_val->mobile)?$emp_val->mobile:'';
					// 			$arr = array($first_name,$last_name);
					// 			$username =join(" ",$arr);
	                //             $target_list[] = array(
	                //                 'employee_id' => $employee_id,
	                //                 'username'    => $username,
	                //                 'mobile'      => $mobile,
	                //                 'description' => '',
	                //                 'target_val'  => $target_val,
	                //                 'achieve_val' => $achieve_val,
	                //             );
	                //         }

	                //         $response['status']  = 1;
	                //         $response['message'] = "Target"; 
	                //         $response['data']    = $target_list;
	                //         echo json_encode($response);
	                //         return; 
					// 	}
					// 	else
	                //     {
	                //         $response['status']  = 0;
	                //         $response['message'] = "Data Not Found"; 
	                //         $response['data']    = [];
	                //         echo json_encode($response);
	                //         return; 
	                //     }
					// }

					// // Product Target Details
					// else if($view_type == 2)
					// {
					// 	$where_1 = array(
					// 		'month_name'    => $monthName,
					// 		'year_name'     => $year_value,
					// 		'target_val !=' => '0', 
					// 		'published'     => '1',
					// 	);

					// 	if(!empty($employee_id))
					// 	{
					// 		$where_1['emp_id'] = $employee_id;
					// 	}

					// 	$column_1 = 'emp_id, description, target_val, achieve_val';
					// 	$data_1   = $this->target_model->getProductTargetDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

					// 	if($data_1)
					// 	{
					// 		$target_list = [];
	                //         foreach ($data_1 as $key => $val_1) {
	                //         	$employee_id = !empty($val_1->emp_id)?$val_1->emp_id:'';
					//             $description = !empty($val_1->description)?$val_1->description:'';
					//             $target_val  = !empty($val_1->target_val)?$val_1->target_val:'0';
					//             $achieve_val = !empty($val_1->achieve_val)?$val_1->achieve_val:'0';

					//             // Employee Details
	                //             $emp_whr = array('id' => $employee_id);
	                //             $emp_col = 'first_name,last_name, mobile, email';
	                //             $emp_res = $this->employee_model->getEmployee($emp_whr, '', '', 'result', '', '', '', '', $emp_col);
	                //             $emp_val = $emp_res[0];

	                //             $first_name = !empty($emp_val->first_name)?$emp_val->first_name:'';
					// 			$last_name = !empty($emp_val->last_name)?$emp_val->last_name:'';
	                //             $mobile   = !empty($emp_val->mobile)?$emp_val->mobile:'';
					// 			$arr = array($first_name,$last_name);
					// 			$username =join(" ",$arr);

	                //             $target_list[] = array(
	                //             	'employee_id' => $employee_id,
	                //                 'username'    => $username,
	                //                 'mobile'      => $mobile,
					// 	            'description' => $description,
					// 	            'target_val'  => $target_val,
					// 	            'achieve_val' => $achieve_val,
	                //             );
	                //         }

	                //         $response['status']  = 1;
	                //         $response['message'] = "Target"; 
	                //         $response['data']    = $target_list;
	                //         echo json_encode($response);
	                //         return;
					// 	}
					// 	else
	                //     {
	                //         $response['status']  = 0;
	                //         $response['message'] = "Data Not Found"; 
	                //         $response['data']    = [];
	                //         echo json_encode($response);
	                //         return; 
	                //     }							
					// }

					// // Beat Target Details
					// else if($view_type == 3)
					// {
					// 	$where_1 = array(
					// 		'month_name'    => $monthName,
					// 		'year_name'     => $year_value,
					// 		'target_val !=' => '0', 
					// 		'published'     => '1',
					// 	);

					// 	if(!empty($employee_id))
					// 	{
					// 		$where_1['emp_id'] = $employee_id;
					// 	}

					// 	$column_1 = 'emp_id, zone_name, target_val, achieve_val';
					// 	$data_1   = $this->target_model->getBeatTargetDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

					// 	if($data_1)
					// 	{
					// 		$target_list = [];
	                //         foreach ($data_1 as $key => $val_1) {
	                //         	$employee_id = !empty($val_1->emp_id)?$val_1->emp_id:'';
					// 			$zone_name   = !empty($val_1->zone_name)?$val_1->zone_name:'';
					// 			$target_val  = !empty($val_1->target_val)?$val_1->target_val:'0';
					// 			$achieve_val = !empty($val_1->achieve_val)?$val_1->achieve_val:'0';

					// 			// Employee Details
	                //             $emp_whr = array('id' => $employee_id);
	                //             $emp_col = 'first_name,last_name, mobile, email';
	                //             $emp_res = $this->employee_model->getEmployee($emp_whr, '', '', 'result', '', '', '', '', $emp_col);
	                //             $emp_val = $emp_res[0];

	                //             $first_name = !empty($emp_val->first_name)?$emp_val->first_name:'';
					// 			$last_name = !empty($emp_val->last_name)?$emp_val->last_name:'';
	                //             $mobile   = !empty($emp_val->mobile)?$emp_val->mobile:'';
					// 			$arr = array($first_name,$last_name);
					// 			$username =join(" ",$arr);

	                //             $target_list[] = array(
	                //             	'employee_id' => $employee_id,
	                //                 'username'    => $username,
	                //                 'mobile'      => $mobile,
					// 				'description' => $zone_name,
					// 				'target_val'  => $target_val,
					// 				'achieve_val' => $achieve_val,
	                //             );
	                //         }

	                //         $response['status']  = 1;
	                //         $response['message'] = "Target"; 
	                //         $response['data']    = $target_list;
	                //         echo json_encode($response);
	                //         return;
					// 	}	
					// 	else
	                //     {
	                //         $response['status']  = 0;
	                //         $response['message'] = "Data Not Found"; 
	                //         $response['data']    = [];
	                //         echo json_encode($response);
	                //         return; 
	                //     }
					// }

					// else
					// {
					// 	$response['status']  = 0;
				    //     $response['message'] = "Error"; 
				    //     $response['data']    = [];
				    //     echo json_encode($response);
				    //     return; 
					// }
			    }
			}

			else if($method == '_employeeTargetData')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id', 'employee_id');
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
			    	$dateObj   = DateTime::createFromFormat('!m', $month_id);
					$monthName = $dateObj->format('F'); // March

			    	// Year Details
					$whr_1  = array('id' => $year_id);
					$col_1  = 'year_value';
					$year_1 = $this->commom_model->getYear($whr_1, '', '', 'result', '', '', '', '', $col_1);

					$year_value = !empty($year_1[0]->year_value)?$year_1[0]->year_value:'';
					$where_mg = array(
							
						'employee_id' => $employee_id,
					);


					$mg_val = $this->managers_model->getManagers($where_mg);
					$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id)?$mg_val[0]->ctrl_zone_id:'0';
					$ctrl_state = !empty($mg_val[0]->ctrl_state_id)?$mg_val[0]->ctrl_state_id:'0';
					$ctrl_city = !empty($mg_val[0]->ctrl_city_id)?$mg_val[0]->ctrl_city_id:'0';
					$designation_code = !empty($mg_val[0]->designation_code)?$mg_val[0]->designation_code:'0';
					if($designation_code=='BDE'){
							// Overall Target Details
						$where_1 = array(
							'month_name'    => $monthName,
							'year_name'     => $year_value,
							'employee_id'   => $employee_id,
							'target_val !=' => '0', 
							'published'     => '1',
						);

						$column_1 = 'employee_id, target_val, achieve_val';
						$data_1   = $this->target_model->getTargetDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($data_1)
						{
							// Overall Target Details
							$target_val  = !empty($data_1[0]->target_val)?$data_1[0]->target_val:'0';
							$achieve_val = !empty($data_1[0]->achieve_val)?$data_1[0]->achieve_val:'0';
							$achieve_per = $achieve_val / $target_val * 100;

							$overall_target = array(
								'overall_target_val'  => $target_val,
								'overall_achieve_val' => $achieve_val,
								'overall_achieve_per' => round($achieve_per),
							);

							// Product Target Details
							$where_2 = array(
								'month_name'    => $monthName,
								'year_name'     => $year_value,
								'emp_id'        => $employee_id,
								'target_val !=' => '0', 
								'published'     => '1',
							);

							$column_2 = 'description, target_val, achieve_val';
							$data_2   = $this->target_model->getProductTargetDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

							$product_target = [];
							if($data_2)
							{
								foreach ($data_2 as $key => $val_2) {
									$description     = !empty($val_2->description)?$val_2->description:'';
									$pdt_target_val  = !empty($val_2->target_val)?$val_2->target_val:'0';
									$pdt_achieve_val = !empty($val_2->achieve_val)?$val_2->achieve_val:'0';
									$pdt_achieve_per = $pdt_achieve_val / $pdt_target_val * 100;

									$product_target[] = array(
										'description'     => $description,
										'pdt_target_val'  => $pdt_target_val,
										'pdt_achieve_val' => $pdt_achieve_val,
										'pdt_achieve_per' => round($pdt_achieve_per),
									);
								}
							}

							// Beat Target Details
							$where_3 = array(
								'month_name'    => $monthName,
								'year_name'     => $year_value,
								'emp_id'        => $employee_id,
								'target_val !=' => '0', 
								'published'     => '1',
							);

							$column_3 = 'zone_name, target_val, achieve_val';
							$data_3   = $this->target_model->getBeatTargetDetails($where_3, '', '', 'result', '', '', '', '', $column_3);

							$beat_target = [];
							if($data_3)
							{
								foreach ($data_3 as $key => $val_3) {
									$zone_name        = !empty($val_3->zone_name)?$val_3->zone_name:'';
									$beat_target_val  = !empty($val_3->target_val)?$val_3->target_val:'0';
									$beat_achieve_val = !empty($val_3->achieve_val)?$val_3->achieve_val:'0';
									$beat_achieve_per = $beat_achieve_val / $beat_target_val * 100;

									$beat_target[] = array(
										'zone_name'        => $zone_name,
										'beat_target_val'  => $beat_target_val,
										'beat_achieve_val' => $beat_achieve_val,
										'beat_achieve_per' => round($beat_achieve_per),
									);
								}
							}

							$target_details[] = array(
								'overall_target' => $overall_target,
								'product_target' => $product_target,
								'beat_target'    => $beat_target,
							);

							$response['status']  = 1;
							$response['message'] = "Success"; 
							$response['data']    = $target_details;
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
					}else if($designation_code=='RSM'){
							// Overall Target Details
							if($ctrl_state){
								$state_id_finall = substr($ctrl_state,1,-1);
						
		
								$d_state = !empty($state_id_finall)?$state_id_finall:'';
						
								$d_state_val = explode(',', $d_state);
								$st_count = count($d_state_val);
								$count_emp = [];
								for( $i=0; $i < $st_count; $i++){
	
	
	
									$wer = array(
										'designation_code'  => 'BDE', 
										'published'      => '1'
									);
									$like['ctrl_state_id'] =','. $d_state_val[$i].',';
		
									$co1 ='employee_id';
		
									$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);
								
									if(!empty($mg_val)){
											
										foreach ($mg_val as $key => $value) {
											array_push($count_emp,$value);
											
										
										}
									}
								}

							}
							if(!empty($count_emp)){
								$emp_c=count($count_emp);
							    for( $i=0; $i < $emp_c; $i++){
								$new_emp_id[]   = !empty($count_emp[$i]->employee_id)?$count_emp[$i]->employee_id:'';
							 
							}
							}
							if(!empty($new_emp_id)){
								$where_1 = array(
									'month_name'    => $monthName,
									'year_name'     => $year_value,
									
									'target_val !=' => '0', 
									'published'     => '1',
								);
								$wher_in444['employee_id']  = $new_emp_id;
								$column_1 = 'employee_id, target_val, achieve_val';
								$data_1   = $this->target_model->getTargetDetails($where_1, '', '', 'result', '', '', '', '', $column_1,'',$wher_in444);
		
								if($data_1)
								{
									// Overall Target Details
									$target =0;
									$achieve =0;
									foreach ($data_1 as $key => $val_1) {
										
										$target_val  = !empty($val_1->target_val)?$val_1->target_val:'0';
										$achieve_val = !empty($val_1->achieve_val)?$val_1->achieve_val:'0';
										$target += $target_val;
										$achieve += $achieve_val;
									  
									};
									$achieve_per = $achieve / $target * 100;
		
									$overall_target = array(
										'overall_target_val'  => $target,
										'overall_achieve_val' => $achieve,
										'overall_achieve_per' => round($achieve_per),
									);
		
									// Product Target Details
									$where_2 = array(
										'month_name'    => $monthName,
										'year_name'     => $year_value,
										'emp_id'        => $employee_id,
										'target_val !=' => '0', 
										'published'     => '1',
									);
		
									$column_2 = 'description, target_val, achieve_val';
									$data_2   = $this->target_model->getProductTargetDetails($where_2, '', '', 'result', '', '', '', '', $column_2);
		
									$product_target = [];
									if($data_2)
									{
										foreach ($data_2 as $key => $val_2) {
											$description     = !empty($val_2->description)?$val_2->description:'';
											$pdt_target_val  = !empty($val_2->target_val)?$val_2->target_val:'0';
											$pdt_achieve_val = !empty($val_2->achieve_val)?$val_2->achieve_val:'0';
											$pdt_achieve_per = $pdt_achieve_val / $pdt_target_val * 100;
		
											$product_target[] = array(
												'description'     => $description,
												'pdt_target_val'  => $pdt_target_val,
												'pdt_achieve_val' => $pdt_achieve_val,
												'pdt_achieve_per' => round($pdt_achieve_per),
											);
										}
									}
		
									// Beat Target Details
									$where_3 = array(
										'month_name'    => $monthName,
										'year_name'     => $year_value,
										'emp_id'        => $employee_id,
										'target_val !=' => '0', 
										'published'     => '1',
									);
		
									$column_3 = 'zone_name, target_val, achieve_val';
									$data_3   = $this->target_model->getBeatTargetDetails($where_3, '', '', 'result', '', '', '', '', $column_3);
		
									$beat_target = [];
									if($data_3)
									{
										foreach ($data_3 as $key => $val_3) {
											$zone_name        = !empty($val_3->zone_name)?$val_3->zone_name:'';
											$beat_target_val  = !empty($val_3->target_val)?$val_3->target_val:'0';
											$beat_achieve_val = !empty($val_3->achieve_val)?$val_3->achieve_val:'0';
											$beat_achieve_per = $beat_achieve_val / $beat_target_val * 100;
		
											$beat_target[] = array(
												'zone_name'        => $zone_name,
												'beat_target_val'  => $beat_target_val,
												'beat_achieve_val' => $beat_achieve_val,
												'beat_achieve_per' => round($beat_achieve_per),
											);
										}
									}
		
									$target_details[] = array(
										'overall_target' => $overall_target,
										'product_target' => $product_target,
										'beat_target'    => $beat_target,
									);
		
									$response['status']  = 1;
									$response['message'] = "Success"; 
									$response['data']    = $target_details;
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
							}else
							{
								$response['status']  = 0;
								$response['message'] = "Data Not Found"; 
								$response['data']    = [];
								echo json_encode($response);
								return; 
							}
						
					} else if($designation_code=='ASM'){
						// Overall Target Details
							if($ctrl_state){
								$state_id_finall = substr($ctrl_state,1,-1);
						
		
								$d_state = !empty($state_id_finall)?$state_id_finall:'';
						
								$d_state_val = explode(',', $d_state);
								$st_count = count($d_state_val);
								$count_emp = [];
								for( $i=0; $i < $st_count; $i++){



									$wer = array(
										'designation_code'  => 'BDE', 
										'published'      => '1'
									);
									$like['ctrl_state_id'] =','. $d_state_val[$i].',';
		
									$co1 ='employee_id';
		
									$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);
								
									if(!empty($mg_val)){
											
										foreach ($mg_val as $key => $value) {
											array_push($count_emp,$value);
											
										
										}
									}
								}

							}
							if(!empty($count_emp)){
								$emp_c=count($count_emp);
								for( $i=0; $i < $emp_c; $i++){
								$new_emp_id[]   = !empty($count_emp[$i]->employee_id)?$count_emp[$i]->employee_id:'';
							
							}
							}
							if(!empty($new_emp_id)){
								$where_1 = array(
									'month_name'    => $monthName,
									'year_name'     => $year_value,
									
									'target_val !=' => '0', 
									'published'     => '1',
								);
								$wher_in444['employee_id']  = $new_emp_id;
								$column_1 = 'employee_id, target_val, achieve_val';
								$data_1   = $this->target_model->getTargetDetails($where_1, '', '', 'result', '', '', '', '', $column_1,'',$wher_in444);
		
								if($data_1)
								{
									// Overall Target Details
									$target =0;
									$achieve =0;
									foreach ($data_1 as $key => $val_1) {
										
										$target_val  = !empty($val_1->target_val)?$val_1->target_val:'0';
										$achieve_val = !empty($val_1->achieve_val)?$val_1->achieve_val:'0';
										$target += $target_val;
										$achieve += $achieve_val;
									
									};
									$achieve_per = $achieve / $target * 100;
		
									$overall_target = array(
										'overall_target_val'  => $target,
										'overall_achieve_val' => $achieve,
										'overall_achieve_per' => round($achieve_per),
									);
		
									// Product Target Details
									$where_2 = array(
										'month_name'    => $monthName,
										'year_name'     => $year_value,
										'emp_id'        => $employee_id,
										'target_val !=' => '0', 
										'published'     => '1',
									);
		
									$column_2 = 'description, target_val, achieve_val';
									$data_2   = $this->target_model->getProductTargetDetails($where_2, '', '', 'result', '', '', '', '', $column_2);
		
									$product_target = [];
									if($data_2)
									{
										foreach ($data_2 as $key => $val_2) {
											$description     = !empty($val_2->description)?$val_2->description:'';
											$pdt_target_val  = !empty($val_2->target_val)?$val_2->target_val:'0';
											$pdt_achieve_val = !empty($val_2->achieve_val)?$val_2->achieve_val:'0';
											$pdt_achieve_per = $pdt_achieve_val / $pdt_target_val * 100;
		
											$product_target[] = array(
												'description'     => $description,
												'pdt_target_val'  => $pdt_target_val,
												'pdt_achieve_val' => $pdt_achieve_val,
												'pdt_achieve_per' => round($pdt_achieve_per),
											);
										}
									}
		
									// Beat Target Details
									$where_3 = array(
										'month_name'    => $monthName,
										'year_name'     => $year_value,
										'emp_id'        => $employee_id,
										'target_val !=' => '0', 
										'published'     => '1',
									);
		
									$column_3 = 'zone_name, target_val, achieve_val';
									$data_3   = $this->target_model->getBeatTargetDetails($where_3, '', '', 'result', '', '', '', '', $column_3);
		
									$beat_target = [];
									if($data_3)
									{
										foreach ($data_3 as $key => $val_3) {
											$zone_name        = !empty($val_3->zone_name)?$val_3->zone_name:'';
											$beat_target_val  = !empty($val_3->target_val)?$val_3->target_val:'0';
											$beat_achieve_val = !empty($val_3->achieve_val)?$val_3->achieve_val:'0';
											$beat_achieve_per = $beat_achieve_val / $beat_target_val * 100;
		
											$beat_target[] = array(
												'zone_name'        => $zone_name,
												'beat_target_val'  => $beat_target_val,
												'beat_achieve_val' => $beat_achieve_val,
												'beat_achieve_per' => round($beat_achieve_per),
											);
										}
									}
		
									$target_details[] = array(
										'overall_target' => $overall_target,
										'product_target' => $product_target,
										'beat_target'    => $beat_target,
									);
		
									$response['status']  = 1;
									$response['message'] = "Success"; 
									$response['data']    = $target_details;
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
							}else
							{
								$response['status']  = 0;
								$response['message'] = "Data Not Found"; 
								$response['data']    = [];
								echo json_encode($response);
								return; 
							}
						
					}else if($designation_code=='SO'){
						// Overall Target Details
						if($ctrl_city){
							$city_id_finall = substr($ctrl_city,1,-1);
					

							$d_city = !empty($city_id_finall)?$city_id_finall:'';
					
							$d_city_val = explode(',', $d_city);
							$st_count = count($d_city_val);
							$count_emp = [];
							for( $i=0; $i < $st_count; $i++){



								$wer = array(
									'designation_code'  => 'BDE', 
									'published'      => '1'
								);
								$like['ctrl_city_id'] =','. $d_city_val[$i].',';

								$co1 ='employee_id';

								$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);
							
								if(!empty($mg_val)){
										
									foreach ($mg_val as $key => $value) {
										array_push($count_emp,$value);
										
									
									}
								}
							}

						}
						if(!empty($count_emp)){
							$emp_c=count($count_emp);
							for( $i=0; $i < $emp_c; $i++){
							$new_emp_id[]   = !empty($count_emp[$i]->employee_id)?$count_emp[$i]->employee_id:'';
						
						}
						}
						if(!empty($new_emp_id)){
							$where_1 = array(
								'month_name'    => $monthName,
								'year_name'     => $year_value,
								
								'target_val !=' => '0', 
								'published'     => '1',
							);
							$wher_in444['employee_id']  = $new_emp_id;
							$column_1 = 'employee_id, target_val, achieve_val';
							$data_1   = $this->target_model->getTargetDetails($where_1, '', '', 'result', '', '', '', '', $column_1,'',$wher_in444);
	
							if($data_1)
							{
								// Overall Target Details
								$target =0;
								$achieve =0;
								foreach ($data_1 as $key => $val_1) {
									
									$target_val  = !empty($val_1->target_val)?$val_1->target_val:'0';
									$achieve_val = !empty($val_1->achieve_val)?$val_1->achieve_val:'0';
									$target += $target_val;
									$achieve += $achieve_val;
								
								};
								$achieve_per = $achieve / $target * 100;
	
								$overall_target = array(
									'overall_target_val'  => $target,
									'overall_achieve_val' => $achieve,
									'overall_achieve_per' => round($achieve_per),
								);
	
								// Product Target Details
								$where_2 = array(
									'month_name'    => $monthName,
									'year_name'     => $year_value,
									'emp_id'        => $employee_id,
									'target_val !=' => '0', 
									'published'     => '1',
								);
	
								$column_2 = 'description, target_val, achieve_val';
								$data_2   = $this->target_model->getProductTargetDetails($where_2, '', '', 'result', '', '', '', '', $column_2);
	
								$product_target = [];
								if($data_2)
								{
									foreach ($data_2 as $key => $val_2) {
										$description     = !empty($val_2->description)?$val_2->description:'';
										$pdt_target_val  = !empty($val_2->target_val)?$val_2->target_val:'0';
										$pdt_achieve_val = !empty($val_2->achieve_val)?$val_2->achieve_val:'0';
										$pdt_achieve_per = $pdt_achieve_val / $pdt_target_val * 100;
	
										$product_target[] = array(
											'description'     => $description,
											'pdt_target_val'  => $pdt_target_val,
											'pdt_achieve_val' => $pdt_achieve_val,
											'pdt_achieve_per' => round($pdt_achieve_per),
										);
									}
								}
	
								// Beat Target Details
								$where_3 = array(
									'month_name'    => $monthName,
									'year_name'     => $year_value,
									'emp_id'        => $employee_id,
									'target_val !=' => '0', 
									'published'     => '1',
								);
	
								$column_3 = 'zone_name, target_val, achieve_val';
								$data_3   = $this->target_model->getBeatTargetDetails($where_3, '', '', 'result', '', '', '', '', $column_3);
	
								$beat_target = [];
								if($data_3)
								{
									foreach ($data_3 as $key => $val_3) {
										$zone_name        = !empty($val_3->zone_name)?$val_3->zone_name:'';
										$beat_target_val  = !empty($val_3->target_val)?$val_3->target_val:'0';
										$beat_achieve_val = !empty($val_3->achieve_val)?$val_3->achieve_val:'0';
										$beat_achieve_per = $beat_achieve_val / $beat_target_val * 100;
	
										$beat_target[] = array(
											'zone_name'        => $zone_name,
											'beat_target_val'  => $beat_target_val,
											'beat_achieve_val' => $beat_achieve_val,
											'beat_achieve_per' => round($beat_achieve_per),
										);
									}
								}
	
								$target_details[] = array(
									'overall_target' => $overall_target,
									'product_target' => $product_target,
									'beat_target'    => $beat_target,
								);
	
								$response['status']  = 1;
								$response['message'] = "Success"; 
								$response['data']    = $target_details;
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
						}else
						{
							$response['status']  = 0;
							$response['message'] = "Data Not Found"; 
							$response['data']    = [];
							echo json_encode($response);
							return; 
						}
						
			  		}else if($designation_code=='TSI'){
						// Overall Target Details
						if($ctrl_zone){
							$zone_id_finall = substr($ctrl_zone,1,-1);
					
	
							$d_zone = !empty($zone_id_finall)?$zone_id_finall:'';
					
							$d_zone_val = explode(',', $d_zone);
							$st_count = count($d_zone_val);
							$count_emp = [];
							for( $i=0; $i < $st_count; $i++){



								$wer = array(
									'designation_code'  => 'BDE', 
									'published'      => '1'
								);
								$like['ctrl_zone_id'] =','. $d_zone_val[$i].',';

								$co1 ='employee_id';

								$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);
							
								if(!empty($mg_val)){
										
									foreach ($mg_val as $key => $value) {
										array_push($count_emp,$value);
										
									
									}
								}
							}

						}
						if(!empty($count_emp)){
							$emp_c=count($count_emp);
							for( $i=0; $i < $emp_c; $i++){
							$new_emp_id[]   = !empty($count_emp[$i]->employee_id)?$count_emp[$i]->employee_id:'';
						
						 }
						}
						if(!empty($new_emp_id)){
							$where_1 = array(
								'month_name'    => $monthName,
								'year_name'     => $year_value,
								
								'target_val !=' => '0', 
								'published'     => '1',
							);
							$wher_in444['employee_id']  = $new_emp_id;
							$column_1 = 'employee_id, target_val, achieve_val';
							$data_1   = $this->target_model->getTargetDetails($where_1, '', '', 'result', '', '', '', '', $column_1,'',$wher_in444);
	
							if($data_1)
							{
								// Overall Target Details
								$target =0;
								$achieve =0;
								foreach ($data_1 as $key => $val_1) {
									
									$target_val  = !empty($val_1->target_val)?$val_1->target_val:'0';
									$achieve_val = !empty($val_1->achieve_val)?$val_1->achieve_val:'0';
									$target += $target_val;
									$achieve += $achieve_val;
								
								};
								$achieve_per = $achieve / $target * 100;
	
								$overall_target = array(
									'overall_target_val'  => $target,
									'overall_achieve_val' => $achieve,
									'overall_achieve_per' => round($achieve_per),
								);
	
								// Product Target Details
								$where_2 = array(
									'month_name'    => $monthName,
									'year_name'     => $year_value,
									'emp_id'        => $employee_id,
									'target_val !=' => '0', 
									'published'     => '1',
								);
	
								$column_2 = 'description, target_val, achieve_val';
								$data_2   = $this->target_model->getProductTargetDetails($where_2, '', '', 'result', '', '', '', '', $column_2);
	
								$product_target = [];
								if($data_2)
								{
									foreach ($data_2 as $key => $val_2) {
										$description     = !empty($val_2->description)?$val_2->description:'';
										$pdt_target_val  = !empty($val_2->target_val)?$val_2->target_val:'0';
										$pdt_achieve_val = !empty($val_2->achieve_val)?$val_2->achieve_val:'0';
										$pdt_achieve_per = $pdt_achieve_val / $pdt_target_val * 100;
	
										$product_target[] = array(
											'description'     => $description,
											'pdt_target_val'  => $pdt_target_val,
											'pdt_achieve_val' => $pdt_achieve_val,
											'pdt_achieve_per' => round($pdt_achieve_per),
										);
									}
								}
	
								// Beat Target Details
								$where_3 = array(
									'month_name'    => $monthName,
									'year_name'     => $year_value,
									'emp_id'        => $employee_id,
									'target_val !=' => '0', 
									'published'     => '1',
								);
	
								$column_3 = 'zone_name, target_val, achieve_val';
								$data_3   = $this->target_model->getBeatTargetDetails($where_3, '', '', 'result', '', '', '', '', $column_3);
	
								$beat_target = [];
								if($data_3)
								{
									foreach ($data_3 as $key => $val_3) {
										$zone_name        = !empty($val_3->zone_name)?$val_3->zone_name:'';
										$beat_target_val  = !empty($val_3->target_val)?$val_3->target_val:'0';
										$beat_achieve_val = !empty($val_3->achieve_val)?$val_3->achieve_val:'0';
										$beat_achieve_per = $beat_achieve_val / $beat_target_val * 100;
	
										$beat_target[] = array(
											'zone_name'        => $zone_name,
											'beat_target_val'  => $beat_target_val,
											'beat_achieve_val' => $beat_achieve_val,
											'beat_achieve_per' => round($beat_achieve_per),
										);
									}
								}
	
								$target_details[] = array(
									'overall_target' => $overall_target,
									'product_target' => $product_target,
									'beat_target'    => $beat_target,
								);
	
								$response['status']  = 1;
								$response['message'] = "Success"; 
								$response['data']    = $target_details;
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
						  }else
						  {
							  $response['status']  = 0;
							  $response['message'] = "Data Not Found"; 
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

		// Dashboard Report
		// ***************************************************
		public function dashboard_report($param1="",$param2="",$param3="")
		{
			$method  = $this->input->post('method');

			if($method == '_orderReport')
			{	
				// Custom Value
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

				$today_value = round($today_total);

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

				$week_value = round($week_total);

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
						// $month_total += 0;
					}
				}

				$month_value = round($month_total);

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

				$response['status']  = 1;
		        $response['message'] = "Success"; 
		        $response['data']    = $sale_report;
		        echo json_encode($response);
		        return;
			}

			else if($method == '_orderCountReport')
			{
				$month_val   = date('m');
				$year_val    = date('Y');
				$month_count = cal_days_in_month(CAL_GREGORIAN, $month_val, $year_val);

				$start_date  = '01-'.$month_val.'-'.$year_val;
    			$end_date    = $month_count.'-'.$month_val.'-'.$year_val;
    			$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
			    $end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

			    // Order Count
			    $where_1 = array(
    				'_ordered >='     => $start_value,
					'_ordered <='     => $end_value,
    				'published'       => '1'
    			);

				$column_1 = 'id';
				$result_1 = $this->order_model->getOrder($where_1, '', '', 'result', '', '', '', '', $column_1);
				$count_1  = !empty($result_1)?count($result_1):'0';

				// Process Order
				$where_2 = array(
    				'_processing >='   => $start_value,
					'_processing <='   => $end_value,
    				'published'        => '1'
    			);

				$column_2 = 'id';
				$result_2 = $this->order_model->getOrder($where_2, '', '', 'result', '', '', '', '', $column_2);
				$count_2  = !empty($result_2)?count($result_2):'0';

				// Cancel Count
			    $where_3 = array(
    				'_canceled >=' => $start_value,
					'_canceled <=' => $end_value,
    				'published'    => '1'
    			);

				$column_3 = 'id';
				$result_3 = $this->order_model->getOrder($where_3, '', '', 'result', '', '', '', '', $column_3);
				$count_3  = !empty($result_3)?count($result_3):'0';

				// Invoice Count
			    $where_4 = array(
    				'_complete >=' => $start_value,
					'_complete <=' => $end_value,
    				'published'    => '1'
    			);

				$column_4 = 'id';
				$result_4 = $this->order_model->getOrder($where_4, '', '', 'result', '', '', '', '', $column_4);
				$count_4  = !empty($result_4)?count($result_4):'0';

				$order_value = array(
					'success_order' => strval($count_1),
					'process_order' => strval($count_2),
					'cancel_order'  => strval($count_3),
					'invoice_order' => strval($count_4),
				);

				$response['status']  = 1;
                $response['message'] = "Success"; 
                $response['data']    = $order_value;
                echo json_encode($response);
                return; 
			}

			else if($method == '_attendaceReport')
			{
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

	        	$response['status']  = 1;
		        $response['message'] = "Success"; 
		        $response['data']    = $attendance_report;
		        echo json_encode($response);
		        return;
			}

			else if($method == '_distributorInvoiceReport')
			{
				// Today
				$today_value = date('Y-m-d');

				// Week wise report
				$week_start  = date('Y-m-d', strtotime("sunday -1 week"));
				$week_end    = date('Y-m-d', strtotime($week_start. "+7 day"));

				// Month wise report
				$month_count = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime('02')), date('Y'));
				$month_start = date('Y-m-d', strtotime('01-'.date('m').'-'.date('Y')));
				$month_end   = date('Y-m-d', strtotime($month_count.'-'.date('m').'-'.date('Y')));

				$whr_1 = array('distributor_type' => '1', 'status' => '1', 'published' => '1');
    			$col_1 = 'id, company_name';
				$res_1 = $this->distributors_model->getDistributors($whr_1, '', '', 'result', '', '', '', '', $col_1);

				if($res_1)
				{
					$ins_list = [];
					foreach ($res_1 as $key => $val_1) {
						$dis_id   = !empty($val_1->id)?$val_1->id:'';
            			$dis_name = !empty($val_1->company_name)?$val_1->company_name:'';

            			// Today Order
            			$start_today = date('Y-m-d H:i:s', strtotime($today_value. '00:00:00'));
			    		$end_today   = date('Y-m-d H:i:s', strtotime($today_value. '23:59:59'));

            			$whr_2 = array(
            				'distributor_id' => $dis_id,
							'createdate >='  => $start_today,
							'createdate <='  => $end_today,
							'cancel_status'  => '1',
							'status'         => '1',
							'published'      => '1',
						);

						$col_2 = 'COUNT(`id`) AS today_order';
						$res_2 = $this->invoice_model->getInvoice($whr_2, '', '', 'row', '', '', '', '', $col_2);

						$today_order = 0;
						if($res_2)
						{
							$today_order = zero_check($res_2->today_order);
						}

            			// This Week
            			$start_week = date('Y-m-d H:i:s', strtotime($week_start. '00:00:00'));
			    		$end_week   = date('Y-m-d H:i:s', strtotime($week_end. '23:59:59'));

            			$whr_3 = array(
            				'distributor_id' => $dis_id,
							'createdate >='  => $start_week,
							'createdate <='  => $end_week,
							'cancel_status'  => '1',
							'status'         => '1',
							'published'      => '1',
						);

						$col_3 = 'COUNT(`id`) AS week_order';
						$res_3 = $this->invoice_model->getInvoice($whr_3, '', '', 'row', '', '', '', '', $col_3);

						$week_order = 0;
						if($res_3)
						{
							$week_order = zero_check($res_3->week_order);
						}

            			// This Month
            			$start_month = date('Y-m-d H:i:s', strtotime($month_start. '00:00:00'));
			    		$end_month   = date('Y-m-d H:i:s', strtotime($month_end. '23:59:59'));

            			$whr_4 = array(
            				'distributor_id' => $dis_id,
							'createdate >='  => $start_month,
							'createdate <='  => $end_month,
							'cancel_status'  => '1',
							'status'         => '1',
							'published'      => '1',
						);

						$col_4 = 'COUNT(`id`) AS month_order';
						$res_4 = $this->invoice_model->getInvoice($whr_4, '', '', 'row', '', '', '', '', $col_4);

						$month_order = 0;
						if($res_4)
						{
							$month_order = zero_check($res_4->month_order);
						}

            			$ins_list[] = array(
            				'distributor_id'   => $dis_id,
            				'distributor_name' => $dis_name,
            				'today_order'      => $today_order,
            				'week_order'       => $week_order,
            				'month_order'      => $month_order,
            			);
					}

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $ins_list;
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

			else
			{
				$response['status']  = 0;
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// Dashboard Report
		// ***************************************************
		public function dashboard_report_mg($param1="",$param2="",$param3="")
		{
			$method  = $this->input->post('method');
			$manager_id  = $this->input->post('id');


				// Custom Value
				$month = date('m');
				$year  = date('Y');

				// Today Value
				$today = date('Y-m-d');

				$today_start = date('Y-m-d H:i:s', strtotime($today. '00:00:00'));
			    $today_end   = date('Y-m-d H:i:s', strtotime($today. '23:59:59'));

				$date_val   = new DateTime($today);
				$week_val   = $date_val->format("W");
				$week_array = getStartAndEndDate($week_val, $year);
				$week_start = $week_array['week_start'];
				$week_end   = $week_array['week_end'];

				$week_start = date('Y-m-d H:i:s', strtotime($week_start. '00:00:00'));
			    $week_end   = date('Y-m-d H:i:s', strtotime($week_end. '23:59:59'));

				$month_count = cal_days_in_month(CAL_GREGORIAN, $month, $year);
				$month_start = '01'.'-'.$month.'-'.$year;
				$start_date  = date('Y-m-d', strtotime($month_start));
				$month_end   = $month_count.'-'.$month.'-'.$year;
				$end_date    = date('Y-m-d', strtotime($month_end));

				$month_start = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
			    $month_end   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

			$where_mg = array(
						
				'employee_id' => $manager_id,
			);


			$mg_val = $this->managers_model->getManagers($where_mg);
			$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id)?$mg_val[0]->ctrl_zone_id:'0';
			$ctrl_state = !empty($mg_val[0]->ctrl_state_id)?$mg_val[0]->ctrl_state_id:'0';
			$ctrl_city = !empty($mg_val[0]->ctrl_city_id)?$mg_val[0]->ctrl_city_id:'0';
			$designation_code = !empty($mg_val[0]->designation_code)?$mg_val[0]->designation_code:'0';
			
			if($method == '_orderReport')
			{
			 if($designation_code=='RSM'||$designation_code=='ASM'){
				if(!empty($ctrl_state)){
					$state_id_finall = substr($ctrl_state,1,-1);
					//print_r($state_id_finall);exit;
	
					$d_state = !empty($state_id_finall)?$state_id_finall:'';
			
					$d_state_val = explode(',', $d_state);
					$st_count = count($d_state_val);
					$today_total = 0;
					$todayCount  = 0;
					$week_total = 0;
					$weekCount  = 0;
					$month_total = 0;
					$monthCount  = 0;
					for( $i=0; $i < $st_count; $i++){
						$today_where = array(
							// 'financial_year' => $financ_year,
							'C.createdate >='      => $today_start,
							'C.createdate <='      => $today_end,
							'C.product_process !=' => '8',
							'C.status'             => '1',
							'C.published'      => '1',
							'B.state_id'       => $d_state_val[$i],
						);

						$today_col ='C.id,C.order_no,C.price,C.order_qty';
						$today_val = $this->order_model->getOrderDetailsJoinbyZone($today_where, '', '', 'result', '', '', '','',$today_col);


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

						$todayCount_whr = array(
							'A.createdate >='   => $today_start,
							'A.createdate <='   => $today_end,
							'A.order_status !=' => '8',
							'A.published'       => '1',
							'A.status'          => '1',
							'B.state_id'       => $d_state_val[$i],
						);

						$today_col2 ='A.id';
						$today_count = $this->order_model->getOrderJoinbyZone($todayCount_whr, '', '', 'result', '', '', '','',$today_col2);

						if(!empty($today_count)){
							$todayCount += count($today_count);
						}

						$week_where = array(
							'C.createdate >='      => $week_start,
							'C.createdate <='      => $week_end,
							'C.product_process !=' => '8',
							'C.published'          => '1',
							'C.status'             => '1',
							'B.state_id'       => $d_state_val[$i],
						);

						$week_col ='C.id,C.order_no,C.price,C.order_qty';
						
						$week_val = $this->order_model->getOrderDetailsJoinbyZone($week_where, '', '', 'result', '', '', '', '', $week_col);
		
						
						if($week_val)
						{
							foreach ($week_val as $key => $week_data) {
								$price       = !empty($week_data->price)?$week_data->price:'0';
								$order_qty   = !empty($week_data->order_qty)?$week_data->order_qty:'0';
								$total_val   = $order_qty * number_format((float)$price, 2, '.', '');
								$week_total += $total_val;
							}
						}

						$weekCount_whr = array(
							'A.createdate >='   => $week_start,
							'A.createdate <='   => $week_end,
							'A.order_status !=' => '8',
							'A.published'       => '1',
							'A.status'          => '1',
							'B.state_id'       => $d_state_val[$i],
						);
						$week_col2 ='A.id';
						$week_count = $this->order_model->getOrderJoinbyZone($weekCount_whr, '', '', 'result', '', '', '', '', $week_col2);
		
						if(!empty($week_count)){
							$weekCount += count($week_count);
						}

						$month_where = array(
							'C.createdate >='      => $month_start,
							'C.createdate <='      => $month_end,
							'C.product_process !=' => '8',
							'C.published'          => '1',
							'C.status'             => '1',
							'B.state_id'       => $d_state_val[$i],
						);
		
						$month_col = 'C.id,C.order_no,C.price,C.order_qty';
		
						$month_val = $this->order_model->getOrderDetailsJoinbyZone($month_where, '', '', 'result', '', '', '', '', $month_col);
		
					
						if($month_val)
						{
							foreach ($month_val as $key => $month_data) {
		
								$price        = !empty($month_data->price)?$month_data->price:'0';
								$order_qty    = !empty($month_data->order_qty)?$month_data->order_qty:'0';
								$total_val    = $order_qty * number_format((float)$price, 2, '.', '');
								$month_total += $total_val;
								// $month_total += 0;
							}
						}

						$monthCount_whr = array(
							'A.createdate >='   => $month_start,
							'A.createdate <='   => $month_end,
							'A.order_status !=' => '8',
							'A.published'       => '1',
							'A.status'          => '1',
							'B.state_id'       => $d_state_val[$i],
						);
						$month_col2 ='A.id';
						$month_count = $this->order_model->getOrderJoinbyZone($monthCount_whr, '', '', 'result', '', '', '', '', $month_col2);
		
						if(!empty($month_count)){
							
							$monthCount += count($month_count);
							
						}

					}
					$today_value = round($today_total);
					$week_value = round($week_total);
					$month_value = round($month_total);
				}
			 }else if($designation_code=='SO'){
				if(!empty($ctrl_city)){
					$city_id_finall = substr($ctrl_city,1,-1);
					//print_r($city_id_finall);exit;
	
					$d_city = !empty($city_id_finall)?$city_id_finall:'';
			
					$d_city_val = explode(',', $d_city);
					$ct_count = count($d_city_val);
					$today_total = 0;
					$todayCount  = 0;
					$week_total = 0;
					$weekCount  = 0;
					$month_total = 0;
					$monthCount  = 0;
					for( $i=0; $i < $ct_count; $i++){
						$today_where = array(
							// 'financial_year' => $financ_year,
							'C.createdate >='      => $today_start,
							'C.createdate <='      => $today_end,
							'C.product_process !=' => '8',
							'C.status'             => '1',
							'C.published'      => '1',
							'B.city_id'       => $d_city_val[$i],
						);

						$today_col ='C.id,C.order_no,C.price,C.order_qty';
						$today_val = $this->order_model->getOrderDetailsJoinbyZone($today_where, '', '', 'result', '', '', '','',$today_col);


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

						$todayCount_whr = array(
							'A.createdate >='   => $today_start,
							'A.createdate <='   => $today_end,
							'A.order_status !=' => '8',
							'A.published'       => '1',
							'A.status'          => '1',
							'B.city_id'       => $d_city_val[$i],
						);

						$today_col2 ='A.id';
						$today_count = $this->order_model->getOrderJoinbyZone($todayCount_whr, '', '', 'result', '', '', '','',$today_col2);

						if(!empty($today_count)){
							$todayCount += count($today_count);
						}

						$week_where = array(
							'C.createdate >='      => $week_start,
							'C.createdate <='      => $week_end,
							'C.product_process !=' => '8',
							'C.published'          => '1',
							'C.status'             => '1',
							'B.city_id'       => $d_city_val[$i],
						);

						$week_col ='C.id,C.order_no,C.price,C.order_qty';
						
						$week_val = $this->order_model->getOrderDetailsJoinbyZone($week_where, '', '', 'result', '', '', '', '', $week_col);
		
						
						if($week_val)
						{
							foreach ($week_val as $key => $week_data) {
								$price       = !empty($week_data->price)?$week_data->price:'0';
								$order_qty   = !empty($week_data->order_qty)?$week_data->order_qty:'0';
								$total_val   = $order_qty * number_format((float)$price, 2, '.', '');
								$week_total += $total_val;
							}
						}

						$weekCount_whr = array(
							'A.createdate >='   => $week_start,
							'A.createdate <='   => $week_end,
							'A.order_status !=' => '8',
							'A.published'       => '1',
							'A.status'          => '1',
							'B.city_id'       => $d_city_val[$i],
						);
						$week_col2 ='A.id';
						$week_count = $this->order_model->getOrderJoinbyZone($weekCount_whr, '', '', 'result', '', '', '', '', $week_col2);
		
						if(!empty($week_count)){
							$weekCount += count($week_count);
						}

						$month_where = array(
							'C.createdate >='      => $month_start,
							'C.createdate <='      => $month_end,
							'C.product_process !=' => '8',
							'C.published'          => '1',
							'C.status'             => '1',
							'B.city_id'       => $d_city_val[$i],
						);
		
						$month_col = 'C.id,C.order_no,C.price,C.order_qty';
		
						$month_val = $this->order_model->getOrderDetailsJoinbyZone($month_where, '', '', 'result', '', '', '', '', $month_col);
		
					
						if($month_val)
						{
							foreach ($month_val as $key => $month_data) {
		
								$price        = !empty($month_data->price)?$month_data->price:'0';
								$order_qty    = !empty($month_data->order_qty)?$month_data->order_qty:'0';
								$total_val    = $order_qty * number_format((float)$price, 2, '.', '');
								$month_total += $total_val;
								// $month_total += 0;
							}
						}

						$monthCount_whr = array(
							'A.createdate >='   => $month_start,
							'A.createdate <='   => $month_end,
							'A.order_status !=' => '8',
							'A.published'       => '1',
							'A.status'          => '1',
							'B.city_id'       => $d_city_val[$i],
						);
						$month_col2 ='A.id';
						$month_count = $this->order_model->getOrderJoinbyZone($monthCount_whr, '', '', 'result', '', '', '', '', $month_col2);
		
						if(!empty($month_count)){
							
							$monthCount += count($month_count);
							
						}

					}
					$today_value = round($today_total);
					$week_value = round($week_total);
					$month_value = round($month_total);
				}
			 }else if($designation_code=='TSI'){
				if(!empty($ctrl_zone)){
					$zone_id_finall = substr($ctrl_zone,1,-1);
					//print_r($zone_id_finall);exit;
	
					$d_zone = !empty($zone_id_finall)?$zone_id_finall:'';
			
					$d_zone_val = explode(',', $d_zone);
					$ct_count = count($d_zone_val);
					$today_total = 0;
					$todayCount  = 0;
					$week_total = 0;
					$weekCount  = 0;
					$month_total = 0;
					$monthCount  = 0;
					for( $i=0; $i < $ct_count; $i++){
						$today_where = array(
							// 'financial_year' => $financ_year,
							'C.createdate >='      => $today_start,
							'C.createdate <='      => $today_end,
							'C.product_process !=' => '8',
							'C.status'             => '1',
							'C.published'      => '1',
							'B.id'       => $d_zone_val[$i],
						);

						$today_col ='C.id,C.order_no,C.price,C.order_qty';
						$today_val = $this->order_model->getOrderDetailsJoinbyZone($today_where, '', '', 'result', '', '', '','',$today_col);


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

						$todayCount_whr = array(
							'A.createdate >='   => $today_start,
							'A.createdate <='   => $today_end,
							'A.order_status !=' => '8',
							'A.published'       => '1',
							'A.status'          => '1',
							'B.id'       => $d_zone_val[$i],
						);

						$today_col2 ='A.id';
						$today_count = $this->order_model->getOrderJoinbyZone($todayCount_whr, '', '', 'result', '', '', '','',$today_col2);

						if(!empty($today_count)){
							$todayCount += count($today_count);
						}

						$week_where = array(
							'C.createdate >='      => $week_start,
							'C.createdate <='      => $week_end,
							'C.product_process !=' => '8',
							'C.published'          => '1',
							'C.status'             => '1',
							'B.id'       => $d_zone_val[$i],
						);

						$week_col ='C.id,C.order_no,C.price,C.order_qty';
						
						$week_val = $this->order_model->getOrderDetailsJoinbyZone($week_where, '', '', 'result', '', '', '', '', $week_col);
		
						
						if($week_val)
						{
							foreach ($week_val as $key => $week_data) {
								$price       = !empty($week_data->price)?$week_data->price:'0';
								$order_qty   = !empty($week_data->order_qty)?$week_data->order_qty:'0';
								$total_val   = $order_qty * number_format((float)$price, 2, '.', '');
								$week_total += $total_val;
							}
						}

						$weekCount_whr = array(
							'A.createdate >='   => $week_start,
							'A.createdate <='   => $week_end,
							'A.order_status !=' => '8',
							'A.published'       => '1',
							'A.status'          => '1',
							'B.id'       => $d_zone_val[$i],
						);
						$week_col2 ='A.id';
						$week_count = $this->order_model->getOrderJoinbyZone($weekCount_whr, '', '', 'result', '', '', '', '', $week_col2);
		
						if(!empty($week_count)){
							$weekCount += count($week_count);
						}

						$month_where = array(
							'C.createdate >='      => $month_start,
							'C.createdate <='      => $month_end,
							'C.product_process !=' => '8',
							'C.published'          => '1',
							'C.status'             => '1',
							'B.id'            => $d_zone_val[$i],
						);
		
						$month_col = 'C.id,C.order_no,C.price,C.order_qty';
		
						$month_val = $this->order_model->getOrderDetailsJoinbyZone($month_where, '', '', 'result', '', '', '', '', $month_col);
		
					
						if($month_val)
						{
							foreach ($month_val as $key => $month_data) {
		
								$price        = !empty($month_data->price)?$month_data->price:'0';
								$order_qty    = !empty($month_data->order_qty)?$month_data->order_qty:'0';
								$total_val    = $order_qty * number_format((float)$price, 2, '.', '');
								$month_total += $total_val;
								// $month_total += 0;
							}
						}

						$monthCount_whr = array(
							'A.createdate >='   => $month_start,
							'A.createdate <='   => $month_end,
							'A.order_status !=' => '8',
							'A.published'       => '1',
							'A.status'          => '1',
							'B.id'         => $d_zone_val[$i],
						);
						$month_col2 ='A.id';
						$month_count = $this->order_model->getOrderJoinbyZone($monthCount_whr, '', '', 'result', '', '', '', '', $month_col2);
		
						if(!empty($month_count)){
							
							$monthCount += count($month_count);
							
						}

					}
					$today_value = round($today_total);
					$week_value = round($week_total);
					$month_value = round($month_total);
				}
			 }
			

				$sale_report = array(
					'today_count' => strval($todayCount),
					'today_value' => strval($today_value),
					'week_count'  => strval($weekCount),
					'week_value'  => strval($week_value),
					'month_count' => strval($monthCount),
					'month_value' => strval($month_value),
				);

				$response['status']  = 1;
		        $response['message'] = "Success"; 
		        $response['data']    = $sale_report;
		        echo json_encode($response);
		        return;
			}

			else if($method == '_orderCountReport')
			{

				if($designation_code=='RSM'||$designation_code=='ASM'){
					if(!empty($ctrl_state)){
						$state_id_finall = substr($ctrl_state,1,-1);
						//print_r($state_id_finall);exit;
		
						$d_state = !empty($state_id_finall)?$state_id_finall:'';
				
						$d_state_val = explode(',', $d_state);
						$st_count = count($d_state_val);
						$count_1 = 0;
						$count_2  = 0;
						$count_3  = 0;
						$count_4  = 0;
						for( $i=0; $i < $st_count; $i++){
							$where_1 = array(
								'A._ordered >='     => $month_start,
								'A._ordered <='     => $month_end,
								'A.published'       => '1',
								'B.state_id'       => $d_state_val[$i],
							);
			
							$column_1 = 'A.id';
							$result_1 = $this->order_model->getOrderJoinbyZone($where_1, '', '', 'result', '', '', '','',$column_1);
							if(!empty($result_1)){
								$count_1 += count($result_1);
							}
			
							// Process Order
							$where_2 = array(
								'A._processing >='   => $month_start,
								'A._processing <='   => $month_end,
								'A.published'        => '1',
								'B.state_id'       => $d_state_val[$i],
							);
			
							$column_2 = 'A.id';
							$result_2 = $this->order_model->getOrderJoinbyZone($where_2, '', '', 'result', '', '', '','',$column_2);
							if(!empty($result_2)){
								$count_2 += count($result_2);
							}
							// Cancel Count
							$where_3 = array(
								'A._canceled >=' => $month_start,
								'A._canceled <=' => $month_end,
								'A.published'    => '1',
								'B.state_id'       => $d_state_val[$i],
							);
			
							$column_3 = 'A.id';
							$result_3 = $this->order_model->getOrderJoinbyZone($where_3, '', '', 'result', '', '', '','',$column_3);
							
							if(!empty($result_3)){
								$count_3 += count($result_3);
							}
							// Invoice Count
							$where_4 = array(
								'A._complete >=' => $month_start,
								'A._complete <=' => $month_end,
								'A.published'    => '1',
								'B.state_id'       => $d_state_val[$i],
							);
			
							$column_4 = 'A.id';
							$result_4 = $this->order_model->getOrderJoinbyZone($where_4, '', '', 'result', '', '', '','',$column_4);
						
							if(!empty($result_4)){
								$count_4 += count($result_4);
							}
			
					    }
					}
				}else if($designation_code=='SO'){
					if(!empty($ctrl_city)){
						$city_id_finall = substr($ctrl_city,1,-1);
						
		
						$d_city = !empty($city_id_finall)?$city_id_finall:'';
				
						$d_city_val = explode(',', $d_city);
						$ct_count = count($d_city_val);
						$count_1 = 0;
						$count_2  = 0;
						$count_3  = 0;
						$count_4  = 0;
						for( $i=0; $i < $ct_count; $i++){
							$where_1 = array(
								'A._ordered >='     => $month_start,
								'A._ordered <='     => $month_end,
								'A.published'       => '1',
								'B.city_id'       => $d_city_val[$i],
							);
			
							$column_1 = 'A.id';
							$result_1 = $this->order_model->getOrderJoinbyZone($where_1, '', '', 'result', '', '', '','',$column_1);
							if(!empty($result_1)){
								$count_1 += count($result_1);
							}
			
							// Process Order
							$where_2 = array(
								'A._processing >='   => $month_start,
								'A._processing <='   => $month_end,
								'A.published'        => '1',
								'B.city_id'       => $d_city_val[$i],
							);
			
							$column_2 = 'A.id';
							$result_2 = $this->order_model->getOrderJoinbyZone($where_2, '', '', 'result', '', '', '','',$column_2);
							if(!empty($result_2)){
								$count_2 += count($result_2);
							}
							// Cancel Count
							$where_3 = array(
								'A._canceled >=' => $month_start,
								'A._canceled <=' => $month_end,
								'A.published'    => '1',
								'B.city_id'       => $d_city_val[$i],
							);
			
							$column_3 = 'A.id';
							$result_3 = $this->order_model->getOrderJoinbyZone($where_3, '', '', 'result', '', '', '','',$column_3);
							
							if(!empty($result_3)){
								$count_3 += count($result_3);
							}
							// Invoice Count
							$where_4 = array(
								'A._complete >=' => $month_start,
								'A._complete <=' => $month_end,
								'A.published'    => '1',
								'B.city_id'       => $d_city_val[$i],
							);
			
							$column_4 = 'A.id';
							$result_4 = $this->order_model->getOrderJoinbyZone($where_4, '', '', 'result', '', '', '','',$column_4);
						
							if(!empty($result_4)){
								$count_4 += count($result_4);
							}
			
					    }
					}
				}else if($designation_code=='TSI'){
					if(!empty($ctrl_zone)){
						$zone_id_finall = substr($ctrl_zone,1,-1);
						
		
						$d_zone = !empty($zone_id_finall)?$zone_id_finall:'';
				
						$d_zone_val = explode(',', $d_zone);
						$zt_count = count($d_zone_val);
						$count_1 = 0;
						$count_2  = 0;
						$count_3  = 0;
						$count_4  = 0;
						for( $i=0; $i < $zt_count; $i++){
							$where_1 = array(
								'A._ordered >='     => $month_start,
								'A._ordered <='     => $month_end,
								'A.published'       => '1',
								'B.id'       => $d_zone_val[$i],
							);
			
							$column_1 = 'A.id';
							$result_1 = $this->order_model->getOrderJoinbyZone($where_1, '', '', 'result', '', '', '','',$column_1);
							

							if(!empty($result_1)){
								$count_1 += count($result_1);
							}
			
							// Process Order
							$where_2 = array(
								'A._processing >='   => $month_start,
								'A._processing <='   => $month_end,
								'A.published'        => '1',
								'B.id'       => $d_zone_val[$i],
							);
			
							$column_2 = 'A.id';
							$result_2 = $this->order_model->getOrderJoinbyZone($where_2, '', '', 'result', '', '', '','',$column_2);
							if(!empty($result_2)){
								$count_2 += count($result_2);
							}
							// Cancel Count
							$where_3 = array(
								'A._canceled >=' => $month_start,
								'A._canceled <=' => $month_end,
								'A.published'    => '1',
								'B.id'       => $d_zone_val[$i],
							);
			
							$column_3 = 'A.id';
							$result_3 = $this->order_model->getOrderJoinbyZone($where_3, '', '', 'result', '', '', '','',$column_3);
							
							if(!empty($result_3)){
								$count_3 += count($result_3);
							}
							// Invoice Count
							$where_4 = array(
								'A._complete >=' => $month_start,
								'A._complete <=' => $month_end,
								'A.published'    => '1',
								'B.id'       => $d_zone_val[$i],
							);
			
							$column_4 = 'A.id';
							$result_4 = $this->order_model->getOrderJoinbyZone($where_4, '', '', 'result', '', '', '','',$column_4);
						
							if(!empty($result_4)){
								$count_4 += count($result_4);
							}
			
					    }
					}
				}
		

					$order_value = array(
						'success_order' => strval($count_1),
						'process_order' => strval($count_2),
						'cancel_order'  => strval($count_3),
						'invoice_order' => strval($count_4),
					);

				$response['status']  = 1;
                $response['message'] = "Success"; 
                $response['data']    = $order_value;
                echo json_encode($response);
                return; 
			}

			else if($method == '_attendaceReport')
			{
				// Today
				$today_value = date('Y-m-d');
				if($designation_code=='RSM'){
					if(!empty($ctrl_state)){
						$state_id_finall = substr($ctrl_state,1,-1);
						//print_r($state_id_finall);exit;
		
						$d_state = !empty($state_id_finall)?$state_id_finall:'';
				
						$d_state_val = explode(',', $d_state);
						$st_count = count($d_state_val);
						$inactive_emp = [];
						$count_emp = [];
						$mg_prasent = array();
						for( $i=0; $i < $st_count; $i++){
							
							$wer = array(
						
								'published'      => '1',
							);
							$myArray = array("'ASM','SO','TSI', 'BDE'");
							$where_in['designation_code'] = $myArray;
							$like['ctrl_state_id'] =','. $d_state_val[$i].',';

							$co1 ='employee_id';

							$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1,$where_in);
				          
							if(!empty($mg_val)){
									
								foreach ($mg_val as $key => $value) {
									array_push($count_emp,$value);
									$employee_id     = !empty($value->employee_id)?$value->employee_id:'';
									$wer_att1 = array(
										'emp_id' => $employee_id,
										'c_date' => date('Y-m-d'),
										'published'      => '1'
									);
	
									$col_att ='id';
									$att_val = $this->attendance_model->getAttendance($wer_att1, '', '', 'result', $like, '', '', '', $col_att);
									if(!empty($att_val)){
										
										foreach( $att_val as $value){
											array_push($mg_prasent,$value);
										}
									}
									$wer_act =array(
										'id' => $employee_id,
										'published'      => '1',
										'status'    => '0',
									);
									$col_act ='id';
									$emp_act = $this->employee_model->getEmployee($wer_act, '', '', 'result', '', '', '', '', $col_act);
									if(!empty($emp_act)){
										
										foreach( $emp_act as $value){
											array_push($inactive_emp,$value);
										}
									}
								
								}
							}
								// Employee Count
							// $where_1 = array(
							// 	'log_type'  => '2',
							// 	'published' => '1',
							// );

							// $tot_employee_val = $this->employee_model->getEmployee($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

							// $tot_employee_count = !empty($tot_employee_val[0]->autoid)?$tot_employee_val[0]->autoid:'0';

							// // Active Employee Count
							// $where_2 = array(
							// 	'log_type'  => '2',
							// 	'status'    => '1',
							// 	'published' => '1',
							// );

							// $act_employee_val = $this->employee_model->getEmployee($where_2,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

							// $acv_employee_count = !empty($act_employee_val[0]->autoid)?$act_employee_val[0]->autoid:'0';

							// // Present Employee Count
							// $where_3 = array(
							// 	'c_date'    => $today_value,
							// 	'status'    => '1',
							// 	'published' => '1',
							// );

							// $column    = 'id';
	        				// $groupby   = 'emp_id';
	        				// $att_data  = $this->attendance_model->getAttendance($where_3, '', '', 'result', '', '', '', '', $column, $groupby);

	        				// $pre_count = 0;
	        				// if(!empty($att_data))
	        				// {
	        				// 	$pre_count = count($att_data);
	        				// }
						}
					}
				}else 	if($designation_code=='ASM'){
					if(!empty($ctrl_state)){
						$state_id_finall = substr($ctrl_state,1,-1);
						//print_r($state_id_finall);exit;
		
						$d_state = !empty($state_id_finall)?$state_id_finall:'';
				
						$d_state_val = explode(',', $d_state);
						$st_count = count($d_state_val);
						$inactive_emp = [];
						$count_emp = [];
						$mg_prasent = array();
						for( $i=0; $i < $st_count; $i++){
							
							$wer = array(
						
								'published'      => '1',
							);
							$myArray = array("'SO','TSI', 'BDE'");
							$where_in['designation_code'] = $myArray;
							$like['ctrl_state_id'] =','. $d_state_val[$i].',';

							$co1 ='employee_id';

							$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1,$where_in);
				          
							if(!empty($mg_val)){
									
								foreach ($mg_val as $key => $value) {
									array_push($count_emp,$value);
									$employee_id     = !empty($value->employee_id)?$value->employee_id:'';
									$wer_att1 = array(
										'emp_id' => $employee_id,
										'c_date' => date('Y-m-d'),
										'published'      => '1'
									);
	
									$col_att ='id';
									$att_val = $this->attendance_model->getAttendance($wer_att1, '', '', 'result', $like, '', '', '', $col_att);
									if(!empty($att_val)){
										
										foreach( $att_val as $value){
											array_push($mg_prasent,$value);
										}
									}
									$wer_act =array(
										'id' => $employee_id,
										'published'      => '1',
										'status'    => '0',
									);
									$col_act ='id';
									$emp_act = $this->employee_model->getEmployee($wer_act, '', '', 'result', '', '', '', '', $col_act);
									if(!empty($emp_act)){
										
										foreach( $emp_act as $value){
											array_push($inactive_emp,$value);
										}
									}
								
								}
							}
								// Employee Count
							// $where_1 = array(
							// 	'log_type'  => '2',
							// 	'published' => '1',
							// );

							// $tot_employee_val = $this->employee_model->getEmployee($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

							// $tot_employee_count = !empty($tot_employee_val[0]->autoid)?$tot_employee_val[0]->autoid:'0';

							// // Active Employee Count
							// $where_2 = array(
							// 	'log_type'  => '2',
							// 	'status'    => '1',
							// 	'published' => '1',
							// );

							// $act_employee_val = $this->employee_model->getEmployee($where_2,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

							// $acv_employee_count = !empty($act_employee_val[0]->autoid)?$act_employee_val[0]->autoid:'0';

							// // Present Employee Count
							// $where_3 = array(
							// 	'c_date'    => $today_value,
							// 	'status'    => '1',
							// 	'published' => '1',
							// );

							// $column    = 'id';
	        				// $groupby   = 'emp_id';
	        				// $att_data  = $this->attendance_model->getAttendance($where_3, '', '', 'result', '', '', '', '', $column, $groupby);

	        				// $pre_count = 0;
	        				// if(!empty($att_data))
	        				// {
	        				// 	$pre_count = count($att_data);
	        				// }
						}
					}
				}else 	if($designation_code=='SO'){
					if(!empty($ctrl_city)){
						$city_id_finall = substr($ctrl_city,1,-1);
						//print_r($city_id_finall);exit;
		
						$d_city = !empty($city_id_finall)?$city_id_finall:'';
				
						$d_city_val = explode(',', $d_city);
						$st_count = count($d_city_val);
						$inactive_emp = [];
						$count_emp = [];
						$mg_prasent = array();
						for( $i=0; $i < $st_count; $i++){

						
					
					
							$wer = array(
						
								'published'      => '1',
							);
							$myArray = array("'TSI', 'BDE'");
							$where_in['designation_code'] = $myArray;
							$like['ctrl_city_id'] =','. $d_city_val[$i].',';

							$co1 ='employee_id';

							$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1,$where_in);
				          
							if(!empty($mg_val)){
									
								foreach ($mg_val as $key => $value) {
									array_push($count_emp,$value);
									$employee_id     = !empty($value->employee_id)?$value->employee_id:'';
									$wer_att1 = array(
										'emp_id' => $employee_id,
										'c_date' => date('Y-m-d'),
										'published'      => '1'
									);
	
									$col_att ='id';
									$att_val = $this->attendance_model->getAttendance($wer_att1, '', '', 'result', $like, '', '', '', $col_att);
									if(!empty($att_val)){
										
										foreach( $att_val as $value){
											array_push($mg_prasent,$value);
										}
									}
									$wer_act =array(
										'id' => $employee_id,
										'published'      => '1',
										'status'    => '0',
									);
									$col_act ='id';
									$emp_act = $this->employee_model->getEmployee($wer_act, '', '', 'result', '', '', '', '', $col_act);
									if(!empty($emp_act)){
										
										foreach( $emp_act as $value){
											array_push($inactive_emp,$value);
										}
									}
								
								}
							}
								// Employee Count
							// $where_1 = array(
							// 	'log_type'  => '2',
							// 	'published' => '1',
							// );

							// $tot_employee_val = $this->employee_model->getEmployee($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

							// $tot_employee_count = !empty($tot_employee_val[0]->autoid)?$tot_employee_val[0]->autoid:'0';

							// // Active Employee Count
							// $where_2 = array(
							// 	'log_type'  => '2',
							// 	'status'    => '1',
							// 	'published' => '1',
							// );

							// $act_employee_val = $this->employee_model->getEmployee($where_2,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

							// $acv_employee_count = !empty($act_employee_val[0]->autoid)?$act_employee_val[0]->autoid:'0';

							// // Present Employee Count
							// $where_3 = array(
							// 	'c_date'    => $today_value,
							// 	'status'    => '1',
							// 	'published' => '1',
							// );

							// $column    = 'id';
	        				// $groupby   = 'emp_id';
	        				// $att_data  = $this->attendance_model->getAttendance($where_3, '', '', 'result', '', '', '', '', $column, $groupby);

	        				// $pre_count = 0;
	        				// if(!empty($att_data))
	        				// {
	        				// 	$pre_count = count($att_data);
	        				// }
						}
					}
				}else 	if($designation_code=='TSI'){
					
					if(!empty($ctrl_zone)){
						$zone_id_finall = substr($ctrl_zone,1,-1);
						//print_r($zone_id_finall);exit;
		
						$d_zone = !empty($zone_id_finall)?$zone_id_finall:'';
				
						$d_zone_val = explode(',', $d_zone);
						$st_count = count($d_zone_val);
						$inactive_emp = [];
						$count_emp = [];
						$mg_prasent = array();
						for( $i=0; $i < $st_count; $i++){
							
							$wer = array(
						
								'published'      => '1',
								'designation_code'  => 'BDE'
							);
							$like['ctrl_zone_id'] =','. $d_zone_val[$i].',';

							$co1 ='employee_id';

							$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);
				         
							if(!empty($mg_val)){
									
								foreach ($mg_val as $key => $value) {
									array_push($count_emp,$value);
									$employee_id     = !empty($value->employee_id)?$value->employee_id:'';
									$wer_att1 = array(
										'emp_id' => $employee_id,
										'c_date' => date('Y-m-d'),
										'published'      => '1'
									);
	
									$col_att ='id';
									$att_val = $this->attendance_model->getAttendance($wer_att1, '', '', 'result', $like, '', '', '', $col_att);
									if(!empty($att_val)){
										
										foreach( $att_val as $value){
											array_push($mg_prasent,$value);
										}
									}
									$wer_act =array(
										'id' => $employee_id,
										'published'      => '1',
										'status'    => '0',
									);
									$col_act ='id';
									$emp_act = $this->employee_model->getEmployee($wer_act, '', '', 'result', '', '', '', '', $col_act);
									if(!empty($emp_act)){
										
										foreach( $emp_act as $value){
											array_push($inactive_emp,$value);
										}
									}
								
								}
							}
								// Employee Count
							// $where_1 = array(
							// 	'log_type'  => '2',
							// 	'published' => '1',
							// );

							// $tot_employee_val = $this->employee_model->getEmployee($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

							// $tot_employee_count = !empty($tot_employee_val[0]->autoid)?$tot_employee_val[0]->autoid:'0';

							// // Active Employee Count
							// $where_2 = array(
							// 	'log_type'  => '2',
							// 	'status'    => '1',
							// 	'published' => '1',
							// );

							// $act_employee_val = $this->employee_model->getEmployee($where_2,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

							// $acv_employee_count = !empty($act_employee_val[0]->autoid)?$act_employee_val[0]->autoid:'0';

							// // Present Employee Count
							// $where_3 = array(
							// 	'c_date'    => $today_value,
							// 	'status'    => '1',
							// 	'published' => '1',
							// );

							// $column    = 'id';
	        				// $groupby   = 'emp_id';
	        				// $att_data  = $this->attendance_model->getAttendance($where_3, '', '', 'result', '', '', '', '', $column, $groupby);

	        				// $pre_count = 0;
	        				// if(!empty($att_data))
	        				// {
	        				// 	$pre_count = count($att_data);
	        				// }
						}
					}
				}

				$tot_employee_count = count($count_emp);
				$inact=count($inactive_emp);
				$acv_employee_count = $tot_employee_count-$inact;
				$pre_count=count($mg_prasent);
				$abs_count = $acv_employee_count - $pre_count;

				// // Employee Count
				// $where_1 = array(
				// 	'log_type'  => '2',
				// 	'published' => '1',
				// );

				// $tot_employee_val = $this->employee_model->getEmployee($where_1,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				// $tot_employee_count = !empty($tot_employee_val[0]->autoid)?$tot_employee_val[0]->autoid:'0';

				// // Active Employee Count
				// $where_2 = array(
				// 	'log_type'  => '2',
				// 	'status'    => '1',
				// 	'published' => '1',
				// );

				// $act_employee_val = $this->employee_model->getEmployee($where_2,'','',"result",array(),array(),array(),TRUE,'COUNT(id) AS autoid');

				// $acv_employee_count = !empty($act_employee_val[0]->autoid)?$act_employee_val[0]->autoid:'0';

				// // Present Employee Count
				// $where_3 = array(
				// 	'c_date'    => $today_value,
				// 	'status'    => '1',
				// 	'published' => '1',
				// );

				// $column    = 'id';
	        	// $groupby   = 'emp_id';
	        	// $att_data  = $this->attendance_model->getAttendance($where_3, '', '', 'result', '', '', '', '', $column, $groupby);

	        	// $pre_count = 0;
	        	// if(!empty($att_data))
	        	// {
	        	// 	$pre_count = count($att_data);
	        	// }

	        	// Absent Employee Count
	        	// $abs_count = $acv_employee_count - $pre_count;

	        	$attendance_report = array(
	        		'total_employee'   => strval($tot_employee_count),
	        		'active_employee'  => strval($acv_employee_count),
	        		'present_employee' => strval($pre_count),
	        		'absent_employee'  => strval($abs_count),
	        	);

	        	$response['status']  = 1;
		        $response['message'] = "Success"; 
		        $response['data']    = $attendance_report;
		        echo json_encode($response);
		        return;
			}

			else if($method == '_distributorInvoiceReport')
			{
				// Today
				$today_value = date('Y-m-d');

				// Week wise report
				$week_start  = date('Y-m-d', strtotime("sunday -1 week"));
				$week_end    = date('Y-m-d', strtotime($week_start. "+7 day"));

				// Month wise report
				$month_count = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime('02')), date('Y'));
				$month_start = date('Y-m-d', strtotime('01-'.date('m').'-'.date('Y')));
				$month_end   = date('Y-m-d', strtotime($month_count.'-'.date('m').'-'.date('Y')));

				$whr_1 = array('distributor_type' => '1', 'status' => '1', 'published' => '1');
    			$col_1 = 'id, company_name';
				$res_1 = $this->distributors_model->getDistributors($whr_1, '', '', 'result', '', '', '', '', $col_1);

				if($res_1)
				{
					$ins_list = [];
					foreach ($res_1 as $key => $val_1) {
						$dis_id   = !empty($val_1->id)?$val_1->id:'';
            			$dis_name = !empty($val_1->company_name)?$val_1->company_name:'';

            			// Today Order
            			$start_today = date('Y-m-d H:i:s', strtotime($today_value. '00:00:00'));
			    		$end_today   = date('Y-m-d H:i:s', strtotime($today_value. '23:59:59'));

            			$whr_2 = array(
            				'distributor_id' => $dis_id,
							'createdate >='  => $start_today,
							'createdate <='  => $end_today,
							'cancel_status'  => '1',
							'status'         => '1',
							'published'      => '1',
						);

						$col_2 = 'COUNT(`id`) AS today_order';
						$res_2 = $this->invoice_model->getInvoice($whr_2, '', '', 'row', '', '', '', '', $col_2);

						$today_order = 0;
						if($res_2)
						{
							$today_order = zero_check($res_2->today_order);
						}

            			// This Week
            			$start_week = date('Y-m-d H:i:s', strtotime($week_start. '00:00:00'));
			    		$end_week   = date('Y-m-d H:i:s', strtotime($week_end. '23:59:59'));

            			$whr_3 = array(
            				'distributor_id' => $dis_id,
							'createdate >='  => $start_week,
							'createdate <='  => $end_week,
							'cancel_status'  => '1',
							'status'         => '1',
							'published'      => '1',
						);

						$col_3 = 'COUNT(`id`) AS week_order';
						$res_3 = $this->invoice_model->getInvoice($whr_3, '', '', 'row', '', '', '', '', $col_3);

						$week_order = 0;
						if($res_3)
						{
							$week_order = zero_check($res_3->week_order);
						}

            			// This Month
            			$start_month = date('Y-m-d H:i:s', strtotime($month_start. '00:00:00'));
			    		$end_month   = date('Y-m-d H:i:s', strtotime($month_end. '23:59:59'));

            			$whr_4 = array(
            				'distributor_id' => $dis_id,
							'createdate >='  => $start_month,
							'createdate <='  => $end_month,
							'cancel_status'  => '1',
							'status'         => '1',
							'published'      => '1',
						);

						$col_4 = 'COUNT(`id`) AS month_order';
						$res_4 = $this->invoice_model->getInvoice($whr_4, '', '', 'row', '', '', '', '', $col_4);

						$month_order = 0;
						if($res_4)
						{
							$month_order = zero_check($res_4->month_order);
						}

            			$ins_list[] = array(
            				'distributor_id'   => $dis_id,
            				'distributor_name' => $dis_name,
            				'today_order'      => $today_order,
            				'week_order'       => $week_order,
            				'month_order'      => $month_order,
            			);
					}

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $ins_list;
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

			else
			{
				$response['status']  = 0;
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// Order Overall Report
		// ***************************************************
		public function order_report($param1="",$param2="",$param3="")
		{
			$start_date = $this->input->post('start_date');
			$end_date   = $this->input->post('end_date');
			$state_id   = $this->input->post('state_id');
			$city_id    = $this->input->post('city_id');
			$zone_id    = $this->input->post('zone_id');
			$order_by   = $this->input->post('order_by');
		    $view_by    = $this->input->post('view_by');
			$method     = $this->input->post('method');

			if($method == '_overallOrderReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'tbl_order_details.createdate >=' => $start_value,
							'tbl_order_details.createdate <=' => $end_value,
							'tbl_order_details.published'     => '1',
						);

						$column_1 = 'tbl_order.store_name, tbl_order.emp_name, tbl_order_details.order_no, tbl_order_details.product_id, tbl_order_details.type_id, tbl_order_details.hsn_code, tbl_order_details.gst_val, tbl_order_details.price, tbl_order_details.order_qty, tbl_order_details._ordered';

						$order_data = $this->order_model->getProductionOrderDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

	        			if(!empty($order_data))
	        			{
	        				$order_details = [];
	        				foreach ($order_data as $key => $val) {
	        					$store_name = !empty($val->store_name)?$val->store_name:'';
	        					$emp_name   = !empty($val->emp_name)?$val->emp_name:'Admin';
								$order_no   = !empty($val->order_no)?$val->order_no:'';
								$product_id = !empty($val->product_id)?$val->product_id:'';
								$type_id    = !empty($val->type_id)?$val->type_id:'';
								$hsn_code   = !empty($val->hsn_code)?$val->hsn_code:'';
								$gst_val    = !empty($val->gst_val)?$val->gst_val:'';
								$price      = !empty($val->price)?$val->price:'';
								$order_qty  = !empty($val->order_qty)?$val->order_qty:'';
								$_ordered   = !empty($val->_ordered)?$val->_ordered:'';

								// Product Details
								$whr_2 = array(
									'id'        => $product_id,
									'published' => '1',
								);

								$col_2 = 'name';

								$pro_data = $this->commom_model->getProduct($whr_2, '', '', 'result', '', '', '', '', $col_2);

								$pro_name = !empty($pro_data[0]->name)?$pro_data[0]->name:'';

								// Product Details
								$whr_3 = array(
									'id'        => $type_id,
									'published' => '1',
								);

								$col_3 = 'description';

								$pro_data = $this->commom_model->getProductType($whr_3, '', '', 'result', '', '', '', '', $col_3);

								$pro_desc = !empty($pro_data[0]->description)?$pro_data[0]->description:'';

								$order_details[] = array(
									'store_name'   => $store_name,
									'emp_name'     => $emp_name,
									'order_no'     => $order_no,
									'product_name' => $pro_name,
									'type_name'    => $pro_desc,
									'hsn_code'     => $hsn_code,
									'gst_val'      => $gst_val,
									'price'        => $price,
									'order_qty'    => $order_qty,
									'_ordered'     => date('d-M-Y h:i:s', strtotime($_ordered)),
								);
	        				}

	        				$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $order_details;
					        echo json_encode($response);
					        return;
	        			}
	        			else
	        			{
	        				$response['status']  = 0;
					        $response['message'] = "Not Found"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
	        			}
			    	}
			    	else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Invalid Date"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			    }
			}

			else if($method == '_overallOutletReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date', 'state_id');
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
			        $response['message'] = "Please fill all required fields...."; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(count($errors)==0)
			    {
			    	if($start_date <= $end_date)
			    	{
			    		// Attendace Details
						$where_1 = array(
							'A.published' => '1',
							'A.status'    => '1',
						);

						if($zone_id != '')
						{
							$where_1['A.zone_id'] = $zone_id;
						}

						if($city_id != '')
						{
							$where_1['A.city_id'] = $city_id;
						}

						if($state_id != '')
						{
							$where_1['A.state_id'] = $state_id;
						}

						$column_1  = 'A.id, A.company_name, A.mobile, A.current_balance, B.state_name, C.city_name, D.name AS zone_name';

						$data_list = $this->outlets_model->getOutletsJoin($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($data_list)
						{
							$outlet_list = [];
							foreach ($data_list as $key => $val_1) {
									
								$str_id     = !empty($val_1->id)?$val_1->id:'';
							    $str_name   = !empty($val_1->company_name)?$val_1->company_name:'';
							    $mobile     = !empty($val_1->mobile)?$val_1->mobile:'';
							    $curr_bal   = !empty($val_1->current_balance)?$val_1->current_balance:'0';
							    $state_name = !empty($val_1->state_name)?$val_1->state_name:'';
							    $city_name  = !empty($val_1->city_name)?$val_1->city_name:'';
							    $zone_name  = !empty($val_1->zone_name)?$val_1->zone_name:'';

							    // Invoice Details
							    $start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
						    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

						    	$where_1 = array(
									'createdate >='  => $start_value,
									'createdate <='  => $end_value,
									'store_id'       => $str_id,
									'cancel_status'  => '1',
									'published'      => '1',
								);

						    	$column_1 = 'price, order_qty';

								$inv_details = $this->invoice_model->getInvoiceDetails($where_1, '', '', 'result', '', '', '', '', $column_1);
								
								$inv_val = 0;
								if($inv_details)
								{
									foreach ($inv_details as $key => $val_2) {
										$price_val = !empty($val_2->price)?$val_2->price:'0';
            							$order_qty = !empty($val_2->order_qty)?$val_2->order_qty:'0';
            							$inv_res   = $order_qty * $price_val;
            							$inv_val  += $inv_res;
									}
								}

							    $outlet_list[] = array(
							    	'str_id'      => $str_id,
								    'str_name'    => $str_name,
								    'mobile'      => $mobile,
								    'curr_bal'    => $curr_bal,
								    'invoice_val' => round($inv_val),
								    'state_name'  => $state_name,
								    'city_name'   => $city_name,
								    'zone_name'   => $zone_name,
							    );
							}

							$invoicesort = array();
						    foreach ($outlet_list as $key => $row)
						    {
						        $invoicesort[$key] = $row['invoice_val'];
						    }
						    
						    if($order_by == 1)
						    {
						    	array_multisort($invoicesort, SORT_DESC, $outlet_list);
						    }
						    else
						    {
						    	array_multisort($invoicesort, SORT_ASC, $outlet_list);
						    }

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $outlet_list;
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
			    	else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Invalid Date"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			    }
			}

			else if($method == '_getProductOrderReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

			    		$whr_1 = array('status' => '1', 'published' => '1');
			    		$col_1 = 'id, vendor_id, description, product_unit';
			    		$res_1 = $this->commom_model->getProductType($whr_1, '', '', 'result', '', '', '', '', $col_1);

			    		if($res_1)
			    		{
			    			$pdt_list = [];
			    			foreach ($res_1 as $key => $val_1) {
			    				$type_id     = !empty($val_1->id)?$val_1->id:'';
							    $vendor_id   = !empty($val_1->vendor_id)?$val_1->vendor_id:'';
							    $description = !empty($val_1->description)?$val_1->description:'';

							    $whr_2 = array('id' => $vendor_id);
							    $col_2 = 'company_name';
							    $res_2 = $this->vendors_model->getVendors($whr_2, '', '', 'result', '', '', '', '', $col_2);

							    $ven_name = !empty($res_2[0]->company_name)?$res_2[0]->company_name:'';

							    // 1 => Success
							    $whr_3 = array(
							    	'_ordered >=' => $start_value,
									'_ordered <=' => $end_value,
									'type_id'     => $type_id,
									'published'   => '1',
							    );

							    $success_val = $this->order_model->getOrderDetails($whr_3,'','',"result",array(),array(),array(),TRUE,'SUM(order_qty) AS order_qty');

							    $success_count = !empty($success_val[0]->order_qty)?$success_val[0]->order_qty:'0';

							    // 2 => Process
							    $whr_4 = array(
							    	'_processing >=' => $start_value,
									'_processing <=' => $end_value,
									'type_id'        => $type_id,
									'published'      => '1',
							    );

							    $process_val = $this->order_model->getOrderDetails($whr_4,'','',"result",array(),array(),array(),TRUE,'SUM(order_qty) AS order_qty');

							    $process_count = !empty($process_val[0]->order_qty)?$process_val[0]->order_qty:'0';

							    // 3 => Packing
							    $whr_5 = array(
							    	'_packing >=' => $start_value,
									'_packing <=' => $end_value,
									'type_id'     => $type_id,
									'published'   => '1',
							    );

							    $packing_val = $this->order_model->getOrderDetails($whr_5,'','',"result",array(),array(),array(),TRUE,'SUM(order_qty) AS order_qty');

							    $packing_count = !empty($packing_val[0]->order_qty)?$packing_val[0]->order_qty:'0';

							    // 4 => Shipping
							    $whr_6 = array(
							    	'_shiping >=' => $start_value,
									'_shiping <=' => $end_value,
									'type_id'     => $type_id,
									'published'   => '1',
							    );

							    $shipping_val = $this->order_model->getOrderDetails($whr_6,'','',"result",array(),array(),array(),TRUE,'SUM(order_qty) AS order_qty');

							    $shipping_count = !empty($shipping_val[0]->order_qty)?$shipping_val[0]->order_qty:'0';

							    // 5 => Invoice
							    $whr_7 = array(
							    	'_invoice >='     => $start_value,
									'_invoice <='     => $end_value,
									'type_id'         => $type_id,
									'invoice_process' => '1',
									'cancel_status'   => '1',
									'published'       => '1',
							    );

							    $invoice_val = $this->order_model->getOrderDetails($whr_7,'','',"result",array(),array(),array(),TRUE,'SUM(order_qty) AS order_qty');

							    $invoice_count = !empty($invoice_val[0]->order_qty)?$invoice_val[0]->order_qty:'0';

							    // 6 => Delivery
							    $whr_8 = array(
							    	'_delivery >='    => $start_value,
									'_delivery <='    => $end_value,
									'type_id'         => $type_id,
									'invoice_process' => '2',
									'published'       => '1',
							    );

							    $delivery_val = $this->order_model->getOrderDetails($whr_8,'','',"result",array(),array(),array(),TRUE,'SUM(order_qty) AS order_qty');

							    $delivery_count = !empty($delivery_val[0]->order_qty)?$delivery_val[0]->order_qty:'0';

							    // 9 => Cancel Invoice
							    $whr_9 = array(
							    	'_delete >='      => $start_value,
									'_delete <='      => $end_value,
									'type_id'         => $type_id,
									'cancel_status'   => '2',
									'published'       => '1',
							    );

							    $canInv_val = $this->order_model->getOrderDetails($whr_9,'','',"result",array(),array(),array(),TRUE,'SUM(order_qty) AS order_qty');

							    $canInv_count = !empty($canInv_val[0]->order_qty)?$canInv_val[0]->order_qty:'0';

							    // 8 => Cancel
							    $whr_10 = array(
							    	'_canceled >=' => $start_value,
									'_canceled <=' => $end_value,
									'type_id'      => $type_id,
									'published'    => '1',
							    );

							    $cancel_val = $this->order_model->getOrderDetails($whr_10,'','',"result",array(),array(),array(),TRUE,'SUM(order_qty) AS order_qty');

							    $cancel_count = !empty($cancel_val[0]->order_qty)?$cancel_val[0]->order_qty:'0';

							    $pdt_list[] = array(
							    	'type_id'        => $type_id,
								    'vendor_name'    => $ven_name,
								    'description'    => $description,
								    'success_count'  => $success_count,
								    'process_count'  => $process_count,
								    'packing_count'  => $packing_count,
								    'shipping_count' => $shipping_count,
								    'invoice_count'  => $invoice_count,
								    'delivery_count' => $delivery_count,
								    'canInv_count'   => $canInv_count,
								    'cancel_count'   => $cancel_count,
							    );
			    			} 

			    			$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $pdt_list;
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
			    	else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Invalid Date"; 
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

		// Product Stock Report
		// ***************************************************
		public function stock_report($param1="",$param2="",$param3="")
		{
			$method         = $this->input->post('method');
			$category_id    = $this->input->post('category_id');
			$distributor_id = $this->input->post('distributor_id');
			$vendor_id      = $this->input->post('vendor_id');

			if($method == '_overallProductStockReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('category_id');
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

			    	$sub_cat_id = $this->input->post('sub_cat_id');
					if($sub_cat_id){
						$where_1 = array(
							'sub_cat_id'   => $sub_cat_id, 
							'published'    => '1',
							'status'       => '1',
						);	
					}else{
						$where_1 = array(
							'category_id'  => $category_id,
							'published'    => '1',
							'status'       => '1',
						);	
					}
			    	

			    	$column_1 = 'product_id, product_type, description, product_unit, product_price, product_stock, view_stock, minimum_stock';

					$data_1   = $this->commom_model->getProductTypeImplode($where_1, '', '', 'result', '', '', '', '', $column_1);

					if(!empty($data_1))
					{
						$product_details = [];
        				foreach ($data_1 as $key => $val) {

        					$product_id    = !empty($val->product_id)?$val->product_id:'';
        					$product_type  = !empty($val->product_type)?$val->product_type:'';
				            $description   = !empty($val->description)?$val->description:'';
				            $product_unit  = !empty($val->product_unit)?$val->product_unit:'';
				            $product_price = !empty($val->product_price)?$val->product_price:'0';
				            $product_stock = !empty($val->product_stock)?$val->product_stock:'0';
				            $view_stock    = !empty($val->view_stock)?$val->view_stock:'0';
				            $minimum_stock = !empty($val->minimum_stock)?$val->minimum_stock:'0';

				            // Product Details
							$pdt_whr = array('id' => $product_id);
							$pdt_col = 'gst';
							$pdt_val = $this->commom_model->getProduct($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);

							$pdt_gst  = !empty($pdt_val[0]->gst)?$pdt_val[0]->gst:'0';

							// Stock Details							
							$pdt_stock = $view_stock / $product_type;

							// Product Stock
				    		if($product_unit == 1 || $product_unit == 11)
				    		{
				    			$stock_data   = $minimum_stock / 1000;
				    		}
				    		else if($product_unit == 2 || $product_unit == 4)
				    		{
				    			$stock_data   = $minimum_stock;
				    		}
				    		else
				    		{
				    			$stock_data   = $minimum_stock;
				    		}

				    		$product_details[] = array(
				    			'description'   => $description,
								'product_type'  => $product_type,
								'gst_value'     => $pdt_gst,
								'product_price' => $product_price,
								'stock_detail'  => round($product_stock),
								'minimum_stock' => strval($stock_data),
				    		);
        				}

        				$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $product_details;
				        echo json_encode($response);
				        return;
					}
					else
        			{
        				$response['status']  = 0;
				        $response['message'] = "Not Found"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
        			}
			    }
			}

			if($method == '_overallDistributorStockReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id', 'category_id');
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
			    	$sub_cat_id = $this->input->post('s_cat_id');
					$distributor_id = $this->input->post('distributor_id');
					if($sub_cat_id){
						$where_1 = array(
							'distributor_id' => $distributor_id,
							'sub_cat_id'   => $sub_cat_id, 
							'published'    => '1',
							'status'       => '1',
						);	
					}else if($sub_cat_id == "" || $category_id == ""){
						$where_1 = array(
							'distributor_id'=> $distributor_id,
							'published'    => '1',
							'status'       => '1',
						);	
					}else{
						$where_1 = array(
							'distributor_id'=> $distributor_id,
							'category_id'  => $category_id,
							'published'    => '1',
							'status'       => '1',
						);	
					}





			    	$column_1 = 'category_id, product_id, type_id, description, product_unit, stock, view_stock, minimum_stock';

					$data_1   = $this->assignproduct_model->getAssignProductDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

					if(!empty($data_1))
					{
						$product_details = [];
						foreach ($data_1 as $key => $val) {
							$category_id   = !empty($val->category_id)?$val->category_id:'';
							$product_id    = !empty($val->product_id)?$val->product_id:'';
							$type_id       = !empty($val->type_id)?$val->type_id:'';
				            $description   = !empty($val->description)?$val->description:'';
				            $product_unit  = !empty($val->product_unit)?$val->product_unit:'';
				            $stock         = !empty($val->stock)?$val->stock:'0';
				            $view_stock    = !empty($val->view_stock)?$val->view_stock:'0';
				            $minimum_stock = !empty($val->minimum_stock)?$val->minimum_stock:'0';

				    		// Product Details
							$pdt_whr = array('id' => $product_id);
							$pdt_col = 'gst';
							$pdt_val = $this->commom_model->getProduct($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);

							$pdt_gst  = !empty($pdt_val[0]->gst)?$pdt_val[0]->gst:'0';

							// Product Type Details
							$type_whr = array('id' => $type_id);
							$type_col = 'product_type, dis_price';
							$type_val = $this->commom_model->getProductType($type_whr, '', '', 'result', '', '', '', '', $type_col);

							$pdt_type  = !empty($type_val[0]->product_type)?$type_val[0]->product_type:'0';
							$dis_price = !empty($type_val[0]->dis_price)?$type_val[0]->dis_price:'0';

							// Price Details
					    	$where_3  = array(
					    		'distributor_id' => $distributor_id,
					    		'category_id'    => $category_id,
					    		'product_id'     => $product_id,
					    		'type_id'        => $type_id,
					    		'published'      => '1',
					    		'status'         => '1',
					    	);

					    	$option['order_by']   = 'id';
							$option['disp_order'] = 'DESC';

							$limit  = 1;
							$offset = 0;

							$column = 'product_price';

					    	$price_val = $this->pricemaster_model->getDistributorPrice($where_3, $limit, $offset, 'result', '', '', $option, '', $column);

					    	$pdt_price = isset($price_val[0]->product_price)?$price_val[0]->product_price:'0';

					    	if(!empty($pdt_price))
					    	{
					    		$distributor_price = $pdt_price;
					    	}
					    	else
					    	{
					    		$distributor_price = $dis_price;
					    	}

							// Stock Details							
							$pdt_stock = $view_stock / $pdt_type;

				    		$product_details[] = array(
					            'description'   => $description,
					            'gst_value'     => $pdt_gst,
					            'product_price' => $distributor_price,
					            'view_stock'    => round($pdt_stock),
					            'minimum_stock' => $minimum_stock,
				    		);
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $product_details;
				        echo json_encode($response);
				        return;
					}
			    }
			}

			if($method == '_overallVendorProductStockReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('category_id', 'vendor_id');
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
			    	$where_1 = array(
						'vendor_id' => $vendor_id,
						'published' => '1',
						'status'    => '1',
					);	

					if(!empty($category_id))
					{
						$where_1['category_id'] = $category_id;
					}

			    	$column_1 = 'category_id, product_type, description, product_id, product_unit, type_stock, type_stock, stock_detail, ven_price';

					$data_1   = $this->commom_model->getProductTypeImplode($where_1, '', '', 'result', '', '', '', '', $column_1);

					if(!empty($data_1))
					{
						$data = [];
						foreach ($data_1 as $key => $val)
						{
							$category_id  = !empty($val->category_id)?$val->category_id:'';
							$product_type = !empty($val->product_type)?$val->product_type:'';
							$description  = !empty($val->description)?$val->description:'';
							$product_id   = !empty($val->product_id)?$val->product_id:'';
							$product_unit = !empty($val->product_unit)?$val->product_unit:'0';
							$type_stock   = !empty($val->type_stock)?$val->type_stock:'0';
							$stock_detail = !empty($val->stock_detail)?$val->stock_detail:'0';
							$ven_price    = !empty($val->ven_price)?$val->ven_price:'0';

							// Product Details
							$pdt_whr = array('id' => $product_id);
							$pdt_col = 'gst';
							$pdt_val = $this->commom_model->getProduct($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);

							$pdt_gst  = !empty($pdt_val[0]->gst)?$pdt_val[0]->gst:'0';

							// Stock Details							
							$pdt_stock = $stock_detail / $product_type;

							$data[] = array(
								'description'  => $description,
								'product_type' => $product_type,
								'gst_value'    => $pdt_gst,
								'ven_price'    => $ven_price,
								'stock_detail' => strval($pdt_stock),
							);
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $data;
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
			if($method == '_StockOutletReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('outlet_id', 'month_id', 'year_id');
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
							$state_id=$this->input->post('state_id');
					    	$city_id=$this->input->post('city_id');
					    	$zone_id=$this->input->post('zone_id');
					    	$year_id=$this->input->post('year_id');
							$month_id=$this->input->post('month_id');
							$outlet_id=$this->input->post('outlet_id');
					    	$category_id=$this->input->post('category_id');
							$sub_cat_id=$this->input->post('sub_cat_id');
							$type_id=$this->input->post('type_id');
							$wr=array(
								'id' => $year_id,
								'published' => '1'
							);
							$cm ='year_value';
							$year = $this->commom_model->getYear($wr, '', '', 'result', '', '', '', '', $cm);
							$year_value = !empty($year[0]->year_value)?$year[0]->year_value:'0';
							$month_count = cal_days_in_month(CAL_GREGORIAN, $month_id, $year_value);
							
							$array = [];
							for ($i=1; $i <= $month_count; $i++) { 
		
								$date_val = date('d-m-Y', strtotime($i.'-'.$month_id.'-'.$year_value));

								$day_val  = date('l', strtotime($date_val));
								array_push($array,$date_val);
							}
							$arr_count=count($array);
							$last_date_data =$arr_count-1;

							$first_date = $array[0];
							
							$new_first_Date = date('Y-m-d', strtotime($first_date. ' -1 months'));
							
							
							$end_date   = $array[$last_date_data];
							
							$start_value = date('Y-m-d H:i:s', strtotime($first_date. '00:00:00'));
							$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));
							
					if(!empty($type_id)){
						$where_1 = array(
							'createdate >='=> $start_value,
							'createdate <='=> $end_value,
							'type_id'      => $type_id, 
							'outlet_id'    => $outlet_id,
							'published'    => '1',
							'status'       => '1',
						);
						$column_1 = 'opening_stk, closeing_stk, sales_val, pdt_unit, pur_val, entry_val, type_id, outlet_id, entry_date';

						$data_1   = $this->attendance_model->getOutletStock($where_1, '', '', 'result', '', '', '', '', $column_1);
						$product_details_new =[];
						if(!empty($data_1))
						{
							
							$product_details = [];
							foreach ($data_1 as $key => $val) {
	
								$opening_sk    = !empty($val->opening_stk)?$val->opening_stk:'0';
								$purchese  = !empty($val->pur_val)?$val->pur_val:'0';
								$emp_entry_sk   = !empty($val->entry_val)?$val->entry_val:'0';
								$sales  = !empty($val->sales_val)?$val->sales_val:'0';
								$closeing_sk = !empty($val->closeing_stk)?$val->closeing_stk:'0';
								$pdt_unit = !empty($val->pdt_unit)?$val->pdt_unit:'0';
								$outlet_id    = !empty($val->outlet_id)?$val->outlet_id:'0';
								$type_id = !empty($val->type_id)?$val->type_id:'0';
								$entry_date = !empty($val->entry_date)?$val->entry_date:'0';
	
								// Product Details
								$pdt_whr = array('id' => $type_id);
								$pdt_col = 'description,product_unit';
								$pdt_val = $this->commom_model->getProductType($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);
	
								$description  = !empty($pdt_val[0]->description)?$pdt_val[0]->description:'0';
								$product_unit  = !empty($pdt_val[0]->product_unit)?$pdt_val[0]->product_unit:'0';
								//outlet name
								$outlet_we = array('id' => $outlet_id);
								$outlet_col = 'company_name';
								$outlet_val = $this->outlets_model->getOutlets($outlet_we, '', '', 'result', '', '', '', '', $outlet_col);
	
								$company_name  = !empty($outlet_val[0]->company_name)?$outlet_val[0]->company_name:'0';
								
								$unit_we = array('id' => $product_unit);
								$unit_col = 'name';
								$unit_val = $this->commom_model->getUnit($unit_we, '', '', 'result', '', '', '', '', $unit_col);
	
								$unit_name  = !empty($unit_val[0]->name)?$unit_val[0]->name:'0';
		
	
								$product_details[] = array(
									'description'   => $description,
									'opening_sk'    => $opening_sk,
									'purchese'      => $purchese,
									'emp_entry_sk'  => $emp_entry_sk,
									'sales'         => $sales,
									'closeing_sk'   => $closeing_sk,
									'pdt_unit'      => $unit_name,
									'entry_date'    => $entry_date,
									'company_name'  => $company_name,
								);
								
							}
							array_push($product_details_new,$product_details);
							$response['status']  = 1;
							$response['message'] = "Success"; 
							$response['data']    = $product_details_new;
							echo json_encode($response);
	
						
						}else
						{
							$time=strtotime($new_first_Date);
								$month=date("m",$time);
								$yearr=date("Y",$time);
								$pre_month_count = cal_days_in_month(CAL_GREGORIAN, $month, $yearr);
							
								$array = [];
								for ($i=1; $i <= $pre_month_count; $i++) { 
			
									$pre_date_val = date('d-m-Y', strtotime($i.'-'.$month.'-'.$yearr));
	
									$day_val  = date('l', strtotime($pre_date_val));
									array_push($array,$pre_date_val);
								}
								$arr_count=count($array);
								$pre_last_date_data =$arr_count-1;
								
								$pre_first_date = $array[0];
								$pre_end_date   = $array[$pre_last_date_data];
							
								$pre_start_value = date('Y-m-d H:i:s', strtotime($pre_first_date. '00:00:00'));
								$pre_end_value   = date('Y-m-d H:i:s', strtotime($pre_end_date. '23:59:59'));
							$where_1 = array(
								'createdate >='=> $pre_start_value,
								'createdate <='=> $pre_end_value,
								'type_id'      => $type_id, 
								'outlet_id'    => $outlet_id,
								'published'    => '1',
								'status'       => '1',
							);	
							$limit=1;
							$option['order_by']   = 'id';
							$option['disp_order'] = 'DESC';
	
							$column_1 = 'opening_stk, closeing_stk, sales_val, pdt_unit, pur_val, entry_val, type_id, outlet_id, entry_date';
	
							$data_2   = $this->attendance_model->getOutletStock($where_1, $limit, '', 'result', '', '', $option, '', $column_1);
							
							$product_details = [];
							if(!empty($data_2)){
								foreach ($data_2 as $key => $val) {
	
									$opening_sk    = !empty($val->opening_stk)?$val->opening_stk:'';
									$purchese  = !empty($val->pur_val)?$val->pur_val:'';
									$emp_entry_sk   = !empty($val->entry_val)?$val->entry_val:'';
									$sales  = !empty($val->sales_val)?$val->sales_val:'';
									$closeing_sk = !empty($val->closeing_stk)?$val->closeing_stk:'0';
									$pdt_unit = !empty($val->pdt_unit)?$val->pdt_unit:'0';
									$outlet_id    = !empty($val->outlet_id)?$val->outlet_id:'0';
									$type_id = !empty($val->type_id)?$val->type_id:'0';
									$entry_date = !empty($val->entry_date)?$val->entry_date:'0';
		
									// Product Details
									$pdt_whr = array('id' => $type_id);
									$pdt_col = 'description,product_unit';
									$pdt_val = $this->commom_model->getProductType($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);
		
									$description  = !empty($pdt_val[0]->description)?$pdt_val[0]->description:'0';
									$product_unit  = !empty($pdt_val[0]->product_unit)?$pdt_val[0]->product_unit:'0';
									//outlet name
									$outlet_we = array('id' => $outlet_id);
									$outlet_col = 'company_name';
									$outlet_val = $this->outlets_model->getOutlets($outlet_we, '', '', 'result', '', '', '', '', $outlet_col);
		
									$company_name  = !empty($outlet_val[0]->company_name)?$outlet_val[0]->company_name:'0';
									$unit_we = array('id' => $product_unit);
	
									$unit_col = 'name';
									$unit_val = $this->commom_model->getUnit($unit_we, '', '', 'result', '', '', '', '', $unit_col);
		
									$unit_name  = !empty($unit_val[0]->name)?$unit_val[0]->name:'0';
			
		
									$product_details[] = array(
										'description'   => $description,
										'opening_sk'    => $opening_sk,
										'purchese'      => 0,
										'emp_entry_sk'  => 0,
										'sales'         => 0,
										'closeing_sk'   => $closeing_sk,
										'pdt_unit'      => $unit_name,
										'entry_date'    => '---',
										'company_name'  => $company_name,
									);
								}
							}else{
								
		
									// Product Details
									$pdt_whr = array('id' => $type_id);
									$pdt_col = 'description,product_unit';
									$pdt_val = $this->commom_model->getProductType($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);
		
									$description  = !empty($pdt_val[0]->description)?$pdt_val[0]->description:'0';
									$product_unit  = !empty($pdt_val[0]->product_unit)?$pdt_val[0]->product_unit:'0';
		
									//outlet name
									$outlet_we = array('id' => $outlet_id);
									$outlet_col = 'company_name';
									$outlet_val = $this->outlets_model->getOutlets($outlet_we, '', '', 'result', '', '', '', '', $outlet_col);
		
									$company_name  = !empty($outlet_val[0]->company_name)?$outlet_val[0]->company_name:'0';
		
									$unit_we = array('id' => $product_unit);
									$unit_col = 'name';
									$unit_val = $this->commom_model->getUnit($unit_we, '', '', 'result', '', '', '', '', $unit_col);
		
									$unit_name  = !empty($unit_val[0]->name)?$unit_val[0]->name:'0';
		
			
		
									$product_details[] = array(
										'description'   => $description,
										'opening_sk'    => 0,
										'purchese'      => 0,
										'emp_entry_sk'  => 0,
										'sales'         => 0,
										'closeing_sk'   => 0,
										'pdt_unit'      => $unit_name,
										'entry_date'    => '---',
										'company_name'  => $company_name,
									);
								
							}
							
							array_push($product_details_new,$product_details);
							$response['status']  = 1;
							$response['message'] = "Success"; 
							$response['data']    = $product_details_new;
							echo json_encode($response);
						}	
					}else if(!empty($sub_cat_id)&&empty($type_id)){
						
						$pdt_whr = array('sub_cat_id' => $sub_cat_id);
								$pdt_col = 'id';
								$pdt_val = $this->commom_model->getProductType($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);
								
								foreach ($pdt_val as $key => $val) {

									$array_1[]    = !empty($val->id)?$val->id:'';
								}
								
					}else if(!empty($category_id)&&empty($sub_cat_id)){
						
						$pdt_whr = array('category_id' => $category_id);
								$pdt_col = 'id';
								$pdt_val = $this->commom_model->getProductType($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);
								
								foreach ($pdt_val as $key => $val) {

									$array_1[]    = !empty($val->id)?$val->id:'';
								}
					}else if(!empty($outlet_id)&&empty($category_id)){
						$pdt_whr = array(
							'published' => 1,
							'status'    => 1,
						);
								$pdt_col = 'id';
								$pdt_val = $this->commom_model->getProductType($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);
								
								foreach ($pdt_val as $key => $val) {

									$array_1[]    = !empty($val->id)?$val->id:'';
								}
					}
					if(empty($type_id)){
					// print_r($array_1);die;
					// $arr_count =count($array_1);
					$product_details_new=array();
					

					foreach ($array_1 as $value_) {
					// for($i=0; $i<$arr_count; $i++){
						$where_1 = array(
							'createdate >='=> $start_value,
							'createdate <='=> $end_value,
							'type_id'      => $value_, 
							'outlet_id'    => $outlet_id,
							'published'    => '1',
							'status'       => '1',
						);
						// array_push($na,$where_1);
						$column_1 = 'opening_stk, closeing_stk, sales_val, pdt_unit, pur_val, entry_val, type_id, outlet_id, entry_date';
	
						$data_1   = $this->attendance_model->getOutletStock($where_1, '', '', 'result', '', '', '', '', $column_1);
	
						if(!empty($data_1))
						{
							
							$product_details =array();
							foreach ($data_1 as $key => $val) {
	
								$opening_sk    = !empty($val->opening_stk)?$val->opening_stk:'0';
								$purchese  = !empty($val->pur_val)?$val->pur_val:'0';
								$emp_entry_sk   = !empty($val->entry_val)?$val->entry_val:'0';
								$sales  = !empty($val->sales_val)?$val->sales_val:'0';
								$closeing_sk = !empty($val->closeing_stk)?$val->closeing_stk:'0';
								$pdt_unit = !empty($val->pdt_unit)?$val->pdt_unit:'0';
								$outlet_id    = !empty($val->outlet_id)?$val->outlet_id:'0';
								$type_id = !empty($val->type_id)?$val->type_id:'0';
								$entry_date = !empty($val->entry_date)?$val->entry_date:'0';
	
								// Product Details
								$pdt_whr = array('id' => $type_id);
								$pdt_col = 'description,product_unit';
								$pdt_val = $this->commom_model->getProductType($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);
	
								$description  = !empty($pdt_val[0]->description)?$pdt_val[0]->description:'0';
								$product_unit  = !empty($pdt_val[0]->product_unit)?$pdt_val[0]->product_unit:'0';
								//outlet name
								$outlet_we = array('id' => $outlet_id);
								$outlet_col = 'company_name';
								$outlet_val = $this->outlets_model->getOutlets($outlet_we, '', '', 'result', '', '', '', '', $outlet_col);
	
								$company_name  = !empty($outlet_val[0]->company_name)?$outlet_val[0]->company_name:'0';
								
								$unit_we = array('id' => $product_unit);
								$unit_col = 'name';
								$unit_val = $this->commom_model->getUnit($unit_we, '', '', 'result', '', '', '', '', $unit_col);
	
								$unit_name  = !empty($unit_val[0]->name)?$unit_val[0]->name:'0';
		
	
								$product_details[] = array(
									'type_id'       => $value_,
									'description'   => $description,
									'opening_sk'    => $opening_sk,
									'purchese'      => $purchese,
									'emp_entry_sk'  => $emp_entry_sk,
									'sales'         => $sales,
									'closeing_sk'   => $closeing_sk,
									'pdt_unit'      => $unit_name,
									'entry_date'    => $entry_date,
									'company_name'  => $company_name,
								);
								
							}
							array_push($product_details_new,$product_details);
							
	
						
						}
						else{
							$time=strtotime($new_first_Date);
								$month=date("m",$time);
								$yearr=date("Y",$time);
								$pre_month_count = cal_days_in_month(CAL_GREGORIAN, $month, $yearr);
							
								$array = [];
								for ($j=1; $j <= $pre_month_count; $j++) { 
			
									$pre_date_val = date('d-m-Y', strtotime($j.'-'.$month.'-'.$yearr));
	
									$day_val  = date('l', strtotime($pre_date_val));
									array_push($array,$pre_date_val);
								}
								$arr_count=count($array);
								$pre_last_date_data =$arr_count-1;
								
								$pre_first_date = $array[0];
								$pre_end_date   = $array[$pre_last_date_data];
							
								$pre_start_value = date('Y-m-d H:i:s', strtotime($pre_first_date. '00:00:00'));
								$pre_end_value   = date('Y-m-d H:i:s', strtotime($pre_end_date. '23:59:59'));
							$where_1 = array(
								'createdate >='=> $pre_start_value,
								'createdate <='=> $pre_end_value,
								'type_id'      => $value_,
								'outlet_id'    => $outlet_id,
								'published'    => '1',
								'status'       => '1',
							);	
							$limit=1;
							$option['order_by']   = 'id';
							$option['disp_order'] = 'DESC';
	
							$column_1 = 'opening_stk, closeing_stk, sales_val, pdt_unit, pur_val, entry_val, type_id, outlet_id, entry_date';
	
							$data_2   = $this->attendance_model->getOutletStock($where_1, $limit, '', 'result', '', '', $option, '', $column_1);
							
							// $product_details = [];
							if(!empty($data_2)){
								$product_details =array();
								foreach ($data_2 as $key => $val) {
	
									$opening_sk    = !empty($val->opening_stk)?$val->opening_stk:'';
									$purchese  = !empty($val->pur_val)?$val->pur_val:'';
									$emp_entry_sk   = !empty($val->entry_val)?$val->entry_val:'';
									$sales  = !empty($val->sales_val)?$val->sales_val:'';
									$closeing_sk = !empty($val->closeing_stk)?$val->closeing_stk:'0';
									$pdt_unit = !empty($val->pdt_unit)?$val->pdt_unit:'0';
									$outlet_id    = !empty($val->outlet_id)?$val->outlet_id:'0';
									$type_id = !empty($val->type_id)?$val->type_id:'0';
									$entry_date = !empty($val->entry_date)?$val->entry_date:'0';
		
									// Product Details
									$pdt_whr = array('id' => $type_id);
									$pdt_col = 'description,product_unit';
									$pdt_val = $this->commom_model->getProductType($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);
		
									$description  = !empty($pdt_val[0]->description)?$pdt_val[0]->description:'0';
									$product_unit  = !empty($pdt_val[0]->product_unit)?$pdt_val[0]->product_unit:'0';
									//outlet name
									$outlet_we = array('id' => $outlet_id);
									$outlet_col = 'company_name';
									$outlet_val = $this->outlets_model->getOutlets($outlet_we, '', '', 'result', '', '', '', '', $outlet_col);
		
									$company_name  = !empty($outlet_val[0]->company_name)?$outlet_val[0]->company_name:'0';
									$unit_we = array('id' => $product_unit);
	
									$unit_col = 'name';
									$unit_val = $this->commom_model->getUnit($unit_we, '', '', 'result', '', '', '', '', $unit_col);
		
									$unit_name  = !empty($unit_val[0]->name)?$unit_val[0]->name:'0';
			
		
									$product_details[] = array(
										'description'   => $description,
										'opening_sk'    => $opening_sk,
										'purchese'      => 0,
										'emp_entry_sk'  => 0,
										'sales'         => 0,
										'type_id'       => $type_id,
										'closeing_sk'   => $closeing_sk,
										'pdt_unit'      => $unit_name,
										'entry_date'    => '---',
										'company_name'  => $company_name,
									);
									
								}
								array_push($product_details_new,$product_details);
							}else{
								
									$product_details =array();
									// Product Details
									$pdt_whr = array('id' => $value_);
									$pdt_col = 'description,product_unit';
									$pdt_val = $this->commom_model->getProductType($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);
		
									$description  = !empty($pdt_val[0]->description)?$pdt_val[0]->description:'0';
									$product_unit  = !empty($pdt_val[0]->product_unit)?$pdt_val[0]->product_unit:'0';
		
									//outlet name
									$outlet_we = array('id' => $outlet_id);
									$outlet_col = 'company_name';
									$outlet_val = $this->outlets_model->getOutlets($outlet_we, '', '', 'result', '', '', '', '', $outlet_col);
		
									$company_name  = !empty($outlet_val[0]->company_name)?$outlet_val[0]->company_name:'0';
		
									$unit_we = array('id' => $product_unit);
									$unit_col = 'name';
									$unit_val = $this->commom_model->getUnit($unit_we, '', '', 'result', '', '', '', '', $unit_col);
		
									$unit_name  = !empty($unit_val[0]->name)?$unit_val[0]->name:'0';
		
			
		
									$product_details[]= array(
										'description'   => $description,
										'opening_sk'    => 0,
										'purchese'      => 0,
										'emp_entry_sk'  => 0,
										'sales'         => 0,
										'closeing_sk'   => 0,
										'type_id'       => $value_,
										'pdt_unit'      => $unit_name,
										'entry_date'    => '---',
										'company_name'  => $company_name,
									);
									array_push($product_details_new,$product_details);
							}
							
							
							
						}
						
					
					}
				
					$response['status']  = 1;
					$response['message'] = "Success"; 
					$response['data']    = $product_details_new;
					echo json_encode($response);
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

		// Distributor Report
		// ***************************************************
		public function distributor_report($param1="",$param2="",$param3="")
		{
			$method          = $this->input->post('method');
			$start_date      = $this->input->post('start_date');
			$end_date        = $this->input->post('end_date');
			$assign_id       = $this->input->post('assign_id');
			$distributor_id  = $this->input->post('distributor_id');

			if($method == '_outletPaymentDetails')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'value_type'    => '2',
							'published'     => '1',
						);

						if(!empty($assign_id))
						{
							$where_1['assign_id'] = $assign_id;
						}

						$column_1  = 'id, distributor_id, bill_code, bill_id, bill_no, pre_bal, cur_bal, amount, discount, pay_type, description, value_type, createdate';

						$payment_data = $this->payment_model->getOutletPayment($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($payment_data)
						{
							$payment_list = [];
							foreach ($payment_data as $key => $val_1) {
								$auto_id        = !empty($val_1->id)?$val_1->id:'';
					            $distributor_id = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
					            $bill_code      = !empty($val_1->bill_code)?$val_1->bill_code:'INV';
					            $bill_id        = !empty($val_1->bill_id)?$val_1->bill_id:'';
					            $bill_no        = !empty($val_1->bill_no)?$val_1->bill_no:'';
					            $pre_bal        = !empty($val_1->pre_bal)?$val_1->pre_bal:'0';
					            $cur_bal        = !empty($val_1->cur_bal)?$val_1->cur_bal:'0';
					            $amount         = !empty($val_1->amount)?$val_1->amount:'0';
					            $discount       = !empty($val_1->discount)?$val_1->discount:'0';
					            $pay_type       = !empty($val_1->pay_type)?$val_1->pay_type:'';
					            $description    = !empty($val_1->description)?$val_1->description:'';
					            $value_type     = !empty($val_1->value_type)?$val_1->value_type:'';
					            $createdate     = !empty($val_1->createdate)?$val_1->createdate:'';

					            if($value_type == 1)
					            {
					            	$value_view = 'Order';
					            }
					            else if($value_type == 2)
					            {
					            	$value_view = 'Payment Collection';
					            }
					            else
					            {
					            	$value_view = 'Stock Return';
					            }

					            $payment_list[] = array(
					            	'auto_id'        => $auto_id,
						            'distributor_id' => $distributor_id,
						            'bill_code'      => $bill_code,
						            'bill_id'        => $bill_id,
						            'bill_no'        => $bill_no,
						            'pre_bal'        => $pre_bal,
						            'cur_bal'        => $cur_bal,
						            'amount'         => $amount,
						            'discount'       => $discount,
						            'pay_type'       => $pay_type,
						            'description'    => $description,
						            'value_type'     => $value_type,
						            'value_view'     => $value_view,
						            'createdate'     => date('d-m-Y H:i:s', strtotime($createdate)),
					            );

							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $payment_list;
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
			    	else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Invalid Date"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			    }
			}

			else if($method == '_overallSalesReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >='  => $start_value,
							'createdate <='  => $end_value,
							'distributor_id' => $distributor_id,
							'cancel_status'  => '1',
							'published'      => '1',
						);

				    	$column_1 = 'invoice_no, invoice_no, order_id, type_id, hsn_code, gst_val, price, order_qty, createdate';

						$inv_details = $this->invoice_model->getInvoiceDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($inv_details)
						{
							$sales_details = [];
							foreach ($inv_details as $key => $val) {

								$invoice_no = !empty($val->invoice_no)?$val->invoice_no:'';
								$order_id   = !empty($val->order_id)?$val->order_id:'';
								$type_value = !empty($val->type_id)?$val->type_id:'';
								$hsn_code   = !empty($val->hsn_code)?$val->hsn_code:'';
								$gst_val    = !empty($val->gst_val)?$val->gst_val:'';
								$price      = !empty($val->price)?number_format((float)$val->price, 2, '.', ''):'0';
								$order_qty  = !empty($val->order_qty)?$val->order_qty:'';
								$inv_date   = !empty($val->createdate)?$val->createdate:'';

								// Order Details
								$where_2 = array(
									'id' => $order_id
								);

								$column_2 = 'order_no, emp_name, store_id, discount, due_days, _ordered';

								$ord_details = $this->order_model->getOrder($where_2, '', '', 'result', '', '', '', '', $column_2);
								$ord_res     = $ord_details[0];

								$order_no = !empty($ord_res->order_no)?$ord_res->order_no:'';
								$emp_name = !empty($ord_res->emp_name)?$ord_res->emp_name:'Admin';
								$store_id = !empty($ord_res->store_id)?$ord_res->store_id:'';
								$discount = !empty($ord_res->discount)?$ord_res->discount:'0';
								$due_days = !empty($ord_res->due_days)?$ord_res->due_days:'0';
								$ordered  = !empty($ord_res->_ordered)?$ord_res->_ordered:'';

								// Outlet Details
								$where_3 = array(
									'id' => $store_id
								);

								$column_3 = 'company_name, mobile, gst_no, address, state_id';

								$str_details = $this->outlets_model->getOutlets($where_3, '', '', 'result', '', '', '', '', $column_3);
								$str_res     = $str_details[0];

								$str_name = !empty($str_res->company_name)?$str_res->company_name:'';
								$mobile   = !empty($str_res->mobile)?$str_res->mobile:'';
								$gst_no   = !empty($str_res->gst_no)?$str_res->gst_no:'';
								$address  = !empty($str_res->address)?$str_res->address:'';
								$state_id = !empty($str_res->state_id)?$str_res->state_id:'';

								// State Details
								$where_4 = array(
									'id' => $state_id
								);

								$column_4 = 'gst_code, state_name';

								$gst_details = $this->commom_model->getState($where_4, '', '', 'result', '', '', '', '', $column_4);
								$gst_res     = $gst_details[0];

								$state_name = !empty($gst_res->state_name)?$gst_res->state_name:'';
								$gst_code   = !empty($gst_res->gst_code)?$gst_res->gst_code:'';

								// Product Details
								$where_5 = array(
									'id' => $type_value
								);

								$column_5 = 'description, product_price';

								$pdt_details = $this->commom_model->getProductType($where_5, '', '', 'result', '', '', '', '', $column_5);

								$pdt_res     = $pdt_details[0];

								$description   = !empty($pdt_res->description)?$pdt_res->description:'';
								$product_price = !empty($pdt_res->product_price)?$pdt_res->product_price:'';

								$where_6 = array(
									'id' => $distributor_id
								);

								$column_6 = 'state_id';

								$dis_details  = $this->distributors_model->getDistributors($where_6, '', '', 'result', '', '', '', '', $column_6);
								$dis_res      = $dis_details[0];

								$dis_state_id = !empty($dis_res->state_id)?$dis_res->state_id:'';

								$sales_details[] = array(
									'dis_state_id'  => $dis_state_id,
									'invoice_no'    => $invoice_no,
									'order_no'      => $order_no,
									'emp_name'      => $emp_name,
									'str_name'      => $str_name,
									'mobile'        => $mobile,
									'gst_no'        => $gst_no,
									'address'       => $address,
									'state_id'      => $state_id,
									'state_name'    => $state_name,
									'gst_code'      => $gst_code,
									'due_days'      => $due_days,
									'order_date'    => date('d-m-Y', strtotime($ordered)),
									'invoice_date'  => date('d-m-Y', strtotime($inv_date)),
									'discount'      => $discount,
									'description'   => $description,
									'hsn_code'      => $hsn_code,
									'gst_val'       => $gst_val,
									'price'         => $price,
									'order_qty'     => $order_qty,
									'product_price' => $product_price,
								);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $sales_details;
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
			    	else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Invalid Date"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			    }
			}

			else if($method == '_overallInvoiceReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >='  => $start_value,
							'createdate <='  => $end_value,
							'distributor_id' => $distributor_id,
							'cancel_status'  => '1',
							'published'      => '1',
						);

						if(!empty($outlet_id))
						{
							$where_1['store_id'] = $outlet_id;
						}

						$column_1 = 'id, invoice_no, invoice_no, distributor_id, order_id, store_id, due_days, discount, random_value, createdate';	

						$inv_details = $this->invoice_model->getInvoice($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($inv_details)
						{
							$sales_details = [];	
							$inv_cnt       = !empty(count($inv_details))?count($inv_details):'0';
							$tot_amt       = 0;
							$tot_tax       = 0;
							$tot_taxable   = 0;

							foreach ($inv_details as $key => $val_1) {
								$inv_id     = !empty($val_1->id)?$val_1->id:'';
					            $inv_no     = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
					            $dis_id     = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
					            $order_id   = !empty($val_1->order_id)?$val_1->order_id:'';
					            $store_id   = !empty($val_1->store_id)?$val_1->store_id:'';
					            $due_days   = !empty($val_1->due_days)?$val_1->due_days:'0';
					            $discount   = !empty($val_1->discount)?$val_1->discount:'0';
					            $inv_random = !empty($val_1->random_value)?$val_1->random_value:'';
					            $createdate = !empty($val_1->createdate)?$val_1->createdate:'';

					            // Order Details
								$where_2 = array(
									'id' => $order_id
								);

								$column_2 = 'order_no, emp_name, store_id, discount, due_days, _ordered, random_value';

								$ord_details = $this->order_model->getOrder($where_2, '', '', 'result', '', '', '', '', $column_2);
								$ord_res     = $ord_details[0];

								$order_no   = !empty($ord_res->order_no)?$ord_res->order_no:'';
								$emp_name   = !empty($ord_res->emp_name)?$ord_res->emp_name:'Admin';
								$store_id   = !empty($ord_res->store_id)?$ord_res->store_id:'';
								$ordered    = !empty($ord_res->_ordered)?$ord_res->_ordered:'';
								$ord_random = !empty($ord_res->random_value)?$ord_res->random_value:'';

								// Outlet Details
								$where_3 = array(
									'id' => $store_id
								);

								$column_3 = 'company_name, mobile, gst_no, address, state_id';

								$str_details = $this->outlets_model->getOutlets($where_3, '', '', 'result', '', '', '', '', $column_3);
								$str_res     = $str_details[0];

								$str_name = !empty($str_res->company_name)?$str_res->company_name:'';
								$mobile   = !empty($str_res->mobile)?$str_res->mobile:'';
								$gst_no   = !empty($str_res->gst_no)?$str_res->gst_no:'';
								$address  = !empty($str_res->address)?$str_res->address:'';
								$state_id = !empty($str_res->state_id)?$str_res->state_id:'';

								// State Details
								$where_4 = array(
									'id' => $state_id
								);

								$column_4 = 'gst_code, state_name';

								$gst_details = $this->commom_model->getState($where_4, '', '', 'result', '', '', '', '', $column_4);
								$gst_res     = $gst_details[0];

								$state_name = !empty($gst_res->state_name)?$gst_res->state_name:'';
								$gst_code   = !empty($gst_res->gst_code)?$gst_res->gst_code:'';

								$where_5 = array(
									'id' => $dis_id
								);

								$column_5 = 'company_name, state_id';

								$dis_details  = $this->distributors_model->getDistributors($where_5, '', '', 'result', '', '', '', '', $column_5);
								$dis_res      = $dis_details[0];

								$dis_username = !empty($dis_res->company_name)?$dis_res->company_name:'';
								$dis_state_id = !empty($dis_res->state_id)?$dis_res->state_id:'';

								// Invoice Details
								$where_6  = array(
									'invoice_id' => $inv_id,
									'published'  => '1',
								);

								$column_6 = 'gst_val, price, order_qty';

								$invData_details = $this->invoice_model->getInvoiceDetails($where_6, '', '', 'result', '', '', '', '', $column_6);

								$inv_total   = 0;
					            $taxable_amt = 0;
					            $tax_amt     = 0;
					            $dis_amt     = 0;
								if($invData_details)
								{
									foreach ($invData_details as $key => $val_6) {

										$gst_val   = !empty($val_6->gst_val)?$val_6->gst_val:'0';
										$pdt_price = !empty($val_6->price)?number_format((float)$val_6->price, 2, '.', ''):'0';
										$order_qty = !empty($val_6->order_qty)?$val_6->order_qty:'0';

										$price_tot  = $pdt_price * $order_qty;
										$inv_total += $price_tot;

										// Discount Calculation
										$total_dis = $price_tot * $discount / 100;
                        				$dis_amt  += $total_dis;

										// GST Calculation
										$gst_data     = $pdt_price - ($pdt_price * (100 / (100 + $gst_val)));
										$price_val    = $pdt_price - $gst_data;
										$tax_amt     += $order_qty * $gst_data;
                        				$taxable_amt += $order_qty * $price_val;
									}
								}

								$invoice_tot = $inv_total - $dis_amt;

		                        $tot_taxable += $taxable_amt;
								$tot_tax     += $tax_amt;
		                        $tot_amt     += $invoice_tot;

								$sales_details[] = array(
									'dis_username'  => $dis_username,
									'dis_state_id'  => $dis_state_id,
									'invoice_no'    => $inv_no,
									'order_no'      => $order_no,
									'emp_name'      => $emp_name,
									'str_name'      => $str_name,
									'mobile'        => $mobile,
									'gst_no'        => $gst_no,
									'address'       => $address,
									'state_id'      => $state_id,
									'state_name'    => $state_name,
									'gst_code'      => $gst_code,
									'due_days'      => $due_days,
									'invoice_date'  => date('d-m-Y', strtotime($createdate)),
									'discount'      => $discount,
									'invoice_total' => strval(round($invoice_tot)),
									'inv_random'    => $inv_random,
									'ord_random'    => $ord_random,
									'taxable_amt'   => $taxable_amt,
									'tax_amt'       => $tax_amt,
									'invoice_tot'   => $invoice_tot,
								);
							}

							$total_value = array(
								'total_count'   => strval($inv_cnt),
								'total_taxable' => number_format((float)$tot_taxable, 2, '.', ''),
								'total_tax'     => number_format((float)$tot_tax, 2, '.', ''),
								'total_value'   => number_format((float)$tot_amt, 2, '.', ''),
							);

							$inv_data = array(
								'inv_total' => $total_value,
								'inv_list'  => $sales_details,
							);

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $inv_data;
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
			    	else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Invalid Date"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			    }
	    	}

			else if($method == '_outletReport')
			{
				$where_1 = array(
					'distributor_id' => $distributor_id,
					'published'      => '1',
				);

				$column_1 = 'outlet_id, pre_bal, cur_bal';

				$shop_details = $this->outlets_model->getDistributorOutlets($where_1, '', '', 'result', '', '', '', '', $column_1);		

				if($shop_details)
				{
					$outlet_detail = [];
					foreach ($shop_details as $key => $val_1) {
						$outlet_id = !empty($val_1->outlet_id)?$val_1->outlet_id:'';
						$pre_bal   = !empty($val_1->pre_bal)?$val_1->pre_bal:'0';
						$cur_bal   = !empty($val_1->cur_bal)?$val_1->cur_bal:'0';

						// Outlet Details
						$where_3 = array(
							'id' => $outlet_id
						);

						$column_3 = 'company_name, mobile, gst_no, address';

						$str_details = $this->outlets_model->getOutlets($where_3, '', '', 'result', '', '', '', '', $column_3);
						$str_res     = $str_details[0];

						$str_name = !empty($str_res->company_name)?$str_res->company_name:'';
						$mobile   = !empty($str_res->mobile)?$str_res->mobile:'';
						$gst_no   = !empty($str_res->gst_no)?$str_res->gst_no:'';
						$address  = !empty($str_res->address)?$str_res->address:'';

						$outlet_detail[] = array(
							'store_name' => $str_name,
							'mobile'     => $mobile,
							'address'    => $address,
							'pre_bal'    => $pre_bal,
							'cur_bal'    => $cur_bal,
						);
					}

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $outlet_detail;
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

			else if($method == '_distributorOverallOrderReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	// Distributor List
				    	$whr_1 = array('status' => '1', 'published' => '1');
				    	$col_1 = 'id, company_name, mobile, type_id';
				    	$res_1 = $this->distributors_model->getDistributors($whr_1, '', '', 'result', '', '', '', '', $col_1);

				    	if($res_1)
				    	{
				    		$order_res = [];
				    		foreach ($res_1 as $key => $val_1) {
				    			$distri_id    = !empty($val_1->id)?$val_1->id:'';
								$company_name = !empty($val_1->company_name)?$val_1->company_name:'';
								$mobile       = !empty($val_1->mobile)?$val_1->mobile:'';
								$type_id      = !empty($val_1->type_id)?$val_1->type_id:'';

								// process_order
								$whr_2 = array(
				    				'tbl_order_details.order_status'    => '2',
				    				'tbl_order.published'               => '1',
				    				'tbl_order_details.delete_status'   => '1',
				    				'tbl_order_details.type_id'         => $type_id,
				    				'tbl_order_details.vendor_type != ' => '1',
				    				'tbl_order_details.start_date'      => $start_value,
				    				'tbl_order_details.end_date'        => $end_value,
				    			);

				    			$col_2 = 'tbl_order_details.order_id, tbl_order_details.zone_id, tbl_order_details.type_id';
					    		$grp_2 = 'tbl_order_details.order_id';
								$res_2 = $this->order_model->getDistributorOrder($whr_2, '', '', 'result', '', '', '', '', $col_2, $grp_2);

								$pro_cnt = 0;
								if(!empty($res_2))
								{
									$order_value = '';
									foreach ($res_2 as $key => $val_2) {
										$order_val = !empty($val_2->order_id)?$val_2->order_id:'';
									    $zone_val  = !empty($val_2->zone_id)?$val_2->zone_id:'';
									    $type_val  = !empty($val_2->type_id)?$val_2->type_id:'';

									    $whr_3 = array(
									    	'distributor_id' => $distri_id,
									    	'type_id'        => $type_val,
									    	'zone_id'        => $zone_val,
									    	'status'         => '1', 
											'published'      => '1'
									    );

									    $col_3 = 'id';

										$assign_data = $this->assignproduct_model->getAssignProductDetails($whr_3, '', '', 'result', '', '', '', '', $col_3);

										if(!empty($assign_data))
										{
											$order_value .= $order_val.',';
										}
									}

									$order_list = substr_replace($order_value, '', -1);

									if(!empty($order_list))
									{
										$whr_4 = array(
						    				'tbl_order.id'                      => $order_list,
						    				'tbl_order_details.order_status'    => '2',
						    				'tbl_order_details.delete_status'   => '1',
						    				'tbl_order.published'               => '1',
						    				'tbl_order_details.vendor_type != ' => '1',
						    				'tbl_order_details.start_date'      => $start_value,
				    						'tbl_order_details.end_date'        => $end_value,
						    			);

						    			$col_4   = 'tbl_order.id';
						    			$grp_4   = 'tbl_order_details.order_id';
						    			$col_4   = 'tbl_order.id';
						    			$res_4   = $this->order_model->getDistributorOrder($whr_4, '', '', 'result', '', '', '', '', $col_4, $grp_4);
						    			$pro_cnt = !empty($res_4)?count($res_4):'0';
									}
								}

								// Packing Order
								$whr_5 = array(
				    				'tbl_order_details.order_status'    => '3',
				    				'tbl_order_details.delete_status'   => '1',
				    				'tbl_order.published'               => '1',
				    				'tbl_order_details.type_id'         => $type_id,
				    				'tbl_order_details.vendor_type != ' => '1',
				    				'tbl_order_details.start_date'      => $start_value,
				    				'tbl_order_details.end_date'        => $end_value,
				    			);

				    			$col_5 = 'tbl_order_details.order_id, tbl_order_details.zone_id, tbl_order_details.type_id';
					    		$grp_5 = 'tbl_order_details.order_id';
								$res_5 = $this->order_model->getDistributorOrder($whr_5, '', '', 'result', '', '', '', '', $col_5, $grp_5);

								$pak_cnt = 0;
								if(!empty($res_5))
								{
									$order_value = '';
									foreach ($res_5 as $key => $val_5) {
										$order_val = !empty($val_5->order_id)?$val_5->order_id:'';
									    $zone_val  = !empty($val_5->zone_id)?$val_5->zone_id:'';
									    $type_val  = !empty($val_5->type_id)?$val_5->type_id:'';

									    $whr_6 = array(
									    	'distributor_id' => $distri_id,
									    	'type_id'        => $type_val,
									    	'zone_id'        => $zone_val,
									    	'status'         => '1', 
											'published'      => '1'
									    );

									    $col_6 = 'id';

										$assign_data = $this->assignproduct_model->getAssignProductDetails($whr_6, '', '', 'result', '', '', '', '', $col_6);

										if(!empty($assign_data))
										{
											$order_value .= $order_val.',';
										}
									}

									$order_list = substr_replace($order_value, '', -1);

									if(!empty($order_list))
									{
										$whr_7 = array(
						    				'tbl_order.id'                      => $order_list,
						    				'tbl_order_details.order_status'    => '3',
						    				'tbl_order_details.delete_status'   => '1',
						    				'tbl_order.published'               => '1',
						    				'tbl_order_details.vendor_type != ' => '1',
						    				'tbl_order_details.start_date'      => $start_value,
				    						'tbl_order_details.end_date'        => $end_value,
						    			);

						    			$col_7   = 'tbl_order.id';
						    			$grp_7   = 'tbl_order_details.order_id';
						    			$col_7   = 'tbl_order.id';
						    			$res_7   = $this->order_model->getDistributorOrder($whr_7, '', '', 'result', '', '', '', '', $col_7, $grp_7);
						    			$pak_cnt = !empty($res_7)?count($res_7):'0';
									}
								}

								// Shipping Order
								$whr_8 = array(
				    				'tbl_order_details.order_status'    => '4',
				    				'tbl_order_details.delete_status'   => '1',
				    				'tbl_order.published'               => '1',
				    				'tbl_order_details.type_id'         => $type_id,
				    				'tbl_order_details.vendor_type != ' => '1',
				    				'tbl_order_details.start_date'      => $start_value,
				    				'tbl_order_details.end_date'        => $end_value,
				    			);

				    			$col_8 = 'tbl_order_details.order_id, tbl_order_details.zone_id, tbl_order_details.type_id';
					    		$grp_8 = 'tbl_order_details.order_id';
								$res_8 = $this->order_model->getDistributorOrder($whr_8, '', '', 'result', '', '', '', '', $col_8, $grp_8);

								$ship_cnt = 0;
								if(!empty($res_8))
								{
									$order_value = '';
									foreach ($res_8 as $key => $val_8) {
										$order_val = !empty($val_8->order_id)?$val_8->order_id:'';
									    $zone_val  = !empty($val_8->zone_id)?$val_8->zone_id:'';
									    $type_val  = !empty($val_8->type_id)?$val_8->type_id:'';

									    $whr_9 = array(
									    	'distributor_id' => $distri_id,
									    	'type_id'        => $type_val,
									    	'zone_id'        => $zone_val,
									    	'status'         => '1', 
											'published'      => '1'
									    );

									    $col_9 = 'id';

										$assign_data = $this->assignproduct_model->getAssignProductDetails($whr_9, '', '', 'result', '', '', '', '', $col_9);

										if(!empty($assign_data))
										{
											$order_value .= $order_val.',';
										}
									}

									$order_list = substr_replace($order_value, '', -1);

									if(!empty($order_list))
									{
										$whr_10 = array(
						    				'tbl_order.id'                      => $order_list,
						    				'tbl_order_details.order_status'    => '4',
						    				'tbl_order_details.delete_status'   => '1',
						    				'tbl_order.published'               => '1',
						    				'tbl_order_details.vendor_type != ' => '1',
						    				'tbl_order_details.start_date'      => $start_value,
				    						'tbl_order_details.end_date'        => $end_value,
						    			);

						    			$col_10   = 'tbl_order.id';
						    			$grp_10   = 'tbl_order_details.order_id';
						    			$col_10   = 'tbl_order.id';
						    			$res_10   = $this->order_model->getDistributorOrder($whr_10, '', '', 'result', '', '', '', '', $col_10, $grp_10);
						    			$ship_cnt = !empty($res_10)?count($res_10):'0';
									}
								}

								// Invoice Order
								$whr_11 = array(
				    				'tbl_order_details.order_status'    => '5',
				    				'tbl_order_details.delete_status'   => '1',
				    				'tbl_order.published'               => '1',
				    				'tbl_order_details.type_id'         => $type_id,
				    				'tbl_order_details.vendor_type != ' => '1',
				    				'tbl_order_details.start_date'      => $start_value,
				    				'tbl_order_details.end_date'        => $end_value,
				    			);

				    			$col_11 = 'tbl_order_details.order_id, tbl_order_details.zone_id, tbl_order_details.type_id';
					    		$grp_11 = 'tbl_order_details.order_id';
								$res_11 = $this->order_model->getDistributorOrder($whr_11, '', '', 'result', '', '', '', '', $col_11, $grp_11);

								$inv_cnt = 0;
								if(!empty($res_11))
								{
									$order_value = '';
									foreach ($res_11 as $key => $val_11) {
										$order_val = !empty($val_11->order_id)?$val_11->order_id:'';
									    $zone_val  = !empty($val_11->zone_id)?$val_11->zone_id:'';
									    $type_val  = !empty($val_11->type_id)?$val_11->type_id:'';

									    $whr_12 = array(
									    	'distributor_id' => $distri_id,
									    	'type_id'        => $type_val,
									    	'zone_id'        => $zone_val,
									    	'status'         => '1', 
											'published'      => '1'
									    );

									    $col_12 = 'id';

										$assign_data = $this->assignproduct_model->getAssignProductDetails($whr_12, '', '', 'result', '', '', '', '', $col_12);

										if(!empty($assign_data))
										{
											$order_value .= $order_val.',';
										}
									}

									$order_list = substr_replace($order_value, '', -1);

									if(!empty($order_list))
									{
										$whr_13 = array(
						    				'tbl_order.id'                      => $order_list,
						    				'tbl_order_details.order_status'    => '5',
						    				'tbl_order_details.delete_status'   => '1',
						    				'tbl_order.published'               => '1',
						    				'tbl_order_details.vendor_type != ' => '1',
						    				'tbl_order_details.start_date'      => $start_value,
				    						'tbl_order_details.end_date'        => $end_value,
						    			);

						    			$col_13   = 'tbl_order.id';
						    			$grp_13   = 'tbl_order_details.order_id';
						    			$col_13   = 'tbl_order.id';
						    			$res_13   = $this->order_model->getDistributorOrder($whr_13, '', '', 'result', '', '', '', '', $col_13, $grp_13);
						    			$inv_cnt = !empty($res_13)?count($res_13):'0';
									}
								}

								// Delivery Order
								$whr_14 = array(
				    				'tbl_order_details.order_status'    => '6',
				    				'tbl_order_details.delete_status'   => '1',
				    				'tbl_order.published'               => '1',
				    				'tbl_order_details.type_id'         => $type_id,
				    				'tbl_order_details.vendor_type != ' => '1',
				    				'tbl_order_details.start_date'      => $start_value,
				    				'tbl_order_details.end_date'        => $end_value,
				    			);

				    			$col_14 = 'tbl_order_details.order_id, tbl_order_details.zone_id, tbl_order_details.type_id';
					    		$grp_14 = 'tbl_order_details.order_id';
								$res_14 = $this->order_model->getDistributorOrder($whr_14, '', '', 'result', '', '', '', '', $col_14, $grp_14);

								$del_cnt = 0;
								if(!empty($res_14))
								{
									$order_value = '';
									foreach ($res_14 as $key => $val_14) {
										$order_val = !empty($val_14->order_id)?$val_14->order_id:'';
									    $zone_val  = !empty($val_14->zone_id)?$val_14->zone_id:'';
									    $type_val  = !empty($val_14->type_id)?$val_14->type_id:'';

									    $whr_15 = array(
									    	'distributor_id' => $distri_id,
									    	'type_id'        => $type_val,
									    	'zone_id'        => $zone_val,
									    	'status'         => '1', 
											'published'      => '1'
									    );

									    $col_15 = 'id';

										$assign_data = $this->assignproduct_model->getAssignProductDetails($whr_15, '', '', 'result', '', '', '', '', $col_15);

										if(!empty($assign_data))
										{
											$order_value .= $order_val.',';
										}
									}

									$order_list = substr_replace($order_value, '', -1);

									if(!empty($order_list))
									{
										$whr_16 = array(
						    				'tbl_order.id'                      => $order_list,
						    				'tbl_order_details.order_status'    => '6',
						    				'tbl_order_details.delete_status'   => '1',
						    				'tbl_order.published'               => '1',
						    				'tbl_order_details.vendor_type != ' => '1',
						    				'tbl_order_details.start_date'      => $start_value,
				    						'tbl_order_details.end_date'        => $end_value,
						    			);

						    			$col_16   = 'tbl_order.id';
						    			$grp_16   = 'tbl_order_details.order_id';
						    			$col_16   = 'tbl_order.id';
						    			$res_16   = $this->order_model->getDistributorOrder($whr_16, '', '', 'result', '', '', '', '', $col_16, $grp_16);
						    			$del_cnt = !empty($res_16)?count($res_16):'0';
									}
								}

								// Cancel Invoice Order
								$whr_17 = array(
				    				'tbl_order_details.order_status'    => '9',
				    				'tbl_order_details.delete_status'   => '1',
				    				'tbl_order.published'               => '1',
				    				'tbl_order_details.type_id'         => $type_id,
				    				'tbl_order_details.vendor_type != ' => '1',
				    				'tbl_order_details.start_date'      => $start_value,
				    				'tbl_order_details.end_date'        => $end_value,
				    			);

				    			$col_17 = 'tbl_order_details.order_id, tbl_order_details.zone_id, tbl_order_details.type_id';
					    		$grp_17 = 'tbl_order_details.order_id';
								$res_17 = $this->order_model->getDistributorOrder($whr_17, '', '', 'result', '', '', '', '', $col_17, $grp_17);

								$can_cnt = 0;
								if(!empty($res_17))
								{
									$order_value = '';
									foreach ($res_17 as $key => $val_17) {
										$order_val = !empty($val_17->order_id)?$val_17->order_id:'';
									    $zone_val  = !empty($val_17->zone_id)?$val_17->zone_id:'';
									    $type_val  = !empty($val_17->type_id)?$val_17->type_id:'';

									    $whr_18 = array(
									    	'distributor_id' => $distri_id,
									    	'type_id'        => $type_val,
									    	'zone_id'        => $zone_val,
									    	'status'         => '1', 
											'published'      => '1'
									    );

									    $col_18 = 'id';

										$assign_data = $this->assignproduct_model->getAssignProductDetails($whr_18, '', '', 'result', '', '', '', '', $col_18);

										if(!empty($assign_data))
										{
											$order_value .= $order_val.',';
										}
									}

									$order_list = substr_replace($order_value, '', -1);

									if(!empty($order_list))
									{
										$whr_19 = array(
						    				'tbl_order.id'                      => $order_list,
						    				'tbl_order_details.order_status'    => '9',
						    				'tbl_order_details.delete_status'   => '1',
						    				'tbl_order.published'               => '1',
						    				'tbl_order_details.vendor_type != ' => '1',
						    				'tbl_order_details.start_date'      => $start_value,
				    						'tbl_order_details.end_date'        => $end_value,
						    			);

						    			$col_19   = 'tbl_order.id';
						    			$grp_19   = 'tbl_order_details.order_id';
						    			$col_19   = 'tbl_order.id';
						    			$res_19   = $this->order_model->getDistributorOrder($whr_19, '', '', 'result', '', '', '', '', $col_19, $grp_19);
						    			$can_cnt = !empty($res_19)?count($res_19):'0';
									}
								}

								$order_res[] = array(
									'distributor_id' => $distri_id,
									'company_name'   => $company_name,
									'contact_no'     => $mobile,
									'process_order'  => strval($pro_cnt),
									'packing_order'  => strval($pak_cnt),
									'shipping_order' => strval($ship_cnt),
									'invoice_order'  => strval($inv_cnt),
									'delivery_order' => strval($del_cnt),
									'cancel_order'   => strval($can_cnt),
								);
				    		}

				    		$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $order_res;
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
			    	else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Invalid Date"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			    }
			}

			else if($method == '_getOutletOverallOrder')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

			    		// Distributor List
				    	$whr_1 = array('status' => '1', 'published' => '1');
				    	$col_1 = 'id, company_name, mobile, type_id';
				    	$res_1 = $this->distributors_model->getDistributors($whr_1, '', '', 'result', '', '', '', '', $col_1);

				    	if($res_1)
				    	{
				    		$order_result = [];
				    		foreach ($res_1 as $key => $val_1) {
				    			$distri_id    = !empty($val_1->id)?$val_1->id:'';
				    			$company_name = !empty($val_1->company_name)?$val_1->company_name:'';
				    			$type_id      = !empty($val_1->type_id)?$val_1->type_id:'';

				    			$whr_2 = array(
				    				'tbl_order_details.order_status'    => '2,3,4,5,6,9',
				    				'tbl_order_details.delete_status'   => '1',
				    				'tbl_order.published'               => '1',
				    				'tbl_order_details.type_id'         => $type_id,
				    				'tbl_order_details.vendor_type != ' => '1',
				    				'tbl_order_details.start_date'      => $start_value,
				    				'tbl_order_details.end_date'        => $end_value,
				    			);

				    			$col_2 = 'tbl_order_details.order_id, tbl_order_details.zone_id, tbl_order_details.type_id';
					    		$grp_2 = 'tbl_order_details.order_id';
								$res_2 = $this->order_model->getDistributorOrder($whr_2, '', '', 'result', '', '', '', '', $col_2, $grp_2);

								$order_value = '';
								if(!empty($res_2))
								{
									foreach ($res_2 as $key => $val_2) {
										$order_val = !empty($val_2->order_id)?$val_2->order_id:'';
									    $zone_val  = !empty($val_2->zone_id)?$val_2->zone_id:'';
									    $type_val  = !empty($val_2->type_id)?$val_2->type_id:'';

									    $whr_3 = array(
									    	'distributor_id' => $distri_id,
									    	'type_id'        => $type_val,
									    	'zone_id'        => $zone_val,
									    	'status'         => '1', 
											'published'      => '1'
									    );

									    $col_3 = 'id';

										$assign_data = $this->assignproduct_model->getAssignProductDetails($whr_3, '', '', 'result', '', '', '', '', $col_3);

										if(!empty($assign_data))
										{
											$order_value .= $order_val.',';
										}
									}
								}

								$order_list = substr_replace($order_value, '', -1);

								if(!empty($order_list))
								{
									$whr_4 = array(
					    				'tbl_order.id'                      => $order_list,
					    				'tbl_order_details.delete_status'   => '1',
					    				'tbl_order.published'               => '1',
					    				'tbl_order_details.vendor_type != ' => '1',
					    				'tbl_order_details.start_date'      => $start_value,
				    					'tbl_order_details.end_date'        => $end_value,
					    			);

					    			$col_4 = 'tbl_order.id, tbl_order.order_no, tbl_order.emp_name, tbl_order.store_name, tbl_order.zone_id,  tbl_order.due_days, tbl_order.discount, tbl_order.random_value, tbl_order.createdate';

						    		$groupby = 'tbl_order_details.order_id';

									$res_4 = $this->order_model->getDistributorOrder($whr_4, '', '', 'result', '', '', '', '', $col_4, $groupby);

									if(!empty($res_4))
									{	
										foreach ($res_4 as $key => $val_4){
											$order_id   = !empty($val_4->id)?$val_4->id:'';
											$order_no   = !empty($val_4->order_no)?$val_4->order_no:'';
											$emp_name   = !empty($val_4->emp_name)?$val_4->emp_name:'Admin';
											$store_name = !empty($val_4->store_name)?$val_4->store_name:'';
											$zone_value = !empty($val_4->zone_id)?$val_4->zone_id:'';
											$due_days   = !empty($val_4->due_days)?$val_4->due_days:'0';
											$discount   = !empty($val_4->discount)?$val_4->discount:'0';
											$random_val = !empty($val_4->random_value)?$val_4->random_value:'';
											$createdate = !empty($val_4->createdate)?$val_4->createdate:'';

											// Order Details
										    $whr_5 = array(
							    				'tbl_order_details.order_id'        => $order_id,
							    				'tbl_order_details.delete_status'   => '1',
							    				'tbl_order_details.published'       => '1',
							    				'tbl_order_details.type_id'         => $type_id,
							    				'tbl_order_details.vendor_type != ' => '1',
							    				'tbl_order_details.start_date'      => $start_value,
				    							'tbl_order_details.end_date'        => $end_value,
							    			);

							    			$col_5 = 'tbl_order_details.id, tbl_order_details.order_no, tbl_order_details.type_id, tbl_order_details.price, tbl_order_details.order_qty';

		    								$res_5 = $this->order_model->getDistributorOrder($whr_5, '', '', 'result', '', '', '', '', $col_5);

		    								$inv_total  = 0;
		    								$order_data = '';
		    								if(!empty($res_5))
		    								{
		    									foreach ($res_5 as $key => $val_5) {

		    										$type_res  = !empty($val_5->type_id)?$val_5->type_id:'';

													$whr_6 = array(
												    	'distributor_id' => $distri_id,
												    	'type_id'        => $type_res,
												    	'zone_id'        => $zone_value,
												    	'status'         => '1', 
														'published'      => '1'
												    );

												    $col_6 = 'id';
													$res_6 = $this->assignproduct_model->getAssignProductDetails($whr_6, '', '', 'result', '', '', '', '', $col_6);

													if(!empty($res_6))
													{
														$ord_value = !empty($val_5->id)?$val_5->id:'';
														$price_val = !empty($val_5->price)?number_format((float)$val_5->price, 2, '.', ''):'0';
														$ord_qty   = !empty($val_5->order_qty)?$val_5->order_qty:'';
														$price_tot   = $price_val * $ord_qty;
														$inv_total  += $price_tot;

														$order_data .= $ord_value.',';
													}
		    									}
		    								}

		    								// Round Val Details
					                        $net_value  = round($inv_total);
					                        if($discount != 0)
					                        {
					                        	$total_dis  = $net_value * $discount / 100;
					                        }
					                        else
					                        {
					                        	$total_dis = 0;	
					                        }

					                        $total_val  = $net_value - $total_dis;

					                        // Round Val Details
					                        $last_value = round($total_val);

					                        // Round Val Details
					                        $last_value = round($total_val);
					                        $rond_total = $last_value - $total_val;

					                        // Order Status
					                        $order_res = substr($order_data, 0, -1);

					                        if(!empty($order_res))
					                        {
					                        	$whr_7 = array(
								    				'tbl_order.id'                    => $order_id,
								    				'tbl_order_details.id'            => $order_res,
								    				'tbl_order_details.delete_status' => '1',
								    				'tbl_order_details.published'     => '1',
								    			);

								    			$col_7 = 'tbl_order_details.order_status, tbl_order_details._processing, tbl_order_details._packing, tbl_order_details._shiping, tbl_order_details._invoice, tbl_order_details._delivery, tbl_order_details.invoice_num';
					        					$grp_7 = 'tbl_order_details.order_status';
					        					$res_7 = $this->order_model->getDistributorOrder($whr_7, '', '', 'result', '', '', '', '', $col_7, $grp_7);

					        					$sta_val  = !empty($res_7[0]->order_status)?$res_7[0]->order_status:'';
					        					$pro_val  = !empty($res_7[0]->_processing)?date('d-m-Y', strtotime($res_7[0]->_processing)):'';
					        					$pck_val  = !empty($res_7[0]->_packing)?date('d-m-Y', strtotime($res_7[0]->_packing)):'';
					        					$shp_val  = !empty($res_7[0]->_shiping)?date('d-m-Y', strtotime($res_7[0]->_shiping)):'';
					        					$inv_val  = !empty($res_7[0]->_invoice)?date('d-m-Y', strtotime($res_7[0]->_invoice)):'';
					        					$del_val  = !empty($res_7[0]->_delivery)?date('d-m-Y', strtotime($res_7[0]->_delivery)):'';
					        					$inv_num  = !empty($res_7[0]->invoice_num)?$res_7[0]->invoice_num:'';

					        					// Order Status
									            if($sta_val == '1')
											    {
											        $order_view = 'Success';
											    }
											    else if($sta_val == '2')
											    {
											        $order_view = 'Approved';
											    }
											    else if($sta_val == '3')
											    {
											        $order_view = 'Packing';
											    }
											    else if($sta_val == '4')
											    {
											        $order_view = 'Shipping';
											    }
											    else if($sta_val == '5')
											    {
											        $order_view = 'Invoice';
											    }
											    else if($sta_val == '6')
											    {
											        $order_view = 'Delivery';
											    }
											    else if($sta_val == '7')
											    {
											        $order_view = 'Complete';
											    }
											    else
											    {
											        $order_view = 'Cancel Invoice';
											    }

												$order_result[] = array(
													'order_no'         => $order_no,
													'emp_name'         => $emp_name,
													'store_name'       => $store_name,
													'distributor_name' => $company_name,
													'discount'         => $discount,   
													'due_days'         => $due_days,
													'order_status'     => $order_view,
													'round_value'      => strval($rond_total),
							            			'order_value'      => strval($last_value),
							            			'order_date'       => date('d-m-Y', strtotime($createdate)),
							            			'_processing'      => $pro_val,
							            			'_packing'         => $pck_val,
							            			'_shiping'         => $shp_val,
							            			'_invoice'         => $inv_val,
							            			'_delivery'        => $del_val,
							            			'invoice_no'       => $inv_num,
												);
					                        }
										}
									}
								}
				    		}


				    		$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $order_result;
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
			    	else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Invalid Date"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			    }
			}

			else if($method == '_outletXmlExport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >='   => $start_value,
							'createdate <='   => $end_value,
							'distributor_id'  => $distributor_id,
							'cancel_status'   => '1',
							// 'delivery_status' => '1',
							'published'       => '1',
						);

						$column_1 = 'id, invoice_no, distributor_id, store_id, due_days, discount, createdate';

						$result_1 = $this->invoice_model->getInvoice($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($result_1)
						{
							$inv_data = [];
							foreach ($result_1 as $key => $val_1) {
								$inv_id     = !empty($val_1->id)?$val_1->id:'';
								$inv_no     = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
								$dis_id     = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
								$store_id   = !empty($val_1->store_id)?$val_1->store_id:'';
								$createdate = !empty($val_1->createdate)?$val_1->createdate:'';

								// Store Details
								$where_2  = array('id' => $store_id);
								$column_2 = 'company_name, mobile, email, gst_no, address, country_id, state_id';

								$res_2    = $this->outlets_model->getOutlets($where_2, '', '', 'result', '', '', '', '', $column_2);

								$str_name     = !empty($res_2[0]->company_name)?$res_2[0]->company_name:'';
								$str_mobile   = !empty($res_2[0]->mobile)?$res_2[0]->mobile:'';
								$str_email    = !empty($res_2[0]->email)?$res_2[0]->email:'';
								$str_gst_no   = !empty($res_2[0]->gst_no)?$res_2[0]->gst_no:'';
								$str_address  = !empty($res_2[0]->address)?$res_2[0]->address:'';
								$str_state_id = !empty($res_2[0]->state_id)?$res_2[0]->state_id:'';

								// State Details
								$where_3  = array('id' => $str_state_id);
								$column_3 = 'state_name';
								$res_3    = $this->commom_model->getState($where_3, '', '', 'result', '', '', '', '', $column_3);

								$str_state_name = !empty($res_3[0]->state_name)?$res_3[0]->state_name:'';

								// Distributor Details
								$where_4  = array('id' => $dis_id);
								$column_4 = 'company_name, state_id, vch_key';

								$res_4    = $this->distributors_model->getDistributors($where_4, '', '', 'result', '', '', '', '', $column_4);

								$distri_name  = !empty($res_4[0]->company_name)?$res_4[0]->company_name:'';
								$distri_state = !empty($res_4[0]->state_id)?$res_4[0]->state_id:'';
								$distri_vch   = !empty($res_4[0]->vch_key)?$res_4[0]->vch_key:'';

								// Invoice Date Details
								$inv_date = date('d-M-Y', strtotime($createdate));
								$inv_time = date('H:i', strtotime($createdate));

								// Invoice Details
								$where_5  = array('invoice_id' => $inv_id, 'published' => '1');
								$column_5 = 'product_id, type_id, hsn_code, gst_val, unit_val, price, order_qty, receive_qty';
								$result_5 = $this->invoice_model->getInvoiceDetails($where_5, '', '', 'result', '', '', '', '', $column_5);

								$inv_details = [];
								$inv_total   = 0;
								if($result_5)
								{
									foreach ($result_5 as $key => $val_5) {
										$product_id  = !empty($val_5->product_id)?$val_5->product_id:'0';
							            $type_id     = !empty($val_5->type_id)?$val_5->type_id:'0';
							            $gst_val     = !empty($val_5->gst_val)?$val_5->gst_val:'0';
							            $unit_val    = !empty($val_5->unit_val)?$val_5->unit_val:'0';
							            $price_val   = !empty($val_5->price)?$val_5->price:'0';
							            $order_qty   = !empty($val_5->order_qty)?$val_5->order_qty:'0';
							            $receive_qty = !empty($val_5->receive_qty)?$val_5->receive_qty:'0';

							            // Prouct Type Details
							            $where_6  = array('id' => $type_id);
							            $column_6 = 'description, mrp_price';
							            $res_6    = $this->commom_model->getProductType($where_6, '', '', 'result', '', '', '', '', $column_6);

							            $descrip  = !empty($res_6[0]->description)?$res_6[0]->description:'';
							            $pdt_mrp  = !empty($res_6[0]->mrp_price)?$res_6[0]->mrp_price:'0';

							            // Unit Details
							            $where_7  = array('id' => $unit_val);
							            $column_7 = 'name';
							            $res_7    = $this->commom_model->getUnit($where_7, '', '', 'result', '', '', '', '', $column_7);

							            $unit_name = !empty($res_7[0]->name)?$res_7[0]->name:'';

							            // Price Details
							            $gst_data  = $price_val - ($price_val * (100 / (100 + $gst_val)));
							            $price_res = $price_val - $gst_data;
							            $tot_price = $order_qty * $price_res;

							            $inv_details[] = array(
							            	'product_name'  => $descrip,
							            	'product_unit'  => $unit_name,
							            	'product_mrp'   => $pdt_mrp,
							            	'product_gst'   => $gst_val,
							            	'product_price' => $price_res,
							            	'total_price'   => $tot_price,
							            	'order_qty'     => $order_qty,
							            	'receive_qty'   => $receive_qty,
							            );

							            // Invoice Total
							            $total_val  = $receive_qty * $price_val;
							            $inv_total += $total_val;
									}
								}

								// Rount Value Details
								$net_value  = round($inv_total);
                				$rond_total = $net_value - $inv_total;

                				$round_details = array(
                					'round_val' => $rond_total,
                					'net_val'   => $net_value,
                				);

                				// GSTIN Details
                				$where_6   = array('invoice_id' => $inv_id, 'published' => '1');
								$column_6  = 'hsn_code, gst_val';
								$groupby_6 = 'hsn_code';
								$result_6  = $this->invoice_model->getInvoiceDetails($where_6, '', '', 'result', '', '', '', '', $column_6, $groupby_6);

								$tax_details = [];
	        					if(!empty($result_6))
	        					{
	        						foreach ($result_6 as $key => $val_6) {
	        							$hsn_code = !empty($val_6->hsn_code)?$val_6->hsn_code:'';
	        							$gst_val  = !empty($val_6->gst_val)?$val_6->gst_val:'';

	        							// Price Details
			        					$where_7  = array('invoice_id' => $inv_id, 'hsn_code' => $hsn_code,'published'  => '1');
					        			$column_7 = 'price, order_qty, gst_val';

					        			$result_7 = $this->invoice_model->getInvoiceDetails($where_7, '', '', 'result', '', '', '', '', $column_7);

					        			$product_price = 0;
					        			$gst_price     = 0;
					        			foreach ($result_7 as $key => $val_7) {
					        				$price     = !empty($val_7->price)?$val_7->price:'';
					        				$order_qty = !empty($val_7->order_qty)?$val_7->order_qty:'';
					        				$gst_val   = !empty($val_7->gst_val)?$val_7->gst_val:'';

					        				$gst_data    = $price - ($price * (100 / (100 + $gst_val)));
		                            		$price_val   = $price - $gst_data;
		                            		$total_price = $order_qty * $price_val;
		                            		$total_gst   = $order_qty * $gst_data;

					        				$gst_price     += $total_gst;
					        				$product_price += $total_price;
					        			}

					        			$tax_details[] = array(
			        						'hsn_code'    => $hsn_code,
			        						'gst_val'     => $gst_val,
			        						'gst_value'   => $gst_price,
			        						'price_value' => $product_price,
			        						
			        					);
	        						}
	        					}

								$inv_data[] = array(
									'invoive_id'       => $inv_id,
									'invoive_no'       => $inv_no,
									'store_name'       => $str_name,
									'store_mobile'     => $str_mobile,
									'store_email'      => $str_email,
									'store_gst_no'     => $str_gst_no,
									'store_address'    => $str_address,
									'store_state_id'   => $str_state_id,
									'store_state_name' => $str_state_name,
									// 'distri_name'      => $distri_name,
									// 'distri_state_id'  => $distri_state,
									// 'distri_vch_key'   => $distri_vch,
									'invoice_date'     => $inv_date.' at '.$inv_time,
									'createdate'       => $createdate,
									'invoice_details'  => $inv_details,
									'tax_details'      => $tax_details,
									'round_details'    => $round_details,
								);
							}

							// Distributor Details
							$where_8  = array('id' => $distributor_id);
							$column_8 = 'company_name, state_id, vch_key';

							$res_8    = $this->distributors_model->getDistributors($where_8, '', '', 'result', '', '', '', '', $column_8);

							$distri_name  = !empty($res_8[0]->company_name)?$res_8[0]->company_name:'';
							$distri_state = !empty($res_8[0]->state_id)?$res_8[0]->state_id:'';
							$distri_vch   = !empty($res_8[0]->vch_key)?$res_8[0]->vch_key:'';

							$dis_data = array(
								'distributor_name'  => $distri_name,
								'distributor_state' => $distri_state,
								'distributor_vch'   => $distri_vch,
							);

							$array_val = array(
								'dis_data' => $dis_data,
								'inv_data' => $inv_data,
							);

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $array_val;
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
				    else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Invalid Date"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
				}
			}

			else if($method == '_outletGstExport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >='   => $start_value,
							'createdate <='   => $end_value,
							'distributor_id'  => $distributor_id,
							'cancel_status'   => '1',
							'published'       => '1',
						);

						$column_1 = 'id, invoice_no, distributor_id, store_id, due_days, discount, createdate';

						$result_1 = $this->invoice_model->getInvoice($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($result_1)
						{
							$inv_data = [];
                            foreach ($result_1 as $key => $val_1) {

                            	$inv_id     = !empty($val_1->id)?$val_1->id:'';
                                $inv_no     = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
                                $dis_id     = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
                                $store_id   = !empty($val_1->store_id)?$val_1->store_id:'';
                                $createdate = !empty($val_1->createdate)?$val_1->createdate:'';

                            	// Store Details
	                            $where_2  = array('id' => $store_id);
	                            $column_2 = 'company_name, gst_no, state_id';

	                            $res_2    = $this->outlets_model->getOutlets($where_2, '', '', 'result', '', '', '', '', $column_2);

	                            $str_name     = !empty($res_2[0]->company_name)?$res_2[0]->company_name:'';
	                            $str_gst_no   = !empty($res_2[0]->gst_no)?$res_2[0]->gst_no:'';
	                            $str_state_id = !empty($res_2[0]->state_id)?$res_2[0]->state_id:'';

	                            // State Details
	                            $where_3  = array('id' => $str_state_id);
	                            $column_3 = 'state_name, gst_code';
	                            $res_3    = $this->commom_model->getState($where_3, '', '', 'result', '', '', '', '', $column_3);

	                            $str_state_name = !empty($res_3[0]->state_name)?$res_3[0]->state_name:'';
	                            $str_gst_code   = !empty($res_3[0]->gst_code)?$res_3[0]->gst_code:'';

	                            $gst_per = array('0', '5', '12', '18', '28');
	                            $gst_cnt = count($gst_per);

	                            // Invoice Details
	                            $where_4  = array('invoice_id' => $inv_id, 'published' => '1');
	                            $column_4 = 'price, order_qty';
	                            $result_4 = $this->invoice_model->getInvoiceDetails($where_4, '', '', 'result', '', '', '', '', $column_4);

	                            $inv_tot  = 0;
	                            if($result_4)
	                            {
	                            	foreach ($result_4 as $key => $val_4) {
	                            		$price_val   = !empty($val_4->price)?number_format((float)$val_4->price, 2, '.', ''):'0';
                                        $order_qty   = !empty($val_4->order_qty)?$val_4->order_qty:'0';
                                        $tot_price   = $order_qty * $price_val;
                                        $inv_tot    += $tot_price;
	                            	}
	                            }

	                            $inv_round = round($inv_tot);
	                            $inv_total = number_format((float)$inv_round, 2, '.', '');

	                            for ($i=0; $i < $gst_cnt; $i++) { 
	                            		
	                            	$gst_res = number_format((float)$gst_per[$i], 2, '.', '');

	                            	// GST Calculation Value
	                            	$where_5  = array(
	                            		'invoice_id' => $inv_id, 
	                            		'gst_val'    => $gst_per[$i],
	                            		'published'  => '1'
	                            	);

	                            	$column_5 = 'price, order_qty, gst_val';
	                            	$result_5 = $this->invoice_model->getInvoiceDetails($where_5, '', '', 'result', '', '', '', '', $column_5);

	                            	$pdt_price = 0;
                                    $gst_price = 0;
	                            	if($result_5)
	                            	{
	                            		foreach ($result_5 as $key => $val_5) {
	                            			$price     = !empty($val_5->price)?number_format((float)$val_5->price, 2, '.', ''):'0';
                                            $order_qty = !empty($val_5->order_qty)?$val_5->order_qty:'';
                                            $gst_val   = !empty($val_5->gst_val)?$val_5->gst_val:'';

                                            $gst_data    = $price - ($price * (100 / (100 + $gst_val)));
                                            $price_val   = $price - $gst_data;
                                            $total_price = $order_qty * $price_val;
                                            $total_gst   = $order_qty * $gst_data;

                                            $gst_price += $total_gst;
                                            $pdt_price += $total_price;
	                            		}
	                            	}

	                            	$pdt_total = number_format((float)$pdt_price, 2, '.', ''); 
	                            	$gst_total = number_format((float)$gst_price, 2, '.', '');

	                            	$inv_data[] = array(
	                            		'company_name' => $str_name,
		                            	'company_gst'  => $str_gst_no,
		                            	'invoice_no'   => $inv_no,
		                            	'invoice_date' => date('d-M-Y', strtotime($createdate)),
		                            	'state_gst'    => $str_gst_code,
		                            	'state_name'   => $str_state_name,
		                            	'gst_rate'     => $gst_res,
		                            	'invoice_val'  => $inv_total,
		                            	'product_val'  => $pdt_total,
		                            	'taxable_val'  => $gst_total,
		                            );
	                            }
                            }

                            $response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $inv_data;
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
				    else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Invalid Date"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
				}
			}

			else if($method == '_overallPurchaseReport')
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
			    		$start_val = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_val   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >='   => $start_val,
							'createdate <='   => $end_val,
							'distributor_id'  => $distributor_id,
							'published'       => '1',
						);
						$array = array(8,7,9);

						$we_in['order_status'] = $array;
						$column_1 = 'id, po_no, distributor_id, _ordered, _delivery, invoice_no, invoice_random, order_status';

						$result_1 = $this->distributorpurchase_model->getDistributorPurchase($where_1, '', '', 'result', '', '', '', '', $column_1,$we_in);

						if($result_1)
						{
							$data_list = [];
							foreach ($result_1 as $key => $val_1) {

								$auto_id    = !empty($val_1->id)?$val_1->id:'';
					            $po_no      = !empty($val_1->po_no)?$val_1->po_no:'';
					            $dis_id     = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
					            $_ordered   = !empty($val_1->_ordered)?$val_1->_ordered:'';
					            $_delivery  = !empty($val_1->_delivery)?$val_1->_delivery:'';
					            $inv_num    = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
					            $inv_random = !empty($val_1->invoice_random)?$val_1->invoice_random:'';

					            // Purchase Value
					            $whr_2 = array(
					            	'po_id'         => $auto_id, 
					            	'delete_status' => '1',
			            			'status'        => '1',
			            			'published'     => '1'
			            		);

					            $col_2 = 'product_price, product_qty';
					            $res_2 = $this->distributorpurchase_model->getDistributorPurchaseDetails($whr_2, '', '', 'result', '', '', '', '', $col_2);

					            $net_val = 0;
					            if($res_2)
					            {
					            	foreach ($res_2 as $key => $val_2) {
					            		$pdt_price = !empty($val_2->product_price)?$val_2->product_price:'0';
            							$pdt_qty   = !empty($val_2->product_qty)?$val_2->product_qty:'0';
            							$tot_val   = $pdt_qty * $pdt_price;
            							$net_val  += $tot_val;
					            	}
					            }

					            $data_list[] = array(
					            	'auto_id'        => $auto_id,
						            'po_no'          => $po_no,
						            'distributor_id' => $dis_id,
						            '_ordered'       => date('d-m-Y', strtotime($_ordered)),
						            '_delivery'      => date('d-m-Y', strtotime($_delivery)),
						            'invoice_no'     => $inv_num,
						            'invoice_random' => $inv_random,
						            'purchase_value' => strval(round($net_val)),
					            );
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
					        $response['message'] = "Data Not Found"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
				    	}
				    }
				    else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Invalid Date"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
				}
			}

			else if($method == '_overallPurchaseDetails')
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
			    		$start_val = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_val   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >='   => $start_val,
							'createdate <='   => $end_val,
							'distributor_id'  => $distributor_id,
			            	'delete_status'   => '1',
	            			'status'          => '1',
	            			'published'       => '1'
						);
						$array = array(8,7,9);

						$we_in['order_status'] = $array;
						$column_1 = 'id, po_id, distributor_id, product_id, type_id, product_price, product_qty, createdate';

						$result_1 = $this->distributorpurchase_model->getDistributorPurchaseDetails($where_1, '', '', 'result', '', '', '', '', $column_1,'',$we_in);
						if($result_1)
						{
							$data_list = [];
							foreach ($result_1 as $key => $val_1) {
								$auto_id   = !empty($val_1->id)?$val_1->id:'';
					            $po_id     = !empty($val_1->po_id)?$val_1->po_id:'';
					            $dis_id    = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
					            $pdt_id    = !empty($val_1->product_id)?$val_1->product_id:'';
					            $type_id   = !empty($val_1->type_id)?$val_1->type_id:'';
					            $pdt_price = !empty($val_1->product_price)?$val_1->product_price:'';
					            $pdt_qty   = !empty($val_1->product_qty)?$val_1->product_qty:'';
					            $cre_date  = !empty($val_1->createdate)?$val_1->createdate:'';

					            // Admin Details
					            $whr_2 = array('id' => '1');
								$col_2 = 'username, mobile, email, address, pincode, state_id, gst_no';
								$res_2 = $this->login_model->getLoginStatus($whr_2, '', '', 'result', '', '', '', '', $col_2);

								$adm_username = !empty($res_2[0]->username)?$res_2[0]->username:'';
					            $adm_mobile   = !empty($res_2[0]->mobile)?$res_2[0]->mobile:'';
					            $adm_email    = !empty($res_2[0]->email)?$res_2[0]->email:'';
					            $adm_address  = !empty($res_2[0]->address)?$res_2[0]->address:'';
					            $adm_pincode  = !empty($res_2[0]->pincode)?$res_2[0]->pincode:'';
					            $adm_state_id = !empty($res_2[0]->state_id)?$res_2[0]->state_id:'';
					            $adm_gst_no   = !empty($res_2[0]->gst_no)?$res_2[0]->gst_no:'';

								// Purchase Details
								$whr_4 = array('id' => $po_id);
								$col_4 = 'po_no, invoice_id, createdate';
								$res_4 = $this->distributorpurchase_model->getDistributorPurchase($whr_4, '', '', 'result', '', '', '', '', $col_4);

								$po_no    = !empty($res_4[0]->po_no)?$res_4[0]->po_no:'';
            					$inv_id   = !empty($res_4[0]->invoice_id)?$res_4[0]->invoice_id:'';
            					$cre_date = !empty($res_4[0]->createdate)?$res_4[0]->createdate:'';

					            // Invoice Details
					            $invoice_no = '';
	            				$createdate = '';

					            if(!empty($inv_id))
					            {
					            	$whr_5 = array('id' => $inv_id);
									$col_5 = 'invoice_no, createdate';
									$res_5 = $this->invoice_model->getDistributorInvoice($whr_5, '', '', 'result', '', '', '', '', $col_5);

									$invoice_no = !empty($res_5[0]->invoice_no)?$res_5[0]->invoice_no:'';
	            					$inv_date   = !empty($res_5[0]->createdate)?$res_5[0]->createdate:'';
	            					$createdate = date('d-M-Y', strtotime($inv_date));
					            }

					            // Product Deails
            					$whr_6 = array('id' => $pdt_id);
            					$col_6 = 'gst, hsn_code';
            					$res_6 = $this->commom_model->getProduct($whr_6, '', '', 'result', '', '', '', '', $col_6);

            					$gst_val = !empty($res_6[0]->gst)?$res_6[0]->gst:'';
            					$hsn_val = !empty($res_6[0]->hsn_code)?$res_6[0]->hsn_code:'';

					            // Product Type Details
            					$whr_7 = array('id' => $type_id);
            					$col_7 = 'description';
            					$res_7 = $this->commom_model->getProductType($whr_7, '', '', 'result', '', '', '', '', $col_7);

            					$desc_val = !empty($res_7[0]->description)?$res_7[0]->description:'';

					            // Distributor Details
					            $whr_8 = array('id' => $dis_id);
								$col_8 = 'company_name, gst_no, state_id';
								$res_8 = $this->distributors_model->getDistributors($whr_8, '', '', 'result', '', '', '', '', $col_8);

								$dis_company  = !empty($res_8[0]->company_name)?$res_8[0]->company_name:'';
								$dis_gst_no   = !empty($res_8[0]->gst_no)?$res_8[0]->gst_no:'';
								$dis_state_id = !empty($res_8[0]->state_id)?$res_8[0]->state_id:'';

								// State Details
					            $whr_9 = array('id' => $dis_state_id);
								$col_9 = 'state_name';
								$res_9 = $this->commom_model->getState($whr_9, '', '', 'result', '', '', '', '', $col_9);

								$dis_state_val = !empty($res_9[0]->state_name)?$res_9[0]->state_name:'';

					            $data_list[] = array(
					            	'pur_no'        => $po_no,
					            	'pur_date'      => date('d-M-Y', strtotime($cre_date)),
					            	'adm_username'  => $adm_username,
					            	'adm_state_id'  => $adm_state_id,
					            	'dis_username'  => $dis_company,
					            	'dis_gst_no'    => $dis_gst_no,
					            	'dis_state_id'  => $dis_state_id,
					            	'dis_state_val' => $dis_state_val,
						            'product_name'  => $desc_val, 
            						'hsn_code'      => $hsn_val,
            						'product_qty'   => $pdt_qty,
            						'product_gst'   => $gst_val,
            						'product_price' => $pdt_price,
            						'invoice_no'    => $invoice_no,
            						'invoice_date'  => $createdate,
					            );

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
					        $response['message'] = "Data Not Found"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
				    	}
				    }
				    else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Invalid Date"; 
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

		// Sales Report
		// ***************************************************
		public function sales_report($param1="",$param2="",$param3="")
		{	
			$method         = $this->input->post('method');
			$start_date     = $this->input->post('start_date');
			$end_date       = $this->input->post('end_date');
			$distributor_id = $this->input->post('distributor_id');

			if($method == '_salesReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'cancel_status' => '1',
							'published'     => '1',
						);

						if(!empty($distributor_id))
						{
							$where_1['distributor_id'] = $distributor_id;
						}

						$column_1 = 'invoice_id, invoice_no, type_id, hsn_code, gst_val, price, order_qty';

						$inv_details = $this->invoice_model->getDistributorInvoiceDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($inv_details)
						{
							$sales_details = [];
							foreach ($inv_details as $key => $val_1) {

								$invoice_id = !empty($val_1->invoice_id)?$val_1->invoice_id:'';
								$invoice_no = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
								$type_id    = !empty($val_1->type_id)?$val_1->type_id:'';
								$hsn_code   = !empty($val_1->hsn_code)?$val_1->hsn_code:'';
								$gst_val    = !empty($val_1->gst_val)?$val_1->gst_val:'';
								$price      = !empty($val_1->price)?$val_1->price:'';
								$order_qty  = !empty($val_1->order_qty)?$val_1->order_qty:'';

								// Invoice Details
								$where_2  = array('id' => $invoice_id);
								$column_2 = 'order_id, distributor_id, date';
								$inv_data = $this->invoice_model->getDistributorInvoice($where_2, '', '', 'result', '', '', '', '', $column_2);

								$ord_id   = !empty($inv_data[0]->order_id)?$inv_data[0]->order_id:'';
								$dis_id   = !empty($inv_data[0]->distributor_id)?$inv_data[0]->distributor_id:'';
								$ord_date = !empty($inv_data[0]->date)?$inv_data[0]->date:'';

								// Distributor Details
								$where_3  = array('id' => $dis_id);
								$column_3 = 'company_name, mobile, gst_no, address, state_id';
								$dis_data = $this->distributors_model->getDistributors($where_3, '', '', 'result', '', '', '', '', $column_3);

								$com_name  = !empty($dis_data[0]->company_name)?$dis_data[0]->company_name:'';
					            $mobile    = !empty($dis_data[0]->mobile)?$dis_data[0]->mobile:'';
					            $gst_no    = !empty($dis_data[0]->gst_no)?$dis_data[0]->gst_no:'';
					            $address   = !empty($dis_data[0]->address)?$dis_data[0]->address:'';
					            $dis_state = !empty($dis_data[0]->state_id)?$dis_data[0]->state_id:'';

					            // State Details
								$where_4  = array('id' => $dis_state);
								$column_4 = 'gst_code, state_name';
								$gst_data = $this->commom_model->getState($where_4, '', '', 'result', '', '', '', '', $column_4);

								$state_name = !empty($gst_data[0]->state_name)?$gst_data[0]->state_name:'';
								$gst_code   = !empty($gst_data[0]->gst_code)?$gst_data[0]->gst_code:'';

								// Product Details
								$where_5  = array('id' => $type_id);
								$column_5 = 'description';
								$pdt_data = $this->commom_model->getProductType($where_5, '', '', 'result', '', '', '', '', $column_5);

								$description  = !empty($pdt_data[0]->description)?$pdt_data[0]->description:'';

								$where_6  = array('id' => '1');
								$column_6 = 'state_id';
								$adm_data = $this->user_model->getUser($where_6, '', '', 'result', '', '', '', '', $column_6);

								$adm_state = !empty($adm_data[0]->state_id)?$adm_data[0]->state_id:'';

								// Order Details
								$where_7  = array('id' => $ord_id);
								$column_7 = 'po_no';
								$pur_data = $this->distributorpurchase_model->getDistributorPurchase($where_7, '', '', 'result', '', '', '', '', $column_7);

								$pur_num  = !empty($pur_data[0]->po_no)?$pur_data[0]->po_no:'';

								$sales_details[] = array(
									'adm_state'    => $adm_state,
									'invoice_no'   => $invoice_no,
									'order_no'     => $pur_num,
									'dis_name'     => $com_name,
									'mobile'       => $mobile,
									'gst_no'       => $gst_no,
									'address'      => $address,
									'state_id'     => $dis_state,
									'state_name'   => $state_name,
									'gst_code'     => $gst_code,
									'invoice_date' => date('d-m-Y', strtotime($ord_date)),
									'description'  => $description,
									'hsn_code'     => $hsn_code,
									'gst_val'      => $gst_val,
									'price'        => $price,
									'order_qty'    => $order_qty,
								);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $sales_details;
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

			else if($method == '_overallInviceReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'cancel_status' => '1',
							'published'     => '1',
						);

						if(!empty($distributor_id))
						{
							$where_1['distributor_id'] = $distributor_id;
						}

						$column_1 = 'id, order_id, invoice_no, distributor_id, due_days, createdate, random_value';

						$inv_details = $this->invoice_model->getDistributorInvoice($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($inv_details)
						{
							$inv_list    = [];
							$inv_cnt     = !empty(count($inv_details))?count($inv_details):'0';
							$tot_amt     = 0;
							$tot_tax     = 0;
							$tot_taxable = 0;

							foreach ($inv_details as $key => $val_1) {
								$inv_id   = !empty($val_1->id)?$val_1->id:'';
					            $ord_id   = !empty($val_1->order_id)?$val_1->order_id:'';
					            $inv_no   = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
					            $dis_id   = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
					            $due_days = !empty($val_1->due_days)?$val_1->due_days:'';
					            $inv_date = !empty($val_1->createdate)?$val_1->createdate:'';
					            $inv_rdm  = !empty($val_1->random_value)?$val_1->random_value:'';

					            // Order No
					            $whr_2 = array('id' => $ord_id);
					            $col_2 = 'id, po_no';
					            $res_2 = $this->distributorpurchase_model->getDistributorPurchase($whr_2, '', '', 'result', '', '', '', '', $col_2);

					            $order_rdm = !empty($res_2[0]->id)?$res_2[0]->id:'';
					            $order_no  = !empty($res_2[0]->po_no)?$res_2[0]->po_no:'';

					            // Invoice Details
					            $whr_4 = array('invoice_id' => $inv_id, 'cancel_status' => '1', 'published' => '1');
					            $col_4 = 'price, order_qty, gst_val';
					            $res_4 = $this->invoice_model->getDistributorInvoiceDetails($whr_4, '', '', 'result', '', '', '', '', $col_4);

					            $inv_total   = 0;
					            $taxable_amt = 0;
					            $tax_amt     = 0;
					            if($res_4)
					            {
					            	foreach ($res_4 as $key => $val_4) {
					            		$pdt_price  = !empty($val_4->price)?$val_4->price:'0';
										$order_qty  = !empty($val_4->order_qty)?$val_4->order_qty:'0';
										$gst_value  = !empty($val_4->gst_val)?$val_4->gst_val:'0';

										$price_tot  = $pdt_price * $order_qty;
										$inv_total += $price_tot;

										// GST Calculation
										$gst_data    = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
										$price_val   = $pdt_price - $gst_data;
										$tax_amt     += $order_qty * $gst_data;
                        				$taxable_amt += $order_qty * $price_val;
					            	}
					            }

					            // Distributor Details
					            $whr_5 = array('id' => $dis_id);
					            $col_5 = 'company_name';
					            $res_5 = $this->distributors_model->getDistributors($whr_5, '', '', 'result', '', '', '', '', $col_5);

					            $dis_name = !empty($res_5[0]->company_name)?$res_5[0]->company_name:'';

					            // Round Val Details
		                        $net_value = round($inv_total);
		                        $total_dis = 0;	
		                        $total_val = $net_value - $total_dis;

		                        // Round Val Details
		                        $last_value = round($total_val);

		                        // Round Val Details
		                        $last_value = round($total_val);
		                        $rond_total = $last_value - $total_val;

		                        $tot_amt     += $last_value;
		                        $tot_tax     += $tax_amt;
		                        $tot_taxable += $taxable_amt;

		                        $inv_list[] = array(
					            	'inv_id'       => $inv_id,
						            'order_no'     => $order_no,
						            'inv_no'       => $inv_no,
						            'inv_date'     => date('d-M-Y', strtotime($inv_date)),
						            'company_name' => $dis_name,
						            'due_days'     => $due_days,
						            'round_value'  => strval($rond_total),
						            'inv_value'    => strval($last_value),
						            'purchase_res' => $order_rdm,
						            'invoice_res'  => $inv_rdm,
					            );
							}

							$total_value = array(
								'total_count'   => strval($inv_cnt),
								'total_taxable' => number_format((float)$tot_taxable, 2, '.', ''),
								'total_tax'     => number_format((float)$tot_tax, 2, '.', ''),
								'total_value'   => number_format((float)$tot_amt, 2, '.', ''),
							);

							$inv_data = array(
								'inv_total' => $total_value,
								'inv_list'  => $inv_list,
							);

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $inv_data;
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

			else if($method == '_salesGstReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'cancel_status' => '1',
							'published'     => '1',
						);

				    	if(!empty($distributor_id))
						{
							$where_1['distributor_id'] = $distributor_id;
						}

						$column_1 = 'id, invoice_no, distributor_id, due_days, createdate';

						$result_1 = $this->invoice_model->getDistributorInvoice($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($result_1)
						{
							$inv_data = [];
							foreach ($result_1 as $key => $val_1) {
								$inv_id   = !empty($val_1->id)?$val_1->id:'';
					            $inv_no   = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
					            $dis_id   = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
					            $due_days = !empty($val_1->due_days)?$val_1->due_days:'';
					            $inv_date = !empty($val_1->createdate)?$val_1->createdate:'';

					            // Store Details
	                            $where_2  = array('id' => $dis_id);
	                            $column_2 = 'company_name, gst_no, state_id';

	                            $res_2    = $this->distributors_model->getDistributors($where_2, '', '', 'result', '', '', '', '', $column_2);

	                            $dis_name     = !empty($res_2[0]->company_name)?$res_2[0]->company_name:'';
	                            $dis_gst_no   = !empty($res_2[0]->gst_no)?$res_2[0]->gst_no:'';
	                            $dis_state_id = !empty($res_2[0]->state_id)?$res_2[0]->state_id:'';

	                            // State Details
	                            $where_3  = array('id' => $dis_state_id);
	                            $column_3 = 'state_name, gst_code';
	                            $res_3    = $this->commom_model->getState($where_3, '', '', 'result', '', '', '', '', $column_3);

	                            $dis_state_name = !empty($res_3[0]->state_name)?$res_3[0]->state_name:'';
	                            $dis_gst_code   = !empty($res_3[0]->gst_code)?$res_3[0]->gst_code:'';

	                            $gst_per = array('0', '5', '12', '18', '28');
	                            $gst_cnt = count($gst_per);

	                            // Invoice Details
	                            $where_4  = array('invoice_id' => $inv_id, 'published' => '1');
	                            $column_4 = 'price, order_qty';
	                            $result_4 = $this->invoice_model->getDistributorInvoiceDetails($where_4, '', '', 'result', '', '', '', '', $column_4);

	                            $inv_tot  = 0;
	                            if($result_4)
	                            {
	                            	foreach ($result_4 as $key => $val_4) {
	                            		$price_val   = !empty($val_4->price)?$val_4->price:'0';
                                        $order_qty   = !empty($val_4->order_qty)?$val_4->order_qty:'0';
                                        $tot_price   = $order_qty * $price_val;
                                        $inv_tot    += $tot_price;
	                            	}
	                            }

	                            $inv_round = round($inv_tot);
	                            $inv_total = number_format((float)$inv_round, 2, '.', '');

	                            for ($i=0; $i < $gst_cnt; $i++) {

	                            	$gst_res = number_format((float)$gst_per[$i], 2, '.', '');

	                            	// GST Calculation Value
	                            	$where_5  = array(
	                            		'invoice_id' => $inv_id, 
	                            		'gst_val'    => $gst_per[$i],
	                            		'published'  => '1'
	                            	);

	                            	$column_5 = 'price, order_qty, gst_val';
	                            	$result_5 = $this->invoice_model->getDistributorInvoiceDetails($where_5, '', '', 'result', '', '', '', '', $column_5);

	                            	$pdt_price = 0;
                                    $gst_price = 0;
	                            	if($result_5)
	                            	{
	                            		foreach ($result_5 as $key => $val_5) {
	                            			$price     = !empty($val_5->price)?$val_5->price:'';
                                            $order_qty = !empty($val_5->order_qty)?$val_5->order_qty:'';
                                            $gst_val   = !empty($val_5->gst_val)?$val_5->gst_val:'0';

                                            $gst_data    = $price - ($price * (100 / (100 + $gst_val)));
                                            $price_val   = $price - $gst_data;
                                            $total_price = $order_qty * $price_val;
                                            $total_gst   = $order_qty * $gst_data;

                                            $gst_price += $total_gst;
                                            $pdt_price += $total_price;
	                            		}
	                            	}

	                            	$pdt_total = number_format((float)$pdt_price, 2, '.', ''); 
	                            	$gst_total = number_format((float)$gst_price, 2, '.', '');

	                            	$inv_data[] = array(
	                            		'company_name' => $dis_name,
		                            	'company_gst'  => $dis_gst_no,
		                            	'invoice_no'   => $inv_no,
		                            	'invoice_date' => date('d-M-Y', strtotime($inv_date)),
		                            	'state_gst'    => $dis_gst_code,
		                            	'state_name'   => $dis_state_name,
		                            	'gst_rate'     => $gst_res,
		                            	'invoice_val'  => $inv_total,
		                            	'product_val'  => $pdt_total,
		                            	'taxable_val'  => $gst_total,
		                            );
	                            }
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $inv_data;
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

			else if($method == '_overallSalesReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'cancel_status' => '1',
							'published'     => '1',
						);

				    	if(!empty($distributor_id))
						{
							$where_1['distributor_id'] = $distributor_id;
						}

						$column_1 = 'invoice_id, invoice_no, type_id, hsn_code, gst_val, price, order_qty';

						$inv_details = $this->invoice_model->getDistributorInvoiceDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($inv_details)
						{
							$sales_details = [];
							foreach ($inv_details as $key => $val_1) {

								$invoice_id = !empty($val_1->invoice_id)?$val_1->invoice_id:'';
								$invoice_no = !empty($val_1->invoice_no)?$val_1->invoice_no:'';
								$type_id    = !empty($val_1->type_id)?$val_1->type_id:'';
								$hsn_code   = !empty($val_1->hsn_code)?$val_1->hsn_code:'';
								$gst_val    = !empty($val_1->gst_val)?$val_1->gst_val:'';
								$price      = !empty($val_1->price)?$val_1->price:'';
								$order_qty  = !empty($val_1->order_qty)?$val_1->order_qty:'';

								// Invoice Details
								$where_2  = array('id' => $invoice_id);
								$column_2 = 'order_id, distributor_id, date';
								$inv_data = $this->invoice_model->getDistributorInvoice($where_2, '', '', 'result', '', '', '', '', $column_2);

								$ord_id   = !empty($inv_data[0]->order_id)?$inv_data[0]->order_id:'';
								$dis_id   = !empty($inv_data[0]->distributor_id)?$inv_data[0]->distributor_id:'';
								$ord_date = !empty($inv_data[0]->date)?$inv_data[0]->date:'';

								// Distributor Details
								$where_3  = array('id' => $dis_id);
								$column_3 = 'company_name, mobile, gst_no, address, state_id';
								$dis_data = $this->distributors_model->getDistributors($where_3, '', '', 'result', '', '', '', '', $column_3);

								$com_name  = !empty($dis_data[0]->company_name)?$dis_data[0]->company_name:'';
					            $mobile    = !empty($dis_data[0]->mobile)?$dis_data[0]->mobile:'';
					            $gst_no    = !empty($dis_data[0]->gst_no)?$dis_data[0]->gst_no:'';
					            $address   = !empty($dis_data[0]->address)?$dis_data[0]->address:'';
					            $dis_state = !empty($dis_data[0]->state_id)?$dis_data[0]->state_id:'';

								// State Details
								$where_4  = array('id' => $dis_state);
								$column_4 = 'gst_code, state_name';
								$gst_data = $this->commom_model->getState($where_4, '', '', 'result', '', '', '', '', $column_4);

								$state_name = !empty($gst_data[0]->state_name)?$gst_data[0]->state_name:'';
								$gst_code   = !empty($gst_data[0]->gst_code)?$gst_data[0]->gst_code:'';

								// Product Details
								$where_5  = array('id' => $type_id);
								$column_5 = 'description';
								$pdt_data = $this->commom_model->getProductType($where_5, '', '', 'result', '', '', '', '', $column_5);

								$description  = !empty($pdt_data[0]->description)?$pdt_data[0]->description:'';

								$where_6  = array('id' => '1');
								$column_6 = 'state_id';
								$adm_data = $this->user_model->getUser($where_6, '', '', 'result', '', '', '', '', $column_6);

								$adm_state = !empty($adm_data[0]->state_id)?$adm_data[0]->state_id:'';

								// Order Details
								$where_7  = array('id' => $ord_id);
								$column_7 = 'po_no, _ordered';
								$pur_data = $this->distributorpurchase_model->getDistributorPurchase($where_7, '', '', 'result', '', '', '', '', $column_7);

								$pur_num  = !empty($pur_data[0]->po_no)?$pur_data[0]->po_no:'';
								$pur_date = !empty($pur_data[0]->_ordered)?$pur_data[0]->_ordered:'';

								$sales_details[] = array(
									'adm_state'    => $adm_state,
									'invoice_no'   => $invoice_no,
									'order_no'     => $pur_num,
									'order_date'   => $pur_date,
									'dis_name'     => $com_name,
									'mobile'       => $mobile,
									'gst_no'       => $gst_no,
									'address'      => $address,
									'state_id'     => $dis_state,
									'state_name'   => $state_name,
									'gst_code'     => $gst_code,
									'invoice_date' => date('d-m-Y', strtotime($ord_date)),
									'description'  => $description,
									'hsn_code'     => $hsn_code,
									'gst_val'      => $gst_val,
									'price'        => $price,
									'order_qty'    => $order_qty,
								);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $sales_details;
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

			else if($method == '_overallSalesReturnReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'published'     => '1',
						);

						if(!empty($distributor_id))
						{
							$where_1['distributor_id'] = $distributor_id;
						}

						$column_1 = 'id, return_no, invoice_id, distributor_name, random_value, createdate';
						$result_1 = $this->return_model->getDistributorReturn($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($result_1)
						{
							$data_list = [];
							foreach ($result_1 as $key => $val_1) {

								$ret_id   = !empty($val_1->id)?$val_1->id:'';
								$ret_no   = !empty($val_1->return_no)?$val_1->return_no:'';
					            $inv_id   = !empty($val_1->invoice_id)?$val_1->invoice_id:'';
					            $dis_name = !empty($val_1->distributor_name)?$val_1->distributor_name:'';
					            $rand_val = !empty($val_1->random_value)?$val_1->random_value:'';
					            $cre_date = !empty($val_1->createdate)?$val_1->createdate:'';

					            // Invoice Details
					            $whr_2 = array('id' => $inv_id);
					            $col_2 = 'invoice_no, random_value';
					            $res_2 = $this->invoice_model->getDistributorInvoice($whr_2, '', '', 'result', '', '', '', '', $col_2);

					            $inv_no     = !empty($res_2[0]->invoice_no)?$res_2[0]->invoice_no:'';
					            $inv_random = !empty($res_2[0]->random_value)?$res_2[0]->random_value:'';

					            // Return Details
					            $whr_3 = array('return_id' => $ret_id, 'published' => '1');
					            $col_3 = 'price, return_qty';
					            $res_3 = $this->return_model->getDistributorReturnDetails($whr_3, '', '', 'result', '', '', '', '', $col_3);

					            $ret_tot = 0;
					            if($res_3)
					            {
					            	foreach ($res_3 as $key => $val_3) {
					            		$price_val = !empty($val_3->price)?$val_3->price:'0';
            							$ret_qty   = !empty($val_3->return_qty)?$val_3->return_qty:'0';
            							$tot_val   = $ret_qty * $price_val;
            							$ret_tot  += $tot_val;
					            	}
					            }

					            $data_list[] = array(
					            	'return_no'        => $ret_no,
					            	'distributor_name' => $dis_name,
					            	'invoice_no'       => $inv_no,
					            	'invoice_random'   => $inv_random,
					            	'return_value'     => strval(round($ret_tot)),
					            	'return_random'    => $rand_val,
					            	'return_date'      => date('d-M-Y', strtotime($cre_date)),
					            );
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
					        $response['message'] = "Data Not Found"; 
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

			else if($method == '_overallSalesReturnDetails')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >=' => $start_value,
							'createdate <=' => $end_value,
							'published'     => '1',
						);

						if(!empty($distributor_id))
						{
							$where_1['distributor_id'] = $distributor_id;
						}

						$column_1 = 'id, return_no, invoice_id, distributor_id, type_id, hsn_code, gst_val, unit_val, price, return_qty, createdate';
						$result_1 = $this->return_model->getDistributorReturnDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($result_1)
						{
							$data_list = [];
							foreach ($result_1 as $key => $val_1) {
									
								$auto_id     = !empty($val_1->id)?$val_1->id:'';
							    $return_no   = !empty($val_1->return_no)?$val_1->return_no:'';
							    $invoice_id  = !empty($val_1->invoice_id)?$val_1->invoice_id:'';
							    $dis_value   = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
							    $type_id     = !empty($val_1->type_id)?$val_1->type_id:'';
							    $hsn_code    = !empty($val_1->hsn_code)?$val_1->hsn_code:'';
							    $gst_val     = !empty($val_1->gst_val)?$val_1->gst_val:'';
							    $unit_val    = !empty($val_1->unit_val)?$val_1->unit_val:'';
							    $price_val   = !empty($val_1->price)?$val_1->price:'';
							    $return_qty  = !empty($val_1->return_qty)?$val_1->return_qty:'';
							    $return_date = !empty($val_1->createdate)?$val_1->createdate:'';

							    // Invoice Details
					            $whr_2 = array('id' => $invoice_id);
					            $col_2 = 'invoice_no, date';
					            $res_2 = $this->invoice_model->getDistributorInvoice($whr_2, '', '', 'result', '', '', '', '', $col_2);

					            $inv_no   = !empty($res_2[0]->invoice_no)?$res_2[0]->invoice_no:'';
					            $inv_date = !empty($res_2[0]->date)?$res_2[0]->date:'';

					            // Distributor Details
					            $whr_3 = array('id' => $dis_value);
					            $col_3 = 'company_name, state_id, gst_no';
					            $res_3 = $this->distributors_model->getDistributors($whr_3, '', '', 'result', '', '', '', '', $col_3);

					            $dis_name   = !empty($res_3[0]->company_name)?$res_3[0]->company_name:'';
					            $state_id   = !empty($res_3[0]->state_id)?$res_3[0]->state_id:'';
					            $gst_number = !empty($res_3[0]->gst_no)?$res_3[0]->gst_no:'';

					            // State Details
					            $whr_4 = array('id' => $state_id);
					            $col_4 = 'state_name';
					            $res_4 = $this->commom_model->getState($whr_4, '', '', 'result', '', '', '', '', $col_4);

					            $dis_state = !empty($res_4[0]->state_name)?$res_4[0]->state_name:'';

					            // Admin Details
					            $whr_5 = array('id' => '1');
					            $col_5 = 'state_id';
					            $res_5 = $this->login_model->getLoginStatus($whr_5, '', '', 'result', '', '', '', '', $col_5);

					            $adm_state = !empty($res_5[0]->state_id)?$res_5[0]->state_id:'';


					            // Product Type Details
					            $whr_6 = array('id' => $type_id);
					            $col_6 = 'description';
					            $res_6 = $this->commom_model->getProductType($whr_6, '', '', 'result', '', '', '', '', $col_6);

					            $pdt_desc = !empty($res_6[0]->description)?$res_6[0]->description:'';

					            $data_list[] = array(
					            	'return_no'   => $return_no,
					            	'return_date' => date('d-m-Y', strtotime($return_date)),
					            	'inv_no'      => $inv_no,
					            	'inv_date'    => date('d-m-Y', strtotime($inv_date)),
					            	'dis_name'    => $dis_name,
					            	'gst_number'  => $gst_number,
					            	'adm_state'   => $adm_state,
					            	'state_id'    => $state_id,
					            	'dis_state'   => $dis_state,
					            	'pdt_desc'    => $pdt_desc,
					            	'hsn_code'    => $hsn_code,
					            	'return_qty'  => $return_qty,
					            	'gst_val'     => $gst_val,
					            	'price_val'   => $price_val,
					            );
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
					        $response['message'] = "Data Not Found"; 
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

			else if($method == '_overallOutletSalesReturnReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >='  => $start_value,
							'createdate <='  => $end_value,
							'distributor_id' => $distributor_id,
							'published'      => '1',
						);

						$column_1 = 'id, return_no, invoice_id, store_name, random_value, createdate';
						$result_1 = $this->return_model->getOutletReturn($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($result_1)
						{
							$data_list = [];
							foreach ($result_1 as $key => $val_1) {

								$ret_id   = !empty($val_1->id)?$val_1->id:'';
								$ret_no   = !empty($val_1->return_no)?$val_1->return_no:'';
					            $inv_id   = !empty($val_1->invoice_id)?$val_1->invoice_id:'';
					            $str_name = !empty($val_1->store_name)?$val_1->store_name:'';
					            $rand_val = !empty($val_1->random_value)?$val_1->random_value:'';
					            $cre_date = !empty($val_1->createdate)?$val_1->createdate:'';

					            // Invoice Details
					            $whr_2 = array('id' => $inv_id);
					            $col_2 = 'invoice_no, random_value';
					            $res_2 = $this->invoice_model->getInvoice($whr_2, '', '', 'result', '', '', '', '', $col_2);

					            $inv_no     = !empty($res_2[0]->invoice_no)?$res_2[0]->invoice_no:'';
					            $inv_random = !empty($res_2[0]->random_value)?$res_2[0]->random_value:'';

					            // Return Details
					            $whr_3 = array('return_id' => $ret_id, 'published' => '1');
					            $col_3 = 'price, return_qty';
					            $res_3 = $this->return_model->getOutletReturnDetails($whr_3, '', '', 'result', '', '', '', '', $col_3);

					            $ret_tot = 0;
					            if($res_3)
					            {
					            	foreach ($res_3 as $key => $val_3) {
					            		$price_val = !empty($val_3->price)?$val_3->price:'0';
            							$ret_qty   = !empty($val_3->return_qty)?$val_3->return_qty:'0';
            							$tot_val   = $ret_qty * $price_val;
            							$ret_tot  += $tot_val;
					            	}
					            }

					            $data_list[] = array(
					            	'return_no'      => $ret_no,
					            	'store_name'     => $str_name,
					            	'invoice_no'     => $inv_no,
					            	'invoice_random' => $inv_random,
					            	'return_value'   => strval(round($ret_tot)),
					            	'return_random'  => $rand_val,
					            	'return_date'    => date('d-M-Y', strtotime($cre_date)),
					            );
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
					        $response['message'] = "Data Not Found"; 
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

			else if($method == '_overallOutletSalesReturnDetails')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

				    	$where_1 = array(
							'createdate >='  => $start_value,
							'createdate <='  => $end_value,
							'distributor_id' => $distributor_id,
							'published'      => '1',
						);

						$column_1 = 'id, return_no, invoice_id, distributor_id, outlet_id, type_id, hsn_code, gst_val, unit_val, price, return_qty, createdate';
						$result_1 = $this->return_model->getOutletReturnDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($result_1)
						{
							$data_list = [];
							foreach ($result_1 as $key => $val_1) {

								$auto_id     = !empty($val_1->id)?$val_1->id:'';
							    $return_no   = !empty($val_1->return_no)?$val_1->return_no:'';
							    $invoice_id  = !empty($val_1->invoice_id)?$val_1->invoice_id:'';
							    $dis_value   = !empty($val_1->distributor_id)?$val_1->distributor_id:'';
							    $outlet_id   = !empty($val_1->outlet_id)?$val_1->outlet_id:'';
							    $type_id     = !empty($val_1->type_id)?$val_1->type_id:'';
							    $hsn_code    = !empty($val_1->hsn_code)?$val_1->hsn_code:'';
							    $gst_val     = !empty($val_1->gst_val)?$val_1->gst_val:'';
							    $unit_val    = !empty($val_1->unit_val)?$val_1->unit_val:'';
							    $price_val   = !empty($val_1->price)?$val_1->price:'';
							    $return_qty  = !empty($val_1->return_qty)?$val_1->return_qty:'';
							    $return_date = !empty($val_1->createdate)?$val_1->createdate:'';

							    // Invoice Details
					            $whr_2 = array('id' => $invoice_id);
					            $col_2 = 'invoice_no, date';
					            $res_2 = $this->invoice_model->getInvoice($whr_2, '', '', 'result', '', '', '', '', $col_2);

					            $inv_no   = !empty($res_2[0]->invoice_no)?$res_2[0]->invoice_no:'';
					            $inv_date = !empty($res_2[0]->date)?$res_2[0]->date:'';

					            // Distributor Details
					            $whr_3 = array('id' => $dis_value);
					            $col_3 = 'company_name, state_id, gst_no';
					            $res_3 = $this->distributors_model->getDistributors($whr_3, '', '', 'result', '', '', '', '', $col_3);

					            $dis_name   = !empty($res_3[0]->company_name)?$res_3[0]->company_name:'';
					            $state_id   = !empty($res_3[0]->state_id)?$res_3[0]->state_id:'';
					            $gst_number = !empty($res_3[0]->gst_no)?$res_3[0]->gst_no:'';

					            // Outlet Details
					            $whr_4 = array('id' => $outlet_id);
					            $col_4 = 'company_name, state_id, gst_no';
					            $res_4 = $this->outlets_model->getOutlets($whr_4, '', '', 'result', '', '', '', '', $col_4);

					            $str_name  = !empty($res_4[0]->company_name)?$res_4[0]->company_name:'';
					            $str_state = !empty($res_4[0]->state_id)?$res_4[0]->state_id:'';
					            $str_gst   = !empty($res_4[0]->gst_no)?$res_4[0]->gst_no:'';

					            // State Details
					            $whr_5 = array('id' => $str_state);
					            $col_5 = 'state_name';
					            $res_5 = $this->commom_model->getState($whr_5, '', '', 'result', '', '', '', '', $col_5);

					            $std_val = !empty($res_5[0]->state_name)?$res_5[0]->state_name:'';

					            // Product Type Details
					            $whr_6 = array('id' => $type_id);
					            $col_6 = 'description';
					            $res_6 = $this->commom_model->getProductType($whr_6, '', '', 'result', '', '', '', '', $col_6);

					            $pdt_desc = !empty($res_6[0]->description)?$res_6[0]->description:'';

					            $data_list[] = array(
					            	'return_no'   => $return_no,
					            	'return_date' => date('d-m-Y', strtotime($return_date)),
					            	'inv_no'      => $inv_no,
					            	'inv_date'    => date('d-m-Y', strtotime($inv_date)),
					            	'str_name'    => $str_name,
					            	'gst_number'  => $str_gst,
					            	'dis_state'   => $state_id,
					            	'state_id'    => $str_state,
					            	'str_state'   => $std_val,
					            	'pdt_desc'    => $pdt_desc,
					            	'hsn_code'    => $hsn_code,
					            	'return_qty'  => $return_qty,
					            	'gst_val'     => $gst_val,
					            	'price_val'   => $price_val,
					            );
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
					        $response['message'] = "Data Not Found"; 
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

		// Expense Report
		// ***************************************************
		public function expense_report($param1="",$param2="",$param3="")
		{
			$method      = $this->input->post('method');
			$start_date  = $this->input->post('start_date');
			$end_date    = $this->input->post('end_date');

			if($method == '_expenseReport')
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

			    		$where_1 = array(
							'expense_date >=' => $start_value,
							'expense_date <=' => $end_value,
							'published'       => '1',
						);

						$column_1 = 'employee_id, expense_id, expense_date, expense_val, expense_desc';

						$exp_details = $this->commom_model->getExpensesEntry($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($exp_details)
						{
							$exp_list = [];
							foreach ($exp_details as $key => $val_1) {
								$employee_id  = !empty($val_1->employee_id)?$val_1->employee_id:'';
								$expense_id   = !empty($val_1->expense_id)?$val_1->expense_id:'';
								$expense_date = !empty($val_1->expense_date)?$val_1->expense_date:'';
								$expense_val  = !empty($val_1->expense_val)?$val_1->expense_val:'0';
								$expense_desc = !empty($val_1->expense_desc)?$val_1->expense_desc:'';

								// Employee Details
								$emp_name = '';
								if(!empty($employee_id))
								{
									$where_2  = array('id' => $employee_id);
									$column_2 = 'first_name,last_name';
									$emp_data = $this->employee_model->getEmployee($where_2, '', '', 'result', '', '', '', '', $column_2);
									$last_name = !empty($emp_data[0]->last_name)?$emp_data[0]->last_name:'';
									$first_name = !empty($emp_data[0]->first_name)?$emp_data[0]->first_name:'';
									$arr = array($first_name,$last_name);
									$emp_name =join(" ",$arr);
								}

								// Expense Details
								$exp_name = '';
								if(!empty($expense_id))
								{
									$where_3  = array('id' => $expense_id);
									$column_3 = 'name';
									$exp_data = $this->commom_model->getExpenses($where_3, '', '', 'result', '', '', '', '', $column_3);
									$exp_name = !empty($exp_data[0]->name)?$exp_data[0]->name:'';
								}

								$exp_list[] = array(
									'employee_name' => $emp_name,
									'expense_name'  => $exp_name,
									'expense_date'  => date('d-M-Y', strtotime($expense_date)),
									'expense_val'   => $expense_val,
									'expense_desc'  => $expense_desc,
								);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $exp_list;
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

		// Distributor Stock Report
		// ***************************************************
		public function stock_entry_report($param1="",$param2="",$param3="")
		{
			$method         = $this->input->post('method');
			$distributor_id = $this->input->post('distributor_id');
			$vendor_id      = $this->input->post('vendor_id');
			$start_date     = $this->input->post('start_date');
			$end_date       = $this->input->post('end_date');

			if($method == '_distributorStockEntryReport')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id', 'start_date', 'end_date');
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

			    		$where_1 = array(
							'createdate >='  => $start_value,
							'createdate <='  => $end_value,
							'distributor_id' => $distributor_id,
							'published'      => '1',
						);

						$column_1 = 'description, stock_val, damage_val, expiry_val, date';

						$stk_details = $this->stock_model->getDistributorEntryDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($stk_details)
						{
							$stock_list = [];
							foreach ($stk_details as $key => $val) {
								$description = !empty($val->description)?$val->description:'';
								$stock_val   = !empty($val->stock_val)?$val->stock_val:'0';
								$damage_val  = !empty($val->damage_val)?$val->damage_val:'0';
								$expiry_val  = !empty($val->expiry_val)?$val->expiry_val:'0';
								$entry_date  = !empty($val->date)?$val->date:'';

								$stock_list[] = array(
									'description' => $description,
									'stock_val'   => $stock_val,
									'damage_val'  => $damage_val,
									'expiry_val'  => $expiry_val,
									'entry_date'  => date('d-M-Y', strtotime($entry_date)),
								);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $stock_list;
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

			else if($method == '_adminStockEntryReport')
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

			    		$where_1 = array(
							'createdate >='  => $start_value,
							'createdate <='  => $end_value,
							'published'      => '1',
						);

						$column_1 = 'description, stock_val, damage_val, expiry_val, date';

						$stk_details = $this->stock_model->getProductEntryDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($stk_details)
						{
							$stock_list = [];
							foreach ($stk_details as $key => $val) {
								$description = !empty($val->description)?$val->description:'';
								$stock_val   = !empty($val->stock_val)?$val->stock_val:'0';
								$damage_val  = !empty($val->damage_val)?$val->damage_val:'0';
								$expiry_val  = !empty($val->expiry_val)?$val->expiry_val:'0';
								$entry_date  = !empty($val->date)?$val->date:'';

								$stock_list[] = array(
									'description' => $description,
									'stock_val'   => $stock_val,
									'damage_val'  => $damage_val,
									'expiry_val'  => $expiry_val,
									'entry_date'  => date('d-M-Y', strtotime($entry_date)),
								);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $stock_list;
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

			else if($method == '_vendorStockEntryReport')
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

			    		$where_1 = array(
			    			'vendor_id'      => $vendor_id,
							'createdate >='  => $start_value,
							'createdate <='  => $end_value,
							'published'      => '1',
						);

						$column_1 = 'description, stock_val, damage_val, expiry_val, date';

						$stk_details = $this->stock_model->getVendorEntryDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($stk_details)
						{
							$stock_list = [];
							foreach ($stk_details as $key => $val) {
								$description = !empty($val->description)?$val->description:'';
								$stock_val   = !empty($val->stock_val)?$val->stock_val:'0';
								$damage_val  = !empty($val->damage_val)?$val->damage_val:'0';
								$expiry_val  = !empty($val->expiry_val)?$val->expiry_val:'0';
								$entry_date  = !empty($val->date)?$val->date:'';

								$stock_list[] = array(
									'description' => $description,
									'stock_val'   => $stock_val,
									'damage_val'  => $damage_val,
									'expiry_val'  => $expiry_val,
									'entry_date'  => date('d-M-Y', strtotime($entry_date)),
								);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $stock_list;
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

		// Distributor Stock Report
		// ***************************************************
		public function inventory_report($param1="",$param2="",$param3="")
		{
			$method         = $this->input->post('method');
			$start_date     = $this->input->post('start_date');
			$end_date       = $this->input->post('end_date');
			$distributor_id = $this->input->post('distributor_id');

			if($method == '_inventoryReport')
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
			    		$start_value = date('Y-m-d H:i:s', strtotime($start_date. '00:00:00'));
				    	$end_value   = date('Y-m-d H:i:s', strtotime($end_date. '23:59:59'));

			    		$where_1 = array(
							'createdate >='  => $start_value,
							'createdate <='  => $end_value,
							'distributor_id' => $distributor_id,
							'published'      => '1',
						);

						$column_1 = 'description, entry_qty, createdate';

						$inv_details = $this->purchase_model->getInventory($where_1, '', '', 'result', '', '', '', '', $column_1);

						if($inv_details)
						{
							$inv_list = [];
							foreach ($inv_details as $key => $val) {
								$description = !empty($val->description)?$val->description:'';
					            $entry_qty   = !empty($val->entry_qty)?$val->entry_qty:'0';
					            $createdate  = !empty($val->createdate)?$val->createdate:'';

					            $inv_list[] = array(
					            	'description' => $description,
						            'entry_qty'   => $entry_qty,
						            'createdate'  => date('d-M-Y', strtotime($createdate)),
					            );
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $inv_list;
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

		public function outlet_outstanding($param1="",$param2="",$param3="")
		{
			$method          = $this->input->post('method');
			$state_id        = $this->input->post('state_id');
			$city_id         = $this->input->post('city_id');
			$zone_id         = $this->input->post('zone_id');
			$distributor_id  = $this->input->post('distributor_id');

			if($method == '_getOutstandingData')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('state_id', 'distributor_id');
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
					$where_1 = array(
						'A.distributor_id' => $distributor_id,
						'B.state_id'       => $state_id,
						'A.published'      => '1',
					);

					if(!empty($city_id))
					{
						$where_1['B.city_id'] = $city_id;	
					}

					if(!empty($zone_id))
					{
						$where_1['B.zone_id'] = $zone_id;	
					}

					$column_1 = 'A.id, A.outlet_name, B.mobile, B.address, C.state_name, D.city_name, E.name AS zone_name, A.cur_bal';

					$str_list = $this->outlets_model->getDistributorOutletsJoin($where_1, '', '', 'result', '', '', '', '', $column_1);

					if($str_list)
					{
						$outlet_list = [];
						foreach ($str_list as $key => $val) {

						    $outlet_list[] = array(
				            	'outlet_id'   => !empty($val->id)?$val->id:'',
							    'outlet_name' => !empty($val->outlet_name)?$val->outlet_name:'',
							    'mobile'      => !empty($val->mobile)?$val->mobile:'',
							    'address'     => !empty($val->address)?$val->address:'',
							    'state_name'  => !empty($val->state_name)?$val->state_name:'',
							    'city_name'   => !empty($val->city_name)?$val->city_name:'',
							    'zone_name'   => !empty($val->zone_name)?$val->zone_name:'',
							    'cur_bal'     => !empty($val->cur_bal)?$val->cur_bal:'0',
				            );
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $outlet_list;
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
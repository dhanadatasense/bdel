<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Salesreturn extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('return_model');
			$this->load->model('commom_model');
			$this->load->model('outlets_model');
			$this->load->model('distributors_model');
			$this->load->model('invoice_model');
			$this->load->model('assignproduct_model');
			$this->load->model('payment_model');
			$this->load->model('user_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Add Outlet Return
		// ***************************************************
		public function outlet_return($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');



			

			if($method == '_addOutletReturn')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('outlet_id', 'return_details', 'invoice_id', 'return_value', 'active_financial');
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
			    	$outlet_id        = $this->input->post('outlet_id');
			    	$return_details   = $this->input->post('return_details');
			    	$invoice_id       = $this->input->post('invoice_id');
			    	$distributor_id   = $this->input->post('distributor_id');
			    	$return_value     = $this->input->post('return_value');
			    	$active_financial = $this->input->post('active_financial');
			    	$random_value     = generateRandomString(32);

					$wwhere_2 = array(
						'id' => $distributor_id,
					);

					$ccolumn_2 = 'ref_id';

					$value_2  = $this->distributors_model->getDistributors($wwhere_2, '', '', 'result', '', '', '', '', $ccolumn_2);

					$dist_val = $value_2[0];
					$ref_id = !empty($dist_val->ref_id) ? $dist_val->ref_id : '';



			    	$whr_1 = array(
			    		'distributor_id' => $distributor_id,
				    	'financial_year' => $active_financial,
				    );			   

			    	// Return Count
					$return_val = $this->return_model->getOutletReturn($whr_1,'','',"row",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

					$return_count = !empty($return_val->autoid)?$return_val->autoid:'0';
					$return_num   = 'RET'.leadingZeros($return_count, 6);

					// Assign Outlet Details
					$whr_2  = array(
						'distributor_id' => $distributor_id,
						'outlet_id'      => $outlet_id,
					);

					$col_2  = 'id, pre_bal, cur_bal';
					$data_2 = $this->distributors_model->getDistributorOutlet($whr_2, '', '', 'result', '', '', '', '', $col_2);

					$assign_id = !empty($data_2[0]->id)?$data_2[0]->id:'';
		            $pre_bal   = !empty($data_2[0]->pre_bal)?$data_2[0]->pre_bal:'';
		            $curr_bal  = !empty($data_2[0]->cur_bal)?$data_2[0]->cur_bal:'';

		            // Outlet Details
		            $whr_3  = array(
						'id'      => $outlet_id,
					);

					$col_3  = 'company_name, contact_name, zone_id, available_limit, current_balance';

					$data_3 = $this->outlets_model->getOutlets($whr_3, '', '', 'result', '', '', '', '', $col_3);

					$company_name  = !empty($data_3[0]->company_name)?$data_3[0]->company_name:'';
					$contact_name  = !empty($data_3[0]->contact_name)?$data_3[0]->contact_name:'';
					$zone_id       = !empty($data_3[0]->zone_id)?$data_3[0]->zone_id:'';
					$available_lmt = !empty($data_3[0]->available_limit)?$data_3[0]->available_limit:'';
					$current_bal   = !empty($data_3[0]->current_balance)?$data_3[0]->current_balance:'';

					// Invoice Details
				    $whr_4  = array(
	    				'id'        => $invoice_id,
	    				'status'    => '1',
	    				'published' => '1',
	    			);

				    $col_4   = 'id, bill_id, bill_no, pre_bal, cur_bal, amount, bal_amt';
	    			$data_4  = $this->payment_model->getOutletPaymentDetails($whr_4, '', '', 'result', '', '', '', '', $col_4);

	    			$pay_id  = !empty($data_4[0]->id)?$data_4[0]->id:'0';
		            $bill_id = !empty($data_4[0]->bill_id)?$data_4[0]->bill_id:'0';
		            $bill_no = !empty($data_4[0]->bill_no)?$data_4[0]->bill_no:'0';
		            $pre_bal = !empty($data_4[0]->pre_bal)?$data_4[0]->pre_bal:'0';
		            $cur_bal = !empty($data_4[0]->cur_bal)?$data_4[0]->cur_bal:'0';
		            $amount  = !empty($data_4[0]->amount)?$data_4[0]->amount:'0';
		            $bal_amt = !empty($data_4[0]->bal_amt)?$data_4[0]->bal_amt:'0';

		            // Get Invoice Details
		            $whr_11 = array(
		            	'order_id'       => $bill_id,
		            	'store_id'       => $outlet_id,
		            	'distributor_id' => $distributor_id,
		            );

		            $col_11 = 'id';
		            $res_11 = $this->invoice_model->getInvoice($whr_11, '', '', 'row', '', '', '', '', $col_11);

		            $inv_id = !empty($res_11->id)?$res_11->id:'0';

		            $data = array(
						'ref_id'         => $ref_id,
						'return_no'      => $return_num,
				    	'distributor_id' => $distributor_id,
				    	'invoice_id'     => $inv_id,
				    	'assign_id'      => $assign_id,
				    	'store_id'       => $outlet_id,
				    	'store_name'     => $company_name,
				    	'contact_name'   => $contact_name,
				    	'zone_id'        => $zone_id,
				    	'financial_year' => $active_financial,
				    	'return_details' => $return_details,
				    	'random_value'   => $random_value,
				    	'date'           => date('Y-m-d'),
				    	'time'           => date('H:i:s'),
				    	'createdate'     => date('Y-m-d H:i:s')
				    );

				    $product_value = json_decode($return_value);	

				    $pdt_val = 0;
				    foreach ($product_value as $key => $val) {
					    $pdt_price = !empty($val->product_price)?$val->product_price:'0';
					    $pdt_qty   = !empty($val->product_qty)?$val->product_qty:'0';
					    $pdt_total = $pdt_price * $pdt_qty;
					    $pdt_val  += $pdt_total;
				    }

				    $total_val = round($pdt_val);

				    // if($bal_amt >= $total_val)
				    // {
				    	$insert = $this->return_model->outletReturn_insert($data);

				    	$total_value = 0;
	    				foreach ($product_value as $key => $val_5) {

	    					$category_id   = !empty($val_5->category_id)?$val_5->category_id:'';
						    $type_id       = !empty($val_5->type_id)?$val_5->type_id:'';
						    $product_price = !empty($val_5->product_price)?$val_5->product_price:'0';
						    $product_qty   = !empty($val_5->product_qty)?$val_5->product_qty:'0';
						    $product_unit  = !empty($val_5->product_unit)?$val_5->product_unit:'';

						    // Assign Product Details
						    $whr_6  = array(
						    	'id' => $type_id
						    );

						    $col_6  = 'product_id, type_id, description, stock, view_stock';

						    $data_6 = $this->assignproduct_model->getAssignProductDetails($whr_6, '', '', 'result', '', '', '', '', $col_6);

						    $res_6  = $data_6[0];

						    $product_id   = !empty($res_6->product_id)?$res_6->product_id:'';
						    $product_type = !empty($res_6->type_id)?$res_6->type_id:'';
						    $description  = !empty($res_6->description)?$res_6->description:'';
							$stock        = !empty($res_6->stock)?$res_6->stock:'0';
							$view_stock   = !empty($res_6->view_stock)?$res_6->view_stock:'0';

							// Product Details
							$whr_7 = array(
								'id' => $product_id
							);

							$col_7  = 'hsn_code, gst';

							$data_7 = $this->commom_model->getProduct($whr_7, '', '', 'result', '', '', '', '', $col_7);

							$res_7  = $data_7[0];

							$hsn_code = !empty($res_7->hsn_code)?$res_7->hsn_code:'';
							$gst_val  = !empty($res_7->gst)?$res_7->gst:'';

							// Sales Return Details
							$whr_8  = array(
								'return_id' => $insert,
								'assign_id' => $type_id,
								'published' => '1',
							);

							$col_8  = 'id';
							$data_8 = $this->return_model->getOutletReturnDetails($whr_8, '', '', 'result', '', '', '', '', $col_8);

							if(empty($data_8))
							{
								// Price Value
								$price_value  = $product_price * $product_qty;
								$total_value += $price_value;

								$data_val = array(
									'ref_id'         => $ref_id,
									'return_id'      => $insert,
									'return_no'      => $return_num,
									'distributor_id' => $distributor_id,
									'invoice_id'     => $inv_id,
									'outlet_id'      => $outlet_id,
									'assign_id'      => $type_id,
									'category_id'    => $category_id,
									'product_id'     => $product_id,
									'type_id'        => $product_type,
									'hsn_code'       => $hsn_code,
									'gst_val'        => $gst_val,
									'unit_val'       => $product_unit,
									'price'          => $product_price,
									'return_qty'     => $product_qty,
									'createdate'     => date('Y-m-d H:i:s')
								);

								$insert_val = $this->return_model->outletReturnDetails_insert($data_val);
							}
	    				}

	    				$new_total = round($total_value);

	    				// Outlet Balance Details
	    				$new_available = $available_lmt + $new_total;
	    				$new_credit    = $current_bal - $new_total;

	    				$val_9 = array(
	    					'available_limit' => $new_available,
	    					'current_balance' => $new_credit,
	    				);

	    				$whr_9 = array('id' => $outlet_id);
	    				$upt_9 = $this->outlets_model->outlets_update($val_9, $whr_9);

	    				// Distributor Outlet Balance Details
	    				$new_pre_bal = $curr_bal;
	    				$new_cur_bal = $curr_bal - $new_total;

	    				$val_10 = array(
	    					'pre_bal' => $new_pre_bal,
	    					'cur_bal' => $new_cur_bal,
	    				);

	    				$whr_10 = array('id' => $assign_id);
	    				$upt_10 = $this->distributors_model->distributorOutlet_update($val_10, $whr_10);

	    				// Balance Sheet
	    				$bill_balance = $cur_bal - $total_val;
					    $new_bal_amt  = $bal_amt - $total_val;

	    				$payment_data = array(
				    		'pre_bal'    => $cur_bal,
				    		'cur_bal'    => $bill_balance,
				    		'bal_amt'    => $new_bal_amt,
				    		'updatedate' => date('Y-m-d H:i:s'),
				    	);

				    	$payment_whr = array('id' => $invoice_id);
				    	$payment_upt = $this->payment_model->outletPaymentDetails_update($payment_data, $payment_whr);

				    	$master_where = array(
				    		'distributor_id' => $distributor_id,
				    		'bill_code'      => 'PAY',
							'published'      => '1',
							'status'         => '1',
						);

						$bill_val = $this->payment_model->getOutletPayment($master_where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

						$count_val = leadingZeros($bill_val[0]->autoid, 5);
	            		$bill_num  = 'PAY'.$count_val;

	            		$ins_data = array(
							'ref_id'         => $ref_id,
	            			'assign_id'       => $assign_id,
	    					'payment_id'      => $invoice_id,
	    					'distributor_id'  => $distributor_id,
	    					'outlet_id'       => $outlet_id,
	    					'bill_code'       => 'PAY',
	    					'bill_no'         => $bill_num,
	    					'available_limit' => $available_lmt,
	    					'pre_bal'         => $current_bal,
	    					'cur_bal'         => $new_cur_bal,
	    					'amount'          => round($new_total),
	    					'pay_type'        => 1,
	    					'description'     => $return_details,
	    					'amt_type'        => 4,
	    					'collection_type' => 2,
	    					'value_type'      => 2,
	    					'date'            => date('Y-m-d'),
	    					'time'            => date('H:i:s'),
	    					'createdate'      => date('Y-m-d H:i:s'),
	            		);

	            		$payment_insert = $this->payment_model->outletPayment_insert($ins_data);

	            		// Pre Payment Update
				    	$prePayment_data = array(
				    		'status' => '0'
				    	);

				    	$prePayment_whr  = array(
				    		'id !='          => $payment_insert,
				    		'assign_id'      => $assign_id,
				    		'distributor_id' => $distributor_id,
				    		'outlet_id'      => $outlet_id,
				    	);

				    	$payment_update = $this->payment_model->outletPayment_update($prePayment_data, $prePayment_whr);

	    				if($insert)	
	    				{
	    					$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = [];
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
				    // }
				    // else
	    			// {
	    			// 	$response['status']  = 0;
				    //     $response['message'] = "Invalid Value"; 
				    //     $response['data']    = [];
				    //     echo json_encode($response);
				    //     return; 			
	    			// }
			    }
			}
			// else if($method == '_addOutletReturn'){
			// 	$wheree_1 =array(
			// 		'distributor_id'  => $dis_id,
			// 	);
			// 	$columm_1 = 'id, return_no, invoice_no, invoice_id, distributor_id, distributor_id, store_id, return_details, date';
 
			// 	$data_1   = $this->invoice_model->getDistributorInvoice($wheree_1, '', '', 'result', '', '', '', '', $columm_1);


			// 	if($data_1)
			// 	{
			// 		$return_list = [];
			// 		foreach ($data_list as $key => $val) {
			// 			$return_no    = !empty($val->return_no)?$val->return_no:'';
			// 			$store_name   = !empty($val->store_name)?$val->store_name:'';
			// 			$random_value = !empty($val->random_value)?$val->random_value:'';
			// 			$date         = !empty($val->date)?$val->date:'';

			// 			$return_list[] = array(
			// 				'return_no'    => $return_no,
			// 				'store_name'   => $store_name,
			// 				'random_value' => $random_value,
			// 				'date'         => date('d-M-Y', strtotime($date)),
			// 			);
			// 		}

			// 		if($offset !='' && $limit !='') {
			// 			$offset = $offset + $limit;
			// 			$limit  = $limit;
			// 		} 
			// 		else {
			// 			$offset = $limit;
			// 			$limit  = 10;
			// 		}

			// 		$response['status']       = 1;
			//         $response['message']      = "Success"; 
			//         $response['total_record'] = $totalc;
			//         $response['offset']       = (int)$offset;
		    // 		$response['limit']        = (int)$limit;
			//         $response['data']         = $return_list;
		    // 		echo json_encode($response);
			//         return;
			// 	}
			// 	else
			// 	{
			// 		$response['status']  = 0;
			//         $response['message'] = "Not Found"; 
			//         $response['data']    = [];
			//         echo json_encode($response);
			//         return;
			// 	}
            // }
			else if($method == '_manageOutletReturnPagination')
			{
				$limit          = $this->input->post('limit');
	    		$offset         = $this->input->post('offset');
	    		$distributor_id = $this->input->post('distributor_id');
	    		$financial_id   = $this->input->post('financial_id');

	    		if($limit !='' && $offset !='')
				{
					$limit  = $limit;
					$offset = $offset;
				}
				else
				{
					$limit  = 10;
					$offset = 0;
				}

				$search = $this->input->post('search');
	    		if($search !='')
	    		{
	    			$like['name'] = $search;
	    		}
	    		else
	    		{
	    			$like = [];
	    		}

	    		$where = array(
	    			'distributor_id' => $distributor_id,
	    			// 'financial_year' => $financial_id,
    				'published'      => '1'
    			);

	    		$column = 'id';
				$overalldatas = $this->return_model->getOutletReturn($where, '', '', 'result', $like, '', '', '', $column);

				if($overalldatas)
				{
					$totalc = count($overalldatas);
				}
				else
				{
					$totalc = 0;
				}

				$option['order_by']   = 'id';
				$option['disp_order'] = 'DESC';

				$column_2  = 'return_no, store_name, random_value, date';
				$data_list = $this->return_model->getOutletReturn($where, $limit, $offset, 'result', $like, '', $option, '', $column_2);

				if($data_list)
				{
					$return_list = [];
					foreach ($data_list as $key => $val) {
						$return_no    = !empty($val->return_no)?$val->return_no:'';
						$store_name   = !empty($val->store_name)?$val->store_name:'';
						$random_value = !empty($val->random_value)?$val->random_value:'';
						$date         = !empty($val->date)?$val->date:'';

						$return_list[] = array(
							'return_no'    => $return_no,
							'store_name'   => $store_name,
							'random_value' => $random_value,
							'date'         => date('d-M-Y', strtotime($date)),
						);
					}

					if($offset !='' && $limit !='') {
						$offset = $offset + $limit;
						$limit  = $limit;
					} 
					else {
						$offset = $limit;
						$limit  = 10;
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['total_record'] = $totalc;
			        $response['offset']       = (int)$offset;
		    		$response['limit']        = (int)$limit;
			        $response['data']         = $return_list;
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

			else if($method == '_outletReturnDetails')
			{
				$return_value = $this->input->post('return_value');

				if($return_value)
				{
					$whr_1  = array(
						'random_value' => $return_value
					);

					$col_1  = 'id, return_no, random_value, invoice_id, distributor_id, distributor_id, store_id, return_details, date';

					$data_1 = $this->return_model->getOutletReturn($whr_1, '', '', 'result', '', '', '', '', $col_1);

					$res_1  = $data_1[0];

					if($data_1)
					{		
						$return_id      = !empty($res_1->id)?$res_1->id:'';
						$return_no      = !empty($res_1->return_no)?$res_1->return_no:'';
			            $random_value   = !empty($res_1->random_value)?$res_1->random_value:'';
			            $invoice_id     = !empty($res_1->invoice_id)?$res_1->invoice_id:'';
			            $distributor_id = !empty($res_1->distributor_id)?$res_1->distributor_id:'';
			            $store_id       = !empty($res_1->store_id)?$res_1->store_id:'';
			            $return_value   = !empty($res_1->random_value)?$res_1->random_value:'';
			            $return_date    = !empty($res_1->date)?$res_1->date:'';

						// Distributor Details
						$whr_2  = array('id' => $distributor_id);
						$col_2  = 'company_name, contact_name, mobile, email, state_id, gst_no, address';

						$data_2 = $this->distributors_model->getDistributors($whr_2, '', '', 'result', '', '', '', '', $col_2);

						$company_name = !empty($data_2[0]->company_name)?$data_2[0]->company_name:'';
			            $dis_mobile   = !empty($data_2[0]->mobile)?$data_2[0]->mobile:'';
			            $dis_email    = !empty($data_2[0]->email)?$data_2[0]->email:'';
			            $dis_state_id = !empty($data_2[0]->state_id)?$data_2[0]->state_id:'';
			            $dis_gst_no   = !empty($data_2[0]->gst_no)?$data_2[0]->gst_no:'';
			            $dis_address  = !empty($data_2[0]->address)?$data_2[0]->address:'';

			            $whr_8  = array('id' => $invoice_id);
			            $col_8  = 'invoice_no, date';
			            $data_8 = $this->invoice_model->getInvoice($whr_8, '', '', 'result', '', '', '', '', $col_8);
			            $res_8  = $data_8[0];

			            $invoice_no   = !empty($res_8->invoice_no)?$res_8->invoice_no:'';
			            $invoice_date = !empty($res_8->date)?$res_8->date:'';

			            $distributor_det = array(
			            	'return_no'    => $return_no,
			            	'return_date'  => $return_date,
			            	'return_value' => $return_value,
			            	'company_name' => $company_name,
				            'dis_mobile'   => $dis_mobile,
				            'dis_email'    => $dis_email,
				            'dis_state_id' => $dis_state_id,
				            'dis_gst_no'   => $dis_gst_no,
				            'dis_address'  => $dis_address,
				            'invoice_no'   => $invoice_no,
				            'invoice_date' => $invoice_date,
			            );

			            // Outlet Details
			            $whr_3  = array('id' => $store_id);
			            $col_3  = 'company_name, contact_name, mobile, email, state_id, gst_no, address';

			            $data_3 = $this->outlets_model->getOutlets($whr_3, '', '', 'result', '', '', '', '', $col_3);

			            $res_3  = $data_3[0];

						$store_name   = !empty($res_3->company_name)?$res_3->company_name:'';
			            $str_mobile   = !empty($res_3->mobile)?$res_3->mobile:'';
			            $str_email    = !empty($res_3->email)?$res_3->email:'';
			            $str_state_id = !empty($res_3->state_id)?$res_3->state_id:'';
			            $str_gst_no   = !empty($res_3->gst_no)?$res_3->gst_no:'';
			            $str_address  = !empty($res_3->address)?$res_3->address:'';

			            $store_det = array(
			            	'store_name'   => $store_name,
				            'str_mobile'   => $str_mobile,
				            'str_email'    => $str_email,
				            'str_state_id' => $str_state_id,
				            'str_gst_no'   => $str_gst_no,
				            'str_address'  => $str_address,
			            );

			            // Product Details
			            $whr_4  = array(
			            	'return_id' => $return_id,
			            	'published' => '1',
			            );

			            $col_4  = 'type_id, hsn_code, gst_val, price, return_qty';

			            $data_4 = $this->return_model->getOutletReturnDetails($whr_4, '', '', 'result', '', '', '', '', $col_4);

			            $product_details = [];
			            if(!empty($data_4))
			            {
			            	foreach ($data_4 as $key => $val_4) {
				            		
				            	$type_id    = !empty($val_4->type_id)?$val_4->type_id:'';
								$hsn_code   = !empty($val_4->hsn_code)?$val_4->hsn_code:'';
								$gst_val    = !empty($val_4->gst_val)?$val_4->gst_val:'0';
								$price      = !empty($val_4->price)?$val_4->price:'0';
								$return_qty = !empty($val_4->return_qty)?$val_4->return_qty:'0';

								// Product Type Details
								$whr_5  = array('id' => $type_id);
								$col_5  = 'description';

								$data_5 = $this->commom_model->getProductType($whr_5, '', '', 'result', '', '', '', '', $col_5);
								$res_5  = $data_5[0];
								$desc   = !empty($res_5->description)?$res_5->description:'';

								$product_details[] = array(
									'description' => $desc,
									'hsn_code'    => $hsn_code,
									'gst_val'     => $gst_val,
									'price'       => $price,
									'return_qty'  => $return_qty,
								);
				            }
			            }

			            // Tax Details
			            $whr_6 = array(
	        				'return_id' => $return_id,
	        				'published' => '1',
	        			);

	        			$col_6   = 'hsn_code, gst_val';
	        			$groupby = 'hsn_code';
	        			$data_6  = $this->return_model->getOutletReturnDetails($whr_6, '', '', 'result', '', '', '', '', $col_6, $groupby);

	        			$tax_details = [];
	        			if(!empty($data_6))
	        			{
	        				foreach ($data_6 as $key => $val_6) {
	        					$hsn_code = !empty($val_6->hsn_code)?$val_6->hsn_code:'';
	        					$gst_val  = !empty($val_6->gst_val)?$val_6->gst_val:'0';

	        					// Price Details
	        					$whr_7  = array(
			        				'return_id' => $return_id,
			        				'hsn_code'  => $hsn_code,
			        				'published' => '1',
			        			);

			        			$col_7  = 'price, return_qty, gst_val';

			        			$data_7 = $this->return_model->getOutletReturnDetails($whr_7, '', '', 'result', '', '', '', '', $col_7);

			        			$product_price = 0;
			        			$gst_price     = 0;
			        			foreach ($data_7 as $key => $val_7) {
			        				$price      = !empty($val_7->price)?$val_7->price:'0';
			        				$return_qty = !empty($val_7->return_qty)?$val_7->return_qty:'0';
			        				$gst_val    = !empty($val_7->gst_val)?$val_7->gst_val:'0';

			        				$gst_data    = $price - ($price * (100 / (100 + $gst_val)));
                            		$price_val   = $price - $gst_data;
                            		$total_price = $return_qty * $price_val;
                            		$total_gst   = $return_qty * $gst_data;

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

	        			$return_details = array(
			            	'distributor_details' => $distributor_det,
			            	'store_details'       => $store_det,
			            	'product_details'     => $product_details,
			            	'tax_details'         => $tax_details,
			            );

			            $response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $return_details;
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
			        $response['message'] = "Please fill all required fields"; 
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

		// Add Distributor Return
		// ***************************************************
		public function distributor_return($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addDistributorReturn')
			{
				$distributor_id   = $this->input->post('distributor_id');
				$ref_id           = $this->input->post('ref_id');
			    $invoice_id       = $this->input->post('invoice_id');
			    $return_details   = $this->input->post('return_details');
			    $return_value     = $this->input->post('return_value');
			    $active_financial = $this->input->post('active_financial');
			    $random_value     = generateRandomString(32);

			    $error = FALSE;
			    $errors = array();
				$required = array('distributor_id', 'invoice_id', 'return_details', 'return_value', 'active_financial');
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
			    	$whr_1 = array(
				    	'financial_year' => $active_financial,
				    );			   

			    	// Return Count
					$return_val = $this->return_model->getDistributorReturn($whr_1,'','',"row",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

					$return_count = !empty($return_val->autoid)?$return_val->autoid:'0';
					$return_num   = 'RET'.leadingZeros($return_count, 6);

					// Distributor Details
					$whr_1  = array('id' => $distributor_id);
					$col_1  = 'company_name, available_limit, current_balance';
					$data_1 = $this->distributors_model->getDistributors($whr_1, '', '', 'row', '', '', '', '', $col_1);

					$com_name  = !empty($data_1->company_name)?$data_1->company_name:'';
		            $avl_limit = !empty($data_1->available_limit)?$data_1->available_limit:'';
		            $curr_bal  = !empty($data_1->current_balance)?$data_1->current_balance:'';

		            // Invoice Details
		            $whr_2   = array('id' => $invoice_id, 'distributor_id' => $distributor_id);
		            $col_2   = 'id, bill_id, bill_no, pre_bal, cur_bal, amount, bal_amt';
		            $data_2  = $this->payment_model->getDistributorPaymentDetails($whr_2, '', '', 'row', '', '', '', '', $col_2);

		            $pay_id  = !empty($data_2->id)?$data_2->id:'0';
		            $bill_id = !empty($data_2->bill_id)?$data_2->bill_id:'0';
		            $bill_no = !empty($data_2->bill_no)?$data_2->bill_no:'0';
		            $pre_bal = !empty($data_2->pre_bal)?$data_2->pre_bal:'0';
		            $cur_bal = !empty($data_2->cur_bal)?$data_2->cur_bal:'0';
		            $amount  = !empty($data_2->amount)?$data_2->amount:'0';
		            $bal_amt = !empty($data_2->bal_amt)?$data_2->bal_amt:'0';

		            // Get Invoice Details
		            $whr_8 = array(
		            	'order_id'       => $bill_id,
		            	'distributor_id' => $distributor_id,
		            );

		            $col_8 = 'id';
		            $res_8 = $this->invoice_model->getDistributorInvoice($whr_8, '', '', 'row', '', '', '', '', $col_8);

		            $inv_id = !empty($res_8->id)?$res_8->id:'0';

		            $product_value = json_decode($return_value);	

		            $pdt_val = 0;
				    foreach ($product_value as $key => $val) {
				    	$pdt_price = !empty($val->product_price)?$val->product_price:'0';
					    $pdt_qty   = !empty($val->product_qty)?$val->product_qty:'0';
					    $pdt_total = $pdt_price * $pdt_qty;
					    $pdt_val  += $pdt_total;
				    }

				    $total_val = round($pdt_val);

				    // Distributor Return Insert
				    $data = array(
						"ref_id"            => $ref_id,
						'return_no'        => $return_num,
				    	'distributor_id'   => $distributor_id,
				    	'invoice_id'       => $inv_id, 
				    	'distributor_name' => $com_name,
				    	'financial_year'   => $active_financial,
				    	'return_details'   => $return_details,
				    	'random_value'     => $random_value,
				    	'date'             => date('Y-m-d'),
				    	'time'             => date('H:i:s'),
				    	'createdate'       => date('Y-m-d H:i:s')
				    );

				    // if($bal_amt >= $total_val)
				    // {
				    	$insert = $this->return_model->distributorReturn_insert($data);

				    	$total_value = 0;
	    				foreach ($product_value as $key => $val_3) {

	    					$assign_id     = !empty($val_3->product_id)?$val_3->product_id:'';
						    $product_price = !empty($val_3->product_price)?$val_3->product_price:'0';
						    $product_qty   = !empty($val_3->product_qty)?$val_3->product_qty:'0';
						    $product_unit  = !empty($val_3->product_unit)?$val_3->product_unit:'';

							// Price Value
						    $whr_4  = array('id' => $assign_id);
						    $col_4  = 'category_id, product_id, type_id';
						    $data_4 = $this->assignproduct_model->getAssignProductDetails($whr_4, '', '', 'result', '', '', '', '', $col_4);

						    $category_id = !empty($data_4[0]->category_id)?$data_4[0]->category_id:'';
							$product_id  = !empty($data_4[0]->product_id)?$data_4[0]->product_id:'';
							$type_id     = !empty($data_4[0]->type_id)?$data_4[0]->type_id:'';

							// Product Details
							$whr_5  = array('id' => $product_id);
						    $col_5  = 'hsn_code, gst';
						    $data_5 = $this->commom_model->getProduct($whr_5, '', '', 'result', '', '', '', '', '');

						    $hsn_code = !empty($data_5[0]->hsn_code)?$data_5[0]->hsn_code:'';
							$gst_val  = !empty($data_5[0]->gst)?$data_5[0]->gst:'';

							// Sales Return Details
							$whr_6  = array('return_id' => '', 'assign_id' => $assign_id, 'published' => '1');
							$col_6  = 'id';
							$data_6 = $this->return_model->getDistributorReturnDetails($whr_6, '', '', 'result', '', '', '', '', $col_6);

							if(empty($data_8))
							{
								$price_value  = $product_price * $product_qty;
								$total_value += $price_value;

								$data_val = array(
									"ref_id"            => $ref_id,
									'return_id'      => $insert,
									'return_no'      => $return_num,
									'invoice_id'     => $inv_id,
									'distributor_id' => $distributor_id,
									'assign_id'      => $assign_id,
									'category_id'    => $category_id,
									'product_id'     => $product_id,
									'type_id'        => $type_id,
									'hsn_code'       => $hsn_code,
									'gst_val'        => $gst_val,
									'unit_val'       => $product_unit,
									'price'          => $product_price,
									'return_qty'     => $product_qty,
									'createdate'     => date('Y-m-d H:i:s')
								);

								$insert_val = $this->return_model->distributorReturnDetails_insert($data_val);
							}
	    				}

	    				$new_total = round($total_value);

	    				// Outlet Balance Details
	    				$new_available = $avl_limit + $new_total;
	    				$new_credit    = $curr_bal - $new_total;

	    				$val_7 = array(
	    					'available_limit' => $new_available,
	    					'current_balance' => $new_credit,
	    				);

	    				$whr_7 = array('id' => $distributor_id);
	    				$upt_7 = $this->distributors_model->distributors_update($val_7, $whr_7);

	    				// Balance Sheet
	    				$bill_balance = $cur_bal - $total_val;
					    $new_bal_amt  = $bal_amt - $total_val;

	    				$payment_data = array(
				    		'pre_bal'    => $cur_bal,
				    		'cur_bal'    => $bill_balance,
				    		'bal_amt'    => $new_bal_amt,
				    		'updatedate' => date('Y-m-d H:i:s'),
				    	);

				    	$payment_whr = array('id' => $invoice_id);
				    	$payment_upt = $this->payment_model->distributorPaymentDetails_update($payment_data, $payment_whr);

	    				$master_where = array(
				    		'bill_code'    => 'REC',
							'published'    => '1',
							'financial_id' => $active_financial,
						);

						$bill_val = $this->payment_model->getDistributorPayment($master_where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

						$count_val = leadingZeros($bill_val[0]->autoid, 5);
	            		$bill_num  = 'REC'.$count_val;

	            		// Payment Insert
				    	$ins_data = array(
				    		'distributor_id'  => $distributor_id,
				    		'payment_id'      => $pay_id,
				    		'bill_code'       => 'REC',
				    		'bill_id'         => $bill_id,
				    		'bill_no'         => $bill_num,
				    		'pre_bal'         => $curr_bal,
				    		'cur_bal'         => $new_credit,
				    		'amount'          => round($new_total),
				    		'pay_type'        => 1,
				    		'description'     => $return_details,
				    		'amt_type'        => 4,
				    		'collection_type' => 2,
				    		'cheque_process'  => 2,
				    		'value_type'      => 2,
				    		'status'          => 0,
				    		'financial_id'    => $active_financial,
				    		'date'            => date('Y-m-d'),
	    					'time'            => date('H:i:s'),
	    					'createdate'      => date('Y-m-d H:i:s'),
				    	);

				    	$payment_insert = $this->payment_model->distributorPayment_insert($ins_data);

				    	// Pre Payment Update
				    	$prePayment_data = array(
				    		'status' => '0'
				    	);

				    	$prePayment_whr  = array(
				    		'id !='          => $payment_insert,
				    		'distributor_id' => $distributor_id,
				    	);

				    	$payment_update = $this->payment_model->distributorPayment_update($prePayment_data, $prePayment_whr);

	    				if($insert)	
	    				{
	    					$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = [];
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
				    // }
				    // else
	    			// {
	    			// 	$response['status']  = 0;
				    //     $response['message'] = "Invalid Value"; 
				    //     $response['data']    = [];
				    //     echo json_encode($response);
				    //     return; 			
	    			// }
			    }
			}

			else if($method == '_manageDistributorReturnPagination')
			{
				$limit        = $this->input->post('limit');
	    		$offset       = $this->input->post('offset');
	    		$financial_id = $this->input->post('financial_id');
				$ref_id       = $this->input->post('ref_id');

	    		if($limit !='' && $offset !='')
				{
					$limit  = $limit;
					$offset = $offset;
				}
				else
				{
					$limit  = 10;
					$offset = 0;
				}

				$search = $this->input->post('search');
	    		if($search !='')
	    		{
	    			$like['name'] = $search;
	    		}
	    		else
	    		{
	    			$like = [];
	    		}

	    		$where = array(
					'ref_id'        => $ref_id,
	    			// 'financial_year' => $financial_id,
    				'published'      => '1'
    			);

	    		$column = 'id';
				$overalldatas = $this->return_model->getDistributorReturn($where, '', '', 'result', $like, '', '', '', $column);

				if($overalldatas)
				{
					$totalc = count($overalldatas);
				}
				else
				{
					$totalc = 0;
				}

				$option['order_by']   = 'id';
				$option['disp_order'] = 'DESC';

				$column_2  = 'return_no, distributor_name, random_value, date';
				$data_list = $this->return_model->getDistributorReturn($where, $limit, $offset, 'result', $like, '', $option, '', $column_2);

				if($data_list)
				{
					$return_list = [];
					foreach ($data_list as $key => $val) {
						$return_no    = !empty($val->return_no)?$val->return_no:'';
						$distri_name  = !empty($val->distributor_name)?$val->distributor_name:'';
						$random_value = !empty($val->random_value)?$val->random_value:'';
						$date         = !empty($val->date)?$val->date:'';

						$return_list[] = array(
							'return_no'        => $return_no,
							'distributor_name' => $distri_name,
							'random_value'     => $random_value,
							'date'             => date('d-M-Y', strtotime($date)),
						);
					}

					if($offset !='' && $limit !='') {
						$offset = $offset + $limit;
						$limit  = $limit;
					} 
					else {
						$offset = $limit;
						$limit  = 10;
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['total_record'] = $totalc;
			        $response['offset']       = (int)$offset;
		    		$response['limit']        = (int)$limit;
			        $response['data']         = $return_list;
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

			else if($method == '_distributorReturnDetails')
			{
				$return_value = $this->input->post('return_value');
				$ref_id = $this->input->post('ref_id');
				
				if($return_value)
				{
					$whr_1  = array(
						'random_value' => $return_value
					);

					$col_1  = 'id, return_no, random_value, invoice_id, distributor_id, distributor_name, return_details, date';

					$res_1 = $this->return_model->getDistributorReturn($whr_1, '', '', 'row', '', '', '', '', $col_1);

					if($res_1)
					{
						$return_id        = !empty($res_1->id)?$res_1->id:'';
						$return_no        = !empty($res_1->return_no)?$res_1->return_no:'';
			            $random_value     = !empty($res_1->random_value)?$res_1->random_value:'';
			            $invoice_id       = !empty($res_1->invoice_id)?$res_1->invoice_id:'';
			            $distributor_id   = !empty($res_1->distributor_id)?$res_1->distributor_id:'';
			            $distributor_name = !empty($res_1->distributor_name)?$res_1->distributor_name:'';
			            $return_value     = !empty($res_1->random_value)?$res_1->random_value:'';
			            $return_date      = !empty($res_1->date)?$res_1->date:'';

						// Distributor Details
						$whr_2 = array('id' => $distributor_id);
						$col_2 = 'company_name, contact_name, mobile, email, state_id, gst_no, address';

						$res_2 = $this->distributors_model->getDistributors($whr_2, '', '', 'row', '', '', '', '', $col_2);

						$company_name = !empty($res_2->company_name)?$res_2->company_name:'';
			            $dis_mobile   = !empty($res_2->mobile)?$res_2->mobile:'';
			            $dis_email    = !empty($res_2->email)?$res_2->email:'';
			            $dis_state_id = !empty($res_2->state_id)?$res_2->state_id:'';
			            $dis_gst_no   = !empty($res_2->gst_no)?$res_2->gst_no:'';
			            $dis_address  = !empty($res_2->address)?$res_2->address:'';

			            $whr_8  = array('id' => $invoice_id);
			            $col_8  = 'invoice_no, date';
			            $data_8 = $this->invoice_model->getDistributorInvoice($whr_8, '', '', 'result', '', '', '', '', $col_8);

			            $res_8  = $data_8[0];

			            $invoice_no   = !empty($res_8->invoice_no)?$res_8->invoice_no:'';
			            $invoice_date = !empty($res_8->date)?$res_8->date:'';

			            $distributor_det = array(
			            	'return_no'    => $return_no,
			            	'return_date'  => $return_date,
			            	'return_value' => $return_value,
			            	'company_name' => $company_name,
				            'dis_mobile'   => $dis_mobile,
				            'dis_email'    => $dis_email,
				            'dis_state_id' => $dis_state_id,
				            'dis_gst_no'   => $dis_gst_no,
				            'dis_address'  => $dis_address,
				            'invoice_no'   => $invoice_no,
				            'invoice_date' => $invoice_date,
			            );
						// Admin Details
						if($ref_id==0){

							$adm_whr  = array('id' => '1');
							$adm_col  = 'username, mobile, address, gst_no, state_id';
							$adm_data = $this->user_model->getUser($adm_whr, '', '', 'result', '', '', '', '', $adm_col);
	
							$adm_username = !empty($adm_data[0]->username)?$adm_data[0]->username:'';
							$adm_mobile   = !empty($adm_data[0]->mobile)?$adm_data[0]->mobile:'';
							$adm_address  = !empty($adm_data[0]->address)?$adm_data[0]->address:'';
							$adm_gst_no   = !empty($adm_data[0]->gst_no)?$adm_data[0]->gst_no:'';
							$adm_state_id = !empty($adm_data[0]->state_id)?$adm_data[0]->state_id:'';
	
						}else{
							$whr_2 = array('id' => $ref_id);
							$col_2 = 'company_name, contact_name, mobile, email, state_id, gst_no, address';
	
							$res_2 = $this->distributors_model->getDistributors($whr_2, '', '', 'row', '', '', '', '', $col_2);
	
							$adm_username = !empty($res_2->company_name)?$res_2->company_name:'';
							$adm_mobile   = !empty($res_2->mobile)?$res_2->mobile:'';
							$adm_state_id = !empty($res_2->state_id)?$res_2->state_id:'';
							$adm_gst_no   = !empty($res_2->gst_no)?$res_2->gst_no:'';
							$adm_address  = !empty($res_2->address)?$res_2->address:'';	
						}
			            
			            

			            $admin_det = array(
			            	'adm_username' => $adm_username,
				            'adm_mobile'   => $adm_mobile,
				            'adm_address'  => $adm_address,
				            'adm_gst_no'   => $adm_gst_no,
				            'adm_state_id' => $adm_state_id,
			            );

			            // Product Details
			            $whr_4  = array(
			            	'return_id' => $return_id,
			            	'published' => '1',
			            );

			            $col_4  = 'type_id, hsn_code, gst_val, price, return_qty';

			            $data_4 = $this->return_model->getDistributorReturnDetails($whr_4, '', '', 'result', '', '', '', '', $col_4);
					
			            $product_details = [];
			            if(!empty($data_4))
			            {
			            	foreach ($data_4 as $key => $val_4) {
				            		
				            	$type_id    = !empty($val_4->type_id)?$val_4->type_id:'';
								$hsn_code   = !empty($val_4->hsn_code)?$val_4->hsn_code:'';
								$gst_val    = !empty($val_4->gst_val)?$val_4->gst_val:'0';
								$price      = !empty($val_4->price)?$val_4->price:'0';
								$return_qty = !empty($val_4->return_qty)?$val_4->return_qty:'0';

								// Product Type Details
								$whr_5  = array('id' => $type_id);
								$col_5  = 'description';

								$data_5 = $this->commom_model->getProductType($whr_5, '', '', 'result', '', '', '', '', $col_5);
								$res_5  = $data_5[0];
								$desc   = !empty($res_5->description)?$res_5->description:'';

								$product_details[] = array(
									'description' => $desc,
									'hsn_code'    => $hsn_code,
									'gst_val'     => $gst_val,
									'price'       => $price,
									'return_qty'  => $return_qty,
								);
				            }
			            }

			            // Tax Details
			            $whr_6   = array('return_id' => $return_id, 'published' => '1');
	        			$col_6   = 'hsn_code, gst_val';
	        			$groupby = 'hsn_code';
	        			$data_6  = $this->return_model->getDistributorReturnDetails($whr_6, '', '', 'result', '', '', '', '', $col_6, $groupby);

	        			$tax_details = [];
	        			if(!empty($data_6))
	        			{
	        				foreach ($data_6 as $key => $val_6) {
	        					$hsn_code = !empty($val_6->hsn_code)?$val_6->hsn_code:'';
	        					$gst_val  = !empty($val_6->gst_val)?$val_6->gst_val:'0';

	        					// Price Details
	        					$whr_7  = array(
			        				'return_id' => $return_id,
			        				'hsn_code'  => $hsn_code,
			        				'published' => '1',
			        			);

			        			$col_7  = 'price, return_qty, gst_val';

			        			$data_7 = $this->return_model->getDistributorReturnDetails($whr_7, '', '', 'result', '', '', '', '', $col_7);

			        			$product_price = 0;
			        			$gst_price     = 0;
			        			foreach ($data_7 as $key => $val_7) {
			        				$price      = !empty($val_7->price)?$val_7->price:'0';
			        				$return_qty = !empty($val_7->return_qty)?$val_7->return_qty:'0';
			        				$gst_val    = !empty($val_7->gst_val)?$val_7->gst_val:'0';

			        				$gst_data    = $price - ($price * (100 / (100 + $gst_val)));
                            		$price_val   = $price - $gst_data;
                            		$total_price = $return_qty * $price_val;
                            		$total_gst   = $return_qty * $gst_data;

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

	        			$return_details = array(
			            	'distributor_details' => $distributor_det,
			            	'admin_details'       => $admin_det,
			            	'product_details'     => $product_details,
			            	'tax_details'         => $tax_details,
			            );

			            $response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $return_details;
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
			        $response['message'] = "Please fill all required fields"; 
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
	}
?>
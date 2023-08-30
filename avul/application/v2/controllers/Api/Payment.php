<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class Payment extends CI_Controller {

		// Amount Type
		// 1 => Cash
		// 2 => Cheque
		// 3 => Others

		public function __construct()
		{
			parent::__construct();

			$this->load->model('vendors_model');
			$this->load->model('outlets_model');
			$this->load->model('distributors_model');
			$this->load->model('payment_model');
			$this->load->model('employee_model');
			$this->load->model('invoice_model');
			$this->load->model('commom_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Outlet Payment
		// ***************************************************
		public function outlet_payment($param1="",$param2="",$param3="")
		{
			// Financial Year Details
			$option['order_by']   = 'id';
			$option['disp_order'] = 'DESC';

			$where = array(
				'status'    => '1', 
				'published' => '1',
			);

			$data_list = $this->commom_model->getfinancial($where, '1', '0', 'result', '', '', $option);

			$financial_id = !empty($data_list[0]->id)?$data_list[0]->id:'';

			$method = $this->input->post('method');

			if($method == '_addOutletPayment')
			{
				$assign_id      = $this->input->post('assign_id');
				$pay_id         = $this->input->post('pay_id');
				$employee_id    = $this->input->post('employee_id');
				$distributor_id = $this->input->post('distributor_id');
				$outlet_id      = $this->input->post('outlet_id');
				$amount         = $this->input->post('amount');
				$discount       = $this->input->post('discount');
				$amt_type       = $this->input->post('amt_type');
				$description    = $this->input->post('description');
				$entry_type     = $this->input->post('entry_type');
				$entry_date     = $this->input->post('entry_date');
				$bank_name      = $this->input->post('bank_name');
				$cheque_no      = $this->input->post('cheque_no');
				$collect_date   = $this->input->post('collect_date');
				$coll_detail    = $this->input->post('coll_type');
				$ref_id         = $this->input->post('ref_id');
				$coll_type      = ($coll_detail == 1) ? 1 : 2;
				$date           = date('Y-m-d');
				$time           = date('H:i:s');
				$c_date         = date('Y-m-d H:i:s');

				$error = FALSE;
			    $errors = array();
				$required = array('assign_id', 'pay_id', 'distributor_id', 'outlet_id', 'amount', 'amt_type', 'entry_type', 'entry_date');

				if($amt_type == 2)
			    {
			    	array_push($required, 'bank_name', 'cheque_no', 'collect_date');
			    }
			    // else if($amt_type == 3)
			    // {
			    // 	array_push($required, 'description');
			    // }

			    if($entry_type != 1)
			    {
			    	array_push($required, 'employee_id');
			    }

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
			    	if(!empty($discount))
			    	{
			    		$discount_val = $discount;
			    	}
			    	else
			    	{
			    		$discount_val = '0';
			    	}
                    // Distributor Details
							$dis_where  = array('id' => $distributor_id);
							$dis_column = 'ref_id';
							$dis_data   = $this->distributors_model->getDistributors($dis_where, '', '', 'result', '', '', '', '', $dis_column);
							$ref_id   = !empty($dis_data[0]->ref_id) ? $dis_data[0]->ref_id : '';
			    	// Total Amount
					$total_val   = $amount + $discount_val;

					// Outlet Bill Details
					$bill_whr = array(
						'id'        => $pay_id,
			    		'published' => '1',
			    		'status'    => '1',
					);

					$bill_col  = 'id, bill_id, bill_no, pre_bal, cur_bal, bal_amt';

					$bill_data = $this->payment_model->getOutletPaymentDetails($bill_whr, '', '', 'result', '', '', '', '', $bill_col);

					$auto_id = !empty($bill_data[0]->id)?$bill_data[0]->id:'';
					$bill_id = !empty($bill_data[0]->bill_id)?$bill_data[0]->bill_id:'';
					$bill_no = !empty($bill_data[0]->bill_no)?$bill_data[0]->bill_no:'';
					$pre_bal = !empty($bill_data[0]->pre_bal)?$bill_data[0]->pre_bal:'0';
					$cur_bal = !empty($bill_data[0]->cur_bal)?$bill_data[0]->cur_bal:'0';
					$bal_amt = !empty($bill_data[0]->bal_amt)?$bill_data[0]->bal_amt:'0';

					if($bal_amt >= $total_val)
					{
						// Outlet Table Balance Update
				    	$outlet_whr  = array(
				    		'id'        => $outlet_id,
				    		'published' => '1',
				    	);

				    	$outlet_col  = 'available_limit, current_balance';

				    	$outlet_data = $this->outlets_model->getOutlets($outlet_whr, '', '', 'result', '', '', '', '', $outlet_col);

				    	$available_limit = !empty($outlet_data[0]->available_limit)?$outlet_data[0]->available_limit:'0';
				    	$current_balance = !empty($outlet_data[0]->current_balance)?$outlet_data[0]->current_balance:'0';

				    	if($current_balance >= $total_val)
				    	{
				    		// Distributor outlet payment details
					    	$dis_shop_whr = array(
					    		'distributor_id' => $distributor_id,
					    		'outlet_id'      => $outlet_id,
					    		'published'      => '1',
					    	);

					    	$dis_shop_col  = 'id, pre_bal, cur_bal';

					    	$dis_shop_data = $this->distributors_model->getDistributorOutlet($dis_shop_whr, '', '', 'result', '', '', '', '', $dis_shop_col);

					    	$old_pre_bal = !empty($dis_shop_data[0]->pre_bal)?$dis_shop_data[0]->pre_bal:'0';
							$old_cur_bal = !empty($dis_shop_data[0]->cur_bal)?$dis_shop_data[0]->cur_bal:'0';

							// Outlet payment update
							$new_balance = $old_cur_bal - $total_val;

					    	$dis_outlet_data = array(
					    		'pre_bal'    => $old_cur_bal,
					    		'cur_bal'    => $new_balance,
					    		'updatedate' => $c_date,
					    	);

					    	if($coll_type == 1 && $amt_type != 2)
					    	{
					    		$dis_outlet_whr  = array('id' => $assign_id,);
					    		$dis_outlet_upt  = $this->distributors_model->distributorOutlet_update($dis_outlet_data, $dis_outlet_whr);
					    	}
					    	else if($coll_type != 1 && $amt_type == 1)
					    	{
					    		$dis_outlet_whr  = array('id' => $assign_id,);
					    		$dis_outlet_upt  = $this->distributors_model->distributorOutlet_update($dis_outlet_data, $dis_outlet_whr);
					    	}

					    	$new_avl_balance = $available_limit + $total_val;
					    	$new_cur_balance = $current_balance - $total_val;

					    	$outlet_data = array(
					    		'available_limit' => $new_avl_balance,
					    		'current_balance' => $new_cur_balance,
					    		'updatedate'      => $c_date,
					    	);

					    	if($coll_type == 1 && $amt_type != 2)
					    	{
					    		$outlet_whr = array('id' => $outlet_id);
					    		$outlet_upt = $this->outlets_model->outlets_update($outlet_data, $outlet_whr);
					    	}

					    	else if($coll_type != 1 && $amt_type == 1)
					    	{
					    		$outlet_whr = array('id' => $outlet_id);
					    		$outlet_upt = $this->outlets_model->outlets_update($outlet_data, $outlet_whr);
					    	}

					    	// Outlet bill wise payment
					    	$bill_balance = $cur_bal - $total_val;
					    	$new_bal_amt  = $bal_amt - $total_val;

					    	$payment_data = array(
					    		'pre_bal'    => $cur_bal,
					    		'cur_bal'    => $bill_balance,
					    		'bal_amt'    => $new_bal_amt,
					    		'updatedate' => $c_date,
					    	);

					    	if($coll_type == 1 && $amt_type != 2)
					    	{
					    		$payment_whr = array('id' => $pay_id);
					    		$payment_upt = $this->payment_model->outletPaymentDetails_update($payment_data, $payment_whr);
					    	}

					    	else if($coll_type != 1 && $amt_type == 1)
					    	{
					    		$payment_whr = array('id' => $pay_id);
					    		$payment_upt = $this->payment_model->outletPaymentDetails_update($payment_data, $payment_whr);
					    	}

		            		if($coll_type == 1 && $amt_type != 2)
		            		{
		            			$collection_type = 2;

		            			// Balance Sheet
						    	$master_where = array(
						    		'distributor_id' => $distributor_id,
						    		'bill_code'      => 'REC',
									'published'      => '1',
									'financial_id'   => $financial_id,
								);

								$bill_val = $this->payment_model->getOutletPayment($master_where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

								$count_val = leadingZeros($bill_val[0]->autoid, 5);
			            		$bill_num  = 'REC'.$count_val;
			            		$bill_code = 'REC';
			            		$chq_pro   = '2';
		            		}

		            		else if($coll_type != 1 && $amt_type == 1)
		            		{
		            			$collection_type = 2;

		            			// Balance Sheet
						    	$master_where = array(
						    		'distributor_id' => $distributor_id,
						    		'bill_code'      => 'REC',
									'published'      => '1',
									'financial_id'   => $financial_id,
								);

								$bill_val = $this->payment_model->getOutletPayment($master_where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

								$count_val = leadingZeros($bill_val[0]->autoid, 5);
			            		$bill_num  = 'REC'.$count_val;
			            		$bill_code = 'REC';
			            		$chq_pro   = '2';
		            		}

		            		else if($coll_type != 1 && $amt_type == 3 || $amt_type == 5)
		            		{
		            			$collection_type = 1;

		            			// Balance Sheet
						    	$master_where = array(
						    		'distributor_id' => $distributor_id,
						    		'bill_code'      => 'TEMP',
									'published'      => '1',
									'financial_id'   => $financial_id,
								);

								$bill_val = $this->payment_model->getOutletPayment($master_where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

								$count_val = leadingZeros($bill_val[0]->autoid, 5);
			            		$bill_num  = 'TEMP'.$count_val;
			            		$bill_code = 'TEMP';
			            		$chq_pro   = '1';
		            		}

		            		else if($amt_type == 2)
		            		{
		            			$collection_type = 1;

		            			// Balance Sheet
						    	$master_where = array(
						    		'distributor_id' => $distributor_id,
						    		'bill_code'      => 'CHQ',
									'published'      => '1',
									'financial_id'   => $financial_id,
								);

								$bill_val = $this->payment_model->getOutletPayment($master_where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

								$count_val = leadingZeros($bill_val[0]->autoid, 5);
			            		$bill_num  = 'CHQ'.$count_val;
			            		$bill_code = 'CHQ';
			            		$chq_pro   = '1';
		            		}
		            		

		    				$ins_data = array(
								'ref_id'          => $ref_id,
		    					'assign_id'       => $assign_id,
		    					'payment_id'      => $pay_id,
		    					'employee_id'     => $employee_id,
		    					'distributor_id'  => $distributor_id,
		    					'outlet_id'       => $outlet_id,
		    					'bill_code'       => $bill_code,
		    					'bill_id'         => $bill_id,
		    					'bill_no'         => $bill_num,
		    					'available_limit' => $available_limit,
		    					'pre_bal'         => $current_balance,
		    					'cur_bal'         => $new_balance,
		    					'amount'          => round($amount),
		    					'discount'        => $discount,
		    					'pay_type'        => 1,
		    					'description'     => $description,
		    					'bank_name'       => $bank_name,
					    		'cheque_no'       => $cheque_no,
					    		'collect_date'    => date('Y-m-d', strtotime($collect_date)),
		    					'amt_type'        => $amt_type,
		    					'collection_type' => $collection_type,
		    					'cheque_process'  => $chq_pro,
		    					'value_type'      => 2,
		    					'financial_id'    => $financial_id,
		    					'date'            => date('Y-m-d', strtotime($entry_date)),
		    					'time'            => date('H:i:s'),
		    					'createdate'      => date('Y-m-d H:i:s'),
		    				);

		    				if($amt_type == 5)
				    		{
				    			if(!empty($_FILES['collection_img']['name']))
							    {
							    	$img_name  = $_FILES['collection_img']['name'];
									$img_val   = explode('.', $img_name);
									$img_res   = end($img_val);
									$file_name = generateRandomString(13).'.'.$img_res;

								    $configImg['upload_path']   = 'upload/distributor/collection/';
									$configImg['max_size']      = '1024000';
									$configImg['allowed_types'] = 'jpg|jpeg|png|gif';
									$configImg['overwrite']     = FALSE;
									$configImg['remove_spaces'] = TRUE;
		                			$configImg['file_name']     = $file_name;
									$this->load->library('upload', $configImg);
									$this->upload->initialize($configImg);

									if(!$this->upload->do_upload('collection_img'))
									{
								        $response['status']  = 0;
								        $response['message'] = $this->upload->display_errors();
								        $response['data']    = [];
								        echo json_encode($response);
								        return;
									}
									else
									{
										$ins_data['collection_img'] = $file_name;
									}
							    }
							    else
							    {
							    	$response['status']  = 0;
							        $response['message'] = "Upload receipt collection screenshot."; 
							        $response['data']    = [];
							        echo json_encode($response);
							        return;
							    }
				    		}

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

						    if($payment_insert)
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
						        $response['message'] = "Not Success"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
				    	}
				    	else
					    {
		        			$response['status']  = 0;
					        $response['message'] = "Invalid Amount"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
					    }
					}
					else
				    {
	        			$response['status']  = 0;
				        $response['message'] = "Invalid Amount"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
			    }
			}

			else if($method == '_editOutletPayment')
			{
        		$auto_id       = $this->input->post('auto_id');
        		$entry_date    = $this->input->post('entry_date');
			    $description   = $this->input->post('description');
			    $penalty_amt   = $this->input->post('penalty_amt');
			    $bank_charge   = $this->input->post('bank_charge');
			    $cheque_status = $this->input->post('cheque_status');
			    $date          = date('Y-m-d');
				$time          = date('H:i:s');
				$c_date        = date('Y-m-d H:i:s');

				$error = FALSE;
			    $errors = array();
				$required = array('auto_id', 'cheque_status', 'entry_date', 'description');

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

					   

			    	$payment_whr  = array(
						'id'        => $auto_id,
						'published' => '1',
					);

					$payment_col  = 'id, assign_id, payment_id, distributor_id, employee_id, outlet_id, amount, discount, bank_name, cheque_no, collect_date, amt_type, date';
					$payment_data = $this->payment_model->getOutletPayment($payment_whr, '', '', 'result', '', '', '', '', $payment_col);

					if(!empty($payment_data))
					{
						$pay_data     = $payment_data[0];

						$db_auto_id      = !empty($pay_data->id)?$pay_data->id:'';
			            $db_assign_id    = !empty($pay_data->assign_id)?$pay_data->assign_id:'';
			            $db_payment_id   = !empty($pay_data->payment_id)?$pay_data->payment_id:'';
			            $db_dis_id       = !empty($pay_data->distributor_id)?$pay_data->distributor_id:'';
			            $db_emp_id       = !empty($pay_data->employee_id)?$pay_data->employee_id:'';
			            $db_outlet_id    = !empty($pay_data->outlet_id)?$pay_data->outlet_id:'';
			            $db_amount       = !empty($pay_data->amount)?$pay_data->amount:'0';
			            $db_discount     = !empty($pay_data->discount)?$pay_data->discount:'0';
			            $db_bank_name    = !empty($pay_data->bank_name)?$pay_data->bank_name:'';
			            $db_cheque_no    = !empty($pay_data->cheque_no)?$pay_data->cheque_no:'';
			            $db_collect_date = !empty($pay_data->collect_date)?$pay_data->collect_date:'';
			            $db_amt_type     = !empty($pay_data->amt_type)?$pay_data->amt_type:'';
			            $db_entry_date   = !empty($pay_data->date)?$pay_data->date:'';

			            $tot_penalty  = !empty($penalty_amt)?$penalty_amt:'0';
			            $tot_bankChar = !empty($bank_charge)?$bank_charge:'0';

			            if(!empty($entry_date))
			            {
			            	$ins_date = date('Y-m-d', strtotime($entry_date));
			            }
			            else
			            {
			            	$ins_date = date('Y-m-d', strtotime($db_entry_date));
			            }

						 // Distributor Details
						$dis_where  = array('id' => $db_dis_id);
						$dis_column = 'ref_id';
						$dis_data   = $this->distributors_model->getDistributors($dis_where, '', '', 'result', '', '', '', '', $dis_column);
						$ref_id   = !empty($dis_data[0]->ref_id) ? $dis_data[0]->ref_id : '';

			            if($cheque_status == 1)
			            {
			            	$db_total_amt = $db_amount + $db_discount;

			            	// Outlet Bill Details
							$bill_whr = array(
								'id'        => $db_payment_id,
					    		'published' => '1',
					    		'status'    => '1',
							);

							$bill_col  = 'id, bill_id, bill_no, pre_bal, cur_bal, bal_amt';

							$bill_data = $this->payment_model->getOutletPaymentDetails($bill_whr, '', '', 'result', '', '', '', '', $bill_col);

							$auto_id = !empty($bill_data[0]->id)?$bill_data[0]->id:'';
							$bill_id = !empty($bill_data[0]->bill_id)?$bill_data[0]->bill_id:'';
							$bill_no = !empty($bill_data[0]->bill_no)?$bill_data[0]->bill_no:'';
							$pre_bal = !empty($bill_data[0]->pre_bal)?$bill_data[0]->pre_bal:'0';
							$cur_bal = !empty($bill_data[0]->cur_bal)?$bill_data[0]->cur_bal:'0';
							$bal_amt = !empty($bill_data[0]->bal_amt)?$bill_data[0]->bal_amt:'0';

							if($bal_amt >= $db_total_amt)
							{
								// Outlet Table Balance Update
						    	$outlet_whr  = array(
						    		'id'        => $db_outlet_id,
						    		'published' => '1',
						    	);

						    	$outlet_col  = 'available_limit, current_balance';

						    	$outlet_data = $this->outlets_model->getOutlets($outlet_whr, '', '', 'result', '', '', '', '', $outlet_col);

						    	$available_limit = !empty($outlet_data[0]->available_limit)?$outlet_data[0]->available_limit:'0';
						    	$current_balance = !empty($outlet_data[0]->current_balance)?$outlet_data[0]->current_balance:'0';

						    	if($current_balance >= $db_total_amt)
						    	{
						    		// Distributor outlet payment details
							    	$dis_shop_whr = array(
							    		'distributor_id' => $db_dis_id,
							    		'outlet_id'      => $db_outlet_id,
							    		'published'      => '1',
							    	);

							    	$dis_shop_col  = 'id, pre_bal, cur_bal';

							    	$dis_shop_data = $this->distributors_model->getDistributorOutlet($dis_shop_whr, '', '', 'result', '', '', '', '', $dis_shop_col);

							    	$old_pre_bal = !empty($dis_shop_data[0]->pre_bal)?$dis_shop_data[0]->pre_bal:'0';
									$old_cur_bal = !empty($dis_shop_data[0]->cur_bal)?$dis_shop_data[0]->cur_bal:'0';

									// Outlet payment update
									$new_balance = $old_cur_bal - $db_total_amt;

							    	$dis_outlet_data = array(
							    		'pre_bal'    => $old_cur_bal,
							    		'cur_bal'    => $new_balance,
							    		'updatedate' => $c_date,
							    	);

							    	$dis_outlet_whr  = array('id' => $db_assign_id);
					    			$dis_outlet_upt  = $this->distributors_model->distributorOutlet_update($dis_outlet_data, $dis_outlet_whr);

					    			$new_avl_balance = $available_limit + $db_total_amt;
							    	$new_cur_balance = $current_balance - $db_total_amt;

							    	$outletBal_data = array(
							    		'available_limit' => $new_avl_balance,
							    		'current_balance' => $new_cur_balance,
							    		'updatedate'      => $c_date,
							    	);

							    	$outlet_whr = array('id' => $db_outlet_id);
					    			$outlet_upt = $this->outlets_model->outlets_update($outletBal_data, $outlet_whr);

					    			// Outlet bill wise payment
							    	$bill_balance = $cur_bal - $db_total_amt;
							    	$new_bal_amt  = $bal_amt - $db_total_amt;

							    	$payment_data = array(
							    		'pre_bal'    => $cur_bal,
							    		'cur_bal'    => $bill_balance,
							    		'bal_amt'    => $new_bal_amt,
							    		'updatedate' => $c_date,
							    	);

							    	$payment_whr = array('id' => $auto_id);
					    			$payment_upt = $this->payment_model->outletPaymentDetails_update($payment_data, $payment_whr);

					    			$outPayment_val = array('collection_type' => '2'); 
					    			$outPayment_whr = array('id' => $db_auto_id);
						    		$outPayment_upt = $this->payment_model->outletPayment_update($outPayment_val, $outPayment_whr);

					    			$collection_type = 2;

			            			// Balance Sheet
							    	$master_where = array(
							    		'distributor_id' => $db_dis_id,
							    		'bill_code'      => 'REC',
										'published'      => '1',
										'financial_id'   => $financial_id,
									);

									$bill_val = $this->payment_model->getOutletPayment($master_where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

									$count_val = leadingZeros($bill_val[0]->autoid, 5);
				            		$bill_num  = 'REC'.$count_val;
				            		$bill_code = 'REC';
				            		$chq_pro   = '2';

				            		$ins_data = array(
										'ref_id'          => $ref_id,
				    					'assign_id'       => $db_assign_id,
				    					'payment_id'      => $db_payment_id,
				    					'employee_id'     => $db_emp_id,
				    					'distributor_id'  => $db_dis_id,
				    					'outlet_id'       => $db_outlet_id,
				    					'bill_code'       => $bill_code,
				    					'bill_id'         => $bill_id,
				    					'bill_no'         => $bill_num,
				    					'available_limit' => $available_limit,
				    					'pre_bal'         => $current_balance,
				    					'cur_bal'         => $new_balance,
				    					'amount'          => round($db_amount),
				    					'discount'        => $db_discount,
				    					'pay_type'        => 1,
				    					'description'     => $description,
				    					'bank_name'       => $db_bank_name,
							    		'cheque_no'       => $db_cheque_no,
							    		'collect_date'    => date('Y-m-d', strtotime($db_collect_date)),
				    					'amt_type'        => $db_amt_type,
				    					'collection_type' => $collection_type,
				    					'cheque_process'  => $chq_pro,
				    					'value_type'      => 2,
				    					'financial_id'    => $financial_id,
				    					'date'            => date('Y-m-d', strtotime($ins_date)),
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
							    		'assign_id'      => $db_assign_id,
							    		'distributor_id' => $db_dis_id,
							    		'outlet_id'      => $db_outlet_id,
							    	);

							    	$payment_update = $this->payment_model->outletPayment_update($prePayment_data, $prePayment_whr);

								    if($payment_insert)
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
								        $response['message'] = "Not Success"; 
								        $response['data']    = [];
								        echo json_encode($response);
								        return; 
								    }
						    	}
						    	else
							    {
				        			$response['status']  = 0;
							        $response['message'] = "Invalid Amount"; 
							        $response['data']    = [];
							        echo json_encode($response);
							        return; 
							    }
							}
							else
						    {
			        			$response['status']  = 0;
						        $response['message'] = "Invalid Amount"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
			            }
			            else
			            {
			            	$total_value  = $tot_penalty + $tot_bankChar;

			            	// Distributor outlet payment details
					    	$dis_shop_whr = array(
					    		'distributor_id' => $db_dis_id,
					    		'outlet_id'      => $db_outlet_id,
					    		'published'      => '1',
					    	);

					    	$dis_shop_col  = 'id, pre_bal, cur_bal';

					    	$dis_shop_data = $this->distributors_model->getDistributorOutlet($dis_shop_whr, '', '', 'result', '', '', '', '', $dis_shop_col);

					    	$old_pre_bal = !empty($dis_shop_data[0]->pre_bal)?$dis_shop_data[0]->pre_bal:'0';
							$old_cur_bal = !empty($dis_shop_data[0]->cur_bal)?$dis_shop_data[0]->cur_bal:'0';

							// Outlet payment update
							$new_balance = $old_cur_bal + $total_value;

					    	$dis_outlet_data = array(
					    		'pre_bal'    => $old_cur_bal,
					    		'cur_bal'    => $new_balance,
					    		'updatedate' => $c_date,
					    	);

					    	$dis_outlet_whr  = array('id' => $db_assign_id);
					    	$dis_outlet_upt  = $this->distributors_model->distributorOutlet_update($dis_outlet_data, $dis_outlet_whr);

						    $outlet_whr  = array(
					    		'id'        => $db_outlet_id,
					    		'published' => '1',
					    	);

					    	$outlet_col  = 'available_limit, current_balance';

					    	$outlet_data = $this->outlets_model->getOutlets($outlet_whr, '', '', 'result', '', '', '', '', $outlet_col);

					    	$available_limit = !empty($outlet_data[0]->available_limit)?$outlet_data[0]->available_limit:'0';
					    	$current_balance = !empty($outlet_data[0]->current_balance)?$outlet_data[0]->current_balance:'0';

						    $new_avl_balance = $available_limit - $total_value;
					    	$new_cur_balance = $current_balance + $total_value;

					    	$outletBal_data = array(
					    		'available_limit' => $new_avl_balance,
					    		'current_balance' => $new_cur_balance,
					    		'updatedate'      => $c_date,
					    	);

					    	$outlet_whr = array('id' => $db_outlet_id);
					    	$outlet_upt = $this->outlets_model->outlets_update($outletBal_data, $outlet_whr);

					    	// Outlet bill wise payment
					    	$bill_whr = array(
								'id'        => $db_payment_id,
					    		'published' => '1',
					    		'status'    => '1',
							);

							$bill_col  = 'id, bill_id, bill_no, pre_bal, cur_bal, bal_amt';

							$bill_data = $this->payment_model->getOutletPaymentDetails($bill_whr, '', '', 'result', '', '', '', '', $bill_col);

							$auto_id = !empty($bill_data[0]->id)?$bill_data[0]->id:'';
							$bill_id = !empty($bill_data[0]->bill_id)?$bill_data[0]->bill_id:'';
							$bill_no = !empty($bill_data[0]->bill_no)?$bill_data[0]->bill_no:'';
							$pre_bal = !empty($bill_data[0]->pre_bal)?$bill_data[0]->pre_bal:'0';
							$cur_bal = !empty($bill_data[0]->cur_bal)?$bill_data[0]->cur_bal:'0';
							$bal_amt = !empty($bill_data[0]->bal_amt)?$bill_data[0]->bal_amt:'0';

					    	$bill_balance = $cur_bal + $total_value;
					    	$new_bal_amt  = $bal_amt + $total_value;

					    	$payment_data = array(
					    		'pre_bal'    => $cur_bal,
					    		'cur_bal'    => $bill_balance,
					    		'bal_amt'    => $new_bal_amt,
					    		'updatedate' => $c_date,
					    	);

					    	$payment_whr = array('id' => $auto_id);
					    	$payment_upt = $this->payment_model->outletPaymentDetails_update($payment_data, $payment_whr);

					    	$outPayment_val = array('collection_type' => '2'); 
			    			$outPayment_whr = array('id' => $db_auto_id);
				    		$outPayment_upt = $this->payment_model->outletPayment_update($outPayment_val, $outPayment_whr);

				    		// Balance Sheet
					    	$master_where = array(
					    		'distributor_id' => $db_dis_id,
					    		'bill_code'      => 'PEN',
								'published'      => '1',
								'financial_id'   => $financial_id,
							);

							$bill_val = $this->payment_model->getOutletPayment($master_where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

							$count_val = leadingZeros($bill_val[0]->autoid, 5);
		            		$bill_num  = 'PEN'.$count_val;
		            		$bill_code = 'PEN';
		            		$chq_pro   = '2';

		            		$ins_data = array(
								'ref_id'          => $ref_id,
		    					'assign_id'       => $db_assign_id,
		    					'payment_id'      => $db_payment_id,
		    					'employee_id'     => $db_emp_id,
		    					'distributor_id'  => $db_dis_id,
		    					'outlet_id'       => $db_outlet_id,
		    					'bill_code'       => $bill_code,
		    					'bill_id'         => $bill_id,
		    					'bill_no'         => $bill_num,
		    					'available_limit' => $available_limit,
		    					'pre_bal'         => $current_balance,
		    					'cur_bal'         => $new_balance,
		    					'amount'          => round($db_amount),
		    					'discount'        => $db_discount,
		    					'pay_type'        => 1,
		    					'description'     => $description,
		    					'bank_name'       => $db_bank_name,
					    		'cheque_no'       => $db_cheque_no,
					    		'collect_date'    => date('Y-m-d', strtotime($db_collect_date)),
		    					'amt_type'        => 3,
		    					'penalty_amt'     => $tot_penalty,
				    			'bank_charge'     => $tot_bankChar,
					    		'collection_type' => 2,
					    		'cheque_process'  => 2,
					    		'value_type'      => 2,
					    		'status'          => 0,
		    					'financial_id'    => $financial_id,
		    					'date'            => date('Y-m-d', strtotime($ins_date)),
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
					    		'assign_id'      => $db_assign_id,
					    		'distributor_id' => $db_dis_id,
					    		'outlet_id'      => $db_outlet_id,
					    	);

					    	$payment_update = $this->payment_model->outletPayment_update($prePayment_data, $prePayment_whr);

						    if($payment_insert)
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
						        $response['message'] = "Not Success"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
			            }
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

			else if($method == '_outletPaymentDetails')
			{
				$payment_id = $this->input->post('payment_id');

				if(!empty($payment_id))
				{
					$payment_whr  = array(
						'id'        => $payment_id,
						'published' => '1',
					);

					$payment_col  = 'id, payment_id, amount, discount, bank_name, cheque_no, collect_date, amt_type, date';
					$payment_data = $this->payment_model->getOutletPayment($payment_whr, '', '', 'result', '', '', '', '', $payment_col);

					if(!empty($payment_data))
					{
						$pay_data     = $payment_data[0];
						$auto_id      = !empty($pay_data->id)?$pay_data->id:'';
			            $payment_id   = !empty($pay_data->payment_id)?$pay_data->payment_id:'';
			            $amount       = !empty($pay_data->amount)?$pay_data->amount:'';
			            $discount     = !empty($pay_data->discount)?$pay_data->discount:'';
			            $bank_name    = !empty($pay_data->bank_name)?$pay_data->bank_name:'';
			            $cheque_no    = !empty($pay_data->cheque_no)?$pay_data->cheque_no:'';
			            $collect_date = !empty($pay_data->collect_date)?$pay_data->collect_date:'';
			            $amt_type     = !empty($pay_data->amt_type)?$pay_data->amt_type:'';
			            $entry_date   = !empty($pay_data->date)?$pay_data->date:'';

			            $pay_result = array(
			            	'auto_id'      => $auto_id,
				            'payment_id'   => $payment_id,
				            'amount'       => $amount,
				            'discount'     => $discount,
				            'bank_name'    => $bank_name,
				            'cheque_no'    => $cheque_no,
				            'collect_date' => date('d-m-Y', strtotime($collect_date)),
				            'amt_type'     => $amt_type,
				            'entry_date'   => date('d-m-Y', strtotime($entry_date)),
			            );

			            $response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $pay_result;
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

			else if($method == '_collectionType')
			{
				$amount_type = array(
					array('type_id'  => strval('1'), 'type_val' => 'Cash'),
					array('type_id'  => strval('2'), 'type_val' => 'Cheque'),
					array('type_id'  => strval('3'), 'type_val' => 'Others')
				);

				// for ($i=1; $i <= 3 ; $i++) { 

				// 	if($i == 1)
				// 	{
				// 		$type_val = 'Cash';
				// 	}
				// 	else if($i == 2)
				// 	{
				// 		$type_val = 'Cheque';
				// 	}
				// 	else
				// 	{
				// 		$type_val = 'Others';
				// 	}

				// 	$amount_type[] = array(
				// 		'type_id'  => strval($i),
				// 		'type_val' => $type_val,
				// 	);

				// }

				$response['status']  = 1;
		        $response['message'] = "Success"; 
		        $response['data']    = $amount_type;
		        echo json_encode($response);
		        return;
			}

			else if($method == '_collectionTypeNew')
			{
				$dis_id   = $this->input->post('distributor_id');

				$error    = FALSE;
			    $errors   = array();
				$required = array('distributor_id');

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
			    	$amount_type = array(
						array('type_id'  => '1', 'type_val' => 'Cash', 'qr_img' => ''),
						array('type_id'  => '2', 'type_val' => 'Cheque', 'qr_img' => ''),
						array('type_id'  => '3', 'type_val' => 'Others', 'qr_img' => '')
					);

					// Distributor details
					$dis_whr = array('id' => $dis_id, 'published' => '1');
					$dis_col = 'qr_image';
					$dis_res = $this->distributors_model->getDistributors($dis_whr, '', '', 'row', '', '', '', '', $dis_col);
					$dis_qr  = !empty($dis_res->qr_image)?$dis_res->qr_image:null;

					if(!empty($dis_qr))
					{
						$qr_image = FILE_URL.'distributor/qr_code/'.$dis_qr;
						array_push($amount_type, array('type_id' => '5', 'type_val' => 'UPI', 'qr_img' => $qr_image));
					}

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $amount_type;
		    		echo json_encode($response);
			        return;
			    }
			}

			else if($method == '_listDistributorOutletPaymentPaginate')
			{
				$assign_id      = $this->input->post('assign_id');

				$error = FALSE;
			    $errors = array();
				$required = array('assign_id');
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
			    	$limit  = $this->input->post('limit');
	    			$offset = $this->input->post('offset');

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
		    			$where = array(
		    				'assign_id'  => $assign_id,
		    				'value_type' => '2',
		    				'published'  => '1',
		    			);
		    		}
		    		else
		    		{
		    			$like = [];
		    			$where = array(
		    				'assign_id'  => $assign_id,
		    				'value_type' => '2',
		    				'published'  => '1',
		    			);
		    		}

		    		$column = 'id';
					$overalldatas = $this->payment_model->getOutletPayment($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->payment_model->getOutletPayment($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$payment_list = [];
						foreach ($data_list as $key => $value) {
							$payment_id      = !empty($value->id)?$value->id:'';
							$assign_id       = !empty($value->assign_id)?$value->assign_id:'';
							$employee_id     = !empty($value->employee_id)?$value->employee_id:'';
							$distributor_id  = !empty($value->distributor_id)?$value->distributor_id:'';
							$bill_code       = !empty($value->bill_code)?$value->bill_code:'';
							$bill_no         = !empty($value->bill_no)?$value->bill_no:'';
							$outlet_id       = !empty($value->outlet_id)?$value->outlet_id:'';
							$pre_bal         = !empty($value->pre_bal)?$value->pre_bal:'0';
							$cur_bal         = !empty($value->cur_bal)?$value->cur_bal:'0';
							$amount          = !empty($value->amount)?$value->amount:'0';
							$discount        = !empty($value->discount)?$value->discount:'0';
							$pay_type        = !empty($value->pay_type)?$value->pay_type:'';
							$description     = !empty($value->description)?$value->description:'';
							$amt_type        = !empty($value->amt_type)?$value->amt_type:'';
							$collection_type = !empty($value->collection_type)?$value->collection_type:'';
							$cheque_process  = !empty($value->cheque_process)?$value->cheque_process:'';
							$penalty_amt     = !empty($value->penalty_amt)?$value->penalty_amt:'0';
							$bank_charge     = !empty($value->bank_charge)?$value->bank_charge:'0';
							$date            = !empty($value->date)?$value->date:'';
							$time            = !empty($value->time)?$value->time:'';
							$status          = !empty($value->status)?$value->status:'';

							if(!empty($employee_id))
							{
								// Employee Details
								$emp_whr  = array(
									'id'        => $employee_id,
									'status'    => '1',
			    					'published' => '1',
								);

								$emp_col  = 'first_name,last_name';

								$emp_data = $this->employee_model->getEmployee($emp_whr, '', '', 'result', '', '', '', '', $emp_col);

								$first_name = !empty($emp_data[0]->first_name)?$emp_data[0]->first_name:'';
								$last_name = !empty($emp_data[0]->last_name)?$emp_data[0]->last_name:'';
								
								$arr = array($first_name,$last_name);
								$emp_name =join(" ",$arr);
							}
							else
							{
								$emp_name = 'Admin';
							}

							// Outlet Details
							$outlet_whr  = array(
								'id'        => $outlet_id,
								'status'    => '1',
		    					'published' => '1',
							);

							$outlet_col  = 'company_name';

							$outlet_data = $this->outlets_model->getOutlets($outlet_whr, '', '', 'result', '', '', '', '', $outlet_col);

							$outlet_name = !empty($outlet_data[0]->company_name)?$outlet_data[0]->company_name:'';

							// Distributor Details
							$distributor_whr  = array(
								'id'        => $distributor_id,
								'status'    => '1',
		    					'published' => '1',
							);

							$distributor_col  = 'company_name';

							$dist_data = $this->distributors_model->getDistributors($distributor_whr, '', '', 'result', '', '', '', '', $distributor_col);

							$dist_name = !empty($dist_data[0]->company_name)?$dist_data[0]->company_name:'';

							// Payment typ details
							if($pay_type == 1)
							{
								$payment_type = 'Debit';
							}
							else
							{
								$payment_type = 'Credit';
							}

							// Amount Type
							if($amt_type == 1)
							{
								$amount_type = 'Cash';
							}
							else if($amt_type == 2)
							{
								$amount_type = 'Cheque';
							}
							else if($amt_type == 3)
							{
								$amount_type = 'Others';
							}
							else if($amt_type == 4)
							{
								$amount_type = 'Credit Note';
							}
							else if($amt_type == 5)
							{
								$amount_type = 'UPI';
							}
							else
							{
								$amount_type = '---';
							}

							$payment_list[] = array(
								'payment_id'       => $payment_id,
								'assign_id'        => $assign_id,
								'employee_id'      => $employee_id,
								'employee_name'    => $emp_name,
								'distributor_id'   => $distributor_id,
								'distributor_name' => $dist_name,
								'bill_code'        => $bill_code,
								'bill_no'          => $bill_no,
								'outlet_id'        => $outlet_id,
								'outlet_name'      => $outlet_name,
								'pre_bal'          => $pre_bal,
								'cur_bal'          => $cur_bal,
								'amount'           => $amount,
								'discount'         => $discount,
								'pay_type'         => $pay_type,
								'payment_type'     => $payment_type,
								'description'      => $description,
								'amt_type'         => $amt_type,
								'amount_type'      => $amount_type,
								'penalty_amt'      => $penalty_amt,
								'bank_charge'      => $bank_charge,
								'collection_type'  => $collection_type,
								'cheque_process'   => $cheque_process,
								'date'             => date('d-m-Y', strtotime($date)),
								'time'             => date('h:i:s', strtotime($time)),
								'status'           => $status,

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
				        $response['data']         = $payment_list;
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

			else if($method == '_listOutletPaymentPaginate')
			{
				$assign_id      = $this->input->post('assign_id');

				$error = FALSE;
			    $errors = array();
				$required = array('assign_id');
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
			    	$limit  = $this->input->post('limit');
	    			$offset = $this->input->post('offset');

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
		    			$where = array(
		    				'assign_id'  => $assign_id,
		    				'published'  => '1',
		    			);
		    		}
		    		else
		    		{
		    			$like = [];
		    			$where = array(
		    				'assign_id'  => $assign_id,
		    				'published'  => '1',
		    			);
		    		}

		    		$column = 'id';
					$overalldatas = $this->payment_model->getOutletPayment($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->payment_model->getOutletPayment($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$payment_list = [];
						foreach ($data_list as $key => $value) {
							$payment_id      = !empty($value->id)?$value->id:'';
							$assign_id       = !empty($value->assign_id)?$value->assign_id:'';
							$employee_id     = !empty($value->employee_id)?$value->employee_id:'';
							$distributor_id  = !empty($value->distributor_id)?$value->distributor_id:'';
							$outlet_id       = !empty($value->outlet_id)?$value->outlet_id:'';
							$pre_bal         = !empty($value->pre_bal)?$value->pre_bal:'0';
							$cur_bal         = !empty($value->cur_bal)?$value->cur_bal:'0';
							$amount          = !empty($value->amount)?$value->amount:'0';
							$discount        = !empty($value->discount)?$value->discount:'0';
							$pay_type        = !empty($value->pay_type)?$value->pay_type:'';
							$description     = !empty($value->description)?$value->description:'';
							$amt_type        = !empty($value->amt_type)?$value->amt_type:'';
							$collection_type = !empty($value->collection_type)?$value->collection_type:'';
							$date            = !empty($value->date)?$value->date:'';
							$time            = !empty($value->time)?$value->time:'';
							$status          = !empty($value->status)?$value->status:'';

							if(!empty($employee_id))
							{
								// Employee Details
								$emp_whr  = array(
									'id'        => $employee_id,
									'status'    => '1',
			    					'published' => '1',
								);

								$emp_col  = 'first_name,last_name';

								$emp_data = $this->employee_model->getEmployee($emp_whr, '', '', 'result', '', '', '', '', $emp_col);
								$first_name = !empty($emp_data[0]->first_name)?$emp_data[0]->first_name:'';
								$last_name = !empty($emp_data[0]->last_name)?$emp_data[0]->last_name:'';
								
								$arr = array($first_name,$last_name);
								$emp_name =join(" ",$arr);
							}
							else
							{
								$emp_name = 'Admin';
							}

							// Outlet Details
							$outlet_whr  = array(
								'id'        => $outlet_id,
								'status'    => '1',
		    					'published' => '1',
							);

							$outlet_col  = 'company_name';

							$outlet_data = $this->outlets_model->getOutlets($outlet_whr, '', '', 'result', '', '', '', '', $outlet_col);

							$outlet_name = !empty($outlet_data[0]->company_name)?$outlet_data[0]->company_name:'';

							// Distributor Details
							$distributor_whr  = array(
								'id'        => $distributor_id,
								'status'    => '1',
		    					'published' => '1',
							);

							$distributor_col  = 'company_name';

							$dist_data = $this->distributors_model->getDistributors($distributor_whr, '', '', 'result', '', '', '', '', $distributor_col);

							$dist_name = !empty($dist_data[0]->company_name)?$dist_data[0]->company_name:'';

							// Payment typ details
							if($pay_type == 1)
							{
								$payment_type = 'Debit';
							}
							else
							{
								$payment_type = 'Credit';
							}

							// Amount Type
							if($amt_type == 1)
							{
								$amount_type = 'Cash';
							}
							else if($amt_type == 2)
							{
								$amount_type = 'Cheque';
							}
							else if($amt_type == 3)
							{
								$amount_type = 'Others';
							}
							else
							{
								$amount_type = '---';
							}

							$payment_list[] = array(
								'payment_id'       => $payment_id,
								'assign_id'        => $assign_id,
								'employee_id'      => $employee_id,
								'employee_name'    => $emp_name,
								'distributor_id'   => $distributor_id,
								'distributor_name' => $dist_name,
								'outlet_id'        => $outlet_id,
								'outlet_name'      => $outlet_name,
								'pre_bal'          => $pre_bal,
								'cur_bal'          => $cur_bal,
								'amount'           => $amount,
								'discount'         => $discount,
								'pay_type'         => $pay_type,
								'payment_type'     => $payment_type,
								'description'      => $description,
								'amt_type'         => $amt_type,
								'amount_type'      => $amount_type,
								'collection_type'  => $collection_type,
								'date'             => date('d-m-Y', strtotime($date)),
								'time'             => date('h:i:s', strtotime($time)),
								'status'           => $status,

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
				        $response['data']         = $payment_list;
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

			else if($method == '_listEmployeePaymentCollectionPaginate')
			{
				$employee_id = $this->input->post('employee_id');
				$cur_date    = date('Y-m-d');

				$error = FALSE;
			    $errors = array();
				$required = array('employee_id');
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
			    	$limit  = $this->input->post('limit');
	    			$offset = $this->input->post('offset');

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
	    				'employee_id' => $employee_id,
	    				'date'        => $cur_date,
	    				'value_type'  => '2',
	    				'published'   => '1',
	    			);

		    		$column = 'id';
					$overalldatas = $this->payment_model->getOutletPayment($where, '', '', 'result', $like, '', '', '', $column);

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

					$column = 'id, distributor_id, bill_code, bill_no, amount, discount, amt_type, collection_type, date';

					$data_list = $this->payment_model->getOutletPayment($where, $limit, $offset, 'result', $like, '', $option, '', $column);

					if($data_list)
					{
						foreach ($data_list as $key => $value) {

							$payment_id      = !empty($val_3->id)?$val_3->id:'';
							$distributor_id  = !empty($val_3->distributor_id)?$val_3->distributor_id:'';
							$bill_code       = !empty($val_3->bill_code)?$val_3->bill_code:'';
							$bill_no         = !empty($val_3->bill_no)?$val_3->bill_no:'';
							$amount          = !empty($val_3->amount)?$val_3->amount:'0';
							$discount        = !empty($val_3->discount)?$val_3->discount:'0';
							$amt_type        = !empty($val_3->amt_type)?$val_3->amt_type:'';
							$collection_type = !empty($val_3->collection_type)?$val_3->collection_type:'';
							$date            = !empty($val_3->date)?$val_3->date:'';

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


						$payment_data[] = array(
							'payment_id'       => $payment_id,
							'distributor_name' => $distributor_id,
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

			else if($method == '_listOutletInvoicePayment')
			{
				$assign_id      = $this->input->post('assign_id');

				$error = FALSE;
			    $errors = array();
				$required = array('assign_id');
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
			    	$limit  = $this->input->post('limit');
	    			$offset = $this->input->post('offset');

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
		    			$where = array(
		    				'assign_id'  => $assign_id,
		    				'published'  => '1',
		    			);
		    		}
		    		else
		    		{
		    			$like = [];
		    			$where = array(
		    				'assign_id'  => $assign_id,
		    				'published'  => '1',
		    			);
		    		}

		    		$column = 'id';
					$overalldatas = $this->payment_model->getOutletPaymentDetails($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->payment_model->getOutletPaymentDetails($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$payment_list = [];
						foreach ($data_list as $key => $value) {
				            $bill_no = !empty($value->bill_no)?$value->bill_no:'';
				            $pre_bal = !empty($value->pre_bal)?$value->pre_bal:'0';
				            $cur_bal = !empty($value->cur_bal)?$value->cur_bal:'0';
				            $date    = !empty($value->date)?$value->date:'';

				            $payment_list[] = array(
				            	'bill_no' => $bill_no,
				            	'pre_bal' => $pre_bal,
				            	'cur_bal' => $cur_bal,
				            	'date'    => date('d-M-Y', strtotime($date)),
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
				        $response['data']         = $payment_list;
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

			else if($method == '_listOutletPaymentBill')
			{
				$assign_id = $this->input->post('assign_id');

				if(!empty($assign_id))
				{
					$where = array(
	    				'assign_id'  => $assign_id,
	    				'cur_bal !=' => '0',
	    				'status'     => '1',
	    				'published'  => '1',
	    			);

	    			$data_list = $this->payment_model->getOutletPaymentDetails($where);

	    			if($data_list)
	    			{
	    				$payment_list = [];
	    				foreach ($data_list as $key => $value) {
	    					$pay_id  = !empty($value->id)?$value->id:'';
							$bill_id = !empty($value->bill_id)?$value->bill_id:'';
							$bill_no = !empty($value->bill_no)?$value->bill_no:'';
							$pre_bal = !empty($value->pre_bal)?$value->pre_bal:'0';
							$cur_bal = !empty($value->cur_bal)?$value->cur_bal:'0';
							$amount  = !empty($value->amount)?$value->amount:'0';
							$bal_amt = !empty($value->bal_amt)?$value->bal_amt:'0';

							if($bal_amt != 0)
							{
								$payment_list[] = array(
									'pay_id'  => $pay_id,
									'bill_id' => $bill_id,
									'bill_no' => $bill_no,
									'pre_bal' => $pre_bal,
									'cur_bal' => $cur_bal,
									'amount'  => $amount,
								);
							}
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
				        $response['message'] = "Not Found"; 
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

			else if($method == '_detailOutletPaymentBill')
			{
				$payment_id = $this->input->post('payment_id');

				if(!empty($payment_id))
				{
					$where = array(
	    				'id'        => $payment_id,
	    				'status'    => '1',
	    				'published' => '1',
	    			);

	    			$data_list = $this->payment_model->getOutletPaymentDetails($where);

	    			if($data_list)
	    			{
	    				$payment_list = [];
	    				foreach ($data_list as $key => $value) {
	    					$pay_id  = !empty($value->id)?$value->id:'';
							$bill_id = !empty($value->bill_id)?$value->bill_id:'';
							$bill_no = !empty($value->bill_no)?$value->bill_no:'';
							$pre_bal = !empty($value->pre_bal)?$value->pre_bal:'0';
							$cur_bal = !empty($value->cur_bal)?$value->cur_bal:'0';
							$amount  = !empty($value->amount)?$value->amount:'0';
							$bal_amt = !empty($value->bal_amt)?$value->bal_amt:'0';

							$payment_list[] = array(
								'pay_id'  => $pay_id,
								'bill_id' => $bill_id,
								'bill_no' => $bill_no,
								'pre_bal' => $pre_bal,
								'cur_bal' => $cur_bal,
								'amount'  => $amount,
								'bal_amt' => $bal_amt,
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
				        $response['message'] = "Not Found"; 
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

			else if($method == '_deleteOutletPayment')
			{
				$auto_id = $this->input->post('payment_id');

				if(!empty($auto_id))
				{
					$payment_whr  = array(
						'id'        => $auto_id,
						'status'    => '1',
						'published' => '1',
					);

					$payment_data = $this->payment_model->getOutletPayment($payment_whr);

					if(!empty($payment_data))
					{
						$pay_val         = $payment_data[0];
						$assign_id       = !empty($pay_val->assign_id)?$pay_val->assign_id:'';
						$payment_id      = !empty($pay_val->payment_id)?$pay_val->payment_id:'';
						$employee_id     = !empty($pay_val->employee_id)?$pay_val->employee_id:'';
						$distributor_id  = !empty($pay_val->distributor_id)?$pay_val->distributor_id:'';
						$outlet_id       = !empty($pay_val->outlet_id)?$pay_val->outlet_id:'';
						$amount          = !empty($pay_val->amount)?$pay_val->amount:'0';
						$discount        = !empty($pay_val->discount)?$pay_val->discount:'0';
						$collection_type = !empty($pay_val->collection_type)?$pay_val->collection_type:'0';
						$total_val       = $amount + $discount;

						if($collection_type == 2)
						{
							// Outlet table details
							$outlet_whr = array(
								'id'        => $outlet_id,
								'published' => '1',
								'status'    => '1',
							);

							$outlet_col  = 'available_limit, current_balance';

							$outlet_data = $this->outlets_model->getOutlets($outlet_whr, '', '', 'result', '', '', '', '', $outlet_col);

							$available_limit = !empty($outlet_data[0]->available_limit)?$outlet_data[0]->available_limit:'0';
							$current_balance = !empty($outlet_data[0]->current_balance)?$outlet_data[0]->current_balance:'0';

							$new_avl_lmt = $available_limit - $total_val;
							$new_cur_bal = $current_balance + $total_val;

							$ovrOutlet_data = array(
								'available_limit' => $new_avl_lmt,
								'current_balance' => $new_cur_bal,
							);

							$ovrOutlet_whr = array('id' => $outlet_id);
					    	$ovrOutlet_upt = $this->outlets_model->outlets_update($ovrOutlet_data, $ovrOutlet_whr);

							// Outlet bill wise details
							$bill_whr = array(
								'id'        => $payment_id,
								'published' => '1',
								'status'    => '1',
							);

							$bill_data = $this->payment_model->getOutletPaymentDetails($bill_whr);

							$bill_pre_bal = !empty($bill_data[0]->pre_bal)?$bill_data[0]->pre_bal:'0';
							$pre_amount   = !empty($bill_data[0]->amount)?$bill_data[0]->amount:'0';
            				$bill_cur_bal = !empty($bill_data[0]->cur_bal)?$bill_data[0]->cur_bal:'0';

            				$new_bill_pre = $bill_pre_bal + $total_val;
            				$new_bill_cur = $bill_cur_bal + $total_val;

            				if($bill_pre_bal == $pre_amount)
            				{
            					$new_bal_val = $pre_amount;
            				}
            				else
            				{
            					$new_bal_val = $new_bill_pre;
            				}

            				$outletBill_data = array(
								'pre_bal' => $new_bal_val,
								'cur_bal' => $new_bill_cur,
								'bal_amt' => $new_bill_cur,
							);

							$outletBill_whr = array('id' => $payment_id);
					    	$outletBill_upt = $this->payment_model->outletPaymentDetails_update($outletBill_data, $outletBill_whr);

							// Distributor wise outlet details
							$dis_shop_whr  = array(
								'id'        => $assign_id,
								'published' => '1',
								'status'    => '1',
							);

							$dis_shop_data = $this->distributors_model->getDistributorOutlet($dis_shop_whr);

							$shop_pre_bal = !empty($dis_shop_data[0]->pre_bal)?$dis_shop_data[0]->pre_bal:'0';
            				$shop_cur_bal = !empty($dis_shop_data[0]->cur_bal)?$dis_shop_data[0]->cur_bal:'0';

            				$new_shop_pre = $shop_pre_bal + $total_val;
            				$new_shop_cur = $shop_cur_bal + $total_val;

            				$disOutlet_data = array(
								'pre_bal' => $new_shop_pre,
								'cur_bal' => $new_shop_cur,
							);

							$disOutlet_whr  = array('id' => $assign_id);
					    	$disOutlet_upt  = $this->distributors_model->distributorOutlet_update($disOutlet_data, $disOutlet_whr);
						}

						// Payment Table Update
				    	$payment_upt = array('published' => '0');
				    	$payment_whr = array('id' => $auto_id);
				    	$pay_update  = $this->payment_model->outletPayment_delete($payment_upt, $payment_whr);

				    	if($pay_update)
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
					        $response['message'] = "Not Success"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
					    }
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
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
				}
			}

			else if($method == '_updateOutletPayment')
			{
				$auto_id = $this->input->post('payment_id');

				if(!empty($auto_id))
				{
					$payment_whr  = array(
						'id'        => $auto_id,
						'published' => '1',
					);

					$payment_data = $this->payment_model->getOutletPayment($payment_whr);

					if(!empty($payment_data))
					{
						$pay_val         = $payment_data[0];
						$assign_id       = !empty($pay_val->assign_id)?$pay_val->assign_id:'';
						$payment_id      = !empty($pay_val->payment_id)?$pay_val->payment_id:'';
						$employee_id     = !empty($pay_val->employee_id)?$pay_val->employee_id:'';
						$distributor_id  = !empty($pay_val->distributor_id)?$pay_val->distributor_id:'';
						$outlet_id       = !empty($pay_val->outlet_id)?$pay_val->outlet_id:'';
						$amount          = !empty($pay_val->amount)?$pay_val->amount:'0';
						$discount        = !empty($pay_val->discount)?$pay_val->discount:'0';
						$financial_id    = !empty($pay_val->financial_id)?$pay_val->financial_id:'0';
						$total_val       = $amount + $discount;

						// Outlet table details
						$outlet_whr = array(
							'id'        => $outlet_id,
							'published' => '1',
							'status'    => '1',
						);

						$outlet_col  = 'available_limit, current_balance';

						$outlet_data = $this->outlets_model->getOutlets($outlet_whr, '', '', 'result', '', '', '', '', $outlet_col);

						$available_limit = !empty($outlet_data[0]->available_limit)?$outlet_data[0]->available_limit:'0';
						$current_balance = !empty($outlet_data[0]->current_balance)?$outlet_data[0]->current_balance:'0';

						$new_avl_lmt = $available_limit + $total_val;
						$new_cur_bal = $current_balance - $total_val;

						$ovrOutlet_data = array(
							'available_limit' => $new_avl_lmt,
							'current_balance' => $new_cur_bal,
						);

						$ovrOutlet_whr = array('id' => $outlet_id);
					    $ovrOutlet_upt = $this->outlets_model->outlets_update($ovrOutlet_data, $ovrOutlet_whr);

					    // Outlet bill wise details
						$bill_whr = array(
							'id'        => $payment_id,
							'published' => '1',
							'status'    => '1',
						);

						$bill_data = $this->payment_model->getOutletPaymentDetails($bill_whr);

						$bill_pre_bal = !empty($bill_data[0]->pre_bal)?$bill_data[0]->pre_bal:'0';
						$pre_amount   = !empty($bill_data[0]->amount)?$bill_data[0]->amount:'0';
        				$bill_cur_bal = !empty($bill_data[0]->cur_bal)?$bill_data[0]->cur_bal:'0';

        				$new_bill_pre = $bill_pre_bal - $total_val;
        				$new_bill_cur = $bill_cur_bal - $total_val;

        				if($bill_pre_bal == $pre_amount)
        				{
        					$new_bal_val = $pre_amount;
        				}
        				else
        				{
        					$new_bal_val = $new_bill_pre;
        				}

        				$outletBill_data = array(
							'pre_bal' => $new_bal_val,
							'cur_bal' => $new_bill_cur,
							'bal_amt' => $new_bill_cur,
						);

						$outletBill_whr = array('id' => $payment_id);
				    	$outletBill_upt = $this->payment_model->outletPaymentDetails_update($outletBill_data, $outletBill_whr);

				    	// Distributor wise outlet details
						$dis_shop_whr  = array(
							'id'        => $assign_id,
							'published' => '1',
							'status'    => '1',
						);

						$dis_shop_data = $this->distributors_model->getDistributorOutlet($dis_shop_whr);

						$shop_pre_bal = !empty($dis_shop_data[0]->pre_bal)?$dis_shop_data[0]->pre_bal:'0';
        				$shop_cur_bal = !empty($dis_shop_data[0]->cur_bal)?$dis_shop_data[0]->cur_bal:'0';

        				$new_shop_pre = $shop_pre_bal - $total_val;
        				$new_shop_cur = $shop_cur_bal - $total_val;

        				$disOutlet_data = array(
							'pre_bal' => $new_shop_pre,
							'cur_bal' => $new_shop_cur,
						);

						$disOutlet_whr  = array('id' => $assign_id);
				    	$disOutlet_upt  = $this->distributors_model->distributorOutlet_update($disOutlet_data, $disOutlet_whr);

				    	// Payment Table Update
						$collection_type = 2;

            			// Balance Sheet
				    	$master_where = array(
				    		'distributor_id' => $distributor_id,
				    		'bill_code'      => 'REC',
							'published'      => '1',
							'financial_id'   => $financial_id,
						);

						$bill_val = $this->payment_model->getOutletPayment($master_where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

						$count_val = leadingZeros($bill_val[0]->autoid, 5);
	            		$bill_num  = 'REC'.$count_val;
	            		$chq_pro   = '2';

				    	$payment_upt = array(
				    		'bill_code'       => 'REC',
				    		'bill_no'         => $bill_num,
				    		'collection_type' => $collection_type,
				    		'updatedate'      => date('Y-m-d H:i:s'),
				    	);


				    	$payment_whr = array('id' => $auto_id);
				    	$pay_update  = $this->payment_model->outletPayment_update($payment_upt, $payment_whr);

				    	if($pay_update)
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
					        $response['message'] = "Not Success"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
					    }
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

		// Distributor Payment
		// ***************************************************
		public function distributor_payment($param1="",$param2="",$param3="")
		{
			// Financial Year Details
			$option['order_by']   = 'id';
			$option['disp_order'] = 'DESC';

			$where = array(
				'status'    => '1', 
				'published' => '1',
			);

			$data_list = $this->commom_model->getfinancial($where, '1', '0', 'result', '', '', $option);

			$financial_id = !empty($data_list[0]->id)?$data_list[0]->id:'';

			$method = $this->input->post('method');

			if($method == '_addDistributorPayment')
			{
				$distributor_id = $this->input->post('distributor_id');
				$pay_id         = $this->input->post('pay_id');
				$amount         = $this->input->post('amount');
				$discount       = $this->input->post('discount');
				$entry_date     = $this->input->post('entry_date');
				$amt_type       = $this->input->post('amt_type');
				$description    = $this->input->post('description');
				$bank_name      = $this->input->post('bank_name');
				$cheque_no      = $this->input->post('cheque_no');
				$collect_date   = $this->input->post('collect_date');
				$penalty_amt    = $this->input->post('penalty_amt');
				$cheque_status  = $this->input->post('cheque_status');
				$date           = date('Y-m-d');
				$time           = date('H:i:s');
				$c_date         = date('Y-m-d H:i:s');

				$error = FALSE;
			    $errors = array();
				$required = array('pay_id', 'distributor_id', 'amount', 'entry_date', 'amt_type');

				if($amt_type == 2)
			    {
			    	array_push($required, 'bank_name', 'cheque_no', 'collect_date');
			    }
			    else if($amt_type == 3)
			    {
			    	array_push($required, 'description');
			    }

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
			    	if(!empty($discount))
			    	{
			    		$discount_val = $discount;
			    	}
			    	else
			    	{
			    		$discount_val = '0';
			    	}

			    	// Total Amount
					$total_val   = $amount + $discount_val;


					// Distributor Bill Details
					$bill_whr = array(
						'id'        => $pay_id,
			    		'published' => '1',
			    		'status'    => '1',
					);

					$bill_col  = 'bill_id, bill_no, pre_bal, cur_bal, bal_amt';

					$bill_data = $this->payment_model->getDistributorPaymentDetails($bill_whr, '', '', 'result', '', '', '', '', $bill_col);

					$bill_id = !empty($bill_data[0]->bill_id)?$bill_data[0]->bill_id:'';
					$bill_no = !empty($bill_data[0]->bill_no)?$bill_data[0]->bill_no:'';
					$pre_bal = !empty($bill_data[0]->pre_bal)?$bill_data[0]->pre_bal:'0';
					$cur_bal = !empty($bill_data[0]->cur_bal)?$bill_data[0]->cur_bal:'0';
					$bal_amt = !empty($bill_data[0]->bal_amt)?$bill_data[0]->bal_amt:'0';

					if($bal_amt >= $total_val)
					{
						// Distributor Table Balance Update
				    	$distributor_whr  = array(
				    		'id'        => $distributor_id,
				    		'published' => '1',
				    	);

				    	$distributor_col  = 'available_limit, current_balance';

						$distributor_data = $this->distributors_model->getDistributors($distributor_whr, '', '', 'result', '', '', '', '', $distributor_col);

						$available_limit = !empty($distributor_data[0]->available_limit)?$distributor_data[0]->available_limit:'0';
					    $current_balance = !empty($distributor_data[0]->current_balance)?$distributor_data[0]->current_balance:'0';

					    // New Outlet Main Balance
				    	$new_avl_balance = $available_limit + $total_val;
				    	$new_cur_balance = $current_balance - $total_val;

				    	if($current_balance >= $total_val)
				    	{
				    		// New Outlet Main Balance
					    	$new_inv_balance = $cur_bal - $total_val;
					    	$new_balance_amt = $bal_amt - $total_val;

				    		// Vendor Invoice Payment Table Update
				    		$disInvoice_val = array(
				    			'pre_bal' => $cur_bal,
				    			'cur_bal' => $new_inv_balance,
				    			'bal_amt' => $new_balance_amt,
				    		);

				    		if($amt_type != 2)
				    		{
				    			$disInvoice_whr = array('id' => $pay_id);
						    	$disInvoice_upt = $this->payment_model->distributorPaymentDetails_update($disInvoice_val, $disInvoice_whr);
				    		}

				    		$distributor_upt = array(
					    		'available_limit' => $new_avl_balance,
					    		'current_balance' => $new_cur_balance,
					    	);

				    		if($amt_type != 2)
				    		{
				    			$distributor_whr = array('id' => $distributor_id);
						    	$dis_update      = $this->distributors_model->distributors_update($distributor_upt, $distributor_whr);
				    		}

		            		if($amt_type != 2)
		            		{
		            			$collection_type = 2;

		            			// Balance Sheet
						    	$master_where = array(
						    		'bill_code'    => 'REC',
									'published'    => '1',
									'financial_id' => $financial_id,
								);

								$bill_val = $this->payment_model->getDistributorPayment($master_where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

								$count_val = leadingZeros($bill_val[0]->autoid, 5);
			            		$bill_num  = 'REC'.$count_val;
			            		$bill_code = 'REC';
			            		$chq_pro   = '2';
		            		}
		            		else
		            		{
		            			$collection_type = 1;

		            			// Balance Sheet
						    	$master_where = array(
						    		'bill_code'    => 'CHQ',
									'published'    => '1',
									'financial_id' => $financial_id,
								);

								$bill_val = $this->payment_model->getDistributorPayment($master_where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

								$count_val = leadingZeros($bill_val[0]->autoid, 5);
			            		$bill_num  = 'CHQ'.$count_val;
			            		$bill_code = 'CHQ';
			            		$chq_pro   = '1';
		            		}

		            		$new_balance = $current_balance - $total_val;

		            		// Payment Insert
					    	$ins_data = array(
					    		'distributor_id'  => $distributor_id,
					    		'payment_id'      => $pay_id,
					    		'bill_code'       => $bill_code,
					    		'bill_id'         => $bill_id,
					    		'bill_no'         => $bill_num,
					    		'pre_bal'         => $current_balance,
					    		'cur_bal'         => round($new_balance),
					    		'amount'          => $amount,
					    		'discount'        => $discount,
					    		'pay_type'        => 1,
					    		'description'     => $description,
					    		'bank_name'       => $bank_name,
					    		'cheque_no'       => $cheque_no,
					    		'collect_date'    => date('Y-m-d', strtotime($collect_date)),
					    		'amt_type'        => $amt_type,
					    		'collection_type' => $collection_type,
					    		'cheque_process'  => $chq_pro,
					    		'value_type'      => 2,
					    		'financial_id'    => $financial_id,
					    		'date'            => date('Y-m-d', strtotime($entry_date)),
					    		'time'            => $time,
					    		'createdate'      => $c_date,
					    	);

					    	$payment_insert = $this->payment_model->distributorPayment_insert($ins_data);

					    	// Pre Payment Update
					    	$prePayment_data = array(
					    		'status' => '0'
					    	);

					    	$prePayment_whr  = array(
					    		'id !='              => $payment_insert,
					    		'distributor_id'     => $distributor_id,
					    		'collection_type !=' => '1',
					    	);

					    	$payment_update = $this->payment_model->distributorPayment_update($prePayment_data, $prePayment_whr);

						    if($payment_insert)
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
						        $response['message'] = "Not Success"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
				    	}
				    	else
					    {
		        			$response['status']  = 0;
					        $response['message'] = "Invalid Amount"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
					    }
					}
					else
				    {
	        			$response['status']  = 0;
				        $response['message'] = "Invalid Amount"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
			    }
			}

			else if($method == '_editDistributorPayment')
			{
				$auto_id        = $this->input->post('auto_id');
				$distributor_id = $this->input->post('distributor_id');
				$entry_date     = $this->input->post('entry_date');
				$description    = $this->input->post('description');
				$penalty_amt    = $this->input->post('penalty_amt');
				$bank_charge    = $this->input->post('bank_charge');
				$cheque_status  = $this->input->post('cheque_status');
				$date           = date('Y-m-d');
				$time           = date('H:i:s');
				$c_date         = date('Y-m-d H:i:s');

				$error = FALSE;
			    $errors = array();
				$required = array('auto_id', 'distributor_id', 'entry_date', 'cheque_status', 'description');

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
			    	$payment_whr  = array(
						'id'        => $auto_id,
						'published' => '1',
					);

					$payment_col  = 'id, payment_id, amount, discount, bank_name, cheque_no, collect_date, amt_type, date';
					$payment_data = $this->payment_model->getDistributorPayment($payment_whr, '', '', 'result', '', '', '', '', $payment_col);

					if(!empty($payment_data))
					{
						$pay_data     = $payment_data[0];
						$db_auto_id      = !empty($pay_data->id)?$pay_data->id:'';
			            $db_payment_id   = !empty($pay_data->payment_id)?$pay_data->payment_id:'';
			            $db_amount       = !empty($pay_data->amount)?$pay_data->amount:'0';
			            $db_discount     = !empty($pay_data->discount)?$pay_data->discount:'0';
			            $db_bank_name    = !empty($pay_data->bank_name)?$pay_data->bank_name:'';
			            $db_cheque_no    = !empty($pay_data->cheque_no)?$pay_data->cheque_no:'';
			            $db_collect_date = !empty($pay_data->collect_date)?$pay_data->collect_date:'';
			            $db_amt_type     = !empty($pay_data->amt_type)?$pay_data->amt_type:'';
			            $db_entry_date   = !empty($pay_data->date)?$pay_data->date:'';

			            if(!empty($entry_date))
			            {
			            	$ins_date = date('Y-m-d', strtotime($entry_date));
			            }
			            else
			            {
			            	$ins_date = date('Y-m-d', strtotime($db_entry_date));
			            }

			            if($cheque_status == 1)
			            {
			            	$db_total_amt = $db_amount + $db_discount;

			            	// Invoice Details
			            	$inv_whr = array('id' => $db_payment_id, 'published' => '1');
			            	$inv_col = 'bill_id, cur_bal, bal_amt';
			            	$inv_val = $this->payment_model->getDistributorPaymentDetails($inv_whr, '', '', 'result', '', '', '', '', $inv_col);

			            	$dB_bill_id = !empty($inv_val[0]->bill_id)?$inv_val[0]->bill_id:'0';
			            	$cur_bal    = !empty($inv_val[0]->cur_bal)?$inv_val[0]->cur_bal:'0';
			            	$bal_amt    = !empty($inv_val[0]->bal_amt)?$inv_val[0]->bal_amt:'0';
			            	$new_bal    = $bal_amt - $db_total_amt;

			            	if($bal_amt >= $db_total_amt)
			            	{
			            		// Distributor Table Balance Update
						    	$distributor_whr  = array(
						    		'id'        => $distributor_id,
						    		'published' => '1',
						    	);

						    	$distributor_col  = 'available_limit, current_balance';

								$distributor_data = $this->distributors_model->getDistributors($distributor_whr, '', '', 'result', '', '', '', '', $distributor_col);

								$available_limit = !empty($distributor_data[0]->available_limit)?$distributor_data[0]->available_limit:'0';
							    $current_balance = !empty($distributor_data[0]->current_balance)?$distributor_data[0]->current_balance:'0';

							    // New Outlet Main Balance
						    	$new_avl_balance = $available_limit + $db_total_amt;
						    	$new_cur_balance = $current_balance - $db_total_amt;

						    	if($current_balance >= $db_total_amt)
						    	{
						    		$disInvoice_val = array(
					            		'bal_amt'    => $new_bal,
					            		'updatedate' => date('Y-m-d H:i:s')
					            	);

					            	$disInvoice_whr = array('id' => $db_payment_id);
								    $disInvoice_upt = $this->payment_model->distributorPaymentDetails_update($disInvoice_val, $disInvoice_whr);


							    	$distributor_upt = array(
							    		'available_limit' => $new_avl_balance,
							    		'current_balance' => $new_cur_balance,
							    	);

							    	$distributor_whr = array('id' => $distributor_id);
								    $dis_update      = $this->distributors_model->distributors_update($distributor_upt, $distributor_whr);

								    $disPayment_val = array('collection_type' => '2'); 
					    			$disPayment_whr = array('id' => $db_auto_id);
						    		$disPayment_upt = $this->payment_model->distributorPayment_update($disPayment_val, $disPayment_whr);

								    $collection_type = 2;

			            			// Balance Sheet
							    	$master_where = array(
							    		'bill_code'    => 'REC',
										'published'    => '1',
										'financial_id' => $financial_id,
									);

									$bill_val = $this->payment_model->getDistributorPayment($master_where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

									$count_val = leadingZeros($bill_val[0]->autoid, 5);
				            		$bill_num  = 'REC'.$count_val;
				            		$bill_code = 'REC';

				            		$new_balance = $current_balance - $db_total_amt;

				            		// Payment Insert
							    	$ins_data = array(
							    		'distributor_id'  => $distributor_id,
							    		'payment_id'      => $db_payment_id,
							    		'bill_code'       => $bill_code,
							    		'bill_id'         => $dB_bill_id,
							    		'bill_no'         => $bill_num,
							    		'pre_bal'         => $current_balance,
							    		'cur_bal'         => round($new_balance),
							    		'amount'          => $db_total_amt,
							    		'pay_type'        => 1,
							    		'description'     => $description,
							    		'bank_name'       => $db_bank_name,
							    		'cheque_no'       => $db_cheque_no,
							    		'collect_date'    => date('Y-m-d', strtotime($db_collect_date)),
							    		'cheque_status'   => $cheque_status,
							    		'collect_date'    => date('Y-m-d'),
							    		'amt_type'        => $db_amt_type,
							    		'collection_type' => $collection_type,
							    		'cheque_process'  => 2,
							    		'value_type'      => 2,
							    		'financial_id'    => $financial_id,
							    		'date'            => date('Y-m-d', strtotime($ins_date)),
							    		'time'            => $time,
							    		'createdate'      => $c_date,
							    	);

							    	$payment_insert = $this->payment_model->distributorPayment_insert($ins_data);

							    	// Pre Payment Update
							    	$prePayment_data = array(
							    		'status' => '0'
							    	);

							    	$prePayment_whr  = array(
							    		'id !='              => $payment_insert,
							    		'distributor_id'     => $distributor_id,
							    		'collection_type !=' => '1',
							    	);

							    	$payment_update = $this->payment_model->distributorPayment_update($prePayment_data, $prePayment_whr);

							    	// Cheque Payment Update
							    	$chequePay_data = array('payment_type' => '2');
							    	$chequePay_whr  = array('id' => $db_auto_id);
							    	$chequePay_upt  = $this->payment_model->distributorPayment_update($chequePay_data, $chequePay_whr);

								    if($payment_insert)
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
								        $response['message'] = "Not Success"; 
								        $response['data']    = [];
								        echo json_encode($response);
								        return; 
								    }
						    	}
						    	else
							    {
				        			$response['status']  = 0;
							        $response['message'] = "Invalid Amount"; 
							        $response['data']    = [];
							        echo json_encode($response);
							        return; 
							    }
			            	}
			            	else
			            	{
			            		$response['status']  = 0;
						        $response['message'] = "Invalid Amount"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
			            	}
			            }
			            else
			            {
			            	// Invoice Details
			            	$inv_whr = array('id' => $db_payment_id, 'published' => '1');
			            	$inv_col = 'bill_id, bal_amt';
			            	$inv_val = $this->payment_model->getDistributorPaymentDetails($inv_whr, '', '', 'result', '', '', '', '', $inv_col);

			            	$dB_bill_id = !empty($inv_val[0]->bill_id)?$inv_val[0]->bill_id:'0';
			            	$bal_amt    = !empty($inv_val[0]->bal_amt)?$inv_val[0]->bal_amt:'0';

			            	$tot_penalty  = !empty($penalty_amt)?$penalty_amt:'0';
			            	$tot_bankChar = !empty($bank_charge)?$bank_charge:'0';
			            	$total_value  = $tot_penalty + $tot_bankChar;
			            	$new_bal      = $bal_amt + $total_value;

			            	$disInvoice_val = array(
			            		'bal_amt'    => $new_bal,
			            		'updatedate' => date('Y-m-d H:i:s')
			            	);

			            	$disInvoice_whr = array('id' => $db_payment_id);
						    $disInvoice_upt = $this->payment_model->distributorPaymentDetails_update($disInvoice_val, $disInvoice_whr);

						    // Distributor Table Balance Update
					    	$distributor_whr  = array(
					    		'id'        => $distributor_id,
					    		'published' => '1',
					    	);

					    	$distributor_col  = 'available_limit, current_balance';

							$distributor_data = $this->distributors_model->getDistributors($distributor_whr, '', '', 'result', '', '', '', '', $distributor_col);

							$available_limit = !empty($distributor_data[0]->available_limit)?$distributor_data[0]->available_limit:'0';
						    $current_balance = !empty($distributor_data[0]->current_balance)?$distributor_data[0]->current_balance:'0';

						    // New Outlet Main Balance
					    	$new_avl_balance = $available_limit - $total_value;
					    	$new_cur_balance = $current_balance + $total_value;

					    	$distributor_upt = array(
					    		'available_limit' => $new_avl_balance,
					    		'current_balance' => $new_cur_balance,
					    	);

					    	$distributor_whr = array('id' => $distributor_id);
						    $dis_update      = $this->distributors_model->distributors_update($distributor_upt, $distributor_whr);

						    $disPayment_val = array('collection_type' => '2'); 
			    			$disPayment_whr = array('id' => $db_auto_id);
				    		$disPayment_upt = $this->payment_model->distributorPayment_update($disPayment_val, $disPayment_whr);

						    // Balance Sheet
					    	$master_where = array(
					    		'bill_code'    => 'PEN',
								'published'    => '1',
								'financial_id' => $financial_id,
							);

							$bill_val = $this->payment_model->getDistributorPayment($master_where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

							$count_val = leadingZeros($bill_val[0]->autoid, 5);
		            		$bill_num  = 'PEN'.$count_val;
		            		$bill_code = 'PEN';

		            		$new_balance = $current_balance + $total_value;

		            		// Payment Insert
					    	$ins_data = array(
					    		'distributor_id'  => $distributor_id,
					    		'payment_id'      => $db_payment_id,
					    		'bill_code'       => $bill_code,
					    		'bill_id'         => $dB_bill_id,
					    		'bill_no'         => $bill_num,
					    		'pre_bal'         => $current_balance,
					    		'cur_bal'         => round($new_balance),
					    		'amount'          => $total_value,
					    		'pay_type'        => 1,
					    		'description'     => $description,
					    		'collect_date'    => date('Y-m-d'),
					    		'penalty_amt'     => $penalty_amt,
					    		'cheque_status'   => $cheque_status,
					    		'bank_charge'     => $bank_charge,
					    		'amt_type'        => 3,
			    				'penalty_amt'     => $tot_penalty,
		    					'bank_charge'     => $tot_bankChar,
					    		'collection_type' => 2,
					    		'cheque_process'  => 2,
					    		'value_type'      => 2,
					    		'status'          => 0,
					    		'financial_id'    => $financial_id,
					    		'date'            => date('Y-m-d', strtotime($ins_date)),
					    		'time'            => $time,
					    		'createdate'      => $c_date,
					    	);

					    	$payment_insert = $this->payment_model->distributorPayment_insert($ins_data);

					    	// Pre Payment Update
					    	$prePayment_data = array(
					    		'status' => '0'
					    	);

					    	$prePayment_whr  = array(
					    		'id !='              => $payment_insert,
					    		'distributor_id'     => $distributor_id,
					    		'collection_type !=' => '1',
					    	);

					    	$payment_update = $this->payment_model->distributorPayment_update($prePayment_data, $prePayment_whr);

						    if($payment_insert)
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
						        $response['message'] = "Not Success"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
			            }
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

			else if($method == '_listDistributorPaymentPaginate')
			{
				$distributor_id = $this->input->post('distributor_id');

				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id');
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
			    	$limit  = $this->input->post('limit');
	    			$offset = $this->input->post('offset');

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
		    			// 'bill_code !='   => 'PEN',
	    				'distributor_id' => $distributor_id,
	    				'value_type'     => '2',
	    				// 'payment_type'   => '1',
	    				'published'      => '1',
	    			);

		    		$column = 'id';
					$overalldatas = $this->payment_model->getDistributorPayment($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->payment_model->getDistributorPayment($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$payment_list = [];
						foreach ($data_list as $key => $value) {
								
							$payment_id     = !empty($value->id)?$value->id:'';
							$bill_id        = !empty($value->payment_id)?$value->payment_id:'';
							$distributor_id = !empty($value->distributor_id)?$value->distributor_id:'';
							$bill_code      = !empty($value->bill_code)?$value->bill_code:'';
							$bill_no        = !empty($value->bill_no)?$value->bill_no:'';
							$pre_bal        = !empty($value->pre_bal)?$value->pre_bal:'';
							$cur_bal        = !empty($value->cur_bal)?$value->cur_bal:'';
							$amount         = !empty($value->amount)?$value->amount:'';
							$discount       = !empty($value->discount)?$value->discount:'';
							$pay_type       = !empty($value->pay_type)?$value->pay_type:'';
							$description    = !empty($value->description)?$value->description:'';
							$amt_type       = !empty($value->amt_type)?$value->amt_type:'';
							$collect_type   = !empty($value->collection_type)?$value->collection_type:'';
							$cheque_process = !empty($value->cheque_process)?$value->cheque_process:'';
							$penalty_amt    = !empty($value->penalty_amt)?$value->penalty_amt:'0';
							$bank_charge    = !empty($value->bank_charge)?$value->bank_charge:'0';
							$date           = !empty($value->date)?$value->date:'';
							$time           = !empty($value->time)?$value->time:'';
							$status         = !empty($value->status)?$value->status:'0';

							// Payment typ details
							if($pay_type == 1)
							{
								$payment_type = 'Debit';
							}
							else
							{
								$payment_type = 'Credit';
							}

							// Amount Type
							if($amt_type == 1)
							{
								$amount_type = 'Cash';
							}
							else if($amt_type == 2)
							{
								$amount_type = 'Cheque';
							}
							else
							{
								$amount_type = 'Others';
							}

							// Invoice Details
							$inv_whr = array('id' => $bill_id);
							$inv_col = 'invoice_no';
							$inv_val = $this->invoice_model->getDistributorInvoice($inv_whr, '', '', 'result', '', '', '', '', $inv_col);

							$inv_num = !empty($inv_val[0]->invoice_no)?$inv_val[0]->invoice_no:'';

							$payment_list[] = array(
								'payment_id'      => $payment_id,
								'distributor_id'  => $distributor_id,
								'invoice_no'      => $inv_num,
								'bill_code'       => $bill_code,
								'bill_no'         => $bill_no,
								'pre_bal'         => $pre_bal,
								'cur_bal'         => $cur_bal,
								'amount'          => $amount,
								'discount'        => $discount,
								'pay_type'        => $pay_type,
								'payment_type'    => $payment_type,
								'description'     => $description,
								'amt_type'        => $amt_type,
								'amount_type'     => $amount_type,
								'penalty_amt'     => $penalty_amt,
								'bank_charge'     => $bank_charge,
								'collection_type' => $collect_type,
								'cheque_process'  => $cheque_process,
								'date'            => date('d-m-Y', strtotime($date)),
								'time'            => date('h:i:s', strtotime($time)),
								'status'          => $status,
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
				        $response['data']         = $payment_list;
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

			else if($method == '_distributorPaymentDetails')
			{
				$payment_id = $this->input->post('payment_id');

				if(!empty($payment_id))
				{
					$payment_whr  = array(
						'id'        => $payment_id,
						'published' => '1',
					);

					$payment_col  = 'id, payment_id, amount, discount, bank_name, cheque_no, collect_date, amt_type, date';
					$payment_data = $this->payment_model->getDistributorPayment($payment_whr, '', '', 'result', '', '', '', '', $payment_col);

					if(!empty($payment_data))
					{
						$pay_data     = $payment_data[0];
						$auto_id      = !empty($pay_data->id)?$pay_data->id:'';
			            $payment_id   = !empty($pay_data->payment_id)?$pay_data->payment_id:'';
			            $amount       = !empty($pay_data->amount)?$pay_data->amount:'';
			            $discount     = !empty($pay_data->discount)?$pay_data->discount:'';
			            $bank_name    = !empty($pay_data->bank_name)?$pay_data->bank_name:'';
			            $cheque_no    = !empty($pay_data->cheque_no)?$pay_data->cheque_no:'';
			            $collect_date = !empty($pay_data->collect_date)?$pay_data->collect_date:'';
			            $amt_type     = !empty($pay_data->amt_type)?$pay_data->amt_type:'';
			            $entry_date   = !empty($pay_data->date)?$pay_data->date:'';

			            $pay_result = array(
			            	'auto_id'      => $auto_id,
				            'payment_id'   => $payment_id,
				            'amount'       => $amount,
				            'discount'     => $discount,
				            'bank_name'    => $bank_name,
				            'cheque_no'    => $cheque_no,
				            'collect_date' => date('d-m-Y', strtotime($collect_date)),
				            'amt_type'     => $amt_type,
				            'entry_date'   => date('d-m-Y', strtotime($entry_date)),
			            );

			            $response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $pay_result;
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

			else if($method == '_listDistributorPaymentBill')
			{
				$distributor_id = $this->input->post('distributor_id');

				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id');
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
			    	$where = array(
	    				'distributor_id' => $distributor_id,
	    				'bal_amt !='     => '0',
	    				'status'         => '1',
	    				'published'      => '1',
	    			);

	    			$data_list = $this->payment_model->getDistributorPaymentDetails($where);

	    			if($data_list)
	    			{
	    				$payment_list = [];
	    				foreach ($data_list as $key => $value) {
	    					$pay_id  = !empty($value->id)?$value->id:'';
							$bill_id = !empty($value->bill_id)?$value->bill_id:'';
							$bill_no = !empty($value->bill_no)?$value->bill_no:'';
							$pre_bal = !empty($value->pre_bal)?$value->pre_bal:'0';
							$cur_bal = !empty($value->cur_bal)?$value->cur_bal:'0';
							$amount  = !empty($value->amount)?$value->amount:'0';
							$bal_amt = !empty($value->bal_amt)?$value->bal_amt:'0';

							if($bal_amt != 0)
							{
								$payment_list[] = array(
									'pay_id'  => $pay_id,
									'bill_id' => $bill_id,
									'bill_no' => $bill_no,
									'pre_bal' => $pre_bal,
									'cur_bal' => $cur_bal,
									'amount'  => $amount,
									'bal_amt' => $bal_amt,
								);
							}
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
				        $response['message'] = "Not Found"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
					}
			    }
			}

			else if($method == '_detailDistributorPaymentBill')
			{
				$payment_id = $this->input->post('payment_id');

				$error = FALSE;
			    $errors = array();
				$required = array('payment_id');
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
			    	$where = array(
	    				'id'        => $payment_id,
	    				'status'    => '1',
	    				'published' => '1',
	    			);

	    			$data_list = $this->payment_model->getDistributorPaymentDetails($where);

	    			if($data_list)
	    			{
	    				$payment_list = [];
	    				foreach ($data_list as $key => $value) {
	    					$pay_id  = !empty($value->id)?$value->id:'';
							$bill_id = !empty($value->bill_id)?$value->bill_id:'';
							$bill_no = !empty($value->bill_no)?$value->bill_no:'';
							$pre_bal = !empty($value->pre_bal)?$value->pre_bal:'0';
							$cur_bal = !empty($value->cur_bal)?$value->cur_bal:'0';
							$amount  = !empty($value->amount)?$value->amount:'0';
							$bal_amt = !empty($value->bal_amt)?$value->bal_amt:'0';

							$payment_list[] = array(
								'pay_id'  => $pay_id,
								'bill_id' => $bill_id,
								'bill_no' => $bill_no,
								'pre_bal' => $pre_bal,
								'cur_bal' => $cur_bal,
								'amount'  => $amount,
								'bal_amt' => $bal_amt,
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
				        $response['message'] = "Not Found"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
					}
			    }
			}

			else if($method == '_deleteDistributorPayment')
			{
				$payment_id = $this->input->post('payment_id');

				if(!empty($payment_id))
				{
					$payment_whr  = array(
						'id'        => $payment_id,
						'published' => '1',
						'status'    => '1',
					);

					$payment_data = $this->payment_model->getDistributorPayment($payment_whr);

					if(!empty($payment_data))
					{
						$pay_val         = $payment_data[0];
						$auto_id         = !empty($pay_val->id)?$pay_val->id:'';
						$distributor_id  = !empty($pay_val->distributor_id)?$pay_val->distributor_id:'';
						$bill_id         = !empty($pay_val->bill_id)?$pay_val->bill_id:'0';
						$pre_bal         = !empty($pay_val->pre_bal)?$pay_val->pre_bal:'0';
						$cur_bal         = !empty($pay_val->cur_bal)?$pay_val->cur_bal:'0';
						$amount          = !empty($pay_val->amount)?$pay_val->amount:'0';
						$discount        = !empty($pay_val->discount)?$pay_val->discount:'0';
						$collect_type    = !empty($pay_val->collection_type)?$pay_val->collection_type:'0';
						$total_val       = $amount + $discount;

						if($collect_type == 2)
						{
							// Distributor Invoice Payment Details
							$bill_whr = array(
								'distributor_id' => $distributor_id,
								'bill_id'        => $bill_id,
					    		'published'      => '1',
					    		'status'         => '1',
							);

							$bill_col  = 'id, bill_id, bill_no, pre_bal, cur_bal, bal_amt';

							$bill_data = $this->payment_model->getDistributorPaymentDetails($bill_whr, '', '', 'result', '', '', '', '', $bill_col);

							$pay_id       = !empty($bill_data[0]->id)?$bill_data[0]->id:'';
							$bill_pre_bal = !empty($bill_data[0]->pre_bal)?$bill_data[0]->pre_bal:'0';
							$bill_cur_bal = !empty($bill_data[0]->cur_bal)?$bill_data[0]->cur_bal:'0';
							$bill_bal_amt = !empty($bill_data[0]->bal_amt)?$bill_data[0]->bal_amt:'0';

							// Vendor Invoice Payment Update
							$new_inv_balance = $bill_cur_bal + $total_val;
							$new_balance_amt = $bill_bal_amt + $total_val;

							$disInvoice_val = array(
				    			'pre_bal' => $bill_cur_bal,
				    			'cur_bal' => $new_inv_balance,
				    			'bal_amt' => $new_balance_amt,
				    		);

				    		$disInvoice_whr = array('id' => $pay_id);
					    	$disInvoice_upt = $this->payment_model->distributorPaymentDetails_update($disInvoice_val, $disInvoice_whr);

				    		// Distributor Main Table
				    		$dist_whr = array(
				    			'id' => $distributor_id,
				    		);

				    		$dist_col  = 'credit_limit, available_limit, pre_limit, current_balance';

				    		$dist_data = $this->distributors_model->getDistributors($dist_whr, '', '', 'result', '', '', '', '', $dist_col);

				    		$dis_val   = $dist_data[0];

				    		$credit_limit    = !empty($dis_val->credit_limit)?$dis_val->credit_limit:'';
				            $available_limit = !empty($dis_val->available_limit)?$dis_val->available_limit:'';
				            $pre_limit       = !empty($dis_val->pre_limit)?$dis_val->pre_limit:'';
				            $current_balance = !empty($dis_val->current_balance)?$dis_val->current_balance:'';

				            $new_available_limit = $available_limit - $total_val;
				            $new_current_balance = $current_balance + $total_val;

				            $ovrDistributor_upt = array(
								'available_limit' => $new_available_limit,
								'current_balance' => $new_current_balance,
							);

							$distributor_whr    = array('id' => $distributor_id);
					    	$distributor_update = $this->distributors_model->distributors_update($ovrDistributor_upt, $distributor_whr);
						}

				    	// Payment Table Update
				    	$delete_val = array('published' => '0');
				    	$delete_whr = array('id' => $auto_id);
				    	$pay_update  = $this->payment_model->distributorPayment_delete($delete_val, $delete_whr);

						if($pay_update)
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
					        $response['message'] = "Not Success"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
					    }
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

		// Vendor Payment
		// ***************************************************
		public function vendor_payment($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			// Financial Year Details
			$option['order_by']   = 'id';
			$option['disp_order'] = 'DESC';

			$where = array(
				'status'    => '1', 
				'published' => '1',
			);

			$data_list = $this->commom_model->getfinancial($where, '1', '0', 'result', '', '', $option);

			$financial_id = !empty($data_list[0]->id)?$data_list[0]->id:'';

			if($method == '_addVendorPayment')
			{
				$vendor_id     = $this->input->post('vendor_id');
				$pay_id        = $this->input->post('pay_id');
				$amount        = $this->input->post('amount');
				$discount      = $this->input->post('discount');
				$entry_date    = $this->input->post('entry_date');
				$amt_type      = $this->input->post('amt_type');
				$bank_name     = $this->input->post('bank_name');
				$cheque_no     = $this->input->post('cheque_no');
				$collect_date  = $this->input->post('collect_date');
				$penalty_amt   = $this->input->post('penalty_amt');
				$cheque_status = $this->input->post('cheque_status');
				$description   = $this->input->post('description');
				$date          = date('Y-m-d');
				$time          = date('H:i:s');
				$c_date        = date('Y-m-d H:i:s');

				$error = FALSE;
			    $errors = array();
				$required = array('vendor_id', 'pay_id', 'amount', 'entry_date', 'amt_type');

				if($amt_type == 2)
			    {
			    	array_push($required, 'bank_name', 'cheque_no', 'collect_date');
			    }
			    else if($amt_type == 3 || $amt_type == 4)
			    {
			    	array_push($required, 'description');
			    }

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
			    	if(!empty($discount))
			    	{
			    		$discount_val = $discount;
			    	}
			    	else
			    	{
			    		$discount_val = '0';
			    	}

			    	// Total Amount
					$total_val   = $amount + $discount_val;

					// Vendor Bill Details
					$bill_whr = array(
						'id'        => $pay_id,
			    		'published' => '1',
			    		'status'    => '1',
					);

					$bill_col  = 'bill_id, bill_no, pre_bal, cur_bal, bal_amt';

					$bill_data = $this->payment_model->getVendorPaymentDetails($bill_whr, '', '', 'result', '', '', '', '', $bill_col);

					$bill_id = !empty($bill_data[0]->bill_id)?$bill_data[0]->bill_id:'';
					$bill_no = !empty($bill_data[0]->bill_no)?$bill_data[0]->bill_no:'';
					$pre_bal = !empty($bill_data[0]->pre_bal)?$bill_data[0]->pre_bal:'0';
					$cur_bal = !empty($bill_data[0]->cur_bal)?$bill_data[0]->cur_bal:'0';
					$bal_amt = !empty($bill_data[0]->bal_amt)?$bill_data[0]->bal_amt:'0';

					if($bal_amt >= $total_val)
					{
						$vendor_col  = 'pre_balance, cur_balance';

						// Vendor Table Balance Update
				    	$vendor_whr  = array(
				    		'id'        => $vendor_id,
				    		'published' => '1',
				    	);

				    	$vendor_col  = 'pre_balance, cur_balance';

						$vendor_data = $this->vendors_model->getVendors($vendor_whr, '', '', 'result', '', '', '', '', $vendor_col);

						$pre_balance = !empty($vendor_data[0]->pre_balance)?$vendor_data[0]->pre_balance:'0';
					    $cur_balance = !empty($vendor_data[0]->cur_balance)?$vendor_data[0]->cur_balance:'0';

					    // New Outlet Main Balance
				    	$new_cur_balance = $cur_balance - $total_val;
				    	$new_inv_balance = $cur_bal - $total_val;
				    	$new_balance_amt = $bal_amt - $total_val;

				    	if($cur_balance >= $total_val)
				    	{
				    		// Vendor Invoice Payment Table Update
				    		$vendInvoice_val = array(
				    			'pre_bal' => $cur_bal,
				    			'cur_bal' => $new_inv_balance,
				    			'bal_amt' => $new_balance_amt,
				    		);

				    		if($amt_type != 2)
				    		{
				    			$vendInvoice_whr = array('id' => $pay_id);
						    	$vendInvoice_upt = $this->payment_model->vendorPaymentDetails_update($vendInvoice_val, $vendInvoice_whr);
				    		}

				    		$vendor_upt = array(
					    		'pre_balance' => $cur_balance,
					    		'cur_balance' => $new_cur_balance,
					    	);

				    		if($amt_type != 2)
				    		{
				    			$vendor_whr = array('id' => $vendor_id);
					    		$vendot_upt = $this->vendors_model->vendors_update($vendor_upt, $vendor_whr);
				    		}

					    	$new_balance = $cur_balance - $total_val;

					    	// Balance Sheet

					    	if($amt_type != 2)
		            		{
		            			$collection_type = 2;

		            			$master_where = array(
						    		'bill_code'    => 'PAY',
									'published'    => '1',
									'financial_id' => $financial_id,
								);

								$bill_val = $this->payment_model->getVendorPayment($master_where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

								$count_val = leadingZeros($bill_val[0]->autoid, 5);
			            		$bill_num  = 'PAY'.$count_val;
			            		$bill_code = 'PAY';
			            		$chq_pro   = '2';
		            		}
		            		else
		            		{
		            			$collection_type = 1;

		            			$master_where = array(
						    		'bill_code'    => 'CHQ',
									'published'    => '1',
									'financial_id' => $financial_id,
								);

								$bill_val = $this->payment_model->getVendorPayment($master_where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

								$count_val = leadingZeros($bill_val[0]->autoid, 5);
			            		$bill_num  = 'CHQ'.$count_val;
			            		$bill_code = 'CHQ';
			            		$chq_pro   = '1';
		            		}

					    	// Payment Insert
					    	$ins_data = array(
					    		'vendor_id'       => $vendor_id,
					    		'payment_id'      => $pay_id,
					    		'bill_code'       => $bill_code,
					    		'bill_id'         => $bill_id,
					    		'bill_no'         => $bill_num,
					    		'pre_bal'         => $cur_balance,
					    		'cur_bal'         => $new_balance,
					    		'amount'          => $amount,
					    		'discount'        => $discount_val,
					    		'pay_type'        => 1,
					    		'description'     => $description,
					    		'bank_name'       => $bank_name,
					    		'cheque_no'       => $cheque_no,
					    		'collect_date'    => date('Y-m-d', strtotime($collect_date)),
					    		'amt_type'        => $amt_type,
					    		'collection_type' => $collection_type,
					    		'cheque_process'  => $chq_pro,
					    		'value_type'      => 2,
					    		'financial_id'    => $financial_id,
					    		'date'            => date('Y-m-d', strtotime($entry_date)),
					    		'time'            => $time,
					    		'createdate'      => $c_date,
					    	);

					    	$payment_insert = $this->payment_model->vendorPayment_insert($ins_data);

					    	// Pre Payment Update
					    	$prePayment_data = array(
					    		'status' => '0'
					    	);

					    	$prePayment_whr  = array(
					    		'id !='     => $payment_insert,
					    		'vendor_id' => $vendor_id,
					    	);

					    	$payment_update = $this->payment_model->vendorPayment_update($prePayment_data, $prePayment_whr);

						    if($payment_insert)
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
						        $response['message'] = "Not Success"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
				    	}
				    	else
					    {
		        			$response['status']  = 0;
					        $response['message'] = "Invalid Amount"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
					    }
					}
					else
				    {
	        			$response['status']  = 0;
				        $response['message'] = "Invalid Amount"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
			    }
			}

			else if($method == '_editVendorPayment')
			{
				$auto_id        = $this->input->post('auto_id');
				$vendor_id      = $this->input->post('vendor_id');
				$entry_date     = $this->input->post('entry_date');
				$description    = $this->input->post('description');
				$penalty_amt    = $this->input->post('penalty_amt');
				$bank_charge    = $this->input->post('bank_charge');
				$cheque_status  = $this->input->post('cheque_status');
				$date           = date('Y-m-d');
				$time           = date('H:i:s');
				$c_date         = date('Y-m-d H:i:s');

				$error = FALSE;
			    $errors = array();
				$required = array('auto_id', 'vendor_id', 'entry_date', 'cheque_status', 'description');

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
			    	$payment_whr  = array(
						'id'        => $auto_id,
						'published' => '1',
					);

					$payment_col  = 'id, payment_id, amount, discount, bank_name, cheque_no, collect_date, amt_type, date';
					$payment_data = $this->payment_model->getVendorPayment($payment_whr, '', '', 'result', '', '', '', '', $payment_col);

					if(!empty($payment_data))
					{
						$pay_data     = $payment_data[0];
						$db_auto_id      = !empty($pay_data->id)?$pay_data->id:'';
			            $db_payment_id   = !empty($pay_data->payment_id)?$pay_data->payment_id:'';
			            $db_amount       = !empty($pay_data->amount)?$pay_data->amount:'0';
			            $db_discount     = !empty($pay_data->discount)?$pay_data->discount:'0';
			            $db_bank_name    = !empty($pay_data->bank_name)?$pay_data->bank_name:'';
			            $db_cheque_no    = !empty($pay_data->cheque_no)?$pay_data->cheque_no:'';
			            $db_collect_date = !empty($pay_data->collect_date)?$pay_data->collect_date:'';
			            $db_amt_type     = !empty($pay_data->amt_type)?$pay_data->amt_type:'';
			            $db_entry_date   = !empty($pay_data->date)?$pay_data->date:'';

			            if(!empty($entry_date))
			            {
			            	$ins_date = date('Y-m-d', strtotime($entry_date));
			            }
			            else
			            {
			            	$ins_date = date('Y-m-d', strtotime($db_entry_date));
			            }

			            if($cheque_status == 1)
			            {
			            	$db_total_amt = $db_amount + $db_discount;

			            	// Invoice Details
			            	$inv_whr = array('id' => $db_payment_id, 'published' => '1');
			            	$inv_col = 'bill_id, cur_bal, bal_amt';
			            	$inv_val = $this->payment_model->getVendorPaymentDetails($inv_whr, '', '', 'result', '', '', '', '', $inv_col);

			            	$dB_bill_id = !empty($inv_val[0]->bill_id)?$inv_val[0]->bill_id:'0';
			            	$cur_bal    = !empty($inv_val[0]->cur_bal)?$inv_val[0]->cur_bal:'0';
			            	$bal_amt    = !empty($inv_val[0]->bal_amt)?$inv_val[0]->bal_amt:'0';
			            	$new_bal    = $bal_amt - $db_total_amt;

			            	if($bal_amt >= $db_total_amt)
			            	{
			            		// Vendor Table Balance Update
						    	$vendor_whr  = array(
						    		'id'        => $vendor_id,
						    		'published' => '1',
						    	);

						    	$vendor_col  = 'pre_balance, cur_balance';

								$vendor_data = $this->vendors_model->getVendors($vendor_whr, '', '', 'result', '', '', '', '', $vendor_col);

								$pre_balance = !empty($vendor_data[0]->pre_balance)?$vendor_data[0]->pre_balance:'0';
							    $cur_balance = !empty($vendor_data[0]->cur_balance)?$vendor_data[0]->cur_balance:'0';

							    // New Outlet Main Balance
						    	$new_cur_balance = $cur_balance - $db_total_amt;
						    	$new_inv_balance = $cur_bal - $db_total_amt;
						    	$new_balance_amt = $bal_amt - $db_total_amt;

						    	if($cur_balance >= $db_total_amt)
						    	{
						    		// Vendor Invoice Payment Table Update
						    		$vendInvoice_val = array(
						    			'pre_bal' => $cur_bal,
						    			'cur_bal' => $new_inv_balance,
						    			'bal_amt' => $new_balance_amt,
						    		);

						    		$vendInvoice_whr = array('id' => $db_payment_id);
						    		$vendInvoice_upt = $this->payment_model->vendorPaymentDetails_update($vendInvoice_val, $vendInvoice_whr);

						    		$vendor_upt = array(
							    		'pre_balance' => $cur_balance,
							    		'cur_balance' => $new_cur_balance,
							    	);

							    	$vendor_whr = array('id' => $vendor_id);
					    			$vendot_upt = $this->vendors_model->vendors_update($vendor_upt, $vendor_whr);

					    			$vendPayment_val = array('collection_type' => '2'); 
					    			$vendPayment_whr = array('id' => $db_auto_id);
						    		$vendPayment_upt = $this->payment_model->vendorPayment_update($vendPayment_val, $vendPayment_whr);

					    			$new_balance = $cur_balance - $db_total_amt;

					    			$collection_type = 2;

			            			$master_where = array(
							    		'bill_code'    => 'PAY',
										'published'    => '1',
										'financial_id' => $financial_id,
									);

									$bill_val = $this->payment_model->getVendorPayment($master_where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

									$count_val = leadingZeros($bill_val[0]->autoid, 5);
				            		$bill_num  = 'PAY'.$count_val;
				            		$bill_code = 'PAY';

				            		// Payment Insert
							    	$ins_data = array(
							    		'vendor_id'       => $vendor_id,
							    		'payment_id'      => $db_payment_id,
							    		'bill_code'       => $bill_code,
							    		'bill_id'         => $dB_bill_id,
							    		'bill_no'         => $bill_num,
							    		'pre_bal'         => $cur_balance,
							    		'cur_bal'         => $new_balance,
							    		'amount'          => $db_amount,
							    		'discount'        => $db_discount,
							    		'pay_type'        => 1,
							    		'description'     => $description,
							    		'bank_name'       => $db_bank_name,
							    		'cheque_no'       => $db_cheque_no,
							    		'collect_date'    => date('Y-m-d', strtotime($db_collect_date)),
							    		'amt_type'        => $db_amt_type,
							    		'collection_type' => $collection_type,
							    		'cheque_process'  => 2,
							    		'value_type'      => 2,
							    		'financial_id'    => $financial_id,
							    		'date'            => date('Y-m-d', strtotime($ins_date)),
							    		'time'            => $time,
							    		'createdate'      => $c_date,
							    	);

							    	$payment_insert = $this->payment_model->vendorPayment_insert($ins_data);

							    	// Pre Payment Update
							    	$prePayment_data = array(
							    		'status' => '0'
							    	);

							    	$prePayment_whr  = array(
							    		'id !='     => $payment_insert,
							    		'vendor_id' => $vendor_id,
							    	);

							    	$payment_update = $this->payment_model->vendorPayment_update($prePayment_data, $prePayment_whr);

								    if($payment_insert)
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
								        $response['message'] = "Not Success"; 
								        $response['data']    = [];
								        echo json_encode($response);
								        return; 
								    }
						    	}
						    	else
							    {
				        			$response['status']  = 0;
							        $response['message'] = "Invalid Amount"; 
							        $response['data']    = [];
							        echo json_encode($response);
							        return; 
							    }
			            	}
			            	else
			            	{
			            		$response['status']  = 0;
						        $response['message'] = "Invalid Amount"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
			            	}
			            }
			            else
			            {
			            	// Invoice Details
			            	$inv_whr = array('id' => $db_payment_id, 'published' => '1');
			            	$inv_col = 'bill_id, bal_amt';
			            	$inv_val = $this->payment_model->getVendorPaymentDetails($inv_whr, '', '', 'result', '', '', '', '', $inv_col);

			            	$dB_bill_id = !empty($inv_val[0]->bill_id)?$inv_val[0]->bill_id:'0';
			            	$bal_amt    = !empty($inv_val[0]->bal_amt)?$inv_val[0]->bal_amt:'0';

			            	$tot_penalty  = !empty($penalty_amt)?$penalty_amt:'0';
			            	$tot_bankChar = !empty($bank_charge)?$bank_charge:'0';
			            	$total_value  = $tot_penalty + $tot_bankChar;
			            	$new_bal      = $bal_amt + $total_value;

			            	$venInvoice_val = array(
			            		'bal_amt'    => $new_bal,
			            		'updatedate' => date('Y-m-d H:i:s')
			            	);

			            	$venInvoice_whr = array('id' => $db_payment_id);
						    $venInvoice_upt = $this->payment_model->vendorPaymentDetails_update($venInvoice_val, $venInvoice_whr);

						    // Vendor Table Balance Update
					    	$vendor_whr  = array(
					    		'id'        => $vendor_id,
					    		'published' => '1',
					    	);

					    	$vendor_col  = 'pre_balance, cur_balance';

							$vendor_data = $this->vendors_model->getVendors($vendor_whr, '', '', 'result', '', '', '', '', $vendor_col);

							$pre_balance = !empty($vendor_data[0]->pre_balance)?$vendor_data[0]->pre_balance:'0';
						    $cur_balance = !empty($vendor_data[0]->cur_balance)?$vendor_data[0]->cur_balance:'0';

						    // New Outlet Main Balance
					    	$new_cur_balance = $cur_balance + $total_value;

					    	$vendor_upt = array(
					    		'pre_balance' => $cur_balance,
					    		'cur_balance' => $new_cur_balance,
					    	);

					    	$vendor_whr = array('id' => $vendor_id);
					    	$vendot_upt = $this->vendors_model->vendors_update($vendor_upt, $vendor_whr);

					    	$vendPayment_val = array('collection_type' => '2'); 
			    			$vendPayment_whr = array('id' => $db_auto_id);
				    		$vendPayment_upt = $this->payment_model->vendorPayment_update($vendPayment_val, $vendPayment_whr);

					    	// Balance Sheet
					    	$master_where = array(
					    		'bill_code'    => 'PEN',
								'published'    => '1',
								'financial_id' => $financial_id,
							);

							$bill_val = $this->payment_model->getVendorPayment($master_where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

							$count_val = leadingZeros($bill_val[0]->autoid, 5);
		            		$bill_num  = 'PEN'.$count_val;
		            		$bill_code = 'PEN';

		            		$new_balance = $cur_balance + $total_value;

		            		// Payment Insert
					    	$ins_data = array(
					    		'vendor_id'       => $vendor_id,
					    		'payment_id'      => $db_payment_id,
					    		'bill_code'       => $bill_code,
					    		'bill_id'         => $dB_bill_id,
					    		'bill_no'         => $bill_num,
					    		'pre_bal'         => $cur_balance,
					    		'cur_bal'         => round($new_balance),
					    		'amount'          => $total_value,
					    		'pay_type'        => 1,
					    		'description'     => $description,
					    		'collect_date'    => date('Y-m-d'),
					    		'penalty_amt'     => $penalty_amt,
					    		'cheque_status'   => $cheque_status,
					    		'bank_charge'     => $bank_charge,
					    		'penalty_amt'     => $tot_penalty,
		    					'bank_charge'     => $tot_bankChar,
					    		'amt_type'        => 3,
					    		'collection_type' => 2,
					    		'cheque_process'  => 2,
					    		'value_type'      => 2,
					    		'status'          => 0,
					    		'financial_id'    => $financial_id,
					    		'date'            => date('Y-m-d', strtotime($ins_date)),
					    		'time'            => $time,
					    		'createdate'      => $c_date,
					    	);

					    	$payment_insert = $this->payment_model->vendorPayment_insert($ins_data);

					    	// Pre Payment Update
					    	$prePayment_data = array(
					    		'status' => '0'
					    	);

					    	$prePayment_whr  = array(
					    		'id !='              => $payment_insert,
					    		'vendor_id'          => $vendor_id,
					    		'collection_type !=' => '1',
					    	);

					    	$payment_update = $this->payment_model->vendorPayment_update($prePayment_data, $prePayment_whr);

						    if($payment_insert)
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
						        $response['message'] = "Not Success"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return; 
						    }
			            }
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

			else if($method == '_listVendorPaymentPaginate')
			{
				$vendor_id = $this->input->post('vendor_id');

				$error = FALSE;
			    $errors = array();
				$required = array('vendor_id');
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
			    	$limit  = $this->input->post('limit');
	    			$offset = $this->input->post('offset');

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

		    			$where = array(
		    				'vendor_id'  => $vendor_id,
		    				'value_type' => '2',
		    				'published'  => '1',
		    			);
		    		}
		    		else
		    		{
		    			$like = [];

		    			$where = array(
		    				'vendor_id'  => $vendor_id,
		    				'value_type' => '2',
		    				'published'  => '1',
		    			);
		    		}

		    		$column = 'id';
					$overalldatas = $this->payment_model->getVendorPayment($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->payment_model->getVendorPayment($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$payment_list = [];
						foreach ($data_list as $key => $value) 
						{
							$payment_id  = !empty($value->id)?$value->id:'';
							$bill_id     = !empty($value->payment_id)?$value->payment_id:'';
							$vendor_id   = !empty($value->vendor_id)?$value->vendor_id:'';
							$bill_code   = !empty($value->bill_code)?$value->bill_code:'';
							$bill_no     = !empty($value->bill_no)?$value->bill_no:'';
							$pre_bal     = !empty($value->pre_bal)?$value->pre_bal:'';
							$cur_bal     = !empty($value->cur_bal)?$value->cur_bal:'';
							$amount      = !empty($value->amount)?$value->amount:'';
							$discount    = !empty($value->discount)?$value->discount:'';
							$pay_type    = !empty($value->pay_type)?$value->pay_type:'';
							$description = !empty($value->description)?$value->description:'';
							$amt_type    = !empty($value->amt_type)?$value->amt_type:'';
							$col_type    = !empty($value->collection_type)?$value->collection_type:'';
							$chq_pro     = !empty($value->cheque_process)?$value->cheque_process:'';
							$penalty_amt = !empty($value->penalty_amt)?$value->penalty_amt:'0';
							$bank_charge = !empty($value->bank_charge)?$value->bank_charge:'0';
							$date        = !empty($value->date)?$value->date:'';
							$time        = !empty($value->time)?$value->time:'';
							$status      = !empty($value->status)?$value->status:'';

							// Payment typ details
							if($pay_type == 1)
							{
								$payment_type = 'Debit';
							}
							else
							{
								$payment_type = 'Credit';
							}

							// Amount Type
							if($amt_type == 1)
							{
								$amount_type = 'Cash';
							}
							else if($amt_type == 2)
							{
								$amount_type = 'Cheque';
							}
							else if($amt_type == 3)
							{
								$amount_type = 'Others';
							}
							else
							{
								$amount_type = 'Credit';	
							}

							// Invoice Details
							$inv_whr = array('id' => $bill_id);
							$inv_col = 'invoice_no';
							$inv_val = $this->invoice_model->getVendorInvoice($inv_whr, '', '', 'result', '', '', '', '', $inv_col);

							$inv_num = !empty($inv_val[0]->invoice_no)?$inv_val[0]->invoice_no:'';

							$payment_list[] = array(
								'payment_id'      => $payment_id,
								'invoice_no'      => $inv_num,
								'vendor_id'       => $vendor_id,
								'bill_code'       => $bill_code,
								'bill_no'         => $bill_no,
								'pre_bal'         => $pre_bal,
								'cur_bal'         => $cur_bal,
								'amount'          => $amount,
								'discount'        => $discount,
								'pay_type'        => $pay_type,
								'payment_type'    => $payment_type,
								'description'     => $description,
								'collection_type' => $col_type,
								'amt_type'        => $amt_type,
								'amount_type'     => $amount_type,
								'bank_charge'     => $bank_charge,
								'penalty_amt'     => $penalty_amt,
								'cheque_process'  => $chq_pro,
								'date'            => date('d-m-Y', strtotime($date)),
								'time'            => date('h:i:s', strtotime($time)),
								'status'          => $status,
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
				        $response['data']         = $payment_list;
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

			else if($method == '_vendorPaymentDetails')
			{
				$payment_id = $this->input->post('payment_id');

				if(!empty($payment_id))
				{
					$payment_whr  = array(
						'id'        => $payment_id,
						'published' => '1',
					);

					$payment_col  = 'id, payment_id, amount, discount, bank_name, cheque_no, collect_date, amt_type, date';
					$payment_data = $this->payment_model->getVendorPayment($payment_whr, '', '', 'result', '', '', '', '', $payment_col);

					if(!empty($payment_data))
					{
						$pay_data     = $payment_data[0];
						$auto_id      = !empty($pay_data->id)?$pay_data->id:'';
			            $payment_id   = !empty($pay_data->payment_id)?$pay_data->payment_id:'';
			            $amount       = !empty($pay_data->amount)?$pay_data->amount:'';
			            $discount     = !empty($pay_data->discount)?$pay_data->discount:'';
			            $bank_name    = !empty($pay_data->bank_name)?$pay_data->bank_name:'';
			            $cheque_no    = !empty($pay_data->cheque_no)?$pay_data->cheque_no:'';
			            $collect_date = !empty($pay_data->collect_date)?$pay_data->collect_date:'';
			            $amt_type     = !empty($pay_data->amt_type)?$pay_data->amt_type:'';
			            $entry_date   = !empty($pay_data->date)?$pay_data->date:'';

			            $pay_result = array(
			            	'auto_id'      => $auto_id,
				            'payment_id'   => $payment_id,
				            'amount'       => $amount,
				            'discount'     => $discount,
				            'bank_name'    => $bank_name,
				            'cheque_no'    => $cheque_no,
				            'collect_date' => date('d-m-Y', strtotime($collect_date)),
				            'amt_type'     => $amt_type,
				            'entry_date'   => date('d-m-Y', strtotime($entry_date)),
			            );

			            $response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $pay_result;
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

			else if($method == '_listVendorPaymentBill')
			{
				$vendor_id = $this->input->post('vendor_id');

				$error = FALSE;
			    $errors = array();
				$required = array('vendor_id');
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
			    	$where = array(
	    				'vendor_id'  => $vendor_id,
	    				'cur_bal !=' => '0',
	    				'status'     => '1',
	    				'published'  => '1',
	    			);

	    			$data_list = $this->payment_model->getVendorPaymentDetails($where);

	    			if($data_list)
	    			{
	    				$payment_list = [];
	    				foreach ($data_list as $key => $value) {
	    					$pay_id  = !empty($value->id)?$value->id:'';
							$bill_id = !empty($value->bill_id)?$value->bill_id:'';
							$bill_no = !empty($value->bill_no)?$value->bill_no:'';
							$pre_bal = !empty($value->pre_bal)?$value->pre_bal:'0';
							$cur_bal = !empty($value->cur_bal)?$value->cur_bal:'0';
							$amount  = !empty($value->amount)?$value->amount:'0';
							$bal_amt = !empty($value->bal_amt)?$value->bal_amt:'0';

							if($bal_amt != 0)
							{
								$payment_list[] = array(
									'pay_id'  => $pay_id,
									'bill_id' => $bill_id,
									'bill_no' => $bill_no,
									'pre_bal' => $pre_bal,
									'cur_bal' => $cur_bal,
									'amount'  => $amount,
									'bal_amt' => $bal_amt,
								);
							}
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
				        $response['message'] = "Not Found"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
					}
			    }
			}

			else if($method == '_detailVendorPaymentBill')
			{
				$payment_id = $this->input->post('payment_id');

				$error = FALSE;
			    $errors = array();
				$required = array('payment_id');
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
			    	$where = array(
	    				'id'        => $payment_id,
	    				'status'    => '1',
	    				'published' => '1',
	    			);

	    			$data_list = $this->payment_model->getVendorPaymentDetails($where);

	    			if($data_list)
	    			{
	    				$payment_list = [];
	    				foreach ($data_list as $key => $value) {
	    					$pay_id  = !empty($value->id)?$value->id:'';
							$bill_id = !empty($value->bill_id)?$value->bill_id:'';
							$bill_no = !empty($value->bill_no)?$value->bill_no:'';
							$pre_bal = !empty($value->pre_bal)?$value->pre_bal:'0';
							$cur_bal = !empty($value->cur_bal)?$value->cur_bal:'0';
							$amount  = !empty($value->amount)?$value->amount:'0';
							$bal_amt = !empty($value->bal_amt)?$value->bal_amt:'0';

							$payment_list[] = array(
								'pay_id'  => $pay_id,
								'bill_id' => $bill_id,
								'bill_no' => $bill_no,
								'pre_bal' => $pre_bal,
								'cur_bal' => $cur_bal,
								'amount'  => $amount,
								'bal_amt' => $bal_amt,
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
				        $response['message'] = "Not Found"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
					}
			    }
			}

			else if($method == '_deleteVendorPayment')
			{
				$payment_id = $this->input->post('payment_id');

				if(!empty($payment_id))
				{
					$payment_whr  = array(
						'id'        => $payment_id,
						'published' => '1',
					);

					$payment_data = $this->payment_model->getVendorPayment($payment_whr);

					if(!empty($payment_data))
					{
						$pay_val   = $payment_data[0];
						$bill_id   = !empty($pay_val->bill_id)?$pay_val->bill_id:'';
						$bill_no   = !empty($pay_val->bill_no)?$pay_val->bill_no:'';
						$vendor_id = !empty($pay_val->vendor_id)?$pay_val->vendor_id:'';
						$amount    = !empty($pay_val->amount)?$pay_val->amount:'0';
						$discount  = !empty($pay_val->discount)?$pay_val->discount:'0';
						$total_val = $amount + $discount;

						// Vendor Invoice Payment Details
						$bill_whr = array(
							'vendor_id' => $vendor_id,
							'bill_id'   => $bill_id,
				    		'published' => '1',
				    		'status'    => '1',
						);

						$bill_col  = 'id, bill_id, bill_no, pre_bal, cur_bal, bal_amt';

						$bill_data = $this->payment_model->getVendorPaymentDetails($bill_whr, '', '', 'result', '', '', '', '', $bill_col);

						$pay_id  = !empty($bill_data[0]->id)?$bill_data[0]->id:'';
						$bill_id = !empty($bill_data[0]->bill_id)?$bill_data[0]->bill_id:'';
						$bill_no = !empty($bill_data[0]->bill_no)?$bill_data[0]->bill_no:'';
						$pre_bal = !empty($bill_data[0]->pre_bal)?$bill_data[0]->pre_bal:'0';
						$cur_bal = !empty($bill_data[0]->cur_bal)?$bill_data[0]->cur_bal:'0';
						$bal_amt = !empty($bill_data[0]->bal_amt)?$bill_data[0]->bal_amt:'0';

						// Vendor Invoice Payment Update
						$new_inv_balance = $cur_bal + $total_val;
						$new_balance_amt = $bal_amt + $total_val;

						$vendInvoice_val = array(
			    			'pre_bal' => $cur_bal,
			    			'cur_bal' => $new_inv_balance,
			    			'bal_amt' => $new_balance_amt,
			    		);

			    		$vendInvoice_whr = array('id' => $pay_id);
				    	$vendInvoice_upt = $this->payment_model->vendorPaymentDetails_update($vendInvoice_val, $vendInvoice_whr);

						// Payment Table Update
				    	$payment_upt = array('published' => '0');
				    	$payment_whr = array('id' => $payment_id);
				    	$pay_update  = $this->payment_model->vendorPayment_delete($payment_upt, $payment_whr);

						// Last Vendor Payment Data
						$paymentData = array(
							'vendor_id' => $vendor_id,
							'status'    => '0',
						);

						$option['order_by']   = 'id';
						$option['disp_order'] = 'DESC';

						$limit  = 1;
						$offset = 0;

						$payment_list = $this->payment_model->getVendorPayment($paymentData, $limit, $offset, 'result', '', '', $option);

						$payment_val = $payment_list[0];
						$new_pre_bal = !empty($payment_val->pre_bal)?$payment_val->pre_bal:'0';
						$new_cur_bal = !empty($payment_val->cur_bal)?$payment_val->cur_bal:'0';

						$ovrVendor_upt = array(
							'pre_balance' => $new_pre_bal,
							'cur_balance' => $new_cur_bal,
						);

						$vendor_whr    = array('id' => $vendor_id);
				    	$distributor_update = $this->vendors_model->vendors_update($ovrVendor_upt, $vendor_whr);

				    	if($pay_update)
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
					        $response['message'] = "Not Success"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
					    }
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
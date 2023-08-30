<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class AssignInvoice extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('assigninvoice_model');
			$this->load->model('commom_model');
			$this->load->model('employee_model');
			$this->load->model('outlets_model');
			$this->load->model('distributors_model');
			$this->load->model('order_model');
			$this->load->model('vendors_model');
			$this->load->model('invoice_model');
			$this->load->model('assignproduct_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Add Assign Invoice
		// ***************************************************
		public function add_assign_invoice($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addAssignInvoice')
			{
				$error = FALSE;

				$distributor_type = $this->input->post('distributor_type');
				$vendor_id        = $this->input->post('vendor_id');
				$distributor_id   = $this->input->post('distributor_id');
				$employee_id      = $this->input->post('employee_id');
				$employee_name    = $this->input->post('employee_name');
				$month_id         = $this->input->post('month_id');
				$financial_id     = $this->input->post('financial_id');
				$store_value      = $this->input->post('store_value');

				$error = FALSE;
			    $errors = array();
				$required = array('distributor_type', 'employee_id', 'employee_name', 'month_id', 'financial_id', 'store_value');
				if($distributor_type == 1)
			    {
			    	array_push($required, 'distributor_id');
			    }
			    if($distributor_type == 2)
			    {
			    	array_push($required, 'vendor_id');
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
			    	$where=array(
				    	'employee_id'  => $employee_id,
				    	'month_id'     => $month_id,
				    	'financial_id' => $financial_id,
				    	'published'    => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->assigninvoice_model->getAssigninvoice($where, '', '', 'result', '', '', '', '', $column);

					if(!empty($overalldatas))
					{
						$response['status']  = 0;
				        $response['message'] = "Data Already Exist"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
					}
					else
					{
						$data = array(
							'distributor_type' => $distributor_type,
							'vendor_id'        => $vendor_id,
							'distributor_id'   => $distributor_id,
							'employee_id'      => $employee_id,
							'employee_name'    => $employee_name,
					    	'month_id'         => $month_id,
					    	'financial_id'     => $financial_id,
					    	'createdate'       => date('Y-m-d H:i:s'),
					    );

					    $insert = $this->assigninvoice_model->assigninvoice_insert($data);

					    $store_val = json_decode($store_value);	

					    foreach ($store_val as $key => $value) {
					    	$store_data = array(
								'assign_id'        => $insert,
								'distributor_type' => $distributor_type,
								'vendor_id'        => $vendor_id,
								'distributor_id'   => $distributor_id,
								'employee_id'      => $employee_id,
								'assign_date'      => date('Y-m-d', strtotime($value->assign_date)),
								'assign_day'       => $value->assign_day,
								'assign_invoice'   => $value->assign_store,
								'createdate'       => date('Y-m-d H:i:s'),
							);

							$assign_insert = $this->assigninvoice_model->assigninvoiceDetails_insert($store_data);
					    }

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
					        $response['message'] = "Not Success"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
					    }
					}
			    }
			}

			else if($method == '_updateAssignShop')
			{
				$error = FALSE;
				$assign_id      = $this->input->post('assign_id');
				$employee_id    = $this->input->post('employee_id');
				$employee_name  = $this->input->post('employee_name');
				$month_id       = $this->input->post('month_id');
				$financial_id   = $this->input->post('financial_id');
				$store_value    = $this->input->post('store_value');

				$error = FALSE;
			    $errors = array();
				$required = array('assign_id', 'employee_id', 'employee_name', 'month_id', 'financial_id', 'store_value');
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
			    	$where=array(
			    		'id !='        => $assign_id,
				    	'employee_id'  => $employee_id,
				    	'month_id'     => $month_id,
				    	'financial_id' => $financial_id,
				    	'published'    => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->assigninvoice_model->getAssigninvoice($where, '', '', 'result', '', '', '', '', $column);

					if(!empty($overalldatas))
					{
						$response['status']  = 0;
				        $response['message'] = "Data Already Exist"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
					}
					else
					{
						// Assign Shop
						$data = array(
							'employee_id'    => $employee_id,
							'employee_name'  => $employee_name,
					    	// 'month_id'       => $month_id,
					    	'financial_id'   => $financial_id,
					    	'updatedate'     => date('Y-m-d H:i:s'),
					    );

			    		$update_id = array('id' => $assign_id);
					    $update    = $this->assigninvoice_model->assigninvoice_update($data, $update_id);

					    // Assign Shop Details
					    $store_val = json_decode($store_value);	
					    foreach ($store_val as $key => $value) {

					    	$str_data = array(
								'assign_id'      => $assign_id,
								'employee_id'    => $employee_id,
								'assign_date'    => date('Y-m-d', strtotime($value->assign_date)),
								'assign_day'     => $value->assign_day,
								'assign_invoice' => $value->assign_store,
								'updatedate'     => date('Y-m-d H:i:s'),
							);

							$auto_id  = array('id' => $value->auto_id);
					    	$update_det = $this->assigninvoice_model->assigninvoiceDetails_update($str_data, $auto_id);
					    }

					    if($update)
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

		// Manage Assign Invoice
		// ***************************************************
		public function manage_assign_invoice($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listAssignInvoicePaginate')
			{
				$limit          = $this->input->post('limit');
	    		$offset         = $this->input->post('offset');
	    		$financ_year    = $this->input->post('financial_year');
	    		$distributor_id = $this->input->post('distributor_id');

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
	    			$like['name']     = $search;

	    			$where = array(
	    				'distributor_id' => $distributor_id,
	    				'financial_id'   => $financ_year,
	    				'published'      => '1'
	    			);
	    		}
	    		else
	    		{
	    			$like = [];
	    			$where = array(
	    				'distributor_id' => $distributor_id,
	    				'financial_id'   => $financ_year,
	    				'published'      => '1'
	    			);
	    		}

	    		$column = 'id';
				$overalldatas = $this->assigninvoice_model->getAssigninvoice($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->assigninvoice_model->getAssigninvoice($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$assign_list = [];
					foreach ($data_list as $key => $value) {

						$assign_id     = !empty($value->id)?$value->id:'';
						$employee_id   = !empty($value->employee_id)?$value->employee_id:'';
						$employee_name = !empty($value->employee_name)?$value->employee_name:'';
						$month_id      = !empty($value->month_id)?$value->month_id:'';
						$financial_id  = !empty($value->financial_id)?$value->financial_id:'';
						$status        = !empty($value->status)?$value->status:'';
						$createdate    = !empty($value->createdate)?$value->createdate:'';

						$assign_list[] = array(
							'assign_id'     => $assign_id,
							'employee_id'   => $employee_id,
							'employee_name' => $employee_name,
							'month_id'      => $month_id,
							'financial_id'  => $financial_id,
							'status'        => $status,
							'createdate'    => $createdate,
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
			        $response['data']         = $assign_list;
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

			else if($method == '_detailAssignInvoice')
			{
				$assign_id = $this->input->post('assign_id');

				if(!empty($assign_id))
				{
					$where_1 = array(
						'id'        => $assign_id,
						'published' => '1',
					);

				    $data    = $this->assigninvoice_model->getAssigninvoice($where_1);
				    if($data)
				    {
				    	$assign_details = array(
				    		'assign_id'  => !empty($data[0]->id)?$data[0]->id:'',
							'emp_id'     => !empty($data[0]->employee_id)?$data[0]->employee_id:'',
							'emp_name'   => !empty($data[0]->employee_name)?$data[0]->employee_name:'',
							'month_id'   => !empty($data[0]->month_id)?$data[0]->month_id:'',
							'finan_id'   => !empty($data[0]->financial_id)?$data[0]->financial_id:'',
							'status'     => !empty($data[0]->status)?$data[0]->status:'',
							'createdate' => !empty($data[0]->createdate)?$data[0]->createdate:'',
				    	);

				    	$where_2 = array('assign_id'=>$assign_id);
				    	$data_2  = $this->assigninvoice_model->getAssignInvoiceDetails($where_2);

				    	$store_list = [];
				    	foreach ($data_2 as $key => $value) {
				    		$auto_id        = !empty($value->id)?$value->id:'';
							$assign_id      = !empty($value->assign_id)?$value->assign_id:'';
							$assign_date    = !empty($value->assign_date)?$value->assign_date:'';
							$assign_day     = !empty($value->assign_day)?$value->assign_day:'';
							$assign_invoice = !empty($value->assign_invoice)?$value->assign_invoice:'';
							$status         = !empty($value->status)?$value->status:'';
							$createdate     = !empty($value->createdate)?$value->createdate:'';

							$store_list[] = array(
								'auto_id'        => $auto_id,
								'assign_id'      => $assign_id,
								'assign_date'    => date('d-m-Y', strtotime($assign_date)),
								'assign_day'     => $assign_day,
								'assign_invoice' => $assign_invoice,
								'status'         => $status,
								'createdate'     => $createdate,
							);
				    	}

				    	$assign_value = array(
				    		'assign_details' => $assign_details,
				    		'store_list'     => $store_list, 
				    	);

				    	$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $assign_value;
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
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
		    	}
			}

			else if($method == '_deleteAssignInvoice')
			{
				$assign_id = $this->input->post('assign_id');

				if(!empty($assign_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

				    $whr_one = array('id' => $assign_id);
				    $upt_one = $this->assigninvoice_model->assigninvoice_delete($data, $whr_one);

				    $whr_two = array('assign_id' => $assign_id);
				    $upt_two = $this->assigninvoice_model->assigninvoiceDetails_delete($data, $whr_two);

				    if($upt_one)
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

		// Employee wise shop
		// ***************************************************
		public function employee_wise_shop($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');
			$limit  = $this->input->post('limit');
			$offset = $this->input->post('offset');
			$search = $this->input->post('search');

			if($method == '_employeeWiseList')
			{
				$employee_id  = $this->input->post('employee_id');
				$current_date = date('Y-m-d');

				if(!empty($employee_id))
				{
					$where_1 = array(
						'employee_id' => $employee_id,
						'assign_date' => $current_date,
						'published'   => '1',
						'status'      => '1',
					);

					$column_1 = 'id, distributor_id, assign_invoice';
					$data_1   = $this->assigninvoice_model->getAssignInvoiceDetails($where_1, '', '', 'result', '', '', '', '', $column_1);

					if($data_1)
					{
						foreach ($data_1 as $key => $val_1) {
							$auto_id      = !empty($val_1->id)?$val_1->id:'';
				            $assign_inv   = !empty($val_1->assign_invoice)?$val_1->assign_invoice:'';
				            $distribut_id = !empty($val_1->distributor_id)?$val_1->distributor_id:'';

				            if($assign_inv)
				            {
				            	$where_2 = array(
				            		'distributor_id'  => $distribut_id,
				            		'zone_id'         => $assign_inv,
				            		'delivery_status' => '1',
				            		'invoice_status'  => '1',
				            		'cancel_status'   => '1',
				            		'published'       => '1',
				            	);

				            	$column_2 = 'id';
				            	$result_2 = $this->invoice_model->getInvoiceImplode($where_2, '', '', 'result', '', '', '', '', $column_2);

				            	if($result_2)
				            	{
				            		$inv_val = '';
				            		foreach ($result_2 as $key => $val_2) {

				            			$inv_id   = !empty($val_2->id)?$val_2->id:'';
				            			$inv_val .= $inv_id.',';
				            		}

				            		$inv_res = substr($inv_val, 0, -1);

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

									if($search !='')
						    		{
						    			$like['name']     = $search;
						    		}
						    		else
						    		{
						    			$like = [];
						    		}

					            	$where_3 = array(
										'distributor_id'  => $distribut_id,
										'invoice_id'      => $inv_res,
										'delivery_status' => '1',
										'invoice_status'  => '1',
					            		'cancel_status'   => '1',
					            		'published'       => '1',
									);

					            	$column = 'id';
									$overalldatas = $this->invoice_model->getInvoiceImplode($where_3, '', '', 'result', $like, '', '', '', $column);

									if($overalldatas)
									{
										$totalc = count($overalldatas);
									}
									else
									{
										$totalc = 0;
									}

									$option['order_by']   = 'store_name';
									$option['disp_order'] = 'ASC';

									$column_2 = 'order_id, bill_type, order_type, invoice_no, store_id, store_name, zone_id, due_days, discount, random_value, createdate';

									$data_2   = $this->invoice_model->getInvoiceImplode($where_3, $limit, $offset, 'result', $like, '', '', '', $column_2);

									if($data_2)
									{
										$order_list = [];
										foreach ($data_2 as $key => $val_2) {							

											$order_id   = !empty($val_2->order_id)?$val_2->order_id:'';
										    $bill_type  = !empty($val_2->bill_type)?$val_2->bill_type:'';
										    $order_type = !empty($val_2->order_type)?$val_2->order_type:'';
										    $invoice_no = !empty($val_2->invoice_no)?$val_2->invoice_no:'';
										    $store_id   = !empty($val_2->store_id)?$val_2->store_id:'';
										    $store_name = !empty($val_2->store_name)?$val_2->store_name:'';
										    $zone_id    = !empty($val_2->zone_id)?$val_2->zone_id:'';
										    $due_days   = !empty($val_2->due_days)?$val_2->due_days:'0';
										    $discount   = !empty($val_2->discount)?$val_2->discount:'0';
										    $random_val = !empty($val_2->random_value)?$val_2->random_value:'';
										    $createdate = !empty($val_2->createdate)?$val_2->createdate:'';

										    // Outlet Details
										    $whr_1  = array('id' => $store_id);
										    $col_1  = 'mobile, address, latitude, longitude';
										    $res_1  = $this->outlets_model->getOutlets($whr_1, '', '', 'result', '', '', '', '', $col_1);

										    $mobile = !empty($res_1[0]->mobile)?$res_1[0]->mobile:'';
										    $adrs   = !empty($res_1[0]->address)?$res_1[0]->address:'';
										    $lat    = !empty($res_1[0]->latitude)?$res_1[0]->latitude:'';
										    $long   = !empty($res_1[0]->longitude)?$res_1[0]->longitude:'';

										    $order_list[] = array(
										    	'order_id'     => $order_id,
											    'bill_type'    => $bill_type,
											    'order_type'   => $order_type,
											    'invoice_no'   => $invoice_no,
											    'store_name'   => $store_name,
											    'contact_no'   => $mobile,
											    'address'      => $adrs,
											    'latitude'     => $lat,
											    'longitude'    => $long,
											    'zone_id'      => $zone_id,
											    'due_days'     => $due_days,
											    'discount'     => $discount,
											    '_invoice'     => $createdate,
											    'random_value' => $random_val,
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
								        $response['data']         = $order_list;
							    		echo json_encode($response);
								        return;
									}
									else
									{
										$response['status']  = 0;
								        $response['message'] = "No Data Found"; 
								        $response['data']    = [];
								        echo json_encode($response);
								        return;
									}
				            	}
				            	else
								{
									$response['status']  = 0;
							        $response['message'] = "No Data Found"; 
							        $response['data']    = [];
							        echo json_encode($response);
							        return;
								}
				            }
				            else
							{
								$response['status']  = 0;
						        $response['message'] = "No Data Found"; 
						        $response['data']    = [];
						        echo json_encode($response);
						        return;
							}
						}
					}
					else
					{
						$response['status']  = 0;
				        $response['message'] = "No Data Found"; 
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

			else if($method == '_employeeBillDetail')
			{
				$employee_id  = $this->input->post('employee_id');
				$random_value = $this->input->post('random_value');

				$error = FALSE;
			    $errors = array();
				$required = array('employee_id', 'random_value');
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
			    	// Employee Details
			    	$where_1 = array(
			        	'id'        => $employee_id,
			        	'status'    => '1',
			        	'published' => '1',
			        );

			        $employee_data = $this->employee_model->getEmployee($where_1);
			        $company_id    = !empty($employee_data[0]->company_id)?$employee_data[0]->company_id:'';

			        // Distributor Details
			        $where_2 = array(
			        	'id'        => $company_id,
			        	'status'    => '1',
			        	'published' => '1',
			        );

			        $company_data = $this->distributors_model->getDistributors($where_2);

			        $distri_id   = !empty($company_data[0]->id)?$company_data[0]->id:'';
			        $distri_type = !empty($company_data[0]->distributor_type)?$company_data[0]->distributor_type:'';
			        $vendor_id   = !empty($company_data[0]->vendor_id)?$company_data[0]->vendor_id:'';

			        // Invoice Data
    				$where_1 = array(
    					'random_value' => $random_value,
    					'published'    => '1',
    				);

    				$bill_data = $this->invoice_model->getInvoice($where_1);

    				if(!empty($bill_data))
    				{
    					// Bill Details
    					$data_val    = $bill_data[0];
    					$invoice_id  = !empty($data_val->id)?$data_val->id:'';
    					$order_id    = !empty($data_val->order_id)?$data_val->order_id:'';
    					$bill_type   = !empty($data_val->bill_type)?$data_val->bill_type:'';
					    $invoice_no  = !empty($data_val->invoice_no)?$data_val->invoice_no:'';
					    $distri_id   = !empty($data_val->distributor_id)?$data_val->distributor_id:'';
					    $vendor_id   = !empty($data_val->vendor_id)?$data_val->vendor_id:'';
					    $store_id    = !empty($data_val->store_id)?$data_val->store_id:'';
					    $due_days    = !empty($data_val->due_days)?$data_val->due_days:'';
					    $discount    = !empty($data_val->discount)?$data_val->discount:'';
					    $outlet_type = !empty($data_val->outlet_type)?$data_val->outlet_type:'';
					    $createdate  = !empty($data_val->createdate)?$data_val->createdate:'';

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
							'outlet_type'    => $outlet_type,
							'createdate'     => $createdate,
					    );

					    if(!empty($vendor_id))
					    {
					    	// Vendor Details
					    	$vdr_col = 'company_name, gst_no, contact_no, email, tan_no, state_id, address';

							$vdr_whr = array(
								'id'  => $vendor_id,
							);

							$vdr_data = $this->vendors_model->getVendors($vdr_whr, '', '', 'result', '', '', '', '', $vdr_col);

							$distributor_details = [];
							if(!empty($vdr_data))
							{
								$vdr_val    = $vdr_data[0];
								$com_name   = !empty($vdr_val->company_name)?$vdr_val->company_name:'';
							    $gst_no     = !empty($vdr_val->gst_no)?$vdr_val->gst_no:'';
							    $contact_no = !empty($vdr_val->contact_no)?$vdr_val->contact_no:'';
							    $email      = !empty($vdr_val->email)?$vdr_val->email:'';
							    $tan_no     = !empty($vdr_val->tan_no)?$vdr_val->tan_no:'';
							    $state_id   = !empty($vdr_val->state_id)?$vdr_val->state_id:'';
							    $address    = !empty($vdr_val->address)?$vdr_val->address:'';

							    // State Details
					            $state_whr  = array('id' => $state_id);
					            $state_data = $this->commom_model->getState($state_whr);

					            $state_val  = $state_data[0];
					            $state_name = !empty($state_val->state_name)?$state_val->state_name:'';
					            $gst_code   = !empty($state_val->gst_code)?$state_val->gst_code:'';

					            $distributor_details = array(
					            	'company_name' => $com_name,
					            	'gst_no'       => $gst_no,
					            	'contact_no'   => $contact_no,
					            	'email'        => $email,
					            	'tan_no'       => $tan_no,
					            	'address'      => $address,
					            	'state_name'   => $state_name,
					            	'gst_code'     => $gst_code,
					            );
							}
					    }
					    else
					    {
					    	// Distributor Details
					    	$dis_col = 'company_name, gst_no, mobile, email, tan_no, state_id, address';

							$dis_whr = array(
								'id'  => $distri_id,
							);

							$dis_data = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);

							$distributor_details = [];
							if(!empty($dis_data))
							{
								$dis_val    = $dis_data[0];
								$com_name   = !empty($dis_val->company_name)?$dis_val->company_name:'';
							    $gst_no     = !empty($dis_val->gst_no)?$dis_val->gst_no:'';
							    $contact_no = !empty($dis_val->mobile)?$dis_val->mobile:'';
							    $email      = !empty($dis_val->email)?$dis_val->email:'';
							    $tan_no     = !empty($dis_val->tan_no)?$dis_val->tan_no:'';
							    $state_id   = !empty($dis_val->state_id)?$dis_val->state_id:'';
							    $address    = !empty($dis_val->address)?$dis_val->address:'';

							    // State Details
					            $state_whr  = array('id' => $state_id);
					            $state_data = $this->commom_model->getState($state_whr);

					            $state_val  = $state_data[0];
					            $state_name = !empty($state_val->state_name)?$state_val->state_name:'';
					            $gst_code   = !empty($state_val->gst_code)?$state_val->gst_code:'';

					            $distributor_details = array(
					            	'company_name' => $com_name,
					            	'gst_no'       => $gst_no,
					            	'contact_no'   => $contact_no,
					            	'email'        => $email,
					            	'tan_no'       => $tan_no,
					            	'address'      => $address,
					            	'state_name'   => $state_name,
					            	'gst_code'     => $gst_code,
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
					    if(!empty($buyer_data))
					    {
					    	$order_no = !empty($buyer_data[0]->order_no)?$buyer_data[0]->order_no:'';
            				$_ordered = !empty($buyer_data[0]->_ordered)?$buyer_data[0]->_ordered:'';

            				$buyer_details = array(
            					'order_no' => $order_no,
            					'_ordered' => $_ordered,
            				);
					    }

					    // Outlet Details
						$out_col = 'company_name, contact_name, mobile, email, gst_no, address, country_id, state_id, city_id, zone_id, outlet_type';

						$out_whr = array(
							'id' => $store_id,
						);

						$out_data = $this->outlets_model->getOutlets($out_whr, '', '', 'result', '', '', '', '', $out_col);

						$store_details = [];
						if(!empty($out_data))
						{
							$out_val      = $out_data[0];
							$company_name = !empty($out_val->company_name)?$out_val->company_name:'';
				            $contact_name = !empty($out_val->contact_name)?$out_val->contact_name:'';
				            $mobile       = !empty($out_val->mobile)?$out_val->mobile:'';
				            $email        = !empty($out_val->email)?$out_val->email:'';
				            $gst_no       = !empty($out_val->gst_no)?$out_val->gst_no:'';
				            $address      = !empty($out_val->address)?$out_val->address:'';
				            $country_id   = !empty($out_val->country_id)?$out_val->country_id:'';
				            $state_id     = !empty($out_val->state_id)?$out_val->state_id:'';
				            $city_id      = !empty($out_val->city_id)?$out_val->city_id:'';
				            $zone_id      = !empty($out_val->zone_id)?$out_val->zone_id:'';
				            $outlet_type  = !empty($out_val->outlet_type)?$out_val->outlet_type:'';

				            // State Details
				            $state_whr  = array('id' => $state_id);
				            $state_data = $this->commom_model->getState($state_whr);

				            $state_val  = $state_data[0];
				            $state_name = !empty($state_val->state_name)?$state_val->state_name:'';
				            $gst_code   = !empty($state_val->gst_code)?$state_val->gst_code:'';

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
							'invoice_id' => $invoice_id,
		    				'published'  => '1',
		    			);		

		    			$order_details = $this->invoice_model->getInvoiceDetails($ord_whr);

		    			$product_details = [];
		    			if(!empty($order_details))
		    			{
		    				foreach ($order_details as $key => $value) {

		    					$auto_id     = !empty($value->id)?$value->id:'';
								$product_id  = !empty($value->product_id)?$value->product_id:'';
								$type_id     = !empty($value->type_id)?$value->type_id:'';
								$hsn_code    = !empty($value->hsn_code)?$value->hsn_code:'';
								$gst_val     = !empty($value->gst_val)?$value->gst_val:'';
								$unit_val    = !empty($value->unit_val)?$value->unit_val:'';
								$price       = !empty($value->price)?$value->price:'';
								$order_qty   = !empty($value->order_qty)?$value->order_qty:'';

								// Product Details
								$where_1      = array('id' => $product_id);	
								$product_det  = $this->commom_model->getProduct($where_1);
						    	$product_name = isset($product_det[0]->name)?$product_det[0]->name:'';		

						    	// Unit Type Details
						    	$where_2   = array('id' => $unit_val);
						    	$unit_det  = $this->commom_model->getUnit($where_2);
						    	$unit_name = isset($unit_det[0]->name)?$unit_det[0]->name:'';

								// Product Type Details
						    	$where_3     = array('id' => $type_id);
						    	$type_det    = $this->commom_model->getProductType($where_3);
						    	$description = isset($type_det[0]->description)?$type_det[0]->description:'';
						    	$type_name   = isset($type_det[0]->product_type)?$type_det[0]->product_type:'';

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

	        			$tax_column = 'hsn_code, gst_val';

	        			$groupby = 'hsn_code';

	        			$tax_data   = $this->invoice_model->getInvoiceDetails($tax_where, '', '', 'result', '', '', '', '', $tax_column, $groupby);

	        			$tax_details = [];
	        			if(!empty($tax_data))
	        			{
	        				foreach ($tax_data as $key => $value) {
	        					
	        					$hsn_code = !empty($value->hsn_code)?$value->hsn_code:'';
	        					$gst_val  = !empty($value->gst_val)?$value->gst_val:'';

	        					// Price Details
	        					$price_where = array(
			        				'invoice_id' => $invoice_id,
			        				'hsn_code'   => $hsn_code,
			        				'published'  => '1',
			        				'status'     => '1',
			        			);

			        			$price_column = 'price';

			        			$price_data   = $this->invoice_model->getInvoiceDetails($price_where, '', '', 'result', '', '', '', '', $price_column);

			        			$product_price = 0;
			        			foreach ($price_data as $key => $price_val) {
			        				$price = !empty($price_val->price)?$price_val->price:'';

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
			            	'buyer_details'       => $buyer_details,
			            	'distributor_details' => $distributor_details,
			            	'store_details'       => $store_details,
			            	'product_details'     => $product_details,
			            	'tax_details'         => $tax_details,
			            );

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
			}

			else if($method == '_employeeUpdateBill')
			{
				$employee_id  = $this->input->post('employee_id');
				$random_value = $this->input->post('random_value');
				$progress     = $this->input->post('progress');

				$error = FALSE;
			    $errors = array();
				$required = array('employee_id', 'random_value', 'progress');
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
			    		'random_value' => $random_value,
			    		'published'    => '1',
			    	);

			    	$column_1 = 'id, order_id, zone_id, distributor_id, vendor_id';

			    	$invoice_data = $this->invoice_model->getInvoice($where_1, '', '', 'result', '', '', '', '', $column_1);

    				if(!empty($invoice_data))
    				{	
    					$invoice_id = !empty($invoice_data[0]->id)?$invoice_data[0]->id:'';
    					$order_id   = !empty($invoice_data[0]->order_id)?$invoice_data[0]->order_id:'';
    					$zone_value = !empty($invoice_data[0]->zone_id)?$invoice_data[0]->zone_id:'';
    					$distri_id  = !empty($invoice_data[0]->distributor_id)?$invoice_data[0]->distributor_id:'';

    					if($progress == 11)
    					{
    						$update_data = array(
								'invoice_process' => '2',
								'order_status'    => $progress,
								'product_process' => $progress,
								'_delivery'       => date('Y-m-d H:i:s'),
							);

							// Update Invoice Table
							if(!empty($employee_id))
							{
								$upt_data = array(
									'delivery_status'   => '2',
									'delivery_employee' => $employee_id,
									'delivery_date'     => date('Y-m-d'),
								);

								$upt_wht  = array('id' => $invoice_id);
								$upt_inv  = $this->invoice_model->invoice_update($upt_data, $upt_wht);
							}

							// Distributor Product Details
							$column_2 = 'type_id';
							$where_2  = array('id' => $distri_id);
							$data_2   = $this->distributors_model->getDistributors($where_2, '', '', 'result', '', '', '', '', $column_2);

							$result_2 = $data_2[0];
							$type_id  = !empty($result_2->type_id)?$result_2->type_id:'';

							$ord_whr = array(
			    				'tbl_order_details.order_id'        => $order_id,
			    				'tbl_order_details.delete_status'   => '1',
			    				'tbl_order_details.published'       => '1',
			    				'tbl_order_details.type_id'         => $type_id,
			    				'tbl_order_details.vendor_type != ' => '1'
			    			);

			    			$ord_col = 'tbl_order_details.id, tbl_order_details.type_id';

			    			$order_list  = $this->order_model->getDistributorOrder($ord_whr, '', '', 'result', '', '', '', '', $ord_col);

			    			if(!empty($order_list))
			    			{
			    				foreach ($order_list as $key => $value)
			    				{
			    					$ord_value  = !empty($value->id)?$value->id:'';
			    					$type_value = !empty($value->type_id)?$value->type_id:'';

				    				$where_3 = array(
								    	'distributor_id' => $distri_id,
								    	'type_id'        => $type_value,
								    	'zone_id'        => $zone_value,
								    	'status'         => '1', 
										'published'      => '1'
								    );

								    $column_3 = 'id';

									$assign_data = $this->assignproduct_model->getAssignProductDetails($where_3, '', '', 'result', '', '', '', '', $column_3);

									if(!empty($assign_data))
									{
										$upt_whr = array('id' => $ord_value);
									    $update  = $this->order_model->orderDetails_update($update_data, $upt_whr);
									}
			    				}
			    			}

			    			if($update_data)
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
					        $response['message'] = "Invalid order status"; 
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
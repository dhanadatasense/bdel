<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class Backlog extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('order_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Outlet backlog
		// ***************************************************
		public function outlet_backlog($param1="",$param2="",$param3="")
		{
			$method         = $this->input->post('method');
			$start_date     = $this->input->post('start_date');
			$end_date       = $this->input->post('end_date');
			$distributor_id = $this->input->post('distributor_id');
			$state_id       = $this->input->post('state_id');
    		$city_id        = $this->input->post('city_id');
    		$zone_id        = $this->input->post('zone_id');
    		$employee_id    = $this->input->post('employee_id');
    		$category_id    = $this->input->post('category_id');

			if($method == '_outletBacklog')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('start_date', 'end_date','category_id');
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
							'B.createdate >=' => $start_value,
							'B.createdate <=' => $end_value,
						);

				    	if($employee_id)
				    	{
				    		$whr_1['A.emp_id'] = $employee_id;
				    	}
				    	if($state_id)
				    	{
				    		$whr_1['E.state_id'] = $state_id;
				    	}
				    	if($city_id)
				    	{
				    		$whr_1['E.city_id'] = $city_id;
				    	}
				    	if($zone_id)
				    	{
				    		$whr_1['E.zone_id'] = $zone_id;
				    	}
				    	if($category_id)
				    	{
				    		$whr_in['D.category_id'] = $category_id;
						}else{
							$whr_in['D.category_id'] = [1];
				    	}

						$col_1 = 'B.order_no, A.emp_name, A.store_name, F.state_name, G.city_name, H.name AS zone_name, C.name AS pdt_name, D.description, B.gst_val, B.price, B.entry_qty, B.order_qty, B.delete_status, B.published, B.createdate';

						$qry_1 = $this->order_model->getOrderOverallJoin($whr_1, '', '', 'result', '', '', '', '', $col_1, '', $whr_in);

						// echo $this->db->last_query(); exit;

						if($qry_1)
						{
							$data_list = [];
							foreach ($qry_1 as $key => $val_1) {

								$order_no      = empty_check($val_1->order_no);
							    $emp_name      = empty_check($val_1->emp_name);
							    $store_name    = empty_check($val_1->store_name);
							    $state_name    = empty_check($val_1->state_name);
							    $city_name     = empty_check($val_1->city_name);
							    $zone_name     = empty_check($val_1->zone_name);
							    $pdt_name      = empty_check($val_1->pdt_name);
							    $type_desc     = empty_check($val_1->description);
							    $gst_value     = zero_check($val_1->gst_val);
							    $pdt_price     = zero_check($val_1->price);
							    $pdt_qty       = zero_check($val_1->entry_qty);
							    $order_qty     = zero_check($val_1->order_qty);
							    $delete_status = zero_check($val_1->delete_status);
							    $published     = zero_check($val_1->published);
							    $createdate    = date_check($val_1->createdate);

							    // published = 0
							    if($published == 0)
							    {
							    	$gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
			                        $price_val  = $pdt_price - $gst_data;
			                        $pdt_gst    = $pdt_qty * $gst_data;
			                        $TaxableAmt = $pdt_qty * $price_val;
			                        $tot_price  = $pdt_qty * $pdt_price;
			                        $tax_value  = number_format((float)$pdt_gst, 2, '.', '');
			                        $TaxableTot = number_format((float)$TaxableAmt, 2, '.', '');
			                        $pdt_value  = number_format((float)round($tot_price), 2, '.', '');

							    	$remove_list = array(
							    		'order_no'      => $order_no,
										'emp_name'      => !empty($emp_name)?$emp_name:'Admin',
										'store_name'    => $store_name,
										'state_name'    => $state_name,
										'city_name'     => $city_name,
										'zone_name'     => $zone_name,
										'description'   => $type_desc,
										'gst_value'     => $gst_value,
										'pdt_price'     => $pdt_price,
										'pdt_qty'       => strval($pdt_qty),
										'taxable_value' => $TaxableTot,
										'tax_value'     => $tax_value,
										'net_value'     => $pdt_value,
										'log_data'      => 'Product deleted by admin',
							    	);

							    	array_push($data_list, $remove_list);
							    }

							    // delete_status = 2
							    if($delete_status == 2)
							    {
							    	$gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
			                        $price_val  = $pdt_price - $gst_data;
			                        $pdt_gst    = $pdt_qty * $gst_data;
			                        $TaxableAmt = $pdt_qty * $price_val;
			                        $tot_price  = $pdt_qty * $pdt_price;
			                        $tax_value  = number_format((float)$pdt_gst, 2, '.', '');
			                        $TaxableTot = number_format((float)$TaxableAmt, 2, '.', '');
			                        $pdt_value  = number_format((float)round($tot_price), 2, '.', '');

							    	$delete_list = array(
							    		'order_no'      => $order_no,
										'emp_name'      => !empty($emp_name)?$emp_name:'Admin',
										'store_name'    => $store_name,
										'state_name'    => $state_name,
										'city_name'     => $city_name,
										'zone_name'     => $zone_name,
										'description'   => $type_desc,
										'gst_value'     => $gst_value,
										'pdt_price'     => $pdt_price,
										'pdt_qty'       => strval($pdt_qty),
										'taxable_value' => $TaxableTot,
										'tax_value'     => $tax_value,
										'net_value'     => $pdt_value,
										'log_data'      => 'Product deleted by distributor',
							    	);

							    	array_push($data_list, $delete_list);
							    }

							    // mis match quantity
							    if($pdt_qty >= $order_qty)
							    {
							    	$pdt_count  = $pdt_qty - $order_qty;
							    	if($pdt_count != 0)
							    	{
							    		$gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
				                        $price_val  = $pdt_price - $gst_data;
				                        $pdt_gst    = $pdt_count * $gst_data;
				                        $TaxableAmt = $pdt_count * $price_val;
				                        $tot_price  = $pdt_count * $pdt_price;
				                        $tax_value  = number_format((float)$pdt_gst, 2, '.', '');
			                        	$TaxableTot = number_format((float)$TaxableAmt, 2, '.', '');
				                        $pdt_value  = number_format((float)round($tot_price), 2, '.', '');

								    	$invalid_list = array(
								    		'order_no'      => $order_no,
											'emp_name'      => !empty($emp_name)?$emp_name:'Admin',
											'store_name'    => $store_name,
											'state_name'    => $state_name,
											'city_name'     => $city_name,
											'zone_name'     => $zone_name,
											'description'   => $type_desc,
											'gst_value'     => $gst_value,
											'pdt_price'     => $pdt_price,
											'pdt_qty'       => strval($pdt_count),
											'taxable_value' => $TaxableTot,
											'tax_value'     => $tax_value,
											'net_value'     => $pdt_value,
											'log_data'      => 'Product quantity change by admin',
								    	);

								    	array_push($data_list, $invalid_list);
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
	}
?>
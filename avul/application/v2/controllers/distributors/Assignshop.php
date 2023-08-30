<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Assignshop extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function add_assign_shop($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	// Session data
        	$distributor_type = $this->session->userdata('distributor_type');
        	$distributor_id   = $this->session->userdata('id');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$employee_id = $this->input->post('employee_id');
				$month_id    = $this->input->post('month_id');
				$month_val   = $this->input->post('month_val');
				$year_val    = $this->input->post('year_val');
				$auto_id     = $this->input->post('auto_id');
				$date_val    = $this->input->post('date_val');
				$day_val     = $this->input->post('day_val');
				$method      = $this->input->post('method');

				$required = array('employee_id', 'month_id');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if(count(array_filter($date_val))!==count($date_val) || $error == TRUE)
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
			    	if($method == 'BTBM_X_C')
			    	{
			    		// Employee Details
				    	$where = array(
		            		'employee_id' => $employee_id,
		            		'method'      => '_detailEmployee'
		            	);

		            	$data_list = avul_call(API_URL.'employee/api/employee',$where);

		            	$username  = !empty($data_list['data'][0]['username'])?$data_list['data'][0]['username']:'';

		            	$assign_value  = [];
		            	for ($i=1; $i <= $month_val; $i++) { 

		            		$date_val = date('d-m-Y', strtotime($i.'-'.$month_id.'-'.$year_val));
				    		$day_val  = date('l', strtotime($date_val));

				    		$zone_res = '';
		            		$zone_val = $this->input->post('zone_id_'.$i);
		            		if(!empty($zone_val))
		            		{
		            			$zone_res .= implode(',', $zone_val);
		            		}

		            		$assign_value[] = array(
		            			'auto_id'      => $i,
		            			'assign_date'  => $date_val,
		            			'assign_day'   => $day_val,
		            			'assign_store' => $zone_res,
		            		);	
		            	}

		            	$store_value = json_encode($assign_value);

				    	$data = array(
				    		'distributor_type' => $distributor_type,
				    		'distributor_id'   => $distributor_id,
					    	'employee_id'      => $employee_id,
					    	'employee_name'    => $username,
					    	'month_id'         => $month_id,
					    	'store_value'      => $store_value,
					    	'financial_id'     => $this->session->userdata('active_year'),
					    	'method'           => '_addAssignInvoice',
					    );

					    $data_save = avul_call(API_URL.'assigninvoice/api/add_assign_invoice',$data);

					    if($data_save['status'] == 1)
					    {
					    	$response['status']  = 1;
					        $response['message'] = $data_save['message']; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
					    }
					    else
					    {
					    	$response['status']  = 0;
					        $response['message'] = $data_save['message']; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 	
					    }
			    	}
			    	else
			    	{
			    		$assign_id  = $this->input->post('assign_id');
			    		$auto_id    = $this->input->post('auto_id');
			    		$auto_count = count($auto_id);

			    		// Employee Details
				    	$where = array(
		            		'employee_id' => $employee_id,
		            		'method'      => '_detailEmployee'
		            	);

		            	$data_list = avul_call(API_URL.'employee/api/employee',$where);

		            	$username  = !empty($data_list['data'][0]['username'])?$data_list['data'][0]['username']:'';

		            	$assign_value  = [];
		            	$j = 1;
		            	for ($i=0; $i < $auto_count; $i++) { 
		            		
		            		$zone_res = '';
		            		$zone_val = $this->input->post('zone_id_'.$j);
		            		if(!empty($zone_val))
		            		{
		            			$zone_res .= implode(',', $zone_val);
		            		}

		            		$assign_value[] = array(
		            			'auto_id'      => $auto_id[$i],
		            			'assign_date'  => $date_val[$i],
		            			'assign_day'   => $day_val[$i],
		            			'assign_store' => $zone_res,
		            		);	

		            		$j++;
		            	}

		            	$assign_invoice = json_encode($assign_value);

		            	$data = array(
		            		'assign_id'      => $assign_id,
					    	'employee_id'    => $employee_id,
					    	'employee_name'  => $username,
					    	'month_id'       => $month_id,
					    	'store_value'    => $assign_invoice,
					    	'financial_id'   => $this->session->userdata('active_year'),
					    	'method'         => '_updateAssignShop',
					    );

					    $data_save = avul_call(API_URL.'assigninvoice/api/add_assign_invoice',$data);

					    if($data_save['status'] == 1)
					    {
					    	$response['status']  = 1;
					        $response['message'] = $data_save['message']; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
					    }
					    else
					    {
					    	$response['status']  = 0;
					        $response['message'] = $data_save['message']; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 	
					    }
			    	}
			    }
			}

			if($method =='getMonthData')
			{
				$error = FALSE;
				$employee_id = $this->input->post('employee_id');
				$month_id    = $this->input->post('month_id');
				$active_year = $this->session->userdata('active_year');

				$required = array('employee_id', 'month_id');
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
			    	// Financial Year Details
			    	$where_1 = array(
			    		'financial_id' => $active_year,
                        'method'       => '_detailFinancial',
                    );

                    $financial_data = avul_call(API_URL.'master/api/financial',$where_1);

                    $financial_year = !empty($financial_data['data'][0]['financial_name'])?$financial_data['data'][0]['financial_name']:'';

                    $financial_val  = explode('-', $financial_year);

                    // Zone List
                    $where_2 = array(
	            		'financial_year' => $active_year,
			    		'distributor_id' => $distributor_id,
                        'method'         => '_listInvoiceDistributorOrder',
	            	);

	            	$order_list  = avul_call(API_URL.'order/api/distributor_manage_order',$where_2);
	            	$order_value = !empty($order_list['data'])?$order_list['data']:'';

			    	if(4 <= $month_id)
			    	{
			    		$year_val = $financial_val[0];
			    	}
			    	else
			    	{
			    		$year_val = $financial_val[1];
			    	}

			    	$month_count = cal_days_in_month(CAL_GREGORIAN, $month_id, $year_val);

			    	$table = '';
			    	for ($i=1; $i <= $month_count; $i++) { 

			    		$date_val = date('d-m-Y', strtotime($i.'-'.$month_id.'-'.$year_val));
			    		$day_val  = date('l', strtotime($date_val));

			    		$table .= '
			    			<tr class="row_'.$i.'">
                                <td>'.$date_val.'</td>
                                <td>'.$day_val.'</td>
                                <td data-te="'.$i.'" class="p-l-0 order_list">
		                            <select class="form-control order_id js-example-placeholder-multiple" id="zone_id" name="zone_id_'.$i.'[]" multiple="multiple">';

		                            	if(!empty($order_value))
		                            	{
		                            		foreach ($order_value as $key => $value) {
							        			$zone_id   = !empty($value['zone_id'])?$value['zone_id']:'';
											    $zone_name = !empty($value['zone_name'])?$value['zone_name']:'';

											    $table .= '<option value="'.$zone_id.'">'.$zone_name.'</<option>';
		                            		}
		                            	}
		                                
		                            $table .=' </select> 
		                            <input type="hidden" name="date_val[]" value="'.$date_val.'">
		                            <input type="hidden" name="day_val[]" value="'.$day_val.'">
		                            <input type="hidden" name="auto_id[]" value="'.$i.'">
		                        </td>
                            </tr>
			    		';
			    	}

			    	$response['status']    = 1;
			        $response['message']   = 'success'; 
			        $response['data']      = $table;
			        $response['month_val'] = $month_count;
			        $response['year_val']  = $year_val;
			        echo json_encode($response);
			        return;
			    }
			}

			else
			{
				if($param1 =='Edit')
				{
					$assign_id = !empty($param2)?$param2:'';

					$where_1 = array(
	            		'assign_id' => $assign_id,
	            		'method'    => '_detailAssignInvoice'
	            	);

	            	$data_list   = avul_call(API_URL.'assigninvoice/api/manage_assign_invoice',$where_1);

	            	$active_year = $this->session->userdata('active_year');

	            	$where_2 = array(
	            		'financial_year' => $active_year,
			    		'distributor_id' => $distributor_id,
                        'method'         => '_listBothDistributorOrder',
	            	);

	            	$order_list  = avul_call(API_URL.'order/api/distributor_manage_order',$where_2);

	            	$order_value = !empty($order_list['data'])?$order_list['data']:'';

	            	$page['order_list'] = $order_value;
					$page['dataval']    = $data_list['data'];
					$page['method']     = 'BTBM_X_U';
					$page['page_title'] = "Edit Assign Beat";
				}
				else
				{
					$page['dataval']    = '';
					$page['method']     = 'BTBM_X_C';
					$page['page_title'] = "Add Assign Beat";
				}

				$where_1 = array(
            		'log_type'   => '1',
            		'company_id' => $distributor_id,
            		'method'     => '_typeCompanyWiseEmployee'
            	);

            	$data_list  = avul_call(API_URL.'employee/api/employee',$where_1);
            	$emp_list   = !empty($data_list['data'])?$data_list['data']:'';

            	$where_2 = array(
            		'method'   => '_listMonth'
            	);

            	$month_data = avul_call(API_URL.'master/api/month',$where_2);
            	$month_list = !empty($month_data['data'])?$month_data['data']:'';


            	$page['emp_list']     = $emp_list;
            	$page['month_list']   = $month_list;
				$page['main_heading'] = "Assign Beat";
				$page['sub_heading']  = "Assign Beat";
				$page['pre_title']    = "List Assign Beat";
				$page['pre_menu']     = "index.php/distributors/assignshop/list_assign_shop";
				$data['page_temp']    = $this->load->view('distributors/assign_shop/add_assign_shop',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_assign_bill";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		public function list_assign_shop($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	// Session Data
        	$distributor_id = $this->session->userdata('id');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Assign Beat";
				$page['sub_heading']  = "Assign Beat";
				$page['page_title']   = "List Assign Beat";
				$page['pre_title']    = "Add Assign Beat";
				$page['pre_menu']     = "index.php/distributors/assignshop/add_assign_shop";
				$data['page_temp']    = $this->load->view('distributors/assign_shop/list_assign_shop',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_assign_bill";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				$limit    = $this->input->post('limitval');
            	$page     = $this->input->post('page');
            	$search   = $this->input->post('search');
            	$cur_page = isset($page)?$page:'1';
            	$_offset  = ($cur_page-1) * $limit;
            	$nxt_page = $cur_page + 1;
            	$pre_page = $cur_page - 1;

            	$where = array(
            		'offset'          => $_offset,
            		'limit'           => $limit,
            		'search'          => $search,
            		'distributor_id'  => $distributor_id,
            		'financial_year'  => $this->session->userdata('active_year'),
            		'method'          => '_listAssignInvoicePaginate',
            	);

            	$data_list  = avul_call(API_URL.'assigninvoice/api/manage_assign_invoice',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	if(!empty($data_value))
            	{
            		$count    = count($data_value);
	            	$total    = isset($data_list['total_record'])?$data_list['total_record']:'';
	            	$tot_page = ceil($total / $limit); 

            		$status  = 1;
	            	$message = 'Success';
	            	$table   = '';

	            	$i=1;
	            	foreach ($data_value as $key => $value) {
	            		$assign_id     = !empty($value['assign_id'])?$value['assign_id']:'';
			            $employee_id   = !empty($value['employee_id'])?$value['employee_id']:'';
			            $employee_name = !empty($value['employee_name'])?$value['employee_name']:'';
			            $month_id      = !empty($value['month_id'])?$value['month_id']:'';
			            $financial_id  = !empty($value['financial_id'])?$value['financial_id']:'';
			            $active_status = !empty($value['status'])?$value['status']:'';
			            $month_value   = monthName($month_id);

			            if($active_status == '1')
		                {
		                	$status_view = '<span class="badge badge-success">Active</span>';
		                }
		                else
		                {
		                	$status_view = '<span class="badge badge-danger">In Active</span>';
		                }

		                $table .= '
					    	<tr class="row_'.$i.'">
                                <td class="line_height">'.$i.'</td>
                                <td class="line_height">'.$employee_name.'</td>
                                <td class="line_height">'.$month_value.'</td>
                                <td class="line_height">'.$status_view.'</td>
                                <td>
                                	<a href="'.BASE_URL.'index.php/distributors/assignshop/add_assign_shop/Edit/'.$assign_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>
                                	<a data-row="'.$i.'" data-id="'.$assign_id.'" data-value="distributors" data-cntrl="assignshop" data-func="list_assign_shop" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>
                                </td>
                            </tr>
					    ';

		                $i++;
	            	}

	            	$prev    = '';

	            	$next = '
		        		<tr>
		        			<td>';
		        				if($cur_page >= 2):
		        				$next .='<span data-page="'.$pre_page.'" class="pages btn btn-warning waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>';
		        				endif;
		        			$next .= '</td>
		        			<td>';
		        				if($tot_page > $cur_page):
		        				$next .='<span data-page="'.$nxt_page.'" class="pages btn btn-success waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
		        				endif;
		        			$next .='</td>
		        		</tr>
		        	';
            	}
            	else
            	{
            		$status     = 0;
	            	$message    = 'No Records';
	            	$table      = '';
	            	$next       = '';
	            	$prev       = '';
            	}

            	$response['status']     = $status;  
                $response['result']     = $table;  
                $response['message']    = $message;
                $response['next']       = $next;
                $response['prev']       = $prev;
                echo json_encode($response);
                return;
			}

			else if($param1 == 'delete')
			{
				$id = $this->input->post('id');

				if(!empty($id))	
				{
					$data = array(
				    	'assign_id' => $id,
				    	'method'    => '_deleteAssignInvoice'
				    );

				    $data_save = avul_call(API_URL.'assigninvoice/api/manage_assign_invoice',$data);

				    if($data_save['status'] == 1)
				    {
				    	$response['status']  = 1;
				        $response['message'] = $data_save['message']; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
				    else
				    {
				    	$response['status']  = 0;
				        $response['message'] = $data_save['message']; 
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
			        $response['error']   = []; 
			        echo json_encode($response);
			        return;
				}
			}
		}

		public function add_assign_beat($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage         = $this->input->post('formpage');
			$method           = $this->input->post('method');
			$distributor_type = $this->session->userdata('distributor_type');
        	$distributor_id   = $this->session->userdata('id');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$employee_id = $this->input->post('employee_id');
				$month_id    = $this->input->post('month_id');
				$month_val   = $this->input->post('month_val');
				$year_val    = $this->input->post('year_val');
				$auto_id     = $this->input->post('auto_id');
				$date_val    = $this->input->post('date_val');
				$day_val     = $this->input->post('day_val');
				$method      = $this->input->post('method');

				$required = array('employee_id', 'month_id');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if(count(array_filter($date_val))!==count($date_val) || $error == TRUE)
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
			    	if($method == 'BTBM_X_C')
			    	{
		    			// Employee Details
				    	$where = array(
		            		'employee_id' => $employee_id,
		            		'method'      => '_detailEmployee'
		            	);

		            	$data_list = avul_call(API_URL.'employee/api/employee',$where);

		            	$username  = !empty($data_list['data'][0]['username'])?$data_list['data'][0]['username']:'';

		            	$assign_value  = [];
		            	for ($i=1; $i <= $month_val; $i++) { 

		            		$date_val    = date('d-m-Y', strtotime($i.'-'.$month_id.'-'.$year_val));
				    		$day_val     = date('l', strtotime($date_val));

				    		$zone_res    = '';
		            		$zone_val    = $this->input->post('zone_id_'.$i);
		            		$meeting_val = $this->input->post('meeting_val_'.$i);
		            		if(!empty($zone_val))
		            		{
		            			$zone_res .= implode(',', $zone_val);
		            		}

		            		$assign_value[] = array(
		            			'auto_id'      => $i,
		            			'assign_date'  => $date_val,
		            			'assign_day'   => $day_val,
		            			'assign_store' => $zone_res,
		            			'meeting_val'  => $meeting_val,
		            		);	
		            	}

		            	$store_value = json_encode($assign_value);

				    	$data = array(
					    	'employee_id'    => $employee_id,
					    	'employee_name'  => $username,
					    	'month_id'       => $month_id,
					    	'store_value'    => $store_value,
					    	'financial_id'   => $this->session->userdata('active_year'),
					    	'assign_type'    => 2,
					    	'distributor_id' => $distributor_id,
					    	'method'         => '_addAssignShop',
					    );

					    $data_save = avul_call(API_URL.'assignshop/api/add_assign_shop', $data);

					    if($data_save['status'] == 1)
					    {
					    	$response['status']  = 1;
					        $response['message'] = $data_save['message']; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
					    }
					    else
					    {
					    	$response['status']  = 0;
					        $response['message'] = $data_save['message']; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 	
					    }
			    	}
			    	else
			    	{
		    			$assign_id  = $this->input->post('assign_id');
			    		$auto_id    = $this->input->post('auto_id');
			    		$auto_count = count($auto_id);

			    		// Employee Details
				    	$where = array(
		            		'employee_id' => $employee_id,
		            		'method'      => '_detailEmployee'
		            	);

		            	$data_list = avul_call(API_URL.'employee/api/employee',$where);

		            	$username  = !empty($data_list['data'][0]['username'])?$data_list['data'][0]['username']:'';

		            	$assign_value  = [];
		            	$j = 1;
		            	for ($i=0; $i < $auto_count; $i++) { 
		            		
		            		$zone_res    = '';
		            		$zone_val    = $this->input->post('zone_id_'.$j);
		            		$meeting_val = $this->input->post('meeting_val_'.$j);
		            		if(!empty($zone_val))
		            		{
		            			$zone_res .= implode(',', $zone_val);
		            		}

		            		$assign_value[] = array(
		            			'auto_id'      => $auto_id[$i],
		            			'assign_date'  => $date_val[$i],
		            			'assign_day'   => $day_val[$i],
		            			'assign_store' => $zone_res,
		            			'meeting_val'  => $meeting_val,
		            		);

		            		$j++;
		            	}

		            	$store_value = json_encode($assign_value);

		            	$data = array(
		            		'assign_id'      => $assign_id,
					    	'employee_id'    => $employee_id,
					    	'employee_name'  => $username,
					    	'month_id'       => $month_id,
					    	'store_value'    => $store_value,
					    	'assign_type'    => 2,
					    	'distributor_id' => $distributor_id,
					    	'financial_id'   => $this->session->userdata('active_year'),
					    	'method'         => '_updateAssignShop',
					    );

					    $data_save = avul_call(API_URL.'assignshop/api/add_assign_shop',$data);

					    if($data_save['status'] == 1)
					    {
					    	$response['status']  = 1;
					        $response['message'] = $data_save['message']; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
					    }
					    else
					    {
					    	$response['status']  = 0;
					        $response['message'] = $data_save['message']; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 	
					    }
			    	}
			    }
			}

			if($method =='getMonthData')
			{
				$error = FALSE;
				$employee_id = $this->input->post('employee_id');
				$month_id    = $this->input->post('month_id');
				$active_year = $this->session->userdata('active_year');

				$required = array('employee_id', 'month_id');
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
			    	// Financial Year Details
			    	$where_1 = array(
			    		'financial_id' => $active_year,
                        'method'       => '_detailFinancial',
                    );

                    $financial_data = avul_call(API_URL.'master/api/financial',$where_1);

                    $financial_year = !empty($financial_data['data'][0]['financial_name'])?$financial_data['data'][0]['financial_name']:'';

                    $financial_val  = explode('-', $financial_year);

                    // Zone List
                    $where_2 = array(
	            		'method'  => '_overallZone'
	            	);

	            	$zone_list  = avul_call(API_URL.'master/api/zone',$where_2);
	            	$zone_value = !empty($zone_list['data'])?$zone_list['data']:'';

			    	if(4 <= $month_id)
			    	{
			    		$year_val = $financial_val[0];
			    	}
			    	else
			    	{
			    		$year_val = $financial_val[1];
			    	}

			    	$month_count = cal_days_in_month(CAL_GREGORIAN, $month_id, $year_val);

			    	$table = '';
			    	for ($i=1; $i <= $month_count; $i++) { 

			    		$date_val = date('d-m-Y', strtotime($i.'-'.$month_id.'-'.$year_val));
			    		$day_val  = date('l', strtotime($date_val));

			    		$table .= '
			    			<tr class="row_'.$i.'">
                                <td>'.$date_val.'</td>
                                <td>'.$day_val.'</td>
                                <td data-te="'.$i.'" class="p-l-0 zone_list">
		                            <select class="form-control zone_id js-example-placeholder-multiple" id="zone_id" name="zone_id_'.$i.'[]" multiple="multiple">';

		                            	if(!empty($zone_value))
		                            	{
		                            		foreach ($zone_value as $key => $value) {
							        			$zone_id   = !empty($value['zone_id'])?$value['zone_id']:'';
											    $state_id  = !empty($value['state_id'])?$value['state_id']:'';
											    $city_id   = !empty($value['city_id'])?$value['city_id']:'';
											    $zone_name = !empty($value['zone_name'])?$value['zone_name']:'';

											    $table .= '<option value="'.$zone_id.'">'.$zone_name.'</<option>';
		                            		}
		                            	}
		                                
		                            $table .=' </select> 
		                            <input type="hidden" name="date_val[]" value="'.$date_val.'">
		                            <input type="hidden" name="day_val[]" value="'.$day_val.'">
		                            <input type="hidden" name="auto_id[]" value="'.$i.'">
		                        </td>
		                        <td>
		                        	<input type="checkbox" class="meeting_res" data-te="'.$i.'" id="meeting_status" name="meeting_status_'.$i.'" style="margin-top: 15px;">
		                        	<input type="hidden" name="meeting_val_'.$i.'" class="meeting_val_'.$i.'" value="0">
		                        </td>
                            </tr>
			    		';
			    	}

			    	$response['status']    = 1;
			        $response['message']   = 'success'; 
			        $response['data']      = $table;
			        $response['month_val'] = $month_count;
			        $response['year_val']  = $year_val;
			        echo json_encode($response);
			        return;
			    }
			}

			else
			{
				if($param1 =='Edit')
				{
					$assign_id = !empty($param2)?$param2:'';

					$where = array(
	            		'assign_id' => $assign_id,
	            		'method'    => '_detailAssignShop'
	            	);

	            	$data_list  = avul_call(API_URL.'assignshop/api/manage_assign_shop',$where);

					$page['dataval']    = $data_list['data'];
					$page['method']     = 'BTBM_X_U';
					$page['page_title'] = "Edit Assign Beat";
				}
				else
				{
					$page['dataval']    = '';
					$page['method']     = 'BTBM_X_C';
					$page['page_title'] = "Add Assign Beat";
				}

				$where_1 = array(
					'log_type'   => '2',
            		'company_id' => $distributor_id,
            		'method'     => '_typeCompanyWiseEmployee'
            	);

            	$data_list  = avul_call(API_URL.'employee/api/employee',$where_1);
            	$emp_list   = !empty($data_list['data'])?$data_list['data']:'';

            	$where_2 = array(
            		'method'   => '_listMonth'
            	);

            	$month_data = avul_call(API_URL.'master/api/month',$where_2);
            	$month_list = !empty($month_data['data'])?$month_data['data']:'';

            	$where_3 = array(
            		'method'  => '_overallZone'
            	);

            	$zone_data  = avul_call(API_URL.'master/api/zone',$where_3);
            	$zone_list  = !empty($zone_data['data'])?$zone_data['data']:'';

            	$page['emp_list']     = $emp_list;
            	$page['month_list']   = $month_list;
            	$page['zone_list']    = $zone_list;
				$page['main_heading'] = "Assign Beat";
				$page['sub_heading']  = "Assign Beat";
				$page['pre_title']    = "List Assign Beat";
				$page['page_access']  = userAccess('assign-beat-view');
				$page['pre_menu']     = "index.php/distributors/assignshop/list_assign_beat";
				$data['page_temp']    = $this->load->view('distributors/assign_shop/add_assign_beat',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_assign_shop";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		public function list_assign_beat($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$distributor_id   = $this->session->userdata('id');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Assign Beat";
				$page['sub_heading']  = "Assign Beat";
				$page['page_title']   = "List Assign Beat";
				$page['pre_title']    = "Add Assign Beat";
				$page['page_access']  = "";
				$page['pre_menu']     = "index.php/distributors/assignshop/add_assign_shop";
				$data['page_temp']    = $this->load->view('distributors/assign_shop/list_assign_beat',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_assign_shop";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				$limit    = $this->input->post('limitval');
            	$page     = $this->input->post('page');
            	$search   = $this->input->post('search');
            	$cur_page = isset($page)?$page:'1';
            	$_offset  = ($cur_page-1) * $limit;
            	$nxt_page = $cur_page + 1;
            	$pre_page = $cur_page - 1;

            	$where = array(
            		'offset'          => $_offset,
            		'limit'           => $limit,
            		'search'          => $search,
            		'assign_type'     => 2,
            		'distributor_id'  => $distributor_id,
            		'financial_year'  => $this->session->userdata('active_year'),
            		'method'          => '_listAssignShopPaginate',
            	);

            	$data_list  = avul_call(API_URL.'assignshop/api/manage_assign_shop',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	if(!empty($data_value))
            	{
            		$count    = count($data_value);
	            	$total    = isset($data_list['total_record'])?$data_list['total_record']:'';
	            	$tot_page = ceil($total / $limit); 

            		$status  = 1;
	            	$message = 'Success';
	            	$table   = '';

	            	$i=1;
	            	foreach ($data_value as $key => $value) {
	            		$assign_id     = !empty($value['assign_id'])?$value['assign_id']:'';
			            $employee_id   = !empty($value['employee_id'])?$value['employee_id']:'';
			            $employee_name = !empty($value['employee_name'])?$value['employee_name']:'';
			            $month_id      = !empty($value['month_id'])?$value['month_id']:'';
			            $financial_id  = !empty($value['financial_id'])?$value['financial_id']:'';
			            $active_status = !empty($value['status'])?$value['status']:'';
			            $month_value   = monthName($month_id);

			            if($active_status == '1')
		                {
		                	$status_view = '<span class="badge badge-success">Active</span>';
		                }
		                else
		                {
		                	$status_view = '<span class="badge badge-danger">In Active</span>';
		                }

			            $edit = '<a href="'.BASE_URL.'index.php/distributors/assignshop/add_assign_beat/Edit/'.$assign_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';

			            $delete = '<a data-row="'.$i.'" data-id="'.$assign_id.'" data-value="distributors" data-cntrl="assignshop" data-func="list_assign_beat" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';

		                $table .= '
					    	<tr class="row_'.$i.'">
                                <td class="line_height">'.$i.'</td>
                                <td class="line_height">'.$employee_name.'</td>
                                <td class="line_height">'.$month_value.'</td>
                                <td class="line_height">'.$status_view.'</td>';
	                            $table .= '<td>'.$edit.$delete.'</td>';
                            $table .='</tr>
					    ';

		                $i++;
	            	}

	            	$prev    = '';

	            	$next = '
		        		<tr>
		        			<td>';
		        				if($cur_page >= 2):
		        				$next .='<span data-page="'.$pre_page.'" class="pages btn btn-warning waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>';
		        				endif;
		        			$next .= '</td>
		        			<td>';
		        				if($tot_page > $cur_page):
		        				$next .='<span data-page="'.$nxt_page.'" class="pages btn btn-success waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
		        				endif;
		        			$next .='</td>
		        		</tr>
		        	';
            	}
            	else
            	{
            		$status     = 0;
	            	$message    = 'No Records';
	            	$table      = '';
	            	$next       = '';
	            	$prev       = '';
            	}

            	$response['status']     = $status;  
                $response['result']     = $table;  
                $response['message']    = $message;
                $response['next']       = $next;
                $response['prev']       = $prev;
                echo json_encode($response);
                return;
			}

			else if($param1 == 'delete')
			{
				$id = $this->input->post('id');

				if(!empty($id))	
				{
					$data = array(
				    	'assign_id' => $id,
				    	'method'    => '_deleteAssignShop'
				    );

				    $data_save = avul_call(API_URL.'assignshop/api/manage_assign_shop',$data);

				    if($data_save['status'] == 1)
				    {
				    	$response['status']  = 1;
				        $response['message'] = $data_save['message']; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
				    else
				    {
				    	$response['status']  = 0;
				        $response['message'] = $data_save['message']; 
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
			        $response['error']   = []; 
			        echo json_encode($response);
			        return;
				}
			}
		}

	}
?>
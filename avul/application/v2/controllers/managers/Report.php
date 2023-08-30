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

		public function attendance_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');
          
         
           
           
        	if($method == '_getAttendanceData')
        	{
				$error = false;
				$required = array('start_date','end_date','position_id');
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
					$start_date  = $this->input->post('start_date');
					$end_date    = $this->input->post('end_date');
					$employee_id = $this->input->post('employee_id');
					$hierarchy = $this->input->post('position_id');
					$att_status = $this->input->post('pstatus');

					$att_whr = array(
						'start_date'  => date('Y-m-d', strtotime($start_date)),
						'end_date'    => date('Y-m-d', strtotime($end_date)),
						'mg_id'       => $this->session->userdata('id'),
						'employee_id' => $employee_id,
						'att_status'  => $att_status,
						'hierarchy'   => $hierarchy,
						'emp_type'    => 2,
						'method'      => '_attendanceReportMg',
					);
				
					$data_list  = avul_call(API_URL.'report/api/attendace_report',$att_whr);
					
					if($data_list['status'] == 1)
					{
						$html     = '';
						$data_val = $data_list['data'];
	
						$num = 1;
						foreach ($data_val as $key => $value) {
	
							$emp_name   = !empty($value['emp_name'])?$value['emp_name']:'';
							$store_name = !empty($value['store_name'])?$value['store_name']:'';
							$att_type   = !empty($value['att_type'])?$value['att_type']:'';
							$c_date     = !empty($value['c_date'])?$value['c_date']:'---';
							$in_time    = !empty($value['in_time'])?$value['in_time']:'---';
							$out_time   = !empty($value['out_time'])?$value['out_time']:'---';
							$c_image   = !empty($value['c_image'])?$value['c_image']:'';
							if($att_type == 1)
							{
								$type_name = 'Sales Order';
							}
							else if($att_type == 2)
							{
								$type_name = 'No Order';
							}
							else
							{
								$type_name = 'Pending';
							}
							if(!empty($c_image))
							{
								$img_value = FILE_URL.'attendance/'.$c_image;
							}
							else
							{
								$img_value = BASE_URL.'app-assets/images/img_icon.png';
							}
							$html .= '
								<tr>
									<td>'.$num.'</td>
									<td>'.mb_strimwidth($emp_name, 0, 12, '...').'</td>
									<td>'.mb_strimwidth($store_name, 0, 20, '...').'</td>
									<td>'.date('d-M-Y', strtotime($c_date)).'</td>
									<td>'.$in_time.'</td>
									<td>'.$out_time.'</td>
									<td><img src="'.$img_value.'" data-id="'.$img_value.'" id="account-upload-img" class="uploadedAvatar1 rounded me-50" alt="attendance image" height="45" width="45"/></td>
								</tr>
							';
	
							$num++;
						}
	
						$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/attendance_report/excel_print/'.$start_date.'/'.$end_date.'/'.$employee_id.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';
	
						$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/attendance_report/pdf_print/'.$start_date.'/'.$end_date.'/'.$employee_id.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';
	
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
        		$start_date  = $param2; 
        		$end_date    = $param3;
        		$employee_id = $param4;

        		$att_whr = array(
			    	'start_date'  => date('Y-m-d', strtotime($start_date)),
					'end_date'    => date('Y-m-d', strtotime($end_date)),
			    	'employee_id' => $employee_id,
			    	'method'      => '_attendanceReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/attendace_report',$att_whr);
		    	
			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=attendance_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Employee Name','Store Name','Date', 'Clock In', 'Clock Out', 'Attendance Type', 'Order No', 'Order Value', 'Reason'));

		    	if($data_list['status'] == 1)
		    	{
		    		$data_val = $data_list['data'];	
		    		foreach ($data_val as $key => $value) {
		    			
		    			$emp_name   = !empty($value['emp_name'])?$value['emp_name']:'';
			            $store_name = !empty($value['store_name'])?$value['store_name']:'';
			            $att_type   = !empty($value['att_type'])?$value['att_type']:'';
			            $c_date     = !empty($value['c_date'])?$value['c_date']:'---';
			            $in_time    = !empty($value['in_time'])?$value['in_time']:'---';
			            $out_time   = !empty($value['out_time'])?$value['out_time']:'---';
			            $order_num  = !empty($value['order_num'])?$value['order_num']:'---';
			            $order_tot  = !empty($value['order_tot'])?$value['order_tot']:'---';
			            $reason     = !empty($value['reason'])?$value['reason']:'---';

			            if($att_type == 1)
                        {
                            $type_name = 'Sales Order';
                        }
                        else if($att_type == 2)
                        {
                            $type_name = 'No Order';
                        }
                        else
                        {
                            $type_name = 'Pending';
                        }

                        $num = array(
                        	$emp_name,
                        	$store_name,
                        	$c_date,
                        	$in_time,
                        	$out_time,
                        	$type_name,
                        	$order_num,
                        	$order_tot,
                        	$reason	
                        );

                        fputcsv($output, $num);  
                    }
		    	}

			    fclose($output);
      			exit();
        	}
			if($param1 == 'emp_list')
        	{
        		$designation_code  = $this->input->post('designation_code');
			  
			   // value position_id like RSM,ASM ... position_id have designation code
				
			    $att_whr = array(
			    	'designation_code'  => $designation_code,
					'manager_id'  =>  $this->session->userdata('id'),
			    	'method'      => 'gethierarchy',
			    );
				
			    $data_list  = avul_call(API_URL.'managers/api/hierarchy_list',$att_whr);
		    	
		    	$data_val  = $data_list['data'];
				
			
        		$option ='<option value="">Select Employee Name</option>';

        		if(!empty($data_val))
        		{
        			foreach ($data_val as $key => $value) {
        				$employee_id   = !empty($value['employee_id']) ?$value['employee_id']:'';
                        $mobile = !empty($value['mobile'])?$value['mobile']:'';
						$position_id =!empty($value['position_id'])?$value['position_id']:'';
						$name =!empty($value['name'])?$value['name']:'';

                        $select   = '';
        				

                        $option .= '<option value="'.$employee_id.'" '.$select.'>'.$name.$mobile.'</option>';
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


	     			$where_1 = array(
						'designation_code' => $this->session->userdata('designation_code'),
						'method'   => '_listDesignation'
					);
			

		    	$data_list  = avul_call(API_URL.'employee/api/employee_designation',$where_1);
		    	$desgination_list   = !empty($data_list['data'])?$data_list['data']:'';

                $page['manager_id']       = $this->session->userdata('id');
		    	$page['method']       = '_getAttendanceData';
		    	$page['desgination_list']     = $desgination_list;
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Attendance Report";
				$page['page_title']   = "Attendance Report";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('managers/report/attendance_report',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "attendance_report";
				$this->bassthaya->load_Managers_form_template($data);
        	}
    	}	
		public function attendance_details_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method == '_getAttendanceData')
        	{
        		$start_date  = $this->input->post('start_date');
			    $end_date    = $this->input->post('end_date');
			    $employee_id = $this->input->post('employee_id');
				
			    $att_whr = array(
			    	'start_date'  => date('Y-m-d', strtotime($start_date)),
			    	'end_date'    => date('Y-m-d', strtotime($end_date)),
			    	'employee_id' => $employee_id,
					'mg_id'       => $this->session->userdata('id'),
					'emp_type'    => 2,
			    	'method'      => '_attendanceDetailsReportMg',
			    );

			    $data_list  = avul_call(API_URL.'report/api/attendace_details_report',$att_whr);
		    	
		    	if($data_list['status'] == 1)
		    	{
		    		$html     = '';
		    		$data_val = $data_list['data'];

		    		$num = 1;
		    		foreach ($data_val as $key => $value) {

		    			$emp_name   = !empty($value['emp_name'])?$value['emp_name']:'';
			            $store_name = !empty($value['store_name'])?$value['store_name']:'';
			            $att_type   = !empty($value['att_type'])?$value['att_type']:'';
			            $c_date     = !empty($value['c_date'])?$value['c_date']:'---';
						$c_image     = !empty($value['c_image'])?$value['c_image']:'';
			            $in_time    = !empty($value['in_time'])?$value['in_time']:'---';
			            $out_time   = !empty($value['out_time'])?$value['out_time']:'---';

			            if($att_type == 1)
                        {
                            $type_name = 'Sales Order';
                        }
                        else if($att_type == 2)
                        {
                            $type_name = 'No Order';
                        }
                        else
                        {
                            $type_name = 'Pending';
                        }
						
						if(!empty($c_image))
						{
							$img_value = FILE_URL.'attendance/'.$c_image;
						}
						else
						{
							$img_value = BASE_URL.'app-assets/images/img_icon.png';
						}
						
						// $view = '<a href="'.BASE_URL.'index.php/admin/employee/add_employee/Edit/'.$employee_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
			            $html .= '
			            	<tr>
                                <td>'.$num.'</td>
                                <td>'.mb_strimwidth($emp_name, 0, 12, '...').'</td>
                                <td>'.mb_strimwidth($store_name, 0, 20, '...').'</td>
                                <td>'.date('d-M-Y', strtotime($c_date)).'</td>
                                <td>'.$in_time.'</td>
                                <td>'.$out_time.'</td>
								<td><img src="'.$img_value.'" data-id="'.$img_value.'" id="account-upload-img" class="uploadedAvatar1 rounded me-50" alt="attendance image" height="45" width="45"/></td>
                            </tr>
			            ';

			            $num++;
		    		}

		    		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/attendance_details_report/excel_print/'.$start_date.'/'.$end_date.'/'.$employee_id.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		    		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/attendance_details_report/pdf_print/'.$start_date.'/'.$end_date.'/'.$employee_id.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

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
        		$start_date  = $param2; 
        		$end_date    = $param3;
        		$employee_id = $param4;

        		$att_whr = array(
			    	'start_date'  => date('Y-m-d', strtotime($start_date)),
					'end_date'    => date('Y-m-d', strtotime($end_date)),
			    	'employee_id' => $employee_id,
					'emp_type'    => 2,
			    	'method'      => '_attendanceDetailsReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/attendace_details_report',$att_whr);
		    	
			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=attendance_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Employee Name','Store Name','Date', 'Clock In', 'Clock Out', 'Attendance Type', 'Order No', 'Order Value', 'Reason'));

		    	if($data_list['status'] == 1)
		    	{
		    		$data_val = $data_list['data'];	
		    		foreach ($data_val as $key => $value) {
		    			
		    			$emp_name   = !empty($value['emp_name'])?$value['emp_name']:'';
			            $store_name = !empty($value['store_name'])?$value['store_name']:'';
			            $att_type   = !empty($value['att_type'])?$value['att_type']:'';
			            $c_date     = !empty($value['c_date'])?$value['c_date']:'---';
			            $in_time    = !empty($value['in_time'])?$value['in_time']:'---';
			            $out_time   = !empty($value['out_time'])?$value['out_time']:'---';
			            $order_num  = !empty($value['order_num'])?$value['order_num']:'---';
			            $order_tot  = !empty($value['order_tot'])?$value['order_tot']:'---';
			            $reason     = !empty($value['reason'])?$value['reason']:'---';

			            if($att_type == 1)
                        {
                            $type_name = 'Sales Order';
                        }
                        else if($att_type == 2)
                        {
                            $type_name = 'No Order';
                        }
                        else
                        {
                            $type_name = 'Pending';
                        }

                        $num = array(
                        	$emp_name,
                        	$store_name,
                        	$c_date,
                        	$in_time,
                        	$out_time,
                        	$type_name,
                        	$order_num,
                        	$order_tot,
                        	$reason	
                        );

                        fputcsv($output, $num);  
                    }
		    	}

			    fclose($output);
      			exit();
        	}
			if($param1 == 'emp_list')
        	{
        		$designation_code  = $this->input->post('designation_code');
			  
			   // value position_id like RSM,ASM ... position_id have designation code
				
			    $att_whr = array(
			    	'designation_code'  => $designation_code,
					'manager_id'  =>  $this->session->userdata('id'),
			    	'method'      => 'gethierarchy',
			    );
				
			    $data_list  = avul_call(API_URL.'managers/api/hierarchy_list',$att_whr);
		    	
		    	$data_val  = $data_list['data'];
				
			
        		$option ='<option value="">Select Employee Name</option>';

        		if(!empty($data_val))
        		{
        			foreach ($data_val as $key => $value) {
        				$employee_id   = !empty($value['employee_id']) ?$value['employee_id']:'';
                        $mobile = !empty($value['mobile'])?$value['mobile']:'';
						$position_id =!empty($value['position_id'])?$value['position_id']:'';
						$name =!empty($value['name'])?$value['name']:'';

                        $select   = '';
        				

                        $option .= '<option value="'.$employee_id.'" '.$select.'>'.$name.$mobile.'</option>';
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
        		$where_1 = array(
					'designation_code' => $this->session->userdata('designation_code'),
					'method'   => '_listDesignation'
				);
		

			$data_list  = avul_call(API_URL.'employee/api/employee_designation',$where_1);
			$desgination_list   = !empty($data_list['data'])?$data_list['data']:'';

		    	$page['method']       = '_getAttendanceData';
				$page['manager_id']       = $this->session->userdata('id');
		    	$page['desgination_list']     = $desgination_list;
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Attendance Details Report";
				$page['page_title']   = "Attendance Details Report";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('managers/report/attendance_details_report',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "attendance_details_report";
				$this->bassthaya->load_Managers_form_template($data);
        	}
    	}
    	public function outlet_order($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
    	{
    		if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method == '_getOutletOrderData')
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
			    	$state_id   = $this->input->post('state_id');
	        		$city_id    = $this->input->post('city_id');
	        		$zone_id    = $this->input->post('zone_id');
	        		$start_date = $this->input->post('start_date');
				    $end_date   = $this->input->post('end_date');
				    $order_by   = $this->input->post('order_by');

			    	
					    $order_whr = array(
					    	'state_id'   => $state_id,
					    	'city_id'    => $city_id,
					    	'zone_id'    => $zone_id,
					    	'start_date' => date('Y-m-d', strtotime($start_date)),
							'end_date'   => date('Y-m-d', strtotime($end_date)),
					    	'order_by'   => $order_by,
					    	'method'     => '_overallOutletReport',
					    );

					    $data_list  = avul_call(API_URL.'report/api/order_report',$order_whr);
				    	
				    	if($data_list['status'] == 1)
				    	{
				    		$html     = '';
				    		$data_val = $data_list['data'];

				    		$num = 1;
				    		foreach ($data_val as $key => $val) {

					            $str_name    = !empty($val['str_name'])?$val['str_name']:'';
					            $mobile      = !empty($val['mobile'])?$val['mobile']:'';
					            $curr_bal    = !empty($val['curr_bal'])?$val['curr_bal']:'0';
					            $invoice_val = !empty($val['invoice_val'])?$val['invoice_val']:'0';

			                    $html .='
			                    	<tr>
		                                <td>'.$num.'</td>
		                                <td>'.mb_strimwidth($str_name, 0, 35, '...').'</td>
		                                <td>'.$mobile.'</td>
		                                <td>'.$curr_bal.'</td>
		                                <td>'.$invoice_val.'</td>
		                            </tr>
			                    ';

			                    $num++;
				    		}

				    		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/outlet_order/excel_print/'.$start_date.'/'.$end_date.'/'.$state_id.'/'.$city_id.'/'.$zone_id.'/'.$order_by.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

				    		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/outlet_order/pdf_print/'.$start_date.'/'.$end_date.'/'.$state_id.'/'.$city_id.'/'.$zone_id.'/'.$order_by.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

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

        	if($param1 =='getCity_name')
			{
				$state_id = $this->input->post('state_id');
				
				$where = array(
					'id'       => $this->session->userdata('id'),
            		'state_id' => $state_id,
            		'method'   => '_listCity'
            	);

            	$city_list   = avul_call(API_URL.'managers/api/hierarchy_list',$where);
            	$city_result = $city_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($city_result))
        		{
        			foreach ($city_result as $key => $value) {
        				$city_id   = !empty($value['id'])?$value['id']:'';
                        $city_name = !empty($value['city_name'])?$value['city_name']:'';
						$city_code = !empty($value['city_code'])?$value['city_code']:'';

                        $option .= '<option value="'.$city_id.'">'.$city_name.'('.$city_code.')'.'</option>';
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
					'id'       => $this->session->userdata('id'),
            		'state_id' => $state_id,
            		'city_id'  => $city_id,
            		'method'   => '_listZone'
            	);

            	$zone_list   = avul_call(API_URL.'managers/api/hierarchy_list',$where);
            	$zone_result = $zone_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($zone_result))
        		{
        			foreach ($zone_result as $key => $value) {
        				$zone_id   = !empty($value['id'])?$value['id']:'';
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

        	if($param1 == 'excel_print')
        	{
        		$start_date  = $param2; 
        		$end_date    = $param3;
        		$state_id    = $param4;
        		$city_id     = $param5; 
        		$zone_id     = $param6;
        		$order_by    = $param7;

        		$order_whr = array(
			    	'state_id'   => $state_id,
			    	'city_id'    => $city_id,
			    	'zone_id'    => $zone_id,
			    	'start_date' => date('Y-m-d', strtotime($start_date)),
					'end_date'   => date('Y-m-d', strtotime($end_date)),
			    	'order_by'   => $order_by,
			    	'method'     => '_overallOutletReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/order_report',$order_whr);

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=outlet_order_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Store Name', 'Mobile', 'State Name', 'City Name', 'Beat Name', 'Current Balance', 'Invoice Value'));

			    if($data_list['status'] == 1)
			    {
			    	$data_val = $data_list['data'];	
		    		foreach ($data_val as $key => $value) {
		    			$str_name    = !empty($value['str_name'])?$value['str_name']:'';
	                    $mobile      = !empty($value['mobile'])?$value['mobile']:'';
	                    $curr_bal    = !empty($value['curr_bal'])?$value['curr_bal']:'';
	                    $invoice_val = !empty($value['invoice_val'])?$value['invoice_val']:'';
	                    $state_name  = !empty($value['state_name'])?$value['state_name']:'';
	                    $city_name   = !empty($value['city_name'])?$value['city_name']:'';
	                    $zone_name   = !empty($value['zone_name'])?$value['zone_name']:'';

	                    $num = array(
                        	$str_name,    
		                    $mobile,      
		                    $state_name,  
		                    $city_name,   
		                    $zone_name,   
		                    $curr_bal,    
		                    $invoice_val, 
                        );

                        fputcsv($output, $num);  
		    		}
			    }

			    // header('Content-Type: text/csv; charset=utf-8');  
			    // header('Content-Disposition: attachment; filename=outlet_order_report('.$start_date.' to '.$end_date.').csv');  
			    // $output = fopen("php://output", "w");   
			    // fputcsv($output, array('Order No', 'Store Name','Date', 'Product Name', 'HSN Code', 'GST Val', 'Order Qty', 'Price', 'Total Value'));

		    	// if($data_list['status'] == 1)
		    	// {
		    	// 	$data_val = $data_list['data'];	
		    	// 	foreach ($data_val as $key => $value) {
		    			
		    	// 		$store_name   = !empty($value['store_name'])?$value['store_name']:'';
				// 	    $order_no     = !empty($value['order_no'])?$value['order_no']:'';
				// 	    $product_name = !empty($value['product_name'])?$value['product_name']:'';
				// 	    $type_name    = !empty($value['type_name'])?$value['type_name']:'';
				// 	    $hsn_code     = !empty($value['hsn_code'])?$value['hsn_code']:'';
				// 	    $gst_val      = !empty($value['gst_val'])?$value['gst_val']:'';
				// 	    $price        = !empty($value['price'])?$value['price']:'';
				// 	    $order_qty    = !empty($value['order_qty'])?$value['order_qty']:'';
				// 	    $_ordered     = !empty($value['_ordered'])?$value['_ordered']:'';
				// 	    $total_amount = $order_qty * $price;


                //         $num = array(
                //         	$order_no,
                //         	$store_name,
                //         	date('d-M-Y', strtotime($_ordered)),
                //         	$type_name,
                //         	$hsn_code,
                //         	$gst_val,
                //         	$order_qty,
                //         	$price,
                //         	$total_amount
                //         );

                //         fputcsv($output, $num);  
                //     }
		    	// }

		    	fclose($output);
      			exit();
			}

        	else
        	{
        		$where_1 = array(
					'method'   => 'get_state',
					'manager_id'  => $this->session->userdata('id'),
					
				);

            	$state_list  = avul_call(API_URL.'managers/api/hierarchy_list',$where_1);

                $page['manager_id']       = $this->session->userdata('id');
		    	$page['method']       = '_getOutletOrderData';
		    	$page['state_val']    = $state_list['data'];
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Outlet Order Report";
				$page['page_title']   = "Outlet Order Report";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('managers/report/outlet_order',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "outlet_order";
				$this->bassthaya->load_Managers_form_template($data);
        	}
    	}

    	// public function purchase_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
    	// {
    	// 	if ($this->session->userdata('random_value') == '')
        // 	redirect(base_url() . 'index.php?login', 'refresh');

        // 	$method = $this->input->post('method');

        // 	if($method == '_getPurchaseData')
        // 	{
        // 		$start_date = $this->input->post('start_date');
        // 		$end_date   = $this->input->post('end_date');

        // 		$vendor_purchase_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'method'     => '_overallPurchaseReport',
		// 	    );

        // 		$data_list  = avul_call(API_URL.'report/api/vendor_report',$vendor_purchase_whr);

		// 	    if($data_list['status'] == 1)
		// 	    {
		// 	    	$html     = '';
		//     		$data_val = $data_list['data'];

		//     		$num = 1;
		//     		foreach ($data_val as $key => $val) {
		//     			$po_id        = !empty($val['po_id'])?$val['po_id']:'';
	    //                 $po_no        = !empty($val['po_no'])?$val['po_no']:'';
	    //                 $vendor_name  = !empty($val['vendor_name'])?$val['vendor_name']:'';
	    //                 $ordered      = !empty($val['ordered'])?$val['ordered']:'';
	    //                 $order_status = !empty($val['order_status'])?$val['order_status']:'';
	    //                 $invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
	    //                 $inv_value    = !empty($val['inv_value'])?$val['inv_value']:'';
	    //                 $total_value  = !empty($val['total_value'])?$val['total_value']:'';

	    //                 $html .= '
		// 	            	<tr>
        //                         <td>'.$num.'</td>
        //                         <td><a target="_blank" href="'.BASE_URL.'index.php/admin/purchase/print_order/'.$po_id.'">'.$po_no.'</a></td>
        //                         <td><a target="_blank" href="'.BASE_URL.'index.php/admin/purchase/print_invoice/'.$inv_value.'">'.$invoice_no.'</a></td>
        //                         <td>'.mb_strimwidth($vendor_name, 0, 20, '...').'</td>
        //                         <td>'.date('d-M-Y', strtotime($ordered)).'</td>
        //                         <td>'.$total_value.'</td>
        //                     </tr>
		// 	            ';

		// 	            $num++;
		//     		}

		//     		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/purchase_report/excel_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		//     		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/purchase_report/pdf_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		//     		$response['status']    = 1;
		// 	        $response['message']   = $data_list['message']; 
		// 	        $response['data']      = $html;
		// 	        $response['excel_btn'] = $excel_btn;
		// 	        $response['pdf_btn']   = $pdf_btn;
		// 	        $response['error']     = []; 
		// 	        echo json_encode($response);
		// 	        return;
		// 	    }
		// 	    else
		//     	{
		//     		$response['status']  = 0;
		// 	        $response['message'] = $data_list['message']; 
		// 	        $response['data']    = [];
		// 	        $response['error']   = []; 
		// 	        echo json_encode($response);
		// 	        return;
		//     	}
        // 	}

        // 	if($param1 == 'excel_print')
        // 	{
        // 		$start_date = $param2; 
        // 		$end_date   = $param3;

        // 		$vendor_purchase_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'method'     => '_overallPurchaseDetails',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/vendor_report',$vendor_purchase_whr);

		// 	    header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=new_tally_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('InvNo', 'Inv_Dt', 'Pty_Name', 'Vch_Type', 'GSTIN', 'StateOfSupply', 'Product_Name', 'HSNCode', 'Qty', 'UOM', 'TaxPer', 'TaxableAmt', 'IGSTAmt', 'SGSTAmt', 'CGSTAmt', 'Cess', 'OtherAmt', 'Net_Amt', 'Narration', 'Discount', 'Supplied In voice No', 'Supplied Date', 'Purchase Ledger'));

		// 	    if($data_list['status'] == 1)
		// 	    {
		// 	    	$data_val   = $data_list['data'];	
		// 	    	$totQty     = 0;
		//     		$totTaxable = 0;
		//     		$totIgstAmt = 0;
		//     		$totSgstAmt = 0;
		//     		$totCgstAmt = 0;
		//     		$totNetAmt  = 0;

		// 	    	foreach ($data_val as $key => $val) {
		// 	    		$pur_no       = !empty($val['pur_no'])?$val['pur_no']:'';
	    //                 $pur_date     = !empty($val['pur_date'])?$val['pur_date']:'';
	    //                 $admin_name   = !empty($val['admin_name'])?$val['admin_name']:'';
	    //                 $admin_gst    = !empty($val['admin_gst'])?$val['admin_gst']:'';
	    //                 $adm_state_id = !empty($val['adm_state_id'])?$val['adm_state_id']:'';
	    //                 $ven_com_name = !empty($val['ven_com_name'])?$val['ven_com_name']:'';
	    //                 $ven_gst_no   = !empty($val['ven_gst_no'])?$val['ven_gst_no']:'';
	    //                 $ven_state_id = !empty($val['ven_state_id'])?$val['ven_state_id']:'';
	    //                 $ven_state    = !empty($val['ven_state'])?$val['ven_state']:'';
	    //                 $description  = !empty($val['product_name'])?$val['product_name']:'';
	    //                 $hsn_code     = !empty($val['hsn_code'])?$val['hsn_code']:'';
	    //                 $pdt_qty      = !empty($val['product_qty'])?$val['product_qty']:'';
	    //                 $gst_value    = !empty($val['product_gst'])?$val['product_gst']:'0';
	    //                 $pdt_price    = !empty($val['product_price'])?$val['product_price']:'0';
	    //                 $inv_no       = !empty($val['invoice_no'])?$val['invoice_no']:'';
	    //                 $inv_date     = !empty($val['invoice_date'])?$val['invoice_date']:'';
	    //                 $discount     = 0;

	    //                 $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
        //                 $price_val  = $pdt_price - $gst_data;
        //                 $pdt_gst    = $pdt_qty * $gst_data;
        //                 $TaxableAmt = $pdt_qty * $price_val;
        //                 $tot_price  = $pdt_qty * $pdt_price;
        //                 $pdt_value  = round($tot_price);
        //                 $total_dis  = $pdt_value * $discount / 100;
        //                 $total_val  = $pdt_value;

        //                 if($adm_state_id == $ven_state_id)
        //                 {
        //                 	$gst_res   = $pdt_gst / 2;
        //                 	$sgst_val  = number_format((float)$gst_res, 2, '.', '');
        //                 	$cgst_val  = number_format((float)$gst_res, 2, '.', '');
        //                 	$igst_val  = '0';
        //                 	$vch_type  = 'Local Purchase';
        //                 	$narration = '';
        //                 }
        //                 else
        //                 {
        //                 	$sgst_val  = '0';
        //                 	$cgst_val  = '0';
        //                 	$igst_val  = number_format((float)$pdt_gst, 2, '.', '');
        //                 	$vch_type  = 'Inter Purchase';
        //                 	$narration = 'Purchase @ '.$gst_value.'% IGST';
        //                 }

        //                 $totQty     += $pdt_qty;
		// 	    		$totTaxable += $TaxableAmt;
		// 	    		$totIgstAmt += $igst_val;
		// 	    		$totSgstAmt += $sgst_val;
		// 	    		$totCgstAmt += $cgst_val;
		// 	    		$totNetAmt  += $total_val;

		// 	    		$num = array(
		// 	    			$pur_no,
		// 	    			$pur_date,
		// 	    			$ven_com_name,
		// 	    			$vch_type,
		// 	    			$ven_gst_no,
		// 	    			$ven_state,
		// 	    			$description,
		// 	    			$hsn_code,
		// 	    			$pdt_qty,
		// 	    			'Nos',
		// 	    			$gst_value,
		// 	    			number_format((float)$TaxableAmt, 2, '.', ''),
		// 	    			$igst_val,
		// 	    			$cgst_val,
		// 	    			$sgst_val,
		// 	    			'0',
		// 	    			'0',
		// 	    			number_format((float)$total_val, 2, '.', ''),
		// 	    			'',
		// 	    			'0',
		// 	    			$inv_no,
		// 	    			$inv_date,
		// 	    			$narration
		// 	    		);

		// 	    		fputcsv($output, $num);
		// 	    	}

		// 	    	fputcsv($output, array('', '', '', '', '', '', '', '', $totQty, '', '', number_format((float)$totTaxable, 2, '.', ''), number_format((float)$totIgstAmt, 2, '.', ''), number_format((float)$totSgstAmt, 2, '.', ''), number_format((float)$totCgstAmt, 2, '.', ''), '0', '0', number_format((float)$totNetAmt, 2, '.', ''), '', '', '', '', ''));
		// 	    }

		// 	    fclose($output);
      	// 		exit();
        // 	}

        // 	else
		// 	{
		//     	$page['method']       = '_getPurchaseData';
		// 		$page['main_heading'] = "Report";
		// 		$page['sub_heading']  = "Report";
		// 		$page['pre_title']    = "Purchas Report";
		// 		$page['page_title']   = "Purchas Report";
		// 		$page['pre_menu']     = "";
		// 		$data['page_temp']    = $this->load->view('admin/report/purchase_report',$page,TRUE);
		// 		$data['view_file']    = "Page_Template";
		// 		$data['currentmenu']  = "purchase_report";
		// 		$this->bassthaya->load_admin_form_template($data);
		// 	}
    	// }

    	// public function purchase_return($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
    	// {
    	// 	if ($this->session->userdata('random_value') == '')
        // 	redirect(base_url() . 'index.php?login', 'refresh');

        // 	$method = $this->input->post('method');

        // 	if($method == '_getPurchaseReturnData')
        // 	{
        // 		$start_date = $this->input->post('start_date');
        // 		$end_date   = $this->input->post('end_date');

        // 		$vendor_purchase_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'method'     => '_overallPurchaseReturnReport',
		// 	    );

        // 		$data_list  = avul_call(API_URL.'report/api/vendor_report',$vendor_purchase_whr);

		// 	    if($data_list['status'] == 1)
		// 	    {
		// 	    	$html     = '';
		//     		$data_val = $data_list['data'];

		//     		$num = 1;
		//     		foreach ($data_val as $key => $val) {
		//     			$order_id     = !empty($val['order_id'])?$val['order_id']:'';
	    //                 $order_no     = !empty($val['order_no'])?$val['order_no']:'';
	    //                 $vendor_name  = !empty($val['vendor_name'])?$val['vendor_name']:'';
	    //                 $ordered      = !empty($val['ordered'])?$val['ordered']:'';
	    //                 $order_status = !empty($val['order_status'])?$val['order_status']:'';
	    //                 $total_value  = !empty($val['total_value'])?$val['total_value']:'';

	    //                 $html .= '
		// 	            	<tr>
        //                         <td>'.$num.'</td>
        //                         <td><a target="_blank" href="'.BASE_URL.'index.php/admin/purchase/print_return/'.$order_id.'">'.$order_no.'</a></td>
        //                         <td>'.mb_strimwidth($vendor_name, 0, 20, '...').'</td>
        //                         <td>'.date('d-M-Y', strtotime($ordered)).'</td>
        //                         <td>'.$total_value.'</td>
        //                     </tr>
		// 	            ';

		// 	            $num++;
		//     		}

		//     		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/purchase_return/excel_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		//     		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/purchase_return/pdf_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		//     		$response['status']    = 1;
		// 	        $response['message']   = $data_list['message']; 
		// 	        $response['data']      = $html;
		// 	        $response['excel_btn'] = $excel_btn;
		// 	        $response['pdf_btn']   = $pdf_btn;
		// 	        $response['error']     = []; 
		// 	        echo json_encode($response);
		// 	        return;
		// 	    }
		// 	    else
		//     	{
		//     		$response['status']  = 0;
		// 	        $response['message'] = $data_list['message']; 
		// 	        $response['data']    = [];
		// 	        $response['error']   = []; 
		// 	        echo json_encode($response);
		// 	        return;
		//     	}
        // 	}

        // 	if($param1 == 'excel_print')
        // 	{
        // 		$start_date = $param2; 
        // 		$end_date   = $param3;

        // 		$vendor_purchase_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'method'     => '_overallPurchaseReturnDetails',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/vendor_report',$vendor_purchase_whr);

		// 	    header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=purchase_return_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('Debit note No', 'Inv Date', 'Original Inv No', 'Voucher Date', 'Pty_Name', 'Vch_Type', 'GSTIN', 'StateOfSupply', 'Product_Name', 'HSNCode', 'Qty', 'UOM', 'TaxPer', 'TaxableAmt', 'IGSTAmt', 'SGSTAmt', 'CGSTAmt', 'Cess', 'OtherAmt', 'Net_Amt', 'Narration', 'Discount'));

		// 	    if($data_list['status'] == 1)
		// 	    {
		// 	    	$data_val   = $data_list['data'];	
		// 	    	$totQty     = 0;
		//     		$totTaxable = 0;
		//     		$totIgstAmt = 0;
		//     		$totSgstAmt = 0;
		//     		$totCgstAmt = 0;
		//     		$totNetAmt  = 0;

		// 	    	foreach ($data_val as $key => $val) {

		// 	    		$ret_no       = !empty($val['return_no'])?$val['return_no']:'';
	    //                 $ret_date     = !empty($val['return_date'])?$val['return_date']:'';
	    //                 $admin_name   = !empty($val['admin_name'])?$val['admin_name']:'';
	    //                 $admin_gst    = !empty($val['admin_gst'])?$val['admin_gst']:'';
	    //                 $admin_state  = !empty($val['admin_state'])?$val['admin_state']:'';
	    //                 $adm_state_id = !empty($val['adm_state_id'])?$val['adm_state_id']:'';
	    //                 $ven_state_id = !empty($val['ven_state_id'])?$val['ven_state_id']:'';
	    //                 $description  = !empty($val['product_name'])?$val['product_name']:'';
	    //                 $hsn_code     = !empty($val['hsn_code'])?$val['hsn_code']:'';
	    //                 $pdt_qty      = !empty($val['product_qty'])?$val['product_qty']:'';
	    //                 $gst_value    = !empty($val['product_gst'])?$val['product_gst']:'';
	    //                 $pdt_price    = !empty($val['product_price'])?$val['product_price']:'';
	    //                 $discount     = 0;

	    //                 $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
        //                 $price_val  = $pdt_price - $gst_data;
        //                 $pdt_gst    = $pdt_qty * $gst_data;
        //                 $TaxableAmt = $pdt_qty * $price_val;
        //                 $tot_price  = $pdt_qty * $pdt_price;
        //                 $pdt_value  = round($tot_price);
        //                 $total_dis  = $pdt_value * $discount / 100;
        //                 $total_val  = $pdt_value;

        //                 if($adm_state_id == $ven_state_id)
        //                 {
        //                 	$gst_res  = $pdt_gst / 2;
        //                 	$sgst_val = number_format((float)$gst_res, 2, '.', '');
        //                 	$cgst_val = number_format((float)$gst_res, 2, '.', '');
        //                 	$igst_val = '0';
        //                 	$vch_type = 'Debit Note';
        //                 }
        //                 else
        //                 {
        //                 	$sgst_val = '0';
        //                 	$cgst_val = '0';
        //                 	$igst_val = number_format((float)$pdt_gst, 2, '.', '');
        //                 	$vch_type = 'Debit Note';
        //                 }

        //                 $totQty     += $pdt_qty;
		// 	    		$totTaxable += $TaxableAmt;
		// 	    		$totIgstAmt += $igst_val;
		// 	    		$totSgstAmt += $sgst_val;
		// 	    		$totCgstAmt += $cgst_val;
		// 	    		$totNetAmt  += $total_val;

		// 	    		$num = array(
		// 	    			$ret_no,
		// 	    			$ret_date,
		// 	    			'',
		// 	    			'',
		// 	    			$admin_name,
		// 	    			$vch_type,
		// 	    			$admin_gst,
		// 	    			$admin_state,
		// 	    			$description,
		// 	    			$hsn_code,
		// 	    			$pdt_qty,
		// 	    			'Nos',
		// 	    			$gst_value,
		// 	    			number_format((float)$TaxableAmt, 2, '.', ''),
		// 	    			$igst_val,
		// 	    			$cgst_val,
		// 	    			$sgst_val,
		// 	    			'0',
		// 	    			'0',
		// 	    			number_format((float)$total_val, 2, '.', ''),
		// 	    			'',
		// 	    			'0',
		// 	    		);

		// 	    		fputcsv($output, $num);
		// 	    	}

		// 	    	fputcsv($output, array('', '', '', '', '', '', '', '', '', '', $totQty, '', '', number_format((float)$totTaxable, 2, '.', ''), number_format((float)$totIgstAmt, 2, '.', ''), number_format((float)$totSgstAmt, 2, '.', ''), number_format((float)$totCgstAmt, 2, '.', ''), '0', '0', number_format((float)$totNetAmt, 2, '.', ''), '', '', '', '', ''));
		// 	    }

		// 	    fclose($output);
      	// 		exit();
        // 	}

        // 	else
		// 	{
		//     	$page['method']       = '_getPurchaseReturnData';
		// 		$page['main_heading'] = "Report";
		// 		$page['sub_heading']  = "Report";
		// 		$page['pre_title']    = "Purchas Return Report";
		// 		$page['page_title']   = "Purchas Return Report";
		// 		$page['pre_menu']     = "";
		// 		$data['page_temp']    = $this->load->view('admin/report/purchase_return',$page,TRUE);
		// 		$data['view_file']    = "Page_Template";
		// 		$data['currentmenu']  = "purchase_return";
		// 		$this->bassthaya->load_admin_form_template($data);
		// 	}
    	// }

    	// public function vendor_purchase($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
    	// {
    	// 	if ($this->session->userdata('random_value') == '')
        // 	redirect(base_url() . 'index.php?login', 'refresh');

        // 	$method = $this->input->post('method');

        // 	if($method == '_getVendorPurchaseData')
        // 	{
        // 		$start_date = $this->input->post('start_date');
        // 		$end_date   = $this->input->post('end_date');
        // 		$vendor_id  = $this->input->post('vendor_id');
        // 		$product_id = $this->input->post('product_id');
        // 		$type_id    = $this->input->post('type_id');

        // 		$vendor_purchase_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'vendor_id'  => $vendor_id,
		// 	    	'product_id' => $product_id,
		// 	    	'type_id'    => $type_id,
		// 	    	'method'     => '_purchaseReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/vendor_report',$vendor_purchase_whr);

		// 	    if($data_list['status'] == 1)
		// 	    {
		// 	    	$html     = '';
		//     		$data_val = $data_list['data'];

		//     		$num = 1;
		//     		foreach ($data_val as $key => $value) {
		// 			    $pur_no       = !empty($value['pur_no'])?$value['pur_no']:'';
		// 			    $company_name = !empty($value['company_name'])?$value['company_name']:'';
		// 			    $description  = !empty($value['description'])?$value['description']:'';
		// 			    $product_qty  = !empty($value['product_qty'])?$value['product_qty']:'';
		// 			    $receive_qty  = !empty($value['receive_qty'])?$value['receive_qty']:'';
		// 			    $createdate   = !empty($value['createdate'])?$value['createdate']:'';

		// 			    $html .= '
		// 	            	<tr>
        //                         <td>'.$num.'</td>
        //                         <td>'.$pur_no.'</td>
        //                         <td>'.mb_strimwidth($company_name, 0, 12, '...').'</td>
        //                         <td>'.mb_strimwidth($description, 0, 20, '...').'</td>
        //                         <td>'.$product_qty.'</td>
        //                         <td>'.$receive_qty.'</td>
        //                         <td>'.date('d-M-Y', strtotime($createdate)).'</td>
        //                     </tr>
		// 	            ';

		// 	            $num++;
		//     		}

		//     		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/vendor_purchase/excel_print/'.$start_date.'/'.$end_date.'/'.$vendor_id.'/'.$product_id.'/'.$type_id.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		//     		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/vendor_purchase/pdf_print/'.$start_date.'/'.$end_date.'/'.$vendor_id.'/'.$product_id.'/'.$type_id.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		//     		$response['status']    = 1;
		// 	        $response['message']   = $data_list['message']; 
		// 	        $response['data']      = $html;
		// 	        $response['excel_btn'] = $excel_btn;
		// 	        $response['pdf_btn']   = $pdf_btn;
		// 	        $response['error']     = []; 
		// 	        echo json_encode($response);
		// 	        return;
		// 	    }
		// 	    else
		//     	{
		//     		$response['status']  = 0;
		// 	        $response['message'] = $data_list['message']; 
		// 	        $response['data']    = [];
		// 	        $response['error']   = []; 
		// 	        echo json_encode($response);
		// 	        return;
		//     	}
        // 	}

        // 	if($param1 == 'excel_print')
        // 	{
        // 		$start_date = $param2; 
        // 		$end_date   = $param3;
        // 		$vendor_id  = $param4;
        // 		$product_id = $param5;
        // 		$type_id    = $param6;

        // 		$vendor_purchase_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'vendor_id'  => $vendor_id,
		// 	    	'product_id' => $product_id,
		// 	    	'type_id'    => $type_id,
		// 	    	'method'     => '_purchaseReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/vendor_report',$vendor_purchase_whr);

		// 	    header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=hoisst_purchase_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('Order No', 'Order Date', 'Company Name','GSTIN', 'Contact No', 'Description', 'Order Qty', 'Receive Qty', 'Unit'));

		//     	if($data_list['status'] == 1)
		//     	{
		//     		$data_val = $data_list['data'];	
		//     		foreach ($data_val as $key => $value) {

	    //                 $pur_no       = !empty($value['pur_no'])?$value['pur_no']:'';
	    //                 $createdate   = !empty($value['createdate'])?$value['createdate']:'';
	    //                 $company_name = !empty($value['company_name'])?$value['company_name']:'';
	    //                 $gst_no       = !empty($value['gst_no'])?$value['gst_no']:'';
	    //                 $contact_no   = !empty($value['contact_no'])?$value['contact_no']:'';
	    //                 $description  = !empty($value['description'])?$value['description']:'';
	    //                 $product_qty  = !empty($value['product_qty'])?$value['product_qty']:'';
	    //                 $receive_qty  = !empty($value['receive_qty'])?$value['receive_qty']:'';

	    //                 $num = array(
        //                 	$pur_no,
        //                 	date('d-m-Y', strtotime($createdate)),
        //                 	$company_name,
        //                 	$gst_no,
        //                 	$contact_no,
        //                 	$description,
        //                 	$product_qty,
        //                 	$receive_qty,
        //                 	'nos',	
        //                 );

        //                 fputcsv($output, $num);  
		//     		}
		//     	}

		//     	fclose($output);
      	// 		exit();
        // 	}

        // 	if($param1 =='getVendor_products')
		// 	{
		// 		$vendor_id = $this->input->post('vendor_id');

		// 		$where = array(
        //     		'vendor_id' => $vendor_id,
        //     		'method'    => '_listVendorProducts'
        //     	);

        //     	$vendor_list   = avul_call(API_URL.'catlog/api/product',$where);
        //     	$vendor_result = $vendor_list['data'];

        // 		$option ='<option value="">Select Value</option>';

        // 		if(!empty($vendor_result))
        // 		{
        // 			foreach ($vendor_result as $key => $value) {
        // 				$product_id   = !empty($value['product_id'])?$value['product_id']:'';
        //                 $product_name = !empty($value['product_name'])?$value['product_name']:'';

        //                 $option .= '<option value="'.$product_id.'">'.$product_name.'</option>';
        // 			}
        // 		}

        // 		$response['status']  = 1;
		//         $response['message'] = 'success'; 
		//         $response['data']    = $option;
		//         echo json_encode($response);
		//         return;
		// 	}

		// 	else if($param1 =='getVendor_productType')
		// 	{
		// 		$product_id = $this->input->post('product_id');

		// 		$where = array(
        //     		'product_id' => $product_id,
        //     		'method'     => '_listProductType'
        //     	);

        //     	$type_list   = avul_call(API_URL.'catlog/api/productType',$where);
        //     	$type_result = $type_list['data'];

        // 		$option ='<option value="">Select Value</option>';

        // 		if(!empty($type_result))
        // 		{
        // 			foreach ($type_result as $key => $value) {
        // 				$type_id     = !empty($value['type_id'])?$value['type_id']:'';
        //                 $description = !empty($value['description'])?$value['description']:'';

        //                 $option .= '<option value="'.$type_id.'">'.$description.'</option>';
        // 			}
        // 		}

        // 		$response['status']  = 1;
		//         $response['message'] = 'success'; 
		//         $response['data']    = $option;
		//         echo json_encode($response);
		//         return;
		// 	}

		// 	else
		// 	{
		// 		$where_1 = array(
	    //     		'method'    => '_listManufacturerVendors'
	    //     	);

	    //     	$vendor_list = avul_call(API_URL.'vendors/api/vendors',$where_1);

		//     	$data_list  = avul_call(API_URL.'employee/api/employee',$where_1);
		//     	$emp_list   = !empty($data_list['data'])?$data_list['data']:'';

		//     	$page['method']       = '_getVendorPurchaseData';
		//     	$page['vendor_val']   = $vendor_list['data'];
		// 		$page['main_heading'] = "Report";
		// 		$page['sub_heading']  = "Report";
		// 		$page['pre_title']    = "Vendor Purchas Report";
		// 		$page['page_title']   = "Vendor Purchas Report";
		// 		$page['pre_menu']     = "";
		// 		$data['page_temp']    = $this->load->view('admin/report/vendor_purchase',$page,TRUE);
		// 		$data['view_file']    = "Page_Template";
		// 		$data['currentmenu']  = "vendor_purchase";
		// 		$this->bassthaya->load_admin_form_template($data);
		// 	}
    	// }

    	// public function vendor_overall($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
    	// {
    	// 	if ($this->session->userdata('random_value') == '')
        // 	redirect(base_url() . 'index.php?login', 'refresh');

        // 	$method = $this->input->post('method');	

        // 	if($method == '_getVendorOverallData')
        // 	{
        // 		$start_date = $this->input->post('start_date');
        // 		$end_date   = $this->input->post('end_date');
        // 		$vendor_id  = $this->input->post('vendor_id');
        // 		$product_id = $this->input->post('product_id');
        // 		$type_id    = $this->input->post('type_id');

        // 		$vendor_purchase_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'vendor_id'  => $vendor_id,
		// 	    	'method'     => '_overallReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/vendor_report',$vendor_purchase_whr);

		// 	    if($data_list['status'] == 1)
		// 	    {
		// 	    	$html     = '';
		//     		$data_val = $data_list['data'];
		//     		$num      = 1;

		//     		foreach ($data_val as $key => $value) {
		// 			    $bill_no    = !empty($value['bill_no'])?$value['bill_no']:'';
		// 			    $pre_bal    = !empty($value['pre_bal'])?$value['pre_bal']:'0';
		// 			    $cur_bal    = !empty($value['cur_bal'])?$value['cur_bal']:'0';
		// 			    $amount     = !empty($value['amount'])?$value['amount']:'0';
		// 			    $discount   = !empty($value['discount'])?$value['discount']:'0';
		// 			    $value_view = !empty($value['value_view'])?$value['value_view']:'';
		// 			    $createdate = !empty($value['createdate'])?$value['createdate']:'';

		// 			    $html .= '
		// 	            	<tr>
        //                         <td>'.$num.'</td>
        //                         <td>'.$bill_no.'</td>
        //                         <td>'.$pre_bal.'</td>
        //                         <td>'.$amount.'</td>
        //                         <td>'.$discount.'</td>
        //                         <td>'.$cur_bal.'</td>
        //                         <td>'.mb_strimwidth($value_view, 0, 10, '...').'</td>
        //                         <td>'.date('d-M-Y', strtotime($createdate)).'</td>
        //                     </tr>
		// 	            ';

		// 	            $num++;
		//     		}

		//     		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/vendor_overall/excel_print/'.$start_date.'/'.$end_date.'/'.$vendor_id.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		//     		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/vendor_overall/pdf_print/'.$start_date.'/'.$end_date.'/'.$vendor_id.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		//     		$response['status']    = 1;
		// 	        $response['message']   = $data_list['message']; 
		// 	        $response['data']      = $html;
		// 	        $response['excel_btn'] = $excel_btn;
		// 	        $response['pdf_btn']   = $pdf_btn;
		// 	        $response['error']     = []; 
		// 	        echo json_encode($response);
		// 	        return;
		// 	    }
		// 	    else
		//     	{
		//     		$response['status']  = 0;
		// 	        $response['message'] = $data_list['message']; 
		// 	        $response['data']    = [];
		// 	        $response['error']   = []; 
		// 	        echo json_encode($response);
		// 	        return;
		//     	}
        // 	}

        // 	if($param1 == 'excel_print')
        // 	{
        // 		$start_date = $param2; 
        // 		$end_date   = $param3;
        // 		$vendor_id  = $param4;

        // 		$vendor_purchase_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'vendor_id'  => $vendor_id,
		// 	    	'method'     => '_overallReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/vendor_report',$vendor_purchase_whr);	

		// 	    header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=vendor_overall_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('Bill Code', 'Bill No', 'Date', 'Opening Balance','Amount', 'Discount', 'Closing Balance', 'Description', 'Status'));

		// 	    if($data_list['status'] == 1)
		// 	    {
		// 	    	$html     = '';
		//     		$data_val = $data_list['data'];
		//     		$num      = 1;

		//     		foreach ($data_val as $key => $value) {

	    //                 $bill_code   = !empty($value['bill_code'])?$value['bill_code']:'';
	    //                 $bill_no     = !empty($value['bill_no'])?$value['bill_no']:'';
	    //                 $pre_bal     = !empty($value['pre_bal'])?$value['pre_bal']:'0';
	    //                 $cur_bal     = !empty($value['cur_bal'])?$value['cur_bal']:'0';
	    //                 $amount      = !empty($value['amount'])?$value['amount']:'0';
	    //                 $discount    = !empty($value['discount'])?$value['discount']:'0';
	    //                 $description = !empty($value['description'])?$value['description']:'---';
	    //                 $value_view  = !empty($value['value_view'])?$value['value_view']:'';
	    //                 $createdate  = !empty($value['createdate'])?$value['createdate']:'';

	    //                 $num = array(
        //                 	$bill_code,
        //                 	$bill_no,
        //                 	date('d-m-Y', strtotime($createdate)),
        //                 	$pre_bal,
        //                 	$amount,
        //                 	$discount,
        //                 	$cur_bal,
        //                 	$description,
        //                 	$value_view,
        //                 );

	    //                 fputcsv($output, $num); 
		//     		}
		// 	    }

		// 	    fclose($output);
      	// 		exit();
        // 	}

        // 	else
        // 	{
        // 		$where_1 = array(
	    //     		'method'    => '_listManufacturerVendors'
	    //     	);

	    //     	$vendor_list = avul_call(API_URL.'vendors/api/vendors',$where_1);

		//     	$data_list  = avul_call(API_URL.'employee/api/employee',$where_1);
		//     	$emp_list   = !empty($data_list['data'])?$data_list['data']:'';

		//     	$page['method']       = '_getVendorOverallData';
		//     	$page['vendor_val']   = $vendor_list['data'];
		// 		$page['main_heading'] = "Report";
		// 		$page['sub_heading']  = "Report";
		// 		$page['pre_title']    = "Vendor Overall Report";
		// 		$page['page_title']   = "Vendor Overall Report";
		// 		$page['pre_menu']     = "";
		// 		$data['page_temp']    = $this->load->view('admin/report/vendor_overall',$page,TRUE);
		// 		$data['view_file']    = "Page_Template";
		// 		$data['currentmenu']  = "vendor_overall";
		// 		$this->bassthaya->load_admin_form_template($data);
        // 	}
    	// }

    	public function beat_outlet($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
    	{
    		if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method =='_getBeatWiseOutletData')
        	{
        		$state_id   = $this->input->post('state_id');
        		$city_id    = $this->input->post('city_id');
        		$zone_id    = $this->input->post('zone_id');
        		$status_val = $this->input->post('status_val');

        		$outlet_whr  = array(
        			'state_id'   => $state_id,
        			'city_id'    => $city_id,
        			'zone_id'    => $zone_id,
        			'status_val' => $status_val,
            		'method'     => '_beatWiseOutlet'
            	);

            	$data_list = avul_call(API_URL.'report/api/outlet_report',$outlet_whr);

            	if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'];
		    		$num      = 1;

		    		foreach ($data_val as $key => $value) {
		    			$company_name = !empty($value['company_name'])?$value['company_name']:'';
		    			$mobile       = !empty($value['mobile'])?$value['mobile']:'';
		    			$gst_no       = !empty($value['gst_no'])?$value['gst_no']:'';

		    			$html .= '
			            	<tr>
                                <td>'.$num.'</td>
                                <td>'.mb_strimwidth($company_name, 0, 50, '...').'</td>
                                <td>'.$mobile.'</td>
                                <td>'.$gst_no.'</td>
                            </tr>
			            ';

			            $num++;
		    		}

		    		$excel_btn = '<a class="btn btn-success " target="_blank" href="'.BASE_URL.'index.php/admin/report/beat_outlet/excel_print/'.$state_id.'/'.$city_id.'/'.$zone_id.'/'.$status_val.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		    		$pdf_btn   = '<a class="btn btn-danger " target="_blank" href="'.BASE_URL.'index.php/admin/report/beat_outlet/pdf_print/'.$state_id.'"'.$city_id.'/'.$zone_id.'/ style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

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
        		$state_id   = $param2; 
        		$city_id    = $param3;
        		$zone_id    = $param4;
        		$status_val = $param5; 

        		$outlet_whr  = array(
        			'state_id'   => $state_id,
        			'city_id'    => $city_id,
        			'zone_id'    => $zone_id,
        			'status_val' => $status_val,
            		'method'     => '_beatWiseOutlet'
            	);

            	$data_list = avul_call(API_URL.'report/api/outlet_report',$outlet_whr);

            	// Zone Details
            	$beat_whr = array(
            		'zone_id' => $zone_id,
            		'method'  => '_detailZone'
            	);

            	header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=outlet_list.csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Store Name', 'Contact No', 'Email', 'GSTIN No', 'PAN No', 'TAN No', 'Due Days', 'Discount', 'Account Name', 'Account No', 'Address', 'State Name', 'City Name', 'Beat Name', 'Credit Limit', 'Available Limit', 'Current Balance'));

			    if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'];
		    		$num      = 1;

		    		foreach ($data_val as $key => $value) {
	                    $company_name    = !empty($value['company_name'])?$value['company_name']:'';
	                    $mobile          = !empty($value['mobile'])?$value['mobile']:'';
	                    $email           = !empty($value['email'])?$value['email']:'';
	                    $gst_no          = !empty($value['gst_no'])?$value['gst_no']:'-';
	                    $pan_no          = !empty($value['pan_no'])?$value['pan_no']:'-';
	                    $tan_no          = !empty($value['tan_no'])?$value['tan_no']:'-';
	                    $due_days        = !empty($value['due_days'])?$value['due_days']:'-';
	                    $discount        = !empty($value['discount'])?$value['discount']:'-';
	                    $account_name    = !empty($value['account_name'])?$value['account_name']:'-';
	                    $account_no      = !empty($value['account_no'])?$value['account_no']:'-';
	                    $address         = !empty($value['address'])?$value['address']:'-';
	                    $state_name      = !empty($value['state_name'])?$value['state_name']:'-';
	                    $city_name       = !empty($value['city_name'])?$value['city_name']:'-';
	                    $beat_name       = !empty($value['beat_name'])?$value['beat_name']:'-';
	                    $credit_limit    = !empty($value['credit_limit'])?$value['credit_limit']:'-';
	                    $available_limit = !empty($value['available_limit'])?$value['available_limit']:'-';
	                    $current_balance = !empty($value['current_balance'])?$value['current_balance']:'-';

	                    $num = array(
                        	$company_name,
                        	$mobile,
                        	$email,
                        	$gst_no,
                        	$pan_no,
                        	$tan_no,
                        	$due_days,
                        	$discount,
                        	$account_name,
                        	$account_no,
                        	$address,
                        	$state_name,
                        	$city_name,
                        	$beat_name,
                        	$credit_limit,
                        	$available_limit,
                        	$current_balance,
                        );

	                    fputcsv($output, $num); 
		    		}
			    }

			    fclose($output);
      			exit();
        	}

        	if($param1 =='getCity_name')
			{
				$state_id = $this->input->post('state_id');
				
				$where = array(
					'id'       => $this->session->userdata('id'),
            		'state_id' => $state_id,
            		'method'   => '_listCity'
            	);

            	$city_list   = avul_call(API_URL.'managers/api/hierarchy_list',$where);
            	$city_result = $city_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($city_result))
        		{
        			foreach ($city_result as $key => $value) {
        				$city_id   = !empty($value['id'])?$value['id']:'';
                        $city_name = !empty($value['city_name'])?$value['city_name']:'';
						$city_code = !empty($value['city_code'])?$value['city_code']:'';

                        $option .= '<option value="'.$city_id.'">'.$city_name.'('.$city_code.')'.'</option>';
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
					'id'       => $this->session->userdata('id'),
            		'state_id' => $state_id,
            		'city_id'  => $city_id,
            		'method'   => '_listZone'
            	);

            	$zone_list   = avul_call(API_URL.'managers/api/hierarchy_list',$where);
            	$zone_result = $zone_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($zone_result))
        		{
        			foreach ($zone_result as $key => $value) {
        				$zone_id   = !empty($value['id'])?$value['id']:'';
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

			else
			{
				$where_1 = array(
					'method'   => 'get_state',
					'manager_id'  => $this->session->userdata('id'),
					
				);

            	$state_list  = avul_call(API_URL.'managers/api/hierarchy_list',$where_1);

            	$page['method']       = '_getBeatWiseOutletData';
		    	$page['state_val']    = $state_list['data'];
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Beat Wise Outlet Report";
				$page['page_title']   = "Beat Wise Outlet Report";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('managers/report/beat_outlet',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "beat_outlet";
				$this->bassthaya->load_Managers_form_template($data);
			}
    	}

    	public function outlet_overall($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
    	{
    		if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method =='_getOutletOverallData')
        	{
        		$start_date = $this->input->post('start_date');
        		$end_date   = $this->input->post('end_date');
        		$outlet_id  = $this->input->post('outlet_id');

        		$outlet_whr  = array(
        			'start_date' => date('Y-m-d', strtotime($start_date)),
					'end_date'   => date('Y-m-d', strtotime($end_date)),
        			'outlet_id'  => $outlet_id,
            		'method'     => '_overallReport'
            	);

            	$data_list = avul_call(API_URL.'report/api/outlet_report',$outlet_whr);

            	if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'];
		    		$num      = 1;

		    		foreach ($data_val as $key => $value) {

		    			$bill_no    = !empty($value['bill_no'])?$value['bill_no']:'';
					    $pre_bal    = !empty($value['pre_bal'])?$value['pre_bal']:'0';
					    $cur_bal    = !empty($value['cur_bal'])?$value['cur_bal']:'0';
					    $amount     = !empty($value['amount'])?$value['amount']:'0';
					    $discount   = !empty($value['discount'])?$value['discount']:'0';
					    $value_view = !empty($value['value_view'])?$value['value_view']:'';
					    $createdate = !empty($value['createdate'])?$value['createdate']:'';

					    $html .= '
			            	<tr>
                                <td>'.$num.'</td>
                                <td>'.$bill_no.'</td>
                                <td>'.$pre_bal.'</td>
                                <td>'.$amount.'</td>
                                <td>'.$discount.'</td>
                                <td>'.$cur_bal.'</td>
                                <td>'.mb_strimwidth($value_view, 0, 10, '...').'</td>
                                <td>'.date('d-M-Y', strtotime($createdate)).'</td>
                            </tr>
			            ';

			            $num++;
		    		}

		    		$excel_btn = '<a class="btn btn-success" target="_blank" href="'.BASE_URL.'index.php/admin/report/outlet_overall/excel_print/'.$start_date.'/'.$end_date.'/'.$outlet_id.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		    		$order_btn = '<a class="btn btn-warning" target="_blank" href="'.BASE_URL.'index.php/admin/report/outlet_overall/order_print/'.$start_date.'/'.$end_date.'/'.$outlet_id.'" style="color: #fff;"><i class="icon-grid"></i> Order</a>';

		    		$invoice_btn = '<a class="btn btn-danger" target="_blank" href="'.BASE_URL.'index.php/admin/report/outlet_overall/invoice_print/'.$start_date.'/'.$end_date.'/'.$outlet_id.'" style="color: #fff;"><i class="icon-grid"></i> Invoice</a>';

		    		$pdf_btn   = '<a class="btn btn-danger" target="_blank" href="'.BASE_URL.'index.php/admin/report/outlet_overall/pdf_print/'.$start_date.'/'.$end_date.'/'.$outlet_id.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		    		$response['status']      = 1;
			        $response['message']     = $data_list['message']; 
			        $response['data']        = $html;
			        $response['excel_btn']   = $excel_btn;
			        $response['order_btn']   = $order_btn;
			        $response['invoice_btn'] = $invoice_btn;
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
			        $response['error']   = []; 
			        echo json_encode($response);
			        return;
		    	}
        	}

        	if($param1 == 'excel_print')
        	{
        		$start_date = $param2; 
        		$end_date   = $param3;
        		$outlet_id  = $param4;

        		$outlet_whr = array(
			    	'start_date' => date('Y-m-d', strtotime($start_date)),
					'end_date'   => date('Y-m-d', strtotime($end_date)),
			    	'outlet_id'  => $outlet_id,
			    	'method'     => '_overallReport',
			    );

			    $data_list = avul_call(API_URL.'report/api/outlet_report',$outlet_whr);

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=outlet_overall_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Bill Code', 'Bill No', 'Date', 'Opening Balance','Amount', 'Discount', 'Closing Balance', 'Description', 'Status'));

			    if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'];
		    		$num      = 1;

		    		foreach ($data_val as $key => $value) {

	                    $bill_code   = !empty($value['bill_code'])?$value['bill_code']:'';
	                    $bill_no     = !empty($value['bill_no'])?$value['bill_no']:'';
	                    $pre_bal     = !empty($value['pre_bal'])?$value['pre_bal']:'0';
	                    $cur_bal     = !empty($value['cur_bal'])?$value['cur_bal']:'0';
	                    $amount      = !empty($value['amount'])?$value['amount']:'0';
	                    $discount    = !empty($value['discount'])?$value['discount']:'0';
	                    $description = !empty($value['description'])?$value['description']:'---';
	                    $value_view  = !empty($value['value_view'])?$value['value_view']:'';
	                    $createdate  = !empty($value['createdate'])?$value['createdate']:'';

	                    $num = array(
                        	$bill_code,
                        	$bill_no,
                        	date('d-m-Y', strtotime($createdate)),
                        	$pre_bal,
                        	$amount,
                        	$discount,
                        	$cur_bal,
                        	$description,
                        	$value_view,
                        );

	                    fputcsv($output, $num); 
		    		}
			    }

			    fclose($output);
      			exit();
        	}

        	if($param1 == 'order_print')
        	{
        		$start_date = $param2; 
        		$end_date   = $param3;
        		$outlet_id  = $param4;

        		$outlet_whr = array(
			    	'start_date' => date('Y-m-d', strtotime($start_date)),
					'end_date'   => date('Y-m-d', strtotime($end_date)),
			    	'outlet_id'  => $outlet_id,
			    	'method'     => '_overallOrderReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/outlet_report',$outlet_whr);

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=outlet_order_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Order No', 'Order Date', 'Employee Name', 'Store Name', 'Due Days', 'Discount', 'Round Val', 'Total Val'));
			    
        		if($data_list['status'] == 1)
		    	{
		    		$data_val = $data_list['data'];	

		    		foreach ($data_val as $key => $val) {
		    			$order_no    = !empty($val['order_no'])?$val['order_no']:'';
					    $order_date  = !empty($val['order_date'])?$val['order_date']:'';
					    $emp_name    = !empty($val['emp_name'])?$val['emp_name']:'';
					    $store_name  = !empty($val['store_name'])?$val['store_name']:'';
					    $due_days    = !empty($val['due_days'])?$val['due_days']:'0';
					    $discount    = !empty($val['discount'])?$val['discount']:'0';
					    $round_value = !empty($val['round_value'])?$val['round_value']:'0';
					    $order_value = !empty($val['order_value'])?$val['order_value']:'';

					    $num = array(
			            	$order_no,
			            	date('d-m-Y', strtotime($order_date)),
			            	$emp_name,
			            	$store_name,
			            	$due_days,
			            	$discount,
			            	number_format((float)$round_value, 2, '.', ''),
			            	number_format((float)$order_value, 2, '.', ''),
			            );

			            fputcsv($output, $num);
		    		}
		    	}

		    	fclose($output);
      			exit();
        	}

        	if($param1 == 'invoice_print')
        	{
        		$start_date = $param2; 
        		$end_date   = $param3;
        		$outlet_id  = $param4;

        		$outlet_whr = array(
			    	'start_date' => date('Y-m-d', strtotime($start_date)),
					'end_date'   => date('Y-m-d', strtotime($end_date)),
			    	'outlet_id'  => $outlet_id,
			    	'method'     => '_overallInvoiceReport',
			    );

			    $data_list  = avul_call(API_URL.'report/api/outlet_report',$outlet_whr);

			    header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=outlet_invoice_report('.$start_date.' to '.$end_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Invoice No', 'Invoice Date', 'Order No', 'Distributor Name', 'Employee Name', 'Store Name', 'Due Days', 'Discount', 'Round Val', 'Total Val'));
			    
        		if($data_list['status'] == 1)
		    	{
		    		$data_val = $data_list['data'];	

		    		foreach ($data_val as $key => $val) {

		    			$inv_id      = !empty($val['inv_id'])?$val['inv_id']:'';
					    $bill_type   = !empty($val['bill_type'])?$val['bill_type']:'';
					    $order_no    = !empty($val['order_no'])?$val['order_no']:'';
					    $inv_no      = !empty($val['inv_no'])?$val['inv_no']:'';
					    $inv_date    = !empty($val['inv_date'])?$val['inv_date']:'';
					    $dis_name    = !empty($val['distributor_name'])?$val['distributor_name']:'';
					    $sales_emp   = !empty($val['sales_employee'])?$val['sales_employee']:'';
					    $store_name  = !empty($val['store_name'])?$val['store_name']:'';
					    $due_days    = !empty($val['due_days'])?$val['due_days']:'0';
					    $discount    = !empty($val['discount'])?$val['discount']:'0';
					    $round_value = !empty($val['round_value'])?$val['round_value']:'0';
					    $inv_value   = !empty($val['inv_value'])?$val['inv_value']:'0';

					    $num = array(
			            	$inv_no,
			            	date('d-m-Y', strtotime($inv_date)),
			            	$order_no,
			            	$dis_name,
			            	$sales_emp,
			            	$store_name,
			            	$due_days,
			            	$discount,
			            	number_format((float)$round_value, 2, '.', ''),
			            	number_format((float)$inv_value, 2, '.', ''),
			            );

			            fputcsv($output, $num);
		    		}
		    	}

		    	fclose($output);
      			exit();
        	}

        	if($param1 =='getCity_name')
			{
				$state_id = $this->input->post('state_id');
				
				$where = array(
					'id'       => $this->session->userdata('id'),
            		'state_id' => $state_id,
            		'method'   => '_listCity'
            	);

            	$city_list   = avul_call(API_URL.'managers/api/hierarchy_list',$where);
            	$city_result = $city_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($city_result))
        		{
        			foreach ($city_result as $key => $value) {
        				$city_id   = !empty($value['id'])?$value['id']:'';
                        $city_name = !empty($value['city_name'])?$value['city_name']:'';
						$city_code = !empty($value['city_code'])?$value['city_code']:'';

                        $option .= '<option value="'.$city_id.'">'.$city_name.'('.$city_code.')'.'</option>';
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
					'id'       => $this->session->userdata('id'),
            		'state_id' => $state_id,
            		'city_id'  => $city_id,
            		'method'   => '_listZone'
            	);

            	$zone_list   = avul_call(API_URL.'managers/api/hierarchy_list',$where);
            	$zone_result = $zone_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($zone_result))
        		{
        			foreach ($zone_result as $key => $value) {
        				$zone_id   = !empty($value['id'])?$value['id']:'';
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

			else if($param1 =='getOutlet_name')
			{
				$state_id = $this->input->post('state_id');
				$city_id  = $this->input->post('city_id');
				$zone_id  = $this->input->post('zone_id');

				$where = array(
            		'state_id' => $state_id,
            		'city_id'  => $city_id,
            		'zone_id'  => $zone_id,
            		'method'   => '_zoneWiseOutlets'
            	);

            	$outlet_list   = avul_call(API_URL.'outlets/api/outlets',$where);
            	$outlet_result = $outlet_list['data'];

            	$option ='<option value="">Select Value</option>';

        		if(!empty($outlet_result))
        		{
        			foreach ($outlet_result as $key => $value) {
        				$outlets_id   = !empty($value['outlets_id'])?$value['outlets_id']:'';
                        $company_name = !empty($value['company_name'])?$value['company_name']:'';

                        $option .= '<option value="'.$outlets_id.'">'.$company_name.'</option>';
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
				$where_1 = array(
					'method'   => 'get_state',
					'manager_id'  => $this->session->userdata('id'),
					
				);

            	$state_list  = avul_call(API_URL.'managers/api/hierarchy_list',$where_1);

            	$page['method']       = '_getOutletOverallData';
		    	$page['state_val']    = $state_list['data'];
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Outlet Overall Report";
				$page['page_title']   = "Outlet Overall Report";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('managers/report/outlet_overall',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "outlet_overall";
				$this->bassthaya->load_Managers_form_template($data);
			}	
        }

        public function outlet_history($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        {
        	if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method =='_getOutletHistory')
        	{
				$outlet_id = $this->input->post('outlet_id');

				$target_whr  = array(
        			'outlet_id' => $outlet_id,
            		'method'    => '_outletHistory'
            	);

            	$data_list = avul_call(API_URL.'report/api/outlet_report',$target_whr);

            	if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'];
		    		$num      = 1;

		    		$att_data = !empty($data_val['attendance_data'])?$data_val['attendance_data']:'';
		    		$ord_data = !empty($data_val['order_data'])?$data_val['order_data']:'';
		    		$inv_data = !empty($data_val['invoice_data'])?$data_val['invoice_data']:'';
		    		$pay_data = !empty($data_val['payment_data'])?$data_val['payment_data']:'';

		    		if(!empty($att_data))
		    		{
		    			$attendance_id   = !empty($att_data['attendance_id'])?$att_data['attendance_id']:'';
	                    $employee_name   = !empty($att_data['employee_name'])?$att_data['employee_name']:'';
	                    $attendance_type = !empty($att_data['attendance_type'])?$att_data['attendance_type']:'';
	                    $attendance_date = !empty($att_data['attendance_date'])?$att_data['attendance_date']:'';
	                    $attendance_time = !empty($att_data['attendance_time'])?$att_data['attendance_time']:'';

		    			$html .= '
		    				<div class="card-header">
                                <h4 class="card-title">Attendance Details</h4>
                            </div>
                            <div class="col-sm-12 filter-design">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Employee Name</th>
                                                <th>Attendance Type</th>
                                                <th>Attendance Date</th>
                                                <th>Attendance Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>'.$employee_name.'</td>
                                                <td>'.$attendance_type.'</td>
                                                <td>'.$attendance_date.'</td>
                                                <td>'.$attendance_time.'</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
		    			';
		    		}

		    		if(!empty($ord_data))
		    		{
	                    $order_no     = !empty($ord_data['order_no'])?$ord_data['order_no']:'';
	                    $emp_name     = !empty($ord_data['emp_name'])?$ord_data['emp_name']:'';
	                    $order_status = !empty($ord_data['order_status'])?$ord_data['order_status']:'';
	                    $_ordered     = !empty($ord_data['_ordered'])?$ord_data['_ordered']:'';
	                    $order_value  = !empty($ord_data['order_value'])?$ord_data['order_value']:'';
	                    $order_random = !empty($ord_data['random_value'])?$ord_data['random_value']:'';

	                    // Order Status
			            if($order_status == '1')
					    {
					        $order_view = '<span class="badge badge-success">Success</span>';
					    }
					    else if($order_status == '2')
					    {
					        $order_view = '<span class="badge badge-warning">Approved</span>';
					    }
					    else if($order_status == '3')
					    {
					        $order_view = '<span class="badge badge-primary">Packing</span>';
					    }
					    else if($order_status == '4')
					    {
					        $order_view = '<span class="badge badge-info">Shipping</span>';
					    }
					    else if($order_status == '5')
					    {
					        $order_view = '<span class="badge badge-warning">Invoice</span>';
					    }
					    else if($order_status == '6')
					    {
					        $order_view = '<span class="badge badge-success">Delivered</span>';
					    }
					    else if($order_status == '7')
					    {
					        $order_view = '<span class="badge badge-success">Complete</span>';
					    }
					    else
					    {
					        $order_view = '<span class="badge badge-danger">Cancel</span>';
					    }

		    			$html .= '
		    				<div class="card-header">
                                <h4 class="card-title">Order Details</h4>
                            </div>
                            <div class="col-sm-12 filter-design">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Order No</th>
                                                <th>Employee Name</th>
                                                <th>Order Status</th>
                                                <th>Order Date</th>
                                                <th>Order Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><a href="'.BASE_URL.'index.php/admin/order/print_order/'.$order_random.'" target="_blank">'.$order_no.'</a></td>
                                                <td>'.$emp_name.'</td>
                                                <td>'.$order_view.'</td>
                                                <td>'.date('d-M-Y', strtotime($_ordered)).'</td>
                                                <td>'.$order_value.'</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
		    			';
		    		}

		    		if(!empty($inv_data))
		    		{
		    			$inv_no     = !empty($inv_data['invoice_no'])?$inv_data['invoice_no']:'';
	                    $dis_name   = !empty($inv_data['distributor_name'])?$inv_data['distributor_name']:'';
	                    $inv_value  = !empty($inv_data['invoice_value'])?$inv_data['invoice_value']:'';
	                    $inv_date   = !empty($inv_data['createdate'])?$inv_data['createdate']:'';
	                    $inv_random = !empty($inv_data['random_value'])?$inv_data['random_value']:'';

		    			$html .= '
		    				<div class="card-header">
                                <h4 class="card-title">Invoice Details</h4>
                            </div>
                            <div class="col-sm-12 filter-design">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Invoice No</th>
                                                <th>Distributor Name</th>
                                                <th>Invoice Value</th>
                                                <th>Invoice Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><a href="'.BASE_URL.'index.php/admin/order/print_invoice/'.$inv_random.'" target="_blank">'.$inv_no.'</a></td>
                                                <td>'.$dis_name.'</td>
                                                <td>'.$inv_value.'</td>
                                                <td>'.date('d-M-Y', strtotime($inv_date)).'</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
		    			';
		    		}

		    		if(!empty($pay_data))
		    		{
	                    $dis_name = !empty($pay_data['distributor_name'])?$pay_data['distributor_name']:'';
	                    $bill_no  = !empty($pay_data['bill_no'])?$pay_data['bill_no']:'';
	                    $amount   = !empty($pay_data['amount'])?$pay_data['amount']:'0';
	                    $discount = !empty($pay_data['discount'])?$pay_data['discount']:'0';
	                    $amt_type = !empty($pay_data['amt_type'])?$pay_data['amt_type']:'';
	                    $pay_date = !empty($pay_data['date'])?$pay_data['date']:'';

		    			$html .= '
		    				<div class="card-header">
                                <h4 class="card-title">Payment Details</h4>
                            </div>
                            <div class="col-sm-12 filter-design">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Receipt No</th>
                                                <th>Distributor Name</th>
                                                <th>Amount</th>
                                                <th>Discount</th>
                                                <th>Payment Type</th>
                                                <th>Payment Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>'.$bill_no.'</td>
                                                <td>'.$dis_name.'</td>
                                                <td>'.$amount.'</td>
                                                <td>'.$discount.'</td>
                                                <td>'.$amt_type.'</td>
                                                <td>'.date('d-M-Y', strtotime($pay_date)).'</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
		    			';
		    		}

		    		$response['status']    = 1;
			        $response['message']   = $data_list['message']; 
			        $response['data']      = $html;
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

        	if($param1 =='getCity_name')
			{
				$state_id = $this->input->post('state_id');
				
				$where = array(
					'id'       => $this->session->userdata('id'),
            		'state_id' => $state_id,
            		'method'   => '_listCity'
            	);

            	$city_list   = avul_call(API_URL.'managers/api/hierarchy_list',$where);
            	$city_result = $city_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($city_result))
        		{
        			foreach ($city_result as $key => $value) {
        				$city_id   = !empty($value['id'])?$value['id']:'';
                        $city_name = !empty($value['city_name'])?$value['city_name']:'';
						$city_code = !empty($value['city_code'])?$value['city_code']:'';

                        $option .= '<option value="'.$city_id.'">'.$city_name.'('.$city_code.')'.'</option>';
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
					'id'       => $this->session->userdata('id'),
            		'state_id' => $state_id,
            		'city_id'  => $city_id,
            		'method'   => '_listZone'
            	);

            	$zone_list   = avul_call(API_URL.'managers/api/hierarchy_list',$where);
            	$zone_result = $zone_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($zone_result))
        		{
        			foreach ($zone_result as $key => $value) {
        				$zone_id   = !empty($value['id'])?$value['id']:'';
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

			else if($param1 =='getOutlet_name')
			{
				$state_id = $this->input->post('state_id');
				$city_id  = $this->input->post('city_id');
				$zone_id  = $this->input->post('zone_id');

				$where = array(
            		'state_id' => $state_id,
            		'city_id'  => $city_id,
            		'zone_id'  => $zone_id,
            		'method'   => '_zoneWiseOutlets'
            	);

            	$outlet_list   = avul_call(API_URL.'outlets/api/outlets',$where);
            	$outlet_result = $outlet_list['data'];

            	$option ='<option value="">Select Value</option>';

        		if(!empty($outlet_result))
        		{
        			foreach ($outlet_result as $key => $value) {
        				$outlets_id   = !empty($value['outlets_id'])?$value['outlets_id']:'';
                        $company_name = !empty($value['company_name'])?$value['company_name']:'';

                        $option .= '<option value="'.$outlets_id.'">'.$company_name.'</option>';
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
				$where_1 = array(
					'method'   => 'get_state',
					'manager_id'  => $this->session->userdata('id'),
					
				);

            	$state_list  = avul_call(API_URL.'managers/api/hierarchy_list',$where_1);
            	$page['method']       = '_getOutletHistory';
		    	$page['state_val']    = $state_list['data'];
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Outlet History";
				$page['page_title']   = "Outlet History";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('managers/report/outlet_history',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "outlet_history";
				$this->bassthaya->load_Managers_form_template($data);
			}
        }

        // public function order_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        // {
        // 	if ($this->session->userdata('random_value') == '')
        // 	redirect(base_url() . 'index.php?login', 'refresh');

        // 	$method = $this->input->post('method');

        // 	if($method =='_getOverallOrderData')
        // 	{
        // 		$start_date = $this->input->post('start_date');
        // 		$end_date   = $this->input->post('end_date');
        // 		$outlet_id  = $this->input->post('outlet_id');

        // 		$outlet_whr  = array(
        // 			'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
        //     		'method'     => '_overallOrderReport'
        //     	);

        //     	$data_list = avul_call(API_URL.'report/api/order_report',$outlet_whr);

        //     	if($data_list['status'] == 1)
		// 	    {
		// 	    	$html     = '';
		//     		$data_val = $data_list['data'];
		//     		$num      = 1;

		//     		foreach ($data_val as $key => $value) {
	    //                 $order_no   = !empty($value['order_no'])?$value['order_no']:'';
		//     			$store_name = !empty($value['store_name'])?$value['store_name']:'';
	    //                 $type_name  = !empty($value['type_name'])?$value['type_name']:'';
	    //                 $order_qty  = !empty($value['order_qty'])?$value['order_qty']:'';
	    //                 $ordered    = !empty($value['_ordered'])?$value['_ordered']:'';

	    //                 $html .= '
	    //                 	<tr>
        //                         <td>'.$num.'</td>
        //                         <td>'.$order_no.'</td>
        //                         <td>'.mb_strimwidth($store_name, 0, 25, '...').'</td>
        //                         <td>'.mb_strimwidth($type_name, 0, 25, '...').'</td>
        //                         <td>'.$order_qty.'</td>
        //                         <td>'.date('d-M-Y', strtotime($ordered)).'</td>
        //                     </tr>
	    //                 ';

	    //                 $num++;
		//     		}

		//     		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/order_report/excel_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		//     		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/order_report/pdf_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		//     		$response['status']    = 1;
		// 	        $response['message']   = $data_list['message']; 
		// 	        $response['data']      = $html;
		// 	        $response['excel_btn'] = $excel_btn;
		// 	        $response['pdf_btn']   = $pdf_btn;
		// 	        $response['error']     = []; 
		// 	        echo json_encode($response);
		// 	        return;
		// 	    }
		// 	    else
		//     	{
		//     		$response['status']  = 0;
		// 	        $response['message'] = $data_list['message']; 
		// 	        $response['data']    = [];
		// 	        $response['error']   = []; 
		// 	        echo json_encode($response);
		// 	        return;
		//     	}
        // 	}

        // 	if($param1 == 'excel_print')
        // 	{
        // 		$start_date = $param2; 
        // 		$end_date   = $param3;

        // 		$outlet_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'method'     => '_overallOrderReport',
		// 	    );

		// 	    $data_list = avul_call(API_URL.'report/api/order_report',$outlet_whr);

		// 	    header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=overall_order_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('Order No', 'Order Date', 'Store Name', 'Employee Name', 'Product Name', 'HSN Code', 'GST Val', 'Price', 'Order Qty', 'Total'));

		// 	    if($data_list['status'] == 1)
		// 	    {
		// 	    	$html     = '';
		//     		$data_val = $data_list['data'];
		//     		$num      = 1;

		//     		foreach ($data_val as $key => $value) {

		// 			    $order_no   = !empty($value['order_no'])?$value['order_no']:'';
		//     			$store_name = !empty($value['store_name'])?$value['store_name']:'';
		//     			$emp_name   = !empty($value['emp_name'])?$value['emp_name']:'';
		// 			    $type_name  = !empty($value['type_name'])?$value['type_name']:'';
		// 			    $hsn_code   = !empty($value['hsn_code'])?$value['hsn_code']:'';
		// 			    $gst_val    = !empty($value['gst_val'])?$value['gst_val']:'';
		// 			    $price      = !empty($value['price'])?$value['price']:'0';
		// 			    $order_qty  = !empty($value['order_qty'])?$value['order_qty']:'0';
		// 			    $ordered    = !empty($value['_ordered'])?$value['_ordered']:'';
		// 			    $pdt_total  = $price * $order_qty;

	    //                 $num = array(
        //                 	$order_no,
        //                 	date('d-m-Y', strtotime($ordered)),
        //                 	$store_name,
        //                 	$emp_name,
        //                 	$type_name,
        //                 	$hsn_code,
        //                 	$gst_val,
        //                 	$price,
        //                 	$order_qty,
        //                 	$pdt_total,
        //                 );

	    //                 fputcsv($output, $num); 
		//     		}
		// 	    }

		// 	    fclose($output);
      	// 		exit();
        // 	}

        // 	else
		// 	{
        //     	$page['method']       = '_getOverallOrderData';
		// 		$page['main_heading'] = "Report";
		// 		$page['sub_heading']  = "Report";
		// 		$page['pre_title']    = "Overall Order Report";
		// 		$page['page_title']   = "Overall Order Report";
		// 		$page['pre_menu']     = "";
		// 		$data['page_temp']    = $this->load->view('managers/report/order_report',$page,TRUE);
		// 		$data['view_file']    = "Page_Template";
		// 		$data['currentmenu']  = "order_report";
		// 		$this->bassthaya->load_Managers_form_template($data);
		// 	}
        // }

        public function target_achievement($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        {
        	if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method =='_getTargetAchievement')
        	{
        		$month_id    = $this->input->post('month_id');
				$year_id     = $this->input->post('year_id');
				$employee_id = $this->input->post('employee_id');
				$view_type   = $this->input->post('view_type');
				$position   = $this->input->post('position_id');
				$target_whr  = array(
        			'month_id'    => $month_id,
        			'year_id'     => $year_id,
        			'employee_id' => $employee_id,
        			'view_type'   => $view_type,
					'podition_id' => $position,
            		'method'      => '_employeeTargetDetails'
            	);
			
            	$data_list = avul_call(API_URL.'report/api/target_report',$target_whr);

            	if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'];
		    		$num      = 1;

		    		foreach ($data_val as $key => $value) {

	                    $username    = !empty($value['username'])?$value['username']:'';
	                    $mobile      = !empty($value['mobile'])?$value['mobile']:'';
	                    $description = !empty($value['description'])?$value['description']:'----';
	                    $target_val  = !empty($value['target_val'])?$value['target_val']:'0';
	                    $achieve_val = !empty($value['achieve_val'])?$value['achieve_val']:'0';

	                    $html .= '
	                    	<tr>
                                <td>'.$num.'</td>
                                <td>'.mb_strimwidth($username, 0, 15, '...').'</td>
                                <td>'.mb_strimwidth($description, 0, 45, '...').'</td>
                                <td>'.$target_val.'</td>
                                <td>'.$achieve_val.'</td>
                            </tr>
	                    ';

	                    $num++;
		    		}

		    		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/target_achievement/excel_print/'.$month_id.'/'.$year_id.'/'.$view_type.'/'.$employee_id.'/" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		    		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/target_achievement/pdf_print/'.$month_id.'/'.$year_id.'/'.$view_type.'/'.$employee_id.'/" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

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
        		$month_id    = !empty($param2)?$param2:'0';; 
        		$year_id     = !empty($param3)?$param3:'0';;
        		$view_type   = !empty($param4)?$param4:'0';;
        		$employee_id = !empty($param5)?$param5:'0';;

        		$target_whr  = array(
        			'month_id'    => $month_id,
        			'year_id'     => $year_id,
        			'employee_id' => $employee_id,
        			'view_type'   => $view_type,
            		'method'      => '_employeeTargetDetails'
            	);

            	$data_list = avul_call(API_URL.'report/api/target_report',$target_whr);

            	if($view_type == 1)
            	{
            		$view_status = 'overall';
            	}
            	else if($view_type == 2)
            	{
            		$view_status = 'product_wise';
            	}
            	else
            	{
            		$view_status = 'beat_wise';
            	}

            	header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename='.$view_status.'_target_achievement.csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Employee Name', 'Mobile No', 'Description', 'Target Value', 'Achieve Value'));

			    if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'];
		    		$num      = 1;

		    		foreach ($data_val as $key => $value) {

					    $username    = !empty($value['username'])?$value['username']:'';
	                    $mobile      = !empty($value['mobile'])?$value['mobile']:'';
	                    $description = !empty($value['description'])?$value['description']:'---';
	                    $target_val  = !empty($value['target_val'])?$value['target_val']:'0';
	                    $achieve_val = !empty($value['achieve_val'])?$value['achieve_val']:'0';

	                    $num = array(
                        	$username,
                        	$mobile,
                        	$description,
                        	$target_val,
                        	$achieve_val,
                        );

	                    fputcsv($output, $num); 
		    		}
			    }

			    fclose($output);
      			exit();
        	}
			if($param1 == 'emp_list')
        	{
        		$designation_code  = $this->input->post('designation_code');
			   
			   
				
			    $att_whr = array(
			    	'designation_code'  => $designation_code,
					'manager_id'  => $this->session->userdata('id'),
			    	'method'      => 'gethierarchy',
			    );

			    $data_list  = avul_call(API_URL.'managers/api/hierarchy_list',$att_whr);
		    	
		    	$data_val  = $data_list['data'];
				
			
        		$option ='<option value="">Select Employee Name</option>';

        		if(!empty($data_val))
        		{
        			foreach ($data_val as $key => $value) {
        				$employee_id   = !empty($value['employee_id']) ?$value['employee_id']:'';
                        $mobile = !empty($value['mobile'])?$value['mobile']:'';
						$position_id =!empty($value['position_id'])?$value['position_id']:'';
						$name =!empty($value['name'])?$value['name']:'';

                        $select   = '';
        				

                        $option .= '<option value="'.$employee_id.'" '.$select.'>'.$name.$mobile.'</option>';
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
				$where_1 = array(
					'designation_code' => $this->session->userdata('designation_code'),
					'method'   => '_listDesignation'
				);
			

				$data_list  = avul_call(API_URL.'employee/api/employee_designation',$where_1);
				$desgination_list   = !empty($data_list['data'])?$data_list['data']:'';

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

            	$page['method']       = '_getTargetAchievement';
            	$page['desgination_list']     = $desgination_list;
            	$page['month_list']   = $month_list;
            	$page['year_list']    = $year_list;
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Target Achievement";
				$page['page_title']   = "Target Achievement";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('managers/report/target_achievement',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "target_achievement";
				$this->bassthaya->load_Managers_form_template($data);
			}
        }

        public function employee_order($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        {
        	if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method =='_getEmployeeOrder')
        	{
        		$month_id    = $this->input->post('month_id');
				$year_id     = $this->input->post('year_id');
				$employee_id = $this->input->post('employee_id');

				$target_whr  = array(
        			'month_id'    => $month_id,
        			'year_id'     => $year_id,
        			'employee_id' => $employee_id,
            		'method'      => '_employeeOrderReport'
            	);

            	$data_list = avul_call(API_URL.'report/api/target_report',$target_whr);

            	if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'];
		    		$num      = 1;

		    		foreach ($data_val as $key => $value) {

	                    $date_value  = !empty($value['date_value'])?$value['date_value']:'';
	                    $day_value   = !empty($value['day_value'])?$value['day_value']:'';
	                    $order_count = !empty($value['order_count'])?$value['order_count']:'0';
	                    $order_total = !empty($value['order_total'])?$value['order_total']:'0';

	                    $html .= '
	                    	<tr>
                                <td>'.$num.'</td>
                                <td>'.$date_value.'</td>
                                <td>'.$day_value.'</td>
                                <td>'.$order_count.'</td>
                                <td>'.$order_total.'</td>
                            </tr>
	                    ';

	                    $num++;
		    		}

		    		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/employee_order/excel_print/'.$month_id.'/'.$year_id.'/'.$employee_id.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		    		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/employee_order/pdf_print/'.$month_id.'/'.$year_id.'/'.$employee_id.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

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
        		$month_id    = $param2; 
        		$year_id     = $param3;
        		$employee_id = $param4;

        		$whr_1 = array(
            		'employee_id' => $employee_id,
            		'method'      => '_detailEmployee'
            	);

            	$emp_data  = avul_call(API_URL.'employee/api/employee',$whr_1);
            	$emp_res   = $emp_data['data'][0];
            	$user_name = !empty($emp_res['username'])?$emp_res['username']:'';

        		$target_whr  = array(
        			'month_id'    => $month_id,
        			'year_id'     => $year_id,
        			'employee_id' => $employee_id,
            		'method'      => '_employeeOrderReport'
            	);

            	$data_list = avul_call(API_URL.'report/api/target_report',$target_whr);

            	header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename='.$user_name.'_order_value_report.csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Date', 'Day', 'Order Count', 'Order Value'));

			    if($data_list['status'] == 1)
			    {
			    	$data_val = $data_list['data'];
			    	
		    		foreach ($data_val as $key => $value) {

					    $date_value  = !empty($value['date_value'])?$value['date_value']:'';
	                    $day_value   = !empty($value['day_value'])?$value['day_value']:'';
	                    $order_count = !empty($value['order_count'])?$value['order_count']:'0';
	                    $order_total = !empty($value['order_total'])?$value['order_total']:'0';

	                    $num = array(
                        	$date_value,
                        	$day_value,
                        	$order_count,
                        	$order_total,
                        );

	                    fputcsv($output, $num); 
		    		}
			    }

			    fclose($output);
      			exit();
        	}
			if($param1 == 'emp_list')
        	{
        		$designation_code  = $this->input->post('designation_code');
			   
			   
				
			    $att_whr = array(
			    	'designation_code'  => $designation_code,
					'manager_id'  => $this->session->userdata('id'),
			    	'method'      => 'gethierarchy',
			    );

			    $data_list  = avul_call(API_URL.'managers/api/hierarchy_list',$att_whr);
		    	
		    	$data_val  = $data_list['data'];
				
			
        		$option ='<option value="">Select Employee Name</option>';

        		if(!empty($data_val))
        		{
        			foreach ($data_val as $key => $value) {
        				$employee_id   = !empty($value['employee_id']) ?$value['employee_id']:'';
                        $mobile = !empty($value['mobile'])?$value['mobile']:'';
						$position_id =!empty($value['position_id'])?$value['position_id']:'';
						$name =!empty($value['name'])?$value['name']:'';

                        $select   = '';
        				

                        $option .= '<option value="'.$employee_id.'" '.$select.'>'.$name.$mobile.'</option>';
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
				$where_1 = array(
					'designation_code' => $this->session->userdata('designation_code'),
					'method'   => '_listDesignation'
				);
			

				$data_list  = avul_call(API_URL.'employee/api/employee_designation',$where_1);
				$desgination_list   = !empty($data_list['data'])?$data_list['data']:'';

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

	        	$page['method']       = '_getEmployeeOrder';
	        	$page['desgination_list']     = $desgination_list;
	        	$page['month_list']   = $month_list;
	        	$page['year_list']    = $year_list;
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Employee Order Value";
				$page['page_title']   = "Employee Order Value";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('managers/report/employee_order',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "employee_order";
				$this->bassthaya->load_Managers_form_template($data);
        	}
        }

        // public function product_stock($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        // {
        // 	if ($this->session->userdata('random_value') == '')
        // 	redirect(base_url() . 'index.php?login', 'refresh');

        // 	$method = $this->input->post('method');

        // 	if($method =='_getProductStock')
        // 	{
        // 		$error = FALSE;
        // 		$category_id = $this->input->post('category_id');

        // 		$required = array('category_id');
		// 		foreach ($required as $field) 
		// 	    {
		// 	        if(empty($this->input->post($field)))
		// 	        {
		// 	            $error = TRUE;
		// 	        }
		// 	    }

		// 	    if($error == TRUE)
		// 	    {
		// 	    	$response['status']  = 0;
		// 	        $response['message'] = "Please fill all required fields"; 
		// 	        $response['data']    = [];
		// 	        $response['error']   = []; 
		// 	        echo json_encode($response);
		// 	        return;
		// 	    }
		// 	    else
		// 	    {
		// 	    	$category_val = implode(',', $category_id);
	    //     		$category_res = implode('_', $category_id);

		// 			$stock_whr  = array(
	    //     			'category_id' => $category_val,
	    //         		'method'      => '_overallProductStockReport'
	    //         	);

	    //         	$data_list = avul_call(API_URL.'report/api/stock_report',$stock_whr);
	    //         	$data_res  = $data_list['data'];

	    //         	if($data_list['status'] == 1)
		// 		    {	
		// 		    	$html     = '';
		// 	    		$data_val = $data_list['data'];
		// 	    		$num      = 1;

		// 		    	foreach ($data_res as $key => $val) {
		//                     $description  = !empty($val['description'])?$val['description']:'';
		// 				    $product_type = !empty($val['product_type'])?$val['product_type']:'0';
		// 				    $gst_value    = !empty($val['gst_value'])?$val['gst_value']:'0';
		// 				    $pdt_price    = !empty($val['product_price'])?$val['product_price']:'0';
		// 				    $stock_detail = !empty($val['stock_detail'])?$val['stock_detail']:'0';  
		// 				    $minimum_stock = !empty($val['minimum_stock'])?$val['minimum_stock']:'0';  

		// 				    $table_row = '';
		// 				    if($minimum_stock != 0 && $minimum_stock >= $stock_detail)
		// 				    {
		// 				    	$table_row = 'class="alret alert-danger"';
		// 				    }

		//                     $html .= '
		//                     	<tr '.$table_row.'>
	    //                             <td>'.$num.'</td>
	    //                             <td>'.mb_strimwidth($description, 0, 40, '...').'</td>
	    //                             <td>'.$pdt_price.'</td>
	    //                             <td>'.$stock_detail.'</td>
	    //                             <td>'.$minimum_stock.'</td>
	    //                             <td>Nos</td>
	    //                         </tr>
		//                     ';

		//                     $num++;
		// 		    	}

		// 		    	$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/product_stock/excel_print/'.$category_res.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		// 	    		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/product_stock/pdf_print/'.$category_res.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		// 	    		$response['status']    = 1;
		// 		        $response['message']   = $data_list['message']; 
		// 		        $response['data']      = $html;
		// 		        $response['excel_btn'] = $excel_btn;
		// 		        $response['pdf_btn']   = $pdf_btn;
		// 		        $response['error']     = []; 
		// 		        echo json_encode($response);
		// 		        return;
		// 		    }
		// 		    else
		// 	    	{
		// 	    		$response['status']  = 0;
		// 		        $response['message'] = $data_list['message']; 
		// 		        $response['data']    = [];
		// 		        $response['error']   = []; 
		// 		        echo json_encode($response);
		// 		        return;
		// 	    	}
		// 	    }
        // 	}

        // 	if($param1 =='excel_print')
        // 	{
        // 		$category_id  = $param2;
        // 		$category_val = explode('_', $category_id);
        // 		$category_res = implode(',', $category_val);

        // 		$stock_whr  = array(
        // 			'category_id' => $category_res,
        //     		'method'      => '_overallProductStockReport'
        //     	);

        //     	$data_list = avul_call(API_URL.'report/api/stock_report',$stock_whr);
        //     	$data_res  = $data_list['data'];

        //     	header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=admin_product_stock.csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('Description', 'Tax', 'Stock', 'Sum of Taxable Value',	'Sum of SGST Value', 'Sum of CGST Value', 'Sum of Net Amount'));

        //     	if($data_list['status'] == 1)
		// 	    {	
		// 	    	$totQty     = 0;
        //         	$totTaxable = 0;
        //         	$totSgst    = 0;
        //         	$totCgst    = 0;
        //         	$totValue   = 0;

		// 	    	foreach ($data_res as $key => $val) {
		// 	    		$description  = !empty($val['description'])?$val['description']:'';
		// 			    $product_type = !empty($val['product_type'])?$val['product_type']:'0';
		// 			    $gst_value    = !empty($val['gst_value'])?$val['gst_value']:'0';
		// 			    $pdt_price    = !empty($val['product_price'])?$val['product_price']:'0';
		// 			    $pdt_qty      = !empty($val['stock_detail'])?$val['stock_detail']:'0';

		// 			    $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
        //                 $price_val  = $pdt_price - $gst_data;
        //                 $pdt_gst    = $pdt_qty * $gst_data;
        //                 $TaxableAmt = $pdt_qty * $price_val;
        //                 $tot_price  = $pdt_qty * $pdt_price;
        //                 $pdt_value  = round($tot_price);
        //                 $gst_res    = $pdt_gst / 2;

        //                 $totQty     += $pdt_qty;
	    //             	$totTaxable += $TaxableAmt;
	    //             	$totSgst    += $gst_res;
	    //             	$totCgst    += $gst_res;
	    //             	$totValue   += $pdt_value;

		// 	            $num = array(
		// 	            	$description,
		// 	            	$gst_value,
		// 	            	$pdt_qty,
		// 	            	number_format((float)$TaxableAmt, 2, '.', ''),
		// 	            	number_format((float)$gst_res, 2, '.', ''),
		// 	            	number_format((float)$gst_res, 2, '.', ''),
		// 	            	number_format((float)$pdt_value, 2, '.', ''),
		// 	            );

	    //                 fputcsv($output, $num); 
		// 	    	}

		// 	    	fputcsv($output, array('', '', $totQty, number_format((float)$totTaxable, 2, '.', ''), number_format((float)$totSgst, 2, '.', ''), number_format((float)$totCgst, 2, '.', ''), number_format((float)$totValue, 2, '.', '')));
		// 	    }

		// 	    fclose($output);
      	// 		exit();
        // 	}

        // 	else
        // 	{
        // 		$where_1 = array(
		// 			'item_type'      => '1',
		// 			'salesagents_id' => '0',
        //     		'method'         => '_listCategory',
        //     	);

        //     	$category_list = avul_call(API_URL.'catlog/api/category',$where_1);

        // 		$page['method']        = '_getProductStock';
        // 		$page['category_val']  = $category_list['data'];
		// 		$page['main_heading']  = "Report";
		// 		$page['sub_heading']   = "Report";
		// 		$page['pre_title']     = "Product Stock";
		// 		$page['page_title']    = "Product Stock";
		// 		$page['pre_menu']      = "";
		// 		$data['page_temp']     = $this->load->view('admin/report/product_stock',$page,TRUE);
		// 		$data['view_file']     = "Page_Template";
		// 		$data['currentmenu']   = "product_stock";
		// 		$this->bassthaya->load_admin_form_template($data);
        // 	}
        // }

        // public function distributor_stock($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        // {
        // 	if ($this->session->userdata('random_value') == '')
        // 	redirect(base_url() . 'index.php?login', 'refresh');

        // 	$method = $this->input->post('method');

        // 	if($method =='_getDisProductStock')
        // 	{
        // 		$error = FALSE;
        // 		$distributor_id = $this->input->post('distributor_id');
        // 		$category_id    = $this->input->post('category_id');

        // 		$required = array('distributor_id', 'category_id');
		// 		foreach ($required as $field) 
		// 	    {
		// 	        if(empty($this->input->post($field)))
		// 	        {
		// 	            $error = TRUE;
		// 	        }
		// 	    }

		// 	    if($error == TRUE)
		// 	    {
		// 	    	$response['status']  = 0;
		// 	        $response['message'] = "Please fill all required fields"; 
		// 	        $response['data']    = [];
		// 	        $response['error']   = []; 
		// 	        echo json_encode($response);
		// 	        return;
		// 	    }
		// 	    else
		// 	    {
		// 	    	$category_val   = implode(',', $category_id);
	    //     		$category_res   = implode('_', $category_id);

	    //     		$stock_whr = array(
	    //     			'distributor_id' => $distributor_id,
	    //     			'category_id'    => $category_val,
	    //         		'method'         => '_overallDistributorStockReport',
	    //         	);

	    //         	$data_list = avul_call(API_URL.'report/api/stock_report',$stock_whr);
	    //         	$data_res  = $data_list['data'];

	    //         	if($data_list['status'] == 1)
		// 		    {
		// 		    	$html     = '';
		// 	    		$data_val = $data_list['data'];
		// 	    		$num      = 1;

		// 		    	foreach ($data_res as $key => $val) {
		//                     $description   = !empty($val['description'])?$val['description']:'';  
		//                     $gst_value     = !empty($val['gst_value'])?$val['gst_value']:'';  
		//                     $product_price = !empty($val['product_price'])?$val['product_price']:'';  
		//                     $view_stock    = !empty($val['view_stock'])?$val['view_stock']:'0';  
		//                     $minimum_stock = !empty($val['minimum_stock'])?$val['minimum_stock']:'0';  

		//                     $table_row = '';
		// 				    if($minimum_stock != 0 && $minimum_stock >= $view_stock)
		// 				    {
		// 				    	$table_row = 'class="alret alert-danger"';
		// 				    }

		//                     $html .= '
		//                     	<tr '.$table_row.'>
	    //                             <td>'.$num.'</td>
	    //                             <td>'.mb_strimwidth($description, 0, 40, '...').'</td>
	    //                             <td>'.$view_stock.'</td>
	    //                             <td>'.$minimum_stock.'</td>
	    //                             <td>Nos</td>
	    //                         </tr>
		//                     ';

		//                     $num++;
		// 		    	}

		// 		    	$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/distributor_stock/excel_print/'.$distributor_id.'/'.$category_res.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		// 	    		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/distributor_stock/pdf_print/'.$distributor_id.'/'.$category_res.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		// 	    		$response['status']    = 1;
		// 		        $response['message']   = $data_list['message']; 
		// 		        $response['data']      = $html;
		// 		        $response['excel_btn'] = $excel_btn;
		// 		        $response['pdf_btn']   = $pdf_btn;
		// 		        $response['error']     = []; 
		// 		        echo json_encode($response);
		// 		        return;
		// 		    }
		// 		    else
		// 	    	{
		// 	    		$response['status']  = 0;
		// 		        $response['message'] = $data_list['message']; 
		// 		        $response['data']    = [];
		// 		        $response['error']   = []; 
		// 		        echo json_encode($response);
		// 		        return;
		// 	    	}
		// 	    }
        // 	}

        // 	if($param1 =='getCategotry_list')
        // 	{
        // 		$distributor_id = $this->input->post('distributor_id');

        // 		$where = array(
        // 			'distributor_id' => $distributor_id,
        // 			'method'         => '_distributorCategoryList',
        // 		);

        // 		$category_list = avul_call(API_URL.'distributors/api/distributors',$where);
        // 		$category_res  = $category_list['data'];

        // 		$option ='<option value="">Select Value</option>';

        // 		if(!empty($category_res))
        // 		{
        // 			foreach ($category_res as $key => $value) {
        // 				$category_id   = !empty($value['category_id'])?$value['category_id']:'';
        //                 $category_name = !empty($value['category_name'])?$value['category_name']:'';

        //                 $option .= '<option value="'.$category_id.'">'.$category_name.'</option>';
        // 			}
        // 		}

        // 		$response['status']  = 1;
		//         $response['message'] = 'success'; 
		//         $response['data']    = $option;
		//         echo json_encode($response);
		//         return; 
        // 	}

        // 	if($param1 =='excel_print')
        // 	{
        // 		$distributor_id = $param2;
        // 		$category_id    = $param3;
        // 		$category_val   = explode('_', $category_id);
        // 		$category_res   = implode(',', $category_val);

        // 		$stock_whr = array(
        // 			'distributor_id' => $distributor_id,
        // 			'category_id'    => $category_res,
        //     		'method'         => '_overallDistributorStockReport',
        //     	);

        //     	$data_list = avul_call(API_URL.'report/api/stock_report',$stock_whr);
        //     	$data_res  = $data_list['data'];

        //     	$where = array(
        //     		'distributor_id' => $distributor_id,
        //     		'method'         => '_detailDistributors'
        //     	);

        //     	$data_list    = avul_call(API_URL.'distributors/api/distributors',$where);
        //     	$data_value   = $data_list['data'][0];	
        //     	$company_name = !empty($data_value['company_name'])?$data_value['company_name']:'';

		// 		header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename='.$company_name.'_product_stock.csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('Description', 'Tax', 'Stock', 'Sum of Taxable Value',	'Sum of SGST Value', 'Sum of CGST Value', 'Sum of Net Amount'));            	

        //     	if($data_list['status'] == 1)
		// 	    {
		// 	    	$totQty     = 0;
        //         	$totTaxable = 0;
        //         	$totSgst    = 0;
        //         	$totCgst    = 0;
        //         	$totValue   = 0;

		// 	    	foreach ($data_res as $key => $val) {
		// 	    		$description = !empty($val['description'])?$val['description']:'';  
	    //                 $gst_value   = !empty($val['gst_value'])?$val['gst_value']:'';  
	    //                 $pdt_price   = !empty($val['product_price'])?$val['product_price']:'';  
	    //                 $pdt_qty     = !empty($val['view_stock'])?$val['view_stock']:'0';  

	    //                 $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
        //                 $price_val  = $pdt_price - $gst_data;
        //                 $pdt_gst    = $pdt_qty * $gst_data;
        //                 $TaxableAmt = $pdt_qty * $price_val;
        //                 $tot_price  = $pdt_qty * $pdt_price;
        //                 $pdt_value  = round($tot_price);
        //                 $gst_res    = $pdt_gst / 2;

        //                 $totQty     += $pdt_qty;
	    //             	$totTaxable += $TaxableAmt;
	    //             	$totSgst    += $gst_res;
	    //             	$totCgst    += $gst_res;
	    //             	$totValue   += $pdt_value;

		// 	            $num = array(
		// 	            	$description,
		// 	            	$gst_value,
		// 	            	$pdt_qty,
		// 	            	number_format((float)$TaxableAmt, 2, '.', ''),
		// 	            	number_format((float)$gst_res, 2, '.', ''),
		// 	            	number_format((float)$gst_res, 2, '.', ''),
		// 	            	number_format((float)$pdt_value, 2, '.', ''),
		// 	            );

	    //                 fputcsv($output, $num);  
		// 	    	}

		// 	    	fputcsv($output, array('', '', $totQty, number_format((float)$totTaxable, 2, '.', ''), number_format((float)$totSgst, 2, '.', ''), number_format((float)$totCgst, 2, '.', ''), number_format((float)$totValue, 2, '.', '')));
		// 	    }

		// 	    fclose($output);
      	// 		exit();
        // 	}

        // 	else
        // 	{
        //     	$where_1 = array(
		// 			'ref_id' => 0,
        //     		'method' => '_listOverallDistributors',
        //     	);

        //     	$distributor_list = avul_call(API_URL.'distributors/api/distributors',$where_1);

        // 		$page['method']         = '_getDisProductStock';
        // 		$page['dis_val']        = $distributor_list['data'];
		// 		$page['main_heading']   = "Report";
		// 		$page['sub_heading']    = "Report";
		// 		$page['pre_title']      = "Distributor Product Stock";
		// 		$page['page_title']     = "Distributor Product Stock";
		// 		$page['pre_menu']       = "";
		// 		$data['page_temp']      = $this->load->view('admin/report/distributor_stock',$page,TRUE);
		// 		$data['view_file']      = "Page_Template";
		// 		$data['currentmenu']    = "distributor_stock";
		// 		$this->bassthaya->load_admin_form_template($data);
        // 	}
        // }

        // public function distributor_overall($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        // {
        // 	if ($this->session->userdata('random_value') == '')
        // 	redirect(base_url() . 'index.php?login', 'refresh');

        // 	$method = $this->input->post('method');

        // 	if($method =='_getDistributorOverallData')
        // 	{
		// 		$start_date     = $this->input->post('start_date');
		// 		$end_date       = $this->input->post('end_date');        		
		// 		$distributor_id = $this->input->post('distributor_id');

		// 		$distributor_whr = array(
		// 			'start_date'     => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'       => date('Y-m-d', strtotime($end_date)),
        // 			'distributor_id' => $distributor_id,
        //     		'method'         => '_distributorOverallReport',
        //     	);

        //     	$data_list = avul_call(API_URL.'report/api/tally_report',$distributor_whr);
        //     	$data_res  = $data_list['data'];

        //     	if($data_list['status'] == 1)
		// 	    {
		// 	    	$html     = '';
		//     		$data_val = $data_list['data'];
		//     		$num      = 1;

		//     		foreach ($data_res as $key => $val) {
		//     			$bill_code = !empty($val['bill_code'])?$val['bill_code']:'';
	    //                 $bill_no   = !empty($val['bill_no'])?$val['bill_no']:'';
	    //                 $pay_date  = !empty($val['pay_date'])?$val['pay_date']:'';
	    //                 $payment   = !empty($val['payment'])?$val['payment']:'';
	    //                 $cr_ledger = !empty($val['cr_ledger'])?$val['cr_ledger']:'';

	    //                 $html .= '
	    //                 	<tr>
        //                         <td>'.$num.'</td>
        //                         <td>'.mb_strimwidth($cr_ledger, 0, 20, '...').'</td>
        //                         <td>'.$bill_no.'</td>
        //                         <td>'.date('d-M-Y', strtotime($pay_date)).'</td>
        //                         <td>'.$payment.'</td>
        //                     </tr>
	    //                 ';

	    //                 $num++;
		//     		}

		//     		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/distributor_overall/excel_print/'.$start_date.'/'.$end_date.'/'.$distributor_id.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		//     		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/distributor_overall/pdf_print/'.$start_date.'/'.$end_date.'/'.$distributor_id.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		//     		$response['status']    = 1;
		// 	        $response['message']   = $data_list['message']; 
		// 	        $response['data']      = $html;
		// 	        $response['excel_btn'] = $excel_btn;
		// 	        $response['pdf_btn']   = $pdf_btn;
		// 	        $response['error']     = []; 
		// 	        echo json_encode($response);
		// 	        return;
		// 	    }
		// 	    else
		//     	{
		//     		$response['status']  = 0;
		// 	        $response['message'] = $data_list['message']; 
		// 	        $response['data']    = [];
		// 	        $response['error']   = []; 
		// 	        echo json_encode($response);
		// 	        return;
		//     	}
        // 	}

        // 	if($param1 =='excel_print')
        // 	{
        // 		$start_date     = $param2;
        // 		$end_date       = $param3;
        // 		$distributor_id = $param4;

        // 		$distributor_whr = array(
		// 			'start_date'     => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'       => date('Y-m-d', strtotime($end_date)),
        // 			'distributor_id' => $distributor_id,
        //     		'method'         => '_distributorOverallReport',
        //     	);

        //     	$data_list = avul_call(API_URL.'report/api/tally_report',$distributor_whr);
        //     	$data_res  = $data_list['data'];

        //     	header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=distributor_invoice_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('Voucher Type', 'Voucher No', 'Voucher Date', 'DR Leder', 'Reference Type', 'New Ref', 'Cost Centre', 'Amount Dr', 'Cr Ledger', 'Reference Type', 'New Ref', 'Cost Centre', 'Amount Cr', 'Voucher narration'));

        //     	if($data_list['status'] == 1)
		// 	    {
		// 		    foreach ($data_res as $key => $val) {
		// 	    		$bill_code   = !empty($val['bill_code'])?$val['bill_code']:'';
		// 	            $bill_no     = !empty($val['bill_no'])?$val['bill_no']:'';
		// 	            $pay_date    = !empty($val['pay_date'])?$val['pay_date']:'';
		// 	            $dr_ledger   = !empty($val['dr_ledger'])?$val['dr_ledger']:'';
		// 	            $ref_type    = !empty($val['ref_type'])?$val['ref_type']:'';
		// 	            $new_ref     = !empty($val['new_ref'])?$val['new_ref']:'';
		// 	            $cost_center = !empty($val['cost_center'])?$val['cost_center']:'';
		// 	            $payment     = !empty($val['payment'])?$val['payment']:'';
		// 	            $cr_ledger   = !empty($val['cr_ledger'])?$val['cr_ledger']:'';
		// 	            $amount      = !empty($val['amount'])?$val['amount']:'';
		// 	            $vhr_nar     = !empty($val['vhr_nar'])?$val['vhr_nar']:'';

		// 	            $num = array(
        //                 	$bill_code,
		// 		            $bill_no,
		// 		            $pay_date,
		// 		            $dr_ledger,
		// 		            $ref_type,
		// 		            $new_ref,
		// 		            $cost_center,
		// 		            $payment,
		// 		            $cr_ledger,
		// 		            '',
		// 		            '',
		// 		            '',
		// 		            $amount,
		// 		            $vhr_nar,
        //                 );

	    //                 fputcsv($output, $num); 
		// 	    	}
		// 	    }

		// 	    fclose($output);
      	// 		exit();
        // 	}

        // 	else
		// 	{
		// 		$where_1 = array(
		// 			'ref_id' => 0,
        //     		'method'   => '_listOverallDistributors'
        //     	);

        //     	$distributor_list  = avul_call(API_URL.'distributors/api/distributors',$where_1);

        //     	$page['method']          = '_getDistributorOverallData';
		//     	$page['distributor_val'] = $distributor_list['data'];
		// 		$page['main_heading']    = "Report";
		// 		$page['sub_heading']     = "Report";
		// 		$page['pre_title']       = "Distributor Overall Report";
		// 		$page['page_title']      = "Distributor Overall Report";
		// 		$page['pre_menu']        = "";
		// 		$data['page_temp']       = $this->load->view('admin/report/distributor_overall',$page,TRUE);
		// 		$data['view_file']       = "Page_Template";
		// 		$data['currentmenu']     = "distributor_overall";
		// 		$this->bassthaya->load_admin_form_template($data);
		// 	}
        // }

        // public function distributor_order($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        // {
        // 	if ($this->session->userdata('random_value') == '')
        // 	redirect(base_url() . 'index.php?login', 'refresh');

        // 	$method = $this->input->post('method');

        // 	if($method =='_getDistributorOrderData')
        // 	{
        // 		$start_date = $this->input->post('start_date');
		// 		$end_date   = $this->input->post('end_date');        		

		// 		$distributor_whr = array(
		// 			'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
        //     		'method'     => '_distributorOverallOrderReport',
        //     	);

        //     	$data_list = avul_call(API_URL.'report/api/distributor_report',$distributor_whr);
        //     	$data_res  = $data_list['data'];

        //     	if($data_list['status'] == 1)
		// 	    {
		// 	    	$html     = '';
		//     		$data_val = $data_list['data'];
		//     		$num      = 1;

		//     		foreach ($data_res as $key => $val) {
		//     			$company_name   = !empty($val['company_name'])?$val['company_name']:'';
	    //                 $contact_no     = !empty($val['contact_no'])?$val['contact_no']:'';
	    //                 $process_order  = !empty($val['process_order'])?$val['process_order']:'0';
	    //                 $packing_order  = !empty($val['packing_order'])?$val['packing_order']:'0';
	    //                 $invoice_order  = !empty($val['invoice_order'])?$val['invoice_order']:'0';
	    //                 $delivery_order = !empty($val['delivery_order'])?$val['delivery_order']:'0';
	    //                 $cancel_order   = !empty($val['cancel_order'])?$val['cancel_order']:'0';

	    //                 $html .= '
	    //                 	<tr>
        //                         <td>'.$num.'</td>
        //                         <td>'.mb_strimwidth($company_name, 0, 25, '...').'</td>
        //                         <td>'.$process_order.'</td>
        //                         <td>'.$packing_order.'</td>
        //                         <td>'.$invoice_order.'</td>
        //                         <td>'.$delivery_order.'</td>
        //                         <td>'.$cancel_order.'</td>
        //                     </tr>
	    //                 ';

	    //                 $num++;
		//     		}

		//     		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/distributor_order/excel_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		//     		$order_btn = '<a class="btn btn-warning m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/distributor_order/order_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-shopping-cart"></i> Order</a>';

		//     		$invoice_btn = '<a class="btn btn-info m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/distributor_order/invoice_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-notebook"></i> Invoice</a>';

		//     		$hoisst_btn = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/distributor_order/hoisst_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Hoisst</a>';

		//     		$del_btn   = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/distributor_order/del_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-file-text"></i> Delivered</a>';

		//     		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/distributor_order/pdf_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		//     		$response['status']      = 1;
		// 	        $response['message']     = $data_list['message']; 
		// 	        $response['data']        = $html;
		// 	        $response['excel_btn']   = $excel_btn;
		// 	        $response['invoice_btn'] = $invoice_btn;
		// 	        $response['order_btn']   = $order_btn;
		// 	        $response['hoisst_btn']  = $hoisst_btn;
		// 	        $response['del_btn']     = $del_btn;
		// 	        $response['pdf_btn']     = $pdf_btn;
		// 	        $response['error']       = []; 
		// 	        echo json_encode($response);
		// 	        return;
		// 	    }
		// 	    else
		//     	{
		//     		$response['status']  = 0;
		// 	        $response['message'] = $data_list['message']; 
		// 	        $response['data']    = [];
		// 	        $response['error']   = []; 
		// 	        echo json_encode($response);
		// 	        return;
		//     	}
        // 	}	

        // 	if($param1 =='excel_print')
        // 	{
        // 		$start_date = $param2;
        // 		$end_date   = $param3;

        // 		$distributor_whr = array(
		// 			'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
        //     		'method'     => '_distributorOverallOrderReport',
        //     	);

        //     	$data_list = avul_call(API_URL.'report/api/distributor_report',$distributor_whr);
        //     	$data_res  = $data_list['data'];

        //     	header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=distributor_order_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('Distributor Name', 'Contact Number', 'Process Order', 'Packing Order', 'Shipping Order', 'Invoice Order', 'Delivery Order', 'Cancel Order'));

        //     	if($data_list['status'] == 1)
        //     	{
        //     		foreach ($data_res as $key => $val) {
        //     			$distributor_id = !empty($val['distributor_id'])?$val['distributor_id']:'';
		// 			    $company_name   = !empty($val['company_name'])?$val['company_name']:'';
		// 			    $contact_no     = !empty($val['contact_no'])?$val['contact_no']:'';
		// 			    $process_order  = !empty($val['process_order'])?$val['process_order']:'0';
		// 			    $packing_order  = !empty($val['packing_order'])?$val['packing_order']:'0';
		// 			    $shipping_order = !empty($val['shipping_order'])?$val['shipping_order']:'0';
		// 			    $invoice_order  = !empty($val['invoice_order'])?$val['invoice_order']:'0';
		// 			    $delivery_order = !empty($val['delivery_order'])?$val['delivery_order']:'0';
		// 			    $cancel_order   = !empty($val['cancel_order'])?$val['cancel_order']:'0';

		// 			    $num = array(
        //                 	$company_name,
        //                 	$contact_no,
        //                 	$process_order,
        //                 	$packing_order,
        //                 	$shipping_order,
        //                 	$invoice_order,
        //                 	$delivery_order,
        //                 	$cancel_order,
        //                 );

        //                 fputcsv($output, $num); 
        //     		}
        //     	}

        //     	fclose($output);
      	// 		exit();
        // 	}

        // 	if($param1 =='invoice_print')
        // 	{
        // 		$start_date  = $param2; 
        // 		$end_date    = $param3;

        // 		$outlet_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'view_type'  => '3',
		// 	    	'method'     => '_getOutletOverallReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/outlet_report',$outlet_whr);

		// 	    header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=invoice_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('Date', 'Invoice No', 'Distributor Name', 'Dealer Name', 'BDE', 'Outlet Name', 'Invoice Value', 'Against PO No'));
			    
        // 		if($data_list['status'] == 1)
		//     	{
		//     		$data_val = $data_list['data'];	

		//     		foreach ($data_val as $key => $val) {

		//     			$invoice_date = !empty($val['invoice_date'])?$val['invoice_date']:'';
		//     			$invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
		//     			$emp_name     = !empty($val['emp_name'])?$val['emp_name']:'';
		//     			$str_name     = !empty($val['str_name'])?$val['str_name']:'';
		//     			$dis_username = !empty($val['dis_username'])?$val['dis_username']:'';
		//     			$invData_list = !empty($val['invData_list'])?$val['invData_list']:'';
		//     			$order_no     = !empty($val['order_no'])?$val['order_no']:'';
		//     			$discount     = !empty($val['discount'])?$val['discount']:'0';

		//     			$sub_tot = 0;
	    //                 $tot_gst = 0;
		//     			$net_tot = 0;
	    //                 if($invData_list)
	    //                 {
	    //                 	foreach ($invData_list as $key => $val_1) {
	    //                 		$gst_value = !empty($val_1['gst_val'])?$val_1['gst_val']:'0';
        //                         $pdt_price = !empty($val_1['price_val'])?number_format((float)$val_1['price_val'], 2, '.', ''):'0';
        //                         $pdt_qty   = !empty($val_1['order_qty'])?$val_1['order_qty']:'0';

        //                         $gst_data  = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
	    //                         $price_val = $pdt_price - $gst_data;
	    //                         $tot_price = $pdt_qty * $price_val;
	    //                         $sub_tot  += $tot_price;
        //                         $gst_val   = $pdt_qty * $gst_data;
        //                     	$tot_gst  += $gst_val;

        //                         $total_val = $pdt_qty * $pdt_price;
        //                     	$net_tot  += $total_val;
	    //                 	}
	    //                 }

	    //                 // Round Val Details
        //                 $net_value  = round($net_tot);
        //                 if($discount != 0)
        //                 {
        //                 	$total_dis  = $net_value * $discount / 100;
        //                 }
        //                 else
        //                 {
        //                 	$total_dis = 0;	
        //                 }

        //                 $total_val  = $net_value - $total_dis;

        //                 // Round Val Details
        //                 $last_value = round($total_val);

        //                 // Round Val Details
        //                 $last_value = round($total_val);
        //                 $rond_total = $last_value - $total_val;

	    //                 $num = array(
		// 	            	date('d-m-Y', strtotime($invoice_date)),
		// 	            	$invoice_no,
	    //                 	$dis_username,
	    //                 	'',
	    //                 	$emp_name,
	    //                 	$str_name,
	    //            			// number_format((float)$total_dis, 2, '.', ''),
		// 	            	// number_format((float)$rond_total, 2, '.', ''),
		// 	            	number_format((float)$last_value, 2, '.', ''),
		// 	            	$order_no,
		// 	            );

		// 	            fputcsv($output, $num);
		//     		}
		//     	}

		//     	fclose($output);
      	// 		exit();
        // 	}

        // 	if($param1 =='order_print')
        // 	{
        // 		$start_date  = $param2; 
        // 		$end_date    = $param3;

        // 		$outlet_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'method'     => '_getOutletOverallOrder',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/distributor_report',$outlet_whr);

		// 	    header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=retailer_order_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('Date', 'Retailer Name', 'PO No', 'PO Value', 'Distributor'));
			    
        // 		if($data_list['status'] == 1)
		//     	{
		//     		$data_res  = $data_list['data'];

		//     		foreach ($data_res as $key => $val) {
		//     			$order_no         = !empty($val['order_no'])?$val['order_no']:'';
	    //                 $store_name       = !empty($val['store_name'])?$val['store_name']:'';
	    //                 $distributor_name = !empty($val['distributor_name'])?$val['distributor_name']:'';
	    //                 $discount         = !empty($val['discount'])?$val['discount']:'';
	    //                 $due_days         = !empty($val['due_days'])?$val['due_days']:'';
	    //                 $order_status     = !empty($val['order_status'])?$val['order_status']:'';
	    //                 $round_value      = !empty($val['round_value'])?$val['round_value']:'';
	    //                 $order_value      = !empty($val['order_value'])?$val['order_value']:'';
	    //                 $order_date       = !empty($val['order_date'])?$val['order_date']:'';

	    //                 $num = array(
        //                 	$order_date,
        //                 	$store_name,
        //                 	$order_no,
        //                 	$order_value,
        //                 	$distributor_name,
        //                 	// $order_status,
        //                 );

        //                 fputcsv($output, $num);
		//     		}
		//     	}

		//     	fclose($output);
      	// 		exit();
        // 	}

        // 	if($param1 =='hoisst_print')
        // 	{
        // 		$start_date  = $param2; 
        // 		$end_date    = $param3;

        // 		$outlet_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'method'     => '_getOutletOverallOrder',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/distributor_report',$outlet_whr);

		// 	    header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=hoisst_overall_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('Order No', 'Invoice No', 'Employee Name', 'Outlet Name', 'Distributor Name', 'Order Value', 'Order Status', 'Order Date', 'Approved Date', 'Packing Date', 'Shipping Date', 'Invoice Date', 'Delivery Date'));
			    
        // 		if($data_list['status'] == 1)
		//     	{
		//     		$data_val = $data_list['data'];	

		//     		foreach ($data_val as $key => $val) {

		//     			$order_no     = !empty($val['order_no'])?$val['order_no']:'';
	    //                 $emp_name     = !empty($val['emp_name'])?$val['emp_name']:'';
	    //                 $store_name   = !empty($val['store_name'])?$val['store_name']:'';
	    //                 $dis_name     = !empty($val['distributor_name'])?$val['distributor_name']:'';
	    //                 $discount     = !empty($val['discount'])?$val['discount']:'';
	    //                 $due_days     = !empty($val['due_days'])?$val['due_days']:'';
	    //                 $order_status = !empty($val['order_status'])?$val['order_status']:'';
	    //                 $round_value  = !empty($val['round_value'])?$val['round_value']:'';
	    //                 $order_value  = !empty($val['order_value'])?$val['order_value']:'';
	    //                 $order_date   = !empty($val['order_date'])?$val['order_date']:'';
	    //                 $_processing  = !empty($val['_processing'])?$val['_processing']:'';
	    //                 $_packing     = !empty($val['_packing'])?$val['_packing']:'';
	    //                 $_shiping     = !empty($val['_shiping'])?$val['_shiping']:'';
	    //                 $_invoice     = !empty($val['_invoice'])?$val['_invoice']:'';
	    //                 $_delivery    = !empty($val['_delivery'])?$val['_delivery']:'';
	    //                 $invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';

	    //                 $num = array(
	    //                 	$order_no,
	    //                 	$invoice_no,
	    //                 	$emp_name,
	    //                 	$store_name,
	    //                 	$dis_name,
	    //                 	number_format((float)$order_value, 2, '.', ''),
	    //                 	$order_status,
	    //                 	$order_date,
	    //                 	$_processing,
	    //                 	$_packing,
	    //                 	$_shiping,
	    //                 	$_invoice,
	    //                 	$_delivery
		// 	            );

		// 	            fputcsv($output, $num);
		//     		}
		//     	}

		//     	fclose($output);
      	// 		exit();
        // 	}

        // 	if($param1 =='del_print')
        // 	{
        // 		$start_date  = $param2; 
        // 		$end_date    = $param3;

        // 		$outlet_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'view_type'  => '3',
		// 	    	'method'     => '_getOutletOverallReport',
		// 	    );

		// 	    print_r($outlet_whr); exit;	
        // 	}

        // 	else
		// 	{
        //     	$page['method']       = '_getDistributorOrderData';
		// 		$page['main_heading'] = "Report";
		// 		$page['sub_heading']  = "Report";
		// 		$page['pre_title']    = "Distributor Order Report";
		// 		$page['page_title']   = "Distributor Order Report";
		// 		$page['pre_menu']     = "";
		// 		$data['page_temp']    = $this->load->view('admin/report/distributor_order',$page,TRUE);
		// 		$data['view_file']    = "Page_Template";
		// 		$data['currentmenu']  = "distributor_order";
		// 		$this->bassthaya->load_admin_form_template($data);
		// 	}
        // }

        public function sales_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
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
			    	'start_date'     => date('Y-m-d', strtotime($start_date)),
					'end_date'       => date('Y-m-d', strtotime($end_date)),
			    	'method'         => '_overallInviceReport',
			    );

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
                                <td><a target="_blank" href="'.BASE_URL.'index.php/admin/distributorsorder/print_invoice/'.$inv_res.'">'.$inv_no.'</a></td>
                                <td><a target="_blank" href="'.BASE_URL.'index.php/admin/distributorsorder/print_order/'.$pur_res.'">'.$order_no.'</a></td>

                                <td>'.mb_strimwidth($company_name, 0, 25, '...').'</td>
                                <td>'.date('d-M-Y', strtotime($inv_date)).'</td>
                                <td>'.number_format((float)$inv_value, 2, '.', '').'</td>
                            </tr>
			            ';

			            $num++;
		    		}

		    		// $sales_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/sales_report/sales_export/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Sales Export</a>';

		    		$tally_btn = '<a class="btn btn-warning" target="_blank" href="'.BASE_URL.'index.php/admin/report/sales_report/tally_export/'.$start_date.'/'.$end_date.'/'.$distributor_id.'" style="color: #fff;"><i class="icon-grid"></i> Tally</a>';

		    		$gst_btn = '<a class="btn btn-info" target="_blank" href="'.BASE_URL.'index.php/admin/report/sales_report/gst_export/'.$start_date.'/'.$end_date.'/'.$distributor_id.'" style="color: #fff;"><i class="icon-grid"></i> GST</a>';

		    		$invoice_btn = '<a class="btn btn-success" target="_blank" href="'.BASE_URL.'index.php/admin/report/sales_report/invoice_export/'.$start_date.'/'.$end_date.'/'.$distributor_id.'" style="color: #fff;"><i class="ft-shopping-cart"></i> Invoice</a>';

		    		$new_btn = '<a class="btn btn-danger" target="_blank" href="'.BASE_URL.'index.php/admin/report/sales_report/new_export/'.$start_date.'/'.$end_date.'/'.$distributor_id.'" style="color: #fff;"><i class="icon-grid"></i> New</a>';

		    		$pdf_btn   = '<a class="btn btn-danger" target="_blank" href="'.BASE_URL.'index.php/admin/report/sales_report/pdf_print/'.$start_date.'/'.$end_date.'/'.$distributor_id.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

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
					'ref_id' => 0,
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
				$data['page_temp']    = $this->load->view('admin/report/sales_report',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "sales_report";
				$this->bassthaya->load_Managers_form_template($data);
        	}
        }

        // public function sales_return($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        // {
		// 	if ($this->session->userdata('random_value') == '')
        // 	redirect(base_url() . 'index.php?login', 'refresh');

        // 	$method = $this->input->post('method');	

        // 	if($method == '_getSalesReturnData')
        // 	{
        // 		$start_date = $this->input->post('start_date');
		// 	    $end_date   = $this->input->post('end_date');
		// 	    $dis_id     = $this->input->post('distributor_id');

		// 	    $sales_whr = array(
		// 	    	'start_date'     => date('Y-m-d', strtotime($start_date)),
		// 	    	'end_date'       => date('Y-m-d', strtotime($end_date)),
		// 	    	'distributor_id' => $dis_id,
		// 	    	'method'         => '_overallSalesReturnReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/sales_report',$sales_whr);

		// 	    if($data_list['status'] == 1)
		// 	    {
		// 	    	$html     = '';
		//     		$data_val = $data_list['data'];

		//     		$num = 1;
		//     		foreach ($data_val as $key => $val) {
		//     			$return_no      = !empty($val['return_no'])?$val['return_no']:'';
		// 			    $dis_name       = !empty($val['distributor_name'])?$val['distributor_name']:'';
		// 			    $invoice_no     = !empty($val['invoice_no'])?$val['invoice_no']:'';
		// 			    $invoice_random = !empty($val['invoice_random'])?$val['invoice_random']:'';
		// 			    $return_value   = !empty($val['return_value'])?$val['return_value']:'';
		// 			    $return_random  = !empty($val['return_random'])?$val['return_random']:'';
		// 			    $return_date    = !empty($val['return_date'])?$val['return_date']:'';

		// 			    $html .= '
		// 	            	<tr>
        //                         <td>'.$num.'</td>
        //                         <td><a target="_blank" href="'.BASE_URL.'index.php/admin/salesreturn/list_sales_return/print_invoice/'.$return_random.'">'.$return_no.'</a></td>
        //                         <td><a target="_blank" href="'.BASE_URL.'index.php/admin/distributorsorder/print_invoice/'.$invoice_random.'">'.$invoice_no.'</a></td>
        //                         <td>'.mb_strimwidth($dis_name, 0, 20, '...').'</td>
        //                         <td>'.date('d-M-Y', strtotime($return_date)).'</td>
        //                         <td>'.$return_value.'</td>
        //                     </tr>
		// 	            ';

		// 	            $num++;
		//     		}

		//     		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/sales_return/excel_print/'.$start_date.'/'.$end_date.'/'.$dis_id.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		//     		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/sales_return/pdf_print/'.$start_date.'/'.$end_date.'/'.$dis_id.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		//     		$response['status']    = 1;
		// 	        $response['message']   = $data_list['message']; 
		// 	        $response['data']      = $html;
		// 	        $response['tally_btn'] = $excel_btn;
		// 	        $response['pdf_btn']   = $pdf_btn;
		// 	        $response['error']     = []; 
		// 	        echo json_encode($response);
		// 	        return;
		//     	}
		//     	else
		//     	{
		//     		$response['status']  = 0;
		// 	        $response['message'] = $data_list['message']; 
		// 	        $response['data']    = [];
		// 	        $response['error']   = []; 
		// 	        echo json_encode($response);
		// 	        return;
		//     	}
        // 	}  

        // 	if($param1 == 'excel_print')
        // 	{
        // 		$start_date = $param2; 
        // 		$end_date   = $param3;
        // 		$dis_id     = $param4;

        // 		$sales_whr = array(
		// 	    	'start_date'     => date('Y-m-d', strtotime($start_date)),
		// 	    	'end_date'       => date('Y-m-d', strtotime($end_date)),
		// 	    	'distributor_id' => $dis_id,
		// 	    	'method'         => '_overallSalesReturnDetails',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/sales_report',$sales_whr);

		// 	    header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=sales_return_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('Credit note No', 'Inv Date', 'Original Inv No', 'Voucher Date', 'Pty_Name', 'Vch_Type', 'GSTIN', 'StateOfSupply', 'Product_Name', 'HSNCode', 'Qty', 'UOM', 'TaxPer', 'TaxableAmt', 'IGSTAmt', 'SGSTAmt', 'CGSTAmt', 'Cess', 'OtherAmt', 'Net_Amt', 'Narration', 'Discount'));

		// 	    if($data_list['status'] == 1)
		// 	    {
		// 	    	$data_val   = $data_list['data'];	
		// 	    	$totQty     = 0;
		//     		$totTaxable = 0;
		//     		$totIgstAmt = 0;
		//     		$totSgstAmt = 0;
		//     		$totCgstAmt = 0;
		//     		$totNetAmt  = 0;

		//     		foreach ($data_val as $key => $val) {

		//     			$return_no   = !empty($val['return_no'])?$val['return_no']:'';
	    //                 $return_date = !empty($val['return_date'])?$val['return_date']:'';
	    //                 $inv_no      = !empty($val['inv_no'])?$val['inv_no']:'';
	    //                 $inv_date    = !empty($val['inv_date'])?$val['inv_date']:'';
	    //                 $dis_name    = !empty($val['dis_name'])?$val['dis_name']:'';
	    //                 $gst_number  = !empty($val['gst_number'])?$val['gst_number']:'';
	    //                 $adm_state   = !empty($val['adm_state'])?$val['adm_state']:'';
	    //                 $state_id    = !empty($val['state_id'])?$val['state_id']:'';
	    //                 $dis_state   = !empty($val['dis_state'])?$val['dis_state']:'';
	    //                 $description = !empty($val['pdt_desc'])?$val['pdt_desc']:'';
	    //                 $hsn_code    = !empty($val['hsn_code'])?$val['hsn_code']:'';
	    //                 $pdt_qty     = !empty($val['return_qty'])?$val['return_qty']:'0';
	    //                 $gst_value   = !empty($val['gst_val'])?$val['gst_val']:'0';
	    //                 $pdt_price   = !empty($val['price_val'])?$val['price_val']:'0';
	    //                 $discount    = 0;

	    //                 $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
        //                 $price_val  = $pdt_price - $gst_data;
        //                 $pdt_gst    = $pdt_qty * $gst_data;
        //                 $TaxableAmt = $pdt_qty * $price_val;
        //                 $tot_price  = $pdt_qty * $pdt_price;
        //                 $pdt_value  = round($tot_price);
        //                 $total_dis  = $pdt_value * $discount / 100;
        //                 $total_val  = $pdt_value;

        //                 if($adm_state == $state_id)
        //                 {
        //                 	$gst_res  = $pdt_gst / 2;
        //                 	$sgst_val = number_format((float)$gst_res, 2, '.', '');
        //                 	$cgst_val = number_format((float)$gst_res, 2, '.', '');
        //                 	$igst_val = '0';
        //                 	$vch_type = 'Credit Note';
        //                 }
        //                 else
        //                 {
        //                 	$sgst_val = '0';
        //                 	$cgst_val = '0';
        //                 	$igst_val = number_format((float)$pdt_gst, 2, '.', '');
        //                 	$vch_type = 'Credit Note';
        //                 }

        //                 $totQty     += $pdt_qty;
		// 	    		$totTaxable += $TaxableAmt;
		// 	    		$totIgstAmt += $igst_val;
		// 	    		$totSgstAmt += $sgst_val;
		// 	    		$totCgstAmt += $cgst_val;
		// 	    		$totNetAmt  += $total_val;

		// 	    		$num = array(
		// 	    			$return_no,
		// 	    			date('d-m-Y', strtotime($return_date)),
		// 	    			$inv_no,
		// 	    			date('d-m-Y', strtotime($inv_date)),
		// 	    			$dis_name,
		// 	    			$vch_type,
		// 	    			$gst_number,
		// 	    			$dis_state,
		// 	    			$description,
		// 	    			$hsn_code,
		// 	    			$pdt_qty,
		// 	    			'Nos',
		// 	    			$gst_value,
		// 	    			number_format((float)$TaxableAmt, 2, '.', ''),
		// 	    			$igst_val,
		// 	    			$cgst_val,
		// 	    			$sgst_val,
		// 	    			'0',
		// 	    			'0',
		// 	    			number_format((float)$total_val, 2, '.', ''),
		// 	    			'',
		// 	    			'0',
		// 	    		);

		// 	    		fputcsv($output, $num);
		//     		}

		//     		fputcsv($output, array('', '', '', '', '', '', '', '', '', '', $totQty, '', '', number_format((float)$totTaxable, 2, '.', ''), number_format((float)$totIgstAmt, 2, '.', ''), number_format((float)$totSgstAmt, 2, '.', ''), number_format((float)$totCgstAmt, 2, '.', ''), '0', '0', number_format((float)$totNetAmt, 2, '.', ''), '', '', '', '', ''));
		// 	    }

		// 	    fclose($output);
      	// 		exit();
        // 	}
        	
        // 	else
        // 	{
        // 		$where_1 = array(
		// 			'ref_id' => 0,
        //     		'method' => '_listOverallDistributors',
        //     	);

        //     	$distributor_list = avul_call(API_URL.'distributors/api/distributors',$where_1);

		//     	$page['method']       = '_getSalesReturnData';
		//     	$page['dis_val']      = $distributor_list['data'];
		// 		$page['main_heading'] = "Report";
		// 		$page['sub_heading']  = "Report";
		// 		$page['pre_title']    = "Sales Return Report";
		// 		$page['page_title']   = "Sales Return Report";
		// 		$page['pre_menu']     = "";
		// 		$data['page_temp']    = $this->load->view('admin/report/sales_return',$page,TRUE);
		// 		$data['view_file']    = "Page_Template";
		// 		$data['currentmenu']  = "sales_return";
		// 		$this->bassthaya->load_admin_form_template($data);
        // 	}      	
        // }

        // public function expense_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        // {
        // 	if ($this->session->userdata('random_value') == '')
        // 	redirect(base_url() . 'index.php?login', 'refresh');

        // 	$method = $this->input->post('method');	

        // 	if($method == '_getExpenseData')
        // 	{
        // 		$error = FALSE;
        // 		$start_date  = $this->input->post('start_date');
		// 	    $end_date    = $this->input->post('end_date');

		// 	    $required = array('start_date', 'end_date');
		// 		foreach ($required as $field) 
		// 	    {
		// 	        if(empty($this->input->post($field)))
		// 	        {
		// 	            $error = TRUE;
		// 	        }
		// 	    }

		// 	    if($error == TRUE)
		// 	    {
		// 	    	$response['status']  = 0;
		// 	        $response['message'] = "Please fill all required fields"; 
		// 	        $response['data']    = [];
		// 	        $response['error']   = []; 
		// 	        echo json_encode($response);
		// 	        return;
		// 	    }
		// 	    else
		// 	    {
		// 	    	$expense_whr = array(
		// 		    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 				'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 		    	'method'     => '_expenseReport',
		// 		    );

		// 		    $data_list  = avul_call(API_URL.'report/api/expense_report', $expense_whr);

		// 		    if($data_list['status'] == 1)
		// 		    {
		// 		    	$html     = '';
		// 	    		$data_val = $data_list['data'];

		// 	    		$num = 1;
		// 	    		foreach ($data_val as $key => $val) {
			    				
		// 	    			$employee_name = !empty($val['employee_name'])?$val['employee_name']:'Overall';
		// 				    $expense_name  = !empty($val['expense_name'])?$val['expense_name']:'---';
		// 				    $expense_date  = !empty($val['expense_date'])?$val['expense_date']:'';
		// 				    $expense_val   = !empty($val['expense_val'])?$val['expense_val']:'0';

		// 				    $html .= '
		// 		            	<tr>
	    //                             <td>'.$num.'</td>
	    //                             <td>'.mb_strimwidth($employee_name, 0, 30, '...').'</td>
	    //                             <td>'.mb_strimwidth($expense_name, 0, 30, '...').'</td>
	    //                             <td>'.$expense_date.'</td>
	    //                             <td>'.$expense_val.'</td>
	    //                         </tr>
		// 		            ';

		// 		            $num++;
		// 	    		}

		// 	    		$tally_btn = '<a class="btn btn-warning m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/expense_report/tally_export/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Excel Export</a>';

		// 	    		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/expense_report/pdf_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		// 	    		$response['status']    = 1;
		// 		        $response['message']   = $data_list['message']; 
		// 		        $response['data']      = $html;
		// 		        $response['excel_btn'] = $tally_btn;
		// 		        $response['pdf_btn']   = $pdf_btn;
		// 		        $response['error']     = []; 
		// 		        echo json_encode($response);
		// 		        return;
		// 	    	}
		// 	    }
        // 	}

        // 	if($param1 == 'tally_export')
        // 	{
        // 		$start_date  = $param2; 
        // 		$end_date    = $param3;

        // 		$expense_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'method'     => '_expenseReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/expense_report', $expense_whr);

		// 	    header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=expense_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('Employee Name', 'Expense Name', 'Date', 'Value', 'Details'));

		// 	    if($data_list['status'] == 1)
		// 	    {
		// 	    	$data_val = $data_list['data'];	

		//     		foreach ($data_val as $key => $val) {
		//     			$employee_name = !empty($val['employee_name'])?$val['employee_name']:'Overall';
		// 			    $expense_name  = !empty($val['expense_name'])?$val['expense_name']:'---';
		// 			    $expense_date  = !empty($val['expense_date'])?$val['expense_date']:'';
		// 			    $expense_val   = !empty($val['expense_val'])?$val['expense_val']:'0';
		// 			    $expense_desc  = !empty($val['expense_desc'])?$val['expense_desc']:'';

		// 			    $num = array(
		// 	            	$employee_name,
		// 	            	$expense_name,
		// 	            	$expense_date,
		// 	            	$expense_val,
		// 	            	$expense_desc,
		// 	            );

		// 	            fputcsv($output, $num);  
		//     		}
		// 	    }

		// 	    fclose($output);
      	// 		exit();
        // 	}

        // 	else
        // 	{
		//     	$page['method']       = '_getExpenseData';
		// 		$page['main_heading'] = "Report";
		// 		$page['sub_heading']  = "Report";
		// 		$page['pre_title']    = "Expense Report";
		// 		$page['page_title']   = "Expense Report";
		// 		$page['pre_menu']     = "";
		// 		$data['page_temp']    = $this->load->view('admin/report/expense_entry',$page,TRUE);
		// 		$data['view_file']    = "Page_Template";
		// 		$data['currentmenu']  = "expense_report";
		// 		$this->bassthaya->load_admin_form_template($data);
        // 	}
        // }

        // public function stock_entry_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
		// {
		// 	if ($this->session->userdata('random_value') == '')
        // 	redirect(base_url() . 'index.php?login', 'refresh');

        // 	$method = $this->input->post('method');

        // 	if($method == '_getStockData')
        // 	{
        // 		$start_date  = $this->input->post('start_date');
		// 	    $end_date    = $this->input->post('end_date');

		// 	    $stock_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'method'     => '_adminStockEntryReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/stock_entry_report',$stock_whr);
		    	
		//     	if($data_list['status'] == 1)
		//     	{
		//     		$html     = '';
		//     		$data_val = $data_list['data'];

		//     		$num = 1;
		//     		foreach ($data_val as $key => $val) {

		//     			$description = !empty($val['description'])?$val['description']:'';
	    //                 $stock_val   = !empty($val['stock_val'])?$val['stock_val']:'0';
	    //                 $damage_val  = !empty($val['damage_val'])?$val['damage_val']:'0';
	    //                 $expiry_val  = !empty($val['expiry_val'])?$val['expiry_val']:'0';
	    //                 $entry_date  = !empty($val['entry_date'])?$val['entry_date']:'';

		// 				$html .= '
		// 	            	<tr>
        //                         <td>'.$num.'</td>
        //                         <td>'.mb_strimwidth($description, 0, 30, '...').'</td>
        //                         <td>'.$entry_date.'</td>
        //                         <td>'.$stock_val.'</td>
        //                         <td>'.$damage_val.'</td>
        //                         <td>'.$expiry_val.'</td>
        //                     </tr>
		// 	            ';

		// 	            $num++;
		//     		}		

		//     		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/stock_entry_report/excel_export/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="icon-grid"></i> Excel Export</a>';

		//     		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/stock_entry_report/pdf_print/'.$start_date.'/'.$end_date.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		//     		$response['status']    = 1;
		// 	        $response['message']   = $data_list['message']; 
		// 	        $response['data']      = $html;
		// 	        $response['excel_btn'] = $excel_btn;
		// 	        $response['pdf_btn']   = $pdf_btn;
		// 	        $response['error']     = []; 
		// 	        echo json_encode($response);
		// 	        return;
		//     	}
		//     	else
		//     	{
		//     		$response['status']  = 0;
		// 	        $response['message'] = $data_list['message']; 
		// 	        $response['data']    = [];
		// 	        echo json_encode($response);
		// 	        return;
		//     	}
        // 	}

        // 	if($param1 == 'excel_export')
        // 	{
        // 		$start_date  = $param2; 
        // 		$end_date    = $param3;

        // 		$stock_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'method'     => '_adminStockEntryReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/stock_entry_report',$stock_whr);
		    		
		//     	header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=stock_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('Description', 'Date', 'Stock Value', 'Damage Value', 'Expiry Value'));

		//     	if($data_list['status'] == 1)
		//     	{
		//     		$data_val = $data_list['data'];	

		//     		foreach ($data_val as $key => $val) {
		//     			$description = !empty($val['description'])?$val['description']:'';
	    //                 $stock_val   = !empty($val['stock_val'])?$val['stock_val']:'0';
	    //                 $damage_val  = !empty($val['damage_val'])?$val['damage_val']:'0';
	    //                 $expiry_val  = !empty($val['expiry_val'])?$val['expiry_val']:'0';
	    //                 $entry_date  = !empty($val['entry_date'])?$val['entry_date']:'';

	    //                 $num = array(
		// 	            	$description,
		// 	            	$entry_date,
		// 	            	$stock_val,
		// 	            	$damage_val,
		// 	            	$expiry_val,
		// 	            );

		// 	            fputcsv($output, $num); 
		//     		}
		//     	}

		//     	fclose($output);
      	// 		exit();
        // 	}

        // 	else
        // 	{
		//     	$page['method']       = '_getStockData';
		// 		$page['main_heading'] = "Report";
		// 		$page['sub_heading']  = "Report";
		// 		$page['pre_title']    = "Stock Entry Report";
		// 		$page['page_title']   = "Stock Entry Report";
		// 		$page['pre_menu']     = "";
		// 		$data['page_temp']    = $this->load->view('admin/report/stock_entry_report',$page,TRUE);
		// 		$data['view_file']    = "Page_Template";
		// 		$data['currentmenu']  = "stock_entry_report";
		// 		$this->bassthaya->load_admin_form_template($data);
        // 	}
		// }

		// public function outlet_invoice($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        // {
    	// 	if ($this->session->userdata('random_value') == '')
        // 	redirect(base_url() . 'index.php?login', 'refresh');

        // 	$method = $this->input->post('method');	

        // 	if($method == '_getOutletInvoiceData')
        // 	{
        // 		$dis_id     = $this->input->post('distributor_id');
        // 		$start_date = $this->input->post('start_date');
        // 		$end_date   = $this->input->post('end_date');
        // 		$view_type  = $this->input->post('view_type');

        // 		$outlet_inv_whr = array(
        // 			'distributor_id' => $dis_id,
		// 	    	'start_date'     => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'       => date('Y-m-d', strtotime($end_date)),
		// 	    	'view_type'      => $view_type,
		// 	    	'method'         => '_getOutletOverallReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/outlet_report',$outlet_inv_whr);

		// 	    if($data_list['status'] == 1)
		// 	    {
		// 	    	$html     = '';
		//     		$data_val = $data_list['data'];
		//     		$num      = 1;

		//     		foreach ($data_val as $key => $value) {
		// 			    $dis_username = !empty($value['dis_username'])?$value['dis_username']:'';
		// 			    $invoice_no   = !empty($value['invoice_no'])?$value['invoice_no']:'0';
		// 			    $order_no     = !empty($value['order_no'])?$value['order_no']:'0';
		// 			    $emp_name     = !empty($value['emp_name'])?$value['emp_name']:'0';
		// 			    $str_name     = !empty($value['str_name'])?$value['str_name']:'0';
		// 			    $invoice_date = !empty($value['invoice_date'])?$value['invoice_date']:'';

		// 			    $html .= '
		// 	            	<tr>
        //                         <td>'.$num.'</td>
        //                         <td>'.mb_strimwidth($dis_username, 0, 15, '...').'</td>
        //                         <td>'.$invoice_no.'</td>
        //                         <td>'.$order_no.'</td>
        //                         <td>'.mb_strimwidth($emp_name, 0, 10, '...').'</td>
        //                         <td>'.mb_strimwidth($str_name, 0, 15, '...').'</td>
        //                         <td>'.date('d-M-Y', strtotime($invoice_date)).'</td>
        //                     </tr>
		// 	            ';

		// 	            $num++;
		//     		}

		//     		$sale_btn = '<a class="btn btn-success" target="_blank" href="'.BASE_URL.'index.php/admin/report/outlet_invoice/sales_print/'.$start_date.'/'.$end_date.'/'.$view_type.'/'.$dis_id.'" style="color: #fff;"><i class="ft-shopping-cart"></i> Excel</a>';

		//     		$cancel_btn = '<a class="btn btn-danger" target="_blank" href="'.BASE_URL.'index.php/admin/report/outlet_invoice/cancel_print/'.$start_date.'/'.$end_date.'/'.$view_type.'/'.$dis_id.'" style="color: #fff;"><i class="ft-trash-2"></i> Cancel Invoice</a>';

		//     		$inv_btn = '<a class="btn btn-info" target="_blank" href="'.BASE_URL.'index.php/admin/report/outlet_invoice/invoice_print/'.$start_date.'/'.$end_date.'/'.$view_type.'/'.$dis_id.'" style="color: #fff;"><i class="icon-notebook"></i> Invoice</a>';

		//     		$com_btn = '<a class="btn btn-warning" target="_blank" href="'.BASE_URL.'index.php/admin/report/outlet_invoice/commission_print/'.$start_date.'/'.$end_date.'/'.$view_type.'/'.$dis_id.'" style="color: #fff;"><i class="icon-cloud-download"></i> Commission</a>';

		//     		$new_btn = '<a class="btn btn-success" target="_blank" href="'.BASE_URL.'index.php/admin/report/outlet_invoice/new_print/'.$start_date.'/'.$end_date.'/'.$view_type.'/'.$dis_id.'" style="color: #fff;"><i class="ft-file-text"></i> New</a>';

		//     		$pdf_btn = '<a class="btn btn-danger" target="_blank" href="'.BASE_URL.'index.php/admin/report/outlet_invoice/pdf_print/'.$start_date.'/'.$end_date.'/'.$view_type.'/'.$dis_id.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		//     		$delivery_btn = '<a class="btn btn-info" target="_blank" href="'.BASE_URL.'index.php/admin/report/outlet_invoice/delivery_print/'.$start_date.'/'.$end_date.'/'.$view_type.'/'.$dis_id.'" style="color: #fff;"><i class="ft-file-text"></i> Delivery</a>';

		//     		$response['status']         = 1;
		// 	        $response['message']        = $data_list['message']; 
		// 	        $response['data']           = $html;
		// 	        $response['excel_btn']      = $sale_btn;
		// 	        $response['cancel_btn']     = $cancel_btn;
		// 	        $response['invoice_btn']    = $inv_btn;
		// 	        $response['commission_btn'] = $com_btn;
		// 	        $response['new_btn']        = $new_btn;
		// 	        $response['pdf_btn']        = $pdf_btn;
		// 	        $response['delivery_btn']   = $delivery_btn;
		// 	        $response['error']          = []; 
		// 	        echo json_encode($response);
		// 	        return;
		// 	    }
		// 	    else
		//     	{
		//     		$response['status']  = 0;
		// 	        $response['message'] = $data_list['message']; 
		// 	        $response['data']    = [];
		// 	        $response['error']   = []; 
		// 	        echo json_encode($response);
		// 	        return;
		//     	}
        // 	}

        // 	if($param1 == 'sales_print')
        // 	{
        // 		$start_date = $param2; 
        // 		$end_date   = $param3;
        // 		$view_type  = $param4;
        // 		$dis_id     = $param5;

        // 		$outlet_whr = array(
		// 	    	'start_date'     => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'       => date('Y-m-d', strtotime($end_date)),
		// 			'distributor_id' => $dis_id,
		// 	    	'view_type'      => '1',
		// 	    	'method'         => '_getOutletOverallReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/outlet_report',$outlet_whr);

		// 	    header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=tally_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('DistributorName', 'InvNo', 'Inv_Dt', 'Pty_Name', 'Vch_Type', 'GSTIN', 'StateOfSupply', 'Product_Name', 'HSNCode', 'Qty', 'UOM', 'TaxPer', 'TaxableAmt', 'IGSTAmt', 'SGSTAmt', 'CGSTAmt', 'Cess', 'OtherAmt', 'Discount', 'Net_Amt', 'Narration', 'Group', 'Address', 'Country', 'St. Group', 'Salees Ledger'));

		//     	if($data_list['status'] == 1)
		//     	{
		//     		$data_val = $data_list['data'];	

		//     		foreach ($data_val as $key => $val) {

		//     			$dis_username = !empty($val['dis_username'])?$val['dis_username']:'';
		//     			$dis_state_id = !empty($val['dis_state_id'])?$val['dis_state_id']:'';
		//     			$invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
		// 	            $order_no     = !empty($val['order_no'])?$val['order_no']:'';
		// 	            $emp_name     = !empty($val['emp_name'])?$val['emp_name']:'';
		// 	            $str_name     = !empty($val['str_name'])?$val['str_name']:'';
		// 	            $mobile       = !empty($val['mobile'])?$val['mobile']:'';
		// 	            $gst_no       = !empty($val['gst_no'])?$val['gst_no']:'';
		// 	            $address      = !empty($val['address'])?$val['address']:'';
		// 	            $state_id     = !empty($val['state_id'])?$val['state_id']:'';
		// 	            $state_name   = !empty($val['state_name'])?$val['state_name']:'';
		// 	            $gst_code     = !empty($val['gst_code'])?$val['gst_code']:'';
		// 	            $due_days     = !empty($val['due_days'])?$val['due_days']:'';
		// 	            $invoice_date = !empty($val['invoice_date'])?$val['invoice_date']:'';
		// 	            $discount     = !empty($val['discount'])?$val['discount']:'0';
		// 	            $description  = !empty($val['description'])?$val['description']:'';
		// 	            $hsn_code     = !empty($val['hsn_code'])?$val['hsn_code']:'';
		// 	            $gst_value    = !empty($val['gst_val'])?$val['gst_val']:'0';
		// 	            $pdt_price    = !empty($val['price'])?number_format((float)$val['price'], 2, '.', ''):'0';
		// 	            $pdt_qty      = !empty($val['order_qty'])?$val['order_qty']:'0';

		// 	            $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
        //                 $price_val  = $pdt_price - $gst_data;
        //                 $pdt_gst    = $pdt_qty * $gst_data;
        //                 $TaxableAmt = $pdt_qty * $price_val;
        //                 $tot_price  = $pdt_qty * $pdt_price;
        //                 $pdt_value  = round($tot_price);
        //                 if($discount != 0)
        //                 {
        //                 	$total_dis  = $pdt_value * $discount / 100;
        //                 }
        //                 else
        //                 {
        //                 	$total_dis = 0;	
        //                 }
        //                 $total_val  = $pdt_value - $total_dis;

        //                 if($dis_state_id == $state_id)
        //                 {
        //                 	$gst_res  = $pdt_gst / 2;
        //                 	$sgst_val = number_format((float)$gst_res, 2, '.', '');
        //                 	$cgst_val = number_format((float)$gst_res, 2, '.', '');
        //                 	$igst_val = '0';
        //                 	$vch_type = 'Local Sales';
        //                 }
        //                 else
        //                 {
        //                 	$sgst_val = '0';
        //                 	$cgst_val = '0';
        //                 	$igst_val = number_format((float)$pdt_gst, 2, '.', '');
        //                 	$vch_type = 'Inter Sales';
        //                 }

		// 	            $num = array(
		// 	            	$dis_username,
		// 	            	$invoice_no,
		// 	            	date('d-m-Y', strtotime($invoice_date)),
		// 	            	$str_name,
		// 	            	$vch_type,
		// 	            	$gst_no,
		// 	            	$state_name,
		// 	            	$description,
		// 	            	$hsn_code,
		// 	            	$pdt_qty,
		// 	            	'NOS',
		// 	            	$gst_value,
		// 	            	number_format((float)$TaxableAmt, 2, '.', ''),
		// 	            	$igst_val,
		// 	            	$sgst_val,
		// 	            	$cgst_val,
		// 	            	'0',
		// 	            	'0',
		// 	            	number_format((float)$total_dis, 2, '.', ''),
		// 	            	number_format((float)$total_val, 2, '.', ''),
		// 	            	'',
		// 	            	'Sundry Debtors',
		// 	            	$address,
		// 	            	'India',
		// 	            	'',
		// 	            	'Sales'
		// 	            );

		// 	            fputcsv($output, $num);  
		//     		}
		//     	}

		//     	fclose($output);
      	// 		exit();
        // 	}

        // 	if($param1 == 'cancel_print')
        // 	{
        // 		$start_date = $param2; 
        // 		$end_date   = $param3;
        // 		$view_type  = $param4;
        // 		$dis_id     = $param5;

        // 		$outlet_whr = array(
		// 	    	'start_date'     => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'       => date('Y-m-d', strtotime($end_date)),
		// 			'distributor_id' => $dis_id,
		// 	    	'view_type'      => '2',
		// 	    	'method'         => '_getOutletOverallReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/outlet_report',$outlet_whr);

		// 	    header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=cancel_invoice_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('DistributorName', 'InvNo', 'Inv_Dt', 'Pty_Name', 'Vch_Type', 'GSTIN', 'StateOfSupply', 'Product_Name', 'HSNCode', 'Qty', 'UOM', 'TaxPer', 'TaxableAmt', 'IGSTAmt', 'SGSTAmt', 'CGSTAmt', 'Cess', 'OtherAmt', 'Discount', 'Net_Amt', 'Narration', 'Group', 'Address', 'Country', 'St. Group', 'Salees Ledger'));

		//     	if($data_list['status'] == 1)
		//     	{
		//     		$data_val = $data_list['data'];	

		//     		foreach ($data_val as $key => $val) {

		//     			$dis_username = !empty($val['dis_username'])?$val['dis_username']:'';
		//     			$dis_state_id = !empty($val['dis_state_id'])?$val['dis_state_id']:'';
		//     			$invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
		// 	            $order_no     = !empty($val['order_no'])?$val['order_no']:'';
		// 	            $emp_name     = !empty($val['emp_name'])?$val['emp_name']:'';
		// 	            $str_name     = !empty($val['str_name'])?$val['str_name']:'';
		// 	            $mobile       = !empty($val['mobile'])?$val['mobile']:'';
		// 	            $gst_no       = !empty($val['gst_no'])?$val['gst_no']:'';
		// 	            $address      = !empty($val['address'])?$val['address']:'';
		// 	            $state_id     = !empty($val['state_id'])?$val['state_id']:'';
		// 	            $state_name   = !empty($val['state_name'])?$val['state_name']:'';
		// 	            $gst_code     = !empty($val['gst_code'])?$val['gst_code']:'';
		// 	            $due_days     = !empty($val['due_days'])?$val['due_days']:'';
		// 	            $invoice_date = !empty($val['invoice_date'])?$val['invoice_date']:'';
		// 	            $discount     = !empty($val['discount'])?$val['discount']:'0';
		// 	            $description  = !empty($val['description'])?$val['description']:'';
		// 	            $hsn_code     = !empty($val['hsn_code'])?$val['hsn_code']:'';
		// 	            $gst_value    = !empty($val['gst_val'])?$val['gst_val']:'0';
		// 	            $pdt_price    = !empty($val['price'])?$val['price']:'0';
		// 	            $pdt_qty      = !empty($val['order_qty'])?$val['order_qty']:'0';

		// 	            $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
        //                 $price_val  = $pdt_price - $gst_data;
        //                 $pdt_gst    = $pdt_qty * $gst_data;
        //                 $TaxableAmt = $pdt_qty * $price_val;
        //                 $tot_price  = $pdt_qty * $pdt_price;
        //                 $pdt_value  = round($tot_price);

        //                 if($discount != 0)
        //                 {
        //                 	$total_dis  = $pdt_value * $discount / 100;
        //                 }
        //                 else
        //                 {
        //                 	$total_dis = 0;	
        //                 }

        //                 $total_val  = $pdt_value - $total_dis;

        //                 if($dis_state_id == $state_id)
        //                 {
        //                 	$gst_res  = $pdt_gst / 2;
        //                 	$sgst_val = number_format((float)$gst_res, 2, '.', '');
        //                 	$cgst_val = number_format((float)$gst_res, 2, '.', '');
        //                 	$igst_val = '0';
        //                 	$vch_type = 'Local Sales';
        //                 }
        //                 else
        //                 {
        //                 	$sgst_val = '0';
        //                 	$cgst_val = '0';
        //                 	$igst_val = number_format((float)$pdt_gst, 2, '.', '');
        //                 	$vch_type = 'Inter Sales';
        //                 }

		// 	            $num = array(
		// 	            	$dis_username,
		// 	            	$invoice_no,
		// 	            	date('d-m-Y', strtotime($invoice_date)),
		// 	            	$str_name,
		// 	            	$vch_type,
		// 	            	$gst_no,
		// 	            	$state_name,
		// 	            	$description,
		// 	            	$hsn_code,
		// 	            	$pdt_qty,
		// 	            	'NOS',
		// 	            	$gst_value,
		// 	            	number_format((float)$TaxableAmt, 2, '.', ''),
		// 	            	$igst_val,
		// 	            	$sgst_val,
		// 	            	$cgst_val,
		// 	            	'0',
		// 	            	'0',
		// 	            	number_format((float)$total_dis, 2, '.', ''),
		// 	            	number_format((float)$total_val, 2, '.', ''),
		// 	            	'',
		// 	            	'Sundry Debtors',
		// 	            	$address,
		// 	            	'India',
		// 	            	'',
		// 	            	'Sales'
		// 	            );

		// 	            fputcsv($output, $num);  
		//     		}
		//     	}

		//     	fclose($output);
      	// 		exit();
        // 	}

        // 	if($param1 == 'invoice_print')
        // 	{
        // 		$start_date  = $param2; 
        // 		$end_date    = $param3;
        // 		$dis_id      = $param5;

        // 		$outlet_whr = array(
		// 	    	'start_date'     => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'       => date('Y-m-d', strtotime($end_date)),
		// 			'distributor_id' => $dis_id,
		// 	    	'view_type'      => '3',
		// 	    	'method'         => '_getOutletOverallReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/outlet_report',$outlet_whr);

        // 		header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=invoice_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('DistributorName', 'InvNo', 'Inv_Dt', 'Pty_Name', 'BDE', 'Beat', 'Vch_Type', 'GSTIN', 'StateOfSupply', 'TaxableAmt', 'IGSTAmt', 'SGSTAmt', 'CGSTAmt', 'Cess', 'OtherAmt', 'Discount', 'Round_Amt', 'Net_Amt', 'Narration', 'Group', 'Address', 'Country', 'St. Group', 'Salees Ledger'));
			    
        // 		if($data_list['status'] == 1)
		//     	{
		//     		$data_val = $data_list['data'];	

		//     		foreach ($data_val as $key => $val) {

		//     			$dis_username = !empty($val['dis_username'])?$val['dis_username']:'';
	    //                 $dis_state_id = !empty($val['dis_state_id'])?$val['dis_state_id']:'';
	    //                 $invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
	    //                 $order_no     = !empty($val['order_no'])?$val['order_no']:'';
	    //                 $emp_name     = !empty($val['emp_name'])?$val['emp_name']:'';
	    //                 $str_name     = !empty($val['str_name'])?$val['str_name']:'';
	    //                 $mobile       = !empty($val['mobile'])?$val['mobile']:'';
	    //                 $gst_no       = !empty($val['gst_no'])?$val['gst_no']:'';
	    //                 $address      = !empty($val['address'])?$val['address']:'';
	    //                 $state_id     = !empty($val['state_id'])?$val['state_id']:'';
	    //                 $state_name   = !empty($val['state_name'])?$val['state_name']:'';
	    //                 $beat_name    = !empty($val['beat_name'])?$val['beat_name']:'';
	    //                 $gst_code     = !empty($val['gst_code'])?$val['gst_code']:'';
	    //                 $due_days     = !empty($val['due_days'])?$val['due_days']:'';
	    //                 $invoice_date = !empty($val['invoice_date'])?$val['invoice_date']:'';
	    //                 $discount     = !empty($val['discount'])?$val['discount']:'0';
	    //                 $random_val   = !empty($val['random_val'])?$val['random_val']:'';
	    //                 $invData_list = !empty($val['invData_list'])?$val['invData_list']:'';

	    //                 $sub_tot = 0;
	    //                 $tot_gst = 0;
	    //                 $net_tot = 0;
	    //                 if($invData_list)
	    //                 {
	    //                 	foreach ($invData_list as $key => $val_1) {
	    //                 		$gst_value = !empty($val_1['gst_val'])?$val_1['gst_val']:'0';
        //                         $pdt_price = !empty($val_1['price_val'])?number_format((float)$val_1['price_val'], 2, '.', ''):'0';
        //                         $pdt_qty   = !empty($val_1['order_qty'])?$val_1['order_qty']:'0';

        //                         $gst_data  = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
	    //                         $price_val = $pdt_price - $gst_data;
	    //                         $tot_price = $pdt_qty * $price_val;
	    //                         $sub_tot  += $tot_price;
        //                         $gst_val   = $pdt_qty * $gst_data;
        //                     	$tot_gst  += $gst_val;

        //                         $total_val = $pdt_qty * $pdt_price;
        //                     	$net_tot  += $total_val;
	    //                 	}
	    //                 }

	    //                 if($dis_state_id == $state_id)
        //                 {
        //                 	$gst_res  = $tot_gst / 2;
        //                 	$sgst_val = number_format((float)$gst_res, 2, '.', '');
        //                 	$cgst_val = number_format((float)$gst_res, 2, '.', '');
        //                 	$igst_val = '0';
        //                 	$vch_type = 'Local Sales';
        //                 }
        //                 else
        //                 {
        //                 	$sgst_val = '0';
        //                 	$cgst_val = '0';
        //                 	$igst_val = number_format((float)$tot_gst, 2, '.', '');
        //                 	$vch_type = 'Inter Sales';
        //                 }

        //                 // Round Val Details
        //                 $net_value  = round($net_tot);
        //                 if($discount != 0)
        //                 {
        //                 	$total_dis  = $net_value * $discount / 100;
        //                 }
        //                 else
        //                 {
        //                 	$total_dis = 0;	
        //                 }
        //                 $total_val  = $net_value - $total_dis;

        //                 // Round Val Details
        //                 $last_value = round($total_val);
        //                 $rond_total = $last_value - $total_val;

	    //                 $num = array(
	    //                 	$dis_username,
		// 	            	$invoice_no,
		// 	            	date('d-m-Y', strtotime($invoice_date)),
		// 	            	$str_name,
		// 	            	$emp_name,
		// 	            	$beat_name,
		// 	            	$vch_type,
		// 	            	$gst_no,
		// 	            	$state_name,
		// 	            	number_format((float)$sub_tot, 2, '.', ''),
		// 	            	$igst_val,
		// 	            	$sgst_val,
		// 	            	$cgst_val,
		// 	            	'0',
		// 	            	'0',
		// 	            	number_format((float)$total_dis, 2, '.', ''),
		// 	            	number_format((float)$rond_total, 2, '.', ''),
		// 	            	number_format((float)$last_value, 2, '.', ''),
		// 	            	'',
		// 	            	'Sundry Debtors',
		// 	            	$address,
		// 	            	'India',
		// 	            	'',
		// 	            	'Sales',
		// 	            );

		// 	            fputcsv($output, $num);
		//     		}
		//     	}

		//     	fclose($output);
      	// 		exit();
        // 	}

        // 	if($param1 == 'commission_print')
        // 	{
        // 		$start_date = $param2; 
        // 		$end_date   = $param3;
        // 		$view_type  = $param4;
        // 		$dis_id     = $param5;

        // 		$outlet_whr = array(
		// 	    	'start_date'     => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'       => date('Y-m-d', strtotime($end_date)),
		// 			'distributor_id' => $dis_id,
		// 	    	'view_type'      => '1',
		// 	    	'method'         => '_getOutletOverallReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/outlet_report',$outlet_whr);

		// 	    header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=commision_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('DistributorName', 'Invoice No', 'Order No', 'Employee Name', 'Store Name', 'Mobile', 'GSTIN', 'Address', 'GSTIN Code', 'Due Days', 'Invoice Date', 'Product Name', 'HSN Code', 'GST Value', 'Rate', 'Quantity', 'Per', 'CGST', 'SGST', 'IGST', 'Discount', 'Discount Price', 'Actual Price'));

        // 		if($data_list['status'] == 1)
		//     	{
		//     		$data_val = $data_list['data'];	

		//     		foreach ($data_val as $key => $val) {
		//     			$dis_username = !empty($val['dis_username'])?$val['dis_username']:'';
		//     			$dis_state_id = !empty($val['dis_state_id'])?$val['dis_state_id']:'';
		//     			$invoice_no   = !empty($val['invoice_no'])?$val['invoice_no']:'';
		// 	            $order_no     = !empty($val['order_no'])?$val['order_no']:'';
		// 	            $emp_name     = !empty($val['emp_name'])?$val['emp_name']:'';
		// 	            $str_name     = !empty($val['str_name'])?$val['str_name']:'';
		// 	            $mobile       = !empty($val['mobile'])?$val['mobile']:'';
		// 	            $gst_no       = !empty($val['gst_no'])?$val['gst_no']:'';
		// 	            $address      = !empty($val['address'])?$val['address']:'';
		// 	            $state_id     = !empty($val['state_id'])?$val['state_id']:'';
		// 	            $gst_code     = !empty($val['gst_code'])?$val['gst_code']:'';
		// 	            $due_days     = !empty($val['due_days'])?$val['due_days']:'';
		// 	            $invoice_date = !empty($val['invoice_date'])?$val['invoice_date']:'';
		// 	            $discount     = !empty($val['discount'])?$val['discount']:'0';
		// 	            $description  = !empty($val['description'])?$val['description']:'';
		// 	            $hsn_code     = !empty($val['hsn_code'])?$val['hsn_code']:'';
		// 	            $gst_value    = !empty($val['gst_val'])?$val['gst_val']:'0';
		// 	            $pdt_price    = !empty($val['price'])?$val['price']:'0';
		// 	            $pdt_qty      = !empty($val['order_qty'])?$val['order_qty']:'0';
		// 	            $act_price    = !empty($val['product_price'])?$val['product_price']:'0';

		// 	            $gst_data  = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
        //                 $price_val = $pdt_price - $gst_data;
        //                 $tot_price = $pdt_qty * $pdt_price;
        //                 $pdt_value = round($tot_price);
        //                 $total_dis = $pdt_value * $discount / 100;
        //                 $total_val = $pdt_value - $total_dis;
                        
        //                 $nor_total = $pdt_qty * $act_price;
        //                 $nor_price = round($nor_total);

        //                 if($dis_state_id == $state_id)
        //                 {
        //                 	$gst_value = $gst_data / 2;

        //                 	$sgst_val = number_format((float)$gst_value, 2, '.', '');
        //                 	$cgst_val = number_format((float)$gst_value, 2, '.', '');
        //                 	$igst_val = '-';
        //                 }
        //                 else
        //                 {
        //                 	$sgst_val = '-';
        //                 	$cgst_val = '-';
        //                 	$igst_val = number_format((float)$gst_data, 2, '.', '');
        //                 }

		// 	            $num = array(
		// 	            	$dis_username,
		// 	            	$invoice_no,
		// 	            	$order_no,
		// 	            	$emp_name,
		// 	            	$str_name,
		// 	            	$mobile,
		// 	            	$gst_no,
		// 	            	$address,
		// 	            	$gst_code,
		// 	            	$due_days,
		// 	            	date('d-m-Y', strtotime($invoice_date)),
		// 	            	$description,
		// 	            	$hsn_code,
		// 	            	number_format((float)$gst_value, 2, '.', ''),
		// 	            	number_format((float)$price_val, 2, '.', ''),
		// 	            	$pdt_qty,
		// 	            	'nos',
		// 	            	$sgst_val,
		// 	            	$cgst_val,
		// 	            	$igst_val,
		// 	            	number_format((float)$total_dis, 2, '.', ''),
		// 	            	number_format((float)$total_val, 2, '.', ''),
		// 	            	number_format((float)$nor_price, 2, '.', ''),
		// 	            );

		// 	            fputcsv($output, $num);
		//     		}
		//     	}

		//     	fclose($output);
      	// 		exit();
        // 	}

        // 	if($param1 == 'new_print')
        // 	{
        // 		$start_date = $param2; 
        // 		$end_date   = $param3;
        // 		$view_type  = $param4;
        // 		$dis_id     = $param5;

        // 		$outlet_whr = array(
		// 	    	'start_date'     => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'       => date('Y-m-d', strtotime($end_date)),
		// 			'distributor_id' => $dis_id,
		// 	    	'view_type'      => '4',
		// 	    	'method'         => '_getOutletOverallReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/outlet_report',$outlet_whr);

		// 	    header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=new_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('Date/ time stamp', 'SKU/ sku ID', 'Employee ID/BDE ID', 'City', 'Beat', 'Outlet/ retailer ID', 'Units', 'INR Value', 'Cost/Profit (if any)'));
			    
        // 		if($data_list['status'] == 1)
		//     	{
		//     		$data_val = $data_list['data'];	

		//     		foreach ($data_val as $key => $val) {
		//     			$invoice_date  = !empty($val['invoice_date'])?$val['invoice_date']:'';
	    //                 $product_name  = !empty($val['product_name'])?$val['product_name']:'';
	    //                 $employee_name = !empty($val['employee_name'])?$val['employee_name']:'';
	    //                 $city_name     = !empty($val['city_name'])?$val['city_name']:'';
	    //                 $beat_name     = !empty($val['beat_name'])?$val['beat_name']:'';
	    //                 $store_name    = !empty($val['store_name'])?$val['store_name']:'';
	    //                 $order_qty     = !empty($val['order_qty'])?$val['order_qty']:'';
	    //                 $invoice_value = !empty($val['invoice_value'])?$val['invoice_value']:'';

	    //                 $num = array(
	    //                 	$invoice_date,
		// 	            	$product_name,
		// 	            	$employee_name,
		// 	            	$city_name,
		// 	            	$beat_name,
		// 	            	$store_name,
		// 	            	$order_qty.' Nos',
		// 	            	number_format((float)$invoice_value, 2, '.', ''),
		// 	            	'',
		// 	            );

		// 	            fputcsv($output, $num);
		//     		}
		//     	}

		//     	fclose($output);
      	// 		exit();
        // 	}

        // 	if($param1 == 'delivery_print')
        // 	{
        // 		$start_date = $param2; 
        // 		$end_date   = $param3;
        // 		$view_type  = $param4;
        // 		$dis_id     = $param5;

        // 		$outlet_whr = array(
		// 	    	'start_date'     => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'       => date('Y-m-d', strtotime($end_date)),
		// 			'distributor_id' => $dis_id,
		// 	    	'view_type'      => '5',
		// 	    	'method'         => '_getOutletOverallReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/outlet_report',$outlet_whr);

		// 	    header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=new_delivery_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('DATE','ORDER DATE','ORDER NUM','DISTRIBUTERNAME','BDE NAME','INVOICE NUM','OUTLET NAME','REAGION','BEAT NAME','INV VALUE','DELIVERY DATE'));

		// 	    if($data_list['status'] == 1)
		// 	    {
		// 	    	$data_val = $data_list['data'];	

		// 	    	foreach ($data_val as $key => $val) {
		// 	    		$num = array(
	    //                 	empty_check($val['invoice_date']),
		// 		            empty_check($val['order_date']),
		// 		            empty_check($val['order_no']),
		// 		            empty_check($val['distributor']),
		// 		            empty_check($val['emp_name'])?$val['emp_name']:'Admin',
		// 		            empty_check($val['invoice_no']),
		// 		            empty_check($val['store_name']),
		// 		            empty_check($val['city_name']),
		// 		            empty_check($val['beat_name']),
		// 		            empty_check($val['invoice_total']),
		// 		            empty_check($val['delivery_date']),
		// 	            );

		// 	            fputcsv($output, $num);
		// 	    	}
		// 	    }

		// 	    fclose($output);
      	// 		exit();
        // 	}

        // 	else
        // 	{
        // 		$where_1 = array(
		// 			'ref_id' => 0,
        //     		'method'   => '_listOverallDistributors'
        //     	);

        //     	$distributor_list  = avul_call(API_URL.'distributors/api/distributors',$where_1);	

		//     	$page['method']          = '_getOutletInvoiceData';
		//     	$page['distributor_val'] = $distributor_list['data'];
		// 		$page['main_heading']    = "Report";
		// 		$page['sub_heading']     = "Report";
		// 		$page['pre_title']       = "Outlet Invoice Report";
		// 		$page['page_title']      = "Outlet Invoice Report";
		// 		$page['pre_menu']        = "";
		// 		$data['page_temp']       = $this->load->view('admin/report/outlet_invoice',$page,TRUE);
		// 		$data['view_file']       = "Page_Template";
		// 		$data['currentmenu']     = "outlet_invoice";
		// 		$this->bassthaya->load_admin_form_template($data);
        // 	}
    	// }

    	public function employee_target($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
    	{
        	if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method =='_getEmployeeTarget')
        	{
        		$month_id    = $this->input->post('month_id');
				$year_id     = $this->input->post('year_id');
				$employee_id = $this->input->post('employee_id');

				$target_whr  = array(
        			'month_id'    => $month_id,
        			'year_id'     => $year_id,
        			'employee_id' => $employee_id,
            		'method'      => '_employeeTargetData'
            	);

            	$data_list = avul_call(API_URL.'report/api/target_report',$target_whr);

            	if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'][0];
		    		$num      = 1;

		    		$ovr_target  = !empty($data_val['overall_target'])?$data_val['overall_target']:'';
		    		$pdt_target  = !empty($data_val['product_target'])?$data_val['product_target']:'';
		    		$beat_target = !empty($data_val['beat_target'])?$data_val['beat_target']:'';

		    		$overall_target_val  = !empty($ovr_target['overall_target_val'])?$ovr_target['overall_target_val']:'0';
		            $overall_achieve_val = !empty($ovr_target['overall_achieve_val'])?$ovr_target['overall_achieve_val']:'0';
		            $overall_achieve_per = !empty($ovr_target['overall_achieve_per'])?$ovr_target['overall_achieve_per']:'0';

		    		$html .= '
                        <div class="card-body pt-0" style="margin-top: 25px;">
                			<div class="row">
                                <div class="col-sm-12 filter-design" style="display: inherit;">
                                    <div class="col-md-4 col-12 border-right-blue-grey border-right-lighten-5 text-center">
                                        <h4 class="info text-bold-600"><span class="icon-user"></span></h4>
                                        <h4 class="font-large-2 text-bold-400">'.number_format($overall_target_val).'</h4>
                                        <p class="blue-grey lighten-2 mb-0">Overall Target Value</p>
                                    </div>
                                    <div class="col-md-4 col-12 border-right-blue-grey border-right-lighten-5 text-center">
                                        <h4 class="warning text-bold-600"><span class="icon-user-follow"></span></h4>
                                        <h4 class="font-large-2 text-bold-400">'.number_format($overall_achieve_val).'</h4>
                                        <p class="blue-grey lighten-2 mb-0">Achievement Value</p>
                                    </div>
                                    <div class="col-md-4 col-12 text-center">
                                        <h4 class="success text-bold-600"><span class="icon-user-following"></span></h4>
                                        <h4 class="font-large-2 text-bold-400">'.$overall_achieve_per.' %</h4>
                                        <p class="blue-grey lighten-2 mb-0">Achievement Percentage</p>
                                    </div>
                                </div>
                            </div>
                        </div>';

                        if(!empty($pdt_target))
                        {
                        	$html .= '
	                        	<div class="card-header">
		                            <h4 class="card-title">Product Target Details</h4>
		                        </div>
		                        <div class="col-sm-12 filter-design">
		                            <div class="table-responsive">
		                                <table class="table">
		                                    <thead>
		                                        <tr>
		                                            <th>#</th>
		                                            <th>Product Name</th>
		                                            <th>Target Value</th>
		                                            <th>Achieve Value</th>
		                                            <th>Progress</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody>';
		                                    	$pdt_num = 1;
		                                    	foreach ($pdt_target as $key => $val_1) {
								            		$description     = !empty($val_1['description'])?$val_1['description']:'0';
								                    $pdt_target_val  = !empty($val_1['pdt_target_val'])?$val_1['pdt_target_val']:'0';
								                    $pdt_achieve_val = !empty($val_1['pdt_achieve_val'])?$val_1['pdt_achieve_val']:'0';
								                    $pdt_achieve_per = !empty($val_1['pdt_achieve_per'])?$val_1['pdt_achieve_per']:'0';

								                    if(80 <= $pdt_achieve_per)
								                    {
								                    	$pdt_clr = 'success';
								                    }
								                    else if(60 <= $pdt_achieve_per)
								                    {
								                    	$pdt_clr = 'info';
								                    }
								                    else if(25 <= $pdt_achieve_per)
								                    {
								                    	$pdt_clr = 'warning';
								                    }
								                    else if(0 <= $pdt_achieve_per)
								                    {
								                    	$pdt_clr = 'danger';
								                    }

								                    $html .= '
								                    	<tr>
								                            <td>'.$pdt_num.'</td>
								                            <td>'.$description.'</td>
								                            <td>'.$pdt_target_val.'</td>
								                            <td>'.$pdt_achieve_val.'</td>
								                            <td style="padding-top: 18px;">
								                            	<div class="progress progress-sm mb-0 box-shadow-2">
								                                    <div class="progress-bar bg-gradient-x-'.$pdt_clr.'" role="progressbar" style="width: '.$pdt_achieve_per.'%" aria-valuenow="'.$pdt_achieve_per.'" aria-valuemin="0" aria-valuemax="100"></div>
								                                </div>
								                            </td>
								                        </tr>
								                    ';
	                    							$pdt_num++;
		                                    	}
		                                    $html .='</tbody>
		                                </table>
		                            </div>
		                        </div>
	                        ';
                        }

                        if(!empty($beat_target))
                        {
                        	$html .='
                        	<div class="card-header">
	                            <h4 class="card-title">Beat Target Details</h4>
	                        </div>
	                        <div class="col-sm-12 filter-design">
	                            <div class="table-responsive">
	                                <table class="table">
	                                    <thead>
	                                        <tr>
	                                            <th>#</th>
	                                            <th>Product Name</th>
	                                            <th>Target Value</th>
	                                            <th>Achieve Value</th>
	                                            <th>Progress</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody>';
	                                    $beat_num = 1;
	                                    foreach ($beat_target as $key => $val_2) {
							                $zone_name        = !empty($val_2['zone_name'])?$val_2['zone_name']:'';
						                    $beat_target_val  = !empty($val_2['beat_target_val'])?$val_2['beat_target_val']:'0';
						                    $beat_achieve_val = !empty($val_2['beat_achieve_val'])?$val_2['beat_achieve_val']:'0';
						                    $beat_achieve_per = !empty($val_2['beat_achieve_per'])?$val_2['beat_achieve_per']:'0';

						                    if(80 <= $beat_achieve_per)
						                    {
						                    	$beat_clr = 'success';
						                    }
						                    else if(60 <= $beat_achieve_per)
						                    {
						                    	$beat_clr = 'info';
						                    }
						                    else if(25 <= $beat_achieve_per)
						                    {
						                    	$beat_clr = 'warning';
						                    }
						                    else if(0 <= $beat_achieve_per)
						                    {
						                    	$beat_clr = 'danger';
						                    }

						                    $html .= '
						                    	<tr>
						                            <td>'.$beat_num.'</td>
						                            <td>'.$zone_name.'</td>
						                            <td>'.$beat_target_val.'</td>
						                            <td>'.$beat_achieve_val.'</td>
						                            <td style="padding-top: 18px;">
						                            	<div class="progress progress-sm mb-0 box-shadow-2">
						                                    <div class="progress-bar bg-gradient-x-'.$beat_clr.'" role="progressbar" style="width: '.$beat_achieve_per.'%" aria-valuenow="'.$beat_achieve_per.'" aria-valuemin="0" aria-valuemax="100"></div>
						                                </div>
						                            </td>
						                        </tr>
						                    ';

						                    $beat_num++;
	                                    }
	                                    $html .='</tbody>
	                                </table>
	                            </div>
	                        </div>';
                        }

		    		$response['status']    = 1;
			        $response['message']   = $data_list['message']; 
			        $response['data']      = $html;
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
			if($param1 == 'emp_list')
        	{
        		$designation_code  = $this->input->post('designation_code');
			   
			   
				
			    $att_whr = array(
			    	'designation_code'  => $designation_code,
					'manager_id'  => $this->session->userdata('id'),
			    	'method'      => 'gethierarchy',
			    );

			    $data_list  = avul_call(API_URL.'managers/api/hierarchy_list',$att_whr);
		    	
		    	$data_val  = $data_list['data'];
				
			
        		$option ='<option value="">Select Employee Name</option>';

        		if(!empty($data_val))
        		{
        			foreach ($data_val as $key => $value) {
        				$employee_id   = !empty($value['employee_id']) ?$value['employee_id']:'';
                        $mobile = !empty($value['mobile'])?$value['mobile']:'';
						$position_id =!empty($value['position_id'])?$value['position_id']:'';
						$name =!empty($value['name'])?$value['name']:'';

                        $select   = '';
        				

                        $option .= '<option value="'.$employee_id.'" '.$select.'>'.$name.$mobile.'</option>';
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
				$where_1 = array(
					'designation_code' => $this->session->userdata('designation_code'),
					'method'   => '_listDesignation'
				);
			

				$data_list  = avul_call(API_URL.'employee/api/employee_designation',$where_1);
				$desgination_list   = !empty($data_list['data'])?$data_list['data']:'';

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

            	$page['method']       = '_getEmployeeTarget';
            	$page['desgination_list']     = $desgination_list;
            	$page['month_list']   = $month_list;
            	$page['year_list']    = $year_list;
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Employee Target";
				$page['page_title']   = "Employee Target";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('managers/report/employee_target',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "employee_target";
				$this->bassthaya->load_Managers_form_template($data);
			}
        }

        public function employee_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
    	{
        	if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method =='_getEmployeeReport')
        	{
        		$sel_date    = $this->input->post('sel_date');
				$employee_id = $this->input->post('employee_id');

				$target_whr  = array(
        			'sel_date'    => date('Y-m-d', strtotime($sel_date)),
        			'employee_id' => $employee_id,
            		'method'      => '_dayEndReport'
            	);
				
            	$data_list = avul_call(API_URL.'login/api/employee_login',$target_whr);

            	if($data_list['status'] == 1)
			    {
			    	$html     = '';
		    		$data_val = $data_list['data'][0];
		    		$num      = 1;

		    		$beat_val     = !empty($data_val['beat'])?$data_val['beat']:'';
		            $total_outlet = !empty($data_val['total_outlet'])?$data_val['total_outlet']:'0';
		            $new_outlet   = !empty($data_val['new_outlet'])?$data_val['new_outlet']:'0';
		            $old_outlet   = !empty($data_val['old_outlet'])?$data_val['old_outlet']:'0';
		            $order_outlet = !empty($data_val['order_outlet'])?$data_val['order_outlet']:'0';
		            $order_count  = !empty($data_val['order_count'])?$data_val['order_count']:'0';
		            $order_total  = !empty($data_val['order_total'])?$data_val['order_total']:'0';
		            $outlet_list  = !empty($data_val['outlet_list'])?$data_val['outlet_list']:'';

		            $html .= '
		            	<div class="card-body pt-0" style="margin-top: 25px;">
                            <div class="row">
                                <div class="col-sm-12 filter-design" style="display: inherit;">
                                    <div class="col-md-2 col-12 border-right-blue-grey border-right-lighten-5 text-center">
                                        <h4 class="success text-bold-600"><span class="ft-bookmark"></span></h4>
                                        <h4 class="text-bold-400">'.$total_outlet.'</h4>
                                        <p class="blue-grey lighten-2 mb-0">Total Outlet</p>
                                    </div>
                                    <div class="col-md-2 col-12 border-right-blue-grey border-right-lighten-5 text-center">
                                        <h4 class="success text-bold-600"><span class="icon icon-handbag"></span></h4>
                                        <h4 class="text-bold-400">'.$new_outlet.'</h4>
                                        <p class="blue-grey lighten-2 mb-0">New Outlet</p>
                                    </div>
                                    <div class="col-md-2 col-12 border-right-blue-grey border-right-lighten-5 text-center">
                                        <h4 class="success text-bold-600"><span class="icon icon-handbag"></span></h4>
                                        <h4 class="text-bold-400">'.$old_outlet.'</h4>
                                        <p class="blue-grey lighten-2 mb-0">Old Outlet</p>
                                    </div>
                                    <div class="col-md-2 col-12 border-right-blue-grey border-right-lighten-5 text-center">
                                        <h4 class="success text-bold-600"><span class="ft-check-circle"></span></h4>
                                        <h4 class="text-bold-400">'.$order_outlet.'</h4>
                                        <p class="blue-grey lighten-2 mb-0">Order Place Shop</p>
                                    </div>
                                    <div class="col-md-2 col-12 border-right-blue-grey border-right-lighten-5 text-center">
                                        <h4 class="success text-bold-600"><span class="ft-calendar"></span></h4>
                                        <h4 class="text-bold-400">'.$order_count.'</h4>
                                        <p class="blue-grey lighten-2 mb-0">Order Count</p>
                                    </div>
                                    <div class="col-md-2 col-12 text-center">
                                        <h4 class="success text-bold-600"><span class="ft-grid"></span></h4>
                                        <h4 class="text-bold-400">'.$order_total.'</h4>
                                        <p class="blue-grey lighten-2 mb-0">Order Value</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header">
                            <h4 class="card-title">Beat Details : <b>'.$beat_val.'</b> </h4>
                        </div>
                        <div class="card-header">
                            <h4 class="card-title">Outlet Details</h4>
                        </div>
		            ';

		            if(!empty($outlet_list))
		            {
		            	$html .= '
		            		<div class="col-sm-12 filter-design">
	                            <div class="table-responsive">
	                                <table class="table">
	                                    <thead>
	                                        <tr>
	                                            <th>#</th>
	                                            <th colspan="4">Outlet Name</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody>';
	                                    	$str_num = 1;
	                                        foreach ($outlet_list as $key => $val_1) {
	                                        	$str_name   = !empty($val_1['company_name'])?$val_1['company_name']:'';
                            					$mobile_val = !empty($val_1['mobile'])?$val_1['mobile']:'';
                            					$att_list   = !empty($val_1['attendance_list'])?$val_1['attendance_list']:'';

                            					$html .= '
                            						<tr style="background-color: #f4f4f4;">
			                                            <td>'.$str_num.'</td>
			                                            <td colspan="4">'.$str_name.' - <b>Ph : </b> '.$mobile_val.'</td>
			                                        </tr>';
			                                        if($att_list)
			                                        {
			                                        	$html .= '
			                                        		<tr>
					                                            <th></th>
					                                            <th>Attendance Details</th>
					                                            <th>Order Number</th>
					                                            <th>Order Value</th>
					                                            <th>Time</th>
					                                        </tr>
			                                        	';
			                                        	foreach ($att_list as $key => $val_2) {
            		$in_time   = !empty($val_2['in_time'])?date('h:i', strtotime($val_2['in_time'])):'---';
                    $out_time  = !empty($val_2['out_time'])?date('h:i', strtotime($val_2['out_time'])):'---';
                    $att_type  = !empty($val_2['attendance_type'])?$val_2['attendance_type']:'';
                    $ord_no    = !empty($val_2['order_no'])?$val_2['order_no']:'---';
                    $ord_total = !empty($val_2['order_total'])?$val_2['order_total']:'---';
                    $reason    = !empty($val_2['reason'])?$val_2['reason']:'---';

                    $html .= '
                    	<tr>
                            <td></td>
                            <td>'.$att_type.'</td>
                            <td>'.$ord_no.'</td>
                            <td>'.$ord_total.'</td>
                            <td>'.$in_time.' - '.$out_time.'</td>
                        </tr>
                    ';
			                                        	}
			                                        }
			                                        else
			                                        {
			                                        	$html .= '
			                                        		<tr>
					                                            <td colspan="5"><div class="alert alert-danger text-center"><b>No items found...</b></div></td>
					                                        </tr>
			                                        	';
			                                        }
                            					$str_num++;
	                                        }
	                                    $html .= '</tbody>
	                                </table>
	                            </div>
	                        </div>
		            	';
		            }

		            $excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/managers/report/employee_report/excel_print/'.$sel_date.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

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
        		$select_date = $param2;

        		$target_whr  = array(
        			'sel_date'    => date('Y-m-d', strtotime($select_date)),
            		'method'      => '_overallDayEndReport'
            	);

            	$data_list = avul_call(API_URL.'login/api/employee_login',$target_whr);

            	header('Content-Type: text/csv; charset=utf-8');  
			    header('Content-Disposition: attachment; filename=employee_daily_report('.$select_date.').csv');  
			    $output = fopen("php://output", "w");   
			    fputcsv($output, array('Employee Name', 'Starting Time', 'Total Outlet', 'New Outlet', 'Old Outlet', 'Productive Outlet', 'Closing Time', 'Total Order', 'Total Value', 'Collection Outlet', 'Collection Value'));

			    if($data_list['status'] == 1)
			    {
			    	$data_val = $data_list['data'];	

		    		foreach ($data_val as $key => $val) {
		    			$emp_name       = !empty($val['emp_name'])?$val['emp_name']:'';
	                    $cur_date       = !empty($val['date'])?$val['date']:'';
	                    $start_time     = !empty($val['start_time'])?$val['start_time']:'';
	                    $close_time     = !empty($val['close_time'])?$val['close_time']:'';
	                    $total_outlet   = !empty($val['total_outlet'])?$val['total_outlet']:'0';
	                    $new_outlet     = !empty($val['new_outlet'])?$val['new_outlet']:'0';
	                    $old_outlet     = !empty($val['old_outlet'])?$val['old_outlet']:'0';
	                    $order_outlet   = !empty($val['order_outlet'])?$val['order_outlet']:'0';
	                    $order_count    = !empty($val['order_count'])?$val['order_count']:'0';
	                    $order_total    = !empty($val['order_total'])?$val['order_total']:'0';
	                    $collection_cnt = !empty($val['collection_cnt'])?$val['collection_cnt']:'0';
	                    $collection_val = !empty($val['collection_val'])?$val['collection_val']:'0';

	                    $num = array(
			            	$emp_name,
		                    $start_time,
		                    $total_outlet,
		                    $new_outlet,
		                    $old_outlet,
		                    $order_outlet,
		                    $close_time,
		                    $order_count,
		                    $order_total,
		                    $collection_cnt,
		                    $collection_val,
			            );

			            fputcsv($output, $num);  
		    		}
			    }

			    fclose($output);
      			exit();
        	}
			if($param1 == 'emp_list')
        	{
        		$designation_code  = $this->input->post('designation_code');
			   
			   
				
			    $att_whr = array(
			    	'designation_code'  => $designation_code,
					'manager_id'  => $this->session->userdata('id'),
			    	'method'      => 'gethierarchy',
			    );

			    $data_list  = avul_call(API_URL.'managers/api/hierarchy_list',$att_whr);
		    	
		    	$data_val  = $data_list['data'];
				
			
        		$option ='<option value="">Select Employee Name</option>';

        		if(!empty($data_val))
        		{
        			foreach ($data_val as $key => $value) {
        				$employee_id   = !empty($value['employee_id']) ?$value['employee_id']:'';
                        $mobile = !empty($value['mobile'])?$value['mobile']:'';
						$position_id =!empty($value['position_id'])?$value['position_id']:'';
						$name =!empty($value['name'])?$value['name']:'';

                        $select   = '';
        				

                        $option .= '<option value="'.$employee_id.'" '.$select.'>'.$name.$mobile.'</option>';
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
				$where_1 = array(
					'designation_code' => $this->session->userdata('designation_code'),
					'method'   => '_listDesignation'
				);
			

				$data_list  = avul_call(API_URL.'employee/api/employee_designation',$where_1);
				$desgination_list   = !empty($data_list['data'])?$data_list['data']:'';


            	$page['method']       = '_getEmployeeReport';
            	$page['desgination_list']     = $desgination_list;
				$page['main_heading'] = "Report";
				$page['sub_heading']  = "Report";
				$page['pre_title']    = "Employee Daily Report";
				$page['page_title']   = "Employee Daily Report";
				$page['pre_menu']     = "";
				$data['page_temp']    = $this->load->view('managers/report/employee_report',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "employee_report";
				$this->bassthaya->load_Managers_form_template($data);
			}
        }

        // public function product_order($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
        // {
        // 	if ($this->session->userdata('random_value') == '')
        // 	redirect(base_url() . 'index.php?login', 'refresh');

        // 	$method = $this->input->post('method');	

        // 	if($method == '_getProductOrder')
        // 	{
        // 		$start_date = $this->input->post('start_date');
		// 	    $end_date   = $this->input->post('end_date');

		// 	    $pdt_order_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'method'     => '_getProductOrderReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/order_report',$pdt_order_whr);	

		// 	    if($data_list['status'] == 1)
		// 	    {
		// 	    	$html     = '';
		//     		$data_val = $data_list['data'];
		//     		$num      = 1;

		//     		foreach ($data_val as $key => $val) {
	    //                 $description   = !empty($val['description'])?$val['description']:'';
	    //                 $success_count = !empty($val['success_count'])?$val['success_count']:'0';
	    //                 $packing_count = !empty($val['packing_count'])?$val['packing_count']:'0';
	    //                 $invoice_count = !empty($val['invoice_count'])?$val['invoice_count']:'0';
	    //                 $cancel_count  = !empty($val['cancel_count'])?$val['cancel_count']:'0';

	    //                 $html .= '
		// 	            	<tr>
        //                         <td>'.$num.'</td>
        //                         <td>'.mb_strimwidth($description, 0, 35, '...').'</td>
        //                         <td>'.$success_count.'</td>
        //                         <td>'.$packing_count.'</td>
        //                         <td>'.$invoice_count.'</td>
        //                         <td>'.$cancel_count.'</td>
        //                     </tr>
		// 	            ';

		// 	            $num++;
		//     		}

		//     		$sale_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/product_order/excel_print/'.$start_date.'/'.$end_date.'/" style="color: #fff;"><i class="ft-shopping-cart"></i> Excel</a>';

		//     		$pdf_btn = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/product_order/pdf_print/'.$start_date.'/'.$end_date.'/" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		//     		$response['status']      = 1;
		// 	        $response['message']     = $data_list['message']; 
		// 	        $response['data']        = $html;
		// 	        $response['excel_btn']   = $sale_btn;
		// 	        $response['pdf_btn']     = $pdf_btn;
		// 	        $response['error']       = []; 
		// 	        echo json_encode($response);
		// 	        return;
		// 	    }
		// 	    else
		//     	{
		//     		$response['status']  = 0;
		// 	        $response['message'] = $data_list['message']; 
		// 	        $response['data']    = [];
		// 	        echo json_encode($response);
		// 	        return;
		//     	}
        // 	}

        // 	if($param1 == 'excel_print')
        // 	{
        // 		$start_date = $param2; 
        // 		$end_date   = $param3;

        // 		$pdt_order_whr = array(
		// 	    	'start_date' => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'   => date('Y-m-d', strtotime($end_date)),
		// 	    	'method'     => '_getProductOrderReport',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'report/api/order_report',$pdt_order_whr);	

		// 	    header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=product_order_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('Vendor Name', 'Product Name', 'Success', 'Process', 'Packing', 'Shipping', 'Invoice', 'Delivery', 'Cancel Invoice', 'Cancel Order'));

		// 	    if($data_list['status'] == 1)
		// 	    {
		// 	    	$data_val = $data_list['data'];	

		// 	    	foreach ($data_val as $key => $val) {
		// 	    		$vendor_name    = !empty($val['vendor_name'])?$val['vendor_name']:'';
	    //                 $description    = !empty($val['description'])?$val['description']:'';
	    //                 $success_count  = !empty($val['success_count'])?$val['success_count']:'0';
	    //                 $process_count  = !empty($val['process_count'])?$val['process_count']:'0';
	    //                 $packing_count  = !empty($val['packing_count'])?$val['packing_count']:'0';
	    //                 $shipping_count = !empty($val['shipping_count'])?$val['shipping_count']:'0';
	    //                 $invoice_count  = !empty($val['invoice_count'])?$val['invoice_count']:'0';
	    //                 $delivery_count = !empty($val['delivery_count'])?$val['delivery_count']:'0';
	    //                 $canInv_count   = !empty($val['canInv_count'])?$val['canInv_count']:'0';
	    //                 $cancel_count   = !empty($val['cancel_count'])?$val['cancel_count']:'0';

	    //                 $num = array(
		// 	            	$vendor_name,
		// 	            	$description,
		// 	            	$success_count,
		// 	            	$process_count,
		// 	            	$packing_count,
		// 	            	$shipping_count,
		// 	            	$invoice_count,
		// 	            	$delivery_count,
		// 	            	$canInv_count,
		// 	            	$cancel_count,
		// 	            );

		// 	            fputcsv($output, $num);  
		// 	    	}
		// 	    }

		// 	    fclose($output);
      	// 		exit();
        // 	}

        // 	else
        // 	{
		//     	$page['method']       = '_getProductOrder';
		// 		$page['main_heading'] = "Report";
		// 		$page['sub_heading']  = "Report";
		// 		$page['pre_title']    = "Product Order Report";
		// 		$page['page_title']   = "Product Order Report";
		// 		$page['pre_menu']     = "";
		// 		$data['page_temp']    = $this->load->view('admin/report/product_order',$page,TRUE);
		// 		$data['view_file']    = "Page_Template";
		// 		$data['currentmenu']  = "product_order";
		// 		$this->bassthaya->load_admin_form_template($data);
        // 	}
        // }

        // public function backlog_report($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="", $param8="")
        // {
        // 	if ($this->session->userdata('random_value') == '')
        // 	redirect(base_url() . 'index.php?login', 'refresh');

        // 	$method = $this->input->post('method');

        // 	if($method == '_getBacklogData')
        // 	{
        // 		$start_date  = $this->input->post('start_date');
        // 		$end_date    = $this->input->post('end_date');
        // 		$state_id    = zero_check($this->input->post('state_id'));
        // 		$city_id     = zero_check($this->input->post('city_id'));
        // 		$zone_id     = zero_check($this->input->post('zone_id'));
        // 		$employee_id = zero_check($this->input->post('employee_id'));
        // 		$category_id = zero_check($this->input->post('category_id'));

        // 		$category_val = '';
        // 		$category_res = '';

        // 		if($category_id)
        // 		{
        // 			$category_val = implode(',', $category_id);
	    //     		$category_res = implode('_', $category_id);
        // 		}	

        // 		$whr_1 = array(
		// 	    	'start_date'  => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'    => date('Y-m-d', strtotime($end_date)),
		// 			'state_id'    => $state_id,
		// 			'city_id'     => $city_id,
		// 			'zone_id'     => $zone_id,
		// 			'employee_id' => $employee_id,
		// 			'category_id' => $category_val,
		// 	    	'method'      => '_outletBacklog',
		// 	    );

        // 		$data_list  = avul_call(API_URL.'backlog/api/outlet_backlog',$whr_1);

		// 	    if($data_list['status'] == 1)
		// 	    {
		// 	    	$html     = '';
		//     		$data_val = $data_list['data'];

		//     		$num = 1;
		//     		foreach ($data_val as $key => $val) {

		//     			$order_no    = empty_check($val['order_no']);
	    //                 $description = empty_check($val['description']);
	    //                 $pdt_price   = empty_check($val['pdt_price']);
	    //                 $pdt_qty     = empty_check($val['pdt_qty']);
	    //                 $net_value   = empty_check($val['net_value']);

	    //                 $html .= '
		// 	            	<tr>
        //                         <td>'.$num.'</td>
        //                         <td>'.$order_no.'</td>
        //                         <td>'.$description.'</td>
        //                         <td>'.$pdt_price.'</td>
        //                         <td>'.$pdt_qty.'</td>
        //                         <td>'.$net_value.'</td>
        //                     </tr>
		// 	            ';

		// 	            $num++;
		//     		}

		//     		$excel_btn = '<a class="btn btn-success m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/backlog_report/excel_print/'.$start_date.'/'.$end_date.'/'.$state_id.'/'.$city_id.'/'.$zone_id.'/'.$employee_id.'/'.$category_res.'" style="color: #fff;"><i class="icon-grid"></i> Excel</a>';

		//     		$pdf_btn   = '<a class="btn btn-danger m-t-27" target="_blank" href="'.BASE_URL.'index.php/admin/report/backlog_report/pdf_print/'.$start_date.'/'.$end_date.'/'.$state_id.'/'.$city_id.'/'.$zone_id.'/'.$employee_id.'/'.$category_res.'" style="color: #fff;"><i class="ft-file-text"></i> PDF</a>';

		//     		$response['status']    = 1;
		// 	        $response['message']   = $data_list['message']; 
		// 	        $response['data']      = $html;
		// 	        $response['excel_btn'] = $excel_btn;
		// 	        $response['pdf_btn']   = $pdf_btn;
		// 	        $response['error']     = []; 
		// 	        echo json_encode($response);
		// 	        return;
		// 	    }
		// 	    else
		//     	{
		//     		$response['status']  = 0;
		// 	        $response['message'] = $data_list['message']; 
		// 	        $response['data']    = [];
		// 	        $response['error']   = []; 
		// 	        echo json_encode($response);
		// 	        return;
		//     	}
        // 	}

        // 	else if($param1 =='getCity_name')
		// 	{
		// 		$state_id = $this->input->post('state_id');

		// 		$where = array(
        //     		'state_id' => $state_id,
        //     		'method'   => '_listCity'
        //     	);

        //     	$city_list   = avul_call(API_URL.'master/api/city',$where);
        //     	$city_result = $city_list['data'];

        // 		$option ='<option value="">Select Value</option>';

        // 		if(!empty($city_result))
        // 		{
        // 			foreach ($city_result as $key => $value) {
        // 				$city_id   = !empty($value['city_id'])?$value['city_id']:'';
        //                 $city_name = !empty($value['city_name'])?$value['city_name']:'';

        //                 $option .= '<option value="'.$city_id.'">'.$city_name.'</option>';
        // 			}
        // 		}

        // 		$response['status']  = 1;
		//         $response['message'] = 'success'; 
		//         $response['data']    = $option;
		//         echo json_encode($response);
		//         return; 	
		// 	}

		// 	else if($param1 =='getZone_name')
		// 	{
		// 		$state_id = $this->input->post('state_id');
		// 		$city_id  = $this->input->post('city_id');

		// 		$where = array(
        //     		'state_id' => $state_id,
        //     		'city_id'  => $city_id,
        //     		'method'   => '_listZone'
        //     	);

        //     	$zone_list   = avul_call(API_URL.'master/api/zone',$where);
        //     	$zone_result = $zone_list['data'];

        // 		$option ='<option value="">Select Value</option>';

        // 		if(!empty($zone_result))
        // 		{
        // 			foreach ($zone_result as $key => $value) {
        // 				$zone_id   = !empty($value['zone_id'])?$value['zone_id']:'';
        //                 $zone_name = !empty($value['zone_name'])?$value['zone_name']:'';

        //                 $option .= '<option value="'.$zone_id.'">'.$zone_name.'</option>';
        // 			}
        // 		}

        // 		$response['status']  = 1;
		//         $response['message'] = 'success'; 
		//         $response['data']    = $option;
		//         echo json_encode($response);
		//         return; 	
		// 	}

        // 	if($param1 == 'excel_print')
        // 	{
        // 		$start_date  = $param2; 
        // 		$end_date    = $param3;
        // 		$state_id    = $param4;
        // 		$city_id     = $param5;
        // 		$zone_id     = $param6;
        // 		$employee_id = $param7;
        // 		$category_id = $param8;

        // 		$category_val = explode('_', $category_id);
        // 		$category_res = implode(',', $category_val);

        // 		$whr_1 = array(
		// 	    	'start_date'  => date('Y-m-d', strtotime($start_date)),
		// 			'end_date'    => date('Y-m-d', strtotime($end_date)),
		// 			'state_id'    => $state_id,
		// 			'city_id'     => $city_id,
		// 			'zone_id'     => $zone_id,
		// 			'employee_id' => $employee_id,
		// 			'category_id' => $category_res,
		// 	    	'method'      => '_outletBacklog',
		// 	    );

		// 	    $data_list  = avul_call(API_URL.'backlog/api/outlet_backlog',$whr_1);

		// 	    header('Content-Type: text/csv; charset=utf-8');  
		// 	    header('Content-Disposition: attachment; filename=outlet_backlog_report('.$start_date.' to '.$end_date.').csv');  
		// 	    $output = fopen("php://output", "w");   
		// 	    fputcsv($output, array('OrdNo', 'Emp_Name', 'Pty_Name', 'Beat_Name', 'Product_Name', 'Qty', 'UOM', 'TaxPer', 'TaxableAmt', 'SGSTAmt', 'CGSTAmt', 'Net_Amt', 'Description'));

		// 	    if($data_list['status'] == 1)
		// 	    {
		// 	    	$data_val   = $data_list['data'];	
		// 	    	$totQty     = 0;
		//     		$totTaxable = 0;
		//     		$totSgstAmt = 0;
		//     		$totCgstAmt = 0;
		//     		$totNetAmt  = 0;

		// 	    	foreach ($data_val as $key => $val) {

		// 	    		$order_no      = empty_check($val['order_no']);
	    //                 $emp_name      = empty_check($val['emp_name']);
	    //                 $store_name    = empty_check($val['store_name']);
	    //                 $zone_name     = empty_check($val['zone_name']);
	    //                 $description   = empty_check($val['description']);
	    //                 $gst_value     = empty_check($val['gst_value']);
	    //                 $pdt_price     = empty_check($val['pdt_price']);
	    //                 $pdt_qty       = empty_check($val['pdt_qty']);
	    //                 $taxable_value = empty_check($val['taxable_value']);
	    //                 $tax_value     = empty_check($val['tax_value']);
	    //                 $net_value     = empty_check($val['net_value']);
	    //                 $log_data      = empty_check($val['log_data']);
	    //                 $tax_state     = $tax_value / 2;

        //                 $totQty     += $pdt_qty;
		// 	    		$totTaxable += $taxable_value;
		// 	    		$totSgstAmt += $tax_state;
		// 	    		$totCgstAmt += $tax_state;
		// 	    		$totNetAmt  += $net_value;

		// 	    		$num = array(
		// 	    			$order_no,
		// 	    			$emp_name,
		// 	    			$store_name,
		// 	    			$zone_name,
		// 	    			$description,
		// 	    			$pdt_qty,
		// 	    			'Nos',
		// 	    			$gst_value,
		// 	    			number_format((float)$taxable_value, 2, '.', ''),
		// 	    			number_format((float)$tax_state, 2, '.', ''),
		// 	    			number_format((float)$tax_state, 2, '.', ''),
		// 	    			number_format((float)$net_value, 2, '.', ''),
		// 	    			$log_data,
		// 	    		);

		// 	    		fputcsv($output, $num);
		// 	    	}

		// 	    	fputcsv($output, array(
		// 	    		'', 
		// 	    		'', 
		// 	    		'', 
		// 	    		'', 
		// 	    		$totQty, 
		// 	    		'', 
		// 	    		'', 
		// 	    		number_format((float)$totTaxable, 2, '.', ''), 
		// 	    		number_format((float)$totSgstAmt, 2, '.', ''), 
		// 	    		number_format((float)$totCgstAmt, 2, '.', ''), 
		// 	    		number_format((float)$totNetAmt, 2, '.', ''),
		// 	    	));
		// 	    }

		// 	    fclose($output);
      	// 		exit();
        // 	}

        // 	else
		// 	{
		// 		$where_1 = array('method'   => '_listState');
        //     	$state_list  = avul_call(API_URL.'master/api/state',$where_1);

        //     	$where_2 = array(
		//     		'log_type' => '2',
		//     		'method'   => '_typeWiseEmployee'
		//     	);

		//     	$data_list  = avul_call(API_URL.'employee/api/employee',$where_2);
		//     	$emp_list   = !empty($data_list['data'])?$data_list['data']:'';

		//     	$where_2 = array(
		// 			'item_type'      => '1',
		// 			'salesagents_id' => '0',
        //     		'method'         => '_listCategory',
        //     	);

        //     	$category_list = avul_call(API_URL.'catlog/api/category',$where_2);


		//     	$page['method']        = '_getBacklogData';
		//     	$page['state_val']     = $state_list['data'];
		//     	$page['employee_val']  = $emp_list;
		//     	$page['category_val']  = $category_list['data'];
		// 		$page['main_heading']  = "Report";
		// 		$page['sub_heading']   = "Report";
		// 		$page['pre_title']     = "Backlog Report";
		// 		$page['page_title']    = "Backlog Report";
		// 		$page['pre_menu']      = "";
		// 		$data['page_temp']     = $this->load->view('admin/report/backlog_report',$page,TRUE);
		// 		$data['view_file']     = "Page_Template";
		// 		$data['currentmenu']   = "backlog_report";
		// 		$this->bassthaya->load_admin_form_template($data);
		// 	}
        // }
    }
?>
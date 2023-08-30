<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Attendance extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('employee_model');
			$this->load->model('attendance_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Count List
		// ***************************************************
		public function list($param1="", $param2="", $param3="")
		{
			$method      = $this->input->post('method');

			if($method == '_attendanceList')
			{
				// Master Value
		    	// **********************************************
		    	$where_1 = array(
					'attendance_type' => '2',
					'published'       => '1',
					'status'          => '1',
				);

				$limit  = 10;
				$offset = 0;

				$option['order_by']   = 'id';
				$option['disp_order'] = 'DESC';

				// Attendace List
				$attendance_list = $this->attendance_model->getAttendance($where_1, $limit, $offset, 'result', '', '', $option);

				if($attendance_list)
				{
					$attendance_data = [];
					
					foreach ($attendance_list as $key => $val_1) {
						$att_id     = !empty($val_1->id)?$val_1->id:'';
						$emp_name   = !empty($val_1->emp_name)?$val_1->emp_name:'';
						$emp_type   = !empty($val_1->emp_type)?$val_1->emp_type:'';
						$store_name = !empty($val_1->store_name)?$val_1->store_name:'';
						$att_type   = !empty($val_1->attendance_type)?$val_1->attendance_type:'';
						$reason     = !empty($val_1->reason)?$val_1->reason:'';
						$c_date     = !empty($val_1->c_date)?$val_1->c_date:'';
						$in_time    = !empty($val_1->in_time)?$val_1->in_time:'';
						$out_time   = !empty($val_1->out_time)?$val_1->out_time:'';
						$log_status = !empty($val_1->log_status)?$val_1->log_status:'0';

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

						$attendance_data[] = array(
							'attendance_id'   => $att_id,
							'emp_name'        => $emp_name,
							'emp_type'        => $emp_type,
							'store_name'      => $store_name,
							'attendance_type' => $type_name,
							'reason'          => $reason,
							'c_date'          => date_check($c_date),
							'in_time'         => time_check($in_time),
							'out_time'        => time_check($out_time),
							'log_status'      => $log_status,
						);
					}

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $attendance_data;
		    		echo json_encode($response);
			        return;
				}
				else
				{
					$response['status']  = 0;
			        $response['message'] = "No data found"; 
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
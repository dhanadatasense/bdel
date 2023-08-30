<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Order extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('order_model');
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

			if($method == '_orderList')
			{
				// Master Value
		    	// **********************************************
		    	$where_1 = array(
					'A.published' => '1',
					'A.status'    => '1',
					'B.published' => '1',
					'B.status'    => '1',
				);

				$limit  = 10;
				$offset = 0;

				$option['order_by']   = 'A.id';
				$option['disp_order'] = 'DESC';

				$group_by = 'B.order_id';

				$column   = 'A.id, A.order_no, A.emp_name, A.store_name, A._ordered, A.order_status, SUM(B.order_qty * B.price) AS total_value';

				// Purchase List
				$order_list = $this->order_model->getOrderJoin($where_1, $limit, $offset, 'result', '', '', $option, '', $column, $group_by);

				if($order_list)
				{
					$order_data = [];
					foreach ($order_list as $key => $val) {
						$order_no     = !empty($val->order_no)?$val->order_no:'';
						$emp_name     = !empty($val->emp_name)?$val->emp_name:'Admin';
						$store_name   = !empty($val->store_name)?$val->store_name:'';
						$_ordered     = !empty($val->_ordered)?$val->_ordered:'';
						$order_status = !empty($val->order_status)?$val->order_status:'';
						$total_value  = !empty($val->total_value)?$val->total_value:'';

			            $order_data[] = array(
							'order_no'     => $order_no,
							'emp_name'     => $emp_name,
							'store_name'   => $store_name,
							'_ordered'     => date('d-M-Y', strtotime($_ordered)),
							'order_status' => $order_status,
				            'total_value'  => number_format((float)round($total_value), 2, '.', ''),
			            );
					}

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $order_data;
		    		echo json_encode($response);
			        return;
				}
				else
				{
					$response['status']  = 1;
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
<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Purchase extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('purchase_model');
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

			if($method == '_purchaseList')
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

				$group_by = 'B.po_id';

				$column   = 'A.id, A.po_no, A.vendor_name, A.order_date, A.order_status, A.invoice_no, SUM(B.product_qty * B.product_price) AS total_value';

				// Purchase List
				$purchase_list = $this->purchase_model->getPurchaseJoin($where_1, $limit, $offset, 'result', '', '', $option, '', $column, $group_by);

				if($purchase_list)
				{
					$purchase_data = [];
					foreach ($purchase_list as $key => $val) {
			            $po_no        = !empty($val->po_no)?$val->po_no:'';
			            $vendor_name  = !empty($val->vendor_name)?$val->vendor_name:'';
			            $order_date   = !empty($val->order_date)?$val->order_date:'';
			            $order_status = !empty($val->order_status)?$val->order_status:'';
			            $invoice_no   = !empty($val->invoice_no)?$val->invoice_no:'';
			            $total_value  = !empty($val->total_value)?$val->total_value:'';

			            $purchase_data[] = array(
				            'po_no'        => $po_no,
				            'vendor_name'  => $vendor_name,
				            'order_date'   => $order_date,
				            'order_status' => $order_status,
				            'invoice_no'   => $invoice_no,
				            'total_value'  => number_format((float)round($total_value), 2, '.', ''),
			            );
					}

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $purchase_data;
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
<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Target extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('target_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Product target
		// ***************************************************
		public function product_target($param1="", $param2="", $param3="")
		{
			$method = $this->input->post('method');

			// Month Details
			$month_val = date('m');
			$year_val  = date('Y');
			$dateObj   = DateTime::createFromFormat('!m', $month_val);
			$monthName = $dateObj->format('F'); // March

			if($method == '_purchaseTarget')
			{
				// Master Value
		    	// **********************************************
		    	$where_1 = array(
					'published' => '1',
					'status'    => '1',
				);

				$limit  = 10;
				$offset = 0;

				$option['order_by']   = 'id';
				$option['disp_order'] = 'DESC';

				$group_by = 'type_id';
				$column   = 'type_id, description, SUM(target_val) AS target_val, SUM(achieve_val) AS achieve_val';

				$target_list  = $this->target_model->getProductTargetDetails($where_1, $limit, $offset, 'result', '', '', $option, '', $column, $group_by);

				$target_data  = [];
				if($target_list)
				{
					foreach ($target_list as $key => $val) {
						$type_id     = empty_check($val->type_id);
            			$description = empty_check($val->description);
            			$target_val  = zero_check($val->target_val);
    					$achieve_val = zero_check($val->achieve_val);

    					$target_data[] = array(
							'description' => $description,
							'target_val'  => $target_val,
							'achieve_val' => $achieve_val,
						);
					}

					$targetsort = array();
				    foreach ($target_data as $key => $row)
				    {
				        $targetsort[$key] = $row['target_val'];
				    }

				    array_multisort($targetsort, SORT_DESC, $target_data);

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $target_data;
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

		// Beat target
		// ***************************************************
		public function beat_target($param1="", $param2="", $param3="")
		{
			$method = $this->input->post('method');

			// Month Details
			$month_val = date('m');
			$year_val  = date('Y');
			$dateObj   = DateTime::createFromFormat('!m', $month_val);
			$monthName = $dateObj->format('F'); // March

			if($method == '_beatTarget')
			{
				// Master Value
		    	// **********************************************
		    	$where_1 = array(
					'published' => '1',
					'status'    => '1',
				);

				$limit  = 10;
				$offset = 0;

				$option['order_by']   = 'id';
				$option['disp_order'] = 'DESC';

				$group_by = 'zone_id';
				$column   = 'zone_id, zone_name, SUM(target_val) AS target_val, SUM(achieve_val) AS achieve_val';

				$target_list  = $this->target_model->getBeatTargetDetails($where_1, $limit, $offset, 'result', '', '', $option, '', $column, $group_by);

				$target_data  = [];
				if($target_list)
				{
					foreach ($target_list as $key => $val) {
						$zone_id     = empty_check($val->zone_id);
            			$zone_name   = empty_check($val->zone_name);
            			$target_val  = zero_check($val->target_val);
    					$achieve_val = zero_check($val->achieve_val);

    					$target_data[] = array(
							'zone_name'   => $zone_name,
							'target_val'  => $target_val,
							'achieve_val' => $achieve_val,
						);
					}

					$targetsort = array();
				    foreach ($target_data as $key => $row)
				    {
				        $targetsort[$key] = $row['target_val'];
				    }

				    array_multisort($targetsort, SORT_DESC, $target_data);

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $target_data;
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
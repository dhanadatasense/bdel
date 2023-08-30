<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class ExecutiveCollection extends CI_Controller {

		public function __construct()
		{
			parent::__construct();

			$this->load->model('payment_model');
		}

		public function index()
		{
			echo "Test";
		}

		// invoice_list
		// ***************************************************
		public function invoice_list($param1="",$param2="",$param3="")
		{
			$method    = $this->input->post('method');
			$outlet_id = $this->input->post('outlet_id');
			$limit     = $this->input->post('limit');
	    	$offset    = $this->input->post('offset');

			if($method == '_invoiceList')
			{
				if($outlet_id)
				{
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

					$whr_1 = array(
						'A.outlet_id'   => $outlet_id,
						'A.published'   => '1',
						'A.bal_amt !='  => '0',
					);

					$col_1 = 'A.id';

					$res_1 = $this->payment_model->getOutletPaymentJoinDetails($whr_1, '', '', 'result', $like, '', '', '', $col_1);

					if($res_1)
					{
						$totalc = count($res_1);
					}
					else
					{
						$totalc = 0;
					}

					$col_2 = 'A.id, A.assign_id, A.distributor_id, A.outlet_id, B.company_name, A.bill_no, A.amount, A.bal_amt, A.date';

					$res_2 = $this->payment_model->getOutletPaymentJoinDetails($whr_1, $limit, $offset, 'result', $like, '', '', '', $col_2);

					if($res_2)
					{
						$data_list = [];
						foreach ($res_2 as $key => $val_1) {
							$payment_id     = empty_check($val_1->id);
							$assign_id      = empty_check($val_1->assign_id);
							$distributor_id = empty_check($val_1->distributor_id);
							$outlet_id      = empty_check($val_1->outlet_id);
				            $company_name   = empty_check($val_1->company_name);
				            $bill_no        = empty_check($val_1->bill_no);
				            $amount         = zero_check($val_1->amount);
				            $bal_amt        = zero_check($val_1->bal_amt);
				            $date           = empty_check($val_1->date);

				            $data_list[] = array(
				            	'payment_id'     => $payment_id,
				            	'assign_id'      => $assign_id,
				            	'distributor_id' => $distributor_id,
				            	'outlet_id'      => $outlet_id,
					            'company_name'   => $company_name,
					            'bill_no'        => $bill_no,
					            'amount'         => $amount,
					            'bal_amt'        => $bal_amt,
					            'date'           => date_check($date),
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
				        $response['data']         = $data_list;
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
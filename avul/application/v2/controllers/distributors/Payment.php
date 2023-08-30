<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Payment extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function index($param1="", $param2="", $param3="")
		{
			echo "string";
		}		

		public function outlet_payment($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method == '_addOutletPayment')
        	{
        		$assign_id     = $this->input->post('assign_id');
        		$pay_id        = $this->input->post('pay_id');
				$amount        = $this->input->post('amount');
				$discount      = $this->input->post('discount');
				$amt_type      = $this->input->post('amt_type');
				$bank_name     = $this->input->post('bank_name');
				$cheque_no     = $this->input->post('cheque_no');
				$collect_date  = $this->input->post('collect_date');
				$penalty_amt   = $this->input->post('penalty_amt');
				$bank_charge   = $this->input->post('bank_charge');
				$cheque_status = $this->input->post('cheque_status');
				$description   = $this->input->post('description');
				$entry_date    = $this->input->post('entry_date');

        		$error = FALSE;
			    $errors = array();
				$required = array('pay_id', 'assign_id', 'amount', 'amt_type', 'entry_date');

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
			    	$assign_whr  = array(
	        			'assign_id' => $assign_id,
	        			'method'    => '_detailsDistributorOutlets',
	        		);

	        		$assign_data  = avul_call(API_URL.'outlets/api/distributor_outlet_list',$assign_whr);
	            	$assign_value = !empty($assign_data['data'])?$assign_data['data']:'';

	            	$distri_id = !empty($assign_value['distributor_id'])?$assign_value['distributor_id']:'';
    				$outlet_id = !empty($assign_value['outlet_id'])?$assign_value['outlet_id']:'';

					$whereee_1 = array(
						'distributor_id' => $distri_id,
						'method'   => '_detailDistributors'
					);
			
					$data_val    = avul_call(API_URL . 'distributors/api/distributors', $whereee_1);
					$data_value  = $data_val['data'];	
					$ref_id  = !empty($data_value[0]['ref_id'])?$data_value[0]['ref_id']:'';
		
		
			    	$data = array(
						'ref_id'        => $ref_id,
			    		'pay_id'         => $pay_id,
				    	'assign_id'      => $assign_id,
				    	'distributor_id' => $distri_id,
				    	'outlet_id'      => $outlet_id,
				    	'amount'         => $amount,
				    	'discount'       => $discount,
				    	'amt_type'       => $amt_type,
				    	'description'    => $description,
				    	'entry_date'     => $entry_date,
				    	'bank_name'      => $bank_name,
				    	'cheque_no'      => $cheque_no,
				    	'collect_date'   => $collect_date,
				    	'entry_type'     => 1,
				    	'coll_type'      => 1,
				    	'method'         => '_addOutletPayment',
				    );

				    $data_save = avul_call(API_URL.'payment/api/outlet_payment',$data);

				    if($data_save['status'] == 1)
				    {
				    	$response['status']   = 1;
				        $response['message']  = $data_save['message']; 
				        $response['data']     = [];
				        $response['redirect'] = BASE_URL.'index.php/distributors/payment/outlet_payment/'.$assign_id;
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

        	if($method == '_editOutletPayment')
        	{
        		$assign_id     = $this->input->post('assign_id');
        		$auto_id       = $this->input->post('auto_id');
			    $amt_type      = $this->input->post('amt_type');
			    $entry_date    = $this->input->post('entry_date');
			    $description   = $this->input->post('description');
			    $penalty_amt   = $this->input->post('penalty_amt');
			    $bank_charge   = $this->input->post('bank_charge');
			    $cheque_status = $this->input->post('cheque_status');

			    $error = FALSE;
			    $errors = array();
				$required = array('auto_id', 'cheque_status', 'description');

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
			    	$data = array(
			    		'auto_id'        => $auto_id,
			    		'entry_date'     => $entry_date,
				    	'description'    => $description,
				    	'penalty_amt'    => $penalty_amt,
				    	'bank_charge'    => $bank_charge,
				    	'cheque_status'  => $cheque_status,
				    	'method'         => '_editOutletPayment',
				    );

				    $data_save = avul_call(API_URL.'payment/api/outlet_payment',$data);

				    if($data_save['status'] == 1)
				    {
				    	$response['status']   = 1;
				        $response['message']  = $data_save['message']; 
				        $response['data']     = [];
				        $response['redirect'] = BASE_URL.'index.php/distributors/payment/outlet_payment/'.$assign_id;

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

        	if($param1 == 'payment_value')
        	{
        		$limit      = $this->input->post('limitval');
            	$page       = $this->input->post('page');
            	$search     = $this->input->post('search');
            	$random_val = $this->input->post('random_val');
            	$cur_page   = isset($page)?$page:'1';
            	$_offset    = ($cur_page-1) * $limit;
            	$nxt_page   = $cur_page + 1;
            	$pre_page   = $cur_page - 1;

            	$where = array(
            		'offset'    => $_offset,
            		'limit'     => $limit,
            		'search'    => $search,
            		'assign_id' => $random_val,
            		'method'    => '_listDistributorOutletPaymentPaginate'
            	);

            	$data_list  = avul_call(API_URL.'payment/api/outlet_payment',$where);
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

	            		$payment_id      = !empty($value['payment_id'])?$value['payment_id']:'';
			            $employee_name   = !empty($value['employee_name'])?$value['employee_name']:'';
			            $distri_name     = !empty($value['distributor_name'])?$value['distributor_name']:'';
			            $bill_code       = !empty($value['bill_code'])?$value['bill_code']:'';
			            $bill_no         = !empty($value['bill_no'])?$value['bill_no']:'';
			            $pre_bal         = !empty($value['pre_bal'])?$value['pre_bal']:'0';
			            $cur_bal         = !empty($value['cur_bal'])?$value['cur_bal']:'0';
			            $amount          = !empty($value['amount'])?$value['amount']:'0';
			            $discount        = !empty($value['discount'])?$value['discount']:'0';
			            $payment_type    = !empty($value['payment_type'])?$value['payment_type']:'';
			            $amount_type     = !empty($value['amount_type'])?$value['amount_type']:'';
			            $collection_type = !empty($value['collection_type'])?$value['collection_type']:'';
			            $cheque_process  = !empty($value['cheque_process'])?$value['cheque_process']:'';
			            $penalty_amt     = !empty($value['penalty_amt'])?$value['penalty_amt']:'0';
			            $bank_charge     = !empty($value['bank_charge'])?$value['bank_charge']:'0';
			            $date            = !empty($value['date'])?$value['date']:'';
			            $time            = !empty($value['time'])?$value['time']:'';
			            $active_status   = !empty($value['status'])?$value['status']:'';

			            if($bill_code == 'PEN')
			            {
			            	$amount_value = $penalty_amt.' + '.$bank_charge;
			            }
			            else
			            {
			            	$amount_value = $amount.' + '.$discount;
			            }

			            if($bill_code == 'CHQ')
			            {
			            	$bg_clr   = 'style="background-color: #eeeeee6b;"';
			            }
			            else
			            {
			            	$bg_clr   = 'style="background-color: #fff;"';
			            }

			            if($collection_type == 1)
			            {
			            	if($bill_code == 'TEMP')
			            	{
			            		$edit_bth = '<a data-row="'.$i.'" data-id="'.$payment_id.'" data-value="distributors" data-cntrl="payment" data-func="outlet_payment" class="update-btn button_clr btn btn-success"><i class="la la-check-square-o"></i> </a>';
			            	}
			            	else
			            	{
			            		$edit_bth = '<a href="'.BASE_URL.'index.php/distributors/payment/outlet_payment/'.$random_val.'/Edit/'.$payment_id.'" class="button_clr btn btn-warning"><i class="ft-edit"></i> </a>';
			            	}
			            }
			            else
			            {
			            	$edit_bth = '<a data-row="'.$i.'" data-id="'.$payment_id.'" data-value="distributors" data-cntrl="payment" data-func="outlet_payment" class="button_clr btn btn-success"><i class="la la-check-square-o"></i></a>';
			            }

			            if($active_status != 0)
			            {
			            	$delete_bth = '<a data-row="'.$i.'" data-id="'.$payment_id.'" data-value="distributors" data-cntrl="payment" data-func="outlet_payment" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> </a>';
			            }
			            else
			            {
			            	$delete_bth = '<a data-row="'.$i.'" data-id="" data-value="" data-cntrl="payment" data-func="" class="button_clr btn btn-danger" disabled="disabled"><i class="ft-trash-2"></i> </a>';	
			            }

			            if($cheque_process == 2)
			            {
			            	$table .= '
						    	<tr class="row_'.$i.'" '.$bg_clr.'>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.mb_strimwidth($employee_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.$bill_no.'</td>
	                                <td class="line_height">'.$amount_value.'</td>
	                                <td class="line_height">'.$amount_type.'</td>
	                                <td class="line_height">'.$date.'</td>
	                                <td>'.$edit_bth.' '.$delete_bth.'</td>
	                            </tr>
						    ';
			            }
			            else
			            {
			            	$table .= '
						    	<tr class="row_'.$i.'" '.$bg_clr.'>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.mb_strimwidth($employee_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.$bill_no.'</td>
	                                <td class="line_height">'.$amount_value.'</td>
	                                <td class="line_height">'.$amount_type.'</td>
	                                <td class="line_height">'.$date.'</td>
	                                <td>'.$edit_bth.' '.$delete_bth.'</td>
	                            </tr>
						    ';
			            }

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
				    	'payment_id' => $id,
				    	'method'     => '_deleteOutletPayment'
				    );

				    $data_save = avul_call(API_URL.'payment/api/outlet_payment',$data);

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

			else if($param1 == 'payment_update')
			{
				$id = $this->input->post('id');

				if(!empty($id))	
				{
					$data = array(
				    	'payment_id' => $id,
				    	'method'     => '_updateOutletPayment'
				    );

				    $data_save = avul_call(API_URL.'payment/api/outlet_payment',$data);

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

			else if($param1 == 'getBill_detail')
			{
				$pay_id = $this->input->post('pay_id');

				$where = array(
            		'payment_id' => $pay_id,
            		'method'     => '_detailOutletPaymentBill'
            	);

            	$data_list  = avul_call(API_URL.'payment/api/outlet_payment',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	if(!empty($data_value))
            	{
            		$pay_id  = !empty($data_value[0]['pay_id'])?$data_value[0]['pay_id']:'';
		            $bill_id = !empty($data_value[0]['bill_id'])?$data_value[0]['bill_id']:'';
		            $bill_no = !empty($data_value[0]['bill_no'])?$data_value[0]['bill_no']:'';
		            $pre_bal = !empty($data_value[0]['pre_bal'])?$data_value[0]['pre_bal']:'0';
		            $cur_bal = !empty($data_value[0]['cur_bal'])?$data_value[0]['cur_bal']:'0';
		            $bal_amt = !empty($data_value[0]['bal_amt'])?$data_value[0]['bal_amt']:'0';

		            $payment_data = array(
		            	'pay_id'  => $pay_id,
			            'bill_id' => $bill_id,
			            'bill_no' => $bill_no,
			            'pre_bal' => $pre_bal,
			            'cur_bal' => $cur_bal,
			            'bal_amt' => $bal_amt,
		            );

		            $response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $payment_data;
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
        		if($param2 == 'Edit')
        		{
        			$pay_whr = array(
	            		'payment_id' => $param3,
	            		'method'     => '_outletPaymentDetails'
	            	);

	            	$pay_res = avul_call(API_URL.'payment/api/outlet_payment',$pay_whr);
	            	$pay_val = !empty($pay_res['data'])?$pay_res['data']:'';

	            	$page['pay_val']     = $pay_val;
	            	$page['page_action'] = 'Edit';
	            	$page['method']      = '_editOutletPayment';
	            	$page['page_title']  = "Edit Outlet Receipt";
        		}
        		else
        		{
        			$page['pay_val']     = '';
        			$page['page_action'] = 'Add';
        			$page['method']      = '_addOutletPayment';
        			$page['page_title']  = "Add Outlet Receipt";
        		}

        		$where = array(
            		'assign_id' => $param1,
            		'method'    => '_listOutletPaymentBill'
            	);

            	$data_list  = avul_call(API_URL.'payment/api/outlet_payment',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

        		$page['main_heading'] = "Receipt";
				$page['sub_heading']  = "Receipt";
				$page['pre_title']    = "List Outlet Receipt";
				$page['random_val']   = $param1;
				$page['payment_list'] = $data_value;
				$page['pre_menu']     = "index.php/distributors/payment/list_outlets";
				$data['page_temp']    = $this->load->view('distributors/payment/outlet_payment',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "outlet_payment";
				$this->bassthaya->load_distributors_form_template($data);
        	}
		}

		public function list_outlets($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Outlets Receipt";
				$page['sub_heading']  = "Outlets Receipt";
				$page['page_title']   = "List Outletss Receipt";
				$data['page_temp']    = $this->load->view('distributors/payment/list_outlets',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "outlet_payment";
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
            		'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
            		'distributor_id' => $this->session->userdata('id'),
            		'method'         => '_listDistributorOutletsPaginate'
            	);

            	$data_list  = avul_call(API_URL.'outlets/api/distributor_outlet_list',$where);
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


	            		$assign_id      = !empty($value['assign_id'])?$value['assign_id']:'';
					    $distributor_id = !empty($value['distributor_id'])?$value['distributor_id']:'';
					    $outlet_id      = !empty($value['outlet_id'])?$value['outlet_id']:'';
					    $outlet_name    = !empty($value['outlet_name'])?$value['outlet_name']:'';
					    $mobile         = !empty($value['mobile'])?$value['mobile']:'';
					    $pre_bal        = !empty($value['pre_bal'])?$value['pre_bal']:'0';
					    $cur_bal        = !empty($value['cur_bal'])?$value['cur_bal']:'0';
					    $active_status  = !empty($value['status'])?$value['status']:'';

					    if($active_status == '1')
		                {
		                	$status_view = '<span class="badge badge-success">Active</span>';
		                }
		                else
		                {
		                	$status_view = '<span class="badge badge-danger">In Active</span>';
		                }

					    $table .= '
					    	<tr>
                                <td class="line_height">'.$i.'</td>
                                <td class="line_height">'.mb_strimwidth($outlet_name, 0, 20, '...').'</td>
                                <td class="line_height">'.$mobile.'</td>
                                <td class="line_height">'.$cur_bal.'</td>
                                <td class="line_height">'.$status_view.'</td>
                                <td>
                                	<a href="'.BASE_URL.'index.php/distributors/payment/outlet_payment/'.$assign_id.'" class="button_clr btn btn-info"><i class="icon-wallet"></i> Receipt </a>
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
		}
	}
?>
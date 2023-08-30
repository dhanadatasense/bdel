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

		public function vendor_payment($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method        = $this->input->post('method');
        	$auto_id       = $this->input->post('auto_id');
        	$vendor_id     = $this->input->post('vendor_id');
    		$pay_id        = $this->input->post('pay_id');
			$amount        = $this->input->post('amount');
			$discount      = $this->input->post('discount');
			$entry_date    = $this->input->post('entry_date');
			$amt_type      = $this->input->post('amt_type');
			$bank_name     = $this->input->post('bank_name');
			$cheque_no     = $this->input->post('cheque_no');
			$collect_date  = $this->input->post('collect_date');
			$penalty_amt   = $this->input->post('penalty_amt');
			$bank_charge   = $this->input->post('bank_charge');
			$cheque_status = $this->input->post('cheque_status');
			$description   = $this->input->post('description');

        	if($method == '_addVendorPayment')
        	{
        		$error = FALSE;
			    $errors = array();
				$required = array('vendor_id', 'pay_id', 'amount', 'amt_type', 'entry_date');

				if($amt_type == 2)
			    {
			    	array_push($required, 'bank_name', 'cheque_no', 'collect_date');
			    }
			    else if($amt_type == 3 || $amt_type == 4)
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
			    	if(userAccess('manufacture-payment-add'))
			    	{
			    		$data = array(
					    	'vendor_id'    => $vendor_id,
					    	'pay_id'       => $pay_id,
					    	'amount'       => $amount,
					    	'discount'     => $discount,
					    	'entry_date'   => $entry_date,
					    	'amt_type'     => $amt_type,
					    	'bank_name'    => $bank_name,
					    	'cheque_no'    => $cheque_no,
					    	'collect_date' => $collect_date,
					    	'description'  => $description,
					    	'method'       => '_addVendorPayment',
					    );

					    $data_save = avul_call(API_URL.'payment/api/vendor_payment',$data);

					    if($data_save['status'] == 1)
					    {
					    	$response['status']   = 1;
					        $response['message']  = $data_save['message']; 
					        $response['data']     = [];
					        $response['redirect'] = BASE_URL.'index.php/admin/payment/vendor_payment/'.$vendor_id;
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
				        $response['message'] = 'Access denied'; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
		    		}
			    }
        	}

        	else if($method == '_editVendorPayment')
        	{
        		$error = FALSE;
			    $errors = array();
				$required = array('auto_id', 'cheque_status', 'entry_date', 'description');

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
				    	'vendor_id'      => $vendor_id,
				    	'description'    => $description,
				    	'penalty_amt'    => $penalty_amt,
				    	'bank_charge'    => $bank_charge,
				    	'cheque_status'  => $cheque_status,
				    	'method'         => '_editVendorPayment',
				    );

				    $data_save = avul_call(API_URL.'payment/api/vendor_payment',$data);

				    if($data_save['status'] == 1)
				    {
				    	$response['status']   = 1;
				        $response['message']  = $data_save['message']; 
				        $response['data']     = [];
				        $response['redirect'] = BASE_URL.'index.php/admin/payment/vendor_payment/'.$vendor_id;

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
            		'vendor_id' => $random_val,
            		'method'    => '_listVendorPaymentPaginate'
            	);

            	$data_list  = avul_call(API_URL.'payment/api/vendor_payment',$where);
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

	            		$payment_id     = !empty($value['payment_id'])?$value['payment_id']:'0';
	            		$invoice_no     = !empty($value['invoice_no'])?$value['invoice_no']:'0';
	            		$bill_no        = !empty($value['bill_no'])?$value['bill_no']:'0';
	            		$bill_code      = !empty($value['bill_code'])?$value['bill_code']:'0';
			            $pre_bal        = !empty($value['pre_bal'])?$value['pre_bal']:'0';
			            $cur_bal        = !empty($value['cur_bal'])?$value['cur_bal']:'0';
			            $amount         = !empty($value['amount'])?$value['amount']:'0';
			            $discount       = !empty($value['discount'])?$value['discount']:'0';
			            $payment_type   = !empty($value['payment_type'])?$value['payment_type']:'';
			            $amt_type       = !empty($value['amt_type'])?$value['amt_type']:'';
			            $amount_type    = !empty($value['amount_type'])?$value['amount_type']:'';
			            $collect_type   = !empty($value['collection_type'])?$value['collection_type']:'';
			            $cheque_process = !empty($value['cheque_process'])?$value['cheque_process']:'';
			            $penalty_amt    = !empty($value['penalty_amt'])?$value['penalty_amt']:'0';
			            $bank_charge    = !empty($value['bank_charge'])?$value['bank_charge']:'0';
			            $date           = !empty($value['date'])?$value['date']:'';
			            $time           = !empty($value['time'])?$value['time']:'';
			            $active_status  = !empty($value['status'])?$value['status']:'';

			            if($bill_code == 'PEN')
			            {
			            	$amount_value = $penalty_amt.' + '.$bank_charge;
			            }
			            else
			            {
			            	$amount_value = $amount.' + '.$discount;
			            }

			            if($collect_type == 1)
			            {
			            	$edit_bth = '<a href="'.BASE_URL.'index.php/admin/payment/vendor_payment/'.$random_val.'/Edit/'.$payment_id.'" class="button_clr btn btn-warning"><i class="ft-edit"></i> </a>';
			            }
			            else
			            {
			            	$edit_bth = '<a data-row="'.$i.'" data-id="'.$payment_id.'" data-value="admin" data-cntrl="payment" data-func="vendor_payment" class="button_clr btn btn-success"><i class="la la-check-square-o"></i></a>';
			            }

			            $delete_bth = '';
			            if(userAccess('manufacture-payment-delete') == TRUE)
			            {
			            	if($active_status != 0)
				            {
				            	$delete_bth = '<a data-row="'.$i.'" data-id="'.$payment_id.'" data-value="admin" data-cntrl="payment" data-func="vendor_payment" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> </a>';
				            }
				            else
				            {
				            	$delete_bth = '<a data-row="'.$i.'" data-id="" data-value="" data-cntrl="payment" data-func="" class="button_clr btn btn-danger" disabled="disabled"><i class="ft-trash-2"></i> </a>';	
				            }
			            }

			            if($cheque_process == 2)
			            {
			            	$table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.$bill_no.'</td>
	                                <td class="line_height">'.$pre_bal.'</td>
	                                <td class="line_height">'.$amount_value.'</td>
	                                <td class="line_height">'.$cur_bal.'</td>
	                                <td class="line_height">'.$date.'</td>
	                                <td>'.$edit_bth.' '.$delete_bth.'</td>
	                            </tr>
						    ';
			            }
			            else
			            {
			            	$table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.$bill_no.'</td>
	                                <td class="line_height">---</td>
	                                <td class="line_height">'.$amount_value.'</td>
	                                <td class="line_height">---</td>
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
				    	'method'     => '_deleteVendorPayment'
				    );

				    $data_save = avul_call(API_URL.'payment/api/vendor_payment',$data);

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
            		'method'     => '_detailVendorPaymentBill'
            	);

            	$data_list  = avul_call(API_URL.'payment/api/vendor_payment',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	if(!empty($data_value))
            	{
            		$pay_id  = !empty($data_value[0]['pay_id'])?$data_value[0]['pay_id']:'';
		            $bill_id = !empty($data_value[0]['bill_id'])?$data_value[0]['bill_id']:'';
		            $bill_no = !empty($data_value[0]['bill_no'])?$data_value[0]['bill_no']:'';
		            $pre_bal = !empty($data_value[0]['pre_bal'])?$data_value[0]['pre_bal']:'0';
		            $cur_bal = !empty($data_value[0]['cur_bal'])?$data_value[0]['cur_bal']:'0';
		            $amount  = !empty($data_value[0]['amount'])?$data_value[0]['amount']:'0';
		            $bal_amt = !empty($data_value[0]['bal_amt'])?$data_value[0]['bal_amt']:'0';

		            $payment_data = array(
		            	'pay_id'  => $pay_id,
			            'bill_id' => $bill_id,
			            'bill_no' => $bill_no,
			            'pre_bal' => $pre_bal,
			            'cur_bal' => $cur_bal,
			            'amount'  => $amount,
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
	            		'method'     => '_vendorPaymentDetails'
	            	);

	            	$pay_res = avul_call(API_URL.'payment/api/vendor_payment',$pay_whr);
	            	$pay_val = !empty($pay_res['data'])?$pay_res['data']:'';

	            	$page['pay_val']     = $pay_val;
	            	$page['page_action'] = 'Edit';
	            	$page['method']      = '_editVendorPayment';
	            	$page['page_title']  = "Edit Manufacture Payment";
        		}
        		else
        		{
        			$page['pay_val']     = '';
        			$page['page_action'] = 'Add';
        			$page['method']      = '_addVendorPayment';
        			$page['page_title']  = "Add Manufacture Payment";
        		}

        		$where = array(
            		'vendor_id' => $param1,
            		'method'    => '_listVendorPaymentBill'
            	);

            	$data_list  = avul_call(API_URL.'payment/api/vendor_payment',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

        		$page['main_heading'] = "Payment";
				$page['sub_heading']  = "Payment";
				$page['pre_title']    = "List Manufacture Payment";
				$page['random_val']   = $param1;
				$page['payment_list'] = $data_value;
				$page['pre_menu']     = "index.php/admin/payment/list_vendor";
				$data['page_temp']    = $this->load->view('admin/payment/vendor_payment',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "vendor_payment";
				$this->bassthaya->load_admin_form_template($data);
        	}
		}

		public function list_vendor($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Manufacture Payment";
				$page['sub_heading']  = "Manufacture Payment";
				$page['page_title']   = "List Manufacture Payment";
				$data['page_temp']    = $this->load->view('admin/payment/list_vendor',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "vendor_payment";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('manufacture-payment-view'))
				{
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
	            		'offset'  => $_offset,
	            		'limit'   => $limit,
	            		'search'  => $search,
	            		'method'  => '_listVendorsPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'vendors/api/vendors',$where);
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
		            		$vendor_id     = !empty($value['vendor_id'])?$value['vendor_id']:'';
						    $company_name  = !empty($value['company_name'])?$value['company_name']:'';
						    $gst_no        = !empty($value['gst_no'])?$value['gst_no']:'';
						    $contact_no    = !empty($value['contact_no'])?$value['contact_no']:'';
						    $email         = !empty($value['email'])?$value['email']:'';
						    $pre_balance   = !empty($value['pre_balance'])?$value['pre_balance']:'0';
						    $cur_balance   = !empty($value['cur_balance'])?$value['cur_balance']:'0';
						    $active_status = !empty($value['status'])?$value['status']:'';
						    $createdate    = !empty($value['createdate'])?$value['createdate']:'';

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
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 15, '...').'</td>
	                                <td class="line_height">'.$contact_no.'</td>
	                                <td class="line_height">'.$pre_balance.'</td>
	                                <td class="line_height">'.$cur_balance.'</td>
	                                <td class="line_height">'.$status_view.'</td>
	                                <td>
	                                	<a href="'.BASE_URL.'index.php/admin/payment/vendor_payment/'.$vendor_id.'" class="button_clr btn btn-info"><i class="icon-wallet"></i> Payment </a>
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
				}
				else
		    	{
		    		$status     = 0;
		        	$message    = 'Access denied';
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

		public function distributor_payment($param1="", $param2="", $param3="")
		{	
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$method = $this->input->post('method');

        	if($method == '_addDistributorPayment')
        	{
        		$distributor_id = $this->input->post('distributor_id');
        		$pay_id         = $this->input->post('pay_id');
				$amount         = $this->input->post('amount');
				$discount       = $this->input->post('discount');
				$entry_date     = $this->input->post('entry_date');
				$amt_type       = $this->input->post('amt_type');
				$description    = $this->input->post('description');
				$bank_name      = $this->input->post('bank_name');
				$cheque_no      = $this->input->post('cheque_no');
				$collect_date   = $this->input->post('collect_date');
				$penalty_amt    = $this->input->post('penalty_amt');
				$bank_charge    = $this->input->post('bank_charge');
				$cheque_status  = $this->input->post('cheque_status');

        		$error = FALSE;
			    $errors = array();
				$required = array('pay_id', 'distributor_id', 'amount', 'entry_date', 'amt_type');

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
			    	if(userAccess('distributors-receipt-add'))
			    	{
			    		$data = array(
					    	'distributor_id' => $distributor_id,
					    	'pay_id'         => $pay_id,
					    	'amount'         => $amount,
					    	'discount'       => $discount,
					    	'entry_date'     => $entry_date,
					    	'amt_type'       => $amt_type,
					    	'description'    => $description,
					    	'bank_name'      => $bank_name,
					    	'cheque_no'      => $cheque_no,
					    	'collect_date'   => $collect_date,
					    	'method'         => '_addDistributorPayment',
					    );

					    $data_save = avul_call(API_URL.'payment/api/distributor_payment',$data);

					    if($data_save['status'] == 1)
					    {
					    	$response['status']   = 1;
					        $response['message']  = $data_save['message']; 
					        $response['data']     = [];
					        $response['redirect'] = BASE_URL.'index.php/admin/payment/distributor_payment/'.$distributor_id;

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
				        $response['message'] = 'Access denied'; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
		    		}
			    }
        	}

        	else if($method == '_editDistributorPayment')
        	{
        		$auto_id        = $this->input->post('auto_id');
        		$distributor_id = $this->input->post('distributor_id');
        		$pay_id         = $this->input->post('pay_id');
				$amount         = $this->input->post('amount');
				$discount       = $this->input->post('discount');
				$entry_date     = $this->input->post('entry_date');
				$amt_type       = $this->input->post('amt_type');
				$description    = $this->input->post('description');
				$bank_name      = $this->input->post('bank_name');
				$cheque_no      = $this->input->post('cheque_no');
				$collect_date   = $this->input->post('collect_date');
				$penalty_amt    = $this->input->post('penalty_amt');
				$bank_charge    = $this->input->post('bank_charge');
				$cheque_status  = $this->input->post('cheque_status');

        		$error = FALSE;
			    $errors = array();
				$required = array('auto_id', 'pay_id', 'distributor_id', 'amount', 'entry_date', 'amt_type');

				if($amt_type == 2)
			    {
			    	array_push($required, 'bank_name', 'cheque_no', 'collect_date', 'cheque_status', 'description');
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
			    	$data = array(
			    		'auto_id'        => $auto_id,
				    	'distributor_id' => $distributor_id,
				    	'description'    => $description,
				    	'entry_date'     => $entry_date,
				    	'penalty_amt'    => $penalty_amt,
				    	'bank_charge'    => $bank_charge,
				    	'cheque_status'  => $cheque_status,
				    	'method'         => '_editDistributorPayment',
				    );

				    $data_save = avul_call(API_URL.'payment/api/distributor_payment',$data);

				    if($data_save['status'] == 1)
				    {
				    	$response['status']   = 1;
				        $response['message']  = $data_save['message']; 
				        $response['data']     = [];
				        $response['redirect'] = BASE_URL.'index.php/admin/payment/distributor_payment/'.$distributor_id;

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
            		'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
            		'distributor_id' => $random_val,
            		'method'         => '_listDistributorPaymentPaginate'
            	);

            	$data_list  = avul_call(API_URL.'payment/api/distributor_payment',$where);
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

	            		$payment_id     = !empty($value['payment_id'])?$value['payment_id']:'0';
	            		$invoice_no     = !empty($value['invoice_no'])?$value['invoice_no']:'0';
	            		$bill_code      = !empty($value['bill_code'])?$value['bill_code']:'';
	            		$bill_no        = !empty($value['bill_no'])?$value['bill_no']:'';
			            $pre_bal        = !empty($value['pre_bal'])?$value['pre_bal']:'0';
			            $cur_bal        = !empty($value['cur_bal'])?$value['cur_bal']:'0';
			            $amount         = !empty($value['amount'])?$value['amount']:'0';
			            $discount       = !empty($value['discount'])?$value['discount']:'0';
			            $payment_type   = !empty($value['payment_type'])?$value['payment_type']:'';
			            $amt_type       = !empty($value['amt_type'])?$value['amt_type']:'';
			            $amount_type    = !empty($value['amount_type'])?$value['amount_type']:'';
			            $collect_type   = !empty($value['collection_type'])?$value['collection_type']:'';
			            $cheque_process = !empty($value['cheque_process'])?$value['cheque_process']:'';
			            $penalty_amt    = !empty($value['penalty_amt'])?$value['penalty_amt']:'0';
			            $bank_charge    = !empty($value['bank_charge'])?$value['bank_charge']:'0';
			            $date           = !empty($value['date'])?$value['date']:'';
			            $time           = !empty($value['time'])?$value['time']:'';
			            $active_status  = !empty($value['status'])?$value['status']:'';

			            if($bill_code == 'PEN')
			            {
			            	$amount_value = $penalty_amt.' + '.$bank_charge;
			            }
			            else
			            {
			            	$amount_value = $amount.' + '.$discount;
			            }

			            if($collect_type == 1)
			            {
			            	$edit_bth = '<a href="'.BASE_URL.'index.php/admin/payment/distributor_payment/'.$random_val.'/Edit/'.$payment_id.'" class="button_clr btn btn-warning"><i class="ft-edit"></i> </a>';
			            }
			            else
			            {
			            	$edit_bth = '<a data-row="'.$i.'" data-id="'.$payment_id.'" data-value="admin" data-cntrl="payment" data-func="distributor_payment" class="button_clr btn btn-success"><i class="la la-check-square-o"></i></a>';
			            }

			            $delete_bth = '';
			            if(userAccess('distributors-receipt-delete') == TRUE)
			            {
			            	if($active_status != 0)
				            {
				            	$delete_bth = '<a data-row="'.$i.'" data-id="'.$payment_id.'" data-value="admin" data-cntrl="payment" data-func="distributor_payment" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> </a>';
				            }
				            else
				            {
				            	$delete_bth = '<a data-row="'.$i.'" data-id="" data-value="" data-cntrl="payment" data-func="" class="button_clr btn btn-danger" disabled="disabled"><i class="ft-trash-2"></i> </a>';	
				            }
			            }


					    if($cheque_process == 2)
			            {
			            	$table .= '
						    	<tr class="row_'.$i.'">
						    		<td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.$bill_no.'</td>
	                                <td class="line_height">'.$pre_bal.'</td>
	                                <td class="line_height">'.$amount_value.'</td>
	                                <td class="line_height">'.$cur_bal.'</td>
	                                <td class="line_height">'.$date.'</td>
	                                <td>'.$edit_bth.' '.$delete_bth.'</td>
	                            </tr>
						    ';
			            }
			            else
			            {
			            	$table .= '
						    	<tr>
						    		<td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.$bill_no.'</td>
	                                <td class="line_height">---</td>
	                                <td class="line_height">'.$amount_value.'</td>
	                                <td class="line_height">---</td>
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

        	else if($param1 == 'getBill_detail')
			{
				$pay_id = $this->input->post('pay_id');

				$where = array(
            		'payment_id' => $pay_id,
            		'method'     => '_detailDistributorPaymentBill'
            	);

            	$data_list  = avul_call(API_URL.'payment/api/distributor_payment',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	if(!empty($data_value))
            	{
            		$pay_id  = !empty($data_value[0]['pay_id'])?$data_value[0]['pay_id']:'';
		            $bill_id = !empty($data_value[0]['bill_id'])?$data_value[0]['bill_id']:'';
		            $bill_no = !empty($data_value[0]['bill_no'])?$data_value[0]['bill_no']:'';
		            $pre_bal = !empty($data_value[0]['pre_bal'])?$data_value[0]['pre_bal']:'0';
		            $cur_bal = !empty($data_value[0]['cur_bal'])?$data_value[0]['cur_bal']:'0';
		            $amount  = !empty($data_value[0]['amount'])?$data_value[0]['amount']:'0';
		            $bal_amt = !empty($data_value[0]['bal_amt'])?$data_value[0]['bal_amt']:'0';

		            $payment_data = array(
		            	'pay_id'  => $pay_id,
			            'bill_id' => $bill_id,
			            'bill_no' => $bill_no,
			            'pre_bal' => $pre_bal,
			            'cur_bal' => $cur_bal,
			            'amount'  => $amount,
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

        	else if($param1 == 'delete')
			{
				$id = $this->input->post('id');

				if(!empty($id))	
				{
					$data = array(
				    	'payment_id' => $id,
				    	'method'     => '_deleteDistributorPayment'
				    );

				    $data_save = avul_call(API_URL.'payment/api/distributor_payment',$data);

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

        	else
        	{
        		if($param2 == 'Edit')
        		{
        			$pay_whr = array(
	            		'payment_id' => $param3,
	            		'method'     => '_distributorPaymentDetails'
	            	);

	            	$pay_res = avul_call(API_URL.'payment/api/distributor_payment',$pay_whr);
	            	$pay_val = !empty($pay_res['data'])?$pay_res['data']:'';

	            	$page['pay_val']     = $pay_val;
	            	$page['page_action'] = 'Edit';
	            	$page['method']      = '_editDistributorPayment';
	            	$page['page_title']  = "Edit Distributor Receipt";
        		}
        		else
        		{
        			$page['pay_val']     = '';
        			$page['page_action'] = 'Add';
        			$page['method']      = '_addDistributorPayment';
        			$page['page_title']  = "Add Distributor Receipt";
        		}

        		$where = array(
            		'distributor_id' => $param1,
            		'method'         => '_listDistributorPaymentBill'
            	);

            	$data_list  = avul_call(API_URL.'payment/api/distributor_payment',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

        		$page['main_heading'] = "Receipt";
				$page['sub_heading']  = "Receipt";
				$page['pre_title']    = "List Distributor Receipt";
				$page['random_val']   = $param1;
				$page['payment_list'] = $data_value;
				$page['pre_menu']     = "index.php/admin/payment/list_distributor";
				$data['page_temp']    = $this->load->view('admin/payment/distributor_payment',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "distributor_payment";
				$this->bassthaya->load_admin_form_template($data);
        	}
		}

		public function list_distributor($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Distributor Receipt";
				$page['sub_heading']  = "Distributor Receipt";
				$page['page_title']   = "List Distributors Receipt";
				$data['page_temp']    = $this->load->view('admin/payment/list_distributor',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "distributor_payment";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('distributors-receipt-view'))
				{
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
	            		'offset'       => $_offset,
	            		'limit'        => $limit,
	            		'search'       => $search,
	            		'company_type' => 1,
	            		'company_id'   => 0,
	            		'method'       => '_listDistributorsPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'distributors/api/distributors',$where);
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
		            		$distributor_id  = !empty($value['distributor_id'])?$value['distributor_id']:'';
						    $company_name    = !empty($value['company_name'])?$value['company_name']:'';
						    $mobile          = !empty($value['mobile'])?$value['mobile']:'';
						    $current_balance = !empty($value['current_balance'])?$value['current_balance']:'0';
						    $available_limit = !empty($value['available_limit'])?$value['available_limit']:'0';
						    $active_status   = !empty($value['status'])?$value['status']:'';
						    $createdate      = !empty($value['createdate'])?$value['createdate']:'';

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
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 15, '...').'</td>
	                                <td class="line_height">'.$mobile.'</td>
	                                <td class="line_height">'.$available_limit.'</td>
	                                <td class="line_height">'.$current_balance.'</td>
	                                <td class="line_height">'.$status_view.'</td>
	                                <td>
	                                	<a href="'.BASE_URL.'index.php/admin/payment/distributor_payment/'.$distributor_id.'" class="button_clr btn btn-info"><i class="icon-wallet"></i> Receipt </a>
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
				}
				else
		    	{
		    		$status     = 0;
		        	$message    = 'Access denied';
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
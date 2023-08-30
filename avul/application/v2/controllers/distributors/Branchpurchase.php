<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Branchpurchase extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function br_overall_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');

			if($param1 == '')
			{
				$page['main_heading']  = "Branch Purchase";
				$page['sub_heading']   = "Branch Purchase";
				$page['page_title']    = "Overall Order";
				$page['pre_title']     = "Add Purchase";
				//$page['page_access']   = userAccess('distributors-order-view');
				$page['load_data']     = "";
				$page['function_name'] = "br_overall_order";
				$page['pre_menu']      = "index.php/distributors/branch_order/list_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/list_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_overall_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				//if(userAccess('distributors-order-view'))
				//{
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
                        'ref_id'         => $this->session->userdata('id'),
	            		'offset'         => $_offset,
	            		'limit'          => $limit,
	            		'search'         => $search,
	            		'financial_year' => $this->session->userdata('active_year'),
	            		'method'         => '_listPurchasePaginate'
	            	);
                   
	            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
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

		            		$po_id          = !empty($value['po_id'])?$value['po_id']:'';
				            $po_no          = !empty($value['po_no'])?$value['po_no']:'';
				            $distributor_id = !empty($value['distributor_id'])?$value['distributor_id']:'';
				            $company_name   = !empty($value['company_name'])?$value['company_name']:'';
				            $order_date     = !empty($value['order_date'])?$value['order_date']:'';
				            $order_status   = !empty($value['order_status'])?$value['order_status']:'';
				            $_ordered       = !empty($value['_ordered'])?$value['_ordered']:'';
				            $financial_year = !empty($value['financial_year'])?$value['financial_year']:'';
				            $bill           = !empty($value['bill'])?$value['bill']:'';
				            $invoice_no     = !empty($value['invoice_no'])?$value['invoice_no']:'---';
				            $published      = !empty($value['published'])?$value['published']:'';
				            $active_status  = !empty($value['status'])?$value['status']:'';
				            $createdate     = !empty($value['createdate'])?$value['createdate']:'';

						    if($active_status == '1')
			                {
			                	$status_view = '<span class="badge badge-success">Active</span>';
			                }
			                else
			                {
			                	$status_view = '<span class="badge badge-danger">In Active</span>';
			                }

			                // Order Status
						    if($order_status == '1')
						    {
						        $order_view = '<span class="badge badge-success">Success</span>';

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="distributors" data-cntrl="Branchpurchase" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
						    }
						    else if($order_status == '2')
						    {
						        $order_view = '<span class="badge badge-warning">Approved</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '3')
						    {
						        $order_view = '<span class="badge badge-primary">Packing</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '4')
						    {
						        $order_view = '<span class="badge badge-info">Invoice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '10')
						    {
						        $order_view = '<span class="badge badge-warning">Shipping</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '11')
						    {
						        $order_view = '<span class="badge badge-primary">Delivered</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '5')
						    {
						        $order_view = '<span class="badge badge-success">Complete</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '7')
						    {
						        $order_view = '<span class="badge badge-danger">Cancel Invice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else
						    {
						        $order_view = '<span class="badge badge-danger">Cancel</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    $edit   = '';
				            $view = '';
				        //   //  if(userAccess('distributors-order-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				         //   if(userAccess('distributors-order-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/distributors/Branchpurchase/br_overall_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                            //    if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	//endif;
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
				// }
				// else
		    	// {
		    	// 	$status     = 0;
		        // 	$message    = 'Access denied';
		        // 	$table      = '';
		        // 	$next       = '';
		        // 	$prev       = '';
		    	// }

            	$response['status']     = $status;  
                $response['result']     = $table;  
                $response['message']    = $message;
                $response['next']       = $next;
                $response['prev']       = $prev;
                echo json_encode($response);
                return;
			}

			else if($param1 == 'View')
			{
				$purchase_id = $param2;

				$where = array(
			    	'purchase_id' => $purchase_id,
			    	'view_type'   => 1,
			    	'method'      => '_viewDistributorPurchase'
			    );

			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				//$page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/distributors/branch_order/view_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/view_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_overall_order";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		public function br_success_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');

			if($param1 == '')
			{
				$page['main_heading']  = "Branch Purchase";
				$page['sub_heading']   = "Branch Purchase";
				$page['page_title']    = "Success Order";
				$page['pre_title']     = "Add Purchase";
				//$page['page_access']   = userAccess('distributors-order-view');
				$page['load_data']     = "1";
				$page['function_name'] = "br_success_order";
				$page['pre_menu']      = "index.php/distributors/branch_order/list_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/list_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_success_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				// if(userAccess('distributors-order-view'))
				// {
					$limit     = $this->input->post('limitval');
	            	$page      = $this->input->post('page');
	            	$search    = $this->input->post('search');
	            	$load_data = $this->input->post('load_data');
	            	$cur_page  = isset($page)?$page:'1';
	            	$_offset   = ($cur_page-1) * $limit;
	            	$nxt_page  = $cur_page + 1;
	            	$pre_page  = $cur_page - 1;

	            	$where = array(
                        'ref_id'         => $this->session->userdata('id'),
	            		'offset'         => $_offset,
	            		'limit'          => $limit,
	            		'search'         => $search,
	            		'load_data'      => $load_data,
	            		'financial_year' => $this->session->userdata('active_year'),
	            		'method'         => '_listPurchasePaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
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

		            		$po_id          = !empty($value['po_id'])?$value['po_id']:'';
				            $po_no          = !empty($value['po_no'])?$value['po_no']:'';
				            $distributor_id = !empty($value['distributor_id'])?$value['distributor_id']:'';
				            $company_name   = !empty($value['company_name'])?$value['company_name']:'';
				            $order_date     = !empty($value['order_date'])?$value['order_date']:'';
				            $order_status   = !empty($value['order_status'])?$value['order_status']:'';
				            $_ordered       = !empty($value['_ordered'])?$value['_ordered']:'';
				            $financial_year = !empty($value['financial_year'])?$value['financial_year']:'';
				            $bill           = !empty($value['bill'])?$value['bill']:'';
				            $invoice_no     = !empty($value['invoice_no'])?$value['invoice_no']:'---';
				            $published      = !empty($value['published'])?$value['published']:'';
				            $active_status  = !empty($value['status'])?$value['status']:'';
				            $createdate     = !empty($value['createdate'])?$value['createdate']:'';

						    if($active_status == '1')
			                {
			                	$status_view = '<span class="badge badge-success">Active</span>';
			                }
			                else
			                {
			                	$status_view = '<span class="badge badge-danger">In Active</span>';
			                }

			                // Order Status
						    if($order_status == '1')
						    {
						        $order_view = '<span class="badge badge-success">Success</span>';

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="distributors" data-cntrl="Branchpurchase" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
						    }
						    else if($order_status == '2')
						    {
						        $order_view = '<span class="badge badge-warning">Approved</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '3')
						    {
						        $order_view = '<span class="badge badge-primary">Packing</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '4')
						    {
						        $order_view = '<span class="badge badge-info">Invoice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '10')
						    {
						        $order_view = '<span class="badge badge-warning">Shipping</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '11')
						    {
						        $order_view = '<span class="badge badge-primary">Delivered</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '5')
						    {
						        $order_view = '<span class="badge badge-success">Complete</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '7')
						    {
						        $order_view = '<span class="badge badge-danger">Cancel Invice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else
						    {
						        $order_view = '<span class="badge badge-danger">Cancel</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    $edit = '';
				            $view = '';
				          //  if(userAccess('distributors-order-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            //if(userAccess('distributors-order-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/distributors/Branchpurchase/br_success_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                              // if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	//endif;
	                            $table .=' </tr>
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
				// }
				// else
	    		// {
	    		// 	$response['status']  = 0;
			    //     $response['message'] = 'Access denied'; 
			    //     $response['data']    = [];
			    //     echo json_encode($response);
			    //     return; 
	    		// }

            	$response['status']     = $status;  
                $response['result']     = $table;  
                $response['message']    = $message;
                $response['next']       = $next;
                $response['prev']       = $prev;
                echo json_encode($response);
                return;
			}

			else if($param1 == 'View')
			{
				$purchase_id = $param2;

				$where = array(
			    	'purchase_id' => $purchase_id,
			    	'view_type'   => 1,
			    	'method'      => '_viewDistributorPurchase'
			    );

			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				//$page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/distributors/branch_order/view_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/view_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_success_order";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		public function br_process_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');

			if($param1 == '')
			{
				$page['main_heading']  = "Distributor Order";
				$page['sub_heading']   = "Distributor Order";
				$page['page_title']    = "Approved Order";
				$page['pre_title']     = "Add Purchase";
				$page['load_data']     = "2";
				$page['function_name'] = "br_process_order";
				// $page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/distributors/branch_order/list_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/list_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_process_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				// if(userAccess('distributors-order-view'))
				// {
					$limit     = $this->input->post('limitval');
	            	$page      = $this->input->post('page');
	            	$search    = $this->input->post('search');
	            	$load_data = $this->input->post('load_data');
	            	$cur_page  = isset($page)?$page:'1';
	            	$_offset   = ($cur_page-1) * $limit;
	            	$nxt_page  = $cur_page + 1;
	            	$pre_page  = $cur_page - 1;

	            	$where = array(
	            		'ref_id'         => $this->session->userdata('id'),
	            		'offset'         => $_offset,
	            		'limit'          => $limit,
	            		'search'         => $search,
	            		'load_data'      => $load_data,
	            		'financial_year' => $this->session->userdata('active_year'),
	            		'method'         => '_listPurchasePaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
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

		            		$po_id          = !empty($value['po_id'])?$value['po_id']:'';
				            $po_no          = !empty($value['po_no'])?$value['po_no']:'';
				            $distributor_id = !empty($value['distributor_id'])?$value['distributor_id']:'';
				            $company_name   = !empty($value['company_name'])?$value['company_name']:'';
				            $order_date     = !empty($value['order_date'])?$value['order_date']:'';
				            $order_status   = !empty($value['order_status'])?$value['order_status']:'';
				            $_ordered       = !empty($value['_ordered'])?$value['_ordered']:'';
				            $financial_year = !empty($value['financial_year'])?$value['financial_year']:'';
				            $bill           = !empty($value['bill'])?$value['bill']:'';
				            $invoice_no     = !empty($value['invoice_no'])?$value['invoice_no']:'---';
				            $published      = !empty($value['published'])?$value['published']:'';
				            $active_status  = !empty($value['status'])?$value['status']:'';
				            $createdate     = !empty($value['createdate'])?$value['createdate']:'';

						    if($active_status == '1')
			                {
			                	$status_view = '<span class="badge badge-success">Active</span>';
			                }
			                else
			                {
			                	$status_view = '<span class="badge badge-danger">In Active</span>';
			                }

			                // Order Status
						    if($order_status == '1')
						    {
						        $order_view = '<span class="badge badge-success">Success</span>';

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="distributors" data-cntrl="Branchpurchase" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
						    }
						    else if($order_status == '2')
						    {
						        $order_view = '<span class="badge badge-warning">Approved</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '3')
						    {
						        $order_view = '<span class="badge badge-primary">Packing</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '4')
						    {
						        $order_view = '<span class="badge badge-info">Invoice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '10')
						    {
						        $order_view = '<span class="badge badge-warning">Shipping</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '11')
						    {
						        $order_view = '<span class="badge badge-primary">Delivered</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '5')
						    {
						        $order_view = '<span class="badge badge-success">Complete</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '7')
						    {
						        $order_view = '<span class="badge badge-danger">Cancel Invice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else
						    {
						        $order_view = '<span class="badge badge-danger">Cancel</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    $edit = '';
				            $view = '';
				            // if(userAccess('distributors-order-edit') == TRUE)
				            // {
				            	$edit = $order_btn;
				            // }
				            // if(userAccess('distributors-order-view') == TRUE)
				            // {
				            	$view = '<a href="'.BASE_URL.'index.php/distributors/Branchpurchase/br_process_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				          //  }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                // if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	//endif;
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
				// }
				// else
		    	// {
		    	// 	$status     = 0;
		        // 	$message    = 'Access denied';
		        // 	$table      = '';
		        // 	$next       = '';
		        // 	$prev       = '';
		    	// }

            	$response['status']     = $status;  
                $response['result']     = $table;  
                $response['message']    = $message;
                $response['next']       = $next;
                $response['prev']       = $prev;
                echo json_encode($response);
                return;
			}

			else if($param1 == 'View')
			{
				$purchase_id = $param2;

				$where = array(
			    	'purchase_id' => $purchase_id,
			    	'view_type'   => 1,
			    	'method'      => '_viewDistributorPurchase'
			    );
				// function zerovalidation(){
					// 	var element=document.getElementById("dis_product_qty").value;
					// 	console.log(element);
						
					// }
			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				// $page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/distributors/branch_order/view_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/view_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_process_order";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		public function br_packing_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');

			if($param1 == '')
			{
				$page['main_heading']  = "Distributor Order";
				$page['sub_heading']   = "Distributor Order";
				$page['page_title']    = "Packing Order";
				$page['pre_title']     = "Add Purchase";
				$page['load_data']     = "3";
				// $page['page_access']   = userAccess('distributors-order-view');
				$page['function_name'] = "br_packing_order";
				$page['pre_menu']      = "index.php/distributors/branch_order/list_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/list_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_packing_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				// if(userAccess('distributors-order-view'))
				// {
					$limit     = $this->input->post('limitval');
	            	$page      = $this->input->post('page');
	            	$search    = $this->input->post('search');
	            	$load_data = $this->input->post('load_data');
	            	$cur_page  = isset($page)?$page:'1';
	            	$_offset   = ($cur_page-1) * $limit;
	            	$nxt_page  = $cur_page + 1;
	            	$pre_page  = $cur_page - 1;

	            	$where = array(
	            		'ref_id'         => $this->session->userdata('id'),
	            		'offset'         => $_offset,
	            		'limit'          => $limit,
	            		'search'         => $search,
	            		'load_data'      => $load_data,
	            		'financial_year' => $this->session->userdata('active_year'),
	            		'method'         => '_listPurchasePaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
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

		            		$po_id          = !empty($value['po_id'])?$value['po_id']:'';
				            $po_no          = !empty($value['po_no'])?$value['po_no']:'';
				            $distributor_id = !empty($value['distributor_id'])?$value['distributor_id']:'';
				            $company_name   = !empty($value['company_name'])?$value['company_name']:'';
				            $order_date     = !empty($value['order_date'])?$value['order_date']:'';
				            $order_status   = !empty($value['order_status'])?$value['order_status']:'';
				            $_ordered       = !empty($value['_ordered'])?$value['_ordered']:'';
				            $financial_year = !empty($value['financial_year'])?$value['financial_year']:'';
				            $bill           = !empty($value['bill'])?$value['bill']:'';
				            $invoice_no     = !empty($value['invoice_no'])?$value['invoice_no']:'---';
				            $published      = !empty($value['published'])?$value['published']:'';
				            $active_status  = !empty($value['status'])?$value['status']:'';
				            $createdate     = !empty($value['createdate'])?$value['createdate']:'';

						    if($active_status == '1')
			                {
			                	$status_view = '<span class="badge badge-success">Active</span>';
			                }
			                else
			                {
			                	$status_view = '<span class="badge badge-danger">In Active</span>';
			                }

			                // Order Status
						    if($order_status == '1')
						    {
						        $order_view = '<span class="badge badge-success">Success</span>';

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="distributors" data-cntrl="Branchpurchase" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
						    }
						    else if($order_status == '2')
						    {
						        $order_view = '<span class="badge badge-warning">Approved</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '3')
						    {
						        $order_view = '<span class="badge badge-primary">Packing</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '4')
						    {
						        $order_view = '<span class="badge badge-info">Invoice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '10')
						    {
						        $order_view = '<span class="badge badge-warning">Shipping</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '11')
						    {
						        $order_view = '<span class="badge badge-primary">Delivered</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '5')
						    {
						        $order_view = '<span class="badge badge-success">Complete</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '7')
						    {
						        $order_view = '<span class="badge badge-danger">Cancel Invice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else
						    {
						        $order_view = '<span class="badge badge-danger">Cancel</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    $edit   = '';
				            $view = '';
				            // if(userAccess('distributors-order-edit') == TRUE)
				            // {
				            	$edit = $order_btn;
				            // }
				            // if(userAccess('distributors-order-view') == TRUE)
				            // {
				            	$view = '<a href="'.BASE_URL.'index.php/distributors/Branchpurchase/br_packing_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				           // }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                // if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	//endif;
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
				// }
				// else
		    	// {
		    	// 	$status     = 0;
		        // 	$message    = 'Access denied';
		        // 	$table      = '';
		        // 	$next       = '';
		        // 	$prev       = '';
		    	// }

            	$response['status']     = $status;  
                $response['result']     = $table;  
                $response['message']    = $message;
                $response['next']       = $next;
                $response['prev']       = $prev;
                echo json_encode($response);
                return;
			}

			else if($param1 == 'View')
			{
				$purchase_id = $param2;

				$where = array(
			    	'purchase_id' => $purchase_id,
			    	'view_type'   => 1,
					'ref_id'      => $this->session->userdata('id'),
			    	'method'      => '_viewDistributorPurchase'
			    );

			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				$page['pre_menu']      = "index.php/distributors/branch_order/view_purchase";
				// $page['page_access']   = userAccess('distributors-order-view');
				$data['page_temp']     = $this->load->view('distributors/branch_order/view_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_packing_order";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		public function br_invoice_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');

			if($param1 == '')
			{
				$page['main_heading']  = "Distributor Order";
				$page['sub_heading']   = "Distributor Order";
				$page['page_title']    = "Invoice Order";
				$page['pre_title']     = "Add Purchase";
				$page['load_data']     = "4";
				// $page['page_access']   = userAccess('distributors-order-view');
				$page['function_name'] = "br_invoice_order";
				$page['pre_menu']      = "index.php/distributors/branch_order/list_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/list_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_invoice_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				// if(userAccess('distributors-order-view'))
				// {
					$limit     = $this->input->post('limitval');
	            	$page      = $this->input->post('page');
	            	$search    = $this->input->post('search');
	            	$load_data = $this->input->post('load_data');
	            	$cur_page  = isset($page)?$page:'1';
	            	$_offset   = ($cur_page-1) * $limit;
	            	$nxt_page  = $cur_page + 1;
	            	$pre_page  = $cur_page - 1;

	            	$where = array(
	            		'ref_id'         => $this->session->userdata('id'),
	            		'offset'         => $_offset,
	            		'limit'          => $limit,
	            		'search'         => $search,
	            		'load_data'      => $load_data,
	            		'financial_year' => $this->session->userdata('active_year'),
	            		'method'         => '_listPurchasePaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
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

		            		$po_id          = !empty($value['po_id'])?$value['po_id']:'';
				            $po_no          = !empty($value['po_no'])?$value['po_no']:'';
				            $distributor_id = !empty($value['distributor_id'])?$value['distributor_id']:'';
				            $company_name   = !empty($value['company_name'])?$value['company_name']:'';
				            $order_date     = !empty($value['order_date'])?$value['order_date']:'';
				            $order_status   = !empty($value['order_status'])?$value['order_status']:'';
				            $_ordered       = !empty($value['_ordered'])?$value['_ordered']:'';
				            $financial_year = !empty($value['financial_year'])?$value['financial_year']:'';
				            $bill           = !empty($value['bill'])?$value['bill']:'';
				            $invoice_no     = !empty($value['invoice_no'])?$value['invoice_no']:'---';
				            $published      = !empty($value['published'])?$value['published']:'';
				            $active_status  = !empty($value['status'])?$value['status']:'';
				            $createdate     = !empty($value['createdate'])?$value['createdate']:'';

						    if($active_status == '1')
			                {
			                	$status_view = '<span class="badge badge-success">Active</span>';
			                }
			                else
			                {
			                	$status_view = '<span class="badge badge-danger">In Active</span>';
			                }

			                // Order Status
						    if($order_status == '1')
						    {
						        $order_view = '<span class="badge badge-success">Success</span>';

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="distributors" data-cntrl="Branchpurchase" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
						    }
						    else if($order_status == '2')
						    {
						        $order_view = '<span class="badge badge-warning">Approved</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '3')
						    {
						        $order_view = '<span class="badge badge-primary">Packing</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '4')
						    {
						        $order_view = '<span class="badge badge-info">Invoice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '10')
						    {
						        $order_view = '<span class="badge badge-warning">Shipping</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '11')
						    {
						        $order_view = '<span class="badge badge-primary">Delivered</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '5')
						    {
						        $order_view = '<span class="badge badge-success">Complete</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '7')
						    {
						        $order_view = '<span class="badge badge-danger">Cancel Invice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else
						    {
						        $order_view = '<span class="badge badge-danger">Cancel</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    $edit = '';
				            $view = '';
				            // if(userAccess('distributors-order-edit') == TRUE)
				            // {
				            	$edit = $order_btn;
				            // }
				            // if(userAccess('distributors-order-view') == TRUE)
				            // {
				            	$view = '<a href="'.BASE_URL.'index.php/distributors/Branchpurchase/br_invoice_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				           // }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                // if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	//endif;
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
				// }
				// else
				// {
				// 	$status     = 0;
		        // 	$message    = 'Access denied';
		        // 	$table      = '';
		        // 	$next       = '';
		        // 	$prev       = '';
				// }

            	$response['status']     = $status;  
                $response['result']     = $table;  
                $response['message']    = $message;
                $response['next']       = $next;
                $response['prev']       = $prev;
                echo json_encode($response);
                return;
			}

			else if($param1 == 'View')
			{
				$purchase_id = $param2;

				$where = array(
			    	'purchase_id' => $purchase_id,
			    	'view_type'   => 1,
			    	'method'      => '_viewDistributorPurchase'
			    );

			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				// $page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/distributors/branch_order/view_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/view_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_invoice_order";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		public function br_shipping_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');

			if($param1 == '')
			{
				$page['main_heading']  = "Distributor Order";
				$page['sub_heading']   = "Distributor Order";
				$page['page_title']    = "Shipping Order";
				$page['pre_title']     = "Add Purchase";
				$page['load_data']     = "10";
				// $page['page_access']   = userAccess('distributors-order-view');
				$page['function_name'] = "br_shipping_order";
				$page['pre_menu']      = "index.php/distributors/branch_order/list_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/list_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_shipping_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				// if(userAccess('distributors-order-view'))
				// {
					$limit     = $this->input->post('limitval');
	            	$page      = $this->input->post('page');
	            	$search    = $this->input->post('search');
	            	$load_data = $this->input->post('load_data');
	            	$cur_page  = isset($page)?$page:'1';
	            	$_offset   = ($cur_page-1) * $limit;
	            	$nxt_page  = $cur_page + 1;
	            	$pre_page  = $cur_page - 1;

	            	$where = array(
	            		'ref_id'         => $this->session->userdata('id'),
	            		'offset'         => $_offset,
	            		'limit'          => $limit,
	            		'search'         => $search,
	            		'load_data'      => $load_data,
	            		'financial_year' => $this->session->userdata('active_year'),
	            		'method'         => '_listPurchasePaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
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

		            		$po_id          = !empty($value['po_id'])?$value['po_id']:'';
				            $po_no          = !empty($value['po_no'])?$value['po_no']:'';
				            $distributor_id = !empty($value['distributor_id'])?$value['distributor_id']:'';
				            $company_name   = !empty($value['company_name'])?$value['company_name']:'';
				            $order_date     = !empty($value['order_date'])?$value['order_date']:'';
				            $order_status   = !empty($value['order_status'])?$value['order_status']:'';
				            $_ordered       = !empty($value['_ordered'])?$value['_ordered']:'';
				            $financial_year = !empty($value['financial_year'])?$value['financial_year']:'';
				            $bill           = !empty($value['bill'])?$value['bill']:'';
				            $invoice_no     = !empty($value['invoice_no'])?$value['invoice_no']:'---';
				            $published      = !empty($value['published'])?$value['published']:'';
				            $active_status  = !empty($value['status'])?$value['status']:'';
				            $createdate     = !empty($value['createdate'])?$value['createdate']:'';

						    if($active_status == '1')
			                {
			                	$status_view = '<span class="badge badge-success">Active</span>';
			                }
			                else
			                {
			                	$status_view = '<span class="badge badge-danger">In Active</span>';
			                }

			                // Order Status
						    if($order_status == '1')
						    {
						        $order_view = '<span class="badge badge-success">Success</span>';

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="distributors" data-cntrl="Branchpurchase" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
						    }
						    else if($order_status == '2')
						    {
						        $order_view = '<span class="badge badge-warning">Approved</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '3')
						    {
						        $order_view = '<span class="badge badge-primary">Packing</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '4')
						    {
						        $order_view = '<span class="badge badge-info">Invoice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '10')
						    {
						        $order_view = '<span class="badge badge-warning">Shipping</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '11')
						    {
						        $order_view = '<span class="badge badge-primary">Delivered</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '5')
						    {
						        $order_view = '<span class="badge badge-success">Complete</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '7')
						    {
						        $order_view = '<span class="badge badge-danger">Cancel Invice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else
						    {
						        $order_view = '<span class="badge badge-danger">Cancel</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    $edit = '';
				            $view = '';
				            // if(userAccess('distributors-order-edit') == TRUE)
				            // {
				            	$edit = $order_btn;
				            // }
				            // if(userAccess('distributors-order-view') == TRUE)
				            // {
				            	$view = '<a href="'.BASE_URL.'index.php/distributors/Branchpurchase/br_shipping_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				          //  }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                // if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	//endif;
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
				// }
				// else
				// {
				// 	$status     = 0;
		        // 	$message    = 'Access denied';
		        // 	$table      = '';
		        // 	$next       = '';
		        // 	$prev       = '';
				// }

            	$response['status']     = $status;  
                $response['result']     = $table;  
                $response['message']    = $message;
                $response['next']       = $next;
                $response['prev']       = $prev;
                echo json_encode($response);
                return;
			}

			else if($param1 == 'View')
			{
				$purchase_id = $param2;

				$where = array(
			    	'purchase_id' => $purchase_id,
			    	'view_type'   => 1,
			    	'method'      => '_viewDistributorPurchase'
			    );

			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				// $page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/distributors/branch_order/view_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/view_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_shipping_order";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		public function br_delivery_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');

			if($param1 == '')
			{
				$page['main_heading']  = "Distributor Order";
				$page['sub_heading']   = "Distributor Order";
				$page['page_title']    = "Delivered Order";
				$page['pre_title']     = "Add Purchase";
				$page['load_data']     = "11";
				
				$page['function_name'] = "br_delivery_order";
				$page['pre_menu']      = "index.php/distributors/branch_order/list_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/list_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_delivery_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				
					$limit     = $this->input->post('limitval');
	            	$page      = $this->input->post('page');
	            	$search    = $this->input->post('search');
	            	$load_data = $this->input->post('load_data');
	            	$cur_page  = isset($page)?$page:'1';
	            	$_offset   = ($cur_page-1) * $limit;
	            	$nxt_page  = $cur_page + 1;
	            	$pre_page  = $cur_page - 1;

	            	$where = array(
	            		'ref_id'         => $this->session->userdata('id'),
	            		'offset'         => $_offset,
	            		'limit'          => $limit,
	            		'search'         => $search,
	            		'load_data'      => $load_data,
	            		'financial_year' => $this->session->userdata('active_year'),
	            		'method'         => '_listPurchasePaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
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

		            		$po_id          = !empty($value['po_id'])?$value['po_id']:'';
				            $po_no          = !empty($value['po_no'])?$value['po_no']:'';
				            $distributor_id = !empty($value['distributor_id'])?$value['distributor_id']:'';
				            $company_name   = !empty($value['company_name'])?$value['company_name']:'';
				            $order_date     = !empty($value['order_date'])?$value['order_date']:'';
				            $order_status   = !empty($value['order_status'])?$value['order_status']:'';
				            $_ordered       = !empty($value['_ordered'])?$value['_ordered']:'';
				            $financial_year = !empty($value['financial_year'])?$value['financial_year']:'';
				            $bill           = !empty($value['bill'])?$value['bill']:'';
				            $invoice_no     = !empty($value['invoice_no'])?$value['invoice_no']:'---';
				            $published      = !empty($value['published'])?$value['published']:'';
				            $active_status  = !empty($value['status'])?$value['status']:'';
				            $createdate     = !empty($value['createdate'])?$value['createdate']:'';

						    if($active_status == '1')
			                {
			                	$status_view = '<span class="badge badge-success">Active</span>';
			                }
			                else
			                {
			                	$status_view = '<span class="badge badge-danger">In Active</span>';
			                }

			                // Order Status
						    if($order_status == '1')
						    {
						        $order_view = '<span class="badge badge-success">Success</span>';

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="distributors" data-cntrl="Branchpurchase" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
						    }
						    else if($order_status == '2')
						    {
						        $order_view = '<span class="badge badge-warning">Approved</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '3')
						    {
						        $order_view = '<span class="badge badge-primary">Packing</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '4')
						    {
						        $order_view = '<span class="badge badge-info">Invoice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '10')
						    {
						        $order_view = '<span class="badge badge-warning">Shipping</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '11')
						    {
						        $order_view = '<span class="badge badge-primary">Delivered</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '5')
						    {
						        $order_view = '<span class="badge badge-success">Complete</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '7')
						    {
						        $order_view = '<span class="badge badge-danger">Cancel Invice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else
						    {
						        $order_view = '<span class="badge badge-danger">Cancel</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    $edit = '';
				            $view = '';
				            
				            	$edit = $order_btn;
				            
				            	$view = '<a href="'.BASE_URL.'index.php/distributors/Branchpurchase/br_delivery_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				        

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                               
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                       
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

			else if($param1 == 'View')
			{
				$purchase_id = $param2;

				$where = array(
			    	'purchase_id' => $purchase_id,
			    	'view_type'   => 1,
			    	'method'      => '_viewDistributorPurchase'
			    );

			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				// $page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/distributors/branch_order/view_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/view_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_delivery_order";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		public function br_complete_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');

			if($param1 == '')
			{
				$page['main_heading']  = "Distributor Order";
				$page['sub_heading']   = "Distributor Order";
				$page['page_title']    = "Complete Order";
				$page['pre_title']     = "Add Purchase";
				$page['load_data']     = "5";
				// $page['page_access']   = userAccess('distributors-order-view');
				$page['function_name'] = "br_complete_order";
				$page['pre_menu']      = "index.php/distributors/branch_order/list_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/list_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_complete_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				// if(userAccess('distributors-order-view'))
				// {	
					$limit     = $this->input->post('limitval');
	            	$page      = $this->input->post('page');
	            	$search    = $this->input->post('search');
	            	$load_data = $this->input->post('load_data');
	            	$cur_page  = isset($page)?$page:'1';
	            	$_offset   = ($cur_page-1) * $limit;
	            	$nxt_page  = $cur_page + 1;
	            	$pre_page  = $cur_page - 1;

	            	$where = array(
	            		'ref_id'         => $this->session->userdata('id'),
	            		'offset'         => $_offset,
	            		'limit'          => $limit,
	            		'search'         => $search,
	            		'load_data'      => $load_data,
	            		'financial_year' => $this->session->userdata('active_year'),
	            		'method'         => '_listPurchasePaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
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

		            		$po_id          = !empty($value['po_id'])?$value['po_id']:'';
				            $po_no          = !empty($value['po_no'])?$value['po_no']:'';
				            $distributor_id = !empty($value['distributor_id'])?$value['distributor_id']:'';
				            $company_name   = !empty($value['company_name'])?$value['company_name']:'';
				            $order_date     = !empty($value['order_date'])?$value['order_date']:'';
				            $order_status   = !empty($value['order_status'])?$value['order_status']:'';
				            $_ordered       = !empty($value['_ordered'])?$value['_ordered']:'';
				            $financial_year = !empty($value['financial_year'])?$value['financial_year']:'';
				            $bill           = !empty($value['bill'])?$value['bill']:'';
				            $invoice_no     = !empty($value['invoice_no'])?$value['invoice_no']:'---';
				            $published      = !empty($value['published'])?$value['published']:'';
				            $active_status  = !empty($value['status'])?$value['status']:'';
				            $createdate     = !empty($value['createdate'])?$value['createdate']:'';

						    if($active_status == '1')
			                {
			                	$status_view = '<span class="badge badge-success">Active</span>';
			                }
			                else
			                {
			                	$status_view = '<span class="badge badge-danger">In Active</span>';
			                }

			                // Order Status
						    if($order_status == '1')
						    {
						        $order_view = '<span class="badge badge-success">Success</span>';

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="distributors" data-cntrl="Branchpurchase" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
						    }
						    else if($order_status == '2')
						    {
						        $order_view = '<span class="badge badge-warning">Approved</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '3')
						    {
						        $order_view = '<span class="badge badge-primary">Packing</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '4')
						    {
						        $order_view = '<span class="badge badge-info">Invoice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '10')
						    {
						        $order_view = '<span class="badge badge-warning">Shipping</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '11')
						    {
						        $order_view = '<span class="badge badge-primary">Delivered</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '5')
						    {
						        $order_view = '<span class="badge badge-success">Complete</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '7')
						    {
						        $order_view = '<span class="badge badge-danger">Cancel Invice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else
						    {
						        $order_view = '<span class="badge badge-danger">Cancel</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    $edit = '';
				            $view = '';
				            // if(userAccess('distributors-order-edit') == TRUE)
				            // {
				            	$edit = $order_btn;
				            // }
				            // if(userAccess('distributors-order-view') == TRUE)
				            // {
				            	$view = '<a href="'.BASE_URL.'index.php/distributors/Branchpurchase/br_complete_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				           // }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                // if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        //	endif;
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
				// }
				// else
		    	// {
		    	// 	$status     = 0;
		        // 	$message    = 'Access denied';
		        // 	$table      = '';
		        // 	$next       = '';
		        // 	$prev       = '';
		    	// }

            	$response['status']     = $status;  
                $response['result']     = $table;  
                $response['message']    = $message;
                $response['next']       = $next;
                $response['prev']       = $prev;
                echo json_encode($response);
                return;
			}

			else if($param1 == 'View')
			{
				$purchase_id = $param2;

				$where = array(
			    	'purchase_id' => $purchase_id,
			    	'view_type'   => 1,
			    	'method'      => '_viewDistributorPurchase'
			    );

			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				// $page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/distributors/branch_order/view_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/view_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_complete_order";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		public function br_cancle_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');

			if($param1 == '')
			{
				$page['main_heading']  = "Distributor Order";
				$page['sub_heading']   = "Distributor Order";
				$page['page_title']    = "Cancel Order";
				$page['pre_title']     = "Add Purchase";
				$page['load_data']     = "8";
				// $page['page_access']   = userAccess('distributors-order-view');
				$page['function_name'] = "br_cancle_order";
				$page['pre_menu']      = "index.php/distributors/branch_order/list_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/list_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_cancle_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				// if(userAccess('distributors-order-view'))
				// {
					$limit     = $this->input->post('limitval');
	            	$page      = $this->input->post('page');
	            	$search    = $this->input->post('search');
	            	$load_data = $this->input->post('load_data');
	            	$cur_page  = isset($page)?$page:'1';
	            	$_offset   = ($cur_page-1) * $limit;
	            	$nxt_page  = $cur_page + 1;
	            	$pre_page  = $cur_page - 1;

	            	$where = array(
	            		'ref_id'         => $this->session->userdata('id'),
	            		'offset'         => $_offset,
	            		'limit'          => $limit,
	            		'search'         => $search,
	            		'load_data'      => $load_data,
	            		'financial_year' => $this->session->userdata('active_year'),
	            		'method'         => '_listPurchasePaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
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

		            		$po_id          = !empty($value['po_id'])?$value['po_id']:'';
				            $po_no          = !empty($value['po_no'])?$value['po_no']:'';
				            $distributor_id = !empty($value['distributor_id'])?$value['distributor_id']:'';
				            $company_name   = !empty($value['company_name'])?$value['company_name']:'';
				            $order_date     = !empty($value['order_date'])?$value['order_date']:'';
				            $order_status   = !empty($value['order_status'])?$value['order_status']:'';
				            $_ordered       = !empty($value['_ordered'])?$value['_ordered']:'';
				            $financial_year = !empty($value['financial_year'])?$value['financial_year']:'';
				            $bill           = !empty($value['bill'])?$value['bill']:'';
				            $invoice_no     = !empty($value['invoice_no'])?$value['invoice_no']:'---';
				            $published      = !empty($value['published'])?$value['published']:'';
				            $active_status  = !empty($value['status'])?$value['status']:'';
				            $createdate     = !empty($value['createdate'])?$value['createdate']:'';

						    if($active_status == '1')
			                {
			                	$status_view = '<span class="badge badge-success">Active</span>';
			                }
			                else
			                {
			                	$status_view = '<span class="badge badge-danger">In Active</span>';
			                }

			                // Order Status
						    if($order_status == '1')
						    {
						        $order_view = '<span class="badge badge-success">Success</span>';

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="Branchpurchase" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
						    }
						    else if($order_status == '2')
						    {
						        $order_view = '<span class="badge badge-warning">Approved</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '3')
						    {
						        $order_view = '<span class="badge badge-primary">Packing</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '4')
						    {
						        $order_view = '<span class="badge badge-info">Invoice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '10')
						    {
						        $order_view = '<span class="badge badge-warning">Shipping</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '11')
						    {
						        $order_view = '<span class="badge badge-primary">Delivered</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '5')
						    {
						        $order_view = '<span class="badge badge-success">Complete</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '7')
						    {
						        $order_view = '<span class="badge badge-danger">Cancel Invice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else
						    {
						        $order_view = '<span class="badge badge-danger">Cancel</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    $edit   = '';
				            $view = '';
				            // if(userAccess('distributors-order-edit') == TRUE)
				            // {
				            	$edit = $order_btn;
				            // }
				            // if(userAccess('distributors-order-view') == TRUE)
				            // {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/Branchpurchase/br_cancle_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				          //  }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                // if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	//endif;
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
				// }
				// else
	    		// {
	    		// 	$response['status']  = 0;
			    //     $response['message'] = 'Access denied'; 
			    //     $response['data']    = [];
			    //     echo json_encode($response);
			    //     return; 
	    		// }

            	$response['status']     = $status;  
                $response['result']     = $table;  
                $response['message']    = $message;
                $response['next']       = $next;
                $response['prev']       = $prev;
                echo json_encode($response);
                return;
			}

			else if($param1 == 'View')
			{
				$purchase_id = $param2;

				$where = array(
			    	'purchase_id' => $purchase_id,
			    	'view_type'   => 1,
			    	'method'      => '_viewDistributorPurchase'
			    );

			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				// $page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/distributors/branch_order/view_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/view_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_cancle_order";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		public function br_cancle_invoice($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');

			if($param1 == '')
			{
				$page['main_heading']  = "Distributor Order";
				$page['sub_heading']   = "Distributor Order";
				$page['page_title']    = "Cancel Invoice";
				$page['pre_title']     = "Add Purchase";
				$page['load_data']     = "7";
				$page['function_name'] = "br_cancle_invoice";
				$page['pre_menu']      = "index.php/distributors/branch_order/list_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/list_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_cancle_invoice";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
                $limit     = $this->input->post('limitval');
            	$page      = $this->input->post('page');
            	$search    = $this->input->post('search');
            	$load_data = $this->input->post('load_data');
            	$cur_page  = isset($page)?$page:'1';
            	$_offset   = ($cur_page-1) * $limit;
            	$nxt_page  = $cur_page + 1;
            	$pre_page  = $cur_page - 1;

            	$where = array(
            		'ref_id'         => $this->session->userdata('id'),
	            	'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
            		'load_data'      => $load_data,
            		'financial_year' => $this->session->userdata('active_year'),
            		'method'         => '_listPurchasePaginate'
            	);

            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
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

	            		$po_id          = !empty($value['po_id'])?$value['po_id']:'';
			            $po_no          = !empty($value['po_no'])?$value['po_no']:'';
			            $distributor_id = !empty($value['distributor_id'])?$value['distributor_id']:'';
			            $company_name   = !empty($value['company_name'])?$value['company_name']:'';
			            $order_date     = !empty($value['order_date'])?$value['order_date']:'';
			            $order_status   = !empty($value['order_status'])?$value['order_status']:'';
			            $_ordered       = !empty($value['_ordered'])?$value['_ordered']:'';
			            $financial_year = !empty($value['financial_year'])?$value['financial_year']:'';
			            $bill           = !empty($value['bill'])?$value['bill']:'';
			            $invoice_no     = !empty($value['invoice_no'])?$value['invoice_no']:'---';
			            $published      = !empty($value['published'])?$value['published']:'';
			            $active_status  = !empty($value['status'])?$value['status']:'';
			            $createdate     = !empty($value['createdate'])?$value['createdate']:'';

					    if($active_status == '1')
		                {
		                	$status_view = '<span class="badge badge-success">Active</span>';
		                }
		                else
		                {
		                	$status_view = '<span class="badge badge-danger">In Active</span>';
		                }

		                // Order Status
					    if($order_status == '1')
					    {
					        $order_view = '<span class="badge badge-success">Success</span>';

					        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="distributors" data-cntrl="Branchpurchase" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
					    }
					    else if($order_status == '2')
						    {
						        $order_view = '<span class="badge badge-warning">Approved</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '3')
						    {
						        $order_view = '<span class="badge badge-primary">Packing</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '4')
						    {
						        $order_view = '<span class="badge badge-info">Invoice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '10')
						    {
						        $order_view = '<span class="badge badge-warning">Shipping</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '11')
						    {
						        $order_view = '<span class="badge badge-primary">Delivered</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

						    else if($order_status == '5')
						    {
						        $order_view = '<span class="badge badge-success">Complete</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '7')
						    {
						        $order_view = '<span class="badge badge-danger">Cancel Invice</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else
						    {
						        $order_view = '<span class="badge badge-danger">Cancel</span>';

						        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }

					    $table .= '
					    	<tr>
                                <td class="line_height">'.$i.'</td>
                                <td class="line_height">'.$po_no.'</td>
                                <td class="line_height">'.$invoice_no.'</td>
                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
                                <td class="line_height">'.$order_view.'</td>
                                <td>
                                	'.$order_btn.'
                                	<a href="'.BASE_URL.'index.php/distributors/Branchpurchase/br_cancle_invoice/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>
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

			else if($param1 == 'View')
			{
				$purchase_id = $param2;

				$where = array(
			    	'purchase_id' => $purchase_id,
			    	'view_type'   => 1,
			    	'method'      => '_viewDistributorPurchase'
			    );

			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				$page['pre_menu']      = "index.php/distributors/branch_order/view_purchase";
				$data['page_temp']     = $this->load->view('distributors/branch_order/view_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_cancle_invoice";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		public function order_process($param1="", $param2="", $param3="")
		{  
             if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$method = $this->input->post('method');

			if($method == 'changeOrder_status')
			{
				$order_id = $this->input->post('order_id');

				$order_data = array(
		    		'auto_id'  => $order_id,
					'progress' => '2',
                    'ref_id'   => $this->session->userdata('id'),
					'method'   => '_updateOrderProgress',
				);

				$data_save = avul_call(API_URL.'distributorpurchase/api/order_process',$order_data);

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

			else if($method == '_changePackProcess')
			{
				$error    = FALSE;
				$required = array('order_id');
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
			    	$order_id = $this->input->post('order_id');

			    	$order_data = array(
			    		'auto_id' => $order_id,
						'ref_id'   => $this->session->userdata('id'),
						'method'  => '_changePackProcess',
					);
				
					$data_save = avul_call(API_URL.'distributorpurchase/api/order_process',$order_data);

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

			else if($method == '_deletePackStatus')
			{
    			$error    = FALSE;
				$required = array('order_id');
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
			    	$order_id = $this->input->post('order_id');

			    	$order_data = array(
			    		'auto_id' => $order_id,
						'method'  => '_deletePackStatus',
					);

					$data_save = avul_call(API_URL.'distributorpurchase/api/order_process',$order_data);

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

			if($param1 == 'changeOrder_process')
			{
				$error        = FALSE;
				$order_id     = $this->input->post('order_id');
				$invoice_id   = $this->input->post('invoice_id');
			    $order_status = $this->input->post('order_status');
			    $message      = $this->input->post('message');
			    $length       = $this->input->post('length');
			    $breadth      = $this->input->post('breadth');
			    $height       = $this->input->post('height');
			    $weight       = $this->input->post('weight');
				
				$required = array('order_id', 'order_status');
				if($order_status == 8)
				{
					array_push($required, 'message');
				}
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
			    	$order_data = array(
						'ref_id'   => $this->session->userdata('id'), 
			    		'auto_id'    => $order_id,
			    		'invoice_id' => $invoice_id,
						'progress'   => $order_status,
						'reason'     => $message,
						'length'     => $length,
						'breadth'    => $breadth,
						'height'     => $height,
						'weight'     => $weight,
						'method'     => '_updateOrderProgress',
					);
					
					$data_save = avul_call(API_URL.'distributorpurchase/api/order_process',$order_data);

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
		
			else if($param1 == 'order_update')
			{	
				$error = FALSE;
				$id    = $this->input->post('id');
				$rate  = $this->input->post('rate');
				$qty   = $this->input->post('qty');

				$required = array('id', 'rate', 'qty');
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
			    	$order_data = array(
			    		'auto_id'  => $id,
						'price'    => $rate,
						'quantity' => $qty,
						'method'   => '_updateOrderDetails',
					);

					$update = avul_call(API_URL.'distributorpurchase/api/order_process',$order_data);

					if($update)
				    {
	        			$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
				    else
				    {
	        			$response['status']  = 0;
				        $response['message'] = "Not Success"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
			    }
			}

			else if($param1 == '_changePackStatus')
			{
				$error    = FALSE;
				$required = array('order_id');
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
			    	$order_id = $this->input->post('order_id');

			    	$order_data = array(
                        'ref_id'   => $this->session->userdata('id'),
			    		'auto_id' => $order_id,
						'method'  => '_changePackStatus',
					);
                 print_r($order_data);exit;
					$data_save = avul_call(API_URL.'distributorpurchase/api/order_process',$order_data);

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

			else if($param1 == 'delete')
    		{
    			$error = FALSE;
    			$auto_id  = $this->input->post('id');
    			$progress = $this->input->post('progress');

    			$required = array('id', 'progress');
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
			    	if($progress == '1')
			    	{
			    		$data = array(
					    	'auto_id' => $auto_id,
					    	'method'  => '_DeleteOrderDetails',
					    );

					    $data_save = avul_call(API_URL.'distributorpurchase/api/order_process',$data);

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
		}

		public function order_stock($param1="", $param2="", $param3="")
    	{
    		if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			$formpage = $this->input->post('formpage');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$received_date = $this->input->post('received_date');
				$received_qty  = $this->input->post('received_qty');
				$order_id      = $this->input->post('order_id');
				$auto_id       = $this->input->post('auto_id');
				$product_id    = $this->input->post('product_id');
				$type_id       = $this->input->post('type_id');
				$method        = $this->input->post('method');

				$required = array('received_date', 'received_qty', 'auto_id', 'order_id', 'product_id', 'type_id');
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
			    	$stock_data = array(
			    		'po_id'         => $order_id,
			    		'po_auto_id'    => $auto_id,
			    		'product_id'    => $product_id,
			    		'type_id'       => $type_id,
			    		'received_date' => $received_date,
			    		'received_qty'  => $received_qty,
			    		'method'        => '_addOrderStockDetails',
			    	);

			    	$data_save  = avul_call(API_URL.'distributorpurchase/api/manage_order_stock',$stock_data);

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

			if($param1 =='stock_add')
			{
				$order_id = $param3;
				$auto_id  = $param2;

				// Order details
				$order_details = array(
					'po_id'      => $order_id,
					'po_auto_id' => $auto_id,
					'method'     => '_detailOrderStockDetails',
				);

				$order_data  = avul_call(API_URL.'distributorpurchase/api/manage_order_stock',$order_details);

				$order_value = $order_data['data'];

				$product_id = !empty($order_value['product_id'])?$order_value['product_id']:'';
    			$type_id    = !empty($order_value['type_id'])?$order_value['type_id']:'';

				// Stock entry list
				$stock_details = array(
					'po_id'      => $order_id,
					'po_auto_id' => $auto_id,
					'product_id' => $product_id,
					'type_id'    => $type_id,
					'method'     => '_listOrderStockDetails',
				);

				$stock_data  = avul_call(API_URL.'distributorpurchase/api/manage_order_stock',$stock_details);

				$stock_value = $stock_data['data'];

				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Manage Outlet Order";
				$page['page_title']    = "Add Stock";
				$page['pre_title']     = "View Order";
				$page['order_value']   = $order_value;
				$page['stock_value']   = $stock_value;
				$page['pre_menu']      = "index.php/admin/Branchpurchase/br_packing_order/View/$order_id";
				$data['page_temp']     = $this->load->view('admin/distributors/stock_add',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "br_packing_order";
				$this->bassthaya->load_distributors_form_template($data);
			}

			if($param1 == 'delete')
			{
				$id = $this->input->post('id');

				if(!empty($id))	
				{
					$data = array(
				    	'stock_id' => $id,
				    	'method'   => '_deleteOrderStockDetails'
				    );

				    $data_save = avul_call(API_URL.'distributorpurchase/api/manage_order_stock',$data);

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

		public function print_invoice($param1="", $param2="", $param3="")
		{
			$whr_1 = array(
	    		'inv_random' => $param1,
	    		'method'     => '_distributorPrintInvoice',
	    	);

	    	$data_val  = avul_call(API_URL.'distributorpurchase/api/distributor_invoice',$whr_1);	
	    	$data_res  = $data_val['data'];

	    	if($data_res)
	    	{
	    		$invoice_det = !empty($data_res['invoice_details'])?$data_res['invoice_details']:'';
	    		$admin_det   = !empty($data_res['admin_details'])?$data_res['admin_details']:'';
	    		$product_det = !empty($data_res['product_details'])?$data_res['product_details']:'';
	    		$tax_det     = !empty($data_res['tax_details'])?$data_res['tax_details']:'';
	    		$return_det  = !empty($data_res['return_details'])?$data_res['return_details']:'';

	    		// Return Details
    			$return_total = !empty($return_det['return_total'])?$return_det['return_total']:'0';

	    		// Invocie Details
	    		$invoice_no   = !empty($invoice_det['invoice_no'])?$invoice_det['invoice_no']:'';
	            $invoice_date = !empty($invoice_det['invoice_date'])?$invoice_det['invoice_date']:'';
	            $po_no        = !empty($invoice_det['po_no'])?$invoice_det['po_no']:'';
	            $order_date   = !empty($invoice_det['order_date'])?$invoice_det['order_date']:'';
	            $company_name = !empty($invoice_det['company_name'])?$invoice_det['company_name']:'';
	            $mobile       = !empty($invoice_det['mobile'])?$invoice_det['mobile']:'';
	            $email        = !empty($invoice_det['email'])?$invoice_det['email']:'';
	            $state_id     = !empty($invoice_det['state_id'])?$invoice_det['state_id']:'';
	            $gst_no       = !empty($invoice_det['gst_no'])?$invoice_det['gst_no']:'';
	            $address      = !empty($invoice_det['address'])?$invoice_det['address']:'';
	            $length       = !empty($invoice_det['length'])?$invoice_det['length']:'0';
	            $breadth      = !empty($invoice_det['breadth'])?$invoice_det['breadth']:'0';
	            $height       = !empty($invoice_det['height'])?$invoice_det['height']:'0';
	            $weight       = !empty($invoice_det['weight'])?$invoice_det['weight']:'0';

	            // Admin Details
	            $adm_username = !empty($admin_det['username'])?$admin_det['username']:'';
	            $adm_mobile   = !empty($admin_det['mobile'])?$admin_det['mobile']:'';
	            $adm_address  = !empty($admin_det['address'])?$admin_det['address']:'';
	            $adm_gst_no   = !empty($admin_det['gst_no'])?$admin_det['gst_no']:'';
	            $adm_state_id = !empty($admin_det['state_id'])?$admin_det['state_id']:'';
				$account_no = !empty($admin_det['account_no'])?$admin_det['account_no']:'';
				$account_name = !empty($admin_det['account_name'])?$admin_det['account_name']:'';
				$ifsc_code = !empty($admin_det['ifsc_code'])?$admin_det['ifsc_code']:'';
				$bank_name = !empty($admin_det['bank_name'])?$admin_det['bank_name']:'';
				$branch_name = !empty($admin_det['branch_name'])?$admin_det['branch_name']:'';

	            $this->load->library('Pdf');
	      		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'mm', array(216,356), TRUE, 'UTF-8', FALSE);
	          	$pdf->SetTitle('Manufacturer Invice');
	          	$pdf->SetPrintHeader(false);
	          	$pdf->SetPrintFooter(false);
	          		
				$pdf->SetPrintHeader(false);
				$pdf->SetPrintFooter(false);

	          	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	          	$pdf->SetFont('');

	          	$bill_type = array('Original Copy', 'Duplicate Copy', 'Triplicate Copy');

	          	for ($i=0; $i <= 2; $i++) { 
	          		// Original
		          	$pdf->AddPage('P');
		          	$html_1  = '';
		          	$html_1 .= '<span style="color:black; text-align:right; font-size:13px; text-transform: uppercase;">'.$bill_type[$i].'</span><p style="color:black; font-size:12px; text-align: center;"><strong style="font-size:18px; padding-bottom:1000px;">'.$adm_username.'</strong><br/>'.$adm_address.',<br>Contact No: '.$adm_mobile.', GSTIN\UIN : '.$adm_gst_no.'<br><strong style="color:black; text-align:center; font-size:17px;"> TAX INVOICE </strong><br></p>';

		          	$html_1 .='<br><br><br>
						<table border= "1" cellpadding="1" top="100">
					        <tr>
					            <td rowspan="7" style="font-size:12px; width: 50%; margin-left:10px;">Shipped To: <br> '.$company_name.'<br> '.$address.'<br> Contact No: '.$mobile.'<br> GSTIN\UIN :'.$gst_no.'</td>
					            <td style="font-size:12px; width: 20%;"> Invoice No</td>
					            <td style="font-size:12px; width: 30%;"> '.$invoice_no.'</td>
					        </tr>
					        <tr>
					            <td style="font-size:12px; width: 20%;"> Invoice Date</td>
					            <td style="font-size:12px; width: 30%;"> '.date('d-M-Y', strtotime($invoice_date)).'</td>
					        </tr>
					        <tr>
					            <td style="font-size:12px; width: 20%;"> Distributor(s) Order No</td>
					            <td style="font-size:12px; width: 30%;"> '.$po_no.'</td>
					        </tr>
					        <tr>
					            <td style="font-size:12px; width: 20%;"> Distributor(s) Order Date</td>
					            <td style="font-size:12px; width: 30%;"> '.date('d-M-Y', strtotime($order_date)).'</td>
					        </tr>
					        <tr>
					            <td style="font-size:12px; width: 20%;"> Bill Type</td>
					            <td style="font-size:12px; width: 30%;"> </td>
					        </tr>
					        <tr>
					            <td style="font-size:12px; width: 20%;"> Due Days</td>
					            <td style="font-size:12px; width: 30%;"> </td>
					        </tr>
					        <tr>
					            <td style="font-size:12px; width: 20%;"> Dimensions</td>
					            <td style="font-size:12px; width: 30%;"> '.$length.' cm X '.$breadth.' cm X '.$height.' cm X '.$weight.' kg</td>
					        </tr>
					    </table>
					    ';

				    $html_1 .='<br><br>
						<table border= "1" cellpadding="1" top="100">
					        <tr>
					            <td style="font-size:12px; width: 5%;">S.No</td>
					            <td style="font-size:12px; width: 44%;">Description</td>
					            <td style="font-size:12px; text-align: center; width: 10%;">HSN</td>
					            <td style="font-size:12px; text-align: center; width: 10%;">Rate</td>
					            <td style="font-size:12px; text-align: center; width: 8%;">Qty</td>
					            <td style="font-size:12px; text-align: center; width: 8%;">Per</td>
					            <td style="font-size:12px; text-align: center; width: 15%;">Amount</td>
					        </tr>';

					        $num     = 1;
					        $sub_tot = 0;
			                $tot_gst = 0;
			                $net_tot = 0;
			                $tot_qty = 0;
					        foreach ($product_det as $key => $val) {
					        	$description = !empty($val['description'])?$val['description']:'';
			                    $hsn_code    = !empty($val['hsn_code'])?$val['hsn_code']:'';
			                    $gst_value   = !empty($val['gst_val'])?$val['gst_val']:'0';
			                    $price       = !empty($val['price'])?$val['price']:'0';
			                    $order_qty   = !empty($val['order_qty'])?$val['order_qty']:'0';

			                    $gst_data  = $price - ($price * (100 / (100 + $gst_value)));
			                    $price_val = $price - $gst_data;
			                    $tot_price = $order_qty * $price_val;
			                    $sub_tot  += $tot_price;

			                    // GST Calculation
			                    $gst_val   = $order_qty * $gst_data;
			                    $tot_gst  += $gst_val;
			                    $total_val = $order_qty * $price;
			                    $net_tot  += $total_val;
			                    $tot_qty  += $order_qty;

			                    $html_1 .= '
			                    	<tr>
							            <td style="font-size:12px; width: 5%;">'.$num.'</td>
							            <td style="font-size:12px; width: 44%;">'.$description.'</td>
							            <td style="font-size:12px; text-align: center; width: 10%;">'.$hsn_code.'</td>
							            <td style="font-size:12px; text-align: center; width: 10%;">'.number_format((float)$price_val, 2, '.', '').'</td>
							            <td style="font-size:12px; text-align: center; width: 8%;">'.$order_qty.'</td>
							            <td style="font-size:12px; text-align: center; width: 8%;">nos</td>
							            <td style="font-size:12px; text-align: center; width: 15%;">'.number_format((float)$tot_price, 2, '.', '').'</td>
							        </tr>
			                    ';

			                    $num++;
					        }

		          	$rowspan = '7';
				    if($adm_state_id == $state_id)
				    {
				    	$rowspan = '8';
				    }

	                // Round Val Details
	                $total_amt  = $net_tot - $return_total;
	                $net_value  = round($total_amt);
	                $rond_total = $net_value - $total_amt;

				    $html_1 .= '
			            <tr>
			                <td rowspan ="'.$rowspan.'"  colspan="4"></td>
			                <td colspan="2" style="font-size:12px; text-align: right;">Qty</td>
			                <td style="font-size:12px; text-align: right;"> '.$tot_qty.'</td>
			                
			            </tr>
			            <tr>
			                <td colspan="2" style="font-size:12px; text-align: right;">Sub Total</td>
			                <td style="font-size:12px; text-align: right;"> '.number_format((float)$sub_tot, 2, '.', '').'</td>
			            </tr>';
			            if($adm_state_id == $state_id)
			            {
			            	$gst_value = $tot_gst / 2;

			            	$html_1 .= '
			            		<tr>
					                <td colspan="2" style="font-size:12px; text-align: right;">SGST</td>
					                <td style="font-size:12px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
					            </tr>
					            <tr>
					                <td colspan="2" style="font-size:12px; text-align: right;">CGST</td>
					                <td style="font-size:12px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
					            </tr>
			            	';
			            }
			            else
			            {
			            	$html_1 .= '
			            		<tr>
					                <td colspan="2" style="font-size:12px; text-align: right;">IGST</td>
					                <td style="font-size:12px; text-align: right;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
					            </tr>
			            	';
			            }

			            if($return_total != 0)
			            {
			            	$return_data = round($return_total);
			            	
			            	$html_1 .='<tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">Credit Note</td>
				                <td style="font-size:12px; text-align: right;"> '.number_format((float)$return_data, 2, '.', '').'</td>
				            </tr>';	
			            }

			            $html_1 .='<tr>
			                <td colspan="2" style="font-size:12px; text-align: right;">Round off</td>
			                <td style="font-size:12px; text-align: right;"> '.number_format((float)$rond_total, 2, '.', '').'</td>
			            </tr>
			            <tr>
			                <td colspan="2" style="font-size:12px; text-align: right;">Net Total</td>
			                <td style="font-size:12px; text-align: right;"> '.number_format((float)$net_value, 2, '.', '').'</td>
			            </tr>';
				    $html_1 .='</table>';

					$html_1 .='<br><br>
						<table border= "1" cellpadding="1" top="100">
					        <tr>
					            <td rowspan="2" style="font-size:12px; width: 10%;">HSN</td>
					            <td rowspan="2" style="font-size:12px; width: 15%;">Taxable Value</td>';
					            if($adm_state_id == $state_id)
					            {
					            	$html_1 .= '
					            		<td colspan="2" style="font-size:12px; width: 30%; text-align:center;">CGST</td>
					            		<td colspan="2" style="font-size:12px; width: 30%; text-align:center;">SGST</td>
					            	';
					            }
					            else
					            {
					            	$html_1 .= '
					            		<td colspan="2" style="font-size:12px; width: 60%; text-align:center;">IGST</td>
					            	';
					            }
						            
						       $html_1 .= '<td rowspan="2" style="font-size:12px; width: 15%;">Total Tax Amount</td>
					        </tr>
					        <tr>';
					        	if($adm_state_id == $state_id)
					        	{
					        		$html_1 .= '
					        			<td style="font-size: 12px; text-align:center;">Rate</td>
							            <td style="font-size: 12px; text-align:center;">Amt</td>
							            <td style="font-size: 12px; text-align:center;">Rate</td>
							            <td style="font-size: 12px; text-align:center;">Amt</td>
					        		';
					        	}
					        	else
					        	{
					        		$html_1 .= '
					        			<td style="font-size: 12px; text-align:center;">Rate</td>
					            		<td style="font-size: 12px; text-align:center;">Amt</td>
					        		';
					        	}
					        $html_1 .='</tr>';
					        $tot_price = 0;
							$tot_gst   = 0;
					        foreach ($tax_det as $key => $value) {
					        	$hsn_code    = !empty($value['hsn_code'])?$value['hsn_code']:'';
			                    $gst_val     = !empty($value['gst_val'])?$value['gst_val']:'0';
			                    $gst_value   = !empty($value['gst_value'])?$value['gst_value']:'0';
			                    $price_value = !empty($value['price_value'])?$value['price_value']:'0';

			                    $tot_gst    += $gst_value;
					            $tot_price  += $price_value;

			                    $html_1 .= '
			                    	<tr>
			                    		<td style="font-size: 12px; text-align:left;"> '.$hsn_code.'</td>
			                    		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$price_value, 2, '.', '').'</td>';
			                    		if($adm_state_id == $state_id)
			                    		{
			                    			$state_value = $gst_value / 2;
					                        $gst_calc    = $gst_val / 2;
			                    			$html_1 .= '
			                    				<td style="font-size: 12px; text-align:left;"> '.$gst_calc.' %</td>
					                    		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$state_value, 2, '.', '').'</td>
					                    		<td style="font-size: 12px; text-align:left;"> '.$gst_calc.' %</td>
					                    		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$state_value, 2, '.', '').'</td>
			                    			';
			                    		}
			                    		else
			                    		{
			                    			$html_1 .= '
			                    				<td style="font-size: 12px; text-align:left;"> '.$gst_val.' %</td>
				                    			<td style="font-size: 12px; text-align:left;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
			                    			';
			                    		}
			                    		$html_1 .='<td style="font-size: 12px; text-align:left;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
			                    	</tr>
			                    ';
					        }
					        $html_1 .= '
					        	<tr>
					        		<td style="font-size: 12px; text-align:right;"> Total </td>
					        		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$tot_price, 2, '.', '').'</td>';
					        		if($adm_state_id == $state_id)
					        		{
					        			$state_val = $tot_gst / 2;

					        			$html_1 .= '
						        			<td style="font-size: 12px; text-align:left;"> </td>
							        		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$state_val, 2, '.', '').'</td>
							        		<td style="font-size: 12px; text-align:left;"> </td>
							        		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$state_val, 2, '.', '').'</td>
						        		';
					        		}
					        		else
					        		{
					        			$html_1 .= '
						        			<td style="font-size: 12px; text-align:left;"> </td>
							        		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
						        		';
					        		}

					        		$html_1 .='<td style="font-size: 12px; text-align:left;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
					        	</tr>
					        ';
					    $html_1 .='</table>';

					    $html_1 .='<br><br>
						<table border= "1" cellpadding="1" top="100">
					        <tr>
					            <td colspan="5" style="font-size:12px; width: 14%;"> Account Name</td>
					            <td colspan="5" style="font-size:12px; width: 36%;"> '.$account_name.'</td>
					            <td rowspan="5" style="font-size:12px; width: 50%;">
					            	<span> for '.$adm_username.'</span>
					            	<br><br><br>
					            	<p style="text-align: right; "> Authorised signature </p>
					            </td>
					        </tr>
					        <tr>
					        	<td colspan="5" style="font-size:12px; width: 14%;"> Account No</td>
					            <td colspan="5" style="font-size:12px; width: 36%;"> '.$account_no.'</td>
					        </tr>
					        <tr>
					        	<td colspan="5" style="font-size:12px; width: 14%;"> Bank Name</td>
					            <td colspan="5" style="font-size:12px; width: 36%;"> '.$bank_name.'</td>
					        </tr>
					        <tr>
					        	<td colspan="5" style="font-size:12px; width: 14%;"> Branch Name</td>
					            <td colspan="5" style="font-size:12px; width: 36%;"> '.$branch_name.'</td>
					        </tr>
					        <tr>
					        	<td colspan="5" style="font-size:12px; width: 14%;"> IFSC Code</td>
					            <td colspan="5" style="font-size:12px; width: 36%;"> '.$ifsc_code.'</td>
					        </tr>
					    </table>';

		          	$pdf->writeHTML($html_1, true, false, true, false, '');
	          	}

	       		$pdf->Output($company_name.'_'.$invoice_no.'_'.date('d-F-Y').'.pdf', 'I');
	    	}
		}

		public function print_order($param1="", $param2="", $param3="")
		{
			$purchase_id = $param1;

			$where = array(
		    	'purchase_id' => $purchase_id,
		    	'view_type'   => 1,
		    	'method'      => '_viewDistributorPurchase' 
		    );

		    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase',$where);
        	$data_value = !empty($data_list['data'])?$data_list['data']:'';

	    	if($data_value)
	    	{
	    		$bill_det = !empty($data_value['bill_details'])?$data_value['bill_details']:'';
	    		$adm_det  = !empty($data_value['admin_details'])?$data_value['admin_details']:'';
	    		$dis_det  = !empty($data_value['distributor_details'])?$data_value['distributor_details']:'';
	    		$ord_det  = !empty($data_value['order_details'])?$data_value['order_details']:'';

	    		// Bill Details
	            $po_no      = !empty($bill_det['po_no'])?$bill_det['po_no']:'';
	            $order_date = !empty($bill_det['order_date'])?$bill_det['order_date']:'';

	            // Admin Details
	            $adm_username = !empty($adm_det['username'])?$adm_det['username']:'';
	            $adm_mobile   = !empty($adm_det['mobile'])?$adm_det['mobile']:'';
	            $adm_address  = !empty($adm_det['address'])?$adm_det['address']:'';
	            $adm_gst_no   = !empty($adm_det['gst_no'])?$adm_det['gst_no']:'';
	            $adm_state_id = !empty($adm_det['state_id'])?$adm_det['state_id']:'';
				$account_name = !empty($adm_det['account_name'])?$adm_det['account_name']:'';
				$account_no = !empty($adm_det['account_no'])?$adm_det['account_no']:'';
				$ifsc_code = !empty($adm_det['ifsc_code'])?$adm_det['ifsc_code']:'';
				$branch_name = !empty($adm_det['branch_name'])?$adm_det['branch_name']:'';
				$bank_name = !empty($adm_det['bank_name'])?$adm_det['bank_name']:'';

	            // Distributor Details
	            $company_name = !empty($dis_det['company_name'])?$dis_det['company_name']:'';
	            $mobile       = !empty($dis_det['mobile'])?$dis_det['mobile']:'';
	            $email        = !empty($dis_det['email'])?$dis_det['email']:'';
	            $gst_no       = !empty($dis_det['gst_no'])?$dis_det['gst_no']:'';
	            $address      = !empty($dis_det['address'])?$dis_det['address']:'';
	            $state_id     = !empty($dis_det['state_id'])?$dis_det['state_id']:'';

	            $this->load->library('Pdf');
	      		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'mm', array(216,356), TRUE, 'UTF-8', FALSE);
	          	$pdf->SetTitle('Manufacturer Invice');
	          	$pdf->SetPrintHeader(false);
	          	$pdf->SetPrintFooter(false);
	          		
				$pdf->SetPrintHeader(false);
				$pdf->SetPrintFooter(false);

	          	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	          	$pdf->SetFont('');
	          	$pdf->AddPage('P');
	          	$html = '';

	          	$html .= '<p style="color:black; font-size:12px; text-align: center;"><strong style="font-size:18px; padding-bottom:1000px;">'.$adm_username.'</strong><br/>'.$adm_address.', Contact No: '.$adm_mobile.'<br>GSTIN\UIN : '.$adm_gst_no.'<br><strong style="color:black; text-align:center; font-size:17px;"> PROFORMA INVOICE</strong><br></p>';

	          	$html .='<br><br><br>
					<table border= "1" cellpadding="1" top="100">
				        <tr>
				            <td rowspan="6" style="font-size:12px; width: 55%; margin-left:10px;">Shipped To: <br> '.$company_name.'<br> '.$address.'<br> Contact No: '.$mobile.'<br> GSTIN\UIN :'.$gst_no.'</td>
				            <td style="font-size:12px; width: 20%;"> Voucher No</td>
				            <td style="font-size:12px; width: 25%;"> '.$po_no.'</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Voucher Date</td>
				            <td style="font-size:12px; width: 25%;"> '.date('d-M-Y', strtotime($order_date)).'</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Buyer\'s Ref No</td>
				            <td style="font-size:12px; width: 25%;"> '.$po_no.'</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Bill Type</td>
				            <td style="font-size:12px; width: 25%;"> </td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Due Days</td>
				            <td style="font-size:12px; width: 25%;"> </td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Other Reference(s)</td>
				            <td style="font-size:12px; width: 25%;"> </td>
				        </tr>
				    </table>
				    ';

				$html .='<br><br>
					<table border= "1" cellpadding="1" top="100">
				        <tr>
				            <td style="font-size:12px; width: 5%;">S.No</td>
				            <td style="font-size:12px; width: 44%;">Description</td>
				            <td style="font-size:12px; text-align: center; width: 10%;">HSN</td>
				            <td style="font-size:12px; text-align: center; width: 10%;">Rate</td>
				            <td style="font-size:12px; text-align: center; width: 8%;">Qty</td>
				            <td style="font-size:12px; text-align: center; width: 8%;">Per</td>
				            <td style="font-size:12px; text-align: center; width: 15%;">Amount</td>
				        </tr>';

				        $num     = 1;
				        $sub_tot = 0;
		                $tot_gst = 0;
		                $net_tot = 0;
		                $tot_qty = 0;
				        foreach ($ord_det as $key => $val) {
				        	$description = !empty($val['description'])?$val['description']:'';
		                    $hsn_code    = !empty($val['hsn_code'])?$val['hsn_code']:'';
		                    $gst_value   = !empty($val['gst_val'])?$val['gst_val']:'0';
		                    $price       = !empty($val['product_price'])?$val['product_price']:'0';
		                    $order_qty   = !empty($val['product_qty'])?$val['product_qty']:'0';

		                    $gst_data  = $price - ($price * (100 / (100 + $gst_value)));
		                    $price_val = $price - $gst_data;
		                    $tot_price = $order_qty * $price_val;
		                    $sub_tot  += $tot_price;

		                    // GST Calculation
		                    $gst_val   = $order_qty * $gst_data;
		                    $tot_gst  += $gst_val;
		                    $total_val = $order_qty * $price;
		                    $net_tot  += $total_val;
		                    $tot_qty  += $order_qty;

		                    $html .= '
		                    	<tr>
						            <td style="font-size:12px; width: 5%;"> '.$num.'</td>
						            <td style="font-size:12px; width: 44%;"> '.$description.'</td>
						            <td style="font-size:12px; text-align: center; width: 10%;">'.$hsn_code.'</td>
						            <td style="font-size:12px; text-align: center; width: 10%;">'.number_format((float)$price_val, 2, '.', '').'</td>
						            <td style="font-size:12px; text-align: center; width: 8%;">'.$order_qty.'</td>
						            <td style="font-size:12px; text-align: center; width: 8%;">nos</td>
						            <td style="font-size:12px; text-align: center; width: 15%;">'.number_format((float)$tot_price, 2, '.', '').'</td>
						        </tr>
		                    ';

		                    $num++;
				        }

	          	$rowspan = '7';
			    if($adm_state_id == $state_id)
			    {
			    	$rowspan = '8';
			    }

                // Round Val Details
                $net_value  = round($net_tot);
                $rond_total = $net_value - $net_tot;

			    $html .= '
		            <tr>
		                <td rowspan ="'.$rowspan.'"  colspan="4"></td>
		                <td colspan="2" style="font-size:12px; text-align: right;" class="text-right">Qty</td>
		                <td style="font-size:12px; text-align: right;"> '.$tot_qty.'</td>
		                
		            </tr>
		            <tr>
		                <td colspan="2" style="font-size:12px; text-align: right;">Sub Total</td>
		                <td style="font-size:12px; text-align: right;"> '.number_format((float)$sub_tot, 2, '.', '').'</td>
		            </tr>';
		            if($adm_state_id == $state_id)
		            {
		            	$gst_value = $tot_gst / 2;

		            	$html .= '
		            		<tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">SGST</td>
				                <td style="font-size:12px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
				            </tr>
				            <tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">CGST</td>
				                <td style="font-size:12px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
				            </tr>
		            	';
		            }
		            else
		            {
		            	$html .= '
		            		<tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">IGST</td>
				                <td style="font-size:12px; text-align: right;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
				            </tr>
		            	';
		            }

		            $html .='<tr>
		                <td colspan="2" style="font-size:12px; text-align: right;">Round off</td>
		                <td style="font-size:12px; text-align: right;"> '.number_format((float)$rond_total, 2, '.', '').'</td>
		            </tr>
		            <tr>
		                <td colspan="2" style="font-size:12px; text-align: right;">Net Total</td>
		                <td style="font-size:12px; text-align: right;"> '.number_format((float)$net_value, 2, '.', '').'</td>
		            </tr>';
			    $html .='</table>';

			    $html .='<br><br>
					<table border= "1" cellpadding="1" top="100">
				        <tr>
				            <td colspan="5" style="font-size:11px; width: 13%;"> Account Name</td>
				            <td colspan="5" style="font-size:11px; width: 42%;"> '.$account_name.'</td>
				            <td rowspan="5" style="font-size:11px; width: 45%;">
				            	<span> for '.$adm_username.'</span>
				            	<br><br><br>
				            	<p style="text-align: right; "> Authorised signature </p>
				            </td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:11px; width: 13%;"> Account No</td>
				            <td colspan="5" style="font-size:11px; width: 42%;"> '.$account_no.'</td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:11px; width: 13%;"> Bank Name</td>
				            <td colspan="5" style="font-size:11px; width: 42%;"> '.$bank_name.'</td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:11px; width: 13%;"> Branch Name</td>
				            <td colspan="5" style="font-size:11px; width: 42%;"> '.$branch_name.'</td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:11px; width: 13%;"> IFSC Code</td>
				            <td colspan="5" style="font-size:11px; width: 42%;"> '.$ifsc_code.'</td>
				        </tr>
				    </table>';

	          	$pdf->writeHTML($html, true, false, true, false, '');
	       		$pdf->Output($company_name.'_'.$po_no.'_'.date('d-F-Y').'.pdf', 'I');
	    	}
		}

		public function print_json($param1="", $param2="", $param3="")
		{
			$whr_1 = array(
	    		'inv_random' => $param1,
	    		'method'     => '_distributorPrintInvoice',
	    	);

	    	$data_val  = avul_call(API_URL.'distributorpurchase/api/distributor_invoice',$whr_1);	
	    	$data_res  = $data_val['data'];

	    	if($data_res)
	    	{
	    		$inv_det = !empty($data_res['invoice_details'])?$data_res['invoice_details']:'';
		    	$adm_det = !empty($data_res['admin_details'])?$data_res['admin_details']:'';
		    	$pdt_det = !empty($data_res['product_details'])?$data_res['product_details']:'';
		    	$tax_det = !empty($data_res['tax_details'])?$data_res['tax_details']:'';
		    	$ret_det = !empty($data_res['return_details'])?$data_res['return_details']:'';

		    	// Invocie Details
		    	$order_id     = !empty($inv_det['order_id'])?$inv_det['order_id']:'';
	            $due_days     = !empty($inv_det['due_days'])?$inv_det['due_days']:'';
	            $invoice_no   = !empty($inv_det['invoice_no'])?$inv_det['invoice_no']:'';
	            $invoice_date = !empty($inv_det['invoice_date'])?$inv_det['invoice_date']:'';
	            $dis_po_no    = !empty($inv_det['po_no'])?$inv_det['po_no']:'';
	            $order_date   = !empty($inv_det['order_date'])?$inv_det['order_date']:'';
	            $company_name = !empty($inv_det['company_name'])?$inv_det['company_name']:'';
	            $dis_mobile   = !empty($inv_det['mobile'])?$inv_det['mobile']:'';
	            $dis_email    = !empty($inv_det['email'])?$inv_det['email']:'';
	            $dis_state_id = !empty($inv_det['state_id'])?$inv_det['state_id']:'';
	            $dis_state    = !empty($inv_det['dis_state'])?$inv_det['dis_state']:'';
	            $dis_gst_no   = !empty($inv_det['gst_no'])?$inv_det['gst_no']:'';
	            $dis_address  = !empty($inv_det['address'])?$inv_det['address']:'';
	            $dis_pincode  = !empty($inv_det['pincode'])?$inv_det['pincode']:'';

		    	// Admin Details
		    	$adm_username = !empty($adm_det['username'])?$adm_det['username']:'';
	            $adm_mobile   = !empty($adm_det['mobile'])?$adm_det['mobile']:'';
	            $adm_address  = !empty($adm_det['address'])?$adm_det['address']:'';
	            $adm_pincode  = !empty($adm_det['pincode'])?$adm_det['pincode']:'';
	            $adm_gst_no   = !empty($adm_det['gst_no'])?$adm_det['gst_no']:'';
	            $adm_state_id = !empty($adm_det['state_id'])?$adm_det['state_id']:'';
	            $adm_state    = !empty($adm_det['adm_state'])?$adm_det['adm_state']:'';

	            // Return Details
	            $return_total = !empty($ret_det['return_total'])?$ret_det['return_total']:'0';

		    	$itemList   = [];
		    	$num        = 1;
		    	$total_val  = 0;
		    	$total_gst  = 0;
		    	$total_bill = 0;
		    	foreach ($pdt_det as $key => $pdt_val) {
		    		$description = !empty($pdt_val['description'])?$pdt_val['description']:'';
	                $hsn_code    = !empty($pdt_val['hsn_code'])?$pdt_val['hsn_code']:'';
	                $gst_val     = !empty($pdt_val['gst_val'])?$pdt_val['gst_val']:'';
	                $pdt_price   = !empty($pdt_val['price'])?$pdt_val['price']:'';
	                $order_qty   = !empty($pdt_val['order_qty'])?$pdt_val['order_qty']:'';
	                $unit_val    = !empty($pdt_val['unit_va'])?$pdt_val['unit_va']:'';

	                $gst_data   = $pdt_price - ($pdt_price * (100 / (100 + $gst_val)));
	                $price_val  = $pdt_price - $gst_data;
	                $tot_price  = $order_qty * $price_val;
	                $total_val += $tot_price;

	                // GST Calculation
	                $pdt_gst    = $pdt_price - ($pdt_price * (100 / (100 + $gst_val)));
	                $tot_gst    = $order_qty * $pdt_gst;
	                $total_gst += $tot_gst;

	                // Total Bill Calculation
	                $tot_amt     = $order_qty * $pdt_price;
	                $total_bill += $tot_amt;

	                if($unit_val == 1)
	                {
	                	$qtyUnit = 'KGS';
	                }
	                else if($unit_val == 1)
	                {
	                	$qtyUnit = 'KGS';
	                }
	                else
	                {
	                	$qtyUnit = 'NOS';
	                }

	                if($dis_state_id == $adm_state_id)
	                {
	                	$gst_res  = $gst_val / 2;
	                	$sgstRate = (float)$gst_res;
	                	$cgstRate = (float)$gst_res;
	                	$igstRate = 0;
	                }
	                else
	                {
	                	$sgstRate = 0;
	                	$cgstRate = 0;
	                	$igstRate = (float)$gst_val;
	                }

	                $pdt_value = number_format((float)$tot_price, 2, '.', '');

	                $itemList[] = array(
	                	'itemNo'        => $num,
	                	'productName'   => $description,
	                	'productDesc'   => $description,
	                	'hsnCode'       => (float)$hsn_code,
	                	'quantity'      => (float)$order_qty,
	                	'qtyUnit'       => $qtyUnit,
	                	'taxableAmount' => (float)$pdt_value,
	                	'sgstRate'      => $sgstRate,
	                	'cgstRate'      => $cgstRate,
	                	'igstRate'      => $igstRate,
	                	'cessRate'      => 0,
	                	'cessNonAdvol'  => 0,
	                );

	                $num++;
		    	}

		    	if($dis_state_id == $adm_state_id)
	            {
	            	$gst_value = $total_gst / 2;
	            	$cgstValue = number_format((float)$gst_value, 2, '.', '');
	            	$sgstValue = number_format((float)$gst_value, 2, '.', '');
	            	$igstValue = 0;
	            }
	            else
	            {
	            	$cgstValue = 0;
	            	$sgstValue = 0;
	            	$igstValue = number_format((float)$total_gst, 2, '.', '');
	            }

	            $bill_total = number_format((float)$total_val, 2, '.', '');

	            $net_value  = round($total_bill);
	           	$rond_total = $net_value - $total_bill;

	           	$bill_round = number_format((float)$rond_total, 2, '.', '');
	           	$bill_net   = number_format((float)$net_value, 2, '.', '');

		    	$bill_details[] = array(
		    		'userGstin'           => $adm_gst_no,
		    		'supplyType'          => '',
		    		'subSupplyType'       => '',
		    		'docType'             => 'INV',
		    		'docNo'               => $invoice_no,
		    		'docDate'             => date('d/m/Y', strtotime($invoice_date)),
		    		'transType'           => '',
		    		'fromGstin'           => $adm_gst_no,
		    		'fromTrdName'         => $adm_username,
		    		'fromAddr1'           => $adm_address,
		    		'fromAddr2'           => '',
		    		'fromPlace'           => '',
		    		'fromPincode'         => (int)$adm_pincode,
		    		'fromStateCode'       => (int)$adm_state,
		    		'actualFromStateCode' => (int)$adm_state,
		    		'toGstin'             => $dis_gst_no,
		    		'toTrdName'           => $company_name,
		    		'toAddr1'             => $dis_address,
		    		'toAddr2'             => '',
		    		'toPlace'             => '',
		    		'toPincode'           => (int)$dis_pincode,
		    		'toStateCode'         => (int)$dis_state,
		    		'actualToStateCode'   => (int)$dis_state,
		    		'totalValue'          => (float)$bill_total,
		    		'cgstValue'           => (float)$cgstValue,
		    		'sgstValue'           => (float)$sgstValue,
		    		'igstValue'           => (float)$igstValue,
		    		'cessValue'           => 0,
		    		'TotNonAdvolVal'      => 0,
		    		'OthValue'            => (float)$bill_round,
		    		'totInvValue'         => (float)$bill_net,
		    		'transMode'           => '',
		    		'transDistance'       => '',
		    		'transporterName'     => '',
		    		'transporterId'       => '',
		    		'transDocNo'          => '',
		    		'transDocDate'        => '',
		    		'vehicleNo'           => '',
		    		'vehicleType'         => '',
		    		'mainHsnCode'         => '',
		    		'itemList'            => $itemList,
		    	);

		    	// Header Value
		    	$file_name = 'Ewaybill_'.$invoice_no.'_'.date('d-M-Y');
		    	
		    	header('Content-Type: application/json');
		    	header('Content-Disposition: filename="'.$file_name.'.json"');

		    	$response['version']   = '1.0.0219';
		        $response['billLists'] = $bill_details;
		        echo json_encode($response);
		        return;
	    	}
		}
	}
?>
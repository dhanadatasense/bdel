<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Distributorsorder extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function dis_overall_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');

			if($param1 == '')
			{
				$page['main_heading']  = "Distributor Order";
				$page['sub_heading']   = "Distributor Order";
				$page['page_title']    = "Overall Order";
				$page['pre_title']     = "Add Purchase";
				$page['page_access']   = userAccess('distributors-order-view');
				$page['load_data']     = "";
				$page['function_name'] = "dis_overall_order";
				$page['pre_menu']      = "index.php/admin/distributors/list_order";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_overall_order";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('distributors-order-view'))
				{
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
						'ref_id'         => 0,
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

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="distributorsorder" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
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
				            if(userAccess('distributors-order-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('distributors-order-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/distributorsorder/dis_overall_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	endif;
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
				$page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/admin/distributors/view_order";
				$data['page_temp']     = $this->load->view('admin/distributors/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_overall_order";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function dis_success_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');

			if($param1 == '')
			{
				$page['main_heading']  = "Distributor Order";
				$page['sub_heading']   = "Distributor Order";
				$page['page_title']    = "Success Order";
				$page['pre_title']     = "Add Purchase";
				$page['page_access']   = userAccess('distributors-order-view');
				$page['load_data']     = "1";
				$page['function_name'] = "dis_success_order";
				$page['pre_menu']      = "index.php/admin/distributors/list_order";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_success_order";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('distributors-order-view'))
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
						'ref_id'         => 0,
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

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="distributorsorder" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
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
				            if(userAccess('distributors-order-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('distributors-order-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/distributorsorder/dis_success_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	endif;
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
				}
				else
	    		{
	    			$response['status']  = 0;
			        $response['message'] = 'Access denied'; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
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
				$page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/admin/distributors/view_order";
				$data['page_temp']     = $this->load->view('admin/distributors/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_success_order";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function dis_process_order($param1="", $param2="", $param3="")
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
				$page['function_name'] = "dis_process_order";
				$page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/admin/distributors/list_order";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_process_order";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('distributors-order-view'))
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
						'ref_id'         => 0,
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

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="distributorsorder" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
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
				            if(userAccess('distributors-order-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('distributors-order-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/distributorsorder/dis_process_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	endif;
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
				$page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/admin/distributors/view_order";
				$data['page_temp']     = $this->load->view('admin/distributors/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_process_order";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function dis_packing_order($param1="", $param2="", $param3="")
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
				$page['page_access']   = userAccess('distributors-order-view');
				$page['function_name'] = "dis_packing_order";
				$page['pre_menu']      = "index.php/admin/distributors/list_order";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_packing_order";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('distributors-order-view'))
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
						'ref_id'         => 0,
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

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="distributorsorder" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
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
				            if(userAccess('distributors-order-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('distributors-order-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/distributorsorder/dis_packing_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	endif;
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
				$page['pre_menu']      = "index.php/admin/distributors/view_order";
				$page['page_access']   = userAccess('distributors-order-view');
				$data['page_temp']     = $this->load->view('admin/distributors/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_packing_order";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function dis_invoice_order($param1="", $param2="", $param3="")
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
				$page['page_access']   = userAccess('distributors-order-view');
				$page['function_name'] = "dis_invoice_order";
				$page['pre_menu']      = "index.php/admin/distributors/list_order";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_invoice_order";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('distributors-order-view'))
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
						'ref_id'         => 0,
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

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="distributorsorder" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
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
				            if(userAccess('distributors-order-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('distributors-order-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/distributorsorder/dis_invoice_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	endif;
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
				$page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/admin/distributors/view_order";
				$data['page_temp']     = $this->load->view('admin/distributors/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_invoice_order";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function dis_shipping_order($param1="", $param2="", $param3="")
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
				$page['page_access']   = userAccess('distributors-order-view');
				$page['function_name'] = "dis_shipping_order";
				$page['pre_menu']      = "index.php/admin/distributors/list_order";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_shipping_order";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('distributors-order-view'))
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
						'ref_id'         => 0,
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

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="distributorsorder" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
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
				            if(userAccess('distributors-order-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('distributors-order-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/distributorsorder/dis_shipping_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	endif;
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
				$page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/admin/distributors/view_order";
				$data['page_temp']     = $this->load->view('admin/distributors/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_shipping_order";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function dis_delivery_order($param1="", $param2="", $param3="")
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
				$page['page_access']   = userAccess('distributors-order-view');
				$page['function_name'] = "dis_delivery_order";
				$page['pre_menu']      = "index.php/admin/distributors/list_order";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_delivery_order";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('distributors-order-view'))
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
						'ref_id'         => 0,
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

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="distributorsorder" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
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
				            if(userAccess('distributors-order-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('distributors-order-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/distributorsorder/dis_delivery_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	endif;
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
				$page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/admin/distributors/view_order";
				$data['page_temp']     = $this->load->view('admin/distributors/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_delivery_order";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function dis_complete_order($param1="", $param2="", $param3="")
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
				$page['page_access']   = userAccess('distributors-order-view');
				$page['function_name'] = "dis_complete_order";
				$page['pre_menu']      = "index.php/admin/distributors/list_order";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_complete_order";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('distributors-order-view'))
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
						'ref_id'         => 0,
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

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="distributorsorder" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
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
				            if(userAccess('distributors-order-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('distributors-order-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/distributorsorder/dis_complete_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	endif;
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
				$page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/admin/distributors/view_order";
				$data['page_temp']     = $this->load->view('admin/distributors/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_complete_order";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function dis_cancle_order($param1="", $param2="", $param3="")
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
				$page['page_access']   = userAccess('distributors-order-view');
				$page['function_name'] = "dis_cancle_order";
				$page['pre_menu']      = "index.php/admin/distributors/list_order";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_cancle_order";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('distributors-order-view'))
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
						'ref_id'         => 0,
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

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="distributorsorder" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
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
				            if(userAccess('distributors-order-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('distributors-order-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/distributorsorder/dis_cancle_order/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.$invoice_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	endif;
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
				}
				else
	    		{
	    			$response['status']  = 0;
			        $response['message'] = 'Access denied'; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
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
				$page['page_access']   = userAccess('distributors-order-view');
				$page['pre_menu']      = "index.php/admin/distributors/view_order";
				$data['page_temp']     = $this->load->view('admin/distributors/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_cancle_order";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function dis_cancle_invoice($param1="", $param2="", $param3="")
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
				$page['function_name'] = "dis_cancle_invoice";
				$page['pre_menu']      = "index.php/admin/distributors/list_order";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_cancle_invoice";
				$this->bassthaya->load_admin_form_template($data);
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
					'ref_id'         => 0,
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

					        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="distributorsorder" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
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
                                	<a href="'.BASE_URL.'index.php/admin/distributorsorder/dis_cancle_invoice/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>
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
				$page['pre_menu']      = "index.php/admin/distributors/view_order";
				$data['page_temp']     = $this->load->view('admin/distributors/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_cancle_invoice";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function order_process($param1="", $param2="", $param3="")
		{
			$method = $this->input->post('method');

			if($method == 'changeOrder_status')
			{
				$order_id = $this->input->post('order_id');

				$order_data = array(
		    		'auto_id'  => $order_id,
					'progress' => '2',
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
				$error                   = FALSE;
				$order_id                = $this->input->post('order_id');
				$invoice_id              = $this->input->post('invoice_id');
				$inv_random              = $this->input->post('inv_random');
			    $order_status            = $this->input->post('order_status');
			    $message                 = $this->input->post('message');
			    $length                  = $this->input->post('length');
			    $breadth                 = $this->input->post('breadth');
			    $height                  = $this->input->post('height');
			    $weight                  = $this->input->post('weight');
			    $e_inv_status            = $this->input->post('e_inv_status');
			    $e_way_status            = $this->input->post('e_way_status');
			    $transporter_id          = $this->input->post('transporter_id');
			    $transporter_name        = $this->input->post('transporter_name');
			    $transportation_mode     = $this->input->post('transportation_mode');
			    $transportation_distance = $this->input->post('transportation_distance');
			    $transporter_doc_number  = $this->input->post('transporter_document_number');
			    $transporter_doc_date    = $this->input->post('transporter_document_date');
			    $vehicle_number          = $this->input->post('vehicle_number');
			    $vehicle_type            = $this->input->post('vehicle_type');

				$required = array('order_id', 'order_status');
				if($order_status == 8)
				{
					array_push($required, 'message');
				}
				if($order_status == 10)
				{
					array_push($required, 'e_inv_status');
				}
				if($e_inv_status == 1)
				{
					array_push($required, 'e_way_status');
				}
				if($e_way_status == 1)
				{
					array_push($required, 'transporter_id', 'transporter_name', 'transportation_mode', 'vehicle_number', 'vehicle_type');
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
						'ref_id'                  => 0,
			    		'auto_id'                 => $order_id,
			    		'invoice_id'              => $invoice_id,
			    		'inv_random'              => $inv_random,
						'progress'                => $order_status,
						'reason'                  => $message,
						'length'                  => $length,
						'breadth'                 => $breadth,
						'height'                  => $height,
						'weight'                  => $weight,
						'e_inv_status'            => $e_inv_status,
						'e_way_status'            => $e_way_status,
						'transporter_id'          => $transporter_id,
						'transporter_name'        => $transporter_name,
						'transportation_mode'     => $transportation_mode,
						'transportation_distance' => $transportation_distance,
						'transporter_doc_number'  => $transporter_doc_number,
						'transporter_doc_date'    => $transporter_doc_date,
						'vehicle_number'          => $vehicle_number,
						'vehicle_type'            => $vehicle_type,
						'method'                  => '_updateOrderProgress',
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
						'ref_id'  => 0,
			    		'auto_id' => $order_id,
						'method'  => '_changePackStatus',
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
				$page['pre_menu']      = "index.php/admin/distributorsorder/dis_packing_order/View/$order_id";
				$data['page_temp']     = $this->load->view('admin/distributors/stock_add',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_packing_order";
				$this->bassthaya->load_admin_form_template($data);
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

	            $this->load->library('Pdf');
	      		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'mm', array(216,356), TRUE, 'UTF-8', FALSE);
	          	$pdf->SetTitle('Manufacturer Invice');
	          	$pdf->SetPrintHeader(false);
	          	$pdf->SetPrintFooter(false);
	          		
				$pdf->SetPrintHeader(false);
				$pdf->SetPrintFooter(false);

	          	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	          	$pdf->SetFont('');

	          	// $bill_type = array('Original Copy', 'Duplicate Copy', 'Triplicate Copy');
	          	$bill_type = array('Original Copy', 'Duplicate Copy');

	          	// for ($i=0; $i <= 2; $i++) { 
	          	for ($i=0; $i <= 1; $i++) {
	          		
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
					            <td style="font-size:11px; width: 5%;">S.No</td>
					            <td style="font-size:11px; width: 30%;">Description</td>
					            <td style="font-size:11px; text-align: center; width: 10%;">HSN</td>
					            <td style="font-size:11px; text-align: center; width: 10%;">MRP</td>
					            <td style="font-size:11px; text-align: center; width: 8%;">GST %</td>
					            <td style="font-size:11px; text-align: center; width: 9%;">Rate</td>
					            <td style="font-size:11px; text-align: center; width: 8%;">Qty</td>
					            <td style="font-size:11px; text-align: center; width: 8%;">Per</td>
					            <td style="font-size:11px; text-align: center; width: 12%;">Amount</td>
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
							            <td style="font-size:11px; width: 5%;">'.$num.'</td>
							            <td style="font-size:11px; width: 30%;">'.$description.'</td>
							            <td style="font-size:11px; text-align: center; width: 10%;">'.$hsn_code.'</td>
							            <td style="font-size:11px; text-align: center; width: 10%;">'.number_format((float)$price, 2, '.', '').'</td>
							            <td style="font-size:11px; text-align: center; width: 8%;">'.$gst_value.'</td>
							            <td style="font-size:11px; text-align: center; width: 9%;">'.number_format((float)$price_val, 2, '.', '').'</td>
							            <td style="font-size:11px; text-align: center; width: 8%;">'.$order_qty.'</td>
							            <td style="font-size:11px; text-align: center; width: 8%;">nos</td>
							            <td style="font-size:11px; text-align: center; width: 12%;">'.number_format((float)$tot_price, 2, '.', '').'</td>
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
			                <td rowspan ="'.$rowspan.'"  colspan="6"></td>
			                <td colspan="2" style="font-size:11px; text-align: right;">Qty</td>
			                <td style="font-size:11px; text-align: right;"> '.$tot_qty.'</td>
			                
			            </tr>
			            <tr>
			                <td colspan="2" style="font-size:11px; text-align: right;">Sub Total</td>
			                <td style="font-size:11px; text-align: right;"> '.number_format((float)$sub_tot, 2, '.', '').'</td>
			            </tr>';
			            if($adm_state_id == $state_id)
			            {
			            	$gst_value = $tot_gst / 2;

			            	$html_1 .= '
			            		<tr>
					                <td colspan="2" style="font-size:11px; text-align: right;">SGST</td>
					                <td style="font-size:11px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
					            </tr>
					            <tr>
					                <td colspan="2" style="font-size:11px; text-align: right;">CGST</td>
					                <td style="font-size:11px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
					            </tr>
			            	';
			            }
			            else
			            {
			            	$html_1 .= '
			            		<tr>
					                <td colspan="2" style="font-size:11px; text-align: right;">IGST</td>
					                <td style="font-size:11px; text-align: right;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
					            </tr>
			            	';
			            }

			            if($return_total != 0)
			            {
			            	$return_data = round($return_total);
			            	
			            	$html_1 .='<tr>
				                <td colspan="2" style="font-size:11px; text-align: right;">Credit Note</td>
				                <td style="font-size:11px; text-align: right;"> '.number_format((float)$return_data, 2, '.', '').'</td>
				            </tr>';	
			            }

			            $html_1 .='<tr>
			                <td colspan="2" style="font-size:11px; text-align: right;">Round off</td>
			                <td style="font-size:11px; text-align: right;"> '.number_format((float)$rond_total, 2, '.', '').'</td>
			            </tr>
			            <tr>
			                <td colspan="2" style="font-size:11px; text-align: right;">Net Total</td>
			                <td style="font-size:11px; text-align: right;"> '.number_format((float)$net_value, 2, '.', '').'</td>
			            </tr>';
				    $html_1 .='</table>';

					$html_1 .='<br><br>
						<table border= "1" cellpadding="1" top="100">
					        <tr>
					            <td rowspan="2" style="font-size:11px; width: 10%;">HSN</td>
					            <td rowspan="2" style="font-size:11px; width: 15%;">Taxable Value</td>';
					            if($adm_state_id == $state_id)
					            {
					            	$html_1 .= '
					            		<td colspan="2" style="font-size:11px; width: 30%; text-align:center;">CGST</td>
					            		<td colspan="2" style="font-size:11px; width: 30%; text-align:center;">SGST</td>
					            	';
					            }
					            else
					            {
					            	$html_1 .= '
					            		<td colspan="2" style="font-size:11px; width: 60%; text-align:center;">IGST</td>
					            	';
					            }
						            
						       $html_1 .= '<td rowspan="2" style="font-size:11px; width: 15%;">Total Tax Amount</td>
					        </tr>
					        <tr>';
					        	if($adm_state_id == $state_id)
					        	{
					        		$html_1 .= '
					        			<td style="font-size: 11px; text-align:center;">Rate</td>
							            <td style="font-size: 11px; text-align:center;">Amt</td>
							            <td style="font-size: 11px; text-align:center;">Rate</td>
							            <td style="font-size: 11px; text-align:center;">Amt</td>
					        		';
					        	}
					        	else
					        	{
					        		$html_1 .= '
					        			<td style="font-size: 11px; text-align:center;">Rate</td>
					            		<td style="font-size: 11px; text-align:center;">Amt</td>
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
			                    		<td style="font-size: 11px; text-align:left;"> '.$hsn_code.'</td>
			                    		<td style="font-size: 11px; text-align:left;"> '.number_format((float)$price_value, 2, '.', '').'</td>';
			                    		if($adm_state_id == $state_id)
			                    		{
			                    			$state_value = $gst_value / 2;
					                        $gst_calc    = $gst_val / 2;
			                    			$html_1 .= '
			                    				<td style="font-size: 11px; text-align:left;"> '.$gst_calc.' %</td>
					                    		<td style="font-size: 11px; text-align:left;"> '.number_format((float)$state_value, 2, '.', '').'</td>
					                    		<td style="font-size: 11px; text-align:left;"> '.$gst_calc.' %</td>
					                    		<td style="font-size: 11px; text-align:left;"> '.number_format((float)$state_value, 2, '.', '').'</td>
			                    			';
			                    		}
			                    		else
			                    		{
			                    			$html_1 .= '
			                    				<td style="font-size: 11px; text-align:left;"> '.$gst_val.' %</td>
				                    			<td style="font-size: 11px; text-align:left;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
			                    			';
			                    		}
			                    		$html_1 .='<td style="font-size: 11px; text-align:left;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
			                    	</tr>
			                    ';
					        }
					        $html_1 .= '
					        	<tr>
					        		<td style="font-size: 11px; text-align:right;"> Total </td>
					        		<td style="font-size: 11px; text-align:left;"> '.number_format((float)$tot_price, 2, '.', '').'</td>';
					        		if($adm_state_id == $state_id)
					        		{
					        			$state_val = $tot_gst / 2;

					        			$html_1 .= '
						        			<td style="font-size: 11px; text-align:left;"> </td>
							        		<td style="font-size: 11px; text-align:left;"> '.number_format((float)$state_val, 2, '.', '').'</td>
							        		<td style="font-size: 11px; text-align:left;"> </td>
							        		<td style="font-size: 11px; text-align:left;"> '.number_format((float)$state_val, 2, '.', '').'</td>
						        		';
					        		}
					        		else
					        		{
					        			$html_1 .= '
						        			<td style="font-size: 11px; text-align:left;"> </td>
							        		<td style="font-size: 11px; text-align:left;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
						        		';
					        		}

					        		$html_1 .='<td style="font-size: 11px; text-align:left;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
					        	</tr>
					        ';
					    $html_1 .='</table>';

					    $html_1 .='<br><br>
						<table border= "1" cellpadding="1" top="100">
					        <tr>
					            <td colspan="5" style="font-size:11px; width: 14%;"> Account Name</td>
					            <td colspan="5" style="font-size:11px; width: 36%;"> '.ACCOUNT_NAME.'</td>
					            <td rowspan="5" style="font-size:11px; width: 50%;">
					            	<span> for '.$adm_username.'</span>
					            	<br><br><br>
					            	<p style="text-align: right; "> Authorised signature </p>
					            </td>
					        </tr>
					        <tr>
					        	<td colspan="5" style="font-size:11px; width: 14%;"> Account No</td>
					            <td colspan="5" style="font-size:11px; width: 36%;"> '.ACCOUNT_NO.'</td>
					        </tr>
					        <tr>
					        	<td colspan="5" style="font-size:11px; width: 14%;"> Bank Name</td>
					            <td colspan="5" style="font-size:11px; width: 36%;"> '.BANK_NAME.'</td>
					        </tr>
					        <tr>
					        	<td colspan="5" style="font-size:11px; width: 14%;"> Branch Name</td>
					            <td colspan="5" style="font-size:11px; width: 36%;"> '.BRANCH_NAME.'</td>
					        </tr>
					        <tr>
					        	<td colspan="5" style="font-size:11px; width: 14%;"> IFSC Code</td>
					            <td colspan="5" style="font-size:11px; width: 36%;"> '.IFSC_CODE.'</td>
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
				$tax_det  = !empty($data_value['tax_details'])?$data_value['tax_details']:'';

	    		// Bill Details
	            $po_no      = !empty($bill_det['po_no'])?$bill_det['po_no']:'';
	            $order_date = !empty($bill_det['order_date'])?$bill_det['order_date']:'';

	            // Admin Details
	            $adm_username = !empty($adm_det['username'])?$adm_det['username']:'';
	            $adm_mobile   = !empty($adm_det['mobile'])?$adm_det['mobile']:'';
	            $adm_address  = !empty($adm_det['address'])?$adm_det['address']:'';
	            $adm_gst_no   = !empty($adm_det['gst_no'])?$adm_det['gst_no']:'';
	            $adm_state_id = !empty($adm_det['state_id'])?$adm_det['state_id']:'';

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
						<td rowspan="2" style="font-size:12px; width: 10%;">HSN</td>
						<td rowspan="2" style="font-size:12px; width: 15%;">Taxable Value</td>';
						if($adm_state_id == $state_id)
						{
							$html .= '
								<td colspan="2" style="font-size:12px; width: 30%; text-align:center;">CGST</td>
								<td colspan="2" style="font-size:12px; width: 30%; text-align:center;">SGST</td>
							';
						}
						else
						{
							$html .= '
								<td colspan="2" style="font-size:12px; width: 60%; text-align:center;">IGST</td>
							';
						}
							
					   $html .= '<td rowspan="2" style="font-size:12px; width: 15%;">Total Tax Amount</td>
					</tr>
					<tr>';
						if($adm_state_id == $state_id)
						{
							$html .= '
								<td style="font-size: 12px; text-align:center;">Rate</td>
								<td style="font-size: 12px; text-align:center;">Amt</td>
								<td style="font-size: 12px; text-align:center;">Rate</td>
								<td style="font-size: 12px; text-align:center;">Amt</td>
							';
						}
						else
						{
							$html .= '
								<td style="font-size: 12px; text-align:center;">Rate</td>
								<td style="font-size: 12px; text-align:center;">Amt</td>
							';
						}
					$html .='</tr>';
					$tot_price = 0;
					$tot_gst   = 0;
					foreach ($tax_det as $key => $value) {
						$hsn_code    = !empty($value['hsn_code'])?$value['hsn_code']:'';
						$gst_val     = !empty($value['gst_val'])?$value['gst_val']:'0';
						$gst_value   = !empty($value['gst_value'])?$value['gst_value']:'0';
						$price_value = !empty($value['price_value'])?$value['price_value']:'0';

						$tot_gst    += $gst_value;
						$tot_price  += $price_value;

						$html .= '
							<tr>
								<td style="font-size: 12px; text-align:left;"> '.$hsn_code.'</td>
								<td style="font-size: 12px; text-align:left;"> '.number_format((float)$price_value, 2, '.', '').'</td>';
								if($adm_state_id == $state_id)
								{
									$state_value = $gst_value / 2;
									$gst_calc    = $gst_val / 2;
									$html .= '
										<td style="font-size: 12px; text-align:left;"> '.$gst_calc.' %</td>
										<td style="font-size: 12px; text-align:left;"> '.number_format((float)$state_value, 2, '.', '').'</td>
										<td style="font-size: 12px; text-align:left;"> '.$gst_calc.' %</td>
										<td style="font-size: 12px; text-align:left;"> '.number_format((float)$state_value, 2, '.', '').'</td>
									';
								}
								else
								{
									$html .= '
										<td style="font-size: 12px; text-align:left;"> '.$gst_val.' %</td>
										<td style="font-size: 12px; text-align:left;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
									';
								}
								$html .='<td style="font-size: 12px; text-align:left;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
							</tr>
						';
					}
					$html .= '
						<tr>
							<td style="font-size: 12px; text-align:right;"> Total </td>
							<td style="font-size: 12px; text-align:left;"> '.number_format((float)$tot_price, 2, '.', '').'</td>';
							if($adm_state_id == $state_id)
							{
								$state_val = $tot_gst / 2;

								$html .= '
									<td style="font-size: 12px; text-align:left;"> </td>
									<td style="font-size: 12px; text-align:left;"> '.number_format((float)$state_val, 2, '.', '').'</td>
									<td style="font-size: 12px; text-align:left;"> </td>
									<td style="font-size: 12px; text-align:left;"> '.number_format((float)$state_val, 2, '.', '').'</td>
								';
							}
							else
							{
								$html .= '
									<td style="font-size: 12px; text-align:left;"> </td>
									<td style="font-size: 12px; text-align:left;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
								';
							}

							$html .='<td style="font-size: 12px; text-align:left;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
						</tr>
					';
				$html .='</table>';

			    $html .='<br><br>
					<table border= "1" cellpadding="1" top="100">
				        <tr>
				            <td colspan="5" style="font-size:11px; width: 13%;"> Account Name</td>
				            <td colspan="5" style="font-size:11px; width: 42%;"> '.ACCOUNT_NAME.'</td>
				            <td rowspan="5" style="font-size:11px; width: 45%;">
				            	<span> for '.ACCOUNT_NAME.'</span>
				            	<br><br><br>
				            	<p style="text-align: right; "> Authorised signature </p>
				            </td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:11px; width: 13%;"> Account No</td>
				            <td colspan="5" style="font-size:11px; width: 42%;"> '.ACCOUNT_NO.'</td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:11px; width: 13%;"> Bank Name</td>
				            <td colspan="5" style="font-size:11px; width: 42%;"> '.BANK_NAME.'</td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:11px; width: 13%;"> Branch Name</td>
				            <td colspan="5" style="font-size:11px; width: 42%;"> '.BRANCH_NAME.'</td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:11px; width: 13%;"> IFSC Code</td>
				            <td colspan="5" style="font-size:11px; width: 42%;"> '.IFSC_CODE.'</td>
				        </tr>
				    </table>';

	          	$pdf->writeHTML($html, true, false, true, false, '');
	       		$pdf->Output($company_name.'_'.$po_no.'_'.date('d-F-Y').'.pdf', 'I');
	    	}
		}

		public function print_json($param1="", $param2="", $param3="")
		{
			header('Access-Control-Allow-Origin: *');
			header('Access-Control-Allow-Methods: GET, POST');
			header('Content-Type: application/json; charset=utf-8');

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


		public function print_einvoice($param1="", $param2="", $param3="")
    	{
    		if(!empty($param1))
    		{
    			$whr_1 = array(
		    		'inv_random' => $param1,
		    		'method'     => '_distributorPrintInvoice',
		    	);

		    	$data_val  = avul_call(API_URL.'distributorpurchase/api/distributor_invoice',$whr_1);	
		    	$data_res  = $data_val['data'];

    			if(!empty($data_res))
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
		            $dis_address  = !empty($inv_det['address'])?mb_strimwidth($inv_det['address'], 0, 95, '...'):'';
		            $dis_pincode  = !empty($inv_det['pincode'])?$inv_det['pincode']:'';

		            // Distributor state details
		            $dis_std_whr  = array('state_id' => $dis_state_id, 'method' => '_detailState');
	            	$dis_std_res  = avul_call(API_URL.'master/api/state',$dis_std_whr);
	            	$dis_std_val  = '';
	            	$dis_std_cod  = '';
	            	if($dis_std_res['status'] == 1)
	            	{
	            		$dis_std_val = ($dis_std_res['data'][0]['state_name'])?$dis_std_res['data'][0]['state_name']:'';
	            		$dis_std_cod = ($dis_std_res['data'][0]['gst_code'])?$dis_std_res['data'][0]['gst_code']:'';
	            	}

			    	// Admin Details
			    	$adm_username = !empty($adm_det['username'])?$adm_det['username']:'';
		            $adm_mobile   = !empty($adm_det['mobile'])?$adm_det['mobile']:'';
		            $adm_address  = !empty($adm_det['address'])?mb_strimwidth($adm_det['address'], 0, 95, '...'):'';
		            $adm_pincode  = !empty($adm_det['pincode'])?$adm_det['pincode']:'';
		            $adm_cntry_cd = !empty($adm_det['country_code'])?$adm_det['country_code']:'';
		            $adm_gst_no   = !empty($adm_det['gst_no'])?$adm_det['gst_no']:'';
		            $adm_state_id = !empty($adm_det['state_id'])?$adm_det['state_id']:'';
		            $adm_state    = !empty($adm_det['adm_state'])?$adm_det['adm_state']:'';
		            $adm_city     = !empty($adm_det['adm_city'])?$adm_det['adm_city']:'';


		            // Admin state details
		            $adm_std_whr  = array('state_id' => $adm_state_id, 'method' => '_detailState');
	            	$adm_std_res  = avul_call(API_URL.'master/api/state',$adm_std_whr);
	            	$adm_std_val  = '';
	            	if($adm_std_res['status'] == 1)
	            	{
	            		$adm_std_val = ($adm_std_res['data'][0]['state_name'])?$adm_std_res['data'][0]['state_name']:'';
	            	}

	            	// Order Details
	            	$item_list  = array();
	            	$total_ass  = 0;
	            	$total_inv  = 0;
	            	$total_cgst = 0;
	            	$total_sgst = 0;
	            	$total_igst = 0;

	            	foreach ($pdt_det as $key => $pdt_val) {
	            		$pdt_code    = !empty($pdt_val['product_code'])?$pdt_val['product_code']:'';
	            		$description = !empty($pdt_val['description'])?$pdt_val['description']:'';
	                    $hsn_code    = !empty($pdt_val['hsn_code'])?$pdt_val['hsn_code']:'';
	                    $gst_val     = !empty($pdt_val['gst_val'])?$pdt_val['gst_val']:'0';
	                    $pdt_price   = !empty($pdt_val['price'])?$pdt_val['price']:'0';
	                    $order_qty   = !empty($pdt_val['order_qty'])?$pdt_val['order_qty']:'0';
	                    $unit_val    = !empty($pdt_val['unit_val'])?$pdt_val['unit_val']:'';

	                    $gst_data    = $pdt_price - ($pdt_price * (100 / (100 + $gst_val)));
	                    $price_val   = $pdt_price - $gst_data;
	                    $tot_amt     = $order_qty * $price_val;
	                    $tot_gst     = $order_qty * $gst_data;
	                    $tot_val     = $order_qty * $pdt_price;
	                    $ass_val     = $tot_amt - 0;
	                    $total_ass  += $ass_val;
	            		$total_inv  += $tot_val;

	                    if($dis_state_id == $adm_state_id)
		                {
		                	$gst_res     = $tot_gst / 2;
		                	$sgstRate    = (float)$gst_res;
		                	$cgstRate    = (float)$gst_res;
		                	$igstRate    = 0;
		                	$total_cgst += $sgstRate;
			            	$total_sgst += $cgstRate;
			            	$total_igst += 0;
		                }
		                else
		                {
		                	$sgstRate    = 0;
		                	$cgstRate    = 0;
		                	$igstRate    = (float)$tot_gst;
		                	$total_cgst += 0;
			            	$total_sgst += 0;
			            	$total_igst += $igstRate;
		                }

	                    $item_list[] = array(
	                    	'item_serial_number'         => $key+1,
					        'product_description'        => $description,
					        'is_service'                 => 'N',
					        'hsn_code'                   => $hsn_code,
					        'bar_code'                   => '',
					        'quantity'                   => $order_qty,
					        'free_quantity'              => '0',
					        'unit'                       => 'NOS',
					        'unit_price'                 => number_format((float)$price_val, 2, '.', ''),
					        'total_amount'               => number_format((float)$tot_amt, 2, '.', ''),
					        'pre_tax_value'              => '0',
					        'discount'                   => '0',
					        'other_charge'               => '0',
					        'assessable_value'           => number_format((float)$ass_val, 2, '.', ''),
					        'gst_rate'                   => $gst_val,
					        'igst_amount'                => number_format((float)$igstRate, 2, '.', ''),
					        'cgst_amount'                => number_format((float)$cgstRate, 2, '.', ''),
					        'sgst_amount'                => number_format((float)$sgstRate, 2, '.', ''),
					        'cess_rate'                  => '0',
					        'cess_amount'                => '0',
					        'cess_nonadvol_amount'       => '0',
					        'state_cess_rate'            => '0',
					        'state_cess_amount'          => '0',
					        'state_cess_nonadvol_amount' => '0',
					        'total_item_value'           => number_format((float)$tot_val, 2, '.', ''),
					        'country_origin'             => $adm_cntry_cd,
					        'order_line_reference'       => 'N',
					        'product_serial_number'      => 'N',
					        'batch_details'              => array(
					        	'name'          => $pdt_code,
					        	'expiry_date'   => '',
					        	'warranty_date' => '',
					        ),
					        'attribute_details'          => array(
					        	'item_attribute_details' => '',
					        	'item_attribute_value'   => '',
					        )
	                    );
	            	}

	            	// Round Val Details
	                $net_value  = round($total_inv);
	                $rond_total = $net_value - $total_inv;

		            $einv_data = array(
						'access_token'        => '67118f6bfaa1efedba09c90f9b2bc578e70f8468',
						// 'user_gstin'          => $adm_gst_no,
						'user_gstin'          => '09AAAPG7885R002',
						'data_source'         => 'erp',
						'transaction_details' => array(
							'supply_type'     => 'B2B', 
							'charge_type'     => 'N', 
							'igst_on_intra'   => 'N', 
							'ecommerce_gstin' => ''
						),
						'document_details'    => array(
							'document_type'   => 'INV', // INV, CRN, DBN
							'document_number' => $invoice_no,
							'document_date'   => date('d/m/Y', strtotime($invoice_date))
						),
						'seller_details'      => array(
							// 'gstin'        => $adm_gst_no,
							'gstin'        => '09AAAPG7885R002',
							'legal_name'   => $adm_username,
							'trade_name'   => $adm_username,
							'address1'     => $adm_address,
							'address2'     => 'None',
							'location'     => $adm_city,
							// 'pincode'      => $adm_pincode,
							'pincode'      => '201301',
							// 'state_code'   => $adm_std_val,
							'state_code'   => 'UTTAR PRADESH',
							'phone_number' => $adm_mobile,
							'email'        => '',
						),
						'buyer_details'       => array(
							// 'gstin'           => $dis_gst_no,
							'gstin'           => '05AAAPG7885R002',
							'legal_name'      => $company_name,
							'trade_name'      => $company_name,
							'address1'        => $dis_address,
							'address2'        => 'None',
							'location'        => 'None',
							'pincode'         => '263001',
							// 'pincode'         => $dis_pincode,
							'place_of_supply' => '05',
							'state_code'      => 'UTTARAKHAND',
							// 'place_of_supply' => $dis_std_cod,
							// 'state_code'      => $dis_std_val,
							'phone_number'    => $dis_mobile,
							'email'           => $dis_email,
						),
						'dispatch_details'    => array(
							'company_name' => $adm_username,
							'address1'     => $adm_address,
							'address2'     => 'None',
							'location'     => $adm_city,
							// 'pincode'      => $adm_pincode,
							'pincode'      => '201301',
							'state_code'   => 'UTTAR PRADESH',
							// 'state_code'   => $adm_std_val,
						),
						'ship_details'        => array(
							// 'gstin'       => $dis_gst_no,
							'gstin'       => '05AAAPG7885R002',
							'legal_name'  => $company_name,
							'trade_name'  => $company_name,
							'address1'    => $dis_address,
							'address2'    => 'None',
							'location'    => 'None',
							'pincode'     => '263001',
							// 'pincode'     => $dis_pincode,
							'state_code'  => 'UTTARAKHAND',
							// 'state_code'  => $dis_std_val,
						),
						'export_details'      => array(
							'ship_bill_number' => '',
							'ship_bill_date'   => '',
							'country_code'     => '',
							'foreign_currency' => '',
							'refund_claim'     => '',
							'port_code'        => '',
							'export_duty'      => '',
						),
						'payment_details'     => array(
							'bank_account_number' => '',
							'paid_balance_amount' => '',
							'credit_days'         => '',
							'credit_transfer'     => '',
							'direct_debit'        => '',
							'branch_or_ifsc'      => '',
							'payment_mode'        => '',
							'payee_name'          => '',
							'outstanding_amount'  => '',
							'payment_instruction' => '',
							'payment_term'        => '',
						),
						'reference_details'   => array(
							'invoice_remarks'            => '',
							'document_period_details'    => array(
								'invoice_period_start_date' => date('Y-m-d', strtotime($invoice_date)),
								'invoice_period_end_date'   => date('Y-m-d', strtotime($invoice_date.' +1 Days')),
							),
							'preceding_document_details' => array(
								'reference_of_original_invoice' => 'N',
								'preceding_invoice_date'        => 'N',
								'other_reference'               => 'N',	
							),
							'contract_details'           => array(
								'receipt_advice_number'      => '',
								'receipt_advice_date'        => '',
								'batch_reference_number'     => '',
								'contract_reference_number'  => '',
								'other_reference'            => '',
								'project_reference_number'   => '',
								'vendor_po_reference_number' => $dis_po_no,
								'vendor_po_reference_date'   => date('d/m/Y', strtotime($order_date)),
							),
						),
						'additional_document_details'   => array(
							'supporting_document_url' => '',
							'supporting_document'     => '',
							'additional_information'  => '',
						),
						'value_details'       => array(
							'total_assessable_value'    => number_format((float)$total_ass, 2, '.', ''),
							'total_cgst_value'          => number_format((float)$total_cgst, 2, '.', ''),
							'total_sgst_value'          => number_format((float)$total_sgst, 2, '.', ''),
							'total_igst_value'          => number_format((float)$total_igst, 2, '.', ''),
							'total_cess_value'          => '0',
							'total_cess_value_of_state' => '0',
							'total_discount'            => '0',
							'total_other_charge'        => '0',
							'total_invoice_value'       => number_format((float)$net_value, 2, '.', ''),
							'round_off_amount'          => number_format((float)$rond_total, 2, '.', ''),
							'total_invoice_value_additional_currency' => '',
						),
						'ewaybill_details'    => array(
							'transporter_id'              => '33AABCK2751L1ZL',
							'transporter_name'            => 'K P N TRAVELS INDIA LIMITED',
							'transportation_mode'         => '1', // 1 Road, 2 Rail, 3 Air, 4 Ship
							'transportation_distance'     => '0',
							'transporter_document_number' => '',
							'transporter_document_date'   => '',
							'vehicle_number'              => 'TN66C1501',
							'vehicle_type'                => 'R', // R Regular, O ODC
						),
						'item_list'           => $item_list,
					);

					header('Content-Type: application/json');
			    	header('Content-Disposition: filename="e-invoice.json"');
			        echo json_encode($einv_data);
			        return;
    			}
    		}
    	}

    	public function get_einvoice($param1="", $param2="", $param3="")
    	{
    		if(!empty($param1))
    		{
    			
    		}
    	}
	}
?>

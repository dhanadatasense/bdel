<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Distributorordertwo extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}
		public function dis_overall_order_dc($param1="", $param2="", $param3="")
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
				$page['page_access']   = userAccess('delivery-challan-view');
				$page['load_data']     = "";
				$page['function_name'] = "dis_overall_order_dc";
				$page['pre_menu']      = "index.php/admin/distributors/list_order_dc";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order_dc',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_overall_order_dc";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('delivery-challan-view'))
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

	            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase_order',$where);
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

		            		$order_id          = !empty($value['order_id'])?$value['order_id']:'';
				            $order_no          = !empty($value['order_no'])?$value['order_no']:'';
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

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="distributorordertwo" data-func="order_process" data-id="'.$order_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
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
				            if(userAccess('delivery-challan-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('delivery-challan-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/distributorordertwo/dis_overall_order_dc/View/'.$order_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$order_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('delivery-challan-edit') == TRUE || userAccess('delivery-challan-view') == TRUE):
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

			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase_order',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				$page['page_access']   = userAccess('delivery-challan-view');
				$page['pre_menu']      = "index.php/admin/distributors/view_orderr";
				$data['page_temp']     = $this->load->view('admin/distributors/view_orderr',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_overall_order_dc";
				$this->bassthaya->load_admin_form_template($data);
			}
		}
		

		public function dis_success_order_dc($param1="", $param2="", $param3="")
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
				$page['page_access']   = userAccess('delivery-challan-view');
				$page['load_data']     = "1";
				$page['function_name'] = "dis_success_order_dc";
				$page['pre_menu']      = "index.php/admin/distributors/list_order_dc";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order_dc',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_success_order_dc";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('delivery-challan-view'))
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
					
	            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase_order',$where);
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

		            		$order_id          = !empty($value['order_id'])?$value['order_id']:'';
				            $order_no          = !empty($value['order_no'])?$value['order_no']:'';
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

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="distributorordertwo" data-func="order_process" data-id="'.$order_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
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
				            if(userAccess('delivery-challan-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('delivery-challan-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/distributorordertwo/dis_success_order_dc/View/'.$order_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$order_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('delivery-challan-edit') == TRUE || userAccess('delivery-challan-view') == TRUE):
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
			
			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase_order',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				$page['page_access']   = userAccess('delivery-challan-view');
				$page['pre_menu']      = "index.php/admin/distributors/view_orderr";
				$data['page_temp']     = $this->load->view('admin/distributors/view_orderr',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_success_order_dc";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function dis_process_order_dc($param1="", $param2="", $param3="")
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
				$page['function_name'] = "dis_process_order_dc";
				$page['page_access']   = userAccess('delivery-challan-view');
				$page['pre_menu']      = "index.php/admin/distributors/list_order_dc";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order_dc',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_process_order_dc";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('delivery-challan-view'))
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

	            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase_order',$where);
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

		            		$order_id          = !empty($value['order_id'])?$value['order_id']:'';
				            $order_no          = !empty($value['order_no'])?$value['order_no']:'';
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

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="distributorsorder" data-func="order_process" data-id="'.$order_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
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
				            if(userAccess('delivery-challan-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('delivery-challan-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/distributorordertwo/dis_process_order_dc/View/'.$order_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$order_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('delivery-challan-edit') == TRUE || userAccess('delivery-challan-view') == TRUE):
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

			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase_order',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				$page['page_access']   = userAccess('delivery-challan-view');
				$page['pre_menu']      = "index.php/admin/distributors/view_orderr";
				$data['page_temp']     = $this->load->view('admin/distributors/view_orderr',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_process_order_dc";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function dis_packing_order_dc($param1="", $param2="", $param3="")
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
				$page['page_access']   = userAccess('delivery-challan-view');
				$page['function_name'] = "dis_packing_order_dc";
				$page['pre_menu']      = "index.php/admin/distributors/list_order_dc";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order_dc',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_packing_order_dc";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				
				if(userAccess('delivery-challan-view'))
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

	            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase_order',$where);
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

		            		$order_id          = !empty($value['order_id'])?$value['order_id']:'';
				            $order_no          = !empty($value['order_no'])?$value['order_no']:'';
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

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="distributorsordetwo" data-func="order_process" data-id="'.$order_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
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
				            if(userAccess('delivery-challan-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('delivery-challan-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/distributorordertwo/dis_packing_order_dc/View/'.$order_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$order_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('delivery-challan-edit') == TRUE || userAccess('delivery-challan-view') == TRUE):
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

			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase_order',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				$page['pre_menu']      = "index.php/admin/distributors/view_order";
				$page['page_access']   = userAccess('delivery-challan-view');
				$data['page_temp']     = $this->load->view('admin/distributors/view_orderr',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_packing_order_dc";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		
		public function dis_shipping_order_dc($param1="", $param2="", $param3="")
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
				$page['page_access']   = userAccess('delivery-challan-view');
				$page['function_name'] = "dis_shipping_order_dc";
				$page['pre_menu']      = "index.php/admin/distributors/list_order_dc";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order_dc',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_shipping_order_dc";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('delivery-challan-view'))
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

	            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase_order',$where);
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

		            		$order_id          = !empty($value['order_id'])?$value['order_id']:'';
				            $order_no          = !empty($value['order_no'])?$value['order_no']:'';
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
				            if(userAccess('delivery-challan-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('delivery-challan-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/distributorordertwo/dis_shipping_order_dc/View/'.$order_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$order_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('delivery-challan-edit') == TRUE || userAccess('delivery-challan-view') == TRUE):
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
				
			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase_order',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				$page['page_access']   = userAccess('delivery-challan-view');
				$page['pre_menu']      = "index.php/admin/distributors/view_orderr";
				$data['page_temp']     = $this->load->view('admin/distributors/view_orderr',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_shipping_order_dc";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function dis_delivery_order_dc($param1="", $param2="", $param3="")
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
				$page['page_access']   = userAccess('delivery-challan-view');
				$page['function_name'] = "dis_delivery_order_dc";
				$page['pre_menu']      = "index.php/admin/distributors/list_order_dc";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order_dc',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_delivery_order_dc";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('delivery-challan-view'))
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

	            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase_order',$where);
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

		            		$order_id          = !empty($value['order_id'])?$value['order_id']:'';
				            $order_no          = !empty($value['order_no'])?$value['order_no']:'';
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

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="distributorsordertwo" data-func="order_process" data-id="'.$po_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
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
				            if(userAccess('delivery-challan-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('delivery-challan-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/distributorordertwo/dis_delivery_order_dc/View/'.$order_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$order_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('delivery-challan-edit') == TRUE || userAccess('delivery-challan-view') == TRUE):
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

			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase_order',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				$page['page_access']   = userAccess('delivery-challan-view');
				$page['pre_menu']      = "index.php/admin/distributors/view_orderr";
				$data['page_temp']     = $this->load->view('admin/distributors/view_orderr',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_delivery_order_dc";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function dis_complete_order_dc($param1="", $param2="", $param3="")
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
				$page['page_access']   = userAccess('delivery-challan-view');
				$page['function_name'] = "dis_complete_order_dc";
				$page['pre_menu']      = "index.php/admin/distributors/list_order_dc";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order_dc',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_complete_order_dc";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('delivery-challan-view'))
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

	            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase_order',$where);
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

		            		$order_id          = !empty($value['order_id'])?$value['order_id']:'';
				            $order_no          = !empty($value['order_no'])?$value['order_no']:'';
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

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="distributorsorder" data-func="order_process" data-id="'.$order_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
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
				            if(userAccess('delivery-challan-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('delivery-challan-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/distributorordertwo/dis_complete_order_dc/View/'.$order_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$order_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('delivery-challan-edit') == TRUE || userAccess('delivery-challan-view') == TRUE):
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

			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase_order',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				$page['page_access']   = userAccess('delivery-challan-view');
				$page['pre_menu']      = "index.php/admin/distributors/view_orderr";
				$data['page_temp']     = $this->load->view('admin/distributors/view_orderr',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_complete_order_dc";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function dis_cancle_order_dc($param1="", $param2="", $param3="")
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
				$page['page_access']   = userAccess('delivery-challan-view');
				$page['function_name'] = "dis_cancle_order_dc";
				$page['pre_menu']      = "index.php/admin/distributors/list_order_dc";
				$data['page_temp']     = $this->load->view('admin/distributors/list_order_dc',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_cancle_order_dc";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('delivery-challan-view'))
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

	            	$data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase_order',$where);
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

		            		$order_id          = !empty($value['order_id'])?$value['order_id']:'';
				            $order_no          = !empty($value['order_no'])?$value['order_no']:'';
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

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="distributorsorder" data-func="order_process" data-id="'.$order_id.'" data-method="changeOrder_status"><i class="ft-edit"></i></a>';
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
				            if(userAccess('delivery-challan-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('delivery-challan-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/distributorordertwo/dis_cancle_order_dc/View/'.$order_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$order_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($company_name, 0, 20, '...').'</td>
	                                <td class="line_height">'.date('d-M-Y', strtotime($order_date)).'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('delivery-challan-edit') == TRUE || userAccess('delivery-challan-view') == TRUE):
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

			    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase_order',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				$page['page_access']   = userAccess('delivery-challan-view');
				$page['pre_menu']      = "index.php/admin/distributors/view_orderr";
				$data['page_temp']     = $this->load->view('admin/distributors/view_orderr',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_cancle_order_dc";
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
                
				$data_save = avul_call(API_URL.'distributorpurchase/api/orderr_process',$order_data);

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

					$data_save = avul_call(API_URL.'distributorpurchase/api/orderr_process',$order_data);

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

					$data_save = avul_call(API_URL.'distributorpurchase/api/orderr_process',$order_data);

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
				$dly_address  = $this->input->post('dly_address');

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
			    		'auto_id'    => $order_id,
			    		'invoice_id' => $invoice_id,
						'progress'   => $order_status,
						'reason'     => $message,
						'length'     => $length,
						'breadth'    => $breadth,
						'height'     => $height,
						'weight'     => $weight,
						'diy_address'=> $dly_address,
						'method'     => '_updateOrderProgress',
					);

					$data_save = avul_call(API_URL.'distributorpurchase/api/orderr_process',$order_data);

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

					$update = avul_call(API_URL.'distributorpurchase/api/orderr_process',$order_data);

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
			    		'auto_id' => $order_id,
						'method'  => '_changePackStatus',
					);
                 
					$data_save = avul_call(API_URL.'distributorpurchase/api/orderr_process',$order_data);

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

					    $data_save = avul_call(API_URL.'distributorpurchase/api/orderr_process',$data);

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
			    		'order_id'         => $order_id,
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
				$page['pre_menu']      = "index.php/admin/distributorsorder/dis_packing_order_dc/View/$order_id";
				$data['page_temp']     = $this->load->view('admin/distributors/stock_add',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dis_packing_order_dc";
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

		

		public function print_order($param1="", $param2="", $param3="")
		{
			$purchase_id = $param1;

			$where = array(
		    	'purchase_id' => $purchase_id,
		    	'view_type'   => 1,
		    	'method'      => '_viewDistributorPurchase'
		    );

		    $data_list  = avul_call(API_URL.'distributorpurchase/api/manage_purchase_order',$where);
        	$data_value = !empty($data_list['data'])?$data_list['data']:'';

	    	if($data_value)
	    	{
	    		$bill_det = !empty($data_value['bill_details'])?$data_value['bill_details']:'';
	    		$adm_det  = !empty($data_value['admin_details'])?$data_value['admin_details']:'';
	    		$dis_det  = !empty($data_value['distributor_details'])?$data_value['distributor_details']:'';
	    		$ord_det  = !empty($data_value['order_details'])?$data_value['order_details']:'';

	    		// Bill Details
	            $order_no      = !empty($bill_det['order_no'])?$bill_det['order_no']:'';
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

	          	$html .= '<p style="color:black; font-size:12px; text-align: center;"><strong style="font-size:18px; padding-bottom:1000px;">'.$adm_username.'</strong><br/>'.$adm_address.', Contact No: '.$adm_mobile.'<br>GSTIN\UIN : '.$adm_gst_no.'<br><strong style="color:black; text-align:center; font-size:17px;"> PROFORMA ORDER</strong><br></p>';

	          	$html .='<br><br><br>
					<table border= "1" cellpadding="1" top="100">
				        <tr>
				            <td rowspan="6" style="font-size:12px; width: 55%; margin-left:10px;">Shipped To: <br> '.$company_name.'<br> '.$address.'<br> Contact No: '.$mobile.'<br> GSTIN\UIN :'.$gst_no.'</td>
				            <td style="font-size:12px; width: 20%;"> Voucher No</td>
				            <td style="font-size:12px; width: 25%;"> '.$order_no.'</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Voucher Date</td>
				            <td style="font-size:12px; width: 25%;"> '.date('d-M-Y', strtotime($order_date)).'</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Buyer\'s Ref No</td>
				            <td style="font-size:12px; width: 25%;"> '.$order_no.'</td>
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
		                // $tot_gst = 0;
		                $net_tot = 0;
		                $tot_qty = 0;
				        foreach ($ord_det as $key => $val) {
				        	$description = !empty($val['description'])?$val['description']:'';
		                    $hsn_code    = !empty($val['hsn_code'])?$val['hsn_code']:'';
		                    $gst_value   = !empty($val['gst_val'])?$val['gst_val']:'0';
		                    $price       = !empty($val['product_price'])?$val['product_price']:'0';
		                    $order_qty   = !empty($val['product_qty'])?$val['product_qty']:'0';

		                    // $gst_data  = $price - ($price * (100 / (100 + $gst_value)));
		                    // $price_val = $price - $gst_data;
		                    $tot_price = $order_qty * $price;
		                    $sub_tot  += $tot_price;

		                    // GST Calculation
		                    // $gst_val   = $order_qty * $gst_data;
		                    // $tot_gst  += $gst_val;
		                    $total_val = $order_qty * $price;
		                    $net_tot  += $total_val;
		                    $tot_qty  += $order_qty;

		                    $html .= '
		                    	<tr>
						            <td style="font-size:12px; width: 5%;"> '.$num.'</td>
						            <td style="font-size:12px; width: 44%;"> '.$description.'</td>
						            <td style="font-size:12px; text-align: center; width: 10%;">'.$hsn_code.'</td>
						            <td style="font-size:12px; text-align: center; width: 10%;">'.number_format((float)$price, 2, '.', '').'</td>
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
		            // if($adm_state_id == $state_id)
		            // {
		            // 	$gst_value = $tot_gst / 2;

		            // 	$html .= '
		            // 		<tr>
				    //             <td colspan="2" style="font-size:12px; text-align: right;">SGST</td>
				    //             <td style="font-size:12px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
				    //         </tr>
				    //         <tr>
				    //             <td colspan="2" style="font-size:12px; text-align: right;">CGST</td>
				    //             <td style="font-size:12px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
				    //         </tr>
		            // 	';
		            // }
		            // else
		            // {
		            // 	$html .= '
		            // 		<tr>
				    //             <td colspan="2" style="font-size:12px; text-align: right;">IGST</td>
				    //             <td style="font-size:12px; text-align: right;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
				    //         </tr>
		            // 	';
		            // }

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
	       		$pdf->Output($company_name.'_'.$order_no.'_'.date('d-F-Y').'.pdf', 'I');
	    	}
		}

		public function print_json($param1="", $param2="", $param3="")
		{
			$whr_1 = array(
	    		'dc_random' => $param1,
	    		'method'     => '_distributorPrintDc',
	    	);

	    	$data_val  = avul_call(API_URL.'distributorpurchase/api/distributor_dc',$whr_1);	
	    	$data_res  = $data_val['data'];

	    	// print_r($data_res); exit;

	    	if($data_res)
	    	{
	    		$dc_det = !empty($data_res['dc_details'])?$data_res['dc_details']:'';
		    	$adm_det = !empty($data_res['admin_details'])?$data_res['admin_details']:'';
		    	$pdt_det = !empty($data_res['product_details'])?$data_res['product_details']:'';
		    	$tax_det = !empty($data_res['tax_details'])?$data_res['tax_details']:'';
		    	$ret_det = !empty($data_res['return_details'])?$data_res['return_details']:'';

		    	// Invocie Details
		    	$order_id     = !empty($dc_det['order_id'])?$dc_det['order_id']:'';
	            $due_days     = !empty($dc_det['due_days'])?$dc_det['due_days']:'';
	            $dc_no   = !empty($dc_det['dc_no'])?$dc_det['dc_no']:'';
	            $dc_date = !empty($dc_det['dc_date'])?$dc_det['dc_date']:'';
	            $dis_po_no    = !empty($dc_det['po_no'])?$dc_det['po_no']:'';
	            $order_date   = !empty($dc_det['order_date'])?$dc_det['order_date']:'';
	            $company_name = !empty($dc_det['company_name'])?$dc_det['company_name']:'';
	            $dis_mobile   = !empty($dc_det['mobile'])?$dc_det['mobile']:'';
	            $dis_email    = !empty($dc_det['email'])?$dc_det['email']:'';
	            $dis_state_id = !empty($dc_det['state_id'])?$dc_det['state_id']:'';
	            $dis_state    = !empty($dc_det['dis_state'])?$dc_det['dis_state']:'';
	            $dis_gst_no   = !empty($dc_det['gst_no'])?$dc_det['gst_no']:'';
	            $dis_address  = !empty($dc_det['address'])?$dc_det['address']:'';
	            $dis_pincode  = !empty($dc_det['pincode'])?$dc_det['pincode']:'';

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
		    		'docType'             => 'DC',
		    		'docNo'               => $dc_no,
		    		'docDate'             => date('d/m/Y', strtotime($dc_date)),
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
		    	$file_name = 'Ewaybill_'.$dc_no.'_'.date('d-M-Y');
		    	
		    	header('Content-Type: application/json');
		    	header('Content-Disposition: filename="'.$file_name.'.json"');

		    	$response['version']   = '1.0.0219';
		        $response['billLists'] = $bill_details;
		        echo json_encode($response);
		        return;
	    	}
		}

		public function print_dc($param1="", $param2="", $param3="")
		{
			$whr_1 = array(
	    		'dc_random' => $param1,
	    		'method'     => '_distributorPrintDc',
	    	);

	    	$data_val  = avul_call(API_URL.'distributorpurchase/api/distributor_dc',$whr_1);	
	    	$data_res  = $data_val['data'];

	    	if($data_res)
	    	{
	    		$dc_det = !empty($data_res['dc_details'])?$data_res['dc_details']:'';
	    		$admin_det   = !empty($data_res['admin_details'])?$data_res['admin_details']:'';
	    		$product_det = !empty($data_res['product_details'])?$data_res['product_details']:'';
	    		$tax_det     = !empty($data_res['tax_details'])?$data_res['tax_details']:'';
	    		$return_det  = !empty($data_res['return_details'])?$data_res['return_details']:'';

	    		// Return Details
    			$return_total = !empty($return_det['return_total'])?$return_det['return_total']:'0';

	    		// Invocie Details
	    		$dc_no   = !empty($dc_det['dc_no'])?$dc_det['dc_no']:'';
	            $dc_date      = !empty($dc_det['dc_date'])?$dc_det['dc_date']:'';
	            $order_no     = !empty($dc_det['order_no'])?$dc_det['order_no']:'';
	            $order_date   = !empty($dc_det['order_date'])?$dc_det['order_date']:'';
	            $company_name = !empty($dc_det['company_name'])?$dc_det['company_name']:'';
	            $mobile       = !empty($dc_det['mobile'])?$dc_det['mobile']:'';
	            $email        = !empty($dc_det['email'])?$dc_det['email']:'';
	            $state_id     = !empty($dc_det['state_id'])?$dc_det['state_id']:'';
	            $gst_no       = !empty($dc_det['gst_no'])?$dc_det['gst_no']:'';
	            $address      = !empty($dc_det['address'])?$dc_det['address']:'';
	            $length       = !empty($dc_det['length'])?$dc_det['length']:'0';
	            $breadth      = !empty($dc_det['breadth'])?$dc_det['breadth']:'0';
	            $height       = !empty($dc_det['height'])?$dc_det['height']:'0';
	            $weight       = !empty($dc_det['weight'])?$dc_det['weight']:'0';
				$dly_address       = !empty($dc_det['dly_address'])?$dc_det['dly_address']:'0';
	            // Admin Details
	            $adm_username = !empty($admin_det['username'])?$admin_det['username']:'';
	            $adm_mobile   = !empty($admin_det['mobile'])?$admin_det['mobile']:'';
	            $adm_address  = !empty($admin_det['address'])?$admin_det['address']:'';
	            $adm_gst_no   = !empty($admin_det['gst_no'])?$admin_det['gst_no']:'';
	            $adm_state_id = !empty($admin_det['state_id'])?$admin_det['state_id']:'';

	            $this->load->library('Pdf');
	      		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'mm', array(216,356), TRUE, 'UTF-8', FALSE);
	          	$pdf->SetTitle('Manufacturer Delivery Challan');
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
		          	$html_1 .= '<span style="color:black; text-align:right; font-size:13px; text-transform: uppercase;">'.$bill_type[$i].'</span><p style="color:black; font-size:12px; text-align: center;"><strong style="font-size:18px; padding-bottom:1000px;">'.$adm_username.'</strong><br/>'.$adm_address.',<br>Contact No: '.$adm_mobile.', GSTIN\UIN : '.$adm_gst_no.'<br><strong style="color:black; text-align:center; font-size:17px;"> DELIVARY CHALLAN </strong><br></p>';

		          	$html_1 .='<br><br><br>
						<table border= "1" cellpadding="7" top="100">
					        <tr>
					            <td rowspan="7" style="font-size:12px; width: 50%; margin-left:10px;">Consignee: <br> '.$company_name.'<br> '.$address.'<br> Contact No: '.$mobile.'<br> GSTIN\UIN :'.$gst_no.'</td>
								
								
					            
								<td style="font-size:12px; width: 20%;"> DC No</td>
					            <td style="font-size:12px; width: 30%;"> '.$dc_no.'</td>
					        </tr>
							
					        <tr>

							
					            <td style="font-size:12px; width: 20%;"> DC Date</td>
					            <td style="font-size:12px; width: 30%;"> '.date('d-M-Y', strtotime($dc_date)).'</td>
					        </tr>
					        <tr>
					            <td style="font-size:12px; width: 20%;"> Distributor(s) Order No</td>
					            <td style="font-size:12px; width: 30%;"> '.$order_no.'</td>
					        </tr>
					        <tr>
					            <td style="font-size:12px; width: 20%;"> Distributor(s) Order Date</td>
					            <td style="font-size:12px; width: 30%;"> '.date('d-M-Y', strtotime($order_date)).'</td>
					        </tr>
					      
							
					    </table>
					    ';
						$html_1 .='
						<table border= "1" cellpadding="7" top="100">
					        <tr>
					            <td rowspan="7" style="font-size:12px; width: 50%; margin-left:10px;">Buyer (if other than consignee): <br>'.$dly_address.'<br> </td>
								
								
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
						
						<tr>
						<td style="font-size:12px; width: 50%;"> </td>
							</tr>
					    </table>
					    ';

				    $html_1 .='<br><br>
						<table border= "1" cellpadding="1" top="100">
					        <tr>
					            <td style="font-size:12px; width: 5%;">S.No</td>
					            <td style="font-size:12px; width: 44%;">Description of Goods</td>
					            <td style="font-size:12px; text-align: center; width: 10%;">HSN/SAC</td>
								<td style="font-size:12px; text-align: center; width: 10%;">Rate</td>
					            <td style="font-size:12px; text-align: center; width: 8%;">Quantity</td>
					            <td style="font-size:12px; text-align: center; width: 8%;">Per</td>
					            <td style="font-size:12px; text-align: center; width: 15%;">Amount</td>
					        </tr>';

					        $num     = 1;
					        $sub_tot = 0;
			                // $tot_gst = 0;
			                $net_tot = 0;
			                $tot_qty = 0;
					        foreach ($product_det as $key => $val) {
					        	$description = !empty($val['description'])?$val['description']:'';
			                    $hsn_code    = !empty($val['hsn_code'])?$val['hsn_code']:'';
			                    $gst_value   = !empty($val['gst_val'])?$val['gst_val']:'0';
			                    $price       = !empty($val['price'])?$val['price']:'0';
			                    $order_qty   = !empty($val['order_qty'])?$val['order_qty']:'0';

			                    // $gst_data  = $price - ($price * (100 / (100 + $gst_value)));
			                    // $price_val = $price - $gst_data;
								
			                    $tot_price = $order_qty * $price;
			                    $sub_tot  += $tot_price;

			                    // GST Calculation
			                    // $gst_val   = $order_qty * $gst_data;
			                    // $tot_gst  += $gst_val;
			                    $total_val = $order_qty * $price;
			                    $net_tot  += $total_val;
			                    $tot_qty  += $order_qty;

			                    $html_1 .= '
			                    	<tr>
							            <td style="font-size:12px; width: 5%;">'.$num.'</td>
							            <td style="font-size:12px; width: 44%;">'.$description.'</td>
							            <td style="font-size:12px; text-align: center; width: 10%;">'.$hsn_code.'</td>
										<td style="font-size:12px; text-align: center; width: 10%;">'.number_format((float)$price, 2, '.', '').'</td>
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
				    	$rowspan = '7';
				    }

	                // Round Val Details
	                $total_amt  = $net_tot - $return_total;
	                $net_value  = round($total_amt);
	                $rond_total = $net_value - $total_amt;

				    $html_1 .= '
			            <tr>
			                <td rowspan ="'.$rowspan.'"  colspan="5"></td>
			                <td colspan="1" style="font-size:12px; text-align: right;">Qty</td>
			                <td style="font-size:12px; text-align: right;"> '.$tot_qty.'</td>
			                
			            </tr>
			            <tr>
			                <td colspan="1" style="font-size:12px; text-align: right;">Sub Total</td>
			                <td style="font-size:12px; text-align: right;"> '.number_format((float)$sub_tot, 2, '.', '').'</td>
			            </tr>';
			            // if($adm_state_id == $state_id)
			            // {
			            // 	$gst_value = $tot_gst / 2;

			            // 	$html_1 .= '
			            // 		<tr>
					    //             <td colspan="2" style="font-size:12px; text-align: right;">SGST</td>
					    //             <td style="font-size:12px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
					    //         </tr>
					    //         <tr>
					    //             <td colspan="2" style="font-size:12px; text-align: right;">CGST</td>
					    //             <td style="font-size:12px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
					    //         </tr>
			            // 	';
			            // }
			            // else
			            // {
			            // 	$html_1 .= '
			            // 		<tr>
					    //             <td colspan="2" style="font-size:12px; text-align: right;">IGST</td>
					    //             <td style="font-size:12px; text-align: right;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
					    //         </tr>
			            // 	';
			            // }

			            if($return_total != 0)
			            {
			            	$return_data = round($return_total);
			            	
			            	$html_1 .='<tr>
				                <td colspan="1" style="font-size:12px; text-align: right;">Credit Note</td>
				                <td style="font-size:12px; text-align: right;"> '.number_format((float)$return_data, 2, '.', '').'</td>
				            </tr>';	
			            }

			            $html_1 .='<tr>
			                <td colspan="1" style="font-size:12px; text-align: right;">Round off</td>
			                <td style="font-size:12px; text-align: right;"> '.number_format((float)$rond_total, 2, '.', '').'</td>
			            </tr>
			            <tr>
			                <td colspan="1" style="font-size:12px; text-align: right;">Net Total</td>
			                <td style="font-size:12px; text-align: right;"> '.number_format((float)$net_value, 2, '.', '').'</td>
			            </tr>';
				    $html_1 .='</table>';

					//$html_1 .='<br><br>
						// <table border= "1" cellpadding="1" top="100">
					    //     <tr>
					    //         <td rowspan="2" style="font-size:12px; width: 10%;">HSN</td>
					    //         <td rowspan="2" style="font-size:12px; width: 15%;">Taxable Value</td>';
					    //         if($adm_state_id == $state_id)
					    //         {
					    //         	$html_1 .= '
					    //         		<td colspan="2" style="font-size:12px; width: 30%; text-align:center;">CGST</td>
					    //         		<td colspan="2" style="font-size:12px; width: 30%; text-align:center;">SGST</td>
					    //         	';
					    //         }
					    //         else
					    //         {
					    //         	$html_1 .= '
					    //         		<td colspan="2" style="font-size:12px; width: 60%; text-align:center;">IGST</td>
					    //         	';
					    //         }
						            
						//        $html_1 .= '<td rowspan="2" style="font-size:12px; width: 15%;">Total Tax Amount</td>
					    //     </tr>
					    //     <tr>';
					    //     	if($adm_state_id == $state_id)
					    //     	{
					    //     		$html_1 .= '
					    //     			<td style="font-size: 12px; text-align:center;">Rate</td>
						// 	            <td style="font-size: 12px; text-align:center;">Amt</td>
						// 	            <td style="font-size: 12px; text-align:center;">Rate</td>
						// 	            <td style="font-size: 12px; text-align:center;">Amt</td>
					    //     		';
					    //     	}
					    //     	else
					    //     	{
					    //     		$html_1 .= '
					    //     			<td style="font-size: 12px; text-align:center;">Rate</td>
					    //         		<td style="font-size: 12px; text-align:center;">Amt</td>
					    //     		';
					    //     	}
					    //     $html_1 .='</tr>';
					    //     $tot_price = 0;
						// 	$tot_gst   = 0;
					    //     foreach ($tax_det as $key => $value) {
					    //     	$hsn_code    = !empty($value['hsn_code'])?$value['hsn_code']:'';
			            //         $gst_val     = !empty($value['gst_val'])?$value['gst_val']:'0';
			            //         $gst_value   = !empty($value['gst_value'])?$value['gst_value']:'0';
			            //         $price_value = !empty($value['price_value'])?$value['price_value']:'0';

			            //         $tot_gst    += $gst_value;
					    //         $tot_price  += $price_value;

			            //         $html_1 .= '
			            //         	<tr>
			            //         		<td style="font-size: 12px; text-align:left;"> '.$hsn_code.'</td>
			            //         		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$price_value, 2, '.', '').'</td>';
			            //         		if($adm_state_id == $state_id)
			            //         		{
			            //         			$state_value = $gst_value / 2;
					    //                     $gst_calc    = $gst_val / 2;
			            //         			$html_1 .= '
			            //         				<td style="font-size: 12px; text-align:left;"> '.$gst_calc.' %</td>
					    //                 		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$state_value, 2, '.', '').'</td>
					    //                 		<td style="font-size: 12px; text-align:left;"> '.$gst_calc.' %</td>
					    //                 		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$state_value, 2, '.', '').'</td>
			            //         			';
			            //         		}
			            //         		else
			            //         		{
			            //         			$html_1 .= '
			            //         				<td style="font-size: 12px; text-align:left;"> '.$gst_val.' %</td>
				        //             			<td style="font-size: 12px; text-align:left;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
			            //         			';
			            //         		}
			            //         		$html_1 .='<td style="font-size: 12px; text-align:left;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
			            //         	</tr>
			            //         ';
					    //     }
					    //     $html_1 .= '
					    //     	<tr>
					    //     		<td style="font-size: 12px; text-align:right;"> Total </td>
					    //     		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$tot_price, 2, '.', '').'</td>';
					    //     		if($adm_state_id == $state_id)
					    //     		{
					    //     			$state_val = $tot_gst / 2;

					    //     			$html_1 .= '
						//         			<td style="font-size: 12px; text-align:left;"> </td>
						// 	        		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$state_val, 2, '.', '').'</td>
						// 	        		<td style="font-size: 12px; text-align:left;"> </td>
						// 	        		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$state_val, 2, '.', '').'</td>
						//         		';
					    //     		}
					    //     		else
					    //     		{
					    //     			$html_1 .= '
						//         			<td style="font-size: 12px; text-align:left;"> </td>
						// 	        		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
						//         		';
					    //     		}

					    //     		$html_1 .='<td style="font-size: 12px; text-align:left;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
					    //     	</tr>
					    //     ';
					    // $html_1 .='</table>';

					    $html_1 .='<br><br>
						<table border= "1" cellpadding="1" top="100">
					        <tr>
					            <td colspan="5" style="font-size:12px; width: 14%;"> Account Name</td>
					            <td colspan="5" style="font-size:12px; width: 36%;"> '.ACCOUNT_NAME.'</td>
					            <td rowspan="5" style="font-size:12px; width: 50%;">
					            	<span> for '.$adm_username.'</span>
					            	<br><br><br>
					            	<p style="text-align: right; "> Authorised signature </p>
					            </td>
					        </tr>
					        <tr>
					        	<td colspan="5" style="font-size:12px; width: 14%;"> Account No</td>
					            <td colspan="5" style="font-size:12px; width: 36%;"> '.ACCOUNT_NO.'</td>
					        </tr>
					        <tr>
					        	<td colspan="5" style="font-size:12px; width: 14%;"> Bank Name</td>
					            <td colspan="5" style="font-size:12px; width: 36%;"> '.BANK_NAME.'</td>
					        </tr>
					        <tr>
					        	<td colspan="5" style="font-size:12px; width: 14%;"> Branch Name</td>
					            <td colspan="5" style="font-size:12px; width: 36%;"> '.BRANCH_NAME.'</td>
					        </tr>
					        <tr>
					        	<td colspan="5" style="font-size:12px; width: 14%;"> IFSC Code</td>
					            <td colspan="5" style="font-size:12px; width: 36%;"> '.IFSC_CODE.'</td>
					        </tr>
					    </table>';

		          	$pdf->writeHTML($html_1, true, false, true, false, '');
	          	}
				  ob_end_clean();
	       		$pdf->Output($company_name.'_'.$dc_no.'_'.date('d-F-Y').'.pdf', 'I');
	    	}
		}
	}

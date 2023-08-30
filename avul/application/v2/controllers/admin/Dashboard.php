<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Dashboard extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function index($param1="", $param2="", $param3="")
		{	

			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$active_year = $this->session->userdata('active_year');

        	// Master Count
        	$whereOne = array(
        		'financial_year' => $active_year,
        		'method'         => '_adminDashboard'
        	);

        	$data_list  = avul_call(API_URL.'dashboard/api/admin_dashboard',$whereOne);
        	$data_value = !empty($data_list['data'])?$data_list['data']:'';

        	// Master Count
        	$whereTwo = array(
        		'method'         => '_orderReport',
								
        	);

        	$sales_list  = avul_call(API_URL.'report/api/dashboard_report',$whereTwo);
        	$sales_value = !empty($sales_list['data'])?$sales_list['data']:'';

        	// Master Count
        	$whereThree = array(
        		'method'         => '_attendaceReport',
				        	);

        	$attendance_list  = avul_call(API_URL.'report/api/dashboard_report',$whereThree);
        	$attendance_value = !empty($attendance_list['data'])?$attendance_list['data']:'';

        	// Overall Order Count
        	$whereFour = array(
        		'method'         => '_orderCountReport',
				        	);

        	$invoice_list  = avul_call(API_URL.'report/api/dashboard_report',$whereFour);
        	$order_value = !empty($order_list['data'])?$order_list['data']:'';

        	// Distributor wise invoice count
        	$whereFive = array(
        		'method' => '_distributorInvoiceReport'
        	);

        	$invoice_list  = avul_call(API_URL.'report/api/dashboard_report',$whereFour);
        	$order_value = !empty($order_list['data'])?$order_list['data']:'';

        	// Distributor wise invoice count
        	$whereFive = array(
        		'method' => '_distributorInvoiceReport'
        	);

        	$invoice_list  = avul_call(API_URL.'report/api/dashboard_report',$whereFive);
        	$invoice_value = !empty($invoice_list['data'])?$invoice_list['data']:'';

        	
        	$page['data_value']       = $data_value;
        	$page['sales_value']      = $sales_value;
        	$page['attendance_value'] = $attendance_value;
        	$page['order_value']      = $order_value;
        	$page['invoice_value']    = $invoice_value;
			$page['page_heading']     = "Dashboard";
			$page['page_title']       = "Dashboard";
			$data['page_temp']        = $this->load->view('admin/dashboard',$page,TRUE);
			$data['view_file']        = "Page_Template";
			$data['title']            = "Dashboard";
			$data['currentmenu']      = "dashboard";
			$this->bassthaya->load_admin_dashboard_template($data);
		}

		public function order_count($param1="", $param2="", $param3="")
		{
			// Master Count
        	$whereOne = array(
        		'method'         => '_adminOrderCount'
        	);

        	$data_list  = avul_call(API_URL.'dashboard/api/order_count',$whereOne);
        	$data_value = !empty($data_list['data'])?$data_list['data']:'';

        	$str_ord = !empty($data_value['outlet_order'])?$data_value['outlet_order']:'0';
    		$dis_ord = !empty($data_value['distributor_order'])?$data_value['distributor_order']:'0';

    		$order_res = array(
				'outlet_order'      => $str_ord,
				'distributor_order' => $dis_ord,
			);

			$response['status']  = 1;
	        $response['message'] = "Success"; 
	        $response['data']    = $order_res;
	        echo json_encode($response);
	        return;
		}

		public function admin_chart($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

			$method      = $this->input->post('method');
			$permission=$this->session->userdata('permission');
			$employee_id=$this->session->userdata('id');
     		$designation_code = $this->session->userdata('designation_code');
			// Master Count
			if($method =='_adminTargetDashboard'){
				$whereOne = array(
					'method'         => '_adminTargetDashboard',
					'designation_code'    => $designation_code,
					'permission'     => $permission,
					'id'             => $employee_id,
				);
	
				$data_list  = avul_call(API_URL.'dashboard/api/admin_chart',$whereOne);
				$data_value = !empty($data_list['data'])?$data_list['data']:'';
	
				
	
				$response['status']  = 1;
				$response['message'] = "Success"; 
				$response['data']    = $data_value;
				echo json_encode($response);
				return;
			}else if($method =='_adminOrderDashboard'){
				$whereOne = array(
					'method'         => '_adminOrderDashboard',
					'designation_code'    => $designation_code,
					'permission'     => $permission,
					'id'             => $employee_id,
				);
	
				$data_list  = avul_call(API_URL.'dashboard/api/admin_chart',$whereOne);
				$data_value = !empty($data_list['data'])?$data_list['data']:'';
	
				
	
				$response['status']  = 1;
				$response['message'] = "Success"; 
				$response['data']    = $data_value;
				echo json_encode($response);
				return;
			}
        	
		}
	}
?>
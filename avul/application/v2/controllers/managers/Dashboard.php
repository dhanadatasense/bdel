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

        	$manager_id = $this->session->userdata('id');
        	$financial_year = $this->session->userdata('active_year');

        	$where = array(
        		'method'         => '_managerDashboard',
        		'manager_id' => $manager_id,
        		'financial_year' => $financial_year,
        	);

        	$data_list  = avul_call(API_URL.'dashboard/api/manager_dashboard',$where);
            $data_value = !empty($data_list['data'])?$data_list['data']:'';

			// Master Count
        	$whereTwo = array(
        		'method'         => '_orderReport',
				'id'             => $manager_id
				
        	);

        	$sales_list  = avul_call(API_URL.'report/api/dashboard_report_mg',$whereTwo);
        	$sales_value = !empty($sales_list['data'])?$sales_list['data']:'';

        	// Master Count
        	$whereThree = array(
        		'method'         => '_attendaceReport',
				'id'             => $manager_id
        	);

        	$attendance_list  = avul_call(API_URL.'report/api/dashboard_report_mg',$whereThree);
        	$attendance_value = !empty($attendance_list['data'])?$attendance_list['data']:'';

        	// Overall Order Count
        	$whereFour = array(
        		'method'         => '_orderCountReport',
				'id'             => $manager_id
        	);

        	$invoice_list  = avul_call(API_URL.'report/api/dashboard_report_mg',$whereFour);
        	$order_value = !empty($invoice_list['data'])?$invoice_list['data']:'';

        	// // Distributor wise invoice count
        	// $whereFive = array(
        	// 	'method' => '_distributorInvoiceReport',
			// 	'id'             => $manager_id
        	// );

        	// $invoice_list  = avul_call(API_URL.'report/api/dashboard_report_mg',$whereFive);
        	// $invoice_value = !empty($invoice_list['data'])?$invoice_list['data']:'';


        	$page['data_value']       = $data_value;
        	$page['sales_value']      = $sales_value;
        	$page['attendance_value'] = $attendance_value;
        	$page['order_value']      = $order_value;
        	// $page['invoice_value']    = $invoice_value;
			$page['page_heading'] = "Dashboard";
			$page['page_title']   = "Dashboard";
			$data['page_temp']    = $this->load->view('managers/dashboard',$page,TRUE);
			$data['view_file']    = "Page_Template";
			$data['title']        = "Dashboard";
			$data['currentmenu']  = "dashboard";
			$this->bassthaya->load_Managers_dashboard_template($data);
		}
	}
?>
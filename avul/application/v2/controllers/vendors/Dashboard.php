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

        	$vendor_id   = $this->session->userdata('id');
        	$active_year = $this->session->userdata('active_year');

        	// Master Count
        	$whereOne = array(
        		'vendor_id'      => $vendor_id,
        		'financial_year' => $active_year,
        		'method'         => '_vendorDashboard'
        	);

        	$data_list  = avul_call(API_URL.'dashboard/api/vendor_dashboard',$whereOne);
        	$data_value = !empty($data_list['data'])?$data_list['data']:'';

        	$page['data_value']   = $data_value;
			$page['page_heading'] = "Dashboard";
			$page['page_title']   = "Dashboard";
			$data['page_temp']    = $this->load->view('vendors/dashboard',$page,TRUE);
			$data['view_file']    = "Page_Template";
			$data['title']        = "Dashboard";
			$data['currentmenu']  = "dashboard";
			$this->bassthaya->load_vendors_dashboard_template($data);
		}
	}
?>
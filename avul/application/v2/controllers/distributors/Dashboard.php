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

        	$distributor_id = $this->session->userdata('id');
        	$financial_year = $this->session->userdata('active_year');

        	$where = array(
        		'method'         => '_distributorDashboard',
        		'distributor_id' => $distributor_id,
        		'financial_year' => $financial_year,
        	);

        	$data_list  = avul_call(API_URL.'dashboard/api/distributor_dashboard',$where);
            $data_value = !empty($data_list['data'])?$data_list['data']:'';
        	
        	$page['data_value']   = $data_value;
			$page['page_heading'] = "Dashboard";
			$page['page_title']   = "Dashboard";
			$data['page_temp']    = $this->load->view('distributors/dashboard',$page,TRUE);
			$data['view_file']    = "Page_Template";
			$data['title']        = "Dashboard";
			$data['currentmenu']  = "dashboard";
			$this->bassthaya->load_distributors_dashboard_template($data);
		}
	}
?>
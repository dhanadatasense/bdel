<?php
	class Bassthaya{
		private $CI;
		
		public function __construct(){
			$this->CI = & get_instance();
		}
		// Admin
		public function load_admin_dashboard_template($data = array('content' => '', 'title' => '', 'current_menu' => '', 'view_file' => '')){
			$this->CI->load->view('admin/Laydashtemp/header', $data);
			$this->CI->load->view('admin/Laymenu/navmenu', $data);
			$this->CI->load->view('admin/Laymenu/mainmenu', $data);
			$this->CI->load->view( $data['view_file'], $data);
			$this->CI->load->view('admin/Laydashtemp/footer', $data);
		}
		public function load_admin_form_template($data = array('content' => '', 'title' => '', 'current_menu' => '', 'view_file' => '')){
			$this->CI->load->view('admin/Laypagetemp/header', $data);
			$this->CI->load->view('admin/Laymenu/navmenu', $data);
			$this->CI->load->view('admin/Laymenu/mainmenu', $data);
			$this->CI->load->view($data['view_file'], $data);
			$this->CI->load->view('admin/Laypagetemp/footer', $data);
		}

		// Vendors
		public function load_vendors_dashboard_template($data = array('content' => '', 'title' => '', 'current_menu' => '', 'view_file' => '')){
			$this->CI->load->view('vendors/Laydashtemp/header', $data);
			$this->CI->load->view('vendors/Laymenu/navmenu', $data);
			$this->CI->load->view('vendors/Laymenu/mainmenu', $data);
			$this->CI->load->view( $data['view_file'], $data);
			$this->CI->load->view('vendors/Laydashtemp/footer', $data);
		}
		public function load_vendors_form_template($data = array('content' => '', 'title' => '', 'current_menu' => '', 'view_file' => '')){
			$this->CI->load->view('vendors/Laypagetemp/header', $data);
			$this->CI->load->view('vendors/Laymenu/navmenu', $data);
			$this->CI->load->view('vendors/Laymenu/mainmenu', $data);
			$this->CI->load->view($data['view_file'], $data);
			$this->CI->load->view('vendors/Laypagetemp/footer', $data);
		}

		// Distributors
		public function load_distributors_dashboard_template($data = array('content' => '', 'title' => '', 'current_menu' => '', 'view_file' => '')){
			$this->CI->load->view('distributors/Laydashtemp/header', $data);
			$this->CI->load->view('distributors/Laymenu/navmenu', $data);
			$this->CI->load->view('distributors/Laymenu/mainmenu', $data);
			$this->CI->load->view( $data['view_file'], $data);
			$this->CI->load->view('distributors/Laydashtemp/footer', $data);
		}
		public function load_distributors_form_template($data = array('content' => '', 'title' => '', 'current_menu' => '', 'view_file' => '')){
			$this->CI->load->view('distributors/Laypagetemp/header', $data);
			$this->CI->load->view('distributors/Laymenu/navmenu', $data);
			$this->CI->load->view('distributors/Laymenu/mainmenu', $data);
			$this->CI->load->view($data['view_file'], $data);
			$this->CI->load->view('distributors/Laypagetemp/footer', $data);
		}

		public function load_page_template($data = array('content' => '', 'title' => '', 'current_menu' => '', 'view_file' => '')){
			$this->CI->load->view('Laydashtemp/table_header', $data);
			$this->CI->load->view('Laymenu/navmenu', $data);
			$this->CI->load->view('Laymenu/mainmenu', $data);
			$this->CI->load->view($data['view_file'], $data);
			$this->CI->load->view('Laydashtemp/table_footer', $data);
		}
		public function load_login_template($data = array('content' => '', 'title' => '', 'current_menu' => '', 'view_file' => '')){
			$this->CI->load->view('Laylogintemp/header', $data);
			$this->CI->load->view( $data['view_file'], $data);
			$this->CI->load->view('Laylogintemp/footer', $data);
		}

		// Managers
		public function load_Managers_dashboard_template($data = array('content' => '', 'title' => '', 'current_menu' => '', 'view_file' => '')){
			$this->CI->load->view('managers/Laydashtemp/header', $data);
			$this->CI->load->view('managers/Laymenu/navmenu', $data);
			$this->CI->load->view('managers/Laymenu/mainmenu', $data);
			$this->CI->load->view( $data['view_file'], $data);
			$this->CI->load->view('managers/Laydashtemp/footer', $data);
		}
		public function load_Managers_form_template($data = array('content' => '', 'title' => '', 'current_menu' => '', 'view_file' => '')){
			$this->CI->load->view('managers/Laypagetemp/header', $data);
			$this->CI->load->view('managers/Laymenu/navmenu', $data);
			$this->CI->load->view('managers/Laymenu/mainmenu', $data);
			$this->CI->load->view($data['view_file'], $data);
			$this->CI->load->view('managers/Laypagetemp/footer', $data);
		}

	}
?>

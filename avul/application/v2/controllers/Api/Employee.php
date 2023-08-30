<?php
defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set('Asia/Kolkata');

class Employee extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('employee_model');
		$this->load->model('commom_model');
		$this->load->model('managers_model');
	}

	public function index()
	{
		echo "Test";
	}

	// employee
	// ***************************************************
	public function employee($param1 = "", $param2 = "", $param3 = "")
	{
		$method   = $this->input->post('method');
		$log_id   = $this->input->post('log_id');
		$log_role = $this->input->post('log_role');

		if ($method == '_addEmployee') {
			$error = FALSE;
			$errors = array();
			$required = array( 'email', 'door', 'street_name', 'Pincode', 'f_name', 'l_name', 'branch_name', 'bank_name', 'ifsc_code', 'account_type', 'account_no', 'mother_n', 'account_name', 'father_n', 'gender', 'aadhar_no', 'o_educational_q', 'pan_no', 'dob','mobile');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
        
			if( $this->input->post('pan_no')){
				
				if(!preg_match( "/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/", $this->input->post('pan_no'))) {
					
					$response['status']  = 0;
					$response['message'] = "pan number No. does not appear to be valid";
					$response['data']    = [];
					echo json_encode($response);
					return;
					
			    }
			}	
         
			if ($this->input->post('mobile')) {
				if (preg_match('#[^0-9]#', $this->input->post('mobile')) || strlen($this->input->post('mobile')) != 10) {
					$response['status']  = 0;
					$response['message'] = "Mobile No. does not appear to be valid";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}
			if ($this->input->post('email')) {
				if (mb_strlen($this->input->post('email')) > 254 || !filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
					$response['status']  = 0;
					$response['message'] = "E-mail address does not appear to be valid";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}

			if (count($errors) == 0) {
				$f_name     = $this->input->post('f_name');
				$l_name     = $this->input->post('l_name');
				$mobile       = $this->input->post('mobile');
				$email        = $this->input->post('email');
				$address      = $this->input->post('address');
				$password     = $this->input->post('password');
				$log_type     = $this->input->post('log_type');
				$login_status = $this->input->post('login_status');
				$password             = $this->input->post('password');
				$pan_no             = $this->input->post('pan_no');
				$aadhar_no             = $this->input->post('aadhar_no');
				$dob             = $this->input->post('dob');
				$o_educational_q             = $this->input->post('o_educational_q');
				$gender_id             = $this->input->post('gender');
				$account_name             = $this->input->post('account_name');
				$father_n             = $this->input->post('father_n');
				$mother_n             = $this->input->post('mother_n');
				$account_no             = $this->input->post('account_no');
				$account_type             = $this->input->post('account_type');
				$ifsc_code             = $this->input->post('ifsc_code');
				$bank_name             = $this->input->post('bank_name');
				$branch_name             = $this->input->post('branch_name');
				$Pincode             = $this->input->post('Pincode');
				$street_name             = $this->input->post('street_name');
				$Door_no             = $this->input->post('door');
				$grade_id             = $this->input->post('grade_id');
				$district_name     = $this->input->post('district_name');
				$post_name     = $this->input->post('post_name');
				$state_name     = $this->input->post('state_name');
				$country_name       = $this->input->post('country_name');
			
				$where = array(
					
					'mobile'    => $mobile,
					'published' => '1',
				);

				$column = 'id';

				$overalldatas = $this->employee_model->getEmployee($where, '', '', 'result', '', '', '', '', $column);
				$where_n = array(
					'first_name'  => ucfirst($f_name),
					'last_name'  => ucfirst($l_name),
				
					'published' => '1',
				);

				$column_n = 'id';

				$name = $this->employee_model->getEmployee($where_n, '', '', 'result', '', '', '', '', $column_n);

				if (!empty($overalldatas)) {
					$response['status']  = 0;
					$response['message'] = "Mobile Number Already Exist";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}else if(!empty($name)){
					$response['status']  = 0;
					$response['message'] = "Name Allready Exit";
					$response['data']    = [];
					echo json_encode($response);
					return;
				} else {

					$position_id = 0;
					$designation_name = '';
					$designation_code = '';

					if($grade_id != 0){

						if($grade_id != 0)
						{
							$where_3 = array(
								'position_id' => $grade_id,
							);
							$col_3 = 'designation_name,designation_code,position_id';
							$data1 = $this->employee_model->getdesignation($where_3, '', '', "result", '', '', '', '', $col_3);
							$position_id = !empty($data1[0]->position_id) ? $data1[0]->position_id : '';
							$designation_name = !empty($data1[0]->designation_name) ? $data1[0]->designation_name : '';
							$designation_code = !empty($data1[0]->designation_code) ? $data1[0]->designation_code : '';
						}
	
						if($position_id==5){
							$permission = 2;
						}else{
							$permission = 5;
						}
						$ref_id = 0;
						$company_type =1;
						$company_id   = 0;
					}else{
						$permission = 2;
						$position_id =0;
						$designation_name = 'Delivery Man';
						$ref_id   = $log_id;
						$company_type =2;
						$company_id   = $log_id;
					}

					if($gender_id==1){
						$gender ='Male';
					}else{
						$gender ='Female';
					
					}

					$account_typee ='';
					if($account_type==1){
						$account_typee = 'Current Account';
					}else if($account_type==2){
						$account_typee = 'Salary Account';
					}else if($account_type==3){
						$account_typee = 'Fixed Deposit Account';
					}else if($account_type==4){
						$account_typee = 'Recurring Deposite Account';
					}else if($account_type==5){
						$account_typee = 'NRI Account';
					}
					$name = array ($f_name,$l_name);
					$username = join(" ",$name);
					
					$data = array(
						'first_name'       => ucfirst($f_name),
						'last_name'       => ucfirst($l_name),
						'username'      => ucfirst($username),
						'mobile'       => $mobile,
						'email'        => $email,
						'address'      => $address,
						'date_o_birth'   => date("y-m-d", strtotime($dob)),
						'permission'     => $permission,
						'company_type'   => $company_type,
						'company_id'     => $company_id,
						'educational_q'=> $o_educational_q,
						'pan_no'         => $pan_no,
						'aadhar_no'    => $aadhar_no,
						'gender'       => $gender,
						'father_n'     => ucfirst($father_n),
						'mother_n'     => ucfirst($mother_n),
						'account_name' => $account_name,
						'account_no'   => $account_no,
						'account_type' => $account_typee,
						'ifsc_code'    => $ifsc_code,
						'bank_name'    => $bank_name,
						'branch_name'  => $branch_name,
						'pincode'      => $Pincode,
						'street_name'  => $street_name,
						'door_no'         => $Door_no,
						'position_id'     => $position_id,
						'role'         => $designation_name,
						'designation_code' => $designation_code,
						'posting_status'      => 0,
						'password'    =>$password,
						'log_type'     => $log_type,
						'district_name'     => $district_name,
						'post_name'     => $post_name,
			    		'country_name'       => $country_name,
						'status'         =>1,
						'state_name'     => $state_name,
						'ref_id'       => $ref_id,
						'createdate'   => date('Y-m-d H:i:s')
					);
			
					$insert = $this->employee_model->employee_insert($data);
			
					if ($log_type == 2) {
						$log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_employee',
							'auto_id'    => $insert,
							'action'     => 'create',
							'date'       => date('Y-m-d'),
							'time'       => date('H:i:s'),
							'createdate' => date('Y-m-d H:i:s')
						);

						$log_val = $this->commom_model->log_insert($log_data);
					}

					if ($insert) {
						$response['status']  = 1;
						$response['message'] = "Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					} else {
						$response['status']  = 0;
						$response['message'] = "Not Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				}
			}
		} else if ($method == '_listEmployeePaginate') {
			$limit        = $this->input->post('limit');
			$offset       = $this->input->post('offset');
			$ref_id       = $this->input->post('ref_id');
			$page        = $this->input->post('page');
				
			$sort_column = $this->input->post('sort_column');
			$sort_type   = $this->input->post('sort_type');
			$cur_page    = isset($page)?$page:'1';
			$_offset     = ($cur_page-1) * $limit;

			if ($limit != '' && $offset != '') {
				$limit  = $limit;
				$offset = $_offset;
			} else {
				$limit  = 10;
				$offset = 0;
			}

			$search = $this->input->post('search');
			if ($search != '') {
				$like['username'] = $search;
				// $like['mobile'] = $search;
				$where = array(
					'ref_id'       => $ref_id,
					'published'    => '1'
				);
			} else {
				$like = [];
				$where = array(
					'ref_id'       => $ref_id,
					'published'    => '1'
				);
			}
			$sort_col_ = array('id', 'first_name', 'mobile', 'position_id', 'status','posting_status');
    		array_unshift($sort_col_,"");
			unset($sort_col_[0]);

			$option['order_by']   = !empty($sort_column) ? $sort_col_[$sort_column] : 'id';
    		$option['disp_order'] = !empty($sort_type) ? ($sort_type==1? 'DESC' : 'ASC') : 'DESC';

			$column = 'id';
			$overalldatas = $this->employee_model->getEmployee($where, '', '', 'result', $like, '', '', '', $column);
		
			if ($overalldatas) {
				$totalc = count($overalldatas);
			} else {
				$totalc = 0;
			}
		
			// $option['order_by']   = 'id';
			// $option['disp_order'] = 'DESC';

			$data_list = $this->employee_model->getEmployee($where, $limit, $offset, 'result', $like, '', $option);

			if ($data_list) {
				$employee_list = [];

				foreach ($data_list as $key => $value) {

					$employee_id = isset($value->id) ? $value->id : '';
					$first_name    = isset($value->first_name) ? $value->first_name : '';
					$last_name    = isset($value->last_name) ? $value->last_name : '';
					$mobile      = isset($value->mobile) ? $value->mobile : '';
					$email       = isset($value->email) ? $value->email : '';
					$pincode     = isset($value->pincode) ? $value->pincode : '';
					$street_name     = isset($value->street_name) ? $value->street_name : '';
					$door_no     = isset($value->door_no) ? $value->door_no : '';
					$position_id = isset($value->position_id) ? $value->position_id	 : '';
					$published   = isset($value->published) ? $value->published : '';
					$status      = isset($value->status) ? $value->status : '';
					$createdate  = isset($value->createdate) ? $value->createdate : '';
					$posting_status = isset($value->posting_status) ? $value->posting_status : '';
					$log_type = isset($value->log_type) ? $value->log_type	 : '';
					
							$where = array('position_id' => $position_id,
						'published' =>1);
							$pos_name  = $this->employee_model->getdesignation($where);
							if ($pos_name) {
			
								
			
								foreach ($pos_name as $key => $value) {
			
									$designation_name = isset($value->designation_name) ? $value->designation_name : '';
									$designation_code = isset($value->designation_code) ? $value->designation_code : '';
								}
							}else{
								$designation_name = '';
								$designation_code = '';
							}
							$arr1 = array($first_name,$last_name);
								$name =join(" ",$arr1);
						
					$employee_list[] = array(
						'employee_id'  => $employee_id,
						'username'     => $name,
						'first_name'   => $first_name,
						'last_name'    => $last_name,
						'mobile'       => $mobile,
						'email'        => $email,
						'pincode'      => $pincode,
						'street_name'  => $street_name,
						'door_no'      => $door_no,
						'designation_code'=>$designation_code,
						'published'    => $published,
						'status'       => $status,
						'createdate'   => $createdate,
						'posting_status'=> $posting_status,
						'log_type'      => $log_type,
					);
				}

				if ($offset != '' && $limit != '') {
					$offset = $offset + $limit;
					$limit  = $limit;
				} else {
					$offset = $limit;
					$limit  = 10;
				}

				$response['status']       = 1;
				$response['message']      = "Success";
				$response['total_record'] = $totalc;
				$response['offset']       = (int)$offset;
				$response['limit']        = (int)$limit;
				$response['data']         = $employee_list;
				echo json_encode($response);
				return;
			} else {
				$response['status']  = 0;
				$response['message'] = "Not Found";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		} else if ($method == '_detailEmployee') {
			$employee_id = $this->input->post('employee_id');

			if (!empty($employee_id)) {

				$where = array('id' => $employee_id);
				$data  = $this->employee_model->getEmployee($where);
				if ($data) {

					$employee_list = [];

					foreach ($data as $key => $value) {
						$posting_status = isset($value->posting_status) ? $value->posting_status : '';
						$employee_id  = isset($value->id) ? $value->id : '';
						$first_name     = isset($value->first_name) ? $value->first_name : '';
						$last_name     = isset($value->last_name) ? $value->last_name : '';
						$mobile       = isset($value->mobile) ? $value->mobile : '';
						$email        = isset($value->email) ? $value->email : '';
						$position_id        = isset($value->position_id) ? $value->position_id : '';
						$educational_q        = isset($value->educational_q) ? $value->educational_q : '';
						$pan_no        = isset($value->pan_no) ? $value->pan_no : '';
						$aadhar_no        = isset($value->aadhar_no) ? $value->aadhar_no : '';
						$gender        = isset($value->gender) ? $value->gender : '';
						$father_n        = isset($value->father_n) ? $value->father_n : '';
						$mother_n        = isset($value->mother_n) ? $value->mother_n : '';
						$account_name        = isset($value->account_name) ? $value->account_name : '';
						$account_no        = isset($value->account_no) ? $value->account_no : '';
						$account_type        = isset($value->account_type) ? $value->account_type : '';
						$ifsc_code        = isset($value->ifsc_code) ? $value->ifsc_code : '';
						$bank_name        = isset($value->bank_name) ? $value->bank_name : '';
						$branch_name        = isset($value->branch_name) ? $value->branch_name : '';
						$pincode        = isset($value->pincode) ? $value->pincode : '';
						$street_name        = isset($value->street_name) ? $value->street_name : '';
						$door_no        = isset($value->door_no) ? $value->door_no : '';
						$date_o_birth        = isset($value->date_o_birth) ? $value->date_o_birth : '';
						$designation_code      = isset($value->designation_code) ? $value->designation_code : '';
						$password     = isset($value->password) ? $value->password : '';
						$log_type     = isset($value->log_type) ? $value->log_type : '';
						$login_status = isset($value->login_status) ? $value->login_status : '';
						$published    = isset($value->published) ? $value->published : '';
						$status       = isset($value->status) ? $value->status : '';
						$createdate   = isset($value->createdate) ? $value->createdate : '';
						
						$post_name    = !empty($value->post_name)?$value->post_name:'';
						$district_name   = isset($value->district_name) ? $value->district_name : '';
						$country_name         = !empty($value->country_name)?$value->country_name:'';
						$state_name          = !empty($value->state_name)?$value->state_name:'';
						
						$arr1 = array($first_name,$last_name);
						$name =join(" ",$arr1);

						
						$employee_list[] = array(
							'designation_code'=> $designation_code,
							'first_name'   => $first_name,
							'last_name'    => $last_name,
							'employee_id'  => $employee_id,
							'password'     => $password,
							'username'     => $name,
							'position_id'     => $position_id,
							'educational_q'     => $educational_q,
							'pan_no'     => $pan_no,
							'aadhar_no'     => $aadhar_no,
							'gender'     => $gender,
							'father_n'     => $father_n,
							'mother_n'     => $mother_n,
							'account_name'     => $account_name,
							'account_no'     => $account_no,
							'account_type'     => $account_type,
							'ifsc_code'     => $ifsc_code,
							'bank_name'     => $bank_name,
							'branch_name'     => $branch_name,
							'mobile'       => $mobile,
							'email'        => $email,
							'pincode'        => $pincode,
							'street_name'        => $street_name,
							'door_no'        => $door_no,
							'date_o_birth'   => $date_o_birth,
							'district_name'      => $district_name,
							'post_name'       => $post_name,
							'country_name'     => $country_name,
							'designation_id'=> $position_id,
							'log_type'     => $log_type,
							'login_status' => $login_status,
							'published'    => $published,
							'status'       => $status,
							'createdate'   => $createdate,
							'state_name'=> $state_name,
							'posting_status'=>  $posting_status,


						);
					}

					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = $employee_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		} else if ($method == '_listEmployee') {
			$where = array('status' => '1', 'published' => '1');

			$data_list = $this->employee_model->getEmployee($where);

			if ($data_list) {
				$employee_list = [];

				foreach ($data_list as $key => $value) {

					$employee_id = isset($value->id) ? $value->id : '';
					$mobile      = isset($value->mobile) ? $value->mobile : '';
					$email       = isset($value->email) ? $value->email : '';
					$published   = isset($value->published) ? $value->published : '';
					$status      = isset($value->status) ? $value->status : '';
					$createdate  = isset($value->createdate) ? $value->createdate : '';
					$first_name    = isset($value->first_name) ? $value->first_name : '';
					$last_name    = isset($value->last_name) ? $value->last_name : '';

					$arr1 = array($first_name,$last_name);
							$name =join(" ",$arr1);

					$employee_list[] = array(
						'employee_id' => $employee_id,
						'username'    => $name,
						'mobile'      => $mobile,
						'email'       => $email,
						'published'   => $published,
						'status'      => $status,
						'createdate'  => $createdate,
					);
				}

				$response['status']       = 1;
				$response['message']      = "Success";
				$response['data']         = $employee_list;
				echo json_encode($response);
				return;
			} else {
				$response['status']  = 0;
				$response['message'] = "Not Found";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		} else if ($method == '_typeWiseEmployee') {
			$log_type = $this->input->post('log_type');

			if (!empty($log_type)) {
				$where = array('log_type' => $log_type, 'status' => '1', 'published' => '1');

				$data_list = $this->employee_model->getEmployee($where);

				if ($data_list) {
					$employee_list = [];

					foreach ($data_list as $key => $value) {

						$employee_id = isset($value->id) ? $value->id : '';
						$first_name    = isset($value->first_name) ? $value->first_name : '';
						$last_name    = isset($value->last_name) ? $value->last_name : '';
						$mobile      = isset($value->mobile) ? $value->mobile : '';
						$email       = isset($value->email) ? $value->email : '';
						$published   = isset($value->published) ? $value->published : '';
						$status      = isset($value->status) ? $value->status : '';
						$createdate  = isset($value->createdate) ? $value->createdate : '';
						$arr1 = array($first_name,$last_name);
								$name =join(" ",$arr1);

						$employee_list[] = array(
							'employee_id' => $employee_id,
							'username'    => $name,
							'mobile'      => $mobile,
							'email'       => $email,
							'published'   => $published,
							'status'      => $status,
							'createdate'  => $createdate,
						);
					}

					$response['status']       = 1;
					$response['message']      = "Success";
					$response['data']         = $employee_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		} else if ($method == '_typeCompanyWiseEmployee') {
			$log_type   = $this->input->post('log_type');
			$company_id = $this->input->post('company_id');

			$error = FALSE;
			$errors = array();
			$required = array('log_type', 'company_id');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}

			if (count($errors) == 0) {
				$where = array(
					'log_type'   => $log_type,
					'company_id' => $company_id,
					'status'     => '1',
					'published'  => '1'
				);

				$data_list = $this->employee_model->getEmployee($where);

				if ($data_list) {
					$employee_list = [];

					foreach ($data_list as $key => $value) {

						$employee_id = isset($value->id) ? $value->id : '';
						$first_name    = isset($value->first_name) ? $value->first_name : '';
						$last_name    = isset($value->last_name) ? $value->last_name : '';
						$mobile      = isset($value->mobile) ? $value->mobile : '';
						$email       = isset($value->email) ? $value->email : '';
						$published   = isset($value->published) ? $value->published : '';
						$status      = isset($value->status) ? $value->status : '';
						$createdate  = isset($value->createdate) ? $value->createdate : '';
						
						$arr1 = array($first_name,$last_name);
								$name =join(" ",$arr1);

						$employee_list[] = array(
							'employee_id' => $employee_id,
							'username'    => $name,
							'mobile'      => $mobile,
							'email'       => $email,
							'published'   => $published,
							'status'      => $status,
							'createdate'  => $createdate,
						);
					}

					$response['status']       = 1;
					$response['message']      = "Success";
					$response['data']         = $employee_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		} else if ($method == '_updateEmployee') {
			$error = FALSE;
			$errors = array();
			$required = array( 'email', 'door', 'street_name', 'Pincode', 'f_name', 'l_name', 'branch_name', 'bank_name', 'ifsc_code', 'account_type', 'account_no', 'mother_n', 'account_name', 'father_n', 'gender', 'aadhar_no', 'o_educational_q', 'pan_no', 'dob','mobile');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}
			if ($error) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}

			if ($this->input->post('mobile')) {
				if (preg_match('#[^0-9]#', $this->input->post('mobile')) || strlen($this->input->post('mobile')) != 10) {
					$response['status']  = 0;
					$response['message'] = "Mobile No. does not appear to be valid";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}

			if ($this->input->post('email')) {
				if (mb_strlen($this->input->post('email')) > 254 || !filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
					$response['status']  = 0;
					$response['message'] = "E-mail address does not appear to be valid";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			}

			if (count($errors) == 0) {
				$f_name     = $this->input->post('f_name');
				$l_name     = $this->input->post('l_name');
				$mobile       = $this->input->post('mobile');
				$email        = $this->input->post('email');
				$address      = $this->input->post('address');
				$password     = $this->input->post('password');
				$log_type     = $this->input->post('log_type');
				$login_status = $this->input->post('login_status');
				$district_name     = $this->input->post('district_name');
				$post_name     = $this->input->post('post_name');
				$state_name     = $this->input->post('state_name');
				$country_name       = $this->input->post('country_name');
				$pan_no             = $this->input->post('pan_no');
				$aadhar_no             = $this->input->post('aadhar_no');
				$dob             = $this->input->post('dob');
				$o_educational_q             = $this->input->post('o_educational_q');
				$gender_id             = $this->input->post('gender');
				$account_name             = $this->input->post('account_name');
				$father_n             = $this->input->post('father_n');
				$mother_n             = $this->input->post('mother_n');
				$account_no             = $this->input->post('account_no');
				$account_type             = $this->input->post('account_type');
				$ifsc_code             = $this->input->post('ifsc_code');
				$bank_name             = $this->input->post('bank_name');
				$branch_name             = $this->input->post('branch_name');
				$Pincode             = $this->input->post('Pincode');
				$street_name             = $this->input->post('street_name');
				$Door_no             = $this->input->post('door');
				$grade_id             = $this->input->post('grade_id');
				$employee_id = $this->input->post('employee_id');
				$pstatus     = $this->input->post('status');

				$where = array(
					'id !='     => $employee_id,
					'mobile'    => $mobile,
					'published' => '1',
				);

				$column = 'id';

				$overalldatas = $this->employee_model->getEmployee($where, '', '', 'result', '', '', '', '', $column);
				$where_n = array(
					'id !='     => $employee_id,
					'first_name'  => ucfirst($f_name),
					'last_name'  => ucfirst($l_name),
					'published' => '1',
				);

				$column_n = 'id';

				$name = $this->employee_model->getEmployee($where_n, '', '', 'result', '', '', '', '', $column_n);

				if (!empty($overalldatas)) {
					$response['status']  = 0;
					$response['message'] = "Mobile Number Already Exist";
					$response['data']    = [];
					echo json_encode($response);
					return;

				}else if(!empty($name)){
					$response['status']  = 0;
					$response['message'] = "Name Allready Exit";
					$response['data']    = [];
					echo json_encode($response);
					return;

				} else {


					$position_id = 0;
					$designation_name = '';
					$designation_code = '';

					if($grade_id != 0){

						if($grade_id != 0)
						{
							$where_3 = array(
								'position_id' => $grade_id,
							);
							$col_3 = 'designation_name,designation_code,position_id';
							$data1 = $this->employee_model->getdesignation($where_3, '', '', "result", '', '', '', '', $col_3);
							$position_id = !empty($data1[0]->position_id) ? $data1[0]->position_id : '';
							$designation_name = !empty($data1[0]->designation_name) ? $data1[0]->designation_name : '';
							$designation_code = !empty($data1[0]->designation_code) ? $data1[0]->designation_code : '';
						}
	
						if($position_id==5){
							$permission = 2;
						}else{
							$permission = 5;
						}
						$ref_id = 0;
						$company_type =1;
						$company_id   = 0;
					}else{
						$permission = 2;
						$position_id =0;
						$designation_name = 'Delivery Man';
						$ref_id   = $log_id;
						$company_type =2;
						$company_id   = $log_id;
					}

					if($gender_id==1){
						$gender ='Male';
					}else{
						$gender ='Female';
					
					}

					$account_typee ='';
					if($account_type==1){
						$account_typee = 'Current Account';
					}else if($account_type==2){
						$account_typee = 'Salary Account';
					}else if($account_type==3){
						$account_typee = 'Fixed Deposit Account';
					}else if($account_type==4){
						$account_typee = 'Recurring Deposite Account';
					}else if($account_type==5){
						$account_typee = 'NRI Account';
					}
					$name = array($f_name,$l_name);
					$username = join(" ",$name);

					$data = array(
						'first_name'       => ucfirst($f_name),
						'last_name'       => ucfirst($l_name),
						'username'     => ucfirst($username),
						'mobile'       => $mobile,
						'email'        => $email,
						'address'      => $address,
						'date_o_birth'   => date("y-m-d", strtotime($dob)),
						'permission'     => $permission,
						'company_type'   => $company_type,
						'company_id'     => $company_id,
						'educational_q'=> $o_educational_q,
						'pan_no'         => $pan_no,
						'aadhar_no'    => $aadhar_no,
						'gender'       => $gender,
						'father_n'     => ucfirst($father_n),
						'mother_n'     => ucfirst($mother_n),
						'account_name' => $account_name,
						'account_no'   => $account_no,
						'account_type' => $account_typee,
						'ifsc_code'    => $ifsc_code,
						'bank_name'    => $bank_name,
						'branch_name'  => $branch_name,
						'pincode'      => $Pincode,
						'street_name'  => $street_name,
						'door_no'         => $Door_no,
						'position_id'     => $position_id,
						'role'         => $designation_name,
						'designation_code' => $designation_code,
						'posting_status'      => 0,
						'password'    =>$password,
						'log_type'     => $log_type,
						'district_name'     => $district_name,
						'post_name'     => $post_name,
			    		'country_name'       => $country_name,
						'status'         =>$pstatus,
						'login_status'   => $login_status,
						'state_name'     => $state_name,
						'ref_id'       => $ref_id,
						'createdate'   => date('Y-m-d H:i:s')
					);

					$update_id  = array('id' => $employee_id);
					
					$update = $this->employee_model->employee_update($data, $update_id);

					$data = array(
					
				
						'status'       => $pstatus,
						
						'updatedate'         => date('Y-m-d H:i:s'),
					);

					$update_id  = array('employee_id' => $employee_id);
					
					
					$update  = $this->managers_model->managers_update($data,$update_id);
					
					if ($log_type == 2) {
						$log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_employee',
							'auto_id'    => $employee_id,
							'action'     => 'update',
							'date'       => date('Y-m-d'),
							'time'       => date('H:i:s'),
							'createdate' => date('Y-m-d H:i:s')
						);

						$log_val = $this->commom_model->log_insert($log_data);
					}

					if ($update) {
						$response['status']  = 1;
						$response['message'] = "Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					} else {
						$response['status']  = 0;
						$response['message'] = "Not Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				}
			}
		} else if ($method == '_deleteEmployee') {
			$employee_id = $this->input->post('employee_id');

			if (!empty($employee_id)) {
				$data = array(
					'published' => '0',
				);

				$where  = array('id' => $employee_id);
				$update = $this->employee_model->employee_delete($data, $where);

				$data_d = array(
					'published' => '0',
				);

				$wher_d  = array('employee_id' => $employee_id);
				$de_posting = $this->managers_model->managers_delete($data_d, $wher_d);
				
				$log_data = array(
					'u_id'       => $log_id,
					'role'       => $log_role,
					'table'      => 'tbl_employee',
					'auto_id'    => $employee_id,
					'action'     => 'delete',
					'date'       => date('Y-m-d'),
					'time'       => date('H:i:s'),
					'createdate' => date('Y-m-d H:i:s')
				);

				$log_val = $this->commom_model->log_insert($log_data);

				if ($update) {
					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		}else if($method == '_getPinDetails')
		{

			$pincode = $this->input->post('search');

			if(!empty($pincode))
			{
				$where = array('c_pincode'=>$pincode);
				
				$data  = $this->commom_model->getStateDistric($where);

				if($data)
				{	
					$pin_detail = [];
					foreach ($data as $key => $value) {
						$id      = isset($value->id)?$value->id:'';
						$c_pincode      = isset($value->c_pincode)?$value->c_pincode:'';
						$c_district   = isset($value->c_district)?$value->c_district:'';
						$c_state   = isset($value->c_state)?$value->c_state:'';
						
						$pin_detail[] = array(
							'id'      => $id,
							'c_pincode'      => $c_pincode,
							'c_district'   => $c_district,
							'c_state'   => ucwords(strtolower($c_state))
						  );
					}

					$response['status']  = 1;
					$response['message'] = "Success"; 
					$response['data']    = $pin_detail;
					echo json_encode($response);
					return; 
				}
				else
				{
					$response['status']  = 0;
					$response['message'] = "Incorrect Pincode"; 
					$response['data']    = [];
					echo json_encode($response);
					return; 
				}
			}

			else
			{
				$response['status']  = 0;
				$response['message'] = "Please Enter Pincode"; 
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		}else {
			$response['status']  = 0;
			$response['message'] = "Error";
			$response['data']    = [];
			echo json_encode($response);
			return;
		}
	}
	public function employee_designation($param1 = "", $param2 = "", $param3 = "")
	{
		$method   = $this->input->post('method');

		if ($method == '_addDesignation') {
			$error = FALSE;
			$errors = array();
			$required = array('position_id', 'designation_code', 'designation');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}

		

			if (count($errors) == 0) {
				$position_id     = $this->input->post('position_id');
				$designation_code       = $this->input->post('designation_code');
				$designation        = $this->input->post('designation');
				
				//edit
				$ref_id   = $this->input->post('ref_id');

				$where = array(
					'designation_name '  => $designation,
					'published' => '1',
				);

				$column = 'id';

				$overalldatas1= $this->employee_model->getdesignation($where, '', '', 'result', '', '', '', '', $column);
				$where = array(
					'position_id'  => $position_id,
					'published' => '1',
				);

				$column = 'id';

				$overalldatas2 = $this->employee_model->getdesignation($where, '', '', 'result', '', '', '', '', $column);
				$where = array(
					
					'designation_code'    => $designation_code,
					'published' => '1',
				);

				$column = 'id';

				$overalldatas3 = $this->employee_model->getdesignation($where, '', '', 'result', '', '', '', '', $column);

				if (!empty($overalldatas1||$overalldatas2||$overalldatas3)) {
					$response['status']  = 0;
					$response['message'] = "Data Already Exist";
					$response['data']    = [];
					echo json_encode($response);
					return;
				} else {
					

					$data = array(
						'designation_name '  => $designation,
						'position_id'  => $position_id,
						'designation_code'    => $designation_code,
						'createdate'   => date('Y-m-d H:i:s')
					);

					$insert = $this->employee_model->designation_insert($data);

					$log_type  = $this->input->post('log');
					$log_role     = $this->input->post('log_role');
					$log_id       = $this->input->post('log_id');
					if ($log_type == 2) {
						$log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_designation',
							'auto_id'    => $insert,
							'action'     => 'create',
							'date'       => date('Y-m-d'),
							'time'       => date('H:i:s'),
							'createdate' => date('Y-m-d H:i:s')
						);

						$log_val = $this->commom_model->log_insert($log_data);
					
					}
					if ($insert) {
						$response['status']  = 1;
						$response['message'] = "Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					} else {
						$response['status']  = 0;
						$response['message'] = "Not Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				}
			}
		} else if ($method == '_listDesignationPaginate') {
			$limit        = $this->input->post('limit');
			$offset       = $this->input->post('offset');
			$sort_column = $this->input->post('sort_column');
			$sort_type   = $this->input->post('sort_type');
		
			
			if ($limit != '' && $offset != '') {
				$limit  = $limit;
				$offset = $offset;
			} else {
				$limit  = 10;
				$offset = 0;
			}

			$search = $this->input->post('search');
			if ($search != '') {
				$like['name'] = $search;
				$where = array(
					'published'    => '1'
				);
			} else {
				$like = [];
				$where = array(
					'published'    => '1'
				);
			}
			$sort_col_ = array('id', 'designation_name', 'designation_code', 'position_id','status');
    		array_unshift($sort_col_,"");
			unset($sort_col_[0]);

			$option['order_by']   = !empty($sort_column) ? $sort_col_[$sort_column] : 'id';
    		$option['disp_order'] = !empty($sort_type) ? ($sort_type==1? 'DESC' : 'ASC') : 'DESC';
			$column = 'id';
			$overalldatas = $this->employee_model->getdesignation($where, '', '', 'result', $like, '', '', '', $column);
			
			if ($overalldatas) {
				$totalc = count($overalldatas);
			} else {
				$totalc = 0;
			}

			// $option['order_by']   = 'id';
			// $option['disp_order'] = 'DESC';

			$data_list = $this->employee_model->getdesignation($where, $limit, $offset, 'result', $like, '', $option);

			if ($data_list) {
				$designation_list = [];

				foreach ($data_list as $key => $value) {

					$designation_id = isset($value->id) ? $value->id : '';
					$position_id    = isset($value->position_id) ? $value->position_id : '';
					$designation_name      = isset($value->designation_name) ? $value->designation_name : '';
					$designation_code       = isset($value->designation_code) ? $value->designation_code : '';
					$published   = isset($value->published) ? $value->published : '';
					$status      = isset($value->status) ? $value->status : '';
					$createdate  = isset($value->createdate) ? $value->createdate : '';

					$designation_list[] = array(
						'designation_id'  => $designation_id,
						'position_id'     => $position_id,
						'designation_name'       => $designation_name,
						'designation_code'        => $designation_code,
						'published'    => $published,
						'status'       => $status,
						'createdate'   => $createdate,
					);
				}

				if ($offset != '' && $limit != '') {
					$offset = $offset + $limit;
					$limit  = $limit;
				} else {
					$offset = $limit;
					$limit  = 10;
				}

				$response['status']       = 1;
				$response['message']      = "Success";
				$response['total_record'] = $totalc;
				$response['offset']       = (int)$offset;
				$response['limit']        = (int)$limit;
				$response['data']         = $designation_list;
				echo json_encode($response);
				return;
			} else {
				$response['status']  = 0;
				$response['message'] = "Not Found";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		} else if ($method == '_detailDesignation') {
			$position_id = $this->input->post('position_id');

			if (!empty($position_id)) {

				$where = array('position_id' => $position_id);
				$data  = $this->employee_model->getdesignation($where);
				if ($data) {

					$designation_list = [];

					foreach ($data as $key => $value) {

						$designation_id  = isset($value->id) ? $value->id : '';
						$position_id     = isset($value->position_id) ? $value->position_id : '';
						$designation_name = isset($value->designation_name) ? $value->designation_name : '';
						$designation_code = isset($value->designation_code) ? $value->designation_code : '';
						$published    = isset($value->published) ? $value->published : '';
						$status       = isset($value->status) ? $value->status : '';
						$createdate   = isset($value->createdate) ? $value->createdate : '';

						$designation_list[] = array(
							'designation_id'  => $designation_id,
							'position_id'     => $position_id,
							'designation_name'       => $designation_name,
							'designation_code'        => $designation_code,
							'published'    => $published,
							'status'       => $status,
							'createdate'   => $createdate,
						);
						
					}

					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = $designation_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		} else if ($method == '_listDesignation') {
			$designation_id   = $this->input->post('designation_id');
			$designation_code   = $this->input->post('designation_code');
			if(!empty($designation_code)){
				if($designation_code=="ADMIN"){

					$myArray = array("'RSM','ASM', 'SO', 'TSI', 'BDE'");
					$where = array(
						'status' => '1',
						'published' => '1'
					);
					
					$where_in['designation_code'] = $myArray;
					$data_list = $this->employee_model->getdesignation($where,'','',"result",'','','','','',$where_in);

				}else if($designation_code == 'RSM'){

					$myArray = array("'ASM', 'SO', 'TSI', 'BDE'");
					$where = array(
						'status' => '1',
						'published' => '1'
					);
					$where_in['designation_code'] = $myArray;
					$data_list = $this->employee_model->getdesignation($where,'','',"result",'','','','','',$where_in);

				}else if($designation_code == 'ASM'){

					$myArray = array("'SO', 'TSI', 'BDE'");
					$where = array(
						'status' => '1',
						'published' => '1'
					);
					$where_in['designation_code'] = $myArray;
					$data_list = $this->employee_model->getdesignation($where,'','',"result",'','','','','',$where_in);

				}else if($designation_code == 'SO'){

					$myArray = array("'TSI', 'BDE'");
					$where = array(
						'status' => '1',
						'published' => '1'
					);
					$where_in['designation_code'] = $myArray;
					$data_list = $this->employee_model->getdesignation($where,'','',"result",'','','','','',$where_in);

				}else if($designation_code == 'TSI'){

					$where = array(
						'designation_code'=> 'BDE',
						'status' => '1',
						'published' => '1'
					);
					
					$data_list = $this->employee_model->getdesignation($where);
				}
			}else if(!empty($designation_id)){
					$where = array(
						'designation_code'     => $designation_id,
						'status' => '1',
						'published' => '1'
					);
					$data_list = $this->employee_model->getdesignation($where);
			}
			else{
				$where = array('status' => '1', 'published' => '1');
				$data_list = $this->employee_model->getdesignation($where);
			}
			

			

			if ($data_list) {
				$employee_list = [];

				foreach ($data_list as $key => $value) {

					$designation_id = isset($value->id) ? $value->id : '';
					$position_id    = isset($value->position_id) ? $value->position_id : '';
					$designation_name      = isset($value->designation_name) ? $value->designation_name : '';
					$designation_code       = isset($value->designation_code) ? $value->designation_code : '';
					$published   = isset($value->published) ? $value->published : '';
					$status      = isset($value->status) ? $value->status : '';
					$createdate  = isset($value->createdate) ? $value->createdate : '';

					$employee_list[] = array(
						'designation_id' => $position_id,
						'position_id'    => $position_id,
						'designation_name'      => $designation_name,
						'designation_code'       => $designation_code,
						'published'   => $published,
						'status'      => $status,
						'createdate'  => $createdate,
					);
				}

				$response['status']       = 1;
				$response['message']      = "Success";
				$response['data']         = $employee_list;
				echo json_encode($response);
				return;
			} else {
				$response['status']  = 0;
				$response['message'] = "Not Found";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		} else if ($method == '_typeWiseEmployee') {
			$log_type = $this->input->post('log_type');

			if (!empty($log_type)) {
				$where = array('log_type' => $log_type, 'status' => '1', 'published' => '1');

				$data_list = $this->employee_model->getEmployee($where);

				if ($data_list) {
					$employee_list = [];

					foreach ($data_list as $key => $value) {

						$employee_id = isset($value->id) ? $value->id : '';
						$first_name    = isset($value->first_name) ? $value->first_name : '';
						$last_name    = isset($value->last_name) ? $value->last_name : '';
						$mobile      = isset($value->mobile) ? $value->mobile : '';
						$email       = isset($value->email) ? $value->email : '';
						$published   = isset($value->published) ? $value->published : '';
						$status      = isset($value->status) ? $value->status : '';
						$createdate  = isset($value->createdate) ? $value->createdate : '';

						$arr = array($first_name,$last_name);
						$username =join(" ",$arr);
						$employee_list[] = array(
							'employee_id' => $employee_id,
							'username'    => $username,
							'mobile'      => $mobile,
							'email'       => $email,
							'published'   => $published,
							'status'      => $status,
							'createdate'  => $createdate,
						);
					}

					$response['status']       = 1;
					$response['message']      = "Success";
					$response['data']         = $employee_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		} else if ($method == '_typeCompanyWiseEmployee') {
			$log_type   = $this->input->post('log_type');
			$company_id = $this->input->post('company_id');

			$error = FALSE;
			$errors = array();
			$required = array('log_type', 'company_id');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}

			if (count($errors) == 0) {
				$where = array(
					'log_type'   => $log_type,
					'company_id' => $company_id,
					'status'     => '1',
					'published'  => '1'
				);

				$data_list = $this->employee_model->getEmployee($where);

				if ($data_list) {
					$employee_list = [];

					foreach ($data_list as $key => $value) {

						$employee_id = isset($value->id) ? $value->id : '';
						$first_name    = isset($value->first_name) ? $value->first_name : '';
						$last_name    = isset($value->last_name) ? $value->last_name : '';
						$mobile      = isset($value->mobile) ? $value->mobile : '';
						$email       = isset($value->email) ? $value->email : '';
						$published   = isset($value->published) ? $value->published : '';
						$status      = isset($value->status) ? $value->status : '';
						$createdate  = isset($value->createdate) ? $value->createdate : '';

						$arr = array($first_name,$last_name);
						$username =join(" ",$arr);
						$employee_list[] = array(
							'employee_id' => $employee_id,
							'username'    => $username,
							'mobile'      => $mobile,
							'email'       => $email,
							'published'   => $published,
							'status'      => $status,
							'createdate'  => $createdate,
						);
					}

					$response['status']       = 1;
					$response['message']      = "Success";
					$response['data']         = $employee_list;
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		} else if ($method == '_updateDesignation') {
			$error = FALSE;
			$errors = array();
			
			$required = array('position_id', 'designation_code', 'designation');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		

			

			if (count($errors) == 0) {
				$designation  = $this->input->post('designation');
				$designation_code     = $this->input->post('designation_code');
				$position_id       = $this->input->post('position_id');
				$status       = $this->input->post('status');
				$designation_id=$this->input->post('designation_id');
				

				$where = array(
					'id !='    => $designation_id,
					'position_id' => $position_id,
					'designation_code'   => $designation_code,
					'designation_name' => $designation,
					'published'   => '1',
				);

				$column = 'id';

				$overalldatas = $this->employee_model->getdesignation($where, '', '', 'result', '', '', '', '', $column);

				if (!empty($overalldatas)) {
					$response['status']  = 0;
					$response['message'] = "Data Already Exist";
					$response['data']    = [];
					echo json_encode($response);
					return;
				} else {

					
					

					$data = array(
						'position_id'         => $position_id,
						'designation_code'       => $designation_code,
						'designation_name'       => $designation,
						'status'       => $status,
						'updatedate'   => date('Y-m-d H:i:s')
					);

					$update_id  = array('id' => $designation_id);
					$update = $this->employee_model->designation_update($data, $update_id);
					$log_type  = $this->input->post('log');
					$log_role     = $this->input->post('log_role');
					$log_id       = $this->input->post('log_id');
					if ($log_type == 2) {
						$log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_designation',
							'auto_id'    => $designation_id,
							'action'     => 'update',
							'date'       => date('Y-m-d'),
							'time'       => date('H:i:s'),
							'updatedate' => date('Y-m-d H:i:s')
						);

						$log_val = $this->commom_model->log_insert($log_data);
					}

					if ($update) {
						$response['status']  = 1;
						$response['message'] = "Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					} else {
						$response['status']  = 0;
						$response['message'] = "Not Success";
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				}
			}
		} else if ($method == '_deleteDesignation') {
			$designation_id = $this->input->post('designation_id');

			if (!empty($designation_id)) {
				$data = array(
					'published' => '0',
				);

				$where  = array('id' => $designation_id);
				$update = $this->employee_model->designation_delete($data, $where);

				// $log_data = array(
				// 	'u_id'       => $log_id,
				// 	'role'       => $log_role,
				// 	'table'      => 'tbl_employee',
				// 	'auto_id'    => $employee_id,
				// 	'action'     => 'delete',
				// 	'date'       => date('Y-m-d'),
				// 	'time'       => date('H:i:s'),
				// 	'createdate' => date('Y-m-d H:i:s')
				// );

				// $log_val = $this->commom_model->log_insert($log_data);

				if ($update) {
					$response['status']  = 1;
					$response['message'] = "Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				} else {
					$response['status']  = 0;
					$response['message'] = "Not Success";
					$response['data']    = [];
					echo json_encode($response);
					return;
				}
			} else {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		} else {
			$response['status']  = 0;
			$response['message'] = "Error";
			$response['data']    = [];
			echo json_encode($response);
			return;
		}
	}
}
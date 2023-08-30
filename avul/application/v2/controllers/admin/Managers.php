<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Managers extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('encryption');
		$this->load->helper('url');
	}

	public function add_managers($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$formpage = $this->input->post('formpage');

		if ($formpage == 'BTBM_X_P') {
			$error = FALSE;
			$asmstate_id       = $this->input->post('asmstate_id');
			$astate_id       = $this->input->post('astate_id');
			$company_name       = $this->input->post('company_name');
			$contact_name       = $this->input->post('contact_name');
			$mobile             = $this->input->post('mobile');
			$email              = $this->input->post('email');
			$gst_no             = $this->input->post('gst_no');
			$pan_no             = $this->input->post('pan_no');
			$aadhar_no             = $this->input->post('aadhar_no');

			$password           = $this->input->post('password');

			$account_name       = $this->input->post('account_name');
			$account_no         = $this->input->post('account_no');
			$account_type       = $this->input->post('account_type');
			$ifsc_code          = $this->input->post('ifsc_code');
			$bank_name          = $this->input->post('bank_name');
			$branch_name        = $this->input->post('branch_name');
			$pincode            = $this->input->post('pincode');

			$state_id           = $this->input->post('state_id');
			$manager_id     = $this->input->post('manager_id');
			$address            = $this->input->post('address');
			$grade_id        = $this->input->post('grade_id');
			$senior_id            = $this->input->post('senior_id');
			$pstatus            = $this->input->post('pstatus');
			$method             = $this->input->post('method');
			$mzone                = $this->input->post('mzone');
			$ascity_id              = $this->input->post('ascity_id');
			$acity_id                = $this->input->post('acity_id');
			if($grade_id==4){	 
			$control_zn_id                      =(",".implode(",",$mzone).",");
			}else{
				$control_zn_id    ='';
			}
			if($grade_id==1){
				$control_st_id                      =(",".implode(",",$astate_id).",");
			}else{
				$control_st_id                      =(",".$asmstate_id.",");
			}
            if($grade_id==3){
				$control_ct_id                      =(",".implode(",",$acity_id).",");
			}else{
				$control_ct_id                      =(",".$ascity_id.",");
			}
		
			$required = array('company_name', 'mobile', 'email', 'grade_id',  'password');

			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error == TRUE) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {


				if ($method == 'BTBM_X_C') {
					if (userAccess('distributors-add')) {


						$data = array(
							'control_zn_id'      => $control_zn_id,
							'control_ct_id'      => $control_ct_id,
							'control_st_id'      => $control_st_id,
							'company_name'       => $company_name,
							'contact_name'       => $contact_name,
							'mobile'             => $mobile,
							'email'              => $email,
							'gst_no'             => $gst_no,
							'pan_no'             => $pan_no,
							'aadhar_no'          => $aadhar_no,
							'password'           => $password,
							'account_name'       => $account_name,
							'account_no'         => $account_no,
							'account_type'       => $account_type,
							'ifsc_code'          => $ifsc_code,
							'bank_name'          => $bank_name,
							'branch_name'        => $branch_name,
							'pincode'            => $pincode,
							'state_id'           => $state_id,
							'address'            => $address,
							'method'             => '_addManagers',
							'grade'              => $grade_id
						);

						$data_save = avul_call(API_URL . 'managers/api/managers', $data);




						if ($data_save['status'] == 1) {
							$response['status']  = 1;
							$response['message'] = $data_save['message'];
							$response['data']    = [];
							echo json_encode($response);
							return;
						} else {
							$response['status']  = 0;
							$response['message'] = $data_save['message'];
							$response['data']    = [];
							echo json_encode($response);
							return;
						}
					} else {
						$response['status']  = 0;
						$response['message'] = 'Access denied';
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				}
				// exit;
				else {
					if (userAccess('distributors-edit')) {
						


						
						$data = array(
							'manager_id'     => $manager_id,
							'control_zn_id'      => $control_zn_id,
							'control_ct_id'      => $control_ct_id,
							'control_st_id'      => $control_st_id,
							'company_name'       => $company_name,
							'contact_name'       => $contact_name,
							'mobile'             => $mobile,
							'email'              => $email,
							'gst_no'             => $gst_no,
							'pan_no'             => $pan_no,
							'aadhar_no'          => $aadhar_no,
							'password'           => $password,
							'account_name'       => $account_name,
							'account_no'         => $account_no,
							'account_type'       => $account_type,
							'ifsc_code'          => $ifsc_code,
							'bank_name'          => $bank_name,
							'branch_name'        => $branch_name,
							'pincode'            => $pincode,
							'state_id'           => $state_id,
							'status'             => $pstatus,
							'address'            => $address,
							'method'             => '_updateManagers',
							'grade'              => $grade_id
						);
						

						$data_save = avul_call(API_URL . 'managers/api/managers', $data);

						if ($data_save['status'] == 1) {
							$response['status']  = 1;
							$response['message'] = $data_save['message'];
							$response['data']    = [];
							echo json_encode($response);
							return;
						} else {
							$response['status']  = 0;
							$response['message'] = $data_save['message'];
							$response['data']    = [];
							echo json_encode($response);
							return;
						}
					} else {
						$response['status']  = 0;
						$response['message'] = 'Access denied';
						$response['data']    = [];
						echo json_encode($response);
						return;
					}
				}
			}
		}else if($param1 == 'get_state'){
			$role_id             = $this->input->post('role_id');


			$option ='';

			// if($role_id==1){
				$wher = array(
					'grade' =>$role_id ,
					'method' => 'getstate'
				);
				$state_rsm = avul_call(API_URL . 'managers/api/managers', $wher);

				if(!empty($state_rsm['data']))
	        		{
				// print_r($state_rsm['data']);
						
	        			foreach ($state_rsm['data'] as $key => $value) {
				
                            
	        				$ast_id   = !empty($value['id'])?$value['id']:'';
	                        $ast_name = !empty($value['state_name'])?$value['state_name']:'';
							
							
	                        $option .= '<option value="'.$ast_id.'" >'.$ast_name.'</option>';
	        			}
						
						
	        		}
					
                $response['grade']  = $role_id;
				$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 
			// }
			// else if($role_id==2){

			// }
			// else if($role_id==3){

			// }
			// else if($role_id==4){

			// }else{

			// }
			
		}else if($param1 == 'get_city'){
			$role_id             = $this->input->post('role_id');
			$state_id             = $this->input->post('state_id');

			$option ='';

			 if($role_id<=2){
				
			 }else{
				$wher = array(
					'grade' =>$role_id ,
					'state_id' => $state_id,
					'method' => 'getCity'
				);
			
				$state_rsm = avul_call(API_URL . 'managers/api/managers', $wher);

				if(!empty($state_rsm['data']))
	        		{
				// print_r($state_rsm['data']);
						
	        			foreach ($state_rsm['data'] as $key => $value) {
				
                            
	        				$acity_id   = !empty($value['id'])?$value['id']:'';
	                        $acity_name = !empty($value['city_name'])?$value['city_name']:'';
							
							
	                        $option .= '<option value="'.$acity_id.'" >'.$acity_name.'</option>';
	        			}
						
						
	        		}
					
                $response['grade']  = $role_id;
				$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return;} 
			// }
			// else if($role_id==2){

			// }
			// else if($role_id==3){

			// }
			// else if($role_id==4){

			// }else{

			// }
			
		}else if($param1 == 'get_zone'){
			$role_id             = $this->input->post('role_id');
			$state_id             = $this->input->post('state_id');
			$city_id             = $this->input->post('city_id');

			$option ='';

			 if($role_id<=2){
				
			 }else{
				$wher = array(
					'grade' =>$role_id ,
					'state_id' => $state_id,
					'city_id' => $city_id,
					'method' => 'getZone'
				);
			
				$state_rsm = avul_call(API_URL . 'managers/api/managers', $wher);

				if(!empty($state_rsm['data']))
	        		{
				// print_r($state_rsm['data']);
						
	        			foreach ($state_rsm['data'] as $key => $value) {
				
                            
	        				$azone_id   = !empty($value['id'])?$value['id']:'';
	                        $azone_name = !empty($value['name'])?$value['name']:'';
							
							
	                        $option .= '<option value="'.$azone_id.'" >'.$azone_name.'</option>';
	        			}
						
						
	        		}
					
                $response['grade']  = $role_id;
				$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return;} 
			// }
			// else if($role_id==2){

			// }
			// else if($role_id==3){

			// }
			// else if($role_id==4){

			// }else{

			// }
			
		}
		 else {
			if ($param1 == 'Edit') {
				$employee_id = !empty($param2) ? $param2 : '';
				
				$where = array(
					'employee_id' => $employee_id,
					'method'         => '_detailEmployee'
				);
				
				$data_list  = avul_call(API_URL . 'employee/api/employee', $where);
				
				$data_value = $data_list['data'];
				
				$role_id        = !empty($data_value[0]['grade']) ? $data_value[0]['grade'] : '';
				$avai_state_val = !empty($data_value[0]['ctrl_state_id']) ? $data_value[0]['ctrl_state_id'] : '';
			
				if(!empty($avai_state_val)){
					foreach ($avai_state_val as $key => $value) {
					   $st   = !empty($value['id'])?$value['id']:'';	
					}
				}
				
				$avai_city_val  = !empty($data_value[0]['ctrl_city_id']) ? $data_value[0]['ctrl_city_id'] : '';
				if(!empty($avai_city_val)){
					foreach ($avai_city_val as $key => $value) {
					   $ct   = !empty($value['id'])?$value['id']:'';	
					}
				}
				$avai_zone_val  = !empty($data_value[0]['ctrl_zone_id']) ? $data_value[0]['ctrl_zone_id'] : '';
				
				if(!empty($avai_state_val)){
					$wher = array(
						'grade' =>$role_id ,
						'method' => 'getstate'
					);
					
					$state_by = avul_call(API_URL . 'managers/api/managers', $wher);
					$data_state = $state_by['data'];
					
					foreach( $data_state as $value){
						array_push($avai_state_val,$value);
					}
				}
				
				if(!empty($avai_city_val)){
					$wher_c = array(
						'grade' =>$role_id ,
						"state_id" => $st,

						'method' => 'getCity'
					);
					
					$city_by = avul_call(API_URL . 'managers/api/managers', $wher_c);
					
					$data_city = $city_by['data'];
					foreach( $data_city as $value){
						array_push($avai_city_val,$value);
					}
	
				}
				
				if(!empty($avai_zone_val)){
					$wher_z = array(
						'grade' =>$role_id ,
						"state_id" => $st,
						"city_id" => $ct,
						'method' => 'getZone'
					);
					$zone_by = avul_call(API_URL . 'managers/api/managers', $wher_z);
					$data_zone = $zone_by['data'];
					foreach( $data_zone as $value){
						array_push($avai_zone_val,$value);
						}
	
				}
				

					
						

				
				$page['dataval']          = $data_list['data'];
				// $page['type_val']         = $type_list['data'];
				$page['avai_state_val']   = $avai_state_val;
				$page['avai_city_val']   = $avai_city_val;
				$page['avai_zone_val']   = $avai_zone_val;
				$page['method']     = 'BTBM_X_U';
				$page['page_title'] = "Edit Manager";
			} else {
				
				$page['dataval']    = '';
				$page['city_val']   = '';
				$page['zone_val']   = '';
				$page['method']     = 'BTBM_X_C';
				$page['page_title'] = "Add Manager";
			}

			$where_1 = array(


				'method'         => '_listHead',
			);

			$head_list = avul_call(API_URL . 'managers/api/managers', $where_1);
			$data_value = !empty($head_list['data']) ? $head_list['data'] : '';

			// State List
			$where_2 = array(
				'method' => '_listState',
			);

			$state_list = avul_call(API_URL . 'master/api/state', $where_2);
			$state_data = $state_list['data'];

			// // Vendor List
			// $where_3 = array(
			// 	'method' => '_listManufacturerVendors',
			// );

			// $vendor_list = avul_call(API_URL.'vendors/api/vendors',$where_3);
			// $vendor_data = $vendor_list['data'];

			$page['state_val']    = $state_data;
			$page['grade_val']     = $data_value;
			//$page['vendor_val']   = $vendor_list['data'];
			$page['main_heading'] = "Manager";
			$page['sub_heading']  = "Manager";
			$page['pre_title']    = "List Manager";
			$page['page_access']  = userAccess('distributors-view');
			$page['pre_menu']     = "index.php/admin/distributors/list_distributors";
			$data['page_temp']    = $this->load->view('admin/managers/add_managers', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "add_managers";
			$this->bassthaya->load_admin_form_template($data);
		}
	}


	public function hierarchy_list($param1="", $param2="", $param3="", $param4="", $param5="", $param6="", $param7="")
	{
		if ($this->session->userdata('random_value') == '')
		redirect(base_url() . 'index.php?login', 'refresh');

		$method = $this->input->post('method');
		
		if($method == 'getCityname')
		{
			$state_id  = $this->input->post('state_id');

		

			$att_whr = array(
				'state_id'  => $state_id,
			
				'method'      => '_listCity',
			);

			$data_list  = avul_call(API_URL.'master/api/city',$att_whr);
			$data_val   = !empty($data_list['data'])?$data_list['data']:'';
			$option ='<option value="">Select Value</option>';

			if(!empty($data_val))
			{
				foreach ($data_val as $key => $value) {
					$city_id   = !empty($value['city_id'])?$value['city_id']:'';
					$city_name = !empty($value['city_name'])?$value['city_name']:'';

					$select   = '';
				

					

					$option .= '<option value="'.$city_id.'" '.$select.'>'.$city_name.'</option>';
				}
			}
			if($data_list['status'] == 1)
			{
				
				$response['status']    = 1;
				$response['message']   = $data_list['message']; 
				$response['data']      = $option;
				$response['error']     = []; 
				echo json_encode($response);
				return;
			}
			else
			{
				$response['status']  = 0;
				$response['message'] = $data_list['message']; 
				$response['data']    = [];
				$response['error']   = []; 
				echo json_encode($response);
				return;
			}
		}
		
		if($method == 'getZonename')
		{
			$state_id  = $this->input->post('state_id');
			$city_id  = $this->input->post('city_id');
			

			$att_whr = array(
				'city_id'  => $city_id,
				'state_id'  => $state_id,
				'method'      => '_listZone',
			);

			$data_list  = avul_call(API_URL.'master/api/zone',$att_whr);
			$data_val   = !empty($data_list['data'])?$data_list['data']:'';

			$option ='<option value="">Select Value</option>';

			if(!empty($data_val))
			{
				foreach ($data_val as $key => $value) {
					$zone_id   = !empty($value['zone_id'])?$value['zone_id']:'';
					$zone_name = !empty($value['zone_name'])?$value['zone_name']:'';

					$select   = '';
				

					

					$option .= '<option value="'.$zone_id.'" '.$select.'>'.$zone_name.'</option>';
				}
			}

			if($data_list['status'] == 1)
			{
				
				$response['status']    = 1;
				$response['message']   = $data_list['message']; 
				$response['data']      = $option;
				$response['error']     = []; 
				echo json_encode($response);
				return;
			}
			else
			{
				$response['status']  = 0;
				$response['message'] = $data_list['message']; 
				$response['data']    = [];
				$response['error']   = []; 
				echo json_encode($response);
				return;
			}
		}

		if($method == '_getHierarchyData')
		{
			$state_c_id  = $this->input->post('state_c_id');
			$city_z_id    = $this->input->post('city_z_id');
			$zone_z_id = $this->input->post('zone_z_id');
			$error = FALSE;
			$required = array('state_c_id', 'city_z_id');
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
			

			$att_whr = array(
				'state_c_id'  => $state_c_id,
				'city_z_id'    => $city_z_id,
				'zone_z_id' => $zone_z_id,
				'method'      => '_hierarchy',
			);

			$data_list  = avul_call(API_URL.'managers/api/hierarchy_list',$att_whr);
			$data_rsm = !empty($data_list['datar']) ? $data_list['datar'] : '';
			$data_asm = !empty($data_list['dataa']) ? $data_list['dataa'] : '';
			$data_so = !empty($data_list['datas']) ? $data_list['datas'] : '';
		
			if($data_list['status'] == 1)
			{
				$html     = '';
				$data_val = $data_list['data'];

				$num = 1;
				foreach ($data_val as $key => $value) {

					
					$bde_name = !empty($value['bde_name'])?$value['bde_name']:'';
					
					$tsi_name     = !empty($value['tsi_name'])?$value['tsi_name']:'---';
					$zone_desc    = !empty($value['zone_desc'])?$value['zone_desc']:'---';
					$status    = !empty($value['status'])?$value['status']:'';
					$zone_id   = !empty($value['zone_id'])?$value['zone_id']:'---';
					if ($status == '1') {
						$status_view = '<span class="badge badge-success">Active</span>';
					} else {
						$status_view = '<span class="badge badge-danger">In Active</span>';
					}
					

					$html .= '
						<tr>
							<td>'.$num.'</td>
							<td>'.mb_strimwidth($zone_desc, 0, 30, '...').'</td>
							<td>'.mb_strimwidth($tsi_name, 0, 20, '...').'</td>
							<td>'.mb_strimwidth($bde_name, 0, 20, '...').'</td>
							<td class="line_height">' . $status_view . '</td>
							
						</tr>
					';

					$num++;
				}
				

				
				$response['status']    = 1;
				$response['message']   = $data_list['message']; 
				$response['data']      = $html;
				$response['rsm']      = $data_rsm;
				$response['asm']      = $data_asm;
				$response['so']      = $data_so;
				$response['error']     = []; 
				echo json_encode($response);
				return;
			}
			else
			{
				$response['status']  = 0;
				$response['message'] = $data_list['message']; 
				$response['data']    = [];
				$response['error']   = []; 
				echo json_encode($response);
				return;
			}
		  }
		}else
		{
			$where_1 = array(
				'method'   => '_listState'
			);

			$state_list  = avul_call(API_URL.'master/api/state',$where_1);
			$ste_list   = !empty($state_list['data'])?$state_list['data']:'';

			$page['method']       = '_getHierarchyData';
			$page['state_list']     = $ste_list;
			$page['main_heading'] = "Hierarchy";
			$page['sub_heading']  = "Hierarchy";
			$page['pre_title']    = "Hierarchy List";
			$page['page_title']   = "Hierarchy List";
			$page['pre_menu']     = "";
			$data['page_temp']    = $this->load->view('admin/managers/hierarchy_list',$page,TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "hierarchy_list";
			$this->bassthaya->load_admin_form_template($data);
		}
	}

}
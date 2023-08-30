<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class Managers extends CI_Controller {

		public function __construct()
		{
			parent::__construct();

			$this->load->model('commom_model');
			$this->load->model('distributors_model');
			$this->load->model('vendors_model');
			$this->load->model('user_model');
			$this->load->model('assignproduct_model');
			$this->load->model('managers_model');
			$this->load->model('commom_model');
			$this->load->model('employee_model');
			
		}

		public function index()
		{
			echo "Test";
		}

		// distributors
		// ***************************************************
		public function managers($param1="",$param2="",$param3="")
		{
		
			$method = $this->input->post('method');

			// Create Managers
			if($method == '_addManagers')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('grade');
			
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error)
			    { 
			        $response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    

			    if(count($errors)==0)
			    {   //sathish
					$control_ct_id              = $this->input->post('control_ct_id');
					$mzone              = $this->input->post('control_zn_id');
					$grade              = $this->input->post('grade');
			    	$control_st_id             = $this->input->post('control_st_id');
			    	$employee_id        = $this->input->post('employee_id');

					$_des_wer =array(
						'designation_code'        => $grade,
					   );
					   $_des_col = 'position_id,designation_name';
					   $_des = $this->employee_model->getdesignation($_des_wer,'','',"result",'','','','',$_des_col);
					   foreach ($_des as $key => $value) {
						$position_id  = isset($value->position_id) ? $value->position_id : '';
						$designation_name  = isset($value->designation_name) ? $value->designation_name : '';
					   }
					//    $employee_id  = isset($value->id) ? $value->id : '';
                       $we =array(
						'employee_id'        => $employee_id,
					   );
					   $check = $this->managers_model->getManagers($we);
					   
					if(empty($check)){
						$data = array(
							'employee_id'        => $employee_id,
							'ctrl_state_id'      => $control_st_id,
							'ctrl_city_id'       => $control_ct_id,
							'ctrl_zone_id'       => $mzone,
							'position_id'        => $position_id,
							'designation_code'   => $grade,
							'role'               => $designation_name,
							'published'          =>1,
							'status'             =>1,
							'createdate'         => date('Y-m-d H:i:s'),
						);
						
						$insert = $this->managers_model->managers_insert($data);

						$d_ta = array(
							'posting_status'    => 1,
							'role'               => $designation_name,
							'position_id'       => $position_id,
							'designation_code' => $grade,
							'updatedate'         => date('Y-m-d H:i:s'),
						);
						$update_id  = array('id' => $employee_id);
			
						$update_posting_status = $this->employee_model->employee_update($d_ta, $update_id);

					  

						if($insert)
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
					}else{
						    $response['status']  = 0;
							$response['message'] = "Data Already Exists"; 
							$response['data']    = [];
							echo json_encode($response);
							return; 
					}






							
				}
					
			    
			}
   			// List head
			else if($method == '_listHead')
			{

			
				$where = array(
					'grade'              => '1',
					'status'             => '1', 
					'published'          => '1'
				);

				$data1_list = $this->managers_model->getManagers($where);
				
				
				$manager_list1 = [];
				if(!empty($data1_list)){
					foreach ($data1_list as $key => $value) {

						$manager_id = isset($value->id)?$value->id:'';
						$manager_name   = isset($value->contact_name)?$value->contact_name:'';
					   
						$manager_list1[] = array(
							  'manager_id' => $manager_id,
							'manager_name'   => $manager_name,
						   
						  );
					}
				}
				
				 
				$where_1 = array(
					'grade'              => '2',
					'status'             => '1', 
					'published'          => '1'
				);

				$data2_list = $this->managers_model->getManagers($where_1);
			
				$manager_list2 = [];

				if(!empty($data2_list)){

					foreach ($data2_list as $key => $value) {

						$manager_id = isset($value->id)?$value->id:'';
						$manager_name   = isset($value->contact_name)?$value->contact_name:'';
					   
						$manager_list2[] = array(
							  'manager_id' => $manager_id,
							'manager_name'   => $manager_name,
						   
						  );
					}
				}
			
				$where = array(
					'grade'              => '3',
					'status'             => '1', 
					'published'          => '1'
				);

				$data3_list = $this->managers_model->getManagers($where);

				$manager_list3 = [];

				if(!empty($data3_list)){

					foreach ($data3_list as $key => $value) {

						$manager_id = isset($value->id)?$value->id:'';
						$manager_name   = isset($value->contact_name)?$value->contact_name:'';
					   
						$manager_list3[] = array(
							  'manager_id' => $manager_id,
							'manager_name'   => $manager_name,
						   
						  );
					}
				}
			
				$where_4 = array(
					'grade'              => '4',
					'status'             => '1', 
					'published'          => '1'
				);

				$data4_list4 = $this->managers_model->getManagers($where_4);

				$managers_list4 = [];

				if(!empty($data4_list4)){

					foreach ($data4_list4 as $key => $value) {

						$manager_id = isset($value->id)?$value->id:'';
						$manager_name   = isset($value->contact_name)?$value->contact_name:'';
					   
						$managers_list4[] = array(
							  'manager_id' => $manager_id,
							'manager_name'   => $manager_name,
						   
						  );
					}
				}
				
				//$manager_list=[];

				$manager_list=array(
					'grade_1'=> $manager_list1,
					'grade_2'=> $manager_list2,
					'grade_3'=> $manager_list3,
					'grade_4'=> $managers_list4
				);

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $manager_list;
		    		echo json_encode($response);
			        return;
				
			}
            //available state
			else if($method == 'getstate')
			{   
				$grade = $this->input->post('grade');
			
				//$st_list ='';
				$where = array(
					
					 
					'published'          => '1'
				);
           
				$data1_list = $this->commom_model->getState($where);
				$dt_count=count($data1_list);
			 if($grade == "ASM" || $grade == "RSM"){
		
				for($i=0; $i < $dt_count; $i++){

					$new_st_id[]   = !empty($data1_list[$i]->id)?$data1_list[$i]->id:'';
					
					
					}
					$st_count = count($new_st_id);
				 $data1_list1=array();
					for ($i=0; $i < $st_count; $i++) {
	
						
						
						$where_1  = array(
							'designation_code'        => $grade,
							//'status'         => '1', 
							'published'      => '1'
						);
	
						$like['ctrl_state_id'] =','. $new_st_id[$i].',';
						
						$column = 'id';
	
						$assign_data = $this->managers_model->getAssignStateDetails($where_1, '', '', 'result', $like, '', '', '', $column);
					
					 if($assign_data){
						
					 }
					 else{
						
						
					
				
						$where = array(
							'id'     =>$new_st_id[$i],
						 
							'published'          => '1'
						);
						$co = 'id,state_name,state_code';
						$data1_list123 = $this->commom_model->getState($where, '', '', 'result', '', '', '', '', $co);
						foreach( $data1_list123 as $value){
							array_push($data1_list1,$value);
						}
					 }
					
						
					}
					
					
			 }else{
				$data1_list1 = $data1_list;
				
			 }
				
				
				
				
					
	
	

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $data1_list1;
		    		echo json_encode($response);
			        return;
				
			}

            //available City

			else if($method == 'getCity')
			{
				$state_id = $this->input->post('state_id');
				$grade = $this->input->post('grade');

				//$st_list ='';
				$where = array(
					
					 'state_id'          => $state_id,
					'published'          => '1'
				);
           
				$data2_list = $this->commom_model->getCity($where);
				
				$dt_count=count($data2_list);
				
				if($grade == "SO"){
					
					for($i=0; $i < $dt_count; $i++){

						$new_st_id[]   = !empty($data2_list[$i]->id)?$data2_list[$i]->id:'';
						
						
						}
						
						$st_count = count($new_st_id);
					    $data1_list2=array();
						for ($i=0; $i < $st_count; $i++) {
		
							
							
							$where_1  = array(
								'designation_code'      => $grade,
								//'status'         => '1', 
								'published'        => '1'
							);
		
							$like['ctrl_city_id'] =','. $new_st_id[$i].',';
							
							$column = 'id';
		
							$assign_data = $this->managers_model->getAssignStateDetails($where_1, '', '', 'result', $like, '', '', '', $column);
							
						 if($assign_data){
		
						 }
						 else{
							
							
							//$st_list .= $new_st_id[$i].',';
						
							
							$where = array(
								'id'     =>$new_st_id[$i],
							 
								'published'          => '1'
							);
							$co = 'id,city_name,city_code';
							$data1_list123 = $this->commom_model->getCity($where, '', '', 'result', '', '', '', '', $co);
							foreach( $data1_list123 as $value){
								array_push($data1_list2,$value);
							}
						 }
						
							
						}
				}else{
					$data1_list2=$data2_list;
				}
				
				
				
				

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $data1_list2;
		    		echo json_encode($response);
			        return;
				
			}

			//available zone
			else if($method == 'getZone')
			{
				$state_id = $this->input->post('state_id');
				$grade = $this->input->post('grade');
				$city_id = $this->input->post('city_id');

				$st_list ='';
				$where = array(
					'city_id'            => $city_id,
					 'state_id'          => $state_id,
					'published'          => '1'
				);
           
				$data2_list = $this->commom_model->getZone($where);
				$dt_count=count($data2_list);
			 if($grade != "BDE"){
				for($i=0; $i < $dt_count; $i++){

                $new_st_id[]   = !empty($data2_list[$i]->id)?$data2_list[$i]->id:'';
				
				
				}
				$st_count = count($new_st_id);
			    $data1_list3=array();
				for ($i=0; $i < $st_count; $i++) {

					
					
					$where_1  = array(
						'designation_code'        => $grade,
						//'status'         => '1', 
						'published'      => '1'
					);

					$like['ctrl_zone_id'] =','. $new_st_id[$i].',';
					
					$column = 'id';

					$assign_data = $this->managers_model->getAssignStateDetails($where_1, '', '', 'result', $like, '', '', '', $column);
				
				 if($assign_data){

				 }
				 else{
					
					
					$st_list .= $new_st_id[$i].',';
				
					
					$where = array(
						'id'     =>$new_st_id[$i],
					 
						'published'          => '1'
					);
					$co = 'id, name';
					$data1_list123 = $this->commom_model->getZone($where, '', '', 'result', '', '', '', '', $co);
					foreach( $data1_list123 as $value){
						array_push($data1_list3,$value);
					}
				 }
				
					
				}
			 }else{
				$data1_list3 = $data2_list;
			 }
				
				
			

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $data1_list3;
		    		echo json_encode($response);
			        return;
				
			}
		
						 
			// List Managers Pagination
			else if($method == '_listManagersPaginate')
			{    
				$limit  = $this->input->post('limit');
	    		$offset = $this->input->post('offset');
				//sathish
                $grade = $this->input->post('grade');
	    		if($limit !='' && $offset !='')
				{
					$limit  = $limit;
					$offset = $offset;
				}
				else
				{
					$limit  = 10;
					$offset = 0;
				}

				$search = $this->input->post('search');
	    		if($search !='')
	    		{
	    			$like['name'] = $search;
	    			$where = array(
	    				
	    				'published'        => '1',
						'position_id'           => $grade
	    			);
	    		}
	    		else
	    		{
	    			$like = [];
	    			$where = array(
	    				
	    				'published'        => '1',
						'position_id'           => $grade
	    			);
	    		}

	    		$column = 'id';
				$overalldatas = $this->employee_model->getEmployee($where, '', '', 'result', $like, '', '', '', $column);

				if($overalldatas)
				{
					$totalc = count($overalldatas);
				}
				else
				{
					$totalc = 0;
				}

				$option['order_by']   = 'id';
				$option['disp_order'] = 'DESC';

				$data_list = $this->employee_model->getEmployee($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$details_list = [];
					foreach ($data_list as $key => $value) {

						$employee_id = isset($value->id) ? $value->id : '';
						$first_name    = isset($value->first_name) ? $value->first_name : '';
						$last_name    = isset($value->last_name) ? $value->last_name : '';
						$mobile      = isset($value->mobile) ? $value->mobile : '';
						$email       = isset($value->email) ? $value->email : '';
						$pincode     = isset($value->pincode) ? $value->pincode : '';
						$street_name     = isset($value->street_name) ? $value->street_name : '';
						$position_id = isset($value->position_id) ? $value->position_id	 : '';
						$published   = isset($value->published) ? $value->published : '';
						$status      = isset($value->status) ? $value->status : '';
						$createdate  = isset($value->createdate) ? $value->createdate : '';
						$posting_status = isset($value->posting_status) ? $value->posting_status : '';

						$arr = array($first_name,$last_name);
						$first_name =join(" ",$arr);
						$where = array('position_id' => $position_id,
						'published' =>1);
							$pos_name  = $this->employee_model->getdesignation($where);
							if ($pos_name) {
			
								
			
								foreach ($pos_name as $key => $value) {
			
									$designation_name = isset($value->designation_name) ? $value->designation_name : '';
									$designation_code = isset($value->designation_code) ? $value->designation_code : '';
								}
							}
						

			            $details_list[] = array(
          					'employee_id'  => $employee_id,
				            'first_name'    => $first_name,
				            'last_name'          => $last_name,
				            'mobile'          => $mobile,
				            'pincode'         => $pincode,
				            'email'           => $email,
				            'position_id'         => $position_id,
				            'published'       => $published,
				            'status'          => $status,
				            'posting_status'      => $createdate,
							'designation_code' => $designation_code
          				);
					}

					if($offset !='' && $limit !='') {
						$offset = $offset + $limit;
						$limit  = $limit;
					} 
					else {
						$offset = $limit;
						$limit  = 10;
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['total_record'] = $totalc;
			        $response['offset']       = (int)$offset;
		    		$response['limit']        = (int)$limit;
			        $response['data']         = $details_list;
		    		echo json_encode($response);
			        return;
				}
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Not Found"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}

			// Manager Details
			else if($method == '_detailmanagers')
			{
				
				
				$employee_id = $this->input->post('employee_id');

		    	if(!empty($employee_id))
		    	{

		    		$where = array('employee_id' => $employee_id);
				    $data  = $this->managers_model->getManagers($where);
					
				    if($data)
				    {	

				    	$did_list = [];
						foreach ($data as $key => $value) {
							$employee_id     = !empty($value->employee_id)?$value->employee_id:'';
							$position_id     = !empty($value->position_id)?$value->position_id:'';
						   
						    $status             = !empty($value->status)?$value->status:'';
						    $published          = !empty($value->published)?$value->published:'';
						    $createdate         = !empty($value->createdate)?$value->createdate:'';
							$designation_code            = !empty($value->designation_code)?$value->designation_code:'';
						    $ctrl_city_id         = !empty($value->ctrl_city_id)?$value->ctrl_city_id:'';
						    $ctrl_zone_id          = !empty($value->ctrl_zone_id)?$value->ctrl_zone_id:'';
							

							$ctrl_state_id            = !empty($value->ctrl_state_id)?$value->ctrl_state_id:'';
							$exsiting_data=array();
						if(!empty($ctrl_state_id)){
							$state_id_finall = substr($ctrl_state_id,1,-1);
							
			
					    	$d_state = !empty($state_id_finall)?$state_id_finall:'';
					
						    $d_state_val = explode(',', $d_state);
						    $st_count = count($d_state_val);
					     	
					     
					      
					     	for( $i=0; $i < $st_count; $i++){
							
						
					     	 $wer = array(
						    	'id'        =>  $d_state_val[$i],
					    		'published' => '1'
					     	 );
						
					
						     $col='id, state_name';
						     $exsiting_list    = $this->commom_model->getState($wer,'','',"result",'','','','',$col);
							if(!empty($exsiting_list)){
								foreach( $exsiting_list as $value){
									array_push($exsiting_data,$value);
									}
							}
						    
							
					     	}
						}	 
							//CITY details
							$exsiting_ct_data=array();
						if(!empty($ctrl_city_id)){
							
								$city_id_finall = substr($ctrl_city_id,1,-1);
								
				
								$d_city = !empty($city_id_finall)?$city_id_finall:'';
						
								$d_city_val = explode(',', $d_city);
								$ct_count = count($d_city_val);
							
							
								
								 for( $i=0; $i < $ct_count; $i++){
								
							
								  $wer_c = array(
									'id'        =>  $d_city_val[$i],
									'published' => '1'
								  );
							
						
								  $col_c='id, city_name';
								  $exsiting_ct_list    = $this->commom_model->getCity($wer_c,'','',"result",'','','','',$col_c);
								if(!empty($exsiting_ct_list)){
									foreach( $exsiting_ct_list as $value){
										array_push($exsiting_ct_data,$value);
										}
								}
								  
								
								 }
						}
							
							  //ZONE details
							  $exsiting_zn_data=array();
						if(!empty($ctrl_zone_id)){  
							 $zone_id_finall = substr($ctrl_zone_id,1,-1);
							 
			 
							 $d_zone = !empty($zone_id_finall)?$zone_id_finall:'';
					 
							 $d_zone_val = explode(',', $d_zone);
							 $zn_count = count($d_zone_val);
							  
						
							  
							  for( $i=0; $i < $zn_count; $i++){
							 
						 
							   $wer_z = array(
								 'id'        =>  $d_zone_val[$i],
								 'published' => '1'
							   );
						 
					 
							   $col_z='id, name';
							   $exsiting_zn_list    = $this->commom_model->getZone($wer_z,'','',"result",'','','','',$col_z);
							 if(!empty($exsiting_zn_list)){
								foreach( $exsiting_zn_list as $value){
									array_push($exsiting_zn_data,$value);
									}
							 }
							   
							 
							  }
							  
						}
					

				            $did_list[] = array(
								'designation_code'   => $designation_code,
								'ctrl_state_id'      => $exsiting_data,
								'ctrl_city_id'       => $exsiting_ct_data,
								'ctrl_zone_id'       => $exsiting_zn_data,
								'position_id'        => $position_id,
								'employee_id'        => $employee_id,
							    'status'             => $status,
							    'published'          => $published,
							    'createdate'         => $createdate,
	          				);
						}

	        			$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $did_list;
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

		    	else
		    	{
		    		$response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
		    	}
			}

			// List Managers
			else if($method == '_listDistributors')
			{
				$where = array(
					'distributor_type'   => '1', 
					'distributor_status' => '1',
					'status'             => '1', 
					'published'          => '1'
				);

				$data_list = $this->distributors_model->getDistributors($where);

				if($data_list)
				{
					$distributor_list = [];
					foreach ($data_list as $key => $value) {

						$distributor_id = isset($value->id)?$value->id:'';
			            $company_name   = isset($value->company_name)?$value->company_name:'';
			            $gst_no         = isset($value->gst_no)?$value->gst_no:'';
			            $mobile         = isset($value->mobile)?$value->mobile:'';
			            $email          = isset($value->email)?$value->email:'';
			            $address        = isset($value->address)?$value->address:'';
			            $published      = isset($value->published)?$value->published:'';
			            $status         = isset($value->status)?$value->status:'';
			            $createdate     = isset($value->createdate)?$value->createdate:'';

			            $distributor_list[] = array(
          					'distributor_id' => $distributor_id,
				            'company_name'   => $company_name,
				            'gst_no'         => $gst_no,
				            'mobile'         => $mobile,
				            'email'          => $email,
				            'address'        => $address,
				            'published'      => $published,
				            'status'         => $status,
				            'createdate'     => $createdate,
          				);
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $distributor_list;
		    		echo json_encode($response);
			        return;
				}
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Not Found"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}	

			// List Managers
			else if($method == '_listOverallDistributors')
			{    // sathish 'ref_id'
				$ref_id             = $this->input->post('ref_id');
				$where = array(
					'ref_id'    => $ref_id ,
					'status'    => '1', 
					'published' => '1'
				);

				$data_list = $this->distributors_model->getDistributors($where);

				if($data_list)
				{
					$distributor_list = [];
					foreach ($data_list as $key => $value) {

						$distributor_id = isset($value->id)?$value->id:'';
			            $company_name   = isset($value->company_name)?$value->company_name:'';
			            $gst_no         = isset($value->gst_no)?$value->gst_no:'';
			            $mobile         = isset($value->mobile)?$value->mobile:'';
			            $email          = isset($value->email)?$value->email:'';
			            $address        = isset($value->address)?$value->address:'';
			            $published      = isset($value->published)?$value->published:'';
			            $status         = isset($value->status)?$value->status:'';
			            $createdate     = isset($value->createdate)?$value->createdate:'';

			            $distributor_list[] = array(
          					'distributor_id' => $distributor_id,
				            'company_name'   => $company_name,
				            'gst_no'         => $gst_no,
				            'mobile'         => $mobile,
				            'email'          => $email,
				            'address'        => $address,
				            'published'      => $published,
				            'status'         => $status,
				            'createdate'     => $createdate,
          				);
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $distributor_list;
		    		echo json_encode($response);
			        return;
				}
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Not Found"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}


			// Update Managers
			else if($method == '_updateManagers')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('grade');
			    foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error)
			    {
			        $response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }
			    
			   

			    if(count($errors)==0)
			    {	
					$grade              = $this->input->post('grade');
			    	$employee_id     = $this->input->post('employee_id');
					$control_zn_id            = $this->input->post('control_zn_id');
					$control_ct_id            = $this->input->post('control_ct_id');
					$control_st_id            = $this->input->post('control_st_id');

					$_des_wer =array(
						'designation_code'        => $grade,
					   );
					   $_des_col = 'position_id,designation_name';
					   $_des = $this->employee_model->getdesignation($_des_wer,'','',"result",'','','','',$_des_col);
					   foreach ($_des as $key => $value) {
						$position_id  = isset($value->position_id) ? $value->position_id : '';
						$designation_name  = isset($value->designation_name) ? $value->designation_name : '';
					   }
				
					
				
					$data = array(
					'designation_code'       => $grade,
						'role'               => $designation_name,
						'ctrl_state_id'      => $control_st_id,
						'ctrl_city_id'       => $control_ct_id,
						'ctrl_zone_id'       => $control_zn_id,
						'position_id'        => $position_id,
						
						'updatedate'         => date('Y-m-d H:i:s'),
					);

					$update_id  = array('employee_id' => $employee_id);
					
					
					$update  = $this->managers_model->managers_update($data,$update_id);

					$d_ta = array(
						'designation_code'   => $grade,
						'role'               => $designation_name,
						'position_id'       => $position_id,
						'updatedate'         => date('Y-m-d H:i:s'),
					);
					$update_id  = array('id' => $employee_id);
		
					$update_posting_status = $this->employee_model->employee_update($d_ta, $update_id);
				
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

			// Delete Managers
			else if($method == '_deleteManagers')
			{	
		    	$manager_id = $this->input->post('manager_id');

		    	if(!empty($manager_id))
		    	{
		    		$data = array(
				    	'published' => '0',
				    );

		    		
		    		// manager Delete
		    		$where_2  = array('id' => $manager_id);
				    $update_2 = $this->managers_model->managers_delete($data, $where_2);
				    if($update_2)
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

		    	else
		    	{
		    		$response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
		    	}
			}

			
			else if($method == '_distributorZoneList')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id');
			    foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error)
			    {
			        $response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(count($errors)==0)
			    {
			    	$distributor_id = $this->input->post('distributor_id');

			    	// Outlet Details
					$where_1 = array(
						'id'        => $distributor_id,
						'status'    => '1',
						'published' => '1',
					);

					$column_1 = 'zone_id';

					$dis_data = $this->distributors_model->getDistributors($where_1, '', '', 'result', '', '', '', '', $column_1);

					$zone_id  = !empty($dis_data[0]->zone_id)?$dis_data[0]->zone_id:'';

					$where_2 = array(
						'id'        => $zone_id,
						'status'    => '1',
						'published' => '1',
					);

					$column_2 = 'id, name';

					$zone_data = $this->commom_model->getDistributoeZone($where_2, '', '', 'result', '', '', '', '', $column_2);

					if($zone_data)
					{
						$zone_list = [];
						foreach ($zone_data as $key => $value) {
							$zone_id   = !empty($value->id)?$value->id:'';
				            $zone_name = !empty($value->name)?$value->name:'';

				            $zone_list[] = array(
				            	'zone_id'   => $zone_id,
				            	'zone_name' => $zone_name,

				            );
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $zone_list;
				        echo json_encode($response);
				        return;
					}
					else
					{
						$response['status']  = 0;
				        $response['message'] = "Not Found"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
					}
			    }
			}

			else if($method == '_distributorCategoryList')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('distributor_id');
			    foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error)
			    {
			        $response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(count($errors)==0)
			    {
			    	$distributor_id = $this->input->post('distributor_id');

			    	$whr_1  = array('id' => $distributor_id);
			    	$col_1  = 'category_id';
				    $data_1 = $this->distributors_model->getDistributors($whr_1, '', '', 'result', '', '', '', '', $col_1);

				    if($data_1)
				    {
				    	$category_id = !empty($data_1[0]->category_id)?$data_1[0]->category_id:'';

				    	$whr_2 = array(
				    		'category_id' => $category_id,
				    		'published'   => '1',
				    		'status'      => '1',
				    	);

				    	$col_2  = 'id, name';
				    	$data_2 = $this->commom_model->getCategoryImplode($whr_2, '', '', 'result', '', '', '', '', $col_2);

				    	$category_list = [];
				    	if($data_2)
				    	{
				    		foreach ($data_2 as $key => $val) {
					    		$cat_id   = !empty($val->id)?$val->id:'';
	            				$cat_name = !empty($val->name)?$val->name:'';

	            				$category_list[] = array(
	            					'category_id'   => $cat_id,
	            					'category_name' => $cat_name,
	            				);
					    	}
				    	}

				    	$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $category_list;
				        echo json_encode($response);
				        return;
				    }
				    else
				    {
				    	$response['status']  = 0;
				        $response['message'] = "Data Not Found"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
				    }
			    }
			}
			
			else if($method == 'list_hierarchy')
			{    
				$limit  = $this->input->post('limit');
	    		$offset = $this->input->post('offset');
				//sathish
				$grade = $this->input->post('grade');
                $hierarchy = $this->input->post('employee_id');

				

	    		if($limit !='' && $offset !='')
				{
					$limit  = $limit;
					$offset = $offset;
				}
				else
				{
					$limit  = 10;
					$offset = 0;
				}

				$search = $this->input->post('search');
	    		if($search !='')
	    		{
	    			$like['name'] = $search;
	    			$where = array(
	    				
	    				'published'        => '1',
						'position_id'           => $grade
	    			);
					$column = 'id';
				    $overalldatas = $this->employee_model->getEmployee($where, '', '', 'result', $like, '', '', '', $column);
	    		}
	    		else
	    		{
					$where = array('employee_id' => $hierarchy);
				$data  = $this->managers_model->getManagers($where);
				
				if($data)
				{	

					$overalldatas = [];
					foreach ($data as $key => $value) {
					
						$ctrl_state_id            = !empty($value->ctrl_state_id)?$value->ctrl_state_id:'';
						$ctrl_city_id         = !empty($value->ctrl_city_id)?$value->ctrl_city_id:'';
						$ctrl_zone_id          = !empty($value->ctrl_zone_id)?$value->ctrl_zone_id:'';
						

						$ctrl_state_id            = !empty($value->ctrl_state_id)?$value->ctrl_state_id:'';
						$exsiting_data=array();
					    if(!empty($ctrl_state_id)){
						$state_id_finall = substr($ctrl_state_id,1,-1);
						
		
						$d_state = !empty($state_id_finall)?$state_id_finall:'';
				
						$d_state_val = explode(',', $d_state);
						$st_count = count($d_state_val);
						 
						
					  $overalldatas=array();
						 for( $i=0; $i < $st_count; $i++){
						
					
						
							$where_1  = array(
								'position_id'        => $grade,
								//'status'         => '1', 
								'published'      => '1'
							);
		
							$like['ctrl_state_id'] =','. $d_state_val[$i].',';
							
							$column = 'employee_id';
		
							$assign_data = $this->managers_model->getAssignStateDetails($where_1, '', '', 'result', $like, '', '', '', $column);
							if($assign_data){
								foreach( $assign_data as $key => $value){
									$ctrl_state_id            = !empty($value->employee_id)?$value->employee_id:'';
									array_push($overalldatas,$ctrl_state_id);
								}
							}
						}
						
							
				    	// $whr_1 = array(
						// 	'B.published ' => 1,
							
						// );

				    	// if($overalldatas)
				    	// {
				    	// 	$whr_1['A.emp_id'] = $overalldatas;
				    	// }
				    	// if($state_id)
				    	// {
				    	// 	$whr_1['E.state_id'] = $state_id;
				    	// }
				    	// if($city_id)
				    	// {
				    	// 	$whr_1['E.city_id'] = $city_id;
				    	// }
				    	// if($zone_id)
				    	// {
				    	// 	$whr_1['E.zone_id'] = $zone_id;
				    	// }
				    	if($overalldatas)
				    	{
				    		$whr_in['A.employee_id'] = $overalldatas;
				    	}

						$col_1 = 'B.id, A.ctrl_state_id, A.position_id, B.first_name, B.last_name, B.email , B.mobile, A.role, B.published, B.status';

						$qry_1 = $this->managers_model->getManagersOverallJoin('', '', '', 'result', '', '', '', '', $col_1, '', $whr_in);

						// echo $this->db->last_query(); exit;

						if($qry_1)
						{
							$data_list = [];
							foreach ($qry_1 as $key => $val_1) {

								$employee_id      = empty_check($val_1->id);
							    $first_name      = empty_check($val_1->first_name);
								$last_name      = empty_check($val_1->last_name);
							    $ctrl_state_id    = empty_check($val_1->ctrl_state_id);
							    $position_id    = empty_check($val_1->position_id);
							    $email     = empty_check($val_1->email);
								$mobile     = empty_check($val_1->mobile);
								$role     = empty_check($val_1->role);
								$status     = empty_check($val_1->status);

								$where = array('position_id' => $position_id,
								'published' =>1);
									$pos_name  = $this->employee_model->getdesignation($where);
									if ($pos_name) {
					
										
					
										foreach ($pos_name as $key => $value) {
					
											$designation_name = isset($value->designation_name) ? $value->designation_name : '';
											$designation_code = isset($value->designation_code) ? $value->designation_code : '';
										}
									}
								$data_list = array(
									'employee_id'  => $employee_id,
								  'first_name'    => $first_name,
								  'last_name'          => $last_name,
								  'mobile'          => $mobile,
								  'email'           => $email,
								  'position_id'         => $position_id,
								  'status'          => $status,
								  'designation_code' => $designation_code
								);
						
							}
						}
						 if($overalldatas)
						 {
							 $totalc = count($overalldatas);
							 for( $i=0; $i < $totalc; $i++){
								$employee_id            = !empty($overalldatas[$i]->employee_id)?$overalldatas[$i]->employee_id:'';

								$where_1  = array(
									'position_id'        => $grade,
									//'status'         => '1', 
									'published'      => '1'
								);
			
								$like['ctrl_state_id'] =','. $d_state_val[$i].',';
								
								$column = 'employee_id,ctrl_state_id';
			
								$assign_data = $this->managers_model->getAssignStateDetails($where_1, '', '', 'result', $like, '', '', '', $column);

							 }
			 
								 
								 foreach ($data as $key => $value) {
									$employee_id            = !empty($value->employee_id)?$value->employee_id:'';
									$ctrl_state_id            = !empty($value->ctrl_state_id)?$value->ctrl_state_id:'';
								 }
						 }
						 else
						 {
							 $totalc = 0;
						 }
						 
		 
						 $option['order_by']   = 'id';
						 $option['disp_order'] = 'DESC';
		 
						 $data_list = $this->employee_model->getEmployee($where, $limit, $offset, 'result', $like, '', $option);
		 
						 if($data_list)
						 {
							 $details_list = [];
							 foreach ($data_list as $key => $value) {
		 
								 $employee_id = isset($value->id) ? $value->id : '';
								 $first_name    = isset($value->first_name) ? $value->first_name : '';
								 $last_name    = isset($value->last_name) ? $value->last_name : '';
								 $mobile      = isset($value->mobile) ? $value->mobile : '';
								 $email       = isset($value->email) ? $value->email : '';
								 $pincode     = isset($value->pincode) ? $value->pincode : '';
								 $street_name     = isset($value->street_name) ? $value->street_name : '';
								 $position_id = isset($value->position_id) ? $value->position_id	 : '';
								 $published   = isset($value->published) ? $value->published : '';
								 $status      = isset($value->status) ? $value->status : '';
								 $createdate  = isset($value->createdate) ? $value->createdate : '';
								 $posting_status = isset($value->posting_status) ? $value->posting_status : '';
		 
								 $where = array('position_id' => $position_id,
								 'published' =>1);
									 $pos_name  = $this->employee_model->getdesignation($where);
									 if ($pos_name) {
					 
										 
					 
										 foreach ($pos_name as $key => $value) {
					 
											 $designation_name = isset($value->designation_name) ? $value->designation_name : '';
											 $designation_code = isset($value->designation_code) ? $value->designation_code : '';
										 }
									 }
								 
		 
								 $details_list[] = array(
									   'employee_id'  => $employee_id,
									 'first_name'    => $first_name,
									 'last_name'          => $last_name,
									 'mobile'          => $mobile,
									 'pincode'         => $pincode,
									 'email'           => $email,
									 'position_id'         => $position_id,
									 'published'       => $published,
									 'status'          => $status,
									 'posting_status'      => $createdate,
									 'designation_code' => $designation_code
								   );
							 }
		 
							 if($offset !='' && $limit !='') {
								 $offset = $offset + $limit;
								 $limit  = $limit;
							 } 
							 else {
								 $offset = $limit;
								 $limit  = 10;
							 }

							 $response['status']       = 1;
							 $response['message']      = "Success"; 
							 $response['total_record'] = $totalc;
							 $response['offset']       = (int)$offset;
							 $response['limit']        = (int)$limit;
							 $response['data']         = $details_list;
							 echo json_encode($response);
							 return;
						 }
						 else
						 {
							 $response['status']  = 0;
							 $response['message'] = "Not Found"; 
							 $response['data']    = [];
							 echo json_encode($response);
							 return;
						 }
						

					    }
				    }
				}
	    			
	    		}

	    		

			

			
			}


			else if($method == '_access')
			{
				$distributor_id = $this->input->post('distributor_id');
				$where = array(
					'id'    => $distributor_id, 
					
				);
                $col_g='grade';
				$data = $this->distributors_model->getDistributors($where, '', '', 'result', '', '', '', '', $col_g);
                $grade = !empty($data[0]->grade)?$data[0]->grade:'';

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $grade;
		    		echo json_encode($response);
			        return;
				
			}	
			
		}

		public function Hierarchy_list($param1="",$param2="",$param3="")
		{
			$method         = $this->input->post('method');
			$state_c_id     = $this->input->post('state_c_id');
			$city_z_id      = $this->input->post('city_z_id');
			$zone_z_id      = $this->input->post('zone_z_id');
			
			if($method == '_hierarchy')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('state_c_id');
			    foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error)
			    {
			        $response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
			    }

			    if(count($errors)==0)
			    {
					
			    		if(!empty($zone_z_id)){
							
							
							$where = array(
								'A.designation_code' =>  'BDE',
								'B.published'     => '1',
							);
	
						
								$like_1['A.ctrl_zone_id'] = ','.$zone_z_id.',';

								$col ='A.employee_id,A.position_id,A.ctrl_city_id,A.status,B.first_name,B.last_name';
							
	                 
							$att_data = $this->managers_model->getManagersOverallJoin($where, '', '', 'result', $like_1, '', '', '', $col);

							if(!empty($att_data))
						    {
							$employee_list = [];
							foreach ($att_data as $key => $val_1) {
								$employee_id     = !empty($val_1->employee_id)?$val_1->employee_id:'';
								$status   = !empty($val_1->status)?$val_1->status:'';
								$first_name = !empty($val_1->first_name)?$val_1->first_name:'';
								$last_name     = !empty($val_1->last_name)?$val_1->last_name:'';
								$arr3 = array($first_name,$last_name);
								$bde_name =join(" ",$arr3);
								$zone_z_id     = $zone_z_id;

								$where_z = array(
								'id'       => $zone_z_id,
									'published'     => '1',
								);
							
								$col_z ='id,name';
								$att_data = $this->commom_model->getZone($where_z, '', '', 'result', '', '', '', '', $col_z);
							
								$dt_count=count($att_data);
			
				                   for($i=0; $i < $dt_count; $i++){

                                         $zone_data   = !empty($att_data[$i]->name)?$att_data[$i]->name:'';
										 $zone_id   = !empty($att_data[$i]->id)?$att_data[$i]->id:'';
				
				                    }

									$where = array(
										'A.designation_code' =>  'TSI',
										'B.published'     => '1',
									);
			
								
										$like_2['A.ctrl_zone_id'] = ','.$zone_z_id.',';
		
										$col ='A.employee_id,A.position_id,A.ctrl_city_id,A.status,B.first_name,B.last_name';
									
							 
									$tsi_data = $this->managers_model->getManagersOverallJoin($where, '', '', 'result', $like_2, '', '', '', $col);

									if(!empty($tsi_data)){
										foreach ($tsi_data as $key => $val_1) {
											$tsi_f_name     = !empty($val_1->first_name)?$val_1->first_name:'';
											$tsi_l_name     = !empty($val_1->last_name)?$val_1->last_name:'';
											
											$arrtsi = array($tsi_f_name,$tsi_l_name);
											$tsi_name =join(" ",$arrtsi);
											
										}
									}else{
										$tsi_name = '';
									}
									
									
				
									

								$employee_list[] = array(
									'status'     => $status,
									'employee_id'=> $employee_id,
									'bde_name'   => $bde_name,
									'tsi_name'   => $tsi_name,
									'zone_desc'  => $zone_data,
									'zone_id'    => $zone_id,
								);
							}

							$where_asm = array(
								'A.designation_code' =>  'ASM',
								'B.published'     => '1',
							);
	
						
								$like_3['A.ctrl_state_id'] = ','.$state_c_id.',';

								$col_asm ='A.employee_id,A.status,B.first_name,B.last_name';
							
	                 
							$asm_data = $this->managers_model->getManagersOverallJoin($where_asm, '', '', 'result', $like_3, '', '', '', $col_asm);
							if(!empty($asm_data)){
								foreach ($asm_data as $key => $val_1) {
									$asm_f_name     = !empty($val_1->first_name)?$val_1->first_name:'';
									$asm_l_name     = !empty($val_1->last_name)?$val_1->last_name:'';
									$asm_id   = !empty($val_1->employee_id)?$val_1->employee_id:'';
									$arr2 = array($asm_f_name,$asm_l_name);
									$asm_name =join(" ",$arr2);
								}
							}else{
								$asm_name ='';
							}
							

							$where_so = array(
								'A.designation_code' =>  'SO',
								'B.published'     => '1',
							);
	
						
								$like_4['A.ctrl_city_id'] = ','.$city_z_id.',';

								$col_so ='A.employee_id,A.status,B.first_name,B.last_name';
							
	                 
							$so_data = $this->managers_model->getManagersOverallJoin($where_so, '', '', 'result', $like_4, '', '', '', $col_so);
							if(!empty($so_data)){
								foreach ($so_data as $key => $val_1) {
									$so_f_name     = !empty($val_1->first_name)?$val_1->first_name:'';
									$so_l_name     = !empty($val_1->last_name)?$val_1->last_name:'';
									$so_id   = !empty($val_1->employee_id)?$val_1->employee_id:'';
									$arr1 = array($so_f_name,$so_l_name);
									$so_name =join(" ",$arr1);
									
								}
							}else{
								$so_name ="";
							}
						

							$where_rsm = array(
								'A.designation_code' =>  'RSM',
								'B.published'     => '1',
							);
	
						
								$like_5['A.ctrl_state_id'] = ','.$state_c_id.',';

								$col_rsm ='A.employee_id,A.status,B.first_name,B.last_name';
							
	                 
							$rsm_data = $this->managers_model->getManagersOverallJoin($where_rsm, '', '', 'result', $like_5, '', '', '', $col_rsm);
							if(!empty($rsm_data)){
								foreach ($rsm_data as $key => $val_1) {
									$first_name     = !empty($val_1->first_name)?$val_1->first_name:'';
									$last_name     = !empty($val_1->last_name)?$val_1->last_name:'';
									$id   = !empty($val_1->employee_id)?$val_1->employee_id:'';
									$arr = array($first_name,$last_name);
									$name =join(" ",$arr);
									
									
								}
							}else{
								$name ='';
							}
							

							$response['status']   = 1;
					        $response['message']  = "Success"; 
					        $response['data']     = $employee_list;
							$response['datar']    = $name;
							$response['dataa']    = $asm_name;
							$response['datas']    = $so_name;
					        echo json_encode($response);
					        return;
						    }
						    else
						    {
							$response['status']  = 0;
					        $response['message'] = "Data Not Found"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
						    }

						}
						// else if(empty($zone_z_id) && empty($city_z_id))
						// {

						// 	$where = array(
						// 		'A.designation_code' =>  'TSI',
						// 		'B.published'     => '1',
						// 	);
	
						
						// 		$like['A.ctrl_city_id'] = ','.$city_z_id.',';

						// 		$col ='A.employee_id,A.position_id,A.ctrl_city_id,A.status,B.first_name,B.last_name';
							
	                 
						// 	$att_data = $this->managers_model->getManagersOverallJoin($where, '', '', 'result', $like, '', '', '', $col);

						// 	if(!empty($att_data))
						//     {
						// 	$attendance_list = [];
						// 	foreach ($att_data as $key => $val_1) {
						// 		$employee_id     = !empty($val_1->employee_id)?$val_1->employee_id:'';
						// 		$status   = !empty($val_1->status)?$val_1->status:'';
						// 		$first_name = !empty($val_1->first_name)?$val_1->first_name:'';
						// 		$last_name     = !empty($val_1->last_name)?$val_1->last_name:'';
						// 		$arr3 = array($first_name,$last_name);
						// 		$tsi_name =join(" ",$arr3);
						// 		$zone_z_id     = $zone_z_id;
						// 		$where_z = array(
						// 		'id'       => $zone_z_id,
						// 			'published'     => '1',
						// 		);
							
						// 		$col_z ='name';
						// 		$att_data = $this->commom_model->getZone($where_z, '', '', 'result', '', '', '', '', $col_z);
							
						// 		$dt_count=count($att_data);
			
				        //            for($i=0; $i < $dt_count; $i++){

                        //                  $zone_data   = !empty($att_data[$i]->name)?$att_data[$i]->name:'';
				
				
				        //             }
				
									
						// 	$where_asm = array(
						// 		'A.designation_code' =>  'ASM',
						// 		'B.published'     => '1',
						// 	);
	
						
						// 		$like['A.ctrl_state_id'] = ','.$state_c_id.',';

						// 		$col_asm ='A.employee_id,A.status,B.first_name,B.last_name';
							
	                 
						// 	$att_data = $this->managers_model->getManagersOverallJoin($where_asm, '', '', 'result', $like, '', '', '', $col_asm);
						// 	foreach ($att_data as $key => $val_1) {
						// 		$asm_f_name     = !empty($val_1->first_name)?$val_1->first_name:'';
						// 		$asm_l_name     = !empty($val_1->last_name)?$val_1->last_name:'';
						// 		$asm_id   = !empty($val_1->employee_id)?$val_1->employee_id:'';
						// 		$arr2 = array($asm_f_name,$asm_l_name);
						// 		$asm_name =join(" ",$arr2);
						// 	}
						// 	$where_so = array(
						// 		'A.designation_code' =>  'SO',
						// 		'B.published'     => '1',
						// 	);
	
						
						// 		$like['A.ctrl_city_id'] = ','.$city_z_id.',';

						// 		$col_so ='A.employee_id,A.status,B.first_name,B.last_name';
							
	                 
						// 	$att_data = $this->managers_model->getManagersOverallJoin($where_so, '', '', 'result', $like, '', '', '', $col_so);
						// 	foreach ($att_data as $key => $val_1) {
						// 		$so_f_name     = !empty($val_1->first_name)?$val_1->first_name:'';
						// 		$so_l_name     = !empty($val_1->last_name)?$val_1->last_name:'';
						// 		$so_id   = !empty($val_1->employee_id)?$val_1->employee_id:'';
						// 		$arr1 = array($so_f_name,$so_l_name);
						// 		$so_name =join(" ",$arr1);
								
						// 	}
						// 		$attendance_list[] = array(
						// 			'status'     => $status,
						// 			'employee_id'=> $employee_id,
						// 			'asm_name' => $asm_name,
						// 			'so_name'  => $so_name,
						// 			'tsi_name' => $tsi_name,
						// 			'zone_desc'  => $zone_data,
						// 			'asm_id'     => $asm_id,
						// 			'so_id'     => $so_id,
						// 		);
						// 	}
						// 	$where_rsm = array(
						// 		'A.designation_code' =>  'SO',
						// 		'B.published'     => '1',
						// 	);
	
						
						// 		$like['A.ctrl_city_id'] = ','.$city_z_id.',';

						// 		$col_rsm ='A.employee_id,A.status,B.first_name,B.last_name';
							
	                 
						// 	$att_data = $this->managers_model->getManagersOverallJoin($where_rsm, '', '', 'result', $like, '', '', '', $col_rsm);
						// 	$rsm = [];
						// 	foreach ($att_data as $key => $val_1) {
						// 		$first_name     = !empty($val_1->first_name)?$val_1->first_name:'';
						// 		$last_name     = !empty($val_1->last_name)?$val_1->last_name:'';
						// 		$id   = !empty($val_1->employee_id)?$val_1->employee_id:'';
						// 		$arr = array($first_name,$last_name);
						// 		$name =join(" ",$arr);
						// 		$rsm[] = array(
						// 			'name' => $name,
						// 			'id'        => $id,
						// 		);
								
						// 	}

						// 	$response['status']  = 1;
					    //     $response['message'] = "Success"; 
					    //     $response['data']    = $attendance_list;
						// 	$response['datat']    = $name;
					    //     echo json_encode($response);
					    //     return;
						//     }
						//     else
						//     {
						// 	$response['status']  = 0;
					    //     $response['message'] = "Data Not Found"; 
					    //     $response['data']    = [];
					    //     echo json_encode($response);
					    //     return;
						//     }
						// }
						else
						{

							$where = array(
								'A.designation_code' =>  'BDE',
								'B.published'     => '1',
							);
	
						
								$like_6['A.ctrl_city_id'] = ','.$city_z_id.',';

								$col ='A.employee_id,A.position_id,A.ctrl_zone_id,A.status,B.first_name,B.last_name';
							
	                 
							$att_data = $this->managers_model->getManagersOverallJoin($where, '', '', 'result', $like_6, '', '', '', $col);

							if(!empty($att_data))
						    {
							$employee_list = [];
							foreach ($att_data as $key => $val_1) {
								$status     = !empty($val_1->status)?$val_1->status:'';
								$ctrl_zone_id   = !empty($val_1->ctrl_zone_id)?$val_1->ctrl_zone_id:'';
								$first_name = !empty($val_1->first_name)?$val_1->first_name:'';
								$last_name     = !empty($val_1->last_name)?$val_1->last_name:'';
								$arr3 = array($first_name,$last_name);
								$bde_name =join(" ",$arr3);
								$zone_z_id     = $zone_z_id;


								
							
							if(!empty($ctrl_zone_id)){
								$zone_id_finall = substr($ctrl_zone_id,1,-1);
								
				
								$d_zone = !empty($zone_id_finall)?$zone_id_finall:'';
						
								$d_zone_val = explode(',', $d_zone);
								$st_count = count($d_zone_val);
								 
						
							  
								for( $i=0; $i < $st_count; $i++){
								
							
								  $wer = array(
									'id'        =>  $d_zone_val[$i],
									'published' => '1'
								  );
							
						
								 $col='id,name';
								 $exsiting_list    = $this->commom_model->getZone($wer,'','',"result",'','','','',$col);
								 foreach ($exsiting_list as $key => $val_zone) {
									$zone_data     = !empty($val_zone->name)?$val_zone->name:'';
									$zone_id     = !empty($val_zone->id)?$val_zone->id:'';
									
									$where = array(
										'A.designation_code' =>  'TSI',
										'B.published'     => '1',
									);
			
								
										$like_7['A.ctrl_zone_id'] = ','.$zone_id.',';
		
										$col ='A.employee_id,A.position_id,A.ctrl_zone_id,A.status,B.first_name,B.last_name';
									
							 
									$tsi_att = $this->managers_model->getManagersOverallJoin($where, '', '', 'result', $like_7, '', '', '', $col);
								
									if(!empty($tsi_att))
									{
									
									 foreach ($tsi_att as $key => $val_1) {
										$employee_id     = !empty($val_1->employee_id)?$val_1->employee_id:'';
										$ctrl_zone_id   = !empty($val_1->ctrl_zone_id)?$val_1->ctrl_zone_id:'';
										$first_name = !empty($val_1->first_name)?$val_1->first_name:'';
										$last_name     = !empty($val_1->last_name)?$val_1->last_name:'';
										$arr3 = array($first_name,$last_name);
										$tsi_name =join(" ",$arr3);
										$zone_z_id     = $zone_z_id;
									 }
								    }else{
										$employee_id ='';
										$tsi_name   ='';
									}
		
		
									
									
								 }

								 $employee_list[] = array(
									
									'status'     => $status,
									'employee_id'=> $employee_id,
									'bde_name'   => $bde_name,
									'tsi_name'   => $tsi_name,
									'zone_desc'  => $zone_data,
									'zone_id'    => $zone_id,
								 );

								}
							}
								
							}
							$where_asm = array(
								'A.designation_code' =>  'ASM',
								'B.published'     => '1',
							);
	
						
								$like_8['A.ctrl_state_id'] = ','.$state_c_id.',';

								$col_asm ='A.employee_id,A.status,B.first_name,B.last_name';
							
	                 
							$asm_data = $this->managers_model->getManagersOverallJoin($where_asm, '', '', 'result', $like_8, '', '', '', $col_asm);
						if(!empty($asm_data)){
							foreach ($asm_data as $key => $val_31) {
								$asm_f_name     = !empty($val_31->first_name)?$val_31->first_name:'';
								$asm_l_name     = !empty($val_31->last_name)?$val_31->last_name:'';
								$asm_id   = !empty($val_31->employee_id)?$val_31->employee_id:'';
								$arr2 = array($asm_f_name,$asm_l_name);
								$asm_name =join(" ",$arr2);
							}

						}else{
							$asm_name = '';
						}
						
							$where_so = array(
								'A.designation_code' =>  'SO',
								'B.published'     => '1',
							);
	
						
								$like_9['A.ctrl_city_id'] = ','.$city_z_id.',';

								$col_so ='A.employee_id,A.status,B.first_name,B.last_name';
							
	                 
							$so_data = $this->managers_model->getManagersOverallJoin($where_so, '', '', 'result', $like_9, '', '', '', $col_so);

							if(!empty($so_data)){
								foreach ($so_data as $key => $val_1) {
									$so_f_name     = !empty($val_1->first_name)?$val_1->first_name:'';
									$so_l_name     = !empty($val_1->last_name)?$val_1->last_name:'';
									$so_id   = !empty($val_1->employee_id)?$val_1->employee_id:'';
									$arr1 = array($so_f_name,$so_l_name);
									$so_name =join(" ",$arr1);
									
								}
	
							}else{
								
								$so_name ='';
							}
						
							$where_rsm = array(
								'A.designation_code' =>  'RSM',
								'B.published'     => '1',
							);
	
						
								$like_10['A.ctrl_state_id'] = ','.$state_c_id.',';

								$col_rsm ='A.employee_id,A.status,B.first_name,B.last_name';
							
	                 
							$rsm_data = $this->managers_model->getManagersOverallJoin($where_rsm, '', '', 'result', $like_10, '', '', '', $col_rsm);
						    if(!empty($rsm_data)){
								foreach ($rsm_data as $key => $val_1) {
									$first_name     = !empty($val_1->first_name)?$val_1->first_name:'';
									$last_name     = !empty($val_1->last_name)?$val_1->last_name:'';
									$id   = !empty($val_1->employee_id)?$val_1->employee_id:'';
									$arr = array($first_name,$last_name);
									$name =join(" ",$arr);
									
									
								}
							}else{
								$name = '';
							}
						
							
							$response['status']   = 1;
					        $response['message']  = "Success"; 
					        $response['data']     = $employee_list;
							$response['datar']    = $name;
							$response['dataa']    = $asm_name;
							$response['datas']    = $so_name;
					        echo json_encode($response);
					        return;
						    }
						    else
						    {
							$response['status']  = 0;
					        $response['message'] = "Data Not Found"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
						    }
						}


						
			    	
			    }
			}else if($method =='gethierarchy'){
				
					$manager_id    = $this->input->post('manager_id');
					$designation_code_  = $this->input->post('designation_code');
					$count_emp = [];
					if(empty($manager_id))	{
						
						$wer = array(
							'designation_code'  => $designation_code_, 
							'published'      => '1'
						);
						
						$co1 ='employee_id';

						$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', '', '', '', '', $co1);
						
						if(!empty($mg_val)){
								
							foreach ($mg_val as $key => $value) {
								array_push($count_emp,$value);
								
							
							}
						}
					}else{
						
						$where_mg = array(
						
							'employee_id' => $manager_id,
						);
	
	
						$mg_val = $this->managers_model->getManagers($where_mg);
						$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id)?$mg_val[0]->ctrl_zone_id:'0';
						$ctrl_state = !empty($mg_val[0]->ctrl_state_id)?$mg_val[0]->ctrl_state_id:'0';
						$ctrl_city = !empty($mg_val[0]->ctrl_city_id)?$mg_val[0]->ctrl_city_id:'0';
						$position_id = !empty($mg_val[0]->position_id)?$mg_val[0]->position_id:'0';
						$designation_code = !empty($mg_val[0]->designation_code)?$mg_val[0]->designation_code:'0';
					
						if($designation_code == 'RSM' ||  $designation_code == 'ASM'){
							if($ctrl_state){
								$state_id_finall = substr($ctrl_state,1,-1);
						
		
								$d_state = !empty($state_id_finall)?$state_id_finall:'';
						
								$d_state_val = explode(',', $d_state);
								$st_count = count($d_state_val);
								$count_emp = [];
								for( $i=0; $i < $st_count; $i++){



									$wer = array(
										'designation_code'  => $designation_code_, 
										'published'      => '1'
									);
									$like['ctrl_state_id'] =','. $d_state_val[$i].',';
		
									$co1 ='employee_id';
		
									$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);
								
									if(!empty($mg_val)){
											
										foreach ($mg_val as $key => $value) {
											array_push($count_emp,$value);
											
										
										}
									}
								}
							}
						
						}
					
						if($designation_code=='SO'){
							if($ctrl_city){
								$city_id_finall = substr($ctrl_city,1,-1);
						
		
								$d_city = !empty($city_id_finall)?$city_id_finall:'';
						
								$d_city_val = explode(',', $d_city);
								$st_count = count($d_city_val);
								$count_emp = [];
								for( $i=0; $i < $st_count; $i++){



									$wer = array(
										'designation_code'  => $designation_code_, 
										'published'      => '1'
									);
									$like['ctrl_city_id'] =','. $d_city_val[$i].',';
		
									$co1 ='employee_id';
		
									$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);
								 
									if(!empty($mg_val)){
											
										foreach ($mg_val as $key => $value) {
											array_push($count_emp,$value);
											
										
										}
									}
								}
							}
						
						}
						
						if($designation_code=='TSI'){
							if($ctrl_zone){
								$zone_id_finall = substr($ctrl_zone,1,-1);
						
		
								$d_zone = !empty($zone_id_finall)?$zone_id_finall:'';
						
								$d_zone_val = explode(',', $d_zone);
								$st_count = count($d_zone_val);
								$count_emp = [];
								
								for( $i=0; $i < $st_count; $i++){



									$wer = array(
										'designation_code'  => $designation_code_, 
										'published'      => '1'
									);
									$like['ctrl_zone_id'] =','. $d_zone_val[$i].',';
		
									$co1 ='employee_id';
		
									$mg_val = $this->managers_model->getAssignStateDetails($wer, '', '', 'result', $like, '', '', '', $co1);
									
									if(!empty($mg_val)){
											
										foreach ($mg_val as $key => $value){
											array_push($count_emp,$value);
											
										
										}
									}
								}
							}
						
						}
						
					}
					
						$emp_c=count($count_emp);
						
						for( $i=0; $i < $emp_c; $i++){
							$new_st_id[]   = !empty($count_emp[$i]->employee_id)?$count_emp[$i]->employee_id:'';
						 
						}
						$where_o = array(
						
							'published' => 1,
						);

						$whr_in['id'] = $new_st_id;
							
							$overalldatas = $this->employee_model->getEmployee($where_o, '', '', 'result', '', '', '', '', '',$whr_in);
						
							$employee_list = array();
							foreach ($overalldatas as $key => $val_1) {
								$employee_id     = !empty($val_1->id)?$val_1->id:'';
								$last_name     = !empty($val_1->last_name)?$val_1->last_name:'';
								$first_name     = !empty($val_1->first_name)?$val_1->first_name:'';
								$position_id     = !empty($val_1->position_id)?$val_1->position_id:'';
								$mobile     = !empty($val_1->mobile)?$val_1->mobile:'';
							
								$arr = array($first_name,$last_name);
								$name =join(" ",$arr);
								
								$employee_list[] = array(
									'employee_id'		=> $employee_id,
									'mobile'    		=> $mobile,
									'position_id'   	=> $position_id,
									'name'    			=> $name,
								);
							}
						
					$response['status']       = 1;
					$response['message']      = "Success";
					$response['data']         = $employee_list;
					echo json_encode($response);
					return;
			}else if($method =='get_state'){
				
				$manager_id    = $this->input->post('manager_id');
			
				
					
					$where_mg = array(
					
						'employee_id' => $manager_id,
					);


					$mg_val = $this->managers_model->getManagers($where_mg);
					$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id)?$mg_val[0]->ctrl_zone_id:'0';
					$ctrl_state = !empty($mg_val[0]->ctrl_state_id)?$mg_val[0]->ctrl_state_id:'0';
					$ctrl_city = !empty($mg_val[0]->ctrl_city_id)?$mg_val[0]->ctrl_city_id:'0';
					$position_id = !empty($mg_val[0]->position_id)?$mg_val[0]->position_id:'0';
					$designation_code = !empty($mg_val[0]->designation_code)?$mg_val[0]->designation_code:'0';

					$state_list = array();

					
						if($ctrl_state){
							$state_id_finall = substr($ctrl_state,1,-1);
					
	
							$d_state = !empty($state_id_finall)?$state_id_finall:'';
					
							$d_state_val = explode(',', $d_state);
							$st_count = count($d_state_val);
							
							$count_emp = [];
						
								$wer = array(
									
									'published'      => '1'
								);
								$wher_in['id']  = $d_state_val;
								$col_m ='state_name,state_code,id';
	
								$mg_val = $this->commom_model->getState($wer, '', '', 'result', '', '', '', '', $col_m,$wher_in);
							
								if(!empty($mg_val)){
										
									foreach ($mg_val as $key => $value) {
										$id     = !empty($value->id)?$value->id:'';
										$state_name     = !empty($value->state_name)?$value->state_name:'';
										$state_code     = !empty($value->state_code)?$value->state_code:'';
										
										
										$state_list[] = array(
											'id'			=> $id,
											'state_name'  	=> $state_name,
											'state_code'   	=> $state_code,
											
										);
									}
								}
							
						}
					
					
					
					
				$response['status']       = 1;
				$response['message']      = "Success";
				$response['data']         = $state_list;
				echo json_encode($response);
				return;
			}else if($method =='_listCity'){
				
				$manager_id    = $this->input->post('id');
				$state_id    = $this->input->post('state_id');
				
					
					$where_mg = array(
					
						'employee_id' => $manager_id,
					);


					$mg_val = $this->managers_model->getManagers($where_mg);
					$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id)?$mg_val[0]->ctrl_zone_id:'0';
					$ctrl_state = !empty($mg_val[0]->ctrl_state_id)?$mg_val[0]->ctrl_state_id:'0';
					$ctrl_city = !empty($mg_val[0]->ctrl_city_id)?$mg_val[0]->ctrl_city_id:'0';
					$position_id = !empty($mg_val[0]->position_id)?$mg_val[0]->position_id:'0';
					$designation_code = !empty($mg_val[0]->designation_code)?$mg_val[0]->designation_code:'0';

					$city_list = array();

					if($designation_code=='RSM'){
						$wer_c = array(
							'state_id'	     => $state_id,
							'published'      => '1'
						);
						
						$col_c ='city_name,city_code,id';

						$ct_val = $this->commom_model->getCity($wer_c, '', '', 'result', '', '', '', '', $col_c);

						if(!empty($ct_val)){
										
							foreach ($ct_val as $key => $value_2) {
								$id     = !empty($value_2->id)?$value_2->id:'';
								$city_name     = !empty($value_2->city_name)?$value_2->city_name:'';
								$city_code     = !empty($value_2->city_code)?$value_2->city_code:'';
								
								
								$city_list[] = array(
									'id'			=> $id,
									'city_name'  	=> $city_name,
									'city_code'   	=> $city_code,
									
								);
							}
						}

					}
					if($designation_code=='ASM'){
						$wer_c = array(
							'state_id'	     => $state_id,
							'published'      => '1'
						);
						
						$col_c ='city_name,city_code,id';

						$ct_val = $this->commom_model->getCity($wer_c, '', '', 'result', '', '', '', '', $col_c);

						if(!empty($ct_val)){
										
							foreach ($ct_val as $key => $value_2) {
								$id     = !empty($value_2->id)?$value_2->id:'';
								$city_name     = !empty($value_2->city_name)?$value_2->city_name:'';
								$city_code     = !empty($value_2->city_code)?$value_2->city_code:'';
								
								
								$city_list[] = array(
									'id'			=> $id,
									'city_name'  	=> $city_name,
									'city_code'   	=> $city_code,
									
								);
							}
						}
					
					}
					
					if($designation_code=='SO' || $designation_code=='TSI'){
						
						if($ctrl_city){
							$city_id_finall = substr($ctrl_city,1,-1);
					
							
							$d_city = !empty($city_id_finall)?$city_id_finall:'';
							
							$d_city_val = explode(',', $d_city);
							$st_count = count($d_city_val);
							
							$count_emp = [];
					
							$wer = array(
									
								'published'      => '1'
							);
							$wher_in['id']  = $d_city_val;
							$col_m ='city_name,city_code,id';

							$mg_val = $this->commom_model->getCity($wer, '', '', 'result', '', '', '', '', $col_m,$wher_in);
						
							if(!empty($mg_val)){
									
								foreach ($mg_val as $key => $value) {
									$id     = !empty($value->id)?$value->id:'';
									$city_name     = !empty($value->city_name)?$value->city_name:'';
									$city_code     = !empty($value->city_code)?$value->city_code:'';
									
									
									$city_list[] = array(
										'id'			=> $id,
										'city_name'  	=> $city_name,
										'city_code'   	=> $city_code,
										
									);
								}
							}
						}
					
					}

					
					
				
				$response['status']       = 1;
				$response['message']      = "Success";
				$response['data']         = $city_list;
				echo json_encode($response);
				return;
			}else if($method =='_listZone'){
				
				$manager_id    = $this->input->post('id');
				$state_id    = $this->input->post('state_id');
				$city_id    = $this->input->post('city_id');
				
					
					$where_mg = array(
					
						'employee_id' => $manager_id,
					);


					$mg_val = $this->managers_model->getManagers($where_mg);
					$ctrl_zone = !empty($mg_val[0]->ctrl_zone_id)?$mg_val[0]->ctrl_zone_id:'0';
					$ctrl_state = !empty($mg_val[0]->ctrl_state_id)?$mg_val[0]->ctrl_state_id:'0';
					$ctrl_city = !empty($mg_val[0]->ctrl_city_id)?$mg_val[0]->ctrl_city_id:'0';
					$position_id = !empty($mg_val[0]->position_id)?$mg_val[0]->position_id:'0';
					$designation_code = !empty($mg_val[0]->designation_code)?$mg_val[0]->designation_code:'0';

					$zone_list = array();

					if($designation_code=='SO' || $designation_code=='ASM' || $designation_code=='RSM'){
						$wer_c = array(
							'city_id'        => $city_id,
							'state_id'	     => $state_id,
							'published'      => '1'
						);
						
						$col_c ='name,id';

						$zt_val = $this->commom_model->getZone($wer_c, '', '', 'result', '', '', '', '', $col_c);

						if(!empty($zt_val)){
										
							foreach ($zt_val as $key => $value_2) {
								$id     = !empty($value_2->id)?$value_2->id:'';
								$zone_name     = !empty($value_2->name)?$value_2->name:'';
								
								
								$zone_list[] = array(
									'id'			=> $id,
									'zone_name'  	=> $zone_name,
									
								);
							}
						}

					}
					if($designation_code=='TSI'){
						if($ctrl_zone){
							$zone_id_finall = substr($ctrl_zone,1,-1);
					
							
							$d_zone = !empty($zone_id_finall)?$zone_id_finall:'';
							
							$d_zone_val = explode(',', $d_zone);
							$st_count = count($d_zone_val);
							
							$count_emp = [];
					
							$wer = array(
									
								'published'      => '1'
							);
							$wher_in['id']  = $d_zone_val;
							$col_m ='name,id';

							$mg_val = $this->commom_model->getZoneSecond($wer, '', '', 'result', '', '', '', '', $col_m,$wher_in);
						
							if(!empty($mg_val)){
									
								foreach ($mg_val as $key => $value) {
									$id     = !empty($value->id)?$value->id:'';
									$zone_name     = !empty($value->name)?$value->name:'';
									
									
									
									$zone_list[] = array(
										'id'			=> $id,
										'zone_name'  	=> $zone_name,
										
									);
								}
							}
						}
					
					}
					
					// if($position_id==3){
					// 	if($ctrl_state){
					// 		$state_id_finall = substr($ctrl_state,1,-1);
					
	
					// 		$d_state = !empty($state_id_finall)?$state_id_finall:'';
					
					// 		$d_state_val = explode(',', $d_state);
					// 		$st_count = count($d_state_val);
							
					// 		$count_emp = [];
						
					// 			$wer = array(
									
					// 				'published'      => '1'
					// 			);
					// 			$wher_in['id']  = $d_state_val;
					// 			$col_m ='state_name,state_code,id';
	
					// 			$mg_val = $this->commom_model->getState($wer, '', '', 'result', '', '', '', '', $col_m,$wher_in);
							
					// 			if(!empty($mg_val)){
										
					// 				foreach ($mg_val as $key => $value) {
					// 					$id     = !empty($value->id)?$value->id:'';
					// 					$state_name     = !empty($value->state_name)?$value->state_name:'';
					// 					$state_code     = !empty($value->state_code)?$value->state_code:'';
										
										
					// 					$state_list[] = array(
					// 						'id'			=> $id,
					// 						'state_name'  	=> $state_name,
					// 						'state_code'   	=> $state_code,
											
					// 					);
					// 				}
					// 			}
							
					// 	}
					
					// }

					// if($position_id==4){
					// 	if($ctrl_state){
					// 		$state_id_finall = substr($ctrl_state,1,-1);
					
	
					// 		$d_state = !empty($state_id_finall)?$state_id_finall:'';
					
					// 		$d_state_val = explode(',', $d_state);
					// 		$st_count = count($d_state_val);
							
					// 		$count_emp = [];
						
					// 			$wer = array(
									
					// 				'published'      => '1'
					// 			);
					// 			$wher_in['id']  = $d_state_val;
					// 			$col_m ='state_name,state_code,id';
	
					// 			$mg_val = $this->commom_model->getState($wer, '', '', 'result', '', '', '', '', $col_m,$wher_in);
							
					// 			if(!empty($mg_val)){
										
					// 				foreach ($mg_val as $key => $value) {
					// 					$id     = !empty($value->id)?$value->id:'';
					// 					$state_name     = !empty($value->state_name)?$value->state_name:'';
					// 					$state_code     = !empty($value->state_code)?$value->state_code:'';
										
										
					// 					$state_list[] = array(
					// 						'id'			=> $id,
					// 						'state_name'  	=> $state_name,
					// 						'state_code'   	=> $state_code,
											
					// 					);
					// 				}
					// 			}
							
					// 	}
					
					// }
					
				
				$response['status']       = 1;
				$response['message']      = "Success";
				$response['data']         = $zone_list;
				echo json_encode($response);
				return;
			}else {
					$response['status']  = 0;
					$response['message'] = "Not Found";
					$response['data']    = [];
					echo json_encode($response);
					return;
			}
		
							

						
						

						
			    	
		}
			
	}

	
		
    

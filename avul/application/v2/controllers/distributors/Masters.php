<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Masters extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function add_beat($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');
			$method   = $this->input->post('method');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$state_id  = $this->input->post('state_id');
				$city_id   = $this->input->post('city_id');
				$zone_name = $this->input->post('zone_name');
				$method    = $this->input->post('method');
				$log_id    = $this->session->userdata('id');
				$log_role  = 'Distributor';

				$required = array('state_id', 'city_id', 'zone_name');
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
			    	if($method == 'BTBM_X_C')
			    	{
		    			$where_1 = array(
			    			'state_id' => $state_id, 
			    			'method'   => '_detailState',
			    		);

			    		$dataVal_1  = avul_call(API_URL.'master/api/state',$where_1);
			    		$stateVal   = $dataVal_1['data'][0];

			    		$state_code = !empty($stateVal['state_code'])?$stateVal['state_code']:'';

			    		$where_2 = array(
			    			'city_id' => $city_id, 
			    			'method'   => '_detailCity',
			    		);

			    		$dataVal_2  = avul_call(API_URL.'master/api/city',$where_2);
			    		$cityVal    = $dataVal_2['data'][0];

			    		$city_code  = !empty($cityVal['city_code'])?$cityVal['city_code']:'';

			    		$zone_value = $state_code.'/'.$city_code.'/'.$zone_name;

			    		$data = array(
			    			'log_id'        => $log_id,
							'log_role'      => $log_role,
					    	'state_id'      => $state_id,
					    	'city_id'       => $city_id,
					    	'name'          => strtoupper($zone_value),
					    	'createdate'    => date('Y-m-d H:i:s'),
					    	'method'        => '_addZone',
					    );

					    $data_save = avul_call(API_URL.'master/api/zone',$data);

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
		    			$zone_id = $this->input->post('zone_id');
			    		$pstatus = $this->input->post('pstatus');

			    		$where_1 = array(
			    			'state_id' => $state_id, 
			    			'method'   => '_detailState',
			    		);

			    		$dataVal_1  = avul_call(API_URL.'master/api/state',$where_1);
			    		$stateVal   = $dataVal_1['data'][0];

			    		$state_code = !empty($stateVal['state_code'])?$stateVal['state_code']:'';

			    		$where_2 = array(
			    			'city_id' => $city_id, 
			    			'method'   => '_detailCity',
			    		);

			    		$dataVal_2  = avul_call(API_URL.'master/api/city',$where_2);
			    		$cityVal    = $dataVal_2['data'][0];

			    		$city_code  = !empty($cityVal['city_code'])?$cityVal['city_code']:'';

			    		$zone_value = $state_code.'/'.$city_code.'/'.$zone_name;

			    		$data = array(
			    			'log_id'   => $log_id,
							'log_role' => $log_role,
			    			'id'       => $zone_id,
					    	'state_id' => $state_id,
					    	'city_id'  => $city_id,
					    	'name'     => strtoupper($zone_value),
					    	'status'   => $pstatus,
					    	'method'   => '_updateZone'
					    );

					    $data_save = avul_call(API_URL.'master/api/zone',$data);

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

			else if($param1 =='getCity_name')
			{
				$state_id = $this->input->post('state_id');

				$where = array(
            		'state_id' => $state_id,
            		'method'   => '_listCity'
            	);

            	$city_list   = avul_call(API_URL.'master/api/city',$where);
            	$city_result = $city_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($city_result))
        		{
        			foreach ($city_result as $key => $value) {
        				$city_id   = !empty($value['city_id'])?$value['city_id']:'';
                        $city_name = !empty($value['city_name'])?$value['city_name']:'';

                        $option .= '<option value='.$city_id.'>'.$city_name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
			}

			else
			{
				if($param1 =='Edit')
				{
					$zone_id = !empty($param2)?$param2:'';

					$where_1 = array(
	            		'zone_id' => $zone_id,
	            		'method'  => '_detailZone'
	            	);

	            	$data_list  = avul_call(API_URL.'master/api/zone',$where_1);

	            	$where_2 = array(
	            		'method'   => '_listState'
	            	);

	            	$state_list  = avul_call(API_URL.'master/api/state',$where_2);

	            	$start_value = !empty($data_list['data'][0]['state_id'])?$data_list['data'][0]['state_id']:'';

	            	$where_3 = array(
	            		'state_id' => $start_value,
	            		'method'   => '_listCity'
	            	);

	            	$city_list = avul_call(API_URL.'master/api/city',$where_3);

					$page['dataval']    = $data_list['data'];
					$page['state_val']  = $state_list['data'];
					$page['city_val']   = $city_list['data'];
					$page['method']     = 'BTBM_X_U';
					$page['page_title'] = "Edit Beat";
				}
				else
				{
					$where_1 = array(
	            		'method'   => '_listState'
	            	);

	            	$state_list = avul_call(API_URL.'master/api/state',$where_1);

					$page['dataval']    = '';
					$page['state_val']  = $state_list['data'];
					$page['method']     = 'BTBM_X_C';
					$page['page_title'] = "Add Beat";
				}
				$page['main_heading'] = "Master";
				$page['sub_heading']  = "Beat";
				$page['pre_title']    = "List Beat";
				$page['page_access']  = "";
				$page['pre_menu']     = "index.php/distributors/masters/list_beat";
				$data['page_temp']    = $this->load->view('distributors/master/beat/add_beat',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_beat";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		public function list_beat($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Master";
				$page['sub_heading']  = "Beat";
				$page['page_title']   = "List Beat";
				$page['pre_title']    = "Add Beat";
				$page['page_access']  = "";
				$page['pre_menu']     = "index.php/distributors/masters/add_beat";
				$data['page_temp']    = $this->load->view('distributors/master/beat/list_beat',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_beat";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				$limit    = $this->input->post('limitval');
            	$page     = $this->input->post('page');
            	$search   = $this->input->post('search');
            	$cur_page = isset($page)?$page:'1';
            	$_offset  = ($cur_page-1) * $limit;
            	$nxt_page = $cur_page + 1;
            	$pre_page = $cur_page - 1;

            	$where = array(
            		'offset'  => $_offset,
            		'limit'   => $limit,
            		'search'  => $search,
            		'method'  => '_listZonePaginate'
            	);

            	$data_list  = avul_call(API_URL.'master/api/zone',$where);
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
	            		$zone_id       = !empty($value['zone_id'])?$value['zone_id']:'';
					    $state_name    = !empty($value['state_name'])?$value['state_name']:'';
					    $city_name     = !empty($value['city_name'])?$value['city_name']:'';
					    $zone_name     = !empty($value['zone_name'])?$value['zone_name']:'';
					    $active_status = !empty($value['status'])?$value['status']:'';
					    $createdate    = !empty($value['createdate'])?$value['createdate']:'';

					    if($active_status == '1')
		                {
		                	$status_view = '<span class="badge badge-success">Active</span>';
		                }
		                else
		                {
		                	$status_view = '<span class="badge badge-danger">In Active</span>';
		                }

		                $edit = '<a href="'.BASE_URL.'index.php/admin/masters/add_beat/Edit/'.$zone_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
			            
			            $delete = '<a data-row="'.$i.'" data-id="'.$zone_id.'" data-value="admin" data-cntrl="masters" data-func="list_beat" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';

					    $table .= '
					    	<tr class="row_'.$i.'">
                                <td class="line_height">'.$i.'</td>
                                <td class="line_height">'.$state_name.'</td>
                                <td class="line_height">'.$city_name.'</td>
                                <td class="line_height">'.$zone_name.'</td>
                                <td class="line_height">'.$status_view.'</td>';
	                            $table .= '<td>'.$edit.$delete.'</td>';
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

            	$response['status']     = $status;  
                $response['result']     = $table;  
                $response['message']    = $message;
                $response['next']       = $next;
                $response['prev']       = $prev;
                echo json_encode($response);
                return;
			}

			else if($param1 == 'delete')
			{
				$id       = $this->input->post('id');
				$log_id   = $this->session->userdata('id');
				$log_role = 'Distributor';

				if(!empty($id))	
				{
					$data = array(
						'log_id'   => $log_id,
						'log_role' => $log_role,
				    	'zone_id'  => $id,
				    	'method'   => '_deleteZone'
				    );

				    $data_save = avul_call(API_URL.'master/api/zone',$data);

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
	}
?>
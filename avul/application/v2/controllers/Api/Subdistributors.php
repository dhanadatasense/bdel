<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class Subdistributors extends CI_Controller {

		public function __construct()
		{
			parent::__construct();

			$this->load->model('commom_model');
			$this->load->model('distributors_model');
			$this->load->model('assignproduct_model');
		}

		public function index()
		{
			echo "Test";
		}

		// sub-distributors details
		// ***************************************************
		public function initial_details($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_getDetails')
			{
				$distributor_id = $this->input->post('distributor_id');				

				if(!empty($distributor_id))
				{
                   // state id
                    $where = array(
						'id'        => $distributor_id,
						'status'                => '1',
					);
                    $col = 'state_id';

					$st_id = $this->distributors_model->getDistributors($where, '', '', 'result', '', '', '', '', $col);
                    $state_id = !empty($st_id[0]->state_id)?$st_id[0]->state_id:'';
                   
                   
                    // State name
                    $where_1 = array('id' => $state_id);
                    $col_1 ='state_name';

                    $st_name = $this->commom_model->getState($where_1, '', '', 'result', '', '', '', '', $col_1);
                    $state_name = !empty($st_name[0]->state_name)?$st_name[0]->state_name:'';

                    $state_val = [];
                    $state_val[] = array(
                        'state_id'    => $state_id,
                        'state_name'  => $state_name,
                          
                      );
                 

                        

			            

		            	$response['status']  = 1;
				        $response['message'] = "Success";
                        $response['state_val'] =$state_val;
                        
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
			else
			{
				$response['status']  = 0;
			    $response['message'] = "Please fill all required fields"; 
			    $response['data']    = [];
			    echo json_encode($response);
			    return;
			}
		}
			// city
		// ***************************************************
		public function city($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addCity')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('state_id', 'name', 'city_code');
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
			    	$state_id   = $this->input->post('state_id');
			    	$name       = $this->input->post('name');
			    	$city_code  = $this->input->post('city_code');
			    	$log_id     = $this->input->post('log_id');
					$log_role   = $this->input->post('log_role');

			    	$where=array(
			    		'state_id'  => $state_id,
				    	'city_name' => ucfirst($name),
				    	'city_code' => strtoupper($city_code),
				    	'status'    => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getCity($where, '', '', 'result', '', '', '', '', $column);

					if(!empty($overalldatas))
					{
						$response['status']  = 0;
				        $response['message'] = "Data Already Exist"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
					}
					else
					{
			    		$data=array(
			    			'state_id'   => $state_id,
					    	'city_name'  => ucfirst($name),
					    	'city_code' => strtoupper($city_code),
					    	'createdate' => date('Y-m-d H:i:s')
					    );

					    $insert = $this->commom_model->city_insert($data);

					    $log_data = array(
					    	'u_id'       => $log_id,
					    	'role'       => $log_role,
					    	'table'      => 'tbl_city',
					    	'auto_id'    => $insert,
					    	'action'     => 'create',
					    	'date'       => date('Y-m-d'),
					    	'time'       => date('H:i:s'),
					    	'createdate' => date('Y-m-d H:i:s')
					    );

					    $log_val = $this->commom_model->log_insert($log_data);

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
					}
			    }
			}

			else if($method == '_listCityPaginate')
			{
				$limit  = $this->input->post('limit');
	    		$offset = $this->input->post('offset');

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
	    			$where = array('published'=>'1');
	    		}
	    		else
	    		{
	    			$like = [];
	    			$where = array('published'=>'1');
	    		}

	    		$column = 'id';
				$overalldatas = $this->commom_model->getCity($where, '', '', 'result', $like, '', '', '', $column);

				if($overalldatas)
				{
					$totalc = count($overalldatas);
				}
				else
				{
					$totalc = 0;
				}

				$option['order_by']   = 'id';
				$option['disp_order'] = 'ASC';

				$data_list = $this->commom_model->getCity($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$city_list = [];

					foreach ($data_list as $key => $value) {

						$city_id    = isset($value->id)?$value->id:'';
					    $state_id   = isset($value->state_id)?$value->state_id:'';
					    $city_name  = isset($value->city_name)?$value->city_name:'';
					    $city_code  = isset($value->city_code)?$value->city_code:'';
					    $published  = isset($value->published)?$value->published:'';
					    $status     = isset($value->status)?$value->status:'';
					    $createdate = isset($value->createdate)?$value->createdate:'';

					    $where_1    = array('id'=>$state_id);
				    	$data_val   = $this->commom_model->getState($where_1);

				    	$state_name = isset($data_val[0]->state_name)?$data_val[0]->state_name:'';

			            $city_list[] = array(
          					'city_id'    => $city_id,
						    'state_id'   => $state_id,
						    'state_name' => $state_name,
						    'city_name'  => $city_name,
						    'city_code'  => $city_code,
						    'published'  => $published,
						    'status'     => $status,
						    'createdate' => $createdate,
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
			        $response['data']         = $city_list;
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

			else if($method == '_detailCity')
			{
				$city_id = $this->input->post('city_id');

				if(!empty($city_id))
				{
		    		$where  = array('id' => $city_id);
				    $data = $this->commom_model->getCity($where);
				    if($data)
				    {

				    	$city_list = [];
						foreach ($data as $key => $value) {

							$city_id    = isset($value->id)?$value->id:'';
						    $state_id   = isset($value->state_id)?$value->state_id:'';
						    $city_name  = isset($value->city_name)?$value->city_name:'';
						    $city_code  = isset($value->city_code)?$value->city_code:'';
						    $published  = isset($value->published)?$value->published:'';
						    $status     = isset($value->status)?$value->status:'';
						    $createdate = isset($value->createdate)?$value->createdate:'';

				            $city_list[] = array(
	          					'city_id'    => $city_id,
							    'state_id'   => $state_id,
							    'city_name'  => $city_name,
							    'city_code'  => $city_code,
							    'published'  => $published,
							    'status'     => $status,
							    'createdate' => $createdate,
	          				);
						}

	        			$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $city_list;
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

			else if($method == '_listCity')
			{
				$state_id = $this->input->post('state_id');

				if(!empty($state_id))
				{
					$where = array('state_id' => $state_id, 'status' => '1', 'published' => '1');

					$data_list = $this->commom_model->getCity($where);

					if($data_list)
					{
						$city_list = [];

						foreach ($data_list as $key => $value) {

							$city_id    = isset($value->id)?$value->id:'';
							$state_id   = isset($value->state_id)?$value->state_id:'';
							$city_name  = isset($value->city_name)?$value->city_name:'';
							$city_code  = isset($value->city_code)?$value->city_code:'';
				            $published  = isset($value->published)?$value->published:'';
				            $status     = isset($value->status)?$value->status:'';
				            $createdate = isset($value->createdate)?$value->createdate:'';

				            $city_list[] = array(
	          					'city_id'    => $city_id,
							    'state_id'   => $state_id,
							    'city_name'  => $city_name,
							    'city_code'  => $city_code,
							    'published'  => $published,
							    'status'     => $status,
							    'createdate' => $createdate,
	          				);
						}

						$response['status']       = 1;
				        $response['message']      = "Success"; 
				        $response['data']         = $city_list;
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
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}

			else if($method == '_updateCity')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('id', 'state_id', 'name');
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
			    	$city_id  = $this->input->post('id');
			    	$state_id = $this->input->post('state_id');
			    	$name     = $this->input->post('name');
			    	$status   = $this->input->post('status');
			    	$log_id   = $this->input->post('log_id');
					$log_role = $this->input->post('log_role');

			    	$where=array(
			    		'id !='     => $city_id,
			    		'state_id'  => $state_id,
				    	'city_name' => ucfirst($name),
				    	'status'    => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getCity($where, '', '', 'result', '', '', '', '', $column);

					if(!empty($overalldatas))
					{
						$response['status']  = 0;
				        $response['message'] = "Data Already Exist"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
					}
					else
					{
						$data=array(
			    			'state_id'   => $state_id,
					    	'city_name'  => ucfirst($name),
					    	'status'     => $status,
					    	'updatedate' => date('Y-m-d H:i:s')
					    );

			    		$where  = array('id' => $city_id);
					    $update = $this->commom_model->city_update($data, $where);

					    $log_data = array(
					    	'u_id'       => $log_id,
					    	'role'       => $log_role,
					    	'table'      => 'tbl_city',
					    	'auto_id'    => $city_id,
					    	'action'     => 'update',
					    	'date'       => date('Y-m-d'),
					    	'time'       => date('H:i:s'),
					    	'createdate' => date('Y-m-d H:i:s')
					    );

					    $log_val = $this->commom_model->log_insert($log_data);

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
			}

			else if($method == '_deleteCity')
			{
				$city_id  = $this->input->post('city_id');
				$log_id   = $this->input->post('log_id');
				$log_role = $this->input->post('log_role');

				if(!empty($city_id))
				{
					$data = array(
				    	'published' => '0',
				    );

		    		$where  = array('id' => $city_id);
				    $update = $this->commom_model->city_delete($data, $where);

				    $log_data = array(
				    	'u_id'       => $log_id,
				    	'role'       => $log_role,
				    	'table'      => 'tbl_city',
				    	'auto_id'    => $city_id,
				    	'action'     => 'delete',
				    	'date'       => date('Y-m-d'),
				    	'time'       => date('H:i:s'),
				    	'createdate' => date('Y-m-d H:i:s')
				    );

				    $log_val = $this->commom_model->log_insert($log_data);

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

				else
				{
					$response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}

			else
			{
				$response['status']  = 0;
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}
			
	 }
    
?>
<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class Master extends CI_Controller {

		public function __construct()
		{
			parent::__construct();

			$this->load->model('commom_model');
		}

		public function index()
		{
			echo "Test";
		}

		// financial
		// ***************************************************
		public function financial($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addFinancial')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('name', 'start_date', 'end_date');
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
			    	$log_id     = $this->input->post('log_id');
			    	$log_role   = $this->input->post('log_role');
			    	$name       = $this->input->post('name');
			    	$start_date = $this->input->post('start_date');
			    	$end_date   = $this->input->post('end_date');

					$where=array(
				    	'name'   => $name,
				    	'status' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getfinancial($where, '', '', 'result', '', '', '', '', $column);

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
					    	'name'       => $name,
					    	'start_date' => date('Y-m-d', strtotime($start_date)),
					    	'end_date'   => date('Y-m-d', strtotime($end_date)),
					    	'createdate' => date('Y-m-d H:i:s')
					    );

					    $insert=$this->commom_model->financial_insert($data);

					    $log_data = array(
					    	'u_id'       => $log_id,
					    	'role'       => $log_role,
					    	'table'      => 'tbl_financial',
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

			else if($method == '_listFinancialPaginate')
			{
				$limit  = $this->input->post('limit');
	    		$offset = $this->input->post('offset');
				$sort_column = $this->input->post('sort_column');
	            $sort_type   = $this->input->post('sort_type');

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

				$sort_col_ = array('id', 'name', 'start_date', 'end_date', 'status');
	    		array_unshift($sort_col_,"");
				unset($sort_col_[0]);

	    		$option['order_by']   = !empty($sort_column) ? $sort_col_[$sort_column] : 'id';
	    		$option['disp_order'] = !empty($sort_type) ? ($sort_type==1? 'DESC' : 'ASC') : 'DESC';

	    		$where       = array('published' => '1');

	    		$column = 'id';
				$overalldatas = $this->commom_model->getfinancial($where, '', '', 'result', $like, '', '', '', $column);
				
				if($overalldatas)
				{
					$totalc = count($overalldatas);
				}
				else
				{
					$totalc = 0;
				}

				// $option['order_by']   = 'id';
				// $option['disp_order'] = 'DESC';

				$data_list = $this->commom_model->getfinancial($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$financial_list = [];

					foreach ($data_list as $key => $value) {

						$financial_id   = isset($value->id)?$value->id:'';
						$financial_name = isset($value->name)?$value->name:'';
			            $start_date     = isset($value->start_date)?$value->start_date:'';
			            $end_date       = isset($value->end_date)?$value->end_date:'';
			            $published      = isset($value->published)?$value->published:'';
			            $status         = isset($value->status)?$value->status:'';
			            $createdate     = isset($value->createdate)?$value->createdate:'';

			            $financial_list[] = array(
          					'financial_id'   => $financial_id,
          					'financial_name' => $financial_name,
				            'start_date'     => date('d-m-Y', strtotime($start_date)),
				            'end_date'       => date('d-m-Y', strtotime($end_date)),
				            'published'      => $published,
				            'status'         => $status,
				            'createdate'     => $createdate,
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
			        $response['data']         = $financial_list;
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

			else if($method == '_detailFinancial')
			{
				$financial_id = $this->input->post('financial_id');

		    	if(!empty($financial_id))
		    	{

		    		$where = array('id'=>$financial_id);
				    $data  = $this->commom_model->getfinancial($where);
				    if($data)
				    {	

				    	$financial_list = [];

						foreach ($data as $key => $value) {

							$financial_id   = isset($value->id)?$value->id:'';
							$financial_name = isset($value->name)?$value->name:'';
				            $start_date     = isset($value->start_date)?$value->start_date:'';
				            $end_date       = isset($value->end_date)?$value->end_date:'';
				            $published      = isset($value->published)?$value->published:'';
				            $status         = isset($value->status)?$value->status:'';
				            $createdate     = isset($value->createdate)?$value->createdate:'';

				            $financial_list[] = array(
	          					'financial_id'   => $financial_id,
	          					'financial_name' => $financial_name,
					            'start_date'     => date('d-m-Y', strtotime($start_date)),
					            'end_date'       => date('d-m-Y', strtotime($end_date)),
					            'published'      => $published,
					            'status'         => $status,
					            'createdate'     => $createdate,
	          				);
						}

	        			$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $financial_list;
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

			else if($method == '_listFinancial')
			{
				$option['order_by']   = 'id';
				$option['disp_order'] = 'DESC';

				$where = array('status'=>'1', 'published'=>'1');

				$data_list = $this->commom_model->getfinancial($where, '', '', 'result', '', '', $option);

				if($data_list)
				{
					$financial_list = [];

					foreach ($data_list as $key => $value) {

						$financial_id   = isset($value->id)?$value->id:'';
						$financial_name = isset($value->name)?$value->name:'';
			            $start_date     = isset($value->start_date)?$value->start_date:'';
			            $end_date       = isset($value->end_date)?$value->end_date:'';
			            $published      = isset($value->published)?$value->published:'';
			            $status         = isset($value->status)?$value->status:'';
			            $createdate     = isset($value->createdate)?$value->createdate:'';

			            $financial_list[] = array(
          					'financial_id'   => $financial_id,
          					'financial_name' => $financial_name,
				            'start_date'     => date('d-m-Y', strtotime($start_date)),
				            'end_date'       => date('d-m-Y', strtotime($end_date)),
				            'published'      => $published,
				            'status'         => $status,
				            'createdate'     => $createdate,
          				);
					}

        			$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $financial_list;
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

			else if($method == '_updateFinancial')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('financial_id', 'name', 'start_date', 'end_date');
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
			    	$log_id       = $this->input->post('log_id');
			    	$log_role     = $this->input->post('log_role');
			    	$financial_id = $this->input->post('financial_id');
			    	$name         = $this->input->post('name');
			    	$start_date   = $this->input->post('start_date');
			    	$end_date     = $this->input->post('end_date');
			    	$status       = $this->input->post('status');

			    	$where=array(
			    		'id !='  => $financial_id,
				    	'name'   => $name,
				    	'status' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getfinancial($where, '', '', 'result', '', '', '', '', $column);

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
						$data = array(
					    	'name'       => $name,
					    	'start_date' => date('Y-m-d', strtotime($start_date)),
					    	'end_date'   => date('Y-m-d', strtotime($end_date)),
					    	'status'     => $status,
					    	'updatedate' => date('Y-m-d H:i:s')
					    );

					    $update_id = array('id'=>$financial_id);

					    $update=$this->commom_model->financial_update($data, $update_id);

					    $log_data = array(
					    	'u_id'       => $log_id,
					    	'role'       => $log_role,
					    	'table'      => 'tbl_financial',
					    	'auto_id'    => $financial_id,
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

			else if($method == '_deleteFinancial')
			{
				$log_id       = $this->input->post('log_id');
			    $log_role     = $this->input->post('log_role');
		    	$financial_id = $this->input->post('financial_id');

		    	if(!empty($financial_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

		    		$where  = array('id'=>$financial_id);
				    $update = $this->commom_model->financial_delete($data, $where);

				    $log_data = array(
				    	'u_id'       => $log_id,
				    	'role'       => $log_role,
				    	'table'      => 'tbl_financial',
				    	'auto_id'    => $financial_id,
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

		// state
		// ***************************************************
		public function state($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addState')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('name', 'state_code', 'gst_code');
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
			    	$name       = $this->input->post('name');
			    	$state_code = $this->input->post('state_code');
			    	$gst_code   = $this->input->post('gst_code');

			    	$where=array(
				    	'state_name' => ucfirst($name),
				    	'state_code' => strtoupper($state_code),
				    	'status'     => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getState($where, '', '', 'result', '', '', '', '', $column);

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
						$log_id     = $this->input->post('log_id');
						$log_role   = $this->input->post('log_role');

						$data=array(
					    	'state_name' => ucfirst($name),
				    		'state_code' => strtoupper($state_code),
				    		'gst_code'   => $gst_code,
					    	'createdate' => date('Y-m-d H:i:s')
					    );

					    $insert=$this->commom_model->state_insert($data);

					    $log_data = array(
					    	'u_id'       => $log_id,
					    	'role'       => $log_role,
					    	'table'      => 'tbl_state',
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

			else if($method == '_listStatePaginate')
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
				$overalldatas = $this->commom_model->getState($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->commom_model->getState($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$state_list = [];

					foreach ($data_list as $key => $value) {

						$state_id   = isset($value->id)?$value->id:'';
			            $country_id = isset($value->country_id)?$value->country_id:'';
			            $state_name = isset($value->state_name)?$value->state_name:'';
			            $state_code = isset($value->state_code)?$value->state_code:'';
			            $gst_code   = isset($value->gst_code)?$value->gst_code:'';
			            $published  = isset($value->published)?$value->published:'';
			            $status     = isset($value->status)?$value->status:'';
			            $createdate = isset($value->createdate)?$value->createdate:'';

			            $state_list[] = array(
          					'state_id'   => $state_id,
				            'country_id' => $country_id,
				            'state_name' => $state_name,
				            'state_code' => $state_code,
				            'gst_code'   => $gst_code,
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
			        $response['data']         = $state_list;
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

			else if($method == '_detailState')
			{
				$state_id = $this->input->post('state_id');

		    	if(!empty($state_id))
		    	{

		    		$where = array('id'=>$state_id);
				    $data  = $this->commom_model->getState($where);
				    if($data)
				    {	

				    	$state_list = [];

						foreach ($data as $key => $value) {

							$state_id   = isset($value->id)?$value->id:'';
				            $country_id = isset($value->country_id)?$value->country_id:'';
				            $state_name = isset($value->state_name)?$value->state_name:'';
				            $state_code = isset($value->state_code)?$value->state_code:'';
				            $gst_code   = isset($value->gst_code)?$value->gst_code:'';
				            $published  = isset($value->published)?$value->published:'';
				            $status     = isset($value->status)?$value->status:'';
				            $createdate = isset($value->createdate)?$value->createdate:'';

				            $state_list[] = array(
	          					'state_id'   => $state_id,
					            'country_id' => $country_id,
					            'state_name' => $state_name,
					            'state_code' => $state_code,
					            'gst_code'   => $gst_code,
					            'published'  => $published,
					            'status'     => $status,
					            'createdate' => $createdate,
	          				);
						}

	        			$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $state_list;
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

			else if($method == '_listState')
			{
				$where = array('status'=>'1', 'published'=>'1');

				$data_list = $this->commom_model->getState($where);

				if($data_list)
				{
					$state_list = [];

					foreach ($data_list as $key => $value) {

						$state_id   = isset($value->id)?$value->id:'';
			            $country_id = isset($value->country_id)?$value->country_id:'';
			            $state_name = isset($value->state_name)?$value->state_name:'';
			            $state_code = isset($value->state_code)?$value->state_code:'';
			            $gst_code   = isset($value->gst_code)?$value->gst_code:'';
			            $published  = isset($value->published)?$value->published:'';
			            $status     = isset($value->status)?$value->status:'';
			            $createdate = isset($value->createdate)?$value->createdate:'';

			            $state_list[] = array(
          					'state_id'   => $state_id,
				            'country_id' => $country_id,
				            'state_name' => $state_name,
				            'state_code' => $state_code,
				            'gst_code'   => $gst_code,
				            'published'  => $published,
				            'status'     => $status,
				            'createdate' => $createdate,
          				);
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $state_list;
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

			else if($method == '_updateState')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('id', 'name');
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
			    	$state_id   = $this->input->post('id');
			    	$name       = $this->input->post('name');
			    	$state_code = $this->input->post('state_code');
			    	$gst_code   = $this->input->post('gst_code');
			    	$status     = $this->input->post('status');
			    	$log_id     = $this->input->post('log_id');
					$log_role   = $this->input->post('log_role');

			    	$where=array(
			    		'id !='      => $state_id,
				    	'state_name' => ucfirst($name),
				    	'state_code' => strtoupper($state_code),
				    	'status'     => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getState($where, '', '', 'result', '', '', '', '', $column);

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
					    	'state_name' => ucfirst($name),
				    		'state_code' => strtoupper($state_code),
				    		'gst_code'   => $gst_code,
					    	'status'     => $status,
					    	'updatedate' => date('Y-m-d H:i:s')
					    );

			    		$update_id  = array('id'=>$state_id);
					    $update = $this->commom_model->state_update($data, $update_id);

					    $log_data = array(
					    	'u_id'       => $log_id,
					    	'role'       => $log_role,
					    	'table'      => 'tbl_state',
					    	'auto_id'    => $state_id,
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

			else if($method == '_deleteState')
			{	
		    	$state_id = $this->input->post('state_id');
		    	$log_id   = $this->input->post('log_id');
				$log_role = $this->input->post('log_role');

		    	if(!empty($state_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

		    		$where  = array('id'=>$state_id);
				    $update = $this->commom_model->state_delete($data, $where);

				    $log_data = array(
				    	'u_id'       => $log_id,
				    	'role'       => $log_role,
				    	'table'      => 'tbl_state',
				    	'auto_id'    => $state_id,
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

		// zone
		// ***************************************************
		public function zone($param1="",$param2="",$param3="")
		{
			$method   = $this->input->post('method');
			$log_id   = $this->input->post('log_id');
			$log_role = $this->input->post('log_role');

			if($method == '_addZone')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('state_id', 'city_id', 'name');
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
			    	$state_id = $this->input->post('state_id');
			    	$city_id  = $this->input->post('city_id');
			    	$name     = $this->input->post('name');

			    	$where=array(
			    		'state_id' => $state_id,
			    		'city_id'  => $city_id,
				    	'name'     => $name,
				    	'status'   => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getZone($where, '', '', 'result', '', '', '', '', $column);

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
					    	'city_id'    => $city_id,
				    		'name'       => strtoupper($name),
					    	'createdate' => date('Y-m-d H:i:s')
					    );

					    $insert=$this->commom_model->zone_insert($data);

					    $log_data = array(
					    	'u_id'       => $log_id,
					    	'role'       => $log_role,
					    	'table'      => 'tbl_zone',
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

			else if($method == '_listZonePaginate')
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
				$overalldatas = $this->commom_model->getZone($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->commom_model->getZone($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$zone_list = [];

					foreach ($data_list as $key => $value) {

						$zone_id    = isset($value->id)?$value->id:'';
					    $state_id   = isset($value->state_id)?$value->state_id:'';
					    $city_id    = isset($value->city_id)?$value->city_id:'';
					    $zone_name  = isset($value->name)?$value->name:'';
					    $published  = isset($value->published)?$value->published:'';
					    $status     = isset($value->status)?$value->status:'';
					    $createdate = isset($value->createdate)?$value->createdate:'';

					    $where_1    = array('id'=>$state_id);
				    	$dataVal_1  = $this->commom_model->getState($where_1);

				    	$state_name = isset($dataVal_1[0]->state_name)?$dataVal_1[0]->state_name:'';

				    	$where_2    = array('id'=>$city_id);
				    	$dataVal_2  = $this->commom_model->getCity($where_2);

				    	$city_name  = isset($dataVal_2[0]->city_name)?$dataVal_2[0]->city_name:'';

			            $zone_list[] = array(
          					'zone_id'    => $zone_id,
          					'zone_name'  => $zone_name,
						    'state_id'   => $state_id,
						    'state_name' => $state_name,
						    'city_id'    => $city_id,
						    'city_name'  => $city_name,
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
			        $response['data']         = $zone_list;
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

			else if($method == '_detailZone')
			{
				$zone_id = $this->input->post('zone_id');

				if(!empty($zone_id))
				{
		    		$where  = array('id' => $zone_id);
				    $data = $this->commom_model->getZone($where);
				    if($data)
				    {

				    	$zone_list = [];
						foreach ($data as $key => $value) {

							$zone_id    = isset($value->id)?$value->id:'';
						    $state_id   = isset($value->state_id)?$value->state_id:'';
						    $city_id    = isset($value->city_id)?$value->city_id:'';
						    $zone_name  = isset($value->name)?$value->name:'';
						    $published  = isset($value->published)?$value->published:'';
						    $status     = isset($value->status)?$value->status:'';
						    $createdate = isset($value->createdate)?$value->createdate:'';

				            $zone_list[] = array(
	          					'zone_id'    => $zone_id,
							    'state_id'   => $state_id,
							    'city_id'    => $city_id,
							    'zone_name'  => $zone_name,
							    'published'  => $published,
							    'status'     => $status,
							    'createdate' => $createdate,
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

			else if($method == '_listZone')
			{

				$error = FALSE;
			    $errors = array();
				$required = array('state_id', 'city_id');
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
			    	$state_id = $this->input->post('state_id');
			    	$city_id  = $this->input->post('city_id');

			    	$where = array(
			    		'state_id'  => $state_id, 
			    		'city_id'   => $city_id, 
			    		'status'    => '1', 
			    		'published' => '1'
			    	);

					$data_list = $this->commom_model->getZoneImplode($where);

					if($data_list)
					{
						$zone_list = [];

						foreach ($data_list as $key => $value) {

							$zone_id    = isset($value->id)?$value->id:'';
							$state_id   = isset($value->state_id)?$value->state_id:'';
							$city_id    = isset($value->city_id)?$value->city_id:'';
							$zone_name  = isset($value->name)?$value->name:'';
				            $published  = isset($value->published)?$value->published:'';
				            $status     = isset($value->status)?$value->status:'';
				            $createdate = isset($value->createdate)?$value->createdate:'';

				            $zone_list[] = array(
	          					'zone_id'    => $zone_id,
							    'state_id'   => $state_id,
							    'city_id'    => $city_id,
							    'zone_name'  => $zone_name,
							    'published'  => $published,
							    'status'     => $status,
							    'createdate' => $createdate,
	          				);
						}

						$response['status']       = 1;
				        $response['message']      = "Success"; 
				        $response['data']         = $zone_list;
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

			else if($method == '_overallZone')
			{
		    	$where = array('status' => '1', 'published' => '1');

				$data_list = $this->commom_model->getZone($where);

				if($data_list)
				{
					$zone_list = [];

					foreach ($data_list as $key => $value) {

						$zone_id    = isset($value->id)?$value->id:'';
						$state_id   = isset($value->state_id)?$value->state_id:'';
						$city_id    = isset($value->city_id)?$value->city_id:'';
						$zone_name  = isset($value->name)?$value->name:'';
			            $published  = isset($value->published)?$value->published:'';
			            $status     = isset($value->status)?$value->status:'';
			            $createdate = isset($value->createdate)?$value->createdate:'';

			            $zone_list[] = array(
          					'zone_id'    => $zone_id,
						    'state_id'   => $state_id,
						    'city_id'    => $city_id,
						    'zone_name'  => $zone_name,
						    'published'  => $published,
						    'status'     => $status,
						    'createdate' => $createdate,
          				);
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $zone_list;
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

			else if($method == '_updateZone')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('id' ,'state_id', 'city_id', 'name');
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
			    	$zone_id  = $this->input->post('id');
			    	$state_id = $this->input->post('state_id');
			    	$city_id  = $this->input->post('city_id');
			    	$name     = $this->input->post('name');
			    	$status   = $this->input->post('status');

			    	$where=array(
			    		'id !='     => $zone_id,
			    		'state_id'  => $state_id,
			    		'city_id'   => $city_id,
				    	'name'      => strtoupper($name),
				    	'status'    => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getZone($where, '', '', 'result', '', '', '', '', $column);

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
					    	'city_id'    => $city_id,
				    		'name'       => strtoupper($name),
					    	'status'     => $status,
					    	'updatedate' => date('Y-m-d H:i:s')
					    );

			    		$where  = array('id' => $zone_id);
					    $update = $this->commom_model->zone_update($data, $where);

					    $log_data = array(
					    	'u_id'       => $log_id,
					    	'role'       => $log_role,
					    	'table'      => 'tbl_zone',
					    	'auto_id'    => $zone_id,
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

			else if($method == '_deleteZone')
			{
				$zone_id = $this->input->post('zone_id');

				if(!empty($zone_id))
				{
					$data = array(
				    	'published' => '0',
				    );

		    		$where  = array('id' => $zone_id);
				    $update = $this->commom_model->zone_delete($data, $where);

				    $log_data = array(
				    	'u_id'       => $log_id,
				    	'role'       => $log_role,
				    	'table'      => 'tbl_zone',
				    	'auto_id'    => $zone_id,
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

		// unit
		// ***************************************************
		public function unit($param1="",$param2="",$param3="")
		{
			$method   = $this->input->post('method');
			$log_id   = $this->input->post('log_id');
			$log_role = $this->input->post('log_role');

			if($method == '_addUnit')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('name');
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
			    	$name   = $this->input->post('name');

			    	$where=array(
				    	'name'   => $name,
				    	'status' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getUnit($where, '', '', 'result', '', '', '', '', $column);

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
					    	'name'       => $name,
					    	'createdate' => date('Y-m-d H:i:s')
					    );

					    $insert=$this->commom_model->unit_insert($data);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_unit',
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

			else if($method == '_listUnitPaginate')
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
				$overalldatas = $this->commom_model->getUnit($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->commom_model->getUnit($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$unit_list = [];

					foreach ($data_list as $key => $value) {

						$unit_id    = isset($value->id)?$value->id:'';
			            $unit_name  = isset($value->name)?$value->name:'';
			            $published  = isset($value->published)?$value->published:'';
			            $status     = isset($value->status)?$value->status:'';
			            $createdate = isset($value->createdate)?$value->createdate:'';

			            $unit_list[] = array(
	      					'unit_id'    => $unit_id,
				            'unit_name'  => $unit_name,
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
			        $response['data']         = $unit_list;
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

			else if($method == '_detailUnit')
			{
				$unit_id = $this->input->post('unit_id');

		    	if(!empty($unit_id))
		    	{

		    		$where = array('id'=>$unit_id);
				    $data  = $this->commom_model->getUnit($where);
				    if($data)
				    {	

				    	$unit_list = [];

						foreach ($data as $key => $value) {

							$unit_id    = isset($value->id)?$value->id:'';
				            $unit_name  = isset($value->name)?$value->name:'';
				            $published  = isset($value->published)?$value->published:'';
				            $status     = isset($value->status)?$value->status:'';
				            $createdate = isset($value->createdate)?$value->createdate:'';

				            $unit_list[] = array(
	          					'unit_id'   => $unit_id,
					            'unit_name' => $unit_name,
					            'published'  => $published,
					            'status'     => $status,
					            'createdate' => $createdate,
	          				);
						}

	        			$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $unit_list;
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

			else if($method == '_listUnit')
			{
				$where = array('status'=>'1', 'published'=>'1');

				$data_list = $this->commom_model->getUnit($where);

				if($data_list)
				{
					$unit_list = [];

					foreach ($data_list as $key => $value) {

						$unit_id    = isset($value->id)?$value->id:'';
			            $unit_name  = isset($value->name)?$value->name:'';
			            $published  = isset($value->published)?$value->published:'';
			            $status     = isset($value->status)?$value->status:'';
			            $createdate = isset($value->createdate)?$value->createdate:'';

			            $unit_list[] = array(
	      					'unit_id'    => $unit_id,
				            'unit_name'  => $unit_name,
				            'published'  => $published,
				            'status'     => $status,
				            'createdate' => $createdate,
	      				);
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $unit_list;
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

			else if($method == '_updateUnit')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('id', 'name');
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
			    	$unit_id  = $this->input->post('id');
			    	$name     = $this->input->post('name');
			    	$status   = $this->input->post('status');

			    	$where=array(
			    		'id !='  => $unit_id,
				    	'name'   => $name,
				    	'status' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getUnit($where, '', '', 'result', '', '', '', '', $column);

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
					    	'name'       => $name,
					    	'status'     => $status,
					    	'updatedate' => date('Y-m-d H:i:s')
					    );

			    		$update_id = array('id'=>$unit_id);
					    $update    = $this->commom_model->unit_update($data, $update_id);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_unit',
							'auto_id'    => $unit_id,
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

			else if($method == '_deleteUnit')
			{	
		    	$unit_id = $this->input->post('unit_id');

		    	if(!empty($unit_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

		    		$where  = array('id'=>$unit_id);
				    $update = $this->commom_model->unit_delete($data, $where);

				    $log_data = array(
						'u_id'       => $log_id,
						'role'       => $log_role,
						'table'      => 'tbl_unit',
						'auto_id'    => $unit_id,
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

		// variation
		// ***************************************************
		public function variation($param1="",$param2="",$param3="")
		{
			$method   = $this->input->post('method');
			$log_id   = $this->input->post('log_id');
			$log_role = $this->input->post('log_role');

			if($method == '_addVariation')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('name');
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
			    	$name   = $this->input->post('name');

			    	$where=array(
				    	'name'   => ucfirst($name),
				    	'status' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getVariation($where, '', '', 'result', '', '', '', '', $column);

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
					    	'name'       => ucfirst($name),
					    	'createdate' => date('Y-m-d H:i:s')
					    );

					    $insert=$this->commom_model->variation_insert($data);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_variation',
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

			else if($method == '_listVariationPaginate')
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
				$overalldatas = $this->commom_model->getVariation($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->commom_model->getVariation($where, $limit, $offset, 'result', $like, '', $option);
				if($data_list)
				{
					$variation_list = [];

					foreach ($data_list as $key => $value) {

						$variation_id   = isset($value->id)?$value->id:'';
			            $variation_name = isset($value->name)?$value->name:'';
			            $published      = isset($value->published)?$value->published:'';
			            $status         = isset($value->status)?$value->status:'';
			            $createdate     = isset($value->createdate)?$value->createdate:'';

			            $variation_list[] = array(
	      					'variation_id'    => $variation_id,
				            'variation_name'  => $variation_name,
				            'published'       => $published,
				            'status'          => $status,
				            'createdate'      => $createdate,
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
			        $response['data']         = $variation_list;
			        echo json_encode($response);
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

			else if($method == '_detailVariation')
			{
				$variation_id = $this->input->post('variation_id');

		    	if(!empty($variation_id))
		    	{

		    		$where = array('id'=>$variation_id);
				    $data  = $this->commom_model->getVariation($where);
				    if($data)
				    {	

				    	$variation_list = [];

						foreach ($data as $key => $value) {

							$variation_id   = isset($value->id)?$value->id:'';
				            $variation_name = isset($value->name)?$value->name:'';
				            $published      = isset($value->published)?$value->published:'';
				            $status         = isset($value->status)?$value->status:'';
				            $createdate     = isset($value->createdate)?$value->createdate:'';

				            $variation_list[] = array(
	          					'variation_id'   => $variation_id,
					            'variation_name' => $variation_name,
					            'published'      => $published,
					            'status'         => $status,
					            'createdate'     => $createdate,
	          				);
						}

	        			$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $variation_list;
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

			else if($method == '_listVariation')
			{
				$where = array('status'=>'1', 'published'=>'1');

				$data_list = $this->commom_model->getVariation($where);

				if($data_list)
				{
					$variation_list = [];

					foreach ($data_list as $key => $value) {

						$variation_id    = isset($value->id)?$value->id:'';
			            $variation_name  = isset($value->name)?$value->name:'';
			            $published       = isset($value->published)?$value->published:'';
			            $status          = isset($value->status)?$value->status:'';
			            $createdate      = isset($value->createdate)?$value->createdate:'';

			            $variation_list[] = array(
	      					'variation_id'   => $variation_id,
				            'variation_name' => $variation_name,
				            'published'      => $published,
				            'status'         => $status,
				            'createdate'     => $createdate,
	      				);
					}

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $variation_list;
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

			else if($method == '_updateVariation')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('id', 'name');
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
			    	$variation_id = $this->input->post('id');
			    	$name         = $this->input->post('name');
			    	$status       = $this->input->post('status');

			    	$where=array(
			    		'id !='  => $variation_id,
				    	'name'   => ucfirst($name),
				    	'status' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getVariation($where, '', '', 'result', '', '', '', '', $column);

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
					    	'name'       => ucfirst($name),
					    	'status'     => $status,
					    	'updatedate' => date('Y-m-d H:i:s')
					    );

			    		$update_id = array('id'=>$variation_id);
					    $update    = $this->commom_model->variation_update($data, $update_id);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_variation',
							'auto_id'    => $variation_id,
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

			else if($method == '_deleteVariation')
			{	
		    	$variation_id = $this->input->post('variation_id');

		    	if(!empty($variation_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

		    		$where  = array('id'=>$variation_id);
				    $update = $this->commom_model->variation_delete($data, $where);

				    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_variation',
							'auto_id'    => $variation_id,
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

		// privilege
		// ***************************************************
		public function privilege($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addPrivilege')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('header', 'privilege', 'privilege_type');
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
			    	$log_id         = $this->input->post('log_id');
					$log_role       = $this->input->post('log_role');
			    	$header         = $this->input->post('header');
			    	$privilege      = $this->input->post('privilege');
				    $short_code     = $this->input->post('short_code');
				    $privilege_type = $this->input->post('privilege_type');

				    $where=array(
				    	'short_code' => urlSlug($short_code),
				    	'status'     => '1',
				    );

					$column = 'id';

					$overalldatas = $this->commom_model->getPrivilege($where, '', '', 'result', '', '', '', '', $column);

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
							'header'         => ucfirst($header),
					    	'name'           => ucfirst($privilege),
					    	'short_code'     => urlSlug($privilege),
					    	'privilege_type' => $privilege_type,
					    	'createdate'     => date('Y-m-d H:i:s'),
					    );

					    $insert=$this->commom_model->privilege_insert($data);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_privilege',
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

			else if($method == '_listPrivilegePaginate')
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
				$overalldatas = $this->commom_model->getPrivilege($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->commom_model->getPrivilege($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$privilege_list = [];

					foreach ($data_list as $key => $value) {

						$privilege_id   = isset($value->id)?$value->id:'';
						$header         = isset($value->header)?$value->header:'';
			            $privilege_name = isset($value->name)?$value->name:'';
			            $published      = isset($value->published)?$value->published:'';
			            $status         = isset($value->status)?$value->status:'';
			            $createdate     = isset($value->createdate)?$value->createdate:'';

			            $privilege_list[] = array(
	      					'privilege_id'   => $privilege_id,
	      					'header'         => $header,
				            'privilege_name' => $privilege_name,
				            'published'      => $published,
				            'status'         => $status,
				            'createdate'     => $createdate,
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
			        $response['data']         = $privilege_list;
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

			else if($method == '_listPrivilege')
			{
				$col_1 = 'id, header, name, short_code, privilege_type';
				$whr_1 = array('status'=>'1', 'published'=>'1');
				$res_1 = $this->commom_model->getPrivilege($whr_1, '', '', 'result', '', '', '', '', $col_1);

				$data_list = [];
				if($res_1)
				{
					foreach ($res_1 as $key => $val_1) {

						$data_list[] = array(
							'privilege_id'   => empty_check($val_1->id),
							'header'         => empty_check($val_1->header),
				            'privilege_name' => empty_check($val_1->name),
				            'short_code'     => empty_check($val_1->short_code),
				            'privilege_type' => empty_check($val_1->privilege_type),
						);
					}
				}

				$response['status']  = 1;
		        $response['message'] = "Success"; 
		        $response['data']    = $data_list;
		        echo json_encode($response);
		        return;
			}

			else if($method == '_detailPrivilege')
			{
				$privilege_id = $this->input->post('privilege_id');

		    	if(!empty($privilege_id))
		    	{

		    		$where = array('id'=>$privilege_id);
				    $data  = $this->commom_model->getPrivilege($where);
				    if($data)
				    {	

				    	$privilege_list = [];

						foreach ($data as $key => $value) {

							$privilege_id   = isset($value->id)?$value->id:'';
							$header         = isset($value->header)?$value->header:'';
				            $privilege_name = isset($value->name)?$value->name:'';
				            $privilege_type = isset($value->privilege_type)?$value->privilege_type:'';
				            $published      = isset($value->published)?$value->published:'';
				            $status         = isset($value->status)?$value->status:'';
				            $createdate     = isset($value->createdate)?$value->createdate:'';

				            $privilege_list[] = array(
	          					'privilege_id'   => $privilege_id,
	          					'header'         => $header,
					            'privilege_name' => $privilege_name,
					            'privilege_type' => $privilege_type,
					            'published'      => $published,
					            'status'         => $status,
					            'createdate'     => $createdate,
	          				);
						}

	        			$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $privilege_list;
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

			else if($method == '_updatePrivilege')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('id', 'header', 'privilege');
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
			    	$log_id         = $this->input->post('log_id');
					$log_role       = $this->input->post('log_role');
			    	$privilege_id   = $this->input->post('id');
			    	$header         = $this->input->post('header');
			    	$privilege_name = $this->input->post('privilege');
					$privilege_type = $this->input->post('privilege_type');
			    	$status         = $this->input->post('status');

			    	$where=array(
			    		'id !='      => $privilege_id,
				    	'short_code' => urlSlug($privilege_name),
				    	'status'     => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getPrivilege($where, '', '', 'result', '', '', '', '', $column);

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
							'header'         => ucfirst($header),
					    	'name'           => ucfirst($privilege_name),
					    	'short_code'     => urlSlug($privilege_name),
					    	'privilege_type' => $privilege_type,
					    	'status'         => $status,
					    	'updatedate'     => date('Y-m-d H:i:s')
					    );

			    		$update_id = array('id'=>$privilege_id);
					    $update    = $this->commom_model->privilege_update($data, $update_id);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_privilege',
							'auto_id'    => $privilege_id,
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

			else if($method == '_deletePrivilege')
			{
		    	$privilege_id = $this->input->post('privilege_id');
		    	$log_id       = $this->input->post('log_id');
				$log_role     = $this->input->post('log_role');

		    	if(!empty($privilege_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

		    		$where  = array('id'=>$privilege_id);
				    $update = $this->commom_model->privilege_delete($data, $where);

				    $log_data = array(
						'u_id'       => $log_id,
						'role'       => $log_role,
						'table'      => 'tbl_privilege',
						'auto_id'    => $privilege_id,
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

		// month
		// ***************************************************
		public function month($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listMonth')
			{
				$where = array('status'=>'1', 'published'=>'1');

				$data_list = $this->commom_model->getMonth($where);

				if($data_list)
				{
					$month_list = [];

					foreach ($data_list as $key => $value) {

						$month_id    = isset($value->id)?$value->id:'';
			            $month_value = isset($value->month_value)?$value->month_value:'';
			            $month_name  = isset($value->month_name)?$value->month_name:'';
			            $published   = isset($value->published)?$value->published:'';
			            $status      = isset($value->status)?$value->status:'';
			            $createdate  = isset($value->createdate)?$value->createdate:'';

			            $month_list[] = array(
	      					'month_id'    => $month_id,
				            'month_value' => $month_value,
				            'month_name'  => $month_name,
				            'published'   => $published,
				            'status'      => $status,
				            'createdate'  => $createdate,
	      				);
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $month_list;
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

			if($method == '_detailMonth')
			{
				$month_id = $this->input->post('month_id');
				$where    = array('id' => $month_id, 'status'=>'1', 'published'=>'1');

				$data_list = $this->commom_model->getMonth($where);

				if($data_list)
				{
					$month_detail = [];

					foreach ($data_list as $key => $value) {

						$month_id    = isset($value->id)?$value->id:'';
			            $month_value = isset($value->month_value)?$value->month_value:'';
			            $month_name  = isset($value->month_name)?$value->month_name:'';
			            $published   = isset($value->published)?$value->published:'';
			            $status      = isset($value->status)?$value->status:'';
			            $createdate  = isset($value->createdate)?$value->createdate:'';

			            $month_detail = array(
	      					'month_id'    => $month_id,
				            'month_value' => $month_value,
				            'month_name'  => $month_name,
				            'published'   => $published,
				            'status'      => $status,
				            'createdate'  => $createdate,
	      				);
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $month_detail;
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
			if($method == '_detail_days_Month')
			{
				$month_id = $this->input->post('month_id');
				$year_id = $this->input->post('year_id');
				$wr=array(
					'id' => $year_id,
					'published' => '1'
				);
				$cm ='year_value';
				$year = $this->commom_model->getYear($wr, '', '', 'result', '', '', '', '', $cm);
				$year_value = !empty($year[0]->year_value)?$year[0]->year_value:'0';
				$month_count = cal_days_in_month(CAL_GREGORIAN, $month_id, $year_value);
				
				$array = [];
				for ($i=1; $i <= $month_count; $i++) { 

					$date_val = date('d-m-Y', strtotime($i.'-'.$month_id.'-'.$year_value));

					$day_val  = date('l', strtotime($date_val));
					array_push($array,$date_val);
				}
				$arr_count=count($array);
				$last_date_data =$arr_count-1;

				$first_date = $array[0];
				
			
				
				
				$end_date   = $array[$last_date_data];





				

					$response['status']       = 1;
			        $response['message']      = "Success";
					$response['data_from']         = $first_date; 
			        $response['data_to']         = $end_date;
		    		echo json_encode($response);
			        return;
				
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

		// year
		// ***************************************************
		public function year($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listYear')
			{
				$where = array('status'=>'1', 'published'=>'1');

				$data_list = $this->commom_model->getYear($where);

				if($data_list)
				{
					$year_list = [];
					foreach ($data_list as $key => $value) {

						$year_id     = isset($value->id)?$value->id:'';
			            $year_value  = isset($value->year_value)?$value->year_value:'';
			            $published   = isset($value->published)?$value->published:'';
			            $status      = isset($value->status)?$value->status:'';
			            $createdate  = isset($value->createdate)?$value->createdate:'';

			            $year_list[] = array(
	      					'year_id'    => $year_id,
				            'year_name'  => $year_value,
				            'published'  => $published,
				            'status'     => $status,
				            'createdate' => $createdate,
	      				);
					}

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $year_list;
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
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// message
		// ***************************************************
		public function message($param1="",$param2="",$param3="")
		{
			$method   = $this->input->post('method');
			$log_id   = $this->input->post('log_id');
			$log_role = $this->input->post('log_role');

			if($method == '_addMessage')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('message');
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
			    	$message = $this->input->post('message');
			    	
		    		$data=array(
				    	'message'    => $message,
				    	'createdate' => date('Y-m-d H:i:s')
				    );

				    $insert = $this->commom_model->message_insert($data);

				    $log_data = array(
						'u_id'       => $log_id,
						'role'       => $log_role,
						'table'      => 'tbl_message',
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

			else if($method == '_listMessagePaginate')
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
				$overalldatas = $this->commom_model->getMessage($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->commom_model->getMessage($where, $limit, $offset, 'result', $like, '', $option);
				if($data_list)
				{
					$message_list = [];

					foreach ($data_list as $key => $value) {

						$message_id = isset($value->id)?$value->id:'';
			            $message    = isset($value->message)?$value->message:'';
			            $published  = isset($value->published)?$value->published:'';
			            $status     = isset($value->status)?$value->status:'';
			            $createdate = isset($value->createdate)?$value->createdate:'';

			            $message_list[] = array(
	      					'message_id' => $message_id,
				            'message'    => $message,
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
			        $response['data']         = $message_list;
			        echo json_encode($response);
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

			else if($method == '_detailMessage')
			{
				$message_id = $this->input->post('message_id');

		    	if(!empty($message_id))
		    	{

		    		$where = array('id'=>$message_id);
				    $data  = $this->commom_model->getMessage($where);
				    if($data)
				    {	

				    	$message_list = [];

						foreach ($data as $key => $value) {

							$message_id = isset($value->id)?$value->id:'';
				            $message    = isset($value->message)?$value->message:'';
				            $published  = isset($value->published)?$value->published:'';
				            $status     = isset($value->status)?$value->status:'';
				            $createdate = isset($value->createdate)?$value->createdate:'';

				            $message_list[] = array(
	          					'message_id' => $message_id,
					            'message'    => $message,
					            'published'  => $published,
					            'status'     => $status,
					            'createdate' => $createdate,
	          				);
						}

	        			$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $message_list;
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

			else if($method == '_listMessage')
			{
				$where = array('status'=>'1', 'published'=>'1');

				$data_list = $this->commom_model->getMessage($where);

				if($data_list)
				{
					$message_list = [];

					foreach ($data_list as $key => $value) {

						$message_id = isset($value->id)?$value->id:'';
			            $message    = isset($value->message)?$value->message:'';
			            $published  = isset($value->published)?$value->published:'';
			            $status     = isset($value->status)?$value->status:'';
			            $createdate = isset($value->createdate)?$value->createdate:'';

			            $message_list[] = array(
	      					'message_id' => $message_id,
				            'message'    => $message,
				            'published'  => $published,
				            'status'     => $status,
				            'createdate' => $createdate,
	      				);
					}

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $message_list;
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

			else if($method == '_updateMessage')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('id', 'message');
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
			    	$message_id = $this->input->post('id');
			    	$message    = $this->input->post('message');
			    	$status     = $this->input->post('status');

					$data=array(
				    	'message'    => $message,
				    	'status'     => $status,
				    	'updatedate' => date('Y-m-d H:i:s')
				    );

		    		$update_id = array('id' => $message_id);
				    $update    = $this->commom_model->message_update($data, $update_id);

				    $log_data = array(
						'u_id'       => $log_id,
						'role'       => $log_role,
						'table'      => 'tbl_message',
						'auto_id'    => $message_id,
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

			else if($method == '_deleteMessage')
			{	
		    	$message_id = $this->input->post('message_id');

		    	if(!empty($message_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

		    		$where  = array('id'=>$message_id);
				    $update = $this->commom_model->message_delete($data, $where);

				    $log_data = array(
						'u_id'       => $log_id,
						'role'       => $log_role,
						'table'      => 'tbl_message',
						'auto_id'    => $message_id,
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

		// expense
		// ***************************************************
		public function expense($param1="",$param2="",$param3="")
		{
			$method   = $this->input->post('method');
			$log_id   = $this->input->post('log_id');
			$log_role = $this->input->post('log_role');

			if($method == '_addExpense')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('name');
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
			    	$name   = $this->input->post('name');

			    	$where=array(
				    	'name'   => $name,
				    	'status' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getExpenses($where, '', '', 'result', '', '', '', '', $column);

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
					    	'name'       => $name,
					    	'createdate' => date('Y-m-d H:i:s')
					    );

					    $insert=$this->commom_model->expenses_insert($data);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_expenses',
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

			else if($method == '_listExpensePaginate')
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
				$overalldatas = $this->commom_model->getExpenses($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->commom_model->getExpenses($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$expense_list = [];

					foreach ($data_list as $key => $value) {

						$expense_id   = isset($value->id)?$value->id:'';
			            $expense_name = isset($value->name)?$value->name:'';
			            $published    = isset($value->published)?$value->published:'';
			            $status       = isset($value->status)?$value->status:'';
			            $createdate   = isset($value->createdate)?$value->createdate:'';

			            $expense_list[] = array(
	      					'expense_id'   => $expense_id,
				            'expense_name' => $expense_name,
				            'published'    => $published,
				            'status'       => $status,
				            'createdate'   => $createdate,
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
			        $response['data']         = $expense_list;
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

			else if($method == '_detailExpense')
			{
				$expense_id = $this->input->post('expense_id');

		    	if(!empty($expense_id))
		    	{

		    		$where = array('id'=>$expense_id);
				    $data  = $this->commom_model->getExpenses($where);
				    if($data)
				    {	

				    	$expense_list = [];
						foreach ($data as $key => $value) {

							$expense_id   = isset($value->id)?$value->id:'';
				            $expense_name = isset($value->name)?$value->name:'';
				            $published    = isset($value->published)?$value->published:'';
				            $status       = isset($value->status)?$value->status:'';
				            $createdate   = isset($value->createdate)?$value->createdate:'';

				            $expense_list[] = array(
	          					'expense_id'   => $expense_id,
					            'expense_name' => $expense_name,
					            'published'    => $published,
					            'status'       => $status,
					            'createdate'   => $createdate,
	          				);
						}

	        			$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $expense_list;
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

			else if($method == '_listExpense')
			{
				$where = array('status'=>'1', 'published'=>'1');

				$data_list = $this->commom_model->getExpenses($where);

				if($data_list)
				{
					$expense_list = [];
					foreach ($data_list as $key => $value) {

						$expense_id   = isset($value->id)?$value->id:'';
			            $expense_name = isset($value->name)?$value->name:'';
			            $published    = isset($value->published)?$value->published:'';
			            $status       = isset($value->status)?$value->status:'';
			            $createdate   = isset($value->createdate)?$value->createdate:'';

			            $expense_list[] = array(
	      					'expense_id'   => $expense_id,
				            'expense_name' => $expense_name,
				            'published'    => $published,
				            'status'       => $status,
				            'createdate'   => $createdate,
	      				);
					}

					$response['status']  = 1;
			        $response['message'] = "Success"; 
			        $response['data']    = $expense_list;
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

			else if($method == '_updateExpense')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('id', 'name');
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
			    	$expense_id   = $this->input->post('id');
			    	$expense_name = $this->input->post('name');
			    	$status       = $this->input->post('status');

			    	$where=array(
			    		'id !='     => $expense_id,
				    	'name'      => $expense_name,
				    	'published' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getExpenses($where, '', '', 'result', '', '', '', '', $column);

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
					    	'name'       => $expense_name,
					    	'status'     => $status,
					    	'updatedate' => date('Y-m-d H:i:s')
					    );

			    		$update_id = array('id'=>$expense_id);
					    $update    = $this->commom_model->expenses_update($data, $update_id);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_expenses',
							'auto_id'    => $expense_id,
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

			else if($method == '_deleteExpense')
			{	
		    	$expense_id = $this->input->post('expense_id');

		    	if(!empty($expense_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

		    		$where  = array('id'=>$expense_id);
				    $update = $this->commom_model->expenses_delete($data, $where);

				    $log_data = array(
						'u_id'       => $log_id,
						'role'       => $log_role,
						'table'      => 'tbl_expenses',
						'auto_id'    => $expense_id,
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

            else if($method == '_Expenselist')
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
	    			$where = array('B.name');
	    		}
	    		else
	    		{
	    			$like = [];
	    			$where = array('A.published'=>'1');
	    		}

	    		$column = 'A.id';
				$overalldatas = $this->commom_model->expensesEntry_join($where, '', '', 'result', $like, '', '', '', $column);

				if($overalldatas)
				{
					$totalc = count($overalldatas);
				}
				else
				{
					$totalc = 0;
				}

				$option['order_by']   = 'A.id';
				$option['disp_order'] = 'DESC';

				$res_column = 'A.id, A.expense_no, A.employee_id, B.name , C.first_name, A.expense_date,A.expense_val,A.expense_type,A.status,A.createdate';
				$data_list = $this->commom_model->expensesEntry_join($where, $limit, $offset, 'result', $like, '', $option, '', $res_column);

				if($data_list)
				{
					$list_expense = [];
					foreach ($data_list as $key => $value) 
                    {

						$expense_no    = isset($value->expense_no)?$value->expense_no:'';
						$employee_id   = isset($value->employee_id)?$value->employee_id:'';
						$employee_name = isset($value->first_name)?$value->first_name:'';
						$expense_id    = isset($value->name)?$value->name:'';
						$expense_date  = isset($value->expense_date)?$value->expense_date:'';
						$expense_val   = isset($value->expense_val)?$value->expense_val:'';
						$expense_type  = isset($value->expense_type)?$value->expense_type:'';
			            $status        = isset($value->status)?$value->status:'';
			            $createdate    = isset($value->createdate)?$value->createdate:'';

			            $expense_list[] = array(
							'expense_no'    => $expense_no,
							'employee_id'   => $employee_id,	
							'employee_name' => $employee_name,	
							'expense_id'    => $expense_id,						
							'expense_date'  => $expense_date,
							'expense_val'   => $expense_val,
							'expense_type'  => $expense_type,
				            'status'        => $status,
				            'createdate'    => $createdate,
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
			        $response['data']         = $expense_list;
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

			else if($method == '_listType')
			{
				$type_id = array(
					array('type_id'  => strval('1'), 'type_val' => 'Cash'),
					array('type_id'  => strval('2'), 'type_val' => 'Bank'),
				);

				$response['status']  = 1;
		        $response['message'] = "Success"; 
		        $response['data']    = $type_id;
		        echo json_encode($response);
		        return;
			}

			else if($method == '_addExpenseEntry')
			{
				$expense_value    = $this->input->post('expense_value');
				$active_financial = $this->input->post('active_financial');

				if(empty($active_financial))
				{
					
					$option['order_by']   = 'id';
					$option['disp_order'] = 'DESC';
					$res_column = 'id';
					$where = array('status'=>'1', 'published'=>'1');
					$data_list = $this->commom_model->getfinancial($where, '', '', 'result', '', '', $option, '', $res_column);
					$active_financial = $data_list[0]->id;
					$status = 2;
				}
				else
				{
					$status = 1;
				}

				$error = FALSE;
			    $errors = array();
				$required = array('expense_value');
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
			    	$expense_value = json_decode($expense_value);	

			    	foreach ($expense_value as $key => $val) {

			    		$where = array(
				    		'financial_year' => $active_financial,
							'published'      => '1',
							'status'         => '1',
						);

						$exp_val   = $this->commom_model->getExpensesEntry($where,'','',"result",array(),array(),array(),TRUE,'COUNT(id)+1 AS autoid');

						$exp_count = !empty($exp_val[0]->autoid)?$exp_val[0]->autoid:'0';
						$exp_num   = 'EXP'.leadingZeros($exp_count, 5);

			    		$employee_id  = !empty($val->employee_id)?$val->employee_id:'0';
					    $expense_id   = !empty($val->expense_id)?$val->expense_id:'0';
					    $expense_date = !empty($val->expense_date)?$val->expense_date:'';
					    $expense_type = !empty($val->expense_type)?$val->expense_type:'';
					    $expense_val  = !empty($val->expense_val)?$val->expense_val:'0';
					    $expense_desc = !empty($val->expense_desc)?$val->expense_desc:'';

					    $value = array(
					    	'expense_no'     => $exp_num,
					    	'employee_id'    => $employee_id,
						    'expense_id'     => $expense_id,
						    'expense_date'   => date('Y-m-d', strtotime($expense_date)),
						    'expense_val'    => $expense_val,
						    'expense_type'   => $expense_type,
						    'expense_desc'   => $expense_desc,
						    'financial_year' => $active_financial,
							'status'		 => $status,
						    'createdate'     => date('Y-m-d H:i:s'),
					    );

					    $insert = $this->commom_model->expensesEntry_insert($value);
			    	}

			    	if($expense_value)
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

            else if($method == '_ExpenseStatusUpdate')
			{
				$expense_no    = $this->input->post('expense_no');
				$status  = $this->input->post('status');

				$error    = FALSE;
			    $errors   = array();
				$required = array('expense_no', 'status');
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
			    	$upt_val = array(
			    		'status' => $status,
			    		'updatedate' => date('Y-m-d H:i:s')
			    	);


			    	$upt_whr = array('expense_no' => $expense_no);
					
			    	$upt_res = $this->commom_model->expensesEntry_update($upt_val, $upt_whr);

				    if($upt_res)
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

			else
			{
				$response['status']  = 0;
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// outlet category
		// ***************************************************
		public function outlet_category($param1="",$param2="",$param3="")
		{
			$method   = $this->input->post('method');
			$log_id   = $this->input->post('log_id');
			$log_role = $this->input->post('log_role');

			if($method == '_addOutletCategoty')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('name', 'max_time');
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
			    	$name     = $this->input->post('name');
			    	$max_time = $this->input->post('max_time');

			    	$where=array(
				    	'name'   => ucfirst($name),
				    	'status' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getOutletCategory($where, '', '', 'result', '', '', '', '', $column);

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
					    	'name'       => ucfirst($name),
					    	'max_time'   => $max_time,
					    	'createdate' => date('Y-m-d H:i:s')
					    );

					    $insert=$this->commom_model->outletCategory_insert($data);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_outlet_category',
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

			else if($method == '_listOutletCategotyPaginate')
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
				$overalldatas = $this->commom_model->getOutletCategory($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->commom_model->getOutletCategory($where, $limit, $offset, 'result', $like, '', $option);
				if($data_list)
				{
					$category_list = [];

					foreach ($data_list as $key => $value) {

						$category_id    = isset($value->id)?$value->id:'';
			            $category_name  = isset($value->name)?$value->name:'';
			            $max_time       = isset($value->max_time)?$value->max_time:'';
			            $published      = isset($value->published)?$value->published:'';
			            $status         = isset($value->status)?$value->status:'';
			            $createdate     = isset($value->createdate)?$value->createdate:'';

			            $category_list[] = array(
	      					'category_id'     => $category_id,
				            'category_name'   => $category_name,
				            'max_time'        => $max_time,
				            'published'       => $published,
				            'status'          => $status,
				            'createdate'      => $createdate,
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
			        $response['data']         = $category_list;
			        echo json_encode($response);
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

			else if($method == '_detailOutletCategoty')
			{
				$category_id = $this->input->post('category_id');

		    	if(!empty($category_id))
		    	{

		    		$where = array('id'=>$category_id);
				    $data  = $this->commom_model->getOutletCategory($where);
				    if($data)
				    {	

				    	$category_list = [];

						foreach ($data as $key => $value) {

							$category_id    = isset($value->id)?$value->id:'';
				            $category_name  = isset($value->name)?$value->name:'';
				            $max_time       = isset($value->max_time)?$value->max_time:'';
				            $published      = isset($value->published)?$value->published:'';
				            $status         = isset($value->status)?$value->status:'';
				            $createdate     = isset($value->createdate)?$value->createdate:'';

				            $category_list[] = array(
		      					'category_id'     => $category_id,
					            'category_name'   => $category_name,
					            'max_time'        => $max_time,
					            'published'       => $published,
					            'status'          => $status,
					            'createdate'      => $createdate,
		      				);
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

			else if($method == '_listOutletCategoty')
			{
				$where = array('status'=>'1', 'published'=>'1');

				$data_list = $this->commom_model->getOutletCategory($where);

				if($data_list)
				{
					$category_list = [];

					foreach ($data_list as $key => $value) {

						$category_id    = isset($value->id)?$value->id:'';
			            $category_name  = isset($value->name)?$value->name:'';
			            $published      = isset($value->published)?$value->published:'';
			            $status         = isset($value->status)?$value->status:'';
			            $createdate     = isset($value->createdate)?$value->createdate:'';

			            $category_list[] = array(
	      					'category_id'     => $category_id,
				            'category_name'   => $category_name,
				            'published'       => $published,
				            'status'          => $status,
				            'createdate'      => $createdate,
	      				);
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
			        $response['message'] = "Not Found"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}

			else if($method == '_updateOutletCategoty')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('id', 'name');
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
			    	$category_id = $this->input->post('id');
			    	$name        = $this->input->post('name');
			    	$max_time    = $this->input->post('max_time');
			    	$status      = $this->input->post('status');

			    	$where=array(
			    		'id !='  => $category_id,
				    	'name'   => ucfirst($name),
				    	'status' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getOutletCategory($where, '', '', 'result', '', '', '', '', $column);

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
					    	'name'       => ucfirst($name),
					    	'max_time'   => $max_time,
					    	'status'     => $status,
					    	'updatedate' => date('Y-m-d H:i:s')
					    );

			    		$update_id = array('id'=>$category_id);
					    $update    = $this->commom_model->outletCategory_update($data, $update_id);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_outlet_category',
							'auto_id'    => $category_id,
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

			else if($method == '_deleteOutletCategoty')
			{
		    	$category_id = $this->input->post('category_id');

		    	if(!empty($category_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

		    		$where  = array('id'=>$category_id);
				    $update = $this->commom_model->outletCategory_delete($data, $where);

				    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_outlet_category',
							'auto_id'    => $category_id,
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
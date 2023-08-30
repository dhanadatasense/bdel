<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Collaterals extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('collaterals_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Add Collaterals
		// ***************************************************
		public function add_collaterals($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_addCollaterals')
			{
				$name        = $this->input->post('name');
				$type        = $this->input->post('type');
				$start_date  = $this->input->post('start_date');
				$end_date    = $this->input->post('end_date');
				$description = $this->input->post('description');
				$loged_by    = $this->input->post('loged_by');
				$random_val  = generateRandomString(32);

				$error    = FALSE;
			    $errors   = array();
				$required = array('name', 'type', 'description', 'loged_by');
				if($type == 2)
			    {
			    	array_push($required, 'start_date', 'end_date');
			    }
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

			    $data = array(
					'name'         => $name,
					'type'         => $type,
					'description'  => $description,
					'random_val'   => $random_val,
					'created_by'   => $loged_by,
			    	'created_date' => date('Y-m-d H:i:s'),
			    );

			   	if($type == 2)
			   	{
			   		if($start_date <= $end_date)
			   		{
			   			$data['start_date'] = date('Y-m-d', strtotime($start_date));
			   			$data['end_date']   = date('Y-m-d', strtotime($end_date));
			   		}
			   		else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Date incorrect"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			   	}

		    	if(!empty($_FILES['file']['name']))
		    	{
		    		$file_name   = $_FILES['file']['name'];
					$file_val    = explode('.', $file_name);
					$file_res    = $file_val[1];
					$upload_name = generateRandomString(13).'.'.$file_res;

				    $configImg['upload_path']   = 'upload/collaterals/';
					$configImg['max_size']      = '1024000';
					$configImg['allowed_types'] = 'jpg|jpeg|png|gif|doc|pdf|mp4|mov|wmv|avi|avchd|mkv';
					$configImg['overwrite']     = FALSE;
					$configImg['remove_spaces'] = TRUE;
        			$configImg['file_name']     = $upload_name;
					$this->load->library('upload', $configImg);
					$this->upload->initialize($configImg);

					if(!$this->upload->do_upload('file'))
					{
				        $response['status']  = 0;
				        $response['message'] = $this->upload->display_errors();
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
					}
					else
					{
						$data['file'] = $upload_name;
					}

					$insert = $this->collaterals_model->collaterals_insert($data);

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
		    	else
		    	{
		    		$response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
		    	}
			}

			else if($method == '_editCollaterals')
			{	
				$c_id        = $this->input->post('c_id');
				$name        = $this->input->post('name');
				$type        = $this->input->post('type');
				$start_date  = $this->input->post('start_date');
				$end_date    = $this->input->post('end_date');
				$description = $this->input->post('description');
				$loged_by    = $this->input->post('loged_by');
				$pstatus     = $this->input->post('pstatus');

				$error    = FALSE;
			    $errors   = array();
				$required = array('c_id', 'name', 'type', 'description', 'loged_by');
				if($type == 2)
			    {
			    	array_push($required, 'start_date', 'end_date');
			    }
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

			    $data = array(
					'name'         => $name,
					'type'         => $type,
					'description'  => $description,
			    	'status'       => $pstatus,
					'updated_by'   => $loged_by,
			    	'updated_date' => date('Y-m-d H:i:s'),
			    );

			    if($type == 2)
			   	{
			   		if($start_date <= $end_date)
			   		{
			   			$data['start_date'] = date('Y-m-d', strtotime($start_date));
			   			$data['end_date']   = date('Y-m-d', strtotime($end_date));
			   		}
			   		else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = "Date incorrect"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			   	}
			   	else
			   	{
			   		$data['start_date'] = NULL;
			   		$data['end_date']   = NULL;
			   	}

		    	if(!empty($_FILES['file']['name']))
		    	{
		    		$file_name   = $_FILES['file']['name'];
					$file_val    = explode('.', $file_name);
					$file_res    = $file_val[1];
					$upload_name = generateRandomString(13).'.'.$file_res;

				    $configImg['upload_path']   = 'upload/collaterals/';
					$configImg['max_size']      = '1024000';
					$configImg['allowed_types'] = 'jpg|jpeg|png|gif|doc|pdf|mp4|mov|wmv|avi|avchd|mkv';
					$configImg['overwrite']     = FALSE;
					$configImg['remove_spaces'] = TRUE;
        			$configImg['file_name']     = $upload_name;
					$this->load->library('upload', $configImg);
					$this->upload->initialize($configImg);

					if(!$this->upload->do_upload('file'))
					{
				        $response['status']  = 0;
				        $response['message'] = $this->upload->display_errors();
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
					}
					else
					{
						$data['file'] = $upload_name;
					}
		    	}
		    	
		    	$where  = array('id' => $c_id);
				$update = $this->collaterals_model->collaterals_update($data, $where);

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
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// Manage Collaterals
		// ***************************************************
		public function manage_collaterals($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listCollateralsPaginate')
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
	    		}
	    		else
	    		{
	    			$like = [];
	    		}

	    		$where  = array('published'=>'1');
	    		$column = 'id';
				$overalldatas = $this->collaterals_model->getCollaterals($where, '', '', 'result', $like, '', '', '', $column);

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

				$column    = 'id, name, type, status, created_date';
				$data_res = $this->collaterals_model->getCollaterals($where, $limit, $offset, 'result', $like, '', $option, '', $column);

				if($data_res)
				{
					$data_list = [];

					foreach ($data_res as $key => $val) {
						$data_list[] = array(
							'id'           => zero_check($val->id),
				            'name'         => empty_check($val->name),
				            'type'         => empty_check($val->type),
				            'status'       => zero_check($val->status),
				            'created_date' => date_check($val->created_date),
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
			        $response['data']         = $data_list;
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

			else if($method == '_collateralsDetails')
			{
				$collateral_id = $this->input->post('collateral_id');

				if($collateral_id != '')
				{
					$where  = array(
						'id'        =>  $collateral_id,
						'published' => '1',
					);

					$column = 'id, name, type, start_date, end_date, file, description, status';

					$result = $this->collaterals_model->getCollaterals($where, '', '', 'row', '', '', '', '', $column);

					if($result)
					{
						$data_list = array(
							'id'           => zero_check($result->id),
				            'name'         => empty_check($result->name),
				            'type'         => empty_check($result->type),
				            'start_date'   => date_check($result->start_date),
				            'end_date'     => date_check($result->end_date),
						    'file'         => empty_check($result->file),
						    'description'  => empty_check($result->description),
				            'status'       => zero_check($result->status),
						);

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $data_list;
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

			else if($method == '_deleteCollaterals')
			{
				$collateral_id = $this->input->post('collateral_id');
				$deleted_by    = $this->input->post('deleted_by');

				if($collateral_id != '' && $deleted_by != '')
				{
					$data   = array(
						'published'    => '0',
						'deleted_by'   => $deleted_by,
						'deleted_date' => date('Y-m-d H:i:s'),
					);

					$where  = array('id' => $collateral_id);
					$delete = $this->collaterals_model->collaterals_delete($data, $where);

					if($delete)
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

		// Collaterals List
		// ***************************************************
		public function collaterals_list($param1="",$param2="",$param3="")
		{
			$method   = $this->input->post('method');
			$cur_date = date('Y-m-d');

			if($method == '_listCollateralsPaginate')
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
	    		}
	    		else
	    		{
	    			$like = [];
	    		}

	    		$where  = array('published'=>'1');
	    		$column = 'id';
				$overalldatas = $this->collaterals_model->getCollaterals($where, '', '', 'result', $like, '', '', '', $column);

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

				$column   = 'name, type, start_date, end_date, created_date, random_val';
				$data_res = $this->collaterals_model->getCollaterals($where, $limit, $offset, 'result', $like, '', $option, '', $column);

				if($data_res)
				{
					$data_list = [];
					foreach ($data_res as $key => $val) {
				        $name         = empty_check($val->name);
				        $type         = empty_check($val->type);
				        $start_date   = system_date($val->start_date);
				        $end_date     = system_date($val->end_date);
				        $created_date = date_check($val->created_date);
				        $random_val   = empty_check($val->random_val);

				        if($type == 1)
				        {
				        	$parmanent_list = array(
				        		'name'         => $name,
				        		'created_date' => $created_date,
				        		'random_val'   => $random_val,
				        	);

				        	array_push($data_list, $parmanent_list);
				        }
				        else if($type == 2)
				        {
				        	if($cur_date >= $start_date && $end_date >= $cur_date)
				    		{
				    			$temporary_list = array(
					        		'name'         => $name,
					        		'created_date' => $created_date,
					        		'random_val'   => $random_val,
					        	);

					        	array_push($data_list, $temporary_list);
				    		}
				        }
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
			        $response['data']         = $data_list;
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

			else if($method == '_collateralsDetails')
			{
				$random_val = $this->input->post('random_val');

				if($random_val != '')
				{
					$where  = array(
						'random_val' =>  $random_val,
						'status'     => '1',
						'published'  => '1',
					);

					$column = 'name, file, description, status';

					$result = $this->collaterals_model->getCollaterals($where, '', '', 'row', '', '', '', '', $column);

					if($result)
					{
						$file_data = '';
						$file_type = '';
						if(empty_check($result->file))
						{
							$file_res   = explode('.', $result->file);
							$file_name  = $file_res[1];

							$array_1 = array('mp4', 'mov', 'wmv', 'avi', 'avchd', 'mkv');
							$array_2 = array('jpg', 'jpeg', 'png', 'gif');
							$array_3 = array('doc', 'pdf');

							if(in_array($file_name, $array_1))
							{
								$file_type .= 'Video';
							}
							else if(in_array($file_name, $array_2))
							{
								$file_type .= 'Image';
							}
							else
							{
								$file_type .= 'Docs';
							}

							$file_data .= FILE_URL.'collaterals/'.empty_check($result->file);
						}

						$data_list = array(
				            'name'        => empty_check($result->name),
						    'file'        => $file_data,
						    'file_type'   => $file_type,
						    'description' => strip_tags(empty_check($result->description)),
				            'status'      => zero_check($result->status),
						);

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $data_list;
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
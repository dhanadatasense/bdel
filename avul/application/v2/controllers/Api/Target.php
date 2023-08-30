<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Target extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('target_model');
			$this->load->model('commom_model');
			$this->load->model('employee_model');
			$this->load->model('assignshop_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Add Target
		// ***************************************************
		public function add_target($param1="",$param2="",$param3="")
		{
			$method       = $this->input->post('method');
			$target_id    = $this->input->post('target_id');
			$month_id     = $this->input->post('month_id');
			$year_id      = $this->input->post('year_id');
			$target_value = $this->input->post('target_value');
			$financial_id = $this->input->post('financial_id');

			if($method == '_addTarget')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id', 'financial_id', 'target_value');
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

					$target_val = json_decode($target_value);	
					// Year Details
					$where_2    = array('id' => $year_id);
					$year_det   = $this->commom_model->getYear($where_2);	
					$year_value = !empty($year_det[0]->year_value)?$year_det[0]->year_value:'';

					foreach ($target_val as $key => $val) {

						$employee_id = $val->employee_id;

						// Employee Details
						$where_4    = array('id' => $employee_id);
						$column_4   = 'first_name,last_name';
						$data_4     = $this->employee_model->getEmployee($where_4, '', '', 'result', '', '', '', '', $column_4);
						$first_name = !empty($data_4[0]->first_name)?$data_4[0]->first_name:'';
						$last_name  = !empty($data_4[0]->last_name)?$data_4[0]->last_name:'';

						$arr = array($first_name,$last_name);
						$emp_name =join(" ",$arr);


						$where =array(
							'employee_id' => $employee_id,
							'month_id'    => $month_id,
							'year_name'   => $year_value,
							'status'      => '1',
							'published'   => '1',
						);	

						
						$column = 'id';

						$overalldatas = $this->target_model->getTargetDetails($where, '', '', 'result', '', '', '', '', $column);

						if(!empty($overalldatas))
						{
							$response['status']  = 0;
							$response['message'] = " $emp_name , Already Exist "; 
							$response['data']    = [];
							echo json_encode($response);
							return; 
						}
						else
						{
							// Month Details
							$where_1   = array('month_value' => $month_id);
							$month_det = $this->commom_model->getMonth($where_1);	
							if($month_det)
							{
								$month_name  = !empty($month_det[0]->month_name)?$month_det[0]->month_name:'';

								// Year Details
								$where_2    = array('id' => $year_id);
								$year_det   = $this->commom_model->getYear($where_2);	
								$year_value = !empty($year_det[0]->year_value)?$year_det[0]->year_value:'';


								$where1 = array(
									'month_id'    => $month_id,
									'month_name'  => $month_name,
									'year_id'     => $year_id,
									'year_value'  => $year_value,
								);
								$column1 = 'id';

								$overalldatas1 = $this->target_model->getTarget($where1, '', '', 'result', '', '', '', '', $column1);

								// $insert = $overalldatas1[0]->id;

								if(empty($overalldatas1))
								{
									$data = array(
										'month_id'    => $month_id,
										'month_name'  => $month_name,
										'year_id'     => $year_id,
										'year_value'  => $year_value,
										'createdate'  => date('Y-m-d H:i:s'),
									);
	
									$insert = $this->target_model->target_insert($data);
								}

								$target_val = json_decode($target_value);	

								foreach ($target_val as $key => $val) {

									$employee_id = !empty($val->employee_id)?$val->employee_id:'0';
									$target_res  = !empty($val->target_val)?$val->target_val:'0';

									$str_data = array(
										'target_id'    => $insert,
										'employee_id'  => $employee_id,	
										'month_id'     => $month_id,
										'month_name'   => $month_name,
										'year_name'    => $year_value,
										'target_val'   => $target_res,
										'createdate'   => date('Y-m-d H:i:s'),
									);

									$target_insert = $this->target_model->targetDetails_insert($str_data);
								}

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
								$response['message'] = "Data Not Found"; 
								$response['data']    = [];
								echo json_encode($response);
								return;
							}
						}
					}

					// exit;

			    	// $where=array(
			    	// 	'month_id'  => $month_id,
				    // 	'year_id'   => $year_id,
				    // 	'status'    => '1',
				    // 	'published' => '1',
				    // );			   

					// $column = 'id';

					// $overalldatas = $this->target_model->getTarget($where, '', '', 'result', '', '', '', '', $column);

					// if(!empty($overalldatas))
					// {
					// 	$response['status']  = 0;
				    //     $response['message'] = "Data Already Exist"; 
				    //     $response['data']    = [];
				    //     echo json_encode($response);
				    //     return; 
					// }
					
			    }
			}

			else if($method == '_updateTarget')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('target_id', 'month_id', 'year_id', 'financial_id', 'target_value');
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
			    	$where=array(
			    		'id !='     => $target_id,
				    	'month_id'  => $month_id,
				    	'year_id'   => $year_id,
				    	'status'    => '1',
				    	'published' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->target_model->getTarget($where, '', '', 'result', '', '', '', '', $column);

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
						// Month Details
				    	$where_1   = array('month_value' => $month_id);
						$month_det = $this->commom_model->getMonth($where_1);	
						if($month_det)
						{
							$month_name  = !empty($month_det[0]->month_name)?$month_det[0]->month_name:'';

	            			// Year Details
	            			$where_2    = array('id' => $year_id);
							$year_det   = $this->commom_model->getYear($where_2);	
							$year_value = !empty($year_det[0]->year_value)?$year_det[0]->year_value:'';

							$data = array(
								'month_id'    => $month_id,
								'month_name'  => $month_name,
						    	'year_id'     => $year_id,
						    	'year_value'  => $year_value,
						    	'updatedate'  => date('Y-m-d H:i:s'),
						    );

						    $update_id = array('id' => $target_id);
					    	$update    = $this->target_model->target_update($data, $update_id);

					    	$target_val = json_decode($target_value);

					    	$del_val = array('published' => '0');
					    	$del_whr = array('target_id' => $target_id);
				    		$del_tar = $this->target_model->targetDetails_delete($del_val, $del_whr);

				    		foreach ($target_val as $key => $val) {

				    			$employee_id = !empty($val->employee_id)?$val->employee_id:'0';
						    	$target_res  = !empty($val->target_val)?$val->target_val:'0';

				    			$str_data = array(
									'employee_id'  => $employee_id,	
									'month_id'     => $month_id,
									'month_name'   => $month_name,
									'year_name'    => $year_value,
									'target_val'   => $target_res,
									'published'    => '1',
									'updatedate'   => date('Y-m-d H:i:s'),
								);

								$auto_id    = array('id' => $val->auto_id);
					    		$update_det = $this->target_model->targetDetails_update($str_data, $auto_id);
				    		}

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
					        $response['message'] = "Data Not Found"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return;
						}
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

		// Manage Target
		// ***************************************************
		public function manage_target($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listTargetPaginate')
			{
				$limit       = $this->input->post('limit');
	    		$offset      = $this->input->post('offset');
	    		$financ_year = $this->input->post('financial_year');

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
	    			$like['name']     = $search;
	    			$where = array('published'    => '1');
	    		}
	    		else
	    		{
	    			$like  = [];
	    			$where = array('published'    => '1');
	    		}

	    		$column = 'id';
				$overalldatas = $this->target_model->getTarget($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->target_model->getTarget($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$assign_list = [];
					foreach ($data_list as $key => $value) {

						$target_id  = !empty($value->id)?$value->id:'';
					    $month_id   = !empty($value->month_id)?$value->month_id:'';
					    $month_name = !empty($value->month_name)?$value->month_name:'';
					    $year_id    = !empty($value->year_id)?$value->year_id:'';
					    $year_value = !empty($value->year_value)?$value->year_value:'';
					    $status     = !empty($value->status)?$value->status:'';
					    $createdate = !empty($value->createdate)?$value->createdate:'';

						$assign_list[] = array(
							'target_id'  => $target_id,
							'month_id'   => $month_id,
							'month_name' => $month_name,
							'year_id'    => $year_id,
							'year_value' => $year_value,
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
			        $response['data']         = $assign_list;
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

			else if($method == '_detailTarget')
			{
				$target_id = $this->input->post('target_id');

				if(!empty($target_id))
				{
					$where_1 = array('id'=>$target_id);
				    $data    = $this->target_model->getTarget($where_1);
				    if($data)
				    {
				    	$assign_details = array(
				    		'target_id'  => !empty($data[0]->id)?$data[0]->id:'',
				            'month_id'   => !empty($data[0]->month_id)?$data[0]->month_id:'',
				            'month_name' => !empty($data[0]->month_name)?$data[0]->month_name:'',
				            'year_id'    => !empty($data[0]->year_id)?$data[0]->year_id:'',
				            'year_value' => !empty($data[0]->year_value)?$data[0]->year_value:'',
				            'status'     => !empty($data[0]->status)?$data[0]->status:'',
				    	);

				    	$where_2 = array(
				    		'target_id' => $target_id,
				    		'published' => '1',
				    	);

				    	$data_2  = $this->target_model->getTargetDetails($where_2);

				    	$target_list = [];
				    	foreach ($data_2 as $key => $value) {
				    		$auto_id     = !empty($value->id)?$value->id:'';
						    $target_id   = !empty($value->target_id)?$value->target_id:'';
						    $employee_id = !empty($value->employee_id)?$value->employee_id:'';
						    $target_val  = !empty($value->target_val)?$value->target_val:'0';
						    $achieve_val = !empty($value->achieve_val)?$value->achieve_val:'0';

						    // Employee Details
						    $where_3  = array('id' => $employee_id);
						    $column_3 = 'first_name,last_name, mobile';
						    $emp_data = $this->employee_model->getEmployee($where_3, '', '', 'result', '', '', '', '', $column_3);

						    $first_name   = !empty($emp_data[0]->first_name)?$emp_data[0]->first_name:'';
							$last_name   = !empty($emp_data[0]->last_name)?$emp_data[0]->last_name:'';
            				$emp_mobile = !empty($emp_data[0]->mobile)?$emp_data[0]->mobile:'';
							$arr = array($first_name,$last_name);
							$emp_name =join(" ",$arr);
						    $target_list[] = array(
						    	'auto_id'         => $auto_id,
							    'target_id'       => $target_id,
							    'employee_id'     => $employee_id,
							    'employee_name'   => $emp_name,
							    'employee_mobile' => $emp_mobile,
							    'target_val'      => $target_val,
							    'achieve_val'     => $achieve_val,
						    );
				    	}	

				    	$target_value = array(
				    		'assign_details' => $assign_details,
				    		'target_list'    => $target_list, 
				    	);

				    	$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $target_value;
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
				else
		    	{
		    		$response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
		    	}
			}

			else if($method == '_deleteTarget')
			{
				$target_id = $this->input->post('target_id');

				if(!empty($target_id))
				{
					$data=array(
				    	'published' => '0',
				    );

		    		$where_1 = array('id' => $target_id);
				    $delete  = $this->target_model->target_delete($data, $where_1);
				    if($delete)
				    {
				    	$where_2 = array('target_id' => $target_id);
				    	$del_tar = $this->target_model->targetDetails_delete($data, $where_2);

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
		
		// assign Product Target
		// ***************************************************
		public function assign_product_target($param1="",$param2="",$param3="")
		{
			$method       = $this->input->post('method');
			$target_id    = $this->input->post('target_id');
			$month_id     = $this->input->post('month_id');
			$year_id      = $this->input->post('year_id');
			$category_id  = $this->input->post('category_id');
			$target_value = $this->input->post('target_value');
			$financial_id = $this->input->post('financial_id');
			$templatename = $this->input->post('templatename');

			if($method == '_addEmployeeProductTarget')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('templatename','category_id', 'financial_id', 'target_value');
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
			    	$where=array(
				    	'templatename'=> $templatename,
				    	'category_id' => $category_id,
				    	'status'      => '1',
				    	'published'   => '1',
				    );			   
					$column = 'id';

					$overalldatas = $this->target_model->getProductAssignTarget($where, '', '', 'result', '', '', '', '', $column);

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
						$target_res = json_decode($target_value);	
						$total_val  = 0;
						foreach ($target_res as $key => $val) {
							$target_amt = !empty($val->target_val)?$val->target_val:'0';
							$total_val += $target_amt;
					    }

			    		if($target_res)
			    		{

			    			$where_3  = array('id' => $category_id);
					    	$column_3 = 'name';
					    	$data_3   = $this->commom_model->getCategory($where_3, '', '', 'result', '', '', '', '', $column_3);
					    	$cat_name = !empty($data_3[0]->name)?$data_3[0]->name:'';

							$data = array(
								'category_id'   => $category_id,
								'templatename'  => $templatename,
								'total_target'	=> $total_val,
								'financial_year'=> $financial_id,
								'createdate'    => date('Y-m-d H:i:s'),
							);

							$insert = $this->target_model->ProducttempalteTarget_insert($data);

						    // Total Value
							$target_res = json_decode($target_value);	
							foreach ($target_res as $key => $val) {
								$product_id = !empty($val->product_id)?$val->product_id:'0';
							    $type_id    = !empty($val->type_id)?$val->type_id:'0';
							    $target_val = !empty($val->target_val)?$val->target_val:'0';

							    // Employee Details
				    			$where_6  = array('id' => $type_id);
								$column_6 = 'description';
								$data_6   = $this->commom_model->getProductType($where_6, '', '', 'result', '', '', '', '', $column_6);
								$pdt_desc = !empty($data_6[0]->description)?$data_6[0]->description:'';


							    $str_data = array(
									'target_id'	    => $insert,
							    	'category_id'   => $category_id,
							    	'product_id'    => $product_id,
							    	'type_id'       => $type_id,
							    	'description'   => $pdt_desc,
									'templatename'  =>$templatename,
							    	'target_val'    => $target_val,
									'financial_year'=>$financial_id,
							    	'createdate'    => date('Y-m-d H:i:s'),
							    );

							    $target_insert = $this->target_model->ProductAssignTarget_insert($str_data);
							}

							if($target_insert)
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
					        $response['message'] = "Invalid Value"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
			    		}

					}
			    }
			}

			else if($method == '_getEmployeeList')
			{
				$month_id = $this->input->post('month_id');
				$year_id  = $this->input->post('year_id');

				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id');
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
			    	// Year Details
			    	$where_1  = array('id' => $year_id);
			    	$column_1 = 'year_value';
			    	$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
			    	$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';

			    	// Employee List
			    	$where_2 = array(
			    		'month_id'  => $month_id,
			    		'year_name' => $year_val,
			    		'published' => '1',
			    	);

			    	$column_2 = 'employee_id, target_val';

			    	$data_2   = $this->target_model->getTargetDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

			    	if($data_2)
			    	{	
			    		$employee_list = [];
			    		foreach ($data_2 as $key => $val_2) {
			    				
			    			$employee_id = !empty($val_2->employee_id)?$val_2->employee_id:'';
							$target_val  = !empty($val_2->target_val)?$val_2->target_val:'0';

							// Employee Details
							$where_3  = array('id' => $employee_id);
							$column_3 = 'first_name,last_name';
							$data_3   = $this->employee_model->getEmployee($where_3, '', '', 'result', '', '', '', '', $column_3);
							$first_name = !empty($data_3[0]->first_name)?$data_3[0]->first_name:'';
							$last_name = !empty($data_3[0]->last_name)?$data_3[0]->last_name:'';
							$arr = array($first_name,$last_name);
							$emp_name =join(" ",$arr);
							$employee_list[] = array(
								'employee_id' => $employee_id,
								'emp_name'    => $emp_name,
								'target_val'  => $target_val,
							);

			    		}

			    		$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $employee_list;
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

			else
			{
				$response['status']  = 0;
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// assign employee Target
		// ***************************************************
		public function assign_Employee_target($param1="",$param2="",$param3="")
		{
			$method       = $this->input->post('method');
			$month_id     = $this->input->post('month_id');
			$year_id      = $this->input->post('year_id');
			$employee_id  = $this->input->post('employee_id');
			$category_id  = $this->input->post('category_id');
			$financial_id = $this->input->post('financial_id');
			$template_id  = $this->input->post('template_id');
		
			if($method == '_addEmployeeProductarget')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id', 'template_id', 'employee_id','financial_id');
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
					$type_val  = explode(',', $employee_id);
					foreach ($type_val as $key => $vall) 
					{
						$empvalue = $vall;
					
						$where_1  = array('id' => $year_id);
						$column_1 = 'year_value';
						$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
						$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';

						$where=array(
							'month_id'    => $month_id,
							'year_name'   => $year_val,
							'emp_id'      => $empvalue,
							'template_id' => $template_id,
							'status'      => '1',
							'published'   => '1',
						);			   
						$column = 'id';
						$overalldatas = $this->target_model->getProductTargetDetails($where, '', '', 'result', '', '', '', '', $column);
						
						if(!empty($overalldatas))
						{
							$response['status']  = 0;
							$response['message'] = "Data Already Exist"; 
							$response['data']    = [];
							echo json_encode($response);
							return; 
						}
					}
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
						if(!empty($template_id))
						{
							$type_vall  = explode(',', $employee_id);
							foreach ($type_vall as $key => $valll) 
							{

								// Employee Details
								$where_4    = array('id' => $valll);
								$column_4   = 'first_name,last_name';
								$data_4     = $this->employee_model->getEmployee($where_4, '', '', 'result', '', '', '', '', $column_4);
								$first_name = !empty($data_4[0]->first_name)?$data_4[0]->first_name:'';
								$last_name  = !empty($data_4[0]->last_name)?$data_4[0]->last_name:'';

								$arr = array($first_name,$last_name);
								$emp_name =join(" ",$arr);


								$where_2 = array(
									'employee_id' => $valll,
									'month_id'    => $month_id,
									'year_name'   => $year_val,
									'published'   => '1',
								);
		
								$column_2   = 'target_val';
								$data_2     = $this->target_model->getTargetDetails($where_2, '', '', 'result', '', '', '', '', $column_2);
								$target_tot = !empty($data_2[0]->target_val)?$data_2[0]->target_val:'0';

								$where_       = array( 'id' => $template_id);
								$data_        = $this->target_model->getProducttempalteTarget($where_, '', '', 'result', '', '', '', '',);
								$total_target = !empty($data_[0]->total_target)?$data_[0]->total_target:'';

								if($total_target >= $target_tot)
								{
									$response['status']  = 0;
									$response['message'] = "$emp_name Target Value Invalid"; 
									$response['data']    = [];
									echo json_encode($response);
									return; 
									exit();
								}
							}	

							$type_vall  = explode(',', $employee_id);
							foreach ($type_vall as $key => $valll) 
							{
								$empvaluee = $valll;

								// Employee Details
								$where_4    = array('id' => $empvaluee);
								$column_4   = 'first_name,last_name';
								$data_4     = $this->employee_model->getEmployee($where_4, '', '', 'result', '', '', '', '', $column_4);
								$first_name = !empty($data_4[0]->first_name)?$data_4[0]->first_name:'';
								$last_name  = !empty($data_4[0]->last_name)?$data_4[0]->last_name:'';

								$arr = array($first_name,$last_name);
								$emp_name =join(" ",$arr);

								$where_  = array( 'target_id' => $template_id);
								$data_   = $this->target_model->getProductAssignTarget($where_, '', '', 'result', '', '', '', '',);

								$category_idd = !empty($data_[0]->category_id)?$data_[0]->category_id:'';
								
								// Category Details
								$where_3  = array('id' => $category_idd);
								$column_3 = 'name';
								$data_3   = $this->commom_model->getCategory($where_3, '', '', 'result', '', '', '', '', $column_3);
								$cat_name = !empty($data_3[0]->name)?$data_3[0]->name:'';
								
								// Month Details
								$where_5  = array('month_value' => $month_id);
								$column_5 = 'month_name';
								$data_5   = $this->commom_model->getMonth($where_5, '', '', 'result', '', '', '', '', $column_5);
								$mon_name = !empty($data_5[0]->month_name)?$data_5[0]->month_name:'';

								//year details
								$where_1  = array('id' => $year_id);
								$column_1 = 'year_value';
								$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
								$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';

								$target_valu = [];
								foreach($data_ as $key => $val) 
								{
									$target_val  = !empty($val->target_val)?$val->target_val:'0';
									$target_valu[]=$target_val;
								}
								$targetvalue = array_sum($target_valu);
							
								$data = array(
									'month_id'      => $month_id,
									'month_name'    => $mon_name,
									'year_id'       => $year_id,
									'year_name'    	=> $year_val,
									'emp_id'        => $empvaluee,
									'emp_name'      => $emp_name,
									'category_id'   => $category_idd,
									'category_name' => $cat_name,
									'total_target'	=> $targetvalue,
									'createdate'    => date('Y-m-d H:i:s'),
								);

								$insert = $this->target_model->ProductTarget_insert($data);

								foreach ($data_ as $key => $val) 
								{
									$product_id   = !empty($val->product_id)?$val->product_id:'0';
									$type_id      = !empty($val->type_id)?$val->type_id:'0';
									$target_val   = !empty($val->target_val)?$val->target_val:'0';
									$description  = !empty($val->description)?$val->description:'0';
									$category_idd = !empty($val->category_id)?$val->category_id:'';

									// Month Details
									$where_5  = array('month_value' => $month_id);
									$column_5 = 'month_name';
									$data_5   = $this->commom_model->getMonth($where_5, '', '', 'result', '', '', '', '', $column_5);
									$mon_name = !empty($data_5[0]->month_name)?$data_5[0]->month_name:'';

									//Year Details
									$where_1  = array('id' => $year_id);
									$column_1 = 'year_value';
									$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
									$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';


									$str_data = array(
										'target_id'	  => $insert,
										'template_id' => $template_id,
										'emp_id'      => $empvaluee,
										'category_id' => $category_idd,
										'product_id'  => $product_id,
										'type_id'     => $type_id,
										'description' => $description,
										'month_id'    => $month_id,
										'month_name'  => $mon_name,
										'year_name'   => $year_val,
										'target_val'  => $target_val,
										'createdate'  => date('Y-m-d H:i:s'),
									);

									$target_insert = $this->target_model->ProductTargetDetails_insert($str_data); 
								}
							}
						
							if($target_insert)
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
							$response['message'] = "Invalid Value"; 
							$response['data']    = [];
							echo json_encode($response);
							return; 
						}
					}
			    }
				else
				{
					$response['status']  = 0;
					$response['message'] = "Invalid Value"; 
					$response['data']    = [];
					echo json_encode($response);
					return; 
				}
			}

			else if($method == '_getEmployeeList')
			{
				$month_id = $this->input->post('month_id');
				$year_id  = $this->input->post('year_id');

				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id');
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
			    	// Year Details
			    	$where_1  = array('id' => $year_id);
			    	$column_1 = 'year_value';
			    	$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
			    	$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';

			    	// Employee List
			    	$where_2 = array(
			    		'month_id'  => $month_id,
			    		'year_name' => $year_val,
			    		'published' => '1',
			    	);

			    	$column_2 = 'employee_id, target_val';

			    	$data_2   = $this->target_model->getTargetDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

			    	if($data_2)
			    	{	
			    		$employee_list = [];
			    		foreach ($data_2 as $key => $val_2) {
			    				
			    			$employee_id = !empty($val_2->employee_id)?$val_2->employee_id:'';
							$target_val  = !empty($val_2->target_val)?$val_2->target_val:'0';

							// Employee Details
							$where_3  = array('id' => $employee_id);
							$column_3 = 'first_name,last_name';
							$data_3   = $this->employee_model->getEmployee($where_3, '', '', 'result', '', '', '', '', $column_3);
							$first_name = !empty($data_3[0]->first_name)?$data_3[0]->first_name:'';
							$last_name = !empty($data_3[0]->last_name)?$data_3[0]->last_name:'';
							$arr = array($first_name,$last_name);
							$emp_name =join(" ",$arr);
							$employee_list[] = array(
								'employee_id' => $employee_id,
								'emp_name'    => $emp_name,
								'target_val'  => $target_val,
							);
			    		}

			    		$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $employee_list;
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

			else if($method == '_template_list')
			{
				$financial_idd = $this->input->post('financial_id');


				$where_2 = array(
					'status'  => '1',
					'published' => '1',
					'financial_year' => $financial_idd,
				);

				$data_2   = $this->target_model->getProducttempalteTarget($where_2, '', '', 'result', '', '', '', '',);

				if($data_2)
				{	
					$employee_list = [];
					foreach ($data_2 as $key => $val_2) {
							
						$templatename = !empty($val_2->templatename)?$val_2->templatename:'';
						$id  = !empty($val_2->id)?$val_2->id:'0';
						// Employee Details
						// $where_3  = array('id' => $employee_id);
						// $column_3 = 'first_name,last_name';
						// $data_3   = $this->employee_model->getEmployee($where_3, '', '', 'result', '', '', '', '', $column_3);
						// $first_name = !empty($data_3[0]->first_name)?$data_3[0]->first_name:'';
						// $last_name = !empty($data_3[0]->last_name)?$data_3[0]->last_name:'';
						// $arr = array($first_name,$last_name);
						// $emp_name =join(" ",$arr);
						$employee_list[] = array(
							'id' => $id,
							'templatename'    => $templatename,
						);
					}


					$response['status']  = 1;
					$response['message'] = "Success"; 
					$response['data']    = $employee_list;
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

			if($method == '_listYear')
			{

				$option['order_by']   = 'id';
				$option['disp_order'] = 'DESC';
				$where = array('status'=>'1', 'published'=>'1');

				$data_list = $this->commom_model->getYear($where, '', '', 'result', '', '', '',$option);

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

		// average sales Target
		// ***************************************************
		public function average_sales($param1="",$param2="",$param3="")
		{
			$method       = $this->input->post('method');
			$month_id     = $this->input->post('month_id');
			$year_id      = $this->input->post('year_id');
			$employee_id  = $this->input->post('employee_id');
			$category_id  = $this->input->post('category_id');
			$financial_id = $this->input->post('financial_id');
			$setaveragenxt  = $this->input->post('setaveragenxt');

			// if($method == '_averageSalesProductarget')
			// {
			// 	$error = FALSE;
			//     $errors = array();
			// 	$required = array('month_id', 'year_id', 'category_id', 'employee_id','financial_id');
			//     foreach ($required as $field) 
			//     {
			//         if(empty($this->input->post($field)))
			//         {
			//             $error = TRUE;
			//         }
			//     }

			//     if($error)
			//     {
			//         $response['status']  = 0;
			//         $response['message'] = "Please fill all required fields"; 
			//         $response['data']    = [];
			//         echo json_encode($response);
			//         return; 
			//     }

			//     if(count($errors)==0)
			//     {
			// 		$type_val  = explode(',', $employee_id);
			// 		foreach ($type_val as $key => $vall) 
			// 		{
			// 			$empvalue = $vall;
					
			// 			$where_1  = array('id' => $year_id);
			// 			$column_1 = 'year_value';
			// 			$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
			// 			$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';

			// 			$where=array(
			// 				'month_id'    => $month_id,
			// 				'year_name'   => $year_val,
			// 				'emp_id'      => $empvalue,
			// 				'category_id' => $category_id,
			// 				'status'      => '1',
			// 				'published'   => '1',
			// 			);			   
			// 			$column = 'id';
			// 			$overalldatas = $this->target_model->getProductTargetDetails($where, '', '', 'result', '', '', '', '', $column);
						
			// 			if(!empty($overalldatas))
			// 			{
			// 			$response['status']  = 0;
			// 			$response['message'] = "Data Already Exist"; 
			// 			$response['data']    = [];
			// 			echo json_encode($response);
			// 			return; 
			// 		}
			// 		}
			// 		if(!empty($overalldatas))
			// 			{
			// 			$response['status']  = 0;
			// 			$response['message'] = "Data Already Exist"; 
			// 			$response['data']    = [];
			// 			echo json_encode($response);
			// 			return; 
			// 		}
			// 		else
			// 		{
			// 			if(!empty($category_id))
			// 			{
			// 				$type_vall  = explode(',', $employee_id);
			// 				foreach ($type_vall as $key => $valll) 
			// 				{
			// 					$empvaluee = $valll;
							
			// 				// Employee Details
			// 				$where_4  = array('id' => $empvaluee);
			// 				$column_4 = 'first_name,last_name';
			// 				$data_4   = $this->employee_model->getEmployee($where_4, '', '', 'result', '', '', '', '', $column_4);
			// 				$first_name = !empty($data_4[0]->first_name)?$data_4[0]->first_name:'';
			// 				$last_name = !empty($data_4[0]->last_name)?$data_4[0]->last_name:'';

			// 				$arr = array($first_name,$last_name);
			// 				$emp_name =join(" ",$arr);

			// 				// Category Details
			// 				$where_3  = array('id' => $category_id);
			// 				$column_3 = 'name';
			// 				$data_3   = $this->commom_model->getCategory($where_3, '', '', 'result', '', '', '', '', $column_3);
			// 				$cat_name = !empty($data_3[0]->name)?$data_3[0]->name:'';


			// 				$where_  = array( 'category_id'=> $category_id,'month_id'=>$month_id,'year_id'=>$year_id);
			// 				$data_   = $this->target_model->getProductAssignTarget($where_, '', '', 'result', '', '', '', '',);

			// 				$mnth_id   = !empty($data_[0]->month_id)?$data_[0]->month_id:'';
			// 				$mnth_name = !empty($data_[0]->month_name)?$data_[0]->month_name:'';
			// 				$yer_id    = !empty($data_[0]->year_id)?$data_[0]->year_id:'';
			// 				$yer_name  = !empty($data_[0]->year_name)?$data_[0]->year_name:'';


			// 				$data = array(
			// 					'month_id'      => $mnth_id,
			// 					'month_name'    => $mnth_name,
			// 					'year_id'       => $yer_id,
			// 					'year_name'    	=> $yer_name,
			// 					'emp_id'        => $empvaluee,
			// 					'emp_name'      => $emp_name,
			// 					'category_id'   => $category_id,
			// 					'category_name' => $cat_name,
			// 					'createdate'    => date('Y-m-d H:i:s'),
			// 				);

			// 				$insert = $this->target_model->ProductTarget_insert($data);

			// 				foreach ($data_ as $key => $val) 
			// 				{
			// 					$product_id  = !empty($val->product_id)?$val->product_id:'0';
			// 					$type_id     = !empty($val->type_id)?$val->type_id:'0';
			// 					$target_val  = !empty($val->target_val)?$val->target_val:'0';
			// 					$description = !empty($val->description)?$val->description:'0';
			// 					$month_id    = !empty($val->month_id)?$val->month_id:'0';
			// 					$month_name  = !empty($val->month_name)?$val->month_name:'0';
			// 					$year_id     = !empty($val->year_id)?$val->year_id:'0';
			// 					$year_name   = !empty($val->year_name)?$val->year_name:'0';
							

			// 					$str_data = array(
			// 						'target_id'	  => $insert,
			// 						'emp_id'      => $empvaluee,
			// 						'category_id' => $category_id,
			// 						'product_id'  => $product_id,
			// 						'type_id'     => $type_id,
			// 						'description' => $description,
			// 						'month_id'    => $month_id,
			// 						'month_name'  => $month_name,
			// 						'year_name'   => $year_name,
			// 						'target_val'  => $target_val,
			// 						'createdate'  => date('Y-m-d H:i:s'),
			// 					);

			// 					$target_insert = $this->target_model->ProductTargetDetails_insert($str_data); 
			// 					}
			// 				}
						
			// 				if($target_insert)
			// 				{
			// 					$response['status']  = 1;
			// 					$response['message'] = "Success"; 
			// 					$response['data']    = [];
			// 					echo json_encode($response);
			// 					return; 
			// 				}
			// 				else
			// 				{
			// 					$response['status']  = 0;
			// 					$response['message'] = "Not Success"; 
			// 					$response['data']    = [];
			// 					echo json_encode($response);
			// 					return; 
			// 				}
			// 			}
					
			// 			else
			// 			{
			// 				$response['status']  = 0;
			// 				$response['message'] = "Invalid Value"; 
			// 				$response['data']    = [];
			// 				echo json_encode($response);
			// 				return; 
			// 			}
			// 		}
			//     }
			// }
			
			if($method == '_averageSalesProductarget')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id', 'category_id', 'employee_id','financial_id','setaveragenxt');
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

					$type_vall  = explode(',', $month_id);
					$lastmonth_value =  end($type_vall);

					// Year Details
					$where_1  = array('id' => $year_id);
					$column_1 = 'year_value';
					$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
					$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';
					

					$date = $year_val."-".$lastmonth_value."-01";
					
					$new_date = date('m', strtotime($date. ' + 1 months')); 
							
					$where_1  = array('id' => $year_id);
					$column_1 = 'year_value';
					$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
					$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';

						$where=array(
							'month_id'    => $new_date,
							'year_name'   => $year_val,
							'emp_id'      => $employee_id,
							'category_id' => $category_id,
							'status'      => '1',
							'published'   => '1',
						);			   
						$column = 'id';
						$overalldatas = $this->target_model->getProductTargetDetails($where, '', '', 'result', '', '', '', '', $column);
					}
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
						if(!empty($category_id))
						{
							
							$type_vall  = explode(',', $month_id);
							$lastmonth_value =  end($type_vall);

							// Year Details
							$where_1  = array('id' => $year_id);
							$column_1 = 'year_value';
							$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
							$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';
							

							$date = $year_val."-".$lastmonth_value."-01";
							
							$new_date = date('m', strtotime($date. ' + 1 months')); 
							
							// Employee Details
							$where_4  = array('id' => $employee_id);
							$column_4 = 'first_name,last_name';
							$data_4   = $this->employee_model->getEmployee($where_4, '', '', 'result', '', '', '', '', $column_4);
							$first_name = !empty($data_4[0]->first_name)?$data_4[0]->first_name:'';
							$last_name = !empty($data_4[0]->last_name)?$data_4[0]->last_name:'';

							$arr = array($first_name,$last_name);
							$emp_name =join(" ",$arr);

							// Category Details
							$where_3  = array('id' => $category_id);
							$column_3 = 'name';
							$data_3   = $this->commom_model->getCategory($where_3, '', '', 'result', '', '', '', '', $column_3);
							$cat_name = !empty($data_3[0]->name)?$data_3[0]->name:'';



							// Month Details
							$where_5  = array('month_value' => $new_date);
							$column_5 = 'month_name';
							$data_5   = $this->commom_model->getMonth($where_5, '', '', 'result', '', '', '', '', $column_5);
							$mon_name = !empty($data_5[0]->month_name)?$data_5[0]->month_name:'';


							$where=array(
								'month_id'    => $lastmonth_value,
								'year_name'   => $year_val,
								'emp_id'      => $employee_id,
								'category_id' => $category_id,
								'status'      => '1',
								'published'   => '1',
							);			   
							$overalldatas = $this->target_model->getProductTargetDetails($where, '', '', 'result', '', '', '', '', );

							$where_1  = array('id' => $year_id);
							$column_1 = 'year_value';
							$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
							$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';
							
							// Category Details
							$where_3  = array('id' => $category_id);
							$column_3 = 'name';
							$data_3   = $this->commom_model->getCategory($where_3, '', '', 'result', '', '', '', '', $column_3);
							$cat_name = !empty($data_3[0]->name)?$data_3[0]->name:'';

							// Product id
							$data_ssd=[];
							$where_pii  = array('category_id' => $category_id,'financial_year'=>$financial_id, 'status'=>'1','published'=>'1');
							$column_pi = 'product_id';
							$data_pi   = $this->target_model->getProductAssignTarget($where_pii, '', '', 'result', '', '', '', '', $column_pi);
							if(empty($data_pi)) 
							{
								$response['status']  = 0;
								$response['message'] = "Data Not Found"; 
								$response['data']    = [];
								$response['target_data'] = [];
								$response['avg_valuee']  = [];
								echo json_encode($response);
								return;
							}
						
							foreach ($data_pi as $value) 
							{
								$data_ssd[]= $value;
							}

							$prd_val =[];
							foreach ($data_ssd as $key => $value) 
							{
								$product_id = !empty($value->product_id)?$value->product_id:'';
								$prd_val[]=$product_id;
							}

							$product_idd = array_unique($prd_val);
							$pproo =[];
							$pro = array();
							foreach ($product_idd as $key => $value) 
							{
								$pro=array(
									'value' => $value,
								);
								$pproo[]=$pro;
							}
							
							$finall_value=[];
							
							foreach($pproo as $key => $value)
							{
								$p = $value['value'];

								$where_pi  = array('id' => $p);
								$data_pi   = $this->commom_model->getProduct($where_pi, '', '', 'result', '', '', '', '');
								$prd_name = !empty($data_pi[0]->name)?$data_pi[0]->name:'';

								$averagefullvalue=[];
								$achieve_val=[];
								$averageeda =[];

								$type_valu  = explode(',', $month_id);
								foreach ($type_valu as $key => $valll) 
								{
									$monthvaluee = $valll;

									$where1 = array(
									'month_id'    => $monthvaluee,
									'year_name'   => $year_val,
									'emp_id'      => $employee_id,
									'category_id' => $category_id,
									'product_id'  => $p,
									'status'      => '1',
									'published'   => '1',
									);
									$averageedata = $this->target_model->getProductTargetDetails($where1, '', '', 'result', '', '', '', '');						
									
									if(!empty($averageedata)) 
									{
										foreach ($averageedata as $value1) 
										{
											$averageeda []= $value1;
											
										}
										
									}
									
									else
									{
										$response['status']  = 0;
										$response['message'] = "Data Not Found"; 
										$response['data']    = [];
										$response['target_data'] = [];
										$response['avg_valuee']  = [];
										echo json_encode($response);
										return;
									}	
									
								}

								$month_name1 = !empty($averageeda[0]->month_name)?$averageeda[0]->month_name:'';
								$month_nam2 = !empty($averageeda[1]->month_name)?$averageeda[1]->month_name:'';
								$month_nam3 = !empty($averageeda[2]->month_name)?$averageeda[2]->month_name:'';


								$month_target1 = !empty($averageeda[0]->target_val)?$averageeda[0]->target_val:'';
								$month_target1 = !empty($averageeda[0]->target_val)?$averageeda[0]->target_val:'';
								$month_target1 = !empty($averageeda[0]->target_val)?$averageeda[0]->target_val:'';
								
								foreach ($averageeda as $key => $value1) 
								{
									
									$achieve_vall  = !empty($value1->achieve_val)?$value1->achieve_val:'';
									$achieve_val[] = $achieve_vall;
								}
								
								
								
								$averagefullvalue = array_sum($achieve_val);
								$avaerageroundedvalue = round(($averagefullvalue/3*$setaveragenxt));
								
								
								$averagefullval   = array(
										'averagevalue' => $avaerageroundedvalue,
										'description'  => $prd_name,
										);
								$finall_value[]=$averagefullval;
								


							}
							
							$total_val  = 0;
							foreach ($finall_value as $key => $val) {

								$target_amt = !empty($val['averagevalue'])?$val['averagevalue']:'0';
								
								$total_val += $target_amt;
							}
							
									
							$data = array(
								'month_id'      => $new_date,
								'month_name'    => $mon_name,
								'year_id'       => $year_id,
								'year_name'    	=> $year_val,
								'emp_id'        => $employee_id,
								'emp_name'      => $emp_name,
								'category_id'   => $category_id,
								'category_name' => $cat_name,
								'total_target'	=> $total_val,
								'createdate'    => date('Y-m-d H:i:s'),
							);


							$insert = $this->target_model->ProductTarget_insert($data);				

							$target_am=[];
							foreach ($finall_value as $key => $val) {

								$target_am[] = !empty($val['averagevalue'])?$val['averagevalue']:'0';
							}
							
							foreach ($overalldatas as $key => $val) 
							{
							$product_id  = !empty($val->product_id)?$val->product_id:'0';
							$type_id     = !empty($val->type_id)?$val->type_id:'0';
							$target_val  = !empty($val->target_am)?$val->target_am:'0';
							$description = !empty($val->description)?$val->description:'0';
							$month_id    = !empty($val->month_id)?$val->month_id:'0';
							$month_name  = !empty($val->month_name)?$val->month_name:'0';
							$year_id     = !empty($val->year_id)?$val->year_id:'0';
							$year_name   = !empty($val->year_name)?$val->year_name:'0';
						
							
							$str_data = array(
								'target_id'	  => $insert,
								'emp_id'      => $employee_id,
								'category_id' => $category_id,
								'product_id'  => $product_id,
								'type_id'     => $type_id,
								'description' => $description,
								'month_id'    => $new_date,
								'month_name'  => $mon_name,
								'year_name'   => $year_val,
								'target_val'  => ($target_am[$key])?$target_am[$key]:'0',
								'createdate'  => date('Y-m-d H:i:s'),
							);
							
							$target_insert = $this->target_model->ProductTargetDetails_insert($str_data); 
						
							}
								
							
							if($target_insert)
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
							$response['message'] = "Invalid Value"; 
							$response['data']    = [];
							echo json_encode($response);
							return; 
						}
					// }
			    }
			}

			else if($method == '_listAverageTarget')
			{
				
				$month_id = $this->input->post('month_id');
				$year_id  = $this->input->post('year_id');
				$employee_id  = $this->input->post('employee_id');
				$category_id  = $this->input->post('category_id');
				$financial_id = $this->input->post('financial_id');

				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id','employee_id','category_id','financial_id');
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
					
					$monthvalue  = explode(',', $month_id);
					$count_month = count($monthvalue);
					
					if($count_month == 3)
					{
						// Year Details
						$where_1  = array('id' => $year_id);
						$column_1 = 'year_value';
						$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
						$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';
						
						// Category Details
						$where_3  = array('id' => $category_id);
						$column_3 = 'name';
						$data_3   = $this->commom_model->getCategory($where_3, '', '', 'result', '', '', '', '', $column_3);
						$cat_name = !empty($data_3[0]->name)?$data_3[0]->name:'';

						// Product id
						$data_ssd=[];
						$where_pii  = array('category_id' => $category_id,'financial_year'=>$financial_id, 'status'=>'1','published'=>'1');
						$column_pi = 'product_id';
						$data_pi   = $this->target_model->getProductAssignTarget($where_pii, '', '', 'result', '', '', '', '', $column_pi);
						if(empty($data_pi)) 
						{
							$response['status']  = 0;
							$response['message'] = "Data Not Found"; 
							$response['data']    = [];
							$response['target_data'] = [];
							$response['avg_valuee']  = [];
							echo json_encode($response);
							return;
						}
					
						foreach ($data_pi as $value) 
						{
							$data_ssd[]= $value;
						}

						$prd_val =[];
						foreach ($data_ssd as $key => $value) 
						{
							$product_id = !empty($value->product_id)?$value->product_id:'';
							$prd_val[]=$product_id;
						}

						$product_idd = array_unique($prd_val);
						$pproo =[];
						$pro = array();
						foreach ($product_idd as $key => $value) 
						{
							$pro=array(
								'value' => $value,
							);
							$pproo[]=$pro;
						}
						
						$finall_value=[];
						
						foreach($pproo as $key => $value)
						{
							$p = $value['value'];

							$where_pi  = array('id' => $p);
							$data_pi   = $this->commom_model->getProduct($where_pi, '', '', 'result', '', '', '', '');
							$prd_name = !empty($data_pi[0]->name)?$data_pi[0]->name:'';

							$averagefullvalue=[];
							$achieve_val=[];
							$averageeda =[];

							$type_valu  = explode(',', $month_id);
							foreach ($type_valu as $key => $valll) 
							{
								$monthvaluee = $valll;

								$where1 = array(
								'month_id'    => $monthvaluee,
								'year_name'   => $year_val,
								'emp_id'      => $employee_id,
								'category_id' => $category_id,
								'product_id'  => $p,
								'status'      => '1',
								'published'   => '1',
								);
								$averageedata = $this->target_model->getProductTargetDetails($where1, '', '', 'result', '', '', '', '');						
								
								if(!empty($averageedata)) 
								{
									foreach ($averageedata as $value1) 
									{
										$averageeda []= $value1;
										
									}
									
							
								}
								
								else
								{
									$response['status']  = 0;
									$response['message'] = "Data Not Found"; 
									$response['data']    = [];
									$response['target_data'] = [];
									$response['avg_valuee']  = [];
									echo json_encode($response);
									return;
								}	
								
							}

							$month_name1 = !empty($averageeda[0]->month_name)?$averageeda[0]->month_name:'';
							$month_nam2 = !empty($averageeda[1]->month_name)?$averageeda[1]->month_name:'';
							$month_nam3 = !empty($averageeda[2]->month_name)?$averageeda[2]->month_name:'';


							$month_target1 = !empty($averageeda[0]->target_val)?$averageeda[0]->target_val:'';
							$month_target1 = !empty($averageeda[0]->target_val)?$averageeda[0]->target_val:'';
							$month_target1 = !empty($averageeda[0]->target_val)?$averageeda[0]->target_val:'';


							// print_r($month_target1);
							// print_r($month_target1);
							// print_r($month_nam3);

							// exit;
							
							foreach ($averageeda as $key => $value1) 
							{
								
								// $monthname = !empty($value1->month_name)?$value1->month_name:'';
								// $monthtarget  = !empty($value1->target_val)?$value1->target_val:'';
								// print_r($monthname);
								$achieve_vall  = !empty($value1->achieve_val)?$value1->achieve_val:'0';
								$achieve_val[] = $achieve_vall;
							}
							
							// print_r($achieve_val);
							// exit;

							
							$averagefullvalue = array_sum($achieve_val);
							$averagefullval   = array(
									'averagevalue' => $averagefullvalue,
									'description'  => $prd_name,
									);
							$finall_value[]=$averagefullval;
							

						}
						
						$type_vall  = explode(',', $month_id);
						$sur = [];
						$targetdata = [];
						foreach ($type_vall as $key => $valll) 
						{
							$monthvaluee = $valll;

							$where=array(
								'month_id'    => $monthvaluee,
								'year_name'   => $year_val,
								'emp_id'      => $employee_id,
								'category_id' => $category_id,
								'status'      => '1',
								'published'   => '1',
							);	
							
							$overalldatas = $this->target_model->getProductTargetDetails($where, '', '', 'result', '', '', '', '');					
							
							$targetdatas = $this->target_model->getProductTarget($where, '', '', 'result', '', '', '', '');						
							if(!empty($targetdatas)) 
							{
								foreach ($targetdatas as $value1) 
								{
									$targetdata[] = $value1;
								}
							}
							else
							{
								$response['status']  = 0;
								$response['message'] = "Data Not Found"; 
								$response['data']    = [];
								$response['target_data'] = [];
								$response['avg_valuee']  = [];
								echo json_encode($response);
								return;
							}		
							
							if(!empty($overalldatas)) 
							{
								foreach ($overalldatas as $value) 
								{
									$sur[] = $value;
								}
							}
							else
							{
								$response['status']  = 0;
								$response['message'] = "Data Not Found"; 
								$response['data']    = [];
								$response['target_data'] = [];
								$response['avg_valuee']  = [];
								echo json_encode($response);
								return;
							}	
						}
						
						if($sur && $targetdata)
						{	
							$average_list = [];
							foreach ($sur as $key => $val_2) {
									
								$employee_id = !empty($val_2->emp_id)?$val_2->emp_id:'';
								$target_val  = !empty($val_2->target_val)?$val_2->target_val:'0';
								$category_id = !empty($val_2->category_id)?$val_2->category_id:'';
								$product_id  = !empty($val_2->product_id)?$val_2->product_id:'';
								$type_id     = !empty($val_2->type_id)?$val_2->type_id:'';
								$description = !empty($val_2->description)?$val_2->description:'';
								$month_id    = !empty($val_2->month_id)?$val_2->month_id:'';
								$month_name  = !empty($val_2->month_name)?$val_2->month_name:'';
								$year_name   = !empty($val_2->year_name)?$val_2->year_name:'';
								$achieve_val = !empty($val_2->achieve_val)?$val_2->achieve_val:'';
								$published   = !empty($val_2->published)?$val_2->published:'';
								$status      = !empty($val_2->status)?$val_2->status:'';

								$average_list[] = array(
									'emp_id'        => $employee_id,
									'target_val'    => $target_val,
									'category_id'   => $category_id,
									'product_id'    => $product_id,
									'type_id'       => $type_id,
									'description'   => $description,
									'month_id'      => $month_id,
									'month_name'    => $month_name,
									'year_name'     => $year_name,
									'achieve_val'   => $achieve_val,
									'category_name' => $cat_name,
									'published'     => $published,
									'status'        => $status,
	
								);
							}

							// $null = $this->groupBy($average_list, 'description');

							$name = [];
							$month = [];
							foreach ($average_list as $key => $value) {
								if(!in_array($value['description'], $name)) {
									$name[] = $value['description'];
								}
								if(!in_array($value['month_name'], $month)) {
									$month[] = $value['month_name'];
								}
							}

							
							$dhan  = [];
							foreach ($name as $n_) {
								$a = array("description"=>$n_);
								// foreach ($variable as $key => $value) {
								// 	# code...
								// }
								foreach ($month as $key => $month_) {
									$var =$this->searchFor($month_, $n_,  $average_list);
									$a['category_name'] = $var['category_name'];
									// $a[$month_] = $var['target_val'];
									$a['m'.($key+1)] = $var['target_val'];
									$a['a'.($key+1)] = !empty($var['achieve_val'])?$var['achieve_val']:'0';
									
								}
								array_push($dhan,$a);
							}

							// category_name

							
							$targetdata_list = [];
							foreach ($targetdata as $key => $val_1) 
							{
								$total_target  = !empty($val_1->total_target)?$val_1->total_target:'0';
								$month_name_list = !empty($val_1->month_name)?$val_1->month_name:'0';

								$targetdata_list[] = array(
									'total_target'  => $total_target,
									'month_name'    => $month_name_list,
								);
							}
							// print_r($dhan);
							// exit;

							if($finall_value)
							{
								$response['status']  	 = 1;
								$response['message'] 	 = "Success"; 
								$response['data']    	 = $dhan;
								$response['target_data'] = $targetdata_list;
								$response['avg_valuee']  = $finall_value;
								// $response['achieve_val']  = $achieve_val;
								echo json_encode($response);
								return;
							}
							else
							{
								$response['status']  = 0;
								$response['message'] = "Data Not Found"; 
								$response['data']    = [];
								$response['target_data'] = [];
								$response['avg_valuee']  = [];
								echo json_encode($response);
								return;
							}
						}
						else
						{
							$response['status']  = 0;
							$response['message'] = "Invalid Value"; 
							$response['data']    = [];
							$response['target_data'] = [];
							$response['avg_valuee']  = [];
							echo json_encode($response);
							return; 
						}
					}
					else
					{
						$response['status']  = 0;
						$response['message'] = "Invalid Value"; 
						$response['data']    = [];
						$response['target_data'] = [];
						$response['avg_valuee']  = [];
						echo json_encode($response);
						return; 
					}
				}
			}
		}

		public function searchFor($month_name, $description,  $array) 
		{
			foreach ($array as $val) 
			{
				if ($val['month_name'] == $month_name && $val['description']==$description) {
					return $val;
				}
			}
		}

		// Add Product Target
		// ***************************************************
		public function product_target($param1="",$param2="",$param3="")
		{
			$method       = $this->input->post('method');
			$target_id    = $this->input->post('target_id');
			$month_id     = $this->input->post('month_id');
			$year_id      = $this->input->post('year_id');
			$employee_id  = $this->input->post('employee_id');
			$category_id  = $this->input->post('category_id');
			$target_value = $this->input->post('target_value');
			$financial_id = $this->input->post('financial_id');
			$sub_cat_id   = $this->input->post('sub_cat_id');
			
			if($method == '_addProductTarget')
			{
				
				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id', 'category_id','employee_id', 'financial_id', 'target_value');
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
			    	$where=array(
			    		'month_id'    => $month_id,
				    	'year_id'     => $year_id,
				    	'emp_id'      => $employee_id,
				    	'category_id' => $category_id,
				    	'status'      => '1',
				    	'published'   => '1',
				    );			   

					
					$column = 'id';

					$overalldatas = $this->target_model->getProductTarget($where, '', '', 'result', '', '', '', '', $column);

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
						// Total Value
						$target_res = json_decode($target_value);	
						$total_val  = 0;
						foreach ($target_res as $key => $val) {
							$target_amt = !empty($val->target_val)?$val->target_val:'0';
							$total_val += $target_amt;
					    }
						// Current Month Target Value
				    	$where_1  = array('id' => $year_id);
				    	$column_1 = 'year_value';
				    	$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
				    	$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';

				    	$where_2 = array(
				    		'employee_id' => $employee_id,
				    		'month_id'    => $month_id,
				    		'year_name'   => $year_val,
				    		'published'   => '1',
				    	);

				    	$column_2 = 'target_val';

			    		$data_2   = $this->target_model->getTargetDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

			    		$target_tot = !empty($data_2[0]->target_val)?$data_2[0]->target_val:'0';

			    		if($target_tot >= $total_val)
			    		{
			    			// Category Details
			    			$where_3  = array('id' => $category_id);
					    	$column_3 = 'name';
					    	$data_3   = $this->commom_model->getCategory($where_3, '', '', 'result', '', '', '', '', $column_3);
					    	$cat_name = !empty($data_3[0]->name)?$data_3[0]->name:'';

			    			// Employee Details
			    			$where_4  = array('id' => $employee_id);
							$column_4 = 'first_name,last_name';
							$data_4   = $this->employee_model->getEmployee($where_4, '', '', 'result', '', '', '', '', $column_4);
							$first_name = !empty($data_4[0]->first_name)?$data_4[0]->first_name:'';
							$last_name = !empty($data_4[0]->last_name)?$data_4[0]->last_name:'';

							$arr = array($first_name,$last_name);
							$emp_name =join(" ",$arr);

							// Month Details
			    			$where_5  = array('month_value' => $month_id);
							$column_5 = 'month_name';
							$data_5   = $this->commom_model->getMonth($where_5, '', '', 'result', '', '', '', '', $column_5);
							$mon_name = !empty($data_5[0]->month_name)?$data_5[0]->month_name:'';

							$total_targetvalue=[];
							$target_res = json_decode($target_value);	

							foreach ($target_res as $key => $val) {
							    $target_val = !empty($val->target_val)?$val->target_val:'0';
								$total_targetvalue[]=$target_val;
							}
							$total_target = array_sum($total_targetvalue);
						
							$data = array(
								'month_id'      => $month_id,
								'month_name'    => $mon_name,
						    	'year_id'       => $year_id,
						    	'year_name'     => $year_val,
						    	'emp_id'        => $employee_id,
						    	'emp_name'      => $emp_name,
						    	'category_id'   => $category_id,
						    	'category_name' => $cat_name,
								'total_target'    => $total_target,
						    	'createdate'    => date('Y-m-d H:i:s'),
						    );
							
						    $insert = $this->target_model->ProductTarget_insert($data);

							foreach ($target_res as $key => $val) {
								$product_id = !empty($val->product_id)?$val->product_id:'0';
							    $type_id    = !empty($val->type_id)?$val->type_id:'0';
							    $target_val = !empty($val->target_val)?$val->target_val:'0';

							    // Employee Details
				    			$where_6  = array('id' => $type_id);
								$column_6 = 'description,sub_cat_id';
								$data_6   = $this->commom_model->getProductType($where_6, '', '', 'result', '', '', '', '', $column_6);
								$pdt_desc = !empty($data_6[0]->description)?$data_6[0]->description:'';
								$sub_cat_id = !empty($data_6[0]->sub_cat_id)?$data_6[0]->sub_cat_id:'';
							    $str_data = array(
							    	'target_id'   => $insert,
									'sub_cat_id'  => $sub_cat_id,
							    	'emp_id'      => $employee_id,
							    	'category_id' => $category_id,
							    	'product_id'  => $product_id,
							    	'type_id'     => $type_id,
							    	'description' => $pdt_desc,
							    	'month_id'    => $month_id,
							    	'month_name'  => $mon_name,
							    	'year_name'   => $year_val,
							    	'target_val'  => $target_val,
							    	'createdate'  => date('Y-m-d H:i:s'),
							    );
							    $target_insert = $this->target_model->ProductTargetDetails_insert($str_data);
							}

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
					        $response['message'] = "Invalid Value"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
			    		}

					}
			    }
			}

			else if($method == '_manageProductTarget')
			{
				
				$limit       = $this->input->post('limit');
	    		$offset      = $this->input->post('offset');
	    		$financ_year = $this->input->post('financial_year');

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
	    			$like['name']     = $search;
	    			$where = array('published'    => '1');
	    		}
	    		else
	    		{
	    			$like  = [];
	    			$where = array('published'    => '1');
	    		}

	    		$column_1 = 'id';
				$overalldatas = $this->target_model->getProductTarget($where, '', '', 'result', $like, '', '', '', $column_1);

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

				$column_2  = 'id, month_name, year_name, emp_name, total_target ,category_name, sub_cat_name, status, createdate';
				$data_list = $this->target_model->getProductTarget($where, $limit, $offset, 'result', $like, '', $option, '', $column_2);

				if($data_list)
				{
					$assign_list = [];
					foreach ($data_list as $key => $value) {
							
						$target_id     = !empty($value->id)?$value->id:'';
						$month_name    = !empty($value->month_name)?$value->month_name:'';
						$year_value    = !empty($value->year_name)?$value->year_name:'';
						$emp_name      = !empty($value->emp_name)?$value->emp_name:'';
						$sub_cat_name  = !empty($value->sub_cat_name)?$value->sub_cat_name:'';
						$category_name = !empty($value->category_name)?$value->category_name:'';
						$total_target  = !empty($value->total_target)?$value->total_target:'';
						$status        = !empty($value->status)?$value->status:'';
						$createdate    = !empty($value->createdate)?$value->createdate:'';

						$assign_list[] = array(
							'target_id'     => $target_id,
							'month_name'    => $month_name,
							'year_value'    => $year_value,
							'emp_name'      => $emp_name,
							'sub_cat_name'  => $sub_cat_name,
							'category_name' => $category_name,
							'total_target'	=> $total_target,
							'status'     	=> $status,
							'createdate' 	=> date('d-m-Y H:i:s', strtotime($createdate)),
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
			        $response['data']         = $assign_list;
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

			else if($method == '_detailProductTarget')
			{
				if($target_id)
				{
					// Target Data
					$where_1  = array('id' => $target_id, 'published' => '1');
					$column_1 = 'id, month_id, year_id, emp_id, category_id, sub_cat_id, status';
					$data_1   = $this->target_model->getProductTarget($where_1, '', '', 'result', '', '', '', '', $column_1);

					if($data_1)
					{
						$target_id   = !empty($data_1[0]->id)?$data_1[0]->id:'';
						$month_id    = !empty($data_1[0]->month_id)?$data_1[0]->month_id:'';
						$year_id     = !empty($data_1[0]->year_id)?$data_1[0]->year_id:'';
						$employee_id = !empty($data_1[0]->emp_id)?$data_1[0]->emp_id:'';
						$sub_cat_id  = !empty($data_1[0]->sub_cat_id)?$data_1[0]->sub_cat_id:'';
						$category_id = !empty($data_1[0]->category_id)?$data_1[0]->category_id:'';
						$status      = !empty($data_1[0]->status)?$data_1[0]->status:'';

			            $target_data = array(
			            	'target_id'   => $target_id,
				            'month_id'    => $month_id,
				            'year_id'     => $year_id,
				            'employee_id' => $employee_id,
				            'category_id' => $category_id,
				            'status'      => $status,
			            );

			            // Target Details
			            $where_2  = array('target_id' => $target_id, 'published' => '1');
			            $column_2 = 'id, category_id, product_id, type_id, description, sub_cat_id, target_val';
			            $data_2   = $this->target_model->getProductTargetDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

			            $target_list = [];
			            if($data_2)
			            {
			            	foreach ($data_2 as $key => $val_2) {
			            		$auto_id     = !empty($val_2->id)?$val_2->id:'';
					            $category_id = !empty($val_2->category_id)?$val_2->category_id:'';
								$sub_cat_id  = !empty($val_2->sub_cat_id)?$val_2->sub_cat_id:'';
					            $product_id  = !empty($val_2->product_id)?$val_2->product_id:'';
					            $type_id     = !empty($val_2->type_id)?$val_2->type_id:'';
					            $description = !empty($val_2->description)?$val_2->description:'';
					            $target_val  = !empty($val_2->target_val)?$val_2->target_val:'';

					            $target_list[] = array(
					            	'auto_id'     => $auto_id,
						            'category_id' => $category_id,
						            'product_id'  => $product_id,
						            'type_id'     => $type_id,
									'sub_cat_id'  => $sub_cat_id,
						            'description' => $description,
						            'target_val'  => $target_val,
					            );
			            	}
			            }

			            $target_details = array(
			            	'target_data' => $target_data,
			            	'target_list' => $target_list,
			            );

			            $response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $target_details;
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
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
				}
			}

			else if($method == '_updateProductTarget')
			{

				$error = FALSE;
			    $errors = array();
				$required = array('target_id', 'month_id', 'year_id', 'employee_id', 'category_id', 'financial_id', 'target_value');
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
			    	$where=array(
			    		'id !='       => $target_id,
			    		'month_id'    => $month_id,
				    	'year_id'     => $year_id,
				    	'emp_id'      => $employee_id,
				    	'category_id' => $category_id,
				    	'published'   => '1',
				    );			   


					$column = 'id';

					$overalldatas = $this->target_model->getProductTarget($where, '', '', 'result', '', '', '', '', $column);

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
						// Total Value
						$target_res = json_decode($target_value);	
						$total_val  = 0;
						foreach ($target_res as $key => $val) {
							$target_amt = !empty($val->target_val)?$val->target_val:'0';
							$total_val += $target_amt;
					    }

						// Current Month Target Value
				    	$where_1  = array('id' => $year_id);
				    	$column_1 = 'year_value';
				    	$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
				    	$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';

				    	$where_2 = array(
				    		'employee_id' => $employee_id,
				    		'month_id'    => $month_id,
				    		'year_name'   => $year_val,
				    		'published'   => '1',
				    	);

				    	$column_2 = 'target_val';

			    		$data_2   = $this->target_model->getTargetDetails($where_2, '', '', 'result', '', '', '', '', $column_2);
			    		$target_tot = !empty($data_2[0]->target_val)?$data_2[0]->target_val:'0';

			    		if($target_tot >= $total_val)
			    		{
			    			// Employee Details
			    			$where_3  = array('id' => $employee_id);
							$column_3 = 'first_name,last_name';
							$data_3   = $this->employee_model->getEmployee($where_3, '', '', 'result', '', '', '', '', $column_3);
							$first_name = !empty($data_3[0]->first_name)?$data_3[0]->first_name:'';
							$last_name = !empty($data_3[0]->last_name)?$data_3[0]->last_name:'';
							$arr = array($first_name,$last_name);
							$emp_name =join(" ",$arr);
							$data = array(
						    	'emp_id'     => $employee_id,
						    	'emp_name'   => $emp_name,
								'total_target' => $total_val,
						    	'updatedate' => date('Y-m-d H:i:s'),
						    );
							
							
						    $update_id = array('id' => $target_id);
							
					    	$update    = $this->target_model->ProductTarget_update($data, $update_id);
							
					    	$del_val = array('published' => '0');
					    	$del_whr = array('target_id' => $target_id);
				    		$del_tar = $this->target_model->ProductTargetDetails_delete($del_val, $del_whr);
							
					    	// Total Value
							$target_res = json_decode($target_value);

							foreach ($target_res as $key => $val) 
							{
								
								$auto_id    = !empty($val->auto_id)?$val->auto_id:'0';
								$id_value   = !empty($val->id_value)?$val->id_value:'0';								
								$product_id = !empty($val->product_id)?$val->product_id:'0';
							    $type_id    = !empty($val->type_id)?$val->type_id:'0';
							    $target_val = !empty($val->target_val)?$val->target_val:'0';

							    // Employee Details
				    			$where_6  = array('id' => $type_id);
								$column_6 = 'description,sub_cat_id';
								$data_6   = $this->commom_model->getProductType($where_6, '', '', 'result', '', '', '', '', $column_6);
								$pdt_desc = !empty($data_6[0]->description)?$data_6[0]->description:'';
								$sub_cat_id = !empty($data_6[0]->sub_cat_id)?$data_6[0]->sub_cat_id:'';
							    $str_data = array(
									'sub_cat_id'  => $sub_cat_id,
							    	'target_id'   => $target_id,
							    	'product_id'  => $product_id,
							    	'type_id'     => $type_id,
							    	'description' => $pdt_desc,
							    	'target_val'  => $target_val,
							    	'published'   => '1',
									'updatedate'  => date('Y-m-d H:i:s'),
							   	);
								   
									$upt_whr = array('id'=> $id_value ,'emp_id' => $employee_id,'target_id' => $target_id);
									
									$update_det = $this->target_model->ProductTargetDetails_update($str_data,$upt_whr);
								
							}	
							
							
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
					        $response['message'] = "Invalid Value"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
			    		}
					}
			    }
			}

			else if($method == '_deleteProductTarget')
			{
				$target_id = $this->input->post('target_id');

				if(!empty($target_id))
				{
					$data = array(
				    	'published' => '0',
				    );

		    		$where_1 = array('id' => $target_id);
				    $delete  = $this->target_model->ProductTarget_delete($data, $where_1);
				    if($delete)
				    {
				    	$where_2 = array('target_id' => $target_id);
				    	$del_tar = $this->target_model->ProductTargetDetails_delete($data, $where_2);

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

			else if($method == '_getEmployeeList')
			{
				$month_id = $this->input->post('month_id');
				$year_id  = $this->input->post('year_id');

				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id');
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
			    	// Year Details
			    	$where_1  = array('id' => $year_id);
			    	$column_1 = 'year_value';
			    	$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
			    	$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';

			    	// Employee List
			    	$where_2 = array(
			    		'month_id'  => $month_id,
			    		'year_name' => $year_val,
			    		'published' => '1',
			    	);

			    	$column_2 = 'employee_id, target_val';

			    	$data_2   = $this->target_model->getTargetDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

			    	if($data_2)
			    	{	
			    		$employee_list = [];
			    		foreach ($data_2 as $key => $val_2) {
			    				
			    			$employee_id = !empty($val_2->employee_id)?$val_2->employee_id:'';
							$target_val  = !empty($val_2->target_val)?$val_2->target_val:'0';

							// Employee Details
							$where_3  = array('id' => $employee_id);
							$column_3 = 'first_name,last_name';
							$data_3   = $this->employee_model->getEmployee($where_3, '', '', 'result', '', '', '', '', $column_3);
							$first_name = !empty($data_3[0]->first_name)?$data_3[0]->first_name:'';
							$last_name = !empty($data_3[0]->last_name)?$data_3[0]->last_name:'';
							$arr = array($first_name,$last_name);
							$emp_name =join(" ",$arr);
							$employee_list[] = array(
								'employee_id' => $employee_id,
								'emp_name'    => $emp_name,
								'target_val'  => $target_val,
							);

			    		}

			    		$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $employee_list;
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

			else
			{
				$response['status']  = 0;
		        $response['message'] = "Error"; 
		        $response['data']    = [];
		        echo json_encode($response);
		        return;
			}
		}

		// Add Beat Target
		// ***************************************************
		public function beat_target($param1="",$param2="",$param3="")
		{	
			$method       = $this->input->post('method');
			$target_id    = $this->input->post('target_id');
			$month_id     = $this->input->post('month_id');
			$year_id      = $this->input->post('year_id');
			$employee_id  = $this->input->post('employee_id');
			$target_value = $this->input->post('target_value');

			if($method == '_addBeatTarget')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id', 'employee_id', 'financial_id', 'target_value');
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
			    	$where=array(
			    		'month_id'    => $month_id,
				    	'year_id'     => $year_id,
				    	'emp_id'      => $employee_id,
				    	'status'      => '1',
				    	'published'   => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->target_model->getBeatTarget($where, '', '', 'result', '', '', '', '', $column);

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
						// Total Value
						$target_res = json_decode($target_value);	
						$total_val  = 0;
						foreach ($target_res as $key => $val) {
							$target_amt = !empty($val->target_val)?$val->target_val:'0';
							$total_val += $target_amt;
					    }

						// Current Month Target Value
				    	$where_1  = array('id' => $year_id);
				    	$column_1 = 'year_value';
				    	$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
				    	$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';

				    	$where_2 = array(
				    		'employee_id' => $employee_id,
				    		'month_id'    => $month_id,
				    		'year_name'   => $year_val,
				    		'published'   => '1',
				    	);

				    	$column_2 = 'target_val';

			    		$data_2   = $this->target_model->getTargetDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

			    		$target_tot = !empty($data_2[0]->target_val)?$data_2[0]->target_val:'0';

			    		if($target_tot >= $total_val)
			    		{
			    			// Employee Details
			    			$where_3  = array('id' => $employee_id);
							$column_3 = 'first_name,last_name';
							$data_3   = $this->employee_model->getEmployee($where_3, '', '', 'result', '', '', '', '', $column_3);
							$first_name = !empty($data_3[0]->first_name)?$data_3[0]->first_name:'';
							$last_name = !empty($data_3[0]->last_name)?$data_3[0]->last_name:'';
							$arr = array($first_name,$last_name);
							$emp_name =join(" ",$arr);
							// Month Details
			    			$where_4  = array('month_value' => $month_id);
							$column_4 = 'month_name';
							$data_4   = $this->commom_model->getMonth($where_4, '', '', 'result', '', '', '', '', $column_4);
							$mon_name = !empty($data_4[0]->month_name)?$data_4[0]->month_name:'';

							$data = array(
								'month_id'      => $month_id,
								'month_name'    => $mon_name,
						    	'year_id'       => $year_id,
						    	'year_value'    => $year_val,
						    	'emp_id'        => $employee_id,
						    	'emp_name'      => $emp_name,
						    	'createdate'    => date('Y-m-d H:i:s'),
						    );

							$insert = $this->target_model->beatTarget_insert($data);

							// Total Value
							$target_res = json_decode($target_value);	
							foreach ($target_res as $key => $val) {
							    $zone_id    = !empty($val->zone_id)?$val->zone_id:'0';
							    $target_val = !empty($val->target_val)?$val->target_val:'0';

							    // Zone Details
				    			$where_5   = array('id' => $zone_id);
								$column_5  = 'name';
								$data_5    = $this->commom_model->getZone($where_5, '', '', 'result', '', '', '', '', $column_5);
								$zone_name = !empty($data_5[0]->name)?$data_5[0]->name:'';

								$str_data = array(
							    	'target_id'   => $insert,
							    	'emp_id'      => $employee_id,
							    	'emp_name'    => $emp_name,
							    	'zone_id'     => $zone_id,
							    	'zone_name'   => $zone_name,
							    	'month_id'    => $month_id,
							    	'month_name'  => $mon_name,
							    	'year_name'   => $year_val,
							    	'target_val'  => $target_val,
							    	'createdate'  => date('Y-m-d H:i:s'),
							    );

							    $target_insert = $this->target_model->beatTargetDetails_insert($str_data);
							}

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
					        $response['message'] = "Invalid Value"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
			    		}
					}
			    }
			}

			else if($method == '_manageBeatTarget')
			{
				$limit       = $this->input->post('limit');
	    		$offset      = $this->input->post('offset');
	    		$financ_year = $this->input->post('financial_year');

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
	    			$like['name']     = $search;
	    			$where = array('published'    => '1');
	    		}
	    		else
	    		{
	    			$like  = [];
	    			$where = array('published'    => '1');
	    		}

	    		$column_1 = 'id';
				$overalldatas = $this->target_model->getBeatTarget($where, '', '', 'result', $like, '', '', '', $column_1);

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

				$column_2  = 'id, month_name, year_value, emp_name, status, createdate';
				$data_list = $this->target_model->getBeatTarget($where, $limit, $offset, 'result', $like, '', $option, '', $column_2);

				if($data_list)
				{
					$assign_list = [];
					foreach ($data_list as $key => $value) {

						$target_id  = !empty($value->id)?$value->id:'';
			            $month_name = !empty($value->month_name)?$value->month_name:'';
			            $year_value = !empty($value->year_value)?$value->year_value:'';
			            $emp_name   = !empty($value->emp_name)?$value->emp_name:'';
			            $status     = !empty($value->status)?$value->status:'';
			            $createdate = !empty($value->createdate)?$value->createdate:'';

			            $assign_list[] = array(
			            	'target_id'  => $target_id,
				            'month_name' => $month_name,
				            'year_value' => $year_value,
				            'emp_name'   => $emp_name,
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
			        $response['data']         = $assign_list;
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

			else if($method == '_detailBeatTarget')
			{
				if($target_id)
				{
					// Target Data
					$where_1  = array('id' => $target_id, 'published' => '1');
					$column_1 = 'id, month_id, year_id, emp_id, status';
					$data_1   = $this->target_model->getBeatTarget($where_1, '', '', 'result', '', '', '', '', $column_1);

					if($data_1)
					{
						$target_id   = !empty($data_1[0]->id)?$data_1[0]->id:'';
						$month_id    = !empty($data_1[0]->month_id)?$data_1[0]->month_id:'';
						$year_id     = !empty($data_1[0]->year_id)?$data_1[0]->year_id:'';
						$employee_id = !empty($data_1[0]->emp_id)?$data_1[0]->emp_id:'';
						$status      = !empty($data_1[0]->status)?$data_1[0]->status:'';

			            $target_data = array(
			            	'target_id'   => $target_id,
				            'month_id'    => $month_id,
				            'year_id'     => $year_id,
				            'employee_id' => $employee_id,
				            'status'      => $status,
			            );

			            // Target Details
			            $where_2  = array('target_id' => $target_id, 'published' => '1');
			            $column_2 = 'id, zone_id, zone_name, target_val';
			            $data_2   = $this->target_model->getBeatTargetDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

			            $target_list = [];
			            if($data_2)
			            {
			            	foreach ($data_2 as $key => $val_2) {
			            		$auto_id    = !empty($val_2->id)?$val_2->id:'';
					            $zone_id    = !empty($val_2->zone_id)?$val_2->zone_id:'';
					            $zone_name  = !empty($val_2->zone_name)?$val_2->zone_name:'';
					            $target_val = !empty($val_2->target_val)?$val_2->target_val:'0';

					            $target_list[] = array(
					            	'auto_id'    => $auto_id,
						            'zone_id'    => $zone_id,
						            'zone_name'  => $zone_name,
						            'target_val' => $target_val,
					            );
			            	}
			            }

			            $target_details = array(
			            	'target_data' => $target_data,
			            	'target_list' => $target_list,
			            );

			            $response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $target_details;
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
				else
				{
					$response['status']  = 0;
			        $response['message'] = "Please fill all required fields"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return; 
				}
			}

			else if($method == '_updateBeatTarget')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('target_id', 'month_id', 'year_id', 'employee_id', 'financial_id', 'target_value');
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
			    	$where=array(
			    		'id !='       => $target_id,
			    		'month_id'    => $month_id,
				    	'year_id'     => $year_id,
				    	'emp_id'      => $employee_id,
				    	'published'   => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->target_model->getBeatTarget($where, '', '', 'result', '', '', '', '', $column);

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
						// Total Value
						$target_res = json_decode($target_value);	
						$total_val  = 0;
						foreach ($target_res as $key => $val) {
							$target_amt = !empty($val->target_val)?$val->target_val:'0';
							$total_val += $target_amt;
					    }

						// Current Month Target Value
				    	$where_1  = array('id' => $year_id);
				    	$column_1 = 'year_value';
				    	$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
				    	$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';

				    	$where_2 = array(
				    		'employee_id' => $employee_id,
				    		'month_id'    => $month_id,
				    		'year_name'   => $year_val,
				    		'published'   => '1',
				    	);

				    	$column_2 = 'target_val';

			    		$data_2   = $this->target_model->getTargetDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

			    		$target_tot = !empty($data_2[0]->target_val)?$data_2[0]->target_val:'0';

			    		if($target_tot >= $total_val)
			    		{
			    			// Employee Details
			    			$where_3  = array('id' => $employee_id);
							$column_3 = 'first_name,last_name';
							$data_3   = $this->employee_model->getEmployee($where_3, '', '', 'result', '', '', '', '', $column_3);
							$first_name = !empty($data_3[0]->first_name)?$data_3[0]->first_name:'';
							$last_name = !empty($data_3[0]->last_name)?$data_3[0]->last_name:'';
							$arr = array($first_name,$last_name);
							$emp_name =join(" ",$arr);
							$data = array(
						    	'emp_id'        => $employee_id,
						    	'emp_name'      => $emp_name,
						    	'createdate'    => date('Y-m-d H:i:s'),
						    );

							$update_id = array('id' => $target_id);
					    	$update    = $this->target_model->beatTarget_update($data, $update_id);

					    	$del_val = array('published' => '0');
					    	$del_whr = array('target_id' => $target_id);
				    		$del_tar = $this->target_model->beatTargetDetails_delete($del_val, $del_whr);

							// Total Value
							$target_res = json_decode($target_value);	
							foreach ($target_res as $key => $val) {
								$auto_id    = !empty($val->auto_id)?$val->auto_id:'0';
							    $zone_id    = !empty($val->zone_id)?$val->zone_id:'0';
							    $target_val = !empty($val->target_val)?$val->target_val:'0';

								$str_data = array(
							    	'target_id'   => $target_id,
							    	'emp_id'      => $employee_id,
							    	'emp_name'    => $emp_name,
							    	'target_val'  => $target_val,
							    	'published'   => '1',
							    	'updatedate'  => date('Y-m-d H:i:s'),
							    );

							    $upt_whr    = array('id' => $auto_id);
					    		$update_det = $this->target_model->beatTargetDetails_update($str_data, $upt_whr);
							}

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
					        $response['message'] = "Invalid Value"; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
			    		}
					}
				}
			}

			else if($method == '_deleteBeatTarget')
			{
				$target_id = $this->input->post('target_id');

				if(!empty($target_id))
				{
					$data = array(
				    	'published' => '0',
				    );

		    		$where_1 = array('id' => $target_id);
				    $delete  = $this->target_model->beatTarget_delete($data, $where_1);
				    if($delete)
				    {
				    	$where_2 = array('target_id' => $target_id);
				    	$del_tar = $this->target_model->beatTargetDetails_delete($data, $where_2);

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

			else if($method == '_getBeatList')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('month_id', 'year_id', 'employee_id');
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
			    	$where_1  = array('id' => $year_id);
			    	$column_1 = 'year_value';
			    	$data_1   = $this->commom_model->getYear($where_1, '', '', 'result', '', '', '', '', $column_1);
			    	$year_val = !empty($data_1[0]->year_value)?$data_1[0]->year_value:'';

			    	$month_count = cal_days_in_month(CAL_GREGORIAN, $month_id, $year_val);

			    	$str_date = date('Y-m-d', strtotime('01-'.$month_id.'-'.$year_val));
			    	$end_date = date('Y-m-d', strtotime($month_count.'-'.$month_id.'-'.$year_val));

			    	$where_2  = array(
			    		'employee_id'    => $employee_id,
						'assign_date >=' => $str_date,
						'assign_date <=' => $end_date,
						'published'      => '1',
					);

					$column_2 = 'assign_store';
					$data_2   = $this->assignshop_model->getAssignshopDetails($where_2, '', '', 'result', '', '', '', '', $column_2);

					if($data_2)
					{
						$assign_val = '';
						foreach ($data_2 as $key => $val_2) {
							$assign_store = !empty($val_2->assign_store)?$val_2->assign_store:'';

							if(!empty($assign_store))
							{
								$assign_val .= $assign_store.',';
							}
						}

						$assign_lst  = substr($assign_val, 0, -1);
						$assign_res  = explode(',', $assign_lst);
						$assign_data = array_unique($assign_res);
						$assign_get  = implode(',', $assign_data);

						$where_3  = array('zone_id' => $assign_get, 'published' => '1');
						$column_3  = 'id, name';
						$data_3   = $this->commom_model->getZoneImplode($where_3, '', '', 'result', '', '', '', '', $column_3);

						if($data_3)
						{
							$beat_list = [];
							foreach ($data_3 as $key => $val_3) {
								$zone_id   = !empty($val_3->id)?$val_3->id:'';
								$zone_name = !empty($val_3->name)?$val_3->name:'';

								$beat_list[] = array(
									'zone_id'   => $zone_id,
									'zone_name' => $zone_name
								);
							}

							$response['status']  = 1;
					        $response['message'] = "Success"; 
					        $response['data']    = $beat_list;
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

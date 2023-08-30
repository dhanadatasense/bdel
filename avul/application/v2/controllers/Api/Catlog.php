<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	date_default_timezone_set('Asia/Kolkata');

	class Catlog extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('commom_model');
			$this->load->model('vendors_model');
			$this->load->model('pricemaster_model');
			$this->load->model('outlets_model');
			$this->load->model('loyalty_model');
			$this->load->model('assignproduct_model');
			$this->load->model('user_model');
			$this->load->model('distributors_model');
		}

		public function index()
		{
			echo "Test";
		}

		// Category
		// ***************************************************
		public function category($param1="",$param2="",$param3="")
		{
			$method   = $this->input->post('method');
			$log_id   = $this->input->post('log_id');
			$log_role = $this->input->post('log_role');

			if($method == '_addCategory')
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
			    	$name           = $this->input->post('name');
			    	$item_type      = $this->input->post('item_type');
			    	$salesagents_id = $this->input->post('salesagents_id');

			    	$where=array(
				    	'name'      => ucfirst($name),
				    	'status'    => '1',
				    	'published' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getCategory($where, '', '', 'result', '', '', '', '', $column);

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
					    	'name'           => ucfirst($name),
					    	'item_type'      => $item_type,
				    		'salesagents_id' => $salesagents_id,
					    	'createdate'     => date('Y-m-d H:i:s')
					    );

					    $insert=$this->commom_model->category_insert($data);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_category',
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

			else if($method == '_listCategoryPaginate')
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
	    			$where = array(
	    				'published' => '1'
	    			);
	    		}
	    		else
	    		{
	    			$like = [];
	    			$where = array(
	    				'published' => '1'
	    			);
	    		}

	    		$column = 'id';
				$overalldatas = $this->commom_model->getCategory($where, '', '', 'result', $like, '', '', '', $column);

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

				$data_list = $this->commom_model->getCategory($where, $limit, $offset, 'result', $like, '', $option);

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
	      					'category_id'    => $category_id,
				            'category_name'  => $category_name,
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
			        $response['data']         = $category_list;
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

			else if($method == '_detailCategory')
			{
				$category_id = $this->input->post('category_id');

		    	if(!empty($category_id))
		    	{

		    		$where = array('id'=>$category_id);
				    $data  = $this->commom_model->getCategory($where);
				    if($data)
				    {	

				    	$category_list = [];

						foreach ($data as $key => $value) {

							$category_id    = isset($value->id)?$value->id:'';
				            $category_name  = isset($value->name)?$value->name:'';
				            $published      = isset($value->published)?$value->published:'';
				            $status         = isset($value->status)?$value->status:'';
				            $createdate     = isset($value->createdate)?$value->createdate:'';

				            $category_list[] = array(
	          					'category_id'    => $category_id,
					            'category_name'  => $category_name,
					            'published'      => $published,
					            'status'         => $status,
					            'createdate'     => $createdate,
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

			else if($method == '_listCategory')
			{
				$where = array(
    				'published' => '1',
    				'status'    => '1',
    			);

				$data_list = $this->commom_model->getCategory($where);

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
	      					'category_id'    => $category_id,
				            'category_name'  => $category_name,
				            'published'      => $published,
				            'status'         => $status,
				            'createdate'     => $createdate,
	      				);
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $category_list;
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

			else if($method == '_updateCategory')
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
			    	$category_id  = $this->input->post('id');
			    	$name         = $this->input->post('name');
			    	$status       = $this->input->post('status');

			    	$where=array(
			    		'id !='     => $category_id,
				    	'name'      => ucfirst($name),
				    	'status'    => '1',
				    	'published' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getCategory($where, '', '', 'result', '', '', '', '', $column);

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

			    		$update_id = array('id'=>$category_id);
					    $update    = $this->commom_model->category_update($data, $update_id);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_category',
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

			else if($method == '_deleteCategory')
			{	
		    	$category_id = $this->input->post('category_id');

		    	if(!empty($category_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

		    		$where  = array('id'=>$category_id);
				    $update = $this->commom_model->category_delete($data, $where);

				    $log_data = array(
						'u_id'       => $log_id,
						'role'       => $log_role,
						'table'      => 'tbl_category',
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
			// Category
		// ***************************************************
		public function sub_category($param1="",$param2="",$param3="")
		{
			$method   = $this->input->post('method');
			$log_id   = $this->input->post('log_id');
			$log_role = $this->input->post('log_role');

			if($method == '_addSubCategory')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('name','category_id');
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
			    	$name           = $this->input->post('name');
					$category_id    = $this->input->post('category_id');
			    	$item_type      = $this->input->post('item_type');
			    	$salesagents_id = $this->input->post('salesagents_id');

			    	$where=array(
				    	'name'      => ucfirst($name),
				    	'status'    => '1',
				    	'published' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getSubCategory($where, '', '', 'result', '', '', '', '', $column);

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
					    	'name'           => ucfirst($name),
							'category_id'    => $category_id,
					    	'item_type'      => $item_type,
				    		'salesagents_id' => $salesagents_id,
					    	'createdate'     => date('Y-m-d H:i:s')
					    );

					    $insert=$this->commom_model->sub_category_insert($data);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_category',
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

			else if($method == '_listSubCategoryPaginate')
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
	    			$where = array(
	    				'published' => '1'
	    			);
	    		}
	    		else
	    		{
	    			$like = [];
	    			$where = array(
	    				'published' => '1'
	    			);
	    		}
			
				$sort_col_ = array('id', 'name', 'category_id', 'status');
				array_unshift($sort_col_,"");
				unset($sort_col_[0]);

				$option['order_by']   = !empty($sort_column) ? $sort_col_[$sort_column] : 'id';
				$option['disp_order'] = !empty($sort_type) ? ($sort_type==1? 'DESC' : 'ASC') : 'DESC';
	    		$column = 'id';
				$overalldatas = $this->commom_model->getSubCategory($where, '', '', 'result', $like, '', '', '', $column);

				if($overalldatas)
				{
					$totalc = count($overalldatas);
				}
				else
				{
					$totalc = 0;
				}
			

				$data_list = $this->commom_model->getSubCategory($where, $limit, $offset, 'result', $like, '', $option);

				if($data_list)
				{
					$category_list = [];

					foreach ($data_list as $key => $value) {

						$id    = isset($value->id)?$value->id:'';
						$category_id    = isset($value->category_id)?$value->category_id:'';
			            $sub_category_name  = isset($value->name)?$value->name:'';
			            $published      = isset($value->published)?$value->published:'';
			            $status         = isset($value->status)?$value->status:'';
			            $createdate     = isset($value->createdate)?$value->createdate:'';

						$wher = array(
							'id' => $category_id,
							'published' => 1,
						);
						$main_cat =$this->commom_model->getCategory($wher);
						if(!empty($main_cat)){
							foreach ($main_cat as $key => $value) {
								$cat_name  = isset($value->name)?$value->name:'';
							}
						}
			            $category_list[] = array(
	      					'category_id'    => $category_id,
							'cat_name'       => $cat_name,
							'id'             => $id,
				            'sub_category_name'  => $sub_category_name,
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
			        $response['data']         = $category_list;
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

			else if($method == '_detailSubCategory')
			{
				$category_id = $this->input->post('category_id');

		    	if(!empty($category_id))
		    	{

		    		$where = array('id'=>$category_id);
				    $data  = $this->commom_model->getSubCategory($where);
				    if($data)
				    {	

				    	$category_list = [];

						foreach ($data as $key => $value) {
							$category_id    = isset($value->category_id)?$value->category_id:'';
							$id    = isset($value->id)?$value->id:'';
				            $category_name  = isset($value->name)?$value->name:'';
				            $published      = isset($value->published)?$value->published:'';
				            $status         = isset($value->status)?$value->status:'';
				            $createdate     = isset($value->createdate)?$value->createdate:'';

				            $category_list[] = array(
	          					'category_id'    => $category_id,
								'id'             => $id,
					            'category_name'  => $category_name,
					            'published'      => $published,
					            'status'         => $status,
					            'createdate'     => $createdate,
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

			else if($method == '_listSubCategory')
			{
				$cat_id_val = $this->input->post('cat_id');
				$admin = $this->input->post('admin');
				
				$cat_id = explode(',', $cat_id_val);
				$where = array(
					
    				'published' => '1',
    				'status'    => '1',
    			);
				$wr_in['category_id'] = $cat_id;

				$data_list = $this->commom_model->getSubCategory($where,'','',"result",'','','','','',$wr_in);

				if($data_list)
				{
					$category_list = [];

					foreach ($data_list as $key => $value) {

						$id    = isset($value->id)?$value->id:'';
						$category_id    = isset($value->category_id)?$value->category_id:'';
			            $sub_cat_name  = isset($value->name)?$value->name:'';
			            $published      = isset($value->published)?$value->published:'';
			            $status         = isset($value->status)?$value->status:'';
			            $createdate     = isset($value->createdate)?$value->createdate:'';

			            $category_list[] = array(
	      					'category_id'    => $category_id,
							'id'             => $id,
				            'sub_cat_name'  => $sub_cat_name,
				            'published'      => $published,
				            'status'         => $status,
				            'createdate'     => $createdate,
	      				);
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $category_list;
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
			
			

			else if($method == '_listCategory_sub_cat')
			{
				$category_id = $this->input->post('category_id');

				if(!empty($category_id))
				{
					$where = array(
						'category_id' => $category_id,
						'status'      => '1', 
						'published'   => '1'
					);

					$data_list = $this->commom_model->getSubCategoryImplode($where); 

					if($data_list)
					{
						$sub_cat_list = [];
						foreach ($data_list as $key => $value) {
								
							$id       = !empty($value->id)?$value->id:'';
							$name    = !empty($value->name)?$value->name:'';
							$description   = !empty($value->description)?$value->description:'';

							$sub_cat_list[] = array(
								'name'     => $name,
								'id'  => $id,
							);
						}

						$response['status']       = 1;
				        $response['message']      = "Success"; 
				        $response['data']         = $sub_cat_list;
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

			else if($method == '_updateSubCategory')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('id', 'name','category_id');
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
					$category_id  = $this->input->post('category_id');
			    	$id  = $this->input->post('id');
			    	$name         = $this->input->post('name');
			    	$status       = $this->input->post('status');

			    	$where=array(
			    		'id !='     => $id,
				    	'name'      => ucfirst($name),
				    	'status'    => '1',
				    	'published' => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getSubCategory($where, '', '', 'result', '', '', '', '', $column);

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
					    	'updatedate' => date('Y-m-d H:i:s'),
							'category_id' => $category_id,
					    );

			    		$update_id = array('id'=>$id);
					    $update    = $this->commom_model->sub_category_update($data, $update_id);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_category',
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

			else if($method == '_deleteSubCategory')
			{	
		    	$category_id = $this->input->post('category_id');

		    	if(!empty($category_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

		    		$where  = array('id'=>$category_id);
				    $update = $this->commom_model->category_delete($data, $where);

				    $log_data = array(
						'u_id'       => $log_id,
						'role'       => $log_role,
						'table'      => 'tbl_category',
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
			else if($method == '_listCategory_sub_cat_dis')
			{
				$category_id = $this->input->post('category_id');
				$sub_cat_id = $this->input->post('sub_cat_id');
				
				if(!empty($category_id))
				{    $sub_arr   = explode(',', $sub_cat_id);
					$category_arr   = explode(',', $category_id);
					$category_count = count($category_arr);

				    		for($i = 0; $i < $category_count; $i++)
				    		{
								$wheree = array(
									'category_id' => $category_arr[$i],
									'status'      => '1', 
									'published'   => '1'
								);
								$where_in['id'] = $sub_arr;
								
								$data_list = $this->commom_model->getSubCategory($wheree,'','',"result",'','','','','',$where_in);
								
								if($data_list)
								{
									$sub_list = [];
									foreach ($data_list as $key => $value) {
											
										$sub_id       = !empty($value->id)?$value->id:'';
										$product_id    = !empty($value->product_id)?$value->product_id:'';
										$name   = !empty($value->name)?$value->name:'';
			
										$sub_list[] = array(
											'sub_id'     => $sub_id,
											'product_id'  => $product_id,
											'name' => $name,
										);
									}
								}
							}
					
					if(!empty($sub_list))
					{
						$response['status']       = 1;
				        $response['message']      = "Success"; 
				        $response['data']         = $sub_list;
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

		// Product
		// ***************************************************
		public function product($param1="",$param2="",$param3="")
		{
			$method   = $this->input->post('method');
			$log_id   = $this->input->post('log_id');
			$log_role = $this->input->post('log_role');

			if($method == '_addProduct')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('name', 'category_id', 'vendor_id', 'hsn_code', 'unit');
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
			    	$name         = $this->input->post('name');
			    	$unique_code  = $this->input->post('unique_code');
			    	$category_id  = $this->input->post('category_id');
					$sub_cat_id  = $this->input->post('sub_cat_id');
			    	$vendor_id    = $this->input->post('vendor_id');
			    	$hsn_code     = $this->input->post('hsn_code');
			    	$price        = $this->input->post('price');
			    	$stock        = $this->input->post('stock');
			    	$vend_stock   = $this->input->post('vend_stock');
			    	$unit         = $this->input->post('unit');
			    	$gst          = $this->input->post('gst');
			    	$item_type    = $this->input->post('item_type');
			    	$product_type = $this->input->post('product_type');

			    	$where = array(
				    	'name'        => ucfirst($name),
				    	'category_id' => $category_id,
						'sub_cat_id'  => $sub_cat_id,
				    	'vendor_id'   => $vendor_id,
				    	'status'      => '1',
				    	'published'   => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getProduct($where, '', '', 'result', '', '', '', '', $column);

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
						// Insert prodyct to distributors
						$dis_whr = array('published' => 1);
						$dis_col = 'id, ref_id';
						$dis_qry = $this->distributors_model->getDistributors($dis_whr, '', '', 'result', '', '', '', '', $dis_col);

						print_r($dis_qry); 

						exit;

						$column = 'id';
						$product_count  = $this->commom_model->getProduct('', '', '', 'result', '', '', '', '', $column);

						if(!empty($product_count))
						{
							$overall_count = count($product_count);
						}
						else
						{
							$overall_count = 0;
						}

						$product_name  = strtoupper($name);
						$product_code  = mb_strimwidth($product_name, 0, 3);
						$newcount      = $overall_count + 1;
						$product_num   = leadingZeros($newcount, 6);
						$material_code = $product_code.'/'.$product_num;

						// Product Stock
			    		if($unit == 1 || $unit == 11)
			    		{
			    			$view_stock   = $stock * 1000;
			    			$stock_detail = $vend_stock * 1000;
			    		}
			    		else if($unit == 2 || $unit == 4)
			    		{
			    			$view_stock   = $stock * 1000;
			    			$stock_detail = $vend_stock / 1000;
			    		}
			    		else
			    		{
			    			$view_stock   = $stock;
			    			$stock_detail = $vend_stock;
			    		}

			    		// Vendor Details
			    		$column_1 = 'vendor_type';

			    		$where_1  = array(
			    			'id'        => $vendor_id,
			    			'status'    => '1', 
							'published' => '1'
			    		);

			    		$vendor_whr  = $this->vendors_model->getVendors($where_1, '', '', 'result', '', '', '', '', $column_1);

			    		$vendor_type = !empty($vendor_whr[0]->vendor_type)?$vendor_whr[0]->vendor_type:'';

			    		$data = array(
			    			'product_code' => $material_code,
			    			'unique_code'  => urlSlug($unique_code),
					    	'name'         => ucfirst($name),
					    	'category_id'  => $category_id,
							'sub_cat_id'   => $sub_cat_id,
					    	'vendor_id'    => $vendor_id,
					    	'vendor_type'  => $vendor_type,
					    	'hsn_code'     => $hsn_code,
					    	'price'        => digit_val($price),
					    	'stock'        => $stock,
					    	'view_stock'   => $view_stock,
					    	'vend_stock'   => $vend_stock,
					    	'stock_detail' => $stock_detail,
					    	'unit'         => $unit,
					    	'gst'          => $gst,
					    	'item_type'    => $item_type,
					    	'createdate'   => date('Y-m-d H:i:s')
					    );

					    // Image Information
					    $fileCount = count($_FILES['image']['name']);
					    if($fileCount > 0)
					    {
					    	for ($i=0; $i < $fileCount; $i++) {

					    		$image = generateRandomString(13).'.jpg';

								post_img($image, $_FILES['image']['tmp_name'][$i],"upload/product/");

								$data['product_img'] = $image;
				    		}
					    }

					    $insert = $this->commom_model->product_insert($data);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_product',
							'auto_id'    => $insert,
							'action'     => 'create',
							'date'       => date('Y-m-d'),
							'time'       => date('H:i:s'),
							'createdate' => date('Y-m-d H:i:s')
						);

						$log_val = $this->commom_model->log_insert($log_data);

					    $product_value = json_decode($product_type);	

					    $number = 1;
					    foreach ($product_value as $key => $value) {

					    	$sub_code = leadingZeros($number, 6);

					    	$description = !empty($value->description)?$value->description:'';
							$pro_type    = !empty($value->pro_type)?$value->pro_type:'';
							$pro_unit    = !empty($value->pro_unit)?$value->pro_unit:'';
							$mrp_price   = !empty($value->mrp_price)?$value->mrp_price:'';
							$pro_price   = !empty($value->pro_price)?$value->pro_price:'';
							$ven_price   = !empty($value->ven_price)?$value->ven_price:'';
							$dis_price   = !empty($value->dis_price)?$value->dis_price:'';
							$pro_stock   = !empty($value->pro_stock)?$value->pro_stock:'0';
							$type_stock  = !empty($value->type_stock)?$value->type_stock:'0';
							$minimum_stock = !empty($value->minimum_stock)?$value->minimum_stock:'0';

					    	// Product Type Stock
				    		if($unit == 1 || $unit == 11)
				    		{
				    			$main_stock = $pro_stock * 1000;
				    			$sub_stock  = $type_stock * 1000;
				    			$mini_stock = $minimum_stock * 1000;
				    		}
							else if($unit == 2 || $unit == 4)
				    		{
				    			$main_stock = $pro_stock * 1000;
				    			$sub_stock  = $type_stock / 1000;
				    			$mini_stock = $minimum_stock / 1000;
				    		}
				    		else
				    		{
				    			$main_stock = $pro_stock;
				    			$sub_stock  = $type_stock;
				    			$mini_stock = $minimum_stock;
				    		}

					    	$value = array(
					    		'vendor_id'     => $vendor_id,
					    		'vendor_type'   => $vendor_type,
						    	'sub_code'      => $material_code.'/'.$sub_code,
						    	'category_id'   => $category_id,
								'sub_cat_id'   => $sub_cat_id,
						    	'product_id'    => $insert,
						    	'description'   => ucfirst($description),
						    	'product_type'  => $pro_type,
						    	'product_unit'  => $pro_unit,
						    	'mrp_price'     => digit_val($mrp_price),
						    	'product_price' => digit_val($pro_price),
						    	'ven_price'     => digit_val($ven_price),
						    	'dis_price'     => digit_val($dis_price),
						    	'product_stock' => $pro_stock,
						    	'view_stock'    => $main_stock,
						    	'type_stock'    => $type_stock,
						    	'stock_detail'  => $sub_stock,
						    	'minimum_stock' => $mini_stock,
						    	'createdate'    => date('Y-m-d H:i:s'),
						    );

						    $type_insert = $this->commom_model->productType_insert($value);

						    $number++;
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
			    }
			}

			else if($method == '_listProductPaginate')
			{
				$limit     = $this->input->post('limit');
	    		$offset    = $this->input->post('offset');
	    		$search    = $this->input->post('search');
	    		$item_type = $this->input->post('item_type');
	    		$vendor_id = $this->input->post('vendor_id');

	    		$error = FALSE;
			    $errors = array();
				$required = array('item_type');
				if($item_type == '2')
				{
					array_push($required, 'vendor_id');
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
			    if(count($errors) == 0)
			    {
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

		    		if($search !='')
		    		{
		    			$like['name']     = $search;

		    			if($item_type == '2')
		    			{
		    				$where = array(
		    					'vendor_id'  => $vendor_id,
			    				'published'  => '1',
			    			);
		    			}
		    			else
		    			{
		    				$where = array(
			    				'published'  => '1',
			    			);
		    			}
		    		}
		    		else
		    		{
		    			$like = [];
		    			if($item_type == '2')
		    			{
		    				$where = array(
		    					'vendor_id'  => $vendor_id,
			    				'published'  => '1',
			    			);
		    			}
		    			else
		    			{
		    				$where = array(
			    				'published'  => '1',
			    			);
		    			}
		    		}

		    		$column = 'id';
					$overalldatas = $this->commom_model->getProduct($where, '', '', 'result', $like, '', '', '', $column);

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

					$data_list = $this->commom_model->getProduct($where, $limit, $offset, 'result', $like, '', $option);

					if($data_list)
					{
						$product_list = [];

						foreach ($data_list as $key => $value) {

							$product_id   = isset($value->id)?$value->id:'';
				            $product_name = isset($value->name)?$value->name:'';
				            $category_id  = isset($value->category_id)?$value->category_id:'';
				            $vendor_id    = isset($value->vendor_id)?$value->vendor_id:'';
				            $hsn_code     = isset($value->hsn_code)?$value->hsn_code:'';
				            $price        = isset($value->price)?$value->price:'';
				            $stock        = isset($value->stock)?$value->stock:'';
				            $unit         = isset($value->unit)?$value->unit:'';
				            $gst          = isset($value->gst)?$value->gst:'';
				            $product_img  = isset($value->product_img)?$value->product_img:'';
				            $published    = isset($value->published)?$value->published:'';
				            $status       = isset($value->status)?$value->status:'';
				            $createdate   = isset($value->createdate)?$value->createdate:'';

				            // Category Name
				            $where_1       = array('id'=>$category_id);
					    	$data_val      = $this->commom_model->getCategory($where_1);
					    	$category_name = isset($data_val[0]->name)?$data_val[0]->name:'';

					    	// Vendor Name
					    	$where_2       = array('id'=>$vendor_id);
					    	$data_val      = $this->vendors_model->getVendors($where_2);
					    	$company_name  = isset($data_val[0]->company_name)?$data_val[0]->company_name:'';

					    	// Unit Name
					    	$where_3       = array('id'=>$unit);
					    	$data_val      = $this->commom_model->getUnit($where_3);
					    	$unit_name     = isset($data_val[0]->name)?$data_val[0]->name:'';

				            $product_list[] = array(
		      					'product_id'    => $product_id,
					            'product_name'  => $product_name,
					            'category_id'   => $category_id,
					            'category_name' => $category_name,
					            'vendor_id'     => $vendor_id,
					            'company_name'  => $company_name,
					            'hsn_code'      => $hsn_code,
					            'price'         => $price,
					            'stock'         => $stock,
					            'unit'          => $unit,
					            'gst'           => $gst,
					            'product_img'   => $product_img,
					            'unit_name'     => $unit_name,
					            'published'     => $published,
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
					        $response['data']         = $product_list;
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

			else if($method == '_detailProduct')
			{
				$product_id = $this->input->post('product_id');

		    	if(!empty($product_id))
		    	{

		    		$where = array('id'=>$product_id);
				    $data  = $this->commom_model->getProduct($where);
				    if($data)
				    {	

				    	$product_list = [];

						foreach ($data as $key => $value) {

							$product_id   = isset($value->id)?$value->id:'';
							$product_code = isset($value->product_code)?$value->product_code:'';
							$unique_code  = isset($value->unique_code)?$value->unique_code:'';
				            $product_name = isset($value->name)?$value->name:'';
				            $category_id  = isset($value->category_id)?$value->category_id:'';
							$sub_cat_id  = isset($value->sub_cat_id)?$value->sub_cat_id:'';
				            $vendor_id    = isset($value->vendor_id)?$value->vendor_id:'';
				            $hsn_code     = isset($value->hsn_code)?$value->hsn_code:'';
				            $price        = isset($value->price)?$value->price:'';
				            $stock        = isset($value->stock)?$value->stock:'';
				            $vend_stock   = isset($value->vend_stock)?$value->vend_stock:'';
				            $unit         = isset($value->unit)?$value->unit:'';
				            $gst          = isset($value->gst)?$value->gst:'';
				            $product_img  = isset($value->product_img)?$value->product_img:'';
				            $published    = isset($value->published)?$value->published:'';
				            $status       = isset($value->status)?$value->status:'';
				            $createdate   = isset($value->createdate)?$value->createdate:'';

				            // Unit Name
					    	$where_1       = array('id'=>$unit);
					    	$data_val      = $this->commom_model->getUnit($where_1);
					    	$unit_name     = isset($data_val[0]->name)?$data_val[0]->name:'';

				            $product_list[] = array(
	          					'product_id'   => $product_id,
	          					'product_code' => $product_code,
	          					'unique_code'  => $unique_code,
					            'product_name' => $product_name,
					            'category_id'  => $category_id,
								'sub_cat_id'   => $sub_cat_id,
					            'vendor_id'    => $vendor_id,
					            'hsn_code'     => $hsn_code,
					            'price'        => $price,
					            'stock'        => $stock,
					            'vend_stock'   => $vend_stock,
					            'unit'         => $unit,
					            'unit_name'    => $unit_name,
					            'gst'          => $gst,
					            'product_img'  => $product_img,
					            'published'    => $published,
					            'status'       => $status,
					            'createdate'   => $createdate,
	          				);
						}

	        			$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $product_list;
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

			else if($method == '_listProduct')
			{
				$where = array(
					'status'      => '1', 
					'published'   => '1'
				);

				$data_list = $this->commom_model->getProduct($where);

				if($data_list)
				{
					$product_list = [];

					foreach ($data_list as $key => $value) {

						$product_id   = isset($value->id)?$value->id:'';
			            $product_name = isset($value->name)?$value->name:'';
			            $category_id  = isset($value->category_id)?$value->category_id:'';
			            $vendor_id    = isset($value->vendor_id)?$value->vendor_id:'';
			            $hsn_code     = isset($value->hsn_code)?$value->hsn_code:'';
			            $price        = isset($value->price)?$value->price:'';
			            $stock        = isset($value->stock)?$value->stock:'';
			            $unit         = isset($value->unit)?$value->unit:'';
			            $gst          = isset($value->gst)?$value->gst:'';
			            $published    = isset($value->published)?$value->published:'';
			            $status       = isset($value->status)?$value->status:'';
			            $createdate   = isset($value->createdate)?$value->createdate:'';

			            $product_list[] = array(
	      					'product_id'   => $product_id,
				            'product_name' => $product_name,
				            'category_id'  => $category_id,
				            'vendor_id'    => $vendor_id,
				            'hsn_code'     => $hsn_code,
				            'price'        => $price,
				            'stock'        => $stock,
				            'unit'         => $unit,
				            'gst'          => $gst,
				            'published'    => $published,
				            'status'       => $status,
				            'createdate'   => $createdate,
	      				);
					}

					$response['status']       = 1;
			        $response['message']      = "Success"; 
			        $response['data']         = $product_list;
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

			else if($method == '_listTypeWiseProduct')
			{
		    	// Pagination Details
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

				$where = array('status' => '1', 'published' => '1');

				// Search Details
				$search = $this->input->post('search');
	    		if($search !='')
	    		{
	    			$like['name'] = $search;
	    		}
	    		else
	    		{
	    			$like = [];
	    		}

	    		$column = 'id';
				$overalldatas = $this->commom_model->getProduct($where, '', '', 'result', $like, '', '', '', $column);

				if($overalldatas)
				{
					$totalc = count($overalldatas);
				}
				else
				{
					$totalc = 0;
				}

				$sel_column = 'id, name, category_id, hsn_code, gst';

	    		$option['order_by']   = 'name';
				$option['disp_order'] = 'ASC';

				$data_list = $this->commom_model->getProduct($where, $limit, $offset, 'result', $like, '', $option, $sel_column);

		    	if($data_list)
		    	{
		    		$product_list = [];

					foreach ($data_list as $key => $value) {

						$product_id   = isset($value->id)?$value->id:'';
			            $product_name = isset($value->name)?$value->name:'';
			            $hsn_code     = isset($value->hsn_code)?$value->hsn_code:'';
			            $gst_val      = isset($value->gst)?$value->gst:'';

			            $product_list[] = array(
	      					'product_id'   => $product_id,
				            'product_name' => $product_name,
				            'hsn_code'     => $hsn_code,
				            'gst_val'      => $gst_val,
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
			        $response['data']         = $product_list;
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

			else if($method == '_listTypeWiseProductNew')
			{
		    	// Pagination Details
		    	$limit        = $this->input->post('limit');
	    		$offset       = $this->input->post('offset');
	    		$category     = $this->input->post('category');
	    		$sub_category = $this->input->post('sub_category');

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

				$where = array('A.status' => '1', 'A.published' => '1');

				// Search Details
				$search = $this->input->post('search');
	    		if($search !='')
	    		{
	    			$like['name'] = $search;
	    		}
	    		else
	    		{
	    			$like = [];
	    		}

	    		$where_in = array();
				if($category)
				{
					$where_in['category_id'] = $category;
				}

				if($sub_category)
				{
					$where_in['sub_cat_id'] = $sub_category;
				}

	    		$column = 'A.id';
				$overalldatas = $this->commom_model->getProductJoin($where, '', '', 'result', $like, '', '', '', $column, $where_in);

				if($overalldatas)
				{
					$totalc = count($overalldatas);
				}
				else
				{
					$totalc = 0;
				}

				$sel_column = 'A.id, A.name, A.category_id, A.hsn_code, A.gst, B.name AS category, C.name AS sub_category, A.product_img';

	    		$option['order_by']   = 'A.name';
				$option['disp_order'] = 'ASC';

				$data_list = $this->commom_model->getProductJoin($where, $limit, $offset, 'result', $like, '', $option, '', $sel_column, $where_in);

		    	if($data_list)
		    	{
		    		$product_list = [];

					foreach ($data_list as $key => $value) {

						$product_id   = isset($value->id)?$value->id:'';
			            $product_name = isset($value->name)?$value->name:'';
			            $hsn_code     = isset($value->hsn_code)?$value->hsn_code:'';
			            $gst_val      = isset($value->gst)?$value->gst:'';
			            $category     = isset($value->category)?$value->category:'';
			            $sub_category = isset($value->sub_category)?$value->sub_category:'';
			            $product_img  = isset($value->product_img)?FILE_URL.'product/'.$value->product_img:null;

			            // Price details
			            $mrp_val = '';
			            $pdt_val = '';
			            $pdt_whr = array('product_id' => $product_id, 'published' => 1);
			            $pdt_col = 'mrp_price, product_price';
			            $pdt_qry = $this->commom_model->getProductType($pdt_whr, '', '', 'result', '', '', '', '', $pdt_col);
			            if($pdt_qry)
			            {
			            	$pdt_cnt = count($pdt_qry);

			            	if($pdt_cnt > 1)
			            	{
			            		$min_value = min($pdt_qry);
							    $max_value = max($pdt_qry);

			            		$min_mrp = !empty($min_value->mrp_price)?$min_value->mrp_price:0;
		            			$min_pdt = !empty($min_value->product_price)?$min_value->product_price:0;

		            			$max_mrp = !empty($max_value->mrp_price)?$max_value->mrp_price:0;
		            			$max_pdt = !empty($max_value->product_price)?$max_value->product_price:0;

		            			$mrp_val = $min_mrp.' - '.$max_mrp;
		            			$pdt_val = $min_pdt.' - '.$max_pdt;
			            	}
			            	else
			            	{
			            		$mrp_val = !empty($pdt_qry[0]->mrp_price)?$pdt_qry[0]->mrp_price:0;
		            			$pdt_val = !empty($pdt_qry[0]->product_price)?$pdt_qry[0]->product_price:0;
			            	}
			            }

			            $product_list[] = array(
	      					'product_id'    => $product_id,
				            'product_name'  => $product_name,
				            'hsn_code'      => $hsn_code,
				            'gst_val'       => $gst_val,
				            'category'      => $category,
				            'sub_category'  => $sub_category,
				            'product_img'   => $product_img,
				            'mrp_val'       => $mrp_val,
				            'pdt_val'       => $pdt_val,
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
			        $response['data']         = $product_list;
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

			else if($method == '_listVendorProducts')
			{
				$vendor_id = $this->input->post('vendor_id');

				if(!empty($vendor_id))
				{
					$where = array(
						'vendor_id' => $vendor_id,
						'status'    =>'1', 
						'published' =>'1'
					);

					$column = 'id, name, category_id, vendor_id, hsn_code, gst';

					$data_list = $this->commom_model->getProduct($where, '', '', 'result', '', '', '', '', $column);

					if($data_list)
					{
						$product_list = [];

						foreach ($data_list as $key => $value) {

							$product_id   = isset($value->id)?$value->id:'';
				            $product_name = isset($value->name)?$value->name:'';
				            $category_id  = isset($value->category_id)?$value->category_id:'';
				            $vendor_id    = isset($value->vendor_id)?$value->vendor_id:'';
				            $hsn_code     = isset($value->hsn_code)?$value->hsn_code:'';
				            $gst          = isset($value->gst)?$value->gst:'';

				            $product_list[] = array(
		      					'product_id'   => $product_id,
					            'product_name' => $product_name,
					            'category_id'  => $category_id,
					            'vendor_id'    => $vendor_id,
					            'hsn_code'     => $hsn_code,
					            'gst'          => $gst,
		      				);
						}

						$response['status']       = 1;
				        $response['message']      = "Success"; 
				        $response['data']         = $product_list;
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

			
			else if($method == '_listSubCategoryProducts')
			{
				$sub_cat_id = $this->input->post('sub_cat_id');

				if(!empty($sub_cat_id))
				{
					$where = array(
						'sub_cat_id' => $sub_cat_id,
						'vendor_type' => '1', 
						'status'      => '1', 
						'published'   => '1'
					);

					$data_list = $this->commom_model->getProductTypeImplode($where);

					if($data_list)
					{
						$product_list = [];
						foreach ($data_list as $key => $value) {
								
							$type_id       = !empty($value->id)?$value->id:'';
							$product_id    = !empty($value->product_id)?$value->product_id:'';
							$description   = !empty($value->description)?$value->description:'';

							$product_list[] = array(
								'type_id'     => $type_id,
								'product_id'  => $product_id,
								'description' => $description,
							);
						}

						$response['status']       = 1;
				        $response['message']      = "Success"; 
				        $response['data']         = $product_list;
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

			else if($method == '_listCategoryProducts')
			{
				$category_id = $this->input->post('category_id');

				if(!empty($category_id))
				{
					$where = array(
						'category_id' => $category_id,
						'vendor_type' => '1', 
						'status'      => '1', 
						'published'   => '1'
					);

					$data_list = $this->commom_model->getProductTypeImplode($where);

					if($data_list)
					{
						$product_list = [];
						foreach ($data_list as $key => $value) {
								
							$type_id       = !empty($value->id)?$value->id:'';
							$product_id    = !empty($value->product_id)?$value->product_id:'';
							$description   = !empty($value->description)?$value->description:'';

							$product_list[] = array(
								'type_id'     => $type_id,
								'product_id'  => $product_id,
								'description' => $description,
							);
						}

						$response['status']       = 1;
				        $response['message']      = "Success"; 
				        $response['data']         = $product_list;
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

			else if($method == '_listCategoryProducts_dis')
			{
				$category_id = $this->input->post('category_id');
				$type_id = $this->input->post('type_id');
				
				if(!empty($category_id))
				{    $ty_arr   = explode(',', $type_id);
					$category_arr   = explode(',', $category_id);
					$category_count = count($category_arr);

				    		for($i = 0; $i < $category_count; $i++)
				    		{
								$wheree = array(
									'category_id' => $category_arr[$i],
									'status'      => '1', 
									'published'   => '1'
								);
								$where_in['id'] = $ty_arr;
								
								$data_list = $this->commom_model->getProductType($wheree,'','',"result",'','','','','',$where_in);
								
								if($data_list)
								{
									$product_list = [];
									foreach ($data_list as $key => $value) {
											
										$type_id       = !empty($value->id)?$value->id:'';
										$product_id    = !empty($value->product_id)?$value->product_id:'';
										$description   = !empty($value->description)?$value->description:'';
			
										$product_list[] = array(
											'type_id'     => $type_id,
											'product_id'  => $product_id,
											'description' => $description,
										);
									}
								}
							}
					
					if(!empty($product_list))
					{
						$response['status']       = 1;
				        $response['message']      = "Success"; 
				        $response['data']         = $product_list;
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

			else if($method == '_listSubCategoryProducts_dis')
			{
				$sub_cat_id = $this->input->post('sub_cat_id');
				$type_id = $this->input->post('type_id');
				
				if(!empty($sub_cat_id))
				{    $ty_arr   = explode(',', $type_id);
					$s_category_arr   = explode(',', $sub_cat_id);
					$s_category_count = count($s_category_arr);

				    		for($i = 0; $i < $s_category_count; $i++)
				    		{
								$wheree = array(
									'sub_cat_id' => $s_category_arr[$i],
									'status'      => '1', 
									'published'   => '1'
								);
								$where_in['id'] = $ty_arr;
								
								$data_list = $this->commom_model->getProductType($wheree,'','',"result",'','','','','',$where_in);
								
								if($data_list)
								{
									$product_list = [];
									foreach ($data_list as $key => $value) {
											
										$type_id       = !empty($value->id)?$value->id:'';
										$product_id    = !empty($value->product_id)?$value->product_id:'';
										$description   = !empty($value->description)?$value->description:'';
			
										$product_list[] = array(
											'type_id'     => $type_id,
											'product_id'  => $product_id,
											'description' => $description,
										);
									}
								}
							}
					
					if(!empty($product_list))
					{
						$response['status']       = 1;
				        $response['message']      = "Success"; 
				        $response['data']         = $product_list;
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

			else if($method == '_updateProduct')
			{
				$error = FALSE;
			    $errors = array();
				$required = array('id', 'name', 'category_id', 'vendor_id', 'hsn_code', 'unit');
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
			    	$product_id     = $this->input->post('id');
			    	$unique_code    = $this->input->post('unique_code');
			    	$product_code   = $this->input->post('product_code');
			    	$name           = $this->input->post('name');
			    	$category_id    = $this->input->post('category_id');
			    	$vendor_id      = $this->input->post('vendor_id');
			    	$hsn_code       = $this->input->post('hsn_code');
			    	$price          = $this->input->post('price');
			    	$stock          = $this->input->post('stock');
			    	$vend_stock     = $this->input->post('vend_stock');
			    	$unit           = $this->input->post('unit');
			    	$gst            = $this->input->post('gst');
			    	$item_type      = $this->input->post('item_type');
			    	$salesagents_id = $this->input->post('salesagents_id');
			    	$status         = $this->input->post('status');
			    	$product_type   = $this->input->post('product_type');

			    	$where=array(
			    		'id !='       => $product_id,
				    	'name'        => ucfirst($name),
				    	'category_id' => $category_id,
				    	'vendor_id'   => $vendor_id,
				    	'status'      => '1',
				    	'published'   => '1',
				    );			   

					$column = 'id';

					$overalldatas = $this->commom_model->getProduct($where, '', '', 'result', '', '', '', '', $column);

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
						// Product Stock
			    		if($unit == 1 || $unit == 11)
			    		{
			    			$view_stock   = $stock * 1000;
			    			$stock_detail = $vend_stock * 1000;
			    		}
						else if($unit == 2 || $unit == 4)
			    		{
			    			$view_stock   = $stock;
			    			$stock_detail = $vend_stock / 1000;
			    		}
			    		else
			    		{
			    			$view_stock   = $stock;
			    			$stock_detail = $vend_stock;
			    		}

			    		// Vendor Details
			    		$column_1 = 'vendor_type';

			    		$where_1  = array(
			    			'id'        => $vendor_id,
			    			'status'    => '1', 
							'published' => '1'
			    		);

			    		$vendor_whr  = $this->vendors_model->getVendors($where_1, '', '', 'result', '', '', '', '', $column_1);

			    		$vendor_type = !empty($vendor_whr[0]->vendor_type)?$vendor_whr[0]->vendor_type:'';

						$data = array(
							'unique_code'    => urlSlug($unique_code),
					    	'name'           => ucfirst($name),
					    	'category_id'    => $category_id,
					    	'vendor_id'      => $vendor_id,
					    	'vendor_type'    => $vendor_type,
					    	'hsn_code'       => $hsn_code,
					    	'price'          => digit_val($price),
					    	'stock'          => $stock,
					    	'view_stock'     => $view_stock,
					    	'vend_stock'     => $vend_stock,
					    	'stock_detail'   => $stock_detail,
					    	'unit'           => $unit,
					    	'gst'            => $gst,
					    	'item_type'      => $item_type,
					    	'salesagents_id' => $salesagents_id,
					    	'status'         => $status,
					    	'updatedate'     => date('Y-m-d H:i:s'),
					    );

					    // Image Information
					    $fileCount = count($_FILES['image']['name']);
					    if($fileCount > 0)
					    {
					    	for ($i=0; $i < $fileCount; $i++) {

					    		$image = generateRandomString(13).'.jpg';

								post_img($image, $_FILES['image']['tmp_name'][$i],"upload/product/");

								$data['product_img'] = $image;
				    		}
					    }

			    		$update_id = array('id'=>$product_id);
					    $update    = $this->commom_model->product_update($data, $update_id);

					    $log_data = array(
							'u_id'       => $log_id,
							'role'       => $log_role,
							'table'      => 'tbl_product',
							'auto_id'    => $product_id,
							'action'     => 'update',
							'date'       => date('Y-m-d'),
							'time'       => date('H:i:s'),
							'createdate' => date('Y-m-d H:i:s')
						);

						$log_val = $this->commom_model->log_insert($log_data);

			    		// Product Type Delete
			    		$type_data = array(
					    	'published' => '0',
					    );

			    		$type_whr = array('product_id'=>$product_id);
					    $type_det = $this->commom_model->productType_delete($type_data, $type_whr);

					    // Product Type Update
					    $product_value = json_decode($product_type);

					    $number = 1;
					    foreach ($product_value as $key => $value) {

					    	$sub_code = leadingZeros($number, 6);

					    	$type_id     = !empty($value->type_id)?$value->type_id:'';
					    	$description = !empty($value->description)?$value->description:'';
							$pro_type    = !empty($value->pro_type)?$value->pro_type:'';
							$pro_unit    = !empty($value->pro_unit)?$value->pro_unit:'';
							$mrp_price   = !empty($value->mrp_price)?$value->mrp_price:'';
							$pro_price   = !empty($value->pro_price)?$value->pro_price:'';
							$ven_price   = !empty($value->ven_price)?$value->ven_price:'';
							$dis_price   = !empty($value->dis_price)?$value->dis_price:'';
							$pro_stock   = !empty($value->pro_stock)?$value->pro_stock:'0';
							$type_stock  = !empty($value->type_stock)?$value->type_stock:'0';
							$minimum_stock = !empty($value->minimum_stock)?$value->minimum_stock:'0';

					    	// Product Type Stock
				    		if($unit == 1)
				    		{
				    			$main_stock = $pro_stock * 1000;
				    			$sub_stock  = $type_stock * 1000;
				    			$mini_stock = $minimum_stock * 1000;
				    		}
				    		else if($unit == 2)
				    		{
				    			$main_stock = $pro_stock;
				    			$sub_stock  = $type_stock / 1000;
				    			$mini_stock = $minimum_stock / 1000;
				    		}
				    		else
				    		{
				    			$main_stock = $pro_stock;
				    			$sub_stock  = $type_stock;
				    			$mini_stock = $minimum_stock;
				    		}

					    	if(!empty($type_id))
					    	{
					    		$type_value = array(
					    			'vendor_id'     => $vendor_id,
					    			'vendor_type'   => $vendor_type,
					    			'sub_code'      => $product_code.'/'.$sub_code,
					    			'category_id'   => $category_id,
							    	'product_id'    => $product_id,
							    	'description'   => $description,
							    	'product_type'  => $pro_type,
							    	'mrp_price'     => digit_val($mrp_price),
							    	'product_price' => digit_val($pro_price),
							    	'ven_price'     => digit_val($ven_price),
							    	'dis_price'     => digit_val($dis_price),
							    	'product_stock' => $pro_stock,
							    	'view_stock'    => $main_stock,
							    	'type_stock'    => $type_stock,
							    	'stock_detail'  => $sub_stock,
							    	'minimum_stock' => $mini_stock,
							    	'product_unit'  => $pro_unit,
							    	'published'     => '1',
							    	'createdate'    => date('Y-m-d H:i:s'),
							    );

							    $type_where  = array('id' => $type_id);
							    $type_update = $this->commom_model->productType_update($type_value, $type_where);
					    	}
					    	else
					    	{
					    		$type_value = array(
					    			'vendor_id'     => $vendor_id,
					    			'vendor_type'   => $vendor_type,
					    			'sub_code'      => $product_code.'/'.$sub_code,
					    			'category_id'   => $category_id,
							    	'product_id'    => $product_id,
							    	'description'   => $description,
							    	'product_type'  => $pro_type,
							    	'mrp_price'     => digit_val($mrp_price),
							    	'product_price' => digit_val($pro_price),
							    	'ven_price'     => digit_val($ven_price),
							    	'dis_price'     => digit_val($dis_price),
							    	'product_stock' => $pro_stock,
							    	'view_stock'    => $main_stock,
							    	'type_stock'    => $type_stock,
							    	'stock_detail'  => $sub_stock,
							    	'product_unit'  => $pro_unit,
							    	'createdate'    => date('Y-m-d H:i:s'),
							    );

							    $type_insert = $this->commom_model->productType_insert($type_value);
					    	}

					    	$number++;
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
			    }
			}

			else if($method == '_deleteProduct')
			{	
		    	$product_id = $this->input->post('product_id');

		    	if(!empty($product_id))
		    	{
		    		$data=array(
				    	'published' => '0',
				    );

		    		// Product
		    		$where_1  = array('id'=>$product_id);
				    $update_1 = $this->commom_model->product_delete($data, $where_1);

				    // Product Type
				    $where_2  = array('product_id'=>$product_id);
				    $update_2 = $this->commom_model->productType_delete($data, $where_2);

				    $log_data = array(
						'u_id'       => $log_id,
						'role'       => $log_role,
						'table'      => 'tbl_product',
						'auto_id'    => $product_id,
						'action'     => 'delete',
						'date'       => date('Y-m-d'),
						'time'       => date('H:i:s'),
						'createdate' => date('Y-m-d H:i:s')
					);

					$log_val = $this->commom_model->log_insert($log_data);

				    if($update_1)
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

		// Product Type
		// ***************************************************
		public function productType($param1="",$param2="",$param3="")
		{
			$method = $this->input->post('method');

			if($method == '_listVendorProductType')
			{
				$vendor_id = $this->input->post('vendor_id');

				if($vendor_id != '')
				{
					$where = array(
						'vendor_id' => $vendor_id,
						'status'    => '1', 
						'published' => '1',
					);

					$data_list = $this->commom_model->getProductType($where);

					if($data_list)
					{
						$product_list = [];

						foreach ($data_list as $key => $value) {

							$type_id       = isset($value->id)?$value->id:'';
							$sub_code      = isset($value->sub_code)?$value->sub_code:'';
							$product_id    = isset($value->product_id)?$value->product_id:'';
							$description   = isset($value->description)?$value->description:'';
				            $product_type  = isset($value->product_type)?$value->product_type:'';
				            $product_unit  = isset($value->product_unit)?$value->product_unit:'';
				            $product_price = isset($value->product_price)?$value->product_price:'';
				            $mrp_price     = isset($value->mrp_price)?$value->mrp_price:'';
				            $dis_price     = isset($value->dis_price)?$value->dis_price:'';
				            $product_stock = isset($value->product_stock)?$value->product_stock:'';
				            $type_stock    = isset($value->type_stock)?$value->type_stock:'';
				            $published     = isset($value->published)?$value->published:'';
				            $status        = isset($value->status)?$value->status:'';
				            $createdate    = isset($value->createdate)?$value->createdate:'';

				            // Unit Name
					    	$where_1       = array('id'=>$product_unit);
					    	$data_val      = $this->commom_model->getUnit($where_1);
					    	$unit_name     = isset($data_val[0]->name)?$data_val[0]->name:'';

				            $product_list[] = array(
				            	'type_id'       => $type_id,
				            	'sub_code'      => $sub_code,
		      					'product_id'    => $product_id,
		      					'description'   => $description,
					            'product_type'  => $product_type,
					            'product_unit'  => $product_unit,
					            'unit_name'     => $unit_name,
					            'mrp_price'     => $mrp_price,
					            'product_price' => $product_price,
					            'dis_price'     => $dis_price,
					            'product_stock' => $product_stock,
					            'type_stock'    => $type_stock,
					            'published'     => $published,
					            'status'        => $status,
					            'createdate'    => $createdate,
		      				);
						}

						$response['status']       = 1;
				        $response['message']      = "Success"; 
				        $response['data']         = $product_list;
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
			        $response['message'] = "Not Found"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}

			else if($method == '_listVendorProduct')
			{
				$vendor_id   = $this->input->post('vendor_id');
				$category_id = $this->input->post('category_id');

				$error    = FALSE;
			    $errors   = array();
				$required = array('vendor_id', 'category_id');
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
			    	$where = array(
			    		'vendor_id'   => $vendor_id,
						'category_id' => $category_id,
						'status'      => '1', 
						'published'   => '1',
					);

					$column    = 'id, product_id, category_id, description';

					$data_list = $this->commom_model->getProductTypeImplode($where, '', '', 'result', '', '', '', '', $column);

					if($data_list)
					{
						$product_list = [];
						foreach ($data_list as $key => $value) {

							$type_id     = isset($value->id)?$value->id:'';
							$product_id  = isset($value->product_id)?$value->product_id:'';
							$category_id = isset($value->category_id)?$value->category_id:'';
							$description = isset($value->description)?$value->description:'';

				            $product_list[] = array(
				            	'type_id'     => $type_id,
		      					'product_id'  => $product_id,
		      					'category_id' => $category_id,
		      					'description' => $description,
		      				);
						}

						$response['status']  = 1;
				        $response['message'] = "Success"; 
				        $response['data']    = $product_list;
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

			else if($method == '_listProductType')
			{
				$product_id = $this->input->post('product_id');
				$zone_id    = $this->input->post('zone_id');
				$order_type = $this->input->post('order_type');

				if($product_id != '')
				{
					$where = array(
						'product_id' => $product_id,
						'status'     => '1', 
						'published'  => '1',
					);

					$data_list = $this->commom_model->getProductType($where);

					if($data_list)
					{
						$product_list = [];

						foreach ($data_list as $key => $value) {

							$type_id       = isset($value->id)?$value->id:'';
							$sub_code      = isset($value->sub_code)?$value->sub_code:'';
							$product_id    = isset($value->product_id)?$value->product_id:'';
							$description   = isset($value->description)?$value->description:'';
				            $product_type  = isset($value->product_type)?$value->product_type:'0';
				            $product_unit  = isset($value->product_unit)?$value->product_unit:'';
				            $product_price = isset($value->product_price)?$value->product_price:'';
				            $mrp_price     = isset($value->mrp_price)?$value->mrp_price:'';
				            $ven_price     = isset($value->ven_price)?$value->ven_price:'';
				            $dis_price     = isset($value->dis_price)?$value->dis_price:'';
				            $product_stock = isset($value->product_stock)?$value->product_stock:'0';
				            $type_stock    = isset($value->type_stock)?$value->type_stock:'0';
				            $stock_detail  = isset($value->stock_detail)?$value->stock_detail:'0';
				            $minimum_stock = isset($value->minimum_stock)?$value->minimum_stock:'0';
				            $published     = isset($value->published)?$value->published:'';
				            $status        = isset($value->status)?$value->status:'';
				            $createdate    = isset($value->createdate)?$value->createdate:'';

				            // Unit Name
					    	$where_1       = array('id'=>$product_unit);
					    	$data_val      = $this->commom_model->getUnit($where_1);
					    	$unit_name     = isset($data_val[0]->name)?$data_val[0]->name:'';

					    	if($zone_id)
				    		{
				    			$pdt_pack_cnt = 0;
								$dis_status   = 0;
								$admin_sta    = 0;

					    		// Admin stock status
								$col_2      = 'stock_status';
								$where_2    = array('id' => 1, 'status' => '1', 'published' => '1');
								$admin_data = $this->user_model->getUser($where_2, '', '', 'row', '', '', '', '', $col_2);
								$admin_sta  = zero_check($admin_data->stock_status);

						    	// Assign details
						    	$col_3   = 'id, distributor_id, view_stock';
								$where_3 = array('type_id' => $type_id, 'status' => '1', 'published' => '1');
								$like['zone_id'] = ','.$zone_id.',';

								$assign_data = $this->assignproduct_model->getAssignProductAddtionalDetails($where_3, '', '', 'row', $like, '', '', '', $col_3);

								if($assign_data)
								{
									$distributor_id = zero_check($assign_data->distributor_id);
									$dis_view_stock = zero_check($assign_data->view_stock);

									if($dis_view_stock > 0)
									{
										$pdt_pack_cnt   = $dis_view_stock / $product_type;
									}

									// Distributor stock status
									$col_4      = 'stock_status';
									$where_4    = array('id' => $distributor_id, 'status' => '1', 'published' => '1');
									$dis_data   = $this->distributors_model->getDistributors($where_4, '', '', 'row', '', '', '', '', $col_4);

									if($dis_data)
									{
										$dis_status = zero_check($dis_data->stock_status);
									}
								}
								else
								{
									if($stock_detail > 0)
									{
										$pdt_pack_cnt   = $stock_detail / $product_type;
									}
								}

								if($dis_status == 1)
						    	{
						    		if($pdt_pack_cnt > 0)
						    		{
						    			$product_data = array(
							            	'type_id'       => $type_id,
							            	'sub_code'      => $sub_code,
					      					'product_id'    => $product_id,
					      					'description'   => $description,
								            'product_type'  => $product_type,
								            'product_unit'  => $product_unit,
								            'unit_name'     => $unit_name,
								            'mrp_price'     => $mrp_price,
								            'product_price' => $product_price,
								            'ven_price'     => $ven_price,
								            'dis_price'     => $dis_price,
								            'product_stock' => $product_stock,
								            'type_stock'    => $type_stock,
								            'minimum_stock' => $minimum_stock,
								            'pdt_pack_cnt'  => strval($pdt_pack_cnt),
								            'stock_check'   => strval(1),
								            'published'     => $published,
								            'status'        => $status,
								            'createdate'    => $createdate,
					      				);

					      				array_push($product_list, $product_data);
						    		}
						    	}
						    	else if($dis_status == 2)
						    	{
						    		$product_data = array(
						            	'type_id'       => $type_id,
						            	'sub_code'      => $sub_code,
				      					'product_id'    => $product_id,
				      					'description'   => $description,
							            'product_type'  => $product_type,
							            'product_unit'  => $product_unit,
							            'unit_name'     => $unit_name,
							            'mrp_price'     => $mrp_price,
							            'product_price' => $product_price,
							            'ven_price'     => $ven_price,
							            'dis_price'     => $dis_price,
							            'product_stock' => $product_stock,
							            'type_stock'    => $type_stock,
							            'minimum_stock' => $minimum_stock,
							            'pdt_pack_cnt'  => strval(0),
							            'stock_check'   => strval(2),
							            'published'     => $published,
							            'status'        => $status,
							            'createdate'    => $createdate,
				      				);

				      				array_push($product_list, $product_data);
						    	}
						    	else
						    	{
						    		if($admin_sta == 1)
						    		{
						    			if($pdt_pack_cnt > 0)
						    			{
						    				$product_data = array(
								            	'type_id'       => $type_id,
								            	'sub_code'      => $sub_code,
						      					'product_id'    => $product_id,
						      					'description'   => $description,
									            'product_type'  => $product_type,
									            'product_unit'  => $product_unit,
									            'unit_name'     => $unit_name,
									            'mrp_price'     => $mrp_price,
									            'product_price' => $product_price,
									            'ven_price'     => $ven_price,
									            'dis_price'     => $dis_price,
									            'product_stock' => $product_stock,
									            'type_stock'    => $type_stock,
									            'minimum_stock' => $minimum_stock,
									            'pdt_pack_cnt'  => strval($pdt_pack_cnt),
									            'stock_check'   => strval(1),
									            'published'     => $published,
									            'status'        => $status,
									            'createdate'    => $createdate,
						      				);

						      				array_push($product_list, $product_data);
						    			}
						    		}
						    		else
						    		{
						    			$product_data = array(
							            	'type_id'       => $type_id,
							            	'sub_code'      => $sub_code,
					      					'product_id'    => $product_id,
					      					'description'   => $description,
								            'product_type'  => $product_type,
								            'product_unit'  => $product_unit,
								            'unit_name'     => $unit_name,
								            'mrp_price'     => $mrp_price,
								            'product_price' => $product_price,
								            'ven_price'     => $ven_price,
								            'dis_price'     => $dis_price,
								            'product_stock' => $product_stock,
								            'type_stock'    => $type_stock,
								            'minimum_stock' => $minimum_stock,
								            'pdt_pack_cnt'  => strval(0),
								            'stock_check'   => strval(2),
								            'published'     => $published,
								            'status'        => $status,
								            'createdate'    => $createdate,
					      				);

					      				array_push($product_list, $product_data);
						    		}
						    	}
				    		}
					    	else
					    	{
					    		$product_data = array(
					            	'type_id'       => $type_id,
					            	'sub_code'      => $sub_code,
			      					'product_id'    => $product_id,
			      					'description'   => $description,
						            'product_type'  => $product_type,
						            'product_unit'  => $product_unit,
						            'unit_name'     => $unit_name,
						            'mrp_price'     => $mrp_price,
						            'product_price' => $product_price,
						            'ven_price'     => $ven_price,
						            'dis_price'     => $dis_price,
						            'product_stock' => $product_stock,
						            'type_stock'    => $type_stock,
						            'minimum_stock' => $minimum_stock,
						            'pdt_pack_cnt'  => strval(0),
						            'published'     => $published,
						            'status'        => $status,
						            'createdate'    => $createdate,
			      				);

			      				array_push($product_list, $product_data);
					    	}
					    	
						}

						if($product_list)
						{
							$response['status']       = 1;
					        $response['message']      = "Success"; 
					        $response['data']         = $product_list;
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
				        $response['message'] = "Not Found"; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
					}
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

			else if($method == '_detailProductType')
			{
				$type_id   = $this->input->post('type_id');
				$state_id  = $this->input->post('state_id');
				$city_id   = $this->input->post('city_id');
				$zone_id   = $this->input->post('zone_id');
				$outlet_id = $this->input->post('outlet_id');
				$view_type = $this->input->post('view_type');

				if($type_id != '')
				{
					$where = array(
						'id'        => $type_id,
						'status'    => '1', 
						'published' => '1',
					);

					$data_list = $this->commom_model->getProductType($where);

					if($data_list)
					{
						$product_list = [];

						foreach ($data_list as $key => $value) {

							$type_id        = isset($value->id)?$value->id:'';
							$sub_code       = isset($value->sub_code)?$value->sub_code:'';
							$product_id     = isset($value->product_id)?$value->product_id:'';
							$description    = isset($value->description)?$value->description:'';
				            $product_type   = isset($value->product_type)?$value->product_type:'';
				            $product_unit   = isset($value->product_unit)?$value->product_unit:'';
				            $product_price  = isset($value->product_price)?$value->product_price:'0';
				            $mrp_price      = isset($value->mrp_price)?$value->mrp_price:'';
				            $ven_price      = isset($value->ven_price)?$value->ven_price:'';
				            $dis_price      = isset($value->dis_price)?$value->dis_price:'';
				            $product_stock  = isset($value->product_stock)?$value->product_stock:'';
				            $type_stock     = isset($value->type_stock)?$value->type_stock:'';
				            $stock_detail   = isset($value->stock_detail)?$value->stock_detail:'0';
				            $published      = isset($value->published)?$value->published:'';
				            $status         = isset($value->status)?$value->status:'';
				            $createdate     = isset($value->createdate)?$value->createdate:'';
				            $original_price = number_format((float)empty_check($product_price), 2, '.', '');

				            // Unit Name
					    	$where_1       = array('id'=>$product_unit);
					    	$data_val      = $this->commom_model->getUnit($where_1);
					    	$unit_name     = isset($data_val[0]->name)?$data_val[0]->name:'';

					    	$pdt_pack_cnt = 0;
							$dis_status   = 0;
							$admin_sta    = 0;

				    		// Admin stock status
							$col_2      = 'stock_status';
							$where_2    = array('id' => 1, 'status' => '1', 'published' => '1');
							$admin_data = $this->user_model->getUser($where_2, '', '', 'row', '', '', '', '', $col_2);
							$admin_sta  = zero_check($admin_data->stock_status);

					    	// Assign details
					    	$col_3   = 'id, distributor_id, view_stock';
							$where_3 = array('type_id' => $type_id, 'status' => '1', 'published' => '1');
							$like['zone_id'] = ','.$zone_id.',';

							$assign_data = $this->assignproduct_model->getAssignProductAddtionalDetails($where_3, '', '', 'row', $like, '', '', '', $col_3);

							if($assign_data)
							{
								$distributor_id = zero_check($assign_data->distributor_id);
								$dis_view_stock = zero_check($assign_data->view_stock);

								if($dis_view_stock > 0)
								{
									$pdt_pack_cnt   = $dis_view_stock / $product_type;
								}

								// Distributor stock status
								$col_4      = 'stock_status';
								$where_4    = array('id' => $distributor_id, 'status' => '1', 'published' => '1');
								$dis_data   = $this->distributors_model->getDistributors($where_4, '', '', 'row', '', '', '', '', $col_4);

								if($dis_data)
								{
									$dis_status = zero_check($dis_data->stock_status);
								}
							}
							else
							{
								if($stock_detail > 0)
								{
									$pdt_pack_cnt   = $stock_detail / $product_type;
								}
							}

					    	$pdt_price = 0;

					    	if($view_type == '1')
					    	{
					    		// Distributor Price Details
						    	$whr_6  = array(
						    		'outlet_id'   => $outlet_id,
						    		'type_id'     => $type_id,
						    		'published'   => '1',
						    		'status'      => '1',
						    	);

						    	$option['order_by']   = 'id';
								$option['disp_order'] = 'DESC';

								$limit  = 1;
								$offset = 0;

								$column = 'product_price';

						    	$price_val = $this->pricemaster_model->getOutletPrice($whr_6, $limit, $offset, 'result', '', '', $option, '', $column);

						    	$ofr_price = isset($price_val[0]->product_price)?$price_val[0]->product_price:'0';

						    	if($ofr_price != 0)
						    	{
						    		$pdt_price = empty_check($ofr_price);
						    	}
						    	else
						    	{
						    		if($outlet_id != 0)
						    		{
						    			$whr_1 = array(
						            		'A.state_id'      => $state_id,
						            		'A.city_id'       => $city_id,
						            		'A.beat_id'       => $zone_id,
						            		'A.outlet_id'     => $outlet_id,
						            		'A.type_id'       => $type_id,
											'A.end_date >='   => date('Y-m-d'),
						            		'A.published'     => '1',
						            	);

						            	$col_1 = 'A.product_price, A.loyalty_type';
							    		$res_1 = $this->loyalty_model->getProductLoyaltyDetailsJoin($whr_1, '', '', 'row', '', '', '', '', $col_1);

							    		if($res_1)
							    		{
							    			$pdt_1  = zero_check($res_1->product_price);
							    			$type_1 = zero_check($res_1->loyalty_type);

							    			if($type_1 == 1)
							    			{
							    				$cal_val = $original_price * $pdt_1 / 100;
    											$price_1 = $original_price - $cal_val;

    											$pdt_price = number_format((float)empty_check($price_1), 2, '.', '');
							    			}
							    			else
							    			{
							    				$pdt_price = number_format((float)empty_check($pdt_1), 2, '.', '');
							    			}
							    		}
							    		else
							    		{
							    			if($zone_id != 0)
								    		{
								    			$whr_2 = array(
								            		'A.state_id'      => $state_id,
								            		'A.city_id'       => $city_id,
								            		'A.beat_id'       => $zone_id,
								            		'A.outlet_id'     => 0,
								            		'A.type_id'       => $type_id,
													'A.end_date >='   => date('Y-m-d'),
								            		'A.published'     => '1',
								            	);

								            	$col_2 = 'A.product_price, A.loyalty_type';
									    		$res_2 = $this->loyalty_model->getProductLoyaltyDetailsJoin($whr_2, '', '', 'row', '', '', '', '', $col_2);

									    		if($res_2)
									    		{
									    			$pdt_2  = zero_check($res_2->product_price);
									    			$type_2 = zero_check($res_2->loyalty_type);

									    			if($type_2 == 1)
									    			{
									    				$cal_val = $original_price * $pdt_2 / 100;
		    											$price_2 = $original_price - $cal_val;

		    											$pdt_price = number_format((float)empty_check($price_2), 2, '.', '');
									    			}
									    			else
									    			{
									    				$pdt_price = number_format((float)empty_check($pdt_2), 2, '.', '');
									    			}
									    		}
									    		else
									    		{
									    			if($city_id != 0)
										    		{
										    			$whr_3 = array(
										            		'A.state_id'      => $state_id,
										            		'A.city_id'       => $city_id,
										            		'A.beat_id'       => 0,
										            		'A.outlet_id'     => 0,
										            		'A.type_id'       => $type_id,
															'A.end_date >='   => date('Y-m-d'),
										            		'A.published'     => '1',
										            	);

										            	$col_3 = 'A.product_price, A.loyalty_type';
											    		$res_3 = $this->loyalty_model->getProductLoyaltyDetailsJoin($whr_3, '', '', 'row', '', '', '', '', $col_3);

											    		if($res_3)
											    		{
											    			$pdt_3  = zero_check($res_3->product_price);
											    			$type_3 = zero_check($res_3->loyalty_type);

											    			if($type_3 == 1)
											    			{
											    				$cal_val = $original_price * $pdt_3 / 100;
				    											$price_3 = $original_price - $cal_val;

				    											$pdt_price = number_format((float)empty_check($price_3), 2, '.', '');
											    			}
											    			else
											    			{
											    				$pdt_price = number_format((float)empty_check($pdt_3), 2, '.', '');
											    			}
											    		}
											    		else
											    		{
											    			if($state_id != 0)
												    		{
												    			$whr_4 = array(
												            		'A.state_id'      => $state_id,
												            		'A.city_id'       => 0,
												            		'A.beat_id'       => 0,
												            		'A.outlet_id'     => 0,
												            		'A.type_id'       => $type_id,
																	'A.end_date >='   => date('Y-m-d'),
												            		'A.published'     => '1',
												            	);

												            	$col_4 = 'A.product_price, A.loyalty_type';
													    		$res_4 = $this->loyalty_model->getProductLoyaltyDetailsJoin($whr_4, '', '', 'row', '', '', '', '', $col_4);

													    		if($res_4)
													    		{
													    			$pdt_4  = zero_check($res_4->product_price);
													    			$type_4 = zero_check($res_4->loyalty_type);

													    			if($type_4 == 1)
													    			{
													    				$cal_val = $original_price * $pdt_4 / 100;
						    											$price_4 = $original_price - $cal_val;

						    											$pdt_price = number_format((float)empty_check($price_4), 2, '.', '');
													    			}
													    			else
													    			{
													    				$pdt_price = number_format((float)empty_check($pdt_4), 2, '.', '');
													    			}
													    		}
													    		else
													    		{
													    			$whr_5 = array(
													            		'A.state_id'      => 0,
													            		'A.city_id'       => 0,
													            		'A.beat_id'       => 0,
													            		'A.outlet_id'     => 0,
													            		'A.type_id'       => $type_id,
																		'A.end_date >='   => date('Y-m-d'),
													            		'A.published'     => '1',
													            	);

													            	$col_5 = 'A.product_price, A.loyalty_type';
														    		$res_5 = $this->loyalty_model->getProductLoyaltyDetailsJoin($whr_5, '', '', 'row', '', '', '', '', $col_5);

														    		if($res_5)
														    		{
														    			$pdt_5  = zero_check($res_5->product_price);
														    			$type_5 = zero_check($res_5->loyalty_type);

														    			if($type_5 == 1)
														    			{
														    				$cal_val = $original_price * $pdt_5 / 100;
							    											$price_5 = $original_price - $cal_val;

							    											$pdt_price = number_format((float)empty_check($price_5), 2, '.', '');
														    			}
														    			else
														    			{
														    				$pdt_price = number_format((float)empty_check($pdt_5), 2, '.', '');
														    			}
														    		}
														    		else
														    		{
														    			$pdt_price = number_format((float)empty_check($original_price), 2, '.', '');
														    		}
													    		}
												    		}
											    		}
										    		}
									    		}
								    		}
							    		}
						    		}
						    	}
					    	}
					    	else
					    	{
					    		if($outlet_id != '')
					    		{
					    			// Distributor Price Details
							    	$whr_1  = array(
							    		'outlet_id'   => $outlet_id,
							    		'type_id'     => $type_id,
							    		'published'   => '1',
							    		'status'      => '1',
							    	);

							    	$option['order_by']   = 'id';
									$option['disp_order'] = 'DESC';

									$limit  = 1;
									$offset = 0;
									$col_1  = 'product_price';

							    	$price_val = $this->pricemaster_model->getOutletPrice($whr_1, $limit, $offset, 'result', '', '', $option, '', $col_1);

							    	$ofr_price = isset($price_val[0]->product_price)?$price_val[0]->product_price:'0';

							    	$pdt_price = 0;
							    	if($ofr_price != 0)
							    	{
							    		$pdt_price = empty_check($ofr_price);
							    	}
							    	else
							    	{
							    		$str_whr = array(
							    			'id'        => $outlet_id, 
							    			'status'    => '1', 
							    			'published' => '1'
							    		);

							    		$str_col = 'state_id, city_id, zone_id';

							    		$str_res = $this->outlets_model->getOutlets($str_whr, '', '', 'row', '', '', '', '', $str_col);

							    		$state_val = empty_check($str_res->state_id);
										$city_val  = empty_check($str_res->city_id);
										$zone_val  = empty_check($str_res->zone_id);

										if($outlet_id != 0)
							    		{
							    			$whr_1 = array(
							            		'A.state_id'      => $state_id,
							            		'A.city_id'       => $city_id,
							            		'A.beat_id'       => $zone_id,
							            		'A.outlet_id'     => $outlet_id,
							            		'A.type_id'       => $type_id,
												'A.end_date >='   => date('Y-m-d'),
							            		'A.published'     => '1',
							            	);

							            	$col_1 = 'A.product_price, A.loyalty_type';
								    		$res_1 = $this->loyalty_model->getProductLoyaltyDetailsJoin($whr_1, '', '', 'row', '', '', '', '', $col_1);

								    		if($res_1)
								    		{
								    			$pdt_1  = zero_check($res_1->product_price);
								    			$type_1 = zero_check($res_1->loyalty_type);

								    			if($type_1 == 1)
								    			{
								    				$cal_val = $original_price * $pdt_1 / 100;
	    											$price_1 = $original_price - $cal_val;

	    											$pdt_price = number_format((float)empty_check($price_1), 2, '.', '');
								    			}
								    			else
								    			{
								    				$pdt_price = number_format((float)empty_check($pdt_1), 2, '.', '');
								    			}
								    		}
								    		else
								    		{
								    			if($zone_id != 0)
									    		{
									    			$whr_2 = array(
									            		'A.state_id'      => $state_id,
									            		'A.city_id'       => $city_id,
									            		'A.beat_id'       => $zone_id,
									            		'A.outlet_id'     => 0,
									            		'A.type_id'       => $type_id,
														'A.end_date >='   => date('Y-m-d'),
									            		'A.published'     => '1',
									            	);

									            	$col_2 = 'A.product_price, A.loyalty_type';
										    		$res_2 = $this->loyalty_model->getProductLoyaltyDetailsJoin($whr_2, '', '', 'row', '', '', '', '', $col_2);

										    		if($res_2)
										    		{
										    			$pdt_2  = zero_check($res_2->product_price);
										    			$type_2 = zero_check($res_2->loyalty_type);

										    			if($type_2 == 1)
										    			{
										    				$cal_val = $original_price * $pdt_2 / 100;
			    											$price_2 = $original_price - $cal_val;

			    											$pdt_price = number_format((float)empty_check($price_2), 2, '.', '');
										    			}
										    			else
										    			{
										    				$pdt_price = number_format((float)empty_check($pdt_2), 2, '.', '');
										    			}
										    		}
										    		else
										    		{
										    			if($city_id != 0)
											    		{
											    			$whr_3 = array(
											            		'A.state_id'      => $state_id,
											            		'A.city_id'       => $city_id,
											            		'A.beat_id'       => 0,
											            		'A.outlet_id'     => 0,
											            		'A.type_id'       => $type_id,
																'A.end_date >='   => date('Y-m-d'),
											            		'A.published'     => '1',
											            	);

											            	$col_3 = 'A.product_price, A.loyalty_type';
												    		$res_3 = $this->loyalty_model->getProductLoyaltyDetailsJoin($whr_3, '', '', 'row', '', '', '', '', $col_3);

												    		if($res_3)
												    		{
												    			$pdt_3  = zero_check($res_3->product_price);
												    			$type_3 = zero_check($res_3->loyalty_type);

												    			if($type_3 == 1)
												    			{
												    				$cal_val = $original_price * $pdt_3 / 100;
					    											$price_3 = $original_price - $cal_val;

					    											$pdt_price = number_format((float)empty_check($price_3), 2, '.', '');
												    			}
												    			else
												    			{
												    				$pdt_price = number_format((float)empty_check($pdt_3), 2, '.', '');
												    			}
												    		}
												    		else
												    		{
												    			if($state_id != 0)
													    		{
													    			$whr_4 = array(
													            		'A.state_id'      => $state_id,
													            		'A.city_id'       => 0,
													            		'A.beat_id'       => 0,
													            		'A.outlet_id'     => 0,
													            		'A.type_id'       => $type_id,
																		'A.end_date >='   => date('Y-m-d'),
													            		'A.published'     => '1',
													            	);

													            	$col_4 = 'A.product_price, A.loyalty_type';
														    		$res_4 = $this->loyalty_model->getProductLoyaltyDetailsJoin($whr_4, '', '', 'row', '', '', '', '', $col_4);

														    		if($res_4)
														    		{
														    			$pdt_4  = zero_check($res_4->product_price);
														    			$type_4 = zero_check($res_4->loyalty_type);

														    			if($type_4 == 1)
														    			{
														    				$cal_val = $original_price * $pdt_4 / 100;
							    											$price_4 = $original_price - $cal_val;

							    											$pdt_price = number_format((float)empty_check($price_4), 2, '.', '');
														    			}
														    			else
														    			{
														    				$pdt_price = number_format((float)empty_check($pdt_4), 2, '.', '');
														    			}
														    		}
														    		else
														    		{
														    			$whr_5 = array(
														            		'A.state_id'      => 0,
														            		'A.city_id'       => 0,
														            		'A.beat_id'       => 0,
														            		'A.outlet_id'     => 0,
														            		'A.type_id'       => $type_id,
																			'A.end_date >='   => date('Y-m-d'),
														            		'A.published'     => '1',
														            	);

														            	$col_5 = 'A.product_price, A.loyalty_type';
															    		$res_5 = $this->loyalty_model->getProductLoyaltyDetailsJoin($whr_5, '', '', 'row', '', '', '', '', $col_5);

															    		if($res_5)
															    		{
															    			$pdt_5  = zero_check($res_5->product_price);
															    			$type_5 = zero_check($res_5->loyalty_type);

															    			if($type_5 == 1)
															    			{
															    				$cal_val = $original_price * $pdt_5 / 100;
								    											$price_5 = $original_price - $cal_val;

								    											$pdt_price = number_format((float)empty_check($price_5), 2, '.', '');
															    			}
															    			else
															    			{
															    				$pdt_price = number_format((float)empty_check($pdt_5), 2, '.', '');
															    			}
															    		}
															    		else
															    		{
															    			$pdt_price = number_format((float)empty_check($original_price), 2, '.', '');
															    		}
														    		}
													    		}
												    		}
											    		}
										    		}
									    		}
								    		}
							    		}
							    	}
					    		}
					    		else
					    		{
					    			$pdt_price = $original_price;
					    		}
					    	}

		      				if($dis_status == 1)
					    	{
					    		if($pdt_pack_cnt > 0)
					    		{
					    			$product_data = array(
						            	'type_id'       => $type_id,
						            	'sub_code'      => $sub_code,
				      					'product_id'    => $product_id,
				      					'description'   => $description,
							            'product_type'  => $product_type,
							            'product_unit'  => $product_unit,
							            'unit_name'     => $unit_name,
							            'mrp_price'     => $mrp_price,
							            'product_price' => $pdt_price,
							            'ven_price'     => $ven_price,
							            'dis_price'     => $dis_price,
							            'product_stock' => $product_stock,
							            'type_stock'    => $type_stock,
							            'pdt_pack_cnt'  => strval($pdt_pack_cnt),
										'stock_check'   => strval(1),
							            'published'     => $published,
							            'status'        => $status,
							            'createdate'    => $createdate,
				      				);

					    			array_push($product_list, $product_data);
					    		}
					    	}
					    	else if($dis_status == 2)
					    	{
					    		$product_data = array(
					            	'type_id'       => $type_id,
					            	'sub_code'      => $sub_code,
			      					'product_id'    => $product_id,
			      					'description'   => $description,
						            'product_type'  => $product_type,
						            'product_unit'  => $product_unit,
						            'unit_name'     => $unit_name,
						            'mrp_price'     => $mrp_price,
						            'product_price' => $pdt_price,
						            'ven_price'     => $ven_price,
						            'dis_price'     => $dis_price,
						            'product_stock' => $product_stock,
						            'type_stock'    => $type_stock,
						            'pdt_pack_cnt'  => strval(0),
									'stock_check'   => strval(2),
						            'published'     => $published,
						            'status'        => $status,
						            'createdate'    => $createdate,
			      				);

				    			array_push($product_list, $product_data);
					    	}
					    	else
					    	{
					    		if($admin_sta == 1)
					    		{
					    			if($pdt_pack_cnt > 0)
					    			{
					    				$product_data = array(
							            	'type_id'       => $type_id,
							            	'sub_code'      => $sub_code,
					      					'product_id'    => $product_id,
					      					'description'   => $description,
								            'product_type'  => $product_type,
								            'product_unit'  => $product_unit,
								            'unit_name'     => $unit_name,
								            'mrp_price'     => $mrp_price,
								            'product_price' => $pdt_price,
								            'ven_price'     => $ven_price,
								            'dis_price'     => $dis_price,
								            'product_stock' => $product_stock,
								            'type_stock'    => $type_stock,
								            'pdt_pack_cnt'  => strval($pdt_pack_cnt),
									        'stock_check'   => strval(1),
								            'published'     => $published,
								            'status'        => $status,
								            'createdate'    => $createdate,
					      				);

						    			array_push($product_list, $product_data);
					    			}
					    		}
					    		else
					    		{
					    			$product_data = array(
						            	'type_id'       => $type_id,
						            	'sub_code'      => $sub_code,
				      					'product_id'    => $product_id,
				      					'description'   => $description,
							            'product_type'  => $product_type,
							            'product_unit'  => $product_unit,
							            'unit_name'     => $unit_name,
							            'mrp_price'     => $mrp_price,
							            'product_price' => $pdt_price,
							            'ven_price'     => $ven_price,
							            'dis_price'     => $dis_price,
							            'product_stock' => $product_stock,
							            'type_stock'    => $type_stock,
							            'pdt_pack_cnt'  => strval(0),
								        'stock_check'   => strval(2),
							            'published'     => $published,
							            'status'        => $status,
							            'createdate'    => $createdate,
				      				);

					    			array_push($product_list, $product_data);
					    		}
					    	}
						}

						$response['status']       = 1;
				        $response['message']      = "Success"; 
				        $response['data']         = $product_list;
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
			        $response['message'] = "Not Found"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}

			else if($method == '_purchaseProductType')
			{
				$type_id   = $this->input->post('type_id');
				$state_id  = $this->input->post('state_id');
				$city_id   = $this->input->post('city_id');
				$zone_id   = $this->input->post('zone_id');
				$outlet_id = $this->input->post('outlet_id');
				$view_type = $this->input->post('view_type');

				if($type_id != '')
				{
					$where = array(
						'id'        => $type_id,
						'status'    => '1', 
						'published' => '1',
					);

					$data_list = $this->commom_model->getProductType($where);

					if($data_list)
					{
						$product_data = [];

						foreach ($data_list as $key => $value) {

							$type_id        = isset($value->id)?$value->id:'';
							$sub_code       = isset($value->sub_code)?$value->sub_code:'';
							$product_id     = isset($value->product_id)?$value->product_id:'';
							$description    = isset($value->description)?$value->description:'';
				            $product_type   = isset($value->product_type)?$value->product_type:'';
				            $product_unit   = isset($value->product_unit)?$value->product_unit:'';
				            $product_price  = isset($value->product_price)?$value->product_price:'0';
				            $mrp_price      = isset($value->mrp_price)?$value->mrp_price:'';
				            $ven_price      = isset($value->ven_price)?$value->ven_price:'';
				            $dis_price      = isset($value->dis_price)?$value->dis_price:'';
				            $product_stock  = isset($value->product_stock)?$value->product_stock:'';
				            $type_stock     = isset($value->type_stock)?$value->type_stock:'';
				            $stock_detail   = isset($value->stock_detail)?$value->stock_detail:'0';
				            $published      = isset($value->published)?$value->published:'';
				            $status         = isset($value->status)?$value->status:'';
				            $createdate     = isset($value->createdate)?$value->createdate:'';
				            $original_price = number_format((float)empty_check($product_price), 2, '.', '');

				            // Unit Name
					    	$where_1       = array('id'=>$product_unit);
					    	$data_val      = $this->commom_model->getUnit($where_1);
					    	$unit_name     = isset($data_val[0]->name)?$data_val[0]->name:'';

					
							$product_data = array(
								'type_id'       => $type_id,
								'sub_code'      => $sub_code,
								'product_id'    => $product_id,
								'description'   => $description,
								'product_type'  => $product_type,
								'product_unit'  => $product_unit,
								'unit_name'     => $unit_name,
								'mrp_price'     => $mrp_price,
								'product_price' => 0,
								'ven_price'     => $ven_price,
								'dis_price'     => $dis_price,
								'product_stock' => $product_stock,
								'type_stock'    => $type_stock,
								'pdt_pack_cnt'  => strval(0),
								'stock_check'   => strval(2),
								'published'     => $published,
								'status'        => $status,
								'createdate'    => $createdate,
							  );
				    		
						}

						$response['status']       = 1;
				        $response['message']      = "Success"; 
				        $response['data']         = $product_data;
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
			        $response['message'] = "Not Found"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}

			else if($method == '_lowStockProduct')
			{
				$limit  = $this->input->post('limit');
	    		$offset = $this->input->post('offset');
	    		$search = $this->input->post('search');

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

	    		if($search !='')
	    		{
	    			$like['name'] = $search;
	    		}
	    		else
	    		{
	    			$like = [];
	    		}

				$column = 'id, minimum_stock, view_stock';
				$where  = array(
					'status'        => '1', 
					'published'     => '1',
				);

				$pdt_list = $this->commom_model->getProductType($where, '', '', 'result', '', '', '', '', $column);

				if($pdt_list)
				{
					$product_list = '';
					foreach ($pdt_list as $key => $val) {
						$product_id    = !empty($val->id)?$val->id:'0';
					    $minimum_stock = !empty($val->minimum_stock)?$val->minimum_stock:'0';
					    $view_stock    = !empty($val->view_stock)?$val->view_stock:'0';

					    if($minimum_stock != 0 && $minimum_stock >= $view_stock)
					    {
					    	$product_list .= $product_id.',';
					    }
					}

					$product_val = substr($product_list, 0, -1);

					$whr_1 = array(
						'type_id'   => $product_val,
						'status'    => '1',
						'published' => '1',
					);

					$col_1 = 'id';
					$overalldatas = $this->commom_model->getProductTypeImplode($whr_1, '', '', 'result', $like, '', '', '', $col_1);

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

					$col_2 = 'id, description, product_price, product_stock, view_stock, minimum_stock, product_unit';
					$data_list = $this->commom_model->getProductTypeImplode($whr_1, $limit, $offset, 'result', $like, '', $option, '', $col_2);

					if($data_list)
					{
						$product_data = [];
						foreach ($data_list as $key => $pdt_val) {

							$pdtType_id    = empty_check($pdt_val->id);
							$description   = empty_check($pdt_val->description);
							$product_price = empty_check($pdt_val->product_price);
							$product_stock = empty_check($pdt_val->product_stock);
							$view_stock    = empty_check($pdt_val->view_stock);
							$minimum_stock = empty_check($pdt_val->minimum_stock);
							$product_unit  = empty_check($pdt_val->product_unit);

							// Product Stock
				    		if($product_unit == 1 || $product_unit == 11)
				    		{
				    			$stock_data   = $minimum_stock / 1000;
				    		}
				    		else if($product_unit == 2 || $product_unit == 4)
				    		{
				    			$stock_data   = $minimum_stock;
				    		}
				    		else
				    		{
				    			$stock_data   = $minimum_stock;
				    		}

				    		// Product unit
				    		$unit_whr = array('id'   => $product_unit);
				    		$unit_col = 'name';
							$unit_res = $this->commom_model->getUnit($unit_whr, '', '', 'row', '', '', '', '', $unit_col);
							$unit_val = empty_check($unit_res->name);

							$product_data[] = array(
								'type_id'       => $pdtType_id,
								'description'   => $description,
								'product_price' => $product_price,
								'product_stock' => strval($product_stock),
								'minimum_stock' => strval($stock_data),
								'product_unit'  => $unit_val,
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
				        $response['data']         = $product_data;
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
			        $response['message'] = "Not Found"; 
			        $response['data']    = [];
			        echo json_encode($response);
			        return;
				}
			}

			else if($method == '_lowStockProduct')
			{
				$limit  = $this->input->post('limit');
	    		$offset = $this->input->post('offset');
	    		$search = $this->input->post('search');

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

	    		if($search !='')
	    		{
	    			$like['name'] = $search;
	    		}
	    		else
	    		{
	    			$like = [];
	    		}

				$column = 'id, minimum_stock, view_stock';
				$where  = array(
					'status'        => '1', 
					'published'     => '1',
				);

				$pdt_list = $this->commom_model->getProductType($where, '', '', 'result', '', '', '', '', $column);

				if($pdt_list)
				{
					$product_list = '';
					foreach ($pdt_list as $key => $val) {
						$product_id    = !empty($val->id)?$val->id:'0';
					    $minimum_stock = !empty($val->minimum_stock)?$val->minimum_stock:'0';
					    $view_stock    = !empty($val->view_stock)?$val->view_stock:'0';

					    if($minimum_stock != 0 && $minimum_stock >= $view_stock)
					    {
					    	$product_list .= $product_id.',';
					    }
					}

					$product_val = substr($product_list, 0, -1);

					$whr_1 = array(
						'type_id'   => $product_val,
						'status'    => '1',
						'published' => '1',
					);

					$col_1 = 'id';
					$overalldatas = $this->commom_model->getProductTypeImplode($whr_1, '', '', 'result', $like, '', '', '', $col_1);

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

					$col_2 = 'id, description, product_price, product_stock, view_stock, minimum_stock, product_unit';
					$data_list = $this->commom_model->getProductTypeImplode($whr_1, $limit, $offset, 'result', $like, '', $option, '', $col_2);

					if($data_list)
					{
						$product_data = [];
						foreach ($data_list as $key => $pdt_val) {

							$pdtType_id    = empty_check($pdt_val->id);
							$description   = empty_check($pdt_val->description);
							$product_price = empty_check($pdt_val->product_price);
							$product_stock = empty_check($pdt_val->product_stock);
							$view_stock    = empty_check($pdt_val->view_stock);
							$minimum_stock = empty_check($pdt_val->minimum_stock);
							$product_unit  = empty_check($pdt_val->product_unit);

							// Product Stock
				    		if($product_unit == 1 || $product_unit == 11)
				    		{
				    			$stock_data   = $minimum_stock / 1000;
				    		}
				    		else if($product_unit == 2 || $product_unit == 4)
				    		{
				    			$stock_data   = $minimum_stock;
				    		}
				    		else
				    		{
				    			$stock_data   = $minimum_stock;
				    		}

				    		// Product unit
				    		$unit_whr = array('id'   => $product_unit);
				    		$unit_col = 'name';
							$unit_res = $this->commom_model->getUnit($unit_whr, '', '', 'row', '', '', '', '', $unit_col);
							$unit_val = empty_check($unit_res->name);

							$product_data[] = array(
								'type_id'       => $pdtType_id,
								'description'   => $description,
								'product_price' => $product_price,
								'product_stock' => strval($product_stock),
								'minimum_stock' => strval($stock_data),
								'product_unit'  => $unit_val,
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
				        $response['data']         = $product_data;
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
	}
?>
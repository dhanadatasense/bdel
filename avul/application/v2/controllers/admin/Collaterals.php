<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Collaterals extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function add_collaterals($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			if($param1 =='Edit')
			{
				$collateral_id = !empty($param2)?$param2:'';

				$where = array(
            		'collateral_id' => $collateral_id,
            		'method'        => '_collateralsDetails'
            	);

            	$data_list  = avul_call(API_URL.'collaterals/api/manage_collaterals',$where);

				$page['dataval']    = $data_list['data'];
				$page['method']     = '_editCollaterals';
				$page['page_title'] = "Edit Collaterals";
			}
			else
			{
				$page['dataval']    = '';
				$page['method']     = '_addCollaterals';
				$page['page_title'] = "Add Collaterals";
			}
			
			$page['value']        = "admin";
        	$page['controller']   = "collaterals";
        	$page['function']     = "add_collaterals";
        	$page['load_data']    = "";
			$page['main_heading'] = "Collaterals";
			$page['sub_heading']  = "Collaterals";
			$page['pre_title']    = "List Collaterals";
			$page['page_access']  = userAccess('collaterals-view');
			$page['pre_menu']     = "index.php/admin/collaterals/list_collaterals";
			$page['submit_url']   = "collaterals/api/add_collaterals";
			$data['page_temp']    = $this->load->view('admin/collaterals/add_collaterals',$page,TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "add_collaterals";
			$this->bassthaya->load_admin_form_template($data);
		}

		public function list_collaterals($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	if($param1 == '')
			{
				$page['value']        = "admin";
	        	$page['controller']   = "collaterals";
	        	$page['function']     = "list_collaterals";
	        	$page['load_data']    = "";
				$page['main_heading'] = "Collaterals";
				$page['sub_heading']  = "Collaterals";
				$page['page_title']   = "List Collaterals";
				$page['pre_title']    = "Add Collaterals";
				$page['page_access']  = userAccess('collaterals-add');
				$page['pre_menu']     = "index.php/admin/collaterals/add_collaterals";
				$data['page_temp']    = $this->load->view('admin/collaterals/list_collaterals',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_collaterals";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('collaterals-view'))
				{
					$limit    = $this->input->post('limitval');
	            	$page     = $this->input->post('page');
	            	$search   = $this->input->post('search');
	            	$cur_page = isset($page)?$page:'1';
	            	$_offset  = ($cur_page-1) * $limit;
	            	$nxt_page = $cur_page + 1;
	            	$pre_page = $cur_page - 1;

	            	$where = array(
	            		'offset'    => $_offset,
	            		'limit'     => $limit,
	            		'search'    => $search,
	            		'method'    => '_listCollateralsPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'collaterals/api/manage_collaterals',$where);
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

		            		$collateral_id = empty_check($value['id']);
				            $name          = empty_check($value['name']);
				            $type          = empty_check($value['type']);
				            $active_status = empty_check($value['status']);
				            $created_date  = empty_check($value['created_date']);

				            if($active_status == '1')
			                {
			                	$status_view = '<span class="badge badge-success">Active</span>';
			                }
			                else
			                {
			                	$status_view = '<span class="badge badge-danger">In Active</span>';
			                }

			                if($type == '1')
			                {
			                	$collateral_type = '<span class="badge badge-success">Permanent</span>';
			                }
			                else
			                {
			                	$collateral_type = '<span class="badge badge-danger">Temporary</span>';
			                }

			                $edit   = '';
				            $delete = '';
				            if(userAccess('collaterals-edit') == TRUE)
				            {
				            	$edit = '<a href="'.BASE_URL.'index.php/admin/collaterals/add_collaterals/Edit/'.$collateral_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
				            }

				            if(userAccess('collaterals-delete') == TRUE)
				            {
				            	$delete = '<a data-row="'.$i.'" data-id="'.$collateral_id.'" data-value="admin" data-cntrl="collaterals" data-func="list_collaterals" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
				            }

			                $table .= '
						    	<tr class="row_'.$i.'">
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$name.'</td>
	                                <td class="line_height">'.$collateral_type.'</td>
	                                <td class="line_height">'.$status_view.'</td>';
	                                if(userAccess('collaterals-edit') == TRUE || userAccess('collaterals-delete') == TRUE):
		                            	$table .= '<td>'.$edit.$delete.'</td>';
		                        	endif;
	                            $table .='</tr>
						    ';
						    $i++;
		            	}

		            	$prev = '';
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
				}
				else
		    	{
		    		$status     = 0;
		        	$message    = 'Access denied';
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
				$id = $this->input->post('id');

				if(!empty($id))	
				{
					$data = array(
				    	'collateral_id' => $id,
				    	'deleted_by'    => $this->session->userdata('id'),
				    	'method'        => '_deleteCollaterals'
				    );

				    $data_save = avul_call(API_URL.'collaterals/api/manage_collaterals', $data);

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
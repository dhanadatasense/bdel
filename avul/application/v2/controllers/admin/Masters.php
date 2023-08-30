<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Masters extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('encryption');
		$this->load->helper('url');
	}

	public function add_financial_year($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$formpage = $this->input->post('formpage');

		if ($formpage == 'BTBM_X_P') {
			$error = FALSE;
			$financial_name = $this->input->post('financial_name');
			$start_date     = $this->input->post('start_date');
			$end_date       = $this->input->post('end_date');
			$method         = $this->input->post('method');

			$required = array('financial_name', 'start_date', 'end_date');
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
					if (userAccess('financial-year-add')) {
						$log_id   = $this->session->userdata('id');
						$log_role = $this->session->userdata('user_role');

						$data = array(
							'log_id'     => $log_id,
							'log_role'   => $log_role,
							'name'       => $financial_name,
							'start_date' => date('Y-m-d', strtotime($start_date)),
							'end_date'   => date('Y-m-d', strtotime($end_date)),
							'createdate' => date('Y-m-d H:i:s'),
							'method'     => '_addFinancial',
						);

						$data_save = avul_call(API_URL . 'master/api/financial', $data);

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
				} else {
					if (userAccess('financial-year-edit')) {
						$log_id   = $this->session->userdata('id');
						$log_role = $this->session->userdata('user_role');

						$financial_id = $this->input->post('financial_id');
						$pstatus  = $this->input->post('pstatus');

						$data = array(
							'log_id'       => $log_id,
							'log_role'     => $log_role,
							'financial_id' => $financial_id,
							'name'         => $financial_name,
							'start_date'   => date('Y-m-d', strtotime($start_date)),
							'end_date'     => date('Y-m-d', strtotime($end_date)),
							'status'       => $pstatus,
							'method'       => '_updateFinancial'
						);

						$data_save = avul_call(API_URL . 'master/api/financial', $data);

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
		} else {
			if ($param1 == 'Edit') {
				$financial_id = !empty($param2) ? $param2 : '';

				$where = array(
					'financial_id' => $financial_id,
					'method'       => '_detailFinancial'
				);

				$data_list  = avul_call(API_URL . 'master/api/financial', $where);

				$page['dataval']    = $data_list['data'];
				$page['method']     = 'BTBM_X_U';
				$page['page_title'] = "Edit Financial Year";
			} else {
				$page['dataval']    = '';
				$page['method']     = 'BTBM_X_C';
				$page['page_title'] = "Add Financial Year";
			}

			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Financial Year";
			$page['pre_title']    = "List Financial Year";
			$page['page_access']  = userAccess('financial-year-view');
			$page['pre_menu']     = "index.php/admin/masters/list_financial_year";
			$data['page_temp']    = $this->load->view('admin/master/financial_year/add_financial_year', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "add_financial_year";
			$this->bassthaya->load_admin_form_template($data);
		}
	}

	public function list_financial_year($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		if ($param1 == '') {
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Financial Year";
			$page['page_title']   = "List Financial Year";
			$page['pre_title']    = "Add Financial Year";
			$page['page_access']  = userAccess('financial-year-add');
			$page['pre_menu']     = "index.php/admin/masters/add_financial_year";
			$data['page_temp']    = $this->load->view('admin/master/financial_year/list_financial_year', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "list_financial_year";
			$this->bassthaya->load_admin_form_template($data);
		} else if ($param1 == 'data_list') {
			if (userAccess('financial-year-view')) {
				$limit    = $this->input->post('limitval');
				$page     = $this->input->post('page');
				$search   = $this->input->post('search');
				$sort_column = $this->input->post('sort_column');
				$sort_type   = $this->input->post('sort_type');
				$cur_page = isset($page) ? $page : '1';
				$_offset  = ($cur_page - 1) * $limit;
				$nxt_page = $cur_page + 1;
				$pre_page = $cur_page - 1;

				$where = array(
					'offset'  => $_offset,
					'limit'   => $limit,
					'search'  => $search,
					'sort_column' => $sort_column,
					'sort_type' => $sort_type,
					'method'  => '_listFinancialPaginate'
				);

				$data_list  = avul_call(API_URL . 'master/api/financial', $where);
				$data_value = !empty($data_list['data']) ? $data_list['data'] : '';

				if (!empty($data_value)) {
					$count    = count($data_value);
					$total    = isset($data_list['total_record']) ? $data_list['total_record'] : '';
					$tot_page = ceil($total / $limit);

					$status  = 1;
					$message = 'Success';
					$table   = '';

					$i = 1;
					foreach ($data_value as $key => $value) {
						$financial_id  = !empty($value['financial_id']) ? $value['financial_id'] : '';
						$name          = !empty($value['financial_name']) ? $value['financial_name'] : '';
						$start_date    = !empty($value['start_date']) ? $value['start_date'] : '';
						$end_date      = !empty($value['end_date']) ? $value['end_date'] : '';
						$active_status = !empty($value['status']) ? $value['status'] : '';
						$createdate    = !empty($value['createdate']) ? $value['createdate'] : '';

						if ($active_status == '1') {
							$status_view = '<span class="badge badge-success">Active</span>';
						} else {
							$status_view = '<span class="badge badge-danger">In Active</span>';
						}

						$edit   = '';
						$delete = '';
						if (userAccess('financial-year-edit') == TRUE) {
							$edit = '<a href="' . BASE_URL . 'index.php/admin/masters/add_financial_year/Edit/' . $financial_id . '" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
						}
						if (userAccess('financial-year-delete') == TRUE) {
							$delete = '<a data-row="' . $i . '" data-id="' . $financial_id . '" data-value="admin" data-cntrl="masters" data-func="list_financial_year" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
						}

						$table .= '
						    	<tr class="row_' . $i . '">
	                                <td class="line_height">' . $i . '</td>
	                                <td class="line_height">' . $name . '</td>
	                                <td class="line_height">' . $start_date . '</td>
	                                <td class="line_height">' . $end_date . '</td>
	                                <td class="line_height">' . $status_view . '</td>';
						if (userAccess('financial-year-edit') == TRUE || userAccess('financial-year-delete') == TRUE) :
							$table .= '<td>' . $edit . $delete . '</td>';
						endif;
						$table .= '</tr>
						    ';
						$i++;
					}

					$prev    = '';

					$next = '
			        		<tr>
			        			<td>';
					if ($cur_page >= 2) :
						$next .= '<span data-page="' . $pre_page . '" class="pages btn btn-warning waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>';
					endif;
					$next .= '</td>
			        			<td>';
					if ($tot_page > $cur_page) :
						$next .= '<span data-page="' . $nxt_page . '" class="pages btn btn-success waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
					endif;
					$next .= '</td>
			        		</tr>
			        	';
				} else {
					$status     = 0;
					$message    = 'No Records';
					$table      = '';
					$next       = '';
					$prev       = '';
				}
			} else {
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
		} else if ($param1 == 'delete') {
			$id       = $this->input->post('id');
			$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');

			if (!empty($id)) {
				$data = array(
					'log_id'       => $log_id,
					'log_role'     => $log_role,
					'financial_id' => $id,
					'method'       => '_deleteFinancial'
				);

				$data_save = avul_call(API_URL . 'master/api/financial', $data);

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
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			}
		}
	}

	public function add_state($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$formpage = $this->input->post('formpage');

		if ($formpage == 'BTBM_X_P') {
			$error = FALSE;
			$state_name = $this->input->post('state_name');
			$state_code = $this->input->post('state_code');
			$gst_code   = $this->input->post('gst_code');
			$method     = $this->input->post('method');

			$required = array('state_name', 'state_code', 'gst_code');
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
					if (userAccess('state-add')) {
						$log_id   = $this->session->userdata('id');
						$log_role = $this->session->userdata('user_role');

						$data = array(
							'log_id'     => $log_id,
							'log_role'   => $log_role,
							'name'       => ucfirst($state_name),
							'state_code' => strtoupper($state_code),
							'gst_code'   => $gst_code,
							'createdate' => date('Y-m-d H:i:s'),
							'method'     => '_addState',
						);

						$data_save = avul_call(API_URL . 'master/api/state', $data);

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
				} else {
					if (userAccess('state-edit')) {
						$state_id = $this->input->post('state_id');
						$pstatus  = $this->input->post('pstatus');
						$log_id   = $this->session->userdata('id');
						$log_role = $this->session->userdata('user_role');

						$data = array(
							'log_id'     => $log_id,
							'log_role'   => $log_role,
							'id'         => $state_id,
							'name'       => ucfirst($state_name),
							'state_code' => strtoupper($state_code),
							'gst_code'   => $gst_code,
							'status'     => $pstatus,
							'method'     => '_updateState'
						);

						$data_save = avul_call(API_URL . 'master/api/state', $data);

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
		} else {
			if ($param1 == 'Edit') {
				$state_id = !empty($param2) ? $param2 : '';

				$where = array(
					'state_id' => $state_id,
					'method'   => '_detailState'
				);

				$data_list  = avul_call(API_URL . 'master/api/state', $where);

				$page['dataval']    = $data_list['data'];
				$page['method']     = 'BTBM_X_U';
				$page['page_title'] = "Edit State";
			} else {
				$page['dataval']    = '';
				$page['method']     = 'BTBM_X_C';
				$page['page_title'] = "Add State";
			}
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "State";
			$page['pre_title']    = "List State";
			$page['page_access']  = userAccess('state-view');
			$page['pre_menu']     = "index.php/admin/masters/list_state";
			$data['page_temp']    = $this->load->view('admin/master/state/add_state', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "add_state";
			$this->bassthaya->load_admin_form_template($data);
		}
	}

	public function list_state($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		if ($param1 == '') {
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "State";
			$page['page_title']   = "List State";
			$page['pre_title']    = "Add State";
			$page['page_access']  = userAccess('state-add');
			$page['pre_menu']     = "index.php/admin/masters/add_state";
			$data['page_temp']    = $this->load->view('admin/master/state/list_state', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "list_state";
			$this->bassthaya->load_admin_form_template($data);
		} else if ($param1 == 'data_list') {
			if (userAccess('state-view')) {
				$limit    = $this->input->post('limitval');
				$page     = $this->input->post('page');
				$search   = $this->input->post('search');
				$cur_page = isset($page) ? $page : '1';
				$_offset  = ($cur_page - 1) * $limit;
				$nxt_page = $cur_page + 1;
				$pre_page = $cur_page - 1;

				$where = array(
					'offset'  => $_offset,
					'limit'   => $limit,
					'search'  => $search,
					'method'  => '_listStatePaginate'
				);

				$data_list  = avul_call(API_URL . 'master/api/state', $where);
				$data_value = !empty($data_list['data']) ? $data_list['data'] : '';

				if (!empty($data_value)) {

					$count    = count($data_value);
					$total    = isset($data_list['total_record']) ? $data_list['total_record'] : '';
					$tot_page = ceil($total / $limit);

					$status  = 1;
					$message = 'Success';
					$table   = '';

					$i = 1;
					foreach ($data_value as $key => $value) {
						$state_id      = !empty($value['state_id']) ? $value['state_id'] : '';
						$state_name    = !empty($value['state_name']) ? $value['state_name'] : '';
						$state_code    = !empty($value['state_code']) ? $value['state_code'] : '';
						$gst_code      = !empty($value['gst_code']) ? $value['gst_code'] : '';
						$active_status = !empty($value['status']) ? $value['status'] : '';
						$createdate    = !empty($value['createdate']) ? $value['createdate'] : '';

						if ($active_status == '1') {
							$status_view = '<span class="badge badge-success"><i class="las la-eye"></i> Active</span>';
						} else {
							$status_view = '<span class="badge badge-warning"><i class="las la-low-vision"></i> InActive</span>';
						}

						$edit   = '';
						$delete = '';
						if (userAccess('state-edit') == TRUE) {
							$edit = '<a href="' . BASE_URL . 'index.php/admin/masters/add_state/Edit/' . $state_id . '" class="button_clr btn btn-primary"><i class="ft-edit"></i></a>';
						}
						if (userAccess('state-delete') == TRUE) {
							// $delete = '<a data-row="'.$i.'" data-id="'.$state_id.'" data-value="admin" data-cntrl="masters" data-func="list_state" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
						}

						$table .= '
						    	<tr class="row_' . $i . '">
	                                <td class="line_height">' . $i . '</td>
	                                <td class="line_height">' . $state_name . '</td>
	                                <td class="line_height">' . $state_code . '</td>
	                                <td class="line_height">' . $gst_code . '</td>
	                                <td class="line_height">' . $status_view . '</td>';
						if (userAccess('state-edit') == TRUE || userAccess('state-delete') == TRUE) :
							$table .= '<td>' . $edit . $delete . '</td>';
						endif;
						$table .= '</tr>
						    ';
						$i++;
					}

					$prev    = '';

					$next = '
			        		<tr>
			        			<td>';
					if ($cur_page >= 2) :
						$next .= '<span data-page="' . $pre_page . '" class="pages btn btn-warning waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>';
					endif;
					$next .= '</td>
			        			<td>';
					if ($tot_page > $cur_page) :
						$next .= '<span data-page="' . $nxt_page . '" class="pages btn btn-success waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
					endif;
					$next .= '</td>
			        		</tr>
			        	';
				} else {
					$status     = 0;
					$message    = 'No Records';
					$table      = '';
					$next       = '';
					$prev       = '';
				}
			} else {
				$status     = 0;
				$message    = 'Access denied';
				$table      = '';
				$next       = '';
				$prev       = '';
			}

			$this->load->library('pagination');
			$config['base_url'] = 'http://example.com/index.php/test/page/';
			$config['total_rows'] = 1050;
			$config["uri_segment"] = 3;
			$config['per_page'] = $limit;

			$this->pagination->initialize($config);
			$response['pagination'] = $this->pagination->create_links();
			$response['status']     = $status;
			$response['result']     = $table;
			$response['message']    = $message;
			$response['next']       = $next;
			$response['prev']       = $prev;
			echo json_encode($response);
			return;
		} else if ($param1 == 'delete') {
			$id       = $this->input->post('id');
			$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');

			if (!empty($id)) {
				$data = array(
					'log_id'   => $log_id,
					'log_role' => $log_role,
					'state_id' => $id,
					'method'   => '_deleteState'
				);

				$data_save = avul_call(API_URL . 'master/api/state', $data);

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
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			}
		}
	}

	public function add_city($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$formpage = $this->input->post('formpage');

		if ($formpage == 'BTBM_X_P') {
			$error = FALSE;
			$state_id  = $this->input->post('state_id');
			$city_name = $this->input->post('city_name');
			$city_code = $this->input->post('city_code');
			$method    = $this->input->post('method');
			$log_id    = $this->session->userdata('id');
			$log_role  = $this->session->userdata('user_role');

			$required = array('state_id', 'city_name', 'city_code');
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
					if (userAccess('city-add')) {
						$data = array(
							'log_id'     => $log_id,
							'log_role'   => $log_role,
							'state_id'   => $state_id,
							'name'       => ucfirst($city_name),
							'city_code'  => strtoupper($city_code),
							'createdate' => date('Y-m-d H:i:s'),
							'method'     => '_addCity'
						);

						$data_save = avul_call(API_URL . 'master/api/city', $data);

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
				} else {
					if (userAccess('city-edit')) {
						$city_id  = $this->input->post('city_id');
						$pstatus  = $this->input->post('pstatus');

						$data = array(
							'log_id'    => $log_id,
							'log_role'  => $log_role,
							'id'        => $city_id,
							'state_id'  => $state_id,
							'name'      => ucfirst($city_name),
							'city_code' => strtoupper($city_code),
							'status'    => $pstatus,
							'method'    => '_updateCity'
						);

						$data_save = avul_call(API_URL . 'master/api/city', $data);

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
		} else {
			if ($param1 == 'Edit') {
				$city_id = !empty($param2) ? $param2 : '';

				$where_1 = array(
					'city_id' => $city_id,
					'method'  => '_detailCity'
				);

				$data_value = avul_call(API_URL . 'master/api/city', $where_1);

				$where_2 = array(
					'method'   => '_listState'
				);

				$data_list  = avul_call(API_URL . 'master/api/state', $where_2);

				$page['state_val']  = $data_list['data'];
				$page['dataval']    = $data_value['data'];
				$page['method']     = 'BTBM_X_U';
				$page['page_title'] = "Edit City";
			} else {
				$where_2 = array(
					'method'   => '_listState'
				);

				$data_list  = avul_call(API_URL . 'master/api/state', $where_2);

				$page['state_val']  = $data_list['data'];
				$page['dataval']    = '';
				$page['method']     = 'BTBM_X_C';
				$page['page_title'] = "Add City";
			}
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "City";
			$page['pre_title']    = "List City";
			$page['page_access']  = userAccess('city-view');
			$page['pre_menu']     = "index.php/admin/masters/list_city";
			$data['page_temp']    = $this->load->view('admin/master/city/add_city', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "add_city";
			$this->bassthaya->load_admin_form_template($data);
		}
	}

	public function list_city($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		if ($param1 == '') {
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "City";
			$page['page_title']   = "List City";
			$page['pre_title']    = "Add City";
			$page['page_access']  = userAccess('city-add');
			$page['pre_menu']     = "index.php/admin/masters/add_city";
			$data['page_temp']    = $this->load->view('admin/master/city/list_city', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "list_city";
			$this->bassthaya->load_admin_form_template($data);
		} else if ($param1 == 'data_list') {
			if (userAccess('city-view')) {
				$limit    = $this->input->post('limitval');
				$page     = $this->input->post('page');
				$search   = $this->input->post('search');
				$cur_page = isset($page) ? $page : '1';
				$_offset  = ($cur_page - 1) * $limit;
				$nxt_page = $cur_page + 1;
				$pre_page = $cur_page - 1;

				$where = array(
					'offset'  => $_offset,
					'limit'   => $limit,
					'search'  => $search,
					'method'  => '_listCityPaginate'
				);

				$data_list  = avul_call(API_URL . 'master/api/city', $where);
				$data_value = !empty($data_list['data']) ? $data_list['data'] : '';

				if (!empty($data_value)) {

					$count    = count($data_value);
					$total    = isset($data_list['total_record']) ? $data_list['total_record'] : '';
					$tot_page = ceil($total / $limit);

					$status  = 1;
					$message = 'Success';
					$table   = '';

					$i = 1;
					foreach ($data_value as $key => $value) {
						$city_id       = !empty($value['city_id']) ? $value['city_id'] : '';
						$state_id      = !empty($value['state_id']) ? $value['state_id'] : '';
						$state_name    = !empty($value['state_name']) ? $value['state_name'] : '';
						$city_code     = !empty($value['city_code']) ? $value['city_code'] : '';
						$city_name     = !empty($value['city_name']) ? $value['city_name'] : '';
						$active_status = !empty($value['status']) ? $value['status'] : '';
						$createdate    = !empty($value['createdate']) ? $value['createdate'] : '';

						if ($active_status == '1') {
							$status_view = '<span class="badge badge-success">Active</span>';
						} else {
							$status_view = '<span class="badge badge-danger">In Active</span>';
						}

						$edit   = '';
						$delete = '';
						if (userAccess('city-edit') == TRUE) {
							$edit = '<a href="' . BASE_URL . 'index.php/admin/masters/add_city/Edit/' . $city_id . '" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
						}
						if (userAccess('city-delete') == TRUE) {
							$delete = '<a data-row="' . $i . '" data-id="' . $city_id . '" data-value="admin" data-cntrl="masters" data-func="list_city" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
						}

						$table .= '
						    	<tr class="row_' . $i . '">
	                                <td class="line_height">' . $i . '</td>
	                                <td class="line_height">' . $state_name . '</td>
	                                <td class="line_height">' . $city_code . '</td>
	                                <td class="line_height">' . $city_name . '</td>
	                                <td class="line_height">' . $status_view . '</td>';
						if (userAccess('city-edit') == TRUE || userAccess('city-delete') == TRUE) :
							$table .= '<td>' . $edit . $delete . '</td>';
						endif;
						$table .= '</tr>
						    ';
						$i++;
					}

					$prev    = '';

					$next = '
			        		<tr>
			        			<td>';
					if ($cur_page >= 2) :
						$next .= '<span data-page="' . $pre_page . '" class="pages btn btn-warning waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>';
					endif;
					$next .= '</td>
			        			<td>';
					if ($tot_page > $cur_page) :
						$next .= '<span data-page="' . $nxt_page . '" class="pages btn btn-success waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
					endif;
					$next .= '</td>
			        		</tr>
			        	';
				} else {
					$status     = 0;
					$message    = 'No Records';
					$table      = '';
					$next       = '';
					$prev       = '';
				}
			} else {
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
		} else if ($param1 == 'delete') {
			$id       = $this->input->post('id');
			$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');

			if (!empty($id)) {
				$data = array(
					'log_id'   => $log_id,
					'log_role' => $log_role,
					'city_id'  => $id,
					'method'   => '_deleteCity'
				);

				$data_save = avul_call(API_URL . 'master/api/city', $data);

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
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			}
		}
	}

	public function add_beat($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$formpage = $this->input->post('formpage');
		$method   = $this->input->post('method');

		if ($formpage == 'BTBM_X_P') {
			$error = FALSE;
			$state_id  = $this->input->post('state_id');
			$city_id   = $this->input->post('city_id');
			$zone_name = $this->input->post('zone_name');
			$method    = $this->input->post('method');
			$log_id    = $this->session->userdata('id');
			$log_role  = $this->session->userdata('user_role');

			$required = array('state_id', 'city_id', 'zone_name');
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
					if (userAccess('beat-add')) {
						$where_1 = array(
							'state_id' => $state_id,
							'method'   => '_detailState',
						);

						$dataVal_1  = avul_call(API_URL . 'master/api/state', $where_1);
						$stateVal   = $dataVal_1['data'][0];

						$state_code = !empty($stateVal['state_code']) ? $stateVal['state_code'] : '';

						$where_2 = array(
							'city_id' => $city_id,
							'method'   => '_detailCity',
						);

						$dataVal_2  = avul_call(API_URL . 'master/api/city', $where_2);
						$cityVal    = $dataVal_2['data'][0];

						$city_code  = !empty($cityVal['city_code']) ? $cityVal['city_code'] : '';

						$zone_value = $state_code . '/' . $city_code . '/' . $zone_name;

						$data = array(
							'log_id'        => $log_id,
							'log_role'      => $log_role,
							'state_id'      => $state_id,
							'city_id'       => $city_id,
							'name'          => strtoupper($zone_value),
							'createdate'    => date('Y-m-d H:i:s'),
							'method'        => '_addZone',
						);

						$data_save = avul_call(API_URL . 'master/api/zone', $data);

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
				} else {
					if (userAccess('beat-edit')) {
						$zone_id = $this->input->post('zone_id');
						$pstatus = $this->input->post('pstatus');

						$where_1 = array(
							'state_id' => $state_id,
							'method'   => '_detailState',
						);

						$dataVal_1  = avul_call(API_URL . 'master/api/state', $where_1);
						$stateVal   = $dataVal_1['data'][0];

						$state_code = !empty($stateVal['state_code']) ? $stateVal['state_code'] : '';

						$where_2 = array(
							'city_id' => $city_id,
							'method'   => '_detailCity',
						);

						$dataVal_2  = avul_call(API_URL . 'master/api/city', $where_2);
						$cityVal    = $dataVal_2['data'][0];

						$city_code  = !empty($cityVal['city_code']) ? $cityVal['city_code'] : '';

						$zone_value = $state_code . '/' . $city_code . '/' . $zone_name;

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

						$data_save = avul_call(API_URL . 'master/api/zone', $data);

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
		} else if ($param1 == 'getCity_name') {
			$state_id = $this->input->post('state_id');

			$where = array(
				'state_id' => $state_id,
				'method'   => '_listCity'
			);

			$city_list   = avul_call(API_URL . 'master/api/city', $where);
			$city_result = $city_list['data'];

			$option = '<option value="">Select Value</option>';

			if (!empty($city_result)) {
				foreach ($city_result as $key => $value) {
					$city_id   = !empty($value['city_id']) ? $value['city_id'] : '';
					$city_name = !empty($value['city_name']) ? $value['city_name'] : '';

					$option .= '<option value=' . $city_id . '>' . $city_name . '</option>';
				}
			}

			$response['status']  = 1;
			$response['message'] = 'success';
			$response['data']    = $option;
			echo json_encode($response);
			return;
		} else {
			if ($param1 == 'Edit') {
				$zone_id = !empty($param2) ? $param2 : '';

				$where_1 = array(
					'zone_id' => $zone_id,
					'method'  => '_detailZone'
				);

				$data_list  = avul_call(API_URL . 'master/api/zone', $where_1);

				$where_2 = array(
					'method'   => '_listState'
				);

				$state_list  = avul_call(API_URL . 'master/api/state', $where_2);

				$start_value = !empty($data_list['data'][0]['state_id']) ? $data_list['data'][0]['state_id'] : '';

				$where_3 = array(
					'state_id' => $start_value,
					'method'   => '_listCity'
				);

				$city_list = avul_call(API_URL . 'master/api/city', $where_3);

				$page['dataval']    = $data_list['data'];
				$page['state_val']  = $state_list['data'];
				$page['city_val']   = $city_list['data'];
				$page['method']     = 'BTBM_X_U';
				$page['page_title'] = "Edit Beat";
			} else {
				$where_1 = array(
					'method'   => '_listState'
				);

				$state_list = avul_call(API_URL . 'master/api/state', $where_1);

				$page['dataval']    = '';
				$page['state_val']  = $state_list['data'];
				$page['method']     = 'BTBM_X_C';
				$page['page_title'] = "Add Beat";
			}
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Beat";
			$page['pre_title']    = "List Beat";
			$page['page_access']  = userAccess('beat-view');
			$page['pre_menu']     = "index.php/admin/masters/list_beat";
			$data['page_temp']    = $this->load->view('admin/master/beat/add_beat', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "add_beat";
			$this->bassthaya->load_admin_form_template($data);
		}
	}

	public function list_beat($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		if ($param1 == '') {
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Beat";
			$page['page_title']   = "List Beat";
			$page['pre_title']    = "Add Beat";
			$page['page_access']  = userAccess('beat-add');
			$page['pre_menu']     = "index.php/admin/masters/add_beat";
			$data['page_temp']    = $this->load->view('admin/master/beat/list_beat', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "list_beat";
			$this->bassthaya->load_admin_form_template($data);
		} else if ($param1 == 'data_list') {
			if (userAccess('beat-view')) {
				$limit    = $this->input->post('limitval');
				$page     = $this->input->post('page');
				$search   = $this->input->post('search');
				$cur_page = isset($page) ? $page : '1';
				$_offset  = ($cur_page - 1) * $limit;
				$nxt_page = $cur_page + 1;
				$pre_page = $cur_page - 1;

				$where = array(
					'offset'  => $_offset,
					'limit'   => $limit,
					'search'  => $search,
					'method'  => '_listZonePaginate'
				);

				$data_list  = avul_call(API_URL . 'master/api/zone', $where);
				$data_value = !empty($data_list['data']) ? $data_list['data'] : '';

				if (!empty($data_value)) {

					$count    = count($data_value);
					$total    = isset($data_list['total_record']) ? $data_list['total_record'] : '';
					$tot_page = ceil($total / $limit);

					$status  = 1;
					$message = 'Success';
					$table   = '';

					$i = 1;
					foreach ($data_value as $key => $value) {
						$zone_id       = !empty($value['zone_id']) ? $value['zone_id'] : '';
						$state_name    = !empty($value['state_name']) ? $value['state_name'] : '';
						$city_name     = !empty($value['city_name']) ? $value['city_name'] : '';
						$zone_name     = !empty($value['zone_name']) ? $value['zone_name'] : '';
						$active_status = !empty($value['status']) ? $value['status'] : '';
						$createdate    = !empty($value['createdate']) ? $value['createdate'] : '';

						if ($active_status == '1') {
							$status_view = '<span class="badge badge-success">Active</span>';
						} else {
							$status_view = '<span class="badge badge-danger">In Active</span>';
						}

						$edit   = '';
						$delete = '';
						if (userAccess('beat-edit') == TRUE) {
							$edit = '<a href="' . BASE_URL . 'index.php/admin/masters/add_beat/Edit/' . $zone_id . '" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
						}
						if (userAccess('beat-delete') == TRUE) {
							$delete = '<a data-row="' . $i . '" data-id="' . $zone_id . '" data-value="admin" data-cntrl="masters" data-func="list_beat" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
						}

						$table .= '
						    	<tr class="row_' . $i . '">
	                                <td class="line_height">' . $i . '</td>
	                                <td class="line_height">' . $state_name . '</td>
	                                <td class="line_height">' . $city_name . '</td>
	                                <td class="line_height">' . $zone_name . '</td>
	                                <td class="line_height">' . $status_view . '</td>';
						if (userAccess('beat-edit') == TRUE || userAccess('beat-delete') == TRUE) :
							$table .= '<td>' . $edit . $delete . '</td>';
						endif;
						$table .= '</tr>
						    ';
						$i++;
					}

					$prev    = '';

					$next = '
			        		<tr>
			        			<td>';
					if ($cur_page >= 2) :
						$next .= '<span data-page="' . $pre_page . '" class="pages btn btn-warning waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>';
					endif;
					$next .= '</td>
			        			<td>';
					if ($tot_page > $cur_page) :
						$next .= '<span data-page="' . $nxt_page . '" class="pages btn btn-success waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
					endif;
					$next .= '</td>
			        		</tr>
			        	';
				} else {
					$status     = 0;
					$message    = 'No Records';
					$table      = '';
					$next       = '';
					$prev       = '';
				}
			} else {
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
		} else if ($param1 == 'delete') {
			$id       = $this->input->post('id');
			$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');

			if (!empty($id)) {
				$data = array(
					'log_id'   => $log_id,
					'log_role' => $log_role,
					'zone_id'  => $id,
					'method'   => '_deleteZone'
				);

				$data_save = avul_call(API_URL . 'master/api/zone', $data);

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
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			}
		}
	}

	public function add_privilege($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$formpage = $this->input->post('formpage');

		if ($formpage == 'BTBM_X_P') {
			$error = FALSE;
			$header         = $this->input->post('header');
			$privilege_name = $this->input->post('privilege_name');
			$privilege_type = $this->input->post('privilege_type');
			$method         = $this->input->post('method');
			$log_id         = $this->session->userdata('id');
			$log_role       = $this->session->userdata('user_role');

			$required = array('header', 'privilege_name');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if ($error == TRUE) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields	";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				$privilege_val = '';
				if (!empty($privilege_type)) {
					$privilege_val = implode(',', $privilege_type);
				}

				if ($method == 'BTBM_X_C') {
					if (userAccess('privilege-add')) {
						$data = array(
							'log_id'         => $log_id,
							'log_role'       => $log_role,
							'header'         => ucfirst($header),
							'privilege'      => ucfirst($privilege_name),
							'short_code'     => urlSlug($privilege_name),
							'privilege_type' => $privilege_val,
							'createdate'     => date('Y-m-d H:i:s'),
							'method'         => '_addPrivilege',
						);

						$data_save = avul_call(API_URL . 'master/api/privilege', $data);

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
				} else {
					if (userAccess('privilege-edit')) {
						$privilege_id = $this->input->post('privilege_id');
						$pstatus      = $this->input->post('pstatus');

						$data = array(
							'log_id'         => $log_id,
							'log_role'       => $log_role,
							'id'             => $privilege_id,
							'header'         => ucfirst($header),
							'privilege'      => ucfirst($privilege_name),
							'short_code'     => urlSlug($privilege_name),
							'privilege_type' => $privilege_val,
							'status'         => $pstatus,
							'method'         => '_updatePrivilege'
						);

						$data_save = avul_call(API_URL . 'master/api/privilege', $data);

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
		} else {
			if ($param1 == 'Edit') {
				$privilege_id = !empty($param2) ? $param2 : '';

				$where = array(
					'privilege_id' => $privilege_id,
					'method'       => '_detailPrivilege'
				);

				$data_list  = avul_call(API_URL . 'master/api/privilege', $where);

				$page['dataval']    = $data_list['data'];
				$page['method']     = 'BTBM_X_U';
				$page['page_title'] = "Edit Privilege";
			} else {
				$page['dataval']    = '';
				$page['method']     = 'BTBM_X_C';
				$page['page_title'] = "Add Privilege";
			}
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Privilege";
			$page['pre_title']    = "List Privilege";
			$page['page_access']  = userAccess('privilege-view');
			$page['pre_menu']     = "index.php/admin/masters/list_privilege";
			$data['page_temp']    = $this->load->view('admin/master/privilege/add_privilege', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "add_privilege";
			$this->bassthaya->load_admin_form_template($data);
		}
	}

	public function list_privilege($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		if ($param1 == '') {
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Privilege";
			$page['page_title']   = "List Privilege";
			$page['pre_title']    = "Add Privilege";
			$page['page_access']  = userAccess('privilege-add');
			$page['pre_menu']     = "index.php/admin/masters/add_privilege";
			$data['page_temp']    = $this->load->view('admin/master/privilege/list_privilege', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "list_privilege";
			$this->bassthaya->load_admin_form_template($data);
		} else if ($param1 == 'data_list') {
			if (userAccess('privilege-view')) {
				$limit    = $this->input->post('limitval');
				$page     = $this->input->post('page');
				$search   = $this->input->post('search');
				$cur_page = isset($page) ? $page : '1';
				$_offset  = ($cur_page - 1) * $limit;
				$nxt_page = $cur_page + 1;
				$pre_page = $cur_page - 1;

				$where = array(
					'offset'  => $_offset,
					'limit'   => $limit,
					'search'  => $search,
					'method'  => '_listPrivilegePaginate'
				);

				$data_list  = avul_call(API_URL . 'master/api/privilege', $where);
				$data_value = !empty($data_list['data']) ? $data_list['data'] : '';

				if (!empty($data_value)) {

					$count    = count($data_value);
					$total    = isset($data_list['total_record']) ? $data_list['total_record'] : '';
					$tot_page = ceil($total / $limit);

					$status  = 1;
					$message = 'Success';
					$table   = '';

					$i = 1;
					foreach ($data_value as $key => $value) {
						$privilege_id   = !empty($value['privilege_id']) ? $value['privilege_id'] : '';
						$header         = !empty($value['header']) ? $value['header'] : '';
						$privilege_name = !empty($value['privilege_name']) ? $value['privilege_name'] : '';
						$active_status  = !empty($value['status']) ? $value['status'] : '';
						$createdate     = !empty($value['createdate']) ? $value['createdate'] : '';

						if ($active_status == '1') {
							$status_view = '<span class="badge badge-success">Active</span>';
						} else {
							$status_view = '<span class="badge badge-danger">In Active</span>';
						}

						$edit   = '';
						$delete = '';
						if (userAccess('privilege-edit') == TRUE) {
							$edit = '<a href="' . BASE_URL . 'index.php/admin/masters/add_privilege/Edit/' . $privilege_id . '" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
						}
						if (userAccess('privilege-delete') == TRUE) {
							$delete = '<a data-row="' . $i . '" data-id="' . $privilege_id . '" data-value="admin" data-cntrl="masters" data-func="list_privilege" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
						}

						$table .= '
						    	<tr class="row_' . $i . '">
	                                <td class="line_height">' . $i . '</td>
	                                <td class="line_height">' . $header . '</td>
	                                <td class="line_height">' . $privilege_name . '</td>
	                                <td class="line_height">' . $status_view . '</td>';
						if (userAccess('privilege-edit') == TRUE || userAccess('privilege-delete') == TRUE) :
							$table .= '<td>' . $edit . $delete . '</td>';
						endif;
						$table .= '</tr>
						    ';
						$i++;
					}

					$prev    = '';

					$next = '
			        		<tr>
			        			<td>';
					if ($cur_page >= 2) :
						$next .= '<span data-page="' . $pre_page . '" class="pages btn btn-warning waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>';
					endif;
					$next .= '</td>
			        			<td>';
					if ($tot_page > $cur_page) :
						$next .= '<span data-page="' . $nxt_page . '" class="pages btn btn-success waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
					endif;
					$next .= '</td>
			        		</tr>
			        	';
				} else {
					$status     = 0;
					$message    = 'No Records';
					$table      = '';
					$next       = '';
					$prev       = '';
				}
			} else {
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
		} else if ($param1 == 'delete') {
			$id       = $this->input->post('id');
			$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');

			if (!empty($id)) {
				$data = array(
					'log_id'       => $log_id,
					'log_role'     => $log_role,
					'privilege_id' => $id,
					'method'       => '_deletePrivilege'
				);

				$data_save = avul_call(API_URL . 'master/api/privilege', $data);

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
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			}
		}
	}

	public function add_unit($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$formpage = $this->input->post('formpage');
		$log_id   = $this->session->userdata('id');
		$log_role = $this->session->userdata('user_role');

		if ($formpage == 'BTBM_X_P') {
			$error = FALSE;
			$unit_name = $this->input->post('unit_name');
			$method    = $this->input->post('method');

			$required = array('unit_name');
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
					if (userAccess('unit-add')) {
						$data = array(
							'log_id'     => $log_id,
							'log_role'   => $log_role,
							'name'       => $unit_name,
							'createdate' => date('Y-m-d H:i:s'),
							'method'     => '_addUnit',
						);

						$data_save = avul_call(API_URL . 'master/api/unit', $data);

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
				} else {
					if (userAccess('unit-edit')) {
						$unit_id = $this->input->post('unit_id');
						$pstatus = $this->input->post('pstatus');

						$data = array(
							'log_id'     => $log_id,
							'log_role'   => $log_role,
							'id'         => $unit_id,
							'name'       => $unit_name,
							'status'     => $pstatus,
							'method'     => '_updateUnit'
						);

						$data_save = avul_call(API_URL . 'master/api/unit', $data);

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
		} else {
			if ($param1 == 'Edit') {
				$unit_id = !empty($param2) ? $param2 : '';

				$where = array(
					'unit_id' => $unit_id,
					'method'  => '_detailUnit'
				);

				$data_list  = avul_call(API_URL . 'master/api/unit', $where);

				$page['dataval']    = $data_list['data'];
				$page['method']     = 'BTBM_X_U';
				$page['page_title'] = "Edit Unit";
			} else {
				$page['dataval']    = '';
				$page['method']     = 'BTBM_X_C';
				$page['page_title'] = "Add Unit";
			}
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Unit";
			$page['pre_title']    = "List Unit";
			$page['page_access']  = userAccess('unit-view');
			$page['pre_menu']     = "index.php/admin/masters/list_unit";
			$data['page_temp']    = $this->load->view('admin/master/unit/add_unit', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "add_unit";
			$this->bassthaya->load_admin_form_template($data);
		}
	}

	public function list_unit($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		if ($param1 == '') {
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Unit";
			$page['page_title']   = "List Unit";
			$page['pre_title']    = "Add Unit";
			$page['page_access']  = userAccess('unit-add');
			$page['pre_menu']     = "index.php/admin/masters/add_unit";
			$data['page_temp']    = $this->load->view('admin/master/unit/list_unit', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "list_unit";
			$this->bassthaya->load_admin_form_template($data);
		} else if ($param1 == 'data_list') {
			if (userAccess('unit-view')) {
				$limit    = $this->input->post('limitval');
				$page     = $this->input->post('page');
				$search   = $this->input->post('search');
				$cur_page = isset($page) ? $page : '1';
				$_offset  = ($cur_page - 1) * $limit;
				$nxt_page = $cur_page + 1;
				$pre_page = $cur_page - 1;

				$where = array(
					'offset'  => $_offset,
					'limit'   => $limit,
					'search'  => $search,
					'method'  => '_listUnitPaginate'
				);

				$data_list  = avul_call(API_URL . 'master/api/unit', $where);
				$data_value = !empty($data_list['data']) ? $data_list['data'] : '';

				if (!empty($data_value)) {

					$count    = count($data_value);
					$total    = isset($data_list['total_record']) ? $data_list['total_record'] : '';
					$tot_page = ceil($total / $limit);

					$status  = 1;
					$message = 'Success';
					$table   = '';

					$i = 1;
					foreach ($data_value as $key => $value) {
						$unit_id       = !empty($value['unit_id']) ? $value['unit_id'] : '';
						$unit_name     = !empty($value['unit_name']) ? $value['unit_name'] : '';
						$active_status = !empty($value['status']) ? $value['status'] : '';
						$createdate    = !empty($value['createdate']) ? $value['createdate'] : '';

						if ($active_status == '1') {
							$status_view = '<span class="badge badge-success">Active</span>';
						} else {
							$status_view = '<span class="badge badge-danger">In Active</span>';
						}

						$unit_value = $unit_id;

						if ($unit_id == 1 || $unit_id == 2) {
							$unit_value = '0';
						}

						$edit   = '';
						$delete = '';
						if (userAccess('unit-edit') == TRUE) {
							$edit = '<a href="' . BASE_URL . 'index.php/admin/masters/add_unit/Edit/' . $unit_value . '" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
						}
						if (userAccess('unit-delete') == TRUE) {
							$delete = '<a data-row="' . $i . '" data-id="' . $unit_value . '" data-value="admin" data-cntrl="masters" data-func="list_unit" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
						}

						$table .= '
						    	<tr class="row_' . $i . '">
	                                <td class="line_height">' . $i . '</td>
	                                <td class="line_height">' . $unit_name . '</td>
	                                <td class="line_height">' . $status_view . '</td>';
						if (userAccess('unit-edit') == TRUE || userAccess('unit-delete') == TRUE) :
							$table .= '<td>' . $edit . $delete . '</td>';
						endif;
						$table .= '</tr>
						    ';
						$i++;
					}

					$prev    = '';

					$next = '
			        		<tr>
			        			<td>';
					if ($cur_page >= 2) :
						$next .= '<span data-page="' . $pre_page . '" class="pages btn btn-warning waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>';
					endif;
					$next .= '</td>
			        			<td>';
					if ($tot_page > $cur_page) :
						$next .= '<span data-page="' . $nxt_page . '" class="pages btn btn-success waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
					endif;
					$next .= '</td>
			        		</tr>
			        	';
				} else {
					$status     = 0;
					$message    = 'No Records';
					$table      = '';
					$next       = '';
					$prev       = '';
				}
			} else {
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
		} else if ($param1 == 'delete') {
			$id       = $this->input->post('id');
			$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');

			if (!empty($id)) {
				$data = array(
					'log_id'   => $log_id,
					'log_role' => $log_role,
					'unit_id'  => $id,
					'method'   => '_deleteUnit'
				);

				$data_save = avul_call(API_URL . 'master/api/unit', $data);

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
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			}
		}
	}

	public function add_variations($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$formpage = $this->input->post('formpage');
		$log_id   = $this->session->userdata('id');
		$log_role = $this->session->userdata('user_role');

		if ($formpage == 'BTBM_X_P') {
			$error = FALSE;
			$variation_name = $this->input->post('variation_name');
			$method         = $this->input->post('method');

			$required = array('variation_name');
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
					if (userAccess('variations-add')) {
						$data = array(
							'log_id'     => $log_id,
							'log_role'   => $log_role,
							'name'       => ucfirst($variation_name),
							'createdate' => date('Y-m-d H:i:s'),
							'method'     => '_addVariation',
						);

						$data_save = avul_call(API_URL . 'master/api/variation', $data);

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
				} else {
					if (userAccess('variations-edit')) {
						$variation_id = $this->input->post('variation_id');
						$pstatus      = $this->input->post('pstatus');

						$data = array(
							'log_id'   => $log_id,
							'log_role' => $log_role,
							'id'       => $variation_id,
							'name'     => ucfirst($variation_name),
							'status'   => $pstatus,
							'method'   => '_updateVariation'
						);

						$data_save = avul_call(API_URL . 'master/api/variation', $data);

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
		} else {
			if ($param1 == 'Edit') {
				$variation_id = !empty($param2) ? $param2 : '';

				$where = array(
					'variation_id' => $variation_id,
					'method'       => '_detailVariation'
				);

				$data_list  = avul_call(API_URL . 'master/api/variation', $where);

				$page['dataval']    = $data_list['data'];
				$page['method']     = 'BTBM_X_U';
				$page['page_title'] = "Edit Variation";
			} else {
				$page['dataval']    = '';
				$page['method']     = 'BTBM_X_C';
				$page['page_title'] = "Add Variation";
			}
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Variation";
			$page['pre_title']    = "List Variation";
			$page['page_access']  = userAccess('variations-view');
			$page['pre_menu']     = "index.php/admin/masters/list_variations";
			$data['page_temp']    = $this->load->view('admin/master/variations/add_variations', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "add_variations";
			$this->bassthaya->load_admin_form_template($data);
		}
	}

	public function list_variations($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		if ($param1 == '') {
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Variations";
			$page['page_title']   = "List Variations";
			$page['pre_title']    = "Add Variations";
			$page['page_access']  = userAccess('variations-add');
			$page['pre_menu']     = "index.php/admin/masters/add_variations";
			$data['page_temp']    = $this->load->view('admin/master/variations/list_variations', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "list_variations";
			$this->bassthaya->load_admin_form_template($data);
		} else if ($param1 == 'data_list') {
			if (userAccess('variations-view')) {
				$limit    = $this->input->post('limitval');
				$page     = $this->input->post('page');
				$search   = $this->input->post('search');
				$cur_page = isset($page) ? $page : '1';
				$_offset  = ($cur_page - 1) * $limit;
				$nxt_page = $cur_page + 1;
				$pre_page = $cur_page - 1;

				$where = array(
					'offset'  => $_offset,
					'limit'   => $limit,
					'search'  => $search,
					'method'  => '_listVariationPaginate'
				);

				$data_list  = avul_call(API_URL . 'master/api/variation', $where);
				$data_value = !empty($data_list['data']) ? $data_list['data'] : '';

				if (!empty($data_value)) {

					$count    = count($data_value);
					$total    = isset($data_list['total_record']) ? $data_list['total_record'] : '';
					$tot_page = ceil($total / $limit);

					$status  = 1;
					$message = 'Success';
					$table   = '';

					$i = 1;
					foreach ($data_value as $key => $value) {
						$variation_id   = !empty($value['variation_id']) ? $value['variation_id'] : '';
						$variation_name = !empty($value['variation_name']) ? $value['variation_name'] : '';
						$active_status  = !empty($value['status']) ? $value['status'] : '';
						$createdate     = !empty($value['createdate']) ? $value['createdate'] : '';

						if ($active_status == '1') {
							$status_view = '<span class="badge badge-success">Active</span>';
						} else {
							$status_view = '<span class="badge badge-danger">In Active</span>';
						}

						$edit   = '';
						$delete = '';
						if (userAccess('variations-edit') == TRUE) {
							$edit = '<a href="' . BASE_URL . 'index.php/admin/masters/add_variations/Edit/' . $variation_id . '" class="button_clr btn btn-primary"><i class="fa fa-edit"></i> Edit </a>';
						}
						if (userAccess('variations-delete') == TRUE) {
							$delete = '<a data-row="' . $i . '" data-id="' . $variation_id . '" data-value="admin" data-cntrl="masters" data-func="list_variations" class="delete-btn button_clr btn btn-danger"><i class="fa fa-trash"></i> Delete </a>';
						}

						$table .= '
						    	<tr class="row_' . $i . '">
	                                <td class="line_height">' . $i . '</td>
	                                <td class="line_height">' . $variation_name . '</td>
	                                <td class="line_height">' . $status_view . '</td>';
						if (userAccess('variations-edit') == TRUE || userAccess('variations-delete') == TRUE) :
							$table .= '<td>' . $edit . $delete . '</td>';
						endif;
						$table .= '</tr>
						    ';
						$i++;
					}

					$prev    = '';

					$next = '
			        		<tr>
			        			<td>';
					if ($cur_page >= 2) :
						$next .= '<span data-page="' . $pre_page . '" class="pages btn btn-warning waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>';
					endif;
					$next .= '</td>
			        			<td>';
					if ($tot_page > $cur_page) :
						$next .= '<span data-page="' . $nxt_page . '" class="pages btn btn-success waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
					endif;
					$next .= '</td>
			        		</tr>
			        	';
				} else {
					$status     = 0;
					$message    = 'No Records';
					$table      = '';
					$next       = '';
					$prev       = '';
				}
			} else {
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
		} else if ($param1 == 'delete') {
			$id       = $this->input->post('id');
			$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');

			if (!empty($id)) {
				$data = array(
					'log_id'       => $log_id,
					'log_role'     => $log_role,
					'variation_id' => $id,
					'method'       => '_deleteVariation'
				);

				$data_save = avul_call(API_URL . 'master/api/variation', $data);

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
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			}
		}
	}

	public function add_message($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$formpage = $this->input->post('formpage');
		$log_id   = $this->session->userdata('id');
		$log_role = $this->session->userdata('user_role');

		if ($formpage == 'BTBM_X_P') {
			$error   = FALSE;
			$message = $this->input->post('message');
			$method  = $this->input->post('method');

			$required = array('message');
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
					if (userAccess('message-add')) {
						$data = array(
							'log_id'     => $log_id,
							'log_role'   => $log_role,
							'message'    => $message,
							'createdate' => date('Y-m-d H:i:s'),
							'method'     => '_addMessage',
						);

						$data_save = avul_call(API_URL . 'master/api/message', $data);

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
				} else {
					if (userAccess('message-edit')) {
						$message_id = $this->input->post('message_id');
						$pstatus    = $this->input->post('pstatus');

						$data = array(
							'log_id'   => $log_id,
							'log_role' => $log_role,
							'id'       => $message_id,
							'message'  => $message,
							'status'   => $pstatus,
							'method'   => '_updateMessage'
						);

						$data_save = avul_call(API_URL . 'master/api/message', $data);

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
		} else {
			if ($param1 == 'Edit') {
				$message_id = !empty($param2) ? $param2 : '';

				$where = array(
					'message_id' => $message_id,
					'method'     => '_detailMessage'
				);

				$data_list  = avul_call(API_URL . 'master/api/message', $where);

				$page['dataval']    = $data_list['data'];
				$page['method']     = 'BTBM_X_U';
				$page['page_title'] = "Edit Message";
			} else {
				$page['dataval']    = '';
				$page['method']     = 'BTBM_X_C';
				$page['page_title'] = "Add Message";
			}
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Message";
			$page['pre_title']    = "List Message";
			$page['page_access']  = userAccess('message-view');
			$page['pre_menu']     = "index.php/admin/masters/list_message";
			$data['page_temp']    = $this->load->view('admin/master/message/add_message', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "add_message";
			$this->bassthaya->load_admin_form_template($data);
		}
	}

	public function list_message($param1 = "", $param2 = "", $param3 = "")
	{
		if ($param1 == '') {
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Message";
			$page['page_title']   = "List Message";
			$page['pre_title']    = "Add Message";
			$page['page_access']  = userAccess('message-add');
			$page['pre_menu']     = "index.php/admin/masters/add_message";
			$data['page_temp']    = $this->load->view('admin/master/message/list_message', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "list_message";
			$this->bassthaya->load_admin_form_template($data);
		} else if ($param1 == 'data_list') {
			if (userAccess('message-view')) {
				$limit    = $this->input->post('limitval');
				$page     = $this->input->post('page');
				$search   = $this->input->post('search');
				$cur_page = isset($page) ? $page : '1';
				$_offset  = ($cur_page - 1) * $limit;
				$nxt_page = $cur_page + 1;
				$pre_page = $cur_page - 1;

				$where = array(
					'offset'  => $_offset,
					'limit'   => $limit,
					'search'  => $search,
					'method'  => '_listMessagePaginate'
				);

				$data_list  = avul_call(API_URL . 'master/api/message', $where);
				$data_value = !empty($data_list['data']) ? $data_list['data'] : '';

				if (!empty($data_value)) {

					$count    = count($data_value);
					$total    = isset($data_list['total_record']) ? $data_list['total_record'] : '';
					$tot_page = ceil($total / $limit);

					$status  = 1;
					$message = 'Success';
					$table   = '';

					$i = 1;
					foreach ($data_value as $key => $value) {
						$message_id    = !empty($value['message_id']) ? $value['message_id'] : '';
						$message       = !empty($value['message']) ? $value['message'] : '';
						$active_status = !empty($value['status']) ? $value['status'] : '';
						$createdate    = !empty($value['createdate']) ? $value['createdate'] : '';

						if ($active_status == '1') {
							$status_view = '<span class="badge badge-success">Active</span>';
						} else {
							$status_view = '<span class="badge badge-danger">In Active</span>';
						}

						$edit   = '';
						$delete = '';
						if (userAccess('message-edit') == TRUE) {
							$edit = '<a href="' . BASE_URL . 'index.php/admin/masters/add_message/Edit/' . $message_id . '" class="button_clr btn btn-primary"><i class="fa fa-edit"></i> Edit </a>';
						}
						if (userAccess('message-delete') == TRUE) {
							$delete = '<a data-row="' . $i . '" data-id="' . $message_id . '" data-value="admin" data-cntrl="masters" data-func="list_message" class="delete-btn button_clr btn btn-danger"><i class="fa fa-trash"></i> Delete </a>';
						}

						$table .= '
						    	<tr class="row_' . $i . '">
	                                <td class="line_height">' . $i . '</td>
	                                <td class="line_height">' . mb_strimwidth($message, 0, 75, '...') . '</td>
	                                <td class="line_height">' . $status_view . '</td>';
						if (userAccess('message-edit') == TRUE || userAccess('message-delete') == TRUE) :
							$table .= '<td>' . $edit . $delete . '</td>';
						endif;
						$table .= '</tr>
						    ';
						$i++;
					}

					$prev    = '';

					$next = '
			        		<tr>
			        			<td>';
					if ($cur_page >= 2) :
						$next .= '<span data-page="' . $pre_page . '" class="pages btn btn-warning waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>';
					endif;
					$next .= '</td>
			        			<td>';
					if ($tot_page > $cur_page) :
						$next .= '<span data-page="' . $nxt_page . '" class="pages btn btn-success waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
					endif;
					$next .= '</td>
			        		</tr>
			        	';
				} else {
					$status     = 0;
					$message    = 'No Records';
					$table      = '';
					$next       = '';
					$prev       = '';
				}
			} else {
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
		} else if ($param1 == 'delete') {
			$id       = $this->input->post('id');
			$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');

			if (!empty($id)) {
				$data = array(
					'log_id'     => $log_id,
					'log_role'   => $log_role,
					'message_id' => $id,
					'method'     => '_deleteMessage'
				);

				$data_save = avul_call(API_URL . 'master/api/message', $data);

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
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			}
		}
	}

	public function add_expense($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$formpage  = $this->input->post('formpage');
		$log_id    = $this->session->userdata('id');
		$log_role  = $this->session->userdata('user_role');


		if ($formpage == 'BTBM_X_P') {
			$error = FALSE;
			$expense_name = $this->input->post('expense_name');
			$method       = $this->input->post('method');

			$required = array('expense_name');
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
					if (userAccess('expense-add')) {
						$data = array(
							'log_id'   => $log_id,
							'log_role' => $log_role,
							'name'     => $expense_name,
							'method'   => '_addExpense',
						);

						$data_save = avul_call(API_URL . 'master/api/expense', $data);

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
				} else {
					if (userAccess('expense-edit')) {
						$expense_id = $this->input->post('expense_id');
						$pstatus    = $this->input->post('pstatus');

						$data = array(
							'log_id'   => $log_id,
							'log_role' => $log_role,
							'id'       => $expense_id,
							'name'     => $expense_name,
							'status'   => $pstatus,
							'method'   => '_updateExpense'
						);

						$data_save = avul_call(API_URL . 'master/api/expense', $data);

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
		} else {
			if ($param1 == 'Edit') {
				$expense_id = !empty($param2) ? $param2 : '';

				$where = array(
					'expense_id' => $expense_id,
					'method'     => '_detailExpense'
				);

				$data_list  = avul_call(API_URL . 'master/api/expense', $where);

				$page['dataval']    = $data_list['data'];
				$page['method']     = 'BTBM_X_U';
				$page['page_title'] = "Edit Expense";
			} else {
				$page['dataval']    = '';
				$page['method']     = 'BTBM_X_C';
				$page['page_title'] = "Add Expense";
			}
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Expense";
			$page['pre_title']    = "List Expense";
			$page['page_access']  = userAccess('expense-view');
			$page['pre_menu']     = "index.php/admin/masters/list_expense";
			$data['page_temp']    = $this->load->view('admin/master/expense/add_expense', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "add_expense";
			$this->bassthaya->load_admin_form_template($data);
		}
	}

	public function list_expense($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		if ($param1 == '') {
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Expense";
			$page['page_title']   = "List Expense";
			$page['pre_title']    = "Add Expense";
			$page['page_access']  = userAccess('expense-add');
			$page['pre_menu']     = "index.php/admin/masters/add_expense";
			$data['page_temp']    = $this->load->view('admin/master/expense/list_expense', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "list_expense";
			$this->bassthaya->load_admin_form_template($data);
		} else if ($param1 == 'data_list') {
			if (userAccess('expense-view')) {
				$limit    = $this->input->post('limitval');
				$page     = $this->input->post('page');
				$search   = $this->input->post('search');
				$cur_page = isset($page) ? $page : '1';
				$_offset  = ($cur_page - 1) * $limit;
				$nxt_page = $cur_page + 1;
				$pre_page = $cur_page - 1;

				$where = array(
					'offset'  => $_offset,
					'limit'   => $limit,
					'search'  => $search,
					'method'  => '_listExpensePaginate'
				);

				$data_list  = avul_call(API_URL . 'master/api/expense', $where);
				$data_value = !empty($data_list['data']) ? $data_list['data'] : '';

				if (!empty($data_value)) {

					$count    = count($data_value);
					$total    = isset($data_list['total_record']) ? $data_list['total_record'] : '';
					$tot_page = ceil($total / $limit);

					$status  = 1;
					$message = 'Success';
					$table   = '';

					$i = 1;
					foreach ($data_value as $key => $value) {
						$expense_id    = !empty($value['expense_id']) ? $value['expense_id'] : '';
						$expense_name  = !empty($value['expense_name']) ? $value['expense_name'] : '';
						$active_status = !empty($value['status']) ? $value['status'] : '';
						$createdate    = !empty($value['createdate']) ? $value['createdate'] : '';

						if ($active_status == '1') {
							$status_view = '<span class="badge badge-success">Active</span>';
						} else {
							$status_view = '<span class="badge badge-danger">In Active</span>';
						}

						$edit   = '';
						$delete = '';
						if (userAccess('expense-edit') == TRUE) {
							$edit = '<a href="' . BASE_URL . 'index.php/admin/masters/add_expense/Edit/' . $expense_id . '" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>';
						}
						if (userAccess('expense-delete') == TRUE) {
							$delete = '<a data-row="' . $i . '" data-id="' . $expense_id . '" data-value="admin" data-cntrl="masters" data-func="list_expense" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a>';
						}

						$table .= '
						    	<tr class="row_' . $i . '">
	                                <td class="line_height">' . $i . '</td>
	                                <td class="line_height">' . $expense_name . '</td>
	                                <td class="line_height">' . $status_view . '</td>';
						if (userAccess('expense-edit') == TRUE || userAccess('expense-delete') == TRUE) :
							$table .= '<td>' . $edit . $delete . '</td>';
						endif;
						$table .= '</tr>
						    ';
						$i++;
					}

					$prev    = '';

					$next = '
			        		<tr>
			        			<td>';
					if ($cur_page >= 2) :
						$next .= '<span data-page="' . $pre_page . '" class="pages btn btn-warning waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>';
					endif;
					$next .= '</td>
			        			<td>';
					if ($tot_page > $cur_page) :
						$next .= '<span data-page="' . $nxt_page . '" class="pages btn btn-success waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
					endif;
					$next .= '</td>
			        		</tr>
			        	';
				} else {
					$status     = 0;
					$message    = 'No Records';
					$table      = '';
					$next       = '';
					$prev       = '';
				}
			} else {
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
		} else if ($param1 == 'delete') {
			$id       = $this->input->post('id');
			$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');

			if (!empty($id)) {
				$data = array(
					'log_id'     => $log_id,
					'log_role'   => $log_role,
					'expense_id' => $id,
					'method'     => '_deleteExpense'
				);

				$data_save = avul_call(API_URL . 'master/api/expense', $data);

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
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			}
		}
	}

	// public function expense_entry($param1="", $param2="", $param3="")
	// {
	// 	if ($this->session->userdata('random_value') == '')
	// 	redirect(base_url() . 'index.php?login', 'refresh');

	// 	$formpage = $this->input->post('formpage');

	// 	if($formpage =='BTBM_X_P')
	// 	{
	// 		$employee_id  = $this->input->post('employee_id');
	// 		$expense_id   = $this->input->post('expense_id');
	// 		$expense_date = $this->input->post('expense_date');
	// 		$expense_type = $this->input->post('expense_type');
	// 		$expense_val  = $this->input->post('expense_val');
	// 		$expense_desc = $this->input->post('expense_desc');
	// 		$auto_id      = $this->input->post('auto_id');

	// 		if(count(array_filter($expense_id)) !== count($expense_id) || count(array_filter($expense_date)) !== count($expense_date) || count(array_filter($expense_type)) !== count($expense_type) || count(array_filter($expense_val)) !== count($expense_val))
	// 	    {
	// 	    	$response['status']  = 0;
	// 	        $response['message'] = "Please fill all required fields"; 
	// 	        $response['data']    = [];
	// 	        $response['error']   = []; 
	// 	        echo json_encode($response);
	// 	        return;
	// 	    }
	// 	    else
	// 	    {
	// 	    	if(userAccess('expense-entry-add'))
	// 	    	{
	// 	    		$expense_det   = [];
	// 		    	$expense_count = count($expense_id);

	// 		    	for($j = 0; $j < $expense_count; $j++)
	// 		    	{
	// 		    		$expense_det[] = array(
	// 	    				'employee_id'   => $employee_id[$j],
	// 	    				'expense_id'    => $expense_id[$j],
	// 	    				'expense_date'  => $expense_date[$j],
	// 	    				'expense_type'  => $expense_type[$j],
	// 	    				'expense_val'   => $expense_val[$j],
	// 	    				'expense_desc'  => $expense_desc[$j],
	// 	    			);
	// 		    	}

	// 		    	$expense_value = json_encode($expense_det);

	// 		    	$data = array(
	// 			    	'expense_value'    => $expense_value,
	// 			    	'active_financial' => $this->session->userdata('active_year'),
	// 			    	'method'           => '_addExpenseEntry',
	// 			    );

	// 			    $data_save = avul_call(API_URL.'master/api/expense',$data);

	// 			    if($data_save['status'] == 1)
	// 			    {
	// 			    	$response['status']  = 1;
	// 			        $response['message'] = $data_save['message']; 
	// 			        $response['data']    = [];
	// 			        echo json_encode($response);
	// 			        return; 
	// 			    }
	// 			    else
	// 			    {
	// 			    	$response['status']  = 0;
	// 			        $response['message'] = $data_save['message']; 
	// 			        $response['data']    = [];
	// 			        echo json_encode($response);
	// 			        return; 	
	// 			    }
	// 	    	}
	// 	    	else
	//     		{
	//     			$response['status']  = 0;
	// 		        $response['message'] = 'Access denied'; 
	// 		        $response['data']    = [];
	// 		        echo json_encode($response);
	// 		        return; 
	//     		}
	// 	    }
	// 	}

	// 	if($param1 =='getExpense_row')
	// 	{
	// 		$rowCount    = $this->input->post('rowCount');
	// 		$newCount    = $rowCount + 1;

	// 		$where_1 = array(
	//     		'log_type' => '2',
	//     		'method'   => '_typeWiseEmployee'
	//     	);

	//     	$emp_value = avul_call(API_URL.'employee/api/employee',$where_1);
	//     	$emp_list  = !empty($emp_value['data'])?$emp_value['data']:'';

	// 		$where_2 = array(
	//     		'method'   => '_listExpense'
	//     	);

	//     	$exp_value = avul_call(API_URL.'master/api/expense',$where_2);
	//     	$exp_list  = !empty($exp_value['data'])?$exp_value['data']:'';

	//     	$option = '
	// 			<tr class="row_'.$newCount.'">
	// 				<script src="'.BASE_URL.'app-assets/js/select2.full.js"></script>
	// 				<script>
	// 					if($(".js-select2-multi").length)
	// 				    {
	// 				        $(".js-select2-multi").select2({
	// 				            placeholder: "Select Value",
	// 				        });
	// 				    }

	// 				    $(".dateselecter").datepicker({
	// 				        maxDate: new Date(),
	// 				        format: "dd-mm-yyyy",
	// 				        ignoreReadonly: true,
	// 				        autoclose: true
	// 				    });

	// 				</script>

	// 				<td data-te="'.$newCount.'" class="p-l-0 expense_list">
	//                     <select data-te="'.$newCount.'" name="employee_id[]" id="employee_id'.$newCount.'" class="form-control employee_id'.$newCount.' employee_id js-select2-multi">
	//                         <option value="">Select Product Name</option>';
	//                         if (!empty($emp_list))
	//                         {
	//                             foreach ($emp_list as $key => $value_1)
	//                             {
	//                                 $employee_id = !empty($value_1['employee_id'])?$value_1['employee_id']:'';
	//                                 $username    = !empty($value_1['username'])?$value_1['username']:'';
	//                                 $mobile      = !empty($value_1['mobile'])?$value_1['mobile']:'';

	//                                 $option .= '<option value="'.$employee_id.'">'.$username.' ('.$mobile.')'.'</option>';
	//                             }
	//                         }
	//                     $option .=' </select> 
	//                 </td>
	// 				<td data-te="'.$newCount.'" class="p-l-0 expense_list">
	//                     <select data-te="'.$newCount.'" name="expense_id[]" id="expense_id'.$newCount.'" class="form-control expense_id'.$newCount.' expense_id js-select2-multi">
	//                         <option value="">Select Product Name</option>';
	//                         if(!empty($exp_list))
	//                         {
	//                         	foreach ($exp_list as $key => $value_2)
	//                             {
	//                                 $exp_id   = !empty($value_2['expense_id'])?$value_2['expense_id']:'';
	//                                 $exp_name = !empty($value_2['expense_name'])?$value_2['expense_name']:'';

	//                                 $option .='<option value="'.$exp_id.'">'.$exp_name.'</option>';
	//                             }
	//                         }
	//                     $option .=' </select> 
	//                 </td>
	//                 <td class="p-l-0">
	//                     <input type="text" data-te="'.$newCount.'" name="expense_date[]" id="expense_date'.$newCount.'" class="form-control expense_date1 expense_date dateselecter" placeholder="Expense Date">
	//                 </td>
	//                 <td data-te="'.$newCount.'" class="p-l-0 expense_list">
	//                     <select data-te="'.$newCount.'" name="expense_type[]" id="expense_type'.$newCount.'" class="form-control expense_type'.$newCount.' expense_type js-select2-multi">
	//                         <option value="">Select Product Name</option>
	//                         <option value="1">Cash</option>
	//                         <option value="2">Bank</option>
	//                     </select> 
	//                 </td>
	//                 <td class="p-l-0">
	//                     <input type="text" data-te="'.$newCount.'" name="expense_val[]" id="expense_val'.$newCount.'" class="form-control expense_val1 expense_val int_value" placeholder="Price">

	//                     <input type="hidden" data-te="'.$newCount.'" name="auto_id[]" id="auto_id'.$newCount.'" class="form-control auto_id1 auto_id" value="'.$newCount.'">
	//                 </td>
	//                 <td class="buttonlist p-l-0">
	//                     <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6 remove_item"><span class="ft-minus-square"></span></button>
	//                 </td>
	// 			</tr>
	// 			<tr class="row_'.$newCount.'">
	//                 <td class="p-l-0" colspan="6">
	//                     <textarea id="expense_desc" class="form-control expense_desc" placeholder="Expense Description" name="expense_desc[]" rows="2"></textarea>
	//                 </td>
	//             </tr>
	// 		';

	// 		$response['status']  = 1;
	//         $response['message'] = 'success'; 
	//         $response['data']    = $option;
	//         $response['count']   = $newCount;
	//         echo json_encode($response);
	//         return; 
	// 	}

	// 	else
	// 	{
	// 		$where_1 = array(
	//     		'log_type' => '2',
	//     		'method'   => '_typeWiseEmployee'
	//     	);

	//     	$emp_value = avul_call(API_URL.'employee/api/employee',$where_1);
	//     	$emp_list  = !empty($emp_value['data'])?$emp_value['data']:'';

	//     	$where_2 = array(
	//     		'method'   => '_listExpense'
	//     	);

	//     	$exp_value = avul_call(API_URL.'master/api/expense',$where_2);
	//     	$exp_list  = !empty($exp_value['data'])?$exp_value['data']:'';

	//     	$page['emp_list']     = $emp_list;
	//     	$page['exp_list']     = $exp_list;
	//     	$page['main_heading'] = "Master";
	// 		$page['sub_heading']  = "Expense";
	// 		$page['page_title']   = "Expense Entry";
	// 		$page['pre_title']    = "Expense Entry";
	// 		$page['pre_menu']     = "#";
	// 		$data['page_temp']    = $this->load->view('admin/master/expense/expense_entry',$page,TRUE);
	// 		$data['view_file']    = "Page_Template";
	// 		$data['currentmenu']  = "expense_entry";
	// 		$this->bassthaya->load_admin_form_template($data);

	// 	}
	// }

	public function expense_entry($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$formpage = $this->input->post('formpage');

		if ($formpage == 'BTBM_X_P') {
			$employee_id  = $this->input->post('employee_id');
			$expense_id   = $this->input->post('expense_id');
			$expense_date = $this->input->post('expense_date');
			$expense_type = $this->input->post('expense_type');
			$expense_val  = $this->input->post('expense_val');
			$expense_desc = $this->input->post('expense_desc');
			$auto_id      = $this->input->post('auto_id');

			if (count(array_filter($expense_id)) !== count($expense_id) || count(array_filter($expense_date)) !== count($expense_date) || count(array_filter($expense_type)) !== count($expense_type) || count(array_filter($expense_val)) !== count($expense_val)) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				if (userAccess('expense-entry-add')) {
					$expense_det   = [];
					$expense_count = count($expense_id);

					for ($j = 0; $j < $expense_count; $j++) {
						$expense_det[] = array(
							'employee_id'   => $employee_id[$j],
							'expense_id'    => $expense_id[$j],
							'expense_date'  => $expense_date[$j],
							'expense_type'  => $expense_type[$j],
							'expense_val'   => $expense_val[$j],
							'expense_desc'  => $expense_desc[$j],
						);
					}

					$expense_value = json_encode($expense_det);

					$data = array(
						'expense_value'    => $expense_value,
						'active_financial' => $this->session->userdata('active_year'),
						'method'           => '_addExpenseEntry',
					);

					$data_save = avul_call(API_URL . 'master/api/expense', $data);

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

		if ($param1 == 'getExpense_row') {
			$rowCount    = $this->input->post('rowCount');
			$newCount    = $rowCount + 1;

			$where_1 = array(
				'log_type' => '2',
				'method'   => '_typeWiseEmployee'
			);

			$emp_value = avul_call(API_URL . 'employee/api/employee', $where_1);
			$emp_list  = !empty($emp_value['data']) ? $emp_value['data'] : '';

			$where_2 = array(
				'method'   => '_listExpense'
			);

			$exp_value = avul_call(API_URL . 'master/api/expense', $where_2);
			$exp_list  = !empty($exp_value['data']) ? $exp_value['data'] : '';

			$option = '
					<tr class="row_' . $newCount . '">
						<script src="' . BASE_URL . 'app-assets/js/select2.full.js"></script>
						<script>
							if($(".js-select2-multi").length)
						    {
						        $(".js-select2-multi").select2({
						            placeholder: "Select Value",
						        });
						    }

						    $(".dateselecter").datepicker({
						        maxDate: new Date(),
						        format: "dd-mm-yyyy",
						        ignoreReadonly: true,
						        autoclose: true
						    });

						</script>

						<td data-te="' . $newCount . '" class="p-l-0 expense_list">
                            <select data-te="' . $newCount . '" name="employee_id[]" id="employee_id' . $newCount . '" class="form-control employee_id' . $newCount . ' employee_id js-select2-multi">
                                <option value="">Select Product Name</option>';
			if (!empty($emp_list)) {
				foreach ($emp_list as $key => $value_1) {
					$employee_id = !empty($value_1['employee_id']) ? $value_1['employee_id'] : '';
					$username    = !empty($value_1['username']) ? $value_1['username'] : '';
					$mobile      = !empty($value_1['mobile']) ? $value_1['mobile'] : '';

					$option .= '<option value="' . $employee_id . '">' . $username . ' (' . $mobile . ')' . '</option>';
				}
			}
			$option .= ' </select> 
                        </td>
						<td data-te="' . $newCount . '" class="p-l-0 expense_list">
                            <select data-te="' . $newCount . '" name="expense_id[]" id="expense_id' . $newCount . '" class="form-control expense_id' . $newCount . ' expense_id js-select2-multi">
                                <option value="">Select Product Name</option>';
			if (!empty($exp_list)) {
				foreach ($exp_list as $key => $value_2) {
					$exp_id   = !empty($value_2['expense_id']) ? $value_2['expense_id'] : '';
					$exp_name = !empty($value_2['expense_name']) ? $value_2['expense_name'] : '';

					$option .= '<option value="' . $exp_id . '">' . $exp_name . '</option>';
				}
			}
			$option .= ' </select> 
                        </td>
                        <td class="p-l-0">
                            <input type="text" data-te="' . $newCount . '" name="expense_date[]" id="expense_date' . $newCount . '" class="form-control expense_date1 expense_date dateselecter" placeholder="Expense Date">
                        </td>
                        <td data-te="' . $newCount . '" class="p-l-0 expense_list">
                            <select data-te="' . $newCount . '" name="expense_type[]" id="expense_type' . $newCount . '" class="form-control expense_type' . $newCount . ' expense_type js-select2-multi">
                                <option value="">Select Product Name</option>
                                <option value="1">Cash</option>
                                <option value="2">Bank</option>
                            </select> 
                        </td>
                        <td class="p-l-0">
                            <input type="text" data-te="' . $newCount . '" name="expense_val[]" id="expense_val' . $newCount . '" class="form-control expense_val1 expense_val int_value" placeholder="Price">

                            <input type="hidden" data-te="' . $newCount . '" name="auto_id[]" id="auto_id' . $newCount . '" class="form-control auto_id1 auto_id" value="' . $newCount . '">
                        </td>
                        <td class="buttonlist p-l-0">
                            <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6 remove_item"><span class="ft-minus-square"></span></button>
                        </td>
					</tr>
					<tr class="row_' . $newCount . '">
                        <td class="p-l-0" colspan="6">
                            <textarea id="expense_desc" class="form-control expense_desc" placeholder="Expense Description" name="expense_desc[]" rows="2"></textarea>
                        </td>
                    </tr>
				';

			$response['status']  = 1;
			$response['message'] = 'success';
			$response['data']    = $option;
			$response['count']   = $newCount;
			echo json_encode($response);
			return;
		} else {
			$where_1 = array(
				'log_type' => '2',
				'method'   => '_typeWiseEmployee'
			);

			$emp_value = avul_call(API_URL . 'employee/api/employee', $where_1);
			$emp_list  = !empty($emp_value['data']) ? $emp_value['data'] : '';

			$where_2 = array(
				'method'   => '_listExpense'
			);

			$exp_value = avul_call(API_URL . 'master/api/expense', $where_2);
			$exp_list  = !empty($exp_value['data']) ? $exp_value['data'] : '';

			$page['emp_list']     = $emp_list;
			$page['exp_list']     = $exp_list;
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Expense";
			$page['page_title']   = "Expense Entry";
			$page['pre_title']    = "Expense Entry";
			$page['pre_menu']     = "#";
			$data['page_temp']    = $this->load->view('admin/master/expense/expense_entry', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "expense_entry";
			$this->bassthaya->load_admin_form_template($data);
		}
	}

	public function expense_list($param1 = "", $param2 = "", $param3 = "")
	{

		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		if ($param1 == '') {

			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Expense";
			$page['page_title']   = "List Expense";
			$page['pre_title']    = "Add Expense";
			$page['page_access']  = userAccess('expense-entry-view');
			$page['pre_menu']     = "index.php/admin/masters/expense_entry";
			$data['page_temp']    = $this->load->view('admin/master/expense/expense_list', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "expense_list";
			$this->bassthaya->load_admin_form_template($data);
		} else if ($param1 == 'data_list') {

			if (userAccess('expense-entry-view')) {
				$limit    = $this->input->post('limitval');
				$page     = $this->input->post('page');
				$search   = $this->input->post('search');
				$cur_page = isset($page) ? $page : '1';
				$_offset  = ($cur_page - 1) * $limit;
				$nxt_page = $cur_page + 1;
				$pre_page = $cur_page - 1;

				$where = array(
					'offset'  => $_offset,
					'limit'   => $limit,
					'search'  => $search,
					'method'  => '_Expenselist'
				);

				$data_list  = avul_call(API_URL . 'master/api/expense', $where);

				$data_value = !empty($data_list['data']) ? $data_list['data'] : '';


				if (!empty($data_value)) {

					$count    = count($data_value);

					$total    = isset($data_list['total_record']) ? $data_list['total_record'] : '';
					$tot_page = ceil($total / $limit);



					$status  = 1;
					$message = 'Success';
					$table   = '';

					$i = 1;
					foreach ($data_value as $key => $value) {
						$id = $_offset + $i;
						$expense_no    = !empty($value['expense_no']) ? $value['expense_no'] : '';
						$employee_id   = !empty($value['employee_id']) ? $value['employee_id'] : '';
						$employee_name = !empty($value['employee_name']) ? $value['employee_name'] : '';
						$expense_id    = !empty($value['expense_id']) ? $value['expense_id'] : '';
						$expense_date  = !empty($value['expense_date']) ? $value['expense_date'] : '';
						$expense_val   = !empty($value['expense_val']) ? $value['expense_val'] : '';
						$expense_type  = !empty($value['expense_type']) ? $value['expense_type'] : '';
						$active_status = !empty($value['status']) ? $value['status'] : '';
						$createdate    = !empty($value['createdate']) ? $value['createdate'] : '';

						if ($expense_type == '1') {
							$expense_type_name = 'Cash';
						} else if ($expense_type == '2') {
							$expense_type_name = 'Bank';
						}

						if ($active_status == '1') {
							$status_view = '<a data-row="' . $i . '" data-id="' . $expense_no . '" data-status="' . $active_status . '" data-value="admin" data-cntrl="Masters" data-func="expense_list" class="list_' . $i . ' enable_btn_' . $i . ' status-btn button_clr btn btn-success"><i class="ft-edit"></i> Enable </a>';
						} else {
							$status_view = '<a data-row="' . $i . '" data-id="' . $expense_no . '" data-status="' . $active_status . '" data-value="admin" data-cntrl="Masters" data-func="expense_list" class="list_' . $i . ' disable_btn_' . $i . ' status-btn button_clr btn btn-danger"><i class="ft-edit"></i> Disable </a>';
						}

						$table .= '
						    	<tr class="row_' . $i . '">
	                                <td class="line_height">' . $id . '</td>
	                                <td class="line_height">' . $expense_no . '</td>
	                                <td class="line_height">' . $employee_name . '</td>
	                                <td class="line_height">' . $expense_id . '</td>
	                                <td class="line_height">' . date('d-m-Y', strtotime($expense_date)) . '</td>
	                                <td class="line_height">' . $expense_val . '</td>
	                                <td class="line_height">' . $expense_type_name . '</td>
	                                <td class="line_height">' . $status_view . '</td>';
						$table .= '</tr>
						    ';
						$i++;
					}

					$prev    = '';

					$next = '
			        		<tr>
			        			<td>';
					if ($cur_page >= 2) :
						$next .= '<span data-page="' . $pre_page . '" class="pages btn btn-warning waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>';
					endif;
					$next .= '</td>
			        			<td>';
					if ($tot_page > $cur_page) :
						$next .= '<span data-page="' . $nxt_page . '" class="pages btn btn-success waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
					endif;
					$next .= '</td>
			        		</tr>
			        	';
				} else {
					$status     = 0;
					$message    = 'No Records';
					$table      = '';
					$next       = '';
					$prev       = '';
				}
			} else {
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
		} else if ($param1 == 'status_update') {
			if (userAccess('expense-entry-edit')) {
				$id     = $this->input->post('id');
				$status = $this->input->post('status');

				if (!empty($id)) {
					if ($status == 1) {
						$upt_val = array(
							'expense_no'    => $id,
							'status'        => '2',
							'method'        => '_ExpenseStatusUpdate',
						);
					} else {
						$upt_val = array(
							'expense_no'    => $id,
							'status'        => '1',
							'method'        => '_ExpenseStatusUpdate',
						);
					}

					$data_save = avul_call(API_URL . 'master/api/expense', $upt_val);

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
					$response['message'] = "Please fill all required fields";
					$response['data']    = [];
					$response['error']   = [];
					echo json_encode($response);
					return;
				}
			} else {
				$status  = 0;
				$message = 'Access denied';

				$response['status']     = $status;
				$response['message']    = $message;
				echo json_encode($response);
				return;
			}
		}
	}

	public function add_outlet_category($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$formpage  = $this->input->post('formpage');
		$log_id    = $this->session->userdata('id');
		$log_role  = $this->session->userdata('user_role');


		if ($formpage == 'BTBM_X_P') {
			$error = FALSE;
			$category_name = $this->input->post('category_name');
			$max_time      = $this->input->post('max_time');
			$method        = $this->input->post('method');

			$required = array('category_name', 'max_time');
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
					if (userAccess('outlet-category-add')) {
						$data = array(
							'log_id'   => $log_id,
							'log_role' => $log_role,
							'name'     => $category_name,
							'max_time' => $max_time,
							'method'   => '_addOutletCategoty',
						);

						$data_save = avul_call(API_URL . 'master/api/outlet_category', $data);

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
				} else {
					if (userAccess('outlet-category-edit')) {
						$category_id = $this->input->post('category_id');
						$pstatus     = $this->input->post('pstatus');

						$data = array(
							'log_id'   => $log_id,
							'log_role' => $log_role,
							'id'       => $category_id,
							'name'     => $category_name,
							'max_time' => $max_time,
							'status'   => $pstatus,
							'method'   => '_updateOutletCategoty'
						);

						$data_save = avul_call(API_URL . 'master/api/outlet_category', $data);

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
		} else {
			if ($param1 == 'Edit') {
				$category_id = !empty($param2) ? $param2 : '';

				$where = array(
					'category_id' => $category_id,
					'method'     => '_detailOutletCategoty'
				);

				$data_list  = avul_call(API_URL . 'master/api/outlet_category', $where);

				$page['dataval']    = $data_list['data'];
				$page['method']     = 'BTBM_X_U';
				$page['page_title'] = "Edit Outlet Category";
			} else {
				$page['dataval']    = '';
				$page['method']     = 'BTBM_X_C';
				$page['page_title'] = "Add Outlet Category";
			}
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Outlet Category";
			$page['pre_title']    = "List Outlet Category";
			$page['page_access']  = userAccess('outlet-category-view');
			$page['pre_menu']     = "index.php/admin/masters/list_outlet_category";
			$data['page_temp']    = $this->load->view('admin/master/outlet_category/add_outlet_category', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "add_outlet_category";
			$this->bassthaya->load_admin_form_template($data);
		}
	}

	public function list_outlet_category($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		if ($param1 == '') {
			$page['main_heading'] = "Master";
			$page['sub_heading']  = "Outlet Category";
			$page['page_title']   = "List Outlet Category";
			$page['pre_title']    = "Add Outlet Category";
			$page['page_access']  = userAccess('outlet-category-add');
			$page['pre_menu']     = "index.php/admin/masters/add_outlet_category";
			$data['page_temp']    = $this->load->view('admin/master/outlet_category/list_outlet_category', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "list_outlet_category";
			$this->bassthaya->load_admin_form_template($data);
		} else if ($param1 == 'data_list') {
			if (userAccess('outlet-category-view')) {
				$limit    = $this->input->post('limitval');
				$page     = $this->input->post('page');
				$search   = $this->input->post('search');
				$cur_page = isset($page) ? $page : '1';
				$_offset  = ($cur_page - 1) * $limit;
				$nxt_page = $cur_page + 1;
				$pre_page = $cur_page - 1;

				$where = array(
					'offset'  => $_offset,
					'limit'   => $limit,
					'search'  => $search,
					'method'  => '_listOutletCategotyPaginate'
				);

				$data_list  = avul_call(API_URL . 'master/api/outlet_category', $where);
				$data_value = !empty($data_list['data']) ? $data_list['data'] : '';

				if (!empty($data_value)) {

					$count    = count($data_value);
					$total    = isset($data_list['total_record']) ? $data_list['total_record'] : '';
					$tot_page = ceil($total / $limit);

					$status  = 1;
					$message = 'Success';
					$table   = '';

					$i = 1;
					foreach ($data_value as $key => $value) {
						$category_id   = !empty($value['category_id']) ? $value['category_id'] : '';
						$category_name = !empty($value['category_name']) ? $value['category_name'] : '';
						$max_time      = !empty($value['max_time']) ? $value['max_time'] : '';
						$active_status = !empty($value['status']) ? $value['status'] : '';
						$createdate    = !empty($value['createdate']) ? $value['createdate'] : '';
						$minute_name   = ($value['max_time'] == 1) ? ' minute' : ' minutes';

						if ($active_status == '1') {
							$status_view = '<span class="badge badge-success">Active</span>';
						} else {
							$status_view = '<span class="badge badge-danger">In Active</span>';
						}

						$edit   = '';
						$delete = '';
						if (userAccess('outlet-category-edit') == TRUE) {
							$edit = '<a href="' . BASE_URL . 'index.php/admin/masters/add_outlet_category/Edit/' . $category_id . '" class="button_clr btn btn-primary"><i class="fa fa-edit"></i> Edit </a>';
						}
						if (userAccess('outlet-category-delete') == TRUE) {
							$delete = '<a data-row="' . $i . '" data-id="' . $category_id . '" data-value="admin" data-cntrl="masters" data-func="list_outlet_category" class="delete-btn button_clr btn btn-danger"><i class="fa fa-trash"></i> Delete </a>';
						}

						$table .= '
						    	<tr class="row_' . $i . '">
	                                <td class="line_height">' . $i . '</td>
	                                <td class="line_height">' . $category_name . '</td>
	                                <td class="line_height">' . $max_time . ' ' . $minute_name . '</td>
	                                <td class="line_height">' . $status_view . '</td>';
						if (userAccess('outlet-category-edit') == TRUE || userAccess('outlet-category-delete') == TRUE) :
							$table .= '<td>' . $edit . $delete . '</td>';
						endif;
						$table .= '</tr>
						    ';
						$i++;
					}

					$prev    = '';

					$next = '
			        		<tr>
			        			<td>';
					if ($cur_page >= 2) :
						$next .= '<span data-page="' . $pre_page . '" class="pages btn btn-warning waves-effect waves-light"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>';
					endif;
					$next .= '</td>
			        			<td>';
					if ($tot_page > $cur_page) :
						$next .= '<span data-page="' . $nxt_page . '" class="pages btn btn-success waves-effect waves-light">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
					endif;
					$next .= '</td>
			        		</tr>
			        	';
				} else {
					$status     = 0;
					$message    = 'No Records';
					$table      = '';
					$next       = '';
					$prev       = '';
				}
			} else {
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
		} else if ($param1 == 'delete') {
			$id       = $this->input->post('id');
			$log_id   = $this->session->userdata('id');
			$log_role = $this->session->userdata('user_role');

			if (!empty($id)) {
				$data = array(
					'log_id'       => $log_id,
					'log_role'     => $log_role,
					'category_id'  => $id,
					'method'       => '_deleteOutletCategoty'
				);

				$data_save = avul_call(API_URL . 'master/api/outlet_category', $data);

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
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			}
		}
	}
}

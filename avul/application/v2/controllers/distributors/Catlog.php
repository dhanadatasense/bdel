<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Catlog extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function list_product($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Product";
				$page['sub_heading']  = "Product";
				$page['page_title']   = "List Product";
				$page['pre_title']    = "Add Product";
				$page['pre_menu']     = "index.php/distributors/catlog/add_product";
				$data['page_temp']    = $this->load->view('distributors/catlog/product/list_product',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_product";
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
            		'offset'         => $_offset,
            		'limit'          => $limit,
            		'search'         => $search,
            		'distributor_id' => $this->session->userdata('id'),
            		'method'         => '_distributorAssignProductPaginate',
            	);

            	$data_list  = avul_call(API_URL.'assignproduct/api/list_assign_product',$where);
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

	            		$assproduct_id  = !empty($value['assproduct_id'])?$value['assproduct_id']:'';
			            $assign_id      = !empty($value['assign_id'])?$value['assign_id']:'';
			            $distributor_id = !empty($value['distributor_id'])?$value['distributor_id']:'';
			            $category_id    = !empty($value['category_id'])?$value['category_id']:'';
			            $product_id     = !empty($value['product_id'])?$value['product_id']:'';
			            $type_id        = !empty($value['type_id'])?$value['type_id']:'';
			            $description    = !empty($value['description'])?$value['description']:'';
			            $hsn_code       = !empty($value['hsn_code'])?$value['hsn_code']:'';
			            $gst_val        = !empty($value['gst_val'])?$value['gst_val']:'';
			            $stock          = !empty($value['stock'])?$value['stock']:'0';
			            $active_status  = !empty($value['status'])?$value['status']:'';

					    if($active_status == '1')
		                {
		                	$status_view = '<span class="badge badge-success">Active</span>';
		                }
		                else
		                {
		                	$status_view = '<span class="badge badge-danger">In Active</span>';
		                }

					    $table .= '
					    	<tr>
                                <td class="line_height">'.$i.'</td>
                                <td class="line_height">'.mb_strimwidth($description, 0, 40, '...').'</td>
                                <td class="line_height">'.$hsn_code.'</td>
                                <td class="line_height">'.$gst_val.'</td>
                                <td class="line_height">'.$stock.'</td>
                                <td class="line_height">'.$status_view.'</td>
                            </tr>
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
		}
	}
?>
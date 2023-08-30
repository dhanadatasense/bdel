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

		public function add_product($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
				
			$agents_id = $this->session->userdata('id');

			$formpage = $this->input->post('formpage');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$product_name = $this->input->post('product_name');
				$vendor_id    = $this->input->post('vendor_id');
				$category_id  = $this->input->post('category_id');
				$unit         = $this->input->post('unit');
				$hsn_code     = $this->input->post('hsn_code');
				$price        = $this->input->post('price');
				$dis_price    = $this->input->post('dis_price');
				$gst          = $this->input->post('gst');
				$stock        = $this->input->post('stock');
				$vend_stock   = $this->input->post('vend_stock');
				$method       = $this->input->post('method');

				$description  = $this->input->post('description');
				$pro_type     = $this->input->post('pro_type');
				$pro_unit     = $this->input->post('pro_unit');
				$mrp_price    = $this->input->post('mrp_price');
				$pro_price    = $this->input->post('pro_price');
				$ven_price    = $this->input->post('ven_price');
				$dis_price    = $this->input->post('dis_price');
				$pro_stock    = $this->input->post('pro_stock');
				$type_stock   = $this->input->post('type_stock');
				$type_id      = $this->input->post('type_id');

				$required = array('product_name', 'vendor_id', 'category_id', 'unit', 'hsn_code', 'gst');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if(count(array_filter($pro_type))!==count($pro_type) || $error == TRUE)
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
			    	if($method == 'BTBM_X_U')
			    	{
			    		$product_id   = $this->input->post('product_id');
			    		$product_code = $this->input->post('product_code');

			    		$product_type  = [];
			    		$product_count = count($pro_type);

			    		for($j = 0; $j < $product_count; $j++)
			    		{
			    			$product_type[] = array(
			    				'type_id'     => $type_id[$j],
			    				'description' => $description[$j],
			    				'pro_type'    => $pro_type[$j],
			    				'pro_unit'    => $pro_unit[$j],
			    				'mrp_price'   => $mrp_price[$j],
			    				'pro_price'   => $pro_price[$j],
			    				'ven_price'   => $ven_price[$j],
			    				'dis_price'   => $dis_price[$j],
			    				'pro_stock'   => $pro_stock[$j],
			    				'type_stock'  => $type_stock[$j],
			    			);
			    		}

			    		$product_value = json_encode($product_type);

			    		$data = array(
			    			'id'             => $product_id,
			    			'product_code'   => $product_code,
					    	'name'           => ucfirst($product_name),
					    	'vendor_id'      => $vendor_id,
					    	'category_id'    => $category_id,
					    	'unit'           => $unit,
					    	'price'          => $price,
					    	'hsn_code'       => $hsn_code,
					    	'gst'            => $gst,
					    	'stock'          => $stock,
					    	'vend_stock'     => $vend_stock,
					    	'item_type'      => '1',
					    	'salesagents_id' => '0',
					    	'status'         => '1',
					    	'product_type'   => $product_value,
					    	'method'         => '_updateProduct'
					    );

					    $data_save = avul_call(API_URL.'catlog/api/product',$data);

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

			else
			{
				if($param1 =='Edit')
				{
					$product_id = !empty($param2)?$param2:'';

					$where_1 = array(
	            		'product_id' => $product_id,
	            		'method'     => '_detailProduct'
	            	);

	            	$data_list  = avul_call(API_URL.'catlog/api/product',$where_1);

	            	$where_2 = array(
	            		'product_id' => $product_id,
	            		'method'     => '_listProductType'
	            	);

	            	$type_list  = avul_call(API_URL.'catlog/api/productType',$where_2);

					$page['dataval']      = $data_list['data'];
					$page['type_val']     = $type_list['data'];
					$page['method']       = 'BTBM_X_U';
					$page['page_title']   = "Edit Product";
				}
				else
				{
					$page['dataval']      = '';
					$page['method']       = 'BTBM_X_C';
					$page['page_title']   = "Add Product";
				}

				$where_1 = array(
	        		'method'     => '_listCategory',
	        	);

	        	$category_list = avul_call(API_URL.'catlog/api/category',$where_1);

	        	$where_2 = array(
	        		'method' => '_listUnit'
	        	);

	        	$unit_list  = avul_call(API_URL.'master/api/unit',$where_2);

	        	$where_3 = array(
	        		'method' => '_listVariation'
	        	);

	        	$variation_list  = avul_call(API_URL.'master/api/variation',$where_3);

	        	$where_4 = array(
	        		'method'    => '_detailVendors',
	        		'vendor_id' => $agents_id,
	        	);

	        	$vendor_list  = avul_call(API_URL.'vendors/api/vendors',$where_4);

	        	$page['category_val']  = $category_list['data'];
				$page['unit_val']      = $unit_list['data'];
				$page['variation_val'] = $variation_list['data'];
				$page['vendor_val']    = $vendor_list['data'];
				$page['main_heading']  = "Catlog";
				$page['sub_heading']   = "Product";
				$page['pre_title']     = "List Product";
				$page['pre_menu']      = "index.php/vendors/catlog/list_product";
				$data['page_temp']     = $this->load->view('vendors/catlog/product/add_product',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "list_product";
				$this->bassthaya->load_vendors_form_template($data);
			}
		}

		public function list_product($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Catlog";
				$page['sub_heading']  = "Product";
				$page['page_title']   = "List Product";
				$page['pre_title']    = "Add Product";
				$page['pre_menu']     = "index.php/vendors/catlog/add_product";
				$data['page_temp']    = $this->load->view('vendors/catlog/product/list_product',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_product";
				$this->bassthaya->load_vendors_form_template($data);
			}

			else if($param1 == 'data_list')
			{
                $limit     = $this->input->post('limitval');
            	$page      = $this->input->post('page');
            	$search    = $this->input->post('search');
            	$agents_id = $this->session->userdata('id');
            	$cur_page  = isset($page)?$page:'1';
            	$_offset   = ($cur_page-1) * $limit;
            	$nxt_page  = $cur_page + 1;
            	$pre_page  = $cur_page - 1;

            	$where = array(
            		'offset'    => $_offset,
            		'limit'     => $limit,
            		'search'    => $search,
            		'item_type' => '2',
            		'vendor_id' => $agents_id,
            		'method'    => '_listProductPaginate'
            	);

            	$data_list  = avul_call(API_URL.'catlog/api/product',$where);
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
	            		$product_id    = !empty($value['product_id'])?$value['product_id']:'';
					    $product_name  = !empty($value['product_name'])?$value['product_name']:'';
					    $category_name = !empty($value['category_name'])?$value['category_name']:'';
					    $brand_name    = !empty($value['brand_name'])?$value['brand_name']:'';
					    $hsn_code      = !empty($value['hsn_code'])?$value['hsn_code']:'';
					    $price         = !empty($value['price'])?$value['price']:'-';
					    $gst           = !empty($value['gst'])?$value['gst']:'';
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

					    $table .= '
					    	<tr>
                                <td class="line_height">'.$i.'</td>
                                <td class="line_height">'.mb_strimwidth($product_name, 0, 40, '...').'</td>
                                <td class="line_height">'.$hsn_code.'</td>
                                <td class="line_height">'.$gst.'</td>
                                <td class="line_height">'.$status_view.'</td>
                                <td>
                                	<a href="'.BASE_URL.'index.php/vendors/catlog/add_product/Edit/'.$product_id.'" class="button_clr btn btn-primary"><i class="ft-edit"></i> Edit </a>
                                </td>
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

			else if($param1 == 'delete')
			{
				$id = $this->input->post('id');

				if(!empty($id))	
				{
					$data = array(
				    	'product_id' => $id,
				    	'method'     => '_deleteProduct'
				    );

				    $data_save = avul_call(API_URL.'catlog/api/product',$data);

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
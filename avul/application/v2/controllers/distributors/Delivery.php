<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Delivery extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function add_delivery_challan($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');
			$distributor_id = $this->session->userdata('id');
			$vendor_id      = $this->session->userdata('vendor_id');

			if($formpage == 'BTBM_X_P')
			{
				$dis_product_id  = $this->input->post('dis_product_id');
				$dis_product_qty = $this->input->post('dis_product_qty');
				$dis_price_val   = $this->input->post('dis_price_val');
				$dis_unit_id     = $this->input->post('dis_unit_id');

				$error = FALSE;

				if(count(array_filter($dis_product_id))!==count($dis_product_id) || count(array_filter($dis_product_qty))!==count($dis_product_qty) || count(array_filter($dis_unit_id))!==count($dis_unit_id))
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
			    		$dis_product_count = count($dis_product_id);
			    		$purchase_type     = [];

			    		for ($i=0; $i < $dis_product_count; $i++) { 

			    			$purchase_type[] = array(
			    				'dis_product_id'  => $dis_product_id[$i],
			    				'dis_product_qty' => $dis_product_qty[$i],
			    				'dis_price_val'   => $dis_price_val[$i],
			    				'dis_unit_id'     => $dis_unit_id[$i],
			    			);
			    		}

			    		$purchase_value = json_encode($purchase_type);

			    		$data = array(
					    	'distributor_id'   => $distributor_id,
					    	'purchase_value'   => $purchase_value,
					    	'active_financial' => $this->session->userdata('active_year'),
					    	'method'           => '_addDeliverychallan',
					    );

					    $data_save = avul_call(API_URL.'distributordelivery/api/add_delivery',$data);

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

			else if($param1 == 'getDistributorProduct_details')
			{
				$product_id = $this->input->post('product_id');

				$pdt_whr = array(
					'distributor_id' => $distributor_id,
					'assproduct_id'  => $product_id,
					'method'         => '_detailsDistributorAssignProduct_Dc',
				);

				$product_list  = avul_call(API_URL.'assignproduct/api/list_assign_product', $pdt_whr);
            	$product_val   = $product_list['data'];

            	$pdt_unit  = !empty($product_val['product_unit'])?$product_val['product_unit']:'';
                $unit_name = !empty($product_val['unit_name'])?$product_val['unit_name']:'';
                $pdt_price = !empty($product_val['product_price'])?$product_val['product_price']:'0';

                $option = '<option value="'.$pdt_unit.'">'.$unit_name.'</option>';

                $response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        $response['price']   = $pdt_price;
		        echo json_encode($response);
		        return;
			}

			else if($param1 == 'getDistributorPurchase_row')
			{
				$rowCount  = $this->input->post('rowCount');
				$newCount  = $rowCount + 1;

				// Product List
				$where_1  = array(
					'distributor_id' => $distributor_id,
					'vendor_id'      => $vendor_id,
					'published'      => '1',
					'status'         => '1',
					'method'         => '_listDistributorAssignProduct'
				);

				$pdt_list = avul_call(API_URL.'assignproduct/api/list_assign_product', $where_1);
				$pdt_val  = $pdt_list['data'];

				// Unit List
				$where_2 = array(
            		'method'    => '_listUnit'
            	);

            	$unit_list = avul_call(API_URL.'master/api/unit',$where_2);
            	$unit_val  = $unit_list['data'];

            	$option = '
            		<tr class="row_'.$newCount.'">
            			<script src="'.BASE_URL.'app-assets/js/select2.full.js"></script>
            			<script>
							var baseurl = $(".geturl").val();

							if($(".js-select2-multi").length)
						    {
						        $(".js-select2-multi").select2({
						            placeholder: "Select Value",
						        });
						    }

						    if($(".product_id").length)
						    {
						        $(".product_id").on("change",function(){
						            var $row       = $(this).closest("tr");
						            var product_id = $(this).val();
						            var value      = $("#value").val();
						            var cntrl      = $("#cntrl").val();
						            var func       = $("#func").val();

						            $.ajax({
						                method: "POST",
						                data: {
						                    "product_id" : product_id,
						                },
						                url: baseurl+"index.php/"+value+"/"+cntrl+"/"+func+"/getDistributorProduct_details",
						                dataType: "json",
						            }).done(function (response)
						            {
						                if(response["status"] == 1)
						                {
						                    $row.find(".dis_unit_id").empty().html(response[\'data\']);
						                    $row.find(".dis_product_price").empty().val(response[\'price\']);
						                    $row.find(".dis_price_val").empty().val(response[\'price\']);
						                }
						            });
						        });
						    }

						</script>  

	                    <td data-te="'.$newCount.'" class="p-l-0 dis_product_list" style="width: 40%;">
	                        <select data-te="'.$newCount.'" name="dis_product_id[]" id="dis_product_id'.$newCount.'" class="form-control dis_product_id'.$newCount.' product_id js-select2-multi" data-te="'.$newCount.'" style="width: 100%;">
	                        	<option value="">Select Product Name</option>';
	                        	if(!empty($pdt_val))
	                        	{
	                        		foreach ($pdt_val as $key => $value) {

	                        			$assproduct_id = !empty($value['assproduct_id'])?$value['assproduct_id']:'';
                                        $description   = !empty($value['description'])?$value['description']:'';

                                        $option .= '<option value="'.$assproduct_id.'">'.$description.'</option>';
	                        		}
	                        	}
	                        $option .='</select>
	                    </td>
	                    <td class="p-l-0">
	                        <input type="text" data-te="'.$newCount.'" name="dis_product_price[]" id="dis_product_price'.$newCount.'" class="form-control bg-white dis_product_price'.$newCount.' dis_product_price int_value" placeholder="Price" readonly="readonly">
	                    </td>
	                    <td class="p-l-0">
	                    	<input type="text" data-te="'.$newCount.'" name="dis_product_qty[]" id="dis_product_qty'.$newCount.'" class="form-control dis_product_qty'.$newCount.' dis_product_qty int_value" placeholder="Quantity">

	                        <input type="hidden" data-te="'.$newCount.'" name="dis_purchase_id[]" id="dis_purchase_id'.$newCount.'" class="form-control dis_purchase_id'.$newCount.' dis_purchase_id" placeholder="Enter the Price">

	                        <input type="hidden" data-te="'.$newCount.'" name="dis_price_val[]" id="dis_price_val'.$newCount.'" class="form-control dis_price_val'.$newCount.' dis_price_val" placeholder="Enter the Price" value="">
	                    </td>
	                    <td class="p-l-0" style="width: 30%;">
	                        <select data-te="'.$newCount.'" name="dis_unit_id[]" id="dis_unit_id'.$newCount.'" class="form-control dis_unit_id'.$newCount.' dis_unit_id js-select2-multi" data-te="'.$newCount.'" style="width: 100%;">
	                            <option value="">Select Unit Name</option>';
	                            if(!empty($unit_val))
	                            {
	                            	foreach ($unit_val as $key => $value) {

                                        $unit_id   = !empty($value['unit_id'])?$value['unit_id']:'';
                                        $unit_name = !empty($value['unit_name'])?$value['unit_name']:'';

                                        $option .= '<option value="'.$unit_id.'">'.$unit_name.'</option>';
                                    }
	                            }
                            $option .='</select>
                    	</td>
                    	<td class="buttonlist p-l-0">
	                        <button type="button" name="remove" class="btn btn-danger btn-sm remove_item button_size m-t-6"><span class="ft-minus-square"></span></button>
	                    </td>
	                </tr>
            	';

            	$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        $response['count']   = $newCount;
		        echo json_encode($response);
		        return; 
			}

			else
			{
				// Product List
				$where_1  = array(
					'distributor_id' => $distributor_id,
					'vendor_id'      => $vendor_id,
					'published'      => '1',
					'status'         => '1',
					'method'         => '_listDistributorAssignProduct'
				);

				$pdt_list = avul_call(API_URL.'assignproduct/api/list_assign_product', $where_1);
				$pdt_val  = $pdt_list['data'];

				// Unit List
				$where_2 = array(
            		'method'    => '_listUnit'
            	);

            	$unit_list = avul_call(API_URL.'master/api/unit',$where_2);
            	$unit_val  = $unit_list['data'];

				$page['dataval']     = '';
				$page['product_val'] = $pdt_val;
				$page['unit_val']    = $unit_val;
				$page['method']      = 'BTBM_X_C';
				$page['page_title']  = "Add Delivery Challan";
				$page['main_heading'] = "Delivery Challan";
				$page['sub_heading']  = "Delivery Challan";
				$page['pre_title']    = "List Delivery Challan";
				$page['pre_menu']     = "index.php/distributors/delivery/list_delivery_challan";
				$data['page_temp']    = $this->load->view('distributors/delivery/add_delivery_challan',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_delivery_challan";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		public function list_delivery_challan($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');
			$distributor_id = $this->session->userdata('id');

			if($param1 == '')
			{
				$page['main_heading'] = "Delivery Challan";
				$page['sub_heading']  = "Delivery Challan";
				$page['page_title']   = "List Delivery Challan";
				$page['pre_title']    = "Add Delivery Challan";
				$page['pre_menu']     = "index.php/distributors/delivery/add_delivery_challan";
				$data['page_temp']    = $this->load->view('distributors/delivery/list_delivery_challan',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_delivery_challan";
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
            		'offset'          => $_offset,
            		'limit'           => $limit,
            		'search'          => $search,
            		'distributor_id'  => $distributor_id,
            		'financial_year'  => $this->session->userdata('active_year'),
            		'method'          => '_listDistributorDeliveryPaginate'
            	);

            	$data_list  = avul_call(API_URL.'distributordelivery/api/manage_delivery',$where);
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

	            		$order_id       = !empty($value['order_id'])?$value['order_id']:'';
			            $order_no       = !empty($value['order_no'])?$value['order_no']:'';
			            $distributor_id = !empty($value['distributor_id'])?$value['distributor_id']:'';
			            $company_name   = !empty($value['company_name'])?$value['company_name']:'';
			            $order_date     = !empty($value['order_date'])?$value['order_date']:'';
			            $order_status   = !empty($value['order_status'])?$value['order_status']:'';
			            $_ordered       = !empty($value['_ordered'])?$value['_ordered']:'';
			            $financial_year = !empty($value['financial_year'])?$value['financial_year']:'';
			            $bill           = !empty($value['bill'])?$value['bill']:'';
			            $published      = !empty($value['published'])?$value['published']:'';
			            $active_status  = !empty($value['status'])?$value['status']:'';
			            $createdate     = !empty($value['createdate'])?$value['createdate']:'';

					    if($active_status == '1')
		                {
		                	$status_view = '<span class="badge badge-success">Active</span>';
		                }
		                else
		                {
		                	$status_view = '<span class="badge badge-danger">In Active</span>';
		                }

		                // Order Status
					    if($order_status == '1')
					    {
					        $order_view = '<span class="badge badge-success">Success</span>';
					    }
					    else if($order_status == '2')
					    {
					        $order_view = '<span class="badge badge-warning">Approved</span>';
					    }
					    else if($order_status == '3')
					    {
					        $order_view = '<span class="badge badge-primary">Packing</span>';
					    }
					    else if($order_status == '4')
					    {
					        $order_view = '<span class="badge badge-info">Invoice</span>';
					    }
					    else if($order_status == '10')
					    {
					        $order_view = '<span class="badge badge-warning">Shipping</span>';
					    }
					    else if($order_status == '11')
					    {
					        $order_view = '<span class="badge badge-primary">Delivered</span>';
					    }
					    else if($order_status == '5')
					    {
					        $order_view = '<span class="badge badge-success">Complete</span>';
					    }
					    else if($order_status == '7')
					    {
					        $order_view = '<span class="badge badge-danger">Cancel Invice</span>';

					        $order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
					    }
					    else
					    {
					        $order_view = '<span class="badge badge-danger">Cancel</span>';
					    }

					    $table .= '
					    	<tr>
                                <td class="line_height">'.$i.'</td>
                                <td class="line_height">'.$order_no.'</td>
                                <td class="line_height">'.$order_date.'</td>
                                <td class="line_height">'.$order_view.'</td>
                                <td>
                                	<a href="'.BASE_URL.'index.php/distributors/delivery/view_delivery_challan/View/'.$order_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a>
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
		}

		public function view_delivery_challan($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$distributor_id = $this->session->userdata('id');

			if($param1 =='View')
			{
				$order_id = $param2;

				$where = array(
			    	'order_id'       => $order_id,
			    	'distributor_id' => $distributor_id,
			    	'view_type'      => 2,
			    	'method'         => '_viewDistributorDelivery'
			    );

			    $data_list  = avul_call(API_URL.'distributordelivery/api/manage_delivery',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['purchase_data'] = $data_value;
				$page['main_heading']  = "Delivery Challan";
				$page['sub_heading']   = "Manage Delivery Challan";
				$page['page_title']    = "Delivery Challan Invoice";
				$page['pre_title']     = "Delivery Challan";
				$page['pre_menu']      = "index.php/distributors/delivery/list_delivery_challan";
				$data['page_temp']     = $this->load->view('distributors/delivery/view_delivery_challan',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "list_delivery_challan";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}
	}
?>
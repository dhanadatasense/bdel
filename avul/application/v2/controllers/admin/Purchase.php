<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Purchase extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}

		public function add_purchase($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$vendor_id     = $this->input->post('vendor_id');
				$order_date    = $this->input->post('order_date');
				$product_id    = $this->input->post('product_id');
				$type_id       = $this->input->post('type_id');
				$product_qty   = $this->input->post('product_qty');
				$product_price = $this->input->post('product_price');
				$unit_id       = $this->input->post('unit_id');
				$method        = $this->input->post('method');

				$required = array('vendor_id', 'order_date');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if(count(array_filter($product_id))!==count($product_id) || count(array_filter($type_id))!==count($type_id) || count(array_filter($product_qty))!==count($product_qty) || $error == TRUE)
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
			    		if(userAccess('purchase-add'))
			    		{
			    			$purchase_type  = [];
				    		$purchase_count = count($product_id);

				    		for($j = 0; $j < $purchase_count; $j++)
				    		{
				    			$purchase_type[] = array(
				    				'product_id'    => $product_id[$j],
				    				'type_id'       => $type_id[$j],
				    				'product_price' => $product_price[$j],
				    				'product_qty'   => $product_qty[$j],
				    				'product_unit'  => $unit_id[$j],
				    			);
				    		}

				    		$purchase_value = json_encode($purchase_type);

				    		$data = array(
						    	'vendor_id'        => $vendor_id,
						    	'purchase_value'   => $purchase_value,
						    	'order_date'       => date('Y-m-d', strtotime($order_date)),
						    	'active_financial' => $this->session->userdata('active_year'),
						    	'method'           => '_addPurchase',
						    );


						    $data_save = avul_call(API_URL.'purchase/api/add_purchase',$data);

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
					        $response['message'] = 'Access denied'; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
			    		}
			    	}
			    }
			}

			else if($param1 =='getVendor_details')
			{
				$vendor_id = $this->input->post('vendor_id');

				$where = array(
            		'vendor_id' => $vendor_id,
            		'method'    => '_detailVendors'
            	);

            	$vendor_list   = avul_call(API_URL.'vendors/api/vendors',$where);
            	$data_status   = $vendor_list['status'];
            	$vendor_result = $vendor_list['data'];

            	if($data_status == 1)
            	{
            		$option = array(
	            		'gst_no'     => $vendor_result[0]['gst_no'],
	            		'contact_no' => $vendor_result[0]['contact_no'],
	            		'address'    => $vendor_result[0]['address'],
	            	);

	            	$response['status']  = 1;
			        $response['message'] = 'success'; 
			        $response['data']    = $option;
			        echo json_encode($response);
			        return; 
            	}
			}

			else if($param1 =='getVendor_products')
			{
				$vendor_id = $this->input->post('vendor_id');

				$where = array(
            		'vendor_id' => $vendor_id,
            		'method'    => '_listVendorProducts'
            	);

            	$vendor_list   = avul_call(API_URL.'catlog/api/product',$where);
            	$vendor_result = $vendor_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($vendor_result))
        		{
        			foreach ($vendor_result as $key => $value) {
        				$product_id   = !empty($value['product_id'])?$value['product_id']:'';
                        $product_name = !empty($value['product_name'])?$value['product_name']:'';

                        $option .= '<option value="'.$product_id.'">'.$product_name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return;
			}

			else if($param1 =='getPurchase_row')
			{	
				$vendor_id = $this->input->post('vendor_id');
				$rowCount  = $this->input->post('rowCount');
				$newCount  = $rowCount + 1;

				$where_1 = array(
					'vendor_id' => $vendor_id,
            		'method'    => '_listVendorProducts'
            	);

            	$product_list  = avul_call(API_URL.'catlog/api/product',$where_1);
            	$product_val   = $product_list['data'];

            	$where_2 = array(
            		'method'    => '_listUnit'
            	);

	            $unit_list  = avul_call(API_URL.'master/api/unit',$where_2);
	            $unit_val   = $unit_list['data'];

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
						            var auto_id    = $(this).attr("data-te");
						            var value      = $("#value").val();
						            var cntrl      = $("#cntrl").val();
						            var func       = $("#func").val();

						            $.ajax({
						                method: "POST",
						                data: {
						                    "product_id" : product_id,
						                },
						                url: baseurl+"index.php/"+value+"/"+cntrl+"/"+func+"/getVendor_productType",
						                dataType: "json",
						            }).done(function (response)
						            {
						                if(response["status"] == 1)
						                {
						                    $row.find(".type_id").empty().html(response[\'data\']);
						                }
						            });
						        });
						    }

						    if($(".type_id").length)
						    {
						        $(".type_id").on("change",function(){

						        	var $row    = $(this).closest("tr");
						            var type_id = $(this).val();
						            var value   = $("#value").val();
						            var cntrl   = $("#cntrl").val();
						            var func    = $("#func").val();

						            $.ajax({
						                method: "POST",
						                data: {
						                    "type_id" : type_id,
						                },
						                url: baseurl+"index.php/"+value+"/"+cntrl+"/"+func+"/getProductType_details",
						                dataType: "json",
						            }).done(function (response)
						            {
						                if(response["status"] == 1)
						                {
						                    $row.find(".unit_id").empty().html(response[\'data\']);
                    						$row.find(".product_price").empty().val(response[\'price\']);
						                }
						            });
						        });
						    }

						</script>
                        <td data-te="'.$newCount.'" class="p-l-0 product_list" style="width: 30%;">
                            <select data-te="'.$newCount.'" name="product_id[]" id="product_id'.$newCount.'" class="form-control product_id'.$newCount.' product_id js-select2-multi">
                                <option value="">Select Product Name</option>';
                                if(!empty($product_val))
	                            {
	                            	foreach ($product_val as $key => $value) {
	                            		$product_id   = !empty($value['product_id'])?$value['product_id']:'';
	                                    $product_name = !empty($value['product_name'])?$value['product_name']:'';

	                                    $option .="<option value=".$product_id.">".$product_name."</option>";
	                            	}
	                            }
                            $option .=' </select> 
                        </td>
                        <td data-te="'.$newCount.'" class="p-l-0 product_list" style="width: 30%;">
                            <select data-te="'.$newCount.'" name="type_id[]" id="type_id'.$newCount.'" class="form-control type_id'.$newCount.' type_id js-select2-multi" >
                                <option value="">Select Product Name</option>
                            </select> 
                        </td>
                        <td class="p-l-0" style="width: 13%;">
                            <input type="text" data-te="'.$newCount.'" name="product_price[]" id="product_price'.$newCount.'" class="form-control product_price'.$newCount.' product_price int_value" placeholder="Price">
                        </td>
                        <td class="p-l-0" style="width: 13%;">
                            <input type="text" data-te="'.$newCount.'" name="product_qty[]" id="product_qty'.$newCount.'" class="form-control product_qty'.$newCount.' product_qty int_value" placeholder="Quantity">

                            <input type="hidden" data-te="'.$newCount.'" name="purchase_id[]" id="purchase_id'.$newCount.'" class="form-control purchase_id'.$newCount.' purchase_id" placeholder="Price">
                        </td>
                        <td class="p-l-0" style="width: 14%;">
                            <select data-te="'.$newCount.'" name="unit_id[]" id="unit_id'.$newCount.'" class="form-control unit_id'.$newCount.' unit_id js-select2-multi" >
                                <option value="">Select Unit Name</option>';
                                if(!empty($unit_val))
	                            {
	                            	foreach ($unit_val as $key => $value) {
	                            		$unit_id   = !empty($value['unit_id'])?$value['unit_id']:'';
	                                    $unit_name = !empty($value['unit_name'])?$value['unit_name']:'';

	                                    $option .="<option value=".$unit_id.">".$unit_name."</option>";
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

			else if($param1 =='getVendor_productType')
			{
				$product_id = $this->input->post('product_id');

				$where = array(
            		'product_id' => $product_id,
            		'method'     => '_listProductType'
            	);

            	$type_list   = avul_call(API_URL.'catlog/api/productType',$where);
            	$type_result = $type_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($type_result))
        		{
        			foreach ($type_result as $key => $value) {
        				$type_id     = !empty($value['type_id'])?$value['type_id']:'';
                        $description = !empty($value['description'])?$value['description']:'';

                        $option .= '<option value="'.$type_id.'">'.$description.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return;
			}

			else if($param1 =='getProductType_details')
			{
				$type_id = $this->input->post('type_id');

				$where_1 = array(
					'type_id' => $type_id,
            		'method'  => '_purchaseProductType',
            	);

            	$product_list  = avul_call(API_URL.'catlog/api/productType',$where_1);
            	$product_val   = $product_list['data'];


            	$unit_id   = !empty($product_val['product_unit'])?$product_val['product_unit']:'';
                $unit_name = !empty($product_val['unit_name'])?$product_val['unit_name']:'';
                $price     = !empty($product_val['ven_price'])?$product_val['ven_price']:'0';

                $option = '<option value="'.$unit_id.'">'.$unit_name.'</option>';

                $response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        $response['price']   = $price;
		        echo json_encode($response);
		        return;
			}

			else
			{
				if($param1 =='Edit')
				{
	            	$where = array(
				    	'purchase_id' => $param2,
				    	'method'      => '_listPurchaseDetails'
				    );

				    $data_val = avul_call(API_URL.'purchase/api/manage_purchase_details',$where);

					$page['dataval']     = $data_val['data'];
					$page['method']      = 'BTBM_X_U';
					$page['page_title']  = "Edit Purchase";
				}
				else
				{
					$page['dataval']     = '';
					$page['method']      = 'BTBM_X_C';
					$page['page_title']  = "Add Purchase";
				}

				$where_1 = array(
            		'method'    => '_listManufacturerVendors'
            	);

            	$vendor_list = avul_call(API_URL.'vendors/api/vendors',$where_1);

            	$where_2 = array(
            		'item_type'      => '1',
            		'salesagents_id' => '0',
            		'method'         => '_listProduct'
            	);

            	$product_list  = avul_call(API_URL.'catlog/api/product',$where_2);

            	$where_3 = array(
            		'method'    => '_listUnit'
            	);

            	$unit_list  = avul_call(API_URL.'master/api/unit',$where_3);
					
				$page['vendor_val']   = $vendor_list['data'];
				$page['product_val']  = $product_list['data'];
				$page['unit_val']     = $unit_list['data'];
				$page['main_heading'] = "Purchase";
				$page['sub_heading']  = "Purchase";
				$page['pre_title']    = "List Purchase";
				$page['page_access']  = userAccess('purchase-view');
				$page['pre_menu']     = "index.php/admin/purchase/list_purchase";
				$data['page_temp']    = $this->load->view('admin/purchase/add_purchase',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_purchase";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function list_purchase($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$order_id = $this->input->post('order_id');
        	$method   = $this->input->post('method');

        	if($method == 'changeOrder_process')
			{
				$order_data = array(
		    		'auto_id'  => $order_id,
					'progress' => '2',
					'method'   => '_updateOrderProgress',
				);

				$data_save = avul_call(API_URL.'purchase/api/order_process',$order_data);

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

			if($param1 == '')
			{
				$page['main_heading'] = "Purchase";
				$page['sub_heading']  = "Purchase";
				$page['page_title']   = "List Purchase";
				$page['pre_title']    = "Add Purchase";
				$page['page_access']  = userAccess('purchase-add');
				$page['pre_menu']     = "index.php/admin/purchase/add_purchase";
				$data['page_temp']    = $this->load->view('admin/purchase/list_purchase',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_purchase";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('purchase-view'))
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
	            		'financial_year'  => $this->session->userdata('active_year'),
	            		'method'          => '_listPurchasePaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'purchase/api/manage_purchase',$where);
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
		            		$po_id         = !empty($value['po_id'])?$value['po_id']:'';
				            $po_no         = !empty($value['po_no'])?$value['po_no']:'';
				            $vendor_id     = !empty($value['vendor_id'])?$value['vendor_id']:'';
				            $vendor_name   = !empty($value['vendor_name'])?$value['vendor_name']:'';
				            $contact_no    = !empty($value['contact_no'])?$value['contact_no']:'';
				            $order_date    = !empty($value['order_date'])?$value['order_date']:'';
				            $order_status  = !empty($value['order_status'])?$value['order_status']:'';
				            $bill          = !empty($value['bill'])?$value['bill']:'';
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

						    if($order_status == '1')
						    {
						        $order_view = '<span class="badge badge-success">Success</span>';

						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="purchase" data-func="list_purchase" data-id="'.$po_id.'" data-method="changeOrder_process"><i class="ft-edit"></i></a>';
						    }
						    else if($order_status == '2')
						    {
						        $order_view = '<span class="badge badge-warning">Process</span>';

						        // Order Status
			                	$order_btn = "<a href='".BASE_URL."index.php/admin/purchase/purchase_entry/Add/".$po_id."' class='button_clr btn btn-info'><i class='ft-grid'></i></a>";
						    }
						    else if($order_status == '3')
						    {
						        $order_view = '<span class="badge badge-primary">Packing</span>';

						        // Order Status
			                	$order_btn = "<a href='".BASE_URL."index.php/admin/purchase/purchase_entry/Add/".$po_id."' class='button_clr btn btn-info'><i class='ft-grid'></i></a>";
						    }
						    else if($order_status == '4')
						    {
						        $order_view = '<span class="badge badge-info">Delivery</span>';

						        // Order Status
			                	$order_btn = "<a href='".BASE_URL."index.php/admin/purchase/purchase_entry/Add/".$po_id."' class='button_clr btn btn-info'><i class='ft-grid'></i></a>";
						    }
						    else if($order_status == '5')
						    {
						        $order_view = '<span class="badge badge-success">Complete</span>';

						        // Order Status
			                	$order_btn = "<a href='".BASE_URL."index.php/admin/purchase/purchase_entry/Add/".$po_id."' class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
						    }
						    else if($order_status == '9')
						    {
						        $order_view = '<span class="badge badge-danger">Cancel Invoice</span>';

						        // Order Status
			                	$order_btn = "<a class='button_clr btn btn-danger'><i class='ft-trash-2'></i></a>";
						    }
						    else
						    {
						        $order_view = '<span class="badge badge-danger">Cancel</span>';

						        // Order Status
			                	$order_btn = "<a class='button_clr btn btn-danger'><i class='ft-trash-2'></i></a>";
						    }

						    $edit = '';
				            $view = '';
				            if(userAccess('purchase-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('purchase-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/purchase/view_purchase/View/'.$po_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$po_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($vendor_name, 0, 15, '...').'</td>
	                                <td class="line_height">'.$order_date.'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('purchase-edit') == TRUE || userAccess('purchase-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	endif;
	                            $table .='</tr>
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
				    	'po_id'  => $id,
				    	'method' => '_deletePurchase'
				    );

				    $data_save = avul_call(API_URL.'purchase/api/manage_purchase',$data);

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

		public function view_purchase($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			if($param1 =='View')
			{
				$purchase_id = $param2;

				$where_1 = array(
			    	'purchase_id' => $purchase_id,
			    	'method'      => '_listPurchaseDetails'
			    );

			    $data_val = avul_call(API_URL.'purchase/api/manage_purchase_details',$where_1);

			    // Admin Details
				$where_2 = array(
			    	'user_id' => '1',
			    	'method'  => '_userDetails'
			    );

			    $admin_val = avul_call(API_URL.'user/api/profile_settings',$where_2);

			    $page['admin_data']    = $admin_val['data'];
			    $page['purchase_data'] = $data_val['data'];
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				$page['page_access']   = userAccess('purchase-view');
				$page['pre_menu']      = "index.php/admin/purchase/list_purchase";
				$data['page_temp']     = $this->load->view('admin/purchase/view_purchase',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "list_purchase";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function purchase_stock($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			$formpage = $this->input->post('formpage');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$po_id         = $this->input->post('po_id');
				$po_auto_id    = $this->input->post('po_auto_id');
				$product_id    = $this->input->post('product_id');
				$type_id       = $this->input->post('type_id');
				$product_unit  = $this->input->post('product_unit');
				$bill_no       = $this->input->post('bill_no');
				$received_qty  = $this->input->post('received_qty');
				$received_date = $this->input->post('received_date');

				$required = array('po_id', 'po_auto_id', 'product_id', 'type_id', 'product_unit', 'bill_no', 'received_qty', 'received_date');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error == TRUE)
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
			    	$data = array(
				    	'po_id'         => $po_id,
				    	'po_auto_id'    => $po_auto_id,
				    	'product_id'    => $product_id,
				    	'type_id'       => $type_id,
				    	'product_unit'  => $product_unit,
				    	'bill_no'       => $bill_no,
				    	'received_qty'  => $received_qty,
				    	'received_date' => date('Y-m-d', strtotime($received_date)),
				    	'method'        => '_addPurchaseStockDetails',
				    );

				    $data_save = avul_call(API_URL.'purchase/api/manage_purchase_stock_details',$data);

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

			if($param1 =='stock_list')
			{
				$purchase_id = $param2;

				// Product Details
				$where_1 = array(
			    	'purchase_id' => $purchase_id,
			    	'method'      => '_listPurchaseDetails'
			    );

			    $product_val = avul_call(API_URL.'purchase/api/manage_purchase_details',$where_1);

			    $page['purchase_data'] = $product_val['data'];
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				$page['pre_menu']      = "index.php/admin/purchase/list_purchase";
				$data['page_temp']     = $this->load->view('admin/purchase/purchase_stock',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "list_purchase";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 =='stock_add')
			{
				$purchase_id = $param3;
				$auto_id     = $param2;

				// Purchase Details
				$where_1 = array(
			    	'purchase_id' => $purchase_id,
			    	'method'      => '_detailPurchase'
			    );

			    $purchase_val = avul_call(API_URL.'purchase/api/manage_purchase',$where_1);

				// Product Details
				$where_2 = array(
			    	'purchase_id' => $purchase_id,
			    	'auto_id'     => $auto_id,
			    	'method'      => '_listProductDetails'
			    );

			    $product_val = avul_call(API_URL.'purchase/api/manage_purchase_details',$where_2);

			    $product_id  = !empty($product_val['data']['product_id'])?$product_val['data']['product_id']:'';

			    // Stock Details
			    $where_3 = array(
			    	'po_id'      => $purchase_id,
			    	'po_auto_id' => $auto_id,
			    	'product_id' => $product_id,
			    	'method'     => '_listPurchaseStockDetails'
			    );

			    $stock_val = avul_call(API_URL.'purchase/api/manage_purchase_stock_details',$where_3);

			    $page['purchase_data'] = $purchase_val['data'];
			    $page['product_data']  = $product_val['data'];
			    $page['stock_data']    = $stock_val['data'];
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				$page['page_access']   = userAccess('purchase-view');
				$page['pre_menu']      = "index.php/admin/purchase/purchase_stock/stock_list/$purchase_id";
				$data['page_temp']     = $this->load->view('admin/purchase/stock_add',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "list_purchase";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'delete')
			{
				$id = $this->input->post('id');

				if(!empty($id))	
				{
					$data = array(
				    	'stock_id' => $id,
				    	'method'   => '_deletePurchaseStockDetails'
				    );

				    $data_save = avul_call(API_URL.'purchase/api/manage_purchase_stock_details',$data);

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

		public function purchase_entry($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;

				$vendor_id     = $this->input->post('vendor_id');
				$purchase_id   = $this->input->post('purchase_id');
				$bill_no       = $this->input->post('bill_no');
				$received_date = $this->input->post('received_date');
				$item_id       = $this->input->post('item_id');
				$product_id    = $this->input->post('product_id');
				$type_id       = $this->input->post('type_id');
				$product_qty   = $this->input->post('product_qty');
				$received_qty  = $this->input->post('received_qty');
				$product_unit  = $this->input->post('product_unit');

				$required = array('vendor_id', 'purchase_id', 'bill_no', 'received_date');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if(count(array_filter($item_id))!==count($item_id) || count(array_filter($product_id))!==count($product_id) || count(array_filter($type_id))!==count($type_id) || count(array_filter($product_unit))!==count($product_unit) || $error == TRUE)
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
			    	$stock_type  = [];
		    		$stock_count = count($item_id);

		    		for($j = 0; $j < $stock_count; $j++)
		    		{
		    			$stock_type[] = array(
		    				'item_id'      => $item_id[$j],
		    				'product_id'   => $product_id[$j],
		    				'type_id'      => $type_id[$j],
		    				'product_unit' => $product_unit[$j],
		    				'product_qty'  => $product_qty[$j],
		    				'received_qty' => $received_qty[$j],
		    			);
		    		}

		    		$stock_value = json_encode($stock_type);

		    		$postData = array(
				    	'vendor_id'        => $vendor_id,
				    	'purchase_id'      => $purchase_id,
				    	'bill_no'          => $bill_no,
				    	'received_date'    => date('Y-m-d', strtotime($received_date)),
				    	'stock_type'       => $stock_value,
				    	'active_financial' => $this->session->userdata('active_year'),
				    	'method'           => '_addPurchaseEntry',
				    );

				    $data_save = avul_fileUpload(API_URL.'purchase/api/manage_purchase_stock_details', $postData);

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

			if($param1 == 'Add')
			{
				$where_1 = array(
			    	'purchase_id' => $param2,
			    	'method'      => '_listPurchaseDetails'
			    );

			    $data_val = avul_call(API_URL.'purchase/api/manage_purchase_details',$where_1);

			    $page['pur_data']     = $data_val['data'];
			    $page['main_heading'] = "Purchase";
				$page['sub_heading']  = "Manage Purchase";
				$page['page_title']   = "Purchase Entry";
				$page['pre_title']    = "Purchase";
				$page['page_access']  = userAccess('purchase-view');
				$page['pre_menu']     = "index.php/admin/purchase/list_purchase";
				$data['page_temp']    = $this->load->view('admin/purchase/purchase_entry',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_purchase";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function order_process($param1="", $param2="", $param3="")
		{
			if($param1 == 'order_update')
			{
				$error = FALSE;
    			$id   = $this->input->post('id');
				$rate = $this->input->post('rate');
				$qty  = $this->input->post('qty');

				$required = array('id', 'rate', 'qty');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error == TRUE)
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
			    	$order_data = array(
			    		'auto_id'  => $id,
						'price'    => $rate,
						'quantity' => $qty,
						'method'   => '_updateOrderDetails',
					);

					$update = avul_call(API_URL.'purchase/api/order_process',$order_data);

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

			if($param1 == 'changeOrder_process')
    		{
    			$error = FALSE;
    			$order_id     = $this->input->post('order_id');
				$order_status = $this->input->post('order_status');
				$message      = $this->input->post('message');

				$required = array('order_id', 'order_status');
				if($order_status == 8)
				{
					array_push($required, 'message');
				}
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error == TRUE)
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
			    	$order_data = array(
			    		'auto_id'  => $order_id,
						'progress' => $order_status,
						'reason'   => $message,
						'method'   => '_updateOrderProgress',
					);

					$data_save = avul_call(API_URL.'purchase/api/order_process',$order_data);

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

			else if($param1 == 'delete')
    		{
    			$error = FALSE;
    			$auto_id  = $this->input->post('id');
    			$progress = $this->input->post('progress');

    			$required = array('id', 'progress');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error == TRUE)
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
			    	if($progress == '1')
			    	{
			    		$data = array(
					    	'auto_id' => $auto_id,
					    	'method'  => '_DeleteOrderDetails',
					    );

					    $data_save = avul_call(API_URL.'purchase/api/order_process',$data);

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
		}

		public function print_order($param1="", $param2="", $param3="")
		{
			$purchase_id = $param1;

			$where_1 = array(
		    	'purchase_id' => $purchase_id,
		    	'method'      => '_listPurchaseDetails'
		    );

		    $data_val = avul_call(API_URL.'purchase/api/manage_purchase_details',$where_1);
		    $ord_data = $data_val['data'];

		    // Admin Details
			$where_2 = array(
		    	'user_id' => '1',
		    	'method'  => '_userDetails'
		    );

		    $admin_val  = avul_call(API_URL.'user/api/profile_settings',$where_2);
		    $admin_data = $admin_val['data'];

		    $adm_username = !empty($admin_data['username'])?$admin_data['username']:'';
		    $adm_mobile   = !empty($admin_data['mobile'])?$admin_data['mobile']:'';
		    $adm_address  = !empty($admin_data['address'])?$admin_data['address']:'';
		    $adm_state_id = !empty($admin_data['state_id'])?$admin_data['state_id']:'';
		    $adm_city_id  = !empty($admin_data['city_id'])?$admin_data['city_id']:'';
		    $adm_gst_no   = !empty($admin_data['gst_no'])?$admin_data['gst_no']:'';

		    $bil_det = $ord_data['bill_details'];
		    $ven_det = $ord_data['vendor_details'];
		    $pur_det = $ord_data['purchase_details'];

		    // Bill Details
            $purchase_no  = !empty($bil_det['purchase_no'])?$bil_det['purchase_no']:'';
            $order_status = !empty($bil_det['order_status'])?$bil_det['order_status']:'';
            $order_date   = !empty($bil_det['order_date'])?$bil_det['order_date']:'';

            // Vendor Details
            $company_name = !empty($ven_det['company_name'])?$ven_det['company_name']:'';
            $gst_no       = !empty($ven_det['gst_no'])?$ven_det['gst_no']:'';
            $contact_no   = !empty($ven_det['contact_no'])?$ven_det['contact_no']:'';
            $email        = !empty($ven_det['email'])?$ven_det['email']:'';
            $address      = !empty($ven_det['address'])?$ven_det['address']:'';
            $due_days     = !empty($ven_det['due_days'])?$ven_det['due_days']:'';
            $state_id     = !empty($ven_det['state_id'])?$ven_det['state_id']:'';

            $this->load->library('Pdf');
      		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'mm', array(216,356), TRUE, 'UTF-8', FALSE);
          	$pdf->SetTitle('Purchase Order');
          	$pdf->SetPrintHeader(false);
          	$pdf->SetPrintFooter(false);
          		
			$pdf->SetPrintHeader(false);
			$pdf->SetPrintFooter(false);

          	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
          	$pdf->SetFont('');
          	$pdf->AddPage('P');
          	$html = '';

          	$html .= '<p style="color:black; font-size:12px; text-align: center;"><strong style="font-size:18px; padding-bottom:1000px;">'.$adm_username.'</strong><br/>'.$adm_address.'<br>Ph :'.$adm_mobile.', GSTIN\UIN :'.$adm_gst_no.'<br><strong style="color:black; text-align:center; font-size:17px;"> PURCHASE ORDER</strong><br></p>';

          	$html .='<br><br><br>
				<table border= "1" cellpadding="1" top="100">
			        <tr>
			            <td rowspan="4" style="font-size:12px; width: 55%; margin-left:10px;">To: <br> '.$company_name.'<br> '.$address.'<br> Ph : '.$contact_no.'<br> GSTIN\UIN : '.$gst_no.'</td>
			            <td style="font-size:12px; width: 20%;"> Order No</td>
			            <td style="font-size:12px; width: 25%;">'.$purchase_no.'</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Order Date</td>
			            <td style="font-size:12px; width: 25%;">'.date('d-M-Y', strtotime($order_date)).'</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Due Days</td>
			            <td style="font-size:12px; width: 25%;">'.$due_days.'</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Others</td>
			            <td style="font-size:12px; width: 25%;"></td>
			        </tr>
			    </table>
			    ';

			$html .='<br><br>
			<table border= "1" cellpadding="1" top="100">
		        <tr>
		            <td style="font-size:12px; width: 5%;">S.No</td>
		            <td style="font-size:12px; width: 44%;">Description</td>
		            <td style="font-size:12px; text-align: center; width: 10%;">HSN</td>
		            <td style="font-size:12px; text-align: center; width: 10%;">Rate</td>
		            <td style="font-size:12px; text-align: center; width: 8%;">Qty</td>
		            <td style="font-size:12px; text-align: center; width: 8%;">Per</td>
		            <td style="font-size:12px; text-align: center; width: 15%;">Amount</td>
		        </tr>';

		        $num     = 1;
                $sub_tot = 0;
                $tot_gst = 0;
                $net_tot = 0;
                $tot_qty = 0;

		        foreach ($pur_det as $key => $val) {
                    $type_name = !empty($val['type_name'])?$val['type_name']:'';
		            $gst_value = !empty($val['gst_value'])?$val['gst_value']:'0';
		            $hsn_code  = !empty($val['hsn_code'])?$val['hsn_code']:'0';
		            $pdt_price = !empty($val['product_price'])?$val['product_price']:'0';
		            $pdt_qty   = !empty($val['product_qty'])?$val['product_qty']:'0';

		            $gst_data  = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
		            $price_val = $pdt_price - $gst_data;
		            $tot_price = $pdt_qty * $price_val;
		            $sub_tot  += $tot_price;
		            $tot_qty  += $pdt_qty;

		            // GST Calculation
		            $gst_val   = $pdt_qty * $gst_data;
		            $tot_gst  += $gst_val;
		            $total_val = $pdt_qty * $pdt_price;
		            $net_tot  += $total_val;

                    $html .= '
                    	<tr>
				            <td style="font-size:12px; width: 5%;">'.$num.'</td>
				            <td style="font-size:12px; width: 44%;">'.$type_name.'</td>
				            <td style="font-size:12px; text-align: center; width: 10%;">'.$hsn_code.'</td>
				            <td style="font-size:12px; text-align: center; width: 10%;">'.number_format((float)$price_val, 2, '.', '').'</td>
				            <td style="font-size:12px; text-align: center; width: 8%;">'.$pdt_qty.'</td>
				            <td style="font-size:12px; text-align: center; width: 8%;">nos</td>
				            <td style="font-size:12px; text-align: right; width: 15%;">'.number_format((float)$tot_price, 2, '.', '').'</td>
				        </tr>
                    ';
                    $num++;
                }

                $net_value  = round($net_tot);
                $rond_total = $net_value - $net_tot;

                $html .= '
                	<tr>
			            <td colspan="5" style="font-size:12px; width: 85%; text-align: right;">Total </td>
			            <td style="font-size:12px; text-align: right; width: 15%;">'.number_format((float)$sub_tot, 2, '.', '').'</td>
			        </tr>';

			        if($adm_state_id == $state_id)
		            {
		            	$gst_value = $tot_gst / 2;

		            	$html .= '
		            		<tr>
				                <td colspan="5" style="font-size:12px; text-align: right;">SGST</td>
				                <td style="font-size:12px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
				            </tr>
				            <tr>
				                <td colspan="5" style="font-size:12px; text-align: right;">CGST</td>
				                <td style="font-size:12px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
				            </tr>
		            	';
		            }
		            else
		            {
		            	$html .= '
		            		<tr>
				                <td colspan="5" style="font-size:12px; text-align: right;">IGST</td>
				                <td style="font-size:12px; text-align: right;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
				            </tr>
		            	';
		            }

		            $html .='<tr>
		                <td colspan="5" style="font-size:12px; text-align: right;">Round off</td>
		                <td style="font-size:12px; text-align: right;"> '.number_format((float)$rond_total, 2, '.', '').'</td>
		            </tr>
		            <tr>
		                <td colspan="5" style="font-size:12px; text-align: right;">Net Total</td>
		                <td style="font-size:12px; text-align: right;"> '.number_format((float)$net_value, 2, '.', '').'</td>
		            </tr>';

		    $html .='</table>';

			$pdf->writeHTML($html, true, false, true, false, '');
	       	$pdf->Output($company_name.'_'.$purchase_no.'_'.date('d-F-Y').'.pdf', 'I');
		}

		public function print_invoice($param1="", $param2="", $param3="")
		{
			$inv_whr = array(
				'method'    => '_invoiceDetails',
				'inv_value' => $param1,
			);

			$inv_val  = avul_call(API_URL.'purchase/api/manage_purchase_details',$inv_whr);
			$inv_data = $inv_val['data'];

			$vdr_det = $inv_data['vendor_details'];
			$ord_det = $inv_data['order_details'];
			$tax_det = $inv_data['tax_details'];

			$invoice_no   = !empty($vdr_det['invoice_no'])?$vdr_det['invoice_no']:'';
            $inv_date     = !empty($vdr_det['inv_date'])?$vdr_det['inv_date']:'';
            $po_no        = !empty($vdr_det['po_no'])?$vdr_det['po_no']:'';
            $po_date      = !empty($vdr_det['po_date'])?$vdr_det['po_date']:'';
            $company_name = !empty($vdr_det['company_name'])?$vdr_det['company_name']:'';
            $gst_no       = !empty($vdr_det['gst_no'])?$vdr_det['gst_no']:'';
            $email        = !empty($vdr_det['email'])?$vdr_det['email']:'';
            $address      = !empty($vdr_det['address'])?$vdr_det['address']:'';
            $account_name = !empty($vdr_det['account_name'])?$vdr_det['account_name']:'';
            $account_no   = !empty($vdr_det['account_no'])?$vdr_det['account_no']:'';
            $ifsc_code    = !empty($vdr_det['ifsc_code'])?$vdr_det['ifsc_code']:'';
            $bank_name    = !empty($vdr_det['bank_name'])?$vdr_det['bank_name']:'';
            $branch_name  = !empty($vdr_det['branch_name'])?$vdr_det['branch_name']:'';
            $vendor_state = !empty($vdr_det['vendor_state'])?$vdr_det['vendor_state']:'';
            $admin_state  = !empty($vdr_det['admin_state'])?$vdr_det['admin_state']:'';

			$this->load->library('Pdf');
      		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'mm', array(216,356), TRUE, 'UTF-8', FALSE);
          	$pdf->SetTitle('Manufacturer Invice');
          	$pdf->SetPrintHeader(false);
          	$pdf->SetPrintFooter(false);
          		
			$pdf->SetPrintHeader(false);
			$pdf->SetPrintFooter(false);

          	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
          	$pdf->SetFont('');
          	$pdf->AddPage('P');
          	$html = '';

          	$html .= '<p style="color:black; font-size:12px; text-align: center;"><strong style="font-size:18px; padding-bottom:1000px;">'.$company_name.'</strong><br/>'.$address.'<br>GSTIN\UIN : '.$gst_no.'<br><strong style="color:black; text-align:center; font-size:17px;"> TAX INVOICE</strong><br></p>';

          	$html .='<br><br><br>
				<table border= "1" cellpadding="1" top="100">
			        <tr>
			            <td rowspan="6" style="font-size:12px; width: 55%; margin-left:10px;">Shipped To: <br> '.ACCOUNT_NAME.'<br> '.ADDRESS.'<br> Ph: '.CONTACT_NO.'<br> GSTIN\UIN :'.ADMIN_GST.'</td>
			            <td style="font-size:12px; width: 20%;"> Invoice No</td>
			            <td style="font-size:12px; width: 25%;">'.$invoice_no.'</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Invoice Date</td>
			            <td style="font-size:12px; width: 25%;">'.$inv_date.'</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Buyer(s) Order No</td>
			            <td style="font-size:12px; width: 25%;">'.$po_no.'</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Buyer(s) Order Date</td>
			            <td style="font-size:12px; width: 25%;">'.$po_date.'</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Eway Bill No</td>
			            <td style="font-size:12px; width: 25%;"></td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Others</td>
			            <td style="font-size:12px; width: 25%;"></td>
			        </tr>
			    </table>
			    ';

			$html .='<br><br>
			<table border= "1" cellpadding="1" top="100">
		        <tr>
		            <td style="font-size:12px; width: 5%;">S.No</td>
		            <td style="font-size:12px; width: 44%;">Description</td>
		            <td style="font-size:12px; text-align: center; width: 10%;">HSN</td>
		            <td style="font-size:12px; text-align: center; width: 10%;">Rate</td>
		            <td style="font-size:12px; text-align: center; width: 8%;">Qty</td>
		            <td style="font-size:12px; text-align: center; width: 8%;">Per</td>
		            <td style="font-size:12px; text-align: center; width: 15%;">Amount</td>
		        </tr>';

		        $num     = 1;
		        $sub_tot = 0;
                $tot_gst = 0;
                $net_tot = 0;
                $tot_qty = 0;
		        foreach ($ord_det as $key => $val) {
		        	$description = !empty($val['description'])?$val['description']:'';
                    $hsn_code    = !empty($val['hsn_code'])?$val['hsn_code']:'';
                    $gst_value   = !empty($val['gst_val'])?$val['gst_val']:'0';
                    $unit_val    = !empty($val['unit_val'])?$val['unit_val']:'0';
                    $price       = !empty($val['price'])?$val['price']:'0';
                    $order_qty   = !empty($val['order_qty'])?$val['order_qty']:'0';

                    $gst_data  = $price - ($price * (100 / (100 + $gst_value)));
                    $price_val = $price - $gst_data;
                    $tot_price = $order_qty * $price_val;
                    $sub_tot  += $tot_price;

                    // GST Calculation
                    $gst_val   = $order_qty * $gst_data;
                    $tot_gst  += $gst_val;
                    $total_val = $order_qty * $price;
                    $net_tot  += $total_val;
                    $tot_qty  += $order_qty;

                    $html .= '
                    	<tr>
				            <td style="font-size:12px; width: 5%;">'.$num.'</td>
				            <td style="font-size:12px; width: 44%;">'.$description.'</td>
				            <td style="font-size:12px; text-align: center; width: 10%;">'.$hsn_code.'</td>
				            <td style="font-size:12px; text-align: center; width: 10%;">'.number_format((float)$price_val, 2, '.', '').'</td>
				            <td style="font-size:12px; text-align: center; width: 8%;">'.$order_qty.'</td>
				            <td style="font-size:12px; text-align: center; width: 8%;">nos</td>
				            <td style="font-size:12px; text-align: center; width: 15%;">'.number_format((float)$tot_price, 2, '.', '').'</td>
				        </tr>
                    ';

                    $num++;
		        }

		    $rowspan = '5';
		    if($vendor_state == $admin_state)
		    {
		    	$rowspan = '6';
		    }

		    // Round Val Details
            $net_value  = round($net_tot);
            $rond_total = $net_value - $net_tot;

		    $html .= '
	            <tr>
	                <td rowspan ="'.$rowspan.'"  colspan="4"></td>
	                <td colspan="2" style="font-size:12px; text-align: right;">Qty</td>
	                <td style="font-size:12px; text-align: right;"> '.$tot_qty.'</td>
	                
	            </tr>
	            <tr>
	                <td colspan="2" style="font-size:12px; text-align: right;">Sub Total</td>
	                <td style="font-size:12px; text-align: right;"> '.number_format((float)$sub_tot, 2, '.', '').'</td>
	            </tr>';
	            if($vendor_state == $admin_state)
	            {
	            	$gst_value = $tot_gst / 2;

	            	$html .= '
	            		<tr>
			                <td colspan="2" style="font-size:12px; text-align: right;">SGST</td>
			                <td style="font-size:12px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
			            </tr>
			            <tr>
			                <td colspan="2" style="font-size:12px; text-align: right;">CGST</td>
			                <td style="font-size:12px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
			            </tr>
	            	';
	            }
	            else
	            {
	            	$html .= '
	            		<tr>
			                <td colspan="2" style="font-size:12px; text-align: right;">IGST</td>
			                <td style="font-size:12px; text-align: right;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
			            </tr>
	            	';
	            }
	            $html .='<tr>
	                <td colspan="2" style="font-size:12px; text-align: right;">Round off</td>
	                <td style="font-size:12px; text-align: right;"> '.number_format((float)$rond_total, 2, '.', '').'</td>
	            </tr>
	            <tr>
	                <td colspan="2" style="font-size:12px; text-align: right;">Net Total</td>
	                <td style="font-size:12px; text-align: right;"> '.number_format((float)$net_value, 2, '.', '').'</td>
	            </tr>
	            <tr>
	                <td colspan="11" style="font-size:12px;" class="text-right">Amount (in words) : '.numberTowords($net_value).'  Rupees Only</td>
	            </tr>';
		    $html .='</table>';

		    $html .='<br><br>
			<table border= "1" cellpadding="1" top="100">
		        <tr>
		            <td rowspan="2" style="font-size:12px; width: 10%;">HSN</td>
		            <td rowspan="2" style="font-size:12px; width: 15%;">Taxable Value</td>';
		            if($vendor_state == $admin_state)
		            {
		            	$html .= '
		            		<td colspan="2" style="font-size:12px; width: 30%; text-align:center;">CGST</td>
		            		<td colspan="2" style="font-size:12px; width: 30%; text-align:center;">SGST</td>
		            	';
		            }
		            else
		            {
		            	$html .= '
		            		<td colspan="2" style="font-size:12px; width: 60%; text-align:center;">IGST</td>
		            	';
		            }
			            
			       $html .= '<td rowspan="2" style="font-size:12px; width: 15%;">Total Tax Amount</td>
		        </tr>
		        <tr>';
		        	if($vendor_state == $admin_state)
		        	{
		        		$html .= '
		        			<td style="font-size: 12px; text-align:center;">Rate</td>
				            <td style="font-size: 12px; text-align:center;">Amt</td>
				            <td style="font-size: 12px; text-align:center;">Rate</td>
				            <td style="font-size: 12px; text-align:center;">Amt</td>
		        		';
		        	}
		        	else
		        	{
		        		$html .= '
		        			<td style="font-size: 12px; text-align:center;">Rate</td>
		            		<td style="font-size: 12px; text-align:center;">Amt</td>
		        		';
		        	}
		        $html .='</tr>';
		        $tot_price = 0;
				$tot_gst   = 0;
		        foreach ($tax_det as $key => $value) {
		        	$hsn_code    = !empty($value['hsn_code'])?$value['hsn_code']:'';
                    $gst_val     = !empty($value['gst_val'])?$value['gst_val']:'0';
                    $gst_value   = !empty($value['gst_value'])?$value['gst_value']:'0';
                    $price_value = !empty($value['price_value'])?$value['price_value']:'0';

                    $tot_gst    += $gst_value;
		            $tot_price  += $price_value;

                    $html .= '
                    	<tr>
                    		<td style="font-size: 12px; text-align:left;"> '.$hsn_code.'</td>
                    		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$price_value, 2, '.', '').'</td>';
                    		if($vendor_state == $admin_state)
                    		{
                    			$state_value = $gst_value / 2;
		                        $gst_calc    = $gst_val / 2;
                    			$html .= '
                    				<td style="font-size: 12px; text-align:left;"> '.$gst_calc.' %</td>
		                    		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$state_value, 2, '.', '').'</td>
		                    		<td style="font-size: 12px; text-align:left;"> '.$gst_calc.' %</td>
		                    		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$state_value, 2, '.', '').'</td>
                    			';
                    		}
                    		else
                    		{
                    			$html .= '
                    				<td style="font-size: 12px; text-align:left;"> '.$gst_val.' %</td>
	                    			<td style="font-size: 12px; text-align:left;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
                    			';
                    		}
                    		$html .='<td style="font-size: 12px; text-align:left;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
                    	</tr>
                    ';
		        }
		        $html .= '
		        	<tr>
		        		<td style="font-size: 12px; text-align:right;"> Total </td>
		        		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$tot_price, 2, '.', '').'</td>';
		        		if($vendor_state == $admin_state)
		        		{
		        			$state_val = $tot_gst / 2;

		        			$html .= '
			        			<td style="font-size: 12px; text-align:left;"> </td>
				        		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$state_val, 2, '.', '').'</td>
				        		<td style="font-size: 12px; text-align:left;"> </td>
				        		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$state_val, 2, '.', '').'</td>
			        		';
		        		}
		        		else
		        		{
		        			$html .= '
			        			<td style="font-size: 12px; text-align:left;"> </td>
				        		<td style="font-size: 12px; text-align:left;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
			        		';
		        		}

		        		$html .='<td style="font-size: 12px; text-align:left;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
		        	</tr>
		        ';
		    $html .='</table>';

		    $html .='<br><br>
			<table border= "1" cellpadding="1" top="100">
		        <tr>
		            <td colspan="5" style="font-size:12px; width: 25%;">Account Name</td>
		            <td colspan="5" style="font-size:12px; width: 25%;"> '.$account_name.'</td>
		            <td rowspan="5" style="font-size:12px; width: 50%;">
		            	<span> for '.$company_name.'</span>
		            	<br><br><br>
		            	<p style="text-align: right; "> Authorised signature </p>
		            </td>
		        </tr>
		        <tr>
		        	<td colspan="5" style="font-size:12px; width: 25%;">Account No</td>
		            <td colspan="5" style="font-size:12px; width: 25%;"> '.$account_no.'</td>
		        </tr>
		        <tr>
		        	<td colspan="5" style="font-size:12px; width: 25%;">Bank Name</td>
		            <td colspan="5" style="font-size:12px; width: 25%;"> '.$bank_name.'</td>
		        </tr>
		        <tr>
		        	<td colspan="5" style="font-size:12px; width: 25%;">Branch Name</td>
		            <td colspan="5" style="font-size:12px; width: 25%;"> '.$branch_name.'</td>
		        </tr>
		        <tr>
		        	<td colspan="5" style="font-size:12px; width: 25%;">IFSC Code</td>
		            <td colspan="5" style="font-size:12px; width: 25%;"> '.$ifsc_code.'</td>
		        </tr>
		    </table>';

          	$pdf->writeHTML($html, true, false, true, false, '');
	       	$pdf->Output($company_name.'_'.$invoice_no.'_'.date('d-F-Y H:i:s').'.pdf', 'I');
		}

		public function add_purchase_return($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$vendor_id     = $this->input->post('vendor_id');
				$order_date    = $this->input->post('order_date');
				$product_id    = $this->input->post('product_id');
				$type_id       = $this->input->post('type_id');
				$product_qty   = $this->input->post('product_qty');
				$product_price = $this->input->post('product_price');
				$unit_id       = $this->input->post('unit_id');
				$method        = $this->input->post('method');

				$required = array('vendor_id', 'order_date');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if(count(array_filter($product_id))!==count($product_id) || count(array_filter($type_id))!==count($type_id) || count(array_filter($product_qty))!==count($product_qty) || $error == TRUE)
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
			    		if(userAccess('purchase-return-add'))
			    		{
			    			$purchase_type  = [];
				    		$purchase_count = count($product_id);

				    		for($j = 0; $j < $purchase_count; $j++)
				    		{
				    			$purchase_type[] = array(
				    				'product_id'    => $product_id[$j],
				    				'type_id'       => $type_id[$j],
				    				'product_price' => $product_price[$j],
				    				'product_qty'   => $product_qty[$j],
				    				'product_unit'  => $unit_id[$j],
				    			);
				    		}

				    		$purchase_value = json_encode($purchase_type);

				    		$data = array(
						    	'vendor_id'        => $vendor_id,
						    	'purchase_value'   => $purchase_value,
						    	'order_date'       => date('Y-m-d', strtotime($order_date)),
						    	'active_financial' => $this->session->userdata('active_year'),
						    	'method'           => '_addPurchaseReturn',
						    );

						    $data_save = avul_call(API_URL.'purchase/api/add_purchase_return',$data);

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
					        $response['message'] = 'Access denied'; 
					        $response['data']    = [];
					        echo json_encode($response);
					        return; 
			    		}
			    	}
			    }
			}

			else if($param1 =='getVendor_details')
			{
				$vendor_id = $this->input->post('vendor_id');

				$where = array(
            		'vendor_id' => $vendor_id,
            		'method'    => '_detailVendors'
            	);

            	$vendor_list   = avul_call(API_URL.'vendors/api/vendors',$where);
            	$vendor_result = $vendor_list['data'];

            	$option = array(
            		'gst_no'     => $vendor_result[0]['gst_no'],
            		'contact_no' => $vendor_result[0]['contact_no'],
            		'address'    => $vendor_result[0]['address'],
            	);

            	$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 
			}

			else if($param1 =='getVendor_products')
			{
				$vendor_id = $this->input->post('vendor_id');

				$where = array(
            		'vendor_id' => $vendor_id,
            		'method'    => '_listVendorProducts'
            	);

            	$vendor_list   = avul_call(API_URL.'catlog/api/product',$where);
            	$vendor_result = $vendor_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($vendor_result))
        		{
        			foreach ($vendor_result as $key => $value) {
        				$product_id   = !empty($value['product_id'])?$value['product_id']:'';
                        $product_name = !empty($value['product_name'])?$value['product_name']:'';

                        $option .= '<option value="'.$product_id.'">'.$product_name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return;
			}

			else if($param1 =='getVendor_invoice')
			{
				$vendor_id = $this->input->post('vendor_id');

				$where = array(
            		'vendor_id' => $vendor_id,
            		'method'    => '_listVendorPaymentBill'
            	);

            	$data_list  = avul_call(API_URL.'payment/api/vendor_payment',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$option ='<option value="">Select Value</option>';

        		if(!empty($data_value))
        		{
        			foreach ($data_value as $key => $value) {
        				$pay_id  = !empty($value['pay_id'])?$value['pay_id']:'';
                        $bill_no = !empty($value['bill_no'])?$value['bill_no']:'';
                        $bal_amt = !empty($value['bal_amt'])?$value['bal_amt']:'';

                        $option .= '<option value="'.$pay_id.'">'.$bill_no.' (Rs. '.$bal_amt.')</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return;
			}

			else if($param1 =='getPurchase_row')
			{	
				$vendor_id = $this->input->post('vendor_id');
				$rowCount  = $this->input->post('rowCount');
				$newCount  = $rowCount + 1;

				$where_1 = array(
					'vendor_id' => $vendor_id,
            		'method'    => '_listVendorProducts'
            	);

            	$product_list  = avul_call(API_URL.'catlog/api/product',$where_1);
            	$product_val   = $product_list['data'];

            	$where_2 = array(
            		'method'    => '_listUnit'
            	);

	            $unit_list  = avul_call(API_URL.'master/api/unit',$where_2);
	            $unit_val   = $unit_list['data'];

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
						                url: baseurl+"index.php/"+value+"/"+cntrl+"/"+func+"/getVendor_productType",
						                dataType: "json",
						            }).done(function (response)
						            {
						                if(response["status"] == 1)
						                {
						                    $row.find(".type_id").empty().html(response[\'data\']);
						                }
						            });
						        });
						    }

						    if($(".type_id").length)
						    {
						        $(".type_id").on("change",function(){

						        	var $row    = $(this).closest("tr");
						            var type_id = $(this).val();
						            var value   = $("#value").val();
						            var cntrl   = $("#cntrl").val();
						            var func    = $("#func").val();

						            $.ajax({
						                method: "POST",
						                data: {
						                    "type_id" : type_id,
						                },
						                url: baseurl+"index.php/"+value+"/"+cntrl+"/"+func+"/getProductType_details",
						                dataType: "json",
						            }).done(function (response)
						            {
						                if(response["status"] == 1)
						                {
						                    $row.find(".unit_id").empty().html(response[\'data\']);
                    						$row.find(".product_price").empty().val(response[\'price\']);
						                }
						            });
						        });
						    }

						</script>
                        <td data-te="'.$newCount.'" class="p-l-0 product_list" style="width: 30%;">
                            <select data-te="'.$newCount.'" name="product_id[]" id="product_id'.$newCount.'" class="form-control product_id'.$newCount.' product_id js-select2-multi">
                                <option value="">Select Product Name</option>';
                                if(!empty($product_val))
	                            {
	                            	foreach ($product_val as $key => $value) {
	                            		$product_id   = !empty($value['product_id'])?$value['product_id']:'';
	                                    $product_name = !empty($value['product_name'])?$value['product_name']:'';

	                                    $option .="<option value=".$product_id.">".$product_name."</option>";
	                            	}
	                            }
                            $option .=' </select> 
                        </td>
                        <td data-te="'.$newCount.'" class="p-l-0 product_list" style="width: 30%;">
                            <select data-te="'.$newCount.'" name="type_id[]" id="type_id'.$newCount.'" class="form-control type_id'.$newCount.' type_id js-select2-multi" >
                                <option value="">Select Product Name</option>
                            </select> 
                        </td>
                        <td class="p-l-0" style="width: 13%;">
                            <input type="text" data-te="'.$newCount.'" name="product_price[]" id="product_price'.$newCount.'" class="form-control product_price'.$newCount.' product_price int_value" placeholder="Price">
                        </td>
                        <td class="p-l-0" style="width: 13%;">
                            <input type="text" data-te="'.$newCount.'" name="product_qty[]" id="product_qty'.$newCount.'" class="form-control product_qty'.$newCount.' product_qty int_value" placeholder="Quantity">

                            <input type="hidden" data-te="'.$newCount.'" name="purchase_id[]" id="purchase_id'.$newCount.'" class="form-control purchase_id'.$newCount.' purchase_id" placeholder="Price">
                        </td>
                        <td class="p-l-0" style="width: 14%;">
                            <select data-te="'.$newCount.'" name="unit_id[]" id="unit_id'.$newCount.'" class="form-control unit_id'.$newCount.' unit_id js-select2-multi" >
                                <option value="">Select Unit Name</option>';
                                if(!empty($unit_val))
	                            {
	                            	foreach ($unit_val as $key => $value) {
	                            		$unit_id   = !empty($value['unit_id'])?$value['unit_id']:'';
	                                    $unit_name = !empty($value['unit_name'])?$value['unit_name']:'';

	                                    $option .="<option value=".$unit_id.">".$unit_name."</option>";
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

			else if($param1 =='getVendor_productType')
			{
				$product_id = $this->input->post('product_id');

				$where = array(
            		'product_id' => $product_id,
            		'method'     => '_listProductType'
            	);

            	$type_list   = avul_call(API_URL.'catlog/api/productType',$where);
            	$type_result = $type_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($type_result))
        		{
        			foreach ($type_result as $key => $value) {
        				$type_id     = !empty($value['type_id'])?$value['type_id']:'';
                        $description = !empty($value['description'])?$value['description']:'';

                        $option .= '<option value="'.$type_id.'">'.$description.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return;
			}

			else if($param1 =='getProductType_details')
			{
				$type_id = $this->input->post('type_id');

				$where_1 = array(
					'type_id' => $type_id,
            		'method'  => '_purchaseProductType',
            	);

            	$product_list  = avul_call(API_URL.'catlog/api/productType',$where_1);
            	$product_val   = $product_list['data'];

            	$unit_id   = !empty($product_val[0]['product_unit'])?$product_val[0]['product_unit']:'';
                $unit_name = !empty($product_val[0]['unit_name'])?$product_val[0]['unit_name']:'';
                $price     = !empty($product_val[0]['ven_price'])?$product_val[0]['ven_price']:'0';

                $option = '<option value="'.$unit_id.'">'.$unit_name.'</option>';

                $response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        $response['price']   = $price;
		        echo json_encode($response);
		        return;
			}

			else
			{
				if($param1 =='Edit')
				{
	            	$where = array(
				    	'purchase_id' => $param2,
				    	'method'      => '_listPurchaseDetails'
				    );

				    $data_val = avul_call(API_URL.'purchase/api/manage_purchase_details',$where);

					$page['dataval']     = $data_val['data'];
					$page['method']      = 'BTBM_X_U';
					$page['page_title']  = "Edit Purchase Return";
				}
				else
				{
					$page['dataval']     = '';
					$page['method']      = 'BTBM_X_C';
					$page['page_title']  = "Add Purchase Return";
				}

				$where_1 = array(
            		'method'    => '_listManufacturerVendors'
            	);

            	$vendor_list = avul_call(API_URL.'vendors/api/vendors',$where_1);

            	$where_2 = array(
            		'item_type'      => '1',
            		'salesagents_id' => '0',
            		'method'         => '_listProduct'
            	);

            	$product_list  = avul_call(API_URL.'catlog/api/product',$where_2);

            	$where_3 = array(
            		'method'    => '_listUnit'
            	);

            	$unit_list  = avul_call(API_URL.'master/api/unit',$where_3);
					
				$page['vendor_val']   = $vendor_list['data'];
				$page['product_val']  = $product_list['data'];
				$page['unit_val']     = $unit_list['data'];
				$page['main_heading'] = "Purchase";
				$page['sub_heading']  = "Purchase";
				$page['pre_title']    = "List Purchase";
				$page['page_access']  = userAccess('purchase-return-view');
				$page['pre_menu']     = "index.php/admin/purchase/list_purchase_return";
				$data['page_temp']    = $this->load->view('admin/purchase/add_purchase_return',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_purchase_return";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function list_purchase_return($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$order_id = $this->input->post('order_id');
        	$method   = $this->input->post('method');

        	if($method == 'changeOrder_process')
			{
				$order_data = array(
		    		'auto_id'  => $order_id,
					'progress' => '5',
					'method'   => '_changePurchaseReturn',
				);

				$data_save = avul_call(API_URL.'purchase/api/manage_purchase_return',$order_data);

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

			if($param1 == '')
			{
				$page['main_heading'] = "Purchase";
				$page['sub_heading']  = "Purchase";
				$page['page_title']   = "List Purchase Return";
				$page['pre_title']    = "Add Purchase Return";
				$page['page_access']  = userAccess('purchase-return-add');
				$page['pre_menu']     = "index.php/admin/purchase/add_purchase_return";
				$data['page_temp']    = $this->load->view('admin/purchase/list_purchase_return',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_purchase_return";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('purchase-return-view'))
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
	            		'financial_year'  => $this->session->userdata('active_year'),
	            		'method'          => '_listPurchaseReturnPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'purchase/api/manage_purchase_return',$where);
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
		            		$order_id      = !empty($value['order_id'])?$value['order_id']:'';
				            $order_no      = !empty($value['order_no'])?$value['order_no']:'';
				            $vendor_id     = !empty($value['vendor_id'])?$value['vendor_id']:'';
				            $vendor_name   = !empty($value['vendor_name'])?$value['vendor_name']:'';
				            $contact_no    = !empty($value['contact_no'])?$value['contact_no']:'';
				            $order_date    = !empty($value['order_date'])?$value['order_date']:'';
				            $order_status  = !empty($value['order_status'])?$value['order_status']:'';
				            $bill          = !empty($value['bill'])?$value['bill']:'';
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

			                // Order Status
			                $order_btn = '<a class="button_clr btn btn-success"><i class="la la-check-square-o"></i> </a>';
						    if($order_status == '1')
						    {
						        $order_view = '<span class="badge badge-success">Success</span>';
						        $order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="admin" data-cntrl="purchase" data-func="list_purchase_return" data-id="'.$order_id.'" data-method="changeOrder_process"><i class="ft-edit"></i> </a>';
						    }
						    else if($order_status == '2')
						    {
						        $order_view = '<span class="badge badge-warning">Process</span>';
						    }
						    else if($order_status == '3')
						    {
						        $order_view = '<span class="badge badge-primary">Packing</span>';
						    }
						    else if($order_status == '4')
						    {
						        $order_view = '<span class="badge badge-info">Delivery</span>';
						    }
						    else if($order_status == '5')
						    {
						        $order_view = '<span class="badge badge-success">Complete</span>';
						    }
						    else
						    {
						        $order_view = '<span class="badge badge-danger">Cancel</span>';
						    }

						    $edit = '';
				            $view = '';
				            if(userAccess('purchase-return-edit') == TRUE)
				            {
				            	$edit = $order_btn;
				            }
				            if(userAccess('purchase-return-view') == TRUE)
				            {
				            	$view = '<a href="'.BASE_URL.'index.php/admin/purchase/view_purchase_return/View/'.$order_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>';
				            }

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$order_no.'</td>
	                                <td class="line_height">'.mb_strimwidth($vendor_name, 0, 15, '...').'</td>
	                                <td class="line_height">'.$order_date.'</td>
	                                <td class="line_height">'.$order_view.'</td>';
	                                if(userAccess('purchase-return-edit') == TRUE || userAccess('purchase-return-view') == TRUE):
		                            	$table .= '<td>'.$edit.$view.'</td>';
		                        	endif;
	                            $table .='</tr>
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
		}

		public function view_purchase_return($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			if($param1 =='View')
			{
				$order_id = $param2;

				$where_1 = array(
			    	'order_id' => $order_id,
			    	'method'   => '_detailsPurchaseReturn'
			    );

			    $data_val = avul_call(API_URL.'purchase/api/manage_purchase_return',$where_1);

			    // Admin Details
				$where_2 = array(
			    	'user_id' => '1',
			    	'method'  => '_userDetails'
			    );

			    $admin_val = avul_call(API_URL.'user/api/profile_settings',$where_2);

			    $page['admin_data']    = $admin_val['data'];
			    $page['return_data']   = $data_val['data'];
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Purchase Return";
				$page['page_title']    = "Purchase Invoice";
				$page['pre_title']     = "Purchase";
				$page['page_access']   = userAccess('purchase-return-view');
				$page['pre_menu']      = "index.php/admin/purchase/list_purchase_return";
				$data['page_temp']     = $this->load->view('admin/purchase/view_purchase_return',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "list_purchase_return";
				$this->bassthaya->load_admin_form_template($data);
			}

			if($param1 == 'changeOrder_process')
    		{
    			$error = FALSE;
    			$order_id     = $this->input->post('order_id');
				$order_status = $this->input->post('order_status');
				$message      = $this->input->post('message');

				$required = array('order_id', 'order_status');
				if($order_status == 8)
				{
					array_push($required, 'message');
				}
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if($error == TRUE)
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
			    	$order_data = array(
			    		'auto_id'  => $order_id,
						'progress' => $order_status,
						'reason'   => $message,
						'method'   => '_changePurchaseReturn',
					);

					$data_save = avul_call(API_URL.'purchase/api/manage_purchase_return',$order_data);

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

		public function print_return($param1="", $param2="", $param3="")
		{
			$order_id = $param1;

			$where_1 = array(
		    	'order_id' => $order_id,
		    	'method'   => '_detailsPurchaseReturn'
		    );

		    $data_val    = avul_call(API_URL.'purchase/api/manage_purchase_return',$where_1);
		    $return_data = $data_val['data'];

		    // Admin Details
			$where_2 = array(
		    	'user_id' => '1',
		    	'method'  => '_userDetails'
		    );

		    $admin_val  = avul_call(API_URL.'user/api/profile_settings',$where_2);
		    $admin_data = $admin_val['data'];

		    $adm_username = !empty($admin_data['username'])?$admin_data['username']:'';
		    $adm_mobile   = !empty($admin_data['mobile'])?$admin_data['mobile']:'';
		    $adm_address  = !empty($admin_data['address'])?$admin_data['address']:'';
		    $adm_state_id = !empty($admin_data['state_id'])?$admin_data['state_id']:'';
		    $adm_city_id  = !empty($admin_data['city_id'])?$admin_data['city_id']:'';
		    $adm_gst_no   = !empty($admin_data['gst_no'])?$admin_data['gst_no']:'';

		    $vendor_details = !empty($return_data['vendor_details'])?$return_data['vendor_details']:'';
		    $order_details  = !empty($return_data['order_details'])?$return_data['order_details']:'';

		    $usr_order_id     = !empty($vendor_details['order_id'])?$vendor_details['order_id']:'';
		    $usr_order_no     = !empty($vendor_details['order_no'])?$vendor_details['order_no']:'';
		    $usr_return_no    = !empty($vendor_details['return_no'])?$vendor_details['return_no']:'';
		    $usr_order_status = !empty($vendor_details['order_status'])?$vendor_details['order_status']:'';
		    $usr_reason       = !empty($vendor_details['reason'])?$vendor_details['reason']:'';
		    $usr_ordered      = !empty($vendor_details['ordered'])?$vendor_details['ordered']:'---';
		    $usr_complete     = !empty($vendor_details['complete'])?$vendor_details['complete']:'---';
		    $usr_canceled     = !empty($vendor_details['canceled'])?$vendor_details['canceled']:'---';
		    $usr_vendor_name  = !empty($vendor_details['vendor_name'])?$vendor_details['vendor_name']:'';
		    $usr_contact_no   = !empty($vendor_details['contact_no'])?$vendor_details['contact_no']:'';
		    $usr_address      = !empty($vendor_details['address'])?$vendor_details['address']:'';
		    $usr_gst_no       = !empty($vendor_details['gst_no'])?$vendor_details['gst_no']:'';
		    $usr_state_id     = !empty($vendor_details['state_id'])?$vendor_details['state_id']:'';

		    $this->load->library('Pdf');
      		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'mm', array(216,356), TRUE, 'UTF-8', FALSE);
          	$pdf->SetTitle('Purchase Retuen Order');
          	$pdf->SetPrintHeader(false);
          	$pdf->SetPrintFooter(false);
          		
			$pdf->SetPrintHeader(false);
			$pdf->SetPrintFooter(false);

          	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
          	$pdf->SetFont('');
          	$pdf->AddPage('P');
          	$html = '';

          	$html .= '<p style="color:black; font-size:12px; text-align: center;"><strong style="font-size:18px; padding-bottom:1000px;">'.$adm_username.'</strong><br/>'.$adm_address.'<br>Ph :'.$adm_mobile.', GSTIN\UIN :'.$adm_gst_no.'<br><strong style="color:black; text-align:center; font-size:17px;"> PURCHASE RETURN</strong><br></p>';

          	$html .='<br><br><br>
				<table border= "1" cellpadding="1" top="100">
			        <tr>
			            <td rowspan="4" style="font-size:12px; width: 55%; margin-left:10px;">To: <br> '.$usr_vendor_name.'<br> '.$usr_address.'<br> Ph : '.$usr_contact_no.'<br> GSTIN\UIN : '.$usr_gst_no.'</td>
			            <td style="font-size:12px; width: 20%;"> Order No</td>
			            <td style="font-size:12px; width: 25%;">'.$usr_order_no.'</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Order Date</td>
			            <td style="font-size:12px; width: 25%;">'.date('d-M-Y', strtotime($usr_ordered)).'</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Due Days</td>
			            <td style="font-size:12px; width: 25%;"></td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Others</td>
			            <td style="font-size:12px; width: 25%;"></td>
			        </tr>
			    </table>
			    ';

			$html .='<br><br>
			<table border= "1" cellpadding="1" top="100">
		        <tr>
		            <td style="font-size:12px; width: 5%;">S.No</td>
		            <td style="font-size:12px; width: 44%;">Description</td>
		            <td style="font-size:12px; text-align: center; width: 10%;">HSN</td>
		            <td style="font-size:12px; text-align: center; width: 10%;">Rate</td>
		            <td style="font-size:12px; text-align: center; width: 8%;">Qty</td>
		            <td style="font-size:12px; text-align: center; width: 8%;">Per</td>
		            <td style="font-size:12px; text-align: center; width: 15%;">Amount</td>
		        </tr>';
		        $num     = 1;
                $sub_tot = 0;
                $tot_gst = 0;
                $net_tot = 0;
                $tot_qty = 0;
		        foreach ($order_details as $key => $val) {
		        	$description = !empty($val['description'])?$val['description']:'';
		            $gst_value   = !empty($val['gst_value'])?$val['gst_value']:'0';
		            $hsn_code    = !empty($val['hsn_code'])?$val['hsn_code']:'';
		            $pdt_price   = !empty($val['product_price'])?$val['product_price']:'0';
		            $pdt_qty     = !empty($val['product_qty'])?$val['product_qty']:'0';

		            $gst_data  = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
		            $price_val = $pdt_price - $gst_data;
		            $tot_price = $pdt_qty * $price_val;
		            $sub_tot  += $tot_price;
		            $tot_qty  += $pdt_qty;

		            // GST Calculation
		            $gst_val   = $pdt_qty * $gst_data;
		            $tot_gst  += $gst_val;
		            $total_val = $pdt_qty * $pdt_price;
		            $net_tot  += $total_val;

                    $html .= '
                    	<tr>
				            <td style="font-size:12px; width: 5%;">'.$num.'</td>
				            <td style="font-size:12px; width: 44%;">'.$description.'</td>
				            <td style="font-size:12px; text-align: center; width: 10%;">'.$hsn_code.'</td>
				            <td style="font-size:12px; text-align: center; width: 10%;">'.$pdt_price.'</td>
				            <td style="font-size:12px; text-align: center; width: 8%;">'.$pdt_qty.'</td>
				            <td style="font-size:12px; text-align: center; width: 8%;">nos</td>
				            <td style="font-size:12px; text-align: right; width: 15%;">'.number_format((float)$tot_price, 2, '.', '').'</td>
				        </tr>
                    ';
                    $num++;
                }

                // Round Val Details
                $net_value  = round($net_tot);
                $rond_total = $net_value - $net_tot;

                $html .= '
		            <tr>
		                <td rowspan ="6"  colspan="4"></td>
		                <td colspan="2" style="font-size:12px; text-align: right;">Qty</td>
		                <td style="font-size:12px; text-align: right;"> '.$tot_qty.'</td>
		            </tr>
		             <tr>
		                <td colspan="2" style="font-size:12px; text-align: right;">Sub Total</td>
		                <td style="font-size:12px; text-align: right;"> '.number_format((float)$sub_tot, 2, '.', '').'</td>
		            </tr>';
		            if($adm_state_id == $usr_state_id)
		            {
		            	$gst_value = $tot_gst / 2;

		            	$html .= '
		            		<tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">SGST</td>
				                <td style="font-size:12px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
				            </tr>
				            <tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">CGST</td>
				                <td style="font-size:12px; text-align: right;"> '.number_format((float)$gst_value, 2, '.', '').'</td>
				            </tr>
		            	';
		            }
		            else
		            {
		            	$html .= '
		            		<tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">IGST</td>
				                <td style="font-size:12px; text-align: right;"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
				            </tr>
		            	';
		            }

		            $html .='<tr>
		                <td colspan="2" style="font-size:12px; text-align: right;">Round off</td>
		                <td style="font-size:12px; text-align: right;"> '.number_format((float)$rond_total, 2, '.', '').'</td>
		            </tr>
		            <tr>
		                <td colspan="2" style="font-size:12px; text-align: right;">Net Total</td>
		                <td style="font-size:12px; text-align: right;"> '.number_format((float)$net_value, 2, '.', '').'</td>
		            </tr>';

		    $html .='</table>';

          	$pdf->writeHTML($html, true, false, true, false, '');
	       	$pdf->Output($usr_vendor_name.'_'.$usr_order_no.'_'.date('d-F-Y').'.pdf', 'I');
		}

		public function stock_entry($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');

			if($formpage == 'BTBM_X_P')
			{
				$error = FALSE;	
				$category_id = $this->input->post('category_id');
				$order_date  = $this->input->post('order_date');
				$stock_val   = $this->input->post('stock_val');
				$damage_val  = $this->input->post('damage_val');
				$expiry_val  = $this->input->post('expiry_val');
				$product_id  = $this->input->post('product_id');
				$type_id     = $this->input->post('type_id');

				$required = array('order_date');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if(count(array_filter($product_id))!==count($product_id) || count(array_filter($type_id))!==count($type_id) || $error == TRUE)
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
			    	if(userAccess('stock-entry-add'))
			    	{
			    		$stock_type  = [];
			    		$stock_count = count($type_id);

			    		for($j = 0; $j < $stock_count; $j++)
			    		{
			    			$stock_type[] = array(
			    				'stock_val'   => $stock_val[$j],
			    				'damage_val'  => $damage_val[$j],
			    				'expiry_val'  => $expiry_val[$j],
			    				'type_id'     => $type_id[$j],
			    				'product_id'  => $product_id[$j],
			    				'category_id' => $category_id[$j],
			    			);
			    		}

			    		$stock_value = json_encode($stock_type);

			    		$data = array(
					    	'stock_value'      => $stock_value,
					    	'order_date'       => date('Y-m-d', strtotime($order_date)),
					    	'active_financial' => $this->session->userdata('active_year'),
					    	'method'           => '_addStockEntry',
					    );

					    $data_save = avul_call(API_URL.'stock/api/add_stock', $data);

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
				        $response['message'] = 'Access denied'; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
			    	}
			    }
			}

			if($param1 == '_getProductList')
			{
				$category_id = $this->input->post('category_id');

				$error = FALSE;
			    $errors = array();
				$required = array('category_id');
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
			    	$category_val = implode(',', $category_id);

			    	// Product List
					$where_1  = array(
						'category_id' => $category_val,
						'method'      => '_listCategoryProducts'
					);

					$cat_list = avul_call(API_URL.'catlog/api/product', $where_1);
					$cat_val  = $cat_list['data'];

					if($cat_val)
					{
						$num  = 1;
						$html = '';
			    		foreach ($cat_val as $key => $val_1) {

			    			$type_id     = !empty($val_1['type_id'])?$val_1['type_id']:'';
			    			$product_id  = !empty($val_1['product_id'])?$val_1['product_id']:'';
			    			$category_id = !empty($val_1['category_id'])?$val_1['category_id']:'';
				            $description = !empty($val_1['description'])?$val_1['description']:'';

	    					$html .= '
	    						<tr>
	                                <td>'.$num.'</td>
	                                <td style="width: 30%;">'.$description.'</td>
	                                <td style="padding: .75rem;">
	                                	<input type="text" data-te="'.$num.'" name="stock_val[]" id="stock_val'.$num.'" class="form-control stock_val'.$num.' stock_val int_value" placeholder="Stock Value">
	                                </td>
	                                <td style="padding: .75rem;">
	                                	<input type="text" data-te="'.$num.'" name="damage_val[]" id="damage_val'.$num.'" class="form-control damage_val'.$num.' damage_val int_value" placeholder="Damage Value">
	                                </td>
	                                <td style="padding: .75rem;">
	                                	<input type="text" data-te="'.$num.'" name="expiry_val[]" id="expiry_val'.$num.'" class="form-control expiry_val'.$num.' expiry_val int_value" placeholder="Expiry Value">

	                                	<input type="hidden" data-te="'.$num.'" name="product_id[]" id="product_id'.$num.'" class="form-control product_id'.$num.' product_id" value="'.$product_id.'">

	                                	<input type="hidden" data-te="'.$num.'" name="type_id[]" id="type_id'.$num.'" class="form-control type_id'.$num.' type_id" value="'.$type_id.'">

	                                	<input type="hidden" data-te="'.$num.'" name="category_id[]" id="category_id'.$num.'" class="form-control category_id'.$num.' category_id" value="'.$category_id.'">
	                                </td>
	                                <td class="buttonlist p-l-0 text-center"><button type="button" name="remove" class="btn btn-danger btn-sm remove_item button_size m-t-6"><span class="ft-minus-square"></span></button></td>
	                            </tr>
	    					';

	    					$num++;
	    				}

	    				$response['status']    = 1;
				        $response['message']   = $cat_list['message']; 
				        $response['data']      = $html;
				        echo json_encode($response);
				        return;
					}
					else
			    	{
			    		$response['status']  = 0;
				        $response['message'] = $cat_val['message']; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return;
			    	}
			    }
			}

			else
			{
				$page['dataval']    = '';
				$page['method']     = 'BTBM_X_C';
				$page['page_title'] = "Stock Entry";

				// Product List
				$where_1  = array(
					'method' => '_listCategory'
				);

				$pdt_list = avul_call(API_URL.'catlog/api/category', $where_1);
				$pdt_val  = $pdt_list['data'];

				$page['product_val']  = $pdt_val;
				$page['main_heading'] = "Stock Entry";
				$page['sub_heading']  = "Stock Entry";
				$page['pre_title']    = "List Stock Entry";
				$page['pre_menu']     = "index.php/admin/purchase/list_purchase_return";
				$page['page_access']  = userAccess('stock-entry-view');
				$data['page_temp']    = $this->load->view('admin/purchase/stock_entry',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "stock_entry";
				$this->bassthaya->load_admin_form_template($data);
			}
		}

		public function manage_stock($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	$order_id = $this->input->post('order_id');
        	$method   = $this->input->post('method');

			if($param1 == '')
			{
				$page['main_heading'] = "Purchase";
				$page['sub_heading']  = "Purchase";
				$page['page_title']   = "List Stock";
				$page['pre_title']    = "Add Stock";
				$page['page_access']  = userAccess('stock-entry-add');
				$page['pre_menu']     = "index.php/admin/purchase/stock_entry";
				$data['page_temp']    = $this->load->view('admin/purchase/manage_stock',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "manage_stock";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('stock-entry-view'))
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
	            		'financial_year'  => $this->session->userdata('active_year'),
	            		'method'          => '_listProductStockPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'stock/api/manage_stock',$where);
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
		            		$stock_id      = !empty($value['stock_id'])?$value['stock_id']:'';
				            $stock_no      = !empty($value['stock_no'])?$value['stock_no']:'';
				            $order_date    = !empty($value['order_date'])?$value['order_date']:'';
				            $active_status = !empty($value['status'])?$value['status']:'1';

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
	                                <td class="line_height">'.$stock_no.'</td>
	                                <td class="line_height">'.$order_date.'</td>
	                                <td class="line_height">'.$status_view.'</td>
	                                <td>
	                                	<a href="'.BASE_URL.'index.php/admin/purchase/manage_stock/View/'.$stock_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View</a>
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

			else if($param1 == 'View')
			{
				$where = array(
            		'stock_id' => $param2,
            		'method'   => '_viewProductStock'
            	);

            	$data_list  = avul_call(API_URL.'stock/api/manage_stock',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['stock_value']   = $data_value;
				$page['main_heading']  = "Purchase";
				$page['sub_heading']   = "Manage Stock Entry";
				$page['page_title']    = "Stock Entry";
				$page['pre_title']     = "Stock";
				$page['pre_menu']      = "index.php/admin/purchase/manage_stock";
				$data['page_temp']     = $this->load->view('admin/purchase/view_stock',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "manage_stock";
				$this->bassthaya->load_admin_form_template($data);
			}
		}
	}
?>
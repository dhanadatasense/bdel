<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Order extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
			$this->load->library("Pdf");
		}

		public function create_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

			$formpage = $this->input->post('formpage');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$outlet_id     = $this->input->post('outlet_id');
				$bill_type     = $this->input->post('bill_type');
				$discount      = $this->input->post('discount');
				$due_days      = $this->input->post('due_days');
				$product_id    = $this->input->post('product_id');
				$type_id       = $this->input->post('type_id');
				$product_price = $this->input->post('product_price');
				$product_qty   = $this->input->post('product_qty');
				$unit_id       = $this->input->post('unit_id');

				$required = array('outlet_id', 'bill_type');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if(count(array_filter($product_id))!==count($product_id) || count(array_filter($type_id))!==count($type_id) || count(array_filter($product_price))!==count($product_price) || count(array_filter($product_qty))!==count($product_qty) || count(array_filter($unit_id))!==count($unit_id) || $error == TRUE)
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
			    	if(userAccess('sales-order-add'))
			    	{
			    		$order_type  = [];
			    		$order_count = count($product_id);

			    		for($j = 0; $j < $order_count; $j++)
			    		{
			    			$order_type[] = array(
			    				'product_id' => $product_id[$j],
			    				'type_id'    => $type_id[$j],
			    				'unit_val'   => $unit_id[$j],
			    				'price'      => $product_price[$j],
			    				'qty'        => $product_qty[$j],
			    			);
			    		}

			    		$order_value = json_encode($order_type);

			    		$data = array(
					    	'store_id'    => $outlet_id,
					    	'sales_order' => $order_value,
					    	'order_type'  => 2,
					    	'bill_type'   => $bill_type,
					    	'discount'    => $discount,
					    	'due_days'    => $due_days,
					    	'method'      => '_addSalesOrder',
					    );

			    		$data_save = avul_call(API_URL.'order/api/create_order',$data);

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

			else if($param1 =='getCity_name')
			{
				$state_id = $this->input->post('state_id');

				$where = array(
            		'state_id' => $state_id,
            		'method'   => '_listCity'
            	);

            	$city_list   = avul_call(API_URL.'master/api/city',$where);
            	$city_result = $city_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($city_result))
        		{
        			foreach ($city_result as $key => $value) {
        				$city_id   = !empty($value['city_id'])?$value['city_id']:'';
                        $city_name = !empty($value['city_name'])?$value['city_name']:'';

                        $option .= '<option value="'.$city_id.'">'.$city_name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
			}

			else if($param1 =='getZone_name')
			{
				$state_id = $this->input->post('state_id');
				$city_id  = $this->input->post('city_id');

				$where = array(
            		'state_id' => $state_id,
            		'city_id'  => $city_id,
            		'method'   => '_listZone'
            	);

            	$zone_list   = avul_call(API_URL.'master/api/zone',$where);
            	$zone_result = $zone_list['data'];

        		$option ='<option value="">Select Value</option>';

        		if(!empty($zone_result))
        		{
        			foreach ($zone_result as $key => $value) {
        				$zone_id   = !empty($value['zone_id'])?$value['zone_id']:'';
                        $zone_name = !empty($value['zone_name'])?$value['zone_name']:'';

                        $option .= '<option value="'.$zone_id.'">'.$zone_name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
			}   

			else if($param1 =='getOutlet_name')
			{
				$state_id = $this->input->post('state_id');
				$city_id  = $this->input->post('city_id');
				$zone_id  = $this->input->post('zone_id');

				$where = array(
            		'state_id' => $state_id,
            		'city_id'  => $city_id,
            		'zone_id'  => $zone_id,
            		'method'   => '_zoneWiseOutlets'
            	);

            	$outlet_list   = avul_call(API_URL.'outlets/api/outlets',$where);
            	$outlet_result = $outlet_list['data'];

            	$option ='<option value="">Select Value</option>';

        		if(!empty($outlet_result))
        		{
        			foreach ($outlet_result as $key => $value) {
        				$outlets_id   = !empty($value['outlets_id'])?$value['outlets_id']:'';
                        $company_name = !empty($value['company_name'])?$value['company_name']:'';

                        $option .= '<option value="'.$outlets_id.'">'.$company_name.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
			}   

			else if($param1 =='getOutlet_details')
			{
				$outlet_id  = $this->input->post('outlet_id');

				$where = array(
            		'outlets_id' => $outlet_id,
            		'method'     => '_detailOutlets'
            	);

            	$outlet_list   = avul_call(API_URL.'outlets/api/outlets',$where);
            	$outlet_result = $outlet_list['data'];

            	$option = array(
            		'available_limit' => $outlet_result[0]['available_limit'],
            		'address'         => $outlet_result[0]['address'],
            		'discount'        => $outlet_result[0]['discount'],
            		'due_days'        => $outlet_result[0]['due_days'],
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

			else if($param1 =='getVendor_productType')
			{
				$product_id = $this->input->post('product_id');
				$zone_id    = $this->input->post('zone_id');
				$order_type = $this->input->post('order_type');

				$where = array(
            		'product_id' => $product_id,
            		'zone_id'    => $zone_id,
            		'order_type' => $order_type,
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

			else if($param1 =='getOrderProduct_details')
			{
				$product_id = $this->input->post('product_id');

				$where_1 = array(
					'product_id' => $product_id,
            		'method'     => '_detailProduct',
            	);

            	$product_list  = avul_call(API_URL.'catlog/api/product',$where_1);
            	$product_val   = $product_list['data'];

            	$hsn_code = !empty($product_val[0]['hsn_code'])?$product_val[0]['hsn_code']:'';
            	$gst_val  = !empty($product_val[0]['gst'])?$product_val[0]['gst']:'';

                $response['status']   = 1;
		        $response['message']  = 'success'; 
		        $response['hsn_code'] = $hsn_code;
		        $response['gst_val']  = $gst_val;
		        echo json_encode($response);
		        return;
			}

			else if($param1 =='getOrder_row')
			{
				$rowCount  = $this->input->post('rowCount');
				$newCount  = $rowCount + 1;

				$where_1 = array(
            		'method' => '_listProduct'
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
						            var product_id = $(this).val();
						            var auto_id    = $(this).attr("data-te");
						            var value      = $("#value").val();
						            var cntrl      = $("#cntrl").val();
						            var func       = $("#func").val();
						            var zone_id    = $("#zone_id").val();

						            $.ajax({
						                method: "POST",
						                data: {
						                    "product_id" : product_id,
						                    "zone_id"    : zone_id,
                            				"order_type" : 1,
						                },
						                url: baseurl+"index.php/"+value+"/"+cntrl+"/"+func+"/getVendor_productType",
						                dataType: "json",
						            }).done(function (response)
						            {
						                if(response["status"] == 1)
						                {
						                    $(".type_id"+auto_id).empty("").html(response["data"]);
						                }
						            });
						        });
						    }

						    if($(".type_id").length)
						    {
						        $(".type_id").on("change",function(){
						            var type_id   = $(this).val();
						            var auto_id   = $(this).attr("data-te");
						            var state_id  = $("#state_id").val();
						            var city_id   = $("#city_id").val();
						            var zone_id   = $("#zone_id").val();
						            var outlet_id = $("#outlet_id").val();
						            var value     = $("#value").val();
						            var cntrl     = $("#cntrl").val();
						            var func      = $("#func").val();

						            $.ajax({
						                method: "POST",
						                data: {
						                	"state_id"  : state_id,
						                    "city_id"   : city_id,
						                    "zone_id"   : zone_id,
						                	"outlet_id" : outlet_id,
						                    "type_id"   : type_id,
						                },
						                url: baseurl+"index.php/"+value+"/"+cntrl+"/"+func+"/getProductType_details",
						                dataType: "json",
						            }).done(function (response)
						            {
						                if(response["status"] == 1)
						                {
						                    $(".unit_id"+auto_id).empty().html(response["data"]);
                    						$(".product_price"+auto_id).empty().val(response["price"]);
                    						$(".pack_qty"+auto_id).empty().val(response["pack_cnt"]);
                    						$(".stock_check"+auto_id).empty().val(response["stock_check"]);
						                }
						            });
						        });
						    }

						    if($(".product_val").length)
						    {
						        $(".product_val").on("change",function(){
						            var auto_id    = $(this).attr("data-te");
						            var product_id = $(this).val();
						            var value      = $("#value").val();
						            var cntrl      = $("#cntrl").val();
						            var func       = $("#func").val();

						            $.ajax({
						                method: "POST",
						                data: {
						                    "product_id" : product_id,
						                },
						                url: baseurl+"index.php/"+value+"/"+cntrl+"/"+func+"/getOrderProduct_details",
						                dataType: "json",
						            }).done(function (response)
						            {
						                if(response["status"] == 1)
						                {
						                    $(".hsn_code"+auto_id).empty().val(response["hsn_code"]);
						                    $(".gst_val"+auto_id).empty().val(response["gst_val"]);
						                }
						            });
						        }); 
						    }

						</script>

						<td data-te="'.$newCount.'" class="p-l-0 product_list" style="width: 30%;">
							<select data-te="'.$newCount.'" name="product_id[]" id="product_id'.$newCount.'" class="form-control product_id'.$newCount.' product_id product_val js-select2-multi" >
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

						<td data-te="'.$newCount.'" class="p-l-0 type_list" style="width: 25%;">
                            <select data-te="'.$newCount.'" name="type_id[]" id="type_id'.$newCount.'" class="form-control type_id'.$newCount.' type_id js-select2-multi" >
                                <option value="">Select Type Name</option>
                            </select> 
                        </td>

						<td class="p-l-0" style="width: 15%;">
                            <input type="text" data-te="'.$newCount.'" id="product_price'.$newCount.'" class="form-control product_price'.$newCount.' product_price int_value" placeholder="Price" disabled style="background-color: #fff !important;">
                        </td>

                        <td class="p-l-0" style="width: 15%;">
                        	<input type="text" data-te="'.$newCount.'" name="product_qty[]" id="product_qty'.$newCount.'" class="form-control product_qty'.$newCount.' product_qty int_value" placeholder="Quantity">

                        	<input type="hidden" data-te="'.$newCount.'" name="product_price[]" id="product_price'.$newCount.'" class="form-control product_price'.$newCount.' product_price int_value" placeholder="Price">

                        	<input type="hidden" data-te="'.$newCount.'" name="pack_qty[]" id="pack_qty'.$newCount.'" class="form-control pack_qty'.$newCount.' pack_qty int_value" placeholder="Price">

                        	<input type="hidden" data-te="'.$newCount.'" name="stock_check[]" id="stock_check'.$newCount.'" class="form-control stock_check'.$newCount.' stock_check int_value" placeholder="Price">

                            <input type="hidden" data-te="'.$newCount.'" name="hsn_code[]" id="hsn_code'.$newCount.'" class="form-control hsn_code'.$newCount.' hsn_code" placeholder="Quantity">

                            <input type="hidden" data-te="'.$newCount.'" name="gst_val[]" id="gst_val'.$newCount.'" class="form-control gst_val'.$newCount.' gst_val" placeholder="Quantity">

                        </td>

                        <td class="p-l-0" style="width: 15%;">
                            <select data-te="'.$newCount.'" name="unit_id[]" id="unit_id'.$newCount.'" class="form-control unit_id'.$newCount.' unit_id js-select2-multi"  >
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
                            <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6 remove_item"><span class="ft-minus-square"></span></button>
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

			else if($param1 =='getProductType_details')
			{
				$state_id  = $this->input->post('state_id');
				$city_id   = $this->input->post('city_id');
				$zone_id   = $this->input->post('zone_id');
				$outlet_id = $this->input->post('outlet_id');
				$type_id   = $this->input->post('type_id');

				$where_1 = array(
					'state_id'  => $state_id,
					'city_id'   => $city_id,
					'zone_id'   => $zone_id,
					'outlet_id' => $outlet_id,
					'type_id'   => $type_id,
					'view_type' => '1',
            		'method'    => '_detailProductType',
            	);

            	$product_list  = avul_call(API_URL.'catlog/api/productType',$where_1);
            	$product_val   = $product_list['data'];

            	$unit_id     = !empty($product_val[0]['product_unit'])?$product_val[0]['product_unit']:'';
                $unit_name   = !empty($product_val[0]['unit_name'])?$product_val[0]['unit_name']:'';
                $price       = !empty($product_val[0]['product_price'])?$product_val[0]['product_price']:'0';
                $pack_cnt    = !empty($product_val[0]['pdt_pack_cnt'])?$product_val[0]['pdt_pack_cnt']:'0';
                $stock_check = !empty($product_val[0]['stock_check'])?$product_val[0]['stock_check']:'0';

                $option = '<option value="'.$unit_id.'">'.$unit_name.'</option>';

                $response['status']      = 1;
		        $response['message']     = 'success'; 
		        $response['data']        = $option;
		        $response['price']       = $price;
		        $response['pack_cnt']    = $pack_cnt;
		        $response['stock_check'] = $stock_check;
		        echo json_encode($response);
		        return;
			}	

			else if($param1 =='_qtyCheck')
			{
				$_qty      = $this->input->post('_qty');
				$_packQty  = $this->input->post('_packQty');
				$_stockChk = $this->input->post('_stockChk');
				$status    = 0;

				if($_stockChk == 1)
				{
					if($_qty > $_packQty)
					{
						$status = 1;
					}
				}

				$response['status']   = $status;
		        $response['message']  = 'success'; 
		        echo json_encode($response);
		        return;
			}		

        	else
			{
				$where_1 = array(
            		'method'   => '_listState'
            	);

            	$state_list  = avul_call(API_URL.'master/api/state',$where_1);

            	$where_2 = array(
            		'method' => '_listProduct'
            	);

            	$product_list = avul_call(API_URL.'catlog/api/product',$where_2);

            	$where_3 = array(
            		'method'    => '_listUnit'
            	);

            	$unit_list  = avul_call(API_URL.'master/api/unit',$where_3);

            	$page['state_val']     = $state_list['data'];
            	$page['product_val']   = $product_list['data'];
            	$page['unit_val']      = $unit_list['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Sales Order";
				$page['pre_title']     = "";
				$page['method']        = "BTBM_X_C";
				$page['load_data']     = "";
				$page['function_name'] = "create_order";
				$page['pre_menu']      = "index.php/admin/order/create_order";
				$data['page_temp']     = $this->load->view('admin/order/create_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "create_order";
				$this->bassthaya->load_admin_form_template($data);
			}
		}		

		public function overall_order($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Overall Order";
				$page['pre_title']     = "";
				$page['load_data']     = "";
				$page['function_name'] = "overall_order";
				$page['pre_menu']      = "index.php/admin/order/list_order";
				$data['page_temp']     = $this->load->view('admin/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "overall_order";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('outlet-orders-view'))
				{
					$load_data = $this->input->post('load_data');
					$limit     = $this->input->post('limitval');
	            	$page      = $this->input->post('page');
	            	$search    = $this->input->post('search');
	            	$cur_page  = isset($page)?$page:'1';
	            	$_offset   = ($cur_page-1) * $limit;
	            	$nxt_page  = $cur_page + 1;
	            	$pre_page  = $cur_page - 1;

	            	$where = array(
	            		'offset'         => $_offset,
	            		'limit'          => $limit,
	            		'search'         => $search,
	            		'load_data'      => $load_data,
	            		'financial_year' => $this->session->userdata('active_year'),
	            		'method'         => '_listOrderPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'order/api/manage_order',$where);
	            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

	            	if(!empty($data_value))
	            	{	
	            		$count    = count($data_value);
		            	$total    = isset($data_list['total_record'])?$data_list['total_record']:'';
		            	$tot_page = ceil($total / $limit); 

	            		$status  = 1;
		            	$message = 'Success';
		            	$table   = '';

	            		$i = 1;
	            		foreach ($data_value as $key => $value) {
	            			$order_id     = !empty($value['order_id'])?$value['order_id']:'';
				            $order_no     = !empty($value['order_no'])?$value['order_no']:'';
				            $emp_name     = !empty($value['emp_name'])?$value['emp_name']:'';
				            $store_name   = !empty($value['store_name'])?$value['store_name']:'';
				            $contact_name = !empty($value['contact_name'])?$value['contact_name']:'';
				            $order_status = !empty($value['order_status'])?$value['order_status']:'';
				            $_ordered     = !empty($value['_ordered'])?$value['_ordered']:'';
				            $_processing  = !empty($value['_processing'])?$value['_processing']:'';
				            $_shiped      = !empty($value['_shiped'])?$value['_shiped']:'';
				            $_canceled    = !empty($value['_canceled'])?$value['_canceled']:'';
				            $_delivery    = !empty($value['_delivery'])?$value['_delivery']:'';
				            $random_value = !empty($value['random_value'])?$value['random_value']:'';
				            $published    = !empty($value['published'])?$value['published']:'';
				            $status       = !empty($value['status'])?$value['status']:'';
				            $createdate   = !empty($value['createdate'])?$value['createdate']:'';

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
						        $order_view = '<span class="badge badge-info">Shipping</span>';
						    }
						    else if($order_status == '5')
						    {
						        $order_view = '<span class="badge badge-warning">Invoice</span>';
						    }
						    else if($order_status == '6')
						    {
						        $order_view = '<span class="badge badge-success">Delivery</span>';
						    }
						    else if($order_status == '7')
						    {
						        $order_view = '<span class="badge badge-success">Complete</span>';
						    }
						    else
						    {
						        $order_view = '<span class="badge badge-danger">Cancel</span>';
						    }

			                $table .= '
						    	<tr>
	                                <td>'.$order_no.'</td>
			                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
			                        <td>'.mb_strimwidth($emp_name, '0', '12', '...').'</td>
	                                <td>'.mb_strimwidth($store_name, '0', '23', '...').'</td>
			                        <td>'.$order_view.'</td>
			                        <td><a href="'.BASE_URL.'index.php/admin/order/overall_order/view/'.$random_value.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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

			else if($param1 == 'view')
			{
				$random_value = $param2;

				$where = array(
			    	'random_value' => $random_value,
			    	'method'       => '_orderDetails'
			    );

				$data_val = avul_call(API_URL.'order/api/manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Overall Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/admin/order/overall_order";
				$data['page_temp']     = $this->load->view('admin/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "overall_order";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'stock_list')
			{
				$auto_id  = $param2;
				$order_id = $param3;

				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Overall Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/admin/order/overall_order";
				$data['page_temp']     = $this->load->view('admin/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "overall_order";
				$this->bassthaya->load_admin_form_template($data);
			}
    	}

    	public function success_order($param1="", $param2="", $param3="")
    	{
    		if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Success Order";
				$page['pre_title']     = "";
				$page['load_data']     = "1";
				$page['function_name'] = "success_order";
				$page['pre_menu']      = "index.php/admin/order/list_order";
				$data['page_temp']     = $this->load->view('admin/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "success_order";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('outlet-orders-view'))
				{
					$load_data = $this->input->post('load_data');
					$limit     = $this->input->post('limitval');
	            	$page      = $this->input->post('page');
	            	$search    = $this->input->post('search');
	            	$cur_page  = isset($page)?$page:'1';
	            	$_offset   = ($cur_page-1) * $limit;
	            	$nxt_page  = $cur_page + 1;
	            	$pre_page  = $cur_page - 1;

	            	$where = array(
	            		'offset'         => $_offset,
	            		'limit'          => $limit,
	            		'search'         => $search,
	            		'load_data'      => $load_data,
	            		'financial_year' => $this->session->userdata('active_year'),
	            		'method'         => '_listOrderPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'order/api/manage_order',$where);
	            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

	            	if(!empty($data_value))
	            	{
	            		$count    = count($data_value);
		            	$total    = isset($data_list['total_record'])?$data_list['total_record']:'';
		            	$tot_page = ceil($total / $limit); 

	            		$status  = 1;
		            	$message = 'Success';
		            	$table   = '';

	            		$i = 1;
	            		foreach ($data_value as $key => $value) {
	            			$order_id     = !empty($value['order_id'])?$value['order_id']:'';
				            $order_no     = !empty($value['order_no'])?$value['order_no']:'';
				            $emp_name     = !empty($value['emp_name'])?$value['emp_name']:'';
				            $store_name   = !empty($value['store_name'])?$value['store_name']:'';
				            $contact_name = !empty($value['contact_name'])?$value['contact_name']:'';
				            $order_status = !empty($value['order_status'])?$value['order_status']:'';
				            $_ordered     = !empty($value['_ordered'])?$value['_ordered']:'';
				            $_processing  = !empty($value['_processing'])?$value['_processing']:'';
				            $_shiped      = !empty($value['_shiped'])?$value['_shiped']:'';
				            $_canceled    = !empty($value['_canceled'])?$value['_canceled']:'';
				            $_delivery    = !empty($value['_delivery'])?$value['_delivery']:'';
				            $random_value = !empty($value['random_value'])?$value['random_value']:'';
				            $published    = !empty($value['published'])?$value['published']:'';
				            $status       = !empty($value['status'])?$value['status']:'';
				            $createdate   = !empty($value['createdate'])?$value['createdate']:'';

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
						        $order_view = '<span class="badge badge-info">Shipping</span>';
						    }
						    else if($order_status == '5')
						    {
						        $order_view = '<span class="badge badge-warning">Invoice</span>';
						    }
						    else if($order_status == '6')
						    {
						        $order_view = '<span class="badge badge-success">Delivery</span>';
						    }
						    else if($order_status == '7')
						    {
						        $order_view = '<span class="badge badge-success">Complete</span>';
						    }
						    else
						    {
						        $order_view = '<span class="badge badge-danger">Cancel</span>';
						    }

			                $table .= '
						    	<tr>
	                                <td>'.$order_no.'</td>
			                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
			                        <td>'.mb_strimwidth($emp_name, '0', '12', '...').'</td>
	                                <td>'.mb_strimwidth($store_name, '0', '23', '...').'</td>
			                        <td>'.$order_view.'</td>
			                        <td><a href="'.BASE_URL.'index.php/admin/order/success_order/view/'.$random_value.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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

			else if($param1 == 'view')
			{
				$random_value = $param2;

				$where = array(
			    	'random_value' => $random_value,
			    	'method'       => '_orderDetails'
			    );

				$data_val = avul_call(API_URL.'order/api/manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Success Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/admin/order/success_order";
				$data['page_temp']     = $this->load->view('admin/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "success_order";
				$this->bassthaya->load_admin_form_template($data);
			}
    	}

    	public function process_order($param1="", $param2="", $param3="")
    	{
    		if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Approved Order";
				$page['pre_title']     = "";
				$page['load_data']     = "2";
				$page['function_name'] = "process_order";
				$page['pre_menu']      = "index.php/admin/order/list_order";
				$data['page_temp']     = $this->load->view('admin/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "process_order";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('outlet-orders-view'))
				{
					$load_data = $this->input->post('load_data');
					$limit     = $this->input->post('limitval');
	            	$page      = $this->input->post('page');
	            	$search    = $this->input->post('search');
	            	$cur_page  = isset($page)?$page:'1';
	            	$_offset   = ($cur_page-1) * $limit;
	            	$nxt_page  = $cur_page + 1;
	            	$pre_page  = $cur_page - 1;

	            	$where = array(
	            		'offset'         => $_offset,
	            		'limit'          => $limit,
	            		'search'         => $search,
	            		'load_data'      => $load_data,
	            		'financial_year' => $this->session->userdata('active_year'),
	            		'method'         => '_listOrderPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'order/api/manage_order',$where);
	            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

	            	if(!empty($data_value))
	            	{	
	            		$count    = count($data_value);
		            	$total    = isset($data_list['total_record'])?$data_list['total_record']:'';
		            	$tot_page = ceil($total / $limit); 

	            		$status  = 1;
		            	$message = 'Success';
		            	$table   = '';

	            		$i = 1;
	            		foreach ($data_value as $key => $value) {
	            			$order_id     = !empty($value['order_id'])?$value['order_id']:'';
				            $order_no     = !empty($value['order_no'])?$value['order_no']:'';
				            $emp_name     = !empty($value['emp_name'])?$value['emp_name']:'';
				            $store_name   = !empty($value['store_name'])?$value['store_name']:'';
				            $contact_name = !empty($value['contact_name'])?$value['contact_name']:'';
				            $order_status = !empty($value['order_status'])?$value['order_status']:'';
				            $_ordered     = !empty($value['_ordered'])?$value['_ordered']:'';
				            $_processing  = !empty($value['_processing'])?$value['_processing']:'';
				            $_shiped      = !empty($value['_shiped'])?$value['_shiped']:'';
				            $_canceled    = !empty($value['_canceled'])?$value['_canceled']:'';
				            $_delivery    = !empty($value['_delivery'])?$value['_delivery']:'';
				            $random_value = !empty($value['random_value'])?$value['random_value']:'';
				            $published    = !empty($value['published'])?$value['published']:'';
				            $status       = !empty($value['status'])?$value['status']:'';
				            $createdate   = !empty($value['createdate'])?$value['createdate']:'';

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
						        $order_view = '<span class="badge badge-info">Shipping</span>';
						    }
						    else if($order_status == '5')
						    {
						        $order_view = '<span class="badge badge-warning">Invoice</span>';
						    }
						    else if($order_status == '6')
						    {
						        $order_view = '<span class="badge badge-success">Delivery</span>';
						    }
						    else if($order_status == '7')
						    {
						        $order_view = '<span class="badge badge-success">Complete</span>';
						    }
						    else
						    {
						        $order_view = '<span class="badge badge-danger">Cancel</span>';
						    }

			                $table .= '
						    	<tr>
	                                <td>'.$order_no.'</td>
			                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
			                        <td>'.mb_strimwidth($emp_name, '0', '12', '...').'</td>
	                                <td>'.mb_strimwidth($store_name, '0', '23', '...').'</td>
			                        <td>'.$order_view.'</td>
			                        <td><a href="'.BASE_URL.'index.php/admin/order/process_order/view/'.$random_value.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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

			else if($param1 == 'view')
			{
				$random_value = $param2;

				$where = array(
			    	'random_value' => $random_value,
			    	'method'       => '_orderDetails'
			    );

				$data_val = avul_call(API_URL.'order/api/manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Process Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/admin/order/process_order";
				$data['page_temp']     = $this->load->view('admin/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "process_order";
				$this->bassthaya->load_admin_form_template($data);
			}
    	}

    	public function complete_order($param1="", $param2="", $param3="")
    	{
    		if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Complete Order";
				$page['pre_title']     = "";
				$page['load_data']     = "7";
				$page['function_name'] = "complete_order";
				$page['pre_menu']      = "index.php/admin/order/list_order";
				$data['page_temp']     = $this->load->view('admin/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "complete_order";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('outlet-orders-view'))
				{
					$load_data = $this->input->post('load_data');
					$limit     = $this->input->post('limitval');
	            	$page      = $this->input->post('page');
	            	$search    = $this->input->post('search');
	            	$cur_page  = isset($page)?$page:'1';
	            	$_offset   = ($cur_page-1) * $limit;
	            	$nxt_page  = $cur_page + 1;
	            	$pre_page  = $cur_page - 1;

	            	$where = array(
	            		'offset'         => $_offset,
	            		'limit'          => $limit,
	            		'search'         => $search,
	            		'load_data'      => $load_data,
	            		'financial_year' => $this->session->userdata('active_year'),
	            		'method'         => '_listOrderPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'order/api/manage_order',$where);
	            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

	            	if(!empty($data_value))
	            	{	
	            		$count    = count($data_value);
		            	$total    = isset($data_list['total_record'])?$data_list['total_record']:'';
		            	$tot_page = ceil($total / $limit); 

	            		$status  = 1;
		            	$message = 'Success';
		            	$table   = '';

	            		$i = 1;
	            		foreach ($data_value as $key => $value) {
	            			$order_id     = !empty($value['order_id'])?$value['order_id']:'';
				            $order_no     = !empty($value['order_no'])?$value['order_no']:'';
				            $emp_name     = !empty($value['emp_name'])?$value['emp_name']:'';
				            $store_name   = !empty($value['store_name'])?$value['store_name']:'';
				            $contact_name = !empty($value['contact_name'])?$value['contact_name']:'';
				            $order_status = !empty($value['order_status'])?$value['order_status']:'';
				            $_ordered     = !empty($value['_ordered'])?$value['_ordered']:'';
				            $_processing  = !empty($value['_processing'])?$value['_processing']:'';
				            $_shiped      = !empty($value['_shiped'])?$value['_shiped']:'';
				            $_canceled    = !empty($value['_canceled'])?$value['_canceled']:'';
				            $_delivery    = !empty($value['_delivery'])?$value['_delivery']:'';
				            $random_value = !empty($value['random_value'])?$value['random_value']:'';
				            $published    = !empty($value['published'])?$value['published']:'';
				            $status       = !empty($value['status'])?$value['status']:'';
				            $createdate   = !empty($value['createdate'])?$value['createdate']:'';

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
			                	$order_view = '<span class="badge badge-info">Complete</span>';
			                }
				            else
			                {
			                	$order_view = '<span class="badge badge-danger">Cancel</span>';
			                }if($order_status == '1')
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
						        $order_view = '<span class="badge badge-info">Shipping</span>';
						    }
						    else if($order_status == '5')
						    {
						        $order_view = '<span class="badge badge-warning">Invoice</span>';
						    }
						    else if($order_status == '6')
						    {
						        $order_view = '<span class="badge badge-success">Delivery</span>';
						    }
						    else if($order_status == '7')
						    {
						        $order_view = '<span class="badge badge-success">Complete</span>';
						    }
						    else
						    {
						        $order_view = '<span class="badge badge-danger">Cancel</span>';
						    }

			                $table .= '
						    	<tr>
	                                <td>'.$order_no.'</td>
			                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
			                        <td>'.mb_strimwidth($emp_name, '0', '12', '...').'</td>
	                                <td>'.mb_strimwidth($store_name, '0', '23', '...').'</td>
			                        <td>'.$order_view.'</td>
			                        <td><a href="'.BASE_URL.'index.php/admin/order/complete_order/view/'.$random_value.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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

			else if($param1 == 'view')
			{
				$random_value = $param2;

				$where = array(
			    	'random_value' => $random_value,
			    	'method'       => '_orderDetails'
			    );

				$data_val = avul_call(API_URL.'order/api/manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Complete Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/admin/order/complete_order";
				$data['page_temp']     = $this->load->view('admin/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "complete_order";
				$this->bassthaya->load_admin_form_template($data);
			}
    	}

    	public function cancle_order($param1="", $param2="", $param3="")
    	{
    		if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');

        	if($param1 == '')
			{
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Cancel Order";
				$page['pre_title']     = "";
				$page['load_data']     = "8";
				$page['function_name'] = "cancle_order";
				$page['pre_menu']      = "index.php/admin/order/list_order";
				$data['page_temp']     = $this->load->view('admin/order/list_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "cancle_order";
				$this->bassthaya->load_admin_form_template($data);
			}

			else if($param1 == 'data_list')
			{
				if(userAccess('outlet-orders-view'))
				{
					$load_data = $this->input->post('load_data');
					$limit     = $this->input->post('limitval');
	            	$page      = $this->input->post('page');
	            	$search    = $this->input->post('search');
	            	$cur_page  = isset($page)?$page:'1';
	            	$_offset   = ($cur_page-1) * $limit;
	            	$nxt_page  = $cur_page + 1;
	            	$pre_page  = $cur_page - 1;

	            	$where = array(
	            		'offset'         => $_offset,
	            		'limit'          => $limit,
	            		'search'         => $search,
	            		'load_data'      => $load_data,
	            		'financial_year' => $this->session->userdata('active_year'),
	            		'method'         => '_listOrderPaginate'
	            	);

	            	$data_list  = avul_call(API_URL.'order/api/manage_order',$where);
	            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

	            	if(!empty($data_value))
	            	{	
	            		$count    = count($data_value);
		            	$total    = isset($data_list['total_record'])?$data_list['total_record']:'';
		            	$tot_page = ceil($total / $limit); 

	            		$status  = 1;
		            	$message = 'Success';
		            	$table   = '';

	            		$i = 1;
	            		foreach ($data_value as $key => $value) {
	            			$order_id     = !empty($value['order_id'])?$value['order_id']:'';
				            $order_no     = !empty($value['order_no'])?$value['order_no']:'';
				            $emp_name     = !empty($value['emp_name'])?$value['emp_name']:'';
				            $store_name   = !empty($value['store_name'])?$value['store_name']:'';
				            $contact_name = !empty($value['contact_name'])?$value['contact_name']:'';
				            $order_status = !empty($value['order_status'])?$value['order_status']:'';
				            $_ordered     = !empty($value['_ordered'])?$value['_ordered']:'';
				            $_processing  = !empty($value['_processing'])?$value['_processing']:'';
				            $_shiped      = !empty($value['_shiped'])?$value['_shiped']:'';
				            $_canceled    = !empty($value['_canceled'])?$value['_canceled']:'';
				            $_delivery    = !empty($value['_delivery'])?$value['_delivery']:'';
				            $random_value = !empty($value['random_value'])?$value['random_value']:'';
				            $published    = !empty($value['published'])?$value['published']:'';
				            $status       = !empty($value['status'])?$value['status']:'';
				            $createdate   = !empty($value['createdate'])?$value['createdate']:'';

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
			                	$order_view = '<span class="badge badge-primary">Shipping</span>';
			                }
				            else if($order_status == '4')
			                {
			                	$order_view = '<span class="badge badge-info">Delivered</span>';
			                }
				            else
			                {
			                	$order_view = '<span class="badge badge-danger">Cancel</span>';
			                }

			                $table .= '
						    	<tr>
	                                <td>'.$order_no.'</td>
			                        <td>'.date('d-m-Y', strtotime($_ordered)).'</td>
			                        <td>'.mb_strimwidth($emp_name, '0', '12', '...').'</td>
	                                <td>'.mb_strimwidth($store_name, '0', '23', '...').'</td>
			                        <td>'.$order_view.'</td>
			                        <td><a href="'.BASE_URL.'index.php/admin/order/cancle_order/view/'.$order_id.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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

			else if($param1 == 'view')
			{
				$random_value = $param2;

				$where = array(
			    	'random_value' => $random_value,
			    	'method'       => '_orderDetails'
			    );

				$data_val = avul_call(API_URL.'order/api/manage_order',$where);

				$page['sales_data']    = $data_val['data'];
				$page['main_heading']  = "Order";
				$page['sub_heading']   = "Order";
				$page['page_title']    = "Cancel Order";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/admin/order/cancle_order";
				$data['page_temp']     = $this->load->view('admin/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "cancle_order";
				$this->bassthaya->load_admin_form_template($data);
			}
    	}

    	public function order_process($param1="", $param2="", $param3="")
    	{	

    		if($param1 == 'changeOrder_process')
    		{
    			$error = FALSE;
    			$order_id     = $this->input->post('order_id');
    			$pre_status   = $this->input->post('pre_status');
				$order_status = $this->input->post('order_status');
				$message      = $this->input->post('message');
				$discount     = $this->input->post('discount');
				$due_days     = $this->input->post('due_days');
				$length       = $this->input->post('length');
			    $breadth      = $this->input->post('breadth');
			    $height       = $this->input->post('height');
			    $weight       = $this->input->post('weight');

				$required = array('pre_status', 'order_id', 'order_status');
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
			    		'auto_id'    => $order_id,
			    		'pre_status' => $pre_status,
						'progress'   => $order_status,
						'reason'     => $message,
						'discount'   => $discount,
						'due_days'   => $due_days,
						'length'     => $length,
						'breadth'    => $breadth,
						'height'     => $height,
						'weight'     => $weight,
						'method'     => '_updateOrderProgress',
					);

					$update = avul_call(API_URL.'order/api/order_process',$order_data);

					if($update['status'] == 1)
				    {
	        			$response['status']  = 1;
				        $response['message'] = $update['message']; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
				    else
				    {
	        			$response['status']  = 0;
				        $response['message'] = $update['message'];  
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
			    }
    		}

    		else if($param1 == 'order_update')
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

					$update = avul_call(API_URL.'order/api/order_process',$order_data);

					if($update['status'] == 1)
				    {
	        			$response['status']  = 1;
				        $response['message'] = $update['message']; 
				        $response['data']    = [];
				        echo json_encode($response);
				        return; 
				    }
				    else
				    {
	        			$response['status']  = 0;
				        $response['message'] = $update['message'];  
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

					    $data_save = avul_call(API_URL.'order/api/order_process',$data);

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
			if(!empty($param1))
    		{
    			$where = array(
			    	'random_value' => $param1,
			    	'method'       => '_orderDetails'
			    );

				$data_val = avul_call(API_URL.'order/api/manage_order',$where);

				if($data_val)
				{
					$ord_val = $data_val['data'];

					$bil_det = !empty($ord_val['bill_details'])?$ord_val['bill_details']:'';
					$str_det = !empty($ord_val['store_details'])?$ord_val['store_details']:'';
					$pdt_det = !empty($ord_val['product_details'])?$ord_val['product_details']:'';
					$tax_det = !empty($ord_val['tax_details'])?$ord_val['tax_details']:'';

                    // Store Details
                    $str_name    = !empty($str_det['company_name'])?$str_det['company_name']:'';
                    $str_cont    = !empty($str_det['mobile'])?$str_det['mobile']:'';
                    $str_gst     = !empty($str_det['gst_no'])?$str_det['gst_no']:'';
                    $str_adrs    = !empty($str_det['address'])?$str_det['address']:'';
                    $str_state   = !empty($str_det['state_id'])?$str_det['state_id']:'';
                    $str_sta_val = !empty($str_det['state_name'])?$str_det['state_name']:'';

                    // Order Details
                    $order_no   = !empty($bil_det['order_no'])?$bil_det['order_no']:'';
		            $bill_type  = !empty($bil_det['bill_type'])?$bil_det['bill_type']:'';
		            $emp_name   = !empty($bil_det['emp_name'])?$bil_det['emp_name']:'';
		            $store_name = !empty($bil_det['store_name'])?$bil_det['store_name']:'';
		            $due_days   = !empty($bil_det['due_days'])?$bil_det['due_days']:'';
		            $discount   = !empty($bil_det['discount'])?$bil_det['discount']:'0';
		            $_ordered   = !empty($bil_det['_ordered'])?$bil_det['_ordered']:'';

		            // Order Type
				    if($bill_type == 1)
				    {
				        $type_view = 'COD';
				    }
				    else
				    {
				        $type_view = 'Credit';
				    }

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

		          	$html .= '<p style="color:black; font-size:12px; text-align: center;"><strong style="font-size:18px; padding-bottom:1000px;">'.ACCOUNT_NAME.'</strong><br/>'.ADDRESS.', Contact No: '.CONTACT_NO.'<br>GSTIN\UIN : '.ADMIN_GST.'<br><strong style="color:black; text-align:center; font-size:17px;"> PROFORMA INVOICE</strong><br></p>';

		          	$html .='<br><br><br>
					<table border= "1" cellpadding="1" top="100">
				        <tr>
				            <td rowspan="6" style="font-size:12px; width: 55%; margin-left:10px;">Shipped To: <br> '.$str_name.'<br> '.$str_adrs.'<br> Contact No: '.$str_cont.'<br> GSTIN\UIN :'.$str_gst.'</td>
				            <td style="font-size:12px; width: 20%;"> Outlet(s) Order No</td>
				            <td style="font-size:12px; width: 25%;">'.$order_no.'</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Outlet(s) Order Date</td>
				            <td style="font-size:12px; width: 25%;">'.date('d-M-Y', strtotime($_ordered)).'</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Invoice No</td>
				            <td style="font-size:12px; width: 25%;"></td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Invoice Date</td>
				            <td style="font-size:12px; width: 25%;"></td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Bill Type</td>
				            <td style="font-size:12px; width: 25%;">'.$type_view.'</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Due Days</td>
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
				        foreach ($pdt_det as $key => $val) {
				        	$description = !empty($val['description'])?$val['description']:'';
		                    $hsn_code    = !empty($val['hsn_code'])?$val['hsn_code']:'';
		                    $gst_value   = !empty($val['gst_val'])?$val['gst_val']:'0';
		                    $unit_val    = !empty($val['unit_val'])?$val['unit_val']:'0';
		                    $price       = !empty($val['price'])?$val['price']:'0';
		                    $order_qty   = !empty($val['order_qty'])?$val['order_qty']:'0';

		                    $tot_price = $order_qty * $price;
		                    $sub_tot  += $tot_price;
		                    $tot_qty  += $order_qty;

		                    $html .= '
		                    	<tr>
						            <td style="font-size:12px; width: 5%;">'.$num.'</td>
						            <td style="font-size:12px; width: 44%;">'.$description.'</td>
						            <td style="font-size:12px; text-align: center; width: 10%;">'.$hsn_code.'</td>
						            <td style="font-size:12px; text-align: center; width: 10%;">'.number_format((float)$price, 2, '.', '').'</td>
						            <td style="font-size:12px; text-align: center; width: 8%;">'.$order_qty.'</td>
						            <td style="font-size:12px; text-align: center; width: 8%;">nos</td>
						            <td style="font-size:12px; text-align: center; width: 15%;">'.number_format((float)$tot_price, 2, '.', '').'</td>
						        </tr>
		                    ';

		                    $num++;
				        }

				        // Round Val Details
	                    $net_value  = round($sub_tot);
	                    $total_dis  = $net_value * $discount / 100;
	                    $total_val  = $net_value - $total_dis;

	                    // Round Val Details
	                    $last_value = round($total_val);
	                    $rond_total = $last_value - $total_val;

	                    $html .= '
	                    	<tr>
				                <td rowspan ="5"  colspan="4"></td>
				                <td colspan="2" style="font-size:12px; text-align: right;">Qty</td>
				                <td style="font-size:12px; text-align: right;"> '.$tot_qty.'</td>
				                
				            </tr>
				            <tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">Sub Total</td>
				                <td style="font-size:12px; text-align: right;"> '.number_format((float)$sub_tot, 2, '.', '').'</td>
				            </tr>
	                    ';

	                    if($discount != 0)
			            {
			            	$html .='<tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">Discount</td>
				                <td style="font-size:12px; text-align: right;"> '.number_format((float)$total_dis, 2, '.', '').'</td>
				            </tr>';	
			            }

			            $html .='<tr>
			                <td colspan="2" style="font-size:12px; text-align: right;">Round off</td>
			                <td style="font-size:12px; text-align: right;"> '.number_format((float)$rond_total, 2, '.', '').'</td>
			            </tr>
			            <tr>
			                <td colspan="2" style="font-size:12px; text-align: right;">Net Total</td>
			                <td style="font-size:12px; text-align: right;"> '.number_format((float)$last_value, 2, '.', '').'</td>
			            </tr>';

				    $html .='</table>';

				    $pdf->writeHTML($html, true, false, true, false, '');
	       			$pdf->Output($order_no.'_'.date('d-F-Y H:i:s').'.pdf', 'I');
				}
    		}
		}

    	public function print_invoice($param1="", $param2="", $param3="")
    	{
    		if(!empty($param1))
    		{
    			$bill_data = array(
    				'random_value' => $param1,
    				'method'       => '_detailInvoice',
    			);

    			$bill_val  = avul_call(API_URL.'order/api/invoice_manage_order', $bill_data);

    			if(!empty($bill_val))
    			{
    				$inv_data = $bill_val['data'];

    				$bill_det    = !empty($inv_data['bill_details'])?$inv_data['bill_details']:'';
    				$buyer_det   = !empty($inv_data['buyer_details'])?$inv_data['buyer_details']:'';
    				$dis_det     = !empty($inv_data['distributor_details'])?$inv_data['distributor_details']:'';
    				$store_det   = !empty($inv_data['store_details'])?$inv_data['store_details']:'';
    				$product_det = !empty($inv_data['product_details'])?$inv_data['product_details']:'';
    				$tax_det     = !empty($inv_data['tax_details'])?$inv_data['tax_details']:'';
    				$return_det  = !empty($inv_data['return_details'])?$inv_data['return_details']:'';

    				// Return Details
    				$return_total = !empty($return_det['return_total'])?$return_det['return_total']:'0';

    				// Invoice Details
    				$invoice_id  = !empty($bill_det['invoice_id'])?$bill_det['invoice_id']:'';
                    $bill_type   = !empty($bill_det['bill_type'])?$bill_det['bill_type']:'';
                    $invoice_no  = !empty($bill_det['invoice_no'])?$bill_det['invoice_no']:'';
                    $due_days    = !empty($bill_det['due_days'])?$bill_det['due_days']:'0';
                    $discount    = !empty($bill_det['discount'])?$bill_det['discount']:'0';
                    $outlet_type = !empty($bill_det['outlet_type'])?$bill_det['outlet_type']:'';
                    $createdate  = !empty($bill_det['createdate'])?$bill_det['createdate']:'';

                    // Order Details
                    $order_no    = !empty($buyer_det['order_no'])?$buyer_det['order_no']:'';
                    $ordered     = !empty($buyer_det['_ordered'])?$buyer_det['_ordered']:'';

                    // Distributor Details
                    $company_name = !empty($dis_det['company_name'])?$dis_det['company_name']:'';
                    $gst_no       = !empty($dis_det['gst_no'])?$dis_det['gst_no']:'';
                    $contact_no   = !empty($dis_det['contact_no'])?$dis_det['contact_no']:'';
                    $email        = !empty($dis_det['email'])?$dis_det['email']:'';
                    $address      = !empty($dis_det['address'])?$dis_det['address']:'';
                    $state_name   = !empty($dis_det['state_name'])?$dis_det['state_name']:'';
                    $gst_code     = !empty($dis_det['gst_code'])?$dis_det['gst_code']:'';
                    $account_name = !empty($dis_det['account_name'])?$dis_det['account_name']:'';
                    $account_no   = !empty($dis_det['account_no'])?$dis_det['account_no']:'';
                    $bank_name    = !empty($dis_det['bank_name'])?$dis_det['bank_name']:'';
                    $branch_name  = !empty($dis_det['branch_name'])?$dis_det['branch_name']:'';
                    $ifsc_code    = !empty($dis_det['ifsc_code'])?$dis_det['ifsc_code']:'';

                    // Store Details
                    $store_name      = !empty($store_det['company_name'])?$store_det['company_name']:'';
                    $str_mobile      = !empty($store_det['mobile'])?$store_det['mobile']:'';
                    $str_email       = !empty($store_det['email'])?$store_det['email']:'';
                    $str_gst_no      = !empty($store_det['gst_no'])?$store_det['gst_no']:'';
                    $str_address     = !empty($store_det['address'])?$store_det['address']:'';
                    $str_state_name  = !empty($store_det['state_name'])?$store_det['state_name']:'';
                    $str_gst_code    = !empty($store_det['gst_code'])?$store_det['gst_code']:'';
                    $str_outlet_type = !empty($store_det['outlet_type'])?$store_det['outlet_type']:'';

                    // Order Type
				    if($bill_type == 1)
				    {
				        $type_view = 'COD';
				    }
				    else
				    {
				        $type_view = 'Credit';
				    }

				    $due_date = '';

				    if(!empty($due_days))
				    {
				    	$due_date = date('d-M-Y', strtotime($createdate. '+ '.$due_days.' day'));
				    }

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

		          	$html .= '<p style="color:black; font-size:12px; text-align: center;"><strong style="font-size:18px; padding-bottom:1000px;">'.$company_name.'</strong><br/>'.$address.', Contact No: '.$contact_no.'<br>GSTIN\UIN : '.$gst_no.'<br><strong style="color:black; text-align:center; font-size:17px;"> TAX INVOICE</strong><br></p>';

		          	$html .='<br><br><br>
					<table border= "1" cellpadding="1" top="100">
				        <tr>
				            <td rowspan="6" style="font-size:12px; width: 55%; margin-left:10px;">Shipped To: <br> '.$store_name.'<br> '.$str_address.'<br> Contact No: '.$str_mobile.'<br> GSTIN\UIN :'.$str_gst_no.'</td>
				            <td style="font-size:12px; width: 20%;"> Invoice No</td>
				            <td style="font-size:12px; width: 25%;">'.$invoice_no.'</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Invoice Date</td>
				            <td style="font-size:12px; width: 25%;">'.date('d-M-Y', strtotime($createdate)).'</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Outlet(s) Order No</td>
				            <td style="font-size:12px; width: 25%;">'.$order_no.'</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Outlet(s) Order Date</td>
				            <td style="font-size:12px; width: 25%;">'.date('d-M-Y', strtotime($ordered)).'</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Bill Type</td>
				            <td style="font-size:12px; width: 25%;">'.$type_view.'</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Due Days</td>
				            <td style="font-size:12px; width: 25%;">'.$due_date.'</td>
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
					        foreach ($product_det as $key => $val) {
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

		          	$rowspan = '7';
				    if($gst_code == $str_gst_code)
				    {
				    	$rowspan = '8';
				    }

		            // Round Val Details
                    $net_value  = round($net_tot);
                    $total_dis  = $net_value * $discount / 100;
                    $total_val  = $net_value - $total_dis - round($return_total);

                    // Round Val Details
                    $last_value = round($total_val);
                    $rond_total = $last_value - $total_val;

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
			            if($gst_code == $str_gst_code)
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

			            if($discount != 0)
			            {
			            	$html .='<tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">Discount</td>
				                <td style="font-size:12px; text-align: right;"> '.number_format((float)$total_dis, 2, '.', '').'</td>
				            </tr>';	
			            }

			            if($return_total != 0)
			            {
			            	$return_data = round($return_total);
			            	
			            	$html .='<tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">Credit Note</td>
				                <td style="font-size:12px; text-align: right;"> '.number_format((float)$return_data, 2, '.', '').'</td>
				            </tr>';	
			            }

			            $html .='<tr>
			                <td colspan="2" style="font-size:12px; text-align: right;">Round off</td>
			                <td style="font-size:12px; text-align: right;"> '.number_format((float)$rond_total, 2, '.', '').'</td>
			            </tr>
			            <tr>
			                <td colspan="2" style="font-size:12px; text-align: right;">Net Total</td>
			                <td style="font-size:12px; text-align: right;"> '.number_format((float)$last_value, 2, '.', '').'</td>
			            </tr>';
				    $html .='</table>';

				    $html .='<br><br>
					<table border= "1" cellpadding="1" top="100">
				        <tr>
				            <td rowspan="2" style="font-size:12px; width: 10%;">HSN</td>
				            <td rowspan="2" style="font-size:12px; width: 15%;">Taxable Value</td>';
				            if($gst_code == $str_gst_code)
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
				        	if($gst_code == $str_gst_code)
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
		                    		if($gst_code == $str_gst_code)
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
				        		if($gst_code == $str_gst_code)
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
				            <td colspan="5" style="font-size:12px; width: 25%;"> Account Name</td>
				            <td colspan="5" style="font-size:12px; width: 25%;"> '.$account_name.'</td>
				            <td rowspan="5" style="font-size:12px; width: 50%;">
				            	<span> for '.$company_name.'</span>
				            	<br><br><br>
				            	<p style="text-align: right; "> Authorised signature </p>
				            </td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:12px; width: 25%;"> Account No</td>
				            <td colspan="5" style="font-size:12px; width: 25%;"> '.$account_no.'</td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:12px; width: 25%;"> Bank Name</td>
				            <td colspan="5" style="font-size:12px; width: 25%;"> '.$bank_name.'</td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:12px; width: 25%;"> Branch Name</td>
				            <td colspan="5" style="font-size:12px; width: 25%;"> '.$branch_name.'</td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:12px; width: 25%;"> IFSC Code</td>
				            <td colspan="5" style="font-size:12px; width: 25%;"> '.$ifsc_code.'</td>
				        </tr>
				    </table>';

		          	$pdf->writeHTML($html, true, false, true, false, '');
	       			$pdf->Output($invoice_no.'_'.date('d-F-Y H:i:s').'.pdf', 'I');
    			}
    		}
    	}
	}
?>
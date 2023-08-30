<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('encryption');
        $this->load->helper('url');
        $this->load->library("Pdf");
    }
    public function create_order($param1 = "", $param2 = "", $param3 = "")
    {
        if ($this->session->userdata('random_value') == '')
            redirect(base_url() . 'index.php?login', 'refresh');

        $formpage = $this->input->post('formpage');

        if ($formpage == 'BTBM_X_P') {
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
            foreach ($required as $field) {
                if (empty($this->input->post($field))) {
                    $error = TRUE;
                }
            }

            if (count(array_filter($product_id)) !== count($product_id) || count(array_filter($type_id)) !== count($type_id) || count(array_filter($product_price)) !== count($product_price) || count(array_filter($product_qty)) !== count($product_qty) || count(array_filter($unit_id)) !== count($unit_id) || $error == TRUE) {
                $response['status']  = 0;
                $response['message'] = "Please fill all required fields";
                $response['data']    = [];
                $response['error']   = [];
                echo json_encode($response);
                return;
            } else {
                if (userAccess('sales-order-add')) {
                    $order_type  = [];
                    $order_count = count($product_id);

                    for ($j = 0; $j < $order_count; $j++) {
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
                        'manager_id'  => $this->session->userdata('id'),
                        'store_id'    => $outlet_id,
                        'sales_order' => $order_value,
                        'order_type'  => 2,
                        'bill_type'   => $bill_type,
                        'discount'    => $discount,
                        'due_days'    => $due_days,
                        'method'      => '_addSalesOrder',
                    );

                    $data_save = avul_call(API_URL . 'order/api/create_order', $data);

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
        else if ($param1 == 'getCity_name') {
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

                    $option .= '<option value="' . $city_id . '">' . $city_name . '</option>';
                }
            }

            $response['status']  = 1;
            $response['message'] = 'success';
            $response['data']    = $option;
            echo json_encode($response);
            return;
        }
        else if ($param1 == 'getZone_name') {
            $state_id = $this->input->post('state_id');
            $city_id  = $this->input->post('city_id');

            $where = array(
                'state_id' => $state_id,
                'city_id'  => $city_id,
                'method'   => '_listZone'
            );

            $zone_list   = avul_call(API_URL . 'master/api/zone', $where);
            $zone_result = $zone_list['data'];

            $option = '<option value="">Select Value</option>';

            if (!empty($zone_result)) {
                foreach ($zone_result as $key => $value) {
                    $zone_id   = !empty($value['zone_id']) ? $value['zone_id'] : '';
                    $zone_name = !empty($value['zone_name']) ? $value['zone_name'] : '';

                    $option .= '<option value="' . $zone_id . '">' . $zone_name . '</option>';
                }
            }

            $response['status']  = 1;
            $response['message'] = 'success';
            $response['data']    = $option;
            echo json_encode($response);
            return;
        } 
        else if ($param1 == 'getOutlet_name') {
            $state_id = $this->input->post('state_id');
            $city_id  = $this->input->post('city_id');
            $zone_id  = $this->input->post('zone_id');

            $where = array(
                'state_id' => $state_id,
                'city_id'  => $city_id,
                'zone_id'  => $zone_id,
                'method'   => '_zoneWiseOutlets'
            );

            $outlet_list   = avul_call(API_URL . 'outlets/api/outlets', $where);
            $outlet_result = $outlet_list['data'];

            $option = '<option value="">Select Value</option>';

            if (!empty($outlet_result)) {
                foreach ($outlet_result as $key => $value) {
                    $outlets_id   = !empty($value['outlets_id']) ? $value['outlets_id'] : '';
                    $company_name = !empty($value['company_name']) ? $value['company_name'] : '';

                    $option .= '<option value="' . $outlets_id . '">' . $company_name . '</option>';
                }
            }

            $response['status']  = 1;
            $response['message'] = 'success';
            $response['data']    = $option;
            echo json_encode($response);
            return;
        } 
        else if ($param1 == 'getOutlet_details') {
            $outlet_id  = $this->input->post('outlet_id');

            $where = array(
                'outlets_id' => $outlet_id,
                'method'     => '_detailOutlets'
            );

            $outlet_list   = avul_call(API_URL . 'outlets/api/outlets', $where);
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
        else if ($param1 == 'getVendor_products') {
            $vendor_id = $this->input->post('vendor_id');

            $where = array(
                'vendor_id' => $vendor_id,
                'method'    => '_listVendorProducts'
            );

            $vendor_list   = avul_call(API_URL . 'catlog/api/product', $where);
            $vendor_result = $vendor_list['data'];

            $option = '<option value="">Select Value</option>';

            if (!empty($vendor_result)) {
                foreach ($vendor_result as $key => $value) {
                    $product_id   = !empty($value['product_id']) ? $value['product_id'] : '';
                    $product_name = !empty($value['product_name']) ? $value['product_name'] : '';

                    $option .= '<option value="' . $product_id . '">' . $product_name . '</option>';
                }
            }

            $response['status']  = 1;
            $response['message'] = 'success';
            $response['data']    = $option;
            echo json_encode($response);
            return;
        } 
        else if ($param1 == 'getVendor_productType') {
            $product_id = $this->input->post('product_id');

            $where = array(
                'product_id' => $product_id,
                'method'     => '_listProductType'
            );

            $type_list   = avul_call(API_URL . 'catlog/api/productType', $where);
            $type_result = $type_list['data'];

            $option = '<option value="">Select Value</option>';

            if (!empty($type_result)) {
                foreach ($type_result as $key => $value) {
                    $type_id     = !empty($value['type_id']) ? $value['type_id'] : '';
                    $description = !empty($value['description']) ? $value['description'] : '';

                    $option .= '<option value="' . $type_id . '">' . $description . '</option>';
                }
            }

            $response['status']  = 1;
            $response['message'] = 'success';
            $response['data']    = $option;
            echo json_encode($response);
            return;
        } 
        else if ($param1 == 'getOrderProduct_details') {
            $product_id = $this->input->post('product_id');

            $where_1 = array(
                'product_id' => $product_id,
                'method'     => '_detailProduct',
            );

            $product_list  = avul_call(API_URL . 'catlog/api/product', $where_1);
            $product_val   = $product_list['data'];

            $hsn_code = !empty($product_val[0]['hsn_code']) ? $product_val[0]['hsn_code'] : '';
            $gst_val  = !empty($product_val[0]['gst']) ? $product_val[0]['gst'] : '';

            $response['status']   = 1;
            $response['message']  = 'success';
            $response['hsn_code'] = $hsn_code;
            $response['gst_val']  = $gst_val;
            echo json_encode($response);
            return;
        } 
        else if ($param1 == 'getOrder_row') {
            $rowCount  = $this->input->post('rowCount');
            $newCount  = $rowCount + 1;
            $emty = '';

            $where_1 = array(
                'method' => '_listProduct'
            );

            $product_list  = avul_call(API_URL . 'catlog/api/product', $where_1);
            $product_val   = $product_list['data'];

            $where_2 = array(
                'method'    => '_listUnit'
            );

            $unit_list  = avul_call(API_URL . 'master/api/unit', $where_2);
            $unit_val   = $unit_list['data'];

            $option = '
	            	<tr class="row_' . $newCount . '">
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

						<td data-te="' . $newCount . '" class="p-l-0 product_list" style="width: 30%;">
							<select data-te="' . $newCount . '" name="product_id[]" id="product_id' . $newCount . '" class="form-control product_id' . $newCount . ' product_id product_val js-select2-multi" >
                                <option value="">Select Product Name</option>';
            if (!empty($product_val)) {
                foreach ($product_val as $key => $value) {
                    $product_id   = !empty($value['product_id']) ? $value['product_id'] : '';
                    $product_name = !empty($value['product_name']) ? $value['product_name'] : '';

                    $option .= "<option value=" . $product_id . ">" . $product_name . "</option>";
                }
            }
            $option .= ' </select>
						</td>

						<td data-te="' . $newCount . '" class="p-l-0 type_list" style="width: 25%;">
                            <select data-te="' . $newCount . '" name="type_id[]" id="type_id' . $newCount . '" class="form-control type_id' . $newCount . ' type_id js-select2-multi" >
                                <option value="">Select Type Name</option>
                            </select> 
                        </td>

						<td class="p-l-0" style="width: 15%;">
                            <input type="text" data-te="' . $newCount . '" name="product_price[]" id="product_price' . $newCount . '" class="form-control product_price' . $newCount . ' product_price int_value" placeholder="Price" oninput="this.value=this.value.replace(/^0+/g,' . $emty . ')">
                        </td>

                        <td class="p-l-0" style="width: 15%;">
                            <input type="text" data-te="' . $newCount . '" name="product_qty[]" id="product_qty' . $newCount . '" class="form-control product_qty' . $newCount . ' product_qty int_value" placeholder="Quantity">

                            <input type="hidden" data-te="' . $newCount . '" name="hsn_code[]" id="hsn_code' . $newCount . '" class="form-control hsn_code' . $newCount . ' hsn_code" placeholder="Quantity">

                            <input type="hidden" data-te="' . $newCount . '" name="gst_val[]" id="gst_val' . $newCount . '" class="form-control gst_val' . $newCount . ' gst_val" placeholder="Quantity">

                        </td>

                        <td class="p-l-0" style="width: 15%;">
                            <select data-te="' . $newCount . '" name="unit_id[]" id="unit_id' . $newCount . '" class="form-control unit_id' . $newCount . ' unit_id js-select2-multi"  >
                                <option value="">Select Unit Name</option>';
            if (!empty($unit_val)) {
                foreach ($unit_val as $key => $value) {
                    $unit_id   = !empty($value['unit_id']) ? $value['unit_id'] : '';
                    $unit_name = !empty($value['unit_name']) ? $value['unit_name'] : '';

                    $option .= "<option value=" . $unit_id . ">" . $unit_name . "</option>";
                }
            }
            $option .= '</select> 
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
        else if ($param1 == 'getProductType_details') {
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

            $product_list  = avul_call(API_URL . 'catlog/api/productType', $where_1);
            $product_val   = $product_list['data'];

            $unit_id   = !empty($product_val[0]['product_unit']) ? $product_val[0]['product_unit'] : '';
            $unit_name = !empty($product_val[0]['unit_name']) ? $product_val[0]['unit_name'] : '';
            $price     = !empty($product_val[0]['product_price']) ? $product_val[0]['product_price'] : '';

            $option = '<option value="' . $unit_id . '">' . $unit_name . '</option>';

            $response['status']  = 1;
            $response['message'] = 'success';
            $response['data']    = $option;
            $response['price']   = $price;
            echo json_encode($response);
            return;
        } 
        else {
            $where_1 = array(
                'method'   => '_listState'
            );

            $state_list  = avul_call(API_URL . 'master/api/state', $where_1);

            $where_2 = array(
                'method' => '_listProduct'
            );

            $product_list = avul_call(API_URL . 'catlog/api/product', $where_2);

            $where_3 = array(
                'method'    => '_listUnit'
            );

            $unit_list  = avul_call(API_URL . 'master/api/unit', $where_3);

            $page['state_val']     = $state_list['data'];
            $page['product_val']   = $product_list['data'];
            $page['unit_val']      = $unit_list['data'];
            //$page['manager_id']      = $this->session->userdata('id');
            $page['main_heading']  = "Order";
            $page['sub_heading']   = "Order";
            $page['page_title']    = "Sales Order";
            $page['pre_title']     = "";
            $page['method']        = "BTBM_X_C";
            $page['load_data']     = "";
            $page['function_name'] = "create_order";
            $page['pre_menu']      = "index.php/managers/order/create_order";
            $data['page_temp']     = $this->load->view('managers/order/create_order', $page, TRUE);
            $data['view_file']     = "Page_Template";
            $data['currentmenu']   = "create_order";
            $this->bassthaya->load_Managers_form_template($data);
        }
    }
	public function overall_order($param1="", $param2="", $param3="")
	{
            if($param1 == 'view')
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
				$page['pre_menu']      = "index.php/managers/order/overall_order";
				$data['page_temp']     = $this->load->view('managers/order/view_order',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "dashboard";
				$this->bassthaya->load_Managers_form_template($data);
			}
    }
   
}

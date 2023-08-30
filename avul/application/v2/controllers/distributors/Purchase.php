<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('encryption');
		$this->load->helper('url');
	}

	public function add_purchase($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$formpage       = $this->input->post('formpage');
		$method         = $this->input->post('method');
		$distributor_id = $this->session->userdata('id');
		$vendor_id      = $this->session->userdata('vendor_id');

		if ($formpage == 'BTBM_X_P') {
			$dis_product_id  = $this->input->post('dis_product_id');
			$dis_product_qty = $this->input->post('dis_product_qty');
			$dis_price_val   = $this->input->post('dis_price_val');
			$dis_unit_id     = $this->input->post('dis_unit_id');

			$error = FALSE;

			if (count(array_filter($dis_product_id)) !== count($dis_product_id) || count(array_filter($dis_product_qty)) !== count($dis_product_qty) || count(array_filter($dis_price_val)) !== count($dis_price_val) || count(array_filter($dis_unit_id)) !== count($dis_unit_id)) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				if ($method == 'BTBM_X_C') {
					$dis_product_count = count($dis_product_id);
					$purchase_type     = [];

					for ($i = 0; $i < $dis_product_count; $i++) {

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
						'method'           => '_addDistributorPurchase',
					);

					$data_save = avul_call(API_URL . 'distributorpurchase/api/add_purchase', $data);

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
				}
			}
		} else if ($param1 == 'getDistributorProduct_details') {
			$product_id = $this->input->post('product_id');

			$pdt_whr = array(
				'distributor_id' => $distributor_id,
				'assproduct_id'  => $product_id,
				'method'         => '_detailsDistributorAssignProduct',
			);

			$product_list  = avul_call(API_URL . 'assignproduct/api/list_assign_product', $pdt_whr);
			$product_val   = $product_list['data'];

			$pdt_unit  = !empty($product_val['product_unit']) ? $product_val['product_unit'] : '';
			$unit_name = !empty($product_val['unit_name']) ? $product_val['unit_name'] : '';
			$pdt_price = !empty($product_val['product_price']) ? $product_val['product_price'] : '0';
			$min_order = !empty($product_val['minimum_order']) ? $product_val['minimum_order'] : '0';

			$option = '<option value="' . $pdt_unit . '">' . $unit_name . '</option>';

			$response['status']  = 1;
			$response['message'] = 'success';
			$response['data']    = $option;
			$response['price']   = $pdt_price;
			$response['qty']     = $min_order;
			echo json_encode($response);
			return;
		} else if ($param1 == 'getDistributorPurchase_row') {
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

			$pdt_list = avul_call(API_URL . 'assignproduct/api/list_assign_product', $where_1);
			$pdt_val  = $pdt_list['data'];

			// Unit List
			$where_2 = array(
				'method'    => '_listUnit'
			);

			$unit_list = avul_call(API_URL . 'master/api/unit', $where_2);
			$unit_val  = $unit_list['data'];

			$option = '
            		<tr class="row_' . $newCount . '">
            			<script src="' . BASE_URL . 'app-assets/js/select2.full.js"></script>
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

	                    <td data-te="' . $newCount . '" class="p-l-0 dis_product_list" style="width: 40%;">
	                        <select data-te="' . $newCount . '" name="dis_product_id[]" id="dis_product_id' . $newCount . '" class="form-control dis_product_id' . $newCount . ' product_id js-select2-multi" data-te="' . $newCount . '" style="width: 100%;">
	                        	<option value="">Select Product Name</option>';
			if (!empty($pdt_val)) {
				foreach ($pdt_val as $key => $value) {

					$assproduct_id = !empty($value['assproduct_id']) ? $value['assproduct_id'] : '';
					$description   = !empty($value['description']) ? $value['description'] : '';

					$option .= '<option value="' . $assproduct_id . '">' . $description . '</option>';
				}
			}
			$option .= '</select>
	                    </td>
	                    <td class="p-l-0">
	                        <input type="text" data-te="' . $newCount . '" name="dis_product_price[]" id="dis_product_price' . $newCount . '" class="form-control bg-white dis_product_price' . $newCount . ' dis_product_price int_value" placeholder="Price"  readonly="readonly">
	                    </td>
	                    <td class="p-l-0">
	                    	<input type="text" data-te="' . $newCount . '" name="dis_product_qty[]" id="dis_product_qty' . $newCount . '" class="form-control dis_product_qty' . $newCount . ' dis_product_qty int_value"  placeholder="Quantity" maxlength="5">

	                        <input type="hidden" data-te="' . $newCount . '" name="dis_purchase_id[]" id="dis_purchase_id' . $newCount . '" class="form-control dis_purchase_id' . $newCount . ' dis_purchase_id" placeholder="Enter the Price">

	                        <input type="hidden" data-te="' . $newCount . '" name="dis_price_val[]" id="dis_price_val' . $newCount . '" class="form-control dis_price_val' . $newCount . ' dis_price_val" placeholder="Enter the Price" value="">
	                    </td>
	                    <td class="p-l-0" style="width: 30%;">
	                        <select data-te="' . $newCount . '" name="dis_unit_id[]" id="dis_unit_id' . $newCount . '" class="form-control dis_unit_id' . $newCount . ' dis_unit_id js-select2-multi" data-te="' . $newCount . '" style="width: 100%;">
	                            <option value="">Select Unit Name</option>';
			if (!empty($unit_val)) {
				foreach ($unit_val as $key => $value) {

					$unit_id   = !empty($value['unit_id']) ? $value['unit_id'] : '';
					$unit_name = !empty($value['unit_name']) ? $value['unit_name'] : '';

					$option .= '<option value="' . $unit_id . '">' . $unit_name . '</option>';
				}
			}
			$option .= '</select>
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
		} else {
			// Product List
			$where_1  = array(
				'distributor_id' => $distributor_id,
				'vendor_id'      => $vendor_id,
				'published'      => '1',
				'status'         => '1',
				'method'         => '_listDistributorAssignProduct'
			);

			$pdt_list = avul_call(API_URL . 'assignproduct/api/list_assign_product', $where_1);
			$pdt_val  = $pdt_list['data'];

			// Unit List
			$where_2 = array(
				'method'    => '_listUnit'
			);

			$unit_list = avul_call(API_URL . 'master/api/unit', $where_2);
			$unit_val  = $unit_list['data'];

			$page['dataval']     = '';
			$page['product_val'] = $pdt_val;
			$page['unit_val']    = $unit_val;
			$page['method']      = 'BTBM_X_C';
			$page['page_title']  = "Add Purchase";
			$page['main_heading'] = "Purchase";
			$page['sub_heading']  = "Purchase";
			$page['pre_title']    = "List Purchase";
			$page['pre_menu']     = "index.php/distributors/purchase/list_purchase";
			$data['page_temp']    = $this->load->view('distributors/purchase/add_purchase', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "add_purchase";
			$this->bassthaya->load_distributors_form_template($data);
		}
	}

	public function list_purchase($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$formpage       = $this->input->post('formpage');
		$method         = $this->input->post('method');
		$distributor_id = $this->session->userdata('id');

		if ($param1 == '') {
			$page['main_heading'] = "Purchase";
			$page['sub_heading']  = "Purchase";
			$page['page_title']   = "List Purchase";
			$page['pre_title']    = "Add Purchase";
			$page['pre_menu']     = "index.php/distributors/purchase/add_purchase";
			$data['page_temp']    = $this->load->view('distributors/purchase/list_purchase', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "list_purchase";
			$this->bassthaya->load_distributors_form_template($data);
		} else if ($param1 == 'data_list') {
			$limit    = $this->input->post('limitval');
			$page     = $this->input->post('page');
			$search   = $this->input->post('search');
			$cur_page = isset($page) ? $page : '1';
			$_offset  = ($cur_page - 1) * $limit;
			$nxt_page = $cur_page + 1;
			$pre_page = $cur_page - 1;

			$where = array(
				'offset'          => $_offset,
				'limit'           => $limit,
				'search'          => $search,
				'distributor_id'  => $distributor_id,
				'financial_year'  => $this->session->userdata('active_year'),
				'method'          => '_listDistributorPurchasePaginate'
			);

			$data_list  = avul_call(API_URL . 'distributorpurchase/api/manage_purchase', $where);
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

					$po_id          = !empty($value['po_id']) ? $value['po_id'] : '';
					$po_no          = !empty($value['po_no']) ? $value['po_no'] : '';
					$distributor_id = !empty($value['distributor_id']) ? $value['distributor_id'] : '';
					$company_name   = !empty($value['company_name']) ? $value['company_name'] : '';
					$order_date     = !empty($value['order_date']) ? $value['order_date'] : '';
					$order_status   = !empty($value['order_status']) ? $value['order_status'] : '';
					$_ordered       = !empty($value['_ordered']) ? $value['_ordered'] : '';
					$financial_year = !empty($value['financial_year']) ? $value['financial_year'] : '';
					$bill           = !empty($value['bill']) ? $value['bill'] : '';
					$published      = !empty($value['published']) ? $value['published'] : '';
					$active_status  = !empty($value['status']) ? $value['status'] : '';
					$createdate     = !empty($value['createdate']) ? $value['createdate'] : '';

					if ($active_status == '1') {
						$status_view = '<span class="badge badge-success">Active</span>';
					} else {
						$status_view = '<span class="badge badge-danger">In Active</span>';
					}

					// Order Status
					if ($order_status == '1') {
						$order_view = '<span class="badge badge-success">Success</span>';
					} else if ($order_status == '2') {
						$order_view = '<span class="badge badge-warning">Approved</span>';
					} else if ($order_status == '3') {
						$order_view = '<span class="badge badge-primary">Packing</span>';
					} else if ($order_status == '4') {
						$order_view = '<span class="badge badge-info">Invoice</span>';
					} else if ($order_status == '10') {
						$order_view = '<span class="badge badge-warning">Shipping</span>';
					} else if ($order_status == '11') {
						$order_view = '<span class="badge badge-primary">Delivered</span>';
					} else if ($order_status == '5') {
						$order_view = '<span class="badge badge-success">Complete</span>';
					} else if ($order_status == '7') {
						$order_view = '<span class="badge badge-danger">Cancel Invice</span>';

						$order_btn = "<a class='button_clr btn btn-success'><i class='ft-check-circle'></i></a>";
					} else {
						$order_view = '<span class="badge badge-danger">Cancel</span>';
					}

					$table .= '
					    	<tr>
                                <td class="line_height">' . $i . '</td>
                                <td class="line_height">' . $po_no . '</td>
                                <td class="line_height">' . $order_date . '</td>
                                <td class="line_height">' . $order_view . '</td>
                                <td>
                                	<a href="' . BASE_URL . 'index.php/distributors/purchase/view_purchase/View/' . $po_id . '" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a>
                                </td>
                            </tr>
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

			$response['status']     = $status;
			$response['result']     = $table;
			$response['message']    = $message;
			$response['next']       = $next;
			$response['prev']       = $prev;
			echo json_encode($response);
			return;
		}
	}

	public function view_purchase($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$distributor_id = $this->session->userdata('id');

		if ($param1 == 'View') {
			$purchase_id = $param2;

			$where = array(
				'purchase_id'    => $purchase_id,
				'distributor_id' => $distributor_id,
				'view_type'      => 2,
				'method'         => '_viewDistributorPurchase'
			);

			$data_list  = avul_call(API_URL . 'distributorpurchase/api/manage_purchase', $where);
			$data_value = !empty($data_list['data']) ? $data_list['data'] : '';

			$page['purchase_data'] = $data_value;
			$page['main_heading']  = "Purchase";
			$page['sub_heading']   = "Manage Purchase";
			$page['page_title']    = "Purchase Invoice";
			$page['pre_title']     = "Purchase";
			$page['pre_menu']      = "index.php/distributors/purchase/list_purchase";
			$data['page_temp']     = $this->load->view('distributors/purchase/view_purchase', $page, TRUE);
			$data['view_file']     = "Page_Template";
			$data['currentmenu']   = "list_purchase";
			$this->bassthaya->load_distributors_form_template($data);
		}
	}

	public function order_process($param1 = "", $param2 = "", $param3 = "")
	{
		if ($param1 == 'changeOrder_process') {
			$error = FALSE;
			$order_id     = $this->input->post('order_id');
			$order_status = $this->input->post('order_status');

			$required = array('order_id', 'order_status');
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
				$order_data = array(
					'auto_id'     => $order_id,
					'progress'    => $order_status,
					'method'      => '_updateOrderProgress',
				);

				$data_save = avul_call(API_URL . 'distributorpurchase/api/order_process', $order_data);

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
			}
		}
	}

	public function add_inventory($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$formpage       = $this->input->post('formpage');
		$method         = $this->input->post('method');
		$distributor_id = $this->session->userdata('id');
		$vendor_id      = $this->session->userdata('vendor_id');

		if ($formpage == 'BTBM_X_P') {
			$dis_cat_id  = $this->input->post('dis_cat_id');
			$dis_pdt_id  = $this->input->post('dis_pdt_id');
			$dis_pdt_qty = $this->input->post('dis_pdt_qty');
			$dis_auto_id = $this->input->post('dis_auto_id');

			if (count(array_filter($dis_cat_id)) !== count($dis_cat_id) || count(array_filter($dis_pdt_id)) !== count($dis_pdt_id) || count(array_filter($dis_pdt_qty)) !== count($dis_pdt_qty)) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				$inventory_type  = [];
				$inventory_count = count($dis_auto_id);

				for ($j = 0; $j < $inventory_count; $j++) {
					$inventory_type[] = array(
						'dis_cat_id'  => $dis_cat_id[$j],
						'dis_pdt_id'  => $dis_pdt_id[$j],
						'dis_pdt_qty' => $dis_pdt_qty[$j],
					);
				}

				$inventory_value = json_encode($inventory_type);

				$data = array(
					'distributor_id'   => $this->session->userdata('id'),
					'inventory_value'  => $inventory_value,
					'active_financial' => $this->session->userdata('active_year'),
					'method'           => '_addInventory',
				);

				$data_save = avul_call(API_URL . 'purchase/api/add_inventory', $data);

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
			}
		} else if ($param1 == '_getDistributorProduct') {
			$category_id    = $this->input->post('category_id');

			$where = array(
				'category_id'    => $category_id,
				'distributor_id' => $distributor_id,
				'vendor_id'      => $vendor_id,
				'method'         => '_listDistributorAssignInventroyProduct'
			);

			$product_list   = avul_call(API_URL . 'assignproduct/api/list_assign_product', $where);
			$product_result = $product_list['data'];

			$option = '<option value="">Select Value</option>';

			if (!empty($product_result)) {
				foreach ($product_result as $key => $value) {
					$assign_id   = !empty($value['assign_id']) ? $value['assign_id'] : '';
					$description = !empty($value['description']) ? $value['description'] : '';

					$option .= '<option value=' . $assign_id . '>' . $description . '</option>';
				}
			}

			$response['status']  = 1;
			$response['message'] = 'success';
			$response['data']    = $option;
			echo json_encode($response);
			return;
		} else if ($param1 == 'getDistributorInventory_row') {
			$rowCount  = $this->input->post('rowCount');
			$newCount  = $rowCount + 1;

			$where = array(
				'distributor_id' => $distributor_id,
				'method'         => '_listDistributorCategory'
			);

			$cat_list = avul_call(API_URL . 'assignproduct/api/list_distributor_product', $where);
			$cat_val  = $cat_list['data'];

			$option = '
            		<tr class="row_' . $newCount . '">
            			<script src="' . BASE_URL . 'app-assets/js/select2.full.js"></script>
            			<script>
							var baseurl = $(".geturl").val();

							if($(".js-select2-multi").length)
						    {
						        $(".js-select2-multi").select2({
						            placeholder: "Select Value",
						        });
						    }

						    if($(".category_id").length)
						    {
						        $(".category_id").on("change",function(){
						            var category_id = $(this).val();
						            var auto_id     = $(this).attr("data-te");
						            var value       = $("#value").val();
						            var cntrl       = $("#cntrl").val();
						            var func        = $("#func").val();

						            $.ajax({
						                method: "POST",
						                data: {
						                    "category_id" : category_id,
						                },
						                url: baseurl+"index.php/"+value+"/"+cntrl+"/"+func+"/_getDistributorProduct",
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
						</script>

	                    <td data-te="' . $newCount . '" class="p-l-0 dis_product_list" style="width: 25%;">
	                        <select data-te="' . $newCount . '" name="dis_cat_id[]" id="dis_cat_id' . $newCount . '" class="form-control dis_cat_id1 category_id js-select2-multi" style="width: 100%;">
	                            <option value="">Select Product Name</option>';
			if (!empty($cat_val)) {
				foreach ($cat_val as $key => $val) {

					$cat_id   = !empty($val['category_id']) ? $val['category_id'] : '';
					$cat_name = !empty($val['category_name']) ? $val['category_name'] : '';

					$option .= '<option value=' . $cat_id . '>' . $cat_name . '</option>';
				}
			}
			$option .= '</select> 
	                    </td>
	                    <td data-te="' . $newCount . '" class="p-l-0 dis_product_list" style="width: 50%;">
	                        <select data-te="' . $newCount . '" name="dis_pdt_id[]" id="dis_pdt_id' . $newCount . '" class="form-control dis_pdt_id1 type_id' . $newCount . ' product_id js-select2-multi type_id" style="width: 100%;">
	                            <option value="">Select Product Name</option>
	                        </select> 
	                    </td>
	                    <td class="p-l-0">
	                        <input type="text" data-te="' . $newCount . '" name="dis_pdt_qty[]" id="dis_pdt_qty' . $newCount . '" class="form-control bg-white dis_pdt_qty' . $newCount . ' dis_pdt_qty int_value" placeholder="Quantity">

	                        <input type="hidden" data-te="' . $newCount . '" name="dis_auto_id[]" id="dis_auto_id' . $newCount . '" class="form-control bg-white dis_auto_id' . $newCount . ' dis_auto_id" placeholder="Quantity" value="' . $newCount . '">
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
		} else {
			$where = array(
				'distributor_id' => $distributor_id,
				'method'         => '_listDistributorCategory'
			);

			$category_list   = avul_call(API_URL . 'assignproduct/api/list_distributor_product', $where);
			$category_result = $category_list['data'];

			$page['cat_val']      = $category_result;
			$page['method']       = 'BTBM_X_C';
			$page['page_title']   = "Add Inventory";
			$page['main_heading'] = "Inventory";
			$page['sub_heading']  = "Inventory";
			$page['pre_title']    = "List Inventory";
			$page['pre_menu']     = "index.php/distributors/purchase/add_inventory";
			$data['page_temp']    = $this->load->view('distributors/purchase/add_inventory', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "add_inventory";
			$this->bassthaya->load_distributors_form_template($data);
		}
	}

	public function stock_entry($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$formpage       = $this->input->post('formpage');
		$method         = $this->input->post('method');
		$distributor_id = $this->session->userdata('id');

		if ($formpage == 'BTBM_X_P') {
			$error = FALSE;
			$category_id = $this->input->post('category_id');
			$order_date  = $this->input->post('order_date');
			$stock_val   = $this->input->post('stock_val');
			$damage_val  = $this->input->post('damage_val');
			$expiry_val  = $this->input->post('expiry_val');
			$assign_val  = $this->input->post('assign_val');

			$required = array('category_id', 'order_date');
			foreach ($required as $field) {
				if (empty($this->input->post($field))) {
					$error = TRUE;
				}
			}

			if (count(array_filter($assign_val)) !== count($assign_val) || $error == TRUE) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				$stock_type  = [];
				$stock_count = count($assign_val);

				for ($j = 0; $j < $stock_count; $j++) {
					$stock_type[] = array(
						'stock_val'  => $stock_val[$j],
						'damage_val' => $damage_val[$j],
						'expiry_val' => $expiry_val[$j],
						'assign_val' => $assign_val[$j],
					);
				}

				$stock_value = json_encode($stock_type);

				$data = array(
					'category_id'      => $category_id,
					'distributor_id'   => $distributor_id,
					'stock_value'      => $stock_value,
					'order_date'       => date('Y-m-d', strtotime($order_date)),
					'active_financial' => $this->session->userdata('active_year'),
					'method'           => '_addDistributorStock',
				);

				$data_save = avul_call(API_URL . 'stock/api/add_distributor_stock', $data);

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
			}
		}

		if ($param1 == '_getAssignProduct') {
			$category_id = $this->input->post('category_id');
			$sub_cat_id  = $this->input->post('sub_cat_id');
			// Product List
			$where_1  = array(
				'distributor_id' => $distributor_id,
				'category_id'    => $category_id,
				'sub_cat_id'     => $sub_cat_id,
				'method'         => '_listDistributorAssignCategoryProduct'
			);

			$cat_list = avul_call(API_URL . 'assignproduct/api/list_assign_product', $where_1);
			$cat_val  = $cat_list['data'];

			if ($cat_val) {
				$num  = 1;
				$html = '';
				foreach ($cat_val as $key => $val_3) {

					$assign_id   = !empty($val_3['assign_id']) ? $val_3['assign_id'] : '';
					$description = !empty($val_3['description']) ? $val_3['description'] : '';

					$html .= '
    						<tr>
                                <td>' . $num . '</td>
                                <td style="width: 30%;">' . $description . '</td>
                                <td style="padding: .75rem;">
                                	<input type="text" data-te="' . $num . '" name="stock_val[]" id="stock_val' . $num . '" class="form-control stock_val' . $num . ' stock_val int_value" placeholder="Stock Value">
                                </td>
                                <td style="padding: .75rem;">
                                	<input type="text" data-te="' . $num . '" name="damage_val[]" id="damage_val' . $num . '" class="form-control damage_val' . $num . ' damage_val int_value" placeholder="Damage Value">
                                </td>
                                <td style="padding: .75rem;">
                                	<input type="text" data-te="' . $num . '" name="expiry_val[]" id="expiry_val' . $num . '" class="form-control expiry_val' . $num . ' expiry_val int_value" placeholder="Expiry Value">

                                	<input type="hidden" data-te="' . $num . '" name="assign_val[]" id="assign_val' . $num . '" class="form-control assign_val' . $num . ' assign_val" value="' . $assign_id . '">
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
			} else {
				$response['status']  = 0;
				$response['message'] = $cat_val['message'];
				$response['data']    = [];
				echo json_encode($response);
				return;
			}
		}else if($param1 == 'get_sub_cat_list')
		{
			$cat_id  = $this->input->post('category_id');
			
			
			
			$att_whr = array(
				'category_id'  => $cat_id,
				'distributor_id'  => $this->session->userdata('id'),
				'method'      => '_distributorSubCategoryList',
			);
			
			
			$data_list  = avul_call(API_URL.'distributors/api/distributors',$att_whr);
			
			$data_val  = $data_list['data'];
			
		
			$option ='<option value="">Select Sub Category</option>';

			if(!empty($data_val))
			{
				foreach ($data_val as $key => $value) {
					$id   = !empty($value['s_cat_id']) ?$value['s_cat_id']:'';
					
					$name =!empty($value['s_cat_name'])?$value['s_cat_name']:'';

					$select   = '';
					
					

					$option .= '<option value="'.$id.'" '.$select.'>'.$name.'</option>';
				}
			}

			$response['status']  = 1;
			$response['message'] = 'success'; 
			$response['data']    = $option;
			echo json_encode($response);
			return; 	
		} else {
			$page['dataval']    = '';
			$page['method']     = 'BTBM_X_C';
			$page['page_title'] = "Stock Entry";

			// Product List
			$where_1  = array(
				'distributor_id' => $distributor_id,
				'method'         => '_listDistributorAssignCategory'
			);

			$pdt_list = avul_call(API_URL . 'assignproduct/api/list_assign_product', $where_1);
			$pdt_val  = $pdt_list['data'];

			$page['product_val']  = $pdt_val;
			$page['main_heading'] = "Stock Entry";
			$page['sub_heading']  = "Stock Entry";
			$page['pre_title']    = "List Stock Entry";
			$page['pre_menu']     = "index.php/distributors/purchase/list_purchase_return";
			$data['page_temp']    = $this->load->view('distributors/purchase/stock_entry', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "stock_entry";
			$this->bassthaya->load_distributors_form_template($data);
		}
	}

	public function manage_stock($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$distributor_id = $this->session->userdata('id');

		if ($param1 == '') {
			$page['main_heading'] = "Stock";
			$page['sub_heading']  = "Stock";
			$page['page_title']   = "List Stock";
			$page['pre_title']    = "Add Stock";
			$page['pre_menu']     = "index.php/distributors/purchase/stock_entry";
			$data['page_temp']    = $this->load->view('distributors/purchase/manage_stock', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "manage_stock";
			$this->bassthaya->load_distributors_form_template($data);
		} else if ($param1 == 'data_list') {
			$limit    = $this->input->post('limitval');
			$page     = $this->input->post('page');
			$search   = $this->input->post('search');
			$cur_page = isset($page) ? $page : '1';
			$_offset  = ($cur_page - 1) * $limit;
			$nxt_page = $cur_page + 1;
			$pre_page = $cur_page - 1;

			$where = array(
				'offset'          => $_offset,
				'limit'           => $limit,
				'search'          => $search,
				'distributor_id'  => $distributor_id,
				'financial_year'  => $this->session->userdata('active_year'),
				'method'          => '_listDistributorStockPaginate'
			);

			$data_list  = avul_call(API_URL . 'stock/api/manage_distributor_stock', $where);
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
					$stock_id      = !empty($value['stock_id']) ? $value['stock_id'] : '';
					$stock_no      = !empty($value['stock_no']) ? $value['stock_no'] : '';
					$order_date    = !empty($value['order_date']) ? $value['order_date'] : '';
					$active_status = !empty($value['status']) ? $value['status'] : '1';

					if ($active_status == '1') {
						$status_view = '<span class="badge badge-success">Active</span>';
					} else {
						$status_view = '<span class="badge badge-danger">In Active</span>';
					}

					$table .= '
					    	<tr>
                                <td class="line_height">' . $i . '</td>
                                <td class="line_height">' . $stock_no . '</td>
                                <td class="line_height">' . $order_date . '</td>
                                <td class="line_height">' . $status_view . '</td>
                                <td>
                                	<a href="' . BASE_URL . 'index.php/distributors/purchase/manage_stock/View/' . $stock_id . '" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View</a>
                                </td>
                            </tr>
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

			$response['status']     = $status;
			$response['result']     = $table;
			$response['message']    = $message;
			$response['next']       = $next;
			$response['prev']       = $prev;
			echo json_encode($response);
			return;
		} else if ($param1 == 'View') {
			$where = array(
				'stock_id' => $param2,
				'method'   => '_viewDistributorStock'
			);

			$data_list  = avul_call(API_URL . 'stock/api/manage_distributor_stock', $where);
			$data_value = !empty($data_list['data']) ? $data_list['data'] : '';

			$page['stock_value']   = $data_value;
			$page['main_heading']  = "Purchase";
			$page['sub_heading']   = "Manage Stock Entry";
			$page['page_title']    = "Stock Entry";
			$page['pre_title']     = "Stock";
			$page['pre_menu']      = "index.php/distributors/purchase/manage_stock";
			$data['page_temp']     = $this->load->view('distributors/purchase/view_stock', $page, TRUE);
			$data['view_file']     = "Page_Template";
			$data['currentmenu']   = "manage_stock";
			$this->bassthaya->load_distributors_form_template($data);
		}
	}

	public function add_purchase_return($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$formpage       = $this->input->post('formpage');
		$method         = $this->input->post('method');
		$distributor_id = $this->session->userdata('id');
		$vendor_id      = $this->session->userdata('vendor_id');

		if ($formpage == 'BTBM_X_P') {
			$dis_product_id  = $this->input->post('dis_product_id');
			$dis_product_qty = $this->input->post('dis_product_qty');
			$dis_price_val   = $this->input->post('dis_price_val');
			$dis_unit_id     = $this->input->post('dis_unit_id');

			$error = FALSE;

			if (count(array_filter($dis_product_id)) !== count($dis_product_id) || count(array_filter($dis_product_qty)) !== count($dis_product_qty) || count(array_filter($dis_price_val)) !== count($dis_price_val) || count(array_filter($dis_unit_id)) !== count($dis_unit_id)) {
				$response['status']  = 0;
				$response['message'] = "Please fill all required fields";
				$response['data']    = [];
				$response['error']   = [];
				echo json_encode($response);
				return;
			} else {
				if ($method == 'BTBM_X_C') {
					$dis_product_count = count($dis_product_id);
					$purchase_type     = [];

					for ($i = 0; $i < $dis_product_count; $i++) {

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
						'method'           => '_addDistributorPurchaseReturn',
					);

					$data_save = avul_call(API_URL . 'distributorpurchase/api/add_purchase_return', $data);

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
				}
			}
		} else if ($param1 == 'getDistributorPurchase_row') {
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

			$pdt_list = avul_call(API_URL . 'assignproduct/api/list_assign_product', $where_1);
			$pdt_val  = $pdt_list['data'];

			// Unit List
			$where_2 = array(
				'method'    => '_listUnit'
			);

			$unit_list = avul_call(API_URL . 'master/api/unit', $where_2);
			$unit_val  = $unit_list['data'];

			$option = '
            		<tr class="row_' . $newCount . '">
            			<script src="' . BASE_URL . 'app-assets/js/select2.full.js"></script>
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

	                    <td data-te="' . $newCount . '" class="p-l-0 dis_product_list" style="width: 40%;">
	                        <select data-te="' . $newCount . '" name="dis_product_id[]" id="dis_product_id' . $newCount . '" class="form-control dis_product_id' . $newCount . ' product_id js-select2-multi" data-te="' . $newCount . '" style="width: 100%;">
	                        	<option value="">Select Product Name</option>';
			if (!empty($pdt_val)) {
				foreach ($pdt_val as $key => $value) {

					$assproduct_id = !empty($value['assproduct_id']) ? $value['assproduct_id'] : '';
					$description   = !empty($value['description']) ? $value['description'] : '';

					$option .= '<option value="' . $assproduct_id . '">' . $description . '</option>';
				}
			}
			$option .= '</select>
	                    </td>
	                    <td class="p-l-0">
	                        <input type="text" data-te="' . $newCount . '" name="dis_product_price[]" id="dis_product_price' . $newCount . '" class="form-control bg-white dis_product_price' . $newCount . ' dis_product_price int_value" placeholder="Price" readonly="readonly">
	                    </td>
	                    <td class="p-l-0">
	                    	<input type="text" data-te="' . $newCount . '" name="dis_product_qty[]" id="dis_product_qty' . $newCount . '" class="form-control dis_product_qty' . $newCount . ' dis_product_qty int_value" placeholder="Quantity">

	                        <input type="hidden" data-te="' . $newCount . '" name="dis_purchase_id[]" id="dis_purchase_id' . $newCount . '" class="form-control dis_purchase_id' . $newCount . ' dis_purchase_id" placeholder="Enter the Price">

	                        <input type="hidden" data-te="' . $newCount . '" name="dis_price_val[]" id="dis_price_val' . $newCount . '" class="form-control dis_price_val' . $newCount . ' dis_price_val" placeholder="Enter the Price" value="">
	                    </td>
	                    <td class="p-l-0" style="width: 30%;">
	                        <select data-te="' . $newCount . '" name="dis_unit_id[]" id="dis_unit_id' . $newCount . '" class="form-control dis_unit_id' . $newCount . ' dis_unit_id js-select2-multi" data-te="' . $newCount . '" style="width: 100%;">
	                            <option value="">Select Unit Name</option>';
			if (!empty($unit_val)) {
				foreach ($unit_val as $key => $value) {

					$unit_id   = !empty($value['unit_id']) ? $value['unit_id'] : '';
					$unit_name = !empty($value['unit_name']) ? $value['unit_name'] : '';

					$option .= '<option value="' . $unit_id . '">' . $unit_name . '</option>';
				}
			}
			$option .= '</select>
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
		} else if ($param1 == 'getDistributorProduct_details') {
			$product_id = $this->input->post('product_id');

			$pdt_whr = array(
				'distributor_id' => $distributor_id,
				'assproduct_id'  => $product_id,
				'method'         => '_detailsDistributorAssignProduct',
			);

			$product_list  = avul_call(API_URL . 'assignproduct/api/list_assign_product', $pdt_whr);
			$product_val   = $product_list['data'];

			$pdt_unit  = !empty($product_val['product_unit']) ? $product_val['product_unit'] : '';
			$unit_name = !empty($product_val['unit_name']) ? $product_val['unit_name'] : '';
			$pdt_price = !empty($product_val['product_price']) ? $product_val['product_price'] : '0';

			$option = '<option value="' . $pdt_unit . '">' . $unit_name . '</option>';

			$response['status']  = 1;
			$response['message'] = 'success';
			$response['data']    = $option;
			$response['price']   = $pdt_price;
			echo json_encode($response);
			return;
		} else {
			$page['dataval']    = '';
			$page['method']     = 'BTBM_X_C';
			$page['page_title'] = "Add Purchase Return";

			// Product List
			$where_1  = array(
				'distributor_id' => $distributor_id,
				'vendor_id'      => $vendor_id,
				'published'      => '1',
				'status'         => '1',
				'method'         => '_listDistributorAssignProduct'
			);

			$pdt_list = avul_call(API_URL . 'assignproduct/api/list_assign_product', $where_1);
			$pdt_val  = $pdt_list['data'];

			// Unit List
			$where_2 = array(
				'method'    => '_listUnit'
			);

			$unit_list = avul_call(API_URL . 'master/api/unit', $where_2);
			$unit_val  = $unit_list['data'];

			$page['product_val']  = $pdt_val;
			$page['unit_val']     = $unit_val;
			$page['main_heading'] = "Purchase";
			$page['sub_heading']  = "Purchase";
			$page['pre_title']    = "List Purchase";
			$page['pre_menu']     = "index.php/distributors/purchase/list_purchase_return";
			$data['page_temp']    = $this->load->view('distributors/purchase/add_purchase_return', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "add_purchase_return";
			$this->bassthaya->load_distributors_form_template($data);
		}
	}

	public function list_purchase_return($param1 = "", $param2 = "", $param3 = "")
	{
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');

		$order_id       = $this->input->post('order_id');
		$method         = $this->input->post('method');
		$distributor_id = $this->session->userdata('id');

		if ($method == 'changeOrder_process') {
			$order_data = array(
				'auto_id'  => $order_id,
				'progress' => '5',
				'method'   => '_changePurchaseReturn',
			);

			$data_save = avul_call(API_URL . 'distributorpurchase/api/manage_purchase_return', $order_data);

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
		}

		if ($param1 == '') {
			$page['main_heading'] = "Purchase";
			$page['sub_heading']  = "Purchase";
			$page['page_title']   = "List Purchase Return";
			$page['pre_title']    = "Add Purchase Return";
			$page['pre_menu']     = "index.php/admin/purchase/add_purchase_return";
			$data['page_temp']    = $this->load->view('distributors/purchase/list_purchase_return', $page, TRUE);
			$data['view_file']    = "Page_Template";
			$data['currentmenu']  = "list_purchase_return";
			$this->bassthaya->load_distributors_form_template($data);
		} else if ($param1 == 'data_list') {
			$limit    = $this->input->post('limitval');
			$page     = $this->input->post('page');
			$search   = $this->input->post('search');
			$cur_page = isset($page) ? $page : '1';
			$_offset  = ($cur_page - 1) * $limit;
			$nxt_page = $cur_page + 1;
			$pre_page = $cur_page - 1;

			$where = array(
				'offset'          => $_offset,
				'limit'           => $limit,
				'search'          => $search,
				'distributor_id'  => $distributor_id,
				'financial_year'  => $this->session->userdata('active_year'),
				'method'          => '_listPurchaseReturnPaginate'
			);

			$data_list  = avul_call(API_URL . 'distributorpurchase/api/manage_purchase_return', $where);
			$data_value = !empty($data_list['data']) ? $data_list['data'] : '';

			if (!empty($data_value)) {
				$count    = count($data_value);
				$total    = isset($data_list['total_record']) ? $data_list['total_record'] : '';
				$tot_page = ceil($total / $limit);

				$status  = 1;
				$message = 'Success';
				$table   = '';

				$i = 1;
				foreach ($data_value as $key => $val) {

					$order_id         = !empty($val['order_id']) ? $val['order_id'] : '';
					$order_no         = !empty($val['order_no']) ? $val['order_no'] : '';
					$distributor_id   = !empty($val['distributor_id']) ? $val['distributor_id'] : '';
					$distributor_name = !empty($val['distributor_name']) ? $val['distributor_name'] : '';
					$order_date       = !empty($val['order_date']) ? $val['order_date'] : '';
					$order_status     = !empty($val['order_status']) ? $val['order_status'] : '';
					$financial_year   = !empty($val['financial_year']) ? $val['financial_year'] : '';
					$bill             = !empty($val['bill']) ? $val['bill'] : '';
					$published        = !empty($val['published']) ? $val['published'] : '';
					$active_status    = !empty($val['status']) ? $val['status'] : '';
					$createdate       = !empty($val['createdate']) ? $val['createdate'] : '';

					if ($active_status == '1') {
						$status_view = '<span class="badge badge-success">Active</span>';
					} else {
						$status_view = '<span class="badge badge-danger">In Active</span>';
					}

					// Order Status
					$order_btn = '<a class="button_clr btn btn-success"><i class="la la-check-square-o"></i> </a>';
					if ($order_status == '1') {
						$order_view = '<span class="badge badge-success">Success</span>';
						$order_btn  = '<a class="button_clr btn btn-warning process_bth" data-value="distributors" data-cntrl="purchase" data-func="list_purchase_return" data-id="' . $order_id . '" data-method="changeOrder_process"><i class="ft-edit"></i> </a>';
					} else if ($order_status == '2') {
						$order_view = '<span class="badge badge-warning">Process</span>';
					} else if ($order_status == '3') {
						$order_view = '<span class="badge badge-primary">Packing</span>';
					} else if ($order_status == '4') {
						$order_view = '<span class="badge badge-info">Delivery</span>';
					} else if ($order_status == '5') {
						$order_view = '<span class="badge badge-success">Complete</span>';
					} else {
						$order_view = '<span class="badge badge-danger">Cancel</span>';
					}

					$table .= '
					    	<tr>
                                <td class="line_height">' . $i . '</td>
                                <td class="line_height">' . $order_no . '</td>
                                <td class="line_height">' . mb_strimwidth($distributor_name, 0, 15, '...') . '</td>
                                <td class="line_height">' . $order_date . '</td>
                                <td class="line_height">' . $order_view . '</td>
                                <td>
                                	' . $order_btn . '
                                	<a href="' . BASE_URL . 'index.php/distributors/purchase/list_purchase_return/View/' . $order_id . '" class="button_clr btn btn-primary"><i class="ft-file-text"></i></a>
                                </td>
                            </tr>
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

			$response['status']     = $status;
			$response['result']     = $table;
			$response['message']    = $message;
			$response['next']       = $next;
			$response['prev']       = $prev;
			echo json_encode($response);
			return;
		} else if ($param1 == 'View') {

			$distributor_id = $this->session->userdata('id');
			$whereee_1 = array(
				'distributor_id' => $distributor_id,
				'method'   => '_detailDistributors'
			);
	
			$data_val    = avul_call(API_URL . 'distributors/api/distributors', $whereee_1);
			$data_value  = $data_val['data'];	
			$ref_id  = !empty($data_value[0]['ref_id'])?$data_value[0]['ref_id']:'';


			$order_id = $param2;

			$where_1 = array(
				'order_id' => $order_id,
				'method'   => '_detailsPurchaseReturn'
			);

			$data_val = avul_call(API_URL . 'distributorpurchase/api/manage_purchase_return', $where_1);

			// Admin Details
		if($ref_id==0){
			$where_2 = array(
				'user_id' => '1',
				'method'  => '_userDetails'
			);

			$admin_val = avul_call(API_URL . 'user/api/profile_settings', $where_2);
			$admin_data = $admin_val['data'];

			$adm_username = !empty($admin_data['username']) ? $admin_data['username'] : '';
			$adm_mobile   = !empty($admin_data['mobile']) ? $admin_data['mobile'] : '';
			$adm_address  = !empty($admin_data['address']) ? $admin_data['address'] : '';
			$adm_state_id = !empty($admin_data['state_id']) ? $admin_data['state_id'] : '';
			$adm_city_id  = !empty($admin_data['city_id']) ? $admin_data['city_id'] : '';
			$adm_gst_no   = !empty($admin_data['gst_no']) ? $admin_data['gst_no'] : '';
		}else{
			$where_2 = array(
				'distributor_id' => $ref_id,
				'method'  => '_detailDistributors'
			);

			$admindata_val  = avul_call(API_URL . 'distributors/api/distributors', $where_2);
			$admindt_data = $admindata_val['data'];
			$adm_username = !empty($admindt_data[0]['company_name']) ? $admindt_data[0]['company_name'] : '';
			$adm_mobile   = !empty($admindt_data[0]['mobile']) ? $admindt_data[0]['mobile'] : '';
			$adm_address  = !empty($admindt_data[0]['address']) ? $admindt_data[0]['address'] : '';
			$adm_state_id = !empty($admindt_data[0]['state_id']) ? $admindt_data[0]['state_id'] : '';
			$adm_city_id  = !empty($admindt_data[0]['city_id']) ? $admindt_data[0]['city_id'] : '';
			$adm_gst_no   = !empty($admindt_data[0]['gst_no']) ? $admindt_data[0]['gst_no'] : '';
			
		


		}
		$admin_val=[];
		$admin_val=array(
			'username'    => $adm_username,
			'mobile'    =>  $adm_mobile, 
			'address'  =>   $adm_address,
			'state_id'  =>   $adm_state_id,
			'city_id' =>  $adm_city_id,
			'gst_no'  => $adm_gst_no,
		);

			$page['admin_data']    = $admin_val;
			$page['return_data']   = $data_val['data'];
			$page['main_heading']  = "Purchase";
			$page['sub_heading']   = "Manage Purchase Return";
			$page['page_title']    = "Purchase Invoice";
			$page['pre_title']     = "Purchase";
			$page['pre_menu']      = "index.php/distributors/purchase/list_purchase_return";
			$data['page_temp']     = $this->load->view('distributors/purchase/view_purchase_return', $page, TRUE);
			$data['view_file']     = "Page_Template";
			$data['currentmenu']   = "list_purchase_return";
			$this->bassthaya->load_distributors_form_template($data);
		} else if ($param1 == 'changeOrder_process') {
			$error = FALSE;
			$order_id     = $this->input->post('order_id');
			$order_status = $this->input->post('order_status');
			$message      = $this->input->post('message');

			$required = array('order_id', 'order_status');
			if ($order_status == 8) {
				array_push($required, 'message');
			}
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
				$order_data = array(
					'auto_id'  => $order_id,
					'progress' => $order_status,
					'reason'   => $message,
					'method'   => '_changePurchaseReturn',
				);

				$data_save = avul_call(API_URL . 'distributorpurchase/api/manage_purchase_return', $order_data);

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
			}
		}
	}

	public function print_return($param1 = "", $param2 = "", $param3 = "")
	{
		
		if ($this->session->userdata('random_value') == '')
			redirect(base_url() . 'index.php?login', 'refresh');


		$distributor_id = $this->session->userdata('id');
		$whereee_1 = array(
			'distributor_id' => $distributor_id,
			'method'   => '_detailDistributors'
		);

		$data_val    = avul_call(API_URL . 'distributors/api/distributors', $whereee_1);
		$data_value  = $data_val['data'];	
		$ref_id  = !empty($data_value[0]['ref_id'])?$data_value[0]['ref_id']:'';
		
		


		$order_id = $param1;

		$where_1 = array(
			'order_id' => $order_id,
			'method'   => '_detailsPurchaseReturn'
		);

		$data_val    = avul_call(API_URL . 'distributorpurchase/api/manage_purchase_return', $where_1);
		$return_data = $data_val['data'];

		// Admin Details
		if ($ref_id == 0) {
			$where_2 = array(
				'user_id' => '1',
				'method'  => '_userDetails'
			);

			$admin_val  = avul_call(API_URL . 'user/api/profile_settings', $where_2);
			$admin_data = $admin_val['data'];

			$adm_username = !empty($admin_data['username']) ? $admin_data['username'] : '';
			$adm_mobile   = !empty($admin_data['mobile']) ? $admin_data['mobile'] : '';
			$adm_address  = !empty($admin_data['address']) ? $admin_data['address'] : '';
			$adm_state_id = !empty($admin_data['state_id']) ? $admin_data['state_id'] : '';
			$adm_city_id  = !empty($admin_data['city_id']) ? $admin_data['city_id'] : '';
			$adm_gst_no   = !empty($admin_data['gst_no']) ? $admin_data['gst_no'] : '';
		} 
		else 
		{
			$where_2 = array(
				'distributor_id' => $ref_id,
				'method'  => '_detailDistributors'
			);

			$admin_val  = avul_call(API_URL . 'distributors/api/distributors', $where_2);
			$admin_data = $admin_val['data'];

			$adm_username = !empty($admin_data[0]['company_name']) ? $admin_data[0]['company_name'] : '';
			$adm_mobile   = !empty($admin_data[0]['mobile']) ? $admin_data[0]['mobile'] : '';
			$adm_address  = !empty($admin_data[0]['address']) ? $admin_data[0]['address'] : '';
			$adm_state_id = !empty($admin_data[0]['state_id']) ? $admin_data[0]['state_id'] : '';

			$adm_gst_no   = !empty($admin_data[0]['gst_no']) ? $admin_data[0]['gst_no'] : '';
		}
		$distributor_details = !empty($return_data['distributor_details']) ? $return_data['distributor_details'] : '';
		$order_details       = !empty($return_data['order_details']) ? $return_data['order_details'] : '';

		$usr_order_id     = !empty($distributor_details['order_id']) ? $distributor_details['order_id'] : '';
		$usr_order_no     = !empty($distributor_details['order_no']) ? $distributor_details['order_no'] : '';
		$usr_return_no    = !empty($distributor_details['return_no']) ? $distributor_details['return_no'] : '';
		$usr_order_status = !empty($distributor_details['order_status']) ? $distributor_details['order_status'] : '';
		$usr_reason       = !empty($distributor_details['reason']) ? $distributor_details['reason'] : '';
		$usr_ordered      = !empty($distributor_details['ordered']) ? $distributor_details['ordered'] : '---';
		$usr_complete     = !empty($distributor_details['complete']) ? $distributor_details['complete'] : '---';
		$usr_canceled     = !empty($distributor_details['canceled']) ? $distributor_details['canceled'] : '---';
		$usr_distri_name  = !empty($distributor_details['distri_name']) ? $distributor_details['distri_name'] : '';
		$usr_contact_no   = !empty($distributor_details['contact_no']) ? $distributor_details['contact_no'] : '';
		$usr_address      = !empty($distributor_details['address']) ? $distributor_details['address'] : '';
		$usr_gst_no       = !empty($distributor_details['gst_no']) ? $distributor_details['gst_no'] : '';
		$usr_state_id     = !empty($distributor_details['state_id']) ? $distributor_details['state_id'] : '';

		$this->load->library('Pdf');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'mm', array(216, 356), TRUE, 'UTF-8', FALSE);
		$pdf->SetTitle('Purchase Retuen Order');
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);

		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);

		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->SetFont('');
		$pdf->AddPage('P');
		$html = '';

		$html .= '<p style="color:black; font-size:12px; text-align: center;"><strong style="font-size:18px; padding-bottom:1000px;">' . $usr_distri_name . '</strong><br/>' . $usr_address . '<br>Ph :' . $usr_contact_no . ', GSTIN\UIN :' . $usr_gst_no . '<br><strong style="color:black; text-align:center; font-size:17px;"> PURCHASE RETURN</strong><br></p>';

		$html .= '<br><br><br>
				<table border= "1" cellpadding="1" top="100">
			        <tr>
			            <td rowspan="4" style="font-size:12px; width: 55%; margin-left:10px;">To: <br> ' . $adm_username . '<br> ' . $adm_address . '<br> Ph : ' . $adm_mobile . '<br> GSTIN\UIN : ' . $adm_gst_no . '</td>
			            <td style="font-size:12px; width: 20%;"> Order No</td>
			            <td style="font-size:12px; width: 25%;">' . $usr_order_no . '</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Order Date</td>
			            <td style="font-size:12px; width: 25%;">' . date('d-M-Y', strtotime($usr_ordered)) . '</td>
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

		$html .= '<br><br>
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
			$description = !empty($val['description']) ? $val['description'] : '';
			$gst_value   = !empty($val['gst_value']) ? $val['gst_value'] : '0';
			$hsn_code    = !empty($val['hsn_code']) ? $val['hsn_code'] : '';
			$pdt_price   = !empty($val['product_price']) ? $val['product_price'] : '0';
			$pdt_qty     = !empty($val['product_qty']) ? $val['product_qty'] : '0';

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
				            <td style="font-size:12px; width: 5%;">' . $num . '</td>
				            <td style="font-size:12px; width: 44%;">' . $description . '</td>
				            <td style="font-size:12px; text-align: center; width: 10%;">' . $hsn_code . '</td>
				            <td style="font-size:12px; text-align: center; width: 10%;">' . $pdt_price . '</td>
				            <td style="font-size:12px; text-align: center; width: 8%;">' . $pdt_qty . '</td>
				            <td style="font-size:12px; text-align: center; width: 8%;">nos</td>
				            <td style="font-size:12px; text-align: right; width: 15%;">' . number_format((float)$tot_price, 2, '.', '') . '</td>
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
		                <td style="font-size:12px; text-align: right;"> ' . $tot_qty . '</td>
		            </tr>
		             <tr>
		                <td colspan="2" style="font-size:12px; text-align: right;">Sub Total</td>
		                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$sub_tot, 2, '.', '') . '</td>
		            </tr>';
		if ($adm_state_id == $usr_state_id) {
			$gst_value = $tot_gst / 2;

			$html .= '
		            		<tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">SGST</td>
				                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$gst_value, 2, '.', '') . '</td>
				            </tr>
				            <tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">CGST</td>
				                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$gst_value, 2, '.', '') . '</td>
				            </tr>
		            	';
		} else {
			$html .= '
		            		<tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">IGST</td>
				                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$tot_gst, 2, '.', '') . '</td>
				            </tr>
		            	';
		}

		$html .= '<tr>
		                <td colspan="2" style="font-size:12px; text-align: right;">Round off</td>
		                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$rond_total, 2, '.', '') . '</td>
		            </tr>
		            <tr>
		                <td colspan="2" style="font-size:12px; text-align: right;">Net Total</td>
		                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$net_value, 2, '.', '') . '</td>
		            </tr>';

		$html .= '</table>';

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->Output($usr_distri_name . '_' . $usr_order_no . '_' . date('d-F-Y') . '.pdf', 'I');
	}

	public function print_invoice($param1 = "", $param2 = "", $param3 = "")
	{
		$whr_1 = array(
			'inv_random' => $param1,
			'method'     => '_distributorPrintInvoice',
		);

		$data_val  = avul_call(API_URL . 'distributorpurchase/api/distributor_invoice', $whr_1);
		$data_res  = $data_val['data'];

		if ($data_res) {
			$invoice_det = !empty($data_res['invoice_details']) ? $data_res['invoice_details'] : '';
			$admin_det   = !empty($data_res['admin_details']) ? $data_res['admin_details'] : '';
			$product_det = !empty($data_res['product_details']) ? $data_res['product_details'] : '';
			$tax_det     = !empty($data_res['tax_details']) ? $data_res['tax_details'] : '';
			$return_det  = !empty($data_res['return_details']) ? $data_res['return_details'] : '';

			// Return Details
			$return_total = !empty($return_det['return_total']) ? $return_det['return_total'] : '0';

			// Invocie Details
			$invoice_no   = !empty($invoice_det['invoice_no']) ? $invoice_det['invoice_no'] : '';
			$invoice_date = !empty($invoice_det['invoice_date']) ? $invoice_det['invoice_date'] : '';
			$po_no        = !empty($invoice_det['po_no']) ? $invoice_det['po_no'] : '';
			$order_date   = !empty($invoice_det['order_date']) ? $invoice_det['order_date'] : '';
			$company_name = !empty($invoice_det['company_name']) ? $invoice_det['company_name'] : '';
			$mobile       = !empty($invoice_det['mobile']) ? $invoice_det['mobile'] : '';
			$email        = !empty($invoice_det['email']) ? $invoice_det['email'] : '';
			$state_id     = !empty($invoice_det['state_id']) ? $invoice_det['state_id'] : '';
			$gst_no       = !empty($invoice_det['gst_no']) ? $invoice_det['gst_no'] : '';
			$address      = !empty($invoice_det['address']) ? $invoice_det['address'] : '';

			// Admin Details
			$adm_username = !empty($admin_det['username']) ? $admin_det['username'] : '';
			$adm_mobile   = !empty($admin_det['mobile']) ? $admin_det['mobile'] : '';
			$adm_address  = !empty($admin_det['address']) ? $admin_det['address'] : '';
			$adm_gst_no   = !empty($admin_det['gst_no']) ? $admin_det['gst_no'] : '';
			$adm_state_id = !empty($admin_det['state_id']) ? $admin_det['state_id'] : '';

			$this->load->library('Pdf');
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'mm', array(216, 356), TRUE, 'UTF-8', FALSE);
			$pdf->SetTitle('Manufacturer Invice');
			$pdf->SetPrintHeader(false);
			$pdf->SetPrintFooter(false);

			$pdf->SetPrintHeader(false);
			$pdf->SetPrintFooter(false);

			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			$pdf->SetFont('');
			$pdf->AddPage('P');
			$html = '';

			$html .= '<p style="color:black; font-size:12px; text-align: center;"><strong style="font-size:18px; padding-bottom:1000px;">' . $adm_username . '</strong><br/>' . $adm_address . ', Contact No: ' . $adm_mobile . '<br>GSTIN\UIN : ' . $adm_gst_no . '<br><strong style="color:black; text-align:center; font-size:17px;"> TAX INVOICE</strong><br></p>';

			$html .= '<br><br><br>
					<table border= "1" cellpadding="1" top="100">
				        <tr>
				            <td rowspan="6" style="font-size:12px; width: 55%; margin-left:10px;">Shipped To: <br> ' . $company_name . '<br> ' . $address . '<br> Contact No: ' . $mobile . '<br> GSTIN\UIN :' . $gst_no . '</td>
				            <td style="font-size:12px; width: 20%;"> Invoice No</td>
				            <td style="font-size:12px; width: 25%;"> ' . $invoice_no . '</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Invoice Date</td>
				            <td style="font-size:12px; width: 25%;"> ' . date('d-M-Y', strtotime($invoice_date)) . '</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Distributor(s) Order No</td>
				            <td style="font-size:12px; width: 25%;"> ' . $po_no . '</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Distributor(s) Order Date</td>
				            <td style="font-size:12px; width: 25%;"> ' . date('d-M-Y', strtotime($order_date)) . '</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Bill Type</td>
				            <td style="font-size:12px; width: 25%;"> </td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Due Days</td>
				            <td style="font-size:12px; width: 25%;"> </td>
				        </tr>
				    </table>
				    ';

			$html .= '<br><br>
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
				$description = !empty($val['description']) ? $val['description'] : '';
				$hsn_code    = !empty($val['hsn_code']) ? $val['hsn_code'] : '';
				$gst_value   = !empty($val['gst_val']) ? $val['gst_val'] : '0';
				$price       = !empty($val['price']) ? $val['price'] : '0';
				$order_qty   = !empty($val['order_qty']) ? $val['order_qty'] : '0';

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
						            <td style="font-size:12px; width: 5%;">' . $num . '</td>
						            <td style="font-size:12px; width: 44%;">' . $description . '</td>
						            <td style="font-size:12px; text-align: center; width: 10%;">' . $hsn_code . '</td>
						            <td style="font-size:12px; text-align: center; width: 10%;">' . number_format((float)$price_val, 2, '.', '') . '</td>
						            <td style="font-size:12px; text-align: center; width: 8%;">' . $order_qty . '</td>
						            <td style="font-size:12px; text-align: center; width: 8%;">nos</td>
						            <td style="font-size:12px; text-align: center; width: 15%;">' . number_format((float)$tot_price, 2, '.', '') . '</td>
						        </tr>
		                    ';

				$num++;
			}

			$rowspan = '7';
			if ($adm_state_id == $state_id) {
				$rowspan = '8';
			}

			// Round Val Details
			$total_amt  = $net_tot - $return_total;
			$net_value  = round($total_amt);
			$rond_total = $net_value - $total_amt;

			$html .= '
		            <tr>
		                <td rowspan ="' . $rowspan . '"  colspan="4"></td>
		                <td colspan="2" style="font-size:12px; text-align: right;">Qty</td>
		                <td style="font-size:12px; text-align: right;"> ' . $tot_qty . '</td>
		                
		            </tr>
		            <tr>
		                <td colspan="2" style="font-size:12px; text-align: right;">Sub Total</td>
		                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$sub_tot, 2, '.', '') . '</td>
		            </tr>';
			if ($adm_state_id == $state_id) {
				$gst_value = $tot_gst / 2;

				$html .= '
		            		<tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">SGST</td>
				                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$gst_value, 2, '.', '') . '</td>
				            </tr>
				            <tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">CGST</td>
				                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$gst_value, 2, '.', '') . '</td>
				            </tr>
		            	';
			} else {
				$html .= '
		            		<tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">IGST</td>
				                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$tot_gst, 2, '.', '') . '</td>
				            </tr>
		            	';
			}

			if ($return_total != 0) {
				$return_data = round($return_total);

				$html .= '<tr>
			                <td colspan="2" style="font-size:12px; text-align: right;">Credit Note</td>
			                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$return_data, 2, '.', '') . '</td>
			            </tr>';
			}

			$html .= '<tr>
		                <td colspan="2" style="font-size:12px; text-align: right;">Round off</td>
		                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$rond_total, 2, '.', '') . '</td>
		            </tr>
		            <tr>
		                <td colspan="2" style="font-size:12px; text-align: right;">Net Total</td>
		                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$net_value, 2, '.', '') . '</td>
		            </tr>';
			$html .= '</table>';

			$html .= '<br><br>
					<table border= "1" cellpadding="1" top="100">
				        <tr>
				            <td rowspan="2" style="font-size:12px; width: 10%;">HSN</td>
				            <td rowspan="2" style="font-size:12px; width: 15%;">Taxable Value</td>';
			if ($adm_state_id == $state_id) {
				$html .= '
				            		<td colspan="2" style="font-size:12px; width: 30%; text-align:center;">CGST</td>
				            		<td colspan="2" style="font-size:12px; width: 30%; text-align:center;">SGST</td>
				            	';
			} else {
				$html .= '
				            		<td colspan="2" style="font-size:12px; width: 60%; text-align:center;">IGST</td>
				            	';
			}

			$html .= '<td rowspan="2" style="font-size:12px; width: 15%;">Total Tax Amount</td>
				        </tr>
				        <tr>';
			if ($adm_state_id == $state_id) {
				$html .= '
				        			<td style="font-size: 12px; text-align:center;">Rate</td>
						            <td style="font-size: 12px; text-align:center;">Amt</td>
						            <td style="font-size: 12px; text-align:center;">Rate</td>
						            <td style="font-size: 12px; text-align:center;">Amt</td>
				        		';
			} else {
				$html .= '
				        			<td style="font-size: 12px; text-align:center;">Rate</td>
				            		<td style="font-size: 12px; text-align:center;">Amt</td>
				        		';
			}
			$html .= '</tr>';
			$tot_price = 0;
			$tot_gst   = 0;
			foreach ($tax_det as $key => $value) {
				$hsn_code    = !empty($value['hsn_code']) ? $value['hsn_code'] : '';
				$gst_val     = !empty($value['gst_val']) ? $value['gst_val'] : '0';
				$gst_value   = !empty($value['gst_value']) ? $value['gst_value'] : '0';
				$price_value = !empty($value['price_value']) ? $value['price_value'] : '0';

				$tot_gst    += $gst_value;
				$tot_price  += $price_value;

				$html .= '
		                    	<tr>
		                    		<td style="font-size: 12px; text-align:left;"> ' . $hsn_code . '</td>
		                    		<td style="font-size: 12px; text-align:left;"> ' . number_format((float)$price_value, 2, '.', '') . '</td>';
				if ($adm_state_id == $state_id) {
					$state_value = $gst_value / 2;
					$gst_calc    = $gst_val / 2;
					$html .= '
		                    				<td style="font-size: 12px; text-align:left;"> ' . $gst_calc . ' %</td>
				                    		<td style="font-size: 12px; text-align:left;"> ' . number_format((float)$state_value, 2, '.', '') . '</td>
				                    		<td style="font-size: 12px; text-align:left;"> ' . $gst_calc . ' %</td>
				                    		<td style="font-size: 12px; text-align:left;"> ' . number_format((float)$state_value, 2, '.', '') . '</td>
		                    			';
				} else {
					$html .= '
		                    				<td style="font-size: 12px; text-align:left;"> ' . $gst_val . ' %</td>
			                    			<td style="font-size: 12px; text-align:left;"> ' . number_format((float)$gst_value, 2, '.', '') . '</td>
		                    			';
				}
				$html .= '<td style="font-size: 12px; text-align:left;"> ' . number_format((float)$gst_value, 2, '.', '') . '</td>
		                    	</tr>
		                    ';
			}
			$html .= '
				        	<tr>
				        		<td style="font-size: 12px; text-align:right;"> Total </td>
				        		<td style="font-size: 12px; text-align:left;"> ' . number_format((float)$tot_price, 2, '.', '') . '</td>';
			if ($adm_state_id == $state_id) {
				$state_val = $tot_gst / 2;

				$html .= '
					        			<td style="font-size: 12px; text-align:left;"> </td>
						        		<td style="font-size: 12px; text-align:left;"> ' . number_format((float)$state_val, 2, '.', '') . '</td>
						        		<td style="font-size: 12px; text-align:left;"> </td>
						        		<td style="font-size: 12px; text-align:left;"> ' . number_format((float)$state_val, 2, '.', '') . '</td>
					        		';
			} else {
				$html .= '
					        			<td style="font-size: 12px; text-align:left;"> </td>
						        		<td style="font-size: 12px; text-align:left;"> ' . number_format((float)$tot_gst, 2, '.', '') . '</td>
					        		';
			}

			$html .= '<td style="font-size: 12px; text-align:left;"> ' . number_format((float)$tot_gst, 2, '.', '') . '</td>
				        	</tr>
				        ';
			$html .= '</table>';

			$html .= '<br><br>
					<table border= "1" cellpadding="1" top="100">
				        <tr>
				            <td colspan="5" style="font-size:12px; width: 14%;"> Account Name</td>
				            <td colspan="5" style="font-size:12px; width: 36%;"> ' . ACCOUNT_NAME . '</td>
				            <td rowspan="5" style="font-size:12px; width: 50%;">
				            	<span> for ' . $adm_username . '</span>
				            	<br><br><br>
				            	<p style="text-align: right; "> Authorised signature </p>
				            </td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:12px; width: 14%;"> Account No</td>
				            <td colspan="5" style="font-size:12px; width: 36%;"> ' . ACCOUNT_NO . '</td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:12px; width: 14%;"> Bank Name</td>
				            <td colspan="5" style="font-size:12px; width: 36%;"> ' . BANK_NAME . '</td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:12px; width: 14%;"> Branch Name</td>
				            <td colspan="5" style="font-size:12px; width: 36%;"> ' . BRANCH_NAME . '</td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:12px; width: 14%;"> IFSC Code</td>
				            <td colspan="5" style="font-size:12px; width: 36%;"> ' . IFSC_CODE . '</td>
				        </tr>
				    </table>';

			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->Output($company_name . '_' . $invoice_no . '_' . date('d-F-Y') . '.pdf', 'I');
		}
	}

	public function print_order($param1 = "", $param2 = "", $param3 = "")
	{
		$purchase_id = $param1;

		$where = array(
			'purchase_id' => $purchase_id,
			'view_type'   => 1,
			'method'      => '_viewDistributorPurchase'
		);

		$data_list  = avul_call(API_URL . 'distributorpurchase/api/manage_purchase', $where);
		$data_value = !empty($data_list['data']) ? $data_list['data'] : '';

		if ($data_value) {
			$bill_det = !empty($data_value['bill_details']) ? $data_value['bill_details'] : '';
			$adm_det  = !empty($data_value['admin_details']) ? $data_value['admin_details'] : '';
			$dis_det  = !empty($data_value['distributor_details']) ? $data_value['distributor_details'] : '';
			$ord_det  = !empty($data_value['order_details']) ? $data_value['order_details'] : '';

			// Bill Details
			$po_no      = !empty($bill_det['po_no']) ? $bill_det['po_no'] : '';
			$order_date = !empty($bill_det['order_date']) ? $bill_det['order_date'] : '';

			// Admin Details
			$adm_username = !empty($adm_det['username']) ? $adm_det['username'] : '';
			$adm_mobile   = !empty($adm_det['mobile']) ? $adm_det['mobile'] : '';
			$adm_address  = !empty($adm_det['address']) ? $adm_det['address'] : '';
			$adm_gst_no   = !empty($adm_det['gst_no']) ? $adm_det['gst_no'] : '';
			$adm_state_id = !empty($adm_det['state_id']) ? $adm_det['state_id'] : '';

			// Distributor Details
			$company_name = !empty($dis_det['company_name']) ? $dis_det['company_name'] : '';
			$mobile       = !empty($dis_det['mobile']) ? $dis_det['mobile'] : '';
			$email        = !empty($dis_det['email']) ? $dis_det['email'] : '';
			$gst_no       = !empty($dis_det['gst_no']) ? $dis_det['gst_no'] : '';
			$address      = !empty($dis_det['address']) ? $dis_det['address'] : '';
			$state_id     = !empty($dis_det['state_id']) ? $dis_det['state_id'] : '';

			$this->load->library('Pdf');
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'mm', array(216, 356), TRUE, 'UTF-8', FALSE);
			$pdf->SetTitle('Manufacturer Invice');
			$pdf->SetPrintHeader(false);
			$pdf->SetPrintFooter(false);

			$pdf->SetPrintHeader(false);
			$pdf->SetPrintFooter(false);

			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			$pdf->SetFont('');
			$pdf->AddPage('P');
			$html = '';

			$html .= '<p style="color:black; font-size:12px; text-align: center;"><strong style="font-size:18px; padding-bottom:1000px;">' . $adm_username . '</strong><br/>' . $adm_address . ', Contact No: ' . $adm_mobile . '<br>GSTIN\UIN : ' . $adm_gst_no . '<br><strong style="color:black; text-align:center; font-size:17px;"> PROFORMA INVOICE</strong><br></p>';

			$html .= '<br><br><br>
					<table border= "1" cellpadding="1" top="100">
				        <tr>
				            <td rowspan="6" style="font-size:12px; width: 55%; margin-left:10px;">Shipped To: <br> ' . $company_name . '<br> ' . $address . '<br> Contact No: ' . $mobile . '<br> GSTIN\UIN :' . $gst_no . '</td>
				            <td style="font-size:12px; width: 20%;"> Voucher No</td>
				            <td style="font-size:12px; width: 25%;"> ' . $po_no . '</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Voucher Date</td>
				            <td style="font-size:12px; width: 25%;"> ' . date('d-M-Y', strtotime($order_date)) . '</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Buyer\'s Ref No</td>
				            <td style="font-size:12px; width: 25%;"> ' . $po_no . '</td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Bill Type</td>
				            <td style="font-size:12px; width: 25%;"> </td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Due Days</td>
				            <td style="font-size:12px; width: 25%;"> </td>
				        </tr>
				        <tr>
				            <td style="font-size:12px; width: 20%;"> Other Reference(s)</td>
				            <td style="font-size:12px; width: 25%;"> </td>
				        </tr>
				    </table>
				    ';

			$html .= '<br><br>
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
				$description = !empty($val['description']) ? $val['description'] : '';
				$hsn_code    = !empty($val['hsn_code']) ? $val['hsn_code'] : '';
				$gst_value   = !empty($val['gst_val']) ? $val['gst_val'] : '0';
				$price       = !empty($val['product_price']) ? $val['product_price'] : '0';
				$order_qty   = !empty($val['product_qty']) ? $val['product_qty'] : '0';

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
						            <td style="font-size:12px; width: 5%;"> ' . $num . '</td>
						            <td style="font-size:12px; width: 44%;"> ' . $description . '</td>
						            <td style="font-size:12px; text-align: center; width: 10%;">' . $hsn_code . '</td>
						            <td style="font-size:12px; text-align: center; width: 10%;">' . number_format((float)$price_val, 2, '.', '') . '</td>
						            <td style="font-size:12px; text-align: center; width: 8%;">' . $order_qty . '</td>
						            <td style="font-size:12px; text-align: center; width: 8%;">nos</td>
						            <td style="font-size:12px; text-align: center; width: 15%;">' . number_format((float)$tot_price, 2, '.', '') . '</td>
						        </tr>
		                    ';

				$num++;
			}

			$rowspan = '7';
			if ($adm_state_id == $state_id) {
				$rowspan = '8';
			}

			// Round Val Details
			$net_value  = round($net_tot);
			$rond_total = $net_value - $net_tot;

			$html .= '
		            <tr>
		                <td rowspan ="' . $rowspan . '"  colspan="4"></td>
		                <td colspan="2" style="font-size:12px; text-align: right;" class="text-right">Qty</td>
		                <td style="font-size:12px; text-align: right;"> ' . $tot_qty . '</td>
		                
		            </tr>
		            <tr>
		                <td colspan="2" style="font-size:12px; text-align: right;">Sub Total</td>
		                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$sub_tot, 2, '.', '') . '</td>
		            </tr>';
			if ($adm_state_id == $state_id) {
				$gst_value = $tot_gst / 2;

				$html .= '
		            		<tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">SGST</td>
				                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$gst_value, 2, '.', '') . '</td>
				            </tr>
				            <tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">CGST</td>
				                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$gst_value, 2, '.', '') . '</td>
				            </tr>
		            	';
			} else {
				$html .= '
		            		<tr>
				                <td colspan="2" style="font-size:12px; text-align: right;">IGST</td>
				                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$tot_gst, 2, '.', '') . '</td>
				            </tr>
		            	';
			}

			$html .= '<tr>
		                <td colspan="2" style="font-size:12px; text-align: right;">Round off</td>
		                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$rond_total, 2, '.', '') . '</td>
		            </tr>
		            <tr>
		                <td colspan="2" style="font-size:12px; text-align: right;">Net Total</td>
		                <td style="font-size:12px; text-align: right;"> ' . number_format((float)$net_value, 2, '.', '') . '</td>
		            </tr>';
			$html .= '</table>';

			$html .= '<br><br>
					<table border= "1" cellpadding="1" top="100">
				        <tr>
				            <td colspan="5" style="font-size:12px; width: 14%;"> Account Name</td>
				            <td colspan="5" style="font-size:12px; width: 36%;"> ' . ACCOUNT_NAME . '</td>
				            <td rowspan="5" style="font-size:12px; width: 50%;">
				            	<span> for ' . $adm_username . '</span>
				            	<br><br><br>
				            	<p style="text-align: right; "> Authorised signature </p>
				            </td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:12px; width: 14%;"> Account No</td>
				            <td colspan="5" style="font-size:12px; width: 36%;"> ' . ACCOUNT_NO . '</td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:12px; width: 14%;"> Bank Name</td>
				            <td colspan="5" style="font-size:12px; width: 36%;"> ' . BANK_NAME . '</td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:12px; width: 14%;"> Branch Name</td>
				            <td colspan="5" style="font-size:12px; width: 36%;"> ' . BRANCH_NAME . '</td>
				        </tr>
				        <tr>
				        	<td colspan="5" style="font-size:12px; width: 14%;"> IFSC Code</td>
				            <td colspan="5" style="font-size:12px; width: 36%;"> ' . IFSC_CODE . '</td>
				        </tr>
				    </table>';

			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->Output($company_name . '_' . $po_no . '_' . date('d-F-Y') . '.pdf', 'I');
		}
	}
}

<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Salesreturn extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
		}
		
		public function add_sales_return($param1="", $param2="", $param3="")
		{
			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;
				$outlet_id      = $this->input->post('outlet_id');
				$invoice_id     = $this->input->post('invoice_id');
				$return_details = $this->input->post('return_details');
				$category_id    = $this->input->post('category_id');
				$type_id        = $this->input->post('type_id');
				$product_price  = $this->input->post('product_price');
				$product_qty    = $this->input->post('product_qty');
				$unit_id        = $this->input->post('unit_id');

				$required = array('outlet_id', 'return_details', 'invoice_id');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if(count(array_filter($category_id))!==count($category_id) || count(array_filter($type_id))!==count($type_id) || count(array_filter($product_qty))!==count($product_qty) || $error == TRUE)
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
			    		$return_type  = [];
			    		$return_count = count($category_id);

			    		for($j = 0; $j < $return_count; $j++)
			    		{
			    			$return_type[] = array(
			    				'category_id'   => $category_id[$j],
			    				'type_id'       => $type_id[$j],
			    				'product_price' => $product_price[$j],
			    				'product_qty'   => $product_qty[$j],
			    				'product_unit'  => $unit_id[$j],
			    			);
			    		}

			    		$return_value = json_encode($return_type);

			    		$data = array(
					    	'outlet_id'        => $outlet_id,
					    	'return_details'   => $return_details,
					    	'invoice_id'       => $invoice_id,
					    	'distributor_id'   => $this->session->userdata('id'),
					    	'return_value'     => $return_value,
					    	'active_financial' => $this->session->userdata('active_year'),
					    	'method'           => '_addOutletReturn',
					    );

					    $data_save = avul_call(API_URL.'salesreturn/api/outlet_return',$data);

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

			if($param1 =='getOutlet_details')
			{
				$outlet_id  = $this->input->post('outlet_id');

				$where = array(
            		'outlets_id' => $outlet_id,
            		'method'     => '_detailOutlets'
            	);

            	$outlet_list   = avul_call(API_URL.'outlets/api/outlets',$where);
            	$outlet_result = $outlet_list['data'];

            	$option = array(
            		'contact_no' => $outlet_result[0]['mobile'],
            		'gst_no'     => $outlet_result[0]['gst_no'],
            		'address'    => $outlet_result[0]['address'],
            	);

            	$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 
			} 

			else if($param1 =='_getDistributorInvoice')
			{
				$outlet_id      = $this->input->post('outlet_id');	
				$distributor_id = $this->session->userdata('id');

				$where = array(
					'distributor_id' => $distributor_id,
            		'store_id'       => $outlet_id,
            		'method'         => '_distributorInvoice'
            	);

            	$invoice_list   = avul_call(API_URL.'order/api/invoice_manage_order',$where);
			
            	$invoice_result = $invoice_list['data'];

            	$option ='<option value="">Select Value</option>';

            	if(!empty($invoice_result))
        		{
        			foreach ($invoice_result as $key => $value) {
        				$invoice_id = !empty($value['invoice_id'])?$value['invoice_id']:'';
                        $invoice_no = !empty($value['invoice_no'])?$value['invoice_no']:'';
                        $bill_value = !empty($value['bill_value'])?$value['bill_value']:'0';

                        $option .= '<option value="'.$invoice_id.'">'.$invoice_no.' (Rs.'.$bill_value.')</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
			}

			else if($param1 =='_getDistributorProduct')
			{
				$category_id    = $this->input->post('category_id');
				$distributor_id = $this->session->userdata('id');

				$where = array(
					'category_id'    => $category_id,
					'distributor_id' => $distributor_id,
            		'method'         => '_listDistributorProduct'
            	);

            	$product_list   = avul_call(API_URL.'assignproduct/api/list_distributor_product',$where);
            	$product_result = $product_list['data'];

            	$option ='<option value="">Select Value</option>';

        		if(!empty($product_result))
        		{
        			foreach ($product_result as $key => $value) {
        				$assign_id   = !empty($value['assign_id'])?$value['assign_id']:'';
                        $description = !empty($value['description'])?$value['description']:'';

                        $option .= '<option value='.$assign_id.'>'.$description.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
			}

			else if($param1 =='_getDistributorPdtdetails')
			{
				$type_id    = $this->input->post('type_id');

				$where = array(
					'assign_id' => $type_id,
            		'method'    => '_outletProductDetails'
            	);

            	$product_list = avul_call(API_URL.'assignproduct/api/list_distributor_product',$where);
            	$product_val  = $product_list['data'];

            	$unit_id   = !empty($product_val['product_unit'])?$product_val['product_unit']:'';
                $unit_name = !empty($product_val['unit_name'])?$product_val['unit_name']:'';
                $price     = !empty($product_val['product_price'])?$product_val['product_price']:'0';

                $option = '<option value="'.$unit_id.'">'.$unit_name.'</option>';

                $response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        $response['price']   = $price;
		        echo json_encode($response);
		        return;
			}

			else if($param1 =='getOutletReturn_row')
			{
				$rowCount  = $this->input->post('rowCount');
				$newCount  = $rowCount + 1;

				$distributor_id = $this->session->userdata('id');

				$where = array(
					'distributor_id' => $distributor_id,
            		'method'         => '_listDistributorCategory'
            	);

            	$category_list   = avul_call(API_URL.'assignproduct/api/list_distributor_product',$where);
            	$category_result = $category_list['data'];

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

						    if($(".category_id").length)
						    {
						        $(".category_id").on("change",function(){
						            var category_id = $(this).val();
						            var $row        = $(this).closest("tr");
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
						                url: baseurl+"index.php/"+value+"/"+cntrl+"/"+func+"/_getDistributorPdtdetails",
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
                            <select data-te="'.$newCount.'" name="category_id[]" id="category_id'.$newCount.'" class="form-control category_id'.$newCount.' category_id js-select2-multi">
                                <option value="">Select Product Name</option>';
                                if(!empty($category_result))
                                {
                                    foreach ($category_result as $key => $val_1) {
                                            
                                        $cat_id = !empty($val_1['category_id'])?$val_1['category_id']:'';
                                        $cat_name = !empty($val_1['category_name'])?$val_1['category_name']:'';

                                        $option .= '<option value="'.$cat_id.'">'.$cat_name.'</option>';
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

			else
			{
				$distributor_id = $this->session->userdata('id');

				$where = array(
					'distributor_id' => $distributor_id,
            		'method'         => '_listDistributorCategory'
            	);

            	$category_list   = avul_call(API_URL.'assignproduct/api/list_distributor_product',$where);
            	$category_result = $category_list['data'];

				$page['dataval']    = '';
				$page['cat_val']    = $category_result;
				$page['method']     = 'BTBM_X_C';
				$page['page_title'] = "Add Sales Return";

				$where_1 = array(
            		'distributor_id' => $this->session->userdata('id'),
            		'method'         => '_listDistributorOutlets'
            	);

            	$data_list   = avul_call(API_URL.'outlets/api/distributor_outlet_list',$where_1);
            	$outlet_list = !empty($data_list['data'])?$data_list['data']:'';

            	$where_2 = array(
            		'method'    => '_listUnit'
            	);

	            $unit_list  = avul_call(API_URL.'master/api/unit',$where_2);
	            $unit_val   = $unit_list['data'];

            	$page['unit_val']     = $unit_list['data'];
            	$page['outlet_val']   = $outlet_list;
            	$page['main_heading'] = "Sales Return";
				$page['sub_heading']  = "Sales Return";
				$page['pre_title']    = "List Sales Return";
				$page['pre_menu']     = "index.php/distributors/salesreturn/list_sales_return";
				$data['page_temp']    = $this->load->view('distributors/salesreturn/add_sales_return',$page,TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "add_sales_return";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}
	
		public function list_sales_return($param1="", $param2="", $param3="")
		{
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Sales Return";
				$page['sub_heading']  = "Sales Return";
				$page['page_title']   = "List Sales Return";
				$page['pre_title']    = "Add Sales Return";
				$page['pre_menu']     = "index.php/distributors/salesreturn/add_sales_return";
				$data['page_temp']    = $this->load->view('distributors/salesreturn/list_sales_return',$page, TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_sales_return";
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
            		'distributor_id'  => $this->session->userdata('id'),
            		'financial_id'    => $this->session->userdata('active_year'),
            		'method'          => '_manageOutletReturnPagination'
            	);

            	$data_list  = avul_call(API_URL.'salesreturn/api/outlet_return',$where);
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

			            $return_no    = !empty($value['return_no'])?$value['return_no']:'';
			            $store_name   = !empty($value['store_name'])?$value['store_name']:'';
			            $random_value = !empty($value['random_value'])?$value['random_value']:'';
			            $date        = !empty($value['date'])?$value['date']:'';

					    $table .= '
					    	<tr>
                                <td class="line_height">'.$i.'</td>
                                <td class="line_height">'.$return_no.'</td>
                                <td class="line_height">'.$store_name.'</td>
                                <td class="line_height">'.$date.'</td>
                                <td>
                                	<a href="'.BASE_URL.'index.php/distributors/salesreturn/list_sales_return/View/'.$random_value.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a>
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

			else if($param1 == 'View')
			{
				$where = array(
            		'return_value' => $param2,
            		'method'       => '_outletReturnDetails'
            	);

            	// print_r($where); exit;

            	$data_list  = avul_call(API_URL.'salesreturn/api/outlet_return',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['return_data']   = $data_value;
				$page['main_heading']  = "Sales Return";
				$page['sub_heading']   = "Sales Return";
				$page['page_title']    = "Sales Return";
				$page['pre_title']     = "";
				$page['pre_menu']      = "index.php/distributors/salesreturn/list_sales_return";
				$data['page_temp']     = $this->load->view('distributors/salesreturn/view_sales_return',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "list_sales_return";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'print_invoice')
			{
				$where = array(
            		'return_value' => $param2,
            		'method'       => '_outletReturnDetails'
            	);

            	$data_list  = avul_call(API_URL.'salesreturn/api/outlet_return',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$distributor_det = !empty($data_value['distributor_details'])?$data_value['distributor_details']:'';
				$store_det       = !empty($data_value['store_details'])?$data_value['store_details']:'';
				$product_det     = !empty($data_value['product_details'])?$data_value['product_details']:'';
				$tax_det         = !empty($data_value['tax_details'])?$data_value['tax_details']:'';

				// Distributor Details
				$return_no    = !empty($distributor_det['return_no'])?$distributor_det['return_no']:'';
				$return_date  = !empty($distributor_det['return_date'])?$distributor_det['return_date']:'';
				$return_value = !empty($distributor_det['return_value'])?$distributor_det['return_value']:'';
				$company_name = !empty($distributor_det['company_name'])?$distributor_det['company_name']:'';
			    $dis_mobile   = !empty($distributor_det['dis_mobile'])?$distributor_det['dis_mobile']:'';
			    $dis_email    = !empty($distributor_det['dis_email'])?$distributor_det['dis_email']:'';
			    $dis_state_id = !empty($distributor_det['dis_state_id'])?$distributor_det['dis_state_id']:'';
			    $dis_gst_no   = !empty($distributor_det['dis_gst_no'])?$distributor_det['dis_gst_no']:'';
			    $dis_address  = !empty($distributor_det['dis_address'])?$distributor_det['dis_address']:'';
			    $invoice_no   = !empty($distributor_det['invoice_no'])?$distributor_det['invoice_no']:'';
			    $invoice_date = !empty($distributor_det['invoice_date'])?$distributor_det['invoice_date']:'';

			    // Store Details
			    $store_name   = !empty($store_det['store_name'])?$store_det['store_name']:'';
			    $str_mobile   = !empty($store_det['str_mobile'])?$store_det['str_mobile']:'';
			    $str_email    = !empty($store_det['str_email'])?$store_det['str_email']:'';
			    $str_state_id = !empty($store_det['str_state_id'])?$store_det['str_state_id']:'';
			    $str_gst_no   = !empty($store_det['str_gst_no'])?$store_det['str_gst_no']:'';
			    $str_address  = !empty($store_det['str_address'])?$store_det['str_address']:'';

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

	          	$html .= '<p style="color:black; font-size:12px; text-align: center;"><strong style="font-size:18px; padding-bottom:1000px;">'.$company_name.'</strong><br/>'.$dis_address.', Contact No: '.$dis_mobile.'<br>GSTIN\UIN : '.$dis_gst_no.'<br><strong style="color:black; text-align:center; font-size:17px;"> SALES RETURN</strong><br></p>';

	          	$html .='<br><br><br>
				<table border= "1" cellpadding="1" top="100">
			        <tr>
			            <td rowspan="6" style="font-size:12px; width: 55%; margin-left:10px;">Shipped To: <br> '.$store_name.'<br> '.$str_address.'<br> Contact No: '.$str_mobile.'<br> GSTIN\UIN :'.$str_gst_no.'</td>
			            <td style="font-size:12px; width: 20%;"> Return No</td>
			            <td style="font-size:12px; width: 25%;"> '.$return_no.'</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Return Date</td>
			            <td style="font-size:12px; width: 25%;"> '.date('d-M-Y', strtotime($return_date)).'</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Outlet(s) Invoice No</td>
			            <td style="font-size:12px; width: 25%;"> '.$invoice_no.'</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Outlet(s) Invoice Date</td>
			            <td style="font-size:12px; width: 25%;"> '.date('d-M-Y', strtotime($invoice_date)).'</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Return Type</td>
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
					        foreach ($product_det as $key => $val_1) {
								$description = !empty($val_1['description'])?$val_1['description']:'';
				                $hsn_code    = !empty($val_1['hsn_code'])?$val_1['hsn_code']:'';
				                $gst_value   = !empty($val_1['gst_val'])?$val_1['gst_val']:'0';
				                $pdt_price   = !empty($val_1['price'])?$val_1['price']:'0';
				                $pdt_qty     = !empty($val_1['return_qty'])?$val_1['return_qty']:'0';

			                    $gst_data  = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
			                    $price_val = $pdt_price - $gst_data;
			                    $tot_price = $pdt_qty * $price_val;
			                    $sub_tot  += $tot_price;

			                    // GST Calculation
			                    $gst_val   = $pdt_qty * $gst_data;
			                    $tot_gst  += $gst_val;
			                    $total_val = $pdt_qty * $pdt_price;
			                    $net_tot  += $total_val;
			                    $tot_qty  += $pdt_qty;

			                    $html .= '
			                    	<tr>
							            <td style="font-size:12px; width: 5%;">'.$num.'</td>
							            <td style="font-size:12px; width: 44%;">'.$description.'</td>
							            <td style="font-size:12px; text-align: center; width: 10%;">'.$hsn_code.'</td>
							            <td style="font-size:12px; text-align: center; width: 10%;">'.number_format((float)$price_val, 2, '.', '').'</td>
							            <td style="font-size:12px; text-align: center; width: 8%;">'.$pdt_qty.'</td>
							            <td style="font-size:12px; text-align: center; width: 8%;">nos</td>
							            <td style="font-size:12px; text-align: center; width: 15%;">'.number_format((float)$tot_price, 2, '.', '').'</td>
							        </tr>
			                    ';

			                    $num++;
					        }

		          	$rowspan = '5';
				    if($dis_state_id == $str_state_id)
				    {
				    	$rowspan = '6';
				    }

				    // Round Val Details
                    $last_value = round($net_tot);
                    $rond_total = $last_value - $net_tot;

                   	$html .= '
			            <tr>
			                <td rowspan ="'.$rowspan.'"  colspan="4"></td>
			                <td colspan="2" style="font-size:12px;" class="text-right">Qty</td>
			                <td style="font-size:12px;" class="text-right"> '.$tot_qty.'</td>
			                
			            </tr>
			            <tr>
			                <td colspan="2" style="font-size:12px;" class="text-right">Sub Total</td>
			                <td style="font-size:12px;" class="text-right"> '.number_format((float)$sub_tot, 2, '.', '').'</td>
			            </tr>';
			            if($dis_state_id == $str_state_id)
			            {
			            	$gst_value = $tot_gst / 2;

			            	$html .= '
			            		<tr>
					                <td colspan="2" style="font-size:12px;" class="text-right">SGST</td>
					                <td style="font-size:12px;" class="text-right"> '.number_format((float)$gst_value, 2, '.', '').'</td>
					            </tr>
					            <tr>
					                <td colspan="2" style="font-size:12px;" class="text-right">CGST</td>
					                <td style="font-size:12px;" class="text-right"> '.number_format((float)$gst_value, 2, '.', '').'</td>
					            </tr>
			            	';
			            }
			            else
			            {
			            	$html .= '
			            		<tr>
					                <td colspan="2" style="font-size:12px;" class="text-right">IGST</td>
					                <td style="font-size:12px;" class="text-right"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
					            </tr>
			            	';
			            }

			            $html .='<tr>
			                <td colspan="2" style="font-size:12px;" class="text-right">Round off</td>
			                <td style="font-size:12px;" class="text-right"> '.number_format((float)$rond_total, 2, '.', '').'</td>
			            </tr>
			            <tr>
			                <td colspan="2" style="font-size:12px;" class="text-right">Net Total</td>
			                <td style="font-size:12px;" class="text-right"> '.number_format((float)$last_value, 2, '.', '').'</td>
			            </tr>
			            <tr>
			                <td colspan="11" style="font-size:12px;" class="text-right">Amount (in words) : '.numberTowords($last_value).'  rupees only</td>
			            </tr>';
				    $html .='</table>';

				    $html .='<br><br>
					<table border= "1" cellpadding="1" top="100">
				        <tr>
				            <td rowspan="2" style="font-size:12px; width: 10%;">HSN</td>
				            <td rowspan="2" style="font-size:12px; width: 15%;">Taxable Value</td>';
				            if($dis_state_id == $str_state_id)
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
				        	if($dis_state_id == $str_state_id)
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
		                    		if($dis_state_id == $str_state_id)
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
				        		if($dis_state_id == $str_state_id)
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
				            <td colspan="5" style="font-size:12px; width: 50%;">
				            	<br><br><br><br><br><br>
				            </td>
				            <td rowspan="5" style="font-size:12px; width: 50%;">
				            	<span> for '.$company_name.'</span>
				            	<br><br><br>
				            	<p style="text-align: right; "> Authorised signature </p>
				            </td>
				        </tr>
				    </table>';

	          	$pdf->writeHTML($html, true, false, true, false, '');
	       		$pdf->Output($return_no.'_'.date('d-F-Y H:i:s').'.pdf', 'I');
			}
		}
		public function add_distributor_sales_return($param1="", $param2="", $param3="")
		{
			$formpage       = $this->input->post('formpage');
			$method         = $this->input->post('method');

			if($formpage =='BTBM_X_P')
			{
				$error = FALSE;	
				$distributor_id = $this->input->post('distributor_id');
				$invoice_id     = $this->input->post('invoice_id');
				$return_details = $this->input->post('return_details');
    			$product_id     = $this->input->post('product_id');
    			$product_price  = $this->input->post('product_price');
    			$product_qty    = $this->input->post('product_qty');
    			$unit_id        = $this->input->post('unit_id');
    			$method         = $this->input->post('method');

    			$required = array('distributor_id', 'invoice_id', 'return_details');
				foreach ($required as $field) 
			    {
			        if(empty($this->input->post($field)))
			        {
			            $error = TRUE;
			        }
			    }

			    if(count(array_filter($product_id))!==count($product_id) || count(array_filter($product_price))!==count($product_price) || count(array_filter($product_qty))!==count($product_qty) || $error == TRUE)
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
			    	
			    			$return_type  = [];
				    		$return_count = count($product_id);

				    		for($j = 0; $j < $return_count; $j++)
				    		{
				    			$return_type[] = array(
				    				'product_id'    => $product_id[$j],
				    				'product_price' => $product_price[$j],
				    				'product_qty'   => $product_qty[$j],
				    				'product_unit'  => $unit_id[$j],
				    			);
				    		}

				    		$return_value = json_encode($return_type);

				    		$data = array(
								'ref_id'           => $this->session->userdata('id'),
						    	'distributor_id'   => $distributor_id,
						    	'invoice_id'       => $invoice_id,
						    	'return_details'   => $return_details,
						    	'return_value'     => $return_value,
						    	'active_financial' => $this->session->userdata('active_year'),
						    	'method'           => '_addDistributorReturn',
						    );

						    $data_save = avul_call(API_URL.'salesreturn/api/distributor_return',$data);

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

			if($param1 =='getDistributor_row')
			{
				$rowCount  = $this->input->post('rowCount');
				$newCount  = $rowCount + 1;

				$distributor_id = $this->input->post('distributor_id');

				$where_1 = array(
					'distributor_id' => $distributor_id,
					'published'      => '1',
					'status'         => '1',
            		'method'         => '_listDistributorAssignProduct'
            	);

            	$product_list = avul_call(API_URL.'assignproduct/api/list_assign_product',$where_1);
            	$product_val  = $product_list['data'];

				$where_2 = array(
            		'method'    => '_listUnit'
            	);

	            $unit_list = avul_call(API_URL.'master/api/unit',$where_2);
	            $unit_val  = $unit_list['data'];

	            $option = '
					<tr class="row_1">

						<script src="'.BASE_URL.'app-assets/js/select2.full.js"></script>
						<script>
							var baseurl = $(".geturl").val();

							if($(".js-select2-multi").length)
						    {
						        $(".js-select2-multi").select2({
						            placeholder: "Select Value",
						        });
						    }

						    $(document).on("change", ".product_id", function () {

					            var $row           = $(this).closest("tr");
					            var distributor_id = $("#distributor_id").val();
					            var product_id     = $(this).val();
					            var value          = $("#value").val();
					            var cntrl          = $("#cntrl").val();
					            var func           = $("#func").val();

					            $.ajax({
					                method: "POST",
					                data: {
					                    "distributor_id" : distributor_id,
					                    "product_id"     : product_id,
					                },
					                url: baseurl+"index.php/"+value+"/"+cntrl+"/"+func+"/getDisProduct_details",
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

						</script>

	                    <td data-te="'.$newCount.'" class="p-l-0 product_list" style="width: 50%;">
	                        <select data-te="'.$newCount.'" name="product_id[]" id="product_id'.$newCount.'" class="form-control product_id'.$newCount.' product_id js-select2-multi" data-te="'.$newCount.'">
	                            <option value="">Select Product Name</option>';
	                            if(!empty($product_val))
	                            {
	                            	foreach ($product_val as $key => $value) {
				        				$assproduct_id = !empty($value['assproduct_id'])?$value['assproduct_id']:'';
				                        $description   = !empty($value['description'])?$value['description']:'';

				                        $option .= '<option value="'.$assproduct_id.'">'.$description.'</option>';
				        			}
	                            }
	                        $option .='</select> 
	                    </td>
	                    <td class="p-l-0" style="width: 15%;">
	                        <input type="text" data-te="'.$newCount.'" name="product_price[]" id="product_price'.$newCount.'" class="form-control product_price'.$newCount.' product_price int_value" placeholder="Price">
	                    </td>
	                    <td class="p-l-0" style="width: 15%;">
	                        <input type="text" data-te="'.$newCount.'" name="product_qty[]" id="product_qty'.$newCount.'" class="form-control product_qty'.$newCount.' product_qty int_value" placeholder="Quantity">

	                        <input type="hidden" data-te="'.$newCount.'" name="purchase_id[]" id="purchase_id'.$newCount.'" class="form-control purchase_id'.$newCount.' purchase_id" placeholder="Enter the Price">
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

			else if($param1 =='_getDistributorInvoice')
			{
				$distributor_id      = $this->input->post('distributor_id');	

				$where = array(
					'distributor_id' => $distributor_id,
            		'method'         => '_listDistributorPaymentBill'
            	);

            	$invoice_list   = avul_call(API_URL.'payment/api/distributor_payment',$where);
            	$invoice_result = $invoice_list['data'];

            	$option ='<option value="">Select Value</option>';

            	if(!empty($invoice_result))
        		{
        			foreach ($invoice_result as $key => $value) {
        				$invoice_id = !empty($value['pay_id'])?$value['pay_id']:'';
                        $invoice_no = !empty($value['bill_no'])?$value['bill_no']:'';
                        $bill_value = !empty($value['bal_amt'])?$value['bal_amt']:'0';

                        $option .= '<option value="'.$invoice_id.'">'.$invoice_no.' (Rs.'.$bill_value.')</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
			}

			else if($param1 =='_getDistributorProduct')
			{
				$distributor_id      = $this->input->post('distributor_id');	

				$where = array(
					'distributor_id' => $distributor_id,
					'published'      => '1',
					'status'         => '1',
            		'method'         => '_listDistributorAssignProduct'
            	);

            	$product_list   = avul_call(API_URL.'assignproduct/api/list_assign_product',$where);
            	$product_result = $product_list['data'];

            	$option ='<option value="">Select Value</option>';

            	if(!empty($product_result))
        		{
        			foreach ($product_result as $key => $value) {
        				$assproduct_id = !empty($value['assproduct_id'])?$value['assproduct_id']:'';
                        $description   = !empty($value['description'])?$value['description']:'';

                        $option .= '<option value="'.$assproduct_id.'">'.$description.'</option>';
        			}
        		}

        		$response['status']  = 1;
		        $response['message'] = 'success'; 
		        $response['data']    = $option;
		        echo json_encode($response);
		        return; 	
			}

			else if($param1 == 'getDisProduct_details')
			{
				$distributor_id = $this->input->post('distributor_id');
				$product_id     = $this->input->post('product_id');

				$pdt_whr = array(
					'distributor_id' => $distributor_id,
					'assproduct_id'  => $product_id,
					'method'         => '_detailsDistributorAssignProduct',
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

			else if($param1 == '_getDistributorDetails')
			{
				$distributor_id = $this->input->post('distributor_id');

				$where = array(
	        		'distributor_id' => $distributor_id,
	        		'method'         => '_detailDistributors'
	        	);

	        	$data_list   = avul_call(API_URL.'distributors/api/distributors',$where);
	        	$data_status = $data_list['status'];
	        	$data_value  = $data_list['data'];	

	        	if($data_status == 1)
	        	{
	        		$mobile  = !empty($data_value[0]['mobile'])?$data_value[0]['mobile']:'';
		        	$gst_no  = !empty($data_value[0]['gst_no'])?$data_value[0]['gst_no']:'';
		        	$address = !empty($data_value[0]['address'])?$data_value[0]['address']:'';

		        	$option = array(
	            		'gst_no'     => $gst_no,
	            		'contact_no' => $mobile,
	            		'address'    => $address,
	            	);

	            	$response['status']  = 1;
			        $response['message'] = 'success'; 
			        $response['data']    = $option;
			        echo json_encode($response);
			        return; 
	        	}
			}

			else
			{
				$where_1 = array(
					'method' => '_listDistributors',
					'ref_id' => $this->session->userdata('id'),
			    );

            	$data_list = avul_call(API_URL.'distributors/api/distributors',$where_1);
            	$dist_list = !empty($data_list['data'])?$data_list['data']:'';

            	$where_2 = array('method' => '_listUnit');

	            $unit_list  = avul_call(API_URL.'master/api/unit',$where_2);
	            $unit_val   = $unit_list['data'];

            	$page['method']          = 'BTBM_X_C';
				$page['page_title']      = "Add Distributors Sales Return";
				$page['distributor_val'] = $dist_list;
				$page['unit_val']        = $unit_list['data'];
            	$page['main_heading']    = "Distributors Sales Return";
				$page['sub_heading']     = "Distributor Sales Return";
				$page['pre_title']       = "List Distributors Sales Return";
				//$page['page_access']     = userAccess('sales-return-view');
				$page['pre_menu']        = "index.php/distributors/salesreturn/list_distributor_sales_return";
				$data['page_temp']       = $this->load->view('distributors/salesreturn/add_distributor_sales_return',$page,TRUE);
				$data['view_file']       = "Page_Template";
				$data['currentmenu']     = "add_distributor_sales_return";
				$this->bassthaya->load_distributors_form_template($data);
			}
		}

		public function list_distributors_sales_return($param1="", $param2="", $param3="")
		{	
			
			if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
        
			if($param1 == '')
			{
				$page['main_heading'] = "Distributors Sales Return";
				$page['sub_heading']  = "Distributors Sales Return";
				$page['page_title']   = "List Distributors Sales Return";
				$page['pre_title']    = "Add Distributors Sales Return";
				$page['pre_menu']     = "index.php/distributors/salesreturn/add_distributor_sales_return";
				//$page['page_access']     = userAccess('sales-return-add');
				$data['page_temp']    = $this->load->view('distributors/salesreturn/list_distributor_sales_return',$page, TRUE);
				$data['view_file']    = "Page_Template";
				$data['currentmenu']  = "list_distributor_sales_return";
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
	            		'offset'       => $_offset,
	            		'limit'        => $limit,
	            		'search'       => $search,
	            		'financial_id' => $this->session->userdata('active_year'),
						'ref_id'       => $this->session->userdata('id'),
	            		'method'       => '_manageDistributorReturnPagination'
	            	);

	            	$data_list  = avul_call(API_URL.'salesreturn/api/distributor_return',$where);
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

				            $return_no    = !empty($value['return_no'])?$value['return_no']:'';
				            $distri_name  = !empty($value['distributor_name'])?$value['distributor_name']:'';
				            $random_value = !empty($value['random_value'])?$value['random_value']:'';
				            $date         = !empty($value['date'])?$value['date']:'';

						    $table .= '
						    	<tr>
	                                <td class="line_height">'.$i.'</td>
	                                <td class="line_height">'.$return_no.'</td>
	                                <td class="line_height">'.$distri_name.'</td>
	                                <td class="line_height">'.$date.'</td>
	                                <td>
	                                	<a href="'.BASE_URL.'index.php/distributors/salesreturn/list_distributors_sales_return/View/'.$random_value.'" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a>
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

			else if($param1 == 'View')
			{
				
				$where = array(
					'ref_id'       => $this->session->userdata('id'),
            		'return_value' => $param2,
            		'method'       => '_distributorReturnDetails'
            	);

            	$data_list  = avul_call(API_URL.'salesreturn/api/distributor_return',$where);
            	$data_value = !empty($data_list['data'])?$data_list['data']:'';

            	$page['return_data']   = $data_value;
				$page['main_heading']  = "Sales Return";
				$page['sub_heading']   = "Sales Return";
				$page['page_title']    = "Sales Return";
				$page['pre_title']     = "";
				//$page['page_access']   = userAccess('sales-return-view');
				$page['pre_menu']      = "index.php/distributors/salesreturn/list_distributor_sales_return";
				$data['page_temp']     = $this->load->view('distributors/salesreturn/view_dis_sales_return',$page,TRUE);
				$data['view_file']     = "Page_Template";
				$data['currentmenu']   = "list_distributor_sales_return";
				$this->bassthaya->load_distributors_form_template($data);
			}

			else if($param1 == 'print_invoice')
			{
				$where = array(
					'ref_id'       => $this->session->userdata('id'),
            		'return_value' => $param2,
            		'method'       => '_distributorReturnDetails'
            	);

            	$data_list   = avul_call(API_URL.'salesreturn/api/distributor_return',$where);
            	$return_data = !empty($data_list['data'])?$data_list['data']:'';

            	$distributor_det = !empty($return_data['distributor_details'])?$return_data['distributor_details']:'';
				$admin_details   = !empty($return_data['admin_details'])?$return_data['admin_details']:'';
				$product_det     = !empty($return_data['product_details'])?$return_data['product_details']:'';
				$tax_det         = !empty($return_data['tax_details'])?$return_data['tax_details']:'';

				// Distributor Details
				$return_no    = !empty($distributor_det['return_no'])?$distributor_det['return_no']:'';
				$return_value = !empty($distributor_det['return_value'])?$distributor_det['return_value']:'';
				$return_date  = !empty($distributor_det['return_date'])?$distributor_det['return_date']:'';
				$company_name = !empty($distributor_det['company_name'])?$distributor_det['company_name']:'';
			    $dis_mobile   = !empty($distributor_det['dis_mobile'])?$distributor_det['dis_mobile']:'';
			    $dis_email    = !empty($distributor_det['dis_email'])?$distributor_det['dis_email']:'';
			    $dis_state_id = !empty($distributor_det['dis_state_id'])?$distributor_det['dis_state_id']:'';
			    $dis_gst_no   = !empty($distributor_det['dis_gst_no'])?$distributor_det['dis_gst_no']:'';
			    $dis_address  = !empty($distributor_det['dis_address'])?$distributor_det['dis_address']:'';
			    $invoice_no   = !empty($distributor_det['invoice_no'])?$distributor_det['invoice_no']:'';
			    $invoice_date = !empty($distributor_det['invoice_date'])?$distributor_det['invoice_date']:'';

			    $adm_username = !empty($admin_details['adm_username'])?$admin_details['adm_username']:'';
			    $adm_mobile   = !empty($admin_details['adm_mobile'])?$admin_details['adm_mobile']:'';
			    $adm_address  = !empty($admin_details['adm_address'])?$admin_details['adm_address']:'';
			    $adm_gst_no   = !empty($admin_details['adm_gst_no'])?$admin_details['adm_gst_no']:'';
			    $adm_state_id = !empty($admin_details['adm_state_id'])?$admin_details['adm_state_id']:'';

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

	          	$html .= '<p style="color:black; font-size:12px; text-align: center;"><strong style="font-size:18px; padding-bottom:1000px;">'.$adm_username.'</strong><br/>'.$adm_address.', Contact No: '.$adm_mobile.'<br>GSTIN\UIN : '.$adm_gst_no.'<br><strong style="color:black; text-align:center; font-size:17px;"> SALES RETURN</strong><br></p>';

	          	$html .='<br><br><br>
				<table border= "1" cellpadding="1" top="100">
			        <tr>
			            <td rowspan="6" style="font-size:12px; width: 55%; margin-left:10px;">Shipped To: <br> '.$company_name.'<br> '.$dis_address.'<br> Contact No: '.$dis_mobile.'<br> GSTIN\UIN :'.$dis_gst_no.'</td>
			            <td style="font-size:12px; width: 20%;"> Return No</td>
			            <td style="font-size:12px; width: 25%;"> '.$return_no.'</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Return Date</td>
			            <td style="font-size:12px; width: 25%;"> '.date('d-M-Y', strtotime($return_date)).'</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Invoice No</td>
			            <td style="font-size:12px; width: 25%;"> '.$invoice_no.'</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Invoice Date</td>
			            <td style="font-size:12px; width: 25%;"> '.date('d-M-Y', strtotime($invoice_date)).'</td>
			        </tr>
			        <tr>
			            <td style="font-size:12px; width: 20%;"> Return Type</td>
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
					        foreach ($product_det as $key => $val_1) {
								$description = !empty($val_1['description'])?$val_1['description']:'';
				                $hsn_code    = !empty($val_1['hsn_code'])?$val_1['hsn_code']:'';
				                $gst_value   = !empty($val_1['gst_val'])?$val_1['gst_val']:'0';
				                $pdt_price   = !empty($val_1['price'])?$val_1['price']:'0';
				                $pdt_qty     = !empty($val_1['return_qty'])?$val_1['return_qty']:'0';

			                    $gst_data  = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
			                    $price_val = $pdt_price - $gst_data;
			                    $tot_price = $pdt_qty * $price_val;
			                    $sub_tot  += $tot_price;

			                    // GST Calculation
			                    $gst_val   = $pdt_qty * $gst_data;
			                    $tot_gst  += $gst_val;
			                    $total_val = $pdt_qty * $pdt_price;
			                    $net_tot  += $total_val;
			                    $tot_qty  += $pdt_qty;

			                    $html .= '
			                    	<tr>
							            <td style="font-size:12px; width: 5%;">'.$num.'</td>
							            <td style="font-size:12px; width: 44%;">'.$description.'</td>
							            <td style="font-size:12px; text-align: center; width: 10%;">'.$hsn_code.'</td>
							            <td style="font-size:12px; text-align: center; width: 10%;">'.number_format((float)$price_val, 2, '.', '').'</td>
							            <td style="font-size:12px; text-align: center; width: 8%;">'.$pdt_qty.'</td>
							            <td style="font-size:12px; text-align: center; width: 8%;">nos</td>
							            <td style="font-size:12px; text-align: center; width: 15%;">'.number_format((float)$tot_price, 2, '.', '').'</td>
							        </tr>
			                    ';

			                    $num++;
					        }

		          	$rowspan = '5';
				    if($dis_state_id == $adm_state_id)
				    {
				    	$rowspan = '6';
				    }

				    // Round Val Details
                    $last_value = round($net_tot);
                    $rond_total = $last_value - $net_tot;

                   	$html .= '
			            <tr>
			                <td rowspan ="'.$rowspan.'"  colspan="4"></td>
			                <td colspan="2" style="font-size:12px;" class="text-right">Qty</td>
			                <td style="font-size:12px;" class="text-right"> '.$tot_qty.'</td>
			                
			            </tr>
			            <tr>
			                <td colspan="2" style="font-size:12px;" class="text-right">Sub Total</td>
			                <td style="font-size:12px;" class="text-right"> '.number_format((float)$sub_tot, 2, '.', '').'</td>
			            </tr>';
			            if($dis_state_id == $adm_state_id)
			            {
			            	$gst_value = $tot_gst / 2;

			            	$html .= '
			            		<tr>
					                <td colspan="2" style="font-size:12px;" class="text-right">SGST</td>
					                <td style="font-size:12px;" class="text-right"> '.number_format((float)$gst_value, 2, '.', '').'</td>
					            </tr>
					            <tr>
					                <td colspan="2" style="font-size:12px;" class="text-right">CGST</td>
					                <td style="font-size:12px;" class="text-right"> '.number_format((float)$gst_value, 2, '.', '').'</td>
					            </tr>
			            	';
			            }
			            else
			            {
			            	$html .= '
			            		<tr>
					                <td colspan="2" style="font-size:12px;" class="text-right">IGST</td>
					                <td style="font-size:12px;" class="text-right"> '.number_format((float)$tot_gst, 2, '.', '').'</td>
					            </tr>
			            	';
			            }

			            $html .='<tr>
			                <td colspan="2" style="font-size:12px;" class="text-right">Round off</td>
			                <td style="font-size:12px;" class="text-right"> '.number_format((float)$rond_total, 2, '.', '').'</td>
			            </tr>
			            <tr>
			                <td colspan="2" style="font-size:12px;" class="text-right">Net Total</td>
			                <td style="font-size:12px;" class="text-right"> '.number_format((float)$last_value, 2, '.', '').'</td>
			            </tr>
			            <tr>
			                <td colspan="11" style="font-size:12px;" class="text-right">Amount (in words) : '.numberTowords($last_value).'  rupees only</td>
			            </tr>';
				    $html .='</table>';

				    $html .='<br><br>
					<table border= "1" cellpadding="1" top="100">
				        <tr>
				            <td rowspan="2" style="font-size:12px; width: 10%;">HSN</td>
				            <td rowspan="2" style="font-size:12px; width: 15%;">Taxable Value</td>';
				            if($dis_state_id == $adm_state_id)
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
				        	if($dis_state_id == $adm_state_id)
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
		                    		if($dis_state_id == $adm_state_id)
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
				        		if($dis_state_id == $adm_state_id)
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
				            <td colspan="5" style="font-size:12px; width: 50%;">
				            	<br><br><br><br><br><br>
				            </td>
				            <td rowspan="5" style="font-size:12px; width: 50%;">
				            	<span> for '.$adm_username.'</span>
				            	<br><br><br>
				            	<p style="text-align: right; "> Authorised signature </p>
				            </td>
				        </tr>
				    </table>';

	          	$pdf->writeHTML($html, true, false, true, false, '');
	       		$pdf->Output($company_name.'_'.$return_no.'_'.date('d-F-Y').'.pdf', 'I');
			}
		}
	}
?>
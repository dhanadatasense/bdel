<?php
    // Status Details
    $bill_details        = !empty($sales_data['bill_details'])?$sales_data['bill_details']:'';
    $store_details       = !empty($sales_data['store_details'])?$sales_data['store_details']:'';
    $distributor_details = !empty($sales_data['distributor_details'])?$sales_data['distributor_details']:'';
    $product_details     = !empty($sales_data['product_details'])?$sales_data['product_details']:'';
    $tax_details         = !empty($sales_data['tax_details'])?$sales_data['tax_details']:'';
    $return_details      = !empty($sales_data['return_details'])?$sales_data['return_details']:'';

    // Return Details
    $return_total = !empty($return_details['return_total'])?$return_details['return_total']:'0';

    // Invocie Details
    $inv_value = !empty($product_details[0]['invoice_value'])?$product_details[0]['invoice_value']:'---';

    // Bill Details
    $order_id     = !empty($bill_details['order_id'])?$bill_details['order_id']:'';
    $order_no     = !empty($bill_details['order_no'])?$bill_details['order_no']:'';
    $bill_type    = !empty($bill_details['bill_type'])?$bill_details['bill_type']:'';
    $emp_name     = !empty($bill_details['emp_name'])?$bill_details['emp_name']:'';
    $store_id     = !empty($bill_details['store_id'])?$bill_details['store_id']:'';
    $store_name   = !empty($bill_details['store_name'])?$bill_details['store_name']:'';
    $contact_name = !empty($bill_details['contact_name'])?$bill_details['contact_name']:'';
    $zone_value   = !empty($bill_details['zone_value'])?$bill_details['zone_value']:'';
    $due_days     = !empty($bill_details['due_days'])?$bill_details['due_days']:'0';
    $discount     = !empty($bill_details['discount'])?$bill_details['discount']:'0';
    $order_status = !empty($bill_details['order_status'])?$bill_details['order_status']:'';
    $_ordered     = !empty($bill_details['_ordered'])?$bill_details['_ordered']:'';
    $_processing  = !empty($bill_details['_processing'])?$bill_details['_processing']:'';
    $_packing     = !empty($bill_details['_packing'])?$bill_details['_packing']:'';
    $_shiped      = !empty($bill_details['_shiped'])?$bill_details['_shiped']:'';
    $_delivery    = !empty($bill_details['_delivery'])?$bill_details['_delivery']:'';
    $_complete    = !empty($bill_details['_complete'])?$bill_details['_complete']:'';
    $_canceled    = !empty($bill_details['_canceled'])?$bill_details['_canceled']:'';
    $einvoicepdf  = !empty($bill_details['einvoicepdf'])?$bill_details['einvoicepdf']:'';
    $ewaybillpdf  = !empty($bill_details['ewaybillpdf'])?$bill_details['ewaybillpdf']:'';

    // Distributor Details
    $dis_company_name = !empty($distributor_details['company_name'])?$distributor_details['company_name']:'';
    $dis_gst_no       = !empty($distributor_details['gst_no'])?$distributor_details['gst_no']:'';
    $dis_contact_no   = !empty($distributor_details['contact_no'])?$distributor_details['contact_no']:'';
    $dis_email        = !empty($distributor_details['email'])?$distributor_details['email']:'';
    $dis_address      = !empty($distributor_details['address'])?$distributor_details['address']:'';
    $dis_state_id     = !empty($distributor_details['state_id'])?$distributor_details['state_id']:'';
    $dis_city_id      = !empty($distributor_details['city_id'])?$distributor_details['city_id']:'';

    // Store Details
    $company_name = !empty($store_details['company_name'])?$store_details['company_name']:'';
    $contact_name = !empty($store_details['contact_name'])?$store_details['contact_name']:'';
    $mobile       = !empty($store_details['mobile'])?$store_details['mobile']:'-';
    $email        = !empty($store_details['email'])?$store_details['email']:'-';
    $gst_no       = !empty($store_details['gst_no'])?$store_details['gst_no']:'-';
    $address      = !empty($store_details['address'])?$store_details['address']:'';
    $state_id     = !empty($store_details['state_id'])?$store_details['state_id']:'';
    $state_name   = !empty($store_details['state_name'])?$store_details['state_name']:'';
    $state_code   = !empty($store_details['state_code'])?$store_details['state_code']:'';
    $gst_code     = !empty($store_details['gst_code'])?$store_details['gst_code']:'';
    $outlet_type  = !empty($store_details['outlet_type'])?$store_details['outlet_type']:'';
    $state_name   = !empty($store_details['state_name'])?$store_details['state_name']:'';

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
        $order_view = '<span class="badge badge-info">Ready to shipping</span>';
    }
    else if($order_status == '5')
    {
        $order_view = '<span class="badge badge-success">Invoice</span>';
    }
    else if($order_status == '10')
    {
        $order_view = '<span class="badge badge-warning">Shipping</span>';
    }
    else if($order_status == '11')
    {
        $order_view = '<span class="badge badge-primary">Delivered</span>';
    }
    else if($order_status == '6')
    {
        $order_view = '<span class="badge badge-success">Delivered</span>';
    }
    else if($order_status == '7')
    {
        $order_view = '<span class="badge badge-success">Complete</span>';
    }
    else
    {
        $order_view = '<span class="badge badge-danger">Cancel Invoice</span>';
    }

    // Date Details
    $ordered_date = '---';
    if(!empty($_ordered))
    {
        $ordered_date = date('d-M-Y h:i:s A', strtotime($_ordered));
    }

    $process_date = '---';
    if(!empty($_processing))
    {
        $process_date = date('d-M-Y h:i:s A', strtotime($_processing));
    }

    $packing_date = '---';
    if(!empty($_packing))
    {
        $packing_date = date('d-M-Y h:i:s A', strtotime($_packing));
    }

    $delivery_date = '---';
    if(!empty($_delivery))
    {
        $delivery_date = date('d-M-Y h:i:s A', strtotime($_delivery));
    }

    $complete_date = '---';
    if(!empty($_complete))
    {
        $complete_date = date('d-M-Y h:i:s A', strtotime($_complete));
    }

    $cancel_date = '---';
    if(!empty($_canceled))
    {
        $cancel_date = date('d-M-Y h:i:s A', strtotime($_canceled));
    }

    // Process Status
    $pro_active  = $order_status == '2' ? 'active':'';
    $pro_checked = $order_status == '2' ? 'checked':'';

    // Packing Status
    $pak_active  = $order_status == '3' ? 'active':'';
    $pak_checked = $order_status == '3' ? 'checked':'';

    // Ready to Shipping Status
    $ready_active  = $order_status == '4' ? 'active':'';
    $ready_checked = $order_status == '4' ? 'checked':'';

    // Invoice Status
    $inv_active   = $order_status == '5' ? 'active':'';
    $inv_checked  = $order_status == '5' ? 'checked':'';

    // Shipping Status
    $shipping_active   = $order_status == '10' ? 'active':'';
    $shipping_checked  = $order_status == '10' ? 'checked':'';

    // Delivered Status
    $delivered_active   = $order_status == '11' ? 'active':'';
    $delivered_checked  = $order_status == '11' ? 'checked':'';

    // Delivery Status
    $del_active   = $order_status == '6' ? 'active':'';
    $del_checked  = $order_status == '6' ? 'checked':'';

    // Invoice Cancel Status
    $can_active   = $order_status == '9' ? 'active':'';
    $can_checked  = $order_status == '9' ? 'checked':'';

    // Order Type
    if($bill_type == 1)
    {
        $type_view = 'COD';
    }
    else
    {
        $type_view = 'Credit';
    }

?>

<style type="text/css">
    .pro_type_button
    {
        margin-right: 10px;
        color: #fff;
        border-radius: 4px;
        cursor: pointer;
    }
    .price_lable input
    {
        display: none;
    }
    .price_lable {
        background-color: #fff;
    }
    .btn-success
    {
        color: #28d094;
        background-color: #fff;
    }
    .btn-warning
    {
        color: #ff9149;
        background-color: #fff;
    }
    .btn-primary
    {
        color: #666ee8;
        background-color: #fff;
    }
    .btn-info
    {
        color: #1e9ff2;
        background-color: #fff;
    }
    .btn-danger
    {
        color: #ff4961;
        background-color: #fff;
    }

</style>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <?php
                if(!empty($sales_data))
                {
                    ?>
                        <?php
                            if($order_status != 6 && $order_status != 9 && $order_status != 11)
                            {
                                ?>
                        <div class="row">
                            <div id="accordionWrap3" role="tablist" aria-multiselectable="false" style="width: 100%;">
                                <div class="card accordion collapse-icon accordion-icon-rotate">
                                    <a id="headingCollapse2" class="card-header bg-success success" data-toggle="collapse" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                        <div class="card-title lead collapsed white" >Change Order Status</div>
                                    </a>
                                    <div id="collapse2" role="tabpanel" aria-labelledby="headingCollapse2" class="collapse hide" aria-expanded="false">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <?php
                                                            if($order_status == 2)
                                                            {
                                                                ?>
                                                                    <label class="price_lable btn btn-warning pro_type_button progress_2 <?php echo $pro_active; ?>">
                                                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="2" <?php echo $pro_checked; ?>> Approved
                                                                    </label>

                                                                    <label class="price_lable btn btn-primary pro_type_button progress_3 <?php echo $pak_active; ?>">
                                                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="3" <?php echo $pak_checked; ?>> Packing
                                                                    </label>
                                                                <?php
                                                            }
                                                            else if($order_status == 3)
                                                            {
                                                                ?>
                                                                    <label class="price_lable btn btn-primary pro_type_button progress_3 <?php echo $pak_active; ?>">
                                                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="3" <?php echo $pak_checked; ?>> Packing
                                                                    </label>

                                                                    <label class="price_lable btn btn-success pro_type_button progress_4 <?php echo $ready_active; ?>">
                                                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="4" <?php echo $ready_checked; ?>> Ready to shipping
                                                                    </label>
                                                                <?php
                                                            }
                                                            else if($order_status == 4)
                                                            {
                                                                ?>
                                                                    <label class="price_lable btn btn-success pro_type_button progress_4 <?php echo $ready_active; ?>">
                                                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="4" <?php echo $ready_checked; ?>> Ready to shipping
                                                                    </label>
                                                                    <label class="price_lable btn btn-info pro_type_button progress_5 <?php echo $inv_active; ?>">
                                                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="5" <?php echo $inv_checked; ?>> Invoice
                                                                    </label>
                                                                <?php
                                                            }
                            else if($order_status == 5)
                            {
                                ?>
                                    <label class="price_lable btn btn-info pro_type_button progress_5 <?php echo $inv_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="5" <?php echo $inv_checked; ?>> Invoice
                                    </label>
                                    <label class="price_lable btn btn-danger pro_type_button progress_9 <?php echo $can_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="9" <?php echo $can_checked; ?>> Cancel Invoice
                                    </label>
                                    <label class="price_lable btn btn-warning pro_type_button progress_10 <?php echo $shipping_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="10" <?php echo $shipping_checked; ?>> Shipping
                                    </label>
                                <?php
                            }
                                                            else if($order_status == 10)
                                                            {
                                                                ?>

                                                                <label class="price_lable btn btn-warning pro_type_button progress_10 <?php echo $shipping_active; ?>">
                                                                    <input type="radio" name="options" id="hatchback" class="progress_option" value="10" <?php echo $shipping_checked; ?>> Shipping
                                                                </label>

                                                                <label class="price_lable btn btn-primary pro_type_button progress_11 <?php echo $delivered_active; ?>">
                                                                    <input type="radio" name="options" id="hatchback" class="progress_option" value="11" <?php echo $delivered_checked; ?>> Delivered
                                                                </label>

                                                                <?php
                                                                    if(!empty($inv_random))
                                                                    {
                                                                        ?>
                                                                            <label class="price_lable btn btn-danger pro_type_button progress_7">
                                                                                <input type="radio" name="options" id="hatchback" class="progress_option" value="7"> Cancel Invoice
                                                                            </label>
                                                                        <?php
                                                                    }
                                                                ?>

                                                                <?php
                                                            }
                                                        ?>
                                                    </div>
                                                    <div class="col-md-12 order_message m-t-6 hide">
                                                        <div class="form-group">
                                                            <label for="projectinput1">Status Message <span class="text-danger">*</span></label>
                                                            <textarea name="message" id="message" class="form-control message" placeholder="Enter the Message" rows="3"></textarea>
                                                        </div>
                                                    </div>
                            <div class="col-md-12 delivery_message m-t-10 hide">
                                <div class="row clearfix form-group">
                                    <div class="col-md-3">
                                        <label for="firstName2">Length</label>
                                        <input type="text" id="length" class="form-control length" placeholder="Length (.cm)" name="length" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="firstName2">Breadth</label>
                                        <input type="text" id="breadth" class="form-control breadth" placeholder="Breadth (.cm)" name="length" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="firstName2">Height</label>
                                        <input type="text" id="height" class="form-control height" placeholder="Height (.cm)" name="length" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="firstName2">Weight</label>
                                        <input type="text" id="weight" class="form-control weight" placeholder="Weight (.kg)" name="length" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                    </div>
                                </div>
                                <div class="row clearfix form-group">
                                    <div class="col-md-3">
                                        <div class="form-group" style="display: grid;">
                                            <label for="projectinput1">E Invoice Status <span class="text-danger">*</span></label>
                                            <select class="form-control e_inv_status js-select1-multi" id="e_inv_status" name="e_inv_status">
                                                <option value="">Select Value</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 eway_status hide">
                                        <div class="form-group" style="display: grid;">
                                            <label for="projectinput1">E Way bill Status <span class="text-danger">*</span></label>
                                            <select class="form-control e_way_status js-select1-multi" id="e_way_status" name="e_way_status">
                                                <option value="">Select Value</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 eway_value hide">
                                        <label for="firstName2">Transporter GSTIN</label>
                                        <input type="text" id="transporter_id" class="form-control transporter_id" placeholder="Transporter GSTIN" name="transporter_id" value="">
                                    </div>
                                    <div class="col-md-3 eway_value hide">
                                        <label for="firstName2">Transporter Name</label>
                                        <input type="text" id="transporter_name" class="form-control transporter_name" placeholder="Transporter Name" name="transporter_name" value="">
                                    </div>
                                    <div class="col-md-3 eway_value hide">
                                        <div class="form-group" style="display: grid;">
                                            <label for="projectinput1">Transportation Mode</label>
                                            <select class="form-control transportation_mode js-select1-multi" id="transportation_mode" name="transportation_mode">
                                                <option value="">Select Value</option>
                                                <option value="1">Road</option>
                                                <option value="2">Rail</option>
                                                <option value="3">Air</option>
                                                <option value="4">Ship</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 eway_value hide">
                                        <label for="firstName2">Document No</label>
                                        <input type="text" id="transporter_document_number" class="form-control transporter_document_number" placeholder="Transporter Name" name="transporter_document_number" value="">
                                    </div>
                                    <div class="col-md-3 eway_value hide">
                                        <label for="firstName2">Vehicle No</label>
                                        <input type="text" id="vehicle_number" class="form-control vehicle_number" placeholder="Vehicle No" name="vehicle_number" value="">
                                    </div>
                                    <div class="col-md-3 eway_value hide">
                                        <div class="form-group" style="display: grid;">
                                            <label for="projectinput1">Vehicle Type</label>
                                            <select class="form-control vehicle_type js-select1-multi" id="vehicle_type" name="vehicle_type">
                                                <option value="">Select Value</option>
                                                <option value="R">Regular</option>
                                                <option value="O">ODC</option>
                                            </select>
                                            <input type="hidden" id="transportation_distance" class="form-control transportation_distance" placeholder="Transporter Distance" name="transportation_distance" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">

                                            <input type="hidden" id="transporter_document_date" class="form-control transporter_document_date atdates" placeholder="Document Date" name="transporter_document_date" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group text-right">
                                                            <input type="hidden" name="order_id" id="order_id" class="order_id" value="<?php echo $order_id; ?>">
                                                            <input type="hidden" name="inv_value" id="inv_value" class="inv_value" value="<?php echo $inv_value; ?>">
                                                            <input type="hidden" name="pre_status" id="pre_status" class="pre_status" value="<?php echo $order_status; ?>">
                                                            <input type="hidden" name="zone_value" id="zone_value" class="zone_value" value="<?php echo $zone_value; ?>">
                                                            <input type="hidden" name="distributor_id" id="distributor_id" class="distributor_id" value="<?php echo $this->session->userdata('id'); ?>">
                                                            <input type="hidden" name="value" id="value" class="value" value="distributors">
                                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="order">
                                                            <input type="hidden" name="func" id="func" class="func" value="order_process">
                                                            <button type="button" class="btn btn-success distributor_process_button process_submit" style="background-color: #28d094 !important; color: #fff;"> 
                                                                <span class="first_btn show"><i class="la la-check-square-o"></i> Submit</span>

                                                                <span class="span_btn hide"><i class="la la-spinner spinner"></i> Loading....</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                <?php      
                            }
                        ?>

                        <div class="row">
                            <section class="card" style="width: 100%;">
                                <div id="invoice-template" class="card-body p-4">
                                    <!-- Invoice Company Details -->
                                    <div id="invoice-company-details" class="row">
                                        <div class="col-sm-6 col-12 text-center text-sm-left">
                                            <div class="media row">
                                                <div class="col-12 col-sm-6 col-xl-6">
                                                <img class="brand-logo logo-mx" alt="modern admin logo" src="<?php echo BASE_URL; ?>app-assets/images/logo/logobdel.jpg">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12 text-center text-sm-right">
                                            <h2>ORDER</h2>
                                            <p class="pb-sm-3"># <?php echo $order_no; ?></p>
                                        </div>
                                    </div>
                                    <!-- Invoice Company Details -->
                                    <!-- Invoice Customer Details -->
                                    <div id="invoice-customer-details" class="row pt-2">
                                        <div class="col-sm-6 col-12 text-center text-sm-left">
                                            <ul class="ml-2 px-0 list-unstyled">
                                                <li class="text-bold-800"><b><?php echo $dis_company_name; ?></b></li>
                                                <li><?php echo $dis_address; ?></li>
                                                <li><b>GSTIN:</b> <?php echo $dis_gst_no; ?></li>
                                                <li><b>Contact No:</b> <?php echo $dis_contact_no; ?></li>
                                                <li><b>Email:</b> <?php echo $dis_email; ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-6 col-12 text-center text-sm-right">
                                            <ul class="px-0 list-unstyled">
                                                <li class="text-bold-800"><b><?php echo $company_name; ?></b></li>
                                                <li><?php echo $address; ?></li>
                                                <li><b>GSTIN:</b> <?php echo $gst_no; ?></li>
                                                <li><b>Contact No:</b> <?php echo $mobile; ?></li>
                                                <li><b>Email:</b> <?php echo $email; ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Invoice Customer Details -->

                                    <!-- Invoice Customer Details -->
                                    <div id="invoice-customer-details" class="row pt-2">
                                        <div class="col-sm-3 col-12 text-center">
                                            <ul class="px-0 list-unstyled">
                                                <li><b>Approved Date</b></li>
                                                <li><?php echo $process_date; ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-3 col-12 text-center">
                                            <ul class="px-0 list-unstyled">
                                                <li><b>Payment Method</b></li>
                                                <li><?php echo $type_view; ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-3 col-12 text-center">
                                            <ul class="px-0 list-unstyled">
                                                <li><b>Order Type</b></li>
                                                <li><?php echo $order_view; ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-3 col-12 text-center">
                                            <ul class="px-0 list-unstyled">
                                                <li><b>Discount / Due Days</b></li>
                                                <li><?php echo $discount; ?> / <?php echo $due_days; ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Invoice Customer Details -->
                                    <!-- Invoice Items Details -->
                                    <div id="invoice-items-details invoice_tbl" class="pt-2">
                                        <div class="row">
                                            <?php
                                                if(!empty($product_details))
                                                {
                                                    if($order_status == 2)
                                                    {
                                                        ?>
        <div class="table-responsive col-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>HSN / SAC</th>
                        <th>Rate</th>
                        <th>Qty</th>
                        <th>Per</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 1;
                        $total_qty = 0;
                        $sub_total = 0;
                        foreach ($product_details as $key => $value) {

                            $auto_id      = !empty($value['auto_id'])?$value['auto_id']:'';
                            $order_id     = !empty($value['order_id'])?$value['order_id']:'';
                            $product_name = !empty($value['product_name'])?$value['product_name']:'';
                            $type_name    = !empty($value['type_name'])?$value['type_name']:'';
                            $description  = !empty($value['description'])?$value['description']:'';
                            $unit_name    = !empty($value['unit_name'])?$value['unit_name']:'';
                            $hsn_code     = !empty($value['hsn_code'])?$value['hsn_code']:'';
                            $price        = !empty($value['price'])?$value['price']:'0';
                            $order_qty    = !empty($value['order_qty'])?$value['order_qty']:'';
                            $total_price  = $order_qty * number_format((float)$price, 2, '.', '');
                            $total_qty   += $order_qty;
                            $sub_total   += $total_price;

                            ?>
                                <tr class="row_<?php echo $auto_id; ?>">
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $description; ?></td>
                                    <td><?php echo $hsn_code; ?></td>
                                    <td><?php echo number_format((float)$price, 2, '.', ''); ?></td>
                                    <td><?php echo $order_qty; ?></td>
                                    <td>nos</td>
                                    <td><?php echo number_format((float)$total_price, 2, '.', ''); ?></td>
                                </tr>
                            <?php
                            $i++;
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-right">Quantity</th>
                        <td><?php echo $total_qty; ?></td>
                    </tr>
                    <tr>
                        <th colspan="6" class="text-right">Sub Total</th>
                        <td><?php echo number_format((float)$sub_total, 2, '.', ''); ?></td>
                    </tr>
                    <?php
                        $total_dis  = $sub_total * $discount / 100;
                        $total_val  = $sub_total - $total_dis;
                        $last_total = round($total_val);
                        $round_val  = $last_total - $total_val;
                    ?>
                    <?php
                        if($discount != 0)
                        {
                            ?>
                                <tr>
                                    <th colspan="6" class="text-right">Discount</th>
                                    <td><?php echo number_format((float)$total_dis, 2, '.', ''); ?></td>
                                </tr>
                            <?php
                        }
                    ?>
                    <tr>
                        <th colspan="6" class="text-right">Round off</th>
                        <td><?php echo number_format((float)$round_val, 2, '.', ''); ?></td>
                    </tr>
                    <tr>
                        <th colspan="6" class="text-right">Total</th>
                        <td><?php echo number_format((float)$last_total, 2, '.', ''); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
                                                    <?php
                                                    }
                                                    else if($order_status == 3)
                                                    {
                                                        ?>
        <div class="table-responsive col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Order Qty</th>
                        <!-- <th>Received Qty</th> -->
                        <th>Available Qty</th>
                        <th>Unit</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 1;
                        foreach ($product_details as $key => $value) {

                            $auto_id      = !empty($value['auto_id'])?$value['auto_id']:'';
                            $order_id     = !empty($value['order_id'])?$value['order_id']:'';
                            $product_name = !empty($value['product_name'])?$value['product_name']:'';
                            $type_name    = !empty($value['type_name'])?$value['type_name']:'';
                            $description  = !empty($value['description'])?$value['description']:'';
                            $unit_name    = !empty($value['unit_name'])?$value['unit_name']:'';
                            $price        = !empty($value['price'])?$value['price']:'0';
                            $order_qty    = !empty($value['order_qty'])?$value['order_qty']:'0';
                            $receive_qty  = !empty($value['receive_qty'])?$value['receive_qty']:'0';
                            $stock_qty    = !empty($value['stock_qty'])?$value['stock_qty']:'0';
                            $pack_status  = !empty($value['pack_status'])?$value['pack_status']:'';
                            $total_price  = $order_qty * number_format((float)$price, 2, '.', '');

                            if($order_qty == $receive_qty)
                            {
                                $stk_link = '#';
                            }
                            else
                            {
                                $stk_link = BASE_URL.'index.php/distributors/order/order_stock/stock_add/'.$auto_id.'/'.$order_id;
                            }

                            ?>
                                <tr class="row_<?php echo $auto_id; ?>">
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $description; ?></td>
                                    <td><?php echo $order_qty; ?></td>
                                    <!-- <td><?php echo $receive_qty; ?></td> -->
                                    <td><?php echo $stock_qty; ?></td>
                                    <td>nos</td>
                                    <!-- <td><a href="<?php echo $stk_link; ?>" class="button_clr btn btn-warning" style="background-color: #ff9149 !important;"><i class="ft-plus-square"></i></a></td> -->
                                    <td>
                                        <?php
                                            if($pack_status == 2)
                                            {
                                                ?>
                                                    <button class="btn btn-success" style="padding: 4px;"><i class="icon-check"></i> </button>

                                                    <button class="btn btn-danger" style="padding: 4px;"><i class="ft-trash-2"></i> </button>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <button class="btn btn-warning pack_btn" data-id="<?php echo $auto_id; ?>" data-value="distributors" data-cntrl="order" data-func="order_process" data-method="_changePackStatus" style="padding: 4px;"><i class="ft-rotate-cw"></i> </button>

                                                    <button class="btn btn-danger del_btn" data-id="<?php echo $auto_id; ?>" data-value="distributors" data-cntrl="order" data-func="order_process" data-method="_deletePackStatus" style="padding: 4px;"><i class="ft-trash-2"></i> </button>
                                                <?php
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php
                            $i++;
                        }
                    ?>
                </tbody>
            </table>
        </div>
                                                        <?php
                                                    }
                                                    else if($order_status == 4)
                                                    {
                                                        ?>
        <div class="table-responsive col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Per</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 1;
                        $total_qty = 0;
                        $sub_total = 0;
                        foreach ($product_details as $key => $value) {

                            $auto_id      = !empty($value['auto_id'])?$value['auto_id']:'';
                            $order_id     = !empty($value['order_id'])?$value['order_id']:'';
                            $product_name = !empty($value['product_name'])?$value['product_name']:'';
                            $type_name    = !empty($value['type_name'])?$value['type_name']:'';
                            $description  = !empty($value['description'])?$value['description']:'';
                            $unit_name    = !empty($value['unit_name'])?$value['unit_name']:'';
                            $hsn_code     = !empty($value['hsn_code'])?$value['hsn_code']:'';
                            $price        = !empty($value['price'])?$value['price']:'0';
                            $order_qty    = !empty($value['order_qty'])?$value['order_qty']:'';
                            $total_price  = $order_qty * number_format((float)$price, 2, '.', '');
                            $total_qty   += $order_qty;
                            $sub_total   += $total_price;

                            ?>
                                <tr class="row_<?php echo $auto_id; ?>">
                                    <td><?php echo $i; ?></td>
                                    <td style="font-size: 12px;"><?php echo $description; ?></td>
                                    <td>
                                        <input data-val="<?php echo $auto_id; ?>" type="text" id="qty" class="form-control qty qty_<?php echo $auto_id; ?> int_value" placeholder="" name="qty" value="<?php echo $order_qty; ?>" style="height: calc(1em + 1.0rem + 0px); padding: 5px; width: 70px;">
                                    </td>
                                    <td><?php echo $price; ?></td>
                                    <td>Nos</td>
                                    <td><?php echo number_format((float)$total_price, 2, '.', ''); ?></td>
                                    <td>
                                        <a data-id="<?php echo $auto_id; ?>" class="subadmin_order_update button_clr btn btn-primary" data-value="distributors" data-cntrl="order" data-func="order_process" style="background-color: #666ee8 !important;"><i class="ft-edit"></i></a>

                                        <a data-id="<?php echo $auto_id; ?>" data-progress="<?php echo $order_status; ?>" data-value="distributors" data-cntrl="order" data-func="order_process" class="delete-order button_clr btn btn-danger" style="background-color: #ff4961 !important;"><i class="ft-trash-2"></i></a>
                                    </td>
                                </tr>
                            <?php
                            $i++;
                        }
                    ?>
                </tbody>
            </table>
        </div>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
        <div class="table-responsive col-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th>HSN / SAC</th>
                        <th>Rate</th>
                        <th>Qty</th>
                        <th>Per</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $num     = 1;
                        $sub_tot = 0;
                        $tot_gst = 0;
                        $net_tot = 0;
                        foreach ($product_details as $key => $val) {

                            $description = !empty($val['description'])?$val['description']:'';
                            $unit_name   = !empty($val['unit_name'])?$val['unit_name']:'';
                            $hsn_code    = !empty($val['hsn_code'])?$val['hsn_code']:'';
                            $gst_value   = !empty($val['gst_val'])?$val['gst_val']:'0';
                            $pdt_price   = !empty($val['price'])?$val['price']:'0';
                            $pdt_qty     = !empty($val['order_qty'])?$val['order_qty']:'';
                            $pdt_value   = number_format((float)$pdt_price, 2, '.', '');

                            $gst_data  = $pdt_value - ($pdt_value * (100 / (100 + $gst_value)));
                            $price_val = $pdt_value - $gst_data;
                            $tot_price = $pdt_qty * $price_val;
                            $sub_tot  += $tot_price;

                            // GST Calculation
                            $gst_val   = $pdt_qty * $gst_data;
                            $tot_gst  += $gst_val;
                            $total_val = $pdt_qty * $pdt_value;
                            $net_tot  += $total_val;

                            ?>
                                <tr>
                                    <td><?php echo $num; ?></td>
                                    <td><?php echo $description; ?></td>
                                    <td><?php echo $hsn_code; ?></td>
                                    <td><?php echo number_format((float)$price_val, 2, '.', ''); ?></td>
                                    <td><?php echo $pdt_qty; ?></td>
                                    <td>nos</td>
                                    <td><?php echo number_format((float)$tot_price, 2, '.', '');; ?></td>
                                </tr>
                            <?php
                            $num++;
                        }
                    ?>
                </tbody>
                <tfoot>
                    <?php
                        // Round Val Details
                        $net_value  = round($net_tot);
                        $total_dis  = $net_value * $discount / 100;
                        $total_val  = $net_value - $total_dis - round($return_total);

                        // Round Val Details
                        $last_value = round($total_val);
                        $rond_total = $last_value - $total_val;
                    ?>
                    <tr>
                        <th colspan="6" class="text-right">Sub Total</th>
                        <td><?php echo number_format((float)$sub_tot, 2, '.', ''); ?></td>
                    </tr>
                    <?php
                        if($dis_state_id == $state_id)
                        {
                            $gst_value = $tot_gst / 2;

                            ?>
                                <tr>
                                    <th colspan="6" class="text-right">SGST</th>
                                    <td><?php echo number_format((float)$gst_value, 2, '.', ''); ?></td>
                                </tr>
                                <tr>
                                    <th colspan="6" class="text-right">CGST</th>
                                    <td><?php echo number_format((float)$gst_value, 2, '.', ''); ?></td>
                                </tr>
                            <?php
                        }
                        else
                        {
                            ?>
                                <tr>
                                    <th colspan="6" class="text-right">IGST</th>
                                    <td><?php echo number_format((float)$tot_gst, 2, '.', ''); ?></td>
                                </tr>
                            <?php
                        }

                        if($discount != 0)
                        {
                            ?>
                                <tr>
                                    <th colspan="6" class="text-right">Discount</th>
                                    <td><?php echo number_format((float)$total_dis, 2, '.', ''); ?></td>
                                </tr>
                            <?php
                        }
                        if($return_total != 0)
                        {
                            $return_data = round($return_total);

                            ?>
                                <tr>
                                    <th colspan="6" class="text-right">Credit Note</th>
                                    <td><?php echo number_format((float)$return_data, 2, '.', ''); ?></td>
                                </tr>
                            <?php   
                        }
                    ?>
                    <tr>
                        <th colspan="6" class="text-right">Round off</th>
                        <td><?php echo number_format((float)$rond_total, 2, '.', ''); ?></td>
                    </tr>
                    <tr>
                        <th colspan="6" class="text-right">Total</th>
                        <td><?php echo number_format((float)$last_value, 2, '.', ''); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                        if($order_status == 3)
                                        {
                                            ?>
                                                <div id="invoice-footer">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-12 text-right">
                                                            <button class="btn btn-info btn-lg my-1 process_click" data-value="distributors" data-cntrl="order" data-func="order_process" data-method="_changePackProcess" data-id="<?php echo $order_id; ?>"><i class="fa fa-spinner"></i> Process Order</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                        }

                                        else if($order_status == 5 || $order_status == 10 || $order_status == 11)
                                        {
                                            $print_link = BASE_URL.'index.php/distributors/order/print_invoice/'.$inv_value;

                                            $print_json = BASE_URL.'index.php/distributors/order/print_json/'.$inv_value;

                                            ?>
                                                <div id="invoice-footer">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-12 text-right">
                                                            <a href="<?php echo $print_link; ?>" class="btn btn-info btn-lg my-1"><i class="fa fa-print"></i> Print Invoice</a>

                                                            <a target="_blank" href="<?php echo $print_json; ?>" class="btn btn-info btn-lg my-1"><i class="ft-paperclip"></i> Print JSON</a>

                                                            <?php if($einvoicepdf) { ?>
                                                                <a target="_blank" href="<?php echo $einvoicepdf; ?>" class="btn btn-info btn-lg my-1"><i class="fa fa-print"></i> Print E-Invoice</a>
                                                            <?php }?>
                                                            
                                                            <?php if($ewaybillpdf) { ?>
                                                                <a target="_blank" href="<?php echo $ewaybillpdf; ?>" class="btn btn-info btn-lg my-1"><i class="fa fa-print"></i> Print E-Way bill</a>  
                                                            <?php }?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                    ?>
                                </div>
                            </section>
                        </div>
                    <?php
                }
                else
                {
                    ?>
                        <div class="col-sm-12 filter-design">
                            <div class="alert alert-danger text-center">
                                <b>No items found...</b>
                            </div>
                        </div>
                    <?php
                }
            ?>
        </div>
    </div>
</div>
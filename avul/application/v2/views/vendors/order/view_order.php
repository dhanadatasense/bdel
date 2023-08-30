<?php
    // Status Details
    $bill_details        = !empty($sales_data['bill_details'])?$sales_data['bill_details']:'';
    $store_details       = !empty($sales_data['store_details'])?$sales_data['store_details']:'';
    $distributor_details = !empty($sales_data['distributor_details'])?$sales_data['distributor_details']:'';
    $product_details     = !empty($sales_data['product_details'])?$sales_data['product_details']:'';
    $tax_details         = !empty($sales_data['tax_details'])?$sales_data['tax_details']:'';

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

    // Vendor Details
    $vdr_company_name = !empty($distributor_details['company_name'])?$distributor_details['company_name']:'';
    $vdr_gst_no       = !empty($distributor_details['gst_no'])?$distributor_details['gst_no']:'';
    $vdr_contact_no   = !empty($distributor_details['contact_no'])?$distributor_details['contact_no']:'';
    $vdr_email        = !empty($distributor_details['email'])?$distributor_details['email']:'';
    $vdr_address      = !empty($distributor_details['address'])?$distributor_details['address']:'';
    $vdr_state_id     = !empty($distributor_details['state_id'])?$distributor_details['state_id']:'';
    $vdr_city_id      = !empty($distributor_details['city_id'])?$distributor_details['city_id']:'';
    $vendor_type      = !empty($distributor_details['vendor_type'])?$distributor_details['vendor_type']:'';

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
    $gst_code     = !empty($store_details['gst_code'])?$store_details['gst_code']:'';

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
        $order_view = '<span class="badge badge-success">Delivered</span>';
    }
    else if($order_status == '7')
    {
        $order_view = '<span class="badge badge-success">Complete</span>';
    }
    else
    {
        $order_view = '<span class="badge badge-danger">Cancel</span>';
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

    // Shipping Status
    $ship_active  = $order_status == '4' ? 'active':'';
    $ship_checked = $order_status == '4' ? 'checked':'';

    // Invoice Status
    $inv_active   = $order_status == '5' ? 'active':'';
    $inv_checked  = $order_status == '5' ? 'checked':'';

    // Delivery Status
    $del_active   = $order_status == '6' ? 'active':'';
    $del_checked  = $order_status == '6' ? 'checked':'';

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
                    if($vendor_type == 2 && $order_status != 6)
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
                                <?php
                                    if($order_status == 2)
                                    {
                                        ?>
                                <div class="col-md-12">
                                    <label class="price_lable btn btn-warning pro_type_button progress_2 <?php echo $pro_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="2" <?php echo $pro_checked; ?>> Approw
                                    </label>

                                    <label class="price_lable btn btn-primary pro_type_button progress_3 <?php echo $pak_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="3" <?php echo $pak_checked; ?>> Packing
                                    </label>
                                </div>
                                        <?php
                                    }
                                    else if($order_status == 3)
                                    {
                                        ?>
                                <div class="col-md-12">
                                    <label class="price_lable btn btn-primary pro_type_button progress_3 <?php echo $pak_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="3" <?php echo $pak_checked; ?>> Packing
                                    </label>

                                    <label class="price_lable btn btn-info pro_type_button progress_4 <?php echo $ship_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="4" <?php echo $ship_checked; ?>> Shipping
                                    </label>
                                </div>
                                        <?php
                                    }
                                    else if($order_status == 4)
                                    {
                                        ?>
                                <div class="col-md-12">
                                    <label class="price_lable btn btn-info pro_type_button progress_4 <?php echo $ship_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="4" <?php echo $ship_checked; ?>> Shipping
                                    </label>
                                    <label class="price_lable btn btn-warning pro_type_button progress_5 <?php echo $inv_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="5" <?php echo $inv_checked; ?>> Invoice
                                    </label>
                                </div>
                                        <?php
                                    }

                                    else if($order_status == 5)
                                    {
                                        ?>
                                <div class="col-md-12">
                                    <label class="price_lable btn btn-warning pro_type_button progress_5 <?php echo $inv_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="5" <?php echo $inv_checked; ?>> Invoice
                                    </label>
                                    <label class="price_lable btn btn-success pro_type_button progress_6 <?php echo $del_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="6" <?php echo $del_checked; ?>> Delivered
                                    </label>
                                </div>
                                        <?php
                                    }
                                ?>
                                <div class="col-md-12 order_message m-t-6 hide">
                                    <div class="form-group">
                                        <label for="projectinput1">Status Message <span class="text-danger">*</span></label>
                                        <textarea name="message" id="message" class="form-control message" placeholder="Enter the Message" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group text-right">
                                        <input type="hidden" name="order_id" id="order_id" class="order_id" value="<?php echo $order_id; ?>">
                                        <input type="hidden" name="vendor_id" id="vendor_id" class="vendor_id" value="<?php echo $this->session->userdata('id'); ?>">
                                        <input type="hidden" name="value" id="value" class="value" value="vendors">
                                        <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="order">
                                        <input type="hidden" name="func" id="func" class="func" value="order_process">
                                        <button type="button" class="btn btn-success vendor_process_button process_submit" style="background-color: #28d094 !important; color: #fff;"> 
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
                                    <?php
                                        if($vendor_type == 2)
                                        {
                                            ?>
                                                <div id="invoice-customer-details" class="row pt-2">
                                                    <div class="col-sm-6 col-12 text-center text-sm-left">
                                                        <ul class="ml-2 px-0 list-unstyled">
                                                            <li class="text-bold-800"><b><?php echo $vdr_company_name; ?></b></li>
                                                            <li><?php echo $vdr_address; ?></li>
                                                            <li><b>GSTIN:</b> <?php echo $vdr_gst_no; ?></li>
                                                            <li><b>Contact No:</b> <?php echo $vdr_contact_no; ?></li>
                                                            <li><b>Email:</b> <?php echo $vdr_email; ?></li>
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
                                            <?php
                                        }
                                    ?>
                                    <!-- Invoice Customer Details -->
                                    <!-- Invoice Customer Details -->
                                    <div id="invoice-customer-details" class="row pt-2">
                                        <div class="col-sm-3 col-12 text-center">
                                            <ul class="px-0 list-unstyled">
                                                <li><b>Approw Date</b></li>
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
                                                <li><b>Due Days</b></li>
                                                <li><?php echo $due_days; ?></li>
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
                                                    if($vendor_type == 2)
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
                            <th>Qty</th>
                            <th>Rate</th>
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
                                $price        = !empty($value['price'])?$value['price']:'';
                                $order_qty    = !empty($value['order_qty'])?$value['order_qty']:'';
                                $total_price  = $order_qty * $price;
                                $total_qty   += $order_qty;
                                $sub_total   += $total_price;

                                ?>
                                    <tr class="row_<?php echo $auto_id; ?>">
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $description; ?></td>
                                        <td><?php echo $hsn_code; ?></td>
                                        <td><?php echo $order_qty; ?></td>
                                        <td><?php echo $price; ?></td>
                                        <td>Nos</td>
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
                            if($discount != 0)
                            {
                                ?>
                                    <tr>
                                        <th colspan="6" class="text-right">Discount</th>
                                        <td><?php echo $discount; ?></td>
                                    </tr>
                                <?php
                            }
                        ?>
                        <?php
                            $total_val  = $sub_total - $discount;
                            $last_total = round($total_val);
                            $round_val  = $last_total - $total_val;
                        ?>
                        <tr>
                            <th colspan="6" class="text-right">Round off</th>
                            <td><?php echo $round_val; ?></td>
                        </tr>
                        <tr>
                            <th colspan="6" class="text-right">Total</th>
                            <td><?php echo $last_total; ?></td>
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
                            <th>Amount</th>
                            <th>Order Qty</th>
                            <th>Received Qty</th>
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
                                $price        = !empty($value['price'])?$value['price']:'';
                                $order_qty    = !empty($value['order_qty'])?$value['order_qty']:'0';
                                $receive_qty  = !empty($value['receive_qty'])?$value['receive_qty']:'0';
                                $total_price  = $order_qty * $price;

                                if($order_qty == $receive_qty)
                                {
                                    $stk_link = '#';
                                }
                                else
                                {
                                    $stk_link = BASE_URL.'index.php/vendors/order/order_stock/stock_add/'.$auto_id.'/'.$order_id;
                                }

                                ?>
                                    <tr class="row_<?php echo $auto_id; ?>">
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $description; ?></td>
                                        <td><?php echo number_format((float)$total_price, 2, '.', ''); ?></td>
                                        <td><?php echo $order_qty; ?></td>
                                        <td><?php echo $receive_qty; ?></td>
                                        <td>Nos</td>
                                        <td><a href="<?php echo $stk_link; ?>" class="button_clr btn btn-warning" style="background-color: #ff9149 !important;"><i class="ft-plus-square"></i> Add </a></td>
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
                            <th>Product Name</th>
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
                                $price        = !empty($value['price'])?$value['price']:'';
                                $order_qty    = !empty($value['order_qty'])?$value['order_qty']:'';
                                $total_price  = $order_qty * $price;
                                $total_qty   += $order_qty;
                                $sub_total   += $total_price;

                                ?>
                                    <tr class="row_<?php echo $auto_id; ?>">
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $description; ?></td>
                                        <td>
                                            <input data-val="<?php echo $auto_id; ?>" type="text" id="qty" class="form-control qty qty_<?php echo $auto_id; ?>" placeholder="" name="qty" value="<?php echo $order_qty; ?>" style="height: calc(1em + 1.0rem + 0px); padding: 5px; width: 70px;">
                                        </td>
                                        <td><?php echo $price; ?></td>
                                        <td>Nos</td>
                                        <td><?php echo number_format((float)$total_price, 2, '.', ''); ?></td>
                                        <td>
                                            <a data-id="<?php echo $auto_id; ?>" class="subadmin_order_update button_clr btn btn-primary" data-value="vendors" data-cntrl="order" data-func="order_process" style="background-color: #666ee8 !important;"><i class="ft-edit"></i></a>

                                            <a data-id="<?php echo $auto_id; ?>" data-progress="<?php echo $order_status; ?>" data-value="vendors" data-cntrl="order" data-func="order_process" class="delete-order button_clr btn btn-danger" style="background-color: #ff4961 !important;"><i class="ft-trash-2"></i></a>
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
                            <th>Product Name</th>
                            <th>HSN / SAC</th>
                            <th>Qty</th>
                            <th>Rate</th>
                            <th>Per</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 1;
                            $total_qty = 0;
                            $sub_total = 0;
                            $total_gst = 0;
                            $gst_value = 0;
                            foreach ($product_details as $key => $value) {

                                $auto_id      = !empty($value['auto_id'])?$value['auto_id']:'';
                                $order_id     = !empty($value['order_id'])?$value['order_id']:'';
                                $product_name = !empty($value['product_name'])?$value['product_name']:'';
                                $type_name    = !empty($value['type_name'])?$value['type_name']:'';
                                $description  = !empty($value['description'])?$value['description']:'';
                                $unit_name    = !empty($value['unit_name'])?$value['unit_name']:'';
                                $hsn_code     = !empty($value['hsn_code'])?$value['hsn_code']:'';
                                $gst_val      = !empty($value['gst_val'])?$value['gst_val']:'';
                                $price        = !empty($value['price'])?$value['price']:'';
                                $order_qty    = !empty($value['order_qty'])?$value['order_qty']:'';
                                $total_price  = $order_qty * $price;
                                $total_qty   += $order_qty;
                                $sub_total   += $total_price;

                                // GST Calculation
                                $gst_price  = $total_price * $gst_val / 100;
                                $pre_gst    = $total_price - $gst_price;
                                $total_gst += $pre_gst;
                                $gst_value += $gst_price;

                                ?>
                                    <tr class="row_<?php echo $auto_id; ?>">
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $description; ?></td>
                                        <td><?php echo $hsn_code; ?></td>
                                        <td><?php echo $order_qty; ?></td>
                                        <td><?php echo $price; ?></td>
                                        <td>Nos</td>
                                        <td><?php echo number_format((float)$pre_gst, 2, '.', ''); ?></td>
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
                            <td><?php echo number_format((float)$total_gst, 2, '.', ''); ?></td>
                        </tr>
                        <?php

                            if($outlet_type == 1)
                            {
                                $state_gst = $gst_value / 2;
                                
                                ?>
                                    <tr>
                                        <th colspan="6" class="text-right">CGST</th>
                                        <td><?php echo number_format((float)$state_gst, 2, '.', ''); ?></td>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-right">SGST</th>
                                        <td><?php echo number_format((float)$state_gst, 2, '.', ''); ?></td>
                                    </tr>  
                                <?php
                            }
                            else
                            {
                                if($vdr_state_id == $state_id)
                                {
                                    $state_gst = $gst_value / 2;

                                    ?>
                                        <tr>
                                            <th colspan="6" class="text-right">CGST</th>
                                            <td><?php echo number_format((float)$state_gst, 2, '.', ''); ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="6" class="text-right">SGST</th>
                                            <td><?php echo number_format((float)$state_gst, 2, '.', ''); ?></td>
                                        </tr>
                                    <?php
                                }
                                else
                                {
                                    ?>  
                                        <tr>
                                            <th colspan="6" class="text-right">IGST</th>
                                            <td><?php echo number_format((float)$gst_value, 2, '.', ''); ?></td>
                                        </tr>
                                    <?php
                                }
                            }
                        ?>
                        <?php
                            if($discount != 0)
                            {
                                ?>
                                    <tr>
                                        <th colspan="6" class="text-right">Discount</th>
                                        <td><?php echo $discount; ?></td>
                                    </tr>
                                <?php
                            }
                        ?>
                        <?php
                            $total_val  = $sub_total - $discount;
                            $last_total = round($total_val);
                            $round_val  = $last_total - $total_val;
                        ?>
                        <tr>
                            <th colspan="6" class="text-right">Round off</th>
                            <td><?php echo $round_val; ?></td>
                        </tr>
                        <tr>
                            <th colspan="6" class="text-right">Total</th>
                            <td><?php echo $last_total; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
                                                            <?php
                                                        }
                                                    }
                                                    else
                                                    {
                                                        ?>
            <div class="table-responsive col-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Qty</th>
                            <th>Unit</th>
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
                                $description  = !empty($value['description'])?$value['description']:'';
                                $order_qty    = !empty($value['order_qty'])?$value['order_qty']:'';

                                ?>
                                    <tr class="row_<?php echo $auto_id; ?>">
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $description; ?></td>
                                        <td><?php echo $order_qty; ?></td>
                                        <td>nos</td>
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
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                        if($order_status == 5 || $order_status == 6)
                                        {
                                            $print_link = BASE_URL.'index.php/vendors/order/print_invoice/'.$inv_value;

                                            ?>
                                                <!-- <div id="invoice-footer">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-12 text-right">
                                                            <a href="<?php echo $print_link; ?>" class="btn btn-info btn-lg my-1"><i class="fa fa-print"></i> Print
                                                            Invoice</a>
                                                        </div>
                                                    </div>
                                                </div> -->
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

<?php
    // Status Details
    $bill_details    = !empty($sales_data['bill_details'])?$sales_data['bill_details']:'';
    $store_details   = !empty($sales_data['store_details'])?$sales_data['store_details']:'';
    $product_details = !empty($sales_data['product_details'])?$sales_data['product_details']:'';
    $tax_details     = !empty($sales_data['tax_details'])?$sales_data['tax_details']:'';

    // Bill Details
    $order_id     = !empty($bill_details['order_id'])?$bill_details['order_id']:'';
    $order_no     = !empty($bill_details['order_no'])?$bill_details['order_no']:'';
    $bill_type    = !empty($bill_details['bill_type'])?$bill_details['bill_type']:'';
    $outlet_type  = !empty($bill_details['outlet_type'])?$bill_details['outlet_type']:'';
    $emp_name     = !empty($bill_details['emp_name'])?$bill_details['emp_name']:'';
    $store_name   = !empty($bill_details['store_name'])?$bill_details['store_name']:'';
    $contact_name = !empty($bill_details['contact_name'])?$bill_details['contact_name']:'';
    $order_status = !empty($bill_details['order_status'])?$bill_details['order_status']:'';
    $discount     = !empty($bill_details['discount'])?$bill_details['discount']:'0';
    $due_days     = !empty($bill_details['due_days'])?$bill_details['due_days']:'0';
    $_ordered     = !empty($bill_details['_ordered'])?$bill_details['_ordered']:'';
    $_processing  = !empty($bill_details['_processing'])?$bill_details['_processing']:'';
    $_shiped      = !empty($bill_details['_shiped'])?$bill_details['_shiped']:'';
    $_canceled    = !empty($bill_details['_canceled'])?$bill_details['_canceled']:'';
    $_delivery    = !empty($bill_details['_delivery'])?$bill_details['_delivery']:'';
    $reason       = !empty($bill_details['reason'])?$bill_details['reason']:'';

    // Store Details
    $company_name = !empty($store_details['company_name'])?$store_details['company_name']:'';
    $contact_name = !empty($store_details['contact_name'])?$store_details['contact_name']:'';
    $mobile       = !empty($store_details['mobile'])?$store_details['mobile']:'';
    $email        = !empty($store_details['email'])?$store_details['email']:'';
    $gst_no       = !empty($store_details['gst_no'])?$store_details['gst_no']:'';
    $pan_no       = !empty($store_details['pan_no'])?$store_details['pan_no']:'';
    $tan_no       = !empty($store_details['tan_no'])?$store_details['tan_no']:'';
    $address      = !empty($store_details['address'])?$store_details['address']:'';
    $state_id     = !empty($store_details['state_id'])?$store_details['state_id']:'';
    $state_name   = !empty($store_details['state_name'])?$store_details['state_name']:'';
    $state_code   = !empty($store_details['state_code'])?$store_details['state_code']:'';
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
    else if($order_status == '9')
    {
        $order_view = '<span class="badge badge-danger">Cancel Invoice</span>';
    }
    else
    {
        $order_view = '<span class="badge badge-danger">Cancel</span>';
    }

    // Order Type
    if($bill_type == 1)
    {
        $type_view  = 'COD';
        $type_title = 'Discount';
        $type_value = $discount;
    }
    else
    {
        $type_view  = 'Credit';
        $type_title = 'Due Days';
        $type_value = $due_days;
    }

    // Date Details
    $ordered_date = '---';
    if(!empty($_ordered))
    {
        $ordered_date = date('d-M-Y h:i:s A', strtotime($_ordered));
    }

    $processing_date = '---';
    if(!empty($_processing))
    {
        $processing_date = date('d-M-Y h:i:s A', strtotime($_processing));
    }

    $canceled_date = '---';
    if(!empty($_canceled))
    {
        $canceled_date = date('d-M-Y h:i:s A', strtotime($_canceled));
    }

    $delivery_date = '---';
    if(!empty($_delivery))
    {
        $delivery_date = date('d-M-Y h:i:s A', strtotime($_delivery));
    }

    $suc_active  = $order_status == '1' ? 'active':'';
    $suc_checked = $order_status == '1' ? 'checked':'';

    // Production Status
    $pro_active  = $order_status == '2' ? 'active':'';
    $pro_checked = $order_status == '2' ? 'checked':'';

    // Shipping Status
    $ship_active  = $order_status == '3' ? 'active':'';
    $ship_checked = $order_status == '3' ? 'checked':'';

    // Cancel Status
    $can_active   = $order_status == '8' ? 'active':'';
    $can_checked  = $order_status == '8' ? 'checked':'';

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
                        <?php if(userAccess('outlet-orders-edit') == TRUE): ?>
                        <div class="row">
                            <?php
                                if(!empty($reason))
                                {
                                    if($order_status == 5)
                                    {
                                        ?>
                                            <div class="alert alert-danger" style="width: 100%;">
                                                <span><?php echo $reason; ?></span>
                                            </div>
                                        <?php
                                    }
                                }
                            ?>
                        </div>
                        <div class="row">
                            <?php
                                if($order_status == 1 || $order_status == 2)
                                {
                                    ?>
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
                                                                        if($order_status == 1)
                                                                        {
                                                                            ?>
                                                                                <label class="price_lable btn btn-success pro_type_button progress_1 <?php echo $suc_active; ?>">
                                                                                    <input type="radio" name="options" id="hatchback" class="progress_option" value="1" <?php echo $suc_checked; ?>> Success
                                                                                </label>

                                                                                <label class="price_lable btn btn-warning pro_type_button progress_2 <?php echo $pro_active; ?>">
                                                                                    <input type="radio" name="options" id="hatchback" class="progress_option discount_option" value="2" <?php echo $pro_checked; ?>> Approved
                                                                                </label>

                                                                                <label class="price_lable btn btn-danger pro_type_button progress_8 <?php echo $can_active; ?>">
                                                                                    <input type="radio" name="options" id="hatchback" class="progress_option" value="8" <?php echo $can_checked; ?>> Cancel
                                                                                </label>
                                                                            <?php
                                                                        }
                                                                        else if($order_status == 2)
                                                                        {
                                                                            ?>
                                                                                <label class="price_lable btn btn-warning pro_type_button progress_2 <?php echo $pro_active; ?>">
                                                                                    <input type="radio" name="options" id="hatchback" class="progress_option" value="2" <?php echo $pro_checked; ?>> Approved
                                                                                </label>

                                                                                <label class="price_lable btn btn-success pro_type_button progress_7 <?php echo $ship_active; ?>">
                                                                                    <input type="radio" name="options" id="hatchback" class="progress_option" value="7" <?php echo $ship_checked; ?>> Complete
                                                                                </label>
                                                                            <?php
                                                                        }  
                                                                    ?>
                                                                </div>
                                                                <div class="col-md-12 order_discount m-t-6 hide">
                                                                    <div class="row clearfix">
                                                                        <div class="col-md-3">
                                                                            <label for="firstName2">Discount</label>
                                                                            <input type="text" id="discount" class="form-control discount" placeholder="Discount" name="discount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" value="<?php echo $discount; ?>" maxlength="2">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="firstName2">Due Days</label>
                                                                            <input type="text" id="due_days" class="form-control due_days" placeholder="Due Days" name="due_days" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" value="<?php echo $due_days; ?>" maxlength="2">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 order_message m-t-6 hide">
                                                                    <div class="form-group">
                                                                        <label for="projectinput1">Status Message <span class="text-danger">*</span></label>
                                                                        <textarea name="message" id="message" class="form-control message" placeholder="Enter the Message" rows="3"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group text-right">
                                                                        <input type="hidden" name="order_id" id="order_id" class="order_id" value="<?php echo $order_id; ?>">
                                                                        <input type="hidden" name="pre_status" id="pre_status" class="pre_status" value="<?php echo $order_status; ?>">
                                                                        
                                                                        <input type="hidden" name="value" id="value" class="value" value="admin">
                                                                        <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="order">
                                                                        <input type="hidden" name="func" id="func" class="func" value="order_process">
                                                                        <button type="button" class="btn btn-success process_button process_submit" style="background-color: #28d094 !important; color: #fff;"> 
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
                                    <?php
                                }
                            ?>
                        </div>
                        <?php endif; ?>
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
                                            <ul class="px-0 list-unstyled">
                                                <li class="text-bold-800"><b><?php echo $company_name; ?></b></li>
                                                <li><?php echo $contact_name; ?></li>
                                                <li><?php echo $address; ?></li>
                                                <li>GST: <?php echo $gst_no; ?></li>
                                                <li>Contact No: <?php echo $mobile; ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Invoice Customer Details -->
                                    <!-- Invoice Customer Details -->
                                    <div id="invoice-customer-details" class="row pt-2">
                                        <div class="col-sm-3 col-12 text-center">
                                            <ul class="px-0 list-unstyled">
                                                <li><b>State Code</b></li>
                                                <li><?php echo $gst_code; ?></li>
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
                                    <div id="invoice-customer-details" class="row pt-2">
                                        <div class="col-sm-3 col-12 text-center">
                                            <ul class="px-0 list-unstyled">
                                                <li><b>Bill Date</b></li>
                                                <li><?php echo $ordered_date; ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-3 col-12 text-center">
                                            <ul class="px-0 list-unstyled">
                                                <li><b>Production Date</b></li>
                                                <li><?php echo $processing_date; ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-3 col-12 text-center">
                                            <ul class="px-0 list-unstyled">
                                                <li><b>Cancel Date</b></li>
                                                <li><?php echo $canceled_date; ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-3 col-12 text-center">
                                            <ul class="px-0 list-unstyled">
                                                <li><b>Complete Date</b></li>
                                                <li><?php echo $delivery_date; ?></li>
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
                                                    if($order_status == 1)
                                                    {
                                                        ?>
    <div class="table-responsive col-12">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 10px;">#</th>
                    <th>Product Name</th>
                    <th style="width: 120px;">Rate</th>
                    <th style="width: 110px;">Qty</th>
                    <th style="width: 110px;">Unit</th>
                    <th style="width: 110px;">Amount</th>
                    <?php if(userAccess('outlet-orders-edit') == TRUE || userAccess('outlet-orders-delete') == TRUE): ?>
                        <th style="width: 130px;">Action</th>
                    <?php endif; ?>
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
                        $total_price  = $order_qty * number_format((float)$price, 2, '.', '');

                        ?>
                            <tr class="row_<?php echo $auto_id; ?>">
                                <td><?php echo $i; ?></td>
                                <td><?php echo $description; ?></td>
                                <td>
                                    <input data-val="<?php echo $auto_id; ?>" type="text" id="rate" class="form-control rate rate_<?php echo $auto_id ?> int_value" placeholder="" name="rate" value="<?php echo $price; ?>" style="height: calc(1em + 1.0rem + 0px); padding: 5px;">
                                </td>
                                <td>
                                    <input data-val="<?php echo $auto_id; ?>" type="text" id="qty" class="form-control qty qty_<?php echo $auto_id; ?> int_value" placeholder="" name="qty" value="<?php echo $order_qty; ?>" style="height: calc(1em + 1.0rem + 0px); padding: 5px;">
                                </td>
                                <td><span class=""><?php echo $unit_name; ?></span></td>
                                <td><span class="amount_<?php echo $auto_id; ?>"><?php echo number_format($total_price, 2); ?></span></td>
                                <?php if(userAccess('outlet-orders-edit') == TRUE || userAccess('outlet-orders-delete') == TRUE): ?>
                                <td>
                                    <?php if(userAccess('outlet-orders-edit') == TRUE): ?>
                                    <a data-id="<?php echo $auto_id; ?>" class="order_update button_clr btn btn-primary" data-value="admin" data-cntrl="order" data-func="order_process" style="background-color: #666ee8 !important;"><i class="ft-edit"></i></a>
                                    <?php endif; ?>
                                    <?php if(userAccess('outlet-orders-delete') == TRUE): ?>
                                    <a data-id="<?php echo $auto_id; ?>" data-progress="<?php echo $order_status; ?>" data-value="admin" data-cntrl="order" data-func="order_process" class="delete-order button_clr btn btn-danger" style="background-color: #ff4961 !important;"><i class="ft-trash-2"></i></a>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?>
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
                                                    else if($order_status == 2)
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
                    <!-- <th>Received Qty</th> -->
                    <th>Unit</th>
                    <th>Status</th>
                    <th>Distributor</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i = 1;
                    foreach ($product_details as $key => $value) {

                        $auto_id        = !empty($value['auto_id'])?$value['auto_id']:'';
                        $order_id       = !empty($value['order_id'])?$value['order_id']:'';
                        $dis_name       = !empty($value['dis_name'])?$value['dis_name']:'';
                        $product_name   = !empty($value['product_name'])?$value['product_name']:'';
                        $type_name      = !empty($value['type_name'])?$value['type_name']:'';
                        $description    = !empty($value['description'])?$value['description']:'';
                        $unit_name      = !empty($value['unit_name'])?$value['unit_name']:'';
                        $price          = !empty($value['price'])?$value['price']:'0';
                        $order_qty      = !empty($value['order_qty'])?$value['order_qty']:'0';
                        $receive_qty    = !empty($value['receive_qty'])?$value['receive_qty']:'0';
                        $product_status = !empty($value['product_status'])?$value['product_status']:'0';
                        $total_price    = $order_qty * number_format((float)$price, 2, '.', '');

                        if($product_status == '1')
                        {
                            $product_view = '<span class="badge badge-success">Success</span>';
                        }
                        else if($product_status == '2')
                        {
                            $product_view = '<span class="badge badge-warning">Approved</span>';
                        }
                        else if($product_status == '3')
                        {
                            $product_view = '<span class="badge badge-primary">Packing</span>';
                        }
                        else if($product_status == '4')
                        {
                            $product_view = '<span class="badge badge-info">Ready to shipping</span>';
                        }
                        else if($product_status == '5')
                        {
                            $product_view = '<span class="badge badge-warning">Invoice</span>';
                        }
                        else if($product_status == '10')
                        {
                            $product_view = '<span class="badge badge-warning">Shipping</span>';
                        }
                        else if($product_status == '11')
                        {
                            $product_view = '<span class="badge badge-primary">Delivered</span>';
                        }
                        else if($product_status == '6')
                        {
                            $product_view = '<span class="badge badge-success">Delivered</span>';
                        }
                        else if($product_status == '7')
                        {
                            $product_view = '<span class="badge badge-success">Complete</span>';
                        }
                        else if($product_status == '9')
                        {
                            $product_view = '<span class="badge badge-danger">Cancel Invoice</span>';
                        }
                        else
                        {
                            $product_view = '<span class="badge badge-danger">Cancel</span>';
                        }

                        if($order_qty == $receive_qty)
                        {
                            $stk_link = '#';
                        }
                        else
                        {
                            $stk_link = BASE_URL.'index.php/admin/order/overall_order/stock_list/'.$auto_id.'/'.$order_id;
                        }

                        ?>
                            <tr class="row_<?php echo $auto_id; ?>">
                                <td><?php echo $i; ?></td>
                                <td><?php echo $description; ?></td>
                                <td><?php echo number_format($total_price, 2); ?></td>
                                <td><?php echo $order_qty; ?></td>
                                <!-- <td><?php echo $receive_qty; ?></td> -->
                                <td>nos</td>
                                <td><?php echo $product_view; ?></td>
                                <td><?php echo $dis_name; ?></td>
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
                    $num     = 1;
                    $sub_tot = 0;
                    $tot_gst = 0;
                    $net_tot = 0;
                    foreach ($product_details as $key => $value) {

                        $auto_id      = !empty($value['auto_id'])?$value['auto_id']:'';
                        $order_id     = !empty($value['order_id'])?$value['order_id']:'';
                        $product_name = !empty($value['product_name'])?$value['product_name']:'';
                        $type_name    = !empty($value['type_name'])?$value['type_name']:'';
                        $description  = !empty($value['description'])?$value['description']:'';
                        $unit_name    = !empty($value['unit_name'])?$value['unit_name']:'';
                        $hsn_code     = !empty($value['hsn_code'])?$value['hsn_code']:'';
                        $gst_value    = !empty($value['gst_val'])?$value['gst_val']:'';
                        $pdt_price    = !empty($value['price'])?$value['price']:'0';
                        $pdt_qty      = !empty($value['order_qty'])?$value['order_qty']:'';
                        $pdt_value    = number_format((float)$pdt_price, 2, '.', '');

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
                            <tr class="row_<?php echo $auto_id; ?>">
                                <td><?php echo $num; ?></td>
                                <td><?php echo $description; ?></td>
                                <td><?php echo $hsn_code; ?></td>
                                <td><?php echo $pdt_qty; ?></td>
                                <td><?php echo number_format((float)$price_val, 2, '.', '');; ?></td>
                                <td>Nos</td>
                                <td><?php echo number_format((float)$tot_price, 2, '.', ''); ?></td>
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
                    $total_val  = $net_value - $total_dis;

                    // Round Val Details
                    $last_value = round($total_val);
                    $rond_total = $last_value - $total_val;
                ?>
                <tr>
                    <th colspan="6" class="text-right">Sub Total</th>
                    <td><?php echo number_format((float)$sub_tot, 2, '.', ''); ?></td>
                </tr>
                <?php

                    if($outlet_type == 1)
                    {
                        $state_gst = $tot_gst / 2;
                        
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
                        if($gst_code == 33)
                        {
                            $state_gst = $tot_gst / 2;

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
                                    <td><?php echo number_format((float)$tot_gst, 2, '.', ''); ?></td>
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
                                <td><?php echo number_format((float)$total_dis, 2, '.', ''); ?></td>
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
                                    <div id="invoice-items-details" class="pt-2">
                                        <div class="row">
                                            <?php
                                                if($order_status == '7' || $order_status == '8')
                                                {
                                                    if(!empty($tax_details))
                                                    {
                                                        ?>
    <!-- <div class="table-responsive col-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" rowspan="2">HSN / SAC</th>
                    <th class="text-center" rowspan="2">Taxable Value</th>
                    <?php
                        if($gst_code == 33)
                        {
                            ?>
                                <th class="text-center" colspan="2">SGST</th>
                                <th class="text-center" colspan="2">CGST</th>
                            <?php
                        }
                        else
                        {
                            ?>
                                <th class="text-center" colspan="2">IGST</th>
                            <?php
                        }
                    ?>
                    <th class="text-center" rowspan="2">Total Amount</th>
                </tr>
                <?php
                    if($gst_code == 33)
                    {
                        ?>  
                            <tr>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Net</th>
                            </tr>
                        <?php
                    }
                    else
                    {
                        ?>
                            <tr>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Amount</th>
                            </tr>
                        <?php
                    }
                ?>
            </thead>
            <tbody>
                <?php
                    foreach ($tax_details as $key => $value) {
                        

                    }
                ?>
            </tbody>
        </table>
    </div> -->
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <!-- <div id="invoice-footer">
                                        <div class="row">
                                            <div class="col-sm-12 col-12 text-right">
                                                <a href="javascript:window.print()" class="btn btn-info btn-print btn-lg my-1"><i class="fa fa-print"></i> Print
                                                Invoice</a>
                                            </div>
                                        </div>
                                    </div> -->
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
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
                                                   
                                                
                                                ?>
                                           
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
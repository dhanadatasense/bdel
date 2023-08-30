<?php

    // Status Details
    $bill_details    = !empty($purchase_data['bill_details'])?$purchase_data['bill_details']:'';
    $admin_details   = !empty($purchase_data['admin_details'])?$purchase_data['admin_details']:'';
    $distributor_det = !empty($purchase_data['distributor_details'])?$purchase_data['distributor_details']:'';
    $order_details   = !empty($purchase_data['order_details'])?$purchase_data['order_details']:'';

    // Bill Details
    $order_id       = !empty($bill_details['order_id'])?$bill_details['order_id']:'';
    $order_no       = !empty($bill_details['order_no'])?$bill_details['order_no']:'';
    $distributor_id = !empty($bill_details['distributor_id'])?$bill_details['distributor_id']:'';
    $order_date     = !empty($bill_details['order_date'])?$bill_details['order_date']:'';
    $order_status   = !empty($bill_details['order_status'])?$bill_details['order_status']:'';
    $_ordered       = !empty($bill_details['_ordered'])?$bill_details['_ordered']:'';
    $_processing    = !empty($bill_details['_processing'])?$bill_details['_processing']:'';
    $_packing       = !empty($bill_details['_packing'])?$bill_details['_packing']:'';
    $_shiped        = !empty($bill_details['_shiped'])?$bill_details['_shiped']:'';
    $_delivery      = !empty($bill_details['_delivery'])?$bill_details['_delivery']:'';
    $_complete      = !empty($bill_details['_complete'])?$bill_details['_complete']:'';
    $_canceled      = !empty($bill_details['_canceled'])?$bill_details['_canceled']:'';
    $reason         = !empty($bill_details['reason'])?$bill_details['reason']:'';

    // Admin Details
    $adm_username = !empty($admin_details['username'])?$admin_details['username']:'';
    $adm_mobile   = !empty($admin_details['mobile'])?$admin_details['mobile']:'';
    $adm_address  = !empty($admin_details['address'])?$admin_details['address']:'';
    $adm_gst_no   = !empty($admin_details['gst_no'])?$admin_details['gst_no']:'';
    $adm_state_id = !empty($admin_details['state_id'])?$admin_details['state_id']:'';

    $company_name = !empty($distributor_det['company_name'])?$distributor_det['company_name']:'';
    $mobile       = !empty($distributor_det['mobile'])?$distributor_det['mobile']:'';
    $email        = !empty($distributor_det['email'])?$distributor_det['email']:'';
    $gst_no       = !empty($distributor_det['gst_no'])?$distributor_det['gst_no']:'';
    $address      = !empty($distributor_det['address'])?$distributor_det['address']:'';
    $state_id     = !empty($distributor_det['state_id'])?$distributor_det['state_id']:'';

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

    $packing_date = '---';
    if(!empty($_packing))
    {
        $packing_date = date('d-M-Y h:i:s A', strtotime($_packing));
    }

    $shipping_date = '---';
    if(!empty($_shiped))
    {
        $shipping_date = date('d-M-Y h:i:s A', strtotime($_shiped));
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

    $canceled_date = '---';
    if(!empty($_canceled))
    {
        $canceled_date = date('d-M-Y h:i:s A', strtotime($_canceled));
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
        $order_view = '<span class="badge badge-danger">Cancel Invoice</span>';
    }
    else
    {
        $order_view = '<span class="badge badge-danger">Cancel</span>';
    }

    // Suucess Status
    $suc_active  = $order_status == '1' ? 'active':'';
    $suc_checked = $order_status == '1' ? 'checked':'';

    // Process Status
    $pro_active  = $order_status == '2' ? 'active':'';
    $pro_checked = $order_status == '2' ? 'checked':'';

    // Packing Status
    $pak_active  = $order_status == '3' ? 'active':'';
    $pak_checked = $order_status == '3' ? 'checked':'';

    // Invoice Status
    $invoice_active   = $order_status == '4' ? 'active':'';
    $invoice_checked  = $order_status == '4' ? 'checked':'';

    // Shipping Status
    $shipping_active   = $order_status == '10' ? 'active':'';
    $shipping_checked  = $order_status == '10' ? 'checked':'';

    // Delivered Status
    $delivered_active   = $order_status == '11' ? 'active':'';
    $delivered_checked  = $order_status == '11' ? 'checked':'';

    // Complete Status
    $com_active   = $order_status == '5' ? 'active':'';
    $com_checked  = $order_status == '5' ? 'checked':'';

    // Cancel Status
    $can_active   = $order_status == '5' ? 'active':'';
    $can_checked  = $order_status == '5' ? 'checked':'';
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
                if(!empty($purchase_data))
                {
                    ?>
                        <div class="row">
                            <?php
                                if($order_status == 11)
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
                                    <label class="price_lable btn btn-info pro_type_button progress_11 <?php echo $delivered_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="11" <?php echo $delivered_checked; ?>> Delivered
                                    </label>

                                    <label class="price_lable btn btn-success pro_type_button progress_5 <?php echo $com_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="5" <?php echo $com_checked; ?>> Complete
                                    </label>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group text-right">
                                        <input type="hidden" name="order_id" id="order_id" class="order_id" value="<?php echo $order_id; ?>">
                                        
                                        <input type="hidden" name="value" id="value" class="value" value="distributors">
                                        <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="purchase">
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
                                            <p># <?php echo $order_no; ?></p>
                                            <div class="pb-sm-3"><?php echo $order_view; ?></div>
                                        </div>
                                    </div>
                                    <!-- Invoice Company Details -->
                                    <!-- Invoice Customer Details -->
                                    <div id="invoice-customer-details" class="row pt-2">
                                        <div class="col-sm-6 col-12 text-center text-sm-left">
                                            <ul class="ml-2 px-0 list-unstyled">
                                                <li class="text-bold-800"><b><?php echo $adm_username; ?></b></li>
                                                <li><?php echo $adm_address; ?></li>
                                                <li><b>GSTIN:</b> <?php echo $adm_gst_no; ?></li>
                                                <li><b>Contact No:</b> <?php echo $adm_mobile; ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-6 col-12 text-center text-sm-right">
                                            <ul class="px-0 list-unstyled">
                                                <li class="text-bold-800"><b><?php echo $company_name; ?></b></li>
                                                <li><?php echo $address; ?></li>
                                                <li><b>GSTIN:</b> <?php echo $gst_no; ?></li>
                                                <li><b>Contact No:</b> <?php echo $mobile; ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Invoice Customer Details -->
                                    <div id="invoice-customer-details" class="row pt-2">
                                        <div class="col-sm-3 col-12 text-center">
                                            <ul class="px-0 list-unstyled">
                                                <li><b>Bill Date</b></li>
                                                <li><?php echo $ordered_date; ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-3 col-12 text-center">
                                            <ul class="px-0 list-unstyled">
                                                <li><b>Approved Date</b></li>
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
                                                <li><?php echo $complete_date; ?></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div id="invoice-items-details invoice_tbl" class="pt-2">
                                        <div class="row">
                                            <?php
                                                if(!empty($order_details))
                                                {
                                                    ?>
                                                        <div class="table-responsive col-12">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Product Name</th>
                                                                        <th>Qty</th>
                                                                        <th>Per</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                            <?php
                                $num     = 1;
                                $sub_tot = 0;
                                foreach ($order_details as $key => $value) {
                                    $auto_id         = !empty($value['auto_id'])?$value['auto_id']:'';
                                    $order_id        = !empty($value['order_id'])?$value['order_id']:'';
                                    $assproduct_id   = !empty($value['assproduct_id'])?$value['assproduct_id']:'';
                                    $description     = !empty($value['description'])?$value['description']:'';
                                    $category_id     = !empty($value['category_id'])?$value['category_id']:'';
                                    $product_id      = !empty($value['product_id'])?$value['product_id']:'';
                                    $type_id         = !empty($value['type_id'])?$value['type_id']:'';
                                    $product_price   = !empty($value['product_price'])?$value['product_price']:'0';
                                    $product_qty     = !empty($value['product_qty'])?$value['product_qty']:'0';
                                    $receive_qty     = !empty($value['receive_qty'])?$value['receive_qty']:'0';
                                    $product_unit    = !empty($value['product_unit'])?$value['product_unit']:'';
                                    $gst_value       = !empty($value['gst_val'])?$value['gst_val']:'0';
                                    $unit_name       = !empty($value['unit_name'])?$value['unit_name']:'';
                                    $hsn_code        = !empty($value['hsn_code'])?$value['hsn_code']:'';
                                    $product_type    = !empty($value['product_type'])?$value['product_type']:'';
                                    $product_process = !empty($value['product_process'])?$value['product_process']:'';

                                    $product_val = $product_qty * $product_price;
                                    $sub_tot    += $product_val;

                                    ?>
                                        <tr>
                                            <td><?php echo $num; ?></td>
                                            <td><?php echo $description; ?></td>
                                            <td><?php echo $product_qty; ?></td>
                                            <td>nos</td>
                                        </tr>
                                    <?php

                                    $num++;
                                }
                            ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
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
<?php

    // Status Details
    $bill_details    = !empty($purchase_data['bill_details'])?$purchase_data['bill_details']:'';
    $admin_details   = !empty($purchase_data['admin_details'])?$purchase_data['admin_details']:'';
    $distributor_det = !empty($purchase_data['distributor_details'])?$purchase_data['distributor_details']:'';
    $order_details   = !empty($purchase_data['order_details'])?$purchase_data['order_details']:'';
    $return_details  = !empty($purchase_data['return_details'])?$purchase_data['return_details']:'';

    // Return Details
    $return_total    = !empty($return_details['return_total'])?$return_details['return_total']:'0';

    // Bill Details
    $po_id          = !empty($bill_details['po_id'])?$bill_details['po_id']:'';
    $po_no          = !empty($bill_details['po_no'])?$bill_details['po_no']:'';
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
    $invoice_id     = !empty($bill_details['invoice_id'])?$bill_details['invoice_id']:'';
    $invoice_no     = !empty($bill_details['invoice_no'])?$bill_details['invoice_no']:'';
    $inv_random     = !empty($bill_details['inv_random'])?$bill_details['inv_random']:'';

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
        $order_view = '<span class="badge badge-success">Completed</span>';
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
              //  if(userAccess('distributors-order-view')):

                if(!empty($purchase_data))
                {
                    ?>
                        <?php 
                        //if(userAccess('distributors-order-edit')): ?>
                        <div class="row">
                            <?php
                                if(!empty($reason))
                                {
                                    if($order_status == 7)
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
                                if($order_status == 1 || $order_status == 2 || $order_status == 3 || $order_status == 4 || $order_status == 10)
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
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="2" <?php echo $pro_checked; ?>> Approved
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

                                    <label class="price_lable btn btn-primary pro_type_button progress_3 <?php echo $pak_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="3" <?php echo $pak_checked; ?>> Packing
                                    </label>

                                    <label class="price_lable btn btn-danger pro_type_button progress_8 <?php echo $can_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="8" <?php echo $can_checked; ?>> Cancel
                                    </label>
                                    <?php
                                }
                                else if($order_status == 3)
                                {
                                    ?>
                                    <label class="price_lable btn btn-primary pro_type_button progress_3 <?php echo $pak_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="3" <?php echo $pak_checked; ?>> Packing
                                    </label>

                                    <label class="price_lable btn btn-info pro_type_button progress_4 <?php echo $invoice_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="4" <?php echo $invoice_checked; ?>> Invoice
                                    </label>
                                    <?php
                                }
                                else if($order_status == 4)
                                {
                                    ?>
                                    <label class="price_lable btn btn-info pro_type_button progress_4 <?php echo $invoice_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="4" <?php echo $invoice_checked; ?>> Invoice
                                    </label>

                                    <label class="price_lable btn btn-warning pro_type_button progress_10 <?php echo $shipping_active; ?>">
                                        <input type="radio" name="options" id="hatchback" class="progress_option" value="10" <?php echo $shipping_checked; ?>> Shipping
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
                            </div>
                                                                <div class="col-md-12 m-t-10">
                                                                    <div class="form-group text-right">
                                                                        <input type="hidden" name="order_id" id="order_id" class="order_id" value="<?php echo $po_id; ?>">
                                                                        <input type="hidden" name="invoice_id" id="invoice_id" class="invoice_id" value="<?php echo $invoice_id; ?>">
                                                                        <input type="hidden" name="value" id="value" class="value" value="distributors">
                                                                        <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="Branchpurchase">
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
                        <?php 
                  //  endif; ?>
                        <div class="row">
                            <section class="card" style="width: 100%;">
                                <div id="invoice-template" class="card-body p-4">
                                    <!-- Invoice Company Details -->
                                    <div id="invoice-company-details" class="row">
                                        <div class="col-sm-6 col-12 text-center text-sm-left">
                                            <div class="media row">
                                                <div class="col-12 col-sm-6 col-xl-6">
                                                <img class="brand-logo" alt="modern admin logo" src="<?php echo BASE_URL; ?>app-assets/images/logob.jpg">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12 text-center text-sm-right">
                                            <h2>PURCHASE</h2>
                                            <p># <?php echo $po_no; ?></p>
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
                        <th style="width: 120px;">Qty</th>
                        <th style="width: 120px;">Amount</th>
                       
                            <th style="width: 120px;">Action</th>
                      
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i=1;
                        $net_total = 0;
                        $round_tot = 0;
                        foreach ($order_details as $key => $value) {
                            $auto_id         = !empty($value['auto_id'])?$value['auto_id']:'';
                            $po_id           = !empty($value['po_id'])?$value['po_id']:'';
                            $assproduct_id   = !empty($value['assproduct_id'])?$value['assproduct_id']:'';
                            $description     = !empty($value['description'])?$value['description']:'';
                            $category_id     = !empty($value['category_id'])?$value['category_id']:'';
                            $product_id      = !empty($value['product_id'])?$value['product_id']:'';
                            $type_id         = !empty($value['type_id'])?$value['type_id']:'';
                            $product_price   = !empty($value['product_price'])?$value['product_price']:'';
                            $product_qty     = !empty($value['product_qty'])?$value['product_qty']:'';
                            $receive_qty     = !empty($value['receive_qty'])?$value['receive_qty']:'';
                            $product_unit    = !empty($value['product_unit'])?$value['product_unit']:'';
                            $unit_name       = !empty($value['unit_name'])?$value['unit_name']:'';
                            $product_type    = !empty($value['product_type'])?$value['product_type']:'';
                            $product_process = !empty($value['product_process'])?$value['product_process']:'';
                            $net_total       = $product_qty * $product_price;
                            $round_tot      += $net_total;

                            ?>
                                <tr class="row_<?php echo $i; ?>">
                                    <td class="font-12" style="width: 10px;"><?php echo $i; ?></td>
                                    <td class="font-12"><?php echo mb_strimwidth($description, 0, 60, '...'); ?></td>
                                    <td class="font-12"><?php echo $product_price; ?></td>
                                    <td class="font-12">
                                        <input data-val="<?php echo $auto_id; ?>" type="hidden" id="rate" class="form-control rate rate_<?php echo $auto_id ?>" placeholder="" name="rate" value="<?php echo $product_price; ?>" style="height: calc(1em + 1.0rem + 0px); padding: 5px;">

                                        <input data-val="<?php echo $auto_id; ?>" type="text" id="qty" class="form-control qty qty_<?php echo $auto_id; ?> int_value" placeholder="" name="qty" value="<?php echo $product_qty; ?>" style="height: calc(1em + 1.0rem + 0px); padding: 5px;">
                                    </td>
                                    <td class="font-12" style="width: 120px;"><span class="amount_<?php echo $auto_id; ?>"><?php echo number_format($net_total, 2); ?></span></td>
                                    <?php //if(userAccess('distributors-order-edit') == TRUE || userAccess('distributors-order-delete') == TRUE): ?>
                                    <td class="font-12" style="width: 13%;">
                                       
                                            <a data-id="<?php echo $auto_id; ?>" class="order_update button_clr btn btn-primary" data-value="admin" data-cntrl="distributorsorder" data-func="order_process" style="background-color: #666ee8 !important;"><i class="ft-edit"></i></a>
                                        
                                       
                                            <a data-id="<?php echo $auto_id; ?>" data-progress="<?php echo $order_status; ?>" data-value="admin" data-cntrl="distributorsorder" data-func="order_process" data-row="<?php echo $i; ?>" class="delete-order button_clr btn btn-danger" style="background-color: #ff4961 !important;"><i class="ft-trash-2"></i></a>
                                        
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

                                                    else if($order_status == 2)
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
                        foreach ($order_details as $key => $value) {
                            $auto_id         = !empty($value['auto_id'])?$value['auto_id']:'';
                            $po_id           = !empty($value['po_id'])?$value['po_id']:'';
                            $assproduct_id   = !empty($value['assproduct_id'])?$value['assproduct_id']:'';
                            $description     = !empty($value['description'])?$value['description']:'';
                            $category_id     = !empty($value['category_id'])?$value['category_id']:'';
                            $product_id      = !empty($value['product_id'])?$value['product_id']:'';
                            $type_id         = !empty($value['type_id'])?$value['type_id']:'';
                            $product_price   = !empty($value['product_price'])?$value['product_price']:'';
                            $product_qty     = !empty($value['product_qty'])?$value['product_qty']:'';
                            $receive_qty     = !empty($value['receive_qty'])?$value['receive_qty']:'';
                            $product_unit    = !empty($value['product_unit'])?$value['product_unit']:'';
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
                                    <td><?php echo $hsn_code; ?></td>
                                    <td><?php echo $product_qty; ?></td>
                                    <td><?php echo number_format((float)$product_price, 2, '.', ''); ?></td>
                                    <td>nos</td>
                                    <td><?php echo number_format((float)$product_val, 2, '.', ''); ?></td>
                                </tr>
                            <?php

                            $num++;
                        }
                    ?>
                </tbody>
                <tfoot>
                    <?php
                        // Round Val Details
                        $net_value  = round($sub_tot);
                        $rond_total = $net_value - $sub_tot;
                    ?>
                    <tr>
                        <th colspan="6" class="text-right">Sub Total</th>
                        <td><?php echo number_format((float)$sub_tot, 2, '.', ''); ?></td>
                    </tr>
                    <tr>
                        <th colspan="6" class="text-right">Round off</th>
                        <td><?php echo number_format((float)$rond_total, 2, '.', ''); ?></td>
                    </tr>
                    <tr>
                        <th colspan="6" class="text-right">Total</th>
                        <td><?php echo number_format((float)$net_value, 2, '.', ''); ?></td>
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
                        <th style="width: 5%;">#</th>
                        <th style="width: 40%;">Product Name</th>
                        <th style="width: 10%;">Amount</th>
                        <th style="width: 10%;">Order Qty</th>
                        <th style="width: 10%;">Available Qty</th>
                        <!-- <th style="width: 10%;">Received Qty</th> -->
                        <th style="width: 10%;">Unit</th>
                        <th style="width: 15%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i=1;
                        foreach ($order_details as $key => $value) {
                            $auto_id         = !empty($value['auto_id'])?$value['auto_id']:'';
                            $po_id           = !empty($value['po_id'])?$value['po_id']:'';
                            $description     = !empty($value['description'])?$value['description']:'';
                            $product_price   = !empty($value['product_price'])?$value['product_price']:'';
                            $product_qty     = !empty($value['product_qty'])?$value['product_qty']:'0';
                            $receive_qty     = !empty($value['receive_qty'])?$value['receive_qty']:'0';
                            $product_stock   = !empty($value['product_stock'])?$value['product_stock']:'0';
                            $unit_name       = !empty($value['unit_name'])?$value['unit_name']:'';
                            $pack_status     = !empty($value['pack_status'])?$value['pack_status']:'';
                            $net_total       = $product_qty * $product_price;
                            $total_value     = round($net_total);

                            if($product_qty == $receive_qty)
                            {
                                $stk_link = '#';
                            }
                            else
                            {
                                $stk_link = BASE_URL.'index.php/admin/distributorsorder/order_stock/stock_add/'.$auto_id.'/'.$po_id;
                            }

                            ?>
                                <tr class="row_<?php echo $i; ?>">
                                    <td><?php echo $i; ?></td>
                                    <td style="font-size: 13px;"><?php echo $description; ?></td>
                                    <td><?php echo number_format((float)$total_value, 2, '.', ''); ?></td>
                                    <td><?php echo $product_qty; ?></td>
                                    <td><?php echo $product_stock; ?></td>
                                    <!-- <td><?php echo $receive_qty; ?></td> -->
                                    <td>nos</td>
                                    <!-- <td class="font-12"><a href="<?php echo $stk_link; ?>" class="button_clr btn btn-warning" style="background-color: #ff9149 !important;"><i class="ft-plus-square"></i> Add </a></td> -->
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
                                                    <button class="btn btn-warning pack_btn" data-id="<?php echo $auto_id; ?>" data-value="distributors" data-cntrl="Branchpurchase" data-func="order_process" data-method="_changePackStatus" style="padding: 4px;"><i class="ft-rotate-cw"></i> </button>

                                                    <button class="btn btn-danger del_bth" data-id="<?php echo $auto_id; ?>" data-row="<?php echo $i; ?>" data-value="distributors" data-cntrl="Branchpurchase" data-func="order_process" data-method="_deletePackStatus" style="padding: 4px;"><i class="ft-trash-2"></i> </button>
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
                        foreach ($order_details as $key => $val) {
                            $description = !empty($val['description'])?$val['description']:'';
                            $pdt_price   = !empty($val['product_price'])?$val['product_price']:'';
                            $pdt_qty     = !empty($val['product_qty'])?$val['product_qty']:'';
                            $hsn_code    = !empty($val['hsn_code'])?$val['hsn_code']:'';
                            $gst_value   = !empty($val['gst_val'])?$val['gst_val']:'0';

                            $gst_data  = $pdt_price - ($pdt_price * (100 / (100 + $gst_value)));
                            $price_val = $pdt_price - $gst_data;
                            $tot_price = $pdt_qty * $price_val;
                            $sub_tot  += $tot_price;

                            // GST Calculation
                            $gst_val   = $pdt_qty * $gst_data;
                            $tot_gst  += $gst_val;
                            $total_val = $pdt_qty * $pdt_price;
                            $net_tot  += $total_val;

                            ?>
                                <tr>
                                    <td><?php echo $num; ?></td>
                                    <td><?php echo $description; ?></td>
                                    <td><?php echo $hsn_code; ?></td>
                                    <td><?php echo $pdt_qty;?></td>
                                    <td><?php echo number_format((float)$price_val, 2, '.', ''); ?></td>
                                    <td>nos</td>
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
                        $total_amt  = $net_tot - $return_total;
                        $net_value  = round($total_amt);
                        $rond_total = $net_value - $total_amt;
                    ?>
                    <tr>
                        <th colspan="6" class="text-right">Sub Total</th>
                        <td><?php echo number_format((float)$sub_tot, 2, '.', ''); ?></td>
                    </tr>
                    <?php
                        if($adm_state_id == $state_id)
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
                        <td><?php echo number_format((float)$net_value, 2, '.', ''); ?></td>
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
                                        if($order_status == 1)
                                        {
                                            ?>
                                                <div id="invoice-footer">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-12 text-right">
                                                            <h2 style="margin: 0px 35px;">Rs. <?php echo number_format(round($round_tot), 2) ?> /-</h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                    ?>
                                    <?php
                                        if($order_status == 2)
                                        {
                                            $print_link = BASE_URL.'index.php/distributors/Branchpurchase/print_order/'.$po_id;

                                            ?>
                                                <div id="invoice-footer">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-12 text-right">
                                                            <a target="_blank" href="<?php echo $print_link; ?>" class="btn btn-info btn-lg my-1"><i class="fa fa-print"></i> Print Order</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                        else if($order_status == 3)
                                        {
                                            ?>
                                                <div id="invoice-footer">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-12 text-right">
                                                            <button class="btn btn-info btn-lg my-1 process_click" data-value="distributors" data-cntrl="Branchpurchase" data-func="order_process" data-method="_changePackProcess" data-id="<?php echo $po_id; ?>"><i class="fa fa-spinner"></i> Approved Order</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                        else if($order_status == 4 || $order_status == 5 || $order_status == 10 || $order_status == 11)
                                        {
                                            if(!empty($inv_random))
                                            {
                                                $print_link = BASE_URL.'index.php/distributors/Branchpurchase/print_invoice/'.$inv_random;

                                                $print_json = BASE_URL.'index.php/distributors/Branchpurchase/print_json/'.$inv_random;

                                                ?>
                                                    <div id="invoice-footer">
                                                        <div class="row">
                                                            <div class="col-sm-12 col-12 text-right">
                                                                <a target="_blank" href="<?php echo $print_link; ?>" class="btn btn-info btn-lg my-1"><i class="fa fa-print"></i> Print Invoice</a>

                                                                <a target="_blank" href="<?php echo $print_json; ?>" class="btn btn-info btn-lg my-1"><i class="ft-paperclip"></i> Print JSON</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                            }
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
              //  endif;
            ?>
    </div>
</div>
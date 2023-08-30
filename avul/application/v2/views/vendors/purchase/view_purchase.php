<?php

    // Status Details
    $bill_details     = !empty($purchase_data['bill_details'])?$purchase_data['bill_details']:'';
    $vendor_details   = !empty($purchase_data['vendor_details'])?$purchase_data['vendor_details']:'';
    $purchase_details = !empty($purchase_data['purchase_details'])?$purchase_data['purchase_details']:'';

    // Bill Details
    $purchase_id  = !empty($bill_details['purchase_id'])?$bill_details['purchase_id']:'';
    $purchase_no  = !empty($bill_details['purchase_no'])?$bill_details['purchase_no']:'';
    $vendor_id    = !empty($bill_details['vendor_id'])?$bill_details['vendor_id']:'';
    $order_date   = !empty($bill_details['order_date'])?$bill_details['order_date']:'';
    $order_status = !empty($bill_details['order_status'])?$bill_details['order_status']:'';
    $_ordered     = !empty($bill_details['_ordered'])?$bill_details['_ordered']:'';
    $_processing  = !empty($bill_details['_processing'])?$bill_details['_processing']:'';
    $_shiped      = !empty($bill_details['_shiped'])?$bill_details['_shiped']:'';
    $_canceled    = !empty($bill_details['_canceled'])?$bill_details['_canceled']:'';
    $_delivery    = !empty($bill_details['_delivery'])?$bill_details['_delivery']:'';
    $reason       = !empty($bill_details['reason'])?$bill_details['reason']:'';

    // Vendor Details
    $company_name = !empty($vendor_details['company_name'])?$vendor_details['company_name']:'';
    $gst_no       = !empty($vendor_details['gst_no'])?$vendor_details['gst_no']:'';
    $contact_no   = !empty($vendor_details['contact_no'])?$vendor_details['contact_no']:'';
    $email        = !empty($vendor_details['email'])?$vendor_details['email']:'';
    $address      = !empty($vendor_details['address'])?$vendor_details['address']:'';

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
        $order_view = '<span class="badge badge-info">Complete</span>';
    }
    else if($order_status == '9')
    {
        $order_view = '<span class="badge badge-danger">Cancel Invoice</span>';
    }
    else
    {
        $order_view = '<span class="badge badge-danger">Cancel</span>';
    }

    $suc_active  = $order_status == '1' ? 'active':'';
    $suc_checked = $order_status == '1' ? 'checked':'';

    // Production Status
    $pro_active  = $order_status == '2' ? 'active':'';
    $pro_checked = $order_status == '2' ? 'checked':'';

    // Shipping Status
    $ship_active  = $order_status == '3' ? 'active':'';
    $ship_checked = $order_status == '3' ? 'checked':'';

    // Delivered Status
    $del_active   = $order_status == '4' ? 'active':'';
    $del_checked  = $order_status == '4' ? 'checked':'';

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
                <input type="radio" name="options" id="hatchback" class="progress_option" value="2" <?php echo $pro_checked; ?>> Approved
            </label>

            <label class="price_lable btn btn-danger pro_type_button progress_5 <?php echo $can_active; ?>">
                <input type="radio" name="options" id="hatchback" class="progress_option" value="5" <?php echo $can_checked; ?>> Cancel
            </label>
        <?php
    }
    else if($order_status == 2)
    {
        ?>
            <label class="price_lable btn btn-warning pro_type_button progress_2 <?php echo $pro_active; ?>">
                <input type="radio" name="options" id="hatchback" class="progress_option" value="2" <?php echo $pro_checked; ?>> Approved
            </label>

            <label class="price_lable btn btn-primary pro_type_button progress_4 <?php echo $ship_active; ?>">
                <input type="radio" name="options" id="hatchback" class="progress_option" value="4" <?php echo $ship_checked; ?>> Complete
            </label>

            <!-- <label class="price_lable btn btn-danger pro_type_button progress_5 <?php echo $can_active; ?>">
                <input type="radio" name="options" id="hatchback" class="progress_option" value="5" <?php echo $can_checked; ?>> Cancel
            </label> -->
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
                                <div class="col-md-12">
                                    <div class="form-group text-right">
                                        <input type="hidden" name="order_id" id="order_id" class="order_id" value="<?php echo $purchase_id; ?>">
                                        
                                        <input type="hidden" name="value" id="value" class="value" value="admin">
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
                                            <h2>INVOICE</h2>
                                            <p># <?php echo $purchase_no; ?></p>
                                            <div class="pb-sm-3"><?php echo $order_view; ?></div>
                                        </div>
                                    </div>
                                    <!-- Invoice Company Details -->
                                    <!-- Invoice Customer Details -->
                                    <div id="invoice-customer-details" class="row pt-2">
                                        <div class="col-sm-6 col-12 text-center text-sm-left">
                                            <ul class="ml-2 px-0 list-unstyled">
                                                <li class="text-bold-800"><b>Retail vend</b></li>
                                                <li>No.2, K.K. Nagar,</li>
                                                <li>Bharathi Nagar,</li>
                                                <li>Ganapathy, Coimbatore - 641006</li>
                                                <li>GST: 33AIXPP2926R1Z9</li>
                                                <li>Contact No: 99011 97119</li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-6 col-12 text-center text-sm-right">
                                            <ul class="px-0 list-unstyled">
                                                <li class="text-bold-800"><b><?php echo $company_name; ?></b></li>
                                                <li><?php echo $address; ?></li>
                                                <li>GST: <?php echo $gst_no; ?></li>
                                                <li>Contact No: <?php echo $contact_no; ?></li>
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
                                                <li><?php echo $delivery_date; ?></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div id="invoice-items-details invoice_tbl" class="pt-2">
                                        <div class="row">
                                            <?php
                                                if(!empty($purchase_details))
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
                        foreach ($purchase_details as $key => $value) {
                            $item_id       = !empty($value['item_id'])?$value['item_id']:'';
                            $product_id    = !empty($value['product_id'])?$value['product_id']:'';
                            $product_name  = !empty($value['product_name'])?$value['product_name']:'';
                            $category_name = !empty($value['category_name'])?$value['category_name']:'';
                            $unit_name     = !empty($value['unit_name'])?$value['unit_name']:'';
                            $product_price = !empty($value['product_price'])?$value['product_price']:'0';
                            $product_qty   = !empty($value['product_qty'])?$value['product_qty']:'0';
                            $product_unit  = !empty($value['product_unit'])?$value['product_unit']:'';
                            $net_total     = $product_qty * $product_price;

                            ?>
                                <tr>
                                    <td style="width: 10px;"><?php echo $i; ?></td>
                                    <td><?php echo $product_name; ?></td>
                                    <td>
                                        <input data-val="<?php echo $item_id; ?>" type="text" id="rate" class="form-control rate rate_<?php echo $item_id ?>" placeholder="" name="rate" value="<?php echo $product_price; ?>" style="height: calc(1em + 1.0rem + 0px); padding: 5px;">
                                    </td>
                                    <td>
                                        <input data-val="<?php echo $item_id; ?>" type="text" id="qty" class="form-control qty qty_<?php echo $item_id; ?>" placeholder="" name="qty" value="<?php echo $product_qty; ?>" style="height: calc(1em + 1.0rem + 0px); padding: 5px;">
                                    </td>
                                    <td style="width: 120px;"><span class="amount_<?php echo $item_id; ?>"><?php echo number_format($net_total, 2); ?></span></td>
                                    <td>
                                        <a data-id="<?php echo $item_id; ?>" class="order_update button_clr btn btn-primary" data-value="admin" data-cntrl="purchase" data-func="order_process" style="background-color: #666ee8 !important;"><i class="ft-edit"></i></a>

                                        <a data-id="<?php echo $item_id; ?>" data-progress="<?php echo $order_status; ?>" data-value="admin" data-cntrl="purchase" data-func="order_process" class="delete-order button_clr btn btn-danger" style="background-color: #ff4961 !important;"><i class="ft-trash-2"></i></a>
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
                        $i=1;
                        $net_total = 0;
                        foreach ($purchase_details as $key => $value) {
                            $item_id       = !empty($value['item_id'])?$value['item_id']:'';
                            $product_id    = !empty($value['product_id'])?$value['product_id']:'';
                            $product_name  = !empty($value['product_name'])?$value['product_name']:'';
                            $category_name = !empty($value['category_name'])?$value['category_name']:'';
                            $unit_name     = !empty($value['unit_name'])?$value['unit_name']:'';
                            $product_price = !empty($value['product_price'])?$value['product_price']:'0';
                            $product_qty   = !empty($value['product_qty'])?$value['product_qty']:'0';
                            $received_qty  = !empty($value['received_qty'])?$value['received_qty']:'0';
                            $product_unit  = !empty($value['product_unit'])?$value['product_unit']:'';
                            $net_total     = $product_qty * $product_price;

                            if($product_qty == $received_qty)
                            {
                                $stk_link = '#';
                            }
                            else
                            {
                                $stk_link = BASE_URL.'index.php/admin/purchase/purchase_stock/stock_add/'.$item_id.'/'.$purchase_id;
                            }

                            ?>
                                <tr>
                                    <td style="width: 10px;"><?php echo $i; ?></td>
                                    <td><?php echo $product_name; ?></td>
                                    <td><?php echo $net_total; ?></td>
                                    <td><?php echo $product_qty; ?></td>
                                    <td><?php echo $received_qty; ?></td>
                                    <td><?php echo $unit_name; ?></td>
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
                        $i=1;
                        $net_total = 0;
                        $total_qty = 0;
                        $total_val = 0;
                        foreach ($purchase_details as $key => $value) {
                            $item_id       = !empty($value['item_id'])?$value['item_id']:'';
                            $product_id    = !empty($value['product_id'])?$value['product_id']:'';
                            $product_name  = !empty($value['product_name'])?$value['product_name']:'';
                            $category_name = !empty($value['category_name'])?$value['category_name']:'';
                            $unit_name     = !empty($value['unit_name'])?$value['unit_name']:'';
                            $product_price = !empty($value['product_price'])?$value['product_price']:'0';
                            $product_qty   = !empty($value['product_qty'])?$value['product_qty']:'0';
                            $product_unit  = !empty($value['product_unit'])?$value['product_unit']:'';
                            $net_total     = $product_qty * $product_price;
                            $total_qty    += $product_qty;
                            $total_val    += $net_total;
                            ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $product_name; ?></td>
                                    <td>HSN / SAC</td>
                                    <td><?php echo $product_qty; ?></td>
                                    <td><?php echo $product_price; ?></td>
                                    <td><?php echo $unit_name; ?></td>
                                    <td><?php echo $net_total; ?></td>
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
                        <th colspan="6" class="text-right">Total</th>
                        <td><?php echo $total_val; ?></td>
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
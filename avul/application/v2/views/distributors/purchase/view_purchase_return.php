<?php
    
    $adm_username = !empty($admin_data['username'])?$admin_data['username']:'';
    $adm_mobile   = !empty($admin_data['mobile'])?$admin_data['mobile']:'';
    $adm_address  = !empty($admin_data['address'])?$admin_data['address']:'';
    $adm_state_id = !empty($admin_data['state_id'])?$admin_data['state_id']:'';
    $adm_city_id  = !empty($admin_data['city_id'])?$admin_data['city_id']:'';
    $adm_gst_no   = !empty($admin_data['gst_no'])?$admin_data['gst_no']:'';

    $distributor_details = !empty($return_data['distributor_details'])?$return_data['distributor_details']:'';
    $order_details       = !empty($return_data['order_details'])?$return_data['order_details']:'';

    $usr_order_id     = !empty($distributor_details['order_id'])?$distributor_details['order_id']:'';
    $usr_order_no     = !empty($distributor_details['order_no'])?$distributor_details['order_no']:'';
    $usr_return_no    = !empty($distributor_details['return_no'])?$distributor_details['return_no']:'';
    $usr_order_status = !empty($distributor_details['order_status'])?$distributor_details['order_status']:'';
    $usr_reason       = !empty($distributor_details['reason'])?$distributor_details['reason']:'';
    $usr_ordered      = !empty($distributor_details['ordered'])?$distributor_details['ordered']:'---';
    $usr_complete     = !empty($distributor_details['complete'])?$distributor_details['complete']:'---';
    $usr_canceled     = !empty($distributor_details['canceled'])?$distributor_details['canceled']:'---';
    $usr_distri_name  = !empty($distributor_details['distri_name'])?$distributor_details['distri_name']:'';
    $usr_contact_no   = !empty($distributor_details['contact_no'])?$distributor_details['contact_no']:'';
    $usr_address      = !empty($distributor_details['address'])?$distributor_details['address']:'';
    $usr_gst_no       = !empty($distributor_details['gst_no'])?$distributor_details['gst_no']:'';
    $usr_state_id     = !empty($distributor_details['state_id'])?$distributor_details['state_id']:'';

    // Order Status
    if($usr_order_status == '1')
    {
        $order_view = '<span class="badge badge-success">Success</span>';
    }
    else if($usr_order_status == '5')
    {
        $order_view = '<span class="badge badge-success">Complete</span>';
    }
    else
    {
        $order_view = '<span class="badge badge-danger">Cancel</span>';
    }

    // Suucess Status
    $suc_active  = $usr_order_status == '1' ? 'active':'';
    $suc_checked = $usr_order_status == '1' ? 'checked':'';

    // Complete Status
    $com_active   = $usr_order_status == '5' ? 'active':'';
    $com_checked  = $usr_order_status == '5' ? 'checked':'';

    // Cancel Status
    $can_active   = $usr_order_status == '5' ? 'active':'';
    $can_checked  = $usr_order_status == '5' ? 'checked':'';
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
                if(!empty($return_data))
                {
                    ?>
                        <div class="row">
                            <?php
                                if(!empty($usr_reason))
                                {
                                    if($usr_order_status == 8)
                                    {
                                        ?>
                                            <div class="alert alert-danger" style="width: 100%;">
                                                <span><?php echo $usr_reason; ?></span>
                                            </div>
                                        <?php
                                    }
                                }
                            ?>
                        </div>
                        <div class="row">
                            <?php
                                if($usr_order_status == 1)
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
    <label class="price_lable btn btn-success pro_type_button progress_1 <?php echo $suc_active; ?>">
        <input type="radio" name="options" id="hatchback" class="progress_option" value="1" <?php echo $suc_checked; ?>> Success
    </label>

    <label class="price_lable btn btn-warning pro_type_button progress_5 <?php echo $com_active; ?>">
        <input type="radio" name="options" id="hatchback" class="progress_option" value="5" <?php echo $com_checked; ?>> Complete
    </label>

    <label class="price_lable btn btn-danger pro_type_button progress_8 <?php echo $can_active; ?>">
        <input type="radio" name="options" id="hatchback" class="progress_option" value="8" <?php echo $can_checked; ?>> Cancel
    </label>
                                                                </div>
    <div class="col-md-12 order_message m-t-6 hide">
        <div class="form-group">
            <label for="projectinput1">Status Message <span class="text-danger">*</span></label>
            <textarea name="message" id="message" class="form-control message" placeholder="Enter the Message" rows="3"></textarea>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group text-right">
            <input type="hidden" name="order_id" id="order_id" class="order_id" value="<?php echo $usr_order_id; ?>">
            
            <input type="hidden" name="value" id="value" class="value" value="distributors">
            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="purchase">
            <input type="hidden" name="func" id="func" class="func" value="list_purchase_return">
            <button type="button" class="btn btn-success process_button process_bth" style="background-color: #28d094 !important; color: #fff;"> 
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
                                            <h2>RETURN</h2>
                                            <p># <?php echo $usr_order_no; ?></p>
                                            <div class="pb-sm-3"><?php echo $order_view; ?></div>
                                        </div>
                                    </div>
                                    <!-- Invoice Company Details -->
                                    <!-- Invoice Customer Details -->
                                    <div id="invoice-customer-details" class="row pt-2">
                                        <div class="col-sm-6 col-12 text-center text-sm-left">
                                            <ul class="ml-2 px-0 list-unstyled">
                                                <li class="text-bold-800"><b><?php echo $adm_username ?></b></li>
                                                <li><?php echo $adm_address; ?></li>
                                                <li><b>GSTIN:</b> <?php echo $adm_gst_no; ?></li>
                                                <li><b>Contact No:</b> <?php echo $adm_mobile; ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-6 col-12 text-center text-sm-right">
                                            <ul class="px-0 list-unstyled">
                                                <li class="text-bold-800"><b><?php echo $usr_distri_name; ?></b></li>
                                                <li><?php echo $usr_address; ?></li>
                                                <li><b>GSTIN:</b> <?php echo $usr_gst_no; ?></li>
                                                <li><b>Contact No:</b> <?php echo $usr_contact_no; ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Invoice Customer Details -->
                                    <div id="invoice-customer-details" class="row pt-2">
                                        <div class="col-sm-4 col-12 text-center">
                                            <ul class="px-0 list-unstyled">
                                                <li><b>Bill Date</b></li>
                                                <li><?php echo $usr_ordered; ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-4 col-12 text-center">
                                            <ul class="px-0 list-unstyled">
                                                <li><b>Complete Date</b></li>
                                                <li><?php echo $usr_complete; ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-4 col-12 text-center">
                                            <ul class="px-0 list-unstyled">
                                                <li><b>Cancel Date</b></li>
                                                <li><?php echo $usr_canceled; ?></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div id="invoice-items-details invoice_tbl" class="pt-2">
                                        <div class="row">
                                            <div class="table-responsive col-12">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 3%;">#</th>
                                                            <th style="width: 45%;">Description</th>
                                                            <th style="width: 10%;">HSN / SAC</th>
                                                            <th style="width: 10%;">Qty</th>
                                                            <th style="width: 10%;">Rate</th>
                                                            <th style="width: 10%;">Per</th>
                                                            <th style="width: 10%;">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $num     = 1;
                                                            $sub_tot = 0;
                                                            $tot_gst = 0;
                                                            $net_tot = 0;
                                                            foreach ($order_details as $key => $val)
                                                            {
            $description = !empty($val['description'])?$val['description']:'';
            $gst_value   = !empty($val['gst_value'])?$val['gst_value']:'0';
            $hsn_code    = !empty($val['hsn_code'])?$val['hsn_code']:'';
            $pdt_price   = !empty($val['product_price'])?$val['product_price']:'0';
            $pdt_qty     = !empty($val['product_qty'])?$val['product_qty']:'0';

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
                    <td><?php echo $pdt_qty; ?></td>
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
                        $net_value  = round($net_tot);
                        $rond_total = $net_value - $net_tot;
                    ?>
                    <tr>
                        <th colspan="6" class="text-right">Sub Total</th>
                        <td><?php echo number_format((float)$sub_tot, 2, '.', ''); ?></td>
                    </tr>
                    <?php
                        if($adm_state_id == $usr_state_id)
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
                                        </div>
                                    </div>
                                    <?php
                                        $print_link = BASE_URL.'index.php/distributors/purchase/print_return/'.$usr_order_id;

                                        ?>
                                            <div id="invoice-footer">
                                                <div class="row">
                                                    <div class="col-sm-12 col-12 text-right">
                                                        <a target="_blank" href="<?php echo $print_link; ?>" class="btn btn-info btn-lg my-1"><i class="fa fa-print"></i> Print Order</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
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
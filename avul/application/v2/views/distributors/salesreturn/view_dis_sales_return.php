<?php
		
	$distributor_det = !empty($return_data['distributor_details'])?$return_data['distributor_details']:'';
	$admin_details   = !empty($return_data['admin_details'])?$return_data['admin_details']:'';
	$product_det     = !empty($return_data['product_details'])?$return_data['product_details']:'';
	$tax_det         = !empty($return_data['tax_details'])?$return_data['tax_details']:'';

	// Distributor Details
	$return_no    = !empty($distributor_det['return_no'])?$distributor_det['return_no']:'';
	$return_value = !empty($distributor_det['return_value'])?$distributor_det['return_value']:'';
	$company_name = !empty($distributor_det['company_name'])?$distributor_det['company_name']:'';
    $dis_mobile   = !empty($distributor_det['dis_mobile'])?$distributor_det['dis_mobile']:'';
    $dis_email    = !empty($distributor_det['dis_email'])?$distributor_det['dis_email']:'';
    $dis_state_id = !empty($distributor_det['dis_state_id'])?$distributor_det['dis_state_id']:'';
    $dis_gst_no   = !empty($distributor_det['dis_gst_no'])?$distributor_det['dis_gst_no']:'';
    $dis_address  = !empty($distributor_det['dis_address'])?$distributor_det['dis_address']:'';

    $adm_username = !empty($admin_details['adm_username'])?$admin_details['adm_username']:'';
    $adm_mobile   = !empty($admin_details['adm_mobile'])?$admin_details['adm_mobile']:'';
    $adm_address  = !empty($admin_details['adm_address'])?$admin_details['adm_address']:'';
    $adm_gst_no   = !empty($admin_details['adm_gst_no'])?$admin_details['adm_gst_no']:'';
    $adm_state_id = !empty($admin_details['adm_state_id'])?$admin_details['adm_state_id']:'';

    $print_link = BASE_URL.'index.php/distributors/salesreturn/list_distributors_sales_return/print_invoice/'.$return_value;
?>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
        	
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
                                <p># <?php echo $return_no; ?></p>
                            </div>
                        </div>

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
                                    <li><?php echo $dis_address; ?></li>
                                    <li><b>GSTIN:</b> <?php echo $dis_gst_no; ?></li>
                                    <li><b>Contact No:</b> <?php echo $dis_mobile; ?></li>
                                </ul>
                            </div>
                        </div>

                        <div id="invoice-items-details invoice_tbl" class="pt-2">
                            <div class="row">
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
						                        foreach ($product_det as $key => $val_1) {
									            	$description = !empty($val_1['description'])?$val_1['description']:'';
									                $hsn_code    = !empty($val_1['hsn_code'])?$val_1['hsn_code']:'';
									                $gst_value   = !empty($val_1['gst_val'])?$val_1['gst_val']:'';
									                $pdt_price   = !empty($val_1['price'])?$val_1['price']:'';
									                $pdt_qty     = !empty($val_1['return_qty'])?$val_1['return_qty']:'';

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
						                        $last_value = round($net_tot);
						                        $rond_total = $last_value - $net_tot;
						                	?>
						                	<tr>
						                        <th colspan="6" class="text-right">Sub Total</th>
						                        <td><?php echo number_format((float)$sub_tot, 2, '.', ''); ?></td>
						                    </tr>
						                    <?php
						                    	if($dis_state_id == $adm_state_id)
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
						                        <td><?php echo number_format((float)$last_value, 2, '.', ''); ?></td>
						                    </tr>
						                </tfoot>
						            </table>
						        </div>
                            </div>
                        </div>
                        <div id="invoice-footer">
                            <div class="row">
                                <div class="col-sm-12 col-12 text-right">
                                    <a target="_blank" href="<?php echo $print_link; ?>" class="btn btn-info btn-lg my-1"><i class="fa fa-print"></i> Print Order</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            
            	<div id="error" class="show alert alert-danger text-center">
                    <b class="error_msg">No items found...</b>
                </div>
          
        </div>
    </div>
</div>

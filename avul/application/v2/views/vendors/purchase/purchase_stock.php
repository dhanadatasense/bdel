<?php
    // Bill Details
    $purchase_id = !empty($purchase_data['bill_details']['purchase_id'])?$purchase_data['bill_details']['purchase_id']:'';
    $purchase_no = !empty($purchase_data['bill_details']['purchase_no'])?$purchase_data['bill_details']['purchase_no']:'';
    $vendor_id   = !empty($purchase_data['bill_details']['vendor_id'])?$purchase_data['bill_details']['vendor_id']:'';
    $order_date  = !empty($purchase_data['bill_details']['order_date'])?$purchase_data['bill_details']['order_date']:'';

    // Vendor Details
    $company_name = !empty($purchase_data['vendor_details']['company_name'])?$purchase_data['vendor_details']['company_name']:'';
    $gst_no       = !empty($purchase_data['vendor_details']['gst_no'])?$purchase_data['vendor_details']['gst_no']:'';
    $contact_no   = !empty($purchase_data['vendor_details']['contact_no'])?$purchase_data['vendor_details']['contact_no']:'';
    $email        = !empty($purchase_data['vendor_details']['email'])?$purchase_data['vendor_details']['email']:'';
    $address      = !empty($purchase_data['vendor_details']['address'])?$purchase_data['vendor_details']['address']:'';
?>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title"><?php echo $page_title; ?></h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><?php echo $main_heading; ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?php echo $page_title; ?>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-6 col-12">
                <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                    <a class="btn btn-info round px-2 mb-1" href="<?php echo BASE_URL.$pre_menu ?>"><i class="ft-plus-square"></i> <?php echo $pre_title; ?></a>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section class="card">
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
                            <p class="pb-sm-3"># <?php echo $purchase_no; ?></p>
                        </div>
                    </div>
                    <!-- Invoice Company Details -->
                    <!-- Invoice Items Details -->
                    <div id="invoice-items-details" class="pt-2">
                        <?php
                            if($purchase_data['purchase_details'])
                            {
                                ?>
                                    <div class="row">
                                        <div class="table-responsive col-12">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Item</th>
                                                        <th>Category</th>
                                                        <th>Order Qty</th>
                                                        <th>Received Qty</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $i=1;
                                                        $total = 0;
                                                        foreach ($purchase_data['purchase_details'] as $key => $value) {
                                                            
    $item_id       = !empty($value['item_id'])?$value['item_id']:'';
    $product_id    = !empty($value['product_id'])?$value['product_id']:'';
    $product_name  = !empty($value['product_name'])?$value['product_name']:'';
    $category_name = !empty($value['category_name'])?$value['category_name']:'';
    $unit_name     = !empty($value['unit_name'])?$value['unit_name']:'';
    $product_price = !empty($value['product_price'])?$value['product_price']:'0';
    $product_qty   = !empty($value['product_qty'])?$value['product_qty']:'0';
    $product_unit  = !empty($value['product_unit'])?$value['product_unit']:'0';
    $received_qty  = !empty($value['received_qty'])?$value['received_qty']:'0';
    $net_total     = $product_qty * $product_price;
    $total        += $net_total;

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
        <td class="row"><?php echo $i; ?></td>
        <td><p><?php echo $product_name; ?></p></td>
        <td><p><?php echo $category_name; ?></p></td>
        <td><?php echo $product_qty; ?> ( <?php echo $unit_name; ?> )</td>
        <td><?php echo $received_qty; ?> ( <?php echo $unit_name; ?> )</td>
        <td><a href="<?php echo $stk_link; ?>" class="button_clr btn btn-warning"><i class="ft-plus-square"></i> Add </a></td>
    </tr>
                                                            <?php

                                                            $i++;
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php
                            }
                            else
                            {

                            }
                        ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<?php
    
    $production_details = !empty($production_data['production_details'])?$production_data['production_details']:'';
    $production_list    = !empty($production_data['production_list'])?$production_data['production_list']:'';

    $production_no = !empty($production_details['production_no'])?$production_details['production_no']:'';
    $start_date    = !empty($production_details['start_date'])?$production_details['start_date']:'';
    $end_date      = !empty($production_details['end_date'])?$production_details['end_date']:'';
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
                            <h2>WORK ORDER</h2>
                            <p class="pb-sm-3"># <?php echo $production_no; ?></p>
                        </div>
                    </div>
                    <!-- Invoice Company Details -->
                    <!-- Invoice Items Details -->
                    <div id="invoice-items-details" class="pt-2">
                        <?php
                            if($production_list)
                            {
                                ?>
                                    <div class="row">
                                        <div class="table-responsive col-12">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Description</th>
                                                        <th>Order Qty</th>
                                                        <th>Received Qty</th>
                                                        <!-- <th>Unit</th> -->
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $i=1;
                                                        foreach ($production_list as $key => $value) {
    $auto_id       = !empty($value['auto_id'])?$value['auto_id']:'';
    $production_id = !empty($value['production_id'])?$value['production_id']:'';
    $product_id    = !empty($value['product_id'])?$value['product_id']:'';
    $product_name  = !empty($value['product_name'])?$value['product_name']:'';
    $type_id       = !empty($value['type_id'])?$value['type_id']:'';
    $description   = !empty($value['description'])?$value['description']:'';
    $unit_val      = !empty($value['unit_val'])?$value['unit_val']:'';
    $unit_name     = !empty($value['unit_name'])?$value['unit_name']:'';
    $order_qty     = !empty($value['order_qty'])?$value['order_qty']:'0';
    $receive_qty   = !empty($value['receive_qty'])?$value['receive_qty']:'0';

    if($order_qty == $receive_qty)
    {
        $stk_link = '#';
    }
    else
    {
        $stk_link = BASE_URL.'index.php/vendors/production/production_stock/stock_add/'.$auto_id.'/'.$production_id;
    }

    ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $description; ?></td>
            <td><?php echo $order_qty; ?></td>
            <td><?php echo $receive_qty; ?></td>
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
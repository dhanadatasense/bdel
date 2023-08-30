<?php
    $stock_data    = !empty($stock_value['stock_data'])?$stock_value['stock_data']:'';
    $stock_details = !empty($stock_value['stock_details'])?$stock_value['stock_details']:'';

    $stock_no   = !empty($stock_data['stock_no'])?$stock_data['stock_no']:'';
    $order_date = !empty($stock_data['order_date'])?$stock_data['order_date']:'';
?>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <?php
                if(!empty($stock_details))
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
                                                <img class="brand-logo" alt="modern admin logo" src="<?php echo BASE_URL; ?>app-assets/images/logob.jpg">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12 text-center text-sm-right">
                                            <h2>STOCK NO</h2>
                                            <p># <?php echo $stock_no; ?></p>
                                        </div>
                                    </div>
                                    <!-- Invoice Company Details -->

                                    <div id="invoice-items-details invoice_tbl" class="pt-2">
                                        <div class="row">
                                            <?php
                                                if(!empty($stock_details))
                                                {
                                                    ?>
                                                        <div class="table-responsive col-12">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 3%;">#</th>
                                                                    <th style="width: 45%;">Description</th>
                                                                    <th style="width: 10%;">Stock Value</th>
                                                                    <th style="width: 10%;">Damage Value</th>
                                                                    <th style="width: 10%;">Expiry Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    $num     = 1;
                        foreach ($stock_details as $key => $val) {
                            $description = !empty($val['description'])?$val['description']:'';
                            $stock_val   = !empty($val['stock_val'])?$val['stock_val']:'0';
                            $damage_val  = !empty($val['damage_val'])?$val['damage_val']:'0';
                            $expiry_val  = !empty($val['expiry_val'])?$val['expiry_val']:'0';

                            ?>
                                <tr>
                                    <td><?php echo $num; ?></td>
                                    <td><?php echo $description; ?></td>
                                    <td><?php echo $stock_val; ?></td>
                                    <td><?php echo $damage_val; ?></td>
                                    <td><?php echo $expiry_val; ?></td>
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
</div>
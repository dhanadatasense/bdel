<?php
    
    $prod_data = $production_data;

    $auto_id       = !empty($prod_data['auto_id'])?$prod_data['auto_id']:'';
    $production_id = !empty($prod_data['production_id'])?$prod_data['production_id']:'';
    $production_no = !empty($prod_data['production_no'])?$prod_data['production_no']:'';
    $product_id    = !empty($prod_data['product_id'])?$prod_data['product_id']:'';
    $product_name  = !empty($prod_data['product_name'])?$prod_data['product_name']:'';
    $type_id       = !empty($prod_data['type_id'])?$prod_data['type_id']:'';
    $description   = !empty($prod_data['description'])?$prod_data['description']:'';
    $unit_val      = !empty($prod_data['unit_val'])?$prod_data['unit_val']:'';
    $order_qty     = !empty($prod_data['order_qty'])?$prod_data['order_qty']:'';

?>
<style type="text/css">
    .form-actions
    {
        border-top: none !important;
        padding: 0px !important;
        margin-top: 0px !important;
    }
</style>
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
            <section id="basic-form-layouts">
                <div class="row match-height">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"># <?php echo $production_no; ?></h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <form class="data_form" name="data_form" method="post">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Product Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="contact_no" class="form-control contact_no" placeholder="Product Name" name="contact_no" value="<?php echo $description; ?>" readonly="readonly" style="background-color: #fff;">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Received Date <span class="text-danger">*</span></label>
                                                        <input type="text" id="received_date" class="form-control received_date dates" placeholder="Received Date" name="received_date" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Received Qty <span class="text-danger">*</span></label>
                                                        <input type="text" id="received_qty" class="form-control received_qty" placeholder="Received Qty" name="received_qty" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <input type="hidden" name="wo_id" id="wo_id" class="wo_id" value="<?php echo $production_id; ?>">
                                            <input type="hidden" name="wo_auto_id" id="wo_auto_id" class="wo_auto_id" value="<?php echo $auto_id; ?>">
                                            <input type="hidden" name="product_id" id="product_id" class="product_id" value="<?php echo $product_id; ?>">
                                            <input type="hidden" name="type_id" id="type_id" class="type_id" value="<?php echo $type_id; ?>">
                                            <input type="hidden" name="product_unit" id="product_unit" class="product_unit" value="<?php echo $unit_val; ?>">

                                            <input type="hidden" name="value" id="value" class="value" value="vendors">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="production">
                                            <input type="hidden" name="func" id="func" class="func" value="production_stock">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" class="method" value="BTBM_X_C">

                                            <button type="submit" class="btn btn-primary data_submit" data-type="_s_c">
                                                <span class="first_btn show"><i class="la la-check-square-o"></i> <?php echo $page_title; ?></span>

                                                <span class="span_btn hide"><i class="la la-spinner spinner"></i> Loading....</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <?php
                                        if(!empty($stock_data))
                                        {
                                            ?>
                                                <div class="row">
                                                    <div class="table-responsive col-12">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Date</th>
                                                                    <th>Received Qty</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    $i=1;
                                                                    foreach ($stock_data as $key => $value) {
                                $stock_id      = !empty($value['stock_id'])?$value['stock_id']:'';
                                $product_unit  = !empty($value['product_unit'])?$value['product_unit']:'';
                                $unit_name     = !empty($value['unit_name'])?$value['unit_name']:'';
                                $received_qty  = !empty($value['received_qty'])?$value['received_qty']:'';
                                $received_date = !empty($value['received_date'])?$value['received_date']:'';

                                ?>
                                    <tr class="row_<?php echo $i; ?>">
                                        <td><?php echo $i ?></td>
                                        <td><?php echo date('d-M-Y', strtotime($received_date)); ?></td>
                                        <td><?php echo $received_qty; ?> nos</td>
                                        <td><a data-row="<?php echo $i; ?>" data-id="<?php echo $stock_id; ?>" data-value="vendors" data-cntrl="production" data-func="production_stock" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a></td>
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
                                            ?>
                                                <div class="row">
                                                    <div class="col-sm-12 filter-design">
                                                        <div id="stock_error" class="show alert alert-danger text-center">
                                                            <b>No items found...</b>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
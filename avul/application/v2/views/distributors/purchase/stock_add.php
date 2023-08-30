<?php
    
    // Purchase Detals
    $purchase_no  = !empty($purchase_data[0]['po_no'])?$purchase_data[0]['po_no']:'';

    // Product Details
    $item_id      = !empty($product_data['item_id'])?$product_data['item_id']:'';
    $po_id        = !empty($product_data['po_id'])?$product_data['po_id']:'';
    $product_id   = !empty($product_data['product_id'])?$product_data['product_id']:'';
    $product_name = !empty($product_data['product_name'])?$product_data['product_name']:'';
    $product_unit = !empty($product_data['product_unit'])?$product_data['product_unit']:'';
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
                                <h4 class="card-title"># <?php echo $purchase_no; ?></h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <form class="data_form" name="data_form" method="post">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Product Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="contact_no" class="form-control contact_no" placeholder="Product Name" name="contact_no" value="<?php echo $product_name; ?>" readonly="readonly" style="background-color: #fff;">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Bill No <span class="text-danger">*</span></label>
                                                        <input type="text" id="bill_no" class="form-control bill_no" placeholder="Bill No" name="bill_no" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Received Date <span class="text-danger">*</span></label>
                                                        <input type="text" id="received_date" class="form-control received_date dates" placeholder="Received Date" name="received_date" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Received Qty <span class="text-danger">*</span></label>
                                                        <input type="text" id="received_qty" class="form-control received_qty" placeholder="Received Qty" name="received_qty" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <input type="hidden" name="po_id" id="po_id" class="po_id" value="<?php echo $po_id; ?>">
                                            <input type="hidden" name="po_auto_id" id="po_auto_id" class="po_auto_id" value="<?php echo $item_id; ?>">
                                            <input type="hidden" name="product_id" id="product_id" class="product_id" value="<?php echo $product_id; ?>">
                                            <input type="hidden" name="product_unit" id="product_unit" class="product_unit" value="<?php echo $product_unit; ?>">

                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="purchase">
                                            <input type="hidden" name="func" id="func" class="func" value="purchase_stock">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" class="method" value="">

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
                                                                    <th>Bill No</th>
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
                                $bill_no       = !empty($value['bill_no'])?$value['bill_no']:'';
                                $received_qty  = !empty($value['received_qty'])?$value['received_qty']:'';
                                $received_date = !empty($value['received_date'])?$value['received_date']:'';

                                ?>
                                    <tr>
                                        <td><?php echo $i ?></td>
                                        <td><?php echo $bill_no; ?></td>
                                        <td><?php echo $received_date; ?></td>
                                        <td><?php echo $received_qty.' '.$unit_name; ?></td>
                                        <td><a data-id="<?php echo $stock_id; ?>" data-value="admin" data-cntrl="purchase" data-func="purchase_stock" class="delete-btn button_clr btn btn-danger"><i class="ft-trash-2"></i> Delete </a></td>
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
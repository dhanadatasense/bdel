<?php

    $bill_det     = !empty($pur_data['bill_details'])?$pur_data['bill_details']:'';
    $vendor_det   = !empty($pur_data['vendor_details'])?$pur_data['vendor_details']:'';
    $purchase_det = !empty($pur_data['purchase_details'])?$pur_data['purchase_details']:'';

    $purchase_id  = !empty($bill_det['purchase_id'])?$bill_det['purchase_id']:'';
    $purchase_no  = !empty($bill_det['purchase_no'])?$bill_det['purchase_no']:'';
    $vendor_id    = !empty($bill_det['vendor_id'])?$bill_det['vendor_id']:'';
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
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Manufacturer Invoice No <span class="text-danger">*</span></label>
                                                        <input type="text" id="bill_no" class="form-control bill_no" placeholder="Invoice No" name="bill_no" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Manufacturer Invoice Date <span class="text-danger">*</span></label>
                                                        <input type="text" id="received_date" class="form-control received_date dates" placeholder="Invoice Date" name="received_date" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Manufacturer Invoice Copy </label>

                                                        <fieldset class="form-group mb-10">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="inputGroupFile02" name="image[]">
                                                                <label class="custom-file-label" for="inputGroupFile02" aria-describedby="inputGroupFile02">Choose file</label>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="table-responsive col-12">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <!-- <th>#</th> -->
                                                                <th>Product Name</th>
                                                                <th style="width: 15%;">Quantity</th>
                                                                <th>Unit</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $i   = 1;
                                                                $num = 0;
                                                                foreach ($purchase_det as $key => $val)
                                                                {

                                                                    $item_id      = !empty($val['item_id'])?$val['item_id']:'';
                                                                    $product_id   = !empty($val['product_id'])?$val['product_id']:'';
                                                                    $type_id      = !empty($val['type_id'])?$val['type_id']:'';
                                                                    $type_name    = !empty($val['type_name'])?$val['type_name']:'';
                                                                    $product_unit = !empty($val['product_unit'])?$val['product_unit']:'';
                                                                    $product_qty  = !empty($val['product_qty'])?$val['product_qty']:'0';
                                                                    $received_qty = !empty($val['received_qty'])?$val['received_qty']:'0';
                                                                    $balance_qty  = $product_qty - $received_qty;

                                                                    if($product_qty != $received_qty)
                                                                    {
                                                                        ?>
                                                                            <tr class="row_<?php echo $i; ?>">
                                                                                <!-- <td><?php echo $i; ?></td> -->
                                                                                <td><?php echo $type_name; ?></td>
                                                                                <td>
                                                                                    <input type="text" id="received_qty" class="form-control received_qty received_<?php echo $i; ?>" placeholder="<?php echo $balance_qty; ?>" name="received_qty[]" data-id="<?php echo $i; ?>" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                                                    <input type="hidden" name="item_id[]" id="item_id" class="item_id" value="<?php echo $item_id; ?>">
                                                                                    <input type="hidden" name="product_id[]" id="product_id" class="product_id" value="<?php echo $product_id; ?>">
                                                                                    <input type="hidden" name="type_id[]" id="type_id" class="type_id" value="<?php echo $type_id; ?>">
                                                                                    <input type="hidden" name="product_unit[]" id="product_unit" class="product_unit" value="<?php echo $product_unit; ?>">
                                                                                    <input type="hidden" name="product_qty[]" id="product_qty" class="product_qty" value="<?php echo $product_qty; ?>">
                                                                                    <input type="hidden" name="balance_qty[]" id="balance_qty" class="balance_qty balance_<?php echo $i; ?>" value="<?php echo $balance_qty; ?>">
                                                                                </td>
                                                                                <td style="padding-top: 20px;">nos</td>
                                                                                <td><button type="button" name="remove" class="btn btn-danger btn-sm remove_item button_size m-t-6"><span class="ft-minus-square"></span></button></td>
                                                                            </tr>
                                                                        <?php

                                                                        $num++;
                                                                    }

                                                                    $i++;
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                    <?php
                                                        if($num == 0)
                                                        {
                                                            ?>
                                                                <div class="row">
                                                                    <div class="col-sm-12 filter-design">
                                                                        <div id="" class="alert alert-danger text-center">
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
                                        <?php
                                            if($num != 0)
                                            {
                                                ?>
                                                    <div class="form-actions">
                                                        <input type="hidden" name="value" id="value" class="value" value="admin">
                                                        <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="purchase">
                                                        <input type="hidden" name="func" id="func" class="func" value="purchase_entry">
                                                        <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                                        <input type="hidden" name="method" class="method" value="">

                                                        <input type="hidden" name="purchase_id" id="purchase_id" class="purchase_id" value="<?php echo $purchase_id; ?>">
                                                        <input type="hidden" name="vendor_id" id="vendor_id" class="vendor_id" value="<?php echo $vendor_id; ?>">

                                                        <button type="submit" class="btn btn-primary data_submit" data-type="_s_c">
                                                            <span class="first_btn show"><i class="la la-check-square-o"></i> <?php echo $page_title; ?></span>
                                                            <span class="span_btn hide"><i class="la la-spinner spinner"></i> Loading....</span>
                                                        </button>
                                                    </div>
                                                <?php
                                            }
                                        ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<?php

    $bill_details     = !empty($dataval['bill_details'])?$dataval['bill_details']:'';
    $vendor_details   = !empty($dataval['vendor_details'])?$dataval['vendor_details']:'';
    $purchase_details = !empty($dataval['purchase_details'])?$dataval['purchase_details']:'';

    // Bill Details
    $purchase_id = !empty($bill_details['purchase_id'])?$bill_details['purchase_id']:'';
    $purchase_no = !empty($bill_details['purchase_no'])?$bill_details['purchase_no']:'';
    $vendor_res  = !empty($bill_details['vendor_id'])?$bill_details['vendor_id']:'';
    $order_date  = !empty($bill_details['order_date'])?$bill_details['order_date']:'';

    // Date Value
    $date_value  = '';
    if($order_date != '')
    {
        $date_value = date('d-m-Y', strtotime($order_date));
    }

    // Vendor Details
    $company_name = !empty($vendor_details['company_name'])?$vendor_details['company_name']:'';
    $gst_no       = !empty($vendor_details['gst_no'])?$vendor_details['gst_no']:'';
    $contact_no   = !empty($vendor_details['contact_no'])?$vendor_details['contact_no']:'';
    $email        = !empty($vendor_details['email'])?$vendor_details['email']:'';
    $address      = !empty($vendor_details['address'])?$vendor_details['address']:'';

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
                            <!-- <li class="breadcrumb-item"><a href="#"><?php //echo $sub_heading; ?></a>
                            </li> -->
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
                                <h4 class="card-title"><?php echo $page_title; ?></h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <form class="data_form" name="data_form" method="post">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="item_table" class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Product</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Price</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Quantity</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Unit</label></th>
                                                                    <th class="p-l-0">
                                                                        <button type="button" name="remove" class="btn btn-success btn-sm add_dis_purchase button_size m-t-6"><span class="ft-plus-square"></span></button>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody  class="additemform">
                <tr class="row_1">
                    <td data-te="1" class="p-l-0 dis_product_list" style="width: 40%;">
                        <select data-te="1" name="dis_product_id[]" id="dis_product_id1" class="form-control dis_product_id1 product_id js-select1-multi" data-te="1" style="width: 100%;">
                            <option value="">Product Name</option>
                            <?php
                                if(!empty($product_val))
                                {
                                    foreach ($product_val as $key => $value) {

                                        $assproduct_id = !empty($value['assproduct_id'])?$value['assproduct_id']:'';
                                        $description   = !empty($value['description'])?$value['description']:'';

                                        echo "<option value=".$assproduct_id.">".$description."</option>";

                                    }
                                }
                            ?>
                        </select> 
                    </td>
                    <td class="p-l-0">
                        <input type="text" data-te="1" name="dis_product_price[]" id="dis_product_price1" class="form-control bg-white dis_product_price1 dis_product_price int_value" placeholder="Price" readonly="readonly">
                    </td>
                    <td class="p-l-0">
                        <input type="text" data-te="1" name="dis_product_qty[]" id="dis_product_qty1" class="form-control dis_product_qty1 dis_product_qty int_value" oninput="this.value=this.value.replace(/^0+/g,'')" maxlength="5" placeholder="Quantity">

                        <input type="hidden" data-te="1" name="dis_purchase_id[]" id="dis_purchase_id1" class="form-control dis_purchase_id1 dis_purchase_id" placeholder="Enter the Price">

                        <input type="hidden" data-te="1" name="dis_price_val[]" id="dis_price_val1" class="form-control dis_price_val1 dis_price_val" placeholder="Enter the Price" value="">
                    </td>
                    <td class="p-l-0" style="width: 30%;">
                        <select data-te="1" name="dis_unit_id[]" id="dis_unit_id1" class="form-control dis_unit_id1 dis_unit_id js-select1-multi" data-te="1" style="width: 100%;">
                            <option value="">Unit Name</option>
                            <?php
                                if(!empty($unit_val))
                                {
                                    foreach ($unit_val as $key => $value) {

                                        $unit_id   = !empty($value['unit_id'])?$value['unit_id']:'';
                                        $unit_name = !empty($value['unit_name'])?$value['unit_name']:'';

                                        echo "<option value=".$unit_id.">".$unit_name."</option>";
                                    }
                                }
                            ?>
                        </select> 
                    </td>
                    <td class="buttonlist p-l-0">
                        <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6"><span class="ft-minus-square"></span></button>
                    </td>
                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="distributors">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="delivery">
                                            <input type="hidden" name="func" id="func" class="func" value="add_delivery_challan">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" class="method" value="<?php echo $method;?>">
                                            <input type="hidden" name="row_count" id="row_count" value="">
                                            <button type="submit" class="btn btn-primary data_submit" data-type="_s_c">
                                                <span class="first_btn show"><i class="la la-check-square-o"></i> <?php echo $page_title; ?></span>

                                                <span class="span_btn hide"><i class="la la-spinner spinner"></i> Loading....</span>
                                            </button>
                                        </div>
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
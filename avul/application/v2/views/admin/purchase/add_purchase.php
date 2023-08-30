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
<!-- <style type="text/css">
    .product_list .select2-container--default
    {
        width: 100% !important;
    }
</style> -->
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
                    <?php if($page_access): ?>
                    <a class="btn btn-info round px-2 mb-1" href="<?php echo BASE_URL.$pre_menu ?>"><i class="ft-plus-square"></i> <?php echo $pre_title; ?></a>
                    <?php endif; ?>
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
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Manufacture Name <span class="text-danger">*</span></label>
                                                        <select class="form-control vendor_id js-select1-multi" id="vendor_id" name="vendor_id" style="width: 100%;">
                                                            <option value="">Select Vendor Name</option>
                                                            <?php
                                                                if(!empty($vendor_val))
                                                                {
                                                                    foreach ($vendor_val as $key => $value) {
                                                                    $vendor_id    = !empty($value['vendor_id'])?$value['vendor_id']:'';   
                                                                    $company_name = !empty($value['company_name'])?$value['company_name']:''; 

                                                                    if($vendor_res == $vendor_id)
                                                                    {
                                                                        $selected = 'selected';
                                                                    }
                                                                    else
                                                                    {
                                                                        $selected = '';
                                                                    }

                                                                    echo "<option value=".$vendor_id." ".$selected.">".$company_name."</option>";

                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Contact Number <span class="text-danger">*</span></label>
                                                        <input type="text" id="contact_no" class="form-control contact_no int_value" placeholder="Contact Number" name="contact_no" value="<?php echo $contact_no; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">GST No <span class="text-danger">*</span></label>
                                                        <input type="text" id="gst_no" class="form-control gst_no" placeholder="GST No" name="gst_no" value="<?php echo $gst_no; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Order Date <span class="text-danger">*</span></label>
                                                        <input type="text" id="order_date" class="form-control order_date dates" placeholder="Order Date" name="order_date" value="<?php echo $date_value; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Address <span class="text-danger">*</span></label>
                                                        <textarea id="address" class="form-control address" placeholder="Address" name="address" rows="3"><?php echo $address; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="item_table" class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Product Name</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Product Type</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Price</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Quantity</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Unit</label></th>
                                                                    <th class="p-l-0">
                                                                        <button type="button" name="remove" class="btn btn-success btn-sm add_purchase button_size m-t-6"><span class="ft-plus-square"></span></button>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody  class="additemform">
                                                                <?php
                                                                    if($method == 'BTBM_X_U')
                                                                    {
                                                                        $i = 1;
                                                                        foreach ($purchase_details as $key => $pur_value) {
                                                                                
                $item_id       = !empty($pur_value['item_id'])?$pur_value['item_id']:'';
                $product_value = !empty($pur_value['product_id'])?$pur_value['product_id']:'';
                $product_price = !empty($pur_value['product_price'])?$pur_value['product_price']:'';
                $product_qty   = !empty($pur_value['product_qty'])?$pur_value['product_qty']:'';
                $unit_value    = !empty($pur_value['product_unit'])?$pur_value['product_unit']:'';

                                                                            ?>
                <tr class="row_<?php echo $item_id; ?>">
                    <td data-te="1" class="p-l-0 product_list" style="width: 40%;">
                        <select data-te="<?php echo $item_id; ?>" name="product_id[]" id="product_id<?php echo $item_id; ?>" class="form-control product_id<?php echo $item_id; ?> product_id js-select1-multi" data-te="<?php echo $item_id; ?>" style="width: 100%;">
                            <option value="">Select Product Name</option>
                            <?php
                                if(!empty($product_val))
                                {
                                    foreach ($product_val as $key => $value) {

                                        $product_id   = !empty($value['product_id'])?$value['product_id']:'';
                                        $product_name = !empty($value['product_name'])?$value['product_name']:'';

                                        if($product_value == $product_id)
                                        {
                                            $selected = 'selected';
                                        }
                                        else
                                        {
                                            $selected = '';
                                        }

                                        echo "<option value=".$product_id." ".$selected.">".$product_name."</option>";
                                    }
                                }
                            ?>
                        </select> 
                    </td>
                    <td class="p-l-0">
                        <input type="text" data-te="<?php echo $item_id; ?>" name="product_price[]" id="product_price<?php echo $item_id; ?>" class="form-control product_price<?php echo $item_id; ?> product_price int_value" placeholder="Price" value="<?php echo $product_price; ?>">
                    </td>
                    <td class="p-l-0">
                        <input type="text" data-te="<?php echo $item_id; ?>" name="product_qty[]" id="product_qty<?php echo $item_id; ?>" class="form-control product_qty<?php echo $item_id; ?> product_qty int_value" placeholder="Quantity" value="<?php echo $product_qty; ?>">

                        <input type="hidden" data-te="<?php echo $item_id; ?>" name="purchase_id[]" id="purchase_id<?php echo $item_id; ?>" class="form-control purchase_id<?php echo $item_id; ?> purchase_id" placeholder="Enter the Price" value="<?php echo $item_id; ?>">
                    </td>
                    <td class="p-l-0" style="width: 30%;">
                        <select data-te="<?php echo $item_id; ?>" name="unit_id[]" id="unit_id<?php echo $item_id; ?>" class="form-control unit_id<?php echo $item_id; ?> unit_id js-select1-multi" data-te="<?php echo $item_id; ?>" style="width: 100%;">
                            <option value="">Select Unit Name</option>
                            <?php
                                if(!empty($unit_val))
                                {
                                    foreach ($unit_val as $key => $value) {

                                        $unit_id   = !empty($value['unit_id'])?$value['unit_id']:'';
                                        $unit_name = !empty($value['unit_name'])?$value['unit_name']:'';

                                        if($unit_value == $unit_id)
                                        {
                                            echo "<option value=".$unit_id." selected='selected'>".$unit_name."</option>";
                                        }
                                    }
                                }
                            ?>
                        </select> 
                    </td>
                    <td class="buttonlist p-l-0">
                        <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6"><span class="ft-minus-square"></span></button>
                    </td>
                </tr>
                                                                            <?php
                                                                            $i++;
                                                                        }
                                                                    }
                                                                    else
                                                                    {
                                                                        ?>
                <tr class="row_1">
                    <td data-te="1" class="p-l-0 product_list" style="width: 30%;">
                        <select data-te="1" name="product_id[]" id="product_id1" class="form-control product_id1 product_id js-select1-multi" data-te="1">
                            <option value="">Select Product Name</option>
                        </select> 
                    </td>
                    <td data-te="1" class="p-l-0 product_list" style="width: 30%;">
                        <select data-te="1" name="type_id[]" id="type_id1" class="form-control type_id1 type_id js-select1-multi" data-te="1">
                            <option value="">Select Product Name</option>
                        </select> 
                    </td>
                    <td class="p-l-0" style="width: 13%;">
                        <input type="text" data-te="1" name="product_price[]" id="product_price1" class="form-control product_price1 product_price int_value" placeholder="Price">
                    </td>
                    <td class="p-l-0" style="width: 13%;">
                        <input type="text" data-te="1" name="product_qty[]" id="product_qty1" class="form-control product_qty1 product_qty int_value" placeholder="Quantity">

                        <input type="hidden" data-te="1" name="purchase_id[]" id="purchase_id1" class="form-control purchase_id1 purchase_id" placeholder="Enter the Price">
                    </td>
                    <td class="p-l-0" style="width: 14%;">
                        <select data-te="1" name="unit_id[]" id="unit_id1" class="form-control unit_id1 unit_id js-select1-multi" data-te="1" style="width: 100%;">
                            <option value="">Select Unit Name</option>
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
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="outlets_id" id="outlets_id" class="outlets_id" value="">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="purchase">
                                            <input type="hidden" name="func" id="func" class="func" value="add_purchase">
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
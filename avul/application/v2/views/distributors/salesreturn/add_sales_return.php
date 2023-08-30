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
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Outlet Name <span class="text-danger">*</span></label>
                                                        <select class="form-control outlet_id js-select1-multi" id="outlet_id" name="outlet_id" style="width: 100%;">
                                                            <option value="0">Select Outlet Name</option>
                                                            <?php
                                                                if(!empty($outlet_val))
                                                                {
                                                                    foreach ($outlet_val as $key => $val) {
                                                                    $outlet_id    = !empty($val['outlet_id'])?$val['outlet_id']:'';   
                                                                    $outlet_name = !empty($val['outlet_name'])?$val['outlet_name']:''; 

                                                                    

                                                                    echo "<option value=".$outlet_id.">".$outlet_name."</option>";

                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Contact Number <span class="text-danger">*</span></label>
                                                        <input type="text" id="contact_no" class="form-control contact_no int_value" placeholder="Contact Number" name="contact_no" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">GST No <span class="text-danger">*</span></label>
                                                        <input type="text" id="gst_no" class="form-control gst_no" placeholder="GST No" name="gst_no" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Invoice No <span class="text-danger">*</span></label>
                                                        <select class="form-control invoice_id js-select1-multi" id="invoice_id" name="invoice_id" style="width: 100%;">
                                                            <option value="0">Select Value</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Address <span class="text-danger">*</span></label>
                                                        <textarea id="address" class="form-control address" placeholder="Address" name="address" rows="3"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Sales Return Details <span class="text-danger">*</span></label>
                                                        <textarea id="return_details" class="form-control return_details" placeholder="Return details" name="return_details" rows="3"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="item_table" class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Category Name</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Product Name</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Price</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Quantity</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Unit</label></th>
                                                                    <th class="p-l-0">
                                                                        <button type="button" name="remove" class="btn btn-success btn-sm add_outlet_return button_size m-t-6"><span class="ft-plus-square"></span></button>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody  class="additemform">
                                                               
                <tr class="row_1">
                    <td data-te="1" class="p-l-0 product_list" style="width: 30%;">
                        <select data-te="1" name="category_id[]" id="category_id1" class="form-control category_id1 category_id js-select1-multi" data-te="1">
                            <option value="">Select Product Name</option>
                            <?php
                                if(!empty($cat_val))
                                {
                                    foreach ($cat_val as $key => $val_1) {
                                            
                                        $cat_id = !empty($val_1['category_id'])?$val_1['category_id']:'';
                                        $cat_name = !empty($val_1['category_name'])?$val_1['category_name']:'';

                                        echo '<option value="'.$cat_id.'">'.$cat_name.'</option>';
                                    }
                                }
                            ?>
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
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="distributors">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="salesreturn">
                                            <input type="hidden" name="func" id="func" class="func" value="add_sales_return">
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
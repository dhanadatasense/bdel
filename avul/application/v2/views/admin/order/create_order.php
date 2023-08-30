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
                    <!-- <a class="btn btn-info round px-2 mb-1" href="<?php echo BASE_URL.$pre_menu ?>"><i class="ft-plus-square"></i> <?php echo $pre_title; ?></a> -->
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
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">State Name <span class="text-danger">*</span></label>

                                                        <select class="form-control state_id js-select1-multi" id="state_id" name="state_id">   
                                                            <option value="">Select State Name</option>
                                                            <?php
                                                                if(!empty($state_val))
                                                                {
                                                                    foreach ($state_val as $key => $value) {
                                                                        $state_id   = !empty($value['state_id'])?$value['state_id']:'';
                                                                        $state_name = !empty($value['state_name'])?$value['state_name']:'';

                                                                        echo "<option value=".$state_id.">".$state_name."</option>";

                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">City Name <span class="text-danger">*</span></label>

                                                        <select class="form-control city_id js-select1-multi" id="city_id" name="city_id">   
                                                            <option value="">Select City Name</option>
                                                            <?php
                                                                if(!empty($city_val))
                                                                {
                                                                    foreach ($city_val as $key => $value) {
                                                                        $city_id   = !empty($value['city_id'])?$value['city_id']:'';
                                                                        $city_name = !empty($value['city_name'])?$value['city_name']:'';

                                                                        echo "<option value=".$city_id.">".$city_name."</option>";
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Beat Name <span class="text-danger">*</span></label>

                                                        <select class="form-control zone_id js-select1-multi" id="zone_id" name="zone_id">   
                                                            <option value="">Select Beat Name</option>
                                                            <?php
                                                                if(!empty($zone_val))
                                                                {
                                                                    foreach ($zone_val as $key => $value) {
                                                                        $zone_id   = !empty($value['zone_id'])?$value['zone_id']:'';
                                                                        $zone_name = !empty($value['zone_name'])?$value['zone_name']:'';

                                                                        echo "<option value=".$zone_id.">".$zone_name."</option>";
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Outlets Name <span class="text-danger">*</span></label>

                                                        <select class="form-control outlet_id js-select1-multi" id="outlet_id" name="outlet_id">   
                                                            <option value="">Select Outlets Name</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Available Limit</label>
                                                        <input type="text" id="available_limit" class="form-control available_limit int_value" placeholder="Available Limit" name="available_limit" value="">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Bill Type <span class="text-danger">*</span></label>

                                                        <select class="form-control bill_type js-select1-multi" id="bill_type" name="bill_type">   
                                                            <option value="">Select Bill Type</option>
                                                            <option value="1">COD</option>
                                                            <option value="2">Credit</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Discount </label>
                                                        <input type="text" id="discount" class="form-control discount" placeholder="Discount" name="discount" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="2">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Due Days </label>
                                                        <input type="text" id="due_days" class="form-control due_days" placeholder="Due Days" name="due_days" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="2">
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
                                                    <div class="table-responsive">
                                                        <table id="item_table" class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Product Name</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Type Name</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Price</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Quantity</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Unit</label></th>
                                                                    <th class="p-l-0">
                                                                        <button type="button" name="remove" class="btn btn-success btn-sm order_add_btn add_orders button_size m-t-6" disabled><span class="ft-plus-square" style="color: #fff !important;"></span></button>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody  class="addOrderform">
                                        <tr class="row_1">
                                            <td data-te="1" class="p-l-0 product_list" style="width: 30%;">
                                                <select data-te="1" name="product_id[]" id="product_id1" class="form-control product_id1 product_id product_val js-select1-multi" data-te="1" disabled>
                                                    <option value="0">Select Product Name</option>
                                                    <?php
                                                        if(!empty($product_val))
                                                        {
                                                            foreach ($product_val as $key => $value) {

                                                                $product_id   = !empty($value['product_id'])?$value['product_id']:'';
                                                                $product_name = !empty($value['product_name'])?$value['product_name']:'';


                                                                echo "<option value=".$product_id.">".$product_name."</option>";
                                                            }
                                                        }
                                                    ?>
                                                </select> 
                                            </td>
                                            <td data-te="1" class="p-l-0 type_list" style="width: 25%;">
                                                <select data-te="1" name="type_id[]" id="type_id1" class="form-control type_id1 type_id js-select1-multi" data-te="1" >
                                                    <option value="">Select Type Name</option>
                                                </select> 
                                            </td>
                                            <td class="p-l-0" style="width: 15%;">
                                                <input type="text" data-te="1" id="product_price1" class="form-control product_price1 product_price int_value" placeholder="Price" disabled style="background-color: #fff !important;">
                                            </td>
                                            <td class="p-l-0" style="width: 15%;">
                                                <input type="text" data-te="1" name="product_qty[]" id="product_qty1" class="form-control product_qty1 product_qty int_value" placeholder="Quantity">

                                                <input type="hidden" data-te="1" name="product_price[]" id="product_price1" class="form-control product_price1 product_price int_value" placeholder="Price">

                                                <input type="hidden" data-te="1" name="pack_qty[]" id="pack_qty1" class="form-control pack_qty1 pack_qty int_value" placeholder="Quantity">

                                                <input type="hidden" data-te="1" name="stock_check[]" id="stock_check1" class="form-control stock_check1 stock_check int_value" placeholder="Quantity">

                                                <input type="hidden" data-te="1" name="hsn_code[]" id="hsn_code1" class="form-control hsn_code1 hsn_code" placeholder="Quantity">

                                                <input type="hidden" data-te="1" name="gst_val[]" id="gst_val1" class="form-control gst_val1 gst_val" placeholder="Quantity">
                                            </td>
                                            <td class="p-l-0" style="width: 15%;">
                                                <select data-te="1" name="unit_id[]" id="unit_id1" class="form-control unit_id1 unit_id js-select1-multi" data-te="1" >
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
                                            <input type="hidden" name="order_type" id="order_type" class="order_type" value="1">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="order">
                                            <input type="hidden" name="func" id="func" class="func" value="create_order">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" id="method" class="method" value="<?php echo $method; ?>">
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
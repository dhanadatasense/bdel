<?php
        
    $loyalty_head = !empty($dataval['loyalty_header'])?$dataval['loyalty_header']:'';
    $loyalty_body = !empty($dataval['loyalty_body'])?$dataval['loyalty_body']:'';

    $loyalty_id   = !empty($loyalty_head['loyalty_id'])?$loyalty_head['loyalty_id']:'';
    $state_res    = !empty($loyalty_head['state_id'])?$loyalty_head['state_id']:'';
    $city_res     = !empty($loyalty_head['city_id'])?$loyalty_head['city_id']:'';
    $beat_res     = !empty($loyalty_head['beat_id'])?$loyalty_head['beat_id']:'';
    $outlet_res   = !empty($loyalty_head['outlet_id'])?$loyalty_head['outlet_id']:'';
    $vendor_res   = !empty($loyalty_head['vendor_id'])?$loyalty_head['vendor_id']:'';
    $start_date   = !empty($loyalty_head['start_date'])?$loyalty_head['start_date']:'';
    $end_date     = !empty($loyalty_head['end_date'])?$loyalty_head['end_date']:'';
    $category_res = !empty($loyalty_head['category_val'])?$loyalty_head['category_val']:'';

    $view_value = 'hide';
    $hide_value = 'show';
    $column_val = 'col-md-10';

    if($method == 'BTBM_X_U')
    {
        $view_value = 'show';
        $hide_value = 'hide';
        $column_val = 'col-md-12';
    }
?>

<style type="text/css">
    .table td, .table th
    {
        vertical-align: middle;
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
                            <li class="breadcrumb-item"><a href="#"><?php echo $sub_heading; ?></a>
                            </li>
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
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">State Name</label>

                                                        <select class="form-control state_id js-select1-multi" id="state_id" name="state_id">   
                                                            <option value="0">Select State Name</option>
                                                            <?php
                                                                if(!empty($state_val))
                                                                {
                                                                    foreach ($state_val as $key => $val_1) {
                                                                        $state_id   = !empty($val_1['state_id'])?$val_1['state_id']:'';
                                                                        $state_name = !empty($val_1['state_name'])?$val_1['state_name']:'';

                                                                        if($state_res == $state_id)
                                                                        {
                                                                            $selected = 'selected';
                                                                        }
                                                                        else
                                                                        {
                                                                            $selected = '';
                                                                        }

                                                                        echo "<option value=".$state_id." ".$selected.">".$state_name."</option>";

                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">City Name</label>

                                                        <select class="form-control city_id js-select1-multi" id="city_id" name="city_id">   
                                                            <option value="0">Select City Name</option>
                                                            <?php
                                                                if(!empty($city_val))
                                                                {
                                                                    foreach ($city_val as $key => $val_2) {
                                                                        $city_id   = !empty($val_2['city_id'])?$val_2['city_id']:'';
                                                                        $city_name = !empty($val_2['city_name'])?$val_2['city_name']:'';

                                                                        if($city_res == $city_id)
                                                                        {
                                                                            $selected = 'selected';
                                                                        }
                                                                        else
                                                                        {
                                                                            $selected = '';
                                                                        }

                                                                        echo "<option value=".$city_id." ".$selected.">".$city_name."</option>";

                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Beat Name</label>
                                                        <select class="form-control zone_id js-select1-multi" id="zone_id" name="zone_id">   
                                                            <option value="0">Select Beat Name</option>
                                                            <?php
                                                                if(!empty($beat_val))
                                                                {
                                                                    foreach ($beat_val as $key => $val_3) {
                                                                        $zone_id   = !empty($val_3['zone_id'])?$val_3['zone_id']:'';
                                                                        $zone_name = !empty($val_3['zone_name'])?$val_3['zone_name']:'';

                                                                        if($beat_res == $zone_id)
                                                                        {
                                                                            $selected = 'selected';
                                                                        }
                                                                        else
                                                                        {
                                                                            $selected = '';
                                                                        }

                                                                        echo "<option value=".$zone_id." ".$selected.">".$zone_name."</option>";

                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Outlet Name</label>

                                                        <select class="form-control outlet_id js-select1-multi" id="outlet_id" name="outlet_id">   
                                                            <option value="0">Select Outlet Name</option>
                                                            <?php
                                                                if(!empty($outlet_val))
                                                                {
                                                                    foreach ($outlet_val as $key => $val_4) {
                                                                        $outlets_id   = !empty($val_4['outlets_id'])?$val_4['outlets_id']:'';
                                                                        $company_name = !empty($val_4['company_name'])?$val_4['company_name']:'';

                                                                        if($outlet_res == $outlets_id)
                                                                        {
                                                                            $selected = 'selected';
                                                                        }
                                                                        else
                                                                        {
                                                                            $selected = '';
                                                                        }

                                                                        echo "<option value=".$outlets_id." ".$selected.">".$company_name."</option>";

                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Manufacturer Name</label>

                                                        <select class="form-control vendor_id js-select1-multi" id="vendor_id" name="vendor_id">   
                                                            <option value="0">Select Manufacturer Name</option>
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
                                                        <label for="projectinput1">Start Date <span class="text-danger">*</span></label>
                                                        <input type="text" id="start_date" class="form-control start_date atdates" placeholder="Start Date" name="start_date" value="<?php echo $start_date; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">End Date <span class="text-danger">*</span></label>
                                                        <input type="text" id="end_date" class="form-control end_date atdates" placeholder="End Date" name="end_date" value="<?php echo $end_date; ?>">
                                                    </div>
                                                </div>
                                                <div class="<?php echo $column_val; ?>">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Category Name <span class="text-danger">*</span></label>
                                                        <select class="form-control category_val js-example-placeholder-multiple" id="category_id" name="category_val[]" multiple="multiple" style="width: 100%;">
                                                            <?php
                                                                if(!empty($category_val))
                                                                {
                                                                    foreach ($category_val as $key => $value) {

                                                                        $category_id   = !empty($value['category_id'])?$value['category_id']:'';
                                                                        $category_name = !empty($value['category_name'])?$value['category_name']:'';

                                                                                $select   = '';
                                                                                $category_data = explode(',', $category_res);
                                                                                if(in_array($category_id, $category_data))
                                                                                {
                                                                                    $select = 'selected';
                                                                                }

                                                                        echo "<option value=".$category_id." ".$select.">".$category_name."</option>";
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                        <input style="margin-top: 10px;" type="checkbox" id="check_category"> Select all category
                                                    </div>
                                                </div>
                                                <div class="col-md-2 <?php echo $hide_value; ?>">
                                                    <div class="form-group">
                                                        <input type="hidden" name="method" id="method" class="method" value="<?php echo $method;?>">

                                                        <input type="hidden" name="value" id="value" class="value" value="admin">
                                                        <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="loyalty">
                                                        <input type="hidden" name="func" id="func" class="func" value="add_product_loyalty">

                                                        <button type="button" class="btn btn-primary rpt_submit outletLoyalty_list" data-type="_s_c" data-value="admin" data-cntrl="loyalty" data-func="add_product_loyalty" style="margin-top: 11%;">
                                                            <span class="rpt_btn show"><i class="la la-check-square-o"></i> Submit</span>

                                                            <span class="rpt_span hide"><i class="la la-spinner spinner"></i> Loading....</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 filter-design">
                                                <div class="loyalty_value <?php echo $view_value; ?>">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Description</th>
                                                                    <th>Discount</th>
                                                                    <th>Value</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="getLoyaltyList">
    <?php
        if($loyalty_body)
        {
            $num = 1;
            foreach ($loyalty_body as $key => $val) {
                $auto_id      = !empty($val['auto_id'])?$val['auto_id']:'';
                $cat_id       = !empty($val['category_id'])?$val['category_id']:'';
                $pdt_id       = !empty($val['product_id'])?$val['product_id']:'';
                $type_id      = !empty($val['type_id'])?$val['type_id']:'';
                $loyalty_type = !empty($val['loyalty_type'])?$val['loyalty_type']:'';
                $pdt_price    = !empty($val['product_price'])?$val['product_price']:'';
                $desc         = !empty($val['description'])?$val['description']:'';

                ?>
                    <tr class="row_<?php echo $auto_id; ?>">
                        <td><?php echo $num; ?></td>
                        <td><?php echo mb_strimwidth($desc, 0, 50, '...'); ?></td>
                        <td>
                            <select class="form-control loyalty_type loyalty_type_<?php echo $num; ?>" data-te="<?php echo $num; ?>" id="loyalty_type" name="loyalty_type[]" style="height: calc(2em + 1.0rem + 0px); padding: 10px; width: 120px;">
                                <option value="1" <?php echo ($loyalty_type == 1) ? 'selected' : ''; ?>>Percentage</option>
                                <option value="2" <?php echo ($loyalty_type == 2) ? 'selected' : ''; ?>>Amount</option>
                            </select>
                        </td>
                        <td style="padding-top: 12px;">
                            <input data-val="" type="hidden" id="auto_id" class="form-control auto_id auto_id_<?php echo $num; ?> int_value" name="auto_id[]" value="<?php echo $auto_id; ?>" style="height: calc(2em + 1.0rem + 0px); padding: 10px; width: 100px;">
                            <input data-val="" type="hidden" id="category_id" class="form-control category_id category_id_<?php echo $num; ?> int_value" name="category_id[]" value="<?php echo $cat_id; ?>" style="height: calc(2em + 1.0rem + 0px); padding: 10px; width: 100px;">
                            <input data-val="" type="hidden" id="product_id" class="form-control product_id product_id_<?php echo $num; ?> int_value" name="product_id[]" value="<?php echo $pdt_id; ?>" style="height: calc(2em + 1.0rem + 0px); padding: 10px; width: 100px;">
                            <input data-val="" type="hidden" id="type_id" class="form-control type_id type_id_<?php echo $num; ?> int_value" name="type_id[]" value="<?php echo $type_id; ?>" style="height: calc(2em + 1.0rem + 0px); padding: 10px; width: 100px;">
                            <input data-val="" type="text" id="pdt_price" class="form-control pdt_price pdt_price_<?php echo $num; ?> int_value" name="pdt_price[]" placeholder="" style="height: calc(2em + 1.0rem + 0px); padding: 10px; width: 100px;" value="<?php echo $pdt_price; ?>" maxlength="2">
                        </td>
                        <td class="buttonlist p-l-0 text-center">
                            <button type="button" data-row="<?php echo $num; ?>" data-id="<?php echo $auto_id; ?>" data-value="admin" data-cntrl="loyalty" data-func="list_product_loyalty" name="remove" class="single-del btn btn-danger btn-sm  button_size m-t-6"><span class="ft-minus-square"></span></button>
                        </td>
                    </tr>
                <?php
                $num++;
            }      
        }
    ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 filter-design">
                                                <div id="loyalty_error" class="alert alert-danger text-center <?php echo $hide_value; ?>">
                                                    <b>No items found...</b>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions submit_btn <?php echo $view_value; ?>">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" class="method" value="<?php echo $method;?>">
                                            <input type="hidden" name="loyalty_id" id="loyalty_id" class="loyalty_id" value="<?php echo $loyalty_id; ?>">
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
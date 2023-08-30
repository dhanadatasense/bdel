<?php
    $assign_details = !empty($dataval['assign_details'])?$dataval['assign_details']:'';
    $store_list     = !empty($dataval['store_list'])?$dataval['store_list']:'0';

    // Assign Emplyee Details
    $assign_val = !empty($assign_details['assign_id'])?$assign_details['assign_id']:'';
    $emp_id     = !empty($assign_details['emp_id'])?$assign_details['emp_id']:'';
    $emp_name   = !empty($assign_details['emp_name'])?$assign_details['emp_name']:'';
    $month_id   = !empty($assign_details['month_id'])?$assign_details['month_id']:'';
    $finan_id   = !empty($assign_details['finan_id'])?$assign_details['finan_id']:'';
    $status     = !empty($assign_details['status'])?$assign_details['status']:'0';

    $view_value = 'hide';
    $hide_value = 'show';

    if($method == 'BTBM_X_U')
    {
        $view_value = 'show';
        $hide_value = 'hide';
    }
?>
<style type="text/css">
    .zone_list .select2-container--default
    {
        width: 99% !important;
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
                                                        <label for="projectinput1">Employee <span class="text-danger">*</span></label>
                                                        <select class="form-control employee_id js-select1-multi" id="employee_id" name="employee_id" style="width: 100%;">
                                                            <option value="">Vendor</option>
                                                            <?php
                                                                if (!empty($emp_list))
                                                                {
                                                                    foreach ($emp_list as $key => $value_1)
                                                                    {
                        $employee_id = !empty($value_1['employee_id'])?$value_1['employee_id']:'';
                        $username    = !empty($value_1['username'])?$value_1['username']:'';
                        $mobile      = !empty($value_1['mobile'])?$value_1['mobile']:'';

                        $select = '';
                        if($employee_id == $emp_id)
                        {
                            $select = 'selected';
                        }

                        echo '<option value="'.$employee_id.'" '.$select.'>'.$username.' ('.$mobile.')'.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Month <span class="text-danger">*</span></label>
                                                        <select class="form-control month_id js-select1-multi" id="month_id" name="month_id" style="width: 100%;">
                                                            <option value="">Vendor</option>
                                                            <?php
                                                                if (!empty($month_list))
                                                                {
                                                                    foreach ($month_list as $key => $value_2)
                                                                    {
                                                                        $month_value = !empty($value_2['month_value'])?$value_2['month_value']:'';
                                                                        $month_name  = !empty($value_2['month_name'])?$value_2['month_name']:'';

                                                                        $select = '';
                                                                        if($month_value == $month_id)
                                                                        {
                                                                            $select = 'selected';
                                                                        }

                                                                        echo '<option value="'.$month_value.'" '.$select.'>'.$month_name.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-primary shop_list shop_submit m-t-27 <?php echo $hide_value; ?>" data-type="_s_c" data-value="admin" data-cntrl="assignshop" data-func="add_assign_shop">
                                                            <span class="assign_btn show"><i class="la la-check-square-o"></i> Submit</span>

                                                            <span class="assign_span hide"><i class="la la-spinner spinner"></i> Loading....</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 filter-design">
                                                <div class="assign_value <?php echo $view_value; ?>">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <th>Day</th>
                                                                    <th style="padding-left: 0px;">Beat Name</th>
                                                                    <!-- <th>Action</th> -->
                                                                </tr>
                                                            </thead>
                                                            <tbody id="getShopList">
                                                                <?php
                                                                    if(!empty($store_list))
                                                                    {
                                                                        $i=1;
                                                                        foreach ($store_list as $key => $value) {
                $auto_id      = !empty($value['auto_id'])?$value['auto_id']:'';
                $assign_id    = !empty($value['assign_id'])?$value['assign_id']:'';
                $assign_date  = !empty($value['assign_date'])?$value['assign_date']:'';
                $assign_day   = !empty($value['assign_day'])?$value['assign_day']:'';
                $assign_store = !empty($value['assign_store'])?$value['assign_store']:'';
                $status       = !empty($value['status'])?$value['status']:'';

                ?>
                    <tr>
                        <td><?php echo $assign_date; ?></td>
                        <td><?php echo $assign_day; ?></td>
                        <td data-te="<?php echo $i; ?>" class="p-l-0 zone_list">
                            <select class="form-control zone_id js-example-placeholder-multiple" id="zone_id" name="zone_id_<?php echo $i; ?>[]" multiple="multiple">';
                                <?php
                                    if(!empty($zone_list))
                                    {
                                        foreach ($zone_list as $key => $value_3) {
                                            $zone_id   = !empty($value_3['zone_id'])?$value_3['zone_id']:'';
                                            $state_id  = !empty($value_3['state_id'])?$value_3['state_id']:'';
                                            $city_id   = !empty($value_3['city_id'])?$value_3['city_id']:'';
                                            $zone_name = !empty($value_3['zone_name'])?$value_3['zone_name']:'';

                                            $store_val = explode(',', $assign_store);

                                            $select = '';

                                            if(in_array($zone_id, $store_val))
                                            {
                                                $select = 'selected';
                                            }

                                            echo '<option value="'.$zone_id.'" '.$select.'>'.$zone_name.'</<option>';
                                        }
                                    }
                                ?>    
                            </select> 
                            <input type="hidden" name="date_val[]" value="<?php echo $assign_date; ?>">
                            <input type="hidden" name="day_val[]" value="<?php echo $assign_day; ?>">
                            <input type="hidden" name="auto_id[]" value="<?php echo $auto_id; ?>">
                        </td>
                        <!-- <td>
                            <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6 remove_store"><span class="ft-minus-square"></span></button>
                        </td> -->
                    </tr>
                <?php
                                                                            $i++;                    
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
                                                <div id="assign_error" class="<?php echo $hide_value; ?> alert alert-danger text-center">
                                                    <b>No items found...</b>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions submit_btn <?php echo $view_value; ?>">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="assign_id" id="assign_id" class="assign_id" value="<?php echo $assign_val; ?>">
                                            <input type="hidden" name="month_val" id="month_val" class="month_val" value="0">
                                            <input type="hidden" name="year_val" id="year_val" class="year_val" value="0">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="assignshop">
                                            <input type="hidden" name="func" id="func" class="func" value="add_assign_shop">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" class="method" value="<?php echo $method;?>">
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
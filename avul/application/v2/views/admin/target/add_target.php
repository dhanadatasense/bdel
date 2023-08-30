<?php

    $assign_det  = !empty($dataval['assign_details'])?$dataval['assign_details']:'';
    $target_list = !empty($dataval['target_list'])?$dataval['target_list']:'';

    $target_id  = !empty($assign_det['target_id'])?$assign_det['target_id']:'';
    $month_id   = !empty($assign_det['month_id'])?$assign_det['month_id']:'';
    $month_name = !empty($assign_det['month_name'])?$assign_det['month_name']:'';
    $year_id    = !empty($assign_det['year_id'])?$assign_det['year_id']:'';
    $year_value = !empty($assign_det['year_value'])?$assign_det['year_value']:'';
    $status     = !empty($assign_det['status'])?$assign_det['status']:'0';

    $view_value = 'hide';
    $hide_value = 'show';

    if($method == 'BTBM_X_U')
    {
        $view_value = 'show';
        $hide_value = 'hide';
    }
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
                    <!-- <?php if($page_access): ?> -->
                    <a class="btn btn-info round px-2 mb-1" href="<?php echo BASE_URL.$pre_menu ?>"><i class="ft-plus-square"></i> <?php echo $pre_title; ?></a>
                    <!-- <?php endif; ?> -->
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

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Select Month <span class="text-danger">*</span></label>
                                                        <select class="form-control month_id js-select1-multi" id="month_id" name="month_id" style="width: 100%;">
                                                            <option value="">Select Month</option>
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
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Select Year<span class="text-danger">*</span></label>
                                                        <select class="form-control year_id js-select1-multi" id="year_id" name="year_id" style="width: 100%;">
                                                            <option value="">Select Year</option>
                                                            <?php
                                                                if (!empty($year_list))
                                                                {
                                                                    foreach ($year_list as $key => $value_2)
                                                                    {
        $year_value = !empty($value_2['year_id'])?$value_2['year_id']:'';
        $year_name  = !empty($value_2['year_name'])?$value_2['year_name']:'';

        $select = '';
        if($year_value == $year_id)
        {
            $select = 'selected';
        }

        echo '<option value="'.$year_value.'" '.$select.'>'.$year_name.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-primary target_list target_submit m-t-27 <?php echo $hide_value; ?>" data-type="_s_c" data-value="admin" data-cntrl="target" data-func="add_target">
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
                                                                    <th>#</th>
                                                                    <th>Employee Name</th>
                                                                    <th>Mobile No</th>
                                                                    <th>Target Value</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="getTargetList">
                                                                <?php
                                                                    if(!empty($target_list))
                                                                    {
        $num = 1;
        foreach ($target_list as $key => $val) {
            $auto_id         = !empty($val['auto_id'])?$val['auto_id']:'';
            $target_id       = !empty($val['target_id'])?$val['target_id']:'';
            $employee_id     = !empty($val['employee_id'])?$val['employee_id']:'';
            $employee_name   = !empty($val['employee_name'])?$val['employee_name']:'';
            $employee_mobile = !empty($val['employee_mobile'])?$val['employee_mobile']:'';
            $target_val      = !empty($val['target_val'])?$val['target_val']:'0';
            $achieve_val     = !empty($val['achieve_val'])?$val['achieve_val']:'';

            ?>
                <tr>
                    <td><?php echo $num; ?></td>
                    <td><?php echo $employee_name; ?></td>
                    <td><?php echo $employee_mobile; ?></td>
                    <td>
                        <input type="text" data-te="<?php echo $num; ?>" name="target_val[]" id="target_val<?php echo $num; ?>" class="form-control target_val<?php echo $num; ?> target_val int_value" placeholder="Target Value" value="<?php echo $target_val; ?>"> 

                        <input type="hidden" data-te="<?php echo $num; ?>" name="employee_id[]" id="employee_id<?php echo $num; ?>" class="form-control employee_id<?php echo $num; ?> employee_id" placeholder="Target Value" value="<?php echo $employee_id; ?>">

                        <input type="hidden" data-te="<?php echo $num; ?>" name="auto_id[]" id="auto_id<?php echo $num; ?>" class="form-control auto_id<?php echo $num; ?> auto_id" placeholder="Target Value" value="<?php echo $auto_id; ?>">
                    </td>
                    <td class="buttonlist p-l-0 text-center">
                        <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6 remove_item"><span class="ft-minus-square"></span></button>
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
                                                <div id="assign_error" class="<?php echo $hide_value; ?> alert alert-danger text-center">
                                                    <b>No items found...</b>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions submit_btn <?php echo $view_value; ?>">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="target_id" id="target_id" class="target_id" value="<?php echo $target_id; ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="target">
                                            <input type="hidden" name="func" id="func" class="func" value="add_target">
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
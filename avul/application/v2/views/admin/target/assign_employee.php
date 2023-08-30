<?php
    
    $target_data = !empty($dataval['target_data'])?$dataval['target_data']:'';
    $target_list = !empty($dataval['target_list'])?$dataval['target_list']:'';

    $month_id    = !empty($target_data['month_id'])?$target_data['month_id']:'';
    $year_id     = !empty($target_data['year_id'])?$target_data['year_id']:'';
    $employee_id = !empty($target_data['employee_id'])?$target_data['employee_id']:'';
    $category_id = !empty($target_data['category_id'])?$target_data['category_id']:'';
    $status      = !empty($assign_det['status'])?$assign_det['status']:'0';


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
                                            <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Template Name <span class="text-danger">*</span></label>
                                                        <select class="form-control category_id js-select1-multi" id="template_id" name="template_id" style="width: 100%;">
                                                            <option value="">Select Template Name</option>
                                                            <?php
                                                                if(!empty($template_name))
                                                                {
                                                                    foreach ($template_name as $key => $value) 
                                                                    {
                                                                        $category_value   = !empty($value['id'])?$value['id']:'';
                                                                        $category_name = !empty($value['templatename'])?$value['templatename']:'';

                                                                        if($category_value == $category_id)
                                                                        {
                                                                            $selected = 'selected';
                                                                        }
                                                                        else
                                                                        {
                                                                            $selected = '';
                                                                        }

                                                                        echo "<option value=".$category_value." ".$selected.">".$category_name."</option>";
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Month Name <span class="text-danger">*</span></label>
                                                        <select class="form-control monthh_id js-select1-multi" id="month_id" name="month_id" style="width: 100%;">
                                                            <option value="">Select Vendor Name</option>
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
                                                        <label for="projectinput1">Year Name <span class="text-danger">*</span></label>
                                                        <select class="form-control yearr_id js-select1-multi" id="year_id" name="year_id" style="width: 100%;">
                                                            <option value="">Select Vendor Name</option>
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
                                                                       
                                                                    }
                                                                    echo '<option value="'.$year_value.'" '.$select.'>'.$year_name.'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Employee Name <span class="text-danger">*</span></label>
                                                        <select class="form-control employee_id js-select1-multi" id="employee_id" name="employee_id[]"  multiple="multiple" style="width: 100%;">
                                                            <?php
                                                                if (!empty($emp_val))
                                                                {
                                                                    foreach ($emp_val as $key => $value_3)
                                                                    {
                                                                        $emp_val  = !empty($value_3['employee_id'])?$value_3['employee_id']:'';
                                                                        $emp_name = !empty($value_3['emp_name'])?$value_3['emp_name']:'';

                                                                        $select = '';
                                                                        if($emp_val == $employee_id)
                                                                        {
                                                                            $select = 'selected';
                                                                        }

                                                                        echo '<option value="'.$emp_val.'" '.$select.'>'.$emp_name.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                        <input style="margin-top: 10px;" type="checkbox" id="check_employee"> Select all Employee
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions submit_btn ">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="target">
                                            <input type="hidden" name="func" id="func" class="func" value="assign_employee">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" class="method" value="BTBM_X_U">
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
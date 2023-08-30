<style type="text/css">
    .select2-selection__choice
    {
        font-size: 11px !important;
    }

    .select2-selection__rendered
    {
        position: absolute !important;
        top: 8px !important;
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
        </div>
        <?php if(userAccess('backlog-report-view') == TRUE): ?>
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
                                                        <label for="projectinput1">State Name </label>

                                                        <select class="form-control state_id js-select1-multi" id="state_id" name="state_id">   
                                                            <option value="">Select State Name</option>
                                                            <?php
                                                                if(!empty($state_val))
                                                                {
                                                                    foreach ($state_val as $key => $value) {
                                                                        $state_id   = !empty($value['state_id'])?$value['state_id']:'';
                                                                        $state_name = !empty($value['state_name'])?$value['state_name']:'';

                                                                        if($state_value == $state_id)
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
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">City Name </label>

                                                        <select class="form-control city_id js-select1-multi" id="city_id" name="city_id">   
                                                            <option value="">Select City Name</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Beat Name </label>

                                                        <select class="form-control zone_id js-select1-multi" id="zone_id" name="zone_id">   
                                                            <option value="">Select Beat Name</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Select Employee Name</label>
                                                        <select class="form-control employee_id js-select1-multi" id="employee_id" name="employee_id" style="width: 100%;">
                                                            <option value="">Select Vendor Name</option>
                                                            <?php
                                                                if (!empty($employee_val))
                                                                {
                                                                    foreach ($employee_val as $key => $value_1)
                                                                    {
                                                                        $employee_id = !empty($value_1['employee_id'])?$value_1['employee_id']:'';
                                                                        $username    = !empty($value_1['username'])?$value_1['username']:'';
                                                                        $mobile      = !empty($value_1['mobile'])?$value_1['mobile']:'';

                                                                        echo '<option value="'.$employee_id.'">'.$username.' ('.$mobile.')'.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Category Name</label>
                                                        <select class="form-control category_id js-example-placeholder-multiple" id="category_id" name="category_id[]" multiple="multiple" style="width: 100%;">
                                                            <?php
                                                                if(!empty($category_val))
                                                                {
                                                                    foreach ($category_val as $key => $value) {

                                                                        $category_id   = !empty($value['category_id'])?$value['category_id']:'';
                                                                        $category_name = !empty($value['category_name'])?$value['category_name']:'';

                                                                        if($category_value == $category_id)
                                                                        {
                                                                            $selected = 'selected';
                                                                        }
                                                                        else
                                                                        {
                                                                            $selected = '';
                                                                        }

                                                                        echo "<option value=".$category_id." ".$selected.">".$category_name."</option>";
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                        <input style="margin-top: 10px;" type="checkbox" id="check_category"> Select all category
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Start Date <span class="text-danger">*</span></label>
                                                        <input type="text" id="start_date" class="form-control start_date dates" placeholder="Start Date" name="start_date" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">End Date <span class="text-danger">*</span></label>
                                                        <input type="text" id="end_date" class="form-control end_date dates" placeholder="End Date" name="end_date" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="hidden" name="method" id="method" class="method" value="<?php echo $method;?>">

                                                        <input type="hidden" name="value" id="value" class="value" value="admin">
                                                        <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="report">
                                                        <input type="hidden" name="func" id="func" class="func" value="backlog_report">

                                                        <button type="button" class="btn btn-primary rpt_submit vendorOverall_report vendorOverall_submit m-t-27" data-type="_s_c" data-value="admin" data-cntrl="report" data-func="backlog_report">
                                                            <span class="rpt_btn show"><i class="la la-check-square-o"></i> Submit</span>

                                                            <span class="rpt_span hide"><i class="la la-spinner spinner"></i> Loading....</span>
                                                        </button>
                                                        <span class="excel_val"></span>
                                                        <span class="pdf_val"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 filter-design">
                                                <div class="vendorOverall_value hide">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Order No</th>
                                                                    <th>Product Name</th>
                                                                    <th>Price</th>
                                                                    <th>Quantity</th>
                                                                    <th>Total Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="getVendorOverallList">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 filter-design">
                                                <div id="page_error" class="alert alert-danger text-center">
                                                    <b>No items found...</b>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php endif; ?>
        
        <?php if(userAccess('backlog-report-view') == FALSE): ?>
        <div class="row">
            <div class="col-sm-12 filter-design">
                <div class="alert alert-danger text-center">
                    <b class="error_msg">Access denied...</b>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
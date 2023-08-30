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
            <!-- <div class="content-header-right col-md-6 col-12">
                <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                    <a class="btn btn-info round px-2 mb-1" href="<?php echo BASE_URL.$pre_menu ?>"><i class="ft-plus-square"></i> <?php echo $pre_title; ?></a>
                </div>
            </div> -->
        </div>
        <?php if(userAccess('attendance-report-view') == TRUE): ?>
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
                                                        <label for="projectinput1">Select State Name<span class="text-danger">*</span></label>
                                                        <select class="form-control state_c_id js-select1-multi" id="state_c_id" name="state_c_id" style="width: 100%;">
                                                            <option value="">Select State Name</option>
                                                            <?php
                                                                if (!empty($state_list))
                                                                {
                                                                    foreach ($state_list as $key => $value_1)
                                                                    {
                                                                        $state_id = !empty($value_1['state_id'])?$value_1['state_id']:'';
                                                                        $state_name    = !empty($value_1['state_name'])?$value_1['state_name']:'';
                                                                        $state_code      = !empty($value_1['state_code'])?$value_1['state_code']:'';

                                                                        echo '<option value="'.$state_id.'">'.$state_name.' ('.$state_code.')'.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Select City Name<span class="text-danger">*</span></label>
                                                        <select class="form-control city_z_id js-select1-multi" id="city_z_id" name="city_z_id" style="width: 100%;">
                                                            <!-- <option value="">Select City Name</option>
                                                            <?php
                                                                // if (!empty($city_list))
                                                                // {
                                                                //     foreach ($city_list as $key => $value_1)
                                                                //     {
                                                                //         $city_id = !empty($value_1['city_id'])?$value_1['city_id']:'';
                                                                //         $city_name    = !empty($value_1['city_name'])?$value_1['city_name']:'';
                                                                //         $city_code      = !empty($value_1['city_code'])?$value_1['city_code']:'';

                                                                //         echo '<option value="'.$city_id.'">'.$city_name.' ('.$city_code.')'.'</option>';
                                                                //     }
                                                                // }
                                                            ?> -->
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Select Zone Name</label>
                                                        <select class="form-control zone_z_id js-select1-multi" id="zone_z_id" name="zone_z_id" style="width: 100%;">
                                                            <!-- <option value="">Select Zone Name</option>
                                                            <?php
                                                                // if (!empty($zone_list))
                                                                // {
                                                                //     foreach ($zone_list as $key => $value_1)
                                                                //     {
                                                                //         $zone_id = !empty($value_1['zone_id'])?$value_1['zone_id']:'';
                                                                //         $zone_name    = !empty($value_1['zone_name'])?$value_1['zone_name']:'';
                                                                //         $zone_code      = !empty($value_1['zone_code'])?$value_1['zone_code']:'';

                                                                //         echo '<option value="'.$zone_id.'">'.$zone_name.' ('.$zone_code.')'.'</option>';
                                                                //     }
                                                                // }
                                                            ?> -->
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="hidden" name="method" id="method" class="method" value="<?php echo $method;?>">
                                                        <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="managers">
                                                        <input type="hidden" name="func" id="func" class="func" value="hierarchy_list">
                                                        <input type="hidden" name="value" id="value" class="value" value="admin">
                                                        <button type="button" class="btn btn-primary rpt_submit hierarchy_list  m-t-27" data-type="_s_c" data-value="admin" data-cntrl="Managers" data-func="hierarchy_list">
                                                            <span class="rpt_btn show"><i class="la la-check-square-o"></i> Submit</span>

                                                            <span class="rpt_span hide"><i class="la la-spinner spinner"></i> Loading....</span>
                                                        </button>
                                                        <!-- <span class="excel_val"></span>
                                                        <span class="pdf_val"></span> -->
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="hierarchy_value hide">
                                                <div class="col-md-4 ">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Regional Sales Manager </label>
                                                        <input type="text" id="rsm" class="form-control rsm no_entry">
                                                    </div>
                                                </div>
                                                <div class="col-md-4 ">
                                                    <div class="form-group">
                                                            <label for="projectinput1">Area Sales Manager </label>
                                                            <input type="text" id="asm" class="form-control asm no_entry ">
                                                    </div>
                                                </div>
                                                <div class="col-md-4 ">
                                                    <div class="form-group">
                                                            <label for="projectinput1">Sales Officer </label>
                                                            <input type="text" id="so" class="form-control so no_entry">
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 filter-design">
                                                <div class="hierarchy_value hide">

                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Zone Name</th>
                                                                    <th>TSI Name</th>
                                                                    <th>BDE Name</th>
                                                                    <th>Status</th>
                                                                    <!-- <th>Attendance Type</th> -->
                                                                </tr>
                                                            </thead>
                                                            <tbody id="getHierarchyList">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 filter-design">
                                                <div id="hierarchy_error" class="alert alert-danger text-center">
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

        <?php if(userAccess('attendance-report-view') == FALSE): ?>
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
<style>
        .img-size {
      /* Remove fixed height and width */
      max-height: 100%;
      max-width: 100%;
      /* Center the image */
      margin: auto;
    }

    .modal-dialog {
      /* Set maximum width for the modal */
      max-width: 800px;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
      width: 30px;
      height: 48px;
    }
  
</style>
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
        <?php if(userAccess('employee-daily-report-view') == TRUE): ?>
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
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Select Date <span class="text-danger">*</span></label>
                                                        <input type="text" id="sel_date" class="form-control sel_date dates" placeholder="Start Date" name="sel_date" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Employee Name <span class="text-danger">*</span></label>
                                                        <select class="form-control employee_id js-select1-multi" id="employee_id" name="employee_id" style="width: 100%;">
                                                            <option value="">Select Vendor Name</option>
                                                            <?php
                                                                if (!empty($emp_list))
                                                                {
                                                                    foreach ($emp_list as $key => $value_3)
                                                                    {
                                                                        $employee_id = !empty($value_3['employee_id'])?$value_3['employee_id']:'';
                                                                        $username    = !empty($value_3['username'])?$value_3['username']:'';
                                                                        $mobile      = !empty($value_3['mobile'])?$value_3['mobile']:'';

                                                                        echo '<option value="'.$employee_id.'">'.$username.' ('.$mobile.')'.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="hidden" name="method" id="method" class="method" value="<?php echo $method;?>">
                                                        <button type="button" class="btn btn-primary rpt_submit empDaily_report empDaily_submit m-t-27" data-type="_s_c" data-value="admin" data-cntrl="report" data-func="employee_report">
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
                                        	<div class="col-sm-12 target_value hide">
                                        		<div id="getEmployeeRptDetails">
                                                </div>
                                        	</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 filter-design">
                                                <div id="page_error" class="alert alert-danger text-center show">
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

        <?php if(userAccess('employee-daily-report-view') == FALSE): ?>
        <div class="row">
            <div class="col-sm-12 filter-design">
                <div class="alert alert-danger text-center">
                    <b class="error_msg">Access denied...</b>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!-- modal -->
        <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                <!-- carousel -->
                <div id='carouselExampleIndicators' class='carousel slide' data-ride='carousel'>
                    <ol class='carousel-indicators'>
                    <!-- Carousel indicators will be populated dynamically -->
                    </ol>
                    <div class='carousel-inner'>
                    <!-- Carousel items will be populated dynamically -->
                    </div>
                    <a class='carousel-control-prev' href='#carouselExampleIndicators' role='button' data-slide='prev'>
                    <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                    <span class='sr-only'>Previous</span>
                    </a>
                    <a class='carousel-control-next' href='#carouselExampleIndicators' role='button' data-slide='next'>
                    <span class='carousel-control-next-icon' aria-hidden='true'></span>
                    <span class='sr-only'>Next</span>
                    </a>
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
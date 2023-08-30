<?php
    $user_id      = !empty($dataval['user_id'])?$dataval['user_id']:'';
    $username     = !empty($dataval['username'])?$dataval['username']:'';
    $mobile       = !empty($dataval['mobile'])?$dataval['mobile']:'';
    $email        = !empty($dataval['email'])?$dataval['email']:'';
    $max_time     = !empty($dataval['max_time'])?$dataval['max_time']:'';
    $distance_val = !empty($dataval['distance_val'])?$dataval['distance_val']:'';
    $stock_status = !empty($dataval['stock_status'])?$dataval['stock_status']:'';
    $att_status   = !empty($dataval['attendance_status'])?$dataval['attendance_status']:'';
    $data_display = ($att_status == 1)?'contents':'hide';
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
                            <!-- <li class="breadcrumb-item"><a href="#"><?php echo $main_heading; ?></a>
                            </li> -->
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
                                                        <label for="projectinput1">Application Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="username" class="form-control username" placeholder="Application Name" name="username" value="<?php echo $username; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Username <span class="text-danger">*</span></label>
                                                        <input type="text" id="email" class="form-control email" placeholder="Username" name="email" value="<?php echo $email; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Mobile Number </label>
                                                        <input type="text" id="mobile" class="form-control mobile" placeholder="Mobile Number" name="mobile" value="<?php echo $mobile; ?>">
                                                    </div>
                                                </div>
                                                <?php if($this->session->userdata('permission') == 1): ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Stock status <span class="text-danger">*</span></label>
                                                        <select class="form-control stock_status js-select1-multi" id="stock_status" name="stock_status">
                                                            <option value="">Select Value</option>
                                                            <option value="1" <?php echo $stock_status == 1 ? 'selected' : ''; ?>>Enable</option>
                                                            <option value="2" <?php echo $stock_status == 2 ? 'selected' : ''; ?>>Disable</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Attendance status <span class="text-danger">*</span></label>
                                                        <select class="form-control attendance_status js-select1-multi" id="attendance_status" name="attendance_status">
                                                            <option value="">Select Value</option>
                                                            <option value="1" <?php echo $att_status == 1 ? 'selected' : ''; ?>>Enable</option>
                                                            <option value="2" <?php echo $att_status == 2 ? 'selected' : ''; ?>>Disable</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Maximum travel time <span class="text-danger required <?php echo $data_display; ?>">*</span></label>
                                                        <input type="text" id="max_time" class="form-control max_time int_value" placeholder="Enter in minute" name="max_time" value="<?php echo $max_time; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" oninput="this.value=this.value.replace(/^0+/g, '')" maxlength="2">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Distance value <span class="text-danger">*</span></label>
                                                        <input type="text" id="distance_val" class="form-control distance_val int_value" placeholder="Enter in meters" name="distance_val" value="<?php echo $distance_val; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" oninput="this.value=this.value.replace(/^0+/g, '')" maxlength="4">
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="user_id" id="user_id" class="user_id" value="<?php echo $user_id; ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="settings">
                                            <input type="hidden" name="func" id="func" class="func" value="profile_settings">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" class="method" value="<?php echo $method;?>">
                                            <button type="submit" class="btn btn-primary data_submit" data-type="_s_c">
                                                <span class="first_btn show"><i class="fa fa-check-square-o"></i> <?php echo $page_title; ?></span>

                                                <span class="span_btn hide"><i class="fa fa-spinner spinner"></i> Loading....</span>
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
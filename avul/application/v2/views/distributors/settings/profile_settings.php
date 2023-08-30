<?php
    $user_id      = !empty($dataval['user_id'])?$dataval['user_id']:'';
    $company_name = !empty($dataval['company_name'])?$dataval['company_name']:'';
    $mobile       = !empty($dataval['mobile'])?$dataval['mobile']:'';
    $email        = !empty($dataval['email'])?$dataval['email']:'';
    $account_name = !empty($dataval['account_name'])?$dataval['account_name']:'';
    $account_no   = !empty($dataval['account_no'])?$dataval['account_no']:'';
    $account_type = !empty($dataval['account_type'])?$dataval['account_type']:'';
    $ifsc_code    = !empty($dataval['ifsc_code'])?$dataval['ifsc_code']:'';
    $bank_name    = !empty($dataval['bank_name'])?$dataval['bank_name']:'';
    $branch_name  = !empty($dataval['branch_name'])?$dataval['branch_name']:'';
    $address      = !empty($dataval['address'])?$dataval['address']:'';
    $stock_status = !empty($dataval['stock_status'])?$dataval['stock_status']:'';
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
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Company Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="company_name" class="form-control company_name" placeholder="Company Name" name="company_name" value="<?php echo $company_name; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Username <span class="text-danger">*</span></label>
                                                        <input type="text" id="email" class="form-control email" placeholder="Username" name="email" value="<?php echo $email; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Contact Number <span class="text-danger">*</span></label>
                                                        <input type="text" id="mobile" class="form-control mobile" placeholder="Contact Number" name="mobile" value="<?php echo $mobile; ?>">
                                                    </div>
                                                </div>
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

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Account name </label>
                                                        <input type="text" id="account_name" class="form-control account_name" placeholder="Account name" name="account_name" value="<?php echo $account_name; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Account no </label>
                                                        <input type="text" id="account_no" class="form-control account_no" placeholder="Account no" name="account_no" value="<?php echo $account_no; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Account Type</label>
                                                        <select class="form-control account_type js-select1-multi" id="account_type" name="account_type">
                                                            <option value="">Select Value</option>
                                                            <option value="1" <?php echo $account_type == 1 ? 'selected' : ''; ?>>Current Account</option>
                                                            <option value="2" <?php echo $account_type == 2 ? 'selected' : ''; ?>>Salary account</option>
                                                            <option value="3" <?php echo $account_type == 3 ? 'selected' : ''; ?>>Fixed deposit account</option>
                                                            <option value="4" <?php echo $account_type == 4 ? 'selected' : ''; ?>>Recurring deposit account</option>
                                                            <option value="5" <?php echo $account_type == 5 ? 'selected' : ''; ?>>NRI account</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">IFSC code </label>
                                                        <input type="text" id="ifsc_code" class="form-control ifsc_code" placeholder="IFSC code" name="ifsc_code" value="<?php echo $ifsc_code; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Bank name </label>
                                                        <input type="text" id="bank_name" class="form-control bank_name" placeholder="Bank name" name="bank_name" value="<?php echo $bank_name; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Branch name </label>
                                                        <input type="text" id="branch_name" class="form-control branch_name" placeholder="Branch name" name="branch_name" value="<?php echo $branch_name; ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Address <span class="text-danger">*</span></label>
                                                        <textarea id="address" class="form-control address" placeholder="Address" name="address" rows="3"><?php echo $address; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="user_id" id="user_id" class="user_id" value="<?php echo $user_id; ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="distributors">
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
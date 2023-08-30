<?php
    $vendor_id      = !empty($dataval[0]['vendor_id'])?$dataval[0]['vendor_id']:'0';
    $company_name   = !empty($dataval[0]['company_name'])?$dataval[0]['company_name']:'';
    $contact_name   = !empty($dataval[0]['contact_name'])?$dataval[0]['contact_name']:'';
    $vendor_no      = !empty($dataval[0]['vendor_no'])?$dataval[0]['vendor_no']:'';
    $contact_no     = !empty($dataval[0]['contact_no'])?$dataval[0]['contact_no']:'';
    $email          = !empty($dataval[0]['email'])?$dataval[0]['email']:'';
    $gst_no         = !empty($dataval[0]['gst_no'])?$dataval[0]['gst_no']:'';
    $vendor_type    = !empty($dataval[0]['vendor_type'])?$dataval[0]['vendor_type']:'';
    $due_days       = !empty($dataval[0]['due_days'])?$dataval[0]['due_days']:'';
    $account_name   = !empty($dataval[0]['account_name'])?$dataval[0]['account_name']:'';
    $account_no     = !empty($dataval[0]['account_no'])?$dataval[0]['account_no']:'';
    $account_type   = !empty($dataval[0]['account_type'])?$dataval[0]['account_type']:'';
    $ifsc_code      = !empty($dataval[0]['ifsc_code'])?$dataval[0]['ifsc_code']:'';
    $bank_name      = !empty($dataval[0]['bank_name'])?$dataval[0]['bank_name']:'';
    $branch_name    = !empty($dataval[0]['branch_name'])?$dataval[0]['branch_name']:'';
    $password       = !empty($dataval[0]['password'])?$dataval[0]['password']:'';
    $state_value    = !empty($dataval[0]['state_id'])?$dataval[0]['state_id']:'';
    $city_value     = !empty($dataval[0]['city_id'])?$dataval[0]['city_id']:'';
    $purchase_type  = !empty($dataval[0]['purchase_type'])?$dataval[0]['purchase_type']:'';
    $msme_type      = !empty($dataval[0]['msme_type'])?$dataval[0]['msme_type']:'';
    $msme_file      = !empty($dataval[0]['msme_file'])?$dataval[0]['msme_file']:'';
    $address        = !empty($dataval[0]['address'])?$dataval[0]['address']:'';
    $distributor_id = !empty($dataval[0]['distributor_id'])?$dataval[0]['distributor_id']:'';
    $status         = !empty($dataval[0]['status'])?$dataval[0]['status']:'0';

    // Certificate input box
    $view_type = 'hide';
    if($msme_type == 1)
    {
        $view_type = 'show';
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
                                    <form class="form_data" name="form_data" method="post">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Company Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="company_name" class="form-control company_name" placeholder="Company Name" name="company_name" value="<?php echo $company_name; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Contact Name</label>
                                                        <input type="text" id="contact_name" class="form-control contact_name" placeholder="Contact Name" name="contact_name" value="<?php echo $contact_name ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Contact Number <span class="text-danger">*</span></label>
                                                        <input type="text" id="contact_no" class="form-control contact_no" placeholder="Contact Number" name="contact_no" value="<?php echo $contact_no; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Email <span class="text-danger">*</span></label>
                                                        <input type="text" id="email" class="form-control email" placeholder="Email" name="email" value="<?php echo $email ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">GST No <span class="text-danger">*</span></label>
                                                        <input type="text" id="gst_no" class="form-control gst_no" placeholder="GST No" name="gst_no" value="<?php echo $gst_no ?>">
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Vendor Type <span class="text-danger">*</span></label>
                                                        <select class="form-control vendor_type js-select1-multi" id="vendor_type" name="vendor_type">
                                                            <option value="">Select Value</option>
                                                            <?php
                                                                if($method == 'BTBM_X_U')
                                                                {
                                                                    if($vendor_type == 1)
                                                                    {
                                                                        ?>
                                                                            <option value="1" <?php echo $vendor_type == 1 ? 'selected' : ''; ?>>Manufacturer</option>
                                                                        <?php
                                                                    }
                                                                    else
                                                                    {
                                                                        ?>
                                                                            <option value="1" <?php echo $vendor_type == 2 ? 'selected' : ''; ?>>Both</option>
                                                                        <?php
                                                                    }
                                                                }
                                                                else
                                                                {
                                                                    ?>
                                                                        <option value="1" <?php echo $vendor_type == 1 ? 'selected' : ''; ?>>Manufacturer</option>
                                                                        <option value="2" <?php echo $vendor_type == 2 ? 'selected' : ''; ?>>Both</option>
                                                                    <?php
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div> -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Due Days</label>
                                                        <input type="text" id="due_days" class="form-control due_days int_value" placeholder="Due Days" name="due_days" value="<?php echo $due_days ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Account Holder Name</label>
                                                        <input type="text" id="account_name" class="form-control account_name" placeholder="Account Holder Name" name="account_name" value="<?php echo $account_name; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Account Number</label>
                                                        <input type="text" id="account_no" class="form-control account_no" placeholder="Account Number" name="account_no" value="<?php echo $account_no; ?>">
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
                                                            <option value="6" <?php echo $account_type == 6 ? 'selected' : ''; ?>>Savings account</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">IFSC Code</label>
                                                        <input type="text" id="ifsc_code" class="form-control ifsc_code" placeholder="IFSC Code" name="ifsc_code" value="<?php echo $ifsc_code; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Bank Name</label>
                                                        <input type="text" id="bank_name" class="form-control bank_name" placeholder="Bank Name" name="bank_name" value="<?php echo $bank_name; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Branch Name</label>
                                                        <input type="text" id="branch_name" class="form-control branch_name" placeholder="Branch Name" name="branch_name" value="<?php echo $branch_name; ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Password <span class="text-danger">*</span></label>
                                                        <input type="password" id="password" class="form-control password" placeholder="Password" name="password" value="<?php echo $password; ?>">
                                                    </div>
                                                </div>
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

                                                                        if($city_value == $city_id)
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
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Purchase Type <span class="text-danger">*</span></label>

                                                        <select class="form-control purchase_type js-select1-multi" id="purchase_type" name="purchase_type">   
                                                            <option value="">Select City Name</option>
                                                            <option value="1" <?php echo $purchase_type == 1 ? 'selected' : ''; ?>>Group</option>
                                                            <option value="2" <?php echo $purchase_type == 2 ? 'selected' : ''; ?>>Others</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">MSME Type <span class="text-danger">*</span></label>

                                                        <select class="form-control msme_type js-select1-multi" id="msme_type" name="msme_type">   
                                                            <option value="">Select City Name</option>
                                                            <option value="1" <?php echo $msme_type == 1 ? 'selected' : ''; ?>>Yes</option>
                                                            <option value="2" <?php echo $msme_type == 2 ? 'selected' : ''; ?>>No</option>
                                                            
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group getCertificate <?php echo $view_type; ?>">
                                                        <label for="projectinput1">MSME Certificate <span class="text-danger">*</span></label>

                                                        <input type="file" id="msme_certificate" class="form-control msme_certificate" placeholder="MSME Certificate" name="msme_certificate" value="" style="margin-bottom: 10px;">
                                                        <code for="projectinput1"><?php echo $msme_file; ?></code>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Address <span class="text-danger">*</span></label>
                                                        <textarea id="address" class="form-control address" placeholder="Address" name="address" rows="3"><?php echo $address; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                                if($method == 'BTBM_X_U')
                                                {
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>Status <span class="text-danger">*</span></label><br>
                                                                <div class="custom-control custom-radio custom-control-inline">
                                                                    <input type="radio" id="postType1" name="pstatus" class="custom-control-input" <?php echo $status==1 ? 'checked' : ''; ?> value="1">
                                                                    <label class="custom-control-label" for="postType1">Active</label>
                                                                </div>
                                                                <div class="custom-control custom-radio custom-control-inline">
                                                                    <input type="radio" id="postType2" name="pstatus" class="custom-control-input" <?php echo $status==0 ? 'checked' : ''; ?> value="0">
                                                                    <label class="custom-control-label" for="postType2">In Active </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                        <div class="form-actions">
                                            <input type="hidden" name="log_id" id="log_id" class="log_id" value="<?php echo $this->session->userdata('id'); ?>">
                                            <input type="hidden" name="log_role" id="log_role" class="log_role" value="<?php echo $this->session->userdata('user_role'); ?>">

                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="vendor_id" id="vendor_id" class="vendor_id" value="<?php echo $vendor_id; ?>">
                                            <input type="hidden" name="vendor_type" id="vendor_type" class="vendor_type" value="1">
                                            <input type="hidden" name="vendor_no" id="vendor_no" class="vendor_no" value="<?php echo $vendor_no; ?>">
                                            <input type="hidden" name="distributor_id" id="distributor_id" class="distributor_id" value="<?php echo $distributor_id; ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="vendors">
                                            <input type="hidden" name="func" id="func" class="func" value="add_vendor">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" class="method" value="<?php echo $api_method;?>">
                                            <input type="hidden" name="submit_url" id="submit_url" class="submit_url" value="vendors/api/vendors">
                                            <button type="submit" class="btn btn-primary form_submit" data-type="_s_c">
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
<?php 

    if ($this->session->userdata('random_value') == '')
    redirect(base_url() . 'index.php?login', 'refresh');
    
    $ad_id =$this->session->userdata('id');

    $distributor_id = !empty($dataval[0]['distributor_id'])?$dataval[0]['distributor_id']:'0';
    $dis_code       = !empty($dataval[0]['dis_code'])?$dataval[0]['dis_code']:'';
    $company_name   = !empty($dataval[0]['company_name'])?$dataval[0]['company_name']:'';
    $contact_name   = !empty($dataval[0]['contact_name'])?$dataval[0]['contact_name']:'';
    $mobile         = !empty($dataval[0]['mobile'])?$dataval[0]['mobile']:'';
    $email          = !empty($dataval[0]['email'])?$dataval[0]['email']:'';
    $gst_no         = !empty($dataval[0]['gst_no'])?$dataval[0]['gst_no']:'';
    $pan_no         = !empty($dataval[0]['pan_no'])?$dataval[0]['pan_no']:'';
    $tan_no         = !empty($dataval[0]['tan_no'])?$dataval[0]['tan_no']:'';
    $bill_no        = !empty($dataval[0]['bill_no'])?$dataval[0]['bill_no']:'INV';
    $due_days       = !empty($dataval[0]['due_days'])?$dataval[0]['due_days']:'';
    $dis_status     = !empty($dataval[0]['distributor_status'])?$dataval[0]['distributor_status']:'';
    $discount       = !empty($dataval[0]['discount'])?$dataval[0]['discount']:'';
    $credit_limit   = !empty($dataval[0]['credit_limit'])?$dataval[0]['credit_limit']:'0';
    $account_name   = !empty($dataval[0]['account_name'])?$dataval[0]['account_name']:'';
    $account_no     = !empty($dataval[0]['account_no'])?$dataval[0]['account_no']:'';
    $account_type   = !empty($dataval[0]['account_type'])?$dataval[0]['account_type']:'';
    $ifsc_code      = !empty($dataval[0]['ifsc_code'])?$dataval[0]['ifsc_code']:'';
    $bank_name      = !empty($dataval[0]['bank_name'])?$dataval[0]['bank_name']:'';
    $branch_name    = !empty($dataval[0]['branch_name'])?$dataval[0]['branch_name']:'';
    $password       = !empty($dataval[0]['password'])?$dataval[0]['password']:'';
    $pincode        = !empty($dataval[0]['pincode'])?$dataval[0]['pincode']:''; 
    $state_value    = !empty($dataval[0]['state_id'])?$dataval[0]['state_id']:''; 
    $category_value = !empty($dataval[0]['category_id'])?$dataval[0]['category_id']:'';
    $sub_cat_id     = !empty($dataval[0]['sub_cat_id'])?$dataval[0]['sub_cat_id']:'';
    $type_value     = !empty($dataval[0]['type_id'])?$dataval[0]['type_id']:'';    
    $address        = !empty($dataval[0]['address'])?$dataval[0]['address']:'';
    $status         = !empty($dataval[0]['status'])?$dataval[0]['status']:'0';
    $dis_grade      = !empty($dataval[0]['grade'])?$dataval[0]['grade']:'';
    $gst_image      = !empty($dataval[0]['gst_image'])?$dataval[0]['gst_image']:'';
    $cheque_img     = !empty($dataval[0]['cheque_img'])?$dataval[0]['cheque_img']:'';
    $qr_image       = !empty($dataval[0]['qr_image'])?$dataval[0]['qr_image']:'';
    $logo_image     = !empty($dataval[0]['logo_image'])?$dataval[0]['logo_image']:'';

    $einv_status    = !empty($dataval[0]['einv_status'])?$dataval[0]['einv_status']:'0';
    $access_token   = !empty($dataval[0]['access_token'])?$dataval[0]['access_token']:'';

    $msme_status    = !empty($dataval[0]['msme_status'])?$dataval[0]['msme_status']:'0';
    $msme_number    = !empty($dataval[0]['msme_number'])?$dataval[0]['msme_number']:'';

    $einv_result    = ($einv_status == 1)?'show':'hide';
    $msme_result    = ($msme_status == 1)?'show':'hide';

    $distributor_type =1;
    if(!empty($gst_image))
    {
        $img_gst = FILE_URL.'distributor/gst/'.$gst_image;
    }
    else
    {
        $img_gst = BASE_URL.'app-assets/images/img_icon.png';
    }
   
    if(!empty($cheque_img))
    {
        $img_cheque = FILE_URL.'distributor/cheque/'.$cheque_img;
    }
    else
    {
        $img_cheque = BASE_URL.'app-assets/images/img_icon.png';
    }

    if(!empty($qr_image))
    {
        $img_qr = FILE_URL.'distributor/qr_code/'.$qr_image;
    }
    else
    {
        $img_qr = BASE_URL.'app-assets/images/img_icon.png';
    }

    if(!empty($logo_image))
    {
        $img_logo = FILE_URL.'distributor/logo/'.$logo_image;
    }
    else
    {
        $img_logo = BASE_URL.'app-assets/images/img_icon.png';
    }
   // print_r($dis_grade );exit;
?>
<style type="text/css"> 
.error {
  color: red;
  font-size: 14px;
  font-weight: bold;
}

            .field-icon {
                float: right;
                margin-top: -40px;
                position: relative;
                z-index: 2;
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
                                <form class="form_data" name="form_data" method="post">
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
                                                        <label for="projectinput1">Contact Person</label>
                                                        <input type="text" id="contact_name" class="form-control contact_name" placeholder="Contact Person" name="contact_name" value="<?php echo $contact_name; ?>" oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ')">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Contact Number <span class="text-danger">*</span></label>
                                                        <input type="text" id="mobile" class="form-control mobile int_value" placeholder="Contact Number" name="mobile" value="<?php echo $mobile; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="10">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Email <span class="text-danger">*</span></label>
                                                        <input type="text" id="email" class="form-control email" placeholder="Email" name="email" value="<?php echo $email; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">GST No <span class="text-danger">*</span></label>
                                                        <input type="text" id="gst_no" class="form-control" placeholder="GST Number" name="gst_no" value="<?php echo $gst_no; ?>" maxlength="15">

                                                        <input type="hidden" name="pan_no" id="pan_no" class="pan_no" value="">
                                                        <input type="hidden" name="tan_no" id="tan_no" class="tan_no" value="0">
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">PAN Number</label>
                                                        <input type="text" id="pan_no" class="form-control pan_no" placeholder="PAN Number" name="pan_no" value="<?php echo $pan_no; ?>" maxlength="10">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">TAN Number</label>
                                                        <input type="text" id="tan_no" class="form-control tan_no" placeholder="TAN Number" name="tan_no" value="<?php echo $tan_no; ?>" maxlength="10">
                                                    </div>
                                                </div> -->
                                                <!-- <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Bill Number</label>
                                                        <input type="text" id="bill_no" class="form-control bill_no" placeholder="Bill Number" name="bill_no" value="<?php echo $bill_no; ?>">
                                                    </div>
                                                </div> -->
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Password <span class="text-danger">*</span></label>
                                                        <input type="password" id="password" class="form-control password" placeholder="Password" name="password" value="<?php echo $password; ?>">
                                                        <div class="form-control-position field-icon">
                                                        <i class="toggle-password la la-eye-slash" toggle="#password"></i>
                                                    </div>
                                                        <span id="password-error" class ="error" color="red"></span>
                                                    </div>
                                                    </div>
                                              
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Credit Limit <span class="text-danger">*</span></label>
                                                        <input type="text" id="credit_limit" class="form-control credit_limit int_value" placeholder="Credit Limit" name="credit_limit" value="<?php echo $credit_limit; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Discount</label>
                                                        <input type="text" id="discount" class="form-control discount int_value" placeholder="Discount" name="discount" maxlength="2" value="<?php echo $discount; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Due Days</label>
                                                        <input type="text" id="due_days" class="form-control due_days int_value" placeholder="Due Days" name="due_days" value="<?php echo $due_days; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Invoice Prefix <span class="text-danger">*</span></label>
                                                        <input type="text" id="dis_code" class="form-control" placeholder="Invoice Prefix" name="dis_code" maxlength="6" value="<?php echo $dis_code; ?>">
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Distributor Status <span class="text-danger">*</span></label>
                                                        <select class="form-control distributor_status js-select1-multi" id="distributor_status" name="distributor_status">
                                                            <option value="">Select Value</option>
                                                            <option value="1" <?php echo $dis_status == 1 ? 'selected' : ''; ?>>Others</option>
                                                            <option value="2" <?php echo $dis_status == 2 ? 'selected' : ''; ?>>Local</option>
                                                        </select>

                                                    </div>
                                                </div> -->
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">State Name <span class="text-danger">*</span></label>
                                                        <select class="form-control state_id js-select1-multi" id="state_id" name="state_id" style="width: 100%;">
                                                            <option value="">Select Category Name</option>
                                                            <?php
                                                                if (!empty($state_val))
                                                                {
                                                                    foreach ($state_val as $key => $value_1)
                                                                    {
                                                                        $state_id   = !empty($value_1['state_id'])?$value_1['state_id']:'';
                                                                        $state_name = !empty($value_1['state_name'])?$value_1['state_name']:'';

                                                                        $select = '';
                                                                        if($state_id == $state_value)
                                                                        {
                                                                            $select = 'selected';
                                                                        }

                                                                        echo '<option value="'.$state_id.'" '.$select.'>'.$state_name.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Pincode <span class="text-danger">*</span></label>
                                                        <input type="text" id="pincode" class="form-control pincode int_value" placeholder="Pincode" name="pincode" value="<?php echo $pincode; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Account Holder Name</label>
                                                        <input type="text" id="account_name" class="form-control account_name" placeholder="Account Holder Name" name="account_name" value="<?php echo $account_name; ?>" maxlength="30" oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ')">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Account Number</label>
                                                        <input type="text" id="account_no" class="form-control account_no" placeholder="Account Number" name="account_no" value="<?php echo $account_no; ?>" maxlength="17">
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
                                                        <input type="text" id="bank_name" class="form-control bank_name" placeholder="Bank Name" name="bank_name" value="<?php echo $bank_name; ?>" maxlength="50" oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ')">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Branch Name</label>
                                                        <input type="text" id="branch_name" class="form-control branch_name" placeholder="Branch Name" name="branch_name" value="<?php echo $branch_name; ?>" maxlength="30" oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ')"  >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">E-invoice bill status <span class="text-danger">*</span></label>
                                                        <select class="form-control einv_status js-select1-multi" id="einv_status" name="einv_status">
                                                            <option value="">Select Value</option>
                                                            <option value="1" <?php echo $einv_status == 1 ? 'selected' : ''; ?>>Enable</option>
                                                            <option value="2" <?php echo $einv_status == 2 ? 'selected' : ''; ?>>Disable</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 getAccessCode <?php echo $einv_result; ?>">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Access code <span class="text-danger">*</span></label>
                                                        <input type="text" id="access_token" class="form-control access_token" placeholder="Access code" name="access_token" value="<?php echo $access_token; ?>" >
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">MSME status <span class="text-danger">*</span></label>
                                                        <select class="form-control msme_status js-select1-multi" id="msme_status" name="msme_status">
                                                            <option value="">Select Value</option>
                                                            <option value="1" <?php echo $msme_status == 1 ? 'selected' : ''; ?>>Enable</option>
                                                            <option value="2" <?php echo $msme_status == 2 ? 'selected' : ''; ?>>Disable</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 getMsmeNumber <?php echo $msme_result; ?>">
                                                    <div class="form-group">
                                                        <label for="projectinput1">MSME Number <span class="text-danger">*</span></label>
                                                        <input type="text" id="msme_number" class="form-control msme_number" placeholder="MSME Number" name="msme_number" value="<?php echo $msme_number; ?>" >
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
                                            <!-- <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Category Name <span class="text-danger">*</span></label>
                                                        <select class="form-control category_id js-select1-multi" id="category_id" name="category_id[]" multiple="multiple" style="width: 100%;">
                                                            <?php
                                                                if(!empty($category_val))
                                                                {
                                                                    foreach ($category_val as $key => $value) {

                                                                        $category_id   = !empty($value['category_id'])?$value['category_id']:'';
                                                                        $category_name = !empty($value['category_name'])?$value['category_name']:'';

                                                                        $select   = '';
                                                                        $category_res = explode(',', $category_value);
                                                                        if(in_array($category_id, $category_res))
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
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Sub Category Name <span class="text-danger">*</span></label>
                                                        <select class="form-control sub_cat_id js-select1-multi" id="sub_cat_id" name="sub_cat_id[]" multiple="multiple" style="width: 100%;">
                                                            <?php
                                                                if(!empty($sub_val))
                                                                {
                                                                    foreach ($sub_val as $key => $value) {

                                                                        $id     = !empty($value['id'])?$value['id']:'';
                                                                        $sub_cat_name = !empty($value['sub_cat_name'])?$value['sub_cat_name']:'';
                                                                        
                                                                        $select   = '';
                                                                        $sub_cat_res = explode(',', $sub_cat_id);
                                                                        if(in_array($id, $sub_cat_res))
                                                                        {
                                                                            $select = 'selected';
                                                                        }

                                                                        echo "<option value=".$id." ".$select.">".$sub_cat_name."</option>";
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                        <input style="margin-top: 10px;" type="checkbox" id="check_sub"> Select all sub category
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Product Name <span class="text-danger">*</span></label>
                                                        <select class="form-control type_id js-select1-multi" id="type_id" name="type_id[]" multiple="multiple" style="width: 100%;">
                                                            <?php
                                                                if(!empty($type_val))
                                                                {
                                                                    foreach ($type_val as $key => $value) {

                                                                        $type_id     = !empty($value['type_id'])?$value['type_id']:'';
                                                                        $description = !empty($value['description'])?$value['description']:'';
                                                                        $product_id  = !empty($value['product_id'])?$value['product_id']:'';

                                                                        $select   = '';
                                                                        $type_res = explode(',', $type_value);
                                                                        if(in_array($type_id, $type_res))
                                                                        {
                                                                            $select = 'selected';
                                                                        }

                                                                        echo "<option value=".$type_id." ".$select.">".$description."</option>";
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                        <input style="margin-top: 10px;" type="checkbox" id="check_type"> Select all product
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Distributor Type <span class="text-danger">*</span></label><br><br>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="distributor_grade1" name="distributor_grade" class="custom-control-input" <?php echo $dis_grade==1 ? 'checked' : ''; ?> value="1">
                                                            <label class="custom-control-label" for="distributor_grade1">Advanced Distributor</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="distributor_grade2" name="distributor_grade" class="custom-control-input" <?php echo $dis_grade==2 ? 'checked' : ''; ?> value="2">
                                                            <label class="custom-control-label" for="distributor_grade2">Normal Distributor </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class='row'>
                                                <div class="col-md-3">
                                                    <label for="projectinput1"><b>GST Image</b> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="projectinput1"><b>Cheque Image</b> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="projectinput1"><b>Distributor Logo</b></label>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="projectinput1"><b>Payment QR Code</b></label>
                                                </div>
                                            </div>
                                            <div class='row'>
                                                <div class="col-md-3">
                                                    <div class="mb-1">
                                                        <div class="d-flex">
                                                        
                                                            <a href="#" class="me-25">
                                                            <img src="<?php echo $img_gst; ?>" id="gst-upload-img" class="uploadedGst rounded me-50" alt="profile image" height="100" width="100"/>
                                                            </a>
                                                            <div class="d-flex align-items-end mt-75 ms-1">
                                                                <div>
                                                                    <label for="gst-upload" class="btn btn-sm btn-primary mb-75 me-75 file_upload">Upload</label>
                                                                    <input type="file" id="gst-upload" name="gst_image" class="" hidden accept="image/*" />
                                                                    <p class="mb-0">Allowed file types: png, jpg, jpeg.<br> Size : 1920 X 800</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-1">
                                                        <div class="d-flex">
                                                    
                                                            <a href="#" class="me-25">
                                                            <img src="<?php echo $img_cheque; ?>" id="account-upload-img" class="uploadedAvatar rounded me-50" alt="profile image" height="100" width="100"/>
                                                            </a>
                                                            <div class="d-flex align-items-end mt-75 ms-1">
                                                                <div>
                                                                    <label for="account-upload" class="btn btn-sm btn-primary mb-75 me-75 file_upload">Upload</label>
                                                                    <input type="file" id="account-upload" name="cheque_image" class="" hidden accept="image/*" />
                                                                    <p class="mb-0">Allowed file types: png, jpg, jpeg.<br> Size : 1920 X 800</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-1">
                                                        <div class="d-flex">
                                                            <a href="#" class="me-25">
                                                            <img src="<?php echo $img_logo; ?>" id="upload_logo_img" class="uploadedLogo rounded me-50" alt="profile image" height="100" width="100"/>
                                                            </a>
                                                            <div class="d-flex align-items-end mt-75 ms-1">
                                                                <div>
                                                                    <label for="upload_logo" class="btn btn-sm btn-primary mb-75 me-75 file_upload">Upload</label>
                                                                    <input type="file" id="upload_logo" name="logo_image" class="" hidden accept="image/*" />
                                                                    <p class="mb-0">Allowed file types: png, jpg, jpeg.<br> Size : 1920 X 800</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-1">
                                                        <div class="d-flex">
                                                            <a href="#" class="me-25">
                                                            <img src="<?php echo $img_qr; ?>" id="upload_qr_img" class="uploadedQr rounded me-50" alt="profile image" height="100" width="100"/>
                                                            </a>
                                                            <div class="d-flex align-items-end mt-75 ms-1">
                                                                <div>
                                                                    <label for="upload_qr" class="btn btn-sm btn-primary mb-75 me-75 file_upload">Upload</label>
                                                                    <input type="file" id="upload_qr" name="qr_image" class="" hidden accept="image/*" />
                                                                    <p class="mb-0">Allowed file types: png, jpg, jpeg.<br> Size : 1920 X 800</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                          
                                            </div>
                                            <?php
                                                if($method == '_updateDistributors')
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
                                              <div class="form-actions">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="distributor_id" id="distributor_id" class="distributor_id" value="<?php echo $distributor_id; ?>">
                                            <input type="hidden" name="ref_by" id="ref_by" class="ref_by" value="0">
                                            <input type="hidden" name="distributor_grade" id="distributor_grade" class="distributor_grade" value="2">
                                            <input type="hidden" name="ad_id" id="ad_id" class="ad_id" value="<?php echo $ad_id ?>">
                                            <input type="hidden" id="distributor_status" class="form-control" placeholder="Distributor Code" name="distributor_status" maxlength="6" value="1">
                                            <input type="hidden" name="distributor_type" id="distributor_type" class="distributor_type" value="<?php echo $distributor_type ?>">
                                            <input type="hidden" name="mg_role" id="mg_role" class="mg_role" value="ADMIN">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="distributors">
                                            <input type="hidden" name="func" id="func" class="func" value="add_distributors">
                                            <input type="hidden" name="submit_url" class="submit_url" id="submit_url" value="<?php echo $submit_url ?>">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" class="method" value="<?php echo $method;?>">
                                            <button type="submit" class="btn btn-primary form_submit" data-type="_s_c">
                                                <span class="first_btn show"><i class="la la-check-square-o"></i> <?php echo $page_title; ?></span>

                                                <span class="span_btn hide"><i class="la la-spinner spinner"></i> Loading....</span>
                                            </button>
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
    </div>
</div>
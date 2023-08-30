<?php

            if ($this->session->userdata('random_value') == '')
        	redirect(base_url() . 'index.php?login', 'refresh');
            $user_role =$this->session->userdata('designation_code');
            $user_id =$this->session->userdata('id');



    $outlets_id     = !empty($dataval[0]['outlets_id'])?$dataval[0]['outlets_id']:'0';
    $company_name   = !empty($dataval[0]['company_name'])?$dataval[0]['company_name']:'';
    $contact_name   = !empty($dataval[0]['contact_name'])?$dataval[0]['contact_name']:'';
    $mobile         = !empty($dataval[0]['mobile'])?$dataval[0]['mobile']:'';
    $email          = !empty($dataval[0]['email'])?$dataval[0]['email']:'';
    $gst_type       = !empty($dataval[0]['gst_type'])?$dataval[0]['gst_type']:'';
    $gst_no         = !empty($dataval[0]['gst_no'])?$dataval[0]['gst_no']:'';
    $pan_no         = !empty($dataval[0]['pan_no'])?$dataval[0]['pan_no']:'';
    $tan_no         = !empty($dataval[0]['tan_no'])?$dataval[0]['tan_no']:'';
    $discount       = !empty($dataval[0]['discount'])?$dataval[0]['discount']:'';
    $due_days       = !empty($dataval[0]['due_days'])?$dataval[0]['due_days']:'';
    $pincode        = !empty($dataval[0]['pincode'])?$dataval[0]['pincode']:'';

    $account_name   = !empty($dataval[0]['account_name'])?$dataval[0]['account_name']:'';
    $account_no     = !empty($dataval[0]['account_no'])?$dataval[0]['account_no']:'';
    $account_type   = !empty($dataval[0]['account_type'])?$dataval[0]['account_type']:'';
    $ifsc_code      = !empty($dataval[0]['ifsc_code'])?$dataval[0]['ifsc_code']:'';
    $bank_name      = !empty($dataval[0]['bank_name'])?$dataval[0]['bank_name']:'';
    $branch_name    = !empty($dataval[0]['branch_name'])?$dataval[0]['branch_name']:'';
    $address        = !empty($dataval[0]['address'])?$dataval[0]['address']:'';
    $outlet_image   = !empty($dataval[0]['outlet_image'])?$dataval[0]['outlet_image']:'';
    $state_value    = !empty($dataval[0]['state_id'])?$dataval[0]['state_id']:'';
    $city_value     = !empty($dataval[0]['city_id'])?$dataval[0]['city_id']:'';
    $zone_value     = !empty($dataval[0]['zone_id'])?$dataval[0]['zone_id']:'';
    $outlet_type    = !empty($dataval[0]['outlet_type'])?$dataval[0]['outlet_type']:'';
    $latitude       = !empty($dataval[0]['latitude'])?$dataval[0]['latitude']:'11.024903';
    $longitude      = !empty($dataval[0]['longitude'])?$dataval[0]['longitude']:'77.0090556';
    $credit_limit   = !empty($dataval[0]['credit_limit'])?$dataval[0]['credit_limit']:'';
    $otp_type       = !empty($dataval[0]['otp_type'])?$dataval[0]['otp_type']:'';
    $sales_type     = !empty($dataval[0]['sales_type'])?$dataval[0]['sales_type']:'';
    $category_value = !empty($dataval[0]['outlet_category'])?$dataval[0]['outlet_category']:'';
    $status         = !empty($dataval[0]['status'])?$dataval[0]['status']:'0';

    $image_id='';
    if(!empty($outlet_image))
    {
        $img_value = FILE_URL.'outlet/'.$outlet_image;
    }
    else
    {
        $img_value = BASE_URL.'app-assets/images/img_icon.png';
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
                  
                    <a class="btn btn-info round px-2 mb-1" href="<?php echo BASE_URL.$pre_menu ?>"><i class="ft-plus-square"></i> <?php echo $pre_title; ?></a>
                    
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
                                                        <label for="projectinput1">Store Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="company_name" class="form-control company_name" placeholder="Store Name" name="company_name" value="<?php echo $company_name; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Contact Name</label>
                                                        <input type="text" id="contact_name" class="form-control contact_name" placeholder="Contact Name" name="contact_name" value="<?php echo $contact_name; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Contact No <span class="text-danger">*</span></label>
                                                        <input type="text" id="mobile" class="form-control mobile int_value" placeholder="Contact No" name="mobile" value="<?php echo $mobile; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Email</label>
                                                        <input type="text" id="email" class="form-control email" placeholder="Email" name="email" value="<?php echo $email; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">GSTIN type <span class="text-danger">*</span></label>
                                                        <select class="form-control gst_type js-select1-multi" id="gst_type" name="gst_type">
                                                            <option value="">Select Value</option>
                                                            <option value="1" <?php echo $gst_type == 1 ? 'selected' : ''; ?>>Unregistered</option>
                                                            <option value="2" <?php echo $gst_type == 2 ? 'selected' : ''; ?>>Registered</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">GSTIN No</label>
                                                        <input type="text" id="gst_no" class="form-control gst_no" placeholder="GSTIN No" name="gst_no" value="<?php echo $gst_no; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">PAN No</label>
                                                        <input type="text" id="pan_no" class="form-control pan_no" placeholder="PAN No" name="pan_no" value="<?php echo $pan_no; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">TAN No</label>
                                                        <input type="text" id="tan_no" class="form-control tan_no" placeholder="TAN No" name="tan_no" value="<?php echo $tan_no; ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Credit Limit</label>
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
                                                        <label for="projectinput1">Pincode</label>
                                                        <input type="text" id="pincode" class="form-control pincode int_value" placeholder="Pincode" name="pincode" value="<?php echo $pincode; ?>" maxlength="6">
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
                                                <div class="col-md-12">
                                                    <div class="box box-primary">
                                                        <div class="box-body">
                                                            <input id="searchInput" style="width: 70%; margin-top: 12px;" class="form-control" type="text" value="" placeholder="Enter a location"/><br/>
                                                            <div class="map" id="map" style="width: 100%; height: 350px; margin-bottom: 20px;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
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
                                                <div class="col-md-3">
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
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Beat Name <span class="text-danger">*</span></label>

                                                        <select class="form-control zone_id js-select1-multi" id="zone_id" name="zone_id">   
                                                            <option value="">Select Beat Name</option>
                                                            <?php
                                                                if(!empty($zone_val))
                                                                {
                                                                    foreach ($zone_val as $key => $value) {
                                                                        $zone_id   = !empty($value['zone_id'])?$value['zone_id']:'';
                                                                        $zone_name = !empty($value['zone_name'])?$value['zone_name']:'';

                                                                        if($zone_value == $zone_id)
                                                                        {
                                                                            $selected = 'selected';
                                                                        }
                                                                        else
                                                                        {
                                                                            $selected = '';
                                                                        }

                                                                        echo "<option value=".$zone_id." ".$selected.">".$zone_name."</option>";
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Outlet type <span class="text-danger">*</span></label>
                                                        <select class="form-control outlet_type js-select1-multi" id="outlet_type" name="outlet_type">
                                                            <option value="">Select Value</option>
                                                            <option value="1" <?php echo $outlet_type == 1 ? 'selected' : ''; ?>>Group</option>
                                                            <option value="2" <?php echo $outlet_type == 2 ? 'selected' : ''; ?>>Others</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Address <span class="text-danger">*</span></label>
                                                        <textarea id="plocation" class="form-control address" placeholder="Address" name="address" rows="3"><?php echo $address; ?></textarea>
                                                        <input class="form-control"  readonly="readonly" type="hidden" id="latitude" name="latitude" value="<?php echo $latitude; ?>">
                                                        <input class="form-control"  readonly="readonly" type="hidden" id="longitude" name="longitude" value="<?php echo $longitude; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">OTP Status <span class="text-danger">*</span></label>
                                                        <select class="form-control otp_type js-select1-multi" id="otp_type" name="otp_type">
                                                            <option value="">Select Value</option>
                                                            <option value="1" <?php echo $otp_type == 1 ? 'selected' : ''; ?>>Disable</option>
                                                            <option value="2" <?php echo $otp_type == 2 ? 'selected' : ''; ?>>Enable</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Sales Status <span class="text-danger">*</span></label>
                                                        <select class="form-control sales_type js-select1-multi" id="sales_type" name="sales_type">
                                                            <option value="">Select Value</option>
                                                            <option value="1" <?php echo $sales_type == 1 ? 'selected' : ''; ?>>Enable</option>
                                                            <option value="2" <?php echo $sales_type == 2 ? 'selected' : ''; ?>>Disable</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Outlet Category <span class="text-danger">*</span></label>

                                                        <select class="form-control outlet_category js-select1-multi" id="outlet_category" name="outlet_category">   
                                                            <option value="">Select Outlet Category</option>
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
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="mb-1">
                                                    <div class="d-flex">
                                                        <a href="#" class="me-25">
                                                        <img src="<?php echo $img_value; ?>" id="account-upload-img" class="uploadedAvatar rounded me-50" alt="profile image" height="100" width="100"/>
                                                        </a>
                                                        <div class="d-flex align-items-end mt-75 ms-1">
                                                            <div>
                                                                <label for="account-upload" class="btn btn-sm btn-primary mb-75 me-75 file_upload">Upload</label>
                                                                <input type="file" id="account-upload" name="outlet_image" class="" hidden accept="image/*" />
                                                                <p class="mb-0">Allowed file types: png, jpg, jpeg.<br> Size : 1920 X 800</p>
                                                            </div>
                                                        </div>
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
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="outlets_id" id="outlets_id" class="outlets_id" value="<?php echo $outlets_id; ?>">
                                            <input type="hidden" name="user_id" id="user_id" class="user_id" value="<?php echo $user_id; ?>">
                                            <input type="hidden" name="user_role" id="user_role" class="user_role" value="<?php echo $user_role; ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="outlets">
                                            <input type="hidden" name="func" id="func" class="func" value="add_outlets">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" class="method" value="<?php echo $method;?>">
                                            <input type="hidden" name="submit_url" class="submit_url" id="submit_url" value="<?php echo $submit_url ?>">
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
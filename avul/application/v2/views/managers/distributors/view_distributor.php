<?php 
    if ($this->session->userdata('random_value') == '')
    redirect(base_url() . 'index.php?login', 'refresh');
    $user_role =$this->session->userdata('designation_code');
    $user_id =$this->session->userdata('id');

    $distributor_id = !empty($purchase_data[0]['distributor_id'])?$purchase_data[0]['distributor_id']:'0';
    $dis_code       = !empty($purchase_data[0]['dis_code'])?$purchase_data[0]['dis_code']:'';
    $company_name   = !empty($purchase_data[0]['company_name'])?$purchase_data[0]['company_name']:'';
    $contact_name   = !empty($purchase_data[0]['contact_name'])?$purchase_data[0]['contact_name']:'';
    $mobile         = !empty($purchase_data[0]['mobile'])?$purchase_data[0]['mobile']:'';
    $email          = !empty($purchase_data[0]['email'])?$purchase_data[0]['email']:'';
    $gst_no         = !empty($purchase_data[0]['gst_no'])?$purchase_data[0]['gst_no']:'';
    $pan_no         = !empty($purchase_data[0]['pan_no'])?$purchase_data[0]['pan_no']:'';
    $tan_no         = !empty($purchase_data[0]['tan_no'])?$purchase_data[0]['tan_no']:'';
    $bill_no        = !empty($purchase_data[0]['bill_no'])?$purchase_data[0]['bill_no']:'INV';
    $due_days       = !empty($purchase_data[0]['due_days'])?$purchase_data[0]['due_days']:'';
    $dis_status     = !empty($purchase_data[0]['distributor_status'])?$purchase_data[0]['distributor_status']:'';
    $discount       = !empty($purchase_data[0]['discount'])?$purchase_data[0]['discount']:'';
    $credit_limit   = !empty($purchase_data[0]['credit_limit'])?$purchase_data[0]['credit_limit']:'0';
    $account_name   = !empty($purchase_data[0]['account_name'])?$purchase_data[0]['account_name']:'';
    $account_no     = !empty($purchase_data[0]['account_no'])?$purchase_data[0]['account_no']:'';
    $account_type   = !empty($purchase_data[0]['account_type'])?$purchase_data[0]['account_type']:'';
    $ifsc_code      = !empty($purchase_data[0]['ifsc_code'])?$purchase_data[0]['ifsc_code']:'';
    $bank_name      = !empty($purchase_data[0]['bank_name'])?$purchase_data[0]['bank_name']:'';
    $branch_name    = !empty($purchase_data[0]['branch_name'])?$purchase_data[0]['branch_name']:'';
    $password       = !empty($purchase_data[0]['password'])?$purchase_data[0]['password']:'';
    $pincode        = !empty($purchase_data[0]['pincode'])?$purchase_data[0]['pincode']:''; 
    $state_value    = !empty($purchase_data[0]['state_id'])?$purchase_data[0]['state_id']:'';
    $city_value     = !empty($purchase_data[0]['city_id'])?$purchase_data[0]['city_id']:''; 
    $category_value = !empty($purchase_data[0]['category_id'])?$purchase_data[0]['category_id']:'';
    $sub_cat_id     = !empty($purchase_data[0]['sub_cat_id'])?$purchase_data[0]['sub_cat_id']:'';
    $type_value     = !empty($purchase_data[0]['type_id'])?$purchase_data[0]['type_id']:'';    
    $address        = !empty($purchase_data[0]['address'])?$purchase_data[0]['address']:'';
    $status         = !empty($purchase_data[0]['status'])?$purchase_data[0]['status']:'0';
    $dis_grade      = !empty($purchase_data[0]['grade'])?$purchase_data[0]['grade']:'';
    $gst_image        = !empty($purchase_data[0]['gst_image'])?$purchase_data[0]['gst_image']:'';
    $cheque_img         = !empty($purchase_data[0]['cheque_img'])?$purchase_data[0]['cheque_img']:'';
   
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
             
                    <a class="btn btn-primary round px-2 mb-1" href="<?php echo BASE_URL.$pre_menu ?>"><i class="ft-plus-square"></i> <?php echo $pre_title; ?></a>
             
                </div>
                <?php if ($status == 5 && $user_role=='ASM' || $status == 4 && $user_role=='RSM') : ?>
                 <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
             
                    <!-- <a class="btn btn-info round px-2 mb-1" href="<?php echo BASE_URL.$pre_menu ?>"><i class="ft-user-check"></i> <?php echo 'Reject'; ?></a> -->
                    <button type="button" class="btn btn-info round px-2 mb-1 delete-btn" data-type="_s_c" data-id="<?php echo $distributor_id ?>" data-value="managers" data-cntrl="Distributors" data-func="list_dis_approval">
                                                            <span class="rpt_btn show"><i class="ft-user-check"></i> Reject</span>

                                                            <span class="rpt_span hide"><i class="la la-spinner spinner"></i> Loading....</span>
                    </button>
                </div>
                <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
             
                    <!-- <a class="btn btn-success round px-2 mb-1" href="<?php echo BASE_URL.$pre_menu ?>"><i class="ft-user-check"></i> <?php echo 'Approval'; ?></a> -->
                    <button type="button" class="btn btn-success round px-2 mb-1 approve-btn" data-type="_s_c" data-id="<?php echo $distributor_id ?>" data-value="managers" data-cntrl="Distributors" data-func="list_dis_approval">
                                                            <span class="rpt_btn show"><i class="ft-user-check"></i> Approval</span>

                                                            <span class="rpt_span hide"><i class="la la-spinner spinner"></i> Loading....</span>
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="content-body">
        <section id="multiple-column-form">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title m-0"><?php echo $page_title; ?></h4>
                           
                        </div>
                        <div class="card-body">
                            <form id="form_data" class="form_data socialDiv" name="form_data" method="post">
                               
                                <div class="row clearfix">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-3">
                                       
                                            
                                            <label class="form-label" for="first-name-column"><b>Company Name</b> </label>
                                            <span style="float: right;position: inherit;"><b>:</b></span>
                                            
                                           
                                        <!-- </div> -->
                                    </div>
                                    <div class="col-md-5">
                                        <div class="mb-1">
                                            
                                            <p class="form-label" for="first-name-column"><b><?php echo $company_name; ?></b></p>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                       
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-3">
                                       
                                            
                                            <label class="form-label" for="first-name-column"><b>Contact Name</b> </label>
                                            <span style="float: right;position: inherit;"><b>:</b></span>
                                            
                                           
                                        <!-- </div> -->
                                    </div>
                                    <div class="col-md-5">
                                        <div class="mb-1">
                                            
                                            <label class="form-label" for="first-name-column"><b><?php echo $contact_name; ?></b></label>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                       
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-3">
                                       
                                            
                                            <label class="form-label" for="first-name-column"><b>Email ID</b> </label>
                                            <span style="float: right;position: inherit;"><b>:</b></span>
                                            
                                           
                                        <!-- </div> -->
                                    </div>
                                    <div class="col-md-5">
                                        <div class="mb-1">
                                            
                                            <label class="form-label" for="first-name-column"><b><?php echo $email; ?></b></label>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                       
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-3">
                                       
                                            
                                            <label class="form-label" for="first-name-column"><b>Phone NO</b> </label>
                                            <span style="float: right;position: inherit;"><b>:</b></span>
                                            
                                           
                                        <!-- </div> -->
                                    </div>
                                    <div class="col-md-5">
                                        <div class="mb-1">
                                            
                                            <label class="form-label" for="first-name-column"><b><?php echo $mobile; ?></b></label>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                       
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-3">
                                       
                                            
                                            <label class="form-label" for="first-name-column"><b>PAN NO</b> </label>
                                            <span style="float: right;position: inherit;"><b>:</b></span>
                                            
                                           
                                        <!-- </div> -->
                                    </div>
                                    <div class="col-md-5">
                                        <div class="mb-1">
                                            
                                            <label class="form-label" for="first-name-column"><b><?php echo $pan_no; ?></b></label>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                       
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-3">
                                       
                                            
                                            <label class="form-label" for="first-name-column"><b>GST NO</b> </label>
                                            <span style="float: right;position: inherit;"><b>:</b></span>
                                            
                                           
                                        <!-- </div> -->
                                    </div>
                                    <div class="col-md-5">
                                        <div class="mb-1">
                                            
                                            <label class="form-label" for="first-name-column"><b><?php echo $gst_no; ?></b></label>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                       
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-3">
                                       
                                            
                                            <label class="form-label" for="first-name-column"><b>TAN NO</b> </label>
                                            <span style="float: right;position: inherit;"><b>:</b></span>
                                            
                                           
                                        <!-- </div> -->
                                    </div>
                                    <div class="col-md-5">
                                        <div class="mb-1">
                                            
                                            <label class="form-label" for="first-name-column"><b><?php echo $tan_no; ?></b></label>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                       
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-3">
                                       
                                            
                                            <label class="form-label" for="first-name-column"><b>State</b> </label>
                                            <span style="float: right;position: inherit;"><b>:</b></span>
                                            
                                           
                                        <!-- </div> -->
                                    </div>
                                    <div class="col-md-5">
                                        <div class="mb-1">
                                            
                                            <label class="form-label" for="first-name-column"><b><?php echo $state_value; ?></b></label>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                       
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-3">
                                       
                                            
                                            <label class="form-label" for="first-name-column"><b>City</b> </label>
                                            <span style="float: right;position: inherit;"><b>:</b></span>
                                            
                                           
                                        <!-- </div> -->
                                    </div>
                                    <div class="col-md-5">
                                        <div class="mb-1">
                                            
                                            <label class="form-label" for="first-name-column"><b><?php echo $city_value; ?></b></label>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                       
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-3">
                                       
                                            
                                            <label class="form-label" for="first-name-column"><b>Communication address</b> </label>
                                            <span style="float: right;position: inherit;"><b>:</b></span>
                                            
                                           
                                        <!-- </div> -->
                                    </div>
                                    <div class="col-md-5">
                                        <div class="mb-1">
                                            
                                            <p class="form-label" for="first-name-column"><b><?php echo $address; ?></b></p>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                       
                                </div>
                               <div class='row'>
                                                <div class="col-md-6">
                                                    <label for="projectinput1"><b>GST Image</b></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="projectinput1"><b>Cheque Image</b></span></label>
                                                </div>
                               </div>
                                 <div class='row'>
                                                <div class="col-md-6">
                                                    <div class="mb-1">
                                                        <div class="d-flex">
                                                        
                                                           
                                                            <img src="<?php echo $img_gst; ?>" id="gst-upload-img" class="uploadedGst rounded me-50" alt="profile image" width="100%"/>
                                                           
                                                           
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-1">
                                                        <div class="d-flex">
                                                    
                                                           
                                                            <img src="<?php echo $img_cheque; ?>" id="account-upload-img" class="uploadedAvatar rounded me-50" alt="profile image" width="100%" />
                                                           
                                                           
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>











                                








                               
                            </form>  
                        </div>
                    </div>
                </div>
            </div>
        </section>
        </div>
    </div>
</div>
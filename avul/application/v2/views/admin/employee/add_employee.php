<?php
//edit



$employee_id  = !empty($dataval[0]['employee_id']) ? $dataval[0]['employee_id'] : '0';
$f_name     = !empty($dataval[0]['first_name']) ? $dataval[0]['first_name'] : '';
$l_name      = !empty($dataval[0]['last_name']) ? $dataval[0]['last_name'] : '';
$mobile       = !empty($dataval[0]['mobile']) ? $dataval[0]['mobile'] : '';
$email        = !empty($dataval[0]['email']) ? $dataval[0]['email'] : '';
$address      = !empty($dataval[0]['address']) ? $dataval[0]['address'] : '';
$password     = !empty($dataval[0]['password']) ? $dataval[0]['password'] : '';
$log_type     = !empty($dataval[0]['log_type']) ? $dataval[0]['log_type'] : '2';
$login_status = !empty($dataval[0]['login_status']) ? $dataval[0]['login_status'] : '';
$status       = !empty($dataval[0]['status']) ? $dataval[0]['status'] : '0';

$pan_no       =!empty($dataval[0]['pan_no']) ? $dataval[0]['pan_no'] : '';
$aadhar_no    =!empty($dataval[0]['aadhar_no']) ? $dataval[0]['aadhar_no'] : '';
$pincode      =!empty($dataval[0]['pincode']) ? $dataval[0]['pincode'] : '';
$ifsc_code    =!empty($dataval[0]['ifsc_code']) ? $dataval[0]['ifsc_code'] : '';
$account_name =!empty($dataval[0]['account_name']) ? $dataval[0]['account_name'] : '';
$bank_name    =!empty($dataval[0]['bank_name']) ? $dataval[0]['bank_name'] : '';
$account_no   =!empty($dataval[0]['account_no']) ? $dataval[0]['account_no'] : '';
$branch_name  =!empty($dataval[0]['branch_name']) ? $dataval[0]['branch_name'] : '';
$dob          =!empty($dataval[0]['date_o_birth']) ? $dataval[0]['date_o_birth'] : '';
$gender_d       =!empty($dataval[0]['gender']) ? $dataval[0]['gender'] : '0';

$o_educational_q=!empty($dataval[0]['educational_q']) ? $dataval[0]['educational_q'] : '';
$father_n    =!empty($dataval[0]['father_n']) ? $dataval[0]['father_n'] : '';
$mother_n    =!empty($dataval[0]['mother_n']) ? $dataval[0]['mother_n'] : '';
$account_type_d  =!empty($dataval[0]['account_type']) ? $dataval[0]['account_type'] : '';
$street_name   =!empty($dataval[0]['street_name']) ? $dataval[0]['street_name'] : '';
$door          =!empty($dataval[0]['door_no']) ? $dataval[0]['door_no'] : '';
$existing_designation_id =!empty($dataval[0]['designation_id']) ? $dataval[0]['designation_id'] : '0';
$country_name =!empty($dataval[0]['country_name']) ? $dataval[0]['country_name'] : '';
$state_name=!empty($dataval[0]['state_name']) ? $dataval[0]['state_name'] : '';
$district_name=!empty($dataval[0]['district_name']) ? $dataval[0]['district_name'] : '';
$post_name    = !empty($dataval[0]['post_name']) ? $dataval[0]['post_name'] : '';
$designation1_id=[];
array_push($designation1_id,$existing_designation_id);
if($gender_d=="Male"){
$gender=1;

}else if($gender_d=="Female"){
    $gender=2;
}else{
    $gender=0;
}
if($account_type_d=='Current Account'){
    $account_type=1;
}else if($account_type_d=='Salary Account'){
    $account_type=2;
}else if($account_type_d=='Fixed Deposit Account'){
    $account_type=3;
}else if($account_type_d=='Recurring Deposit Account'){
    $account_type=4;
}else if($account_type_d=='NRI Account'){
    $account_type=5;
}else{
    $account_type=5;
}
   

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
                            <!-- <li class="breadcrumb-item"><a href="#"><?php //echo $sub_heading; 
                                                                            ?></a>
                            </li> -->
                            <li class="breadcrumb-item active"><?php echo $page_title; ?>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-6 col-12">
                <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                    <?php if ($page_access) : ?>
                        <a class="btn btn-info round px-2 mb-1" href="<?php echo BASE_URL . $pre_menu ?>"><i class="ft-plus-square"></i> <?php echo $pre_title; ?></a>
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
                                            <h6><b>Personal details :</b></h6>
                                        </div>
                                        </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">First Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="f_name" class="form-control f_name "  placeholder="First Name"  name="f_name" value="<?php echo $f_name; ?>" maxlength="15" oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ')"  >
                                                    </div>
                                                </div>
                                                <!-- oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ')" -->
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Last Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="l_name" class="form-control l_name " placeholder="Last Name" name="l_name" value="<?php echo $l_name; ?>"maxlength="15" oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ')" >
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Login Mobile Number <span class="text-danger">*</span></label>
                                                        <input type="text" id="mobile" class="form-control mobile int_value" placeholder="Mobile Number" name="mobile" value="<?php echo $mobile; ?>" maxlength="10">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Login Email<span class="text-danger">*</span></label>
                                                        <input type="text" id="email" class="form-control email str_int_value" placeholder="Email" name="email" value="<?php echo $email; ?>"maxlength="40" onkeydown="if(['Space'].includes(arguments[0].code)){return false;}">
                                                    </div>
                                                </div>
                                               
                                                
                                            </div>
                                            <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">PAN Number<span class="text-danger">*</span></label>
                                                        <input type="text" id="pan_no" class="form-control pan_no " placeholder="PAN Number" name="pan_no" value="<?php echo $pan_no; ?>"maxlength="10" >
                                                    </div>
                                                </div>
                                               

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Aadhar Number<span class="text-danger">*</span></label>
                                                        <input type="text" id="aadhar_no" class="form-control aadhar_no int_value " placeholder="Aadhar Number" name="aadhar_no" value="<?php echo $aadhar_no; ?>"maxlength="12" pattern="[^10].*">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Date Of Birth<span class="text-danger">*</span></label>
                                                        <input type="text" id="dob" class="form-control dob dates no_entry" placeholder="dd/mm/yyyy" name="dob" value="<?php echo $dob; ?>"maxlength="0">
                                                    </div>
                                                </div> 
                                                <div class="col-md-3">
                                                    <div class="form-group ">
                                                        <label for="projectinput1">Educational Qualification <span class="text-danger">*</span></label>
                                                        <input type="text" id="o_educational_q" class="form-control o_educational_q str_value" placeholder="Insert Educational Qualification" name="o_educational_q" value="<?php echo $o_educational_q; ?>"maxlength="50" oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ')">
                                                    </div>
                                                </div>                                         
                                               
                                               
                                               
                                            </div>
                                            <div class="row">
                                                     
                                                    


                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Gender <span class="text-danger">*</span></label><br>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="gender1" name="gender" class="custom-control-input" <?php echo $gender == 1 ? 'checked' : ''; ?> value="1">
                                                                <label class="custom-control-label" for="gender1">Male</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="gender2" name="gender" class="custom-control-input" <?php echo $gender == 2 ? 'checked' : ''; ?> value="2">
                                                                <label class="custom-control-label" for="gender2">Female </label>
                                                            </div>
                                                            <!-- <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="gender3" name="gender" class="custom-control-input" <?php echo $gender == 3 ? 'checked' : ''; ?> value="3">
                                                                <label class="custom-control-label" for="gender3">others </label>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Father's Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="father_n" class="form-control father_n str_value" placeholder="Father's Name" name="father_n" value="<?php echo $father_n; ?>"maxlength="30" oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ')"  >
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Mother's Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="mother_n" class="form-control mother_n str_value" placeholder="Mother's Name" name="mother_n" value="<?php echo $mother_n; ?>"maxlength="30" oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ')"  >
                                                    </div>
                                                </div>
                                                   
                                               
                                                   
                                            
                                               
                                               
                                               
                                            </div> 
                                             
                                            
                                           
                                            
                                            

                                            <div class="row">
                                                <div class="col-md-4">
                                                 <h6><b>Bank details :</b></h6>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Account Holder Name<span class="text-danger">*</span></label>
                                                        <input type="text" id="account_name" class="form-control account_name str_value" placeholder="Account Holder Name" name="account_name" value="<?php echo $account_name; ?>"maxlength="30" oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ')"  >
                                                    </div>
                                                </div>
                                               
                                               
                                                <div class="col-md-4">
                                                
                                                    <div class="form-group">
                                                        <label for="projectinput1">Account Number<span class="text-danger">*</span></label>
                                                        <input type="text" id="account_no" class="form-control account_no int_value" placeholder="Account Number" name="account_no" value="<?php echo $account_no; ?>"maxlength="17">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Account Type<span class="text-danger">*</span></label>
                                                        <select class="form-control account_type js-select1-multi" id="account_type" name="account_type">
                                                            <option value="">Select Value</option>
                                                            <option value="1" <?php echo $account_type == 1 ? 'selected' : ''; ?>>Current Account</option>
                                                            <option value="2" <?php echo $account_type == 2 ? 'selected' : ''; ?>>Salary Account</option>
                                                            <option value="3" <?php echo $account_type == 3 ? 'selected' : ''; ?>>Fixed Deposit Account</option>
                                                            <option value="4" <?php echo $account_type == 4 ? 'selected' : ''; ?>>Recurring deposit account</option>
                                                            <option value="5" <?php echo $account_type == 5 ? 'selected' : ''; ?>>NRI account</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">IFSC Code<span class="text-danger">*</span></label>
                                                        <input type="text" id="ifsc_code" class="form-control ifsc_code" placeholder="IFSC Code" name="ifsc_code" value="<?php echo $ifsc_code; ?>"maxlength="11" onkeydown="if(['Space'].includes(arguments[0].code)){return false;}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Bank Name<span class="text-danger">*</span></label>
                                                        <input type="text" id="bank_name" class="form-control bank_name " placeholder="Bank Name" name="bank_name" value="<?php echo $bank_name; ?>" maxlength="50" oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ')"  >
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Branch Name<span class="text-danger">*</span></label>
                                                        <input type="text" id="branch_name" class="form-control branch_name " placeholder="Branch Name" name="branch_name" value="<?php echo $branch_name; ?>" maxlength="30" oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ')"  >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                            <div class="col-md-4">
                                                <h6><b>Contact Details :</b></h6>
                                            </div>
                                            </div>
                                            
                                            
                                            <div class="row">
                                                
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Pincode</label>
                                                        <input type="text" id="pincode" class="form-control pincode int_value " placeholder="Pincode" name="pincode" value="<?php echo $pincode; ?>"maxlength="6">
                                                        <span id="pincode-error" class ="error" color="red"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Country Name<span class="text-danger">*</span></label>
                                                        <input type="text" id="country_name" class="form-control country_name no_entry" placeholder="Country Name" name="country_name" value="<?php echo $country_name; ?>"maxlength="30" oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ')"  >
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">State Name<span class="text-danger">*</span></label>
                                                        <input type="text" id="state_name" class="form-control state_name no_entry " placeholder="State Name" name="state_name" value="<?php echo $state_name; ?>"maxlength="30" oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ')"  >
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">District Name<span class="text-danger">*</span></label>
                                                        <input type="text" id="district_name" class="form-control district_name no_entry" placeholder="District Name" name="district_name" value="<?php echo $district_name; ?>"maxlength="30" oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ')"  >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Post Name<span class="text-danger">*</span></label>
                                                        <input type="text" id="post_name" class="form-control post_name " placeholder="Post Office Name" name="post_name" value="<?php echo $post_name; ?>"maxlength="30" oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ')"  >
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Street Name<span class="text-danger">*</span></label>
                                                        <input type="text" id="street_name" class="form-control street_name " placeholder="Street Name" name="street_name" value="<?php echo $street_name; ?>"maxlength="30" oninput="this.value=this.value.replace(/^\s+/g, '').replace(/[^a-z0-9\s]/ig, '').replace(/\s{2,}/g, ' ')"  >
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Door Number<span class="text-danger">*</span></label>
                                                        <input type="text" id="door" class="form-control door  " placeholder="Door Number" name="door" value="<?php echo $door; ?>"maxlength="10" >
                                                    </div>
                                                </div>
                                               
                                            </div>
                                            <div class="row">
                                            <div class="col-md-4">
                                                <h6><b>Role & Password :</b></h6>
                                            </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Designation <span class="text-danger">*</span></label>

                                                    
                                                        <select class="form-control  js-example-placeholder-multiple grade_id" id="grade_id" name="grade_id" >
                                                            <?php
                                                                if(!empty($hierarchy))
                                                                {
                                                                    foreach ($hierarchy as $key => $value) {
                                                                        $id   = !empty($value['designation_id'])?$value['designation_id']:'';
                                                                        $designation_name = !empty($value['designation_name'])?$value['designation_name']:'';
                                                                        $designation_code = !empty($value['designation_code'])?$value['designation_code']:'';
                                                                        $select   = '';
                                                                   
                                                                        if(in_array($id, $designation1_id))
                                                                        {
                                                                            $select = 'selected';
                                                                        }

                                                                        echo '<option value="'.$id.'" '.$select.'>'.$designation_name.'('.$designation_code.')</option>';
                                                                    }
                                                                    
                                                                }
                                                            ?>
                                                        </select>
                                                      
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Login Password<span class="text-danger">*</span></label>
                                                        <input type="text" id="password" class="form-control door password " placeholder="Password" name="password" value="<?php echo $password; ?>"maxlength="8" onkeydown="if(['Space'].includes(arguments[0].code)){return false;}" >
                                                        <div class="form-control-position field-icon">
                                                        <i class="toggle-password la la-eye-slash" toggle="#password"></i>
                                                    </div>
                                                        <span id="password-error" class ="error" color="red"></span>
                                                    </div>
                                                </div>
 
                                            </div>

                                          
                                            
                                            <?php
                                            if ($method == 'BTBM_X_U') {
                                            ?>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>Login Status <span class="text-danger">*</span></label><br>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="logType1" name="login_status" class="custom-control-input" <?php echo $login_status == 1 ? 'checked' : ''; ?> value="1">
                                                                <label class="custom-control-label" for="logType1">Logout</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="logType2" name="login_status" class="custom-control-input" <?php echo $login_status == 2 ? 'checked' : ''; ?> value="2">
                                                                <label class="custom-control-label" for="logType2">Login </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>Status <span class="text-danger">*</span></label><br>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="postType1" name="pstatus" class="custom-control-input" <?php echo $status == 1 ? 'checked' : ''; ?> value="1">
                                                                <label class="custom-control-label" for="postType1">Active</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="postType2" name="pstatus" class="custom-control-input" <?php echo $status == 0 ? 'checked' : ''; ?> value="0">
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
                                            <input type="hidden" name="log_type" id="log_type" class="log_type" value="<?php echo $log_type ?>">
                                            <input type="hidden" name="employee_id" id="employee_id" class="employee_id" value="<?php echo $employee_id; ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="employee">
                                            <input type="hidden" name="func" id="func" class="func" value="add_employee">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" class="method" value="<?php echo $method; ?>">
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
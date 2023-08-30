<?php 
    $rsm_val    = !empty($grade_val['grade_1']) ? $grade_val['grade_1'] : '';
    $asm_val = !empty($grade_val['grade_2']) ? $grade_val['grade_2'] : '';
    $so_val   = !empty($grade_val['grade_3']) ? $grade_val['grade_3'] : '';
    $tsi_val  = !empty($grade_val['grade_4']) ? $grade_val['grade_4'] : '';

    $employee_id   = !empty($dataval[0]['employee_id'])?$dataval[0]['employee_id']:'';
    $position_id   = !empty($dataval[0]['position_id'])?$dataval[0]['position_id']:'';
  
    $contact_name   = !empty($dataval[0]['contact_name'])?$dataval[0]['contact_name']:'';
    $mobile         = !empty($dataval[0]['mobile'])?$dataval[0]['mobile']:'';
    $email          = !empty($dataval[0]['email'])?$dataval[0]['email']:'';
    $gst_no         = !empty($dataval[0]['gst_no'])?$dataval[0]['gst_no']:'';
    $pan_no         = !empty($dataval[0]['pan_no'])?$dataval[0]['pan_no']:'';
    $aadhar_no      = !empty($dataval[0]['aadhar_no'])?$dataval[0]['aadhar_no']:'';
    $account_name   = !empty($dataval[0]['account_name'])?$dataval[0]['account_name']:'';
    $account_no     = !empty($dataval[0]['account_no'])?$dataval[0]['account_no']:'';
    $account_type   = !empty($dataval[0]['account_type'])?$dataval[0]['account_type']:'';
    $ifsc_code      = !empty($dataval[0]['ifsc_code'])?$dataval[0]['ifsc_code']:'';
    $bank_name      = !empty($dataval[0]['bank_name'])?$dataval[0]['bank_name']:'';
    $branch_name    = !empty($dataval[0]['branch_name'])?$dataval[0]['branch_name']:'';
    $password       = !empty($dataval[0]['password'])?$dataval[0]['password']:'';
    $pincode        = !empty($dataval[0]['pincode'])?$dataval[0]['pincode']:''; 
    $state_value    = !empty($dataval[0]['state_id'])?$dataval[0]['state_id']:'';     
    $designation_code = !empty($dataval[0]['designation_code'])?$dataval[0]['designation_code']:'';
    $status         = !empty($dataval[0]['status'])?$dataval[0]['status']:'0';
    $mg_status     = !empty($dataval[0]['grade'])?$dataval[0]['grade']:'0';
    $ctrl_state_id = !empty($dataval[0]['ctrl_state_id']) ? $dataval[0]['ctrl_state_id'] : '';
    $ctrl_city_id = !empty($dataval[0]['ctrl_city_id']) ? $dataval[0]['ctrl_city_id'] : '';
    $ctrl_zone_id = !empty($dataval[0]['ctrl_zone_id']) ? $dataval[0]['ctrl_zone_id'] : '';
   

    $designation1_id=[];
array_push($designation1_id,$position_id);
  
    $exsiting_data =array();
    if(!empty($ctrl_state_id)){
        foreach ($ctrl_state_id as $key => $value) {
           $st   = !empty($value['id'])?$value['id']:'';
     
            array_push($exsiting_data,$st);
        }
    }
   

    $exsiting_cty_data =array();
    if(!empty($ctrl_city_id)){
        foreach ($ctrl_city_id as $key => $value) {
            $ct   = !empty($value['id'])?$value['id']:'';
          
                array_push($exsiting_cty_data,$ct);
                }
       
    }
    
         $exsiting_zne_data =array();
    if(!empty($ctrl_zone_id)){
            foreach ($ctrl_zone_id as $key => $value) {
                $zt   = !empty($value['id'])?$value['id']:'';
               
                    array_push($exsiting_zne_data,$zt);
                    }
    }
      


   
    $view_type6 = 'hide';
    $view_type4 = 'hide';
    $view_type1 = 'hide';
    if($designation_code == 'RSM')
    {
        $view_type1 = 'show';
    }
    $view_type3 = 'hide';
    $view_type2 = 'hide';
    if($designation_code  =='SO')
    {
        $view_type2 = 'show';
        $view_type3 = 'show';
    }
    
    $view_type5 = 'hide';
  
    if($designation_code =='TSI')
    {
        $view_type2 = 'show';
        $view_type4 = 'show';
        $view_type5 = 'show';
    }

    if($designation_code =='BDE')
    {   $view_type2 = 'show';
        $view_type4 = 'show';
        $view_type6 = 'show';
    }
    if($designation_code  =='ASM')
    {
        $view_type2 = 'show';
      
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
                                    <form class="data_form" name="data_form" method="post">
                                        <div class="form-body">
                                            
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        
                                                         <label for="projectinput1">Designation <span class="text-danger">*</span></label>

                                                    
                                                        <select class="form-control  js-example-placeholder-multiple grade_id" id="grade_id" name="grade_id" >
                                                            <option value=''> Select Designation Name</option>
                                                            <?php
                                                                if(!empty($grade_val))
                                                                {
                                                                    foreach ($grade_val as $key => $value) {
                                                                        $id   = !empty($value['designation_id'])?$value['designation_id']:'';
                                                                        $designation_name = !empty($value['designation_name'])?$value['designation_name']:'';
                                                                        $designation_code = !empty($value['designation_code'])?$value['designation_code']:'';
                                                                        $select   = '';
                                                                   
                                                                        if(in_array($id, $designation1_id))
                                                                        {
                                                                            $select = 'selected';
                                                                        }

                                                                        echo '<option value="'.$designation_code.'" '.$select.'>'.$designation_name.'('.$designation_code.')</option>';
                                                                    }
                                                                    
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
 
                                            </div>
 
                                            <div class="row head">
                                                <div class="col-md-4">
                                                    <div class="form-group rsm  <?php echo $view_type1; ?>">
                                                        <label for="projectinput1">Available State <span class="text-danger">*</span></label>

                                                        <select class="form-control astate_id commen js-example-placeholder-multiple" id="astate_id" name="astate_id[]" multiple="multiple" style="width: 100%;">
                                                        <option value="">Select State Name </option>
                                                            <?php
                                                                if(!empty($avai_state_val))
                                                                {
                                                                    foreach ($avai_state_val as $key => $value) {
                                                                        $astate_id   = !empty($value['id'])?$value['id']:'';
                                                                        $astate_name = !empty($value['state_name'])?$value['state_name']:'';

                                                                        $select   = '';
                                                                    
                                                                        if(in_array($astate_id, $exsiting_data))
                                                                        {
                                                                            $select = 'selected';
                                                                        }

                                                                        echo '<option value="'.$astate_id.'" '.$select.'>'.$astate_name.'</option>';
                                                                    }
                                                                    
                                                                }
                                                            ?>
                                                        </select>

                                                        <input style="margin-top: 10px;" type="checkbox" id="check_astate"> Select all state
                                                    </div>
                                                
                                                    <div class="form-group asm  <?php echo $view_type2; ?>">
                                                          <label for="projectinput1">Available State <span class="text-danger">*</span></label>

                                                        <select class="form-control asmstate_id commen js-example-placeholder-multiple" id="asmstate_id" name="asmstate_id" style="width: 100%;">
                                                        <option value =''>Select State Name</option>
                                                            <?php
                                                                if(!empty($avai_state_val))
                                                                {
                                                                    foreach ($avai_state_val as $key => $value) {
                                                                        $asstate_id   = !empty($value['id'])?$value['id']:'';
                                                                        $asstate_name = !empty($value['state_name'])?$value['state_name']:'';

                                                                        $select   = '';
                                                                        
                                                                        if(in_array($asstate_id, $exsiting_data))
                                                                        {
                                                                            $select = 'selected';
                                                                        }

                                                                        echo '<option value="'.$asstate_id.'" '.$select.'>'.$asstate_name.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group so  <?php echo $view_type3; ?>">
                                                          <label for="projectinput1">Available City <span class="text-danger">*</span></label>

                                                          <select class="form-control commen acity_id js-example-placeholder-multiple" id="acity_id" name="acity_id[]" multiple="multiple" style="width: 100%;">
                                                          <option value="">Select City Name</option>
                                                            <?php
                                                                if(!empty($avai_city_val))
                                                                {
                                                                    foreach ($avai_city_val as $key => $value) {
                                                                        $acity_id   = !empty($value['id'])?$value['id']:'';
                                                                        $acity_name = !empty($value['city_name'])?$value['city_name']:'';

                                                                        $select   = '';
                                                                        $zone_res = explode(',', $zone_value);
                                                                        if(in_array($acity_id, $exsiting_cty_data))
                                                                        {
                                                                            $select = 'selected';
                                                                        }

                                                                        echo '<option value="'.$acity_id.'" '.$select.'>'.$acity_name.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>

                                                        <input style="margin-top: 10px;" type="checkbox" id="check_acity"> Select all city
                                                    </div>

                                                    <div class="form-group aso  <?php echo $view_type4; ?>">
                                                          <label for="projectinput1">Available City <span class="text-danger">*</span></label>

                                                        <select class="form-control commen ascity_id js-example-placeholder-multiple" id="ascity_id" name="ascity_id" style="width: 100%;">
                                                        <option value="">Select City Name</option>
                                                            <?php
                                                                if(!empty($avai_city_val))
                                                                {
                                                                    foreach ($avai_city_val as $key => $value) {
                                                                        $ascity_id   = !empty($value['id'])?$value['id']:'';
                                                                        $ascity_name = !empty($value['city_name'])?$value['city_name']:'';

                                                                        $select   = '';
                                                                        
                                                                        if(in_array($ascity_id, $exsiting_cty_data))
                                                                        {
                                                                            $select = 'selected';
                                                                        }

                                                                        echo '<option value="'.$ascity_id.'" '.$select.'>'.$ascity_name.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                
                                                
                                                 
                                                    <div class="form-group tsi  <?php echo $view_type5; ?>">
                                                        <label for="projectinput1">Available Zone <span class="text-danger">*</span></label>

                                                        <select class="form-control commen mzone js-example-placeholder-multiple" id="mzone" name="mzone[]" multiple="multiple" style="width: 100%;">
                                                        <option value="">Select Zone Name</option>
                                                            <?php
                                                                if(!empty($avai_zone_val))
                                                                {
                                                                    foreach ($avai_zone_val as $key => $value) {
                                                                        $azone_id   = !empty($value['id'])?$value['id']:'';
                                                                        $azone_name = !empty($value['name'])?$value['name']:'';

                                                                        $select   = '';
                                                                        // $zone_res = explode(',', $zone_value);
                                                                        if(in_array($azone_id, $exsiting_zne_data))
                                                                        {
                                                                            $select = 'selected';
                                                                        }

                                                                        echo '<option value="'.$azone_id.'" '.$select.'>'.$azone_name.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>

                                                        <input style="margin-top: 10px;" type="checkbox" id="check_mzone"> Select all zone
                                                    </div>

                                                    <div class="form-group bde  <?php echo $view_type6; ?>">
                                                        <label for="projectinput1">Available Zone <span class="text-danger">*</span></label>

                                                        <select class="form-control commen bmzone js-example-placeholder-multiple" id="bmzone" name="bmzone"  style="width: 100%;">
                                                        <option value="">Select Zone Name</option>
                                                            <?php
                                                                if(!empty($avai_zone_val))
                                                                {
                                                                    foreach ($avai_zone_val as $key => $value) {
                                                                        $bazone_id   = !empty($value['id'])?$value['id']:'';
                                                                        $bazone_name = !empty($value['name'])?$value['name']:'';

                                                                        $select   = '';
                                                                        
                                                                        if(in_array($bazone_id, $exsiting_zne_data))
                                                                        {
                                                                            $select = 'selected';
                                                                        }

                                                                        echo '<option value="'.$bazone_id.'" '.$select.'>'.$bazone_name.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>

                                                       
                                                    </div>
                                                        
                                                    
                                               
                                                </div>
                                            </div>
                                           
                                           
                                            <!-- <?php
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
                                            ?> -->
                                        </div>
                                        <div class="form-actions">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="employee_id" id="employee_id" class="employee_id" value="<?php echo $employee_id; ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="posting">
                                            <input type="hidden" name="func" id="func" class="func" value="add_posting">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" class="method" value="<?php echo $method;?>">
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
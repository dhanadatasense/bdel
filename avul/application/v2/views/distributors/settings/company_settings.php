<?php
    $user_id     = !empty($dataval['user_id'])?$dataval['user_id']:'';
    $address     = !empty($dataval['address'])?$dataval['address']:'';
    $state_value = !empty($dataval['state_id'])?$dataval['state_id']:'';
    $city_value  = !empty($dataval['city_id'])?$dataval['city_id']:'';
    $gst_no      = !empty($dataval['gst_no'])?$dataval['gst_no']:'';
    $pan_no      = !empty($dataval['pan_no'])?$dataval['pan_no']:'';
    $fssai_no    = !empty($dataval['fssai_no'])?$dataval['fssai_no']:'';
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
                                                        <label for="projectinput1">GSTIN No <span class="text-danger">*</span></label>
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
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Address <span class="text-danger">*</span></label>
                                                        <textarea id="plocation" class="form-control address" placeholder="Address" name="address" rows="3"><?php echo $address; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="user_id" id="user_id" class="user_id" value="<?php echo $user_id; ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="settings">
                                            <input type="hidden" name="func" id="func" class="func" value="company_settings">
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
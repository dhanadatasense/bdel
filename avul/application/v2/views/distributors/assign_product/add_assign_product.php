<?php

    // Assign Emplyee Details
    $assign_id      = !empty($dataval['assign_id'])?$dataval['assign_id']:'';
    $distributor_id = !empty($dataval['distributor_id'])?$dataval['distributor_id']:'';
    $state_value    = !empty($dataval['state_id'])?$dataval['state_id']:'';
    $city_value     = !empty($dataval['city_id'])?$dataval['city_id']:'';
    $zone_value     = !empty($dataval['zone_id'])?$dataval['zone_id']:'';
    $type_id        = !empty($dataval['type_id'])?$dataval['type_id']:'';
    $description    = !empty($dataval['description'])?$dataval['description']:'';
    $minimum_stock  = !empty($dataval['minimum_stock'])?$dataval['minimum_stock']:'0';
    $minimum_order  = !empty($dataval['minimum_order'])?$dataval['minimum_order']:'0';
    $status         = !empty($dataval['status'])?$dataval['status']:'0';
?>
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
                                    <form class="data_form" name="data_form" method="post">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Description <span class="text-danger">*</span></label>
                                                        <input type="text" id="description" class="form-control description" placeholder="Category Name" name="description" value="<?php echo $description; ?>" readonly="readonly" style="background-color: #fff;">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
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

                                                                        if($state_res == $state_id)
                                                                        {
                                                                            $select = '';
                                                                            if($state_id == $state_value)
                                                                            {
                                                                                $select = 'selected';
                                                                            }

                                                                            echo '<option value="'.$state_id.'" '.$select.'>'.$state_name.'</option>';
                                                                        }
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Minimum Stock</label>
                                                        <input type="text" id="minimum_stock" class="form-control minimum_stock" placeholder="Minimum Stock" name="minimum_stock" value="<?php echo $minimum_stock; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Minimum Order</label>
                                                        <input type="text" id="minimum_order" class="form-control minimum_order" placeholder="Minimum Order" name="minimum_order" value="<?php echo $minimum_order; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="projectinput1">City Name <span class="text-danger">*</span></label>
                                                        <select class="form-control city_id js-select1-multi" id="city_id" name="city_id[]" multiple="multiple" style="width: 100%;">
                                                            <?php
                                                            if(!empty($city_val))
                                                            {
                                                                foreach ($city_val as $key => $value) {
                                                                    $city_id   = !empty($value['city_id'])?$value['city_id']:'';
                                                                    $city_name = !empty($value['city_name'])?$value['city_name']:'';

                                                                    $select   = '';
                                                                    $city_res = explode(',', $city_value);
                                                                    if(in_array($city_id, $city_res))
                                                                    {
                                                                        $select = 'selected';
                                                                    }

                                                                    echo '<option value="'.$city_id.'" '.$select.'>'.$city_name.'</option>';
                                                                }
                                                            }
                                                        ?>
                                                        </select>

                                                        <input style="margin-top: 10px;" type="checkbox" id="check_city"> Select all cities
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Beat Name <span class="text-danger">*</span></label>

                                                        <select class="form-control zone_id js-example-placeholder-multiple" id="zone_id" name="zone_id[]" multiple="multiple" style="width: 100%;">
                                                            <?php
                                                                if(!empty($zone_val))
                                                                {
                                                                    foreach ($zone_val as $key => $value) {
                                                                        $zone_id   = !empty($value['zone_id'])?$value['zone_id']:'';
                                                                        $zone_name = !empty($value['zone_name'])?$value['zone_name']:'';

                                                                        $select   = '';
                                                                        $zone_res = explode(',', $zone_value);
                                                                        if(in_array($zone_id, $zone_res))
                                                                        {
                                                                            $select = 'selected';
                                                                        }

                                                                        echo '<option value="'.$zone_id.'" '.$select.'>'.$zone_name.'</option>';
                                                                    }
                                                                }else{
                                                                    echo '<option >'.'zone not assign'.'</option>';
                                                                }
                                                            ?>
                                                        </select>

                                                        <input style="margin-top: 10px;" type="checkbox" id="check_beat"> Select all beats
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
                                        <div class="form-actions submit_btn">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="assign_id" id="assign_id" class="assign_id" value="<?php echo $assign_id; ?>">
                                            <input type="hidden" name="distributor_id" id="distributor_id" class="distributor_id" value="<?php echo $distributor_id; ?>">
                                            <input type="hidden" name="type_id" id="type_id" class="type_id" value="<?php echo $type_id; ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="distributors">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="assignproduct">
                                            <input type="hidden" name="func" id="func" class="func" value="add_assign_product">
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
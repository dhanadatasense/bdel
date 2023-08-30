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
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Product Name <span class="text-danger">*</span></label>
                                                        <select class="form-control product_id js-select1-multi" id="product_id" name="product_id[]" multiple="multiple" style="width: 100%;">
                                                            <?php
                                                                if(!empty($product_val))
                                                                {
                                                                    foreach ($product_val as $key => $val) {
                                                                        $assproduct_id = !empty($val['assproduct_id'])?$val['assproduct_id']:'';
                                                                        $description   = !empty($val['description'])?$val['description']:'';

                                                                        echo '<option value="'.$assproduct_id.'">'.$description.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                        <input style="margin-top: 10px;" type="checkbox" id="check_product"> Select all product
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

                                                                        echo '<option value="'.$city_id.'">'.$city_name.'</option>';
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
                                                                }
                                                            ?>
                                                        </select>

                                                        <input style="margin-top: 10px;" type="checkbox" id="check_beat"> Select all beats
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-actions submit_btn">
                                                <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                                <input type="hidden" name="assign_id" id="assign_id" class="assign_id" value="">
                                                <input type="hidden" name="distributor_id" id="distributor_id" class="distributor_id" value="<?php echo $distributor_val; ?>">
                                                <input type="hidden" name="state_id" id="state_id" class="state_id" value="<?php echo $state_val; ?>">
                                                <input type="hidden" name="value" id="value" class="value" value="admin">
                                                <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="assignproduct">
                                                <input type="hidden" name="func" id="func" class="func" value="assign_multi_beat">
                                                <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                                <input type="hidden" name="method" class="method" value="<?php echo $method;?>">
                                                <button type="submit" class="btn btn-primary data_submit" data-type="_s_c">
                                                    <span class="first_btn show"><i class="la la-check-square-o"></i> Submit</span>

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
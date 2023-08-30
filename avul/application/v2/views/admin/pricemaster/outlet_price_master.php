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
                <!-- <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                    <a class="btn btn-info round px-2 mb-1" href="<?php echo BASE_URL.$pre_menu ?>"><i class="ft-plus-square"></i> <?php echo $pre_title; ?></a>
                </div> -->
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
                                    <?php if($page_access == TRUE): ?>
                                    <form class="data_form" name="data_form" method="post">
                                        <div class="form-body">
                                            <div class="row">
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
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Beat Name <span class="text-danger">*</span></label>

                                                        <select class="form-control zone_id js-select1-multi" id="zone_id" name="zone_id">   
                                                            <option value="">Select Beat Name</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Outlet Name <span class="text-danger">*</span></label>
                                                        <select class="form-control outlet_id js-select1-multi" id="outlet_id" name="outlet_id" style="width: 100%;">
                                                            <option value="">Select Vendor Name</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Category Name <span class="text-danger">*</span></label>
                                                        <select class="form-control category_id js-select1-multi" id="category_id" name="category_id" style="width: 100%;">
                                                            <option value="">Select Category Name</option>
                                                            <?php
                                                                if (!empty($category_val))
                                                                {
                                                                    foreach ($category_val as $key => $value_2)
                                                                    {
                                                                        $category_id   = !empty($value_2['category_id'])?$value_2['category_id']:'';
                                                                        $category_name = !empty($value_2['category_name'])?$value_2['category_name']:'';

                                                                        echo '<option value="'.$category_id.'" >'.$category_name.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Sub Category Name </label>
                                                        <select class="form-control sub_cat_id js-select1-multi" id="sub_cat_id" name="sub_cat_id" style="width: 100%;">
                                                            <option value="">Select Sub Category Name</option>
                     
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="hidden" name="value" id="value" class="value" value="admin">
                                                        <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="pricemaster">
                                                        <input type="hidden" name="func" id="func" class="func" value="outlet_price_master">
                                                        <button type="button" class="btn btn-primary outlet_price_list price_submit m-t-27 " data-type="_s_c" data-value="admin" data-cntrl="pricemaster" data-func="outlet_price_master">
                                                            <span class="price_btn show"><i class="la la-check-square-o"></i> Submit</span>

                                                            <span class="price_span hide"><i class="la la-spinner spinner"></i> Loading....</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 filter-design">
                                                <div class="price_value hide">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Product Name</th>
                                                                    <th>Price</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="getPriceList">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 filter-design">
                                                <div id="price_error" class="show alert alert-danger text-center">
                                                    <b class="error_msg">No items found...</b>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions submit_btn hide">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="pricemaster">
                                            <input type="hidden" name="func" id="func" class="func" value="outlet_price_master">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" class="method" value="<?php echo $method;?>">
                                            <button type="submit" class="btn btn-primary data_submit" data-type="_s_c">
                                                <span class="first_btn show"><i class="la la-check-square-o"></i> <?php echo $page_title; ?></span>

                                                <span class="span_btn hide"><i class="la la-spinner spinner"></i> Loading....</span>
                                            </button>
                                        </div>
                                    </form>
                                    <?php endif; ?>
                                    <?php if($page_access == FALSE): ?>
                                        <div class="row">
                                            <div class="col-sm-12 filter-design">
                                                <div id="price_error" class="show alert alert-danger text-center">
                                                    <b class="error_msg">Access denied</b>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
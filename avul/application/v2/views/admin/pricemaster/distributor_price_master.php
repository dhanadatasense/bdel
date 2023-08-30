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
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Distributor Name <span class="text-danger">*</span></label>
                                                        <select class="form-control distributor_id js-select1-multi" id="distributor_id" name="distributor_id" style="width: 100%;">
                                                            <option value="">Select Vendor Name</option>
                                                            <?php
                                                                if (!empty($distributor_val))
                                                                {
                                                                    foreach ($distributor_val as $key => $value_1)
                                                                    {
                                                                        $distributor_id = !empty($value_1['distributor_id'])?$value_1['distributor_id']:'';
                                                                        $company_name   = !empty($value_1['company_name'])?$value_1['company_name']:'';
                                                                        $mobile         = !empty($value_1['mobile'])?$value_1['mobile']:'';

                                                                        echo '<option value="'.$distributor_id.'" >'.$company_name.' ('.$mobile.')'.'</option>';
                                                                    }
                                                                }
                                                            ?>
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
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Sub Category Name </label>
                                                        <select class="form-control sub_cat_id js-select1-multi" id="sub_cat_id" name="sub_cat_id" style="width: 100%;">
                                                            <option value="">Select Sub Category Name</option>
                                                            <?php
                                                                if (!empty($sub_category_val))
                                                                {
                                                                    foreach ($sub_category_val as $key => $value_2)
                                                                    {
                                                                        $category_id   = !empty($value_2['s_cat_id'])?$value_2['s_cat_id']:'';
                                                                        $category_name = !empty($value_2['s_cat_name'])?$value_2['s_cat_name']:'';

                                                                        echo '<option value="'.$category_id.'" >'.$category_name.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                    <input type="hidden" name="value" id="value" class="value" value="admin">
                                                        <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="pricemaster">
                                                        <input type="hidden" name="func" id="func" class="func" value="distributor_price_master">
                                                        <button type="button" class="btn btn-primary distributor_price_list price_submit m-t-27 " data-type="_s_c" data-value="admin" data-cntrl="pricemaster" data-func="distributor_price_master">
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
                                            <input type="hidden" name="func" id="func" class="func" value="distributor_price_master">
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
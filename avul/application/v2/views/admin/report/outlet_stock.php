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
            <!-- <div class="content-header-right col-md-6 col-12">
                <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                    <a class="btn btn-info round px-2 mb-1" href="<?php echo BASE_URL.$pre_menu ?>"><i class="ft-plus-square"></i> <?php echo $pre_title; ?></a>
                </div>
            </div> -->
        </div>
        <?php if(userAccess('outlet-order-report-view') == TRUE): ?>
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
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Month Name <span class="text-danger">*</span></label>
                                                        <select class="form-control month_id js-select1-multi" id="month_id" name="month_id" style="width: 100%;">
                                                            <option value="">Select Vendor Name</option>
                                                            <?php
                                                                if (!empty($month_list))
                                                                {
                                                                    foreach ($month_list as $key => $value_1)
                                                                    {
                                                                        $month_value = !empty($value_1['month_value'])?$value_1['month_value']:'';
                                                                        $month_name  = !empty($value_1['month_name'])?$value_1['month_name']:'';

                                                                        echo '<option value="'.$month_value.'">'.$month_name.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Year Name <span class="text-danger">*</span></label>
                                                        <select class="form-control year_id js-select1-multi" id="year_id" name="year_id" style="width: 100%;">
                                                            <option value="">Select Vendor Name</option>
                                                            <?php
                                                                if (!empty($year_list))
                                                                {
                                                                    foreach ($year_list as $key => $value_2)
                                                                    {
                                                                        $year_value = !empty($value_2['year_id'])?$value_2['year_id']:'';
                                                                        $year_name  = !empty($value_2['year_name'])?$value_2['year_name']:'';

                                                                        echo '<option value="'.$year_value.'">'.$year_name.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                               

                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Select State Name <span class="text-danger">*</span></label>

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
                                                        <label for="projectinput1">Select City Name <span class="text-danger">*</span></label>

                                                        <select class="form-control city_id js-select1-multi" id="city_id" name="city_id">   
                                                            <option value="">Select City Name</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Select Beat Name <span class="text-danger">*</span></label>

                                                        <select class="form-control zone_id js-select1-multi" id="zone_id" name="zone_id">   
                                                            <option value="">Select Beat Name</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Outlet Name <span class="text-danger">*</span></label>
                                                        <select class="form-control outlet_id js-select1-multi" id="outlet_id" name="outlet_id" style="width: 100%;">
                                                            <option value="">Select Outlet Name</option>
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">select category Name</label>

                                                        <select class="form-control category_id js-select1-multi" id="category_id" name="category_id">   
                                                            <option value="">Select category Name</option>
                                                            
                                                            <?php
                                                                if(!empty($cat_list))
                                                                {
                                                                    foreach ($cat_list as $key => $value) {
                                                                        $category_id   = !empty($value['category_id'])?$value['category_id']:'';
                                                                        $category_name = !empty($value['category_name'])?$value['category_name']:'';

                                                                        if($state_value == $state_id)
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
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Select Sub-category Name</label>
                                                        <select class="form-control sub_cat_id js-select1-multi" id="sub_cat_id" name="sub_cat_id" style="width: 100%;">
                                                            <option value="">Select Vendor Name</option>
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">select Product </label>
                                                        <select class="form-control type_id js-select1-multi" id="type_id" name="type_id" style="width: 100%;">
                                                            <option value="">Select Vendor Name</option>
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="hidden" name="value" id="value" class="value" value="admin">
                                                        <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="report">
                                                        <input type="hidden" name="func" id="func" class="func" value="outlet_stock">
                                                        <input type="hidden" name="method" id="method" class="method" value="<?php echo $method;?>">
                                                        <button type="button" class="btn btn-primary outletstock_report outletstock_submit m-t-27" data-type="_s_c" data-value="admin" data-cntrl="report" data-func="outlet_stock">
                                                            <span class="out_stock_btn show"><i class="la la-check-square-o"></i> Submit</span>

                                                            <span class="out_stock_span hide"><i class="la la-spinner spinner"></i> Loading....</span>
                                                        </button>
                                                        <span class="excel_val"></span>
                                                        <span class="pdf_val"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 filter-design">
                                                <div class="order_value hide">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                                <!-- <tr>
                                                                    <th>#</th>
                                                                    <th>Outlet Name</th>
                                                                    <th>Product Name</th>
                                                                    <th>Opening Stock</th>
                                                                    <th>Purchese Value</th>
                                                                    <th>Employee Entry</th>
                                                                    <th>Sales</th>
                                                                    <th>Closeing Stock</th>
                                                                    <th>Unit</th>
                                                                    <th>Entry Date</th>
                                                                </tr> -->
                                                                
                                                            </thead>
                                                            <tbody id="getOrderList">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 filter-design">
                                                <div id="order_error" class="alert alert-danger text-center">
                                                    <b>No items found...</b>
                                                </div>
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
        <?php endif; ?>
        
        <?php if(userAccess('outlet-order-report-view') == FALSE): ?>
        <div class="row">
            <div class="col-sm-12 filter-design">
                <div class="alert alert-danger text-center">
                    <b class="error_msg">Access denied...</b>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
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
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Category <span class="text-danger">*</span></label>
                                                        <select class="form-control category_id js-select1-multi" id="category_id" name="category_id" style="width: 100%;">
                                                            <option value="">Category Name</option>
                                                            <?php
                                                                if(!empty($product_val))
                                                                {
                                                                    foreach ($product_val as $key => $value) {

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
                                                        <label for="projectinput1">Entry Date <span class="text-danger">*</span></label>
                                                        <input type="text" id="order_date" class="form-control order_date dates" placeholder="Entry Date" name="order_date" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-primary stock_report m-t-27" data-type="_s_c" data-value="distributors" data-cntrl="purchase" data-func="stock_entry">
                                                            <span class="order_btn show"><i class="la la-check-square-o"></i> Submit</span>

                                                            <span class="order_span hide"><i class="la la-spinner spinner"></i> Loading....</span>
                                                        </button>
                                                        <span class="sales_export"></span>
                                                        <span class="tally_export"></span>
                                                        <span class="pdf_val"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 filter-design">
                                                    <div class="stock_value hide">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Product Name</th>
                                                                        <th>Stock</th>
                                                                        <th>Damage</th>
                                                                        <th>Expiry</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="getStockList">
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 filter-design">
                                                    <div id="stock_error" class="alert alert-danger text-center">
                                                        <b>No items found...</b>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions stock_value hide">
                                                <input type="hidden" name="value" id="value" class="value" value="distributors">
                                                <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="purchase">
                                                <input type="hidden" name="func" id="func" class="func" value="stock_entry">
                                                <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                                <button type="submit" class="btn btn-primary data_submit" data-type="_s_c">
                                                    <span class="first_btn show"><i class="la la-check-square-o"></i> <?php echo $page_title; ?></span>

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
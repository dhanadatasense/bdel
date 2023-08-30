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
        <?php if(userAccess('distributor-product-stock-view') == TRUE): ?>
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
                                        <input type="hidden" name="value" id="value" class="value" value="admin">
                                        <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="report">
                                        <input type="hidden" name="func" id="func" class="func" value="distributor_stock">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Distributor Name <span class="text-danger">*</span></label>
                                                        <select class="form-control distributor_id js-select1-multi" id="distributor_id" name="distributor_id" style="width: 100%;">
                                                            <option value="">Distributor Name</option>
                                                            <?php
                                                                if(!empty($dis_val))
                                                                {
                                                                    foreach ($dis_val as $key => $value) {

                                                                        $distributor_id   = !empty($value['distributor_id'])?$value['distributor_id']:'';
                                                                        $company_name = !empty($value['company_name'])?$value['company_name']:'';
                                                                        $mobile_value = !empty($value['mobile'])?$value['mobile']:'';

                                                                        echo "<option value=".$distributor_id.">".$company_name." (".$mobile_value.")</option>";
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Category Name </label>
                                                        <select class="form-control category_id js-example-placeholder-multiple" id="category_id" name="category_id[]" multiple="multiple" style="width: 100%;">
                                                            <option value="">Select Category Name</option>
                                                        </select> 
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Sub Category Name </label>
                                                        <select class="form-control sub_cat_id js-select1-multi" id="sub_cat_id" name="sub_cat_id[]" multiple="multiple" style="width: 100%;">
                                                            <option value="">Select Sub Category Name</option>
                     
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="hidden" name="method" id="method" class="method" value="<?php echo $method;?>">
                                                        <button type="button" class="btn btn-primary rpt_submit disPdtStock_report disPdtStock_submit m-t-27" data-type="_s_c" data-value="admin" data-cntrl="report" data-func="distributor_stock">
                                                            <span class="rpt_btn show"><i class="la la-check-square-o"></i> Submit</span>

                                                            <span class="rpt_span hide"><i class="la la-spinner spinner"></i> Loading....</span>
                                                        </button>
                                                        <span class="excel_val"></span>
                                                        <span class="pdf_val"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 filter-design">
                                                <div class="disPdtStock_value hide">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Product Name</th>
                                                                    <th>Stock</th>
                                                                    <th>Minimum Stock</th>
                                                                    <th>Unit Name</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="getDisProductStockList">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 filter-design">
                                                <div id="page_error" class="alert alert-danger text-center">
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

        <?php if(userAccess('distributor-product-stock-view') == FALSE): ?>
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
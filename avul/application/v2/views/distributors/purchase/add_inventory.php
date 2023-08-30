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
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="item_table" class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Category</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Product</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Qunatity</label></th>
                                                                    <th class="p-l-0">
                                                                        <button type="button" name="remove" class="btn btn-success btn-sm add_dis_inventory button_size m-t-6"><span class="ft-plus-square"></span></button>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody  class="additemform">
                <tr class="row_1">
                    <td data-te="1" class="p-l-0 dis_product_list" style="width: 25%;">
                        <select data-te="1" name="dis_cat_id[]" id="dis_cat_id1" class="form-control dis_cat_id1 category_id js-select1-multi" style="width: 100%;">
                            <option value="">Product Name</option>
                            <?php
                                if(!empty($cat_val))
                                {
                                    foreach ($cat_val as $key => $val) {

                                        $cat_id   = !empty($val['category_id'])?$val['category_id']:'';
                                        $cat_name = !empty($val['category_name'])?$val['category_name']:'';

                                        echo "<option value=".$cat_id.">".$cat_name."</option>";

                                    }
                                }
                            ?>
                        </select> 
                    </td>
                    <td data-te="1" class="p-l-0 dis_product_list" style="width: 50%;">
                        <select data-te="1" name="dis_pdt_id[]" id="dis_pdt_id1" class="form-control dis_pdt_id1 type_id1 product_id js-select1-multi type_id" style="width: 100%;">
                            <option value="">Product Name</option>
                        </select> 
                    </td>
                    <td class="p-l-0">
                        <input type="text" data-te="1" name="dis_pdt_qty[]" id="dis_pdt_qty1" class="form-control bg-white dis_pdt_qty1 dis_pdt_qty int_value" placeholder="Quantity">

                        <input type="hidden" data-te="1" name="dis_auto_id[]" id="dis_auto_id1" class="form-control bg-white dis_auto_id1 dis_auto_id" placeholder="Quantity">
                    </td>
                    <td class="buttonlist p-l-0">
                        <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6"><span class="ft-minus-square"></span></button>
                    </td>
                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="distributors">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="purchase">
                                            <input type="hidden" name="func" id="func" class="func" value="add_inventory">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" class="method" value="<?php echo $method;?>">
                                            <input type="hidden" name="row_count" id="row_count" value="">
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
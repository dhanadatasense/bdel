<?php
    
    $product_id     = !empty($dataval[0]['product_id'])?$dataval[0]['product_id']:'0';
    $product_code   = !empty($dataval[0]['product_code'])?$dataval[0]['product_code']:'';
    $product_name   = !empty($dataval[0]['product_name'])?$dataval[0]['product_name']:'';
    $category_value = !empty($dataval[0]['category_id'])?$dataval[0]['category_id']:'';
    $vendor_value   = !empty($dataval[0]['vendor_id'])?$dataval[0]['vendor_id']:'';
    $hsn_code       = !empty($dataval[0]['hsn_code'])?$dataval[0]['hsn_code']:'';
    $price          = !empty($dataval[0]['price'])?$dataval[0]['price']:'';
    $stock          = !empty($dataval[0]['stock'])?$dataval[0]['stock']:'0';
    $vend_stock     = !empty($dataval[0]['vend_stock'])?$dataval[0]['vend_stock']:'0';
    $unit_value     = !empty($dataval[0]['unit'])?$dataval[0]['unit']:'';
    $gst            = !empty($dataval[0]['gst'])?$dataval[0]['gst']:'';
    $status         = !empty($dataval[0]['status'])?$dataval[0]['status']:'0';
    $vendor_type    = $this->session->userdata('vendor_type');

    $input_type = '';
    $stock_view = $vend_stock;

    if($vendor_type == 1)
    {
        $input_type = 'readonly';
        $stock_view = $stock;
    }

?>
<style type="text/css">
    .product_page .select2-container--default
    {
        width: 100% !important;
    }

    .bg_wht
    {
        background-color: #fff !important;
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
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Product Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="product_name" class="form-control product_name bg_wht" placeholder="Product Name" name="product_name" value="<?php echo $product_name; ?>" readonly="readonly">

                                                        <input type="hidden" id="vendor_id" class="form-control vendor_id bg_wht" placeholder="Product Name" name="vendor_id" value="<?php echo $vendor_value; ?>" readonly="readonly">

                                                        <input type="hidden" id="category_id" class="form-control category_id bg_wht" placeholder="Product Name" name="category_id" value="<?php echo $category_value; ?>" readonly="readonly">

                                                        <input type="hidden" id="unit" class="form-control unit bg_wht" placeholder="Product Name" name="unit" value="<?php echo $unit_value; ?>" readonly="readonly">

                                                        <input type="hidden" id="stock" class="form-control stock bg_wht" placeholder="Product Name" name="stock" value="<?php echo $stock; ?>" readonly="readonly">

                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Vendor Name <span class="text-danger">*</span></label>
                                                        <select class="form-control vendor_name js-select1-multi bg_wht" id="vendor_name" name="vendor_name" disabled="disabled">
                                                            <option value="">Select Vendor Name</option>
                                                            <?php
                                                                if(!empty($vendor_val))
                                                                {
                                                                    foreach ($vendor_val as $key => $value) {

                                                                        $vendor_id   = !empty($value['vendor_id'])?$value['vendor_id']:'';
                                                                        $company_name = !empty($value['company_name'])?$value['company_name']:'';

                                                                        if($vendor_value == $vendor_id)
                                                                        {
                                                                            $selected = 'selected';
                                                                        }
                                                                        else
                                                                        {
                                                                            $selected = '';
                                                                        }

                                                                        echo "<option value=".$vendor_id." ".$selected.">".$company_name."</option>";
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Category Name <span class="text-danger">*</span></label>
                                                        <select class="form-control category_name js-select1-multi bg_wht" id="category_name" name="category_name" disabled="disabled">
                                                            <option value="">Select Category Name</option>
                                                            <?php
                                                                if(!empty($category_val))
                                                                {
                                                                    foreach ($category_val as $key => $value) {

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
                                                        <label for="projectinput1">Unit Name <span class="text-danger">*</span></label>
                                                        <select class="form-control unit_name js-select1-multi bg_wht" id="unit_name" name="unit_name" disabled="disabled">
                                                            <option value="">Select Category Name</option>
                                                            <?php
                                                                if(!empty($unit_val))
                                                                {
                                                                    foreach ($unit_val as $key => $value) {

                                                                        $unit_id   = !empty($value['unit_id'])?$value['unit_id']:'';
                                                                        $unit_name = !empty($value['unit_name'])?$value['unit_name']:'';

                                                                        if($unit_value == $unit_id)
                                                                        {
                                                                            $selected = 'selected';
                                                                        }
                                                                        else
                                                                        {
                                                                            $selected = '';
                                                                        }

                                                                        echo "<option value=".$unit_id." ".$selected.">".$unit_name."</option>";
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">GST Value <span class="text-danger">*</span></label>
                                                        <input type="text" id="gst" class="form-control gst bg_wht" placeholder="GST Value" name="gst" value="<?php echo $gst; ?>" readonly="readonly">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">HSN Code <span class="text-danger">*</span></label>
                                                        <input type="text" id="hsn_code" class="form-control hsn_code bg_wht" placeholder="HSN Code" name="hsn_code" value="<?php echo $hsn_code; ?>" readonly="readonly">
                                                    </div>
                                                </div>
                                                <?php
                                                    if($vendor_type == 1)
                                                    {
                                                        ?>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="projectinput1">Price</label>
                                                                    <input type="text" id="price" class="form-control price bg_wht int_value" placeholder="Price" name="price" value="<?php echo $price; ?>">
                                                                </div>
                                                            </div>
                                                        <?php
                                                    }
                                                ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Stock</label>
                                                        <input type="text" id="vend_stock" class="form-control vend_stock bg_wht int_value" placeholder="Vend Stock" name="vend_stock" value="<?php echo $vend_stock; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="item_table" class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Description</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Product Type</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Product Unit</label></th>
                                                                    <?php
                                                                        if($vendor_type == 1)
                                                                        {
                                                                            ?>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Vendor Price</label></th>
                                                                            <?php
                                                                        }
                                                                        else
                                                                        {
                                                                            ?>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">MRP Price</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Product Price</label></th>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Product Stock</label></th>
                                                                    <th class="p-l-0">
                                                                        <button type="button" name="remove" class="btn btn-success btn-sm button_size m-t-6"><span class="ft-plus-square"></span></button>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody  class="additemform">
                                                            <?php
                                                                if($method == 'BTBM_X_U')
                                                                {
                                                                    if(!empty($type_val))
                                                                    {
                                                                        $i=1;
                                                                        foreach ($type_val as $key => $value) {
                $type_id       = !empty($value['type_id'])?$value['type_id']:'';
                $sub_code      = !empty($value['sub_code'])?$value['sub_code']:'';
                $description   = !empty($value['description'])?$value['description']:'';
                $product_type  = !empty($value['product_type'])?$value['product_type']:'';
                $product_unit  = !empty($value['product_unit'])?$value['product_unit']:'';
                $product_price = !empty($value['product_price'])?$value['product_price']:'0';
                $mrp_price     = !empty($value['mrp_price'])?$value['mrp_price']:'0';
                $ven_price     = !empty($value['ven_price'])?$value['ven_price']:'0';
                $dis_price     = !empty($value['dis_price'])?$value['dis_price']:'0';
                $product_stock = !empty($value['product_stock'])?$value['product_stock']:'0';
                $type_stock    = !empty($value['type_stock'])?$value['type_stock']:'0';

                                                                            if($i == 1)
                                                                            {
                                                                                $remove_btn = '';
                                                                            }
                                                                            else
                                                                            {
                                                                                $remove_btn = 'remove_item';
                                                                            }


                                                                            ?>
                                                                                <tr class="row_<?php echo $type_id; ?>">
                    <td class="p-l-0 productlist">
                        <input type="text" data-te="<?php echo $type_id; ?>" name="description[]" id="description<?php echo $type_id; ?>" class="form-control description<?php echo $type_id; ?> description bg_wht" placeholder="Enter the Prodct Type" value="<?php echo $description; ?>" readonly="readonly"> 

                        <input type="hidden" data-te="<?php echo $type_id; ?>" name="pro_type[]" id="pro_type<?php echo $type_id; ?>" class="form-control pro_type<?php echo $type_id; ?> pro_type bg_wht" placeholder="Enter the Prodct Type" value="<?php echo $product_type; ?>" readonly="readonly"> 

                        <input type="hidden" data-te="<?php echo $type_id; ?>" name="pro_unit[]" id="pro_unit<?php echo $type_id; ?>" class="form-control pro_unit<?php echo $type_id; ?> pro_unit bg_wht" placeholder="Enter the Prodct Type" value="<?php echo $product_unit; ?>" readonly="readonly"> 

                    </td>
                    <td data-te="1" class="p-l-0 product_page">
                        <select class="form-control product_type product_type1 js-select1-multi bg_wht" date-te="1" id="product_type1" name="product_type[]" disabled="disabled">
                            <option value="">Select Category Name</option>
                            <?php
                                if(!empty($variation_val))
                                {
                                    foreach ($variation_val as $key => $value) {

                                        $variation_id   = !empty($value['variation_id'])?$value['variation_id']:'';
                                        $variation_name = !empty($value['variation_name'])?$value['variation_name']:'';

                                        if($product_type == $variation_name)
                                        {
                                            $selected = 'selected';
                                        }
                                        else
                                        {
                                            $selected = '';
                                        }

                                        echo "<option value=".$variation_name." ".$selected.">".$variation_name."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </td>
                    <td data-te="1" class="p-l-0 product_page">
                            <select class="form-control product_unit product_unit1 js-select1-multi bg_wht" date-te="1" id="product_unit1" name="product_unit[]" disabled="disabled">
                                <option value="">Select Category Name</option>
                                <?php
                                    if(!empty($unit_val))
                                    {
                                        foreach ($unit_val as $key => $value) {

                                            $unit_id   = !empty($value['unit_id'])?$value['unit_id']:'';
                                            $unit_name = !empty($value['unit_name'])?$value['unit_name']:'';

                                            if($product_unit == $unit_id)
                                            {
                                                $selected = 'selected';
                                            }
                                            else
                                            {
                                                $selected = '';
                                            }

                                            echo "<option value=".$unit_id." ".$selected.">".$unit_name."</option>";
                                        }
                                    }
                                ?>
                            </select>
                    </td>
                    <?php
                        if($vendor_type == 1)
                        {
                            ?>
                                <td class="p-l-0">
                                    <input type="text" data-te="<?php echo $type_id; ?>" name="ven_price[]" id="ven_price<?php echo $type_id; ?>" class="form-control ven_price<?php echo $type_id; ?> ven_price bg_wht int_value" placeholder="Enter the Vendor Price" value="<?php echo $ven_price; ?>" readonly="readonly">

                                    <input type="hidden" data-te="<?php echo $type_id; ?>" name="dis_price[]" id="dis_price<?php echo $type_id; ?>" class="form-control dis_price<?php echo $type_id; ?> dis_price bg_wht int_value" placeholder="Enter the MRP" value="<?php echo $dis_price; ?>" readonly="readonly">

                                    <input type="hidden" data-te="<?php echo $type_id; ?>" name="mrp_price[]" id="mrp_price<?php echo $type_id; ?>" class="form-control mrp_price<?php echo $type_id; ?> mrp_price bg_wht int_value" placeholder="Enter the MRP" value="<?php echo $mrp_price; ?>" readonly="readonly">
                                    <input type="hidden" data-te="<?php echo $type_id; ?>" name="pro_price[]" id="pro_price<?php echo $type_id; ?>" class="form-control pro_price<?php echo $type_id; ?> pro_price bg_wht int_value" placeholder="Enter the Price" value="<?php echo $product_price; ?>" readonly="readonly">
                                </td>
                            <?php
                        }
                        else
                        {
                            ?>
                                <td class="p-l-0">
                                    <input type="text" data-te="<?php echo $type_id; ?>" name="mrp_price[]" id="mrp_price<?php echo $type_id; ?>" class="form-control mrp_price<?php echo $type_id; ?> mrp_price bg_wht int_value" placeholder="Enter the MRP" value="<?php echo $mrp_price; ?>" readonly="readonly">
                                </td>
                                <td class="p-l-0">
                                    <input type="text" data-te="<?php echo $type_id; ?>" name="pro_price[]" id="pro_price<?php echo $type_id; ?>" class="form-control pro_price<?php echo $type_id; ?> pro_price bg_wht int_value" placeholder="Enter the Price" value="<?php echo $product_price; ?>" readonly="readonly">
                                </td>
                            <?php
                        }
                    ?>
                    <td class="p-l-0">
                        <input type="text" class="form-control bg_wht" placeholder="Enter the Stock" value="<?php echo $type_stock; ?>" readonly="readonly"> 

                        <input type="hidden" data-te="<?php echo $type_id; ?>" name="type_stock[]" id="type_stock<?php echo $type_id; ?>" class="form-control type_stock<?php echo $type_id; ?> type_stock bg_wht int_value" placeholder="Enter the Stock" value="<?php echo $type_stock; ?>"> 

                        <input type="hidden" data-te="<?php echo $type_id; ?>" name="pro_stock[]" id="pro_stock<?php echo $type_id; ?>" class="form-control pro_stock<?php echo $type_id; ?> pro_stock bg_wht" placeholder="Enter the Price" value="<?php echo $product_stock; ?>" readonly="readonly">

                        <input type="hidden" data-te="<?php echo $type_id; ?>" name="type_id[]" id="type_id<?php echo $type_id; ?>" class="form-control type_id<?php echo $type_id; ?> type_id bg_wht" placeholder="Enter the Price" value="<?php echo $type_id; ?>" readonly="readonly"> 

                        <input type="hidden" data-te="<?php echo $type_id; ?>" name="sub_code[]" id="sub_code<?php echo $type_id; ?>" class="form-control sub_code<?php echo $type_id; ?> sub_code bg_wht" placeholder="Enter the Price" value="<?php echo $sub_code; ?>" readonly="readonly"> 
                    </td>
                    <td class="buttonlist p-l-0">
                        <button type="button" name="remove" class="btn btn-danger btn-sm button_size m-t-6 <?php echo $remove_btn; ?>"><span class="ft-minus-square"></span></button>
                    </td>
                                                                                </tr>
                                                                            <?php
                                                                            $i++;
                                                                        } 
                                                                    }
                                                                }
                                                                else
                                                                {
                                                                    ?>
                    <tr class="row_1">
                        <td data-te="1" class="p-l-0">
                            <input type="text" data-te="1" name="description[]" id="description1" class="form-control description1 description" placeholder="Description">
                        </td>
                        <td data-te="1" class="p-l-0 product_page">
                            <select class="form-control pro_type pro_type1 js-select1-multi" date-te="1" id="pro_type1" name="pro_type[]">
                                <option value="">Select Variation Name</option>
                                <?php
                                    if(!empty($variation_val))
                                    {
                                        foreach ($variation_val as $key => $value) {

                                            $variation_id   = !empty($value['variation_id'])?$value['variation_id']:'';
                                            $variation_name = !empty($value['variation_name'])?$value['variation_name']:'';

                                            echo "<option value=".$variation_name.">".$variation_name."</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </td>
                        <td data-te="1" class="p-l-0 product_page">
                            <select class="form-control pro_unit pro_unit1 js-select1-multi" date-te="1" id="pro_unit1" name="pro_unit[]">
                                <option value="">Select Category Name</option>
                                <?php
                                    if(!empty($unit_val))
                                    {
                                        foreach ($unit_val as $key => $value) {

                                            $unit_id   = !empty($value['unit_id'])?$value['unit_id']:'';
                                            $unit_name = !empty($value['unit_name'])?$value['unit_name']:'';

                                            echo "<option value=".$unit_id.">".$unit_name."</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </td>
                        <td class="p-l-0">
                            <input type="text" data-te="1" name="mrp_price[]" id="mrp_price1" class="form-control mrp_price1 mrp_price" placeholder="Enter the MRP"> 
                        </td>
                        <td class="p-l-0">
                            <input type="text" data-te="1" name="pro_price[]" id="pro_price1" class="form-control pro_price1 pro_price" placeholder="Enter the Price"> 
                        </td>
                        <td class="p-l-0">
                            <input type="text" data-te="1" name="pro_stock[]" id="pro_stock1" class="form-control pro_stock1 pro_stock" placeholder="Enter the Stock" value="0">

                            <input type="hidden" data-te="1" name="type_id[]" id="type_id1" class="form-control type_id1 type_id" placeholder="Enter the Price">
                        </td>
                        <td class="buttonlist p-l-0">
                            <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6"><span class="ft-minus-square"></span></button>
                        </td>
                    </tr>
                                                                    <?php
                                                                }
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="product_id" id="product_id" class="product_id" value="<?php echo $product_id; ?>">
                                            <input type="hidden" name="product_code" id="product_code" class="product_code" value="<?php echo $product_code; ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="vendors">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="catlog">
                                            <input type="hidden" name="func" id="func" class="func" value="add_product">
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
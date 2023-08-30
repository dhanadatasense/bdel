<?php

    $product_id     = !empty($dataval[0]['product_id'])?$dataval[0]['product_id']:'0';
    $product_code   = !empty($dataval[0]['product_code'])?$dataval[0]['product_code']:'';
    $unique_code    = !empty($dataval[0]['unique_code'])?$dataval[0]['unique_code']:'';
    $product_name   = !empty($dataval[0]['product_name'])?$dataval[0]['product_name']:'';
    $category_value = !empty($dataval[0]['category_id'])?$dataval[0]['category_id']:'';
    $sub_cat_value  = !empty($dataval[0]['sub_cat_id'])? $dataval[0]['sub_cat_id']:'';
    $vendor_value   = !empty($dataval[0]['vendor_id'])?$dataval[0]['vendor_id']:'';
    $hsn_code       = !empty($dataval[0]['hsn_code'])?$dataval[0]['hsn_code']:'';
    $price          = !empty($dataval[0]['price'])?$dataval[0]['price']:'';
    $stock          = !empty($dataval[0]['stock'])?$dataval[0]['stock']:'0';
    $vend_stock     = !empty($dataval[0]['vend_stock'])?$dataval[0]['vend_stock']:'0';
    $unit_value     = !empty($dataval[0]['unit'])?$dataval[0]['unit']:'';
    $gst            = !empty($dataval[0]['gst'])?$dataval[0]['gst']:'';
    $product_img    = !empty($dataval[0]['product_img'])?$dataval[0]['product_img']:'';
    $status         = !empty($dataval[0]['status'])?$dataval[0]['status']:'0';

    if(!empty($product_img))
    {
        $pdt_img = FILE_URL.'product/'.$product_img;
    }
    else
    {
        $pdt_img = BASE_URL.'app-assets/images/img_icon.png';
    }
?>
<style type="text/css">
    .product_page .select2-container--default
    {
        width: 100% !important;
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
                    <?php if($page_access): ?>
                    <a class="btn btn-info round px-2 mb-1" href="<?php echo BASE_URL.$pre_menu ?>"><i class="ft-plus-square"></i> <?php echo $pre_title; ?></a>
                    <?php endif; ?>
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
                                                        <input type="text" id="product_name" class="form-control product_name" placeholder="Product Name" name="product_name" value="<?php echo $product_name; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Manufacture Name <span class="text-danger">*</span></label>
                                                        <select class="form-control vendor_id js-select1-multi" id="vendor_id" name="vendor_id">
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
                                                        <select class="form-control category_id js-select1-multi" id="category_id" name="category_id">
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
                                                        <label for="projectinput1">Sub Category <span class ="text-danger">*</span></label>
                                                        <select class="form-control sub_cat_id js-select1-multi" id="sub_cat_id" name="sub_cat_id" style="width: 100%;">
                                                            <option value="">Select Sub Category Name</option>
                                                            <?php
                                                                if(!empty($sub_cat_val))
                                                                {
                                                                    foreach ($sub_cat_val as $key => $value) {

                                                                        $id   = !empty($value['id'])?$value['id']:'';
                                                                        $sub_cat_name = !empty($value['sub_cat_name'])?$value['sub_cat_name']:'';

                                                                        if($sub_cat_value == $id)
                                                                        {
                                                                            $selected = 'selected';
                                                                        }
                                                                        else
                                                                        {
                                                                            $selected = '';
                                                                        }

                                                                        echo "<option value=".$id." ".$selected.">".$sub_cat_name."</option>";
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
                                                            <label for="projectinput1">Unit Name <span class="text-danger">*</span></label>
                                                            <select class="form-control unit js-select1-multi" id="unit" name="unit">
                                                                <option value="">Select Category Name</option>
                                                                <?php
                                                                    if(!empty($unit_val))
                                                                    {
                                                                        foreach ($unit_val as $key => $value) {

                                                                            $unit_id   = !empty($value['unit_id'])?$value['unit_id']:'';
                                                                            $unit_name = !empty($value['unit_name'])?$value['unit_name']:'';

                                                                            if($unit_id != '2')
                                                                            {
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
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">GST Value <span class="text-danger">*</span></label>
                                                        <input type="text" id="gst" class="form-control gst" placeholder="GST Value" name="gst" value="<?php echo $gst; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">HSN Code <span class="text-danger">*</span></label>
                                                        <input type="text" id="hsn_code" class="form-control hsn_code" placeholder="HSN Code" name="hsn_code" value="<?php echo $hsn_code; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">

                                                        <input type="hidden" id="price" class="form-control price" placeholder="Price" name="price" value="<?php echo $price; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">

                                                        <input type="hidden" id="stock" class="form-control stock" placeholder="Stock" name="stock" value="<?php echo $stock; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">

                                                        <input type="hidden" id="vend_stock" class="form-control vend_stock" placeholder="Vendor Stock" name="vend_stock" value="<?php echo $vend_stock; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Unique code</label>
                                                        <input type="text" id="unique_code" class="form-control unique_code" placeholder="Unique code" name="unique_code" value="<?php echo $unique_code; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-1">
                                                        <div class="d-flex">
                                                            <a href="#" class="me-25">
                                                            <img src="<?php echo $pdt_img; ?>" id="upload_qr_img" class="uploadedQr rounded me-50" alt="profile image" height="100" width="100"/>
                                                            </a>
                                                            <div class="d-flex align-items-end mt-75 ms-1" style="margin-left: 10px;">
                                                                <div>
                                                                    <label for="upload_qr" class="btn btn-sm btn-primary mb-75 me-75 file_upload">Upload</label>
                                                                    <input type="file" id="upload_qr" name="image[]" class="" hidden accept="image/*" />
                                                                    <p class="mb-0">Allowed file types: png, jpg, jpeg.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">MRP Price</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Product Price</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Purchase Price</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Sales Price</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Product Stock</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Minimum Stock</label></th>
                                                                    <th class="p-l-0">
                                                                        <button type="button" name="remove" class="btn btn-success btn-sm add_items button_size m-t-6"><span class="ft-plus-square"></span></button>
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
$minimum_stock = !empty($value['minimum_stock'])?$value['minimum_stock']:'0';

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
        <input type="text" data-te="<?php echo $type_id; ?>" name="description[]" id="description<?php echo $type_id; ?>" class="form-control description<?php echo $type_id; ?> description" placeholder="Prodct Type" value="<?php echo $description; ?>"> 
    </td>
    <td data-te="1" class="p-l-0 product_page">
            <select class="form-control pro_type pro_type1 js-select1-multi" date-te="1" id="pro_type1" name="pro_type[]">
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
            <select class="form-control pro_unit pro_unit1 js-select1-multi" date-te="1" id="pro_unit1" name="pro_unit[]">
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
    <td class="p-l-0">
        <input type="text" data-te="<?php echo $type_id; ?>" name="mrp_price[]" id="mrp_price<?php echo $type_id; ?>" class="form-control mrp_price<?php echo $type_id; ?> mrp_price int_value" placeholder="MRP" value="<?php echo $mrp_price; ?>">
    </td>
    <td class="p-l-0">
        <input type="text" data-te="<?php echo $type_id; ?>" name="pro_price[]" id="pro_price<?php echo $type_id; ?>" class="form-control pro_price<?php echo $type_id; ?> pro_price int_value" placeholder="Price" value="<?php echo $product_price; ?>">
    </td>
    <td class="p-l-0">
        <input type="text" data-te="<?php echo $type_id; ?>" name="ven_price[]" id="ven_price<?php echo $type_id; ?>" class="form-control ven_price<?php echo $type_id; ?> ven_price int_value" placeholder="Price" value="<?php echo $ven_price; ?>">
    </td>
    <td class="p-l-0">
        <input type="text" data-te="<?php echo $type_id; ?>" name="dis_price[]" id="dis_price<?php echo $type_id; ?>" class="form-control dis_price<?php echo $type_id; ?> dis_price int_value" placeholder="Price" value="<?php echo $dis_price; ?>">
    </td>
    <td class="p-l-0">
        <input type="text" data-te="<?php echo $type_id; ?>" name="pro_stock[]" id="pro_stock<?php echo $type_id; ?>" class="form-control pro_stock<?php echo $type_id; ?> pro_stock int_value" placeholder="Stock" value="<?php echo $product_stock; ?>"> 

        <input type="hidden" data-te="<?php echo $type_id; ?>" name="type_id[]" id="type_id<?php echo $type_id; ?>" class="form-control type_id<?php echo $type_id; ?> type_id" placeholder="Price" value="<?php echo $type_id; ?>"> 

        <input type="hidden" data-te="<?php echo $type_id; ?>" name="sub_code[]" id="sub_code<?php echo $type_id; ?>" class="form-control sub_code<?php echo $type_id; ?> sub_code" placeholder="Price" value="<?php echo $sub_code; ?>"> 

        <input type="hidden" data-te="<?php echo $type_id; ?>" name="type_stock[]" id="type_stock<?php echo $type_id; ?>" class="form-control type_stock<?php echo $type_id; ?> type_stock" placeholder="Enter the Price" value="<?php echo $type_stock; ?>">
    </td>
    <td class="p-l-0">
        <input type="text" data-te="<?php echo $type_id; ?>" name="minimum_stock[]" id="minimum_stock<?php echo $type_id; ?>" class="form-control minimum_stock<?php echo $type_id; ?> minimum_stock int_value" placeholder="Price" value="<?php echo $minimum_stock; ?>">
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
        <input type="text" data-te="1" name="mrp_price[]" id="mrp_price1" class="form-control mrp_price1 mrp_price" placeholder="MRP" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"> 
    </td>
    <td class="p-l-0">
        <input type="text" data-te="1" name="pro_price[]" id="pro_price1" class="form-control pro_price1 pro_price" placeholder="Price" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"> 
    </td>
    <td class="p-l-0">
        <input type="text" data-te="1" name="ven_price[]" id="ven_price1" class="form-control ven_price1 ven_price" placeholder="Vendor Price" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"> 
    </td>
    <td class="p-l-0">
        <input type="text" data-te="1" name="dis_price[]" id="dis_price1" class="form-control dis_price1 dis_price" placeholder="Distributor Price" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"> 
    </td>
    <td class="p-l-0">
        <input type="text" data-te="1" name="pro_stock[]" id="pro_stock1" class="form-control pro_stock1 pro_stock" placeholder="Stock" value="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">

        <input type="hidden" data-te="1" name="type_id[]" id="type_id1" class="form-control type_id1 type_id" placeholder="Price">

        <input type="hidden" data-te="1" name="type_stock[]" id="type_stock1" class="form-control type_stock1 type_stock" placeholder="Enter the Price">
    </td>
    <td class="p-l-0">
        <input type="text" data-te="1" name="minimum_stock[]" id="minimum_stock1" class="form-control minimum_stock1 minimum_stock" value="0" placeholder="Minimum Stock" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"> 
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
                                        </div>
                                        <div class="form-actions">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="product_id" id="product_id" class="product_id" value="<?php echo $product_id; ?>">
                                            <input type="hidden" name="product_code" id="product_code" class="product_code" value="<?php echo $product_code; ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
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
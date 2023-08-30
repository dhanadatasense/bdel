<?php
    $role_data  = !empty($role_list)?$role_list:'';

    $role_id      = !empty($dataval['role_id'])?$dataval['role_id']:'';
    $role_name    = !empty($dataval['role_name'])?$dataval['role_name']:'';
    $role_heading = !empty($dataval['role_heading'])?$dataval['role_heading']:'';
    $role_result  = !empty($dataval['role_list'])?$dataval['role_list']:'';
    $status       = !empty($dataval['status'])?$dataval['status']:'0';

    $c_role_heading = explode(',', $role_heading);
    $c_role_result  = explode(',', $role_result);
?>

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
                                                 <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">User Role <span class="text-danger">*</span></label>
                                                        <input type="text" id="role_name" class="form-control role_name" placeholder="User Role" name="role_name" value="<?php echo $role_name; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="table-responsive mb-2">
                                                    <table class="table table-responsive-md">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:50px;">
                                                                    <div class="form-check form-check-success">
                                                                        <input type="checkbox" class="form-check-input" id="checkAll" style="margin-top: -15px;" />
                                                                    </div>
                                                                </th>
                                                                <th><strong>Headers</strong></th>
                                                                <th><strong>Module</strong></th>
                                                                <th><strong>Add</strong></th>
                                                                <th><strong>Edit</strong></th>
                                                                <th><strong>View</strong></th>
                                                                <th><strong>Delete</strong></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                if($role_data)
                                                                {
                                                                    foreach ($role_data as $key => $val_1) {

            $crud_id        = empty_check($val_1['privilege_id']);
            $crud_header    = empty_check($val_1['header']);
            $crud_name      = empty_check($val_1['privilege_name']);
            $crud_code      = empty_check($val_1['short_code']);
            $privilege_type = empty_check($val_1['privilege_type']);

            $privilege_exp  = explode(',', $privilege_type);

            ?>
                <tr>
                    <td>
                        <div class="form-check form-check-success">
                            <input data-item="<?php echo $crud_id; ?>" type="checkbox" name="heading_val[]" value="<?php echo $crud_code; ?>" class="accessModule form-check-input" <?php echo in_array($crud_code, $c_role_heading) ? 'checked' : ''; ?> />
                        </div>
                    </td>
                    <td><span><?php echo $crud_header; ?></span></td>
                    <td><span><?php echo $crud_name; ?></span></td>
                    <td>
                        <?php if(in_array('1', $privilege_exp)){ ?>
                        <div class="form-check form-check-success">
                            <input type="checkbox" name="check_val[]" value="<?php echo $crud_code; ?>-add" class="form-check-input <?php echo $crud_code; ?>Chk checkVal data_<?php echo $crud_id; ?>" <?php echo in_array($crud_code.'-add', $c_role_result) ? 'checked' : ''; ?> />
                        </div>
                        <?php } else {echo '<i class="icon-close"></i>';} ?>
                    </td>
                    <td>    
                        <?php if(in_array('2', $privilege_exp)){ ?>
                        <div class="form-check form-check-success">
                            <input type="checkbox" name="check_val[]" value="<?php echo $crud_code; ?>-edit" class="form-check-input <?php echo $crud_code; ?>Chk checkVal data_<?php echo $crud_id; ?>" <?php echo in_array($crud_code.'-edit', $c_role_result) ? 'checked' : ''; ?> />
                        </div>
                        <?php } else {echo '<i class="icon-close"></i>';} ?>
                    </td>
                    <td>
                        <?php if(in_array('3', $privilege_exp)){ ?>
                        <div class="form-check form-check-success">
                            <input type="checkbox" name="check_val[]" value="<?php echo $crud_code; ?>-view" class="form-check-input <?php echo $crud_code; ?>Chk checkVal data_<?php echo $crud_id; ?>" <?php echo in_array($crud_code.'-view', $c_role_result) ? 'checked' : ''; ?> />
                        </div>
                        <?php } else {echo '<i class="icon-close"></i>';} ?>

                    </td>
                    <td>
                        <?php if(in_array('4', $privilege_exp)){ ?>
                        <div class="form-check form-check-success">
                            <input type="checkbox" name="check_val[]" value="<?php echo $crud_code; ?>-delete" class="form-check-input <?php echo $crud_code; ?>Chk checkVal data_<?php echo $crud_id; ?>" <?php echo in_array($crud_code.'-delete', $c_role_result) ? 'checked' : ''; ?> />
                        </div>
                        <?php } else {echo '<i class="icon-close"></i>';} ?>
                    </td>
                </tr>
            <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
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
                                        <div class="form-actions submit_btn">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="role_id" id="role_id" class="role_id" value="<?php echo $role_id; ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="user">
                                            <input type="hidden" name="func" id="func" class="func" value="add_role">
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
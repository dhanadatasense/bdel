<?php
    $user_id    = !empty($dataval[0]['user_id'])?$dataval[0]['user_id']:'0';
    $username   = !empty($dataval[0]['username'])?$dataval[0]['username']:'';
    $mobile     = !empty($dataval[0]['mobile'])?$dataval[0]['mobile']:'';
    $email      = !empty($dataval[0]['email'])?$dataval[0]['email']:'';
    $address    = !empty($dataval[0]['address'])?$dataval[0]['address']:'';
    $password   = !empty($dataval[0]['password'])?$dataval[0]['password']:'';
    $user_role  = !empty($dataval[0]['user_role'])?$dataval[0]['user_role']:'';
    $status     = !empty($dataval[0]['status'])?$dataval[0]['status']:'0';
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
                                                        <label for="projectinput1">Username <span class="text-danger">*</span></label>
                                                        <input type="text" id="email" class="form-control email c_username" placeholder="Username" name="email" value="<?php echo $email; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Mobile Number</label>
                                                        <input type="text" id="mobile" class="form-control mobile int_value" placeholder="Mobile Number" name="mobile" value="<?php echo $mobile; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">User Role <span class="text-danger">*</span></label>
                                                        <select class="form-control user_role js-select1-multi" id="user_role" name="user_role">
                        <option value="">Select User Role</option>
                        <?php
                            if(!empty($role_list))
                            {
                                foreach ($role_list as $key => $value) {

                                    $role_id   = !empty($value['role_id'])?$value['role_id']:'';
                                    $role_name = !empty($value['role_name'])?$value['role_name']:'';

                                    if($user_role == $role_id)
                                    {
                                        $selected = 'selected';
                                    }
                                    else
                                    {
                                        $selected = '';
                                    }

                                    echo "<option value=".$role_id." ".$selected.">".$role_name."</option>";
                                }
                            }
                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Password <span class="text-danger">*</span></label>
                                                        <input type="password" id="password" class="form-control password" placeholder="Password" name="password" value="<?php echo $password; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Address <span class="text-danger">*</span></label>
                                                        <textarea id="address" class="form-control address" placeholder="Address" name="address" rows="3"><?php echo $address; ?></textarea>
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
                                            <input type="hidden" name="user_id" id="user_id" class="user_id" value="<?php echo $user_id; ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="user">
                                            <input type="hidden" name="func" id="func" class="func" value="add_user">
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
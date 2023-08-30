<?php
    $privilege_id   = !empty($dataval[0]['privilege_id'])?$dataval[0]['privilege_id']:'0';
    $header         = !empty($dataval[0]['header'])?$dataval[0]['header']:'';
    $privilege_name = !empty($dataval[0]['privilege_name'])?$dataval[0]['privilege_name']:'';
    $privilege_type = !empty($dataval[0]['privilege_type'])?$dataval[0]['privilege_type']:'';
    $status         = !empty($dataval[0]['status'])?$dataval[0]['status']:'0';
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
                                                        <label for="projectinput1">Header <span class="text-danger">*</span></label>
                                                        <input type="text" id="header" class="form-control header" placeholder="Header" name="header" value="<?php echo $header; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Privilege Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="privilege_name" class="form-control privilege_name" placeholder="Privilege Name" name="privilege_name" value="<?php echo $privilege_name; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Privilege Type <span class="text-danger">*</span></label>
                                                        <select class="form-control privilege_type js-select1-multi" id="privilege_type" name="privilege_type[]" multiple="multiple">
                                                            <option value="">Select Value</option>

                <?php
                    if($method == 'BTBM_X_U')
                    {
                        $privilege_val = array('Add', 'Edit', 'View', 'Delete');
                        $privilege_cnt = count($privilege_val);

                        for ($i=0; $i < $privilege_cnt; $i++) { 
                            
                            $privilege_sel = '';
                            $privilege_res = $i + 1;
                            $privilege_ans = explode(',', $privilege_type);
                            if(in_array($privilege_res, $privilege_ans))
                            {
                                $privilege_sel = 'selected';
                            }

                            echo '<option value="'.$privilege_res.'" '.$privilege_sel.'>'.$privilege_val[$i].'</option>';
                        }

                    }
                    else
                    {
                        ?>
                            <option value="1">Add</option>
                            <option value="2">Edit</option>
                            <option value="3">View</option>
                            <option value="4">Delete</option>
                        <?php
                    }
                ?>
                                                        </select>
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
                                            <input type="hidden" name="privilege_id" id="privilege_id" class="privilege_id" value="<?php echo $privilege_id; ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="masters">
                                            <input type="hidden" name="func" id="func" class="func" value="add_privilege">
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

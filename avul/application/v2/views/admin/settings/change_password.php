<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title"><?php echo $page_title; ?></h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <!-- <li class="breadcrumb-item"><a href="#"><?php echo $main_heading; ?></a>
                            </li> -->
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
                                                        <label for="projectinput1">Old Password <span class="text-danger">*</span></label>
                                                        <input type="text" id="old_password" class="form-control old_password" placeholder="Old Password" name="old_password" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">New Password <span class="text-danger">*</span></label>
                                                        <input type="password" id="password" class="form-control password" placeholder="New Password" name="password" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Confirm Password <span class="text-danger">*</span></label>
                                                        <input type="password" id="confirm_password" class="form-control confirm_password" placeholder="Confirm Password" name="confirm_password" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="settings">
                                            <input type="hidden" name="func" id="func" class="func" value="change_password">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" class="method" value="<?php echo $method;?>">
                                            <button type="submit" class="btn btn-primary data_submit" data-type="_s_c">
                                                <span class="first_btn show"><i class="fa fa-check-square-o"></i> <?php echo $page_title; ?></span>

                                                <span class="span_btn hide"><i class="fa fa-spinner spinner"></i> Loading....</span>
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
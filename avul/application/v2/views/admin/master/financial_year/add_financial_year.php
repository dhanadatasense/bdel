<?php
$financial_id   = !empty($dataval[0]['financial_id']) ? $dataval[0]['financial_id'] : '0';
$financial_name = !empty($dataval[0]['financial_name']) ? $dataval[0]['financial_name'] : '';
$start_date     = !empty($dataval[0]['start_date']) ? $dataval[0]['start_date'] : '';
$end_date       = !empty($dataval[0]['end_date']) ? $dataval[0]['end_date'] : '';
$status         = !empty($dataval[0]['status']) ? $dataval[0]['status'] : '0';
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
                    <?php if ($page_access) : ?>
                        <a class="btn btn-info px-2 mb-1" href="<?php echo BASE_URL . $pre_menu ?>"><i class="ft-plus-square"></i> <?php echo $pre_title; ?></a>
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
                                                        <label for="projectinput1">Financial Year Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="financial_name" class="form-control financial_name" placeholder="Financial Name" name="financial_name" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*?)\..*/g, '$1');" value="<?php echo $financial_name; ?>" maxlength="9">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Start Date <span class="text-danger">*</span></label>
                                                        <input type="text" id="start_date" class="form-control start_date atdates " aceholder="Start Date" name="start_date" value="<?php echo $start_date; ?>" maxlength="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">End Date <span class="text-danger">*</span></label>
                                                        <input type="text" id="end_date" class="form-control end_date atdates " placeholder="End Date" name="end_date" value="<?php echo $end_date; ?>" maxlength="0">
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            if ($method == 'BTBM_X_U') {
                                            ?>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>Status <span class="text-danger">*</span></label><br>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="postType1" name="pstatus" class="custom-control-input" <?php echo $status == 1 ? 'checked' : ''; ?> value="1">
                                                                <label class="custom-control-label" for="postType1">Active</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="postType2" name="pstatus" class="custom-control-input" <?php echo $status == 0 ? 'checked' : ''; ?> value="0">
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
                                            <input type="hidden" name="financial_id" id="financial_id" class="financial_id" value="<?php echo $financial_id; ?>">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="masters">
                                            <input type="hidden" name="func" id="func" class="func" value="add_financial_year">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" class="method" value="<?php echo $method; ?>">
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
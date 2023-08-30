<?php
    
    $collateral_id = !empty($dataval['id'])?$dataval['id']:'';
    $name          = !empty($dataval['name'])?$dataval['name']:'';
    $type          = !empty($dataval['type'])?$dataval['type']:'';
    $start_date    = !empty($dataval['start_date'])?$dataval['start_date']:'';
    $end_date      = !empty($dataval['end_date'])?$dataval['end_date']:'';
    $file          = !empty($dataval['file'])?$dataval['file']:'';
    $description   = !empty($dataval['description'])?$dataval['description']:'';
    $status        = !empty($dataval['status'])?$dataval['status']:'';

    $data_display  = 'hide';
    $data_disabled = 'disabled';

    if($method == '_editCollaterals' && $type == 2)
    {
        $data_display  = 'contents';
        $data_disabled = '';
        
    }
?>
    
<style type="text/css">
    .date-picker
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
                                    <form class="form_data" name="form_data" method="post">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Collateral Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="name" class="form-control name" placeholder="Collateral Name" name="name" value="<?php echo $name; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Collateral Type <span class="text-danger">*</span></label>
                                                        <select class="form-control collateral_type js-select1-multi" id="collateral_type" name="type">
                                                            <option value="">Select Value</option>
                                                            <option value="1" <?php echo $type == 1 ? 'selected' : ''; ?>>Permanent</option>
                                                            <option value="2" <?php echo $type == 2 ? 'selected' : ''; ?>>Temporary</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Start Date <span class="text-danger required <?php echo $data_display; ?>">*</span></label>
                                                        <input type="text" id="start_date" class="form-control start_date atdates date-picker" placeholder="Start Date" name="start_date" value="<?php echo $start_date; ?>" <?php echo $data_disabled; ?>>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">End Date <span class="text-danger required <?php echo $data_display; ?>">*</span></label>
                                                        <input type="text" id="end_date" class="form-control end_date atdates date-picker" placeholder="End Date" name="end_date" value="<?php echo $end_date; ?>" <?php echo $data_disabled; ?>>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Description <span class="text-danger">*</span></label>
                                                        <textarea id="description" class="form-control description editor" placeholder="Description" name="description" rows="3"><?php echo $description; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group getCertificate">
                                                        <label for="projectinput1">File <span class="text-danger">*</span></label>
                                                        <input type="file" id="file" class="form-control file" placeholder="MSME Certificate" name="file" value="" style="padding: 0px;border: 0px;">
                                                        <?php if($file): ?>
                                                        <code for="projectinput1"><?php echo $file; ?></code>
                                                        <?php endif; ?>
                                                        <label class="" style="display: inherit; margin-top: 10px;">Allowed file types: jpg, jpeg, png, gif, doc, pdf, mp4, mov, wmv, avi, avchd, mkv</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                                if($method == '_editCollaterals')
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
                                            <input type="hidden" name="loged_by" id="loged_by" class="loged_by" value="<?php echo $this->session->userdata('id'); ?>">
                                            <input type="hidden" name="pre_menu" id="pre_menu" class="pre_menu" value="<?php echo $pre_menu ?>">
                                            <input type="hidden" name="c_id" id="c_id" class="c_id" value="<?php echo $collateral_id; ?>">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                            <input type="hidden" name="method" class="method" value="<?php echo $method;?>">
                                            <input type="hidden" name="submit_url" class="submit_url" id="submit_url" value="<?php echo $submit_url;?>">

                                            <button type="submit" class="btn btn-primary form_submit" data-type="_s_c">
                                                <span class="first_btn show"><i class="la la-check-square-o"></i> Submit</span>

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
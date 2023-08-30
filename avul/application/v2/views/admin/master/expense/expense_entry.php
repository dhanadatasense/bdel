<style type="text/css">
    .expense_list .select2-container--default
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
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="item_table" class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Employee Name</label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Expense Name <span class="text-danger">*</span></label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Date <span class="text-danger">*</span></label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Type <span class="text-danger">*</span></label></th>
                                                                    <th class="p-l-0 f-w-500"><label for="projectinput1">Value <span class="text-danger">*</span></label></th>
                                                                    <th class="p-l-0">
                                                                        <button type="button" name="remove" class="btn btn-success btn-sm add_expense button_size m-t-6"><span class="ft-plus-square"></span></button>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody  class="addExpenseform">
                                                                <tr class="row_1">
                        <td data-te="1" class="p-l-0 expense_list" style="width: 25%;">
                            <select data-te="1" name="employee_id[]" id="employee_id1" class="form-control employee_id1 employee_id js-select1-multi" data-te="1">
                                <option value="">Select Product Name</option>
                                <?php
                                    if (!empty($emp_list))
                                    {
                                        foreach ($emp_list as $key => $value_1)
                                        {
                                            $employee_id = !empty($value_1['employee_id'])?$value_1['employee_id']:'';
                                            $username    = !empty($value_1['username'])?$value_1['username']:'';
                                            $mobile      = !empty($value_1['mobile'])?$value_1['mobile']:'';

                                            echo '<option value="'.$employee_id.'">'.$username.' ('.$mobile.')'.'</option>';
                                        }
                                    }
                                ?>
                            </select> 
                        </td>
                        <td data-te="1" class="p-l-0 expense_list" style="width: 25%;">
                            <select data-te="1" name="expense_id[]" id="expense_id1" class="form-control expense_id1 expense_id js-select1-multi" data-te="1">
                                <option value="">Select Product Name</option>
                                <?php
                                    if (!empty($exp_list))
                                    {
                                        foreach ($exp_list as $key => $value_2)
                                        {
                                            $exp_id   = !empty($value_2['expense_id'])?$value_2['expense_id']:'';
                                            $exp_name = !empty($value_2['expense_name'])?$value_2['expense_name']:'';

                                            echo '<option value="'.$exp_id.'">'.$exp_name.'</option>';
                                        }
                                    }
                                ?>
                            </select> 
                        </td>
                        <td class="p-l-0">
                            <input type="text" data-te="1" name="expense_date[]" id="expense_date1" class="form-control expense_date1 expense_date dateselecter" placeholder="Expense Date">
                        </td>
                        <td data-te="1" class="p-l-0 expense_list">
                            <select data-te="1" name="expense_type[]" id="expense_type1" class="form-control expense_type1 expense_type js-select1-multi" data-te="1">
                                <option value="">Select Product Name</option>
                                <option value="1">Cash</option>
                                <option value="2">Bank</option>
                            </select> 
                        </td>
                                                                    <td class="p-l-0">
                                                                        <input type="text" data-te="1" name="expense_val[]" id="expense_val1" class="form-control expense_val1 expense_val int_value" placeholder="Price">

                                                                        <input type="hidden" data-te="1" name="auto_id[]" id="auto_id1" class="form-control auto_id1 auto_id" value="1">
                                                                    </td>
                                                                    <td class="buttonlist p-l-0">
                                                                        <button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6"><span class="ft-minus-square"></span></button>
                                                                    </td>
                                                                </tr>
                                                                <tr class="row_1">
                                                                    <td class="p-l-0" colspan="6">
                                                                        <textarea id="expense_desc" class="form-control expense_desc" placeholder="Expense Description" name="expense_desc[]" rows="2"></textarea>
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
                                            <input type="hidden" name="outlets_id" id="outlets_id" class="outlets_id" value="">
                                            <input type="hidden" name="value" id="value" class="value" value="admin">
                                            <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="masters">
                                            <input type="hidden" name="func" id="func" class="func" value="expense_entry">
                                            <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
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
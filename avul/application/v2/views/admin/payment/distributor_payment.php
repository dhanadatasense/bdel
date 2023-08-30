<style type="text/css">
    .pt-0
    {
        padding-top: 0px;
    }   

    .zone_list .select2-container--default
    {
        width: 99% !important;
    } 

    .btn[disabled]
    {
        cursor: not-allowed;
        box-shadow: none;
        opacity: .65;
    }

    .bg-wht
    {
        background-color: #fff !important;
    }

</style>
<?php
    $auto_id      = !empty($pay_val['auto_id'])?$pay_val['auto_id']:'';
    $payment_id   = !empty($pay_val['payment_id'])?$pay_val['payment_id']:'';
    $amount       = !empty($pay_val['amount'])?$pay_val['amount']:'';
    $discount     = !empty($pay_val['discount'])?$pay_val['discount']:'0';
    $bank_name    = !empty($pay_val['bank_name'])?$pay_val['bank_name']:'';
    $cheque_no    = !empty($pay_val['cheque_no'])?$pay_val['cheque_no']:'';
    $collect_date = !empty($pay_val['collect_date'])?$pay_val['collect_date']:'';
    $amt_type     = !empty($pay_val['amt_type'])?$pay_val['amt_type']:'';
    $entry_date   = !empty($pay_val['entry_date'])?$pay_val['entry_date']:'';

    if($page_action == 'Edit')
    {
        $cheque_add  = 'show';
        $cheque_edit = 'show';
        $read_only   = 'readonly';
    }
    else
    {
        $cheque_add  = 'hide';
        $cheque_edit = 'hide';
        $read_only   = '';
    }
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
            <section id="configuration">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo $page_title; ?></h4>
                            </div>
                            <div class="card-content collapse show">

                                <div class="card-body card-dashboard pt-0">
                                    <form class="data_form" name="data_form" method="post">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Bill No <span class="text-danger">*</span></label>
                                                        <select class="form-control pay_id js-select1-multi" id="pay_id" name="pay_id">
                                                            <option value="">Select Value</option>
                                                            <?php
                                                                if(!empty($payment_list))
                                                                {
                                                                    foreach ($payment_list as $key => $pay_val) {
                                                                        $pay_id  = !empty($pay_val['pay_id'])?$pay_val['pay_id']:'';
                                                                        $bill_id = !empty($pay_val['bill_id'])?$pay_val['bill_id']:'';
                                                                        $bill_no = !empty($pay_val['bill_no'])?$pay_val['bill_no']:'';
                                                                        $pre_bal = !empty($pay_val['pre_bal'])?$pay_val['pre_bal']:'0';
                                                                        $cur_bal = !empty($pay_val['cur_bal'])?$pay_val['cur_bal']:'0';

                                                                        $select = '';
                                                                        if($payment_id == $pay_id)
                                                                        {
                                                                            $select = 'selected';
                                                                        }

                                                                        echo '<option value='.$pay_id.' '.$select.'>'.$bill_no.'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Amount <span class="text-danger">*</span></label>
                                                        <input type="text" id="amount" class="form-control amount int_value bg-wht" placeholder="Amount" name="amount" value="<?php echo $amount; ?>" <?php echo $read_only; ?>>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Discount</label>
                                                        <input type="text" id="discount" class="form-control discount int_value bg-wht" placeholder="Discount" name="discount" value="<?php echo $discount; ?>" <?php echo $read_only; ?>>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Entry Date <span class="text-danger">*</span></label>
                                                        <input type="text" id="entry_date" class="form-control entry_date dates" placeholder="Entry Date" name="entry_date" value="<?php echo $entry_date; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Payment Type <span class="text-danger">*</span></label>
                                                        <select class="form-control amt_type js-select1-multi" id="amt_type" name="amt_type">
                                                            <option value="">Select Value</option>
                                                            <option value="1" <?php echo $amt_type == 1 ? 'selected' : ''; ?>>Cash</option>
                                                            <option value="2" <?php echo $amt_type == 2 ? 'selected' : ''; ?>>Cheque</option>
                                                            <option value="3" <?php echo $amt_type == 3 ? 'selected' : ''; ?>>Others</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="cheque_val <?php echo $cheque_add; ?>">
                                                <div class="row clearfix">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="projectinput1">Bank Name <span class="text-danger">*</span></label>
                                                            <input type="text" id="bank_name" class="form-control bank_name bg-wht" placeholder="Bank Name" name="bank_name" value="<?php echo $bank_name; ?>" <?php echo $read_only; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="projectinput1">Cheque No <span class="text-danger">*</span></label>
                                                            <input type="text" id="cheque_no" class="form-control cheque_no bg-wht" placeholder="Cheque No" name="cheque_no" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" value="<?php echo $cheque_no; ?>" <?php echo $read_only; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="projectinput1">Collection Date <span class="text-danger">*</span></label>
                                                            <input type="text" id="collect_date" class="form-control collect_date atdates" placeholder="Collection Date" name="collect_date" value="<?php echo $collect_date; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 cheque_edit <?php echo $cheque_edit; ?>">
                                                        <div class="form-group">
                                                            <label for="projectinput1">Penalty Amount </label>
                                                            <input type="text" id="penalty_amt" class="form-control penalty_amt" placeholder="Penalty Amount" name="penalty_amt" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 cheque_edit <?php echo $cheque_edit; ?>">
                                                        <div class="form-group">
                                                            <label for="projectinput1">Bank Charge </label>
                                                            <input type="text" id="bank_charge" class="form-control bank_charge" placeholder="Bank Charge" name="bank_charge" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 cheque_edit <?php echo $cheque_edit; ?>">
                                                        <div class="form-group select_list zone_list">
                                                            <label for="projectinput1" class="row" style="margin-left: 0px;">Cheque Status <span class="text-danger">*</span></label>
                                                            <select class="form-control cheque_status js-select1-multi" id="cheque_status" name="cheque_status">  
                                                                <option value="">Select Value</option>
                                                                <option value="1">Success</option>
                                                                <option value="2">Failure</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group desc_val <?php echo $cheque_edit; ?>">
                                                        <label for="projectinput1">Description <span class="text-danger">*</span></label>
                                                        <textarea id="description" class="form-control description" placeholder="Description" name="description" rows="3"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="button" class="btn btn-primary distributor_payment" data-type="_s_c" data-value="admin" data-cntrl="payment" data-func="distributor_payment" data-method="<?php echo $method; ?>" data-id="<?php echo $random_val; ?>" data-auto="<?php echo $auto_id; ?>">
                                                <span class="payment_btn show"><i class="la la-check-square-o"></i> Submit</span>

                                                <span class="payment_span hide"><i class="la la-spinner spinner"></i> Loading....</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-body card-dashboard pt-0">
                                    <div class="row">
                                        <div class="col-sm-12 filter-design">
                                            <div class="form-group row">
                                                <div class="col-sm-2 text-right">
                                                    <input type="hidden" name="searchval" id="searchval" class="searchval" value="">
                                                    <input type="hidden" name="getlimitval" id="getlimitval" class="getlimitval" value="10">
                                                    <input type="hidden" name="value" id="value" class="value" value="admin">
                                                    <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="payment">
                                                    <input type="hidden" name="func" id="func" class="func" value="distributor_payment">
                                                    <input type="hidden" name="load_data" id="load_data" class="load_data" value="">
                                                    <input type="hidden" name="random_val" id="random_val" class="random_val" value="<?php echo $random_val; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 filter-design">
                                            <div class="table_load show">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Inv No</th>
                                                                <th>Rec No</th>
                                                                <th>Opening Bal</th>
                                                                <th>Amount</th>
                                                                <th>Closing Bal</th>
                                                                <th>Payment Date</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="loaddefaultData">
                                                            <tr>
                                                                <td class="tablelocload"></td>
                                                                <td class="tablelocload"></td>
                                                                <td class="tablelocload"></td>
                                                                <td class="tablelocload"></td>
                                                                <td class="tablelocload"></td>
                                                                <td class="tablelocload"></td>
                                                                <td class="tablelocload"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="table_value hide">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Inv No</th>
                                                                <th>Rec No</th>
                                                                <th>Opening Bal</th>
                                                                <th>Amount</th>
                                                                <th>Closing Bal</th>
                                                                <th>Payment Date</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="getPayment">
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="pagprev" style="margin-bottom: 20px; position: relative; float: left;">
                                                </div>
                                                <div class="pagnext" style="margin-bottom: 20px; position: relative; float: right;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 filter-design">
                                            <div id="error" class="hide alert alert-danger text-center">
                                                <b>No items found...</b>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
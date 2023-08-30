<style type="text/css">
    .pt-0
    {
        padding-top: 0px;
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
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Amount <span class="text-danger">*</span></label>
                                                        <input type="text" id="amount" class="form-control amount" placeholder="Amount" name="amount" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Discount</label>
                                                        <input type="text" id="discount" class="form-control discount" placeholder="Discount" name="discount" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="projectinput1">Payment Type <span class="text-danger">*</span></label>
                                                        <select class="form-control amt_type js-select1-multi" id="amt_type" name="amt_type">
                                                            <option value="">Select Value</option>
                                                            <option value="1">Cash</option>
                                                            <option value="2">Cheque</option>
                                                            <option value="3">Others</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group desc_val hide">
                                                        <label for="projectinput1">Description <span class="text-danger">*</span></label>
                                                        <textarea id="description" class="form-control description" placeholder="Description" name="description" rows="3"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="button" class="btn btn-primary outlet_payment" data-type="_s_c" data-value="distributors" data-cntrl="payment" data-func="outlet_payment" data-id="<?php echo $random_val; ?>">
                                                <span class="payment_btn show"><i class="la la-check-square-o"></i> <?php echo $page_title; ?></span>

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
                                                    <input type="hidden" name="value" id="value" class="value" value="distributors">
                                                    <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="payment">
                                                    <input type="hidden" name="func" id="func" class="func" value="outlet_payment">
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
                                                                <th>#</th>
                                                                <th>Employee Name</th>
                                                                <th>Amount</th>
                                                                <th>Discount</th>
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
                                                                <th>#</th>
                                                                <th>Employee Name</th>
                                                                <th>Amount</th>
                                                                <th>Discount</th>
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
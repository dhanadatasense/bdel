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
                                <div class="card-body card-dashboard">
                                    <div class="row">
                                        <div class="col-sm-12 filter-design">
                                            <div class="form-group row">
                                                <div class="col-sm-2 text-right">
                                                    <input type="hidden" name="searchval" id="searchval" class="searchval" value="">
                                                    <input type="hidden" name="getlimitval" id="getlimitval" class="getlimitval" value="10">
                                                    <input type="hidden" name="value" id="value" class="value" value="admin">
                                                    <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="outlets">
                                                    <input type="hidden" name="func" id="func" class="func" value="list_payment">
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
                                                                <th>Distributor Name</th>
                                                                <th>Pre Balance</th>
                                                                <th>Amount</th>
                                                                <th>Discount</th>
                                                                <th>Payment Type</th>
                                                                <th>Payment Date</th>
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
                                                                <th>#</th>
                                                                <th>Distributor Name</th>
                                                                <th>Pre Balance</th>
                                                                <th>Amount</th>
                                                                <th>Discount</th>
                                                                <th>Payment Type</th>
                                                                <th>Payment Date</th>
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
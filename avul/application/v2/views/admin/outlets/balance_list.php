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
                                                <div class="col-sm-6">
                                                    <input type="text" name="searchval" class="form-control searchval" id="searchval" placeholder="Search" value="">
                                                </div>
                                                <div class="col-sm-3">
                                                    <button class="btn btn-warning searchdata">Search</button>
                                                </div>
                                                <div class="col-sm-2 text-right">
                                                    <select class="set_offset form-control getlimitval filteropt">
                                                        <option value="5">5</option>
                                                        <option value="10" selected="selected">10</option>
                                                        <option value="25">25</option>
                                                        <option value="50">50</option>
                                                        <option value="100">100</option>
                                                    </select>
                                                    <input type="hidden" name="value" id="value" class="value" value="admin">
                                                    <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="outlets">
                                                    <input type="hidden" name="func" id="func" class="func" value="balance_list">
                                                    <input type="hidden" name="load_data" id="load_data" class="load_data" value="">
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
                                                                <th>Distributor</th>
                                                                <th>Store</th>
                                                                <th>Invoice No</th>
                                                                <th>Balance</th>
                                                                <th>Date</th>
                        <?php
                            if(userAccess('outlets-edit') == TRUE):
                                ?>
                                    <th class="text-center">Actions</th>
                                <?php
                            endif;
                        ?>
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
                                                                <th>Distributor</th>
                                                                <th>Store</th>
                                                                <th>Invoice No</th>
                                                                <th>Balance</th>
                                                                <th>Date</th>
                        <?php
                            if(userAccess('outlets-edit') == TRUE || userAccess('outlets-delete') == TRUE):
                                ?>
                                    <th class="text-center">Actions</th>
                                <?php
                            endif;
                        ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="getCategory">
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
                                                <b class="error_msg">No items found...</b>
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
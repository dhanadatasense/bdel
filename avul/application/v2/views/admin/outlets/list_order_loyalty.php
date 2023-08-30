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
                                    <form class="data_form" name="data_form" method="post">
                                        <div class="form-body">
                                            <div class="col-sm-12 filter-design">
                                                <div class="table_value_ show">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 140px; padding-left: 0px;">Invoice Count</th>
                                                                    <th style="width: 140px;">Discount Value</th>
                                                                    <th style="width: 140px;"><button type="button" name="remove" class="btn btn-success btn-sm add_loyalty button_size m-t-6"><span class="ft-plus-square"></span></button></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="addloyaltyform">
                                                                <?php
                                                                    if($data_value)
                                                                    {
                            $num = 1;
                            foreach ($data_value as $key => $val) {
                                $auto_id   = !empty($val['auto_id'])?$val['auto_id']:'';
                                $inv_count = !empty($val['inv_count'])?$val['inv_count']:'';
                                $dis_value = !empty($val['dis_value'])?$val['dis_value']:'';

                                ?>
                                    <tr class="row_<?php echo $num; ?>">
                                        <td style="padding-left: 0px;">
                                            <input type="text" data-te="<?php echo $num; ?>" name="invoice_count[]" id="invoice_count<?php echo $num; ?>" class="form-control invoice_count<?php echo $num; ?> invoice_count" value="<?php echo $inv_count; ?>" placeholder="Minimum Stock" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        </td>
                                        <td>
                                            <input type="text" data-te="<?php echo $num; ?>" name="discount_value[]" id="discount_value<?php echo $num; ?>" class="form-control discount_value<?php echo $num; ?> discount_value" value="<?php echo $dis_value; ?>" placeholder="Minimum Stock" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            <input type="hidden" data-te="<?php echo $num; ?>" name="loyalty_id[]" id="loyalty_id<?php echo $num; ?>" class="form-control loyalty_id<?php echo $num; ?> loyalty_id" value="<?php echo $auto_id; ?>" placeholder="Minimum Stock">
                                        </td>
                                        <td><button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6"><span class="ft-minus-square"></span></button></td>
                                    </tr>
                                <?php

                                $num++;
                            }
                                                                    }
                                                                    else
                                                                    {
                                                                        ?>
                                                                            <tr class="row_1">
                                                                                <td style="padding-left: 0px;">
                                                                                    <input type="text" data-te="1" name="invoice_count[]" id="invoice_count1" class="form-control invoice_count1 invoice_count" value="0" placeholder="Minimum Stock" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" data-te="1" name="discount_value[]" id="discount_value1" class="form-control discount_value1 discount_value" value="0" placeholder="Minimum Stock" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                                                    <input type="hidden" data-te="1" name="loyalty_id[]" id="loyalty_id1" class="form-control loyalty_id1 loyalty_id" value="0" placeholder="Minimum Stock">
                                                                                </td>
                                                                                <td><button type="button" name="remove" class="btn btn-danger btn-sm  button_size m-t-6"><span class="ft-minus-square"></span></button></td>
                                                                            </tr>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                        <input type="hidden" name="row_count" id="row_count" value="<?php echo isset($num)?$num-1:'1'; ?>">
                                                    </div>

                                                    <div class="pagprev" style="margin-bottom: 20px; position: relative; float: left;">
                                                    </div>
                                                    <div class="pagnext" style="margin-bottom: 20px; position: relative; float: right;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <!-- <div class="col-sm-12 filter-design">
                                                <div id="error_" class="hide alert alert-danger text-center">
                                                    <b>No items found...</b>
                                                </div>
                                            </div> -->
                                            <div class="col-sm-2 text-right">
                                                <input type="hidden" name="searchval" id="searchval" class="searchval" value="">
                                                <input type="hidden" name="getlimitval" id="getlimitval" class="getlimitval" value="10">
                                                <input type="hidden" name="value" id="value" class="value" value="admin">
                                                <input type="hidden" name="cntrl" id="cntrl" class="cntrl" value="outlets">
                                                <input type="hidden" name="func" id="func" class="func" value="list_order_loyalty">
                                                <input type="hidden" name="formpage" id="formpage" class="formpage" value="BTBM_X_P">
                                                <input type="hidden" name="method" class="method" value="outlet_loyalty">
                                                <input type="hidden" name="load_data" id="load_data" class="load_data" value="">
                                                <input type="hidden" name="random_val" id="random_val" class="random_val" value="<?php echo $random_val; ?>">
                                            </div>
                                            <button type="submit" class="btn btn-primary data_submit" data-type="_s_c">
                                                <span class="first_btn show"><i class="la la-check-square-o"></i> Submit</span>

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
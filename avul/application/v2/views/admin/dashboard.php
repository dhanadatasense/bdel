<?php

    $state_count       = !empty($data_value['state_count'])?$data_value['state_count']:'0';
    $city_count        = !empty($data_value['city_count'])?$data_value['city_count']:'0';
    $zone_count        = !empty($data_value['zone_count'])?$data_value['zone_count']:'0';
    $unit_count        = !empty($data_value['unit_count'])?$data_value['unit_count']:'0';
    $category_count    = !empty($data_value['category_count'])?$data_value['category_count']:'0';
    $product_count     = !empty($data_value['product_count'])?$data_value['product_count']:'0';
    $vendor_count      = !empty($data_value['vendor_count'])?$data_value['vendor_count']:'0';
    $outlet_count      = !empty($data_value['outlet_count'])?$data_value['outlet_count']:'0';
    $distributor_count = !empty($data_value['distributor_count'])?$data_value['distributor_count']:'0';
    $employee_count    = !empty($data_value['employee_count'])?$data_value['employee_count']:'0';

    $attendance_data    = !empty($data_value['attendance_data'])?$data_value['attendance_data']:'';
    $purchase_data      = !empty($data_value['purchase_data'])?$data_value['purchase_data']:'';
    $order_data         = !empty($data_value['order_data'])?$data_value['order_data']:'';
    $disPurchase_data   = !empty($data_value['disPurchase_data'])?$data_value['disPurchase_data']:'';
    $productTarget_data = !empty($data_value['productTarget_data'])?$data_value['productTarget_data']:'';
    $beatTarget_data    = !empty($data_value['beatTarget_data'])?$data_value['beatTarget_data']:'';
    $mostOrder_list     = !empty($data_value['mostOrder_list'])?$data_value['mostOrder_list']:'';
    $mostInv_list       = !empty($data_value['mostInv_list'])?$data_value['mostInv_list']:'';

    // Sales Details
    $today_count = !empty($sales_value['today_count'])?$sales_value['today_count']:'0';
    $today_value = !empty($sales_value['today_value'])?$sales_value['today_value']:'0';
    $week_count  = !empty($sales_value['week_count'])?$sales_value['week_count']:'0';
    $week_value  = !empty($sales_value['week_value'])?$sales_value['week_value']:'0';
    $month_count = !empty($sales_value['month_count'])?$sales_value['month_count']:'0';
    $month_value = !empty($sales_value['month_value'])?$sales_value['month_value']:'0';

    // Attendace Details
    $total_emp   = !empty($attendance_value['total_employee'])?$attendance_value['total_employee']:'0';
    $active_emp  = !empty($attendance_value['active_employee'])?$attendance_value['active_employee']:'0';
    $present_emp = !empty($attendance_value['present_employee'])?$attendance_value['present_employee']:'0';
    $absent_emp  = !empty($attendance_value['absent_employee'])?$attendance_value['absent_employee']:'0';

    // Order Details
    $success_order = !empty($order_value['success_order'])?$order_value['success_order']:'0';
    $process_order = !empty($order_value['process_order'])?$order_value['process_order']:'0';
    $cancel_order  = !empty($order_value['cancel_order'])?$order_value['cancel_order']:'0';
    $invoice_order = !empty($order_value['invoice_order'])?$order_value['invoice_order']:'0';
?>
        <div class="app-content content">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <div class="content-header row">
                </div>
                <div class="content-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card pull-up">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="media-body text-left">
                                                <h3 class="info"><?php echo $state_count; ?></h3>
                                                <h6>State</h6>
                                            </div>
                                            <div>
                                                <i class="ft-compass info font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card pull-up">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="media-body text-left">
                                                <h3 class="warning"><?php echo $city_count; ?></h3>
                                                <h6>City</h6>
                                            </div>
                                            <div>
                                                <i class="icon-grid warning font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card pull-up">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="media-body text-left">
                                                <h3 class="success"><?php echo $zone_count; ?></h3>
                                                <h6>Beat</h6>
                                            </div>
                                            <div>
                                                <i class="icon-cursor success font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card pull-up">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="media-body text-left">
                                                <h3 class="danger"><?php echo $category_count; ?></h3>
                                                <h6>Category</h6>
                                            </div>
                                            <div>
                                                <i class="icon-layers danger font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card pull-up">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="media-body text-left">
                                                <h3 class="success"><?php echo $product_count; ?></h3>
                                                <h6>Product</h6>
                                            </div>
                                            <div>
                                                <i class="la la-newspaper-o success font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card pull-up">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="media-body text-left">
                                                <h3 class="danger"><?php echo $vendor_count; ?></h3>
                                                <h6>Manufacture</h6>
                                            </div>
                                            <div>
                                                <i class="la la-cart-arrow-down  danger font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card pull-up">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="media-body text-left">
                                                <h3 class="primary"><?php echo $outlet_count; ?></h3>
                                                <h6>Outlets</h6>
                                            </div>
                                            <div>
                                                <i class="icon icon-handbag primary font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card pull-up">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="media-body text-left">
                                                <h3 class="warning"><?php echo $distributor_count; ?></h3>
                                                <h6>Distributors</h6>
                                            </div>
                                            <div>
                                                <i class="ft-users warning font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title text-center">Attendance Details</h4>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-md-3 col-12 border-right-blue-grey border-right-lighten-5 text-center">
                                                <h4 class="info text-bold-600"><span class="icon-user"></span></h4>
                                                <h4 class="font-large-2 text-bold-400"><?php echo $total_emp; ?></h4>
                                                <p class="blue-grey lighten-2 mb-0">Total Employee</p>
                                            </div>
                                            <div class="col-md-3 col-12 border-right-blue-grey border-right-lighten-5 text-center">
                                                <h4 class="warning text-bold-600"><span class="icon-user-follow"></span></h4>
                                                <h4 class="font-large-2 text-bold-400"><?php echo $active_emp; ?></h4>
                                                <p class="blue-grey lighten-2 mb-0">Active Employee</p>
                                            </div>
                                            <div class="col-md-3 col-12 border-right-blue-grey border-right-lighten-5 text-center">
                                                <h4 class="success text-bold-600"><span class="icon-user-following"></span></h4>
                                                <h4 class="font-large-2 text-bold-400"><?php echo $present_emp; ?></h4>
                                                <p class="blue-grey lighten-2 mb-0">Present</p>
                                            </div>
                                            <div class="col-md-3 col-12 text-center">
                                                <h4 class="danger text-bold-600"><span class="icon-user-unfollow"></span></h4>
                                                <h4 class="font-large-2 text-bold-400"><?php echo $absent_emp; ?></h4>
                                                <p class="blue-grey lighten-2 mb-0">Absent</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="recent-transactions" class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Target Performance</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                </div>
                                <div class="card-content mb-20">
                                    <div id="donutchart" style="width: 100%; height: 250px; margin-bottom: 20px;"></div>
                                </div>
                            </div>
                        </div>
                        <div id="recent-transactions" class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Order Performance</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                </div>
                                <div class="card-content mb-20">
                                    <div id="orderchart" style="width: 100%; height: 250px; margin-bottom: 20px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="crypto-stats-3" class="row">
                        <div class="col-xl-4 col-12">
                            <div class="card crypto-card-3 pull-up">
                                <div class="card-content">
                                    <div class="card-body pb-0">
                                        <div class="row">
                                            <div class="col-2">
                                                <i class="warning font-large-2 ft-grid"></i>
                                            </div>
                                            <div class="col-5 pl-2">
                                                <h4>Today</h4>
                                                <h6 class="text-muted">Order</h6>
                                            </div>
                                            <div class="col-5 text-right">
                                                <h4 style="font-size: 1.12rem;">Rs. <?php echo $today_value; ?></h4>
                                                <h4 class="success darken-4"><?php echo $today_count; ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <canvas id="btc-chartjs" class="height-75"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-12">
                            <div class="card crypto-card-3 pull-up">
                                <div class="card-content">
                                    <div class="card-body pb-0">
                                        <div class="row">
                                            <div class="col-2">
                                                <i class="ft-layers blue-grey lighten-1 font-large-2"></i>
                                            </div>
                                            <div class="col-5 pl-2">
                                                <h4>This Week</h4>
                                                <h6 class="text-muted">Order</h6>
                                            </div>
                                            <div class="col-5 text-right">
                                                <h4 style="font-size: 1.12rem;">Rs. <?php echo $week_value; ?></h4>
                                                <h4 class="success darken-4"><?php echo $week_count; ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <canvas id="eth-chartjs" class="height-75"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-12">
                            <div class="card crypto-card-3 pull-up">
                                <div class="card-content">
                                    <div class="card-body pb-0">
                                        <div class="row">
                                            <div class="col-2">
                                                <i class="ft-inbox info font-large-2"></i>
                                            </div>
                                            <div class="col-5 pl-2">
                                                <h4>This Month</h4>
                                                <h6 class="text-muted">Order</h6>
                                            </div>
                                            <div class="col-5 text-right">
                                                <h4 style="font-size: 1.12rem;">Rs. <?php echo $month_value; ?></h4>
                                                <h4 class="success darken-4"><?php echo $month_count; ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <canvas id="xrp-chartjs" class="height-75"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title text-center">Outlet Order Details</h4>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-md-3 col-12 border-right-blue-grey border-right-lighten-5 text-center">
                                                <h4 class="info text-bold-600"><span class="ft-layers"></span></h4>
                                                <h4 class="font-large-2 text-bold-400"><?php echo $success_order; ?></h4>
                                                <p class="blue-grey lighten-2 mb-0">Overall Order</p>
                                            </div>
                                            <div class="col-md-3 col-12 border-right-blue-grey border-right-lighten-5 text-center">
                                                <h4 class="warning text-bold-600"><span class="la la-spinner"></span></h4>
                                                <h4 class="font-large-2 text-bold-400"><?php echo $process_order; ?></h4>
                                                <p class="blue-grey lighten-2 mb-0">Process Order</p>
                                            </div>
                                            <div class="col-md-3 col-12 border-right-blue-grey border-right-lighten-5 text-center">
                                                <h4 class="success text-bold-600"><span class="la la-check-square-o"></span></h4>
                                                <h4 class="font-large-2 text-bold-400"><?php echo $invoice_order; ?></h4>
                                                <p class="blue-grey lighten-2 mb-0">Complete Order</p>
                                            </div>
                                            <div class="col-md-3 col-12 text-center">
                                                <h4 class="danger text-bold-600"><span class="ft-trash-2"></span></h4>
                                                <h4 class="font-large-2 text-bold-400"><?php echo $cancel_order; ?></h4>
                                                <p class="blue-grey lighten-2 mb-0">Cancel Order</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div id="recent-transactions" class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Distributor Invoice Count</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                </div>
                                <div class="card-content">
                                    <div class="table-responsive">
                                        <?php
                                            if(!empty($invoice_value))
                                            {
                                                ?>
                                                    <table id="recent-orders" class="table table-hover table-xl mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="border-top-0">Distributor Name</th>
                                                                <th class="border-top-0">Today</th>
                                                                <th class="border-top-0">This Week</th>
                                                                <th class="border-top-0">This Month</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                if(!empty($invoice_value))
                                                                {
                                                                    foreach ($invoice_value as $key => $val_1) {
                                                                        $dis_name     = !empty($val_1['distributor_name'])?$val_1['distributor_name']:'';
                                                                        $today_order  = !empty($val_1['today_order'])?$val_1['today_order']:'0';
                                                                        $week_order   = !empty($val_1['week_order'])?$val_1['week_order']:'0';
                                                                        $month_order  = !empty($val_1['month_order'])?$val_1['month_order']:'0';

                                                                        ?>
                                                                            <tr>
                                                                                <td class="border-top-0"><?php echo mb_strimwidth($dis_name, 0, 50, '...'); ?></td>
                                                                                <td class="border-top-0"><?php echo $today_order; ?></td>
                                                                                <td class="border-top-0"><?php echo $week_order; ?></td>
                                                                                <td class="border-top-0"><?php echo $month_order; ?></td>
                                                                            </tr>
                                                                        <?php
                                                                    }
                                                                }  
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <div class="alert alert-danger text-center" style="margin-left: 10px;margin-right: 10px;">
                                                        <b>No data found...</b>
                                                    </div>
                                                <?php
                                            } 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="recent-transactions" class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Recent Attendace</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                </div>
                                <div class="card-content">
                                    <div class="table-responsive">
                                        <?php
                                            if(!empty($attendance_data))
                                            {
                                                ?>
                                                    <table id="recent-orders" class="table table-hover table-xl mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="border-top-0">Employee Name</th>
                                                                <th class="border-top-0">Store Name</th>
                                                                <th class="border-top-0">Type</th>
                                                                <th class="border-top-0">Date</th>
                                                                <th class="border-top-0">In Time</th>
                                                                <th class="border-top-0">Out Time</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                if(!empty($attendance_data))
                                                                {
                                                                    foreach ($attendance_data as $key => $val_1) {
                                                                        $emp_name   = !empty($val_1['emp_name'])?$val_1['emp_name']:'';
                                                                        $store_name = !empty($val_1['store_name'])?$val_1['store_name']:'';
                                                                        $att_type   = !empty($val_1['attendance_type'])?$val_1['attendance_type']:'';
                                                                        $c_date     = !empty($val_1['c_date'])?$val_1['c_date']:'';
                                                                        $in_time    = !empty($val_1['in_time'])?$val_1['in_time']:'';
                                                                        $out_time   = !empty($val_1['out_time'])?$val_1['out_time']:'';

                                                                        if($att_type == 1)
                                                                        {
                                                                            $type_name = 'Sales Order';
                                                                        }
                                                                        else if($att_type == 2)
                                                                        {
                                                                            $type_name = 'No Order';
                                                                        }
                                                                        else
                                                                        {
                                                                            $type_name = 'Pending';
                                                                        }

                                                                        ?>
                                                                            <tr>
                                                                                <td class="border-top-0"><?php echo $emp_name; ?></td>
                                                                                <td class="border-top-0"><?php echo mb_strimwidth($store_name, 0, 35, '...'); ?></td>
                                                                                <td class="border-top-0"><?php echo $type_name; ?></td>
                                                                                <td class="border-top-0"><?php echo $c_date; ?></td>
                                                                                <td class="border-top-0"><?php echo $in_time; ?></td>
                                                                                <td class="border-top-0"><?php echo $out_time; ?></td>
                                                                            </tr>
                                                                        <?php
                                                                    }
                                                                }  
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <div class="alert alert-danger text-center" style="margin-left: 10px;margin-right: 10px;">
                                                        <b>No data found...</b>
                                                    </div>
                                                <?php
                                            } 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="recent-transactions" class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Recent Purchase</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                </div>
                                <div class="card-content">
                                    <div class="table-responsive">
                                        <?php
                                            if(!empty($purchase_data))
                                            {
                                                ?>
                                                    <table id="recent-orders" class="table table-hover table-xl mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="border-top-0">PO No</th>
                                                                <th class="border-top-0">Date</th>
                                                                <th class="border-top-0">Status</th>
                                                                <th class="border-top-0">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                if(!empty($purchase_data))
                                                                {
                                                                    foreach ($purchase_data as $key => $val_2) {

                                                                        $po_id        = !empty($val_2['po_id'])?$val_2['po_id']:'';
                                                                        $po_no        = !empty($val_2['po_no'])?$val_2['po_no']:'';
                                                                        $vendor_name  = !empty($val_2['vendor_name'])?$val_2['vendor_name']:'';
                                                                        $contact_no   = !empty($val_2['contact_no'])?$val_2['contact_no']:'';
                                                                        $order_date   = !empty($val_2['order_date'])?$val_2['order_date']:'';
                                                                        $order_status = !empty($val_2['order_status'])?$val_2['order_status']:'';

                                                                        // Order Status
                                                                        if($order_status == '1')
                                                                        {
                                                                            $order_view = '<span class="badge badge-success">Success</span>';
                                                                        }
                                                                        else if($order_status == '2')
                                                                        {
                                                                            $order_view = '<span class="badge badge-warning">Approved</span>';
                                                                        }
                                                                        else if($order_status == '3')
                                                                        {
                                                                            $order_view = '<span class="badge badge-primary">Packing</span>';
                                                                        }
                                                                        else if($order_status == '4')
                                                                        {
                                                                            $order_view = '<span class="badge badge-info">Delivered</span>';
                                                                        }
                                                                        else if($order_status == '5')
                                                                        {
                                                                            $order_view = '<span class="badge badge-success">Complete</span>';
                                                                        }
                                                                        else
                                                                        {
                                                                            $order_view = '<span class="badge badge-danger">Cancel</span>';
                                                                        }

                                                                        ?>
                                                                            <tr>
                                                                                <td class="border-top-0"><?php echo $po_no; ?></td>
                                                                                <td class="border-top-0"><?php echo date('d-M-Y', strtotime($order_date)); ?></td>
                                                                                <td class="border-top-0"><?php echo $order_view; ?></td>
                                                                                <td class="border-top-0"><a href="<?php echo BASE_URL; ?>index.php/admin/purchase/view_purchase/View/<?php echo $po_id; ?>" class="button_clr btn btn-primary"><i class="ft-file-text"></i> </a></td>
                                                                            </tr>
                                                                        <?php
                                                                    }
                                                                }  
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <div class="alert alert-danger text-center" style="margin-left: 10px;margin-right: 10px;">
                                                        <b>No data found...</b>
                                                    </div>
                                                <?php
                                            } 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="recent-transactions" class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Recent Outlet Orders</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                </div>
                                <div class="card-content">
                                    <div class="table-responsive">
                                        <?php
                                            if(!empty($order_data))
                                            {
                                                ?>
                                                <table id="recent-orders" class="table table-hover table-xl mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-top-0">Order No</th>
                                                            <th class="border-top-0">Order Date</th>
                                                            <th class="border-top-0">Status</th>
                                                            <th class="border-top-0">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            if(!empty($order_data))
                                                            {
                                                                foreach ($order_data as $key => $val_3) {

                                                                    $order_id     = !empty($val_3['order_id'])?$val_3['order_id']:'';
                                                                    $order_no     = !empty($val_3['order_no'])?$val_3['order_no']:'';
                                                                    $emp_name     = !empty($val_3['emp_name'])?$val_3['emp_name']:'';
                                                                    $store_name   = !empty($val_3['store_name'])?$val_3['store_name']:'';
                                                                    $order_status = !empty($val_3['order_status'])?$val_3['order_status']:'';
                                                                    $_ordered     = !empty($val_3['_ordered'])?$val_3['_ordered']:'';
                                                                    $random_value = !empty($val_3['random_value'])?$val_3['random_value']:'';

                                                                    // Order Status
                                                                    if($order_status == '1')
                                                                    {
                                                                        $order_view = '<span class="badge badge-success">Success</span>';
                                                                    }
                                                                    else if($order_status == '2')
                                                                    {
                                                                        $order_view = '<span class="badge badge-warning">Approved</span>';
                                                                    }
                                                                    else if($order_status == '3')
                                                                    {
                                                                        $order_view = '<span class="badge badge-primary">Packing</span>';
                                                                    }
                                                                    else if($order_status == '4')
                                                                    {
                                                                        $order_view = '<span class="badge badge-info">Shipping</span>';
                                                                    }
                                                                    else if($order_status == '5')
                                                                    {
                                                                        $order_view = '<span class="badge badge-warning">Invoice</span>';
                                                                    }
                                                                    else if($order_status == '6')
                                                                    {
                                                                        $order_view = '<span class="badge badge-success">Delivered</span>';
                                                                    }
                                                                    else if($order_status == '7')
                                                                    {
                                                                        $order_view = '<span class="badge badge-success">Complete</span>';
                                                                    }
                                                                    else
                                                                    {
                                                                        $order_view = '<span class="badge badge-danger">Cancel</span>';
                                                                    }

                                                                    ?>
                                                                        <tr>
                                                                            <td class="border-top-0"><?php echo $order_no; ?></td>
                                                                            <td class="border-top-0"><?php echo date('d-m-Y', strtotime($_ordered)); ?></td>
                                                                            <td class="border-top-0"><?php echo $order_view; ?></td>
                                                                            <td class="border-top-0"><a href="<?php echo BASE_URL; ?>index.php/admin/order/overall_order/view/<?php echo $random_value; ?>" class="button_clr btn btn-primary"><i class="ft-file-text"></i> </a></td>
                                                                        </tr>
                                                                    <?php

                                                                }
                                                            }  
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <div class="alert alert-danger text-center" style="margin-left: 10px;margin-right: 10px;">
                                                        <b>No data found...</b>
                                                    </div>
                                                <?php
                                            } 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="recent-transactions" class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Recent Product Target</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                </div>
                                <div class="card-content">
                                    <div class="table-responsive">
                                        <?php
                                            if(!empty($productTarget_data))
                                            {
                                                ?>
                                                    <table id="recent-orders" class="table table-hover table-xl mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="border-top-0">Description</th>
                                                                <th class="border-top-0">Target Value</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                foreach ($productTarget_data as $key => $val_4) 
                                                                {
                                                                    $description    = !empty($val_4['description'])?$val_4['description']:'';
                                                                    $pdtTarget_val  = !empty($val_4['pdtTarget_val'])?$val_4['pdtTarget_val']:'0';
                                                                    $pdtAchieve_val = !empty($val_4['pdtAchieve_val'])?$val_4['pdtAchieve_val']:'0';

                                                                    ?>
                                                            <tr>
                                                                <td><?php echo mb_strimwidth($description, 0, 45, '...') ?></td>
                                                                <td><?php echo $pdtTarget_val; ?> / <?php echo $pdtAchieve_val; ?></td>
                                                            </tr>
                                                                    <?php
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <div class="alert alert-danger text-center" style="margin-left: 10px;margin-right: 10px;">
                                                        <b>No data found...</b>
                                                    </div>
                                                <?php
                                            } 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="recent-transactions" class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Recent Beat Target</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                </div>
                                <div class="card-content">
                                    <div class="table-responsive">
                                        <?php
                                            if(!empty($beatTarget_data))
                                            {
                                                ?>
                                                    <table id="recent-orders" class="table table-hover table-xl mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="border-top-0">Beat Name</th>
                                                                <th class="border-top-0">Target Value</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                foreach ($beatTarget_data as $key => $val_4) 
                                                                {
                                                                    $zone_name    = !empty($val_4['zone_name'])?$val_4['zone_name']:'';
                                                                    $beatTarget_val  = !empty($val_4['beatTarget_val'])?$val_4['beatTarget_val']:'0';
                                                                    $beatAchieve_val = !empty($val_4['beatAchieve_val'])?$val_4['beatAchieve_val']:'0';

                                                                    ?>
                                                            <tr>
                                                                <td><?php echo mb_strimwidth($zone_name, 0, 45, '...') ?></td>
                                                                <td><?php echo $beatTarget_val; ?> / <?php echo $beatAchieve_val; ?></td>
                                                            </tr>
                                                                    <?php
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <div class="alert alert-danger text-center" style="margin-left: 10px;margin-right: 10px;">
                                                        <b>No data found...</b>
                                                    </div>
                                                <?php
                                            } 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="recent-transactions" class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Most Order Product</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                </div>
                                <div class="card-content">
                                    <div class="table-responsive">
                                        <?php
                                            if(!empty($mostOrder_list))
                                            {
                                                ?>
                                                    <table id="recent-orders" class="table table-hover table-xl mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="border-top-0">Description</th>
                                                                <th class="border-top-0">Order Count</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                foreach ($mostOrder_list as $key => $val_6) 
                                                                {
                                                                    $description = !empty($val_6['description'])?$val_6['description']:'';
                                                                    $order_value = !empty($val_6['order_value'])?$val_6['order_value']:'0';

                                                                    ?>
                                                            <tr>
                                                                <td><?php echo mb_strimwidth($description, 0, 45, '...') ?></td>
                                                                <td><?php echo $order_value; ?></td>
                                                            </tr>
                                                                    <?php
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <div class="alert alert-danger text-center" style="margin-left: 10px;margin-right: 10px;">
                                                        <b>No data found...</b>
                                                    </div>
                                                <?php
                                            } 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="recent-transactions" class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Most Invoice Product</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                </div>
                                <div class="card-content">
                                    <div class="table-responsive">
                                        <?php
                                            if(!empty($mostInv_list))
                                            {
                                                ?>
                                                    <table id="recent-orders" class="table table-hover table-xl mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="border-top-0">Description</th>
                                                                <th class="border-top-0">Invoice Count</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                foreach ($mostInv_list as $key => $val_7) 
                                                                {
                                                                    $description = !empty($val_7['description'])?$val_7['description']:'';
                                                                    $order_value = !empty($val_7['order_value'])?$val_7['order_value']:'0';

                                                                    ?>
                                                            <tr>
                                                                <td><?php echo mb_strimwidth($description, 0, 45, '...') ?></td>
                                                                <td><?php echo $order_value; ?></td>
                                                            </tr>
                                                                    <?php
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <div class="alert alert-danger text-center" style="margin-left: 10px;margin-right: 10px;">
                                                        <b>No data found...</b>
                                                    </div>
                                                <?php
                                            } 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

<?php

    $product_count      = !empty($data_value['product_count'])?$data_value['product_count']:'0';
    $employee_count     = !empty($data_value['employee_count'])?$data_value['employee_count']:'0';
    $outlet_count       = !empty($data_value['outlet_count'])?$data_value['outlet_count']:'0';
    $purchase_data      = !empty($data_value['purchase_data'])?$data_value['purchase_data']:'';
    $outletPayment_data = !empty($data_value['outletPayment_data'])?$data_value['outletPayment_data']:'';
    $recentPayment_data = !empty($data_value['recentPayment_data'])?$data_value['recentPayment_data']:'';
    $outletOrder_data   = !empty($data_value['outletOrder_data'])?$data_value['outletOrder_data']:'';
?>
        <div class="app-content content">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <div class="content-header row">
                </div>
                <div class="content-body">
                    <div class="row">
                        <div class="col-xl-4 col-lg-6 col-12">
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
                        <div class="col-xl-4 col-lg-6 col-12">
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
                        <div class="col-xl-4 col-lg-6 col-12">
                            <div class="card pull-up">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="media-body text-left">
                                                <h3 class="warning"><?php echo $employee_count; ?></h3>
                                                <h6>Employee</h6>
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
                    <?php
                        if($this->session->userdata('distributor_status') == '1')
                        {
                            ?>
                                <div class="row">
                                    <div id="recent-transactions" class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Recent Purchase Order</h4>
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
                                                                            <th class="border-top-0">Order Date</th>
                                                                            <th class="border-top-0">Invoice No</th>
                                                                            <th class="border-top-0">Status</th>
                                                                            <th class="border-top-0">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                            foreach ($purchase_data as $key => $val_1)
                                                                            {
                                                                                $po_id        = !empty($val_1['po_id'])?$val_1['po_id']:'';
                                                                                $po_no        = !empty($val_1['po_no'])?$val_1['po_no']:'---';
                                                                                $order_date   = !empty($val_1['order_date'])?$val_1['order_date']:'';
                                                                                $invoice_no   = !empty($val_1['invoice_no'])?$val_1['invoice_no']:'---';
                                                                                $order_status = !empty($val_1['order_status'])?$val_1['order_status']:'';

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
                                                                                        <td><?php echo $po_no; ?></td>
                                                                                        <td><?php echo date('d-M-Y', strtotime($order_date)); ?></td>
                                                                                        <td><?php echo $invoice_no; ?></td>
                                                                                        <td><?php echo $order_view; ?></td>
                                                                                        <td>
                                                                                            <a href="<?php echo BASE_URL; ?>index.php/distributors/purchase/view_purchase/View/<?php echo $po_id; ?>" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a>
                                                                                        </td>
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
                            <?php
                        }
                    ?>
                    <div class="row">
                        <div id="recent-transactions" class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Recent Outlet Payment</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                </div>
                                <div class="card-content">
                                    <div class="table-responsive">
                                        <?php
                                            if(!empty($outletPayment_data))
                                            {
                                                ?>
                                                    <table id="recent-orders" class="table table-hover table-xl mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="border-top-0">Store Name</th>
                                                                <th class="border-top-0">Pay No</th>
                                                                <th class="border-top-0">Amount</th>
                                                                <th class="border-top-0">Discount</th>
                                                                <th class="border-top-0">Payment Type</th>
                                                                <th class="border-top-0">Payment Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                foreach ($outletPayment_data as $key => $val_2) 
                                                                {
                                                                    $str_name = !empty($val_2['str_name'])?$val_2['str_name']:'';
                                                                    $bill_no  = !empty($val_2['bill_no'])?$val_2['bill_no']:'';
                                                                    $amount   = !empty($val_2['amount'])?$val_2['amount']:'0';
                                                                    $discount = !empty($val_2['discount'])?$val_2['discount']:'0';
                                                                    $amt_type = !empty($val_2['amt_type'])?$val_2['amt_type']:'';
                                                                    $date     = !empty($val_2['date'])?$val_2['date']:'';

                                                                    // Amount Type
                                                                    if($amt_type == 1)
                                                                    {
                                                                        $amount_type = 'Cash';
                                                                    }
                                                                    else if($amt_type == 2)
                                                                    {
                                                                        $amount_type = 'Cheque';
                                                                    }
                                                                    else if($amt_type == 3)
                                                                    {
                                                                        $amount_type = 'Others';
                                                                    }
                                                                    else if($amt_type == 4)
                                                                    {
                                                                        $amount_type = 'Credit Note';
                                                                    }
                                                                    else
                                                                    {
                                                                        $amount_type = '---';
                                                                    }

                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo mb_strimwidth($str_name, 0, 30, '...'); ?></td>
                                                                            <td><?php echo $bill_no; ?></td>
                                                                            <td><?php echo $amount; ?></td>
                                                                            <td><?php echo $discount; ?></td>
                                                                            <td><?php echo $amount_type; ?></td>
                                                                            <td><?php echo date('d-M-Y', strtotime($date)); ?></td>
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
                    <?php
                        if($this->session->userdata('distributor_status') == '1')
                        {
                            ?>
                                <div class="row">
                                    <div id="recent-transactions" class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Recent Distributor Payment</h4>
                                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                            </div>
                                            <div class="card-content">
                                                <div class="table-responsive">
                                                    <?php
                                                        if(!empty($recentPayment_data))
                                                        {
                                                            ?>
                                                                <table id="recent-orders" class="table table-hover table-xl mb-0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="border-top-0">Pay No</th>
                                                                            <th class="border-top-0">Amount</th>
                                                                            <th class="border-top-0">Discount</th>
                                                                            <th class="border-top-0">Payment Type</th>
                                                                            <th class="border-top-0">Payment Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                            foreach ($recentPayment_data as $key => $val_3) 
                                                                            {
                                                                                $bill_no  = !empty($val_3['bill_no'])?$val_3['bill_no']:'';
                                                                                $amount   = !empty($val_3['amount'])?$val_3['amount']:'0';
                                                                                $discount = !empty($val_3['discount'])?$val_3['discount']:'0';
                                                                                $amt_type = !empty($val_3['amt_type'])?$val_3['amt_type']:'';
                                                                                $date     = !empty($val_3['date'])?$val_3['date']:'';

                                                                                // Amount Type
                                                                                if($amt_type == 1)
                                                                                {
                                                                                    $amount_type = 'Cash';
                                                                                }
                                                                                else if($amt_type == 2)
                                                                                {
                                                                                    $amount_type = 'Cheque';
                                                                                }
                                                                                else if($amt_type == 3)
                                                                                {
                                                                                    $amount_type = 'Others';
                                                                                }
                                                                                else if($amt_type == 4)
                                                                                {
                                                                                    $amount_type = 'Credit Note';
                                                                                }
                                                                                else
                                                                                {
                                                                                    $amount_type = '---';
                                                                                }

                                                                                ?>
                                                                                    <tr>
                                                                                        <td><?php echo $bill_no; ?></td>
                                                                                        <td><?php echo $amount; ?></td>
                                                                                        <td><?php echo $discount; ?></td>
                                                                                        <td><?php echo $amount_type; ?></td>
                                                                                        <td><?php echo date('d-M-Y', strtotime($date)); ?></td>
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
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
        

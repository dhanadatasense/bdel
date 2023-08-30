<?php
    $product_count    = !empty($data_value['product_count'])?$data_value['product_count']:'0';
    $purchase_count   = !empty($data_value['purchase_count'])?$data_value['purchase_count']:'0';
    $production_count = !empty($data_value['production_count'])?$data_value['production_count']:'0';
    $purchase_data    = !empty($data_value['purchase_data'])?$data_value['purchase_data']:'';
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
                                                <h3 class="info"><?php echo $product_count; ?></h3>
                                                <h6>Products</h6>
                                            </div>
                                            <div>
                                                <i class="la la-newspaper-o info font-large-2 float-right"></i>
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
                                                <h3 class="warning"><?php echo $purchase_count; ?></h3>
                                                <h6>Purchase</h6>
                                            </div>
                                            <div>
                                                <i class="la la-cart-arrow-down warning font-large-2 float-right"></i>
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
                                                <h3 class="success"><?php echo $production_count; ?></h3>
                                                <h6>Production</h6>
                                            </div>
                                            <div>
                                                <i class="ft-check-square success font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="recent-transactions" class="col-12">
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
                                                                <th class="border-top-0">Vendor Number</th>
                                                                <th class="border-top-0">Contact No</th>
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
                                                                                <td class="border-top-0"><?php echo $vendor_name; ?></td>
                                                                                <td class="border-top-0"><?php echo $contact_no; ?></td>
                                                                                <td class="border-top-0"><?php echo date('d-M-Y', strtotime($order_date)); ?></td>
                                                                                <td class="border-top-0"><?php echo $order_view; ?></td>
                                                                                <td class="border-top-0"><a href="<?php echo BASE_URL; ?>index.php/vendors/purchase/view_hoisst_purchase/View/<?php echo $po_id; ?>" class="button_clr btn btn-primary"><i class="ft-file-text"></i> View </a></td>
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
                </div>
            </div>
        </div>
        

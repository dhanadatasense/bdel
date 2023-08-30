<?php
	if ($this->session->userdata('random_value') == '')
    redirect(base_url() . 'index.php?login', 'refresh');

 $order_data = array(
    'method'           => '_access',
    'distributor_id'   => $this->session->userdata('id'),
 );

  $data_save = avul_call(API_URL.'distributors/api/distributors',$order_data);
  $access= !empty($data_save['data'])?$data_save['data']:'';

?>   
        <div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true">
            <div class="main-menu-content">
                <input type="hidden" class="geturl" value="<?php echo BASE_URL;?>">
                <input type="hidden" class="apiurl" value="<?php echo API_URL;?>">
                <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                    <li class="<?php if($currentmenu == 'dashboard') echo 'active'; ?>">
                        <a href="<?php echo BASE_URL;?>index.php/distributors/dashboard"><i class="ft-airplay"></i><span class="menu-title">Dashboard</span></a>
                    </li>

                    <li class=" nav-item">
                        <a href="#"><i class="ft ft-cpu"></i><span class="menu-title" data-i18n="Calendars">Masters</span></a>
                        <ul class="menu-content">
                            <li>
                                <a class="menu-item" href="#"><i class="ft-chevron-right"></i><span data-i18n="Full Calendar">Beat</span></a>
                                <ul class="menu-content">
                                    <li class="<?php if($currentmenu == 'add_beat') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/masters/add_beat"><i></i><span data-i18n="Basic">Add Beat</span></a>
                                    </li>
                                    <li class="<?php if($currentmenu == 'list_beat') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/masters/list_beat"><i></i><span data-i18n="Events">Manage Beat</span></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="<?php if($currentmenu == 'list_product') echo 'active'; ?>">
                        <a href="<?php echo BASE_URL;?>index.php/distributors/catlog/list_product"><i class="la la-newspaper-o"></i><span class="menu-title">Product</span></a>
                    </li>
                    <li class=" nav-item">
                        <a href=""><i class="fa ft-users"></i><span class="menu-title" data-i18n="Dashboard">Employee</span></a>
                        <ul class="menu-content">
                            <li class="<?php if($currentmenu == 'add_employee') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/employee/add_employee"><i></i><span>Add Employee</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'list_employee') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/employee/list_employee"><i></i><span>Manage Employee</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class=" nav-item">
                        <a href=""><i class="icon-handbag"></i><span class="menu-title" data-i18n="Dashboard">Outlets</span></a>
                        <ul class="menu-content">
                            <li class="<?php if($currentmenu == 'add_outlets') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/outlets/add_outlets"><i></i><span>Add Outlet</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'list_outlets') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/outlets/list_outlets"><i></i><span>Manage Outlet</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'upload_outlets') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/outlets/upload_outlets"><i></i><span>Upload Outlet</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class=" nav-item">
                        <a href=""><i class="icon-grid"></i><span class="menu-title" data-i18n="Dashboard">Assign Beat</span></a>
                        <ul class="menu-content">
                            <li class="<?php if($currentmenu == 'add_assign_shop') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/assignshop/add_assign_beat"><i></i><span>Add Assign Beat</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'list_assign_shop') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/assignshop/list_assign_beat"><i></i><span>Manage Assign Beat</span></a>
                            </li>
                        </ul>
                    </li>

                    <?php if($access==1): ?>
                    <li class=" nav-item">
                        <a href=""><i class="icon icon-users"></i><span class="menu-title" data-i18n="Dashboard">Distributors</span></a>
                        <ul class="menu-content">
                            <li class="<?php if($currentmenu == 'add_sub_distributors') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/distributor/add_distributors"><i></i><span>Add Distributors</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'list_sub_distributors') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/distributor/list_distributors"><i></i><span>Manage Distributors</span></a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if($access==1): ?>
                    <li class="<?php if($currentmenu == 'distributor_list') echo 'active'; ?>">
                        <a href="<?php echo BASE_URL;?>index.php/distributors/assignproduct/distributor_list"><i class="ft-check-square"></i><span class="menu-title">Assign Product</span></a>
                    </li>
                    <?php endif; ?>
                    <?php if($access==1): ?>
                    <li class=" nav-item">
                        <a href=""><i class="icon-tag"></i><span class="menu-title" data-i18n="Dashboard">Price Master</span></a>
                        <ul class="menu-content">
                           
                            <li class="<?php if($currentmenu == 'distributor_price_master') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/pricemaster/distributor_price_master"><i></i><span>Distributors Price Master</span></a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a href=""><i class="ft-shopping-cart"></i><span class="menu-title" data-i18n="Dashboard">Purchase Order</span></a>
                        <ul class="menu-content">
                            <li class="<?php if($currentmenu == 'add_purchase') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/purchase/add_purchase"><i></i><span>Add Purchase</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'list_purchase') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/purchase/list_purchase"><i></i><span>Manage Purchase</span></a>
                            </li>
                        </ul>
                    </li> 
                   
                    <li class="nav-item">
                        <a href=""><i class="ft-shopping-cart"></i><span class="menu-title" data-i18n="Dashboard">Delivey Challan</span></a>
                        <ul class="menu-content">
                            <li class="<?php if($currentmenu == 'add_challan') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/challan/add_challan"><i></i><span>Add Challan</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'list_challan') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/challan/list_challan"><i></i><span>View Challan</span></a>
                            </li>
                        </ul>
                    </li> 

                    <li class="nav-item">
                        <a href=""><i class="icon-loop"></i><span class="menu-title" data-i18n="Dashboard">Purchase Return</span></a>
                        <ul class="menu-content">
                            <li class="<?php if($currentmenu == 'add_purchase_return') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/purchase/add_purchase_return"><i></i><span>Add Purchase Return</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'list_purchase_return') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/purchase/list_purchase_return"><i></i><span>Manage Purchase Return</span></a>
                            </li>
                        </ul>
                    </li> 

                    <!-- <li class="nav-item">
                        <a href=""><i class="ft-repeat"></i><span class="menu-title" data-i18n="Dashboard">Delivery Challan</span></a>
                        <ul class="menu-content">
                            <li class="<?php if($currentmenu == 'add_delivery_challan') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/delivery/add_delivery_challan"><i></i><span>Add Delivery Challan</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'list_delivery_challan') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/delivery/list_delivery_challan"><i></i><span>Manage Delivery Challan</span></a>
                            </li>
                        </ul>
                    </li>  -->

                    <?php
                        if($this->session->userdata('distributor_status') == '2')
                        {
                            ?>
                                <li class="<?php if($currentmenu == 'add_inventory') echo 'active'; ?>">
                                    <a href="<?php echo BASE_URL;?>index.php/distributors/purchase/add_inventory"><i class="ft-shopping-cart"></i><span class="menu-title">Inventory</span></a>
                                </li>                                        
                            <?php
                        }
                    ?>
                    
                    <li class=" nav-item">
                        <a href=""><i class="icon-notebook"></i><span class="menu-title" data-i18n="Dashboard">Outlet Orders</span></a>
                        <ul class="menu-content">
                            <li class="<?php if($currentmenu == 'overall_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/order/overall_order"><i></i><span>All</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'process_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/order/process_order"><i></i><span>Approved</span></a>
                            </li>
                            <!-- <li class="<?php if($currentmenu == 'packing_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/order/packing_order"><i></i><span>Packing</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'ready_to_shipping_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/order/ready_to_shipping_order"><i></i><span>Ready to shipping</span></a>
                            </li> -->
                            <li class="<?php if($currentmenu == 'invoice_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/order/invoice_order"><i></i><span>Invoice</span></a>
                            </li>
                            <!-- <li class="<?php if($currentmenu == 'shipping_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/order/shipping_order"><i></i><span>Shipping</span></a>
                            </li> -->
                            <li class="<?php if($currentmenu == 'delivery_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/order/delivery_order"><i></i><span>Delivered</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'cancel_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/order/cancel_order"><i></i><span>Cancel Invoice</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class=" nav-item">
                        <a href=""><i class="icon-grid"></i><span class="menu-title" data-i18n="Dashboard">Assign Bill</span></a>
                        <ul class="menu-content">
                            <li class="<?php if($currentmenu == 'add_assign_bill') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/assignshop/add_assign_shop"><i></i><span>Add Assign Bill</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'list_assign_bill') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/assignshop/list_assign_shop"><i></i><span>Manage Assign Bill</span></a>
                            </li>
                        </ul>
                    </li>

                    <?php if($access==1): ?>
                    <li class=" nav-item">
                        <a href=""><i class="icon-drawer"></i><span class="menu-title" data-i18n="Dashboard">Distributors Order</span> <span class="badge badge badge-pill badge-danger float-right mr-2 admDis_ord hide">New</span></a>
                        <ul class="menu-content">
                            <ul class="menu-content">
                            <li class="<?php if($currentmenu == 'br_overall_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/Branchpurchase/br_overall_order"><i></i><span>All</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'br_success_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/Branchpurchase/br_success_order"><i></i><span>Success</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'br_process_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/Branchpurchase/br_process_order"><i></i><span>Approved</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'br_packing_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/Branchpurchase/br_packing_order"><i></i><span>Packing</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'br_invoice_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/Branchpurchase/br_invoice_order"><i></i><span>Invoice</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'br_shipping_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/Branchpurchase/br_shipping_order"><i></i><span>Shipping</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'br_delivery_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/Branchpurchase/br_delivery_order"><i></i><span>Delivered</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'br_complete_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/Branchpurchase/br_complete_order"><i></i><span>Complete</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'br_cancle_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/Branchpurchase/br_cancle_order"><i></i><span>Cancel</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'br_cancle_invoice') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/Branchpurchase/br_cancle_invoice"><i></i><span>Cancel Invoice</span></a>
                            </li>
                        </ul>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if($access==1): ?>
                    <li class=" nav-item">
                        <a href=""><i class="icon-drawer"></i><span class="menu-title" data-i18n="Dashboard">Branch Order</span> <span class="badge badge badge-pill badge-danger float-right mr-2 admDis_ord hide">New</span></a>
                        <ul class="menu-content">
                            
                            <li class="<?php if($currentmenu == 'br_overall_order_dc') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/branchorders/br_overall_order"><i></i><span>All</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'br_success_order_dc') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/Branchorders/br_success_order"><i></i><span>Success</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'br_process_order_dc') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/Branchorders/br_process_order"><i></i><span>Approved</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'br_packing_order_dc') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/Branchorders/br_packing_order"><i></i><span>Packing</span></a>
                            </li>
                       
                            <li class="<?php if($currentmenu == 'br_shipping_order_dc') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/Branchorders/br_shipping_order"><i></i><span>Shipping</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'br_delivery_order_dc') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/Branchorders/br_delivery_order"><i></i><span>Delivered</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'br_complete_order_dc') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/Branchorders/br_complete_order"><i></i><span>Complete</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'br_cancle_order_dc') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/Branchorders/br_cancle_order"><i></i><span>Cancel</span></a>
                            </li>
                           
                       
                        </ul>
                    </li>
                    <?php endif; ?>
                    <li class="<?php if($currentmenu == 'outlet_payment') echo 'active'; ?>">
                        <a href="<?php echo BASE_URL;?>index.php/distributors/payment/list_outlets"><i class="icon-wallet"></i><span class="menu-title">Outlets Receipt</span></a>
                    </li>

                    <li class="nav-item">
                        <a href=""><i class="icon-loop"></i><span class="menu-title" data-i18n="Dashboard">Sales Return</span></a>
                        <ul class="menu-content">
                        <?php if($access==1): ?>
                            <li class="<?php if($currentmenu == 'add_distributor_sales_return') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/salesreturn/add_distributor_sales_return"><i></i><span>Add distributor sales Return</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'list_distributor_sales_return') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/salesreturn/list_distributors_sales_return"><i></i><span>List distributor sales Return</span></a>
                            </li>
                            <?php endif; ?>
                            <li class="<?php if($currentmenu == 'add_sales_return') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/salesreturn/add_sales_return"><i></i><span>Add Sales Return</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'list_sales_return') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/salesreturn/list_sales_return"><i></i><span>Manage Sales Return</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href=""><i class="icon-cloud-download"></i><span class="menu-title" data-i18n="Dashboard">Stock Entry</span></a>
                        <ul class="menu-content">
                            <li class="<?php if($currentmenu == 'stock_entry') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/purchase/stock_entry"><i></i><span>Add Stock Entry</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'manage_stock') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/purchase/manage_stock"><i></i><span>Manage Stock Entry</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href=""><i class="ft-bar-chart-2"></i><span class="menu-title" data-i18n="Dashboard">Report</span></a>
                        <ul class="menu-content">
                        <?php if($access==1): ?>
                            <li class="<?php if($currentmenu == 'sub_dis_outlet_sales_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/report/sub_dis_outlet_sales_report"><i></i><span>Distributor Outlet Sales Report</span></a>
                            </li>
                            <?php endif; ?>
                            <?php if($access==1): ?>
                            <li class="<?php if($currentmenu == 'sub_distributor_sales_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/report/sub_distributor_sales_report"><i></i><span>Distributor Sales Report</span></a>
                            </li>
                            <?php endif; ?>
                            <li class="<?php if($currentmenu == 'purchase_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/report/purchase_report"><i></i><span>Purchase Report</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'sales_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/report/sales_report"><i></i><span>Sales Report</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'outlet_outstanding') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/report/outlet_outstanding"><i></i><span>Outlet Outstanding Report</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'stock_entry_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/report/stock_entry_report"><i></i><span>Stock Entry Report</span></a>
                            </li>
                            <?php
                                if($this->session->userdata('distributor_status') == '2')
                                {
                                    ?>
                                        <li class="<?php if($currentmenu == 'inventory_report') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/report/inventory_report"><i></i><span>Inventory Report</span></a>
                                        </li>
                                    <?php
                                }
                            ?>
                            <li class="<?php if($currentmenu == 'product_stock') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/report/product_stock"><i></i><span>Product Stock</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'sales_return') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/report/sales_return"><i></i><span>Sales Return</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'overall_outstanding') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/report/overall_outstanding"><i></i><span>Overall Outstanding Report</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'attendance_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/report/attendance_report"><i></i><span>Attendance Report</span></a>
                            </li>
                            <!-- <li class="<?php if($currentmenu == 'payment_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/report/payment_report"><i></i><span>Payment Report</span></a>
                            </li> -->
                        </ul>
                    </li>

                    <li class=" nav-item">
                        <a href=""><i class="icon-notebook"></i><span class="menu-title" data-i18n="Dashboard">Accounts</span></a>
                        <ul class="menu-content">
                            <li class="<?php if($currentmenu == 'cashbook_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/accounts/cashbook"><i></i><span>Cashbook</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'bank_entry') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/accounts/bank_entry"><i></i><span>Bank Entry</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'outlet_ledger') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/distributors/accounts/outlet_ledger"><i></i><span>Outlet Ledger</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class="">
                        <a href="<?php echo BASE_URL;?>index.php/welcome/logout"><i class="ft-power"></i><span class="menu-title">Logout</span></a>
                    </li>
                </ul>
            </div>
        </div>
        
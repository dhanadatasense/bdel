        <div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true">
            <div class="main-menu-content">
                <input type="hidden" class="geturl" value="<?php echo BASE_URL;?>">
                <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                    <li class="<?php if($currentmenu == 'dashboard') echo 'active'; ?>">
                        <a href="<?php echo BASE_URL;?>index.php/vendors/dashboard"><i class="ft-airplay"></i><span class="menu-title">Dashboard</span></a>
                    </li>

                    <li class="<?php if($currentmenu == 'list_product') echo 'active'; ?>">
                        <a href="<?php echo BASE_URL;?>index.php/vendors/catlog/list_product"><i class="la la-newspaper-o"></i><span class="menu-title">Product</span></a>
                    </li>
                    
                    <?php
                        if($this->session->userdata('vendor_type') == '2')
                        {
                            ?>
                                <li class=" nav-item">
                                    <a href=""><i class="fa ft-users"></i><span class="menu-title" data-i18n="Dashboard">Employee</span></a>
                                    <ul class="menu-content">
                                        <li class="<?php if($currentmenu == 'add_employee') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/employee/add_employee"><i></i><span>Add Employee</span></a>
                                        </li>
                                        <li class="<?php if($currentmenu == 'list_employee') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/employee/list_employee"><i></i><span>Manage Employee</span></a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="<?php if($currentmenu == 'list_outlets') echo 'active'; ?>">
                                    <a href="<?php echo BASE_URL;?>index.php/vendors/outlets/list_outlets"><i class="icon-handbag"></i><span class="menu-title">Outlets</span></a>
                                </li>
                                <li class=" nav-item">
                                    <a href=""><i class="icon-grid"></i><span class="menu-title" data-i18n="Dashboard">Assign Shop</span></a>
                                    <ul class="menu-content">
                                        <li class="<?php if($currentmenu == 'add_assign_shop') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/assignshop/add_assign_shop"><i></i><span>Add Assign Shop</span></a>
                                        </li>
                                        <li class="<?php if($currentmenu == 'list_assign_shop') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/assignshop/list_assign_shop"><i></i><span>Manage Assign Shop</span></a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="<?php if($currentmenu == 'outlet_payment') echo 'active'; ?>">
                                    <a href="<?php echo BASE_URL;?>index.php/vendors/payment/list_outlets"><i class="icon-wallet"></i><span class="menu-title">Outlets Payment</span></a>
                                </li>
                            <?php
                        }
                    ?>

                    <?php
                        if($this->session->userdata('vendor_type') == '1')
                        {
                            ?>
                                <li class="<?php if($currentmenu == 'list_hoisst_order') echo 'active'; ?>">
                                    <a href="<?php echo BASE_URL;?>index.php/vendors/purchase/list_hoisst_order"><i class="ft-shopping-cart"></i><span class="menu-title">Admin Order</span></a>
                                </li>

                                <li class=" nav-item">
                                    <a href=""><i class="icon icon-loop"></i><span class="menu-title" data-i18n="Dashboard">Work order</span></a>
                                    <ul class="menu-content">
                                        <li class="<?php if($currentmenu == 'add_production') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/production/add_production"><i></i><span>Add Work order</span></a>
                                        </li>
                                        <li class="<?php if($currentmenu == 'list_production') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/production/list_production"><i></i><span>Manage Work order</span></a>
                                        </li>
                                    </ul>
                                </li>

                                <li class=" nav-item">
                                    <a href=""><i class="icon-notebook"></i><span class="menu-title" data-i18n="Dashboard">Outlet Orders</span></a>
                                    <ul class="menu-content">
                                        <li class="<?php if($currentmenu == 'process_order') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/order/process_order"><i></i><span>Approved</span></a>
                                        </li>
                                        <li class="<?php if($currentmenu == 'packing_order') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/order/packing_order"><i></i><span>Packing</span></a>
                                        </li>
                                        <li class="<?php if($currentmenu == 'shipping_order') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/order/shipping_order"><i></i><span>Shipping</span></a>
                                        </li>
                                        <li class="<?php if($currentmenu == 'invoice_order') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/order/invoice_order"><i></i><span>Invoice</span></a>
                                        </li>
                                        <li class="<?php if($currentmenu == 'delivery_order') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/order/delivery_order"><i></i><span>Delivered</span></a>
                                        </li>
                                    </ul>
                                </li>
                            <?php
                        }
                    ?>
                    <?php
                        if($this->session->userdata('vendor_type') == '2')
                        {
                            ?>
                                <li class=" nav-item">
                                    <a href=""><i class="icon-notebook"></i><span class="menu-title" data-i18n="Dashboard">Outlet Orders</span></a>
                                    <ul class="menu-content">
                                        <li class="<?php if($currentmenu == 'process_order') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/order/process_order"><i></i><span>Approved</span></a>
                                        </li>
                                        <li class="<?php if($currentmenu == 'packing_order') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/order/packing_order"><i></i><span>Packing</span></a>
                                        </li>
                                        <li class="<?php if($currentmenu == 'shipping_order') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/order/shipping_order"><i></i><span>Shipping</span></a>
                                        </li>
                                        <li class="<?php if($currentmenu == 'invoice_order') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/order/invoice_order"><i></i><span>Invoice</span></a>
                                        </li>
                                        <li class="<?php if($currentmenu == 'delivery_order') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/order/delivery_order"><i></i><span>Delivered</span></a>
                                        </li>
                                    </ul>
                                </li>
                            <?php
                        }
                    ?>
                    <?php
                        if($this->session->userdata('vendor_type') == '2')
                        {
                            ?>
                                <li class=" nav-item">
                                    <a href=""><i class="icon icon-loop"></i><span class="menu-title" data-i18n="Dashboard">Work order</span></a>
                                    <ul class="menu-content">
                                        <li class="<?php if($currentmenu == 'add_work_order') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/workorder/add_work_order"><i></i><span>Add Work order</span></a>
                                        </li>
                                        <li class="<?php if($currentmenu == 'list_work_order') echo 'active'; ?>">
                                            <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/workorder/list_work_order"><i></i><span>Manage Work order</span></a>
                                        </li>
                                    </ul>
                                </li>
                            <?php
                        }
                    ?>

                    <li class="nav-item">
                        <a href=""><i class="icon-cloud-download"></i><span class="menu-title" data-i18n="Dashboard">Stock Entry</span></a>
                        <ul class="menu-content">
                            <li class="<?php if($currentmenu == 'stock_entry') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/purchase/stock_entry"><i></i><span>Add Stock Entry</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'manage_stock') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/purchase/manage_stock"><i></i><span>Manage Stock Entry</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href=""><i class="ft-bar-chart-2"></i><span class="menu-title" data-i18n="Dashboard">Report</span></a>
                        <ul class="menu-content">
                            <li class="<?php if($currentmenu == 'sales_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/report/sales_report"><i></i><span>Sales Report</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'stock_entry_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/report/stock_entry_report"><i></i><span>Stock Entry Report</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'product_stock') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL;?>index.php/vendors/report/product_stock"><i></i><span>Product Stock</span></a>
                            </li>
                        </ul>
                    </li>

                    <!-- <li class="nav-item">
                        <a href=""><i class="icon-settings"></i><span class="menu-title" data-i18n="Dashboard">Settings</span></a>
                        <ul class="menu-content">
                            <li class="<?php if($currentmenu == 'profile_settings') echo 'active'; ?>">
                                <a class="menu-item" href="#"><i></i><span>Profile Setting</span></a>
                            </li>
                            <li class="<?php if($currentmenu == 'change_password') echo 'active'; ?>">
                                <a class="menu-item" href="#"><i></i><span>Change Password</span></a>
                            </li>
                        </ul>
                    </li> -->
                    <li class="">
                        <a href="<?php echo BASE_URL;?>index.php/welcome/logout"><i class="ft-power"></i><span class="menu-title">Logout</span></a>
                    </li>
                </ul>
            </div>
        </div>
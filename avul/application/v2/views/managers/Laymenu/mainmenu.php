<?php
if ($this->session->userdata('random_value') == '')
redirect(base_url() . 'index.php?login', 'refresh');


$position   = $this->session->userdata('designation_code');


?>
<div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true">
    <div class="main-menu-content">
        <input type="hidden" class="geturl" value="<?php echo BASE_URL; ?>">
        <input type="hidden" class="apiurl" value="<?php echo API_URL;?>">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="<?php if ($currentmenu == 'dashboard') echo 'active'; ?>">
                <a href="<?php echo BASE_URL; ?>index.php/managers/dashboard"><i class="ft-airplay"></i><span class="menu-title">Dashboard</span></a>
            </li>
            <!-- <li class="<?php if ($currentmenu == 'list_product') echo 'active'; ?>">
                <a href="<?php echo BASE_URL; ?>index.php/managers/catlog/list_product"><i class="la la-newspaper-o"></i><span class="menu-title">Product</span></a>
            </li> -->



            <!-- <li class=" nav-item">
                <a href=""><i class="icon icon-users"></i><span class="menu-title" data-i18n="Dashboard">Attendace</span></a>
                <ul class="menu-content">

                    <li class="<?php if ($currentmenu == 'list_rsm') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/managers/list_rsm"><i></i><span>Regional Sales Manager</span></a>
                    </li>


                    <li class="<?php if ($currentmenu == 'list_asm') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/managers/list_asm"><i></i><span>Area Sales Manager</span></a>
                    </li>

                    <li class="<?php if ($currentmenu == 'list_so') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/managers/list_so"><i></i><span>Sales Officers</span></a>
                    </li>


                    <li class="<?php if ($currentmenu == 'list_tsi') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/managers/list_tsi"><i></i><span>Territory Sales Incharge</span></a>
                    </li>
                    <li class="<?php if ($currentmenu == 'list_bde') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/managers/list_bde"><i></i><span>Business development executives</span></a>
                    </li>


                </ul>
            </li> -->

            
                <!-- <li class="<?php if ($currentmenu == 'create_order') echo 'active'; ?>">
                    <a href="<?php echo BASE_URL; ?>index.php/managers/order/create_order"><i class="icon-basket-loaded"></i><span class="menu-title">Sales Order</span></a>
                </li> -->
          
<!-- 
            <li class="<?php if ($currentmenu == 'list_outlets') echo 'active'; ?>">
                <a href="<?php echo BASE_URL; ?>index.php/managers/outlets/list_outlets"><i class="icon-handbag"></i><span class="menu-title">Outlets</span></a>
            </li> -->
            <?php if ($position == 'TSI') : ?>
            <li class=" nav-item">
                <a href=""><i class="icon-grid"></i><span class="menu-title" data-i18n="Dashboard">Assign Beat</span></a>
                <ul class="menu-content">
                    <li class="<?php if ($currentmenu == 'add_assign_shop') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/assignshop/add_assign_shop"><i></i><span>Add Assign Beat</span></a>
                    </li>
                    <li class="<?php if ($currentmenu == 'list_assign_shop') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/assignshop/list_assign_shop"><i></i><span>Manage Assign Beat</span></a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>

        
            <li class=" nav-item">
                <a href=""><i class="icon icon-users"></i><span class="menu-title" data-i18n="Dashboard">Distributors</span></a>
                <ul class="menu-content">
                          
                    <li class="<?php if($currentmenu == 'add_distributors') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL;?>index.php/managers/distributors/add_distributors"><i></i><span>Add Distributors</span></a>
                    </li>
                          
                    

                    <li class="<?php if($currentmenu == 'list_distributors') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL;?>index.php/managers/distributors/list_distributors"><i></i><span>Manage Distributors</span></a>
                    </li>
                            
                </ul>
            </li>
            <?php if ($position == 'RSM'||$position == 'ASM') : ?>
            <li class="<?php if($currentmenu == 'list_dis_approval') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL;?>index.php/managers/distributors/list_dis_approval"><i class="ft-check-square"></i><span class="menu-title">Distributors Approval</span></a>
            </li>
            <?php endif; ?>
           
            <li class=" nav-item">
                <a href=""><i class="icon icon-handbag"></i><span class="menu-title" data-i18n="Dashboard">Outlets</span></a>
                <ul class="menu-content">
                            
                    <li class="<?php if($currentmenu == 'add_outlets') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL;?>index.php/managers/Outlet/add_outlets"><i></i><span>Add Outlets</span></a>
                    </li>
                           
                    <li class="<?php if($currentmenu == 'list_outlets') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL;?>index.php/managers/Outlet/list_outlets"><i></i><span>Manage Outlets</span></a>
                    </li>
                          
                </ul>
            </li>
                  
            <!-- <?php if ($access == 1) : ?>
                    <li class=" nav-item">
                        <a href=""><i class="icon icon-users"></i><span class="menu-title" data-i18n="Dashboard">Distributors</span></a>
                        <ul class="menu-content">
                            <li class="<?php if ($currentmenu == 'add_sub_distributors') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/distributors/distributor/add_distributors"><i></i><span>Add Distributors</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'list_sub_distributors') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/distributors/distributor/list_distributors"><i></i><span>Manage Distributors</span></a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?> -->
            <!-- <?php if ($access == 1) : ?>
                <li class="<?php if ($currentmenu == 'distributor_list') echo 'active'; ?>">
                    <a href="<?php echo BASE_URL; ?>index.php/managers/assignproduct/distributor_list"><i class="ft-check-square"></i><span class="menu-title">Assign Product</span></a>
                </li>
            <?php endif; ?> -->
            <!-- <?php if ($access == 1) : ?>
                    <li class=" nav-item">
                        <a href=""><i class="icon-tag"></i><span class="menu-title" data-i18n="Dashboard">Price Master</span></a>
                        <ul class="menu-content">
                           
                            <li class="<?php if ($currentmenu == 'distributor_price_master') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/distributors/pricemaster/distributor_price_master"><i></i><span>Distributors Price Master</span></a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?> -->
            <!-- <li class="nav-item">
                        <a href=""><i class="ft-shopping-cart"></i><span class="menu-title" data-i18n="Dashboard">Purchase Order</span></a>
                        <ul class="menu-content">
                            <li class="<?php if ($currentmenu == 'add_purchase') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/distributors/purchase/add_purchase"><i></i><span>Add Purchase</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'list_purchase') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/distributors/purchase/list_purchase"><i></i><span>Manage Purchase</span></a>
                            </li>
                        </ul>
                    </li> 
                   
                    <li class="nav-item">
                        <a href=""><i class="ft-shopping-cart"></i><span class="menu-title" data-i18n="Dashboard">Delivey Challan</span></a>
                        <ul class="menu-content">
                            <li class="<?php if ($currentmenu == 'add_challan') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/distributors/challan/add_challan"><i></i><span>Add Challan</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'list_challan') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/distributors/challan/list_challan"><i></i><span>View Challan</span></a>
                            </li>
                        </ul>
                    </li> 

                    <li class="nav-item">
                        <a href=""><i class="icon-loop"></i><span class="menu-title" data-i18n="Dashboard">Purchase Return</span></a>
                        <ul class="menu-content">
                            <li class="<?php if ($currentmenu == 'add_purchase_return') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/distributors/purchase/add_purchase_return"><i></i><span>Add Purchase Return</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'list_purchase_return') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/distributors/purchase/list_purchase_return"><i></i><span>Manage Purchase Return</span></a>
                            </li>
                        </ul>
                    </li>  -->

            <!-- <li class="nav-item">
                        <a href=""><i class="ft-repeat"></i><span class="menu-title" data-i18n="Dashboard">Delivery Challan</span></a>
                        <ul class="menu-content">
                            <li class="<?php if ($currentmenu == 'add_delivery_challan') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/distributors/delivery/add_delivery_challan"><i></i><span>Add Delivery Challan</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'list_delivery_challan') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/distributors/delivery/list_delivery_challan"><i></i><span>Manage Delivery Challan</span></a>
                            </li>
                        </ul>
                    </li>  -->


       

            
            <!-- <li class="<?php if ($currentmenu == 'outlet_payment') echo 'active'; ?>">
                <a href="<?php echo BASE_URL; ?>index.php/distributors/payment/list_outlets"><i class="icon-wallet"></i><span class="menu-title">Outlets Receipt</span></a>
            </li> -->

         

            

                
                <li class="nav-item">
                    <a href=""><i class="ft-bar-chart-2"></i><span class="menu-title" data-i18n="Dashboard">Report</span></a>
                    <ul class="menu-content">
                       
                            <li class="<?php if ($currentmenu == 'attendance_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/report/attendance_report"><i></i><span>Attendace Report</span></a>
                            </li>
                          
                            <li class="<?php if ($currentmenu == 'attendance_details_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/report/attendance_details_report"><i></i><span>Attendace Details Report</span></a>
                            </li>
                      
                       
                      
                            <li class="<?php if ($currentmenu == 'outlet_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/report/outlet_order"><i></i><span>Outlet Order Report</span></a>
                            </li>
                       
                            <!-- <li class="<?php if ($currentmenu == 'purchase_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/purchase_report"><i></i><span>Purchase Report</span></a>
                            </li> -->
                       
                            <!-- <li class="<?php if ($currentmenu == 'vendor_purchase') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/vendor_purchase"><i></i><span>Vendor Purchase Report</span></a>
                            </li>
                       
                            <li class="<?php if ($currentmenu == 'purchase_return') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/purchase_return"><i></i><span>Purchase Return Report</span></a>
                            </li>
                      
                            <li class="<?php if ($currentmenu == 'vendor_overall') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/vendor_overall"><i></i><span>Vendor Overall Report</span></a>
                            </li> -->
                       
                            <li class="<?php if ($currentmenu == 'beat_outlet') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/report/beat_outlet"><i></i><span>Beat Wise Outlet</span></a>
                            </li>
                       
                            <li class="<?php if ($currentmenu == 'outlet_history') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/report/outlet_history"><i></i><span>Outlet History</span></a>
                            </li>
<!--                         
                            <li class="<?php if ($currentmenu == 'distributor_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/distributor_order"><i></i><span>Distributor Order Report</span></a>
                            </li> -->
                     
                            <li class="<?php if ($currentmenu == 'outlet_overall') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/report/outlet_overall"><i></i><span>Outlet Overall Report</span></a>
                            </li>
                       
                            <!-- <li class="<?php if ($currentmenu == 'outlet_invoice') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/outlet_invoice"><i></i><span>Outlet Invoice Report</span></a>
                            </li> -->
                       
                            <!-- <li class="<?php if ($currentmenu == 'order_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/report/order_report"><i></i><span>Overall Order Report</span></a>
                            </li> -->
                        
                            <li class="<?php if ($currentmenu == 'target_achievement') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/report/target_achievement"><i></i><span>Target Achievement</span></a>
                            </li>
                        
                            <li class="<?php if ($currentmenu == 'employee_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/report/employee_order"><i></i><span>Employee Order Value</span></a>
                            </li>
                    
                            <!-- <li class="<?php if ($currentmenu == 'product_stock') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/product_stock"><i></i><span>Product Stock</span></a>
                            </li> -->
                        
                            <!-- <li class="<?php if ($currentmenu == 'distributor_stock') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/distributor_stock"><i></i><span>Distributor Product Stock</span></a>
                            </li>
                        
                            <li class="<?php if ($currentmenu == 'sales_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/sales_report"><i></i><span>Distributor Sales Report</span></a>
                            </li> -->
                        
                            <!-- <li class="<?php if ($currentmenu == 'sales_return') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/sales_return"><i></i><span>Sales Return Report</span></a>
                            </li>
                      
                        <li class="<?php if ($currentmenu == 'sub_dis_outlet_sales_report') echo 'active'; ?>">
                            <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/sub_dis_outlet_sales_report"><i></i><span>Distributor Outlet Sales Report</span></a>
                        </li> -->

                        
                            <!-- <li class="<?php if ($currentmenu == 'expense_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/expense_report"><i></i><span>Expense Report</span></a>
                            </li>
                       
                            <li class="<?php if ($currentmenu == 'stock_entry_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/stock_entry_report"><i></i><span>Stock Entry Report</span></a>
                            </li> -->
                        
                            <li class="<?php if ($currentmenu == 'employee_target') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/report/employee_target"><i></i><span>Employee Target</span></a>
                            </li>
                       
                            <li class="<?php if ($currentmenu == 'employee_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/report/employee_report"><i></i><span>Employee Daily Report</span></a>
                            </li>
                      
                            <!-- <li class="<?php if ($currentmenu == 'product_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/product_order"><i></i><span>Product Order Report</span></a>
                            </li>
                        
                            <li class="<?php if ($currentmenu == 'backlog_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/backlog_report"><i></i><span>Backlog Report Report</span></a>
                            </li> -->
                      
                    </ul>
                </li>
            
<!-- 
            <li class=" nav-item">
                <a href=""><i class="icon-notebook"></i><span class="menu-title" data-i18n="Dashboard">Accounts</span></a>
                <ul class="menu-content">
                    <li class="<?php if ($currentmenu == 'cashbook_report') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/accounts/cashbook"><i></i><span>Cashbook</span></a>
                    </li>
                    <li class="<?php if ($currentmenu == 'bank_entry') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/accounts/bank_entry"><i></i><span>Bank Entry</span></a>
                    </li>
                    <li class="<?php if ($currentmenu == 'outlet_ledger') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/managers/accounts/outlet_ledger"><i></i><span>Outlet Ledger</span></a>
                    </li>
                </ul>
            </li> -->

            <li class="">
                <a href="<?php echo BASE_URL; ?>index.php/welcome/logout"><i class="ft-power"></i><span class="menu-title">Logout</span></a>
            </li>
        </ul>
    </div>
</div>
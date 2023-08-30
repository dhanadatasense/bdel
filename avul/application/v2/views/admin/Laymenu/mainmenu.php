<?php

$user_accessing  = explode(",", $this->session->userdata('role_list'));

$financial_year_add  = in_array('financial-year-add', $user_accessing);
$financial_year_view = in_array('financial-year-view', $user_accessing);

$state_add  = in_array('state-add', $user_accessing);
$state_view = in_array('state-view', $user_accessing);

$city_add       = in_array('city-add', $user_accessing);
$city_view      = in_array('city-view', $user_accessing);

$beat_add       = in_array('beat-add', $user_accessing);
$beat_view      = in_array('beat-view', $user_accessing);

$privilege_add  = in_array('privilege-add', $user_accessing);
$privilege_view = in_array('privilege-view', $user_accessing);

$unit_add       = in_array('unit-add', $user_accessing);
$unit_view      = in_array('unit-view', $user_accessing);

$variations_add  = in_array('variations-add', $user_accessing);
$variations_view = in_array('variations-view', $user_accessing);

$message_add  = in_array('message-add', $user_accessing);
$message_view = in_array('message-view', $user_accessing);

$expense_add  = in_array('expense-add', $user_accessing);
$expense_view = in_array('expense-view', $user_accessing);

$outlet_category_add  = in_array('outlet-category-add', $user_accessing);
$outlet_category_view = in_array('outlet-category-view', $user_accessing);

$category_add   = in_array('category-add', $user_accessing);
$category_view  = in_array('category-view', $user_accessing);

$product_add      = in_array('product-add', $user_accessing);
$product_view     = in_array('product-view', $user_accessing);

$manufacture_add  = in_array('manufacture-add', $user_accessing);
$manufacture_view = in_array('manufacture-view', $user_accessing);

$user_role_add        = in_array('user-role-add', $user_accessing);
$user_role_view       = in_array('user-role-view', $user_accessing);

$outlets_add          = in_array('outlets-add', $user_accessing);
$outlets_view         = in_array('outlets-view', $user_accessing);

$employee_add         = in_array('employee-add', $user_accessing);
$employee_view        = in_array('employee-view', $user_accessing);

$assign_beat_add      = in_array('assign-beat-add', $user_accessing);
$assign_beat_view     = in_array('assign-beat-view', $user_accessing);

$employee_target_add  = in_array('employee-target-add', $user_accessing);
$employee_target_view = in_array('employee-target-view', $user_accessing);

$product_target_add   = in_array('product-target-add', $user_accessing);
$product_target_view  = in_array('product-target-view', $user_accessing);

$beat_target_add  = in_array('beat-target-add', $user_accessing);
$beat_target_view = in_array('beat-target-view', $user_accessing);

$collaterals_add  = in_array('collaterals-add', $user_accessing);
$collaterals_view = in_array('collaterals-view', $user_accessing);

$distributors_add    = in_array('distributors-add', $user_accessing);
$distributors_view   = in_array('distributors-view', $user_accessing);

$assign_product_add  = in_array('assign-product-add', $user_accessing);
$assign_product_view = in_array('assign-product-view', $user_accessing);

$distributor_price_master_edit = in_array('distributor-price-master-edit', $user_accessing);

$distributor_price_master_view = in_array('distributor-price-master-view', $user_accessing);


$outlet_price_master_edit = in_array('outlet-price-master-edit', $user_accessing);
$outlet_price_master_view = in_array('outlet-price-master-view', $user_accessing);

$purchase_add            = in_array('purchase-add', $user_accessing);
$purchase_view           = in_array('purchase-view', $user_accessing);

$purchase_return_add     = in_array('purchase-return-add', $user_accessing);
$purchase_return_view    = in_array('purchase-return-view', $user_accessing);

$distributors_order_view = in_array('distributors-order-view', $user_accessing);

$sales_return_add  = in_array('sales-return-add', $user_accessing);
$sales_return_view = in_array('sales-return-view', $user_accessing);
$stock_entry_add   = in_array('stock-entry-add', $user_accessing);
$stock_entry_view  = in_array('stock-entry-view', $user_accessing);

$sales_order_add          = in_array('sales-order-add', $user_accessing);
$outlet_orders_view       = in_array('outlet-orders-view', $user_accessing);

$manufacture_payment_add  = in_array('manufacture-payment-add', $user_accessing);
$manufacture_payment_view = in_array('manufacture-payment-view', $user_accessing);

$distributors_receipt_add  = in_array('distributors-receipt-add', $user_accessing);
$distributors_receipt_view = in_array('distributors-receipt-view', $user_accessing);

$expense_entry_add            = in_array('expense-entry-add', $user_accessing);
$expense_entry_view           = in_array('expense-entry-view', $user_accessing);

$attendance_report_view       = in_array('attendance-report-view', $user_accessing);
$outlet_order_report_view     = in_array('outlet-order-report-view', $user_accessing);
$purchas_report_view          = in_array('purchas-report-view', $user_accessing);
$vendor_purchas_report_view   = in_array('vendor-purchas-report-view', $user_accessing);
$purchas_return_report_view   = in_array('purchas-return-report-view', $user_accessing);
$vendor_overall_report_view   = in_array('vendor-overall-report-view', $user_accessing);
$beat_wise_outlet_report_view = in_array('beat-wise-outlet-report-view', $user_accessing);

$outlet_history_view            = in_array('outlet-history-view', $user_accessing);
$distributor_order_report_view  = in_array('distributor-order-report-view', $user_accessing);
$outlet_overall_report_view     = in_array('outlet-overall-report-view', $user_accessing);
$outlet_invoice_report_view     = in_array('outlet-invoice-report-view', $user_accessing);
$overall_order_report_view      = in_array('overall-order-report-view', $user_accessing);
$target_achievement_view        = in_array('target-achievement-view', $user_accessing);
$employee_order_value_view      = in_array('employee-order-value-view', $user_accessing);
$product_stock_view             = in_array('product-stock-view', $user_accessing);
$distributor_product_stock_view = in_array('distributor-product-stock-view', $user_accessing);
$distributor_sales_report_view  = in_array('distributor-sales-report-view', $user_accessing);
$sales_return_report_view       = in_array('sales-return-report-view', $user_accessing);
$expense_report_view            = in_array('expense-report-view', $user_accessing);
$stock_entry_report_view        = in_array('stock-entry-report-view', $user_accessing);
$employee_target_report_view    = in_array('employee-target-report-view', $user_accessing);
$employee_daily_report_view     = in_array('employee-daily-report-view', $user_accessing);
$product_order_report_view      = in_array('product-order-report-view', $user_accessing);
$cashbook_view                  = in_array('cashbook-view', $user_accessing);
$bank_entry_view                = in_array('bank-entry-view', $user_accessing);
$manufacture_ledger_view        = in_array('manufacture-ledger-view', $user_accessing);
$distributor_ledger_view        = in_array('distributor-ledger-view', $user_accessing);
$backlog_report_view            = in_array('backlog-report-view', $user_accessing);

$user_add  = in_array('user-add', $user_accessing);
$user_view = in_array('user-view', $user_accessing);

$product_loyalty_add  = in_array('product-loyalty-add', $user_accessing);
$product_loyalty_view = in_array('product-loyalty-view', $user_accessing);

$month_wise_order_view    = in_array('month-wise-order-view', $user_accessing);
$employee_wise_order_view = in_array('employee-wise-order-view', $user_accessing);
$collection_report_view   = in_array('collection-report-view', $user_accessing);

$delivery_challan_view   = in_array('delivery-challan-view', $user_accessing);
?>
<div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true">
    <div class="main-menu-content">
        <input type="hidden" class="geturl" value="<?php echo BASE_URL; ?>">
        <input type="hidden" class="apiurl" value="<?php echo API_URL; ?>">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="<?php if ($currentmenu == 'dashboard') echo 'active'; ?>">
                <a href="<?php echo BASE_URL; ?>index.php/admin/dashboard"><i class="ft-airplay"></i><span class="menu-title">Dashboard</span></a>
            </li>
            <?php if ($financial_year_add || $financial_year_view || $state_add || $state_view || $city_add || $city_view || $beat_add || $beat_view || $privilege_add || $privilege_view || $unit_add || $unit_view || $variations_add || $variations_view || $message_add || $message_view || $expense_add || $expense_view) : ?>
                <li class=" nav-item">
                    <a href="#"><i class="ft ft-cpu"></i><span class="menu-title" data-i18n="Calendars">Masters</span></a>
                    <ul class="menu-content">
                        <?php if ($financial_year_add || $financial_year_view) : ?>
                            <li>
                                <a class="menu-item" href="#"><i class="ft-chevron-right"></i><span data-i18n="Full Calendar"> Financial Year</span></a>
                                <ul class="menu-content">
                                    <?php if ($financial_year_add) : ?>
                                        <li class="<?php if ($currentmenu == 'add_financial_year') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/add_financial_year"><i></i><span data-i18n="Basic">Add</span></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($financial_year_view) : ?>
                                        <li class="<?php if ($currentmenu == 'list_financial_year') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/list_financial_year"><i></i><span data-i18n="Events">Manage</span></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <?php if ($employee_add || $employee_view) : ?>
                            <li>
                                <a class="menu-item" href="#"><i class="ft-chevron-right"></i><span data-i18n="Full Calendar"> Employee Designation</span></a>
                                <ul class="menu-content">
                                    <?php if ($employee_add) : ?>
                                        <li class="<?php if ($currentmenu == 'add_designation') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/employee/add_designation"><i></i><span data-i18n="Basic">Add</span></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($employee_view) : ?>
                                        <li class="<?php if ($currentmenu == 'list_designation') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/employee/list_designation"><i></i><span data-i18n="Events">Manage</span></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <?php if ($state_add || $state_view) : ?>
                            <li>
                                <a class="menu-item" href="#"><i class="ft-chevron-right"></i><span data-i18n="Full Calendar">State</span></a>
                                <ul class="menu-content">
                                    <?php if ($state_add) : ?>
                                        <li class="<?php if ($currentmenu == 'add_state') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/add_state"><i></i><span data-i18n="Basic">Add</span></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($state_view) : ?>
                                        <li class="<?php if ($currentmenu == 'list_state') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/list_state"><i></i><span data-i18n="Events">Manage</span></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($city_add || $city_view) : ?>
                            <li>
                                <a class="menu-item" href="#"><i class="ft-chevron-right"></i><span data-i18n="Full Calendar">City</span></a>
                                <ul class="menu-content">
                                    <?php if ($city_add) : ?>
                                        <li class="<?php if ($currentmenu == 'add_city') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/add_city"><i></i><span data-i18n="Basic">Add</span></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($city_view) : ?>
                                        <li class="<?php if ($currentmenu == 'list_city') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/list_city"><i></i><span data-i18n="Events">Manage</span></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($beat_add || $beat_view) : ?>
                            <li>
                                <a class="menu-item" href="#"><i class="ft-chevron-right"></i><span data-i18n="Full Calendar">Beat</span></a>
                                <ul class="menu-content">
                                    <?php if ($beat_add) : ?>
                                        <li class="<?php if ($currentmenu == 'add_beat') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/add_beat"><i></i><span data-i18n="Basic">Add</span></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($beat_view) : ?>
                                        <li class="<?php if ($currentmenu == 'list_beat') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/list_beat"><i></i><span data-i18n="Events">Manage</span></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($privilege_add || $privilege_view) : ?>
                            <li style="display: block;">
                                <a class="menu-item" href="#"><i class="ft-chevron-right"></i><span data-i18n="Full Calendar">Privilege</span></a>
                                <ul class="menu-content">
                                    <?php if ($privilege_add) : ?>
                                        <li class="<?php if ($currentmenu == 'add_privilege') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/add_privilege"><i></i><span data-i18n="Basic">Add</span></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($privilege_view) : ?>
                                        <li class="<?php if ($currentmenu == 'list_privilege') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/list_privilege"><i></i><span data-i18n="Events">Manage</span></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($unit_add || $unit_view) : ?>
                            <li>
                                <a class="menu-item" href="#"><i class="ft-chevron-right"></i><span data-i18n="Full Calendar">Unit</span></a>
                                <ul class="menu-content">
                                    <?php if ($unit_add) : ?>
                                        <li class="<?php if ($currentmenu == 'add_unit') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/add_unit"><i></i><span data-i18n="Basic">Add Unit</span></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($unit_view) : ?>
                                        <li class="<?php if ($currentmenu == 'list_unit') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/list_unit"><i></i><span data-i18n="Events">Manage Unit</span></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($variations_add || $variations_view) : ?>
                            <li>
                                <a class="menu-item" href="#"><i class="ft-chevron-right"></i><span data-i18n="Full Calendar">Variations</span></a>
                                <ul class="menu-content">
                                    <?php if ($variations_add) : ?>
                                        <li class="<?php if ($currentmenu == 'add_variations') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/add_variations"><i></i><span data-i18n="Basic">Add Variations</span></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($variations_view) : ?>
                                        <li class="<?php if ($currentmenu == 'list_variations') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/list_variations"><i></i><span data-i18n="Events">Manage Variations</span></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($message_add || $message_view) : ?>
                            <li>
                                <a class="menu-item" href="#"><i class="ft-chevron-right"></i><span data-i18n="Full Calendar">Message Template</span></a>
                                <ul class="menu-content">
                                    <?php if ($message_add) : ?>
                                        <li class="<?php if ($currentmenu == 'add_message') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/add_message"><i></i><span data-i18n="Basic">Add Message Template</span></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($message_view) : ?>
                                        <li class="<?php if ($currentmenu == 'list_message') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/list_message"><i></i><span data-i18n="Events">Manage Message Template</span></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($expense_add || $expense_view) : ?>
                            <li>
                                <a class="menu-item" href="#"><i class="ft-chevron-right"></i><span data-i18n="Full Calendar">Expense</span></a>
                                <ul class="menu-content">
                                    <?php if ($expense_add) : ?>
                                        <li class="<?php if ($currentmenu == 'add_expense') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/add_expense"><i></i><span data-i18n="Basic">Add Expense</span></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($expense_view) : ?>
                                        <li class="<?php if ($currentmenu == 'list_expense') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/list_expense"><i></i><span data-i18n="Events">Manage Expense</span></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($outlet_category_add || $outlet_category_view) : ?>
                            <li>
                                <a class="menu-item" href="#"><i class="ft-chevron-right"></i><span data-i18n="Full Calendar">Outlet Category</span></a>
                                <ul class="menu-content">
                                    <?php if ($outlet_category_add) : ?>
                                        <li class="<?php if ($currentmenu == 'add_outlet_category') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/add_outlet_category"><i></i><span data-i18n="Basic">Add Outlet Category</span></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($outlet_category_view) : ?>
                                        <li class="<?php if ($currentmenu == 'list_outlet_category') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/list_outlet_category"><i></i><span data-i18n="Events">Manage Outlet Category</span></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($category_add || $category_view || $product_add || $product_view) : ?>
                <li class=" nav-item">
                    <a href="#"><i class="la la-newspaper-o"></i><span class="menu-title" data-i18n="Calendars">Catalogue</span></a>
                    <ul class="menu-content">
                        <?php if ($category_add || $category_view) : ?>
                            <li>
                                <a class="menu-item" href="#"><i class="ft-chevron-right"></i><span data-i18n="Full Calendar">Category</span></a>
                                <ul class="menu-content">
                                    <?php if ($category_add) : ?>
                                        <li class="<?php if ($currentmenu == 'add_category') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/catlog/add_category"><i></i><span data-i18n="Basic">Add Category</span></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($category_view) : ?>
                                        <li class="<?php if ($currentmenu == 'list_category') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/catlog/list_category"><i></i><span data-i18n="Events">Manage Category</span></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($category_add || $category_view) : ?>
                            <li>
                                <a class="menu-item" href="#"><i class="ft-chevron-right"></i><span data-i18n="Full Calendar">Sub Category</span></a>
                                <ul class="menu-content">
                                    <?php if ($category_add) : ?>
                                        <li class="<?php if ($currentmenu == 'add_sub_category') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/catlog/add_sub_category"><i></i><span data-i18n="Basic">Add Sub Category</span></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($category_view) : ?>
                                        <li class="<?php if ($currentmenu == 'list_sub_category') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/catlog/list_sub_category"><i></i><span data-i18n="Events">Manage Sub Category</span></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <!-- <?php if ($distributors_add || $distributors_view) : ?>
                                <li class="<?php if ($currentmenu == 'list_dis_approval') echo 'active'; ?>">
                                    <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributors/list_dis_approval"><i class="ft-check-circle"></i><span class="menu-title">Distributors Approval</span></a>
                                </li>
                            <?php endif; ?> -->
                        <?php if ($product_add || $product_view) : ?>
                            <li>
                                <a class="menu-item" href="#"><i class="ft-chevron-right"></i><span data-i18n="Full Calendar">Product</span></a>
                                <ul class="menu-content">
                                    <?php if ($product_add) : ?>
                                        <li class="<?php if ($currentmenu == 'add_product') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/catlog/add_product"><i></i><span data-i18n="Basic">Add Product</span></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($product_view) : ?>
                                        <li class="<?php if ($currentmenu == 'list_product') echo 'active'; ?>"><a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/catlog/list_product"><i></i><span data-i18n="Events">Manage Product</span></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($manufacture_add || $manufacture_view) : ?>
                <li class=" nav-item">
                    <a href=""><i class="la la-cart-arrow-down"></i><span class="menu-title" data-i18n="Dashboard">Manufacture</span></a>
                    <ul class="menu-content">
                        <?php if ($manufacture_add) : ?>
                            <li class="<?php if ($currentmenu == 'add_vendor') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/vendors/add_vendor"><i></i><span>Add Manufacture</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($manufacture_view) : ?>
                            <li class="<?php if ($currentmenu == 'list_vendor') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/vendors/list_vendor"><i></i><span>Manage Manufacture</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($user_role_add || $user_role_view) : ?>
                <li class=" nav-item">
                    <a href=""><i class="icon-user-following"></i><span class="menu-title" data-i18n="Dashboard">User Role</span></a>
                    <ul class="menu-content">
                        <?php if ($user_role_add) : ?>
                            <li class="<?php if ($currentmenu == 'add_role') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/user/add_role"><i></i><span>Add User Role</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($user_role_view) : ?>
                            <li class="<?php if ($currentmenu == 'list_role') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/user/list_role"><i></i><span>Manage User Role</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($user_add || $user_view) : ?>
                <li class=" nav-item">
                    <a href=""><i class="icon-user"></i><span class="menu-title" data-i18n="Dashboard">User</span></a>
                    <ul class="menu-content">
                        <?php if ($user_add) : ?>
                            <li class="<?php if ($currentmenu == 'add_user') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/user/add_user"><i></i><span>Add User</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($user_view) : ?>
                            <li class="<?php if ($currentmenu == 'list_user') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/user/list_user"><i></i><span>Manage User</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <!-- <?php if ($outlets_add || $outlets_view) : ?>
                    <li class=" nav-item">
                        <a href=""><i class="icon icon-handbag"></i><span class="menu-title" data-i18n="Dashboard">Outlets</span></a>
                        <ul class="menu-content">
                            <?php if ($outlets_add) : ?>
                            <li class="<?php if ($currentmenu == 'add_outlets') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/outlets/add_outlets"><i></i><span>Add Outlets</span></a>
                            </li>
                            <?php endif; ?>
                            <?php if ($outlets_view) : ?>
                            <li class="<?php if ($currentmenu == 'list_outlets') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/outlets/list_outlets"><i></i><span>Manage Outlets</span></a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?> -->

            <?php if ($product_loyalty_add || $product_loyalty_view) : ?>
                <li class=" nav-item">
                    <a href=""><i class="icon-trophy"></i><span class="menu-title" data-i18n="Dashboard">Product Loyalty</span></a>
                    <ul class="menu-content">
                        <?php if ($product_loyalty_add) : ?>
                            <li class="<?php if ($currentmenu == 'add_product_loyalty') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/loyalty/add_product_loyalty"><i></i><span>Add Product Loyalty</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($product_loyalty_view) : ?>
                            <li class="<?php if ($currentmenu == 'list_product_loyalty') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/loyalty/list_product_loyalty"><i></i><span>Manage Product Loyalty</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($employee_add || $employee_view) : ?>
                <li class=" nav-item">
                    <a href=""><i class="fa ft-users"></i><span class="menu-title" data-i18n="Dashboard">Employee</span></a>
                    <ul class="menu-content">
                        <?php if ($employee_add) : ?>
                            <li class="<?php if ($currentmenu == 'add_employee') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/employee/add_employee"><i></i><span>Add Employee</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($employee_view) : ?>
                            <li class="<?php if ($currentmenu == 'list_employee') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/employee/list_employee"><i></i><span>Manage Employee</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($assign_product_add || $assign_product_view) : ?>
                            <li class="<?php if ($currentmenu == 'employee_list') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/Posting/employee_list"><i></i><span>Employee Mapping</span></a>
                            </li>
                        <?php endif; ?>

                        <?php if ($assign_product_add || $assign_product_view) : ?>
                            <li class="<?php if ($currentmenu == 'hierarchy_list') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/Managers/hierarchy_list"><i></i><span>Hierarchy</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>


            <?php if ($assign_beat_add || $assign_beat_view) : ?>
                <li class=" nav-item">
                    <a href=""><i class="icon-grid"></i><span class="menu-title" data-i18n="Dashboard">Assign Beat</span></a>
                    <ul class="menu-content">
                        <?php if ($assign_beat_add) : ?>
                            <li class="<?php if ($currentmenu == 'add_assign_shop') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/assignshop/add_assign_shop"><i></i><span>Add Assign Beat</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($assign_beat_view) : ?>
                            <li class="<?php if ($currentmenu == 'list_assign_shop') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/assignshop/list_assign_shop"><i></i><span>Manage Assign Beat</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($employee_target_add || $employee_target_view || $product_target_add || $product_target_view || $beat_target_add || $beat_target_view) : ?>
                <li class=" nav-item">
                    <a href=""><i class="ft-pie-chart"></i><span class="menu-title" data-i18n="Dashboard">Target</span></a>
                    <ul class="menu-content">
                        <?php if ($employee_target_add) : ?>
                            <li class="<?php if ($currentmenu == 'add_target') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/target/add_target"><i></i><span>Add Employee Target</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($employee_target_view) : ?>
                            <li class="<?php if ($currentmenu == 'list_target') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/target/list_target"><i></i><span>Manage Employee Target</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($product_target_add) : ?>
                            <li class="<?php if ($currentmenu == 'add_product_target') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/target/add_product_target"><i></i><span>Add Product Target</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($product_target_add) : ?>
                            <li class="<?php if ($currentmenu == 'assign_product') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/target/assign_product"><i></i><span>Create Target Template</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($product_target_add) : ?>
                            <li class="<?php if ($currentmenu == 'assign_employee') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/target/assign_employee"><i></i><span>Assign Employee Target</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($product_target_view) : ?>
                            <li class="<?php if ($currentmenu == 'list_product_target') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/target/list_product_target"><i></i><span>Manage Product Target</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($product_target_add) : ?>
                            <li class="<?php if ($currentmenu == 'average_sales') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/target/average_sales"><i></i><span>Average Sales Target</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($beat_target_add) : ?>
                            <li class="<?php if ($currentmenu == 'add_beat_target') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/target/add_beat_target"><i></i><span>Add Beat Target</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($beat_target_view) : ?>
                            <li class="<?php if ($currentmenu == 'list_beat_target') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/target/list_beat_target"><i></i><span>Manage Beat Target</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($collaterals_add || $collaterals_view) : ?>
                <li class=" nav-item">
                    <a href=""><i class="ft-paperclip"></i><span class="menu-title" data-i18n="Dashboard">Collaterals</span></a>
                    <ul class="menu-content">
                        <?php if ($collaterals_add) : ?>
                            <li class="<?php if ($currentmenu == 'add_collaterals') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/collaterals/add_collaterals"><i></i><span>Add Collaterals</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($collaterals_view) : ?>
                            <li class="<?php if ($currentmenu == 'list_collaterals') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/collaterals/list_collaterals"><i></i><span>Manage Collaterals</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if ($distributors_add || $distributors_view) : ?>
                <li class="<?php if ($currentmenu == 'list_dis_approval') echo 'active'; ?>">
                    <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributors/list_dis_approval"><i class="ft-check-circle"></i><span class="menu-title">Distributors Approval</span></a>
                </li>
            <?php endif; ?>
            <?php if ($distributors_add || $distributors_view) : ?>
                <li class=" nav-item">
                    <a href=""><i class="icon icon-users"></i><span class="menu-title" data-i18n="Dashboard">Distributors</span></a>
                    <ul class="menu-content">
                        <?php if ($distributors_add) : ?>
                            <li class="<?php if ($currentmenu == 'add_distributors') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributors/add_distributors"><i></i><span>Add Distributors</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($distributors_view) : ?>
                            <li class="<?php if ($currentmenu == 'list_distributors') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributors/list_distributors"><i></i><span>Manage Distributors</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <!-- <?php if ($assign_product_add || $assign_product_view) : ?>
                    <li class="<?php if ($currentmenu == 'distributor_list') echo 'active'; ?>">
                        <a href="<?php echo BASE_URL; ?>index.php/admin/assignproduct/distributor_list"><i class="ft-check-square"></i><span class="menu-title">Assign Product</span></a>
                    </li>
                    <?php endif; ?> -->

            <?php if ($distributor_price_master_view || $outlet_price_master_view) : ?>
                <li class=" nav-item">
                    <a href=""><i class="icon-tag"></i><span class="menu-title" data-i18n="Dashboard">Price Master</span></a>
                    <ul class="menu-content">
                        <?php if ($distributor_price_master_view) : ?>
                            <li class="<?php if ($currentmenu == 'distributor_price_master') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/pricemaster/distributor_price_master"><i></i><span>Distributors Price Master</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($outlet_price_master_view) : ?>
                            <li class="<?php if ($currentmenu == 'outlet_price_master') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/pricemaster/outlet_price_master"><i></i><span>Outlet Price Master</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($purchase_add || $purchase_view) : ?>
                <li class="nav-item">
                    <a href=""><i class="ft-shopping-cart"></i><span class="menu-title" data-i18n="Dashboard">Purchase Order</span></a>
                    <ul class="menu-content">
                        <?php if ($purchase_add) : ?>
                            <li class="<?php if ($currentmenu == 'add_purchase') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/purchase/add_purchase"><i></i><span>Add Purchase</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($purchase_view) : ?>
                            <li class="<?php if ($currentmenu == 'list_purchase') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/purchase/list_purchase"><i></i><span>Manage Purchase</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($purchase_return_add || $purchase_return_view) : ?>
                <li class="nav-item">
                    <a href=""><i class="icon-loop"></i><span class="menu-title" data-i18n="Dashboard">Purchase Return</span></a>
                    <ul class="menu-content">
                        <?php if ($purchase_return_add) : ?>
                            <li class="<?php if ($currentmenu == 'add_purchase_return') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/purchase/add_purchase_return"><i></i><span>Add Purchase Return</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($purchase_return_view) : ?>
                            <li class="<?php if ($currentmenu == 'list_purchase_return') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/purchase/list_purchase_return"><i></i><span>Manage Purchase Return</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($distributors_order_view) : ?>
                <li class=" nav-item">
                    <a href=""><i class="icon-drawer"></i><span class="menu-title" data-i18n="Dashboard">Distributors Order</span> <span class="badge badge badge-pill badge-danger float-right mr-2 admDis_ord hide">New</span></a>
                    <ul class="menu-content">
                        <ul class="menu-content">
                            <li class="<?php if ($currentmenu == 'dis_overall_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorsorder/dis_overall_order"><i></i><span>All</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dis_success_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorsorder/dis_success_order"><i></i><span>Success</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dis_process_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorsorder/dis_process_order"><i></i><span>Approved</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dis_packing_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorsorder/dis_packing_order"><i></i><span>Packing</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dis_invoice_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorsorder/dis_invoice_order"><i></i><span>Invoice</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dis_shipping_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorsorder/dis_shipping_order"><i></i><span>Shipping</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dis_delivery_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorsorder/dis_delivery_order"><i></i><span>Delivered</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dis_complete_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorsorder/dis_complete_order"><i></i><span>Complete</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dis_cancle_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorsorder/dis_cancle_order"><i></i><span>Cancel</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dis_cancle_invoice') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorsorder/dis_cancle_invoice"><i></i><span>Cancel Invoice</span></a>
                            </li>
                        </ul>
                    </ul>
                </li>
            <?php endif; ?>

            <!-- <li class=" nav-item">
                        <a href=""><i class="ft-repeat"></i><span class="menu-title" data-i18n="Dashboard">Delivery Challan</span></a>
                        <ul class="menu-content">
                            <ul class="menu-content">
                            <li class="<?php if ($currentmenu == 'dc_overall_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/deliverychallan/dis_overall_order"><i></i><span>All</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dc_success_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/deliverychallan/dis_success_order"><i></i><span>Success</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dc_process_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/deliverychallan/dis_process_order"><i></i><span>Approved</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dc_packing_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/deliverychallan/dis_packing_order"><i></i><span>Packing</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dc_shipping_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/deliverychallan/dis_shipping_order"><i></i><span>Shipping</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dc_delivery_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/deliverychallan/dis_delivery_order"><i></i><span>Delivered</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dc_complete_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/deliverychallan/dis_complete_order"><i></i><span>Complete</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dc_cancle_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/deliverychallan/dis_cancle_order"><i></i><span>Cancel</span></a>
                            </li>
                        </ul>
                        </ul>
                    </li>  -->

            <?php if ($delivery_challan_view) : ?>
                <li class=" nav-item">
                    <a href=""><i class="icon-drawer"></i><span class="menu-title" data-i18n="Dashboard">Delivery Challan</span>
                        <!-- <span class="badge badge badge-pill badge-danger float-right mr-2 admDis_ord hide">New</span> -->
                    </a>
                    <ul class="menu-content">
                        <ul class="menu-content">

                            <li class="<?php if ($currentmenu == 'dis_overall_order_dc') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorordertwo/dis_overall_order_dc"><i></i><span>All</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dis_success_order_dc') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorordertwo/dis_success_order_dc"><i></i><span>Success</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dis_process_order_dc') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorordertwo/dis_process_order_dc"><i></i><span>Approved</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dis_packing_order_dc') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorordertwo/dis_packing_order_dc"><i></i><span>Packing</span></a>
                            </li>

                            <li class="<?php if ($currentmenu == 'dis_shipping_order_dc') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorordertwo/dis_shipping_order_dc"><i></i><span>Shipping</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dis_delivery_order_dc') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorordertwo/dis_delivery_order_dc"><i></i><span>Delivered</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dis_complete_order_dc') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorordertwo/dis_complete_order_dc"><i></i><span>Complete</span></a>
                            </li>
                            <li class="<?php if ($currentmenu == 'dis_cancle_order_dc') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/distributorordertwo/dis_cancle_order_dc"><i></i><span>Cancel</span></a>
                            </li>

                        </ul>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($sales_return_add || $sales_return_view) : ?>
                <li class="nav-item">
                    <a href=""><i class="icon-loop"></i><span class="menu-title" data-i18n="Dashboard">Sales Return</span></a>
                    <ul class="menu-content">
                        <?php if ($sales_return_add) : ?>
                            <li class="<?php if ($currentmenu == 'add_sales_return') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/salesreturn/add_sales_return"><i></i><span>Add Sales Return</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($sales_return_view) : ?>
                            <li class="<?php if ($currentmenu == 'list_sales_return') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/salesreturn/list_sales_return"><i></i><span>Manage Sales Return</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($stock_entry_add || $stock_entry_view) : ?>
                <li class="nav-item">
                    <a href=""><i class="icon-cloud-download"></i><span class="menu-title" data-i18n="Dashboard">Stock Entry</span></a>
                    <ul class="menu-content">
                        <?php if ($stock_entry_add) : ?>
                            <li class="<?php if ($currentmenu == 'stock_entry') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/purchase/stock_entry"><i></i><span>Add Stock Entry</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($stock_entry_view) : ?>
                            <li class="<?php if ($currentmenu == 'manage_stock') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/purchase/manage_stock"><i></i><span>Manage Stock Entry</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($sales_order_add) : ?>
                <li class="<?php if ($currentmenu == 'create_order') echo 'active'; ?>">
                    <a href="<?php echo BASE_URL; ?>index.php/admin/order/create_order"><i class="icon-basket-loaded"></i><span class="menu-title">Sales Order</span></a>
                </li>
            <?php endif; ?>

            <?php if ($outlet_orders_view) : ?>
                <li class=" nav-item">
                    <a href=""><i class="icon-notebook"></i><span class="menu-title" data-i18n="Dashboard">Outlet Orders</span> <span class="badge badge badge-pill badge-danger float-right mr-2 admStr_ord hide">New</span></a>
                    <ul class="menu-content">
                        <li class="<?php if ($currentmenu == 'overall_order') echo 'active'; ?>">
                            <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/order/overall_order"><i></i><span>All</span></a>
                        </li>
                        <li class="<?php if ($currentmenu == 'success_order') echo 'active'; ?>">
                            <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/order/success_order"><i></i><span>Success</span></a>
                        </li>
                        <li class="<?php if ($currentmenu == 'process_order') echo 'active'; ?>">
                            <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/order/process_order"><i></i><span>Approved</span></a>
                        </li>
                        <li class="<?php if ($currentmenu == 'complete_order') echo 'active'; ?>">
                            <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/order/complete_order"><i></i><span>Complete</span></a>
                        </li>
                        <li class="<?php if ($currentmenu == 'cancle_order') echo 'active'; ?>">
                            <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/order/cancle_order"><i></i><span>Cancel</span></a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($manufacture_payment_add || $manufacture_payment_view || $distributors_receipt_add || $distributors_receipt_view) : ?>
                <li class="nav-item">
                    <a href=""><i class="icon-wallet"></i><span class="menu-title" data-i18n="Dashboard">Payment</span></a>
                    <ul class="menu-content">
                        <?php if ($manufacture_payment_add || $manufacture_payment_view) : ?>
                            <li class="<?php if ($currentmenu == 'vendor_payment') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/payment/list_vendor"><i></i><span>Manufacture Payment</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($distributors_receipt_add || $distributors_receipt_view) : ?>
                            <li class="<?php if ($currentmenu == 'distributor_payment') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/payment/list_distributor"><i></i><span>Distributor Receipt</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($expense_entry_add || $expense_entry_view) : ?>
                <li class=" nav-item">
                    <a href=""><i class="la la-newspaper-o"></i><span class="menu-title" data-i18n="Dashboard">Expense Entry</span></a>
                    <ul class="menu-content">
                        <?php if ($expense_entry_add) : ?>
                            <li class="<?php if ($currentmenu == 'expense_entry') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/expense_entry"><i></i><span>Add Expense Entry</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($expense_entry_view) : ?>
                            <li class="<?php if ($currentmenu == 'expense_list') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/masters/expense_list"><i></i><span>Manage Expense Entry</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($attendance_report_view || $outlet_order_report_view || $purchas_report_view || $vendor_purchas_report_view || $purchas_return_report_view || $vendor_overall_report_view || $beat_wise_outlet_report_view || $outlet_history_view || $distributor_order_report_view || $outlet_overall_report_view || $outlet_invoice_report_view || $overall_order_report_view || $target_achievement_view || $employee_order_value_view || $product_stock_view || $distributor_product_stock_view || $distributor_sales_report_view || $sales_return_report_view || $expense_report_view || $stock_entry_report_view || $employee_target_report_view || $employee_daily_report_view || $product_order_report_view || $backlog_report_view) : ?>
                <li class="nav-item">
                    <a href=""><i class="ft-bar-chart-2"></i><span class="menu-title" data-i18n="Dashboard">Report</span></a>
                    <ul class="menu-content">
                        <?php if ($attendance_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'attendance_details_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/attendance_details_report"><i></i><span>Attendace Details Report</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($attendance_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'attendance_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/attendance_report"><i></i><span>Attendace Report</span></a>
                            </li>
                        <?php endif; ?>

                        <?php if ($outlet_order_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'outlet_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/outlet_order"><i></i><span>Outlet Order Report</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($outlet_order_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'outlet_stock') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/outlet_stock"><i></i><span>Outlet stock Report</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($purchas_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'purchase_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/purchase_report"><i></i><span>Purchase Report</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($vendor_purchas_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'vendor_purchase') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/vendor_purchase"><i></i><span>Vendor Purchase Report</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($purchas_return_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'purchase_return') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/purchase_return"><i></i><span>Purchase Return Report</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($vendor_overall_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'vendor_overall') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/vendor_overall"><i></i><span>Vendor Overall Report</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($beat_wise_outlet_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'beat_outlet') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/beat_outlet"><i></i><span>Beat Wise Outlet</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($outlet_history_view) : ?>
                            <li class="<?php if ($currentmenu == 'outlet_history') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/outlet_history"><i></i><span>Outlet History</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($distributor_order_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'distributor_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/distributor_order"><i></i><span>Distributor Order Report</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($outlet_overall_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'outlet_overall') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/outlet_overall"><i></i><span>Outlet Overall Report</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($outlet_invoice_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'outlet_invoice') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/outlet_invoice"><i></i><span>Outlet Invoice Report</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($overall_order_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'order_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/order_report"><i></i><span>Overall Order Report</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($target_achievement_view) : ?>
                            <li class="<?php if ($currentmenu == 'target_achievement') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/target_achievement"><i></i><span>Target Achievement</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($employee_order_value_view) : ?>
                            <li class="<?php if ($currentmenu == 'employee_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/employee_order"><i></i><span>Employee Order Value</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($product_stock_view) : ?>
                            <li class="<?php if ($currentmenu == 'product_stock') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/product_stock"><i></i><span>Product Stock</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($distributor_product_stock_view) : ?>
                            <li class="<?php if ($currentmenu == 'distributor_stock') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/distributor_stock"><i></i><span>Distributor Product Stock</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($distributor_sales_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'sales_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/sales_report"><i></i><span>Distributor Sales Report</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($sales_return_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'sales_return') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/sales_return"><i></i><span>Sales Return Report</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($expense_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'expense_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/expense_report"><i></i><span>Expense Report</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($stock_entry_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'stock_entry_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/stock_entry_report"><i></i><span>Stock Entry Report</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($employee_target_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'employee_target') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/employee_target"><i></i><span>Employee Target</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($employee_daily_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'employee_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/employee_report"><i></i><span>Employee Daily Report</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($product_order_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'product_order') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/product_order"><i></i><span>Product Order Report</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($backlog_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'backlog_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/report/backlog_report"><i></i><span>Backlog Report Report</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($month_wise_order_view || $employee_wise_order_view || $collection_report_view) : ?>
                <li class="nav-item">
                    <a href=""><i class="la la-cloud-upload"></i><span class="menu-title" data-i18n="Dashboard">MIS Report</span></a>
                    <ul class="menu-content">
                        <?php if ($month_wise_order_view) : ?>
                            <!-- <li class="<?php if ($currentmenu == 'month_wise_order_view') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/misreport/month_wise_order_view"><i></i><span>Month wise order</span></a>
                            </li> -->
                        <?php endif; ?>
                        <?php if ($employee_wise_order_view) : ?>
                            <li class="<?php if ($currentmenu == 'employee_wise_order_view') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/misreport/employee_wise_order_view"><i></i><span>Employee wise order</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($collection_report_view) : ?>
                            <li class="<?php if ($currentmenu == 'collection_report_view') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/misreport/collection_report_view"><i></i><span>Month wise collection</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($cashbook_view || $bank_entry_view || $manufacture_ledger_view || $distributor_ledger_view) : ?>
                <li class=" nav-item">
                    <a href=""><i class="icon-notebook"></i><span class="menu-title" data-i18n="Dashboard">Accounts</span></a>
                    <ul class="menu-content">
                        <?php if ($cashbook_view) : ?>
                            <li class="<?php if ($currentmenu == 'cashbook_report') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/accounts/cashbook"><i></i><span>Cashbook</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bank_entry_view) : ?>
                            <li class="<?php if ($currentmenu == 'bank_entry') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/accounts/bank_entry"><i></i><span>Bank Entry</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($manufacture_ledger_view) : ?>
                            <li class="<?php if ($currentmenu == 'manufacture_ledger') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/accounts/manufacture_ledger"><i></i><span>Manufacture Ledger</span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($distributor_ledger_view) : ?>
                            <li class="<?php if ($currentmenu == 'distributor_ledger') echo 'active'; ?>">
                                <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/accounts/distributor_ledger"><i></i><span>Distributor Ledger</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <li class="nav-item">
                <a href=""><i class="icon-settings"></i><span class="menu-title" data-i18n="Dashboard">Settings</span></a>
                <ul class="menu-content">
                    <li class="<?php if ($currentmenu == 'profile_settings') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/settings/profile_settings"><i></i><span>Profile Setting</span></a>
                    </li>
                    <li class="<?php if ($currentmenu == 'company_settings') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/settings/company_settings"><i></i><span>Company Setting</span></a>
                    </li>
                    <li class="<?php if ($currentmenu == 'change_password') echo 'active'; ?>">
                        <a class="menu-item" href="<?php echo BASE_URL; ?>index.php/admin/settings/change_password"><i></i><span>Change Password</span></a>
                    </li>
                </ul>
            </li>
            <li class="">
                <a href="<?php echo BASE_URL; ?>index.php/welcome/logout"><i class="ft-power"></i><span class="menu-title">Logout</span></a>
            </li>
        </ul>
        </li>
        </ul>
    </div>
</div>
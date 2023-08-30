<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['managers/api/hierarchy_list']   = 'Api/managers/hierarchy_list';
$route['managers/api/managers']         = 'Api/managers/managers';

// Login
$route['login/api/admin_login']         = 'Api/Login/admin_login';
$route['login/api/employee_login']      = 'Api/Login/employee_login';

$route['attendance/api/emp_checkpoint'] = 'Api/Attendance/emp_checkpoint';

// Masters
$route['master/api/financial']       = 'Api/Master/financial';
$route['master/api/state']           = 'Api/Master/state';
$route['master/api/city']            = 'Api/Master/city';
$route['master/api/zone']            = 'Api/Master/zone';
$route['master/api/unit']            = 'Api/Master/unit';
$route['master/api/variation']       = 'Api/Master/variation';
$route['master/api/privilege']       = 'Api/Master/privilege';
$route['master/api/month']           = 'Api/Master/month';
$route['master/api/year']            = 'Api/Master/year';
$route['master/api/message']         = 'Api/Master/message';
$route['master/api/expense']         = 'Api/Master/expense';
$route['master/api/outlet_category'] = 'Api/Master/outlet_category';

// Catlog
$route['catlog/api/category']    = 'Api/Catlog/category';
$route['catlog/api/product']     = 'Api/Catlog/product';
$route['catlog/api/sub_category']    = 'Api/Catlog/sub_category';
$route['catlog/api/productType'] = 'Api/Catlog/productType';

// Vendors
$route['vendors/api/vendors'] = 'Api/Vendors/vendors';

// Outlets
$route['outlets/api/outlets']                 = 'Api/Outlets/outlets';
$route['outlets/api/outletPayment']           = 'Api/Outlets/outletPayment';
$route['outlets/api/distributor_outlet_list'] = 'Api/Outlets/distributor_outlet_list';

// Distributors
$route['distributors/api/distributors']            = 'Api/Distributors/distributors';
$route['distributors/api/distributor_outlet_list'] = 'Api/Distributors/distributor_outlet_list';
$route['distributors/api/profile_settings']        = 'Api/Distributors/profile_settings';

// Sathish file
$route['distributorPurchase/api/add_distributor_purchase'] = 'Api/DistributorPurchase/add_distributor_purchase';
$route['distributors/api/manage_purchase_details']         = 'Api/distributors/manage_purchase_details';
$route['distributors/api/manage_purchase']                 = 'Api/Distributors/manage_purchase';
$route['distributors/api/manage_order']                    = 'Api/distributors/manage_order';

// Sathish file
$route['distributorPurchase/api/add_distributor_purchase'] = 'Api/DistributorPurchase/add_distributor_purchase';
$route['distributors/api/manage_purchase_details']         = 'Api/distributors/manage_purchase_details';
$route['distributors/api/manage_purchase']                 = 'Api/Distributors/manage_purchase';
$route['distributors/api/manage_order']                    = 'Api/distributors/manage_order';

// Payment 
$route['payment/api/outlet_payment']      = 'Api/Payment/outlet_payment';
$route['payment/api/distributor_payment'] = 'Api/Payment/distributor_payment';
$route['payment/api/vendor_payment']      = 'Api/Payment/vendor_payment';

// User
$route['user/api/user']             = 'Api/User/user';
$route['user/api/profile_update']   = 'Api/User/profile_update';
$route['user/api/profile_settings'] = 'Api/User/profile_settings';

// Employee
$route['employee/api/employee'] = 'Api/Employee/employee';
$route['employee/api/employee_designation'] = 'Api/Employee/employee_designation';

// Assign Shop
$route['assignshop/api/add_assign_shop']    = 'Api/AssignShop/add_assign_shop';
$route['assignshop/api/manage_assign_shop'] = 'Api/AssignShop/manage_assign_shop';
$route['assignshop/api/employee_wise_shop'] = 'Api/AssignShop/employee_wise_shop';

// Target
$route['target/api/add_target']     = 'Api/Target/add_target';
$route['target/api/manage_target']  = 'Api/Target/manage_target';
$route['target/api/product_target'] = 'Api/Target/product_target';
$route['target/api/assign_product_target'] = 'Api/Target/assign_product_target';
$route['target/api/assign_Employee_target'] = 'Api/Target/assign_Employee_target';
$route['target/api/average_sales'] = 'Api/Target/average_sales';
$route['target/api/beat_target']    = 'Api/Target/beat_target';

// Assign Product
$route['assignproduct/api/add_assign_product']       = 'Api/AssignProduct/add_assign_product';
$route['assignproduct/api/list_assign_product']      = 'Api/AssignProduct/list_assign_product';
$route['assignproduct/api/list_distributor_product'] = 'Api/AssignProduct/list_distributor_product';
$route['assignproduct/api/assign_multiple_beat']     = 'Api/AssignProduct/assign_multiple_beat';

// Attendance
$route['attendance/api/add_attendance']    = 'Api/Attendance/add_attendance';
$route['attendance/api/manage_attendance'] = 'Api/Attendance/manage_attendance';
$route['attendance/api/attendance_type']   = 'Api/Attendance/attendance_type';
$route['attendance/api/outlet_history']    = 'Api/Attendance/outlet_history';
$route['attendance/api/upload_images']     = 'Api/Attendance/upload_images';
$route['attendance/api/stock_list']        = 'Api/Attendance/stock_list';

// Purchase
$route['purchase/api/add_purchase']                  = 'Api/Purchase/add_purchase';
$route['purchase/api/manage_purchase']               = 'Api/Purchase/manage_purchase';
$route['purchase/api/manage_purchase_details']       = 'Api/Purchase/manage_purchaseDetails';
$route['purchase/api/manage_purchase_stock_details'] = 'Api/Purchase/manage_purchaseStkDetails';
$route['purchase/api/order_process']                 = 'Api/Purchase/order_process';
$route['purchase/api/add_purchase_return']           = 'Api/Purchase/add_purchase_return';
$route['purchase/api/manage_purchase_return']        = 'Api/Purchase/manage_purchase_return';
$route['purchase/api/add_inventory']                 = 'Api/Purchase/add_inventory';

// Order
$route['order/api/create_order']         = 'Api/Order/create_order';
$route['order/api/manage_order']         = 'Api/Order/manage_order';
$route['order/api/manage_items']         = 'Api/Order/manage_items';
$route['order/api/order_process']        = 'Api/Order/order_process';
$route['order/api/manage_order_stock']   = 'Api/Order/manage_orderStkDetails';
$route['order/api/manage_order_details'] = 'Api/Order/manage_orderDetails';
$route['order/api/invoice_manage_order'] = 'Api/Order/invoice_manage_order';
	
// Sub admin Order Details
$route['order/api/vendor_manage_order']      = 'Api/Order/vendor_manage_order';
$route['order/api/distributor_manage_order'] = 'Api/Order/distributor_manage_order';

// Assign Invoice
$route['assigninvoice/api/add_assign_invoice']    = 'Api/AssignInvoice/add_assign_invoice';
$route['assigninvoice/api/manage_assign_invoice'] = 'Api/AssignInvoice/manage_assign_invoice';
$route['assigninvoice/api/employee_wise_shop']    = 'Api/AssignInvoice/employee_wise_shop';

// Production
$route['production/api/add_production']          = 'Api/Production/add_production';
$route['production/api/list_production']         = 'Api/Production/list_production';
$route['production/api/manage_production_stock'] = 'Api/Production/manage_productionStkDetails';

// Price Master
$route['pricemaster/api/distributor_price_master'] = 'Api/PriceMaster/distributor_price_master';
$route['pricemaster/api/outlet_price_master']      = 'Api/PriceMaster/outlet_price_master';

// Distributors Purchase Order
$route['distributorpurchase/api/add_purchase']           = 'Api/DistributorPurchase/add_purchase';
$route['distributorpurchase/api/manage_purchase']        = 'Api/DistributorPurchase/manage_purchase';
$route['distributorpurchase/api/order_process']          = 'Api/DistributorPurchase/order_process';
$route['distributorpurchase/api/manage_order_stock']     = 'Api/DistributorPurchase/manage_orderStkDetails';
$route['distributorpurchase/api/distributor_invoice']    = 'Api/DistributorPurchase/distributor_invoice';
$route['distributorpurchase/api/add_purchase_return']    = 'Api/DistributorPurchase/add_purchase_return';
$route['distributorpurchase/api/manage_purchase_return'] = 'Api/DistributorPurchase/manage_purchase_return';

// Sathish file
$route['distributorpurchase/api/add_distributor_dc']     = 'Api/DistributorPurchase/add_distributor_dc';
$route['distributorpurchase/api/add_distributor_order']  = 'Api/DistributorPurchase/add_distributor_order';
$route['distributorpurchase/api/manage_purchase_order']  = 'Api/DistributorPurchase/manage_purchase_order';
$route['distributorpurchase/api/orderr_process']         = 'Api/DistributorPurchase/orderr_process';
$route['distributorpurchase/api/distributor_dc']         =  'Api/DistributorPurchase/distributor_dc';

// Workorder
$route['report/api/attendace_details_report'] = 'Api/Report/attendace_details_report';
$route['workorder/api/add_workorder']    = 'Api/Workorder/add_workorder';
$route['workorder/api/manage_workorder'] = 'Api/Workorder/manage_workorder';

// Report
$route['report/api/attendace_report']   = 'Api/Report/attendace_report';
$route['report/api/vendor_report']      = 'Api/Report/vendor_report';
$route['report/api/outlet_report']      = 'Api/Report/outlet_report';
$route['report/api/payment_report']     = 'Api/Report/payment_report';
$route['report/api/target_report']      = 'Api/Report/target_report';
$route['report/api/dashboard_report']   = 'Api/Report/dashboard_report';
$route['report/api/dashboard_report_mg']   = 'Api/Report/dashboard_report_mg';
$route['report/api/order_report']       = 'Api/Report/order_report';
$route['report/api/stock_report']       = 'Api/Report/stock_report';
$route['report/api/distributor_report'] = 'Api/Report/distributor_report';
$route['report/api/tally_report']       = 'Api/Report/tally_report';
$route['report/api/sales_report']       = 'Api/Report/sales_report';
$route['report/api/expense_report']     = 'Api/Report/expense_report';
$route['report/api/stock_entry_report'] = 'Api/Report/stock_entry_report';
$route['report/api/inventory_report']   = 'Api/Report/inventory_report';
$route['report/api/sub_dis_sales_report'] = 'Api/Report/sub_dis_sales_report';

// Dashboard
$route['dashboard/api/admin_dashboard']       = 'Api/Dashboard/admin_dashboard';
$route['dashboard/api/admin_chart']           = 'Api/Dashboard/admin_chart';
$route['dashboard/api/vendor_dashboard']      = 'Api/Dashboard/vendor_dashboard';
$route['dashboard/api/distributor_dashboard'] = 'Api/Dashboard/distributor_dashboard';
$route['dashboard/api/order_count']           = 'Api/Dashboard/order_count';
$route['dashboard/api/manager_dashboard']     ='Api/Dashboard/manager_dashboard';

$route['salesreturn/api/outlet_return']      = 'Api/Salesreturn/outlet_return';
$route['salesreturn/api/distributor_return'] = 'Api/Salesreturn/distributor_return';

$route['stock/api/add_distributor_stock']    = 'Api/Stock/add_distributor_stock';
$route['stock/api/manage_distributor_stock'] = 'Api/Stock/manage_distributor_stock';
$route['stock/api/add_stock']                = 'Api/Stock/add_stock';
$route['stock/api/manage_stock']             = 'Api/Stock/manage_stock';
$route['stock/api/add_vendor_stock']         = 'Api/Stock/add_vendor_stock';
$route['stock/api/manage_vendor_stock']      = 'Api/Stock/manage_vendor_stock';

$route['accounts/api/accounts_data'] = 'Api/Accounts/accounts_data';

// New API
$route['admin/api/login']             = 'Api/admin/Login/admin_login';
$route['admin/api/dashboard_count']   = 'Api/admin/Dashboard/count_list';
$route['admin/api/attendace_list']    = 'Api/admin/Attendance/list';
$route['admin/api/purchase_list']     = 'Api/admin/Purchase/list';
$route['admin/api/order_list']        = 'Api/admin/Order/list';
$route['admin/api/product_target']    = 'Api/admin/Target/product_target';
$route['admin/api/beat_target']       = 'Api/admin/Target/beat_target';
$route['admin/api/beat_target']       = 'Api/admin/Target/beat_target';
$route['admin/api/order_product']     = 'Api/admin/Product/order_product';
$route['admin/api/invoice_product']   = 'Api/admin/Product/invoice_product';
$route['admin/api/target_chart']      = 'Api/admin/Chart/target_chart';
$route['admin/api/performance_chart'] = 'Api/admin/Chart/performance_chart';

// Admin
$route['user/api/user_role']                 = 'Api/User/user_role';
$route['backlog/api/outlet_backlog']         = 'Api/Backlog/outlet_backlog';
$route['collaterals/api/add_collaterals']    = 'Api/Collaterals/add_collaterals';
$route['collaterals/api/manage_collaterals'] = 'Api/Collaterals/manage_collaterals';
$route['outlets/api/outlet_loyalty']         = 'Api/Outlets/outlet_loyalty';
$route['loyalty/api/product_loyalty']        = 'Api/Loyalty/product_loyalty';

// Mobile
$route['collaterals/api/collaterals_list']      = 'Api/Collaterals/collaterals_list';
$route['executive-collection/api/invoice_list'] = 'Api/ExecutiveCollection/invoice_list';
$route['outlet-order/api/outlet_otp']           = 'Api/Order/outlet_otp';
$route['loyalty/api/outlet-loyalty']            = 'Api/Loyalty/store_loyalty';
$route['loyalty/api/product-loyalty']           = 'Api/Loyalty/store_loyalty';

// Distributors Purchase Order
$route['distributordelivery/api/add_delivery']         = 'Api/DistributorDelivery/add_delivery';
$route['distributordelivery/api/manage_delivery']      = 'Api/DistributorDelivery/manage_delivery';
$route['distributordelivery/api/order_process']        = 'Api/DistributorDelivery/order_process';
$route['distributordelivery/api/manage_order_stock']   = 'Api/DistributorDelivery/manage_orderStkDetails';
$route['distributordelivery/api/distributor_delivery'] = 'Api/DistributorDelivery/distributor_delivery';

// sub distributors
$route['subdistributors/api/initial_details'] = 'Api/Subdistributors/initial_details';

// New Report
$route['report/api/outlet_outstanding']   = 'Api/Report/outlet_outstanding';

// MIS Report
$route['misreport/api/order_report']      = 'Api/Misreport/order_report';
$route['misreport/api/collection_report'] = 'Api/Misreport/collection_report';

$route['import/api/outlet_import'] = 'Api/Import/outlet_import';
$route['import/api/beat_import']   = 'Api/Import/beat_import';

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'user\UserauthenticateController@index');

Route::get('home', 'user\UserauthenticateController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);


Route::get('/admin', 'admin\AdminauthenticateController@index');
Route::post('/admin/login', 'admin\AdminauthenticateController@login');
Route::get('/admin/logout', 'admin\AdminController@logout');
Route::get('/admin/dashboard', 'admin\AdminController@dashboard');
Route::get('/admin/users_email_check', 'admin\AdminController@useremailcheck');

Route::get('/admin/area', 'admin\AreaController@area');
Route::post('/admin/area_add', 'admin\AreaController@areaadd');
Route::post('/admin/area_details', 'admin\AreaController@areadetails');
Route::post('/admin/area_update', 'admin\AreaController@areaupdate');
Route::get('/admin/area_status', 'admin\AreaController@areastatus');

Route::get('/admin/area_manager', 'admin\ManagerController@areamanager');
Route::post('/admin/area_manager_add', 'admin\ManagerController@areamanageradd');
Route::post('/admin/area_manager_details', 'admin\ManagerController@areamanagerdetails');
Route::get('/admin/area_manager_status', 'admin\ManagerController@areamanagerstatus');
Route::post('/admin/area_manager_update', 'admin\ManagerController@areamanagerupdate');


Route::get('/admin/sales_rep', 'admin\SalesrepController@salesrep');
Route::post('/admin/sales_rep_add', 'admin\SalesrepController@salesrepadd');
Route::post('/admin/sales_rep_details', 'admin\SalesrepController@salesrepdetails');
Route::get('/admin/sales_rep_status', 'admin\SalesrepController@salesrepstatus');
Route::post('/admin/sales_rep_update', 'admin\SalesrepController@salesrepupdate');


Route::get('/admin/route', 'admin\RouteController@route');
Route::post('/admin/route_add', 'admin\RouteController@routeadd');
Route::post('/admin/route_details', 'admin\RouteController@routedetails');
Route::get('/admin/route_status', 'admin\RouteController@routestatus');
Route::post('/admin/route_update', 'admin\RouteController@routeupdate');
Route::post('/admin/route_select_rep', 'admin\RouteController@routeselectrep');

Route::get('/admin/shop', 'admin\ShopController@shop');
Route::post('/admin/shop_add', 'admin\ShopController@shopadd');
Route::post('/admin/shop_details', 'admin\ShopController@shopdetails');
Route::get('/admin/shop_status', 'admin\ShopController@shopstatus');
Route::post('/admin/shop_update', 'admin\ShopController@shopupdate');

Route::post('/admin/shop_form_filter_area', 'admin\ShopController@shopformfilterarea');
Route::post('/admin/shop_form_filter_sales', 'admin\ShopController@shopformfiltersales');


Route::get('/admin/network', 'admin\NetworkController@network');
Route::post('/admin/network_add', 'admin\NetworkController@networkadd');
Route::post('/admin/network_details', 'admin\NetworkController@networkdetails');
Route::get('/admin/network_status', 'admin\NetworkController@networkstatus');
Route::post('/admin/network_update', 'admin\NetworkController@networkupdate');

Route::get('/admin/simcards', 'admin\SimcardController@simcards');
Route::post('/admin/import_sim', 'admin\SimcardController@import_sim');
Route::get('/admin/import_sim_one', 'admin\SimcardController@import_sim_one');
Route::post('/admin/import_activation_sim', 'admin\SimcardController@import_activation_sim');
Route::get('/admin/import_activation_sim_one', 'admin\SimcardController@import_activation_sim_one');

Route::post('/admin/sim_import_details', 'admin\SimcardController@simimportdetails');



/*-----------Sales REP----------------*/



Route::get('/sales', 'sales\SalesauthenticateController@index');
Route::post('/sales/login', 'sales\SalesauthenticateController@login');
Route::get('/sales/logout', 'sales\SalesController@logout');
Route::get('/sales/dashboard', 'sales\SalesController@dashboard');

Route::get('/sales/route', 'sales\RouteController@route');
Route::post('/sales/route_add', 'sales\RouteController@routeadd');
Route::post('/sales/route_details', 'sales\RouteController@routedetails');
Route::get('/sales/route_status', 'sales\RouteController@routestatus');
Route::post('/sales/route_update', 'sales\RouteController@routeupdate');

Route::get('/sales/shop', 'sales\ShopController@shop');
Route::post('/sales/shop_add', 'sales\ShopController@shopadd');
Route::post('/sales/shop_details', 'sales\ShopController@shopdetails');
Route::get('/sales/shop_status', 'sales\ShopController@shopstatus');
Route::post('/sales/shop_update', 'sales\ShopController@shopupdate');

Route::get('/sales/shop_list_route/{id?}', 'sales\ShopController@shoplistroute');
Route::get('/sales/shop_view_details/{id?}', 'sales\ShopController@shopviewdetails');

Route::post('/sales/shop_form_filter_sales', 'sales\ShopController@shopformfiltersales');

Route::post('/sales/sim_allocate_shop', 'sales\ShopController@simallocateshop');

Route::post('/sales/sim_save', 'sales\ShopController@simsave');

/*--------28-01-2020----Sales REP--------*/

Route::get('/sales/start_day_stock', 'sales\StockController@startdaystock');
Route::post('/sales/add_start_day_stock', 'sales\StockController@addstartdaystock');

Route::get('/sales/end_day_stock', 'sales\StockController@enddaystock');
Route::post('/sales/add_end_day_stock', 'sales\StockController@addenddaystock');

/*--------01-02-2020----Sales REP--------*/
Route::get('/admin/network_check', 'admin\NetworkController@networkcheck');
Route::get('/sales/time_sheet', 'sales\StockController@timesheet');
Route::post('/sales/visit_only', 'sales\ShopController@visitonly');
Route::get('/sales/time_sheet_previous/{id?}', 'sales\StockController@timesheetprevious');

Route::post('/admin/add_stock', 'admin\StockController@addstock');
Route::get('/sales/stock_month', 'sales\StockController@stockmonth');
Route::get('/sales/view_stock/{id?}', 'sales\StockController@viewstock');

Route::post('/sales/stock_update', 'sales\StockController@stockupdate');
Route::get('/admin/view_stock/{id?}', 'admin\StockController@viewstock');
Route::get('/admin/view_stock_details/{id?}', 'admin\StockController@viewstockdetails');

Route::post('/admin/search_common', 'admin\ShopController@searchcommon');

Route::post('/admin/filter_for_shop', 'admin\ShopController@filterforshop');
Route::post('/admin/total_sim', 'admin\SimcardController@totalsim');
Route::post('/admin/active_sim', 'admin\SimcardController@activesim');
Route::post('/admin/inactive_sim', 'admin\SimcardController@inactivesim');

Route::post('/admin/filter_for_salesrep', 'admin\SalesrepController@filterforsalesrep');

Route::post('/admin/filter_route', 'admin\RouteController@filterroute');
Route::post('/sales/filter_route', 'sales\RouteController@filterroute');

Route::post('/sales/total_sim', 'sales\RouteController@totalsim');
Route::post('/sales/active_sim', 'sales\RouteController@activesim');
Route::post('/sales/inactive_sim', 'sales\RouteController@inactivesim');

Route::post('/sales/filter_shop_route', 'sales\ShopController@filtershoproute');
Route::post('/sales/search_common', 'sales\ShopController@searchcommon');

Route::get('/sales/check_sim', 'sales\SalesController@checksim');
Route::post('/sales/check_sim_scan', 'sales\SalesController@checksimscan');
Route::post('/sales/sim_return_shop', 'sales\ShopController@simreturnshop');

Route::post('/sales/return_sim', 'sales\ShopController@returnsim');
Route::get('/admin/time_sheet/{id?}', 'admin\SalesrepController@timesheet');
Route::get('/admin/time_sheet_previous/{id?}', 'admin\SalesrepController@timesheetprevious');

Route::get('/admin/shop_view_details/{id?}', 'admin\ShopController@shopviewdetails');
Route::post('/admin/refresh_salesrep', 'admin\SalesrepController@refreshsalesrep');
Route::post('/admin/salesrep_sim_total', 'admin\SalesrepController@salesrepsimtotal');
Route::post('/admin/salesrep_sim_active', 'admin\SalesrepController@salesrepsimactive');
Route::post('/admin/salesrep_sim_inactive', 'admin\SalesrepController@salesrepsiminactive');

Route::get('/admin/connection_level', 'admin\ConnectionController@connection');
Route::post('/admin/connection_add', 'admin\ConnectionController@connectionadd');
Route::post('/admin/connection_details', 'admin\ConnectionController@connectiondetails');
Route::post('/admin/connection_update', 'admin\ConnectionController@connectionupdate');
Route::get('/admin/connection_status', 'admin\ConnectionController@connectionstatus');
Route::get('/admin/connection_level_check', 'admin\ConnectionController@connectionlevelcheck');

Route::get('/admin/commission', 'admin\CommissionController@commission');
Route::post('/admin/commission_add', 'admin\CommissionController@commissionadd');
Route::post('/admin/commission_details', 'admin\CommissionController@commissiondetails');
Route::post('/admin/commission_update', 'admin\CommissionController@commissionupdate');
Route::get('/admin/commission_status', 'admin\CommissionController@commissionstatus');

Route::get('/admin/setting', 'admin\AdminController@setting');
Route::post('/admin/setting_update', 'admin\AdminController@settingupdate');
Route::get('/admin/admin_password_check', 'admin\AdminController@adminpasswordcheck');
Route::post('/admin/sales_rep_location', 'admin\SalesrepController@salesreplocation');
Route::post('/admin/upload_commission', 'admin\CommissionController@upload_commission');
Route::get('/admin/upload_commission_one', 'admin\CommissionController@upload_commission_one');
Route::get('/admin/upload_commission_page', 'admin\CommissionController@upload_commission_page');
Route::get('/admin/review_commission', 'admin\CommissionController@review_commission');
Route::get('/admin/proceed_commission', 'admin\CommissionController@proceed_commission');

Route::post('/admin/check_commission_plan', 'admin\CommissionController@check_commission_plan');
Route::post('/admin/update_commission_for_date', 'admin\CommissionController@update_commission_for_date');
Route::post('/admin/update_commission_for_date_shop', 'admin\CommissionController@update_commission_for_date_shop');
Route::get('/admin/shop_review_commission', 'admin\CommissionController@shop_review_commission');

Route::post('/admin/print_pdf_for_shop', 'admin\CommissionController@print_pdf_for_shop');
Route::get('/admin/delete_commission_page', 'admin\CommissionController@delete_commission_page');
Route::get('/admin/shops_commission_completed', 'admin\CommissionController@shops_commission_completed');

Route::get('about', 'user\UserController@about');
Route::get('service', 'user\UserController@service');
Route::get('why_choose_us', 'user\UserController@why_choose_us');
/*Route::get('products', 'user\UserController@products');*/
Route::get('career', 'user\UserController@career');
Route::get('contact', 'user\UserController@contact');
Route::get('terms_conditions', 'user\UserController@terms_conditions');
Route::get('delivery_return', 'user\UserController@delivery_return');
Route::get('privacy_policy', 'user\UserController@privacy_policy');


Route::post('/shop/login', 'user\ShopauthenticateController@login');
Route::get('/shop/logout', 'user\ShopController@logout');
Route::get('/shop/my_account', 'user\ShopController@myaccount');
Route::get('/shop/password_check', 'user\ShopController@passwordcheck');
Route::post('/shop/password_update', 'user\ShopController@passwordupdate');

Route::get('/shop/shop_commission', 'user\ShopController@shopcommission');

Route::post('/admin/route_select_rep_edit', 'admin\RouteController@routeselectrepedit');
Route::post('/admin/refresh_shop_icon', 'admin\ShopController@refreshshop');
Route::post('/admin/check_sim_scan', 'admin\AdminController@checksimscan');
Route::post('/admin/refresh_route_icon', 'admin\RouteController@refreshroute');
Route::post('/admin/total_sim_route', 'admin\RouteController@totalsim');
Route::post('/admin/active_sim_route', 'admin\RouteController@activesimroute');
Route::post('/admin/inactive_sim_route', 'admin\RouteController@inactivesimroute');

Route::post('/admin/total_sim_shop', 'admin\ShopController@totalsim');
Route::post('/admin/active_sim_shop', 'admin\ShopController@activesim');
Route::post('/admin/inactive_sim_shop', 'admin\ShopController@inactivesim');

Route::post('/admin/shop_last_3moth', 'admin\ShopController@last3month');
Route::get('/admin/shop_month_on_month', 'admin\ShopController@monthonmonth');


Route::get('/sales/search', 'sales\ShopController@search');

Route::post('/sales/search_common_sales', 'sales\ShopController@searchcommonsales');
Route::post('/admin/month_on_month_scroll', 'admin\ShopController@monthscroll');

Route::post('/admin/report_shop_month_on_month', 'admin\ShopController@report_shop_month_on_month');
Route::post('/admin/filter_for_shop_month', 'admin\ShopController@filterforshopmonth');


Route::get('/areamanager', 'areamanager\AreamanagerauthenticateController@index');
Route::post('/areamanager/login', 'areamanager\AreamanagerauthenticateController@login');
Route::get('/areamanager/logout', 'areamanager\AreamanagerController@logout');
Route::get('/areamanager/dashboard', 'areamanager\AreamanagerController@dashboard');
Route::post('/areamanager/check_sim_scan', 'areamanager\AreamanagerController@checksimscan');


Route::get('/areamanager/sales_rep', 'areamanager\SalesrepController@salesrep');
Route::post('/areamanager/sales_rep_add', 'areamanager\SalesrepController@salesrepadd');
Route::post('/areamanager/sales_rep_details', 'areamanager\SalesrepController@salesrepdetails');
Route::get('/areamanager/sales_rep_status', 'areamanager\SalesrepController@salesrepstatus');
Route::post('/areamanager/sales_rep_update', 'areamanager\SalesrepController@salesrepupdate');

Route::post('/areamanager/filter_for_salesrep', 'areamanager\SalesrepController@filterforsalesrep');

Route::post('/areamanager/refresh_salesrep', 'areamanager\SalesrepController@refreshsalesrep');
Route::post('/areamanager/salesrep_sim_total', 'areamanager\SalesrepController@salesrepsimtotal');
Route::post('/areamanager/salesrep_sim_active', 'areamanager\SalesrepController@salesrepsimactive');
Route::post('/areamanager/salesrep_sim_inactive', 'areamanager\SalesrepController@salesrepsiminactive');

Route::post('/areamanager/sales_rep_location', 'areamanager\SalesrepController@salesreplocation');


Route::get('/areamanager/users_email_check', 'areamanager\AreamanagerController@useremailcheck');

Route::post('/areamanager/add_stock', 'areamanager\StockController@addstock');
Route::get('/areamanager/view_stock/{id?}', 'areamanager\StockController@viewstock');
Route::get('/areamanager/view_stock_details/{id?}', 'areamanager\StockController@viewstockdetails');

Route::get('/areamanager/time_sheet/{id?}', 'areamanager\SalesrepController@timesheet');
Route::get('/areamanager/time_sheet_previous/{id?}', 'areamanager\SalesrepController@timesheetprevious');

Route::get('/areamanager/route', 'areamanager\RouteController@route');
Route::post('/areamanager/route_add', 'areamanager\RouteController@routeadd');
Route::post('/areamanager/route_details', 'areamanager\RouteController@routedetails');
Route::get('/areamanager/route_status', 'areamanager\RouteController@routestatus');
Route::post('/areamanager/route_update', 'areamanager\RouteController@routeupdate');
Route::post('/areamanager/route_select_rep', 'areamanager\RouteController@routeselectrep');

Route::post('/areamanager/total_sim_route', 'areamanager\RouteController@totalsim');
Route::post('/areamanager/active_sim_route', 'areamanager\RouteController@activesimroute');
Route::post('/areamanager/inactive_sim_route', 'areamanager\RouteController@inactivesimroute');
Route::post('/areamanager/refresh_route_icon', 'areamanager\RouteController@refreshroute');

Route::post('/areamanager/filter_route', 'areamanager\RouteController@filterroute');
Route::post('/areamanager/route_select_rep_edit', 'areamanager\RouteController@routeselectrepedit');

Route::get('/areamanager/shop', 'areamanager\ShopController@shop');
Route::post('/areamanager/shop_add', 'areamanager\ShopController@shopadd');
Route::post('/areamanager/shop_details', 'areamanager\ShopController@shopdetails');
Route::get('/areamanager/shop_status', 'areamanager\ShopController@shopstatus');
Route::post('/areamanager/shop_update', 'areamanager\ShopController@shopupdate');

Route::post('/areamanager/filter_for_shop', 'areamanager\ShopController@filterforshop');
Route::post('/areamanager/shop_form_filter_area', 'areamanager\ShopController@shopformfilterarea');
Route::post('/areamanager/shop_form_filter_sales', 'areamanager\ShopController@shopformfiltersales');
Route::get('/areamanager/shop_view_details/{id?}', 'areamanager\ShopController@shopviewdetails');
Route::post('/areamanager/search_common', 'areamanager\ShopController@searchcommon');
Route::post('/areamanager/refresh_shop_icon', 'areamanager\ShopController@refreshshop');

Route::get('/areamanager/check_sim', 'areamanager\AreamanagerController@checksim');
Route::get('/areamanager/search', 'areamanager\ShopController@search');
Route::get('/areamanager/shop_list_route/{id?}', 'areamanager\ShopController@shoplistroute');
Route::post('/areamanager/search_common_areamanager', 'areamanager\ShopController@searchcommonareamanager');

Route::get('/sales/users_email_check', 'sales\ShopController@useremailcheck');
Route::get('/sales/shop_request_password/{id?}', 'sales\ShopController@resetpassword');

Route::get('/admin/accessories', 'admin\AccessoriesController@accessories');

Route::get('/admin/manage_category', 'admin\CategoryController@category');
Route::post('/admin/category_add', 'admin\CategoryController@categoryadd');
Route::post('/admin/category_details', 'admin\CategoryController@categorydetails');
Route::post('/admin/category_update', 'admin\CategoryController@categoryupdate');
Route::get('/admin/category_status', 'admin\CategoryController@categorystatus');


Route::get('/admin/manage_products', 'admin\ProductController@product');
Route::post('/admin/product_add', 'admin\ProductController@productadd');
Route::post('/admin/product_details', 'admin\ProductController@productdetails');
Route::post('/admin/product_update', 'admin\ProductController@productupdate');
Route::get('/admin/product_status', 'admin\ProductController@productstatus');

Route::get('/sales/accessories/{id?}', 'sales\AccessoriesController@accessories');
Route::get('/admin/shop_email_check', 'admin\ShopController@shopemailcheck');
Route::post('/sales/accessories_qty_check', 'sales\AccessoriesController@accessoriesqtycheck');

Route::get('/admin/accessories_salesrep', 'admin\ProductController@salesrep');
Route::get('/admin/accessories_salesrep_products/{id?}', 'admin\ProductController@salesrepproducts');
Route::post('/admin/accessories_add_salesrep/{id?}', 'admin\ProductController@accessoriesaddsalesrep');

Route::post('/admin/shop_inactive_move_salesrep', 'admin\ShopController@shopinactivemove');

Route::get('/sales/order_process/{id?}', 'sales\AccessoriesController@orderprocess');

Route::get('/admin/commission_settings', 'admin\CommissionController@commissionsettings');

Route::get('/sales/process_payment', 'sales\AccessoriesController@processpayment');
Route::post('/sales/accessories_delete', 'sales\AccessoriesController@accessoriesdelete');

Route::get('/admin/manage_coupon', 'admin\CouponController@coupon');
Route::post('/admin/coupon_add', 'admin\CouponController@couponadd');
Route::post('/admin/coupon_details', 'admin\CouponController@coupondetails');
Route::post('/admin/coupon_update', 'admin\CouponController@couponupdate');
Route::get('/admin/coupon_status', 'admin\CouponController@couponstatus');


Route::get('/admin/accessories_setting', 'admin\AccessoriesController@accessoriessetting');

Route::get('/admin/coupon_code_check', 'admin\CouponController@couponcode');
Route::post('/sales/coupon_discount', 'sales\AccessoriesController@coupondiscount');
Route::post('/sales/order_confirm', 'sales\AccessoriesController@orderconfirm');
Route::get('/sales/order_history/{id?}', 'sales\AccessoriesController@orderhistory');
Route::get('/sales/order_details', 'sales\AccessoriesController@orderdetails');

Route::get('/admin/order_history/{id?}', 'admin\AccessoriesController@orderhistory');
Route::get('/admin/order_details', 'admin\AccessoriesController@orderdetails');

Route::post('/admin/release_cheque', 'admin\CommissionController@releasecheque');
Route::post('/admin/cheque_details', 'admin\CommissionController@chequedetails');
Route::post('/admin/release_cheque_update', 'admin\CommissionController@chequeupdate');
Route::post('/admin/cheque_received', 'admin\CommissionController@chequereceived');

Route::post('/sales/cheque_details', 'sales\ShopController@chequedetails');
Route::post('/sales/cheque_update', 'sales\ShopController@chequeupdate');

Route::post('/admin/shop_notes', 'admin\ShopController@shopnotes');
Route::post('/admin/note_update', 'admin\ShopController@noteupdate');

Route::post('/sales/commission_bonus_check', 'sales\AccessoriesController@commissionbonuscheck');
Route::get('/admin/sales_rep_payments/{id?}', 'admin\AccessoriesController@salesreppayments');

Route::post('/admin/salesrep_payment_add', 'admin\AccessoriesController@salesreppaymentadd');
Route::post('/admin/sales_rep_payment_details', 'admin\AccessoriesController@salesreppaymentdetails');
Route::post('/admin/salesrep_payment_update', 'admin\AccessoriesController@salesreppaymentupdate');

Route::post('/admin/sales_rep_products_history', 'admin\ProductController@salesrepproductshistory');

Route::get('/admin/admin_query_check', 'admin\AdminController@admin_query_check');









/*
sales rep order type = 1 = inhand
sales rep order type = 2 = online

shop order = order process table - shop_order - filed = 0 = Sales REP was ordered

------ Commission Cheque ----
1) 0 is In Process
2) 1 is issued
3) 2 is return sales 
4) 3 is return cheque received 


----- invoice checkbox remove inhand orders. So we are not use invoice table ----
sales rep invoice = 0 = online vat yes
sales rep invoice = 1 = inhand vat yes
sales rep invoice = 2 = inhand vat no


------commission_paid----
type = 1 admin release
type = 2 sales rep orders
type = 3 shop orders

status = 0 in process cheque
status = 1 issued cheque
status = 2 return cheque
status = 3 admin received cheque
status = 4 Sales Rep Orders

given_type = 1 issued
given_type = 2 return
given_type = 3 Sales REP orders

*/



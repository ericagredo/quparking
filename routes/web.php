<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
   return view('admin/login');
});

Auth::routes();

//Route::get('/home', 'HomeController@index');

/* ---------- Admin: Common Route List --------- */
Route::any('admin', 'AdminController@adminLogin');
Route::any('admin/dashboard', 'AdminController@index');
Route::any('admin/resetpassword', 'AdminController@forgotpassword');
Route::any('admin/changepassword', 'AdminController@changepassword');
Route::any('admin/login', 'AdminController@dashboard');
Route::get('admin/logout', 'AdminController@logout');
Route::get('admin/resetpasswordpage/{token}', 'AdminController@resetpasswordpage');

/* ------------ Admin : AdminUser Route ----------- */
Route::get('admin/notification_admin', 'AdminController@notification_admin');
Route::post('admin/notification_admin_show', 'AdminController@notification_admin_show');
Route::get('admin/adminusers', 'AdminController@show');
Route::get('admin/createAdminUser', 'AdminController@create');
Route::post('admin/create', 'AdminController@store');
Route::get('admin/editadminUser/{id}', 'AdminController@edit');
Route::post('admin/update/{id}', 'AdminController@update');
Route::post('admin/delete','AdminController@destroy');
Route::post('admin/activeInactiveStatus','AdminController@activeInactiveStatus');
Route::any('admin/ajaxList/','AdminController@adminAjaxList');
Route::any('admin/CheckAdminEmailExist',['as'=>'CheckAdminEmailExist','uses'=>'AdminController@CheckAdminEmailExist']);

/* ------------ Profile Routes ------------ */
Route::get('admin/editProfile', 'AdminController@editProfile');
Route::get('admin/viewProfile', 'AdminController@viewProfile');
Route::post('admin/updateProfile', 'AdminController@updateProfile');
Route::get('admin/profilechangePassword', 'AdminController@profilechangePassword');
Route::post('admin/changepwdProfile', 'AdminController@changepwdProfile');

/* ------------ Static Pages Routes ------------ */
Route::get('staticpages/staticpagelist', 'PageContentController@show');
Route::get('staticpages/createStaticPage', 'PageContentController@create');
Route::post('staticpages/create', 'PageContentController@store');
Route::get('staticpages/editStaticPage/{id}', 'PageContentController@edit');
Route::post('staticpages/update/{id}', 'PageContentController@update');
Route::post('staticpages/delete','PageContentController@destroy');
Route::post('staticpages/activeInactiveStatus','PageContentController@activeInactiveStatus');
Route::any('staticpages/ajaxstaticPageList/','PageContentController@ajaxstaticPageList');

/* ------------ Email Template Routes ------------ */
Route::get('emailtemplate/emailtemplatelist', 'EmailTemplateController@show');
Route::get('emailtemplate/createemailtemplate', 'EmailTemplateController@create');
Route::post('emailtemplate/create', 'EmailTemplateController@store');
Route::get('emailtemplate/editemailtemplate/{id}', 'EmailTemplateController@edit');
Route::post('emailtemplate/update/{id}', 'EmailTemplateController@update');
Route::post('emailtemplate/delete','EmailTemplateController@destroy');
Route::post('emailtemplate/activeInactiveStatus','EmailTemplateController@activeInactiveStatus');
Route::any('emailtemplate/ajaxemailtemplateList/','EmailTemplateController@ajaxemailtemplateList');

/* ------------ App Settings Routes ------------ */
Route::get('appsettings/editappsettings', 'AdminAppSettingsController@edit');
Route::post('appsettings/update', 'AdminAppSettingsController@update');

/* ------------ Admin : Users Routes ----------------- */
Route::any('users/adminUsersList', 'AdminUsersController@index');
Route::get('users/createadminUsers', 'AdminUsersController@create');
Route::post('users/create', 'AdminUsersController@store');
Route::any('users/ajaxadminUsersList/', 'AdminUsersController@ajaxadminUsersList');
Route::get('users/editadminUsers/{id}', 'AdminUsersController@edit');
Route::post('users/update/{id}', 'AdminUsersController@update');
Route::any('users/deleteadminUsers','AdminUsersController@destroy');
Route::post('users/activeInactiveadminUsers','AdminUsersController@activeInactiveadminUsers');
Route::any('users/checkUserEmailExist/','AdminUsersController@checkUserEmailExist');
Route::any('users/checkUsernameExist/','AdminUsersController@checkUsernameExist');

/* ------------ Admin : Parking Spot Routes ----------------- */
Route::any('parkingspot/parkingspotList', 'AdminParkingSpotController@index');
Route::get('parkingspot/createparkingspot', 'AdminParkingSpotController@create');
Route::post('parkingspot/create', 'AdminParkingSpotController@store');
Route::any('parkingspot/ajaxparkingspotList/', 'AdminParkingSpotController@ajaxparkingspotList');
Route::get('parkingspot/editparkingspot/{id}', 'AdminParkingSpotController@edit');
Route::post('parkingspot/update/{id}', 'AdminParkingSpotController@update');
Route::any('parkingspot/deleteparkingspot','AdminParkingSpotController@destroy');
Route::post('parkingspot/activeInactiveparkingspot','AdminParkingSpotController@activeInactiveparkingspot');
Route::any('parkingspot/manageparkingspotGallery/{id}','AdminParkingSpotController@manageparkingspotGallery');
Route::post('parkingspot/saveparkingspot/{id}', 'AdminParkingSpotController@Saveparkingspot');
Route::any('parkingspot/deleteparkingspotGallery', 'AdminParkingSpotController@deleteparkingspotGallery');
Route::any('parkingspot/veryfyParkingspot', 'AdminParkingSpotController@veryfyParkingspot');

/* ------------ Admin : Pricing Routes ----------------- */
Route::any('pricing/pricingList', 'AdminPricingController@index');
Route::get('pricing/createpricing', 'AdminPricingController@create');
Route::post('pricing/create', 'AdminPricingController@store');
Route::any('pricing/ajaxpricingList/', 'AdminPricingController@ajaxpricingList');
Route::get('pricing/editpricing/{id}', 'AdminPricingController@edit');
Route::post('pricing/update/{id}', 'AdminPricingController@update');
Route::any('pricing/deletepricing','AdminPricingController@destroy');
Route::post('pricing/activeInactivepricing','AdminPricingController@activeInactivepricing');

/* ------------Admin : Surcharge Amount Routes ------------ */
Route::get('surchargeamount/editsurchargeamount', 'AdminSurchargeController@edit');
Route::post('surchargeamount/update', 'AdminSurchargeController@update');

/* ------------Admin : General Settings Routes ------------ */
Route::get('generalsettings/editgeneralsettings', 'AdminGeneralSettingsController@edit');
Route::post('generalsettings/update', 'AdminGeneralSettingsController@update');

/* ------------ Admin : Review Questionary Routes ----------------- */
Route::any('review/reviewList', 'AdminReviewController@index');
Route::get('review/createreview', 'AdminReviewController@create');
Route::post('review/create', 'AdminReviewController@store');
Route::any('review/ajaxreviewList/', 'AdminReviewController@ajaxreviewList');
Route::get('review/editreview/{id}', 'AdminReviewController@edit');
Route::post('review/update/{id}', 'AdminReviewController@update');
Route::any('review/deletereview','AdminReviewController@destroy');
Route::post('review/activeInactivereview','AdminReviewController@activeInactivereview');

/* ------------ Admin : Promo codes Routes ----------------- */
Route::any('promocode/promocodeList', 'AdminPromocodeController@index');
Route::get('promocode/createpromocode', 'AdminPromocodeController@create');
Route::post('promocode/create', 'AdminPromocodeController@store');
Route::any('promocode/ajaxpromocodeList/', 'AdminPromocodeController@ajaxpromocodeList');
Route::get('promocode/editpromocode/{id}', 'AdminPromocodeController@edit');
Route::post('promocode/update/{id}', 'AdminPromocodeController@update');
Route::any('promocode/deletepromocode','AdminPromocodeController@destroy');
Route::post('promocode/activeInactivepromocode','AdminPromocodeController@activeInactivepromocode');
Route::any('promocode/sendPromocodeSource','AdminPromocodeController@sendPromocodeSource');

/* ------------ Admin : Contact Us Routes ----------------- */
Route::any('contactus/contactusList', 'AdminContactUsController@index');
Route::any('contactus/ajaxcontactusList/', 'AdminContactUsController@ajaxcontactusList');
Route::get('contactus/editcontactus/{id}', 'AdminContactUsController@edit');
Route::any('contactus/replytousercontactus/{id}', 'AdminContactUsController@replytousercontactus');
Route::any('contactus/deletecontactus','AdminContactUsController@destroy');

/* ------------ Admin : Admin Notification Routes ----------------- */
Route::any('adminnotification/adminnotificationList', 'AdminNotificationController@show');
Route::any('adminnotification/updatenotification', 'AdminNotificationController@update');

/* ------------ Admin : Admin Country Routes ----------------- */
Route::get('country/managecountry',['as'=>'countryList','uses'=>'AdminCountryController@countryList']);
Route::any('country/AjaxCountryList',['as'=>'AjaxCountryList','uses'=>'AdminCountryController@AjaxCountryList']);
Route::post('country/activeInactivecountry',['as'=>'activeInactivecountry','uses'=>'AdminCountryController@activeInactivecountry']);
Route::any('country/deletecountry',['as'=>'deletecountry','uses'=>'AdminCountryController@deletecountry']);
Route::any('country/createNewCountry',['as'=>'createNewCountry','uses'=>'AdminCountryController@createNewCountry']);
Route::post('country/create',['as'=>'store','uses'=>'AdminCountryController@store']);
Route::any('country/CheckCountryNameExist',['as'=>'CheckCountryNameExist','uses'=>'AdminCountryController@CheckCountryNameExist']);
Route::any('country/CheckCountryCodeExist',['as'=>'CheckCountryCodeExist','uses'=>'AdminCountryController@CheckCountryCodeExist']);
Route::get('country/editCountry/{id}',['as'=>'editCountry','uses'=>'AdminCountryController@editCountry']);
Route::post('country/update/{id}',['as'=>'update','uses'=>'AdminCountryController@update']);

/* ------------ Admin : Admin State Routes ----------------- */
Route::get('state/managestate',['as'=>'stateList','uses'=>'AdminStateController@stateList']);
Route::any('state/AjaxStateList',['as'=>'AjaxStateList','uses'=>'AdminStateController@AjaxStateList']);
Route::post('state/activeInactivestate',['as'=>'activeInactivestate','uses'=>'AdminStateController@activeInactivestate']);
Route::any('state/deletestate',['as'=>'deletestate','uses'=>'AdminStateController@deletestate']);
Route::any('state/createNewstate',['as'=>'createNewstate','uses'=>'AdminStateController@createNewstate']);
Route::post('state/create',['as'=>'store','uses'=>'AdminStateController@store']);
Route::any('state/CheckStateNameExist',['as'=>'CheckStateNameExist','uses'=>'AdminStateController@CheckStateNameExist']);
Route::get('state/editState/{id}',['as'=>'editState','uses'=>'AdminStateController@editState']);
Route::post('state/update/{id}',['as'=>'update','uses'=>'AdminStateController@update']);
Route::post('state/get_all_state_by_country_id',['as'=>'get_all_state_by_country_id','uses'=>'AdminStateController@get_all_state_by_country_id']);

/* ------------ Admin : Admin Booking Routes ----------------- */
Route::get('booking/managebooking',['as'=>'bookingListAdmin','uses'=>'BookingController@bookingListAdmin']);
Route::any('booking/AjaxBookingList',['as'=>'AjaxBookingList','uses'=>'BookingController@AjaxBookingList']);
Route::any('booking/deletebooking',['as'=>'deletebooking','uses'=>'BookingController@deletebooking_admin']);
Route::any('booking/manageparkingspotGallery/{generated_booking_id}','BookingController@manageparkingspotGallery');
Route::get('booking/bookingReviewRating/{booking_id}/{user_id}',['as'=>'bookingReviewRating','uses'=>'BookingController@bookingReviewRating']);

/* ------------ Admin : Manage Monthly Reports Routes ----------------- */
Route::get('reports/reportListAdmin',['as'=>'reportListAdmin','uses'=>'AdminMonthlyReportsController@reportListAdmin']);
Route::any('reports/AjaxReportsList',['as'=>'AjaxReportsList','uses'=>'AdminMonthlyReportsController@AjaxReportsList']);
Route::post('reports/PayAmountStatusAjaxSource',['as'=>'PayAmountStatusAjaxSource','uses'=>'AdminMonthlyReportsController@PayAmountStatusAjaxSource']);
Route::any('reports/receiptGallery/{id}','AdminMonthlyReportsController@receiptGallery');

Route::post('reports/Savereceiptreports/{id}', 'AdminMonthlyReportsController@Savereceiptreports');
Route::any('reports/deletereceiptGallery', 'AdminMonthlyReportsController@deletereceiptGallery');

Route::any('reports/monthlyreport',['as'=>'monthlyreport','uses'=>'AdminMonthlyReportsController@monthlyreport']);

/* ------------ Admin : Manage Monthly Refund Routes ----------------- */
Route::get('refund/RefundListAdmin',['as'=>'RefundListAdmin','uses'=>'AdminRefundController@RefundListAdmin']);
Route::any('refund/AjaxRefundsList',['as'=>'AjaxRefundsList','uses'=>'AdminRefundController@AjaxRefundsList']);
Route::post('refund/AjaxBankReceiptStatus',['as'=>'AjaxBankReceiptStatus','uses'=>'AdminRefundController@AjaxBankReceiptStatus']);
Route::post('refund/RefundAmountStatusAjaxSource',['as'=>'RefundAmountStatusAjaxSource','uses'=>'AdminRefundController@RefundAmountStatusAjaxSource']);
Route::any('refund/receiptGallery/{id}','AdminRefundController@receiptGallery');
Route::post('refund/Savereceipt/{id}', 'AdminRefundController@Savereceipt');
Route::any('refund/deletereceiptGallery', 'AdminRefundController@deletereceiptGallery');

/* ------------ Admin : Admin User Notification Routes ----------------- */
Route::get('usernotification/manageusernotification',['as'=>'usernotificationlist','uses'=>'UserNotificationController@usernotificationlist']);
Route::any('usernotification/AjaxUserNotificationList',['as'=>'AjaxUserNotificationList','uses'=>'UserNotificationController@AjaxUserNotificationList']);
Route::post('usernotification/activeInactiveusernotification',['as'=>'activeInactiveusernotification','uses'=>'UserNotificationController@activeInactiveusernotification']);
Route::any('usernotification/createNewUserNotification',['as'=>'createNewUserNotification','uses'=>'UserNotificationController@createNewUserNotification']);
Route::post('usernotification/create',['as'=>'store','uses'=>'UserNotificationController@store']);
Route::any('usernotification/deleteusernotification',['as'=>'deleteusernotification','uses'=>'UserNotificationController@deleteusernotification']);
Route::get('usernotification/editusernotification/{id}',['as'=>'editusernotification','uses'=>'UserNotificationController@editusernotification']);
Route::post('usernotification/update/{id}',['as'=>'update','uses'=>'UserNotificationController@update']);
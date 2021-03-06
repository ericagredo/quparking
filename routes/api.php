<?php

use Illuminate\Http\Request;


/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */

Route::get('/', function() {
    return view('index');
});

//========= Start : Users Details Route ===========//
Route::post('login', 'UsersController@login');
Route::post('logout', 'UsersController@logout');
Route::post('signup', 'UsersController@signup');
Route::post('fetchuserdetails', 'UsersController@fetchUserDetails');
Route::post('changepasswordapi', 'UsersController@changePasswordApi');
Route::post('userprofileedit', 'UsersController@userProfileEdit');
Route::post('forgotpassword', 'UsersController@forgotPassword');
Route::any('resetPassword/{id}', 'UsersController@resetPassword');
Route::post('changePassword', 'UsersController@changePassword'); // page Url
Route::post('socialsignup', 'UsersController@socialSignupFacebookGooglePlus');
Route::post('bankdetailsettings', 'UsersController@bankdetailsettings');
//========= End : Users Details Route ===========//
//
//========= Start : Parking Spot Details Route ===========//
Route::post('registerparkingspot', 'ParkingSpotController@registerParkingSpot');
Route::post('changeverificationcode', 'ParkingSpotController@changeverificationcode');
Route::post('parkingspotlist', 'ParkingSpotController@parkingSpotList');
Route::post('fetchparkingspotdetails', 'ParkingSpotController@fetchParkingSpotDetails');
Route::post('deleteparkingspot', 'ParkingSpotController@deleteParkingSpot');
Route::post('editparkingspot', 'ParkingSpotController@updateParkingSpot');
Route::post('searchableparkingspotlist', 'ParkingSpotController@searchableparkingSpotList');
Route::post('deleteparkingspotimage', 'ParkingSpotController@deleteparkingspotimage');
//========= End : Parking Spot Details Route ===========//


//========= Start : Promotion Details Route ===========//
Route::post('promotionlist', 'PromotionController@promotionList');
Route::post('promotionCodeApply', 'PromotionController@promotionCodeApply');
//========= End : Promotion Details Route ===========//

//========= Start : Static Pages Details Route ===========//
Route::post('staticpagesdetails', 'StaticPagesApiController@staticPagesDetails');
Route::post('contactus', 'StaticPagesApiController@contactus');
//========= End : Static Pages Details Route ===========//

//========= Start : General Route ===========//
Route::post('countrylist', 'GeneralController@countryList');
Route::post('statelist', 'GeneralController@stateList');
//========= End : General Route ===========//

//========= Start : Notification Route ===========//
Route::post('notificationlist', 'UserNotificationController@NotificationList');
Route::post('changenotificationmode', 'UserNotificationController@changeNotificationMode');
//========= End : Notification Route ===========//

//========= Start : Booking Route ===========//
Route::post('addbooking', 'BookingController@addbooking');
Route::post('bookinglist', 'BookingController@bookingList');
Route::post('fetchbookingdetails','BookingController@bookingDetails');
Route::post('deletebooking','BookingController@deletebooking');
Route::post('canclebookingbyuser','BookingController@canclebookingbyuser');
Route::post('canclebooking','BookingController@canclebooking');

Route::post('entryinbooking','BookingController@entryinbooking');
Route::post('exitfrombooking','BookingController@exitfrombooking');
//========= End : Booking Route ===========//

//========= Start : Reservation Route ===========//
Route::post('reservationlist', 'ReservationController@reservationList');
Route::post('fetchreservationdetails','ReservationController@reservationDetails');
Route::post('deletereservation','ReservationController@deletereservation');
Route::post('canclebookingbyhost','ReservationController@canclebookingbyhost');
//========= End : Reservation Route ===========//

//========= Start : Review Route ===========//
Route::post('reviewquestionlist', 'ReviewController@reviewquestionlist');
Route::post('usersreview', 'ReviewController@addusersreview');
Route::post('fetchreviewofuser', 'ReviewController@fetchreviewofuser');
//========= End : Review Route ===========//

//========== Start : Braintree Route =============//
Route::get('checkout', 'BraintreeController@checkout');
Route::post('checkout_post', 'BraintreeController@checkout_post');
Route::post('checkout_post_surcharge', 'BraintreeController@checkout_post_surcharge');
Route::any('transaction_details', 'BraintreeController@transaction_details');

Route::post('booking_invoice', 'BraintreeController@Booking_invoice');
//========== End : Braintree Route =============//


/*========== Start Testing routing =============*/
Route::post('test',['as'=>'test','uses'=>'TestController@test']);

/*============cron function test===============*/
Route::any('user_can_not_exit_from_booking',['as'=>'user_can_not_exit_from_booking','uses'=>'PushNotificationController@user_can_not_exit_from_booking']);
Route::any('befor_exit_time_send_push',['as'=>'befor_exit_time_send_push','uses'=>'PushNotificationController@befor_exit_time_send_push']);
Route::any('spot_high_demand',['as'=>'spot_high_demand','uses'=>'PushNotificationController@notify_when_parking_spot_on_high_demand_nearby']);
Route::any('parking_spot_auto_active_deactive',['as'=>'parking_spot_auto_active_deactive','uses'=>'PushNotificationController@parking_spot_auto_active_deactive']);
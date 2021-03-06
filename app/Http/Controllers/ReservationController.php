<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Http\Requests;
use App\Http\Helper;
use App\Models\ParkingSpot;
use Mail;

use App\Models\DeviceMaster;
use Aws\Credentials\CredentialProvider;

class ReservationController extends Controller {
    /*
     * Request Parameter :  [Apikey, parking_spot_userID, filter_type, timezone, page_limit, page_offset]
     * Method : POST
     * Request Api Url : "/api/reservationList"
     * Request Controller & Method : ReservationController/reservationList
     * Success response : [ message : Success,  code : 200, data : Array of Reservation List]
     * Error response : 
      1)[ message : Reservation not available., code : 101]
      2)[ message : Unauthorised Call. , code : 101]
     */

    public function reservationList(Request $request) {
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $userID = $request->userID;
            $filter_type = $request->filter_type;
            $timezone = $request->timezone;
            $page_limit = $request->page_limit;
            $page_offset = $request->page_offset;

            date_default_timezone_set($timezone);
            $CurrentDateTime = date('Y-m-d H:i:s');

            if ($page_offset) {
                $page_offset1 = $page_offset + 1;
                $start = ($page_offset1 - 1) * $page_limit;
            } else {
                $start = 0;
            }

            $parking_spotSql = "SELECT id 
                                    FROM parking_spot 
                                    WHERE users_id = '".$userID."' 
                                    And status='Active'";
            $parking_spotArray = DB::Select($parking_spotSql);
            
            $i = 0;
            $get_spot_ids = '';
            foreach ($parking_spotArray as $spots_ids) {
                $get_spot_ids .= "FIND_IN_SET($spots_ids->id, parking_spot_id) OR ";
                $i++;
            }

            $spot_ids = rtrim($get_spot_ids, " OR");

            $get_spot_id_sql = '';
            if ($spot_ids != '') {
                $get_spot_id_sql = " AND (" . $spot_ids .")";
            }
            
            $AndWhere = "";
            if ($filter_type == 'upcoming') {
                $AndWhere .= ' And b.booking_date >= STR_TO_DATE( "' . $CurrentDateTime . '", "%Y-%m-%d" ) And b.booking_status="Upcoming"';
            } else if ($filter_type == 'past') {
                $AndWhere .= ' And b.booking_date <= STR_TO_DATE( "' . $CurrentDateTime . '", "%Y-%m-%d" ) And b.booking_status="Cancelled" OR b.booking_status="Completed"';
            }

            $reservationArray = array();
            $reservationSql = "SELECT `b`.*,`u`.firstname, `u`.lastname, `u`.profile_image "
                            . "FROM `booking` as b "
                            . "LEFT JOIN `parking_spot` as p ON `b`.parking_spot_id = p.id "
                            . "LEFT JOIN `users` as u ON `b`.users_id = u.id "
                            . "Where b.status='Active' And b.is_delete_reservation='No' And p.users_id = '".$userID."' 
                             
                            $get_spot_id_sql 
                            $AndWhere 
                            ORDER BY b.id desc limit " . $start . " , " . $page_limit;
            /*And b.booking_status='Completed'*/
            $reservationArray = DB::Select($reservationSql);


            //echo strtotime("2017-00-00 00:00:00"); exit;


            if (count($reservationArray) > 0) {
                foreach($reservationArray as $reservation){
                    /*$reservation->latitude = ($reservation->latitude != "" && $reservation->latitude != null && $reservation->latitude != 'null') ? $reservation->latitude : 0;
                    $reservation->longitude = ($reservation->longitude != "" && $reservation->longitude != null && $reservation->longitude != 'null') ? $reservation->longitude : 0;*/
                    $reservation->id = !empty($reservation->id) ? (int) $reservation->id : 0;
                    $reservation->parking_spot_id = !empty($reservation->parking_spot_id) ? (int) $reservation->parking_spot_id : 0;
                    $reservation->users_id = !empty($reservation->users_id) ? (int) $reservation->users_id : 0;
                    $reservation->booking_amount = !empty($reservation->booking_amount) ? (int) $reservation->booking_amount : 0;
                    $reservation->cancellation_fee = !empty($reservation->cancellation_fee) ? (int) $reservation->cancellation_fee : 0;
                    $reservation->additional_credited_amount = !empty($reservation->additional_credited_amount) ? (int) $reservation->additional_credited_amount : 0;
                    $reservation->paid_amount = !empty($reservation->paid_amount) ? (int) $reservation->paid_amount : 0;
                    $reservation->surcharge_amount = !empty($reservation->surcharge_amount) ? (int) $reservation->surcharge_amount : 0;
                    $reservation->booking_transaction_id = !empty($reservation->booking_transaction_id) ? (string) $reservation->booking_transaction_id : '';

                    $reservation->profile_image = !empty($reservation->profile_image) ? asset('uploads/user_profile_image/'.$reservation->profile_image) : "";

                    //if($reservation->booking_status == "Upcoming"){
                        $CurrentDateTime = date('Y-m-d H:i:s');
                        if($reservation->booking_type == "Months"){
                            $time = strtotime($reservation->booking_date.' '.$reservation->booking_time);
                            $temp_month = "+".$reservation->booking_month." month";
                            $final_end_date = date("Y-m-d H:i:s", strtotime($temp_month, $time));
                            if( strtotime($CurrentDateTime) > strtotime($final_end_date) ){
                                if(strtotime($reservation->entry_date_time) == "" && strtotime($reservation->exit_date_time) == ""){
                                    $update = DB::table('booking')
                                            ->where('id', $reservation->id)
                                            ->update([
                                                'booking_status' => 'Cancelled',
                                                'cancelled_by' => 'User',
                                                'created_date' => Helper::get_curr_datetime(),
                                                'created_by' => $userID
                                            ]);
                                    $reservation->booking_status = "Cancelled";
                                    $reservation->cancelled_by = "User";
                                }
                            }
                        }elseif ($reservation->booking_type == "days"){
                            $time = strtotime($reservation->booking_date.' '.$reservation->booking_time);
                            $temp_day = "+".$reservation->booking_days." day";
                            $final_end_date = date("Y-m-d H:i:s", strtotime($temp_day, $time));
                            if( strtotime($CurrentDateTime) > strtotime($final_end_date) ){
                                if(strtotime($reservation->entry_date_time) == "" && strtotime($reservation->exit_date_time) == ""){
                                    $update = DB::table('booking')
                                        ->where('id', $reservation->id)
                                        ->update([
                                            'booking_status' => 'Cancelled',
                                            'cancelled_by' => 'User',
                                            'created_date' => Helper::get_curr_datetime(),
                                            'created_by' => $userID
                                        ]);
                                    $reservation->booking_status = "Cancelled";
                                    $reservation->cancelled_by = "User";
                                }
                            }
                        }elseif ($reservation->booking_type == "Hours"){
                            $CurrentDate = date('Y-m-d');
                            $current_time = date("H:i:s", time());
                            $time2 = date("H:i:s", strtotime($reservation->booking_time));
                            $temp_hours = '+' . $reservation->booking_hours . ' hour';
                            $time2 = date('H:i:s', strtotime($time2 . $temp_hours));
                            if( strtotime($CurrentDate) >= strtotime($reservation->booking_date) ){
                                if(strtotime($CurrentDate) == strtotime($reservation->booking_date) && $current_time < $time2){

                                }else{
                                    if(strtotime($reservation->entry_date_time) == "" && strtotime($reservation->exit_date_time) == ""){
                                        $update = DB::table('booking')
                                            ->where('id', $reservation->id)
                                            ->update([
                                                'booking_status' => 'Cancelled',
                                                'cancelled_by' => 'User',
                                                'created_date' => Helper::get_curr_datetime(),
                                                'created_by' => $userID
                                            ]);
                                        $reservation->booking_status = "Cancelled";
                                        $reservation->cancelled_by = "User";
                                    }
                                }
                            }
                        }
                    //}
                    $reservation->total_park_time = "";
                    if($reservation->booking_status == "Completed"){
                        $date1 = $reservation->entry_date_time;
                        $date2 = $reservation->exit_date_time;
                        $final_consume_time =  Helper::dateDiff($date1, $date2);
                        $reservation->total_park_time = $final_consume_time;
                    }

                }
                return response()->json(['message' => 'Success', 'code' => 200, 'myReservation' => $reservationArray]);
            } else {
                return response()->json(['message' => 'Reservation not available.', 'code' => 101]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }



    /*
     * Request Parameter :  [Apikey, booking_id, userID]
     * Method : POST
     * Request Api Url : "/api/reservationDetails"
     * Request Controller & Method : ReservationController/reservationDetails
     * Success response : [ message : Success,  code : 200, data : Array of Reservation List]
     * Error response : 
      1)[ message : Reservation not available., code : 101]
      2)[ message : Unauthorised Call. , code : 101]
     */

    public function reservationDetails(Request $request) {
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $surcharge_amount = DB::table('surcharge_amount')->first();

            $booking_id = $request->booking_id;
            $userID = $request->userID;
            $timezone = $request->timezone;
            date_default_timezone_set($timezone);

            $BookingArray = array();
            $BookingSql = "Select b.*,u.id as uid, u.firstname, u.lastname , u.contact_number, u.profile_image, p.latitude, p.longitude,  p.id as pid, p.address, p.postal_code, p.country_id, p.state_id, p.city_name, c.country_name, s.state_name "
                . "from booking as b "
                . "Left Join parking_spot as p ON b.parking_spot_id = p.id "
                . "Left Join users as u ON p.users_id = u.id "
                . "Left Join country as c ON p.country_id = c.id "
                . "Left Join state as s ON p.state_id = s.id "
                . "Where b.status='Active' And b.is_delete_reservation='No' And b.id = '" . $booking_id . "'";
            //. "Where b.status='Active' And b.booking_status='Completed' And b.id = '" . $booking_id . "'";
            $BookingArray = DB::Select($BookingSql);

            if (count($BookingArray) > 0) {

                foreach ($BookingArray as $booking) {

                    $booking->id = !empty($booking->id) ? (int) $booking->id : 0;
                    $booking->parking_spot_id = !empty($booking->parking_spot_id) ? (int) $booking->parking_spot_id : 0;
                    $booking->users_id = !empty($booking->users_id) ? (int) $booking->users_id : 0;
                    $booking->booking_amount = !empty($booking->booking_amount) ? (int) $booking->booking_amount : 0;
                    $booking->cancellation_fee = !empty($booking->cancellation_fee) ? (int) $booking->cancellation_fee : 0;
                    $booking->additional_credited_amount = !empty($booking->additional_credited_amount) ? (int) $booking->additional_credited_amount : 0;
                    $booking->paid_amount = !empty($booking->paid_amount) ? (int) $booking->paid_amount : 0;
                    $booking->surcharge_amount = !empty($booking->surcharge_amount) ? (int) $booking->surcharge_amount : 0;
                    $booking->booking_transaction_id = !empty($booking->booking_transaction_id) ? (string) $booking->booking_transaction_id : '';
                    $booking->booking_hours = !empty($booking->booking_hours) ? (int) $booking->booking_hours : 0;
                    $booking->booking_days = !empty($booking->booking_days) ? (int) $booking->booking_days : 0;
                    $booking->booking_month = !empty($booking->booking_month) ? (int) $booking->booking_month : 0;


                    $booking->uid = !empty($booking->uid) ? (int) $booking->uid : 0;
                    $booking->contact_number = !empty($booking->contact_number) ? (int) $booking->contact_number : 0;
                    $booking->profile_image = !empty($booking->profile_image) ? asset('uploads/user_profile_image/' . $booking->profile_image) : "";
                    $booking->latitude = ($booking->latitude != "" && $booking->latitude != null && $booking->latitude != 'null') ? (float)$booking->latitude : 0.0;
                    $booking->longitude = ($booking->longitude != "" && $booking->longitude != null && $booking->longitude != 'null') ? (float)$booking->longitude : 0.0;

                    $booking->pid = !empty($booking->pid) ? (int) $booking->pid : 0;
                    $booking->address = !empty($booking->address) ? (string) $booking->address : '';
                    $booking->postal_code = !empty($booking->postal_code) ? (string) $booking->postal_code : '';
                    $booking->country_id = ($booking->country_id != "" && $booking->country_id != null && $booking->country_id != 'null') ? (int)$booking->country_id : 0;
                    $booking->state_id = ($booking->state_id != "" && $booking->state_id != null && $booking->state_id != 'null') ? (int)$booking->state_id : 0;
                    $booking->country_name = ($booking->country_name != "" && $booking->country_name != null && $booking->country_name != 'null') ? (string)$booking->country_name : "";
                    $booking->state_name = ($booking->state_name != "" && $booking->state_name != null && $booking->state_name != 'null') ? (string)$booking->state_name : "";
                    $booking->city_name = ($booking->city_name != "" && $booking->city_name != null && $booking->city_name != 'null') ? (string)$booking->city_name : "";

                    $booking->refund_status = "Pending";
                    if($booking->booking_status == "Cancelled"){
                        $booking_refund = DB::table('booking_refund')
                            ->where('id',$booking->id)->first();
                        if(isset($booking_refund) && !empty($booking_refund)){
                            $booking->refund_status = $booking_refund->booking_refund;
                        }
                    }
                    $booking->total_park_time = "";
                    if($booking->booking_status == "Completed"){
                        $date1 = $booking->entry_date_time;
                        $date2 = $booking->exit_date_time;
                        $final_consume_time =  Helper::dateDiff($date1, $date2);
                        $booking->total_park_time = $final_consume_time;
                    }

                    $CurrentDateTime = date('Y-m-d H:i:s');
                    if($booking->booking_type == "Months"){
                        $time = strtotime($booking->booking_date.' '.$booking->booking_time);
                        $temp_month = "+".$booking->booking_month." month";
                        $final_end_date = date("Y-m-d H:i:s", strtotime($temp_month, $time));
                        if( strtotime($CurrentDateTime) > strtotime($final_end_date) ){
                            if(strtotime($booking->entry_date_time) == "" && strtotime($booking->exit_date_time) == ""){
                                $update = DB::table('booking')
                                    ->where('id', $booking->id)
                                    ->update([
                                        'booking_status' => 'Cancelled',
                                        'cancelled_by' => 'User',
                                        'created_date' => Helper::get_curr_datetime(),
                                        'created_by' => $userID
                                    ]);
                                $booking->booking_status = "Cancelled";
                                $booking->cancelled_by = "User";
                            }
                        }
                    }elseif ($booking->booking_type == "days"){
                        $time = strtotime($booking->booking_date.' '.$booking->booking_time);
                        $temp_day = "+".$booking->booking_days." day";
                        $final_end_date = date("Y-m-d H:i:s", strtotime($temp_day, $time));
                        if( strtotime($CurrentDateTime) > strtotime($final_end_date) ){
                            if(strtotime($booking->entry_date_time) == "" && strtotime($booking->exit_date_time) == ""){
                                $update = DB::table('booking')
                                    ->where('id', $booking->id)
                                    ->update([
                                        'booking_status' => 'Cancelled',
                                        'cancelled_by' => 'User',
                                        'created_date' => Helper::get_curr_datetime(),
                                        'created_by' => $userID
                                    ]);
                                $booking->booking_status = "Cancelled";
                                $booking->cancelled_by = "User";
                            }
                        }
                    }elseif ($booking->booking_type == "Hours"){
                        $CurrentDate = date('Y-m-d');
                        $current_time = date("H:i:s", time());
                        $time2 = date("H:i:s", strtotime($booking->booking_time));
                        $temp_hours = '+' . $booking->booking_hours . ' hour';
                        $time2 = date('H:i:s', strtotime($time2 . $temp_hours));
                        $final_end_date = date('Y-m-d H:i:s', strtotime($time2 . $temp_hours));
                        if( strtotime($CurrentDate) >= strtotime($booking->booking_date) ){
                            if(strtotime($CurrentDate) == strtotime($booking->booking_date) && $current_time < $time2){

                            }else{
                                if(strtotime($booking->entry_date_time) == "" && strtotime($booking->exit_date_time) == ""){
                                    $update = DB::table('booking')
                                        ->where('id', $booking->id)
                                        ->update([
                                            'booking_status' => 'Cancelled',
                                            'cancelled_by' => 'User',
                                            'created_date' => Helper::get_curr_datetime(),
                                            'created_by' => $userID
                                        ]);
                                    $booking->booking_status = "Cancelled";
                                    $booking->cancelled_by = "User";
                                }
                            }
                        }
                    }
                    /*$surcharge = 0;
                    if ($booking->booking_type == "Hours"){
                        $exit_date_time = $booking->entry_date_time;
                        $exit_date = date("H:i:s", strtotime($exit_date_time));
                        $temp_hours = '+' . $booking->booking_hours . ' hour';
                        $final_exit_time = date('Y-m-d H:i:s', strtotime($exit_date . $temp_hours));
                    }elseif ($booking->booking_type == "days"){
                        $exit_date_time = strtotime($booking->entry_date_time);
                        $temp_day = "+".$booking->booking_days." day";
                        $final_exit_time = date("Y-m-d H:i:s", strtotime($temp_day, $exit_date_time));
                    }elseif ($booking->booking_type == "Months"){
                        $exit_date_time = strtotime($booking->entry_date_time);
                        $temp_month = "+".$booking->booking_month." month";
                        $final_exit_time = date("Y-m-d H:i:s", strtotime($temp_month, $exit_date_time));
                    }
                    if(strtotime($final_exit_time) < strtotime($booking->exit_date_time)){
                        $date_diff = Helper::timeAgo(strtotime($final_exit_time),strtotime($booking->exit_date_time));
                        if(isset($date_diff) && !empty($date_diff)){
                            if($date_diff[1] == "minutes" && $date_diff[0] < 30){
                                $surcharge = $surcharge_amount->amount_before_half_min;
                            }elseif ($date_diff[1] == "minutes" && $date_diff[0] > 30) {
                                $surcharge = $surcharge_amount->amount_after_half_min;
                            }elseif ($date_diff[1] == "hours") {
                                $surcharge = $date_diff[0] * $surcharge_amount->amount_per_hour;
                            }
                        }
                    }
                    $booking->surcharge_amount = (int)$surcharge;*/
                }
                
                $ExistReview = DB::table('review')->where('booking_id', $booking_id)->Where('users_id', $userID)->first();
                if (count($ExistReview) > 0) {
                    $ExistReview->id = !empty($booking->id) ? (int) $booking->id : 0;
                    $ExistReview->booking_id = !empty($ExistReview->booking_id) ? (int) $ExistReview->booking_id : 0;
                    $ExistReview->users_id = !empty($ExistReview->users_id) ? (int) $ExistReview->users_id : 0;
                    $ExistReview->parking_spot_id = !empty($ExistReview->parking_spot_id) ? (int) $ExistReview->parking_spot_id : 0;
                    $ExistReview->rating = !empty($ExistReview->rating) ? (float) $ExistReview->rating : 0.0;

                    //$ExistReview->questions_answer = (!empty($ExistReview->questions_answer) ? unserialize($ExistReview->questions_answer) : array());
                    /*if(!empty($ExistReview->questions_answer)){
                            $tempQA = json_decode($ExistReview->questions_answer);
                            $temparray = [];
                            foreach($tempQA as $val){
                               foreach ($val as $key1=>$val1){
                                   array_push($temparray, array('answer' =>$val1));
                               }
                            }
                            $ExistReview->questions_answer = $temparray;
                    }  else {
                        $ExistReview->questions_answer = array();
                    }*/
                }else{
                    $ExistReview = array();
                }
                
                $sql="
                    SELECT 
                         p.users_id
                    FROM parking_spot as p
                    LEFT JOIN booking ON booking.parking_spot_id = p.id       
                    WHERE booking.id = '$booking_id'
                    AND p.users_id = '$userID'     
                    ";
                 $results = DB::select( DB::raw($sql) );
            $SubmitedReview = array();     
            if(isset($results) && !empty($results[0]->users_id)){
                $SubmitedReview = DB::table('review')->Where('users_id', $BookingArray[0]->users_id)->where('booking_id', $booking_id)->first();
            }
            if (count($SubmitedReview) > 0) {
                $SubmitedReview->id = !empty($SubmitedReview->id) ? (int) $SubmitedReview->id : 0;
                $SubmitedReview->booking_id = !empty($SubmitedReview->booking_id) ? (int) $SubmitedReview->booking_id : 0;
                $SubmitedReview->users_id = !empty($SubmitedReview->users_id) ? (int) $SubmitedReview->users_id : 0;
                $SubmitedReview->parking_spot_id = !empty($SubmitedReview->parking_spot_id) ? (int) $SubmitedReview->parking_spot_id : 0;
                $SubmitedReview->rating = !empty($SubmitedReview->rating) ? (float) $SubmitedReview->rating : 0.0;

                /*if(!empty($SubmitedReview->questions_answer)){
                    $tempQA = json_decode($SubmitedReview->questions_answer);
                    $temparray = [];
                    foreach($tempQA as $val){
                       foreach ($val as $key1=>$val1){
                           array_push($temparray, array('answer' =>$val1));
                       }
                    }
                     //print_r($temparray);exit;
                    $SubmitedReview->questions_answer = $temparray;
                }  else {
                    $SubmitedReview->questions_answer = array();
                }*/
                //$ExistReview->questions_answer = (!empty($ExistReview->questions_answer) ? unserialize($ExistReview->questions_answer) : array()) ;
                
            }  else {
                $SubmitedReview = array();
            }
               
                return response()->json(['message' => 'Success', 'code' => 200, 'BookingDetails' => $BookingArray, 'ReceivedReview' =>$SubmitedReview , 'SubmitedReview'=>$ExistReview]);
            } else {
                return response()->json(['message' => 'Reservation not available.', 'code' => 101]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }
    
    /*
     * Request Parameter :  [Apikey, booking_id]
     * Method : POST
     * Request Api Url : "/api/deletereservation"
     * Request Controller & Method : ReservationController/deletereservation
     * Success response : [ message : Reservation deleted successfully.,  code : 200]
     * Error response : 
      1)[ message : Reservation not deleted successfully., code : 101]
     * 2)[ message : Unauthorised Call. , code : 101]
     */

    public function deletereservation(Request $request) {
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $booking_id = $request->booking_id;
            $del_parking_spot = DB::table('booking')
                ->where('id', $booking_id)
                ->update([
                    'is_delete_reservation' => 'Yes'
                ]);
            //$del_parking_spot = DB::delete('DELETE FROM `booking` WHERE id IN (' . $booking_id . ')');
            if ($del_parking_spot) {
                $msg = 'Reservation deleted successfully.';
                $code = 200;
                return response()->json(['message' => $msg, 'code' => $code]);
            } else {
                $msg = 'Reservation not deleted successfully.';
                $code = 101;
                return response()->json(['message' => $msg, 'code' => $code]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }
    
    /*
     * Request Parameter :  [Apikey, booking_id]
     * Method : POST
     * Request Api Url : "/api/canclebookingbyhost"
     * Request Controller & Method : ReservationController/canclebookingbyhost
     * Success response : [ message : Booking cancel by host successfully.,  code : 200]
     * Error response : 
      1)[ message : Booking cancel by host not successfully., code : 101]
      2)[ message : Unauthorised Call. , code : 101]
     */

    public function canclebookingbyhost(Request $request) {
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $booking_id = $request->booking_id;
            
            $booking_update_id = DB::table('booking')->where('id', $booking_id)->update([
                    'cancelled_by' => 'Host',
                    'booking_status' => "Cancelled"
            ]);
            if ($booking_update_id) {
                $get_user_by_booking_id = DB::table('booking')
                    ->select('booking.users_id')
                    ->where('booking.id', $booking_id)
                    ->first();
                if($get_user_by_booking_id){
                    $notification = DB::table('notification')
                        ->join('user_notification', 'user_notification.id', '=', 'notification.push_notification_id')
                        ->Where('notification.users_id', $get_user_by_booking_id->users_id)
                        ->get();
                    if(count($notification) > 0 && $notification[4]->notification_mode == "ON") {
                        $deviceDetails = DeviceMaster::Where('users_id', $get_user_by_booking_id->users_id)->where('is_login', 'Yes')->first();
                        if (count($deviceDetails) > 0) {
                            $data = [
                                'notification' => $booking_id,
                                'type' => 'booking',
                                'message' => 'Booking has benn cancle by host'
                            ];
                            $message_title = 'Cancle Booking';

                            DeviceMaster::sendPushNotification($deviceDetails['gcm_arn'], $message_title, $data);

                        }
                    }
                }
                $msg = 'Booking cancel by host successfully.';
                $code = 200;
                return response()->json(['message' => $msg, 'code' => $code]);
            } else {
                $msg = 'Booking cancel by host not successfully.';
                $code = 101;
                return response()->json(['message' => $msg, 'code' => $code]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }
}

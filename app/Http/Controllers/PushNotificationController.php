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
use App\Models\Users;
use App\Models\ParkingspotImages;
use App\Models\ParkingSpot;
use App\Models\Pricing;
use App\Models\SurchargeAmount;
use Mail;
use DateTime;

use App\Models\DeviceMaster;
use Aws\Sns\SnsClient;
use Aws\Credentials\CredentialProvider;


class PushNotificationController extends Controller {

    /*This is cron function for
        => Notify me after fixed 'X' number of hours/days of exit time (as per the booking details)
            Every 30 minutes*/
    public static function user_can_not_exit_from_booking(){

        $txt = date('Y-m-d H:i:s',time()). "start ;";
        $myfile = file_put_contents(public_path('log/user_can_not_exit_from_booking.text'), $txt.PHP_EOL , FILE_APPEND | LOCK_EX);


        $booking_details = DB::table('booking')
            ->leftJoin('users', 'users.id', '=', 'booking.users_id')
            ->select('booking.*','users.timezone')
            ->where('booking.exit_date_time', '=', "0000-00-00 00:00:00")
            /*->where('booking.entry_date_time', '!=', "0000-00-00 00:00:00")*/
            ->where('booking.booking_status', '=', "Upcoming")
            ->where('booking.status', '=', "Active")
            ->where('booking.is_delete', '=', "No")
            ->get();

        if(count($booking_details) > 0){

            foreach ($booking_details as $booking){
                if($booking->timezone){
                    date_default_timezone_set($booking->timezone);
                    $current_datetime = date("Y-m-d H:i:s");
                    $current_time = date("H:i:s", strtotime($current_datetime));
                    $current_date = date("Y-m-d", strtotime($current_datetime));

                    if ($booking->booking_type == "Hours"){
                        $exit_date_time = $booking->booking_date.' '.$booking->booking_time;
                        $exit_date = date("Y-m-d H:i:s", strtotime($exit_date_time));
                        $temp_hours = '+' . $booking->booking_hours . ' hour';
                        $final_exit_time = date('Y-m-d H:i:s', strtotime($exit_date . $temp_hours));
                    }elseif ($booking->booking_type == "days"){
                        $exit_date_time = strtotime($booking->booking_date.' '.$booking->booking_time);
                        $temp_day = "+".$booking->booking_days." day";
                        $final_exit_time = date("Y-m-d H:i:s", strtotime($temp_day, $exit_date_time));
                    }elseif ($booking->booking_type == "Months"){
                        $exit_date_time = strtotime($booking->booking_date.' '.$booking->booking_time);
                        $temp_month = "+".$booking->booking_month." month";
                        $final_exit_time = date("Y-m-d H:i:s", strtotime($temp_month, $exit_date_time));
                    }
                }
                if( strtotime($final_exit_time) < strtotime($current_datetime) ){

                    if($booking->entry_date_time == "0000-00-00 00:00:00"){
                        $update = DB::table('booking')
                            ->where('id', $booking->id)
                            ->update([
                                'booking_status' => 'Completed'
                            ]);
                    }else{
                        $notification = DB::table('notification')
                            ->join('user_notification', 'user_notification.id', '=', 'notification.push_notification_id')
                            ->Where('notification.users_id', $booking->users_id)
                            ->get();

                        if(count($notification) > 0 && $notification[3]->notification_mode == "ON" ){
                            $deviceDetails = DeviceMaster::Where('users_id', $booking->users_id)->where('is_login', 'Yes')->first();
                            if(count($deviceDetails) > 0){
                                $data = [
                                    'notification' => $booking->id,
                                    'type' => 'x_number_of_exit_time',
                                    'message'=> 'Your exit time is expire, please complete your booking'
                                ];
                                $message_title = "Exit time expired \r\nYour paid parking time is over, please exit your vehicle from the Parking Spot to avoid more charges.";
                                //$message_title = "Exit time expired \r\nYour exit time is expire, please complete your booking and avoid more surcharge";

                                $AmazonConfigs = config('aws');
                                $aws = new SnsClient([
                                    'region' => 'us-east-1',
                                    'version' => '2010-03-31',
                                    'credentials' => CredentialProvider::env()
                                ]);
                                $gcm_arn = $deviceDetails['gcm_arn'];
                                $endpointAtt = $aws->getEndpointAttributes(array('EndpointArn' => $gcm_arn));

                                if (!empty($endpointAtt) && $endpointAtt['Attributes']['Enabled'] == 'true') {
                                    DeviceMaster::sendPushNotification($gcm_arn, $message_title, $data);
                                } else if (!empty($endpointAtt) && $endpointAtt['Attributes']['Enabled'] == 'false') {
                                    $del_device_master = DB::delete('DELETE FROM `device_master` WHERE gcm_arn = "' . $gcm_arn . '"');
                                }

                            }
                        }
                    }
                }
            }
                //echo $final_exit_time; exit;
            //if( date("Y-m-d",strtotime($final_exit_time)) == date("Y-m-d",strtotime($current_datetime)) ) {

            //}
        }

    }

    /*This is cron function for
        => Notify me when less than 25% of my time allowance is left*/
    public static function befor_exit_time_send_push(){

        $txt = date('Y-m-d H:i:s',time()). "start ;";
        $myfile = file_put_contents(public_path('log/befor_exit_time_send_push.text'), $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

        $booking_details = DB::table('booking')
            ->leftJoin('users', 'users.id', '=', 'booking.users_id')
            ->select('booking.*','users.timezone')
            /*->where('booking.exit_date_time', '=', "0000-00-00 00:00:00")
            ->where('booking.entry_date_time', '!=', "0000-00-00 00:00:00")*/
            ->where('booking.booking_status', '=', "Upcoming")
            ->where('booking.status', '=', "Active")
            ->where('booking.is_delete', '=', "No")
            ->get();
        if(count($booking_details) > 0) {
            foreach ($booking_details as $booking) {
                date_default_timezone_set($booking->timezone);
                $current_datetime = date("Y-m-d H:i:s");
                $current_time = date("H:i:s", strtotime($current_datetime));
                $current_date = date("Y-m-d", strtotime($current_datetime));

                if ($booking->booking_type == "Hours") {
                    $exit_date_time = $booking->booking_date.' '.$booking->booking_time;
                    $exit_date = date("Y-m-d H:i:s", strtotime($exit_date_time));
                    $temp_hours = '+' . $booking->booking_hours . ' hour';
                    $final_exit_time = date('Y-m-d H:i:s', strtotime($exit_date . $temp_hours));
                } elseif ($booking->booking_type == "days") {
                    $exit_date_time = strtotime($booking->booking_date.' '.$booking->booking_time);
                    $temp_day = "+" . $booking->booking_days . " day";
                    $final_exit_time = date("Y-m-d H:i:s", strtotime($temp_day, $exit_date_time));
                } elseif ($booking->booking_type == "Months") {
                    $exit_date_time = strtotime($booking->booking_date.' '.$booking->booking_time);
                    $temp_month = "+" . $booking->booking_month . " month";
                    $final_exit_time = date("Y-m-d H:i:s", strtotime($temp_month, $exit_date_time));
                }
            }
            $final_exit_time1 = $final_exit_time;
            $final_exit_time = date("Y-m-d H:i:s",strtotime ( '-30 minute' , strtotime ( $final_exit_time ) )) ;
            if( date("Y-m-d",strtotime($final_exit_time)) == date("Y-m-d",strtotime($current_datetime)) ) {
                if (strtotime($final_exit_time) <= strtotime($current_datetime) && strtotime($final_exit_time1) > strtotime($current_datetime)) {

                    $notification = DB::table('notification')
                        ->join('user_notification', 'user_notification.id', '=', 'notification.push_notification_id')
                        ->Where('notification.users_id', $booking->users_id)
                        ->get();
                    if(count($notification) > 0 && $notification[5]->notification_mode == "ON"){
                        $deviceDetails = DeviceMaster::Where('users_id', $booking->users_id)->where('is_login', 'Yes')->first();
                        if(count($deviceDetails) > 0){
                            $data = [
                                'notification' => $booking->id,
                                'type' => '25_percent_time_left',
                                'message'=> 'Your exit time is '.$final_exit_time1.'please exit from spot before finish your booking time'
                            ];
                            //$message_title = "Your exit time is $final_exit_time1 \r\nPlease exit from spot before finish your booking time";
                            $message_title = "Your exit time is $final_exit_time1 \r\nYour paid parking time is nearing an end, please exit your vehicle on time to avoid more charges.";

                            $AmazonConfigs = config('aws');
                            $aws = new SnsClient([
                                'region' => 'us-east-1',
                                'version' => '2010-03-31',
                                'credentials' => CredentialProvider::env()
                            ]);
                            $gcm_arn = $deviceDetails['gcm_arn'];
                            $endpointAtt = $aws->getEndpointAttributes(array('EndpointArn' => $gcm_arn));

                            if (!empty($endpointAtt) && $endpointAtt['Attributes']['Enabled'] == 'true') {
                                DeviceMaster::sendPushNotification($gcm_arn, $message_title, $data);
                            } else if (!empty($endpointAtt) && $endpointAtt['Attributes']['Enabled'] == 'false') {
                                $del_device_master = DB::delete('DELETE FROM `device_master` WHERE gcm_arn = "' . $gcm_arn . '"');
                            }

                        }
                    }

                }
            }
        }
    }

    /*This is cron function for
        => Notify me when there is high demand nearby*/
    public function notify_when_parking_spot_on_high_demand_nearby(){
        /*date_default_timezone_set('Asia/Kolkata');
        $current_datetime = date("Y-m-d H:i:s");
        $current_time = date("H:i:s", strtotime($current_datetime));
        $current_date = date("Y-m-d", strtotime($current_datetime));*/

        /*$txt = $current_datetime. "start ;";
        $myfile = file_put_contents(public_path('log/user_can_not_exit_from_booking.text'), $txt.PHP_EOL , FILE_APPEND | LOCK_EX);*/




    }

    /*this function for Parking spot auto active and Inactive
        Cron should be run on every 10 minutes*/
    public function parking_spot_auto_active_deactive(){

        $parking_spot = "  SELECT 
                                p.*,
                                u.timezone
                           FROM parking_spot as p
                           LEFT JOIN users as u ON u.id = p.users_id
                           WHERE p.verification_status = 'Yes'
                           AND p.is_delete = 'No' ";
        $parking_spot = DB::Select($parking_spot);
        if( isset($parking_spot) && !empty($parking_spot)){
            foreach ($parking_spot as $spot){

                date_default_timezone_set($spot->timezone);

                $current_datetime = date("Y-m-d H:i:s");
                $current_time = date("H:i:s", strtotime($current_datetime));
                $current_date = date("Y-m-d", strtotime($current_datetime));
                $day = date("l", strtotime($current_datetime));
                if($spot->renting_type == "Auto Rent"){
                    switch ($day) {
                        case "Monday":
                            $start_time = $current_date.' '.$spot->mon_start_time;
                            $end_time = $current_date.' '.$spot->mon_end_time;
                            break;
                        case "Tuesday":
                            $start_time = $current_date.' '.$spot->tue_start_time;
                            $end_time = $current_date.' '.$spot->tue_end_time;
                            break;
                        case "Wednesday":
                            $start_time = $current_date.' '.$spot->wed_start_time;
                            $end_time = $current_date.' '.$spot->wed_end_time;
                            break;
                        case "Thursday":
                            $start_time = $current_date.' '.$spot->thur_start_time;
                            $end_time = $current_date.' '.$spot->thur_end_time;
                            break;
                        case "Friday":
                            $start_time = $current_date.' '.$spot->fri_start_time;
                            $end_time = $current_date.' '.$spot->fri_end_time;
                            break;
                        case "Saturday":
                            $start_time = $current_date.' '.$spot->sat_start_time;
                            $end_time = $current_date.' '.$spot->sat_end_time;
                            break;
                        case "Sunday":
                            $start_time = $current_date.' '.$spot->sun_start_time;
                            $end_time = $current_date.' '.$spot->sun_end_time;
                            break;
                    }
                    //echo strtotime('-30 minute',strtotime(date('Y-m-d',strtotime('2017-09-22')).' '.'00:00:00')); exit;
                    if ($start_time == "00:00:00" && $end_time == "00:00:00"){
                        $update = DB::table('parking_spot')
                            ->where('id', $spot->id)
                            ->update([
                                'parking_spot_search' => 'Inactive',
                            ]);
                    }elseif(strtotime('-30 minute',strtotime($start_time)) < strtotime($current_datetime) && strtotime($start_time) > strtotime($current_datetime)){
                        $update = DB::table('parking_spot')
                            ->where('id', $spot->id)
                            ->update([
                                'parking_spot_search' => 'Active',
                            ]);
                    }elseif (strtotime($end_time) < strtotime($current_datetime) && strtotime('+30 minute',strtotime($end_time)) > strtotime($current_datetime)){
                        $update = DB::table('parking_spot')
                            ->where('id', $spot->id)
                            ->update([
                                'parking_spot_search' => 'Inactive',
                            ]);
                    }
                }elseif ($spot->renting_type == "Schedule Rent"){
                    $final_end_date = date("Y-m-d", strtotime($spot->sche_start_date));
                    if($spot->no_of_months >0){
                        $time = strtotime($spot->sche_start_date);
                        $temp_month = "+".$spot->no_of_months." month";
                        $final_end_date = date("Y-m-d", strtotime($temp_month, $time));
                    }
                    if($spot->no_of_days >0){
                        if(isset($final_end_date) && !empty($final_end_date)){
                            $time = strtotime($final_end_date);
                            $temp_day = "+".$spot->no_of_days." day";
                            $final_end_date = date("Y-m-d", strtotime($temp_day, $time));
                        }else{
                            $time = strtotime($spot->sche_start_date);
                            $temp_day = "+".$spot->no_of_days." day";
                            $final_end_date = date("Y-m-d", strtotime($temp_day, $time));
                        }
                    }
                    if($spot->no_of_hours >0) {
                        $time2 = date("H:i:s", strtotime($spot->sche_start_time));
                        $temp_hours = '+' . $spot->no_of_hours . ' hour';
                        $time2 = date('H:i:s', strtotime($time2 . $temp_hours));
                    }else{
                        $time2 = $spot->sche_start_time;
                    }
                    $final_end_date = $final_end_date.' '.$time2;

                    if(strtotime('-30 minute',strtotime(date('Y-m-d',strtotime($spot->sche_start_date)).' '.$spot->sche_start_time)) < strtotime($current_datetime)){
                        $update = DB::table('parking_spot')
                            ->where('id', $spot->id)
                            ->update([
                                'parking_spot_search' => 'Active',
                            ]);
                    }elseif ( strtotime($final_end_date) < strtotime($current_datetime) && strtotime('+30 minute',strtotime($final_end_date)) > strtotime($current_datetime) ){
                        $update = DB::table('parking_spot')
                            ->where('id', $spot->id)
                            ->update([
                                'parking_spot_search' => 'Inactive',
                            ]);
                    }
                }
            }
        }

    }
}

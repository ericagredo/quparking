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
use App\Models\ParkingSpot;
use App\Models\Pricing;
use App\Models\GeneralSettings;
use Mail;

use App\Models\DeviceMaster;
use Aws\Sns\SnsClient;
use Aws\Credentials\CredentialProvider;


class BookingController extends Controller {
    /*=============================Start Admin Function===============================*/
    
    /* Start - this function for redirect on booking list*/
    public function bookingListAdmin() {
        return view('booking/bookigList');
    }
    /* End - this function for redirect on booking list*/
    
    /* Start - this function for get booking list using datatable*/
    public function AjaxBookingList($order_by = "id", $sort_order = "asc", $search = "all", $offset = 0) {
        
        $Booking = new \App\Models\Booking();

        $aColumns = array('id','firstname','lastname','','contact_number',
                          'host_firstname','host_lastname','','host_contact_number',
            'address','postal_code','country_name','state_name','city_name',
            'generated_booking_id','booking_date','booking_time','booking_amount','booking_hours','booking_days','booking_month','booking_type','booking_status','status');
        $grid_data = Helper::get_search_data($aColumns);
        
        
        $sort_order = $grid_data['sort_order'];
        $order_by = $grid_data['order_by'];
        if ($grid_data['sort_order'] == '' && $grid_data['order_by'] == '') {
            $order_by = 'id';
            $sort_order = 'DESC';
        }

        /*
         * Paging
         */
        $limit = $grid_data['per_page'];
        $offset = $grid_data['offset'];


        $SearchType = $grid_data['SearchType'];
        $search_data = $grid_data['search_data'];
        
        $data = $this->trim_serach_data($search_data, $SearchType);
        
        $Booking->set_data($data);
        $Booking->set_SearchType($SearchType);
        $Booking->set_order_by($order_by);
        $Booking->set_sort_order($sort_order);
        $Booking->set_limit($limit);
        $Booking->set_offset($offset);
        $result = $Booking->get_data_for_booking();
        
        /*echo '<pre>';
        print_r($result);exit;*/
       
        $data = array();
        if (count($result) > 0) {
            $data['result'] = $result;
            $data['totalRecord'] = $Booking->count_all_booking_grid();
        }
        
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => 0,
            "iTotalDisplayRecords" => 0,
            "aaData" => array()
        );
        
        if (isset($data) && !empty($data)) {
            if (isset($data['result']) && !empty($data['result'])) {
                $output = array(
                    "sEcho" => intval($_GET['sEcho']),
                    "iTotalRecords" => $data['totalRecord'],
                    "iTotalDisplayRecords" => $data['totalRecord'],
                    "aaData" => array()
                );
                /*echo '<pre>';
                print_r($data['result']);exit;*/
                foreach ($data['result'] AS $result_row) {
                    $row = array();
                    $row[] = $result_row->id;
                    $row[] = $result_row->firstname;
                    $row[] = $result_row->lastname;
                    $row[] = !empty($result_row->profile_image) ? asset('uploads/user_profile_image/' . $result_row->profile_image) : '';
                    $row[] = $result_row->contact_number;
                    $row[] = $result_row->host_firstname;
                    $row[] = $result_row->host_lastname;
                    $row[] = !empty($result_row->host_profile_image) ? asset('uploads/user_profile_image/' . $result_row->host_profile_image) : '';
                    $row[] = $result_row->host_contact_number;
                    $row[] = $result_row->address;
                    $row[] = $result_row->postal_code;
                    $row[] = $result_row->country_name;
                    $row[] = $result_row->state_name;
                    $row[] = $result_row->city_name;
                    $row[] = $result_row->generated_booking_id;
                    $row[] = $result_row->booking_date;
                    $row[] = $result_row->booking_time;
                    $row[] = $result_row->booking_amount;
                    $row[] = $result_row->booking_hours;
                    $row[] = $result_row->booking_days;
                    $row[] = $result_row->booking_month;
                    $row[] = $result_row->booking_type;
                    $row[] = $result_row->booking_status;
                    $row[] = $result_row->status;
                    $row[] = $result_row->users_id;
                    //$row[] = array();
                    $output['aaData'][] = $row;
                }
                
            }
        }
        //print_r(json_encode($output));exit;
        echo json_encode($output);
    }
    /* End - this function for get booking list using datatable*/
    
    /* Start this is halping function for data table and & like query*/
    public function trim_serach_data($search_data, $SearchType) {
       $QueryStr = array();

        if (!empty($search_data)) {
            if ($SearchType == 'ANDLIKE') {
                $i = 0;
                foreach ($search_data as $key => $val) {
                    if ($key == 'firstname' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'users.firstname';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'lastname' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'users.lastname';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'host_firstname' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'host.firstname';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'host_lastname' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'host.lastname';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    
                    if ($key == 'address' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'parking_spot.address';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'country_name' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'country.country_name';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'state_name' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'state.state_name';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'city_name' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'parking_spot.city_name';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'booking_type' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'booking.booking_type';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = '=';
                    }
                    if ($key == 'status' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'booking.status';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = '=';
                    }
                    $i++;
                }
            } 
        }
        return $QueryStr;
    }
    /* End this is halping function for data table and & like query*/
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletebooking_admin(Request $request) {
        $Booking = new \App\Models\Booking();
        $id = $request->id;
        //Code to check if the id from the url exists in the table or not starts here. 
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'booking');
        if (!$id_exists) {
            return view('error.404');
        }
        //Code to check id ends here
        $del_user = $Booking->delete_record_of_booking_id_ids($id);

        if ($del_user) {
            echo '1';
        } else {
            echo '0';
        }
        exit;
    }
    /*Start - This function for view image of parking spot*/
    public function manageparkingspotGallery($generated_booking_id) {
        
        $Booking = new \App\Models\Booking();
        $parking_spot_images = $Booking->get_parking_spot_image($generated_booking_id);
        
        /*echo '<pre>';
        print_r($parking_spot_images);exit;*/
        return view('booking/manageparkingspotImage', ['parking_spot_images' => $parking_spot_images, 'parking_spot_id' => $parking_spot_images[0]->parking_spot_id]);
    }
    /*END - This function for view image of parking spot*/
    
    /*Start - This function for view review and rating page*/
    public function bookingReviewRating($booking_id,$user_id) {
        
        $Review = new \App\Models\Review();
        $review_reting_details = $Review->review_reting_details_by_renter_id_and_booking_id($booking_id,$user_id);
        
        if(isset($review_reting_details) && !empty($review_reting_details)){
            $questions_answer = json_decode($review_reting_details[0]->questions_answer);
            $temp_array = [];
            foreach($questions_answer as $loop){
                 foreach($loop as $key=>$single){
                        $get_all_questions = $Review->get_question_by_id($key);
                        if(isset($get_all_questions) && !empty($get_all_questions)){
                            $get_all_questions[0]->answer = $single;
                        }
                        array_push($temp_array,$get_all_questions);
                    }
            }
            $review_reting_details[0]->questions_answer = $temp_array;
            
            $review_reting_details_host = $Review->review_reting_details_by_booking_id_for_host($booking_id);
            $questions_answer = json_decode($review_reting_details_host[0]->questions_answer);
            $temp_array_host = [];
            foreach($questions_answer as $loops){
                 foreach($loops as $key=>$single){
                        $get_all_questions_host = $Review->get_question_by_id($key);
                        if(isset($get_all_questions_host) && !empty($get_all_questions_host)){
                            $get_all_questions_host[0]->answer = $single;
                        }
                        array_push($temp_array_host,$get_all_questions_host);
                    }
            }
            $review_reting_details_host[0]->questions_answer = $temp_array_host;
            /*echo '<pre>';
            print_r($review_reting_details_host); exit;*/
            
            return view('booking/bookingReviewRating',['review_reting_details' => $review_reting_details,'review_reting_details_host' => $review_reting_details_host]);
        }else {
            $message = 'Review and Rating details are not available in this booking.';
            return redirect('booking/managebooking')->with('message', $message);
        }
        
        
    }
    /*END - This function for view review and rating page*/
    
   
    /*=============================Start API Function===============================*/
    /*
     * Request Parameter :  [Apikey, userID, parking_spot_id, selected_date, selected_time, no_of_hours, no_of_days, no_of_month]
     * Method : POST
     * Request Api Url : "/api/addbooking"
     * Request Controller & Method : BookingController/addbooking
     * Success response : [ message : Parking Spot save SuccessFully.,  code : 200, data : Array of Parking Spot Details]
     * Error response : 
      1)[ message : Parking spot does not available., code : 101]
      2)[ message : Booking does not save SuccessFully., code : 101]
      3)[ message : Unauthorised Call. , code : 101]
     */

    public function addbooking(Request $request) {
        $userID = $request->userID;
        $parking_spot_id = $request->parking_spot_id;
        $selected_date = date('Y-m-d', strtotime($request->selected_date));
        $selected_time = date('H:i:s', strtotime($request->selected_time));
        $no_of_hours_req = $request->no_of_hours;
        $no_of_days_req = $request->no_of_days;
        $no_of_month_req = $request->no_of_month;
        $booking_transaction_id = trim($request->booking_transaction_id);
        $paid_amount_get = $request->paid_amount;
        $additional_credited_amount = $request->additional_credited_amount;

        $cancellation_fee = '';
        //---------------------- Start : Send admin pricing in array --------------//
        $pricing_array = array();
        $pricing_array = Pricing::select('id', 'no_of_hours', 'hourly_price', 'no_of_days', 'daily_price', 'no_of_month', 'monthly_price', 'monthly_price')->Where('status', 'Active')->first();
        $GeneralSettings = GeneralSettings::select('cancellation_fee', 'commission_amount')->first();
        //---------------------- End : Send admin pricing in array --------------//
        $paid_amount = '';
        $total_time = '';
        $booking_amount = '';
        if (!empty($no_of_hours_req)) {
            $no_of_hours = $no_of_hours_req;
            $no_of_days = '';
            $no_of_month = '';
            $booking_amount =  (count($pricing_array) > 0) ? $pricing_array->hourly_price : '0';
            $paid_amount = (count($pricing_array) > 0) ? $pricing_array->hourly_price * $no_of_hours : '0';
            $booking_type = "Hours";
            $total_time = $no_of_hours . ' Hours';

        } else if (!empty($no_of_days_req)) {
            $no_of_days = $no_of_days_req;
            $no_of_hours = '';
            $no_of_month = '';
            $booking_amount = (count($pricing_array) > 0) ? $pricing_array->daily_price : '0';
            $paid_amount = (count($pricing_array) > 0) ? $pricing_array->daily_price * $no_of_days : '0';
            $booking_type = "Days";
            $total_time = $no_of_days . ' Days';
        } else if (!empty($no_of_month_req)) {
            $no_of_month = $no_of_month_req;
            $no_of_hours = '';
            $no_of_days = '';
            $booking_amount = (count($pricing_array) > 0) ? $pricing_array->monthly_price : '0';
            $paid_amount = (count($pricing_array) > 0) ? $pricing_array->monthly_price * $no_of_month: '0';
            $booking_type = "Months";
            $total_time = $no_of_month . ' Months';
        }

        $sche_start_date_time = date("Y-m-d", strtotime($selected_date)).' '.$selected_time;
        $final_end_date = '';
        if($no_of_month_req >0){
            $time = strtotime($sche_start_date_time);
            $temp_month = "+".$no_of_month_req." month";
            $final_end_date = date("Y-m-d H:i:s", strtotime($temp_month, $time));
        }
        if($no_of_days_req >0){
            if(isset($final_end_date) && !empty($final_end_date)){
                $time = strtotime($final_end_date);
                $temp_day = "+".$no_of_days_req." day";
                $final_end_date = date("Y-m-d H:i:s", strtotime($temp_day, $time));
            }else{
                $time = strtotime($sche_start_date_time);
                $temp_day = "+".$no_of_days_req." day";
                $final_end_date = date("Y-m-d H:i:s", strtotime($temp_day, $time));
            }
        }
        if($no_of_hours_req >0) {
            if(isset($final_end_date) && !empty($final_end_date)){
                $time = strtotime($final_end_date);
                $temp_hours = '+' . $no_of_hours_req . ' hour';
                $final_end_date = date("Y-m-d H:i:s", strtotime($temp_hours, $time));
            }else{
                $time = strtotime($sche_start_date_time);
                $temp_hours = '+' . $no_of_hours_req . ' hour';
                $final_end_date = date("Y-m-d H:i:s", strtotime($temp_hours, $time));
            }
        }


        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {

            $cancellation_fee = (count($GeneralSettings) > 0) ? (int)$GeneralSettings->cancellation_fee : 0;
            $GetParkingspotDetails = ParkingSpot::where('id', $parking_spot_id)->where('status', 'Active')->where('verification_status', 'Yes')->first();

            $space_managment = DB::table('parking_spot_space_managment')->Where('parking_spot_id', $parking_spot_id)->get();

            if(count($space_managment) > 0){
                foreach ($space_managment as $space){

                    $check_avaibility_count = "SELECT count( * ) as count
                                                FROM `booking`
                                                WHERE `parking_spot_id` = $parking_spot_id
                                                AND `space_managment_id` = $space->id
                                                AND `booking_status` = 'Upcoming'
                                                AND `is_delete` = 'No'
                                                AND(
                                                    `booking_start_date_time` BETWEEN '$sche_start_date_time' AND '$final_end_date' 
                                                    OR `booking_end_date_time` BETWEEN '$sche_start_date_time' AND '$final_end_date'
                                                    OR `booking_start_date_time` < '$sche_start_date_time' and `booking_end_date_time` > '$final_end_date')
                                              
                                            ";
                    $check_avaibility_count = DB::Select($check_avaibility_count);

                    if($check_avaibility_count[0]->count == 0){
                        $space_managment_id = $space->id;
                        break;
                    }
                }
            }

            if(isset($space_managment_id) && !empty($space_managment_id)){
                if (count($GetParkingspotDetails) > 0) {
                    $startdatetime = $selected_date . ' ' . $selected_time;
                    $booking_id = DB::table('booking')->insertGetId([
                        'users_id' => $userID,
                        'parking_spot_id' => $parking_spot_id,
                        'space_managment_id' => $space_managment_id,
                        /*'entry_date_time' => $startdatetime,*/
                        'entry_date_time' => '0000-00-00 00:00:00',
                        'exit_date_time' => '0000-00-00 00:00:00',
                        'total_time' => $total_time,
                        'booking_status' => 'Upcoming',
                        'booking_amount' => $booking_amount,
                        'booking_date' => $selected_date,
                        'booking_time' => $selected_time,
                        'booking_start_date_time' => $sche_start_date_time,
                        'booking_end_date_time' => $final_end_date,
                        'booking_type' => $booking_type,
                        'generated_booking_id' => substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), -9),
                        'booking_hours' => $no_of_hours,
                        'booking_days' => $no_of_days,
                        'booking_month' => $no_of_month,
                        /*'cancellation_fee' => $cancellation_fee,*/
                        'cancellation_fee' => round($paid_amount_get * 25 /100 ),
                        'additional_credited_amount' => $additional_credited_amount,
                        'paid_amount' => $paid_amount_get,
                        'is_surcharge' => 'No',
                        'surcharge_amount' => 0,
                        'is_surcharge_paid' => '',
                        'surcharge_transaction_id' => '',
                        'booking_transaction_id' => $booking_transaction_id,
                        'status' => 'Active',
                        'created_date' => Helper::get_curr_datetime(),
                        'created_by' => $userID
                    ]);



                    $booking_data = DB::table('booking')->Where('id', $booking_id)->first();
                    if ($booking_id) {

                        if(isset($booking_data->booking_transaction_id) && !empty($booking_data->booking_transaction_id)){
                            /*Update payment status*/
                            $update_ph = DB::table('payment_history')
                                ->where('transaction_id', trim($booking_data->booking_transaction_id))
                                ->update([
                                    'status' => 'Complete'
                                ]);
                            /*Update transection history status*/
                            $ph = DB::table('payment_history')->where('transaction_id', trim($booking_data->booking_transaction_id))->first();
                            $update = DB::table('transaction_history')
                                ->where('transaction_id', $ph->id)
                                ->update([
                                    'is_transaction' => 'Yes'
                                ]);
                        }



                        $insert_admin = DB::table('admin_notification_management')->insert([
                            'users_id' => $userID,
                            'parking_spot_id' => $parking_spot_id,
                            'booking_id' => $booking_id,
                            'notification_for' => 'new_booking',
                            'is_show' => 'No',
                            'created_date' => Helper::get_curr_datetime(),
                            'created_by' => $userID
                        ]);

                        $update = DB::table('booking')
                            ->where('users_id', $userID)
                            ->update([
                                'is_additional_credited_amount' => 'No'
                            ]);

                        $notification = DB::table('notification')
                            ->join('user_notification', 'user_notification.id', '=', 'notification.push_notification_id')
                            ->Where('notification.users_id', $userID)
                            ->get();
                        if(isset($notification[1]) && $notification[1]->notification_mode == "ON"){
                            $deviceDetails = DeviceMaster::Where('users_id', $GetParkingspotDetails->users_id)->where('is_login', 'Yes')->first();
                            if(count($deviceDetails) > 0){
                                $data = [
                                    'notification' => $booking_id,
                                    'type' => 'new_booking',
                                    'message'=> 'Your booking added successfully.'
                                ];
                                /*$message_title = "New booking \r\nNew booking added on your parking spot";*/
                                $message_title = "New booking \r\nSomeone has paid to book your Parking Spot!";
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


                        $msg = 'Booking save SuccessFully.';
                        $code = 200;
                        return response()->json(['message' => $msg, 'code' => $code, 'data' => $booking_data], 200, [], JSON_NUMERIC_CHECK);
                    } else {
                        $msg = 'Booking does not save SuccessFully.';
                        $code = 101;
                        return response()->json(['message' => $msg, 'code' => $code]);
                    }
                } else {
                    $msg = 'Parking spot does not available.';
                    $code = 101;
                    return response()->json(['message' => $msg, 'code' => $code]);
                }
            }else{
                $msg = 'Parking space is not available on your time.';
                $code = 101;
                return response()->json(['message' => $msg, 'code' => $code]);
            }

        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }



    /*
     * Request Parameter :  [Apikey, userID, filter_type('all, upcoming, past'), timezone, page_limit, page_offset]
     * Method : POST
     * Request Api Url : "/api/bookingList"
     * Request Controller & Method : BookingController/bookingList
     * Success response : [ message : Success,  code : 200, data : Array of Booking List]
     * Error response : 
      1)[ message : Booking not available., code : 101]
      2)[ message : Unauthorised Call. , code : 101]
     */

    public function bookingList(Request $request) {
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

            $AndWhere = "";
            if ($filter_type == 'upcoming') {
                $AndWhere .= ' And booking_date >= STR_TO_DATE( "' . $CurrentDateTime . '", "%Y-%m-%d" ) And b.booking_status="Upcoming"';
            } else if ($filter_type == 'past') {
                $AndWhere .= ' And booking_date <= STR_TO_DATE( "' . $CurrentDateTime . '", "%Y-%m-%d" ) And b.booking_status="Cancelled" OR b.booking_status="Completed"';
            }

            $BookingArray = array();
            $BookingSql = "Select b.*, p.id as pid, p.address, p.postal_code, p.country_id, p.state_id, p.city_name, p.latitude, p.longitude, c.country_name, s.state_name "
                    . "from booking as b "
                    . "Left Join parking_spot as p ON b.parking_spot_id = p.id "
                    . "Left Join country as c ON p.country_id = c.id "
                    . "Left Join state as s ON p.state_id = s.id "
                    . "Where b.status='Active' And  b.is_delete='No'  And b.users_id = '" . $userID . "' 
                    $AndWhere ORDER BY id desc limit " . $start . " , " . $page_limit;
            //. "Where b.status='Active' And b.booking_status='Completed' And b.users_id = '" . $userID . "' $AndWhere ORDER BY id desc limit " . $start . " , " . $page_limit;
            $BookingArray = DB::Select($BookingSql);
            if (count($BookingArray) > 0) {
                foreach ($BookingArray as $single){

                    $single->id = !empty($single->id) ? (int) $single->id : 0;
                    $single->pid = !empty($single->pid) ? (int) $single->pid : 0;
                    $single->parking_spot_id = !empty($single->parking_spot_id) ? (int) $single->parking_spot_id : 0;
                    $single->users_id = !empty($single->users_id) ? (int) $single->users_id : 0;
                    $single->booking_amount = !empty($single->booking_amount) ? (int) $single->booking_amount : 0;
                    $single->cancellation_fee = !empty($single->cancellation_fee) ? (int) $single->cancellation_fee : 0;
                    $single->additional_credited_amount = !empty($single->additional_credited_amount) ? (int) $single->additional_credited_amount : 0;
                    $single->paid_amount = !empty($single->paid_amount) ? (int) $single->paid_amount : 0;
                    $single->surcharge_amount = !empty($single->surcharge_amount) ? (int) $single->surcharge_amount : 0;
                    $single->booking_transaction_id = !empty($single->booking_transaction_id) ? (string) $single->booking_transaction_id : '';
                    $single->postal_code = !empty($single->postal_code) ? (string) $single->postal_code : '';

                    $single->latitude = ($single->latitude != "" && $single->latitude != null && $single->latitude != 'null') ? (float)$single->latitude : 0.0;
                    $single->longitude = ($single->longitude != "" && $single->longitude != null && $single->longitude != 'null') ? (float)$single->longitude : 0.0;


                    $single->country_name = ($single->country_name != "" && $single->country_name != null && $single->country_name != 'null') ? (string)$single->country_name : "";
                    $single->state_name = ($single->state_name != "" && $single->state_name != null && $single->state_name != 'null') ? (string)$single->state_name : "";
                    $single->city_name = ($single->city_name != "" && $single->city_name != null && $single->city_name != 'null') ? (string)$single->city_name : "";
                    $single->country_id = ($single->country_id != "" && $single->country_id != null && $single->country_id != 'null') ? (int)$single->country_id : 0;
                    $single->state_id = ($single->state_id != "" && $single->state_id != null && $single->state_id != 'null') ? (int)$single->state_id : 0;
                    $single->address = ($single->address != "" && $single->address != null && $single->address != 'null') ? (string)$single->address : "";
                    $single->booking_end_datetime = "";

                    $time = strtotime($single->booking_time);
                    $time_after = $time + (15 * 60);
                    $time_befor = $time - (15 * 60);
                    $single->time_after_15_minutes = date("H:i:s", $time_after);
                    $single->time_befor_15_minutes = date("H:i:s", $time_befor);
                    /*$single->booking_hours_end = "";*/
                    $CurrentDateTime = date('Y-m-d H:i:s');

                    if($single->booking_type == "Months"){
                        $time = strtotime($single->booking_date.' '.$single->booking_time);
                        $temp_month = "+".$single->booking_month." month";
                        $final_end_date = date("Y-m-d H:i:s", strtotime($temp_month, $time));
                        $single->booking_end_datetime = $final_end_date;
                        if( strtotime($CurrentDateTime) > strtotime($final_end_date) ){
                            if(strtotime($single->entry_date_time) == "" && strtotime($single->exit_date_time) == ""){
                                $update = DB::table('booking')
                                    ->where('id', $single->id)
                                    ->update([
                                        'booking_status' => 'Cancelled',
                                        'cancelled_by' => 'User',
                                        'created_date' => Helper::get_curr_datetime(),
                                        'created_by' => $userID
                                    ]);
                                $single->booking_status = "Cancelled";
                                $single->cancelled_by = "User";
                            }
                        }
                    }elseif ($single->booking_type == "days"){
                        $time = strtotime($single->booking_date.' '.$single->booking_time);
                        $temp_day = "+".$single->booking_days." day";
                        $final_end_date = date("Y-m-d H:i:s", strtotime($temp_day, $time));
                        $single->booking_end_datetime = $final_end_date;
                        //echo $final_end_date; exit;
                        if( strtotime($CurrentDateTime) > strtotime($final_end_date) ){
                            if(strtotime($single->entry_date_time) == "" && strtotime($single->exit_date_time) == ""){
                                $update = DB::table('booking')
                                    ->where('id', $single->id)
                                    ->update([
                                        'booking_status' => 'Cancelled',
                                        'cancelled_by' => 'User',
                                        'created_date' => Helper::get_curr_datetime(),
                                        'created_by' => $userID
                                    ]);
                                $single->booking_status = "Cancelled";
                                $single->cancelled_by = "User";
                            }
                        }
                    }elseif ($single->booking_type == "Hours"){
                        $CurrentDate = date('Y-m-d');
                        $current_time = date("H:i:s", time());
                        $time2 = date("H:i:s", strtotime($single->booking_time));
                        $temp_hours = '+' . $single->booking_hours . ' hour';
                        $time2 = date('H:i:s', strtotime($time2 . $temp_hours));
                        $single->booking_end_datetime = $single->booking_date.' '.$time2;
                        if( strtotime($CurrentDate) >= strtotime($single->booking_date) ){
                            if(strtotime($CurrentDate) == strtotime($single->booking_date) && $current_time < $time2){

                            }else{
                                if(strtotime($single->entry_date_time) == "" && strtotime($single->exit_date_time) == ""){
                                    $update = DB::table('booking')
                                        ->where('id', $single->id)
                                        ->update([
                                            'booking_status' => 'Cancelled',
                                            'cancelled_by' => 'User',
                                            'created_date' => Helper::get_curr_datetime(),
                                            'created_by' => $userID
                                        ]);
                                    $single->booking_status = "Cancelled";
                                    $single->cancelled_by = "User";
                                }
                            }
                        }
                    }

                    $single->total_park_time = "";
                    if($single->booking_status == "Completed"){
                        $date1 = $single->entry_date_time;
                        $date2 = $single->exit_date_time;
                        $final_consume_time =  Helper::dateDiff($date1, $date2);
                        $single->total_park_time = $final_consume_time;
                    }
                }
                return response()->json(['message' => 'Success', 'code' => 200, 'MybookingList' => $BookingArray]);
            } else {
                return response()->json(['message' => 'Booking not available.', 'code' => 101]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    /*
     * Request Parameter :  [Apikey, booking_id, userID]
     * Method : POST
     * Request Api Url : "/api/fetchbookingdetails"
     * Request Controller & Method : BookingController/bookingDetails
     * Success response : [ message : Success,  code : 200, data : Array of Booking List]
     * Error response : 
      1)[ message : Booking not available., code : 101]
      2)[ message : Unauthorised Call. , code : 101]
     */

    public function bookingDetails(Request $request) {
        
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
                    . "Where b.status='Active' And b.is_delete='No' And b.id = '" . $booking_id . "'";
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
                    $booking->firstname = !empty($booking->firstname) ? (string) $booking->firstname : '';
                    $booking->lastname = !empty($booking->lastname) ? (string) $booking->lastname : '';
                    $booking->country_id = ($booking->country_id != "" && $booking->country_id != null && $booking->country_id != 'null') ? (int)$booking->country_id : 0;
                    $booking->state_id = ($booking->state_id != "" && $booking->state_id != null && $booking->state_id != 'null') ? (int)$booking->state_id : 0;
                    $booking->country_name = ($booking->country_name != "" && $booking->country_name != null && $booking->country_name != 'null') ? (string)$booking->country_name : "";
                    $booking->state_name = ($booking->state_name != "" && $booking->state_name != null && $booking->state_name != 'null') ? (string)$booking->state_name : "";

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
                        $exit_date_time = $booking->booking_date.' '.$booking->booking_time;
                        $exit_date = date("H:i:s", strtotime($exit_date_time));
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
                    if(strtotime($final_exit_time) < strtotime($CurrentDateTime)){
                        $date_diff = Helper::timeAgo(strtotime($final_exit_time),strtotime($CurrentDateTime));
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

                $ExistReview = DB::table('review')
                                ->where('booking_id', $booking_id)
                                ->Where('users_id', $userID)
                                ->first();
                if (count($ExistReview) > 0) {
                    $ExistReview->id = !empty($booking->id) ? (int) $booking->id : 0;
                    $ExistReview->booking_id = !empty($ExistReview->booking_id) ? (int) $ExistReview->booking_id : 0;
                    $ExistReview->users_id = !empty($ExistReview->users_id) ? (int) $ExistReview->users_id : 0;
                    $ExistReview->parking_spot_id = !empty($ExistReview->parking_spot_id) ? (int) $ExistReview->parking_spot_id : 0;
                    $ExistReview->rating = !empty($ExistReview->rating) ? (float) $ExistReview->rating : 0.0;

                    //$ExistReview->questions_answer = (!empty($ExistReview->questions_answer) ? unserialize($ExistReview->questions_answer) : array());
                    //$ExistReview->questions_answer = (!empty($ExistReview->questions_answer) ? json_decode($ExistReview->questions_answer) : array());
                    /*if(!empty($ExistReview->questions_answer)){
                            $tempQA = json_decode($ExistReview->questions_answer);
                            $temparray = [];
                            foreach($tempQA as $val){
                               foreach ($val as $key1=>$val1){
                                   array_push($temparray, array('answer' =>$val1));
                               }
                            }
                             //print_r($temparray);exit;
                            $ExistReview->questions_answer = $temparray;
                    }  else {
                        $ExistReview->questions_answer = array();
                    }*/
                }else{
                    $ExistReview = array();
                }
                $SubmitedReview =array();
                $sql="
                    SELECT 
                         p.users_id
                    FROM parking_spot as p
                    LEFT JOIN booking ON booking.parking_spot_id = p.id       
                    WHERE booking.id = '$booking_id'
                    AND booking.users_id = '$userID'     
                    ";
                 $results = DB::select( DB::raw($sql) );
                 if(isset($results) && !empty($results[0]->users_id)){
                    $SubmitedReview = DB::table('review')->where('booking_id', $booking_id)->Where('users_id', $results[0]->users_id)->first();
                     if (count($SubmitedReview) > 0) {
                         $SubmitedReview->id = !empty($SubmitedReview->id) ? (int) $SubmitedReview->id : 0;
                         $SubmitedReview->booking_id = !empty($SubmitedReview->booking_id) ? (int) $SubmitedReview->booking_id : 0;
                         $SubmitedReview->users_id = !empty($SubmitedReview->users_id) ? (int) $SubmitedReview->users_id : 0;
                         $SubmitedReview->parking_spot_id = !empty($SubmitedReview->parking_spot_id) ? (int) $SubmitedReview->parking_spot_id : 0;
                         $SubmitedReview->rating = !empty($SubmitedReview->rating) ? (float) $SubmitedReview->rating : 0.0;
                     }  else {
                         $SubmitedReview = array();
                     }
                }
                

                return response()->json(['message' => 'Success', 'code' => 200, 'BookingDetails' => $BookingArray, 'ReceivedReview' => $SubmitedReview, 'SubmitedReview' =>  $ExistReview]);
            } else {
                return response()->json(['message' => 'Booking not available.', 'code' => 101]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    /*
     * Request Parameter :  [Apikey, booking_id]
     * Method : POST
     * Request Api Url : "/api/deletebooking"
     * Request Controller & Method : BookingController/deletebooking
     * Success response : [ message : Booking deleted successfully.,  code : 200]
     * Error response : 
      1)[ message : Booking not deleted successfully., code : 101]
      2)[ message : Unauthorised Call. , code : 101]
     */

    public function deletebooking(Request $request) {
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $booking_id = $request->booking_id;
            $del_parking_spot = DB::table('booking')
                ->where('id', $booking_id)
                ->update([
                    'is_delete' => 'Yes'
                ]);
            //$del_parking_spot = DB::delete('DELETE FROM `booking` WHERE id IN (' . $booking_id . ')');
            if ($del_parking_spot) {
                $msg = 'Booking deleted successfully.';
                $code = 200;
                return response()->json(['message' => $msg, 'code' => $code]);
            } else {
                $msg = 'Booking not deleted successfully.';
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
     * Request Api Url : "/api/canclebookingbyuser"
     * Request Controller & Method : BookingController/canclebookingbyuser
     * Success response : [ message : Booking cancel by user successfully.,  code : 200]
     * Error response : 
      1)[ message : Booking cancel by user not successfully., code : 101]
      2)[ message : Unauthorised Call. , code : 101]
     */

    public function canclebookingbyuser(Request $request) {
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $booking_id = $request->booking_id;

            $booking_update_id = DB::table('booking')->where('id', $booking_id)->update([
                'cancelled_by' => 'User',
                'booking_status' => "Cancelled"
            ]);
            if ($booking_update_id) {
                $get_host_by_booking_id = DB::table('booking')
                                            ->leftJoin('parking_spot', 'parking_spot.id', '=', 'booking.parking_spot_id')
                                            ->select('parking_spot.users_id')
                                            ->where('booking.id', $booking_id)
                                            ->first();

                if($get_host_by_booking_id){
                    $notification = DB::table('notification')
                        ->join('user_notification', 'user_notification.id', '=', 'notification.push_notification_id')
                        ->Where('notification.users_id', $get_host_by_booking_id->users_id)
                        ->get();
                    /*echo '<pre>';
                    print_r($notification);
                    echo '</pre>';
                    exit;*/
                    if(count($notification) > 0 && $notification[4]->notification_mode == "ON") {
                        $deviceDetails = DeviceMaster::Where('users_id', $get_host_by_booking_id->users_id)->where('is_login', 'Yes')->first();
                        if (count($deviceDetails) > 0) {
                            $data = [
                                'notification' => $booking_id,
                                'type' => 'cancel booking',
                                'message' => 'Booking has benn cancle by user'
                            ];
                            $message_title = 'Cancle Booking';

                            DeviceMaster::sendPushNotification($deviceDetails['gcm_arn'], $message_title, $data);

                        }
                    }
                }
                $msg = 'Booking cancel by user successfully.';
                $code = 200;
                return response()->json(['message' => $msg, 'code' => $code]);
            } else {
                $msg = 'Booking cancel by user not successfully.';
                $code = 101;
                return response()->json(['message' => $msg, 'code' => $code]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }
    
    /*
     * Request Parameter :  [Apikey, booking_id,parking_spot_id]
     * Method : POST
     * Request Api Url : "/api/canclebooking"
     * Request Controller & Method : BookingController/canclebookingbyuser
     * Success response : [ message : Booking cancel by user successfully.,  code : 200]
     * Error response : 
      1)[ message : Booking cancel by user not successfully., code : 101]
      2)[ message : Unauthorised Call. , code : 101]
     */
    
    public function canclebooking(Request $request) {
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $booking_id = $request->booking_id;
            $parking_spot_id = $request->parking_spot_id;
            $userID = $request->userID;
            $timezone = $request->timezone;
            date_default_timezone_set($timezone);
            $current_time =date("Y-m-d H:i:s",time());

            $BookingSql = "Select 
                        b.users_id as b_user_id, 
                        b.booking_date, 
                        b.booking_time, 
                        p.users_id as p_user_id,
                        b.paid_amount,
                        b.cancellation_fee
                    from booking as b
                    Left Join parking_spot as p ON b.parking_spot_id = p.id 
                    Where b.status='Active' And b.id = $booking_id  And b.parking_spot_id = $parking_spot_id ";
            //. "Where b.status='Active' And b.booking_status='Completed' And b.id = '" . $booking_id . "'";
            $BookingArray = DB::Select($BookingSql);

            
            if(isset($BookingArray) && !empty($BookingArray)){
                $final_exit_time = date("Y-m-d H:i:s",strtotime ( '-4 hours' , strtotime ( $BookingArray[0]->booking_date.' '.$BookingArray[0]->booking_time ) )) ;
                if(strtotime($final_exit_time) >= strtotime($current_time)){
                    $cancelled_by = '';
                    /*if($BookingArray[0]->b_user_id == $BookingArray[0]->p_user_id){
                        $cancelled_by = "Host";
                    }  else {
                        $cancelled_by = "User";
                    }*/

                    if($userID == $BookingArray[0]->p_user_id){
                        $cancelled_by = "Host";
                    }  else {
                        $cancelled_by = "User";
                    }


                    $booking_update_id = DB::table('booking')->where('id', $booking_id)->update([
                        'cancelled_by' => $cancelled_by,
                        'booking_status' => "Cancelled",
                        'cancellation_date' => date("Y-m-d H:i:s",time())
                    ]);

                    if ($booking_update_id) {
                        if($cancelled_by == "User"){
                            $refund_amount = $BookingArray[0]->paid_amount - $BookingArray[0]->cancellation_fee;
                        }else{
                            $refund_amount = $BookingArray[0]->paid_amount;
                        }

                        $booking_refund = DB::table('booking_refund')->insertGetId([
                            'booking_id' => $booking_id,
                            'users_id' => $BookingArray[0]->b_user_id,
                            'bank_details_id' => 0,
                            'refund_amount' => $refund_amount,
                            'refund_amount_status' => 'Pending',
                            'upload_bank_receipt' => '',
                            'upload_bank_receipt_status' => 'Pending',
                            'booking_refund_for' => 'Booking_cancel',
                            'created_date' => Helper::get_curr_datetime(),
                            'updated_date' => '',
                            'created_by' => $userID,
                            'updated_by' => '',
                        ]);

                        $refund_entry_for_renter = DB::table('transaction_history')->insertGetId([
                            'users_id' => $BookingArray[0]->b_user_id,
                            'transaction_id' => $booking_refund,
                            'parking_spot_id' => $parking_spot_id,
                            'transaction_for' => 'Refund_Booking',
                            'amount' => $refund_amount,
                            'created_date' => Helper::get_curr_datetime(),
                            'created_by' => $userID
                        ]);
                        $refund_entry_for_host = DB::table('transaction_history')->insertGetId([
                            'users_id' => $BookingArray[0]->p_user_id,
                            'transaction_id' => $booking_refund,
                            'parking_spot_id' => $parking_spot_id,
                            'transaction_for' => 'Refund_Reservation',
                            'amount' => $refund_amount,
                            'created_date' => Helper::get_curr_datetime(),
                            'created_by' => $userID
                        ]);

                        $insert_admin = DB::table('admin_notification_management')->insert([
                            'users_id' => $userID,
                            'parking_spot_id' => $parking_spot_id,
                            'booking_id' => $booking_id,
                            'notification_for' => 'cancelled_booking',
                            'is_show' => 'No',
                            'created_date' => Helper::get_curr_datetime(),
                            'created_by' => $userID
                        ]);


                        if($userID == $BookingArray[0]->p_user_id) {
                            $notification = DB::table('notification')
                                ->join('user_notification', 'user_notification.id', '=', 'notification.push_notification_id')
                                ->Where('notification.users_id', $BookingArray[0]->b_user_id)
                                ->get();
                            if(count($notification) > 0 && $notification[4]->notification_mode == "ON") {
                                $deviceDetails = DeviceMaster::Where('users_id', $BookingArray[0]->b_user_id)->where('is_login', 'Yes')->first();
                                if (count($deviceDetails) > 0) {
                                    $data = [
                                        'notification' => $booking_id,
                                        'type' => 'cancelled_booking',
                                        'message' => 'Booking has been cancel by '.$cancelled_by
                                    ];
                                    //$message_title = "Cancel Booking \r\nBooking has been cancel by $cancelled_by";
                                    $message_title = "Cancel Booking \r\nReservation has been cancelled by the $cancelled_by";

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
                        }else{
                            /*$get_host_by_booking_id = DB::table('booking')
                                ->leftJoin('parking_spot', 'parking_spot.id', '=', 'booking.parking_spot_id')
                                ->select('parking_spot.users_id')
                                ->where('booking.id', $booking_id)
                                ->first();*/
                            $notification = DB::table('notification')
                                ->join('user_notification', 'user_notification.id', '=', 'notification.push_notification_id')
                                ->Where('notification.users_id', $BookingArray[0]->p_user_id)
                                ->get();
                            if(count($notification) > 0 && $notification[4]->notification_mode == "ON") {
                                $deviceDetails = DeviceMaster::Where('users_id', $BookingArray[0]->p_user_id)->where('is_login', 'Yes')->first();
                                if (count($deviceDetails) > 0) {
                                    $data = [
                                        'notification' => $booking_id,
                                        'type' => 'cancelled_booking',
                                        'message' => 'Booking has been cancel by '.$cancelled_by
                                    ];
                                    $message_title = "Cancel Booking \r\nBooking has been cancel by $cancelled_by";

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

                        $msg = 'Booking cancel by '.$cancelled_by.' successfully.';
                        $code = 200;
                        return response()->json(['message' => $msg, 'code' => $code]);
                    } else {
                        $msg = 'Booking cancel by '.$cancelled_by.' not successfully.';
                        $code = 101;
                        return response()->json(['message' => $msg, 'code' => $code]);
                    }
                }else{
                    $msg = 'Now you can not able to cancel booking.';
                    $code = 101;
                    return response()->json(['message' => $msg, 'code' => $code]);
                }

            }
            
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    /*
     * Request Parameter :  [Apikey, booking_id, entry_date_time]
     * Method : POST
     * Request Api Url : "/api/entryinbooking"
     * Request Controller & Method : BookingController/entryinbooking
     * Success response : [ message : Entry by user successfully.,  code : 200]
     * Error response :
      1)[ message : Entry by user not successfully., code : 101]
      2)[ message : Unauthorised Call. , code : 101]
     */
    public function entryinbooking(Request $request){
        $Apikey = $request->Apikey;
        date_default_timezone_set($request->timezone);
        if ($Apikey == APIKEY && isset($request->booking_id)) {
            $booking_entry = DB::table('booking')->where('id', $request->booking_id)->update([
                'entry_date_time' => date('Y-m-d H:i:s',strtotime($request->entry_date_time))
            ]);
            if ($booking_entry) {
                $msg = 'Entry by user successfully.';
                $code = 200;
                return response()->json(['message' => $msg, 'code' => $code]);
            } else {
                $msg = 'Entry by user not successfully.';
                $code = 101;
                return response()->json(['message' => $msg, 'code' => $code]);
            }
        }else{
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    /*
     * Request Parameter :  [Apikey, booking_id,exit_date_time, timezone]
     * Method : POST
     * Request Api Url : "/api/exitfrombooking"
     * Request Controller & Method : BookingController/exitfrombooking
     * Success response : [ message : Exit by user successfully.,  code : 200]
     * Error response :
      1)[ message : Exit by user not successfully., code : 101]
      2)[ message : Unauthorised Call. , code : 101]
     */

    public function exitfrombooking(Request $request){
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY && isset($request->booking_id)) {
            date_default_timezone_set($request->timezone);
            $booking_exit = DB::table('booking')->where('id', $request->booking_id)->update([
                'exit_date_time' => $request->exit_date_time,
                'booking_status' => 'Completed'
            ]);
            //$booking_exit = 1;
            if ($booking_exit) {
                $surcharge_amount = DB::table('surcharge_amount')->first();
                $booking = DB::table('booking')->where('id',$request->booking_id)->first();



                /*$date_diff = Helper::timeAgo(strtotime($booking->exit_date_time),strtotime(date('Y-m-d H:i:s')));

                $surcharge = 0;
                if(strtotime($booking->exit_date_time) < strtotime(date('Y-m-d H:i:s'))){
                    if(isset($date_diff) && !empty($date_diff)){
                        if($date_diff[1] == "minutes" && $date_diff[0] < 30){
                            $surcharge = $surcharge_amount->amount_before_half_min;
                        }elseif ($date_diff[1] == "minutes" && $date_diff[0] > 30) {
                            $surcharge = $surcharge_amount->amount_after_half_min;
                        }elseif ($date_diff[1] == "hours") {
                            $surcharge =  $date_diff[0] * $surcharge_amount->amount_per_hour;
                        }
                    }
                }
                $booking->surcharge = $surcharge;*/
                $CurrentDateTime = date('Y-m-d H:i:s');
                $surcharge = 0;
                if ($booking->booking_type == "Hours"){
                    $exit_date_time = $booking->booking_date.' '.$booking->booking_time;
                    $exit_date = date("H:i:s", strtotime($exit_date_time));
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
                if(strtotime($final_exit_time) < strtotime($CurrentDateTime)){
                    $date_diff = Helper::timeAgo(strtotime($final_exit_time),strtotime($CurrentDateTime));
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
                $booking->surcharge_amount = (int)$surcharge;
                if($surcharge > 0){
                    $booking_surcharge = DB::table('booking')->where('id', $request->booking_id)->update([
                        'is_surcharge' => "Yes",
                        'surcharge_amount' => $surcharge,
                        'is_surcharge_paid' => "No"
                    ]);

                    $booking_get = DB::table('booking')
                        ->Where('id', $request->booking_id)
                        ->first();

                    $insert_admin = DB::table('admin_notification_management')->insert([
                        'users_id' => $booking_get->users_id,
                        'parking_spot_id' => $booking_get->parking_spot_id,
                        'booking_id' => $booking_get->id,
                        'notification_for' => 'surcharge_amount',
                        'is_show' => 'No',
                        'created_date' => Helper::get_curr_datetime(),
                        'created_by' => $booking_get->users_id
                    ]);

                    $check_next_booking = "SELECT *
                                                FROM `booking`
                                                WHERE `parking_spot_id` = $booking->parking_spot_id
                                                AND `space_managment_id` = $booking->space_managment_id
                                                AND `booking_status` = 'Upcoming'
                                                AND `booking_start_date_time` < '$request->exit_date_time' 
                                                AND `id` != $booking->id
                                                LIMIT 1
                                            ";
                    $check_next_booking = DB::Select($check_next_booking);

                    if(isset($check_next_booking) && !empty($check_next_booking)){
                        $booking_descount = DB::table('booking')->where('id', $check_next_booking[0]->id)->update([
                            'is_additional_credited_amount' => 'Yes'
                        ]);
                        $notification = DB::table('notification')
                            ->join('user_notification', 'user_notification.id', '=', 'notification.push_notification_id')
                            ->Where('notification.users_id', $check_next_booking[0]->users_id)
                            ->get();
                        if(count($notification) > 0 && $notification[10]->notification_mode == "ON"){
                            $deviceDetails = DeviceMaster::Where('users_id', $check_next_booking[0]->users_id)->where('is_login', 'Yes')->first();
                            if(count($deviceDetails) > 0){
                                $data = [
                                    'notification' => $check_next_booking[0]->users_id,
                                    'type' => 'discount_amount_applied',
                                    'message'=> "You can get $surcharge discount for inconvenience on your next booking"
                                ];
                                //$message_title = "Discount amount \r\nYou can get $surcharge discount for inconvenience on your next booking";
                                $message_title = "Discount amount \r\nWe apologize and you will be discounted on your next reservation for the inconvenience. ";

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


                    $notification = DB::table('notification')
                        ->join('user_notification', 'user_notification.id', '=', 'notification.push_notification_id')
                        ->Where('notification.users_id', $booking->users_id)
                        ->get();
                    if(count($notification) > 0 && $notification[6]->notification_mode == "ON"){
                        $deviceDetails = DeviceMaster::Where('users_id', $booking->users_id)->where('is_login', 'Yes')->first();
                        if(count($deviceDetails) > 0){
                            $data = [
                                'notification' => $booking->id,
                                'type' => 'surchage_amount_applied',
                                'message'=> 'You go over the allotted time along with the '.$surcharge.'$ surcharge/penalty amount to be paid'
                            ];
                            //$message_title = "Surcharge/Penalty amount \r\nYou go over the allotted time along with the $surcharge$ surcharge/penalty amount to be paid";
                            $message_title = "Surcharge/Penalty amount \r\nYou have gone past your paid parking time. You will be charged a surcharge until you exit your vehicle from the Spot or book more time.";

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
                $booking = DB::table('booking')->where('id',$request->booking_id)->first();
                if(isset($booking)&&!empty($booking)){
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

                }
                $msg = 'Exit by user successfully.';
                $code = 200;
                return response()->json(['message' => $msg, 'code' => $code, 'booking_details' => $booking]);
            } else {
                $msg = 'Exit by user not successfully.';
                $code = 101;
                return response()->json(['message' => $msg, 'code' => $code]);
            }
        }else{
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }
}

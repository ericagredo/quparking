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
use Mail;

class ReviewController extends Controller {
    /*
     * Request Parameter :  [Apikey]
     * Method : POST
     * Request Api Url : "/api/reviewquestionlist"
     * Request Controller & Method : ReviewController/reviewquestionlist
     * Success response : [ message : Array Of Review Questions.,  code : 200]
     * Error response : 
      1)[ message : Review does not available., code : 101]
      2)[ message : Unauthorised Call. , code : 101]
     */

    public function reviewquestionlist(Request $request) {
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $review_questionnaires_sql = 'SELECT * 
                                          FROM `review_questionnaires`
                                          WHERE status = "Active"';
            $review_questionnaires_details = DB::select($review_questionnaires_sql);
            if (count($review_questionnaires_details) > 0) {
                $review_questionnaires_Array = array();
                $i = 0;
                foreach ($review_questionnaires_details as $review_questionnaires) {
                    $review_questionnaires_Array[$i] = $review_questionnaires;
                    $review_questionnaires_Array[$i]->id = !empty($review_questionnaires->id) ? (int) $review_questionnaires->id : 0;
                    $review_questionnaires_Array[$i]->questionnaires_title = !empty($review_questionnaires->questionnaires_title) ? (string) $review_questionnaires->questionnaires_title : '';
                    $i++;
                }

                $msg = 'success';
                $code = 200;
                return response()->json(['message' => $msg, 'code' => $code, 'ReviewQuestionsList' => $review_questionnaires_Array]);
            } else {
                $msg = 'Review Questions does not available.';
                $code = 101;
                return response()->json(['message' => $msg, 'code' => $code]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    /*
     * Request Parameter :  [Apikey, userID, booking_id, rating, feedback_description, questions_answer( [{"1":"No"},{"2":"No"},{"3":"Yes"}]) ]
     * Method : POST
     * Request Api Url : "/api/usersreview"
     * Request Controller & Method : ReviewController/addusersreview
     * Success response : [ message : Array Of Review Questions.,  code : 200]
     * Error response : 
      1)[ message : Review does not available., code : 101]
      2)[ message : Unauthorised Call. , code : 101]
     */

    public function addusersreview(Request $request) {
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $userID = $request->userID;
            $booking_id = $request->booking_id;
            $rating = $request->rating;
            $description = ($rating < 5) ? $request->feedback_description : '';
            $questions_answer = $request->questions_answer;

            $ExistReview = DB::table('review')->Where('users_id', $userID)->where('booking_id', $booking_id)->first();
            if (count($ExistReview) > 0) {
                //$questions_ans = serialize(json_decode($questions_answer));
                $questions_ans = $questions_answer;
                $GetParkingSpotReview = DB::table('booking')->where('id', $booking_id)->first();
                $parking_spot_id = (count($GetParkingSpotReview) > 0) ? $GetParkingSpotReview->parking_spot_id : 0;
                $users = DB::table('review')->where('id', $ExistReview->id)->update([
                    'users_id' => $userID,
                    'booking_id' => $booking_id,
                    'parking_spot_id' => $parking_spot_id,
                    'rating' => $rating,
                    'feedback_description' => $description,
                    'questions_answer' => $questions_ans,
                    'updated_date' => Helper::get_curr_datetime(),
                    'updated_by' => $userID
                ]);

                return response()->json(['message' => 'Review update SuccessFully.', 'code' => 200]);
            } else {
                //$questions_ans = serialize(json_decode($questions_answer));
                $questions_ans = $questions_answer;
                $GetParkingSpotReview = DB::table('booking')->where('id', $booking_id)->first();
                $parking_spot_id = (count($GetParkingSpotReview) > 0) ? $GetParkingSpotReview->parking_spot_id : 0;
                $Review = DB::table('review')->insertGetId(
                        [
                            'users_id' => $userID,
                            'booking_id' => $booking_id,
                            'parking_spot_id' => $parking_spot_id,
                            'rating' => $rating,
                            'feedback_description' => $description,
                            'questions_answer' => $questions_ans,
                            'created_date' => Helper::get_curr_datetime(),
                            'created_by' => $userID
                        ]
                );
                return response()->json(['message' => 'Review added SuccessFully.', 'code' => 200]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    /*
     * Request Parameter :  [Apikey, userID, booking_id]
     * Method : POST
     * Request Api Url : "/api/fetchreviewofuser"
     * Request Controller & Method : ReviewController/fetchreviewofuser
     * Success response : [ message : Array Of Review.,  code : 200]
     * Error response : 
      1)[ message : Review does not available., code : 101]
      2)[ message : Unauthorised Call. , code : 101]
     */

    public function fetchreviewofuser(Request $request) {
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $Get_review_for = $request->Get_review_for;
            $userID = $request->userID;
            $booking_id = $request->booking_id;

            if($Get_review_for == "Booking"){
                $ExistReview = DB::table('review')->Where('users_id', $userID)->where('booking_id', $booking_id)->first();
                if (count($ExistReview) > 0) {

                    if(!empty($ExistReview->questions_answer)){
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
                        $ExistReview->questions_answer = (object) array();
                    }
                    //$ExistReview->questions_answer = (!empty($ExistReview->questions_answer) ? unserialize($ExistReview->questions_answer) : array()) ;

                }  else {
                    $ExistReview = (object) array();
                }
                $sql="
                    SELECT 
                         p.users_id
                    FROM parking_spot as p
                    LEFT JOIN booking ON booking.parking_spot_id = p.id       
                    WHERE booking.id = '$booking_id'
                    AND booking.users_id = '$userID'     
                    ";
                $results = DB::select( DB::raw($sql) );
                $SubmitedReview = array();
                if(isset($results) && !empty($results[0]->users_id)){
                    $SubmitedReview = DB::table('review')->Where('users_id', $results[0]->users_id)->where('booking_id', $booking_id)->first();
                }
                if (count($SubmitedReview) > 0) {

                    if(!empty($SubmitedReview->questions_answer)){
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
                        $SubmitedReview->questions_answer = (object) array();
                    }
                    //$ExistReview->questions_answer = (!empty($ExistReview->questions_answer) ? unserialize($ExistReview->questions_answer) : array()) ;

                }  else {
                    $SubmitedReview = (object) array();
                }
            }elseif ($Get_review_for == "Reservation"){
                $BookingArray = array();
                $BookingSql = "Select b.*,u.id as uid, u.firstname, u.lastname , u.contact_number, u.profile_image, u.latitude, u.longitude,  p.id as pid, p.address, p.postal_code, p.country_id, p.state_id, p.city_name, c.country_name, s.state_name "
                    . "from booking as b "
                    . "Left Join parking_spot as p ON b.parking_spot_id = p.id "
                    . "Left Join users as u ON p.users_id = u.id "
                    . "Left Join country as c ON p.country_id = c.id "
                    . "Left Join state as s ON p.state_id = s.id "
                    . "Where b.status='Active' And b.id = '" . $booking_id . "'";
                //. "Where b.status='Active' And b.booking_status='Completed' And b.id = '" . $booking_id . "'";
                $BookingArray = DB::Select($BookingSql);

                $ExistReview = DB::table('review')->where('booking_id', $booking_id)->Where('users_id', $userID)->first();
                if (count($ExistReview) > 0) {
                    $tempQA = json_decode($ExistReview->questions_answer);
                    $temparray = [];
                    foreach($tempQA as $val){
                        foreach ($val as $key1=>$val1){
                            array_push($temparray, array('answer' =>$val1));
                        }
                    }
                    //print_r($temparray);exit;
                    $ExistReview->questions_answer = $temparray;
                }else{
                    $ExistReview = (object) array();
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
                    $SubmitedReview = (object) array();
                }
            }

            if(empty($ExistReview) && empty($SubmitedReview)){
                return response()->json(['message' => 'Review not available for that user.', 'code' => 101]);
            }  else {
                return response()->json(['message' => 'success', 'code' => 200, 'ReviewDetails' =>$SubmitedReview ,'SubmitedReviewDetails' => $ExistReview], 200, [], JSON_NUMERIC_CHECK);
            }     
            
        } else {
           return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

}

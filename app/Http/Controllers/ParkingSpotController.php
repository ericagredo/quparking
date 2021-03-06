<?php

namespace App\Http\Controllers;

use function Composer\Autoload\includeFile;
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

class ParkingSpotController extends Controller {
    /*
     * Request Parameter :  [Apikey, userID, address, postal_code, country_id, state_id, city_name, access_instruction, number_of_space_spot ,latitude, longitude, location, uploaded_images[0], uploaded_images[1], uploaded_images[2], uploaded_images[3], uploaded_images[4]]
     * Method : POST
     * Request Api Url : "/api/registerparkingspot"
     * Request Controller & Method : ParkingSpotController/RegisterParkingSpot
     * Success response : [ message : Parking Spot save SuccessFully.,  code : 200, data : Array of Parking Spot Details]
     * Error response : 
      1)[ message : Parking Spot does not save SuccessFully., code : 101]
     * 2)[ message : Unauthorised Call. , code : 101]
     */

    public function registerParkingSpot(Request $request) {
        //print_r($_POST);die;
        /*$parking_spot_images_id = [];
        if ($request->hasFile('uploaded_images')) {
                $files = $request->file('uploaded_images');
                $i = 0;
                foreach ($files as $file) {
                    if ($i <= 4) {
                        //$last_inserted_id = $parking_spot_id;
                        $filename = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();
                        $rendom = rand();
                        $picture = date('His').$rendom. $filename;
                        $destinationPath = public_path('uploads/parkingspot_images');
                        $file->move($destinationPath, $picture);
                        
                        $name = asset('uploads/parkingspot_images/' . $picture);

                        array_push($parking_spot_images_id, $name);
                    }
                    $i++;
                }
            }
        if (!empty($parking_spot_images_id)) {
                $files = $request->file('uploaded_images');
                return response()->json(['response' => $parking_spot_images_id]);
        }  else {
                return response()->json(['response' => "Image not found"]);
        }
        die;*/
        $address = $request->address;
        $postal_code = $request->postal_code;
        $country_id = $request->country_id;
        $state_id = $request->state_id;
        $city_name = $request->city_name;
        $description = $request->access_instruction;
        $number_of_space_spot = $request->number_of_space_spot;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $location = $request->location;
        $userID = $request->userID;


        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            if(!empty($latitude) && !empty($longitude) && $latitude != "" && $longitude != ""){
                $parking_spot_id = DB::table('parking_spot')->insertGetId([
                    'users_id' => $userID,
                    'address' => $address,
                    'postal_code' => $postal_code,
                    'country_id' => $country_id,
                    'state_id' => $state_id,
                    'city_name' => $city_name,
                    'description' => $description,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'location' => $location,
                    'number_of_space_spot' => $number_of_space_spot,
                    'renting_type' => 'Instant Rent',
                    'verification_status' => 'No',
                    'users_verification_status' => 'No',
                    'parking_spot_search_count' => 0,
                    'status' => 'Inactive',
                    'created_date' => Helper::get_curr_datetime(),
                    'created_by' => $userID
                ]);

                if ($request->hasFile('uploaded_images')) {
                    $files = $request->file('uploaded_images');
                    $i = 0;
                    foreach ($files as $file) {
                        if ($i <= 4) {
                            $last_inserted_id = $parking_spot_id;
                            $filename = $file->getClientOriginalName();
                            $extension = $file->getClientOriginalExtension();
                            $rendom = rand();
                            $picture = date('His'). $rendom . $filename;
                            $destinationPath = public_path('uploads/parkingspot_images');
                            $file->move($destinationPath, $picture);

                            $parking_spot_images_id = DB::table('parking_spot_images')->insert(
                                [
                                    'parking_spot_id' => $last_inserted_id,
                                    'uploaded_image' => $picture,
                                    'created_date' => Helper::get_curr_datetime(),
                                    'created_by' => $userID
                                ]
                            );
                        }
                        $i++;
                    }
                }

                if ($parking_spot_id) {

                    $insert_admin = DB::table('admin_notification_management')->insert([
                            'users_id' => $userID,
                            'parking_spot_id' => $parking_spot_id,
                            'booking_id' => 0,
                            'notification_for' => 'new_spot',
                            'is_show' => 'No',
                            'created_date' => Helper::get_curr_datetime(),
                            'created_by' => $userID
                        ]);

                    for($i = 0; $i < $number_of_space_spot ; ++$i) {
                        $insert = DB::table('parking_spot_space_managment')->insert(
                            [
                                'parking_spot_id' => $parking_spot_id,
                                'space_number' => $i + 1,
                                'created_by' => $userID
                            ]
                        );
                    }

                    $Parking_spot_details = DB::table('parking_spot')->where('id', $parking_spot_id)->first();
                    /*                 * ************ fetch parking_spot images ************* */
                    $Parking_spot_images_details = DB::table('parking_spot_images')->where('parking_spot_id', $parking_spot_id)->get();
                    /*                 * ************ fetch parking_spot images ************* */
                    $Parking_spot_details->id = !empty($Parking_spot_details->id) ? (int) $Parking_spot_details->id : 0;
                    $Parking_spot_details->users_id = !empty($Parking_spot_details->users_id) ? (int) $Parking_spot_details->users_id : 0;
                    $Parking_spot_details->number_of_space_spot = !empty($Parking_spot_details->number_of_space_spot) ? (int) $Parking_spot_details->number_of_space_spot : 0;
                    $Parking_spot_details->no_of_hours = !empty($Parking_spot_details->no_of_hours) ? (int) $Parking_spot_details->no_of_hours : 0;
                    $Parking_spot_details->no_of_days = !empty($Parking_spot_details->no_of_days) ? (int) $Parking_spot_details->no_of_days : 0;
                    $Parking_spot_details->no_of_months = !empty($Parking_spot_details->no_of_months) ? (int) $Parking_spot_details->no_of_months : 0;

                    $Parking_spot_details->latitude = ($Parking_spot_details->latitude != "" && $Parking_spot_details->latitude != null && $Parking_spot_details->latitude != 'null') ? (float)$Parking_spot_details->latitude : 0.0;
                    $Parking_spot_details->longitude = ($Parking_spot_details->longitude != "" && $Parking_spot_details->longitude != null && $Parking_spot_details->longitude != 'null') ? (float)$Parking_spot_details->longitude : 0.0;

                    $Parking_spot_details->country_id = !empty($Parking_spot_details->country_id) ? (int) $Parking_spot_details->country_id : 0;
                    $Parking_spot_details->state_id = !empty($Parking_spot_details->state_id) ? (int) $Parking_spot_details->state_id : 0;

                    $Parking_spot_details->parking_spot_images = (count($Parking_spot_images_details) > 0) ? $Parking_spot_images_details : array();

                    $is_image = FALSE;
                    if (count($Parking_spot_images_details) > 0) {
                        $m = 0;
                        foreach ($Parking_spot_images_details as $uploaded_image) {
                            $Parking_spot_details->parking_spot_images[$m]->uploaded_image = !empty($uploaded_image->uploaded_image) ? asset('uploads/parkingspot_images/' . $uploaded_image->uploaded_image) : '';
                            $m++;
                        }
                        $Parking_spot_details->is_image = TRUE;
                    }
                    $msg = 'Parking Spot save SuccessFully.';
                    $code = 200;
                    return response()->json(['message' => $msg, 'code' => $code, 'data' => $Parking_spot_details]);
                } else {
                    $msg = 'Parking Spot does not save SuccessFully.';
                    $code = 101;
                    return response()->json(['message' => $msg, 'code' => $code]);
                }
            }else{
                $msg = 'Latitude and Longitude are required for register parking spot.';
                $code = 101;
                return response()->json(['message' => $msg, 'code' => $code]);
            }

        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    /*
     * Request Parameter : [Apikey, parking_spot_id, verification_code]
     * Method : POST
     * Request Api Url : "/api/changeverificationcode"
     * Request Controller & Method : ParkingSpotController/changeverificationcode
     * Success response : [ message : Parking Spot verification status change SuccessFully.,  code : 200]
     * Error response : 
      1)[ message : Parking Spot verification status does not save SuccessFully., code : 101]
     * 2)[ message : Unauthorised Call. , code : 101]
     */

    public function changeverificationcode(Request $request) {
        $id = $request->parking_spot_id;
        $verification_code = $request->verification_code;
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $Update_status = DB::update('update parking_spot set users_verification_status = "Yes",verification_code = ""  Where id =' . $id . ' And verification_status = "Yes" And verification_code = "' . $verification_code . '"');
            if ($Update_status) {
                $msg = 'Parking Spot verification status change SuccessFully.';
                $code = 200;
                return response()->json(['message' => $msg, 'code' => $code]);
            } else {
                $msg = 'Parking Spot verification status does not change SuccessFully.';
                return response()->json(['message' => $msg, 'code' => 101]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    /*
     * Request Parameter :  [Apikey, userID]
     * Method : POST
     * Request Api Url : "/api/parkingspotlist"
     * Request Controller & Method : ParkingSpotController/parkingSpotList
     * Success response : [ message : Array Of Parking Spot Details Using UserID.,  code : 200]
     * Error response : 
      1)[ message : Parking spot does not available., code : 101]
     * 2)[ message : Unauthorised Call. , code : 101]
     */

    public function parkingSpotList(Request $request) {
        $userID = $request->userID;
        $page_limit = $request->page_limit;
        $page_offset = $request->page_offset;

        if ($page_offset) {
            $page_offset1 = $page_offset + 1;
            $start = ($page_offset1 - 1) * $page_limit;
        } else {
            $start = 0;
        }
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $Parking_spot_details = DB::table('parking_spot')
                                    ->select('parking_spot.*')
                                    ->where('parking_spot.users_id', $userID)
                                    ->where('parking_spot.status', 'Active')
                                    ->where('parking_spot.verification_status', 'Yes')
                                    ->where('parking_spot.is_delete', 'No')
                                    ->offset($start)
                                    ->limit($page_limit)
                                    ->get();
                
            /*echo '<pre>';
            print_r($Parking_spot_details);
            exit;*/
            if (count($Parking_spot_details) > 0) {
                foreach ($Parking_spot_details as $key => $spot) {
                    $spot->id = !empty($spot->id) ? (int) $spot->id : 0;
                    $spot->users_id = !empty($spot->users_id) ? (int) $spot->users_id : 0;
                    $spot->country_id = !empty($spot->country_id) ? (int) $spot->country_id : 0;
                    $spot->state_id = !empty($spot->state_id) ? (int) $spot->state_id : 0;
                    $spot->number_of_space_spot = !empty($spot->number_of_space_spot) ? (int) $spot->number_of_space_spot : 0;
                    $spot->no_of_hours = !empty($spot->no_of_hours) ? (int) $spot->no_of_hours : 0;
                    $spot->no_of_days = !empty($spot->no_of_days) ? (int) $spot->no_of_days : 0;
                    $spot->no_of_months = !empty($spot->no_of_months) ? (int) $spot->no_of_months : 0;

                    $spot->latitude = ($spot->latitude != "" && $spot->latitude != null && $spot->latitude != 'null') ? (float)$spot->latitude : 0.0;
                    $spot->longitude = ($spot->longitude != "" && $spot->longitude != null && $spot->longitude != 'null') ? (float)$spot->longitude : 0.0;

                    $spot->address = !empty($spot->address) ? (string) $spot->address : '';
                    $spot->city_name = !empty($spot->city_name) ? (string) $spot->city_name : '';
                    $spot->description = !empty($spot->description) ? (string) $spot->description : '';
                    $spot->location = !empty($spot->location) ? (string) $spot->location : '';

                    $spot->country_name = '';
                    $spot->state_name = '';
                    if(!empty($spot->country_id) && $spot->country_id != 0 && !empty($spot->state_id) && $spot->state_id != 0){
                        $parking_country_state = "SELECT
                                                c.country_name,
                                                s.state_name
                                                FROM country as c
                                                join state as s on s.id = $spot->state_id
                                                where c.id = $spot->country_id";

                        $data = DB::Select($parking_country_state);
                        
                      if(isset($data) && !empty($data)){
                            $spot->country_name = $data[0]->country_name;
                            $spot->state_name = $data[0]->state_name;
                      }
                    }

                    // ====== Store Images Code ===== //
                    $SpotsImagesList = ParkingspotImages::Where('parking_spot_id', $spot->id)->get();
                    if (count($SpotsImagesList) > 0) {
                        $Parking_spot_details[$key]->parking_spot_images = $SpotsImagesList;
                        foreach ($SpotsImagesList as $sub_key => $spot_images) {
                            $Parking_spot_details[$key]->parking_spot_images[$sub_key]->uploaded_image = !empty($spot_images->uploaded_image) ? asset('uploads/parkingspot_images/' . $spot_images->uploaded_image) : '';
                        }
                    } else {
                        $Parking_spot_details[$key]->parking_spot_images = array();
                    }
                    // ====== Store Images Code ===== //
                }

                $msg = 'success';
                $code = 200;
                return response()->json(['message' => $msg, 'code' => $code, 'ParkingSpotList' => $Parking_spot_details]);
            } else {
                $msg = 'No parking spots are registered.';
                $code = 101;
                return response()->json(['message' => $msg, 'code' => $code]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    /*
     * Request Parameter :  [Apikey, parking_spot_id]
     * Method : POST
     * Request Api Url : "/api/fetchparkingspotdetails"
     * Request Controller & Method : ParkingSpotController/fetchParkingSpotDetails
     * Success response : [ message : Array Of Parking Spot Details Using Parking spot id.,  code : 200]
     * Error response : 
      1)[ message : Parking spot does not available., code : 101]
     * 2)[ message : Unauthorised Call. , code : 101]
     */

    public function fetchParkingSpotDetails(Request $request) {
        $parking_spot_id = $request->parking_spot_id;
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $GetParkingspotDetails = DB::table('parking_spot')
                                    ->select('parking_spot.*')
                                    ->where('parking_spot.id', $parking_spot_id)
                                    ->where('parking_spot.status', 'Active')
                                    ->where('parking_spot.verification_status', 'Yes')
                                    ->where('parking_spot.is_delete', 'No')

                                    ->first();
                   
                                    
            if (count($GetParkingspotDetails) > 0) {
                // ====== Store Images Code ===== //
                $SpotsImagesList1 = ParkingspotImages::Where('parking_spot_id', $GetParkingspotDetails->id)->get();
                $GetParkingspotDetails->parking_spot_images = array();
                if (count($SpotsImagesList1) > 0) {
                    $GetParkingspotDetails->parking_spot_images = $SpotsImagesList1;
                    foreach ($SpotsImagesList1 as $k => $images) {
                        $GetParkingspotDetails->parking_spot_images[$k]->id = !empty($images->id) ? (int) $images->id : 0;
                        $GetParkingspotDetails->parking_spot_images[$k]->parking_spot_id = !empty($images->parking_spot_id) ? (int) $images->parking_spot_id : 0;
                        $GetParkingspotDetails->parking_spot_images[$k]->uploaded_image = !empty($images->uploaded_image) ? asset('uploads/parkingspot_images/' . $images->uploaded_image) : '';
                    }
                }
                // ====== Store Images Code ===== //
                $GetParkingspotDetails->id = !empty($GetParkingspotDetails->id) ? (int) $GetParkingspotDetails->id : 0;
                $GetParkingspotDetails->users_id = !empty($GetParkingspotDetails->users_id) ? (int) $GetParkingspotDetails->users_id : 0;
                $GetParkingspotDetails->country_id = !empty($GetParkingspotDetails->country_id) ? (int) $GetParkingspotDetails->country_id : 0;
                $GetParkingspotDetails->state_id = !empty($GetParkingspotDetails->state_id) ? (int) $GetParkingspotDetails->state_id : 0;

                $GetParkingspotDetails->number_of_space_spot = !empty($GetParkingspotDetails->number_of_space_spot) ? (int) $GetParkingspotDetails->number_of_space_spot : 0;
                $GetParkingspotDetails->no_of_hours = !empty($GetParkingspotDetails->no_of_hours) ? (int) $GetParkingspotDetails->no_of_hours : 0;
                $GetParkingspotDetails->no_of_days = !empty($GetParkingspotDetails->no_of_days) ? (int) $GetParkingspotDetails->no_of_days : 0;
                $GetParkingspotDetails->no_of_months = !empty($GetParkingspotDetails->no_of_months) ? (int) $GetParkingspotDetails->no_of_months : 0;

                $GetParkingspotDetails->latitude = ($GetParkingspotDetails->latitude != "" && $GetParkingspotDetails->latitude != null && $GetParkingspotDetails->latitude != 'null') ? (float)$GetParkingspotDetails->latitude : 0.0;
                $GetParkingspotDetails->longitude = ($GetParkingspotDetails->longitude != "" && $GetParkingspotDetails->longitude != null && $GetParkingspotDetails->longitude != 'null') ? (float)$GetParkingspotDetails->longitude : 0.0;

                $GetParkingspotDetails->address = !empty($GetParkingspotDetails->address) ? (string) $GetParkingspotDetails->address : '';
                $GetParkingspotDetails->city_name = !empty($GetParkingspotDetails->city_name) ? (string) $GetParkingspotDetails->city_name : '';
                $GetParkingspotDetails->description = !empty($GetParkingspotDetails->description) ? (string) $GetParkingspotDetails->description : '';
                $GetParkingspotDetails->location = !empty($GetParkingspotDetails->location) ? (string) $GetParkingspotDetails->location : '';


                $GetParkingspotDetails->country_name = '';
                $GetParkingspotDetails->state_name = '';
                if(!empty($GetParkingspotDetails->country_id) && $GetParkingspotDetails->country_id != 0 && 
                        !empty($GetParkingspotDetails->state_id) && $GetParkingspotDetails->state_id != 0){
                    $parking_country_state = "SELECT
                                            c.country_name,
                                            s.state_name
                                            FROM country as c
                                            join state as s on s.id = $GetParkingspotDetails->state_id
                                            where c.id = $GetParkingspotDetails->country_id";

                    $data = DB::Select($parking_country_state);

                  if(isset($data) && !empty($data)){
                        $GetParkingspotDetails->country_name = $data[0]->country_name;
                        $GetParkingspotDetails->state_name = $data[0]->state_name;
                  }
                }
                //---------------------- Start : Send admin pricing in array --------------//
                $pricing_array = array();
                $pricing_array = Pricing::select('id', 'no_of_hours', 'hourly_price', 'no_of_days', 'daily_price', 'no_of_month', 'monthly_price', 'monthly_price')->Where('status', 'Active')->first();
                if(isset($pricing_array) && !empty($pricing_array)){
                    $pricing_array->id = !empty($pricing_array->id) ? (int) $pricing_array->id : 0;
                    $pricing_array->no_of_hours = !empty($pricing_array->no_of_hours) ? (int) $pricing_array->no_of_hours : 0;
                    $pricing_array->hourly_price = !empty($pricing_array->hourly_price) ? (int) $pricing_array->hourly_price : 0;
                    $pricing_array->no_of_days = !empty($pricing_array->no_of_days) ? (int) $pricing_array->no_of_days : 0;
                    $pricing_array->daily_price = !empty($pricing_array->daily_price) ? (int) $pricing_array->daily_price : 0;
                    $pricing_array->no_of_month = !empty($pricing_array->no_of_month) ? (int) $pricing_array->no_of_month : 0;
                    $pricing_array->monthly_price = !empty($pricing_array->monthly_price) ? (int) $pricing_array->monthly_price : 0;
                }else{
                    $pricing_array = (object)array();
                }
                //---------------------- End : Send admin pricing in array --------------//
                $SurchargeSettings = array();
                $SurchargeSettings = SurchargeAmount::select('amount_before_half_min', 'amount_after_half_min', 'amount_per_hour')->first();
                if(isset($SurchargeSettings) && !empty($SurchargeSettings)){
                    $SurchargeSettings->amount_before_half_min = !empty($SurchargeSettings->amount_before_half_min) ? (int) $SurchargeSettings->amount_before_half_min : 0;
                    $SurchargeSettings->amount_after_half_min = !empty($SurchargeSettings->amount_after_half_min) ? (int) $SurchargeSettings->amount_after_half_min : 0;
                    $SurchargeSettings->amount_per_hour = !empty($SurchargeSettings->amount_per_hour) ? (int) $SurchargeSettings->amount_per_hour : 0;
                }else{
                    $SurchargeSettings = (object)array();
                }
                $msg = 'success';
                $code = 200;
                return response()->json(['message' => $msg, 'code' => $code, 'ParkingSpotList' => $GetParkingspotDetails, 'Pricing' => $pricing_array, 'SurchargeAmount' => $SurchargeSettings]);
            } else {
                $msg = 'Parking spot does not available.';
                $code = 101;
                return response()->json(['message' => $msg, 'code' => $code]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    /*
     * Request Parameter :  [Apikey, parking_spot_id]
     * Method : POST
     * Request Api Url : "/api/deleteparkingspot"
     * Request Controller & Method : ParkingSpotController/deleteParkingSpot
     * Success response : [ message : Parking spot deleted successfully.,  code : 200]
     * Error response : 
      1)[ message : Parking spot not deleted successfully., code : 101]
     * 2)[ message : Unauthorised Call. , code : 101]
     */

    public function deleteParkingSpot(Request $request) {
        $parking_spot_id = $request->parking_spot_id;
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {

            $booking_count_upcoming = " SELECT count(id) as count 
                                        FROM booking 
                                        WHERE booking_status = 'Upcoming'
                                        AND parking_spot_id = '$parking_spot_id' ";
            $booking_count_upcoming = DB::Select($booking_count_upcoming);

            if($booking_count_upcoming[0]->count == 0){
                /*$fetchMuiltiimagedata = DB::select("select * from parking_spot_images Where parking_spot_id In($parking_spot_id)");

                foreach ($fetchMuiltiimagedata as $gallery) {
                    $removegalleryimage = $gallery->uploaded_image;
                    if(file_exists(public_path('uploads/parkingspot_images/'.$removegalleryimage))) {
                        unlink(public_path('uploads/parkingspot_images/' . $removegalleryimage));
                    }

                }*/
                $del_parking_spot = DB::table('parking_spot')
                    ->where('id', $parking_spot_id)
                    ->update([
                        'is_delete' => 'Yes'
                    ]);

                /*$del_parking_spot_image = DB::delete('DELETE FROM `parking_spot_images` WHERE parking_spot_id IN (' . $parking_spot_id . ')');
                $del_parking_spot = DB::delete('DELETE FROM `parking_spot` WHERE id IN (' . $parking_spot_id . ')');*/

                if ($del_parking_spot) {
                    $msg = 'Parking spot deleted successfully.';
                    $code = 200;
                    return response()->json(['message' => $msg, 'code' => $code]);
                } else {
                    $msg = 'Parking spot not deleted successfully.';
                    $code = 101;
                    return response()->json(['message' => $msg, 'code' => $code]);
                }
            }else{
                $msg = 'You can\'t able to delete this spot because some booking are active';
                $code = 101;
                return response()->json(['message' => $msg, 'code' => $code]);
            }

        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    /*
    * Request Parameter :  [Apikey, image_id]
    * Method : POST
    * Request Api Url : "/api/deleteparkingspotimage"
    * Request Controller & Method : ParkingSpotController/deleteparkingspotimage
    * Success response : [ message : Parking spot image deleted successfully.,  code : 200]
    * Error response :
     1)[ message : Parking spot image not deleted successfully., code : 101]
    * 2)[ message : Unauthorised Call. , code : 101]
    */

    public function deleteparkingspotimage(Request $request) {

        $image_id = $request->image_id;
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $fetchMuiltiimagedata = DB::select(" select * from parking_spot_images 
                                                Where id = $image_id ");

            foreach ($fetchMuiltiimagedata as $gallery) {
                $removegalleryimage = $gallery->uploaded_image;
                if(file_exists(public_path('uploads/parkingspot_images/'.$removegalleryimage))) {
                    unlink(public_path('uploads/parkingspot_images/' . $removegalleryimage));
                }
            }

            $del_parking_spot_image = DB::delete(" DELETE FROM `parking_spot_images` 
                                                  WHERE id = $image_id ");

            if ($del_parking_spot_image) {
                $msg = 'Parking spot image deleted successfully.';
                $code = 200;
                return response()->json(['message' => $msg, 'code' => $code]);
            } else {
                $msg = 'Parking spot image not deleted successfully.';
                $code = 101;
                return response()->json(['message' => $msg, 'code' => $code]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    /*
     * Request Parameter : [Apikey, parking_spot_id]
     * Method : POST
     * Request Api Url : "/api/editparkingspot"
     * Request Controller & Method : ParkingSpotController/updateParkingSpot
     * Success response : [ message : Array Of Parking Spot Details Using Parking spot id.,  code : 200]
     * Error response : 
      1)[ message : Parking spot does not available., code : 101]
     * 2)[ message : Unauthorised Call. , code : 101]
     */

    public function updateParkingSpot(Request $request) {
        $parking_spot_id = $request->parking_spot_id;
        $instant_rent_status = $request->instant_rent_status;
        $renting_type = $request->renting_type;
        $userID = $request->userID;

        $number_of_space_spot = !empty($request->number_of_space_spot) ? $request->number_of_space_spot : 0;
        $description = !empty($request->description) ? $request->description : '';

        $sche_start_date = !empty($request->sche_start_date) ? date('Y-m-d H:i:s', strtotime($request->sche_start_date)) : '';
        $sche_start_time = !empty($request->sche_start_time) ? date('H:i:s', strtotime($request->sche_start_time)) : '';

        $no_of_hours = !empty($request->no_of_hours) ? $request->no_of_hours : 0;
        $no_of_days = !empty($request->no_of_days) ? $request->no_of_days : 0;
        $no_of_months = !empty($request->no_of_months) ? $request->no_of_months : 0;
        $mon_start_time = !empty($request->mon_start_time) ? date('H:i:s', strtotime($request->mon_start_time)) : '';
        $mon_end_time = !empty($request->mon_end_time) ? date('H:i:s', strtotime($request->mon_end_time)) : '';
        $tue_start_time = !empty($request->tue_start_time) ? date('H:i:s', strtotime($request->tue_start_time)) : '';
        $tue_end_time = !empty($request->tue_end_time) ? date('H:i:s', strtotime($request->tue_end_time)) : '';
        $wed_start_time = !empty($request->wed_start_time) ? date('H:i:s', strtotime($request->wed_start_time)) : '';
        $wed_end_time = !empty($request->wed_end_time) ? date('H:i:s', strtotime($request->wed_end_time)) : '';
        $thur_start_time = !empty($request->thur_start_time) ? date('H:i:s', strtotime($request->thur_start_time)) : '';
        $thur_end_time = !empty($request->thur_end_time) ? date('H:i:s', strtotime($request->thur_end_time)) : '';
        $fri_start_time = !empty($request->fri_start_time) ? date('H:i:s', strtotime($request->fri_start_time)) : '';
        $fri_end_time = !empty($request->fri_end_time) ? date('H:i:s', strtotime($request->fri_end_time)) : '';
        $sat_start_time = !empty($request->sat_start_time) ? date('H:i:s', strtotime($request->sat_start_time)) : '';
        $sat_end_time = !empty($request->sat_end_time) ? date('H:i:s', strtotime($request->sat_end_time)) : '';
        $sun_start_time = !empty($request->sun_start_time) ? date('H:i:s', strtotime($request->sun_start_time)) : '';
        $sun_end_time = !empty($request->sun_end_time) ? date('H:i:s', strtotime($request->sun_end_time)) : '';

        $sche_start_date_time = '';
        $final_end_date = '';
        if($request->is_avability_time_set == "Yes"){
            if ($renting_type == 'Schedule Rent') {
                $no_of_hours = $request->no_of_hours;
                $no_of_days = $request->no_of_days;
                $no_of_months = $request->no_of_months;
                $sche_start_date_time = date("Y-m-d", strtotime($sche_start_date)).' '.$sche_start_time;
                if($no_of_months >0){
                    $time = strtotime($sche_start_date_time);
                    $temp_month = "+".$no_of_months." month";
                    $final_end_date = date("Y-m-d H:i:s", strtotime($temp_month, $time));
                }
                if($no_of_days >0){
                    if(isset($final_end_date) && !empty($final_end_date)){
                        $time = strtotime($final_end_date);
                        $temp_day = "+".$no_of_days." day";
                        $final_end_date = date("Y-m-d H:i:s", strtotime($temp_day, $time));
                    }else{
                        $time = strtotime($sche_start_date_time);
                        $temp_day = "+".$no_of_days." day";
                        $final_end_date = date("Y-m-d H:i:s", strtotime($temp_day, $time));
                    }
                }
                if($no_of_hours >0) {
                    if(isset($final_end_date) && !empty($final_end_date)){
                        $time = strtotime($final_end_date);
                        $temp_hours = '+' . $no_of_hours . ' hour';
                        $final_end_date = date("Y-m-d H:i:s", strtotime($temp_hours, $time));
                    }else{
                        $time = strtotime($sche_start_date_time);
                        $temp_hours = '+' . $no_of_hours . ' hour';
                        $final_end_date = date("Y-m-d H:i:s", strtotime($temp_hours, $time));
                    }
                }


            } else if ($renting_type == 'Auto Rent') {
                $mon_start_time = $request->mon_start_time;
                $mon_end_time = $request->mon_end_time;
                $tue_start_time = $request->tue_start_time;
                $tue_end_time = $request->tue_end_time;
                $wed_start_time = $request->wed_start_time;
                $wed_end_time = $request->wed_end_time;
                $thur_start_time = $request->thur_start_time;
                $thur_end_time = $request->thur_end_time;
                $fri_start_time = $request->fri_start_time;
                $fri_end_time = $request->fri_end_time;
                $sat_start_time = $request->sat_start_time;
                $sat_end_time = $request->sat_end_time;
                $sun_start_time = $request->sun_start_time;
                $sun_end_time = $request->sun_end_time;
            }
        }else{
            $renting_type = 'Instant Rent';
        }


        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $parking_spot = DB::table('parking_spot')->where('id', $parking_spot_id)->update(
                    [
                        'instant_rent' => $instant_rent_status,
                        'renting_type' => $renting_type,
                        /*'number_of_space_spot' => $number_of_space_spot,*/
                        'description' => $description,
                        'sche_start_date' => $sche_start_date,
                        'sche_start_time' => $sche_start_time,
                        'sche_start_date_time' => $sche_start_date_time,
                        'sche_end_date_time' => $final_end_date,
                        'no_of_hours' => $no_of_hours,
                        'no_of_days' => $no_of_days,
                        'no_of_months' => $no_of_months,
                        'mon_start_time' => $mon_start_time,
                        'mon_end_time' => $mon_end_time,
                        'tue_start_time' => $tue_start_time,
                        'tue_end_time' => $tue_end_time,
                        'wed_start_time' => $wed_start_time,
                        'wed_end_time' => $wed_end_time,
                        'thur_start_time' => $thur_start_time,
                        'thur_end_time' => $thur_end_time,
                        'fri_start_time' => $fri_start_time,
                        'fri_end_time' => $fri_end_time,
                        'sat_start_time' => $sat_start_time,
                        'sat_end_time' => $sat_end_time,
                        'sun_start_time' => $sun_start_time,
                        'sun_end_time' => $sun_end_time,
                        'updated_date' => Helper::get_curr_datetime()
                    ]
            );

            if ($request->hasFile('uploaded_images')) {
                $files = $request->file('uploaded_images');
                $i = 0;
                foreach ($files as $file) {
                    if ($i <= 4) {
                        $last_inserted_id = $parking_spot_id;
                        $filename = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();
                        $rendom = rand();
                        $picture = date('His'). $rendom . $filename;
                        $destinationPath = public_path('uploads/parkingspot_images');
                        $file->move($destinationPath, $picture);

                        $parking_spot_images_id = DB::table('parking_spot_images')->insert(
                            [
                                'parking_spot_id' => $last_inserted_id,
                                'uploaded_image' => $picture,
                                'created_date' => Helper::get_curr_datetime(),
                                'created_by' => $userID
                            ]
                        );
                    }
                    $i++;
                }
            }

            if ($parking_spot) {
                return response()->json(['message' => 'Parking spot updated successfully.', 'code' => 200]);
            } else {
                return response()->json(['message' => 'Parking spot updated fail.', 'code' => 101]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    /*
     * Request Parameter :  [Apikey, parking_spot_id]
     * Method : POST
     * Request Api Url : "/api/searchableparkingspotlist"
     * Request Controller & Method : ParkingSpotController/searchableparkingSpotList
     * Success response : [ message : Array Of Parking Spot Details Using Parking spot id.,  code : 200]
     * Error response : 
      1)[ message : Parking spot does not available., code : 101]
     * 2)[ message : Unauthorised Call. , code : 101]
     */



    public function searchableparkingSpotList(Request $request) {
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $latitude_by_post = $request->latitude;
            $longitude_by_post = $request->longitude;
            $date = $request->date;
            $time = $request->time;
            $month = $request->month;
            $day = $request->day;
            $hour = $request->hour;
            $timezone = $request->timezone;
            $userID = $request->userID;

            $spot_search_get_id = DB::table('parking_spot_search_log')->Where('users_id', $userID)->first();
            if(isset($spot_search_get_id) && !empty($spot_search_get_id)){
                $update = DB::table('parking_spot_search_log')
                    ->where('users_id', $userID)
                    ->update([
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'date' => $date,
                        'time' => $time
                    ]);
            }else{
                $spot_search_log = DB::table('parking_spot_search_log')->insertGetId([
                    'users_id' => $userID,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'date' => $date,
                    'time' => $time
                ]);
            }


            date_default_timezone_set($timezone);

            //echo date_default_timezone_get();exit;
            
            $general_settings = "SELECT *"
                    . " FROM general_settings";
            $general_settings = DB::Select($general_settings);
            $distance_of_miles = 2 ;
            if(isset($general_settings) && !empty($general_settings)){
                $distance_of_miles = $general_settings[0]->distance_of_miles;
            }     

            $where = '';
            $booking_date = date('Y-m-d', strtotime($date));

            if (!empty($time)) {
                $booking_time = date('H:i:s', strtotime($time));
                $where .= " AND b.booking_time = '" . $booking_time . "'";
            }

            if (!empty($month)) {
                $where .= " AND b.booking_month = " . $month;
            } else if (!empty($day)) {
                $where .= " AND b.booking_days = " . $day;
            } else if (!empty($hour)) {
                $where .= " AND b.booking_hours =" . $hour;
            }

            //$current_datetime = Helper::get_curr_datetime();
            $sche_start_date_time = date("Y-m-d H:i:s",strtotime($date.' '.$booking_time));
            $current_datetime = date("Y-m-d H:i:s");
            $current_time = date("H:i:s", strtotime($current_datetime));

            $final_end_date = '';
            if($month >0){
                $time = strtotime($sche_start_date_time);
                $temp_month = "+".$month." month";
                $final_end_date = date("Y-m-d H:i:s", strtotime($temp_month, $time));
            }
            if($day >0){
                if(isset($final_end_date) && !empty($final_end_date)){
                    $time = strtotime($final_end_date);
                    $temp_day = "+".$day." day";
                    $final_end_date = date("Y-m-d H:i:s", strtotime($temp_day, $time));
                }else{
                    $time = strtotime($sche_start_date_time);
                    $temp_day = "+".$day." day";
                    $final_end_date = date("Y-m-d H:i:s", strtotime($temp_day, $time));
                }
            }
            if($hour >0) {
                if(isset($final_end_date) && !empty($final_end_date)){
                    $time = strtotime($final_end_date);
                    $temp_hours = '+' . $hour . ' hour';
                    $final_end_date = date("Y-m-d H:i:s", strtotime($temp_hours, $time));
                }else{
                    $time = strtotime($sche_start_date_time);
                    $temp_hours = '+' . $hour . ' hour';
                    $final_end_date = date("Y-m-d H:i:s", strtotime($temp_hours, $time));
                }
            }
            

            $parking_Array = array();
            /*THEN (TIMESTAMP(s.sche_start_date_time) <= TIMESTAMP('$sche_start_date_time') ) AND (TIMESTAMP(s.sche_end_date_time) >= TIMESTAMP('$final_end_date') )*/
            $parking_spotSql = "SELECT s.*,
                        69.0 * DEGREES( ACOS( COS( RADIANS( s.latitude ) ) * COS( RADIANS( '" . $latitude . "' ) ) * COS( RADIANS( s.longitude - '" . $longitude . "' ) ) + SIN( RADIANS( s.latitude ) ) * SIN( RADIANS( '" . $latitude . "' ) ) ) ) AS distance_in_miles
                        FROM parking_spot as s
                        Having distance_in_miles < $distance_of_miles 
                        AND s.verification_status='Yes' 
                        AND s.is_delete='No' 
                        AND s.users_id != $userID
                        AND s.instant_rent = 'ON'
                        AND CASE s.renting_type
                             WHEN 'Instant Rent' 
                             THEN (TIMESTAMP(s.created_date) <= TIMESTAMP('$sche_start_date_time') )
                             WHEN 'Schedule Rent' 
                             THEN (TIMESTAMP(s.sche_start_date_time) <= TIMESTAMP('$sche_start_date_time') ) AND (TIMESTAMP(s.sche_end_date_time) >= TIMESTAMP('$sche_start_date_time') )
                             WHEN 'Auto Rent' 
                             THEN 
                                CASE DAYNAME('$current_datetime')
                                     WHEN 'Monday'
                                        THEN (s.mon_start_time <= '$current_time') AND (s.mon_end_time >= '$current_time')
                                     WHEN 'Tuesday'
                                        THEN (s.tue_start_time <= '$current_time') AND (s.tue_end_time >= '$current_time')
                                     WHEN 'Wednesday'
                                        THEN (s.wed_start_time <= '$current_time') AND (s.wed_end_time >= '$current_time')
                                     WHEN 'Thursday'
                                        THEN (s.thur_start_time <= '$current_time') AND (s.thur_end_time >= '$current_time')
                                     WHEN 'Friday'
                                        THEN (s.fri_start_time <= '$current_time') AND (s.fri_end_time >= '$current_time')
                                     WHEN 'Saturday'
                                        THEN (s.sat_start_time <= '$current_time') AND (s.sat_end_time >= '$current_time')
                                     WHEN 'Sunday'
                                        THEN (s.sun_start_time <= '$current_time') AND (s.sun_start_time >= '$current_time')              
                                END 
                        END
                      ";
            //echo $parking_spotSql; exit;
            $parking_Array = DB::Select($parking_spotSql);

            /*echo '<pre>';
            print_r($parking_Array);
            echo '</pre>';
            exit;*/

            /*AND s.id NOT IN (SELECT `booking`.parking_spot_id
                                                FROM `booking`
                                                WHERE `parking_spot_id` = s.id
                                                AND `booking_status` = 'Upcoming'
                                                AND (`booking_start_date_time`
                                                BETWEEN '$sche_start_date_time'
                                                AND '$final_end_date'
                                                OR `booking_end_date_time`
                                                BETWEEN '$sche_start_date_time'
                                                AND '$final_end_date') having count(*) = s.number_of_space_spot)*/
            $new_array_of_spot = [];
            if(count($parking_Array) > 0){
                foreach ($parking_Array as $key =>$action){

                    $space_managment = DB::table('parking_spot_space_managment')->Where('parking_spot_id', $action->id)->get();

                    if(count($space_managment) > 0){
                        foreach ($space_managment as $space){

                            $check_avaibility_count = "SELECT count( * ) as count
                                                FROM `booking`
                                                WHERE `parking_spot_id` = $action->id
                                                AND `space_managment_id` = $space->id
                                                AND `booking_status` = 'Upcoming'
                                                AND `is_delete` = 'No'
                                                AND(
                                                    `booking_start_date_time` BETWEEN '$sche_start_date_time' AND '$final_end_date' 
                                                    OR `booking_end_date_time` BETWEEN '$sche_start_date_time' AND '$final_end_date'
                                                    OR `booking_start_date_time` < '$sche_start_date_time' and `booking_end_date_time` > '$final_end_date')
                                              
                                            ";
                            $check_avaibility_count = DB::Select($check_avaibility_count);

                            /*echo '<pre>';
                            print_r($check_avaibility_count);
                            echo '</pre>';*/


                            if($check_avaibility_count[0]->count == 0){
                                $space_managment_id = $space->id;
                                break;
                            }
                        }
                    }
                    //exit;
                    if(isset($space_managment_id) && !empty($space_managment_id)){
                        $action->latitude = ($action->latitude != "" && $action->latitude != null && $action->latitude != 'null') ? (float)$action->latitude : 0.0;
                        $action->longitude = ($action->longitude != "" && $action->longitude != null && $action->longitude != 'null') ? (float)$action->longitude : 0.0;

                        $action->current_time =  $current_datetime;
                        $action->discount_amount =  !empty($general_settings[0]->discount_amount)?(int)$general_settings[0]->discount_amount:0;
                        $action->penalty_amount =  !empty($general_settings[0]->penalty_amount)?(int)$general_settings[0]->penalty_amount:0;

                        $action->id = !empty($action->id) ? (int) $action->id : 0;
                        $action->users_id = !empty($action->users_id) ? (int) $action->users_id : 0;
                        $action->country_id = !empty($action->country_id) ? (int) $action->country_id : 0;
                        $action->state_id = !empty($action->state_id) ? (int) $action->state_id : 0;

                        $action->number_of_space_spot = !empty($action->number_of_space_spot) ? (int) $action->number_of_space_spot : 0;
                        $action->no_of_hours = !empty($action->no_of_hours) ? (int) $action->no_of_hours : 0;
                        $action->no_of_days = !empty($action->no_of_days) ? (int) $action->no_of_days : 0;
                        $action->no_of_months = !empty($action->no_of_months) ? (int) $action->no_of_months : 0;

                        $action->address = !empty($action->address) ? (string) $action->address : '';
                        $action->city_name = !empty($action->city_name) ? (string) $action->city_name : '';
                        $action->description = !empty($action->description) ? (string) $action->description : '';
                        $action->location = !empty($action->location) ? (string) $action->location : '';

                        $last_booking = DB::table('booking')->where('booking.users_id',$userID)
                                        ->where('booking.is_additional_credited_amount','=','Yes')->first();
                        $action->is_additional_credited_amount = 'No';
                        if(isset($last_booking) && !empty($last_booking)){
                            $action->is_additional_credited_amount = !empty($last_booking->is_additional_credited_amount) ? (string) $last_booking->is_additional_credited_amount : 'No';
                        }

                        // ====== Store Images Code ===== //
                        $SpotsImagesList1 = ParkingspotImages::Where('parking_spot_id', $action->id)->get();
                        $action->parking_spot_images = array();
                        $is_image = FALSE;
                        if (count($SpotsImagesList1) > 0) {
                            $action->parking_spot_images = $SpotsImagesList1;
                            foreach ($SpotsImagesList1 as $k => $images) {
                                $action->parking_spot_images[$k]->id = !empty($images->id) ? (int) $images->id : 0;
                                $action->parking_spot_images[$k]->parking_spot_id = !empty($images->parking_spot_id) ? (int) $images->parking_spot_id : 0;
                                $action->parking_spot_images[$k]->uploaded_image = !empty($images->uploaded_image) ? asset('uploads/parkingspot_images/' . $images->uploaded_image) : '';
                            }
                            $is_image = TRUE;
                        }
                        $action->is_image = $is_image;

                        $review_booking = "SELECT AVG(rating) as rating"
                            . " FROM booking"
                            . " LEFT JOIN review ON review.users_id = booking.users_id"
                            . " WHERE booking.parking_spot_id = $action->id";

                        $review = DB::Select($review_booking);
                        $action->reting = 0.0;
                        if(count($review) > 0 && !empty($review[0]->rating)){
                            $action->reting = (float)$review[0]->rating;
                        }
                        $review_users_count = DB::table('review')->Where('parking_spot_id', $action->id)->count();
                        $action->review_users_count = (int) $review_users_count;
                        //---------------------- Start : Send admin pricing in array --------------//
                        $pricing_array = Pricing::select('id', 'no_of_hours', 'hourly_price', 'no_of_days', 'daily_price', 'no_of_month', 'monthly_price', 'monthly_price')->Where('status', 'Active')->first();
                        if(count($pricing_array) > 0){
                            if(isset($pricing_array) && !empty($pricing_array)){
                                $pricing_array->id = !empty($pricing_array->id) ? (int) $pricing_array->id : 0;
                                $pricing_array->no_of_hours = !empty($pricing_array->no_of_hours) ? (int) $pricing_array->no_of_hours : 0;
                                $pricing_array->hourly_price = !empty($pricing_array->hourly_price) ? (int) $pricing_array->hourly_price : 0;
                                $pricing_array->no_of_days = !empty($pricing_array->no_of_days) ? (int) $pricing_array->no_of_days : 0;
                                $pricing_array->daily_price = !empty($pricing_array->daily_price) ? (int) $pricing_array->daily_price : 0;
                                $pricing_array->no_of_month = !empty($pricing_array->no_of_month) ? (int) $pricing_array->no_of_month : 0;
                                $pricing_array->monthly_price = !empty($pricing_array->monthly_price) ? (int) $pricing_array->monthly_price : 0;
                            }else{
                                $pricing_array = (object) array();
                            }
                        }else{
                            $pricing_array = (object) array();
                        }
                        $action->Pricing = $pricing_array;
                        //---------------------- End : Send admin pricing in array --------------//
                        array_push($new_array_of_spot,$action);
                    }else{
                        unset($parking_Array[$key]);
                    }

                }
            }


            /*Start - push notification*/
            $search_details = "SELECT
                                COUNT(id) as count_id,
                                latitude,
                                longitude
                           FROM parking_spot_search_log
                           GROUP BY latitude
                           ORDER BY count_id DESC ";
            $search_details = DB::Select($search_details);
            if( isset($search_details) && !empty($search_details) ){
                if($search_details[0]->count_id > 15){
                    $parking_spot_push = "SELECT s.id, s.users_id,s.verification_status,s.parking_spot_search,
                        69.0 * DEGREES( ACOS( COS( RADIANS( s.latitude ) ) * COS( RADIANS( '" . $search_details[0]->latitude . "' ) ) * COS( RADIANS( s.longitude - '" . $search_details[0]->longitude . "' ) ) + SIN( RADIANS( s.latitude ) ) * SIN( RADIANS( '" . $search_details[0]->latitude . "' ) ) ) ) AS distance_in_miles "
                        . " FROM parking_spot as s"
                        . " Having distance_in_miles < $distance_of_miles 
                        AND s.verification_status = 'Yes'
                        AND s.is_delete = 'No'
                        AND s.parking_spot_search = 'Inactive'
                      ";
                    $parking_spot_push = DB::Select($parking_spot_push);
                    foreach ($parking_spot_push as $spot_push){
                        $notification = DB::table('notification')
                            ->join('user_notification', 'user_notification.id', '=', 'notification.push_notification_id')
                            ->Where('notification.users_id', $spot_push->users_id)
                            ->get();
                        if(count($notification) > 0 && $notification[2]->notification_mode == "ON"){
                            $deviceDetails = DeviceMaster::Where('users_id', $spot_push->users_id)->where('is_login', 'Yes')->first();
                            if(count($deviceDetails) > 0){
                                $data = [
                                    'notification' => $spot_push->id,
                                    'type' => 'high_demand',
                                    'message'=> 'There is high demand nearby your spot, Please extend your spot time'
                                ];
                                /*$message_title = "High demand \r\nThere is high demand nearby your spot, Please extend your spot time";*/
                                $message_title = "High demand \r\nThere is high demand near your Parking Spot! Make sure your Parking Spot is available for rent!";

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
                            DB::table('parking_spot_search_log')
                                    ->where('latitude', '=', $search_details[0]->latitude)->delete();
                        }
                    }
                }
            }
            /*End - push notification*/

            /*Start => last surcharge of users*/
            $s_details = DB::table('booking')
                ->select('*')
                ->orderBy('id', 'desc')
                ->Where('users_id', $userID)
                ->first();

            if(count($s_details) > 0){
                if($s_details->is_surcharge_paid == "No"){
                    $s_details->id = !empty($s_details->id) ? (int) $s_details->id : 0;
                    $s_details->parking_spot_id = !empty($s_details->parking_spot_id) ? (int) $s_details->parking_spot_id : 0;
                    $s_details->users_id = !empty($s_details->users_id) ? (int) $s_details->users_id : 0;
                    $s_details->booking_amount = !empty($s_details->booking_amount) ? (int) $s_details->booking_amount : 0;
                    $s_details->cancellation_fee = !empty($s_details->cancellation_fee) ? (int) $s_details->cancellation_fee : 0;
                    $s_details->additional_credited_amount = !empty($s_details->additional_credited_amount) ? (int) $s_details->additional_credited_amount : 0;
                    $s_details->paid_amount = !empty($s_details->paid_amount) ? (int) $s_details->paid_amount : 0;
                    $s_details->surcharge_amount = !empty($s_details->surcharge_amount) ? (int) $s_details->surcharge_amount : 0;
                    $s_details->booking_transaction_id = !empty($s_details->booking_transaction_id) ? (string) $s_details->booking_transaction_id : '';
                }else{
                    $s_details = (object) array();
                }
            }else{
                $s_details = (object) array();
            }
            /*End => last surcharge of users*/


            if(count($new_array_of_spot) > 0){

                return response()->json(['message' => 'Success', 'code' => 200, 'data' => $new_array_of_spot, 'surcharge_details' => $s_details ]);
            }else{
                return response()->json(['message' => 'Parking Spots are not available.', 'code' => 101]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

}

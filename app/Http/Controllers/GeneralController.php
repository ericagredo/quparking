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

class GeneralController extends Controller {
    /*
     * Request Parameter : [Apikey]
     * Method : POST
     * Request Api Url : "/api/countryList"
     * Request Controller & Method : GeneralController/countryList
     * Success response : [ message : Success.,  code : 200, data: Array of country]
     * Error response : 
      1)[ message : Unauthorised Call. , code : 101]
     */

    public function countryList(Request $request) {
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $countryList = DB::table('country')->where('status', 'Active')->get();
            if (count($countryList) > 0) {
                foreach ($countryList as $country) {
                    $country->id = !empty($country->id) ? (int) $country->id : 0;
                    $country->country_name = !empty($country->country_name) ? (string) $country->country_name : '';
                    $country->country_code = !empty($country->country_code) ? (string) $country->country_code : '';
                }
                return response()->json(['message' => 'Success', 'code' => 200, 'data' => $countryList]);
            } else {
                return response()->json(['message' => 'Country doesnot avilable', 'code' => 101]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    /*
     * Request Parameter : [Apikey,country_id]
     * Method : POST
     * Request Api Url : "/api/stateList"
     * Request Controller & Method : GeneralController/stateList
     * Success response : [ message : Success.,  code : 200, data: Array of State]
     * Error response : 
      1)[ message : Unauthorised Call, code : 101]
      2)[ message : State doesnot avilable, code : 101]
     * Info
     * if you parse country_is then you can get statelist by country otheviews all
     */

    public function stateList(Request $request) {
        $Apikey = $request->Apikey;
        $country_id = $request->country_id;
        if ($Apikey == APIKEY) {
            $State = new \App\Models\State();
            if(isset($country_id) && !empty($country_id)){
                $State_list = $State->get_state_list_by_country_id($country_id);
            }  else {
                $State_list = DB::table('state')->where('status', 'Active')->get();
                foreach ($State_list as $state) {
                    $state->id = !empty($state->id) ? (int) $state->id : 0;
                    $state->state_name = !empty($state->state_name) ? (string) $state->state_name : '';
                }
            }  
            /*if (count($stateList) > 0) {*/
            if (isset($State_list) && !empty($State_list)) {
                return response()->json(['message' => 'Success', 'code' => 200, 'data' => $State_list]);
            } else {
                return response()->json(['message' => 'State doesnot avilable', 'code' => 101]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

}

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
use App\Models\PromoCode;
use Mail;

class PromotionController extends Controller {
    /*
     * Request Parameter :  [Apikey, page_limit, page_offset, timezone]
     * Method : POST
     * Request Api Url : "/api/promotionList"
     * Request Controller & Method : PromotionController/promotionList
     * Success response : [ message : Array Of Promotion Details.,  code : 200]
     * Error response : 
      1)[ message : Promotion does not available., code : 101]
      2)[ message : Unauthorised Call. , code : 101]
     */

    public function promotionList(Request $request) {
        $page_limit = $request->page_limit;
        $page_offset = $request->page_offset;
        $timezone = $request->timezone;
        
        date_default_timezone_set($timezone);
        $CurrentDateTime = date('Y-m-d H:i:s');
        
        if ($page_offset) {
            $page_offset1 = $page_offset + 1;
            $start = ($page_offset1 - 1) * $page_limit;
        } else {
            $start = 0;
        }
        
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $promocode_sql = 'SELECT * 
                              FROM `promocode`
                              HAVING promo_start_date <= STR_TO_DATE( "'.$CurrentDateTime.'", "%Y-%m-%d" ) AND promo_end_date >= STR_TO_DATE( "'.$CurrentDateTime.'", "%Y-%m-%d" ) AND status = "Active" ORDER BY id desc limit ' . $start . ' , ' . $page_limit;
            $promocode_details = DB::select($promocode_sql);
            if (count($promocode_details) > 0) {
                $promocode_details_Array = array();
                $i=0;
                foreach($promocode_details as $promotion){
                    $promocode_details_Array[$i] = $promotion;
                    $promocode_details_Array[$i]->id = !empty($promocode_details_Array[$i]->id) ? (int) $promocode_details_Array[$i]->id : '';
                    $promocode_details_Array[$i]->discount = !empty($promocode_details_Array[$i]->discount) ? (int) $promocode_details_Array[$i]->discount : '';
                    $promocode_details_Array[$i]->promo_start_date = ($promocode_details_Array[$i]->promo_start_date != '') ? date('d-m-Y', strtotime($promocode_details_Array[$i]->promo_start_date)) : '';
                    $promocode_details_Array[$i]->promo_end_date = ($promocode_details_Array[$i]->promo_end_date != '') ? date('d-m-Y', strtotime($promocode_details_Array[$i]->promo_end_date)) : '';
                    $i++;
                }

                $msg = 'success';
                $code = 200;
                return response()->json(['message' => $msg, 'code' => $code, 'PromotionList' => $promocode_details_Array], 200, [], JSON_NUMERIC_CHECK);
            } else {
                $msg = 'Promotion does not available.';
                $code = 101;
                return response()->json(['message' => $msg, 'code' => $code]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        } 
    }

    public function promotionCodeApply(Request $request){
        $Apikey = $request->Apikey;
        $promocode = strtoupper($request->promo_code);
        $CurrentDateTime = date('Y-m-d');
        if ($Apikey == APIKEY && !empty($promocode)) {
            $query = "SELECT * 
                  FROM `promocode`
                  WHERE `promocode`.promo_code = '$promocode' AND `promocode`.status = 'Active' ";
            $promocode_details = DB::select($query);

            if(isset($promocode_details) && !empty($promocode_details)){
                if($promocode_details[0]->promo_start_date <= $CurrentDateTime && $promocode_details[0]->promo_end_date >= $CurrentDateTime){
                    $msg = 'success';
                    $code = 200;
                    return response()->json(['message' => $msg, 'code' => $code, 'Data' => $promocode_details], 200, [], JSON_NUMERIC_CHECK);
                }else{
                    return response()->json(['message' => 'Pomocode date is not valid.', 'code' => 101]);
                }
            }else{
                return response()->json(['message' => 'Pomocode is not valid.', 'code' => 101]);
            }
        }else{
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }

    }
}

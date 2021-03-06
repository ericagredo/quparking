<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Helper;
use Illuminate\Support\Facades\validate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Models\EmailTemplate;
use Mail;

//use App\Models\UserNotification;

class UserNotificationController extends Controller {
    
    
    /* Start - this function for redirect on UserNotification list*/
    public function usernotificationlist() {
        
        return view('user_notification/userNotificationList');
    }
    /* End - this function for redirect on UserNotification list*/
    
     /* Start - this function for get UserNotification list using datatable*/
    public function AjaxUserNotificationList($order_by = "id", $sort_order = "asc", $search = "all", $offset = 0) {
        
        $UserNotification = new \App\Models\UserNotification();

        $aColumns = array('id', 'notification_title' , 'status');
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
        
        $UserNotification->set_data($data);
        $UserNotification->set_SearchType($SearchType);
        $UserNotification->set_order_by($order_by);
        $UserNotification->set_sort_order($sort_order);
        $UserNotification->set_limit($limit);
        $UserNotification->set_offset($offset);
        $result = $UserNotification->get_data_for_user_notification();

       
        $data = array();
        if (count($result) > 0) {
            $data['result'] = $result;
            $data['totalRecord'] = $UserNotification->count_all_user_notification_grid();
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
                
                foreach ($data['result'] AS $result_row) {
                    $row = array();
                    $row[] = $result_row->id;
                    $row[] = $result_row->notification_title;
                    $row[] = $result_row->status;
                    //$row[] = array();
                    $output['aaData'][] = $row;
                }
                
            }
        }
        //print_r(json_encode($output));exit;
        echo json_encode($output);
    }
    /* End - this function for get UserNotification list using datatable*/
    
    /* Start this is halping function for data table and & like query*/
    public function trim_serach_data($search_data, $SearchType) {
       $QueryStr = array();

        if (!empty($search_data)) {
            if ($SearchType == 'ANDLIKE') {
                $i = 0;
                foreach ($search_data as $key => $val) {
                    if ($key == 'notification_title' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'notification_title';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                   
                    if ($key == 'status' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'status';
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
    
    /* Start - Thiss function for active and inactive status*/
    public function activeInactiveusernotification(Request $request) {
        
        $UserNotification = new \App\Models\UserNotification();
        $id = $request->id;
        $status = $request->status;
        $mode = $request->mode;
        
        //Code to check if the id from the url exists in the table or not starts here. 
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'user_notification');
        if (!$id_exists) {
            return view('error.404');
        }
        
        //Code to check id ends here
        $Update_status = $UserNotification->update_status_of_user_notification($id,$status);
        
        if (count($Update_status) == 1) {
            echo count($Update_status);
        } else {
            echo 0;
        }
        exit;
    }
    /* End - Thiss function for active and inactive status*/
    
     /*Start - this function for redirect add form*/
    public function createNewUserNotification(){
         return view('user_notification/createUserNotification');
    }
    /*End - this function for redirect add form*/
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
         
         $this->validate($request,[
          'notification_title'=>'required|max:255',
          'status'=>'required'
          ]);
         
         $UserNotification = new \App\Models\UserNotification();
         
         $UserId = $request->session()->get('userData');
         $user_id = $UserId->id;
        
         /*$count = DB::table('country')->where('country_name', '=', $request->country_name)->orWhere('country_code', '=', $request->country_code)->count();
        if ($count == 0) {*/
            $UserNotification->set_notification_title($request->notification_title);
            $UserNotification->set_status($request->status);
            $UserNotification->set_created_date(date('Y-m-d H:i:s'));
            $UserNotification->set_updated_date(date('Y-m-d H:i:s'));
            $UserNotification->set_created_by($user_id);
            $UserNotification->set_updated_by('');
            $UserNotification->insert_user_notification();
         
            return redirect('usernotification/manageusernotification');
        /*}else {
            $message = 'Country already exist.';
            return redirect('country/managecountry')->with('message', $message);
        }*/
    }
    /* Start - This function for check exist record record when do add / edit action*/
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteusernotification(Request $request) {
       $UserNotification = new \App\Models\UserNotification();
        $id = $request->id;
        //Code to check if the id from the url exists in the table or not starts here. 
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'user_notification');
        if (!$id_exists) {
            return view('error.404');
        }
        //Code to check id ends here
        $del_user_noti = $UserNotification->delete_record_of_user_notification_id_ids($id);
       

        if ($del_user_noti) {
            echo '1';
        } else {
            echo '0';
        }
        exit;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    /* Start - This function for dedirection on edit user notification page*/
    public function editusernotification($id){
        $notification = DB::table('user_notification')->where('id', $id)->first();
        return view('user_notification/editUserNotification', ['notification' => $notification]);
    }
    /* End - This function for dedirection on edit user notification page*/
    
    /* Start - This function for update user notification data by parsing id*/
    public function update(Request $request, $id) {
        $this->validate($request, [
           'notification_title'=>'required|max:255',
          'status'=>'required'
        ]);

        $UserId = $request->session()->get('userData');
        $user_id = $UserId->id;
        $UserNotification = new \App\Models\UserNotification();

        /*$count = DB::table('country')->where('country_name', '=', $request->get('country_name'))->orWhere('country_code', '=', $request->get('country_code'))->where('id', '!=', $id)->count();
        if ($count == 0 || $count == 1) {*/
            $UserNotification->set_id($id);
            $UserNotification->set_notification_title($request->get('notification_title'));
            $UserNotification->set_status($request->get('status'));
            $UserNotification->set_updated_date(Helper::get_curr_datetime());
            $UserNotification->set_updated_by($user_id);
            $UserNotification->update_user_notification();
            
            return redirect('usernotification/manageusernotification');
        /*} else {
            $message = 'Country already exist.';
            return redirect('country/managecountry')->with('message', $message);
        }*/
    }
    /* End - This function for update user notification data by parsing id*/
    /*===================================================================*/
    
    /*
     * Request Parameter : [Apikey,userID]
     * Method : POST
     * Request Api Url : "/api/notificationlist"
     * Request Controller & Method : UserNotificationController/NotificationList
     * Success response : [ message : Success.,  code : 200, data: Array of Notification]
     * Error response : 
     * 1)[ message : Unauthorised Call. , code : 101]
       2)[ message : Notification doesnot avilable. , code : 101]
     */
   
    public function NotificationList(Request $request) {
        $Apikey = $request->Apikey;
        $userID = $request->userID;
        
        if ($Apikey == APIKEY) {
            $sql="
               SELECT 
                    `notification`.id,
                    `notification`.users_id,
                    `notification`.push_notification_id,
                    `notification`.notification_mode,
                    `user_notification`.notification_title
               FROM `notification`
               LEFT JOIN `user_notification` ON `user_notification`.id = `notification`.push_notification_id
               WHERE `notification`.users_id = $userID
               AND `user_notification`.status = 'Active'     
               ";
           $Push_notification =  DB::select( DB::raw($sql) );
           
            //$Push_notification = DB::table('notification')->where('users_id', $userID)->get();
           
            if (count($Push_notification) > 0) {
                $temp_array = [];
                foreach ($Push_notification as $notification) {
                    $notification->id = !empty($notification->id) ? (int) $notification->id : '';
                    $notification->users_id = !empty($notification->users_id) ? (int) $notification->users_id : '';
                    $notification->push_notification_id = !empty($notification->push_notification_id) ? (int) $notification->push_notification_id : '';
                    array_push($temp_array, $notification);
                }
                //echo '<pre>';
               // print_r($temp_array); exit;
                return response()->json(['message' => 'Success', 'code' => 200, 'data' => $Push_notification]);
            } else {
                return response()->json(['message' => 'Notification doesnot avilable', 'code' => 101]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    /*
     * Request Parameter : [Apikey, push_notification_id,userID, mode]
     * Method : POST
     * Request Api Url : "/api/changenotificationmode"
     * Request Controller & Method : UserNotificationController/changeNotificationMode
     * Success response : [ message : Notification mode change successfully.,  code : 200]
     * Error response : 
     * 1)[ message : Unauthorised Call. , code : 101]
       2)[ message : Notification mode doesnot change successfully. , code : 101]
     */
    public function changeNotificationMode(Request $request) {
        $Apikey = $request->Apikey;
        $push_notification_id = $request->push_notification_id;
        $userID = $request->userID;
        $Mode = $request->mode;
        //echo $Mode; exit;
        if ($Apikey == APIKEY) {
            $notification_update = DB::table('notification')
                                        ->where('users_id', $userID)
                                        ->where('push_notification_id', $push_notification_id)
                ->update([
                    'notification_mode' => $Mode,
                    'updated_date' => Helper::get_curr_datetime()
                ]);

            if($notification_update){
                return response()->json(['message' => 'Notification mode change successfully.', 'code' => 200]);
            }
            return response()->json(['message' => 'Notification mode doesnot change successfully.', 'code' => 101]);
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

}

<?php

namespace App\Http;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Illuminate\Support\Facades\validate;
use Illuminate\Support\Facades\Session;

class Helper {
    
    

    //============= start : Get Current url ============ //
    public static function GetCurrentUrl() {
        $url = url()->current();
        $UrlArray = array_reverse(explode('/', $url)); 
        return $UrlArray;
    }

    public static function get_curr_datetime($format = 'Y-m-d H:i:s') {
        date_default_timezone_set('UTC');
        return date($format, strtotime(date('Y-m-d H:i:s')));
    }

    // ======== Search data =====//
    public static function get_search_data($aColumns = array()) {
        $SearchType = 'ORLIKE';
        /*
         * Paging
         */
        $per_page = 10;
        $offset = 0;

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $per_page = $_GET['iDisplayLength'];
            $offset = $_GET['iDisplayStart'];
        }

        /*
         * Ordering
         */
        $order_by = "";
        $sort_order = "";
        if (isset($_GET['iSortCol_0'])) {
            $order_by = "";
            $sort_order = "";

            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $order_by = $aColumns[intval($_GET['iSortCol_' . $i])];
                    $sort_order = $_GET['sSortDir_' . $i];
                }
            }
        }

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $search_data = array();
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $search_data[$aColumns[$i]] = Helper::mysql_escape($_GET['sSearch']);
            }
            $SearchType = 'ORLIKE';
        }

        for ($i = 0; $i < count($aColumns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '' && $_GET['sSearch_' . $i] != '~') {
                $search_data[$aColumns[$i]] = Helper::mysql_escape($_GET['sSearch_' . $i]);
                $SearchType = 'ANDLIKE';
            }
        }

        $data = array();
        $data['order_by'] = $order_by;
        $data['sort_order'] = $sort_order;
        $data['search_data'] = $search_data;
        $data['per_page'] = $per_page;
        $data['offset'] = $offset;
        $data['SearchType'] = $SearchType;
        return $data;
    }

    // ======== Start : String escape  =====//
    public static function mysql_escape($inp) {
        if (is_array($inp))
            return array_map(__METHOD__, $inp);

        if (!empty($inp) && is_string($inp)) {
            return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
        }

        return $inp;
    }

    /* =============== Start : or Like Condition function ======================= */

    public static function or_like_search($data) {
        if (!empty($data)) {
            $i = 0;
            foreach ($data as $key => $value) {
                if ($i == 0) {
                    // pass the first element of the array
                    $sub = '(' . $key . ' LIKE \'%' . $value . '%\' ';
                } else {
                    //$this->db->or_like('Linkname', $search_string_multiple);
                    $sub .= 'OR ' . $key . ' LIKE \'%' . $value . '%\' ';
                }
                $i++;
            }
            $sub .= ')';
            return $sub;
        }
        return false;
    }

    /* =============== Start : And search function ======================= */

    public static function and_like_search($data) {
        if (!empty($data)) {
            $i = 0;
            $sub = '';
            $querystr = array();
            $query = '';
            foreach ($data as $key => $value) {
                if ($i == 0) {
                    $sub = '( ';
                } else {
                    $sub = 'AND ';
                }

                if (strtoupper($value['Operator']) == 'LIKE') {
                    $querystr[] = $value['Field'] . ' LIKE \'%' . $value['Value'] . '%\' ';
                } else if (strtoupper($value['Operator']) == 'RANGE') {
                    if (isset($value['Condition']) && !empty($value['Condition'])) {
                        foreach ($value['Condition'] as $val) {
                            $querystr[] = $val['Field'] . " " . $val['Operator'] . " '" . $val['Value'] . "' ";
                        }
                    }
                } else if (strtoupper($value['Operator']) == 'IN') {
                    $querystr[] = $value['Field'] . " " . $value['Operator'] . " (" . $value['Value'] . ") ";
                } else {
                    $querystr[] = $value['Field'] . " " . $value['Operator'] . " '" . $value['Value'] . "' ";
                }

                $i++;
            }

            if (isset($querystr) && !empty($querystr)) {
                $query = '( ' . implode(' AND ', $querystr) . ' )';
            }
            return $query;
        }
        return false;
    }

    //============= start : checked id Exist ============ //
    public static function check_id_exists($where = array(), $table = '') {
        if (isset($where) && count($where) > 0 && $table != '') {
            $fetchdata = DB::select("select * from $table Where id In($where)");

            if (count($fetchdata) > 0)
                return count($fetchdata);
        }
        return false;
    }

    //============== Start : Get Admin User List ============//
    public static function getAdminUserList(){
        $AdminUsers = DB::table('tbl_adminuser')->where('status', 'Active')->get();
        return $AdminUsers;
    }
    
    public static function timeAgo($time_start,$time_end) {

        $cur_time 	= $time_end;
        $time_elapsed 	= $cur_time - $time_start;
        $seconds 	= $time_elapsed ;
        $minutes 	= round($time_elapsed / 60 );
        $hours 		= round($time_elapsed / 3600);
        $days 		= round($time_elapsed / 86400 );
        $weeks 		= round($time_elapsed / 604800);
        $months 	= round($time_elapsed / 2600640 );
        $years 		= round($time_elapsed / 31207680 );
        $return=[];
        // Seconds
        /*if($seconds <= 60){
            $return= "$seconds seconds ago";
        }
        //Minutes
        else*/
        if($minutes <=60){
           $return= [$minutes,"minutes"];
        }
        //Hours
        else if($hours <=24 || $days <= 7 || $weeks <= 4.3 || $months <=12 || $years==1){
            
            if($hours){
                $return= $return= [$hours,"hours"];
            }else{
                $return= "$days days ago";
            }
        }
        //Days
        /*else if($days <= 7){
            if($days==1){
                $return= "yesterday";
            }else{
                $return= "$days days ago";
            }
        }
        //Weeks
        else if($weeks <= 4.3){
            if($weeks==1){
                $return= "a week ago";
            }else{
                $return= "$weeks weeks ago";
            }
        }
//Months
        else if($months <=12){
            if($months==1){
                $return= "a month ago";
            }else{
                $return= "$months months ago";
            }
        }
//Years
        else{
            if($years==1){
                $return= "one year ago";
            }else{
                $return= "$years years ago";
            }
        }*/

        return $return;
    }

    public static function dateDiff($time1, $time2, $precision = 6) {
        //https://www.if-not-true-then-false.com/2010/php-calculate-real-differences-between-two-dates-or-timestamps/
        // If not numeric then convert texts to unix timestamps
        if (!is_int($time1)) {
            $time1 = strtotime($time1);
        }
        if (!is_int($time2)) {
            $time2 = strtotime($time2);
        }

        // If time1 is bigger than time2
        // Then swap time1 and time2
        if ($time1 > $time2) {
            $ttime = $time1;
            $time1 = $time2;
            $time2 = $ttime;
        }

        // Set up intervals and diffs arrays
        $intervals = array('year','month','day','hour','minute','second');
        $diffs = array();

        // Loop thru all intervals
        foreach ($intervals as $interval) {
            // Create temp time from time1 and interval
            $ttime = strtotime('+1 ' . $interval, $time1);
            // Set initial values
            $add = 1;
            $looped = 0;
            // Loop until temp time is smaller than time2
            while ($time2 >= $ttime) {
                // Create new temp time from time1 and interval
                $add++;
                $ttime = strtotime("+" . $add . " " . $interval, $time1);
                $looped++;
            }

            $time1 = strtotime("+" . $looped . " " . $interval, $time1);
            $diffs[$interval] = $looped;
        }

        $count = 0;
        $times = array();
        // Loop thru all diffs
        foreach ($diffs as $interval => $value) {
            // Break if we have needed precission
            if ($count >= $precision) {
                break;
            }
            // Add value and interval
            // if value is bigger than 0
            if ($value > 0) {
                // Add s if value is not 1
                if ($value != 1) {
                    $interval .= "s";
                }
                // Add value and interval to times array
                $times[] = $value . " " . $interval;
                $count++;
            }
        }

        // Return string with times
        return implode(", ", $times);
    }
    
    
}

?>  
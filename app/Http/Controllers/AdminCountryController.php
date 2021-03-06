<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\validate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Http\Helper;
use App\Models\EmailTemplate;
use Mail;

class AdminCountryController extends Controller {

    public  $param=array();
    
    public function __construct() {
        parent::login_user_details();
    }
    
    /* Start - this function for redirect on country list*/
    public function countryList() {
        
        $Country = new \App\Models\Country();
        return view('country/countryList');
    }
    /* End - this function for redirect on country list*/
    
    /* Start - this function for get country list using datatable*/
    public function AjaxCountryList($order_by = "id", $sort_order = "asc", $search = "all", $offset = 0) {
        
        $Country = new \App\Models\Country();

        $aColumns = array('id', 'country_name', 'country_code' , 'status');
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
        
        $Country->set_data($data);
        $Country->set_SearchType($SearchType);
        $Country->set_order_by($order_by);
        $Country->set_sort_order($sort_order);
        $Country->set_limit($limit);
        $Country->set_offset($offset);
        $result = $Country->get_data_for_country();

       
        $data = array();
        if (count($result) > 0) {
            $data['result'] = $result;
            $data['totalRecord'] = $Country->count_all_contact_us_grid();
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
                    $row[] = $result_row->country_name;
                    $row[] = $result_row->country_code;
                    $row[] = $result_row->status;
                    //$row[] = array();
                    $output['aaData'][] = $row;
                }
                
            }
        }
        //print_r(json_encode($output));exit;
        echo json_encode($output);
    }
    /* End - this function for get country list using datatable*/
    
    /* Start this is halping function for data table and & like query*/
    public function trim_serach_data($search_data, $SearchType) {
       $QueryStr = array();

        if (!empty($search_data)) {
            if ($SearchType == 'ANDLIKE') {
                $i = 0;
                foreach ($search_data as $key => $val) {
                    if ($key == 'country_name' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'country_name';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'country_code' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'country_code';
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
    public function activeInactivecountry(Request $request) {
        
        $Country = new \App\Models\Country();
        $id = $request->id;
        $status = $request->status;
        $mode = $request->mode;
        
        //Code to check if the id from the url exists in the table or not starts here. 
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'country');
        if (!$id_exists) {
            return view('error.404');
        }
        
        //Code to check id ends here
        $Update_status = $Country->update_status_of_country($id,$status);
        
        if (count($Update_status) == 1) {
            echo count($Update_status);
        } else {
            echo 0;
        }
        exit;
    }
    /* End - Thiss function for active and inactive status*/
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletecountry(Request $request) {
        $Country = new \App\Models\Country();
        $id = $request->id;
        //Code to check if the id from the url exists in the table or not starts here. 
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'country');
        if (!$id_exists) {
            return view('error.404');
        }
        //Code to check id ends here
        $del_user = $Country->delete_record_of_country_id_ids($id);
        //$del_user = DB::delete('DELETE FROM `contact_us` WHERE id IN (' . $id . ')');

        if ($del_user) {
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
    
    /*Start - this function for redirect add form*/
    public function createNewCountry(){
         return view('country/createCountry');
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
          'country_name'=>'required|max:255',
          'country_code'=>'required|max:255',
          'status'=>'required'
          ]);
         
         $Country = new \App\Models\Country();
         
         $UserId = $request->session()->get('userData');
         $user_id = $UserId->id;
        
         $count = DB::table('country')->where('country_name', '=', $request->country_name)->orWhere('country_code', '=', $request->country_code)->count();
        if ($count == 0) {
            $Country->set_country_name($request->country_name);
            $Country->set_country_code($request->country_code);
            $Country->set_status($request->status);
            $Country->set_created_date(date('Y-m-d H:i:s'));
            $Country->set_updated_date(date('Y-m-d H:i:s'));
            $Country->set_created_by($user_id);
            $Country->set_updated_by('');
            $Country->insert_country();
         
            return redirect('country/managecountry');
        }else {
            $message = 'Country already exist.';
            return redirect('country/managecountry')->with('message', $message);
        }
    }
    /* Start - This function for check exist country name record when do add / edit action*/
    public function CheckCountryNameExist(Request $request) {
        $country_name = $request->country_name;

        $id = $request->id;
        if ($id != '') {
            $CountryResArray = DB::table('country')->where('country_name', '=', $country_name)->where('id', '!=', $id)->first();
        } else {
            $CountryResArray = DB::table('country')->where('country_name', '=', $country_name)->first();
        }

        if (count($CountryResArray) > 0) {
            echo '1';
            die;
        } else {
            echo '0';
            die;
        }
    }
    /* End - This function for check exist country name record when do add / edit action*/
    
    /* Start - This function for check exist country code record when do add / edit action*/
    public function CheckCountryCodeExist(Request $request) {
        $country_code = $request->country_code;

        $id = $request->id;
        if ($id != '') {
            $CountryResArray = DB::table('country')->where('country_code', '=', $country_code)->where('id', '!=', $id)->first();
        } else {
            $CountryResArray = DB::table('country')->where('country_code', '=', $country_code)->first();
        }

        if (count($CountryResArray) > 0) {
            echo '1';
            die;
        } else {
            echo '0';
            die;
        }
    }
    /* End - This function for check exist country name record when do add / edit action*/
    
    /* Start - This function for dedirection on edit country page*/
    public function editCountry($id){
        $country = DB::table('country')->where('id', $id)->first();
        return view('country/editcountry', ['country' => $country]);
    }
    /* End - This function for dedirection on edit country page*/
    
    /* Start - This function for update country data by parsing id*/
    public function update(Request $request, $id) {
        $this->validate($request, [
           'country_name'=>'required|max:255',
          'country_code'=>'required|max:255',
          'status'=>'required'
        ]);

        $UserId = $request->session()->get('userData');
        $user_id = $UserId->id;
        $Country = new \App\Models\Country();

        $count = DB::table('country')->where('country_name', '=', $request->get('country_name'))->orWhere('country_code', '=', $request->get('country_code'))->where('id', '!=', $id)->count();
        if ($count == 0 || $count == 1) {
            $Country->set_id($id);
            $Country->set_country_name($request->get('country_name'));
            $Country->set_country_code($request->get('country_code'));
            $Country->set_status($request->get('status'));
            $Country->set_updated_date(Helper::get_curr_datetime());
            $Country->set_updated_by($user_id);
            $Country->update_country();
            
            return redirect('country/managecountry');
        } else {
            $message = 'Country already exist.';
            return redirect('country/managecountry')->with('message', $message);
        }
    }
    /* End - This function for update country data by parsing id*/
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\validate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Http\Helper;
use App\Models\EmailTemplate;
use Mail;

class AdminStateController extends Controller {

    public  $param=array();
    
    public function __construct() {
        parent::login_user_details();
    }
    
    /* Start - this function for redirect on state list*/
    public function stateList() {
        return view('state/stateList');
    }
    /* End - this function for redirect on state list*/
    
    /* Start - this function for get state list using datatable*/
    public function AjaxStateList($order_by = "id", $sort_order = "asc", $search = "all", $offset = 0) {
        
        $State = new \App\Models\State();

        $aColumns = array('id', 'country_name', 'state_name' , 'status');
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
        
        $State->set_data($data);
        $State->set_SearchType($SearchType);
        $State->set_order_by($order_by);
        $State->set_sort_order($sort_order);
        $State->set_limit($limit);
        $State->set_offset($offset);
        $result = $State->get_data_for_state();
        
        /*echo '<pre>';
        print_r($result);exit;*/
       
        $data = array();
        if (count($result) > 0) {
            $data['result'] = $result;
            $data['totalRecord'] = $State->count_all_state_grid();
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
                    $row[] = $result_row->state_name;
                    $row[] = $result_row->status;
                    //$row[] = array();
                    $output['aaData'][] = $row;
                }
                
            }
        }
        //print_r(json_encode($output));exit;
        echo json_encode($output);
    }
    /* End - this function for get state list using datatable*/
    
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
                    if ($key == 'state_name' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'state_name';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'status' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'state.status';
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
    public function activeInactivestate(Request $request) {
        
        $State = new \App\Models\State();
        $id = $request->id;
        $status = $request->status;
        $mode = $request->mode;
        
        //Code to check if the id from the url exists in the table or not starts here. 
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'state');
        if (!$id_exists) {
            return view('error.404');
        }
        
        //Code to check id ends here
        $Update_status = $State->update_status_of_state($id,$status);
        
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
    public function deletestate(Request $request) {
        $State = new \App\Models\State();
        $id = $request->id;
        //Code to check if the id from the url exists in the table or not starts here. 
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'state');
        if (!$id_exists) {
            return view('error.404');
        }
        //Code to check id ends here
        $del_user = $State->delete_record_of_state_id_ids($id);

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
    
    /*Start - this function for redirect add form with country list*/
    public function createNewstate(){
         $Country = new \App\Models\Country();
         $country_list = $Country->get_all_active_country_list();
         return view('state/createNewstate', ['country' => $country_list]);
    }
    /*End - this function for redirect add form with country list*/
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
         $this->validate($request,[
          'country_name'=>'required',
          'state_name'=>'required|max:255',
          'status'=>'required'
          ]);
         
         $State = new \App\Models\State();
         
         $UserId = $request->session()->get('userData');
         $user_id = $UserId->id;
        
         $count = DB::table('state')->where('state_name', '=', $request->state_name)->count();
        if ($count == 0) {
            $State->set_country_id($request->country_name);
            $State->set_state_name($request->state_name);
            $State->set_status($request->status);
            $State->set_created_date(date('Y-m-d H:i:s'));
            $State->set_updated_date(date('Y-m-d H:i:s'));
            $State->set_created_by($user_id);
            $State->set_updated_by('');
            $State->insert_state();
         
            return redirect('state/managestate');
        }else {
            $message = 'State already exist.';
            return redirect('state/managestate')->with('message', $message);
        }
    }
    /* Start - This function for check exist state name record when do add / edit action*/
    public function CheckStateNameExist(Request $request) {
        $state_name = $request->state_name;

        $id = $request->id;
        if ($id != '') {
            $StateResArray = DB::table('state')->where('state_name', '=', $state_name)->where('id', '!=', $id)->first();
        } else {
            $StateResArray = DB::table('state')->where('state_name', '=', $state_name)->first();
        }

        if (count($StateResArray) > 0) {
            echo '1';
            die;
        } else {
            echo '0';
            die;
        }
    }
    /* End - This function for check exist state name record when do add / edit action*/
    
    
    
    /* Start - This function for dedirection on edit country page*/
    public function editState($id){
        $Country = new \App\Models\Country();
        $country_list = $Country->get_all_active_country_list();
        $state = DB::table('state')->where('id', $id)->first();
        return view('state/editState', ['country' => $country_list,'state'=>$state]);
    }
    /* End - This function for dedirection on edit country page*/
    
    /* Start - This function for update country data by parsing id*/
    public function update(Request $request, $id) {
        $this->validate($request,[
          'country_name'=>'required',
          'state_name'=>'required|max:255',
          'status'=>'required'
          ]);
        
        
        $UserId = $request->session()->get('userData');
        $user_id = $UserId->id;
        $State = new \App\Models\State();

        $count = DB::table('state')->where('state_name', '=', $request->get('state_name'))->where('id', '!=', $id)->count();
        if ($count == 0 || $count == 1) {
            $State->set_id($id);
            $State->set_country_id($request->get('country_name'));
            $State->set_state_name($request->get('state_name'));
            $State->set_status($request->get('status'));
            $State->set_updated_date(Helper::get_curr_datetime());
            $State->set_updated_by($user_id);
            $State->update_state();
            
            return redirect('state/managestate');
        } else {
            $message = 'State already exist.';
            return redirect('state/managestate')->with('message', $message);
        }
    }
    /* End - This function for update country data by parsing id*/
    
    /*Start - this function for get state by country id*/
    public function get_all_state_by_country_id(Request $request){
        $State = new \App\Models\State();
        $country_id =  $request->country_id;
        $State_list = $State->get_state_list_by_country_id($country_id);

        
        if(isset($State_list) && !empty($State_list)){
                $msg = 'TRUE';
                return response()->json(['message' => $msg, 'data' => $State_list]);
        }  else {
                return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
       
    }
    /*End - this function for get state by country id*/
}

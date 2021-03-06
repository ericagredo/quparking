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
use Mail;

class AdminUsersController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //  $this->middleware('auth');
        //$this->user = $guard->user();
    }

    public function index() {
        return view('users/adminUsersList');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show() {
        $AdminuserList = DB::table('users')->get();
        return view('users/adminUsersList', ['adminUserList' => $AdminuserList]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('users/createadminUsers');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'username' => 'required|max:255',
            'email' => 'required|max:255',
            'password' => 'required|max:255',
            'contact_number' => 'required',
           /* 'location' => 'required',
            'country_name' => 'required', */
            'zipcode' => 'required',
            'status' => 'required'
        ]);

        $UserId = $request->session()->get('userData');
        $user_id = $UserId->id;

        $imageName = '';
        if ($request->profile_image != '') {
            $imageName = time() . '.' . $request->profile_image->getClientOriginalExtension();
            $request->profile_image->move(public_path('uploads/user_profile_image'), $imageName);
        }
        
        $count = DB::table('users')->where('email', '=', $request->email)->orWhere('contact_number', '=', $request->contact_number)->count();
        if ($count == 0 ) {
            $users = DB::table('users')->insertGetId(
                    [
                        'firstname' => $request->first_name,
                        'lastname' => $request->last_name,
                        'username' => $request->username,
                        'email' => $request->email,
                        'password' => md5($request->password),
                        'status' => $request->status,
                        'profile_image' => $imageName,
                        'contact_number' => $request->contact_number,
                       // 'gender' => $request->gender,
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude,
                        'location' => $request->location,
                        'country_name' => $request->country_name,
                        'zipcode' => $request->zipcode,
                        'created_at' => Helper::get_curr_datetime(),
                        'created_by' => $user_id
                    ]
            );

            return redirect('users/adminUsersList');
        } else {
            $message = 'Users already exist.';
            return redirect('users/adminUsersList')->with('message', $message);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $Country = new \App\Models\Country();
        $user = DB::table('users')->where('id', $id)->first();
        $country_list = $Country->get_all_active_country_list();
        /*echo '<pre>';
        print_r($user);die;*/
        return view('users/editadminUsers', ['user' => $user, 'country' => $country_list]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminreate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            /*'username' => 'required|max:255',*/
            'email' => 'required|max:255',
            'contact_number' => 'required',
            /* 'location' => 'required', */
            'country_id' => 'required',
            'zipcode' => 'required',
            'status' => 'required'
        ]);

        $UserId = $request->session()->get('userData');
        $user_id = $UserId->id;
        
        $edited_res = DB::table('users')->where('id', '=', $id)->first();

        $imageName = $edited_res->profile_image;
        if ($request->profile_image != '') {
            $imageName = time() . '.' . $request->profile_image->getClientOriginalExtension();
            $request->profile_image->move(public_path('uploads/user_profile_image'), $imageName);
        }


        $count = DB::table('users')->where('email', '=', $request->get('email'))->orWhere('contact_number', '=', $request->get('contact_number'))->where('id', '!=', $id)->count();
        /*echo '<pre>';
        print_r($count);
        echo '</pre>';
        exit;*/
        if ($count == 0 || $count == 1) {
            $users = DB::table('users')->where('id', $id)->update([
                'firstname' => $request->get('first_name'),
                'lastname' => $request->get('last_name'),
                'username' => $request->get('username'),
                'email' => $request->get('email'),
                'contact_number' => $request->get('contact_number'),
                'status' => $request->get('status'),
                'profile_image' => $imageName,
                //'gender' => $request->get('gender'),
                'latitude' => $request->get('latitude'),
                'longitude' => $request->get('longitude'),
                'location' => $request->get('location'),
                'country_id' => $request->get('country_id'),
                'zipcode' => $request->get('zipcode'),   
                'updated_at' => Helper::get_curr_datetime(),
                'updated_by' => $user_id
            ]);
            
            return redirect('users/adminUsersList');
        } else {
            $message = 'Users already exist.';
            return redirect('users/adminUsersList')->with('message', $message);
        }
    }

    public function ajaxadminUsersList($order_by = "id", $sort_order = "asc", $search = "all", $offset = 0) {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */

        $aColumns = array('id', 'user_name'/*, 'firstname', 'lastname' ,'username'*/, 'email','location', 'status', 'contact_number', '');
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

        $query = "select users.*, CONCAT(`firstname`, ' ', `lastname`) as user_name from users";
       

        if ($SearchType == 'ORLIKE') {
            $likeStr = Helper::or_like_search($data);
        }
        if ($SearchType == 'ANDLIKE') {
            $likeStr = Helper::and_like_search($data);
        }

        if ($likeStr) {
            $query .= ' having ' . $likeStr;
        }

        $query .= ' order by ' . $order_by . ' ' . $sort_order;
        $query .= ' limit ' . $limit . ' OFFSET ' . $offset;
        $result = DB::select($query);

        $data = array();
        if (count($result) > 0) {
            $data['result'] = $result;
            $data['totalRecord'] = $this->count_all_users_grid($search_data, $SearchType);
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
                    /*$row[] = $result_row->firstname;
                    $row[] = $result_row->lastname;*/
                    $row[] = $result_row->user_name;
                    $row[] = $result_row->email;
                    $row[] = $result_row->location;
                    $row[] = $result_row->status;
                    $row[] = $result_row->contact_number;
                    $row[] = !empty($result_row->profile_image) ? asset('uploads/user_profile_image/' . $result_row->profile_image) : '';
                    $row[] = array();
                    $output['aaData'][] = $row;
                }
            }
        }
        //print_r(json_encode($output));exit;
        echo json_encode($output);
    }

    /* =============== Start : Trim search function ======================= */

    public function trim_serach_data($search_data, $SearchType) {
        $QueryStr = array();

        if (!empty($search_data)) {
            if ($SearchType == 'ANDLIKE') {
                $i = 0;
                foreach ($search_data as $key => $val) {
                    if ($key == 'user_name' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'user_name';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    /*if ($key == 'firstname' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'firstname';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'lastname' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'lastname';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }*/
                    /*if ($key == 'username' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'username';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }*/
                    if ($key == 'email' && !empty($val)) {
                        $email = str_replace('%40', '@', $val);
                        $QueryStr[$i]['Field'] = 'email';
                        $QueryStr[$i]['Value'] = $email;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'location' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'location';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'status' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'status';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = '=';
                    }
                    if ($key == 'contact_number' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'contact_number';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = '=';
                    }
                    $i++;
                }
            } 
        }
        return $QueryStr;
    }

    // =========== Start :  Count all Record in grid data =========//
    public function count_all_users_grid($search_data, $SearchType) {
        $data = $this->trim_serach_data($search_data, $SearchType);

        $query = "select users.*, CONCAT(`firstname`, ' ', `lastname`) as user_name from users";

        if ($SearchType == 'ORLIKE') {
            $likeStr = Helper::or_like_search($data);
        }
        if ($SearchType == 'ANDLIKE') {
            $likeStr = Helper::and_like_search($data);
        }

        if ($likeStr) {
            $query .= ' having ' . $likeStr;
        }

        $result = DB::select($query);
        if (count($result) > 0) {
            return count($result);
        }
        return 0;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        $id = $request->id;

        //Code to check if the id from the url exists in the table or not starts here. 
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'users');
        if (!$id_exists) {
            return view('error.404');
        }
        //Code to check id ends here
        
        $Paymentdata = DB::delete("DELETE FROM payment_history Where users_id In($id)");
        
        $fetchdata = DB::select("select * from users Where id In($id)");
        $fetchprofileimage = $fetchdata[0]->profile_image;
        
        
        if(file_exists( public_path() . '/uploads/user_profile_image/' . $fetchprofileimage)) {
             unlink(public_path('uploads/user_profile_image/' . $fetchprofileimage));
        }
        
        $del_user = DB::delete('DELETE FROM `users` WHERE id IN (' . $id . ')');

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
    public function activeInactiveadminUsers(Request $request) {
        $id = $request->id;
        $status = $request->status;
        $mode = $request->mode;

        //Code to check if the id from the url exists in the table or not starts here. 
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'users');
        if (!$id_exists) {
            return view('error.404');
        }

        //Code to check id ends here
        $Update_status = DB::update('update users set status = "' . $status . '" Where id IN (' . $id . ')');

        if (count($Update_status) == 1) {
            echo count($Update_status);
        } else {
            echo 0;
        }
        exit;
    }

    public function checkUserEmailExist(Request $request) {
        $email = $request->email;

        $id = $request->id;
        if ($id != '') {
            $UserArray = DB::table('users')->where('email', '=', $email)->where('id', '!=', $id)->first();
        } else {
            $UserArray = DB::table('users')->where('email', '=', $email)->first();
        }

        if (count($UserArray) > 0) {
            echo '1';
            die;
        } else {
            echo '0';
            die;
        }
    }

    public function checkUsernameExist(Request $request) {
        $username = $request->username;

        $id = $request->id;
        if ($id != '') {
            $UserResArray = DB::table('users')->where('username', '=', $username)->where('id', '!=', $id)->first();
        } else {
            $UserResArray = DB::table('users')->where('username', '=', $username)->first();
        }

        if (count($UserResArray) > 0) {
            echo '1';
            die;
        } else {
            echo '0';
            die;
        }
    }

}
